<?php
Doo::loadCore('db/DooModel');

class ScLogsWatchmanBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var timestamp
     */
    public $timestamp;

    /**
     * @var mediumtext
     */
    public $activity;

    /**
     * @var int Max length is 11.
     */
    public $concern_flag;

    public $_table = 'sc_logs_watchman';
    public $_primarykey = 'id';
    public $_fields = array('id','timestamp','activity','concern_flag');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'timestamp' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'activity' => array(
                        array( 'notnull' ),
                ),

                'concern_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}