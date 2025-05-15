"use strict";
import { env, require, entities } from "../miscHelper.js";
const searchRouter = require("express").Router();
const jwt = require("jsonwebtoken");
import { asyncHExists } from "../nodeAsyncRedis.js";
import {
	prepareSmsSearchBody,
	prepareSmsStatsBody,
	performGlobalSmsSearch,
	performGlobalSmsAggregations,
	prepareMiniStatsRequest,
} from "../elasticHelper.js";
import { response } from "express";

//search router authentication by a router-level middleware
searchRouter.use(async (req, res, next) => {
	//get auth header
	const bearerHeader = req.headers["authorization"];
	if (bearerHeader == undefined || bearerHeader == "") {
		res.json({ error: "forbidden" });
	} else {
		const bearer = bearerHeader.split(" ");
		const token = bearer[1];
		//check if this token exists in redis
		let jwtFlag = await asyncHExists("search_jwt_tokens", token);
		if (jwtFlag == 1) {
			req.token = token;
			next();
		} else {
			res.json({ error: "session invalid" });
		}
	}
});
//api end point
searchRouter.route("/sms").post(async (req, res) => {
	try {
		let { user, group } = jwt.verify(req.token, env.JWT_SECRET);
		if (["admin", "staff", "reseller", "client"].includes(group) === false) {
			console.log("Malformed Request. Possible hack attempt...");
			console.log(req.token);
			res.json({ error: "unauthorized" });
		} else {
			const es_body = prepareSmsSearchBody(req.body, { user, group });
			let results = await performGlobalSmsSearch(
				es_body,
				req.body.mode ? req.body.mode : "search"
			);
			res.json(results);
		}
	} catch (error) {
		console.log("Malformed Request. Possible hack attempt...");
		console.log(req.token);
		console.log(error);
		res.json({ error: "session malformed" });
	}
});
//download api
searchRouter.route("/download").post(async (req, res) => {
	try {
		let { user, group } = jwt.verify(req.token, env.JWT_SECRET);
		let colHeader = "";
		if (group == "admin") {
			colHeader =
				"Date,Mobile,Type,Sender,Client,Route,SMSC,Channel,Country,Operator,MCCMNC,SMS Parts,Cost,DLR,State,DLR Time,SMS ID,Message\r\n\r\n";
		} else {
			colHeader =
				"Date,Mobile,Type,Sender,Client,Route,Channel,Country,Operator,MCCMNC,SMS Parts,Cost,DLR,State,DLR Time,SMS ID,Message\r\n\r\n";
		}
		let rows = await Promise.all(
			req.body.rows.map(e => parseItemToCsvString(e, req.body.routeTitles, group))
		);
		res.json({
			parsedRows: `${colHeader}${rows.join("\r\n")}`,
		});
	} catch (error) {
		console.log("Error while downloading");
		console.log(error);
		res.json({
			error: "download failed",
		});
	}
});
//statistics api
searchRouter.route("/stats").post(async (req, res) => {
	try {
		let { user, group } = jwt.verify(req.token, env.JWT_SECRET);
		if (["admin", "staff", "reseller", "client"].includes(group) === false) {
			console.log("Malformed Request. User unidentified. Possible hack attempt...");
			console.log(req.token);
			res.json({ error: "unauthorized" });
		} else {
			const es_body = prepareSmsStatsBody(req.body, { user, group });
			let results = await performGlobalSmsAggregations(es_body);
			let statsData = convertResultsToChartsData(results);
			res.json({
				statsData,
			});
		}
	} catch (error) {
		console.log("Malformed Request. JWT or ES error. Possible hack attempt...");
		console.log(req.token);
		console.log(error);
		//console.log(error.body.error);
		res.json({ error: "session malformed" });
	}
});
//dashboard stats api
searchRouter.route("/ministats").post(async (req, res) => {
	try {
		let { user, group } = jwt.verify(req.token, env.JWT_SECRET);
		if (["admin", "staff", "reseller", "client"].includes(group) === false) {
			console.log("Malformed Request. User unidentified. Possible hack attempt...");
			console.log(req.token);
			res.json({ error: "unauthorized" });
		} else {
			//get dashboard stats for the types of user
			const es_body = prepareMiniStatsRequest(req.body, { user, group });
			let results = await performGlobalSmsAggregations(es_body);
			//parse them in format needed by app
			let statsData = convertResultsForDashboard(results, req.body.mode);
			//send response
			res.json({
				statsData,
			});
		}
	} catch (error) {
		console.log("Malformed Request. JWT or ES error. Possible hack attempt...");
		console.log(req.token);
		console.log(error);
		//console.log(error.body.error);
		res.json({ error: "session malformed" });
	}
});
//helper functions
let convertResultsForDashboard = (data, mode) => {
	if (mode === "top_sms_stats") {
		//get last 7 and 30 days total and per day sms sent
		let last_seventh_ts = new Date().setDate(new Date().getDate() - 7);
		let response = {
			total_last_seven: 0,
			last_seven_sms: [],
			last_seven_credits: 0,
			last_seven_credits_daily: [],
			last_seven_cost: 0,
			last_seven_cost_daily: [],
			total_last_thirty: data.total,
			last_thirty_sms: [],
			last_thirty_credits: 0,
			last_thirty_credits_daily: [],
			last_thirty_cost_daily: [],
			last_thirty_cost: 0,
		};
		for (const bucket_item of data.aggs.top_sms_stats.buckets) {
			if (bucket_item.key >= last_seventh_ts) {
				response.total_last_seven += parseInt(bucket_item.doc_count);
				response.last_seven_sms.push(bucket_item.doc_count);
				response.last_seven_credits += parseInt(bucket_item.credits.value);
				response.last_seven_credits_daily.push(bucket_item.credits.value);
				response.last_seven_cost += parseInt(bucket_item.cost.value);
				response.last_seven_cost_daily.push(bucket_item.cost.value);
			}
			response.last_thirty_sms.push(bucket_item.doc_count);
			response.last_thirty_credits += parseInt(bucket_item.credits.value);
			response.last_thirty_credits_daily.push(bucket_item.credits.value);
			response.last_thirty_cost += parseInt(bucket_item.cost.value);
			response.last_thirty_cost_daily.push(bucket_item.cost.value);
		}
		return response;
	} else {
		let response = {};
		if (data.aggs !== undefined && data.aggs.roc_data !== undefined) {
			let roc_data = [];
			for (const roc_bucket of data.aggs.roc_data.buckets) {
				let total_delivered = 0,
					total_failed = 0,
					total_ndnc = 0,
					total_invalid = 0,
					total_ack = 0,
					total_refunds = 0;
				for (let dlrsum_bucket of roc_bucket.dlr_for_route.buckets) {
					switch (dlrsum_bucket.key) {
						case 1:
							total_delivered += dlrsum_bucket.doc_count;
							break;
						case 2:
							total_failed += dlrsum_bucket.doc_count;
							break;
						case 16:
							total_failed += dlrsum_bucket.doc_count;
							break;
						case 8:
							total_ack += dlrsum_bucket.doc_count;
							break;
						case -1:
							total_invalid += dlrsum_bucket.doc_count;
							break;
						default:
							break;
					}
				}
				let ndnc_bucket = roc_bucket.ndnc.buckets.find(e => e.key_as_string == "true");
				if (ndnc_bucket !== undefined) {
					total_ndnc = ndnc_bucket.doc_count;
				}
				let refund_bucket = roc_bucket.refunds.buckets.find(e => e.key_as_string == "true");
				if (refund_bucket !== undefined) {
					total_refunds = refund_bucket.doc_count;
				}
				roc_data.push({
					key: roc_bucket.key,
					total_sms: roc_bucket.doc_count,
					total_delivered,
					total_failed,
					total_ndnc,
					total_invalid,
					total_ack,
					total_refunds,
				});
			}
			response.roc_data = roc_data;
		}
		if (data.aggs.top_users !== undefined) {
			let top_users = [];
			for (const user_bucket of data.aggs.top_users.buckets) {
				top_users.push({
					user_id: user_bucket.key,
					total_sms: user_bucket.doc_count,
				});
			}
			response.top_users = top_users;
		}
		if (data.aggs.client_routes !== undefined) {
			let client_routes = [];
			for (const cr_bucket of data.aggs.client_routes.buckets) {
				let total_delivered = 0,
					total_failed = 0,
					total_ack = 0;
				for (let dlrsum_bucket of cr_bucket.dlr_for_route.buckets) {
					switch (dlrsum_bucket.key) {
						case 1:
							total_delivered += dlrsum_bucket.doc_count;
							break;
						case 2:
							total_failed += dlrsum_bucket.doc_count;
							break;
						case 16:
							total_failed += dlrsum_bucket.doc_count;
							break;
						case 8:
							total_ack += dlrsum_bucket.doc_count;
							break;
						default:
							break;
					}
				}

				client_routes.push({
					key: cr_bucket.key,
					total_sms: cr_bucket.doc_count,
					total_delivered,
					total_failed,
					total_ack,
				});
			}
			response.client_routes = client_routes;
		}
		if (data.aggs.va_traffic_summary !== undefined) {
			let traffic_summary = {
				dates: [],
				delivered: [],
				failed: [],
				ndnc: [],
				invalid: [],
				smsc_submit: [],
				other: [],
			};
			for (let traffic_bucket of data.aggs.va_traffic_summary.buckets) {
				let delivered = 0,
					failed = 0,
					ndnc = 0,
					invalid = 0,
					smsc_submit = 0,
					other = 0;
				for (let dlr_bucket of traffic_bucket.total_dlr.buckets) {
					switch (dlr_bucket.key) {
						case 1:
							delivered += parseInt(dlr_bucket.doc_count);
							break;
						case 2:
							failed += parseInt(dlr_bucket.doc_count);
							break;
						case 16:
							failed += parseInt(dlr_bucket.doc_count);
							break;
						case 8:
							smsc_submit += parseInt(dlr_bucket.doc_count);
							break;
						case -1:
							invalid += parseInt(dlr_bucket.doc_count);
							break;
						default:
							other += parseInt(dlr_bucket.doc_count);
							break;
					}
				}
				let ndnc_bucket = traffic_bucket.total_ndnc.buckets.find(
					o => o.key_as_string == "true"
				);
				if (ndnc_bucket !== undefined) ndnc += ndnc_bucket.doc_count;
				//push calculated values in array
				traffic_summary.dates.push(traffic_bucket.key_as_string);
				traffic_summary.delivered.push(delivered);
				traffic_summary.failed.push(failed);
				traffic_summary.ndnc.push(ndnc);
				traffic_summary.invalid.push(invalid);
				traffic_summary.smsc_submit.push(smsc_submit);
				traffic_summary.other.push(other);
			}
			response.va_traffic_summary = traffic_summary;
		}
		return response;
	}
};
let parseItemToCsvString = (item, routeTitles, group) =>
	Promise.resolve().then(v => {
		let smsitem = item._source;
		let line = "";
		//date
		let send_ts = new Date(smsitem.submit_time);
		let send_ts_human = `${getOrdinalNum(send_ts.getDate())} ${send_ts.toLocaleString(
			"default",
			{
				month: "short",
			}
		)} ${send_ts
			.getFullYear()
			.toString()
			.substr(-2)} ${send_ts.getHours()}:${send_ts.getMinutes()}:${send_ts.getSeconds()}`;
		//sms type
		let smstypestr = `TEXT`;
		if (smsitem.sms_type.main == "text") {
			if (smsitem.sms_type.unicode == true) {
				smstypestr = `UNICODE`;
			}
			if (smsitem.sms_type.flash == true) {
				smstypestr = `FLASH`;
			}
			if (smsitem.sms_type.unicode == true && smsitem.sms_type.flash == true) {
				smstypestr = `UNICODE-FLASH`;
			}
		} else if (smsitem.sms_type.main == "wap") {
			smstypestr = "WAP";
		} else if (smsitem.sms_type.main == "vcard") {
			smstypestr = "VCARD";
		}
		//route
		let route_title = routeTitles[smsitem.route_id];
		//dlr time
		let dlr_ts_human = "-";
		if (smsitem.dlr.time != null && isNaN(smsitem.dlr.time) == false) {
			let dlr_ts = new Date(smsitem.dlr.time);
			dlr_ts_human = `${getOrdinalNum(dlr_ts.getDate())} ${dlr_ts.toLocaleString("default", {
				month: "short",
			})} ${dlr_ts
				.getFullYear()
				.toString()
				.substr(-2)} ${dlr_ts.getHours()}:${dlr_ts.getMinutes()}:${dlr_ts.getSeconds()}`;
		}
		//smstext
		let smstext = smsitem.sms_text.replace(/\n/g, " ").replace(/\"/g, '""');
		//
		line += `${send_ts_human},`;
		line += `${smsitem.msisdn},`;
		line += `${smstypestr},`;
		line += `${smsitem.sender_id},`;
		line += `${smsitem.user_alias},`;
		line += `${route_title},`;
		if (group == "admin") line += `${smsitem.smsc},`;
		line += `${smsitem.channel},`;
		line += `${smsitem.country.iso} (${smsitem.country.prefix}),`;
		line += `${smsitem.operator.title},`;
		line += `${smsitem.operator.mccmnc},`;
		line += `${smsitem.sms_parts},`;
		line += `${smsitem.cost},`;
		line += `${smsitem.dlr.app_status},`;
		line += `${smsitem.dlr.smpp_response},`;
		line += `${dlr_ts_human},`;
		line += `${smsitem.mysql_id},`;
		line += `"${entities.decode(smstext)}"`;
		return line;
	});

let convertResultsToChartsData = results => {
	//traffic summary object
	let traffic_summary = {
		dates: [],
		delivered: [],
		failed: [],
		ndnc: [],
		invalid: [],
		smsc_submit: [],
		other: [],
	};
	for (let traffic_bucket of results.aggs.traffic_summary.buckets) {
		let delivered = 0,
			failed = 0,
			ndnc = 0,
			invalid = 0,
			smsc_submit = 0,
			other = 0;
		for (let dlr_bucket of traffic_bucket.total_dlr.buckets) {
			switch (dlr_bucket.key) {
				case 1:
					delivered += parseInt(dlr_bucket.doc_count);
					break;
				case 2:
					failed += parseInt(dlr_bucket.doc_count);
					break;
				case 16:
					failed += parseInt(dlr_bucket.doc_count);
					break;
				case 8:
					smsc_submit += parseInt(dlr_bucket.doc_count);
					break;
				case -1:
					invalid += parseInt(dlr_bucket.doc_count);
					break;
				default:
					other += parseInt(dlr_bucket.doc_count);
					break;
			}
		}
		let ndnc_bucket = traffic_bucket.total_ndnc.buckets.find(o => o.key_as_string == "true");
		if (ndnc_bucket !== undefined) ndnc += ndnc_bucket.doc_count;
		//push calculated values in array
		traffic_summary.dates.push(traffic_bucket.key_as_string);
		traffic_summary.delivered.push(delivered);
		traffic_summary.failed.push(failed);
		traffic_summary.ndnc.push(ndnc);
		traffic_summary.invalid.push(invalid);
		traffic_summary.smsc_submit.push(smsc_submit);
		traffic_summary.other.push(other);
	}
	//channels
	let app_sms = 0,
		smpp_sms = 0,
		api_sms = 0;
	for (let channel_bucket of results.aggs.channels.buckets) {
		switch (channel_bucket.key) {
			case "APP":
				app_sms += channel_bucket.doc_count;
				break;
			case "SMPP":
				smpp_sms += channel_bucket.doc_count;
				break;
			case "API":
				api_sms += channel_bucket.doc_count;
				break;
			default:
				break;
		}
	}
	let channels = [
		{ value: smpp_sms, name: "SMPP" },
		{ value: app_sms, name: "Panel" },
		{ value: api_sms, name: "API" },
	];
	//dlr summary
	let total_delivered = 0,
		td_credits = 0,
		td_cost = 0,
		total_failed = 0,
		tf_credits = 0,
		tf_cost = 0,
		total_ndnc = 0,
		tn_credits = 0,
		tn_cost = 0,
		total_invalid = 0,
		ti_credits = 0,
		ti_cost = 0,
		total_ack = 0,
		ta_credits = 0,
		ta_cost = 0,
		total_refunds = 0,
		tr_credits = 0,
		tr_cost = 0,
		total_others = 0,
		to_credits = 0,
		to_cost = 0;
	for (let dlrsum_bucket of results.aggs.dlr_summary.buckets) {
		switch (dlrsum_bucket.key) {
			case 1:
				total_delivered += dlrsum_bucket.doc_count;
				td_credits += dlrsum_bucket.credits.value;
				td_cost += dlrsum_bucket.cost.value;
				break;
			case 2:
				total_failed += dlrsum_bucket.doc_count;
				tf_credits += dlrsum_bucket.credits.value;
				tf_cost += dlrsum_bucket.cost.value;
				break;
			case 16:
				total_failed += dlrsum_bucket.doc_count;
				tf_credits += dlrsum_bucket.credits.value;
				tf_cost += dlrsum_bucket.cost.value;
				break;
			case 8:
				total_ack += dlrsum_bucket.doc_count;
				ta_credits += dlrsum_bucket.credits.value;
				ta_cost += dlrsum_bucket.cost.value;
				break;
			case -1:
				total_invalid += dlrsum_bucket.doc_count;
				ti_credits += dlrsum_bucket.credits.value;
				ti_cost += dlrsum_bucket.cost.value;
				break;
			default:
				total_others += dlrsum_bucket.doc_count;
				to_credits += dlrsum_bucket.credits.value;
				to_cost += dlrsum_bucket.cost.value;
				break;
		}
	}
	let ndnc_bucket = results.aggs.ndnc.buckets.find(e => e.key_as_string == "true");
	if (ndnc_bucket !== undefined) {
		total_ndnc = ndnc_bucket.doc_count;
		tn_cost = ndnc_bucket.cost.value;
		tn_credits = ndnc_bucket.credits.value;
	}
	let refund_bucket = results.aggs.refunded.buckets.find(e => e.key_as_string == "true");
	if (refund_bucket !== undefined) {
		total_refunds = refund_bucket.doc_count;
		tr_cost = refund_bucket.cost.value;
		tr_credits = refund_bucket.credits.value;
	}
	let dlr_summary = {
		total: {
			sms: results.total,
			credits: results.aggs.total_credits.value,
			cost: results.aggs.total_cost.value,
			rate: "-",
		},
		delivered: {
			sms: total_delivered,
			credits: td_credits,
			cost: parseFloat(td_cost).toFixed(2),
			rate: `${Math.ceil((total_delivered / results.total) * 100) || 0}%`,
		},
		failed: {
			sms: total_failed,
			credits: tf_credits,
			cost: parseFloat(tf_cost).toFixed(2),
			rate: `${Math.ceil((total_failed / results.total) * 100) || 0}%`,
		},
		ndnc: {
			sms: total_ndnc,
			credits: tn_credits,
			cost: parseFloat(tn_cost).toFixed(2),
			rate: `${Math.ceil((total_ndnc / results.total) * 100) || 0}%`,
		},
		invalid: {
			sms: total_invalid,
			credits: ti_credits,
			cost: parseFloat(ti_cost).toFixed(2),
			rate: `${Math.ceil((total_invalid / results.total) * 100) || 0}%`,
		},
		smsc_submit: {
			sms: total_ack,
			credits: ta_credits,
			cost: parseFloat(ta_cost).toFixed(2),
			rate: `${Math.ceil((total_ack / results.total) * 100) || 0}%`,
		},
		other: {
			sms: total_others,
			credits: to_credits,
			cost: parseFloat(to_cost).toFixed(2),
			rate: `${Math.ceil((total_others / results.total) * 100) || 0}%`,
		},
		refunds: {
			sms: total_refunds,
			credits: tr_credits,
			cost: parseFloat(tr_cost).toFixed(2),
			rate: `${Math.ceil((total_refunds / results.total) * 100) || 0}%`,
		},
	};
	//sms types
	let textsms = 0,
		unicodesms = 0,
		flashsms = 0,
		unicodeflash = 0,
		wapsms = 0,
		vcardsms = 0;
	for (let smstype_bucket of results.aggs.sms_types.buckets) {
		switch (smstype_bucket.key) {
			case "TEXT":
				textsms += smstype_bucket.doc_count;
				break;
			case "UNICODE":
				unicodesms += smstype_bucket.doc_count;
				break;
			case "FLASH":
				flashsms += smstype_bucket.doc_count;
				break;
			case "UNICODE-FLASH":
				unicodeflash += smstype_bucket.doc_count;
				break;
			case "WAP":
				wapsms += smstype_bucket.doc_count;
				break;
			case "VCARD":
				vcardsms += smstype_bucket.doc_count;
				break;
			default:
				break;
		}
	}
	let sms_types = [
		{ value: textsms, name: "GSM/ASCII" },
		{ value: unicodesms, name: "Unicode" },
		{ value: flashsms, name: "FLASH" },
		{ value: unicodeflash, name: "UnicodeFlash" },
		{ value: wapsms, name: "WAP" },
		{ value: vcardsms, name: "vCard" },
	];
	//networks
	let networks = [];
	for (let nw_bucket of results.aggs.networks.buckets) {
		let nwObj = {
			value: nw_bucket.doc_count,
			name: nw_bucket.key == "" ? "Unknown" : nw_bucket.key,
		};
		networks.push(nwObj);
	}
	//since treemap is being pain in the ass and throwing error if less than 4 operator data
	if (networks.length < 4) {
		networks.push(
			{ value: 0, name: "Generic v1" },
			{ value: 0, name: "Generic v2" },
			{ value: 0, name: "Generic v3" },
			{ value: 0, name: "Generic v4" }
		);
	}
	//countries
	let countries = [];
	for (let ct_bucket of results.aggs.countries.buckets) {
		countries.push({
			iso: ct_bucket.key == "" ? "Unknown" : ct_bucket.key,
			total: ct_bucket.doc_count,
		});
	}
	//finally
	return {
		traffic_summary,
		channels,
		dlr_summary,
		sms_types,
		networks,
		countries,
	};
};
const getOrdinalNum = n => {
	return (
		n + (n > 0 ? ["th", "st", "nd", "rd"][(n > 3 && n < 21) || n % 10 > 3 ? 0 : n % 10] : "")
	);
};
export default searchRouter;
