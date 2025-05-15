<link href="<?php echo Doo::conf()->APP_URL ?>global/prism.css" rel="stylesheet">
<script src="<?php echo Doo::conf()->APP_URL ?>global/prism.js"></script>
<style>
    .code-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
        overflow-x: auto;
    }

    .card-header {
        background-color: #f7f7f7;
        border-bottom: 1px solid #ddd;
    }

    pre {
        margin-bottom: 0;
    }

    code {
        border: none !important;
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
                            <h3 class="page-title-sc"><?php echo SCTEXT('Mobile Number Porting Data') ?><small><?php echo SCTEXT('manage MNP lookup database') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix sepH_b">
                                    <div class="btn-group pull-right">
                                        <a href="<?php echo $data['baseurl'] ?>addMnpRecords" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add MNP Task') ?></a>

                                    </div>
                                </div><br />
                                <div class="col-md-12">

                                    <div class="col-md-4">
                                        <div class="panel panel-primary planopts">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"><?php echo SCTEXT('Database Summary') ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item clearfix"><span id="totalmnp" class="pull-right"></span><?php echo SCTEXT('Total records') ?>:</li>
                                                    <li class="list-group-item clearfix"><span id="mnplastup" class="pull-right text-right"><?php echo $data['last_updated'] ?></span><?php echo SCTEXT('Last Updated') ?>:</li>
                                                </ul>
                                                <div class=" clearfix">
                                                    <div class="pull-left" style="position: relative;">
                                                        <div id="health-circle" data-plugin="circleProgress"></div>
                                                    </div>
                                                    <div class="pull-right" style="padding-top: 5%; text-align: center;">
                                                        <h4 class="m-b-xs text-right"><?php echo SCTEXT('DB Health') ?></h4><small class="text-muted">Index &amp; Storage efficiency</small>
                                                    </div>
                                                </div>
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
                                                <h4 class="panel-title"><?php echo SCTEXT('MNP Tasks') ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table id="dt_mnpjobs" data-plugin="DataTable" data-options="{ajax: '<?php echo Doo::conf()->APP_URL ?>getMnpJobs', searching: false, lengthChange: false, ordering: false, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, drawCallback: function(){ $('.taskinfo').each(function(){$(this).popover({trigger:'click',placement:'bottom',html:true});}); }, responsive: {breakpoints: [{ name: 'desktop', width: Infinity },{ name: 'tablet',  width: 1024 },{ name: 'fablet',  width: 768 },{ name: 'phone',   width: 480 }]}}" class="wd100 table row-border order-column">
                                                    <thead>
                                                        <tr>
                                                            <th data-priority="1"><?php echo SCTEXT('Type') ?></th>
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

                                </div>
                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>