<?php
Doo::loadCore('db/DooModel');

class ScLongcourseCampaignsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
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
     * @var int Max length is 11.
     */
    public $sender_id;

    /**
     * @var longtext
     */
    public $contacts;

    /**
     * @var int Max length is 11.
     */
    public $sms_count;

    /**
     * @var double
     */
    public $price;

    /**
     * @var int Max length is 11.
     */
    public $total_contacts;

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
    public $start_time;

    /**
     * @var int Max length is 11.
     */
    public $submission_interval;

    /**
     * @var int Max length is 11.
     */
    public $submission_days;

    /**
     * @var int Max length is 11.
     */
    public $send_flag;

    /**
     * @var int Max length is 11.
     */
    public $sms_status;

    /**
     * @var timestamp
     */
    public $last_sent_time;

    /**
     * @var int Max length is 11.
     */
    public $dlr;

    /**
     * @var varchar Max length is 10.
     */
    public $vendor_dlr;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_longcourse_campaigns';
    public $_primarykey = 'id';
    public $_fields = array('id','sms_shoot_id','user_id','route_id','sender_id','contacts','sms_count','price','total_contacts','sms_type','sms_text','submission_time','start_time','submission_interval','submission_days','send_flag','sms_status','last_sent_time','dlr','vendor_dlr','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'sms_shoot_id' => array(
                        array( 'maxlength', 500 ),
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

                'sender_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'contacts' => array(
                        array( 'notnull' ),
                ),

                'sms_count' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'price' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'total_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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

                'start_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'submission_interval' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'submission_days' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'send_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sms_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_sent_time' => array(
                        array( 'datetime' ),
                        array( 'optional' ),
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

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}