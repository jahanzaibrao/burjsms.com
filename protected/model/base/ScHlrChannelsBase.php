<?php
Doo::loadCore('db/DooModel');

class ScHlrChannelsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $provider_id;

    /**
     * @var varchar Max length is 200.
     */
    public $channel_name;

    /**
     * @var text
     */
    public $auth_data;

    public $_table = 'sc_hlr_channels';
    public $_primarykey = 'id';
    public $_fields = array('id','provider_id','channel_name','auth_data');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'provider_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'channel_name' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'auth_data' => array(
                        array( 'notnull' ),
                )
            );
    }

}