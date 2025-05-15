<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Manage TLV Tags') ?><small><?php echo SCTEXT('create and manage tags with TLV parameters') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="alert alert-custom alert-info">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <i class="fa fa-2x fa-info-circle"></i> <?php echo SCTEXT('Currently Kannel does not support on-the-fly TLV configuration. After making changes, please restart Kannel gracefully.') ?>
                            </div>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix sepH_b">
                                    <div class="btn-group pull-right">
                                        <a href="<?php echo Doo::conf()->APP_URL ?>addNewSmppTlv" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add New Tag') ?></a>

                                    </div>
                                </div><br />
                                <div class="col-md-12 p-b-md" id="tlv_container">
                                    <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">
                                </div>
                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>