<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Campaign')?><small><?php echo SCTEXT('modify campaign with other parameters')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->

                                    <form class="form-horizontal" method="post" id="cmpn_form" action="">
                                        <input type="hidden" name="cid" value="<?php echo $data['cdata']->id ?>">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Campaign Name')?>:</label>
                                            <div class="col-md-8">
                                                <input value="<?php echo $data['cdata']->campaign_name ?>" type="text" name="cname" id="cname" class="form-control" placeholder="<?php echo SCTEXT('enter title for this campaign')?>. . . ." maxlength="100" />

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Campaign description')?>:</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" placeholder="<?php echo SCTEXT('describe your campaign e.g. purpose or audience type for this campaign') ?>" name="cdesc"><?php echo htmlspecialchars_decode($data['cdata']->campaign_desc,ENT_QUOTES); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('2-Way Settings')?>:</label>
                                            <div class="col-md-8">
                                                <div class="p-sm panel m-b-0 bg-info">
                                                    <div>
                                                        <select class="form-control" name="pkeyword" id="pkeyword" data-plugin="select2" data-options="{templateResult: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var nstr = '<div class=\'pull-left\'>'+data.text+'</div><div class=\'pull-right\'><span class=\'label label-primary\'>'+data.element.dataset.vmn+'</span></div><div class=\'clearfix\'></div>';return $(nstr);} }, templateSelection: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var nstr = '<div class=\'pull-left\'>'+data.text+'</div><div class=\'pull-right\'><span class=\'label label-primary\'>'+data.element.dataset.vmn+'</span></div><div class=\'clearfix\'></div>';return $(nstr);} } }">
                                                            <option value="0">- <?php echo SCTEXT('NO KEYWORDS') ?> -</option>
                                                            <?php foreach($data['kws'] as $kw){ ?>
                                                            <option <?php if($data['cdata']->primary_keyword_id==$kw->id){ ?> selected <?php } ?> value="<?php echo $kw->id ?>" data-vmn="<?php echo $kw->vmn ?>"><?php echo $kw->keyword ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="help-block text-white"><?php echo SCTEXT('Select a primary keyword to link with this campaign')?></span>
                                                    </div>
                                                    <div id="optset" class="row p-sm">
                                                        <div class="col-md-6">
                                                            <h5><?php echo SCTEXT('Opt-in Keywords')?></h5>
                                                            <hr>
                                                            <input placeholder="e.g. YES, START, etc" data-plugin="tagsinput" name="optin_kws" class="form-control" value="<?php echo $data['cdata']->optin_keywords ?>">
                                                            <h5><?php echo SCTEXT('Opt-in Reply SMS')?></h5>
                                                            <hr>
                                                            <textarea name="optin_reply" placeholder="e.g. Thank you for your subscription" maxlength="120" class="form-control" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" class="form-control"><?php echo htmlspecialchars_decode($data['cdata']->optin_reply_sms, ENT_QUOTES) ?></textarea>
                                                        </div>
                                                        <div class="col-md-6 p-l-sm">
                                                            <h5><?php echo SCTEXT('Opt-out Keywords')?></h5>
                                                            <hr>
                                                            <input placeholder="e.g. STOP, BLOCK, etc" data-plugin="tagsinput" name="optout_kws" class="form-control" value="<?php echo $data['cdata']->optout_keywords ?>">
                                                            <h5><?php echo SCTEXT('Opt-out Reply SMS')?></h5>
                                                            <hr>
                                                            <textarea name="optout_reply" placeholder="e.g. You have been removed from our list" maxlength="120" class="form-control" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" class="form-control"><?php echo htmlspecialchars_decode($data['cdata']->optout_reply_sms, ENT_QUOTES) ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($_SESSION['user']['account_type']=='0'){ ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Route')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="rsmpp" id="rsmpp" data-plugin="select2">
                                                    <?php foreach($data['routes'] as $rt){ ?>
                                                    <option <?php if($data['cdata']->default_sms_route==$rt['id']){ ?> selected <?php } ?> data-stype="<?php echo $rt['senderType'] ?>" data-smax="<?php echo $rt['maxSender'] ?>" data-sdef="<?php echo $rt['defaultSender'] ?>" data-cov="<?php echo $rt['coverage'] ?>" value="<?php echo $rt['id'].'|'.$rt['senderType'] ?>"><?php echo $rt['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="sidselbox" class="form-group hidden">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="sendersel" id="sendersel" data-plugin="select2">
                                                    <?php foreach($data['sids'] as $sid){ ?>
                                                    <option <?php if($data['cdata']->default_sender==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div id="sidopnbox" class="form-group hidden">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender')?>:</label>
                                            <div class="col-md-8">
                                                <input value="<?php echo $data['cdata']->default_sender; ?>" type="text" name="senderopn" id="senderopn" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID')?>..." maxlength="50" />
                                                <span class="help-block text-info m-b-0"><?php echo SCTEXT('Follow the rules for Sender ID set by your SMS provider.')?></span>
                                            </div>
                                        </div>

                                        <?php } else {
                                            $routedata = array_values($_SESSION['credits']['routes'])[0];
                                            if($routedata['senderType']=='0'){
                                        ?>
                                        <div id="sidselbox" class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender')?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="defsender" id="sendersel" data-plugin="select2">
                                                    <?php foreach($data['sids'] as $sid){ ?>
                                                    <option <?php if($data['cdata']->default_sender==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <?php }
                                            if($routedata['senderType']=='2'){
                                        ?>
                                        <div id="sidopnbox" class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender')?>:</label>
                                            <div class="col-md-8">
                                                <input value="<?php echo $data['cdata']->default_sender; ?>" type="text" name="defsender" id="senderopn" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID')?>..." maxlength="50" />
                                                <span class="help-block text-info m-b-0"><?php echo SCTEXT('Follow the rules for Sender ID set by your SMS provider.')?></span>
                                            </div>
                                        </div>
                                        <?php } } ?>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Set Default Campaign')?>:</label>
                                            <div class="col-md-8">
                                                <div class="radio radio-inline radio-primary">
                                                    <input id="def1" <?php if($data['cdata']->is_default==0){ ?> checked <?php } ?> type="radio" name="isdef" value="0">
                                                    <label for="def1"><?php echo SCTEXT('No')?></label>
                                                </div>
                                                <div class="radio radio-inline radio-primary">
                                                    <input id="def2" <?php if($data['cdata']->is_default==1){ ?> checked <?php } ?> type="radio" name="isdef" value="1">
                                                    <label for="def2"><?php echo SCTEXT('Yes')?></label>
                                                </div>
                                            </div>
                                        </div>


                                        <hr>

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
