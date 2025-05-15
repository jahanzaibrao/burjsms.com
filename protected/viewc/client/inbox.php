<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Inbox')?><small><?php echo SCTEXT('view all incoming SMS')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <div class="clearfix sepH_b">
                                        <div class="topselect2 col-md-2 form-group pull-left">
                                            <select class="form-control" data-plugin="select2" id="vmnsel" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center text-white\'>'+data.text+'</div>';return $(nstr); } }">
                                            <option value="0"> All VMN </option>
                                            <?php foreach($data['vmns'] as $vmn){ ?>
                                            <option value="<?php echo $vmn->vmn ?>"> <?php echo $vmn->vmn ?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                        <div class="btn-group pull-right">

                                            <div id="inboxdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>

                                        </div>
                                    </div><br />
                                    <div class="">
                                          <table id="dt_mosms" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllIncomingSms/0', lengthMenu: [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, 'All']], dom: '<\'col-md-12\'<\'col-md-4\'l><\'col-md-4 clearfix\'B><\'col-md-4\'f><\'clearfix\'><tip>>', buttons: [{extend: 'excel',text: 'Export Results',className: 'btn btn-default',exportOptions: {columns: ':visible:not(.noexp)'}}], serverside: true, processing: true, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Mobile')?></th>
                                                <th><?php echo SCTEXT('VMN')?></th>
                                                <th><?php echo SCTEXT('SMS Text')?></th>
                                                <th><?php echo SCTEXT('Received at')?></th>
                                                <th class="noexp" data-priority="2"><?php echo SCTEXT('Actions')?></th>
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
