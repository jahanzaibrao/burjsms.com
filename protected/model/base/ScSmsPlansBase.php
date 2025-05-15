<?php
Doo::loadCore('db/DooModel');

class ScSmsPlansBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $admin_id;

    /**
     * @var varchar Max length is 200.
     */
    public $plan_name;

    /**
     * @var varchar Max length is 200.
     */
    public $route_ids;

    /**
     * @var int Max length is 11.
     */
    public $plan_type;

    /**
     * @var double
     */
    public $tax;

    /**
     * @var char Max length is 2.
     */
    public $tax_type;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_sms_plans';
    public $_primarykey = 'id';
    public $_fields = array('id','admin_id','plan_name','route_ids','plan_type','tax','tax_type','last_mod');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'admin_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'plan_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'route_ids' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'plan_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'tax' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'tax_type' => array(
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}