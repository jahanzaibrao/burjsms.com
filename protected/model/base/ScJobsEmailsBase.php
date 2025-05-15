<?php
Doo::loadCore('db/DooModel');

class ScJobsEmailsBase extends DooModel{

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
    public $category;

    /**
     * @var varchar Max length is 255.
     */
    public $from_addr;

    /**
     * @var varchar Max length is 255.
     */
    public $to_addr;

    /**
     * @var varchar Max length is 255.
     */
    public $subject_line;

    /**
     * @var text
     */
    public $email_data;

    /**
     * @var text
     */
    public $response;

    /**
     * @var int Max length is 11.
     */
    public $attempts;

    /**
     * @var timestamp
     */
    public $added_on;

    /**
     * @var timestamp
     */
    public $last_updated;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_jobs_emails';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','category','from_addr','to_addr','subject_line','email_data','response','attempts','added_on','last_updated','status');

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

                'category' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'from_addr' => array(
                        array( 'maxlength', 255 ),
                        array( 'notnull' ),
                ),

                'to_addr' => array(
                        array( 'maxlength', 255 ),
                        array( 'notnull' ),
                ),

                'subject_line' => array(
                        array( 'maxlength', 255 ),
                        array( 'notnull' ),
                ),

                'email_data' => array(
                        array( 'notnull' ),
                ),

                'response' => array(
                        array( 'notnull' ),
                ),

                'attempts' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'added_on' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'last_updated' => array(
                        array( 'datetime' ),
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