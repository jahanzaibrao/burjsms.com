<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('WhatsApp Campaign Reports')?><small><?php echo SCTEXT('view reports of all the WhatsApp campaigns')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    
                                    <div class="clearfix sepH_b">
                                        <div id="whrdp" class="tsub_sml m-t-sm pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                        </div>
                                       
                                    </div><br />
                                    <div class="">
                                    <table id="t-whreports" data-plugin="DataTable" data-options="{
                                            order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: false, processing: false, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('Campaign Name')?></th>
                                                    <th ><?php echo SCTEXT('Type')?></th>
                                                    <th><?php echo SCTEXT('Date')?></th>
                                                    <th><?php echo SCTEXT('Status')?></th>
                                                    <th><?php echo SCTEXT('Sending To')?></th>
                                                    <th><?php echo SCTEXT('Delivered')?></th>
                                                    <th><?php echo SCTEXT('Read')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td >Loop Cancellation Refunds</td>
                                                    <td ><h5>Interactive</h5></td>
                                                    <td>9th January 2024 12:55 PM</td>
                                                    <td><span class="label label-success">Sent</span></td>
                                                    <td><b>45</b> Contacts</td>
                                                    <td><i class="fa fa-check-double text-primary"></i> <b>45</b> Contacts</td>
                                                    <td><i class="fa fa-check-double text-success"></i> <b>32</b> Contacts</td>
                                                </tr>
                                                <tr>
                                                    <td >Loop Order Confirmation</td>
                                                    <td ><h5>One Way</h5></td>
                                                    <td>9th January 2024 12:07 PM</td>
                                                    <td><span class="label label-success">Sent</span></td>
                                                    <td><b>105</b> Contacts</td>
                                                    <td><i class="fa fa-check-double text-primary"></i> <b>105</b> Contacts</td>
                                                    <td><i class="fa fa-check-double text-success"></i> <b>97</b> Contacts</td>
                                                </tr>
                                                <tr>
                                                    <td >Citibank Credit Offer</td>
                                                    <td ><h5>One Way</h5></td>
                                                    <td>9th January 2024 11:38 AM</td>
                                                    <td><span class="label label-warning">In Draft</span></td>
                                                    <td><b>50</b> Contacts</td>
                                                    <td><i class="fa fa-check-double text-primary"></i> <b>0</b> Contacts</td>
                                                    <td><i class="fa fa-check-double text-success"></i> <b>0</b> Contacts</td>
                                                </tr>
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