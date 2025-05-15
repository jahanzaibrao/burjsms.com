<?php
Doo::loadCore('db/DooModel');

class ScSmsTemplatesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 50.
     */
    public $title;

    /**
     * @var text
     */
    public $content;

    /**
     * @var timestamp
     */
    public $created_on;

    /**
     * @var timestamp
     */
    public $last_modified;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var varchar Max length is 50.
     */
    public $file_ids;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_sms_templates';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','title','content','created_on','last_modified','route_id','file_ids','status');

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

                'title' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'content' => array(
                        array( 'notnull' ),
                ),

                'created_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_modified' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'file_ids' => array(
                        array( 'maxlength', 50 ),
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