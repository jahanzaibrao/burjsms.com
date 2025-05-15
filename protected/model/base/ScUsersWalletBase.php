<?php
Doo::loadCore('db/DooModel');

class ScUsersWalletBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $wallet_code;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var double
     */
    public $amount;

    /**
     * @var timestamp
     */
    public $expiry_date;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_users_wallet';
    public $_primarykey = 'id';
    public $_fields = array('id','wallet_code','user_id','amount','expiry_date','last_mod');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'wallet_code' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'amount' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'expiry_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}