<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Contact')?><small><?php echo SCTEXT('modify a saved contact from')?> <b><?php echo $data['gdata']->group_name ?></b></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <?php $colar = unserialize($data['gdata']->column_labels); ?>
                                    <form class="form-horizontal" method="post" id="ct_form" action="">
                                        <input type="hidden" name="cid" value="<?php echo $data['cinfo']->id ?>"/>
                                        <input type="hidden" name="groupid" id="groupid" value="<?php echo $data['gdata']->id ?>"/>
                                       
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Contact number')?>:</label>
												    <div class="col-md-8">
													   <input type="tel" class="form-control" name="contactno" id="contactno" placeholder="<?php echo SCTEXT('enter mobile number')?> . . . ." value="<?php echo $data['cinfo']->mobile ?>" />
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Name')?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="cname" id="cname" placeholder="<?php echo SCTEXT('enter name associated with this contact')?> . . . ." value="<?php echo $data['cinfo']->name ?>" />
												    </div>
										</div>
                                        
                                         <?php if($colar['varC']!=''){ ?>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo $colar['varC'] ?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="varC" id="varC" placeholder="enter <?php echo $colar['varC'] ?> associated with this contact . . . ." value="<?php echo $data['cinfo']->varC ?>" />
												    </div>
										      </div>
                                        <?php } ?>
                                        <?php if($colar['varD']!=''){ ?>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo $colar['varD'] ?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="varD" id="varD" placeholder="enter <?php echo $colar['varD'] ?> associated with this contact . . . ." value="<?php echo $data['cinfo']->varD ?>" />
												    </div>
										      </div>
                                        <?php } ?>
                                        <?php if($colar['varE']!=''){ ?>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo $colar['varE'] ?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="varE" id="varE" placeholder="enter <?php echo $colar['varE'] ?> associated with this contact . . . ." value="<?php echo $data['cinfo']->varE ?>" />
												    </div>
										      </div>
                                        <?php } ?>
                                        <?php if($colar['varF']!=''){ ?>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo $colar['varF'] ?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="varF" id="varF" placeholder="enter <?php echo $colar['varF'] ?> associated with this contact . . . ." value="<?php echo $data['cinfo']->varF ?>" />
												    </div>
										      </div>
                                        <?php } ?>
                                        <?php if($colar['varG']!=''){ ?>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo $colar['varG'] ?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="varG" id="varG" placeholder="enter <?php echo $colar['varG'] ?> associated with this contact . . . ." value="<?php echo $data['cinfo']->varG ?>" />
												    </div>
										      </div>
                                        <?php } ?>
                                        
                                        
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Country')?>:</label>
												    <div class="col-md-8">
													   <select name="country" class="form-control" data-plugin="select2">
                                                            <?php foreach($data['covs'] as $cv){ ?>
                                                           <option <?php if($data['cinfo']->country==$cv->id){ ?> selected <?php } ?> value="<?php echo $cv->id ?>"><?php echo $cv->country ?></option>
                                                           <?php } ?>
                                                        </select> 
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Network')?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="cnet" id="cnet" placeholder="<?php echo SCTEXT('enter network operator for this contact')?> . . . ." value="<?php echo $data['cinfo']->network ?>" />
                                                        <span class="help-block"><?php echo SCTEXT("Our system can automatically identify the network operator based on mobile number prefix if data is available. You could leave it blank if you're not sure.")?></span>
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Region')?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="ccir" id="ccir" placeholder="<?php echo SCTEXT('enter network region for this contact')?> . . . ." value="<?php echo $data['cinfo']->circle ?>" />
                                                        <span class="help-block"><?php echo SCTEXT("Our system can automatically identify the circle location based on mobile number prefix, if data is available. You could leave it blank if you're not sure.")?></span>
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