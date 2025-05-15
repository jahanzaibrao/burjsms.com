<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Delivery Reports') ?><small><?php echo SCTEXT('view all campaigns and delivery reports') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix sepH_b">
                                    <div class="topselect2 col-md-2 form-group pull-left">
                                        <select class="form-control" data-plugin="select2" id="campsel" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center text-white\'>'+data.text+'</div>';return $(nstr); } }">
                                            <option value="-1"> <?php echo SCTEXT('All Campaigns') ?> </option>
                                            <?php foreach ($data['camps'] as $cmp) { ?>
                                                <option value="<?php echo $cmp->id ?>" <?php if ($cmp->is_default == 1) { ?> selected <?php } ?>> <?php echo $cmp->campaign_name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="topselect2 col-md-3 form-group pull-left">
                                        <select class="form-control" data-plugin="select2" id="sorttype" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center text-white\'>'+data.text+'</div>';return $(nstr); } }">
                                            <option value="0"> <?php echo SCTEXT('Default Order') ?> </option>
                                            <option value="1"> <?php echo SCTEXT('Sort by Submission Date Ascending') ?> </option>
                                            <option value="-1"> <?php echo SCTEXT('Sort by Submission Date Descending') ?> </option>
                                            <option value="2"> <?php echo SCTEXT('Sort by Scheduled Date Ascending') ?> </option>
                                            <option value="3"> <?php echo SCTEXT('Sort by Scheduled Date Descending') ?> </option>

                                        </select>
                                    </div>
                                    <div class="btn-group pull-right">

                                        <div id="dlrdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                            <i class="fa fa-lg fa-calendar m-r-xs"></i>&nbsp;<span>Select Date</span>&nbsp;<b class="caret"></b>
                                        </div>

                                    </div>
                                </div><br />
                                <?php if ($_SESSION['user']['account_type'] == '0' || $_SESSION['user']['account_type'] == '2') { ?>
                                    <div class="">
                                        <table id="t-dlrsum" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMySmsCampaigns/'+$('#campsel').val(), drawCallback: function(){ $('.dlrsumldr').each(function(){$(this).popover({html: true})});}, columns: [null,null,null,null,null,{width:'20%'},null,null,null], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], serverSide: true, processing: true, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('Submitted On') ?></th>
                                                    <th><?php echo SCTEXT('Scheduled At') ?></th>
                                                    <th><?php echo SCTEXT('Route') ?></th>
                                                    <th><?php echo SCTEXT('Sender') ?></th>
                                                    <th><?php echo SCTEXT('Type') ?></th>
                                                    <th><?php echo SCTEXT('Text') ?></th>
                                                    <th><?php echo SCTEXT('Total Sent') ?></th>
                                                    <th><?php echo SCTEXT('Credits') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>

                                <?php } else { ?>
                                    <div class="">
                                        <table id="t-dlrsum" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getMySmsCampaigns/'+$('#campsel').val(), drawCallback: function(){ $('.dlrsumldr').each(function(){$(this).popover({html: true})});}, columns: [null,null,null,null,{width:'20%'},null,null,null], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], serverSide: true, processing: true, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('Submitted On') ?></th>
                                                    <th><?php echo SCTEXT('Scheduled At') ?></th>
                                                    <th><?php echo SCTEXT('Sender') ?></th>
                                                    <th><?php echo SCTEXT('Type') ?></th>
                                                    <th><?php echo SCTEXT('Text') ?></th>
                                                    <th><?php echo SCTEXT('Total Sent') ?></th>
                                                    <th><?php echo SCTEXT('Credits') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>
                                <?php } ?>
                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>