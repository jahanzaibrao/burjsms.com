<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Approve Sender IDs') ?><small><?php echo SCTEXT('approve or reject sender ID request from users') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="">
                                    <table id="dt_apprsid" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllPendingSenderIds', columns: [null,{width:'22%'},null,null,null,null,null] , order: [], select: 'multiple', dom: '<\'col-md-12\'<\'col-md-4\'l><\'col-md-4 clearfix\'B><\'col-md-4\'f><\'clearfix\'><tip>>', buttons: [{extend: 'selectAll', text: '<i class=\'fa fa-large fa-check-square\' title=\'<?php echo addslashes(SCTEXT('Select All')) ?>\'></i>'},{extend: 'selectNone', text: '<i class=\'far fa-large fa-square\' title=\'<?php echo addslashes(SCTEXT('Uncheck All')) ?>\'></i>'},{text:'<?php echo addslashes(SCTEXT('Approve')) ?>',action: function(){scBulkAction('apprSid');}},{text:'<?php echo addslashes(SCTEXT('Reject')) ?>',action: function(){scBulkAction('rejcSid');}}], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Sender ID') ?></th>
                                                <th><?php echo SCTEXT('Requested By') ?></th>
                                                <th><?php echo SCTEXT('Coverage') ?></th>
                                                <th><?php echo SCTEXT('Request Date') ?></th>
                                                <th><?php echo SCTEXT('Attached Files') ?></th>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
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