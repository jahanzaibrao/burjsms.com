<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add Verified-SMS Agent')?><small><?php echo SCTEXT('An agent is a conversational representation of a brand')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                <div class="alert alert-info alert-custom alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><h4 class="alert-title">Please Note!</h4><p>Agent Name, description and Logo cannot be changed after it is <span class="m-l-xs label label-success">Approved</span></p></div>
                                    <form class="form-horizontal" method="post" id="vsmsa_form" action="" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', headers: {'tictactoe':`<?php echo $_SESSION['smppcube_token'] ?>`}, maxFiles:1, acceptedFiles:'image/png,image/jpeg', addRemoveLinks:true, params:{mode:'logo'}, init: function(){var myDropzone = this; if($('#oldlogo').val()!=''){var mockFile = { name: $('#oldlogo').val(), size: $('#oldlogo').attr('data-size')}; myDropzone.emit('addedfile', mockFile); myDropzone.emit('thumbnail', mockFile,$('#oldlogo').attr('data-path')+$('#oldlogo').val()); myDropzone.emit('complete', mockFile);$('.dz-message').addClass('hidden'); }}, success: function(file,res){createInputFile('vsmsa_form',res); $('#uprocess').val('0'); $('.dz-message').addClass('hidden');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr){deleteInputFile(file.xhr.response,'logo'); $('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}  if($('#oldlogo').val()!=''){ bootbox.confirm({message: 'This will remove the logo and delete the file. Are you sure you want to proceed?', buttons: {cancel: {label: 'No',className: 'btn-default'}, confirm: { label: 'Yes', className: 'btn-info' }},callback: function (result) { if(result){ deleteInputFile($('#oldlogo').val(),'logo');$('#oldlogo').val('');$('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}} });} }}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <input type="hidden" id="oldlogo" data-path="<?php echo Doo::conf()->APP_URL.'global/img/logos/' ?>" data-size="<?php echo filesize(Doo::conf()->image_upload_dir.'logos/'.$data['vagent']->logo) ?>" value="<?php echo $data['vagent']->logo ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Select Country')?>:</label>
                                            <div class="col-md-8">
                                                <select id="cov" name="cov" class="form-control" data-plugin="select2">
                                                    <option value="0"><?php echo SCTEXT('Select One')?></option>
                                                    <?php foreach($data['cvdata'] as $cv){ ?>
                                                    <option <?php if($data['def_country']==$cv->country_code){ ?> selected="selected" <?php } ?>  value="<?php echo $cv->country_code ?>"><?php echo $cv->country ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Agent Name</label>
                                            <div class="col-md-8">
                                                <input maxlength="100" class="form-control" name="vsms_aname" id="vsms_aname" placeholder="e.g. ABC Solutions LTD">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Description</label>
                                            <div class="col-md-8">
                                                <textarea maxlength="100" class="form-control" name="vsms_adesc" id="vsms_adesc" placeholder="e.g. ABC Solutions LTD provides useful services for your needs"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Display Email</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="vsms_email" id="vsms_email" placeholder="e.g. contact@company-name.com">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Website</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="vsms_domain" id="vsms_domain" placeholder="e.g. https://your-site.com">
                                                <span class="help-block text-danger">include the protocol <b>http://</b> or <b>https://</b> in the website address</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Brand Logo</label>
                                            <div class="col-md-8">
                                                <div class="dropzone text-center">
                                                    <div class="dz-message">
                                                        <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.')?></h3>
                                                        <p class="m-b-lg">( Upload png or jpg/jpeg files only )<br><span class="text-danger">Square logo (224 x 224)</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Sender IDs</label>
                                            <div class="col-md-8">
                                                <select class="form-control" data-plugin="select2" name="vsmssids[]" multiple data-placeholder="select all Sender IDs for this brand..">
                                                <?php foreach($data['sids'] as $sid){ ?>
                                                    <option value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Sender Prefixes</label>
                                            <div class="col-md-8">
                                                <input placeholder="e.g. ID, VM, etc" data-plugin="tagsinput" name="vsms_sidpre" class="form-control">
                                                <span class="help-block">Enter all possible prefixes for your Sender ID separated by comma. For example, your operator may add a prefix to your sender like <b>VM</b>-WEBSMS, <b>AR</b>-WEBSMS, <b>ID</b>WEBSMS etc. </span>
                                            </div>

                                        </div>
                                        <hr>

                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes')?></button>
													<button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel')?></button>
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
