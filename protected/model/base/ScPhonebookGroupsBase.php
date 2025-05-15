<?php
Doo::loadCore('db/DooModel');

class ScPhonebookGroupsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $group_name;

    /**
     * @var int Max length is 11.
     */
    public $contact_count;

    /**
     * @var int Max length is 11.
     */
    public $status;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_phonebook_groups';
    public $_primarykey = 'id';
    public $_fields = array('id','group_name','contact_count','status','last_mod');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'group_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'contact_count' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}