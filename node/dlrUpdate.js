"use strict";
/**
 * An alternative for DLR update
 */

import { env, require } from "./miscHelper.js";
import express from "express";
import cors from "cors";
const fs = require("fs");
const http = require("http");
const https = require("https");
import dlrRouter from "./routes/dlr.js";
var cluster = require("cluster");
if (cluster.isMaster) {
	//fork
	let dlrWorker_main = cluster.fork({
		WorkerName: "dlrWorker_main",
	});
	let dlrWorker_sibling_A = cluster.fork({
		WorkerName: "dlrWorker_sibling_A",
	});
	let dlrWorker_sibling_B = cluster.fork({
		WorkerName: "dlrWorker_sibling_B",
	});
	// Respawn if one of the process exits
	cluster.on("exit", function (coreWorker, code, signal) {
		let workerTitle = "";
		if (coreWorker == dlrWorker_main) {
			workerTitle = "dlrWorker_main";
			dlrWorker_main = cluster.fork({
				WorkerName: "dlrWorker_main",
			});
		}
		if (coreWorker == dlrWorker_sibling_A) {
			workerTitle = "dlrWorker_sibling_A";
			dlrWorker_sibling_A = cluster.fork({
				WorkerName: "dlrWorker_sibling_A",
			});
		}
		if (coreWorker == dlrWorker_sibling_B) {
			workerTitle = "dlrWorker_sibling_B";
			dlrWorker_sibling_B = cluster.fork({
				WorkerName: "dlrWorker_sibling_B",
			});
		}
		console.log(`Worker - ${workerTitle} - Exited due to error. Respawned ...`);
	});
} else {
	const app = express();
	app.use(cors());
	app.use(express.json({ limit: "150mb" }));
	const port = process.env.PORT || 5306;
	app.use("/dlrUpdate", dlrRouter);

	if (env.SETUP_MODE == "production") {
		// Certificate
		const privateKey = fs.readFileSync(
			`/etc/letsencrypt/live/${env.ADMIN_DOMAIN}/privkey.pem`,
			"utf8"
		);
		const certificate = fs.readFileSync(
			`/etc/letsencrypt/live/${env.ADMIN_DOMAIN}/cert.pem`,
			"utf8"
		);
		const ca = fs.readFileSync(`/etc/letsencrypt/live/${env.ADMIN_DOMAIN}/chain.pem`, "utf8");

		const credentials = {
			key: privateKey,
			cert: certificate,
			ca: ca,
		};
		const httpsServer = https.createServer(credentials, app);
		httpsServer.listen(port, () =>
			console.log(`Search interface started and listening on port ${port}...`)
		);
	} else {
		app.listen(port, () =>
			console.log(`Search interface started and listening on port ${port}...`)
		);
	}
}
