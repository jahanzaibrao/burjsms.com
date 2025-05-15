<main id="app-main" class="app-main">
	<?php include('breadcrums.php') ?>
	<div class="wrap">
		<section class="app-content">
			<div class="row">
				<div class="col-md-12">
					<div class="widget p-lg">
						<div class="row no-gutter">
							<h3 class="page-title-sc"><?php echo SCTEXT('Edit Smpp') ?><small><?php echo SCTEXT('edit SMPP connection details') ?></small></h3>
							<hr>
							<?php include('notification.php') ?>
							<div class="col-md-12">
								<!-- start content -->
								<form class="form-horizontal" method="post" id="edit_smpp_form" action="">
									<input type="hidden" name="rid" id="rid" value="<?php echo $data['rdata']->id ?>" />
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
															<option <?php if ($data['rdata']->kannel_id == $kan->id) { ?> selected <?php } ?> value="<?php echo $kan->id ?>"><?php echo $kan->kannel_name ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Name') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="title" id="smpp_title" class="form-control" placeholder="<?php echo SCTEXT('enter a title for this smpp') ?>" value="<?php echo $data['rdata']->title ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Provider Name') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="provider" id="provider" class="form-control" placeholder="<?php echo SCTEXT('company name of the smpp provider') ?>" value="<?php echo $data['rdata']->provider ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMSC ID') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smscid" id="smsc_id" class="form-control pop-over" data-html="true" data-trigger="hover" title="<?php echo SCTEXT('SMSC ID Guidelines') ?>" data-placement="top" data-content="<?php echo SCTEXT('Editing SMSC-ID requires Kannel restart.') ?>" placeholder="<?php echo SCTEXT('give a unique smsc_id for this smpp') ?>" value="<?php echo $data['rdata']->smsc_id ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Host') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smpphost" id="smpp_host" class="form-control" placeholder="<?php echo SCTEXT('enter domain or IP address of smpp host') ?>" value="<?php echo $data['rdata']->host ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Port') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smppport" id="smpp_port" class="form-control" placeholder="<?php echo SCTEXT('enter smpp port provided by vendor/operator') ?>" value="<?php echo $data['rdata']->port ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Username') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smppuid" id="smpp_uid" class="form-control" placeholder="<?php echo SCTEXT('enter username or system-ID for the smpp') ?>" value="<?php echo $data['rdata']->username ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Password') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="smpppass" id="smpp_pass" class="form-control" placeholder="<?php echo SCTEXT('enter smpp password') ?>" value="<?php echo $data['rdata']->password ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Receive SMS') ?>:</label>
												<div class="col-md-8">
													<div class="radio radio-inline radio-primary"><input id="rcv_sms_y" type="radio" value="1" name="rcv_sms" <?php if ($data['rdata']->purpose == '2WAY') { ?> checked="checked" <?php } ?>><label for="rcv_sms_y"><?php echo SCTEXT('Yes') ?></label></div>
													<div class="radio radio-inline radio-primary"><input id="rcv_sms_n" value="0" <?php if ($data['rdata']->purpose == 'SMSC') { ?> checked="checked" <?php } ?> name="rcv_sms" type="radio"><label for="rcv_sms_n"><?php echo SCTEXT('No') ?></label></div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP Version') ?>:</label>
												<div class="col-md-8">
													<div class="radio radio-inline radio-primary"><input id="smpp34" type="radio" <?php if ($data['rdata']->smpp_version == '34') { ?> checked="checked" <?php } ?> value="34" name="smppversion"><label for="smpp34">v3.4</label></div>
													<div class="radio radio-inline radio-primary"><input id="smpp50" name="smppversion" type="radio" <?php if ($data['rdata']->smpp_version == '50') { ?> checked="checked" <?php } ?> value="50"><label for="smpp50">v5.0</label></div>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label col-md-3">Tx <?php echo SCTEXT('Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="txno" id="tx_no" class="form-control" placeholder="<?php echo SCTEXT('no. of transmitter sessions') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" value="<?php echo $data['rdata']->tx ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3">Rx <?php echo SCTEXT('Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="rxno" id="rx_no" class="form-control" placeholder="<?php echo SCTEXT('no. of receiver sessions') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" value="<?php echo $data['rdata']->rx ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3">TRx <?php echo SCTEXT('Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="trxno" id="trx_no" class="form-control" placeholder="<?php echo SCTEXT('no. of transceiver sessions') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" value="<?php echo $data['rdata']->trx ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Total Sessions') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="totalsessions" id="total_sessions" disabled class="form-control" placeholder="<?php echo SCTEXT('total no. of smpp sessions') ?>" value="<?php echo intval($data['rdata']->tx) + intval($data['rdata']->rx) + intval($data['rdata']->trx) ?>" />
												</div>
											</div>

											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Credits API') ?>:</label>
												<div class="col-md-8">
													<textarea rows="2" style="min-height: 110px;" name="creditsapi" class="form-control" placeholder="enter complete url with protocol . . . "><?php echo $data['rdata']->credits_api ?></textarea>
													<span class="help-block m-b-0"><?php echo SCTEXT('Enter the complete API URL. Add %u and %p to be replaced by System ID and Password respectively') ?></span>
												</div>
											</div>
											<?php $selected_tlvs = array();
											if ($data['rdata']->tlv_ids != '') {
												$selected_tlvs = explode(",", $data['rdata']->tlv_ids);
											}
											?>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMPP TLV') ?>:</label>
												<div class="col-md-8">
													<select id="tlvlist" name="tlv[]" class="form-control" data-plugin="select2" multiple data-placeholder="<?php echo SCTEXT('Select TLV required by this SMPP') ?>. . .">
														<?php foreach ($data['tlvs'] as $tlv) { ?>
															<option <?php if (in_array($tlv->id, $selected_tlvs)) { ?> selected <?php } ?> value="<?php echo $tlv->id ?>"><?php echo $tlv->tlv_title ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Default Charset') ?>:</label>
												<div class="col-md-8">
													<input value="<?php echo $data['rdata']->alt_charset ?>" type="text" name="alt_charset" id="alt_charset" class="form-control" placeholder="<?php echo SCTEXT('enter alternate character encoding to be used') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Use SSL') ?>:</label>
												<div class="col-md-8">
													<div class="radio radio-inline radio-primary"><input id="use_ssl_y" type="radio" name="use_ssl" value="1" <?php if ($data['rdata']->use_ssl == '1') { ?> checked="checked" <?php } ?>><label for="use_ssl_y"><?php echo SCTEXT('Yes') ?></label></div>
													<div class="radio radio-inline radio-primary"><input id="use_ssl_n" <?php if ($data['rdata']->use_ssl == '0') { ?> checked="checked" <?php } ?> name="use_ssl" type="radio" value="0"><label for="use_ssl_n"><?php echo SCTEXT('No') ?></label></div>
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
														<option <?php if ($data['rdata']->esm_class == '-1') { ?> selected <?php } ?> value="-1">Not Specified</option>
														<option <?php if ($data['rdata']->esm_class == '3') { ?> selected <?php } ?> value="3">Store and Forward</option>
														<option <?php if ($data['rdata']->esm_class == '0') { ?> selected <?php } ?> value="0">Default SMSC Mode</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Receive Port') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="rcvport" id="rcv_port" class="form-control" placeholder="<?php echo SCTEXT('enter port for receiving sms') ?>" value="<?php echo $data['rdata']->rcv_port == 0 ? '' : $data['rdata']->rcv_port ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('System Type') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="systype" id="sys_type" class="form-control" placeholder="<?php echo SCTEXT('enter system type specified by vendor/operator') ?>" value="<?php echo $data['rdata']->system_type ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Service Type') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="sertype" id="ser_type" class="form-control" placeholder="<?php echo SCTEXT('enter service type specified by vendor/operator') ?>" value="<?php echo $data['rdata']->service_type ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Throughput') ?> (TPS):</label>
												<div class="col-md-8">
													<input type="text" name="tps" id="tps" class="form-control" placeholder="<?php echo SCTEXT('enter tps provided by vendor/operator') ?>" value="<?php echo $data['rdata']->throughput == 0 ? '' : $data['rdata']->throughput ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Allowed Prefixes') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="allpre" id="all_pre" class="form-control" placeholder="<?php echo SCTEXT('enter mobile prefixes to allow, leave blank to allow all') ?>" value="<?php echo $data['rdata']->allowed_prefix ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Denied Prefixes') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="denpre" id="den_pre" class="form-control" placeholder="<?php echo SCTEXT('enter mobile prefixes to deny, leave blank to deny none') ?>" value="<?php echo $data['rdata']->denied_prefix ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3">Max SMS Octets:</label>
												<div class="col-md-8">
													<input type="text" name="maxoctets" id="maxoctet" class="form-control" value="<?php echo $data['rdata']->max_octets ?>" placeholder="<?php echo SCTEXT('default value is') ?> 140" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, max:500, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" />
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Enquire Link Interval') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="eli" id="eli" class="form-control" placeholder="<?php echo SCTEXT('enter no. of seconds lapse in enquiring active session') ?>" value="<?php echo $data['rdata']->enquire_link_interval ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Reconnect Delay') ?>:</label>
												<div class="col-md-8">
													<input value="<?php echo $data['rdata']->reconnect_delay ?>" type="text" name="recon" id="recon" class="form-control" placeholder="<?php echo SCTEXT('enter no. of seconds to attempt connecting after failure') ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Source Addr TON') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="ston" id="ston" class="form-control" placeholder="<?php echo SCTEXT('enter source address TON') ?>" value="<?php echo $data['rdata']->ston ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Source Addr NPI') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="snpi" id="snpi" class="form-control" placeholder="<?php echo SCTEXT('enter source address NPI') ?>" value="<?php echo $data['rdata']->snpi ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Dest. Addr TON') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="dton" id="dton" class="form-control" placeholder="<?php echo SCTEXT('enter destination address TON') ?>" value="<?php echo $data['rdata']->dton ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Dest. Addr NPI') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="dnpi" id="dnpi" class="form-control" placeholder="<?php echo SCTEXT('enter destination address NPI') ?>" value="<?php echo $data['rdata']->dnpi ?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Separate Logfile') ?>:</label>
												<div class="col-md-8">
													<input type="text" value="<?php echo $data['rdata']->logfile ?>" name="slog" id="slog" class="form-control" placeholder="<?php echo SCTEXT('enter a log file path if need to log separately') ?>" value="" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Log level') ?>:</label>
												<div class="col-md-8">
													<input type="text" name="sloglvl" id="sloglvl" class="form-control" data-plugin="TouchSpin" value="<?php echo $data['rdata']->log_level ?>" placeholder="enter log-level value for kannel (0-4)" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus', max: 4 }" />
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