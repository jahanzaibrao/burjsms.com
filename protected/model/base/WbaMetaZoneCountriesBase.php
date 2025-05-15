<?php
Doo::loadCore('db/DooModel');

class WbaMetaZoneCountriesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $zone_id;

    /**
     * @var varchar Max length is 100.
     */
    public $zone;

    /**
     * @var varchar Max length is 100.
     */
    public $country;

    /**
     * @var int Max length is 11.
     */
    public $prefix;

    public $_table = 'wba_meta_zone_countries';
    public $_primarykey = 'id';
    public $_fields = array('id','zone_id','zone','country','prefix');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'zone_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'zone' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'country' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'prefix' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                )
            );
    }

}