

<footer id="footer" class="clearfix">

	<div class="container">

		<div class="three-fourth">

			<nav id="footer-nav" class="clearfix">

				<ul>
					<li>
                        <a href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT("Home") ?></a>

                    </li>
                    <li>
                        <a href="<?php echo Doo::conf()->APP_URL ?>web/about"><?php echo SCTEXT("About") ?></a>

                    </li>
                    <li>
                        <a href="<?php echo Doo::conf()->APP_URL ?>web/pricing"><?php echo SCTEXT("Pricing") ?></a>

                    </li>
                    <li>
                        <a href="<?php echo Doo::conf()->APP_URL ?>web/contact-us"><?php echo SCTEXT("Contact") ?></a>

                    </li>
                    <li>
                        <a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"><?php echo SCTEXT("Login") ?></a>
                    </li>
				</ul>
				
			</nav><!-- end #footer-nav -->

			<ul class="contact-info">
				
				<li class="email"><a href="mailto:<?php echo $cdata['helpmail'] ?>"><?php echo $cdata['helpmail'] ?></a></li>
			</ul><!-- end .contact-info -->
			
		</div><!-- end .three-fourth -->

		<div class="one-fourth last">

			<span class="title"><?php echo SCTEXT('Stay connected') ?></span>

			<ul class="contact-info">
				<li class="phone"><?php echo $cdata['helpline'] ?></li>
			</ul><!-- end .social-links -->

		</div><!-- end .one-fourth.last -->
		
	</div><!-- end .container -->

</footer><!-- end #footer -->

<footer id="footer-bottom" class="clearfix">

	<div class="container">

		<ul>
			<li><?php echo $cdata['company_name']; ?> © <?php echo Date('Y'); ?></li>
			<li><a href="<?php echo Doo::conf()->APP_URL ?>web/terms"><?php echo SCTEXT('Terms & Conditions') ?> </a></li>
			<li><a href="<?php echo Doo::conf()->APP_URL ?>web/privacy"><?php echo SCTEXT('Privacy Policy') ?></a></li>
		</ul>

	</div><!-- end .container -->

</footer><!-- end #footer-bottom -->

<!--[if !lte IE 6]><!-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery-1.7.1.min.js"><\/script>')</script>
	<!--[if lt IE 9]> <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/selectivizr-and-extra-selectors.min.js"></script> <![endif]-->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/respond.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.easing-1.3.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.fancybox.pack.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.smartStartSlider.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.jcarousel.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.cycle.all.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.isotope.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/audioplayerv1.min.js"></script>
	<script src="//maps.google.com/maps/api/js?sensor=false"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.gmap.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/jquery.touchSwipe.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/custom.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        
        //test gateway widget
        $(document).on("click","#submit_tgw",function(){
                var ele = $(this);
                ele.html("Sending ...").addClass('disabledBox');
                $.ajax({
                    url: app_url+'submitGwTestSms',
                    data: {mobile: $("#tgw_contact").val()},
                    type: 'post',
                    success: function(res){
                        var mydata = JSON.parse(res);
                        if(mydata.result=='error'){
                            //fail
                            var rstr = '<p class="error"><strong>Error</strong> - '+mydata.msg+' </p>';
                            $("#tgw_msg").html(rstr);
                            ele.html("Send SMS").removeClass('disabledBox');
                        }else{
                            //success
                            var rstr = '<p class="success"><strong>Success</strong> - '+mydata.msg+' </p>';
                            $("#tgw_msg").html(rstr);
                            $("#tgw_contact").val('');
                            ele.html("Send SMS").removeClass('disabledBox');
                        }
                    }
                })
            })
        
    });
        
</script>
<!--<![endif]-->
</body>
</html>