<?php
Doo::loadCore('db/DooModel');

class ScSmppClientSmsBase extends DooModel{

    /**
     * @var bigint Max length is 20.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
     */
    public $batch_id;

    /**
     * @var varchar Max length is 200.
     */
    public $smpp_smsid;

    /**
     * @var varchar Max length is 200.
     */
    public $smpp_client;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var varchar Max length is 200.
     */
    public $smsc;

    /**
     * @var varchar Max length is 200.
     */
    public $sender_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var varchar Max length is 200.
     */
    public $sms_type;

    /**
     * @var longtext
     */
    public $sms_text;

    /**
     * @var int Max length is 11.
     */
    public $sms_count;

    /**
     * @var timestamp
     */
    public $sending_time;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var double
     */
    public $price;

    /**
     * @var double
     */
    public $cost;

    /**
     * @var int Max length is 11.
     */
    public $dlr;

    /**
     * @var varchar Max length is 50.
     */
    public $smpp_resp_code;

    /**
     * @var varchar Max length is 10.
     */
    public $vendor_dlr;

    /**
     * @var varchar Max length is 200.
     */
    public $vendor_msgid;

    /**
     * @var timestamp
     */
    public $dlr_updated_on;

    /**
     * @var text
     */
    public $tlv_data;

    /**
     * @var mediumtext
     */
    public $msgdata;

    /**
     * @var text
     */
    public $platform_data;

    /**
     * @var int Max length is 11.
     */
    public $es_index_status;

    /**
     * @var varchar Max length is 100.
     */
    public $es_index_id;

    public $_table = 'sc_smpp_client_sms';
    public $_primarykey = 'id';
    public $_fields = array('id','batch_id','smpp_smsid','smpp_client','user_id','route_id','smsc','sender_id','mobile','sms_type','sms_text','sms_count','sending_time','status','mccmnc','price','cost','dlr','smpp_resp_code','vendor_dlr','vendor_msgid','dlr_updated_on','tlv_data','msgdata','platform_data','es_index_status','es_index_id');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'batch_id' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'smpp_smsid' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'smpp_client' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'smsc' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'sender_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'sms_type' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'sms_text' => array(
                        array( 'notnull' ),
                ),

                'sms_count' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sending_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'price' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'cost' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'dlr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'smpp_resp_code' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'vendor_dlr' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'vendor_msgid' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'dlr_updated_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'tlv_data' => array(
                        array( 'notnull' ),
                ),

                'msgdata' => array(
                        array( 'notnull' ),
                ),

                'platform_data' => array(
                        array( 'notnull' ),
                ),

                'es_index_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'es_index_id' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                )
            );
    }

}