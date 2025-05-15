<?php
Doo::loadCore('db/DooModel');

class ScUsersCreditDataBase extends DooModel{

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
    public $route_id;

    /**
     * @var bigint Max length is 20.
     */
    public $credits;

    /**
     * @var double
     */
    public $price;

    /**
     * @var timestamp
     */
    public $validity;

    /**
     * @var int Max length is 11.
     */
    public $delv_per;

    /**
     * @var int Max length is 11.
     */
    public $delv_threshold;

    /**
     * @var int Max length is 11.
     */
    public $fdlr_id;

    /**
     * @var timestamp
     */
    public $date_added;

    /**
     * @var timestamp
     */
    public $last_mod;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_users_credit_data';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','route_id','credits','price','validity','delv_per','delv_threshold','fdlr_id','date_added','last_mod','status');

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

                'route_id' => array(
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

                'validity' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'delv_per' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'delv_threshold' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'fdlr_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'date_added' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
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