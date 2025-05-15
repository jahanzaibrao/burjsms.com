<?php
Doo::loadCore('db/DooModel');

class ScUsersCompanyBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 200.
     */
    public $c_name;

    /**
     * @var varchar Max length is 500.
     */
    public $c_address;

    /**
     * @var varchar Max length is 50.
     */
    public $c_phone;

    /**
     * @var varchar Max length is 100.
     */
    public $c_email;

    /**
     * @var varchar Max length is 100.
     */
    public $c_vat;

    /**
     * @var varchar Max length is 100.
     */
    public $c_gst;

    /**
     * @var varchar Max length is 100.
     */
    public $c_stax;

    /**
     * @var varchar Max length is 100.
     */
    public $c_regno;

    /**
     * @var varchar Max length is 1000.
     */
    public $c_payment;

    public $_table = 'sc_users_company';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','c_name','c_address','c_phone','c_email','c_vat','c_gst','c_stax','c_regno','c_payment');

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

                'c_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'c_address' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'c_phone' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'c_email' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'c_vat' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'c_gst' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'c_stax' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'c_regno' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'c_payment' => array(
                        array( 'maxlength', 1000 ),
                        array( 'notnull' ),
                )
            );
    }

}