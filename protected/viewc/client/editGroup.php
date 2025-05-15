<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Group')?><small><?php echo SCTEXT('modify contact group name')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="grp_form" action="">
                                        <input type="hidden" name="grpid" value="<?php echo $data['group']->id ?>" />
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Group Name')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="gname" id="gname" class="form-control" placeholder="<?php echo SCTEXT('enter contact group name')?>. . . ." maxlength="100" value="<?php echo $data['group']->group_name ?>" />
                                                       
												    </div>
										</div>
                                        <?php $colar = unserialize($data['group']->column_labels); ?>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Custom Fields')?>:</label>
												    <div class="col-md-8">
													   <span class="help-block text-info"><?php echo SCTEXT('Now you can add upto 5 custom fields for contacts. Each group can have separate labels for these custom fields. Leave empty if you do not wish to use any of them.')?></span>
                                                        <span class="help-block text-danger"><?php echo SCTEXT('Column variable A and B are reserved. "A" stores mobile numbers and "B" stores name associated with the contact.')?></span>
                                                        <table class="wd100 table table-responsive">
                                                            <thead>
                                                                <tr class="info text-inverse">
                                                                    <th><?php echo SCTEXT('DB Column')?></th>
                                                                    <th><?php echo SCTEXT('Column Label')?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>varC</td>
                                                                    <td><input type="text" class="form-control" name="varC" placeholder="<?php echo SCTEXT('add label for custom field')?> . . ." value="<?php echo $colar['varC'] ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>varD</td>
                                                                    <td><input type="text" class="form-control" name="varD" placeholder="<?php echo SCTEXT('add label for custom field')?> . . ." value="<?php echo $colar['varD'] ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>varE</td>
                                                                    <td><input type="text" class="form-control" name="varE" placeholder="<?php echo SCTEXT('add label for custom field')?> . . ." value="<?php echo $colar['varE'] ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>varF</td>
                                                                    <td><input type="text" class="form-control" name="varF" placeholder="<?php echo SCTEXT('add label for custom field')?> . . ." value="<?php echo $colar['varF'] ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>varG</td>
                                                                    <td><input type="text" class="form-control" name="varG" placeholder="<?php echo SCTEXT('add label for custom field')?> . . ." value="<?php echo $colar['varG'] ?>"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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