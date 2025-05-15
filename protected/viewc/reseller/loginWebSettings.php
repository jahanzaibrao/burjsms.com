
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Login page Settings')?><small><?php echo SCTEXT("set the parameters and customize your website's login page")?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <?php
    $sdata = unserialize(base64_decode($data['pdata']->page_data));
    ?>
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="set_form">
                                        <input type="hidden" name="page" value="LOGIN" />
                                        
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Title')?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="pgtitle" id="pgtitle" placeholder="<?php echo SCTEXT('enter title for the login page')?> . . ." value="<?php echo $sdata['title'] ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Meta Description')?>:</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="metadesc" placeholder="<?php echo SCTEXT('describe your company')?>. . . "><?php echo $sdata['metadesc'] ?></textarea>
                                                </div>
                                            </div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Color theme')?>:</label>
												    <div class="col-md-8">
													   <select class="form-control" name="theme" data-plugin="select2" data-options="{templateResult: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span> '+data.text+'</span>');},templateSelection: function (data){return $('<span class=\'label label-'+data.id+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span>'+data.text+'</span>');} }">
                                                           <option <?php if($sdata['theme']=='info'){ ?> selected <?php } ?> value="info"> <?php echo SCTEXT('Blue Theme')?></option>
                                                            <option <?php if($sdata['theme']=='success'){ ?> selected <?php } ?> value="success"> <?php echo SCTEXT('Green Theme')?></option>
                                                            <option <?php if($sdata['theme']=='primary'){ ?> selected <?php } ?> value="primary"> <?php echo SCTEXT('Royal Blue Theme')?></option>
                                                            <option <?php if($sdata['theme']=='warning'){ ?> selected <?php } ?> value="warning"> <?php echo SCTEXT('Yellow Theme')?></option>
                                                            <option <?php if($sdata['theme']=='danger'){ ?> selected <?php } ?> value="danger"> <?php echo SCTEXT('Red Theme')?></option>
                                                            <option <?php if($sdata['theme']=='pink'){ ?> selected <?php } ?> value="pink"> <?php echo SCTEXT('Pink Theme')?></option>
                                                            <option <?php if($sdata['theme']=='purple'){ ?> selected <?php } ?> value="purple"> <?php echo SCTEXT('Purple Theme')?></option>
                                                            <option <?php if($sdata['theme']=='inverse'){ ?> selected <?php } ?> value="inverse"> <?php echo SCTEXT('Dark Theme')?></option>
                                                        </select> 
												    </div>
										</div>
                                        
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('New User Sign-up')?>:</label>
                                                    <div class="col-md-8">
                                                        <div class="radio radio-primary">
                                                            <input id="su-y" <?php if($sdata['regflag']=='1'){ ?> checked <?php } ?> value="1" type="radio" name="regflag">
                                                            <label for="su-y"><?php echo SCTEXT('Allow')?></label>
                                                            <span class="help-block"><?php echo SCTEXT('Users will be able to register from your website.')?></span>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input id="su-n" <?php if($sdata['regflag']=='0'){ ?> checked <?php } ?> value="0" type="radio" name="regflag">
                                                            <label for="su-n"><?php echo SCTEXT('Block')?></label>
                                                            <span class="help-block"><?php echo SCTEXT('Users will not be able to register themselves. Only user accounts added by you will be able to log-in.')?></span>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            
                                            
                                                
                                         
                                        <hr>
                                        <div class="form-group">
                                                        <div class="col-md-4"></div> 
                                                        <div class="col-md-8">   
                                                        <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes')?></button>
                                                        
                                                        <button class="btn btn-default m-l-md" id="bk" type="button"><?php echo SCTEXT('Cancel')?></button></div>
                                        </div>
                                        
                                     </form>   
                                        
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>