<?php
Doo::loadCore('db/DooModel');

class ScBlacklistIndexBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $admin_id;

    /**
     * @var varchar Max length is 200.
     */
    public $table_name;

    /**
     * @var varchar Max length is 200.
     */
    public $mobile_column;

    /**
     * @var int Max length is 11.
     */
    public $total_records;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_blacklist_index';
    public $_primarykey = 'id';
    public $_fields = array('id','admin_id','table_name','mobile_column','total_records','last_mod');

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

                'table_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'mobile_column' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'total_records' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}