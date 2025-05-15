<?php
Doo::loadCore('db/DooModel');

class ScImportTasksBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $admin_id;

    /**
     * @var int Max length is 11.
     */
    public $table_id;

    /**
     * @var varchar Max length is 100.
     */
    public $file_name;

    /**
     * @var int Max length is 11.
     */
    public $total_records;

    /**
     * @var int Max length is 11.
     */
    public $records_done;

    /**
     * @var varchar Max length is 100.
     */
    public $filetype;

    /**
     * @var varchar Max length is 2.
     */
    public $mobile_column;

    /**
     * @var timestamp
     */
    public $uploaded_on;

    /**
     * @var timestamp
     */
    public $completed_on;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_import_tasks';
    public $_primarykey = 'id';
    public $_fields = array('id','admin_id','table_id','file_name','total_records','records_done','filetype','mobile_column','uploaded_on','completed_on','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'admin_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'table_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'file_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'total_records' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'records_done' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'filetype' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'mobile_column' => array(
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'uploaded_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'completed_on' => array(
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