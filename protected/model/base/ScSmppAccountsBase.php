<?php
Doo::loadCore('db/DooModel');

class ScSmppAccountsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $admin_id;

    /**
     * @var int Max length is 11.
     */
    public $kannel_id;

    /**
     * @var varchar Max length is 100.
     */
    public $title;

    /**
     * @var varchar Max length is 100.
     */
    public $provider;

    /**
     * @var varchar Max length is 100.
     */
    public $smsc_id;

    /**
     * @var varchar Max length is 200.
     */
    public $host;

    /**
     * @var int Max length is 11.
     */
    public $port;

    /**
     * @var varchar Max length is 100.
     */
    public $username;

    /**
     * @var varchar Max length is 100.
     */
    public $password;

    /**
     * @var int Max length is 11.
     */
    public $use_ssl;

    /**
     * @var int Max length is 11.
     */
    public $trx_mode;

    /**
     * @var int Max length is 11.
     */
    public $tx;

    /**
     * @var int Max length is 11.
     */
    public $rx;

    /**
     * @var int Max length is 11.
     */
    public $trx;

    /**
     * @var int Max length is 11.
     */
    public $rcv_port;

    /**
     * @var varchar Max length is 50.
     */
    public $system_type;

    /**
     * @var varchar Max length is 50.
     */
    public $service_type;

    /**
     * @var int Max length is 11.
     */
    public $throughput;

    /**
     * @var mediumtext
     */
    public $allowed_prefix;

    /**
     * @var mediumtext
     */
    public $denied_prefix;

    /**
     * @var int Max length is 11.
     */
    public $enquire_link_interval;

    /**
     * @var int Max length is 11.
     */
    public $reconnect_delay;

    /**
     * @var int Max length is 11.
     */
    public $esm_class;

    /**
     * @var varchar Max length is 100.
     */
    public $alt_charset;

    /**
     * @var int Max length is 11.
     */
    public $ston;

    /**
     * @var int Max length is 11.
     */
    public $dton;

    /**
     * @var int Max length is 11.
     */
    public $snpi;

    /**
     * @var int Max length is 11.
     */
    public $dnpi;

    /**
     * @var int Max length is 11.
     */
    public $max_octets;

    /**
     * @var varchar Max length is 200.
     */
    public $logfile;

    /**
     * @var varchar Max length is 2.
     */
    public $log_level;

    /**
     * @var varchar Max length is 100.
     */
    public $tlv_ids;

    /**
     * @var varchar Max length is 100.
     */
    public $purpose;

    /**
     * @var int Max length is 11.
     */
    public $smpp_version;

    /**
     * @var mediumtext
     */
    public $credits_api;

    /**
     * @var int Max length is 11.
     */
    public $pricing_preference;

    /**
     * @var int Max length is 11.
     */
    public $live_status;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_smpp_accounts';
    public $_primarykey = 'id';
    public $_fields = array('id','admin_id','kannel_id','title','provider','smsc_id','host','port','username','password','use_ssl','trx_mode','tx','rx','trx','rcv_port','system_type','service_type','throughput','allowed_prefix','denied_prefix','enquire_link_interval','reconnect_delay','esm_class','alt_charset','ston','dton','snpi','dnpi','max_octets','logfile','log_level','tlv_ids','purpose','smpp_version','credits_api','pricing_preference','live_status','status');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'admin_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'kannel_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'title' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'provider' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'smsc_id' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'host' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'username' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'password' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'use_ssl' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'trx_mode' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'tx' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'rx' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'trx' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'rcv_port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'system_type' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'service_type' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'throughput' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'allowed_prefix' => array(
                        array( 'notnull' ),
                ),

                'denied_prefix' => array(
                        array( 'notnull' ),
                ),

                'enquire_link_interval' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'reconnect_delay' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'esm_class' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'alt_charset' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'ston' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'dton' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'snpi' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'dnpi' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'max_octets' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'logfile' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'log_level' => array(
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'tlv_ids' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'purpose' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'smpp_version' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'credits_api' => array(
                        array( 'notnull' ),
                ),

                'pricing_preference' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'live_status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
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