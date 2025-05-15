
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manually Add Data')?><small><?php echo SCTEXT('manually add a few mobile numbers in blacklist database')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="manadd_form"> 
                                        <input type="hidden" name="tableid" value="<?php echo $data['tdata']->id ?>"/>    
                                        <div class="form-group">
											<label class="control-label col-md-3"><?php echo SCTEXT('Blacklist Table Name')?>:</label>
											<div class="col-md-8">
                                                <span class="label label-primary label-lg"><?php echo $data['tdata']->table_name ?></span>
											</div>
                                        </div>
                                        <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Enter mobile numbers')?>:</label>
                                        <div class="col-md-8">
                                            <textarea id="contactinput" class="form-control pop-over" name="numbers" placeholder="<?php echo SCTEXT('enter mobile numbers')?>. . . ." data-placement="top" data-content="<?php echo SCTEXT('Enter mobile numbers separated by newline e.g.')?> <br><p>97150xxxxx<br>97100xxxxx<br>97151xxxxxx</p>.... and so on" data-trigger="hover"></textarea>
                                        </div>
                                        </div>
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Import Data')?></button>
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