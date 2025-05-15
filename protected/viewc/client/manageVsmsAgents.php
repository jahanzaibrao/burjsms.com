<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <div class="mail-item planopts m-r-0 m-b-0"><table class="mail-container"><tbody><tr><td class="mail-left"><div class="avatar avatar-lg avatar-circle"><a href="javascript:void(0);"><img src="https://lh3.googleusercontent.com/COxitqgJr1sJnIDe8-jiKhxDx1FrYbtRHKJ9z_hELisAlapwE9LUPh6fcXIfb5vwpbMl4xl9H9TRFPc5NOO8Sb3VSgIBrfRYvW6cUA" alt="Google"></a></div></td><td class="mail-center"><div class="mail-item-header" style="margin-bottom: 0px;margin-top: 0px;"><h4 class="mail-item-title"><a href="mail-view.html" class="title-color">Verified SMS by Google</a></h4><a href="javascript:void(0);"><span class="label label-success">for Android</span></a> <a href="https://developers.google.com/business-communications/verified-sms" target="_blank"><span class="label label-primary">Learn More</span></a> </div><p class="mail-item-excerpt">Verified SMS helps businesses enhance their conversations with users, build trust, and prevent scams. When a message is verified, users see the sender's business name, the sender's business logo, and a verification badge in the message thread.</p></td></tr></tbody></table></div>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->

                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo Doo::conf()->APP_URL ?>addNewVsmsAgent" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New Agent')?></a>

                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="dt_vsa" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllVerifiedAgents', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, columns: [{width:'250px'}<?php echo $_SESSION['user']['group']=='admin'?',{width:\'160px\'}':''; ?>,null,null,null], order:[], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Agent')?></th>
                                                <?php if($_SESSION['user']['group']=='admin'){ ?>
                                                <th><?php echo SCTEXT('User')?></th>
                                                <?php } ?>
                                                <th><?php echo SCTEXT('Created On')?></th>
                                                <th><?php echo SCTEXT('Status')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>

                                    </div>

                                        <div id="vadetailsbox" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Agent Details</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="media-group-item p-t-0">
                                                            <div class="media">
                                                                <div class="media-left">
                                                                    <div class="avatar avatar-xlg avatar-circle"><a href="javascript:void(0);"><img id="vadetails-logo" src="" alt="Company"></a></div>
                                                                </div>
                                                                <div class="media-body">
                                                                    <h5 class="m-t-0 m-b-0"><a id="vadetails-name" href="javascript:void(0);" class="m-r-xs theme-color"> </a></h5>
                                                                    <p class="m-b-xs" id="vadetails-desc" style="font-size: 12px;font-style: Italic;"> </p>
                                                                    <hr class="m-h-sm">
                                                                    <h6>Sender IDs</h6>
                                                                    <span id="vadetails-senders" class="m-b-sm"></span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
