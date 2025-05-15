<?php
Doo::loadCore('db/DooModel');

class ScJobsDownloadBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 20.
     */
    public $mode;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 100.
     */
    public $file_name;

    /**
     * @var text
     */
    public $meta_data;

    /**
     * @var timestamp
     */
    public $added_on;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_jobs_download';
    public $_primarykey = 'id';
    public $_fields = array('id','mode','user_id','file_name','meta_data','added_on','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'mode' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'file_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'meta_data' => array(
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