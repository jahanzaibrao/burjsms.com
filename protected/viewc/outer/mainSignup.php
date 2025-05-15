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
    <!--
Below css for numerals correction
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300"> -->
    <link href="https://cdn.rawgit.com/h-ibaldo/Raleway_Fixed_Numerals/master/css/rawline.css" rel="stylesheet">
    <style>
        .simple-page-form .form-group {
            border-bottom: 1px dotted #ccc;
        }

        .route-assign-ctr {
            overflow: auto;
            border-bottom: 1px dashed rgb(204, 204, 204);
            max-height: 520px;
        }

        .error-input,
        error-input:focus {
            border: 2px red dashed !important;
        }

        .val-icon {
            position: absolute;
            right: 10px;
            top: 8px;
        }

        .abs-ctr {
            position: relative;
        }

        .bootbox-body {
            color: black;
        }
    </style>
    <script>
        var app_url = '<?php echo Doo::conf()->APP_URL ?>';
        var app_lang = '<?php echo $_SESSION['APP_LANG'] ?>';
        var app_currency = '<?php echo Doo::conf()->currency ?>';
        var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
        var defCountry = '<?php echo strtoupper($data['country']) ?>';
        var crecountrule = new Object();
        var defTheme = <?php echo $_SESSION['webfront']['intheme'] != '' ? '"' . $_SESSION['webfront']['intheme'] . '"' : 'danger' ?>;
    </script>
    
</head>

<body class="simple-page <?php echo $pdata['theme'] ?>">
    <div id="back-to-home"><a href="<?php echo Doo::conf()->APP_URL ?>" class="btn btn-outline btn-default"><i class="fa fa-home fa-2x animated zoomIn"></i></a></div>
    <div class="simple-page-wrap text-dark" style="max-width:<?php echo $data['pflag'] == 0 && $data['mode'] != 'paypal' ? '500px' : '900px'; ?> !important">

        <div class="simple-page-form animated flipInY" id="reg-form">

            <div class="simple-page-logo animated swing">
                <a href="<?php echo Doo::conf()->APP_URL ?>">
                    <img src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>" data-at2x="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/placeholders/logo@2x.png" alt="" />
                </a>
            </div>

            <h4 class="form-title m-b-xl text-center"><?php echo SCTEXT('Register a new account') ?></h4>

            <?php include('notification.php') ?>


            <?php if ($data['mode'] == 'paypal') {
                //show paypal payment link here
                $invdata = unserialize($data['docdata']->file_data);

            ?>
                <div class="text-center">
                    <h4 class="text-primary m-b-xs">Dear <?php echo $data['udata']->name . ',' ?></h4>
                    <?php echo SCTEXT('Thank you for signing up with us. Your total payable amount is') ?>:<br>
                    <h3 class="text-success m-b-0"><?php echo Doo::conf()->currency . number_format($invdata['grand_total'], 2); ?></h3>
                    <?php if ($invdata['plan_tax'] != '') { ?> <small class="help-block">(<?php echo SCTEXT('including') ?> <?php echo $invdata['plan_tax'] ?>)</small> <?php } ?>
                    <p><?php echo SCTEXT('To complete your purchase, please pay using secure Paypal checkout below') ?>.</p>
                    <div id="paypal-button"></div>
                </div>




            <?php } else {
                //show sign up page
            ?>
            <script src="https://accounts.google.com/gsi/client" async defer></script>
<div style="display:block; text-align:center; padding: 0 25%;">
            <div id="g_id_onload"
     data-client_id="<?php echo Doo::conf()->gcp_client_id ?>"
     data-context="signup"
     data-ux_mode="redirect"
     data-login_uri="<?php echo Doo::conf()->APP_URL ?>web/sign-up"
     data-callback="gsignup"
     data-nonce=""
     data-auto_select="false"
     data-itp_support="true">
</div>

<div class="g_id_signin"
     data-type="standard"
     data-shape="pill"
     data-theme="filled_blue"
     data-text="signup_with"
     data-size="large"
     data-logo_alignment="left">
</div>
            </div>
  <hr>
  <div id="optional-gs">
  <div align="center"> OR </div>
  <h4 align="center">Signup with your details</h4>
<hr>
            </div>

                <form method="post" id="regform" action="">
                <input type="hidden" value="" name="profilepic" id="profilepic">
                    <?php if ($data['pflag'] == 0) { ?>

                        <div class="">
                            <input type="hidden" id="ucat" name="ucat" value="client">
                            <input type="hidden" name="gender" value="m">

                            <div class="form-group">
                                <input type="text" name="uname" id="uname" class="form-control" placeholder="<?php echo SCTEXT('Enter your name') ?>. . ." maxlength="100" />
                            </div>

                            <div class="form-group">
                                <div class="input-group ">
                                    <select class="form-select" id="coverages" style="border: none; background: none;">
                                        <?php foreach ($data['covdata'] as $cov) { ?>
                                            <option class="covopts" data-code="<?php echo $cov->country_code ?>" value="<?php echo $cov->prefix ?>"><?php echo $cov->country ?></option>
                                        <?php } ?>
                                    </select>
                                    <input style="min-width: 240px;" type="text" name="uphn" id="uphn" class="form-control" placeholder="<?php echo SCTEXT('Enter mobile number') ?>..." maxlength="20" />
                                    <span style="z-index: 9;" id="v-phn" class="val-icon"></span>
                                </div>
                            </div>
                            <div class="form-group abs-ctr">
                                <input type="text" name="uemail" id="uemail" class="form-control" placeholder="<?php echo SCTEXT('Enter email address') ?>.." maxlength="100" />
                                <span id="v-email" class="val-icon"></span>

                            </div>
                            <div class="form-group abs-ctr">
                                <input type="text" name="ulogin" id="ulogin" class="form-control" placeholder="<?php echo SCTEXT('Create login ID') ?> ..." maxlength="100" />
                                <span id="v-login" class="val-icon"></span>
                                <span class="help-block">
                                    <?php echo SCTEXT('No spaces, only numbers, letters, underscore and hyphen allowed') ?>
                                </span>
                            </div>

                        </div>

                    <?php } else { ?>




                        <div class="col-md-6">
                            <input type="hidden" id="ucat" name="ucat" value="client">
                            <input type="hidden" name="gender" value="m">
                            <div class="form-group">
                                <input type="text" name="uname" id="uname" class="form-control" placeholder="<?php echo SCTEXT('Enter your name') ?>. . ." maxlength="100" />
                            </div>
                            <div class="form-group">
                                <div class="input-group ">
                                    <select class="form-select" data-plugin="" id="coverages" style="border: none; background: none;">
                                        <?php foreach ($data['covdata'] as $cov) { ?>
                                            <option class="covopts" data-code="<?php echo $cov->country_code ?>" value="<?php echo $cov->prefix ?>"><?php echo $cov->country ?></option>
                                        <?php } ?>
                                    </select>
                                    <input style="min-width: 240px;" type="text" name="uphn" id="uphn" class="form-control" placeholder="<?php echo SCTEXT('Enter mobile number') ?>..." maxlength="20" />
                                    <span style="z-index: 9;" id="v-phn" class="val-icon"></span>
                                </div>
                            </div>

                            <div class="form-group abs-ctr">
                                <input type="text" name="uemail" id="uemail" class="form-control" placeholder="<?php echo SCTEXT('Enter email address') ?>.." maxlength="100" />
                                <span id="v-email" class="val-icon"></span>

                            </div>
                            <div class="form-group abs-ctr">
                                <input type="text" name="ulogin" id="ulogin" class="form-control" placeholder="<?php echo SCTEXT('Create login ID') ?> ..." maxlength="100" />
                                <span id="v-login" class="val-icon"></span>
                                <span class="help-block">
                                    <?php echo SCTEXT('No spaces, only numbers, letters, underscore and hyphen allowed') ?>
                                </span>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="plan" class="form-control" name="uplan">
                                    <option value="">- <?php echo SCTEXT('Choose SMS Plan') ?> -</option>
                                    <option value="0"><?php echo SCTEXT('Free Trial') ?></option>
                                    <?php foreach ($data['plans'] as $plan) { ?>
                                        <option <?php if (isset($_REQUEST['pid']) && intval($_REQUEST['pid']) == $plan->id) { ?> selected <?php } ?> data-type="<?php echo $plan->plan_type ?>" data-rts="<?php echo $plan->route_ids ?>" value="<?php echo $plan->id ?>" data-tax="<?php echo $plan->tax ?>" data-taxtype="<?php echo $plan->tax_type ?>"><?php echo $plan->plan_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>



                            <div id="routes-n-credits" class="form-group clearfix hidden">
                                <label id="rnclabel">Routes &amp; Credits:</label>
                                <div class="route-assign-ctr">
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <label class="control-label col-md-5"><?php echo SCTEXT('Total amount') ?>:</label>
                                <div class="col-md-5">
                                    <h5 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="total_amt">0.00</span> </h5>
                                </div>
                            </div>




                            <div class="form-group clearfix">
                                <label class="control-label col-md-5"><?php echo SCTEXT('Grand Total') ?>:</label>
                                <div class="col-md-7">
                                    <h4 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="grand_total_amt">0.00</span> <br><small id="all_taxes" class="m-l-0" style="font-size:12px; ">(<?php echo SCTEXT('including') ?> all taxes)</small></h4>
                                </div>
                            </div>



                        </div>

                    <?php } ?>

                    <input type="hidden" name="ptype" id="ptype" value="<?php echo isset($_REQUEST['ptype']) ? intval($_REQUEST['ptype']) : 0; ?>">

                    <input type="button" data-action='submit' class="btn btn-primary" id="signup-submit" value="<?php echo SCTEXT('Create My Account') ?>">

                </form>
            <?php } ?>
            <?php if(Doo::conf()->whatsapp ==1){ ?>
            <hr>
            <h4 align="center">Connect Your WhatsApp Business Account</h4>
            <br>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '<?php echo Doo::conf()->wba_app_id ?>',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v18.0'
    });
  };
</script>
<script async defer crossorigin="anonymous"
  src="https://connect.facebook.net/en_US/sdk.js">
</script>
<script>
  // Facebook Login with JavaScript SDK
  function launchWhatsAppSignup() {
    // Launch Facebook login
    FB.login(function (response) {
      if (response.authResponse) {
        const accessToken = response.authResponse.accessToken;
        //Use this token to call the debug_token API and get the shared WABA's ID
        console.log(accessToken);
        //redirect
        window.location = "<?php echo Doo::conf()->APP_URL ?>finishWabaOnboarding/" + accessToken;
      } else {
        console.log('User cancelled login or did not fully authorize.');
      }
    }, {
      config_id: '<?php echo Doo::conf()->wba_config_id ?>', // configuration ID obtained in the previous step goes here
      response_type: 'code',     // must be set to 'code' for System User access token
      override_default_response_type: true,
      extras: {
        setup: {}
      }
    });
  }
</script>
<div align="center">
<button type="button" onclick="launchWhatsAppSignup()"
  style="background-color: #1877f2; border: 0; border-radius: 4px; color: #fff; cursor: pointer; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; height: 40px; padding: 0 24px;">
  Login with Facebook
</button>
</div>
<?php } ?>
        </div>
        <div class="simple-page-footer">
            <p><a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in"> <i class="fa fa-chevron-left m-r-xs"></i> <?php echo SCTEXT('Back to Login Page') ?></a></p>
        </div>
    </div>
</body>
<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>


<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/superfish.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/retina.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/default/js/bootstrap.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/js/bootbox.js"></script>
<script src="<?php echo Doo::conf()->APP_URL ?>global/js/outer_lang.js"></script>
<?php if ($data['mode'] == 'paypal') { ?> <script src="https://www.paypalobjects.com/api/checkout.js"></script> <?php } ?>
    
    <script src="https://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script>
    const getFlagEmoji = countryCode => String.fromCodePoint(...[...countryCode.toUpperCase()].map(x => 0x1f1a5 + x.charCodeAt()))
    $(document).ready(function() {
        
      

        //populate flags
        $(".covopts").each(function() {
            let iso = $(this).attr("data-code");
            let country = $(this).html();
            let prefix = $(this).val();
            $(this).html(`${getFlagEmoji(iso)} ${country} (+${prefix})`)
            if(defCountry == iso) { $(this).attr("selected", "selected")}
        })

        var erremail = 0;
        var errphone = 0;
        var emsg = '';
        var errloginid = 0;


        //validate login id
        $("#ulogin").on("keyup blur", function() {
            var lid = $(this).val();
            //validate if not blank
            if (lid != '') {
                $("#v-login").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
                if (lid.indexOf(' ') >= 0 || lid.length < 5) {
                    $("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
                    errloginid = 1;
                    emsg = 'Invalid login ID. Must be at least 5 characters without spaces.';
                } else {
                    $("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
                    errloginid = 0;
                    emsg = '';
                    //verify
                    $.ajax({
                        url: app_url + 'checkAvailability',
                        method: 'post',
                        data: {
                            mode: 'login',
                            value: lid
                        },
                        success: function(res) {
                            if (res == 'FALSE') {
                                $("#v-login").html('<i class="fa fa-lg fa-times text-danger"></i>');
                                errloginid = 1;
                                emsg = 'Login ID already exist. Please enter a different login ID.';
                            } else {
                                $("#v-login").html('<i class="fa fa-lg fa-check text-success"></i>');
                                errloginid = 0;
                                emsg = '';

                            }
                        }
                    });
                }
            }


        });


        //validate email
        $("#uemail").on("keyup blur", function() {
            var email = $(this).val();

            //validate if not blank
            if (email != '') {
                $("#v-email").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
                if (!echeck(email)) {
                    $("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
                    erremail = 1;
                    emsg = 'Invalid Email ID';
                } else {
                    $("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
                    erremail = 0;
                    emsg = '';
                    //verify
                    $.ajax({
                        url: app_url + 'checkAvailability',
                        method: 'post',
                        data: {
                            mode: 'email',
                            value: email
                        },
                        success: function(res) {
                            if (res == 'FALSE') {
                                $("#v-email").html('<i class="fa fa-lg fa-times text-danger"></i>');
                                erremail = 1;
                                emsg = 'Email ID already exist. Please enter a different email ID.';
                            } else {
                                $("#v-email").html('<i class="fa fa-lg fa-check text-success"></i>');
                                erremail = 0;
                                emsg = '';

                            }
                        }
                    });
                }
            }


        });


        //validate phone
        $("#uphn").on("keyup blur", function() {
            var phn = $(this).val();

            //validate if not blank
            if (phn != '') {
                $("#v-phn").html('<i class="fa fa-lg fa-circle-o-notch fa-spin"></i>');
                if (!isValidPhone(phn)) {
                    $("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
                    errphone = 1;
                    emsg = 'Invalid Phone number entered.';
                } else {
                    $("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
                    errphone = 0;
                    emsg = '';
                    //verify
                    $.ajax({
                        url: app_url + 'checkAvailability',
                        method: 'post',
                        data: {
                            mode: 'mobile',
                            value: phn
                        },
                        success: function(res) {
                            if (res == 'FALSE') {
                                $("#v-phn").html('<i class="fa fa-lg fa-times text-danger"></i>');
                                errphone = 1;
                                emsg = 'Phone number already exist. Please enter a different phone number.';
                            } else {
                                $("#v-phn").html('<i class="fa fa-lg fa-check text-success"></i>');
                                errphone = 0;
                                emsg = '';

                            }
                        }
                    });
                }
            }


        });

        //switch sms plans
        $(document).on("change", "#plan", function() {
            var planid = $(this).val();
            var ptype = $("#plan option:selected").attr('data-type');
            var rts = $("#plan option:selected").attr('data-rts');
            //get plan options
            if (planid == '' || planid == '0') {
                //free trial
                $("div.route-assign-ctr").html('');
                $("#routes-n-credits").addClass("hidden");
            } else {
                $.ajax({
                    url: app_url + 'getSelPlanOptionsOuter',
                    type: 'post',
                    data: {
                        planid: planid,
                        ptype: ptype,
                        routes: rts
                    },
                    success: function(res) {
                        //make total & grand total zero
                        $("#total_amt, #grand_total_amt").text('0.00');
                        $("#all_taxes").text('(including all taxes)');
                        if (ptype == '0') {
                            //volume based
                            var resar = [];
                            var rtdata = [];
                            resar = JSON.parse(res);
                            rtdata = resar['opt_data'];
                            var str = '';
                            for (rid in rtdata) {

                                if (planid == 0) {

                                } else {
                                    //predefined rates
                                    str += '<div class="individual_rt" data-rid="' + rid + '"><div class="checkbox checkbox-primary"><input id="rtsel-' + rid + '" class="route-sel" name="route[' + rid + ']" checked id="rtsel-' + rid + '" type=checkbox><label for="rtsel-' + rid + '">' + rtdata[rid]['title'] + '</label></div><div class="input-group"><input data-rate="' + rtdata[rid]['price'] + '" style="border:1px solid #ccc;" data-pid="' + planid + '" data-rid="' + rid + '" class="rtcredits form-control input-sm input-small-sc"name="credits[' + rid + ']"placeholder="enter sms credits to buy e.g. 500" id="rtcre-' + rid + '"><span class=input-group-addon>sms</span></div><span id="rtprc-' + rid + '" class="help-block text-primary m-t-0">@ ' + app_currency + rtdata[rid]['price'] + ' per SMS</span> </div>';

                                }

                            }
                            $("div.route-assign-ctr").html(str);
                            $("#rnclabel").text('Routes & Credits:');
                            $("#routes-n-credits").removeClass("hidden");

                            $("#ptype").val('0');
                        } else {
                            //subscription based
                            var resar = [];
                            var pdata = [];
                            resar = JSON.parse(res);
                            pdata = resar['opt_data'];
                            var str = '<select name="plan_option" id="popt-sel" class="form-control" data-plugin="select2"><option value="0">- Choose Plan Option -</option>';

                            //check if any plan was selected
                            var selpopt = '<?php echo isset($_REQUEST['subopt']) ? $_REQUEST['subopt'] : 0 ?>';

                            for (idn in pdata) {
                                if (pdata[idn]['optin'] == '0') {
                                    //allowed to signup with this plan
                                    var prc = pdata[idn]['cycle'] == 'm' ? app_currency + pdata[idn]['fee'] + ' per month' : app_currency + pdata[idn]['fee'] + ' per year';
                                    if (selpopt != '0' && selpopt == idn) {
                                        str += '<option selected data-rate="' + pdata[idn]['fee'] + '" value="' + idn + '">' + pdata[idn]['name'] + ' Plan ( ' + prc + ' )</option>';
                                    } else {
                                        str += '<option data-rate="' + pdata[idn]['fee'] + '" value="' + idn + '">' + pdata[idn]['name'] + ' Plan ( ' + prc + ' )</option>';
                                    }

                                }

                            }
                            str += '</select>';
                            $("div.route-assign-ctr").html(str);
                            $("#routes-n-credits").removeClass("hidden");

                            $("#rnclabel").text('Plan Options:');
                            $("#ptype").val('1');
                            if (selpopt != '0') $("#popt-sel").trigger("change"); //pre selected order
                        }
                    }
                });
            }


        });


        //enter sms count in volume based plan boxes
        $(document).on("keyup blur input", ".rtcredits", function(e) {
            //only numbers allowed
            if (!/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test($(this).val()) && $(this).val() != '') {
                $(this).addClass('error-input');
                e.preventDefault();
                return;
            } else {
                $(this).removeClass('error-input');
            }
            var routencredits = [];
            var pid = $("#plan option:selected").val();

            //calculate only if volume based plan is selected
            if ((pid != null && pid > '0') && $("#ptype").val() == '0') {
                //for each selected route get credits entered and calculate final price
                var totalcost = 0;
                var totalwithtax = 0;
                $("#routes-n-credits").find(".individual_rt").each(function() {
                    var ele = $(this);
                    var rid = ele.attr('data-rid');

                    if ($('#rtsel-' + rid).is(":checked")) {
                        //route assigned
                        var rdata = {
                            id: rid,
                            credits: $("#rtcre-" + rid).val()
                        };
                        routencredits.push(rdata);
                        //totalcost += ($("#rtcre-"+rid).val())  * ($("#rtcre-"+rid).attr('data-rate'));

                    }
                });


                $.ajax({
                    url: app_url + 'getPlanSmsPriceOuter',
                    method: 'post',
                    data: {
                        plan: pid,
                        routesData: JSON.stringify(routencredits)
                    },
                    success: function(res) {
                        var myarr = [];
                        myarr = JSON.parse(res);
                        //you have the price and credits entered

                        //update the rate received from the db in case a plan is chosen
                        if (pid != '0') {
                            for (grid in myarr.price) {
                                $("#rtprc-" + grid).text('@ ' + app_currency + myarr.price[grid].price + ' per SMS');
                            }
                        }


                        var plan_cost = myarr.total_plan;
                        var ptax = myarr.plan_tax;
                        var gtotal = myarr.grand_total;



                        $("#grand_total_amt").text(gtotal.toLocaleString());
                        $("#total_amt").text(plan_cost.toLocaleString());
                        $("#all_taxes").text(ptax);


                    }
                });



            }


        });


        //selection of subscription based plan
        $(document).on("change", "#popt-sel", function() {

            var totalcost = 0;
            var totalwithtax = 0;
            var taxper = $("#plan option:selected").attr('data-tax');
            totalcost = $("#popt-sel option:selected").attr('data-rate');
            var taxtype = 'Tax';
            switch ($("#plan option:selected").attr('data-taxtype')) {
                case 'VT':
                    taxtype = 'VAT';
                    break;
                case 'ST':
                    taxtype = 'Service Tax';
                    break;
                case 'SC':
                    taxtype = 'Service Charge';
                    break;
                case 'OT':
                    taxtype = 'Tax';
                    break;
                case 'GT':
                    taxtype = 'GST';
                    break;
            }
            var taxstr = taxper == '' || taxper == '0' ? '(including all taxes)' : '(including ' + taxper + '% ' + taxtype + ')';
            totalwithtax = parseFloat(totalcost) + ((parseFloat(taxper) / 100) * totalcost);

            $("#grand_total_amt").text(totalwithtax.toLocaleString());
            $("#total_amt").text(totalcost.toLocaleString());
            $("#all_taxes").text(taxstr);

        });


        //submit form and take to payment page
        $("#signup-submit").click(function() {
            //validate
            if ($("#uname").val() == '' || $("#uphn").val() == '' || $("#ulogin").val() == '' || $("#uemail").val() == '') {
                bootbox.alert(SCTEXT(`Please enter values in all the fields.`));
                return;
            }

            if (erremail == 1 || errloginid == 1 || errphone == 1) {
                if (emsg == '') {
                    bootbox.alert(SCTEXT(`Some errors are found with your entry. Please rectify before submitting the form.`));
                } else {
                    bootbox.alert(SCTEXT(emsg));
                }

                return;
            }

            var mydialog = bootbox.dialog({
                closeButton: false,
                message: `<p class="text-center" style="color: hsl(0, 0%, 0%);margin-top: 2%;word-spacing: 2px;"> ${SCTEXT(`Creating Account`)} . . . .</p><div class="progress progress-xs"><div id="stbar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`
            });
            mydialog.init(function() {
                $("#stbar").animate({
                    width: "100%"
                }, 300);
                setTimeout(function() {
                    $("#regform").attr('action', app_url + 'regNewAccount');
                    $("#regform").submit();
                }, 200);
            });
        })




        //-- on load
        if ($("#plan").length > 0) {
            $("#plan").trigger("change");
        }
        //plan options select box change event triggerred after creating it


    });





    //-- paypal button
    if ($("#paypal-button").length > 0) {
        paypal.Button.render({
            // Configure environment
            env: '<?php echo $data['paypal']['env'] ?>',
            client: {
                sandbox: '<?php echo $data['paypal']['clientid'] ?>',
                production: '<?php echo $data['paypal']['clientid'] ?>'
            },
            locale: 'en_US',
            style: {
                size: 'medium',
                color: 'blue',
                shape: 'rect',
                label: 'checkout',
                tagline: 'true'
            },
            // Set up a payment
            payment: function(data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: '<?php echo number_format($invdata['grand_total'], 2) ?>',
                            currency: 'USD' //currency from conf
                        }
                    }]
                });
            },
            // Execute the payment:
            onAuthorize: function(data, actions) {
                return actions.payment.execute()
                    .then(function() {
                        // Show a confirmation message to the buyer
                        //window.alert('Thank you for your purchase!');

                        // Redirect to the payment process page
                        window.location = app_url + "scPaymentReturn/index.php?paymentID=" + data.paymentID + "&token=" + data.paymentToken + "&payerID=" + data.payerID + "&invid=<?php echo $data['docdata']->id; ?>";
                    });
            }
        }, '#paypal-button');
    }



    function gsignup(gdata){
       
        $("#optional-gs").hide();
        //google signup was successful, get details and fill out the form
        const responsePayload = decodeJwtResponse(gdata.credential);
        $("#uname").val(responsePayload.name);
        $("#uemail").val(responsePayload.email);
        $("#profilepic").val(responsePayload.picture);

        //console.log("ID: " + responsePayload.sub);
        //console.log('Full Name: ' + responsePayload.name);
        //console.log("Image URL: " + responsePayload.picture);
        //console.log("Email: " + responsePayload.email);

    }
    function decodeJwtResponse(token) {
        let base64Url = token.split('.')[1];
           let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
           let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
             return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
             }).join(''));

           return JSON.parse(jsonPayload);
    }

    //global validation functions


    function isValidPhone(val) {
        if (val == null) {
            return false;
        }
        if (val.length == 0) {
            return false;
        }
        if (val.length > 15 || val.length < 9) {
            return false;
        }
        for (var i = 0; i < val.length; i++) {
            var ch = val.charAt(i)
            if (ch < "0" || ch > "9" || ch == "+") {
                return false
            }
        }
        return true
    }

    function echeck(str) {

        var at = "@"
        var dot = "."
        var lat = str.indexOf(at)
        var lstr = str.length
        var ldot = str.indexOf(dot)
        if (str.indexOf(at) == -1) {
            return false
        }

        if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr) {
            return false
        }

        if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr) {
            return false
        }

        if (str.indexOf(at, (lat + 1)) != -1) {
            return false
        }

        if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot) {
            return false
        }

        if (str.indexOf(dot, (lat + 2)) == -1) {
            return false
        }

        if (str.indexOf(" ") != -1) {
            return false
        }

        return true
    }

    //console.log(`<p class="text-center">${SCTEXT(`Creating Account`)}</p>` )
</script>


</html>
<!-- Localized -->