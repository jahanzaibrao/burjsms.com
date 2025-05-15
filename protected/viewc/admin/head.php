<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="Admin, Dashboard, Bootstrap">
    <link rel="shortcut icon" sizes="196x196" href="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>">
    <title><?php echo $_SESSION['webfront']['company_name'] != '' ? $_SESSION['webfront']['company_name'] : Doo::conf()->global_page_title; ?></title>
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/font-awesome/css/font-awesome.min.css" media="all"> <!-- this Fontawesome is theme specific, below is loaded FA from cdn -->
    <link rel="stylesheet" media="all" href="<?php echo Doo::conf()->APP_URL ?>global/assets/local/v5.6.1/css/all.css">
    <link rel="stylesheet" media="all" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css">
    <link rel="stylesheet" media="all" href="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/css/app.min.css">
    <!--
Below css for numerals correction
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300"> -->
    <link href="<?php echo Doo::conf()->APP_URL ?>global/assets/local/rawline.css" rel="stylesheet" media="all">
    <?php if ($data['current_page'] == 'usms_log' || $data['current_page'] == 'stats') { ?>
        <link href="<?php echo Doo::conf()->APP_URL ?>global/css/search_datepicker.css" rel="stylesheet">
    <?php } else { ?>
        <link href="<?php echo Doo::conf()->APP_URL ?>global/css/drpkr2.css" rel="stylesheet">
    <?php } ?>
    <link rel="stylesheet" href="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" media="all">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet" media="all">
    <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/breakpoints.js/dist/breakpoints.min.js"></script>
    <script>
        var app_url = '<?php echo Doo::conf()->APP_URL ?>';
        var app_lang = '<?php echo $_SESSION['APP_LANG'] ?>';
        var curpage = '<?php echo $data['current_page'] ?>';
        var app_currency = '<?php echo Doo::conf()->currency ?>';
        var app_invoice_discount = '<?php echo Doo::conf()->invoice_discount ?>';
        var crecountrule = new Object();
        var defTheme = <?php echo $_SESSION['webfront']['intheme'] != '' ? '"' . $_SESSION['webfront']['intheme'] . '"' : '"' . 'danger' . '"' ?>;
        Breakpoints();
        var authToken = `<?php echo $_SESSION['user_auth_token'] ?>`;
    </script>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/switchery/dist/switchery.min.js"></script>
</head>

<body class="menubar-left menubar-unfold">
    <audio id="notifAudio">
        <source src="<?php echo Doo::conf()->APP_URL ?>global/audio/ping.mp3" type="audio/mpeg">
    </audio>
    <style>
        <?php if ($_SESSION['APP_LANG'] == 'th') { ?>body {
            font-family: 'Prompt', sans-serif !important;
            font-weight: 300 !important;
            font-style: normal;
        }

        <?php } ?><?php if ($_SESSION['APP_LANG'] == 'ar') { ?>body {
            font-family: "Tajawal", sans-serif !important;
            font-weight: 300 !important;
            font-style: normal;
        }

        <?php } ?><?php
                    if (Doo::conf()->custom_theme_id == 1) {
                    ?>

        /*
        #custom-sidebar-avatar {
            background: #fff;
            border-radius: 50%;
            padding: 2px;
        }

        #custom-sidebar-header {
            background: #492F51;
        }

        .menubar.dark,
        .bg-theme1 {
            background: #492F51 !important;
        }*/
        #custom-sidebar-avatar {
            background: #fff;
            border-radius: 50%;
            padding: 2px;
        }

        #custom-sidebar-header {
            background: #142233;
        }

        .menubar.dark,
        .bg-theme1 {
            background: #142233 !important;
        }

        #app-navbar {
            background: rgba(74, 47, 81, 0.85);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            /*background-color: #492F51 !important;*/
            /*#FDBF30 !important;*/
        }

        <?php
                    }
                    if (Doo::conf()->custom_theme_id == 2) {
        ?>#custom-sidebar-avatar {
            background: #fff;
            border-radius: 50%;
            padding: 2px;
        }

        #custom-sidebar-header {
            background: #142233;
        }

        .menubar.dark,
        .bg-theme1 {
            background: #142233 !important;
        }

        #app-navbar {
            background-color: #01b2b5 !important;
        }

        <?php
                    }

        ?>.zmdi-hc-lg {
            vertical-align: 0 !important;
        }

        .indicator {
            width: 39px;
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-left: 5px;
        }

        .indicator.online {
            background-color: #4caf50;
        }

        .indicator.offline {
            background-color: #f44336;
        }
    </style>
    <?php include("header.php"); ?>