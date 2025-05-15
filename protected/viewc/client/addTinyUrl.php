<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('New Short URL')?><small><?php echo SCTEXT('add a new short url')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <form class="form-horizontal" method="post" id="turl_form" action="">
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Short URL Type')?>:</label>
												    <div class="col-md-8">
													   <select class="wd100 form-control" data-plugin="select2" id="urltype" name="urltype">
                                                            <option value="0" data-subtext="<?php echo SCTEXT('This will simply shorten the URL and save SMS characters while sending campaigns. A faster option for simple requirements.')?>"><?php echo SCTEXT('Regular Short Link')?></option>
                                                           <option value="1" data-subtext="<?php echo SCTEXT('This will shorten the URL and also provide reports on which numbers clicked it with device details. Works with personalised SMS only.')?>"><?php echo SCTEXT('Trackable Link')?></option>
                                                        </select>
                                                        <span class="help-block text-info" id="urltype_desc"><?php echo SCTEXT('This will simply shorten the URL and save SMS characters while sending campaigns. A faster option for simple requirements.')?></span>
												    </div>
										</div>
                                        
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Destination URL')?>:</label>
												    <div class="col-md-8">
													   <textarea id="redurl" name="redurl" maxlength="500" class="form-control" data-plugin="maxlength" data-options="{ alwaysShow: true, warningClass: 'label label-md label-info', limitReachedClass: 'label label-md label-danger', placement: 'bottom', message: 'used %charsTyped% of %charsTotal% chars.' }" placeholder="<?php echo SCTEXT('enter url to redirect')?> . . . ."></textarea>
												    </div>
										</div>
                                        
                                        
                                        
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Generate Link')?></button>
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