<?php
Doo::loadCore('db/DooModel');

class ScHlrLookupsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var timestamp
     */
    public $req_date;

    /**
     * @var int Max length is 11.
     */
    public $channel_id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var bigint Max length is 20.
     */
    public $msisdn;

    /**
     * @var int Max length is 11.
     */
    public $provider_id;

    /**
     * @var double
     */
    public $lookup_cost;

    /**
     * @var int Max length is 11.
     */
    public $hlr_status;

    /**
     * @var int Max length is 11.
     */
    public $mccmnc;

    /**
     * @var int Max length is 11.
     */
    public $connected_flag;

    /**
     * @var int Max length is 11.
     */
    public $roaming_flag;

    /**
     * @var int Max length is 11.
     */
    public $ported_flag;

    /**
     * @var varchar Max length is 20.
     */
    public $original_location;

    /**
     * @var varchar Max length is 20.
     */
    public $roaming_location;

    /**
     * @var text
     */
    public $response_data;

    /**
     * @var timestamp
     */
    public $last_updated;

    public $_table = 'sc_hlr_lookups';
    public $_primarykey = 'id';
    public $_fields = array('id','req_date','channel_id','user_id','msisdn','provider_id','lookup_cost','hlr_status','mccmnc','connected_flag','roaming_flag','ported_flag','original_location','roaming_location','response_data','last_updated');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'req_date' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'channel_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'user_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'msisdn' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'provider_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'lookup_cost' => array(
                        array( 'float' ),
                        array( 'notnull' ),
                ),

                'hlr_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'mccmnc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'connected_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'roaming_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'ported_flag' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'original_location' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'roaming_location' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'response_data' => array(
                        array( 'notnull' ),
                ),

                'last_updated' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}