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
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/core.css">
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/misc-pages.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <script>
        var app_url = '<?php echo Doo::conf()->APP_URL ?>';
        var app_lang = '<?php echo $_SESSION['APP_LANG'] ?>';
        var app_currency = '<?php echo Doo::conf()->currency ?>';
        var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
        var crecountrule = new Object();
        var defTheme = <?php echo $_SESSION['webfront']['intheme'] != '' ? '"' . $_SESSION['webfront']['intheme'] . '"' : 'danger' ?>;
    </script>
</head>

<body class="simple-page <?php echo $pdata['theme'] ?>">
    <div id="back-to-home"><a href="<?php echo Doo::conf()->APP_URL ?>" class="btn btn-outline btn-default"><i class="fa fa-home fa-2x animated zoomIn"></i></a></div>
    <div class="simple-page-wrap text-dark">

        <?php if ($_SESSION['verifiedUser']['mode'] == 'verified') {
            // reset password page
        ?>

            <div class="simple-page-form animated flipInY" id="login-form">

                <div class="simple-page-logo animated swing">
                    <a href="<?php echo Doo::conf()->APP_URL ?>">
                        <img src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" />
                    </a>
                </div>

                <h4 class="form-title m-b-xl text-center"><?php echo SCTEXT('Reset Password') ?></h4>

                <?php include('notification.php') ?>

                <form method="post" id="vurpform" action="<?php echo Doo::conf()->APP_URL ?>resetOuterVerifiedPassword">
                    <div class="form-group m-b-sm">
                        <div class="media">
                            <div class="media-left">
                                <div class="avatar avatar-sm avatar-circle">
                                    <a href="javascript:void(0);">
                                        <img src="<?php echo $_SESSION['verifiedUser']['avatar'] ?>" alt="Pic">
                                    </a>
                                </div>
                            </div>
                            <div class="media-body">
                                <h5 class="m-t-xs m-b-0">
                                    <a href="javascript:void(0);" class="m-r-xs theme-color"><?php echo $_SESSION['verifiedUser']['name']; ?></a>
                                    <small class="text-muted fz-sm"><?php echo strtoupper($_SESSION['verifiedUser']['category']) ?></small>
                                </h5>
                                <p style="font-size: 12px;font-style: Italic;"><?php echo $_SESSION['verifiedUser']['email'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                        <input type="password" data-strength="<?php echo Doo::conf()->password_strength ?>" class="form-control" placeholder="<?php echo SCTEXT('enter new password') ?> ..." name="newpass1" id="newpass1" maxlength="100" />

                    </div>
                    <div class="form-group">

                        <input type="password" data-strength="<?php echo Doo::conf()->password_strength ?>" class="form-control" placeholder="<?php echo SCTEXT('verify new password') ?> ..." name="newpass2" id="newpass2" maxlength="100" />
                        <span id="pass-err" class="help-block text-danger"></span>
                        <br>
                        <span id="pass-help" class="help-block text-primary">
                            <?php switch (Doo::conf()->password_strength) {
                                case 'weak':
                                    echo SCTEXT('Password length should be minimum 6 characters.');
                                    break;

                                case 'average':
                                    echo SCTEXT('Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.');
                                    break;

                                case 'strong':
                                    echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.');
                                    break;
                            }
                            ?>
                        </span>

                    </div>

                    <input type="submit" class="btn btn-primary" id="submitvrp" value="<?php echo SCTEXT('Save New Password') ?>">
                </form>
            </div>


        <?php } else {

            //OTP verification page
        ?>

            <div class="simple-page-form animated flipInY" id="login-form">

                <div class="simple-page-logo animated swing">
                    <a href="<?php echo Doo::conf()->APP_URL ?>">
                        <img src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" />
                    </a>
                </div>

                <h4 class="form-title m-b-xl text-center"><?php echo SCTEXT('Account Verification') ?></h4>

                <?php include('notification.php') ?>

                <form method="post" id="rpotpform" action="<?php echo Doo::conf()->APP_URL ?>verifyResetPassOtp">
                    <div class="form-group m-b-sm">
                        <div class="media">
                            <div class="media-left">
                                <div class="avatar avatar-sm avatar-circle">
                                    <a href="javascript:void(0);">
                                        <img src="<?php echo $_SESSION['rpvars']['avatar'] ?>" alt="Pic">
                                    </a>
                                </div>
                            </div>
                            <div class="media-body">
                                <h5 class="m-t-xs m-b-0">
                                    <a href="javascript:void(0);" class="m-r-xs theme-color"><?php echo $_SESSION['rpvars']['name']; ?></a>

                                </h5>
                                <p style="font-size: 12px;font-style: Italic;"><?php echo $_SESSION['rpvars']['email'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input id="rpotp" name="rpotp" type="text" maxlength="6" class="form-control" placeholder="<?php echo SCTEXT('Enter 6 digit OTP') ?> ...">
                    </div>

                    <input type="submit" class="btn btn-primary" id="" value="<?php echo SCTEXT('Validate Account') ?>">
                </form>
            </div>
            <div class="simple-page-footer">
                <p class="m-b-sm"><a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"> <i class="fa fa-chevron-left m-r-xs"></i> <?php echo SCTEXT('Back to Login Page') ?></a></p>

            </div>

        <?php } ?>


    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/js/outer_lang.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            if ($("#newpass1").length > 0) {
                //match passwords
                var errpass = 0;
                $("#newpass1, #newpass2").on("keyup blur", function() {
                    var mode = $(this).attr('data-strength');
                    var val = $(this).val();

                    if (mode == 'weak') {
                        //length
                        if (val.length < 6) {
                            errpass = 1;
                            $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password too short'));
                        } else {
                            errpass = 0;
                            $("#pass-err").text('');
                        }
                    }

                    if (mode == 'average') {
                        //length
                        if (val.length < 8) {
                            errpass = 1;
                            $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password too short'));
                        } else {
                            errpass = 0;
                            $("#pass-err").text('');
                            //alphabet letter
                            if (!/[a-zA-Z]/.test(val)) {
                                errpass = 1;
                                $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password must have at least one alphabet letter.'));
                            } else {
                                errpass = 0;
                                $("#pass-err").text('');
                                //numeric
                                if (!/[0-9]/.test(val)) {
                                    errpass = 1;
                                    $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password must have at least one numeric character.'));
                                } else {
                                    errpass = 0;
                                    $("#pass-err").text('');
                                }

                            }

                        }
                    }

                    if (mode == 'strong') {
                        //length
                        if (val.length < 8) {
                            errpass = 1;
                            $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password too short'));
                        } else {
                            errpass = 0;
                            $("#pass-err").text('');
                            //uppercase alphabet
                            if (!/[A-Z]/.test(val)) {
                                errpass = 1;
                                $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password must have at least one uppercase letter.'));
                            } else {
                                errpass = 0;
                                $("#pass-err").text('');
                                //numeric
                                if (!/[0-9]/.test(val)) {
                                    errpass = 1;
                                    $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password must have at least one numeric character.'));
                                } else {
                                    errpass = 0;
                                    $("#pass-err").text('');
                                    //special characters
                                    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val)) {
                                        errpass = 1;
                                        $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Password must have at least one special character.'));
                                    } else {
                                        errpass = 0;
                                        $("#pass-err").removeClass('text-danger').addClass('text-success').text(SCTEXT('Password is acceptable'));
                                    }
                                }

                            }

                        }


                    }
                    if (errpass == 0) {
                        //if everything is good match both passwords
                        if ($('#newpass1').val() != $('#newpass2').val()) {
                            errpass = 1;
                            $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Passwords do not match each other. Please re-type your password'));
                        } else {
                            errpass = 0;
                            $("#pass-err").removeClass('text-danger').addClass('text-success').text(SCTEXT('Password is acceptable'));
                        }
                    }
                });

                //submit
                $("#submitvrp").click(function() {
                    if ($("#newpass1").val() == '') {
                        errpass = 1;
                        $("#pass-err").removeClass('text-success').addClass('text-danger').text(SCTEXT('Passwords cannot be blank.'));
                        return false;
                    }
                    if (errpass != 0) {
                        return false;
                    }
                })

            }

        })
    </script>
</body>

</html>
<!-- Localized -->