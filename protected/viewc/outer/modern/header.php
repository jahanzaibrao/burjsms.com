<?php 
$pdata = unserialize(base64_decode($data['pdata']->page_data));
$cdata = unserialize($_SESSION['webfront']['company_data']);
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class='no-js'>
  <!--<![endif]-->
  <head>
    <meta charset='utf-8'>
    <meta content='width=device-width, initial-scale=1.0' name='viewport'>
    
    <!--
      hash is dirty
    -->
    <title><?php echo $pdata['title'] ?></title>
	<meta name="description" content="<?php echo $pdata['metadesc'] ?>" /> 
      
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>">
      
    <link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/stylesheets/framework.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/stylesheets/style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/stylesheets/skins.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/stylesheets/fontello.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/libs.js" type="text/javascript"></script>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/app.js" type="text/javascript"></script>
    <script>
      // terrificjs bootstrap
      (function($) {
          $(document).ready(function() {
              var $page = $('body');
              var config = {
                dependencyPath: {
                  plugin: 'javascripts/'
                }
              }
              var application = new Tc.Application($page, config);
              application.registerModules();
              application.start();
          });
      })(Tc.$);
    </script>
      <script>
        var app_url='<?php echo Doo::conf()->APP_URL ?>';
        var sctext = [];
        var app_currency = '<?php echo Doo::conf()->currency ?>';
        var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
    </script>
      <style>
          .rtblock{
             box-shadow: 0 9px 5px -2px #ccc;
    margin: 10px 0 15px;
    padding: 10px;
        }
          .wd100{
              width: 100%;
          }
          .rtopts{
              margin: 10px 0px;
              line-height: 20px;
          }
          .control-label{
              font-size: 14px;
                font-weight: bolder;
          }
          .myalert{
              padding-bottom: 0px !important;
              margin-bottom: 0px !important;
          }
          .validation-error{
              font-weight: 400;
              color: firebrick;
          }
      </style>
  </head>
  <body class='<?php echo 'colorScheme'.ucwords($data['skin']['color']) ?>'>
    <div class='contain-to-grid sticky'>
      <nav class='top-bar' data-options='sticky_on: large' data-topbar=''>
        <ul class='title-area'>
          <li class='name'>
              <h1><a href="<?php echo Doo::conf()->APP_URL ?>" id="logo">
                        <img class="responsive-img" src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" style="max-height:57px;" alt="">
                    </a>
              </h1>
           
          </li>
          <li class='toggle-topbar menu-icon'>
            <a href='#'>Menu</a>
          </li>
        </ul>
        <section class='top-bar-section'>
          <ul class='right'>
              
              <li class=" <?php if($data['pdata']->page_type=='HOME'){ ?> active <?php } ?>">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT('Home')?></a>
                        </li>
                        <li class=" <?php if($data['pdata']->page_type=='ABOUT'){ ?> active <?php } ?> ">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/about"><?php echo SCTEXT('About Us')?></a>
                        </li>
                        <li class=" <?php if($data['pdata']->page_type=='PRICING'){ ?> active <?php } ?> ">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/pricing"><?php echo SCTEXT('SMS Pricing')?></a>
                        </li>
                        <li class=" <?php if($data['pdata']->page_type=='CONTACT'){ ?> active <?php } ?> ">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/contact-us"><?php echo SCTEXT('Contact Us')?></a>
                        </li>
                        <li class="">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"><?php echo SCTEXT('Login')?></a>
                        </li>
              
            
          </ul>
        </section>
      </nav>
    </div>
    <script>
      jQuery(document).ready(function() {
        var options = {
          nextButton: true,
          prevButton: true,
          autoPlay: true,
          autoPlayDelay: 5000,
          pauseButton: true,
          preloader: true,
          animateStartingFrameIn: true,
          pagination: true,
          reverseAnimationsWhenNavigatingBackwards: true,
          preventDelayWhenReversingAnimations: true,
          fadeFrameWhenSkipped: false,
          swipeEvents: {
            left: "next",
            right: "prev"
          }
        }
        var sequence = jQuery("#sequence").sequence(options).data("sequence");
        sequence.beforeCurrentFrameAnimatesOut = function() {
          setTimeout(function() {
            jQuery(".frame.static").removeClass('static');
          }, 1000);
        }
      
      });
    </script>
<!-- Localized -->