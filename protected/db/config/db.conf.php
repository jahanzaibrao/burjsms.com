<?php
/**
 * Example Database connection settings and DB relationship mapping
 * $dbmap[Table A]['has_one'][Table B] = array('foreign_key'=> Table B's column that links to Table A );
 * $dbmap[Table B]['belongs_to'][Table A] = array('foreign_key'=> Table A's column where Table B links to );
 

//Food relationship
$dbmap['Food']['belongs_to']['FoodType'] = array('foreign_key'=>'id');
$dbmap['Food']['has_many']['Article'] = array('foreign_key'=>'food_id');
$dbmap['Food']['has_one']['Recipe'] = array('foreign_key'=>'food_id');
$dbmap['Food']['has_many']['Ingredient'] = array('foreign_key'=>'food_id', 'through'=>'food_has_ingredient');

//Food Type
$dbmap['FoodType']['has_many']['Food'] = array('foreign_key'=>'food_type_id');

//Article
$dbmap['Article']['belongs_to']['Food'] = array('foreign_key'=>'id');

//Recipe
$dbmap['Recipe']['belongs_to']['Food'] = array('foreign_key'=>'id');

//Ingredient
$dbmap['Ingredient']['has_many']['Food'] = array('foreign_key'=>'ingredient_id', 'through'=>'food_has_ingredient');

*/

//$dbconfig[ Environment or connection name] = array(Host, Database, User, Password, DB Driver, Make Persistent Connection?);
/**
 * Database settings are case sensitive.
 * To set collation and charset of the db connection, use the key 'collate' and 'charset'
 * array('localhost', 'database', 'root', '1234', 'mysql', true, 'collate'=>'utf8_unicode_ci', 'charset'=>'utf8'); 
 */




$dbmap['ScContactGroups']['has_many']['ScUserContacts'] = array('foreign_key'=> 'group_id' );
$dbmap['ScUserContacts']['belongs_to']['ScContactGroups'] = array('foreign_key'=> 'id' );

$dbmap['ScSenderId']['has_many']['ScSentSms'] = array('foreign_key'=> 'sender_id' );
$dbmap['ScSentSms']['belongs_to']['ScSenderId'] = array('foreign_key'=> 'id' );

$dbmap['ScSenderId']['has_many']['ScSpamSms'] = array('foreign_key'=> 'sender_id' );
$dbmap['ScSpamSms']['belongs_to']['ScSenderId'] = array('foreign_key'=> 'id' );

$dbmap['ScUsers']['has_many']['ScSentSms'] = array('foreign_key'=> 'user_id' );
$dbmap['ScSentSms']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScSenderId'] = array('foreign_key'=> 'req_by' );
$dbmap['ScSenderId']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScSmsTemplates'] = array('foreign_key'=> 'user_id' );
$dbmap['ScSmsTemplates']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScUsers'] = array('foreign_key'=> 'upline_id' );
$dbmap['ScUsers']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );


$dbmap['ScRoutes']['has_many']['ScUserPrice'] = array('foreign_key'=> 'route_id' );
$dbmap['ScUserPrice']['belongs_to']['ScRoutes'] = array('foreign_key'=> 'id' );


$dbmap['ScUsers']['has_many']['ScQueuedCampaigns'] = array('foreign_key'=> 'user_id' );
$dbmap['ScQueuedCampaigns']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScTempCampaigns'] = array('foreign_key'=> 'user_id' );
$dbmap['ScTempCampaigns']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScScheduledCampaigns'] = array('foreign_key'=> 'user_id' );
$dbmap['ScScheduledCampaigns']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScSpamSms'] = array('foreign_key'=> 'user_id' );
$dbmap['ScSpamSms']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );

$dbmap['ScUsers']['has_many']['ScSentSms'] = array('foreign_key'=> 'user_id' );
$dbmap['ScSentSms']['belongs_to']['ScUsers'] = array('foreign_key'=> 'user_id' );



$dbconfig['dev'] = array(
      array('localhost', 'mini3_smsappdb', 'root', '', 'mysql', true),
      array('localhost', 'mini3_blacklistdb', 'root', '', 'mysql', true),
      array('localhost', 'mini3_archivedb', 'root', '', 'mysql', true)
      );
 $dbconfig['prod'] = array('localhost', 'enterprise', 'root', '', 'mysql', true);

?>