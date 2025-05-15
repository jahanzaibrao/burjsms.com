"use strict";
import { env, require } from "./miscHelper.js";
import { dbquery } from "./mariadbHelper.js";
import { client, asyncScan } from "./nodeAsyncRedis.js";
import { processClientSmsBatch, preCheck } from "./smsProcessor.js";
const batchdelay = env.BATCH_DELAY;
var speedDate = require("speed-date");
import stringify from "fast-stringify";
/**
 * Authenticate clients
 */
let smppAuth = async (username, password) => {
  return await dbquery("smppAuth", {
    systemid: username,
    password: password,
  });
};
let getSmppBindInfo = async (smppclient) => {
  let clientData = await dbquery("getSmppclientInfo", {
    smppclient: smppclient,
  });
  return await Promise.all([
    dbquery("getUserInfo", { user: clientData.user_id }),
    dbquery("getRouteTitle", { route: clientData.route_id }),
  ]);
};

/**
 * Functions to store and process incoming PDUs
 */
let asyncStorePdu = (pdu, userObj) =>
  Promise.resolve().then(async (v) => {
    //reject submission and send NACK if
    //1. invalid or wrong sender
    //2. template mismatch
    //3. invalid hours
    //4. sms type not allowed
    //5. spam found
    //6. not enough credits
    //let preCheckResult = await preCheck(pdu, userObj);
    //if (preCheckResult.status == "error") {
    //  return preCheckResult;
    //}
//check if multipart
    let multiflag = 0;
    let udhext = pdu.short_message.udh ? JSON.parse(JSON.stringify(pdu.short_message.udh)) : false;
    if(udhext && udhext[0].data == Array && udhext[0].data.length == 5) {
      multiflag = 1;
    }
    //old checks for multipart
    //pdu.esm_class == 0 ||
      //pdu.esm_class == 3 ||
      //pdu.esm_class == 64 ||
      //pdu.data_coding == 4 ||
      //pdu.data_coding == 246 ||
      //pdu.data_coding == 245
    //end of old check
    //all good store it
    if (
      multiflag == 0
    )	
     {
      let shortmsg =
        pdu.data_coding == 245 || pdu.data_coding == 4
          ? stringify(pdu.short_message.message)
          : pdu.short_message.message;
      let storeObj = {
        smsid: userObj.smsid,
        userid: userObj.user,
        planid: userObj.plan,
        routeid: userObj.route,
        submission_time: Date.now(),
        sms_text: shortmsg,
        sender: pdu.source_addr,
        msisdn: pdu.destination_addr,
        sms_count: 1,
        dlr_request: pdu.registered_delivery,
        pdu: pdu,
        platform_data: userObj.platform_data,
      };
      client.ZADD(
        userObj.sessionSystemId,
        Date.now(),
        JSON.stringify(storeObj)
      );
    } else {
      //multipart sms, store a bit differently
      let udhobj = JSON.parse(JSON.stringify(pdu.short_message.udh))[0].data;
      let idf = udhobj[2];
      let part_no = parseInt(udhobj[4]);
      let key = `MULTIPART${Math.floor(Date.now() / 1000)}:${
        userObj.sessionSystemId
      }:${pdu.destination_addr}:${idf}`;
      let storeObj = {
        smsid: userObj.smsid,
        userid: userObj.user,
        planid: userObj.plan,
        routeid: userObj.route,
        submission_time: Date.now(),
        sms_text: "",
        sender: pdu.source_addr,
        msisdn: pdu.destination_addr,
        sms_count: 0,
        dlr_request: pdu.registered_delivery,
        pdu: pdu,
        platform_data: userObj.platform_data,
      };
      client.ZADD(key, part_no, JSON.stringify(storeObj));
    }
    return {
      status: "success",
    };
  });

let makeCampaignBlocks = async () => {
  let allKeys = await asyncScan(0, "COUNT", "50000");
  allKeys = allKeys[1]; //ignore the cursor
  let processJobs = new Array();
  for (let i = 0, len = allKeys.length; i < len; ++i) {
    let system_id = allKeys[i];
    if (
      system_id.includes("MULTIPART") == false &&
      system_id.includes("deliver_sm") == false &&
      system_id.includes("rand_int") == false &&
      system_id.includes("mylist") == false &&
      system_id.includes("api_requests_queue") == false &&
      system_id.includes("search_jwt_tokens") == false
    ) {
      //exclude multipart sms assembly store
      log(`Making Campaign Blocks for ${system_id}...`);
      //each system id has different client so process asynchronously
      let minobj = await client.zrangebyscore(
        system_id,
        "-inf",
        "+inf",
        "withscores",
        "limit",
        0,
        1
      );
      let mintime = minobj.pop();
      let current_time = Date.now();
      if (
        Math.floor(current_time / 1000) - Math.floor(mintime / 1000) >
        batchdelay
      ) {
        let current_batch = await client.zrangebyscore(
          system_id,
          mintime,
          current_time
        );
        processJobs.push(
          processClientSmsBatch(current_batch, {
            key: system_id,
            min: mintime,
            max: current_time,
          })
        );
      }
    }
  }
  //now an improvement can be made. Executing tasks in parallel creates problem with deduction of credits if one client has more than one smpp accounts with same route or same plan, so instead of system_id create parallel jobs based on user_id
  let response = await Promise.all(processJobs);
  response.forEach((msg) => log(msg));
  return;
};

let processApiCampaigns = async () => {
  //pick from redis queue
  let apiRequests = await client.zrangebyscore(
    "api_requests_queue",
    "-inf",
    "+inf",
    "withscores",
    "limit",
    0,
    1
  );
  let mintime = apiRequests.pop();
  let current_time = Date.now();
  if (
    Math.floor(current_time / 1000) - Math.floor(mintime / 1000) >
    batchdelay
  ) {
    let current_batch = await client.zrangebyscore(
      "api_requests_queue",
      mintime,
      current_time
    );
    current_batch.forEach(async (campaign) => {
      //in each campaign there will be multiple sms objects, one for each mobile
      campaign = JSON.parse(campaign);
      let firstItem = campaign[0];
      //start preparing API call to legacy endpoint
      let api_endpoint = `https://${env.ADMIN_DOMAIN}/smsapi/index`;
      let formData = new FormData();
      formData.append("key", firstItem.api_key);
      formData.append("campaign", firstItem.campaign_id);
      formData.append("routeid", firstItem.route_id);
      formData.append("type", firstItem.sms_type);
      formData.append("senderid", firstItem.sender);
      formData.append("msg", firstItem.msg);
      formData.append("dlr_url", firstItem.dlr_url ? firstItem.dlr_url : "");
      formData.append("sms_shoot_id", firstItem.sms_shoot_id);
      formData.append("mode", firstItem.mode);

      let setMsgIds = {};
      let contacts = [];
      campaign.forEach((msgPDU) => {
        setMsgIds[msgPDU.mobile] = msgPDU.message_id;
        contacts.push(msgPDU.mobile);
      });
      formData.append("contacts", contacts.join(","));
      formData.append("parts_ids", JSON.stringify(setMsgIds));
      let testurl = `${api_endpoint}/?key=${firstItem.api_key}&campaign=${
        firstItem.campaign_id
      }&routeid=${firstItem.route_id}&type=${firstItem.sms_type}&senderid=${
        firstItem.sender
      }&msg=${firstItem.msg}&sms_shoot_id=${
        firstItem.sms_shoot_id
      }&contacts=${contacts.join(",")}&parts_ids=${JSON.stringify(setMsgIds)}`;
      console.log(testurl);
      //postObj.parts_ids = setMsgIds;
      //hit the API endpoimt
      try {
        let fetchRes = await fetch(api_endpoint, {
          method: "POST",
          body: formData,
        });
        let status = await fetchRes.status;
        let jRes = await fetchRes.text();
        await new Promise((r) => setTimeout(r, 2000));
        if (status >= 200 && status < 300) {
          //success, now delete the campaign from redis
          await client.zrem("api_requests_queue", JSON.stringify(campaign));
          console.log(
            `Campaign processed and deleted: ${firstItem.sms_shoot_id}`
          );
        }
        console.log(`Returned ${status} for API hit, with response as:`);
        console.log(jRes);
      } catch (error) {
        console.log(`Fetch faced an issue, will try again....`);
      }
    });
  }
};

/**
 * DLR store, process and forward functions
 */
let getQueuedDlr = async (smppclient) => {
  let now = new Date();
  let codes = [
    {
      dlr: 1,
      err: "000",
      exp: "DELIVRD",
      mstate: 2,
    },
    {
      dlr: 2,
      err: "002",
      exp: "EXPIRED",
      mstate: 3,
    },
    {
      dlr: 2,
      err: "002",
      exp: "DELETED",
      mstate: 4,
    },
    {
      dlr: 4,
      err: "005",
      exp: "UNDELIV",
      mstate: 5,
    },
    {
      dlr: 4,
      err: "001",
      exp: "ACCEPTED",
      mstate: 6,
    },
    {
      dlr: 2,
      err: "005",
      exp: "UNKNOWN",
      mstate: 7,
    },
    {
      dlr: 16,
      err: "555",
      exp: "REJECTD",
      mstate: 8,
    },
    {
      dlr: 34,
      err: "555",
      exp: "EXPIRED",
      mstate: 3,
    },
  ];
  let dlrdata = await dbquery("getSmppDlr", { user: smppclient });
  let response = [];
  if (dlrdata == undefined) return response;

  let ids = [],
    pduValues = [];
  for (let i = 0, len = dlrdata.length; i < len; ++i) {
    let dlr = dlrdata[i];
    ids.push(dlr.id);
    let dlr_smpp_resp =
      dlr.smpp_resp_code == "" ? "UNKNOWN" : dlr.smpp_resp_code;
    let dlrcode = codes.find((item) => {
      return item.exp == dlr_smpp_resp;
    });
    if (dlrcode == undefined) {
      dlrcode = codes.find((item) => {
        return item.dlr == dlr.dlr;
      });
    }
    let finalstate = dlr.dlr == "1" ? 1 : 0;
    let pdu = {
      command_id: 4,
      command_status: "",
      sequence_number: dlr.pdu_seq,
      service_type: "",
      source_addr_ton: 5,
      source_addr_npi: 0,
      source_addr: `${dlr.sender}`,
      dest_addr_ton: 0,
      dest_addr_npi: 1,
      destination_addr: `${dlr.msisdn}`,
      esm_class: 4,
      protocol_id: 1,
      receipted_message_id: `${dlr.sms_id}`,
      message_state: dlrcode.mstate,
      priority_flag: 1,
      schedule_delivery_time: "",
      validity_period: "",
      registered_delivery: 1,
      replace_if_present_flag: "",
      sm_default_msg_id: "",
      short_message: {
        message: `id:${
          dlr.sms_id
        } sub:1 dlvrd:${finalstate} submit date:${speedDate.cached(
          "YYMMDDHHmm",
          new Date()
        )} done date:${speedDate.cached(
          "YYMMDDHHmm",
          new Date()
        )} stat:${
          dlr.smpp_resp_code == "" ? "UNKNOWN" : dlr.smpp_resp_code
        } err:${
          dlrcode != undefined ? dlrcode.err : dlr.vendor_dlr
        } text:'SMPP_DLR_CODE:${dlr.vendor_dlr}'`,
      },
    };
    response.push(pdu);
    let pduArray = [
      smppclient,
      dlr.sms_id,
      dlr.pdu_seq,
      Buffer.from(JSON.stringify(pdu)).toString("base64"),
    ];
    pduValues.push(pduArray);
  }
  if (ids.length > 0) {
    await Promise.all([
      dbquery("deleteSmppDlr", { idlist: ids.join(",") }),
      dbquery("dlrPduEntry", { batchValues: pduValues }),
    ]);
  }
  return response;
};

let getQueuedMo = async (smppclient) => {
  let now = new Date();
  var map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  }; //for htmlspecialchars_decode in js

  let dlrdata = await dbquery("getSmppMo", { user: smppclient });
  let response = [];
  if (dlrdata == undefined) return response;

  let ids = [],
    pduValues = [];
  for (let i = 0, len = dlrdata.length; i < len; ++i) {
    let dlr = dlrdata[i];
    ids.push(dlr.id);

    let pdu = {
      command_id: 5,
      sequence_number: dlr.sms_id,
      service_type: "",
      source_addr_ton: 1,
      source_addr_npi: 1,
      source_addr: `${dlr.source_addr}`,
      dest_addr_ton: 3,
      dest_addr_npi: 3,
      destination_addr: `${dlr.destination_addr}`,
      esm_class: 0,
      registered_delivery: 1,
      replace_if_present_flag: "",
      sm_default_msg_id: "",
      data_coding: dlr.data_coding,
      short_message: {
        message: `${dlr.message.replace(/[&<>"']/g, function (m) {
          return map[m];
        })}`,
      },
    };
    response.push(pdu);
    // let pduArray = [
    //   smppclient,
    //   dlr.sms_id,
    //   dlr.pdu_seq,
    //   Buffer.from(JSON.stringify(pdu)).toString("base64"),
    // ];
    // pduValues.push(pduArray);
  }
  //later update the attempts
  // if (ids.length > 0) {
  //   await Promise.all([
  //     dbquery("deleteSmppDlr", { idlist: ids.join(",") }),
  //     dbquery("dlrPduEntry", { batchValues: pduValues }),
  //   ]);
  // }
  return response;
};

let storeDeliverResp = (smppclient, pdu) =>
  Promise.resolve().then(async (v) => {
    let storeObj = {
      smppclient: smppclient,
      pdu: pdu,
    };
    client.LPUSH("deliver_sm_resp", JSON.stringify(storeObj));
    return;
  });

let updateDeliverSmResp = async (smppclient, pdudata) =>
  Promise.resolve().then(async (v) => {
    if (pdudata.sequence_number != "") {
	    //console.log(pdudata);
	    //console.log(Buffer.from(JSON.stringify(pdudata)).toString("base64"));
      await dbquery("updateDlrResponse", {
        smppclient: smppclient,
        pdu_seq: pdudata.sequence_number,
        response: Buffer.from(JSON.stringify(pdudata)).toString("base64"),
      });
	    //since we're not deleteing dlr, mark it as done
	await dbquery("markDlrDone", {
        smppclient: smppclient,
        pdu_seq: pdudata.sequence_number,
      });
      //jugad for updating mo response
      await dbquery("updateSmppMoResponse", {
        smppclient: smppclient,
        pdu_seq: pdudata.sequence_number,
        response: Buffer.from(JSON.stringify(pdudata)).toString("base64"),
      });
    }
  });

/**
 * Generic helper functions
 */
function log(str, dumpvar = null) {
  console.log(`${speedDate.cached("YYYY-MM-DD HH:mm:ss", new Date())} ${str}`);
  if (dumpvar) console.dir(dumpvar, { depth: null });
}
const setIntervalAsync = (fn, ms) => {
  fn().then(() => {
    setTimeout(() => setIntervalAsync(fn, ms), ms);
  });
};

const _smppAuth = smppAuth;
export { _smppAuth as smppAuth };
const _setIntervalAsync = setIntervalAsync;
export { _setIntervalAsync as setIntervalAsync };
const _asyncStorePdu = asyncStorePdu;
export { _asyncStorePdu as asyncStorePdu };
const _makeCampaignBlocks = makeCampaignBlocks;
export { _makeCampaignBlocks as makeCampaignBlocks };
const _processApiCampaigns = processApiCampaigns;
export { _processApiCampaigns as processApiCampaigns };
const _getQueuedDlr = getQueuedDlr;
export { _getQueuedDlr as getQueuedDlr };
const _getQueuedMo = getQueuedMo;
export { _getQueuedMo as getQueuedMo };
const _updateDeliverSmResp = updateDeliverSmResp;
export { _updateDeliverSmResp as updateDeliverSmResp };
const _storeDeliverResp = storeDeliverResp;
export { _storeDeliverResp as storeDeliverResp };
const _getSmppBindInfo = getSmppBindInfo;
export { _getSmppBindInfo as getSmppBindInfo };
const _log = log;
export { _log as log };
