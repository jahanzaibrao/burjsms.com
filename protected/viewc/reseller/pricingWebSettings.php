
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Pricing page Settings')?><small><?php echo SCTEXT('showcase your pricing details for your sms services')?> </small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <?php
    $sdata = unserialize(base64_decode($data['pdata']->page_data));
    ?>
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="set_form">
                                        <input type="hidden" name="page" value="PRICING" />
                                        
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Title')?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="pgtitle" id="pgtitle" placeholder="<?php echo SCTEXT('enter title for the pricing page')?> . . ." value="<?php echo $sdata['title'] ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Meta Description')?>:</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="metadesc" placeholder="<?php echo SCTEXT('describe your services')?> . . ."><?php echo $sdata['metadesc'] ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Content')?>:</label>
                                                <div class="col-md-8">
                                                     <span class="help-block"><?php echo SCTEXT('You can display SMS pricing here. Use tables to draw your pricing data.')?> <?php if($_SESSION['user']['group']=='admin'){ ?> <?php echo SCTEXT('You could also show SMS plans here, simply use the code <b>[PLANID=2]</b>, where 2 is the ID of the Plan.')?> <?php } ?></span>
                                                    <textarea name="content" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $sdata['content']==''?SCTEXT('Enter content for pricing page').' . . .':$sdata['content']; ?></textarea>
                                                   
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