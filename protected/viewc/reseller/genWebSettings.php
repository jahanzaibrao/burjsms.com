
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('General Settings')?><small><?php echo SCTEXT('modify parameters of your white-label website')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="set_form" action="" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'image/png,image/jpeg', addRemoveLinks:true, params:{mode:'logo'}, init: function(){var myDropzone = this; if($('#oldlogo').val()!=''){var mockFile = { name: $('#oldlogo').val(), size: $('#oldlogo').attr('data-size')}; myDropzone.emit('addedfile', mockFile); myDropzone.emit('thumbnail', mockFile,$('#oldlogo').attr('data-path')+$('#oldlogo').val()); myDropzone.emit('complete', mockFile);$('.dz-message').addClass('hidden'); }}, success: function(file,res){createInputFile('set_form',res); $('#uprocess').val('0'); $('.dz-message').addClass('hidden');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr){deleteInputFile(file.xhr.response,'logo'); $('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}  if($('#oldlogo').val()!=''){ bootbox.confirm({message: 'This will remove the logo and delete the file. Are you sure you want to proceed?', buttons: {cancel: {label: 'No',className: 'btn-default'}, confirm: { label: 'Yes', className: 'btn-info' }},callback: function (result) { if(result){ deleteInputFile($('#oldlogo').val(),'logo');$('#oldlogo').val('');$('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}} });} }}">
                                        <input type="hidden" id="uprocess" value="0" />
                                        <input type="hidden" id="oldlogo" data-path="<?php echo Doo::conf()->APP_URL.'global/img/logos/' ?>" data-size="<?php echo filesize(Doo::conf()->image_upload_dir.'logos/'.$data['wdata']->logo) ?>" value="<?php echo $data['wdata']->logo ?>" />
                                        <?php $sdata = unserialize($data['wdata']->site_data); ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Company Name')?>:</label>
												    <div class="col-md-8">
													   <input type="text" name="comname" id="comname" class="form-control" placeholder="<?php echo SCTEXT('enter name of the company')?> . . ." value="<?php echo $sdata['company_name'] ?>" maxlength="100" />
												    </div>
										</div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Domains')?>:</label>
                                                        <div class="col-md-8">
                                                        <input data-plugin="tagsinput" type="text" name="domains" id="domains" class="form-control" placeholder="<?php echo SCTEXT('enter website domains')?>" value="<?php echo $data['wdata']->domains ?>" />
                                                            <span class="help-block"><?php echo SCTEXT('Enter the domain names without <b>http</b>. For example, sms.company.com, mycompany.com, www.mycompany.com etc. Separate multiple entries by comma.')?></span>
                                                        </div>
                                            </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('App colors')?>:</label>
												    <div class="col-md-8">
													   <select class="form-control" name="theme" data-plugin="select2" data-options="{templateResult: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span> '+data.text+'</span>');},templateSelection: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span>'+data.text+'</span>');} }">
                                                           <option <?php if($sdata['theme']=='info'){ ?> selected <?php } ?> value="info"> <?php echo SCTEXT('Blue Theme')?></option>
                                                            <option <?php if($sdata['theme']=='success'){ ?> selected <?php } ?> value="success"> <?php echo SCTEXT('Green Theme')?></option>
                                                            <option <?php if($sdata['theme']=='primary'){ ?> selected <?php } ?> value="primary"> <?php echo SCTEXT('Royal Blue Theme')?></option>
                                                            <option <?php if($sdata['theme']=='warning'){ ?> selected <?php } ?> value="warning"> <?php echo SCTEXT('Yellow Theme')?></option>
                                                            <option <?php if($sdata['theme']=='danger'){ ?> selected <?php } ?> value="danger"> <?php echo SCTEXT('Red Theme')?></option>
                                                            <option <?php if($sdata['theme']=='pink'){ ?> selected <?php } ?> value="pink"> <?php echo SCTEXT('Pink Theme')?></option>
                                                            <option <?php if($sdata['theme']=='purple'){ ?> selected <?php } ?> value="purple"> <?php echo SCTEXT('Purple Theme')?></option>
                                                            <option <?php if($sdata['theme']=='inverse'){ ?> selected <?php } ?> value="inverse"> <?php echo SCTEXT('Dark Theme')?></option>
                                                        </select> 
												    </div>
										</div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Front-type')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-primary">
                                                                <input id="web-wl" name="fronttype" <?php if($data['wdata']->front_type=='1'){ ?> checked="checked" <?php } ?> type="radio" value="1">
                                                                <label for="web-wl"><?php echo SCTEXT('5-page white-label website')?></label>
                                                                <span class="help-block"><?php echo SCTEXT('Front-end will be a multi-page website. You can modify the content.')?></span>
                                                            </div>
                                                            <div class="radio radio-primary">
                                                                <input id="web-lb" name="fronttype" <?php if($data['wdata']->front_type=='0'){ ?> checked="checked" <?php } ?> value="0" type="radio">
                                                                <label for="web-lb"><?php echo SCTEXT('Login box only')?></label>
                                                                <span class="help-block"><?php echo SCTEXT('Front-end will be simply a page with login box with your company name & logo.')?></span>
                                                            </div>
                                                        </div>
                                            </div>
                                           
                                        </div>
                                        
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Company Logo')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="dropzone text-center">
                                                               <div class="dz-message">
                                                                    <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.')?></h3>
                                                                    <p class="m-b-lg">( Upload png or jpg/jpeg files only )</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Help-Line')?>:</label>
                                                        <div class="col-md-8">
                                                            <input data-title="<?php echo SCTEXT('Enter Phone Number')?>" data-content="<?php echo SCTEXT('Customer care office telephone number to display on front-end website. Helps your customer reach you quickly.')?>" data-trigger="hover" data-placement="top" size="40" type="text" class="form-control pop-over" value="<?php echo $sdata['helpline'] ?>" name="helpline" />
                                                        </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Support Email')?>:</label>
                                                        <div class="col-md-8">
                                                            <input data-title="<?php echo SCTEXT('Enter Email ID')?>" data-content="<?php echo SCTEXT('This will be displayed on front-end of the website and help your customers contact you via email.')?>" data-trigger="hover" data-placement="top" size="40" type="text" class="form-control pop-over" value="<?php echo $sdata['helpmail'] ?>" name="helpmail" />
                                                        </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Logout URL')?>:</label>
                                                        <div class="col-md-8 input-group">
                                                            <span class="input-group-addon">http://</span>
                                                            <input data-title="<?php echo SCTEXT('Enter Logout URL')?>" data-content="<?php echo SCTEXT('You can redirect users to your own website after logout. Enter URL here or leave this blank to redirect to default app page.')?>" data-trigger="hover" data-placement="top" type="text" placeholder="<?php echo SCTEXT('leave blank for default behavior')?>. . ." class="form-control pop-over" value="<?php echo $sdata['logout_url'] ?>" name="outurl" />
                                                        </div>
                                            </div>
                                            
                                            
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="col-md-12">
                                            <div class="widget">
                                                <header class="widget-header">
                                                    <h4 class="widget-title"><?php echo SCTEXT('Terms & Conditions')?></h4></header>
                                                <span class="m-l-md help-block"><?php echo SCTEXT('Define terms and conditions.')?> </span>
                                                <hr class="widget-separator">
                                                <div class="widget-body">
                                                    <textarea name="tnc" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $sdata['tnc']==''?SCTEXT('Enter terms and conditions').' ...':htmlspecialchars_decode($sdata['tnc']); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-md-12">
                                            <div class="widget">
                                                <header class="widget-header">
                                                    <h4 class="widget-title"><?php echo SCTEXT('Privacy Policy')?></h4></header>
                                                
                                                <hr class="widget-separator">
                                                <div class="widget-body">
                                                    <textarea name="prpolicy" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $sdata['policy']==''?SCTEXT('Enter privacy policy').' ...':htmlspecialchars_decode($sdata['policy']); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-5 text-right"><button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes')?></button></div>
												<div class="col-md-5 m-l-md">
													
													<button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel')?></button>
												</div>
											</div>
                                        
                                        <?php if($data['wdata']->logo!=''){ ?>
                                        <input name="uploadedFiles[]" value="<?php echo $data['wdata']->logo ?>" id="<?php echo $data['wdata']->logo ?>" class="uploadedFile" type="hidden">
                                        <?php } ?>
                                    </form>     
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>