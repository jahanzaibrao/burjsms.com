
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Refund Rule')?><small><?php echo SCTEXT('change refund rule details')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="add_rrule_form" action="">
                                        <input type="hidden" name="rule_id" value="<?php echo $data['rdata']->id ?>"/>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Rule Name')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="rname" id="rname" class="form-control" placeholder="<?php echo SCTEXT('enter rule name')?>..." maxlength="100" value="<?php echo $data['rdata']->title ?>" />
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Description')?>:</label>
												    <div class="col-md-8">
													<textarea name="rdesc" placeholder="<?php echo SCTEXT('explain what is the purpose of this refund rule. In which case refund is made as per this rule')?>.." class="form-control" ><?php echo $data['rdata']->description ?></textarea>
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