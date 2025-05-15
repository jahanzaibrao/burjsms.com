<?php
Doo::loadCore('db/DooModel');

class ScVmnMissedcallsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $vmn_inbox_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var bigint Max length is 20.
     */
    public $vmn;

    /**
     * @var timestamp
     */
    public $call_time;

    public $_table = 'sc_vmn_missedcalls';
    public $_primarykey = 'id';
    public $_fields = array('id','vmn_inbox_id','mobile','vmn','call_time');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'vmn_inbox_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'vmn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'call_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}