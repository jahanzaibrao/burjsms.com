<?php
Doo::loadCore('db/DooModel');

class WbaCampaignsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $shoot_id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 50.
     */
    public $phone_id;

    /**
     * @var varchar Max length is 200.
     */
    public $template_id;

    /**
     * @var int Max length is 11.
     */
    public $total_contacts;

    /**
     * @var timestamp
     */
    public $sent_on;

    /**
     * @var text
     */
    public $meta_resp;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'wba_campaigns';
    public $_primarykey = 'id';
    public $_fields = array('id','shoot_id','user_id','phone_id','template_id','total_contacts','sent_on','meta_resp','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'shoot_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'phone_id' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'template_id' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'total_contacts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'sent_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'meta_resp' => array(
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