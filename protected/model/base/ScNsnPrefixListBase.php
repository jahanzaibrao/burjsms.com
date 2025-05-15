<?php
Doo::loadCore('db/DooModel');

class ScNsnPrefixListBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $prefix;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var varchar Max length is 5.
     */
    public $country_iso;

    /**
     * @var int Max length is 11.
     */
    public $country_prefix;

    /**
     * @var varchar Max length is 200.
     */
    public $brand;

    /**
     * @var varchar Max length is 200.
     */
    public $operator;

    public $_table = 'sc_nsn_prefix_list';
    public $_primarykey = 'id';
    public $_fields = array('id','prefix','mccmnc','country_iso','country_prefix','brand','operator');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'prefix' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'country_iso' => array(
                        array( 'maxlength', 5 ),
                        array( 'notnull' ),
                ),

                'country_prefix' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'brand' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'operator' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                )
            );
    }

}