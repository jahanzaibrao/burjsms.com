<?php
Doo::loadCore('db/DooModel');

class ScMccMncPlanPricingBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $plan_id;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var double
     */
    public $price;

    public $_table = 'sc_mcc_mnc_plan_pricing';
    public $_primarykey = 'id';
    public $_fields = array('id','plan_id','mccmnc','route_id','price');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'plan_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'price' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                )
            );
    }

}