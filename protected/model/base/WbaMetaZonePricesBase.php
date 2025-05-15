<?php
Doo::loadCore('db/DooModel');

class WbaMetaZonePricesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 177.
     */
    public $zone;

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

    public $_table = 'wba_meta_zone_prices';
    public $_primarykey = 'id';
    public $_fields = array('id','zone','marketing','utility','cp_auth','auth_int','cp_ser');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'zone' => array(
                        array( 'maxlength', 177 ),
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