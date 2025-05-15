<?php
Doo::loadCore('db/DooModel');

class ScUsersSettingsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $email_daily_sms;

    /**
     * @var int Max length is 11.
     */
    public $email_daily_credits;

    /**
     * @var int Max length is 11.
     */
    public $email_app_notif;

    /**
     * @var varchar Max length is 2.
     */
    public $def_lang;

    /**
     * @var int Max length is 11.
     */
    public $def_route;

    /**
     * @var varchar Max length is 500.
     */
    public $default_dlr_url;

    /**
     * @var varchar Max length is 500.
     */
    public $default_mo_url;

    public $_table = 'sc_users_settings';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','email_daily_sms','email_daily_credits','email_app_notif','def_lang','def_route','default_dlr_url','default_mo_url');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'email_daily_sms' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'email_daily_credits' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'email_app_notif' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'def_lang' => array(
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'def_route' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'default_dlr_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'default_mo_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                )
            );
    }

}