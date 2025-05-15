<?php
Doo::loadCore('db/DooModel');

class ScApiVendorsBase extends DooModel{

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
    public $title;

    /**
     * @var varchar Max length is 100.
     */
    public $provider;

    /**
     * @var varchar Max length is 100.
     */
    public $smsc_id;

    /**
     * @var text
     */
    public $auth_data;

    /**
     * @var text
     */
    public $param_list;

    /**
     * @var varchar Max length is 100.
     */
    public $tlv_ids;

    /**
     * @var varchar Max length is 1000.
     */
    public $credits_api;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_api_vendors';
    public $_primarykey = 'id';
    public $_fields = array('id','admin_id','title','provider','smsc_id','auth_data','param_list','tlv_ids','credits_api','status');

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

                'title' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'provider' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'smsc_id' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'auth_data' => array(
                        array( 'notnull' ),
                ),

                'param_list' => array(
                        array( 'notnull' ),
                ),

                'tlv_ids' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'credits_api' => array(
                        array( 'maxlength', 1000 ),
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