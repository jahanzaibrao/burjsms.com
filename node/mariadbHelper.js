"use strict";
import { env, require } from "./miscHelper.js";
const mariadb = require("mariadb");
//tcp connection on local
if (env.SETUP_MODE == "local") {
	//tcp connection on local
	var mainPool = mariadb.createPool({
		host: env.SERVER_IP,
		user: env.MAIN_DBUSER,
		password: env.MAIN_DBPASS,
		database: env.MAIN_DB,
		acquireTimeout: 60000,
		idleTimeout: 30,
		connectionLimit: 100,
	});
	var ndncPool = mariadb.createPool({
		host: env.SERVER_IP,
		user: env.DND_DBUSER,
		password: env.DND_DBPASS,
		database: env.DND_DB,
		connectionLimit: 5,
	});
} else {
	//socket connection on production
	var mainPool = mariadb.createPool({
		socketPath: env.DB_SOCKET_PATH,
		user: env.MAIN_DBUSER,
		password: env.MAIN_DBPASS,
		database: env.MAIN_DB,
		acquireTimeout: 60000,
		connectionLimit: 100,
	});
	var ndncPool = mariadb.createPool({
		socketPath: env.DB_SOCKET_PATH,
		user: env.DND_DBUSER,
		password: env.DND_DBPASS,
		database: env.DND_DB,
		connectionLimit: 5,
	});
}

const dbquery = async (mode, data) => {
	let selectedPool = 1,
		singleRows = 1;
	let query = "",
		queryArgs = [];
	let batchInsert = 0;
	switch (mode) {
		case "runPlainQuery":
			query = data.query;
			queryArgs = [];
			singleRows = 0;
			break;
		case "smppAuth":
			query = `SELECT user_id,route_id,allowed_ip,tx_max,rx_max,trx_max,tps_max,plan_id FROM sc_smpp_clients WHERE system_id = ? AND smpp_password = ? AND status = 1 LIMIT 1`;
			queryArgs = [data.systemid, data.password];
			break;
		case "getUserInfo":
			query = `SELECT name, login_id, upline_id, avatar, category, email FROM sc_users WHERE user_id = ? LIMIT 1`;
			queryArgs = [data.user];
			break;
		case "getRouteTitle":
			query = `SELECT id, title FROM sc_sms_routes WHERE id = ? LIMIT 1`;
			queryArgs = [data.route];
			break;
		case "getSmppclientInfo":
			query = `SELECT user_id, route_id, plan_id FROM sc_smpp_clients WHERE system_id = ? AND status = 1 LIMIT 1`;
			queryArgs = [data.smppclient];
			break;
		case "getRouteInfo":
			query = `SELECT r.sender_type, r.def_sender, r.max_sid_len, r.template_flag, r.active_time, r.country_id, r.blacklist_ids, r.credit_rule, r.add_pre, s.tlv_ids, s.smsc_id, s.host, s.port, s.username, c.country_code, c.prefix, c.valid_lengths FROM sc_sms_routes r, sc_smpp_accounts s, sc_coverage c WHERE s.id IN (r.smpp_list) AND c.id = r.country_id AND r.id = ?`;
			queryArgs = [data.routeid];
			break;
		case "getSmppInfo":
			query = `SELECT host, port, username FROM sc_smpp_accounts WHERE smsc_id = ? LIMIT 1`;
			queryArgs = [data.smsc_id];
			break;
		case "getCoverageDetailsByIso":
			query = `SELECT id, country_code, country, prefix, valid_lengths, timezone FROM sc_coverage WHERE country_code = ? LIMIT 1`;
			queryArgs = [data.iso];
			break;
		case "getMccMncPlanData":
			query = `SELECT id, route_id, route_coverage  FROM sc_mcc_mnc_plans WHERE id = ? LIMIT 1`;
			queryArgs = [data.planid];
			break;
		case "getMccMncByPrefix":
			query = `SELECT p.*, LENGTH('${data.msisdn}') - LENGTH(REPLACE('${data.msisdn}', p.prefix, '')) AS matched_length FROM sc_nsn_prefix_list AS p WHERE country_prefix = ? AND '${data.msisdn}' LIKE CONCAT(p.prefix, '%') ORDER BY matched_length DESC, p.prefix DESC LIMIT 1`;
			queryArgs = [data.coverage];
			break;
		case "getMccMncDetails":
			query = `SELECT country_iso,country_code,mcc,mnc,mccmnc,brand,operator FROM sc_mcc_mnc_list WHERE mccmnc = ${
				parseInt(data.mccmnc) || 0
			} LIMIT 1`;
			queryArgs = [];
			break;
		case "getMccmncRouteAndCost":
			query = `SELECT route_id, price FROM sc_mcc_mnc_plan_pricing WHERE plan_id = ? AND mccmnc = ? LIMIT 1`;
			queryArgs = [data.plan, data.mccmnc];
			break;
		case "getDefaultCountryPrice":
			query = `SELECT max(price) as price FROM sc_mcc_mnc_plan_pricing WHERE plan_id = ? AND mccmnc IN ( select mccmnc FROM sc_mcc_mnc_list WHERE country_code = ?) LIMIT 1`;
			queryArgs = [data.plan, data.country];
			break;
		case "fetchUserPerms":
			query = `SELECT u.upline_id, u.spam_status, u.opentemp_flag, u.account_type, p.perm_data FROM sc_users u, sc_users_permissions p WHERE p.user_id = u.user_id AND u.user_id = ?`;
			queryArgs = [data.userid];
			break;
		case "getBlacklistDbInfo":
			query = `SELECT table_name, mobile_column FROM sc_blacklist_index WHERE id = ? LIMIT 1`;
			queryArgs = [data.id];
			selectedPool = 2;
			break;
		case "matchBlacklist":
			query = `SELECT ${data.column} as msisdn FROM ${data.table} WHERE ${data.column} IN(${data.mobiles})`;
			queryArgs = [];
			singleRows = 0;
			selectedPool = 2;
			break;
		case "getTlvNamesByIds":
			query = `SELECT tlv_name FROM sc_smpp_tlv WHERE id IN(${data.id_list})`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getCreditDataBySystemid":
			query = `SELECT s.user_id, s.route_id, c.credits, c.validity, c.delv_per, c.price FROM sc_smpp_clients s, sc_users_credit_data c WHERE c.user_id = s.user_id AND c.route_id = s.route_id AND s.system_id = ? LIMIT 1`;
			queryArgs = [data.systemid];
			break;
		case "getWalletCredits":
			query = `SELECT amount FROM sc_users_wallet WHERE user_id = ? LIMIT 1`;
			queryArgs = [data.user];
			break;
		case "getPerSmsPriceByUser":
			query = `SELECT user_id, route_id, price, delv_per FROM sc_users_credit_data WHERE user_id = ? AND route_id = ? LIMIT 1`;
			queryArgs = [data.user, data.route];
			break;
		case "validateSender":
			query = `SELECT id FROM sc_sender_id WHERE sender_id = ? AND req_by = ? AND status = 1 LIMIT 1`;
			queryArgs = [data.sender, data.user];
			break;
		case "getAllApprovedSenders":
			query = `SELECT id,sender_id FROM sc_sender_id WHERE req_by = ? AND status = 1`;
			singleRows = 0;
			queryArgs = [data.user];
			break;
		case "getSenderById":
			query = `SELECT sender_id FROM sc_sender_id WHERE id = ? LIMIT 1`;
			queryArgs = [data.id];
			break;
		case "getAgentData":
			query = `SELECT agent_name, public_key FROM sc_verified_sms_agents WHERE agent_id = ? LIMIT 1`;
			queryArgs = [data.agent_id];
			break;
		case "insertVerifiedCampaign":
			query = `INSERT INTO sc_verified_sms_campaigns(agent_id, user_id, sms_shoot_id, mobile) VALUES (?,?,?,?)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "getApprovedTemplates":
			query = `SELECT content FROM sc_sms_templates WHERE user_id = ? AND route_id = ? AND status = 1`;
			queryArgs = [data.user, data.route];
			singleRows = 0;
			break;
		case "getSpamKeywords":
			query = `SELECT phrase FROM sc_spam_keywords`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getSmppDlr":
			query = `SELECT id, sms_id, sender, msisdn, pdu_seq, dlr, vendor_dlr, smpp_resp_code, logtime FROM sc_smpp_client_dlr WHERE smppclient =  ? AND dlr <> 8 AND status = 0 AND attempts < 10 LIMIT 1000 FOR UPDATE`;
			queryArgs = [data.user];
			singleRows = 0;
			break;
		case "getSmppMo":
			query = `SELECT * FROM sc_smpp_client_mo WHERE smppclient =  ? AND deliver_sm_resp = '' LIMIT 100`;
			queryArgs = [data.user];
			singleRows = 0;
			break;
		case "getClientWhitelistNumbers":
			query = `SELECT mobiles FROM sc_users_whitelist WHERE user_id = ? LIMIT 1`;
			queryArgs = [data.user];
			break;
		case "updateCredits":
			query = `UPDATE sc_users_credit_data SET credits = ? WHERE user_id = ? AND route_id = ? LIMIT 1`;
			queryArgs = [data.credits, data.user, data.route];
			break;
		case "updateWalletCredits":
			query = `UPDATE sc_users_wallet SET amount = ? WHERE user_id = ? LIMIT 1`;
			queryArgs = [data.credits, data.user];
			break;
		case "updateDlrResponse":
			query = `UPDATE sc_smpp_client_dlr_pdustore SET deliver_sm_resp = ? WHERE smppclient = ? AND pdu_seq=? AND deliver_sm_resp='' ORDER BY id DESC LIMIT 1`;
			queryArgs = [data.response, data.smppclient, data.pdu_seq];
			break;
		case "markDlrDone":
			query = `UPDATE sc_smpp_client_dlr SET status = 1 WHERE smppclient = ? AND pdu_seq=? AND dlr <> 8 AND status = 0 ORDER BY id DESC LIMIT 1`;
			queryArgs = [data.smppclient, data.pdu_seq];
			break;
		case "updateSmppMoResponse":
			query = `UPDATE sc_smpp_client_mo SET deliver_sm_resp = ? WHERE smppclient = ? AND sms_id=? AND deliver_sm_resp='' LIMIT 1`;
			queryArgs = [data.response, data.smppclient, data.pdu_seq];
			break;
		case "deleteSmppDlr":
			query = `UPDATE sc_smpp_client_dlr set attempts = attempts + 1 WHERE id IN (${data.idlist})`;
			break;
		case "dlrPduEntry":
			query = `INSERT INTO sc_smpp_client_dlr_pdustore(smppclient, sms_id, pdu_seq, deliver_sm) VALUES (?, ?, ?, ?)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "creditsLogEntry":
			query = `INSERT INTO sc_logs_credits (user_id, amount, route_id, credits_before, credits_after, reference, comments) VALUES (?, ?, ?, ?, ?, ?, ?)`;
			queryArgs = [data.user, data.amount, data.route, data.before, data.after, `SMPP SMS`, `SMS sent via SMPP || BATCHES: ${data.batchid}`];
			break;
		case "insertSmsQuery":
			query = `INSERT INTO sc_smpp_client_sms (batch_id, smpp_smsid, smpp_client, user_id, route_id, smsc, sender_id, mobile, sms_type, sms_text, sms_count, sending_time, status, mccmnc, price, cost, dlr, smpp_resp_code, vendor_dlr, tlv_data, msgdata, platform_data) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "insertDlrQuery":
			query = `INSERT INTO sc_smpp_client_dlr(smppclient, sms_id, sender, msisdn, pdu_seq, dlr, vendor_dlr) VALUES (?,?,?,?,?,?,?)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "addToSqlbox":
			query = `INSERT INTO sc_smpp_incoming(momt, sender, receiver, msgdata, udhdata, time, smsc_id, service, sms_type, mclass, coding, dlr_mask, dlr_url, pid, alt_dcs, charset, boxc_id, meta_data) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "getPendingSearchIndexNumbers":
			query = `SELECT DISTINCT(sms_shoot_id) FROM sc_sent_sms WHERE es_index_id = '' LIMIT 10`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getIndexSmsByShootId":
			query = `SELECT id,sms_shoot_id,user_id,route_id,smsc,sender_id,mobile,sms_text,sms_count,submission_time,sending_time,mccmnc,price,cost,umsgid,status,smpp_resp_code,dlr,vendor_dlr,vendor_msgid,dlr_updated_on,url_visit_flag,url_visit_ts,url_visit_platform,es_index_id FROM sc_sent_sms WHERE sms_shoot_id IN (${data.shoot_id_list}) AND ${data.condition} LIMIT 20000`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getUpdatedIndexSms":
			query = `SELECT id,sms_shoot_id,mobile,status,smpp_resp_code,dlr,vendor_dlr,vendor_msgid,dlr_updated_on,url_visit_flag,url_visit_ts,url_visit_platform,es_index_id FROM sc_sent_sms WHERE ${data.condition} LIMIT 20000`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getPendingSearchIndexNumbersSmpp":
			query = `SELECT batch_id,smpp_smsid,smpp_client,user_id,route_id,smsc,sender_id,mobile,sms_type,sms_text,sms_count,sending_time,status,mccmnc,price,cost,dlr,smpp_resp_code,vendor_dlr,vendor_msgid,dlr_updated_on,tlv_data,platform_data FROM sc_smpp_client_sms WHERE ${data.condition} LIMIT 20000`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getUpdatedIndexSmsSmpp":
			query = `SELECT smpp_smsid,mobile,status,dlr,smpp_resp_code,vendor_dlr,vendor_msgid,dlr_updated_on,es_index_id FROM sc_smpp_client_sms WHERE ${data.condition} LIMIT 20000`;
			queryArgs = [];
			singleRows = 0;
			break;
		case "getShootSummary":
			query = `SELECT campaign_id,sms_shoot_id,user_id,route_id,sender_id,pushed_via,sms_type,sms_text,hide_mobile,contacts_label,tlv_data,vsms_data,platform_data FROM sc_sms_summary WHERE sms_shoot_id = ? LIMIT 1`;
			queryArgs = [data.shoot_id];
			break;
		case "getRefundLogsByShootId":
			query = `SELECT mobile_no as msisdn FROM sc_logs_dlr_refunds WHERE sms_shoot_id = ?`;
			queryArgs = [data.sms_shoot_id];
			singleRows = 0;
			break;
		case "getSmppSmsRefundStatus":
			query = `SELECT id FROM sc_logs_dlr_refunds WHERE sms_shoot_id = ? LIMIT 1`;
			queryArgs = [data.sms_shoot_id];
			break;
		case "addElasticIndexAPP":
			query = `INSERT INTO sc_sent_sms(id, es_index_status, es_index_id) VALUES (?,?,?) ON DUPLICATE KEY UPDATE es_index_status=VALUES(es_index_status), es_index_id=VALUES(es_index_id)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "addElasticIndexSMPP":
			query = `INSERT INTO sc_smpp_client_sms(smpp_smsid, es_index_status, es_index_id) VALUES (?,?,?) ON DUPLICATE KEY UPDATE es_index_status=VALUES(es_index_status), es_index_id=VALUES(es_index_id)`;
			queryArgs = data.batchValues;
			batchInsert = 1;
			break;
		case "masterAppDlrUpdate":
			query = `UPDATE sc_sent_sms SET dlr = ?, vendor_dlr = ?, smpp_resp_code = ?, vendor_msgid = ?, es_index_status = 1 WHERE ${data.condition}`;
			queryArgs = [data.dlr, data.vendor_dlr, data.smpp_resp, data.vendor_msgid];
			break;
		case "getPendingDlrCallbacks":
			query = `SELECT q.id as id, q.route_id as route_id, q.route_title as route_title, q.sender_id as sender_id, q.sms_shoot_id as sms_shoot_id, q.sms_id as sms_id, q.mobile as mobile, q.sms_sent_ts as sms_sent_ts, q.delivery_ts as delivery_ts, q.dlr as dlr, q.vendor_dlr as vendor_dlr, q.operator_reply as operator_reply, q.sms_count as sms_count, q.attempts as attempts, u.callback_url as callback_url FROM sc_api_callback_queue q, sc_api_callback_urls u WHERE q.status = 0 AND q.attempts < ? AND q.callback_url = u.id ORDER BY rand() LIMIT 1000`;
			queryArgs = [data.maxRetry];
			singleRows = 0;
			break;
		case "updateApiCallbackQueue":
			query = `UPDATE sc_api_callback_queue SET status = ?, attempts = ?, callback_response = ? WHERE id = ?`;
			queryArgs = [data.status, data.attempts, data.response, data.id];
			break;
		case "bulkUpdateApiCallbackQueue":
			query = `UPDATE sc_api_callback_queue SET status = ?, attempts = attempts + 1, callback_response = ? WHERE id IN (${data.idList})`;
			queryArgs = [data.status, data.response];
			break;
		case "deleteArchivedSms":
			query = `DELETE FROM sc_sent_sms WHERE ${data.condition}`;
			queryArgs = [];
			break;
		case "updateNewVsmsAgent":
			query = `UPDATE sc_verified_sms_agents SET brand_idf = ?, agent_id = ?, public_key = ?, status = -1 WHERE id = ? LIMIT 1`;
			queryArgs = [data.brand_idf, data.agent_id, data.public_key, data.id];
			break;
		case "updateOldVsmsAgent":
			query = `UPDATE sc_verified_sms_agents SET status = -1 WHERE id = ? LIMIT 1`;
			queryArgs = [data.id];
			break;
		case "confirmVsmsAgent":
			query = `UPDATE sc_verified_sms_agents SET status = 2 WHERE id = ? LIMIT 1`;
			queryArgs = [data.id];
			break;
		case "deleteVsmsAgent":
			query = `DELETE FROM sc_verified_sms_agents WHERE id = ? LIMIT 1`;
			queryArgs = [data.id];
			break;
	}

	try {
		let rows =
			batchInsert == 1
				? await mainPool.batch(query, queryArgs)
				: selectedPool == 1
				? await mainPool.query(query, queryArgs)
				: await ndncPool.query(query, queryArgs);
		if (rows != null) return singleRows == 1 ? rows[0] : rows;
	} catch (err) {
		console.log(`DB Error while executing: ${query}`, err);
	}
};

const _dbquery = dbquery;
export { _dbquery as dbquery };

