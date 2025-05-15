<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Move Contacts')?><small><?php echo SCTEXT('shift contacts to another group')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   <?php
                                    $gid = intval($data['gid']);
                                    $gobj = array_filter($data['groups'], function ($e) use ($gid) {
                                                            return $e->id == $gid;
                                                            }); 
                                    $k = key($gobj);
                                    ?>
                                    <form class="form-horizontal" method="post" id="grp_form" action="">
                                        <input type="hidden" name="grpid" value="<?php echo $data['gid'] ?>" />
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Group Name')?>:</label>
												    <div class="col-md-8">
                                                        <span class="label label-lg label-primary label-flat">
                                                            <?php echo $gobj[$k]->group_name ?>
                                                        </span>
													 
												    </div>
										</div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Total Contacts')?>:</label>
												    <div class="col-md-8">
                                                        <span class="label label-flat label-danger">
                                                            <?php echo number_format(intval($data['cno'])); ?>
                                                        </span>
													 
												    </div>
										</div>
                                        
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Move to Group')?>:</label>
												    <div class="col-md-8">
                                                        <select class="form-control" id="grp" name="grp" data-plugin="select2">
                                                            <?php foreach($data['groups'] as $grp){
                                                            if($gid!=$grp->id){
                                                            ?>
                                                            <option value="<?php echo $grp->id ?>"><?php echo $grp->group_name ?></option>
                                                            <?php }} ?>
                                                        </select>
													 
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