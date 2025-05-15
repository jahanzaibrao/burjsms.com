<?php
Doo::loadCore('db/DooModel');

class ScUserContactGroupsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 100.
     */
    public $group_name;

    /**
     * @var mediumtext
     */
    public $column_labels;

    /**
     * @var timestamp
     */
    public $last_mod;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_user_contact_groups';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','group_name','column_labels','last_mod','status');

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

                'group_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'column_labels' => array(
                        array( 'notnull' ),
                ),

                'last_mod' => array(
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