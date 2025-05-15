<?php
Doo::loadCore('db/DooModel');

class ScWebsitesLeadsBase extends DooModel{

    /**
     * @var bigint Max length is 20.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $mode;

    /**
     * @var mediumtext
     */
    public $visitor_info;

    /**
     * @var int Max length is 11.
     */
    public $user_assoc;

    /**
     * @var timestamp
     */
    public $activity_date;

    /**
     * @var varchar Max length is 500.
     */
    public $web_url;

    /**
     * @var mediumtext
     */
    public $platform_data;

    /**
     * @var mediumtext
     */
    public $sms_data;

    public $_table = 'sc_websites_leads';
    public $_primarykey = 'id';
    public $_fields = array('id','mode','visitor_info','user_assoc','activity_date','web_url','platform_data','sms_data');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'mode' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'visitor_info' => array(
                        array( 'notnull' ),
                ),

                'user_assoc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'activity_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'web_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'platform_data' => array(
                        array( 'notnull' ),
                ),

                'sms_data' => array(
                        array( 'notnull' ),
                )
            );
    }

}