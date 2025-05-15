
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Upload Prefixes')?><small><?php echo SCTEXT('upload prefix to identify operator & circle for mobile numbers')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="upload_ocpr_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,.xls', addRemoveLinks:true, params:{mode:'ocpr'}, success: function(file,res){createInputFile('upload_ocpr_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'ocpr');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                        <input type="hidden" id="uprocess" value="0" />
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Country')?>:</label>
												    <div class="col-md-8">
                                                        <select id="country" name="country" data-plugin="select2">
                                                            <option value="0">- <?php echo SCTEXT('Select Country')?> -</option>
                                                            <?php foreach($data['cdata'] as $cv){ ?>
                                                            <option value="<?php echo $cv->id ?>"><?php echo $cv->country.' ( '.$cv->prefix.' )' ?></option>
                                                            <?php } ?>
                                                        </select>
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Upload File')?>:<br>(xls or xlsx)</label>
												    <div class="col-md-8 text-center dropzone">
													   <div class="dz-message">
                                                            <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.')?></h3>
                                                           
                                                        </div>
                                                        <p class="m-b-lg">( <?php echo SCTEXT('Please add only 1 File.')?> <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/Sample-Operators.xlsx"><?php echo SCTEXT('Download Sample File') ?></a> )</p>
												    </div>
										</div>
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Import Data')?></button>
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