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
                                <h4><?php echo SCTEXT('Campaign DLR Details') ?></h4>
                                <hr>


                                <?php
                                $totalsent = $data['sum']->total_contacts + $data['sum']->dropped_contacts + $data['sum']->invalid_contacts + $data['sum']->blacklist_contacts;
                                $sentper = ($data['sent'] / $totalsent) * 100;
                                $qper = ($data['qcount'] / $totalsent) * 100;
                                ?>
                                <div class="m-b-sm clearfix">
                                    <div class="col-md-8 col-sm-7 p-r-md">

                                        <div class="m-b-sm">
                                            <h4><?php echo SCTEXT('Campaign Progress') ?></h4>
                                            <hr class="m-h-sm">
                                            <div class="clearfix">
                                                <div class="col-md-9 p-t-xs">
                                                    <div class="m-b-xs progress progress-xs ">
                                                        <div class="progress-bar progress-bar-striped active progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <span class="progtxt"><?php echo SCTEXT('Total') ?>: <b><?php echo number_format($totalsent) ?></b></span>
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="col-md-9 p-t-xs">
                                                    <div class="m-b-xs progress progress-xs ">
                                                        <div class="progress-bar progress-bar-striped active progress-bar-primary" role="progressbar" aria-valuenow="<?php echo intval($sentper) ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo intval($sentper) ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <span class="progtxt"><?php echo SCTEXT('Sent') ?>: <b><?php echo number_format($data['sent']) ?></b></span>
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="col-md-9 p-t-xs">
                                                    <div class="m-b-xs progress progress-xs ">
                                                        <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar" aria-valuenow="<?php echo intval($qper) ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo intval($qper) ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <span class="progtxt"><?php echo SCTEXT('Queued') ?>: <b><?php echo number_format($data['qcount']) ?></b></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-t-md">
                                            <h4><?php echo SCTEXT('DLR Summary') ?></h4>
                                            <hr class="m-h-sm">
                                            <div id="dlrsumctr">

                                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">



                                            </div>

                                        </div>

                                    </div>
                                    <div class="p-l-md col-md-4 col-sm-5">
                                        <h4 class="clearfix"><?php echo SCTEXT('Campaign Summary') ?>

                                            <div class="pull-right dropdown btn-group">
                                                <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"> <?php echo SCTEXT('Actions') ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                                    <li>
                                                        <a href="<?php echo Doo::conf()->APP_URL . 'globalFileDownload/dlr/' . $data['sum']->sms_shoot_id ?>"><?php echo SCTEXT('Download Reports') ?></a>
                                                    </li>
                                                    <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                                        <li>
                                                            <a id="adminsummary" href="javascript:void(0);" data-toggle="modal" data-target="#adminsumbox" data-shootid="<?php echo $data['sms']->sms_shoot_id ?>"><?php echo SCTEXT('Admin Summary') ?></a>
                                                        </li>
                                                    <?php } ?>

                                                </ul>
                                            </div>

                                        </h4>

                                        <div>

                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo SCTEXT('SMS Type') ?></td>
                                                        <td class="text-right"><?php echo $data['stype'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Total SMS Submitted') ?></td>
                                                        <td class="text-right"><span class="label label-default"><?php echo $data['sum']->total_contacts ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Duplicates Removed') ?></td>
                                                        <td class="text-right"><span class="label label-danger"><?php echo $data['sum']->duplicates_removed ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Total SMS Sent') ?></td>
                                                        <td class="text-right"><span class="label label-info"><?php echo $totalsent ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('SMS Count per Contact') ?></td>
                                                        <td class="text-right"><span class="label label-pink"><?php echo $data['sum']->total_sms == 0 ? 1 : $data['sum']->total_sms ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Credits Deducted') ?></td>
                                                        <td class="text-right"><span class="label label-purple"><?php echo $data['sum']->total_cost  ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Credits Refunded') ?></td>
                                                        <td class="text-right"><span class="label label-success"><?php echo $data['reftotal'] ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- dlr table -->
                                <input type="hidden" id="shootid" value="<?php echo $data['sum']->sms_shoot_id ?>" />
                                <input type="hidden" id="routeid" value="<?php echo $data['sum']->route_id ?>" />
                                <?php if ($data['user']->account_type == 1) { ?>
                                    <div class="">
                                        <table id="t-dlrdetails" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMySentSms/<?php echo $data['sum']->sms_shoot_id ?>/<?php echo $data['user']->user_id ?>', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, columns: [null,null,null,{width:'10%'},null,null,{width:'15%'},null,null,null,null,null,null], order:[], serverSide: true, processing: true, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('Mobile') ?></th>
                                                    <th><?php echo SCTEXT('MCCMNC') ?></th>
                                                    <th><?php echo SCTEXT('Network') ?></th>
                                                    <th><?php echo SCTEXT('Region') ?></th>
                                                    <th><?php echo SCTEXT('Date') ?></th>
                                                    <th><?php echo SCTEXT('Sender ID') ?></th>
                                                    <th><?php echo SCTEXT('SMS Text') ?></th>
                                                    <th><?php echo SCTEXT('Count') ?></th>
                                                    <th><?php echo SCTEXT('Price') ?></th>
                                                    <th><?php echo SCTEXT('Msg ID') ?></th>
                                                    <th><?php echo SCTEXT('SMPP DLR') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Status') ?></th>
                                                    <th><?php echo SCTEXT('Explanation') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else { ?>
                                    <div class="">
                                        <table id="t-dlrdetails" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMySentSms/<?php echo $data['sum']->sms_shoot_id ?>/<?php echo $data['user']->user_id ?>', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, columns: [null,null,{width:'10%'},null,null,{width:'15%'},null,null,null,null,null], order:[], serverSide: true, processing: true, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('Mobile') ?></th>
                                                    <th><?php echo SCTEXT('Network') ?></th>
                                                    <th><?php echo SCTEXT('Region') ?></th>
                                                    <th><?php echo SCTEXT('Date') ?></th>
                                                    <th><?php echo SCTEXT('Sender ID') ?></th>
                                                    <th><?php echo SCTEXT('SMS Text') ?></th>
                                                    <th><?php echo SCTEXT('Count') ?></th>
                                                    <th><?php echo SCTEXT('Msg ID') ?></th>
                                                    <th><?php echo SCTEXT('SMPP DLR') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Status') ?></th>
                                                    <th><?php echo SCTEXT('Explanation') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>




                            </div>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>

                                <div class="modal fade" id="adminsumbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"><?php echo SCTEXT('Campaign Summary for Admin') ?></h4>
                                            </div>
                                            <div id="adminsumctr" class="modal-body p-lg">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo SCTEXT('Close') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                            <!-- end content -->

                        </div>
                    </div>
                </div>
            </div>

        </section>