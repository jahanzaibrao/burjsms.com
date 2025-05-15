<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Prefixes')?><small><?php echo SCTEXT('manage mobile prefixes for')?> <?php echo strtoupper($data['cdata']->country) ?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo $data['baseurl'] ?>uploadPrefixes" class="btn btn-primary"><i class="fa fa-upload fa-large"></i>&nbsp; <?php echo SCTEXT('Upload More Prefixes')?></a>  
                                        </div>
                                    </div><br />
                                    <input type="hidden" id="cid" name="cid" value="<?php echo $data['cdata']->id ?>"/>
                                    <div class="">
                                          <table id="dt_oppremgmt" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllOP/<?php echo $data['cdata']->country_code ?>', select: 'multiple', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, dom: '<\'col-md-12\'<\'col-md-4\'l><\'col-md-4 clearfix\'B><\'col-md-4\'f><\'clearfix\'><tip>>', buttons: [{extend: 'selectAll', text: '<i class=\'fa fa-large fa-check-square\' title=\'<?php echo addslashes(SCTEXT('Select All'))?>\'></i>'},{extend: 'selectNone', text: '<i class=\'far fa-large fa-square\' title=\'<?php echo addslashes(SCTEXT('Uncheck All'))?>\'></i>'},{text:'<i class=\'fa fa-large fa-trash\' title=\'<?php echo addslashes(SCTEXT('Delete Selected Rows'))?>\'></i>',action: function(){scBulkAction('delpre');}},{extend: 'collection', text:'<i class=\'fa fa-large fa-print\' title=\'Print\'></i>&nbsp;&nbsp; <span class=\'caret\'></span>',buttons:['excel','pdf','print']}], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Prefix')?></th>
                                                <th><?php echo SCTEXT('MCCMNC')?></th>
                                                <th><?php echo SCTEXT('Network')?></th>
                                                <th><?php echo SCTEXT('Region')?></th>
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