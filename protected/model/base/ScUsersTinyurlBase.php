<?php
Doo::loadCore('db/DooModel');

class ScUsersTinyurlBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 20.
     */
    public $domain;

    /**
     * @var timestamp
     */
    public $added_on;

    public $_table = 'sc_users_tinyurl';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','domain','added_on');

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

                'domain' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'added_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}