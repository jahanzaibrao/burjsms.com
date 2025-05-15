"use strict";
/**
 * 1. High performance API module
 */

import { env, require } from "./miscHelper.js";
const fs = require("fs");
var compression = require("compression");
const http = require("http");
const https = require("https");
import express from "express";
import cors from "cors";
import apiRouter from "./routes/api.js";
import ntApiRouter from "./routes/ntApi.js";
import nowApiRouter from "./routes/nowApi.js";
import { setIntervalAsync, processApiCampaigns } from "./helper.js";
var cluster = require("cluster");

if (cluster.isMaster) {
  //fork
  let apiMaster_processor = cluster.fork({
    WorkerName: "apiMaster_processor",
  });
  let apiMaster_main = cluster.fork({
    WorkerName: "apiMaster_main",
  });
  let apiMaster_helper = cluster.fork({
    WorkerName: "apiMaster_helper",
  });
  let api_worker = cluster.fork({
    WorkerName: "api_worker",
  });
  // Respawn if one of the process exits
  cluster.on("exit", async function (coreWorker, code, signal) {
    let workerTitle = "";
    if (coreWorker == apiMaster_processor) {
      workerTitle = "apiMaster_processor";
      apiMaster_processor = cluster.fork({
        WorkerName: "apiMaster_processor",
      });
    }
    if (coreWorker == apiMaster_main) {
      workerTitle = "apiMaster_main";
      apiMaster_main = cluster.fork({
        WorkerName: "apiMaster_main",
      });
    }
    if (coreWorker == apiMaster_helper) {
      workerTitle = "apiMaster_helper";
      apiMaster_helper = cluster.fork({
        WorkerName: "apiMaster_helper",
      });
    }
    if (coreWorker == api_worker) {
      workerTitle = "api_worker";
      api_worker = cluster.fork({
        WorkerName: "api_worker",
      });
    }
    console.log(`Worker - ${workerTitle} - Exited due to error. Respawned ...`);
  });
} else {
  if (process.env.WorkerName == "apiMaster_processor") {
    //process the API messages from redis queue
    setIntervalAsync(async () => {
      await processApiCampaigns();
    }, 3000);
  }
  if (
    process.env.WorkerName == "apiMaster_main" ||
    process.env.WorkerName == "apiMaster_helper"
  ) {
    //initialize app to accept search results
    process.env["NODE_TLS_REJECT_UNAUTHORIZED"] = 0;

    const app = express();
    app.use(cors());
    app.use(express.json({ limit: "150mb" }));
    app.use(compression());
    const port = 5311;
    app.use("/api", apiRouter);
    app.use("/ntApi", ntApiRouter);
    app.use("/nowApi", nowApiRouter);

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
      const ca = fs.readFileSync(
        `/etc/letsencrypt/live/${env.ADMIN_DOMAIN}/chain.pem`,
        "utf8"
      );

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
      app.listen(port, "0.0.0.0", () =>
        console.log(`Search interface started and listening on port ${port}...`)
      );
    }
  }
  if (process.env.WorkerName == "api_worker") {
    //Campaign Worker: run every 3 seconds to have enough SMS objects in Redis to perform operations in batches
    setIntervalAsync(async () => {
      await processApiCampaigns();
    }, 2000);
  }
}
