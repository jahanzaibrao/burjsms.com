"use strict";
import { env, require } from "../miscHelper.js";
const ntApiRouter = require("express").Router();
import { client, asyncScan } from "../nodeAsyncRedis.js";
import { v1 as uuidv1 } from "uuid";
import e, { json, response } from "express";
import { dbquery } from "../mariadbHelper.js";

//api end point
ntApiRouter.route("/").all(async (req, res) => {
  try {
    let ip = req.headers["x-real-ip"] || req.connection.remoteAddress;
    let api_res = await saveApiRequest(req.query);
    res.type("application/xml");
    res.send(api_res);
    //res.json(api_res);
  } catch (error) {
    console.log("API attempt failed");
    console.log(error);
    res.json({ error: "Malformed Request" });
  }
});

//helper functions

let saveApiRequest = async (body) => {
  let dlr_endpoint = "61.91.13.95";
  let dlr_url = body.dlr_url
    ? body.dlr_url
    : `http://${dlr_endpoint}/Connector/cpaConf/TOTIntReceiver.php?CMD=DLVRREP&NTYPE=REP&FROM=${body.FROM}&SMID=%s&STATUS=OK&DETAIL=%d`;
  //parse the body and get variables
  //get user info by key
  let userinfo = await dbquery("runPlainQuery", {
    query: `SELECT u.user_id, u.login_id, u.account_type, s.def_route FROM sc_users u, sc_sender_id k, sc_users_settings s WHERE u.user_id = k.req_by AND u.user_id = s.user_id AND k.sender_id = '${body.FROM}'`,
  });
  userinfo = userinfo ? userinfo[0] : false;
  let dynamic_flag = 0;
  let contacts = body.TO.split(",");
  if (userinfo === false) {
    return "<XML><STATUS>ERR</STATUS><DETAIL>User Not Found</DETAIL></XML>";
  }
  if (userinfo.def_route === false) {
    return "<XML><STATUS>ERR</STATUS><DETAIL>Default Route Not Set</DETAIL></XML>";
  }
  //user authenticated now get api key
  let apiKeyInfo = await dbquery("runPlainQuery", {
    query: `SELECT api_key FROM sc_api_keys WHERE user_id = ${userinfo.user_id}`,
  });
  apiKeyInfo = apiKeyInfo ? apiKeyInfo[0] : "";
  let sms_shoot_id = body.TRANSID
    ? userinfo.login_id + "_" + body.TRANSID
    : userinfo.login_id + "_" + (Math.random() + 1).toString(36).substring(4);
  //prepare the sms object
  let httpSmsPdus = [];
  let responseIds = [];
  let msg_type = body.CTYPE == "UNICODE" ? "unicode" : "text";
  for (let i = 0, reqlen = contacts.length; i < reqlen; ++i) {
    //every message and every part (for long sms) should have a message ID returned in the response
    let message = decodeURIComponent(body.CONTENT);
    let partsCount = 1;
    if (
      (body.CTYPE == "UNICODE" && message.length > 70) ||
      message.length > 160
    ) {
      //multipart, generate id for all parts
      let parts =
        body.CTYPE == "UNICODE"
          ? message.match(/(.{1,67})/g)
          : message.match(/(.{1,153})/g);
      partsCount = parts.length;
    }

    let mobile = contacts[i];
    let msgid = uuidv1(); //msg id
    //start loop from 1 because message id of first part is message id of whole sms
    responseIds.push({ message_id: msgid, mobile: mobile });
    for (let x = 1; x < partsCount; ++x) {
      responseIds.push({ message_id: `${msgid}_${x + 1}`, mobile: mobile });
    }

    let smsobject = {
      message_id: msgid,
      api_key: apiKeyInfo.api_key,
      campaign_id: 0,
      route_id: userinfo.def_route,
      sms_type: msg_type,
      mobile: mobile,
      sender: body.FROM,
      msg: message,
      dlr_url: body.dlr_url || "",
      sms_parts: partsCount,
      parts_ids: responseIds,
      sms_shoot_id: sms_shoot_id,
      user_id: userinfo.user_id,
      mode: "ntapi",
    };
    httpSmsPdus.push(smsobject);
  }
  //now this request can have many contacts, all contact and their info is in httoSMSPDU array

  //start making request
  let firstItem = httpSmsPdus[0];
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
  formData.append("contacts", contacts.join(","));
  formData.append("parts_ids", JSON.stringify(firstItem.parts_ids));
  let testurl = `${api_endpoint}/?key=${firstItem.api_key}&campaign=${
    firstItem.campaign_id
  }&routeid=${firstItem.route_id}&type=${firstItem.sms_type}&senderid=${
    firstItem.sender
  }&sms_shoot_id=${firstItem.sms_shoot_id}&contacts=${contacts.join(
    ","
  )}&parts_ids=${JSON.stringify(responseIds)}&msg=${firstItem.msg}`;
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
      //success, now show positive response if submission result was also success
      let apiRes = JSON.parse(jRes);
      if (apiRes.result == "error") {
        return apiRes;
      } else {
        console.log(`Returned ${status} for API hit, with response as:`);
        console.log(jRes);
        console.log(`Campaign processed: ${firstItem.sms_shoot_id}`);
        let response = "<XML><STATUS>OK</STATUS><DETAIL></DETAIL>";
        responseIds.forEach((ele) => {
          response += `<SMID>${ele.message_id}</SMID>`;
        });
        response += "</XML>";

        return response;
      }
      //await client.zrem("api_requests_queue", JSON.stringify(campaign));
    }
  } catch (error) {
    console.log(`Fetch faced an issue, will try again....`);
  }
  //save in redis, skip to make request sync so no msg id is returned if submission fails
  // await client.ZADD(
  //   "api_requests_queue",
  //   Date.now(),
  //   JSON.stringify(httpSmsPdus)
  // );
  // //return response
  // let response = "<XML><STATUS>OK</STATUS><DETAIL></DETAIL>";
  // responseIds.forEach((ele) => {
  //   response += `<SMID>${ele.message_id}</SMID>`;
  // });
  // response += "</XML>";

  // return response;
};

export default ntApiRouter;
