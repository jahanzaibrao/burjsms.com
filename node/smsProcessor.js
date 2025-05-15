"use strict";
import { env, tsToDate, entities, getCoverageByMsisdn } from "./miscHelper.js";
import { dbquery } from "./mariadbHelper.js";
import { client } from "./nodeAsyncRedis.js";
import { EOL } from "os";
import stringify from "fast-stringify";

let processClientSmsBatch = async (smsbatch, batchinfo) => {
	let clientinfo = await dbquery("getSmppclientInfo", {
		smppclient: batchinfo.key,
	});
	if (clientinfo == undefined) return;
	console.log(`Process started for client ${batchinfo.key} with Plan ${clientinfo.plan_id}`);
	if (clientinfo.plan_id == 0) {
		await processCreditBasedBatch(smsbatch, batchinfo.key, clientinfo);
	} else {
		await processCurrencyBasedBatch(smsbatch, batchinfo.key, clientinfo);
	}

	//delete the batch
	await client.zremrangebyscore(batchinfo.key, batchinfo.min, batchinfo.max);
	return `Deleted batch from REDIS. Key: ${batchinfo.key} Range: ${batchinfo.min} to ${batchinfo.max}`;
};

let processCreditBasedBatch = async (smsbatch, smppclient, clientinfo) => {
	const userInfo = await getUserPermissions(clientinfo.user_id);
	let available_credits = 0;
	let userCreditObject = {};
	let sortedbatch;
	let credits;
	let routeInfo;
	let userCreditInfo;
	let walletData;
	if (userInfo.account_type == 0) {
		//flat credit account
		userCreditInfo = await dbquery("getCreditDataBySystemid", {
			systemid: smppclient,
		});
		[[sortedbatch, credits], routeInfo] = await Promise.all([
			sortBatches(smsbatch, 1, {
				cost_type: "credit",
				price_per_sms: userCreditInfo.price,
			}),
			getRouteDetails(clientinfo.route_id),
		]);
		available_credits = userCreditInfo.credits;
		userCreditObject = userCreditInfo;
	} else {
		//dynamic credit account
		userCreditInfo = await dbquery("getPerSmsPriceByUser", {
			user: clientinfo.user_id,
			route: clientinfo.route_id,
		});
		[[sortedbatch, credits], routeInfo, walletData] = await Promise.all([
			sortBatches(smsbatch, 1, {
				cost_type: "currency",
				price_per_sms: userCreditInfo.price,
			}),
			getRouteDetails(clientinfo.route_id),
			dbquery("getWalletCredits", { user: clientinfo.user_id }),
		]);
		const walletCredits = walletData.amount;
		available_credits = walletCredits;
		userCreditObject = {
			delv_per: userCreditInfo.delv_per,
			walletCredits: walletCredits,
		};
	}

	//the sms batch is now sorted into shoot_id. Check if sufficient credits is available in customer account
	let shootid_array = Object.keys(sortedbatch);
	if (credits > available_credits) {
		//insufficient credits, reject all the SMS in this batch
		await saveCreditRejectedCampaign(sortedbatch, shootid_array, {
			smppclient: smppclient,
			userid: userCreditInfo.user_id,
			planid: 0,
			routeid: routeInfo.route_id,
			smsc: routeInfo.smsc,
			tlv: routeInfo.tlv,
		});
	} else {
		//good to go. perform all the tasks before submitting the campaign to operator
		await performCampaignTasks(sortedbatch, shootid_array, {
			smppclient: smppclient,
			userInfo: userInfo,
			planid: 0,
			routeInfo: routeInfo,
			userCreditInfo: userCreditObject,
			cost_type: userInfo.account_type == 0 ? "credit" : "currency",
		});
	}
	return;
};

let processCurrencyBasedBatch = async (smsbatch, smppclient, clientinfo) => {
	const plandata = await dbquery("getMccMncPlanData", {
		planid: clientinfo.plan_id,
	});

	const [[routewiseSortedBatch, totalCost], userInfo, walletData] = await Promise.all([
		sortBatchesByRoute(smsbatch, plandata),
		getUserPermissions(clientinfo.user_id),
		dbquery("getWalletCredits", { user: clientinfo.user_id }),
	]);
	const walletCredits = walletData.amount;
	//check if sufficient credits
	console.log(routewiseSortedBatch);
	let routeIdArray = Object.keys(routewiseSortedBatch);
	let taskArray = [];
	let task = totalCost > walletCredits ? "reject" : "submit";
	for (const routeid of routeIdArray) {
		console.log(routeid)
		taskArray.push(
			mccmncCampaignTaskPerRoute(task, routeid, routewiseSortedBatch[routeid], {
				smppclient: smppclient,
				plandata: plandata,
				userInfo: userInfo,
				walletCredits: walletCredits,
			})
		);
	}
	await Promise.all(taskArray);
	return;
};

let mccmncCampaignTaskPerRoute = async (task, routeid, smsbatch, info) => {
	//get route info
	const [[sortedbatch], routeData] = await Promise.all([
		sortBatches(smsbatch, 0, { cost_type: "currency", price_per_sms: 0 }),
		getRouteDetails(routeid),
	]);
	let shootid_array = Object.keys(sortedbatch);
	if (task == "reject") {
		await saveCreditRejectedCampaign(sortedbatch, shootid_array, {
			smppclient: info.smppclient,
			userid: info.userInfo.user_id,
			planid: info.plandata.id,
			routeid: routeid,
			smsc: routeData.smsc,
			tlv: routeData.tlv,
		});
	} else {
		await performCampaignTasks(sortedbatch, shootid_array, {
			smppclient: info.smppclient,
			userInfo: info.userInfo,
			planid: info.plandata.id,
			routeInfo: routeData,
			userCreditInfo: {
				delv_per: 100,
				walletCredits: info.walletCredits,
			},
			cost_type: "currency",
		});
	}
};

let saveCreditRejectedCampaign = async (sortedbatch, shootid_array, { smppclient, userid, planid, routeid, smsc, tlv }) => {
	let savecontacts = new Set(); //merge all contacts separated by shoot id into this set
	let batchid = Math.random().toString(36).substring(3);
	for (let i = 0, mainbatchlen = shootid_array.length; i < mainbatchlen; ++i) {
		let shootid = shootid_array[i];
		let shoot_batch = sortedbatch[shootid];
		savecontacts = [...savecontacts, ...shoot_batch];
	}
	//prepare the query as well as response PDU for each sms in this batch
	let data = {
		userid: userid,
		smppclient: smppclient,
		batchid: batchid,
		routeInfo: {
			routeid: routeid,
			planid: planid,
			smsc: smsc,
			tlv: tlv,
		},
		dlr: {
			status: 1,
			dlr: 16,
			smpp_code: "NACK",
			vendor_dlr: env.SMPP_CRD_ERR,
		},
		mode: {
			result: "FAIL",
			reason: env.SMPP_CRD_DESC,
		},
	};
	await saveCampaignInDb(savecontacts, data);
	return;
};

let performCampaignTasks = async (sortedbatch, shootid_array, { smppclient, userInfo, planid, routeInfo, userCreditInfo, cost_type }) => {
	let creditsCharged = 0;
	let whitelist_nums = [];
	if (userCreditInfo.delv_per < 100) {
		//get the whitelist contact for this user
		let client_wl = await dbquery("getClientWhitelistNumbers", {
			user: userInfo.user_id,
		});
		whitelist_nums = client_wl == undefined ? [] : client_wl.mobiles.split(",");
	}
	let [sendContacts, invalidContacts, ndncContacts, fakedelContacts, fakeundelContacts, fakeexpContacts] = Array(6).fill(new Array());
	for (let i = 0, mainbatchlen = shootid_array.length; i < mainbatchlen; ++i) {
		let shootid = shootid_array[i];
		let smsbatch = sortedbatch[shootid];
		let firstSmsMccmnc = !smsbatch[0].mccmnc ? 0 : smsbatch[0].mccmnc;
		let [firstSmsObj] = composeSmsObject(smsbatch[0], {
			routeid: routeInfo.route_id,
			planid: planid,
			smsc: routeInfo.smsc,
			tlv: routeInfo.tlv,
			mccmnc: firstSmsMccmnc,
		});

		//check if any flag causes this batch to be rejected entirely
		let allFlags = await Promise.all([
			checkSmsPermission(firstSmsObj, userInfo),
			checkValidTiming(firstSmsObj, routeInfo),
			checkValidSender(firstSmsObj.senderid, {
				maxlength: routeInfo.max_sender_length,
				senderType: routeInfo.senderid_type,
				userid: userInfo.user_id,
			}),
			checkValidTemplate(firstSmsObj.sms_text, userInfo, routeInfo),
			isSpamFreeText(firstSmsObj.sms_text, userInfo.spam_flag),
		]);
		

		if (allFlags.flat().includes(false)) {
			//reject campaign and queue proper dlr response
			let reason = getCampaignRejectionReason(allFlags.flat());
			let info = {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				smppclient: smppclient,
				batchid: shootid,
				reason: reason,
				tlv: routeInfo.tlv,
				result: "FAIL",
			};
			await saveSmsBatch(smsbatch, info);
		} else {
			//proceed by applying dlr cutting and submitting the campaign
			let validationInfo = {
				cost_type: cost_type,
				validLenths: routeInfo.valid_lengths,
				prefixInfo: {
					flag: routeInfo.add_prefix,
					prefix: routeInfo.country_prefix,
				},
				blackList: {
					table: routeInfo.blacklist_tablename,
					column: routeInfo.blacklist_tablecol,
				},
			};
			let [filtered, invalids, blacklists, creditsToDeduct] = await validateEachContact(smsbatch, validationInfo);
			let cuttingInfo = {
				totalsize: filtered.length,
				percent: userCreditInfo.delv_per,
				whitelist: whitelist_nums,
			};

			let [toBeSent, fakeDels, fakeUndels, fakeExps] = applyDlrCutting(filtered, cuttingInfo);

			//merge into main array
			sendContacts = [...sendContacts, ...toBeSent];
			fakedelContacts = [...fakedelContacts, ...fakeDels];
			fakeundelContacts = [...fakeundelContacts, ...fakeUndels];
			fakeexpContacts = [...fakeexpContacts, ...fakeExps];
			invalidContacts = [...invalidContacts, ...invalids];
			ndncContacts = [...ndncContacts, ...blacklists];
			creditsCharged += creditsToDeduct;
		}
	}
	//deduct credits and submit campaign
	let promiseSet = new Set(); //Iterator with promises
	if (!userCreditInfo.walletCredits) {
		//credit based account
		promiseSet.add(
			dbquery("updateCredits", {
				credits: parseInt(userCreditInfo.credits - creditsCharged),
				user: userInfo.user_id,
				route: routeInfo.route_id,
			})
		);
		promiseSet.add(
			dbquery("creditsLogEntry", {
				user: userInfo.user_id,
				amount: 0 - creditsCharged,
				route: routeInfo.route_id,
				before: userCreditInfo.credits,
				after: parseInt(userCreditInfo.credits - creditsCharged),
				batchid: shootid_array.join("-"),
			})
		);
	} else {
		//wallet based account
		promiseSet.add(
			dbquery("updateWalletCredits", {
				credits: parseFloat(userCreditInfo.walletCredits - creditsCharged),
				user: userInfo.user_id,
			})
		);
		promiseSet.add(
			dbquery("creditsLogEntry", {
				user: userInfo.user_id,
				amount: 0 - creditsCharged,
				route: 0,
				before: userCreditInfo.walletCredits,
				after: parseFloat(userCreditInfo.walletCredits - creditsCharged),
				batchid: shootid_array.join("-"),
			})
		);
	}

	//save sent campaign
	if (sendContacts.length > 0) {
		promiseSet.add(
			saveSmsBatch(sendContacts, {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
				smppclient: smppclient,
				batchid: shootid_array.join("-"),
				reason: {
					state: 1,
					dlr: 0,
					smpp_code: "ENROUTE",
					vendor_dlr: "",
					err_desc: "",
				},
				result: "SEND",
			})
		);
		//send campaign to sqlbox
		let sqlboxInfo = {
			userid: userInfo.user_id,
			smppclient: smppclient,
			routeInfo: {
				routeid: routeInfo.route_id,
				planid: 0,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
			},
		};
		promiseSet.add(addToSqlbox(sendContacts, sqlboxInfo));
	}

	//save fake delivered
	if (fakedelContacts.length > 0) {
		promiseSet.add(
			saveSmsBatch(fakedelContacts, {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
				smppclient: smppclient,
				batchid: shootid_array.join("-"),
				reason: {
					state: 2,
					dlr: 1,
					smpp_code: "DELIVRD",
					vendor_dlr: "FDEL",
					err_desc: "",
				},
				result: "CUTTING",
			})
		);
	}
	//save fake undelivered
	if (fakeundelContacts.length > 0) {
		promiseSet.add(
			saveSmsBatch(fakeundelContacts, {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
				smppclient: smppclient,
				batchid: shootid_array.join("-"),
				reason: {
					state: 2,
					dlr: 8,
					smpp_code: "UNDELIV",
					vendor_dlr: "FUNDEL",
					err_desc: "",
				},
				result: "CUTTING",
			})
		);
	}
	//save fake expired
	if (fakeexpContacts.length > 0) {
		promiseSet.add(
			saveSmsBatch(fakeexpContacts, {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
				smppclient: smppclient,
				batchid: shootid_array.join("-"),
				reason: {
					state: 2,
					dlr: 16,
					smpp_code: "REJECTD",
					vendor_dlr: "FEXP",
					err_desc: "",
				},
				result: "CUTTING",
			})
		);
	}

	//save invalid numbers
	if (invalidContacts.length > 0) {
		promiseSet.add(
			saveSmsBatch(invalidContacts, {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
				smppclient: smppclient,
				batchid: shootid_array.join("-"),
				reason: {
					state: 1,
					dlr: 2,
					smpp_code: "NACK",
					vendor_dlr: env.SMPP_INVALID_ERR,
					err_desc: env.SMPP_INVALID_DESC,
				},
				result: "FAIL",
			})
		);
	}
	//save ndnc numbers
	if (ndncContacts.length > 0) {
		promiseSet.add(
			saveSmsBatch(ndncContacts, {
				userid: userInfo.user_id,
				routeid: routeInfo.route_id,
				smsc: routeInfo.smsc,
				tlv: routeInfo.tlv,
				smppclient: smppclient,
				batchid: shootid_array.join("-"),
				reason: {
					state: 1,
					dlr: 16,
					smpp_code: "REJECTD",
					vendor_dlr: env.SMPP_DND_ERR,
					err_desc: env.SMPP_DND_DESC,
				},
				result: "FAIL",
			})
		);
	}

	await Promise.all(promiseSet);
	return;
};

let saveSmsBatch = async (smsbatch, info) => {
	let data = {
		userid: info.userid,
		smppclient: info.smppclient,
		batchid: info.batchid,
		routeInfo: {
			routeid: info.routeid,
			planid: 0,
			smsc: info.smsc,
			tlv: info.tlv,
		},
		dlr: {
			status: info.reason.state,
			dlr: info.reason.dlr,
			smpp_code: info.reason.smpp_code,
			vendor_dlr: info.reason.vendor_dlr,
		},
		mode: {
			result: info.result,
			reason: info.reason.err_desc,
		},
	};
	await saveCampaignInDb(smsbatch, data);
	return;
};

let saveCampaignInDb = async (batch, data) => {
	let smsValues = new Array();
	let dlrValues = new Array();
	for (let x = 0, savelen = batch.length; x < savelen; ++x) {
		//prepare proper SMS object to save it in DB
		let clientData = {
			routeid: data.routeInfo.routeid,
			planid: data.routeInfo.planid,
			smsc: data.routeInfo.smsc,
			tlv: data.routeInfo.tlv,
			mccmnc: !batch[x].mccmnc ? 0 : batch[x].mccmnc,
		};
		let [sms, pdu] = composeSmsObject(batch[x], clientData);

		//prepare query
		let smstext = pdu.data_coding == 8 ? entities.decode(sms.sms_text) : sms.sms_text;
		let smsArray = [
			data.batchid,
			sms.smsid,
			data.smppclient,
			data.userid,
			sms.routeid,
			data.routeInfo.smsc,
			sms.senderid,
			sms.msisdn,
			JSON.stringify(sms.sms_type),
			smstext,
			sms.sms_count,
			tsToDate(sms.submission_time),
			data.dlr.status,
			sms.mccmnc || 0,
			sms.price || 0,
			sms.cost || 0,
			data.dlr.dlr,
			data.dlr.smpp_code,
			data.dlr.vendor_dlr,
			JSON.stringify(sms.tlv) || "[]",
			Buffer.from(JSON.stringify(sms)).toString("base64"),
			JSON.stringify(sms.platform_data) || "[]",
		];
		let dlrArray = [data.smppclient, sms.smsid, sms.senderid, sms.msisdn, parseInt(pdu.sequence_number) || 0, data.dlr.dlr, data.dlr.vendor_dlr];
		smsValues.push(smsArray);
		dlrValues.push(dlrArray);
	}

	if (data.mode.result == "FAIL" || data.mode.result == "CUTTING") {
		await Promise.all([dbquery("insertSmsQuery", { batchValues: smsValues }), dbquery("insertDlrQuery", { batchValues: dlrValues })]);
	}
	if (data.mode.result == "SEND") {
		await dbquery("insertSmsQuery", { batchValues: smsValues });
	}

	return;
};

let addToSqlbox_org = async (smsbatch, data) => {
	let smsValues = new Array();
	for (let x = 0, savelen = smsbatch.length; x < savelen; ++x) {
		//prepare proper SMS object to save it in DB
		let clientData = {
			routeid: data.routeInfo.routeid,
			planid: data.routeInfo.planid,
			smsc: data.routeInfo.smsc,
			tlv: data.routeInfo.tlv,
			mccmnc: 0,
		};

		let [sms, pdu] = composeSmsObject(smsbatch[x], clientData);

		console.log(sms.pdu);

		//prepare query
		let newlinetxt = sms.sms_text.toString().replace(/\/n/g, EOL).replace(/\n/g, EOL);
		let smstext = sms.sms_type.unicode == 1 ? entities.decode(newlinetxt) : newlinetxt;
		let dbText = smstext;
		try {
			dbText = encodeURIComponent(smstext).replace(/%5Cn/g, "%0A").replace(/'/g, "%27");
		} catch (err) {
			console.log(`SMS Text Encoding Error ${err}`);
		}
		let mclass = sms.sms_type.flash == 1 ? 0 : null;
		let coding = sms.sms_type.unicode == 1 ? 2 : 0;
		let udh = sms.sms_type.main == "wap" || sms.sms_type.main == "vcard" ? JSON.stringify(pdu.short_message.udh) : "";
		let charset = sms.sms_type.unicode == 1 ? "UTF-8" : "WINDOWS-1252";
		let dlrurl = `https://console.smshandover.com/getSmppDlr/index?smppclient=${data.smppclient}&sender=${encodeURIComponent(sms.senderid)}&routeid=${
			sms.routeid
		}&smsid=${sms.smsid}&userid=${sms.userid}&persmscount=${sms.sms_count}&pdu_seq=${pdu.sequence_number}&dlrreq=${
			pdu.registered_delivery
		}&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F`;
		//get tlv values
		let metadata = null;
		if (sms.tlv != undefined && sms.tlv.length > 0) {
			metadata = "?smpp?";
			for (const tlv of sms.tlv) {
				metadata += `${encodeURIComponent(tlv.key)}=${encodeURIComponent(tlv.value)}&`;
			}
		}
		let smsArray = [
			"MT",
			sms.senderid,
			sms.msisdn,
			dbText,
			udh,
			sms.submission_time,
			sms.smsc_id,
			data.smppclient,
			"2",
			mclass,
			coding,
			"31",
			dlrurl,
			charset,
			"smppcubebox",
			metadata,
		];
		smsValues.push(smsArray);
	}
	await dbquery("addToSqlbox", { batchValues: smsValues });

	return;
};

let addToSqlbox = async (smsbatch, data) => {
	let smsValues = new Array();
	for (let x = 0, savelen = smsbatch.length; x < savelen; ++x) {
		//prepare proper SMS object to save it in DB
		let clientData = {
			routeid: data.routeInfo.routeid,
			planid: data.routeInfo.planid,
			smsc: data.routeInfo.smsc,
			tlv: data.routeInfo.tlv,
			mccmnc: 0,
		};
		let [sms, pdu] = composeSmsObject(smsbatch[x], clientData);

		//prepare query

		let mclass = sms.sms_type.flash == 1 ? 0 : null;
		let coding = sms.sms_type.unicode == 1 ? 2 : 0;
		let alt_dcs = "";

		let charset = "UTF-8"; // sms.sms_type.unicode == 1 ? "UTF-8" : "WINDOWS-1252";
		let dlrurl = `https://console.smshandover.com/getSmppDlr/index?smppclient=${data.smppclient}&sender=${encodeURIComponent(sms.senderid)}&routeid=${
			sms.routeid
		}&smsid=${sms.smsid}&userid=${sms.userid}&persmscount=${sms.sms_count}&pdu_seq=${pdu.sequence_number}&dlrreq=${
			pdu.registered_delivery
		}&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F`;
		//get tlv values
		let metadata = null;
		if (sms.tlv != undefined && sms.tlv.length > 0) {
			metadata = "?smpp?";
			for (const tlv of sms.tlv) {
				metadata += `${encodeURIComponent(tlv.key)}=${encodeURIComponent(tlv.value)}&`;
			}
		}
		//if sms has multiple PDU, then insert row for each one
		if (Array.isArray(sms.pdu)) {
			sms.pdu.forEach((smspdu) => {
				let origPdu = JSON.parse(smspdu).pdu;
				let smstext,
					dbText = "";
				if (origPdu.short_message.message.type !== undefined) {
					//binary type msg
					let rawtext = origPdu.short_message.message.data;
					//rawtext.unshift(rawtext.length); //add length to the rawtext
					dbText = `%${toHexString(rawtext)}`;
				} else {
					//normal msg
					let newlinetxt = origPdu.short_message.message.toString().replace(/\/n/g, EOL).replace(/\n/g, EOL);
					smstext = sms.sms_type.unicode == 1 ? entities.decode(newlinetxt) : newlinetxt;
					dbText = smstext;
					try {
						dbText = encodeURIComponent(smstext).replace(/%5Cn/g, "%0A").replace(/'/g, "%27");
					} catch (err) {
						console.log(`SMS Text Encoding Error ${err}`);
					}
				}
				//multipart could have 2 UDH, one for parts other for type
				let hexudh = ``,
					udhlen = 0,
					finaludh = ``;
				if (origPdu.short_message.udh[0]) {
					origPdu.short_message.udh.forEach((udhobj) => {
						let udh = udhobj.data;
						udhlen += udh.length;
						//udh.unshift(udh.length); //add length to the UDH
						hexudh += `%${toHexString(udh)}`;
					});
				}
				if (hexudh != "" && udhlen > 0) {
					finaludh = `%${toHexString([udhlen])}${hexudh}`;
				}
				let pid = parseInt(origPdu.protocol_id);

				if (pid == 127) {
					//sim toolkit
					(coding = 1), (alt_dcs = 1);
				}
				if (origPdu.data_coding == 247) {
					mclass = 3;
				}
				if (origPdu.data_coding == 246) {
					mclass = 2;
				}
				if (origPdu.data_coding == 245) {
					(mclass = 1), (coding = 1);
				}
				//since kannel doesn't set data-coding transparently and for aboce cases the code behaves fine, the below case handles all the exceptions. data-coding = 3 triggered this mess
				if (
					origPdu.data_coding != 0 &&
					origPdu.data_coding != 8 &&
					origPdu.data_coding != 245 &&
					origPdu.data_coding != 246 &&
					origPdu.data_coding != 247
				) {
					//now whatever the data-coding is, force it
					if (metadata === null) {
						metadata = "?smpp?data_coding=" + origPdu.data_coding;
					} else {
						metadata += `data_coding=${origPdu.data_coding}`;
					}
				}

				let finalPduArray = [
					"MT",
					sms.senderid,
					sms.msisdn,
					dbText,
					finaludh,
					sms.submission_time,
					sms.smsc_id,
					data.smppclient,
					"2",
					mclass,
					coding,
					"31",
					dlrurl,
					pid,
					alt_dcs,
					charset,
					"smppcubebox",
					metadata,
				];
				smsValues.push(finalPduArray);
			});
		} else {
			let origPdu = sms.pdu;
			let smstext,
				dbText = "";

			if (origPdu.short_message.message.type !== undefined) {
				//binary type msg
				let rawtext = origPdu.short_message.message.data;
				//rawtext.unshift(rawtext.length); //add length to the rawtext
				dbText = `%${toHexString(rawtext)}`;
			} else {
				//normal msg
				let newlinetxt = origPdu.short_message.message.toString().replace(/\/n/g, EOL).replace(/\n/g, EOL);
				smstext = sms.sms_type.unicode == 1 ? entities.decode(newlinetxt) : newlinetxt;
				dbText = smstext;
				try {
					dbText = encodeURIComponent(smstext).replace(/%5Cn/g, "%0A").replace(/'/g, "%27");
				} catch (err) {
					console.log(`SMS Text Encoding Error ${err}`);
				}
			}

			let hexudh = ``,
				udhlen = 0,
				finaludh = ``;
			if (origPdu.short_message.udh && origPdu.short_message.udh[0]) {
				origPdu.short_message.udh.forEach((udhobj) => {
					let udh = udhobj.data;
					udhlen += udh.length;
					//udh.unshift(udh.length); //add length to the UDH
					hexudh += `%${toHexString(udh)}`;
				});
			}
			if (hexudh != "" && udhlen > 0) {
				finaludh = `%${toHexString([udhlen])}${hexudh}`;
			}

			let pid = parseInt(origPdu.protocol_id);
			if (pid == 127) {
				//sim toolkit
				(coding = 1), (alt_dcs = 1);
			}
			if (origPdu.data_coding == 247) {
				mclass = 3;
			}
			if (origPdu.data_coding == 246) {
				mclass = 2;
			}
			if (origPdu.data_coding == 245) {
				(mclass = 1), (coding = 1);
			}
			//since kannel doesn't set data-coding transparently and for aboce cases the code behaves fine, the below case handles all the exceptions. data-coding = 3 triggered this mess
			if (
				origPdu.data_coding != 0 &&
				origPdu.data_coding != 8 &&
				origPdu.data_coding != 245 &&
				origPdu.data_coding != 246 &&
				origPdu.data_coding != 247
			) {
				//now whatever the data-coding is, force it
				if (metadata === null) {
					metadata = "?smpp?data_coding=" + origPdu.data_coding;
				} else {
					metadata += `data_coding=${origPdu.data_coding}`;
				}
			}

			let finalPduArray = [
				"MT",
				sms.senderid,
				sms.msisdn,
				dbText,
				finaludh,
				sms.submission_time,
				sms.smsc_id,
				data.smppclient,
				"2",
				mclass,
				coding,
				"31",
				dlrurl,
				pid,
				alt_dcs,
				charset,
				"smppcubebox",
				metadata,
			];
			smsValues.push(finalPduArray);
		}
	}
	console.log(`pushing these values into DB for processing...`);
	console.dir(smsValues, { depth: null });
	await dbquery("addToSqlbox", { batchValues: smsValues });

	return;
};
/**
 * Misc sub functions
 */

function toHexString(byteArray) {
	return Array.from(byteArray, function (byte) {
		return ("0" + (byte & 0xff).toString(16)).slice(-2);
	}).join("%");
}

let similar_text = (first, second) => {
	first = first.toString().replace(/\s+/g, "");
	second = second.toString().replace(/\s+/g, "");

	if (!first.length && !second.length) return 1; // if both are empty strings
	if (!first.length || !second.length) return 0; // if only one is empty string
	if (first === second) return 1; // identical
	if (first.length === 1 && second.length === 1) return 0; // both are 1-letter strings
	if (first.length < 2 || second.length < 2) return 0; // if either is a 1-letter string

	let firstBigrams = new Map();
	for (let i = 0; i < first.length - 1; i++) {
		const bigram = first.substring(i, i + 2);
		const count = firstBigrams.has(bigram) ? firstBigrams.get(bigram) + 1 : 1;

		firstBigrams.set(bigram, count);
	}

	let intersectionSize = 0;
	for (let i = 0; i < second.length - 1; i++) {
		const bigram = second.substring(i, i + 2);
		const count = firstBigrams.has(bigram) ? firstBigrams.get(bigram) : 0;

		if (count > 0) {
			firstBigrams.set(bigram, count - 1);
			intersectionSize++;
		}
	}

	return (2.0 * intersectionSize) / (first.length + second.length - 2);
};

let composeSmsObject = (sms, { routeid, planid, smsc, tlv, mccmnc }) => {
	let pdu = Array.isArray(sms.pdu) ? JSON.parse(sms.pdu[0]).pdu : sms.pdu;
	let smsobj = {
		smsid: sms.smsid,
		userid: sms.userid,
		sms_type: {},
		sms_count: sms.sms_count,
		sms_text: sms.sms_text,
		msisdn: sms.msisdn,
		planid: planid,
		senderid: sms.sender,
		smsc_id: smsc,
		routeid: routeid,
		price: sms.price,
		cost: sms.cost,
		mccmnc: mccmnc || 0,
		submission_time: sms.submission_time,
		platform_data: sms.platform_data,
		pdu: sms.pdu,
	};
	//get data coding, esm class and udh to classify sms type
	let smstype = { main: "text", unicode: 0, flash: 0, multipart: 0 };
	if (parseInt(pdu.data_coding) == 0 || parseInt(pdu.data_coding) == 8 || parseInt(pdu.data_coding) == 240) {
		smstype.main = "text";
		if (parseInt(pdu.data_coding) == 8) smstype.unicode = 1;
		if (parseInt(pdu.data_coding) == 240) smstype.flash = 1;
	}
	if (pdu.esm_class == "64" || pdu.esm_class == "67") {
		smstype.multipart = 1;
	}
	if (parseInt(pdu.data_coding) == 4) {
		smstype.main = "vcard";
		smstype.multipart = 0;
	}
	if (parseInt(pdu.data_coding) == 245) {
		smstype.main = "wap";
		smstype.multipart = 0;
	}
	smsobj.sms_type = smstype;
	let tlv_data = [];
	if (Array.isArray(tlv) && tlv.length > 0) {
		for (const tlvparam of tlv) {
			if (pdu[tlvparam]) {
				tlv_data.push({
					key: `${tlvparam}`,
					value: pdu[tlvparam],
				});
			}
		}
	}

	smsobj.tlv = tlv_data;
	return [smsobj, pdu];
};

let getUserPermissions = async (userid) => {
	let userData = await dbquery("fetchUserPerms", { userid: userid });
	//let smstypeperm = JSON.parse(userData.perm_data);
	return {
		user_id: userid,
		account_type: userData.account_type,
		upline_id: userData.upline_id,
		spam_flag: userData.spam_status,
		template_flag: userData.opentemp_flag,
		flash: 1,
		unicode: 1,
		wap: 1,
		vcard: 1,
	};
};

let getRouteDetails = async (routeid) => {
	if (routeid == 0 || routeid == undefined) {
		return {
			route_id: 0,
			valid_lengths: 0,
			add_prefix: 0,
			senderid_type: 0,
			default_sender: "WEBSMS",
			max_sender_length: 0,
			template_flag: 0,
			country_code: "GLOBAL",
			country_prefix: "+",
			blacklist_tablename: "",
			blacklist_tablecol: "",
			smsc: "",
		};
	}
	//get route details
	let rows = await dbquery("getRouteInfo", { routeid: routeid });
	//if returns undefined then it could be a chance of API route
	if (rows == undefined) {
                return {
                        route_id: 0,
                        valid_lengths: 0,
                        add_prefix: 0,
                        senderid_type: 0,
                        default_sender: "WEBSMS",
                        max_sender_length: 0,
                        template_flag: 0,
                        country_code: "GLOBAL",
                        country_prefix: "+",
                        blacklist_tablename: "",
                        blacklist_tablecol: "",
                        smsc: "",
                };
        }	
	
	let validlen = rows.valid_lengths.split(",");
	for (let a in validlen) {
		validlen[a] = parseInt(validlen[a], 10); // Explicitly include base
	}

	//check if blacklist databases are applied, check for DND if needed
	let bltb_table,
		bltb_column = "";
	if (rows.blacklist_ids != "" && rows.blacklist_ids != null) {
		let bldb_ids = rows.blacklist_ids.split(",");
		let dbdata = await dbquery("getBlacklistDbInfo", { id: bldb_ids[0] });
		bltb_table = dbdata.table_name;
		bltb_column = dbdata.mobile_column;
	}
	//get the tlv parameters
	let tlv_params = [];
	if (rows.tlv_ids != "") {
		let tlvdata = await dbquery("getTlvNamesByIds", { id_list: rows.tlv_ids });
		if (tlvdata != undefined) {
			for (const tlv of tlvdata) {
				tlv_params.push(tlv.tlv_name);
			}
		}
	}
	//if reached here proceed normally
	return {
		route_id: routeid,
		valid_lengths: validlen,
		add_prefix: rows.add_pre,
		senderid_type: rows.sender_type,
		default_sender: rows.def_sender,
		max_sender_length: rows.max_sid_len,
		template_flag: rows.template_flag,
		active_time: JSON.parse(Buffer.from(rows.active_time, "base64")),
		country_code: rows.country_code,
		country_prefix: rows.prefix,
		blacklist_tablename: bltb_table,
		blacklist_tablecol: bltb_column,
		smsc: rows.smsc_id,
		tlv: tlv_params,
	};
};

let sortBatches = (smsbatch, parseFlag = 1, { cost_type, price_per_sms }) =>
	Promise.resolve().then(async (v) => {
		const sortedbatch = new Array();
		const map = new Map();
		let req_credits = 0;
		for (let smsitem of smsbatch) {
			if (parseFlag == 1) smsitem = JSON.parse(smsitem);
			let charge_per_part = cost_type == "credit" ? 1 : price_per_sms == 0 ? smsitem.price : price_per_sms;
			let sms_charge = charge_per_part * parseInt(smsitem.sms_count);
			req_credits += sms_charge;
			smsitem.price = price_per_sms == 0 ? smsitem.price : price_per_sms;
			smsitem.cost = price_per_sms == 0 ? smsitem.cost : price_per_sms * parseInt(smsitem.sms_count);
			if (!map.has(smsitem.sender)) {
				//new sms campaign
				let shootid = Math.random().toString(36).substring(3);
				map.set(smsitem.sender, shootid); // set any value to Map
				sortedbatch[shootid] = new Array();
				sortedbatch[shootid].push(smsitem);
			} else {
				//match sms text as well
				let indstr = map.get(smsitem.sender);
				let indices = indstr.split(",");
				if (indices.length == 1) {
					if (similar_text(sortedbatch[indices[0]][0].sms_text, smsitem.sms_text) > env.MATCH_PERCENT / 100) {
						//same campaign
						sortedbatch[indices[0]].push(smsitem);
					} else {
						//new campaign
						let shootid = Math.random().toString(36).substring(3);
						map.set(smsitem.sender, `${indstr},${shootid}`);
						sortedbatch[shootid] = new Array();
						sortedbatch[shootid].push(smsitem);
					}
				} else {
					//loop through all batches to see which one it belongs to
					let txtmatch = 0;
					for (let sidindex of indices) {
						if (similar_text(sortedbatch[sidindex][0].sms_text, smsitem.sms_text) > env.MATCH_PERCENT / 100) {
							//same campaign
							sortedbatch[sidindex].push(smsitem);
							txtmatch = 1;
							break;
						}
					}
					if (txtmatch == 0) {
						//nothing matched create new sms shoot id
						let shootid = Math.random().toString(36).substring(3);
						map.set(smsitem.sender, `${indstr},${shootid}`);
						sortedbatch[shootid] = new Array();
						sortedbatch[shootid].push(smsitem);
					}
				}
			}
		}
		return [sortedbatch, req_credits];
	});

let sortBatchesByRoute = async (smsbatch, plandata) => {
	const routeWiseBatch = new Object();
	let total_cost = 0;
	console.log(`Sorting batch by route..`);
	console.log(plandata);
	let smsBatchWithData = await Promise.all(smsbatch.map((sms) => getMccMncInfoByMsisdn(sms, plandata)));
	//sort contacts by routes
	for (const smsitem of smsBatchWithData) {
		let routeid = smsitem.routeid ||plandata.route_id;
		total_cost += smsitem.cost;
		if (!routeWiseBatch[routeid]) {
			routeWiseBatch[routeid] = [];
			routeWiseBatch[routeid].push(smsitem);
		} else {
			routeWiseBatch[routeid].push(smsitem);
		}
	}
	return [routeWiseBatch, total_cost];
};

let validateEachContact = async (smsbatch, { cost_type, validLenths, prefixInfo, blackList }) => {
	//The reason we calculate credits required here again is because previously we calculated credits needed for an entire batch. That batch might have 2-3 or more shoot_id. Each may have different rules and some might get rejected. If we are validating the contacts it means we are sending them and here we can calculate exactly how many credits we need to charge the client
	let filtered = [],
		invalids = [],
		validNums = [],
		blacklists = [],
		matchList = [];
	let credits = 0;
	for (let i = 0, len = smsbatch.length; i < len; ++i) {
		let mobile = smsbatch[i].msisdn;
		if (validLenths.includes(mobile.length) == false || smsbatch[i].mccmnc == -1) {
			invalids.push(smsbatch[i]);
			continue;
		}
		//add country prefix always for smpp sms, not only if applicable
		if (validLenths.length > 0) {
			mobile =
				mobile.toString().length < Math.max(...validLenths) && prefixInfo.prefix.toString() != "0"
					? prefixInfo.prefix.toString().concat(mobile)
					: mobile;
			smsbatch[i].msisdn = mobile;
		}
		matchList.push(mobile);
		validNums.push(smsbatch[i]);
		credits += cost_type == "credit" ? parseInt(smsbatch[i].sms_count) : smsbatch[i].cost;
	}
	//match against blacklist
	if (blackList.table == "" || blackList.column == "") return [validNums, invalids, blacklists, credits];

	let matches = await dbquery("matchBlacklist", {
		table: blackList.table,
		column: blackList.column,
		mobiles: matchList.join(","),
	});
	if (matches.length > 0) {
		//filter out blacklist matched
		validNums.forEach((ele) => {
			if (matches.some(({ msisdn: id2 }) => id2 === ele.msisdn) == true) {
				blacklists.push(ele);
				credits -= cost_type == "credit" ? parseInt(ele.sms_count) : ele.cost;
			} else {
				filtered.push(ele);
			}
		});
	} else {
		filtered = [...validNums];
	}

	return [filtered, invalids, blacklists, credits];
};

let applyDlrCutting = (smsbatch, info) => {
	if (info.percent == 100) return [smsbatch, [], [], []];
	if (info.totalsize < env.DLRCUT_THRESHOLD) return [smsbatch, [], [], []];
	//filter whitelist
	let whitelist_present = smsbatch.filter((sms) => info.whitelist.includes(sms.msisdn));
	let batch_nowhitelist = smsbatch.filter((sms) => !whitelist_present.includes(sms));

	let dlrcut = info.totalsize - parseInt((info.percent / 100) * info.totalsize);
	let smstocut = dlrcut > batch_nowhitelist.length ? batch_nowhitelist.length : dlrcut;
	if (batch_nowhitelist.length == 0) return [smsbatch, [], [], []];
	//apply cutting here
	let fakedel = parseInt((env.FAKEDLR_DEL / 100) * smstocut);
	let fakeundel = parseInt((env.FAKEDLR_UNDEL / 100) * smstocut);
	let fakeexp = smstocut - (fakedel + fakeundel);

	let droppedcontacts = [...batch_nowhitelist].sort(() => 0.5 - Math.random()).slice(0, smstocut);
	let fakedel_nums = droppedcontacts.slice(0, fakedel);
	let fakeundel_nums = droppedcontacts.slice(fakedel, fakedel + fakeundel);
	let fakeexp_nums = droppedcontacts.slice(fakedel + fakeundel, smstocut);

	//get sms to send
	let setminus = new Set(droppedcontacts);
	let smswithoutdropped = [...new Set([...batch_nowhitelist].filter((x) => !setminus.has(x)))];
	let sendnums = [...smswithoutdropped, ...whitelist_present];

	return [sendnums, fakedel_nums, fakeundel_nums, fakeexp_nums];
};

let wildCardMatch = (pattern, candidate) => {
	// If we reach at the end of both strings,
	// we are done
	if (pattern.length == 0 && candidate.length == 0) return true;

	// Make sure that the characters after '*'
	// are present in candidate string.
	// This function assumes that the pattern
	// string will not contain two consecutive '*'
	if (pattern.length > 1 && pattern[0] == "*" && candidate.length == 0) return false;

	// If the pattern string contains '?',
	// or current characters of both strings match
	if ((pattern.length > 1 && pattern[0] == "?") || (pattern.length != 0 && candidate.length != 0 && pattern[0] == candidate[0]))
		return wildCardMatch(pattern.substring(1), candidate.substring(1));

	// If there is *, then there are two possibilities
	// a) We consider current character of candidate string
	// b) We ignore current character of candidate string.
	if (pattern.length > 0 && pattern[0] == "*")
		return wildCardMatch(pattern.substring(1), candidate) || wildCardMatch(pattern, candidate.substring(1));

	return false;
};
/**
 * Campaign sub tasks
 */

let checkSmsPermission = (smsobj, userPerms) =>
	Promise.resolve().then(async (v) => {
		if (smsobj.sms_type.flash == 1 && userPerms.flash == 0) return false;
		if (smsobj.sms_type.unicode == 1 && userPerms.unicode == 0) return false;
		if (smsobj.sms_type.main == "wap" && userPerms.wap == 0) return false;
		if (smsobj.sms_type.main == "vcard" && userPerms.vcard == 0) return false;

		return true;
	});

let checkValidTiming = (smsobj, routeInfo) =>
	Promise.resolve().then(async (v) => {
		if (routeInfo.active_time == undefined) return true;
		let timedata = routeInfo.active_time;
		if (timedata.type == 0) return true;
		let current_time = new Date(smsobj.submission_time).toLocaleString("en-US", {
			hour: "2-digit",
			hour12: false,
			minute: "2-digit",
			timeZone: timedata.timezone,
		});
		let route_from = ("0" + timedata.from).slice(-5); //pad 0 for 2 digit hour
		let route_until = ("0" + timedata.to).slice(-5);
		if (current_time < route_from || current_time > route_until) {
			return false;
		} else {
			return true;
		}
	});

let checkValidSender = async (senderid, { maxlength, senderType, userid }) => {
	let lengthFlag = true,
		validFlag = true,
		sendercheck = 0;
	if (senderid.length > maxlength) lengthFlag = false;
	if (senderType == 0) {
		let allSenders = await dbquery("getAllApprovedSenders", {
			user: userid,
		});
		for (let i = 0, len = allSenders.length; i < len; ++i) {
			if (wildCardMatch(allSenders[i].sender_id, senderid) == true) {
				sendercheck = 1;
				break;
			}
		}
		if (sendercheck == 0) validFlag = false;
	}
	return [lengthFlag, validFlag];
};

let checkValidTemplate = async (smstext, userInfo, routeInfo) => {
	if (routeInfo.template_flag == 0) return true;
	if (userInfo.template_flag == 1) return true;
	let userTemplates = await dbquery("getApprovedTemplates", {
		user: userid,
		route: routeid,
	});
	if (userTemplates == undefined) return false;
	let match = 0;
	for (let i = 0, len = userTemplates.length; i < len; ++i) {
		if (similar_text(smstext, userTemplates[i].content) > env.MATCH_PERCENT / 100) {
			match = 1;
			break;
		}
	}
	return match == 0 ? false : true;
};

let isSpamFreeText = async (smstext, spamPermission) => {
	if (spamPermission == 1) return true;
	let spamKeywords = await dbquery("getSpamKeywords");
	if (spamKeywords == undefined) return true;
	let match = 0;
	for (let i = 0, len = spamKeywords.length; i < len; ++i) {
		if (smstext.includes(spamKeywords[i].phrase)) {
			match = 1;
			break;
		}
	}
	return match == 0 ? true : false;
};

let getCampaignRejectionReason = (flags) => {
	if (flags[0] == false) {
		return {
			state: 1,
			dlr: 16,
			smpp_code: "REJECTD",
			vendor_dlr: env.SMPP_TINV_ERR,
			err_desc: env.SMPP_TINV_DESC,
		};
	}
	if (flags[1] == false) {
		return {
			state: 1,
			dlr: 16,
			smpp_code: "REJECTD",
			vendor_dlr: env.SMPP_TIME_ERR,
			err_desc: env.SMPP_TIME_DESC,
		};
	}
	if (flags[2] == false) {
		return {
			state: 1,
			dlr: 16,
			smpp_code: "REJECTD",
			vendor_dlr: env.SMPP_SLEN_ERR,
			err_desc: env.SMPP_SLEN_DESC,
		};
	}
	if (flags[3] == false) {
		return {
			state: 1,
			dlr: 16,
			smpp_code: "REJECTD",
			vendor_dlr: env.SMPP_SINV_ERR,
			err_desc: env.SMPP_SINV_DESC,
		};
	}
	if (flags[4] == false) {
		return {
			state: 1,
			dlr: 16,
			smpp_code: "REJECTD",
			vendor_dlr: env.SMPP_TMPL_ERR,
			err_desc: env.SMPP_TMPL_DESC,
		};
	}
	if (flags[5] == false) {
		return {
			state: 1,
			dlr: 16,
			smpp_code: "REJECTD",
			vendor_dlr: env.SMPP_SPAM_ERR,
			err_desc: env.SMPP_SPAM_DESC,
		};
	}
};

let getMccMncInfoByMsisdn = async (smsitem, plandata) => {
	smsitem = JSON.parse(smsitem);
	console.log(`Getting MCCMNC info by Plan`);
	console.log(plandata);
	let isoRouteAndPrice = JSON.parse(plandata.route_coverage);
	let coverage = getCoverageByMsisdn(smsitem.msisdn);
	if (coverage == undefined) {
		smsitem.mccmnc = -1;
		smsitem.price = 0;
		smsitem.routeid = 0;
		smsitem.cost = 0;
		return smsitem;
	}
	let coverageData = await dbquery("getCoverageDetailsByIso", {
		iso: coverage.iso,
	});
	//find the mccmnc code for this msisdn
	let [mccmncInfo, defaultPrc] = await Promise.all([dbquery("getMccMncByPrefix", {
		coverage: coverage.prefix,
		msisdn: smsitem.msisdn.toString(),
	}),
		await dbquery("getDefaultCountryPrice", {
                                        plan: plandata.id,
                                        country: coverage.prefix,
                          })]);
	let priceAndRoute =
		mccmncInfo == undefined || mccmncInfo.mccmnc == 0
			? await dbquery("getDefaultCountryPrice", {
					plan: plandata.id,
					country: coverage.prefix,
			  })
			: await dbquery("getMccmncRouteAndCost", {
					mccmnc: mccmncInfo.mccmnc,
					plan: plandata.id,
			  });
	if (priceAndRoute == undefined) priceAndRoute = defaultPrc;
	//console.log(mccmncInfo); console.log(priceAndRoute);
	if (mccmncInfo == undefined || mccmncInfo.mccmnc == 0) {
		console.log('1')
		//no match found. But the coverage was found so get the default route and default price
		smsitem.mccmnc = 0;
		smsitem.price = priceAndRoute.price;
		smsitem.routeid = plandata.route_id;
		smsitem.cost = priceAndRoute.price * smsitem.sms_count;
	} else {
		console.log('2')
		//mccmnc match found. fetch the route and price for this mccmnc in the plan
		smsitem.mccmnc = mccmncInfo.mccmnc;
		smsitem.price = priceAndRoute.price;
		smsitem.routeid = priceAndRoute.route_id;
		smsitem.cost = priceAndRoute.price * smsitem.sms_count;
	}
	console.log(smsitem);
	return smsitem;
};

let preCheck = async (pdu, userObj) => {
	//reject submission and send NACK if
	//1. invalid or wrong sender
	//2. template mismatch
	//3. invalid hours
	//4. sms type not allowed
	//5. spam found
	//6. not enough credits
	const [userInfo, routeInfo, userCreditInfo, walletData] = await Promise.all([
		getUserPermissions(userObj.user),
		getRouteDetails(userObj.route),
		dbquery("getCreditDataBySystemid", {
			systemid: userObj.sessionSystemId,
		}),
		dbquery("getWalletCredits", { user: userObj.user }),
	]);
	let smsobj = {
		smsid: userObj.smsid,
		userid: userObj.user,
		sms_type: {},
		sms_count: 1,
		sms_text: stringify(pdu.short_message.message),
		msisdn: pdu.destination_addr,
		planid: userObj.plan,
		senderid: pdu.source_addr,
		smsc_id: routeInfo.smsc || "",
		routeid: userObj.route,
		price: 0,
		cost: 0,
		mccmnc: 0,
		submission_time: Date.now(),
		platform_data: userObj.platform_data,
		pdu: pdu,
	};
	//get data coding, esm class and udh to classify sms type
	let smstype = { main: "text", unicode: 0, flash: 0, multipart: 0 };
	if (parseInt(pdu.data_coding) == 0 || parseInt(pdu.data_coding) == 8 || parseInt(pdu.data_coding) == 240) {
		smstype.main = "text";
		if (parseInt(pdu.data_coding) == 8) smstype.unicode = 1;
		if (parseInt(pdu.data_coding) == 240) smstype.flash = 1;
	}
	if (pdu.esm_class == "64" || pdu.esm_class == "67") {
		smstype.multipart = 1;
	}
	if (parseInt(pdu.data_coding) == 4) {
		smstype.main = "vcard";
		smstype.multipart = 0;
	}
	if (parseInt(pdu.data_coding) == 245) {
		smstype.main = "wap";
		smstype.multipart = 0;
	}
	smsobj.sms_type = smstype;
	smsobj.tlv = [];
	console.log(routeInfo);
	let allFlags = await Promise.all([
		checkSmsPermission(smsobj, userInfo),
		checkValidTiming(smsobj, routeInfo),
		checkValidSender(smsobj.senderid, {
			maxlength: routeInfo.max_sender_length,
			senderType: routeInfo.senderid_type,
			userid: userInfo.user_id,
		}),
		checkValidTemplate(smsobj.sms_text, userInfo, routeInfo),
		isSpamFreeText(smsobj.sms_text, userInfo.spam_flag),
	]);

	console.log(allFlags);
	let statuses = allFlags.flat();
	if (statuses.includes(false)) {
		//reject campaign and queue proper dlr response
		if (statuses[1] == false) {
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "INVALID SMS TYPE",
			};
		}
		if (statuses[2] == false) {
			//
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "INVALID HOURS FOR SUBMISSION",
			};
		}
		if (statuses[3] == false) {
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "INVALID SENDER",
			};
		}
		if (statuses[4] == false) {
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "INVALID TEMPLATE",
			};
		}
		if (statuses[5] == false) {
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "SPAM DETECTED",
			};
		}
	}
	//check if number is valid
	if (pdu.destination_addr.length > 13 || pdu.destination_addr.length < 6 || !parseInt(pdu.destination_addr)) {
		return {
			status: "error",
			reasonCode: "0x0045",
			reason: "INVALID MOBILE NUMBER",
		};
	}
	//check if balance is not zero
	if (userObj.plan == 0) {
		//credit based user
		if (userCreditInfo.credits <= 0) {
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "NO CREDITS AVAILABLE",
			};
		}
	} else {
		//currency based user
		if (walletData.amount <= 0) {
			return {
				status: "error",
				reasonCode: "0x0045",
				reason: "NO CREDITS IN WALLET",
			};
		}
	}

	return {
		status: "success",
	};
};

const _processClientSmsBatch = processClientSmsBatch;
export { _processClientSmsBatch as processClientSmsBatch };
//export for preemtive check of PDU before storing it
const _preCheck = preCheck;
export { _preCheck as preCheck };

