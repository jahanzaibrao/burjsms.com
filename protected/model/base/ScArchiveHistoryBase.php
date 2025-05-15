<?php
Doo::loadCore('db/DooModel');

class ScArchiveHistoryBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var timestamp
     */
    public $archive_time;

    /**
     * @var int Max length is 11.
     */
    public $task_id;

    /**
     * @var timestamp
     */
    public $selected_date;

    /**
     * @var bigint Max length is 20.
     */
    public $records_moved;

    /**
     * @var timestamp
     */
    public $completed_on;

    public $_table = 'sc_archive_history';
    public $_primarykey = 'id';
    public $_fields = array('id','archive_time','task_id','selected_date','records_moved','completed_on');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'archive_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'task_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'selected_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'records_moved' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'completed_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}