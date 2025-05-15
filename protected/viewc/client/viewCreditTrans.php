<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Credit Transactions')?><small><?php echo SCTEXT('view all credit transactions performed on your account')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                           
                                            <div id="transdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="icon-calendar icon-large"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>

                                        </div>
                                    </div><br />
                                    <div class="">
                                        <?php if($_SESSION['user']['account_type']==0){ ?>
                                          <table id="dt_utrans" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMyTransactions/', columns: [null,null,null,null,null,{width:'20%'},null], order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Date')?></th>
                                                <th><?php echo SCTEXT('Transaction Type')?></th>
                                                <th><?php echo SCTEXT('Amount')?></th>
                                                <th><?php echo SCTEXT('Route')?></th>
                                                <th><?php echo SCTEXT('Trans ID')?></th>
                                                <th><?php echo SCTEXT('Done By')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                            <?php }else{ ?>
                                                <table id="dt_utrans" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMyTransactions/', columns: [null,null,null,{width:'25%'},null], order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Date')?></th>
                                                <th><?php echo SCTEXT('Transaction Type')?></th>
                                                <th><?php echo SCTEXT('Amount')?></th>
                                                <th><?php echo SCTEXT('Done By')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                            <?php } ?>
                                    </div>  
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>           