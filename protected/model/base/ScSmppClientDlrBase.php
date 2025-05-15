<?php
Doo::loadCore('db/DooModel');

class ScSmppClientDlrBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $smppclient;

    /**
     * @var varchar Max length is 500.
     */
    public $sms_id;

    /**
     * @var varchar Max length is 500.
     */
    public $sender;

    /**
     * @var bigint Max length is 20.
     */
    public $msisdn;

    /**
     * @var int Max length is 11.
     */
    public $pdu_seq;

    /**
     * @var int Max length is 11.
     */
    public $dlr;

    /**
     * @var varchar Max length is 20.
     */
    public $vendor_dlr;

    /**
     * @var varchar Max length is 50.
     */
    public $smpp_resp_code;

    /**
     * @var timestamp
     */
    public $submit_date;

    /**
     * @var timestamp
     */
    public $done_date;

    /**
     * @var int Max length is 11.
     */
    public $attempts;

    /**
     * @var int Max length is 11.
     */
    public $is_mo;

    /**
     * @var varchar Max length is 500.
     */
    public $mo_data;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var timestamp
     */
    public $logtime;

    public $_table = 'sc_smpp_client_dlr';
    public $_primarykey = 'id';
    public $_fields = array('id','smppclient','sms_id','sender','msisdn','pdu_seq','dlr','vendor_dlr','smpp_resp_code','submit_date','done_date','attempts','is_mo','mo_data','status','logtime');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'smppclient' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'sms_id' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'sender' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'msisdn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'pdu_seq' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'dlr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'vendor_dlr' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'smpp_resp_code' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'submit_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'done_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'attempts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'is_mo' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mo_data' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'logtime' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}