<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Primary Keywords')?><small><?php echo SCTEXT('primary keyword is the first word in the incoming SMS') ?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo Doo::conf()->APP_URL ?>addNewKeyword" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New Keyword')?></a> 
                                            
                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="dt-kw" data-plugin="DataTable" data-options="{
                                          ajax: '<?php echo Doo::conf()->APP_URL ?>getAllKeywords', serverSide: true, processing: true, <?php if($_SESSION['user']['group']=='admin'){ ?>columns: [null,null,{width:'25%'},null,null], <?php } ?>language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Keyword')?></th>
                                                <th><?php echo SCTEXT('VMN')?></th>
                                            <?php if($_SESSION['user']['group']=='admin'){ ?><th><?php echo SCTEXT('Assigned User')?></th> <?php } ?>
                                                <th><?php echo SCTEXT('Added on')?></th>
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