<?php
Doo::loadCore('db/DooModel');

class WbaRatePlanPriceBase extends DooModel{

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
    public $zone_id;

    /**
     * @var float
     */
    public $marketing;

    /**
     * @var float
     */
    public $utility;

    /**
     * @var float
     */
    public $cp_auth;

    /**
     * @var float
     */
    public $auth_int;

    /**
     * @var float
     */
    public $cp_ser;

    public $_table = 'wba_rate_plan_price';
    public $_primarykey = 'id';
    public $_fields = array('id','plan_id','zone_id','marketing','utility','cp_auth','auth_int','cp_ser');

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

                'zone_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'marketing' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'utility' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'cp_auth' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'auth_int' => array(
                        array( 'float' ),
                        array( 'optional' ),
                ),

                'cp_ser' => array(
                        array( 'float' ),
                        array( 'optional' ),
                )
            );
    }

}