 <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Import VMN')?><small><?php echo SCTEXT('import VMNs in bulk and assign them to a user')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="vmnfrm" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'text/csv,text/x-csv,text/plain,.csv,.xls,.xlsx', addRemoveLinks:true, params:{mode:'contacts'}, success: function(file,res){createInputFile('vmnfrm',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'rprice');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Select VMN Type')?>:</label>
                                            <div class="col-md-8">
                                                <select id="vmntype" name="vmntype" class="form-control" data-plugin="select2">
                                                    <option value="0" data-title="<?php echo SCTEXT('Short virtual numbers for localised campaigns. These are usually 4-6 digits long.')?>">Shortcode</option>
                                                    <option value="1" data-title="<?php echo SCTEXT('A 12 digit mobile number that can receive SMS globally.')?>">Longcode</option>
                                                    <option value="2" data-title="<?php echo SCTEXT('Virtual number linked with Missed call service. Caller details will be sent via SMPP.')?>">Missed Call Number</option>
                                                </select>
                                                <span class="help-block" id="vtypestr"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Upload VMN list')?>:</label>
                                            <div class="col-md-8 dropzone">
                                            <div class="dz-message">
                                                <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>
                                                <p class="m-b-lg">( <a href="#">Click Here</a> <?php echo SCTEXT(' to download sample file to match the correct format.') ?> )</p>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Assign User')?> (optional):</label>
                                            <div class="col-md-8">
                                            <select title="" id="userpicker" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){ if(data.element && data.element.value>0){ let uname = data.element.text; var lstr = data.element.label; var myarr = lstr.split('|'); var nstr = '<div class=\'media m-t-xs\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+uname+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic; line-height:14px;\'>'+myarr[1]+'</p></div></div>';}else{var nstr='<h5>- No User Associated -</h5>';} return $(nstr);}, templateSelection: function (data){ if(data.element.value==0) return '- No User Associated -'; let uname = data.element.text; var lstr=data.element.label;var myarr = lstr.split('|'); var nstr = '<div class=\'media\' style=\'padding-top: 2px;\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+uname+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic;line-height:14px;\'>'+myarr[1]+'</p></div></div>'; return $(nstr);} }">
                                        <option value="0">- No User Associated -</option>
                                        <?php foreach($data['users'] as $usr){ ?>
                                            <option value="<?php echo $usr->user_id ?>" data-fullname="<?php echo $usr->name ?>" label="<?php echo $usr->avatar.'|'.$usr->email.'|'.$usr->category."|".$usr->mobile ?>" ><?php echo strtok($usr->name," ") ?></option>
                                        <?php } ?>
                                        <?php ?>
                                    </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Auto-reply Rule')?>:</label>
                                            <div class="col-md-8">
                                                <div class="radio radio-primary">
                                                    <input id="art1" checked="checked" value="0" type="radio" name="ar_type">
                                                    <label for="art1"><?php echo SCTEXT('Disabled')?></label>
                                                    <span class="help-block"><?php echo SCTEXT('No auto-reply SMS will be sent.')?></span>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="art2" value="1" type="radio" name="ar_type">
                                                    <label for="art2"><?php echo SCTEXT('Send from User Account')?></label>
                                                    <span class="help-block"><?php echo SCTEXT("Auto-reply SMS will be sent from user account using default route and credits will be deducted.")?></span>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="art3" value="2" type="radio" name="ar_type">
                                                    <label for="art3"><?php echo SCTEXT('Free Reply SMS')?></label>
                                                    <span class="help-block"><?php echo SCTEXT("Auto-reply SMS will be sent from our system. User will not be charged any credits.")?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Reply')?>:</label>
                                            <div class="col-md-8">
                                                <textarea id="dr_sms" name="dr_sms" maxlength="120" class="form-control pop-over" data-placement="top" data-trigger="focus" data-content="<?php echo SCTEXT('This message will be sent if no other reply SMS is matched for incoming SMS')?>" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" placeholder="<?php echo SCTEXT('enter default SMS reply')?>. . . ."></textarea>
                                            </div>
                                        </div>

                                        

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Default Trigger URL')?>:</label>
                                            <div class="col-md-8">
                                                <input data-placement="top" type="text" class="form-control pop-over" data-trigger="focus" data-content="<?php echo SCTEXT('This URL will be triggered only if no URL matches are found for the incoming SMS.') ?>" name="turl" id="turl" placeholder="<?php echo SCTEXT('enter complete url with protocol')?>. . .">
                                            </div>
                                        </div>
											
                                        <hr>
											<div class="form-group">
                                            <div class="col-md-3"></div>
												<div class="col-md-8">
													<button id="save_changes" class="btn btn-primary" type="button"><?php echo SCTEXT('Import VMNs')?></button>
													<button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel')?></button>
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