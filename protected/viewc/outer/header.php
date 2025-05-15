<!DOCTYPE html>

<!--[if IE 7]>                  <html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<title><?php if($data['content']){ echo $data['content']->page_title;}else{echo Doo::conf()->global_page_title;} ?></title>
	
	<meta name="description" content="<?php echo $data['content']->meta_desc ?>">
	<meta name="keywords" content="<?php echo $data['content']->meta_kw ?>">
	
	<!--[if !lte IE 6]><!-->
		<link rel="stylesheet" href="<?php echo $data['baseurl'] ?>global/white/css/style.css" media="screen" />

		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,300,800,700,400italic|PT+Serif:400,400italic" />
		
		<link rel="stylesheet" href="<?php echo $data['baseurl'] ?>global/white/css/fancybox.min.css" media="screen" />

		<link rel="stylesheet" href="<?php echo $data['baseurl'] ?>global/white/css/video-js.min.css" media="screen" />

		<link rel="stylesheet" href="<?php echo $data['baseurl'] ?>global/white/css/audioplayerv1.min.css" media="screen" />
	<!--<![endif]-->

	<!--[if lte IE 6]>
		<link rel="stylesheet" href="//universal-ie6-css.googlecode.com/files/ie6.1.1.css" media="screen, projection">
	<![endif]-->

	<!-- HTML5 Shiv + detect touch events -->
	<script src="<?php echo $data['baseurl'] ?>global/white/js/modernizr.custom.js"></script>
    <script>
    var app_url='/shyam/app/';
    </script>
    
</head>
<body>

<header id="header" class="container clearfix">

	<a href="<?php echo $data['baseurl'] ?>" id="logo">
		<img style="height:100%" src="<?php if($_SESSION['site']['meta']['logo_path']!=''){ ?><?php echo $data['baseurl'] ?><?php echo $_SESSION['site']['meta']['logo_path'];}else{ ?><?php echo $data['baseurl'] ?>global/img/logo_here.jpg<?php } ?>" alt="<?php echo $_SESSION['site']['meta']['company_name'] ?>">
	</a>

	<nav id="main-nav">
		
		<ul>
			<li <?php if($data['content']->page_type=='HOME'){ ?> class="current" <?php } ?>>
				<a href="<?php echo $data['baseurl'] ?>" data-description="<?php echo SCTEXT("Our Business") ?>"><?php echo SCTEXT("Home") ?></a>
				
			</li>
			<li<?php if($data['content']->page_type=='ABOUT'){ ?> class="current" <?php } ?>>
				<a href="<?php echo $data['baseurl'] ?>site/about-us" data-description="<?php echo SCTEXT("All about us") ?>"><?php echo SCTEXT("About") ?></a>

			</li>
			<li<?php if($data['content']->page_type=='PRICING'){ ?> class="current" <?php } ?>>
				<a href="<?php echo $data['baseurl'] ?>site/pricing" data-description="<?php echo SCTEXT("Our SMS Charges") ?>"><?php echo SCTEXT("Pricing") ?></a>
				
			</li>
			<li<?php if($data['content']->page_type=='CONTACT'){ ?> class="current" <?php } ?>>
				<a href="<?php echo $data['baseurl'] ?>site/contact" data-description="<?php echo SCTEXT("Get in touch") ?>"><?php echo SCTEXT("Contact") ?></a>
				
			</li>
			<li>
				<a href="<?php echo $data['baseurl'] ?>sign-in" data-description="<?php echo SCTEXT("Start Here") ?>"><?php echo SCTEXT("Login") ?></a>
			</li>
		</ul>

	</nav><!-- end #main-nav -->
	
</header><!-- end #header -->