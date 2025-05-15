<?php
Doo::loadCore('db/DooModel');

class ScFdlrTemplatesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 200.
     */
    public $title;

    /**
     * @var text
     */
    public $composition;

    /**
     * @var timestamp
     */
    public $last_changed;

    public $_table = 'sc_fdlr_templates';
    public $_primarykey = 'id';
    public $_fields = array('id','title','composition','last_changed');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'title' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'composition' => array(
                        array( 'notnull' ),
                ),

                'last_changed' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}