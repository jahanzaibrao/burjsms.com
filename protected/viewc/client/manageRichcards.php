<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Rich Cards')?><small><?php echo SCTEXT('create rich cards to include in your RCS business campaigns')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo $data['baseurl'] ?>addNewRichcard" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New Rich Card')?></a>  

                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="dt_rcards" data-plugin="DataTable" data-options="{
                                             language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], columns: [{width:'180px'},{width:'480px'},null,{width:'160px'},{width:'160px'}], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Title')?></th>
                                                <th><?php echo SCTEXT('Preview')?></th>
                                                <th><?php echo SCTEXT('Added On')?></th>
                                                <th><?php echo SCTEXT('Status')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td >Fast Food Delivery</td>
                                                <td>
                                                <div class=""><div class="widget"><div class="widget-body text-center"><div class="big-icon m-b-md watermark"><img src="<?php echo Doo::conf()->APP_URL ?>global/img/food.jpeg"></div><h4 class="m-b-md">Doorstep Delivery</h4><p class="text-muted m-b-lg">Celebrate this festive season with our fast doorstep delivery of your delicious cravings.</p><a href="#" class="btn p-v-xl btn-primary m-r-sm"> <i class="fa fa-file"></i>&nbsp; View Our Menu</a><a href="#" class="btn p-v-xl btn-primary"> <i class="fa fa-phone"></i>&nbsp; Call To Place Order</a></div></div></div>

                                                </td>
                                                <td>9th January 2024 10:08 PM</td>
                                                <td><i class="fa fa-check-circle text-success"></i> Approved</td>
                                                <td ><div class="dropdown btn-group "><button data-toggle="dropdown" class="btn dropdown-toggle" aria-expanded="true"> Actions <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="#">Edit</a></li><li><a href="javascript:void(0);" class="del-sid" data-sid="1">Delete</a></li></ul></div></td>
                                            </tr>
                                            <tr>
                                            <td >Travel Deals</td>
                                                <td>
                                                <div class=""><div class="widget"><div class="widget-body text-center"><div class="big-icon m-b-md watermark"><img src="<?php echo Doo::conf()->APP_URL ?>global/img/travel.jpeg"></div><h4 class="m-b-md">Upto 60% Discount on Hotels</h4><p class="text-muted m-b-lg">Check Out our seasons discount offerrings on Luxury hotels.</p><a href="#" class="btn p-v-xl btn-primary"> <i class="fa fa-link"></i>&nbsp; Visit Offer Page</a></div></div></div>

                                                </td>
                                                <td>9th January 2024 9:55 PM</td>
                                                <td><i class="fa fa-check-circle text-success"></i> Approved</td>
                                                <td ><div class="dropdown btn-group "><button data-toggle="dropdown" class="btn dropdown-toggle" aria-expanded="true"> Actions <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="#">Edit</a></li><li><a href="javascript:void(0);" class="del-sid" data-sid="1">Delete</a></li></ul></div></td>
                                            </tr>
                                            <tr>
                                            <td >Home Loan Offer</td>
                                                <td> <div class=""><div class="widget"><div class="widget-body text-center"><div class="big-icon m-b-md watermark"><img src="<?php echo Doo::conf()->APP_URL ?>global/img/homeloan.jpg"></div><h4 class="m-b-md">Afforable Home Loans</h4><p class="text-muted m-b-lg">Celebrate this festive season with our fast doorstep delivery of your delicious cravings.</p><a href="#" class="btn p-v-xl btn-primary"> <i class="fa fa-phone"></i>&nbsp; Call Our Agent</a><a href="#" class="btn p-v-xl btn-primary m-l-sm"> <i class="fa fa-file"></i>&nbsp; Download Brochure</a></div></div></div></td>
                                                <td>10th January 2024 09:30 AM</td>
                                                <td><i class="fa fa-check-circle text-warning"></i> Pending</td>
                                                <td ><div class="dropdown btn-group "><button data-toggle="dropdown" class="btn dropdown-toggle" aria-expanded="true"> Actions <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="#">Edit</a></li><li><a href="javascript:void(0);" class="del-sid" data-sid="1">Delete</a></li></ul></div></td>
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