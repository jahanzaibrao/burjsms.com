
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Block IP')?><small><?php echo SCTEXT('manually enter IP adresses to block')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" action="<?php echo Doo::conf()->APP_URL ?>saveBlockIps" method="post" id="blipfrm">
										
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Enter IP Address')?></label>
												<div class="col-md-8">
													<textarea class="form-control pop-over" name="ipadds" placeholder="<?php echo SCTEXT('enter single or multiple ip addresses')?> . . . ." data-placement="top" data-content="<?php echo SCTEXT('To block multiple IP enter IP separated by newline e.g.<br><p>123.45.67.89<br>234.56.78.90<br>345.67.89.01</p>.... and so on')?>" data-trigger="hover"></textarea>
												</div>
											</div>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Remarks')?></label>
												<div class="col-md-8">
                                                    <textarea class="form-control" name="bliprem" placeholder="<?php echo SCTEXT('enter remarks regarding this action')?> ..."></textarea>
												</div>
											</div>
                                            
                                        <hr>
											<div class="form-group">
                                            <div class="col-md-3"></div>
												<div class="col-md-8">
													<button id="save_changes" class="btn btn-primary" type="submit"><?php echo SCTEXT('Save changes')?></button>
													<button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel')?></button>
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