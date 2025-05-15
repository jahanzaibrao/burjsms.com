<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add New User') ?><small><?php echo SCTEXT('add a new user account and assign routes & credits') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="user_form" action="">
                                    <input type="hidden" name="ptype" id="ptype" value="0" />
                                    <?php if ($_SESSION['user']['group'] == 'reseller') { ?>
                                        <input type="hidden" data-type="0" data-rts="0" name="plan" id="plan" value="0" />
                                    <?php } ?>
                                    <div class="col-md-6">
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Billing Type') ?>:</label>
                                                <div class="col-md-8 topselect2">
                                                    <select class="form-control" data-plugin="select2" id="acctype" name="acctype" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center text-white\'>'+data.text+'</div>';return $(nstr); } }">
                                                        <option data-info="<?php echo SCTEXT('Here you assign SMS credits for different routes and each route has separate credit balance. The price stays the same for all mobile numbers in a particular route.') ?>" value="0">Credit Based (Flat Rate)</option>
                                                        <option data-info="<?php echo SCTEXT('Here you assign SMS prices for different routes and each route. A single currency balance is assigned and cost would be deducted based on selected route.') ?>" value="2">Currency Based (Dynamic Rate)</option>
                                                        <option data-info="<?php echo SCTEXT('This billing scheme will deduct amount from user account based on SMS plan for MCC and MNC matched. User will have a single account balance') ?> e.g. <?php echo Doo::conf()->currency ?>5,000" value="1">Currency Based (MCCMNC Rate)</option>

                                                    </select>
                                                    <span id="acctypeinfo" class="help-block text-primary">
                                                        <?php echo SCTEXT('Here you assign SMS credits for different routes and each route has separate credit balance. The price stays the same for all mobile numbers in a particular route.') ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Select Type') ?>:</label>
                                            <div class="col-md-8">
                                                <select id="ucat" class="form-control" name="category" data-plugin="select2">
                                                    <option value="client"><?php echo SCTEXT('Client Account') ?></option>
                                                    <option value="reseller"><?php echo SCTEXT('Reseller Account') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Name') ?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" name="uname" id="uname" class="form-control" placeholder="<?php echo SCTEXT('enter name of the user') ?> . . ." maxlength="100" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Gender') ?>:</label>
                                            <div class="col-md-8">
                                                <div class="radio radio-inline radio-primary">
                                                    <input id="gen_m" name="gender" checked="checked" type="radio" value="m">
                                                    <label for="gen_m"><?php echo SCTEXT('Male') ?></label>
                                                </div>
                                                <div class="radio radio-inline radio-primary">
                                                    <input id="gen_f" name="gender" value="f" type="radio">
                                                    <label for="gen_f"><?php echo SCTEXT('Female') ?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Email ID') ?>:</label>
                                            <div class="col-md-8 abs-ctr">
                                                <input type="text" name="uemail" id="uemail" class="form-control" placeholder="<?php echo SCTEXT('enter email address') ?>.." maxlength="100" />
                                                <span id="v-email" class="val-icon"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Phone') ?>:</label>
                                            <div class="col-md-8 abs-ctr">
                                                <input type="text" name="uphn" id="uphn" class="form-control" placeholder="e.g. 1555000000 . . ." maxlength="50" />
                                                <span id="v-phn" class="val-icon"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Login ID') ?>:</label>
                                            <div class="col-md-8 abs-ctr">
                                                <input type="text" name="ulogin" id="ulogin" class="form-control" placeholder="<?php echo SCTEXT('enter unique login ID for this user') ?>" maxlength="100" />
                                                <span id="v-login" class="val-icon"></span>
                                            </div>
                                        </div>
                                        <?php if (Doo::conf()->show_password == 1) { ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Enter Password') ?>:</label>
                                                <div class="col-md-8">
                                                    <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="upass" id="upass" class="form-control" placeholder="<?php echo SCTEXT('enter password') ?>..." maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Retype Password') ?>:</label>
                                                <div class="col-md-8">
                                                    <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="upass2" id="upass2" class="form-control" placeholder="<?php echo SCTEXT('enter password again') ?>..." maxlength="100" />
                                                    <span id="pass-err" class="help-block text-danger"></span>
                                                    <span id="pass-help" class="help-block text-primary">
                                                        <?php switch (Doo::conf()->password_strength) {
                                                            case 'weak':
                                                                echo SCTEXT('Password length should be minimum 6 characters.');
                                                                break;

                                                            case 'average':
                                                                echo SCTEXT('Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.');
                                                                break;

                                                            case 'strong':
                                                                echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.');
                                                                break;
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($_SESSION['user']['group'] == 'admin' && Doo::conf()->app_edition == 'e2+') {
                                            //hidden for future editions
                                        ?>
                                            <div class="form-group" style="">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('SMS Permission') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-primary">
                                                        <input id="opt-a" checked="checked" value="0" type="radio" name="optperm">
                                                        <label for="inv-a"><?php echo SCTEXT('Any contact numbers') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('User can send SMS to any contact number with freedom.') ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="opt-o" value="1" type="radio" name="optperm">
                                                        <label for="opt-o"><?php echo SCTEXT('Phonebook contacts only') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('User can only send SMS to opt-in contacts or phonebook contact numbers assigned by Admin.') ?></span>
                                                    </div>


                                                </div>
                                            </div>
                                        <?php }
                                        if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Account Manager') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="staff" name="staff" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                                                        <?php foreach ($data['staff'] as $staff) { ?>
                                                            <option value="<?php echo $staff['uid'] ?>" title="<?php echo $staff['name'] ?>"><?php echo $staff['avatar'] . '|' . $staff['email'] . '|' . $staff['theme'] . '|' . $staff['teamname'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Activation') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="p-sm panel m-b-0 bg-info">
                                                        <h5 class="m-b-xs m-t-0">Start:</h5>
                                                        <div class="input-group text-inverse">
                                                            <label for="acts_dp" class="input-group-addon bg-info text-white"><i class="fas fa-lg fa-calendar-check"></i> </label>
                                                            <input type="text" id="acts_dp" name="act_start" class="form-control">
                                                        </div>
                                                        <h5 class="m-b-xs">Expire:</h5>
                                                        <div class="input-group text-inverse">
                                                            <label for="acte_dp" class="input-group-addon bg-info text-white"><i class="fas fa-lg fa-calendar-times"></i> </label>
                                                            <input type="text" id="acte_dp" placeholder="leave empty for no expiry date.." name="act_expire" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Assign Assets') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="p-sm panel m-b-0 bg-default">
                                                        <h5 class="m-b-xs m-t-0"><?php echo SCTEXT('Approved Sender ID') ?>:</h5>
                                                        <div class="text-inverse">
                                                            <select class="form-control" data-plugin="select2" name="sids[]" data-placeholder="Assign Sender IDs" multiple>
                                                                <?php foreach ($data['senders'] as $sid) { ?>
                                                                    <option value="<?php echo $sid->id ?>"><?php echo $sid->sender_id ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <h5 class="m-b-xs"><?php echo SCTEXT('Approved Templates') ?>:</h5>
                                                        <div class="text-inverse">
                                                            <select class="form-control" data-plugin="select2" name="templates[]" data-placeholder="Assign Templates" multiple>
                                                                <?php foreach ($data['templates'] as $tpl) { ?>
                                                                    <option value="<?php echo $tpl->id ?>"><?php echo $tpl->title ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php if (is_countable($data['tlvs']) && sizeof($data['tlvs']) > 0) { ?>
                                                            <h5 class="m-b-xs"><?php echo SCTEXT('TLV Parameters') ?>:</h5>
                                                            <div class="text-inverse">
                                                                <select class="form-control" data-plugin="select2" name="tlvs[]" data-placeholder="Assign TLV Parameters" multiple>
                                                                    <?php foreach ($data['tlvs'] as $tlv) { ?>
                                                                        <option value="<?php echo $tlv->id ?>"><?php echo $tlv->tlv_title ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="col-md-6">

                                        <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Permission Group') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="pgid" name="pgid" class="form-control" data-plugin="select2">
                                                        <?php foreach ($data['pgroups'] as $pgroup) { ?>
                                                            <option value="<?php echo $pgroup->id ?>"><?php echo $pgroup->title ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Access Control') ?>:</label>
                                            <div class="col-md-8">
                                                <div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="acl_w" name="acl_mode" checked="checked" type="radio" value="0">
                                                        <label for="acl_w">Whitelist Mode</label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="acl_b" name="acl_mode" type="radio" value="1">
                                                        <label for="acl_b">Blacklist Mode</label>
                                                    </div>
                                                    <span id="acl_msg" class="help-block text-primary"><?php echo SCTEXT('Enter IP addresses that are allowed access to this account Panel and API') ?></span>
                                                </div>
                                                <hr>
                                                <div class="code">
                                                    <input data-plugin="tagsinput" class="form-control" type="text" placeholder="e.g. 127.0.0.1" name="acl_ip_list" maxlength="10" value="*.*.*.*">
                                                </div>
                                                <span class="help-block"><?php echo SCTEXT('Enter IP separated by comma sign. Enter <code>*.*.*.*</code> for all IP addresses.') ?></span>

                                            </div>
                                        </div>

                                        <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                            <div id="currencybox" style="display: none">

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Select SMS Plan') ?>:</label>
                                                    <div class="col-md-8">
                                                        <select id="mccmncplans" name="mccmncplans" class="form-control" data-plugin="select2">
                                                            <?php foreach ($data['mplans'] as $plan) { ?>
                                                                <option data-ptax="<?php echo $plan->tax ?>" data-taxtype="<?php echo $plan->tax_type ?>" value="<?php echo $plan->id ?>"><?php echo $plan->plan_name ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Load Credits') ?>:</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                            <input type="text" name="mplancredits" id="mplanscredits" class="form-control" placeholder="e.g. 500">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Total Payable') ?>:</label>
                                                    <div class="col-md-8">
                                                        <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="total_payable">0.00</span> <small id="mcc_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes') ?>)</small></h3>
                                                    </div>
                                                </div>


                                            </div>
                                        <?php } ?>

                                        <div id="creditbox">

                                            <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Select SMS Plan') ?>:</label>
                                                    <div class="col-md-8">
                                                        <select name="plan" class="form-control" data-plugin="select2" id="plan">
                                                            <option data-type="0" data-rts="0" value="0"><?php echo SCTEXT('Custom SMS Rates') ?></option>
                                                            <?php foreach ($data['plans'] as $plan) { ?>
                                                                <option data-type="<?php echo $plan->plan_type ?>" data-rts="<?php echo $plan->route_ids ?>" value="<?php echo $plan->id ?>"><?php echo $plan->plan_name ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div id="routes-n-credits" class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Routes & Credits') ?>:</label>
                                                <div class="col-md-8 route-assign-ctr">

                                                    <?php if ($_SESSION['user']['group'] == 'admin') { ?>

                                                        <?php $ctr = 0;
                                                        foreach ($data['routes'] as $rt) {  ?>
                                                            <div class="po-ctr m-b-sm" data-rid="<?php echo $rt->id ?>">
                                                                <div>
                                                                    <input data-switchery data-color="#10c469" data-size="small" id="rtsel-<?php echo $rt->id ?>" data-rid="<?php echo $rt->id ?>" class="route-sel" name="route[<?php echo $rt->id ?>]" type="checkbox" <?php if ($ctr == 0) { ?> checked <?php } ?>>
                                                                    <label for="rtsel-<?php echo $rt->id ?>"><?php echo $rt->title ?></label>
                                                                </div>
                                                                <div id="rtcredetail-<?php echo $rt->id ?>" class="clearfix collapse <?php if ($ctr == 0) { ?> in <?php } ?>">
                                                                    <table class="wd100 table row-border">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo SCTEXT('SMS Credits') ?></th>
                                                                                <th><?php echo SCTEXT('Per SMS Cost') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="input-group"><input id="rtcre-<?php echo $rt->id ?>" class="rtcredits form-control input-small-sc input-sm" name="credits[<?php echo $rt->id ?>]" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">sms</span></div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input id="rtprc-<?php echo $rt->id ?>" class="rtrates form-control input-small-sc input-sm" name="rate[<?php echo $rt->id ?>]" placeholder="e.g. 0.05" value="" type="text"></div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>

                                                            </div>
                                                        <?php $ctr++;
                                                        } ?>


                                                    <?php } else { ?>

                                                        <?php foreach ($_SESSION['credits']['routes'] as $rt) {  ?>
                                                            <div class="po-ctr" data-rid="<?php echo $rt['id'] ?>">
                                                                <div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="rtsel-<?php echo $rt['id'] ?>" class="route-sel" name="route[<?php echo $rt['id'] ?>]" checked="checked" type="checkbox">
                                                                        <label for="rtsel-<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></label>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <table class="wd100 table row-border">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo SCTEXT('SMS Credits') ?></th>
                                                                                <th><?php echo SCTEXT('Per SMS Cost') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="input-group"><input id="rtcre-<?php echo $rt['id'] ?>" class="rtcredits form-control input-small-sc input-sm" name="credits[<?php echo $rt['id'] ?>]" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">sms</span></div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input id="rtprc-<?php echo $rt['id'] ?>" class="rtrates form-control input-small-sc input-sm" name="rate[<?php echo $rt['id'] ?>]" placeholder="e.g. 0.05" value="" type="text"></div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>

                                                            </div>
                                                        <?php } ?>


                                                    <?php } ?>


                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Total amount') ?>:</label>
                                                <div class="col-md-8">
                                                    <h4 class=" text-primary"><?php echo Doo::conf()->currency ?><span id="total_amt">0.00</span> <small id="plan_taxes" class="m-l-sm" style="font-size:14px; "></small></h4>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Additional Tax') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="col-md-6 col-sm-4 col-xs-8 input-group">
                                                        <input type="text" name="utax" id="utax" class="form-control" placeholder="e.g. 14.5" maxlength="50" />
                                                        <span class="input-group-addon">%</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Discount') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" name="udis" id="udis" class="form-control w-100-px" placeholder="e.g.  20" maxlength="50" />

                                                        <select id="distype" class="form-control w-100-px" name="distype">
                                                            <option value="per">Percent</option>
                                                            <option value="cur"><?php echo Doo::conf()->currency_name ?></option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total') ?>:</label>
                                                <div class="col-md-8">
                                                    <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="grand_total_amt">0.00</span> <small id="all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes') ?>)</small></h3>
                                                </div>
                                            </div>

                                        </div>


                                        <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>

                                            <div id="dynamicbox" style="display: none;">

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Load Credits') ?>:</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                            <input type="text" name="curcredits" id="curcredits" class="form-control" placeholder="e.g. 500">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="routes-n-credits-cur" class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Routes & Credits') ?>:</label>
                                                    <div class="col-md-8 route-assign-ctr-cur">

                                                        <?php if ($_SESSION['user']['group'] == 'admin') { ?>

                                                            <?php $ctr = 0;
                                                            foreach ($data['routes'] as $rt) {  ?>
                                                                <div class="po-ctr m-b-sm" data-rid="<?php echo $rt->id ?>">
                                                                    <div>
                                                                        <input data-switchery data-color="#10c469" data-size="small" id="rtcursel-<?php echo $rt->id ?>" data-rid="<?php echo $rt->id ?>" class="route-sel route-sel-cur" name="routecur[<?php echo $rt->id ?>]" type="checkbox" <?php if ($ctr == 0) { ?> checked <?php } ?>>
                                                                        <label for="rtcursel-<?php echo $rt->id ?>"><?php echo $rt->title ?></label>
                                                                    </div>
                                                                    <div id="rtcurdetail-<?php echo $rt->id ?>" class="clearfix collapse <?php if ($ctr == 0) { ?> in <?php } ?>">
                                                                        <table class="wd100 table row-border">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><?php echo SCTEXT('Per SMS Cost') ?></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input id="rtcurprc-<?php echo $rt->id ?>" class="rtrates form-control input-small-sc input-sm" name="ratecur[<?php echo $rt->id ?>]" placeholder="e.g. 0.05" value="" type="text"></div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>

                                                                </div>
                                                            <?php $ctr++;
                                                            } ?>


                                                        <?php } else { ?>

                                                            <?php foreach ($_SESSION['credits']['routes'] as $rt) {  ?>
                                                                <div class="po-ctr" data-rid="<?php echo $rt['id'] ?>">
                                                                    <div>
                                                                        <div class="checkbox checkbox-primary">
                                                                            <input id="rtsel-<?php echo $rt['id'] ?>" class="route-sel" name="route[<?php echo $rt['id'] ?>]" checked="checked" type="checkbox">
                                                                            <label for="rtsel-<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></label>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <table class="wd100 table row-border">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><?php echo SCTEXT('SMS Credits') ?></th>
                                                                                    <th><?php echo SCTEXT('Per SMS Cost') ?></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="input-group"><input id="rtcre-<?php echo $rt['id'] ?>" class="rtcredits form-control input-small-sc input-sm" name="credits[<?php echo $rt['id'] ?>]" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">sms</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input id="rtprc-<?php echo $rt['id'] ?>" class="rtrates form-control input-small-sc input-sm" name="rate[<?php echo $rt['id'] ?>]" placeholder="e.g. 0.05" value="" type="text"></div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>

                                                                </div>
                                                            <?php } ?>


                                                        <?php } ?>


                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Total amount') ?>:</label>
                                                    <div class="col-md-8">
                                                        <h4 class=" text-primary"><?php echo Doo::conf()->currency ?><span id="total_amt_cur">0.00</span> <small id="cur_taxes" class="m-l-sm" style="font-size:14px; "></small></h4>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Additional Tax') ?>:</label>
                                                    <div class="col-md-8">
                                                        <div class="col-md-6 col-sm-4 col-xs-8 input-group">
                                                            <input type="text" name="utax" id="utax_cur" class="form-control" placeholder="e.g. 14.5" maxlength="50" />
                                                            <span class="input-group-addon">%</span>

                                                        </div>
                                                    </div>
                                                </div>




                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total') ?>:</label>
                                                    <div class="col-md-8">
                                                        <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="grand_total_amt_cur">0.00</span> <small id="all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes') ?>)</small></h3>
                                                    </div>
                                                </div>

                                            </div>

                                        <?php } ?>







                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Invoice status') ?>:</label>
                                            <div class="col-md-8">
                                                <div class="radio radio-primary">
                                                    <input id="inv-d" checked="checked" value="2" type="radio" name="invstatus">
                                                    <label for="inv-d"><?php echo SCTEXT('Payment Due') ?></label>
                                                    <span class="help-block"><?php echo SCTEXT('User will be able to send/sell SMS only after payment is made') ?></span>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="inv-p" value="1" type="radio" name="invstatus">
                                                    <label for="inv-p"><?php echo SCTEXT('Prepaid') ?></label>
                                                    <span class="help-block"><?php echo SCTEXT('User has already paid the amount via cheque, cash etc.') ?></span>
                                                </div>
                                                <div id="invrmbox" class="hidden">
                                                    <label class="label label-info label-md"><?php echo SCTEXT('Remarks') ?>:</label>
                                                    <textarea name="invremarks" class="form-control" placeholder="<?php echo SCTEXT('enter details of the payment e.g. cheque number etc. for record keeping') ?>"></textarea>
                                                </div>

                                            </div>
                                        </div>


                                    </div>

                                    <div class="clearfix"></div>

                                    <hr>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <button id="bk" type="button" class="btn btn-default"><i class="fa fa-lg fa-chevron-left"></i>&nbsp;&nbsp;<?php echo SCTEXT('Back to User Mgmt') ?>.</button>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <button class="btn btn-primary" id="save_changes" type="button"><i class="fa fa-lg fa-check"></i>&nbsp;&nbsp;<?php echo SCTEXT('Create Account') ?></button>
                                            </div>
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