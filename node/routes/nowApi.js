"use strict";
import { env, require } from "../miscHelper.js";
const nowApiRouter = require("express").Router();
import { client, asyncScan } from "../nodeAsyncRedis.js";
import { v1 as uuidv1 } from "uuid";
import e, { json, response } from "express";
import { dbquery } from "../mariadbHelper.js";

//api end point
nowApiRouter.route("/").all(async (req, res) => {
  try {
    let ip = req.headers["x-real-ip"] || req.connection.remoteAddress;

    let api_res = await saveApiRequest(req.query, req._parsedUrl.query);
    res.set("Content-Type", "text/html");
    res.send(api_res);
    //res.json(api_res);
  } catch (error) {
    console.log("API attempt failed");
    console.log(error);
    res.json({ error: "Malformed Request" });
  }
});

//helper functions

let saveApiRequest = async (body, rawUrl) => {
  //parse the body and get variables
  //all this to get non url decoded msg
  let ar = rawUrl.split("&");
  let rawParams = ar.map((e) => {
    let i = e.split("=");
    return { param: i[0], value: i[1] };
  });
  let rawMsg = rawParams.find((item) => {
    return item.param == "Text" || item.param == "text";
  });
  //get user info by login id (use PHP service to authenticate because password is encrypted)
  let authFetch = await fetch(
    `https://${env.ADMIN_DOMAIN}/apiAuth/${encodeURIComponent(
      body.User || body.user
    )}/${encodeURIComponent(body.Password || body.password)}`,
    {
      method: "GET",
    }
  );

  let authFetchstatus = await authFetch.status;
  let authRes = await authFetch.text();
  let userinfo = JSON.parse(authRes);
  //userinfo = userinfo ? userinfo[0] : false;
  let dynamic_flag = 0;
  let suppliedNums = body.PhoneNumber || body.phonenumber;
  let contacts = suppliedNums ? suppliedNums.split(",") : [];
  if (userinfo.result == "error") {
    return {
      result: "error",
      message: "Invalid User",
    };
  }
  let sms_shoot_id =
    (body.User || body.user) +
    "_" +
    (Math.random() + 1).toString(36).substring(4);
  //prepare the sms object
  let httpSmsPdus = [];
  let responseIds = [];
  let msg_type = "text"; //only text is supported for now
  let rtinfo = await dbquery("runPlainQuery", {
    query: `SELECT def_route FROM sc_users_settings WHERE user_id = ${userinfo.user_id}`,
  });
  rtinfo = rtinfo ? rtinfo[0] : false;
  let defaultRoute = body.SMSCRoute || rtinfo.def_route;
  for (let i = 0, reqlen = contacts.length; i < reqlen; ++i) {
    //every message and every part (for long sms) should have a message ID returned in the response
    let message = body.Text || body.text;
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

    let mobile = dynamic_flag == 1 ? contacts[i].mobile : contacts[i];
    let msgid = uuidv1(); //msg id
    //start loop from 1 because message id of first part is message id of whole sms
    responseIds.push({ message_id: msgid, mobile: mobile });
    for (let x = 1; x < partsCount; ++x) {
      responseIds.push({ message_id: `${msgid}_${x + 1}`, mobile: mobile });
    }

    let smsobject = {
      message_id: msgid,
      api_key: userinfo.api_key,
      campaign_id: 0,
      route_id: defaultRoute,
      sms_type: "text",
      mobile: mobile,
      sender: body.Sender || body.sender,
      msg: message,
      dlr_url: body.dlr_url,
      sms_parts: partsCount,
      parts_ids: responseIds,
      sms_shoot_id: sms_shoot_id,
      user_id: userinfo.user_id,
      mode: "nowsms",
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
  formData.append("msg", encodeURIComponent(rawMsg.value));
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
        // //return response in text format
        let response = `<BODY> <p> Message Submitted <br><p><a href='javascript:history.back();'>Continue</a><br><p>`;
        responseIds.forEach((ele) => {
          response += `<pre><small>MessageID=${ele.message_id}, Recipient=${ele.mobile}</small></pre>`;
        });
        response += `</p></p></p></BODY>`;
        return response;
      }
      //await client.zrem("api_requests_queue", JSON.stringify(campaign));
    }
  } catch (error) {
    console.log(`Fetch faced an issue, will try again....`);
  }
  // //save in redis, skip now
  // await client.ZADD(
  //   "api_requests_queue",
  //   Date.now(),
  //   JSON.stringify(httpSmsPdus)
  // );
  // //return response in text format
  // let response = "Message Submitted \r\n\r\n";
  // responseIds.forEach((ele) => {
  //   response += `MessageID=${ele.message_id}, Recipient=${ele.mobile}\r\n`;
  // });

  // return response;
};

export default nowApiRouter;
