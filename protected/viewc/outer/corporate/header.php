<?php 
$pdata = unserialize(base64_decode($data['pdata']->page_data));
$cdata = unserialize($_SESSION['webfront']['company_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $pdata['metadesc'] ?>">
<title><?php echo $pdata['title'] ?></title>
 <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/html5shiv.js"></script>
    <![endif]-->
<link rel="stylesheet" type="text/css" id="color" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/css/<?php echo $data['skin']['color'] ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/css/style.css">
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/css/bootstrap-responsive.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery1.7.2.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery-migrate-1.2.1.min.js"></script>
<!--Revolution slider start here-->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery.themepunch.revolution.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/rskins/corporate/assets/js/preview-fullwidth.js"></script>
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
    a.red:visited{
        color: <?php echo $data['skin']['color'] ?>;
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

    

<div class="slide" id="slide1" data-slide="1" data-stellar-background-ratio="0.5">
  <header class="header">
    <div class="top_row">
      <div class="container">
        <section class="row-fluid">
          <div class="span12">
            <p class="top_mail"><i class="icon-envelope"></i><a href="mailto:<?php echo $cdata['helpmail'] ?>"><?php echo $cdata['helpmail'] ?></a></p>
            <p class="top_number"><i class="icon-phone"></i><?php echo $cdata['helpline'] ?></p>
            <div class="clr"></div>
          </div>
          <!--end span12-->
          
          <div class="clr"></div>
        </section>
        <!--end row-fluid-->
        
        <div class="clr"></div>
      </div>
      <!--end container-->
      
      <div class="clr"></div>
    </div>
    <!--end top_row-->
    
    <nav>
      <div class="navigation_bg">
        <div class="container">
          <div class="row-fluid">
            <div class="span3" style="padding-top:10px;">
              <a href="<?php echo Doo::conf()->APP_URL ?>" id="logo">
                        <img class="responsive-img" src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" style="max-height:57px;" alt="">
                    </a>
              </div>
            <div class="span9"> 
              
              <!-- Navbar
================================================== -->
              <div class="navbar">
                <div class="navbar-inner">
                  <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    <div class="nav-collapse collapse">
                      <ul class="nav">
                        <li class="<?php if($data['pdata']->page_type=='HOME'){ ?> active <?php } ?>"> <a href="<?php echo Doo::conf()->APP_URL ?>"><?php echo SCTEXT('Home')?><br />
                          <span><?php echo SCTEXT('Our Business')?></span></a>
                          
                        </li>
                          <li class="<?php if($data['pdata']->page_type=='ABOUT'){ ?> active <?php } ?>"> <a href="<?php echo Doo::conf()->APP_URL ?>web/about"><?php echo SCTEXT('About')?><br />
                          <span><?php echo SCTEXT('All about us')?></span></a>
                          
                        </li>
                          <li class="<?php if($data['pdata']->page_type=='PRICING'){ ?> active <?php } ?>"> <a href="<?php echo Doo::conf()->APP_URL ?>web/pricing"><?php echo SCTEXT('Pricing')?><br />
                          <span><?php echo SCTEXT('Our SMS Charges')?></span></a>
                          
                        </li>
                          <li class="<?php if($data['pdata']->page_type=='CONTACT'){ ?> active <?php } ?>"> <a href="<?php echo Doo::conf()->APP_URL ?>web/contact-us"><?php echo SCTEXT('Contact')?><br />
                          <span><?php echo SCTEXT('Get in touch')?></span></a>
                          
                        </li>
                          <li class=""> <a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"><?php echo SCTEXT('Login')?><br />
                          <span><?php echo SCTEXT('Start here')?></span></a>
                          
                        </li>
                        
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="clr"></div>
          </div>
          <!--end row-fluid-->
          
          <div class="clr"></div>
        </div>
        <!--end container-->
        
        <div class="clr"></div>
      </div>
    </nav>
    <!--end navigation_bg-->
    
    <div class="clr"></div>
  </header>
  <!--end header-->
    
    
    
