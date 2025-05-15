

<!-- end #content -->

<footer id="footer" class="clearfix">

	<div class="container">

		<div class="three-fourth">

			<nav id="footer-nav" class="clearfix">

				<ul>
					<li><a href="<?php echo $data['baseurl'] ?>"><?php echo SCTEXT('Home') ?></a></li>
					<li><a href="<?php echo $data['baseurl'] ?>site/about-us"><?php echo SCTEXT('About') ?></a></li>
					<li><a href="<?php echo $data['baseurl'] ?>site/pricing"><?php echo SCTEXT('Pricing') ?></a></li>
					<li><a href="<?php echo $data['baseurl'] ?>site/contact"><?php echo SCTEXT('Contact') ?></a></li>
					<li><a href="<?php echo $data['baseurl'] ?>sign-in"><?php echo SCTEXT('Login') ?></a></li>
				</ul>
				
			</nav><!-- end #footer-nav -->

			<ul class="contact-info">
				<li class="phone"><?php echo $_SESSION['site']['meta']['help_line'] ?></li>
				<li class="email"><a href="mailto:<?php echo $_SESSION['site']['meta']['support_email'] ?>"><?php echo $_SESSION['site']['meta']['support_email'] ?></a></li>
			</ul><!-- end .contact-info -->
			
		</div><!-- end .three-fourth -->

		<div class="one-fourth last">

			<span class="title"><?php echo SCTEXT('Stay connected') ?></span>

			<ul class="social-links">
				<li class="twitter"><a target="_blank" href="http://<?php echo $_SESSION['site']['meta']['twitter_link'] ?>">Twitter</a></li>
				<li class="facebook"><a target="_blank" href="http://<?php echo $_SESSION['site']['meta']['fb_link'] ?>">Facebook</a></li>
				<li class="youtube"><a target="_blank" href="http://<?php echo $_SESSION['site']['meta']['youtube_link'] ?>">YouTube</a></li>
			</ul><!-- end .social-links -->

		</div><!-- end .one-fourth.last -->
		
	</div><!-- end .container -->

</footer><!-- end #footer -->

<footer id="footer-bottom" class="clearfix">

	<div class="container">

		<ul>
			<li><?php echo $_SESSION['site']['meta']['company_name'] ?> &copy; 2014</li>
			
		</ul>

	</div><!-- end .container -->

</footer><!-- end #footer-bottom -->

<!--[if !lte IE 6]><!-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery-1.7.1.min.js"><\/script>')</script>
	<!--[if lt IE 9]> <script src="js/selectivizr-and-extra-selectors.min.js"></script> <![endif]-->
	<script src="<?php echo $data['baseurl'] ?>global/white/js/respond.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.easing-1.3.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.fancybox.pack.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.smartStartSlider.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.jcarousel.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.cycle.all.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.isotope.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/audioplayerv1.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/jquery.touchSwipe.min.js"></script>
	<script src="<?php echo $data['baseurl'] ?>global/white/js/custom.js"></script>
<!--<![endif]-->
</body>
</html>