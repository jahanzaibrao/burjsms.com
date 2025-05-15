<?php
Doo::loadCore('db/DooModel');

class ScUsersBase extends DooModel{

    /**
     * @var bigint Max length is 20.
     */
    public $user_id;

    /**
     * @var varchar Max length is 25.
     */
    public $login_id;

    /**
     * @var text
     */
    public $password;

    /**
     * @var varchar Max length is 100.
     */
    public $name;

    /**
     * @var char Max length is 1.
     */
    public $gender;

    /**
     * @var text
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
     * @var int Max length is 11.
     */
    public $optin_only;

    /**
     * @var int Max length is 11.
     */
    public $acl_mode;

    /**
     * @var varchar Max length is 200.
     */
    public $acl_ip_list;

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
    public $email_verified;

    /**
     * @var int Max length is 11.
     */
    public $mobile_verified;

    /**
     * @var int Max length is 11.
     */
    public $upline_id;

    /**
     * @var int Max length is 11.
     */
    public $acc_mgr_id;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var int Max length is 11.
     */
    public $payment_perm;

    /**
     * @var varchar Max length is 500.
     */
    public $default_tax;

    /**
     * @var varchar Max length is 50.
     */
    public $registered_on;

    /**
     * @var timestamp
     */
    public $activation_date;

    /**
     * @var varchar Max length is 20.
     */
    public $last_login_ip;

    /**
     * @var int Max length is 11.
     */
    public $account_type;

    /**
     * @var int Max length is 11.
     */
    public $state;

    /**
     * @var varchar Max length is 50.
     */
    public $last_activity;

    public $_table = 'sc_users';
    public $_primarykey = 'user_id';
    public $_fields = array('user_id','login_id','password','name','gender','avatar','category','subgroup','optin_only','acl_mode','acl_ip_list','mobile','email','email_verified','mobile_verified','upline_id','acc_mgr_id','status','payment_perm','default_tax','registered_on','activation_date','last_login_ip','account_type','state','last_activity');

    public function getVRules() {
        return array(
                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'login_id' => array(
                        array( 'maxlength', 25 ),
                        array( 'notnull' ),
                ),

                'password' => array(
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 100 ),
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

                'optin_only' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'acl_mode' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'acl_ip_list' => array(
                        array( 'maxlength', 200 ),
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

                'email_verified' => array(
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
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'acc_mgr_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'payment_perm' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'default_tax' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'registered_on' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'activation_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_login_ip' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'account_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'state' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_activity' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                )
            );
    }

}