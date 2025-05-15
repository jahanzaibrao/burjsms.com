<?php
Doo::loadCore('db/DooModel');

class ScMiscVarsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 50.
     */
    public $var_name;

    /**
     * @var text
     */
    public $var_value;

    /**
     * @var int Max length is 11.
     */
    public $var_status;

    /**
     * @var timestamp
     */
    public $last_updated;

    public $_table = 'sc_misc_vars';
    public $_primarykey = 'id';
    public $_fields = array('id','var_name','var_value','var_status','last_updated');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'var_name' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'var_value' => array(
                        array( 'notnull' ),
                ),

                'var_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_updated' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}