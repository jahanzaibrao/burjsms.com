<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Virtual Mobile Numbers')?> (VMN)<small><?php echo SCTEXT('manage all virtual numbers here') ?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <?php if($_SESSION['user']['group']=='admin'){ ?>
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo Doo::conf()->APP_URL ?>addNewVmn" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New VMN')?></a> 
                                            <?php if($_SESSION['user']['group']=='admin') { ?>
                                        <a href="<?php echo Doo::conf()->APP_URL ?>importNewVmn" class="btn btn-primary m-l-xs"><i class="fa fa-upload fa-large"></i>&nbsp; <?php echo SCTEXT('Bulk Import VMN')?></a> 
                                            <?php } ?>
                                        </div>
                                    </div><br />
                                <?php } ?>
                                    <div class="">
                                          <table id="dt-vmn" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllVmn', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Virtual Number')?></th>
                                                <th><?php echo SCTEXT('Type')?></th>
                                            <?php if($_SESSION['user']['group']=='admin' || $_SESSION['user']['account_type']=='0'){ ?> <th><?php echo SCTEXT('Route for Auto-reply')?></th> <?php } ?>
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