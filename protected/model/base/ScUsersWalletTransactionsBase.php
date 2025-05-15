<?php
Doo::loadCore('db/DooModel');

class ScUsersWalletTransactionsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $wallet_id;

    /**
     * @var int Max length is 11.
     */
    public $transac_type;

    /**
     * @var double
     */
    public $amount;

    /**
     * @var timestamp
     */
    public $t_date;

    /**
     * @var int Max length is 11.
     */
    public $linked_invoice;

    public $_table = 'sc_users_wallet_transactions';
    public $_primarykey = 'id';
    public $_fields = array('id','wallet_id','transac_type','amount','t_date','linked_invoice');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'wallet_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'transac_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'amount' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                't_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'linked_invoice' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}