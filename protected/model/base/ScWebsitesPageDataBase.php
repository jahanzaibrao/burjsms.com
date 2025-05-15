<?php
Doo::loadCore('db/DooModel');

class ScWebsitesPageDataBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $site_id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 10.
     */
    public $page_type;

    /**
     * @var longtext
     */
    public $page_data;

    /**
     * @var timestamp
     */
    public $last_mod;

    public $_table = 'sc_websites_page_data';
    public $_primarykey = 'id';
    public $_fields = array('id','site_id','user_id','page_type','page_data','last_mod');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'site_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'page_type' => array(
                        array( 'maxlength', 10 ),
                        array( 'notnull' ),
                ),

                'page_data' => array(
                        array( 'notnull' ),
                ),

                'last_mod' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}