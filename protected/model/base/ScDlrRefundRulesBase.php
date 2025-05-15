<?php
Doo::loadCore('db/DooModel');

class ScDlrRefundRulesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var varchar Max length is 500.
     */
    public $description;

    public $_table = 'sc_dlr_refund_rules';
    public $_primarykey = 'id';
    public $_fields = array('id','title','description');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'title' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'description' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                )
            );
    }

}