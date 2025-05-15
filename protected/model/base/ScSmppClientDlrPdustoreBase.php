<?php
Doo::loadCore('db/DooModel');

class ScSmppClientDlrPdustoreBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 50.
     */
    public $smppclient;

    /**
     * @var varchar Max length is 200.
     */
    public $sms_id;

    /**
     * @var int Max length is 11.
     */
    public $pdu_seq;

    /**
     * @var timestamp
     */
    public $pdu_sent_time;

    /**
     * @var timestamp
     */
    public $last_update;

    /**
     * @var text
     */
    public $deliver_sm;

    /**
     * @var text
     */
    public $deliver_sm_resp;

    public $_table = 'sc_smpp_client_dlr_pdustore';
    public $_primarykey = 'id';
    public $_fields = array('id','smppclient','sms_id','pdu_seq','pdu_sent_time','last_update','deliver_sm','deliver_sm_resp');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'smppclient' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'sms_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'pdu_seq' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'pdu_sent_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_update' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'deliver_sm' => array(
                        array( 'notnull' ),
                ),

                'deliver_sm_resp' => array(
                        array( 'notnull' ),
                )
            );
    }

}