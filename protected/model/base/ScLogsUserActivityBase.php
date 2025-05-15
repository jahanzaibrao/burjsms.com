<?php
Doo::loadCore('db/DooModel');

class ScLogsUserActivityBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var timestamp
     */
    public $act_time;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 50.
     */
    public $action_type;

    /**
     * @var varchar Max length is 500.
     */
    public $page_url;

    /**
     * @var varchar Max length is 500.
     */
    public $activity;

    /**
     * @var int Max length is 11.
     */
    public $flag;

    /**
     * @var varchar Max length is 20.
     */
    public $visitor_ip;

    /**
     * @var mediumtext
     */
    public $platform_data;

    public $_table = 'sc_logs_user_activity';
    public $_primarykey = 'id';
    public $_fields = array('id','act_time','user_id','action_type','page_url','activity','flag','visitor_ip','platform_data');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'act_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'action_type' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'page_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'activity' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'visitor_ip' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'platform_data' => array(
                        array( 'notnull' ),
                )
            );
    }

}