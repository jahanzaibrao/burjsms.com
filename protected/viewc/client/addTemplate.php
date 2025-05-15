<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add SMS Template') ?><small><?php echo SCTEXT('add a new sms template here') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <form class="form-horizontal" method="post" id="tmp_form" action="">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Template Name') ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter name for your sms template') ?>. . . ." maxlength="100" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Template Content') ?>:</label>
                                        <div class="col-md-8">
                                            <textarea id="tcont" name="tcont" maxlength="800" class="form-control" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" placeholder="<?php echo SCTEXT('enter content for sms template') ?>. . . ."></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Attach Files') ?>:</label>
                                        <div class="col-md-8">
                                            <select multiple class="form-control" name="tfiles[]" data-plugin="select2" data-options="{placeholder: '<?php echo SCTEXT('Choose Files') ?> ...'}">
                                                <?php foreach ($data['docs'] as $doc) { ?>
                                                    <option value="<?php echo $doc->id ?>"><?php echo $doc->filename ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block"><?php echo SCTEXT('You can link documents with your approval request for example, agreements, registraton, license etc.') ?> <a href="<?php echo Doo::conf()->APP_URL ?>addNewDocument"><?php echo SCTEXT('Add New File here') ?></a> <?php echo SCTEXT("if you don't have the file you need.") ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Options') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="checkbox checkbox-success">
                                                <input id="toggle-rt" name="tglrt" class="toggle" type="checkbox">
                                                <label for="toggle-rt"><?php echo SCTEXT('Request Approval for Template Based Route') ?></label>
                                            </div>
                                            <?php if ($_SESSION['user']['account_type'] == '0') { ?>
                                                <div id="rtbox" class="m-t-md hidden wd100">
                                                    <select class="form-control wd100" data-plugin="select2" name="route">
                                                        <?php foreach ($_SESSION['credits']['routes'] as $rt) { ?>
                                                            <option value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="route" value="<?php echo key($_SESSION['credits']['routes']); ?>">
                                            <?php } ?>
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