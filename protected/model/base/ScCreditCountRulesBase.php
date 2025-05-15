<?php
Doo::loadCore('db/DooModel');

class ScCreditCountRulesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $rule_name;

    /**
     * @var mediumtext
     */
    public $normal_sms_rule;

    /**
     * @var mediumtext
     */
    public $unicode_rule;

    /**
     * @var mediumtext
     */
    public $special_chars_rule;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_credit_count_rules';
    public $_primarykey = 'id';
    public $_fields = array('id','rule_name','normal_sms_rule','unicode_rule','special_chars_rule','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'rule_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'normal_sms_rule' => array(
                        array( 'notnull' ),
                ),

                'unicode_rule' => array(
                        array( 'notnull' ),
                ),

                'special_chars_rule' => array(
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