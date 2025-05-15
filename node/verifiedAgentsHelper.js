"use strict";
import { env, require } from "./miscHelper.js";
const { exec } = require("child_process");
import { readFileSync } from "fs";
const apiHelper = require("./vsms/lib/api_helper.js");
import { dbquery } from "./mariadbHelper.js";
const verifiedSmsServiceClient = require("./vsms/lib/verified_sms_client_library/verfied_sms_service_client.js");

/**
 * Functions to process pending tasks for Verified SMS agents
 * @returns true on success
 */

let processVerifiedAgent = async agentData => {
	//if brand_idf is empty, need to create the brand and agent both
	if (agentData.brand_idf == "") {
		//new agent needs to be created that's for sure, but do we need to create new brand?
		//check if the brand with the name already exist if not create it and get the brand ID
		let allBrands = await listVsmsBrands();
		let brandData = allBrands
			? allBrands.find(br => br.displayName == agentData.agent_brand)
			: false;
		if (brandData) {
			//brand with same name already exists
			//get the brand idf and create the agent
			let agentResponse = await performVsmsAgentCreation(brandData, agentData);
			return agentResponse;
		} else {
			//create this new brand
			let newBrandData = await createBrand(agentData.agent_brand);
			//create the agent
			let agentResponse = await performVsmsAgentCreation(newBrandData, agentData);
			return agentResponse;
		}
	} else {
		//if old agent update sender ids
		let senderList = JSON.parse(agentData.sender_prefix_percom);
		let sendersObj = senderList.map(s => {
			let sender = s.split(",");
			return {
				countryCode: sender[1].trim(),
				senderId: sender[0].trim(),
			};
		});
		try {
			const apiObject = await apiHelper.init();
			// setup the parameters for the API call
			const apiParams = {
				auth: apiObject.authClient,
				name: `brands/${agentData.brand_idf}/agents/${agentData.agent_id}`,
				resource: {
					verifiedSmsAgent: {
						senders: sendersObj,
					},
				},
				updateMask: "verifiedSmsAgent.senders",
			};

			let response = await apiObject.bcApi.brands.agents.patch(apiParams, {});

			return {
				mode: "update",
				agentDbId: agentData.id,
			};
		} catch (error) {
			console.log(`Updating agent ${agentData.agent_name} failed...`);
			console.log(error);
		}
	}

	//return
};

let deleteVerifiedAgent = async agentData => {
	try {
		let resp = await deleteAgent(`brands/${agentData.brand_idf}/agents/${agentData.agent_id}`);
		return {
			mode: "delete",
			agentDbId: agentData.id,
		};
	} catch (error) {
		console.log(`Deletion failed for the agent ${agentData.agent_name}`);
		console.log(error);
	}
};

let checkAgentVerificationStatus = async agentData => {
	try {
		const apiObject = await apiHelper.init();
		const apiParams = {
			auth: apiObject.authClient,
			name: `brands/${agentData.brand_idf}/agents/${agentData.agent_id}/verification`,
		};
		let response = await apiObject.bcApi.brands.agents.getVerification(apiParams, {});
		if (response.data.verificationState == "VERIFICATION_STATE_VERIFIED") {
			return {
				mode: "verify",
				agentDbId: agentData.id,
			};
		} else {
			return {};
		}
	} catch (error) {
		console.log(`Verification check failed for ${agentData.agent_name}`);
		console.log(error);
	}
};

/**
 * List of API sample functions from Google SDK
 */

/**
 * Creates a brand with the given name.
 */
async function createBrand(brandName) {
	return new Promise((resolve, reject) => {
		const apiConnector = apiHelper.init();
		apiConnector.then(function (apiObject) {
			// setup the parameters for the API call
			const apiParams = {
				auth: apiObject.authClient,
				resource: {
					displayName: brandName,
				},
			};

			apiObject.bcApi.brands.create(apiParams, {}, function (err, response) {
				if (err !== undefined && err !== null) {
					reject(err);
				} else {
					resolve(response.data);
				}
			});
		});
	});
}

/**
 * Based on the brand name, looks up the brand details.
 * @param {string} brandName The unique identifier for the
 * brand in "brands/BRAND_ID" format.
 * @return {object} Returns a promise resolving to the updated brand object.
 */
async function getBrand(brandIdfString) {
	return new Promise((resolve, reject) => {
		const apiConnector = apiHelper.init();
		apiConnector
			.then(function (apiObject) {
				const apiParams = {
					auth: apiObject.authClient,
					name: brandIdfString,
				};

				apiObject.bcApi.brands.get(apiParams, {}, function (err, response) {
					if (err !== undefined && err !== null) {
						console.log(err);
						reject(err);
					} else {
						resolve(response.data);
					}
				});
			})
			.catch(function (err) {
				console.log(err);
				reject(err);
			});
	});
}

/**
 * Lists all brands.
 */
async function listVsmsBrands() {
	const apiObject = await apiHelper.init();
	try {
		const apiParams = {
			auth: apiObject.authClient,
		};

		// send the client the message
		let response = await apiObject.bcApi.brands.list(apiParams, {});
		let brands = [];
		if (response.data != undefined) {
			brands = response.data.brands;
		}
		return brands;
	} catch (error) {
		console.log(`Listing all brands failed`);
		console.log(error);
	}
}

/**
 * Based on the brand name, deletes the brand. Deleting a brand with
 * associated agents will also result in the agents also being deleted.
 * Only brands without verified agents can be deleted.
 * @param {string} brandName The unique identifier for the
 * brand in "brands/BRAND_ID" format.
 * @return {object} Returns a promise resolving to the updated brand object.
 */
async function deleteBrand(brandName) {
	return new Promise((resolve, reject) => {
		const apiConnector = apiHelper.init();
		apiConnector
			.then(function (apiObject) {
				const apiParams = {
					auth: apiObject.authClient,
					name: brandName,
				};

				apiObject.bcApi.brands.delete(apiParams, {}, function (err, response) {
					if (err !== undefined && err !== null) {
						console.log(err);
						reject(err);
					} else {
						resolve(response.data);
					}
				});
			})
			.catch(function (err) {
				console.log(err);
				reject(err);
			});
	});
}

/**
 * For a real Verified SMS Agent, you should populate the senders list with all the sender IDs
 * available for the brand.
 * @param {string} brandName The object to be printed.
 * @return {obj} A promise resolving to the agent object returned by the api.
 */

function createVsmsAgent(brandName, agentObject) {
	return new Promise((resolve, reject) => {
		const apiConnector = apiHelper.init();
		apiConnector.then(apiObject => {
			const params = {
				auth: apiObject.authClient,
				parent: brandName,
				resource: agentObject,
			};

			apiObject.bcApi.brands.agents.create(params, {}, (err, response) => {
				if (err !== undefined && err !== null) {
					reject(err);
				} else {
					//printObjectEntities(response.data);
					resolve(response.data);
				}
			});
		});
	});
}

/**
 * Based on the agent name, deletes the agent. Only a non-verified agent can be deleted.
 *
 * @param {string} agentName The unique identifier for the in
 * "brands/BRAND_ID/agents/AGENT_ID" format.
 * @return {obj} A promise which resolves to the agent object return
 * by the api.
 */
function deleteAgent(agentName) {
	return new Promise((resolve, reject) => {
		const apiConnector = apiHelper.init();
		apiConnector.then(apiObject => {
			const apiParams = {
				auth: apiObject.authClient,
				name: agentName,
			};

			apiObject.bcApi.brands.agents.delete(apiParams, {}, (err, response) => {
				if (err !== undefined && err !== null) {
					reject(err);
				} else {
					resolve(response.data);
				}
			});
		});
	});
}

/**
 *
 * @param {*} command that need to be executed
 * promisify the exec command
 */

function execPromise(command) {
	return new Promise(function (resolve, reject) {
		exec(command, { cwd: "/root/vsms/keys" }, (error, stdout, stderr) => {
			if (error) {
				reject(error);
				return;
			}

			resolve(stdout.trim());
		});
	});
}

const performVsmsAgentCreation = async (brandData, agentData) => {
	try {
		let brandidf = brandData.name;
		let senderList = JSON.parse(agentData.sender_prefix_percom);
		let sendersObj = senderList.map(s => {
			let sender = s.split(",");
			return {
				countryCode: sender[1].trim(),
				senderId: sender[0].trim(),
			};
		});
		const agentObject = {
			displayName: agentData.agent_name,
			verifiedSmsAgent: {
				description: agentData.agent_desc,
				logoUrl: agentData.logo,
				senders: sendersObj,
			},
		};
		let gapiAgentResponse = await createVsmsAgent(brandidf, agentObject);
		console.log(gapiAgentResponse);
		//get agent id from this response
		let resp = gapiAgentResponse.name.split("/");
		let agentId = resp[3];
		console.log(`Agent Created with ID ${agentId}. Proceeding to verify..`);
		//create private and public keys
		let completeAgentIdParam = `${brandidf}/agents/${agentId}`;
		let agentName = agentData.agent_name;
		//file paths
		let agentFileName = agentName.replaceAll(" ", "-").replaceAll("(", "").replaceAll(")", "");
		let pemPriKey = `openssl ecparam -name secp384r1 -genkey -outform PEM -noout -out verified-sms-${agentFileName}-private-key-P-384.pem`;
		let derPrikey = `openssl pkcs8 -topk8 -nocrypt -in verified-sms-${agentFileName}-private-key-P-384.pem -outform DER -out verified-sms-${agentFileName}-private-key-P-384-pkcs8.der`;
		let derPubKey = `openssl ec -in verified-sms-${agentFileName}-private-key-P-384.pem -pubout -outform DER -out verified-sms-${agentFileName}-public-key-P-384.der`;

		let resp1 = await execPromise(pemPriKey);
		let resp2 = await execPromise(derPrikey);
		let resp3 = await execPromise(derPubKey);
		console.log(`Key files are created..`);
		//send the key with google
		let pkbytes = readFileSync(
			`/root/vsms/keys/verified-sms-${agentFileName}-public-key-P-384.der`
		);
		verifiedSmsServiceClient.initWithServiceAccount(
			require(env.VERIFIED_SMS_SERVICE_ACCOUNT_PATH)
		);

		let publicKey = pkbytes.toString("base64");
		let keyres = await verifiedSmsServiceClient.updateKey(agentId, publicKey);
		console.log(keyres);
		console.log(`key added to Google`);
		//send verification request to Google
		const agentVerificationContact = {
			agentVerificationContact: {
				partnerName: env.VERIFIED_SMS_PARTNER_NAME,
				partnerEmailAddress: env.VERIFIED_SMS_PARTNER_EMAIL,
				brandContactName: agentData.brand_user_name,
				brandContactEmailAddress: agentData.brand_user_email,
				brandWebsiteUrl: agentData.brand_user_website,
			},
		};

		const apiObject = await apiHelper.init();
		const apiParams = {
			auth: apiObject.authClient,
			name: completeAgentIdParam,
			resource: agentVerificationContact,
		};
		let resp4 = await apiObject.bcApi.brands.agents.requestVerification(apiParams, {});
		console.log(`Verification request sent to Google successfully`);
		return {
			mode: "add",
			agentDbId: agentData.id,
			agentBrandIdf: brandidf,
			agentVsmsId: agentId,
			agentPubKey: publicKey,
		};
	} catch (e) {
		console.error(e);
	}
};

const updateAgentsMySql = async agentData => {
	if (!agentData) return;
	if (agentData.mode == "add") {
		let brandIdfParts = agentData.agentBrandIdf.split("/");
		let dbres = await dbquery("updateNewVsmsAgent", {
			id: agentData.agentDbId,
			brand_idf: brandIdfParts[1],
			agent_id: agentData.agentVsmsId,
			public_key: agentData.agentPubKey,
		});
	}
	if (agentData.mode == "update") {
		let dbres = await dbquery("updateOldVsmsAgent", {
			id: agentData.agentDbId,
		});
	}
	if (agentData.mode == "verify") {
		let dbres = await dbquery("confirmVsmsAgent", {
			id: agentData.agentDbId,
		});
	}
	if (agentData.mode == "delete") {
		let dbres = await dbquery("deleteVsmsAgent", {
			id: agentData.agentDbId,
		});
	}
	return;
};

function listAgents(brandName) {
	const apiConnector = apiHelper.init();
	apiConnector.then(apiObject => {
		// setup the parameters for the API call
		const apiParams = {
			auth: apiObject.authClient,
			parent: brandName,
		};

		apiObject.bcApi.brands.agents.list(apiParams, {}, (err, response) => {
			if (err !== undefined && err !== null) {
				console.log("Error:");
				console.log(err);
			} else {
				console.log(response.data.agents);
			}
		});
	});
}

(async () => {
	//listAgents("brands/69d5e450-d1e4-4994-aaa7-c97343d9716d");
})();

const _processVerifiedAgent = processVerifiedAgent;
export { _processVerifiedAgent as processVerifiedAgent };
const _updateAgentsMySql = updateAgentsMySql;
export { _updateAgentsMySql as updateAgentsMySql };
const _deleteVerifiedAgent = deleteVerifiedAgent;
export { _deleteVerifiedAgent as deleteVerifiedAgent };
const _checkAgentVerificationStatus = checkAgentVerificationStatus;
export { _checkAgentVerificationStatus as checkAgentVerificationStatus };
