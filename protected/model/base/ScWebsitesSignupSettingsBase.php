<?php
Doo::loadCore('db/DooModel');

class ScWebsitesSignupSettingsBase extends DooModel{

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
     * @var mediumtext
     */
    public $notif_data;

    /**
     * @var mediumtext
     */
    public $signup_data;

    public $_table = 'sc_websites_signup_settings';
    public $_primarykey = 'id';
    public $_fields = array('id','site_id','user_id','notif_data','signup_data');

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

                'notif_data' => array(
                        array( 'notnull' ),
                ),

                'signup_data' => array(
                        array( 'notnull' ),
                )
            );
    }

}