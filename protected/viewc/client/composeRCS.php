<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix"><?php echo SCTEXT('Send RCS Message') ?><small><?php echo SCTEXT('compose campaign with Google RCS agent support') ?></small>

                            </h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <!-- start content -->
                            <form class="form-horizontal" method="post" id="sendsms_form" data-plugin="dropzone" action="" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,text/csv,.xls', addRemoveLinks:true, params:{mode:'sendsms'}, success: function(file,res){createInputFile('sendsms_form',res,'sendsms'); $('#uprocess').val('0'); $('.dz-message').addClass('hidden');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'sendsms');$('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                <input type="hidden" name="campaignid" id="campaignid" value="">
                                <input type="hidden" id="uprocess" value="0" />
                                <input type="hidden" id="ufilecno" value="0" />
                                <input type="hidden" name="account_type" id="account_type" value="<?php echo $_SESSION['user']['account_type'] ?>">
                                <input type="hidden" name="contact_label" id="contact_label" value="">
                                <div class="clearfix">
                                    <div class="col-md-5">
                                    <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Campaign Name') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter name for your RCS campaign') ?>. . . ." maxlength="100" />
                                                </div>
                                            </div>
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
                                            <label class="control-label col-md-3"><?php echo SCTEXT('RCS Agent') ?>:</label>
                                            <div class="col-md-8">
                                                <select name="vsms_agent" title="Select An RCS Agent" id="vsms_agent" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){ if(data.element && data.element.value!=''){ let uname = data.element.text; var lstr = data.element.label; var myarr = lstr.split('|'); var nstr = '<div class=\'media m-t-xs\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+uname+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic; line-height:14px;\'>'+myarr[1]+'</p></div></div>';}else{var nstr='<h5>- Select Google RCS Agent -</h5>';} return $(nstr);}, templateSelection: function (data){ if(data.element.value=='') return '- Select Google RCS Agent -'; let uname = data.element.text; var lstr=data.element.label;var myarr = lstr.split('|'); var nstr = '<div class=\'media\' style=\'padding-top: 2px;\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+uname+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic;line-height:14px;\'>'+myarr[1]+'</p></div></div>'; return $(nstr);} }">
                                                    <option data-pkey="v7fvd8g8gd" value="test_agent" data-fullname="Vodafone" label="<?php echo Doo::conf()->APP_URL ?>/global/img/logos/1673452115-8971.jpg|Empowering Telcommunications|UK">Vodafone</option>


                                                </select>

                                            </div>
                                        </div>

                                        <div id="sidopnbox" class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID') ?>:</label>
                                            <div class="col-md-8">
                                            <div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <span>Make sure the Sender ID is approved in RBM Console</span></div>
                                                <select class="form-control" name="sendersel" id="sendersel" data-plugin="select2">
                                                        <?php foreach ($data['sids'] as $sid) { ?>
                                                            <option value="<?php echo $sid->id ?>"><?php echo $sid->sender_id ?></option>
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
                                                                    <li role="presentation" class="active"><a class="p-t-xs" href="#tab-1" aria-controls="tab-3" role="tab" data-toggle="tab"><?php echo SCTEXT('Upload Contacts') ?></a></li>
                                                                    <li role="presentation"><a class="p-t-xs" href="#tab-2" aria-controls="tab-1" role="tab" data-toggle="tab"><?php echo SCTEXT('Contact Groups') ?></a></li>
                                                                    <li role="presentation"><a class="p-t-xs" href="#tab-3" aria-controls="tab-2" role="tab" data-toggle="tab"><?php echo SCTEXT('Enter Manually') ?></a></li>
                                                                </ul>
                                                                <div class="tab-content p-md">
                                                                    <div role="tabpanel" class="tab-pane in active fade" id="tab-1">
                                                                    <div class="dropzone text-center">
                                                                            <div class="dz-message">
                                                                                <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>

                                                                            </div>
                                                                            <p class="m-b-lg">( Upload <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/XLS- sample file.xls" target="_blank"><u>xls</u></a>, <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/XLSX- sample file.xlsx" target="_blank"><u>xlsx</u></a>, <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/CSV- sample file.csv" target="_blank"><u>csv</u></a> or <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/TXT- sample file.txt" target="_blank"><u>txt</u></a> files )</p>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div role="tabpanel" class="tab-pane fade" id="tab-2">
                                                                        <select id="grpsel" class="form-control" data-plugin="select2" name="groups[]" multiple data-options="{placeholder: '<?php echo SCTEXT('Select Groups') ?>. . . .'}">

                                                                            <?php foreach ($data['gdata'] as $grp) { ?>
                                                                                <option data-colstr="<?php echo base64_encode($grp['colstr']) ?>" data-name="<?php echo $grp['name'] ?>" value="<?php echo $grp['id'] ?>" data-count="<?php echo $grp['count'] ?>"><?php echo $grp['name'] . ' (' . number_format($grp['count']) . ' Contacts)' ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div role="tabpanel" class="tab-pane fade" id="tab-3">
                                                                    <textarea id="contactinput" class="form-control pop-over" name="numbers" placeholder="<?php echo SCTEXT('enter mobile numbers') ?>. . . ." data-placement="top" data-content="<?php echo SCTEXT('Enter mobile numbers separated by newline e.g.') ?> <br><p>9876xxxxx<br>8901xxxxx<br>9015xxxxxx</p>.... and so on" data-trigger="hover"></textarea>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="help-block text-dark">
                                                                <div class="checkbox checkbox-primary checkbox-inline m-b-sm">
                                                                    <input name="rminv" id="rminv" checked="checked" type="checkbox">
                                                                    <label for="rminv"><?php echo SCTEXT('Fall back to SMS for Unsupported Devices') ?></label>
                                                                </div>
                                                                
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        


                                    </div>


                                    <div class="col-md-7">

                                    <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Campaign Type') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-primary">
                                                        <input class="rctp" id="sid1" checked="checked" value="0" type="radio" name="wtemp_cat">
                                                        <label for="sid1"><?php echo SCTEXT('Simple Text') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('A regular promotional or informational message with no media. Best suited for alerts, OTP and notifications') ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input class="rctp" id="sid2" value="1" type="radio" name="wtemp_cat">
                                                        <label for="sid2"><?php echo SCTEXT('Single Rich Card') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT("Rich card allows display of a banner with quick action buttons. Best suited for marketing and distributing assets like coupons.") ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input class="rctp" id="sid3" value="2" type="radio" name="wtemp_cat">
                                                        <label for="sid3"><?php echo SCTEXT('Rich Card Carousel') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('A scrollable set of rich cards will be displayed each with its own actions. Best for promotions with multiple options or products.') ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Campaign Body') ?>:</label>
                                            <div class="col-md-8">
                                                <div id="rcst" class="">
                                                <textarea id="tcont" name="tcont" rows="8" cols="50" maxlength="2000" class="form-control" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-primary', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }"></textarea>
                                                </div>

                                                <div id="rcsr" class="hidden">
                                                    <select id="rcsel" class="form-control" data-plugin="select2"><option value="rc1">Fast Food Delivery</option><option value="rc22">Travel Deals</option></select>
                                                                                <hr>
                                                                                <h4>Preview</h4>
                                                                                <hr>
                                                    <div id="rc1" class=""><div class="widget"><div class="widget-body text-center"><div class="big-icon m-b-md watermark"><img src="<?php echo Doo::conf()->APP_URL ?>global/img/food.jpeg"></div><h4 class="m-b-md">Doorstep Delivery</h4><p class="text-muted m-b-lg">Celebrate this festive season with our fast doorstep delivery of your delicious cravings.</p><a href="#" class="btn p-v-xl btn-primary m-r-sm"> <i class="fa fa-file"></i>&nbsp; View Our Menu</a><a href="#" class="btn p-v-xl btn-primary"> <i class="fa fa-phone"></i>&nbsp; Call To Place Order</a></div></div></div>

                                                    <div id="rc2" class="hidden"><div class="widget"><div class="widget-body text-center"><div class="big-icon m-b-md watermark"><img src="<?php echo Doo::conf()->APP_URL ?>global/img/travel.jpeg"></div><h4 class="m-b-md">Upto 60% Discount on Hotels</h4><p class="text-muted m-b-lg">Check Out our seasons discount offerrings on Luxury hotels.</p><a href="#" class="btn p-v-xl btn-primary"> <i class="fa fa-link"></i>&nbsp; Visit Offer Page</a></div></div></div>

                                                </div>
                                                <div id="rcsc" class="hidden">
                                                    <h4>Select Multiple Rich Cards For the Carousel</h4>
                                                    <hr>
                                                    <select class="form-control" data-plugin="select2" name="usrkws[]" multiple data-options="{templateResult: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var typestr = data.element.dataset.vmntype==0?'<span class=\'label label-success\'>'+data.element.dataset.vmn+'</span>':'<span class=\'label label-primary\'>'+data.element.dataset.vmn+'</span>'; var nstr = '<div class=\'clearfix m-b-sm\'><div class=\'m-r-md pull-left\'>'+data.text+'</div><div class=\'pull-right\'>'+typestr+'</div></div>';return $(nstr);} }, templateSelection: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var typestr = data.element.dataset.vmntype==0?'<span class=\'label label-success\'>'+data.element.dataset.vmn+'</span>':'<span class=\'label label-primary\'>'+data.element.dataset.vmn+'</span>'; var nstr = '<div class=\'clearfix m-b-sm\'><div class=\'m-r-md pull-left\'>'+data.text+'</div><div class=\'pull-right\'>'+typestr+'</div></div>';return $(nstr);} }, placeholder: '<?php echo SCTEXT('Select Rich Cards') ?>. . . .'}">

                                                               
                                                                        <option data-vmn="Fast Food Delivery" data-vmntype="0" value="1">Doorstep Delivery</option>
                                                                        <option data-vmn="Travel Deals" data-vmntype="1" value="1">Upto 60% Discount on Hotels</option>
                                                               
                                                            </select>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <hr class="separator">
                                </div>
                                
                                <div class="form-group m-t-lg">
                                        <div class="col-md-7"></div>
                                        <div class="col-md-5">
                                            <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Submit Campaign') ?></button>
                                            <button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel') ?></button>
                                        </div>
                                    </div>
                            </form>


                            <!-- various boxes -->
                            

                            <?php if (isset($data['pageResponse'])) { ?>

                                <div class="modal fade" id="pageResp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel2"> <i class="fa fa-lg fa-check-circle m-r-xs text-success"></i> <?php echo SCTEXT('There you go!') ?></h4>
                                            </div>
                                            <div class="modal-body p-lg">
                                                <p><?php echo SCTEXT('Your campaign was successfully submitted to the server.') ?></p>
                                                <ul class="list-group">
                                                    <li class="list-group-item"><span class="badge badge-primary label-md"><?php echo $data['pageResponse']['total_sms'] ?></span><?php echo SCTEXT('Total SMS Submitted') ?></li>
                                                    <li class="list-group-item"><span class="badge badge-info label-md"><?php echo $data['pageResponse']['duplicates_removed'] ?></span><?php echo SCTEXT('Duplicates Removed') ?></li>
                                                    <li class="list-group-item"><span class="badge badge-warning label-md"><?php echo $data['pageResponse']['invalid_removed'] ?></span><?php echo SCTEXT('Invalids Removed') ?></li>
                                                    <li class="list-group-item"><span class="badge badge-danger label-md"><?php echo $data['pageResponse']['Blremoved'] ?></span><?php echo SCTEXT('Blacklist Numbers') ?></li>
                                                    <li class="list-group-item"><span class="badge badge-success label-md"><?php echo $data['pageResponse']['total_sent'] ?></span><?php echo SCTEXT('Total SMS Sent') ?></li>
                                                    <li class="list-group-item"><span class="badge badge-purple label-md"><?php echo $data['pageResponse']['credits_deducted'] ?></span><?php echo SCTEXT('Credits Deducted') ?></li>
                                                    <li class="list-group-item"><span class="badge badge-info label-md"><?php echo $data['pageResponse']['remaining_balance'] ?></span><?php echo SCTEXT('Current Balance') ?></li>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo SCTEXT('Send another SMS') ?></button>
                                                <a href="<?php echo $data['pageResponse']['schflag'] == '1' ? Doo::conf()->APP_URL . 'scheduledCampaigns' : Doo::conf()->APP_URL . 'showDlrSummary' ?>" class="btn btn-primary"><?php echo SCTEXT('View Delivery Reports') ?></a>
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

                            <div id="conf_modal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body clearfix" style="text-align:center;">
                                            <div id="conf_main_preview_ctr" style="height: 600px;" class="col-md-12">

                                                <div class="col-md-6">
                                                    <div id="conf_model_info">
                                                        <div class="p-t-sm">
                                                            <h2 class="page-title p-l-xs">Confirm Campaign Submission?</h2>
                                                            <table class="table">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="min-width: 250px;">Route</td>
                                                                        <td class="text-right"><span class="label label-info label-md" id="conf_sel_route"></span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>SENDER ID</td>
                                                                        <td class="text-right"><span class="code text-dark" id="conf_sel_sid"></span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Contacts</td>
                                                                        <td class="text-right"><span class="code" id="conf_total_contacts_modal"></span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Charges per SMS</td>
                                                                        <td class="text-right"><span class="code text-danger" id="conf_per_sms_price"></span>
                                                                            <?php if ($_SESSION['user']['account_type'] == '1') { ?>
                                                                                <span class="help-block text-dark fz-sm">
                                                                                    * For Smart Routing, SMS cost for each mobile number would vary.
                                                                                </span>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Charges</td>
                                                                        <td class="text-right"><span class="code text-danger" id="conf_total_cost_modal"></span>
                                                                            <span class="help-block text-dark fz-sm">
                                                                                * This is an approximate cost. Final value will be determined after filtering duplicates, opt-outs and blacklists.
                                                                            </span>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>SMS Type</td>
                                                                        <td class="text-right" id="conf_sms_type"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Campaign Start</td>
                                                                        <td class="text-right text-dark code" id="conf_sch_info"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="m-t-lg">
                                                            <a class="btn btn-primary" id="conf_proceed"><?php echo SCTEXT('Yes, Proceed') ?></a>
                                                            <button class="btn btn-default" data-dismiss="modal"></i><?php echo SCTEXT('No, Cancel') ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 pull-right">
                                                    <div id="conf_preview_open" style="padding-left: 44px;">
                                                        <div id="conf_preview_open_sender"></div>
                                                        <div id="conf_preview_open_msg" class="yours messages">
                                                            <div id="conf_preview_open_msg_text" class="message last text-left">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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