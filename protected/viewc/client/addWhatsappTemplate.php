<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add WhatsApp Template') ?><small><?php echo SCTEXT('add a new WhatsApp template here') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <form class="form-horizontal" method="post" id="wtmp_form" data-plugin="dropzone" action="<?php echo Doo::conf()->APP_URL ?>saveWhatsappTemplate" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'image/png,image/gif,image/jpeg,.jpg', addRemoveLinks:true, params:{mode:'wt_header'}, success: function(file,res){createInputFile('wtmp_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'logo');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <div class="col-md-12 m-b-sm">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Template Name') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter name for your WhatsApp template') ?>. . . ." maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Template Category') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-primary">
                                                        <input id="sid1" checked="checked" value="0" type="radio" name="wtemp_cat">
                                                        <label for="sid1"><?php echo SCTEXT('Marketing') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('Promotions or information about your business, product or services. Or any message that isn\'t utility or authentication') ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="sid2" value="1" type="radio" name="wtemp_cat">
                                                        <label for="sid2"><?php echo SCTEXT('Utility') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT("Message about specific transaction, account, order or customer request.") ?></span>
                                                    </div>
                                                    <div class="radio radio-primary">
                                                        <input id="sid3" value="2" type="radio" name="wtemp_cat">
                                                        <label for="sid3"><?php echo SCTEXT('Authentication') ?></label>
                                                        <span class="help-block"><?php echo SCTEXT('One-time passwords your customers use to authenticate a transaction or login.') ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Language') ?>:</label>
                                                <div class="col-md-8">
                                                    <select data-plugin="select2" name="lang" data-placeholder="Choose a Language...">
                                                        <option value="AF">Afrikaans</option>
                                                        <option value="SQ">Albanian</option>
                                                        <option value="AR">Arabic</option>
                                                        <option value="HY">Armenian</option>
                                                        <option value="EU">Basque</option>
                                                        <option value="BN">Bengali</option>
                                                        <option value="BG">Bulgarian</option>
                                                        <option value="CA">Catalan</option>
                                                        <option value="KM">Cambodian</option>
                                                        <option value="ZH">Chinese (Mandarin)</option>
                                                        <option value="HR">Croatian</option>
                                                        <option value="CS">Czech</option>
                                                        <option value="DA">Danish</option>
                                                        <option value="NL">Dutch</option>
                                                        <option selected value="EN">English</option>
                                                        <option value="ET">Estonian</option>
                                                        <option value="FJ">Fiji</option>
                                                        <option value="FI">Finnish</option>
                                                        <option value="FR">French</option>
                                                        <option value="KA">Georgian</option>
                                                        <option value="DE">German</option>
                                                        <option value="EL">Greek</option>
                                                        <option value="GU">Gujarati</option>
                                                        <option value="HE">Hebrew</option>
                                                        <option value="HI">Hindi</option>
                                                        <option value="HU">Hungarian</option>
                                                        <option value="IS">Icelandic</option>
                                                        <option value="ID">Indonesian</option>
                                                        <option value="GA">Irish</option>
                                                        <option value="IT">Italian</option>
                                                        <option value="JA">Japanese</option>
                                                        <option value="JW">Javanese</option>
                                                        <option value="KO">Korean</option>
                                                        <option value="LA">Latin</option>
                                                        <option value="LV">Latvian</option>
                                                        <option value="LT">Lithuanian</option>
                                                        <option value="MK">Macedonian</option>
                                                        <option value="MS">Malay</option>
                                                        <option value="ML">Malayalam</option>
                                                        <option value="MT">Maltese</option>
                                                        <option value="MI">Maori</option>
                                                        <option value="MR">Marathi</option>
                                                        <option value="MN">Mongolian</option>
                                                        <option value="NE">Nepali</option>
                                                        <option value="NO">Norwegian</option>
                                                        <option value="FA">Persian</option>
                                                        <option value="PL">Polish</option>
                                                        <option value="PT">Portuguese</option>
                                                        <option value="PA">Punjabi</option>
                                                        <option value="QU">Quechua</option>
                                                        <option value="RO">Romanian</option>
                                                        <option value="RU">Russian</option>
                                                        <option value="SM">Samoan</option>
                                                        <option value="SR">Serbian</option>
                                                        <option value="SK">Slovak</option>
                                                        <option value="SL">Slovenian</option>
                                                        <option value="ES">Spanish</option>
                                                        <option value="SW">Swahili</option>
                                                        <option value="SV">Swedish </option>
                                                        <option value="TA">Tamil</option>
                                                        <option value="TT">Tatar</option>
                                                        <option value="TE">Telugu</option>
                                                        <option value="TH">Thai</option>
                                                        <option value="BO">Tibetan</option>
                                                        <option value="TO">Tonga</option>
                                                        <option value="TR">Turkish</option>
                                                        <option value="UK">Ukrainian</option>
                                                        <option value="UR">Urdu</option>
                                                        <option value="UZ">Uzbek</option>
                                                        <option value="VI">Vietnamese</option>
                                                        <option value="CY">Welsh</option>
                                                        <option value="XH">Xhosa</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Template Format') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="gen_m" name="wtformat" checked="checked" type="radio" value="m">
                                                        <label for="gen_m">Catalogue Message</label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="gen_f" name="wtformat" value="f" type="radio">
                                                        <label for="gen_f">Multi-Product Message</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Template Labels') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="tlables" id="tlables" class="form-control" placeholder="<?php echo SCTEXT('enter use case for your WhatsApp template e.g Account Update, OTP') ?>. . . ." maxlength="100" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Header Type') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="wt_text" checked="checked" type="radio" name="wt_type" value="0">
                                                        <label for="wt_text"><?php echo SCTEXT('Text') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="wt_media" type="radio" name="wt_type" value="1">
                                                        <label for="wt_media"><?php echo SCTEXT('Media') ?></label>
                                                    </div>
                                                    <div class="radio radio-inline radio-primary">
                                                        <input id="wt_loc" type="radio" name="wt_type" value="2">
                                                        <label for="wt_loc"><?php echo SCTEXT('Location') ?></label>
                                                    </div>
                                                    <hr>
                                                    <div class="m-b-0 nav-tabs-horizontal">

                                                        <div id="tab-1">
                                                            <input type="text" name="wtheader" id="wtheader" class="form-control" placeholder="<?php echo SCTEXT('enter the header text') ?>. . . ." maxlength="60" />
                                                            <div class="text-right">
                                                                <a href="javascript:void(0)" class="btn btn-xs btn-success" id="add_header_var"><i class="fa fa-plus"></i> <?php echo SCTEXT('Add Variable') ?></a>
                                                            </div>
                                                            <span class="help-block text-primary"><?php echo SCTEXT('Supports 1 Variable. Provide example of the variable value below if the header contains a variable') ?></span>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Example </span>
                                                                <input type="text" class="form-control" placeholder="<?php echo SCTEXT('enter the word or phrase to replace variable') ?>" name="header_egvar">
                                                            </div>
                                                        </div>
                                                        <div class="hidden" id="tab-2">
                                                            <div class="dropzone text-center">
                                                                <div class="dz-message">
                                                                    <h3 class="m-h-lg"><?php echo SCTEXT('Drop Image files here or click to upload.') ?></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="hidden" id="tab-3">
                                                            <span class="help-block text-primary"><?php echo SCTEXT('This will appear as generic maps at the top of the template and are useful for tracking purposes. When tapped, the default map app will open and load the specified location') ?></span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-7">

                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Body') ?>:</label>
                                                <div class="col-md-8">
                                                    <textarea id="tcont" name="tcont" maxlength="1024" class="form-control" data-plugin="maxlength" data-options="{ alwaysShow: false, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" placeholder="<?php echo SCTEXT('enter content for your template') ?>. . . ."></textarea>
                                                    <div class="text-right">
                                                        <a href="javascript:void(0)" class="btn btn-xs btn-success" id="add_body_var"><i class="fa fa-plus"></i> <?php echo SCTEXT('Add Variable') ?></a>
                                                    </div>
                                                    <br>
                                                    <span class="help-block text-primary"><?php echo SCTEXT('Supports multiple variables. Provide Example words below, for each variable you include separated by comma.') ?></span>
                                                    <div id="body_eg_container" class="m-h-sm">
                                                        <input data-plugin="tagsinput" type="text" name="body_eg_list" id="body_eg_list" class="form-control" placeholder="enter example for variables in the body" value="" />

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Footer') ?>:</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="wtfooter" id="wtfooter" class="form-control" placeholder="<?php echo SCTEXT('enter footer for your WhatsApp template') ?>. . . ." maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Buttons') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="text-right">
                                                        <a href="javascript:void(0)" class="btn btn-xs btn-success" id="add_wt_btns"><i class="fa fa-plus"></i> <?php echo SCTEXT('Add Buttons') ?></a>
                                                    </div>
                                                    <span class="help-block text-primary text-right"><b>Tip:</b> <?php echo SCTEXT('All Quick Reply Buttons must be grouped together') ?><br> <?php echo SCTEXT('Maximum two URL buttons per template') ?></span>
                                                    <?php
                                                    $rowstr = '<tr class="wt_btn_row"><td><select class="form-control btn-selector" name="btntype[]" class="form-control wt_btn_type"><option value="-1">' . SCTEXT('- select type -') . '</option><option value="0" data-nocap="1">' . SCTEXT('Quick Reply') . '</option><option value="1">' . SCTEXT('Call Button') . '</option><option value="2">' . SCTEXT('URL Button') . '</option><option value="3" data-nocap="1">' . SCTEXT('Copy Code Button') . '</option><option value="4" data-showvar="1">' . SCTEXT('Dynaimc URL') . '</option></select></td><td><input name="captions[]" class="form-control" type="text" placeholder="' . SCTEXT('Enter Label') . '" /></td><td><input name="vals[]" class="form-control btnvals" value="" type="text" placeholder="' . SCTEXT('Enter Values') . '" /><div class="text-right varbtnbox hidden"><a href="javascript:void(0)" class="btn varbtn btn-xs btn-success"><i class="fa fa-plus"></i> ' . SCTEXT('Add Variable') . '</a></div><input name="btnvars[]" class="form-control varvals hidden" type="text" placeholder="' . SCTEXT('variable example') . '" /></td><td><button class="rmv btn btn-round-min btn-danger" type="button"><span><i class="fa fa-large fa-trash fa-inverse"></i></span></button></td></tr>';
                                                    ?>
                                                    <input type="hidden" id="newrowstr" value="<?php echo htmlentities($rowstr); ?>" />
                                                    <table id="dlrcodetbl" class="sc_responsive wd100 table row-border order-column ">
                                                        <tbody id="btns_ctnr"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="hidden form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Button') ?>:</label>
                                                <div class="col-md-8">
                                                    <div class="m-b-0 nav-tabs-horizontal">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li role="presentation" class="active"><a href="#tab-11" class="p-t-xs" role="tab" data-toggle="tab"><?php echo SCTEXT('Quick Reply') ?></a></li>
                                                            <li role="presentation"><a href="#tab-22" class="p-t-xs" role="tab" data-toggle="tab"><?php echo SCTEXT('Phone Number') ?></a></li>
                                                            <li role="presentation"><a href="#tab-33" class="p-t-xs" role="tab" data-toggle="tab"><?php echo SCTEXT('Copy Offer Code') ?></a></li>

                                                        </ul>
                                                        <div class="tab-content p-md">
                                                            <div role="tabpanel" class="tab-pane in active fade" id="tab-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-lg fa-reply"></i> </span>
                                                                    <input type="text" class="form-control" placeholder="enter a quick reply ..." name="wtqrep">
                                                                </div>

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane fade" id="tab-22">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-lg fa-phone"></i> </span>
                                                                    <input type="text" class="form-control" placeholder="mobile number to call ..." name="wtphn">
                                                                </div>
                                                            </div>
                                                            <div role="tabpanel" class="tab-pane fade" id="tab-33">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-lg fa-copy"></i> </span>
                                                                    <input type="text" class="form-control" placeholder="enter code to copy ..." name="wtcopycode">
                                                                </div>
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
                                            <button class="btn btn-primary" id="save_changes" type="submit"><?php echo SCTEXT('Save changes') ?></button>
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