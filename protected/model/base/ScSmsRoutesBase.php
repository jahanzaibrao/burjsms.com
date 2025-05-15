<?php
Doo::loadCore('db/DooModel');

class ScSmsRoutesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $admin_id;

    /**
     * @var varchar Max length is 200.
     */
    public $title;

    /**
     * @var varchar Max length is 100.
     */
    public $smpp_list;

    /**
     * @var varchar Max length is 200.
     */
    public $route_config;

    /**
     * @var int Max length is 11.
     */
    public $sender_type;

    /**
     * @var varchar Max length is 100.
     */
    public $def_sender;

    /**
     * @var int Max length is 11.
     */
    public $max_sid_len;

    /**
     * @var int Max length is 11.
     */
    public $template_flag;

    /**
     * @var varchar Max length is 500.
     */
    public $active_time;

    /**
     * @var int Max length is 11.
     */
    public $country_id;

    /**
     * @var varchar Max length is 100.
     */
    public $blacklist_ids;

    /**
     * @var int Max length is 11.
     */
    public $credit_rule;

    /**
     * @var int Max length is 11.
     */
    public $add_pre;

    /**
     * @var int Max length is 11.
     */
    public $gsm7_filter;

    /**
     * @var varchar Max length is 200.
     */
    public $tlv_ids;

    /**
     * @var varchar Max length is 200.
     */
    public $optout_config;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_sms_routes';
    public $_primarykey = 'id';
    public $_fields = array('id','admin_id','title','smpp_list','route_config','sender_type','def_sender','max_sid_len','template_flag','active_time','country_id','blacklist_ids','credit_rule','add_pre','gsm7_filter','tlv_ids','optout_config','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'admin_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'title' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'smpp_list' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'route_config' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'sender_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'def_sender' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'max_sid_len' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'template_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'active_time' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'country_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'blacklist_ids' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'credit_rule' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'add_pre' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'gsm7_filter' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'tlv_ids' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'optout_config' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}