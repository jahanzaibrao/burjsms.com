<?php
Doo::loadCore('db/DooModel');

class ScStatsSalesAdminBase extends DooModel{

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
    public $admin_id;

    /**
     * @var double
     */
    public $sale_amount;

    public $_table = 'sc_stats_sales_admin';
    public $_primarykey = 'id';
    public $_fields = array('id','c_date','admin_id','sale_amount');

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

                'admin_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sale_amount' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                )
            );
    }

}