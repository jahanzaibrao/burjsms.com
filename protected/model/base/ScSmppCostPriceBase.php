<?php
Doo::loadCore('db/DooModel');

class ScSmppCostPriceBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $smpp_id;

    /**
     * @var int Max length is 11.
     */
    public $country_prefix;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var float
     */
    public $cost_price;

    public $_table = 'sc_smpp_cost_price';
    public $_primarykey = 'id';
    public $_fields = array('id','smpp_id','country_prefix','mccmnc','cost_price');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'smpp_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'country_prefix' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'cost_price' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                )
            );
    }

}