<main id="app-main" class="app-main">
	<?php include('breadcrums.php') ?>
	<div class="wrap">
		<section class="app-content">
			<div class="row">
				<div class="col-md-12">
					<div class="widget p-lg">
						<div class="row no-gutter">
							<h3 class="page-title-sc"><?php echo SCTEXT('Add New Smpp') ?><small><?php echo SCTEXT('add new SMPP connection to your Kannel') ?></small></h3>
							<hr>
							<div class="col-md-12">
								<!-- start content -->
								<form class="form-horizontal" method="post" id="add_smpp_form" action="">
									<div class="block">
										<div class="block-title">
											<h4><?php echo SCTEXT('Required Parameters') ?></h4>
										</div>
										<br>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Kannel Instance') ?>:</label>
												<div class="col-md-8">
													<select class="form-control" data-plugin="select2" name="skannel">
														<option selected value="0">Master Kannel (Default)</option>
														<?php foreach ($data['kannels'] as $kan) { ?>
															<option value="<?php echo $kan->id ?>"><?php echo $kan->kannel_name ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Name') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="title" id="smpp_title" class="form-control" placeholder="<?php echo SCTEXT('enter a title for this smpp') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Provider Name') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="provider" id="provider" class="form-control" placeholder="<?php echo SCTEXT('company name of the smpp provider') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMSC ID') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smscid" id="smsc_id" class="form-control pop-over" data-html="true" data-trigger="focus" title="<?php echo SCTEXT('SMSC ID Guidelines') ?>" data-placement="top" data-content="<?php echo SCTEXT('SMSC ID is used by Kannel for SMS routing. <u>No space allowed,</u> keep it simple. Use format:<br> <b>{smpp name}-{provider}-smpp</b><br> for example: <br><b>europeglobal-abctelecom-smpp</b>') ?>" placeholder="<?php echo SCTEXT('give a unique smsc_id for this smpp') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Host') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smpphost" id="smpp_host" class="form-control" placeholder="<?php echo SCTEXT('enter domain or IP address of smpp host') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Port') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smppport" id="smpp_port" class="form-control" placeholder="<?php echo SCTEXT('enter smpp port provided by vendor/operator') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Username') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smppuid" id="smpp_uid" class="form-control" placeholder="<?php echo SCTEXT('enter username or system-ID for the smpp') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Password') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smpppass" id="smpp_pass" class="form-control" placeholder="<?php echo SCTEXT('enter smpp password') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Receive SMS') ?>:</label>
												<div class="col-md-8">
													<div class="radio radio-inline radio-primary"><input id="rcv_sms_y" type="radio" value="1" name="rcv_sms"><label for="rcv_sms_y"><?php echo SCTEXT('Yes') ?></label></div>
													<div class="radio radio-inline radio-primary"><input id="rcv_sms_n" checked="checked" name="rcv_sms" type="radio" value="0"><label for="rcv_sms_n"><?php echo SCTEXT('No') ?></label></div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Version') ?>:</label>
												<div class="col-md-8">
													<div class="radio radio-inline radio-primary"><input id="smpp34" type="radio" checked="checked" value="34" name="smppversion"><label for="smpp34">v3.4</label></div>
													<div class="radio radio-inline radio-primary"><input id="smpp50" name="smppversion" type="radio" value="50"><label for="smpp50">v5.0</label></div>
												</div>
											</div>

										</div>

										<div class="col-md-6">

											<div class="form-group">
												<label class="control-label col-md-3">Tx <?php echo SCTEXT('Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="txno" id="tx_no" class="form-control" placeholder="<?php echo SCTEXT('no. of transmitter sessions') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3">Rx <?php echo SCTEXT('Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="rxno" id="rx_no" class="form-control" placeholder="<?php echo SCTEXT('no. of receiver sessions') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3">TRx <?php echo SCTEXT('Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="trxno" id="trx_no" class="form-control" placeholder="<?php echo SCTEXT('no. of transceiver sessions') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Total Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="totalsessions" id="total_sessions" disabled class="form-control" placeholder="<?php echo SCTEXT('total no. of smpp sessions') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Credits API') ?>:</label>
												<div class="col-md-8">
													<textarea rows="2" style="min-height: 110px;" name="creditsapi" class="form-control" placeholder="enter complete url with protocol . . . "></textarea>
													<span class="help-block m-b-0"><?php echo SCTEXT('Enter the complete API URL. Add %u and %p to be replaced by System ID and Password respectively') ?></span>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP TLV') ?>:</label>
												<div class="col-md-8">
													<select id="tlvlist" name="tlv[]" class="form-control" data-plugin="select2" multiple data-placeholder="<?php echo SCTEXT('Select TLV required by this SMPP') ?>. . .">
														<?php foreach ($data['tlvs'] as $tlv) { ?>
															<option value="<?php echo $tlv->id ?>"><?php echo $tlv->tlv_title ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Default Charset') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="alt_charset" id="alt_charset" class="form-control" placeholder="<?php echo SCTEXT('enter alternate character encoding to be used') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Use SSL') ?>:</label>
												<div class="col-md-8">
													<div class="radio radio-inline radio-primary"><input id="use_ssl_y" type="radio" name="use_ssl" value="1"><label for="use_ssl_y"><?php echo SCTEXT('Yes') ?></label></div>
													<div class="radio radio-inline radio-primary"><input id="use_ssl_n" checked="checked" name="use_ssl" type="radio" value="0"><label for="use_ssl_n"><?php echo SCTEXT('No') ?></label></div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>

									<hr>
									<div class="block">
										<div class="block-title">
											<h4><?php echo SCTEXT('Advanced Parameters') ?><small style="margin:1%;font-size:12px;"><?php echo SCTEXT('leave default/blank values if not sure') ?></small></h4>
										</div>
										<br>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('ESM Class') ?>:</label>
												<div class="col-md-8">
													<select class="form-control" data-plugin="select2" name="esm_class">
														<option selected value="-1">Not Specified</option>
														<option value="3">Store and Forward</option>
														<option value="0">Default SMSC Mode</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Receive Port') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="rcvport" id="rcv_port" class="form-control" placeholder="<?php echo SCTEXT('enter port for receiving sms') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('System Type') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="systype" id="sys_type" class="form-control" placeholder="<?php echo SCTEXT('enter system type specified by vendor/operator') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Service Type') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="sertype" id="ser_type" class="form-control" placeholder="<?php echo SCTEXT('enter service type specified by vendor/operator') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Throughput') ?> (TPS):</label>
												<div class="col-md-8">
													<input type="text" name="tps" id="tps" class="form-control" placeholder="<?php echo SCTEXT('enter tps provided by vendor/operator') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Allowed Prefixes') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="allpre" id="all_pre" class="form-control" placeholder="<?php echo SCTEXT('enter mobile prefixes to allow, leave blank to allow all') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Denied Prefixes') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="denpre" id="den_pre" class="form-control" placeholder="<?php echo SCTEXT('enter mobile prefixes to deny, leave blank to deny none') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3">Max SMS Octets:</label>
												<div class="col-md-8">
													<input type="text" name="maxoctets" id="maxoctet" class="form-control" placeholder="<?php echo SCTEXT('default value is') ?> 140" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, max:500, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" />
												</div>
											</div>
										</div>

										<div class="col-md-6">

											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Enquire Link Interval') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="eli" id="eli" class="form-control" placeholder="<?php echo SCTEXT('enter no. of seconds lapse in enquiring active session') ?>" value="30" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Reconnect Delay') ?>:</label>
												<div class="col-md-8">
													<input value="10" type="text" name="recon" id="recon" class="form-control" placeholder="<?php echo SCTEXT('enter no. of seconds to attempt connecting after failure') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Source Addr TON') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="ston" id="ston" class="form-control" placeholder="<?php echo SCTEXT('enter source address TON') ?>" value="0" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Source Addr NPI') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="snpi" id="snpi" class="form-control" placeholder="<?php echo SCTEXT('enter source address NPI') ?>" value="1" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Dest. Addr TON') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="dton" id="dton" class="form-control" placeholder="<?php echo SCTEXT('enter destination address TON') ?>" value="0" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Dest. Addr NPI') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="dnpi" id="dnpi" class="form-control" placeholder="<?php echo SCTEXT('enter destination address NPI') ?>" value="1" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Separate Logfile') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="slog" id="slog" class="form-control" placeholder="<?php echo SCTEXT('enter a log file path if need to log separately') ?>" value="" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Log level') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="sloglvl" id="sloglvl" class="form-control" data-plugin="TouchSpin" value="" placeholder="enter log-level value for kannel (0-4)" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus', max: 4 }" />
												</div>
											</div>
										</div>

										<div class="clearfix"></div>

									</div>

									<div class="form-group">
										<div class="col-md-3"></div>
										<div class="col-md-8">
											<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes') ?></button>
											<button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel') ?></button>
										</div>
									</div>
								</form>
								<!-- end content -->
							</div>
						</div>
					</div>
				</div>
			</div>

		</section>