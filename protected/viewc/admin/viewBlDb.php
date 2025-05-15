<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Table') ?>: <?php echo $data['tdata']->table_name ?><small><?php echo SCTEXT('view details and lookup mobile numbers here') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="col-md-4">
                                    <div class="panel panel-primary planopts">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php echo SCTEXT('Table Info') ?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><span class="badge badge-info"><?php echo number_format($data['tdata']->total_records) ?></span><?php echo SCTEXT('Total records') ?>:</li>
                                                <li class="list-group-item"><span class="badge badge-primary"><?php echo $data['tdata']->last_mod == '0000-00-00 00:00:00' ? SCTEXT('Never Updated') : date(Doo::conf()->date_format_long, strtotime($data['tdata']->last_mod)); ?></span><?php echo SCTEXT('Last Updated') ?>:</li>
                                                <li class="list-group-item">
                                                    <button class="btn btn-danger btn-sm" data-tid="<?php echo $data['tdata']->table_id ?>" id="empty_tbl"><?php echo SCTEXT('Empty the Table') ?> (TRUNCATE)</button>
                                                </li>
                                                <li class="list-group-item">
                                                    <button class="btn btn-success btn-sm" data-tid="<?php echo $data['tdata']->table_id ?>" id="opt_tbl"><?php echo SCTEXT('Rebuild Index') ?> (OPTIMIZE) </button>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel panel-primary planopts">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php echo SCTEXT('Number Lookup') ?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="input-group">
                                                <input type="hidden" id="tinfo" value="<?php echo base64_encode($data['tdata']->table_name . '|' . $data['tdata']->mobile_column) ?>" />
                                                <input id="mobile_no" value="" placeholder="<?php echo SCTEXT('enter mobile number here') ?> ..." class="form-control" style="display: block;" type="text"><span class="input-group-btn"><button id="lookupbtn" class="btn btn-info " type="button"><i class="fa fa-search fa-lg"></i>&nbsp; <?php echo SCTEXT('Search') ?></button></span>
                                            </div>
                                            <hr>
                                            <ul id="lookup_result" class="list-group">

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="panel panel-purple planopts m-r-0">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php echo SCTEXT('DB Import Tasks') ?></h4>
                                        </div>
                                        <div class="panel-body">
                                            <table id="dt_bldbit" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getImportTasks/<?php echo $data['tdata']->id ?>', searching: false, lengthChange: false, ordering: false, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, drawCallback: function(){ $('.taskinfo').each(function(){$(this).popover({animation:false,placement:'bottom',html:true});}); }, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1"><?php echo SCTEXT('File') ?></th>
                                                        <th><?php echo SCTEXT('Added on') ?></th>
                                                        <th data-priority="2"><?php echo SCTEXT('Status') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
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