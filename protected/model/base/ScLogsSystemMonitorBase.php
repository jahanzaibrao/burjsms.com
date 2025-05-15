<?php
Doo::loadCore('db/DooModel');

class ScLogsSystemMonitorBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var date
     */
    public $day;

    /**
     * @var varchar Max length is 10.
     */
    public $channel;

    /**
     * @var int Max length is 11.
     */
    public $peak_rate;

    /**
     * @var int Max length is 11.
     */
    public $total;

    /**
     * @var int Max length is 11.
     */
    public $flag_status;

    public $_table = 'sc_logs_system_monitor';
    public $_primarykey = 'id';
    public $_fields = array('id','day','channel','peak_rate','total','flag_status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'day' => array(
                        array( 'date' ),
                        array( 'notnull' ),
                ),

                'channel' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'peak_rate' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'total' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'flag_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}