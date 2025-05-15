<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Edit Route') ?><small><?php echo SCTEXT('change SMS Route parameters') ?></small></h3>
                            <hr>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="add_route_form" action="">
                                    <input type="hidden" name="routeid" value="<?php echo $data['rdata']->id ?>" />
                                    <?php
                                    $actdata = json_decode(base64_decode($data['rdata']->active_time), true);
                                    $bldbs = explode(",", $data['rdata']->blacklist_ids);
                                    //route config
                                    $route_config = json_decode($data['rdata']->route_config, true);
                                    ?>
                                    <div class="block">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Route Title') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="title" id="rt_title" class="form-control pop-over" data-content="<?php echo SCTEXT('This title will be visible to all those users who will use this route to send SMS') ?>" placeholder="<?php echo SCTEXT('enter a title for this route') ?>" value="<?php echo $data['rdata']->title ?>" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Routing Logic') ?>:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="ralgo" id="ralgo" data-plugin="select2">
                                                        <option <?php if ($route_config['mode'] == 0) { ?> selected <?php } ?> value="0">Dedicated Routing</option>

                                                        <option <?php if ($route_config['mode'] == 1) { ?> selected <?php } ?> value="1">Percent Distribution</option>
                                                        <option <?php if ($route_config['mode'] == 2) { ?> selected <?php } ?> value="2">Round Robin Allocation</option>
                                                        <option <?php if ($route_config['mode'] == 3) { ?> selected <?php } ?> value="3">Least Cost Routing</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            //prepare the string for already existing config rows
                                            $saved_smppopt = '';
                                            $saved_vapiopt = '';
                                            //prepare option string for select field for smpp
                                            $smppopt = '';
                                            foreach ($data['smpp'] as $ch1) {
                                                $smppopt .= '<option data-smsc="smpp" value="' . $ch1->id . '">' . $ch1->title . '</option>';
                                                $saved_smppopt .= '<option data-smsc="smpp" data-sid="' . $ch1->id . '" value="' . $ch1->id . '">' . $ch1->title . '</option>';
                                            }
                                            //prepare vendor API channels
                                            $vapiopt = '';
                                            if (isset($data['vapi'])) {
                                                foreach ($data['vapi'] as $ch12) {
                                                    $vapiopt .= '<option data-smsc="http" value="' . $ch12->id . '">' . $ch12->title . '</option>';
                                                    $saved_vapiopt .= '<option data-smsc="http" data-sid="' . $ch12->id . '" value="' . $ch12->id . '">' . $ch12->title . '</option>';
                                                }
                                            }
                                            //prepare string of rows for each algorithm
                                            $persmppstr = '<tr><td><select name="persmppid[]" class="form-control input-sm">' . $smppopt . '</select></td><td><div class="input-group" style="max-width: 80px;"><input value="50" type="text" name="persmppval[]" class="form-control input-sm"><span class="input-group-addon">%</span></div></td><td><button type="button" class="rmrow btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td></tr>';
                                            $rrsmppstr = '<tr><td><select name="rrsmppid[]" class="form-control input-sm">' . $smppopt . '</select></td><td><div class="input-group" style="max-width: 160px;"><input value="" placeholder="e.g. 20000" name="rrsmppval[]" type="text" class="form-control input-sm" ><span class="input-group-addon">SMS</span></div></td><td><button type="button" class="rmrow btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td></tr>';
                                            $lcrsmppstr = '<tr><td><select name="lcrsmppid[]" class="form-control input-sm">' . $smppopt . '</select></td><td><button type="button" class="rmrow btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td></tr>';
                                            ?>
                                            <input type="hidden" id="persmpprow" value="<?php echo base64_encode($persmppstr) ?>">
                                            <input type="hidden" id="rrsmpprow" value="<?php echo  base64_encode($rrsmppstr) ?>">
                                            <input type="hidden" id="lcrsmpprow" value="<?php echo base64_encode($lcrsmppstr)  ?>">
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-8">
                                                    <div id="ralgo_3" class="p-sm panel m-b-0 bg-info ralgoctrs <?php if ($route_config['mode'] != 3) { ?> hidden <?php } ?>">
                                                        <div class="m-b-sm">
                                                            System will route the SMS traffic to the SMPP which has least cost-price for the MCCMNC (network/operator)
                                                        </div>
                                                        <div class="clearfix sepH_b m-b-xs">
                                                            <div class="btn-group pull-right">
                                                                <a href="javascript:void(0)" id="add_lcrsmpprow" class="btn btn-inverse btn-sm"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add SMPP') ?></a>
                                                            </div>

                                                        </div>
                                                        <fieldset>
                                                            <table class="table">
                                                                <tbody id="ralgo_3_tbody">
                                                                    <?php if ($route_config['mode'] == 3) { ?>
                                                                        <?php foreach ($route_config['smsc_list'] as $tr) {
                                                                            $selectedopt = str_replace('data-sid="' . $tr['smpp'] . '"', 'data-sid="' . $tr['smpp'] . '" selected ', $saved_smppopt);
                                                                        ?>
                                                                            <tr>
                                                                                <td><select name="lcrsmppid[]" class="form-control input-sm"><?php echo $selectedopt ?></select></td>
                                                                                <td><button type="button" class="rmrow btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                                                                            </tr>
                                                                    <?php }
                                                                    } else {
                                                                        echo $lcrsmppstr;
                                                                    } ?>
                                                                </tbody>
                                                            </table>

                                                        </fieldset>
                                                    </div>
                                                    <div id="ralgo_2" class="p-sm panel m-b-0 bg-info ralgoctrs <?php if ($route_config['mode'] != 2) { ?> hidden <?php } ?>">
                                                        <div class="clearfix sepH_b m-b-xs">
                                                            <div class="btn-group pull-right">
                                                                <a href="javascript:void(0)" id="add_rrsmpprow" class="btn btn-inverse btn-sm"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add SMPP') ?></a>
                                                            </div>

                                                        </div>
                                                        <fieldset>
                                                            <table class="table">
                                                                <tbody id="ralgo_2_tbody">
                                                                    <?php if ($route_config['mode'] == 2) { ?>
                                                                        <?php foreach ($route_config['smsc_list'] as $tr) {
                                                                            $selectedopt = str_replace('data-sid="' . $tr['smpp'] . '"', 'data-sid="' . $tr['smpp'] . '" selected ', $saved_smppopt);
                                                                        ?>
                                                                            <tr>
                                                                                <td><select name="rrsmppid[]" class="form-control input-sm"><?php echo $selectedopt ?></select></td>
                                                                                <td>
                                                                                    <div class="input-group" style="max-width: 160px;"><input value="<?php echo $tr['batchsize'] ?>" placeholder="e.g. 20000" name="rrsmppval[]" type="text" class="form-control input-sm"><span class="input-group-addon">SMS</span></div>
                                                                                </td>
                                                                                <td><button type="button" class="rmrow btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                                                                            </tr>
                                                                    <?php }
                                                                    } else {
                                                                        echo $rrsmppstr;
                                                                    } ?>
                                                                </tbody>
                                                            </table>

                                                        </fieldset>
                                                    </div>
                                                    <div id="ralgo_1" class="p-sm panel m-b-0 bg-info ralgoctrs <?php if ($route_config['mode'] != 1) { ?> hidden <?php } ?>">
                                                        <div class="clearfix sepH_b m-b-xs">
                                                            <div class="btn-group pull-right">
                                                                <a href="javascript:void(0)" id="add_persmpprow" class="btn btn-inverse btn-sm"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add SMPP') ?></a>
                                                            </div>

                                                        </div>
                                                        <fieldset>
                                                            <table class="table">
                                                                <tbody id="ralgo_1_tbody">
                                                                    <?php if ($route_config['mode'] == 1) { ?>
                                                                        <?php foreach ($route_config['smsc_list'] as $tr) {
                                                                            $selectedopt = str_replace('data-sid="' . $tr['smpp'] . '"', 'data-sid="' . $tr['smpp'] . '" selected ', $saved_smppopt);
                                                                        ?>
                                                                            <tr>
                                                                                <td><select name="persmppid[]" class="form-control input-sm"><?php echo $selectedopt ?></select></td>
                                                                                <td>
                                                                                    <div class="input-group" style="max-width: 80px;"><input value="<?php echo $tr['percent'] ?>" type="text" name="persmppval[]" class="form-control input-sm"><span class="input-group-addon">%</span></div>
                                                                                </td>
                                                                                <td><button type="button" class="rmrow btn btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
                                                                            </tr>
                                                                    <?php }
                                                                    } else {
                                                                        echo $persmppstr;
                                                                    } ?>
                                                                </tbody>
                                                            </table>

                                                        </fieldset>
                                                    </div>
                                                    <div id="ralgo_0" class="p-sm panel m-b-0 bg-info ralgoctrs <?php if ($route_config['mode'] != 0) { ?> hidden <?php } ?>">
                                                        <fieldset>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Primary SMPP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <select id="prismpp" name="prismpp" class="form-control input-sm" data-plugin="select2">
                                                                                <option value="0"><?php echo SCTEXT('Select One') ?></option>
                                                                                <?php if ($route_config['mode'] == 0) { ?>
                                                                                    <?php if ($route_config['primary_smsc_type'] != 'http') { ?>
                                                                                        <optgroup label="SMPP Accounts"></optgroup>
                                                                                        <?php echo str_replace('data-sid="' . $route_config['primary_smsc'] . '"', 'data-sid="' . $route_config['primary_smsc'] . '" selected ', $saved_smppopt); ?>
                                                                                        <optgroup label="HTTP API Vendors"></optgroup>
                                                                                        <?php echo $vapiopt ?>
                                                                                    <?php } else { ?>
                                                                                        <optgroup label="SMPP Accounts"></optgroup>
                                                                                        <?php echo $smppopt ?>
                                                                                        <optgroup label="HTTP API Vendors"></optgroup>
                                                                                        <?php echo str_replace('data-sid="' . $route_config['primary_smsc'] . '"', 'data-sid="' . $route_config['primary_smsc'] . '" selected ', $saved_vapiopt); ?>
                                                                                    <?php } ?>


                                                                                <?php } else {
                                                                                    echo $smppopt;
                                                                                } ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Backup SMPP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <select id="bkpsmpp" name="bkpsmpp" class="form-control" data-plugin="select2">
                                                                                <option><?php echo SCTEXT('Select One') ?></option>
                                                                                <?php if ($route_config['mode'] == 0) { ?>
                                                                                    <?php if ($route_config['backup_smsc_type'] != 'http') { ?>
                                                                                        <optgroup label="SMPP Accounts"></optgroup>
                                                                                        <?php echo str_replace('data-sid="' . $route_config['backup_smsc'] . '"', 'data-sid="' . $route_config['backup_smsc'] . '" selected ', $saved_smppopt); ?>
                                                                                        <optgroup label="HTTP API Vendors"></optgroup>
                                                                                        <?php echo $vapiopt ?>
                                                                                    <?php } else { ?>
                                                                                        <optgroup label="SMPP Accounts"></optgroup>
                                                                                        <?php echo $smppopt ?>
                                                                                        <optgroup label="HTTP API Vendors"></optgroup>
                                                                                        <?php echo str_replace('data-sid="' . $route_config['backup_smsc'] . '"', 'data-sid="' . $route_config['backup_smsc'] . '" selected ', $saved_vapiopt); ?>
                                                                                    <?php } ?>


                                                                                <?php } else {
                                                                                    echo $smppopt;
                                                                                } ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Switch to Backup</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="checkbox checkbox-primary">
                                                                                <input id="bkrule-1" name="bkrule" <?php if ($route_config['switch_rule']['main_down'] == 1) { ?> checked="checked" <?php } ?> type="checkbox" value="1">
                                                                                <label for="bkrule-1">When Primary is Offline on Kannel</label>
                                                                            </div>

                                                                            <div class="radio radio-primary">
                                                                                <input id="bkrule-4" name="bkruleo" <?php if ($route_config['switch_rule']['fail_switch'] == 0) { ?> checked="checked" <?php } ?> type="radio" value="0">
                                                                                <label for="bkrule-4"> No Auto Switch by Failure rate</label>
                                                                            </div>
                                                                            <div class="radio radio-primary">
                                                                                <input id="bkrule-2" name="bkruleo" <?php if ($route_config['switch_rule']['fail_switch'] == 90) { ?> checked="checked" <?php } ?> type="radio" value="90">
                                                                                <label for="bkrule-2"> more than <b>90%</b> SMS Fail per minute</label>
                                                                            </div>
                                                                            <div class="radio radio-primary">
                                                                                <input id="bkrule-3" name="bkruleo" <?php if ($route_config['switch_rule']['fail_switch'] == 50) { ?> checked="checked" <?php } ?> type="radio" value="50">
                                                                                <label for="bkrule-3"> more than <b>50%</b> SMS Fail per minute</label>
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID Type') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-primary">
                                                        <input id="sid1" <?php if ($data['rdata']->sender_type == 0) { ?> checked <?php } ?> value="0" type="radio" name="sid_type">
                                                        <label for="radio-primary"><?php echo SCTEXT('Approval based') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('Users will have to get sender ID approved by Admin or Staff before they can use it to send SMS') ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="sid2" <?php if ($data['rdata']->sender_type == 1) { ?> checked <?php } ?> value="1" type="radio" name="sid_type">
                                                        <label for="radio-primary"><?php echo SCTEXT('Auto-generate') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT("Users will not have any option to define sender ID. It will be auto-generated by SMPP system. Choose this if SMPP vendor doesn't support Sender ID.") ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="sid3" <?php if ($data['rdata']->sender_type == 2) { ?> checked <?php } ?> value="2" type="radio" name="sid_type">
                                                        <label for="radio-primary"><?php echo SCTEXT('Open Sender') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('Users can use any sender ID without Admin approval. On send sms page they will have a text-box to enter any sender ID they want to use for their campaign.') ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="defsid" id="defsid" class="form-control" maxlength="10" placeholder="e.g. WEBSMS" value="<?php echo $data['rdata']->def_sender ?>" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Max. Sender length') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="input-group bootstrap-touchspin">
                                                        <input id="sidlen" value="<?php echo $data['rdata']->max_sid_len ?>" data-plugin="TouchSpin" data-options="{buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', min: 4, max: 20}" class="form-control" style="display: block;" name="sidlen" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Allowed Text') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="temp1" <?php if ($data['rdata']->template_flag == 0) { ?> checked <?php } ?> type="radio" name="tmpflag" value="0">
                                                        <label for="temp1"><?php echo SCTEXT('Send Any text') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="temp2" <?php if ($data['rdata']->template_flag == 1) { ?> checked <?php } ?> type="radio" name="tmpflag" value="1">
                                                        <label for="temp2"><?php echo SCTEXT('Approved Templates only') ?></label>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Select Country') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="cov" name="cov" class="form-control" data-plugin="select2">
                                                        <?php foreach ($data['cvdata'] as $cv) { ?>
                                                            <option <?php if ($data['rdata']->country_id == $cv->id) { ?> selected <?php } ?> data-tz="<?php echo $cv->timezone ?>" value="<?php echo $cv->id ?>"><?php echo $cv->country . ' ( +' . $cv->prefix . ' )' ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group <?php if ($data['rdata']->country_id == 0) { ?> disabledBox <?php } ?>" id="pfx-toggle">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Add Prefix') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="form-control" <?php if ($data['rdata']->add_pre == '0') { ?> checked="checked" <?php } ?> type="radio" name="add_pre" value="0">
                                                        <label for="temp1"><?php echo SCTEXT('No') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="form-control" <?php if ($data['rdata']->add_pre == '1') { ?> checked="checked" <?php } ?> type="radio" name="add_pre" value="1">
                                                        <label for="acttype"><?php echo SCTEXT('Yes') ?></label>
                                                    </div>
                                                    <span class="help-block"><?php echo SCTEXT('Setting this to YES will force system to add country prefix to the contact numbers before sending them. Contacts already having prefix will be unaffected. Select this YES if your SMPP only sends SMS with country prefix.') ?></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Active Time') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="acttype" <?php if ($actdata['type'] == 0) { ?> checked <?php } ?> type="radio" name="acttype" value="0">
                                                        <label for="temp1"><?php echo SCTEXT('Always Active') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input <?php if ($actdata['type'] == 1) { ?> checked <?php } ?> class="acttype" type="radio" name="acttype" value="1">
                                                        <label for="acttype"><?php echo SCTEXT('Specific time') ?></label>
                                                    </div><br>
                                                    <div id="spectime" <?php if ($actdata['type'] == 1) { ?> style="display:block !important;" <?php } ?> class="panel panel-info panel-custom">
                                                        <label for="actfrom"><?php echo SCTEXT('Active From') ?>:</label>
                                                        <div class="input-group bootstrap-timepicker timepicker">
                                                            <input value="<?php echo $actdata['from'] ?>" id="actfrom" name="actfrom" type="text" class="form-control input-small" data-plugin="timepicker" data-options="{ showInputs: false, showMeridian: false }"> <span class="input-group-addon bg-info"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div><br>
                                                        <label for="actto"><?php echo SCTEXT('Until') ?>:</label>
                                                        <div class="input-group bootstrap-timepicker timepicker">
                                                            <input value="<?php echo $actdata['to'] ?>" id="actto" name="actto" type="text" class="form-control input-small" data-plugin="timepicker" data-options="{ showInputs: false, showMeridian: false }"> <span class="input-group-addon bg-info"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div><br>
                                                        <label for="acttz"><?php echo SCTEXT('Timezone') ?>:</label>
                                                        <div class="">
                                                            <input id="acttz" name="acttz" value="<?php echo $actdata['timezone'] ?>" readonly type="text" class="form-control input-small">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Blacklist Filter') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="bldb" name="bldb[]" class="form-control" data-plugin="select2" multiple data-placeholder="<?php echo SCTEXT('Select blacklist tables') ?>. . .">
                                                        <?php foreach ($data['bldb'] as $tb) { ?>
                                                            <option <?php if (in_array($tb->id, $bldbs)) { ?> selected <?php } ?> value="<?php echo $tb->id ?>"><?php echo $tb->table_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Credit Count Rule') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="ccrule" name="ccrule" class="form-control" data-plugin="select2">
                                                        <?php foreach ($data['ccrule'] as $cr) { ?>
                                                            <option <?php if ($data['rdata']->credit_rule == $cr->id) { ?> selected <?php } ?> value="<?php echo $cr->id ?>"><?php echo $cr->rule_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Opt Out Message') ?>:</label>
                                                <div class="col-md-8">
                                                    <textarea rows="2" style="min-height: 55px;" name="optoutmsg" class="form-control" placeholder="e.g. Send STOP to 12345 to Unsubscribe... "><?php echo $data['rdata']->optout_config ?></textarea>
                                                    <span class="help-block m-b-0"><?php echo SCTEXT('Enter a default Opt-out message to be included in SMS text. Leave empty to disable.') ?></span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <hr>

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