<?php include('header.php') ?>

<section id="content" class="container clearfix" style="min-height: 46px;">

	<header class="page-header">

		<h1 class="page-title"><?php echo SCTEXT('Contact Us') ?></h1>

	</header><!-- end .page-header -->

	<div class="one-fourth">

			<h3><?php echo SCTEXT('Contact Info') ?></h3>

			<p><?php echo nl2br($pdata['address']) ?></p>

				<p><?php echo SCTEXT('Phone')?>: <?php echo $cdata['helpline'] ?><br/>
				Email: <?php echo $cdata['helpmail'] ?></p>

		</div><!-- end .one-fourth -->

	<div class="three-fourth last">
<?php if($data['notif_msg']['msg']!=''){ ?><p class="info"> <?php echo $data['notif_msg']['msg'] ?> </p><?php } ?>
			<h3><?php echo SCTEXT("Let's keep in touch")?></h3>

			<form action="<?php echo Doo::conf()->APP_URL ?>saveContactLead" method="post" class="contact-form">
			<input type="hidden" name="cemail" value="<?php echo base64_encode($pdata['qmail']); ?>" />
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
					<input type="text" name="subject" value="<?php echo $data['sub']==''?'':'Details for: '.$data['sub'] ?>" id="contact-subject">
				</p>

				<p class="textarea-block">
					<label for="contact-message"><strong><?php echo SCTEXT('Your Message')?></strong> (<?php echo SCTEXT('required')?>)</label>
					<textarea name="message" id="contact-message" cols="88" rows="6" required></textarea>
				</p>

				<input class="button" type="submit" value="Submit" id="submit-contact">

				<div class="clear"></div>

			</form>

		</div>


</section>


<?php include('footer.php') ?>
