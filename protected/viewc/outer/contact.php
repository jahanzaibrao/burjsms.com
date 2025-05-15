<?php include('header.php') ?>

<section id="content" class="container clearfix">
<header class="page-header">

		<h1 class="page-title"><?php echo SCTEXT('Contact Us') ?></h1>

		<hr />

		<h2 class="page-description"><?php echo $data['content']->page_header ?></h2>

	</header>

	<?php $parts = unserialize($data['content']->page_content); ?>

    <div class="one-fourth">

			<h3><?php echo SCTEXT('Contact Info') ?></h3>

			<p><?php echo nl2br($parts['addr']); ?></p>

				<p><?php echo SCTEXT('Phone')?>: <?php echo $parts['cphone']; ?><br/>
				Email: <?php echo $parts['cemail']; ?></p>

		</div><!-- end .one-fourth -->

	<div class="three-fourth last">

			<h3><?php echo SCTEXT("Let's keep in touch")?></h3>

			<form action="" method="post" class="contact-form">
			<input type="hidden" name="cemail" value="<?php echo base64_encode($parts['cemail']); ?>" />
				<p class="input-block">
					<label for="contact-name"><strong><?php echo SCTEXT('Name')?></strong> (<?php echo SCTEXT('required')?>)</label>
					<input type="text" name="name" value="" id="contact-name" required>
				</p>

				<p class="input-block">
					<label for="contact-email"><strong>Email</strong> (<?php echo SCTEXT('required')?>)</label>
					<input type="email" name="email" value="" id="contact-email" required>
				</p>

				<p class="input-block">
					<label for="contact-subject"><strong><?php echo SCTEXT('Subject')?></strong></label>
					<input type="text" name="subject" value="" id="contact-subject">
				</p>

				<p class="textarea-block">
					<label for="contact-message"><strong><?php echo SCTEXT('Your Message')?></strong> (<?php echo SCTEXT('required')?>)</label>
					<textarea name="message" id="contact-message" cols="88" rows="6" required></textarea>
				</p>

				<div class="hidden">
					<label for="contact-spam-check">Do not fill out this field:</label>
					<input name="spam-check" type="text" value="" id="contact-spam-check" />
				</div>

				<input class="button" type="button" value="Submit" id="mysub">

				<div class="clear"></div>

			</form>

		</div><!-- end .three-fourth.last -->

</section><!-- end #content -->

<?php include('footer.php') ?>
