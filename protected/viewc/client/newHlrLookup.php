<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('New HLR Lookup')?><small><?php echo SCTEXT('perform a new HLR lookup')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   <?php if($_SESSION['user']['group']!='admin' && $data['hlrperm']==0){ ?>
                                    <h4>No HLR channel is assigned to your account. Please contact your Account Manager and get HLR account activated.</h4>
                                   <?php }else{ ?>

                                    <form class="form-horizontal" method="post" id="hlr_form" action="">
                                        <?php if($_SESSION['user']['group']=='admin'){ ?>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Channel')?>:</label>
												<div class="col-md-8">
                                                    <select name="channel" data-plugin="select2" class="form-control">
                                                        <?php foreach($data['channels'] as $ch){ ?>
                                                            <option value="<?php echo $ch->id ?>"><?php echo $ch->channel_name ?></option>
                                                        <?php } ?>
                                                    </select>
												</div>
										</div>
                                        <?php }else{
                                            if($_SESSION['user']['account_type']==0){
                                            ?>
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('HLR Credits')?>:</label>
												<div class="col-md-8 p-t-xs">
                                                <kbd><?php echo number_format($data['hlrdata']->credits_cost) ?> lookups</kbd>
												</div>
                                            </div>
                                            <?php } else { ?>
                                                <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Lookup Price')?>:</label>
												<div class="col-md-8 p-t-xs">
                                                    <kbd><?php echo Doo::conf()->currency.$data['hlrdata']->credits_cost ?> per lookup</kbd>
												</div>
                                            </div>
                                        <?php }} ?>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Mobile Numbers')?>:</label>
												    <div class="col-md-6">
                                                    <span class="help-block text-danger">Must include country prefix in contact numbers for proper HLR lookup e.g. 91 for India, 39 for Italy etc.</span>
                                                        <textarea id="contactinput" class="form-control pop-over" name="numbers" placeholder="<?php echo SCTEXT('enter mobile numbers')?>. . . ." data-placement="top" data-content="<?php echo SCTEXT('Enter mobile numbers separated by newline e.g.')?> <br><p>919876xxxxx<br>918901xxxxx<br>919015xxxxxx</p>.... and so on" data-trigger="hover"></textarea>
												    </div>
										</div>
                                        
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Submit Lookup Request')?></button>
													<button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel')?></button>
												</div>
											</div>
                                    </form>   
                                    
                                    <?php } ?>
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>           