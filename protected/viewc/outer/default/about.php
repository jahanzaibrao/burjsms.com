   <?php include('header.php') ?>

<div id="main-content">
        
        <section class=" flx-divider">
        
        	<div class="wrapper flx-line">
            
            	<div class="row-fluid">
                
                    <div class="span12 clearfix">
                    
                        <p class="flx-team-thumb"></p>
                        
                        <div class="flx-intro-content">
                        
                            <h2><?php echo SCTEXT('About us')?></h2>
                            <p><?php echo SCTEXT('All about our company and our values.')?></p>
                            
                        </div><!--end:flx-intro-content-->
                    
                    </div><!--end:span12-->
                
                </div><!--end:row-fluid-->
            
            </div><!--end:wrapper-->
            
        </section><!--end:flx-intro-->
        
        <div class="breadcrumb flx-divider">
        
        	<div class="wrapper flx-line">
            
            	<div class="row-fluid">
                
                	<div class="span12 clearfix">
                    	<a href="<?php echo Doo::conf()->APP_URL ?>">Home</a>
                        <span>&nbsp;&nbsp;/&nbsp;&nbsp;</span>
                        <span><?php echo SCTEXT('About Us')?></span>
                    </div><!--end:span12-->
                
                </div><!--end:row-fluid-->
            
            </div><!--end:wrapper-->
        
        </div><!--end:breadcrumb-->
        
        <!--end:flx-divider-->
        
        <section class="">
        
        	
            
            <div class="wrapper">
            
            	<div class="row-fluid">
                
                	<div class="span12">
                    
                    	<br>
                        <?php echo htmlspecialchars_decode($pdata['content']); ?>
                    </div><!--end:span12-->
                    
                </div><!--end:row-fluid-->
                
            </div><!--end:wrapper-->
        
        </section>
        
        <!--end:widget-->
        
    </div>

   <?php include('footer.php') ?>