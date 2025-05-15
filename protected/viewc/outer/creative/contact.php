<?php include('header.php') ?>

<div id="contact-us">
    <div class="page-desc-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 page-desc">
                    <h2 style="padding-left:55px;" class="visible" ><?php echo SCTEXT('Contact Us')?></h2>
                    <p style="padding-left:55px;" class=" visible"><?php echo SCTEXT('We are easily reachable.')?></p>
                </div>
            </div>
        </div>
    </div>

<section id="#contact-details-section" class="contact-details-section section slant-angle">
			<!-- Slant Shape -->
			<div class="slant-top-angle" style="border-top-width: 75.4672px; border-left-width: 1440px;"></div>
			<div class="section-inner">
			<div class="container">
				<!-- Title And Sub Title Section -->
				<div class="row">
					<div class="col-xs-12">
						<!-- Title -->
						<h2 class="section-title text-center animated fadeInRight visible" data-animation="fadeInRight" data-animation-delay="300"><?php echo SCTEXT('Our contact details')?></h2>

					</div>
				</div>
				<!-- Details Section -->
				<div class="row">


					<!-- Item 2 -->
					<div class="col-md-4 col-sm-6 animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="700">
						<div class="datail-box">
							<!-- Title -->
							<div class="detail-title">
								<h3><?php echo SCTEXT('our location')?></h3>
							</div>
							<div class="detail-content">
								<!-- Content 1 -->
								<p><?php echo nl2br($pdata['address']) ?></p>

							</div>
						</div>
					</div>

					<!-- Item 3 -->
					<div class="col-md-4 col-sm-6 animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="900">
						<div class="datail-box">
							<!-- Title -->
							<div class="detail-title">
								<h3><?php echo SCTEXT('phone')?></h3>
							</div>
							<div class="detail-content">
								<!-- Content 1 -->
								<h4>support</h4>
								<p><?php echo $cdata['helpline'] ?></p>

							</div>
						</div>
					</div>

					<!-- Item 4 -->
					<div class="col-md-4 col-sm-6 animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="1100">
						<div class="datail-box">
							<!-- Title -->
							<div class="detail-title">
								<h3><?php echo SCTEXT('e-mail')?></h3>
							</div>
							<div class="detail-content">
								<!-- Content 1 -->
								<h4>support</h4>
								<p><?php echo $cdata['helpmail'] ?></p>


							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
				<!-- Slant Shape -->
		<div class="slant-bottom-angle" style="top: 528.533px; border-bottom-width: 75.4672px; border-right-width: 1440px;"></div>
		</section>





<section id="#customer-support-section" class="customer-support-section">
			<div class="container">
				<!-- Title And Sub Title Section -->
				<div class="row">
					<div class="col-xs-12">
						<!-- Title -->
						<h2 class="section-title text-center animated fadeInRight visible" data-animation="fadeInRight" data-animation-delay="300"><?php echo SCTEXT('Write to us at Customer Support')?></h2>
						<!-- Sub Title -->

					</div>
				</div>

				<!-- Contact Box -->
				<div class="contact-boxes animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="500">
					<?php if($data['notif_msg']['msg']!=''){ ?>
                    <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $data['notif_msg']['msg']; ?></div>
                    <?php } ?>

					<form role="form" name="commentform" class="contact-form-horizontal bv-form" id="commentform" method="post" action="<?php echo Doo::conf()->APP_URL ?>saveContactLead" novalidate="novalidate">
                        <input type="hidden" name="cemail" value="<?php echo base64_encode($pdata['qmail']); ?>" />
						<div class="row">
							<!-- Name -->
							<div class="col-md-4 col-sm-4">
								<div class="input-group form-group has-feedback">
								  <span class="input-group-addon"><span class="fa fa-user"></span></span>
									<input type="text" name="name" id="contact_name" class=" form-control input-name" placeholder="<?php echo SCTEXT('Your Name')?>" data-bv-field="contact_name"><i style="display: none; top: 0px;" class="form-control-feedback" data-bv-icon-for="contact_name"></i>
								<small style="display: none;" class="help-block" data-bv-validator="notEmpty" data-bv-for="contact_name" data-bv-result="NOT_VALIDATED"><?php echo SCTEXT('Name is required. Please enter name.')?></small></div>
							</div>

							<!-- E-mail -->
							<div class="col-md-4 col-sm-4">
								<div class="input-group form-group has-feedback">
								  <span class="input-group-addon"><span class="flaticon-black164"></span></span>
									<input type="email" name="email" id="contact_email" class=" form-control input-email" placeholder="<?php echo SCTEXT('E-mail')?>" data-bv-field="contact_email"><i style="display: none; top: 0px;" class="form-control-feedback" data-bv-icon-for="contact_email"></i>
								<small style="display: none;" class="help-block" data-bv-validator="notEmpty" data-bv-for="contact_email" data-bv-result="NOT_VALIDATED"><?php echo SCTEXT('Email is required. Please enter email.')?></small><small style="display: none;" class="help-block" data-bv-validator="emailAddress" data-bv-for="contact_email" data-bv-result="NOT_VALIDATED"><?php echo SCTEXT('Please enter correct email address.')?></small></div>
							</div>

							<!-- Tele Phone -->
							<div class="col-md-4 col-sm-4">
								<div class="input-group form-group has-feedback">
								  <span class="input-group-addon"><span class="fa fa-book"></span></span>
									<input type="text" name="subject" id="subject" class=" form-control input-phone-number" placeholder="<?php echo SCTEXT('subject')?>" value="<?php echo $data['sub']==''?'':'Details for: '.$data['sub'] ?>" data-bv-field=""><i style="display: none; top: 0px;" class="form-control-feedback" data-bv-icon-for="contact_number"></i>
								<small style="display: none;" class="help-block" data-bv-validator="notEmpty" data-bv-for="contact_number" data-bv-result="NOT_VALIDATED"><?php echo SCTEXT('Please enter a value')?></small></div>
							</div>
						</div>

						<!-- Message Box -->
						<div class="row">
							<div class="col-md-12 message-box form-group has-feedback">

								<textarea class=" form-control textarea-message contact-message-box" rows="3" placeholder="<?php echo SCTEXT('Write Your Questions here')?>..." name="message" data-bv-field="contact_message" id="contact_message"></textarea><i style="display: none; top: 0px;" class="form-control-feedback" data-bv-icon-for="contact_message"></i>
							<small style="display: none;" class="help-block" data-bv-validator="notEmpty" data-bv-for="contact_message" data-bv-result="NOT_VALIDATED"><?php echo SCTEXT('Message is required. Please enter your message.')?></small></div>
						</div>

						<!-- Send Button -->
						<div class="row send-btn">
							<div class="col-md-12">
								<input type="submit" id="sendingbtn" class="btn send-button" value="<?php echo SCTEXT('Send Message')?>">
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>



</div>





<!-- Work Area Ends -->
<?php include('footer.php') ?>
