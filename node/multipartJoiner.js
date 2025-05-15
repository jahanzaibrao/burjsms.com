"use strict";
import { client, asyncScan, asyncRange } from "./nodeAsyncRedis.js";

const joinPdu = async (key) => {
  let keyParts = key.split(":");
  let smppClient = keyParts[1];
  let msisdn = keyParts[2];
  let parts = await asyncRange(key, 0, -1);
  //sms id for this sms will be the msgid of first part
  let finalMsgText = "";
  let firstPdu = JSON.parse(parts[0]);
  let finalMsgId = firstPdu.smsid;
  let totalParts = parts.length;
  for (let i = 0; i < totalParts; ++i) {
    let obj = JSON.parse(parts[i]); //as it is escaped string
    finalMsgText += obj.pdu.short_message.message;
  }
  let storeObj = {
    smsid: finalMsgId,
    userid: firstPdu.userid,
    planid: firstPdu.planid,
    routeid: firstPdu.routeid,
    submission_time: firstPdu.submission_time,
    sms_text: finalMsgText,
    sender: firstPdu.sender,
    msisdn: msisdn,
    sms_count: totalParts,
    dlr_request: firstPdu.dlr_request,
    ip: firstPdu.ip,
    pdu: parts,
  };
  client.ZADD(smppClient, Date.now(), JSON.stringify(storeObj));

  //delete this key
  await client.DEL(key);
  return;
};

let multipartPduJoiner = async () => {
  let current_time = Math.floor(Date.now() / 1000);
  let time_limit = (current_time - 1).toString();
  let last_digit = time_limit.slice(-1);
  let remaining_digits = time_limit.slice(0, -1);
  let pattern = `MULTIPART${remaining_digits}[0-${last_digit}]*`;

  let multipartKeys = await asyncScan(0, "MATCH", pattern, "COUNT", "50000");
  multipartKeys = multipartKeys[1];
  let totalKeys = multipartKeys.length;
  let pduJobs = new Array();
  for (let i = 0; i < totalKeys; ++i) {
    pduJobs.push(joinPdu(multipartKeys[i]));
  }
  let joinRes = await Promise.all(pduJobs);
  return totalKeys;
};

const _multipartPduJoiner = multipartPduJoiner;
export { _multipartPduJoiner as multipartPduJoiner };
