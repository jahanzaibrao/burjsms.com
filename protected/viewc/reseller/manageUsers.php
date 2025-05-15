<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Active Users') ?><small><?php echo SCTEXT('manage user accounts here') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix sepH_b">
                                    <?php if ($_SESSION['user']['group'] == 'admin') { ?>
                                        <div class="form-group pull-left">
                                            <select class="form-control" data-plugin="select2" id="usrfilter">
                                                <option value="0"><?php echo SCTEXT('All User Accounts') ?></option>
                                                <option value="1"><?php echo SCTEXT('My Direct Downline') ?></option>
                                            </select>
                                        </div>
                                    <?php } ?>
                                    <div class="btn-group pull-right">
                                        <?php if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['add'] != 'on') {
                                            echo '';
                                        } else { ?>
                                            <a href="<?php echo $data['baseurl'] ?>addNewUser" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New User') ?></a>
                                        <?php } ?>
                                    </div>
                                </div><br />
                                <div class="">

                                    <?php if ($_SESSION['user']['group'] == 'admin') { ?>
                                        <table id="t-usrlist" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllUsers', ordering: false, serverSide: true, processing: true, drawCallback:function(settings){$('[title]').each(function(){$(this).tooltip();})}, columns: [{width:'180px'},null,null,null,null,{width:'160px'},null], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('User') ?></th>
                                                    <th data-priority="7"><?php echo SCTEXT('Login ID') ?></th>
                                                    <th data-priority="5"><?php echo SCTEXT('Phone') ?></th>
                                                    <th data-priority="4"><?php echo SCTEXT('Category') ?></th>
                                                    <th data-priority="6"><?php echo SCTEXT('SMS Credits') ?></th>
                                                    <th data-priority="3"><?php echo SCTEXT('Upline Reseller') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>


                                    <?php } else { ?>


                                        <table id="t-usrlist" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getAllUsers', serverSide: true, processing: true, columns: [{width:'180px'},null,null,null,null,null], drawCallback:function(settings){$('[title]').each(function(){$(this).tooltip();})}, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"><?php echo SCTEXT('User') ?></th>
                                                    <th data-priority="7"><?php echo SCTEXT('Login ID') ?></th>
                                                    <th data-priority="5"><?php echo SCTEXT('Phone') ?></th>
                                                    <th data-priority="4"><?php echo SCTEXT('Category') ?></th>
                                                    <th data-priority="6"><?php echo SCTEXT('SMS Credits') ?></th>
                                                    <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>



                                    <?php } ?>


                                </div>
                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>