
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Contact page Settings')?><small><?php echo SCTEXT('provide your office location and contact details')?> </small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <?php
    $sdata = unserialize(base64_decode($data['pdata']->page_data));
    ?>
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="set_form">
                                        <input type="hidden" name="page" value="CONTACT" />
                                        
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Page Title')?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="pgtitle" id="pgtitle" placeholder="<?php echo SCTEXT('enter title for the contact page')?> . . ." value="<?php echo $sdata['title'] ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Meta Description')?>:</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="metadesc" placeholder="<?php echo SCTEXT('describe your company and location e.g MyCompany London, UK etc.')?>"><?php echo $sdata['metadesc'] ?></textarea>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Address')?>:</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="address" placeholder="<?php echo SCTEXT('enter your complete business address')?>. . . "><?php echo $sdata['address'] ?></textarea>
                                                </div>
                                        </div>
                                        
                                        <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Email queries to')?>:</label>
                                                <div class="col-md-8">
                                                    <input id="qmail" class="form-control pop-over" data-placement="top" data-trigger="hover" data-content="<?php echo SCTEXT('There will be a contact form on this page. Enter the email which should receive all the enquiries via contact form')?>" name="qmail" placeholder="<?php echo SCTEXT('enter email here')?> . . ." value="<?php echo $sdata['qmail'] ?>" />
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