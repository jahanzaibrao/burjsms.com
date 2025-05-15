<?php
Doo::loadCore('db/DooModel');

class ScUsersTlvValuesBase extends DooModel{

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
    public $assoc_route;

    /**
     * @var varchar Max length is 200.
     */
    public $tlv_category;

    /**
     * @var varchar Max length is 100.
     */
    public $tlv_title;

    /**
     * @var varchar Max length is 500.
     */
    public $tlv_value;

    public $_table = 'sc_users_tlv_values';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','assoc_route','tlv_category','tlv_title','tlv_value');

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

                'assoc_route' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'tlv_category' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'tlv_title' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'tlv_value' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                )
            );
    }

}