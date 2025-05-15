<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Contacts')?><small><?php echo SCTEXT('manage contacts for')?> <b><?php echo $data['gdata']->group_name ?></b></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   <?php $colar = unserialize($data['gdata']->column_labels); ?>
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo Doo::conf()->APP_URL ?>addContact/<?php echo $data['gdata']->id ?>" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New Contact')?></a>

                                            <a href="<?php echo Doo::conf()->APP_URL ?>importContacts" class="btn btn-inverse m-l-md"><i class="fa fa-upload fa-large"></i>&nbsp; <?php echo SCTEXT('Upload Bulk Contacts')?></a>

                                        </div>
                                    </div><br />
                                    <div class="">
                                        <input type="hidden" name="groupid" id="groupid" value="<?php echo $data['gdata']->id ?>">
                                          <table id="dt_gcontacts" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getGroupContacts/<?php echo $data['gdata']->id ?>', serverSide: true, processing: true, lengthMenu: [[10, 50, 100, -1], [10, 50, 100, 'All']], select: 'multiple', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, dom: '<\'col-md-12\'<\'col-md-4\'l><\'col-md-4 clearfix\'B><\'col-md-4\'f><\'clearfix\'><tip>>', buttons: [{extend: 'selectAll', text: '<i class=\'fa fa-large fa-check-square\' title=\'Select All\'></i>'},{extend: 'selectNone', text: '<i class=\'far fa-large fa-square\' title=\'Uncheck All\'></i>'},{text:'<i class=\'fa fa-large fa-trash\' title=\'Delete Selected Rows\'></i>',action: function(){scBulkAction('delcontacts');}},{extend: 'collection', text:'<i class=\'fa fa-large fa-print\' title=\'Print\'></i>&nbsp;&nbsp; <span class=\'caret\'></span>', buttons:['excel','pdf','print']}], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Contact')?></th>
                                                <th><?php echo SCTEXT('Name')?></th>
                                                <?php if($colar['varC']!=''){ ?> <th><?php echo $colar['varC'] ?></th> <?php } ?>
                                                <?php if($colar['varD']!=''){ ?> <th><?php echo $colar['varD'] ?></th> <?php } ?>
                                                <?php if($colar['varE']!=''){ ?> <th><?php echo $colar['varE'] ?></th> <?php } ?>
                                                <?php if($colar['varF']!=''){ ?> <th><?php echo $colar['varF'] ?></th> <?php } ?>
                                                <?php if($colar['varG']!=''){ ?> <th><?php echo $colar['varG'] ?></th> <?php } ?>
                                                <th><?php echo SCTEXT('Network')?></th>
                                                <th><?php echo SCTEXT('Country/Region')?></th>
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
