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
                            <div class="col-md-12">
                                <div class="col-md-5 p-r-sm m-t-xs">
                                    <div class="media-group-item p-t-0">

                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-xlg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $data['user']->avatar; ?>" alt="User Img"></a></div>
                                            </div>
                                            <div class="media-body p-t-xs">
                                                <h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs theme-color"><?php echo $data['user']->name ?> </a></h5>
                                                <p class="m-b-0" style="font-size: 12px;font-style: Italic;"><i class="fa fa-lg fa-phone m-r-xs"></i> <?php echo $data['user']->mobile; ?></p>
                                                <p class="m-b-xs" style="font-size: 12px;font-style: Italic;"><i class="fa fa-lg fa-envelope m-r-xs"></i> <?php echo $data['user']->email; ?></p>
                                                <span class="m-b-sm label label-info label-sm"><?php echo strtoupper($data['user']->category) . ' ACCOUNT' ?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <table class="m-t-sm table wd100">
                                        <tbody>
                                            <tr>
                                                <th><?php echo SCTEXT('Login ID') ?>:</th>
                                                <td><?php echo $data['user']->login_id ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo SCTEXT('Member Since') ?>:</th>
                                                <td><?php echo date(Doo::conf()->date_format_long_time, strtotime($data['user']->registered_on)) ?></td>
                                            </tr>
                                            <?php if ($data['user']->category == 'reseller') { ?>
                                                <tr>
                                                    <th><?php echo SCTEXT('Website Status') ?>:</th>
                                                    <td>
                                                        <input id="ws_toggle" data-size="medium" name="wstatus" type="checkbox" data-switchery data-color="#10c469" <?php if ($data['wstatus']->status == '1') { ?> checked="checked" <?php } ?>>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            <?php if ($_SESSION['user']['subgroup'] == 'admin' && $data['user']->upline_id == '1') { ?>
                                                <tr>
                                                    <th><?php echo SCTEXT('Account Manager') ?>:</th>
                                                    <td>
                                                        <select id="u_acc_mgr" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                                                            <option value="1" title="<?php echo $_SESSION['user']['name'] ?>"><?php echo $_SESSION['user']['avatar'] . '|' . $_SESSION['user']['email'] . '|primary|Administrator' ?></option>
                                                            <?php foreach ($data['staff'] as $staff) { ?>
                                                                <option <?php if ($data['user']->acc_mgr_id == $staff['uid']) { ?> selected <?php } ?> value="<?php echo $staff['uid'] ?>" title="<?php echo $staff['name'] ?>"><?php echo $staff['avatar'] . '|' . $staff['email'] . '|' . $staff['theme'] . '|' . $staff['teamname'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo SCTEXT('WhatsApp Business') ?>:</th>
                                                    <td>
                                                        <div style="box-shadow: 3px 5px 13px 0 hsl(0, 0%, 90%); padding: 2% 5% 5% 5%;">
                                                            <h5>Agent</h5>
                                                            <select id="u_waba" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){ if(data.text == '' || data.text == '- Select WhatsApp Business Agent -') return data.text; var myarr = data.text.split('|'); var nstr = '<div class=\'row\'><div class=\'pull-left col-md-6\'><span class=\'label label-success label\'>'+data.title+'</span></div><div class=\'pull-right col-md-6\'><div class=\'media-right\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[2]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' \'>'+myarr[0]+'</div><small class=\'text-inverse\'>'+myarr[1]+'</small></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){if(data.text == '' || data.text == '- Select WhatsApp Business Agent -') return data.text;var myarr = data.text.split('|'); var nstr = '<div class=\'row\'><div class=\'pull-left col-md-6\'><span class=\'label label-success label-md\'>'+data.title+'</span></div><div class=\'pull-right col-md-6 text-right\'><div class=\'media-left pull-right\'><div class=\'avatar avatar-xs avatar-circle m-r-xs\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[2]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\'m-v-sm\'>'+myarr[0]+'</div></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                                                                <option value="">- Select WhatsApp Business Agent -</option>
                                                                <?php foreach ($data['wabas'] as $waba) { ?>
                                                                    <option <?php if ($waba->user_id == $data['user']->user_id) { ?> selected <?php } ?> value="<?php echo $waba->waba_id ?>" title="<?php echo $waba->waba_name ?>"><?php echo $waba->user_id == 0 ? 'No User|-|' . Doo::conf()->APP_URL . 'global/img/no-pic.png' : $data['wusers'][$waba->user_id] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <h5>Plan</h5>
                                                            <select id="agentplan" class="form-control" data-plugin="select2">
                                                                <option value="">- Select Plan -</option>
                                                                <?php foreach ($data['wplans'] as $plan) { ?>
                                                                    <option <?php if ($data['agent_plan']->plan_id == $plan->id) { ?> selected <?php } ?> value="<?php echo $plan->id ?>"><?php echo $plan->plan_name ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                    </table>

                                </div>
                                <div class="col-md-7">
                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title"><?php echo SCTEXT('SMS Traffic') ?>


                                                <div id="stdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                    <i class="fa fa-lg fa-calendar m-r-xs"></i> <span></span>&nbsp;<b class="caret"></b>
                                                </div>


                                            </h4>


                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body">
                                            <div id="usersmssummary" style="height: 350px"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <?php if ($_SESSION['user']['group'] == 'admin') { ?>
                                <div class="col-md-12 m-t-sm">
                                    <h4><?php echo SCTEXT('Activity Log') ?></h4>
                                    <hr>
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <div id="uactl-dp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>
                                        </div>
                                    </div><br />
                                    <div class="">
                                        <table id="t-uactlog" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getUserActivity/Select Date/<?php echo $data['user']->user_id ?>', order:[], serverSide: true, processing: true, drawCallback : function(s){ $('.blockipbtn').on('click',function(){ var uid = $(this).attr('data-uid');var action_id = $(this).attr('data-aid');var ip = $(this).attr('data-ip');bootbox.confirm({message: 'User will not be able to access web panel from this IP. Are you sure you want to proceed?',buttons: {cancel: {label: 'No',className: 'btn-default'},confirm: {label: 'Yes, Proceed',className: 'btn-info'}},callback: function (result) {if(result){$.ajax({url: app_url+'newBlockIpRequest',type: 'post',data: {user: uid, action: action_id, ip: ip},success: function(res){window.location.reload(false);}})}}});}) },language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('Time') ?></th>
                                                    <th><?php echo SCTEXT('Type') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Activity') ?></th>
                                                    <th><?php echo SCTEXT('IP') ?></th>
                                                    <th><?php echo SCTEXT('Platform') ?></th>
                                                    <th><?php echo SCTEXT('Actions') ?></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            <?php } ?>
                            <!-- end content -->

                        </div>
                    </div>
                </div>
            </div>

        </section>