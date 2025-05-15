<style>
    .popover {
        min-width: 150px !important;
    }
</style>
<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix"><?php echo SCTEXT('Sent SMS via SMPP') ?><small><?php echo SCTEXT('view messages sent from smpp account') ?> CLIENT: <span class="m-v-xs label label-primary"><?php echo $data['client']->system_id ?></span> <?php if (isset($data['client']->route)) { ?> ROUTE: <span class="m-v-xs label label-info"><?php echo $data['client']->route ?></span> <?php } ?></small>

                                <span class="pull-right">
                                    <button class="btn btn-primary" id="smppdl" data-sid="<?php echo $data['client']->id ?>"><i class="fas fa-download fa-lg m-r-xs"></i> <?php echo SCTEXT('Download') ?></button>
                                </span>

                            </h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <div class="clearfix sepH_b">

                                    <div class="btn-group pull-right">

                                        <div id="smsdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                            <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                        </div>

                                    </div>
                                </div><br />
                                <div class="">
                                    <input type="hidden" id="systemid" value="<?php echo $data['client']->system_id ?>">
                                    <table id="t-smppsms" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getSmppSmsList/<?php echo $data['client']->system_id ?>', drawCallback: function(){$('.pop-over').popover({html: true});}, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], ordering: false, searching: false, serverSide: true, processing: true, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead class="small-popover">
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Mobile') ?></th>
                                                <th><?php echo SCTEXT('Network') ?></th>
                                                <th><?php echo SCTEXT('Sender') ?></th>
                                                <th><?php echo SCTEXT('Message') ?></th>
                                                <th><?php echo SCTEXT('Time') ?></th>
                                                <th><?php echo SCTEXT('Cost') ?></th>
                                                <th><?php echo SCTEXT('SMS-ID') ?></th>
                                                <th data-priority="3"><?php echo SCTEXT('DLR') ?></th>
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