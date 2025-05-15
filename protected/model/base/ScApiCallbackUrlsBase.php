<?php
Doo::loadCore('db/DooModel');

class ScApiCallbackUrlsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 500.
     */
    public $callback_url;

    /**
     * @var timestamp
     */
    public $last_update;

    public $_table = 'sc_api_callback_urls';
    public $_primarykey = 'id';
    public $_fields = array('id','callback_url','last_update');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'callback_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'last_update' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}