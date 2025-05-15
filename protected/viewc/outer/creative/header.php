<?php 
$pdata = unserialize(base64_decode($data['pdata']->page_data));
$cdata = unserialize($_SESSION['webfront']['company_data']);
?><!DOCTYPE html>
<html lang="en">
<head>
<!-- Title and Meta Tags Begins -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta charset="utf-8">
<title><?php echo $pdata['title'] ?></title>
<meta name="description" content="<?php echo $pdata['metadesc'] ?>" /> 
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>">
<!-- Title and Meta Tags Ends -->
<!-- Google Font Begins -->
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<!-- Google Font Ends -->
<!-- CSS Begins -->
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href='https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'/>
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/animate.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/flexisel.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/owl.carousel.css" rel="stylesheet" type="text/css"/>
<!-- Your Work -->
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/prettyPhoto.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/YTPlayer.css" rel="stylesheet" type="text/css" />
<!--Flat Icon-->
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/flaticon.css" rel="stylesheet" type="text/css" />
<!-- Color Variations -->
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/color-schemes/<?php echo $data['skin']['color'] ?>.css" id="changeable-colors" rel="stylesheet" type="text/css" />
<!-- Main Style -->
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/creative/css/responsive.css" rel="stylesheet" type="text/css" />
<!-- CSS Ends -->
    <style>
        .rtblock{
             box-shadow: 0 9px 5px -2px #ccc;
    margin: 10px 0 15px;
    padding: 10px;
        }
        .largewb.work-box::after{
            bottom: -26px;

content: "";

height: 30px;
        }
        
        #aboutus.page-desc-section .page-desc:before {

    font-family: 'FontAwesome';
    content: "\f1ad";
    position: absolute;
    font-weight: 900;
    font-size: 44px;
    top: 8%;

}
        
        #pricing.page-desc-section .page-desc:before {

    font-family: 'FontAwesome';
    content: "\f1ec";
    position: absolute;
    font-weight: 900;
    font-size: 44px;
    top: 8%;

}
        
         #contact-us .page-desc-section .page-desc:before {

    font-family: 'FontAwesome';
    content: "\f0e0";
    position: absolute;
    font-weight: 900;
    font-size: 44px;
    top: 8%;

}
        
        #terms.page-desc-section .page-desc:before {

    font-family: 'FontAwesome';
    content: "\f15c";
    position: absolute;
    font-weight: 900;
    font-size: 44px;
    top: 8%;
}
        #privacy.page-desc-section .page-desc:before {

    font-family: 'FontAwesome';
    content: "\f023";
    position: absolute;
    font-weight: 900;
    font-size: 44px;
    top: 8%;
}
        .rtopts{
            margin-top: 10px;

font-size: 12px;
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
<!-- Page Loader -->
<div id="pageloader">
	<div class="loader-item fa fa-spin colored-border"></div>
</div>
<!-- Header Top section -->
<div class="header-top">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Top Navbar Begins -->
				<div  class="navbar">
					<!-- Mail and Phone Number -->
					<div class="navbar-header">
						<ul class="header-top-left">
							<li> <a href="mailto:<?php echo $cdata['helpmail'] ?>"> <i class="flaticon-black164"></i> <?php echo $cdata['helpmail'] ?> </a> </li>
							
						</ul>
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <i class="flaticon-list50"></i> </button>
					</div>
					<!-- Social Icons -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="header-top-left" style="float:right !important;">
							<li> <a href="javascript:void(0);"> <i class="flaticon-phone46"></i> <?php echo $cdata['helpline'] ?> </a> </li>
							
						</ul>
						
					</div>
				</div>
				<!-- Top Navbar Ends -->
			</div>
		</div>
	</div>
</div>
<!-- Header Top section Ends -->
<!-- Header Begins -->
<div id="sticky-section" class="sticky-navigation">
	<nav class="navbar navbar-default menu-bar" role="navigation">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<!-- Logo and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<div class="site-logo"> 
                            <a href="<?php echo Doo::conf()->APP_URL ?>" id="logo">
                        <img class="responsive-img" src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" style="max-height:55px;" alt="">
                            </a>
                            
                        </div>
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2"> <span class="menu-box"><span class="menu">Menu</span><i class="flaticon-list50 menu-button"></i></span> </button>
					</div>
					<!-- Collect the nav links, buttons and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
						<ul class="nav navbar-nav navbar-right ">
				          <li> <a class="<?php if($data['pdata']->page_type=='HOME'){ ?> active <?php } ?>" href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT('Home')?></a>
                          </li>
                          <li> <a class="<?php if($data['pdata']->page_type=='ABOUT'){ ?> active <?php } ?>" href="<?php echo Doo::conf()->APP_URL ?>web/about"><?php echo SCTEXT('About')?></a>
                          </li>
                          <li> <a class="<?php if($data['pdata']->page_type=='PRICING'){ ?> active <?php } ?>" href="<?php echo Doo::conf()->APP_URL ?>web/pricing"><?php echo SCTEXT('Pricing')?></a>
                          </li>
                          <li> <a class="<?php if($data['pdata']->page_type=='CONTACT'){ ?> active <?php } ?>" href="<?php echo Doo::conf()->APP_URL ?>web/contact-us"><?php echo SCTEXT('Contact')?></a>
                          </li>
                          <li> <a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"><?php echo SCTEXT('Login')?></a>
                          </li>
						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</div>
			</div>
		</div>
		<!-- /.container -->
	</nav>
	<!-- Header Ends -->
</div>