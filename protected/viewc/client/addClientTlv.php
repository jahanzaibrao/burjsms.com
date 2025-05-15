<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add TLV Parameter')?><small><?php echo SCTEXT('add a new parameter to be sent with SMS')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->

                                    <form class="form-horizontal" method="post" id="ctlv_form" action="">
                                    <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMS Route')?>:</label>
												    <div class="col-md-8">
													   <select class="form-control" name="route" id="routesel" data-plugin="select2" data-options="{theme:'default'}">
                                                        <option data-tlvs="" value="0">- Select One -</option>
                                                            <?php foreach($data['routes'] as $rt){ ?>
                                                           <option data-tlvs='<?php echo $rt['tlv_ids'] ?>' value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                                           <?php } ?>
                                                        </select>
                                                        <span class="help-block text-info m-b-0"><?php echo SCTEXT('Select Route for which this TLV will be supplied')?>
                                                        </span>
												    </div>
											 </div>
                                        <div id="tlv_box" class="disabledBox">
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('TLV Type')?>:</label>
                                                        <div class="col-md-8">
                                                            <select class="tlv_controls form-control" data-plugin="select2" id="tlv_type" name="tlv_type">

                                                            </select>
                                                        </div>
                                            </div>

                                            <div class="form-group" id="tlv_title_box">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('TLV Title')?>:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" type="text" name="tlv_title" id="tlv_title" placeholder="e.g. Template ID for Bank Alerts">
                                                            <span class="help-block text-success m-b-0"><?php echo SCTEXT('Enter an easy to recognize name for this TLV')?>
                                                            </span>
                                                        </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Parameter Value')?>:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" type="text" name="tlv_value" id="tlv_value" placeholder="enter TLV value as specified by operator...">
                                                        </div>
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
