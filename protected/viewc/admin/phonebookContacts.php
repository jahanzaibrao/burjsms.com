<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo $data['gdata']->group_name ?><small><?php echo SCTEXT('manage phonebook contact numbers')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo Doo::conf()->APP_URL ?>importPhonebookContacts/<?php echo $data['gdata']->id ?>" class="btn btn-primary"><i class="fa fa-upload fa-large"></i>&nbsp; <?php echo SCTEXT('Upload Contacts')?></a>  

                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="dt_pbdb_mgmt" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getPhonebookContacts/<?php echo $data['gdata']->id ?>', order:[], serverSide: true, processing: true, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('S.No.')?></th>
                                                <th data-priority="1"><?php echo SCTEXT('Mobile Number')?></th>
                                                <th><?php echo SCTEXT('Actions')?></th>
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