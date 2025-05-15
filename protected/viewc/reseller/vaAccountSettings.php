<style>
    .panel-body {
        min-height: 150px;
    }
</style>
<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix"><?php echo SCTEXT('View Account') ?><small><?php echo $data['user']->name . ' (' . $data['user']->email . ')' ?></small>
                                <input type="hidden" id="userid" value="<?php echo $data['user']->user_id ?>" />
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
                            <!-- start content -->
                            <div class="col-md-12 clearfix">
                                <div class="col-md-6 p-sm">
                                    <h4><?php echo SCTEXT('Account Permissions') ?></h4>
                                    <hr>
                                    <form id="upermform" method="post">
                                        <input type="hidden" name="uid" value="<?php echo $data['user']->user_id ?>" />
                                        <div class="panel panel-info panel-custom">
                                            <div class="panel-body">

                                                <select id="pgid" name="pgid" class="form-control" data-plugin="select2">
                                                    <?php foreach ($data['pgroups'] as $pgroup) { ?>
                                                        <option <?php if ($data['uperm']->pg_id == $pgroup->id) { ?> selected <?php } ?> value="<?php echo $pgroup->id ?>"><?php echo $pgroup->title ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="panel-footer text-center">
                                                <button id="upermsubmit" type="button" class="btn btn-primary"><i class="fa fa-check m-r-xs"></i><?php echo SCTEXT('Save Permissions') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 p-sm">
                                    <h4><?php echo SCTEXT('Special Privileges') ?></h4>
                                    <hr>
                                    <form id="uflagform" method="post">
                                        <input type="hidden" name="uid" value="<?php echo $data['user']->user_id ?>" />
                                        <div class="panel panel-custom panel-info">
                                            <div class="panel-body" style="min-height:150px;">
                                                <div class="m-b-xs m-r-xl">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input type="radio" id="upay_0" name="upay" <?php if ($data['user']->payment_perm == 0) { ?> checked="checked" <?php } ?> value="0">
                                                        <label for="upay_0">Online Payments</label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input type="radio" id="upay_1" name="upay" <?php if ($data['user']->payment_perm == 1) { ?> checked="checked" <?php } ?> value="1">
                                                        <label for="upay_1">Offline Payments</label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input type="radio" id="upay_2" name="upay" <?php if ($data['user']->payment_perm == 2) { ?> checked="checked" <?php } ?> value="2">
                                                        <label for="upay_2">Both</label>
                                                    </div>

                                                </div>
                                                <?php $deftax = unserialize($data['user']->default_tax); ?>
                                                <div class="col-md-8 input-group m-b-sm">
                                                    <span class="input-group-addon">Default Tax</span>
                                                    <input type="text" name="dtax" id="dtax" class="form-control input-sm" placeholder="e.g. 14.5" value="<?php echo $deftax && $deftax['tax'] > 0 ? number_format($deftax['tax'], 2) : "" ?>" maxlength="50">
                                                    <span class="input-group-addon">%</span>
                                                    <select class="form-control input-sm" name="dtaxtype">
                                                        <option <?php echo $deftax && $deftax['type'] == "" ? 'selected' : "" ?> value="">None</option>
                                                        <option <?php echo $deftax && $deftax['type'] == "GT" ? 'selected' : "" ?> value="GT">GST</option>
                                                        <option <?php echo $deftax && $deftax['type'] == "VT" ? 'selected' : "" ?> value="VT">VAT</option>
                                                        <option <?php echo $deftax && $deftax['type'] == "ST" ? 'selected' : "" ?> value="ST">Service Tax</option>
                                                        <option <?php echo $deftax && $deftax['type'] == "SC" ? 'selected' : "" ?> value="SC">Service Charge</option>
                                                        <option <?php echo $deftax && $deftax['type'] == "OT" ? 'selected' : "" ?> value="OT">Other Taxes</option>
                                                    </select>

                                                </div>

                                                <div class="m-b-xs m-r-xl">
                                                    <input data-size="small" name="cus_tlv_flag" id="cus_tlv_flag" type="checkbox" data-switchery data-color="#35b8e0" <?php if (is_array($data['customTlvLabels']) && sizeof($data['customTlvLabels']) > 0) { ?> checked="checked" <?php } ?>>
                                                    <label class="m-l-xs"><?php echo SCTEXT('Use Custom TLV Labels') ?></label>
                                                </div>
                                                <div class="m-b-xs" id="cus_tlv_ctr" style="<?php if (is_array($data['customTlvLabels']) && sizeof($data['customTlvLabels']) > 0) {
                                                                                                echo 'display:block';
                                                                                            } else {
                                                                                                echo 'display:none;';
                                                                                            } ?>">
                                                    <?php if (is_array($data['allUserTlvs']) && sizeof($data['allUserTlvs']) > 0) { ?>
                                                        <hr>
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>TLV Type</th>
                                                                    <th>Custom Label</th>
                                                                    <th>Default Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($data['allUserTlvs'] as $systemtlv) {
                                                                    $defaults = explode("|", $data['customTlvLabels'][$systemtlv]);
                                                                ?>
                                                                    <tr>
                                                                        <td><kbd><?php echo $systemtlv ?></kbd></td>
                                                                        <td><input class="form-control input-sm" name="customlbl[<?php echo $systemtlv ?>]" value="<?php echo $defaults[0] ?>"> </td>
                                                                        <td><input class="form-control input-sm" name="customval[<?php echo $systemtlv ?>]" value="<?php echo $defaults[1] ?>"> </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } else { ?>
                                                        <hr>
                                                        <h5>No TLV supported by assigned Routes.</h5>
                                                        <hr>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="panel-footer text-center">
                                                <button id="uflagbtn" type="button" class="btn btn-primary"><i class="fa fa-check m-r-xs"></i><?php echo SCTEXT('Save Changes') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>





                            <div class="col-md-12 clearfix">

                                <div class="col-md-6 p-sm">
                                    <h4><?php echo SCTEXT('Whitelist Contacts') ?></h4>
                                    <hr>
                                    <form id="uwconform" method="post">
                                        <input type="hidden" name="uid" value="<?php echo $data['user']->user_id ?>" />
                                        <div class="panel panel-custom panel-info">
                                            <div class="panel-body">
                                                <input type="text" class="form-control" data-plugin="tagsinput" data-options="{maxChars: 12, trimValue: true}" placeholder="e.g.9887xxxxx,9001xxxxx ..." name="wcontacts" value="<?php echo $data['wcon']->mobiles ?>">
                                                <span class="help-block m-b-0"><?php echo SCTEXT('Enter mobile numbers separated by comma') ?></span>
                                            </div>
                                            <div class="panel-footer text-center">
                                                <button id="uwconbtn" type="button" class="btn btn-primary"><i class="fa fa-check m-r-xs"></i><?php echo SCTEXT('Save Changes') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6 p-sm">
                                    <h4><?php echo SCTEXT('HLR Settings') ?></h4>
                                    <hr>
                                    <form id="hlrsetform" method="POST">
                                        <input type="hidden" name="userid" value="<?php echo $data['user']->user_id ?>">
                                        <div class="panel panel-custom panel-info">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="hlrc">HLR Channel:</label>
                                                    <select class="form-control" id="hlrc" name="hlrchannel" data-plugin="select2">
                                                        <option value="0">- No Channel Assigned -</option>
                                                        <?php foreach ($data['channels'] as $ch) { ?>
                                                            <option <?php if ($data['hlrinfo']->channel_id == $ch->id) { ?> selected <?php } ?> value="<?php echo $ch->id ?>"><?php echo $ch->channel_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php if ($data['user']->account_type == 0) { ?>
                                                    <div class="form-group">
                                                        <label for="hlrcre">HLR Credits:</label>
                                                        <input value="<?php echo $data['hlrinfo']->credits_cost ?>" type="number" class="form-control" name="hlrcredits" id="hlrcre" placeholder="enter lookup credits . . .">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="form-group">
                                                        <label for="hlrcre">Lookup Cost:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                            <input value="<?php echo $data['hlrinfo']->credits_cost ?>" type="text" class="form-control" name="hlrcredits" id="hlrcre" placeholder="enter lookup price e.g. 0.08">
                                                        </div>

                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="panel-footer text-center">
                                                <button type="button" id="save_hlrset" class="btn btn-primary btn-md">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- phonebook settings -->
                            <div class="col-md-12">
                                <h4><?php echo SCTEXT('Phonebook Permissions') ?></h4>
                                <hr>
                                <form id="pbdbsetform" method="post">
                                    <input type="hidden" name="uid" value="<?php echo $data['user']->user_id ?>" />
                                    <div class="panel panel-info panel-custom">
                                        <div class="panel-body">
                                            <div class="clearfix">
                                                <div class="col-md-4 splanft-item">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Assigned Phonebooks') ?></h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <?php $assigned_grps = explode(",", $data['upbdb']->phonebook_ids);
                                                            $pattern = unserialize($data['upbdb']->mask_pattern);
                                                            ?>
                                                            <select id="pbdbsel" class="form-control" data-plugin="select2" name="groups[]" multiple data-options="{placeholder: '<?php echo SCTEXT('Select Groups') ?>. . . .'}">
                                                                <?php foreach ($data['pbdata'] as $grp) { ?>
                                                                    <option <?php if (in_array($grp->id, $assigned_grps)) { ?> selected <?php } ?> value="<?php echo $grp->id ?>"><?php echo $grp->group_name . ' (' . number_format($grp->contact_count) . ' Contacts)' ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4 splanft-item">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Allow Click Tracking') ?></h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div class="radio radio-primary">
                                                                <input <?php if ($data['upbdb']->click_track == 1) { ?> checked <?php } ?> id="ct1" value="1" type="radio" name="click_track">
                                                                <label for="ct1"><?php echo SCTEXT('Yes') ?></label>
                                                                <span class="help-block"><?php echo SCTEXT('User will be able to see mobile numbers who clicked the link in the campaign.') ?></span>
                                                            </div>
                                                            <div class="radio radio-primary">
                                                                <input <?php if ($data['upbdb']->click_track == 0) { ?> checked <?php } ?> id="ct2" value="0" type="radio" name="click_track">
                                                                <label for="ct2"><?php echo SCTEXT('No') ?></label>
                                                                <span class="help-block"><?php echo SCTEXT('Phonebook numbers will be hidden from the user irrespective of the click response.') ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4 splanft-item">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Number Masking') ?></h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div class="radio radio-primary">
                                                                <input <?php if ($pattern['type'] == 1) { ?> checked <?php } ?> id="nm1" value="1" type="radio" name="mask_pattern">
                                                                <label for="nm1"><?php echo SCTEXT('Default Format') ?></label>
                                                                <span class="help-block text-primary">e.g. 9715003xxxx2</span>
                                                            </div>
                                                            <div class="radio radio-primary">
                                                                <input <?php if ($pattern['type'] == 0) { ?> checked <?php } ?> id="nm2" value="0" type="radio" name="mask_pattern">
                                                                <label for="nm2"><?php echo SCTEXT('Custom Format') ?></label>
                                                                <?php if ($pattern['type'] == 0) { ?>
                                                                    <span class="help-block  text-dark">
                                                                        <span class="block input-group">
                                                                            <span class="input-group-addon"><?php echo SCTEXT('Mask Position') ?></span>
                                                                            <input id="maskstart" name="maskstart" type="number" min="-12" class="form-control input-sm" value="<?php echo $pattern['mpos'] ?>" placeholder="e.g. -3">
                                                                        </span>
                                                                        <span class="block input-group">
                                                                            <span class="input-group-addon"><?php echo SCTEXT('Mask Count') ?></span>
                                                                            <input id="masklen" name="masklen" type="number" min="1" max="11" value="<?php echo $pattern['mlen'] ?>" class="form-control input-sm" placeholder="e.g. 5">
                                                                        </span>
                                                                        <span class="text-primary block">e.g. <span id="egmask"><?php echo substr_replace('971500301012', str_repeat('x', intval($pattern['mlen'])), intval($pattern['mpos']), intval($pattern['mlen'])) ?></span></span>
                                                                    </span>
                                                                <?php } else { //default display if no settings exist 
                                                                ?>
                                                                    <span class="help-block  text-dark">
                                                                        <span class="block input-group">
                                                                            <span class="input-group-addon"><?php echo SCTEXT('Mask Position') ?></span>
                                                                            <input id="maskstart" name="maskstart" type="number" min="-12" class="form-control input-sm" value="3" placeholder="e.g. -3">
                                                                        </span>
                                                                        <span class="block input-group">
                                                                            <span class="input-group-addon"><?php echo SCTEXT('Mask Count') ?></span>
                                                                            <input id="masklen" name="masklen" type="number" min="1" max="11" value="4" class="form-control input-sm" placeholder="e.g. 5">
                                                                        </span>
                                                                        <span class="text-primary block">e.g. <span id="egmask">971xxxx01012</span></span>
                                                                    </span>

                                                                <?php } ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="panel-footer text-center">
                                            <button id="pldbsetsubmit" type="button" class="btn btn-primary"><i class="fa fa-check m-r-xs"></i><?php echo SCTEXT('Save Settings') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <!-- 2-way settings -->
                            <div class="col-md-12">
                                <h4><?php echo SCTEXT('2-Way SMS Settings') ?></h4>
                                <hr>
                                <form id="usrvmnsetform" method="post" action="">
                                    <input type="hidden" name="uid" value="<?php echo $data['user']->user_id ?>" />
                                    <div class="panel panel-info panel-custom">
                                        <div class="panel-body">
                                            <div class="clearfix">
                                                <div class="col-md-6 splanft-item">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Assign Dedicated VMN') ?></h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <span class="help-block text-primary">
                                                                <?php echo SCTEXT('Assigning dedicated VMN allows user to add their own keywords for the VMN. Assign only keywords if you want a VMN to be shared among many users. Missedcall numbers need to be assigned as dedicated VMN, they cannot be shared.') ?>
                                                            </span>
                                                            <select class="form-control" data-plugin="select2" name="usrvmn[]" multiple data-options="{templateResult: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var typestr = data.element.dataset.vmntype==0?'<span class=\'label label-success\'>Shortcode</span>':(data.element.dataset.vmntype==1?'<span class=\'label label-primary\'>Longcode</span>':'<span class=\'label label-danger\'>Missedcall Number</span>'); var nstr = '<div class=\'clearfix m-b-sm\'><div class=\'m-r-md pull-left\'>'+data.text+'</div><div class=\'pull-right\'>'+typestr+'</div></div>';return $(nstr);} }, templateSelection: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var typestr = data.element.dataset.vmntype==0?'<span class=\'label label-success\'>Shortcode</span>':(data.element.dataset.vmntype==1?'<span class=\'label label-primary\'>Longcode</span>':'<span class=\'label label-danger\'>Missedcall Number</span>'); var nstr = '<div class=\'clearfix m-b-sm\'><div class=\'m-r-md pull-left\'>'+data.text+'</div><div class=\'pull-right\'>'+typestr+'</div></div>';return $(nstr);} }, placeholder: '<?php echo SCTEXT('Assign VMN') ?>. . . .'}">

                                                                <?php foreach ($data['vmns'] as $vmn) {
                                                                    if ($vmn->user_assigned == 0 || $vmn->user_assigned == $data['user']->user_id) {
                                                                ?>
                                                                        <option <?php if ($vmn->user_assigned == $data['user']->user_id) { ?> selected <?php } ?> data-vmntype="<?php echo $vmn->type ?>" value="<?php echo $vmn->id ?>"><?php echo $vmn->vmn ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 splanft-item">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Assign Primary Keywords') ?></h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <select class="form-control" data-plugin="select2" name="usrkws[]" multiple data-options="{templateResult: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var typestr = data.element.dataset.vmntype==0?'<span class=\'label label-success\'>'+data.element.dataset.vmn+'</span>':'<span class=\'label label-primary\'>'+data.element.dataset.vmn+'</span>'; var nstr = '<div class=\'clearfix m-b-sm\'><div class=\'m-r-md pull-left\'>'+data.text+'</div><div class=\'pull-right\'>'+typestr+'</div></div>';return $(nstr);} }, templateSelection: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var typestr = data.element.dataset.vmntype==0?'<span class=\'label label-success\'>'+data.element.dataset.vmn+'</span>':'<span class=\'label label-primary\'>'+data.element.dataset.vmn+'</span>'; var nstr = '<div class=\'clearfix m-b-sm\'><div class=\'m-r-md pull-left\'>'+data.text+'</div><div class=\'pull-right\'>'+typestr+'</div></div>';return $(nstr);} }, placeholder: '<?php echo SCTEXT('Assign Keywords') ?>. . . .'}">

                                                                <?php foreach ($data['kws'] as $kw) {
                                                                    if ($kw->user_assigned == 0 || $kw->user_assigned == $data['user']->user_id) {
                                                                ?>
                                                                        <option <?php if ($kw->user_assigned == $data['user']->user_id) { ?> selected <?php } ?> data-vmn="<?php echo $kw->vmn ?>" data-vmntype="<?php echo $kw->type ?>" value="<?php echo $kw->id ?>"><?php echo $kw->keyword ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="panel-footer text-center">
                                            <button id="vmnsetsubmit" type="button" class="btn btn-primary"><i class="fa fa-check m-r-xs"></i><?php echo SCTEXT('Save Settings') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <!-- smpp client accounts -->
                            <div class="col-md-12">
                                <h4 class="clearfix"><?php echo SCTEXT('SMPP Client Accounts') ?>
                                    <span class="pull-right">
                                        <a class="btn btn-inverse m-b-0 btn-sm" href="<?php echo Doo::conf()->APP_URL ?>addSmppClient/<?php echo $data['user']->user_id ?>"><?php echo SCTEXT('Add SMPP Account') ?></a>
                                    </span>
                                </h4>
                                <hr>
                                <div class="">
                                    <table id="t-ssclient" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getSmppClients/<?php echo $data['user']->user_id ?>', order:[], drawCallback: function(settings){createSwitches();}, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('System ID') ?></th>
                                                <th><?php echo SCTEXT('Password') ?></th>
                                                <th><?php echo SCTEXT('Allowed IP') ?></th>
                                                <?php if ($data['user']->account_type == '0' || $data['user']->account_type == '2') { ?> <th data-priority="2"><?php echo SCTEXT('Route Associated') ?></th> <?php } ?>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th><?php echo SCTEXT('Actions') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <!-- end content -->

                        </div>
                    </div>
                </div>
            </div>

        </section>