<?php include('header.php') ?>

<section id="content" class="container clearfix">

<?php if($pdata['sliderflag']=='1'){ ?>
	<section id="features-slider" class="ss-slider" style="margin-bottom:30px !important;">
	
        <?php 
                                    $i = 1;
                                    foreach($pdata['sliderdata'] as $slide){ ?>
		<article class="slide">
		
			<img src="<?php echo './global/img/banners/'.$slide['image'] ?>" alt="" class="slide-bg-image" />
			
			<div class="slide-button">
				<span class="dropcap"><?php echo $i ?></span>
				<h5><?php echo $slide['title']; ?></h5>
				<span class="description"><?php echo $slide['desc']; ?></span>
			</div>
		
			<div class="slide-content">
				<h2><?php echo $slide['title']; ?></h2>
				<p><?php echo $slide['desc']; ?> </p>
				
			</div>
			
		</article>
	<?php $i++;} ?>
		
		
        
        
	</section><!-- end #features-slider -->
<?php } ?>
	
    
    <?php
        if($pdata['twgflag']=='1'){
        
        ?>
    
<div id="contentbox">
    
    <div style="float:left;width:60%;font-size:12px;">
        <?php echo htmlspecialchars_decode($pdata['content']); ?>
    </div>
        
        <div class="tgwbox" align="center">
            <div id="tgw_msg">
                
            </div>
<h4><?php echo $pdata['twgdata']['title'] ?></h4>
<div class="infobox">

                <input type="text" name="mobile" id="tgw_contact" placeholder="<?php echo SCTEXT('Enter mobile number with prefix')?>.." maxlength="15">
            
			<span class="add-on"><button type="button" class="button" id="submit_tgw"><?php echo SCTEXT('Send SMS') ?></button></span>

		</div>
</div>
    
    <div style="clear:both;"></div>
</div>    
    
    
    <?php }else{ ?> 
    
    <div id="contentbox">
    
    
        <?php echo htmlspecialchars_decode($pdata['content']); ?>
   
    </div> 
    
    <?php } ?>   
    
    
    
</section><!-- end #content -->

<?php include('footer.php') ?>