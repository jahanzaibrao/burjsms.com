
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add Blacklist DB')?><small><?php echo SCTEXT('add a new blacklist table')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="add_bldb_form" action="">
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Table Name')?>:</label>
												    <div class="col-md-8 input-group">
                                                        <span class="input-group-addon">sc_</span>
													<input type="text" name="tname" id="tname" class="form-control" placeholder="<?php echo SCTEXT('enter table name, no space e.g. global_blacklist')?>" maxlength="100" />
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Mobile phone Field name')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="mcol" id="mcol" class="form-control" placeholder="<?php echo SCTEXT('enter mobile column name e.g. mobile or msisdn etc.')?> " maxlength="100" />
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