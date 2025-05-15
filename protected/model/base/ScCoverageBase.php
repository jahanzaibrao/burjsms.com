<?php
Doo::loadCore('db/DooModel');

class ScCoverageBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 50.
     */
    public $country_code;

    /**
     * @var varchar Max length is 200.
     */
    public $country;

    /**
     * @var int Max length is 11.
     */
    public $prefix;

    /**
     * @var varchar Max length is 200.
     */
    public $valid_lengths;

    /**
     * @var varchar Max length is 500.
     */
    public $allowed_first_digits;

    /**
     * @var varchar Max length is 50.
     */
    public $timezone;

    /**
     * @var mediumtext
     */
    public $regulations;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_coverage';
    public $_primarykey = 'id';
    public $_fields = array('id','country_code','country','prefix','valid_lengths','allowed_first_digits','timezone','regulations','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'country_code' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'country' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'prefix' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'valid_lengths' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'allowed_first_digits' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'timezone' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'regulations' => array(
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