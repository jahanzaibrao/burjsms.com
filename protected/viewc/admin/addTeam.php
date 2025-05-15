<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add Staff Team') ?><small><?php echo SCTEXT('add a new team') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="team_form" action="">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Team Name') ?>:</label>
                                        <div class="col-md-8">
                                            <input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter team name') ?>..." maxlength="100" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Description') ?>:</label>
                                        <div class="col-md-8">
                                            <textarea name="tdesc" placeholder="<?php echo SCTEXT('give a brief details of team responsibilities etc.') ?> ." class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Color theme') ?>:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="theme" data-plugin="select2" data-options="{templateResult: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span> '+data.text+'</span>');},templateSelection: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span>'+data.text+'</span>');} }">
                                                <option value="info"> <?php echo SCTEXT('Blue Theme') ?></option>
                                                <option value="success"> <?php echo SCTEXT('Green Theme') ?></option>
                                                <option value="primary"> <?php echo SCTEXT('Royal Blue Theme') ?></option>
                                                <option value="warning"> <?php echo SCTEXT('Yellow Theme') ?></option>
                                                <option value="danger"> <?php echo SCTEXT('Red Theme') ?></option>
                                                <option value="pink"> <?php echo SCTEXT('Pink Theme') ?></option>
                                                <option value="purple"> <?php echo SCTEXT('Purple Theme') ?></option>
                                                <option value="inverse"> <?php echo SCTEXT('Dark Theme') ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Select Roles') ?>:</label>
                                        <div class="col-md-8 trights">

                                            <div class="widget">
                                                <header class="widget-header">
                                                    <h4 class="widget-title"><?php echo SCTEXT('Administration Rights') ?>
                                                        <div class="pull-right">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="toggleAdmin" class="toggle" data-group="admin" checked="checked" type="checkbox">
                                                                <label for="toggleAdmin"><?php echo SCTEXT('check/uncheck all') ?></label>
                                                            </div>

                                                        </div>

                                                    </h4>
                                                </header>
                                                <hr class="widget-separator">
                                                <div class="widget-body">
                                                    <div id="admin_roles" class="row no-gutter">
                                                        <div class="col-sm-4">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-kannel" name="perms[admin][kannel]" checked="checked" type="checkbox">
                                                                <label for="admin-kannel"><?php echo SCTEXT('Kannel Monitor') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-sysmon" name="perms[admin][sysmon]" checked="checked" type="checkbox">
                                                                <label for="admin-sysmon"><?php echo SCTEXT('System Monitor') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-smppmon" name="perms[admin][smppmon]" checked="checked" type="checkbox">
                                                                <label for="admin-smppmon"><?php echo SCTEXT('SMPP Server Monitor') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-powergrid" name="perms[admin][powergrid]" checked="checked" type="checkbox">
                                                                <label for="admin-powergrid"><?php echo SCTEXT('Power Grid') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-smpp" name="perms[admin][smpp]" checked="checked" type="checkbox">
                                                                <label for="admin-smpp"><?php echo SCTEXT('Manage SMPP') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-routes" name="perms[admin][routes]" checked="checked" type="checkbox">
                                                                <label for="admin-routes"><?php echo SCTEXT('Manage Routes') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-tlv" name="perms[admin][tlv]" checked="checked" type="checkbox">
                                                                <label for="admin-tlv"><?php echo SCTEXT('Manage SMPP TLV') ?></label>
                                                            </div>

                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-cpre" name="perms[admin][cpre]" checked="checked" type="checkbox">
                                                                <label for="admin-cpre"><?php echo SCTEXT('Countries & Prefix Mgmt.') ?></label>
                                                            </div>

                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-fdlr" name="perms[admin][fdlr]" checked="checked" type="checkbox">
                                                                <label for="admin-fdlr"><?php echo SCTEXT('Fake DLR Templates') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-web" name="perms[admin][website]" checked="checked" type="checkbox">
                                                                <label for="admin-web"><?php echo SCTEXT('Manage Whitelabel Website') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-hlr" name="perms[admin][hlr]" checked="checked" type="checkbox">
                                                                <label for="admin-hlr"><?php echo SCTEXT('HLR Channels Management') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-ndnc" name="perms[admin][ndnc]" checked="checked" type="checkbox">
                                                                <label for="admin-ndnc"><?php echo SCTEXT('NDNC Management') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-mnpdb" name="perms[admin][mnpdb]" checked="checked" type="checkbox">
                                                                <label for="admin-mnpdb"><?php echo SCTEXT('MNP Database Management') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-announce" name="perms[admin][announce]" checked="checked" type="checkbox">
                                                                <label for="admin-announce"><?php echo SCTEXT('Announcements') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-blockip" name="perms[admin][blockip]" checked="checked" type="checkbox">
                                                                <label for="admin-blockip"><?php echo SCTEXT('Global IP Blocking') ?></label>
                                                            </div>

                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-logs" name="perms[admin][logs]" checked="checked" type="checkbox">
                                                                <label for="admin-logs"><?php echo SCTEXT('Server Logs') ?></label>
                                                            </div>


                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-sid" name="perms[admin][sid]" checked="checked" type="checkbox">
                                                                <label for="admin-sid"><?php echo SCTEXT('Sender ID Approval') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-tmp" name="perms[admin][tmp]" checked="checked" type="checkbox">
                                                                <label for="admin-tmp"><?php echo SCTEXT('SMS Template Approval') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-spamkw" name="perms[admin][spamkw]" checked="checked" type="checkbox">
                                                                <label for="admin-spamkw"><?php echo SCTEXT('SPAM Keywords Management') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-spam" name="perms[admin][spam]" checked="checked" type="checkbox">
                                                                <label for="admin-spam"><?php echo SCTEXT('SPAM Campaign Management') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-support" name="perms[admin][support]" checked="checked" type="checkbox">
                                                                <label for="admin-support"><?php echo SCTEXT('Support Tickets') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-creditplan" name="perms[admin][creditplan]" checked="checked" type="checkbox">
                                                                <label for="admin-creditplan"><?php echo SCTEXT('Credit Based SMS Plans') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-mccmncplan" name="perms[admin][mccmncplan]" checked="checked" type="checkbox">
                                                                <label for="admin-mccmncplan"><?php echo SCTEXT('MCCMNC Based SMS Plans') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="admin-wabaplan" name="perms[admin][wabaplan]" checked="checked" type="checkbox">
                                                                <label for="admin-wabaplan"><?php echo SCTEXT('WhatsApp Plans') ?></label>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>


                                            <div class="widget">
                                                <header class="widget-header">
                                                    <h4 class="widget-title"><?php echo SCTEXT('User Management Rights') ?>
                                                        <div class="pull-right">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="toggleUser" class="toggle" data-group="user" checked="checked" type="checkbox">
                                                                <label for="toggleUser"><?php echo SCTEXT('check/uncheck all') ?></label>
                                                            </div>

                                                        </div>

                                                    </h4>
                                                </header>
                                                <hr class="widget-separator">
                                                <div class="widget-body">
                                                    <div id="user_roles" class="row no-gutter">
                                                        <div class="col-sm-6">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="user-add" name="perms[user][add]" checked="checked" type="checkbox">
                                                                <label for="user-add"><?php echo SCTEXT('Add New Clients/Resellers') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="user-transaction" name="perms[user][transaction]" checked="checked" type="checkbox">
                                                                <label for="user-transaction"><?php echo SCTEXT('Add/Deduct Credits') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="user-set" name="perms[user][set]" checked="checked" type="checkbox">
                                                                <label for="user-set"><?php echo SCTEXT('Modify Settings/Permissions') ?></label>
                                                            </div>


                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="user-logs" name="perms[user][logs]" checked="checked" type="checkbox">
                                                                <label for="user-logs"><?php echo SCTEXT('View User logs (Credits/Refunds etc.)') ?></label>
                                                            </div>

                                                            <div class="checkbox checkbox-primary">
                                                                <input id="user-permgroups" name="perms[user][permgroups]" checked="checked" type="checkbox">
                                                                <label for="user-permgroups"><?php echo SCTEXT('User Permission Groups') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="user-ssl" name="perms[user][ssl]" checked="checked" type="checkbox">
                                                                <label for="user-ssl"><?php echo SCTEXT('SSL Management') ?></label>
                                                            </div>

                                                        </div>


                                                    </div>

                                                </div>
                                            </div>

                                            <hr>
                                            <div class="uperms">
                                                <div class="col-md-12">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Messaging') ?>
                                                                <div class="pull-right">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="toggleMsg" class="toggle" data-group="messaging" checked="checked" type="checkbox">
                                                                        <label for="toggleMsg"><?php echo SCTEXT('check/uncheck all') ?></label>
                                                                    </div>

                                                                </div>
                                                            </h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div id="messaging_roles" class="row no-gutter">
                                                                <div class="col-sm-4">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="gui_sms" name="perms[messaging][gui]" checked="checked" type="checkbox">
                                                                        <label for="gui_sms"><?php echo SCTEXT('Campaign GUI') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="api_sms" name="perms[messaging][http_sms_api]" checked="checked" type="checkbox">
                                                                        <label for="api_sms"><?php echo SCTEXT('HTTP API') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="smpp_sms" name="perms[messaging][smpp_sms]" checked="checked" type="checkbox">
                                                                        <label for="smpp_sms"><?php echo SCTEXT('SMPP API') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="sch_sms" name="perms[messaging][schedule]" checked="checked" type="checkbox">
                                                                        <label for="sch_sms"><?php echo SCTEXT('Scheduled SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="sch_smart_sms" name="perms[messaging][smart_schedule]" checked="checked" type="checkbox">
                                                                        <label for="sch_smart_sms"><?php echo SCTEXT('Smart Scheduling') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="2way_cmp" name="perms[messaging][campaigns]" checked="checked" type="checkbox">
                                                                        <label for="2way_cmp"><?php echo SCTEXT('Campaigns (2-Way)') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="spam_status" name="perms[messaging][allow_spam]" checked="checked" type="checkbox">
                                                                        <label for="spam_status"><?php echo SCTEXT('Disable Spam Check') ?></label>
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="flash_sms" name="perms[messaging][flash_sms]" checked="checked" type="checkbox">
                                                                        <label for="flash_sms"><?php echo SCTEXT('Send Flash SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="pers_sms" name="perms[messaging][personalized_sms]" checked="checked" type="checkbox">
                                                                        <label for="pers_sms"><?php echo SCTEXT('Personalized SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="unicode_sms" name="perms[messaging][unicode_sms]" checked="checked" type="checkbox">
                                                                        <label for="unicode_sms"><?php echo SCTEXT('Unicode SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="vcard_sms" name="perms[messaging][vcard_sms]" checked="checked" type="checkbox">
                                                                        <label for="vcard_sms"><?php echo SCTEXT('Send VCards') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="wap_push" name="perms[messaging][wap_push]" checked="checked" type="checkbox">
                                                                        <label for="wap_push"><?php echo SCTEXT('Send WAP Push') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="2way_inbox" name="perms[messaging][inbox]" checked="checked" type="checkbox">
                                                                        <label for="2way_inbox"><?php echo SCTEXT('Inbox (2-Way)') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="open_temp" name="perms[messaging][open_template]" checked="checked" type="checkbox">
                                                                        <label for="open_temp"><?php echo SCTEXT('Allow All Templates') ?></label>
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="manage_contacts" name="perms[messaging][contacts]" checked="checked" type="checkbox">
                                                                        <label for="manage_contacts"><?php echo SCTEXT('Manage Contacts') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="manage_sender" name="perms[messaging][sender]" checked="checked" type="checkbox">
                                                                        <label for="manage_sender"><?php echo SCTEXT('Add more Sender ID') ?></label>
                                                                    </div>

                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="manage_templates" name="perms[messaging][templates]" checked="checked" type="checkbox">
                                                                        <label for="manage_templates"><?php echo SCTEXT('SMS Templates') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="tinyurl" name="perms[messaging][tinyurl]" checked="checked" type="checkbox">
                                                                        <label for="tinyurl"><?php echo SCTEXT('URL Shortener') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="media_links" name="perms[messaging][media_links]" checked="checked" type="checkbox">
                                                                        <label for="media_links"><?php echo SCTEXT('Media Links in SMS') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="tlv" name="perms[messaging][tlv]" checked="checked" type="checkbox">
                                                                        <label for="tlv"><?php echo SCTEXT('Manage TLV Parameters') ?></label>
                                                                    </div>
                                                                    <div class="checkbox checkbox-primary">
                                                                        <input id="vmn" name="perms[messaging][vmn]" checked="checked" type="checkbox">
                                                                        <label for="vmn"><?php echo SCTEXT('Modify Assigned VMN Settings') ?></label>
                                                                    </div>


                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Addon Features') ?>
                                                            </h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="hlr" name="perms[addons][hlr]" checked="checked" type="checkbox">
                                                                <label for="hlr"><?php echo SCTEXT('HLR Lookups') ?></label>
                                                            </div>

                                                            <div class="checkbox checkbox-primary">
                                                                <input id="otp_api" name="perms[addons][otp_api]" checked="checked" type="checkbox">
                                                                <label for="otp_api"><?php echo SCTEXT('OTP API') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="whatsapp" name="perms[addons][whatsapp]" checked="checked" type="checkbox">
                                                                <label for="whatsapp"><?php echo SCTEXT('WhatsApp Business Messaging') ?></label>
                                                            </div>
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="rcs" name="perms[addons][rcs]" checked="checked" type="checkbox">
                                                                <label for="rcs"><?php echo SCTEXT('Google RCS') ?></label>
                                                            </div>
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