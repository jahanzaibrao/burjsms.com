<?php
Doo::loadCore('db/DooModel');

class ScUsersHlrSettingsBase extends DooModel{

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
    public $channel_id;

    /**
     * @var double
     */
    public $credits_cost;

    public $_table = 'sc_users_hlr_settings';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','channel_id','credits_cost');

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

                'channel_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'credits_cost' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                )
            );
    }

}