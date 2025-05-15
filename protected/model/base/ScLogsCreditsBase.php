<?php
Doo::loadCore('db/DooModel');

class ScLogsCreditsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var timestamp
     */
    public $timestamp;

    /**
     * @var double
     */
    public $amount;

    /**
     * @var int Max length is 11.
     */
    public $route_id;

    /**
     * @var double
     */
    public $credits_before;

    /**
     * @var double
     */
    public $credits_after;

    /**
     * @var varchar Max length is 500.
     */
    public $reference;

    /**
     * @var text
     */
    public $comments;

    public $_table = 'sc_logs_credits';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','timestamp','amount','route_id','credits_before','credits_after','reference','comments');

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

                'timestamp' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'amount' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'route_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'credits_before' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'credits_after' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'reference' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'comments' => array(
                        array( 'notnull' ),
                )
            );
    }

}