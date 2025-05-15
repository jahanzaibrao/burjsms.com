<?php
Doo::loadCore('db/DooModel');

class ScArchiveTasksBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $task_type;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 500.
     */
    public $file_id;

    /**
     * @var varchar Max length is 500.
     */
    public $date_range;

    /**
     * @var timestamp
     */
    public $added_on;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_archive_tasks';
    public $_primarykey = 'id';
    public $_fields = array('id','task_type','user_id','file_id','date_range','added_on','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'task_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'file_id' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'date_range' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'added_on' => array(
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