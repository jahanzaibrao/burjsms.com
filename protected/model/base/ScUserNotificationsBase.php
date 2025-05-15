<?php
Doo::loadCore('db/DooModel');

class ScUserNotificationsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 10.
     */
    public $type;

    /**
     * @var mediumtext
     */
    public $notif_text;

    /**
     * @var varchar Max length is 50.
     */
    public $link_to;

    /**
     * @var timestamp
     */
    public $notif_time;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_user_notifications';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','type','notif_text','link_to','notif_time','status');

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

                'type' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'notif_text' => array(
                        array( 'notnull' ),
                ),

                'link_to' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'notif_time' => array(
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