<?php
Doo::loadCore('db/DooModel');

class ScWebsitesSslCertsBase extends DooModel{

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
    public $domain_name;

    /**
     * @var timestamp
     */
    public $install_date;

    public $_table = 'sc_websites_ssl_certs';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','domain_name','install_date');

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

                'domain_name' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                ),

                'install_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}