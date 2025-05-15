<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Credit Log')?><small><?php echo SCTEXT('all credit deductions and additions info')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <div id="cldp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>
                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="t-crelog" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getCreditLog', order:[], serverSide: true, processing: true, ordering: false, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Amount')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Transaction Date')?></th>
                                            <?php if($_SESSION['user']['account_type']==0){ ?><th><?php echo SCTEXT('Route')?></th><?php } ?>
                                                <th><?php echo SCTEXT('Balance After')?></th>
                                                <th><?php echo SCTEXT('Reference')?></th>
                                                <th><?php echo SCTEXT('Comments')?></th>
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