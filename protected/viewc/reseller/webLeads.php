<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Website Leads')?><small><?php echo SCTEXT('view a list prospective clients')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <div class="text-center ">
                                   
                                   <div class="form-inline img-rounded" style="display:inline-block;">
                                        
                                        <div class="form-group">
                                            <select class="form-control" data-plugin="select2" id="leadfilter">
                                                <option value="-1"><?php echo SCTEXT('All Prospect Sources')?></option>
                                                <option value="0"><?php echo SCTEXT('Contact Form')?></option>
                                                <option value="1"><?php echo SCTEXT('Test Gateway Widget')?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group m-l-sm">
                                            <a class="btn btn-primary" id="dt_leadfilter" href="javascript:void(0);"><?php echo SCTEXT('Filter Results')?></a>
                                        </div>
                                        
                                        
                                    </div>
                                 </div>
                                    <br />
                                    
                                   <!-- tickets -->
                                    
                                    <div class="">
                                          <table id="dt_web_leads" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getWebsiteLeads/-1', order:[], columns: [null,null,null,{width:'20%'},null,{width:'20%'}], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, serverSide: true, processing: true, ordering: false, drawCallback: function(s){$('.pop-over').each(function(){$(this).popover({html: true});})}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('S.No.')?></th>
                                                <th data-priority="1"><?php echo SCTEXT('Source')?></th>
                                                <th><?php echo SCTEXT('Date')?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Prospect Data')?></th>
                                                <th><?php echo SCTEXT('SMS Sent')?></th>
                                                <th><?php echo SCTEXT('OS Platform')?></th>
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
                
            </section>           