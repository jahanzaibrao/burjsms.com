<?php
Doo::loadCore('db/DooModel');

class ScShortUrlsMasterBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var varchar Max length is 15.
     */
    public $url_idf;

    /**
     * @var varchar Max length is 500.
     */
    public $redirect_url;

    /**
     * @var int Max length is 11.
     */
    public $type;

    /**
     * @var int Max length is 11.
     */
    public $media_link;

    public $_table = 'sc_short_urls_master';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','url_idf','redirect_url','type','media_link');

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

                'url_idf' => array(
                        array( 'maxlength', 15 ),
                        array( 'notnull' ),
                ),

                'redirect_url' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'media_link' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}