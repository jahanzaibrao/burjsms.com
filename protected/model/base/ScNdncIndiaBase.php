<?php
Doo::loadCore('db/DooModel');

class ScNdncIndiaBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var bigint Max length is 20.
     */
    public $msisdn;

    public $_table = 'sc_ndnc_india';
    public $_primarykey = 'id';
    public $_fields = array('id','msisdn');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'msisdn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                )
            );
    }

}