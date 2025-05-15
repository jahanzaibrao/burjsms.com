<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Upload new Media')?><small><?php echo SCTEXT('upload a new media to include in your campaigns')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="cmed_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'image/*,audio/*,video/*,application/pdf', addRemoveLinks:true, params:{mode:'media'}, success: function(file,res){createInputFile('cmed_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'media');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}" action="">
                                    <input type="hidden" id="uprocess" value="0" />
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Media Title')?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="mtitle" name="mtitle" placeholder="provide a title for the file for quick search ...">
                                            </div>
										</div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Upload File')?>:</label>
                                            <div class="col-md-8">
                                                <div class="dropzone text-center">
                                                    <div class="dz-message">
                                                        <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.')?></h3>
                                                        <p class="m-b-lg">( Upload Images, Audio, Video or PDF file only )</p>
                                                    </div>
                                                </div>
                                            </div>
										</div>
                                        
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Upload Media')?></button>
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