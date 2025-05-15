<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('DLR Details') ?><small><?php echo SCTEXT('view dlr reports for each sms in the campaign') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <?php
                                $totalsent = intval($data['sum']->total_contacts) + intval($data['sum']->dropped_contacts) + intval($data['sum']->invalid_contacts) + intval($data['sum']->blacklist_contacts);
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

                                        <?php if ($data['urlresponse'] == 1) {

                                            $resp_per = ($data['urlrescount'] / $totalsent) * 100;
                                        ?>
                                            <div class="m-t-md">
                                                <h4><?php echo SCTEXT('URL Response') ?></h4>
                                                <hr class="m-h-sm">
                                                <div class="clearfix">
                                                    <div class="col-md-9 p-t-xs">
                                                        <div class="m-b-xs progress progress-xs ">
                                                            <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="<?php echo intval($resp_per) ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo intval($resp_per) ?>%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 text-right">
                                                        <span class="progtxt">Clicks: <b><?php echo number_format(intval($data['urlrescount'])) ?></b></span>
                                                    </div>
                                                </div>

                                            </div>
                                        <?php } ?>

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
                                                    <?php if ($data['urlrescount'] > 0) { ?>
                                                        <li>
                                                            <a href="<?php echo Doo::conf()->APP_URL . 'globalFileDownload/clickTracking/' . $data['sum']->sms_shoot_id ?>"><?php echo SCTEXT('Download Click Tracking Report') ?></a>
                                                        </li>
                                                    <?php } ?>
                                                    <!--
                                                        <li>
                                                            <a href="<?php echo Doo::conf()->APP_URL . 'resendCampaign/all/' . $data['sum']->sms_shoot_id ?>"><?php echo SCTEXT('Re-send to all contacts') ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="<?php echo Doo::conf()->APP_URL . 'resendCampaign/del/' . $data['sum']->sms_shoot_id ?>"><?php echo SCTEXT('Re-send to Delivered contacts') ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="<?php echo Doo::conf()->APP_URL . 'resendCampaign/fld/' . $data['sum']->sms_shoot_id ?>"><?php echo SCTEXT('Re-send to Failed contacts') ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="<?php echo Doo::conf()->APP_URL . 'resendCampaign/pen/' . $data['sum']->sms_shoot_id ?>"><?php echo SCTEXT('Re-send to Pending contacts') ?></a>
                                                        </li>
                                                        -->
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
                                                        <td class="text-right"><span class="label label-default"><?php echo intval($data['sum']->total_contacts) + intval($data['sum']->duplicates_removed) ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Duplicates Removed') ?></td>
                                                        <td class="text-right"><span class="label label-danger"><?php echo $data['sum']->duplicates_removed ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Total SMS Sent') ?></td>
                                                        <td class="text-right"><span class="label label-info"><?php echo $data['sum']->total_contacts ?></span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><?php echo SCTEXT('Credits Deducted') ?></td>
                                                        <td class="text-right"><span class="label label-purple"><?php echo $data['sum']->total_cost  ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo SCTEXT('Credits Refunded') ?></td>
                                                        <td class="text-right"><span class="label label-success"><?php echo $data['reftotal'] ?></span></td>
                                                    </tr>
                                                    <?php if ($data['sum']->contacts_label != '') { ?>
                                                        <tr>
                                                            <td><?php echo SCTEXT('Contact Group/FIle') ?></td>
                                                            <td class="text-right"><span class="label label-primary"><?php echo $data['sum']->contacts_label ?></span></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- dlr table -->
                                <input type="hidden" id="shootid" value="<?php echo $data['sum']->sms_shoot_id ?>" />
                                <input type="hidden" id="routeid" value="<?php echo $data['sum']->route_id ?>" />
                                <?php if ($_SESSION['user']['account_type'] == '0' || $_SESSION['user']['account_type'] == '2') { ?>
                                    <div class="">
                                        <table id="t-dlrdetails" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMySentSms/<?php echo $data['sum']->sms_shoot_id ?>', columns: [null,null,{width:'10%'},null,null,{width:'15%'},null,null,null,null,null], order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, ordering: false, drawCallback: function(s){$('.pop-over').each(function(){$(this).popover({html: true});})}, responsive: {breakpoints: [
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
                                                    <th><?php echo SCTEXT('Cost') ?></th>
                                                    <th><?php echo SCTEXT('Msg ID') ?></th>
                                                    <th><?php echo SCTEXT('DLR') ?></th>
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
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMySentSms/<?php echo $data['sum']->sms_shoot_id ?>', columns: [null,null,null,{width:'10%'},null,null,{width:'15%'},null,null,null,null,null], order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, ordering: false, drawCallback: function(s){$('.pop-over').each(function(){$(this).popover({html: true});})}, responsive: {breakpoints: [
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
                                                    <th><?php echo SCTEXT('Sender') ?></th>
                                                    <th><?php echo SCTEXT('Text') ?></th>
                                                    <th><?php echo SCTEXT('Cost') ?></th>
                                                    <th><?php echo SCTEXT('MsgID') ?></th>
                                                    <th><?php echo SCTEXT('DLR') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Status') ?></th>
                                                    <th><?php echo SCTEXT('Explanation') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>


                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>