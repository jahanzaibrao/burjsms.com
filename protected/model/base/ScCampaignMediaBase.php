<?php
Doo::loadCore('db/DooModel');

class ScCampaignMediaBase extends DooModel{

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
    public $media_title;

    /**
     * @var text
     */
    public $file_info;

    /**
     * @var varchar Max length is 100.
     */
    public $long_idf;

    /**
     * @var int Max length is 11.
     */
    public $tinyurl_id;

    /**
     * @var timestamp
     */
    public $last_update;

    public $_table = 'sc_campaign_media';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','media_title','file_info','long_idf','tinyurl_id','last_update');

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

                'media_title' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'file_info' => array(
                        array( 'notnull' ),
                ),

                'long_idf' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'tinyurl_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'last_update' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}