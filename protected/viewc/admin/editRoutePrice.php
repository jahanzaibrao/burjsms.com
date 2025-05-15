<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Route Price')?><small><?php echo SCTEXT('set route price for specific MNC-MCC')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="rprc_form" action="">
                                        <input type="hidden" name="routeid" id="routeid" value="<?php echo $data['rdata']->id ?>" />
                                        <input type="hidden" name="mccmnc" value="<?php echo $data['mdata']->mccmnc ?>" />
									     <div class="">
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Route')?>:</label>
												    <div class="col-md-8 p-t-xs">
													<span class="label label-md label-primary"><?php echo $data['rdata']->title ?></span>
												    </div>
											     </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('MCC MNC')?>:</label>
												    <div class="col-md-8 p-t-xs">
													<kbd><?php echo $data['mdata']->mccmnc ?></kbd>
												    </div>
											     </div>
                                             <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Network')?>:</label>
												    <div class="col-md-8 p-t-xs">
													<span><?php echo utf8_decode($data['mdata']->network) ?></span>
												    </div>
                                                 </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Region')?>:</label>
												    <div class="col-md-8 p-t-xs">
													<span><?php echo utf8_decode($data['mdata']->region) ?></span>
												    </div>
                                            </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Cost Price')?>:</label>
												    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                            <input type="text" class="form-control input-sm" name="rcostprc" placeholder="e.g. 0.045" value="<?php echo $data['rmdata']->cost_price!=0 && $data['rmdata']->cost_price!=null ? $data['rmdata']->cost_price : ''; ?>">
                                                        </div>
                                                        <span class="help-block"><?php echo SCTEXT('Enter the cost price for this Network. This is the price the SMPP provider is charging you.')?></span>
												    </div>
                                            </div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Default Selling Price')?>:</label>
												    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                            <input value="<?php echo $data['rmdata']->default_selling_price!=0 && $data['rmdata']->default_selling_price!=null ? $data['rmdata']->default_selling_price : ''; ?>" type="text" class="form-control input-sm" name="rsellprc" placeholder="e.g. 0.045">
                                                        </div>
                                                        <span class="help-block"><?php echo SCTEXT('This is the selling price for this network. You can change this for each user account as well.')?></span>
												    </div>
                                            </div>
                                             
                                            </div>
                                            	
                                        
                                      
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