<?php
Doo::loadCore('db/DooModel');

class ScUsersDeletedAccountsBase extends DooModel{

    /**
     * @var bigint Max length is 20.
     */
    public $user_id;

    /**
     * @var varchar Max length is 25.
     */
    public $login_id;

    /**
     * @var varchar Max length is 40.
     */
    public $name;

    /**
     * @var char Max length is 1.
     */
    public $gender;

    /**
     * @var mediumtext
     */
    public $avatar;

    /**
     * @var varchar Max length is 10.
     */
    public $category;

    /**
     * @var varchar Max length is 10.
     */
    public $subgroup;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var varchar Max length is 45.
     */
    public $email;

    /**
     * @var int Max length is 11.
     */
    public $email_verifed;

    /**
     * @var int Max length is 11.
     */
    public $mobile_verified;

    /**
     * @var varchar Max length is 40.
     */
    public $upline_id;

    /**
     * @var int Max length is 11.
     */
    public $acc_mgr_id;

    /**
     * @var int Max length is 2.
     */
    public $spam_status;

    /**
     * @var int Max length is 11.
     */
    public $opentemp_flag;

    /**
     * @var varchar Max length is 50.
     */
    public $registered_on;

    /**
     * @var varchar Max length is 20.
     */
    public $last_login_ip;

    /**
     * @var varchar Max length is 50.
     */
    public $last_activity;

    public $_table = 'sc_users_deleted_accounts';
    public $_primarykey = 'user_id';
    public $_fields = array('user_id','login_id','name','gender','avatar','category','subgroup','mobile','email','email_verifed','mobile_verified','upline_id','acc_mgr_id','spam_status','opentemp_flag','registered_on','last_login_ip','last_activity');

    public function getVRules() {
        return array(
                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'login_id' => array(
                        array( 'maxlength', 25 ),
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 40 ),
                        array( 'notnull' ),
                ),

                'gender' => array(
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                ),

                'avatar' => array(
                        array( 'notnull' ),
                ),

                'category' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'subgroup' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'email' => array(
                        array( 'maxlength', 45 ),
                        array( 'notnull' ),
                ),

                'email_verifed' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mobile_verified' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'upline_id' => array(
                        array( 'maxlength', 40 ),
                        array( 'notnull' ),
                ),

                'acc_mgr_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'spam_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'opentemp_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'registered_on' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'last_login_ip' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'last_activity' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                )
            );
    }

}