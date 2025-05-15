<?php
Doo::loadCore('db/DooModel');

class ScVmnPrimaryKeywordsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $keyword;

    /**
     * @var int Max length is 11.
     */
    public $vmn;

    /**
     * @var text
     */
    public $default_reply;

    /**
     * @var bigint Max length is 20.
     */
    public $forward_sms_to;

    /**
     * @var varchar Max length is 500.
     */
    public $trigger_url;

    /**
     * @var int Max length is 11.
     */
    public $added_by;

    /**
     * @var int Max length is 11.
     */
    public $user_assigned;

    /**
     * @var timestamp
     */
    public $added_on;

    /**
     * @var timestamp
     */
    public $last_updated;

    public $_table = 'sc_vmn_primary_keywords';
    public $_primarykey = 'id';
    public $_fields = array('id','keyword','vmn','default_reply','forward_sms_to','trigger_url','added_by','user_assigned','added_on','last_updated');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'keyword' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'vmn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'default_reply' => array(
                        array( 'notnull' ),
                ),

                'forward_sms_to' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'trigger_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'added_by' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'user_assigned' => array(
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
                )
            );
    }

}