<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Upload Gateway Cost Price') ?><small><?php echo SCTEXT('upload sms cost price for this SMPP') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="upload_rprc_form" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'text/csv,text/x-csv,text/plain,.csv', addRemoveLinks:true, params:{mode:'ndnc'}, success: function(file,res){createInputFile('upload_rprc_form',res); $('#uprocess').val('0');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'rprice');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <input type="hidden" name="smppid" id="smppid" value="<?php echo $data['rdata']->id ?>">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('SMPP Gateway') ?>:</label>
                                        <div class="col-md-8 p-t-xs">
                                            <span class="label label-md label-dark"><?php echo $data['rdata']->title ?> ( <?php echo $data['rdata']->smsc_id ?> )</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Instructions') ?>:</label>
                                        <div class="col-md-8">
                                            <div>
                                                <p>
                                                    <span class="numberCircle bg-primary">1</span>
                                                    <?php echo SCTEXT('Download the sample file based on your preference.') ?>
                                                </p>
                                                <div class="input-group col-md-6 m-h-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-addon"><?php echo SCTEXT('Select Preference') ?> </span>
                                                    </div>
                                                    <div class="">
                                                        <div class="radio radio-primary planopts p-l-lg">
                                                            <input id="lp1" checked="checked" value="0" type="radio" name="list_pref">
                                                            <label for="lp1"><?php echo SCTEXT('By Countries') ?></label>
                                                            <span class="help-block"><?php echo SCTEXT('Set prices country wise. The price will be applied to all operators in the country.') ?><br>
                                                                <table class="table table-striped">
                                                                    <tr>
                                                                        <td>US</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.04</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>IN</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.01</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>TH</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.02</td>
                                                                    </tr>
                                                                </table>
                                                            </span>
                                                        </div>
                                                        <div class="radio radio-primary planopts p-l-lg">
                                                            <input id="lp2" value="1" type="radio" name="list_pref">
                                                            <label for="lp2"><?php echo SCTEXT('By Operators') ?></label>
                                                            <span class="help-block"><?php echo SCTEXT('Set prices per operator for each country. The price will be applied to all MCCMNC for each operators in the country.') ?> <br>
                                                                <table class="table table-striped">
                                                                    <tr>
                                                                        <td>US</td>
                                                                        <td>Verizon</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.04</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>IN</td>
                                                                        <td>Vodafone</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.01</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>IN</td>
                                                                        <td>Airtel</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.02</td>
                                                                    </tr>
                                                                </table>

                                                            </span>
                                                        </div>
                                                        <div class="radio radio-primary planopts p-l-lg">
                                                            <input id="lp3" value="2" type="radio" name="list_pref">
                                                            <label for="lp3"><?php echo SCTEXT('By MCCMNC') ?></label>
                                                            <span class="help-block"><?php echo SCTEXT('Set prices for each MCCMNC. This will allow to set prices for each MCCMNC for all operators.') ?> <br>
                                                                <table class="table table-striped">
                                                                    <tr>
                                                                        <td>IN</td>
                                                                        <td>Airtel</td>
                                                                        <td>40431</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.04</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>IN</td>
                                                                        <td>Airtel</td>
                                                                        <td>40470</td>
                                                                        <td><?php echo trim(Doo::conf()->currency) ?>0.01</td>
                                                                    </tr>
                                                                </table>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group-append">
                                                        <button class="form-control btn-info" type="button" id="dl_list_pref"><?php echo SCTEXT('Download File') ?></button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div>
                                                <p>
                                                    <span class="numberCircle bg-primary">2</span>
                                                    <?php echo SCTEXT('Enter the Cost price in the mentioned column. To disable routing in MCCMNC Plans for any Country/Operator/MCCMNC, leave the corresponding price column empty.') ?>
                                                </p>
                                            </div>
                                            <div>
                                                <p>
                                                    <span class="numberCircle bg-primary">3</span>
                                                    <?php echo SCTEXT('Upload the saved CSV file below. Once the upload is finished, click on Import Data button.') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Upload File') ?>:<br>(<?php echo SCTEXT('CSV Files Only') ?>)</label>
                                        <div class="col-md-8 dropzone">
                                            <div class="dz-message">
                                                <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>
                                                <p class="m-b-lg">( <?php echo SCTEXT('Download sample file mentioned above and follow instructions') ?> )</p>
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