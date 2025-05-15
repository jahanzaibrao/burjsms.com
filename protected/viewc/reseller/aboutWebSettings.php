
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('About page Settings')?><small><?php echo SCTEXT('write content about your company and services')?> </small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <?php
    $sdata = unserialize(base64_decode($data['pdata']->page_data));
    ?>
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="set_form">
                                        <input type="hidden" name="page" value="ABOUT" />
                                        
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Title')?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="pgtitle" id="pgtitle" placeholder="<?php echo SCTEXT('enter title for the about page')?> . . ." value="<?php echo $sdata['title'] ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Meta Description')?>:</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="metadesc" placeholder="<?php echo SCTEXT('enter description of your business')?> . . ."><?php echo $sdata['metadesc'] ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Content')?>:</label>
                                                <div class="col-md-8">
                                                     <span class="help-block"><?php echo SCTEXT("Describe your company in brief here. You can use editor's tools to create tables, paragraphs and headers. Describe your organisation's vision, mission statement and you can also introduce your team here.")?></span>
                                                    <textarea name="content" class="m-0" data-plugin="summernote" data-options="{height: 250}"><?php echo $sdata['content']==''?SCTEXT('Enter content for About page').' . . .':$sdata['content']; ?></textarea>
                                                   
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