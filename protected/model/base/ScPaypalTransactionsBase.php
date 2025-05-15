<?php
Doo::loadCore('db/DooModel');

class ScPaypalTransactionsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 10.
     */
    public $invoice_id;

    /**
     * @var int Max length is 11.
     */
    public $payer_userid;

    /**
     * @var int Max length is 11.
     */
    public $receiver_userid;

    /**
     * @var varchar Max length is 255.
     */
    public $txn_id;

    /**
     * @var float Max length is 10. ,2).
     */
    public $payment_gross;

    /**
     * @var varchar Max length is 5.
     */
    public $currency_code;

    /**
     * @var varchar Max length is 20.
     */
    public $payer_id;

    /**
     * @var varchar Max length is 200.
     */
    public $payer_name;

    /**
     * @var varchar Max length is 200.
     */
    public $payer_email;

    /**
     * @var varchar Max length is 5.
     */
    public $payer_country;

    /**
     * @var varchar Max length is 10.
     */
    public $payment_status;

    /**
     * @var timestamp
     */
    public $added_on;

    public $_table = 'sc_paypal_transactions';
    public $_primarykey = 'id';
    public $_fields = array('id','invoice_id','payer_userid','receiver_userid','txn_id','payment_gross','currency_code','payer_id','payer_name','payer_email','payer_country','payment_status','added_on');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'invoice_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'payer_userid' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'receiver_userid' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'txn_id' => array(
                        array( 'maxlength', 255 ),
                        array( 'notnull' ),
                ),

                'payment_gross' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'currency_code' => array(
                        array( 'maxlength', 5 ),
                        array( 'notnull' ),
                ),

                'payer_id' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'payer_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'payer_email' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'payer_country' => array(
                        array( 'maxlength', 5 ),
                        array( 'notnull' ),
                ),

                'payment_status' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'added_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}