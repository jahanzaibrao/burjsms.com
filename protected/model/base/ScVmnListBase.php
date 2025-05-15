<?php
Doo::loadCore('db/DooModel');

class ScVmnListBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $type;

    /**
     * @var bigint Max length is 20.
     */
    public $vmn;

    /**
     * @var text
     */
    public $default_reply;

    /**
     * @var varchar Max length is 500.
     */
    public $trigger_url;

    /**
     * @var int Max length is 11.
     */
    public $user_assigned;

    /**
     * @var int Max length is 11.
     */
    public $auto_reply_type;

    /**
     * @var int Max length is 11.
     */
    public $sysreply_smpp;

    /**
     * @var varchar Max length is 100.
     */
    public $sysreply_sender;

    public $_table = 'sc_vmn_list';
    public $_primarykey = 'id';
    public $_fields = array('id','type','vmn','default_reply','trigger_url','user_assigned','auto_reply_type','sysreply_smpp','sysreply_sender');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'vmn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'default_reply' => array(
                        array( 'notnull' ),
                ),

                'trigger_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'user_assigned' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'auto_reply_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sysreply_smpp' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sysreply_sender' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                )
            );
    }

}