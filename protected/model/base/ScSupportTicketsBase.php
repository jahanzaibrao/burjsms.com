<?php
Doo::loadCore('db/DooModel');

class ScSupportTicketsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
     */
    public $ticket_title;

    /**
     * @var int Max length is 11.
     */
    public $priority;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $manager_id;

    /**
     * @var timestamp
     */
    public $date_opened;

    /**
     * @var timestamp
     */
    public $date_closed;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_support_tickets';
    public $_primarykey = 'id';
    public $_fields = array('id','ticket_title','priority','user_id','manager_id','date_opened','date_closed','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'ticket_title' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'priority' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'manager_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'date_opened' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'date_closed' => array(
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