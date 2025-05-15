<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Import Phonebook Contacts')?><small><?php echo SCTEXT('upload contacts for')?>: <?php echo $data['gdata']->group_name ?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="pbct_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:2, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,text/csv,.xls', addRemoveLinks:true, params:{mode:'phonebook'}, success: function(file,res){createInputFile('pbct_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'phonebook');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                        <input type="hidden" id="uprocess" value="0" />
                                        <input type="hidden" name="task" value="import" />
                                        <input type="hidden" id="pbdbid" name="pbdbid" value="<?php echo $data['gdata']->id ?>" />
                                        
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Upload File')?>:<br>(xls, xlsx, txt, csv)</label>
												    <div class="col-md-8 dropzone text-center">
													   <div class="dz-message">
                                                            <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.')?></h3>
                                                            <p class="m-b-lg">( <?php echo SCTEXT('You can add upto 2 files here.')?> )</p>
                                                        </div>
                                                        <p class="m-b-lg">( Upload <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/XLS- sample file.xls" target="_blank"><u>xls</u></a>, <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/XLSX- sample file.xlsx" target="_blank"><u>xlsx</u></a>, <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/CSV- sample file.csv" target="_blank"><u>csv</u></a> or <a href="<?php echo Doo::conf()->APP_URL ?>global/sample_files/TXT- sample file.txt" target="_blank"><u>txt</u></a> files )</p>
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