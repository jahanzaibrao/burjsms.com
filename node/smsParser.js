"use strict";
import { env, getCoverageByMsisdn, unserialize, require } from "./miscHelper.js";
import { dbquery } from "./mariadbHelper.js";
const { parentPort, workerData } = require("worker_threads");

const getPanelAndApiSmsData = async (smsitem, summaryData, mysqlData) => {
	let summary = summaryData[smsitem.sms_shoot_id];
	//get user data
	let userdata = mysqlData.allUsers[smsitem.user_id];
	//get sender id
	let senderid = mysqlData.allSender[smsitem.sender_id];
	if (summary === undefined || userdata === undefined || senderid === undefined) {
		//send this sms id for deletion
		let del_id = parseInt(smsitem.id) || 0;
		await dbquery("runPlainQuery", {
			query: `DELETE FROM sc_sent_sms WHERE id = ${del_id}`,
		});
		console.log(`Deleted Orphan SMS ID ${del_id}, ${smsitem.sms_shoot_id}`);
		return null;
	}
	let send_time_human = new Date(smsitem.sending_time);
	let send_time_epoch = send_time_human.getTime();
	let dlr_time_human = new Date(smsitem.dlr_updated_on);
	let dlr_time_epoch = dlr_time_human.getTime();
	let click_time_epoch;
	if (smsitem.url_visit_ts == null || smsitem.url_visit_ts == undefined) {
		click_time_epoch = null;
	} else {
		let click_time_human = new Date(smsitem.url_visit_ts);
		click_time_epoch = click_time_human.getTime();
	}

	let tlv = {};
	if (summary.tlv_data != "") {
		let campaign_tlv = JSON.parse(summary.tlv_data);
		campaign_tlv.forEach(tlvstring => {
			let tlvparts = tlvstring.split("||");
			tlv[tlvparts[0]] = tlvparts[1];
		});
	}
	let platform_data = {
			ip: summary.platform_data.ip || null,
			system: summary.platform_data.system || "",
			browser: summary.platform_data.browser || "",
			city: summary.platform_data.city || "",
			country: summary.platform_data.country || "",
			location: {
				lat: summary.platform_data.lat || 0,
				lon: summary.platform_data.lon || 0,
			},
		},
		click_platform = {
			ip: null,
			system: "",
			browser: "",
			city: "",
			country: "",
			location: {
				lat: 0,
				lon: 0,
			},
		};
	let smstext = summary.sms_type.personalize ? smsitem.sms_text : summary.sms_text;
	if (smsitem.url_visit_platform != "" && smsitem.url_visit_platform != undefined) {
		let click_platform_data = await unserialize({
			type: "single",
			body: smsitem.url_visit_platform,
		});
		let click_platform_parsed = click_platform_data;
		click_platform.ip = click_platform_parsed.ip || null;
		click_platform.system = click_platform_parsed.system || "";
		click_platform.browser = click_platform_parsed.browser || "";
		click_platform.city = click_platform_parsed.city || "";
		click_platform.country = click_platform_parsed.country || "";
		click_platform.location.lat = click_platform_parsed.lat || 0;
		click_platform.location.lon = click_platform_parsed.lon || 0;
	}

	//get real smsc details
	let smsc = smsitem.smsc;
	let smppobject = mysqlData.allSmsc[smsc];
	let smscdata = {
		host: smppobject === undefined ? "" : smppobject.host,
		port: smppobject === undefined ? 0 : smppobject.port,
		system_id: smppobject === undefined ? "" : smppobject.system_id,
	};

	//get refund status
	let refundflag = mysqlData.refundLogs.has(`${smsitem.sms_shoot_id}-${smsitem.mobile}`);
	//get country and operator
	// if (smsitem.mobile.toString().length == 10) {
	// 	smsitem.mobile = parseInt(`91${smsitem.mobile}`);
	// }
	if (smsitem.mobile.toString().length > 18) {
		smsitem.mobile = smsitem.mobile.toString().substring(0, 16);
	}
	let countryAndMccmnc = getMccmncAndCoverageDetails(smsitem.mobile, smsitem.mccmnc, {
		allMccmnc: mysqlData.allMccmnc,
		allCoverage: mysqlData.allCoverage,
		mccmncMap: mysqlData.mccmncMap,
	});
	//get sms type alias
	let smstypestr = `TEXT`;
	if (summary.sms_type.main == "text") {
		if (summary.sms_type.unicode == true) {
			smstypestr = `UNICODE`;
		}
		if (summary.sms_type.flash == true) {
			smstypestr = `FLASH`;
		}
		if (summary.sms_type.unicode == true && summary.sms_type.flash == true) {
			smstypestr = `UNICODE-FLASH`;
		}
	} else if (summary.sms_type.main == "wap") {
		smstypestr = "WAP";
	} else if (summary.sms_type.main == "vcard") {
		smstypestr = "VCARD";
	}
	//Mask vendor dlr like FDEL, RDEL etc
	let mask_vdlr = smsitem.vendor_dlr;
	if (smsitem.vendor_dlr == "RDEL" || smsitem.vendor_dlr == "FDEL") {
		mask_vdlr = "000";
	}
	if (smsitem.vendor_dlr == "REXP" || smsitem.vendor_dlr == "FEXP") {
		mask_vdlr = "505";
	}
	if (smsitem.vendor_dlr == "RUNDEL" || smsitem.vendor_dlr == "FUNDEL") {
		mask_vdlr = "-";
	}

	let preparedSmsObject = {
		mysql_id: smsitem.id.toString(),
		campaign_id: summary.campaign_id,
		sms_shoot_id: smsitem.sms_shoot_id,
		operator_sms_id: smsitem.vendor_msgid,
		user_id: smsitem.user_id,
		user_alias: userdata.login_id,
		upline_user_id: userdata.upline_id,
		route_id: smsitem.route_id,
		routing_scheme: "dedicated",
		smsc: smsc,
		smsc_data: smscdata,
		submit_time: send_time_epoch,
		channel: summary.pushed_via == "APP" ? "APP" : "API",
		sender_id: senderid,
		msisdn: smsitem.mobile,
		country: {
			iso: countryAndMccmnc.country_code,
			prefix: countryAndMccmnc.country_prefix,
		},
		operator: {
			mccmnc: countryAndMccmnc.mccmnc,
			mcc: countryAndMccmnc.mcc,
			mnc: countryAndMccmnc.mnc,
			title: countryAndMccmnc.network,
			region: countryAndMccmnc.region,
		},
		sms_type: {
			main: summary.sms_type.main,
			unicode: summary.sms_type.unicode ? true : false,
			flash: summary.sms_type.flash ? true : false,
			personalized: summary.sms_type.personalize ? true : false,
			multipart: summary.sms_type.multipart ? true : false,
		},
		sms_type_alias: smstypestr,
		sms_parts: smsitem.sms_count,
		sms_text: smstext,
		price: smsitem.price,
		cost: smsitem.cost,
		currency: env.MAIN_CURRENCY,
		dlr: {
			type: smsitem.status == 2 ? "fakedlr" : "normal",
			kannel_code: smsitem.dlr,
			smpp_response: smsitem.smpp_resp_code,
			operator_code: mask_vdlr,
			app_status: getAppDlrStatus(smsitem.dlr),
			time: isNaN(dlr_time_epoch) ? null : dlr_time_epoch,
		},
		refunded: refundflag,
		ndnc: mysqlData.allNdncCodes.find(e => e == smsitem.vendor_dlr) == undefined ? false : true,
		attempts: 1,
		hide_msisdn: summary.hide_mobile == 0 ? false : true,
		tlv_data: tlv,
		click_tracking: {
			flag: smsitem.url_visit_flag == 0 ? false : true,
			timestamp: isNaN(click_time_epoch) ? null : click_time_epoch,
			platform: click_platform,
		},
		submit_platform: platform_data,
	};
	mysqlData = summaryData = null;
	return preparedSmsObject;
};

const getAppDlrStatus = dlr => {
	let status = "";
	switch (dlr) {
		case 1:
			status = "Delivered";
			break;
		case 2:
			status = "Failed";
			break;
		case 8:
			status = "SMSC Submitted";
			break;
		case 16:
			status = "Rejected";
			break;
		case 0:
			status = "Pending DLR";
			break;
		case -1:
			status = "Invalid";
			break;

		default:
			break;
	}
	return status;
};

const getMccmncAndCoverageDetails = (msisdn, mccmnc, { allMccmnc, allCoverage, mccmncMap }) => {
	let coverage = getCoverageByMsisdn(msisdn);
	let mccmnc_resp = {
		country_code: coverage == undefined ? "" : coverage.iso,
		country_prefix: coverage == undefined ? "" : coverage.prefix,
		mcc: 0,
		mnc: 0,
		mccmnc: 0,
		network: "",
		region: "",
	};

	if (mccmnc == 0) {
		//a bit more work is needed to get details
		if (coverage == undefined) return mccmnc_resp;
		let coveragedata = allCoverage[coverage.iso];
		let valid_lengths = coveragedata.valid_lengths.split(",");
		let msisdn_length = msisdn.toString().length;
		if (msisdn_length == Math.max(...valid_lengths)) {
			//mobile has country prefix
			let network_prefix = msisdn
				.toString()
				.substr(
					coverage.prefix.toString().length,
					parseInt(coveragedata.network_idn_pre_len)
				);
			//find the mccmnc code for this msisdn
			let mccmnc = mccmncMap[`${coverage.iso}-${network_prefix}`];
			if (mccmnc == undefined) return mccmnc_resp;
			let mccmncdata = allMccmnc[mccmnc];
			return mccmncdata == undefined ? mccmnc_resp : mccmncdata;
		} else {
			//mobile has no country prefix
			return mccmnc_resp;
		}
	} else {
		//we have mccmnc, get details from mccmnc list table
		let mccmncdata = allMccmnc[mccmnc];
		return mccmncdata == undefined ? mccmnc_resp : mccmncdata;
	}
};

const { workerBatch, summaryData, mysqlData } = workerData;
(async () => {
	let sms_objects = await Promise.all(
		workerBatch.map(sms => getPanelAndApiSmsData(sms, summaryData, mysqlData))
	);
	parentPort.postMessage(sms_objects.filter(Boolean));
})();
