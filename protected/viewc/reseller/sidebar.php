<aside id="menubar" class="menubar">

    <div class="menubar-scroll">
        <div class="menubar-scroll-inner">
            <ul class="app-menu">
                <li class="<?php if ($data['page'] == 'Dashboard') { ?>active <?php } ?>"><a href="<?php echo Doo::conf()->APP_URL ?>"><i class="menu-icon zmdi zmdi-view-list zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Dashboard') ?></span> </a>

                </li>
                <li class="has-submenu <?php if ($data['page'] == 'User Management') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-accounts zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('User Accounts') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu" <?php if ($data['page'] == 'User Management') { ?>style="display:block;" <?php } ?>>

                        <li <?php echo $data['current_page'] == 'manage_users' || $data['current_page'] == 'add_user' || $data['page_family'] == 'view_account' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageUsers"><span class="menu-text"><?php echo SCTEXT('Clients/Resellers') ?></span></a></li>
                        <li <?php echo $data['current_page'] == 'manage_iusers' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageInactiveUsers"><span class="menu-text"><?php echo SCTEXT('Suspended Accounts') ?></span></a></li>

                    </ul>
                </li>

                <?php if (isset($_SESSION['permissions']['reseller']['whitelabel'])) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Website Management') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-view-web zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Website Mgmt.') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Website Management') { ?>style="display:block;" <?php } ?>>
                            <li <?php echo $data['current_page'] == 'gen_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>genWebSettings"><span class="menu-text"><?php echo SCTEXT('General Settings') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'sig_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>signupWebSettings"><span class="menu-text"><?php echo SCTEXT('Sign-up Settings') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'thm_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>themeWebSettings"><span class="menu-text"><?php echo SCTEXT('Front-end Themes') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'home_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>homeWebSettings"><span class="menu-text"><?php echo SCTEXT('Homepage') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'about_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>aboutWebSettings"><span class="menu-text"><?php echo SCTEXT('About page') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'pricing_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>pricingWebSettings"><span class="menu-text"><?php echo SCTEXT('Pricing Page') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'contact_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>contactWebSettings"><span class="menu-text"><?php echo SCTEXT('Contact Page') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'login_web_set' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>loginWebSettings"><span class="menu-text"><?php echo SCTEXT('Login Page') ?></span></a></li>

                        </ul>
                    </li>
                <?php } ?>

                <?php if (isset($_SESSION['permissions']['messaging'])) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Messaging') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-email zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Messaging') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Messaging') { ?>style="display:block;" <?php } ?>>
                            <?php if (isset($_SESSION['permissions']['messaging']['gui'])) { ?>
                                <li <?php echo $data['current_page'] == 'composeSMS' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeSMS"><span class="menu-text"><?php echo SCTEXT('Send SMS') ?></span></a></li>
                            <?php } ?>
                            <?php if (Doo::conf()->whatsapp == 1) { ?>
                                <?php if (isset($_SESSION['permissions']['addons']['whatsapp'])) { ?>
                                    <li <?php echo $data['current_page'] == 'composeWA' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeWhatsappCampaign"><span class="menu-text"><?php echo SCTEXT('WhatsApp Campaign') ?></span></a></li>
                            <?php }
                            } ?>
                            <?php if (Doo::conf()->rcs == 1) { ?>
                                <?php if (isset($_SESSION['permissions']['addons']['rcs'])) { ?>
                                    <li <?php echo $data['current_page'] == 'composeRCS' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeRCS"><span class="menu-text"><?php echo SCTEXT('RCS Message') ?></span></a></li>
                            <?php }
                            } ?>

                            <?php if (
                                isset($_SESSION['permissions']['messaging']['sender']) ||
                                isset($_SESSION['permissions']['messaging']['templates']) ||
                                isset($_SESSION['permissions']['messaging']['tlv']) ||
                                isset($_SESSION['permissions']['messaging']['tinyurl']) ||
                                isset($_SESSION['permissions']['messaging']['media_links']) ||
                                isset($_SESSION['permissions']['addons']['otp_api']) ||
                                isset($_SESSION['permissions']['addons']['whatsapp']) ||
                                isset($_SESSION['permissions']['addons']['rcs'])
                            ) { ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-plus zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Components') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                                    <ul class="submenu" <?php if ($data['page'] == 'MessagingComponents') { ?>style="display:block;" <?php } ?>>
                                        <?php if (isset($_SESSION['permissions']['messaging']['sender'])) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_sender' || $data['current_page'] == 'edit_sender' || $data['current_page'] == 'add_sender' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSenderId"><span class="menu-text"><?php echo SCTEXT('Sender ID') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['permissions']['messaging']['templates'])) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_templates' || $data['current_page'] == 'edit_template' || $data['current_page'] == 'add_template' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageTemplates"><span class="menu-text"><?php echo SCTEXT('SMS Templates') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (Doo::conf()->whatsapp == 1) { ?>
                                            <?php if (isset($_SESSION['permissions']['addons']['whatsapp'])) { ?>
                                                <li <?php echo $data['current_page'] == 'manage_wtemplates' || $data['current_page'] == 'edit_wtemplate' || $data['current_page'] == 'add_wtemplate' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageWhatsappTemplates"><span class="menu-text"><?php echo SCTEXT('WhatsApp Templates') ?></span></a></li>
                                        <?php }
                                        } ?>
                                        <?php if (Doo::conf()->rcs == 1) { ?>
                                            <?php if (isset($_SESSION['permissions']['addons']['rcs'])) { ?>
                                                <li <?php echo $data['current_page'] == 'manage_richcards' || $data['current_page'] == 'edit_richcard' || $data['current_page'] == 'add_richcard' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageRichcards"><span class="menu-text"><?php echo SCTEXT('RCS Rich Cards') ?></span></a></li>
                                        <?php }
                                        } ?>
                                        <?php if (isset($_SESSION['permissions']['messaging']['tlv'])) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_client_tlv' || $data['current_page'] == 'add_client_tlv' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageClientTlv"><span class="menu-text"><?php echo SCTEXT('TLV Parameters') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['permissions']['addons']['otp_api'])) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_otp_channels' || $data['current_page'] == 'add_otp_channel' || $data['current_page'] == 'edit_otp_channel' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageOtpChannels"><span class="menu-text"><?php echo SCTEXT('OTP Channels') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['permissions']['messaging']['tinyurl'])) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_tinyurl' || $data['current_page'] == 'add_tinyurl' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageShortUrls"><span class="menu-text"><?php echo SCTEXT('URL Shortener') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['permissions']['messaging']['media_links'])) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_media' || $data['current_page'] == 'add_media' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageCampaignMedia"><span class="menu-text"><?php echo SCTEXT('Media Files') ?></span></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (isset($_SESSION['permissions']['messaging']['contacts'])) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Contact Management') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-account-box-phone zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Contacts') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Contact Management') { ?> style="display:block;" <?php } ?>>
                            <li <?php echo $data['current_page'] == 'import_contact' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>importContacts"><span class="menu-text"><?php echo SCTEXT('Import Contacts') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'edit_contact' || $data['current_page'] == 'add_contact' || $data['current_page'] == 'manage_contacts' || $data['current_page'] == 'manage_groups' || $data['current_page'] == 'edit_group' || $data['current_page'] == 'add_group' || $data['current_page'] == 'move_contacts' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageGroups"><span class="menu-text"><?php echo SCTEXT('Contact Groups') ?></span></a></li>

                        </ul>
                    </li>
                <?php } ?>
                <li class="has-submenu <?php if ($data['page'] == 'Reports') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-chart zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Reports') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu" <?php if ($data['page'] == 'Reports') { ?> style="display:block;" <?php } ?>>
                        <?php if (isset($_SESSION['permissions']['messaging']['gui']) || isset($_SESSION['permissions']['messaging']['http_sms_api'])) { ?>
                            <li <?php echo $data['current_page'] == 'dlr' || $data['current_page'] == 'dlr_details' || $data['current_page'] == 'resend_campaign' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>showDlrSummary"><span class="menu-text"><?php echo SCTEXT('View DLR') ?></span></a></li>
                        <?php } ?>
                        <?php if (isset($_SESSION['permissions']['messaging']['schedule'])) { ?>
                            <li <?php echo $data['current_page'] == 'scheduled' ||  $data['current_page'] == 'edit_sch_campaign' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>scheduledCampaigns"><span class="menu-text"><?php echo SCTEXT('Scheduled Campaigns') ?></span></a></li>
                        <?php } ?>
                        <?php if (Doo::conf()->whatsapp == 1) { ?>
                            <?php if (isset($_SESSION['permissions']['addons']['whatsapp'])) { ?>
                                <li <?php echo $data['current_page'] == 'wh_reports' || $data['current_page'] == 'wh_report_details' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>viewWhatsappReports"><span class="menu-text"><?php echo SCTEXT('WhatsApp Campaigns') ?></span></a></li>
                        <?php }
                        } ?>
                        <li <?php echo $data['current_page'] == 'docs' || $data['current_page'] == 'add_doc' || $data['current_page'] == 'view_doc' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><span class="menu-text"><?php echo SCTEXT('Doc Manager') ?></span></a></li>
                        <?php if (
                            isset($_SESSION['permissions']['messaging']['gui']) ||
                            isset($_SESSION['permissions']['messaging']['http_sms_api']) ||
                            isset($_SESSION['permissions']['messaging']['smpp_sms'])
                        ) { ?>
                            <li <?php echo $data['current_page'] == 'smsarchive' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>smsArchive"><span class="menu-text"><?php echo SCTEXT('SMS Archive') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'stats' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>smsStats"><span class="menu-text"> <?php echo SCTEXT('SMS Stats') ?></span></a></li>
                        <?php } ?>
                        <li <?php echo $data['current_page'] == 'transactions' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>transactionReports"><span class="menu-text"><?php echo SCTEXT('Transactions') ?></span></a></li>
                        <?php if (isset($_SESSION['permissions']['reseller']['whitelabel'])) { ?>
                            <li <?php echo $data['current_page'] == 'webleads' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>webLeads"><span class="menu-text"><?php echo SCTEXT('Leads') ?></span></a></li>
                        <?php } ?>

                    </ul>
                </li>

                <?php if (isset($_SESSION['permissions']['messaging']['inbox']) || isset($_SESSION['permissions']['messaging']['campaigns']) || isset($_SESSION['permissions']['messaging']['vmn'])) { ?>
                    <li class="has-submenu <?php if ($data['page'] == '2WAY') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-swap zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('2-Way Messaging') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == '2WAY') { ?>style="display:block;" <?php } ?>>
                            <?php if (isset($_SESSION['permissions']['messaging']['inbox'])) { ?>
                                <li <?php echo $data['current_page'] == 'inbox' || $data['current_page'] == 'view_mo' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>inbox"><span class="menu-text"><?php echo SCTEXT('Inbox') ?></span></a></li>
                            <?php } ?>
                            <?php if (isset($_SESSION['permissions']['messaging']['vmn'])) { ?>
                                <li <?php echo $data['current_page'] == 'manage_vmn' || $data['current_page'] == 'add_vmn' || $data['current_page'] == 'edit_vmn' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageVmn"><span class="menu-text"><?php echo SCTEXT('Virtual Mobile Numbers') ?></span></a></li>
                                <li <?php echo $data['current_page'] == 'manage_kw' || $data['current_page'] == 'add_kw' || $data['current_page'] == 'edit_kw' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageKeywords"><span class="menu-text"><?php echo SCTEXT('Keywords') ?></span></a></li>
                            <?php } ?>
                            <?php if (isset($_SESSION['permissions']['messaging']['campaigns'])) { ?>
                                <li <?php echo $data['current_page'] == 'manage_cmpns' || $data['current_page'] == 'add_cmpn' || $data['current_page'] == 'edit_cmpn' || $data['current_page'] == 'cmpn_optin' || $data['current_page'] == 'cmpn_optout' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>campaigns"><span class="menu-text"><?php echo SCTEXT('Campaigns') ?></span></a></li>
                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>

                <?php if (isset($_SESSION['permissions']['addons']['hlr'])) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'HLR') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-my-location zmdi-hc-lg"></i> <span class="menu-text">HLR Lookup</span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'HLR') { ?> style="display:block;" <?php } ?>>
                            <li <?php echo $data['current_page'] == 'newhlrlookup' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>newHlrLookup"><span class="menu-text">New Lookup</span></a></li>
                            <li <?php echo $data['current_page'] == 'hlrreports' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>viewHlrReports"><span class="menu-text">View Reports</span></a></li>

                        </ul>
                    </li>
                <?php } ?>
                <?php if (
                    isset($_SESSION['permissions']['addons']['otp_api']) ||
                    isset($_SESSION['permissions']['addons']['whatsapp']) ||
                    isset($_SESSION['permissions']['messaging']['http_sms_api']) ||
                    isset($_SESSION['permissions']['messaging']['smpp_sms'])

                ) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'API') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-puzzle-piece zmdi-hc-lg"></i> <span class="menu-text">API</span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'API') { ?> style="display:block;" <?php } ?>>
                            <?php if (isset($_SESSION['permissions']['messaging']['http_sms_api'])) { ?>
                                <li <?php echo $data['current_page'] == 'hapi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>api"><span class="menu-text">HTTP API</span></a></li>
                                <li <?php echo $data['current_page'] == 'lapi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>legacyApi"><span class="menu-text">Legacy API</span></a></li>
                            <?php } ?>

                            <?php if (isset($_SESSION['permissions']['messaging']['smpp_sms'])) { ?>
                                <li <?php echo $data['current_page'] == 'sapi' || $data['current_page'] == 'smppdetail' || $data['current_page'] == 'smppsms' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>smppApi"><span class="menu-text">SMPP API</span></a></li>
                            <?php } ?>
                            <?php if (isset($_SESSION['permissions']['messaging']['otp_api'])) { ?>
                                <li <?php echo $data['current_page'] == 'otpapi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>otpApi"><span class="menu-text">OTP API</span></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <li class="has-submenu <?php if ($data['page'] == 'Logs') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-view-list zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('LOGS') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu" <?php if ($data['page'] == 'Logs') { ?> style="display:block;" <?php } ?>>
                        <li <?php echo $data['current_page'] == 'usms_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>userSmsLog"><span class="menu-text"><?php echo SCTEXT('SMS Log') ?></span></a></li>
                        <li <?php echo $data['current_page'] == 'credit_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>creditLog"><span class="menu-text"><?php echo SCTEXT('Credit Log') ?></span></a></li>
                        <li <?php echo $data['current_page'] == 'refund_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>refundLog"><span class="menu-text"><?php echo SCTEXT('Refund Log') ?></span></a></li>

                    </ul>
                </li>

                <li class="menu-separator">
                    <hr>
                </li>

                <li class="<?php if ($data['page'] == 'Support') { ?>active <?php } ?>"><a href="<?php echo Doo::conf()->APP_URL ?>supportTickets"><i class="menu-icon fa fa-support fa-lg"></i> <span class="menu-text"><?php echo SCTEXT('Support') ?></span></a></li>

                <li <?php echo $data['current_page'] == 'support_tickets_mgmt' || $data['current_page'] == 'view_ticket_mgr' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSupport"><i class="menu-icon fas fa-user-tag m-r-sm fa-lg"></i> <span class="menu-text"><?php echo SCTEXT('Client Tickets') ?></span></a></li>
            </ul>
        </div>
    </div>
</aside>