<?php include('header.php') ?>
		<div id="content" style="padding-bottom:0px;">
		
		<!-- /// CONTENT  /////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php if($pdata['sliderflag']=='1'){ ?>
			<div class="fullwidthbanner-container">
            	<div class="fullwidthbanner">
                	
                    <ul>
                         <?php foreach($pdata['sliderdata'] as $slide){ ?>
                        
                    	<li  data-transition="fade">
                        	
                            <img src="<?php echo './global/img/banners/'.$slide['image'] ?>" alt="">
                            
                           
                            <div style="word-wrap: break-word !important;" class="caption text alt sfl" 
                                data-x="30"  
                                data-y="180" 
                                data-speed="700" 
                                data-start="1200" 
                                data-easing="easeOutBack">
                                <i class="fa fa-3x fa-desktop"></i>
                                <strong><?php echo $slide['title']; ?></strong> <br>
                                <?php echo $slide['desc']; ?> 
                                
                            </div>
                            
                            
                            
                           
                            
                        </li>
                         <?php } ?>
                    </ul>
                    
                </div><!-- end .fullwidthbanner -->
            </div><!-- end .fullwidthbanner-container -->
            
<?php } ?>             
            <div class="row" style="margin-top:-40px;">
                <div class="span12">
                        <?php echo htmlspecialchars_decode($pdata['content']); ?>
                    	
                    </div>
            </div><!-- end .row -->
            
            
            <?php
        if($pdata['twgflag']=='1'){
        
        ?>
            <div class="fullwidth-section" >
            	
                <div class="fullwidth-section-content">
                	
                    <div class="row">
                    	<div class="span12">
                        	<div id="tgw_msg" style="display:inline-block;padding-left:10px;">
                                            
                                    </div>
                            <div class="callout-box">
                            	
                                <div class="row">
                                    
                                	<div class="span9">
                                    	
                                        <h2><?php echo $pdata['twgdata']['title'] ?></h2>
                                        <p style="position: relative;">
                                            <i style="position: absolute;top: 9px;color: <?php echo $data['skin']['code'] ?>;left: 10px;" class="fa fa-2x fa-phone"></i>
                                            <input id="tgw_contact" type="text" placeholder="<?php echo SCTEXT('enter mobile number with prefix')?> . . ." class="span12" style="padding:8px 8px 8px 45px;">
                                        </p>
                                        
                                     </div><!-- end .span9 -->
                                     <div class="span3 text-center">
                                     	
                                        <a id="submit_tgw" class="btn btn-large" href="javascript:void(0);"><?php echo SCTEXT('Send SMS')?></a>
                                        
                                     </div><!-- end .span3 -->
                                </div><!-- end .row -->
                                
                            </div><!-- end .callout-box -->
                            
                        </div><!-- end .span12 -->
                    </div><!-- end .row -->
                    
                </div><!-- end .fullwidth-section-content -->
                
            </div><!-- end .fullwidth-section -->
           
                <?php } ?>
		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #content -->
		
<?php include('footer.php') ?>