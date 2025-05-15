<?php include('header.php') ?>


<div id="privacy" class="page-desc-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 page-desc">
				<h2 style="padding-left:45px;" class="visible" ><?php echo SCTEXT('Privacy Policy')?></h2>
				<p style="padding-left:45px;" class=" visible"><?php echo SCTEXT('We care for information privacy.')?></p>
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