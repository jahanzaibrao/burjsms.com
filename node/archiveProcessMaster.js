"use strict";
/**
 * This module has 2 main tasks.
 * 1. Auto archive old data into MongoDB and remove from MySQL
 * 2. Provide an interface for searching through the archive records
 */
import { env, require } from "./miscHelper.js";
const fs = require("fs");
var compression = require("compression");
const http = require("http");
const https = require("https");
import express from "express";
import cors from "cors";
import { setIntervalAsync } from "./helper.js";
import { dbquery } from "./mariadbHelper.js";
import getArchiveRouter from "./routes/archive.js";
import { getSmsForArchiving, deleteArchivedRecords } from "./elasticHelper.js";
import { importDataInArchive, verifyMongoSave } from "./mongoHelper.js";
var cluster = require("cluster");

if (cluster.isMaster) {
	//fork
	let archiveWorker = cluster.fork({
		WorkerName: "archiveWorker",
	});
	let archiveFetcher = cluster.fork({
		WorkerName: "archiveFetcher",
	});
	// Respawn if one of the process exits
	cluster.on("exit", async function (coreWorker, code, signal) {
		let workerTitle = "";
		if (coreWorker == archiveWorker) {
			//start the process
			workerTitle = "archiveWorker";
			archiveWorker = cluster.fork({
				WorkerName: "archiveWorker",
			});
		}
		if (coreWorker == archiveFetcher) {
			workerTitle = "archiveFetcher";
			archiveFetcher = cluster.fork({
				WorkerName: "archiveFetcher",
			});
		}

		console.log(`Worker - ${workerTitle} - Exited due to error. Respawned ...`);
		console.log(code);
		console.log(signal);
	});
} else {
	if (process.env.WorkerName == "archiveWorker") {
		//This portion will fetch data from ES and save into MongoDB and delete from ES and MySQL
		setIntervalAsync(async () => {
			//get the duration from the DB
			let archiveSetting = await dbquery("runPlainQuery", {
				query: `SELECT var_value FROM sc_misc_vars WHERE var_name = 'AUTO_ARCHIVE_DAYS' LIMIT 1`,
			});

			//pick the specified sms records from ES
			let arch_ts = parseInt(archiveSetting[0].var_value) * 24 * 60 * 60 * 1000;
			let lastdate = new Date(Date.now() - arch_ts).getTime();
			let es_records = await getSmsForArchiving(lastdate);
			//move them to mongo and get all ES ID
			if (es_records.rows.length == 0) {
				console.log("No old records to archive yet...");
			} else {
				let smsEsIds = await importDataInArchive(es_records.rows);
				//cross verify if these ES IDs are saved in Mongo before deleting
				let verificationResult = await verifyMongoSave(es_records.rows.length, smsEsIds);
				//delete from ES
				if (verificationResult === true) {
					await deleteArchivedRecords(smsEsIds);
					//delete from mysql
					let quotedList = smsEsIds.map(esid => `'${esid}'`).join(",");
					let condition = `es_index_id IN (${quotedList})`;
					await dbquery("deleteArchivedSms", { condition: condition });
					console.log(`Deleted archived SMS from MySQL`);
				}
			}

			console.log("A cycle of Archiving was completed...");
			console.log("Cleaning the TXT files...");
			let nowObj = new Date();
			let prev_ts = `${nowObj.getFullYear()}-${nowObj.getMonth() + 1}-${
				nowObj.getDate() - 1
			}`;
			//--
			let path = `/var/www/html/`;
			let regex = new RegExp(`^${prev_ts}.*[.]txt$`);
			fs.readdirSync(path)
				.filter(f => regex.test(f))
				.map(f => fs.unlinkSync(path + f));
		}, 900000); //every 24 hours
	}

	if (process.env.WorkerName == "archiveFetcher") {
		//initialize app to accept search results
		const app = express();
		app.use(cors());
		app.use(express.json({ limit: "150mb" }));
		app.use(compression());
		const port = process.env.PORT || 5306;
		app.use("/archive", getArchiveRouter);

		app.listen(port, () => console.log(`Archiver started and listening on port ${port}...`));
	}
}
