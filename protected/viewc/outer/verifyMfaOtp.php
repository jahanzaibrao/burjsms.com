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
  <link href="<?php echo Doo::conf()->APP_URL ?>global/assets/local/rawline.css" rel="stylesheet">
  <script>
    var app_url = '<?php echo Doo::conf()->APP_URL ?>';
    var app_lang = '<?php echo $_SESSION['APP_LANG'] ?>';
    var app_currency = '<?php echo Doo::conf()->currency ?>';
    var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
    var crecountrule = new Object();
    var defTheme = <?php echo $_SESSION['webfront']['intheme'] != '' ? '"' . $_SESSION['webfront']['intheme'] . '"' : 'primary' ?>;
  </script>
</head>

<body class="simple-page <?php echo $pdata['theme'] ?>">
  <div id="back-to-home"><a href="<?php echo Doo::conf()->APP_URL ?>" class="btn btn-outline btn-default"><i class="fa fa-home fa-2x animated zoomIn"></i></a></div>
  <div class="simple-page-wrap text-dark">


    <div class="simple-page-form animated flipInY" id="login-form">

      <div class="simple-page-logo animated swing">
        <a href="<?php echo Doo::conf()->APP_URL ?>">
          <img src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" />
        </a>
      </div>

      <h3 class="form-title m-b-md text-center"><?php echo SCTEXT('Account Verification') ?></h3>

      <?php if (Doo::conf()->tfa_auth_mode == 2) { ?>
        <h5 class="m-b-xl text-center">Enter OTP sent to your email and mobile number</h5>
      <?php } else { ?>
        <h5 class="m-b-xl text-center">Enter OTP sent to your Email </h5>
        <h6>Make sure to check Spam/Junk folder as well</h6>
      <?php } ?>


      <?php include('notification.php') ?>

      <form method="post" id="mfaotpform" action="<?php echo Doo::conf()->APP_URL ?>auth/authService">
        <div class="form-group">
          <div class="crd">
            <input autocomplete="off" class="code" type="text" onclick="this.value = ''" oninput="onlyNumbers(this)" onkeyup="nextInput(1)" maxlength="1" name="mfaotp[]" placeholder="..." />
            <input autocomplete="off" class="code" type="text" onclick="this.value = ''" oninput="onlyNumbers(this)" onkeyup="nextInput(2)" maxlength="1" name="mfaotp[]" placeholder="..." />
            <input autocomplete="off" class="code" type="text" onclick="this.value = ''" oninput="onlyNumbers(this)" onkeyup="nextInput(3)" maxlength="1" name="mfaotp[]" placeholder="..." />
            <input autocomplete="off" class="code" type="text" onclick="this.value = ''" oninput="onlyNumbers(this)" onkeyup="nextInput(4)" maxlength="1" name="mfaotp[]" placeholder="..." />
            <input autocomplete="off" class="code" type="text" onclick="this.value = ''" oninput="onlyNumbers(this)" onkeyup="nextInput(5)" maxlength="1" name="mfaotp[]" placeholder="..." />
            <input autocomplete="off" class="code" type="text" onclick="this.value = ''" oninput="onlyNumbers(this)" onkeyup="nextInput(6)" maxlength="1" name="mfaotp[]" placeholder="..." />

            <div class="resend-otp">
              <a id="actionTimer"> Resend OTP <span id="timer">59</span></a>
              <span id="otpmsgs" class="help-block"></span>
            </div>
          </div>
        </div>

        <input type="button" class="btn btn-primary" id="submitotp" value="<?php echo SCTEXT('Verify') ?>">
      </form>
    </div>
    <div class="simple-page-footer">
      <p class="m-b-sm"><a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"> <i class="fa fa-chevron-left m-r-xs"></i> <?php echo SCTEXT('Back to Login Page') ?></a></p>

    </div>

  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="<?php echo Doo::conf()->APP_URL ?>global/js/outer_lang.js"></script>
  <script type="text/javascript">
    function onlyNumbers(input) {
      input.value = input.value.replace(/[^0-9]/g, "");
    }

    function nextInput(val) {
      var input = document.getElementsByClassName("code");
      try {
        if (input[val - 1].value != "") {
          input[val].focus();
        }
      } catch (error) {
        console.log(error)
      }
    }
    var time;

    function resetTimer() {
      var timer = $("#timer");
      var actionTimer = $("#actionTimer");
      if (!time) {
        time = 59;
      }
      actionTimer
        .css({
          cursor: "not-allowed",
          color: "#EF3A3A"
        })
        .attr("onclick", false);
      timer.text(`(${time}s)`);
      var intervalTimer = setInterval(function() {
        time -= 1;
        timer.text(`(${time}s)`);
        if (time < 1) {
          clearInterval(intervalTimer);
          timer.text("");
          actionTimer
            .css({
              cursor: "pointer",
              color: "#009688"
            })
            .attr("onclick", "resendToken()");
        }
      }, 1000);
    }
    resetTimer();

    function resendToken() {
      time = 59;
      resetTimer();
      $.ajax({
        url: app_url + 'auth/authService',
        type: "post",
        data: {
          otpresend: 1
        },
        success: function(res) {
          if (res == "MAX_ATTEMPTS_REACHED") {
            $("#actionTimer").addClass("hidden");
            $("#otpmsgs").text('Maximum resend attempt exceeded.');
          } else {
            $("#otpmsgs").text('Verification OTP sent again.');
          }
          console.log(res);
        }
      })
      //alert("resend OTP success");
    }
    $(document).ready(function() {
      $("#submitotp").on("click", function() {
        $(this).attr("disabled", true).val('Validating...');
        $("#mfaotpform").attr("action", '<?php echo Doo::conf()->APP_URL ?>auth/authService').submit()
      })
    })
  </script>
  <style>
    .code {
      display: inline-block;
      width: 32px;
      height: 32px;
      text-align: center;
      font-size: 18px;
      margin: 0px 5px;
      border: 0px;
      border-bottom: 1px solid #ccc !important;
    }

    .code:focus,
    .code:focus-visible {
      outline: 0px;
      color: #ef3a3a !important;
      border-color: #ef3a3a !important;
    }

    .crd {
      text-align: center;
    }

    ::placeholder {
      color: #f5f5f5;
    }

    ::-moz-placeholder {
      color: #f5f5f5;
    }

    ::-webkit-input-placeholder {
      color: #f5f5f5;
    }

    .resend-otp {
      margin-top: 20px;
    }

    .resend-otp a {
      cursor: not-allowed;
      color: #ef3a3a;
    }

    #timer {
      color: #ef3a3a;
    }
  </style>
</body>

</html>
<!-- Localized -->