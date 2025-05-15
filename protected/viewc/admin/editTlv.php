 <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit TLV Parameters')?><small><?php echo SCTEXT('modify TLV parameters here')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="tlvfrm">
                                        <input type="hidden" name="tlv_id" value="<?php echo $data['tlv']->id ?>">
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Tag Title')?></label>
												<div class="col-md-8">
													<input value="<?php echo $data['tlv']->tlv_title ?>" placeholder="enter a recognizable title for this tag.." type="text" name="tlv_title" id="tlv_title" class="form-control">
												</div>
											</div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('TLV Category')?></label>
												<div class="col-md-8">
                                                    <select id="tlv_cat" data-plugin="select2" class="form-control" data-placeholder="<?php echo SCTEXT('Select one')?>.." name="tlv_cat">
                                                    <?php foreach(Doo::conf()->tlv_categories as $tlvcat){ ?>
                                                        <option <?php if($data['tlv']->tlv_category==$tlvcat){ ?> selected <?php } ?> value="<?php echo $tlvcat ?>"><?php echo $tlvcat ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <h4 class="page-title">Operator TLV Settings</h4>
                                            <div class="form-group">
												<label class="control-label col-md-3"><kbd><?php echo SCTEXT('TLV Name')?></kbd></label>
												<div class="col-md-8">
                                                    <input value="<?php echo $data['tlv']->tlv_name ?>" placeholder="enter tlv name parameter.." type="text" name="tlv_name" id="tlv_name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><kbd><?php echo SCTEXT('TLV Tag')?></kbd></label>
												<div class="col-md-8">
                                                    <input value="<?php echo $data['tlv']->tlv_tag ?>" placeholder="enter tlv tag parameter. e.g. 0x1401" type="text" name="tlv_tag" id="tlv_tag" class="form-control">
                                                </div>
											</div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><kbd><?php echo SCTEXT('TLV Type')?></kbd></label>
												<div class="col-md-8">
													<select id="tlv_type" data-plugin="select2" class="form-control" data-placeholder="<?php echo SCTEXT('Select one')?>.." name="tlv_type">
                                                    <option <?php if($data['tlv']->tlv_type=="integer"){ ?> selected <?php } ?> value="integer">Integer</option>
                                                    <option <?php if($data['tlv']->tlv_type=="nulterminated"){ ?> selected <?php } ?> value="nulterminated">Null-Terminated String</option>
                                                    <option <?php if($data['tlv']->tlv_type=="octetstring"){ ?> selected <?php } ?> value="octetstring">Octet String</option>
                                                    </select>

												</div>
                                            </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><kbd><?php echo SCTEXT('TLV Length')?></kbd></label>
												    <div class="col-md-8">
													<input value="<?php echo $data['tlv']->tlv_length ?>" type="text" name="tlv_length" id="tlv_length" class="form-control" placeholder="<?php echo SCTEXT('tlv length in bytes e.g. 30')?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" />
												    </div>
											</div>
                                            <h4 class="page-title">Global Default Value</h4>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Default Value')?></label>
												<div class="col-md-8">
													<input value="<?php echo $data['tlv']->default_value ?>" placeholder="enter a default value for this TLV.." type="text" name="def_tlv" id="def_tlv" class="form-control">
                                                    <span class="help-block">
                                                        If clients do not supply this TLV, above value will be supplied. Leave this empty if you do not want to supply a global default value for this TLV.
                                                    </span>
												</div>
											</div>
                                        <hr>
											<div class="form-group">
                                            <div class="col-md-3"></div>
												<div class="col-md-8">
													<button id="save_changes" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes')?></button>
													<button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel')?></button>
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
