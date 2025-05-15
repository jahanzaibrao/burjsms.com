<?php
$pdata = unserialize(base64_decode($data['pdata']->page_data));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $pdata['title'] ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $pdata['metadesc'] ?>">
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/core.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/misc-pages.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        <?php if ($_SESSION['APP_LANG'] == 'th') { ?>body {
            font-family: 'Prompt', sans-serif !important;
            font-weight: 300 !important;
            font-style: normal;
        }

        <?php } ?>img#refresh {
            float: left;
            margin-top: 45px;
            margin-left: 4px;
            cursor: pointer;
        }

        #wrap {
            border: solid #CCCCCC 1px;
            width: 203px;
            -webkit-border-radius: 10px;
            float: left;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding: 3px;
            margin-top: 3px;
            margin-left: 80px;
        }

        #code {
            border: 1px solid #ccc;
            margin: 8px;
            padding: 10px;
            max-width: 150px;
        }

        #back-to-home {
            padding: 0 0 0 2%;
        }

        #back-to-home a:hover #bthome {
            color: #fff !important;
        }

        #rc-anchor-container {
            background: transparent !important;
            box-shadow: 1px 10px 5px 1px #ffdf74;
        }

        #tp-mainbox {
            background: transparent;
        }

        @media (min-width: 1200px) {
            #tp-mainbox {
                margin: -2% 5% 0 70%;
            }
        }

        @media screen and (min-aspect-ratio: 16/9) and (min-width: 1920px) and (min-height: 1080px) {
            body {
                transform: scale(1.2);
                /* Scale the app to 120% */
                transform-origin: top left;
                /* Keep scaling centered from the top-left */
            }
        }
    </style>
</head>

<body class="simple-page warning" style="background-image: url('/global/img/ntplc-enterprise.png');background-repeat: no-repeat;background-color: #40c1ac;">
    <div id="back-to-home"><a href="<?php echo Doo::conf()->APP_URL ?>" class="btn btn-outline btn-warning"><i id="bthome" class="fa fa-home fa-2x animated zoomIn text-warning"></i></a></div>
    <div id="tp-mainbox" class="simple-page-wrap text-dark">

        <div class="simple-page-form animated flipInY" id="login-form" style="background: rgba(255, 255, 255, 0.46);border-radius: 16px;box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);backdrop-filter: blur(9.8px);-webkit-backdrop-filter: blur(9.8px);border: 1px solid rgba(255, 255, 255, 0.3);">

            <div class="simple-page-logo animated swing">
                <a href="<?php echo Doo::conf()->APP_URL ?>">
                    <img style="max-width: 60%;" src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>" alt="" />
                </a>
            </div>

            <h4 class="form-title m-b-xl text-center"><?php echo SCTEXT('Sign In With Your Account') ?></h4>

            <?php include('notification.php') ?>

            <form method="post" id="loginform" action="">
                <div class="form-group">
                    <input id="loginid" name="loginid" type="text" class="form-control" placeholder="<?php echo SCTEXT('Login ID') ?>">
                </div>
                <div class="form-group">
                    <input id="upassword" name="upassword" type="password" class="form-control" placeholder="<?php echo SCTEXT('Password') ?>">
                </div>



                <div style="margin: 0 auto !important;">
                    <div class="g-recaptcha" data-size="normal" style="margin: 0 auto !important; transform:scale(0.85);transform-origin:0 0; width:85%" data-sitekey="<?php echo Doo::conf()->recaptcha_site_id ?>"></div>
                </div>

                <br clear="all" /><br clear="all" />


                <?php if (Doo::conf()->smd_tnc_flag == 1) { ?>
                    <div class="checkbox checkbox-info">
                        <input type="checkbox" name="tncflag" id="cb-tnc" value="1">
                        <label for="cb-tnc"><?php echo SCTEXT('I Agree to the') ?> <a target="_blank" href="<?php echo Doo::conf()->custom_tnc_url == '' ? Doo::conf()->APP_URL . 'web/terms' : Doo::conf()->custom_tnc_url ?>"> <?php echo SCTEXT('Terms & Conditions') ?> </a> </label>
                    </div>
                <?php } ?>
                <input type="button" class="btn btn-warning text-inverse m-b-xl" id="login-submit" value="<?php echo SCTEXT('SIGN IN') ?>">
                <hr class="m-h-sm">
                <script src="https://accounts.google.com/gsi/client" async defer></script>

                <div style="display:block; text-align:center; padding: 0 20%;">
                    <div id="g_id_onload" data-client_id="<?php echo Doo::conf()->gcp_client_id ?>" data-context="signin" data-ux_mode="redirect" data-login_uri="<?php echo Doo::conf()->APP_URL ?>auth/authService" data-callback="gsignin" data-nonce="" data-itp_support="true">
                    </div>
                    <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="filled_blue" data-text="signin_with" data-size="large" data-logo_alignment="left">
                    </div>

                </div>
            </form>
        </div>
        <div class="simple-page-footer">
            <p class="m-b-sm"><a href="<?php echo Doo::conf()->APP_URL ?>web/resetPassword"><?php echo SCTEXT('FORGOT YOUR PASSWORD') ?>?</a></p>
            <?php if ($pdata['regflag'] == '1') { ?>
                <p><small><?php echo SCTEXT("Don't have an account") ?>?</small> <a href="<?php echo Doo::conf()->APP_URL ?>web/sign-up"><?php echo SCTEXT('CREATE AN ACCOUNT') ?></a></p>
            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function gsignin(gdata) {
            console.log(gdata)
        }
        $(document).ready(function() {
            $("#login-submit").on("click", function() {
                $(this).attr("disabled", true).val('Logging in securely...');
                $("#loginform").attr("action", '<?php echo Doo::conf()->APP_URL ?>auth/authService').submit()
            })
        })
    </script>
</body>

</html>

<!-- Localized -->