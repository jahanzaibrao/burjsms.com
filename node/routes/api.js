"use strict";
import { env, require } from "../miscHelper.js";
const apiRouter = require("express").Router();
import { client, asyncScan } from "../nodeAsyncRedis.js";
import { v1 as uuidv1 } from "uuid";
import e, { json, response } from "express";
import { dbquery } from "../mariadbHelper.js";

//api end point
apiRouter.route("/").all(async (req, res) => {
  try {
    let ip = req.headers["x-real-ip"] || req.connection.remoteAddress;
    let api_res = await saveApiRequest(req.query);

    res.json(api_res);
  } catch (error) {
    console.log("API attempt failed");
    console.log(error);
    res.json({ error: "Malformed Request" });
  }
});

//helper functions

let saveApiRequest = async (body) => {
  //parse the body and get variables
  //get user info by key
  let userinfo = await dbquery("runPlainQuery", {
    query: `SELECT u.user_id, u.login_id, u.account_type FROM sc_users u, sc_api_keys k WHERE u.user_id = k.user_id AND k.api_key = '${body.key}'`,
  });
  userinfo = userinfo ? userinfo[0] : false;
  let dynamic_flag = body.bulk_campaign ? 1 : 0;
  let contacts_g =
    dynamic_flag == 1
      ? JSON.parse(body.bulk_campaign)
      : body.contacts.split(",");
  if (userinfo === false) {
    return {
      result: "error",
      message: "Invalid API Key",
    };
  }

  let sms_shoot_id =
    userinfo.login_id + "_" + (Math.random() + 1).toString(36).substring(4);
  //prepare the sms object
  let httpSmsPdus = [];
  let responseIds = [];
  let msg_type = body.msgtype;
  for (let i = 0, reqlen = contacts_g.length; i < reqlen; ++i) {
    //every message and every part (for long sms) should have a message ID returned in the response
    let message = dynamic_flag == 1 ? "" : decodeURIComponent(body.msg);
    let partsCount = 1;
    if (
      (body.msgtype = "unicode" && message.length > 70) ||
      message.length > 160
    ) {
      //multipart, generate id for all parts
      let parts =
        body.msgtype == "unicode"
          ? message.match(/(.{1,67})/g)
          : message.match(/(.{1,153})/g);
      partsCount = parts.length;
    }

    let mobile = dynamic_flag == 1 ? contacts_g[i].mobile : contacts_g[i];
    let msgid = uuidv1(); //msg id
    //start loop from 1 because message id of first part is message id of whole sms
    responseIds.push({ message_id: msgid, mobile: mobile });
    for (let x = 1; x < partsCount; ++x) {
      responseIds.push({ message_id: `${msgid}_${x + 1}`, mobile: mobile });
    }

    let smsobject = {
      message_id: msgid,
      api_key: body.key,
      campaign_id: body.campaign,
      route_id: body.routeid,
      sms_type: msg_type,
      mobile: mobile,
      sender: body.senderid,
      msg: message,
      dlr_url: body.dlr_url,
      sms_parts: partsCount,
      parts_ids: responseIds,
      sms_shoot_id: sms_shoot_id,
      user_id: userinfo.user_id,
      mode: "native",
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

  formData.append("contacts", contacts_g.join(","));
  formData.append("parts_ids", JSON.stringify(firstItem.parts_ids));
  let testurl = `${api_endpoint}/?key=${firstItem.api_key}&campaign=${
    firstItem.campaign_id
  }&routeid=${firstItem.route_id}&type=${firstItem.sms_type}&senderid=${
    firstItem.sender
  }&sms_shoot_id=${firstItem.sms_shoot_id}&contacts=${contacts_g.join(
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
        return {
          result: "success",
          message: "SMS SUBMITTED SUCCESSFULLY",
          total_contacts: contacts_g.length,
          sms_shoot_id: firstItem.sms_shoot_id,
          message_id: responseIds,
        };
      }
      //await client.zrem("api_requests_queue", JSON.stringify(campaign));
    }
  } catch (error) {
    console.log(`Fetch faced an issue, will try again....`);
  }

  //end of making sync request
  //httpSmsPdus.push(smsobject);

  //save in redis, commented because we send to the app here. No msg id should be given if submission is not successful
  // await client.ZADD(
  //   "api_requests_queue",
  //   Date.now(),
  //   JSON.stringify(httpSmsPdus)
  // );
  //return response
};

export default apiRouter;
