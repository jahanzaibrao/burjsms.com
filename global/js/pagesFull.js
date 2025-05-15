/**
SMPPCUBE Official PAGES JS functions file
Please DO NOT remove or modify any code unless you really know what you're doing.
**/

//-------------- FUNCTIONS LIST ----------------//

//-------------- System Monitor Stats ----------------//

//-------------- Search function ----------------//
function readAllSearchInput() {
	//read all values from the page
	let date_range = "";
	if ($("#datetime").val() != "") {
		let parts = $("#datetime").val().split(" - ");
		let from_object = new Date(parts[0]);
		let to_object = new Date(parts[1]);
		date_range = `${from_object.getTime()} - ${to_object.getTime()}`;
	}
	return {
		user_id: $("#userpicker").val(),
		date_filter: date_range,
		msisdn: $("#msisdn").val(),
		route_id: $("#s_route").val(),
		smsc: $("#s_smpp").val(),
		user_alias: $("#s_smppclient").val(),
		country_iso: $("#s_country").val(),
		operator: $("#s_operator").val(),
		channel: $("#s_channel").val(),
		sms_id: $("#s_smsid").val(),
		sender_id: $("#s_senderid").val(),
		sms_type_alias: $("#s_smstype").val(),
		sms_count: $("#s_smsparts").val(),
		dlr_type: $("#s_fakedlr").val(),
		smpp_dlr: $("#s_smppdlr").val(),
		kannel_dlr: $("#s_appdlr").val(),
		operator_dlr: $("#s_vendordlr").val(),
		refund_flag: $("#s_refund").val(),
		sms_text: $("#s_smstext").val(),
	};
}

function prepareSearchRows(data) {
	// Pre-compile regular expressions
	const singleQuoteRegex = /(['])/g;
	const newlineRegex = /\n/g;

	// Cache DOM queries
	const userPicker = document.getElementById("userpicker");
	const appUrl = app_url;

	// Prepare template strings
	const userPopupTemplate = (userData, userId, fullName) => `
			<div class="media-group-item">
					<div class="media">
							<div class="media-left">
									<div class="avatar avatar-xlg avatar-circle">
											<a href="${appUrl}viewUserAccount/${userId}" target="_blank">
													<img src="${userData[0]}" alt="User Img">
											</a>
									</div>
							</div>
							<div class="media-body p-t-xs">
									<h5 class="m-t-0 m-b-0">
											<a href="${appUrl}viewUserAccount/${userId}" target="_blank" class="m-r-xs theme-color">${fullName}</a>
									</h5>
									<p class="m-b-0" style="font-size: 12px;font-style: Italic;"><i class="fa fa-phone m-r-xs"></i>${userData[3]}</p>
									<p class="m-b-xs" style="font-size: 12px;font-style: Italic;"><i class="fa fa-envelope m-r-xs"></i>${userData[1]}</p>
									<span class="m-b-sm label label-info label-sm">${userData[2].toUpperCase()} Account</span>
							</div>
					</div>
			</div>
	`;

	const smscPopupTemplate = (smppText, host, port, systemId) => `
			<table class="table">
					<tbody>
							<tr><td>SMPP</td><td>${smppText}</td></tr>
							<tr><td>Host</td><td><code class="label label-primary label-md">${host}</code></td></tr>
							<tr><td>PORT</td><td><code class="label label-primary label-md">${port}</code></td></tr>
							<tr><td>SYSTEM-ID</td><td><code class="label label-primary label-md">${systemId}</code></td></tr>
					</tbody>
			</table>
	`;

	// Use a string builder for better performance with large datasets
	let rows = [];

	for (const esobj of data) {
		const smsitem = esobj._source;
		const send_ts = new Date(smsitem.submit_time);
		const send_ts_human = tsToDate(send_ts);

		// User string
		let userstr = smsitem.user_alias;
		if (userPicker) {
			const userelement = userPicker.querySelector(`option[value="${smsitem.user_id}"]`);
			if (userelement && userelement.getAttribute("label")) {
				const userdata = userelement.getAttribute("label").split("|");
				const userpopup = userPopupTemplate(userdata, smsitem.user_id, userelement.getAttribute("data-fullname"));
				userstr = `<a href="javascript:void(0);" data-placement="top" data-content='${userpopup.replace(
					singleQuoteRegex,
					"\\$1"
				)}' class="pop-over">${smsitem.user_alias}</a>`;
			}
		}

		// Route
		const route_title = document.querySelector(`a.routeslist[data-myvalue="${smsitem.route_id}"]`)?.textContent || "";

		// SMSC
		let smscstr = "";
		if (smsitem.smsc !== undefined) {
			const smppText = document.querySelector(`a.smpplist[data-myvalue="${smsitem.smsc}"]`)?.textContent || "";
			const smscpopup = smscPopupTemplate(smppText, smsitem.smsc_data.host, smsitem.smsc_data.port, smsitem.smsc_data.system_id);
			smscstr = `<a href="javascript:void(0);" data-placement="top" data-content='${smscpopup.replace(singleQuoteRegex, "\\$1")}' class="pop-over">${
				smsitem.smsc
			}</a>`;
		}

		// Country
		const countrystr = smsitem.country.iso
			? `<div><img class="m-r-xs" style="width:18px; vertical-align:bottom;" src="${appUrl}global/img/flags/${smsitem.country.iso.toLowerCase()}.png"><span style="vertical-align: baseline;" class="">${
					smsitem.country.iso
			  }</span></div>`
			: "-";

		// Operator
		const operatorstr =
			smsitem.operator.title && smsitem.operator.mccmnc !== 0
				? `<a href="javascript:void(0);" data-placement="top" data-content='<table class="table"><tbody><tr><td>Brand</td><td>${
						smsitem.operator.title
				  }</td></tr><tr><td>Region</td><td>${smsitem.operator.region}</td></tr><tr><td>MCCMNCC</td><td><kbd>${smsitem.operator.mcc}${
						smsitem.operator.mnc
				  }</kbd></td></tr></tbody></table>' class="pop-over">${
						smsitem.operator.title.length > 14 ? `${smsitem.operator.title.substring(0, 10)}..` : smsitem.operator.title
				  }</a>`
				: "-";

		// Cost
		const costpopup = `<table class="table"><tbody><tr><td>Price per SMS</td><td>${app_currency.trim()}${
			smsitem.price
		}</td></tr><tr><td>SMS Cost</td><td>${app_currency.trim()}${smsitem.cost}</td></tr><tr><td>Refund</td><td>${
			smsitem.refunded ? "Yes, credits refunded." : "Not Applicable"
		}</td></tr></tbody></table>`;
		const coststr = `<a href="javascript:void(0);" data-content='${costpopup.replace(
			singleQuoteRegex,
			"\\$1"
		)}' class="pop-over code text-danger fz-sm">${app_currency.trim()}${smsitem.cost}</a>`;

		// DLR time
		const dlr_ts_human = smsitem.dlr.time && !isNaN(smsitem.dlr.time) ? tsToDate(new Date(smsitem.dlr.time)) : "-";

		// SMPP response
		const smppclass =
			smsitem.dlr.smpp_response === "DELIVRD"
				? "success"
				: smsitem.dlr.smpp_response === "ACCEPTD" || smsitem.dlr.smpp_response === "ENROUTE"
				? "primary"
				: "danger";
		const smppstr = `<kbd class="bg-${smppclass} fz-sm">${smsitem.dlr.smpp_response}</kbd>`;
		const vdlrstr = smsitem.dlr.operator_code ? `<kbd>${smsitem.dlr.operator_code}</kbd>` : "-";

		// SMS text
		const msgstr =
			smsitem.sms_text.length < 70
				? smsitem.sms_text.replace(newlineRegex, " ")
				: `${smsitem.sms_text
						.substring(0, 65)
						.replace(
							newlineRegex,
							"<br />"
						)}... <a href="javascript:void(0);" class="pop-over" data-placement="left" data-content='<div class="panel panel-custom panel-primary">${smsitem.sms_text.replace(
						newlineRegex,
						"<br>"
				  )}</div>'>more</a>`;

		// SMS type
		let smstypestr = `<i title="GSM/ASCII Text" class="zmdi zmdi-spellcheck fz-md text-primary"></i>`;
		if (smsitem.sms_type.main === "text") {
			if (smsitem.sms_type.unicode) {
				smstypestr = `<i title="UTF-8/UTF-16 Unicode" class="fas fa-language fz-lg text-primary"></i>`;
			}
			if (smsitem.sms_type.flash) {
				smstypestr = `<span title="Text Content with Flash"><i class="zmdi zmdi-spellcheck fz-md m-r-xs text-primary"></i><i class="fas fa-bolt text-primary fz-md"></i></span>`;
			}
			if (smsitem.sms_type.unicode && smsitem.sms_type.flash) {
				smstypestr = `<span title="Unicode Content with Flash"><i class="fas fa-language text-primary fz-lg m-r-xs"></i><i class="fas fz-md fa-bolt text-primary"></i></span>`;
			}
		} else if (smsitem.sms_type.main === "wap") {
			smstypestr = "WAP";
		} else if (smsitem.sms_type.main === "vcard") {
			smstypestr = "vCARD";
		}

		rows.push(`<tr>
					<td>${send_ts_human}</td>
					<td class="code ${smsitem.dlr.type === undefined ? "" : smsitem.dlr.type == "fakedlr" ? "" : ""}">${smsitem.msisdn}</td>
					<td class="text-center" style="padding: 0 !important; vertical-align: middle;">${smstypestr}</td>
					<td>${smsitem.sender_id}</td>
					<td>${userstr}</td>
					<td>${route_title}</td>
					${smsitem.smsc === undefined ? "" : `<td>${smscstr}</td>`}
					<td>${smsitem.channel}</td>
					<td>${countrystr}</td>
					<td>${operatorstr}</td>
					<td>${smsitem.sms_parts}</td>
					<td>${coststr}</td>
					<td>${smsitem.dlr.app_status}</td>
					<td>${dlr_ts_human}</td>
					<td>${smppstr}</td>
					<td>${vdlrstr}</td>
					<td class="code">${smsitem.sms_id}</td>
					<td>${msgstr}</td>
			</tr>`);
	}

	return rows.length ? rows.join("") : `<tr><td colspan="18" class="text-center">- No Matching Data -</td></tr>`;
}

function getSearchResult() {
	$("#filter_search").attr("disabled", "disabled").css("cursor", "progress");
	$("body").css("cursor", "progress");
	let searchData = readAllSearchInput();
	//send all values to express
	let url = `${app_url}hypernode/search/sms`;
	$.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		contentType: "application/json",
		beforeSend: function (xhr) {
			//Include the bearer token in header
			xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		},
		data: JSON.stringify(searchData),
		crossDomain: true,
		headers: {
			accept: "application/json",
			"Access-Control-Allow-Origin": "*",
		},
		success: function (res) {
			//prepare rows
			let rows = prepareSearchRows(res.rows);
			//get stats
			let totalsms = res.total;
			let dailyavg = Math.ceil(res.aggs.daily_average.value);
			let delivered_sms = 0,
				delivered_rate = 0,
				failed_sms = 0,
				failed_rate = 0;
			let delivered_stats = res.aggs.dlr_stats.buckets.find((e) => e.key == "1");
			let failed_stats = res.aggs.dlr_stats.buckets.find((e) => e.key == "2");
			delivered_sms = delivered_stats == undefined ? 0 : delivered_stats.doc_count;
			delivered_rate = Math.ceil((delivered_sms / totalsms) * 100) || 0;
			failed_sms = failed_stats == undefined ? 0 : failed_stats.doc_count;
			failed_rate = Math.ceil((failed_sms / totalsms) * 100) || 0;
			let totalcost = res.aggs.total_cost.value;
			let totalcredits = res.aggs.total_credits.value;
			let refund_stats = res.aggs.refund_stats.buckets.find((e) => e.key_as_string == "true");
			let refundcost = refund_stats == undefined ? 0 : refund_stats.cost.value;
			let refundcredits = refund_stats == undefined ? 0 : refund_stats.credits.value;

			//show the response
			$("#sr_total").html(totalsms.toLocaleString());
			$("#sr_average").html(dailyavg.toLocaleString());
			$("#sr_del").html(delivered_sms.toLocaleString());
			$("#sr_del_per").html(`${delivered_rate}%`);
			$("#sr_fail").html(failed_sms.toLocaleString());
			$("#sr_fail_per").html(`${failed_rate}%`);
			$("#sr_cost").html(totalcost.toLocaleString());
			$("#sr_refund_cost").html(refundcost.toLocaleString());
			$("#sr_credits").html(`${totalcredits.toLocaleString()} credits`);
			$("#sr_refund_credits").html(`${refundcredits.toLocaleString()} credits`);

			$("#smsdata tbody").html(rows);
			$(".pop-over").popover({ html: true });
			$("i[title]").each(function () {
				if ($(this).hasClass("pop-over") == false) {
					let title = $(this).attr("title");
					$(this).tooltip({ title: title });
				}
			});
			$("#filter_search").attr("disabled", false).css("cursor", "default");
			$("body").css("cursor", "default");
		},
		error: function (err) {
			console.log(err);
		},
	});
}

function downloadSmsLog() {
	//v9 updated code for download millions of records
	var dialog = bootbox.dialog({
		message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
			"Preparing Download. . . ."
		)}</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
		closeButton: false,
	});
	dialog.init(function () {
		$("#stbar").animate({ width: "100%" }, { duration: 3000 });
	});
	let searchData = readAllSearchInput();
	let routetitles = [];
	$(`a.routeslist`).each(function () {
		if (parseInt($(this).attr("data-myvalue"))) {
			routetitles[parseInt($(this).attr("data-myvalue"))] = $(this).text();
		}
	});
	searchData["routeTitles"] = routetitles;
	let url = `${app_url}hypernode/search/download`;
	makeRequest(url, authToken, searchData)
		.then((res) => {
			console.log(res);
		})
		.catch((err) => {
			console.log(err);
			bootbox.hideAll();
			bootbox.alert(
				`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Error while downloading. Please try again</div>`
			);
		});
}

function makeRequest(url, authToken, searchData) {
	return new Promise((resolve, reject) => {
		const xhr = new XMLHttpRequest();
		xhr.open("POST", url, true);

		// Set headers
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		xhr.setRequestHeader("Accept", "application/json");

		// Set responseType to 'blob' to handle both JSON and file downloads
		xhr.responseType = "blob";

		xhr.onload = function () {
			if (xhr.status >= 200 && xhr.status < 300) {
				const contentType = xhr.getResponseHeader("Content-Type");

				if (contentType.includes("application/json")) {
					// If the response is JSON, convert the Blob to text and then parse it
					const reader = new FileReader();
					reader.onload = function () {
						try {
							const jsonResponse = JSON.parse(reader.result);
							console.log(jsonResponse);
							if (jsonResponse.message) {
								bootbox.hideAll();
								bootbox.alert(jsonResponse.message);
							}
							resolve(jsonResponse);
						} catch (error) {
							reject(new Error("Failed to parse JSON response"));
						}
					};
					reader.onerror = function () {
						reject(new Error("Failed to read response as text"));
					};
					reader.readAsText(xhr.response);
				} else {
					// If the response is a Blob (e.g., for file download)
					const blob = xhr.response;
					const link = document.createElement("a");
					link.href = window.URL.createObjectURL(blob);
					link.download = `sms_log_${Date.now()}.csv`; // Specify the filename
					document.body.appendChild(link);
					link.click();
					document.body.removeChild(link);
					resolve(blob);
					bootbox.hideAll();
				}
			} else {
				reject(new Error(`HTTP error! status: ${xhr.status}`));
			}
		};

		xhr.onerror = function () {
			reject(new Error("Network error occurred"));
		};

		// Send the request
		xhr.send(JSON.stringify(searchData));
	});
}

function getSearchDownload() {
	var dialog = bootbox.dialog({
		message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
			"Preparing data for download. . . ."
		)}</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
		closeButton: false,
	});
	dialog.init(function () {
		$("#stbar").animate({ width: "100%" }, { duration: 3000 });
	});
	let searchData = readAllSearchInput();
	searchData.mode = "download";
	let url = `${app_url}hypernode/search/sms`;
	$.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		contentType: "application/json",
		beforeSend: function (xhr) {
			//Include the bearer token in header
			xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		},
		data: JSON.stringify(searchData),
		crossDomain: true,
		headers: {
			accept: "application/json",
			"Access-Control-Allow-Origin": "*",
		},
		success: function (res) {
			//prepare rows
			if (res.rows.length == 0) {
				dialog.modal("hide");
				bootbox.dialog({
					message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
						"No data to download. . . ."
					)}</p>`,
					closeButton: true,
				});
				return;
			}
			var successdialog = bootbox.dialog({
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"File prepared. Downloading. . . ."
				)}</p><div class="progress progress-xs"><div id="stbar2" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
				closeButton: false,
			});
			successdialog.init(function () {
				$("#stbar2").animate({ width: "100%" }, { duration: 5000 });
			});
			prepareSearchDownload(res.rows).then((csvFile) => {
				let now = new Date();
				let filetitle = `REPORTS-${now.toLocaleString().replaceAll(" ", "").replace(",", "-")}.csv`;
				let blob = new Blob([csvFile], {
					type: "text/csv;charset=utf-8;",
				});
				if (navigator.msSaveBlob) {
					// IE 10+
					navigator.msSaveBlob(blob, filetitle);
				} else {
					let link = document.createElement("a");
					if (link.download !== undefined) {
						saveBlob(blob, filetitle);
					}
				}
				$("#filter_search").attr("disabled", false).css("cursor", "default");
				$("body").css("cursor", "default");
				bootbox.hideAll();
			});
		},
		error: function (err) {
			console.log(err);
		},
	});
}

function saveBlob(blob, fileName) {
	let a = document.createElement("a");
	document.body.appendChild(a);
	a.style = "display: none";

	let url = window.URL.createObjectURL(blob);
	a.href = url;
	a.download = fileName;
	a.click();
	window.URL.revokeObjectURL(url);
}

async function prepareSearchDownload(rows) {
	//doing it here was crashing the browser for 100k rows hence performing it on server side
	//collect route titles
	let routetitles = [];
	$(`a.routeslist`).each(function () {
		if (parseInt($(this).attr("data-myvalue"))) {
			routetitles[parseInt($(this).attr("data-myvalue"))] = $(this).text();
		}
	});
	let url = `${app_url}hypernode/search/download`;
	let parsedItems = await $.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		contentType: "application/json",
		beforeSend: function (xhr) {
			//Include the bearer token in header
			xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		},
		data: JSON.stringify({
			routeTitles: routetitles,
			rows: rows,
		}),
		crossDomain: true,
		headers: {
			accept: "application/json; charset=UTF-8",
			"Access-Control-Allow-Origin": "*",
		},
	});

	return parsedItems.parsedRows;
}

//---------- end of search functions -----------//

//---------- stats function ------------//

function readAllFilterInputs() {
	let date_range = "";
	if ($("#datetime").val() != "") {
		let parts = $("#datetime").val().split(" - ");
		let from_object = new Date(parts[0]);
		let to_object = new Date(parts[1]);
		date_range = `${from_object.getTime()} - ${to_object.getTime()}`;
	}
	return {
		user_id: $("#userpicker").val(),
		date_filter: date_range,
		route_id: $("#f_route").val(),
		smsc: $("#f_smpp").val(),
		user_alias: $("#f_smpp_client").val(),
		sender_id: $("#f_senderid").val(),
	};
}

function showSmsStats() {
	$("#apply_filter").attr("disabled", "disabled").css("cursor", "progress");
	$("body").css("cursor", "progress");
	let summary_chart = echarts.init(document.getElementById("s_traffic_summary"), "macarons");
	let channels_pie = echarts.init(document.getElementById("s_channels"));
	let smstype_pie = echarts.init(document.getElementById("s_smstypes"), "macarons");
	let networks_map = echarts.init(document.getElementById("s_networks"), "macarons");
	let filterdata = readAllFilterInputs();
	//send data to get stats
	let url = `${app_url}hypernode/search/stats`;
	$.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		contentType: "application/json",
		beforeSend: function (xhr) {
			//Include the bearer token in header
			xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		},
		data: JSON.stringify(filterdata),
		crossDomain: true,
		headers: {
			accept: "application/json",
			"Access-Control-Allow-Origin": "*",
		},
		success: function (response) {
			let res = response.statsData;
			//traffic summary
			let summary_option = {
				toolbox: {
					show: true,
					orient: "horizontal",
					left: "right",
					top: "top",
					feature: {
						saveAsImage: {
							show: true,
							name: "sms_traffic_summary",
							title: "Download",
						},
					},
				},
				textStyle: {
					fontFamily: `Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif`,
				},
				title: {
					text: "SMS Traffic",
					textStyle: {
						fontWeight: "normal",
					},
				},
				grid: {
					left: 40,
					top: 40,
					right: 0,
					bottom: 20,
				},
				tooltip: {
					trigger: "axis",
					axisPointer: {
						type: "shadow",
					},
					hideDelay: 10,
					confine: true,
					formatter: function (params) {
						let colorSpan = (color) =>
							`<span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:${color}"></span>`;
						let title = `<p>${params[0].axisValue}</p>`,
							pane = "";
						let total = 0;
						params.forEach((item) => {
							let data = parseInt(item.data) || 0;
							let line = `<p class="fz-sm m-b-0 clearfix"><span class="pull-left m-r-sm">${colorSpan(item.color)} ${
								item.seriesName
							}</span> <span class="pull-right"><b>${data.toLocaleString()}</b></span></p>`;
							pane += line;
							total += data;
						});
						let totalStr = `<p class="fz-sm m-b-0 clearfix"><span class="pull-left"><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#000"></span> Total</span> <span class="pull-right"><b>${total.toLocaleString()}</b></span></p>`;
						return `${title}${totalStr}${pane}`;
					},
				},
				legend: {
					data: ["Delivered", "Failed", "NDNC", "Invalid", "SMSC Submit", "Other"],
				},
				xAxis: {
					type: "category",
					data: res.traffic_summary.dates,
					axisTicks: {
						show: true,
						alignWithLabel: true,
					},
				},
				yAxis: {
					type: "value",
					axisLabel: {
						formatter: function (num) {
							return Math.abs(num) > 999999
								? Math.sign(num) * (Math.abs(num) / 1000000).toFixed(1) + "M"
								: Math.abs(num) > 999
								? Math.sign(num) * (Math.abs(num) / 1000).toFixed(1) + "k"
								: Math.sign(num) * Math.abs(num);
						},
					},
				},
				series: [
					{
						name: "Delivered",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: res.traffic_summary.delivered,
					},
					{
						name: "Failed",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: res.traffic_summary.failed,
					},
					{
						name: "NDNC",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: res.traffic_summary.ndnc,
					},
					{
						name: "Invalid",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: res.traffic_summary.invalid,
					},
					{
						name: "SMSC Submit",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: res.traffic_summary.smsc_submit,
					},
					{
						name: "Other",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: res.traffic_summary.other,
					},
				],
			};
			summary_chart.setOption(summary_option);
			//channels pie
			let channels_options = {
				legend: {
					orient: "vertical",
					left: 10,
					data: ["SMPP", "Panel", "API"],
				},
				textStyle: {
					fontFamily: `Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif`,
				},
				title: {},
				tooltip: {
					trigger: "item",
					formatter: `{b} <br/>{c} <b class="m-l-xs">{d}%</b>`,
				},
				grid: {
					left: 0,
					top: 0,
					right: 0,
					bottom: 0,
				},
				series: [
					{
						name: "Channels",
						type: "pie",
						radius: ["20%", "70%"],
						data: res.channels,
						avoidLabelOverlap: false,
						label: { show: false },
						labelLine: {
							show: false,
						},
					},
				],
			};
			channels_pie.setOption(channels_options);
			//dlr dummary table
			let tablerows = `<tr> <td><span class="label label-primary fz-sm">All Messages</span> </td><td><span class="code">${res.dlr_summary.total.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.total.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.total.cost.toLocaleString()}</span> </td><td><span class="code">-</span> </td></tr><tr> <td><span class="label label-success fz-sm">Delivered</span> </td><td><span class="code">${res.dlr_summary.delivered.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.delivered.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.delivered.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.delivered.rate
			}</span> </td></tr><tr> <td><span class="label label-danger fz-sm">Failed</span> </td><td><span class="code">${res.dlr_summary.failed.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.failed.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.failed.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.failed.rate
			}</span> </td></tr><tr> <td><span class="label label-warning fz-sm">NDNC</span> </td><td><span class="code">${res.dlr_summary.ndnc.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.ndnc.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.ndnc.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.ndnc.rate
			}</span> </td></tr><tr> <td><span class="label label-pink fz-sm">Invalid</span> </td><td><span class="code">${res.dlr_summary.invalid.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.invalid.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.invalid.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.invalid.rate
			}</span> </td></tr><tr> <td><span class="label label-purple fz-sm">SMSC Submit</span> </td><td><span class="code">${res.dlr_summary.smsc_submit.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.smsc_submit.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.smsc_submit.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.smsc_submit.rate
			}</span> </td></tr><tr> <td><span class="label label-inverse fz-sm">Others</span> </td><td><span class="code">${res.dlr_summary.other.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.other.credits.toLocaleString()}</span> </td><td><span class="code text-danger">${res.dlr_summary.other.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.other.rate
			}</span> </td></tr><tr> <td><span class="label label-success fz-sm">Refunded</span> </td><td><span class="code">${res.dlr_summary.refunds.sms.toLocaleString()}</span> </td><td><span class="code">${res.dlr_summary.refunds.credits.toLocaleString()}</span> </td><td><span class="code text-success">${res.dlr_summary.refunds.cost.toLocaleString()}</span> </td><td><span class="code">${
				res.dlr_summary.refunds.rate
			}</span> </td></tr>`;
			$("#dlrsummary tbody").html(tablerows);
			//smstype chart
			let smstype_pie_options = {
				legend: {
					orient: "horizontal",
					top: 0,
					data: ["GSM/ASCII", "Unicode", "FLASH", "UnicodeFlash", "WAP", "vCard"],
					textStyle: {
						fontSize: 10,
					},
					padding: [0, 5],
				},
				textStyle: {
					fontFamily: `Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif`,
				},
				title: {},
				tooltip: {
					trigger: "item",
					formatter: `{b} <br/>{c} <b class="m-l-xs">{d}%</b>`,
				},
				series: [
					{
						name: "SMS Types",
						type: "pie",
						radius: "100%",
						data: res.sms_types,
						label: { show: false },
						labelLine: {
							show: false,
						},
						top: 50,
					},
				],
			};
			smstype_pie.setOption(smstype_pie_options);
			//popular networks
			//popular networks
			let networks_map_options = {
				textStyle: {
					fontFamily: `Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif`,
				},
				tooltip: {
					trigger: "item",
					formatter: function (param) {
						return `${param.name}: <br><b>${param.value.toLocaleString()} SMS</b>`;
					},
				},
				series: [
					{
						type: "treemap",
						label: { position: [10, 10] },
						height: "100%",
						width: "100%",
						breadcrumb: { show: false },
						nodeClick: false,
						roam: false,
						data: res.networks,
					},
				],
			};
			networks_map.setOption(networks_map_options);
			//top countries
			let ct_row = "";
			for (const ct of res.countries) {
				let countryname = getCountryName(ct.iso);
				let countryflag =
					ct.iso == "Unknown"
						? `<i class="fas fa-question fa-lg text-pink"></i>`
						: `<img style="height: 15px;" src="${app_url}global/img/flags/${ct.iso.toLowerCase()}.png"/>`;
				ct_row += `<a href="javascript:void(0);" class="list-group-item p-sm clearfix"> <span class="pull-left"> ${countryflag} </span> <span class="pull-left m-l-xs fw-500" style="margin-top: 3px;">${countryname}</span> <div class="pull-right"> <span data-plugin="counterUp">${ct.total.toLocaleString()}</span> SMS </div></a>`;
			}
			if (ct_row == "") {
				$("#top_countries").html("<h5>- Not Enough Data -</h5>");
			} else {
				$("#top_countries").html(ct_row);
			}
			//
			$("#apply_filter").attr("disabled", false).css("cursor", "default");
			$("body").css("cursor", "default");
		},
		error: function (err) {
			console.log(err);
		},
	});

	//dlr summary shown as table
	$("#print_dlr_summary").on("click", function () {
		$("#dlrsummary").printThis({
			importStyle: true,
			pageTitle: "DLR Summary",
			copyTagClasses: true,
			pageTitle: "DLR Summary",
		});
	});
}

//---------- end of stats function -------//

//---------- dashboard Mini stats ---------//

function readDashboardFilters(params) {
	let filters = {};
	if (params.page === "admin") {
		let roc_parts = $("#roc_dp span").html() === "" ? [] : $("#roc_dp span").html().split(" - ");
		let roc_fts = new Date(roc_parts[0]);
		let roc_uts = new Date(roc_parts[1]);
		let roc_ts_range = `${roc_fts.getTime()} - ${roc_uts.getTime()}`;
		let top_clients_parts = $("#topcldp span").html() === "" ? [] : $("#topcldp span").html().split(" - ");
		let top_clients_fts = new Date(top_clients_parts[0]);
		let top_clients_uts = new Date(top_clients_parts[1]);
		let top_clients_ts_range = `${top_clients_fts.getTime()} - ${top_clients_uts.getTime()}`;
		filters = {
			mode: params.mode,
			roc_mode: $("#dash_roc").val() || "",
			roc_date_range: roc_ts_range,
			top_users_date_range: top_clients_ts_range,
		};
	}
	if (params.page === "reseller") {
		let top_clients_parts = $("#topcldp span").html() === "" ? [] : $("#topcldp span").html().split(" - ");
		let top_clients_fts = new Date(top_clients_parts[0]);
		let top_clients_uts = new Date(top_clients_parts[1]);
		let top_clients_ts_range = `${top_clients_fts.getTime()} - ${top_clients_uts.getTime()}`;
		filters = {
			mode: params.mode,
			top_users_date_range: top_clients_ts_range,
		};
	}
	if (params.page === "client") {
		let client_rt_parts = $("#clientrtdp span").html() === "" ? [] : $("#clientrtdp span").html().split(" - ");
		let client_rt_fts = new Date(client_rt_parts[0]);
		let client_rt_uts = new Date(client_rt_parts[1]);
		let client_rt_ts_range = `${client_rt_fts.getTime()} - ${client_rt_uts.getTime()}`;
		filters = {
			mode: params.mode,
			client_routes_date_range: client_rt_ts_range,
		};
	}
	return filters;
}

function loadDashboardStats(params) {
	let pageFilters = readDashboardFilters(params);
	//send data to get stats
	let url = `${app_url}hypernode/search/ministats`;
	$.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		contentType: "application/json",
		beforeSend: function (xhr) {
			//Include the bearer token in header
			xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		},
		data: JSON.stringify(pageFilters),
		crossDomain: true,
		headers: {
			accept: "application/json",
			"Access-Control-Allow-Origin": "*",
		},
		success: function (response) {
			let res = response.statsData;
			if (res.roc_data !== undefined) {
				//for admin load route summary
				let routeStr = "";
				for (const roc of res.roc_data) {
					let roc_title = "";
					if (pageFilters.roc_mode == "r") {
						let title_match = $(`#route_js_${roc.key}`).html();
						roc_title = title_match === undefined ? `<span class="label label-default fz-sm">Deleted</span>` : `<h5>${title_match}</h5>`;
					} else {
						let sdmatch = $(`#smpp_js_${roc.key}`).html();
						if (sdmatch !== undefined) {
							let smppdata = sdmatch.split("|");
							roc_title = `<div class="media-body"><h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs">${smppdata[0]}</a></h5><p style="font-size: 12px;">${smppdata[1]}</p></div>`;
						} else {
							roc_title = `<span class="label label-default fz-sm">Deleted</span>`;
						}
					}
					routeStr += `<tr> <td data-rid="${
						roc.key
					}" class="text-left">${roc_title}</td><td><span class="">${roc.total_sms.toLocaleString()}</span></td><td><span class="">${roc.total_delivered.toLocaleString()} <span class="block text-success">(${
						Math.ceil((roc.total_delivered / roc.total_sms) * 100) || 0
					}%)</span></span></td><td><span class="">${roc.total_failed.toLocaleString()} <span class="block text-danger">(${
						Math.ceil((roc.total_failed / roc.total_sms) * 100) || 0
					}%)</span></span></td><td><span class="">${roc.total_ndnc.toLocaleString()} <span class="block text-danger">(${
						Math.ceil((roc.total_ndnc / roc.total_sms) * 100) || 0
					}%)</span></span></td><td><span class="">${roc.total_ack.toLocaleString()} <span class="block text-primary">(${
						Math.ceil((roc.total_ack / roc.total_sms) * 100) || 0
					}%)</span></span></td><td><span class="">${roc.total_invalid.toLocaleString()} <span class="block text-pink">(${
						Math.ceil((roc.total_invalid / roc.total_sms) * 100) || 0
					}%)</span></span></td><td><span class="">${roc.total_refunds.toLocaleString()} <span class="block text-success">(${
						Math.ceil((roc.total_refunds / roc.total_sms) * 100) || 0
					}%)</span></span></td> </tr>`;
				}
				let roc_str = routeStr == "" ? '<tr><td colspan="8"> - Not Enough Data - </td></tr>' : routeStr;
				$("#roc_summary tbody").html(roc_str);
			}
			if (res.top_users !== undefined) {
				let userStr = "";
				for (const usr of res.top_users) {
					let usermatch = $(`#user_js_${usr.user_id}`).html();
					let userTitle = "";
					if (usermatch === undefined) {
						userTitle = `<div class="media col-md-6 col-sm-6 col-xs-6"> <div class="media-left"> <div class="avatar avatar-sm avatar-circle"><a href="javascript:void(0)"><i class="fa fa-user fa-3x text-warning"></i></a></div></div><div class="media-body"> <h5 class="m-t-0"><small class="text-muted fz-sm">Account Deleted</small></h5></div></div>`;
					} else {
						let usrdata = usermatch.split("|");
						userTitle = `<div class="media col-md-6 col-sm-6 col-xs-6"> <div class="media-left"> <div class="avatar avatar-sm avatar-circle"><a href="${app_url}viewUserAccount/${usr.user_id}"><img src="${usrdata[3]}" alt=""></a></div></div><div class="media-body"> <h5 class="m-t-0"><a href="${app_url}viewUserAccount/${usr.user_id}" class="m-r-xs theme-color">${usrdata[0]}</a><small class="text-muted fz-sm">${usrdata[1]}</small></h5> <p style="font-size: 12px;font-style: Italic;">${usrdata[2]}</p></div></div>`;
					}
					userStr += `<div class="media-group-item"> ${userTitle} <div class="text-right col-md-6 col-sm-6 col-xs-6"> <h5 class="m-t-0 label label-primary">${usr.total_sms.toLocaleString()}</h5> <p style="font-size: 12px;margin-top:3px;">SMS Sent</p></div><div class="clearfix"></div></div>`;
				}
				let topclients_str = userStr == "" ? '<div align="center">No recent consumers to show</div>' : userStr;
				$("#topclctr").html(topclients_str);
			}
			if (res.client_routes !== undefined) {
				let clr_str = "";
				for (const clr of res.client_routes) {
					let title_match = $(`#route_js_${clr.key}`).html();
					rt_title = title_match === undefined ? `<span class="label label-default fz-sm">Deleted</span>` : `<h5>${title_match}</h5>`;
					clr_str += `<div>${rt_title} </div>`;
					clr_str += `<div class="progress"> <div class="progress-bar progress-bar-success" style="width: ${
						Math.ceil((clr.total_delivered / clr.total_sms) * 100) || 0
					}%" data-placement="top" data-toggle="tooltip" title="${clr.total_delivered.toLocaleString()} SMS"> Delivered </div><div class="progress-bar progress-bar-danger" style="width: ${
						Math.ceil((clr.total_failed / clr.total_sms) * 100) || 0
					}%" data-toggle="tooltip" title="${clr.total_failed.toLocaleString()} SMS"> Failed </div><div class="progress-bar progress-bar-warning" style="width: ${
						Math.ceil((clr.total_ack / clr.total_sms) * 100) || 0
					}%" data-placement="top" data-toggle="tooltip" title="${clr.total_ack.toLocaleString()} SMS"> SMSC Submit </div></div>`;
				}
				let final_clr_str = clr_str == "" ? "<h5> - Not Enough Data - </h5>" : clr_str;
				$("#clientrtctr").html(final_clr_str);
				$('[data-toggle="tooltip"]').tooltip();
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
}
//---------- end of dashboard mini stats ------//

//---------- view acount page sms summary -------//
function readViewAccountDateFilter() {
	let va_smssummary_parts = $("#stdp span").html() === "" ? [] : $("#stdp span").html().split(" - ");
	let va_smssummary_fts = new Date(va_smssummary_parts[0]);
	let va_smssummary_uts = new Date(va_smssummary_parts[1]);
	let va_smssummary_ts_range = `${va_smssummary_fts.getTime()} - ${va_smssummary_uts.getTime()}`;
	return {
		mode: "viewaccountsummary",
		user_id: $("#userid").val(),
		viewaccount_sms_date_range: va_smssummary_ts_range,
	};
}
function loadViewAcountSmsSummary() {
	let summary_chart = echarts.init(document.getElementById("usersmssummary"), "macarons");
	let pageFilters = readViewAccountDateFilter();
	//send data to get stats
	let url = `${app_url}hypernode/search/ministats`;
	$.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		contentType: "application/json",
		beforeSend: function (xhr) {
			//Include the bearer token in header
			xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
		},
		data: JSON.stringify(pageFilters),
		crossDomain: true,
		headers: {
			accept: "application/json",
			"Access-Control-Allow-Origin": "*",
		},
		success: function (response) {
			let res = response.statsData;
			//traffic summary
			let summary_option = {
				toolbox: {
					show: true,
					orient: "horizontal",
					left: "right",
					top: "top",
					feature: {
						saveAsImage: {
							show: true,
							name: "sms_traffic_summary",
							title: "Download",
						},
					},
				},
				textStyle: {
					fontFamily: `Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif`,
				},
				title: {},
				grid: {
					left: 40,
					top: 40,
					right: 0,
					bottom: 20,
				},
				tooltip: {
					trigger: "axis",
					axisPointer: {
						type: "shadow",
					},
					hideDelay: 10,
					confine: true,
					formatter: function (params) {
						let colorSpan = (color) =>
							`<span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:${color}"></span>`;
						let title = `<p>${params[0].axisValue}</p>`,
							pane = "";
						let total = 0;
						params.forEach((item) => {
							let data = parseInt(item.data) || 0;
							let line = `<p class="fz-sm m-b-0 clearfix"><span class="pull-left m-r-sm">${colorSpan(item.color)} ${
								item.seriesName
							}</span> <span class="pull-right"><b>${data.toLocaleString()}</b></span></p>`;
							pane += line;
							total += data;
						});
						let totalStr = `<p class="fz-sm m-b-0 clearfix"><span class="pull-left"><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#000"></span> Total</span> <span class="pull-right"><b>${total.toLocaleString()}</b></span></p>`;
						return `${title}${totalStr}${pane}`;
					},
				},
				legend: {
					data: ["Delivered", "Failed", "NDNC", "Invalid", "SMSC Submit", "Other"],
				},
				xAxis: {
					type: "category",
					data: res.va_traffic_summary ? res.va_traffic_summary.dates : [],
					axisTicks: {
						show: true,
						alignWithLabel: true,
					},
				},
				yAxis: {
					type: "value",
					axisLabel: {
						formatter: function (num) {
							return Math.abs(num) > 999999
								? Math.sign(num) * (Math.abs(num) / 1000000).toFixed(1) + "M"
								: Math.abs(num) > 999
								? Math.sign(num) * (Math.abs(num) / 1000).toFixed(1) + "k"
								: Math.sign(num) * Math.abs(num);
						},
					},
				},
				series: [
					{
						name: "Delivered",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: !res.va_traffic_summary ? 0 : res.va_traffic_summary.delivered,
					},
					{
						name: "Failed",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: !res.va_traffic_summary ? 0 : res.va_traffic_summary.failed,
					},
					{
						name: "NDNC",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: !res.va_traffic_summary ? 0 : res.va_traffic_summary.ndnc,
					},
					{
						name: "Invalid",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: !res.va_traffic_summary ? 0 : res.va_traffic_summary.invalid,
					},
					{
						name: "SMSC Submit",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: !res.va_traffic_summary ? 0 : res.va_traffic_summary.smsc_submit,
					},
					{
						name: "Other",
						type: "bar",
						stack: "summary",
						barWidth: "60%",
						data: !res.va_traffic_summary ? 0 : res.va_traffic_summary.other,
					},
				],
			};
			summary_chart.setOption(summary_option);
		},
		error: function (err) {
			console.log(err);
		},
	});
}

//----------  end of view acount page sms summary -------//

function getOrdinalNum(n) {
	return n + (n > 0 ? ["th", "st", "nd", "rd"][(n > 3 && n < 21) || n % 10 > 3 ? 0 : n % 10] : "");
}

function scBulkAction(action) {
	//delete prefixes
	if (action == "delpre") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one prefix to delete"));
			return;
		} else {
			var pids = [];
			$(".selected").each(function () {
				pids.push($(this).children().find(".pids").val());
			});
			var pstr = pids.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete selected prefixes?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "delManyOP",
							method: "post",
							data: {
								cid: $("#cid").val(),
								pids: pstr,
							},
							success: function (res) {
								window.location = app_url + "viewAllOP/" + $("#cid").val();
							},
						});
					}
				},
			});
		}
	}

	//delete contacts
	if (action == "delcontacts") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one contact to delete"));
			return;
		} else {
			var cids = [];
			$(".selected").each(function () {
				cids.push($(this).children().find(".cids").val());
			});
			var cstr = cids.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete selected contacts?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "delManyContacts",
							method: "post",
							data: {
								cids: cstr,
							},
							success: function (res) {
								window.location = app_url + "viewContacts/" + $("#groupid").val();
							},
						});
					}
				},
			});
		}
	}

	//approve or reject sender ids
	if (action == "apprSid") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one sender ID to approve"));
			return;
		} else {
			var sids = [];
			var usrs = [];
			$(".selected").each(function () {
				sids.push($(this).children().find(".sids").val());
				usrs.push($(this).children().find(".sids").attr("data-user"));
			});
			var sstr = sids.join(",");
			var ustr = usrs.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to Approve selected Sender ID?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//--
						$.ajax({
							url: app_url + "approveManySids",
							method: "post",
							data: {
								sids: sstr,
								users: ustr,
							},
							success: function (res) {
								window.location = app_url + "approveSenderIds";
							},
						});
					}
				},
			});
		}
	}
	if (action == "rejcSid") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one sender ID to reject"));
			return;
		} else {
			var sids = [];
			var usrs = [];
			$(".selected").each(function () {
				sids.push($(this).children().find(".sids").val());
				usrs.push($(this).children().find(".sids").attr("data-user"));
			});
			var sstr = sids.join(",");
			var ustr = usrs.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to Reject selected Sender ID? These will be deleted from the system"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//--
						$.ajax({
							url: app_url + "rejectManySids",
							method: "post",
							data: {
								sids: sstr,
								users: ustr,
							},
							success: function (res) {
								window.location = app_url + "approveSenderIds";
							},
						});
					}
				},
			});
		}
	}
	//approve or reject templates
	if (action == "apprTemp") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one template to approve"));
			return;
		} else {
			var tids = [];
			var usrs = [];
			$(".selected").each(function () {
				tids.push($(this).children().find(".tids").val());
				usrs.push($(this).children().find(".tids").attr("data-user"));
			});
			var tstr = tids.join(",");
			var ustr = usrs.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to Approve selected templates?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//--
						$.ajax({
							url: app_url + "approveManyTemps",
							method: "post",
							data: {
								tids: tstr,
								users: ustr,
							},
							success: function (res) {
								window.location = app_url + "approveTemplates";
							},
						});
					}
				},
			});
		}
	}
	if (action == "rejcTemp") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one template to reject"));
			return;
		} else {
			var tids = [];
			var usrs = [];
			$(".selected").each(function () {
				tids.push($(this).children().find(".tids").val());
				usrs.push($(this).children().find(".tids").attr("data-user"));
			});
			var tstr = tids.join(",");
			var ustr = usrs.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to Reject selected templates?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//--
						$.ajax({
							url: app_url + "rejectManyTemps",
							method: "post",
							data: {
								tids: tstr,
								users: ustr,
							},
							success: function (res) {
								window.location = app_url + "approveTemplates";
							},
						});
					}
				},
			});
		}
	}

	//read alerts
	if (action == "readAlerts") {
		if ($(".selected").length < 1) {
			bootbox.alert(SCTEXT("Please select at least one notification to mark it as READ"));
			return;
		} else {
			var nids = [];
			$(".selected").each(function () {
				nids.push($(this).children().find(".nids").val());
			});
			var nstr = nids.join(",");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to mark selected notifications as read?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "markAlertsRead",
							method: "post",
							data: {
								nids: nstr,
							},
							success: function (res) {
								window.location = app_url + "viewNotifications";
							},
						});
					}
				},
			});
		}
	}

	//-eof
}

function downloadUserSmsLog() {
	var searchstr = $("#t-usmslog_filter").find("input").val();
	var expdata = {
		filDate: $("#sldp span").html(),
		senderId: $("#sidsel").val(),
		routeId: $("#rtsel").val(),
		search: searchstr,
	};

	var dialog = bootbox.dialog({
		closeButton: false,
		message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
			"Preparing file.<br> Download will start in few minutes. This window will close automatically."
		)}</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
	});
	dialog.init(function () {
		$("#stbar").animate(
			{ width: "100%" },
			{
				duration: 10000,
				complete: function () {
					bootbox.hideAll();
				},
			}
		);
		setTimeout(function () {
			window.location = app_url + "globalFileDownload/smslog/" + encodeURIComponent(JSON.stringify(expdata));
		}, 5);
	});
}

function loadScText() {
	$.ajax({
		url: app_url + "getScText",
		success: function (res) {
			if (res == "en") {
				localStorage.clear();
				localStorage.setItem("app_lang", app_lang);
			} else {
				//populate js array
				let transtxt = [];
				transtxt = JSON.parse(res);
				for (let word in transtxt) {
					localStorage.setItem(word, transtxt[word]);
				}
				localStorage.setItem("app_lang", app_lang);
			}
		},
	});
}

function SCTEXT(str) {
	let lstr = str.toLowerCase();
	if (!localStorage.getItem(lstr) || localStorage.getItem(lstr) == "") {
		return str;
	} else {
		return localStorage.getItem(lstr);
		//return sctext[$str];
	}
}

function createInputFile(formId, inputval, mode = "") {
	if (mode == "sendsms") {
		//input val is an array
		let filenames = JSON.parse(inputval);
		inputval = filenames.newfile;
		var node = document.createElement("INPUT");
		var atype = document.createAttribute("type");
		atype.value = "hidden";
		var aname = document.createAttribute("name");
		aname.value = "uploadedFiles[]";
		var aval = document.createAttribute("value");
		aval.value = inputval;
		var aid = document.createAttribute("id");
		aid.value = inputval;
		var acls = document.createAttribute("class");
		acls.value = "uploadedFile";

		//save original filename
		$("#contact_label").val(filenames.orgfile);
	} else {
		//input val is name of newly created file
		var node = document.createElement("INPUT");
		var atype = document.createAttribute("type");
		atype.value = "hidden";
		var aname = document.createAttribute("name");
		aname.value = "uploadedFiles[]";
		var aval = document.createAttribute("value");
		aval.value = inputval;
		var aid = document.createAttribute("id");
		aid.value = inputval;
		var acls = document.createAttribute("class");
		acls.value = "uploadedFile";
	}

	node.setAttributeNode(atype);
	node.setAttributeNode(aname);
	node.setAttributeNode(aval);
	node.setAttributeNode(aid);
	node.setAttributeNode(acls);

	document.getElementById(formId).appendChild(node);

	if (mode == "sendsms") {
		//get the sheets and mobile columns
		var ext = inputval.split(".").pop();
		if (ext == "xls" || ext == "xlsx") {
			var loadele =
				'<div id="sheetloader" class="form-group m-v-sm text-center"><i class="fa fa-cog fa-spin fa-lg fa-fw"></i><b>' +
				SCTEXT("Loading Sheets and columns") +
				" ...<b></b></b></div>";
			$("#xlsheetcolbox").before(loadele);
		}

		$.ajax({
			type: "post",
			dataType: "json",
			url: app_url + "getSheetnColumns",
			data: {
				file: inputval,
			},
			success: function (res) {
				var data = res;
				if (ext == "xls" || ext == "xlsx") {
					$sheets = data.sheets;
					$cols = data.cols;
					$sheet_str = "";
					for (var i = 0; i < $sheets.length; i++) {
						$sheet_str += '<option value="' + $sheets[i] + '">' + $sheets[i] + "</option>";
					}
					$cols_str = "";
					$cols_btns = "";
					for (var j in $cols) {
						if ($cols[j] != null && $cols[j] != "") {
							$cols_str += '<option value="' + $cols[j] + '">' + $cols[j] + "</option>";
							$cols_btns += '<button data-colval="' + $cols[j] + '" type="button" class="colsbtn btn btn-sm btn-default">' + $cols[j] + "</button>";
						}
					}
					//append strings to proper select boxed
					$("#sheetloader").remove();
					$("#xlsheet").html($sheet_str);
					$("#xlcol").html($cols_str);
					$("#xlcolbtns").html($cols_btns);
					$("#ufilecno").val(parseInt(data.totalrows));
					$("#xlsheetcolbox").removeClass("hidden");
					calcContacts();
				} else if (ext == "csv" || ext == "CSV") {
					//csv
					$cols = data.cols;
					$cols_btns = "";
					for (var j in $cols) {
						if ($cols[j] != null && $cols[j] != "") {
							$cols_btns += '<button data-colval="' + j + '" type="button" class="colsbtn btn btn-sm btn-default">' + $cols[j] + "</button>";
						}
					}
					$("#xlcolbtns").html($cols_btns);
					$("#ufilecno").val(parseInt(data.totalrows));
					calcContacts();
				} else {
					// txt
					$("#ufilecno").val(parseInt(data.totalrows));
					$("#xlcolbtns").html("- No Columns Available -");
					$("#xlsheetcolbox").addClass("hidden");
					calcContacts();
				}
			},
		});
	}
}

function deleteInputFile(inputval, mode) {
	if (mode == "sendsms") {
		inputval = JSON.parse(inputval).newfile;
	}
	$.ajax({
		url: app_url + "deleteUploadedFile",
		method: "POST",
		data: {
			mode: mode,
			filename: inputval,
		},
		success: function (res) {
			//remove element
			var element = document.getElementById(inputval);
			element.parentNode.removeChild(element);

			//hide sheets and cols and recount contacts if sendsms mode
			if (mode == "sendsms") {
				//hide the sheet column selector
				$("#xlsheetcolbox").addClass("hidden");
				$("#ufilecno").val(parseInt("0"));
				$("#xlcolbtns").html("- No Columns Available -");
				//count contacts
				calcContacts();
			}
		},
	});
}

function nl2br(str, is_xhtml) {
	var breakTag = is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
	return (str + "").replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, "$1" + breakTag + "$2");
}

function calcTotalSessions() {
	$txs = isNaN(parseInt($("#tx_no").val())) ? 0 : parseInt($("#tx_no").val());
	$rxs = isNaN(parseInt($("#rx_no").val())) ? 0 : parseInt($("#rx_no").val());
	$trxs = isNaN(parseInt($("#trx_no").val())) ? 0 : parseInt($("#trx_no").val());

	$ts = $txs + $rxs + $trxs;
	$("#total_sessions").val($ts);
}

function formatInt(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function isPositiveInteger(val) {
	if (val == null) {
		return false;
	}
	if (val.length == 0) {
		return false;
	}
	for (var i = 0; i < val.length; i++) {
		var ch = val.charAt(i);
		if (ch < "0" || ch > "9") {
			return false;
		}
	}
	return true;
}

function isValidPhone(val) {
	if (val == null) {
		return false;
	}
	if (val.length == 0) {
		return false;
	}
	if (val.length > 15 || val.length < 8) {
		return false;
	}
	for (var i = 0; i < val.length; i++) {
		var ch = val.charAt(i);
		if (ch < "0" || ch > "9" || ch == "+") {
			return false;
		}
	}
	return true;
}

function echeck(str) {
	var at = "@";
	var dot = ".";
	var lat = str.indexOf(at);
	var lstr = str.length;
	var ldot = str.indexOf(dot);
	if (str.indexOf(at) == -1) {
		return false;
	}

	if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr) {
		return false;
	}

	if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr) {
		return false;
	}

	if (str.indexOf(at, lat + 1) != -1) {
		return false;
	}

	if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot) {
		return false;
	}

	if (str.indexOf(dot, lat + 2) == -1) {
		return false;
	}

	if (str.indexOf(" ") != -1) {
		return false;
	}

	return true;
}
function dump(obj) {
	var out = "";
	for (var i in obj) {
		out += i + ": " + obj[i] + "\n";
	}

	alert(out);

	// or, if you wanted to avoid alerts...

	var pre = document.createElement("pre");
	pre.innerHTML = out;
	document.body.appendChild(pre);
}

function calcContacts() {
	//calculate contacts on compose sms page
	var txtcno = 0;
	var grpcno = 0;
	var filecno = 0;
	//show loader
	$("#contcountloader").removeClass("hidden");

	if ($("#account_type").val() == "1") {
		let contactcost = 0;
		if ($("#editsch").length > 0) {
			let schno = parseInt($("#totalschno").val());
			contactcost = parseFloat($("#schcontactcost").val());
			let smscnt;
			//check sms count
			if ($("#txtsms").is(":checked")) {
				smscnt = $("#txtcount").val();
			} else {
				smscnt = 1;
			}
			let totalcost = parseFloat(contactcost) * parseInt(smscnt);
			$("#contcountbox").html(
				`<b id="conf_total_contacts">${formatInt(
					schno
				)}</b> contact(s) X <b>${smscnt}</b> SMS = <b id="conf_total_cost">${app_currency} ${totalcost.toFixed(4)}</b> ${SCTEXT(
					"will be charged"
				)} <span id="contcountloader" class="hidden pull-right text-dark"><i class="fa fa-lg fa-spin fa-circle-o-notch"></i> </span>`
			);
		} else {
			let txtvals = $("#contactinput").val() != undefined ? $("#contactinput").val().split("\n") : [];
			if (txtvals.length > 0) {
				//send contacts to ajax call and calculate total cost
				$.ajax({
					url: `${app_url}calculateSmsCost`,
					method: "post",
					data: {
						mode: "sendsms",
						phones: JSON.stringify(txtvals),
					},
					success: function (res) {
						let data = JSON.parse(res);
						let smscnt;
						//check sms count
						if ($("#txtsms").is(":checked")) {
							smscnt = $("#txtcount").val();
						} else {
							smscnt = 1;
						}
						let totalcost = parseFloat(data.price) * parseInt(smscnt);

						$("#contcountbox").html(
							`<b id="conf_total_contacts">${formatInt(
								data.totalcontacts
							)}</b> contact(s) X <b>${smscnt}</b> SMS = <b id="conf_total_cost">${app_currency} ${totalcost.toFixed(4)}</b> ${SCTEXT(
								"will be charged"
							)} <span id="contcountloader" class="hidden pull-right text-dark"><i class="fa fa-lg fa-spin fa-circle-o-notch"></i> </span>`
						);
					},
				});
			} else {
				//unable to calculate total cost
			}
		}
	} else {
		//credit based account. Simply calculate total contacts and sms count
		if ($("#resendpb").length > 0 || ($("#syspb").length > 0 && $("#syspb").is(":checked"))) {
			//phonebook contact selected

			//check sms count
			if ($("#txtsms").is(":checked")) {
				var smscnt = $("#txtcount").val();
			} else {
				var smscnt = 1;
			}

			if ($("#resend").length > 0) {
				//resend phonebook campaign
				var totalno = $("#pbtotal").val();
			} else {
				//contact count
				var pbfrom = parseInt($("#pbfrom").val()) || 0;
				var pbto = parseInt($("#pbto").val()) || 0;

				var totalno = pbto == 0 ? 0 : parseInt(pbto - pbfrom) + 1;
			}
		} else {
			//my contacts
			//edit campaign contacts
			var schno = 0;
			if ($("#totalschno").length > 0) {
				schno = parseInt($("#totalschno").val());
			} else {
				//textarea
				txtcno = parseInt($("#contactinput").val().split("\n").filter(Boolean).length) || 0;

				//contact groups
				if ($("#grpsel option:selected").length > 1) {
					$("#grpsel option:selected").each(function () {
						grpcno += parseInt($(this).attr("data-count")) || 0;
					});
				} else {
					grpcno = parseInt($("#grpsel option:selected").attr("data-count")) || 0;
				}

				//uploaded file
				filecno = $("#ufilecno").val();
			}

			//check sms count
			if ($("#txtsms").is(":checked")) {
				var smscnt = $("#txtcount").val();
			} else {
				var smscnt = 1;
			}

			var totalno = txtcno + grpcno + parseInt(filecno) + schno;
		}

		if ($("#account_type").val() == "2") {
			rate = parseFloat($("#activerate").val());
			let totalcost = rate * parseInt(totalno * smscnt);
			$("#contcountbox").html(
				`<b id="conf_total_contacts">${formatInt(
					totalno
				)}</b> contact(s) X <b>${smscnt}</b> SMS = <b id="conf_total_cost">${app_currency} ${totalcost.toFixed(4)}</b> ${SCTEXT(
					"will be charged"
				)} <span id="contcountloader" class="hidden pull-right text-dark"><i class="fa fa-lg fa-spin fa-circle-o-notch"></i> </span>`
			);
		} else {
			$("#contcountbox").html(
				'<b id="conf_total_contacts">' +
					formatInt(totalno) +
					"</b> contact(s) X <b>" +
					smscnt +
					'</b> SMS = <b id="conf_total_cost">' +
					formatInt(totalno * smscnt) +
					"</b> " +
					SCTEXT("credits required") +
					' <span id="contcountloader" class="hidden pull-right text-dark"><i class="fa fa-lg fa-spin fa-circle-o-notch"></i> </span>'
			);
		}
	}
}

function loadAppAlerts() {
	$.ajax({
		type: "get",
		url: app_url + "getMyAlerts",
		success: function (res) {
			var mydata = JSON.parse(res);
			$("#notifctr").html(mydata.str);
			if (mydata.count != "0") {
				//play notif sound if new notification
				if ($("#notifcnt").html() == "") $("#notifAudio")[0].play();
				$("#notifcnt").html(mydata.count);
			} else {
				$("#notifcnt").html("");
			}
		},
	});
}

function createSwitches(switchclass = "myswitch") {
	$("." + switchclass).each(function () {
		var ele = $(this);
		var elecolor = ele.attr("data-color");
		var elesize = ele.attr("data-size") || "default";
		if (!ele.attr("data-switchery")) {
			var switchery = new Switchery(document.querySelector("#" + ele.attr("id")), {
				color: elecolor,
				size: elesize,
				jackColor: "#ffffff",
			});
		}
	});
}

function getSmppCredits() {
	$(".smppcredits").each(function () {
		let ele = $(this);
		let apiurl = ele.attr("data-apicall");
		fetch(`${app_url}adminRemoteCalls/${encodeURIComponent(apiurl)}`)
			.then((resp) => resp.text())
			.then(function (data) {
				ele.html(data);
			});
	});
}

//--- Google Transliteration functions --//
//load Google transliteration
google.load("elements", "1", {
	packages: "transliteration",
});

var transliterationControl;
function onLoad() {
	``;
	var options = {
		sourceLanguage: "en",
		destinationLanguage: ["ar", "hi", "or", "kn", "ml", "ta", "te", "mr", "gu", "bn", "pa", "ur", "zh", "ne", "ru", "fa", "el", "sa", "sr"],
		transliterationEnabled: false,
		shortcutKey: "ctrl+g",
	};
	// Create an instance on TransliterationControl with the required
	// options.
	transliterationControl = new google.elements.transliteration.TransliterationControl(options);

	// Enable transliteration in the textfields with the given ids.
	var ids = ["text_sms_content"];
	transliterationControl.makeTransliteratable(ids);

	// Add the STATE_CHANGED event handler to correcly maintain the state
	// of the checkbox.
	transliterationControl.addEventListener(
		google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED,
		transliterateStateChangeHandler
	);

	// Add the SERVER_UNREACHABLE event handler to display an error message
	// if unable to reach the server.
	transliterationControl.addEventListener(
		google.elements.transliteration.TransliterationControl.EventType.SERVER_UNREACHABLE,
		serverUnreachableHandler
	);

	// Add the SERVER_REACHABLE event handler to remove the error message
	// once the server becomes reachable.
	transliterationControl.addEventListener(google.elements.transliteration.TransliterationControl.EventType.SERVER_REACHABLE, serverReachableHandler);

	// Set the checkbox to the correct state.
	if (transliterationControl.isTransliterationEnabled()) {
		document.getElementById("unicode_tl").checked = true;
		document.getElementById("unicode_auto").checked = false;
	} else {
		document.getElementById("unicode_tl").checked = false;
		document.getElementById("unicode_auto").checked = true;
	}
	document.getElementById("unicode_tl").checked = transliterationControl.isTransliterationEnabled();

	// Populate the language dropdown
	transliterationControl.showControl("translControl");
}

// Handler for STATE_CHANGED event which makes sure checkbox status
// reflects the transliteration enabled or disabled status.
function transliterateStateChangeHandler(e) {
	document.getElementById("unicode_tl").checked = e.transliterationEnabled;
	document.getElementById("unicode_auto").checked = e.transliterationEnabled ? false : true;
}

// Handler for checkbox's click event.  Calls toggleTransliteration to toggle
// the transliteration state.
function checkboxClickHandler() {
	transliterationControl.toggleTransliteration();
}

// SERVER_UNREACHABLE event handler which displays the error message.
function serverUnreachableHandler(e) {
	document.getElementById("errorDiv").innerHTML = "Transliteration Server unreachable";
}

// SERVER_UNREACHABLE event handler which clears the error message.
function serverReachableHandler(e) {
	document.getElementById("errorDiv").innerHTML = "";
}

function serialize(mixedValue) {
	//      note 1: We feel the main purpose of this function should be to ease
	//      note 1: the transport of data between php & js
	//      note 1: Aiming for PHP-compatibility, we have to translate objects to arrays
	//   example 1: serialize(['Kevin', 'van', 'Zonneveld'])
	//   returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
	//   example 2: serialize({firstName: 'Kevin', midName: 'van'})
	//   returns 2: 'a:2:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";}'
	//   example 3: serialize( {'ü': 'ü', '四': '四', '𠜎': '𠜎'})
	//   returns 3: 'a:3:{s:2:"ü";s:2:"ü";s:3:"四";s:3:"四";s:4:"𠜎";s:4:"𠜎";}'

	var val, key, okey;
	var ktype = "";
	var vals = "";
	var count = 0;

	var _utf8Size = function (str) {
		return ~-encodeURI(str).split(/%..|./).length;
	};

	var _getType = function (inp) {
		var match;
		var key;
		var cons;
		var types;
		var type = typeof inp;

		if (type === "object" && !inp) {
			return "null";
		}

		if (type === "object") {
			if (!inp.constructor) {
				return "object";
			}
			cons = inp.constructor.toString();
			match = cons.match(/(\w+)\(/);
			if (match) {
				cons = match[1].toLowerCase();
			}
			types = ["boolean", "number", "string", "array"];
			for (key in types) {
				if (cons === types[key]) {
					type = types[key];
					break;
				}
			}
		}
		return type;
	};

	var type = _getType(mixedValue);

	switch (type) {
		case "function":
			val = "";
			break;
		case "boolean":
			val = "b:" + (mixedValue ? "1" : "0");
			break;
		case "number":
			val = (Math.round(mixedValue) === mixedValue ? "i" : "d") + ":" + mixedValue;
			break;
		case "string":
			val = "s:" + _utf8Size(mixedValue) + ':"' + mixedValue + '"';
			break;
		case "array":
		case "object":
			val = "a";
			/*
      if (type === 'object') {
        var objname = mixedValue.constructor.toString().match(/(\w+)\(\)/);
        if (objname === undefined) {
          return;
        }
        objname[1] = serialize(objname[1]);
        val = 'O' + objname[1].substring(1, objname[1].length - 1);
      }
      */

			for (key in mixedValue) {
				if (mixedValue.hasOwnProperty(key)) {
					ktype = _getType(mixedValue[key]);
					if (ktype === "function") {
						continue;
					}

					okey = key.match(/^[0-9]+$/) ? parseInt(key, 10) : key;
					vals += serialize(okey) + serialize(mixedValue[key]);
					count++;
				}
			}
			val += ":" + count + ":{" + vals + "}";
			break;
		case "undefined":
		default:
			// Fall-through
			// if the JS object has a property which contains a null value,
			// the string cannot be unserialized by PHP
			val = "N";
			break;
	}
	if (type !== "object" && type !== "array") {
		val += ";";
	}

	return val;
}

function insertAtCaret(areaId, text, element = false) {
	let txtarea = element === true ? areaId[0] : document.getElementById(areaId);
	if (!txtarea) {
		return;
	}

	let scrollPos = txtarea.scrollTop;
	let strPos = 0;
	let br = txtarea.selectionStart || txtarea.selectionStart == "0" ? "ff" : document.selection ? "ie" : false;
	if (br == "ie") {
		txtarea.focus();
		let range = document.selection.createRange();
		range.moveStart("character", -txtarea.value.length);
		strPos = range.text.length;
	} else if (br == "ff") {
		strPos = txtarea.selectionStart;
	}

	let front = txtarea.value.substring(0, strPos);
	let back = txtarea.value.substring(strPos, txtarea.value.length);
	txtarea.value = front + text + back;
	strPos = strPos + text.length;
	if (br == "ie") {
		txtarea.focus();
		let ieRange = document.selection.createRange();
		ieRange.moveStart("character", -txtarea.value.length);
		ieRange.moveStart("character", strPos);
		ieRange.moveEnd("character", 0);
		ieRange.select();
	} else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}

	txtarea.scrollTop = scrollPos;
}
function substr_replace(str, replace, start, length) {
	var cutstart = start < 0 ? parseInt(start) + parseInt(str.length) : parseInt(start);

	if (length <= 0) {
		length = 1;
	}

	// console.log(str.slice(0, cutstart))
	// console.log(replace)
	// console.log(str.slice(cutstart))
	var finalstr = str.slice(0, cutstart) + replace + str.slice(cutstart + parseInt(length));

	return finalstr;
}
var isoCountries = {
	AF: "Afghanistan",
	AX: "Aland Islands",
	AL: "Albania",
	DZ: "Algeria",
	AS: "American Samoa",
	AD: "Andorra",
	AO: "Angola",
	AI: "Anguilla",
	AQ: "Antarctica",
	AG: "Antigua And Barbuda",
	AR: "Argentina",
	AM: "Armenia",
	AW: "Aruba",
	AU: "Australia",
	AT: "Austria",
	AZ: "Azerbaijan",
	BS: "Bahamas",
	BH: "Bahrain",
	BD: "Bangladesh",
	BB: "Barbados",
	BY: "Belarus",
	BE: "Belgium",
	BZ: "Belize",
	BJ: "Benin",
	BM: "Bermuda",
	BT: "Bhutan",
	BO: "Bolivia",
	BA: "Bosnia And Herzegovina",
	BW: "Botswana",
	BV: "Bouvet Island",
	BR: "Brazil",
	IO: "British Indian Ocean Territory",
	BN: "Brunei Darussalam",
	BG: "Bulgaria",
	BF: "Burkina Faso",
	BI: "Burundi",
	KH: "Cambodia",
	CM: "Cameroon",
	CA: "Canada",
	CV: "Cape Verde",
	KY: "Cayman Islands",
	CF: "Central African Republic",
	TD: "Chad",
	CL: "Chile",
	CN: "China",
	CX: "Christmas Island",
	CC: "Cocos (Keeling) Islands",
	CO: "Colombia",
	KM: "Comoros",
	CG: "Congo",
	CD: "Congo, Democratic Republic",
	CK: "Cook Islands",
	CR: "Costa Rica",
	CI: "Cote D'Ivoire",
	HR: "Croatia",
	CU: "Cuba",
	CY: "Cyprus",
	CZ: "Czech Republic",
	DK: "Denmark",
	DJ: "Djibouti",
	DM: "Dominica",
	DO: "Dominican Republic",
	EC: "Ecuador",
	EG: "Egypt",
	SV: "El Salvador",
	GQ: "Equatorial Guinea",
	ER: "Eritrea",
	EE: "Estonia",
	ET: "Ethiopia",
	FK: "Falkland Islands (Malvinas)",
	FO: "Faroe Islands",
	FJ: "Fiji",
	FI: "Finland",
	FR: "France",
	GF: "French Guiana",
	PF: "French Polynesia",
	TF: "French Southern Territories",
	GA: "Gabon",
	GM: "Gambia",
	GE: "Georgia",
	DE: "Germany",
	GH: "Ghana",
	GI: "Gibraltar",
	GR: "Greece",
	GL: "Greenland",
	GD: "Grenada",
	GP: "Guadeloupe",
	GU: "Guam",
	GT: "Guatemala",
	GG: "Guernsey",
	GN: "Guinea",
	GW: "Guinea-Bissau",
	GY: "Guyana",
	HT: "Haiti",
	HM: "Heard Island & Mcdonald Islands",
	VA: "Holy See (Vatican City State)",
	HN: "Honduras",
	HK: "Hong Kong",
	HU: "Hungary",
	IS: "Iceland",
	IN: "India",
	ID: "Indonesia",
	IR: "Iran, Islamic Republic Of",
	IQ: "Iraq",
	IE: "Ireland",
	IM: "Isle Of Man",
	IL: "Israel",
	IT: "Italy",
	JM: "Jamaica",
	JP: "Japan",
	JE: "Jersey",
	JO: "Jordan",
	KZ: "Kazakhstan",
	KE: "Kenya",
	KI: "Kiribati",
	KR: "Korea",
	KW: "Kuwait",
	KG: "Kyrgyzstan",
	LA: "Lao People's Democratic Republic",
	LV: "Latvia",
	LB: "Lebanon",
	LS: "Lesotho",
	LR: "Liberia",
	LY: "Libyan Arab Jamahiriya",
	LI: "Liechtenstein",
	LT: "Lithuania",
	LU: "Luxembourg",
	MO: "Macao",
	MK: "Macedonia",
	MG: "Madagascar",
	MW: "Malawi",
	MY: "Malaysia",
	MV: "Maldives",
	ML: "Mali",
	MT: "Malta",
	MH: "Marshall Islands",
	MQ: "Martinique",
	MR: "Mauritania",
	MU: "Mauritius",
	YT: "Mayotte",
	MX: "Mexico",
	FM: "Micronesia, Federated States Of",
	MD: "Moldova",
	MC: "Monaco",
	MN: "Mongolia",
	ME: "Montenegro",
	MS: "Montserrat",
	MA: "Morocco",
	MZ: "Mozambique",
	MM: "Myanmar",
	NA: "Namibia",
	NR: "Nauru",
	NP: "Nepal",
	NL: "Netherlands",
	AN: "Netherlands Antilles",
	NC: "New Caledonia",
	NZ: "New Zealand",
	NI: "Nicaragua",
	NE: "Niger",
	NG: "Nigeria",
	NU: "Niue",
	NF: "Norfolk Island",
	MP: "Northern Mariana Islands",
	NO: "Norway",
	OM: "Oman",
	PK: "Pakistan",
	PW: "Palau",
	PS: "Palestinian Territory, Occupied",
	PA: "Panama",
	PG: "Papua New Guinea",
	PY: "Paraguay",
	PE: "Peru",
	PH: "Philippines",
	PN: "Pitcairn",
	PL: "Poland",
	PT: "Portugal",
	PR: "Puerto Rico",
	QA: "Qatar",
	RE: "Reunion",
	RO: "Romania",
	RU: "Russian Federation",
	RW: "Rwanda",
	BL: "Saint Barthelemy",
	SH: "Saint Helena",
	KN: "Saint Kitts And Nevis",
	LC: "Saint Lucia",
	MF: "Saint Martin",
	PM: "Saint Pierre And Miquelon",
	VC: "Saint Vincent And Grenadines",
	WS: "Samoa",
	SM: "San Marino",
	ST: "Sao Tome And Principe",
	SA: "Saudi Arabia",
	SN: "Senegal",
	RS: "Serbia",
	SC: "Seychelles",
	SL: "Sierra Leone",
	SG: "Singapore",
	SK: "Slovakia",
	SI: "Slovenia",
	SB: "Solomon Islands",
	SO: "Somalia",
	ZA: "South Africa",
	GS: "South Georgia And Sandwich Isl.",
	ES: "Spain",
	LK: "Sri Lanka",
	SD: "Sudan",
	SR: "Suriname",
	SJ: "Svalbard And Jan Mayen",
	SZ: "Swaziland",
	SE: "Sweden",
	CH: "Switzerland",
	SY: "Syrian Arab Republic",
	TW: "Taiwan",
	TJ: "Tajikistan",
	TZ: "Tanzania",
	TH: "Thailand",
	TL: "Timor-Leste",
	TG: "Togo",
	TK: "Tokelau",
	TO: "Tonga",
	TT: "Trinidad And Tobago",
	TN: "Tunisia",
	TR: "Turkey",
	TM: "Turkmenistan",
	TC: "Turks And Caicos Islands",
	TV: "Tuvalu",
	UG: "Uganda",
	UA: "Ukraine",
	AE: "United Arab Emirates",
	GB: "United Kingdom",
	US: "United States",
	UM: "United States Outlying Islands",
	UY: "Uruguay",
	UZ: "Uzbekistan",
	VU: "Vanuatu",
	VE: "Venezuela",
	VN: "Viet Nam",
	VG: "Virgin Islands, British",
	VI: "Virgin Islands, U.S.",
	WF: "Wallis And Futuna",
	EH: "Western Sahara",
	YE: "Yemen",
	ZM: "Zambia",
	ZW: "Zimbabwe",
};

function getCountryName(countryCode) {
	if (isoCountries.hasOwnProperty(countryCode)) {
		return isoCountries[countryCode];
	} else {
		return countryCode;
	}
}
function tsToDate(dt) {
	//let dt = new Date(ts);
	return `${dt.getFullYear().toString().padStart(4, "0")}-${(dt.getMonth() + 1).toString().padStart(2, "0")}-${dt
		.getDate()
		.toString()
		.padStart(2, "0")} ${dt.getHours().toString().padStart(2, "0")}:${dt.getMinutes().toString().padStart(2, "0")}:${dt
		.getSeconds()
		.toString()
		.padStart(2, "0")}`;
}
var unicode_regex = /[^\u0000-\u00ff]/; // Small performance gain from pre-compiling the regex
function containsUnicode(str) {
	if (!str.length) return false;
	if (str.charCodeAt(0) > 255) return true;
	return unicode_regex.test(str);
}

$(document).on("ready", function () {
	//more global scrips
	$("#applang").on("change", function () {
		let curlang = $("#applang option:selected").val();
		$.ajax({
			url: app_url + "setAppLanguage/" + curlang,
			success: function (res) {
				window.location.reload(false);
			},
		});
	});
	$(".menu-text").bind("mouseenter", function () {
		var $this = $(this);

		if (this.offsetWidth < this.scrollWidth && !$this.attr("title")) {
			$this.attr("title", $this.text());
		}
	});

	//1. Admin dashboard
	if (curpage == "admin_dashboard") {
		$(".search_option_selector").on("click", function () {
			let selectedValue = $(this).attr("data-myvalue");
			let inputid = $(this).attr("data-inputid");
			let displayText = $(this).text();
			if ($(`#${inputid}`).val() == selectedValue) return;
			if (displayText.length > 10) {
				displayText = displayText.replace(/(.{10})..+/, "$1…");
			}
			$(`#${inputid}_dropdown`).find(".search_option_selector").removeClass("chosen");
			$(this).addClass("chosen");
			$(`#${inputid}`).val(selectedValue);
			$(`#${inputid}_selection`).html(`${displayText} <i class="m-l-sm fas fa-caret-down fa-lg"></i>`);
			loadDashboardStats({
				mode: "roc",
				page: "admin",
			});
		});
		//date range picker

		//top Orders
		$("#topsalesdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "right",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#topsalesdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload sales figures
				$.ajax({
					type: "GET",
					url: app_url + "getLatestOrders/" + $("#topsalesdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						$("#topsalesctr").fadeOut(function () {
							$(this)
								.html(mydata.str)
								.fadeIn(function () {
									if (mydata.more == 1 && mydata.rows == 4) {
										$("#more_sales")
											.removeClass("hidden")
											.attr("data-custom", "4,4")
											.html(SCTEXT("Show More") + " ...");
									} else {
										$("#more_sales").addClass("hidden");
									}
								});
						});
					},
				});
			}
		);
		//Set the initial state of the picker label
		$("#topsalesdp span").html(
			Date.today()
				.add({
					days: -6,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//----------------//

		//routes or carriers datepicker
		$("#roc_dp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#roc_dp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload
				loadDashboardStats({
					mode: "roc",
					page: "admin",
				});
			}
		);
		//Set the initial state of the picker label
		$("#roc_dp span").html(
			Date.today()
				.add({
					days: -6,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//----------------//

		//top consumers
		$("#topcldp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "right",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#topcldp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload sms figures
				loadDashboardStats({
					mode: "topclients",
					page: "admin",
				});
			}
		);
		//Set the initial state of the picker label
		$("#topcldp span").html(
			Date.today()
				.add({
					days: -6,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//----------------//

		//top resellers
		$("#toprsdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#toprsdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload sms figures
				$.ajax({
					type: "GET",
					url: app_url + "getTopResellers/" + $("#toprsdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						$("#toprsctr").fadeOut(function () {
							$(this)
								.html(mydata.str)
								.fadeIn(function () {
									if (mydata.more == 1 && mydata.rows == 4) {
										$("#more_toprs")
											.removeClass("hidden")
											.attr("data-custom", "4,4")
											.html(SCTEXT("Show More") + " ...");
									} else {
										$("#more_toprs").addClass("hidden");
									}
								});
						});
					},
				});
			}
		);
		//Set the initial state of the picker label
		$("#toprsdp span").html(
			Date.today()
				.add({
					days: -6,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//----------------//

		//load sales stats
		$.ajax({
			type: "GET",
			url: app_url + "getAdminSalesStats",
			success: function (res) {
				var mydata = JSON.parse(res);
				$sales_this_week = mydata.sales_this_week || 0;
				$sales_this_month = mydata.sales_this_month || 0;
				$sales_seven_days = mydata.sales_seven_days || 0;

				$("#weekly_sales_ctr").html(mydata.total_weekly_sales);
				$("#monthly_sales_ctr").html(mydata.total_monthly_sales);

				//weekly small chart
				var arr = [];
				for (elem in $sales_this_week) {
					arr.push($sales_this_week[elem]);
				}
				$("#ws_sp_chart").sparkline(arr, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 3,
					barSpacing: 2,
				});
				//monthly small chart
				var arr2 = [];
				for (elem2 in $sales_this_month) {
					arr2.push($sales_this_month[elem2]);
				}
				$("#ms_sp_chart").sparkline(arr2, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 2,
					barSpacing: 1.5,
				});
			},
		});

		//load sms stats
		let url = `${app_url}hypernode/search/ministats`;
		let topstats = {
			mode: "top_sms_stats",
		};
		$.ajax({
			url: url,
			method: "POST",
			dataType: "json",
			contentType: "application/json",
			beforeSend: function (xhr) {
				//Include the bearer token in header
				xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
			},
			data: JSON.stringify(topstats),
			crossDomain: true,
			headers: {
				accept: "application/json",
				"Access-Control-Allow-Origin": "*",
			},
			success: function (res) {
				$("#weekly_sms_ctr").html(res.statsData.total_last_seven);
				$("#monthly_sms_ctr").html(res.statsData.total_last_thirty);
				$("#wm_sp_chart").sparkline(res.statsData.last_seven_sms, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 3,
					barSpacing: 2,
				});
				$("#mm_sp_chart").sparkline(res.statsData.last_thirty_sms, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 2,
					barSpacing: 1.5,
				});
			},
			error: function (err) {
				console.log(err);
			},
		});

		// top orders
		$.ajax({
			type: "GET",
			url: app_url + "getLatestOrders/" + $("#topsalesdp span").html(),
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#topsalesctr").fadeOut(function () {
					$(this)
						.html(mydata.str)
						.fadeIn(function () {
							if (mydata.more == 1 && mydata.rows == 4) {
								$("#more_sales").removeClass("hidden").attr("data-custom", "4,4");
							} else {
								$("#more_sales").addClass("hidden");
							}
						});
				});
			},
		});
		// show more sales
		$("#more_sales").on("click", function () {
			$limit = $(this).attr("data-custom");
			if ($limit != "0,4") {
				//check to make sure all data isnnot loaded
				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("loading") + "....");
				$.ajax({
					type: "GET",
					url: app_url + "getLatestOrders/" + $("#topsalesdp span").html() + "/" + $limit,
					success: function (res) {
						var mydata = JSON.parse(res);
						if (mydata.more == 1) {
							$("#topsalesctr").append(mydata.str);
							$("#topsalesctr").animate(
								{
									scrollTop: $("#topsalesctr").prop("scrollHeight"),
								},
								500,
								function () {
									$("#more_sales")
										.html(SCTEXT("Show More") + " ...")
										.attr({
											disabled: false,
											"data-custom": mydata.limit,
										});
								}
							);
						} else {
							$("#more_sales").html(SCTEXT("All loaded")).attr({
								disabled: false,
								"data-custom": mydata.limit,
							});
						}
					},
				});
			}
		});

		// top resellers
		$.ajax({
			type: "GET",
			url: app_url + "getTopResellers/" + $("#toprsdp span").html(),
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#toprsctr").fadeOut(function () {
					$(this)
						.html(mydata.str)
						.fadeIn(function () {
							if (mydata.more == 1 && mydata.rows == 4) {
								$("#more_toprs").removeClass("hidden").attr("data-custom", "4,4");
							} else {
								$("#more_toprs").addClass("hidden");
							}
						});
				});
			},
		});
		// show more top resellers
		$("#more_toprs").on("click", function () {
			$rslimit = $(this).attr("data-custom");
			if ($rslimit != "0,4") {
				//check to make sure all data isnnot loaded
				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("loading") + "....");
				$.ajax({
					type: "GET",
					url: app_url + "getTopResellers/" + $("#toprsdp span").html() + "/" + $rslimit,
					success: function (res) {
						var mydata = JSON.parse(res);
						if (mydata.more == 1) {
							$("#toprsctr").append(mydata.str);
							$("#toprsctr").animate(
								{
									scrollTop: $("#toprsctr").prop("scrollHeight"),
								},
								500,
								function () {
									$("#more_toprs")
										.html(SCTEXT("Show More") + " ...")
										.attr({
											disabled: false,
											"data-custom": mydata.limit,
										});
								}
							);
						} else {
							$("#more_toprs").html(SCTEXT("All loaded")).attr({
								disabled: false,
								"data-custom": mydata.limit,
							});
						}
					},
				});
			}
		});

		loadDashboardStats({
			mode: "all",
			page: "admin",
		});
	}

	//End Admin Dashboard

	//2. Manage smpp
	if (curpage == "manage_smpp") {
		$(document).on("click", ".remove_smpp", function () {
			var rid = $(this).attr("data-rid");

			bootbox.confirm({
				message: SCTEXT("This will remove the SMPP from Kannel. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete smpp
						window.location = app_url + "deleteSmpp/" + rid;
					}
				},
			});
		});
		//toggle status
		$(document).on("change", ".togstatus", function () {
			let rid = $(this).attr("data-rid");
			let rstatus = 1;
			if ($(this).is(":checked")) {
				rstatus = 0;
			}
			$.ajax({
				url: app_url + "changeSmppStatus",
				method: "post",
				data: { rid: rid, status: rstatus },
				success: function (res) {
					bootbox.alert(res);
				},
			});
		});
	}
	//3. Add Smpp
	if (curpage == "add_smpp" || curpage == "edit_smpp") {
		$("#save_changes").click(function () {
			if (
				$("#smpp_title").val() == "" ||
				$("#provider").val() == "" ||
				$("#smsc_id").val() == "" ||
				!isPositiveInteger($("#total_sessions").val()) ||
				$("#smpp_host").val() == "" ||
				$("#smpp_port").val() == "" ||
				$("#smpp_uid").val() == "" ||
				$("#smpp_pass").val() == ""
			) {
				bootbox.alert(SCTEXT("Some error occurred. Please fill all the fields with appropriate values"));
				return;
			} else {
				if (curpage == "edit_smpp") {
					var dialog = bootbox.dialog({
						closeButton: false,
						message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
							"Saving SMPP and restarting SMSC. Less than a minute remaining"
						)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
					});
					dialog.init(function () {
						$("#stbar").animate({ width: "100%" }, 12000);
						setTimeout(function () {
							$("#edit_smpp_form").attr("action", app_url + "saveSmpp");
							$("#edit_smpp_form").submit();
						}, 500);
					});
				} else {
					var dialog = bootbox.dialog({
						closeButton: false,
						message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
							"Adding SMPP to Kannel"
						)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
					});
					dialog.init(function () {
						$("#stbar").animate({ width: "100%" }, 3000);
						setTimeout(function () {
							$("#add_smpp_form").attr("action", app_url + "saveSmpp");
							$("#add_smpp_form").submit();
						}, 500);
					});
				}
			}
		});

		$("#bk").click(function () {
			window.location = app_url + "manageSmpp";
		});

		$("#tx_no,#rx_no,#trx_no").on("click keyup focus blur change", function () {
			calcTotalSessions();
		});
	}

	// 4. manage blacklist db
	if (curpage == "manage_bl_db") {
		$(document).on("click", ".remove_bldb", function () {
			var tid = $(this).attr("data-tid");

			bootbox.confirm({
				message: SCTEXT("This will delete the table and all the data. This cannot be undone. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete bldb
						window.location = app_url + "deleteBlacklistDb/" + tid;
					}
				},
			});
		});
	}

	// 5. add blacklist db
	if (curpage == "add_bl_db" || curpage == "edit_bl_db") {
		$("#save_changes").click(function () {
			//validate
			if ($("#tname").val() == "" || $("#mcol").val() == "") {
				bootbox.alert(SCTEXT("Table name & mobile column cannot be blank."));
				return;
			}
			if (/^[a-zA-Z0-9-_]*$/.test($("#tname").val()) == false) {
				bootbox.alert(SCTEXT("Table name contains illegal characters."));
				return;
			}
			if (/^[a-zA-Z0-9-_]*$/.test($("#mcol").val()) == false) {
				bootbox.alert(SCTEXT("Mobile Column name contains illegal characters."));
				return;
			}
			//submit
			if (curpage == "edit_bl_db") {
				var dialog = bootbox.dialog({
					closeButton: false,
					message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
						"Saving changes in Database"
					)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
				});
				dialog.init(function () {
					$("#stbar").animate({ width: "100%" }, 800);
					setTimeout(function () {
						$("#edit_bldb_form").attr("action", app_url + "saveBlacklistDb");
						$("#edit_bldb_form").submit();
					}, 300);
				});
			} else {
				var dialog = bootbox.dialog({
					closeButton: false,
					message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
						"Adding Database"
					)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
				});
				dialog.init(function () {
					$("#stbar").animate({ width: "100%" }, 800);
					setTimeout(function () {
						$("#add_bldb_form").attr("action", app_url + "saveBlacklistDb");
						$("#add_bldb_form").submit();
					}, 300);
				});
			}
		});

		$("#bk").click(function () {
			window.location = app_url + "manageBlacklists";
		});
	}

	if (curpage == "upload_bl_db") {
		$("#bk").click(function () {
			window.location = app_url + "manageBlacklists";
		});
		$("#save_changes").click(function () {
			//check if table selected
			if ($("#seltbl").val() == 0) {
				bootbox.alert(SCTEXT("Please select a table to import data."));
				return;
			}
			//check if any files is uploaded
			if ($(".uploadedFile").length == 0) {
				bootbox.alert(SCTEXT("Please upload at least one file."));
				return;
			}
			//check if any upload is in progress
			if ($("#uprocess").val() == 1) {
				bootbox.alert(SCTEXT("File upload is in progress. Kindly wait for upload to finish or Cancel Upload & proceed."));
				return;
			}
			//submit
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Adding Upload Task"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#upload_bldb_form").attr("action", app_url + "addUploadTask");
					$("#upload_bldb_form").submit();
				}, 100);
			});
		});
	}

	if (curpage == "view_bl_db") {
		//taskinfo close
		$("body").on("click", ".closeDS", function () {
			$(".taskinfo").popover("hide");
		});
		//cancel import
		$("body").on("click", ".cancel-import", function () {
			var taskid = $(this).attr("data-taskid");
			bootbox.confirm({
				message: SCTEXT(
					"This will delete the current task and uploaded file. If some of the data is already imported it will not be deleted. Are you sure you want to proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete task
						window.location = app_url + "deleteImportTask/" + taskid;
					}
				},
			});
		});
		//lookup number
		$("#lookupbtn").click(function () {
			var btn = $(this);
			btn
				.attr("disabled", "disabled")
				.html(SCTEXT("Searching") + "...")
				.css("cursor", "progress");
			//validate
			var mobile = $("#mobile_no").val();
			if (!isPositiveInteger(mobile) || mobile.length < 7 || mobile.length > 15) {
				bootbox.alert(SCTEXT("Invalid mobile number format. Enter mobile number with no space and without plus (+) sign."));
				btn
					.attr("disabled", false)
					.html('<i class="fa fa-search fa-lg"></i>&nbsp; ' + SCTEXT("Search"))
					.css("cursor", "pointer");
				return;
			}
			$.ajax({
				url: app_url + "numberLookupBlDb",
				data: {
					mobile: mobile,
					tinfo: $("#tinfo").val(),
				},
				success: function (res) {
					var mydata = JSON.parse(res);
					btn
						.attr("disabled", false)
						.html('<i class="fa fa-search fa-lg"></i>&nbsp; ' + SCTEXT("Search"))
						.css("cursor", "pointer");
					if (mydata.result == 0) {
						//not found
						$("#lookup_result").html('<li class="text-center list-group-item list-group-item-danger">- ' + SCTEXT("No Records Found") + " -</li>");
					} else {
						//found
						var str = `<li class="list-group-item list-group-item-info"><div class="input-group"><span class="input-group-addon text-success"><i class="fa fa-lg fa-check-circle"></i>&nbsp;${SCTEXT(
							"Found"
						)}</span><input id="lookup_res_txt" type="text" class="form-control text-center" readonly="readonly" value="${mobile}" /><span class="input-group-btn"><button id="del_lookup_res" data-mid="${
							mydata.id
						}" class="btn btn-danger" type="button"><i class="fa fa-trash fa-lg"></i></button></span></div></li>`;

						$("#lookup_result").html(str);
					}
				},
			});
		});

		//delete lookup number
		$(document).on("click", "#del_lookup_res", function () {
			var mid = $(this).attr("data-mid");
			var btn = $(this);

			bootbox.confirm({
				message: SCTEXT("This will delete the mobile number from database. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						btn.attr("disabled", "disabled").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>').css("cursor", "progress");
						$.ajax({
							url: app_url + "deleteNdncNumber/" + mid,
							data: { tinfo: $("#tinfo").val() },
							success: function (res) {
								$("#lookup_result")
									.html(
										'<li class="text-center list-group-item list-group-item-success"><i class="fa fa-lg fa-inverse fa-check-circle"></i>&nbsp;' +
											SCTEXT("Record Deleted") +
											"</li>"
									)
									.fadeIn(500)
									.delay(2000)
									.fadeOut(500, function () {
										$(this).html("").fadeIn(100);
										$("#mobile_no").val("");
									});
							},
						});
					}
				},
			});
		});
		//empty table
		$("#empty_tbl").click(function () {
			var tid = $(this).attr("data-tid");
			var btn = $(this);
			btn
				.attr("disabled", "disabled")
				.html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>&nbsp; ' + SCTEXT("Deleting All Data") + "..")
				.css("cursor", "progress");
			bootbox.confirm({
				message: SCTEXT("This will delete all the data from the table. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					btn
						.attr("disabled", false)
						.html(SCTEXT("Empty the Table") + " (TRUNCATE)")
						.css("cursor", "pointer");
					if (result) {
						//delete task
						$.ajax({
							url: app_url + "bldbActions",
							method: "post",
							data: {
								tinfo: $("#tinfo").val(),
								mode: "empty",
							},
							success: function (res) {
								bootbox.alert('<i class="fa fa-lg text-success fa-check-circle"></i>&nbsp;' + res);
							},
						});
					}
				},
			});
		});

		//optimize table

		$("#opt_tbl").click(function () {
			var tid = $(this).attr("data-tid");
			var btn = $(this);
			btn
				.attr("disabled", "disabled")
				.html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>&nbsp; ' + SCTEXT("Optimizing Table") + "..")
				.css("cursor", "progress");
			bootbox.confirm({
				message: SCTEXT("Optimization process will take a long time for large database. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					btn
						.attr("disabled", false)
						.html(SCTEXT("Rebuild Index") + " (OPTIMIZE) ")
						.css("cursor", "pointer");
					if (result) {
						//delete task
						$.ajax({
							url: app_url + "bldbActions",
							method: "post",
							data: {
								tinfo: $("#tinfo").val(),
								mode: "optimize",
							},
							success: function (res) {
								bootbox.alert('<i class="fa fa-lg text-success fa-check-circle"></i>&nbsp;' + res);
							},
						});
					}
				},
			});
		});
	}

	if (curpage == "manual_add_bldb") {
		//submit form
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Importing data"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 800);
				setTimeout(function () {
					$("#manadd_form").attr("action", app_url + "saveManualInsertBlDb");
					$("#manadd_form").submit();
				}, 300);
			});
		});
		//cancel
		$("#bk").click(function () {
			window.location = app_url + "manageBlacklists";
		});
	}

	if (curpage == "manual_del_bldb") {
		//submit form
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"This can take longer for large data. Please wait"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 800);
				setTimeout(function () {
					$("#mandel_form").attr("action", app_url + "saveManualDelBlDb");
					$("#mandel_form").submit();
				}, 300);
			});
		});
		//cancel
		$("#bk").click(function () {
			window.location = app_url + "manageBlacklists";
		});
	}

	//6. Manage credit count rules
	if (curpage == "manage_ccrules") {
		//taskinfo close
		$("body").on("click", ".closeDS", function () {
			$(".ruleinfo").popover("hide");
		});
		//delete rule
		$(document).on("click", ".remove_ccr", function () {
			var rid = $(this).attr("data-rid");
			bootbox.confirm({
				message: SCTEXT("This will delete the credit rule. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "delCountRule/" + rid;
					}
				},
			});
		});
	}

	//7. Add CC Rule
	if (curpage == "add_ccrule" || curpage == "edit_ccrule") {
		//auto write range and rate
		$(".rangeto").on("keyup", function () {
			var elem = $(this);
			var parelem = elem.parent().parent().parent().next();
			var ratelem = elem.parent().next().find("span.label");

			parelem.find(".bg-white").val(parseInt(elem.val() == "" || isNaN(elem.val()) ? 0 : elem.val()) + 1);
			ratelem.html(parseInt(parseInt(elem.val() == "" || isNaN(elem.val()) ? 0 : elem.val()) / parseInt(elem.attr("data-count"))) + " chars/sms");
		});

		$(".nextStep").on("click", function () {
			var curstep = $(this).attr("data-step");
			if (curstep == "1") {
				//validate step 1
				if ($("#ccrname").val() == "") {
					bootbox.alert(SCTEXT("Please enter a name for this credit count rule."));
					return;
				}
				var haserr = 0;
				$("#step-1-form")
					.find(".rangeto")
					.each(function () {
						var elem = $(this);
						if (elem.val() == "" || isNaN(elem.val())) {
							elem.addClass("error-input");
							haserr++;
						} else {
							elem.removeClass("error-input");
						}
					});

				if (haserr > 0) {
					bootbox.alert(SCTEXT("Please enter correct values in all the fields"));
					haserr = 0;
					return;
				} else {
					//move to step 2
					$("#step-1-form").hide("slide", { direction: "left" }, 500, function () {
						$(".step-1").removeClass("active");
						$(".step-2").addClass("active");
						$("#step-2-form").removeClass("hidden").show("slide", { direction: "right" }, 400);
					});
				}
			}

			if (curstep == "2") {
				//validate step 2
				var haserr2 = 0;
				$("#step-2-form")
					.find(".rangeto")
					.each(function () {
						var elem = $(this);
						if (elem.val() == "" || isNaN(elem.val())) {
							elem.addClass("error-input");
							haserr2++;
						} else {
							elem.removeClass("error-input");
						}
					});

				if (haserr2 > 0) {
					bootbox.alert(SCTEXT("Please enter correct values in all the fields"));
					haserr2 = 0;
					return;
				} else {
					//move to step 3
					$("#step-2-form").hide("slide", { direction: "left" }, 500, function () {
						$(".step-2").removeClass("active");
						$(".step-3").addClass("active");
						$("#step-3-form").removeClass("hidden").show("slide", { direction: "right" }, 400);
					});
				}
			}

			if (curstep == "3") {
				//validate step 3
				var haserr3 = 0;
				$("#step-3-form")
					.find(".input-sm")
					.each(function () {
						var elem = $(this);
						if (elem.val() == "" || isNaN(elem.val())) {
							elem.addClass("error-input");
							haserr3++;
						} else {
							elem.removeClass("error-input");
						}
					});

				if (haserr3 > 0) {
					bootbox.alert("Please enter correct values in all the fields");
					haserr3 = 0;
					return;
				} else {
					//submit the form
					var dialog = bootbox.dialog({
						closeButton: false,
						message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
							"Adding Credit count rule"
						)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
					});
					dialog.init(function () {
						$("#stbar").animate({ width: "100%" }, 300);
						setTimeout(function () {
							$("#add_rule_form").attr("action", app_url + "saveCountRule");
							$("#add_rule_form").submit();
						}, 100);
					});
				}
			}
		});

		$(".prevStep").on("click", function () {
			if ($(this).attr("data-step") == "2") {
				$("#step-2-form").hide("slide", { direction: "right" }, 400, function () {
					$(".step-2").removeClass("active");
					$(".step-1").addClass("active");
					$("#step-1-form").removeClass("hidden").show("slide", { direction: "left" }, 400);
				});
			}
			if ($(this).attr("data-step") == "3") {
				$("#step-3-form").hide("slide", { direction: "right" }, 400, function () {
					$(".step-3").removeClass("active");
					$(".step-2").addClass("active");
					$("#step-2-form").removeClass("hidden").show("slide", { direction: "left" }, 400);
				});
			}
		});

		//--
		$("#bk").click(function () {
			window.location = app_url + "manageCountRules";
		});
	}

	//8. Manage Countries

	if (curpage == "edit_country") {
		//file upload in editor
		//set timezone
		$("#timezone option").each(function () {
			if ($(this).val() == $("#curtz").val()) {
				$(this).attr("selected", "selected");
			}
		});
		$("#timezone").trigger("change.select2");
		//submit
		$("#save_changes").click(function () {
			//validate
			if ($("#ccname").val() == "") {
				bootbox.alert(SCTEXT("Country name cannot be blank."));
				return;
			}
			if ($("#cpre").val() == "") {
				bootbox.alert(SCTEXT("Calling prefix cannot be blank."));
				return;
			}
			if ($("#cvl").val() == "") {
				bootbox.alert(
					SCTEXT(
						"Valid mobile number lengths cannot be blank. Enter at least two values:<br>1. Mobile Number length with country prefix<br>2. Mobile Number length without country prefix"
					)
				);
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#edit_ctry_form").attr("action", app_url + "saveCountry");
					$("#edit_ctry_form").submit();
				}, 150);
			});
		});
		//--
		$("#bk").click(function () {
			window.location = app_url + "manageCountries";
		});
	}

	if (curpage == "upload_prefix") {
		//submit the form
		$("#save_changes").click(function () {
			if ($("#country").val() == "0") {
				bootbox.alert(SCTEXT("Please select the country for which the prefixes are being uploaded."));
				return;
			}
			if ($(".uploadedFile").length == 0) {
				bootbox.alert(SCTEXT("Please upload a file with prefixes and operator data."));
				return;
			}
			//if alls good
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Importing prefixes & operator data"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#upload_ocpr_form").attr("action", app_url + "importPrefixes");
					$("#upload_ocpr_form").submit();
				}, 150);
			});
		});

		$("#bk").click(function () {
			window.location = app_url + "manageCountries";
		});
	}

	if (curpage == "view_prefix") {
		//delete prefix

		//delete prefixes
		$(document).on("click", ".delprefix", function () {
			var pid = $(this).attr("data-pid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this prefix?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteOP/" + pid + "/" + $("#cid").val();
					}
				},
			});
		});
	}

	if (curpage == "edit_prefix") {
		$("#msel").on("change", function () {
			let nw = $("#msel option:selected").attr("data-nw");
			let rg = $("#msel option:selected").attr("data-rg");
			$("#operator").val(nw);
			$("#circle").val(rg);
		});

		//submit the form
		$("#save_changes").click(function () {
			if ($("#prefix").val() == "") {
				bootbox.alert(SCTEXT("Prefix cannot be blank. Please enter mobile prefix"));
				return;
			}
			if ($("#operator").val() == "") {
				bootbox.alert(SCTEXT("Operator cannot be blank. Please enter operator associated with the mobile prefix"));
				return;
			}

			//if alls good
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving prefixes & operator data"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 100);
				setTimeout(function () {
					$("#edit_pre_form").attr("action", app_url + "saveOP");
					$("#edit_pre_form").submit();
				}, 90);
			});
		});
		//---
		$("#bk").click(function () {
			window.location = app_url + "viewAllOP/" + $("#cid").val();
		});
	}

	//9. Manage Routes
	if (curpage == "manage_routes") {
		//toggle status
		$(document).on("change", ".togstatus", function () {
			var rid = $(this).attr("data-rid");
			var rstatus = 1;
			if ($(this).is(":checked")) {
				rstatus = 0;
			}
			$.ajax({
				url: app_url + "changeRouteStatus",
				method: "post",
				data: { rid: rid, status: rstatus },
				success: function (res) {
					bootbox.alert(res);
				},
			});
		});

		//delete route
		$(document).on("click", ".remove_route", function () {
			var rid = $(this).attr("data-rid");
			bootbox.confirm({
				message:
					"<h4><i class='fa fa-lg fa-info-circle text-danger'></i> " +
					SCTEXT("Caution") +
					" !! </h4>" +
					SCTEXT(
						"This route may be assigned to users with SMS balance associated with this route. If you delete this route, they will get refund of the equal amount of SMS left in their accounts. Are you sure you want to proceed?"
					),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteRoute/" + rid;
					}
				},
			});
		});
	}

	if (curpage == "add_route" || curpage == "edit_route") {
		//set smsc type based on selection
		$("#prismpp, #bkpsmpp").on("change", function () {
			let elem = $("option:selected", this);
			if (elem.parent().attr("id") == "prismpp") {
				$("#pritype").val(elem.attr("data-smsc"));
			} else {
				$("#bkptype").val(elem.attr("data-smsc"));
			}
			//bootbox.alert(elem.parent().attr("id") + "-" + elem.attr("data-smsc"));
		});
		//switch boxes based on route algo selected
		$("#ralgo").on("change", function () {
			let aid = $(this).val();
			$(".ralgoctrs").addClass("hidden");
			$(`#ralgo_${aid}`).removeClass("hidden");
		});
		//add and remove smpp selection rows
		$("#add_persmpprow").on("click", function () {
			let tr = `${$("#ralgo_1_tbody").html()} ${atob($("#persmpprow").val())}`;
			$("#ralgo_1_tbody").html(tr);
		});
		$("#add_rrsmpprow").on("click", function () {
			let tr = `${$("#ralgo_2_tbody").html()} ${atob($("#rrsmpprow").val())}`;
			$("#ralgo_2_tbody").html(tr);
		});
		$("#add_lcrsmpprow").on("click", function () {
			let tr = `${$("#ralgo_3_tbody").html()} ${atob($("#lcrsmpprow").val())}`;
			$("#ralgo_3_tbody").html(tr);
		});
		$(document).on("click", ".rmrow", function () {
			$(this).parent().parent().remove();
		});
		//add timezone
		$("#cov").change(function () {
			if ($("#cov option:selected").val() == 0) {
				$("#pfx-toggle").addClass("disabledBox");
			} else {
				$("#pfx-toggle").removeClass("disabledBox");
			}
			var tz = $("#cov option:selected").attr("data-tz");
			$("#acttz").val(tz);
		});
		//toggle active time panel
		$(".acttype").click(function () {
			var aval = $(this).val();
			if (aval == 1) {
				$("#spectime").fadeIn();
			} else {
				$("#spectime").fadeOut();
			}
		});
		//submit form
		$("#save_changes").click(function () {
			if ($("#rt_title").val() == "") {
				bootbox.alert(SCTEXT("Please enter a title for this route"));
				return;
			}
			//validate on server side as different routing logic needs different validations
			//all good

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving SMS route in the system"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#add_route_form").attr("action", app_url + "saveRoute");
					$("#add_route_form").submit();
				}, 150);
			});
		});
		//--
		$("#bk").click(function () {
			window.location = app_url + "manageRoutes";
		});
	}

	if (curpage == "smpp_dlrcodes") {
		//import code from other smpp
		$("#submit_importVdlr").on("click", function () {
			let srcSmpp = $("#sourceSmpp :selected").val();
			let targetSmpp = $("#targetSmpp").val();
			bootbox.confirm({
				message: SCTEXT("Importing DLR Codes from other SMPP will overwrite existing DLR codes for this SMPP. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "importSmppVdlr/" + srcSmpp + "/" + targetSmpp;
					}
				},
			});
		});
		//add new row
		$("#add_new_code").click(function () {
			$newrow = $("#newrowstr").val();
			//check if empty table
			if ($(".empty_table").length > 0) {
				$("#codes_ctnr").html($newrow);
			} else {
				//table already has rows append new row
				$("#codes_ctnr").append($newrow);
			}
		});
		//toggle dlr code action
		$(document).on("change", ".dlrcodeaction", function () {
			let curval = $(this).val();
			let ele = $(this).parent().next();
			if (curval == "0") {
				ele.html('<select name="act_params[]" class="form-control"><option value="0">- Select Action First -</option></select>');
			} else if (curval == "1" || curval == "3" || curval == "4") {
				ele.html($("#allref_opts").val());
			} else {
				ele.html($("#allrt_opts").val());
			}
		});
		//remove row
		$("body").on("click", ".rmv", function () {
			$(this).parent().parent().remove();
		});
		//submit changes
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving DLR codes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#add_dlrcode_form").attr("action", app_url + "saveSmppDlrCodes");
					$("#add_dlrcode_form").submit();
				}, 150);
			});
		});
		//--
		$("#bk").click(function () {
			window.location = app_url + "manageSmpp";
		});
	}

	//10. Manage Refund rules

	if (curpage == "add_rrule" || curpage == "edit_rrule") {
		//submit
		$("#save_changes").click(function () {
			if ($("#rname").val() == "") {
				bootbox.alert(SCTEXT("Rule name cannot be blank"));
				return;
			}
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Refund Rule"
				)}. . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#add_rrule_form").attr("action", app_url + "saveRefundRule");
					$("#add_rrule_form").submit();
				}, 150);
			});
		});
		//back
		$("#bk").click(function () {
			window.location = app_url + "refundRules";
		});
	}

	//11. Kannel Monitor

	if (curpage == "kannel_mon") {
		if ($("html").scrollTop() != 0) $("html, body").animate({ scrollTop: 0 }, "fast");

		//reload page
		$(".reloadPg").click(function () {
			$(this).find(".fa").addClass("fa-spin");
			window.location.reload(false);
		});
		//kannel actions
		$(".kmon-action").click(function () {
			var action = $(this).attr("data-act");
			bootbox.prompt({
				title: SCTEXT("Please enter Kannel administration password"),
				inputType: "password",
				callback: function (pass) {
					if (pass) {
						var dialog = bootbox.dialog({
							message: `<p class="text-center"><i class="fa fa-large fa-spin fa-circle-o-notch"></i>&nbsp;&nbsp;${SCTEXT("Please wait")} . . .</p>`,
							size: "small",
							closeButton: false,
						});
						//send password and perform action
						$.ajax({
							url: app_url + "kannelActions",
							method: "post",
							data: {
								action: action,
								kpass: pass,
							},
							success: function (res) {
								if (action == "editConf") {
									if (res == "failed") {
										location.reload();
									} else {
										$(dialog).modal("hide");
										bootbox.confirm({
											message: `<div class="bg-dark text-white p-sm m-t-sm"> <h4>Edit Kannel Conf</h4></div> <div class="m-t-md p-xs" style="max-height: 400px; overflow: auto;"><div id="kannel_conf_edtr" spellcheck=false contenteditable="true" style="font-family:monospace;font-size:0.95em;color:darkred;">${res}</div></div>`,
											buttons: {
												cancel: {
													label: SCTEXT("Exit"),
													className: "btn-default",
												},
												confirm: {
													label: SCTEXT("Save Changes"),
													className: "btn-info",
												},
											},
											callback: function (result) {
												if (result) {
													//save contents to kannel conf
													let kannelconf = $("#kannel_conf_edtr").html();
													$.ajax({
														url: app_url + "kannelActions",
														method: "post",
														data: {
															action: "saveConf",
															kpass: pass,
															confData: kannelconf,
														},
														success: function (res) {
															location.reload();
														},
													});
												}
											},
										});
									}
								} else {
									location.reload();
								}
							},
						});
					}
				},
			});
		});

		//stop smsc
		$(".stop-smsc").click(function () {
			var smscid = $(this).attr("data-smsc");
			bootbox.prompt({
				title: SCTEXT("Please enter Kannel administration password"),
				inputType: "password",
				callback: function (pass) {
					if (pass) {
						var dialog = bootbox.dialog({
							message: `<p class="text-center"><i class="fa fa-large fa-spin fa-circle-o-notch"></i>&nbsp;&nbsp;${SCTEXT("Please wait")} . . .</p>`,
							size: "small",
							closeButton: false,
						});
						//send password and perform action
						$.ajax({
							url: app_url + "kannelActions",
							method: "post",
							data: {
								action: "stop-smsc",
								smsc: smscid,
								kpass: pass,
							},
							success: function (res) {
								location.reload();
							},
						});
					}
				},
			});
		});
		//start smsc
		$(".start-smsc").click(function () {
			var smscid = $(this).attr("data-smsc");
			bootbox.prompt({
				title: SCTEXT("Please enter Kannel administration password"),
				inputType: "password",
				callback: function (pass) {
					if (pass) {
						var dialog = bootbox.dialog({
							message: `<p class="text-center"><i class="fa fa-large fa-spin fa-circle-o-notch"></i>&nbsp;&nbsp;${SCTEXT("Please wait")} . . .</p>`,
							size: "small",
							closeButton: false,
						});
						//send password and perform action
						$.ajax({
							url: app_url + "kannelActions",
							method: "post",
							data: {
								action: "start-smsc",
								smsc: smscid,
								kpass: pass,
							},
							success: function (res) {
								location.reload();
							},
						});
					}
				},
			});
		});
	}

	//12. Announcements
	if (curpage == "manage_annc") {
		//set status
		$(document).on("change", ".togstatus", function () {
			var val = 0;
			if ($(this).is(":checked")) {
				val = "1";
			}
			var aid = $(this).val();
			$.ajax({
				method: "post",
				url: app_url + "setAnnouncementState",
				data: { value: val, aid: aid },
			});
		});
		//--
	}

	if (curpage == "add_annc" || curpage == "edit_annc") {
		//save
		$("#save_changes").click(function () {
			if ($("#dtxt").val == "") {
				bootbox.alert(SCTEXT("Please enter the message to broadcast."));
				return;
			}
			if ($("#dfor").val == 0) {
				bootbox.alert(SCTEXT("Please select the users who will see this announcement."));
				return;
			}
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Announcement"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#annfrm").attr("action", app_url + "saveAnnouncement");
					$("#annfrm").submit();
				}, 150);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "announcements";
		});
	}

	//13. Manage SMS plans
	if (curpage == "manage_smsplans") {
		$(document).on("click", ".del-plan", function () {
			var pid = $(this).attr("data-pid");
			var ucnt = $(this).attr("data-ucount");
			bootbox.confirm({
				message: SCTEXT("There may be some user accounts associated with this plan. Are you sure you want to delete this plan?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "delSmsPlan/" + pid;
					}
				},
			});
		});
	}

	if (curpage == "add_smsplan" || curpage == "edit_smsplan") {
		$("#pt1, #pt2").click(function () {
			if ($(this).val() == 1) {
				$("#volbox").addClass("hidden");
				$("#volftbox").addClass("hidden");
				$("#subbox").removeClass("hidden");
				$("#subftbox").removeClass("hidden");
				$("#sexp_box").removeClass("hidden");
			} else {
				$("#subbox").addClass("hidden");
				$("#subftbox").addClass("hidden");
				$("#volbox").removeClass("hidden");
				$("#volftbox").removeClass("hidden");
				$("#sexp_box").addClass("hidden");
			}
		});

		//populate table based on route selection

		$("#proutes").on("select2:select", function (e) {
			var rval = e.params.data.id;
			var rtxt = e.params.data.text;
			//add a column for this route
			$("#splan-vol thead tr").append('<th id="rhcol' + rval + '">' + rtxt + "</th>");
			var ctr = 0;
			$("#splan-vol tbody tr").each(function () {
				$(this).append(
					'<td class="tdcol' +
						rval +
						'" data-colname="' +
						rtxt +
						'"><div class="input-group"><span class="input-group-addon"> ' +
						app_currency +
						' </span><input type="text" class="form-control input-small-sc input-sm" name="price[' +
						ctr +
						"][" +
						rval +
						']" placeholder="e.g. 0.05" value="" /><span class="input-group-addon">/sms</span></div></td>'
				);
				ctr++;
			});

			//add a block for this route
			$(".poptrt").each(function () {
				var id = $(this).parent().parent().parent().attr("id");
				$(this).append(
					'<div class="rtblock' +
						rval +
						'"><div class="text-white text-center label-info">' +
						rtxt +
						'</div><div class="rtopts"><div class="control-label">' +
						SCTEXT("SMS Credits") +
						':</div> <div class="input-group"><input type="text" class="form-control" name="poptcredits[' +
						id +
						"][" +
						rval +
						']" placeholder="e.g. 1000"/><span class="input-group-addon"> SMS</span></div><div class="control-label">' +
						SCTEXT("Additional Purchases") +
						':</div><div class="input-group"><span class="input-group-addon">' +
						app_currency +
						'</span><input type="text" class="form-control" name="poptaddrate[' +
						id +
						"][" +
						rval +
						']" placeholder="e.g. 0.05"/><span class="input-group-addon">per sms</span></div></div></div>'
				);
			});
		});

		$("#proutes").on("select2:unselect", function (e) {
			var rval = e.params.data.id;
			var rtxt = e.params.data.text;
			//remove the column for this route
			$("#splan-vol thead tr")
				.find($("th#rhcol" + rval))
				.remove();
			$("#splan-vol tbody tr").each(function () {
				$(this)
					.find($("td.tdcol" + rval))
					.remove();
			});

			//remove the block for this route
			$(".poptrt").each(function () {
				$(this)
					.find(".rtblock" + rval)
					.remove();
			});
		});

		$("#add_subplan").click(function () {
			//get id n generate new id
			var newid = parseInt($(this).attr("data-oid")) + 1;
			$(this).attr("data-oid", newid);
			if ($("#plan_id").length > 0) {
				var idn = "P" + $("#plan_id").val() + "-SU-POPT-" + newid;
			} else {
				var idn = "SU-POPT-" + newid;
			}
			var str =
				'<div id="' +
				idn +
				'" class="col-md-3 planopts"><input type="hidden" name="subopts[]" value="' +
				idn +
				'" /><a href="javascript:void(0);" class="plan-remove" data-oid="' +
				newid +
				'"><i class="fa fa-3x text-danger fa-minus-circle"></i> </a><div class="panel panel-info"><div class="panel-heading"><input type="text" data-oid="' +
				newid +
				'" class="poptnametxt form-control" placeholder="' +
				SCTEXT("enter plan option name e.g. PRO") +
				'" name="poptname[' +
				idn +
				']" /></div><div class="panel-body"><div class="popt-item"><select class="form-control" data-plugin="select2" data-placeholder="' +
				SCTEXT("choose payment cycle") +
				'.." name="poptcycle[' +
				idn +
				']"><option></option><option value="m">' +
				SCTEXT("Monthly") +
				'</option><option value="y">' +
				SCTEXT("Yearly") +
				'</option></select></div><div class="popt-item"><div class="input-group"><span class="input-group-addon">' +
				app_currency +
				'</span><input type="text" class="form-control" name="poptrate[' +
				idn +
				']" placeholder="' +
				SCTEXT("enter subscription Fee e.g. 150") +
				'"/></div></div><div class="popt-item poptrt">';

			//add boxes for routes
			$("#proutes :selected").each(function () {
				var rval = $(this).val();
				var rtxt = $(this).text();
				str +=
					'<div class="rtblock' +
					rval +
					'"><div class="text-white text-center label-info">' +
					rtxt +
					'</div><div class="rtopts"><div class="control-label">' +
					SCTEXT("SMS Credits") +
					':</div> <div class="input-group"><input type="text" class="form-control" name="poptcredits[' +
					idn +
					"][" +
					rval +
					']" placeholder="e.g. 1000"/><span class="input-group-addon"> SMS</span></div><div class="control-label">' +
					SCTEXT("Additional Purchases") +
					':</div><div class="input-group"><span class="input-group-addon">' +
					app_currency +
					'</span><input type="text" class="form-control" name="poptaddrate[' +
					idn +
					"][" +
					rval +
					']" placeholder="e.g. 0.05"/><span class="input-group-addon">per sms</span></div></div></div>';
			});

			str +=
				'</div><div class="popt-item"><textarea name="poptdesc[' +
				idn +
				']" class="form-control" placeholder="' +
				SCTEXT("enter a small description e.g. list some features etc.") +
				'"></textarea></div><div class="popt-item"><select class="form-control" data-plugin="select2" data-placeholder="' +
				SCTEXT("choose opt-in method") +
				'.." name="poptsel[' +
				idn +
				']"><option></option><option value="0">' +
				SCTEXT("Payment & Sign Up") +
				'</option><option value="1">' +
				SCTEXT("Contact form") +
				"</option> </select></div></div></div></div>";

			$("#subbox").append(str);

			$("#subbox")
				.find("select")
				.each(function () {
					var ph = $(this).attr("data-placeholder");
					$(this).select2({ placeholder: ph });
				});

			$("html,body").animate(
				{
					scrollTop: $("#" + idn).offset().top - 100,
				},
				"slow"
			);
			//add boxes for features
			var ftstr =
				'<div id="FT-SU-POPT-' +
				newid +
				'" class="panel panel-info"><div class="panel-heading">- PLAN</div><div class="panel-body"><div class="clearfix"><div class="col-md-4 splanft-item"><div class="widget"><header class="widget-header"><h4 class="widget-title">' +
				SCTEXT("Allowed SMS Types") +
				'</h4></header><hr class="widget-separator"><div class="widget-body"><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][flash]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked"><label>Flash</label></div><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][wap]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked"> <label>WAP-Push</label></div><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][vcard]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked"><label>VCard</label></div><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][unicode]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked"><label>Unicode</label></div><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][per]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked"><label>Personalised SMS</label></div></div></div></div><div class="col-md-4 splanft-item"><div class="widget"><header class="widget-header"><h4 class="widget-title">' +
				SCTEXT("Allowed API access") +
				'</h4></header><hr class="widget-separator"><div class="widget-body"><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][hapi]" type="checkbox" data-switchery data-color="#ff5b5b" checked="checked"><label>HTTP API</label></div><div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
				idn +
				'][xapi]" type="checkbox" data-switchery data-color="#ff5b5b" checked="checked"><label>XML API</label></div></div></div></div><div class="col-md-4 splanft-item"><div class="widget"><header class="widget-header"><h4 class="widget-title">' +
				SCTEXT("Allowed Refunds") +
				'</h4></header><hr class="widget-separator"><div class="widget-body">';

			$("#reftypebox")
				.find("input")
				.each(function () {
					var refid = $(this).attr("data-refid");
					var ref = $(this).attr("data-ref");
					ftstr +=
						'<div class="m-b-xs m-r-xl"><input data-size="small" name="sftperm[' +
						idn +
						"][ref][" +
						refid +
						']" type="checkbox" data-switchery data-color="#10c469" checked="checked"><label>' +
						ref +
						"</label></div>";
				});

			ftstr += "</div></div></div></div></div></div>";
			$("#subftbox").append(ftstr);

			$("#FT-SU-POPT-" + newid)
				.find("input")
				.each(function () {
					var color = $(this).attr("data-color");
					var size = $(this).attr("data-size");
					var switchery = new Switchery(this, {
						color: color,
						size: size,
						jackColor: "#ffffff",
					});
				});

			//end of add subs plan option
		});

		$(document).on("click", ".plan-remove", function () {
			var ele = $(this);
			bootbox.confirm({
				message: SCTEXT("You will lose the data associated with this plan option. Are you sure you want to delete?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						ele.parent().remove();
						var oid = ele.attr("data-oid");
						$("#FT-SU-POPT-" + oid).remove();
					}
				},
			});
		});

		//plan name should appear on feature selection boxes
		$(document).on("keyup", ".poptnametxt", function () {
			var oid = $(this).attr("data-oid");
			var str = $(this).val();

			$("#FT-SU-POPT-" + oid)
				.find(".panel-heading")
				.text(str + " - PLAN");
		});

		$(document).on("keyup", ".rangeto", function () {
			var elem = $(this);
			var parelem = elem.parent().parent().parent().next();
			parelem.find(".bg-white").val(parseInt(elem.val() == "" || isNaN(elem.val()) ? 0 : elem.val()) + 1);
		});

		//next steps
		$(".nextStep").on("click", function () {
			var curstep = $(this).attr("data-step");
			if (curstep == "1") {
				//validate step 1
				if ($("#pname").val() == "") {
					bootbox.alert(SCTEXT("Please enter a name for this SMS Plan."));
					return;
				}

				if ($("#proutes").val() == null) {
					bootbox.alert(SCTEXT("Please choose at least one Route."));
					return;
				}

				//move to step 2
				$("#step-1-form").hide("slide", { direction: "left" }, 500, function () {
					$(".step-1").removeClass("active");
					$(".step-2").addClass("active");
					$("#step-2-form").removeClass("hidden").show("slide", { direction: "right" }, 400);
				});
			}

			if (curstep == "2") {
				//validate step 2
				var haserr2 = 0;
				if ($("input[name=ptype]:checked", "#add_splan_form").val() == 0) {
					/* $("#step-2-form").find(".rangeto").each(function(){
                       var elem = $(this);
                        if(elem.val()==''||isNaN(elem.val())){
                            elem.addClass("error-input");
                            haserr2++;
                        }else{
                            elem.removeClass("error-input");
                        }
                    });*/
				} else {
				}

				if (haserr2 > 0) {
					bootbox.alert(SCTEXT("Please enter correct values in all the fields"));
					haserr2 = 0;
					return;
				} else {
					//move to step 3
					$("#step-2-form").hide("slide", { direction: "left" }, 500, function () {
						$(".step-2").removeClass("active");
						$(".step-3").addClass("active");
						$("#step-3-form").removeClass("hidden").show("slide", { direction: "right" }, 400);
					});
				}
			}

			if (curstep == "3") {
				//validate step 3

				//submit the form
				var dialog = bootbox.dialog({
					closeButton: false,
					message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
						"Saving SMS Plan"
					)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
				});
				dialog.init(function () {
					$("#stbar").animate({ width: "100%" }, 300);
					setTimeout(function () {
						$("#add_splan_form").attr("action", app_url + "saveSmsPlan");
						$("#add_splan_form").submit();
					}, 100);
				});
			}
		});

		//prev steps
		$(".prevStep").on("click", function () {
			if ($(this).attr("data-step") == "2") {
				$("#step-2-form").hide("slide", { direction: "right" }, 400, function () {
					$(".step-2").removeClass("active");
					$(".step-1").addClass("active");
					$("#step-1-form").removeClass("hidden").show("slide", { direction: "left" }, 400);
				});
			}
			if ($(this).attr("data-step") == "3") {
				$("#step-3-form").hide("slide", { direction: "right" }, 400, function () {
					$(".step-3").removeClass("active");
					$(".step-2").addClass("active");
					$("#step-2-form").removeClass("hidden").show("slide", { direction: "left" }, 400);
				});
			}
		});

		//--
		$("#bk").click(function () {
			window.location = app_url + "manageSmsPlans";
		});
	}

	//14. Manage staff n team
	if (curpage == "manage_teams") {
		$(document).on("click", ".del_team", function () {
			var tid = $(this).attr("data-tid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this team?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "delStaffTeam/" + tid;
					}
				},
			});
		});
	}

	if (curpage == "add_team" || curpage == "edit_team") {
		$("input.toggle").change(function () {
			var box = $(this).attr("data-group") + "_roles";
			var eid = $(this).attr("id");
			var status = $("#" + eid + ":checked").length;

			if (status == 0) {
				//uncheck all
				$("#" + box)
					.find("input[type=checkbox]")
					.each(function () {
						$(this).prop("checked", false);
					});
			} else {
				//check all
				$("#" + box)
					.find("input[type=checkbox]")
					.each(function () {
						$(this).prop("checked", true);
					});
			}
		});

		//save
		$("#save_changes").click(function () {
			if ($("#tname").val() == "") {
				bootbox.alert(SCTEXT("Please enter the team name."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Staff Team"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#team_form").attr("action", app_url + "saveStaffTeam");
					$("#team_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageStaffTeams";
		});
	}

	if (curpage == "manage_staff") {
		$(document).on("click", ".del_staff", function () {
			var uid = $(this).attr("data-uid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this staff member?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "delStaff/" + uid;
					}
				},
			});
		});
	}

	if (curpage == "add_staff") {
		var erremail = 0;
		var errloginid = 0;
		var errphone = 0;
		var errpass = 0;
		var emsg = "";

		//submit form
		$("#save_changes").click(function () {
			//validate
			if ($("#sname").val() == "") {
				bootbox.alert(SCTEXT("Please enter the staff member name."));
				return;
			}

			if ($("#semail").val() == "" || $("#sphn").val() == "" || $("#slogin").val() == "" || $("#spass").val() == "") {
				bootbox.alert(SCTEXT("Please enter values in all the fields."));
				return;
			}

			if (erremail == 1 || errloginid == 1 || errphone == 1 || errpass == 1) {
				if (emsg == "") {
					bootbox.alert(SCTEXT("Some errors are found with your entry. Please rectify before submitting the form."));
				} else {
					bootbox.alert(emsg);
				}

				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Staff Member"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#staff_form").attr("action", app_url + "saveStaff");
					$("#staff_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageStaff";
		});

		//match passwords
		$("#spass, #spass2").on("keyup blur", function () {
			var mode = $(this).attr("data-strength");
			var val = $(this).val();

			if (mode == "weak") {
				//length
				if (val.length < 6) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
				}
			}

			if (mode == "average") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//alphabet letter
					if (!/[a-zA-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one alphabet letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
						}
					}
				}
			}

			if (mode == "strong") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//uppercase alphabet
					if (!/[A-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one uppercase letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
							//special characters
							if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val)) {
								errpass = 1;
								$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one special character."));
							} else {
								errpass = 0;
								$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
							}
						}
					}
				}
			}
			if (errpass == 0) {
				//if everything is good match both passwords
				if ($("#spass").val() != $("#spass2").val()) {
					errpass = 1;
					$("#pass-err")
						.removeClass("text-success")
						.addClass("text-danger")
						.text(SCTEXT("Passwords do not match each other. Please re-type your password"));
				} else {
					errpass = 0;
					$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
				}
			}
		});

		//validate login id
		$("#slogin").on("keyup blur", function () {
			var lid = $(this).val();
			$("#v-login").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (lid.indexOf(" ") >= 0 || lid.length < 5) {
				$("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
				errloginid = 1;
				emsg = SCTEXT("Invalid login ID. Must be at least 5 characters without spaces.");
			} else {
				$("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
				errloginid = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					async: false,
					data: { mode: "login", value: lid },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
							errloginid = 1;
							emsg = SCTEXT("Login ID already exist. Please enter a different login ID.");
						} else {
							$("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
							errloginid = 0;
							emsg = "";
						}
					},
				});
			}
		});
		//validate email
		$("#semail").on("keyup blur", function () {
			var email = $(this).val();
			$("#v-email").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (!echeck(email)) {
				$("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
				erremail = 1;
				emsg = SCTEXT("Invalid Email ID");
			} else {
				$("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
				erremail = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					async: false,
					data: { mode: "email", value: email },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
							erremail = 1;
							emsg = SCTEXT("Email ID already exist. Please enter a different email ID.");
						} else {
							$("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
							erremail = 0;
							emsg = "";
						}
					},
				});
			}
		});
		//validate phone
		$("#sphn").on("keyup blur", function () {
			var phn = $(this).val();
			$("#v-phn").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (!isValidPhone(phn)) {
				$("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
				errphone = 1;
				emsg = SCTEXT("Invalid Phone number entered.");
			} else {
				$("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
				errphone = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					async: false,
					data: { mode: "mobile", value: phn },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
							errphone = 1;
							emsg = SCTEXT("Phone number already exist. Please enter a different phone number.");
						} else {
							$("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
							errphone = 0;
							emsg = "";
						}
					},
				});
			}
		});
	}

	if (curpage == "view_staff") {
		var errpass = 1;

		//change team
		$("#changeTeam").click(function () {
			var uid = $(this).attr("data-uid");
			var tid = $("#team").val();
			$(this).attr("disabled", "disabled").css("cursor", "progress");
			$.ajax({
				url: app_url + "changeTeam",
				method: "post",
				data: { user: uid, team: tid },
				success: function () {
					window.location.reload();
				},
			});
		});
		//change rights
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Staff Rights"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#staff_form").attr("action", app_url + "saveStaffRights");
					$("#staff_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageStaff";
		});

		//delete staff
		$("#delStaff").click(function () {
			var uid = $(this).attr("data-uid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this staff member?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "delStaff/" + uid;
					}
				},
			});
		});
		//reset password
		$("#resetPass").click(function () {
			$("#resetPassBox").modal();
		});
		$(document).on("click", "#resetPassSubmit", function () {
			if (errpass != 1) {
				//no errors
				let pass1 = $("#spass").val();
				let pass2 = $("#spass2").val();
				let uid = $("#staff_uid").val();
				$(this).attr("disabled", "disabled").css("cursor", "progress");
				$.ajax({
					url: app_url + "passwordReset",
					method: "post",
					data: {
						user: uid,
						cat: "staff",
						pass1: pass1,
						pass2: pass2,
					},
					success: function () {
						window.location.reload();
					},
				});
			}
		});
		//match passwords
		$(document).on("keyup blur", "#spass, #spass2", function () {
			var mode = $(this).attr("data-strength");
			var val = $(this).val();

			if (mode == "weak") {
				//length
				if (val.length < 6) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
				}
			}

			if (mode == "average") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//alphabet letter
					if (!/[a-zA-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one alphabet letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
						}
					}
				}
			}

			if (mode == "strong") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//uppercase alphabet
					if (!/[A-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one uppercase letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
							//special characters
							if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val)) {
								errpass = 1;
								$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one special character."));
							} else {
								errpass = 0;
								$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
							}
						}
					}
				}
			}
			if (errpass == 0) {
				//if everything is good match both passwords
				if ($("#spass").val() != $("#spass2").val()) {
					errpass = 1;
					$("#pass-err")
						.removeClass("text-success")
						.addClass("text-danger")
						.text(SCTEXT("Passwords do not match each other. Please re-type your password"));
				} else {
					errpass = 0;
					$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
				}
			}
		});

		//toggle checkall
		$("input.toggle").change(function () {
			var box = $(this).attr("data-group") + "_roles";
			var eid = $(this).attr("id");
			var status = $("#" + eid + ":checked").length;

			if (status == 0) {
				//uncheck all
				$("#" + box)
					.find("input[type=checkbox]")
					.each(function () {
						$(this).prop("checked", false);
					});
			} else {
				//check all
				$("#" + box)
					.find("input[type=checkbox]")
					.each(function () {
						$(this).prop("checked", true);
					});
			}
		});
	}

	// 15. manage users

	if (curpage == "manage_users") {
		//filter for all users or admin downline only
		if ($("#usrfilter").length > 0) {
			$("#usrfilter").on("change", function () {
				var flag = $("#usrfilter option:selected").val();
				$("#t-usrlist")
					.dataTable()
					.api()
					.ajax.url(app_url + "getAllUsers?flag=" + flag)
					.load();
			});
		}
	}

	if (curpage == "add_user") {
		let mccmncflag = 0;
		let dynamic_credits_flag = 0;
		var erremail = 0;
		var errloginid = 0;
		var errphone = 0;
		var errpass = 0;
		var emsg = "";
		var errcredits = 0; //error flag so resellers cannot allot more credits than balance

		if ($("#acl_msg").length > 0) {
			$("input[name='acl_mode']").on("change", function () {
				let mode = $(this).val();
				let msg =
					mode == "0"
						? SCTEXT("Enter IP addresses that are allowed access to this account Panel and API")
						: SCTEXT("Enter IP addresses that are blocked from accessing this account Panel and API");
				if (mode == "0") {
					$("#acl_msg").removeClass("text-danger").addClass("text-primary").text(msg);
				} else {
					$("#acl_msg").removeClass("text-primary").addClass("text-danger").text(msg);
				}
			});
		}

		//based on billing type selection, perform tasks
		if ($("#acctype").length > 0) {
			$("#acctype").on("change", function () {
				let accounttype = $(this).val();

				$("#acctypeinfo").text($("#acctype option:selected").attr("data-info"));
				if (accounttype == "1") {
					//show mccmnc based plan options
					$("#ucat")
						.html(`<option value="client">${SCTEXT("Client Account")}</option>`)
						.trigger("change.select2");
					$("#creditbox").hide("fade", function () {
						$("#dynamicbox").hide("fade", function () {
							$("#currencybox").show();
						});
					});
					mccmncflag = 1;
					dynamic_credits_flag = 0;
				} else if (accounttype == "2") {
					//show currency based plan options
					$("#ucat")
						.html(`<option value="client">${SCTEXT("Client Account")}</option>`)
						.trigger("change.select2");
					$("#currencybox").hide("fade", function () {
						$("#creditbox").hide("fade", function () {
							$("#dynamicbox").show();
						});
					});
					mccmncflag = 0;
					dynamic_credits_flag = 1;
				} else {
					//show credit based billing options
					$("#ucat")
						.html(`<option value="client">${SCTEXT("Client Account")}</option><option value="reseller">${SCTEXT("Reseller Account")}</option>`)
						.trigger("change.select2");
					$("#dynamicbox").hide("fade", function () {
						$("#currencybox").hide("fade", function () {
							$("#creditbox").show();
						});
					});
					mccmncflag = 0;
					dynamic_credits_flag = 0;
				}
			});

			//plan selection
			$("#mccmncplansxdswa2").on("change", function () {
				$("#mplanscredits").trigger("blur");
			});
		}

		//for mcc mnc user, based on selected plan calculate total payable
		if ($("#currencybox").length > 0) {
			$(document).on("keyup blur input", "#mplanscredits", function () {
				//only numbers allowed
				if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
					$(this).addClass("error-input");
					e.preventDefault();
					return;
				} else {
					$(this).removeClass("error-input");
				}
				let tax = $("#mccmncplans option:selected").attr("data-ptax");
				let taxtype = $("#mccmncplans option:selected").attr("data-taxtype");
				let credits = parseFloat($("#mplanscredits").val()) || 0;
				let gtotal = 0;
				let taxstr = "including all taxes";
				if (parseFloat(tax) > 0) {
					gtotal = (credits + parseFloat(credits * (tax / 100))).toFixed(2);
					let taxes = [
						{ tax: "GT", str: "GST" },
						{ tax: "VT", str: "VAT" },
						{ tax: "ST", str: "Service Tax" },
						{ tax: "SC", str: "Service Charges" },
						{ tax: "OT", str: "Other Taxes" },
					];
					let taxtypestr = taxes.find((item) => item.tax == taxtype);
					taxstr = `including ${tax}% ${taxtypestr.str}`;
				} else {
					gtotal = credits.toFixed(2);
				}
				$("#total_payable").text(
					new Number(gtotal).toLocaleString("en-US", {
						minimumFractionDigits: 2,
					})
				);
				$("#mcc_taxes").text(`(${taxstr})`);
			});

			$(document).on("keyup blur input", "#curcredits", function () {
				//only numbers allowed
				if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
					$(this).addClass("error-input");
					e.preventDefault();
					return;
				} else {
					$(this).removeClass("error-input");
				}
				let tax = parseFloat($("#utax_cur").val()) || 0;
				let taxtype = "";
				let credits = parseFloat($("#curcredits").val()) || 0;
				let gtotal = 0;
				let taxstr = "including all taxes";
				if (tax > 0) {
					gtotal = (credits + parseFloat(credits * (tax / 100))).toFixed(2);
				} else {
					gtotal = credits.toFixed(2);
				}
				$("#total_amt_cur").text(
					new Number(credits).toLocaleString("en-US", {
						minimumFractionDigits: 2,
					})
				);
				$("#grand_total_amt_cur").text(
					new Number(gtotal).toLocaleString("en-US", {
						minimumFractionDigits: 2,
					})
				);
				$("#cur_taxes").text(`(${taxstr})`);
			});
		}

		//match passwords
		$("#upass, #upass2").on("keyup blur", function () {
			var mode = $(this).attr("data-strength");
			var val = $(this).val();

			if (mode == "weak") {
				//length
				if (val.length < 6) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
				}
			}

			if (mode == "average") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//alphabet letter
					if (!/[a-zA-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one alphabet letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
						}
					}
				}
			}

			if (mode == "strong") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//uppercase alphabet
					if (!/[A-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one uppercase letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
							//special characters
							if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val)) {
								errpass = 1;
								$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one special character."));
							} else {
								errpass = 0;
								$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
							}
						}
					}
				}
			}
			if (errpass == 0) {
				//if everything is good match both passwords
				if ($("#upass").val() != $("#upass2").val()) {
					errpass = 1;
					$("#pass-err")
						.removeClass("text-success")
						.addClass("text-danger")
						.text(SCTEXT("Passwords do not match each other. Please re-type your password"));
				} else {
					errpass = 0;
					$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
				}
			}
		});
		//validate login id
		$("#ulogin").on("keyup blur", function () {
			var lid = $(this).val();
			$("#v-login").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (lid.indexOf(" ") >= 0 || lid.length < 5) {
				$("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
				errloginid = 1;
				emsg = SCTEXT("Invalid login ID. Must be at least 5 characters without spaces.");
			} else {
				$("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
				errloginid = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					data: { mode: "login", value: lid },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
							errloginid = 1;
							emsg = SCTEXT("Login ID already exist. Please enter a different login ID.");
						} else {
							$("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
							errloginid = 0;
							emsg = "";
						}
					},
				});
			}
		});
		//validate email
		$("#uemail").on("keyup blur", function () {
			var email = $(this).val();
			$("#v-email").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (!echeck(email)) {
				$("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
				erremail = 1;
				emsg = SCTEXT("Invalid Email ID");
			} else {
				$("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
				erremail = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					data: { mode: "email", value: email },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
							erremail = 1;
							emsg = SCTEXT("Email ID already exist. Please enter a different email ID.");
						} else {
							$("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
							erremail = 0;
							emsg = "";
						}
					},
				});
			}
		});
		//validate phone
		$("#uphn").on("keyup blur", function () {
			var phn = $(this).val();
			$("#v-phn").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (!isValidPhone(phn)) {
				$("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
				errphone = 1;
				emsg = SCTEXT("Invalid Phone number entered.");
			} else {
				$("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
				errphone = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					data: { mode: "mobile", value: phn },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
							errphone = 1;
							emsg = SCTEXT("Phone number already exist. Please enter a different phone number.");
						} else {
							$("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
							errphone = 0;
							emsg = "";
						}
					},
				});
			}
		});

		//assign revoke routes

		$(document).on("change", ".route-sel", function () {
			let ele = $(this);
			let dbox = `#rtcredetail-${ele.attr("data-rid")}`;
			if ($(this).is(":checked")) {
				$(dbox).collapse("show");
			} else {
				$(dbox).collapse("hide");
			}
			//calculate
			$("input.rtcredits").trigger("blur");
		});
		$(document).on("change", ".route-sel-cur", function () {
			let ele = $(this);
			let dbox = `#rtcurdetail-${ele.attr("data-rid")}`;
			if ($(this).is(":checked")) {
				$(dbox).collapse("show");
			} else {
				$(dbox).collapse("hide");
			}
			//calculate
			$("input.rtcredits").trigger("blur");
		});

		//switch sms plans
		$("#plan").change(function () {
			var planid = $(this).val();
			var ptype = $("#plan option:selected").attr("data-type");
			var rts = $("#plan option:selected").attr("data-rts");
			//get plan options
			$.ajax({
				url: app_url + "getSelPlanOptions",
				method: "post",
				data: { planid: planid, ptype: ptype, routes: rts },
				success: function (res) {
					//make total & grand total zero
					$("#total_amt, #grand_total_amt").text("0.00");
					$("#udis, #utax").val("");
					$("#plan_taxes").text("");

					if (ptype == "0") {
						//volume based
						var resar = [];
						var rtdata = [];
						resar = JSON.parse(res);
						rtdata = resar["opt_data"];
						var str = "";
						let ctr = 0;
						for (rid in rtdata) {
							if (planid == 0) {
								//custom rates
								str += `<div class="po-ctr m-b-sm" data-rid="${rid}"><div><input data-switchery data-color="#10c469" data-size="small" id="rtsel-${rid}" data-rid="${rid}" class="route-sel" name="route[${rid}]" type="checkbox" ${
									ctr == 0 ? "checked" : ""
								} > <label for="rtsel-${rid}"> ${rtdata[rid]["title"]}</label></div><div id="rtcredetail-${rid}" class="clearfix collapse ${
									ctr == 0 ? "in" : ""
								}"><table class="wd100 table row-border"><thead><tr><th>${SCTEXT("SMS Credits")}</th><th>${SCTEXT(
									"Per SMS Cost"
								)}</th> </tr> </thead><tbody><tr> <td> <div class="input-group"><input id="rtcre-${rid}" class="rtcredits form-control input-small-sc input-sm" name="credits[${rid}]" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">sms</span></div></td><td> <div class="input-group"><span class="input-group-addon"> ${app_currency} </span><input id="rtprc-${rid}" class="rtrates form-control input-small-sc input-sm" name="price[${rid}]" placeholder="e.g. 0.05" value="" type="text"></div></td></tr></tbody></table> </div></div>`;
							} else {
								//predefined rates
								str += `<div class="po-ctr m-b-sm" data-rid="${rid}"><div><input data-switchery data-color="#10c469" data-size="small" id="rtsel-${rid}" data-rid="${rid}" class="route-sel" name="route[${rid}]" type="checkbox" ${
									ctr == 0 ? "checked" : ""
								} > <label for="rtsel-${rid}"> ${rtdata[rid]["title"]}</label></div><div id="rtcredetail-${rid}" class="clearfix collapse ${
									ctr == 0 ? "in" : ""
								}"><table class="wd100 table row-border"><thead><tr><th>${SCTEXT("SMS Credits")}</th><th>${SCTEXT(
									"Per SMS Cost"
								)}</th> </tr> </thead><tbody><tr> <td> <div class="input-group"><input id="rtcre-${rid}" data-pid="${planid}" class="rtcredits form-control input-small-sc input-sm" name="credits[${rid}]" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">sms</span></div></td><td> <div class="input-group"><span class="input-group-addon"> ${app_currency} </span><input id="rtprc-${rid}" class="rtrates form-control input-small-sc input-sm" name="price[${rid}]" placeholder="e.g. 0.05" readonly value="${
									rtdata[rid]["price"]
								}" type="text"></div></td></tr></tbody></table> </div></div>`;
							}
							ctr++;
						}
						$("div.route-assign-ctr").html(str);
						$("#routes-n-credits")
							.find(".control-label")
							.each(function () {
								$(this).text(SCTEXT("Routes & Credits") + ":");
							});
						$("#ptype").val("0");
						createSwitches("route-sel");
					} else {
						//subscription based
						var resar = [];
						var pdata = [];
						resar = JSON.parse(res);
						pdata = resar["opt_data"];
						var str = '<select name="plan_option" id="popt-sel" class="form-control" data-plugin="select2"><option></option>';
						for (idn in pdata) {
							var prc = pdata[idn]["cycle"] == "m" ? app_currency + pdata[idn]["fee"] + " per month" : app_currency + pdata[idn]["fee"] + " per year";
							str += '<option value="' + idn + '">' + pdata[idn]["name"] + " Plan ( " + prc + " )</option>";
						}
						str += "</select>";
						$("div.route-assign-ctr").html(str);
						$("div.route-assign-ctr")
							.find("#popt-sel")
							.each(function () {
								$(this).select2({
									placeholder: SCTEXT("Choose Plan Option") + " . . .",
								});
							});
						$("#routes-n-credits")
							.find(".control-label")
							.each(function () {
								$(this).text(SCTEXT("Plan Options:"));
							});
						$("#ptype").val("1");
					}
				},
			});
		});

		//change total and grand total when sms credits or price change
		$(document).on("keyup blur input", ".rtcredits, .rtrates", function (e) {
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
				$(this).addClass("error-input");
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
			}
			//for each selected route get credits entered and calculate final price
			var routencredits = [];
			var pid = parseInt($("#plan").val());
			$("#routes-n-credits")
				.find(".po-ctr")
				.each(function () {
					var ele = $(this);
					var rid = ele.attr("data-rid");
					if ($("#rtsel-" + rid).is(":checked")) {
						//route assigned
						var rdata = {
							id: rid,
							credits: $("#rtcre-" + rid).val(),
							price: $("#rtprc-" + rid).val(),
						};
						routencredits.push(rdata);
					}
				});

			//send these data and get sms rate applicable for this volume
			$.ajax({
				url: app_url + "getPlanSmsPrice",
				method: "post",
				data: {
					plan: pid,
					routesData: JSON.stringify(routencredits),
					discount: $("#udis").val(),
					dtype: $("#distype").val(),
					addTax: $("#utax").val(),
				},
				success: function (res) {
					var myarr = [];
					myarr = JSON.parse(res);
					//you have the price and credits entered

					//update the rate received from the db in case a plan is chosen
					if (pid != "0") {
						for (grid in myarr.price) {
							$("#rtprc-" + grid).val(myarr.price[grid].price);
						}
					}

					var plan_cost = myarr.total_plan;
					var ptax = myarr.plan_tax;
					var gtotal = myarr.grand_total;

					//put plan total
					$("#total_amt").text(plan_cost);

					//put tax declaration
					$("#plan_taxes").text(ptax);

					//put grand total
					$("#grand_total_amt").text(gtotal);

					//check error
					if (myarr.errcredits == "1") {
						errcredits = 1;
					} else {
						errcredits = 0;
					}
				},
			});
		});

		//change total and grand total when plan option is selected
		$(document).on("change", "#popt-sel", function () {
			var pid = $("#plan").val();
			var idn = $(this).val();
			//send the idn additional taxes and discount to server
			$.ajax({
				url: app_url + "getPlanSmsPrice",
				method: "post",
				data: {
					mode: "sub",
					plan: pid,
					idn: idn,
					discount: $("#udis").val(),
					dtype: $("#distype").val(),
					addTax: $("#utax").val(),
				},
				success: function (res) {
					var myarr = [];
					myarr = JSON.parse(res);
					//you have the price and tax

					var plan_cost = myarr.total_plan;
					var ptax = myarr.plan_tax;
					var gtotal = myarr.grand_total;

					//put plan total
					$("#total_amt").text(plan_cost);

					//put tax declaration
					$("#plan_taxes").text(ptax);

					//put grand total
					$("#grand_total_amt").text(gtotal);
				},
			});
		});

		//addtnal tax n discount change event
		$("#utax, #udis").on("keyup blur", function () {
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
				$(this).addClass("error-input");
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
			}

			if ($("#ptype").val() == "1") {
				//subs based
				$("#popt-sel").trigger("change");
			} else {
				//vol based
				$("input.rtcredits").trigger("blur");
			}
		});

		$("#distype").on("change", function () {
			if ($("#ptype").val() == "1") {
				//subs based
				$("#popt-sel").trigger("change");
			} else {
				//vol based
				$("input.rtcredits").trigger("blur");
			}
		});

		$("#utax_cur, #udis_cur").on("keyup blur", function () {
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
				$(this).addClass("error-input");
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
			}
			$("#curcredits").trigger("blur");
		});

		$("#distype_cur").on("change", function () {
			$("#curcredits").trigger("blur");
		});

		//show remarks box for prepaid accounts
		$("#inv-p, #inv-d").click(function () {
			if ($(this).val() == "1") {
				$("#invrmbox").removeClass("hidden");
			} else {
				$("#invrmbox").addClass("hidden");
			}
		});

		//activation date
		if ($("#acts_dp").length > 0) {
			$("#acts_dp").datetimepicker({
				minDate: moment(),
				showClose: true,
				icons: {
					close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
				},
				sideBySide: true,
				toolbarPlacement: "bottom",
			});
			$("#acte_dp").datetimepicker({
				minDate: moment(),
				showClose: true,
				useCurrent: false,
				icons: {
					close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
				},
				sideBySide: true,
				toolbarPlacement: "bottom",
			});
			$("#acte_dp, #acts_dp").on("dp.show", function (e) {
				$(".dpclose").html("<i class='fa fa-lg fa-check'></i>&nbsp;&nbsp;Done");
			});
		}

		//submit
		$("#save_changes").click(function () {
			//validate all fields
			if ($("#uname").val() == "") {
				bootbox.alert(SCTEXT("Please enter name of the user."));
				return;
			}

			if ($("#uemail").val() == "" || $("#uphn").val() == "" || $("#ulogin").val() == "" || $("#upass").val() == "") {
				bootbox.alert(SCTEXT("Please enter values in all the fields."));
				return;
			}

			if (erremail == 1 || errloginid == 1 || errphone == 1 || errpass == 1) {
				if (emsg == "") {
					bootbox.alert(SCTEXT("Some errors are found with your entry. Please rectify before submitting the form."));
				} else {
					bootbox.alert(emsg);
				}

				return;
			}

			if (errcredits == 1) {
				bootbox.alert(SCTEXT("You cannot assign more credits than available in your account."));
				return;
			}

			//cannot assign subscription based plan for reseller
			if ($("#ptype").val() == "1" && $("#ucat").val() == "reseller") {
				bootbox.alert(
					SCTEXT("Subscription based plans cannot be assigned to reseller accounts. Please choose a different plan or assign custom SMS rates.")
				);
				return;
			}

			//if vol based pricing make sure at least one route is assigned properly
			if (mccmncflag == 0) {
				if ($("#ptype").val() == "0") {
					var $creditserr = 0;
					if (dynamic_credits_flag == 0) {
						$("#routes-n-credits")
							.find(".po-ctr")
							.each(function () {
								var ele = $(this);
								var rid = ele.attr("data-rid");
								if ($("#rtsel-" + rid).is(":checked")) {
									//route assigned
									if ($("#rtcre-" + rid).val() == "") {
										$("#rtcre-" + rid).addClass("error-input");
										$creditserr = 1;
										$cemsg = SCTEXT("Please enter the credits to be assigned. If you do not wish to assign this route, uncheck the Route name.");
										return false; //break
									} else {
										$("#rtcre-" + rid).removeClass("error-input");
										$creditserr = 0;
									}
									//price
									if ($("#rtprc-" + rid).val() == "" || $("#rtprc-" + rid).val() == 0) {
										$("#rtprc-" + rid).addClass("error-input");
										$creditserr = 1;
										$cemsg = "Please enter the sms rate. This cannot be blank or zero";
										return false; //break
									} else {
										$("#rtprc-" + rid).removeClass("error-input");
										$creditserr = 0;
									}
								}
							});
					} else {
						$("#routes-n-credits-cur")
							.find(".po-ctr")
							.each(function () {
								var ele = $(this);
								var rid = ele.attr("data-rid");
								if ($("#rtcursel-" + rid).is(":checked")) {
									//route assigned

									//price
									if ($("#rtcurprc-" + rid).val() == "" || $("#rtcurprc-" + rid).val() == 0) {
										$("#rtcurprc-" + rid).addClass("error-input");
										$creditserr = 1;
										$cemsg = "Please enter the sms rate. This cannot be blank or zero";
										return false; //break
									} else {
										$("#rtcurprc-" + rid).removeClass("error-input");
										$creditserr = 0;
									}
								}
							});
					}

					if ($creditserr == 1) {
						bootbox.alert($cemsg);
						return;
					}
				} else {
					//if subscriptn based make sure plan option is selected
					if ($("#popt-sel").val() == 0 || $("#popt-sel").val() == null) {
						bootbox.alert("Please select one subscription plan option for this user");
						return;
					}
				}
			} else {
				//check if invalid credits
				if ($("#mplanscredits").val() == "" || parseFloat($("#mplanscredits").val()) <= 0) {
					bootbox.alert(SCTEXT("Please enter valid credits. This cannot be blank or zero"));
					return;
				}
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT("Creating Account")}. . . ${SCTEXT(
					"This might take a few seconds."
				)}</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#user_form").attr("action", app_url + "createUserAccount");
					$("#user_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageUsers";
		});
	}

	//16. Website Management
	if (curpage == "gen_web_set") {
		//submit form
		$("#save_changes").click(function () {
			//validate
			if ($("#comname").val() == "") {
				bootbox.alert(SCTEXT("Please enter name of the company."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Website Settings"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#set_form").attr("action", app_url + "saveWebSettings");
					$("#set_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "genWebSettings";
		});
	}

	if (curpage == "sig_web_set") {
		//submit form
		$("#save_changes").click(function () {
			//validate
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($("#smsrate").val()) && $("#smsrate").val() != "") {
				$("#smsrate").addClass("error-input");
				bootbox.alert(SCTEXT("Please enter valid SMS rate"));
				return;
			} else {
				$("#smsrate").removeClass("error-input");
			}

			if (!isPositiveInteger($("#frecre").val())) {
				bootbox.alert(SCTEXT("Please enter valid SMS credits"));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Sign up Settings"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#sig_form").attr("action", app_url + "saveSignupSettings");
					$("#sig_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "signupWebSettings";
		});
	}

	if (curpage == "thm_web_set") {
		//color options
		$("a.color").each(function () {
			var ele = $(this);
			ele.css("background-color", ele.attr("data-color"));
			if ($("#ccode").val() == ele.attr("data-color")) {
				ele.addClass("activeColor");
			}
		});
		//change color
		$(document).on("click", "a.color", function () {
			var ele = $(this);
			var pele = ele.parent().parent();
			//remove the check
			pele.find("a.color").each(function () {
				$(this).removeClass("activeColor").html("");
			});
			ele.addClass("activeColor").html('<i class="fa fa-lg fa-check fa-inverse"></i>');
		});
		//apply theme
		$(document).on("click", ".btn-primary", function () {
			var btn = $(this);
			var colele = btn.prev().prev().find("a.activeColor");
			$("#cname").val(colele.attr("data-code"));
			$("#ccode").val(colele.attr("data-color"));
			$("#tname").val(btn.attr("data-theme"));

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Applying selected theme"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#thm_form").attr("action", app_url + "updateThemeSettings");
					$("#thm_form").submit();
				}, 200);
			});
		});
	}

	if (curpage == "home_web_set") {
		//banner upload
		$(document).on("change", ".sld-file", function () {
			var ele = $(this);
			ele.next().next().html(ele.val());
		});
		//twg yes no
		$("#twg-y, #twg-n").click(function () {
			var sel = $(this).val();
			if (sel == "1") {
				$("#twg-opts").removeClass("disabledBox");
			} else {
				$("#twg-opts").addClass("disabledBox");
			}
		});
		//slider yes no
		$("#sld-y, #sld-n").click(function () {
			var sel = $(this).val();
			if (sel == "1") {
				$("#sld-opts").removeClass("disabledBox");
			} else {
				$("#sld-opts").addClass("disabledBox");
			}
		});
		//submit
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Please wait. Saving Home page Settings"
				)} . . This may take a while</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 1500);
				setTimeout(function () {
					$("#set_form").attr("action", app_url + "saveWebPageSettings");
					$("#set_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "homeWebSettings";
		});
	}

	if (curpage == "about_web_set") {
		//submit
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving About page Settings"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#set_form").attr("action", app_url + "saveWebPageSettings");
					$("#set_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "aboutWebSettings";
		});
	}

	if (curpage == "pricing_web_set") {
		//submit
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Pricing page Settings"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#set_form").attr("action", app_url + "saveWebPageSettings");
					$("#set_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "pricingWebSettings";
		});
	}

	if (curpage == "contact_web_set") {
		//submit
		$("#save_changes").click(function () {
			if ($("#qmail").val() == "") {
				bootbox.alert(SCTEXT("Email cannot be empty."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Contact page Settings"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#set_form").attr("action", app_url + "saveWebPageSettings");
					$("#set_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "contactWebSettings";
		});
	}

	if (curpage == "login_web_set") {
		//submit
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Login page Settings"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#set_form").attr("action", app_url + "saveWebPageSettings");
					$("#set_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "loginWebSettings";
		});
	}

	//17. Client Dashboard

	if (curpage == "client_dashboard") {
		//load notifs
		loadAppAlerts();
		//----- load top sms stats ----//
		//load sms stats
		let url = `${app_url}hypernode/search/ministats`;
		let topstats = {
			mode: "top_sms_stats",
			page: "client",
		};
		$.ajax({
			url: url,
			method: "POST",
			dataType: "json",
			contentType: "application/json",
			beforeSend: function (xhr) {
				//Include the bearer token in header
				xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
			},
			data: JSON.stringify(topstats),
			crossDomain: true,
			headers: {
				accept: "application/json",
				"Access-Control-Allow-Origin": "*",
			},
			success: function (res) {
				$("#weekly_sms_ctr").html(res.statsData.total_last_seven);
				$("#monthly_sms_ctr").html(res.statsData.total_last_thirty);
				if ($("#account_type").val() == "0") {
					$("#weekly_cre_ctr").html(res.statsData.last_seven_credits);
					$("#monthly_cre_ctr").html(res.statsData.last_thirty_credits);
					$("#wk_cre_chart").sparkline(res.statsData.last_seven_credits_daily, {
						type: "bar",
						barColor: "#ffffff",
						barWidth: 3,
						barSpacing: 2,
					});
					$("#mn_cre_chart").sparkline(res.statsData.last_thirty_credits_daily, {
						type: "bar",
						barColor: "#ffffff",
						barWidth: 2,
						barSpacing: 1.5,
					});
				} else {
					$("#weekly_cre_ctr").html(`${app_currency}${res.statsData.last_seven_cost}`);
					$("#monthly_cre_ctr").html(`${app_currency}${res.statsData.last_thirty_cost}`);
					$("#wk_cre_chart").sparkline(res.statsData.last_seven_cost_daily, {
						type: "bar",
						barColor: "#ffffff",
						barWidth: 3,
						barSpacing: 2,
					});
					$("#mn_cre_chart").sparkline(res.statsData.last_thirty_cost_daily, {
						type: "bar",
						barColor: "#ffffff",
						barWidth: 2,
						barSpacing: 1.5,
					});
				}

				$("#wk_sm_chart").sparkline(res.statsData.last_seven_sms, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 3,
					barSpacing: 2,
				});
				$("#mn_sm_chart").sparkline(res.statsData.last_thirty_sms, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 2,
					barSpacing: 1.5,
				});
			},
			error: function (err) {
				console.log(err);
			},
		});

		//----------------//
		if ($("#account_type").val() != "1") {
			$("#clientrtdp").daterangepicker(
				{
					ranges: {
						Today: ["today", "today"],
						Yesterday: ["yesterday", "yesterday"],
						"Last 7 Days": [
							Date.today().add({
								days: -6,
							}),
							"today",
						],
						"Last 30 Days": [
							Date.today().add({
								days: -29,
							}),
							"today",
						],
						"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
						"Last Month": [
							Date.today().moveToFirstDayOfMonth().add({
								months: -1,
							}),
							Date.today().moveToFirstDayOfMonth().add({
								days: -1,
							}),
						],
					},
					opens: "left",
					format: "MM/dd/yyyy",
					separator: " to ",
					startDate: Date.today().add({
						days: -29,
					}),
					endDate: Date.today(),
					minDate: "01/01/2012",
					maxDate: "12/31/2030",
					locale: {
						applyLabel: "Apply",
						clearLabel: "Cancel",
						customRangeLabel: "Custom Range",
						daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
						monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						firstDay: 1,
					},
					showWeekNumbers: true,
					buttonClasses: ["btn-danger"],
				},
				function (start, end) {
					$("#clientrtdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
					//reload
					loadDashboardStats({
						mode: "clientroutessms",
						page: "client",
					});
				}
			);
			//Set the initial state of the picker label
			$("#clientrtdp span").html(
				Date.today()
					.add({
						days: -6,
					})
					.toString("MMM d, yyyy") +
					" - " +
					Date.today().toString("MMM d, yyyy")
			);

			//load routes activity
			loadDashboardStats({
				mode: "clientroutessms",
				page: "client",
			});
		}
		//recent transactions
		$.ajax({
			type: "GET",
			url: app_url + "getRecentTransactions",
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#rectransbox").fadeOut(function () {
					$(this).html(mydata.str).fadeIn();
				});
			},
		});

		//recent campaigns
		$.ajax({
			type: "GET",
			url: app_url + "getRecentCampaigns",
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#recsmsbox").fadeOut(function () {
					$(this).html(mydata.str).fadeIn();
				});
			},
		});
	}

	//18. Sender ID Mgmt

	if (curpage == "manage_sender") {
		$(document).on("click", ".del-sid", function () {
			var sid = $(this).attr("data-sid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this Sender ID?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteSender/" + sid;
					}
				},
			});
		});
	}

	if (curpage == "add_sender" || curpage == "edit_sender") {
		$("#cvsel").on("change", function () {
			//show operators only from this country
			let ele = $(this);
			let selcv = $(this).val();
			//if selcv is an empty array set the value to 0
			if (selcv == null || (Array.isArray(selcv) && (selcv.length == 0 || selcv.includes("0")))) {
				selcv = 0;
			}
			$(".opitem").each(function () {
				if (selcv == 0 || $(this).attr("data-cpre") == selcv || (Array.isArray(selcv) && selcv.includes($(this).attr("data-cpre")))) {
					$(this).attr("disabled", false);
				} else {
					$(this).attr("disabled", "disabled");
				}
			});
			console.log(selcv);
			try {
				if (selcv == 0) {
					ele.prop("multiple", false).select2("destroy").val("0").select2();
				} else {
					// Multi-select for other options
					if (!Array.isArray(selcv)) {
						ele.prop("multiple", true).select2("destroy").select2();
					}
				}
				if (selcv == 0) {
					$("#opsel").val("0").select2().trigger("change");
				} else {
					$("#opsel").select2();
				}
			} catch (e) {
				console.log(e);
			}
		});
		$("#opsel").on("change", function () {
			//show operators only from this country
			let ele = $(this);
			let selop = $(this).val();
			if (selop == null || (Array.isArray(selop) && (selop.length == 0 || selop.includes("0")))) {
				selop = 0;
			}
			console.log(selop);
			try {
				if (selop == 0) {
					ele.prop("multiple", false).select2("destroy").val("0").select2();
				} else {
					// Multi-select for other options
					if (!Array.isArray(selop)) {
						ele.prop("multiple", true).select2("destroy").select2();
					}
				}
			} catch (error) {
				console.log(error);
			}
		});
		//load coverage regulations
		if ($("#country").length > 0) {
			$("#country").on("change", function () {
				let covid = $("#country option:selected").val();
				$.ajax({
					type: "post",
					url: app_url + "getCoverageRegulations",
					data: {
						id: covid,
					},
					success: function (res) {
						$("#covregbox").html(res);
					},
				});
			});
		}
		if (curpage == "edit_sender") {
			//trigger on load
			try {
				$("#cvsel").trigger("change");
				$("#opsel").trigger("change");
			} catch (error) {
				console.log(error);
			}
		}
		//submit
		$("#save_changes").click(function () {
			if ($("#sid").val() == "") {
				bootbox.alert(SCTEXT("Sender ID cannot be blank."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Sender ID"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#sid_form").attr("action", app_url + "saveSender");
					$("#sid_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageSenderId";
		});
	}

	//19. SMS Template Mgmt

	if (curpage == "manage_templates") {
		$(document).on("click", ".del-tmp", function () {
			var tid = $(this).attr("data-tmp");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this SMS Template?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteTemplate/" + tid;
					}
				},
			});
		});
	}

	if (curpage == "add_template" || curpage == "edit_template") {
		//toggle route box
		$("#toggle-rt").change(function () {
			$("#rtbox").toggleClass("hidden");
		});
		//submit
		$("#save_changes").click(function () {
			if ($("#tname").val() == "") {
				bootbox.alert(SCTEXT("Template name cannot be blank."));
				return;
			}
			if ($("#tcont").val() == "") {
				bootbox.alert(SCTEXT("Template content cannot be blank."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving SMS Template"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#tmp_form").attr("action", app_url + "saveTemplate");
					$("#tmp_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageTemplates";
		});
	}

	//20. Contact group Mgmt

	if (curpage == "manage_groups") {
		$(document).on("click", ".del-gid", function () {
			var gid = $(this).attr("data-gid");
			bootbox.confirm({
				message: SCTEXT("All contacts from this group will be deleted. Are you sure you want to delete this contact group?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteGroup/" + gid;
					}
				},
			});
		});
	}

	if (curpage == "add_group" || curpage == "edit_group") {
		//submit
		$("#save_changes").click(function () {
			if ($("#gname").val() == "") {
				bootbox.alert(SCTEXT("Group name cannot be blank."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving contact group"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#grp_form").attr("action", app_url + "saveGroup");
					$("#grp_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageGroups";
		});
	}

	if (curpage == "move_contacts") {
		//submit
		$("#save_changes").click(function () {
			if ($("#grp").val() == 0) {
				bootbox.alert(SCTEXT("Please select a group."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Moving contacts to selected group"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#grp_form").attr("action", app_url + "saveMoveContacts");
					$("#grp_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageGroups";
		});
	}

	if (curpage == "manage_contacts") {
		$(document).on("click", ".del-cid", function () {
			var cid = $(this).attr("data-cid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this contact?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteContact/" + cid;
					}
				},
			});
		});
	}

	if (curpage == "add_contact" || curpage == "edit_contact") {
		//submit
		$("#save_changes").click(function () {
			if ($("#contactno").val() == 0 || !isValidPhone($("#contactno").val())) {
				bootbox.alert(SCTEXT("Invalid contact number entered. Please enter a proper mobile number."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Contact"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#ct_form").attr("action", app_url + "saveContacts");
					$("#ct_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "viewContacts/" + $("#groupid").val();
		});
	}

	if (curpage == "import_contact") {
		//show group columns
		$("#group").on("change", function () {
			if ($(this).val() != "") {
				$("#colbox").html(atob($("#group option:selected").attr("data-colstr")));
				$("body").tooltip({
					selector: '[data-toggle="tooltip"]',
				});
			} else {
				$("#colbox").html("- " + SCTEXT("Select a group to display column suggestions") + " -");
			}
		});

		//submit
		$("#save_changes").click(function () {
			if ($("#group").val() == "" || $("#group").val() == 0) {
				bootbox.alert(SCTEXT("Choosing a group name is required. Add a new group if you do not have any yet."));
				return;
			}
			//check if any upload is in progress
			if ($("#uprocess").val() == 1) {
				bootbox.alert(SCTEXT("File upload is in progress. Kindly wait for upload to finish or Cancel Upload & proceed."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Importing Contacts. This may take a while. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#ct_form").attr("action", app_url + "saveContacts");
					$("#ct_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageGroups";
		});
	}

	//21. Send SMS

	if (curpage == "composeSMS" || curpage == "resend_campaign" || curpage == "edit_sch_campaign") {
		var rangeErr = 0;
		let crecountrule = {};
		//show hide verified sms

		$("#stopmsg").on("click", function () {
			let stopmsg = $("#routesel option:selected").attr("data-stop");
			if (!stopmsg) {
				bootbox.alert("Please manually enter the optout message.");
				return;
			} else {
				insertAtCaret("text_sms_content", stopmsg);
				$("#text_sms_content").trigger("keyup");
			}
		});

		//compose explanation for smart schedule
		$("#sbsize").on("blur keyup", function () {
			let batchsize = $("#sbsize").val();
			let duration = $("#sbduration option:selected").text();
			let days = $("#sbdays option:selected").attr("data-desc");
			$("#sbexp").text(`Send ${parseInt(batchsize) || 0} SMS every ${duration} ${days} starting`);
		});
		$("#sbduration").on("change", function () {
			$("#sbsize").trigger("blur");
		});
		$("#sbdays").on("change", function () {
			$("#sbsize").trigger("blur");
		});

		//show hide phonebook contacts
		if ($("#syspb").length > 0) {
			//toggle
			$("input[name='contact-type']").click(function () {
				var ele = $(this);
				if (ele.val() == "my") {
					$("#phonebookbox").hide("fade", function () {
						//show my
						$("#mycontactbox").show("fade");
					});
				} else {
					$("#mycontactbox").hide("fade", function () {
						//show my
						$("#phonebookbox").show("fade");
					});
				}
				calcContacts();
			});
			//phonebook group selection
			$(document).on("click", ".pbdbsel", function () {
				//show the count
				var scount = $(this).attr("data-count");

				if (scount == "0") {
					scount = 1;
				}

				$("#pbfrom").val("1");
				$("#pbto").val(scount);

				calcContacts();
			});

			//range
			$("#pbfrom, #pbto").on("keyup blur", function (e) {
				//only numbers allowed
				if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
					$(this).addClass("error-input");
					rangeErr = 1;
					e.preventDefault();
					return;
				} else {
					$(this).removeClass("error-input");
					rangeErr = 0;
				}

				//greater than 0
				if (parseInt($(this).val()) < 1) {
					$(this).addClass("error-input");
					rangeErr = 1;
					e.preventDefault();
					return;
				}

				//within max value
				var maxval = parseInt($(".pbdbsel:checked").attr("data-count"));
				if (parseInt($(this).val()) > maxval) {
					$(this).addClass("error-input");
					rangeErr = 1;
					e.preventDefault();
					return;
				}

				//calculate contacts
				calcContacts();
			});
		}

		//toggle column box on personalize sms type
		$("#dynsms").on("change", function () {
			if ($(this).is(":checked")) {
				$("#xlcolbox").removeClass("hidden");
				$("#dyncountnotice").removeClass("hidden");
				$("#grpsel").prepend('<option id="placeholder4cgsel"></option>');
				$("#grpsel").attr("multiple", false);
				$("#grpsel")
					.select2("destroy")
					.select2({
						placeholder: SCTEXT("Select Group") + ". . . .",
					});
				$("#contactinput").attr("disabled", "disabled");
			} else {
				$("#xlcolbox").addClass("hidden");
				$("#dyncountnotice").addClass("hidden");
				$("#placeholder4cgsel").remove();
				$("#grpsel").attr("multiple", true);
				$("#grpsel")
					.select2("destroy")
					.select2({
						placeholder: SCTEXT("Select Groups") + ". . . .",
					});
				$("#grpsel").select2("val", " ");
				$("#contactinput").removeAttr("disabled");
			}
		});

		//for personalize sms click button to add column
		$(document).on("click", ".colsbtn", function () {
			var col = $(this).attr("data-colval");
			insertAtCaret("text_sms_content", "#" + col + "#");
			$("#text_sms_content").trigger("keyup");
		});

		//rtl to ltr after unicode uncheck
		$("input[type=radio][name=unicodetype]").change(function () {
			if (this.value == "uauto") {
				$("#text_sms_content").attr("dir", "ltr");
			}
		});

		//response
		if ($("#pageResp").length > 0) {
			$("#pageResp").modal({ show: true });
		}

		//validate and submit campaign
		$("#submitsms").click(function () {
			//check if phonebook selected then from and to values are valid
			if ($("#syspb").length > 0) {
				if ($("#syspb").is(":checked")) {
					var rfrom = parseInt($("#pbfrom").val());
					var rto = parseInt($("#pbto").val());

					if (rfrom > rto) {
						bootbox.alert(
							SCTEXT('Incorrect range selected for Phonebook option. "FROM" should be a lesser value than "TO" field. Your selected range was:') +
								" from-" +
								rfrom +
								" : to-" +
								rto
						);
						return;
					}
				}
			}

			//check if any upload is in progress
			if ($("#uprocess").val() == 1) {
				bootbox.alert(SCTEXT("File upload is in progress. Kindly wait for upload to finish or Cancel Upload & proceed."));
				return;
			}

			//entered sender id if required
			if ($("#routesel option:selected").attr("data-stype") == "2" && $("#senderopn").val() == "") {
				bootbox.alert(SCTEXT("Sender ID cannot be blank."));
				return;
			}
			if ($("#routesel option:selected").attr("data-stype") == "0" && !$("#sendersel").val()) {
				bootbox.alert(SCTEXT("Please select an approved sender ID."));
				return;
			}

			//if editing scheduled campaign no contacts would be actually present
			if (curpage == "edit_sch_campaign") {
				//check if total number of contacts
				if (parseInt($("#totalschno").val()) == 0) {
					bootbox.alert(SCTEXT("Please add contacts to the campaign."));
					return;
				}
				var dialog = bootbox.dialog({
					closeButton: false,
					message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
						"Saving Scheduled Campaign"
					)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
				});
				dialog.init(function () {
					$("#stbar").animate({ width: "100%" }, 200);
					setTimeout(function () {
						$("#sendsms_form").attr("action", app_url + "saveEditScheduledCampaign");
						$("#sendsms_form").submit();
					}, 100);
				});
				//------------------END of Edit Schedule Submit ----------------------//
			} else {
				//send or resend campaign
				//at least one contact
				if ($("#syspb").length > 0 && $("input[name='contact-type']:checked").val() == "pb") {
					if ($("#pbfrom").val() == "" || $("#pbto").val() == "") {
						bootbox.alert(SCTEXT("Please select a phonebook category for SMS recipient."));
						return;
					}
				} else {
					let filename = $("input.uploadedFile").val() || "";
					if ($("#contactinput").val() == "" && !$("#grpsel").val() && filename == "") {
						bootbox.alert(SCTEXT("Please enter at least one field for SMS recipient."));
						return;
					}
				}

				if (rangeErr == 1) {
					bootbox.alert(SCTEXT("Please enter contact range from Phonebook."));
					return;
				}

				//show a confirmation modal before submitting campaign
				//get sms text and sender id for preview
				$type = $("input[name='smstype']:checked").val();
				let sender = "SMS";
				let route_element_idf = $("#account_type").val() == 1 ? "#mccdefroute" : "#routesel option:selected";
				if ($(route_element_idf).attr("data-stype") == "2") {
					sender = $("#senderopn").val();
				}
				if ($(route_element_idf).attr("data-stype") == "0") {
					sender = $("#sendersel option:selected").text();
				}
				let smstype_conf = "";
				if ($type == "text") {
					smstype_conf = '<span class="label label-primary">Text</span>';
					if ($("#unicodeFlag").val() == "1") {
						smstype_conf = '<span class="label label-primary">Unicode</span>';
					}
					if ($("#flashsms").is(":checked")) {
						smstype_conf += '<span class="m-l-xs label label-info">Flash</span>';
					}
					if ($("#dynsms").is(":checked")) {
						smstype_conf += '<span class="m-l-xs label label-inverse">Personalize</span>';
						//send a GET request and pass sms text and excel filename
						$.ajax({
							type: "post",
							url: app_url + "genDynamicPreview",
							data: {
								sms: $("#text_sms_content").val(),
								filename: $('[name="uploadedFiles[]"]').attr("id"),
								xlsheet: $("#xlsheet option:selected").val(),
								xlcol: $("#xlcol option:selected").val(),
								selgrp: $("#grpsel option:selected").val(),
							},
							success: function (res) {
								$("#conf_preview_open_sender").html(sender);
								$("#conf_preview_open_msg_text").html(nl2br(res));
							},
						});
					} else {
						$("#conf_preview_open_sender").html(sender);
						$("#conf_preview_open_msg_text").html(nl2br($("#text_sms_content").val()));
					}
				} else if ($type == "wap") {
					smstype_conf = '<span class="label label-primary">WAP</span>';
					$("#conf_preview_open_sender").html(sender);
					$("#conf_preview_open_msg_text").html(`[${SCTEXT("No Preview Available")}]`);
				} else if ($type == "vcard") {
					smstype_conf = '<span class="label label-primary">vCard</span>';
					$("#conf_preview_open_sender").html(sender);
					$("#conf_preview_open_msg_text").html(`[${SCTEXT("No Preview Available")}]`);
				}
				//get route title if applicable
				let routetitle = $("#account_type").val() == 1 ? "Smart Routing" : $(route_element_idf).text();

				//get total contacts
				let totalcontacts_conf = $("#conf_total_contacts").text();
				//get total cost of campaign based on account type
				let totalcost_conf = $("#conf_total_cost").text() + ($("#account_type").val() == 0 ? " credits" : "");
				//get per sms price in terms of currency or credits based on account type
				let persms_cost = "1";
				if ($("#account_type").val() == 0) {
					persms_cost = $("#txtcount").val() + " credits";
				}
				if ($("#account_type").val() == 1) {
					persms_cost =
						app_currency +
						parseFloat(
							parseFloat($("#conf_total_cost").text().replace(app_currency, "").trim().split(",").join("")) /
								parseInt($("#conf_total_cost").text().split(",").join(""))
						);
				}
				if ($("#account_type").val() == 2) {
					persms_cost = app_currency + parseFloat($("#routesel option:selected").attr("data-rate")) * parseInt($("#txtcount").val());
				}

				//get schedule information and show as needed
				let sch_conf = "Sending Now";
				if ($("#slater").is(":checked")) {
					sch_conf = 'Send on: <span class="code"> ' + $("#schdp").val() + "</span>";
				}
				if ($("#sbatch").is(":checked")) {
					if ($("#lcnow").is(":checked")) {
						sch_conf = $("#sbexp").text() + " now.";
					} else {
						sch_conf = $("#sbexp").text() + ' on <span class="code"> ' + $("#lcdp").val() + "</span>";
					}
				}
				//fill the modal values
				$("#conf_total_contacts_modal").html(totalcontacts_conf);
				$("#conf_per_sms_price").html(persms_cost);
				$("#conf_total_cost_modal").html(totalcost_conf);
				$("#conf_sel_route").html(routetitle);
				$("#conf_sel_sid").html(sender);
				$("#conf_sms_type").html(smstype_conf);
				$("#conf_sch_info").html(sch_conf);

				$("#conf_modal").modal({ show: true });
			}
		});

		//confirmation submit
		$("body").on("click", "#conf_proceed", function () {
			let account_type = $("#account_type").val();
			let sender = "";
			let route_element_idf = account_type == 1 ? "#mccdefroute" : "#routesel option:selected";
			if ($(route_element_idf).attr("data-stype") == "2") {
				sender = $("#senderopn").val();
			}
			if ($(route_element_idf).attr("data-stype") == "0") {
				sender = $("#sendersel option:selected").text();
			}
			let tlvs = [];
			if ($("select[name='tlv[]']").length > 0) {
				$("select[name='tlv[]']").each(function () {
					tlvs.push($(this).val());
				});
			}

			let campaignData = {
				accounttype: account_type,
				route_id: account_type == 1 ? 0 : $("#routesel").val(),
				tlv: tlvs,
				campaign_id: $("#campsel").val(),
				sender_id: sender,
				contacts_data: {
					type: $("input[name='contact-type']:checked").val() == "pb" ? 1 : 0,
					list: $("#contactinput").val(),
					groups: Array.isArray($("#grpsel").val()) ? $("#grpsel").val() : [$("#grpsel").val() || 0],
					file: $("input.uploadedFile").val() || "",
					excel_data: {
						sheet: $("#xlsheet option:selected").val(),
						column: $("#xlcol option:selected").val(),
					},
					phonebook: $("input[name='contact-type']:checked").val() == "pb" ? $("input[name='pbdbsel']:checked").val() : 0,
					range: {
						to: $("#pbto").val() || 0,
						from: $("#pbfrom").val() || 0,
					},
					flags: {
						duplicate: $("#rmdup").is(":checked") ? 1 : 0,
						invalid: $("#rminv").is(":checked") ? 1 : 0,
					},
					label: $("#contact_label").val(),
				},
				sms_type: {
					main: $("input[name='smstype']:checked").val(),
					unicode: $("#unicodeFlag").val(),
					flash: $("#flashsms").is(":checked") ? 1 : 0,
					personalize: $("#dynsms").is(":checked") ? 1 : 0,
				},
				sms_content: {
					text: $("#text_sms_content").val(),
					wap: {
						title: $("#wap_sms_title").val(),
						url: $("#wap_sms_url").val(),
					},
					vcard: {
						first_name: $("#vcard_fname").val(),
						last_name: $("#vcard_lname").val(),
						phone: $("#vcard_tel").val(),
						email: $("#vcard_email").val(),
						company: $("#vcard_comp").val(),
						job: $("#vcard_job").val(),
					},
				},
				send_now: $("#snow").is(":checked") ? 1 : 0,
				schedule_data: {
					type: $("#slater").is(":checked") ? 1 : $("#sbatch").is(":checked") ? 2 : 0,
					schedule: {
						timezone: $("#timezone option:selected").val(),
						date: $("#schdp").val(),
					},
					batch: {
						size: $("#sbsize").val(),
						duration: $("#sbduration option:selected").val(),
						days: $("#sbdays option:selected").val(),
						start: $("#lcnow").is(":checked") ? 0 : $("#lcdp").val(),
					},
				},
			};
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Processing SMS . . .<br>This may take a few seconds for personalized SMS or a large campaign."
				)}</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 2000);
			});

			$("#conf_modal").modal("hide");
			$.ajax({
				type: "post",
				dataType: "json",
				url: app_url + "hypernode/sendsms/gui",
				contentType: "application/json",
				beforeSend: function (xhr) {
					//Include the bearer token in header
					xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
				},
				data: JSON.stringify(campaignData),
				crossDomain: true,
				headers: {
					accept: "application/json",
					"Access-Control-Allow-Origin": "*",
				},
				success: function (res) {
					dialog.modal("hide");
					//$("#pageResp").modal({ show: true });//remove comments for debug
					//here we update the credits and then redirect to the success page
					$.ajax({
						url: app_url + "reloadCreditData",
						success: function (cres) {
							//console.log(cres);
							if (res.spam == true) {
								window.location = app_url + "spamRedirect";
							} else {
								window.location = app_url + "campaignSuccessRedirect";
							}
						},
					});
				},
				error: function (err) {
					dialog.modal("hide");
					if (err.status == 500) {
						console.log(err);
						bootbox.alert(
							`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Something went wrong</div>`
						);
					} else {
						console.log(err.responseJSON);
						bootbox.alert(
							`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; ${err.responseJSON.message} Try Logout and Login again</div>`
						);
					}
				},
			});
		});

		//count contacts when column changes
		$("body").on("change", "#xlcol", function () {
			$sheet = $("#xlsheet option:selected").val();
			var file = $("input.uploadedFile").val();
			var coln = $(this).val();
			$.ajax({
				type: "post",
				dataType: "json",
				url: app_url + "getSheetnColumns",
				data: {
					file: file,
					mode: "colcount",
					sheet: $sheet,
					col: coln,
				},
				success: function (res) {
					var data = res;

					//append strings to proper select boxed

					$("#ufilecno").val(parseInt(data.totalrows));

					//count contacts
					calcContacts();
				},
			});
		});

		//load columns when sheet changed
		$("body").on("change", "#xlsheet", function () {
			$sheet = $(this).val();
			var file = $("input.uploadedFile").val();
			$("#xlcol").attr("disabled", "disabled");

			$.ajax({
				type: "post",
				dataType: "json",
				url: app_url + "getSheetnColumns",
				data: {
					file: file,
					mode: "columns",
					sheet: $sheet,
				},
				success: function (res) {
					var data = res;
					$cols = data.cols;

					$cols_str = "";
					$cols_btns = "";
					for (var j in $cols) {
						if ($cols[j] != null && $cols[j] != "") {
							$cols_str += '<option value="' + $cols[j] + '">' + $cols[j] + "</option>";
							$cols_btns += '<button data-colval="' + $cols[j] + '" type="button" class="colsbtn btn btn-sm btn-default">' + $cols[j] + "</button>";
						}
					}
					//append strings to proper select boxed

					$("#xlcol").html($cols_str).attr("disabled", false);
					$("#ufilecno").val(parseInt(data.totalrows));
					$("#xlcolbtns").html($cols_btns);
					//count contacts
					calcContacts();
				},
			});
		});

		//sms preview
		$("#smsprev").click(function () {
			let route_element_idf = $("#account_type").val() == 1 ? "#mccdefroute" : "#routesel option:selected";
			$type = $("input[name='smstype']:checked").val();
			let sender = "SMS";
			if ($(route_element_idf).attr("data-stype") == "2") {
				sender = $("#senderopn").val();
			}
			if ($(route_element_idf).attr("data-stype") == "0") {
				sender = $("#sendersel option:selected").text();
			}
			if ($type == "text") {
				if ($("#dynsms").is(":checked")) {
					//send a GET request and pass sms text and excel filename
					$.ajax({
						type: "post",
						url: app_url + "genDynamicPreview",
						data: {
							sms: $("#text_sms_content").val(),
							filename: $('[name="uploadedFiles[]"]').attr("id"),
							xlsheet: $("#xlsheet option:selected").val(),
							xlcol: $("#xlcol option:selected").val(),
							selgrp: $("#grpsel option:selected").val(),
						},
						success: function (res) {
							$("#preview_locked_notif_sender").html(sender);
							$("#preview_open_sender").html(sender);
							$("#preview_locked_notif_text").html(nl2br(res));
							$("#preview_open_msg_text").html(nl2br(res));
						},
					});
				} else {
					$("#preview_locked_notif_sender").html(sender);
					$("#preview_open_sender").html(sender);
					$("#preview_locked_notif_text").html(nl2br($("#text_sms_content").val()));
					$("#preview_open_msg_text").html(nl2br($("#text_sms_content").val()));
				}
			} else if ($type == "wap") {
				$("#preview_locked_notif_sender").html(sender);
				$("#preview_open_sender").html(sender);
				$("#preview_locked_notif_text").html(`[${SCTEXT("No Preview Available")}]`);
				$("#preview_open_msg_text").html(`[${SCTEXT("No Preview Available")}]`);
			} else if ($type == "vcard") {
				$("#preview_locked_notif_sender").html(sender);
				$("#preview_open_sender").html(sender);
				$("#preview_locked_notif_text").html(`[${SCTEXT("No Preview Available")}]`);
				$("#preview_open_msg_text").html(`[${SCTEXT("No Preview Available")}]`);
			}

			$("#smspreview").modal({ show: true });
		});

		//use template
		$(document).on("click", ".useTempBtn", function () {
			var tmpText = $(this).attr("data-template");
			$("#text_sms_content").val(tmpText).trigger("keyup");
		});

		//use tiny url
		$(document).on("click", ".useTurlBtn", function () {
			var turl = $(this).attr("data-turl");
			insertAtCaret("text_sms_content", "http://" + turl);
			$("#text_sms_content").trigger("keyup");
			if ($(this).attr("data-track") == "1") {
				bootbox.alert(SCTEXT("Please make sure <b>Personalized</b> box is checked. Click Tracking is available only for Personalized SMS."));
			}
		});

		//use media link
		$(document).on("click", ".useMediaBtn", function () {
			var turl = $(this).attr("data-turl");
			insertAtCaret("text_sms_content", "http://" + turl);
			$("#text_sms_content").trigger("keyup");
			bootbox.alert(
				SCTEXT("To enable CLICK tracking Please make sure <b>Personalized</b> box is checked. Click Tracking is available only for Personalized SMS.")
			);
		});

		//count credits
		$(document).on("keyup blur", "#text_sms_content", function () {
			var content = $(this).val();
			var clen = content.length;
			var totalspclchargroups = Object.keys(crecountrule.special);

			var totaloccurences = 0;
			var totalspcladd = 0;
			//check special characters
			for (let i = 1; i <= totalspclchargroups.length; i++) {
				if (parseInt(totalspclchargroups[i]) > 1) {
					//it means according to this credit count rule, there are some special characters which will be counted as more than 1 character
					for (let spclchar of crecountrule.special[totalspclchargroups[i]]) {
						if (spclchar == "clb") spclchar = "]";
						if (spclchar == "ln") spclchar = "\n";
						let occurrences = content.split(spclchar).length - 1;

						totaloccurences += occurrences;
						totalspcladd += occurrences * parseInt(totalspclchargroups[i]);
					}
				}
			}

			var totallength = clen - totaloccurences + totalspcladd;

			//check how many sms count based on sms type
			var smscount = 1;
			if (containsUnicode(content)) {
				$("#unicodeFlag").val("1");
				$("#unicodebadge").removeClass("hidden");
				//unicode sms count

				for (var j = 1; j <= 5; j++) {
					if (totallength >= crecountrule.unicode[j].from && totallength <= crecountrule.unicode[j].to) {
						//matched
						smscount = j;
						break;
					}
				}
				//check if sms count was higher than the range
				if (totallength > crecountrule.unicode[5].to) {
					//simply calculate the per sms factor
					var persms = Math.ceil(crecountrule.unicode[5].to / 5);
					smscount = Math.ceil(totallength / persms);
				}
			} else {
				$("#unicodeFlag").val("0");
				$("#unicodebadge").addClass("hidden");
				//normal sms count

				for (var j = 1; j <= 5; j++) {
					if (totallength >= crecountrule.normal[j].from && totallength <= crecountrule.normal[j].to) {
						//matched
						smscount = j;
						break;
					}
				}
				//check if sms count was higher than the range
				if (totallength > crecountrule.normal[5].to) {
					//simply calculate the per sms factor
					var persms = Math.ceil(crecountrule.normal[5].to / 5);
					smscount = Math.ceil(totallength / persms);
				}
			}

			//populate fields
			//if Doo::conf()->credit_counter_dir=='h2l'?1000-totallength:totallength
			$("#txtleft").val(totallength);
			$("#txtcount").val(smscount);

			//calculate credits required
			calcContacts();
		});

		//transliteration
		google.setOnLoadCallback(onLoad);

		//display sms content box based on sms type switch
		$("input[name='smstype']").click(function () {
			var ele = $(this);
			if (ele.val() == "text") {
				$("#stype-subopts").show("slide", { direction: "left" }, 400, function () {
					//show text box
					$("#sms_content_box").removeClass("hidden");
					$("#wap_content_box").addClass("hidden");
					$("#vcard_content_box").addClass("hidden");
				});
			} else {
				$("#stype-subopts").hide("slide", { direction: "left" }, 400, function () {
					if (ele.val() == "wap") {
						//show wap box
						$("#sms_content_box").addClass("hidden");
						$("#wap_content_box").removeClass("hidden");
						$("#vcard_content_box").addClass("hidden");
					} else if (ele.val() == "vcard") {
						//show vcard box
						$("#sms_content_box").addClass("hidden");
						$("#wap_content_box").addClass("hidden");
						$("#vcard_content_box").removeClass("hidden");
					}
				});
			}
			calcContacts();
		});

		//count contacts and total sms every time user

		//a. enters mobile number in text field
		$("#contactinput").on("blur", function () {
			calcContacts();
		});

		//b. choses contact groups
		$("#grpsel").on("change", function () {
			if ($("#dynsms").is(":checked")) {
				var colstrenc = $("#grpsel option:selected").attr("data-colstr");
				$("#xlcolbtns").html(atob(colstrenc));
			}
			$("#contact_label").val($("#grpsel option:selected").attr("data-name"));
			calcContacts();
		});
		//c. uploads a file
		//done on callback

		//d. changes sms type
		//done on click event

		//e. changes sms text
		//done on keyup

		//based on route switch show
		$("#routesel").on("change", function () {
			var rtele = $("#routesel option:selected");
			//a. available credits
			if ($("#account_type").val() == "0") {
				$("#rtavcr").html(formatInt(parseInt(rtele.attr("data-acr"))));
			} else {
				let rate = parseFloat(rtele.attr("data-rate"));
				let credits_available = $("#user_wbal").val() / rate;
				$("#activerate").val(rate);
				$("#rtavcr").html(`${formatInt(parseInt(credits_available))} <small class="text-danger">(${app_currency}${rate}/sms)<small>`);
				calcContacts();
			}

			//b. status and active time message if current time falls in the active time for route
			var actdata = JSON.parse(rtele.attr("data-actspan"));

			if (actdata.type == "0") {
				//route is active all the time
				var statushtml = `<span class="label label-success"><i class="fa fa-lg fa-check-circle"></i> &nbsp; ${SCTEXT(
					"active"
				)}</span><i id="rtstatusexp" class="m-l-xs fa fa-lg fa-info-circle" data-trigger="hover" data-content="<span class='text-dark'>${SCTEXT(
					"This Route accepts SMS 24x7. SMS submission is allowed."
				)}</span>"></i>`;
				$("#rtstatus").html(statushtml);
				$("#rtstatusexp").popover({ html: true, placement: "top" });

				//initialize datepicker
				$("#schdp").datetimepicker({
					defaultDate: $("#orgschdate").length > 0 && $("#orgschdate").val() != "" ? moment($("#orgschdate").val(), "YYYY-MM-DD HH:mm") : moment(),
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
					format: "YYYY-MM-DD HH:mm",
				});
				$("#lcdp").datetimepicker({
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
				});
				//set timezone
				let def_tz = actdata.timezone == "" || !actdata.timezone ? $("#def_sys_tz").val() : actdata.timezone;
				$("#timezone option").each(function () {
					if ($(this).val() == def_tz) {
						$(this).attr("selected", "selected");
					}
				});
				$("#timezone").trigger("change.select2");
			} else {
				//route is active during selected time period
				let def_tz = actdata.timezone == "" || !actdata.timezone ? $("#def_sys_tz").val() : actdata.timezone;
				var nowhr = moment().tz(def_tz);
				var actfhr = moment(actdata.from, "H:mm").tz(def_tz);
				var actthr = moment(actdata.to, "H:mm").tz(def_tz);

				if (nowhr.isBetween(actfhr, actthr)) {
					//sms submission allowed
					var statushtml = `<span class="label label-success"><i class="fa fa-lg fa-check-circle"></i> &nbsp; ${SCTEXT(
						"active"
					)}</span><i id="rtstatusexp" class="m-l-xs fa fa-lg fa-info-circle" data-trigger="hover" data-content="<span class='text-dark'>${SCTEXT(
						"This Route accepts SMS between"
					)} ${actdata.from} & ${actdata.to} Hrs (${def_tz} time). ${SCTEXT("SMS submission is allowed.")}</span>"></i>`;
				} else {
					//sms submission not allowed
					var statushtml = `<span class="label label-danger"><i class="fa fa-lg fa-times-circle"></i> &nbsp; ${SCTEXT(
						"inactive"
					)}</span><i id="rtstatusexp" class="m-l-xs fa fa-lg fa-info-circle" data-trigger="hover" data-content="<span class='text-dark'>${SCTEXT(
						"This Route accepts SMS between"
					)} ${actdata.from} & ${actdata.to} Hrs (${def_tz} time). ${SCTEXT("SMS submission is currently not allowed.")}</span>"></i>`;
				}

				$("#rtstatus").html(statushtml);
				$("#rtstatusexp").popover({ html: true, placement: "top" });

				//c. set timezone for scheduling and limit schedule time to select within active time

				let list = [];
				for (let i = parseInt(actdata.from); i < parseInt(actdata.to); i++) {
					list.push(i);
				}
				if ($("#schdp").data("DateTimePicker")) $("#schdp").data("DateTimePicker").destroy();
				//breakpoint 1
				$("#schdp").datetimepicker({
					defaultDate: $("#orgschdate").length > 0 ? moment($("#orgschdate").val(), "YYYY-MM-DD HH:mm") : moment(),
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
					enabledHours: list,
					format: "YYYY-MM-DD HH:mm",
				});
				$("#lcdp").datetimepicker({
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
					enabledHours: list,
				});

				$("#timezone option").each(function () {
					if ($(this).val() == def_tz) {
						$(this).attr("selected", "selected");
					}
				});
				$("#timezone").trigger("change.select2");
			}

			//d. sender id box type, default sender id and max allowed length
			var stype = parseInt(rtele.attr("data-stype"));
			if (stype == 0) {
				//approval based sender id
				$("#sidselbox").removeClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 1) {
				//sender id not allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 2) {
				//open sender id allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").removeClass("hidden");
			}

			//e. credit count rule display
			$.ajax({
				url: `${app_url}getCreditCountRuleDetails/${rtele.attr("data-crule")}`,
				success: function (res) {
					var respar = JSON.parse(res);
					crecountrule = {
						normal: respar.text,
						unicode: respar.unicode,
						special: respar.special,
					};

					$("#ccruledata").attr("data-content", respar.html).popover({
						animation: false,
						placement: "left",
						html: true,
						trigger: "hover",
					});
				},
			});
			//f. show tlv
			//pass these to get select boxes with user tlvs
			let tlv_cats = rtele.attr("data-tlvs");
			if (tlv_cats != "") {
				//render selecting box
				$.ajax({
					url: `${app_url}getUserTlvList/${rtele.val()}`,
					success: function (res) {
						let tlvdata = JSON.parse(res);
						let tlv_titles = Object.keys(tlvdata);
						if (tlv_titles.length > 0) {
							let tlv_html = ``;
							for (const tlv_name of tlv_titles) {
								let tlv_list = tlvdata[tlv_name];
								tlv_html += `<div class="form-group tlv_boxes"> <label class="control-label col-md-3">${tlv_name}:</label><div class="col-md-8"><select class="tlv_controls form-control" data-plugin="select2" name="tlv[]">`;
								//populate select boxes
								for (const tlv of tlv_list) {
									tlv_html += `<option value="${tlv_name}||${tlv.value}">${tlv.title}</option>`;
								}

								tlv_html += `</select> </div></div>`;
							}
							$(".tlv_boxes").remove();
							$("#contactMainContainer").before(tlv_html);
							$(".tlv_controls").select2();
						} else {
							$(".tlv_boxes").remove();
						}
					},
				});
			} else {
				$(".tlv_boxes").remove();
			}
		});

		//campaign selection
		if ($("#account_type").val() == "0" || $("#account_type").val() == "2") {
			$("#campsel").on("change", function () {
				var cmpele = $("#campsel option:selected");
				$("#campaignid").val(cmpele.val());
				var defrt = cmpele.attr("data-defroute");
				if (defrt != 0 && defrt != "") {
					//select the set route
					$("#routesel").val(defrt).change();
				} else {
					$("#routesel").trigger("change");
				}
			});
			$("#campsel").trigger("change");
		} else {
			$("#campsel").on("change", function () {
				var cmpele = $("#campsel option:selected");
				$("#campaignid").val(cmpele.val());
			});
			$("#campsel").trigger("change");
			var rtele = $("#mccdefroute");
			//a. available credits
			//none for currency based

			//b. status and active time message if current time falls in the active time for route
			var actdata = JSON.parse(rtele.attr("data-actspan"));

			if (actdata["type"] == "0") {
				//route is active all the time
				var statushtml = `<span class="label label-success"><i class="fa fa-lg fa-check-circle"></i> &nbsp; ${SCTEXT(
					"active"
				)}</span><i id="rtstatusexp" class="m-l-xs fa fa-lg fa-info-circle" data-trigger="hover" data-content="<span class='text-dark'>${SCTEXT(
					"This Route accepts SMS 24x7. SMS submission is allowed."
				)}</span>"></i>`;
				$("#rtstatus").html(statushtml);
				$("#rtstatusexp").popover({ html: true, placement: "top" });

				//initialize datepicker
				$("#schdp").datetimepicker({
					defaultDate: $("#orgschdate").length > 0 ? moment($("#orgschdate").val(), "YYYY-MM-DD HH:mm") : moment(),
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
					format: "YYYY-MM-DD HH:mm",
				});
				$("#lcdp").datetimepicker({
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
				});
				//set timezone
				$("#timezone option").each(function () {
					if ($(this).val() == actdata["timezone"]) {
						$(this).attr("selected", "selected");
					}
				});
				$("#timezone").trigger("change.select2");
			} else {
				//route is active during selected time period
				var nowhr = moment().tz(actdata["timezone"]);
				var actfhr = moment(actdata["from"], "H:mm").tz(actdata["timezone"]);
				var actthr = moment(actdata["to"], "H:mm").tz(actdata["timezone"]);

				if (nowhr.isBetween(actfhr, actthr)) {
					//sms submission allowed
					var statushtml = `<span class="label label-success"><i class="fa fa-lg fa-check-circle"></i> &nbsp; ${SCTEXT(
						"active"
					)}</span><i id="rtstatusexp" class="m-l-xs fa fa-lg fa-info-circle" data-trigger="hover" data-content="<span class='text-dark'>${SCTEXT(
						"This Route accepts SMS between"
					)} ${actdata["from"]} & ${actdata["to"]} Hrs (${actdata["timezone"]} time). ${SCTEXT("SMS submission is allowed.")}</span>"></i>`;
				} else {
					//sms submission not allowed
					var statushtml = `<span class="label label-danger"><i class="fa fa-lg fa-times-circle"></i> &nbsp; ${SCTEXT(
						"inactive"
					)}</span><i id="rtstatusexp" class="m-l-xs fa fa-lg fa-info-circle" data-trigger="hover" data-content="<span class='text-dark'>${SCTEXT(
						"This Route accepts SMS between"
					)} ${actdata["from"]} & ${actdata["to"]} Hrs (${actdata["timezone"]} time). ${SCTEXT(
						"SMS submission is currently not allowed."
					)}</span>"></i>`;
				}

				$("#rtstatus").html(statushtml);
				$("#rtstatusexp").popover({ html: true, placement: "top" });

				//c. set timezone for scheduling and limit schedule time to select within active time

				let list = [];
				for (let i = parseInt(actdata["from"]); i < parseInt(actdata["to"]); i++) {
					list.push(i);
				}
				if ($("#schdp").data("DateTimePicker")) $("#schdp").data("DateTimePicker").destroy();
				$("#schdp").datetimepicker({
					defaultDate: $("#orgschdate").length > 0 ? moment($("#orgschdate").val(), "YYYY-MM-DD HH:mm") : moment(),
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
					enabledHours: list,
					format: "YYYY-MM-DD HH:mm",
				});
				$("#lcdp").datetimepicker({
					minDate: moment(),
					showClose: true,
					icons: {
						close: "dpclose m-t-xs m-b-xs label-info label label-flat label-md",
					},
					sideBySide: true,
					toolbarPlacement: "bottom",
					enabledHours: list,
				});

				$("#timezone option").each(function () {
					if ($(this).val() == actdata["timezone"]) {
						$(this).attr("selected", "selected");
					}
				});
				$("#timezone").trigger("change.select2");
			}

			//d. sender id box type, default sender id and max allowed length
			var stype = parseInt(rtele.attr("data-stype"));
			if (stype == 0) {
				//approval based sender id
				$("#sidselbox").removeClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 1) {
				//sender id not allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 2) {
				//open sender id allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").removeClass("hidden");
			}

			//e. credit count rule display
			$.ajax({
				url: app_url + "getCreditCountRuleDetails/" + rtele.attr("data-crule"),
				success: function (res) {
					var respar = JSON.parse(res);
					crecountrule = {
						normal: respar.text,
						unicode: respar.unicode,
						special: respar.special,
					};
					$("#ccruledata").attr("data-content", respar.html).popover({
						animation: false,
						placement: "left",
						html: true,
						trigger: "hover",
					});
				},
			});
		}

		//datepicker close
		$("#schdp").on("dp.show", function (e) {
			$(".dpclose").html("<i class='fa fa-lg fa-check'></i>&nbsp;&nbsp;Done");
		});
		$("#lcdp").on("dp.show", function (e) {
			$(".dpclose").html("<i class='fa fa-lg fa-check'></i>&nbsp;&nbsp;Done");
		});
		//toggle schedule
		$("#slater, #snow, #sbatch").on("click", function () {
			if ($(this).val() == "1") {
				$("#sbbox").hide("slide", { direction: "left" }, 600, () => {
					$("#schbox").show("slide", { direction: "left" }, 600);
				});
			}
			if ($(this).val() == "2") {
				$("#schbox").hide("slide", { direction: "left" }, 600, () => {
					$("#sbbox").show("slide", { direction: "left" }, 600);
				});
			}
			if ($(this).val() == "0") {
				$("#schbox").hide("slide", { direction: "left" }, 600, () => {
					$("#sbbox").hide("slide", { direction: "left" }, 600);
				});
			}
		});

		if (curpage == "resend_campaign") {
			//show sms type
			var ele = $("input[name='smstype']:checked");
			if (ele.val() == "text") {
				$("#stype-subopts").show("slide", { direction: "left" }, 400, function () {
					//show text box
					$("#sms_content_box").removeClass("hidden");
					$("#wap_content_box").addClass("hidden");
					$("#vcard_content_box").addClass("hidden");
				});
			} else {
				$("#stype-subopts").hide("slide", { direction: "left" }, 400, function () {
					if (ele.val() == "wap") {
						//show wap box
						$("#sms_content_box").addClass("hidden");
						$("#wap_content_box").removeClass("hidden");
						$("#vcard_content_box").addClass("hidden");
					} else if (ele.val() == "vcard") {
						//show vcard box
						$("#sms_content_box").addClass("hidden");
						$("#wap_content_box").addClass("hidden");
						$("#vcard_content_box").removeClass("hidden");
					}
				});
			}

			//count sms char and credits required
			$(document).ajaxStop(function () {
				// code to be executed on completion of last outstanding ajax call here
				$("#text_sms_content").trigger("keyup");
			});
		}

		if (curpage == "edit_sch_campaign") {
			$("#routesel").trigger("change");
			//schedule date is set above
			//count sms char and credits required
			$(document).ajaxStop(function () {
				// code to be executed on completion of last outstanding ajax call here

				$("#text_sms_content").trigger("keyup");

				$("#timezone option").each(function () {
					if ($(this).val() == $("#orgschtz").val()) {
						$(this).attr("selected", "selected");
					}
				});
				$("#timezone").trigger("change.select2");
			});
			//show contacts datatable
			// Add Row button click event

			$("#addcontacts").click(function (e) {
				e.preventDefault(); // Prevent default action of the anchor tag
				let err = 0;
				$(".dynschinput").each(function () {
					if ($(this).val() == "") {
						$(this).addClass("error-input");
						err++;
					} else {
						$(this).removeClass("error-input");
					}
				});
				if (err > 0) {
					return false; //add more rows only if newly created one was filled
				}

				var mySchdt = $("#t-sch_contacts").DataTable();
				// Get the number of columns
				let newRowData = [];

				let colCount = mySchdt.columns().count();
				let columns = JSON.parse($("#columns").val());
				// Create input fields for first column which will always be mobile number
				if (colCount == 1) {
					newRowData.push(
						`<div class="input-group"><input type="text" name="msisdn[]" class="form-control dynschinput input-sm" style="min-width: 120px;" placeholder="Enter mobile.."><a href="javascript:void(0);" style="height: 100% !important;" class="input-group-addon btn btn-danger btn-sm removeRow">x</a></div>`
					);
				} else {
					newRowData.push('<input type="text" name="msisdn[]" class="form-control dynschinput input-sm" placeholder="Enter mobile..">');
				}

				//for each column push the input field
				for (let i = 0; i < columns.length; i++) {
					let name = $("#hasparams").val() == "1" ? `parameters[${columns[i]}][]` : `sms[]`;
					if (i == colCount - 2) {
						//because one column is already msisdno
						// Add the "X" anchor tag to the last column
						newRowData.push(
							`<div class="input-group"><input type="text" name="${name}" class="form-control dynschinput input-sm" style="min-width: 120px;" placeholder="Enter ${columns[i]}.."><a href="javascript:void(0);" style="height: 100% !important;" class="input-group-addon btn btn-danger btn-sm removeRow">x</a></div>`
						);
					} else {
						newRowData.push(`<input type="text" name="${name}" class="form-control dynschinput input-sm" placeholder="Enter ${columns[i]}..">`);
					}
				}
				mySchdt.order([[0, "asc"]]).draw(false);

				// Add the new row to the DataTable
				let newRow = mySchdt.row.add(newRowData).draw(false).node();

				// Insert the new row at the top of the table
				$(newRow).insertBefore($("#t-sch_contacts tbody tr:first"));
				$("#totalschno").val(function (i, oldValue) {
					return parseInt(oldValue) + 1;
				});
				$("#text_sms_content").trigger("keyup");

				// Add click event for the "X" anchor tag to remove the row
				$(newRow)
					.find(".removeRow")
					.click(function (e) {
						e.preventDefault();
						mySchdt.row($(this).parents("tr")).remove().draw();
						$("#totalschno").val(function (i, oldValue) {
							return parseInt(oldValue) - 1;
						});
						$("#text_sms_content").trigger("keyup");
					});
			});
			//end of show datatable
		}
	}

	//22. Transaction reports

	if (curpage == "transactions" || curpage == "va_tran_history") {
		//top sales
		$("#transdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#transdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#dt_utrans")
					.dataTable()
					.api()
					.ajax.url(app_url + "getMyTransactions/" + $("#transdp span").html() + "/" + $("#userid").val())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#transdp span").html("Select Date");
	}

	//23. View DLR

	if (curpage == "dlr" || curpage == "va_sentsms") {
		$("#campsel").on("change", function () {
			$("#t-dlrsum")
				.dataTable()
				.api()
				.ajax.url(
					app_url + "getMySmsCampaigns/" + $("#campsel").val() + "/" + $("#dlrdp span").html() + "/" + $("#userid").val() + "/" + $("#sorttype").val()
				)
				.load();
		});
		$("#sorttype").on("change", function () {
			$("#t-dlrsum")
				.dataTable()
				.api()
				.ajax.url(
					app_url + "getMySmsCampaigns/" + $("#campsel").val() + "/" + $("#dlrdp span").html() + "/" + $("#userid").val() + "/" + $("#sorttype").val()
				)
				.load();
		});

		//date pkr dlr
		$("#dlrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#dlrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				var campaignid = $("#campsel").val() || 0;
				$("#t-dlrsum")
					.dataTable()
					.api()
					.ajax.url(
						app_url + "getMySmsCampaigns/" + campaignid + "/" + $("#dlrdp span").html() + "/" + $("#userid").val() + "/" + $("#sorttype").val()
					)
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#dlrdp span").html("Select Date");

		//get dlr summary
		$("body").on("click", ".dlrsumldr", function () {
			var ele = $(this);
			var pbox = ele.next().find(".popover-content");
			var arele = ele.next().find(".arrow");
			$.ajax({
				url: app_url + "getDlrSummary/" + $("#userid").val(),
				data: {
					shootid: ele.attr("data-shootid"),
					routeid: ele.attr("data-routeid"),
					mode: "popover",
				},
				success: function (res) {
					arele.css("top", "20px");
					pbox.html(res);
				},
			});
		});

		$("body").on("click", ".closePO", function () {
			$(".dlrsumldr").popover("hide");
		});
	}

	if (curpage == "dlr_details" || curpage == "va_dlrdetails") {
		//admin summary
		if ($("#adminsummary").length > 0) {
			$("#adminsummary").on("click", function () {
				$("#adminsumctr").html(`<i class="fa-circle-o-notch fa fa-lg m-r-xs fa-spin"></i> <b>${SCTEXT("Loading")}...</b>`);
				$.ajax({
					url: app_url + "getAdminSmsSummary/" + $("#shootid").val(),
					success: function (res) {
						$("#adminsumctr").html(res);
					},
				});
			});
		}

		//fetch dlr summary
		$.ajax({
			url: app_url + "getDlrSummary/" + $("#userid").val(),
			data: {
				shootid: $("#shootid").val(),
				routeid: $("#routeid").val(),
			},
			success: function (res) {
				$("#dlrsumctr").html(res);
			},
		});
	}

	//24. doc manager
	if (curpage == "docs") {
		//delete docs
		$("#app-main").on("click", ".deleteDoc", function () {
			var ele = $(this);
			bootbox.confirm({
				message: SCTEXT(
					"Are you sure you want to delete this file? All comments/remarks will be deleted and no user will be able to access this file. Proceed anyway?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "deleteDocument/" + ele.attr("data-docid"),
							success: function (res) {
								window.location = app_url + "manageDocs";
							},
						});
					}
				},
			});
		});

		//date picker

		$("#docmgrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#docmgrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload invoices
				$.ajax({
					type: "GET",
					url: app_url + "getUserDocs/1/" + $("#docmgrdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						$("#inv-file-ctr").fadeOut(function () {
							$(this)
								.html(mydata.str)
								.fadeIn(function () {
									if (mydata.more == 1 && mydata.rows == 10) {
										$("#more_docinv")
											.removeClass("hidden")
											.attr("data-custom", "10,10")
											.html(SCTEXT("Show More") + " ...");
									} else {
										$("#more_docinv").addClass("hidden");
									}
									$("#inv-file-ctr")
										.find(".pop-over")
										.each(function () {
											$(this).popover({ html: true });
										});
								});
						});
					},
				});
				//reload agreements
				$.ajax({
					type: "GET",
					url: app_url + "getUserDocs/2/" + $("#docmgrdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						$("#apl-file-ctr").fadeOut(function () {
							$(this)
								.html(mydata.str)
								.fadeIn(function () {
									if (mydata.more == 1 && mydata.rows == 10) {
										$("#more_docapl")
											.removeClass("hidden")
											.attr("data-custom", "10,10")
											.html(SCTEXT("Show More") + " ...");
									} else {
										$("#more_docapl").addClass("hidden");
									}
								});
							$("#apl-file-ctr")
								.find(".pop-over")
								.each(function () {
									$(this).popover({ html: true });
								});
						});
					},
				});
				//reload other docs
				$.ajax({
					type: "GET",
					url: app_url + "getUserDocs/3/" + $("#docmgrdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						$("#oth-file-ctr").fadeOut(function () {
							$(this)
								.html(mydata.str)
								.fadeIn(function () {
									if (mydata.more == 1 && mydata.rows == 10) {
										$("#more_docoth")
											.removeClass("hidden")
											.attr("data-custom", "10,10")
											.html(SCTEXT("Show More ") + "...");
									} else {
										$("#more_docoth").addClass("hidden");
									}
								});
							$("#oth-file-ctr")
								.find(".pop-over")
								.each(function () {
									$(this).popover({ html: true });
								});
						});
					},
				});
			}
		);
		//Set the initial state of the picker label
		$("#docmgrdp span").html("Select Date");
		//----------------//

		// load invoices
		$.ajax({
			type: "GET",
			url: app_url + "getUserDocs/1/" + $("#docmgrdp span").html(),
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#inv-file-ctr").fadeOut(function () {
					$(this)
						.html(mydata.str)
						.fadeIn(function () {
							if (mydata.more == 1 && mydata.rows == 10) {
								$("#more_docinv").removeClass("hidden").attr("data-custom", "10,10");
							} else {
								$("#more_docinv").addClass("hidden");
							}
						});
					$("#inv-file-ctr")
						.find(".pop-over")
						.each(function () {
							$(this).popover({ html: true });
						});
				});
			},
		});
		// show more invoices
		$("#more_docinv").on("click", function () {
			$limit = $(this).attr("data-custom");
			if ($limit != "0,10") {
				//check to make sure all data isnnot loaded
				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("loading") + "....");
				$.ajax({
					type: "GET",
					url: app_url + "getUserDocs/1/" + $("#docmgrdp span").html() + "/" + $limit,
					success: function (res) {
						var mydata = JSON.parse(res);
						if (mydata.more == 1) {
							$("#inv-file-ctr").append(mydata.str);
							$("html, body").animate(
								{
									scrollTop: $("#inv-file-ctr").height(),
								},
								500,
								function () {
									$("#more_docinv")
										.html(SCTEXT("Show More") + " ...")
										.attr({
											disabled: false,
											"data-custom": mydata.limit,
										});
									$("#inv-file-ctr")
										.find(".pop-over")
										.each(function () {
											$(this).popover({ html: true });
										});
								}
							);
						} else {
							$("#more_docinv").html(SCTEXT("All loaded")).attr({
								disabled: false,
								"data-custom": mydata.limit,
							});
						}
					},
				});
			}
		});

		// load agreements
		$.ajax({
			type: "GET",
			url: app_url + "getUserDocs/2/" + $("#docmgrdp span").html(),
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#apl-file-ctr").fadeOut(function () {
					$(this)
						.html(mydata.str)
						.fadeIn(function () {
							if (mydata.more == 1 && mydata.rows == 10) {
								$("#more_docapl").removeClass("hidden").attr("data-custom", "10,10");
							} else {
								$("#more_docapl").addClass("hidden");
							}
						});
					$("#apl-file-ctr")
						.find(".pop-over")
						.each(function () {
							$(this).popover({ html: true });
						});
				});
			},
		});
		// show more agreements
		$("#more_docapl").on("click", function () {
			$limit = $(this).attr("data-custom");
			if ($limit != "0,10") {
				//check to make sure all data isnnot loaded
				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("loading") + "....");
				$.ajax({
					type: "GET",
					url: app_url + "getUserDocs/2/" + $("#docmgrdp span").html() + "/" + $limit,
					success: function (res) {
						var mydata = JSON.parse(res);
						if (mydata.more == 1) {
							$("#apl-file-ctr").append(mydata.str);
							$("html, body").animate(
								{
									scrollTop: $("#apl-file-ctr").height(),
								},
								500,
								function () {
									$("#more_docapl")
										.html(SCTEXT("Show More") + " ...")
										.attr({
											disabled: false,
											"data-custom": mydata.limit,
										});
									$("#apl-file-ctr")
										.find(".pop-over")
										.each(function () {
											$(this).popover({ html: true });
										});
								}
							);
						} else {
							$("#more_docapl").html(SCTEXT("All loaded")).attr({
								disabled: false,
								"data-custom": mydata.limit,
							});
						}
					},
				});
			}
		});

		// load other docs
		$.ajax({
			type: "GET",
			url: app_url + "getUserDocs/3/" + $("#docmgrdp span").html(),
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#oth-file-ctr").fadeOut(function () {
					$(this)
						.html(mydata.str)
						.fadeIn(function () {
							if (mydata.more == 1 && mydata.rows == 10) {
								$("#more_docoth").removeClass("hidden").attr("data-custom", "10,10");
							} else {
								$("#more_docoth").addClass("hidden");
							}
						});
					$("#oth-file-ctr")
						.find(".pop-over")
						.each(function () {
							$(this).popover({ html: true });
						});
				});
			},
		});
		// show more other docs
		$("#more_docoth").on("click", function () {
			$limit = $(this).attr("data-custom");
			if ($limit != "0,10") {
				//check to make sure all data isnnot loaded
				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("loading") + "....");
				$.ajax({
					type: "GET",
					url: app_url + "getUserDocs/3/" + $("#docmgrdp span").html() + "/" + $limit,
					success: function (res) {
						var mydata = JSON.parse(res);
						if (mydata.more == 1) {
							$("#oth-file-ctr").append(mydata.str);
							$("html, body").animate(
								{
									scrollTop: $("#oth-file-ctr").height(),
								},
								500,
								function () {
									$("#more_docoth")
										.html(SCTEXT("Show More") + " ...")
										.attr({
											disabled: false,
											"data-custom": mydata.limit,
										});
									$("#oth-file-ctr")
										.find(".pop-over")
										.each(function () {
											$(this).popover({ html: true });
										});
								}
							);
						} else {
							$("#more_docoth").html(SCTEXT("All loaded")).attr({
								disabled: false,
								"data-custom": mydata.limit,
							});
						}
					},
				});
			}
		});

		//show open folder icon
		$(".docnav").on("click", function () {
			var ele = $(this);
			if ($(this).parent().attr("class") == "") {
				$(".docnav").each(function () {
					$(this).find("i").attr("class", "fa fa-lg fa-folder m-r-xs");
				});
				ele.find("i").attr("class", "fa fa-lg fa-folder-open m-r-xs");
			}
		});
	}

	if (curpage == "add_doc") {
		//submit
		$("#save_changes").click(function () {
			if ($("#docname").val() == "") {
				bootbox.alert(SCTEXT("Please enter a title for the document"));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Uploading Document"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 200);
				setTimeout(function () {
					$("#udoc_form").attr("action", app_url + "saveDocument");
					$("#udoc_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageDocs";
		});
	}

	if (curpage == "view_doc") {
		//agreement doc
		$(".agraction").on("click", function () {
			var ele = $(this);
			var msg =
				ele.attr("data-action") == "1"
					? SCTEXT("Are you sure you want to approve this agreement?")
					: SCTEXT("Are you sure you want to decline this agreement?");
			bootbox.confirm({
				message: msg,
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//ajax call
						$.ajax({
							type: "post",
							url: app_url + "markAgreementStatus",
							data: {
								action: ele.attr("data-action"),
								docid: ele.attr("data-docid"),
							},
							success: function (res) {
								window.location.reload(false);
							},
						});
					}
				},
			});
		});

		//invoice actions
		$(".invaction").on("click", function () {
			var ele = $(this);
			var msg =
				ele.attr("data-action") == "1"
					? SCTEXT(
							"Please make sure you have received the payment and enter transaction details as a comment below. Are you sure you want to mark this invoice as PAID?"
					  )
					: SCTEXT("Are you sure you want to cancel this invoice?");
			bootbox.confirm({
				message: msg,
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//ajax call
						$.ajax({
							type: "post",
							url: app_url + "markInvoiceStatus",
							data: {
								action: ele.attr("data-action"),
								docid: ele.attr("data-docid"),
							},
							success: function (res) {
								window.location.reload(false);
							},
						});
					}
				},
			});
		});

		//print invoice
		$("#docinvprint").on("click", function () {
			$("#invoiceBox").printThis();
		});

		//scroll content of chats to bottom
		$("#docremk").animate({ scrollTop: $("#docremk").prop("scrollHeight") }, 500);

		//post comment
		$("#submitCmt").click(function () {
			//validate
			if ($("#doc_comment").val() == "") {
				bootbox.alert(SCTEXT("Your comment cannot be blank"));
				return;
			}
			//post
			$(this)
				.attr("disabled", "disabled")
				.html(SCTEXT("Submitting") + " ...");
			$("#cmt_form").attr("action", app_url + "postFileComment");
			$("#cmt_form").submit();
		});

		//share doc
		if ($("#sharedoc").length > 0) {
			$("#sharedoc").on("click", function () {
				//collect values

				//show popup
				$("#shareusrbox").modal({ show: true });
			});

			$("#submitShare").on("click", function () {
				if ($("#sharedusr").val() == "") {
					bootbox.alert(SCTEXT("Please select at least one user"));
					return;
				}

				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("Submitting") + " ...");
				$("#shareform").attr("action", app_url + "postSharedUsers");
				$("#shareform").submit();
			});
		}

		//undo sharing
		$(".remshare").on("click", function () {
			var ele = $(this);
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to remove this user from shared list?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "rmvSharedUsr",
							method: "post",
							data: {
								uid: ele.attr("data-uid"),
								docid: ele.attr("data-docid"),
							},
							success: function (res) {
								window.location = app_url + "viewDocument/" + ele.attr("data-docid");
							},
						});
					}
				},
			});
		});

		//re-upload document
		$("#docreupload").on("click", function () {
			//show popup
			$("#reupbox").modal({ show: true });
		});
		$("#submitReup").on("click", function () {
			if ($(".uploadedFile").length == 0) {
				bootbox.alert(SCTEXT("Please upload a file."));
				return;
			}

			$(this)
				.attr("disabled", "disabled")
				.html(SCTEXT("Submitting") + " ...");
			$("#reuploadform").attr("action", app_url + "documentReupload");
			$("#reuploadform").submit();
		});

		//delete doc
		$(".deleteDoc").on("click", function () {
			var ele = $(this);
			bootbox.confirm({
				message: SCTEXT(
					"Are you sure you want to delete this file? All comments/remarks will be deleted and no user will be able to access this file. Proceed anyway?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "deleteDocument/" + ele.attr("data-docid"),
							success: function (res) {
								window.location = app_url + "manageDocs";
							},
						});
					}
				},
			});
		});

		//make payment for invoice
		if ($("#docinvpay").length > 0) {
			$("#docinvpay").on("click", function () {
				var invid = $(this).attr("data-docid");
				var dialog = bootbox.dialog({
					message: $("#paymentopts").html(),
					buttons: {
						cancel: {
							label: SCTEXT("Pay Later"),
						},

						ok: {
							label: SCTEXT("Confirm Order"),
							className: "btn-primary",
							callback: function () {
								var wflag = $(".modal-body").find("#wbalflag").is(":checked") == true ? 1 : 0;
								var resobj = {
									invoiceid: invid,
									walletflag: wflag,
									returntoinvoice: 1,
								};
								//call the function to get encrypted URL parameter
								$.ajax({
									url: app_url + "encryptData",
									data: {
										mode: "payment",
										invoiceid: invid,
										walletflag: wflag,
										returntoinvoice: 1,
									},
									success: function (res) {
										window.location = app_url + "confirmPurchaseOrder/" + res;
									},
								});
							},
						},
					},
				});
				// do something in the background
				dialog.modal("show");
			});
		}

		$(document).on("change", "#wbalflag", function () {
			var wflag = $(this).is(":checked") ? 1 : 0;
			$(this).applyWbal(wflag);
		});

		$.fn.applyWbal = function (e) {
			var total = $("#gtotal").val();
			var remwal = 0;
			if (e == 1) {
				var totalpayable = total <= parseFloat($("#walbal").val()) ? 0 : total - parseFloat($("#walbal").val());

				$(".modal-body").find("#total_amt_payable").html(totalpayable.toLocaleString());

				remwal = total >= parseFloat($("#walbal").val()) ? 0 : ($("#walbal").val() - total).toLocaleString();
				$(".modal-body")
					.find("#remwal")
					.text(app_currency + remwal);
			} else {
				//customer does not wanna use wallet credits
				var totalpayable = total;

				$(".modal-body").find("#total_amt_payable").html(totalpayable.toLocaleString());

				remwal = $("#walbal").val().toLocaleString();
				$(".modal-body")
					.find("#remwal")
					.text(app_currency + remwal);
			}
		};
	}

	//25. Support

	if (curpage == "support_tickets") {
		$("#tktdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#tktdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#dt_e")
					.dataTable()
					.api()
					.ajax.url(app_url + "getMyTickets/" + $("#tktdp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#tktdp span").html("Select Date");
	}

	if (curpage == "add_ticket") {
		//submit form
		$("#save_changes").click(function () {
			if ($("#tkttitle").val() == "") {
				bootbox.alert(SCTEXT("Please enter the subject/title for the ticket."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Submitting ticket to Support team"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#st_form").attr("action", app_url + "saveSupportTicket");
					$("#st_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "supportTickets";
		});
	}

	if (curpage == "view_ticket") {
		//scroll to the bottom of comment box
		$("#docremk").animate({ scrollTop: $("#docremk").prop("scrollHeight") }, 500);

		//post comment
		$("#submitCmt").click(function () {
			//validate
			if ($("#t_comment").val() == "") {
				bootbox.alert(SCTEXT("Your comment cannot be blank"));
				return;
			}
			//post
			$(this)
				.attr("disabled", "disabled")
				.html(SCTEXT("Submitting") + " ...");
			$("#cmt_form").attr("action", app_url + "postTicketComment");
			$("#cmt_form").submit();
		});
	}

	//26. Logs

	if (curpage == "refund_log") {
		//datepicker
		$("#rldp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#rldp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-reflog")
					.dataTable()
					.api()
					.ajax.url(app_url + "getRefundLog/" + $("#rldp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#rldp span").html("Select Date");
	}

	if (curpage == "credit_log" || curpage == "va_crelog") {
		//datepicker
		$("#cldp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#cldp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-crelog")
					.dataTable()
					.api()
					.ajax.url(app_url + "getCreditLog/" + $("#cldp span").html() + "/" + $("#userid").val())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#cldp span").html("Select Date");
	}

	if (curpage == "usms_log") {
		//date time range picker initialization
		let start = moment().subtract(29, "days");
		let end = moment();
		$("#datetime").daterangepicker({
			timePicker: true,
			timePicker24Hour: false,
			timePickerSeconds: true,
			autoUpdateInput: false,
			startDate: moment().startOf("hour"),
			endDate: moment().startOf("hour").add(32, "hour"),
			locale: {
				cancelLabel: "Clear",
			},
		});
		$("#datetime").on("apply.daterangepicker", function (ev, picker) {
			$(this).val(picker.startDate.format("YYYY-MM-DD HH:mm:ss") + " - " + picker.endDate.format("YYYY-MM-DD HH:mm:ss"));
		});

		//selection action
		$(".search_option_selector").on("click", function () {
			let selectedValue = $(this).attr("data-myvalue");
			let inputid = $(this).attr("data-inputid");
			let displayText = $(this).text();
			if ($(`#${inputid}`).val() == selectedValue) return;
			if (displayText.length > 10) {
				displayText = displayText.replace(/(.{10})..+/, "$1…");
			}
			$(`#${inputid}_dropdown`).find(".search_option_selector").removeClass("chosen");
			$(this).addClass("chosen");
			$(`#${inputid}`).val(selectedValue);
			$(`#${inputid}_selection`).html(`${displayText} <i class="m-l-sm fas fa-caret-down fa-lg"></i>`);
		});

		$(".search_option_input").on("click", function () {
			let inputid = $(this).attr("data-inputid");
			let displayText = $(`#${inputid}`).val();
			if (displayText == "") {
				displayText = "Any";
			} else {
				if (displayText.length > 10) {
					displayText = displayText.replace(/(.{10})..+/, "$1…");
				}
				$(`#${inputid}_dropdown`).find(".search_option_selector").removeClass("chosen");
			}

			$(`#${inputid}_selection`).html(`${displayText} <i class="m-l-sm fas fa-caret-down fa-lg"></i>`);
		});

		//load search results
		getSearchResult();
		$("#filter_search").on("click", function () {
			getSearchResult();
		});
		$("#export_search").on("click", function () {
			//getSearchDownload();
			downloadSmsLog();
		});
	}

	//27. API

	if (curpage == "hapi" || curpage == "lapi" || curpage == "otpapi") {
		//regenrate key
		$("body").on("click", ".regeneratekey", function () {
			bootbox.confirm({
				message: SCTEXT(
					"Are you sure you want to regenerate API Key? Old implementations of the API would not work unless you update newly generated API Key in those. Shall we proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						window.location = app_url + "regAPIKey";
					}
				},
			});
		});
	}

	//28. SMS Stats

	if (curpage == "stats") {
		let total_sms_for_this_filter = 1283000;
		//date time range picker initialization
		$("#datetime").daterangepicker({
			timePicker: true,
			timePicker24Hour: false,
			timePickerSeconds: true,
			autoUpdateInput: false,
			startDate: moment().startOf("hour"),
			endDate: moment().startOf("hour").add(32, "hour"),
			locale: {
				cancelLabel: "Clear",
			},
			ranges: {
				Today: [moment().startOf("day"), moment().endOf("day")],
				Yesterday: [moment().subtract(1, "days").startOf("day"), moment().subtract(1, "days").endOf("day")],
				"Last 7 Days": [moment().subtract(6, "days").startOf("day"), moment().endOf("day")],
				"Last 30 Days": [moment().subtract(29, "days").startOf("day"), moment().endOf("day")],
				"This Month": [moment().startOf("month").startOf("day"), moment().endOf("month").endOf("day")],
				"Last Month": [moment().subtract(1, "month").startOf("month").startOf("day"), moment().subtract(1, "month").endOf("month").endOf("day")],
			},
			opens: "left",
			showCustomRangeLabel: true,
		});
		$("#datetime").on("apply.daterangepicker", function (ev, picker) {
			$(this).val(picker.startDate.format("YYYY-MM-DD HH:mm:ss") + " - " + picker.endDate.format("YYYY-MM-DD HH:mm:ss"));
		});
		$("#datetime").on("cancel.daterangepicker", function (ev, picker) {
			//do something, like clearing an input
			$("#datetime").val("");
		});
		//load default stats
		showSmsStats();
		//load stats when filter is clicked
		$("#apply_filter").on("click", function () {
			showSmsStats();
		});
	}

	//29. SMS Archive

	if (curpage == "smsarchive") {
		//datepicker
		$("#sldp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "right",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#sldp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				$("#chosenDate").val($("#sldp span").html());
			}
		);
		//Set the initial state of the picker label
		$("#sldp span").html("Select Date");

		//submit search task
		//cancel schedule
		$("body").on("click", "#submit_archdt", function () {
			var daterg = $("#chosenDate").val();

			if (daterg == "Select Date") {
				bootbox.alert(SCTEXT("Please select a valid date to proceed."));
				exit;
			}

			bootbox.confirm({
				message: SCTEXT("System will now fetch records from selected date range. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						$("#ftadd_frm").attr("action", app_url + "saveArchiveFetchTask");
						$("#ftadd_frm").submit();
					}
				},
			});
		});
	}

	//30. Scheduled SMS

	if (curpage == "scheduled") {
		//cancel schedule
		$("body").on("click", ".delsch", function () {
			var shootid = $(this).attr("data-shootid");
			bootbox.confirm({
				message: SCTEXT(
					"This action will cancel your campaign. The credits deducted will be refunded back to your account. Are you sure you want to proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						window.location = app_url + "cancelSchedule/" + shootid;
					}
				},
			});
		});

		//datepicker
		$("#dlrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#dlrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-schtbl")
					.dataTable()
					.api()
					.ajax.url(app_url + "getMyScheduledCampaigns/" + $("#dlrdp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#dlrdp span").html("Select Date");
	}

	//31. Reseller Dashboard

	if (curpage == "reseller_dashboard") {
		//sales chart dp
		$("#salesgrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#salesgrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload graph
				$.ajax({
					url: app_url + "getResellerSales/" + $("#salesgrdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						//chart
						var myChart = echarts.init(document.getElementById("salesgr"));
						var options = {
							tooltip: {
								trigger: "axis",
							},
							color: ["#35b8e0", "#f9c851"],
							legend: {
								data: ["Sales", "New Users"],
							},
							calculable: true,
							xAxis: [
								{
									type: "category",
									data: mydata.line_r.dates,
								},
							],
							yAxis: [
								{
									type: "value",
								},
							],
							series: [
								{
									name: "Sales",
									type: "line",
									data: mydata.line_r.sales,
									markPoint: {
										data: [
											{ type: "max", name: "Max" },
											{ type: "min", name: "Min" },
										],
									},
									markLine: {
										data: [
											{
												type: "average",
												name: "Average",
											},
										],
									},
								},
								{
									name: "New Users",
									type: "line",
									data: mydata.line_r.signups,
									markPoint: {
										data: [
											{ type: "max", name: "Max" },
											{ type: "min", name: "Min" },
										],
									},
									markLine: {
										data: [
											{
												type: "average",
												name: "Average",
											},
										],
									},
								},
							],
						};
						myChart.setOption(options);
					},
				});
			}
		);

		//top consumers dp
		$("#topcldp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "right",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#topcldp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload figures
				//reload sms figures
				loadDashboardStats({
					mode: "topclients",
					page: "reseller",
				});
			}
		);
		//Set the initial state of the picker label
		$("#topcldp span").html(
			Date.today()
				.add({
					days: -6,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//sales and signups
		$.ajax({
			url: app_url + "getResellerStats",
			success: function (res) {
				var mydata = JSON.parse(res);
				//top four boxes
				$("#weekly_sales_ctr").html(formatInt(mydata.s_total_week));
				$("#monthly_sales_ctr").html(formatInt(mydata.s_total_month));
				$("#weekly_usr_ctr").html(mydata.u_total_week);
				$("#monthly_usr_ctr").html(mydata.u_total_month);

				//weekly small chart
				let arr = [];
				for (elem in mydata.s_this_week) {
					arr.push(mydata.s_this_week[elem]);
				}
				let u_arr = [];
				for (u_elem in mydata.u_this_week) {
					u_arr.push(mydata.u_this_week[u_elem]);
				}
				$("#wk_sales_chart").sparkline(arr, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 3,
					barSpacing: 2,
				});
				$("#wk_usr_chart").sparkline(u_arr, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 3,
					barSpacing: 2,
				});
				//monthly small chart
				let arr2 = [];
				for (elem2 in mydata.s_this_month) {
					arr2.push(mydata.s_this_month[elem2]);
				}
				let u_arr2 = [];
				for (u_elem2 in mydata.u_this_month) {
					u_arr2.push(mydata.u_this_month[u_elem2]);
				}
				$("#mn_sales_chart").sparkline(arr2, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 2,
					barSpacing: 1.5,
				});
				$("#mn_usr_chart").sparkline(u_arr2, {
					type: "bar",
					barColor: "#ffffff",
					barWidth: 2,
					barSpacing: 1.5,
				});

				//chart
				let myChart = echarts.init(document.getElementById("salesgr"));
				let options = {
					tooltip: {
						trigger: "axis",
					},
					color: ["#35b8e0", "#f9c851"],
					legend: {
						data: ["Sales", "New Users"],
					},
					calculable: true,
					xAxis: [
						{
							type: "category",
							data: mydata.line_r.dates,
						},
					],
					yAxis: [
						{
							type: "value",
						},
					],
					series: [
						{
							name: "Sales",
							type: "line",
							data: mydata.line_r.sales,
							markPoint: {
								data: [
									{ type: "max", name: "Max" },
									{ type: "min", name: "Min" },
								],
							},
							markLine: {
								data: [{ type: "average", name: "Average" }],
							},
						},
						{
							name: "New Users",
							type: "line",
							data: mydata.line_r.signups,
							markPoint: {
								data: [
									{ type: "max", name: "Max" },
									{ type: "min", name: "Min" },
								],
							},
							markLine: {
								data: [{ type: "average", name: "Average" }],
							},
						},
					],
				};
				myChart.setOption(options);
			},
		});

		// top consumers
		//reload sms figures
		loadDashboardStats({
			mode: "topclients",
			page: "reseller",
		});
		//----------------//

		//top orders stuff
		// top orders
		$.ajax({
			type: "GET",
			url: app_url + "getLatestOrders/" + $("#topsalesdp span").html(),
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#topsalesctr").fadeOut(function () {
					$(this)
						.html(mydata.str)
						.fadeIn(function () {
							if (mydata.more == 1 && mydata.rows == 4) {
								$("#more_sales").removeClass("hidden").attr("data-custom", "4,4");
							} else {
								$("#more_sales").addClass("hidden");
							}
						});
				});
			},
		});
		// show more sales
		$("#more_sales").on("click", function () {
			$limit = $(this).attr("data-custom");
			if ($limit != "0,4") {
				//check to make sure all data isnnot loaded
				$(this)
					.attr("disabled", "disabled")
					.html(SCTEXT("loading") + "....");
				$.ajax({
					type: "GET",
					url: app_url + "getLatestOrders/" + $("#topsalesdp span").html() + "/" + $limit,
					success: function (res) {
						var mydata = JSON.parse(res);
						if (mydata.more == 1) {
							$("#topsalesctr").append(mydata.str);
							$("#topsalesctr").animate(
								{
									scrollTop: $("#topsalesctr").prop("scrollHeight"),
								},
								500,
								function () {
									$("#more_sales")
										.html(SCTEXT("Show More") + " ...")
										.attr({
											disabled: false,
											"data-custom": mydata.limit,
										});
								}
							);
						} else {
							$("#more_sales").html(SCTEXT("All loaded")).attr({
								disabled: false,
								"data-custom": mydata.limit,
							});
						}
					},
				});
			}
		});

		//top order dp
		$("#topsalesdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "right",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#topsalesdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload sales figures
				$.ajax({
					type: "GET",
					url: app_url + "getLatestOrders/" + $("#topsalesdp span").html(),
					success: function (res) {
						var mydata = JSON.parse(res);
						$("#topsalesctr").fadeOut(function () {
							$(this)
								.html(mydata.str)
								.fadeIn(function () {
									if (mydata.more == 1 && mydata.rows == 4) {
										$("#more_sales")
											.removeClass("hidden")
											.attr("data-custom", "4,4")
											.html(SCTEXT("Show More") + " ...");
									} else {
										$("#more_sales").addClass("hidden");
									}
								});
						});
					},
				});
			}
		);
		//Set the initial state of the picker label
		$("#topsalesdp span").html(
			Date.today()
				.add({
					days: -6,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//-------------//

		//recent transactions
		$.ajax({
			type: "GET",
			url: app_url + "getRecentTransactions",
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#rectransbox").fadeOut(function () {
					$(this).html(mydata.str).fadeIn();
				});
			},
		});

		//recent campaigns
		$.ajax({
			type: "GET",
			url: app_url + "getRecentCampaigns",
			success: function (res) {
				var mydata = JSON.parse(res);
				$("#recsmsbox").fadeOut(function () {
					$(this).html(mydata.str).fadeIn();
				});
			},
		});

		//--//
	}

	//32. View Account: Overview

	if (curpage == "view_account") {
		//switch waba agent
		if ($("#u_waba").length > 0) {
			$("#u_waba").on("change", function () {
				let wabaid = $("#u_waba option:selected").val();
				if (wabaid == "") return;
				bootbox.confirm({
					message: SCTEXT("Are you sure you want to assign this WABA agent to this user?"),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: "Yes, Proceed",
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//send
							$.ajax({
								url: app_url + "switchWabaAgent",
								type: "post",
								data: { userid: $("#userid").val(), wabaid: wabaid },
								success: function (res) {
									window.location.reload();
								},
							});
						}
					},
				});
			});
		}
		//switch waba plan
		if ($("#agentplan").length > 0) {
			$("#agentplan").on("change", function () {
				let planid = $("#agentplan option:selected").val();
				if (planid == "") return;
				bootbox.confirm({
					message: SCTEXT("Are you sure you want to assign this Whatsapp plan to this agent?"),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: "Yes, Proceed",
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//send
							$.ajax({
								url: app_url + "switchWabaPlan",
								type: "post",
								data: { userid: $("#userid").val(), planid: planid },
								success: function (res) {
									window.location.reload();
								},
							});
						}
					},
				});
			});
		}

		//switch account manager if admin
		if ($("#u_acc_mgr").length > 0) {
			$("#u_acc_mgr").on("change", function () {
				var uid = $("#u_acc_mgr option:selected").val();
				bootbox.confirm({
					message: SCTEXT("Are you sure you want to change the account manager for this user?"),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: "Yes, Proceed",
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//send
							$.ajax({
								url: app_url + "switchStaff",
								type: "post",
								data: { user: $("#userid").val(), staff: uid },
								success: function (res) {
									window.location.reload();
								},
							});
						}
					},
				});
			});
		}

		//datepicker for activity log
		if ($("#uactl-dp").length > 0) {
			$("#uactl-dp").daterangepicker(
				{
					ranges: {
						Today: ["today", "today"],
						Yesterday: ["yesterday", "yesterday"],
						"Last 7 Days": [
							Date.today().add({
								days: -6,
							}),
							"today",
						],
						"Last 30 Days": [
							Date.today().add({
								days: -29,
							}),
							"today",
						],
						"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
						"Last Month": [
							Date.today().moveToFirstDayOfMonth().add({
								months: -1,
							}),
							Date.today().moveToFirstDayOfMonth().add({
								days: -1,
							}),
						],
					},
					opens: "left",
					format: "MM/dd/yyyy",
					separator: " to ",
					startDate: Date.today().add({
						days: -29,
					}),
					endDate: Date.today(),
					minDate: "01/01/2012",
					maxDate: "12/31/2030",
					locale: {
						applyLabel: "Apply",
						clearLabel: "Cancel",
						customRangeLabel: "Custom Range",
						daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
						monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						firstDay: 1,
					},
					showWeekNumbers: true,
					buttonClasses: ["btn-danger"],
				},
				function (start, end) {
					$("#uactl-dp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
					//reload table
					$("#t-uactlog")
						.dataTable()
						.api()
						.ajax.url(app_url + "getUserActivity/" + $("#uactl-dp span").html() + "/" + $("#userid").val())
						.load();
				}
			);
		}

		//date picker
		$("#stdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#stdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload sms figures
				loadViewAcountSmsSummary();
			}
		);
		//Set the initial state of the picker label
		$("#stdp span").html(
			Date.today()
				.add({
					days: -9,
				})
				.toString("MMM d, yyyy") +
				" - " +
				Date.today().toString("MMM d, yyyy")
		);

		//switch website status
		if ($("#ws_toggle").length > 0) {
			$("#ws_toggle").on("change", function () {
				var wst = $(this).is(":checked") ? 1 : 0;
				var uid = $("#userid").val();

				$.ajax({
					url: app_url + "websiteToggle",
					method: "post",
					data: { uid: uid, status: wst },
					success: function (res) {
						window.location.reload();
					},
				});
			});
		}
		//load stats on pageload
		loadViewAcountSmsSummary();
	}

	//33. View Account: Make transaction

	if (curpage == "va_utrans") {
		var errcredits = 0;
		var numerr = 0;
		var d_errcredits = 0;
		var d_numerr = 0;

		//for currency based account
		$(document).on("keyup blur input", ".cur_inputs", function () {
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
				$(this).addClass("error-input");
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
			}
			let tax = $("#w_planid").attr("data-ptax");
			let taxtype = $("#w_planid").attr("data-taxtype");
			let credits = parseFloat($("#mplanscredits").val()) || 0;
			let additionaltax = parseFloat($("#add_tax").val()) || 0;
			let gtotal = 0;
			let taxstr = "including all taxes";
			if (parseFloat(tax) > 0) {
				gtotal = (credits + parseFloat(credits * (tax / 100))).toFixed(2);
				let taxes = [
					{ tax: "GT", str: "GST" },
					{ tax: "VT", str: "VAT" },
					{ tax: "ST", str: "Service Tax" },
					{ tax: "SC", str: "Service Charges" },
					{ tax: "OT", str: "Other Taxes" },
				];
				let taxtypestr = taxes.find((item) => item.tax == taxtype);
				taxstr = `including ${tax}% ${taxtypestr.str}`;
			} else {
				gtotal = credits.toFixed(2);
			}
			if (parseFloat(additionaltax) > 0) {
				gtotal = (parseFloat(gtotal) + parseFloat(gtotal * (additionaltax / 100))).toFixed(2);
			}
			$("#w_grand_total_amt").text(
				new Number(gtotal).toLocaleString("en-US", {
					minimumFractionDigits: 2,
				})
			);
			$("#w_all_taxes").text(`(${taxstr})`);
		});

		//submit currency based transaction form
		$("#subcurfrm").on("click", function () {
			//validate

			if ($("#mplanscredits").val() == "" || $("#mplanscredits").val() <= 0) {
				bootbox.alert(SCTEXT("Please enter a valid credit amount for this transaction."));
				return;
			}

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Processing Transaction"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#w_form").attr("action", app_url + "processAccountTransaction");
					$("#w_form").submit();
				}, 200);
			});
		});

		//submit credit transaction form
		$("#sub_cform").on("click", function () {
			//validate

			if ($("#c_route option:selected").val() == "") {
				bootbox.alert(SCTEXT("Please select a route to assign credits."));
				return;
			}

			if ($("#add_cre").val() == "") {
				bootbox.alert(SCTEXT("Please enter SMS credits to assign."));
				return;
			}

			if (numerr == 1) {
				bootbox.alert(SCTEXT("Please enter only Numeric values in highlighted fields."));
				return;
			}

			if (errcredits == 1) {
				bootbox.alert(SCTEXT("Please do not assign more credits than your account balance."));
				return;
			}

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Processing Transaction"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#c_form").attr("action", app_url + "processAccountTransaction");
					$("#c_form").submit();
				}, 200);
			});
		});

		//allot credits form
		$("#c_route").on("change", function () {
			var prc = $("#c_route option:selected").attr("data-price");
			//get sms price based on route selected
			$("#c_price").val(prc);

			//recalculate total cost if the credits are already entered
			if ($("#add_cre").val() != "") {
				$("#add_cre").trigger("keyup");
			}
		});

		$(".numtxt").on("blur keyup", function () {
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
				$(this).addClass("error-input");
				numerr = 1;
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
				numerr = 0;
			}
			var routencredits = [];
			var rdata = {
				id: $("#c_route option:selected").val(),
				credits: $("#add_cre").val(),
				price: $("#c_price").val(),
			};
			routencredits.push(rdata);
			//calculate
			$.ajax({
				url: app_url + "getPlanSmsPrice",
				method: "post",
				data: {
					mode: "utrans",
					plan: $("#planid").val(),
					routesData: JSON.stringify(routencredits),
					discount: 0,
					dtype: "",
					addTax: $("#c_utax").val(),
				},
				success: function (res) {
					var myarr = [];
					myarr = JSON.parse(res);
					//you have the price and credits entered

					//update the rate received from the db in case a plan is chosen
					if ($("#planid").val() != "0") {
						$("#c_price").val(myarr.price[$("#c_route option:selected").val()].price);
					}

					var plan_cost = myarr.total_plan;
					var ptax = myarr.plan_tax;
					var gtotal = myarr.grand_total;

					//put plan total
					$("#total_c_amt").text(plan_cost);

					//put tax declaration
					$("#c_plan_taxes").text(ptax);

					//put grand total
					$("#c_grand_total_amt").text(gtotal);

					//check error
					if (myarr.errcredits == "1") {
						errcredits = 1;
						bootbox.alert(SCTEXT("Please do not assign more credits than your account balance."));
					} else {
						errcredits = 0;
					}
				},
			});
		});

		//deduct credit box
		$("#d_route").on("change", function () {
			var prc = $("#d_route option:selected").attr("data-price");
			//get sms price based on route selected
			$("#d_rtprc").text(prc);
			$("#rtavcr").text($("#d_route option:selected").attr("data-credits"));
			//reset the credit deduction number
			$("#deduct_cre").val("");
			$("#d_total_amt, #d_grand_total_amt").text("0.00");
			$("#dutax").val("");
		});
		//
		//refund calculations when deducting credits
		$(".drtcredits").on("blur keyup", function () {
			//only numbers allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != "") {
				$(this).addClass("error-input");
				d_numerr = 1;
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
				d_numerr = 0;
			}
			var routencredits = [];
			var rdata = {
				id: $("#d_route option:selected").val(),
				credits: $("#deduct_cre").val(),
				price: $("#d_rtprc").text(),
			};
			routencredits.push(rdata);
			//calculate
			$.ajax({
				url: app_url + "getPlanSmsPrice",
				method: "post",
				data: {
					mode: "utrans_d",
					plan: $("#planid").val(),
					routesData: JSON.stringify(routencredits),
					discount: 0,
					dtype: "",
					addTax: $("#dutax").val(),
					avcre: $("#rtavcr").text(),
				},
				success: function (res) {
					var myarr = [];
					myarr = JSON.parse(res);
					//you have the price and credits entered

					//update the rate received from the db in case a plan is chosen
					if ($("#planid").val() != "0") {
						$("#d_rtprc").text(myarr.price[$("#d_route option:selected").val()].price);
					}

					var plan_cost = myarr.total_plan;
					var ptax = myarr.plan_tax;
					var gtotal = myarr.grand_total;

					//put plan total
					$("#d_total_amt").text(plan_cost);

					//put tax declaration
					$("#d_plan_taxes").text(ptax);

					//put grand total
					$("#d_grand_total_amt").text(gtotal);

					//check error
					if (myarr.errcredits == "1") {
						d_errcredits = 1;
						bootbox.alert(SCTEXT("Please do not deduct more credits than available in customer account."));
					} else {
						d_errcredits = 0;
					}
				},
			});
		});

		//submit debit transaction box
		$("#subfrm").on("click", function () {
			//validate

			if ($("#d_route option:selected").val() == "") {
				bootbox.alert(SCTEXT("Please select a route to deduct credits from."));
				return;
			}

			if ($("#deduct_cre").val() == "") {
				bootbox.alert(SCTEXT("Please enter SMS credits to deduct."));
				return;
			}

			if (d_numerr == 1) {
				bootbox.alert(SCTEXT("Please enter only Numeric values in highlighted fields."));
				return;
			}

			if (d_errcredits == 1) {
				bootbox.alert(SCTEXT("Please do not deduct more credits than customer account balance."));
				return;
			}

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Processing Transaction"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#d_form").attr("action", app_url + "processAccountTransaction");
					$("#d_form").submit();
				}, 200);
			});
		});
	}

	//34. View Account: User Settings

	if (curpage == "va_uset") {
		//toggle custom tlv
		$("#cus_tlv_flag").on("change", function () {
			if ($(this).is(":checked")) {
				$("#cus_tlv_ctr").show();
			} else {
				$("#cus_tlv_ctr").hide();
			}
		});
		//save hlr settings
		$("#save_hlrset").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#hlrsetform").attr("action", app_url + "saveUserHlrSettings");
					$("#hlrsetform").submit();
				}, 200);
			});
		});

		//save 2-way settings for user
		$("#vmnsetsubmit").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#usrvmnsetform").attr("action", app_url + "saveUserVmnSettings");
					$("#usrvmnsetform").submit();
				}, 200);
			});
		});

		//submit general permissions
		$("#pldbsetsubmit").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Phonebook permissions"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#pbdbsetform").attr("action", app_url + "saveUserPhonebookPermissions");
					$("#pbdbsetform").submit();
				}, 200);
			});
		});

		//change number masking for phonebook
		var defnum = "971500301012";
		$("#maskstart,#masklen").on("change", function () {
			var mpos = $("#maskstart").val();
			var mlen = $("#masklen").val();
			var replacestr = "x".repeat(mlen);
			var newstr = substr_replace(defnum, replacestr, mpos, mlen);
			$("#egmask").html(newstr);
			$("#maskstart").attr("max", 12 - parseInt(mlen));
		});

		//submit general permissions
		$("#upermsubmit").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving user permissions"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#upermform").attr("action", app_url + "saveUserPermissions");
					$("#upermform").submit();
				}, 200);
			});
		});

		//submit special permissions
		$("#uflagbtn").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving user permissions"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#uflagform").attr("action", app_url + "saveUserSpecialFlags");
					$("#uflagform").submit();
				}, 200);
			});
		});

		//submit whitelist numbers
		$("#uwconbtn").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving whitelist contacts"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#uwconform").attr("action", app_url + "saveUserWhitelist");
					$("#uwconform").submit();
				}, 200);
			});
		});

		//smpp client management
		//toggle status
		$(document).on("change", ".togstatus", function () {
			let sid = $(this).attr("data-sid");
			let rstatus = 0;
			if ($(this).is(":checked")) {
				rstatus = 1;
			}
			$.ajax({
				url: app_url + "toggleSmppClientStatus",
				method: "post",
				data: { id: sid, status: rstatus },
				success: function (res) {
					bootbox.alert(res);
				},
			});
		});

		//delete route
		$(document).on("click", ".delsmppclient", function () {
			let aid = $(this).attr("data-aid");
			bootbox.confirm({
				message: SCTEXT(
					"Please make sure this account does not have SMS data associated with it. If you need to temporarily disable it, please change the status of this account by the green switch. Are you sure you want to permanently delete this SMPP client account?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteSmppClient/" + $("#userid").val() + "/" + aid;
					}
				},
			});
		});
	}

	//35. Edit Profile

	if (curpage == "edit_profile") {
		//switch waba profile
		if ($("#waba_phnsel").length > 0) {
			$("#waba_phnsel").on("change", function () {
				let phone_id = $("#waba_phnsel option:selected").val();
				if (phone_id == "") return;
				//load new profile details
				$.ajax({
					url: app_url + "loadWabaProfile/" + phone_id,
					success: function (res) {
						//relace values in the form
						let data = JSON.parse(res);
						$("#phoneid").val(phone_id);
						$("#bp-dp").attr("src", `${data.bp_profile_picture.trim() != "" ? data.bp_profile_picture : "https://placehold.co/200"}`);
						$("#bp-name").html(data.verified_name);
						$("#bp-phone").html(data.display_phone);
						$("#bp-quality").html(
							`<span class="m-l-sm label label-xs ${data.quality == "GREEN" ? "label-success" : "label-danger"}">Quality: ${
								data.quality || "UNKNOWN"
							}</span>`
						);
						$("#bp-about").val(data.bp_about);
						$("#bp_desc").val(data.bp_description);
					},
				});
			});
		}

		//save waba profile settings
		if ($("#savewabaprofile").length > 0) {
			$("#savewabaprofile").click(function () {
				bootbox.alert(SCTEXT("Updating WABA profile . . ."));
				$.ajax({
					url: app_url + "updateWabaProfile",
					method: "post",
					data: {
						phoneid: $("#phoneid").val(),
						about: $("#bp_about").val(),
						description: $("#bp_desc").val(),
					},
					success: function (res) {
						console.log(res);
						window.location.reload();
					},
				});
			});
		}
		//payment method switch
		$("#userpg").on("change", function () {
			let ele = $("#userpg option:selected");
			let link = ele.attr("data-link");
			if (ele.val() != "") {
				$("#pgdesc").html(
					`Make sure you have a merchant account with ${ele.text()}. Please <a href="${link}" target="_blank">click here</a> to sign-up.`
				);
				//show selected options
				$(".pgboxes").hide(function () {
					$(`#${ele.val()}-tab`).fadeIn();
				});
			} else {
				//hide all options
				$("#pgdesc").html("");
				$(".pgboxes").hide();
			}
		});
		$("#userpg").trigger("change");

		//submit company info
		$("#savecifrm").click(function () {
			//validate

			if ($("#cname").val() == "") {
				bootbox.alert(SCTEXT("Company name cannot be blank."));
				return;
			}

			if ($("#cphn").val() == "") {
				bootbox.alert(SCTEXT("Company phone number cannot be blank."));
				return;
			}

			if ($("#cmail").val() == "") {
				bootbox.alert(SCTEXT("Company email ID cannot be blank."));
				return;
			}

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving company information"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#cifrm").attr("action", app_url + "saveCompanyInfo");
					$("#cifrm").submit();
				}, 200);
			});
		});

		//match passwords
		var errpass = 0;
		$("#newpass1, #newpass2").on("keyup blur", function () {
			var mode = $(this).attr("data-strength");
			var val = $(this).val();

			if (mode == "weak") {
				//length
				if (val.length < 6) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
				}
			}

			if (mode == "average") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//alphabet letter
					if (!/[a-zA-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one alphabet letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
						}
					}
				}
			}

			if (mode == "strong") {
				//length
				if (val.length < 8) {
					errpass = 1;
					$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
				} else {
					errpass = 0;
					$("#pass-err").text("");
					//uppercase alphabet
					if (!/[A-Z]/.test(val)) {
						errpass = 1;
						$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one uppercase letter."));
					} else {
						errpass = 0;
						$("#pass-err").text("");
						//numeric
						if (!/[0-9]/.test(val)) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one numeric character."));
						} else {
							errpass = 0;
							$("#pass-err").text("");
							//special characters
							if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val)) {
								errpass = 1;
								$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one special character."));
							} else {
								errpass = 0;
								$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
							}
						}
					}
				}
			}
			if (errpass == 0) {
				//if everything is good match both passwords
				if ($("#newpass1").val() != $("#newpass2").val()) {
					errpass = 1;
					$("#pass-err")
						.removeClass("text-success")
						.addClass("text-danger")
						.text(SCTEXT("Passwords do not match each other. Please re-type your password"));
				} else {
					errpass = 0;
					$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
				}
			}
		});

		//submit save password
		$("#savecpfrm").click(function () {
			//validate

			if ($("#oldpass").val() == "") {
				bootbox.alert(SCTEXT("Please enter old password."));
				return;
			}

			if ($("#newpass1").val() == "" || $("#newpass1").val() == "") {
				bootbox.alert(SCTEXT("Please enter values in all the fields."));
				return;
			}

			if (errpass == 1) {
				bootbox.alert(SCTEXT("Some errors are found with your entry. Please rectify before submitting the form."));
				return;
			}

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Changing account password"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#cpfrm").attr("action", app_url + "saveUserPassword");
					$("#cpfrm").submit();
				}, 200);
			});
		});

		//verify phone
		$("#vphn").click(function () {
			bootbox.confirm({
				message: SCTEXT(
					"We will send you a 6-digit one-time password (OTP) to your mobile number. Make sure you have saved your correct mobile number."
				),
				buttons: {
					cancel: {
						label: SCTEXT("Cancel"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Send OTP"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//call ajax and send otp
						$.ajax({
							url: app_url + "verifyViaOTP/mobile",
							success: function (res) {
								//prompt for otp
								bootbox.prompt(SCTEXT("Enter the 6-digit OTP sent to your mobile number"), function (act) {
									var box = $(this);
									//variable act will have the otp entered
									if (act) {
										//confirm opt

										$.ajax({
											url: app_url + "confirmOTP/mobile",
											method: "post",
											data: { otp: act },
											async: false,
											success: function (myres) {
												var mydata = JSON.parse(myres);
												if (mydata.match_result == "no") {
													if ($("#otperr").length > 0) {
														$("#otperr").html(SCTEXT("The OTP entered did not match."));
														box.effect("shake");
													} else {
														$("<span id='otperr' class='help-block text-danger'>" + SCTEXT("The OTP entered did not match.") + "</span>").insertAfter(
															"input.bootbox-input-text"
														);
														box.effect("shake");
													}
												} else {
													window.location.reload();
												}
											},
										});

										return false; //to keep bootbox open
									} else {
										if ($("#otperr").length > 0) {
											$("#otperr").html(SCTEXT("OTP cannot be blank."));
											box.effect("shake");
										} else {
											$("<span id='otperr' class='help-block text-danger'>" + SCTEXT("OTP cannot be blank.") + "</span>").insertAfter(
												"input.bootbox-input-text"
											);
											box.effect("shake");
										}
										return false;
									}
								});
							},
						});
					}
				},
			});
		});

		//verify email
		$("#vmail").click(function () {
			bootbox.confirm({
				message:
					"We will send you a 6-digit one-time password (OTP) to your email. Make sure you have saved your correct email address and check your SPAM/JUNK box if not received within a minute.",
				buttons: {
					cancel: {
						label: SCTEXT("Cancel"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Send OTP"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//call ajax and send otp
						$.ajax({
							url: app_url + "verifyViaOTP/email",
							success: function (res) {
								//prompt for otp
								bootbox.prompt(SCTEXT("Enter the 6-digit OTP sent to your email address"), function (act) {
									var box = $(this);
									//variable act will have the otp entered
									if (act) {
										//confirm opt

										$.ajax({
											url: app_url + "confirmOTP/email",
											method: "post",
											data: { otp: act },
											async: false,
											success: function (myres) {
												var mydata = JSON.parse(myres);
												console.log(mydata.match_result);
												if (mydata.match_result == "no") {
													if ($("#otperr").length > 0) {
														$("#otperr").html(SCTEXT("The OTP entered did not match."));
														box.effect("shake");
													} else {
														$("<span id='otperr' class='help-block text-danger'>" + SCTEXT("The OTP entered did not match.") + "</span>").insertAfter(
															"input.bootbox-input-text"
														);
														box.effect("shake");
													}
												} else {
													window.location.reload();
												}
											},
										});

										return false; //to keep bootbox open
									} else {
										if ($("#otperr").length > 0) {
											$("#otperr").html(SCTEXT("OTP cannot be blank."));
											box.effect("shake");
										} else {
											$("<span id='otperr' class='help-block text-danger'>" + SCTEXT("OTP cannot be blank.") + "</span>").insertAfter(
												"input.bootbox-input-text"
											);
											box.effect("shake");
										}
										return false;
									}
								});
							},
						});
					}
				},
			});
		});

		var erremail = 0;
		var errphone = 0;

		$(document).on("change", "#u-img", function () {
			var ele = $(this);
			ele.next().next().html(ele.val());
		});

		//validate email
		$("#uemail").on("keyup blur", function () {
			var email = $(this).val();
			$("#v-email").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (!echeck(email)) {
				$("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
				erremail = 1;
				emsg = SCTEXT("Invalid Email ID");
			} else {
				$("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
				erremail = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					async: false,
					data: { mode: "email", value: email, page: "editprofile" },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
							erremail = 1;
							emsg = SCTEXT("Email ID already exist. Please enter a different email ID.");
						} else {
							$("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
							erremail = 0;
							emsg = "";
						}
					},
				});
			}
		});
		//validate phone
		$("#uphn").on("keyup blur", function () {
			var phn = $(this).val();
			$("#v-phn").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (!isValidPhone(phn)) {
				$("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
				errphone = 1;
				emsg = SCTEXT("Invalid Phone number entered.");
			} else {
				$("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
				errphone = 0;
				emsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					async: false,
					data: { mode: "mobile", value: phn, page: "editprofile" },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
							errphone = 1;
							emsg = SCTEXT("Phone number already exist. Please enter a different phone number.");
						} else {
							$("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
							errphone = 0;
							emsg = "";
						}
					},
				});
			}
		});

		//save
		$("#saveupfrm").click(function () {
			//validate

			if ($("#uname").val() == "") {
				bootbox.alert(SCTEXT("Please enter name of the user."));
				return;
			}

			if ($("#uemail").val() == "" || $("#uphn").val() == "") {
				bootbox.alert(SCTEXT("Please enter values in all the fields."));
				return;
			}

			if (erremail == 1 || errphone == 1) {
				if (emsg == "") {
					bootbox.alert(SCTEXT("Some errors are found with your entry. Please rectify before submitting the form."));
				} else {
					bootbox.alert(emsg);
				}

				return;
			}

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Profile Information"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#upfrm").attr("action", app_url + "saveUserProfile");
					$("#upfrm").submit();
				}, 200);
			});
		});
	}

	//36. User Settings

	if (curpage == "user_settings") {
		//submit form
		$("#saveuset").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving account settings"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#usfrm").attr("action", app_url + "saveUserSettings");
					$("#usfrm").submit();
				}, 200);
			});
		});
	}

	//37. Notifications

	if (curpage == "notifs") {
		//datepicker
		$("#altdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#altdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-alerts")
					.dataTable()
					.api()
					.ajax.url(app_url + "getAllMyAlerts/" + $("#altdp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#altdp span").html("Select Date");
	}

	//38. Support Ticket Management

	if (curpage == "support_tickets_mgmt") {
		$("#tktmgrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#tktmgrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#dt_suptkt_mgr")
					.dataTable()
					.api()
					.ajax.url(app_url + "getAssignedTickets/" + $("#tktmgrdp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#tktmgrdp span").html("Select Date");
	}

	//39. View Ticket by Account Manager

	if (curpage == "view_ticket_mgr") {
		//scroll to the bottom of comment box
		$("#docremk").animate({ scrollTop: $("#docremk").prop("scrollHeight") }, 500);

		//post comment
		$("#submitCmt").click(function () {
			//validate
			if ($("#t_comment").val() == "") {
				bootbox.alert(SCTEXT("Your comment cannot be blank"));
				return;
			}
			//post
			$(this)
				.attr("disabled", "disabled")
				.html(SCTEXT("Submitting") + " ...");
			$("#cmt_form").attr("action", app_url + "postTicketComment");
			$("#cmt_form").submit();
		});
	}

	//40. Add Spam Keyword

	if (curpage == "add_spam_kw") {
		//submit form
		$("#save_changes").click(function () {
			if ($("#kw").val() == "") {
				bootbox.alert(SCTEXT("Spam keyword cannot be empty. Please enter a keyword."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving new SPAM keyword"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#add_spmkw_form").attr("action", app_url + "saveSpamKeyword");
					$("#add_spmkw_form").submit();
				}, 100);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageSpamKeywords";
		});
	}

	//41. System monitor
	function drawRateGauges(mode, channel, value, chartElement) {
		let chart = echarts.init(document.getElementById(chartElement), "macarons");
		let color = mode == "now" ? "#77dd77" : mode == "peak" ? "#ff6961" : "#7aa6ff";
		let unit = channel == "smpp" ? "mps" : "rps";
		let option = {
			series: [
				{
					type: "gauge",
					startAngle: 180,
					endAngle: 0,
					min: 0,
					max: parseInt($("#peak_throughput_allowed").val(), 10),
					splitNumber: 5,
					itemStyle: {
						color: color,
						shadowColor: "rgba(0,138,255,0.45)",
						shadowBlur: 10,
						shadowOffsetX: 2,
						shadowOffsetY: 2,
					},
					progress: {
						show: true,
						roundCap: true,
						width: 16,
					},
					pointer: {
						icon: "path://M2090.36389,615.30999 L2090.36389,615.30999 C2091.48372,615.30999 2092.40383,616.194028 2092.44859,617.312956 L2096.90698,728.755929 C2097.05155,732.369577 2094.2393,735.416212 2090.62566,735.56078 C2090.53845,735.564269 2090.45117,735.566014 2090.36389,735.566014 L2090.36389,735.566014 C2086.74736,735.566014 2083.81557,732.63423 2083.81557,729.017692 C2083.81557,728.930412 2083.81732,728.84314 2083.82081,728.755929 L2088.2792,617.312956 C2088.32396,616.194028 2089.24407,615.30999 2090.36389,615.30999 Z",
						length: "75%",
						width: 14,
						offsetCenter: [0, "5%"],
					},
					axisLine: {
						roundCap: true,
						lineStyle: {
							width: 16,
						},
					},
					axisTick: {
						splitNumber: 2,
						lineStyle: {
							width: 2,
							color: "#999",
						},
					},
					splitLine: { show: false },
					axisLabel: {
						show: false,
					},
					title: {
						show: false,
					},
					detail: {
						backgroundColor: "#fff",
						borderColor: "#999",
						borderWidth: 0,
						width: "60%",
						lineHeight: 40,
						height: 50,
						borderRadius: 8,
						offsetCenter: [0, "85%"],
						valueAnimation: true,
						formatter: function (value) {
							return "{value|" + value.toFixed(0) + "}{unit|" + unit + "}";
						},
						rich: {
							value: {
								fontSize: 16,
								fontWeight: "bolder",
								color: "#777",
							},
							unit: {
								fontSize: 12,
								color: "#999",
								padding: [0, 0, 0, 5],
							},
						},
					},
					data: [
						{
							value: value,
						},
					],
				},
			],
		};
		chart.setOption(option);
	}
	function getHistoricalSysmonStats() {
		$.ajax({
			url: app_url + "getSysmonData/" + $("#sysmondp span").html(),
			success: function (res) {
				// Initialize the chart
				let historical_chart = echarts.init(document.getElementById("sysmon"), "macarons");
				let data = JSON.parse(res);
				// Data for the chart
				let dates = data.dates;

				// Service A data
				let totalReqsA = data.smpp_totals; // Total requests for smpp
				let peakRateA = data.smpp_peaks; // Peak rates for smpp in mps

				// Service B data
				let totalReqsB = data.api_totals; // Total requests for api
				let peakRateB = data.api_peaks; // Peak rates for service_b in req/s
				let allowedRate = parseInt($("#peak_throughput_allowed").val(), 10);

				// Configure the chart options
				let historical_option = {
					toolbox: {
						show: true,
						orient: "horizontal",
						left: "right",
						top: "top",
						feature: {
							saveAsImage: {
								show: true,
								name: "system_monitor_data",
								title: "Download",
							},
						},
					},
					textStyle: {
						fontFamily: `Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif`,
					},
					title: {},
					tooltip: {
						trigger: "axis",
						axisPointer: {
							type: "cross",
						},
					},
					legend: {
						data: ["Total SMS (SMPP)", "Total Reqs (API)", "Peak Rate (SMPP)", "Peak Rate (API)"],
						bottom: 10,
					},
					xAxis: {
						type: "category",
						data: dates, // X-axis contains the dates
						axisTick: {
							alignWithLabel: true,
						},
					},
					yAxis: [
						{
							type: "value",
							name: "Total Requests",
							position: "left",
							axisLabel: {
								formatter: "{value}", // Format Y-axis values for total requests
							},
						},
						{
							type: "value",
							name: "Peak Rate",
							position: "right",
							axisLabel: {
								formatter: "{value} req/s", // Format Y-axis values for peak rate
							},
						},
					],
					series: [
						{
							name: "Total SMS (SMPP)",
							type: "bar",
							data: totalReqsA, // Convert to thousands for display
							barWidth: "10%",
						},
						{
							name: "Total Reqs (API)",
							type: "bar",
							data: totalReqsB, // Convert to thousands for display
							barWidth: "10%",
						},
						{
							name: "Peak Rate (SMPP)",
							type: "line",
							yAxisIndex: 1, // Bind this line to the second Y-axis (right side)
							data: peakRateA,
							smooth: true, // Make the line curved
							lineStyle: {
								width: 3,
							},
							markLine: {
								data: [
									{
										yAxis: allowedRate,
										name: "Allowed Rate",
									},
								],
								label: {
									formatter: "",
								},
							},
						},
						{
							name: "Peak Rate (API)",
							type: "line",
							yAxisIndex: 1, // Bind this line to the second Y-axis (right side)
							data: peakRateB,
							smooth: true, // Make the line curved
							lineStyle: {
								width: 3,
							},
						},
					],
				};

				// Set the configured options on the chart
				historical_chart.setOption(historical_option);
			},
		});
	}
	function getSystemMonitorStats() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: app_url + "hypernode/monitor/stats",
			contentType: "application/json",
			beforeSend: function (xhr) {
				//Include the bearer token in header
				xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
			},
			crossDomain: true,
			headers: {
				accept: "application/json",
				"Access-Control-Allow-Origin": "*",
			},
			success: function (res) {
				//draw gauges
				drawRateGauges("now", "smpp", res.data.smpp.current, "smpp_now_traffic");
				drawRateGauges("now", "api", res.data.api.current, "api_now_traffic");
				drawRateGauges("peak", "smpp", res.data.smpp.peak, "smpp_peak_traffic");
				drawRateGauges("peak", "api", res.data.api.peak, "api_peak_traffic");
				drawRateGauges("avg", "smpp", res.data.smpp.average, "smpp_avg_traffic");
				drawRateGauges("avg", "api", res.data.api.average, "api_avg_traffic");
				//show total
				$("#smpp_total").text(res.data.smpp.total.toLocaleString() + " msgs");
				$("#api_total").text(res.data.api.total.toLocaleString() + " reqs");
			},
			error: function (err) {
				if (err.status == 500) {
					console.log(err);
					bootbox.alert(
						`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Something went wrong</div>`
					);
				} else {
					console.log(err.responseJSON);
					bootbox.alert(
						`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; ${err.responseJSON.message}</div>`
					);
				}
			},
		});
	}

	if (curpage == "system_mon") {
		//get stats and draw chart on initial page load
		if ($("#is_sysmon_admin").length > 0) {
			getSystemMonitorStats();
			//historical datepicker
			$("#sysmondp").daterangepicker(
				{
					ranges: {
						Today: ["today", "today"],
						Yesterday: ["yesterday", "yesterday"],
						"Last 7 Days": [
							Date.today().add({
								days: -6,
							}),
							"today",
						],
						"Last 30 Days": [
							Date.today().add({
								days: -29,
							}),
							"today",
						],
						"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
						"Last Month": [
							Date.today().moveToFirstDayOfMonth().add({
								months: -1,
							}),
							Date.today().moveToFirstDayOfMonth().add({
								days: -1,
							}),
						],
					},
					opens: "left",
					format: "MM/dd/yyyy",
					separator: " to ",
					startDate: Date.today().add({
						days: -29,
					}),
					endDate: Date.today(),
					minDate: "01/01/2012",
					maxDate: "12/31/2030",
					locale: {
						applyLabel: "Apply",
						clearLabel: "Cancel",
						customRangeLabel: "Custom Range",
						daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
						monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
						firstDay: 1,
					},
					showWeekNumbers: true,
					buttonClasses: ["btn-danger"],
				},
				function (start, end) {
					$("#sysmondp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
					//reload sms figures
					getHistoricalSysmonStats();
				}
			);
			//Set the initial state of the picker label
			$("#sysmondp span").html(
				Date.today()
					.add({
						days: -9,
					})
					.toString("MMM d, yyyy") +
					" - " +
					Date.today().toString("MMM d, yyyy")
			);
			getHistoricalSysmonStats();

			//auto refresh
			setInterval(getSystemMonitorStats, 3000);
		}

		//load users
		$.ajax({
			url: app_url + "getAllUserStatus",
			success: function (res) {
				var ustrar = [];
				ustrar = JSON.parse(res);

				$("#tab-online").html(ustrar.online);
				$("#tab-all").html(ustrar.all);
			},
		});

		//change schedule status
		$(document).on("change", ".togscstatus", function () {
			var cid = $(this).attr("data-cid");
			var cstatus = 0;
			if ($(this).is(":checked")) {
				cstatus = 1;
			}
			$.ajax({
				url: app_url + "changeCampaignStatus",
				method: "post",
				data: { cid: cid, status: cstatus, mode: "sch" },
				success: function (res) {
					bootbox.alert(res);
				},
			});
		});

		//maually send scheduled
		$(document).on("click", ".mansc", function () {
			var cid = $(this).attr("data-cid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to send this campaign right now?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//send now
						$.ajax({
							url: app_url + "sendScheduleManually",
							method: "post",
							data: { cid: cid },
							success: function (res) {
								bootbox.alert(res);
								//reload table
								$("#dt_sc")
									.dataTable()
									.api()
									.ajax.url(app_url + "getAllScheduledCampaigns")
									.load(function (jdata) {
										$(document)
											.find(".sswitch")
											.each(function () {
												var b = $(this),
													c = b.attr("data-color"),
													d = "#ffffff",
													e = "small";
												new Switchery(this, {
													color: c,
													size: e,
													jackColor: d,
												});
											});
									});
							},
						});
					}
				},
			});
		});

		setInterval(function () {
			//scheduled campaigns
			if ($("#sc_ref").is(":checked")) {
				$("#dt_sc")
					.dataTable()
					.api()
					.ajax.url(app_url + "getAllScheduledCampaigns")
					.load(function (jdata) {
						$(document)
							.find(".sswitch")
							.each(function () {
								var b = $(this),
									c = b.attr("data-color"),
									d = "#ffffff",
									e = "small";
								new Switchery(this, {
									color: c,
									size: e,
									jackColor: d,
								});
							});
					});
			}
			//online users
			if ($("#ou_ref").is(":checked")) {
				$.ajax({
					url: app_url + "getAllUserStatus",
					success: function (res) {
						var ustrar = [];
						ustrar = JSON.parse(res);

						$("#tab-online").html(ustrar.online);
						$("#tab-all").html(ustrar.all);
					},
				});
			}
		}, 10000);
	}

	//42. View Account: Route Settings

	if (curpage == "va_rset") {
		//for sms plans
		$("#save_changes").click(function () {
			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving SMS Plan Assignment"
				)}. . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#rsetform").attr("action", app_url + "saveUserPlanAssignment");
					$("#rsetform").submit();
				}, 200);
			});
		});

		//toggle route detail display
		$(".rtsel").on("change", function () {
			var rid = $(this).attr("data-rid");
			var dbox = "#rtdetail-" + rid;
			if ($(this).is(":checked")) {
				$(dbox).collapse("show");
			} else {
				$(dbox).collapse("hide");
			}
		});

		$("#savefrm").click(function () {
			//validate

			//submit form
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving route assignments"
				)}. . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#rsetform").attr("action", app_url + "saveRouteAssignments");
					$("#rsetform").submit();
				}, 200);
			});
		});
	}

	//43. View account: user actions
	if ($("#page_family").length > 0) {
		//we are in view account section
		$(document).on("click", "a.useraction", function () {
			var act = $(this).attr("data-act");
			var uid = $("#userid").val();

			//upgrade account
			if (act == "upgradeacc") {
				bootbox.confirm({
					message: SCTEXT("Are you sure you want to upgrade this account to Reseller? This action cannot be reversed."),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: SCTEXT("Yes, Proceed"),
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//convert
							$.ajax({
								url: app_url + "accountActions/upgrade/" + uid,
								method: "get",
								success: function (res) {
									window.location.reload();
								},
							});
						}
					},
				});
			}

			//change password
			if (act == "changepsw") {
				var errpass = 1;
				$("#resetPassBox").modal();

				$(document).on("click", "#resetPassSubmit", function () {
					if (errpass != 1) {
						//no errors
						var pass1 = $("#upass").val();
						var pass2 = $("#upass2").val();
						var uid = $("#userid").val();
						$(this).attr("disabled", "disabled").css("cursor", "progress");
						$.ajax({
							url: app_url + "passwordReset",
							method: "post",
							data: {
								user: uid,
								cat: "user",
								pass1: pass1,
								pass2: pass2,
							},
							success: function () {
								window.location.reload();
							},
						});
					}
				});

				//match passwords
				$(document).on("keyup blur", "#upass, #upass2", function () {
					var mode = $(this).attr("data-strength");
					var val = $(this).val();

					if (mode == "weak") {
						//length
						if (val.length < 6) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
						} else {
							errpass = 0;
							$("#pass-err").text("");
						}
					}

					if (mode == "average") {
						//length
						if (val.length < 8) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
						} else {
							errpass = 0;
							$("#pass-err").text("");
							//alphabet letter
							if (!/[a-zA-Z]/.test(val)) {
								errpass = 1;
								$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one alphabet letter."));
							} else {
								errpass = 0;
								$("#pass-err").text("");
								//numeric
								if (!/[0-9]/.test(val)) {
									errpass = 1;
									$("#pass-err")
										.removeClass("text-success")
										.addClass("text-danger")
										.text(SCTEXT("Password must have at least one numeric character."));
								} else {
									errpass = 0;
									$("#pass-err").text("");
								}
							}
						}
					}

					if (mode == "strong") {
						//length
						if (val.length < 8) {
							errpass = 1;
							$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password too short"));
						} else {
							errpass = 0;
							$("#pass-err").text("");
							//uppercase alphabet
							if (!/[A-Z]/.test(val)) {
								errpass = 1;
								$("#pass-err").removeClass("text-success").addClass("text-danger").text(SCTEXT("Password must have at least one uppercase letter."));
							} else {
								errpass = 0;
								$("#pass-err").text("");
								//numeric
								if (!/[0-9]/.test(val)) {
									errpass = 1;
									$("#pass-err")
										.removeClass("text-success")
										.addClass("text-danger")
										.text(SCTEXT("Password must have at least one numeric character."));
								} else {
									errpass = 0;
									$("#pass-err").text("");
									//special characters
									if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val)) {
										errpass = 1;
										$("#pass-err")
											.removeClass("text-success")
											.addClass("text-danger")
											.text(SCTEXT("Password must have at least one special character."));
									} else {
										errpass = 0;
										$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
									}
								}
							}
						}
					}
					if (errpass == 0) {
						//if everything is good match both passwords
						if ($("#upass").val() != $("#upass2").val()) {
							errpass = 1;
							$("#pass-err")
								.removeClass("text-success")
								.addClass("text-danger")
								.text(SCTEXT("Passwords do not match each other. Please re-type your password"));
						} else {
							errpass = 0;
							$("#pass-err").removeClass("text-danger").addClass("text-success").text(SCTEXT("Password is acceptable"));
						}
					}
				});
			}

			//suspend account
			if (act == "usersus") {
				bootbox.confirm({
					message: SCTEXT(
						"Suspending this account will also suspend any accounts added under this user. Complete user tree for this account will be disabled. Are you sure you want to proceed?"
					),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: SCTEXT("Yes, Proceed"),
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//suspend
							$.ajax({
								url: app_url + "accountActions/suspend/" + uid,
								method: "get",
								success: function (res) {
									window.location = app_url + "manageUsers";
								},
							});
						}
					},
				});
			}

			//delete account
			if (act == "userdel") {
				bootbox.confirm({
					message: SCTEXT(
						"Are you sure you want to delete this user account? This action cannot be undone. The available credits in this account will be added to your account or the corresponding reseller account. Shall we proceed?"
					),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: SCTEXT("Yes, Proceed"),
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//delete
							$.ajax({
								url: app_url + "accountActions/delete/" + uid,
								method: "get",
								success: function (res) {
									window.location = app_url + "manageUsers";
								},
							});
						}
					},
				});
			}
		});
	}

	//44. Manage suspended users
	if (curpage == "manage_iusers") {
		$(document).on("click", ".del-user", function () {
			var uid = $(this).attr("data-uid");
			bootbox.confirm({
				message: SCTEXT(
					"Are you sure you want to delete this user account? This action cannot be undone. The available credits in this account will be added to your account or the corresponding reseller account. Shall we proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes, Proceed"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "accountActions/delete/" + uid,
							method: "get",
							success: function (res) {
								window.location = app_url + "manageInactiveUsers";
							},
						});
					}
				},
			});
		});
	}

	//45. Buy SMS
	if (curpage == "buy_sms") {
		//show available credits and SMS rates
		$("#routesel").on("change", function () {
			var rtele = $("#routesel option:selected");
			//a. available credits
			$("#rtavcr").html(formatInt(parseInt(rtele.attr("data-acr"))));
			//b. sms rates
			$("#smsrate").html(app_currency + " " + rtele.attr("data-crt") + " per SMS");
			$("#smscredits").trigger("keyup");
		});

		//wallet credits for currency based accounts
		$("#walletcredits").on("keyup blur", function () {
			var rcr = $(this).val();
			var taxtype = SCTEXT("including all taxes");
			switch ($("#taxtype").val()) {
				case "VT":
					taxtype = "including " + $("#tax").val() + "% VAT";
					break;
				case "ST":
					taxtype = "including " + $("#tax").val() + "% Service Tax";
					break;
				case "SC":
					taxtype = "including " + $("#tax").val() + "% Service Charge";
					break;
				case "OT":
					taxtype = "including " + $("#tax").val() + "% Tax";
					break;
				case "GT":
					taxtype = "including " + $("#tax").val() + "% GST";
					break;
				default:
					taxtype = SCTEXT("including all taxes");
			}

			var tax = $("#tax").val() != "" ? (parseFloat($("#tax").val()) / 100) * rcr : 0;
			var total = parseFloat(rcr) + parseFloat(tax);
			$("#total_amt_payable").html(total.toLocaleString());
			$("#all_taxes").html("(" + taxtype + ")");
		});

		//format sms credits
		$("#smscredits").on("keyup blur", function () {
			var rcr = $(this).val().replace(/\s+/g, "");

			//only number allowed
			if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test(rcr) && rcr != "") {
				$(this).addClass("error-input");
				e.preventDefault();
				return;
			} else {
				$(this).removeClass("error-input");
			}

			var res = rcr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
			$(this).val(res);

			if ($("#ptype").length > 0 && $("#ptype").val() == "0") {
				//volume based plan
				var routencredits = [];
				var pid = $("#planid").val();
				var taxtype = "";
				var total = 0;
				var remwal = 0;
				var rdata = {
					id: $("#routesel option:selected").val(),
					credits: rcr,
				};
				routencredits.push(rdata);
				$.ajax({
					url: app_url + "getPlanSmsPriceOuter",
					method: "post",
					data: {
						plan: pid,
						routesData: JSON.stringify(routencredits),
					},
					success: function (res) {
						var myarr = [];
						myarr = JSON.parse(res);
						console.log(myarr);
						//you have the price and credits entered

						//update the rate received from the db in case a plan is chosen
						if (pid != "0") {
							for (grid in myarr.price) {
								$("#smsrate").html(app_currency + " " + myarr.price[grid].price + " per SMS");
								break; //run loop only once
							}
						}

						taxtype = myarr.plan_tax;
						total = myarr.grand_total;

						//check if wallet credits
						if ($("#walbal").val() > 0) {
							if ($("#cb-1").is(":checked")) {
								var totalpayable = total <= parseFloat($("#walbal").val()) ? 0 : total - parseFloat($("#walbal").val());

								$("#total_amt_payable").html(totalpayable.toLocaleString());
								$("#all_taxes_payable").html(taxtype);
								remwal = total >= parseFloat($("#walbal").val()) ? 0 : ($("#walbal").val() - total).toLocaleString();
								$("#remwal").text(app_currency + remwal);
							} else {
								//customer does not wanna use wallet credits
								var totalpayable = total;

								$("#total_amt_payable").html(totalpayable.toLocaleString());
								$("#all_taxes_payable").html(taxtype);
								remwal = $("#walbal").val().toLocaleString();
								$("#remwal").text(app_currency + remwal);
							}
						}

						$("#grand_total_amt").html(total.toLocaleString());
						$("#all_taxes").html(taxtype);
					},
				});
			} else {
				//regular route assignment
				//calculate sms cost

				var total = rcr * $("#routesel option:selected").attr("data-crt");

				//tax
				var taxtype = SCTEXT("including all taxes");
				switch ($("#taxtype").val()) {
					case "VT":
						taxtype = "including " + $("#tax").val() + "% VAT";
						break;
					case "ST":
						taxtype = "including " + $("#tax").val() + "% Service Tax";
						break;
					case "SC":
						taxtype = "including " + $("#tax").val() + "% Service Charge";
						break;
					case "OT":
						taxtype = "including " + $("#tax").val() + "% Tax";
						break;
					case "GT":
						taxtype = "including " + $("#tax").val() + "% GST";
						break;
					default:
						taxtype = SCTEXT("including all taxes");
				}

				total += $("#tax").val() != "" ? (parseFloat($("#tax").val()) / 100) * total : 0;

				//check if wallet credits
				if ($("#walbal").val() > 0) {
					if ($("#cb-1").is(":checked")) {
						var totalpayable = total <= parseFloat($("#walbal").val()) ? 0 : total - parseFloat($("#walbal").val());

						$("#total_amt_payable").html(totalpayable.toLocaleString());
						$("#all_taxes_payable").html(taxtype);
						remwal = total >= parseFloat($("#walbal").val()) ? 0 : ($("#walbal").val() - total).toLocaleString();
						$("#remwal").text(app_currency + remwal);
					} else {
						//customer does not wanna use wallet credits
						var totalpayable = total;

						$("#total_amt_payable").html(totalpayable.toLocaleString());
						$("#all_taxes_payable").html(taxtype);
						remwal = $("#walbal").val().toLocaleString();
						$("#remwal").text(app_currency + remwal);
					}
				} else {
					//no wallet balance
					var totalpayable = total;
					$("#total_amt_payable").html(totalpayable.toLocaleString());
				}

				$("#grand_total_amt").html(total.toLocaleString());
				$("#all_taxes").html("(" + taxtype + ")");
			}
		});

		//wallet deduction if applicable
		if ($("#cb-1").length > 0) {
			$("#cb-1").on("change", function () {
				$("#smscredits").trigger("keyup");
			});
		}

		$("#routesel").change();

		//payment
		$("#proceedtopay").click(function () {
			if ($("#smscredits").length > 0 && ($("#smscredits").val() == "" || $("#smscredits").val() <= 0)) {
				bootbox.alert(SCTEXT("Please enter valid SMS credits to purchase."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Confirming your order"
				)} ...</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 5000);
				setTimeout(function () {
					$("#bs_form").attr("action", app_url + "buyOrderCheckout");
					$("#bs_form").submit();
				}, 2000);
			});
		});
	}

	//46. Confirm purchase order
	if (curpage == "confirm_po") {
	}

	//47. App Settings
	if (curpage == "app_settings") {
		//
		$('.nav-tabs a[href="#' + $("#activeTabId").val() + '"]').tab("show");

		//fake dlr ratios
		$("#fdlrsel").on("change", function () {
			let a = $(this).val();
			if ((console.log(a), "" == a)) $("#fdlr_comp_ctr").html("-");
			else {
				let t = JSON.parse(a) || [],
					e = '<table class="table table-bordered bg-white"><tbody>';
				t.forEach((a) => {
					let t = 1 == a.type ? "success" : 2 == a.type ? "warning" : "danger";
					e += `<tr> <td> ${a.ratio}% </td> <td> <span class="label label-sm label-${t}">${a.dlrexp}</span> </td> </tr>`;
				}),
					(e += "</tbody></table>"),
					$("#fdlr_comp_ctr").html(e);
			}
		});
		$("#fdlrsel").trigger("change");

		//save settings
		$(".save_settings").each(function () {
			$(this).on("click", function () {
				var ele = $(this);
				var dialog = bootbox.dialog({
					closeButton: false,
					message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
						"Saving App Settings"
					)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
				});
				dialog.init(function () {
					$("#stbar").animate({ width: "100%" }, 300);
					setTimeout(function () {
						$("#" + ele.attr("data-form")).attr("action", app_url + "saveAppSettings");
						$("#" + ele.attr("data-form")).submit();
					}, 100);
				});
			});
		});

		//main settings

		//date time default selection
		$("#timezone option").each(function () {
			if ($(this).val() == $("#deftz").val()) {
				$(this).attr("selected", "selected");
			}
		});
		$("#timezone").trigger("change.select2");

		//date format examples
		$(".datefrmt").each(function () {
			var ele = $(this);
			ele.on("change keyup focus blur", function () {
				$.ajax({
					url: app_url + "generateDateExample",
					type: "post",
					data: { dtype: ele.val() },
					success: function (res) {
						ele.next().html(res);
					},
				});
			});
		});
	}

	//48. Power Grid
	if (curpage == "power_grid") {
		//get pm2 process list with status
		$.ajax({
			type: "get",
			dataType: "json",
			url: app_url + "hypernode/pm2/stats",
			contentType: "application/json",
			beforeSend: function (xhr) {
				//Include the bearer token in header
				xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
			},
			crossDomain: true,
			headers: {
				accept: "application/json",
				"Access-Control-Allow-Origin": "*",
			},
			success: function (res) {
				let data = res.data;
				//populate the table
				let rows = "";
				for (let i = 0; i < data.length; i++) {
					let name_str = `<i class="indicator ${
						data[i].status == "online" ? "online" : "offline"
					}"></i> <kbd class="m-l-sm bg-white text-inverse fz-md"><b>${data[i].name}</b></kbd>`;
					let pulse = !data[i].pulse
						? '<span title="pulse ' + data[i].timeElapsedInMinutes + ' mins ago" class="label label-success pointer">running</span>'
						: '<span title="pulse ' + data[i].timeElapsedInMinutes + ' mins ago" class="label label-danger pointer">stopped</span>';
					let pm2Reloadbtn =
						'<button title="Reload" class="btn btn-xs btn-primary pm2btn" data-proc="' + data[i].name + '" type="button">Restart Process</button>';
					rows +=
						"<tr><td>" +
						name_str +
						"</td><td> " +
						data[i].memory +
						"</td><td> " +
						data[i].cpu +
						"</td><td> " +
						data[i].uptime +
						"</td><td> " +
						(data[i].lastRun == "N/A" ? "<kbd>n/a</kbd>" : pulse) +
						"</td><td> " +
						pm2Reloadbtn +
						"</td></tr>";
				}
				$("#pm2list").html(rows);
			},
			error: function (err) {
				if (err.status == 500) {
					console.log(err);
					bootbox.alert(
						`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Something went wrong</div>`
					);
				} else {
					console.log(err.responseJSON);
					bootbox.alert(
						`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; ${err.responseJSON.message}</div>`
					);
				}
			},
		});

		//reload individual process
		$(document).on("click", ".pm2btn", function () {
			let proc = $(this).attr("data-proc");
			$.ajax({
				type: "get",
				dataType: "json",
				url: app_url + "hypernode/pm2/reload/" + proc,
				contentType: "application/json",
				beforeSend: function (xhr) {
					//Include the bearer token in header
					xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
				},
				crossDomain: true,
				headers: {
					accept: "application/json",
					"Access-Control-Allow-Origin": "*",
				},
				success: function (res) {
					bootbox.alert(
						`<div class="alert alert-custom alert-success"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-check-circle fa-2x"></i>&nbsp; Command sent successfully</div>`
					);
				},
				error: function (err) {
					if (err.status == 500) {
						console.log(err);
						bootbox.alert(
							`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Something went wrong</div>`
						);
					} else {
						console.log(err.responseJSON);
						bootbox.alert(
							`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; ${err.responseJSON.message}</div>`
						);
					}
				},
			});
		});

		//maintenance mode
		$("#mm-y, #mm-n").click(function () {
			var sel = $(this).val();
			if (sel == "1") {
				$("#mm-opts").removeClass("disabledBox");
			} else {
				$("#mm-opts").addClass("disabledBox");
			}
		});
		//datetime picker for mm mode
		$("#mmdp").datetimepicker({
			minDate: moment(),
			showClose: true,
			icons: {
				close: "fa-times-circle m-t-xs m-b-xs label-info label label-flat label-md",
			},
			sideBySide: true,
			toolbarPlacement: "bottom",
			format: "ddd, Do MMM YYYY HH:mm",
		});
		if ($("#deftime").val() != "") $("#mmdp").val($("#deftime").val());
		//submit mm data
		$("#submit_mm").on("click", function () {
			var ele = $(this);
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#mm_form").attr("action", app_url + "saveMiscVars");
					$("#mm_form").submit();
				}, 100);
			});
		});

		//submit archive task
		$("#save_archts").on("click", function () {
			if (isNaN($("#arch_ts").val()) || $("#arch_ts").val() == "") {
				bootbox.alert("Enter a valid numeric value for days.");
				return false;
			}
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Updating auto-archive setting"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#arch_form").attr("action", app_url + "updateAutoArchiver");
					$("#arch_form").submit();
				}, 100);
			});
		});
	}

	//49. Watchman Log

	if (curpage == "watchman_log") {
		//datepicker
		$("#wml-dp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#wml-dp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-wmlog")
					.dataTable()
					.api()
					.ajax.url(app_url + "getWatchmanLog/" + $("#wml-dp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#wml-dp span").html("Select Date");
	}

	//49. DB Archive Log

	if (curpage == "dbarchive_log") {
		//datepicker
		$("#dal-dp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#dal-dp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-dalog")
					.dataTable()
					.api()
					.ajax.url(app_url + "getDbArchiveLog/" + $("#dal-dp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#dal-dp span").html("Select Date");
	}

	//50. Website Leads

	if (curpage == "webleads") {
		//filter results
		$("#dt_leadfilter").click(function () {
			//get selected option and reload
			$("#dt_web_leads")
				.dataTable()
				.api()
				.ajax.url(app_url + "getWebsiteLeads/" + $("#leadfilter option:selected").val())
				.load();
		});
	}

	//51. Security Log
	if (curpage == "security_log") {
		//datepicker
		$("#secl-dp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#secl-dp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-seclog")
					.dataTable()
					.api()
					.ajax.url(app_url + "getSusActivityLog/" + $("#secl-dp span").html())
					.load();
			}
		);
	}

	//52. Blocked IP List

	if (curpage == "blocked_ip_list") {
		//datepicker
		$("#blip-dp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#blip-dp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				$("#t-blip")
					.dataTable()
					.api()
					.ajax.url(app_url + "getBlockedIpList/" + $("#blip-dp span").html())
					.load();
			}
		);
	}

	//53. Phonebook database

	if (curpage == "manage_pbdb") {
		//toggle status
		$(document).on("change", ".pbdbstatus", function () {
			var val = 0;
			if ($(this).is(":checked")) {
				val = "1";
			}
			var gid = $(this).val();
			$.ajax({
				method: "post",
				url: app_url + "setPhonebookStatus",
				data: { value: val, gid: gid },
			});
		});
		//action
		$(document).on("click", ".delpbdb", function () {
			var gid = $(this).attr("data-gid");
			var gcount = $(this).attr("data-gcount");
			bootbox.confirm({
				message: SCTEXT(
					"Deleting this group will remove this from Phonebook list for all clients and will delete all the contacts present in this group. Are you sure you want to proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deletePhonebookDb/" + gid;
					}
				},
			});
		});
	}

	if (curpage == "add_pbdb" || curpage == "edit_pbdb") {
		//submit
		$("#save_changes").on("click", function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Phonebook Name"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#add_pbdb_form").attr("action", app_url + "savePhonebookDb");
					$("#add_pbdb_form").submit();
				}, 100);
			});
		});

		$("#bk").click(function () {
			window.location = app_url + "phonebook";
		});
	}

	if (curpage == "add_pbcontacts") {
		//submit
		$("#save_changes").click(function () {
			//check if any upload is in progress
			if ($("#uprocess").val() == 1) {
				bootbox.alert(SCTEXT("File upload is in progress. Kindly wait for upload to finish or Cancel Upload & proceed."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Importing Contacts. This may take a while. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#pbct_form").attr("action", app_url + "savePhonebookContacts");
					$("#pbct_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "viewPhonebookContacts/" + $("#pbdbid").val();
		});
	}

	if (curpage == "edit_pbcontact") {
		$("#save_changes").click(function () {
			//check if any upload is in progress
			if ($("#pbcontact").val() == "" || !isValidPhone($("#pbcontact").val())) {
				bootbox.alert(SCTEXT("Invalid mobile number provided. Please try again."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Contact"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#edit_pbct_form").attr("action", app_url + "savePhonebookContacts");
					$("#edit_pbct_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "viewPhonebookContacts/" + $("#gid").val();
		});
	}

	//54. Short URL

	if (curpage == "manage_tinyurl") {
		$(document).on("click", ".del-surl", function () {
			var urlid = $(this).attr("data-urlid");
			bootbox.confirm({
				message: SCTEXT("This action will stop redirecting this short URL. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteShortUrl/" + urlid;
					}
				},
			});
		});
	}

	if (curpage == "add_tinyurl") {
		//change type
		$("#urltype").on("change", function () {
			var desc = $("#urltype option:selected").attr("data-subtext");
			$("#urltype_desc").text(desc);
		});

		$("#save_changes").click(function () {
			//check
			if ($("#redurl").val() == "") {
				bootbox.alert(SCTEXT("Destination URL cannot be empty."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Generating Short URL"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#turl_form").attr("action", app_url + "generateShortUrl");
					$("#turl_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageShortUrls";
		});
	}

	//55. SSL Management

	if (curpage == "manage_ssl") {
		$(document).on("click", ".removeSSL", function () {
			var domain = $(this).attr("data-dom");
			bootbox.confirm({
				message: SCTEXT(
					"You are removing SSL for this user. All domains associated with this SSL will no longer have secure access. Are you sure you want to proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//ask for password
						bootbox.prompt({
							title: SCTEXT("Please enter server root SSH password"),
							inputType: "password",
							callback: function (pass) {
								if (pass) {
									var dialog = bootbox.dialog({
										message: `<p class="text-center"><i class="fa fa-large fa-spin fa-circle-o-notch"></i>&nbsp;&nbsp;${SCTEXT(
											"Connecting to server. Please wait"
										)} . . .</p>`,
										size: "small",
										closeButton: false,
									});
									//send password and perform action
									$.ajax({
										url: app_url + "sshTestConnection",
										method: "post",
										data: {
											rpass: pass,
										},
										success: function (res) {
											if (res == "OK") {
												//--begin
												var dialog = bootbox.dialog({
													closeButton: false,
													message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
														"Removing SSL. This may take a few minutes"
													)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
												});
												dialog.init(function () {
													$("#stbar").animate({ width: "100%" }, 20000);
													setTimeout(function () {
														window.location = app_url + "removeSSL/" + domain;
													}, 50);
												});
												//--end
											} else {
												location.reload();
											}
										},
									});
								}
							},
						});
						//end of ssl uninstall confirm
					}
				},
			});
		});
	}

	if (curpage == "add_ssl") {
		$("#domresfil").on("change", function () {
			var domstr = $("#domresfil option:selected").attr("data-doms");
			var domar = domstr.split(",");
			var domstr = "";
			for (var i = 0; i < domar.length; i++) {
				domstr +=
					'<div class="checkbox checkbox-success"><input name="seldoms[]" value="' +
					domar[i] +
					'" type="checkbox" id="cb-' +
					i +
					'" checked="checked"><label for="cb-' +
					i +
					'">' +
					domar[i] +
					"</label></div>";
			}
			$("#domainctr").html(domstr);
		});

		//submit form
		$("#save_changes").click(function () {
			bootbox.prompt({
				title: SCTEXT("Please enter server root SSH password"),
				inputType: "password",
				callback: function (pass) {
					if (pass) {
						var dialog = bootbox.dialog({
							message: `<p class="text-center"><i class="fa fa-large fa-spin fa-circle-o-notch"></i>&nbsp;&nbsp;${SCTEXT(
								"Connecting to server. Please wait"
							)} . . .</p>`,
							size: "small",
							closeButton: false,
						});
						//send password and perform action
						$.ajax({
							url: app_url + "sshTestConnection",
							method: "post",
							data: {
								rpass: pass,
							},
							success: function (res) {
								if (res == "OK") {
									//--begin
									var dialog = bootbox.dialog({
										closeButton: false,
										message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
											"Installing SSL. This may take a few minutes"
										)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
									});
									dialog.init(function () {
										$("#stbar").animate({ width: "100%" }, 20000);
										setTimeout(function () {
											$("#add_ssl_form").attr("action", app_url + "installNewSSL");
											$("#add_ssl_form").submit();
										}, 50);
									});
									//--end
								} else {
									location.reload();
								}
							},
						});
					}
				},
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageSSL";
		});
	}

	//------------------- 2-WAY Messaging ------------------------//

	if (curpage == "inbox") {
		$("#vmnsel").on("change", function () {
			$("#dt_mosms")
				.dataTable()
				.api()
				.ajax.url(app_url + "getAllIncomingSms/" + $("#vmnsel").val() + "/" + $("#inboxdp span").html())
				.load();
		});

		//date pkr dlr
		$("#inboxdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#inboxdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				var campaignid = $("#campsel").val() || 0;
				$("#dt_mosms")
					.dataTable()
					.api()
					.ajax.url(app_url + "getAllIncomingSms/" + $("#vmnsel").val() + "/" + $("#inboxdp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#inboxdp span").html("Select Date");

		$(document).on("click", ".del-mo", function () {
			var sid = $(this).attr("data-moid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this SMS?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteIncomingSms/" + sid;
					}
				},
			});
		});
	}

	if (curpage == "view_mo") {
		$(document).on("click", ".del-mo", function () {
			var sid = $(this).attr("data-moid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this SMS?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteIncomingSms/" + sid;
					}
				},
			});
		});
	}

	if (curpage == "manage_vmn") {
		//delete vmn
		$(document).on("click", ".del-vmn", function () {
			var vid = $(this).attr("data-vmnid");
			bootbox.confirm({
				message: SCTEXT(
					"Please make sure this VMN is not assigned to any user and no Keywords in the system are associated with this number. Are you sure you want to proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteVmn/" + vid;
					}
				},
			});
		});
	}

	if (curpage == "add_vmn" || curpage == "edit_vmn") {
		//switch vmn type explanations
		$("#vmntype").on("change", function () {
			$("#vtypestr").text($("#vmntype option:selected").attr("data-title"));
		});
		//switch sender id selector
		$("#rsmpp").on("change", function () {
			var rtele = $("#rsmpp option:selected");

			// sender id box type, default sender id and max allowed length
			var stype = parseInt(rtele.attr("data-stype"));
			if (stype == 0) {
				//approval based sender id
				$("#sidselbox").removeClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 1) {
				//sender id not allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 2) {
				//open sender id allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").removeClass("hidden");
			}
		});
		$("#rsmpp").trigger("change");
		$("#vmntype").trigger("change");
		//submit
		$("#save_changes").click(function () {
			//check
			if ($("#vmn").val() == "") {
				bootbox.alert(SCTEXT("Virtual mobile number cannot be empty."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#vmnfrm").attr("action", app_url + "saveVmn");
					$("#vmnfrm").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageVmn";
		});
	}

	if (curpage == "manage_kw") {
		$(document).on("click", ".del-kw", function () {
			var kid = $(this).attr("data-kid");
			bootbox.confirm({
				message: SCTEXT("Incoming SMS with this keyword will not be matched. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteKeyword/" + kid;
					}
				},
			});
		});
	}

	if (curpage == "add_kw" || curpage == "edit_kw") {
		//submit
		$("#save_changes").click(function () {
			//check
			if ($("#keyword").val() == "") {
				bootbox.alert(SCTEXT("Primary keyword cannot be empty."));
				return;
			}
			if ($("#fwdmob").val() != "" && isValidPhone($("#fwdmob").val()) == false) {
				bootbox.alert(SCTEXT("Please enter a valid mobile number with country prefix."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#kwfrm").attr("action", app_url + "saveKeyword");
					$("#kwfrm").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageKeywords";
		});
	}

	if (curpage == "manage_cmpns") {
		$(document).on("click", ".del-cmpn", function () {
			var cid = $(this).attr("data-cid");
			bootbox.confirm({
				message: SCTEXT(
					"Please save all the opt-in and opt-out data from this campaign as they will also be deleted permanently with this campaign. Are you sure you want to delete this campaign?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteCampaign/" + cid;
					}
				},
			});
		});
	}

	if (curpage == "add_cmpn" || curpage == "edit_cmpn") {
		//switch optin availability based on keyword selection
		$("#pkeyword").on("change", function () {
			if ($("#pkeyword option:selected").val() == 0) {
				//disable
				//$("#optset").addClass("disabledBox");
			} else {
				//enable
				//$("#optset").removeClass("disabledBox");
			}
		});
		//switch sender id selector
		$("#rsmpp").on("change", function () {
			var rtele = $("#rsmpp option:selected");

			// sender id box type, default sender id and max allowed length
			var stype = parseInt(rtele.attr("data-stype"));
			if (stype == 0) {
				//approval based sender id
				$("#sidselbox").removeClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 1) {
				//sender id not allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").addClass("hidden");
			}
			if (stype == 2) {
				//open sender id allowed
				$("#sidselbox").addClass("hidden");
				$("#sidopnbox").removeClass("hidden");
			}
		});
		$("#rsmpp").trigger("change");
		$("#pkeyword").trigger("change");
		//submit
		$("#save_changes").click(function () {
			//check
			if ($("#cname").val() == "") {
				bootbox.alert(SCTEXT("Campaign name cannot be empty."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#cmpn_form").attr("action", app_url + "saveCampaign");
					$("#cmpn_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "campaigns";
		});
	}

	if (curpage == "cmpn_optin") {
		$(document).on("click", ".del-num", function () {
			var cid = $(this).attr("data-nid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this optin number?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteOptinNumber/" + cid;
					}
				},
			});
		});
	}

	if (curpage == "cmpn_optout") {
		$(document).on("click", ".del-num", function () {
			var cid = $(this).attr("data-nid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this optout number?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteOptoutNumber/" + cid;
					}
				},
			});
		});
	}

	/* -------------------- SMPP OUTBOUND API ---------------------*/

	if (curpage == "add_smppclient" || curpage == "edit_smppclient") {
		let errsystemid = 0;
		let errmsg = "";
		//validate system id
		$("#smpp_sysid").on("keyup blur", function () {
			let lid = $(this).val();
			$("#v-login").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
			if (lid.indexOf(" ") >= 0 || lid.length < 5) {
				$("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
				errsystemid = 1;
				errmsg = SCTEXT("Invalid System ID. Must be at least 5 characters without spaces.");
			} else {
				$("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
				errsystemid = 0;
				errmsg = "";
				//verify
				$.ajax({
					url: app_url + "checkAvailability",
					method: "post",
					data: { mode: "systemid", value: lid },
					success: function (res) {
						if (res == "FALSE") {
							$("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
							errsystemid = 1;
							errmsg = SCTEXT("System ID already exists. Please enter a different System ID.");
						} else {
							$("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
							errsystemid = 0;
							errmsg = "";
						}
					},
				});
			}
		});

		//save changes
		$("#save_changes").click(function () {
			//check
			if (errsystemid == 1) {
				bootbox.alert(errmsg);
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#smppclient_frm").attr("action", app_url + "saveSmppClient");
					$("#smppclient_frm").submit();
				}, 50);
			});
		});

		//back to settings
		$("#bk").click(function () {
			window.location = `${app_url}viewUserAccountSettings/${$("#userid").val()}`;
		});
	}

	if (curpage == "smppsms") {
		//show pdu data
		$(document).on("click", ".vsubmit_sm", function () {
			let pdata = $(this).attr("data-submitsm");
			if (pdata == "") {
				bootbox.alert("No PDU Formed");
			} else {
				bootbox.alert(`<pre>${JSON.stringify(JSON.parse(atob(pdata)).pdu, null, "\t")}</pre>`);
			}
		});
		$(document).on("click", ".vdel_pdu", function () {
			let delpdu = $(this).attr("data-delsm");
			let delpdures = $(this).attr("data-delsmres");
			let smtime = $(this).attr("data-smtime");
			let smrestime = $(this).attr("data-smrestime");
			if (delpdu == "") {
				bootbox.alert("No Deliver_sm PDU Sent yet");
			} else {
				let pdustr = `<span class="label label-primary">DELIVER_SM sent on ${smtime}</span><br>`;
				pdustr += `<pre>${JSON.stringify(JSON.parse(atob(delpdu)), null, "\t")}`;
				if (delpdures != "") {
					pdustr += `</pre><br><span class="label label-primary">DELIVER_SM_RESP received on ${smrestime}</span><br>`;
					pdustr += `<pre>${JSON.stringify(JSON.parse(atob(delpdures)), null, "\t")}</pre>`;
				} else {
					pdustr += `</pre><br><span class="label label-danger">No Acknowledgement (DELIVER_SM_RESP) Received</span>`;
				}
				bootbox.alert(pdustr);
			}
		});

		//download filetered results
		$("#smppdl").on("click", () => {
			let data = {
				clientid: $("#smppdl").attr("data-sid"),
				daterange: $("#smsdp span").html(),
			};
			window.open(`${app_url}globalFileDownload/smppsms/${btoa(JSON.stringify(data))}`, "_blank");
		});

		//date pkr dlr
		$("#smsdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#smsdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				let systemid = $("#systemid").val();
				$("#t-smppsms")
					.dataTable()
					.api()
					.ajax.url(`${app_url}getSmppSmsList/${systemid}/${$("#smsdp span").html()}`)
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#smsdp span").html("Select Date");
	}

	if (curpage == "gw_costprice") {
		let reloadtable = function () {
			$("#dt-routes-def-price_sorted")
				.dataTable()
				.api()
				.ajax.url(
					app_url +
						"getGatewayCostPriceSorted/" +
						$("#smppid").val() +
						"/" +
						$("#cvsel").val() +
						"/" +
						$("#opsel").val() +
						"/" +
						$('input[name="prefsel"]:checked').val()
				)
				.load(null, true);
		};
		$("#cvsel").on("change", function () {
			//show operators only from this country
			let selcv = $(this).val();
			$(".opitem").each(function () {
				if (selcv == 0 || $(this).attr("data-cpre") == selcv) {
					$(this).attr("disabled", false);
				} else {
					$(this).attr("disabled", "disabled");
				}
			});
			$("#opsel").select2();
			//reload list
			reloadtable();
		});
		$('input[name="prefsel"]').on("click", reloadtable);
		$("#opsel").on("change", reloadtable);
		//save price
		$(document).on("click", ".savepricing", function () {
			let ele = $(this);
			let smppid = ele.attr("data-smppid");
			let cc = ele.attr("data-cc");
			let mccmnc = ele.attr("data-mccmnc");
			let mode = ele.attr("data-mode");
			let cprc = parseFloat($(`#cp_${mccmnc}`).val()) || 0;
			ele.addClass("disabledBox").html('<i class="fas fa-lg fa-spin fa-circle-notch"></i>');

			if (cprc == 0) {
				bootbox.alert(`Please enter a valid price.`);
				ele.removeClass("disabledBox").html('<i class="fa fa-large fa-check"></i>');
				return;
			}

			//save
			$.ajax({
				url: app_url + "saveGatewayCostPrice",
				method: "post",
				data: {
					smppid: smppid,
					country: cc,
					mccmnc: mccmnc,
					mode: mode,
					price: cprc,
				},
				success: function (res) {
					bootbox.alert(`Pricing Saved Successfully`);
					ele.removeClass("disabledBox").html('<i class="fa fa-large fa-check"></i>');
					return;
				},
			});
		});
		//remove price
		$(document).on("click", ".delpricing", function () {
			let ele = $(this);
			let smppid = ele.attr("data-smppid");
			let mccmnc = ele.attr("data-mccmnc");
			let cc = ele.attr("data-cc");
			let mode = ele.attr("data-mode");
			ele.addClass("disabledBox").html('<i class="fas fa-lg fa-spin fa-circle-notch"></i>');

			//save
			$.ajax({
				url: app_url + "removeGatewayCostPrice",
				method: "post",
				data: {
					smppid: smppid,
					country: cc,
					mccmnc: mccmnc,
					mode: mode,
				},
				success: function (res) {
					bootbox.alert(`Pricing Removed Successfully`);
					$(`#cp_${mccmnc}`).val("");
					ele.removeClass("disabledBox").html('<i class="fa fa-large fa-trash"></i>');
					return;
				},
			});
		});
	}

	if (curpage == "import_cost_price") {
		//download sample file
		$("#dl_list_pref").click(function () {
			window.location = `${app_url}globalFileDownload/mccmnclist/${$('input[name="list_pref"]:checked').val()}`;
		});
		//save changes
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Importing Data"
				)} This may take few minutes. . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 2000);
				setTimeout(function () {
					$("#upload_rprc_form").attr("action", app_url + "importGatewayCostPrice");
					$("#upload_rprc_form").submit();
				}, 5);
			});
		});

		//back to settings
		$("#bk").click(function () {
			window.location = `${app_url}gatewayCostPrice/${$("#smppid").val()}`;
		});
	}

	if (curpage == "add_mplan" || curpage == "edit_mplan") {
		//based on route selection show countries
		$("#proutes").on("change", function () {
			let selections = `${$(this).val()}`.split(",") || [];
			let finalstr = "";
			let mapFilter = [];
			selections.forEach((element) => {
				let iso = $(`#routesel_${element}`).attr("data-iso");
				let country = $(`#routesel_${element}`).attr("data-country");
				if (country == undefined) return;
				if (mapFilter[iso] == undefined) {
					if (iso == "global") {
						finalstr += `<span class="label label-primary m-r-xs label-lg fz-sm"> <i class="fa fa-lg fa-globe"></i> All Countries </span>`;
					} else {
						finalstr += `<span class="label label-primary m-r-xs label-lg fz-sm"> <img style="height:15px;" src="${app_url}global/img/flags/${iso}.png\"> ${country} </span>`;
					}
					mapFilter[iso] = 1;
				}
			});
			if (finalstr != "") {
				$("#country_list").html(finalstr);
			} else {
				$("#country_list").html("- No Route Selected -");
			}
		});
		$("#proutes").trigger("change");
		//save changes
		$("#save_changes").click(function () {
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#mplanfrm").attr("action", app_url + "saveMccmncPlan");
					$("#mplanfrm").submit();
				}, 50);
			});
		});

		//back to settings
		$("#bk").click(function () {
			window.location = `${app_url}mccmncRatePlans`;
		});
	}

	if (curpage == "mccmnc_plans") {
		$(document).on("click", ".delmplan", function () {
			let pid = $(this).attr("data-pid");
			let ucount = $(this).attr("data-ucount");
			if (ucount != "0") {
				bootbox.alert(
					"There are user accounts associated with this plan. Please assign them different MCC/MNC based SMS Plan before removing this plan."
				);
				return;
			}
			bootbox.confirm({
				message: SCTEXT("All the pricing information will also be deleted. Are you sure you want to delete this SMS Plan?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteMccmncSmsPlan/" + pid;
					}
				},
			});
		});
	}

	if (curpage == "mplan_route_prices") {
		let reloadtable = function () {
			$("#dt-plan-sel-price_sorted")
				.dataTable()
				.api()
				.ajax.url(
					app_url +
						"getPlanSellingPriceSorted/" +
						$("#planid").val() +
						"/" +
						$("#cvsel").val() +
						"/" +
						$("#opsel").val() +
						"/" +
						$('input[name="prefsel"]:checked').val()
				)
				.load(null, true);
		};
		$("#cvsel").on("change", function () {
			//show operators only from this country
			let selcv = $(this).val();
			$(".opitem").each(function () {
				if (selcv == 0 || $(this).attr("data-cpre") == selcv) {
					$(this).attr("disabled", false);
				} else {
					$(this).attr("disabled", "disabled");
				}
			});
			$("#opsel").select2();
			//reload list
			reloadtable();
		});
		$('input[name="prefsel"]').on("click", reloadtable);
		$("#opsel").on("change", reloadtable);

		//save pricing
		$(document).on("click", ".savePlanpricing", function () {
			let ele = $(this);
			ele.addClass("disabledBox").html('<i class="fas fa-lg fa-spin fa-circle-notch"></i>');
			let planid = ele.attr("data-planid");
			let mccmnc = ele.attr("data-mccmnc");
			let mode = ele.attr("data-mode");
			let routeid = ele.attr("data-routeid");
			let inputid = `${mccmnc}-planprice`;
			let price = parseFloat($(`#${inputid}`).val()) || 0;
			//save
			$.ajax({
				url: app_url + "saveMccMncPlanPrice",
				method: "post",
				data: {
					planid: planid,
					mccmnc: mccmnc,
					routeid: routeid,
					mode: mode,
					price: price,
				},
				success: function (res) {
					bootbox.alert(`Pricing Saved Successfully`);
					ele.removeClass("disabledBox").html('<i class="fa fa-large fa-check"></i>');
					return;
				},
			});
		});

		//remove pricing
		$(document).on("click", ".delPlanpricing", function () {
			let ele = $(this);
			ele.addClass("disabledBox").html('<i class="fas fa-lg fa-spin fa-circle-notch"></i>');
			let planid = ele.attr("data-planid");
			let mccmnc = ele.attr("data-mccmnc");
			let mode = ele.attr("data-mode");
			bootbox.confirm({
				message: SCTEXT(`Are you sure you want to delete pricing for this ${mode == 0 ? "country" : mode == 1 ? "operator" : "MCCMNC"}?`),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						$.ajax({
							url: app_url + "removeMccMncPlanPrice",
							method: "post",
							data: {
								planid: planid,
								mccmnc: mccmnc,
								mode: mode,
							},
							success: function (res) {
								bootbox.alert(`Pricing Removed Successfully`);
								ele.removeClass("disabledBox").html('<i class="fa fa-large fa-trash"></i>');
								let inputid = `${mccmnc}-planprice`;
								$(`#${inputid}`).val("");
								return;
							},
						});
					} else {
						ele.removeClass("disabledBox").html('<i class="fa fa-large fa-trash"></i>');
					}
				},
			});
		});
	}

	if (curpage == "hlrset") {
		$(document).on("click", ".delhlrchn", function () {
			let cid = $(this).attr("data-cid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this HLR API channel?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = `${app_url}deleteHlrApi/${cid}`;
					}
				},
			});
		});
	}

	if (curpage == "add_hlr" || curpage == "edit_hlr") {
		$("#prsel").on("change", function () {
			let ele = $("#prsel option:selected");
			let method = ele.attr("data-method");
			if (ele.val() == "") {
				$("#hlrdesc").html("Select a provider for more information");
			} else {
				let desc = `This HLR lookup API is provided by <span class="label label-success text-white">${ele.attr(
					"data-website"
				)}</span>. Make sure you have an account with them and provide the authentication details below.`;
				if (ele.attr("data-async") == "async") {
					desc += ` This API uses asynchronous callback to update HLR lookup. Hence reports are updated by a callback URL to our servers. Please configure following callback URL in SETTINGS section of your HLR provider's panel.<br><kbd>${app_url}hlrApiCallback/${ele.val()}/index</kbd>`;
				}
				if (method == "httpauth") {
					$("#apiparam_httpauth").removeClass("hidden");
					$("#apiparam_getpost").addClass("hidden");
				} else {
					$("#apiparam_httpauth").addClass("hidden");
					$("#apiparam_getpost").removeClass("hidden");
				}
				$("#hlrdesc").html(desc);
			}
		});
		$("#prsel").trigger("change");
		//save changes
		$("#save_changes").click(function () {
			if ($("#prsel").val() == undefined || $("#prsel").val() == "") {
				bootbox.alert("Please select a provider for HLR lookup channel.");
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving changes"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#hlrcfrm").attr("action", app_url + "saveHlrApi");
					$("#hlrcfrm").submit();
				}, 50);
			});
		});

		//back to settings
		$("#bk").click(function () {
			window.location = `${app_url}manageHlr`;
		});
	}

	if (curpage == "newhlrlookup") {
		//submit
		$("#save_changes").click(function () {
			if ($("#contactinput").val() == "") {
				bootbox.alert("Please enter mobile numbers to perform HLR lookup.");
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Submitting Lookup Request"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#hlr_form").attr("action", app_url + "submitHlrLookup");
					$("#hlr_form").submit();
				}, 50);
			});
		});

		//back to settings
		$("#bk").click(function () {
			window.location = `${app_url}viewHlrReports`;
		});
	}

	if (curpage == "hlrreports") {
		//download hlr
		$("#download_hlr").click(function () {
			let date_range = $("#hlrdp span").html();
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Processing request"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 1000);
				setTimeout(function () {
					window.location = `${app_url}downloadHlr/${date_range}`;
				}, 10);
			});
			setTimeout(function () {
				dialog.modal("hide");
			}, 500);
		});
		//show response
		$(document).on("click", ".showHlrData", function () {
			let hdata = $(this).attr("data-rinfo");
			//done to hide the price
			let hlrdata = JSON.parse(atob(hdata));
			//remove the price object if exists
			if (hlrdata.hasOwnProperty("price")) {
				delete hlrdata["price"];
			}
			bootbox.alert(`<pre>${JSON.stringify(hlrdata, null, "\t")}</pre>`);
		});
		//date filter
		$("#hlrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#hlrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
				//reload table
				var campaignid = $("#campsel").val() || 0;
				$("#t-hlrreports")
					.dataTable()
					.api()
					.ajax.url(app_url + "getHlrReports/" + $("#hlrdp span").html())
					.load();
			}
		);
		//Set the initial state of the picker label
		$("#hlrdp span").html("Select Date");
	}

	if (curpage == "manage_orules") {
		$(document).on("click", ".delovrl", function () {
			let rid = $(this).attr("data-rid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this Override Rule?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = `${app_url}deleteOverrideRule/${rid}`;
					}
				},
			});
		});
	}

	if (curpage == "add_orule" || curpage == "edit_orule") {
		$("#addtmp").click(function () {
			let tmpstr = `<div class="p-sm panel m-b-lg bg-info text-white" style="position: relative;">
        <a href="javascript:void(0);" class="plan-remove rmv_temp"><i class="fa fa-3x text-danger fa-minus-circle"></i> </a>
        <table class="table">
            <thead>
                <tr>
                    <th>Match</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">Sender ID</span>
                            <span class="input-group-addon">
                                <select name="sendermatch[]" class="form-control input-sm">
                                    <option value="0">Any</option>
                                    <option value="equal">Equals to</option>
                                    <option value="start">Starts With</option>
                                    <option value="end">Ends With</option>
                                    <option value="has">Contains</option>
                                    <option value="nothave">Does not contain</option>
                                </select>
                            </span>
                            <span class="input-group-addon">
                                <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="e.g. WEBSMS">
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <select name="senderreplace[]" class="form-control input-sm">
                                    <option value="0">No Action</option>
                                    <option value="replace">Replace With</option>
                                    <option value="prepend">Add Prefix</option>
                                    <option value="append">Append Suffix</option>
                                    <option value="remove">Remove Prefix</option>
                                </select>
                            </span>
                            <span class="input-group-addon">
                                <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. AUTOWEB">
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">Mobile No.</span>
                            <span class="input-group-addon">
                                <select name="mobilematch[]" class="form-control input-sm">
                                    <option value="0">Any</option>
                                    <option value="equal">Equals to</option>
                                    <option value="start">Starts With</option>
                                    <option value="end">Ends With</option>
                                </select>
                            </span>
                            <span class="input-group-addon">
                                <input name="mobileinput[]" type="text" class="form-control input-sm" placeholder="e.g. 243812556677">
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <select name="mobilereplace[]" class="form-control input-sm">
                                    <option value="0">No Action</option>
                                    <option value="reject">Reject Number</option>
                                    <option value="prepend">Append Prefix</option>
                                    <option value="append">Append Suffix</option>
                                    <option value="remove">Remove Prefix</option>
                                </select>
                            </span>
                            <span class="input-group-addon">
                                <input name="mobilereplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. 243">
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">SMS Text</span>
                            <span class="input-group-addon">
                                <select name="textmatch[]" class="form-control input-sm">
                                    <option value="0">Any</option>
                                    <option value="equal">Equals to</option>
                                    <option value="start">Starts With</option>
                                    <option value="end">Ends With</option>
                                    <option value="has">Contains</option>
                                    <option value="nothave">Does not contain</option>
                                </select>
                            </span>
                            <span class="input-group-addon">
                                <input name="textinput[]" type="text" class="form-control input-sm" placeholder="e.g. karl">
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <select name="textreplace[]" class="form-control input-sm">
                                    <option value="0">No Action</option>
                                    <option value="replace">Replace With</option>
                                    <option value="prepend">Add Prefix</option>
                                    <option value="append">Append Suffix</option>
                                    <option value="remove">Remove Prefix</option>
                                </select>
                            </span>
                            <span class="input-group-addon">
                                <input name="textreplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. k@rl">
                            </span>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>`;

			$("#templatebox").append(tmpstr);
		});

		$(document).on("click", ".rmv_temp", function () {
			$(this).parent().remove();
		});

		//submit form
		$("#save_changes").click(function () {
			if ($("#orname").val() == "") {
				bootbox.alert("Please enter name for the override rule.");
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Processing request"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#add_orule_frm").attr("action", app_url + "saveOverrideRule");
					$("#add_orule_frm").submit();
				}, 50);
			});
		});

		//back to settings
		$("#bk").click(function () {
			window.location = `${app_url}overrideRules`;
		});
	}

	//------------ Media -------------//
	if (curpage == "manage_media") {
		$(document).on("click", ".del-med", function () {
			var kid = $(this).attr("data-mid");
			bootbox.confirm({
				message: SCTEXT("Deleting this will also disable tracking for this file. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteCampaignMedia/" + kid;
					}
				},
			});
		});
	}
	if (curpage == "add_media") {
		//submit
		$("#save_changes").click(function () {
			if ($("#mtitle").val() == "") {
				bootbox.alert(SCTEXT("Please enter a title for this file."));
				return;
			}
			//check if any upload is in progress
			if ($("#uprocess").val() == 1) {
				bootbox.alert(SCTEXT("File upload is in progress. Kindly wait for upload to finish or Cancel Upload & proceed."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Uploading Media. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#cmed_form").attr("action", app_url + "saveCampaignMedia");
					$("#cmed_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageCampaignMedia";
		});
	}
	//------------ TLV ---------------//
	if (curpage == "manage_tlv") {
		//load all tlv
		$.ajax({
			url: `${app_url}listAllTlvParams`,
			success: function (res) {
				$("#tlv_container").html(res);
			},
		});
		//hover
		$(document)
			.on("mouseenter", ".tlvboxes", function () {
				$(this).addClass("opacity-3").next().show();
			})
			.on("mouseleave", ".tlvboxes", function () {
				$(this).removeClass("opacity-3").next().hide();
			})
			.on("click", ".deltlv", function () {
				let tlvid = $(this).attr("data-tlvid");
				bootbox.confirm({
					message: SCTEXT("Are you sure you want to delete this SMPP TLV tag?"),
					buttons: {
						cancel: {
							label: SCTEXT("No"),
							className: "btn-default",
						},
						confirm: {
							label: SCTEXT("Yes"),
							className: "btn-info",
						},
					},
					callback: function (result) {
						if (result) {
							//delete
							window.location = `${app_url}deleteSmppTlv/${tlvid}`;
						}
					},
				});
			});
	}

	if (curpage == "add_tlv" || curpage == "edit_tlv") {
		//submit
		$("#save_changes").click(function () {
			if ($("#tlv_title").val() == "" || $("#tlv_name").val() == "" || $("#tlv_tag").val() == "" || $("#tlv_length").val() == "") {
				bootbox.alert(SCTEXT("All fields are mandatory. Please fill all values."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving TLV. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#tlvfrm").attr("action", app_url + "saveSmppTlv");
					$("#tlvfrm").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageSmppTlv";
		});
	}

	if (curpage == "manage_client_tlv") {
		//hover
		$(document).on("click", ".delutlv", function () {
			let tlvid = $(this).attr("data-tlvid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this Parameter?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = `${app_url}deleteClientTlv/${tlvid}`;
					}
				},
			});
		});
	}

	if (curpage == "add_client_tlv") {
		//based on route selection display TLV categories selector
		$("#routesel").on("change", function () {
			let rtele = $("#routesel option:selected");
			//f. show tlv
			let tlv_ids = rtele.attr("data-tlvs");
			if (tlv_ids !== "") {
				$("#tlv_box").removeClass("disabledBox");
				//render selecting box
				let tlv_cats = JSON.parse(tlv_ids);
				if (tlv_cats.length > 0) {
					let tlv_html = ``;
					for (const tlv_type of tlv_cats) {
						tlv_html += `<option value="${tlv_type}">${tlv_type}</option>`;
					}
					$("#tlv_type").html(tlv_html);
					$("#tlv_type").select2();
				}
			} else {
				$("#tlv_box").addClass("disabledBox");
				if (rtele.val() != "0") {
					bootbox.alert("This Route does not allow TLV. Please select another route");
					return false;
				}
			}
		});

		//submit
		$("#save_changes").click(function () {
			if ($("#tlv_value").val() == "") {
				bootbox.alert(SCTEXT("Parameter value cannot be empty."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Parameter. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#ctlv_form").attr("action", app_url + "saveClientTlv");
					$("#ctlv_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = `${app_url}manageClientTlv`;
		});
	}

	//------ OTP channels ---------//
	if (curpage == "manage_otp_channels") {
		$(document).on("click", ".del-otpch", function () {
			var cid = $(this).attr("data-cid");
			bootbox.confirm({
				message: SCTEXT("OTP APIs using this channel will not work. Are you sure you want to delete this channel?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteOtpChannel/" + cid;
					}
				},
			});
		});
	}
	if (curpage == "add_otp_channel" || curpage == "edit_otp_channel") {
		//show tlv selection if route requires it
		$("#otproute").on("change", function () {
			let rtele = $("#otproute option:selected");
			let otptlv_form = $("#otptlvs").length > 0 ? $("#otptlvs").val() : "null";
			//pass these to get select boxes with user tlvs
			let tlv_cats = rtele.attr("data-tlvs");
			if (tlv_cats !== "") {
				//render selecting box
				$.ajax({
					url: `${app_url}getUserTlvList/${rtele.val()}`,
					success: function (res) {
						let tlvdata = JSON.parse(res);
						let tlv_titles = Object.keys(tlvdata);
						if (tlv_titles.length > 0) {
							let tlv_html = ``;
							let otptlvs = otptlv_form == "null" ? [] : JSON.parse(otptlv_form);
							for (const tlv_name of tlv_titles) {
								let tlv_list = tlvdata[tlv_name];
								tlv_html += `<div class="form-group tlv_boxes"> <label class="control-label col-md-3">${tlv_name}:</label><div class="col-md-8"><select class="tlv_controls form-control" data-plugin="select2" name="tlv[]">`;
								//populate select boxes
								for (const tlv of tlv_list) {
									tlv_html += `<option`;
									if (otptlvs.includes(`${tlv_name}||${tlv.value}`)) {
										tlv_html += ` selected="selected" `;
									}
									tlv_html += ` value="${tlv_name}||${tlv.value}">${tlv.title}</option>`;
								}

								tlv_html += `</select> </div></div>`;
							}
							$(".tlv_boxes").remove();
							$("#sid_box").before(tlv_html);
							$(".tlv_controls").select2();
						}
					},
				});
			} else {
				$(".tlv_boxes").remove();
			}
		});
		$("#otproute").trigger("change");
		//submit
		$("#save_changes").click(function () {
			if ($("#otph_title").val() == "") {
				bootbox.alert(SCTEXT("Please enter a title for this channel."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving OTP Channel. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#otpch_form").attr("action", app_url + "saveOtpChannel");
					$("#otpch_form").submit();
				}, 50);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "manageOtpChannels";
		});
	}

	//------ Verified SMS ------//
	if (curpage == "manage_vsms_agents") {
		//view details popup
		$(document).on("click", ".view-vsmsagent", function () {
			let vaid = $(this).attr("data-aid");
			$("#vadetails-name").text($(`#vaname-${vaid}`).text());
			$("#vadetails-desc").text($(`#vadesc-${vaid}`).text());
			$("#vadetails-logo").attr("src", $(`#valogo-${vaid}`).attr("src"));
			let sids = $(`#vasids-${vaid}`).val() == "" ? [] : eval("(" + $(`#vasids-${vaid}`).val() + ")");
			let sidstr = "";
			sids.forEach((sid) => {
				sidstr += `<button class="btn btn-xs btn-primary m-b-xs">${sid}</button><br>`;
			});
			$("#vadetails-senders").html(sidstr);
			$("#vadetailsbox").modal();
		});
		//delete
		$(document).on("click", ".del-vsmsagent", function () {
			var aid = $(this).attr("data-aid");
			bootbox.confirm({
				message: SCTEXT("This Agent will be removed. Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete
						window.location = app_url + "deleteVsmsAgent/" + aid;
					}
				},
			});
		});
	}
	if (curpage == "add_vsms_agent" || curpage == "edit_vsms_agent") {
		//--
		$("#cov").trigger("change.select2");
		//submit
		$("#save_changes").click(function () {
			if ($("#vsms_aname").val() == "") {
				bootbox.alert(SCTEXT("Agent name cannot be empty."));
				return;
			}
			if ($("#vsms_adesc").val() == "") {
				bootbox.alert(SCTEXT("Agent description cannot be empty."));
				return;
			}
			if ($("#vsmssids").val() == "") {
				bootbox.alert(SCTEXT("Please select at least one approved sender ID."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Verified-SMS Agent. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#vsmsa_form").attr("action", app_url + "saveVsmsAgent");
					$("#vsmsa_form").submit();
				}, 500);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = `${app_url}manageVsmsAgents`;
		});
	}

	if (curpage == "add_apivendor" || curpage == "edit_apivendor") {
		//submit
		$("#save_changes").click(function () {
			if ($("#vapi_title").val() == "") {
				bootbox.alert(SCTEXT("Vendor API title cannot be empty."));
				return;
			}
			if ($("#smsc_id").val() == "") {
				bootbox.alert(SCTEXT("SMSC ID cannot be empty."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving API Vendor. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#add_vapi_form").attr("action", app_url + "saveApiVendor");
					$("#add_vapi_form").submit();
				}, 500);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = `${app_url}manageApiVendors`;
		});
	}

	if (curpage == "wh_reports") {
		$("#whrdp").daterangepicker(
			{
				ranges: {
					Today: ["today", "today"],
					Yesterday: ["yesterday", "yesterday"],
					"Last 7 Days": [
						Date.today().add({
							days: -6,
						}),
						"today",
					],
					"Last 30 Days": [
						Date.today().add({
							days: -29,
						}),
						"today",
					],
					"This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					"Last Month": [
						Date.today().moveToFirstDayOfMonth().add({
							months: -1,
						}),
						Date.today().moveToFirstDayOfMonth().add({
							days: -1,
						}),
					],
				},
				opens: "left",
				format: "MM/dd/yyyy",
				separator: " to ",
				startDate: Date.today().add({
					days: -29,
				}),
				endDate: Date.today(),
				minDate: "01/01/2012",
				maxDate: "12/31/2030",
				locale: {
					applyLabel: "Apply",
					clearLabel: "Cancel",
					customRangeLabel: "Custom Range",
					daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					firstDay: 1,
				},
				showWeekNumbers: true,
				buttonClasses: ["btn-danger"],
			},
			function (start, end) {
				$("#whrdp span").html(start.toString("MMM d, yyyy") + " - " + end.toString("MMM d, yyyy"));
			}
		);
		//Set the initial state of the picker label
		$("#whrdp span").html("Select Date");
	}

	if (curpage == "composeRCS") {
		$(".rctp").on("click", function () {
			let tp = $(this).val();
			if (tp == 0) {
				$("#rcst").removeClass("hidden");
				$("#rcsr").addClass("hidden");
				$("#rcsc").addClass("hidden");
			}
			if (tp == 1) {
				$("#rcst").addClass("hidden");
				$("#rcsr").removeClass("hidden");
				$("#rcsc").addClass("hidden");
			}
			if (tp == 2) {
				$("#rcst").addClass("hidden");
				$("#rcsr").addClass("hidden");
				$("#rcsc").removeClass("hidden");
			}
		});
		$("#rcsel").on("change", function () {
			let did = $(this).val();
			if (did == "rc1") {
				$("#rc1").removeClass("hidden");
				$("#rc2").addClass("hidden");
			} else {
				$("#rc2").removeClass("hidden");
				$("#rc1").addClass("hidden");
			}
		});
	}

	if (curpage == "send_whatsapp") {
		$("#waba").on("change", function () {
			let ele = $("#waba option:selected");
			$("#wbname2").html(ele.attr("data-name"));
			$("#preview_locked_notif_sender").html(ele.attr("data-name"));
			$("#wbpic").attr("alt", ele.attr("data-name"));
			$("#wbpic").attr("src", ele.attr("data-pic"));
			$("#wbphn").html(ele.text());
		});
		$("#waba").trigger("change");
		//show dynamic variable options
		let colstr = "";

		$("#wtemp").on("change", function () {
			let ele = $("#wtemp option:selected");
			if (ele.val() == 0 || ele.attr("data-vars") == "0,0,0") {
				console.log("no vars");
				//hide columnselection box
				$("#wa_colsel").html("").addClass("hidden");
				//enable enter mobile number tab
				$("#contactinput").attr("disabled", false);
			} else {
				let colhtml = "";
				let varlist = ele.attr("data-vars").split(",");
				console.log(varlist);
				if (varlist[0] > 0) {
					//header column
					colhtml += `<div class="input-group m-sm"><span class="input-group-addon">Header Variable</span><select class="form-control input-sm" name="headervarcol">${colstr}</select> </div>`;
				}
				if (varlist[1] > 0) {
					//body column
					for (let i = 0; i < varlist[1]; i++) {
						colhtml += `<div class="input-group m-sm"><span class="input-group-addon">Body Variable ${
							i + 1
						}</span><select class="form-control input-sm" name="bodyvarcol[]">${colstr}</select> </div>`;
					}
				}
				if (varlist[2] > 0) {
					//btn column
					for (let i = 0; i < varlist[2]; i++) {
						colhtml += `<div class="input-group m-sm"><span class="input-group-addon">URL Button Var ${
							i + 1
						}</span><select class="form-control input-sm" name="btnvarcol[]">${colstr}</select> </div>`;
					}
				}
				console.log(colhtml);
				//append in the box
				$("#contactinput").attr("disabled", true);
				$("#wa_colsel").html(colhtml).removeClass("hidden");
			}
			//on template change trigger preview update
			if (ele.val() != 0) {
				//clear initially
				$("#wt_header").html("");
				$("#wt_body").html("");
				$("#wt_footer").html("");
				$("#wt_btns").html("");
				let tempvars = ele.val().split("|");
				let tlang = tempvars[1];
				if (["ar", "fa_IR", "fars", "Per", "he", "iw", "KOR", "ko", "ur", "uz"].includes(tlang)) {
					$("#wa_preview_temp").css({
						"text-align": "right",
						direction: "rtl",
					});
				} else {
					$("#wa_preview_temp").css({
						"text-align": "left",
						direction: "ltr",
					});
				}
				let components = JSON.parse(atob(tempvars[2]));
				let header_data = components.filter((item) => item.type == "HEADER");
				if (header_data[0]) {
					//append header image or text
					if (header_data[0].format == "IMAGE") {
						$("#wt_header").html(`<img style="max-width: 120px;" src="${header_data[0].example.header_handle}">`);
					}
					if (header_data[0].format == "TEXT") {
						$("#wt_header").html(`<h4>${header_data[0].text.replace(/{{(\d+)}}/g, "<kbd>var $1</kbd>")}</h4>`);
					}
				}
				let body_data = components.filter((item) => item.type == "BODY");
				if (body_data[0]) {
					//append body text and replace {{1}}, {{2}}, etc. with kbd tags
					$("#wt_body").html(body_data[0].text.replace(/{{(\d+)}}/g, "<kbd>var $1</kbd>"));
				}
				let footer_data = components.filter((item) => item.type == "FOOTER");
				if (footer_data[0]) {
					//append footer
					$("#wt_footer").html(footer_data[0].text);
				}
				let buttons_data = components.filter((item) => item.type == "BUTTONS");
				if (buttons_data[0]) {
					let buttons = buttons_data[0].buttons;
					//append buttons
					$("#wt_btns").html("");
					let btnstr = "";
					buttons.forEach((item) => {
						let icon =
							item.type == "URL"
								? `<i class="fa fa-link"></i>`
								: item.type == "PHONE_NUMBER"
								? `<i class="fa fa-phone"></i>`
								: item.type == "QUICK_REPLY"
								? `<i class="fa fa-reply"></i>`
								: item.type == "COPY_CODE"
								? `<i class="fa fa-copy"></i>`
								: "";
						btnstr += `<button class="btn whatsapp-plain-button" type="button"> ${icon} ${item.text}</button>`;
					});
					$("#wt_btns").html(btnstr);
				}
			} else {
				//clear preview and ask to select template
				$("#wt_header").html("- SELECT A TEMPLATE -");
				$("#wt_body").html("");
				$("#wt_footer").html("");
				$("#wt_btns").html("");
			}
		});

		$("#grpsel").on("change", function () {
			let collist = $("#grpsel option:selected").attr("data-colstr");
			colstr = atob(collist);
			//regenerate template column selection
			$("#wtemp").trigger("change");
		});

		$("#save_changes").click(function () {
			if ($("#contactinput").val() == "" && $("#grpsel").val() == "0") {
				bootbox.alert(SCTEXT("Please select at least one mobile number."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Sending Campaign. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#wcmp_frm").attr("action", app_url + "pushWhatsAppCampaign");
					$("#wcmp_frm").submit();
				}, 500);
			});
		});
	}

	if (curpage == "waba_details") {
		var d = new Date();
		// Event listener for send button click
		$(document).on("click", "#save_archts", function () {
			var wmsg = $("#waba-message-input").val();
			var cid = $(this).attr("data-sendwacid");
			if (wmsg.trim() !== "") {
				appendMessage(0, wmsg, `${d.getHours()}:${d.getMinutes()}`);
				sendMessage(wmsg, cid);
				$("#waba-message-input").val("");
			}
		});
		//show conversations when clicked on contact
		$(".contact-list-item").on("click", function () {
			$(this).addClass("active");
			let contact_id = $(this).attr("data-cid");
			let contact_name = $(this).attr("data-cname");
			let contact_mob = $(this).attr("data-cnum");
			let emojilist = $("#emojibox").html();
			$(".contact-list-item").each(function () {
				if ($(this).attr("data-cid") != contact_id) $(this).removeClass("active");
			});
			//show overlay
			$("#loading-overlay").removeClass("hidden");
			$.ajax({
				url: app_url + "getWabaChats/" + contact_id,
				success: function (res) {
					//prepare the chats

					let finstr = `<div class="panel panel-default"> <div class="panel-heading bg-theme1"><div class="media"><div class="media-left">
                    <div class="avatar avatar-sm m-r-xs avatar-circle"><a href="javascript:void(0);"><img src="${app_url}global/waba/contacts.png" alt=""></a></div>
                </div>
                <div class="media-body">
                    <h5 class="m-t-xs"><a href="javascript:void(0);" class="m-r-xs theme-color">${contact_name}</a></h5>
                    <p style="font-size: 12px; margin-bottom: 0px; margin-top: -8px; color:#fff">${contact_mob}</p>
                </div>
            </div>
          </div>`;

					let footstr = `<div class="panel-footer">
              <div class="col-md-12 input-group">

              <div class="input-group-addon" style="background: none !important;border: 0 !important;">
                <div class="btn-group dropup">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: none">
                <i class="fa fa-2x fa-smile-o"></i>
                </button>
                <div class="dropdown-menu">
                  <div class="">
                    <div class="col text-center" style="width: 450px;height: 300px;overflow-x: auto;font-size: 22px;white-space: normal;">
                      ${emojilist}
                    </div>
                    <!-- Add more emojis here -->
                  </div>
                </div>
                </div>
              </div>
                <input class="form-control input-md" id="waba-message-input" style="height: 64px; font-size: 16px;">
                <span class="input-group-btn">
                  <button type="button" id="save_archts" data-sendwacid="${contact_id}" style="border: 0;background: none;" class="btn btn-default btn-round btn-md"> <i class="fas fa-2x fa-send"></i></button>
                </span>
              </div>
            </div>
          </div>`;
					setTimeout(() => {
						//display it in the box
						$("#waba_chatbox").html(`${finstr}${res}${footstr}`);

						//hide overlay
						$("#loading-overlay").addClass("hidden");
					}, 1500);
				},
			});
		});

		//send message when clicked on sent
		// Function to append a message to the chat window
		function appendMessage(senderFlag, message, time) {
			$("#waba-chat-msgs").append(
				`<div class="clearfix sc_chatbox ${
					senderFlag == 1 ? "message-received" : "message-sent"
				}"> <div class="sc_chatele"><div class="media-body p-l-md"><span>${message}</span><small class="text-dark text-right"><i class="fa fa-clock-o"></i> ${time}</small></div>  </div></div>`
			);
			var objDiv = document.getElementById("waba-chat-msgs");
			objDiv.scrollTop = objDiv.scrollHeight;
			//$('#waba-chat-msgs').scrollTop($('#waba-chat-msgs').scrollHeight);
		}

		// Function to send a message to the server
		function sendMessage(message, cid) {
			$.ajax({
				url: app_url + "sendWabaChat",
				method: "POST",
				dataType: "html",
				data: { message: message, cid: cid },
				success: function (response) {
					console.log(response);
				},
				error: function (xhr, status, error) {
					console.error(xhr.responseText);
				},
			});
		}

		// Function to fetch messages from the server
		function fetchMessages() {
			//get current active contact id
			let cid = $("div.contact-list-item.active").attr("data-cid");

			if (cid) {
				$.ajax({
					url: app_url + "fetchUnreadWabaChats/" + cid,
					method: "GET",
					success: function (response) {
						// Assuming response is an array of messages [{ sender: 'sender', message: 'message' }]
						response = JSON.parse(response);
						if (Array.isArray(response)) {
							response.forEach(function (msg) {
								appendMessage(1, msg.message, msg.time);
							});
						}
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
					},
				});
			}
		}

		// Fetch messages periodically (every 5 seconds in this example)
		setInterval(fetchMessages, 5000);

		// Emoji button click handler
		$(document).on("click", ".emoji-btn", function () {
			// Get the emoji from data attribute
			var emoji = $(this).data("emoji");
			// Get the current cursor position in the textarea
			var cursorPos = $("#waba-message-input").prop("selectionStart");
			// Get the textarea value
			var text = $("#waba-message-input").val();
			// Insert the emoji at the cursor position
			var newText = text.substring(0, cursorPos) + emoji + text.substring(cursorPos);
			// Update the textarea value
			$("#waba-message-input").val(newText);
		});
	}

	if (curpage == "add_wtemplate" || curpage == "edit_wtemplate") {
		//add header variable
		let header_var_flag = 0;
		let btn_var_flag = 0;
		$("#add_header_var").click(function () {
			if (header_var_flag == 0) {
				insertAtCaret("wtheader", "{{1}}");
				header_var_flag = 1;
				$("#add_header_var").attr("disabled", true);
			} else {
				bootbox.alert("Header variable already added");
			}
		});
		$("#wtheader").on("change keyup blur", function () {
			if ($(this).val().indexOf("{{1}}") == -1) {
				header_var_flag = 0;
				$("#add_header_var").attr("disabled", false);
			} else {
				header_var_flag = 1;
				$("#add_header_var").attr("disabled", true);
			}
		});
		//add variables in body
		let total_body_vars = 0;
		$("#add_body_var").click(function () {
			insertAtCaret("tcont", `{{${total_body_vars + 1}}}`);
			total_body_vars++;
		});
		$("#tcont").on("change keyup blur", function () {
			let wtbody = $(this).val();
			let matches = wtbody.match(/{{[0-9]+}}/g);
			if (matches) {
				total_body_vars = matches.length;
			} else {
				total_body_vars = 0;
			}
		});

		$("#tname").on("change keyup blur", function () {
			let tname_val = $(this)
				.val()
				.replace(/[^\w\s-]/g, "")
				.replace(/\s+/g, "_")
				.replace(/[-]/g, "_")
				.toLowerCase();
			$(this).val(tname_val);
		});

		//toggle header type display
		$("input[name='wt_type']").change(function () {
			if ($(this).val() == "0") {
				$("#tab-3").addClass("hidden");
				$("#tab-2").addClass("hidden");
				$("#tab-1").removeClass("hidden");
				$("#sid3").attr("disabled", false);
			} else if ($(this).val() == "1") {
				$("#tab-1").addClass("hidden");
				$("#tab-3").addClass("hidden");
				$("#tab-2").removeClass("hidden");
				$("#sid3").attr("disabled", false);
			} else if ($(this).val() == "2") {
				//disable authentication as not supported for location headers
				$("#sid3").attr("disabled", true);
				$("#tab-1").addClass("hidden");
				$("#tab-2").addClass("hidden");
				$("#tab-3").removeClass("hidden");
			}
		});
		//buttons add/remove
		//add new row
		$("#add_wt_btns").click(function () {
			$newrow = $("#newrowstr").val();
			//check if empty table
			if ($(".wt_btn_row").length == 0) {
				$("#btns_ctnr").html($newrow);
			} else {
				//table already has rows append new row
				$("#btns_ctnr").append($newrow);
			}
		});

		//remove row
		$("body").on("click", ".rmv", function () {
			$(this).parent().parent().remove();
		});
		$(document).on("change", ".btn-selector", function () {
			let sel = $(this);
			let elem = $("option:selected", this);
			if (elem.attr("data-nocap") == "1") {
				sel.parent().next().find("input[type='text']").addClass("disabledBox");
			} else {
				sel.parent().next().find("input[type='text']").removeClass("disabledBox");
			}
			if (elem.attr("data-showvar") == "1") {
				sel.parent().next().next().find("input.varvals[type='text']").removeClass("hidden");
				sel.parent().next().next().find(".varbtnbox").removeClass("hidden");
			} else {
				sel.parent().next().next().find("input.varvals[type='text']").addClass("hidden");
				sel.parent().next().next().find(".varbtnbox").addClass("hidden");
			}
		});
		$(document).on("click", ".varbtn", function () {
			let ele = $(this);
			let input_ele = ele.parent().prev();
			if (btn_var_flag == 0) {
				insertAtCaret(input_ele, "{{1}}", true);
				btn_var_flag = 1;
				$(ele).attr("disabled", true);
			} else {
				bootbox.alert("URL Button variable already added");
			}
		});
		$(document).on("change keyup blur", "input.btnvals", function () {
			let btn_ele = $(this).next().children().find(".varbtn");
			if ($(this).val().indexOf("{{1}}") == -1) {
				btn_var_flag = 0;
				$(btn_ele).attr("disabled", false);
			} else {
				btn_var_flag = 1;
				$(btn_ele).attr("disabled", true);
			}
		});
		$("#bk").click(function () {
			window.location = app_url + "manageWhatsappTemplates";
		});
	}

	if (curpage == "view_waba_admin") {
		$(".savepricing").on("click", function () {
			let thisid = $(this).attr("data-id");
			//save whatever pricing is entered for this ID
			$.ajax({
				url: app_url + "saveMetaPricing",
				type: "post",
				data: {
					id: thisid,
					cp_m: $(`#cp_mark${thisid}`).val(),
					cp_u: $(`#cp_util${thisid}`).val(),
					cp_a: $(`#cp_auth${thisid}`).val(),
					cp_ai: $(`#cp_authint${thisid}`).val(),
					cp_s: $(`#cp_serv${thisid}`).val(),
				},
				success: function (result) {
					bootbox.alert(result);
				},
			});
		});
	}

	if (curpage == "waba_plans_prices") {
		//save pricing
		$(document).on("click", ".savepricing", function () {
			let zoneid = $(this).attr("data-zoneid");
			let zone = $(this).attr("data-zone");
			let rowid = $(this).attr("data-rowid");
			let planid = $("#planid").val();
			//confirm and explain before saving that this will update price for all zone
			bootbox.confirm({
				message:
					SCTEXT("This will set the rate for all countries in the zone") + ": <b>" + zone + "</b><br> " + SCTEXT("Are you sure you want to proceed?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//prepare data
						let prices = {
							marketing: $(`#sp_marketing_${rowid}`).val(),
							utility: $(`#sp_utility_${rowid}`).val(),
							auth: $(`#sp_auth_${rowid}`).val(),
							authint: $(`#sp_authint_${rowid}`).length > 0 ? $(`#sp_authint_${rowid}`).val() : 0,
							service: $(`#sp_service_${rowid}`).val(),
						};
						$.ajax({
							url: app_url + "saveWhatsappRatePlanPrices",
							type: "post",
							data: {
								zoneid: zoneid,
								planid: planid,
								prices: prices,
							},
							success: function (result) {
								if (result == "done") {
									//refresh page
									window.location.reload();
								} else {
									console.log(result);
									bootbox.alert("Something went wrong");
								}
							},
						});
					}
				},
			});
		});
	}

	if (curpage == "manage_permgroups") {
		$(document).on("click", ".del_pgrp", function () {
			let pgid = $(this).attr("data-pid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this Permission Group?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						window.location.href = app_url + "deletePermissionGroup/" + pgid;
					}
				},
			});
		});
	}
	if (curpage == "add_permgroup" || curpage == "edit_permgroup") {
		$("#save_changes").click(function () {
			if ($("#pgname").val() == "") {
				bootbox.alert(SCTEXT("Please enter a title for the Permission Group."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Permission Group"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#upermgrp_form").attr("action", app_url + "savePermissionGroup");
					$("#upermgrp_form").submit();
				}, 200);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = app_url + "managePermissionGroups";
		});
	}

	if (curpage == "manage_fdlr") {
		$(document).on("click", ".delfdlr", function () {
			let a = $(this).attr("data-tid");
			bootbox.confirm({
				message: SCTEXT(
					"Routes/Users using this template will now use system global Fake DLR template. Please make sure this template is not assigned to any route or user account. Are you sure you want to delete?"
				),
				buttons: { cancel: { label: SCTEXT("No"), className: "btn-default" }, confirm: { label: SCTEXT("Yes"), className: "btn-info" } },
				callback: function (t) {
					t && (window.location = app_url + "deleteFdlrTemplate/" + a);
				},
			});
		});
	}
	if (curpage == "add_fdlr" || curpage == "edit_fdlr") {
		$("#add_new_code").click(function () {
			let a = $("#newrowstr").val();
			$("#codes_ctr").append(a);
		}),
			$("body").on("click", ".rmv", function () {
				$(this).parent().parent().remove();
			}),
			$("#save_changes").click(function () {
				bootbox
					.dialog({
						closeButton: !1,
						message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
							"Saving Template. Please wait"
						)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
					})
					.init(function () {
						$("#stbar").animate({ width: "100%" }, 500),
							setTimeout(function () {
								$("#fdlrfrm").attr("action", app_url + "saveFdlrTemplate"), $("#fdlrfrm").submit();
							}, 500);
					});
			}),
			$("#bk").click(function () {
				window.location = `${app_url}manageFdlrTemplates`;
			});
	}
	if (curpage == "waba_plans") {
		$(document).on("click", ".remove_wplan", function () {
			let pid = $(this).attr("data-wpid");
			bootbox.confirm({
				message: SCTEXT("Are you sure you want to delete this plan?"),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						window.location = app_url + "deleteWhatsappRatePlan/" + pid;
					}
				},
			});
		});
	}
	if (curpage == "add_wabaplan" || curpage == "edit_wabaplan") {
		$("#cvsel").on("change", function () {
			//show operators only from this country
			let ele = $(this);
			let selcv = $(this).val();
			//if selcv is an empty array set the value to 0
			if (selcv == null || (Array.isArray(selcv) && (selcv.length == 0 || selcv.includes("0")))) {
				selcv = 0;
			}
			$(".opitem").each(function () {
				if (selcv == 0 || $(this).attr("data-cpre") == selcv || (Array.isArray(selcv) && selcv.includes($(this).attr("data-cpre")))) {
					$(this).attr("disabled", false);
				} else {
					$(this).attr("disabled", "disabled");
				}
			});
			try {
				if (selcv == 0) {
					ele.prop("multiple", false).select2("destroy").val("0").select2();
				} else {
					// Multi-select for other options
					if (!Array.isArray(selcv)) {
						ele.prop("multiple", true).select2("destroy").select2();
					}
				}
				if (selcv == 0) {
					$("#opsel").val("0").select2().trigger("change");
				} else {
					$("#opsel").select2();
				}
			} catch (e) {
				console.log(e);
			}
		});

		$("#save_changes").click(function () {
			//validate
			if ($("#pname").val() == "") {
				bootbox.alert(SCTEXT("Plan name cannot be blank"));
				return;
			}
			if ($("#pmarg").val() == "" || $("#pmarg").val() == 0) {
				bootbox.alert(SCTEXT("Profit margin cannot be zero or blank"));
				return;
			}
			//post
			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Saving Whatsapp Plan"
				)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 300);
				setTimeout(function () {
					$("#wplanfrm").attr("action", app_url + "saveWhatsappRatePlan");
					$("#wplanfrm").submit();
				}, 200);
			});
		});
		$("#bk").click(function () {
			window.location = `${app_url}whatsappRatePlans`;
		});
	}

	if (curpage == "smpp_mon") {
		$(".closesmppclient").on("click", function () {
			let postdata = {
				session_id: $(this).attr("data-sessionid"),
			};
			$.ajax({
				type: "post",
				dataType: "json",
				url: app_url + "hypernode/actions/closesmpp",
				contentType: "application/json",
				beforeSend: function (xhr) {
					//Include the bearer token in header
					xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
				},
				data: JSON.stringify(postdata),
				crossDomain: true,
				headers: {
					accept: "application/json",
					"Access-Control-Allow-Origin": "*",
				},
				success: function (res) {
					bootbox.alert(`<i class="fa fa-check-circle fa-2x"></i>&nbsp; ${SCTEXT("Successfully closed the session. Please wait a few seconds.")}`);
				},
				error: function (err) {
					if (err.status == 500) {
						console.log(err);
						bootbox.alert(
							`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Something went wrong</div>`
						);
					} else {
						console.log(err.responseJSON);
						bootbox.alert(
							`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; ${err.responseJSON.message}</div>`
						);
					}
				},
			});
		});
	}

	if (curpage == "manage_mnp") {
		//lookup number
		$("#lookupbtn").click(function () {
			let btn = $(this);
			btn
				.attr("disabled", "disabled")
				.html(SCTEXT("Searching") + "...")
				.css("cursor", "progress");
			//validate
			let mobile = $("#mobile_no").val();
			if (!isPositiveInteger(mobile) || mobile.length < 7 || mobile.length > 14) {
				bootbox.alert(SCTEXT("Invalid mobile number format. Enter mobile number with no space and without plus (+) sign."));
				btn
					.attr("disabled", false)
					.html('<i class="fa fa-search fa-lg"></i>&nbsp; ' + SCTEXT("Search"))
					.css("cursor", "pointer");
				return;
			}
			let mobdata = {
				mobile: mobile,
			};
			$.ajax({
				type: "post",
				dataType: "json",
				url: app_url + "hypernode/mnp/lookup",
				contentType: "application/json",
				beforeSend: function (xhr) {
					//Include the bearer token in header
					xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
				},
				crossDomain: true,
				headers: {
					accept: "application/json",
					"Access-Control-Allow-Origin": "*",
				},
				data: JSON.stringify(mobdata),
				success: function (res) {
					//console.log(res);
					btn
						.attr("disabled", false)
						.html('<i class="fa fa-search fa-lg"></i>&nbsp; ' + SCTEXT("Search"))
						.css("cursor", "pointer");
					if (res.success == false) {
						//mobile couldn't be identified
						$("#lookup_result").html('<li class="text-center list-group-item list-group-item-danger">- ' + SCTEXT("No Records Found") + " -</li>");
						bootbox.alert(
							SCTEXT("Mobile could not be identified. Enter full mobile number with country prefix without space and without plus (+) sign.")
						);
					} else {
						//result is successful
						let pdata = {
							mobile_data: res.data[0].mobile_data,
							operator: res.data[0].country,
							ported: res.data[0].ported,
						};

						let str = `<li>
							<div id="mnplookupresult" class="card"><div class="card-header p-sm">Lookup Result</div><div class="card-body"><pre> <code class="language-json">
${JSON.stringify(pdata, null, 2)}
</code></pre></div></div></li>`;

						$("#lookup_result").html(str);
						Prism.highlightElement($("#mnplookupresult code")[0]);
					}
				},
			});
		});
		$("body").on("click", ".closeDS", function () {
			$(".taskinfo").popover("hide");
		});
		//fetch the mnp summary
		$.ajax({
			type: "get",
			dataType: "json",
			url: app_url + "hypernode/mnp/getStats",
			contentType: "application/json",
			beforeSend: function (xhr) {
				//Include the bearer token in header
				xhr.setRequestHeader("Authorization", "Bearer " + JSON.parse(authToken).token);
			},
			crossDomain: true,
			headers: {
				accept: "application/json",
				"Access-Control-Allow-Origin": "*",
			},
			success: function (res) {
				$("#totalmnp").html(`<kbd><b>${res.data.totalRecords.toLocaleString()}</b></kbd>`);
				let healthPercentage = res.data.health > 100 ? 100 : res.data.health;
				$("#health-circle")
					.circleProgress({
						value: healthPercentage / 100, // Convert percentage to a value between 0 and 1
						size: 100, // Size of the circle
						fill: {
							gradient: ["#4CAF50", "#8BC34A"], // Gradient color for the progress
						},
						startAngle: (-Math.PI / 4) * 2,
						thickness: 10,
						animation: { duration: 1200, easing: "circleProgressEasing" },
					})
					.on("circle-animation-progress", function (event, progress) {
						$(this)
							.find("strong")
							.html(Math.round(healthPercentage * progress) + "<i>%</i>");
					});

				// Add a text label inside the circle
				$("#health-circle").append("<strong>" + healthPercentage + "<i>%</i></strong>");
				$("#health-circle strong").css({
					position: "absolute",
					top: "36px",
					left: "36px",
					fontSize: "18px",
					fontWeight: "bold",
				});
			},
			error: function (err) {
				if (err.status == 500) {
					console.log(err);
					bootbox.alert(
						`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; Something went wrong</div>`
					);
				} else {
					console.log(err.responseJSON);
					bootbox.alert(
						`<div class="alert alert-custom alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-times-circle fa-2x"></i>&nbsp; ${err.responseJSON.message}</div>`
					);
				}
			},
		});
		//cancel mnp job
		$(document).on("click", ".cancel-mnpjob", function () {
			let taskid = $(this).attr("data-taskid");
			bootbox.confirm({
				message: SCTEXT(
					"This will delete the current task and uploaded file. If some of the data is already imported it will not be deleted. Are you sure you want to proceed?"
				),
				buttons: {
					cancel: {
						label: SCTEXT("No"),
						className: "btn-default",
					},
					confirm: {
						label: SCTEXT("Yes"),
						className: "btn-info",
					},
				},
				callback: function (result) {
					if (result) {
						//delete task
						window.location = app_url + "deleteMnpJob/" + taskid;
					}
				},
			});
		});
	}

	if (curpage == "add_mnp") {
		//submit
		$("#save_changes").click(function () {
			if ($("#uprocess").val() == 1) {
				bootbox.alert(SCTEXT("File upload is in progress. Kindly wait for upload to finish or Cancel Upload & proceed."));
				return;
			}

			var dialog = bootbox.dialog({
				closeButton: false,
				message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(
					"Adding new MNP Task. Please wait"
				)} . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
			});
			dialog.init(function () {
				$("#stbar").animate({ width: "100%" }, 500);
				setTimeout(function () {
					$("#upload_mnp_form").attr("action", app_url + "saveMnpRecords");
					$("#upload_mnp_form").submit();
				}, 500);
			});
		});
		//bk
		$("#bk").click(function () {
			window.location = `${app_url}mnpDatabase`;
		});
	}

	// global scripts

	//load notifs
	setInterval(function () {
		loadAppAlerts();
	}, 30000);
	//notifs click redirec
	$(document).on("click", ".albox", function () {
		var nid = $(this).attr("data-nid");
		var lnk = $(this).attr("data-redirect");
		window.location = app_url + "alertRedirect/" + nid + "/" + lnk;
	});

	$("#rlcrehdr").on("click", function (e) {
		e.stopPropagation();
		$("#rlcrehdr i").addClass("fa-spin disabledBox");
		$.ajax({
			url: app_url + "reloadCreditData",
			success: function (res) {
				window.location.reload(false);
			},
		});
	});
	$(".pop-over").popover({ html: true });
	$(".dashboard-link").click(function () {
		window.location = app_url + "Dashboard";
	});

	if (curpage != "usms_log" && curpage != "stats") {
		$("[title]").each(function () {
			if ($(this).hasClass("pop-over") == false) {
				if ($(this).prop("tagName") != "OPTION") {
					let title = $(this).attr("title");
					$(this).tooltip({ title: title });
				}
			}
		});
	}
});
