    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Permission Group') ?><small><?php echo SCTEXT('modify existing group of user permissions') ?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <!-- start content -->
                                    <form class="form-horizontal" method="post" id="upermgrp_form" action="">
                                        <input type="hidden" name="pgid" id="pgid" value="<?php echo $data['pdata']->id ?>" />
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Group Name') ?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" name="pgname" id="pgname" class="form-control" placeholder="<?php echo SCTEXT('enter permission group name') ?>..." maxlength="100" value="<?php echo $data['pdata']->title ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Description:</label>
                                            <div class="col-md-8">
                                                <textarea name="pdesc" placeholder="give a brief details of assigned permissions etc. ." class="form-control"><?php echo $data['pdata']->description ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Color theme') ?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="theme" data-plugin="select2" data-options="{templateResult: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span> '+data.text+'</span>');},templateSelection: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span>'+data.text+'</span>');} }">
                                                    <option <?php echo $data['pdata']->color_scheme == 'info' ? 'selected' : '' ?> value="info"> <?php echo SCTEXT('Blue Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'success' ? 'selected' : '' ?> value="success"> <?php echo SCTEXT('Green Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'primary' ? 'selected' : '' ?> value="primary"> <?php echo SCTEXT('Royal Blue Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'warning' ? 'selected' : '' ?> value="warning"> <?php echo SCTEXT('Yellow Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'danger' ? 'selected' : '' ?> value="danger"> <?php echo SCTEXT('Red Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'pink' ? 'selected' : '' ?> value="pink"> <?php echo SCTEXT('Pink Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'purple' ? 'selected' : '' ?> value="purple"> <?php echo SCTEXT('Purple Theme') ?></option>
                                                    <option <?php echo $data['pdata']->color_scheme == 'inverse' ? 'selected' : '' ?> value="inverse"> <?php echo SCTEXT('Dark Theme') ?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <?php
                                        $perms = json_decode($data['pdata']->permissions, true);
                                        ?>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Select Permissions') ?>:</label>
                                            <div class="col-md-8 uperms">
                                                <div class="col-md-12">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Messaging') ?>
                                                            </h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div id="admin_roles" class="row no-gutter">
                                                                <div class="col-sm-4">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="gui_sms" name="perms[messaging][gui]" <?php echo $perms['messaging']['gui'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="gui_sms"><?php echo SCTEXT('Campaign GUI') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="api_sms" name="perms[messaging][http_sms_api]" <?php echo $perms['messaging']['http_sms_api'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="api_sms"><?php echo SCTEXT('HTTP API') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="smpp_sms" name="perms[messaging][smpp_sms]" <?php echo $perms['messaging']['smpp_sms'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="smpp_sms"><?php echo SCTEXT('SMPP API') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="sch_sms" name="perms[messaging][schedule]" <?php echo $perms['messaging']['schedule'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="sch_sms"><?php echo SCTEXT('Scheduled SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="sch_smart_sms" name="perms[messaging][smart_schedule]" <?php echo $perms['messaging']['smart_schedule'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="sch_smart_sms"><?php echo SCTEXT('Smart Scheduling') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="2way_cmp" name="perms[messaging][campaigns]" <?php echo $perms['messaging']['campaigns'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="2way_cmp"><?php echo SCTEXT('Campaigns (2-Way)') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="spam_status" name="perms[messaging][allow_spam]" <?php echo $perms['messaging']['allow_spam'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="spam_status"><?php echo SCTEXT('Disable Spam Check') ?></label>
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="flash_sms" name="perms[messaging][flash_sms]" <?php echo $perms['messaging']['flash_sms'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="flash_sms"><?php echo SCTEXT('Send Flash SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="pers_sms" name="perms[messaging][personalized_sms]" <?php echo $perms['messaging']['personalized_sms'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="pers_sms"><?php echo SCTEXT('Personalized SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="unicode_sms" name="perms[messaging][unicode_sms]" <?php echo $perms['messaging']['unicode_sms'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="unicode_sms"><?php echo SCTEXT('Unicode SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="vcard_sms" name="perms[messaging][vcard_sms]" <?php echo $perms['messaging']['vcard_sms'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="vcard_sms"><?php echo SCTEXT('Send VCards') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="wap_push" name="perms[messaging][wap_push]" <?php echo $perms['messaging']['wap_push'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="wap_push"><?php echo SCTEXT('Send WAP Push') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="2way_inbox" name="perms[messaging][inbox]" <?php echo $perms['messaging']['inbox'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="2way_inbox"><?php echo SCTEXT('Inbox (2-Way)') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="open_temp" name="perms[messaging][open_template]" <?php echo $perms['messaging']['open_template'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="open_temp"><?php echo SCTEXT('Allow All Templates') ?></label>
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="manage_contacts" name="perms[messaging][contacts]" <?php echo $perms['messaging']['contacts'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="manage_contacts"><?php echo SCTEXT('Manage Contacts') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="manage_sender" name="perms[messaging][sender]" <?php echo $perms['messaging']['sender'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="manage_sender"><?php echo SCTEXT('Add more Sender ID') ?></label>
                                                                    </div>

                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="manage_templates" name="perms[messaging][templates]" <?php echo $perms['messaging']['templates'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="manage_templates"><?php echo SCTEXT('SMS Templates') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="tinyurl" name="perms[messaging][tinyurl]" <?php echo $perms['messaging']['tinyurl'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="tinyurl"><?php echo SCTEXT('URL Shortener') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="media_links" name="perms[messaging][media_links]" <?php echo $perms['messaging']['media_links'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="media_links"><?php echo SCTEXT('Media Links in SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="tlv" name="perms[messaging][tlv]" <?php echo $perms['messaging']['tlv'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="tlv"><?php echo SCTEXT('Manage TLV Parameters') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="vmn" name="perms[messaging][vmn]" <?php echo $perms['messaging']['vmn'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                        <label for="vmn"><?php echo SCTEXT('Modify Assigned VMN Settings') ?></label>
                                                                    </div>


                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Addon Features') ?>
                                                            </h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="hlr" name="perms[addons][hlr]" <?php echo $perms['addons']['hlr'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="hlr"><?php echo SCTEXT('HLR Lookups') ?></label>
                                                            </div>

                                                            <div class="checkbox checkbox-primary">
                                                                <input id="otp_api" name="perms[addons][otp_api]" <?php echo $perms['addons']['otp_api'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="otp_api"><?php echo SCTEXT('OTP API') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="whatsapp" name="perms[addons][whatsapp]" <?php echo $perms['addons']['whatsapp'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="whatsapp"><?php echo SCTEXT('WhatsApp Business Messaging') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="rcs" name="perms[addons][rcs]" <?php echo $perms['addons']['rcs'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="rcs"><?php echo SCTEXT('Google RCS') ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Refund Rules') ?>
                                                            </h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <?php foreach ($data['refunds'] as $ref) { ?>
                                                                <div class="checkbox checkbox-primary">
                                                                    <input id="ref_<?php echo $ref->id ?>" name="perms[refunds][<?php echo $ref->id ?>]" <?php echo $perms['refunds'][$ref->id] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                    <label for="ref_<?php echo $ref->id ?>"><?php echo $ref->title ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Reseller Permissions') ?>
                                                            </h4>
                                                            <span><?php echo SCTEXT('only applicable on reseller accounts') ?></span>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="res_mon" name="perms[reseller][user_monitor]" <?php echo $perms['reseller']['user_monitor'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="res_mon"><?php echo SCTEXT('System Monitor') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="res_wl" name="perms[reseller][whitelabel]" <?php echo $perms['reseller']['whitelabel'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="res_wl"><?php echo SCTEXT('Whitelabel Website') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="tgw_plugin" name="perms[reseller][tgw_plugin]" <?php echo $perms['reseller']['tgw_plugin'] == "on" ? 'checked="checked"' : '' ?> type="checkbox">
                                                                <label for="tgw_plugin"><?php echo SCTEXT('Test Our Gateway Widget') ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>





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