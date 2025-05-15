<?php
$pdata = unserialize(base64_decode($data['pdata']->page_data));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SMS PANEL | <?php echo SCTEXT('Page Not Found') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="">
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/core.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/misc-pages.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
</head>

<body class="simple-page <?php echo $pdata['theme'] ?>">
    <div id="back-to-home"><a href="<?php echo Doo::conf()->APP_URL ?>" class="btn btn-outline btn-default"><i class="fa fa-home fa-2x animated zoomIn"></i></a></div>
    <div class="simple-page-wrap">
        <div class="simple-page-logo animated swing">
            <a href="<?php echo Doo::conf()->APP_URL ?>">
                    <img src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" />
                </a>
        </div>
        <h1 id="_404_title" class="animated shake">404</h1>
        <h5 id="_404_msg" class="animated slideInUp"><?php echo SCTEXT("The page you're looking for isn't here")?> </h5>
    </div>
</body>

</html>
<!-- Localized -->