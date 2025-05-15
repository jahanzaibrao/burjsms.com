<?php 
//******************** SMPPcube Config ********************//
include('main.conf.php');


//-------------- DEFAULT ADDONS PERMISSIONS -----------------//
include('addon_perm.conf.php');


//------------ MESSAGING -------------------//
include('messaging.conf.php');


//--------------- NEW ACCOUNT DEFAULTS --------------------//
include('new_accounts.conf.php');

//---------------- SIGN UP SETTINGS ----------------------//
include('signup.conf.php');

//---------------- KANNEL SETTINGS ----------------------//
// These variables MUST match your kannel.conf configuration
include('kannel.conf.php');



include('misc.conf.php');

include('avatar.conf.php');

include('security.conf.php');

include('reseller.conf.php');

include('logsAlertsText.conf.php');

include('smtp.conf.php');

//----

$config['credit_counter_dir'] = 'l2h';

date_default_timezone_set($config['default_server_timezone']);