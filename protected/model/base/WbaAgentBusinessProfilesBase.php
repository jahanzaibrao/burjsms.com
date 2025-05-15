<?php
Doo::loadCore('db/DooModel');

class WbaAgentBusinessProfilesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 200.
     */
    public $waba_id;

    /**
     * @var varchar Max length is 200.
     */
    public $phone_id;

    /**
     * @var varchar Max length is 100.
     */
    public $verified_name;

    /**
     * @var varchar Max length is 25.
     */
    public $display_phone;

    /**
     * @var varchar Max length is 10.
     */
    public $quality;

    /**
     * @var varchar Max length is 100.
     */
    public $throughput;

    /**
     * @var timestamp
     */
    public $last_onboarded_time;

    /**
     * @var varchar Max length is 200.
     */
    public $webhook;

    /**
     * @var varchar Max length is 200.
     */
    public $bp_about;

    /**
     * @var varchar Max length is 200.
     */
    public $bp_address;

    /**
     * @var varchar Max length is 200.
     */
    public $bp_email;

    /**
     * @var varchar Max length is 500.
     */
    public $bp_description;

    /**
     * @var varchar Max length is 500.
     */
    public $bp_websites;

    /**
     * @var varchar Max length is 500.
     */
    public $bp_profile_picture;

    /**
     * @var varchar Max length is 20.
     */
    public $bp_verticle;

    /**
     * @var timestamp
     */
    public $last_updated;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'wba_agent_business_profiles';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','waba_id','phone_id','verified_name','display_phone','quality','throughput','last_onboarded_time','webhook','bp_about','bp_address','bp_email','bp_description','bp_websites','bp_profile_picture','bp_verticle','last_updated','status');

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

                'waba_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'phone_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'verified_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'display_phone' => array(
                        array( 'maxlength', 25 ),
                        array( 'notnull' ),
                ),

                'quality' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'throughput' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'last_onboarded_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'webhook' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'bp_about' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'bp_address' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'bp_email' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'bp_description' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'bp_websites' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'bp_profile_picture' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'bp_verticle' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'last_updated' => array(
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