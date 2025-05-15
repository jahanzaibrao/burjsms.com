
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Front-end Themes')?><small><?php echo SCTEXT('select a template to be applied on your white-label website')?> </small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                
                                <!-- start content -->
                                
                                <?php 
    if($data['tdata']->id){
        $theme = unserialize($data['tdata']->skin_data);
    }
    ?>
                                
                                    <form class="form-horizontal" action="" method="post" id="thm_form">
                                        <input type="hidden" id="cname" name="cname" value="<?php echo $theme['color'] ?>"/>
                                        <input type="hidden" id="ccode" name="ccode" value="<?php echo $theme['code'] ?>"/>
                                        <input type="hidden" id="tname" name="tname" value="<?php echo $theme['name'] ?>"/>
                                        <div class="col-md-12">
                                            <div class="col-md-6 p-r-sm">
                                            <div class="panel panel-<?php echo $theme['name']=='default'?'success':'inverse'; ?>">
                                                <div class="panel-heading clearfix">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Default Theme')?>
                                                    <?php if($theme['name']=='default'){ ?>
                                                        <span class="label pull-right label-lg label-flat"><i class="fa fa-lg fa-check-circle"></i> &nbsp;<?php echo SCTEXT('Currently Applied')?></span>
                                                        <?php } ?>
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                        <div class="thumb">
                                                            <img src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/screen.png" class="img-responsive" />
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 p-l-md">
                                                        <ul class="todo-list">
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-info-circle text-info"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Slider image recommended size')?> 1920x500</span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Mobile/Tablet Compatible')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('SEO Friendly')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Homepage Slider option')?></span>
                                                            </li>
                                                            
                                                            
                                                        </ul>
                                                        
                                                        <hr>
                                                        <label><?php echo SCTEXT('Color choices')?>:</label>
                                                        <div class="skin-color-opts clearfix">
                                                            <ul class="choose-color">
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#ff6850" data-code="orange" class="color"><?php if($theme['name']=='default' && $theme['color']=='orange'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#1abc9c" data-code="cyan" class="color"><?php if($theme['name']=='default' && $theme['color']=='cyan'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#ec6197" data-code="pink" class="color"><?php if($theme['name']=='default' && $theme['color']=='pink'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#89b424" data-code="green" class="color"><?php if($theme['name']=='default' && $theme['color']=='green'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#db0000" data-code="red" class="color"><?php if($theme['name']=='default' && $theme['color']=='red'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#717171" data-code="gray" class="color"><?php if($theme['name']=='default' && $theme['color']=='gray'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#988ed8" data-code="purple" class="color"><?php if($theme['name']=='default' && $theme['color']=='purple'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                
                                                            </ul>
                                                        </div>
                                                        <hr>
                                                        <?php if($theme['name']=='default'){ ?>
                                                        <button data-theme="default" class="btn btn-primary" id="" type="button"><i class="fa fa-lg fa-check-circle"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes')?></button>
                                                        <?php }else{ ?>
                                                        <button data-theme="default" class="btn btn-primary" id="" type="button"><?php echo SCTEXT('Apply this theme')?></button>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                            
                                            
                                            <div class="col-md-6">
                                            <div class="panel panel-<?php echo $theme['name']=='business'?'success':'inverse'; ?>">
                                                <div class="panel-heading clearfix">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Business')?>
                                                     <?php if($theme['name']=='business'){ ?>
                                                        <span class="label pull-right label-lg label-flat"><i class="fa fa-lg fa-check-circle"></i> &nbsp;<?php echo SCTEXT('Currently Applied')?></span>
                                                        <?php } ?>
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                        <div class="thumb">
                                                            <img src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/screen.png" class="img-responsive" />
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 p-l-md">
                                                        <ul class="todo-list">
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-info-circle text-info"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Slider image recommended size')?> 1920x750</span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Mobile/Tablet Compatible')?></span>
                                                            </li>
                                                            
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Homepage Slider option')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Pricing tables')?></span>
                                                            </li>
                                                            
                                                        </ul>
                                                        
                                                        <hr>
                                                        <label><?php echo SCTEXT('Color choices')?>:</label>
                                                        <div class="skin-color-opts clearfix">
                                                            <ul class="choose-color">
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#289ccb" data-code="blue" class="color"><?php if($theme['name']=='business' && $theme['color']=='blue'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#66b476" data-code="green" class="color"><?php if($theme['name']=='business' && $theme['color']=='green'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#ecaa5d" data-code="orange" class="color"><?php if($theme['name']=='business' && $theme['color']=='orange'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                            </ul>
                                                        </div>
                                                        <hr>
                                                         <?php if($theme['name']=='business'){ ?>
                                                        <button data-theme="business" class="btn btn-primary" id="" type="button"><i class="fa fa-lg fa-check-circle"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes')?></button>
                                                        <?php }else{ ?>
                                                        <button data-theme="business" class="btn btn-primary" id="" type="button"><?php echo SCTEXT('Apply this theme')?></button>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        
                                        
                                        <div class="col-md-12">
                                            <div class="col-md-6 p-r-sm">
                                            <div class="panel panel-<?php echo $theme['name']=='corporate'?'success':'inverse'; ?>">
                                                <div class="panel-heading clearfix">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Corporate')?>
                                                     <?php if($theme['name']=='corporate'){ ?>
                                                        <span class="label pull-right label-lg label-flat"><i class="fa fa-lg fa-check-circle"></i> &nbsp;<?php echo SCTEXT('Currently Applied')?></span>
                                                        <?php } ?>
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                        <div class="thumb">
                                                            <img src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/screen.png" class="img-responsive" />
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 p-l-md">
                                                        <ul class="todo-list">
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-info-circle text-info"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Slider image recommended size')?> 1600x460</span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Mobile/Tablet Compatible')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('SEO Friendly')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Homepage Slider option')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Pricing tables')?></span>
                                                            </li>
                                                            
                                                        </ul>
                                                        
                                                        <hr>
                                                        <label><?php echo SCTEXT('Color choices')?>:</label>
                                                        <div class="skin-color-opts clearfix">
                                                            <ul class="choose-color">
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#e91b23" data-code="red" class="color"><?php if($theme['name']=='corporate' && $theme['color']=='red'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#7fc719" data-code="green" class="color"><?php if($theme['name']=='corporate' && $theme['color']=='green'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#1bb1e9" data-code="blue" class="color"><?php if($theme['name']=='corporate' && $theme['color']=='blue'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#fd7c26" data-code="orange" class="color"><?php if($theme['name']=='corporate' && $theme['color']=='orange'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#fd7c26" data-code="cyan" class="color"><?php if($theme['name']=='corporate' && $theme['color']=='cyan'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#fc4349" data-code="light_pink" class="color"><?php if($theme['name']=='corporate' && $theme['color']=='light_pink'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                            </ul>
                                                        </div>
                                                        <hr>
                                                        <?php if($theme['name']=='corporate'){ ?>
                                                        <button data-theme="corporate" class="btn btn-primary" id="" type="button"><i class="fa fa-lg fa-check-circle"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes')?></button>
                                                        <?php }else{ ?>
                                                        <button data-theme="corporate" class="btn btn-primary" id="" type="button"><?php echo SCTEXT('Apply this theme')?></button>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                            
                                            
                                            <div class="col-md-6">
                                            <div class="panel panel-<?php echo $theme['name']=='modern'?'success':'inverse'; ?>">
                                                <div class="panel-heading clearfix">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Modern')?>
                                                     <?php if($theme['name']=='modern'){ ?>
                                                        <span class="label pull-right label-lg label-flat"><i class="fa fa-lg fa-check-circle"></i> &nbsp;<?php echo SCTEXT('Currently Applied')?></span>
                                                        <?php } ?>
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                        <div class="thumb">
                                                            <img src="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/screen.png" class="img-responsive" />
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 p-l-md">
                                                        <ul class="todo-list">
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-info-circle text-info"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Slider image recommended size')?> 1400x600</span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Mobile/Tablet Compatible')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('SEO Friendly')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Homepage Slider option')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Pricing tables')?></span>
                                                            </li>
                                                            
                                                        </ul>
                                                        
                                                        <hr>
                                                        <label><?php echo SCTEXT('Color choices')?>:</label>
                                                        <div class="skin-color-opts clearfix">
                                                            <ul class="choose-color">
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#fcbf31" data-code="yellow" class="color"><?php if($theme['name']=='modern' && $theme['color']=='yellow'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#f7921e" data-code="orange" class="color"><?php if($theme['name']=='modern' && $theme['color']=='orange'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#1894eb" data-code="blue" class="color"><?php if($theme['name']=='modern' && $theme['color']=='blue'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#22cb22" data-code="green" class="color"><?php if($theme['name']=='modern' && $theme['color']=='green'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#52cebe" data-code="cyan" class="color"><?php if($theme['name']=='modern' && $theme['color']=='cyan'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                            </ul>
                                                        </div>
                                                        <hr>
                                                        <?php if($theme['name']=='modern'){ ?>
                                                        <button data-theme="modern" class="btn btn-primary" id="" type="button"><i class="fa fa-lg fa-check-circle"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes')?></button>
                                                        <?php }else{ ?>
                                                        <button data-theme="modern" class="btn btn-primary" id="" type="button"><?php echo SCTEXT('Apply this theme')?></button>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        
                                        
                                        <div class="col-md-12">
                                            <div class="col-md-6 p-r-sm">
                                            <div class="panel panel-<?php echo $theme['name']=='creative'?'success':'inverse'; ?>">
                                                <div class="panel-heading clearfix">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Creative')?>
                                                     <?php if($theme['name']=='creative'){ ?>
                                                        <span class="label pull-right label-lg label-flat"><i class="fa fa-lg fa-check-circle"></i> &nbsp;<?php echo SCTEXT('Currently Applied')?></span>
                                                        <?php } ?>
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                        <div class="thumb">
                                                            <img src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/screen.png" class="img-responsive" />
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 p-l-md">
                                                        <ul class="todo-list">
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-info-circle text-info"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Slider image recommended size')?> 728x453. <?php echo SCTEXT('Make sure all images have same height.')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Mobile/Tablet Compatible')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('SEO Friendly')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Homepage Slider option')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Pricing tables')?></span>
                                                            </li>
                                                            
                                                        </ul>
                                                        
                                                        <hr>
                                                        <label><?php echo SCTEXT('Color choices')?>:</label>
                                                        <div class="skin-color-opts clearfix">
                                                            <ul class="choose-color">
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#ff6131" data-code="orange" class="color "><?php if($theme['name']=='creative' && $theme['color']=='orange'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#16b6ea" data-code="blue" class="color"><?php if($theme['name']=='creative' && $theme['color']=='blue'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#27ae61" data-code="green" class="color"><?php if($theme['name']=='creative' && $theme['color']=='green'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#e74c3c" data-code="red" class="color"><?php if($theme['name']=='creative' && $theme['color']=='red'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#0ee6f9" data-code="l-blue" class="color"><?php if($theme['name']=='creative' && $theme['color']=='l-blue'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#ffb400" data-code="yellow" class="color"><?php if($theme['name']=='creative' && $theme['color']=='yellow'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#a0ce4e" data-code="l-green" class="color"><?php if($theme['name']=='creative' && $theme['color']=='l-green'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#c2a26f" data-code="sandal" class="color"><?php if($theme['name']=='creative' && $theme['color']=='sandal'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                            </ul>
                                                        </div>
                                                        <hr>
                                                        <?php if($theme['name']=='creative'){ ?>
                                                        <button data-theme="creative" class="btn btn-primary" id="" type="button"><i class="fa fa-lg fa-check-circle"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes')?></button>
                                                        <?php }else{ ?>
                                                        <button data-theme="creative" class="btn btn-primary" id="" type="button"><?php echo SCTEXT('Apply this theme')?></button>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                            
                                            
                                            <div class="col-md-6">
                                            <div class="panel panel-<?php echo $theme['name']=='classical'?'success':'inverse'; ?>">
                                                <div class="panel-heading clearfix">
                                                    <h4 class="panel-title"><?php echo SCTEXT('Classical')?>
                                                     <?php if($theme['name']=='classical'){ ?>
                                                        <span class="label pull-right label-lg label-flat"><i class="fa fa-lg fa-check-circle"></i> &nbsp;<?php echo SCTEXT('Currently Applied')?></span>
                                                        <?php } ?>
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-6 col-sm-6 gallery-item p-r-sm">
                                                        <div class="thumb">
                                                            <img src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/screen.png" class="img-responsive" />
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 p-l-md">
                                                        <ul class="todo-list">
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Mobile/Tablet Compatible')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('SEO Friendly')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Homepage Slider option')?></span>
                                                            </li>
                                                            <li class="todo-item">
                                                                <i class="fa fa-lg fa-check-circle text-success"></i>&nbsp;&nbsp;<span><?php echo SCTEXT('Pricing tables')?></span>
                                                            </li>
                                                            
                                                        </ul>
                                                        
                                                        <hr>
                                                        <label><?php echo SCTEXT('Color choices')?>:</label>
                                                        <div class="skin-color-opts clearfix">
                                                            <ul class="choose-color">
                                                                
                                                                <li><a href="javascript:void(0);" data-color="#f15a23" data-code="orange" class="color"><?php if($theme['name']=='classical'){ ?><i class="fa fa-lg fa-check fa-inverse"></i><?php } ?></a></li>
                                                                
                                                            </ul>
                                                        </div>
                                                        <hr>
                                                        <?php if($theme['name']=='classical'){ ?>
                                                        <button data-theme="classical" class="btn btn-primary" id="" type="button"><i class="fa fa-lg fa-check-circle"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes')?></button>
                                                        <?php }else{ ?>
                                                        <button data-theme="classical" class="btn btn-primary" id="" type="button"><?php echo SCTEXT('Apply this theme')?></button>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                     </form>   
                                        
                                <!-- end content -->    
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>