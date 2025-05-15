<?php
Doo::loadCore('db/DooModel');

class ScUsersTlvDefaultsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 50.
     */
    public $tlv_category;

    /**
     * @var varchar Max length is 100.
     */
    public $custom_label;

    /**
     * @var varchar Max length is 200.
     */
    public $default_value;

    public $_table = 'sc_users_tlv_defaults';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','tlv_category','custom_label','default_value');

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

                'tlv_category' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'custom_label' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'default_value' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                )
            );
    }

}