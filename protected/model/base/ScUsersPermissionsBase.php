<?php
Doo::loadCore('db/DooModel');

class ScUsersPermissionsBase extends DooModel{

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
    public $pg_id;

    /**
     * @var mediumtext
     */
    public $perm_data;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_users_permissions';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','pg_id','perm_data','last_mod');

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

                'pg_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'perm_data' => array(
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}