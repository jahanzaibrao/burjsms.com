<?php
$pdata = unserialize(base64_decode($data['pdata']->page_data));
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?php echo $pdata['title'] ?></title>
    <meta name="description" content="<?php echo $pdata['metadesc'] ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/bootstrap.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/flexslider.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/prettyPhoto.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/mediaelementplayer.min.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/superfish.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/bra_social_media.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/component.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/theme-options.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/icoMoon.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/iview.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/revolution-slider.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/fullwidth.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/style.css" media="all" />
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/bootstrap-responsive.css" media="all">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/responsive.css" media="all">

    <!--[if lt IE 9]>
            <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/ie.css" type="text/css" media="all" />		
    <![endif]-->

    <!-- Favicons
        ================================================== -->
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>">

    <!-- Style
	================================================== -->
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/skin/<?php echo $data['skin']['color'] ?>.css" type="text/css" id="colors" />
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/css/skin/<?php echo $data['skin']['color'] ?>.css" type="text/css" id="templates" />

    <!-- Font
            ================================================== -->
    <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,300,300italic,200italic,400italic,600italic,600,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        <?php if ($_SESSION['APP_LANG'] == 'th') { ?>body {
            font-family: 'Prompt', sans-serif !important;
            font-weight: 300 !important;
            font-style: normal;
        }

        h2 {
            font-family: 'Prompt', sans-serif !important;
        }

        <?php } ?>.rtblock {
            box-shadow: 0 9px 5px -2px #ccc;
            margin: 10px 0 15px;
            padding: 10px;
        }

        form#contact-form input,
        textarea {
            color: #fff;
        }

        #mycontactbox {
            padding-top: 6%;
        }

        .disabledBox {
            opacity: 0.3;
            pointer-events: none;
            cursor: not-allowed;
        }

        @media (max-width:768px) {
            #mycontactbox {
                padding-top: 14%;
            }
        }
    </style>
    <script>
        var app_url = '<?php echo Doo::conf()->APP_URL ?>';
        var sctext = [];
        var app_currency = '<?php echo Doo::conf()->currency ?>';
        var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
    </script>
</head>

<body class="dark-footer flx-home-page-4">


    <header id="flx-header" class="flx-divider">

        <div class="wrapper">

            <div class="row-fluid">

                <div class="span12 clearfix">

                    <div id="logo-image">

                        <a href="<?php echo Doo::conf()->APP_URL ?>"><img src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" /></a>

                    </div><!--end:logo-image-->

                    <nav id="main-nav">

                        <ul id="main-menu" class="clearfix">

                            <li <?php if ($data['pdata']->page_type == 'HOME') { ?> class="current-menu-item" <?php } ?>>
                                <a href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT('Home') ?></a>
                            </li>

                            <li <?php if ($data['pdata']->page_type == 'ABOUT') { ?> class="current-menu-item" <?php } ?>>
                                <a href="<?php echo Doo::conf()->APP_URL . 'web/about' ?>"><?php echo SCTEXT('About Us') ?></a>
                            </li>

                            <li <?php if ($data['pdata']->page_type == 'PRICING') { ?> class="current-menu-item" <?php } ?>>
                                <a href="<?php echo Doo::conf()->APP_URL . 'web/pricing' ?>"><?php echo SCTEXT('Our Pricing') ?></a>
                            </li>

                            <li <?php if ($data['pdata']->page_type == 'CONTACT') { ?> class="current-menu-item" <?php } ?>>
                                <a href="<?php echo Doo::conf()->APP_URL . 'web/contact-us' ?>"><?php echo SCTEXT('Contact Us') ?></a>
                            </li>

                            <li>
                                <a href="<?php echo Doo::conf()->APP_URL . 'web/sign-in' ?>"><?php echo SCTEXT('Login') ?></a>
                            </li>

                        </ul><!--end:main-menu-->

                        <div id="dl-menu" class="dl-menuwrapper">
                            <button>Open Menu</button>
                            <ul class="dl-menu">
                                <li <?php if ($data['pdata']->page_type == 'HOME') { ?> class="current-menu-item" <?php } ?>>
                                    <a href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT('Home') ?></a>
                                </li>

                                <li <?php if ($data['pdata']->page_type == 'ABOUT') { ?> class="current-menu-item" <?php } ?>>
                                    <a href="<?php echo Doo::conf()->APP_URL . 'web/about' ?>"><?php echo SCTEXT('About Us') ?></a>
                                </li>

                                <li <?php if ($data['pdata']->page_type == 'PRICING') { ?> class="current-menu-item" <?php } ?>>
                                    <a href="<?php echo Doo::conf()->APP_URL . 'web/pricing' ?>"><?php echo SCTEXT('Our Pricing') ?></a>
                                </li>

                                <li <?php if ($data['pdata']->page_type == 'CONTACT') { ?> class="current-menu-item" <?php } ?>>
                                    <a href="<?php echo Doo::conf()->APP_URL . 'web/contact-us' ?>"><?php echo SCTEXT('Contact Us') ?></a>
                                </li>

                                <li>
                                    <a href="<?php echo Doo::conf()->APP_URL . 'web/sign-in' ?>"><?php echo SCTEXT('Login') ?></a>
                                </li>
                            </ul>
                        </div><!-- /dl-menuwrapper -->

                    </nav><!--end:main-nav-->

                </div><!--end:span12-->

            </div><!--end:row-fluid-->

        </div><!--end:wrapper-->

    </header><!--end:flx-header-->