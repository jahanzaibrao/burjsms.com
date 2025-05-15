
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add Staff Member')?><small><?php echo SCTEXT('add a new staff member')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="staff_form" action="">
                                        <div class="col-md-6">
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Team')?>:</label>
												    <div class="col-md-8">
													   <select class="form-control" name="team" data-plugin="select2" data-options="{templateResult: function (data){return $('<span class=\'label label-'+data.title+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span> '+data.text+'</span>');},templateSelection: function (data){return $('<span class=\'label label-'+data.title+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span>'+data.text+'</span>');} }">
                                                           <?php foreach($data['tdata'] as $team){ ?>
                                                            <option title="<?php echo $team->theme ?>" value="<?php echo $team->id ?>"> <?php echo $team->name ?></option>
                                                            <?php } ?>
                                                        </select> 
												    </div>
										</div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Member Name')?>:</label>
                                                        <div class="col-md-8">
                                                        <input type="text" name="sname" id="sname" class="form-control" placeholder="<?php echo SCTEXT('enter staff member name')?>..." maxlength="100" />
                                                        </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Gender')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="gen_m" name="gender" checked="checked" type="radio" value="m">
                                                                <label for="gen_m"><?php echo SCTEXT('Male')?></label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="gen_f" name="gender" value="f" type="radio">
                                                                <label for="gen_f"><?php echo SCTEXT('Female')?></label>
                                                            </div>
                                                        </div>
                                            </div>

                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Email ID')?>:</label>
                                                        <div class="col-md-8 abs-ctr">
                                                        <input type="text" name="semail" id="semail" class="form-control" placeholder="<?php echo SCTEXT('enter email address')?>.." maxlength="100" />
                                                            <span id="v-email" class="val-icon"></span>
                                                        </div>
                                            </div>

                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Phone')?>:</label>
                                                        <div class="col-md-8 abs-ctr">
                                                        <input type="text" name="sphn" id="sphn" class="form-control" placeholder="e.g. +919887012345 . . ." maxlength="50" />
                                                            <span id="v-phn" class="val-icon"></span>
                                                        </div>
                                            </div>

                                        </div>
                                        
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Login ID')?>:</label>
                                                        <div class="col-md-8 abs-ctr">
                                                        <input type="text" name="slogin" id="slogin" class="form-control" placeholder="<?php echo SCTEXT('enter unique login ID for staff member')?>" maxlength="100" />
                                                            <span id="v-login" class="val-icon"></span>
                                                        </div>
                                            </div>

                                            

                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Enter Password')?>:</label>
                                                        <div class="col-md-8">
                                                        <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="spass" id="spass" class="form-control" placeholder="<?php echo SCTEXT('enter password')?>..." maxlength="100" />
                                                        </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Retype Password')?>:</label>
                                                        <div class="col-md-8">
                                                        <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="spass2" id="spass2" class="form-control" placeholder="<?php echo SCTEXT('enter password again')?>..." maxlength="100" />
                                                            <span id="pass-err" class="help-block text-danger"></span>
                                                            <span id="pass-help" class="help-block text-primary">
                                                                <?php switch(Doo::conf()->password_strength){
                                                                   case 'weak':
                                                                   echo SCTEXT('Password length should be minimum 6 characters.');
                                                                   break;

                                                                   case 'average':
                                                                   echo SCTEXT('Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.');
                                                                   break;

                                                                   case 'strong':
                                                                   echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.');
                                                                   break;
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
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