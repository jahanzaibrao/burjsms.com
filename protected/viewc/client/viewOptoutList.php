<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix"><?php echo SCTEXT('View Opt-out Numbers') ?><small><?php echo SCTEXT('opted out contact numbers for') ?>: <b><?php echo $data['cdata']->campaign_name ?></b></small>
                                <span class="pull-right">
                                    <a class="btn btn-primary" href="<?php echo Doo::conf()->APP_URL ?>globalFileDownload/optout/<?php echo $data['cdata']->id ?>"><i class="fa m-r-sm fa-lg fa-download"></i><?php echo SCTEXT('Download') ?></a>
                                    <a class="btn btn-primary" href="<?php echo Doo::conf()->APP_URL ?>addOptoutContacts/<?php echo $data['cdata']->id ?>"><i class="fa m-r-sm fa-lg fa-plus"></i><?php echo SCTEXT('Add More') ?></a>
                                </span>
                            </h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="">
                                    <table id="t-optouts" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getOptoutList/<?php echo $data['cdata']->id ?>', serverside: true, processing: true, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Mobile') ?></th>
                                                <th><?php echo SCTEXT('Added On') ?></th>
                                                <th><?php echo SCTEXT('Keyword Matched') ?></th>
                                                <th><?php echo SCTEXT('Actions') ?></th>
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