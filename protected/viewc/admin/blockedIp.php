<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Blocked IP Addresses')?><small><?php echo SCTEXT('list of all IP addresses blocked by system')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <div class="clearfix sepH_b">
                                         <a class="m-l-0 btn btn-primary" href="<?php echo Doo::conf()->APP_URL ?>manuallyBlockIp"><i class="fa fa-plus fa-lg m-r-sm"></i><?php echo SCTEXT('Manually Block IP')?></a>
                                        <div class="btn-group pull-right">
                                            <div id="blip-dp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>
                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="t-blip" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getBlockedIpList' , language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], columns: [null,{width:'25%'},null,null,null,null], serverSide: true, processing: true, drawCallback : function(s){ $('.unblockipbtn').on('click',function(){ var ipid = $(this).attr('data-ipid');bootbox.confirm({message: '<?php echo addslashes(SCTEXT('Are you sure you want to unblock this IP address and allow app access?'))?>',buttons: {cancel: {label: '<?php echo addslashes(SCTEXT('No'))?>',className: 'btn-default'},confirm: {label: '<?php echo addslashes(SCTEXT('Yes, Proceed'))?>',className: 'btn-info'}},callback: function (result) {if(result){$.ajax({url: app_url+'newUnblockIpRequest',type: 'post',data: {ip: ipid},success: function(res){window.location.reload(false);}})}}});}) }, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('IP Address')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('User')?></th>
                                                <th><?php echo SCTEXT('Platform')?></th>
                                                <th><?php echo SCTEXT('Date Added')?></th>
                                                <th><?php echo SCTEXT('Remarks')?></th>
                                                <th><?php echo SCTEXT('Action')?></th>
                                                
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