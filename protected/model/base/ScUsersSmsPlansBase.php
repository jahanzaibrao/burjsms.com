<?php
Doo::loadCore('db/DooModel');

class ScUsersSmsPlansBase extends DooModel{

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
    public $plan_id;

    /**
     * @var int Max length is 11.
     */
    public $plan_type;

    /**
     * @var varchar Max length is 500.
     */
    public $subopt_idn;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_users_sms_plans';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','plan_id','plan_type','subopt_idn','last_mod');

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

                'plan_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'plan_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'subopt_idn' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}