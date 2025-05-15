
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add New Rich Card')?><small><?php echo SCTEXT('add new rich card to be used in RCS campaigns')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <form class="form-horizontal" method="post" id="add_orule_frm" data-plugin="dropzone" action="" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'image/png,image/gif,image/jpeg,.jpg', addRemoveLinks:true, params:{mode:'logo'}, success: function(file,res){createInputFile('add_orule_frm',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'logo');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Card Title')?>:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" placeholder="enter a title for this rich card . . . ." name="orname" id="orname">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Subtitle')?>:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" placeholder="enter a descriptive message conveying the offer . . ." name="orname" id="orname">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Orientation')?>:</label>
                                                    <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                                <input id="gen_m" name="wtformat" checked="checked" type="radio" value="m">
                                                                <label for="gen_m">Horizontal</label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="gen_f" name="wtformat" value="f" type="radio">
                                                                <label for="gen_f">Vertical</label>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Banner Image')?>:</label>
                                                    <div class="col-md-8">
                                                        <div class="dropzone text-center">
                                                                    <div class="dz-message">
                                                                        <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>

                                                                    </div>
                                                                    
                                                                </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Buttons')?>:</label>
                                                    <div class="col-md-8" id="templatebox">
                                                    <div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <span>You can add upto four buttons per rich card</span></div>
                                                        
                                                        <div class="p-sm panel m-b-xs bg-info text-white m-b-lg">
                                                            <table class="table">
                                                               
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Button Type</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="sendermatch[]" class="form-control input-sm">
                                                                                        <option value="0">Reply</option>
                                                                                        <option value="equal">Call</option>
                                                                                        <option value="start">URL</option>
                                                                                        
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="Button Label..">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="enter the action value here..">
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                <input id="switch-0-2" type="checkbox" checked data-color="#10c469" data-switchery="true" >
                                                                            </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Button Type</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="sendermatch[]" class="form-control input-sm">
                                                                                        <option value="0">Reply</option>
                                                                                        <option value="equal">Call</option>
                                                                                        <option value="start">URL</option>
                                                                                        
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="Button Label..">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="enter the action value here..">
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                <input id="switch-0-2" type="checkbox"  data-color="#10c469" data-switchery="true" >
                                                                            </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Button Type</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="sendermatch[]" class="form-control input-sm">
                                                                                        <option value="0">Reply</option>
                                                                                        <option value="equal">Call</option>
                                                                                        <option value="start">URL</option>
                                                                                        
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="Button Label..">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="enter the action value here..">
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                <input id="switch-0-2" type="checkbox"  data-color="#10c469" data-switchery="true" >
                                                                            </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Button Type</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="sendermatch[]" class="form-control input-sm">
                                                                                        <option value="0">Reply</option>
                                                                                        <option value="equal">Call</option>
                                                                                        <option value="start">URL</option>
                                                                                        
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="Button Label..">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="enter the action value here..">
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                <input id="switch-0-2" type="checkbox"  data-color="#10c469" data-switchery="true" >
                                                                            </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button id="save_changes" class="btn btn-primary" type="button">Save changes</button>
                                                        <button id="bk" class="btn btn-default" type="button">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                     
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>