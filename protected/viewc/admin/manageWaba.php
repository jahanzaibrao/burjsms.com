<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('WhatsApp Business Accounts') ?><small><?php echo SCTEXT('view the list of WABA agents') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="clearfix sepH_b">

                                <div class="btn-group pull-right">

                                    <a href="<?php echo $data['baseurl'] ?>syncWaba" class="btn btn-primary"><i class="fa fa-refresh fa-large"></i>&nbsp; <?php echo SCTEXT('Sync with Meta') ?></a>

                                </div>
                            </div><br />
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="">
                                    <table id="dt_staffmgmt" data-plugin="DataTable" data-options="{ order:[], columns: [{width:'50%'},null,{width:'20%'},null], language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('Business Profiles') ?></th>
                                                <th><?php echo SCTEXT('Business Name') ?></th>
                                                <th data-priority="1"><?php echo SCTEXT('User') ?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                                            </tr>
                                        </thead>


                                        <tbody>
                                            <?php foreach ($data['wbaagents'] as $waba) { ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $profiles =  $data['wbaprofiles'][$waba->waba_id];
                                                        foreach ($profiles as $profile) {
                                                        ?>
                                                            <div style="display: flex !important; box-shadow: 3px 5px 13px 0 hsl(0, 0%, 90%); margin: 0 5% 5% 0;" class="col-md-12">
                                                                <div class="col-md-4 p-md text-center">
                                                                    <div class="avatar avatar-xlg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $profile->bp_profile_picture != '' ? $profile->bp_profile_picture : 'global/img/no-pic.png' ?>" alt="<?php echo $profile->verified_name ?>"></a></div>
                                                                    <h5 class="m-h-sm"><a href="javascript:void(0);" class="m-r-xs text-inverse"><?php echo $profile->verified_name ?></a></h5>
                                                                    <kbd class="label label-inverse label-md"><?php echo $profile->display_phone ?></kbd>
                                                                    <hr class="m-h-0"><label class="fz-sm" style="vertical-align: middle;">Quality | <span style="margin-top: -4px; border-radius: 10px;" class="badge badge-xs <?php echo $profile->quality == "GREEN" ? 'badge-success' : 'badge-danger'; ?>"><?php echo $profile->quality ?></span></label>
                                                                </div>
                                                                <div class="col-md-8 p-md">

                                                                    <div class="p-t-xs">
                                                                        <div>
                                                                            <p class="m-xs wabacarditem" style="font-size: 14px;"><i title="About" class="fa fa-lg fa-address-card m-r-xs"></i> <?php echo $profile->bp_about == '' ? 'Not Set' : $profile->bp_about ?></p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="m-t-0 m-b-xs m-l-xs wabacarditem" style="font-size: 14px;"><i title="Description" class="fa fa-lg fa-info-circle m-r-xs"></i> <b><?php echo $profile->bp_description == '' ? 'Not Set' : $profile->bp_description ?></b></p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="m-t-0 m-b-xs m-l-xs wabacarditem" style="font-size: 14px;"><i title="Address" class="fa fa-lg fa-globe-asia m-r-xs"></i> <?php echo $profile->bp_address == '' ? 'Not Set' : $profile->bp_address ?></p>
                                                                        </div>




                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php } ?>
                                                    </td>
                                                    <td><span class="label label-md label-primary"><?php echo $waba->waba_name ?></span></td>
                                                    <td>
                                                        <?php if ($waba->user_id == 0) {
                                                            echo ' - ' . SCTEXT('No User Associated') . ' -';
                                                        } else {
                                                            $user = $data['user_profiles'][$waba->user_id];
                                                            echo '<div class="media-group-item" style="padding-top:0;padding-left:0;">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm m-r-xs avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $waba->user_id . '"><img src="' . $user->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-xs"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $waba->user_id . '" class="m-r-xs theme-color">' . ucwords($user->name) . '</a></h5>
                                                <p style="font-size: 12px; margin-bottom: 0px; margin-top: -8px;">' . $user->email . ' &nbsp; ' . $evef . '</p>
                                            </div>
                                        </div>

                                    </div>';
                                                        } ?>

                                                    </td>
                                                    <td><a class="btn btn-default" href="<?php echo Doo::conf()->APP_URL ?>wabaConversations/<?php echo $waba->waba_id ?>">View Details</a></td>
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
            .wabacarditem {
                display: inline-flex;
                align-items: first baseline;
            }

            .wabacarditem i {
                width: 24px;
            }

            .wabacarditem b {
                margin-left: 5px;
            }
        </style>