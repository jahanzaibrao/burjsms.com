<?php
Doo::loadCore('db/DooModel');

class WbaContactsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

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
    public $contact;

    /**
     * @var varchar Max length is 200.
     */
    public $name;

    /**
     * @var varchar Max length is 50.
     */
    public $wa_id;

    /**
     * @var text
     */
    public $metadata;

    public $_table = 'wba_contacts';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','phone_id','waba_id','contact','name','wa_id','metadata');

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

                'contact' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'wa_id' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'metadata' => array(
                        array( 'notnull' ),
                )
            );
    }

}