<?php
include 'protected/config/common.conf.php';
include 'protected/config/routes.conf.php';
include 'protected/config/db.conf.php';

#secure session library
$secSes = 'vendor/autoload.php';
if (!file_exists($secSes)) {
  echo "You need to execute <strong>composer install</strong>!";
  exit;
}
require_once $secSes;
session_save_path(sys_get_temp_dir());

#Just include this for production mode
//include $config['BASE_PATH'].'deployment/deploy.php';
include $config['BASE_PATH'] . 'Doo.php';
include $config['BASE_PATH'] . 'app/DooConfig.php';

# Uncomment for auto loading the framework classes.
spl_autoload_register('Doo::autoload');

Doo::conf()->set($config);

# remove this if you wish to see the normal PHP error view.
//include $config['BASE_PATH'].'diagnostic/debug.php';


# database usage
//Doo::useDbReplicate();	#for db replication master-slave usage
Doo::db()->setMap($dbmap);
Doo::db()->setDb($dbconfig, $config['APP_MODE']);
//Doo::db()->sql_tracking = true;	#for debugging/profiling purpose

#multiple DB connect

define("MCRYPT_BLOWFISH", '');
define("MCRYPT_MODE_CBC", '');


Doo::app()->route = $route;

# Uncomment for DB profiling
//Doo::logger()->beginDbProfile('doowebsite');
/*$check = Doo::cache('front');
if($check)
 	Doo::cache('front')->start();
*/
Doo::app()->run();
/*
if($check)
   Doo::cache('front')->end();
   */
//Doo::logger()->endDbProfile('doowebsite');
//Doo::logger()->rotateFile(20);
//Doo::logger()->writeDbProfiles();
