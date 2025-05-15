<?php
Doo::loadCore('db/DooModel');

class ScSmsSummaryBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $campaign_id;

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
     * @var varchar Max length is 20.
     */
    public $sender_id;

    /**
     * @var int Max length is 11.
     */
    public $total_contacts;

    /**
     * @var int Max length is 11.
     */
    public $dropped_contacts;

    /**
     * @var int Max length is 11.
     */
    public $invalid_contacts;

    /**
     * @var int Max length is 11.
     */
    public $blacklist_contacts;

    /**
     * @var int Max length is 11.
     */
    public $rejected_contacts;

    /**
     * @var int Max length is 11.
     */
    public $duplicates_removed;

    /**
     * @var int Max length is 11.
     */
    public $total_sms;

    /**
     * @var double
     */
    public $total_cost;

    /**
     * @var int Max length is 11.
     */
    public $cc_rule;

    /**
     * @var varchar Max length is 10.
     */
    public $pushed_via;

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
     * @var timestamp
     */
    public $sent_time;

    /**
     * @var varchar Max length is 500.
     */
    public $schedule_data;

    /**
     * @var int Max length is 11.
     */
    public $hide_mobile;

    /**
     * @var varchar Max length is 200.
     */
    public $contacts_label;

    /**
     * @var varchar Max length is 500.
     */
    public $tlv_data;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var longtext
     */
    public $platform_data;

    public $_table = 'sc_sms_summary';
    public $_primarykey = 'id';
    public $_fields = array('id','campaign_id','sms_shoot_id','user_id','route_id','sender_id','total_contacts','dropped_contacts','invalid_contacts','blacklist_contacts','rejected_contacts','duplicates_removed','total_sms','total_cost','cc_rule','pushed_via','sms_type','sms_text','submission_time','sent_time','schedule_data','hide_mobile','contacts_label','tlv_data','status','platform_data');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'campaign_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
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

                'sender_id' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'total_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'dropped_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'invalid_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'blacklist_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'rejected_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'duplicates_removed' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'total_sms' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'total_cost' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'cc_rule' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'pushed_via' => array(
                        array( 'maxlength', 10 ),
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

                'sent_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'schedule_data' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'hide_mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'contacts_label' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'tlv_data' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'platform_data' => array(
                        array( 'notnull' ),
                )
            );
    }

}