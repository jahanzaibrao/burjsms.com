<?php
Doo::loadCore('db/DooModel');

class ScPhonebookContactsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var int Max length is 11.
     */
    public $group_id;

    public $_table = 'sc_phonebook_contacts';
    public $_primarykey = 'id';
    public $_fields = array('id','mobile','group_id');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'group_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}