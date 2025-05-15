<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Support Tickets')?><small><?php echo SCTEXT('address support queries by your downline')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <div class="clearfix">
                                   
                                    <div class="text-right">
                                        
                                        <div id="tktmgrdp" class="tsub_sml m-b-sm pull-right " style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-calendar fa-lg m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                        </div>
                                         
                                    </div>
                                 
                                    <br />
                                    
                                   <!-- tickets -->
                                    
                                    <div class="">
                                          <table id="dt_suptkt_mgr" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAssignedTickets', order:[], columns: [{width:'15%'},null,null,null,null,null], serverSide: true, processing: true, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('User')?></th>
                                                <th><?php echo SCTEXT('Date')?></th>
                                                <th><?php echo SCTEXT('Title')?></th>
                                                <th><?php echo SCTEXT('Priority')?></th>
                                                <th><?php echo SCTEXT('Status')?></th>
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