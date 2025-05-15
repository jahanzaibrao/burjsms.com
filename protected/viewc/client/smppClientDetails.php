<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('SMPP Client Account') ?><small><?php echo SCTEXT('view all parameters associated with SMPP client account') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <div class="col-md-6">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>SMPP HOST</td>
                                                <td><code class="label label-primary label-md"><?php echo Doo::conf()->smpp_host ?></code></td>
                                            </tr>
                                            <tr>
                                                <td>SMPP PORT</td>
                                                <td><code class="label label-primary label-md"><?php echo Doo::conf()->smpp_port ?></code></td>
                                            </tr>
                                            <tr>
                                                <td>SYSTEM ID</td>
                                                <td><code class="label label-success label-md"><?php echo $data['client']->system_id ?></code></td>
                                            </tr>
                                            <tr>
                                                <td>SMPP PASSWORD</td>
                                                <td><code class="label label-success label-md"><?php echo Doo::conf()->show_password == 1 ? $data['client']->smpp_password : '******'; ?></code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 m-b-lg">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><?php echo SCTEXT('Maximum Tx Sessions') ?></td>
                                                <td><code class="label label-danger label-md"><?php echo $data['client']->tx_max ?></code></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo SCTEXT('Maximum Rx Sessions') ?></td>
                                                <td><code class="label label-danger label-md"><?php echo $data['client']->rx_max ?></code></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo SCTEXT('Maximum TRx Sessions') ?></td>
                                                <td><code class="label label-danger label-md"><?php echo $data['client']->trx_max ?></code></td>
                                            </tr>
                                            <?php if ($_SESSION['user']['account_type'] == 0) { ?>
                                                <tr>
                                                    <td><?php echo SCTEXT('Route Associated') ?></td>
                                                    <td><code class="label label-purple label-md"><?php echo $data['client']->route ?></code></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 m-t-lg text-center">
                                    <a class="btn btn-primary" href="<?php echo Doo::conf()->APP_URL . 'viewSmppSms/' . $data['client']->id ?>"><?php echo SCTEXT('View Sent SMS') ?></a>
                                    <a class="btn btn-default" href="<?php echo Doo::conf()->APP_URL . 'smppApi' ?>"><?php echo SCTEXT('Back to API') ?></a>
                                </div>
                                <!-- end content -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section>