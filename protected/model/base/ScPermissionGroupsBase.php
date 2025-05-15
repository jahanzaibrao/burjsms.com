<?php
Doo::loadCore('db/DooModel');

class ScPermissionGroupsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var varchar Max length is 500.
     */
    public $description;

    /**
     * @var varchar Max length is 20.
     */
    public $color_scheme;

    /**
     * @var text
     */
    public $permissions;

    /**
     * @var timestamp
     */
    public $last_updated;

    public $_table = 'sc_permission_groups';
    public $_primarykey = 'id';
    public $_fields = array('id','title','description','color_scheme','permissions','last_updated');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'title' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'description' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'color_scheme' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'permissions' => array(
                        array( 'notnull' ),
                ),

                'last_updated' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}