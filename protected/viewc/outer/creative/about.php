<?php include('header.php') ?>


<div id="aboutus" class="page-desc-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 page-desc">
				<h2 style="padding-left:45px;" class="visible" ><?php echo SCTEXT('About Us')?></h2>
				<p style="padding-left:45px;" class=" visible"><?php echo SCTEXT('All about our company and our values.')?></p>
			</div>
		</div>
	</div>
</div>

<section class="work-area section">
	<div class="container">
		<div class="row">
                <?php echo htmlspecialchars_decode($pdata['content']); ?>
        </div>
           
	</div>
</section>




<!-- Work Area Ends -->
<?php include('footer.php') ?>