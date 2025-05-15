
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                 <h3 class="page-title-sc clearfix"><?php echo SCTEXT('View Account')?><small><?php echo $data['user']->name.' ('.$data['user']->email.')' ?></small>
                                <input type="hidden" id="userid" value="<?php echo $data['user']->user_id ?>"/>
                                    <span class="dropdown pull-right">
                                      <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><i class="fa fa-large fa-navicon"></i> &nbsp; <?php echo SCTEXT('Actions')?> <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                                    <li><a class="useraction" data-act="upgradeacc" href="javascript:void(0);" ><i class="fa fa-large fa-user-plus"></i>&nbsp;&nbsp; <?php echo SCTEXT('Upgrade to Reseller')?> </a></li>
                                                    <li><a class="useraction" data-act="changepsw" href="javascript:void(0);" ><i class="fa fa-large fa-key"></i>&nbsp;&nbsp; <?php echo SCTEXT('Change Password'
)?> </a></li>
                                                    <li><a class="useraction" data-act="usersus" href="javascript:void(0);" ><i class="fa fa-large fa-ban"></i>&nbsp;&nbsp; <?php echo SCTEXT('Suspend Account')?> </a></li>
                                                    <li><a class="useraction" data-act="userdel" href="javascript:void(0);" ><i class="fa fa-large fa-trash"></i>&nbsp;&nbsp; <?php echo SCTEXT('Delete Account')?> </a></li>
                                                </ul>
                                            
                                    </span>
                                </h3>
                                <hr class="m-t-xs">
                                <?php include('notification.php') ?>
                                
                                <?php include('navpills.php') ?> 
                                
                                <hr>
                                <!-- start content -->
                                <div class="col-md-12">
                                    <h4><?php echo SCTEXT('Transaction History')?></h4>
                                    <hr>
                                    
                                    
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                           
                                            <div id="transdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="icon-calendar icon-large"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>

                                        </div>
                                    </div><br />
                                    <?php if($data['user']->account_type==1 || $data['user']->account_type==2){ ?>
                                                <table id="dt_utrans" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMyTransactions/Select Date/<?php echo $data['user']->user_id ?>', columns: [null,null,null,{width:'25%'},null], order:[], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, responsive: {breakpoints: [
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
                                    <?php }else{ ?>
                                    <div class="">
                                          <table id="dt_utrans" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMyTransactions/Select Date/<?php echo $data['user']->user_id ?>',serverSide: true, processing: true, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, columns: [null,null,null,null,null,{width:'20%'},null], order:[], responsive: {breakpoints: [
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
                                        
                                    </div>  
                                            <?php } ?>
                                    
                                </div>
                                <!-- end content -->    
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>