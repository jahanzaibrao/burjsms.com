<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('SMS Archive') ?><small><?php echo SCTEXT('search and download older SMS records') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix m-b-md">
                                    <div class="col-md-1">
                                        <i class="fa fa-4x fa-warehouse text-primary"></i>
                                    </div>
                                    <div class="col-md-11">
                                        <?php echo SCTEXT('Old sent SMS records are moved to ARCHIVE so our database can perform at its best. You can still search and download your sent SMS records. Search works based on date or date range.') ?>
                                    </div>
                                </div>

                                <div style="margin:auto;width:50%;" class="clearfix p-md bg-inverse img-rounded">
                                    <form method="post" id="ftadd_frm">
                                        <input type="hidden" id="chosenDate" name="chosenDate" value="Select Date">
                                        <div class="col-md-6 p-l-sm clearfix">
                                            <div class="pull-right">
                                                <div id="sldp" style=" cursor: pointer; padding: 8px 12px; border: 1px solid #ccc; font-size:14px;">
                                                    <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="m-l-xs caret"></b>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 p-l-sm">
                                            <a class="btn btn-primary" id="submit_archdt" href="javascript:void(0);"><i class="fa fa-lg fa-search-plus m-r-xs"></i> <?php echo SCTEXT('Request Data') ?></a>
                                        </div>
                                    </form>
                                </div>

                                <hr>
                                <div class="alert alert-custom alert-info">
                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                    <i class="fa fa-2x fa-info-circle"></i> <?php echo SCTEXT('Requests older than 30 days will be automatically deleted.') ?>
                                </div>
                                <div class="">
                                    <table id="t-smsar" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getArchivedFiles', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('S.No.') ?></th>
                                                <th data-priority="1"><?php echo SCTEXT('Requested on') ?></th>
                                                <th><?php echo SCTEXT('Search Date') ?></th>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Download File') ?></th>
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