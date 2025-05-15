"use strict";
/**
 * This module has 2 main tasks.
 * 1. To create a proper JSON object containing all the sms data and import them to elasticsearch in bulk and refresh the index. Check for update records and perform bulk updates. It also checks for deletable records to remove from indexes\
 * 2. Provide an interface for searching through the records
 */

import { env, require } from "./miscHelper.js";
const fs = require("fs");
var compression = require("compression");
const http = require("http");
const https = require("https");
import express from "express";
import cors from "cors";
import { setIntervalAsync } from "./helper.js";
import { parsePanelAndApiSubmissions, parseSmppSubmissions, updateMysqlIndexStatus, getMysqlData } from "./smsIndexHelper.js";
import { dbquery } from "./mariadbHelper.js";
import { importObjectsToIndex, updateObjectsInIndex } from "./elasticHelper.js";
import searchRouter from "./routes/search.js";
import authRouter from "./routes/auth.js";
var cluster = require("cluster");

if (cluster.isMaster) {
	//fork
	let elasticImportWorker = cluster.fork({
		WorkerName: "elasticImportWorker",
	});
	let elasticSearchWorker_main = cluster.fork({
		WorkerName: "elasticSearchWorker_main",
	});
	let elasticSearchWorker_helper = cluster.fork({
		WorkerName: "elasticSearchWorker_helper",
	});
	// Respawn if one of the process exits
	cluster.on("exit", function (coreWorker, code, signal) {
		let workerTitle = "";
		if (coreWorker == elasticImportWorker) {
			workerTitle = "elasticImportWorker";
			elasticImportWorker = cluster.fork({
				WorkerName: "elasticImportWorker",
			});
		}
		if (coreWorker == elasticSearchWorker_main) {
			workerTitle = "elasticSearchWorker_main";
			elasticSearchWorker_main = cluster.fork({
				WorkerName: "elasticSearchWorker_main",
			});
		}
		if (coreWorker == elasticSearchWorker_helper) {
			workerTitle = "elasticSearchWorker_helper";
			elasticSearchWorker_helper = cluster.fork({
				WorkerName: "elasticSearchWorker_helper",
			});
		}
		console.log(`Worker - ${workerTitle} - Exited due to error. Respawned ...`);
	});
} else {
	if (process.env.WorkerName == "elasticImportWorker") {
		//This portion will fetch and write data to external source to be read by another process and be indexed into elasticsearch
		setIntervalAsync(async () => {
			//since running mysql queries for every sms object results in painfully slow parsing (10k records in 8 mins), we will get all the mysql data like routes, users, sender, coverage, mccmnc etc and supply them to the parser function
			let mysqlData = await getMysqlData();
			//index the panel/api and smpp submissions
			console.time("Parsing For Index");
			let smsObjects = await Promise.all([
				parsePanelAndApiSubmissions("getPendingSearchIndexNumbers", mysqlData),
				parseSmppSubmissions("getPendingSearchIndexNumbersSmpp", mysqlData),
			]);
			console.timeEnd("Parsing For Index");
			//smsobjects contains 2 arrays of array of sms_shoot_id/batch_id containing proper sms object to be added in our ES node
			console.time("Indexing...");
			let smsAppObjects = await importObjectsToIndex(smsObjects[0]);
			await new Promise((r) => setTimeout(r, 5000)); //take a 5 sec break
			let smsSmppObjects = await importObjectsToIndex(smsObjects[1], "SMPP");
			console.timeEnd("Indexing...");
			//update mysql with elastic indices and status
			console.time("Updating Mysql..");
			await Promise.all([updateMysqlIndexStatus(smsAppObjects.items), updateMysqlIndexStatus(smsSmppObjects.items, "SMPP")]);
			console.timeEnd("Updating Mysql..");
			console.log("A cycle of Indexing new SMS data was completed...");
			//wait 5 seconds
			await new Promise((r) => setTimeout(r, 5000));
			//get updated data from both app/smpp submissions
			console.time("Parsing For Index Update");
			let smsUpdateObjects = await Promise.all([
				parsePanelAndApiSubmissions("getUpdateSearchIndexNumbers", mysqlData),
				parseSmppSubmissions("getUpdateSearchIndexNumbersSmpp", mysqlData),
			]);
			console.timeEnd("Parsing For Index Update");
			//update elasticsearch index
			console.time("Updating Index...");
			let smsAppUpdatedObjects = await updateObjectsInIndex(smsUpdateObjects[0]);
			await new Promise((r) => setTimeout(r, 5000)); //take a 5 sec break
			let smsSmppUpdatedObjects = await updateObjectsInIndex(smsUpdateObjects[1], "SMPP");
			console.timeEnd("Updating Index...");
			//update mysql
			console.time("Updating Mysql..");
			await Promise.all([
				updateMysqlIndexStatus(smsAppUpdatedObjects.items, "APP", "update"),
				updateMysqlIndexStatus(smsSmppUpdatedObjects.items, "SMPP", "update"),
			]);
			console.timeEnd("Updating Mysql..");
			mysqlData = null;
			console.log("A cycle of Updating Indices was completed...");
			//Send Pulse
			let dt = new Date();
			let formatted_ts = `${dt.getFullYear().toString().padStart(4, "0")}-${(dt.getMonth() + 1).toString().padStart(2, "0")}-${dt
				.getDate()
				.toString()
				.padStart(2, "0")} ${dt.getHours().toString().padStart(2, "0")}:${dt.getMinutes().toString().padStart(2, "0")}:${dt
				.getSeconds()
				.toString()
				.padStart(2, "0")}`;
			let sendPulse = await dbquery("runPlainQuery", {
				query: `UPDATE sc_app_processes SET last_pulse = '${formatted_ts}' WHERE process_name = 'ES_INDEX_PROCESS' LIMIT 1`,
			});
		}, 120000);
	}

	if (process.env.WorkerName == "elasticSearchWorker_main" || process.env.WorkerName == "elasticSearchWorker_helper") {
		//initialize app to accept search results
		const app = express();
		app.use(cors());
		app.use(express.json({ limit: "150mb" }));
		app.use(compression());
		const port = process.env.PORT || 5305;
		app.use("/search", searchRouter);
		app.use("/auth", authRouter);

		app.listen(port, "0.0.0.0", () => console.log(`Search interface started and listening on port ${port}...`));
	}
}
