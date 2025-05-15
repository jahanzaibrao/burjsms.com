<?php
Doo::loadCore('db/DooModel');

class ScAppProcessesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $process_name;

    /**
     * @var int Max length is 11.
     */
    public $manual_flag;

    /**
     * @var int Max length is 11.
     */
    public $master_status;

    /**
     * @var timestamp
     */
    public $last_pulse;

    public $_table = 'sc_app_processes';
    public $_primarykey = 'id';
    public $_fields = array('id','process_name','manual_flag','master_status','last_pulse');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'process_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'manual_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'master_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_pulse' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}