<?php
Doo::loadCore('db/DooModel');

class ScUsersSavedPlatformsBase extends DooModel{

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
    public $platform_data;

    /**
     * @var timestamp
     */
    public $date_added;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_users_saved_platforms';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','platform_data','date_added','status');

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

                'platform_data' => array(
                        array( 'notnull' ),
                ),

                'date_added' => array(
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