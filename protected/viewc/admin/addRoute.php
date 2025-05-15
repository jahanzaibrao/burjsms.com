<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add New Route') ?><small><?php echo SCTEXT('add new SMS Route') ?></small></h3>
                            <hr>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="add_route_form" action="">
                                    <div class="block">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Route Title') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="title" id="rt_title" class="form-control pop-over" data-content="<?php echo SCTEXT('This title will be visible to all those users who will use this route to send SMS') ?>" placeholder="<?php echo SCTEXT('enter a title for this route') ?>" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Routing Logic') ?>:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="ralgo" id="ralgo" data-plugin="select2">
                                                        <option value="0">Dedicated Routing</option>
                                                        <option value="1">Percent Distribution</option>
                                                        <option value="2">Round Robin Allocation</option>
                                                        <option value="3">Least Cost Routing</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            //prepare option string for select field for smpp
                                            $smppopt = '';
                                            foreach ($data['smpp'] as $ch1) {
                                                $smppopt .= '<option data-smsc="smpp" value="' . $ch1->id . '">' . $ch1->title . '</option>';
                                            }
                                            //prepare vendor API channels
                                            $vapiopt = '';
                                            if (isset($data['vapi'])) {
                                                foreach ($data['vapi'] as $ch12) {
                                                    $vapiopt .= '<option data-smsc="http" value="' . $ch12->id . '">' . $ch12->title . '</option>';
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
                                            <input type="hidden" id="pritype" name="pritype" value="smpp">
                                            <input type="hidden" id="bkptype" name="bkptype" value="smpp">

                                            <div class="form-group">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-8">
                                                    <div id="ralgo_3" class="p-sm panel m-b-0 bg-info ralgoctrs hidden">
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
                                                                    <?php echo $lcrsmppstr ?>
                                                                </tbody>
                                                            </table>

                                                        </fieldset>
                                                    </div>
                                                    <div id="ralgo_2" class="p-sm panel m-b-0 bg-info ralgoctrs hidden">
                                                        <div class="clearfix sepH_b m-b-xs">
                                                            <div class="btn-group pull-right">
                                                                <a href="javascript:void(0)" id="add_rrsmpprow" class="btn btn-inverse btn-sm"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add SMPP') ?></a>
                                                            </div>

                                                        </div>
                                                        <fieldset>
                                                            <table class="table">
                                                                <tbody id="ralgo_2_tbody">
                                                                    <?php echo $rrsmppstr ?>
                                                                </tbody>
                                                            </table>

                                                        </fieldset>
                                                    </div>
                                                    <div id="ralgo_1" class="p-sm panel m-b-0 bg-info ralgoctrs hidden">
                                                        <div class="clearfix sepH_b m-b-xs">
                                                            <div class="btn-group pull-right">
                                                                <a href="javascript:void(0)" id="add_persmpprow" class="btn btn-inverse btn-sm"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add SMPP') ?></a>
                                                            </div>

                                                        </div>
                                                        <fieldset>
                                                            <table class="table">
                                                                <tbody id="ralgo_1_tbody">
                                                                    <?php echo $persmppstr ?>
                                                                </tbody>
                                                            </table>

                                                        </fieldset>
                                                    </div>
                                                    <div id="ralgo_0" class="p-sm panel m-b-0 bg-info ralgoctrs">
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
                                                                            <select id="prismpp" name="prismpp" class="form-control input-sm smscsel" data-plugin="select2">
                                                                                <option value="0"><?php echo SCTEXT('Select One') ?></option>
                                                                                <optgroup label="SMPP Accounts"></optgroup>
                                                                                <?php echo $smppopt ?>
                                                                                <?php if ($vapiopt != '') { ?>
                                                                                    <optgroup label="HTTP API Vendors"></optgroup>
                                                                                    <?php echo $vapiopt ?>
                                                                                <?php } ?>
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
                                                                            <select id="bkpsmpp" name="bkpsmpp" class="form-control smscsel" data-plugin="select2">
                                                                                <option><?php echo SCTEXT('Select One') ?></option>
                                                                                <optgroup label="SMPP Accounts"></optgroup>
                                                                                <?php echo $smppopt ?>
                                                                                <?php if ($vapiopt != '') { ?>
                                                                                    <optgroup label="HTTP API Vendors"></optgroup>
                                                                                    <?php echo $vapiopt ?>
                                                                                <?php } ?>
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
                                                                                <input id="bkrule-1" name="bkrule" checked="checked" type="checkbox" value="1">
                                                                                <label for="bkrule-1">When Primary SMPP is disabled</label>
                                                                            </div>

                                                                            <div class="radio radio-primary">
                                                                                <input id="bkrule-4" name="bkruleo" checked="checked" type="radio" value="0">
                                                                                <label for="bkrule-4"> No Auto Switch by Failure rate</label>
                                                                            </div>
                                                                            <div class="radio radio-primary">
                                                                                <input id="bkrule-2" name="bkruleo" type="radio" value="90">
                                                                                <label for="bkrule-2"> more than <b>90%</b> SMS Fail per minute</label>
                                                                            </div>
                                                                            <div class="radio radio-primary">
                                                                                <input id="bkrule-3" name="bkruleo" type="radio" value="50">
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
                                                        <input id="sid1" checked="checked" value="0" type="radio" name="sid_type">
                                                        <label for="sid1"><?php echo SCTEXT('Approval based') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('Users will have to get sender ID approved by Admin or Staff before they can use it to send SMS') ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="sid2" value="1" type="radio" name="sid_type">
                                                        <label for="sid2"><?php echo SCTEXT('Auto-generate') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT("Users will not have any option to define sender ID. It will be auto-generated by SMPP system. Choose this if SMPP vendor doesn't support Sender ID.") ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="sid3" value="2" type="radio" name="sid_type">
                                                        <label for="sid3"><?php echo SCTEXT('Open Sender') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('Users can use any sender ID without Admin approval. On send sms page they will have a text-box to enter any sender ID they want to use for their campaign.') ?></span>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>


                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="defsid" id="defsid" class="form-control" maxlength="10" placeholder="e.g. WEBSMS" value="WEBSMS" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Max. Sender length') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="input-group bootstrap-touchspin">
                                                        <input id="sidlen" value="6" data-plugin="TouchSpin" data-options="{buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', min: 4, max: 20}" class="form-control" style="display: block;" name="sidlen" type="text">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Allowed Text') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="temp1" checked="checked" type="radio" name="tmpflag" value="0">
                                                        <label for="temp1"><?php echo SCTEXT('Send Any text') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="temp2" type="radio" name="tmpflag" value="1">
                                                        <label for="temp2"><?php echo SCTEXT('Approved Templates only') ?></label>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Coverage Country') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="cov" name="cov" class="form-control" data-plugin="select2">
                                                        <?php foreach ($data['cvdata'] as $cv) { ?>
                                                            <option data-tz="<?php echo $cv->timezone ?>" value="<?php echo $cv->id ?>"><?php echo $cv->country . ' ( +' . $cv->prefix . ' )' ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group disabledBox" id="pfx-toggle">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Add Prefix') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="form-control" checked="checked" type="radio" name="add_pre" value="0">
                                                        <label for="temp1"><?php echo SCTEXT('No') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="form-control" type="radio" name="add_pre" value="1">
                                                        <label for="acttype"><?php echo SCTEXT('Yes') ?></label>
                                                    </div>
                                                    <span class="help-block"><?php echo SCTEXT('Setting this to YES will force system to add country prefix to the contact numbers before sending them. Contacts already having prefix will be unaffected. Select this YES if your SMPP only sends SMS with country prefix.') ?></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Active Time') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="acttype" checked="checked" type="radio" name="acttype" value="0">
                                                        <label for="temp1"><?php echo SCTEXT('Always Active') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input class="acttype" type="radio" name="acttype" value="1">
                                                        <label for="acttype"><?php echo SCTEXT('Specific time') ?></label>
                                                    </div><br>
                                                    <div id="spectime" class="panel panel-info panel-custom">
                                                        <label for="actfrom"><?php echo SCTEXT('Active From') ?>:</label>
                                                        <div class="input-group bootstrap-timepicker timepicker">
                                                            <input id="actfrom" name="actfrom" type="text" class="form-control input-small" data-plugin="timepicker" data-options="{ showInputs: false, showMeridian: false }"> <span class="input-group-addon bg-info"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div><br>
                                                        <label for="actto"><?php echo SCTEXT('Until') ?>:</label>
                                                        <div class="input-group bootstrap-timepicker timepicker">
                                                            <input id="actto" name="actto" type="text" class="form-control input-small" data-plugin="timepicker" data-options="{ showInputs: false, showMeridian: false }"> <span class="input-group-addon bg-info"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div><br>
                                                        <label for="acttz"><?php echo SCTEXT('Timezone') ?>:</label>
                                                        <div class="">
                                                            <input id="acttz" name="acttz" readonly type="text" class="form-control input-small">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Blacklist Filter') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="bldb" name="bldb[]" class="form-control" data-plugin="select2" multiple data-placeholder="<?php echo SCTEXT('Select blacklist tables') ?>. . .">
                                                        <?php foreach ($data['bldb'] as $tb) { ?>
                                                            <option value="<?php echo $tb->id ?>"><?php echo $tb->table_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Credit Count Rule') ?>:</label>
                                                <div class="col-md-8">
                                                    <select id="ccrule" name="ccrule" class="form-control" data-plugin="select2">
                                                        <?php foreach ($data['ccrule'] as $cr) { ?>
                                                            <option value="<?php echo $cr->id ?>"><?php echo $cr->rule_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Opt Out Message') ?>:</label>
                                                <div class="col-md-8">
                                                    <textarea rows="2" style="min-height: 55px;" name="optoutmsg" class="form-control" placeholder="e.g. Send STOP to 12345 to Unsubscribe... "></textarea>
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