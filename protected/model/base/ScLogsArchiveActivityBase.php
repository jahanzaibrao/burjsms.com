<?php
Doo::loadCore('db/DooModel');

class ScLogsArchiveActivityBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var timestamp
     */
    public $timestamp;

    /**
     * @var int Max length is 11.
     */
    public $task_id;

    /**
     * @var text
     */
    public $activity;

    /**
     * @var int Max length is 11.
     */
    public $concern_flag;

    public $_table = 'sc_logs_archive_activity';
    public $_primarykey = 'id';
    public $_fields = array('id','timestamp','task_id','activity','concern_flag');

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

                'task_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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