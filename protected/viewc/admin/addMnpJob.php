<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Add MNP Task') ?><small><?php echo SCTEXT('add import or delete job for MNP Database') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="upload_mnp_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFilesize:512, maxFiles:1, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,.xls,.csv ', addRemoveLinks:true, params:{mode:'ndnc'}, success: function(file,res){createInputFile('upload_mnp_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'ndnc');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Job Type') ?>:</label>
                                        <div class="col-md-8">
                                            <select name="jtype" data-plugin="select2">
                                                <option value="0"><?php echo SCTEXT('Import Job') ?></option>
                                                <option value="1"><?php echo SCTEXT('Delete Job') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Country') ?>:</label>
                                        <div class="col-md-8">
                                            <select name="coverage" data-plugin="select2">
                                                <?php foreach ($data['countries'] as $c) { ?>
                                                    <option value="<?php echo $c->prefix ?>"><?php echo $c->country ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block"><?php echo SCTEXT('If the mobile numbers in the file do not include country code please select the country above, otherwise they will be discarded') ?></span>
                                        </div>
                                    </div>
                                    <div class=" form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Upload File') ?>:<br>(<?php echo SCTEXT('CSV or Excel') ?>)</label>
                                        <div class="col-md-8 dropzone">
                                            <div class="dz-message">
                                                <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>
                                                <p class="m-b-lg">( <?php echo SCTEXT('Make sure the first column contains mobile numbers') ?> )</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Add Task') ?></button>
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