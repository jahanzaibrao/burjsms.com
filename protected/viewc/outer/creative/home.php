<?php include('header.php') ?>


<?php if($pdata['sliderflag']=='1'){ ?>

<!-- Top Slider Begins -->
<section class="top-slider">
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		
		<!-- Wrapper for slides -->
		<div class="container">
			<div class="carousel-inner">
                
                <?php
                                    $i = 0;
                                    foreach($pdata['sliderdata'] as $slide){ 
                ?>
                        
                
				<div class="item <?php if($i==0){echo 'active';} ?>">
					<div class="row">
						<div class="carousel-caption">
							<h1><?php echo $slide['title']; ?></h1>
							<p><?php echo $slide['desc']; ?></p>
							
						</div>
						<div class="col-md-8 col-md-offset-4"> <img src="<?php echo './global/img/banners/'.$slide['image'] ?>" class="img-responsive"  alt="slider-img"> </div>
					</div>
				</div>
				
                
                <?php $i++; } ?>
                
				
			</div>
		</div>
	</div>
	<!-- Controls -->
	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <i class="fa fa-angle-left"></i> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <i class="fa fa-angle-right"></i> </a> </section>

<?php } ?>


<!-- Top Slider Ends -->
<!-- Work Area Begins -->
<?php
        if($pdata['twgflag']=='1'){
        
        ?>
<section class="work-area section">
	<div class="container">
		<div class="row">
            <div class="col-sm-8">
                <?php echo htmlspecialchars_decode($pdata['content']); ?>
            </div>
			<div class="col-sm-4">
                <div id="tgw_msg">
                
                </div>
                
				<div class="largewb work-box">
					<!-- Title -->
                    <i class="flaticon-black164"></i>
					<h3 style="margin-top:0px;"><?php echo $pdata['twgdata']['title'] ?></h3>
					
					<div class="input-group form-group">
                        <span class="input-group-addon"><span class="flaticon-phone46"></span></span>
									<input type="text" name="tgw_contact" id="tgw_contact" class=" form-control cooltext input-phone-number" placeholder="<?php echo SCTEXT('enter mobile number with prefix')?> . . .">
                    </div>
					<input type="submit" id="submit_tgw" class="btn btn-default" value="<?php echo SCTEXT('Send SMS')?>">
					
			</div>
			
		</div>
	</div>
    </div>
</section>
    
    <?php } else{ ?>
    
<section class="work-area section">
	<div class="container">
		<div class="row">
                <?php echo htmlspecialchars_decode($pdata['content']); ?>
        </div>
           
	</div>
</section>
  
    
    <?php } ?>

<!-- Work Area Ends -->
<?php include('footer.php') ?>