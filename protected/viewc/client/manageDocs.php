<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Manage Documents')?><small><?php echo SCTEXT('manage invoices, agreements and other files')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   
                                    <div class="clearfix sepH_b">
                                        <div class="btn-group pull-right">
                                            
                                            <a href="<?php echo $data['baseurl'] ?>addNewDocument" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Upload New Document')?></a> 
                                            
                                        </div>
                                         
                                    </div><br />
                                    
                                    
                                    <ul class="nav nav-pills clearfix m-b-md" role="tablist">
                                        <li role="presentation" class="active"><a data-toggle="tab" aria-controls="invoices-ctr" role="tab" class="docnav" href="#invoices-ctr"><i class="fa fa-lg fa-folder-open m-r-xs"></i><?php echo SCTEXT('Invoices')?></a></li>
                                        <li role="presentation" class=""><a data-toggle="tab" aria-controls="agreements-ctr" role="tab" class="docnav" href="#agreements-ctr"><i class="fa fa-lg fa-folder m-r-xs"></i><?php echo SCTEXT('Agreements')?></a></li>
                                        <li role="presentation" class=""><a data-toggle="tab" aria-controls="others-ctr" role="tab" class="docnav" href="#others-ctr"><i class="fa fa-lg fa-folder m-r-xs"></i><?php echo SCTEXT('Other documents')?></a></li>
                                        <li role="presentation" class="dropdown pull-right">
                                            <div id="docmgrdp" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; font-size:14px;">
                                                <i class="fa fa-lg fa-calendar m-r-xs"></i><span>Select Date</span>&nbsp;<b class="caret"></b>
                                            </div>
                                        </li>
                                    </ul>
                                    <hr>
                                    <div class="tab-content">
                                        <div class="tab-pane active clearfix text-center" id="invoices-ctr" role="tabpanel">
                                            <div id="inv-file-ctr" class="col-md-12">
                                                 <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">
                                            </div>
                                            
                                            
                                            <div class="media-group-item" style="text-align:center;">
                                            <button id="more_docinv" type="button" class="btn btn-outline btn-xs btn-info hidden"><?php echo SCTEXT('Show More')?> ...</button>
                                            </div>
                                            
                                        </div>

                                        
                                        <div class="tab-pane fade clearfix text-center" id="agreements-ctr" role="tabpanel">
                                            <div id="apl-file-ctr" class="col-md-12">
                                                 <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">
                                            </div>
                                            
                                            
                                            <div class="media-group-item" style="text-align:center;">
                                            <button id="more_docapl" type="button" class="btn btn-outline btn-xs btn-info hidden"><?php echo SCTEXT('Show More')?> ...</button>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade clearfix text-center" id="others-ctr" role="tabpanel">
                                            <div id="oth-file-ctr" class="col-md-12">
                                                 <img style="margin:0 40%;" src="<?php echo Doo::conf()->APP_URL ?>global/img/ajax_loader.gif" alt="">
                                            </div>
                                            
                                            
                                            <div class="media-group-item" style="text-align:center;">
                                            <button id="more_docoth" type="button" class="btn btn-outline btn-xs btn-info hidden"><?php echo SCTEXT('Show More')?> ...</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>           