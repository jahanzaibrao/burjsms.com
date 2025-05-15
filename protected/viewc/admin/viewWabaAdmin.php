<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc">WhatsApp Business Messaging<small><?php echo SCTEXT('your whatsapp business partner account') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab1" data-toggle="tab">Verified Partner Account</a></li>
                                        <li><a href="#tab2" data-toggle="tab">Pricing</a></li>
                                    </ul>

                                    <div id="apitabctr" class="tab-content p-v-lg">


                                        <div class="tab-pane active fade in" id="tab1"><br /><br />
                                            <form method="post" id="mafrm" action="<?php echo Doo::conf()->APP_URL ?>saveWabaBusinessProfile">
                                                <div class="col-md-12 ">
                                                    <div class="text-center sld-banners col-md-3 col-sm-3 gallery-item p-r-sm">
                                                        <div class="thumb circle">
                                                            <img src="<?php echo Doo::conf()->mask_waba == 1 ? $_SESSION['user']['avatar'] : $data['main_agent']['data'][0]['profile_picture_url'] ?>" class="img-responsive">
                                                        </div>
                                                    </div>
                                                    <div class="form-horizontal col-md-9 col-sm-9">
                                                        <div class="m-t-sm">
                                                            <div class="form-group">
                                                                <label class=" control-label col-md-3">
                                                                    APP ID
                                                                </label>
                                                                <div class="col-md-8 p-t-xs">
                                                                    <span class="text-dark"><?php echo Doo::conf()->mask_waba == 1 ?  substr(Doo::conf()->wba_app_id, 0, floor(strlen(Doo::conf()->wba_app_id) * 0.4)) . str_repeat('x', ceil(strlen(Doo::conf()->wba_app_id) * 0.6)) : Doo::conf()->wba_app_id; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" control-label col-md-3">
                                                                    Verified Name
                                                                </label>
                                                                <div class="col-md-8 p-t-xs">
                                                                    <span class="text-dark"><?php echo Doo::conf()->mask_waba == 1 ? substr($data['main_phone']['verified_name'], 0, floor(strlen($data['main_phone']['verified_name']) * 0.4)) . str_repeat('x', ceil(strlen($data['main_phone']['verified_name']) * 0.6)) : $data['main_phone']['verified_name'] ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" control-label col-md-3">
                                                                    Display Phone
                                                                </label>
                                                                <div class="col-md-8 p-t-xs">
                                                                    <span class="text-dark"><?php echo $data['main_phone']['display_phone_number'] ?></span>
                                                                    <span class="m-l-sm label label-xs <?php echo $data['main_phone']['quality_rating'] == "GREEN" ? 'label-success' : 'label-danger'; ?>">Quality: <?php echo $data['main_phone']['quality_rating'] ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" control-label col-md-3">
                                                                    About
                                                                </label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" id="ma_about" name="ma_about" placeholder="A brief tagline ..." value="<?php echo $data['main_agent']['data'][0]['about'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class=" control-label col-md-3">
                                                                    Description
                                                                </label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" id="ma_desc" name="ma_desc" placeholder="A brief tagline ..." value="<?php echo $data['main_agent']['data'][0]['description'] ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class=" control-label col-md-3">

                                                                </label>
                                                                <div class="col-md-8 text-right">
                                                                    <button id="saveupfrm" class="btn btn-primary" type="submit">Save changes</button>
                                                                </div>
                                                            </div>

                                                        </div>


                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="tab2"><br /><br />
                                            <table id="dt-meta-def-price_sorted" data-plugin="DataTable" data-options="{language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1"><?php echo SCTEXT('Region') ?></th>
                                                        <th>Marketing </th>
                                                        <th>Utility Messages</th>
                                                        <th>Authentication</th>
                                                        <th data-priority="3">Authentication Intl.</th>
                                                        <th data-priority="2">Service Messages</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['def_costs'] as $cp) { ?>
                                                        <tr>
                                                            <td><?php echo $cp->zone ?></td>
                                                            <td>
                                                                <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?> </span><input id="cp_mark<?php echo $cp->id ?>" name="cp_mark<?php echo $cp->id ?>" type="text" placeholder="e.g. 0.045" class="form-control" value="<?php echo $cp->marketing ?>"></div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?></span><input id="cp_util<?php echo $cp->id ?>" name="cp_util<?php echo $cp->id ?>" type="text" placeholder="e.g. 0.045" class="form-control" value="<?php echo $cp->utility ?>"></div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?></span><input id="cp_auth<?php echo $cp->id ?>" name="cp_auth<?php echo $cp->id ?>" type="text" placeholder="e.g. 0.045" class="form-control" value="<?php echo $cp->cp_auth ?>"></div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?></span><input id="cp_authint<?php echo $cp->id ?>" name="cp_authint<?php echo $cp->id ?>" type="text" placeholder="e.g. 0.045" class="form-control" value="<?php echo $cp->auth_int ?>"></div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?></span><input id="cp_serv<?php echo $cp->id ?>" name="cp_serv<?php echo $cp->id ?>" type="text" placeholder="e.g. 0.045" class="form-control" value="<?php echo $cp->cp_ser ?>"></div>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group"><a title="Save Pricing" href="javascript:void(0);" data-smppid="1" data-id="<?php echo $cp->id ?>" class="btn btn-primary savepricing"><i class="fa fa-large fa-check"></i></a></div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>






                                    </div>
                                </div>

                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>