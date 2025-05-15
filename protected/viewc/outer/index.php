<?php include('header.php') ?>
<style>
.add-on{
display: inline-block;
padding: 1px 4px 4px 4px;
border: 1px solid #e8e8e8;
font-size: 16px;
font-weight: bold;
line-height: 34px;
margin-right: -4px;
	}
.tgwbox {
	width:40%;
	float:right;
	}	
#mobile{
	width:135px;
	background-color:#fff;
	padding:4px 10px;
	}	
@media only screen and (min-width: 480px) and (max-width: 767px) {
	.tgwbox {
	width:100%;
	float:right;
	}
}

@media only screen and (max-width: 479px) {
	.tgwbox {
	width:100%;
	float:right;
	}
}
</style>
<section id="content" class="container clearfix">

	<h2 class="slogan align-center"><?php echo $data['content']->page_header ?></h2>
    
<?php if($data['content']->banner_flag=='1'){ ?>
    
<?php if(empty($data['banner'])){ ?>
	<section id="features-slider" class="ss-slider">
	
		<article class="slide">
		
			<img src="<?php echo $data['baseurl'] ?>global/white/img/placeholders/slider-slide-1.jpg" alt="" class="slide-bg-image" />
			
			<div class="slide-button">
				<span class="dropcap">1</span>
				<h5>Slide#1</h5>
				<span class="description">Tagline for slide#1</span>
			</div>
		
			<div class="slide-content">
				<h2>Slide Title</h2>
				<p>A brief text about the slide.</p>
			</div>
			
		</article><!-- end .slide (Responsive Layout) -->
	
		<article class="slide">
		
			<img src="<?php echo $data['baseurl'] ?>global/white/img/placeholders/slider-slide-2.jpg" alt="" class="slide-bg-image" />
		
			<div class="slide-button">
				<span class="dropcap">2</span>
				<h5>Slide#2</h5>
				<span class="description">Tagline for slide#2</span>
			</div>
		
			<div class="slide-content">
				<h2>Slide Title</h2>
				<p>A brief text about the slide.</p>
			</div>
			
		</article><!-- end .slide (HTML5 / CSS3) -->
	
		<article class="slide">
		
			<img src="<?php echo $data['baseurl'] ?>global/white/img/placeholders/slider-slide-3.jpg" alt="" class="slide-bg-image" />
		
			<div class="slide-button">
				<span class="dropcap">3</span>
				<h5>Slide#3</h5>
				<span class="description">Tagline for slide#3</span>
			</div>
		
			<div class="slide-content">
				<h2>Slide Title</h2>
				<p>A brief text about the slide.</p>
			</div>
			
		</article><!-- end .slide (Easily Customisable) -->
	
		<article class="slide">
		
			<img src="<?php echo $data['baseurl'] ?>global/white/img/placeholders/slider-slide-4.jpg" alt="" class="slide-bg-image" />
		
			<div class="slide-button">
				<span class="dropcap">4</span>
				<h5>Slide#4</h5>
				<span class="description">Tagline for slide#4</span>
			</div>
		
			<div class="slide-content">
				<h2>Slide Title</h2>
				<p>A brief text about the slide.</p>
			</div>
			
		</article><!-- end .slide (Unique & Clean) -->
		
	</section><!-- end #features-slider -->
<?php }else{
	// prepare the dynamic banner
	 ?>
     <section id="features-slider" class="ss-slider">
	
		<article class="slide">
		
			<img src="<?php echo $data['banner'][0]->image_path ?>" alt="" class="slide-bg-image" />
			
			<div class="slide-button">
				<span class="dropcap">1</span>
				<h5><?php echo $data['banner'][0]->title ?></h5>
				<span class="description"><?php echo $data['banner'][0]->tagline ?></span>
			</div>
		
			<div class="slide-content">
				<h2><?php echo $data['banner'][0]->title ?></h2>
				<p><?php echo $data['banner'][0]->box_desc ?></p>
			</div>
			
		</article>
        
        
        	<article class="slide">
		
			<img src="<?php echo $data['banner'][1]->image_path ?>" alt="" class="slide-bg-image" />
			
			<div class="slide-button">
				<span class="dropcap">2</span>
				<h5><?php echo $data['banner'][1]->title ?></h5>
				<span class="description"><?php echo $data['banner'][1]->tagline ?></span>
			</div>
		
			<div class="slide-content">
				<h2><?php echo $data['banner'][1]->title ?></h2>
				<p><?php echo $data['banner'][1]->box_desc ?></p>
			</div>
			
		</article>
        
        
        	<article class="slide">
		
			<img src="<?php echo $data['banner'][2]->image_path ?>" alt="" class="slide-bg-image" />
			
			<div class="slide-button">
				<span class="dropcap">3</span>
				<h5><?php echo $data['banner'][2]->title ?></h5>
				<span class="description"><?php echo $data['banner'][2]->tagline ?></span>
			</div>
		
			<div class="slide-content">
				<h2><?php echo $data['banner'][2]->title ?></h2>
				<p><?php echo $data['banner'][2]->box_desc ?></p>
			</div>
			
		</article>
        
        	<article class="slide">
		
			<img src="<?php echo $data['banner'][3]->image_path ?>" alt="" class="slide-bg-image" />
			
			<div class="slide-button">
				<span class="dropcap">4</span>
				<h5><?php echo $data['banner'][3]->title ?></h5>
				<span class="description"><?php echo $data['banner'][3]->tagline ?></span>
			</div>
		
			<div class="slide-content">
				<h2><?php echo $data['banner'][3]->title ?></h2>
				<p><?php echo $data['banner'][3]->box_desc ?></p>
			</div>
			
		</article>
	
	
		
	</section>
     
     <?php } } ?>
  
<?php   //test our gateway
if($data['gtset']->display_flag=='1'){
 ?>   
<div class="tgwbox" align="center">
<h4><?php echo $data['gtset']->w_title ?></h4>
<div class="infobox">

			<h6><?php echo $data['gtset']->w_desc ?></h6>
            
            	<span class="add-on"><?php echo $data['gtset']->prefix ?></span>
                <input type="text" name="mobile" id="mobile" placeholder="Enter mobile number.." maxlength="10">
            
			<span class="add-on"><button type="button" class="button" id="tgbtn">Send SMS</button></span>

		</div>
</div>
<?php } ?>

	<p>
    <?php echo $data['content']->page_content ?>
    
    </p>
    
<div style="clear:right"></div>
</section><!-- end #content -->

<?php include('footer.php') ?>
<script>
$(document).ready(function(){
	
$("#tgbtn").click(function(){
	$ele = $(this);
	$ele.attr('disabled','disabled').text('Sending....');
	
	$.ajax({
		url: app_url+'sendTestSMS',
		type: 'post',
		data: {mobile:$('#mobile').val()},
		success: function(res){
			if($.trim(res)=='DONE'){
				$(".rmsg").hide();
				$(".infobox").prepend('<p class="success rmsg" align="left"><strong>SMS Sent Successfully!</strong></p>');
				$("#mobile").val('');
				$ele.attr('disabled',false).text('Send SMS');
				}else{
					$(".rmsg").hide();
					$(".infobox").prepend('<p class="error rmsg" align="left"><strong>'+res+'</strong></p>');
				$ele.attr('disabled',false).text('Send SMS');
					}
			}
		});
	});
	
	});
</script>