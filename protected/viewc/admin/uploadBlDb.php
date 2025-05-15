<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Upload Mobile numbers') ?><small><?php echo SCTEXT('load data in blacklist database') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="upload_bldb_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:5, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,.xls,.csv ', addRemoveLinks:true, params:{mode:'ndnc'}, success: function(file,res){createInputFile('upload_bldb_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'ndnc');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Select Blacklist Table') ?>:</label>
                                        <div class="col-md-8">
                                            <select id="seltbl" name="tid" data-plugin="select2">
                                                <option value="0">- <?php echo SCTEXT('Select One') ?> -</option>
                                                <?php foreach ($data['tdata'] as $tbl) { ?>
                                                    <option value="<?php echo $tbl->id ?>"><?php echo $tbl->table_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Upload File') ?>:<br>(<?php echo SCTEXT('CSV Files Only') ?>)</label>
                                        <div class="col-md-8 dropzone">
                                            <div class="dz-message">
                                                <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>
                                                <p class="m-b-lg">( <?php echo SCTEXT('You can add upto 5 files here.') ?> <?php echo SCTEXT('Make sure the title of the column containing mobile numbers is PHONE') ?> )</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Import Data') ?></button>
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