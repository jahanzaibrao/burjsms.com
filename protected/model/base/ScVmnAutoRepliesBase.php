<?php
Doo::loadCore('db/DooModel');

class ScVmnAutoRepliesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $reply_against_sms_id;

    /**
     * @var int Max length is 11.
     */
    public $campaign_id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var bigint Max length is 20.
     */
    public $vmn;

    /**
     * @var varchar Max length is 100.
     */
    public $primary_keyword;

    /**
     * @var varchar Max length is 100.
     */
    public $other_keyword;

    /**
     * @var text
     */
    public $sent_sms_text;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var timestamp
     */
    public $sending_time;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var varchar Max length is 50.
     */
    public $sender;

    /**
     * @var varchar Max length is 2000.
     */
    public $api_response;

    public $_table = 'sc_vmn_auto_replies';
    public $_primarykey = 'id';
    public $_fields = array('id','reply_against_sms_id','campaign_id','user_id','vmn','primary_keyword','other_keyword','sent_sms_text','mobile','sending_time','route_id','sender','api_response');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'reply_against_sms_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'campaign_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'vmn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'primary_keyword' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'other_keyword' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'sent_sms_text' => array(
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'sending_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sender' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'api_response' => array(
                        array( 'maxlength', 2000 ),
                        array( 'notnull' ),
                )
            );
    }

}