<?php
Doo::loadCore('db/DooModel');

class ScOverrideRulesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $rule_title;

    /**
     * @var text
     */
    public $sender_rules;

    /**
     * @var text
     */
    public $msisdn_rules;

    /**
     * @var text
     */
    public $mtext_rules;

    /**
     * @var timestamp
     */
    public $last_update;

    public $_table = 'sc_override_rules';
    public $_primarykey = 'id';
    public $_fields = array('id','rule_title','sender_rules','msisdn_rules','mtext_rules','last_update');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'rule_title' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'sender_rules' => array(
                        array( 'notnull' ),
                ),

                'msisdn_rules' => array(
                        array( 'notnull' ),
                ),

                'mtext_rules' => array(
                        array( 'notnull' ),
                ),

                'last_update' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}