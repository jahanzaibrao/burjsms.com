<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add New Document')?><small><?php echo SCTEXT('upload a new document for sharing/agreements etc.')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="udoc_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,text/csv,application/x-compressed,application/x-zip-compressed,application/zip,image/png,image/jpeg,application/pdf,.xls', addRemoveLinks:true, params:{mode:'docmgr'}, success: function(file,res){createInputFile('udoc_form',res); $('#uprocess').val('0'); $('.dz-message').addClass('hidden');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'docmgr');$('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Type')?>:</label>
												    <div class="col-md-8">
                                                       <select name="doctype" class="form-control" data-plugin="select2" id="doctype" >
                                                           <option value="3"><?php echo SCTEXT('Normal Document')?></option>
                                                           <option value="2"><?php echo SCTEXT('Agreement')?></option>
                                                           
                                                           
                                                        </select> 
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Document Name')?>:</label>
												    <div class="col-md-8">
													   <input type="text" placeholder="<?php echo SCTEXT('Enter a title for the document')?> ..." name="docname" value="" class="form-control" id="docname" />
												    </div>
										</div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Upload File')?>:</label>
                                            <div class="col-md-8">
                                                <div class="dropzone text-center">
                                                    <div class="dz-message">
                                                        <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.')?></h3>
                                                        <p class="m-b-lg">( Upload Excel, Text, PDF, Image or Zip files only )</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Remarks')?></label>
												    <div class="col-md-8">
													   <textarea placeholder="<?php echo SCTEXT('enter any comments or remarks for this file')?> ..." class="form-control" name="docrmk"></textarea>
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