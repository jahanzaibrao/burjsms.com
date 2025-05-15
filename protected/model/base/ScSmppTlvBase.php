<?php
Doo::loadCore('db/DooModel');

class ScSmppTlvBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $tlv_title;

    /**
     * @var varchar Max length is 100.
     */
    public $tlv_category;

    /**
     * @var varchar Max length is 200.
     */
    public $tlv_name;

    /**
     * @var varchar Max length is 100.
     */
    public $tlv_tag;

    /**
     * @var varchar Max length is 100.
     */
    public $tlv_type;

    /**
     * @var int Max length is 11.
     */
    public $tlv_length;

    /**
     * @var varchar Max length is 200.
     */
    public $default_value;

    public $_table = 'sc_smpp_tlv';
    public $_primarykey = 'id';
    public $_fields = array('id','tlv_title','tlv_category','tlv_name','tlv_tag','tlv_type','tlv_length','default_value');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'tlv_title' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'tlv_category' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'tlv_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'tlv_tag' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'tlv_type' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'tlv_length' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'default_value' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                )
            );
    }

}