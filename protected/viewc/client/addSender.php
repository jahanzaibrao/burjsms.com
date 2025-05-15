<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add Sender ID') ?><small><?php echo SCTEXT('add a new sender ID to use in sms campaigns') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <form class="form-horizontal" method="post" id="sid_form" action="">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID') ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" name="sid" id="sid" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID') ?>..." maxlength="100" />
                                            <span class="help-block"><?php echo SCTEXT('Follow the standards for Sender ID set by your SMS provider. The standards could be limited number of characters, no space or special characters allowed etc.') ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Request For') ?>:</label>
                                        <div class="col-md-8">
                                            <div class="clearfix">
                                                <div class="col-md-6 p-r-sm">
                                                    <select class="form-control" data-plugin="select2" id="cvsel" name="cvsel[]" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center\'>'+data.text+'</div>';return $(nstr); } }">
                                                        <option selected value="0"> <?php echo SCTEXT('All Countries') ?> </option>
                                                        <?php foreach ($data['cvdata'] as $cv) { ?>
                                                            <option value="<?php echo $cv->prefix ?>"> <?php echo $cv->country . ' (+' . $cv->prefix . ')' ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 p-r-sm">
                                                    <select class="form-control" data-plugin="select2" id="opsel" name="opsel[]" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center\'>'+data.text+'</div>';return $(nstr); } }">
                                                        <option selected value="0"> <?php echo SCTEXT('All Operators') ?> </option>
                                                        <?php foreach ($data['opdata'] as $op) { ?>
                                                            <option class="opitem" data-cpre="<?php echo $op->country_code ?>" value="<?php echo base64_encode($op->brand . '|' . $op->country_iso) ?>"> <?php echo $op->brand . ' (' . $op->country_iso . ')' ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <span class="help-block"><?php echo SCTEXT('Select the countries and operators for which you want to request sender ID.') ?></span>
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

                                    <?php if (Doo::conf()->sender_noc == 1) { ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Required Documents') ?>:</label>
                                            <div class="col-md-8">
                                                <select id="country" name="country" data-plugin="select2">
                                                    <option value="0">- <?php echo SCTEXT('Select Country') ?> -</option>
                                                    <?php foreach ($data['cvdata'] as $cv) { ?>
                                                        <option value="<?php echo $cv->id ?>"><?php echo $cv->country . ' ( ' . $cv->prefix . ' )' ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="help-block text-danger"><?php echo SCTEXT('Based on the country of use, follow the regulations below and upload required documents. For multiple countries, provide NOC for all the countries you want to use.') ?></span>
                                                <div id="covregbox" class="m-t-sm">

                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


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
        <style>
            select option[disabled] {
                display: none;
            }

            .select2-container--default .select2-results__option[aria-disabled=true] {
                display: none;
            }
        </style>