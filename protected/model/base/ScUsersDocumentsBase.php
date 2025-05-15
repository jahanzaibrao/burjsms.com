<?php
Doo::loadCore('db/DooModel');

class ScUsersDocumentsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
     */
    public $filename;

    /**
     * @var int Max length is 11.
     */
    public $type;

    /**
     * @var varchar Max length is 500.
     */
    public $location;

    /**
     * @var int Max length is 11.
     */
    public $owner_id;

    /**
     * @var varchar Max length is 100.
     */
    public $shared_with;

    /**
     * @var timestamp
     */
    public $created_on;

    /**
     * @var timestamp
     */
    public $last_mod;

    /**
     * @var longtext
     */
    public $file_data;

    /**
     * @var int Max length is 11.
     */
    public $file_status;

    /**
     * @var varchar Max length is 500.
     */
    public $init_remarks;

    public $_table = 'sc_users_documents';
    public $_primarykey = 'id';
    public $_fields = array('id','filename','type','location','owner_id','shared_with','created_on','last_mod','file_data','file_status','init_remarks');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'filename' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'location' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'owner_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'shared_with' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'created_on' => array(
                        array( 'datetime' ),
                        array( 'optional' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'file_data' => array(
                        array( 'notnull' ),
                ),

                'file_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'init_remarks' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                )
            );
    }

}