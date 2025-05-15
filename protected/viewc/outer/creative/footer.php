
<!-- Footer Section Begins -->
<section id="footer" class="footer-bg" >
	<!-- Copy Rights Section begins-->
	<div class="copy-rights-bg">
		<div class="copy-rights-section">
			<div class="container">
				<div class="row" style="padding-bottom:10px;">
					<!-- Copy Right Details -->
					<div class="col-sm-6">
						<p class="copyright-content">&copy;&nbsp;COPYRIGHT <?php echo Date('Y'); ?>.&nbsp;&nbsp;<a href="<?php echo Doo::conf()->web_protocol.'://'.$_SESSION['webfront']['current_domain'] ?>"> &nbsp;"<?php echo $cdata['company_name']; ?>"&nbsp;</a>&nbsp; All Rights Reserved.</p>
					</div>
					<!-- Copy Right Social Icons -->
					<div class="col-sm-6">
						<p class="copyright-content" style="text-align:right;">
                            <a style="color:<?php echo $data['skin']['code'] ?> !important;" href="<?php echo Doo::conf()->APP_URL ?>web/terms"><?php echo SCTEXT('Terms & Conditions')?></a> &nbsp;|&nbsp; <a style="color:<?php echo $data['skin']['code'] ?> !important;" href="<?php echo Doo::conf()->APP_URL ?>web/privacy"><?php echo SCTEXT('Privacy Policy')?></a>
                        </p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Copy Rights Section ends-->
</section>
<!-- Footer Section Ends -->
<!-- Script Begins -->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery.sticky.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/carousel.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/slider/jquery.fractionslider.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/slider/main.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery.prettyPhoto.js" ></script>
<!-- Flex Slider -->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery.flexslider-min.js" ></script>
<!-- know about us Section Counter-->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery.content_slider.js" ></script>
<!-- Twitter -->
<!-- Expertise Circular Progress Bar -->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery.easypiechart.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/effect.js"></script>

<!-- Apear Js -->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/jquery.appear.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/js/custom.js"></script>
<script type="text/javascript">

     //test gateway widget
    $(document).ready(function(){

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
                            var rstr = '<div class="alert alert-danger" role="alert">'+mydata.msg+'</div>';
                            $("#tgw_msg").html(rstr);
                            ele.html("Send SMS").removeClass('disabledBox');
                        }else{
                            //success
                            var rstr = '<div class="alert alert-success" role="alert">'+mydata.msg+'</div>';
                            $("#tgw_msg").html(rstr);
                            $("#tgw_contact").val('');
                            ele.html("Send SMS").removeClass('disabledBox');
                        }
                    }
                })
            })

    });

</script>
</body>
</html>

<!-- Localized -->
