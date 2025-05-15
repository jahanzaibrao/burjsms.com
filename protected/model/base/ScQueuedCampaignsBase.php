<?php
Doo::loadCore('db/DooModel');

class ScQueuedCampaignsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
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
     * @var varchar Max length is 10.
     */
    public $pushed_via;

    /**
     * @var int Max length is 11.
     */
    public $count;

    /**
     * @var int Max length is 11.
     */
    public $smscount;

    /**
     * @var varchar Max length is 500.
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
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_queued_campaigns';
    public $_primarykey = 'id';
    public $_fields = array('id','sms_shoot_id','user_id','route_id','sender_id','contacts','pushed_via','count','smscount','sms_type','sms_text','submission_time','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'sms_shoot_id' => array(
                        array( 'maxlength', 100 ),
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

                'pushed_via' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'count' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'smscount' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sms_type' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'sms_text' => array(
                        array( 'notnull' ),
                ),

                'submission_time' => array(
                        array( 'datetime' ),
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