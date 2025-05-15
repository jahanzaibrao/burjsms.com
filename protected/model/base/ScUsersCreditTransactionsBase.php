<?php
Doo::loadCore('db/DooModel');

class ScUsersCreditTransactionsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
     */
    public $transac_id;

    /**
     * @var int Max length is 11.
     */
    public $type;

    /**
     * @var bigint Max length is 20.
     */
    public $credits;

    /**
     * @var double
     */
    public $price;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var timestamp
     */
    public $transac_date;

    /**
     * @var int Max length is 11.
     */
    public $transac_by;

    /**
     * @var int Max length is 11.
     */
    public $transac_to_user;

    /**
     * @var int Max length is 11.
     */
    public $invoice_id;

    public $_table = 'sc_users_credit_transactions';
    public $_primarykey = 'id';
    public $_fields = array('id','transac_id','type','credits','price','route_id','transac_date','transac_by','transac_to_user','invoice_id');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'transac_id' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'credits' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'price' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'transac_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'transac_by' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'transac_to_user' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'invoice_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}