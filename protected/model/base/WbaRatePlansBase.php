<?php
Doo::loadCore('db/DooModel');

class WbaRatePlansBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $plan_name;

    /**
     * @var varchar Max length is 100.
     */
    public $profit_margin;

    /**
     * @var float
     */
    public $tax;

    /**
     * @var varchar Max length is 100.
     */
    public $tax_type;

    /**
     * @var float
     */
    public $nrm;

    /**
     * @var varchar Max length is 1000.
     */
    public $allowed_countries;

    /**
     * @var int Max length is 11.
     */
    public $is_default;

    public $_table = 'wba_rate_plans';
    public $_primarykey = 'id';
    public $_fields = array('id','plan_name','profit_margin','tax','tax_type','nrm','allowed_countries','is_default');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'plan_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'profit_margin' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'tax' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'tax_type' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'nrm' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'allowed_countries' => array(
                        array( 'maxlength', 1000 ),
                        array( 'notnull' ),
                ),

                'is_default' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}