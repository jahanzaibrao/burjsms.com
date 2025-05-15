<?php
Doo::loadCore('db/DooModel');

class ScUsersDocumentRemarksBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $file_id;

    /**
     * @var varchar Max length is 500.
     */
    public $remark_text;

    /**
     * @var timestamp
     */
    public $posted_on;

    public $_table = 'sc_users_document_remarks';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','file_id','remark_text','posted_on');

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

                'file_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'remark_text' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'posted_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}