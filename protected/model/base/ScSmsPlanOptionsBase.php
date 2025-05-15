<?php
Doo::loadCore('db/DooModel');

class ScSmsPlanOptionsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $plan_id;

    /**
     * @var int Max length is 11.
     */
    public $plan_type;

    /**
     * @var varchar Max length is 100.
     */
    public $subopt_idn;

    /**
     * @var longtext
     */
    public $opt_data;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_sms_plan_options';
    public $_primarykey = 'id';
    public $_fields = array('id','plan_id','plan_type','subopt_idn','opt_data','last_mod');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
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
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'opt_data' => array(
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}