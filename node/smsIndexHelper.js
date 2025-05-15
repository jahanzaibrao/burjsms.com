"use strict";
import {
  env,
  getCoverageByMsisdn,
  unserialize,
  doRequests,
} from "./miscHelper.js";
import { dbquery } from "./mariadbHelper.js";
import { getMysqlIdsByElasticIds } from "./elasticHelper.js";

const parsePanelAndApiSubmissions = async (mode, mysqlData) => {
  let condition =
    mode == "getPendingSearchIndexNumbers"
      ? `es_index_id = ''`
      : `es_index_id <> '' AND es_index_status=1`;
  if (mode == "getPendingSearchIndexNumbers") {
    //index fresh sms data
    //get summary and sent sms which needs to be indexed belonging to above shoot ids
    let shoot_id_list = await dbquery(mode);
    if (shoot_id_list.length == 0) return {};
    let shoot_ids = shoot_id_list.map((e) => `'${e.sms_shoot_id}'`).join(",");
    let [sent_sms, summaryData] = await Promise.all([
      dbquery("getIndexSmsByShootId", {
        shoot_id_list: shoot_ids,
        condition: condition,
      }),
      getSmsShootIdData(shoot_ids),
    ]);
    let sms_objects = await Promise.all(
      sent_sms.map((sms) => getPanelAndApiSmsData(sms, summaryData, mysqlData))
    );
    return sms_objects;
  } else {
    //get updated sms data to update elastic index
    let sms_data = await dbquery("getUpdatedIndexSms", {
      condition: condition,
    });
    if (sms_data.length == 0) return [];
    let sms_objects = await Promise.all(
      sms_data.map((sms) => getUpdatedAppSmsData(sms, mysqlData))
    );
    return sms_objects.flat(); //this is complete body which can be sent to elasticsearch api
  }
};
const parseSmppSubmissions = async (mode, mysqlData) => {
  let condition =
    mode == "getPendingSearchIndexNumbersSmpp"
      ? `es_index_id = ''`
      : `es_index_id <> '' AND es_index_status=1`;
  if (mode == "getPendingSearchIndexNumbersSmpp") {
    //index fresh sms data
    let smpp_sms_list = await dbquery(mode, { condition: condition });
    if (smpp_sms_list.length == 0) return {};
    let sms_objects = await Promise.all(
      smpp_sms_list.map((sms) => getSmppSmsData(sms, mysqlData))
    );
    return sms_objects;
  } else {
    //get updated sms data to update elastic index
    let sms_data = await dbquery("getUpdatedIndexSmsSmpp", {
      condition: condition,
    });
    if (sms_data.length == 0) return [];
    let sms_objects = await Promise.all(
      sms_data.map((sms) => getUpdatedSmppSmsData(sms, mysqlData))
    );
    return sms_objects.flat(); //this is complete body which can be sent to elasticsearch api
  }
};

/**
 * Tasks related to processing sms from sc_sent_sms (sent via Panel/API)
 */

const getPanelAndApiSmsData = async (smsitem, summaryData, mysqlData) => {
  let summary = summaryData[smsitem.sms_shoot_id];
  if (summary == undefined) console.log(smsitem.sms_shoot_id);
  let send_time_human = new Date(smsitem.sending_time);
  let send_time_epoch = send_time_human.getTime();
  let dlr_time_human = new Date(smsitem.dlr_updated_on);
  let dlr_time_epoch = dlr_time_human.getTime();
  let click_time_epoch;
  if (smsitem.url_visit_ts == null || smsitem.url_visit_ts == undefined) {
    click_time_epoch = null;
  } else {
    let click_time_human = new Date(smsitem.url_visit_ts);
    click_time_epoch = click_time_human.getTime();
  }

  let tlv = {};
  if (summary.tlv_data != "") {
    let campaign_tlv = JSON.parse(summary.tlv_data);
    campaign_tlv.forEach((tlvstring) => {
      let tlvparts = tlvstring.split("||");
      tlv[tlvparts[0]] = tlvparts[1];
    });
  }
  let platform_data = {
      ip: summary.platform_data.ip || null,
      system: summary.platform_data.system || "",
      browser: summary.platform_data.browser || "",
      city: summary.platform_data.city || "",
      country: summary.platform_data.country || "",
      location: {
        lat: summary.platform_data.lat || 0,
        lon: summary.platform_data.lon || 0,
      },
    },
    click_platform = {
      ip: null,
      system: "",
      browser: "",
      city: "",
      country: "",
      location: {
        lat: 0,
        lon: 0,
      },
    };
  let smstext = summary.sms_type.personalize
    ? smsitem.sms_text
    : summary.sms_text;
  if (
    smsitem.url_visit_platform != "" &&
    smsitem.url_visit_platform != undefined
  ) {
    let click_platform_data = await unserialize({
      type: "single",
      body: smsitem.url_visit_platform,
    });
    let click_platform_parsed = click_platform_data;
    click_platform.ip = click_platform_parsed.ip || null;
    click_platform.system = click_platform_parsed.system || "";
    click_platform.browser = click_platform_parsed.browser || "";
    click_platform.city = click_platform_parsed.city || "";
    click_platform.country = click_platform_parsed.country || "";
    click_platform.location.lat = click_platform_parsed.lat || 0;
    click_platform.location.lon = click_platform_parsed.lon || 0;
  }

  //get real smsc details
  let smsc = smsitem.smsc;
  let smppobject = mysqlData.allSmsc[smsc];
  let smscdata = {
    host: smppobject === undefined ? "" : smppobject.host,
    port: smppobject === undefined ? 0 : smppobject.port,
    system_id: smppobject === undefined ? "" : smppobject.system_id,
  };

  //get user data
  let userdata = mysqlData.allUsers[smsitem.user_id];
  //get sender id
  let senderid = mysqlData.allSender[smsitem.sender_id];
  //get refund status
  let refundflag =
    mysqlData.refundLogs[`${smsitem.sms_shoot_id}-${smsitem.mobile}`];
  //get country and operator
  let countryAndMccmnc = getMccmncAndCoverageDetails(
    smsitem.mobile,
    smsitem.mccmnc,
    {
      allMccmnc: mysqlData.allMccmnc,
      allCoverage: mysqlData.allCoverage,
      mccmncMap: mysqlData.mccmncMap,
    }
  );
  //get sms type alias
  let smstypestr = `TEXT`;
  if (summary.sms_type.main == "text") {
    if (summary.sms_type.unicode == true) {
      smstypestr = `UNICODE`;
    }
    if (summary.sms_type.flash == true) {
      smstypestr = `FLASH`;
    }
    if (summary.sms_type.unicode == true && summary.sms_type.flash == true) {
      smstypestr = `UNICODE-FLASH`;
    }
  } else if (summary.sms_type.main == "wap") {
    smstypestr = "WAP";
  } else if (summary.sms_type.main == "vcard") {
    smstypestr = "VCARD";
  }

  let preparedSmsObject = {
    mysql_id: smsitem.id.toString(),
    campaign_id: summary.campaign_id,
    sms_shoot_id: smsitem.sms_shoot_id,
    operator_sms_id: smsitem.vendor_msgid,
    user_id: smsitem.user_id,
    user_alias: userdata.login_id,
    upline_user_id: userdata.upline_id,
    route_id: smsitem.route_id,
    routing_scheme: "dedicated",
    smsc: smsc,
    smsc_data: smscdata,
    submit_time: send_time_epoch,
    channel: summary.pushed_via == "APP" ? "APP" : "API",
    sender_id: senderid,
    msisdn: smsitem.mobile,
    country: {
      iso: countryAndMccmnc.country_code,
      prefix: countryAndMccmnc.country_prefix,
    },
    operator: {
      mccmnc: countryAndMccmnc.mccmnc,
      mcc: countryAndMccmnc.mcc,
      mnc: countryAndMccmnc.mnc,
      title: countryAndMccmnc.network,
      region: countryAndMccmnc.region,
    },
    sms_type: {
      main: summary.sms_type.main,
      unicode: summary.sms_type.unicode ? true : false,
      flash: summary.sms_type.flash ? true : false,
      personalized: summary.sms_type.personalize ? true : false,
      multipart: summary.sms_type.multipart ? true : false,
    },
    sms_type_alias: smstypestr,
    sms_parts: smsitem.sms_count,
    sms_text: smstext,
    price: smsitem.price,
    cost: smsitem.cost,
    currency: env.MAIN_CURRENCY,
    dlr: {
      type: smsitem.status == 2 ? "fakedlr" : "normal",
      kannel_code: smsitem.dlr,
      smpp_response: smsitem.smpp_resp_code,
      operator_code: smsitem.vendor_dlr,
      app_status: getAppDlrStatus(smsitem.dlr),
      time: isNaN(dlr_time_epoch) ? null : dlr_time_epoch,
    },
    refunded: refundflag == undefined ? false : true,
    ndnc:
      mysqlData.allNdncCodes.find((e) => e == smsitem.vendor_dlr) == undefined
        ? false
        : true,
    attempts: 1,
    hide_msisdn: summary.hide_mobile == 0 ? false : true,
    tlv_data: tlv,
    click_tracking: {
      flag: smsitem.url_visit_flag == 0 ? false : true,
      timestamp: isNaN(click_time_epoch) ? null : click_time_epoch,
      platform: click_platform,
    },
    submit_platform: platform_data,
  };
  mysqlData = summaryData = null;
  return preparedSmsObject;
};

const getUpdatedAppSmsData = async (smsitem, mysqlData) => {
  let dlr_time_human = new Date(smsitem.dlr_updated_on);
  let dlr_time_epoch = dlr_time_human.getTime();
  let click_time_epoch;
  if (smsitem.url_visit_ts == null || smsitem.url_visit_ts == undefined) {
    click_time_epoch = null;
  } else {
    let click_time_human = new Date(smsitem.url_visit_ts);
    click_time_epoch = click_time_human.getTime();
  }
  let click_platform = {
    ip: null,
    system: "",
    browser: "",
    city: "",
    country: "",
    location: {
      lat: 0,
      lon: 0,
    },
  };
  if (
    smsitem.url_visit_platform != "" &&
    smsitem.url_visit_platform != undefined
  ) {
    let click_platform_data = await unserialize({
      type: "single",
      body: smsitem.url_visit_platform,
    });
    let click_platform_parsed = click_platform_data;
    click_platform.ip = click_platform_parsed.ip || null;
    click_platform.system = click_platform_parsed.system || "";
    click_platform.browser = click_platform_parsed.browser || "";
    click_platform.city = click_platform_parsed.city || "";
    click_platform.country = click_platform_parsed.country || "";
    click_platform.location.lat = click_platform_parsed.lat || 0;
    click_platform.location.lon = click_platform_parsed.lon || 0;
  }
  //get refund status
  let refundflag =
    mysqlData.refundLogs[`${smsitem.sms_shoot_id}-${smsitem.mobile}`];
  let index_id_object = {
    update: { _index: env.ELASTIC_SMS_INDEX, _id: smsitem.es_index_id },
  };
  let update_doc = {
    operator_sms_id: smsitem.vendor_msgid,
    dlr: {
      type: smsitem.status == 2 ? "fakedlr" : "normal",
      kannel_code: smsitem.dlr,
      smpp_response: smsitem.smpp_resp_code,
      operator_code: smsitem.vendor_dlr,
      app_status: getAppDlrStatus(smsitem.dlr),
      time: isNaN(dlr_time_epoch) ? null : dlr_time_epoch,
    },
    refunded: refundflag == undefined ? false : true,
    ndnc:
      mysqlData.allNdncCodes.find((e) => e == smsitem.vendor_dlr) == undefined
        ? false
        : true,
    click_tracking: {
      flag: smsitem.url_visit_flag == 0 ? false : true,
      timestamp: isNaN(click_time_epoch) ? null : click_time_epoch,
      platform: click_platform,
    },
  };
  mysqlData = null;
  return [index_id_object, { doc: update_doc }];
};

/**
 * Tasks processing SMPP traffic
 */

const getSmppSmsData = async (smsitem, mysqlData) => {
  //get smsc data
  let smppobject = mysqlData.allSmsc[smsitem.smsc];
  let smscdata = {};
  if (smppobject == undefined) {
    console.log(`Missing SMPP details for SMSC: ${smsitem.smsc}`);
    smscdata = {
      host: "NA",
      port: 0,
      system_id: "NA",
    };
  } else {
    smscdata = {
      host: smppobject.host,
      port: smppobject.port,
      system_id: smppobject.system_id,
    };
  }

  //get country and mccmnc data
  let countryAndMccmnc = getMccmncAndCoverageDetails(
    smsitem.mobile,
    smsitem.mccmnc,
    {
      allMccmnc: mysqlData.allMccmnc,
      allCoverage: mysqlData.allCoverage,
      mccmncMap: mysqlData.mccmncMap,
    }
  );
  //get user data
  let userdata = mysqlData.allUsers[smsitem.user_id];
  let refundflag =
    mysqlData.refundLogs[`${smsitem.smpp_smsid}-${smsitem.mobile}`];

  let sms_type = JSON.parse(smsitem.sms_type);
  let tlv = smsitem.tlv_data == "" ? {} : JSON.parse(smsitem.tlv_data);
  let platform_data = {
    ip: null,
    system: "",
    browser: "",
    city: "",
    country: "",
    location: {
      lat: 0,
      lon: 0,
    },
  };
  if (smsitem.platform_data != "") {
    let platform_data_parsed = JSON.parse(smsitem.platform_data);
    platform_data.ip = platform_data_parsed.ip;
    platform_data.system = platform_data_parsed.system;
    platform_data.browser = platform_data_parsed.browser;
    platform_data.city = platform_data_parsed.city;
    platform_data.country = platform_data_parsed.country;
    platform_data.location.lat = platform_data_parsed.location.lat;
    platform_data.location.lon = platform_data_parsed.location.lon;
  }
  //format dates
  let send_time_human = new Date(smsitem.sending_time);
  let send_time_epoch = send_time_human.getTime();
  let dlr_time_human = new Date(smsitem.dlr_updated_on);
  let dlr_time_epoch = dlr_time_human.getTime();
  //get sms type alias
  let smstypestr = `TEXT`;
  if (sms_type.main == "text") {
    if (sms_type.unicode == true) {
      smstypestr = `UNICODE`;
    }
    if (sms_type.flash == true) {
      smstypestr = `FLASH`;
    }
    if (sms_type.unicode == true && sms_type.flash == true) {
      smstypestr = `UNICODE-FLASH`;
    }
  } else if (sms_type.main == "wap") {
    smstypestr = "WAP";
  } else if (sms_type.main == "vcard") {
    smstypestr = "VCARD";
  }
  let response = {
    mysql_id: smsitem.smpp_smsid,
    campaign_id: -1,
    sms_shoot_id: smsitem.batch_id,
    operator_sms_id: smsitem.vendor_msgid,
    user_id: smsitem.user_id,
    user_alias: smsitem.smpp_client,
    upline_user_id: userdata.upline_id,
    route_id: smsitem.route_id,
    routing_scheme: "dedicated",
    smsc: smsitem.smsc,
    smsc_data: smscdata,
    submit_time: send_time_epoch,
    channel: "SMPP",
    sender_id: smsitem.sender_id,
    msisdn: smsitem.mobile,
    country: {
      iso: countryAndMccmnc.country_code,
      prefix: countryAndMccmnc.country_prefix,
    },
    operator: {
      mccmnc: countryAndMccmnc.mccmnc,
      mcc: countryAndMccmnc.mcc,
      mnc: countryAndMccmnc.mnc,
      title: countryAndMccmnc.network,
      region: countryAndMccmnc.region,
    },
    sms_type: {
      main: sms_type.main,
      unicode: sms_type.unicode ? true : false,
      flash: sms_type.flash ? true : false,
      personalized: sms_type.personalize ? true : false,
      multipart: sms_type.multipart ? true : false,
    },
    sms_type_alias: smstypestr,
    sms_parts: smsitem.sms_count,
    sms_text: smsitem.sms_text,
    price: smsitem.price,
    cost: smsitem.cost,
    currency: env.MAIN_CURRENCY,
    dlr: {
      type: smsitem.status == 2 ? "fakedlr" : "normal",
      kannel_code: smsitem.dlr,
      smpp_response: smsitem.smpp_resp_code,
      operator_code: smsitem.vendor_dlr,
      app_status: getAppDlrStatus(smsitem.dlr),
      time: isNaN(dlr_time_epoch) ? null : dlr_time_epoch,
    },
    refunded: refundflag == undefined ? false : true,
    ndnc:
      mysqlData.allNdncCodes.find((e) => e == smsitem.vendor_dlr) == undefined
        ? false
        : true,
    attempts: 1,
    hide_msisdn: false,
    tlv_data: tlv,
    click_tracking: {
      flag: false,
      timestamp: null,
      platform: {
        ip: null,
        system: "",
        browser: "",
        city: "",
        country: "",
        location: {
          lat: 0,
          lon: 0,
        },
      },
    },
    submit_platform: platform_data,
  };
  mysqlData = null;
  return response;
};

const getUpdatedSmppSmsData = async (smsitem, mysqlData) => {
  let refundflag =
    mysqlData.refundLogs[`${smsitem.smpp_smsid}-${smsitem.mobile}`];
  let dlr_time_human = new Date(smsitem.dlr_updated_on);
  let dlr_time_epoch = dlr_time_human.getTime();
  let index_id_object = {
    update: { _index: env.ELASTIC_SMS_INDEX, _id: smsitem.es_index_id },
  };
  let update_doc = {
    operator_sms_id: smsitem.vendor_msgid,
    dlr: {
      type: smsitem.status == 2 ? "fakedlr" : "normal",
      kannel_code: smsitem.dlr,
      smpp_response: smsitem.smpp_resp_code,
      operator_code: smsitem.vendor_dlr,
      app_status: getAppDlrStatus(smsitem.dlr),
      time: isNaN(dlr_time_epoch) ? null : dlr_time_epoch,
    },
    refunded: refundflag == undefined ? false : true,
    ndnc:
      mysqlData.allNdncCodes.find((e) => e == smsitem.vendor_dlr) == undefined
        ? false
        : true,
  };
  mysqlData = null;
  return [index_id_object, { doc: update_doc }];
};
/**
 * Common functions
 *  */

const getAppDlrStatus = (dlr) => {
  let status = "";
  switch (dlr) {
    case 1:
      status = "Delivered";
      break;
    case 2:
      status = "Failed";
      break;
    case 8:
      status = "SMSC Submitted";
      break;
    case 16:
      status = "Rejected";
      break;
    case 0:
      status = "Pending DLR";
      break;
    case -1:
      status = "Invalid";
      break;

    default:
      break;
  }
  return status;
};

const getMccmncAndCoverageDetails = (
  msisdn,
  mccmnc,
  { allMccmnc, allCoverage, mccmncMap }
) => {
  let coverage = getCoverageByMsisdn(msisdn);
  let mccmnc_resp = {
    country_code: coverage == undefined ? "" : coverage.iso,
    country_prefix: coverage == undefined ? "" : coverage.prefix,
    mcc: 0,
    mnc: 0,
    mccmnc: 0,
    network: "",
    region: "",
  };

  if (mccmnc == 0) {
    //a bit more work is needed to get details
    if (coverage == undefined) return mccmnc_resp;
    let coveragedata = allCoverage[coverage.iso];
    let valid_lengths = coveragedata.valid_lengths.split(",");
    let msisdn_length = msisdn.toString().length;
    if (msisdn_length == Math.max(...valid_lengths)) {
      //mobile has country prefix
      let network_prefix = msisdn
        .toString()
        .substr(
          coverage.prefix.toString().length,
          parseInt(coveragedata.network_idn_pre_len)
        );
      //find the mccmnc code for this msisdn
      let mccmnc = mccmncMap[`${coverage.iso}-${network_prefix}`];
      if (mccmnc == undefined) return mccmnc_resp;
      let mccmncdata = allMccmnc[mccmnc];
      return mccmncdata == undefined ? mccmnc_resp : mccmncdata;
    } else {
      //mobile has no country prefix
      return mccmnc_resp;
    }
  } else {
    //we have mccmnc, get details from mccmnc list table
    let mccmncdata = allMccmnc[mccmnc];
    return mccmncdata == undefined ? mccmnc_resp : mccmncdata;
  }
};

const updateMysqlIndexStatus = async (
  elasticResponse,
  mode = "APP",
  task = "index"
) => {
  if (elasticResponse.length === 0) return;
  //prepare es _mget body
  let bodyWithElasticIds = [];
  for (let i = 0, len = elasticResponse.length; i < len; ++i) {
    let smsobject =
      task == "index" ? elasticResponse[i].index : elasticResponse[i].update;
    if (smsobject.error !== undefined) {
      console.log(
        `ES-Error: ID: ${smsobject._id} TYPE: ${smsobject.error.type} DESC: ${smsobject.error.reason}`
      );
      continue;
    }
    bodyWithElasticIds.push({ _id: smsobject._id });
  }
  //send to elastic and get mysql ids
  let mysqlIdList = await getMysqlIdsByElasticIds(bodyWithElasticIds);
  let batchValues = [];
  for (let i = 0, len = mysqlIdList.length; i < len; ++i) {
    const element = mysqlIdList[i];
    let mysql_id =
      mode == "APP"
        ? parseInt(element._source.mysql_id)
        : element._source.mysql_id;
    batchValues.push([mysql_id, 2, element._id]);
  }
  await dbquery(`addElasticIndex${mode}`, { batchValues: batchValues });
  console.log(`Updated elastic indices in Mysql for ${mode} Campaigns...`);
  return;
};

const getSmsShootIdData = async (shoot_id_list) => {
  let shootdata = await dbquery("runPlainQuery", {
    query: `SELECT campaign_id, sms_shoot_id, pushed_via, sms_type, sms_text, hide_mobile, tlv_data, platform_data FROM sc_sms_summary WHERE sms_shoot_id IN (${shoot_id_list})`,
  });
  let campaignData = [];
  for (const data of shootdata) {
    let smsTypeAndplatform = await unserialize({
      type: "bulk",
      body: [data.sms_type, data.platform_data],
    });
    //smsTypeAndplatform = JSON.parse(smsTypeAndplatform);
    campaignData[data.sms_shoot_id] = {
      campaign_id: data.campaign_id,
      sms_shoot_id: data.sms_shoot_id,
      pushed_via: data.pushed_via,
      sms_type: smsTypeAndplatform[0],
      sms_text: data.sms_text,
      hide_mobile: data.hide_mobile,
      tlv_data: data.tlv_data,
      platform_data: smsTypeAndplatform[1],
    };
  }
  //now get shoot id details by shootid using array[sms_shoot_id]
  return campaignData;
};

/**
 * MySQL data functions
 */
const getMysqlData = async () => {
  let [
    allSmsc,
    allSender,
    allMccmnc,
    allCoverage,
    refundLogs,
    mccmncMap,
    allUsers,
    allNdncCodes,
  ] = await Promise.all([
    getAllSmsc(),
    getAllSender(),
    getAllMccmnc(),
    getAllCoverage(),
    getTodaysRefundLog(),
    getPrefixToMccmncMap(),
    getAllUsers(),
    doRequests({
      url: `https://${env.ADMIN_DOMAIN}/getNdncCodes`,
      method: "POST",
    }),
  ]);
  return {
    allSmsc: allSmsc,
    allSender: allSender,
    allMccmnc: allMccmnc,
    allCoverage: allCoverage,
    refundLogs: refundLogs,
    mccmncMap: mccmncMap,
    allUsers: allUsers,
    allNdncCodes: allNdncCodes.split(","),
  };
};

const getAllSmsc = async () => {
  let smscdata = await dbquery("runPlainQuery", {
    query: `SELECT smsc_id, host, port, username FROM sc_smpp_accounts`,
  });
  let allSmsc = [];
  for (const data of smscdata) {
    allSmsc[data.smsc_id] = {
      host: data.host,
      port: data.port,
      system_id: data.username,
    };
  }
  //now get all smpp info by calling array['smsc-id']
  return allSmsc;
};
const getAllSender = async () => {
  let senderdata = await dbquery("runPlainQuery", {
    query: `SELECT id, sender_id FROM sc_sender_id`,
  });
  let allSenders = [];
  for (const data of senderdata) {
    allSenders[data.id] = data.sender_id;
  }
  //now get sender_id by id array[id]
  return allSenders;
};
const getAllMccmnc = async () => {
  let mccmncdata = await dbquery("runPlainQuery", {
    query: `SELECT country_iso, country_code, mcc, mnc, mccmnc, brand, operator FROM sc_mcc_mnc_list WHERE status = 1`,
  });
  let allMccmnc = [];
  for (const data of mccmncdata) {
    allMccmnc[data.mccmnc] = {
      country_code: data.country_iso,
      country_prefix: data.country_code,
      mcc: data.mcc,
      mnc: data.mnc,
      mccmnc: data.mccmnc,
      network: data.brand,
      region: data.operator,
    };
  }
  //now get mccmnc details by mccmnc using array[mccmnc]
  return allMccmnc;
};
const getAllCoverage = async () => {
  let coveragedata = await dbquery("runPlainQuery", {
    query: `SELECT country_code, country, prefix, valid_lengths, timezone FROM sc_coverage`,
  });
  let allCountries = [];
  for (const data of coveragedata) {
    allCountries[data.country_code] = {
      country_code: data.country_code,
      country: data.country,
      prefix: data.prefix,
      valid_lengths: data.valid_lengths,
      network_idn_pre_len: 4,
      timezone: data.timezone,
    };
  }
  //now get country details by iso using array[iso]
  return allCountries;
};
const getTodaysRefundLog = async () => {
  let today_ts = new Date();
  let today = `${today_ts.getFullYear().toString().padStart(4, "0")}-${(
    today_ts.getMonth() + 1
  )
    .toString()
    .padStart(2, "0")}-${today_ts.getDate().toString().padStart(2, "0")}`;
  let refunddata = await dbquery("runPlainQuery", {
    query: `SELECT sms_shoot_id, mobile_no FROM sc_logs_dlr_refunds WHERE timestamp LIKE '${today}%'`,
  });
  let allRefunds = [];
  for (const data of refunddata) {
    allRefunds[`${data.sms_shoot_id}-${data.mobile_no}`] = 1;
  }
  //now get refund status by shootid/smpp_smsid and mobile using array[shootid-mobile]
  return allRefunds;
};
const getPrefixToMccmncMap = async () => {
  let prefixmapdata = await dbquery("runPlainQuery", {
    query: `SELECT m.prefix, m.mccmnc, c.country_code FROM sc_nsn_prefix_list m, sc_coverage c WHERE m.mccmnc != 0 AND m.country_prefix = c.prefix ORDER BY m.prefix`,
  });
  let prefixMap = [];
  for (const data of prefixmapdata) {
    prefixMap[`${data.country_code}-${data.prefix}`] = data.mccmnc;
  }
  //now get mccmnc by network prefix using array[prefix]
  return prefixMap;
};
const getAllUsers = async () => {
  let userdata = await dbquery("runPlainQuery", {
    query: `SELECT user_id, login_id, upline_id FROM sc_users`,
  });
  let allUsers = [];
  for (const data of userdata) {
    allUsers[data.user_id] = {
      login_id: data.login_id,
      upline_id: data.upline_id,
    };
  }
  //now get user data by id array[user_id]
  return allUsers;
};

const _parsePanelAndApiSubmissions = parsePanelAndApiSubmissions;
export { _parsePanelAndApiSubmissions as parsePanelAndApiSubmissions };
const _parseSmppSubmissions = parseSmppSubmissions;
export { _parseSmppSubmissions as parseSmppSubmissions };
const _updateMysqlIndexStatus = updateMysqlIndexStatus;
export { _updateMysqlIndexStatus as updateMysqlIndexStatus };
const _getMysqlData = getMysqlData;
export { _getMysqlData as getMysqlData };
