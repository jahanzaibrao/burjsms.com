 <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit VMN')?><small><?php echo SCTEXT('modify virtual mobile number parameters')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="vmnfrm">
                                        <input type="hidden" name="vmnid" value="<?php echo $data['vmn']->id ?>">
                                    <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Select VMN Type')?>:</label>
                                            <div class="col-md-8">
                                            <?php if($_SESSION['user']['group']!='admin'){ ?>
                                                <div class="disabledBox">
                                                    <select id="vmntype" name="vmntype" class="form-control" data-plugin="select2" data-options="{readonly: true}">
                                                        <option value="0" <?php if($data['vmn']->type==0){ ?> selected <?php } ?> data-title="<?php echo SCTEXT('Short virtual numbers for localised campaigns. These are usually 4-6 digits long.')?>">Shortcode</option>
                                                        <option value="1" <?php if($data['vmn']->type==1){ ?> selected <?php } ?> data-title="<?php echo SCTEXT('A 12 digit mobile number that can receive SMS globally.')?>">Longcode</option>
                                                        <option value="2" <?php if($data['vmn']->type==2){ ?> selected <?php } ?> data-title="<?php echo SCTEXT('Virtual number linked with Missed call service. Caller details will be sent via SMPP.')?>">Missed Call Number</option>
                                                    </select>
                                                </div>
                                                <span class="help-block" id="vtypestr"></span>
                                            <?php } else { ?>
                                                <select id="vmntype" name="vmntype" class="form-control" data-plugin="select2">
                                                    <option value="0" <?php if($data['vmn']->type==0){ ?> selected <?php } ?> data-title="<?php echo SCTEXT('Short virtual numbers for localised campaigns. These are usually 4-6 digits long.')?>">Shortcode</option>
                                                    <option value="1" <?php if($data['vmn']->type==1){ ?> selected <?php } ?> data-title="<?php echo SCTEXT('A 12 digit mobile number that can receive SMS globally.')?>">Longcode</option>
                                                    <option value="2" <?php if($data['vmn']->type==2){ ?> selected <?php } ?> data-title="<?php echo SCTEXT('Virtual number linked with Missed call service. Caller details will be sent via SMPP.')?>">Missed Call Number</option>
                                                </select>
                                                <span class="help-block" id="vtypestr"></span>
                                            <?php } ?> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Virtual Mobile Number')?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" readonly value="<?php echo $data['vmn']->vmn ?>" class="form-control" name="vmn" id="vmn" placeholder="<?php echo SCTEXT('enter virtual mobile number e.g. 56767')?>">
                                            </div>
                                        </div>
                                        <?php if($_SESSION['user']['group']=='admin'){ ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Auto-reply Rule')?>:</label>
                                            <div class="col-md-8">
                                                <div class="radio radio-primary">
                                                    <input id="art1" <?php if($data['vmn']->auto_reply_type==0){ ?> checked="checked" <?php } ?> value="0" type="radio" name="ar_type">
                                                    <label for="art1"><?php echo SCTEXT('Disabled')?></label>
                                                    <span class="help-block"><?php echo SCTEXT('No auto-reply SMS will be sent.')?></span>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="art2" <?php if($data['vmn']->auto_reply_type==1){ ?> checked="checked" <?php } ?> value="1" type="radio" name="ar_type">
                                                    <label for="art2"><?php echo SCTEXT('Send from User Account')?></label>
                                                    <span class="help-block"><?php echo SCTEXT("Auto-reply SMS will be sent from user account using default route and credits will be deducted.")?></span>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="art3" <?php if($data['vmn']->auto_reply_type==2){ ?> checked="checked" <?php } ?> value="2" type="radio" name="ar_type">
                                                    <label for="art3"><?php echo SCTEXT('Free Reply SMS')?></label>
                                                    <span class="help-block"><?php echo SCTEXT("Auto-reply SMS will be sent from our system. User will not be charged any credits.")?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Reply')?>:</label>
                                            <div class="col-md-8">
                                                <textarea id="dr_sms" name="dr_sms" maxlength="120" class="form-control pop-over" data-placement="top" data-trigger="focus" data-content="<?php echo SCTEXT('This message will be sent if no other reply SMS is matched for incoming SMS')?>" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" placeholder="<?php echo SCTEXT('enter default SMS reply')?>. . . ."><?php echo htmlspecialchars_decode($data['vmn']->default_reply,ENT_QUOTES) ?></textarea>
                                            </div>
                                        </div>
                                            <?php if($_SESSION['user']['account_type']=='0'){ ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Reply SMS Route')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="rsmpp" id="rsmpp" data-plugin="select2">
                                                    <?php foreach($data['routes'] as $rt){ ?>
                                                    <option <?php if($data['vmn']->sysreply_smpp==$rt['id']){ ?> selected <?php } ?> data-stype="<?php echo $rt['senderType'] ?>" data-smax="<?php echo $rt['maxSender'] ?>" data-sdef="<?php echo $rt['defaultSender'] ?>" data-cov="<?php echo $rt['coverage'] ?>" value="<?php echo $rt['id'].'|'.$rt['senderType'] ?>"><?php echo $rt['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="sidselbox" class="form-group hidden">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Reply Sender ID')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="replysender" id="sendersel" data-plugin="select2">
                                                    <?php foreach($data['sids'] as $sid){ ?>
                                                    <option <?php if($data['vmn']->sysreply_sender==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                    <?php } ?>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div id="sidopnbox" class="form-group hidden">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Reply Sender ID')?>:</label>
                                            <div class="col-md-8">
                                                <input value="<?php echo $data['vmn']->sysreply_sender ?>" type="text" name="replysender" id="senderopn" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID')?>..." maxlength="50" />
                                                <span class="help-block text-info m-b-0"><?php echo SCTEXT('Follow the rules for Sender ID set by your SMS provider.')?></span>
                                            </div>
                                        </div>
                                                    <?php }else{
                                                        $routedata = array_values($_SESSION['credits']['routes'])[0];
                                                        if($routedata['senderType']=='0'){
                                                        ?>
                                        <div id="sidselbox" class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Reply Sender ID')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="replysender" id="sendersel" data-plugin="select2">
                                                    <?php foreach($data['sids'] as $sid){ ?>
                                                    <option <?php if($data['vmn']->sysreply_sender==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                    <?php } ?>
                                                </select>
                                                
                                            </div>
                                        </div>
                                                        <?php }
                                                        if($routedata['senderType']=='2'){
                                                        ?>

                                        <div id="sidopnbox" class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Reply Sender ID')?>:</label>
                                            <div class="col-md-8">
                                                <input value="<?php echo $data['vmn']->sysreply_sender ?>" type="text" name="replysender" id="senderopn" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID')?>..." maxlength="50" />
                                                <span class="help-block text-info m-b-0"><?php echo SCTEXT('Follow the rules for Sender ID set by your SMS provider.')?></span>
                                            </div>
                                        </div>                

                                                    <?php } } ?>

                                        

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Trigger URL')?>:</label>
                                            <div class="col-md-8">
                                                <input value="<?php echo strip_tags($data['vmn']->trigger_url) ?>" data-placement="top" type="text" class="form-control pop-over" data-trigger="focus" data-content="<?php echo SCTEXT('This URL will be triggered only if no URL matches are found for the incoming SMS.') ?>" name="turl" id="turl" placeholder="<?php echo SCTEXT('enter complete url with protocol')?>. . .">
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