<?php
Doo::loadCore('db/DooModel');

class ScMccMncListBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 20.
     */
    public $nw_type;

    /**
     * @var varchar Max length is 200.
     */
    public $country_name;

    /**
     * @var varchar Max length is 15.
     */
    public $country_iso;

    /**
     * @var int Max length is 11.
     */
    public $country_code;

    /**
     * @var int Max length is 11.
     */
    public $mcc;

    /**
     * @var int Max length is 11.
     */
    public $mnc;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var varchar Max length is 200.
     */
    public $brand;

    /**
     * @var varchar Max length is 200.
     */
    public $operator;

    /**
     * @var varchar Max length is 200.
     */
    public $bands;

    /**
     * @var varchar Max length is 500.
     */
    public $notes;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_mcc_mnc_list';
    public $_primarykey = 'id';
    public $_fields = array('id','nw_type','country_name','country_iso','country_code','mcc','mnc','mccmnc','brand','operator','bands','notes','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'nw_type' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'country_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'country_iso' => array(
                        array( 'maxlength', 15 ),
                        array( 'notnull' ),
                ),

                'country_code' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mcc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
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
                ),

                'bands' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'notes' => array(
                        array( 'maxlength', 500 ),
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