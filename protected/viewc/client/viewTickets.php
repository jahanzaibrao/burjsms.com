<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Support Tickets')?><small><?php echo SCTEXT('contact support for concerns or issues')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <div class="clearfix">
                                    
                                    <div class="col-md-6">
                                        <div class="media-group-item p-t-0">
                                                        
                                                        <div class="media">
                                                            <div class="media-left">
                                                                <div class="avatar avatar-xlg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $_SESSION['manager']['avatar']; ?>" alt="Manager"></a></div>
                                                            </div>
                                                            <div class="media-body">
                                                                <h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs theme-color"><?php echo $_SESSION['manager']['name']; ?> </a></h5>
                                                                <p class="m-b-0" style="font-size: 12px;font-style: Italic;"><i class="fa fa-lg fa-phone m-r-xs"></i> <?php echo $_SESSION['manager']['mobile']; ?></p>
                                                                <p class="m-b-0" style="font-size: 12px;font-style: Italic;"><i class="fa fa-lg fa-envelope m-r-xs"></i> <?php echo $_SESSION['manager']['email']; ?></p>
                                                                <span class="m-b-sm label label-info label-sm"><?php echo SCTEXT('Account Manager')?></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                    </div>
                                    
                                    <div class="col-md-6 clearfix sepH_b text-right">
                                        <div class="btn-group">
                                            <a href="<?php echo $data['baseurl'] ?>addNewTicket" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('New Support Ticket')?></a>
                                        </div><br>
                                        <div id="tktdp" class="tsub_sml m-t-sm pull-right " style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-calendar fa-lg m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                        </div>
                                         
                                    </div>
                                    
                                </div>
                                    <br />
                                    
                                   <!-- tickets -->
                                    
                                    <div class="">
                                          <table id="dt_stkts" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMyTickets', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Date')?></th>
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