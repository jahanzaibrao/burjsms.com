<?php
Doo::loadCore('db/DooModel');

class ScShortUrlsMsisdnMapBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $parent_url_id;

    /**
     * @var varchar Max length is 15.
     */
    public $url_idf;

    /**
     * @var varchar Max length is 500.
     */
    public $sms_shoot_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var timestamp
     */
    public $visited_on;

    public $_table = 'sc_short_urls_msisdn_map';
    public $_primarykey = 'id';
    public $_fields = array('id','parent_url_id','url_idf','sms_shoot_id','mobile','visited_on');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'parent_url_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'url_idf' => array(
                        array( 'maxlength', 15 ),
                        array( 'notnull' ),
                ),

                'sms_shoot_id' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'visited_on' => array(
                        array( 'datetime' ),
                        array( 'optional' ),
                )
            );
    }

}