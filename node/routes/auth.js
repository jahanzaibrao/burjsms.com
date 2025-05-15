"use strict";
import { env, require } from "../miscHelper.js";
const authRouter = require("express").Router();
import { client } from "../nodeAsyncRedis.js";
const jwt = require("jsonwebtoken");

authRouter.route("/login").post(async (req, res) => {
	let userData = req.body;
	//create a jwt token for this as user already authenticated in php app
	let token = jwt.sign(userData, env.JWT_SECRET, { expiresIn: "24h" });
	//store this token into redis
	client.HSET("search_jwt_tokens", token, new Date());
	//return the token to php app
	res.json({
		token,
	});
});
authRouter.route("/logout").post(async (req, res) => {
	//remove from redis
	if (req.body.token) {
		client.HDEL("search_jwt_tokens", req.body.token);
	}
	//return
	res.json({
		message: "done",
	});
});

export default authRouter;
