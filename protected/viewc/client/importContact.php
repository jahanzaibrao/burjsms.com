<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Import Contacts')?><small><?php echo SCTEXT('import bulk contacts into database')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="ct_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:2, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,text/csv,.xls', addRemoveLinks:true, params:{mode:'contacts'}, success: function(file,res){createInputFile('ct_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'contacts');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                        <input type="hidden" id="uprocess" value="0" />
                                        <input type="hidden" name="task" value="import" />
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Group')?>:</label>
												    <div class="col-md-8">
													   <select name="group" id="group" class="form-control" data-plugin="select2" data-options="{placeholder: '<?php echo SCTEXT('Select a contact group')?> ...'}">
                                                           <option></option>
                                                            <?php foreach($data['groups'] as $grp){
                                                            $colar = unserialize($grp->column_labels);
                                                           $colstr = '<button data-toggle="tooltip" data-placement="bottom" title="Required" type="button" class="btn btn-sm btn-danger">mobile</button> ';
                                                            $colstr .= '<button data-toggle="tooltip" data-placement="bottom" title="Required" type="button" class="btn btn-sm btn-danger">Name</button> ';
                                                            $colstr .= $colar['varC']==''?'<button data-toggle="tooltip" data-placement="bottom" title="Column Ignored" type="button" class="btn btn-sm btn-default">Ignore</button> ':'<button data-toggle="tooltip" data-placement="bottom" title="Custom Field" type="button" class="btn btn-sm btn-primary">'.$colar['varC'].'</button> ';
                                                            $colstr .= $colar['varD']==''?'<button data-toggle="tooltip" data-placement="bottom" title="Column Ignored" type="button" class="btn btn-sm btn-default">Ignore</button> ':'<button data-toggle="tooltip" data-placement="bottom" title="Custom Field" type="button" class="btn btn-sm btn-primary">'.$colar['varD'].'</button> ';
                                                            $colstr .= $colar['varE']==''?'<button data-toggle="tooltip" data-placement="bottom" title="Column Ignored" type="button" class="btn btn-sm btn-default">Ignore</button> ':'<button data-toggle="tooltip" data-placement="bottom" title="Custom Field" type="button" class="btn btn-sm btn-primary">'.$colar['varE'].'</button> ';
                                                            $colstr .= $colar['varF']==''?'<button data-toggle="tooltip" data-placement="bottom" title="Column Ignored" type="button" class="btn btn-sm btn-default">Ignore</button> ':'<button data-toggle="tooltip" data-placement="bottom" title="Custom Field" type="button" class="btn btn-sm btn-primary">'.$colar['varF'].'</button> ';
                                                            $colstr .= $colar['varG']==''?'<button data-toggle="tooltip" data-placement="bottom" title="Column Ignored" type="button" class="btn btn-sm btn-default">Ignore</button> ':'<button data-toggle="tooltip" data-placement="bottom" title="Custom Field" type="button" class="btn btn-sm btn-primary">'.$colar['varG'].'</button> ';
                                                           
                                                           ?>
                                                           <option data-colstr="<?php echo base64_encode($colstr) ?>" value="<?php echo $grp->id ?>"><?php echo $grp->group_name ?></option>
                                                           <?php } ?>
                                                        </select> 
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Country')?>:</label>
												    <div class="col-md-8">
                                                        <div class="radio radio-primary">
                                                            <input id="cnt1" checked="checked" value="0" type="radio" name="cnt_flag">
                                                            <label for="cnt1"><?php echo SCTEXT('Read from File')?></label>
                                                            <span class="help-block"><?php echo SCTEXT('Fetch the country from the uploaded EXCEL file. Country must be the <b>Seventh</b> column. e.g. <b>H</b> in case of Excel/CSV files')?>.</span>
                                                        </div>
                                                        
                                                        <div class="radio radio-primary">
                                                            <input id="cnt2" value="1" type="radio" name="cnt_flag">
                                                            <label for="cnt2"><?php echo SCTEXT('Select Below')?></label>
                                                            <select name="country" class="form-control" data-plugin="select2">
                                                                <?php foreach($data['covs'] as $cv){ ?>
                                                                   <option <?php if($cv->timezone==Doo::conf()->default_server_timezone){ ?> selected <?php } ?> value="<?php echo $cv->id ?>"><?php echo $cv->country ?></option>
                                                                   <?php } ?>
                                                                </select> 
                                                        </div>
                                                        
													   
												    </div>
										</div>
                                         <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Columns')?>:</label>
												    <div class="col-md-8">
													   <div id="colbox" class="panel panel-default p-t-xs">
                                                            - <?php echo SCTEXT('Select a group to display column suggestions')?> -
                                                        </div>
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Upload File')?>:</label>
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