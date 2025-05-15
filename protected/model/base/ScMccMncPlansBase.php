<?php
Doo::loadCore('db/DooModel');

class ScMccMncPlansBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $plan_name;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var float
     */
    public $tax;

    /**
     * @var varchar Max length is 100.
     */
    public $tax_type;

    /**
     * @var double
     */
    public $default_profit;

    /**
     * @var int Max length is 11.
     */
    public $default_profit_type;

    /**
     * @var double
     */
    public $nonref_amount;

    /**
     * @var text
     */
    public $plan_features;

    /**
     * @var varchar Max length is 500.
     */
    public $route_coverage;

    /**
     * @var int Max length is 11.
     */
    public $pricing_preference;

    public $_table = 'sc_mcc_mnc_plans';
    public $_primarykey = 'id';
    public $_fields = array('id','plan_name','route_id','tax','tax_type','default_profit','default_profit_type','nonref_amount','plan_features','route_coverage','pricing_preference');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'plan_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'tax' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'tax_type' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'default_profit' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'default_profit_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'nonref_amount' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'plan_features' => array(
                        array( 'notnull' ),
                ),

                'route_coverage' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'pricing_preference' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}