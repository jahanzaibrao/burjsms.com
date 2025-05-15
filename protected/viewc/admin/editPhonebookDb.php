
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Phonebook Group')?><small><?php echo SCTEXT('edit group name for phonebook contacts')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="add_pbdb_form" action="">
                                        <input type="hidden" name="pbgid" value="<?php echo $data['gdata']->id ?>"/>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Group Name')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="pbgroup" class="form-control" placeholder="<?php echo SCTEXT('enter name for phonebook contact group')?> ..." maxlength="150" value="<?php echo $data['gdata']->group_name ?>" />
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