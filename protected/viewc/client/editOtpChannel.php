<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Edit OTP Channel') ?><small><?php echo SCTEXT('modify channel parameters for OTP API') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <form class="form-horizontal" method="post" id="otpch_form" action="">
                                    <input type="hidden" name="otpchid" value="<?php echo $data['channel']->id ?>">
                                    <input type="hidden" name="otptlvs" id="otptlvs" value='<?php echo $data['channel']->tlv_values ?>'>
                                    <?php $tlvs = json_decode($data['channel']->tlv_values); ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Channel Title') ?>:</label>
                                        <div class="col-md-8">
                                            <input value="<?php echo $data['channel']->title ?>" id="otph_title" name="title" class="form-control" type="text" placeholder="enter a title to easily recognize..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Route for OTP') ?>:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" data-plugin="select2" name="otproute" id="otproute">
                                                <?php foreach ($_SESSION['credits']['routes'] as $rt) { ?>
                                                    <option data-tlvs='<?php echo $rt['tlv_ids'] ?>' <?php if ($data['channel']->route_id == $rt['id']) { ?> selected <?php } ?> value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="sid_box">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID') ?>:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" data-plugin="select2" name="otpsender">
                                                <?php foreach ($data['sids'] as $sid) { ?>
                                                    <option <?php if ($data['channel']->sender == $sid->sender_id) { ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Optional Template') ?>:</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" name="otptemplate" placeholder="define a message template for OTP.."><?php echo htmlspecialchars_decode($data['channel']->template) ?></textarea>
                                            <span class="help-block"><?php echo SCTEXT('Use the following variable format which will be replaced by the one-time password') ?>: <kbd class="bg-white text-primary"><b>{{otp}}</b></kbd></span>
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