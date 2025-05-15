<aside id="menubar" class="menubar">
    <?php if (!isset($data['subpage'])) $data['subpage'] = ''; ?>
    <div class="menubar-scroll">
        <div class="menubar-scroll-inner">
            <ul class="app-menu">
                <?php if (
                    $_SESSION['user']['subgroup'] == 'admin' ||
                    ($_SESSION['user']['subgroup'] == 'staff' &&
                        (
                            (isset($_SESSION['permissions']['admin']['website']) && sizeof($_SESSION['permissions']['admin']) > 1) || //done so staff doesnt see this menu if no admin perms are set except for website
                            (!isset($_SESSION['permissions']['admin']['website']) && sizeof($_SESSION['permissions']['admin']) > 0)
                        )
                    )
                ) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Administration') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-balance zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Administration') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Administration') { ?>style="display:block;" <?php } ?>>

                            <?php if (
                                $_SESSION['user']['subgroup'] == 'admin' ||
                                ($_SESSION['user']['subgroup'] == 'staff' &&
                                    (isset($_SESSION['permissions']['admin']['smpp']) ||
                                        isset($_SESSION['permissions']['admin']['hlr'])))
                            ) { ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-plus zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Gateway Providers') ?></span> <i class="menu-caret zmdi zmdi-hc-xs zmdi-chevron-right"></i></a>
                                    <ul class="submenu" <?php if ($data['subpage'] == 'Gateways') { ?>style="display:block;" <?php } ?>>

                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['smpp']))) { ?>

                                            <li <?php echo $data['current_page'] == 'manage_smpp' || $data['current_page'] == 'add_smpp' || $data['current_page'] == 'edit_smpp' || $data['current_page'] == 'gw_costprice' || $data['current_page'] == 'import_cost_price' || $data['current_page'] == 'smpp_dlrcodes' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSmpp"><span class="menu-text"><?php echo SCTEXT('SMPP SMSC') ?></span></a></li>
                                        <?php } ?>

                                        <?php if (Doo::conf()->http_apivendor == 1 && $_SESSION['user']['subgroup'] == 'admin') { ?>
                                            <li <?php echo $data['current_page'] == 'manage_apivendors' || $data['current_page'] == 'add_apivendor' || $data['current_page'] == 'edit_apivendor' || $data['current_page'] == 'apiv_dlrcodes' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageApiVendors"><span class="menu-text"><?php echo SCTEXT('HTTP SMSC') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (Doo::conf()->mms_vendor == 1 && $_SESSION['user']['subgroup'] == 'admin') { ?>
                                            <li <?php echo $data['current_page'] == 'manage_mmsc' || $data['current_page'] == 'add_mmsc' || $data['current_page'] == 'edit_mmsc' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageMmsc"><span class="menu-text"><?php echo SCTEXT('MMSC VASP') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (Doo::conf()->whatsapp == 1 && $_SESSION['user']['subgroup'] == 'admin') { ?>
                                            <li <?php echo $data['current_page'] == 'view_waba_admin' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>viewWabaAdmin"><span class="menu-text"><?php echo SCTEXT('WhatsApp Business') ?></span></a></li>
                                        <?php } ?>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['hlr']))) { ?>
                                            <li <?php echo $data['current_page'] == 'hlrset' || $data['current_page'] == 'add_hlr' || $data['current_page'] == 'edit_hlr' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageHlr"><span class="menu-text"><?php echo SCTEXT('HLR Channels') ?></span></a></li>
                                        <?php } ?>
                                    </ul>
                                    <hr class="m-h-xs">
                                </li>

                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['routes']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_routes' || $data['current_page'] == 'add_route' || $data['current_page'] == 'edit_route' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageRoutes"><span class="menu-text"><?php echo SCTEXT('Manage Routes') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['tlv']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_tlv' || $data['current_page'] == 'add_tlv' || $data['current_page'] == 'edit_tlv' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSmppTlv"><span class="menu-text"><?php echo SCTEXT('Manage TLV tags') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['cpre']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_countries' || $data['current_page'] == 'edit_country' || $data['current_page'] == 'upload_prefix' || $data['current_page'] == 'view_prefix' || $data['current_page'] == 'edit_prefix' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageCountries"><span class="menu-text"><?php echo SCTEXT('Countries/Prefix') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['sid']))) { ?>
                                <li <?php echo $data['current_page'] == 'approve_sids' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>approveSenderIds"><span class="menu-text"><?php echo SCTEXT('Approve Sender ID') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['tmp']))) { ?>
                                <li <?php echo $data['current_page'] == 'approve_temps' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>approveTemplates"><span class="menu-text"><?php echo SCTEXT('Approve Templates') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['spam']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_spam_cmp' || $data['current_page'] == 'manage_spam_kw' || $data['current_page'] == 'add_spam_kw' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSpam"><span class="menu-text"><?php echo SCTEXT('SPAM Management') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                <li <?php echo $data['current_page'] == 'manage_ccrules' || $data['current_page'] == 'add_ccrule' || $data['current_page'] == 'edit_ccrule' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageCountRules"><span class="menu-text"><?php echo SCTEXT('Credit Count Rules') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                                <li <?php echo $data['current_page'] == 'manage_rrules' || $data['current_page'] == 'add_rrule' || $data['current_page'] == 'edit_rrule' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>refundRules"><span class="menu-text"><?php echo SCTEXT('Refund Rules') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['fdlr']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_fdlr' || $data['current_page'] == 'add_fdlr' || $data['current_page'] == 'edit_fdlr' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageFdlrTemplates"><span class="menu-text"><?php echo SCTEXT('Fake DLR Templates') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['ndnc']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_bl_db' || $data['current_page'] == 'add_bl_db' || $data['current_page'] == 'edit_bl_db' || $data['current_page'] == 'upload_bl_db' || $data['current_page'] == 'view_bl_db' || $data['current_page'] == 'manual_add_bldb' || $data['current_page'] == 'manual_del_bldb' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageBlacklists"><span class="menu-text"><?php echo SCTEXT('NDNC/Blacklist Mgmt.') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['mnp']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_mnp' || $data['current_page'] == 'add_mnp' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>mnpDatabase"><span class="menu-text"><?php echo SCTEXT('MNP Database') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['announce']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_annc' || $data['current_page'] == 'add_annc' || $data['current_page'] == 'edit_annc' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>announcements"><span class="menu-text"><?php echo SCTEXT('Announcements') ?></span></a></li>
                            <?php } ?>

                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['blockip']))) { ?>
                                <li <?php echo $data['current_page'] == 'blocked_ip_list' || $data['current_page'] == 'add_block_ip' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageBlockedIpList"><span class="menu-text"><?php echo SCTEXT('Blocked IP') ?></span></a></li>
                            <?php } ?>

                        </ul>
                    </li>

                <?php } ?>


                <?php if (
                    $_SESSION['user']['subgroup'] == 'admin' ||
                    ($_SESSION['user']['subgroup'] == 'staff' &&
                        (isset($_SESSION['permissions']['admin']['creditplan']) ||
                            isset($_SESSION['permissions']['admin']['mccmncplan']) ||
                            isset($_SESSION['permissions']['admin']['wabaplan'])))
                ) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'SMS Plans') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-collection-text zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Message Plans') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'SMS Plans') { ?>style="display:block;" <?php } ?>>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['creditplan']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_smsplans' || $data['current_page'] == 'add_smsplan' || $data['current_page'] == 'edit_smsplan' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSmsPlans"><span class="menu-text"><?php echo SCTEXT('Credit Based Plans') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['mccmncplan']))) { ?>
                                <li <?php echo $data['current_page'] == 'mccmnc_plans' || $data['current_page'] == 'add_mplan' || $data['current_page'] == 'edit_mplan' || $data['current_page'] == 'mplan_route_prices' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>mccmncRatePlans"><span class="menu-text"><?php echo SCTEXT('MCCMNC Based Plans') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['wabaplan']))) { ?>
                                <?php if (Doo::conf()->whatsapp == 1) { ?>
                                    <li <?php echo $data['current_page'] == 'waba_plans' || $data['current_page'] == 'add_wabaplan' || $data['current_page'] == 'edit_wabaplan' || $data['current_page'] == 'waba_plans_prices' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>whatsappRatePlans"><span class="menu-text"><?php echo SCTEXT('WhatsApp Rate Plans') ?></span></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']))) { ?>

                    <?php if (
                        $_SESSION['user']['subgroup'] == 'admin' ||
                        ($_SESSION['user']['subgroup'] == 'staff' &&
                            (isset($_SESSION['permissions']['messaging']['inbox']) ||
                                isset($_SESSION['permissions']['messaging']['campaigns'])
                            ))
                    ) { ?>
                        <li class="has-submenu <?php if ($data['page'] == '2WAY') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-swap zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('2-Way Messaging') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                            <ul class="submenu" <?php if ($data['page'] == '2WAY') { ?>style="display:block;" <?php } ?>>
                                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['inbox']))) { ?>
                                    <li <?php echo $data['current_page'] == 'inbox' || $data['current_page'] == 'view_mo' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>inbox"><span class="menu-text"><?php echo SCTEXT('Inbox') ?></span></a></li>
                                    <li <?php echo $data['current_page'] == 'manage_vmn' || $data['current_page'] == 'add_vmn' || $data['current_page'] == 'edit_vmn' || $data['current_page'] == 'import_vmn' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageVmn"><span class="menu-text"><?php echo SCTEXT('Virtual Mobile Numbers') ?></span></a></li>
                                    <li <?php echo $data['current_page'] == 'manage_kw' || $data['current_page'] == 'add_kw' || $data['current_page'] == 'edit_kw' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageKeywords"><span class="menu-text"><?php echo SCTEXT('Keywords') ?></span></a></li>
                                <?php } ?>
                                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['campaigns']))) { ?>
                                    <li <?php echo $data['current_page'] == 'manage_cmpns' || $data['current_page'] == 'add_cmpn' || $data['current_page'] == 'edit_cmpn' || $data['current_page'] == 'cmpn_optin' || $data['current_page'] == 'cmpn_optout' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>campaigns"><span class="menu-text"><?php echo SCTEXT('Campaigns') ?></span></a></li>
                                <?php } ?>

                            </ul>
                        </li>
                    <?php } ?>

                    <li class="has-submenu <?php if ($data['page'] == 'Messaging') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-email zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Messaging') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Messaging') { ?>style="display:block;" <?php } ?>>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['gui']))) { ?>
                                <li <?php echo $data['current_page'] == 'composeSMS' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeSMS"><span class="menu-text"><?php echo SCTEXT('Send SMS') ?></span></a></li>
                            <?php } ?>
                            <?php if (Doo::conf()->whatsapp == 1) { ?>
                                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['whatsapp']))) { ?>
                                    <li <?php echo $data['current_page'] == 'composeWA' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeWhatsappCampaign"><span class="menu-text"><?php echo SCTEXT('WhatsApp Campaign') ?></span></a></li>
                            <?php }
                            } ?>
                            <?php if (Doo::conf()->rcs == 1) { ?>
                                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['rcs']))) { ?>
                                    <li <?php echo $data['current_page'] == 'composeRCS' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeRCS"><span class="menu-text"><?php echo SCTEXT('RCS Message') ?></span></a></li>
                            <?php }
                            } ?>

                            <?php /*
                            <li <?php echo $data['current_page'] == 'composeMMS' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeMMS"><span class="menu-text"><?php echo SCTEXT('Multimedia Campaign') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'composeRCS' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeRCS"><span class="menu-text"><?php echo SCTEXT('RCS Message') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'composeLineMsg' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>composeLineMsg"><span class="menu-text"><?php echo SCTEXT('LINE Message') ?></span></a></li>
                            */ ?>
                            <?php if (
                                $_SESSION['user']['subgroup'] == 'admin' ||
                                ($_SESSION['user']['subgroup'] == 'staff' &&
                                    (isset($_SESSION['permissions']['messaging']['sender']) ||
                                        isset($_SESSION['permissions']['messaging']['templates']) ||
                                        isset($_SESSION['permissions']['messaging']['tlv']) ||
                                        isset($_SESSION['permissions']['messaging']['tinyurl'])) ||
                                    isset($_SESSION['permissions']['messaging']['media_links']) ||
                                    isset($_SESSION['permissions']['addons']['otp_api']) ||
                                    isset($_SESSION['permissions']['addons']['whatsapp'])) ||
                                isset($_SESSION['permissions']['addons']['rcs'])
                            ) { ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-plus zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Components') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                                    <ul class="submenu" <?php if ($data['page'] == 'MessagingComponents') { ?>style="display:block;" <?php } ?>>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['sender']))) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_sender' || $data['current_page'] == 'edit_sender' || $data['current_page'] == 'add_sender' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSenderId"><span class="menu-text"><?php echo SCTEXT('Sender ID') ?></span></a></li>
                                        <?php } ?>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['templates']))) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_templates' || $data['current_page'] == 'edit_template' || $data['current_page'] == 'add_template' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageTemplates"><span class="menu-text"><?php echo SCTEXT('SMS Templates') ?></span></a></li>
                                        <?php } ?>
                                        <?php if (Doo::conf()->whatsapp == 1) { ?>
                                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['whatsapp']))) { ?>
                                                <li <?php echo $data['current_page'] == 'manage_wtemplates' || $data['current_page'] == 'edit_wtemplate' || $data['current_page'] == 'add_wtemplate' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageWhatsappTemplates"><span class="menu-text"><?php echo SCTEXT('WhatsApp Templates') ?></span></a></li>
                                        <?php }
                                        } ?>
                                        <?php if (Doo::conf()->rcs == 1) { ?>
                                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['rcs']))) { ?>
                                                <li <?php echo $data['current_page'] == 'manage_richcards' || $data['current_page'] == 'edit_richcard' || $data['current_page'] == 'add_richcard' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageRichcards"><span class="menu-text"><?php echo SCTEXT('RCS Rich Cards') ?></span></a></li>
                                        <?php }
                                        } ?>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['tlv']))) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_client_tlv' || $data['current_page'] == 'add_client_tlv' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageClientTlv"><span class="menu-text"><?php echo SCTEXT('TLV Parameters') ?></span></a></li>
                                        <?php } ?>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['otp_api']))) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_otp_channels' || $data['current_page'] == 'add_otp_channel' || $data['current_page'] == 'edit_otp_channel' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageOtpChannels"><span class="menu-text"><?php echo SCTEXT('OTP Channels') ?></span></a></li>
                                        <?php } ?>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['tinyurl']))) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_tinyurl' || $data['current_page'] == 'add_tinyurl' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageShortUrls"><span class="menu-text"><?php echo SCTEXT('URL Shortener') ?></span></a></li>
                                        <?php } ?>
                                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['media_links']))) { ?>
                                            <li <?php echo $data['current_page'] == 'manage_media' || $data['current_page'] == 'add_media' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageCampaignMedia"><span class="menu-text"><?php echo SCTEXT('Media Files') ?></span></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>


                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['contacts']))) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Contact Management') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-account-box-phone zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Contacts') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Contact Management') { ?> style="display:block;" <?php } ?>>
                            <li <?php echo $data['current_page'] == 'import_contact' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>importContacts"><span class="menu-text"><?php echo SCTEXT('Import Contacts') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'edit_contact' || $data['current_page'] == 'add_contact' || $data['current_page'] == 'manage_contacts' || $data['current_page'] == 'manage_groups' || $data['current_page'] == 'edit_group' || $data['current_page'] == 'add_group' || $data['current_page'] == 'move_contacts' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageGroups"><span class="menu-text"><?php echo SCTEXT('Contact Groups') ?></span></a></li>

                            <li <?php echo $data['current_page'] == 'manage_pbdb' || $data['current_page'] == 'add_pbdb' || $data['current_page'] == 'edit_pbdb' || $data['current_page'] == 'view_pbcontacts' || $data['current_page'] == 'add_pbcontacts' || $data['current_page'] == 'edit_pbcontact' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>phonebook"><span class="menu-text"><?php echo SCTEXT('Phonebook Database') ?></span></a></li>

                        </ul>
                    </li>

                <?php } ?>

                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['user']))) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'User Management') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-accounts zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('User Accounts') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'User Management') { ?>style="display:block;" <?php } ?>>
                            <?php if (
                                $_SESSION['user']['subgroup'] == 'admin' ||
                                ($_SESSION['user']['subgroup'] == 'staff' &&
                                    (isset($_SESSION['permissions']['user']['add']) ||
                                        isset($_SESSION['permissions']['user']['transaction']) ||
                                        isset($_SESSION['permissions']['user']['logs']) ||
                                        isset($_SESSION['permissions']['user']['set']))
                                )
                            ) { ?>
                                <li <?php echo $data['current_page'] == 'manage_users' || $data['current_page'] == 'add_user' || $data['page_family'] == 'view_account' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageUsers"><span class="menu-text"><?php echo SCTEXT('Clients/Resellers') ?></span></a></li>

                                <?php if (Doo::conf()->whatsapp == 1) { ?>
                                    <li <?php echo $data['current_page'] == 'manage_waba' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageWaba"><span class="menu-text"><?php echo SCTEXT('WABA Clients') ?></span></a></li>
                                <?php } ?>

                                <li <?php echo $data['current_page'] == 'manage_iusers' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageInactiveUsers"><span class="menu-text"><?php echo SCTEXT('Suspended Accounts') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['user']['permgroups']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_permgroups' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>managePermissionGroups"><span class="menu-text"><?php echo SCTEXT('Permission Groups') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['user']['ssl']))) { ?>
                                <li <?php echo $data['current_page'] == 'manage_ssl' || $data['current_page'] == 'add_ssl' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSSL"><span class="menu-text"><?php echo SCTEXT('SSL Certificates') ?></span></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Staff-Admin') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-face zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Staff Mgmt.') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Staff-Admin') { ?>style="display:block;" <?php } ?>>
                            <li <?php echo $data['current_page'] == 'manage_staff' || $data['current_page'] == 'add_staff' || $data['current_page'] == 'view_staff' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageStaff"><span class="menu-text"><?php echo SCTEXT('Staff Members') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'manage_teams' || $data['current_page'] == 'add_team' || $data['current_page'] == 'edit_team' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageStaffTeams"><span class="menu-text"><?php echo SCTEXT('Teams') ?></span></a></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['website']))) { ?>
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

                <?php if (
                    $_SESSION['user']['subgroup'] == 'admin' ||
                    ($_SESSION['user']['subgroup'] == 'staff' &&
                        (isset($_SESSION['permissions']['admin']['website']) || isset($_SESSION['permissions']['messaging'])))
                ) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'Reports') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-chart zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Reports') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'Reports') { ?> style="display:block;" <?php } ?>>
                            <?php if (
                                $_SESSION['user']['subgroup'] == 'admin' ||
                                ($_SESSION['user']['subgroup'] == 'staff' &&
                                    (isset($_SESSION['permissions']['messaging']['gui']) ||
                                        isset($_SESSION['permissions']['messaging']['http_sms_api'])
                                    ))
                            ) { ?>
                                <li <?php echo $data['current_page'] == 'dlr' || $data['current_page'] == 'dlr_details' || $data['current_page'] == 'resend_campaign' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>showDlrSummary"><span class="menu-text"><?php echo SCTEXT('View SMS DLR') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['schedule']))) { ?>
                                <li <?php echo $data['current_page'] == 'scheduled' ||  $data['current_page'] == 'edit_sch_campaign' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>scheduledCampaigns"><span class="menu-text"><?php echo SCTEXT('Scheduled Campaigns') ?></span></a></li>
                            <?php } ?>

                            <?php if (Doo::conf()->whatsapp == 1) { ?>
                                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['whatsapp']))) { ?>
                                    <li <?php echo $data['current_page'] == 'wh_reports' || $data['current_page'] == 'wh_report_details' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>viewWhatsappReports"><span class="menu-text"><?php echo SCTEXT('WhatsApp Campaigns') ?></span></a></li>
                            <?php }
                            } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['user']))) { ?>
                                <li <?php echo $data['current_page'] == 'docs' || $data['current_page'] == 'add_doc' || $data['current_page'] == 'view_doc' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><span class="menu-text"><?php echo SCTEXT('Doc Manager') ?></span></a></li>
                            <?php } ?>
                            <?php if (
                                $_SESSION['user']['subgroup'] == 'admin' ||
                                ($_SESSION['user']['subgroup'] == 'staff' &&
                                    (isset($_SESSION['permissions']['messaging']['gui']) ||
                                        isset($_SESSION['permissions']['messaging']['http_sms_api']) ||
                                        isset($_SESSION['permissions']['messaging']['smpp_sms'])
                                    )
                                )
                            ) { ?>
                                <li <?php echo $data['current_page'] == 'smsarchive' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>smsArchive"><span class="menu-text"><?php echo SCTEXT('SMS Archive') ?></span></a></li>
                                <li <?php echo $data['current_page'] == 'stats' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>smsStats"><span class="menu-text"> <?php echo SCTEXT('SMS Stats') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['user']['transaction']))) { ?>
                                <li <?php echo $data['current_page'] == 'transactions' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>transactionReports"><span class="menu-text"><?php echo SCTEXT('Transactions') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['website']))) { ?>
                                <li <?php echo $data['current_page'] == 'webleads' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>webLeads"><span class="menu-text"><?php echo SCTEXT('Leads') ?></span></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['hlr']))) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'HLR') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-my-location zmdi-hc-lg"></i> <span class="menu-text">HLR Lookup</span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'HLR') { ?> style="display:block;" <?php } ?>>
                            <li <?php echo $data['current_page'] == 'newhlrlookup' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>newHlrLookup"><span class="menu-text">New Lookup</span></a></li>
                            <li <?php echo $data['current_page'] == 'hlrreports' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>viewHlrReports"><span class="menu-text">View Reports</span></a></li>

                        </ul>
                    </li>
                <?php } ?>

                <?php if (
                    $_SESSION['user']['subgroup'] == 'admin' ||
                    ($_SESSION['user']['subgroup'] == 'staff' &&
                        (isset($_SESSION['permissions']['addons']['otp_api']) ||
                            isset($_SESSION['permissions']['addons']['whatsapp']) ||
                            isset($_SESSION['permissions']['messaging']['smpp_sms']) ||
                            isset($_SESSION['permissions']['messaging']['http_sms_api']))
                    )
                ) { ?>
                    <li class="has-submenu <?php if ($data['page'] == 'API') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-puzzle-piece zmdi-hc-lg"></i> <span class="menu-text">API</span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu" <?php if ($data['page'] == 'API') { ?> style="display:block;" <?php } ?>>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['http_sms_api']))) { ?>
                                <li <?php echo $data['current_page'] == 'hapi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>api"><span class="menu-text">HTTP API</span></a></li>
                                <li <?php echo $data['current_page'] == 'lapi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>legacyApi"><span class="menu-text">Legacy API</span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['messaging']['smpp_sms']))) { ?>
                                <li <?php echo $data['current_page'] == 'sapi' || $data['current_page'] == 'smppdetail' || $data['current_page'] == 'smppsms' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>smppApi"><span class="menu-text">SMPP API</span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['otp_api']))) { ?>
                                <li <?php echo $data['current_page'] == 'otpapi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>otpApi"><span class="menu-text">OTP API</span></a></li>
                            <?php } ?>
                            <?php if (Doo::conf()->whatsapp == 1) { ?>
                                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['addons']['whatsapp']))) { ?>
                                    <li <?php echo $data['current_page'] == 'whatsappApi' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>whatsappApi"><span class="menu-text"><?php echo SCTEXT('WhatsApp API') ?></span></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </li>

                <?php } ?>


                <li class="has-submenu <?php if ($data['page'] == 'Logs') { ?>active open <?php } ?>"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-view-list zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('LOGS') ?></span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu" <?php if ($data['page'] == 'Logs') { ?> style="display:block;" <?php } ?>>
                        <?php if (
                            $_SESSION['user']['subgroup'] == 'admin' ||
                            ($_SESSION['user']['subgroup'] == 'staff' &&
                                (isset($_SESSION['permissions']['messaging']['gui']) ||
                                    isset($_SESSION['permissions']['messaging']['http_sms_api']) ||
                                    isset($_SESSION['permissions']['messaging']['smpp_sms'])
                                )
                            )
                        ) { ?>
                            <li <?php echo $data['current_page'] == 'usms_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>userSmsLog"><span class="menu-text"><?php echo SCTEXT('SMS Log') ?></span></a></li>
                        <?php } ?>
                        <?php if (
                            $_SESSION['user']['subgroup'] == 'admin' ||
                            ($_SESSION['user']['subgroup'] == 'staff' &&
                                (isset($_SESSION['permissions']['user']['logs']) ||
                                    isset($_SESSION['permissions']['messaging'])
                                ))
                        ) { ?>
                            <li <?php echo $data['current_page'] == 'credit_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>creditLog"><span class="menu-text"><?php echo SCTEXT('Credit Log') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'refund_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>refundLog"><span class="menu-text"><?php echo SCTEXT('Refund Log') ?></span></a></li>
                        <?php } ?>

                        <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['logs']))) { ?>
                            <li <?php echo $data['current_page'] == 'watchman_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>watchmanLog"><span class="menu-text"><?php echo SCTEXT('Watchman Log') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'security_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>susActivityLog"><span class="menu-text"><?php echo SCTEXT('Security Log') ?></span></a></li>
                            <li <?php echo $data['current_page'] == 'dbarchive_log' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>dbArchiveLog"><span class="menu-text"><?php echo SCTEXT('DB-Archive Log') ?></span></a></li>
                        <?php } ?>



                    </ul>
                </li>

                <li class="menu-separator">
                    <hr>
                </li>

                <?php if ($_SESSION['user']['subgroup'] == 'admin') { ?>

                    <li class="<?php if ($data['page'] == 'App Settings') { ?>active <?php } ?>"><a href="<?php echo Doo::conf()->APP_URL ?>appSettings"><i class="menu-icon zmdi zmdi-settings zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('App Settings') ?></span></a></li>

                    <?php if (Doo::conf()->show_license == 1) { ?>
                        <li class="<?php if ($data['page'] == 'License') { ?>active <?php } ?>"><a href="<?php echo Doo::conf()->APP_URL ?>license"><i class="menu-icon fa fa-file-contract fa-md"></i> <span class="menu-text"><?php echo SCTEXT('License Info') ?></span></a></li>
                    <?php } ?>
                <?php } ?>

                <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['support']))) { ?>

                    <li<?php if ($data['page'] == 'ManageSupport') { ?> class="active" <?php } ?>><a href="<?php echo Doo::conf()->APP_URL ?>manageSupport"><i class="menu-icon fa fa-support fa-lg"></i> <span class="menu-text"><?php echo SCTEXT('Support Tickets') ?></span></a></li>

                    <?php } ?>


            </ul>
        </div>
    </div>
</aside>