"use strict";
import { createRequire } from "module";
const require = createRequire(import.meta.url);
const smpp = require("smpp");
const fs = require("fs");
import { v1 as uuidv1 } from "uuid";
import { log, smppAuth, asyncStorePdu, setIntervalAsync, makeCampaignBlocks, storeDeliverResp, getSmppBindInfo } from "./helper.js";
import { multipartPduJoiner } from "./multipartJoiner.js";
import { DlrProcessor } from "./dlrProcessor.js";
import { getPlatformDataByIp } from "./miscHelper.js";
var dlrQueue = new DlrProcessor();
var cluster = require("cluster");

if (cluster.isMaster) {
	//fork
	let smppWorker = cluster.fork({ WorkerName: "smppWorker" });
	let pduProcessor = cluster.fork({ WorkerName: "pduProcessor" });
	let campaignWorker = cluster.fork({ WorkerName: "campaignWorker" });

	// Respawn if one of the process exits
	cluster.on("exit", function (coreWorker, code, signal) {
		let workerTitle = "";
		if (coreWorker == smppWorker) {
			workerTitle = "smppWorker";
			smppWorker = cluster.fork({ WorkerName: "smppWorker" });
		}
		if (coreWorker == pduProcessor) {
			workerTitle = "pduProcessor";
			pduProcessor = cluster.fork({ WorkerName: "pduProcessor" });
		}
		if (coreWorker == campaignWorker) {
			workerTitle = "campaignWorker";
			campaignWorker = cluster.fork({ WorkerName: "campaignWorker" });
		}
		console.log(`Worker - ${workerTitle} - Exited due to error. Respawned ...`);
	});

	//Run a lightweigth work on this core as well. This runs a process to update delivery confirmation responses PDU received by server
	setIntervalAsync(async () => {
		let totalPduDone = await dlrQueue.checkDeliveryResponses();
		log(`Updated ${totalPduDone} delivery_sm_resp PDUs`);
	}, 1000);
} else {
	// This worker only receives SMPP PDU and stores them in Redis database. The processing is done minimally to ensure high throughput. The only action to do when we receive a PDU is to store is in Redis. All other processing is to be done later. This worker also updates the SMPP Monitor to keep track of active connections
	if (process.env.WorkerName == "smppWorker") {
		let processSessions = new Array(); // this session store will be shared between SMPP submit and DLR tasks
		let server = smpp.createServer((session) => {
			//get session info
			//let ipar = session.socket.remoteAddress.split(":"); //old way, remove in future
			let client_ip = session.remoteAddress; // ipar[ipar.length - 1];
			let platform_data = getPlatformDataByIp(client_ip);
			//ipar = null;
			let processBind = {}; //this object stays within this core
			let session_id = session._id;

			session.on("bind_transmitter", async (bindPdu) => {
				//authorize the session
				log(`Tx PDU Received on ${process.env.WorkerName}:`, bindPdu);
				session.pause();

				let userSmppData = await smppAuth(bindPdu.system_id, bindPdu.password);
				//authenticate
				if (!userSmppData) {
					session.send(
						bindPdu.response({
							command_status: smpp.ESME_RBINDFAIL,
						})
					);
					session.close();
					log(`BIND FAILED: Invalid Credentials on ${process.env.WorkerName}`);
					return;
				}
				//check if ip is in allowed list
				if (userSmppData.allowed_ip != "*.*.*.*") {
					let allowedipar = userSmppData.allowed_ip.split(",");
					if (allowedipar.includes(client_ip) == false) {
						session.send(
							bindPdu.response({
								command_status: smpp.ESME_RBINDFAIL,
							})
						);
						session.close();
						log(`BIND FAILED: Invalid Client Attempt from ${client_ip} on ${process.env.WorkerName}`);
						return;
					}
				}
				//add this session in the collection
				let bindid = session_id; //Math.random().toString(36).substring(3);
				processBind = {
					id: bindid,
					smppclient: bindPdu.system_id,
					mode: "Tx",
					receive: false, //it means this one is fit to send dlr
					ip: client_ip,
					connected: Date.now(),
					pulse: Date.now(),
					user: userSmppData.user_id,
					route: userSmppData.route_id,
					plan: userSmppData.plan_id,
				};
				if (Array.isArray(processSessions[bindPdu.system_id]) == false) {
					processSessions[bindPdu.system_id] = new Array();
				}
				processSessions[bindPdu.system_id].push(processBind);

				//check if max tx exceeded
				if (processSessions[bindPdu.system_id].filter((obj) => obj.mode == "Tx").length > userSmppData.tx_max) {
					session.send(
						bindPdu.response({
							command_status: smpp.ESME_RBINDFAIL,
						})
					);
					let index = processSessions[bindPdu.system_id].indexOf(processBind);
					if (index > -1) {
						processSessions[bindPdu.system_id].splice(index, 1);
					}
					session.close();
					log(`BIND FAILED: Maximum allowed Tx binds exceeded on ${process.env.WorkerName}`);
					return;
				}
				//resume session if all checks are passed
				session.resume();
				session.send(bindPdu.response());
				log(`Tx BIND EXTABLISHED from ${client_ip}: Waiting for incoming PDU on ${process.env.WorkerName}..`);

				userSmppData = null;
			});

			session.on("bind_receiver", async (bindPdu) => {
				//authorize the session
				log(`Rx PDU Received on ${process.env.WorkerName}:`, bindPdu);
				session.pause();

				let userSmppData = await smppAuth(bindPdu.system_id, bindPdu.password);
				//authenticate
				if (!userSmppData) {
					session.send(
						bindPdu.response({
							command_status: smpp.ESME_RBINDFAIL,
						})
					);
					session.close();
					log(`BIND FAILED: Invalid Credentials on ${process.env.WorkerName}`);
					return;
				}
				//check if ip is in allowed list
				if (userSmppData.allowed_ip != "*.*.*.*") {
					let allowedipar = userSmppData.allowed_ip.split(",");
					if (allowedipar.includes(client_ip) == false) {
						session.send(
							bindPdu.response({
								command_status: smpp.ESME_RBINDFAIL,
							})
						);
						session.close();
						log(`BIND FAILED: Invalid Client Attempt from ${client_ip} on ${process.env.WorkerName}`);
						return;
					}
				}
				//add this session in the collection
				let bindid = session_id; // Math.random().toString(36).substring(3);
				processBind = {
					id: bindid,
					smppclient: bindPdu.system_id,
					mode: "Rx",
					receive: true, //it means this one is fit to send dlr
					ip: client_ip,
					connected: Date.now(),
					pulse: Date.now(),
					user: userSmppData.user_id,
					route: userSmppData.route_id,
					plan: userSmppData.plan_id,
				};
				if (Array.isArray(processSessions[bindPdu.system_id]) == false) {
					processSessions[bindPdu.system_id] = new Array();
				}
				processSessions[bindPdu.system_id].push(processBind);

				//check if max tx exceeded
				if (processSessions[bindPdu.system_id].filter((obj) => obj.mode == "Rx").length > userSmppData.rx_max) {
					session.send(
						bindPdu.response({
							command_status: smpp.ESME_RBINDFAIL,
						})
					);
					let index = processSessions[bindPdu.system_id].indexOf(processBind);
					if (index > -1) {
						processSessions[bindPdu.system_id].splice(index, 1);
					}
					session.close();
					log(`BIND FAILED: Maximum allowed Rx binds exceeded on ${process.env.WorkerName}`);
					return;
				}
				//resume session if all checks are passed
				session.resume();
				session.send(bindPdu.response());
				log(`Rx BIND EXTABLISHED from ${client_ip}: Waiting for incoming PDU on ${process.env.WorkerName}..`);

				userSmppData = null;
				//Listen to DLR event and send DLR to connected client on successful bind
				dlrQueue.on(`dlrFor-${bindPdu.system_id}-${bindid}`, (options) => {
					session.deliver_sm(options);
				});
			});

			session.on("bind_transceiver", async (bindPdu) => {
				//authorize the session
				log(`TRx PDU Received on ${process.env.WorkerName}:`, bindPdu);
				session.pause();

				let userSmppData = await smppAuth(bindPdu.system_id, bindPdu.password);
				//authenticate
				if (!userSmppData) {
					session.send(
						bindPdu.response({
							command_status: smpp.ESME_RBINDFAIL,
						})
					);
					session.close();
					log(`BIND FAILED: Invalid Credentials on ${process.env.WorkerName}`);
					return;
				}
				//check if ip is in allowed list
				if (userSmppData.allowed_ip != "*.*.*.*") {
					let allowedipar = userSmppData.allowed_ip.split(",");
					if (allowedipar.includes(client_ip) == false) {
						session.send(
							bindPdu.response({
								command_status: smpp.ESME_RBINDFAIL,
							})
						);
						session.close();
						log(`BIND FAILED: Invalid Client Attempt from ${client_ip} on ${process.env.WorkerName}`);
						return;
					}
				}
				//add this session in the collection
				let bindid = session_id; //Math.random().toString(36).substring(3);
				processBind = {
					id: bindid,
					smppclient: bindPdu.system_id,
					mode: "TRx",
					receive: true, //it means this one is fit to send dlr
					ip: client_ip,
					connected: Date.now(),
					pulse: Date.now(),
					user: userSmppData.user_id,
					route: userSmppData.route_id,
					plan: userSmppData.plan_id,
				};
				if (Array.isArray(processSessions[bindPdu.system_id]) == false) {
					processSessions[bindPdu.system_id] = new Array();
				}
				processSessions[bindPdu.system_id].push(processBind);

				//check if max trx exceeded
				if (processSessions[bindPdu.system_id].filter((obj) => obj.mode == "TRx").length > userSmppData.trx_max) {
					session.send(
						bindPdu.response({
							command_status: smpp.ESME_RBINDFAIL,
						})
					);
					let index = processSessions[bindPdu.system_id].indexOf(processBind);
					if (index > -1) {
						processSessions[bindPdu.system_id].splice(index, 1);
					}
					session.close();
					log(`BIND FAILED: Maximum allowed TRx binds exceeded on ${process.env.WorkerName}`);
					return;
				}
				//resume session if all checks are passed
				session.resume();
				session.send(bindPdu.response());
				log(`TRx BIND EXTABLISHED from ${client_ip}: Waiting for incoming PDU on ${process.env.WorkerName}..`);

				userSmppData = null;
				//Listen to DLR event and send DLR to connected client on successful bind
				dlrQueue.on(`dlrFor-${bindPdu.system_id}-${bindid}`, (options) => {
					session.deliver_sm(options);
					console.log(`DLR PDU dump`, options);
				});
			});

			session.on("enquire_link", (enquirePdu) => {
				session.send(enquirePdu.response());
				log(`Enquire link interval called on System ID: ${processBind.smppclient}..`);
				//update pulse
				setTimeout(() => {
					const index = processSessions[processBind.smppclient].indexOf(processBind);
					processSessions[processBind.smppclient][index].pulse = Date.now();
				}, 0);
			});

			session.on("submit_sm", (submitPdu) => {
				let sequence_number = submitPdu.sequence_number;
				log(`PDU DUMP STARTS on ${process.env.WorkerName}`, submitPdu);
				let msgid = uuidv1(); //vendor msg id
				//-- start async processessing of PDU
				let userobj = {
					user: processBind.user,
					route: processBind.route,
					plan: processBind.plan,
					sessionSystemId: processBind.smppclient,
					smsid: msgid,
					platform_data: platform_data,
				};
				asyncStorePdu(submitPdu, userobj).then((res) => {
					if (res.status == "error") {
						//send NACK as response
						session.send(
							submitPdu.response({
								command_status: res.reasonCode,
							})
						);
						log(res.reason);
					} else {
						//submission successful
						session.send(
							submitPdu.response({
								message_id: msgid,
								sequence_number: sequence_number,
							})
						);
						log(msgid);
					}
				});
			});

			session.on("unbind", (pdu) => {
				session.send(pdu.response());
				setTimeout(() => {
					if (processSessions[processBind.smppclient] != undefined) {
						let index = processSessions[processBind.smppclient].indexOf(processBind);
						if (index > -1) {
							processSessions[processBind.smppclient].splice(index, 1);
						}
					}
				}, 0);
				try{
				if (processBind.smppclient && processBind.id) dlrQueue.removeListener(`dlrFor-${processBind.smppclient}-${processBind.id}`);
				}catch(e){
					console.log(e);
					
				}
				log(`${processBind.smppclient} SESSION DISCONNECTED on ${process.env.WorkerName}`);
				session.destroy();
			});

			session.on("deliver_sm_resp", (delPdu) => {
				storeDeliverResp(processBind.smppclient, delPdu).then((res) => {
					log(`Delivery Acknowledgement Received for System ID: ${processBind.smppclient}`, delPdu);
				});
			});
		});

		server.listen(2775, "0.0.0.0");

		// DLR Processor Master Task:: This
		setIntervalAsync(async () => {
			let clientlist = Object.keys({ ...processSessions });
			if (clientlist.length == 0) return;
			//get pending DLR for each client
			let responses = await Promise.all(
				clientlist.map((smppclient) =>
					dlrQueue.performDlrTasks(
						smppclient,
						processSessions[smppclient].filter((bind) => (bind.receive = true))
					)
				)
			);
			let moResponses = await Promise.all(
				clientlist.map((smppclient) =>
					dlrQueue.performMoTasks(
						smppclient,
						processSessions[smppclient].filter((bind) => (bind.receive = true))
					)
				)
			);
			responses.forEach((result) => log(result));
			moResponses.forEach((result) => log(result));
		}, 5000);

		//Monitor Processor: Remove dead sessions and pass the data to the thread that updates SMPP monitor

		setIntervalAsync(async () => {
			let server_cons = server.sessions;
			console.log("Currently connected total sessions = " + server_cons.length);
			let clientlist = Object.keys({ ...processSessions });
			let output = [];
			if (clientlist.length > 0) {
				for (const systemid of clientlist) {
					if (processSessions[systemid].length > 0) {
						let info = {};
						let [userinfo, routeData] = await getSmppBindInfo(systemid);

						info.systemid = systemid;
						info.user = processSessions[systemid][0].user;
						info.username = userinfo.name;
						info.avatar = userinfo.avatar;
						info.usertype = userinfo.category;
						info.useremail = userinfo.email;
						if (routeData != undefined) {
							info.route = routeData.id;
							info.routetitle = routeData.title;
						} else {
							info.route = 0;
							info.routetitle = "";
						}

						info.totalbinds = processSessions[systemid].length;
						info.connections = [];
						processSessions[systemid].forEach((bind) => {
							//check if this bind is actively connected or not
							let con_status = server_cons.find((c) => c._id == bind.id);
							if (con_status != undefined) {
								//active bind
								let con = {};
								con.id = bind.id;
								con.type = bind.mode;
								con.clientip = bind.ip;
								con.time = bind.pulse;
								info.connections.push(con);
							} else {
								//inactive bind, remove it
								console.log(`Bind ID ${bind.id} disconnected from ${bind.ip} by ${systemid}. Removing..`);
								const index = processSessions[systemid].indexOf(bind);
								if (index > -1) {
									processSessions[systemid].splice(index, 1);
								}
							}
						});
						output.push(info);
					}
				}
			}
			fs.writeFile("/var/www/html/monitor.json", JSON.stringify(output), (err) => {
				// success case, the file was saved
				log("SMPP Monitor Updated");
			});
		}, 10000);
	}

	// This worker performs processing of stored PDUs. It joins the multipart into a single SMS object.
	if (process.env.WorkerName == "pduProcessor") {
		//PDU Joiner: run every second
		setIntervalAsync(async () => {
			let totalKeys = await multipartPduJoiner();
			if (totalKeys > 0) log(`Joined ${totalKeys} multipart PDUs.....`);
		}, 2500);
	}

	//This worker sorts the SMS objects, applyies DLR cutting, deducts credits and actually submits SMS to the operator
	if (process.env.WorkerName == "campaignWorker") {
		//Campaign Worker: run every 3 seconds to have enough SMS objects in Redis to perform operations in batches
		setIntervalAsync(async () => {
			console.log("Making Campaign Blocks...");
			await makeCampaignBlocks();
		}, 2000);
	}
}
