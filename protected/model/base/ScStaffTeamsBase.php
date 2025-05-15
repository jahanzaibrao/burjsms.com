<?php
Doo::loadCore('db/DooModel');

class ScStaffTeamsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $name;

    /**
     * @var varchar Max length is 500.
     */
    public $description;

    /**
     * @var varchar Max length is 10.
     */
    public $theme;

    /**
     * @var longtext
     */
    public $rights;

    public $_table = 'sc_staff_teams';
    public $_primarykey = 'id';
    public $_fields = array('id','name','description','theme','rights');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'description' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'theme' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'rights' => array(
                        array( 'notnull' ),
                )
            );
    }

}