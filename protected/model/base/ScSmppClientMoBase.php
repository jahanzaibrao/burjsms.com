<?php
Doo::loadCore('db/DooModel');

class ScSmppClientMoBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $smppclient;

    /**
     * @var int Max length is 11.
     */
    public $sms_id;

    /**
     * @var bigint Max length is 20.
     */
    public $source_addr;

    /**
     * @var bigint Max length is 20.
     */
    public $destination_addr;

    /**
     * @var text
     */
    public $message;

    /**
     * @var int Max length is 11.
     */
    public $data_coding;

    /**
     * @var int Max length is 11.
     */
    public $attempts;

    /**
     * @var text
     */
    public $deliver_sm_resp;

    /**
     * @var timestamp
     */
    public $logtime;

    public $_table = 'sc_smpp_client_mo';
    public $_primarykey = 'id';
    public $_fields = array('id','smppclient','sms_id','source_addr','destination_addr','message','data_coding','attempts','deliver_sm_resp','logtime');

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
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'source_addr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'destination_addr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'message' => array(
                        array( 'notnull' ),
                ),

                'data_coding' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'attempts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'deliver_sm_resp' => array(
                        array( 'notnull' ),
                ),

                'logtime' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}