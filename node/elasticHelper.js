"use strict";
import { env, require } from "./miscHelper.js";
require("array.prototype.flatmap").shim();
const { Client } = require("@elastic/elasticsearch");
const fs = require("fs");
const elastic_client = new Client({
  node: env.ELASTIC_MAIN_NODE,
  auth: {
    username: "elastic",
    password: "o_s0EmC1UTs1hQV0C*CA",
  },
  ssl: {
    ca: fs.readFileSync("/etc/elasticsearch/certs/http_ca.crt"),
    rejectUnauthorized: false,
  },
});

const importObjectsToIndex = async (data, mode = "APP") => {
  if (Array.isArray(data) === false) return { items: [] };
  let all_sms_objects = data.flat();
  const body = all_sms_objects.flatMap((doc) => [
    { index: { _index: env.ELASTIC_SMS_INDEX, _id: null } },
    doc,
  ]);
  const { body: bulkResponse } = await elastic_client.bulk({
    refresh: true,
    body,
  });
  console.log(
    `Indexed ${all_sms_objects.length} ${mode} SMS into Elasticsearch....`
  );
  return bulkResponse;
};

const updateObjectsInIndex = async (data, mode = "APP") => {
  if (Array.isArray(data) === false || data.length === 0) return { items: [] };
  const { body: bulkResponse } = await elastic_client.bulk({
    refresh: true,
    body: data,
  });
  console.log(`Updated ${data.length / 2} ${mode} SMS into Elasticsearch....`);
  return bulkResponse;
};

const getMysqlIdsByElasticIds = async (body) => {
  const { body: mysqlIdList } = await elastic_client.mget({
    index: env.ELASTIC_SMS_INDEX,
    _source: "mysql_id",
    body: { docs: body },
  });
  return mysqlIdList.docs;
};

const performGlobalSmsSearch = async (body, mode = "search") => {
  let response = await elastic_client.search({
    index: env.ELASTIC_SMS_INDEX,
    scroll: "50m",
    size: mode == "search" ? 500 : 200000,
    body: body,
  });
  return mode == "search"
    ? {
        total: response.body.hits.total.value,
        rows: response.body.hits.hits,
        aggs: response.body.aggregations,
      }
    : {
        rows: response.body.hits.hits,
      };
};

const prepareSmsSearchBody = (searchParams, callerData) => {
  let es_query = {},
    es_body = {},
    es_bool = {},
    es_must = [],
    es_filter = [];
  if (searchParams.sms_text !== "") {
    es_must.push({
      match: { sms_text: searchParams.sms_text },
    });
  }
  //if operator is a brand name or MCCMNC
  let mccmnc = parseInt(searchParams.operator) || 0;
  if (searchParams.operator !== "" && mccmnc === 0) {
    es_must.push({
      match: { "operator.title": searchParams.operator },
    });
  }
  //filter by authenticated user
  if (callerData.user == "1" && callerData.group == "admin") {
    if (searchParams.user_alias !== "") {
      es_filter.push({ term: { user_alias: searchParams.user_alias } });
    }
    if (searchParams.user_id !== "0") {
      es_filter.push({ term: { user_id: parseInt(searchParams.user_id) } });
    }
  }
  if (callerData.group == "reseller" || callerData.group == "staff") {
    if (searchParams.user_id === "0") {
      es_filter.push({ term: { upline_user_id: parseInt(callerData.user) } });
    } else if (parseInt(searchParams.user_id) === parseInt(callerData.user)) {
      es_filter.push({ term: { user_id: parseInt(searchParams.user_id) } });
    } else {
      es_filter.push({ term: { user_id: parseInt(searchParams.user_id) } });
      es_filter.push({ term: { upline_user_id: parseInt(callerData.user) } });
    }
    if (searchParams.user_alias !== "") {
      es_filter.push({ term: { user_alias: searchParams.user_alias } });
    }
    es_filter.push({ term: { hide_msisdn: false } });
  }
  if (callerData.group == "client") {
    es_filter.push({ term: { user_id: parseInt(callerData.user) } });
    es_filter.push({ term: { hide_msisdn: false } });
    if (searchParams.user_alias !== "") {
      es_filter.push({ term: { user_alias: searchParams.user_alias } });
    }
  }
  //term queries
  if (searchParams.channel !== "") {
    es_filter.push({
      term: { channel: searchParams.channel },
    });
  }
  if (searchParams.country_iso !== "") {
    es_filter.push({
      term: { "country.iso": searchParams.country_iso },
    });
  }
  if (searchParams.operator !== "" && mccmnc !== 0) {
    es_filter.push({
      term: { "operator.mccmnc": mccmnc },
    });
  }
  if (searchParams.dlr_type !== "" && searchParams.dlr_type !== undefined) {
    es_filter.push({
      term: { "dlr.type": searchParams.dlr_type },
    });
  }
  if (searchParams.kannel_dlr !== "") {
    es_filter.push({
      term: { "dlr.kannel_code": parseInt(searchParams.kannel_dlr) },
    });
  }
  if (searchParams.msisdn !== "") {
    es_filter.push({
      term: { msisdn: parseInt(searchParams.msisdn) },
    });
  }
  if (searchParams.mysql_id !== "") {
    es_filter.push({
      term: { mysql_id: searchParams.mysql_id },
    });
  }
  if (searchParams.operator_dlr !== "") {
    es_filter.push({
      term: { "dlr.operator_code": searchParams.operator_dlr },
    });
  }
  if (searchParams.refund_flag !== "") {
    es_filter.push({
      term: {
        refunded: parseInt(searchParams.refund_flag) === 1 ? true : false,
      },
    });
  }
  if (searchParams.route_id !== "") {
    es_filter.push({
      term: { route_id: parseInt(searchParams.route_id) },
    });
  }
  if (searchParams.sender_id !== "") {
    es_filter.push({
      term: { sender_id: searchParams.sender_id },
    });
  }
  if (searchParams.smpp_dlr !== "") {
    es_filter.push({
      term: { "dlr.smpp_response": searchParams.smpp_dlr },
    });
  }
  if (searchParams.sms_count !== "") {
    let smscount = parseInt(searchParams.sms_count);
    if (smscount === 100) {
      //search all sms greater than 5
      es_filter.push({
        range: {
          sms_parts: {
            gt: 5,
          },
        },
      });
    } else {
      es_filter.push({
        term: { sms_parts: smscount },
      });
    }
  }
  if (searchParams.sms_type_alias !== "") {
    switch (searchParams.sms_type_alias) {
      case "text":
        es_filter.push(
          { term: { "sms_type.main": "text" } },
          { term: { "sms_type.unicode": false } },
          { term: { "sms_type.flash": false } }
        );
        break;
      case "flash":
        es_filter.push(
          { term: { "sms_type.main": "text" } },
          { term: { "sms_type.unicode": false } },
          { term: { "sms_type.flash": true } }
        );
        break;
      case "unicode":
        es_filter.push(
          { term: { "sms_type.main": "text" } },
          { term: { "sms_type.unicode": true } },
          { term: { "sms_type.flash": false } }
        );
        break;
      case "unicodeflash":
        es_filter.push(
          { term: { "sms_type.main": "text" } },
          { term: { "sms_type.unicode": true } },
          { term: { "sms_type.flash": true } }
        );
        break;
      case "wap":
        es_filter.push({ term: { "sms_type.main": "wap" } });
        break;
      case "vcard":
        es_filter.push({ term: { "sms_type.main": "vcard" } });
        break;

      default:
        break;
    }
  }
  if (searchParams.smsc !== "" && searchParams.smsc !== undefined) {
    es_filter.push({ term: { smsc: searchParams.smsc } });
  }

  //date time range
  if (searchParams.date_filter !== "") {
    let parts = searchParams.date_filter.split(" - ");
    let from_ts_epoch = parts[0];
    let until_ts_epoch = parts[1];
    if (isNaN(from_ts_epoch) === false && isNaN(until_ts_epoch) === false) {
      if (from_ts_epoch == until_ts_epoch) {
        es_filter.push({ term: { submit_time: from_ts_epoch } });
      } else {
        es_filter.push({
          range: {
            submit_time: {
              gte: from_ts_epoch,
              lte: until_ts_epoch,
            },
          },
        });
      }
    }
  }

  //now based on filter and must arrays, build the search query
  if (es_must.length !== 0) {
    es_bool.must = es_must;
  }
  if (es_filter.length !== 0) {
    es_bool.filter = es_filter;
  }
  if (Object.keys(es_bool).length !== 0) {
    es_query.bool = es_bool;
    es_body.query = es_query;
  }
  //get aggregations
  es_body.aggs = {
    total_cost: {
      sum: {
        field: "cost",
      },
    },
    total_credits: {
      sum: {
        field: "sms_parts",
      },
    },
    refund_stats: {
      terms: {
        field: "refunded",
      },
      aggs: {
        cost: {
          sum: {
            field: "cost",
          },
        },
        credits: {
          sum: {
            field: "sms_parts",
          },
        },
      },
    },
    dlr_stats: {
      terms: {
        field: "dlr.kannel_code",
      },
      aggs: {
        cost: {
          sum: {
            field: "cost",
          },
        },
        credits: {
          sum: {
            field: "sms_parts",
          },
        },
      },
    },
    traffic: {
      date_histogram: {
        field: "submit_time",
        format: "yyyy-MM-dd",
        calendar_interval: "day",
      },
    },
    daily_average: {
      avg_bucket: {
        buckets_path: "traffic>_count",
      },
    },
  };
  //hide some information if not admin
  if (callerData.group !== "admin") {
    es_body._source = {
      excludes: ["smsc", "smsc_data", "dlr.type"],
    };
  }
  return es_body;
};

const prepareSmsStatsBody = (statsParams, callerData) => {
  let es_query = {},
    es_body = {},
    es_bool = {},
    es_filter = [];
  //term filters
  //filter by authenticated user
  if (callerData.user == "1" && callerData.group == "admin") {
    if (statsParams.user_alias !== "") {
      es_filter.push({ term: { user_alias: statsParams.user_alias } });
    }
    if (statsParams.user_id !== "0") {
      es_filter.push({ term: { user_id: parseInt(statsParams.user_id) } });
    }
  }
  if (callerData.group == "reseller" || callerData.group == "staff") {
    if (statsParams.user_id === "0") {
      es_filter.push({ term: { upline_user_id: parseInt(callerData.user) } });
    } else if (parseInt(statsParams.user_id) === parseInt(callerData.user)) {
      es_filter.push({ term: { user_id: parseInt(statsParams.user_id) } });
    } else {
      es_filter.push({ term: { user_id: parseInt(statsParams.user_id) } });
      es_filter.push({ term: { upline_user_id: parseInt(callerData.user) } });
    }
    if (statsParams.user_alias !== "") {
      es_filter.push({ term: { user_alias: statsParams.user_alias } });
    }
  }
  if (callerData.group == "client") {
    es_filter.push({ term: { user_id: parseInt(callerData.user) } });
    if (statsParams.user_alias !== "") {
      es_filter.push({ term: { user_alias: statsParams.user_alias } });
    }
  }
  //other filter params
  if (statsParams.route_id !== "") {
    es_filter.push({
      term: { route_id: parseInt(statsParams.route_id) },
    });
  }
  if (statsParams.sender_id !== "") {
    es_filter.push({
      term: { sender_id: statsParams.sender_id },
    });
  }
  if (statsParams.smsc !== "" && statsParams.smsc !== undefined) {
    es_filter.push({ term: { smsc: statsParams.smsc } });
  }
  //date range filter
  let format = "dd-MMM-yy";
  let interval = "day";
  if (statsParams.date_filter !== "") {
    let parts = statsParams.date_filter.split(" - ");
    let from_ts_epoch = parts[0];
    let until_ts_epoch = parts[1];
    if (isNaN(from_ts_epoch) === false && isNaN(until_ts_epoch) === false) {
      if (from_ts_epoch == until_ts_epoch) {
        es_filter.push({ term: { submit_time: from_ts_epoch } });
      } else {
        es_filter.push({
          range: {
            submit_time: {
              gte: from_ts_epoch,
              lte: until_ts_epoch,
            },
          },
        });
      }
      //check interval
      let intervalHours = (until_ts_epoch - from_ts_epoch) / (1000 * 3600);
      if (intervalHours < 2) {
        interval = "minute";
        format = "HH:mm";
      } else if (intervalHours < 25) {
        interval = "hour";
        format = "HH:mm";
      } else if (intervalHours >= 25 && intervalHours < 2160) {
        interval = "day";
        format = "dd-MMM-yy";
      } else if (intervalHours >= 2160) {
        interval = "month";
        format = "MMM yyyy";
      }
    }
  }
  if (es_filter.length !== 0) {
    es_bool.filter = es_filter;
  }
  if (Object.keys(es_bool).length !== 0) {
    es_query.bool = es_bool;
    es_body.query = es_query;
  }
  //aggregation rules
  es_body.aggs = {
    traffic_summary: {
      date_histogram: {
        field: "submit_time",
        format: format,
        calendar_interval: interval,
        time_zone: env.SYSTEM_TIMEZONE,
      },
      aggs: {
        total_dlr: { terms: { field: "dlr.kannel_code" } },
        total_ndnc: { terms: { field: "ndnc" } },
      },
    },
    dlr_summary: {
      terms: { field: "dlr.kannel_code" },
      aggs: {
        cost: { sum: { field: "cost" } },
        credits: { sum: { field: "sms_parts" } },
      },
    },
    channels: { terms: { field: "channel" } },
    sms_types: { terms: { field: "sms_type_alias" } },
    networks: { terms: { field: "operator.title.keyword" } },
    countries: { terms: { field: "country.iso" } },
    refunded: {
      terms: { field: "refunded" },
      aggs: {
        cost: { sum: { field: "cost" } },
        credits: { sum: { field: "sms_parts" } },
      },
    },
    ndnc: {
      terms: { field: "ndnc" },
      aggs: {
        cost: { sum: { field: "cost" } },
        credits: { sum: { field: "sms_parts" } },
      },
    },
    total_cost: { sum: { field: "cost" } },
    total_credits: { sum: { field: "sms_parts" } },
  };
  return es_body;
};

const performGlobalSmsAggregations = async (body) => {
  let response = await elastic_client.search({
    index: env.ELASTIC_SMS_INDEX,
    scroll: "50m",
    size: 1,
    body: body,
  });
  return {
    total: response.body.hits.total.value,
    aggs: response.body.aggregations,
  };
};

const prepareMiniStatsRequest = (params, callerData) => {
  let es_query = {},
    es_body = {},
    es_bool = {},
    es_aggs = {},
    es_filter = [];
  //term filters

  if (params.mode === "top_sms_stats") {
    //filter by authenticated user
    if (callerData.group != "admin") {
      es_filter.push({ term: { user_id: parseInt(callerData.user) } });
    }
    //get the last 30 days sms sent day wise
    es_filter.push({
      range: {
        submit_time: {
          gte: new Date().setDate(new Date().getDate() - 30),
          lte: Date.now(),
        },
      },
    });
    if (es_filter.length !== 0) {
      es_bool.filter = es_filter;
    }
    if (Object.keys(es_bool).length !== 0) {
      es_query.bool = es_bool;
      es_body.query = es_query;
    }
    es_body.aggs = {
      top_sms_stats: {
        date_histogram: {
          field: "submit_time",
          format: "dd-MMM-yy",
          calendar_interval: "day",
        },
        aggs: {
          credits: { sum: { field: "sms_parts" } },
          cost: { sum: { field: "cost" } },
          refunds: { terms: { field: "refunded" } },
        },
      },
    };
  } else {
    //date filter based on mode
    if (params.mode == "all") {
      es_filter.push({
        range: {
          submit_time: {
            gte: new Date().setDate(new Date().getDate() - 7),
            lte: Date.now(),
          },
        },
      });
    }
    //roc
    if (params.mode == "all" || params.mode == "roc") {
      if (callerData.group == "admin") {
        //checck date filter
        if (params.mode != "all") {
          let supplied_ts_range = params.roc_date_range.split(" - ");
          if (
            new Date(parseInt(supplied_ts_range[0])).getTime() > 0 &&
            new Date(parseInt(supplied_ts_range[1])).getTime() > 0
          ) {
            //valid dates supplied
            if (supplied_ts_range[0] === supplied_ts_range[1]) {
              //same day
              es_filter.push({
                range: {
                  submit_time: {
                    gte: supplied_ts_range[0],
                    lt: parseInt(supplied_ts_range[1]) + 86400000,
                  },
                },
              });
            } else {
              es_filter.push({
                range: {
                  submit_time: {
                    gte: supplied_ts_range[0],
                    lte: supplied_ts_range[1],
                  },
                },
              });
            }
          } else {
            console.log("Invalid date Supplied for ROC");
          }
        }
        //route or carrier report only for admin
        let summarty_field = params.roc_mode == "r" ? "route_id" : "smsc";
        es_aggs.roc_data = {
          terms: { field: summarty_field, size: 10 },
          aggs: {
            dlr_for_route: { terms: { field: "dlr.kannel_code" } },
            ndnc: { terms: { field: "ndnc" } },
            refunds: { terms: { field: "refunded" } },
          },
        };
      }
    }
    //top consumers
    if (params.mode == "all" || params.mode == "topclients") {
      if (callerData.group != "admin") {
        es_filter.push({ term: { upline_user_id: parseInt(callerData.user) } });
      }
      //checck date filter
      if (params.mode != "all") {
        let supplied_ts_range = params.top_users_date_range.split(" - ");
        if (
          new Date(parseInt(supplied_ts_range[0])).getTime() > 0 &&
          new Date(parseInt(supplied_ts_range[1])).getTime() > 0
        ) {
          //valid dates supplied
          if (supplied_ts_range[0] === supplied_ts_range[1]) {
            //same day
            es_filter.push({
              range: {
                submit_time: {
                  gte: supplied_ts_range[0],
                  lt: parseInt(supplied_ts_range[1]) + 86400000,
                },
              },
            });
          } else {
            es_filter.push({
              range: {
                submit_time: {
                  gte: supplied_ts_range[0],
                  lte: supplied_ts_range[1],
                },
              },
            });
          }
        }
      }
      es_aggs.top_users = { terms: { field: "user_id" } };
    }
    // route wise data for client
    if (params.mode == "clientroutessms") {
      es_filter.push({ term: { user_id: parseInt(callerData.user) } });
      //date filter
      let supplied_ts_range = params.client_routes_date_range.split(" - ");
      if (
        new Date(parseInt(supplied_ts_range[0])).getTime() > 0 &&
        new Date(parseInt(supplied_ts_range[1])).getTime() > 0
      ) {
        //valid dates supplied
        if (supplied_ts_range[0] === supplied_ts_range[1]) {
          //same day
          es_filter.push({
            range: {
              submit_time: {
                gte: supplied_ts_range[0],
                lt: parseInt(supplied_ts_range[1]) + 86400000,
              },
            },
          });
        } else {
          es_filter.push({
            range: {
              submit_time: {
                gte: supplied_ts_range[0],
                lte: supplied_ts_range[1],
              },
            },
          });
        }
      } else {
        console.log("Invalid date Supplied for Client Routes");
      }
      //aggs
      es_aggs.client_routes = {
        terms: { field: "route_id", size: 10 },
        aggs: {
          dlr_for_route: { terms: { field: "dlr.kannel_code" } },
        },
      };
    }
    if (es_filter.length !== 0) {
      es_bool.filter = es_filter;
    }
    if (Object.keys(es_bool).length !== 0) {
      es_query.bool = es_bool;
      es_body.query = es_query;
    }
    es_body.aggs = es_aggs;
  }
  return es_body;
};

const getSmsForArchiving = async (timestamp) => {
  //prepare the earch body
  let es_query = {},
    es_body = {},
    es_bool = {},
    es_must = [],
    es_filter = [];

  //date time range
  es_filter.push({
    range: {
      submit_time: {
        lte: timestamp,
      },
    },
  });

  //now based on filter and must arrays, build the search query
  if (es_must.length !== 0) {
    es_bool.must = es_must;
  }
  if (es_filter.length !== 0) {
    es_bool.filter = es_filter;
  }
  if (Object.keys(es_bool).length !== 0) {
    es_query.bool = es_bool;
    es_body.query = es_query;
  }

  //call search API
  let response = await elastic_client.search({
    index: env.ELASTIC_SMS_INDEX,
    scroll: "50m",
    size: 50000,
    body: es_body,
  });
  return {
    total: response.body.hits.total.value,
    rows: response.body.hits.hits,
  };
};

const deleteArchivedRecords = async (idList) => {
  let bulkObj = idList.map((id) => {
    return {
      delete: {
        _index: env.ELASTIC_SMS_INDEX,
        _type: "_doc",
        _id: id,
      },
    };
  });
  try {
    await elastic_client.bulk({ refresh: true, body: bulkObj });
    console.log(`Archive data deleted from Elasticsearch..`);
  } catch (error) {
    console.log(`Deleting archive failed from ES stack...`);
  }
  return;
};

const _importObjectsToIndex = importObjectsToIndex;
export { _importObjectsToIndex as importObjectsToIndex };
const _getMysqlIdsByElasticIds = getMysqlIdsByElasticIds;
export { _getMysqlIdsByElasticIds as getMysqlIdsByElasticIds };
const _updateObjectsInIndex = updateObjectsInIndex;
export { _updateObjectsInIndex as updateObjectsInIndex };
const _performGlobalSmsSearch = performGlobalSmsSearch;
export { _performGlobalSmsSearch as performGlobalSmsSearch };
const _prepareSmsSearchBody = prepareSmsSearchBody;
export { _prepareSmsSearchBody as prepareSmsSearchBody };
const _prepareSmsStatsBody = prepareSmsStatsBody;
export { _prepareSmsStatsBody as prepareSmsStatsBody };
const _performGlobalSmsAggregations = performGlobalSmsAggregations;
export { _performGlobalSmsAggregations as performGlobalSmsAggregations };
const _prepareMiniStatsRequest = prepareMiniStatsRequest;
export { _prepareMiniStatsRequest as prepareMiniStatsRequest };
const _getSmsForArchiving = getSmsForArchiving;
export { _getSmsForArchiving as getSmsForArchiving };
const _deleteArchivedRecords = deleteArchivedRecords;
export { _deleteArchivedRecords as deleteArchivedRecords };
