"use strict";
import { env, require, unserialize } from "./miscHelper.js";
import { readFileSync } from "fs";
import { dbquery } from "./mariadbHelper.js";

let prepareVerifiedSmsObject = async ({ id, sms_shoot_id, user_id, contacts, sms_text }) => {
	//get verified sms agent id from summary table
	let smsdata = await dbquery("getShootSummary", {
		shoot_id: sms_shoot_id,
	});
	if (smsdata === undefined) return false; //campaign not found
	//get verified agent name and create private & public key path
	let vsms_data = JSON.parse(smsdata.vsms_data);
	if (vsms_data.status !== 1) return false; //campaign not verifyable (should never happen)
	let agent_data = await dbquery("getAgentData", {
		agent_id: vsms_data.agent_id,
	});
	let agent_id = vsms_data.agent_id;
	let agentFileName = agent_data.agent_name
		.replaceAll(" ", "-")
		.replaceAll("(", "")
		.replaceAll(")", "");
	let agent_public_key_path = `/root/vsms/keys/verified-sms-${agentFileName}-public-key-P-384.der`;
	let agent_private_key_path = `/root/vsms/keys/verified-sms-${agentFileName}-private-key-P-384-pkcs8.der`;
	let privateKeyAsBytes = readFileSync(agent_private_key_path);
	let privateKey = privateKeyAsBytes.toString("base64");
	//parse the contacts and create the array of devices(contacts), text messages, postback data, and agent IDs.
	let decoded_data = await unserialize({
		body: [Buffer.from(contacts, "base64").toString("utf-8"), smsdata.sms_type],
		type: "bulk",
	});

	let contactdata = decoded_data[0];
	let sms_type = decoded_data[1]; //in case this is needed later, not used in initial dev
	let recipientAndMessages = [];
	for (const contact of contactdata) {
		let vsms_contact = {
			phoneNumber: `+${contact.mobile}`,
			text: contact.text == "" ? sms_text : contact.text,
			postbackData: `+${contact.mobile}`,
			agentId: vsms_data.agent_id,
		};
		recipientAndMessages.push(vsms_contact);
	}
	//prepare response and return it
	return {
		id,
		user_id,
		agent_id,
		sms_shoot_id,
		recipientAndMessages,
		privateKey,
		agent_public_key_path,
	};
};

let parseVerifiedSmsResponse = async (data, promiseObject) => {
	let response = await promiseObject;
	let verifiedMsgs = new Array();
	if (response == "No enabled devices. No hashes stored.") {
		console.log(response + ` Campaign : ${data.sms_shoot_id}`);
		await Promise.all([
			dbquery("runPlainQuery", {
				query: `UPDATE sc_queued_campaigns SET status = 1 WHERE id = ${
					parseInt(data.id) || 0
				} LIMIT 1`,
			}),
		]);
	} else {
		for (const entry of response.entries()) {
			let dbentry = [data.agent_id, data.user_id, data.sms_shoot_id, Math.abs(entry[0])];
			verifiedMsgs.push(dbentry);
		}
		if (verifiedMsgs.length > 0) {
			await Promise.all([
				dbquery("insertVerifiedCampaign", { batchValues: verifiedMsgs }),
				dbquery("runPlainQuery", {
					query: `UPDATE sc_queued_campaigns SET status = 1 WHERE id = ${
						parseInt(data.id) || 0
					} LIMIT 1`,
				}),
			]);
		}
		console.log(
			`Hash completed with ${response.size} responses for Campaign: ${data.sms_shoot_id}`
		);
	}
};

let verifiedAgentProcessor = async data => {};

const _prepareVerifiedSmsObject = prepareVerifiedSmsObject;
export { _prepareVerifiedSmsObject as prepareVerifiedSmsObject };
const _parseVerifiedSmsResponse = parseVerifiedSmsResponse;
export { _parseVerifiedSmsResponse as parseVerifiedSmsResponse };
const _verifiedAgentProcessor = verifiedAgentProcessor;
export { _verifiedAgentProcessor as verifiedAgentProcessor };
