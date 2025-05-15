<?php include('header.php') ?>


<section id="content" class="container clearfix" style="min-height: 46px;">

	<header class="page-header">

		<h1 class="page-title"><?php echo SCTEXT('About Us') ?></h1>
		
	</header><!-- end .page-header -->

	<div>

		<p>
        
             <?php echo htmlspecialchars_decode($pdata['content']); ?>
        
        </p>
		
	</div><!-- end .one-half -->



	
</section>


<?php include('footer.php') ?>