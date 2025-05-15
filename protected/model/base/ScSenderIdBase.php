<?php
Doo::loadCore('db/DooModel');

class ScSenderIdBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 15.
     */
    public $sender_id;

    /**
     * @var int Max length is 11.
     */
    public $req_by;

    /**
     * @var timestamp
     */
    public $req_on;

    /**
     * @var varchar Max length is 50.
     */
    public $file_ids;

    /**
     * @var varchar Max length is 500.
     */
    public $countries_matrix;

    /**
     * @var int Max length is 11.
     */
    public $wildcard_flag;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_sender_id';
    public $_primarykey = 'id';
    public $_fields = array('id','sender_id','req_by','req_on','file_ids','countries_matrix','wildcard_flag','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'sender_id' => array(
                        array( 'maxlength', 15 ),
                        array( 'notnull' ),
                ),

                'req_by' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'req_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'file_ids' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'countries_matrix' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'wildcard_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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