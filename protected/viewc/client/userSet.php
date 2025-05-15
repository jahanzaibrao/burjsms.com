<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Account Settings') ?><small><?php echo SCTEXT('modify email or account preferences') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="usfrm" action="">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Daily SMS Reports') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="radio radio-inline radio-primary">
                                                <input id="dsyes" name="dsflag" value="1" <?php if ($data['sdata']->email_daily_sms == '1') { ?>checked="checked" <?php } ?> type="radio">
                                                <label for="dsyes"><?php echo SCTEXT('Yes') ?></label>
                                            </div>
                                            <div class="radio radio-inline radio-primary">
                                                <input id="dsno" name="dsflag" value="0" <?php if ($data['sdata']->email_daily_sms == '0') { ?>checked="checked" <?php } ?> type="radio">
                                                <label for="dsno"><?php echo SCTEXT('No') ?></label>
                                            </div>
                                            <span class="help-block"><?php echo SCTEXT('If selected YES, system will send you a <b>daily email</b> with SMS Traffic Report for the day.') ?> </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Low Credit Alert') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="radio radio-inline radio-primary">
                                                <input id="dcyes" name="dcflag" value="1" <?php if ($data['sdata']->email_daily_credits == '1') { ?>checked="checked" <?php } ?> type="radio">
                                                <label for="dcyes"><?php echo SCTEXT('Yes') ?></label>
                                            </div>
                                            <div class="radio radio-inline radio-primary">
                                                <input <?php if ($data['sdata']->email_daily_credits == '0') { ?>checked="checked" <?php } ?> id="dcno" name="dcflag" value="0" type="radio">
                                                <label for="dcno"><?php echo SCTEXT('No') ?></label>
                                            </div>
                                            <span class="help-block"><?php echo SCTEXT('If selected YES, system will send you an <b> email</b> alerting when your credits reach below') ?> <?php echo number_format(Doo::conf()->low_credit_alert_threshold) ?> <?php echo SCTEXT('SMS for any assigned route.') ?> </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Email App Alerts') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="radio radio-inline radio-primary">
                                                <input id="enyes" name="enflag" value="1" <?php if ($data['sdata']->email_app_notif == '1') { ?>checked="checked" <?php } ?> type="radio">
                                                <label for="enyes"><?php echo SCTEXT('Yes') ?></label>
                                            </div>
                                            <div class="radio radio-inline radio-primary">
                                                <input <?php if ($data['sdata']->email_app_notif == '0') { ?>checked="checked" <?php } ?> id="enno" name="enflag" value="0" type="radio">
                                                <label for="enno"><?php echo SCTEXT('No') ?></label>
                                            </div>
                                            <span class="help-block"><?php echo SCTEXT('If selected YES, system will send you an email for important alerts received in the application.') ?> </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Default SMS Route') ?>:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" data-plugin="select2" name="defrt" data-options="{placeholder: '<?php echo SCTEXT('Select a route') ?> ..'}">
                                                <option></option>
                                                <?php foreach ($_SESSION['credits']['routes'] as $rt) { ?>
                                                    <option <?php if ($data['sdata']->def_route == $rt['id']) { ?> selected <?php } ?> value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block"><?php echo SCTEXT('Select a route which will be used by default for SMS campaigns, you would still have the freedom to choose Route when you send the campaign.') ?> </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Default DLR URL') ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="defdlrurl" placeholder="enter a URL with protocol e.g. http://dlr.endpoint.com/script.php . . ." value="<?php echo $data['sdata']->default_dlr_url ?>">
                                            <span class="help-block"><?php echo SCTEXT('Provide a URL where the DLRs from API calls will be pushed.') ?> </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Default MO URL') ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="defmourl" placeholder="enter a URL with protocol e.g. http://mo.endpoint.com/script.php . . ." value="<?php echo $data['sdata']->default_mo_url ?>">
                                            <span class="help-block"><?php echo SCTEXT('Provide a URL where the MO notifications from API calls will be pushed.') ?> </span>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('TinyURL Domain') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="radio radio-primary">
                                                <input id="turlglobal" name="turlflag" value="0" <?php if (!$data['turldata']->domain) { ?> checked="checked" <?php } ?> type="radio">
                                                <label for="turlglobal">
                                                    <?php echo SCTEXT('Use System Domain') ?>
                                                    <kbd class="m-l-sm"><?php echo Doo::conf()->tinyurl ?></kbd>
                                                </label>

                                            </div>
                                            <div class="radio radio-primary">
                                                <input <?php if ($data['turldata']->domain) { ?> checked="checked" <?php } ?> id="turlcustom" name="turlflag" value="1" type="radio">
                                                <label for="turlcustom"><?php echo SCTEXT('Use your own Domain') ?></label>
                                                <div class="input-group m-t-sm">
                                                    <span class="input-group-addon">http://</span>
                                                    <input type="text" name="customturl" id="customturl" value="<?php echo $data['turldata']->domain ?>">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" type="button" id="saveuset"><?php echo SCTEXT('Save Settings') ?></button>
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