<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add New Ticket')?><small><?php echo SCTEXT('raise a new support ticket')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="st_form" action="">
                                        
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Title')?>:</label>
												    <div class="col-md-8">
													   <input type="text" class="form-control" name="tkttitle" id="tkttitle" placeholder="<?php echo SCTEXT('enter title for the ticket')?> . . . ." />
                                                        <span class="help-block"><?php echo SCTEXT('Use this to write a subject line for your concern')?></span>
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Priority')?>:</label>
												    <div class="col-md-8">
													   <div class="radio radio-info">
                                                            <input id="p0" checked="checked" value="0" type="radio" name="tpri">
                                                            <label for="p0"><span class="label label-info"><?php echo SCTEXT('Normal')?></span></label>
                                                            <span class="help-block"><?php echo SCTEXT('Use this option when the issue or concern is mild in nature and there is less urgency.')?></span>
                                                        </div>
                                                        <div class="radio radio-warning">
                                                            <input id="p1" value="1" type="radio" name="tpri">
                                                            <label for="p1"><span class="label label-warning"><?php echo SCTEXT('Medium')?></span></label>
                                                            <span class="help-block"><?php echo SCTEXT('Use this for issues affecting day-to-day operations.')?></span>
                                                        </div>
                                                        <div class="radio radio-danger">
                                                            <input id="p2" value="2" type="radio" name="tpri">
                                                            <label for="p2"><span class="label label-danger"><?php echo SCTEXT('Critical')?></span></label>
                                                            <span class="help-block"><?php echo SCTEXT('Use this for very critical problem which may require urgent attention. Do not use this all the time as this may result in delayed solutions.')?></span>
                                                        </div>
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Description')?>:</label>
												    <div class="col-md-8">
													  <textarea rows="10" placeholder="<?php echo SCTEXT('describe your issue in detail here')?> . . . ." class="form-control" name="tktdesc"></textarea>
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Include Files')?>:</label>
												    <div class="col-md-8">
													  <select multiple class="form-control" name="tfiles[]" data-plugin="select2" data-options="{placeholder: '<?php echo SCTEXT('Choose Files')?> ...'}">
                                                        <?php foreach($data['docs'] as $doc){ ?>
                                                          <option value="<?php echo $doc->id ?>"><?php echo $doc->filename ?></option>
                                                          <?php } ?>
                                                        </select>
                                                        <span class="help-block"><?php echo SCTEXT('You can link documents with your ticket for example, screenshots etc.')?> <a href="<?php echo Doo::conf()->APP_URL ?>addNewDocument"><?php echo SCTEXT('Add New File here')?></a> <?php echo SCTEXT("if you don't have the file you need.")?></span>
												    </div>
										</div>
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Submit')?></button>
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