<?php
Doo::loadCore('db/DooModel');

class ScApiKeysBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 100.
     */
    public $api_key;

    /**
     * @var varchar Max length is 100.
     */
    public $dhash;

    public $_table = 'sc_api_keys';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','api_key','dhash');

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

                'api_key' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'dhash' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                )
            );
    }

}