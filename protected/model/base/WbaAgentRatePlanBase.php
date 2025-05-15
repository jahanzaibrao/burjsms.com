<?php
Doo::loadCore('db/DooModel');

class WbaAgentRatePlanBase extends DooModel{

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
    public $waba_id;

    /**
     * @var int Max length is 11.
     */
    public $plan_id;

    public $_table = 'wba_agent_rate_plan';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','waba_id','plan_id');

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

                'waba_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'plan_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}