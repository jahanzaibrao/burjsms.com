<?php
Doo::loadCore('db/DooModel');

class ScSmsRoutesPricingBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var double
     */
    public $cost_price;

    /**
     * @var double
     */
    public $default_selling_price;

    public $_table = 'sc_sms_routes_pricing';
    public $_primarykey = 'id';
    public $_fields = array('id','route_id','mccmnc','cost_price','default_selling_price');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'route_id' => array(
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
                ),

                'default_selling_price' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                )
            );
    }

}