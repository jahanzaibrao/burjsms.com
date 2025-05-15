<?php
Doo::loadCore('db/DooModel');

class WbaDefMetaPriceBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 177.
     */
    public $market;

    /**
     * @var varchar Max length is 8.
     */
    public $currency;

    /**
     * @var varchar Max length is 9.
     */
    public $marketing;

    /**
     * @var varchar Max length is 7.
     */
    public $utility;

    /**
     * @var varchar Max length is 14.
     */
    public $cp_auth;

    /**
     * @var varchar Max length is 28.
     */
    public $auth_int;

    /**
     * @var varchar Max length is 7.
     */
    public $cp_ser;

    public $_table = 'wba_def_meta_price';
    public $_primarykey = 'id';
    public $_fields = array('id','market','currency','marketing','utility','cp_auth','auth_int','cp_ser');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'market' => array(
                        array( 'maxlength', 177 ),
                        array( 'optional' ),
                ),

                'currency' => array(
                        array( 'maxlength', 8 ),
                        array( 'optional' ),
                ),

                'marketing' => array(
                        array( 'maxlength', 9 ),
                        array( 'optional' ),
                ),

                'utility' => array(
                        array( 'maxlength', 7 ),
                        array( 'optional' ),
                ),

                'cp_auth' => array(
                        array( 'maxlength', 14 ),
                        array( 'optional' ),
                ),

                'auth_int' => array(
                        array( 'maxlength', 28 ),
                        array( 'optional' ),
                ),

                'cp_ser' => array(
                        array( 'maxlength', 7 ),
                        array( 'optional' ),
                )
            );
    }

}