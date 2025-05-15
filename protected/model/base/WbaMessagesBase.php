<?php
Doo::loadCore('db/DooModel');

class WbaMessagesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $meta_msg_id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var bigint Max length is 20.
     */
    public $phone_id;

    /**
     * @var bigint Max length is 20.
     */
    public $waba_id;

    /**
     * @var varchar Max length is 20.
     */
    public $wa_id;

    /**
     * @var int Max length is 11.
     */
    public $contact_id;

    /**
     * @var text
     */
    public $message;

    /**
     * @var int Max length is 11.
     */
    public $direction;

    /**
     * @var varchar Max length is 100.
     */
    public $conversation_id;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var timestamp
     */
    public $sent_time;

    /**
     * @var timestamp
     */
    public $last_update;

    public $_table = 'wba_messages';
    public $_primarykey = 'id';
    public $_fields = array('id','meta_msg_id','user_id','phone_id','waba_id','wa_id','contact_id','message','direction','conversation_id','status','sent_time','last_update');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'meta_msg_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'phone_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'waba_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'wa_id' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'contact_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'message' => array(
                        array( 'notnull' ),
                ),

                'direction' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'conversation_id' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sent_time' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_update' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}