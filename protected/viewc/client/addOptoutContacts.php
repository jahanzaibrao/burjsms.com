<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add Optout Contacts') ?><small><?php echo SCTEXT('manually add a list of contacts to be blacklisted for this campaign') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" action="<?php echo Doo::conf()->APP_URL ?>saveOptoutContacts" method="post" id="coptoutfrm">
                                    <input type="hidden" name="campaignid" value="<?php echo $data['cdata']->id ?>">

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Phone Numbers') ?></label>
                                        <div class="col-md-8">
                                            <span class="help-block text-danger">Must include country prefix in contact numbers e.g. 91 for India, 39 for Italy etc.</span>
                                            <textarea class="form-control pop-over" name="msisdns" placeholder="<?php echo SCTEXT('enter single or multiple contacts with country prefix') ?> . . . ." data-placement="top" data-content="<?php echo SCTEXT('Enter mobile numbers separated by newline e.g.') ?> <br><p>9876xxxxx<br>8901xxxxx<br>9015xxxxxx</p>.... and so on" data-trigger="hover"></textarea>
                                        </div>
                                    </div>


                                    <hr>
                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button id="save_changes" class="btn btn-primary" type="submit"><?php echo SCTEXT('Save changes') ?></button>
                                            <button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel') ?></button>
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