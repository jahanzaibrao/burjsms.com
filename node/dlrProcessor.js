"use strict";
import { env, require } from "./miscHelper.js";
const EventEmitter = require("events");
import {
  log,
  getQueuedDlr,
  getQueuedMo,
  updateDeliverSmResp,
} from "./helper.js";
import { asyncRpop } from "./nodeAsyncRedis.js";

class DlrProcessor extends EventEmitter {
  //This method scans the mysql database and checks if there are any pending DLRs
  performDlrTasks = async (smppclient, arrayOfBinds) => {
    let pduList = await getQueuedDlr(smppclient);
    pduList.forEach((options) => {
      let randomBind =
        arrayOfBinds[Math.floor(Math.random() * arrayOfBinds.length)];
      this.emit(`dlrFor-${smppclient}-${randomBind.id}`, options);
    });
    return `${pduList.length} DLR PDU sent to ${smppclient} ...`;
  };
  performMoTasks = async (smppclient, arrayOfBinds) => {
    let pduList = await getQueuedMo(smppclient);
    pduList.forEach((options) => {
      let randomBind =
        arrayOfBinds[Math.floor(Math.random() * arrayOfBinds.length)];
      this.emit(`dlrFor-${smppclient}-${randomBind.id}`, options);
    });
    return `${pduList.length} MO sent to ${smppclient} ...`;
  };
  //This method checks if there are any delivery responses (deliver_sm_resp) and processes them
  checkDeliveryResponses = async () => {
    //read from redis in batch
    const batchsize = 5000;
    let pduTaskSet = new Array();
    for (let i = 0; i < batchsize; ++i) {
      let respObj = await asyncRpop("deliver_sm_resp");
      if (respObj == null) break;
      respObj = JSON.parse(respObj);
      pduTaskSet.push(updateDeliverSmResp(respObj.smppclient, respObj.pdu));
    }
    //update all using Promose.all
    if (pduTaskSet.length == 0) return 0;
    await Promise.all(pduTaskSet);
    return pduTaskSet.length;
  };
}

const _DlrProcessor = DlrProcessor;
export { _DlrProcessor as DlrProcessor };
