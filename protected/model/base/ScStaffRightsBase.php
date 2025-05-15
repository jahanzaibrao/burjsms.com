<?php
Doo::loadCore('db/DooModel');

class ScStaffRightsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $staff_uid;

    /**
     * @var int Max length is 11.
     */
    public $team_id;

    /**
     * @var longtext
     */
    public $rights;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_staff_rights';
    public $_primarykey = 'id';
    public $_fields = array('id','staff_uid','team_id','rights','last_mod');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'staff_uid' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'team_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'rights' => array(
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}