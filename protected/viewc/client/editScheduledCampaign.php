<main id="app-main" class="app-main">
    <?php

    use Google\Service\Analytics\Columns;

    include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Edit Campaign') ?><small><?php echo SCTEXT('modify campaign parameters or re-schedule it') ?></small>

                            </h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <!-- start content -->
                            <form class="form-horizontal" method="post" id="sendsms_form">
                                <input type="hidden" name="editsch" id="editsch" value="1" />
                                <input type="hidden" name="account_type" id="account_type" value="<?php echo $_SESSION['user']['account_type'] ?>">
                                <?php
                                //get campaign data
                                $smstype = json_decode($data['cdata']->sms_type, true);
                                //get sms text
                                if ($smstype['main'] == 'text') {
                                    $smstext = htmlspecialchars_decode($data['cdata']->sms_text);
                                } else {
                                    $smstext = json_decode(base64_decode($data['cdata']->sms_text), true);
                                }
                                //get original scheduled time
                                $schdata = json_decode($data['cdata']->schedule_data, true);
                                ?>
                                <input type="hidden" name="orgschdate" id="orgschdate" value="<?php echo date('Y-m-d H:i', strtotime($schdata['schedule']['date'])) ?>" />
                                <input type="hidden" name="orgschtz" id="orgschtz" value="<?php echo $schdata['schedule']['timezone'] ?>" />
                                <input type="hidden" id="totalschno" name="totalschno" value="<?php echo intval($data['smsdata']->total_contacts) ?>" />
                                <input type="hidden" id="shootid" name="shootid" value="<?php echo $data['cdata']->sms_shoot_id ?>" />
                                <input type="hidden" name="schcontactcost" id="schcontactcost" value="<?php echo $data['cdata']->total_cost ?>">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <?php if ($_SESSION['user']['account_type'] == '1') {
                                            //check if assigned plan has a single route, if yes show sender id based on route settings else show approval based as it is more restrictive
                                            $rt = array_values($data['routes'])[0];
                                            $crestr = '<i class="zmdi zmdi-hc-lg zmdi-balance-wallet text-primary m-r-xs"></i> <kbd class="text-white bg-primary">' . Doo::conf()->currency . rtrim(number_format($_SESSION['credits']['wallet']['amount'], 5), "0") . '</kbd>';
                                        ?>
                                            <input data-stype="<?php echo $rt['senderType'] ?>" data-smax="<?php echo $rt['maxSender'] ?>" data-sdef="<?php echo $rt['defaultSender'] ?>" data-tflag="<?php echo $rt['templateFlag'] ?>" data-actspan='<?php echo json_encode(unserialize($rt['activeTime'])) ?>' data-cov="<?php echo $rt['coverage'] ?>" data-crule="<?php echo $rt['creditRule'] ?>" data-crval="<?php echo $rt['validity'] ?>" data-acr="<?php echo $rt['credits'] ?>" type="hidden" id="mccdefroute" value="<?php echo $rt['id'] ?>" name="route">

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Wallet Balance') ?>:</label>
                                                <div class="col-md-8">
                                                    <span class="help-block clearfix text-info m-b-0"><?php echo $crestr ?>
                                                        <span id="rtstatus" style="float: right;">

                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" readonly name="senderopn" id="senderopn" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID') ?>..." maxlength="50" value="<?php echo $data['smsdata']->sender_id ?>" />
                                                    <span class="help-block text-info m-b-0"><?php echo SCTEXT('Follow the rules for Sender ID set by your SMS provider.') ?></span>
                                                </div>
                                            </div>



                                        <?php } else { ?>
                                            <?php if ($_SESSION['user']['account_type'] == 2) {
                                                $crestr = '<i class="zmdi zmdi-hc-lg zmdi-balance-wallet text-primary m-r-xs"></i> <kbd class="text-white bg-primary">' . Doo::conf()->currency . rtrim(number_format($_SESSION['credits']['wallet']['amount'], 5), "0") . '</kbd>';
                                            ?>
                                                <input type="hidden" id="user_wbal" value="<?php echo floatval($_SESSION['credits']['wallet']['amount']) ?>">
                                                <input type="hidden" id="activerate" value="0">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Wallet Balance') ?>:</label>
                                                    <div class="col-md-8">
                                                        <span class="help-block clearfix text-info m-b-0"><?php echo $crestr ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('SMS Route') ?>:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="route" id="routesel" data-plugin="select2">
                                                        <?php foreach ($data['routes'] as $rt) { ?>
                                                            <option data-stype="<?php echo $rt['senderType'] ?>" data-smax="<?php echo $rt['maxSender'] ?>" data-sdef="<?php echo $rt['defaultSender'] ?>" data-tflag="<?php echo $rt['templateFlag'] ?>" data-actspan='<?php echo base64_decode($rt['activeTime']) ?>' data-cov="<?php echo $rt['coverage'] ?>" data-crule="<?php echo $rt['creditRule'] ?>" data-crval="<?php echo $rt['validity'] ?>" data-acr="<?php echo $rt['credits'] ?>" data-rate="<?php echo $rt['price'] ?>" data-tlvs='<?php echo $rt['tlv_ids'] ?>' <?php if ($rt['id'] == $data['cdata']->route_id) { ?> selected <?php } ?> value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-block clearfix text-info m-b-0"><?php echo SCTEXT('Available Credits') ?>: <b id="rtavcr">0</b>
                                                        <span id="rtstatus" style="float: right;">

                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div id="sidselbox" class="form-group hidden">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID') ?>:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="sendersel" id="sendersel" data-plugin="select2">
                                                        <?php
                                                        //$sender = ''; not sure why i set this
                                                        foreach ($data['sids'] as $sid) { ?>
                                                            <option value="<?php echo $sid->sender_id ?>" <?php if ($sid->sender_id == $data['cdata']->sender_id) { ?> selected <?php } ?>><?php echo $sid->sender_id ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-block text-info m-b-0"><a href="<?php echo Doo::conf()->APP_URL ?>addSender"><?php echo SCTEXT('Request New Sender ID') ?></a></span>
                                                </div>
                                            </div>
                                            <div id="sidopnbox" class="form-group hidden">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="senderopn" id="senderopn" class="form-control" placeholder="<?php echo SCTEXT('enter sender ID') ?>..." maxlength="50" value="<?php echo $data['smsdata']->sender_id ?>" />
                                                    <span class="help-block text-info m-b-0"><?php echo SCTEXT('Follow the rules for Sender ID set by your SMS provider.') ?></span>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <div id="contactMainContainer" class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Contacts') ?>:</label>
                                            <div class="col-md-8" style="overflow-x: auto;">
                                                <div class="text-right m-b-sm">
                                                    <a href="javascript:void(0);" id="addcontacts" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?php echo SCTEXT('Add More') ?></a>
                                                </div>
                                                <?php
                                                $contacts_matrix = json_decode($data['smsdata']->contacts, true);
                                                //echo '<pre>' . print_r($contacts_matrix, true) . '</pre>';
                                                $columns = [];
                                                $has_params = 0;
                                                $hide_textbox = 0;
                                                if ($smstype['personalize'] != 1) {
                                                    //only one column is enough
                                                } else {
                                                    //check if every contact has a different msg (e.g from API) or do they have parameters
                                                    if (!isset($contacts_matrix['mobile_data'][0]["parameters"])) {
                                                        //all contacts have different sms
                                                        $columns = ["Message"];
                                                        $hide_textbox = 1;
                                                    } else {
                                                        //all contacts have parameters associated with them
                                                        $columns = array_keys($contacts_matrix['mobile_data'][0]["parameters"]);
                                                        $has_params = 1;
                                                    }
                                                }

                                                ?>
                                                <input type="hidden" id="hasparams" name="hasparams" value="<?php echo $has_params ?>">
                                                <input type="hidden" id="columns" name="columns" value='<?php echo json_encode($columns) ?>'>

                                                <table id="t-sch_contacts" data-plugin="DataTable" data-options="{ order: [], ordering: false, select: 'multiple', dom: '<\'col-md-12\'<\'col-md-4 clearfix\'B><\'col-md-8 pull-right\'f><\'clearfix\'><tip>>', buttons: [{extend: 'selectAll', text: '<i class=\'fa fa-check-square\' title=\'<?php echo SCTEXT('Select All') ?>\'></i>'},{extend: 'selectNone', text: '<i class=\'far fa-square\' title=\'<?php echo SCTEXT('Uncheck All') ?>\'></i>'},{text:'<i class=\'fa fa-trash\' title=\'<?php echo SCTEXT('Delete Selected Rows') ?>\'></i>',action: function(e, dt, node, config, cb){dt.rows({ selected: true }).remove().draw(false);}}], language: { url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, scrollX: true}" class="wd100 table order-column">
                                                    <thead>
                                                        <tr>
                                                            <th data-priority="1"><?php echo SCTEXT('Mobile') ?></th>
                                                            <?php
                                                            foreach ($columns as $col) {
                                                                echo '<th>' . $col . '</th>';
                                                            }
                                                            ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="contacts_tbody">
                                                        <?php foreach ($contacts_matrix['mobile_data'] as $contact) { ?>
                                                            <tr>
                                                                <td <?php echo count($columns) > 1 ? 'class="bg-primary text-white"' : '' ?>>
                                                                    <input type="hidden" name="msisdn[]" value="<?php echo $contact['msisdn'] ?>">
                                                                    <?php echo $contact['msisdn'] ?>
                                                                </td>
                                                                <?php
                                                                foreach ($columns as $col) {
                                                                    echo $has_params ?
                                                                        '<input type="hidden" name="parameters[' . $col . '][]" value="' . htmlspecialchars($contact["parameters"][$col], ENT_QUOTES, 'UTF-8') . '">' .
                                                                        '<td>' . htmlspecialchars($contact["parameters"][$col], ENT_QUOTES, 'UTF-8') . '</td>'
                                                                        :
                                                                        '<input type="hidden" name="sms[]" value="' . htmlspecialchars($contact['sms'], ENT_QUOTES, 'UTF-8') . '">' .
                                                                        '<td>' . htmlspecialchars($contact['sms'], ENT_QUOTES, 'UTF-8') . '</td>';
                                                                }
                                                                ?>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>




                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Total Contacts (approx.)') ?>:</label>
                                            <div class="col-md-8 clearfix">
                                                <span id="contcountbox" class="help-block text-danger m-b-0"><b>0</b> contact(s) X <b>1</b> SMS = <b>0</b> <?php echo SCTEXT('credits required') ?> <span id="contcountloader" class="hidden pull-right text-dark"><i class="fa fa-lg fa-spin fa-circle-o-notch"></i> </span></span>

                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('SMS Type') ?>:</label>
                                            <div class="col-md-8 disabledBox">
                                                <div id="stype-main">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input name="smstype" id="txtsms" <?php if ($smstype['main'] == 'text') { ?> checked <?php } ?> type="radio" value="text">
                                                        <label for="txtsms">Text</label>
                                                    </div>

                                                    <div class="radio radio-inline radio-primary">
                                                        <input <?php if ($smstype['main'] == 'wap') { ?> checked <?php } ?> name="smstype" id="wapsms" type="radio" value="wap" <?php if (!$_SESSION['permissions']['master'] && !$_SESSION['permissions']['messaging']['wap_push']) { ?> disabled <?php } ?>>
                                                        <label for="wapsms">WAP</label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input <?php if ($smstype['main'] == 'vcard') { ?> checked <?php } ?> name="smstype" id="vcsms" type="radio" value="vcard" <?php if (!$_SESSION['permissions']['master'] && !$_SESSION['permissions']['messaging']['vcard_sms']) { ?> disabled <?php } ?>>
                                                        <label for="vcsms">vCard</label>
                                                    </div>
                                                </div>
                                                <hr class="m-b-0 m-t-xs">

                                                <div id="stype-subopts">
                                                    <div class="checkbox checkbox-success checkbox-inline">
                                                        <input <?php if ($smstype['flash'] == 1) { ?> checked <?php } ?> id="flashsms" name="flash-sel" type="checkbox" <?php if (!$_SESSION['permissions']['master'] && !$_SESSION['permissions']['messaging']['flash_sms']) { ?> disabled <?php } ?>>
                                                        <label for="flashsms">Flash</label>
                                                    </div>

                                                    <div class="checkbox checkbox-success checkbox-inline">
                                                        <input <?php if ($smstype['personalize'] == 1) { ?> checked <?php } ?> id="dynsms" name="dyn-sel" type="checkbox" <?php if (!$_SESSION['permissions']['master'] && !$_SESSION['permissions']['messaging']['personalized_sms']) { ?> disabled <?php } ?>>
                                                        <label for="dynsms">Personalize</label>
                                                        <i class="pop-over m-l-xs fa fa-lg fa-info-circle text-primary" data-content="<?php echo SCTEXT('Personalize SMS content using dynamic column values. Use contact groups or upload Excel/CSV file to display column options.<br><br>Click on column options to add dynamic values in SMS text.') ?>" data-trigger="hover" data-placement="bottom"></i>
                                                    </div>
                                                    <div id="unicodetypebox" class="panel p-sm bg-primary m-b-0 m-t-xs <?php if (!$_SESSION['permissions']['master'] && !$_SESSION['permissions']['messaging']['unicode_sms']) { ?> disabledBox <?php } ?>">
                                                        <input type="hidden" name="unicodeFlag" id="unicodeFlag" value="<?php echo $smstype['unicode'] == 1 ? '1' : '0' ?>">
                                                        <h5 class="m-h-xs"><?php echo SCTEXT('Unicode') ?></h5>
                                                        <hr class="m-h-xs">
                                                        <div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input onchange="javascript:checkboxClickHandler()" name="unicodetype" id="unicode_auto" checked type="radio" value="uauto">
                                                                <label for="unicode_auto">Auto-detect</label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input onchange="javascript:checkboxClickHandler()" name="unicodetype" id="unicode_tl" type="radio" value="utl">
                                                                <label for="unicode_tl">Transliteration</label>
                                                                <span class="m-l-xs" id="translControl"></span>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <?php if (Doo::conf()->smd_sendsms_warning == 1) { ?>
                                                    <span class="help-block fz-sm text-danger">
                                                        UAE users, please ensure that you put <b><u class="fz-md">"unsub.ae"</u></b> at end of each SMS. As per TRA optout option is mandatory. We will not be responsible for any non delivery arising because of this.
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('SMS Content') ?>:</label>
                                            <div class="col-md-8 <?php if ($hide_textbox == 1) echo 'disabledBox'; ?>">
                                                <div id="sms_content_box">
                                                    <div>
                                                        <?php if ($has_params == 1) { ?>
                                                            <div id="xlcolbox" class="panel p-sm bg-info m-b-0">
                                                                <h5 class="m-h-xs"><?php echo SCTEXT('Columns') ?></h5>
                                                                <hr class="m-h-xs">
                                                                <div id="xlcolbtns">

                                                                    <?php foreach ($columns as $col) { ?>
                                                                        <button data-colval="<?php echo $col ?>" type="button" class="colsbtn btn btn-sm btn-default"><?php echo $col ?></button>
                                                                    <?php } ?>
                                                                </div>

                                                            </div>
                                                        <?php } ?>
                                                        <textarea id="text_sms_content" name="smstextcontent" rows="9" cols="50" class="pop-over form-control"><?php if (!is_array($smstext)) echo $smstext; ?></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="pull-left"><input type="text" readonly="readonly" id="txtleft" value="<?php echo Doo::conf()->credit_counter_dir == 'h2l' ? '1000' : '0'; ?>" size="2" /> &nbsp;<?php echo Doo::conf()->credit_counter_dir == 'h2l' ? 'Left' : 'Chars'; ?> &nbsp;&nbsp;</div>
                                                        <div class="pull-left"><input type="text" id="txtcount" size="1" value="1" readonly="readonly" /> &nbsp;SMS&nbsp;&nbsp;<i id="ccruledata" class="hidden-xs fa fa-lg fa-info-circle text-primary"></i></div>&nbsp;&nbsp;
                                                        <div class="pull-right">
                                                            <?php if ($_SESSION['permissions']['master'] == '1' || isset($_SESSION['permissions']['messaging']['media_links'])) { ?>
                                                                <a id="selMedia" title="Add Media Link" data-toggle="modal" data-target="#medbox" class="btn btn-xs btn-purple p-v-xs m-r-xs" href="javascript:void(0);"><i class="fas fa-images"></i></a>
                                                            <?php } ?>

                                                            <?php if (Doo::conf()->add_auto_stop_button == 1) { ?>
                                                                <a id="stopmsg" class="btn btn-xs btn-danger p-v-xs m-r-xs" title="Add a way to allow people of opt-out to stop receiving messages e.g. reply with STOP to stop receiving messages" href="javascript:void(0);"><?php echo SCTEXT('Stop') ?></a>
                                                            <?php } ?>
                                                            <?php if ($_SESSION['permissions']['master'] == '1' || isset($_SESSION['permissions']['messaging']['tinyurl'])) { ?>
                                                                <a id="seltinyurl" data-toggle="modal" data-target="#tinyurlbox" class="btn btn-xs btn-primary p-v-xs m-r-xs" href="javascript:void(0);"><?php echo SCTEXT('TinyUrl') ?></a>
                                                            <?php } ?>
                                                            <?php if ($_SESSION['permissions']['master'] == '1' || isset($_SESSION['permissions']['messaging']['templates'])) { ?>
                                                                <a class="btn btn-xs btn-success p-v-xs" id="seltemp" rev="text_sms_content" data-toggle="modal" data-target="#templateBox" href="javascript:void(0);"><?php echo SCTEXT('Template') ?></a>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="clear"></div>

                                                    </div>
                                                </div>

                                                <div id="wap_content_box" class="m-t-xs p-l-sm hidden">
                                                    <div class="form-group">
                                                        <label for="wap_sms_title">WAP Title</label>
                                                        <input type="text" name="smstext[wap_title]" placeholder="<?php echo SCTEXT('Enter WAP-Push Title') ?>" class="pop-over form-control" data-content="<?php echo SCTEXT('Title limits to 60 characters. Enter a brief phrase to describe WAP content.') ?>" data-trigger="hover" data-placement="top" maxlength="60" id="wap_sms_title" value="<?php echo is_array($smstext) ? $smstext['wap_title'] : '' ?>" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="wap_sms_url">WAP URL</label>
                                                        <input type="text" name="smstext[wap_url]" maxlength="100" placeholder="<?php echo SCTEXT('Enter WAP-push URL') ?>" class="pop-over form-control" data-trigger="hover" data-content="<?php echo SCTEXT('URL must include protocol also e.g. http/wap. Must be limited to 100 characters.') ?>" data-placement="top" id="wap_sms_url" value="<?php echo is_array($smstext) ? $smstext['wap_url'] : '' ?>" />
                                                    </div>
                                                </div>

                                                <div id="vcard_content_box" class="hidden">
                                                    <div class="input-group m-b-xs">
                                                        <span class="input-group-addon bg-primary"><i class="fa fa-user fa-fw fa-lg text-white"></i></span>
                                                        <input class="form-control" type="text" name="smstext[vcard_fname]" placeholder="<?php echo SCTEXT('Enter First Name') ?>" value="<?php echo is_array($smstext) ? $smstext['vcard_fname'] : '' ?>" />
                                                    </div>
                                                    <div class="input-group m-b-xs">
                                                        <span class="input-group-addon bg-info"><i class="fa fa-user-plus fa-fw fa-lg text-white"></i></span>
                                                        <input class="form-control" type="text" name="smstext[vcard_lname]" placeholder="<?php echo SCTEXT('Enter Last Name') ?>" value="<?php echo is_array($smstext) ? $smstext['vcard_lname'] : '' ?>" />
                                                    </div>
                                                    <div class="input-group m-b-xs">
                                                        <span class="input-group-addon bg-warning"><i class="fa fa-building fa-fw fa-lg text-dark"></i></span>
                                                        <input class="form-control" type="text" name="smstext[vcard_comp]" placeholder="<?php echo SCTEXT('Enter Company Name') ?>" value="<?php echo is_array($smstext) ? $smstext['vcard_comp'] : '' ?>" />
                                                    </div>
                                                    <div class="input-group m-b-xs">
                                                        <span class="input-group-addon bg-danger"><i class="fa fa-briefcase fa-fw fa-lg text-white"></i></span>
                                                        <input class="form-control" type="text" name="smstext[vcard_job]" placeholder="<?php echo SCTEXT('Enter Job Title') ?>" value="<?php echo is_array($smstext) ? $smstext['vcard_job'] : '' ?>" />
                                                    </div>
                                                    <div class="input-group m-b-xs">
                                                        <span class="input-group-addon bg-success"><i class="fa fa-phone fa-fw fa-lg text-white"></i></span>
                                                        <input class="form-control" type="text" name="smstext[vcard_tel]" placeholder="<?php echo SCTEXT('Enter Telephone No.') ?>" value="<?php echo is_array($smstext) ? $smstext['vcard_tel'] : '' ?>" />
                                                    </div>
                                                    <div class="input-group m-b-xs">
                                                        <span class="input-group-addon bg-purple"><i class="fa fa-envelope fa-fw fa-lg text-white"></i></span>
                                                        <input class="form-control" type="text" name="smstext[vcard_email]" placeholder="<?php echo SCTEXT('Enter Email ID') ?>" value="<?php echo is_array($smstext) ? $smstext['vcard_email'] : '' ?>" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Schedule') ?>:</label>
                                            <div class="col-md-8">

                                                <div id="schbox" class="help-block planopts text-dark p-sm m-0">
                                                    <div class="input-group">

                                                        <label for="schdp" class="input-group-addon bg-info text-white"><i class="fa fa-lg fa-calendar"></i> </label>
                                                        <input type="text" id="schdp" name="schtime" class="form-control">
                                                    </div>

                                                    <div class="input-group m-t-sm">
                                                        <span class="input-group-addon bg-info text-white"><i class="fa fa-lg fa-clock-o"></i></span>
                                                        <select data-plugin="select2" data-options="{placeholder: '<?php echo SCTEXT('Select Timezone') ?>'}" class="form-control" id="timezone" name="timezone">
                                                            <option value="Africa/Abidjan">Africa/Abidjan</option>
                                                            <option value="Africa/Accra">Africa/Accra</option>
                                                            <option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
                                                            <option value="Africa/Algiers">Africa/Algiers</option>
                                                            <option value="Africa/Asmara">Africa/Asmara</option>
                                                            <option value="Africa/Asmera">Africa/Asmera</option>
                                                            <option value="Africa/Bamako">Africa/Bamako</option>
                                                            <option value="Africa/Bangui">Africa/Bangui</option>
                                                            <option value="Africa/Banjul">Africa/Banjul</option>
                                                            <option value="Africa/Bissau">Africa/Bissau</option>
                                                            <option value="Africa/Blantyre">Africa/Blantyre</option>
                                                            <option value="Africa/Brazzaville">Africa/Brazzaville</option>
                                                            <option value="Africa/Bujumbura">Africa/Bujumbura</option>
                                                            <option value="Africa/Cairo">Africa/Cairo</option>
                                                            <option value="Africa/Casablanca">Africa/Casablanca</option>
                                                            <option value="Africa/Ceuta">Africa/Ceuta</option>
                                                            <option value="Africa/Conakry">Africa/Conakry</option>
                                                            <option value="Africa/Dakar">Africa/Dakar</option>
                                                            <option value="Africa/Dar_es_Salaam">Africa/Dar_es_Salaam</option>
                                                            <option value="Africa/Djibouti">Africa/Djibouti</option>
                                                            <option value="Africa/Douala">Africa/Douala</option>
                                                            <option value="Africa/El_Aaiun">Africa/El_Aaiun</option>
                                                            <option value="Africa/Freetown">Africa/Freetown</option>
                                                            <option value="Africa/Gaborone">Africa/Gaborone</option>
                                                            <option value="Africa/Harare">Africa/Harare</option>
                                                            <option value="Africa/Johannesburg">Africa/Johannesburg</option>
                                                            <option value="Africa/Juba">Africa/Juba</option>
                                                            <option value="Africa/Kampala">Africa/Kampala</option>
                                                            <option value="Africa/Khartoum">Africa/Khartoum</option>
                                                            <option value="Africa/Kigali">Africa/Kigali</option>
                                                            <option value="Africa/Kinshasa">Africa/Kinshasa</option>
                                                            <option value="Africa/Lagos">Africa/Lagos</option>
                                                            <option value="Africa/Libreville">Africa/Libreville</option>
                                                            <option value="Africa/Lome">Africa/Lome</option>
                                                            <option value="Africa/Luanda">Africa/Luanda</option>
                                                            <option value="Africa/Lubumbashi">Africa/Lubumbashi</option>
                                                            <option value="Africa/Lusaka">Africa/Lusaka</option>
                                                            <option value="Africa/Malabo">Africa/Malabo</option>
                                                            <option value="Africa/Maputo">Africa/Maputo</option>
                                                            <option value="Africa/Maseru">Africa/Maseru</option>
                                                            <option value="Africa/Mbabane">Africa/Mbabane</option>
                                                            <option value="Africa/Mogadishu">Africa/Mogadishu</option>
                                                            <option value="Africa/Monrovia">Africa/Monrovia</option>
                                                            <option value="Africa/Nairobi">Africa/Nairobi</option>
                                                            <option value="Africa/Ndjamena">Africa/Ndjamena</option>
                                                            <option value="Africa/Niamey">Africa/Niamey</option>
                                                            <option value="Africa/Nouakchott">Africa/Nouakchott</option>
                                                            <option value="Africa/Ouagadougou">Africa/Ouagadougou</option>
                                                            <option value="Africa/Porto-Novo">Africa/Porto-Novo</option>
                                                            <option value="Africa/Sao_Tome">Africa/Sao_Tome</option>
                                                            <option value="Africa/Timbuktu">Africa/Timbuktu</option>
                                                            <option value="Africa/Tripoli">Africa/Tripoli</option>
                                                            <option value="Africa/Tunis">Africa/Tunis</option>
                                                            <option value="Africa/Windhoek">Africa/Windhoek</option>
                                                            <option value="America/Adak">America/Adak</option>
                                                            <option value="America/Anchorage">America/Anchorage</option>
                                                            <option value="America/Anguilla">America/Anguilla</option>
                                                            <option value="America/Antigua">America/Antigua</option>
                                                            <option value="America/Araguaina">America/Araguaina</option>
                                                            <option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires</option>
                                                            <option value="America/Argentina/Catamarca">America/Argentina/Catamarca</option>
                                                            <option value="America/Argentina/ComodRivadavia">America/Argentina/ComodRivadavia</option>
                                                            <option value="America/Argentina/Cordoba">America/Argentina/Cordoba</option>
                                                            <option value="America/Argentina/Jujuy">America/Argentina/Jujuy</option>
                                                            <option value="America/Argentina/La_Rioja">America/Argentina/La_Rioja</option>
                                                            <option value="America/Argentina/Mendoza">America/Argentina/Mendoza</option>
                                                            <option value="America/Argentina/Rio_Gallegos">America/Argentina/Rio_Gallegos</option>
                                                            <option value="America/Argentina/Salta">America/Argentina/Salta</option>
                                                            <option value="America/Argentina/San_Juan">America/Argentina/San_Juan</option>
                                                            <option value="America/Argentina/San_Luis">America/Argentina/San_Luis</option>
                                                            <option value="America/Argentina/Tucuman">America/Argentina/Tucuman</option>
                                                            <option value="America/Argentina/Ushuaia">America/Argentina/Ushuaia</option>
                                                            <option value="America/Aruba">America/Aruba</option>
                                                            <option value="America/Asuncion">America/Asuncion</option>
                                                            <option value="America/Atikokan">America/Atikokan</option>
                                                            <option value="America/Atka">America/Atka</option>
                                                            <option value="America/Bahia">America/Bahia</option>
                                                            <option value="America/Bahia_Banderas">America/Bahia_Banderas</option>
                                                            <option value="America/Barbados">America/Barbados</option>
                                                            <option value="America/Belem">America/Belem</option>
                                                            <option value="America/Belize">America/Belize</option>
                                                            <option value="America/Blanc-Sablon">America/Blanc-Sablon</option>
                                                            <option value="America/Boa_Vista">America/Boa_Vista</option>
                                                            <option value="America/Bogota">America/Bogota</option>
                                                            <option value="America/Boise">America/Boise</option>
                                                            <option value="America/Buenos_Aires">America/Buenos_Aires</option>
                                                            <option value="America/Cambridge_Bay">America/Cambridge_Bay</option>
                                                            <option value="America/Campo_Grande">America/Campo_Grande</option>
                                                            <option value="America/Cancun">America/Cancun</option>
                                                            <option value="America/Caracas">America/Caracas</option>
                                                            <option value="America/Catamarca">America/Catamarca</option>
                                                            <option value="America/Cayenne">America/Cayenne</option>
                                                            <option value="America/Cayman">America/Cayman</option>
                                                            <option value="America/Chicago">America/Chicago</option>
                                                            <option value="America/Chihuahua">America/Chihuahua</option>
                                                            <option value="America/Coral_Harbour">America/Coral_Harbour</option>
                                                            <option value="America/Cordoba">America/Cordoba</option>
                                                            <option value="America/Costa_Rica">America/Costa_Rica</option>
                                                            <option value="America/Cuiaba">America/Cuiaba</option>
                                                            <option value="America/Curacao">America/Curacao</option>
                                                            <option value="America/Danmarkshavn">America/Danmarkshavn</option>
                                                            <option value="America/Dawson">America/Dawson</option>
                                                            <option value="America/Dawson_Creek">America/Dawson_Creek</option>
                                                            <option value="America/Denver">America/Denver</option>
                                                            <option value="America/Detroit">America/Detroit</option>
                                                            <option value="America/Dominica">America/Dominica</option>
                                                            <option value="America/Edmonton">America/Edmonton</option>
                                                            <option value="America/Eirunepe">America/Eirunepe</option>
                                                            <option value="America/El_Salvador">America/El_Salvador</option>
                                                            <option value="America/Ensenada">America/Ensenada</option>
                                                            <option value="America/Fortaleza">America/Fortaleza</option>
                                                            <option value="America/Fort_Wayne">America/Fort_Wayne</option>
                                                            <option value="America/Glace_Bay">America/Glace_Bay</option>
                                                            <option value="America/Godthab">America/Godthab</option>
                                                            <option value="America/Goose_Bay">America/Goose_Bay</option>
                                                            <option value="America/Grand_Turk">America/Grand_Turk</option>
                                                            <option value="America/Grenada">America/Grenada</option>
                                                            <option value="America/Guadeloupe">America/Guadeloupe</option>
                                                            <option value="America/Guatemala">America/Guatemala</option>
                                                            <option value="America/Guayaquil">America/Guayaquil</option>
                                                            <option value="America/Guyana">America/Guyana</option>
                                                            <option value="America/Halifax">America/Halifax</option>
                                                            <option value="America/Havana">America/Havana</option>
                                                            <option value="America/Hermosillo">America/Hermosillo</option>
                                                            <option value="America/Indiana/Indianapolis">America/Indiana/Indianapolis</option>
                                                            <option value="America/Indiana/Knox">America/Indiana/Knox</option>
                                                            <option value="America/Indiana/Marengo">America/Indiana/Marengo</option>
                                                            <option value="America/Indiana/Petersburg">America/Indiana/Petersburg</option>
                                                            <option value="America/Indiana/Tell_City">America/Indiana/Tell_City</option>
                                                            <option value="America/Indiana/Vevay">America/Indiana/Vevay</option>
                                                            <option value="America/Indiana/Vincennes">America/Indiana/Vincennes</option>
                                                            <option value="America/Indiana/Winamac">America/Indiana/Winamac</option>
                                                            <option value="America/Indianapolis">America/Indianapolis</option>
                                                            <option value="America/Inuvik">America/Inuvik</option>
                                                            <option value="America/Iqaluit">America/Iqaluit</option>
                                                            <option value="America/Jamaica">America/Jamaica</option>
                                                            <option value="America/Jujuy">America/Jujuy</option>
                                                            <option value="America/Juneau">America/Juneau</option>
                                                            <option value="America/Kentucky/Louisville">America/Kentucky/Louisville</option>
                                                            <option value="America/Kentucky/Monticello">America/Kentucky/Monticello</option>
                                                            <option value="America/Knox_IN">America/Knox_IN</option>
                                                            <option value="America/Kralendijk">America/Kralendijk</option>
                                                            <option value="America/La_Paz">America/La_Paz</option>
                                                            <option value="America/Lima">America/Lima</option>
                                                            <option value="America/Los_Angeles">America/Los_Angeles</option>
                                                            <option value="America/Louisville">America/Louisville</option>
                                                            <option value="America/Lower_Princes">America/Lower_Princes</option>
                                                            <option value="America/Maceio">America/Maceio</option>
                                                            <option value="America/Managua">America/Managua</option>
                                                            <option value="America/Manaus">America/Manaus</option>
                                                            <option value="America/Marigot">America/Marigot</option>
                                                            <option value="America/Martinique">America/Martinique</option>
                                                            <option value="America/Matamoros">America/Matamoros</option>
                                                            <option value="America/Mazatlan">America/Mazatlan</option>
                                                            <option value="America/Mendoza">America/Mendoza</option>
                                                            <option value="America/Menominee">America/Menominee</option>
                                                            <option value="America/Merida">America/Merida</option>
                                                            <option value="America/Metlakatla">America/Metlakatla</option>
                                                            <option value="America/Mexico_City">America/Mexico_City</option>
                                                            <option value="America/Miquelon">America/Miquelon</option>
                                                            <option value="America/Moncton">America/Moncton</option>
                                                            <option value="America/Monterrey">America/Monterrey</option>
                                                            <option value="America/Montevideo">America/Montevideo</option>
                                                            <option value="America/Montreal">America/Montreal</option>
                                                            <option value="America/Montserrat">America/Montserrat</option>
                                                            <option value="America/Nassau">America/Nassau</option>
                                                            <option value="America/New_York">America/New_York</option>
                                                            <option value="America/Nipigon">America/Nipigon</option>
                                                            <option value="America/Nome">America/Nome</option>
                                                            <option value="America/Noronha">America/Noronha</option>
                                                            <option value="America/North_Dakota/Beulah">America/North_Dakota/Beulah</option>
                                                            <option value="America/North_Dakota/Center">America/North_Dakota/Center</option>
                                                            <option value="America/North_Dakota/New_Salem">America/North_Dakota/New_Salem</option>
                                                            <option value="America/Ojinaga">America/Ojinaga</option>
                                                            <option value="America/Panama">America/Panama</option>
                                                            <option value="America/Pangnirtung">America/Pangnirtung</option>
                                                            <option value="America/Paramaribo">America/Paramaribo</option>
                                                            <option value="America/Phoenix">America/Phoenix</option>
                                                            <option value="America/Port-au-Prince">America/Port-au-Prince</option>
                                                            <option value="America/Porto_Acre">America/Porto_Acre</option>
                                                            <option value="America/Porto_Velho">America/Porto_Velho</option>
                                                            <option value="America/Port_of_Spain">America/Port_of_Spain</option>
                                                            <option value="America/Puerto_Rico">America/Puerto_Rico</option>
                                                            <option value="America/Rainy_River">America/Rainy_River</option>
                                                            <option value="America/Rankin_Inlet">America/Rankin_Inlet</option>
                                                            <option value="America/Recife">America/Recife</option>
                                                            <option value="America/Regina">America/Regina</option>
                                                            <option value="America/Resolute">America/Resolute</option>
                                                            <option value="America/Rio_Branco">America/Rio_Branco</option>
                                                            <option value="America/Rosario">America/Rosario</option>
                                                            <option value="America/Santarem">America/Santarem</option>
                                                            <option value="America/Santa_Isabel">America/Santa_Isabel</option>
                                                            <option value="America/Santiago">America/Santiago</option>
                                                            <option value="America/Santo_Domingo">America/Santo_Domingo</option>
                                                            <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                                            <option value="America/Scoresbysund">America/Scoresbysund</option>
                                                            <option value="America/Shiprock">America/Shiprock</option>
                                                            <option value="America/Sitka">America/Sitka</option>
                                                            <option value="America/St_Barthelemy">America/St_Barthelemy</option>
                                                            <option value="America/St_Johns">America/St_Johns</option>
                                                            <option value="America/St_Kitts">America/St_Kitts</option>
                                                            <option value="America/St_Lucia">America/St_Lucia</option>
                                                            <option value="America/St_Thomas">America/St_Thomas</option>
                                                            <option value="America/St_Vincent">America/St_Vincent</option>
                                                            <option value="America/Swift_Current">America/Swift_Current</option>
                                                            <option value="America/Tegucigalpa">America/Tegucigalpa</option>
                                                            <option value="America/Thule">America/Thule</option>
                                                            <option value="America/Thunder_Bay">America/Thunder_Bay</option>
                                                            <option value="America/Tijuana">America/Tijuana</option>
                                                            <option value="America/Toronto">America/Toronto</option>
                                                            <option value="America/Tortola">America/Tortola</option>
                                                            <option value="America/Vancouver">America/Vancouver</option>
                                                            <option value="America/Virgin">America/Virgin</option>
                                                            <option value="America/Whitehorse">America/Whitehorse</option>
                                                            <option value="America/Winnipeg">America/Winnipeg</option>
                                                            <option value="America/Yakutat">America/Yakutat</option>
                                                            <option value="America/Yellowknife">America/Yellowknife</option>
                                                            <option value="Antarctica/Casey">Antarctica/Casey</option>
                                                            <option value="Antarctica/Davis">Antarctica/Davis</option>
                                                            <option value="Antarctica/DumontDUrville">Antarctica/DumontDUrville</option>
                                                            <option value="Antarctica/Macquarie">Antarctica/Macquarie</option>
                                                            <option value="Antarctica/Mawson">Antarctica/Mawson</option>
                                                            <option value="Antarctica/McMurdo">Antarctica/McMurdo</option>
                                                            <option value="Antarctica/Palmer">Antarctica/Palmer</option>
                                                            <option value="Antarctica/Rothera">Antarctica/Rothera</option>
                                                            <option value="Antarctica/South_Pole">Antarctica/South_Pole</option>
                                                            <option value="Antarctica/Syowa">Antarctica/Syowa</option>
                                                            <option value="Antarctica/Vostok">Antarctica/Vostok</option>
                                                            <option value="Arctic/Longyearbyen">Arctic/Longyearbyen</option>
                                                            <option value="Asia/Aden">Asia/Aden</option>
                                                            <option value="Asia/Almaty">Asia/Almaty</option>
                                                            <option value="Asia/Amman">Asia/Amman</option>
                                                            <option value="Asia/Anadyr">Asia/Anadyr</option>
                                                            <option value="Asia/Aqtau">Asia/Aqtau</option>
                                                            <option value="Asia/Aqtobe">Asia/Aqtobe</option>
                                                            <option value="Asia/Ashgabat">Asia/Ashgabat</option>
                                                            <option value="Asia/Ashkhabad">Asia/Ashkhabad</option>
                                                            <option value="Asia/Baghdad">Asia/Baghdad</option>
                                                            <option value="Asia/Bahrain">Asia/Bahrain</option>
                                                            <option value="Asia/Baku">Asia/Baku</option>
                                                            <option value="Asia/Bangkok">Asia/Bangkok</option>
                                                            <option value="Asia/Beirut">Asia/Beirut</option>
                                                            <option value="Asia/Bishkek">Asia/Bishkek</option>
                                                            <option value="Asia/Brunei">Asia/Brunei</option>
                                                            <option value="Asia/Calcutta">Asia/Calcutta</option>
                                                            <option value="Asia/Choibalsan">Asia/Choibalsan</option>
                                                            <option value="Asia/Chongqing">Asia/Chongqing</option>
                                                            <option value="Asia/Chungking">Asia/Chungking</option>
                                                            <option value="Asia/Colombo">Asia/Colombo</option>
                                                            <option value="Asia/Dacca">Asia/Dacca</option>
                                                            <option value="Asia/Damascus">Asia/Damascus</option>
                                                            <option value="Asia/Dhaka">Asia/Dhaka</option>
                                                            <option value="Asia/Dili">Asia/Dili</option>
                                                            <option value="Asia/Dubai">Asia/Dubai</option>
                                                            <option value="Asia/Dushanbe">Asia/Dushanbe</option>
                                                            <option value="Asia/Gaza">Asia/Gaza</option>
                                                            <option value="Asia/Harbin">Asia/Harbin</option>
                                                            <option value="Asia/Hebron">Asia/Hebron</option>
                                                            <option value="Asia/Hong_Kong">Asia/Hong_Kong</option>
                                                            <option value="Asia/Hovd">Asia/Hovd</option>
                                                            <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                                                            <option value="Asia/Irkutsk">Asia/Irkutsk</option>
                                                            <option value="Asia/Istanbul">Asia/Istanbul</option>
                                                            <option value="Asia/Jakarta">Asia/Jakarta</option>
                                                            <option value="Asia/Jayapura">Asia/Jayapura</option>
                                                            <option value="Asia/Jerusalem">Asia/Jerusalem</option>
                                                            <option value="Asia/Kabul">Asia/Kabul</option>
                                                            <option value="Asia/Kamchatka">Asia/Kamchatka</option>
                                                            <option value="Asia/Karachi">Asia/Karachi</option>
                                                            <option value="Asia/Kashgar">Asia/Kashgar</option>
                                                            <option value="Asia/Kathmandu">Asia/Kathmandu</option>
                                                            <option value="Asia/Katmandu">Asia/Katmandu</option>
                                                            <option value="Asia/Kolkata">Asia/Kolkata</option>
                                                            <option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk</option>
                                                            <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur</option>
                                                            <option value="Asia/Kuching">Asia/Kuching</option>
                                                            <option value="Asia/Kuwait">Asia/Kuwait</option>
                                                            <option value="Asia/Macao">Asia/Macao</option>
                                                            <option value="Asia/Macau">Asia/Macau</option>
                                                            <option value="Asia/Magadan">Asia/Magadan</option>
                                                            <option value="Asia/Makassar">Asia/Makassar</option>
                                                            <option value="Asia/Manila">Asia/Manila</option>
                                                            <option value="Asia/Muscat">Asia/Muscat</option>
                                                            <option value="Asia/Nicosia">Asia/Nicosia</option>
                                                            <option value="Asia/Novokuznetsk">Asia/Novokuznetsk</option>
                                                            <option value="Asia/Novosibirsk">Asia/Novosibirsk</option>
                                                            <option value="Asia/Omsk">Asia/Omsk</option>
                                                            <option value="Asia/Oral">Asia/Oral</option>
                                                            <option value="Asia/Phnom_Penh">Asia/Phnom_Penh</option>
                                                            <option value="Asia/Pontianak">Asia/Pontianak</option>
                                                            <option value="Asia/Pyongyang">Asia/Pyongyang</option>
                                                            <option value="Asia/Qatar">Asia/Qatar</option>
                                                            <option value="Asia/Qyzylorda">Asia/Qyzylorda</option>
                                                            <option value="Asia/Rangoon">Asia/Rangoon</option>
                                                            <option value="Asia/Riyadh">Asia/Riyadh</option>
                                                            <option value="Asia/Saigon">Asia/Saigon</option>
                                                            <option value="Asia/Sakhalin">Asia/Sakhalin</option>
                                                            <option value="Asia/Samarkand">Asia/Samarkand</option>
                                                            <option value="Asia/Seoul">Asia/Seoul</option>
                                                            <option value="Asia/Shanghai">Asia/Shanghai</option>
                                                            <option value="Asia/Singapore">Asia/Singapore</option>
                                                            <option value="Asia/Taipei">Asia/Taipei</option>
                                                            <option value="Asia/Tashkent">Asia/Tashkent</option>
                                                            <option value="Asia/Tbilisi">Asia/Tbilisi</option>
                                                            <option value="Asia/Tehran">Asia/Tehran</option>
                                                            <option value="Asia/Tel_Aviv">Asia/Tel_Aviv</option>
                                                            <option value="Asia/Thimbu">Asia/Thimbu</option>
                                                            <option value="Asia/Thimphu">Asia/Thimphu</option>
                                                            <option value="Asia/Tokyo">Asia/Tokyo</option>
                                                            <option value="Asia/Ujung_Pandang">Asia/Ujung_Pandang</option>
                                                            <option value="Asia/Ulaanbaatar">Asia/Ulaanbaatar</option>
                                                            <option value="Asia/Ulan_Bator">Asia/Ulan_Bator</option>
                                                            <option value="Asia/Urumqi">Asia/Urumqi</option>
                                                            <option value="Asia/Vientiane">Asia/Vientiane</option>
                                                            <option value="Asia/Vladivostok">Asia/Vladivostok</option>
                                                            <option value="Asia/Yakutsk">Asia/Yakutsk</option>
                                                            <option value="Asia/Yekaterinburg">Asia/Yekaterinburg</option>
                                                            <option value="Asia/Yerevan">Asia/Yerevan</option>
                                                            <option value="Atlantic/Azores">Atlantic/Azores</option>
                                                            <option value="Atlantic/Bermuda">Atlantic/Bermuda</option>
                                                            <option value="Atlantic/Canary">Atlantic/Canary</option>
                                                            <option value="Atlantic/Cape_Verde">Atlantic/Cape_Verde</option>
                                                            <option value="Atlantic/Faeroe">Atlantic/Faeroe</option>
                                                            <option value="Atlantic/Faroe">Atlantic/Faroe</option>
                                                            <option value="Atlantic/Jan_Mayen">Atlantic/Jan_Mayen</option>
                                                            <option value="Atlantic/Madeira">Atlantic/Madeira</option>
                                                            <option value="Atlantic/Reykjavik">Atlantic/Reykjavik</option>
                                                            <option value="Atlantic/South_Georgia">Atlantic/South_Georgia</option>
                                                            <option value="Atlantic/Stanley">Atlantic/Stanley</option>
                                                            <option value="Atlantic/St_Helena">Atlantic/St_Helena</option>
                                                            <option value="Australia/ACT">Australia/ACT</option>
                                                            <option value="Australia/Adelaide">Australia/Adelaide</option>
                                                            <option value="Australia/Brisbane">Australia/Brisbane</option>
                                                            <option value="Australia/Broken_Hill">Australia/Broken_Hill</option>
                                                            <option value="Australia/Canberra">Australia/Canberra</option>
                                                            <option value="Australia/Currie">Australia/Currie</option>
                                                            <option value="Australia/Darwin">Australia/Darwin</option>
                                                            <option value="Australia/Eucla">Australia/Eucla</option>
                                                            <option value="Australia/Hobart">Australia/Hobart</option>
                                                            <option value="Australia/LHI">Australia/LHI</option>
                                                            <option value="Australia/Lindeman">Australia/Lindeman</option>
                                                            <option value="Australia/Lord_Howe">Australia/Lord_Howe</option>
                                                            <option value="Australia/Melbourne">Australia/Melbourne</option>
                                                            <option value="Australia/North">Australia/North</option>
                                                            <option value="Australia/NSW">Australia/NSW</option>
                                                            <option value="Australia/Perth">Australia/Perth</option>
                                                            <option value="Australia/Queensland">Australia/Queensland</option>
                                                            <option value="Australia/South">Australia/South</option>
                                                            <option value="Australia/Sydney">Australia/Sydney</option>
                                                            <option value="Australia/Tasmania">Australia/Tasmania</option>
                                                            <option value="Australia/Victoria">Australia/Victoria</option>
                                                            <option value="Australia/West">Australia/West</option>
                                                            <option value="Australia/Yancowinna">Australia/Yancowinna</option>
                                                            <option value="Brazil/Acre">Brazil/Acre</option>
                                                            <option value="Brazil/DeNoronha">Brazil/DeNoronha</option>
                                                            <option value="Brazil/East">Brazil/East</option>
                                                            <option value="Brazil/West">Brazil/West</option>
                                                            <option value="Canada/Atlantic">Canada/Atlantic</option>
                                                            <option value="Canada/Central">Canada/Central</option>
                                                            <option value="Canada/East-Saskatchewan">Canada/East-Saskatchewan</option>
                                                            <option value="Canada/Eastern">Canada/Eastern</option>
                                                            <option value="Canada/Mountain">Canada/Mountain</option>
                                                            <option value="Canada/Newfoundland">Canada/Newfoundland</option>
                                                            <option value="Canada/Pacific">Canada/Pacific</option>
                                                            <option value="Canada/Saskatchewan">Canada/Saskatchewan</option>
                                                            <option value="Canada/Yukon">Canada/Yukon</option>
                                                            <option value="CET">CET</option>
                                                            <option value="Chile/Continental">Chile/Continental</option>
                                                            <option value="Chile/EasterIsland">Chile/EasterIsland</option>
                                                            <option value="CST6CDT">CST6CDT</option>
                                                            <option value="Cuba">Cuba</option>
                                                            <option value="EET">EET</option>
                                                            <option value="Egypt">Egypt</option>
                                                            <option value="Eire">Eire</option>
                                                            <option value="EST">EST</option>
                                                            <option value="EST5EDT">EST5EDT</option>
                                                            <option value="Etc/GMT">Etc/GMT</option>
                                                            <option value="Etc/GMT+0">Etc/GMT+0</option>
                                                            <option value="Etc/GMT+1">Etc/GMT+1</option>
                                                            <option value="Etc/GMT+10">Etc/GMT+10</option>
                                                            <option value="Etc/GMT+11">Etc/GMT+11</option>
                                                            <option value="Etc/GMT+12">Etc/GMT+12</option>
                                                            <option value="Etc/GMT+2">Etc/GMT+2</option>
                                                            <option value="Etc/GMT+3">Etc/GMT+3</option>
                                                            <option value="Etc/GMT+4">Etc/GMT+4</option>
                                                            <option value="Etc/GMT+5">Etc/GMT+5</option>
                                                            <option value="Etc/GMT+6">Etc/GMT+6</option>
                                                            <option value="Etc/GMT+7">Etc/GMT+7</option>
                                                            <option value="Etc/GMT+8">Etc/GMT+8</option>
                                                            <option value="Etc/GMT+9">Etc/GMT+9</option>
                                                            <option value="Etc/GMT-0">Etc/GMT-0</option>
                                                            <option value="Etc/GMT-1">Etc/GMT-1</option>
                                                            <option value="Etc/GMT-10">Etc/GMT-10</option>
                                                            <option value="Etc/GMT-11">Etc/GMT-11</option>
                                                            <option value="Etc/GMT-12">Etc/GMT-12</option>
                                                            <option value="Etc/GMT-13">Etc/GMT-13</option>
                                                            <option value="Etc/GMT-14">Etc/GMT-14</option>
                                                            <option value="Etc/GMT-2">Etc/GMT-2</option>
                                                            <option value="Etc/GMT-3">Etc/GMT-3</option>
                                                            <option value="Etc/GMT-4">Etc/GMT-4</option>
                                                            <option value="Etc/GMT-5">Etc/GMT-5</option>
                                                            <option value="Etc/GMT-6">Etc/GMT-6</option>
                                                            <option value="Etc/GMT-7">Etc/GMT-7</option>
                                                            <option value="Etc/GMT-8">Etc/GMT-8</option>
                                                            <option value="Etc/GMT-9">Etc/GMT-9</option>
                                                            <option value="Etc/GMT0">Etc/GMT0</option>
                                                            <option value="Etc/Greenwich">Etc/Greenwich</option>
                                                            <option value="Etc/UCT">Etc/UCT</option>
                                                            <option value="Etc/Universal">Etc/Universal</option>
                                                            <option value="Etc/UTC">Etc/UTC</option>
                                                            <option value="Etc/Zulu">Etc/Zulu</option>
                                                            <option value="Europe/Amsterdam">Europe/Amsterdam</option>
                                                            <option value="Europe/Andorra">Europe/Andorra</option>
                                                            <option value="Europe/Athens">Europe/Athens</option>
                                                            <option value="Europe/Belfast">Europe/Belfast</option>
                                                            <option value="Europe/Belgrade">Europe/Belgrade</option>
                                                            <option value="Europe/Berlin">Europe/Berlin</option>
                                                            <option value="Europe/Bratislava">Europe/Bratislava</option>
                                                            <option value="Europe/Brussels">Europe/Brussels</option>
                                                            <option value="Europe/Bucharest">Europe/Bucharest</option>
                                                            <option value="Europe/Budapest">Europe/Budapest</option>
                                                            <option value="Europe/Chisinau">Europe/Chisinau</option>
                                                            <option value="Europe/Copenhagen">Europe/Copenhagen</option>
                                                            <option value="Europe/Dublin">Europe/Dublin</option>
                                                            <option value="Europe/Gibraltar">Europe/Gibraltar</option>
                                                            <option value="Europe/Guernsey">Europe/Guernsey</option>
                                                            <option value="Europe/Helsinki">Europe/Helsinki</option>
                                                            <option value="Europe/Isle_of_Man">Europe/Isle_of_Man</option>
                                                            <option value="Europe/Istanbul">Europe/Istanbul</option>
                                                            <option value="Europe/Jersey">Europe/Jersey</option>
                                                            <option value="Europe/Kaliningrad">Europe/Kaliningrad</option>
                                                            <option value="Europe/Kiev">Europe/Kiev</option>
                                                            <option value="Europe/Lisbon">Europe/Lisbon</option>
                                                            <option value="Europe/Ljubljana">Europe/Ljubljana</option>
                                                            <option value="Europe/London">Europe/London</option>
                                                            <option value="Europe/Luxembourg">Europe/Luxembourg</option>
                                                            <option value="Europe/Madrid">Europe/Madrid</option>
                                                            <option value="Europe/Malta">Europe/Malta</option>
                                                            <option value="Europe/Mariehamn">Europe/Mariehamn</option>
                                                            <option value="Europe/Minsk">Europe/Minsk</option>
                                                            <option value="Europe/Monaco">Europe/Monaco</option>
                                                            <option value="Europe/Moscow">Europe/Moscow</option>
                                                            <option value="Europe/Nicosia">Europe/Nicosia</option>
                                                            <option value="Europe/Oslo">Europe/Oslo</option>
                                                            <option value="Europe/Paris">Europe/Paris</option>
                                                            <option value="Europe/Podgorica">Europe/Podgorica</option>
                                                            <option value="Europe/Prague">Europe/Prague</option>
                                                            <option value="Europe/Riga">Europe/Riga</option>
                                                            <option value="Europe/Rome">Europe/Rome</option>
                                                            <option value="Europe/Samara">Europe/Samara</option>
                                                            <option value="Europe/San_Marino">Europe/San_Marino</option>
                                                            <option value="Europe/Sarajevo">Europe/Sarajevo</option>
                                                            <option value="Europe/Simferopol">Europe/Simferopol</option>
                                                            <option value="Europe/Skopje">Europe/Skopje</option>
                                                            <option value="Europe/Sofia">Europe/Sofia</option>
                                                            <option value="Europe/Stockholm">Europe/Stockholm</option>
                                                            <option value="Europe/Tallinn">Europe/Tallinn</option>
                                                            <option value="Europe/Tirane">Europe/Tirane</option>
                                                            <option value="Europe/Tiraspol">Europe/Tiraspol</option>
                                                            <option value="Europe/Uzhgorod">Europe/Uzhgorod</option>
                                                            <option value="Europe/Vaduz">Europe/Vaduz</option>
                                                            <option value="Europe/Vatican">Europe/Vatican</option>
                                                            <option value="Europe/Vienna">Europe/Vienna</option>
                                                            <option value="Europe/Vilnius">Europe/Vilnius</option>
                                                            <option value="Europe/Volgograd">Europe/Volgograd</option>
                                                            <option value="Europe/Warsaw">Europe/Warsaw</option>
                                                            <option value="Europe/Zagreb">Europe/Zagreb</option>
                                                            <option value="Europe/Zaporozhye">Europe/Zaporozhye</option>
                                                            <option value="Europe/Zurich">Europe/Zurich</option>
                                                            <option value="Factory">Factory</option>
                                                            <option value="GB">GB</option>
                                                            <option value="GB-Eire">GB-Eire</option>
                                                            <option value="GMT">GMT</option>
                                                            <option value="GMT+0">GMT+0</option>
                                                            <option value="GMT-0">GMT-0</option>
                                                            <option value="GMT0">GMT0</option>
                                                            <option value="Greenwich">Greenwich</option>
                                                            <option value="Hongkong">Hongkong</option>
                                                            <option value="HST">HST</option>
                                                            <option value="Iceland">Iceland</option>
                                                            <option value="Indian/Antananarivo">Indian/Antananarivo</option>
                                                            <option value="Indian/Chagos">Indian/Chagos</option>
                                                            <option value="Indian/Christmas">Indian/Christmas</option>
                                                            <option value="Indian/Cocos">Indian/Cocos</option>
                                                            <option value="Indian/Comoro">Indian/Comoro</option>
                                                            <option value="Indian/Kerguelen">Indian/Kerguelen</option>
                                                            <option value="Indian/Mahe">Indian/Mahe</option>
                                                            <option value="Indian/Maldives">Indian/Maldives</option>
                                                            <option value="Indian/Mauritius">Indian/Mauritius</option>
                                                            <option value="Indian/Mayotte">Indian/Mayotte</option>
                                                            <option value="Indian/Reunion">Indian/Reunion</option>
                                                            <option value="Iran">Iran</option>
                                                            <option value="Israel">Israel</option>
                                                            <option value="Jamaica">Jamaica</option>
                                                            <option value="Japan">Japan</option>
                                                            <option value="Kwajalein">Kwajalein</option>
                                                            <option value="Libya">Libya</option>
                                                            <option value="MET">MET</option>
                                                            <option value="Mexico/BajaNorte">Mexico/BajaNorte</option>
                                                            <option value="Mexico/BajaSur">Mexico/BajaSur</option>
                                                            <option value="Mexico/General">Mexico/General</option>
                                                            <option value="MST">MST</option>
                                                            <option value="MST7MDT">MST7MDT</option>
                                                            <option value="Navajo">Navajo</option>
                                                            <option value="NZ">NZ</option>
                                                            <option value="NZ-CHAT">NZ-CHAT</option>
                                                            <option value="Pacific/Apia">Pacific/Apia</option>
                                                            <option value="Pacific/Auckland">Pacific/Auckland</option>
                                                            <option value="Pacific/Chatham">Pacific/Chatham</option>
                                                            <option value="Pacific/Chuuk">Pacific/Chuuk</option>
                                                            <option value="Pacific/Easter">Pacific/Easter</option>
                                                            <option value="Pacific/Efate">Pacific/Efate</option>
                                                            <option value="Pacific/Enderbury">Pacific/Enderbury</option>
                                                            <option value="Pacific/Fakaofo">Pacific/Fakaofo</option>
                                                            <option value="Pacific/Fiji">Pacific/Fiji</option>
                                                            <option value="Pacific/Funafuti">Pacific/Funafuti</option>
                                                            <option value="Pacific/Galapagos">Pacific/Galapagos</option>
                                                            <option value="Pacific/Gambier">Pacific/Gambier</option>
                                                            <option value="Pacific/Guadalcanal">Pacific/Guadalcanal</option>
                                                            <option value="Pacific/Guam">Pacific/Guam</option>
                                                            <option value="Pacific/Honolulu">Pacific/Honolulu</option>
                                                            <option value="Pacific/Johnston">Pacific/Johnston</option>
                                                            <option value="Pacific/Kiritimati">Pacific/Kiritimati</option>
                                                            <option value="Pacific/Kosrae">Pacific/Kosrae</option>
                                                            <option value="Pacific/Kwajalein">Pacific/Kwajalein</option>
                                                            <option value="Pacific/Majuro">Pacific/Majuro</option>
                                                            <option value="Pacific/Marquesas">Pacific/Marquesas</option>
                                                            <option value="Pacific/Midway">Pacific/Midway</option>
                                                            <option value="Pacific/Nauru">Pacific/Nauru</option>
                                                            <option value="Pacific/Niue">Pacific/Niue</option>
                                                            <option value="Pacific/Norfolk">Pacific/Norfolk</option>
                                                            <option value="Pacific/Noumea">Pacific/Noumea</option>
                                                            <option value="Pacific/Pago_Pago">Pacific/Pago_Pago</option>
                                                            <option value="Pacific/Palau">Pacific/Palau</option>
                                                            <option value="Pacific/Pitcairn">Pacific/Pitcairn</option>
                                                            <option value="Pacific/Pohnpei">Pacific/Pohnpei</option>
                                                            <option value="Pacific/Ponape">Pacific/Ponape</option>
                                                            <option value="Pacific/Port_Moresby">Pacific/Port_Moresby</option>
                                                            <option value="Pacific/Rarotonga">Pacific/Rarotonga</option>
                                                            <option value="Pacific/Saipan">Pacific/Saipan</option>
                                                            <option value="Pacific/Samoa">Pacific/Samoa</option>
                                                            <option value="Pacific/Tahiti">Pacific/Tahiti</option>
                                                            <option value="Pacific/Tarawa">Pacific/Tarawa</option>
                                                            <option value="Pacific/Tongatapu">Pacific/Tongatapu</option>
                                                            <option value="Pacific/Truk">Pacific/Truk</option>
                                                            <option value="Pacific/Wake">Pacific/Wake</option>
                                                            <option value="Pacific/Wallis">Pacific/Wallis</option>
                                                            <option value="Pacific/Yap">Pacific/Yap</option>
                                                            <option value="Poland">Poland</option>
                                                            <option value="Portugal">Portugal</option>
                                                            <option value="PRC">PRC</option>
                                                            <option value="PST8PDT">PST8PDT</option>
                                                            <option value="ROC">ROC</option>
                                                            <option value="ROK">ROK</option>
                                                            <option value="Singapore">Singapore</option>
                                                            <option value="Turkey">Turkey</option>
                                                            <option value="UCT">UCT</option>
                                                            <option value="Universal">Universal</option>
                                                            <option value="US/Alaska">US/Alaska</option>
                                                            <option value="US/Aleutian">US/Aleutian</option>
                                                            <option value="US/Arizona">US/Arizona</option>
                                                            <option value="US/Central">US/Central</option>
                                                            <option value="US/East-Indiana">US/East-Indiana</option>
                                                            <option value="US/Eastern">US/Eastern</option>
                                                            <option value="US/Hawaii">US/Hawaii</option>
                                                            <option value="US/Indiana-Starke">US/Indiana-Starke</option>
                                                            <option value="US/Michigan">US/Michigan</option>
                                                            <option value="US/Mountain">US/Mountain</option>
                                                            <option value="US/Pacific">US/Pacific</option>
                                                            <option value="US/Pacific-New">US/Pacific-New</option>
                                                            <option value="US/Samoa">US/Samoa</option>
                                                            <option value="UTC">UTC</option>
                                                            <option value="W-SU">W-SU</option>
                                                            <option value="WET">WET</option>
                                                            <option value="Zulu">Zulu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="m-t-sm help-block"><?php echo SCTEXT('You can choose the schedule time & timezone based on your preference. The app will automatically convert it to the SYSTEM timezone for accurate delivery.') ?></span>
                                            </div>
                                        </div>


                                        <div class="form-group m-t-lg">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-8">
                                                <button class="btn btn-primary m-t-sm" id="submitsms" type="button"><i class="fa fa-lg fa-check"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Campaign') ?></button>
                                                <button id="smsprev" type="button" class="btn btn-default m-l-sm m-t-sm"><i class="fa fa-lg fa-search"></i>&nbsp;&nbsp; <?php echo SCTEXT('Preview SMS') ?></button>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </form>


                            <!-- various boxes -->
                            <?php if ($_SESSION['permissions']['master'] == '1' || isset($_SESSION['permissions']['messaging']['templates'])) { ?>
                                <div class="modal fade" id="templateBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"><?php echo SCTEXT('Select SMS Template') ?></h4>
                                            </div>
                                            <div id="tboxbody" class="modal-body p-lg">
                                                <div>
                                                    <table id="dt_stmp" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getUseTemplates', language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'} }" class="wd100 table sc_responsive row-border order-column m-xs">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo SCTEXT('Name') ?></th>
                                                                <th><?php echo SCTEXT('Template Text') ?></th>
                                                                <?php if ($_SESSION['user']['account_type'] == '0') { ?> <th><?php echo SCTEXT('Route') ?></th> <?php } ?>
                                                                <th><?php echo SCTEXT('Status') ?></th>
                                                                <th><?php echo SCTEXT('Actions') ?></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo SCTEXT('Close') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($_SESSION['permissions']['master'] == '1' || isset($_SESSION['permissions']['messaging']['tinyurl'])) { ?>
                                <div class="modal fade" id="tinyurlbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"><?php echo SCTEXT('Select Short URL') ?></h4>
                                            </div>
                                            <div id="uboxbody" class="modal-body p-lg">
                                                <div>
                                                    <table id="dt_turl" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getUseShortUrls', pageLength: 5, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, dom: '<<\'clearfix\'<\'col-md-3\'B><\'col-md-5\'><\'col-md-4\'f>><tip>>', buttons:[{text:'<i class=\'fa m-r-sm fa-large fa-repeat\' title=\'Reload Links\'></i>Reload Links',action: function(e, dt, node, config){dt.ajax.reload(null, false);}}]}" class="wd100 table sc_responsive row-border order-column m-xs">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo SCTEXT('Short URL') ?></th>
                                                                <th><?php echo SCTEXT('Destination') ?></th>
                                                                <th><?php echo SCTEXT('Type') ?></th>
                                                                <th><?php echo SCTEXT('Actions') ?></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="<?php echo Doo::conf()->APP_URL ?>addShortUrl" target="_blank" class="btn btn-primary"><?php echo SCTEXT('Add New URL') ?></a>
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo SCTEXT('Close') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($_SESSION['permissions']['master'] == '1' || isset($_SESSION['permissions']['messaging']['media_links'])) { ?>
                                <div class="modal fade" id="medbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"><?php echo SCTEXT('Select Media') ?></h4>
                                            </div>
                                            <div id="mboxbody" class="modal-body p-lg">
                                                <div>
                                                    <table id="dt_smed" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getUseCampaignMedia', columns: [{width:'20%'},null,null], pageLength: 5, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, dom: '<<\'clearfix\'<\'col-md-3\'B><\'col-md-5\'><\'col-md-4\'f>><tip>>', buttons:[{text:'<i class=\'fa m-r-sm fa-large fa-repeat\' title=\'Reload Media List\'></i>Reload List',action: function(e, dt, node, config){dt.ajax.reload(null, false);}}]}" class="wd100 table sc_responsive row-border order-column m-xs">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo SCTEXT('File Info') ?></th>
                                                                <th><?php echo SCTEXT('Title') ?></th>
                                                                <th><?php echo SCTEXT('Actions') ?></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="<?php echo Doo::conf()->APP_URL ?>addCampaignMedia" target="_blank" class="btn btn-primary"><?php echo SCTEXT('Upload New Media') ?></a>
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo SCTEXT('Close') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>








                            <div id="smspreview" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body" style="text-align:center;">
                                            <div id="main_preview_ctr" class="col-md-12">
                                                <div id="preview_locked">
                                                    <div id="preview_locked_notif">
                                                        <div id="preview_locked_notif_head"></div>
                                                        <div id="preview_locked_notif_sender">
                                                        </div>
                                                        <div id="preview_locked_notif_text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="preview_open" style="padding-left: 44px;">
                                                    <div id="preview_open_sender"></div>
                                                    <div id="preview_open_msg" class="yours messages">
                                                        <div id="preview_open_msg_text" class="message last text-left">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" data-dismiss="modal"><i class="icon-ok"></i><?php echo SCTEXT('OK, Cool') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end content -->
                        </div>
                    </div>
                </div>
            </div>

        </section>