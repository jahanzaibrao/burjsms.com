import { env, require } from "./miscHelper.js";
import SentSms from "./models/sent_sms.model.js";
import mongoose from "mongoose";

//mongo driver initialization
const mongoUri = env.MONGO_URI;
mongoose.connect(mongoUri, {
	useNewUrlParser: true,
});
const connection = mongoose.connection;
connection.once("open", () => {
	console.log(`Mongo DB connection established...`);
});

const importDataInArchive = async (data, mode = "sms") => {
	if (mode == "sms") {
		//prepare the sms object
		let preparedData = [],
			elasticIDs = [];
		data.forEach(smsobject => {
			let preparedObject = smsobject._source;
			preparedObject.es_id = smsobject._id;
			preparedData.push(preparedObject);
			elasticIDs.push(smsobject._id);
		});
		//initialize model
		try {
			await SentSms.insertMany(preparedData);
			console.log(`${preparedData.length} SMS records saved in Mongo archive..`);
			return elasticIDs;
		} catch (error) {
			console.log(`MongoDB error while archiving..`, error);
		}
		//save
	}
};

const fetchArchiveRecords = async req => {
	try {
		let from = new Date(req.from).toISOString();
		let to = new Date(req.to).toISOString();
		let user = parseInt(req.user);
		let records = await SentSms.find({
			user_id: user,
			submit_time: {
				$gte: from,
				$lt: to,
			},
		});
		return records;
	} catch (error) {
		console.log(`Archive fetch caused an error for user ID ${req.user}..`);
		console.log(error);
		return;
	}
};

const verifyMongoSave = (esCount, idList) =>
	Promise.resolve().then(async v => {
		let mongoCount = await SentSms.count({ es_id: { $in: idList } });
		if (esCount == mongoCount) {
			return true;
		} else {
			console.log(`Archive save verification failed. ES=${esCount} MG=${mongoCount}`);
			return false;
		}
	});

const testfn = async () => {
	return SentSms.count({ es_id: { $in: ["sGAnqHsBFvZ3MixcmGtx", "r2AnqHsBFvZ3MixcmGtx"] } });
};

const _importDataInArchive = importDataInArchive;
export { _importDataInArchive as importDataInArchive };
const _testfn = testfn;
export { _testfn as testfn };
const _verifyMongoSave = verifyMongoSave;
export { _verifyMongoSave as verifyMongoSave };
const _fetchArchiveRecords = fetchArchiveRecords;
export { _fetchArchiveRecords as fetchArchiveRecords };
