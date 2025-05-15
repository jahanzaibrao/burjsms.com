<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
            <?php include("announcements.php") ?>
            <div class="row">

                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-primary"><span id="weekly_sales_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Weekly SMS Sales') ?></small>
                            </div><span class="pull-right big-icon"><i class="far fa-credit-card"></i></span>
                        </div>
                        <footer class="widget-footer bg-primary"><small><?php echo SCTEXT('Sales this <b>WEEK</b>') ?></small> <span id="wk_sales_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-danger"><span id="monthly_sales_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Monthly Sales') ?></small>
                            </div><span class="pull-right big-icon"><i class="fa fa-credit-card"></i></span>
                        </div>
                        <footer class="widget-footer bg-danger"><small><?php echo SCTEXT('Sales this <b>Month</b>') ?></small> <span id="mn_sales_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>


                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-success"><span id="weekly_usr_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Weekly Signups') ?></small>
                            </div><span class="pull-right big-icon "><i class=" zmdi zmdi-accounts-list zmdi-hc-lg"></i></span>
                        </div>
                        <footer class="widget-footer bg-success"><small><?php echo SCTEXT('New Users this <b>WEEK</b>') ?></small> <span id="wk_usr_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="widget stats-widget">
                        <div class="widget-body clearfix">
                            <div class="pull-left">
                                <h3 class="widget-title text-warning"><span id="monthly_usr_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Monthly Signups') ?></small>
                            </div><span class="pull-right big-icon"><i class=" zmdi zmdi-accounts zmdi-hc-lg"></i></span>
                        </div>
                        <footer class="widget-footer bg-warning"><small><?php echo SCTEXT('Signups this <b>MONTH</b>') ?></small> <span id="mn_usr_chart" class="small-chart pull-right"></span></footer>
                    </div>
                </div>




            </div>


            <div class="row">
                <div class="col-md-12 widget text-center">
                    <div class="widget-body">
                        <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>manageUsers"><i class="menu-icon zmdi zmdi-accounts zmdi-hc-lg"></i> <?php echo SCTEXT('Manage Users') ?></a>
                        <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>composeSMS"><i class="fa fa-lg fa-envelope m-r-xs"></i> <?php echo SCTEXT('Send SMS') ?></a>
                        <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>showDlrSummary"><i class="fa fa-lg fa-list-alt m-r-xs"></i> <?php echo SCTEXT('View DLR') ?></a>
                        <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>genWebSettings"><i class="menu-icon zmdi zmdi-view-web zmdi-hc-lg"></i> <?php echo SCTEXT('Website Settings') ?></a>
                        <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>manageContacts"><i class="zmdi zmdi-account-box-phone zmdi-hc-lg m-r-xs"></i> <?php echo SCTEXT('Manage Contacts') ?></a>
                        <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><i class="fa fa-lg fa-file m-r-xs"></i> <?php echo SCTEXT('Documents') ?></a>
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
                        <header class="widget-header">
                            <h4 class="widget-title"><?php echo SCTEXT('Sales & Signups') ?>
                                <div id="salesgrdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:12px;">
                                    <span><?php echo date('M j, Y', strtotime('today - 9 days')) . ' - ' . date('M j, Y') ?></span>&nbsp;<b class="caret"></b>
                                </div>
                            </h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body p-xs">
                            <div id="salesgr" style="height:300px">
                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">
                            </div>

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
                            <h4 class="widget-title">
                                <?php echo SCTEXT('Recent Transactions') ?>

                            </h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body">
                            <div id="rectransbox" class="media-group feeds-group">
                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                            </div>
                            <div class="media-group-item" style="text-align:center;">
                                <a href="<?php echo Doo::conf()->APP_URL ?>transactionReports" type="button" class="btn btn-outline btn-xs btn-info"><?php echo SCTEXT('View All') ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="widget">
                        <header class="widget-header">
                            <h4 class="widget-title"><?php echo SCTEXT('Recent Campaigns') ?>

                            </h4>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body">
                            <div id="recsmsbox" class="media-group feeds-group">
                                <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                            </div>
                            <div class="media-group-item" style="text-align:center;">
                                <a href="<?php echo Doo::conf()->APP_URL ?>showDlrSummary" type="button" class="btn btn-outline btn-xs btn-info"><?php echo SCTEXT('View All') ?></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>
        <div class="hidden" style="width: 0px; height:0px">
            <ul>
                <?php foreach ($data['all_users'] as $uid => $udata) { ?>
                    <li id="user_js_<?php echo intval($uid) ?>"><?php echo strip_tags($udata) ?></li>
                <?php } ?>
            </ul>
        </div>