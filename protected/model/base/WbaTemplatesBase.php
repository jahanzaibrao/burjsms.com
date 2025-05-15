<?php
Doo::loadCore('db/DooModel');

class WbaTemplatesBase extends DooModel{

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
    public $name;

    /**
     * @var varchar Max length is 500.
     */
    public $category_info;

    /**
     * @var text
     */
    public $meta_info;

    /**
     * @var text
     */
    public $components;

    /**
     * @var timestamp
     */
    public $added_on;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'wba_templates';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','name','category_info','meta_info','components','added_on','status');

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

                'name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'category_info' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'meta_info' => array(
                        array( 'notnull' ),
                ),

                'components' => array(
                        array( 'notnull' ),
                ),

                'added_on' => array(
                        array( 'datetime' ),
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