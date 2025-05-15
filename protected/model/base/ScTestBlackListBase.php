<?php
Doo::loadCore('db/DooModel');

class ScTestBlackListBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var bigint Max length is 20.
     */
    public $phones;

    public $_table = 'sc_test_BlackList';
    public $_primarykey = 'id';
    public $_fields = array('id','phones');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'phones' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                )
            );
    }

}