<?php
Doo::loadCore('db/DooModel');

class ScEmailQueueBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var timestamp
     */
    public $added_on;

    /**
     * @var varchar Max length is 500.
     */
    public $sender_email;

    /**
     * @var varchar Max length is 200.
     */
    public $sender_name;

    /**
     * @var longtext
     */
    public $recipient_list;

    /**
     * @var varchar Max length is 500.
     */
    public $email_sub;

    /**
     * @var longtext
     */
    public $email_text;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_email_queue';
    public $_primarykey = 'id';
    public $_fields = array('id','added_on','sender_email','sender_name','recipient_list','email_sub','email_text','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'added_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'sender_email' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'sender_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'recipient_list' => array(
                        array( 'notnull' ),
                ),

                'email_sub' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'email_text' => array(
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