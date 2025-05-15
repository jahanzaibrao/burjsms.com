<?php
Doo::loadCore('db/DooModel');

class ScUsersOtpChannelsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var varchar Max length is 100.
     */
    public $sender;

    /**
     * @var text
     */
    public $template;

    public $_table = 'sc_users_otp_channels';
    public $_primarykey = 'id';
    public $_fields = array('id','title','user_id','route_id','sender','template');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'title' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sender' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'template' => array(
                        array( 'notnull' ),
                )
            );
    }

}