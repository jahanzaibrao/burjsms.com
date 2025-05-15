<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Smpp')?><small><?php echo SCTEXT('add & manage Smpp connections')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->

                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo $data['baseurl'] ?>addSmpp" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add SMPP Connection')?></a>

                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="dt_e" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllSmpp', order:[], drawCallback: function(settings){getSmppCredits();createSwitches();}, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('SMPP Name')?></th>
                                                <th><?php echo SCTEXT('SMSC ID')?></th>
                                                <th><?php echo SCTEXT('Provider')?></th>
                                                <th><?php echo SCTEXT('Credits')?></th>
                                                <th data-priority="4"><?php echo SCTEXT('Host Server')?></th>
                                                <th data-priority="3"><?php echo SCTEXT('Status')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>

                                    </div>
                                <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
