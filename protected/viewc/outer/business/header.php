<?php 
$pdata = unserialize(base64_decode($data['pdata']->page_data));
$cdata = unserialize($_SESSION['webfront']['company_data']);
?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1,requiresActiveX=true">
    
    
	<title><?php echo $pdata['title'] ?></title>
	<meta name="description" content="<?php echo $pdata['metadesc'] ?>" />
    
    <!-- /// Favicons ////////  -->
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>">


	<!-- /// Google Fonts ////////  -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,600italic,700,700italic">
    
    <!-- /// FontAwesome Icons 4.1.0 ////////  -->
	<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/fontawesome/font-awesome.min.css">
    
    <!-- /// Custom Icon Font ////////  -->
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/iconfontcustom/icon-font-custom.css">  
    
	<!-- /// Template CSS ////////  -->
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/base.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/grid.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/elements.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/layout.css">

	<!-- /// Boxed layout ////////  -->
	<!-- <link rel="stylesheet" href="_layout/css/boxed.css"> -->
    
	<!-- /// JS Plugins CSS ////////  -->
	<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/revolutionslider/css/settings.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/revolutionslider/css/custom.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/bxslider/jquery.bxslider.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/magnificpopup/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/animations/animate.min.css">
	<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/js/itplayer/css/YTPlayer.css">
    
   
 
 	<!-- /// Style Switcher CSS ////////  -->
 	<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/wide.css" id="template-layout">
	<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/business/_layout/css/skins/<?php echo $data['skin']['color'] ?>.css" id="template-skin">
    <style>
        .rtblock{
             box-shadow: 0 9px 5px -2px #ccc;
    margin: 10px 0 15px;
    padding: 10px;
        }
        
        #mycontactbox{
            padding-top: 6%;
        }
        .disabledBox{
    opacity: 0.3;
    pointer-events: none;
    cursor: not-allowed;
}
        @media (max-width:768px) {
    #mycontactbox{
            padding-top: 14%;
        }
} 
    </style>
    <script>
        var app_url='<?php echo Doo::conf()->APP_URL ?>';
        var sctext = [];
        var app_currency = '<?php echo Doo::conf()->currency ?>';
        var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
    </script>
</head>
<body>
	
    <!-- Style Switcher read settings for layout and skin -->
    
    <noscript>
        <div class="alert warning">
            <i class="fa fa-left-sides-circle"></i> You seem to have Javascript disabled. This website needs javascript in order to function properly!
        </div>
    </noscript>
    	
	<!--[if lte IE 8]>
         <div class="alert error">
        	You are using an <strong>outdated</strong> browser. Please 
        	<a href="https://windows.microsoft.com/en-us/internet-explorer/download-ie">upgrade your browser</a> 
            to improve your experience.
		</div>
    <![endif]-->

	<div id="wrap">
		
        <div id="header-top">
        
		<!-- /// HEADER-TOP  //////////////////////////////////////////////////////////////////////////////////////////////////////// -->

			<div class="row">
            	<div class="span6" id="header-top-widget-area-1">
                
                	<div class="widget ewf_widget_contact_info">
                        
                                <i class="fa fa-lg fa-envelope"></i>&nbsp;<?php echo SCTEXT('Mail Us')?>: <a href="mailto:<?php echo $cdata['helpmail'] ?>"><?php echo $cdata['helpmail'] ?></a>
                            
                    </div><!-- end .ewf_widget_contact_info -->
                
                </div><!-- end .span6 -->
                <div class="span6 text-right" id="header-top-widget-area-2">
                    
                                <i class="fa fa-lg fa-phone"></i>&nbsp;<?php echo SCTEXT('Call Us')?>: <?php echo $cdata['helpline'] ?>
                            
                
                </div><!-- end .span6 -->
            </div><!-- end .row -->

		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #header-top -->
		
		<div id="header">
        
		<!-- /// HEADER  //////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

			<div class="row">
            	<div class="span3">
                	
                    <!-- // Logo // -->
                    <a href="<?php echo Doo::conf()->APP_URL ?>" id="logo">
                        <img class="responsive-img" src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" style="max-height:57px;" alt="">
                    </a>
                    
                </div><!-- end .span3 -->
                <div class="span9">
                	
                    <a href="#" id="mobile-menu-trigger">
                    	<i class="fa fa-bars"></i>
                    </a>
                    
                    <!-- // Menu // -->
					<ul class="sf-menu fixed" id="menu">
						<li class=" <?php if($data['pdata']->page_type=='HOME'){ ?> current <?php } ?> dropdown">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT('Home')?></a>
                        </li>
                        <li class=" <?php if($data['pdata']->page_type=='ABOUT'){ ?> current <?php } ?> dropdown">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/about"><?php echo SCTEXT('About Us')?></a>
                        </li>
                        <li class=" <?php if($data['pdata']->page_type=='PRICING'){ ?> current <?php } ?> dropdown">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/pricing"><?php echo SCTEXT('SMS Pricing')?></a>
                        </li>
                        <li class=" <?php if($data['pdata']->page_type=='CONTACT'){ ?> current <?php } ?> dropdown">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/contact-us"><?php echo SCTEXT('Contact Us')?></a>
                        </li>
                        <li class="dropdown">
                        	<a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"><?php echo SCTEXT('Login')?></a>
                        </li>
                        
					</ul>
                    
                </div><!-- end .span9 -->
            </div><!-- end .row -->

		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #header -->