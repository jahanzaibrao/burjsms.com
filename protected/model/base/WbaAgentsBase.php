<?php
Doo::loadCore('db/DooModel');

class WbaAgentsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 100.
     */
    public $waba_id;

    /**
     * @var varchar Max length is 200.
     */
    public $waba_name;

    /**
     * @var int Max length is 11.
     */
    public $meta_tz_id;

    /**
     * @var varchar Max length is 100.
     */
    public $message_template_namespace;

    /**
     * @var int Max length is 11.
     */
    public $is_owned;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var timestamp
     */
    public $last_updated;

    public $_table = 'wba_agents';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','waba_id','waba_name','meta_tz_id','message_template_namespace','is_owned','status','last_updated');

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

                'waba_id' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'waba_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'meta_tz_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'message_template_namespace' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'is_owned' => array(
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