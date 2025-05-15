<?php
Doo::loadCore('db/DooModel');

class ScSupportTicketCommentsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $ticket_id;

    /**
     * @var longtext
     */
    public $ticket_text;

    /**
     * @var varchar Max length is 500.
     */
    public $files_included;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var timestamp
     */
    public $date_added;

    public $_table = 'sc_support_ticket_comments';
    public $_primarykey = 'id';
    public $_fields = array('id','ticket_id','ticket_text','files_included','user_id','date_added');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'ticket_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'ticket_text' => array(
                        array( 'notnull' ),
                ),

                'files_included' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'date_added' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}