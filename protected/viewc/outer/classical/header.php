<?php 
$pdata = unserialize(base64_decode($data['pdata']->page_data));
$cdata = unserialize($_SESSION['webfront']['company_data']);
?>
<!DOCTYPE html>

<!--[if IE 7]>                  <html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<title><?php echo $pdata['title'] ?></title>
	<meta name="description" content="<?php echo $pdata['metadesc'] ?>" />
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>">
	
	<!--[if !lte IE 6]><!-->
		<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/css/style-<?php echo $data['skin']['color'] ?>.css" media="screen" />

		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,300,800,700,400italic|PT+Serif:400,400italic" />
		
		<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/css/fancybox.min.css" media="screen" />

		<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/css/video-js.min.css" media="screen" />

		<link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/css/audioplayerv1.min.css" media="screen" />
	<!--<![endif]-->

	<!--[if lte IE 6]>
		<link rel="stylesheet" href="//universal-ie6-css.googlecode.com/files/ie6.1.1.css" media="screen, projection">
	<![endif]-->

	<!-- HTML5 Shiv + detect touch events -->
	<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/classical/js/modernizr.custom.js"></script>
    <style>
        #content{
            font-size: 12px;
        }
.table-bordered {
    border: 1px solid #dee2e6;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
        .table td, .table th {
    padding: .50rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
        .table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
}
        .rtblock{
             box-shadow: 0 9px 5px -2px #ccc;
    margin: 10px 0 15px;
    padding: 10px;
        }
        .add-on{
display: inline-block;
padding: 1px 4px 4px 4px;
border: 1px solid #e8e8e8;
font-size: 16px;
font-weight: bold;
line-height: 34px;
margin-right: -4px;
	}
.tgwbox {
	width:40%;
	float:right;
	}	
#mobile{
	width:135px;
	background-color:#fff;
	padding:4px 10px;
	}	
@media only screen and (min-width: 480px) and (max-width: 767px) {
	.tgwbox {
	width:100%;
	float:right;
	}
}

@media only screen and (max-width: 479px) {
	.tgwbox {
	width:100%;
	float:right;
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

<header id="header" class="container clearfix">

	<a href="<?php echo Doo::conf()->APP_URL ?>" id="logo">
                        <img class="responsive-img" src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" style="max-height:100%;" alt="">
                    </a>

	<nav id="main-nav">
		
		<ul>
			<li <?php if($data['pdata']->page_type=='HOME'){ ?> class="current" <?php } ?>>
				<a href="<?php echo Doo::conf()->APP_URL ?>" data-description="<?php echo SCTEXT("Our Business") ?>"><?php echo SCTEXT("Home") ?></a>
				
			</li>
			<li<?php if($data['pdata']->page_type=='ABOUT'){ ?> class="current" <?php } ?>>
				<a href="<?php echo Doo::conf()->APP_URL ?>web/about" data-description="<?php echo SCTEXT("All about us") ?>"><?php echo SCTEXT("About") ?></a>

			</li>
			<li<?php if($data['pdata']->page_type=='PRICING'){ ?> class="current" <?php } ?>>
				<a href="<?php echo Doo::conf()->APP_URL ?>web/pricing" data-description="<?php echo SCTEXT("Our SMS Charges") ?>"><?php echo SCTEXT("Pricing") ?></a>
				
			</li>
			<li<?php if($data['pdata']->page_type=='CONTACT'){ ?> class="current" <?php } ?>>
				<a href="<?php echo Doo::conf()->APP_URL ?>web/contact-us" data-description="<?php echo SCTEXT("Get in touch") ?>"><?php echo SCTEXT("Contact") ?></a>
				
			</li>
			<li>
				<a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in" data-description="<?php echo SCTEXT("Start Here") ?>"><?php echo SCTEXT("Login") ?></a>
			</li>
		</ul>

	</nav>
</header><!-- end #header -->