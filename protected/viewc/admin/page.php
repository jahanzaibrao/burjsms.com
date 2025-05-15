<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-primary"><?php echo Doo::conf()->currency ?><span id="weekly_sales_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Weekly Sales') ?></small>
                            </div><span class="pull-right big-icon"><i class="fa fa-money"></i></span>
                        </div>
                        <footer class="widget-footer bg-primary"><small><?php echo SCTEXT('SMS Sales this <b>WEEK</b>') ?></small> <span id="ws_sp_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-danger"><?php echo Doo::conf()->currency ?><span id="monthly_sales_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Monthly Sales') ?></small>
                            </div><span class="pull-right big-icon"><i class="fa fa-credit-card"></i></span>
                        </div>
                        <footer class="widget-footer bg-danger"><small><?php echo SCTEXT('SMS Sales this <b>MONTH</b>') ?></small> <span id="ms_sp_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-success"><span id="weekly_sms_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Weekly SMS Push') ?></small>
                            </div><span class="pull-right big-icon "><i class="fa fa-envelope"></i></span>
                        </div>
                        <footer class="widget-footer bg-success"><small><?php echo SCTEXT('SMS Sent this <b>WEEK</b>') ?></small> <span id="wm_sp_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-warning"><span id="monthly_sms_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Monthly SMS Push') ?></small>
                            </div><span class="pull-right big-icon"><i class="far fa-envelope"></i></span>
                        </div>
                        <footer class="widget-footer bg-warning"><small><?php echo SCTEXT('SMS Sent this <b>MONTH</b>') ?></small> <span id="mm_sp_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="widget">
                        <header class="widget-header">
                            <h4 class="widget-title">
                                <?php echo SCTEXT('Top Orders') ?>

                                <div id="topsalesdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:12px;">
                                    <i class="icon-calendar icon-large"></i>&nbsp;<span><?php echo date('M j, Y', strtotime('today - 6 days')) . ' - ' . date('M j, Y') ?></span>&nbsp;<b class="caret"></b>
                                </div>

                            </h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body">
                            <div id="topsalesctr" class="media-group feeds-group">
                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                            </div>
                            <div class="media-group-item" style="text-align:center;">
                                <button id="more_sales" type="button" class="btn btn-outline btn-xs btn-info hidden"><?php echo SCTEXT('Show More') ?> ...</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="widget">
                        <div class="widget-header">
                            <h4 class="widget-title clearfix">
                                <div class="btn-group pull-left" style="vertical-align: top;">
                                    <button id="dash_roc_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Routes <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                    </button>
                                    <ul id="dash_roc_dropdown" class="dropdown-menu search-options-bar">
                                        <li><a class="search_option_selector chosen" data-inputid="dash_roc" data-myvalue="r" href="javascript:void(0);">Routes</a> </li>
                                        <li><a class="search_option_selector" data-inputid="dash_roc" data-myvalue="c" href="javascript:void(0);">SMPP/Carriers</a></li>

                                    </ul>
                                    <input type="hidden" id="dash_roc" value="r">
                                    <span class="m-l-sm">Summary</span>
                                </div>
                                <div class="pull-right">
                                    <div id="roc_dp" class="tsub_sml " style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:12px;">
                                        <i class="icon-calendar icon-large"></i>&nbsp;<span></span>&nbsp;<b class="caret"></b>
                                    </div>
                                </div>


                            </h4>
                        </div>
                        <hr class="widget-separator">
                        <div class="widget-body" style="max-height: 350px; overflow:auto;">
                            <table id="roc_summary" class="wd100 text-center p-sm table-responsive table table-sm">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Total</th>
                                        <th>Delivered</th>
                                        <th>Failed</th>
                                        <th>NDNC</th>
                                        <th>ACK</th>
                                        <th>Invalid</th>
                                        <th>Refunded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8">Loading Data....</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="widget">
                        <header class="widget-header">
                            <h4 class="widget-title">
                                <?php echo SCTEXT('Top Consumers') ?>

                                <div id="topcldp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:12px;">
                                    <i class="icon-calendar icon-large"></i>&nbsp;<span></span>&nbsp;<b class="caret"></b>
                                </div>
                            </h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body">
                            <div id="topclctr" class="media-group feeds-group">
                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="widget">
                        <header class="widget-header">
                            <h4 class="widget-title"><?php echo SCTEXT('Top Resellers') ?>
                                <div id="toprsdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:12px;">
                                    <i class="icon-calendar icon-large"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                </div>
                            </h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body">
                            <div id="toprsctr" class="media-group feeds-group">
                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                            </div>
                            <div class="media-group-item" style="text-align:center;">
                                <button id="more_toprs" type="button" class="btn btn-outline btn-xs btn-info hidden"><?php echo SCTEXT('Show More') ?> ...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="widget">
                        <header class="widget-header">
                            <h4 class="widget-title"><?php echo SCTEXT('System Health') ?></h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body" id="syshlctr">
                            <div class="clearfix">
                                <div class="pull-left">
                                    <div class="pieprogress" data-plugin="circleProgress" data-value="<?php echo $data['hldata']['ram_ratio'] ?>" data-thickness="10" data-start-angle="90" data-empty-fill="<?php echo $data['hldata']['ram_ef'] ?>" data-fill="{<?php echo $data['hldata']['ram_fillcol'] ?>}"><strong>%<?php echo $data['hldata']['ram_per'] ?></strong></div>
                                </div>
                                <div class="pull-right" style="padding-top: 5%; text-align: center;">
                                    <h4 class="m-b-xs text-right"><?php echo SCTEXT('System RAM Usage') ?></h4><small class="text-muted"><?php echo $data['hldata']['used_ram'] ?> / <?php echo $data['hldata']['total_ram'] ?></small>
                                </div>
                            </div>
                            <div class="clearfix" style="margin-top:10px;">
                                <div class="pull-left">
                                    <div class="pieprogress" data-plugin="circleProgress" data-value="<?php echo $data['hldata']['ds_ratio'] ?>" data-thickness="10" data-start-angle="90" data-empty-fill="<?php echo $data['hldata']['ds_ef'] ?>" data-fill="{<?php echo $data['hldata']['ds_fillcol'] ?>}"><strong>%<?php echo $data['hldata']['ds_per'] ?></strong></div>
                                </div>
                                <div class="pull-right" style="padding-top: 5%; text-align: center;">
                                    <h4 class="m-b-xs text-right"><?php echo SCTEXT('Disk space Usage') ?></h4><small class="text-muted"><?php echo $data['hldata']['used_ds'] ?> / <?php echo $data['hldata']['total_ds'] ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <div class="hidden" style="width: 0px; height:0px">
            <ul>
                <?php foreach ($data['all_routes'] as $rid => $rname) { ?>
                    <li id="route_js_<?php echo intval($rid) ?>"><?php echo strip_tags($rname) ?></li>
                <?php } ?>
            </ul>
            <ul>
                <?php foreach ($data['all_smpp'] as $smsc => $smscdata) { ?>
                    <li id="smpp_js_<?php echo strip_tags($smsc) ?>"><?php echo strip_tags($smscdata) ?></li>
                <?php } ?>
            </ul>
            <ul>
                <?php foreach ($data['all_users'] as $uid => $udata) { ?>
                    <li id="user_js_<?php echo intval($uid) ?>"><?php echo strip_tags($udata) ?></li>
                <?php } ?>
            </ul>
        </div>