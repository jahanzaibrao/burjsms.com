"use strict";
import { require } from "./miscHelper.js";
import { promisify } from "util";
const redis = require("redis");
const oldclient = redis.createClient();
const asyncRedis = require("async-redis");
const client = asyncRedis.decorate(oldclient);
const asyncScan = promisify(client.SCAN).bind(client);
const asyncRange = promisify(client.ZRANGE).bind(client);
const asyncRpop = promisify(client.RPOP).bind(client);
const asyncHExists = promisify(client.HEXISTS).bind(client);

client.on("error", function (err) {
	console.log("Redis Error", err);
});

const _asyncScan = asyncScan;
export { _asyncScan as asyncScan };
const _asyncRange = asyncRange;
export { _asyncRange as asyncRange };
const _asyncRpop = asyncRpop;
export { _asyncRpop as asyncRpop };
const _asyncHExists = asyncHExists;
export { _asyncHExists as asyncHExists };
const _client = client;
export { _client as client };
