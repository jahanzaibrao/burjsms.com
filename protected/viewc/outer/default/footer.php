<?php $cdata = unserialize($_SESSION['webfront']['company_data']); ?>
<footer id="page-footer">

    <div class="wrapper">

        <div class="row-fluid">

            <div class="span12 clearfix">

                <div class="copyright">
                    <p><?php echo $cdata['company_name']; ?> © <?php echo Date('Y'); ?> by &nbsp;<a href="<?php echo Doo::conf()->web_protocol . $_SESSION['webfront']['current_domain'] ?>"><?php echo $_SESSION['webfront']['current_domain'] ?></a></p>

                </div><!--end:copyright-->

                <div class="social-media-widget">
                    <div class="social-bookmarks">
                        <div class="copyright">
                            <p>
                                <a href="<?php echo Doo::conf()->APP_URL ?>web/terms"><?php echo SCTEXT('Terms & Conditions') ?></a> &nbsp;|&nbsp; <a href="<?php echo Doo::conf()->APP_URL ?>web/privacy"><?php echo SCTEXT('Privacy Policy') ?></a>
                            </p>

                        </div>
                    </div><!--end:social-bookmarks-->
                </div><!--end:social-media-widget-->

                <p id="back-top">
                    <a href="#top"><i class="icon-chevron-up"></i></a>
                </p><!--end:back-top-->

            </div><!--end:span12-->

        </div><!--end:row-fluid-->

    </div><!--end:wrapper-->

</footer><!--end:page-footer-->

<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/superfish.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/retina.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/bootstrap.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.form.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.validate.min.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.nivo.slider.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.flexslider.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.carouFredSel-5.6.4.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.prettyPhoto.js"></script>

<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/modernizr.custom.63321.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.hoverdir.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.dropdown.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/modernizr.custom.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.dlmenu.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.isotope.min.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.eislideshow.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/raphael-min.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/iview.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/custom.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.easing.1.3.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.quicksand.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/main.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/bra_social_media.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.themepunch.plugins.min.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/jquery.themepunch.revolution.min.js"></script>
<!-- <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/styleswitch.js"></script> -->

<script type="text/javascript">
    var tpj = jQuery;
    tpj.noConflict();

    tpj(document).ready(function() {

        //test gateway widget
        tpj(document).on("click", "#submit_tgw", function() {
            var ele = tpj(this);
            ele.html("Sending ...").addClass('disabledBox');
            tpj.ajax({
                url: app_url + 'submitGwTestSms',
                data: {
                    mobile: tpj("#tgw_contact").val()
                },
                type: 'post',
                success: function(res) {
                    var mydata = JSON.parse(res);
                    if (mydata.result == 'error') {
                        //fail
                        var rstr = '<div class="alert-box alert-box-warning"><p>Error : ' + mydata.msg + '</p></div>';
                        tpj("#tgw_msg").html(rstr);
                        ele.html(`<?php echo SCTEXT('Send SMS') ?>`).removeClass('disabledBox');
                    } else {
                        //success
                        var rstr = '<div class="alert-box alert-box-success"><p>Success : ' + mydata.msg + '</p></div>';
                        tpj("#tgw_msg").html(rstr);
                        tpj("#tgw_contact").val('');
                        ele.html(`<?php echo SCTEXT('Send SMS') ?>`).removeClass('disabledBox');
                    }
                }
            })
        })










        if (tpj.fn.cssOriginal != undefined)
            tpj.fn.css = tpj.fn.cssOriginal;

        tpj('.fullwidthbanner').revolution({
            delay: 9000,
            startwidth: 1000,
            startheight: 500,

            onHoverStop: "on", // Stop Banner Timet at Hover on Slide on/off

            thumbWidth: 100, // Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
            thumbHeight: 50,
            thumbAmount: 3,

            hideThumbs: 0,
            navigationType: "bullet", // bullet, thumb, none
            navigationArrows: "solo", // nexttobullets, solo (old name verticalcentered), none

            navigationStyle: "navbar", // round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom


            navigationHAlign: "left", // Vertical Align top,center,bottom
            navigationVAlign: "bottom", // Horizontal Align left,center,right
            navigationHOffset: 30,
            navigationVOffset: 30,

            soloArrowLeftHalign: "left",
            soloArrowLeftValign: "center",
            soloArrowLeftHOffset: 0,
            soloArrowLeftVOffset: 0,

            soloArrowRightHalign: "right",
            soloArrowRightValign: "center",
            soloArrowRightHOffset: 0,
            soloArrowRightVOffset: 0,

            touchenabled: "on", // Enable Swipe Function : on/off


            stopAtSlide: -1, // Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
            stopAfterLoops: -1, // Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

            hideCaptionAtLimit: 0, // It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
            hideAllCaptionAtLilmit: 0, // Hide all The Captions if Width of Browser is less then this value
            hideSliderAtLimit: 0, // Hide the whole slider, and stop also functions if Width of Browser is less than this value

            fullWidth: "on",

            shadow: 2 //0 = no Shadow, 1,2,3 = 3 Different Art of Shadows -  (No Shadow in Fullwidth Version !)

        });




    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return email.match(re);
    }
</script>
<style>
    .tp-caption {
        min-width: 250px;
        white-space: normal !important;
    }
</style>

</body>

</html>