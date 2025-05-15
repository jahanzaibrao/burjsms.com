<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('HLR Lookup Reports') ?><small><?php echo SCTEXT('view reports of all the HLR lookup calls') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix sepH_b">
                                    <div class="btn-group pull-right">
                                        <a href="<?php echo Doo::conf()->APP_URL ?>newHlrLookup" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('New HLR Lookup') ?></a>
                                        <a href="javascript:void(0);" id="download_hlr" class="btn btn-primary m-l-xs"><i class="fa fa-download fa-large"></i>&nbsp; <?php echo SCTEXT('Download Reports') ?></a>
                                    </div>
                                    <div class="pull-left">
                                        <div id="hlrdp" class="tsub_sml m-t-sm pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                            <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                        </div>
                                    </div>
                                </div><br />
                                <div class="">
                                    <table id="t-hlrreports" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getHlrReports', order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Mobile') ?></th>
                                                <th><?php echo SCTEXT('HLR-Status') ?></th>
                                                <th><?php echo SCTEXT('MCCMNC') ?></th>
                                                <th><?php echo SCTEXT('Network') ?></th>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th><?php echo SCTEXT('Date') ?></th>
                                                <th><?php echo SCTEXT('Roaming') ?></th>
                                                <th><?php echo SCTEXT('Ported') ?></th>
                                                <th><?php echo SCTEXT('Location') ?></th>
                                                <th><?php echo SCTEXT('Price') ?></th>
                                                <th><?php echo SCTEXT('Data') ?></th>
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