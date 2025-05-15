<?php
Doo::loadCore('db/DooModel');

class ScVmnInboxBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 20.
     */
    public $mobile;

    /**
     * @var bigint Max length is 20.
     */
    public $vmn;

    /**
     * @var text
     */
    public $sms_text;

    /**
     * @var timestamp
     */
    public $receiving_time;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 500.
     */
    public $incoming_smsc;

    /**
     * @var text
     */
    public $req_url;

    /**
     * @var text
     */
    public $sms_data;

    public $_table = 'sc_vmn_inbox';
    public $_primarykey = 'id';
    public $_fields = array('id','mobile','vmn','sms_text','receiving_time','user_id','incoming_smsc','req_url','sms_data');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'mobile' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'vmn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'sms_text' => array(
                        array( 'notnull' ),
                ),

                'receiving_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'incoming_smsc' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'req_url' => array(
                        array( 'notnull' ),
                ),

                'sms_data' => array(
                        array( 'notnull' ),
                )
            );
    }

}