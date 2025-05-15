"use strict";
import { env, require } from "./miscHelper.js";
import { setIntervalAsync } from "./helper.js";
import { dbquery } from "./mariadbHelper.js";
import { prepareVerifiedSmsObject, parseVerifiedSmsResponse } from "./verifiedQueueHelper.js";
const verifiedSmsServiceClient = require("./vsms/lib/verified_sms_client_library/verfied_sms_service_client.js");
import {
	processVerifiedAgent,
	updateAgentsMySql,
	checkAgentVerificationStatus,
	deleteVerifiedAgent,
} from "./verifiedAgentsHelper.js";
setIntervalAsync(async () => {
	console.log(`Checking Queue for Verified SMS Campaigns...`);
	let verified_campaigns = await dbquery("runPlainQuery", {
		query: `SELECT id, sms_shoot_id, user_id, contacts, sms_text FROM sc_queued_campaigns WHERE status = -1 LIMIT 10`,
	});
	//get sorted data and private key for all the batches
	let campaign_data_list = await Promise.all(verified_campaigns.map(prepareVerifiedSmsObject));
	let verifiedHashPromiseList = [];
	verifiedSmsServiceClient.initWithServiceAccount(require(env.VERIFIED_SMS_SERVICE_ACCOUNT_PATH));
	for (const verified_batch of campaign_data_list) {
		let createHashesPromise = verifiedSmsServiceClient.createHashes(
			verified_batch.recipientAndMessages,
			verified_batch.privateKey
		);
		verifiedHashPromiseList.push(parseVerifiedSmsResponse(verified_batch, createHashesPromise));
	}
	let response = await Promise.all(verifiedHashPromiseList);
}, 30000);

setIntervalAsync(async () => {
	console.log(`Checking for Verified SMS Agents Add/Update...`);
	let pending_agents = await dbquery("runPlainQuery", {
		query: `SELECT id, user_id, brand_user_name, brand_user_email, brand_user_website, agent_name, agent_brand, brand_idf, agent_desc, logo, agent_id, sender_prefix_percom FROM sc_verified_sms_agents WHERE status IN (1,3) LIMIT 5`,
	});
	//the tasks could be to create a new agent or update agent like display name or sender id list
	if (pending_agents != undefined) {
		let taskresponses = await Promise.all(pending_agents.map(processVerifiedAgent));
		let completedPromise = [];
		for (const processAgent of taskresponses) {
			completedPromise.push(updateAgentsMySql(processAgent));
		}
		await Promise.all(completedPromise);
	}

	console.log(`Checking if any Agents are marked for deletion...`);
	let deleted_agents = await dbquery("runPlainQuery", {
		query: `SELECT id, user_id, brand_user_name, brand_user_email, brand_user_website, agent_name, agent_brand, brand_idf, agent_desc, logo, agent_id, sender_prefix_percom FROM sc_verified_sms_agents WHERE status = 4 LIMIT 5`,
	});
	if (deleted_agents != undefined) {
		let deltaskresponses = await Promise.all(deleted_agents.map(deleteVerifiedAgent));
		let completedDelPromise = [];
		for (const processAgent of deltaskresponses) {
			completedDelPromise.push(updateAgentsMySql(processAgent));
		}
		await Promise.all(completedDelPromise);
	}
	console.log(`Checking verified status of all the agents...`);
	let allMarkedAgents = await dbquery("runPlainQuery", {
		query: `SELECT id, user_id, brand_user_name, brand_user_email, brand_user_website, agent_name, agent_brand, brand_idf, agent_desc, logo, agent_id, sender_prefix_percom FROM sc_verified_sms_agents WHERE status = -1`,
	});
	if (allMarkedAgents != undefined) {
		let vertaskresponses = await Promise.all(allMarkedAgents.map(checkAgentVerificationStatus));
		let completedVerPromise = [];
		for (const processAgent of vertaskresponses) {
			if (processAgent != {}) completedVerPromise.push(updateAgentsMySql(processAgent));
		}
		await Promise.all(completedVerPromise);
	}
	console.log(`A Cycle of VSMS agents process finished...`);
}, 300000);
