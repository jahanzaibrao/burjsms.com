
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Blacklist DB')?><small><?php echo SCTEXT('edit table name or field name')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="edit_bldb_form" action="">
                                        <input type="hidden" name="tid" value="<?php echo $data['tdata']->id ?>">
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Table Name')?>:</label>
												    <div class="col-md-8 input-group">
                                                        <span class="input-group-addon">sc_</span>
													<input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter table name, no space e.g. global_blacklist')?>" maxlength="100" value="<?php echo str_replace('sc_','',$data['tdata']->table_name) ?>" />
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Mobile phone Field name')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="mcol" id="mcol" class="form-control" placeholder="<?php echo SCTEXT('enter mobile column name e.g. mobile or msisdn etc.')?> " maxlength="100" value="<?php echo $data['tdata']->mobile_column ?>" />
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