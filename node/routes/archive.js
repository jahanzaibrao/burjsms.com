"use strict";
import { env, require } from "../miscHelper.js";
const getArchiveRouter = require("express").Router();
import { fetchArchiveRecords } from "../mongoHelper.js";

import { response } from "express";

//api end point
getArchiveRouter.route("/fetch").post(async (req, res) => {
	try {
		let records = await fetchArchiveRecords(req.body);

		res.json(records);
	} catch (error) {
		console.log("Archive fetch by Smppcube encountered an error...");
		console.log(error);
		res.json({ error: "error" });
	}
});

export default getArchiveRouter;
