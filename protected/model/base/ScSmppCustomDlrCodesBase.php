<?php
Doo::loadCore('db/DooModel');

class ScSmppCustomDlrCodesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $smpp_id;

    /**
     * @var varchar Max length is 50.
     */
    public $vendor_dlr_code;

    /**
     * @var varchar Max length is 50.
     */
    public $optional_custom_code;

    /**
     * @var varchar Max length is 100.
     */
    public $description;

    /**
     * @var int Max length is 11.
     */
    public $action;

    /**
     * @var int Max length is 11.
     */
    public $param_value;

    /**
     * @var int Max length is 11.
     */
    public $category;

    public $_table = 'sc_smpp_custom_dlr_codes';
    public $_primarykey = 'id';
    public $_fields = array('id','smpp_id','vendor_dlr_code','optional_custom_code','description','action','param_value','category');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'smpp_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'vendor_dlr_code' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'optional_custom_code' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'description' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'action' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'param_value' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'category' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}