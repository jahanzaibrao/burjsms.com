<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('System Monitor') ?><small><?php echo SCTEXT('manage all the queued campaigns and monitor users') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>

                            <!-- start content -->

                            <!-- queued and scheduled -->
                            <input type="hidden" id="peak_throughput_allowed" value="<?php echo Doo::conf()->peak_throughput_allowed; ?>">
                            <?php if ($_SESSION['user']['group'] == "admin") {  ?>
                                <input type="hidden" id="is_sysmon_admin" value="1">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="widget">
                                            <header class="widget-header">
                                                <h4 class="widget-title"> <?php echo SCTEXT('Current SMS Traffic') ?></h4>
                                            </header>
                                            <hr class="widget-separator">
                                            <div class="widget-body dt-small">
                                                <table class="sc_responsive wd100 table row-border order-column text-center">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-center">SMPP</th>
                                                            <th class="text-center">API</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="vertical-align: middle;"> <span class="badge badge-success">Now</span></td>
                                                            <td>
                                                                <div id="smpp_now_traffic" style="width: 120px; height: 120px;"></div>
                                                            </td>
                                                            <td>
                                                                <div id="api_now_traffic" style="width: 120px; height: 120px;"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: middle;"> <span class="badge badge-danger">Peak</span></td>
                                                            <td>
                                                                <div id="smpp_peak_traffic" style="width: 120px; height: 120px;"></div>
                                                            </td>
                                                            <td>
                                                                <div id="api_peak_traffic" style="width: 120px; height: 120px;"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: middle;"> <span class="badge badge-primary">5-Min Avg</span></td>
                                                            <td>
                                                                <div id="smpp_avg_traffic" style="width: 120px; height: 120px;"></div>
                                                            </td>
                                                            <td>
                                                                <div id="api_avg_traffic" style="width: 120px; height: 120px;"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: middle;"> <span class="badge badge-inverse">Total</span></td>
                                                            <td> <kbd class="bg-white text-inverse fz-md"><b id="smpp_total"></b></kbd></td>
                                                            <td> <kbd class="bg-white text-inverse fz-md"><b id="api_total"></b></kbd></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="widget">
                                            <header class="widget-header">
                                                <h4 class="widget-title">
                                                    <?php echo SCTEXT('Historical Data') ?>
                                                    <div id="sysmondp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                        <i class="fa fa-lg fa-calendar m-r-xs"></i> <span></span>&nbsp;<b class="caret"></b>
                                                    </div>
                                                </h4>
                                            </header>
                                            <hr class="widget-separator">
                                            <div class="widget-body dt-small">

                                                <div id="sysmon" style="height: 475px;"></div>

                                            </div>
                                        </div>
                                    </div>



                                </div>
                            <?php } ?>

                            <hr class="fc-clear">
                            <!-- temp campaigns and online users -->
                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title clearfix">
                                                <?php echo SCTEXT('User Activity') ?>

                                                <div class="pull-right" style="font-size: 12px; font-weight:500; line-height:20px;">
                                                    <?php echo SCTEXT('Auto-Refresh') ?> &nbsp; <input id="ou_ref" data-switchery="true" data-size="small" checked="checked" data-color="#10c469" type="checkbox">

                                                </div>
                                            </h4>

                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body" id="" style="max-height:490px;overflow:auto;">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#tab-online" role="tab" data-toggle="tab" aria-expanded="true"><?php echo SCTEXT('Online Users') ?></a></li>
                                                <li role="presentation" class=""><a href="#tab-all" role="tab" data-toggle="tab" aria-expanded="false"><?php echo SCTEXT('All Users') ?></a></li>

                                            </ul>
                                            <div class="tab-content p-md">
                                                <div role="tabpanel" class="tab-pane fade active in" id="tab-online">
                                                    <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">


                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="tab-all">

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title clearfix">
                                                <?php echo SCTEXT('Scheduled Campaigns') ?>

                                                <div class="pull-right" style="font-size: 12px; font-weight:500; line-height:20px;">
                                                    <?php echo SCTEXT('Auto-Refresh') ?> &nbsp; <input id="sc_ref" data-switchery="true" data-size="small" checked="checked" data-color="#10c469" type="checkbox">


                                                </div>
                                            </h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body dt-small" id="">
                                            <table id="dt_sc" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllScheduledCampaigns', columns: [{width:'9%'},null,null,null,null,null], initComplete: function(settings){createSwitches('sswitch');}, order: [], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1"><?php echo SCTEXT('User') ?></th>
                                                        <th><?php echo SCTEXT('SMS Text') ?></th>
                                                        <th><?php echo SCTEXT('SMS Data') ?></th>
                                                        <th data-priority="3"><?php echo SCTEXT('Schedule Time') ?></th>
                                                        <th data-priority="2"><?php echo SCTEXT('Status') ?></th>
                                                        <th><?php echo SCTEXT('Action') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- end content -->

                        </div>
                    </div>
                </div>
            </div>

        </section>
        <style>
            .badge {
                border-radius: 10px !important;
                padding-bottom: 6px !important;
            }
        </style>