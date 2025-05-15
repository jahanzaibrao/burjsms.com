
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Sign up Settings')?><small><?php echo SCTEXT('define app settings for new user registerations')?> </small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" style="margin-top:-20px;" action="" method="post" id="sig_form">
                                        <div class="col-md-6 p-r-sm">
                                    <?php $ndata = unserialize($data['stdata']->notif_data);
                                            $sdata = unserialize($data['stdata']->signup_data);
                                            ?>
                                                <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Sign up notification settings')?></h4>
                                                </div>
                                                <div class="panel-body">
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Send Email')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="m-y" <?php if($ndata['email']=='1'){ ?> checked="checked" <?php } ?> name="signupmail" value="1" type="radio">
                                                                <label for="m-y"><?php echo SCTEXT('Yes')?></label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="m-n" name="signupmail" <?php if($ndata['email']=='0'){ ?> checked="checked" <?php } ?> value="0" type="radio">
                                                                <label for="m-n"><?php echo SCTEXT('No')?></label>
                                                            </div>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Select YES if you want to send confirmation email to the registered user')?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Send SMS')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="s-y" <?php if($ndata['sms']=='1'){ ?> checked="checked" <?php } ?> name="signupsms" value="1" type="radio">
                                                                <label for="s-y"><?php echo SCTEXT('Yes')?></label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="s-n" <?php if($ndata['sms']=='0'){ ?> checked="checked" <?php } ?> name="signupsms" value="0" type="radio">
                                                                <label for="s-n"><?php echo SCTEXT('No')?></label>
                                                            </div>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Do you wish to send confirmation SMS as well when a new user registers?')?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Send Password')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="sp-y" <?php if($ndata['pass_flag']=='1'){ ?> checked="checked" <?php } ?> name="sendpass" value="1" type="radio">
                                                                <label for="sp-y"><?php echo SCTEXT('Yes')?></label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="sp-n" <?php if($ndata['pass_flag']=='0'){ ?> checked="checked" <?php } ?> name="sendpass" value="0" type="radio">
                                                                <label for="sp-n"><?php echo SCTEXT('No')?></label>
                                                            </div>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Do you want to send passwords in Email and SMS when user Forget Password')?></span>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Select Route')?>:</label>
                                                        <div class="col-md-8">
                                                            <select name="smsrt" data-plugin="select2" class="form-control">
                                                                <?php foreach($data['rdata'] as $rt){ ?>
                                                                <option <?php if($ndata['sms_route']==$rt->id){ ?> selected <?php } ?> value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Select the route you wish to use for sending notification SMS when new user account is created')?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID')?>:</label>
                                                        <div class="col-md-8">
                                                            <select name="smssid" data-plugin="select2" class="form-control">
                                                                <?php foreach($data['sdata'] as $sid){ ?>
                                                                <option <?php if($ndata['sms_sid']==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Select the Sender ID to be used when sending sign-up notification SMS')?></span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                               
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title"><?php echo SCTEXT('New account default settings')?></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Route')?>:</label>
                                                            <div class="col-md-8">
                                                                <select name="defrt" data-plugin="select2" class="form-control">
                                                                    <?php foreach($data['rdata'] as $rt){ ?>
                                                                    <option <?php if($sdata['def_route']==$rt->id){ ?> selected <?php } ?> value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="help-block m-b-0"><?php echo SCTEXT('Select the route to be assigned to new user account')?> <?php if($_SESSION['user']['group']=='admin') echo SCTEXT('if no SMS plan is selected'); ?></span>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Free Credits')?>:</label>
                                                            <div class="col-md-8 input-group">
                                                                <input id="frecre" type="text" name="frecre" maxlength="6" value="<?php echo $sdata['free_credits'] ?>" placeholder="e.g. 100" class="form-control" />
                                                                <span class="input-group-addon">SMS</span>
                                                                
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('SMS Price')?>:</label>
                                                            <div class="col-md-8 input-group">
                                                                <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                                <input id="smsrate" type="text" name="smsrate" maxlength="6" value="<?php echo $sdata['sms_rate'] ?>" placeholder="e.g. 0.05" class="form-control input-sm" />
                                                                <span class="input-group-addon">per SMS</span>
                                                                
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Tax on tariff')?>:</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" name="ptax" id="ptax" class="form-control input-sm" placeholder="e.g. 14.5" maxlength="50" value="<?php echo $sdata['taxper'] ?>" />
                                                                        <span class="input-group-addon">%</span>
                                                                        <select class="form-control input-sm" name="taxtype">
                                                                            <option <?php if($sdata['taxtype']=='VT'){ ?> selected <?php } ?> value="VT">VAT</option>
                                                                            <option <?php if($sdata['taxtype']=='ST'){ ?> selected <?php } ?> value="ST">Service Tax</option>
                                                                            <option <?php if($sdata['taxtype']=='SC'){ ?> selected <?php } ?> value="SC">Service Charge</option>
                                                                            <option <?php if($sdata['taxtype']=='GT'){ ?> selected <?php } ?> value="GT">GST</option>
                                                                            <option <?php if($sdata['taxtype']=='OT'){ ?> selected <?php } ?> value="OT"><?php echo SCTEXT('Other Taxes')?></option>
                                                                        </select> 
                                                                        

                                                                    </div>
                                                                    <span class="help-block m-b-0"><?php echo SCTEXT('This tax will be applied when customer places order for more SMS credits')?></span>

                                                                </div>
                                                        </div>
                                                        
                                                        
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Validity')?>:</label>
                                                            <div class="col-md-8">
                                                                <select name="defval" data-plugin="select2" class="form-control">
                                                                    <option <?php if($sdata['def_validity']=='1 month'){ ?> selected <?php } ?> value="1 month"><?php echo SCTEXT('1 Month')?></option>
                                                                    <option <?php if($sdata['def_validity']=='3 months'){ ?> selected <?php } ?> value="3 months"><?php echo SCTEXT('3 Months')?></option>
                                                                    <option <?php if($sdata['def_validity']=='6 months'){ ?> selected <?php } ?> value="6 months"><?php echo SCTEXT('6 Months')?></option>
                                                                    <option <?php if($sdata['def_validity']=='1 year'){ ?> selected <?php } ?> value="1 year"><?php echo SCTEXT('1 Year')?></option>
                                                                    <option <?php if($sdata['def_validity']=='2 years'){ ?> selected <?php } ?> value="2 years"><?php echo SCTEXT('2 Years')?></option>
                                                                    <option <?php if($sdata['def_validity']=='5 years'){ ?> selected <?php } ?> value="5 years"><?php echo SCTEXT('5 Years')?></option>
                                                                    <option <?php if($sdata['def_validity']=='10 years'){ ?> selected <?php } ?> value="10 years"><?php echo SCTEXT('10 Years')?></option>
                                                                    <option <?php if($sdata['def_validity']=='20 years'){ ?> selected <?php } ?> value="20 years"><?php echo SCTEXT('Lifetime')?></option>
                                                                </select>
                                                                <span class="help-block m-b-0"><?php echo SCTEXT('Choose default validity period for new accounts. You can change validity of each user later as well.')?></span>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID')?>:</label>
                                                            <div class="col-md-8">
                                                                <?php if($_SESSION['user']['group']=='admin'){ ?>
                                                                <input class="form-control" name="defsid" type="text" placeholder="<?php echo SCTEXT('enter sender ID')?> e.g. WEBSMS" id="defsid" value="<?php echo $sdata['def_sender'] ?>" />
                                                                <?php }else{ ?>
                                                                <select name="defsid" data-plugin="select2" class="form-control">
                                                                <?php foreach($data['sdata'] as $sid){ ?>
                                                                <option <?php if($sdata['def_sender']==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                <?php } ?>
                                                                <span class="help-block m-b-0"><?php echo SCTEXT('Provide a sender ID to be assigned to the new user accounts by default for sending SMS')?></span>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                        <?php if($_SESSION['user']['subgroup']=='admin'){ 
                                                    //hidden for mini plus feature
                                                    ?>
                                                        
                                                        <div class="form-group" style="display:none;">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('SMS Permission')?>:</label>
                                                            <div class="col-md-8">
                                                                <div class="radio radio-primary">
                                                                    <input id="opt-a" <?php if($sdata['optin']=='0'){ ?> checked="checked" <?php } ?> value="0" type="radio" name="optperm">
                                                                    <label for="inv-a"><?php echo SCTEXT('Any contact numbers')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT('User can send SMS to any contact number with freedom.')?></span>
                                                                </div>
                                                                <div class="radio radio-primary">
                                                                    <input id="opt-o" <?php if($sdata['optin']=='1'){ ?> checked="checked" <?php } ?> value="1" type="radio" name="optperm">
                                                                    <label for="opt-o"><?php echo SCTEXT('Opt-in contacts only')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT('User can only send SMS to opt-in contacts or contact numbers assigned by Admin.')?></span>
                                                                </div>


                                                            </div>
                                                        </div>

                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Account Mgr.')?>:</label>
                                                                <div class="col-md-8">
                                                                    <select id="staff" name="staff" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                                                                        <?php foreach($data['staff'] as $staff){ ?>
                                                                        <option <?php if($sdata['acc_mgr']==$staff['uid']){ ?> selected <?php } ?> value="<?php echo $staff['uid'] ?>" title="<?php echo $staff['name'] ?>"><?php echo $staff['avatar'].'|'.$staff['email'].'|'.$staff['theme'].'|'.$staff['teamname'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                    </div>
                                                        
                                                        <?php } ?>
                                                        
                                                        
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr style="clear:both;">
                                        
                                        <div class="form-group">
                                                        <div class="col-md-4"></div> 
                                                        <div class="col-md-8">   
                                                        <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes')?></button>
                                                        
                                                        <button class="btn btn-default m-l-md" id="bk" type="button"><?php echo SCTEXT('Cancel')?></button></div>
                                        </div>
                                        
                                     </form>   
                                        
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>