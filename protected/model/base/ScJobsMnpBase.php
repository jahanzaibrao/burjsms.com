<?php
Doo::loadCore('db/DooModel');

class ScJobsMnpBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $task_type;

    /**
     * @var varchar Max length is 100.
     */
    public $file_name;

    /**
     * @var int Max length is 11.
     */
    public $coverage;

    /**
     * @var int Max length is 11.
     */
    public $format_flag;

    /**
     * @var int Max length is 11.
     */
    public $total_msisdn;

    /**
     * @var int Max length is 11.
     */
    public $done_msisdn;

    /**
     * @var timestamp
     */
    public $start_date;

    /**
     * @var timestamp
     */
    public $last_run;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_jobs_mnp';
    public $_primarykey = 'id';
    public $_fields = array('id','task_type','file_name','coverage','format_flag','total_msisdn','done_msisdn','start_date','last_run','status');

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

                'file_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'coverage' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'format_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'total_msisdn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'done_msisdn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'start_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_run' => array(
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