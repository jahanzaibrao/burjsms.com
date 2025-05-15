<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Send WhatsApp Campaign') ?><small><?php echo SCTEXT('launch a WhatsApp campaign from here') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <form class="form-horizontal" method="post" id="wcmp_frm" data-plugin="dropzone" action="" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,text/csv,.xls', addRemoveLinks:true, params:{mode:'sendsms'}, success: function(file,res){createInputFile('wcmp_frm',res,'sendsms'); $('#uprocess').val('0'); $('.dz-message').addClass('hidden');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'sendsms');$('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <input type="hidden" id="ufilecno" value="0" />
                                    <div class="row clearfix">
                                        <div class="col-md-6">
                                            <div class="form-group hidden">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Campaign Name') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter name for your WhatsApp campaign') ?>. . . ." maxlength="100" />
                                                </div>
                                            </div>
                                            <?php
                                            if ($_SESSION['user']['group'] == "admin") {
                                                //for admin
                                                // $url_sw = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_business_id . '/owned_whatsapp_business_accounts';
                                                // $options = array('http' => array(
                                                //     'method'  => 'GET',
                                                //     'header' => 'Authorization: Bearer ' . Doo::conf()->wba_perm_token
                                                // ));
                                                // $context  = stream_context_create($options);
                                                // $waba_list = json_decode(file_get_contents($url_sw, false, $context), true);
                                                // $all_wabas  = array();
                                                // //foreach WABA get the details
                                                // foreach ($waba_list['data'] as $waba) {
                                                //     $waba_id = $waba['id'];
                                                //     $waba_business_name = $waba['name'];
                                                //     //get phone numbers from WABA ID
                                                //     $wp_url = 'https://graph.facebook.com/v19.0/' . $waba_id . '/phone_numbers';
                                                //     $waba_nums = json_decode(file_get_contents($wp_url, false, $context), true);
                                                //     $waba_phn_bp = [];
                                                //     foreach ($waba_nums['data'] as $wabaphn) {
                                                //         $waba_phonenumber_id = $wabaphn['id'];
                                                //         //get business profile using phone number id of the first registed phone number
                                                //         $wba_url = 'https://graph.facebook.com/v19.0/' . $waba_phonenumber_id . '/whatsapp_business_profile?fields=about,address,description,email,profile_picture_url,websites,vertical';
                                                //         $waba_bp = json_decode(file_get_contents($wba_url, false, $context), true);
                                                //         array_push($waba_phn_bp, $waba_bp['data']);
                                                //     }
                                                //     //save in array
                                                //     $complete_waba['waba_business_profiles'] = $waba_phn_bp;
                                                //     $complete_waba['waba_phone_data'] = $waba_nums['data'];
                                                //     array_push($all_wabas, $complete_waba);
                                                // }
                                            } else {
                                                //for regular clients
                                            }
                                            // unset($waba_list, $waba, $waba_id, $waba_business_name, $wp_url, $waba_nums, $waba_phn_bp, $waba_phonenumber_id, $wba_url, $waba_bp, $complete_waba);
                                            // $data['all_wabas'] = $all_wabas;
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Account') ?>:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" class="disabled" data-plugin="select2" name="waba" id="waba">
                                                        <?php foreach ($data['bprofiles'] as $waba) {
                                                            $i = 0;
                                                            $profile_pic = trim($waba->bp_profile_picture) == "" ? "http://placehold.it/300x300" : $waba->bp_profile_picture;

                                                        ?>
                                                            <option data-name="<?php echo $waba->verified_name ?>" data-pic="<?php echo $profile_pic ?>" value="<?php echo $waba->phone_id ?>"><?php echo $waba->display_phone ?></option>
                                                        <?php
                                                        } ?>
                                                    </select>
                                                    <hr>
                                                    <div class="media-group-item p-t-0">

                                                        <div class="media">
                                                            <div class="media-left">
                                                                <div class="avatar avatar-xlg avatar-circle"><a href="javascript:void(0);"><img id="wbpic" src="" alt=""></a></div>
                                                            </div>
                                                            <div class="media-body p-t-xs">
                                                                <h5 class="m-t-0 m-b-0"><a id="wbname2" href="javascript:void(0);" class="m-r-xs text-inverse"></a></h5>
                                                                <p class="m-b-0" style="font-size: 16px;"><i class="fa fa-lg fa-mobile m-r-xs"></i> <b id="wbphn"></b></p>
                                                                <p class="m-b-xs" style="font-size: 12px;font-style: Italic;"> Messaging limit: 1000/Day</p>
                                                                <span class="m-b-sm label label-success label-sm">connected</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <?php
                                            //get templates
                                            $tempobj = Doo::loadModel('WbaTemplates', true);
                                            $tempobj->user_id = $_SESSION['user']['userid'];
                                            $data['temps'] = Doo::db()->find($tempobj);
                                            // echo '<pre>';
                                            // var_dump($data['temps']);
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Message Template') ?>:</label>
                                                <div class="col-md-8">
                                                    <select data-plugin="select2" class="form-control" name="template" id="wtemp">
                                                        <option value="0">Select a WhatsApp Template</option>
                                                        <?php foreach ($data['temps'] as $temp) {
                                                            //get the variabls to be replaced in the message
                                                            $components = json_decode($temp->components);
                                                            $vars = '';
                                                            $header = array_values(array_filter($components, function ($component) {
                                                                return $component->type == 'HEADER';
                                                            }));
                                                            $body = array_values(array_filter($components, function ($component) {
                                                                return $component->type == 'BODY';
                                                            }));
                                                            $buttons = array_values(array_filter($components, function ($component) {
                                                                return $component->type == 'BUTTONS';
                                                            }));

                                                            // echo '<pre>';
                                                            // var_dump($buttons);

                                                            $vars .= preg_match_all('/\{\{[0-9]+\}\}/', $header[0]->text, $matches) . ',';
                                                            $vars .= preg_match_all('/\{\{[0-9]+\}\}/', $body[0]->text, $matches) . ',';
                                                            if (!$buttons) {
                                                                $vars .= '0';
                                                            } else {
                                                                $var_url_buttons = array_filter($buttons[0]->buttons, function ($button) {
                                                                    return $button->type == 'URL' && is_array($button->example);
                                                                });
                                                                $vars .= count($var_url_buttons);
                                                            }
                                                            $meta = json_decode($temp->meta_info);

                                                        ?>
                                                            <option data-vars="<?php echo $vars ?>" value="<?php echo $temp->name . '|' . $meta->language . '|' . base64_encode(json_encode($components)) ?>"><?php echo $temp->name ?></option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Add Recepients') ?>:</label>
                                                <div class="col-md-8">
                                                    <div id="mycontactbox">
                                                        <div class="widget m-b-0 text-center">
                                                            <div class="m-b-0 nav-tabs-horizontal">
                                                                <ul class="nav nav-tabs" role="tablist">
                                                                    <?php /* <li role="presentation"><a class="p-t-xs" href="#tab-1" aria-controls="tab-3" role="tab" data-toggle="tab"><?php echo SCTEXT('Upload Contacts') ?></a></li>*/ ?>
                                                                    <li role="presentation"><a class="p-t-xs" href="#tab-2" aria-controls="tab-1" role="tab" data-toggle="tab"><?php echo SCTEXT('Contact Groups') ?></a></li>
                                                                    <li role="presentation" class="active"><a class="p-t-xs" href="#tab-3" aria-controls="tab-2" role="tab" data-toggle="tab"><?php echo SCTEXT('Enter Manually') ?></a></li>
                                                                </ul>
                                                                <div class="tab-content p-md">
                                                                    <div role="tabpanel" class="tab-pane fade" id="tab-1">
                                                                        <div class="dropzone text-center">
                                                                            <div class="dz-message">
                                                                                <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>

                                                                            </div>
                                                                            <p class="m-b-lg">( Upload <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/XLS- sample file.xls" target="_blank"><u>xls</u></a>, <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/XLSX- sample file.xlsx" target="_blank"><u>xlsx</u></a>, <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/CSV- sample file.csv" target="_blank"><u>csv</u></a> or <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/TXT- sample file.txt" target="_blank"><u>txt</u></a> files )</p>
                                                                        </div>

                                                                    </div>
                                                                    <div role="tabpanel" class="tab-pane fade" id="tab-2">
                                                                        <select id="grpsel" class="form-control" data-plugin="select2" name="group" data-options="{placeholder: '<?php echo SCTEXT('Select Groups') ?>. . . .'}">
                                                                            <option value="0">Select a Group</option>

                                                                            <?php foreach ($data['gdata'] as $grp) { ?>
                                                                                <option data-colstr="<?php echo base64_encode($grp['colstr']) ?>" data-name="<?php echo $grp['name'] ?>" value="<?php echo $grp['id'] ?>" data-count="<?php echo $grp['count'] ?>"><?php echo $grp['name'] . ' (' . number_format($grp['count']) . ' Contacts)' ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div role="tabpanel" class="tab-pane in active fade" id="tab-3">
                                                                        <textarea id="contactinput" class="form-control pop-over" name="numbers" placeholder="<?php echo SCTEXT('enter mobile numbers') ?>. . . ." data-placement="top" data-content="<?php echo SCTEXT('Enter mobile numbers separated by newline e.g.') ?> <br><p>9876xxxxx<br>8901xxxxx<br>9015xxxxxx</p>.... and so on" data-trigger="hover"></textarea>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="hidden form-horizontal" id="wa_colsel">
                                                                <h4></h4>
                                                                <div class="input-group m-sm"><span class="input-group-addon">Header Variable</span><select class="form-control input-sm" name="taxtype">
                                                                        <option value="GT">GST</option>
                                                                    </select> </div>
                                                                <div class="input-group m-sm"><span class="input-group-addon">Body Variable 1</span><select class="form-control input-sm" name="taxtype">
                                                                        <option value="GT">GST</option>
                                                                    </select> </div>
                                                                <div class="input-group m-sm"><span class="input-group-addon">Body Variable 2</span><select class="form-control input-sm" name="taxtype">
                                                                        <option value="GT">GST</option>
                                                                    </select> </div>
                                                            </div>
                                                            <span class="help-block m-b-0 text-dark">
                                                                <div class="checkbox checkbox-primary checkbox-inline">
                                                                    <input name="rminv" id="rminv" checked="checked" type="checkbox">
                                                                    <label for="rminv"><?php echo SCTEXT('I confirm that the contacts uploaded have agreed to receive this communication') ?></label>
                                                                </div>

                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                        <div class="col-md-6" style="text-align:center; padding-top:20px; min-height: 720px;">

                                            <div id="main_preview_ctr" class="col-md-12">
                                                <div id="preview_open" style="">
                                                    <div id="preview_open_sender"></div>
                                                    <div id="preview_open_msg" class="yours messages" style="background-image: url('/global/img/waba-chat-bg.png');">
                                                        <div class="panel">
                                                            <div class="panel-body mt-5" style="background-color: #dcd6d0; padding: 10px 6px !important;">

                                                                <div class="d-flex justify-content-start mb-4">
                                                                    <div id="wa_preview_temp" class="message-bubble message-received">

                                                                        <p id="wt_header" class="m-b-md">
                                                                            - SELECT A TEMPLATE -
                                                                        </p>
                                                                        <br>
                                                                        <p id="wt_body" class="fz-sm"></p>
                                                                        <br><br>
                                                                        <span id="wt_footer" class="fz-sm"> </span>
                                                                        <hr>
                                                                        <div id="wt_btns" class="btn-ctr">

                                                                        </div>
                                                                        <span class="message-time"><?php echo date('h:i A') ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div id="preview_locked">
                                                    <div id="preview_locked_notif">
                                                        <div id="preview_locked_notif_head_w"></div>
                                                        <div id="preview_locked_notif_sender">
                                                        </div>
                                                        <div id="preview_locked_notif_text">
                                                            1 Message Received
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
                                    <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Submit Campaign') ?></button>
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
        #preview_open_msg,
        #conf_preview_open_msg {
            position: absolute;
            width: 299px;
            max-height: 460px;
            min-height: 460px;
            overflow: auto;
            right: 8.7%;
        }

        .messages {
            margin-top: -10px;
            display: flex;
            flex-direction: column;
        }

        #preview_locked {
            background-image: url(/global/skin/assets/images/preview_locked.png);
            width: 230px;
            height: 500px;
            padding-top: 145px;
            background-repeat: no-repeat;
            display: inline-block;
            background-position: 50% 0;
            background-size: 230px;
        }

        #preview_locked_notif {
            margin: 0px 20px;
            background-color: rgba(255, 255, 255, 0.7);
            max-height: 64px;
            padding-bottom: 5px;
            width: 190px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06);
            position: absolute;
        }

        #preview_locked_notif_head_w {
            background-image: url(/global/skin/assets/images/wa-now.png);
            height: 18px;
            background-repeat: no-repeat;
            display: inline-block;
            width: 190px;
            background-size: 186px;
            background-position: 2px 5px;
            z-index: 9;
        }

        #preview_locked_notif_sender {
            font-size: 0.60rem;
            font-weight: bold;
            font-family: "Open Sans", sans-serif;
            text-align: left;
            padding-left: 8px;
        }

        #preview_locked_notif_text {
            text-align: left;
            padding-left: 8px;
            font-size: 0.58rem;
            font-family: "Open Sans", sans-serif;
            margin-bottom: 6px;
            max-height: 85px;
            overflow: hidden;
        }

        #preview_open {

            width: 360px;
            height: 725px;
            padding-top: 120px;
            background-repeat: no-repeat;
            display: inline-block;
            background-position: 50% 0;
            background-size: 350px;
            position: relative;
        }

        .message-bubble {
            max-width: 95%;
            padding: 10px 15px;
            border-radius: 20px;
            position: relative;
            display: inline-block;
            word-wrap: break-word;
            text-align: left;
            min-width: 275px;
            ;
        }

        .message-sent {
            background-color: #dcd6d0;
            color: #000;
            border-top-right-radius: 0;
        }

        .message-received {
            background-color: #FFF;
            color: #000;
            border-top-left-radius: 0;
            border: 1px solid #e6e6e6;
        }

        .message-text {
            margin: 0;
        }

        .message-time {
            display: block;
            font-size: 0.75rem;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }

        .whatsapp-button {
            background-color: #25D366;
            border-color: #25D366;
            color: white;
            border-radius: 15px;
            margin-right: 10px;
            padding: 5px 15px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .whatsapp-button:hover {
            background-color: #20c057;
            border-color: #20c057;
        }

        .whatsapp-button:focus {
            box-shadow: none;
        }

        .whatsapp-plain-button {
            background-color: #FFF;
            border-color: #e6e6e6;
            color: #007bff;
            border-radius: 15px;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 5px 15px;
            font-size: 14px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            display: block;
        }

        .whatsapp-plain-button:hover {
            background-color: #f1f1f1;
            border-color: #d6d6d6;
        }

        .whatsapp-plain-button:focus {
            box-shadow: none;
        }

        .btn-ctr {
            margin-top: 20px;
            text-align: center;
        }
    </style>