<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add New Keyword') ?><small><?php echo SCTEXT('add a new SPAM Keyword') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="add_spmkw_form" action="">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Keyword') ?>:</label>
                                        <div class="col-md-8">
                                            <textarea name="kwp" id="kwp" class="form-control" placeholder="<?php echo SCTEXT('enter spam keywords or phrase') ?>"></textarea>
                                            <span class="help-block"><?php echo SCTEXT('Enter words or phrases line by line and try include numbers and special characters that might be used to bypass the spam filter') ?></span>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                            <button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel') ?></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>