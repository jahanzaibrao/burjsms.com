<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Edit Profile') ?><small><?php echo SCTEXT('make changes to your profile information') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <div class="m-b-lg nav-tabs-horizontal">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" <?php if ($data['tabpage'] == '') { ?> class="active" <?php } ?>><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab" aria-expanded="true"><?php echo SCTEXT('Profile Information') ?></a></li>
                                        <li role="presentation" <?php if ($data['tabpage'] == 'tab-2') { ?> class="active" <?php } ?>><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab" aria-expanded="false"><?php echo SCTEXT('Change Password') ?></a></li>
                                        <li role="presentation" <?php if ($data['tabpage'] == 'tab-3') { ?> class="active" <?php } ?>><a href="#tab-3" aria-controls="tab-3" role="tab" data-toggle="tab" aria-expanded="false"><?php echo SCTEXT('Company Information') ?> (Billing)</a></li>
                                        <?php if (isset($_SESSION['permissions']['addons']['whatsapp']) && Doo::conf()->whatsapp == 1) { ?>
                                            <li role="presentation" <?php if ($data['tabpage'] == 'tab-4') { ?> class="active" <?php } ?>><a href="#tab-4" aria-controls="tab-4" role="tab" data-toggle="tab" aria-expanded="false"><?php echo SCTEXT('WhatsApp Business Profile') ?></a></li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content p-md">
                                        <div role="tabpanel" class="tab-pane fade <?php if ($data['tabpage'] == '') { ?> active in<?php } ?> " id="tab-1">
                                            <form method="post" id="upfrm" action="" enctype="multipart/form-data">
                                                <div class="col-md-12 ">
                                                    <div class="text-center sld-banners col-md-2 col-sm-2 gallery-item p-r-sm">
                                                        <div class="thumb circle">
                                                            <img src="<?php echo $data['uinfo']->avatar ?>" class="img-responsive" />
                                                            <hr>
                                                            <input id="u-img" class="hidden" name="uavatar" type="file">
                                                            <label for="u-img" class="label label-md label-primary pointer"><i class="fa fa-lg fa-upload"></i> &nbsp;&nbsp;<?php echo SCTEXT('Change Image') ?></label>
                                                            <span class="help-block m-b-0"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-horizontal col-md-10 col-sm-10">

                                                        <div class="m-t-sm">

                                                            <div class="form-group">
                                                                <label class=" control-label col-md-2">
                                                                    <?php echo SCTEXT('Name') ?>:
                                                                </label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" id="uname" name="uname" placeholder="<?php echo SCTEXT('Enter your name') ?> ..." value="<?php echo $data['uinfo']->name ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group m-b-sm">
                                                                <label class=" control-label col-md-2">
                                                                    <?php echo SCTEXT('Phone No.') ?>:
                                                                </label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" id="uphn" name="uphn" placeholder="<?php echo SCTEXT('Enter your mobile number') ?> ..." value="<?php echo $data['uinfo']->mobile ?>">
                                                                    <span id="v-phn" class="val-icon"></span>
                                                                    <span class="help-block clearfix text-info m-b-0">
                                                                        <?php if ($data['uinfo']->mobile_verified == '1') { ?>
                                                                            <span class="label label-md label-success">
                                                                                <i class="fa fa-lg fa-check-circle m-r-xs"></i><?php echo SCTEXT('Verified') ?>
                                                                            </span>

                                                                        <?php } else { ?>
                                                                            <span class="label label-md label-warning">
                                                                                <i class="fa fa-lg fa-exclamation-circle m-r-xs"></i><?php echo SCTEXT('Unverified') ?>
                                                                            </span>
                                                                            <span style="float: right;">
                                                                                <a id="vphn" class="btn btn-xs btn-info"><?php echo SCTEXT('Verify') ?></a>
                                                                            </span>
                                                                        <?php } ?>

                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group m-b-sm">
                                                                <label class=" control-label col-md-2">
                                                                    <?php echo SCTEXT('Email') ?>:
                                                                </label>
                                                                <div class="col-md-8 abs-ctr">
                                                                    <input type="text" class="form-control" id="uemail" name="uemail" placeholder="<?php echo SCTEXT('Enter your email') ?> ..." value="<?php echo $data['uinfo']->email ?>">
                                                                    <span id="v-email" class="val-icon"></span>
                                                                    <span class="help-block clearfix text-info m-b-0">
                                                                        <?php if ($data['uinfo']->email_verified == '1') { ?>
                                                                            <span class="label label-md label-success">
                                                                                <i class="fa fa-lg fa-check-circle m-r-xs"></i><?php echo SCTEXT('Verified') ?>
                                                                            </span>

                                                                        <?php } else { ?>
                                                                            <span class="label label-md label-warning">
                                                                                <i class="fa fa-lg fa-exclamation-circle m-r-xs"></i><?php echo SCTEXT('Unverified') ?>
                                                                            </span>
                                                                            <span style="float: right;">
                                                                                <a id="vmail" class="btn btn-xs btn-info"><?php echo SCTEXT('Verify') ?></a>
                                                                            </span>
                                                                        <?php } ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="gender" value="m">

                                                            <div class="form-group">
                                                                <label class=" control-label col-md-2">

                                                                </label>
                                                                <div class="col-md-8 text-right">
                                                                    <button id="saveupfrm" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                                                </div>
                                                            </div>

                                                        </div>


                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </form>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade <?php if ($data['tabpage'] == 'tab-2') { ?> active in<?php } ?>" id="tab-2">
                                            <form class="form-horizontal" method="post" id="cpfrm" action="">
                                                <div class="form-group">
                                                    <label class=" control-label col-md-3">
                                                        <?php echo SCTEXT('Enter Old Password') ?>:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="password" class="form-control" placeholder="<?php echo SCTEXT('enter your password') ?> ..." name="oldpass" id="oldpass" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" control-label col-md-3">
                                                        <?php echo SCTEXT('Enter New Password') ?>:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="password" data-strength="<?php echo Doo::conf()->password_strength ?>" class="form-control" placeholder="<?php echo SCTEXT('enter new password') ?> ..." name="newpass1" id="newpass1" maxlength="100" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" control-label col-md-3">
                                                        <?php echo SCTEXT('Re-type New Password') ?>:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="password" data-strength="<?php echo Doo::conf()->password_strength ?>" class="form-control" placeholder="<?php echo SCTEXT('verify new password') ?> ..." name="newpass2" id="newpass2" maxlength="100" />
                                                        <span id="pass-err" class="help-block text-danger"></span>
                                                        <span id="pass-help" class="help-block text-primary">
                                                            <?php switch (Doo::conf()->password_strength) {
                                                                case 'weak':
                                                                    echo SCTEXT('Password length should be minimum 6 characters.');
                                                                    break;

                                                                case 'average':
                                                                    echo SCTEXT('Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.');
                                                                    break;

                                                                case 'strong':
                                                                    echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.');
                                                                    break;
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" control-label col-md-3">

                                                    </label>
                                                    <div class="col-md-8 text-right">
                                                        <button id="savecpfrm" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade <?php if ($data['tabpage'] == 'tab-3') { ?> active in<?php } ?>" id="tab-3">
                                            <form class="form-horizontal" method="post" id="cifrm" action="">
                                                <div class="col-md-12 clearfix">
                                                    <?php if ($_SESSION['user']['group'] == 'client') { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Name') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input id="cname" class="form-control" name="cname" type="text" value="<?php echo $data['cinfo']->c_name ?>" placeholder="<?php echo SCTEXT('enter company name') ?> ...">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2">Reg. No.:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_regno ?>" name="crno" placeholder="<?php echo SCTEXT('enter registration number') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Address') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <textarea class="form-control" name="caddr" placeholder="<?php echo SCTEXT('enter full address of your business') ?> ..."><?php echo $data['cinfo']->c_address ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Phone') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input id="cphn" value="<?php echo $data['cinfo']->c_phone ?>" class="form-control" name="cphn" placeholder="<?php echo SCTEXT('enter company phone number') ?> ..." type="text">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Email') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input id="cmail" value="<?php echo $data['cinfo']->c_email ?>" name="cmail" placeholder="<?php echo SCTEXT('enter email id for your business') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('VAT No.') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_vat ?>" name="cvat" placeholder="<?php echo SCTEXT('enter VAT no. if available') ?>" class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Service Tax') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_stax ?>" name="cstax" placeholder="<?php echo SCTEXT('enter service tax registration no. if available') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('GST No.') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_gst ?>" name="cgst" placeholder="<?php echo SCTEXT('enter GST registration no. if available') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group p-t-lg">
                                                                <label class="control-label col-md-2"></label>
                                                                <div class="col-md-8">
                                                                    <button id="savecifrm" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Name') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input id="cname" class="form-control" name="cname" type="text" value="<?php echo $data['cinfo']->c_name ?>" placeholder="<?php echo SCTEXT('enter company name') ?> ...">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2">Reg. No.:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_regno ?>" name="crno" placeholder="<?php echo SCTEXT('enter registration number') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Address') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <textarea class="form-control" name="caddr" placeholder="<?php echo SCTEXT('enter full address of your business') ?> ..."><?php echo $data['cinfo']->c_address ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Phone') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input id="cphn" value="<?php echo $data['cinfo']->c_phone ?>" class="form-control" name="cphn" placeholder="<?php echo SCTEXT('enter company phone number') ?> ..." type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Email') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input id="cmail" value="<?php echo $data['cinfo']->c_email ?>" name="cmail" placeholder="<?php echo SCTEXT('enter email id for your business') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('VAT No.') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_vat ?>" name="cvat" placeholder="<?php echo SCTEXT('enter VAT no. if available') ?>" class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('Service Tax') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_stax ?>" name="cstax" placeholder="<?php echo SCTEXT('enter service tax registration no. if available') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><?php echo SCTEXT('GST No.') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <input value="<?php echo $data['cinfo']->c_gst ?>" name="cgst" placeholder="<?php echo SCTEXT('enter GST registration no. if available') ?> ..." class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?php $allowed_pg = explode(",", Doo::conf()->allowed_payments); ?>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3"><?php echo SCTEXT('Payments') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <select id="userpg" name="userpg" data-plugin="select2" class="form-control">
                                                                        <option value="" data-link="">- None -</option>
                                                                        <?php foreach ($data['payments'] as $pg) {
                                                                            if (in_array($pg['id'], $allowed_pg)) {
                                                                        ?>
                                                                                <option <?php if ($pg['id'] == $data['userpg']['channel']) { ?> selected <?php } ?> data-link="<?php echo $pg['link'] ?>" value="<?php echo $pg['id'] ?>"><?php echo $pg['name'] ?></option>
                                                                        <?php }
                                                                        } ?>
                                                                    </select>
                                                                    <span class="help-block" id="pgdesc"></span>
                                                                </div>
                                                            </div>
                                                            <div class="pgboxes" id="paypal-tab">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Email') ?>:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo $data['userpg']['email'] ?>" name="cpaypal" placeholder="<?php echo SCTEXT('enter Paypal email address') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Client ID:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo base64_decode($data['userpg']['clientid']) ?>" name="pclid" placeholder="<?php echo SCTEXT('leave blank to disable Paypal payments') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Secret:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo base64_decode($data['userpg']['authkey']) ?>" name="pauthk" placeholder="<?php echo SCTEXT('leave blank to disable Paypal payments') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="pgboxes" id="stripe-tab">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Publishable Key:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo base64_decode($data['userpg']['publishable_key']) ?>" name="publishable_key" placeholder="<?php echo SCTEXT('leave blank to disable payments') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Secret Key:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo base64_decode($data['userpg']['secret_key']) ?>" name="secret_key" placeholder="<?php echo SCTEXT('leave blank to disable payments') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="pgboxes" id="paystack-tab">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Public Key:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo base64_decode($data['userpg']['public_key']) ?>" name="public_key" placeholder="<?php echo SCTEXT('leave blank to disable payments') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Secret Key:</label>
                                                                    <div class="col-md-8">
                                                                        <input value="<?php echo base64_decode($data['userpg']['secret_key']) ?>" name="secret_key_ps" placeholder="<?php echo SCTEXT('leave blank to disable payments') ?> ..." class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-md-3"><?php echo SCTEXT('Offline Payment') ?>:</label>
                                                                <div class="col-md-8">
                                                                    <textarea class="form-control" name="offline_payment" placeholder="<?php echo SCTEXT('enter bank details for offline payment') ?> ..."><?php echo $data['userpg']['bank_details'] ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group p-t-lg">
                                                                <label class="control-label col-md-2"></label>
                                                                <div class="col-md-8">
                                                                    <button id="savecifrm" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </form>
                                        </div>
                                        <?php if (isset($_SESSION['permissions']['addons']['whatsapp']) && Doo::conf()->whatsapp == 1) { ?>
                                            <div role="tabpanel" class="tab-pane fade <?php if ($data['tabpage'] == 'tab-4') { ?> active in<?php } ?>" id="tab-4">
                                                <?php if (isset($data['waba_profiles']) && sizeof($data['waba_profiles']) > 0) { ?>
                                                    <div class="col-md-12 clearfix">
                                                        <div class="col-md-4 pull-right">
                                                            <div class="panel-heading bg-theme1" style="min-height:77px;">
                                                                <select id="waba_phnsel" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[1]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[1]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                                                                    <?php foreach ($data['waba_profiles'] as $wprofile) { ?>
                                                                        <option value="<?php echo $wprofile->phone_id ?>" title="<?php echo $wprofile->verified_name ?>"><?php echo (isset($wprofile->bp_profile_picture) && trim($wprofile->bp_profile_picture) != "" ? $wprofile->bp_profile_picture : 'https://placehold.co/200') . '|' . $wprofile->display_phone . '|primary|' . $wprofile->bp_email ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 p-t-sm">
                                                        <form method="post" id="mafrm" action="">
                                                            <?php $firstprofile = $data['waba_profiles'][0]; ?>
                                                            <input type="hidden" id="phoneid" value="<?php echo $firstprofile->phone_id ?>">
                                                            <div class="col-md-12 ">
                                                                <div class="text-center sld-banners col-md-3 col-sm-3 gallery-item p-r-sm">
                                                                    <div class="thumb circle">
                                                                        <img id="bp-dp" src="<?php echo (isset($firstprofile->bp_profile_picture) && trim($firstprofile->bp_profile_picture) != "" ? $firstprofile->bp_profile_picture : 'https://placehold.co/200') ?>" class="img-responsive">
                                                                    </div>
                                                                </div>
                                                                <div class="form-horizontal col-md-9 col-sm-9">
                                                                    <div class="m-t-sm">
                                                                        <div class="form-group">
                                                                            <label class=" control-label col-md-3">
                                                                                WABA ID
                                                                            </label>
                                                                            <div class="col-md-8 p-t-xs">
                                                                                <span class="text-dark"><?php echo $firstprofile->waba_id ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class=" control-label col-md-3">
                                                                                Verified Name
                                                                            </label>
                                                                            <div class="col-md-8 p-t-xs">
                                                                                <span id="bp-name" class="text-dark"><?php echo $firstprofile->verified_name ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class=" control-label col-md-3">
                                                                                Display Phone
                                                                            </label>
                                                                            <div class="col-md-8 p-t-xs">
                                                                                <span id="bp-phone" class="text-dark"><?php echo $firstprofile->display_phone ?></span>
                                                                                <span id="bp-quality"><span class="m-l-sm label label-xs <?php echo $firstprofile->quality == "GREEN" ? 'label-success' : 'label-danger'; ?>">Quality: <?php echo $firstprofile->quality ?></span></span>

                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class=" control-label col-md-3">
                                                                                About
                                                                            </label>
                                                                            <div class="col-md-8">
                                                                                <input type="text" class="form-control" id="bp_about" placeholder="A brief tagline ..." value="<?php echo $firstprofile->bp_about ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class=" control-label col-md-3">
                                                                                Description
                                                                            </label>
                                                                            <div class="col-md-8">
                                                                                <input type="text" class="form-control" id="bp_desc" placeholder="A brief description ..." value="<?php echo $firstprofile->bp_description ?>">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class=" control-label col-md-3">
                                                                            </label>
                                                                            <div class="col-md-8 text-right">
                                                                                <button id="savewabaprofile" class="btn btn-primary" type="button">Save changes</button>
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>

                                                        </form>
                                                    </div>

                                                <?php } else { ?>

                                                    <hr>
                                                    <h4 align="center"><?php echo SCTEXT('Connect Your WhatsApp Business Account') ?></h4>
                                                    <br>
                                                    <script>
                                                        window.fbAsyncInit = function() {
                                                            FB.init({
                                                                appId: '<?php echo Doo::conf()->wba_app_id ?>',
                                                                autoLogAppEvents: true,
                                                                xfbml: true,
                                                                version: 'v18.0'
                                                            });
                                                        };
                                                    </script>
                                                    <script async defer crossorigin="anonymous"
                                                        src="https://connect.facebook.net/en_US/sdk.js">
                                                    </script>
                                                    <script>
                                                        // Facebook Login with JavaScript SDK
                                                        function launchWhatsAppSignup() {
                                                            // Launch Facebook login
                                                            FB.login(function(response) {
                                                                if (response.authResponse) {
                                                                    const accessToken = response.authResponse.accessToken;
                                                                    //Use this token to call the debug_token API and get the shared WABA's ID
                                                                    console.log(accessToken);
                                                                    //redirect
                                                                    window.location = "<?php echo Doo::conf()->APP_URL ?>finishWabaOnboarding/" + accessToken;
                                                                } else {
                                                                    console.log(response)
                                                                    console.log('User cancelled login or did not fully authorize.');
                                                                }
                                                            }, {
                                                                config_id: '<?php echo Doo::conf()->wba_config_id ?>', // configuration ID obtained in the previous step goes here
                                                                response_type: 'code', // must be set to 'code' for System User access token
                                                                override_default_response_type: true,
                                                                extras: {
                                                                    setup: {}
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                    <div align="center">
                                                        <button type="button" onclick="launchWhatsAppSignup()"
                                                            style="background-color: #1877f2; border: 0; border-radius: 4px; color: #fff; cursor: pointer; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; height: 40px; padding: 0 24px;">
                                                            Login with Facebook
                                                        </button>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>


                                    </div>
                                </div>
                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>