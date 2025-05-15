<?php
Doo::loadCore('db/DooModel');

class ScAnnouncementsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
     */
    public $msg;

    /**
     * @var int Max length is 11.
     */
    public $type;

    /**
     * @var int Max length is 11.
     */
    public $show_to;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var timestamp
     */
    public $last_updated;

    public $_table = 'sc_announcements';
    public $_primarykey = 'id';
    public $_fields = array('id','msg','type','show_to','status','last_updated');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'msg' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'show_to' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_updated' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}