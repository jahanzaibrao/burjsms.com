
  <footer class="footer">
    <!--end container-->

    <p class="copyright">

        <?php echo $cdata['company_name']; ?> © <?php echo Date('Y'); ?> by &nbsp;<a class="red" style="text-decoration-color:<?php echo $data['skin']['color'] ?>;" href="<?php echo Doo::conf()->web_protocol.'://'.$_SESSION['webfront']['current_domain'] ?>"><?php echo $_SESSION['webfront']['current_domain'] ?></a>

        &nbsp;&nbsp;

        <a class="red" href="<?php echo Doo::conf()->APP_URL ?>web/terms"><?php echo SCTEXT('Terms & Conditions')?></a> &nbsp;|&nbsp; <a class="red" href="<?php echo Doo::conf()->APP_URL ?>web/privacy"><?php echo SCTEXT('Privacy Policy')?></a>

      </p>
    <div class="clr"></div>
  </footer>
</div>
<!--End Slide 3-->

<script type="text/javascript">

				var tpj=jQuery;
				tpj.noConflict();

     //test gateway widget
            tpj(document).on("click","#submit_tgw",function(){
                var ele = tpj(this);
                ele.html("Sending ...").addClass('disabledBox');
                tpj.ajax({
                    url: app_url+'submitGwTestSms',
                    data: {mobile: tpj("#tgw_contact").val()},
                    type: 'post',
                    success: function(res){
                        var mydata = JSON.parse(res);
                        if(mydata.result=='error'){
                            //fail
                            var rstr = '<p style="color:#fff;" class="error"><i class="icon-remove"></i><span>Error!</span>'+mydata.msg+'</p>';
                            tpj("#tgw_msg").html(rstr);
                            ele.html("<?php echo SCTEXT('Send SMS')?>").removeClass('disabledBox');
                        }else{
                            //success
                            var rstr = '<p style="color:#fff;" class="success"><i class="icon-ok"></i><span>Success!</span>'+mydata.msg+'</p>';
                            tpj("#tgw_msg").html(rstr);
                            tpj("#tgw_contact").val('');
                            ele.html("<?php echo SCTEXT('Send SMS')?>").removeClass('disabledBox');
                        }
                    }
                })
            })

				tpj(document).ready(function() {

                    //validate contact form
                    tpj("#submit-contact").click(function(){
                        if(tpj("#name").val()==''){
                            tpj("#response").html(`<p style="color:#fff;" class="error"><i class="icon-remove"></i><span>Error!</span><?php echo SCTEXT('Name cannot be empty')?></p>`);
                            return false;
                        }
                        if(tpj("#email").val()==''){
                            tpj("#response").html(`<p style="color:#fff;" class="error"><i class="icon-remove"></i><span>Error!</span><?php echo SCTEXT('Email cannot be empty')?></p>`);
                            return false;
                        }

                        if(!echeck(tpj("#email").val())){
                            tpj("#response").html(`<p style="color:#fff;" class="error"><i class="icon-remove"></i><span>Error!</span><?php echo SCTEXT('Please enter a valid email')?></p>`);
                            return false;
                        }

                        if(tpj("#message").val()==''){
                            tpj("#response").html(`<p style="color:#fff;" class="error"><i class="icon-remove"></i><span>Error!</span><?php echo SCTEXT('Message cannot be empty')?></p>`);
                            return false;
                        }

                        tpj("#response").html(`<p style="text-align: right;font-size: 16px;margin: 10px 10px;"><i class="icon-refresh icon-spin"></i>&nbsp:Submitting form. <?php echo SCTEXT('Please wait')?> ...</p>`);
                        tpj("contact-form").submit();
                    })

				if (tpj.fn.cssOriginal!=undefined)
					tpj.fn.css = tpj.fn.cssOriginal;

					tpj('.fullwidthbanner').revolution(
						{
							delay:9000,
							startwidth:890,
							startheight:450,

							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off

							thumbWidth:100,							// Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
							thumbHeight:50,
							thumbAmount:4,

							hideThumbs:200,
							navigationType:"both",					//bullet, thumb, none, both	 (No Shadow in Fullwidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar

							touchenabled:"on",						// Enable Swipe Function : on/off

							navOffsetHorizontal:0,
							navOffsetVertical:20,

							fullWidth:"on",

							shadow:0								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows -  (No Shadow in Fullwidth Version !)

						});

			});
</script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery1.7.2.js"></script>
<!--Revolution slider end here-->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery.stellar.min.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery.easing.1.3.min.js"></script>
<!--Crousel start here-->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script>


		$(function () {
		//	Scrolled by user interaction
				$('#foo2').carouFredSel({
					auto: false,
					prev: '#prev2',
					next: '#next2',
					pagination: "#pager2",
					mousewheel: true,
					swipe: {
						onMouse: true,
						onTouch: true
					}
				});

		$('#slider2').anythingSlider({
			expand       : true,
			autoPlay     : true
		});

	});

</script>
<!--Crousel end here-->

<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/js.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery.anythingslider.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/bootstrap-tab.js"></script>
<script>

    $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    })

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false
		 }

		 if (str.indexOf(" ")!=-1){
		    return false
		 }

 		 return true
	}

</script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/bootstrap-transition.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/bootstrap-collapse.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/bootstrap-dropdown.js"></script>
</body>
</html>
