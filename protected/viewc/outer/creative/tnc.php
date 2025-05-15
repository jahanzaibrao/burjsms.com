<?php include('header.php') ?>


<div id="terms" class="page-desc-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 page-desc">
				<h2 style="padding-left:45px;" class="visible" ><?php echo SCTEXT('Terms & Conditions')?></h2>
				<p style="padding-left:45px;" class=" visible"><?php echo SCTEXT('Please read our terms of service.')?></p>
			</div>
		</div>
	</div>
</div>

<section class="work-area section">
	<div class="container">
		<div class="row">
                <?php echo htmlspecialchars_decode($data['content']); ?>
        </div>
           
	</div>
</section>




<!-- Work Area Ends -->
<?php include('footer.php') ?>