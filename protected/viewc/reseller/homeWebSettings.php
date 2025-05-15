
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Homepage Settings')?><small><?php echo SCTEXT("customize the appearance of your website's homepage")?> </small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <?php
    $sdata = unserialize(base64_decode($data['pdata']->page_data));
    ?>
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="set_form" enctype="multipart/form-data">
                                        <input type="hidden" name="page" value="HOME" />
                                        <input type="hidden" name="sld-1-oldimg" value="<?php echo $sdata['sliderdata'][0]['image'] ?>" />
                                        <input type="hidden" name="sld-2-oldimg" value="<?php echo $sdata['sliderdata'][1]['image'] ?>" />
                                        <input type="hidden" name="sld-3-oldimg" value="<?php echo $sdata['sliderdata'][2]['image'] ?>" />
                                        <input type="hidden" name="sld-4-oldimg" value="<?php echo $sdata['sliderdata'][3]['image'] ?>" />
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Title')?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="pgtitle" id="pgtitle" placeholder="<?php echo SCTEXT('enter title for the home page')?> . . ." value="<?php echo $sdata['title'] ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Meta Description')?>:</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="metadesc" placeholder="<?php echo SCTEXT('enter description of your business')?> . . ."><?php echo $sdata['metadesc'] ?></textarea>
                                                </div>
                                            </div>
                                            <h4 class="Widget-title"><?php echo SCTEXT('Test Gateway Widget')?></h4>
                                            <hr>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Display')?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                                <input id="twg-y" <?php if($sdata['twgflag']==1){ ?> checked="checked" <?php } ?>  name="twgflag" value="1" type="radio">
                                                                <label for="twg-y"><?php echo SCTEXT('Yes')?></label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="twg-n" <?php if($sdata['twgflag']==0){ ?> checked="checked" <?php } ?> name="twgflag" value="0" type="radio">
                                                                <label for="twg-n"><?php echo SCTEXT('No')?></label>
                                                            </div>
                                                    <span class="help-block m-b-0"><?php echo SCTEXT('Select YES if you want to display the Test Gateway Box on homepage. This will allow your users to quickly test SMS delivery')?></span>
                                                </div>
                                            </div>
                                            
                                            <div id="twg-opts" <?php if($sdata['twgflag']==0){ ?> class="disabledBox" <?php } ?>>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Widget Title')?>:</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" name="twgtitle" id="twgtitle" placeholder="<?php echo SCTEXT('e.g. Test our SMS Delivery')?>" value="<?php echo $sdata['twgdata']['title'] ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Select Route')?>:</label>
                                                    <div class="col-md-8">
                                                        <select name="twgrt" data-plugin="select2" class="form-control">
                                                                    <?php foreach($data['rdata'] as $rt){ ?>
                                                                    <option <?php if($sdata['twgdata']['route']==$rt->id){ ?> selected <?php } ?> value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="help-block m-b-0"><?php echo SCTEXT('Select route you wish to use to send test SMS.')?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Sender ID')?>:</label>
                                                            <div class="col-md-8">
                                                                <?php if($_SESSION['user']['group']=='admin'){ ?>
                                                                <input class="form-control" name="twgsid" type="text" placeholder="<?php echo SCTEXT('enter sender ID e.g. WEBSMS')?>" id="twgsid" value="<?php echo $sdata['twgdata']['sender'] ?>" />
                                                                <?php }else{ ?>
                                                                <select name="twgsid" data-plugin="select2" class="form-control">
                                                                <?php foreach($data['sdata'] as $sid){ ?>
                                                                <option <?php if($sdata['twgdata']['sender']==$sid->sender_id){ ?> selected <?php } ?> value="<?php echo $sid->sender_id ?>"><?php echo $sid->sender_id ?></option>
                                                                <?php } ?>
                                                                </select>
                                                                <?php } ?>
                                                                <span class="help-block m-b-0"><?php echo SCTEXT('Provide a sender ID to be used for sending test SMS')?></span>
                                                            </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('SMS Text')?>:</label>
                                                    <div class="col-md-8">
                                                        <textarea data-placement="top" class="form-control pop-over" data-original-title="<?php echo SCTEXT('SMS Text')?>" data-content="<?php echo SCTEXT('You could enter welcome message for example: <br> <i>Welcome to our company. Thank you for testing our sms portal. For more information, visit www.mycompany.com</i>')?>" data-trigger="hover" name="twgsms" placeholder="<?php echo SCTEXT('enter content of sms you wish to send')?>. . . ."><?php echo $sdata['twgdata']['sms'] ?></textarea>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            
                                            <h4 class="Widget-title"><?php echo SCTEXT('Page Content')?></h4>
                                            <hr>
                                            <div class="form-group p-l-md">
                                                <textarea name="homecnt" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $sdata['content']==''?SCTEXT('Enter content for Home page').' . . .':$sdata['content']; ?></textarea>
                                            </div>
                                                
                                            
                                        </div>
                                        
                                        
                                        
                                        
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Homepage Slider')?>:</label>
                                                <div class="col-md-8">
                                                    <div class="radio radio-inline radio-primary">
                                                                <input id="sld-y" <?php if($sdata['sliderflag']==1){ ?> checked="checked" <?php } ?> name="sldflag" value="1" type="radio">
                                                                <label for="sld-y"><?php echo SCTEXT('Display')?></label>
                                                            </div>
                                                            <div class="radio radio-inline radio-primary">
                                                                <input id="sld-n" <?php if($sdata['sliderflag']==0){ ?> checked="checked" <?php } ?> name="sldflag" value="0" type="radio">
                                                                <label for="sld-n"><?php echo SCTEXT('Hide')?></label>
                                                            </div>
                                                    <span class="help-block m-b-0"><?php echo SCTEXT('Check your theme selection for correct size of images for slider.')?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group <?php if($sdata['sliderflag']==0){ ?> disabledBox <?php } ?>" id="sld-opts">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Slider Images')?>:</label>
                                                <div class="col-md-8">
                                                    <div class="col-md-12 sld-banners">
                                                        <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                            <div class="thumb">
                                                            <img src="<?php echo $sdata['sliderdata'][0]['image']==''?'./global/img/placeholder_1.png':'./global/img/banners/'.$sdata['sliderdata'][0]['image']; ?>" class="img-responsive" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 p-l-md">
                                                            <input type="text" name="sld-1-t" class="form-control input-sm" placeholder="<?php echo SCTEXT('enter title for this image')?>" value="<?php echo $sdata['sliderdata'][0]['title'] ?>" />
                                                            <hr class="m-b-xs m-t-xs">
                                                            <textarea class="form-control" name="sld-1-d" placeholder="<?php echo SCTEXT('enter some description')?>. . ."><?php echo $sdata['sliderdata'][0]['desc'] ?></textarea>
                                                            <hr>
                                                            <input id="sld-1-img" class="sld-file hidden" name="sld-1-img" type="file">
                                                            <label for="sld-1-img" class="label label-md label-primary pointer"><i class="fa fa-lg fa-upload"></i> &nbsp;&nbsp;<?php echo SCTEXT('Change Image')?></label>
                                                            <span class="help-block m-b-0"></span>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    
                                                    <div class="col-md-12 sld-banners">
                                                        <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                            <div class="thumb">
                                                            <img src="<?php echo $sdata['sliderdata'][1]['image']==''?'./global/img/placeholder_1.png':'./global/img/banners/'.$sdata['sliderdata'][1]['image']; ?>" class="img-responsive" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 p-l-md">
                                                            <input type="text" name="sld-2-t" class="form-control input-sm" placeholder="<?php echo SCTEXT('enter title for this image')?>" value="<?php echo $sdata['sliderdata'][1]['title'] ?>" />
                                                            <hr class="m-b-xs m-t-xs">
                                                            <textarea class="form-control" name="sld-2-d" placeholder="<?php echo SCTEXT('enter some description')?>. . ."><?php echo $sdata['sliderdata'][1]['desc'] ?></textarea>
                                                            <hr>
                                                            <input id="sld-2-img" class="sld-file hidden" name="sld-2-img" type="file">
                                                            <label for="sld-2-img" class="label label-md label-primary pointer"><i class="fa fa-lg fa-upload"></i> &nbsp;&nbsp;<?php echo SCTEXT('Change Image')?></label>
                                                            <span class="help-block m-b-0"></span>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    
                                                    
                                                    <div class="col-md-12 sld-banners">
                                                        <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                            <div class="thumb">
                                                            <img src="<?php echo $sdata['sliderdata'][2]['image']==''?'./global/img/placeholder_1.png':'./global/img/banners/'.$sdata['sliderdata'][2]['image']; ?>" class="img-responsive" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 p-l-md">
                                                            <input type="text" name="sld-3-t" class="form-control input-sm" placeholder="<?php echo SCTEXT('enter title for this image')?>" value="<?php echo $sdata['sliderdata'][2]['title'] ?>" />
                                                            <hr class="m-b-xs m-t-xs">
                                                            <textarea class="form-control" name="sld-3-d" placeholder="<?php echo SCTEXT('enter some description')?>. . ."><?php echo $sdata['sliderdata'][2]['desc'] ?></textarea>
                                                            <hr>
                                                            <input id="sld-3-img" class="sld-file hidden" name="sld-3-img" type="file">
                                                            <label for="sld-3-img" class="label label-md label-primary pointer"><i class="fa fa-lg fa-upload"></i> &nbsp;&nbsp;<?php echo SCTEXT('Change Image')?></label>
                                                            <span class="help-block m-b-0"></span>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    
                                                    
                                                    <div class="col-md-12 sld-banners">
                                                        <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                            <div class="thumb">
                                                            <img src="<?php echo $sdata['sliderdata'][3]['image']==''?'./global/img/placeholder_1.png':'./global/img/banners/'.$sdata['sliderdata'][3]['image']; ?>" class="img-responsive" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 p-l-md">
                                                            <input type="text" name="sld-4-t" class="form-control input-sm" placeholder="<?php echo SCTEXT('enter title for this image')?>" value="<?php echo $sdata['sliderdata'][3]['title'] ?>" />
                                                            <hr class="m-b-xs m-t-xs">
                                                            <textarea class="form-control" name="sld-4-d" placeholder="<?php echo SCTEXT('enter some description')?>. . ."><?php echo $sdata['sliderdata'][3]['desc'] ?></textarea>
                                                            <hr>
                                                            <input id="sld-4-img" class="sld-file hidden" name="sld-4-img" type="file">
                                                            <label for="sld-4-img" class="label label-md label-primary pointer"><i class="fa fa-lg fa-upload"></i> &nbsp;&nbsp;<?php echo SCTEXT('Change Image')?></label>
                                                            <span class="help-block m-b-0"></span>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    
                                                </div>
                                            </div>
                                            
                                            
                                            
                                        </div>
                                        
                                        
                                        
                                        <div class="clearfix"></div>
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