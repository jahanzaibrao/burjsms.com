"use strict";
import { createRequire } from "module";
const require = createRequire(import.meta.url);
const env = require("dotenv").config().parsed;
var ip2loc = require("ip2location-nodejs");
const Entities = require("html-entities").AllHtmlEntities;
const { promisify } = require("util");
const exec = promisify(require("child_process").exec);
const https = require("https");
const fetch = require("node-fetch");
const entities = new Entities();
const parseQuery = (queryString) => {
  let query = {};
  let pairs = (
    queryString[0] === "?" ? queryString.substr(1) : queryString
  ).split("&");
  for (let i = 0; i < pairs.length; i++) {
    let pair = pairs[i].split("=");
    query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || "");
  }
  return query;
};

const doRequests_cb = (url) => {
  return new Promise((resolve, reject) => {
    https
      .get(url, (resp) => {
        let data = "";

        // A chunk of data has been recieved.
        resp.on("data", (chunk) => {
          data += chunk;
        });

        // The whole response has been received. Print out the result.
        resp.on("end", () => {
          resolve(data);
        });
      })
      .on("error", (err) => {
        console.log("Error: " + err.message);
      });
  });
};
let country_list = [
  { prefix: 91, iso: "IN" },
  { prefix: 1, iso: "US" },
  { prefix: 65, iso: "SG" },
  { prefix: 213, iso: "DZ" },
  { prefix: 244, iso: "AO" },
  { prefix: 229, iso: "BJ" },
  { prefix: 267, iso: "BW" },
  { prefix: 226, iso: "BF" },
  { prefix: 257, iso: "BI" },
  { prefix: 237, iso: "CM" },
  { prefix: 238, iso: "CV" },
  { prefix: 236, iso: "CF" },
  { prefix: 235, iso: "TD" },
  { prefix: 269, iso: "KM" },
  { prefix: 243, iso: "CD" },
  { prefix: 242, iso: "CG" },
  { prefix: 253, iso: "DJ" },
  { prefix: 20, iso: "EG" },
  { prefix: 240, iso: "GQ" },
  { prefix: 291, iso: "ER" },
  { prefix: 251, iso: "ET" },
  { prefix: 241, iso: "GA" },
  { prefix: 220, iso: "GM" },
  { prefix: 233, iso: "GH" },
  { prefix: 224, iso: "GN" },
  { prefix: 245, iso: "GW" },
  { prefix: 225, iso: "CI" },
  { prefix: 254, iso: "KE" },
  { prefix: 266, iso: "LS" },
  { prefix: 231, iso: "LR" },
  { prefix: 218, iso: "LY" },
  { prefix: 261, iso: "MG" },
  { prefix: 265, iso: "MW" },
  { prefix: 223, iso: "ML" },
  { prefix: 222, iso: "MR" },
  { prefix: 230, iso: "MU" },
  { prefix: 212, iso: "MA" },
  { prefix: 258, iso: "MZ" },
  { prefix: 264, iso: "NA" },
  { prefix: 227, iso: "NE" },
  { prefix: 250, iso: "RW" },
  { prefix: 290, iso: "SH" },
  { prefix: 221, iso: "SN" },
  { prefix: 248, iso: "SC" },
  { prefix: 232, iso: "SL" },
  { prefix: 252, iso: "SO" },
  { prefix: 27, iso: "ZA" },
  { prefix: 249, iso: "SD" },
  { prefix: 268, iso: "SZ" },
  { prefix: 239, iso: "ST" },
  { prefix: 255, iso: "TZ" },
  { prefix: 228, iso: "TG" },
  { prefix: 216, iso: "TN" },
  { prefix: 256, iso: "UG" },
  { prefix: 260, iso: "ZM" },
  { prefix: 263, iso: "ZW" },
  { prefix: 262, iso: "TF" },
  { prefix: 93, iso: "AF" },
  { prefix: 374, iso: "AM" },
  { prefix: 994, iso: "AZ" },
  { prefix: 973, iso: "BH" },
  { prefix: 880, iso: "BD" },
  { prefix: 975, iso: "BT" },
  { prefix: 673, iso: "BN" },
  { prefix: 855, iso: "KH" },
  { prefix: 86, iso: "CN" },
  { prefix: 995, iso: "GE" },
  { prefix: 852, iso: "HK" },
  { prefix: 62, iso: "ID" },
  { prefix: 98, iso: "IR" },
  { prefix: 964, iso: "IQ" },
  { prefix: 972, iso: "IL" },
  { prefix: 81, iso: "JP" },
  { prefix: 962, iso: "JO" },
  { prefix: 7, iso: "KZ" },
  { prefix: 965, iso: "KW" },
  { prefix: 996, iso: "KG" },
  { prefix: 856, iso: "LA" },
  { prefix: 961, iso: "LB" },
  { prefix: 853, iso: "MO" },
  { prefix: 60, iso: "MY" },
  { prefix: 960, iso: "MV" },
  { prefix: 976, iso: "MN" },
  { prefix: 95, iso: "MM" },
  { prefix: 977, iso: "NP" },
  { prefix: 850, iso: "KP" },
  { prefix: 968, iso: "OM" },
  { prefix: 92, iso: "PK" },
  { prefix: 970, iso: "PS" },
  { prefix: 63, iso: "PH" },
  { prefix: 974, iso: "QA" },
  { prefix: 966, iso: "SA" },
  { prefix: 82, iso: "KR" },
  { prefix: 94, iso: "LK" },
  { prefix: 963, iso: "SY" },
  { prefix: 886, iso: "TW" },
  { prefix: 992, iso: "TJ" },
  { prefix: 66, iso: "TH" },
  { prefix: 90, iso: "TR" },
  { prefix: 993, iso: "TM" },
  { prefix: 971, iso: "AE" },
  { prefix: 998, iso: "UZ" },
  { prefix: 84, iso: "VN" },
  { prefix: 967, iso: "YE" },
  { prefix: 355, iso: "AL" },
  { prefix: 376, iso: "AD" },
  { prefix: 43, iso: "AT" },
  { prefix: 375, iso: "BY" },
  { prefix: 32, iso: "BE" },
  { prefix: 387, iso: "BA" },
  { prefix: 359, iso: "BG" },
  { prefix: 385, iso: "HR" },
  { prefix: 357, iso: "CY" },
  { prefix: 420, iso: "CZ" },
  { prefix: 45, iso: "DK" },
  { prefix: 372, iso: "EE" },
  { prefix: 298, iso: "FO" },
  { prefix: 358, iso: "FI" },
  { prefix: 33, iso: "FR" },
  { prefix: 49, iso: "DE" },
  { prefix: 350, iso: "GI" },
  { prefix: 30, iso: "GR" },
  { prefix: 36, iso: "HU" },
  { prefix: 354, iso: "IS" },
  { prefix: 353, iso: "IE" },
  { prefix: 39, iso: "IT" },
  { prefix: 383, iso: "XK" },
  { prefix: 371, iso: "LV" },
  { prefix: 423, iso: "LI" },
  { prefix: 370, iso: "LT" },
  { prefix: 352, iso: "LU" },
  { prefix: 389, iso: "MK" },
  { prefix: 356, iso: "MT" },
  { prefix: 373, iso: "MD" },
  { prefix: 377, iso: "MC" },
  { prefix: 382, iso: "ME" },
  { prefix: 31, iso: "NL" },
  { prefix: 47, iso: "NO" },
  { prefix: 48, iso: "PL" },
  { prefix: 351, iso: "PT" },
  { prefix: 40, iso: "RO" },
  { prefix: 79, iso: "RU" },
  { prefix: 378, iso: "SM" },
  { prefix: 381, iso: "RS" },
  { prefix: 421, iso: "SK" },
  { prefix: 386, iso: "SI" },
  { prefix: 34, iso: "ES" },
  { prefix: 46, iso: "SE" },
  { prefix: 41, iso: "CH" },
  { prefix: 380, iso: "UA" },
  { prefix: 44, iso: "GB" },
  { prefix: 297, iso: "AW" },
  { prefix: 501, iso: "BZ" },
  { prefix: 599, iso: "BQ" },
  { prefix: 506, iso: "CR" },
  { prefix: 53, iso: "CU" },
  { prefix: 503, iso: "SV" },
  { prefix: 299, iso: "GL" },
  { prefix: 502, iso: "GT" },
  { prefix: 509, iso: "HT" },
  { prefix: 504, iso: "HN" },
  { prefix: 596, iso: "MQ" },
  { prefix: 52, iso: "MX" },
  { prefix: 505, iso: "NI" },
  { prefix: 507, iso: "PA" },
  { prefix: 590, iso: "BL" },
  { prefix: 508, iso: "PM" },
  { prefix: 54, iso: "AR" },
  { prefix: 591, iso: "BO" },
  { prefix: 55, iso: "BR" },
  { prefix: 56, iso: "CL" },
  { prefix: 57, iso: "CO" },
  { prefix: 593, iso: "EC" },
  { prefix: 500, iso: "FK" },
  { prefix: 594, iso: "GF" },
  { prefix: 595, iso: "PY" },
  { prefix: 51, iso: "PE" },
  { prefix: 597, iso: "SR" },
  { prefix: 598, iso: "UY" },
  { prefix: 58, iso: "VE" },
  { prefix: 61, iso: "AU" },
  { prefix: 682, iso: "CK" },
  { prefix: 670, iso: "TL" },
  { prefix: 679, iso: "FJ" },
  { prefix: 689, iso: "PF" },
  { prefix: 686, iso: "KI" },
  { prefix: 692, iso: "MH" },
  { prefix: 691, iso: "FM" },
  { prefix: 674, iso: "NR" },
  { prefix: 687, iso: "NC" },
  { prefix: 64, iso: "NZ" },
  { prefix: 683, iso: "NU" },
  { prefix: 672, iso: "NF" },
  { prefix: 680, iso: "PW" },
  { prefix: 675, iso: "PG" },
  { prefix: 685, iso: "WS" },
  { prefix: 677, iso: "SB" },
  { prefix: 690, iso: "TK" },
  { prefix: 676, iso: "TO" },
  { prefix: 688, iso: "TV" },
  { prefix: 678, iso: "VU" },
  { prefix: 681, iso: "WF" },
  { prefix: 234, iso: "NG" },
];
let tsToDate = (ts) => {
  let dt = new Date(ts);
  return `${dt.getFullYear().toString().padStart(4, "0")}-${(dt.getMonth() + 1)
    .toString()
    .padStart(2, "0")}-${dt.getDate().toString().padStart(2, "0")} ${dt
    .getHours()
    .toString()
    .padStart(2, "0")}:${dt.getMinutes().toString().padStart(2, "0")}:${dt
    .getSeconds()
    .toString()
    .padStart(2, "0")}`;
};
let getCoverageByMsisdn = (msisdn) => {
  msisdn = msisdn.toString();
  return country_list.find(
    (item) => msisdn.substr(0, item.prefix.toString().length) == item.prefix
  );
};

const doRequests = async (data) => {
  process.env["NODE_TLS_REJECT_UNAUTHORIZED"] = 0;
  let fetchRes = await fetch(data.url, {
    method: data.method,
    headers: { "Content-Type": "application/json" },
    body: data.body ? JSON.stringify(data.body) : "",
  });
  return await fetchRes.text();
};

const unserialize = async (data, mode = "shell") =>
  Promise.resolve().then((x) => {
    //because shell is too slow for bulk and http will crash the server
    if (data.type == "single") {
      return unserializeHelper(data.body);
    } else {
      let readyStr = data.body.map(unserializeHelper);
      return readyStr;
    }
  });

const getPlatformDataByIp = (ip) => {
  ip2loc.IP2Location_init(env.IP2LOC_FILE);
  let result = ip2loc.IP2Location_get_all(ip);
  ip2loc.IP2Location_close();
  return {
    ip: ip,
    system: "",
    browser: "",
    city: result.city,
    country: result.country_long,
    location: {
      lat: result.latitude,
      lon: result.longitude,
    },
  };
};

let unserializeHelper = (phpstr) => {
  var idx = 0,
    refStack = [],
    ridx = 0,
    parseNext, // forward declaraton for "use strict"
    readLength = function () {
      var del = phpstr.indexOf(":", idx),
        val = phpstr.substring(idx, del);
      idx = del + 2;
      return parseInt(val, 10);
    }, //end readLength
    readInt = function () {
      var del = phpstr.indexOf(";", idx),
        val = phpstr.substring(idx, del);
      idx = del + 1;
      return parseInt(val, 10);
    }, //end readInt
    parseAsInt = function () {
      var val = readInt();
      refStack[ridx++] = val;
      return val;
    }, //end parseAsInt
    parseAsFloat = function () {
      var del = phpstr.indexOf(";", idx),
        val = phpstr.substring(idx, del);
      idx = del + 1;
      val = parseFloat(val);
      refStack[ridx++] = val;
      return val;
    }, //end parseAsFloat
    parseAsBoolean = function () {
      var del = phpstr.indexOf(";", idx),
        val = phpstr.substring(idx, del);
      idx = del + 1;
      val = "1" === val ? true : false;
      refStack[ridx++] = val;
      return val;
    }, //end parseAsBoolean
    readString = function () {
      var len = readLength(),
        utfLen = 0,
        bytes = 0,
        ch,
        val;
      while (bytes < len) {
        ch = phpstr.charCodeAt(idx + utfLen++);
        if (ch <= 0x007f) {
          bytes++;
        } else if (ch > 0x07ff) {
          bytes += 3;
        } else {
          bytes += 2;
        }
      }
      val = phpstr.substring(idx, idx + utfLen);
      idx += utfLen + 2;
      return val;
    }, //end readString
    parseAsString = function () {
      var val = readString();
      refStack[ridx++] = val;
      return val;
    }, //end parseAsString
    readType = function () {
      var type = phpstr.charAt(idx);
      idx += 2;
      return type;
    }, //end readType
    readKey = function () {
      var type = readType();
      switch (type) {
        case "i":
          return readInt();
        case "s":
          return readString();
        default:
          throw {
            name: "Parse Error",
            message: "Unknown key type '" + type + "' at position " + (idx - 2),
          };
      } //end switch
    },
    parseAsArray = function () {
      var len = readLength(),
        resultArray = [],
        resultHash = {},
        keep = resultArray,
        lref = ridx++,
        key,
        val,
        i,
        j,
        alen;

      refStack[lref] = keep;
      for (i = 0; i < len; i++) {
        key = readKey();
        val = parseNext();
        if (keep === resultArray && parseInt(key, 10) === i) {
          // store in array version
          resultArray.push(val);
        } else {
          if (keep !== resultHash) {
            // found first non-sequential numeric key
            // convert existing data to hash
            for (j = 0, alen = resultArray.length; j < alen; j++) {
              resultHash[j] = resultArray[j];
            }
            keep = resultHash;
            refStack[lref] = keep;
          }
          resultHash[key] = val;
        } //end if
      } //end for

      idx++;
      return keep;
    }, //end parseAsArray
    fixPropertyName = function (parsedName, baseClassName) {
      var class_name, prop_name, pos;
      if ("\u0000" === parsedName.charAt(0)) {
        // "<NUL>*<NUL>property"
        // "<NUL>class<NUL>property"
        pos = parsedName.indexOf("\u0000", 1);
        if (pos > 0) {
          class_name = parsedName.substring(1, pos);
          prop_name = parsedName.substr(pos + 1);

          if ("*" === class_name) {
            // protected
            return prop_name;
          } else if (baseClassName === class_name) {
            // own private
            return prop_name;
          } else {
            // private of a descendant
            return class_name + "::" + prop_name;

            // On the one hand, we need to prefix property name with
            // class name, because parent and child classes both may
            // have private property with same name. We don't want
            // just to overwrite it and lose something.
            //
            // On the other hand, property name can be "foo::bar"
            //
            //     $obj = new stdClass();
            //     $obj->{"foo::bar"} = 42;
            //     // any user-defined class can do this by default
            //
            // and such property also can overwrite something.
            //
            // So, we can to lose something in any way.
          }
        }
      } else {
        // public "property"
        return parsedName;
      }
    },
    parseAsObject = function () {
      var len,
        obj = {},
        lref = ridx++,
        // HACK last char after closing quote is ':',
        // but not ';' as for normal string
        clazzname = readString(),
        key,
        val,
        i;

      refStack[lref] = obj;
      len = readLength();
      for (i = 0; i < len; i++) {
        key = fixPropertyName(readKey(), clazzname);
        val = parseNext();
        obj[key] = val;
      }
      idx++;
      return obj;
    }, //end parseAsObject
    parseAsCustom = function () {
      var clazzname = readString(),
        content = readString();
      return {
        __PHP_Incomplete_Class_Name: clazzname,
        serialized: content,
      };
    }, //end parseAsCustom
    parseAsRefValue = function () {
      var ref = readInt(),
        // php's ref counter is 1-based; our stack is 0-based.
        val = refStack[ref - 1];
      refStack[ridx++] = val;
      return val;
    }, //end parseAsRefValue
    parseAsRef = function () {
      var ref = readInt();
      // php's ref counter is 1-based; our stack is 0-based.
      return refStack[ref - 1];
    }, //end parseAsRef
    parseAsNull = function () {
      var val = null;
      refStack[ridx++] = val;
      return val;
    }; //end parseAsNull

  parseNext = function () {
    var type = readType();
    switch (type) {
      case "i":
        return parseAsInt();
      case "d":
        return parseAsFloat();
      case "b":
        return parseAsBoolean();
      case "s":
        return parseAsString();
      case "a":
        return parseAsArray();
      case "O":
        return parseAsObject();
      case "C":
        return parseAsCustom();

      // link to object, which is a value - affects refStack
      case "r":
        return parseAsRefValue();

      // PHP's reference - DOES NOT affect refStack
      case "R":
        return parseAsRef();

      case "N":
        return parseAsNull();
      default:
        throw {
          name: "Parse Error",
          message: "Unknown type '" + type + "' at position " + (idx - 2),
        };
    } //end switch
  }; //end parseNext

  return parseNext();
};

let apiCalls = async (data) => {
  let attempt = (parseInt(data.attempts) || 0) + 1;
  let dlrs = [];
  let postobj = {};
  //prepart post obj
  if (data.mode == "ntapi") {
    postobj = {
      CMD: "DLVRREP",
      NTYPE: "REP",
      FROM: data.mobile,
      SMID: data.sms_id,
      STATUS: "OK",
      DETAIL: data.operator_reply,
    };
  }

  if (data.mode == "native") {
    dlrs = [
      {
        message_id: data.sms_id,
        mobile: data.mobile,
        dlr: getDlrExplanation(data.dlr),
        operator_reply: `${data.operator_reply}`,
        sms_delivery_time: `${data.delivery_ts}`,
      },
    ];
    if (data.sms_count > 1) {
      for (let i = 0; i < data.sms_count; i++) {
        dlrs.push({
          message_id: `${data.sms_id}_${i + 2}`,
          mobile: data.mobile,
          dlr: getDlrExplanation(data.dlr),
          operator_reply: `${data.operator_reply}`,
          sms_delivery_time: `${data.delivery_ts}`,
        });
      }
    }
    postobj = {
      sms_shoot_id: data.sms_shoot_id,
      mobile: data.mobile,
      sms_sent_time: `${data.sms_sent_ts}`,
      sender_id: data.sender_id,
      credits_charged: data.sms_count,
      dlr: dlrs,
      post_attempt: attempt,
      route_data: {
        id: data.route_id,
        title: data.route_title,
      },
    };
  }

  console.log(
    `Attempt ${attempt} on ${data.sms_shoot_id} hitting to ${data.callback_url}....`
  );
  let row_id = data.id;

  let fetchRes = await fetch(data.callback_url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(postobj),
  });
  let status = await fetchRes.status;
  let jRes = await fetchRes.text();
  let result = 0;
  if (status >= 200 && status < 300) {
    result = 1;
  }
  console.log(`Returned ${status} for ${attempt} hit`);
  return {
    id: row_id,
    status: result,
    attempts: attempt,
    response: JSON.stringify(jRes),
  };
};

let bulkApiCall = async (url, dlrList) => {
  let postArray = new Array();
  let rowIdArray = new Array();
  for (const data of dlrList) {
    let attempt = (parseInt(data.attempts) || 0) + 1;
    //prepart post obj
    let dlrs = [
      {
        message_id: data.sms_id,
        mobile: data.mobile,
        dlr: getDlrExplanation(data.dlr),
        operator_reply: `${data.operator_reply}`,
        sms_delivery_time: `${data.delivery_ts}`,
      },
    ];
    if (data.sms_count > 1) {
      for (let i = 0; i < data.sms_count; i++) {
        dlrs.push({
          message_id: `${data.sms_id}_${i + 2}`,
          mobile: data.mobile,
          dlr: getDlrExplanation(data.dlr),
          operator_reply: `${data.operator_reply}`,
          sms_delivery_time: `${data.delivery_ts}`,
        });
      }
    }
    let postobj = {
      sms_shoot_id: data.sms_shoot_id,
      mobile: data.mobile,
      sms_sent_time: `${data.sms_sent_ts}`,
      sender_id: data.sender_id,
      credits_charged: data.sms_count,
      dlr: dlrs,
      post_attempt: attempt,
      route_data: {
        id: data.route_id,
        title: data.route_title,
      },
    };

    postArray.push(postobj);
    rowIdArray.push(data.id);
  }

  console.log(`${dlrList.length} DLRs hitting to ${url}....`);

  let fetchRes = await fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(postArray),
  });
  let status = await fetchRes.status;
  let jRes = await fetchRes.text();
  let result = 0;
  if (status >= 200 && status < 300) {
    result = 1;
  }
  console.log(`Returned ${status} for Bulk hit`);
  return {
    idList: `${rowIdArray.join(",")}`,
    status: result,
    response: JSON.stringify(jRes),
  };
};

let sortApiCalls = async (data) => {
  //this sorts dlr api calls by callback url
  const sortedbatch = new Array();
  const map = new Map();
  for (let item of data) {
    if (!map.has(item.callback_url)) {
      //new url
      map.set(item.callback_url, `present`);
      sortedbatch[item.callback_url] = new Array();
      sortedbatch[item.callback_url].push(item);
    } else {
      //url already exist in sorted batch, append item to it
      sortedbatch[item.callback_url].push(item);
    }
  }
  return sortedbatch;
};

let getDlrExplanation = (dlr) => {
  let explanation = "";
  switch (dlr) {
    case -1:
      explanation = "INVALID";
      break;
    case 1:
      explanation = "DELIVERED";
      break;
    case 2:
      explanation = "FAILED";
      break;
    case 16:
      explanation = "REJECTED";
      break;
  }
  return explanation;
};

const _require = require;
export { _require as require };
const _env = env;
export { _env as env };
const _entities = entities;
export { _entities as entities };
const _unserialize = unserialize;
export { _unserialize as unserialize };
const _tsToDate = tsToDate;
export { _tsToDate as tsToDate };
const _getCoverageByMsisdn = getCoverageByMsisdn;
export { _getCoverageByMsisdn as getCoverageByMsisdn };
const _doRequests = doRequests;
export { _doRequests as doRequests };
const _getPlatformDataByIp = getPlatformDataByIp;
export { _getPlatformDataByIp as getPlatformDataByIp };
const _parseQuery = parseQuery;
export { _parseQuery as parseQuery };
const _apiCalls = apiCalls;
export { _apiCalls as apiCalls };
const _sortApiCalls = sortApiCalls;
export { _sortApiCalls as sortApiCalls };
const _bulkApiCall = bulkApiCall;
export { _bulkApiCall as bulkApiCall };
const _doRequests_cb = doRequests_cb;
export { _doRequests_cb as doRequests_cb };
