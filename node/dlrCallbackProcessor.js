"use strict";
import { dbquery } from "./mariadbHelper.js";
import {
	env,
	doRequests_cb,
	require,
	apiCalls,
	sortApiCalls,
	bulkApiCall,
} from "./miscHelper.js";
import {setIntervalAsync} from "./helper.js"; 
const fetch = require("node-fetch");
var cluster = require("cluster");

if (cluster.isMaster) {
	//fork
	let singleWorker = cluster.fork({ WorkerName: "singleWorker" });
	let batchWorker = cluster.fork({ WorkerName: "batchWorker" });

	// Respawn if one of the process exits
	cluster.on("exit", function (coreWorker, code, signal) {
		let workerTitle = "";
		if (coreWorker == singleWorker) {
			workerTitle = "singleWorker";
			singleWorker = cluster.fork({ WorkerName: "singleWorker" });
		}
		if (coreWorker == batchWorker) {
			workerTitle = "batchWorker";
			batchWorker = cluster.fork({ WorkerName: "batchWorker" });
		}
		console.log(`Worker - ${workerTitle} - Exited due to error. Respawned ...`);
	});
} else {
	if (process.env.WorkerName == "singleWorker") {
		setIntervalAsync(async () => {
			let mode = await doRequests_cb(
				`https://${env.ADMIN_DOMAIN}/getCallbackConfigData/mode`
			);
			if (mode == 0) {
				//instant single dlr enabled
				console.log(`Instant DLR callback running....`);
				let retry = await doRequests_cb(
					`https://${env.ADMIN_DOMAIN}/getCallbackConfigData/retry`
				);
				let pending_callbacks = await dbquery("getPendingDlrCallbacks", {
					maxRetry: parseInt(retry),
				});
				if (pending_callbacks.length > 0) {
					console.log(`Queued Callbacks found`);
					let results = await Promise.all(pending_callbacks.map(apiCalls));
					let updateCalls = results.map(data => {
						if (data.attempts >= retry) {
							data.status = 2;
						}
						dbquery("updateApiCallbackQueue", data);
					});
					console.log(`Awaiting Update Calls`);
					await Promise.all(updateCalls);
				}
			}
		}, 500);
	}

	if (process.env.WorkerName == "batchWorker") {
		setIntervalAsync(async () => {
			let mode = await doRequests_cb(
				`https://${env.ADMIN_DOMAIN}/getCallbackConfigData/mode`
			);
			if (mode == 1) {
				//batch dlr callback enabled
				console.log(`Bulk DLR callback running....`);
				let retry = await doRequests_cb(
					`https://${env.ADMIN_DOMAIN}/getCallbackConfigData/retry`
				);
				let pending_callbacks = await dbquery("getPendingDlrCallbacks", {
					maxRetry: parseInt(retry),
				});
				if (pending_callbacks && pending_callbacks.length > 0) {
					console.log(`Queued Callbacks found`);
					let sortedCallbacks = await sortApiCalls(pending_callbacks);
					let callbackUrls = Object.keys(sortedCallbacks);
					let taskArray = new Array();
					for (const cblink of callbackUrls) {
						taskArray.push(bulkApiCall(cblink, sortedCallbacks[cblink]));
					}
					let results = await Promise.all(taskArray);
					let updateCalls = results.map(data => {
						dbquery("bulkUpdateApiCallbackQueue", data);
					});
					await Promise.all(updateCalls);
				}
			}
		}, 900000);
	}
}
