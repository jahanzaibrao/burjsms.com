<?php
Doo::loadCore('db/DooModel');

class ScTempCampaignsBase extends DooModel{

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
     * @var longtext
     */
    public $dropped_contacts;

    /**
     * @var longtext
     */
    public $invalid_contacts;

    /**
     * @var longtext
     */
    public $blacklist_contacts;

    /**
     * @var int Max length is 11.
     */
    public $duplicates_removed;

    /**
     * @var int Max length is 11.
     */
    public $smscount;

    /**
     * @var int Max length is 11.
     */
    public $cc_rule;

    /**
     * @var int Max length is 11.
     */
    public $credits_charged;

    /**
     * @var varchar Max length is 10.
     */
    public $pushed_via;

    /**
     * @var int Max length is 11.
     */
    public $count;

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
     * @var varchar Max length is 500.
     */
    public $schedule_data;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_temp_campaigns';
    public $_primarykey = 'id';
    public $_fields = array('id','sms_shoot_id','user_id','route_id','sender_id','contacts','dropped_contacts','invalid_contacts','blacklist_contacts','duplicates_removed','smscount','cc_rule','credits_charged','pushed_via','count','sms_type','sms_text','submission_time','schedule_data','status');

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

                'dropped_contacts' => array(
                        array( 'notnull' ),
                ),

                'invalid_contacts' => array(
                        array( 'notnull' ),
                ),

                'blacklist_contacts' => array(
                        array( 'notnull' ),
                ),

                'duplicates_removed' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'smscount' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'cc_rule' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'credits_charged' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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

                'schedule_data' => array(
                        array( 'maxlength', 500 ),
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