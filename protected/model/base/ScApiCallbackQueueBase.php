<?php
Doo::loadCore('db/DooModel');

class ScApiCallbackQueueBase extends DooModel{

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
    public $route_id;

    /**
     * @var varchar Max length is 50.
     */
    public $sms_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var varchar Max length is 20.
     */
    public $sender_id;

    /**
     * @var timestamp
     */
    public $sms_sent_ts;

    /**
     * @var timestamp
     */
    public $delivery_ts;

    /**
     * @var int Max length is 11.
     */
    public $dlr;

    /**
     * @var varchar Max length is 100.
     */
    public $vendor_dlr;

    /**
     * @var varchar Max length is 10.
     */
    public $smpp_resp_code;

    /**
     * @var varchar Max length is 500.
     */
    public $callback_url;

    /**
     * @var varchar Max length is 200.
     */
    public $callback_response;

    /**
     * @var int Max length is 11.
     */
    public $attempts;

    /**
     * @var varchar Max length is 10.
     */
    public $mode;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_api_callback_queue';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','route_id','sms_id','mobile','sender_id','sms_sent_ts','delivery_ts','dlr','vendor_dlr','smpp_resp_code','callback_url','callback_response','attempts','mode','status');

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

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sms_id' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'sender_id' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'sms_sent_ts' => array(
                        array( 'datetime' ),
                        array( 'optional' ),
                ),

                'delivery_ts' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'dlr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'vendor_dlr' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'smpp_resp_code' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'callback_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'callback_response' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'attempts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mode' => array(
                        array( 'maxlength', 10 ),
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