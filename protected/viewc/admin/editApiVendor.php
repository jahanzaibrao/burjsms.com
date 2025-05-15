<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit API Vendor')?><small><?php echo SCTEXT('modify API Vendor parameters')?></small></h3>
                                <hr>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="add_vapi_form" action="">
										<input type="hidden" name="aid" id="vaid" value="<?php echo $data['rdata']->id ?>">
										<div class="block">
                                        	
                                            <div class="col-md-6">
												<div class="form-group">
												    <label class="control-label col-md-3"><?php echo SCTEXT('API Title')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="title" id="vapi_title" value="<?php echo $data['rdata']->title ?>" class="form-control" placeholder="<?php echo SCTEXT('enter a title for this API based channel')?>" />
												    </div>
											     </div>
                                                <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Provider')?>:</label>
												    <div class="col-md-8">
														<select class="form-control" data-plugin="select2" name="vapi_provider" id="vapi_provider">
															<option value="twillio">Twillio Programmabe API</option>
														</select>
												    </div>
											     </div>
                                                <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMSC ID')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="smscid" id="smsc_id" value="<?php echo $data['rdata']->smsc_id ?>" class="form-control pop-over" data-html="true" data-trigger="focus" title="<?php echo SCTEXT('SMSC ID Guidelines')?>" data-placement="top" data-content="<?php echo SCTEXT('SMSC ID is used by system for SMS routing and DLR callbacks. <u>No space allowed,</u> keep it simple. Use format:<br> <b>{title}-{provider}-http</b><br> for example: <br><b>europeglobal-abctelecom-http</b>')?>" placeholder="<?php echo SCTEXT('give a unique smsc_id for this API channel')?>" />
												    </div>
											     </div>
												 <div class="form-group">
													<label class="control-label col-md-3"><?php echo SCTEXT('Credits API')?>:</label>
												    <div class="col-md-8">
													   <textarea rows="2" style="min-height: 55px;" name="creditsapi" class="form-control" placeholder="enter complete url with protocol . . . "></textarea>
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Enter the complete API URL. Add %u and %p to be replaced by System ID and Password respectively')?></span>
												    </div>
												 </div>
												 

												
                                               

                                            </div>

                                            <div class="col-md-6">
											
												  <?php
												 	$auth_data = json_decode($data['rdata']->auth_data, true); 
												  ?>
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo SCTEXT('Account SID')?>:</label>
														<div class="col-md-8">
														<input type="text" name="authsid" id="authsid" value="<?php echo $auth_data['sid'] ?>" class="form-control" placeholder="<?php echo SCTEXT('enter the SID from your twillio account')?>" />
														</div>
													</div>
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo SCTEXT('Auth Token')?>:</label>
														<div class="col-md-8">
														<input type="text" name="authtoken" id="authtoken" value="<?php echo $auth_data['token'] ?>" class="form-control" placeholder="<?php echo SCTEXT('enter the Auth token from your twillio account')?>" />
														</div>
													</div>

                                             
                                            </div>
											<div class="clearfix"></div>
										</div>

                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes')?></button>
													<button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel')?></button>
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
