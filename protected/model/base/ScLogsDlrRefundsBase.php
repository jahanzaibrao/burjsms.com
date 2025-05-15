<?php
Doo::loadCore('db/DooModel');

class ScLogsDlrRefundsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 50.
     */
    public $smsid;

    /**
     * @var int Max length is 11.
     */
    public $campaign_id;

    /**
     * @var varchar Max length is 100.
     */
    public $sms_shoot_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile_no;

    /**
     * @var varchar Max length is 100.
     */
    public $vendor_dlr;

    /**
     * @var double
     */
    public $refund_amt;

    /**
     * @var int Max length is 11.
     */
    public $refund_rule;

    /**
     * @var timestamp
     */
    public $timestamp;

    public $_table = 'sc_logs_dlr_refunds';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','smsid','campaign_id','sms_shoot_id','mobile_no','vendor_dlr','refund_amt','refund_rule','timestamp');

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

                'smsid' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'campaign_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sms_shoot_id' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'mobile_no' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'vendor_dlr' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'refund_amt' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'refund_rule' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'timestamp' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}