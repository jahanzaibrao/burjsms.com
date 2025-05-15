<?php
Doo::loadCore('db/DooModel');

class WbaCostPriceBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 5.
     */
    public $country_code;

    /**
     * @var int Max length is 11.
     */
    public $country_prefix;

    /**
     * @var varchar Max length is 100.
     */
    public $meta_group;

    /**
     * @var float
     */
    public $cost_price_marketing;

    /**
     * @var float
     */
    public $cost_price_utility;

    /**
     * @var float
     */
    public $cost_price_auth;

    /**
     * @var float
     */
    public $cost_price_auth_int;

    /**
     * @var float
     */
    public $cost_price_service;

    /**
     * @var timestamp
     */
    public $last_update;

    public $_table = 'wba_cost_price';
    public $_primarykey = 'id';
    public $_fields = array('id','country_code','country_prefix','meta_group','cost_price_marketing','cost_price_utility','cost_price_auth','cost_price_auth_int','cost_price_service','last_update');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'country_code' => array(
                        array( 'maxlength', 5 ),
                        array( 'notnull' ),
                ),

                'country_prefix' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'meta_group' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'cost_price_marketing' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'cost_price_utility' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'cost_price_auth' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'cost_price_auth_int' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'cost_price_service' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'last_update' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}