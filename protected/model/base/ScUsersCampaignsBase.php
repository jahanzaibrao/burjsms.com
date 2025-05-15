<?php
Doo::loadCore('db/DooModel');

class ScUsersCampaignsBase extends DooModel{

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
    public $campaign_name;

    /**
     * @var varchar Max length is 500.
     */
    public $campaign_desc;

    /**
     * @var int Max length is 11.
     */
    public $primary_keyword_id;

    /**
     * @var text
     */
    public $optin_keywords;

    /**
     * @var text
     */
    public $optout_keywords;

    /**
     * @var text
     */
    public $optin_reply_sms;

    /**
     * @var text
     */
    public $optout_reply_sms;

    /**
     * @var int Max length is 11.
     */
    public $default_sms_route;

    /**
     * @var varchar Max length is 50.
     */
    public $default_sender;

    /**
     * @var int Max length is 11.
     */
    public $is_default;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_users_campaigns';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','campaign_name','campaign_desc','primary_keyword_id','optin_keywords','optout_keywords','optin_reply_sms','optout_reply_sms','default_sms_route','default_sender','is_default','status');

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

                'campaign_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'campaign_desc' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'primary_keyword_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'optin_keywords' => array(
                        array( 'notnull' ),
                ),

                'optout_keywords' => array(
                        array( 'notnull' ),
                ),

                'optin_reply_sms' => array(
                        array( 'notnull' ),
                ),

                'optout_reply_sms' => array(
                        array( 'notnull' ),
                ),

                'default_sms_route' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'default_sender' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'is_default' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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