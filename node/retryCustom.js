const mariadb = require("mariadb");
const { promisify } = require("util");
const env = require("dotenv").config().parsed;
const batchdelay = env.BATCH_DELAY;
const Entities = require("html-entities").AllHtmlEntities;
const entities = new Entities();
const sqlStr = require("sqlstring");
const systemos = require("os");
var dateFormat = require("dateformat");

const pool = mariadb.createPool({
	socketPath: env.DB_SOCKET_PATH,
	user: env.MAIN_DBUSER,
	password: env.MAIN_DBPASS,
	database: env.MAIN_DB,
	connectionLimit: 100,
});

async function dbquery(mode, data = null) {
	let conn;
	let singlerows = 1;
	try {
		conn = await pool.getConnection();
		if (mode === "custom") {
			var rows = await conn.query(data.queryStr);
			singlerows = 0;
		}
		if (mode === "run") {
			var rows = await conn.query(data.query);
		}
	} catch (err) {
		throw err;
	} finally {
		if (conn) conn.release(); //release to pool
	}
	if (rows != null) return singlerows == 1 ? rows[0] : rows;
}

async function addToSqlbox(smppclient, data) {
	let query = `INSERT INTO sc_smpp_incoming(momt,sender,receiver,msgdata,udhdata,time,smsc_id,service,sms_type,mclass,coding,dlr_mask,dlr_url,charset,boxc_id) VALUES`;
	for (let i = 0, batchlen = data.length; i < batchlen; ++i) {
		let sms = data[i];
		let pdustring = Buffer.from(sms.msgdata, "base64").toString("ascii");
		let dbpdu;
		if (pdustring) dbpdu = JSON.parse(pdustring);
		let newlinetxt = sms.sms_text.replace(/\/n/g, systemos.EOL).replace(/\n/g, systemos.EOL);
		let smstext = newlinetxt;
		let mclass = 1;
		let coding = 0;
		let udh = "";
		let charset = "WINDOWS-1252";
		pdu = Array.isArray(dbpdu.pdu) ? JSON.parse(dbpdu[0]).pdu : dbpdu.pdu;
		let dlrurl = `https://${
			env.ADMIN_DOMAIN
		}/getSmppDlr/index?smppclient=${smppclient}&sender=${encodeURIComponent(
			sms.sender_id
		)}&routeid=${sms.route_id}&smsid=${sms.smpp_smsid}&userid=${
			sms.user_id
		}&persmscount=1&pdu_seq=${pdu.sequence_number}&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F`;

		query += `('MT','${sms.sender_id}','${sms.mobile}','${encodeURIComponent(
			sqlStr.escape(smstext)
		)
			.replace(/%5C/g, "%0A")
			.replace(/'/g, "%27")}','${udh}',${dbpdu.submission_time},'${
			dbpdu.smsc_id
		}','${smppclient}',2,${mclass},${coding},31,'${dlrurl}','${charset}','smppcubebox'),`;
	}
	query = query.slice(0, -1);
	await dbquery("run", { query: query });
	console.log("Done.....");
}

(async () => {
	let retry_batches = await dbquery("custom", {
		queryStr: `SELECT smpp_smsid,smpp_client,user_id,route_id,sender_id,mobile,sms_text,msgdata  FROM sc_smpp_client_sms WHERE sender_id = 'TOUTCANAL+' LIMIT 2`,
	});
	await addToSqlbox("tosmpp1", retry_batches);
})();
