<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('WBM Plan Prices') ?><small><?php echo SCTEXT('manage zone prices for this WBM plan') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="">
                                    <input type="hidden" id="planid" value="<?php echo $data['planid'] ?>" />
                                    <table id="dt-meta-rate-price_sorted" data-plugin="DataTable" data-options="{language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order: [1,'desc'], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Country') ?></th>
                                                <th><b><?php echo SCTEXT('Zone') ?></b></th>
                                                <th>Marketing </th>
                                                <th>Utility</th>
                                                <th>Authentication</th>
                                                <th data-priority="3">Auth Intl.</th>
                                                <th data-priority="2">Service Messages</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            function getZoneColor($zone, &$colors)
                                            {
                                                if (!isset($colors[$zone])) {
                                                    // Generate a pastel color
                                                    $hue = mt_rand(0, 90);  // Random hue
                                                    $lightness = mt_rand(96, 99);  // Lightness for pastel
                                                    $colors[$zone] = "hsl($hue, 100%, $lightness%)";
                                                }
                                                return $colors[$zone];
                                            }
                                            $zoneColors = [];
                                            ?>
                                            <?php foreach ($data['plan_coverage'] as $country) { ?>
                                                <?php
                                                $cost_prices = $data['cost_prices'][$country->zone_id];
                                                $selling_prices = $data['plan_prices'][$country->zone_id];
                                                $color = getZoneColor($country->zone_id, $zoneColors);

                                                ?>
                                                <tr>
                                                    <td><?php echo $country->country ?> (<?php echo $country->prefix ?>) </td>
                                                    <td style="background-color: <?php echo $color ?>;"><?php echo $country->zone ?></td>
                                                    <td>
                                                        <table class="wd100 card-mgw">
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon bg-primary text-white"><i class="zmdi zmdi-hc-lg zmdi-facebook"></i> </span><kbd class="addon-sticker-sm"><b><?php echo Doo::conf()->currency ?><?php echo $cost_prices['marketing'] ?></b></kbd></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?> </span><input size="8" class="addon-input-sm sp_marketing_<?php echo $country->id ?>" data-zone="<?php echo $country->zone ?>" id="sp_marketing_<?php echo $country->id ?>" type="text" placeholder="e.g. 0.045" class="form-control input-sm" value="<?php echo $selling_prices['marketing'] ?>"></div>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                    <td>
                                                        <table class="wd100 card-mgw">
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon bg-primary text-white"><i class="zmdi zmdi-hc-lg zmdi-facebook"></i> </span><kbd class="addon-sticker-sm"><b><?php echo Doo::conf()->currency ?><?php echo $cost_prices['utility'] ?></b></kbd></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?> </span><input size="8" class="addon-input-sm sp_utility_<?php echo $country->id ?>" data-zone="<?php echo $country->zone ?>" id="sp_utility_<?php echo $country->id ?>" type="text" placeholder="e.g. 0.045" class="form-control input-sm" value="<?php echo $selling_prices['utility'] ?>"></div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <table class="wd100 card-mgw">
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon bg-primary text-white"><i class="zmdi zmdi-hc-lg zmdi-facebook"></i> </span><kbd class="addon-sticker-sm"><b><?php echo Doo::conf()->currency ?><?php echo $cost_prices['cp_auth'] ?></b></kbd></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?> </span><input size="8" class="addon-input-sm sp_auth_<?php echo $country->id ?>" data-zone="<?php echo $country->zone ?>" id="sp_auth_<?php echo $country->id ?>" type="text" placeholder="e.g. 0.045" class="form-control input-sm" value="<?php echo $selling_prices['cp_auth'] ?>"></div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <?php if ($cost_prices['auth_int'] > 0) { ?>
                                                            <table class="wd100 card-mgw">
                                                                <tr>
                                                                    <td>
                                                                        <div class="input-group"><span class="input-group-addon bg-primary text-white"><i class="zmdi zmdi-hc-lg zmdi-facebook"></i> </span><kbd class="addon-sticker-sm"><b><?php echo Doo::conf()->currency ?><?php echo $cost_prices['auth_int'] ?></b></kbd></div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?> </span><input size="8" class="addon-input-sm sp_authint_<?php echo $country->id ?>" data-zone="<?php echo $country->zone ?>" id="sp_authint_<?php echo $country->id ?>" type="text" placeholder="e.g. 0.045" class="form-control input-sm" value="<?php echo $selling_prices['auth_int'] ?>"></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        <?php } else { ?>
                                                            <h6>N/A</h6>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <table class="wd100 card-mgw">
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon bg-primary text-white"><i class="zmdi zmdi-hc-lg zmdi-facebook"></i> </span><kbd class="addon-sticker-sm"><b><?php echo Doo::conf()->currency ?><?php echo $cost_prices['cp_ser'] ?></b></kbd></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?> </span><input size="8" class="addon-input-sm sp_service_<?php echo $country->id ?>" data-zone="<?php echo $country->zone ?>" id="sp_service_<?php echo $country->id ?>" type="text" placeholder="e.g. 0.045" class="form-control input-sm" value="<?php echo $selling_prices['cp_ser'] ?>"></div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group"><a title="Save Pricing" href="javascript:void(0);" data-zoneid="<?php echo $country->zone_id ?>" data-rowid="<?php echo $country->id ?>" data-zone="<?php echo $country->zone ?>" class="btn btn-success savepricing"><i class="fa fa-large fa-check"></i></a></div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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
        <style>
            .addon-sticker-sm {
                padding: 6px 8px 6px 8px;
                vertical-align: middle;
                background: #fff;
                color: #000;
                box-shadow: none;
            }

            .addon-input-sm {
                border-bottom: 2px solid #ccc;
                padding-left: 10%;
                border-left: 0;
                border-top: 0;
                border-right: 0;
            }

            .card-mgw {
                box-shadow: 3px 5px 13px 0 hsl(0, 0%, 90%);
                min-width: 140px;
            }
        </style>