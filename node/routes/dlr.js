"use strict";
import { env, require } from "../miscHelper.js";
import { dbquery } from "../mariadbHelper.js";
const dlrRouter = require("express").Router();

dlrRouter.route("/app").get(async (req, res) => {
	try {
		//perform update query
		let body = req.query,
			dlr = body.dlr;
		let error_code = "",
			smppcode = "";
		let vendor_dlr = decodeURIComponent(body.vendor_dlr.replace(/\+/g, "%20"));
		if (vendor_dlr.indexOf("NACK") === -1) {
			//valid dlr
			let stat = vendor_dlr.match(new RegExp(`stat:(.*) err`));
			let err = vendor_dlr.match(new RegExp(`err:(.*) text`));
			smppcode = stat == null ? "" : stat[1];
			error_code = err == null ? "" : err[1];
		} else {
			//submit failed
			smppcode = "NACK";
			error_code = vendor_dlr.split("/")[1];
		}
		//santizie
		if (dlr == 1 && error_code == "") {
			error_code = "000";
			smppcode = "DELIVRD";
		}
		if (dlr == 2 && error_code == "") {
			error_code = "-4";
			smppcode = "UNDELIV";
		}
		if (dlr == 16 && error_code == "") {
			error_code = "-4";
			smppcode = "REJECTD";
		}
		if (dlr == 8) {
			error_code = "-6";
			smppcode = "ACK";
		}
		//prepare query object
		let condition =
			body.personalize !== undefined
				? `sms_shoot_id ='${body.shoot_id}' AND mobile = ${body.mobile}`
				: `umsgid = '${body.umsgid}'`;

		await dbquery("masterAppDlrUpdate", {
			condition: condition,
			dlr: dlr,
			vendor_dlr: error_code,
			smpp_resp: smppcode,
			vendor_msgid: body.vmsgid,
		});
		console.log(`DLR Updated for ${body.shoot_id} mobile: ${body.mobile} dlr:${dlr}`);
		res.json({
			message: "DONE",
		});
	} catch (error) {
		console.log(error);
		res.json({ error: error });
	}
});

export default dlrRouter;
