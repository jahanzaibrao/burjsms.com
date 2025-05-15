 <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add New Keyword')?><small><?php echo SCTEXT('add a new primary keyword to match with incoming SMS text')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="kwfrm">
                                    
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Enter Keyword')?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="<?php echo SCTEXT('enter your keyword')?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Select VMN')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" data-plugin="select2" name="vmn">
                                                    <?php foreach($data['vmns'] as $vmn){ ?>
                                                        <option value="<?php echo $vmn->id ?>"><?php echo $vmn->vmn ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Reply')?>:</label>
                                            <div class="col-md-8">
                                                <textarea id="dr_sms" name="dr_sms" maxlength="120" class="form-control pop-over" data-placement="top" data-trigger="focus" data-content="<?php echo SCTEXT('This message will be sent if no other reply SMS is matched for incoming SMS')?>" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" placeholder="<?php echo SCTEXT('enter default SMS reply')?>. . . ."></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Forward to')?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" maxlength="15" class="form-control" name="fwdmob" id="fwdmob" placeholder="<?php echo SCTEXT('enter mobile number with country code')?>">
                                                <span class="help-block"><?php echo SCTEXT('Incoming SMS will be sent to above mobile number') ?></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Trigger URL')?>:</label>
                                            <div class="col-md-8">
                                                <input data-placement="top" type="text" class="form-control pop-over" data-trigger="focus" data-content="<?php echo SCTEXT('This URL will be triggered only if no URL matches are found for the incoming SMS.') ?>" name="turl" id="turl" placeholder="<?php echo SCTEXT('enter complete url with protocol')?>. . .">
                                            </div>
                                        </div>
											
                                        <hr>
											<div class="form-group">
                                            <div class="col-md-3"></div>
												<div class="col-md-8">
													<button id="save_changes" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes')?></button>
													<button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel')?></button>
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