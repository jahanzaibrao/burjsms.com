<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Security Log')?><small><?php echo SCTEXT('list of suspicious activities')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <div id="secl-dp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>
                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="t-seclog" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getSusActivityLog', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, columns: [null,{width:'22%'},null,null,null,null,null] , order:[], serverSide: true, processing: true, drawCallback : function(s){ $('.blockipbtn').on('click',function(){ var uid = $(this).attr('data-uid');var action_id = $(this).attr('data-aid');var ip = $(this).attr('data-ip');bootbox.confirm({message: '<?php echo addslashes(SCTEXT('User will not be able to access web panel from this IP. Are you sure you want to proceed?'))?>',buttons: {cancel: {label: '<?php echo SCTEXT('No')?>',className: 'btn-default'},confirm: {label: '<?php echo SCTEXT('Yes, Proceed')?>',className: 'btn-info'}},callback: function (result) {if(result){$.ajax({url: app_url+'newBlockIpRequest',type: 'post',data: {user: uid, action: action_id, ip: ip},success: function(res){window.location.reload(false);}})}}});}) }, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Time')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('User')?></th>
                                                <th><?php echo SCTEXT('Type')?></th>
                                                <th data-priority="3"><?php echo SCTEXT('Activity')?></th>
                                                <th><?php echo SCTEXT('IP')?></th>
                                                <th><?php echo SCTEXT('Platform')?></th>
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