<main id="app-main" class="app-main">
        <div class="wrap">
            <section class="app-content">
            <?php include("announcements.php") ?>
               <div class="row">

                    <div class="col-md-3 col-sm-6">
                        <div class="widget stats-widget">
                            <div class="widget-body clearfix">
                                <div class="pull-left">
                                    <h3 class="widget-title text-success"><span id="weekly_sms_ctr" ></span></h3><small class="text-color"><?php echo SCTEXT('Weekly SMS Push')?></small></div><span class="pull-right big-icon "><i class="fa fa-envelope"></i></span></div>
                            <footer class="widget-footer bg-success"><small><?php echo SCTEXT('SMS Sent this <b>WEEK</b>')?></small> <span id="wk_sm_chart" class="small-chart pull-right"></span></footer>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="widget stats-widget">
                            <div class="widget-body clearfix">
                                <div class="pull-left">
                                    <h3 class="widget-title text-warning"><span id="monthly_sms_ctr"></span></h3><small class="text-color"><?php echo SCTEXT('Monthly SMS Push')?></small></div><span class="pull-right big-icon"><i class="far fa-envelope"></i></span></div>
                            <footer class="widget-footer bg-warning"><small><?php echo SCTEXT('SMS Sent this <b>MONTH</b>')?></small> <span id="mn_sm_chart" class="small-chart pull-right"></span></footer>
                        </div>
                    </div>


                     <div class="col-md-3 col-sm-6">
                        <div class="widget stats-widget">
                            <div class="widget-body clearfix">
                                <div class="pull-left">
                                    <h3 class="widget-title text-primary"><span id="weekly_cre_ctr" ></span></h3><small class="text-color"><?php echo SCTEXT('Weekly credit use')?></small></div><span class="pull-right big-icon"><i class="far fa-credit-card"></i></span></div>
                            <footer class="widget-footer bg-primary"><small><?php echo SCTEXT('Credits Used this <b>WEEK</b>')?></small> <span id="wk_cre_chart" class="small-chart pull-right"></span></footer>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="widget stats-widget">
                            <div class="widget-body clearfix">
                                <div class="pull-left">
                                    <h3 class="widget-title text-danger"><span id="monthly_cre_ctr" ></span></h3><small class="text-color"><?php echo SCTEXT('Monthly Credit Use')?></small></div><span class="pull-right big-icon"><i class="fa fa-money"></i></span></div>
                            <footer class="widget-footer bg-danger"><small><?php echo SCTEXT('Credit Used this <b>Month</b>')?></small> <span id="mn_cre_chart" class="small-chart pull-right"></span></footer>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-12 widget text-center">
                        <div class="widget-body">
                            <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>composeSMS"><i class="fa fa-lg fa-envelope m-r-xs"></i> <?php echo SCTEXT('Send SMS')?></a>
                            <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>showDlrSummary"><i class="fa fa-lg fa-list-alt m-r-xs"></i> <?php echo SCTEXT('View DLR')?></a>
                            <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>scheduledCampaigns"><i class="fa fa-lg fa-clock-o m-r-xs"></i> <?php echo SCTEXT('Scheduled Campaigns')?></a>
                            <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>manageContacts"><i class="zmdi zmdi-account-box-phone zmdi-hc-lg m-r-xs"></i> <?php echo SCTEXT('Manage Contacts')?></a>
                            <a class="m-r-sm btn btn-primary btn-md rounded" href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><i class="fa fa-lg fa-file m-r-xs"></i> <?php echo SCTEXT('Documents')?></a>
                        </div>

                    </div>
                </div>



                <div class="row">
                    <div class="<?php if(intval($data['account_type'])!= 1){ ?> col-md-4 <?php } else { ?> col-md-5 <?php } ?>">
                        <div class="widget">
                            <header class="widget-header">
                                <h4 class="widget-title">
                                        <?php echo SCTEXT('Recent Transactions')?>

                                </h4>
                            </header>
                            <hr class="widget-separator">
                            <div class="widget-body">
                                 <div id="rectransbox" class="media-group feeds-group">
                                    <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                                    </div>
                                    <div class="media-group-item" style="text-align:center;">
                                        <a href="<?php echo Doo::conf()->APP_URL ?>transactionReports" type="button" class="btn btn-outline btn-xs btn-info"><?php echo SCTEXT('View All')?></a>
                                        </div>
                            </div>
                        </div>
                    </div>
                    <div class="<?php if(intval($data['account_type'])!= 1){ ?> col-md-4 <?php } else { ?> col-md-7 <?php } ?>">
                        <div class="widget">
                            <header class="widget-header">
                                <h4 class="widget-title"><?php echo SCTEXT('Recent Campaigns')?>

                                </h4>
                            </header>
                            <hr class="widget-separator">
                            <div class="widget-body">
                                <div style="max-height: 350px; overflow:auto;" id="recsmsbox" class="media-group feeds-group">
                                    <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">

                                    </div>
                                    <div class="media-group-item" style="text-align:center;">
                                        <a href="<?php echo Doo::conf()->APP_URL ?>showDlrSummary" type="button" class="btn btn-outline btn-xs btn-info"><?php echo SCTEXT('View All')?></a>
                                        </div>
                            </div>
                        </div>
                    </div>
                    <?php if(intval($data['account_type'])!= 1){ ?>
                    <div class="col-md-4">
                        <div class="widget">
                            <header class="widget-header">
                                <h4 class="widget-title"><?php echo SCTEXT('Routes Activity')?>
                                     <div id="clientrtdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
											<span></span>&nbsp;<b class="caret"></b>
										</div>
                                </h4>
                            </header>
                            <hr class="widget-separator">
                            <div class="widget-body" id="clientrtctr">
                               <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

            </section>
            <div class="hidden" style="width: 0px; height:0px">
                <ul>
                    <?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                        <li id="route_js_<?php echo intval($rt['id']) ?>"><?php echo strip_tags($rt['name']) ?></li>
                    <?php } ?>
                </ul>
            </div>

        <input type="hidden" id="account_type" value="<?php echo intval($data['account_type']) ?>">
