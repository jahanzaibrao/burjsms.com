<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('SMPP Server Monitor') ?><small><?php echo SCTEXT('view and manage all client connections') ?> </small></h3>
                            <hr>
                            <?php include('notification.php') ?>

                            <!-- start content -->
                            <div class="col-md-12">
                                <div class="col-md-3 col-sm-6">
                                    <div class="widget bg-info">
                                        <header class="widget-header">
                                            <h4 class="widget-title"><?php echo SCTEXT('Connected Accounts') ?></h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body p-t-lg">
                                            <div class="clearfix m-b-md">
                                                <h3 class="pull-left m-0 fw-500"><span class="counter" data-plugin="counterUp"><?php echo $data['totalclients'] ?></span></h3>
                                                <div class="pull-right watermark"><i class="fas fa-2x fa-smile"></i></div>
                                            </div>
                                            <p class="m-b-0 text-muted"><?php echo SCTEXT('Total SMPP client accounts connected to our server.') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 p-l-sm col-sm-6">
                                    <div class="widget bg-warning">
                                        <header class="widget-header">
                                            <h4 class="widget-title"><?php echo SCTEXT('Total Channels') ?></h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body p-t-lg">
                                            <div class="clearfix m-b-md">
                                                <h3 class="pull-left m-0 fw-500"><span class="counter" data-plugin="counterUp"><?php echo $data['totalbinds'] ?></span></h3>
                                                <div class="pull-right watermark"><i class="fas fa-2x fa-stream text-inverse"></i></div>
                                            </div>
                                            <p class="m-b-0 text-muted"><?php echo SCTEXT('Total SMPP sessions connected and online.') ?></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <?php foreach ($data['moninfo'] as $client) { ?>
                                    <div class="smppclient">
                                        <div class="media-group-item">
                                            <div class="sysid col-md-2 col-sm-2 col-xs-2">
                                                <span class="label label-primary label-lg"><?php echo $client["systemid"] ?></span>
                                            </div>
                                            <div class="media col-md-2 col-sm-2 col-xs-2">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="<?php echo Doo::conf()->APP_URL ?>viewUserAccount/<?php echo $client["user"] ?>"><img src="<?php echo $client["avatar"] ?>" alt=""></a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0 m-b-0"><a href="<?php echo Doo::conf()->APP_URL ?>viewUserAccount/<?php echo $client["user"] ?>" class="m-r-xs theme-color"><?php echo $client['username'] ?></a><small class="text-muted fz-sm"><?php echo $client['usertype'] ?></small></h5>
                                                    <p style="font-size: 12px;font-style: Italic;"><?php echo $client['useremail'] ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3 p-l-lg">
                                                <table class="table table-borderless fz-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td>ROUTE</td>
                                                            <td class="text-right"><span class="label label-purple label-md"><?php echo $client['routetitle'] ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>CONNECTIONS</td>
                                                            <td class="text-right"><span class="label label-success label-md"><?php echo $client['totalbinds'] ?></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <!-- connections -->
                                        <div class="smppbinds">
                                            <table class="table table-hover table-responsive table-striped">
                                                <tbody>
                                                    <?php foreach ($client['connections'] as $bind) { ?>
                                                        <tr>
                                                            <td>bind mode <code class="m-l-xs"><?php echo $bind['type'] ?></code></td>
                                                            <td><span class="label label-success">online</span></td>
                                                            <td>
                                                                <kbd><?php echo $bind['clientip'] ?></kbd>
                                                            </td>
                                                            <td>
                                                                <?php echo date(Doo::conf()->date_format_long_time, floor($bind['time'] / 1000)) ?>
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0);" class="btn btn-xs btn-danger closesmppclient" data-sessionid="<?php echo $bind['id'] ?>">Close Session</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>

                            <!-- end content -->
                        </div>
                    </div>
                </div>
            </div>

        </section>