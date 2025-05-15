<?php
Doo::loadCore('db/DooModel');

class ScUsersPhonebookSettingsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 500.
     */
    public $phonebook_ids;

    /**
     * @var int Max length is 11.
     */
    public $click_track;

    /**
     * @var varchar Max length is 200.
     */
    public $mask_pattern;

    public $_table = 'sc_users_phonebook_settings';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','phonebook_ids','click_track','mask_pattern');

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

                'phonebook_ids' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'click_track' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mask_pattern' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                )
            );
    }

}