<?php include('header.php') ?>

<section id="content" class="container clearfix">
<header class="page-header">

		<h1 class="page-title"><?php echo SCTEXT('Pricing') ?></h1>

		<hr />

		<h2 class="page-description"><?php echo $data['content']->page_header ?></h2>
		
	</header>
 <?php $contents = unserialize($data['content']->page_content);
	$pagecontent = $contents['page_content'];
	$ranges = $contents['ranges'];
	$r1_data = $contents['r1_data'];
	$r2_data = $contents['r2_data'];
	$r3_data = $contents['r3_data'];
	 ?>
	<p>
   <?php echo $pagecontent; ?>
    </p>
    
    <?php if(sizeof($ranges)>0){ ?>
    
    <section class="extended-pricing-table col3 featured clearfix">

		<div class="column features-list">

			<div class="header">&nbsp;</div><!-- end .header -->

			<ul class="features">
				<?php foreach($ranges as $rng){ ?>
                <li><?php echo $rng ?> SMS</li>
                <?php } ?>
			</ul><!-- end .features -->

			<div class="footer">&nbsp;</div><!-- end .footer -->
			
		</div><!-- end .column.features-list -->
        
        <?php if($r1_data['label']!=''){ ?>

		<div class="column free">

			<div class="header">
				<h2 class="title"><?php echo $r1_data['label'] ?></h2>
				<h3 class="price"><?php echo SCTEXT('Starts From') ?><span><?php echo Doo::conf()->currency.min($r1_data['prices']) ?></span>per SMS</h3>
			</div><!-- end .header -->

			<ul class="features clearfix">
				<?php foreach($r1_data['prices'] as $pr1){ ?>
                <li><?php echo Doo::conf()->currency.$pr1.' per SMS' ?></li>
                <?php } ?>
			</ul><!-- end .features -->

			
		</div><!-- end .column.free -->

		<?php } ?>
        
        
         
        <?php if($r2_data['label']!=''){ ?>

		<div class="column featured">

			<div class="header">
				<h2 class="title"><?php echo $r2_data['label'] ?></h2>
				<h3 class="price"><?php echo SCTEXT('Starts From') ?><span><?php echo Doo::conf()->currency.min($r2_data['prices']) ?></span>per SMS</h3>
			</div><!-- end .header -->

			<ul class="features clearfix">
				<?php foreach($r2_data['prices'] as $pr2){ ?>
                <li><?php echo Doo::conf()->currency.$pr2.' per SMS' ?></li>
                <?php } ?>
			</ul><!-- end .features -->

			
		</div><!-- end .column.featured -->
        
        
        <?php } ?>
        
        <?php if($r3_data['label']!=''){ ?>

		<div class="column">

			<div class="header">
				<h2 class="title"><?php echo $r3_data['label'] ?></h2>
				<h3 class="price"><?php echo SCTEXT('Starts From') ?><span><?php echo Doo::conf()->currency.min($r3_data['prices']) ?></span>per SMS</h3>
			</div><!-- end .header -->

			<ul class="features clearfix">
            <?php foreach($r3_data['prices'] as $pr3){ ?>
                <li><?php echo Doo::conf()->currency.$pr3.' per SMS' ?></li>
                <?php } ?>
			</ul><!-- end .features -->

			
		</div><!-- end .column -->
        
        <?php } ?>
		
	</section>
    
    <?php } ?>
    
    
</section><!-- end #content -->

<?php include('footer.php') ?>