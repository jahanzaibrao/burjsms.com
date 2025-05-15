<?php
Doo::loadCore('db/DooModel');

class ScSmppClientsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $upline_id;

    /**
     * @var varchar Max length is 50.
     */
    public $system_id;

    /**
     * @var varchar Max length is 50.
     */
    public $smpp_password;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var varchar Max length is 1000.
     */
    public $allowed_ip;

    /**
     * @var int Max length is 11.
     */
    public $tx_max;

    /**
     * @var int Max length is 11.
     */
    public $rx_max;

    /**
     * @var int Max length is 11.
     */
    public $trx_max;

    /**
     * @var int Max length is 11.
     */
    public $tps_max;

    /**
     * @var int Max length is 11.
     */
    public $plan_id;

    /**
     * @var int Max length is 11.
     */
    public $vmn;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_smpp_clients';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','upline_id','system_id','smpp_password','route_id','allowed_ip','tx_max','rx_max','trx_max','tps_max','plan_id','vmn','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'upline_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'system_id' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'smpp_password' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'allowed_ip' => array(
                        array( 'maxlength', 1000 ),
                        array( 'notnull' ),
                ),

                'tx_max' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'rx_max' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'trx_max' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'tps_max' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'plan_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'vmn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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