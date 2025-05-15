<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix"><?php echo SCTEXT('View Account') ?><small><?php echo $data['user']->name . ' (' . $data['user']->email . ')' ?></small>
                                <span class="dropdown pull-right">
                                    <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><i class="fa fa-large fa-navicon"></i> &nbsp; <?php echo SCTEXT('Actions') ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                        <li><a class="useraction" data-act="upgradeacc" href="javascript:void(0);"><i class="fa fa-large fa-user-plus"></i>&nbsp;&nbsp; <?php echo SCTEXT('Upgrade to Reseller') ?> </a></li>
                                        <li><a class="useraction" data-act="changepsw" href="javascript:void(0);"><i class="fa fa-large fa-key"></i>&nbsp;&nbsp; <?php echo SCTEXT('Change Password') ?> </a></li>
                                        <li><a class="useraction" data-act="usersus" href="javascript:void(0);"><i class="fa fa-large fa-ban"></i>&nbsp;&nbsp; <?php echo SCTEXT('Suspend Account') ?> </a></li>
                                        <li><a class="useraction" data-act="userdel" href="javascript:void(0);"><i class="fa fa-large fa-trash"></i>&nbsp;&nbsp; <?php echo SCTEXT('Delete Account') ?> </a></li>
                                    </ul>

                                </span>
                            </h3>
                            <hr class="m-t-xs">
                            <?php include('notification.php') ?>

                            <?php include('navpills.php') ?>

                            <hr>
                            <h4 class="page-title-sc"><?php echo SCTEXT('Edit SMPP Account') ?><small><?php echo SCTEXT('modify SMPP client account parameters') ?></small></h4>
                            <hr>

                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="smppclient_frm" action="">
                                    <input type="hidden" value="<?php echo $data['user']->user_id ?>" name="userid" id="userid">
                                    <input type="hidden" name="scid" value="<?php echo $data['smpp']->id ?>">
                                    <?php if ($data['user']->account_type == '0' || $data['user']->account_type == '2') { ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Associated Route') ?>:</label>
                                            <div class="col-md-8">
                                                <select data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('select route') ?> ..'}" class="form-control" name="route" id="c_route">
                                                    <?php foreach ($data['cdata'] as $rt) { ?>
                                                        <option <?php if ($data['smpp']->route_id == $rt->route_id) { ?> selected <?php } ?> value="<?php echo $rt->route_id ?>"><?php echo $rt->title ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('System ID') ?>:</label>
                                        <div class="col-md-8">
                                            <input class="form-control" readonly type="text" placeholder="<?php echo SCTEXT('enter username for smpp client') ?> ..." value="<?php echo $data['smpp']->system_id ?>" name="systemid" maxlength="12" id="smpp_sysid">
                                            <span id="v-login" class="val-icon"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('SMPP Password') ?>:</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="text" placeholder="<?php echo SCTEXT('enter password for smpp client') ?> ..." value="<?php echo $data['smpppass'] ?>" name="smpp_pass" id="smpp_pass">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Maximum Tx') ?>:</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="number" min="0" max="100" placeholder="<?php echo SCTEXT('maximum allowed transmitter sessions') ?> ..." value="<?php echo $data['smpp']->tx_max ?>" name="tx">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Maximum Rx') ?>:</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="number" min="0" max="100" placeholder="<?php echo SCTEXT('maximum allowed receiver sessions') ?> ..." value="<?php echo $data['smpp']->rx_max ?>" name="rx">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Maximum TRx') ?>:</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="number" min="0" max="100" placeholder="<?php echo SCTEXT('maximum allowed TRx sessions') ?> ..." value="<?php echo $data['smpp']->trx_max ?>" name="trx">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Allowed IP List') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="code">
                                                <input data-plugin="tagsinput" class="form-control" type="text" placeholder="e.g. 127.0.0.1" name="allowed_ip" maxlength="8" value="<?php echo $data['smpp']->allowed_ip ?>">
                                            </div>
                                            <span class="help-block"><?php echo SCTEXT('Enter IP separated by comma sign. Enter <code>*.*.*.*</code> to allow all IP addresses.') ?></span>
                                        </div>

                                    </div>
                                    <?php if (sizeof($data['vmns']) > 0) { ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('VMN for MO Forwarding') ?>:</label>
                                            <div class="col-md-8">
                                                <select data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('choose VMN') ?> ..'}" class="form-control" name="vmn" id="vmn">
                                                    <option value="0">None</option>
                                                    <?php foreach ($data['vmns'] as $vmn) { ?>
                                                        <option <?php if ($data['smpp']->vmn == $vmn->vmn) { ?> selected <?php } ?> value="<?php echo $vmn->vmn ?>"><?php echo $vmn->vmn ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                    <?php } ?>
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save Changes') ?></button>
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