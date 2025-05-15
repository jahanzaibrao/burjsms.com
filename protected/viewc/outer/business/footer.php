
		<div id="footer" style="padding:0px;">

		<!-- /// FOOTER     ///////////////////////////////////////////////////////////////////////////////////////////////////////// -->


		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #footer -->

        <div id="footer-bottom">

		<!-- /// FOOTER-BOTTOM     /////////////////////////////////////////////////////////////////////////////////////////////////// -->

			<div class="row">
				<div class="span6" id="footer-bottom-widget-area-1">

                    <div class="widget widget_text">

                        <div class="textwidget">

                            <p class="last"><?php echo $cdata['company_name']; ?> © <?php echo Date('Y'); ?> by &nbsp;<a  href="<?php echo Doo::conf()->web_protocol.'://'.$_SESSION['webfront']['current_domain'] ?>"><?php echo $_SESSION['webfront']['current_domain'] ?></a></p>

                        </div><!-- end .textwidget -->

                    </div><!-- end .widget_text -->

                </div><!-- end .span6 -->
                <div class="span6 text-right" id="footer-bottom-widget-area-2">

                    <div class="widget widget_pages">

                       <p>
                                    <a style="color:<?php echo $data['skin']['code'] ?> !important;" href="<?php echo Doo::conf()->APP_URL ?>web/terms"><?php echo SCTEXT('Terms & Conditions')?></a> &nbsp;|&nbsp; <a style="color:<?php echo $data['skin']['code'] ?> !important;" href="<?php echo Doo::conf()->APP_URL ?>web/privacy"><?php echo SCTEXT('Privacy Policy')?></a>
                                </p>

                    </div>

                </div><!-- end .span6 -->
            </div><!-- end .row -->

		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #footer -->

	</div><!-- end #wrap -->

    <a id="back-to-top" href="#"><i class="fa fa-angle-up"></i></a>

    <!-- /// jQuery ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/jquery-2.1.1.min.js"></script>

    <!-- /// ViewPort ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/viewport/jquery.viewport.js"></script>

    <!-- /// Easing ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/easing/jquery.easing.1.3.js"></script>

    <!-- /// SimplePlaceholder ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/simpleplaceholder/jquery.simpleplaceholder.js"></script>

    <!-- /// Fitvids ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/fitvids/jquery.fitvids.js"></script>

    <!-- /// Animations ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/animations/animate.js"></script>

    <!-- /// Superfish Menu ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/superfish/hoverIntent.js"></script>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/superfish/superfish.js"></script>

    <!-- /// Revolution Slider ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/revolutionslider/js/jquery.themepunch.plugins.min.js"></script>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/revolutionslider/js/jquery.themepunch.revolution.min.js"></script>

    <!-- /// bxSlider ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/bxslider/jquery.bxslider.min.js"></script>

   	<!-- /// Magnific Popup ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/magnificpopup/jquery.magnific-popup.min.js"></script>

    <!-- /// Isotope ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/isotope/imagesloaded.pkgd.min.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/isotope/isotope.pkgd.min.js"></script>

    <!-- /// Parallax ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/parallax/jquery.parallax.min.js"></script>

	<!-- /// EasyPieChart ////////  -->
	<script src="_layout/js/easypiechart/jquery.easypiechart.min.js"></script>

	<!-- /// YTPlayer ////////  -->
	<script src="_layout/js/itplayer/jquery.mb.YTPlayer.js"></script>

    <!-- /// Easy Tabs ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/easytabs/jquery.easytabs.min.js"></script>

    <!-- /// Waypoints ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/waypoints/waypoints.min.js"></script>

    <!-- /// Form validate ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/jqueryvalidate/jquery.validate.min.js"></script>

	<!-- /// Form submit ////////  -->
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/jqueryform/jquery.form.min.js"></script>

    <!-- /// gMap ////////  -->
	<script src="https://maps.google.com/maps/api/js?sensor=false"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/gmap/jquery.gmap.min.js"></script>

	<!-- /// Twitter ////////  -->

	<!-- /// Custom JS ////////  -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/plugins.js"></script>
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/scripts.js"></script>

    <script type="text/javascript">

		var tpj=jQuery;
		tpj.noConflict();

		tpj(document).ready(function() {

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
                            var rstr = '<div class="alert error"><i class="ifc-close"></i> '+mydata.msg+'</div>';
                            tpj("#tgw_msg").html(rstr);
                            ele.html(`<?php echo SCTEXT('Send SMS')?>`).removeClass('disabledBox');
                        }else{
                            //success
                            var rstr = '<div class="alert success"><i class="ifc-checkmark"></i> '+mydata.msg+'</div>';
                            tpj("#tgw_msg").html(rstr);
                            tpj("#tgw_contact").val('');
                            ele.html(`<?php echo SCTEXT('Send SMS')?>`).removeClass('disabledBox');
                        }
                    }
                })
            })




	});

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return email.match(re);
    }

	</script>

</body>
</html>
<!-- Localized -->
