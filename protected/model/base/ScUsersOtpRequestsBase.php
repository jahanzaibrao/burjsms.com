<?php
Doo::loadCore('db/DooModel');

class ScUsersOtpRequestsBase extends DooModel{

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
    public $channel_id;

    /**
     * @var int Max length is 11.
     */
    public $attempts;

    /**
     * @var varchar Max length is 20.
     */
    public $reference;

    /**
     * @var varchar Max length is 500.
     */
    public $otp;

    /**
     * @var varchar Max length is 1000.
     */
    public $smsresponse;

    /**
     * @var timestamp
     */
    public $added_on;

    public $_table = 'sc_users_otp_requests';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','channel_id','attempts','reference','otp','smsresponse','added_on');

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

                'channel_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'attempts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'reference' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'otp' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'smsresponse' => array(
                        array( 'maxlength', 1000 ),
                        array( 'notnull' ),
                ),

                'added_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}