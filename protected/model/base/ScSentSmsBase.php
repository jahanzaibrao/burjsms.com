<?php
Doo::loadCore('db/DooModel');

class ScSentSmsBase extends DooModel{

    /**
     * @var bigint Max length is 20.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $sms_shoot_id;

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
     * @var varchar Max length is 20.
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
     * @var text
     */
    public $sms_text;

    /**
     * @var timestamp
     */
    public $submission_time;

    /**
     * @var timestamp
     */
    public $sent_time;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var double
     */
    public $cost;

    /**
     * @var varchar Max length is 50.
     */
    public $umsgid;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var varchar Max length is 50.
     */
    public $smpp_resp_code;

    /**
     * @var int Max length is 11.
     */
    public $dlr;

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
     * @var int Max length is 11.
     */
    public $url_visit_flag;

    /**
     * @var timestamp
     */
    public $url_visit_ts;

    /**
     * @var mediumtext
     */
    public $url_visit_platform;

    /**
     * @var mediumtext
     */
    public $msgdata;

    /**
     * @var int Max length is 11.
     */
    public $es_index_status;

    /**
     * @var varchar Max length is 100.
     */
    public $es_index_id;

    public $_table = 'sc_sent_sms';
    public $_primarykey = 'id';
    public $_fields = array('id','sms_shoot_id','user_id','route_id','smsc','sender_id','mobile','sms_type','sms_text','submission_time','sent_time','mccmnc','cost','umsgid','status','smpp_resp_code','dlr','vendor_dlr','vendor_msgid','dlr_updated_on','url_visit_flag','url_visit_ts','url_visit_platform','msgdata','es_index_status','es_index_id');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'sms_shoot_id' => array(
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
                        array( 'maxlength', 20 ),
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

                'submission_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'sent_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'cost' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'umsgid' => array(
                        array( 'maxlength', 50 ),
                        array( 'optional' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'smpp_resp_code' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'dlr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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

                'url_visit_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'url_visit_ts' => array(
                        array( 'datetime' ),
                        array( 'optional' ),
                ),

                'url_visit_platform' => array(
                        array( 'notnull' ),
                ),

                'msgdata' => array(
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