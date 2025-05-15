<?php
$pdata = unserialize(base64_decode($data['pdata']->page_data));
//echo '<pre>';var_dump($pdata);die;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $pdata['title'] ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $pdata['metadesc'] ?>">
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
    <div class="simple-page-wrap text-dark">
        
        <div class="simple-page-form animated flipInY" id="login-form">
            
            <div class="simple-page-logo animated swing">
                <a href="<?php echo Doo::conf()->APP_URL ?>">
                    <img src="<?php echo Doo::conf()->APP_URL.Doo::conf()->image_upload_url.'logos/'.$_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" />
                </a>
            </div>
            
            <h4 class="form-title m-b-xl text-center"><?php echo SCTEXT('Reset Password')?></h4>
            
             <?php include('notification.php') ?>
            
            <form method="post" id="rpform" action="<?php echo Doo::conf()->APP_URL ?>passwordReset">
                <input type="hidden" name="cat" value="fp">
                <div class="form-group">
                    <input id="emailid" name="emailid" type="text" class="form-control" placeholder="<?php echo SCTEXT('Enter your Email ID')?>">
                </div>
                
                <input type="submit" class="btn btn-primary" id="login-submit" value="<?php echo SCTEXT('Reset My Password')?>">
            </form>
        </div>
        <div class="simple-page-footer">
            <p class="m-b-sm"><a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"> <i class="fa fa-chevron-left m-r-xs"></i> <?php echo SCTEXT('Back to Login Page')?></a></p>
          
        </div>
    </div>
</body>

</html>
<!-- Localized -->