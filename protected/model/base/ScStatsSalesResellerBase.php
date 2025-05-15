<?php
Doo::loadCore('db/DooModel');

class ScStatsSalesResellerBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 50.
     */
    public $c_date;

    /**
     * @var int Max length is 11.
     */
    public $reseller_id;

    /**
     * @var int Max length is 11.
     */
    public $new_users_today;

    /**
     * @var bigint Max length is 20.
     */
    public $sms_sold_today;

    public $_table = 'sc_stats_sales_reseller';
    public $_primarykey = 'id';
    public $_fields = array('id','c_date','reseller_id','new_users_today','sms_sold_today');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'c_date' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'reseller_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'new_users_today' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sms_sold_today' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                )
            );
    }

}