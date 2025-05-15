<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('App Alerts')?><small><?php echo SCTEXT('view a list of notifications for various events')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <div class="clearfix sepH_b">
                                    <div id="altdp" class="tsub_sml m-t-sm pull-right " style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-calendar fa-lg m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                    </div>
                                         
                                </div>
                                    
                                
                                    <br />
                                    
                                   <!-- tickets -->
                                    
                                    <div class="">
                                          <table id="t-alerts" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllMyAlerts', order:[], columns: [{width:'70%'},null,null] , order: [], serverSide: true, processing: true, ordering: false, select: 'multiple', dom: '<\'col-md-12\'<\'col-md-4\'l><\'col-md-4 clearfix\'B><\'col-md-4\'f><\'clearfix\'><tip>>', buttons: [{extend: 'selectAll', text: '<i class=\'fa fa-check-square\' title=\'<?php echo SCTEXT('Select All')?>\'></i>'},{extend: 'selectNone', text: '<i class=\'far fa-square\' title=\'<?php echo SCTEXT('Uncheck All')?>\'></i>'},{text:'<?php echo SCTEXT('Mark as Read')?>',action: function(){scBulkAction('readAlerts');}}], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Notification')?></th>
                                                <th><?php echo SCTEXT('Link')?></th>
                                                <th><?php echo SCTEXT('Date')?></th>
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