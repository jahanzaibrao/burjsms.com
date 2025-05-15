<?php
Doo::loadCore('db/DooModel');

class ScUsersWhitelistBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var longtext
     */
    public $mobiles;

    public $_table = 'sc_users_whitelist';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','mobiles');

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

                'mobiles' => array(
                        array( 'notnull' ),
                )
            );
    }

}