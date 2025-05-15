<?php
Doo::loadCore('db/DooModel');

class ScUsersCampaignsOptoutsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var int Max length is 11.
     */
    public $campaign_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var varchar Max length is 100.
     */
    public $keyword_matched;

    /**
     * @var timestamp
     */
    public $date_added;

    public $_table = 'sc_users_campaigns_optouts';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','campaign_id','mobile','keyword_matched','date_added');

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

                'campaign_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'keyword_matched' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'date_added' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}