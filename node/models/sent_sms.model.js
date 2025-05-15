import mongoose from "mongoose";

const Schema = mongoose.Schema;
const sentSmsSchema = new Schema({
	attempts: {
		type: Number,
	},
	campaign_id: {
		type: Number,
	},
	channel: {
		type: String,
	},
	click_tracking: {
		flag: {
			type: Boolean,
		},
		platform: {
			browser: {
				type: String,
			},
			city: {
				type: String,
			},
			country: {
				type: String,
			},
			ip: {
				type: String,
			},
			location: {
				lat: { type: Schema.Types.Decimal128 },
				lon: { type: Schema.Types.Decimal128 },
			},
			system: {
				type: String,
			},
		},
		timestamp: {
			type: Date,
		},
	},
	cost: {
		type: Schema.Types.Decimal128,
	},
	country: {
		iso: {
			type: String,
		},
		prefix: {
			type: String,
		},
	},
	currency: {
		type: String,
	},
	dlr: {
		app_status: {
			type: String,
		},
		kannel_code: {
			type: Number,
		},
		operator_code: {
			type: String,
		},
		smpp_response: {
			type: String,
		},
		time: {
			type: Date,
		},
		type: {
			type: String,
		},
	},
	es_id: {
		type: String,
	},
	hide_msisdn: {
		type: Boolean,
	},
	msisdn: {
		type: String,
	},
	mysql_id: {
		type: String,
	},
	ndnc: {
		type: Boolean,
	},
	operator: {
		mcc: {
			type: Number,
		},
		mccmnc: {
			type: Number,
		},
		mnc: {
			type: Number,
		},
		region: {
			type: String,
		},
		title: {
			type: String,
		},
	},
	operator_sms_id: {
		type: String,
	},
	price: {
		type: Schema.Types.Decimal128,
	},
	refunded: {
		type: Boolean,
	},
	route_id: {
		type: Number,
	},
	routing_scheme: {
		type: String,
	},
	sender_id: {
		type: String,
	},
	sms_parts: {
		type: Number,
	},
	sms_shoot_id: {
		type: String,
	},
	sms_text: {
		type: String,
	},
	sms_type: {
		flash: {
			type: Boolean,
		},
		main: {
			type: String,
		},
		multipart: {
			type: Boolean,
		},
		personalized: {
			type: Boolean,
		},
		unicode: {
			type: Boolean,
		},
	},
	sms_type_alias: {
		type: String,
	},
	smsc: {
		type: String,
	},
	smsc_data: {
		host: {
			type: String,
		},
		port: {
			type: Number,
		},
		system_id: {
			type: String,
		},
	},
	submit_platform: {
		browser: {
			type: String,
		},
		city: {
			type: String,
		},
		country: {
			type: String,
		},
		ip: {
			type: String,
		},
		location: {
			lat: { type: Schema.Types.Decimal128 },
			lon: { type: Schema.Types.Decimal128 },
		},
		system: {
			type: String,
		},
	},
	submit_time: {
		type: Date,
	},
	tlv_data: {
		type: Object,
	},
	upline_user_id: {
		type: Number,
	},
	user_alias: {
		type: String,
	},
	user_id: {
		type: Number,
	},
});

const SentSms = mongoose.model("sent_sms", sentSmsSchema);
export default SentSms;
