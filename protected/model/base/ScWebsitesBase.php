<?php
Doo::loadCore('db/DooModel');

class ScWebsitesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var mediumtext
     */
    public $domains;

    /**
     * @var varchar Max length is 500.
     */
    public $logo;

    /**
     * @var longtext
     */
    public $site_data;

    /**
     * @var int Max length is 11.
     */
    public $front_type;

    /**
     * @var mediumtext
     */
    public $skin_data;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_websites';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','domains','logo','site_data','front_type','skin_data','status');

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

                'domains' => array(
                        array( 'notnull' ),
                ),

                'logo' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'site_data' => array(
                        array( 'notnull' ),
                ),

                'front_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'skin_data' => array(
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