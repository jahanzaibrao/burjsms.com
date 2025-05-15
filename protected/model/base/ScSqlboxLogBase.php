<?php
Doo::loadCore('db/DooModel');

class ScSqlboxLogBase extends DooModel{

    /**
     * @var bigint Max length is 20.
     */
    public $sql_id;

    /**
     * @var int Max length is 11.
     */
    public $foreign_id;

    /**
     * @var enum 'MO','MT').
     */
    public $momt;

    /**
     * @var varchar Max length is 20.
     */
    public $sender;

    /**
     * @var varchar Max length is 20.
     */
    public $receiver;

    /**
     * @var text
     */
    public $msgdata;

    /**
     * @var blob
     */
    public $udhdata;

    /**
     * @var bigint Max length is 20.
     */
    public $time;

    /**
     * @var varchar Max length is 255.
     */
    public $smsc_id;

    /**
     * @var varchar Max length is 255.
     */
    public $service;

    /**
     * @var varchar Max length is 255.
     */
    public $account;

    /**
     * @var varchar Max length is 200.
     */
    public $id;

    /**
     * @var bigint Max length is 20.
     */
    public $sms_type;

    /**
     * @var bigint Max length is 20.
     */
    public $mclass;

    /**
     * @var bigint Max length is 20.
     */
    public $mwi;

    /**
     * @var bigint Max length is 20.
     */
    public $coding;

    /**
     * @var bigint Max length is 20.
     */
    public $compress;

    /**
     * @var bigint Max length is 20.
     */
    public $validity;

    /**
     * @var bigint Max length is 20.
     */
    public $deferred;

    /**
     * @var bigint Max length is 20.
     */
    public $dlr_mask;

    /**
     * @var mediumtext
     */
    public $dlr_url;

    /**
     * @var bigint Max length is 20.
     */
    public $pid;

    /**
     * @var bigint Max length is 20.
     */
    public $alt_dcs;

    /**
     * @var bigint Max length is 20.
     */
    public $rpi;

    /**
     * @var varchar Max length is 255.
     */
    public $charset;

    /**
     * @var varchar Max length is 255.
     */
    public $boxc_id;

    /**
     * @var varchar Max length is 255.
     */
    public $binfo;

    /**
     * @var mediumtext
     */
    public $meta_data;

    /**
     * @var int Max length is 11.
     */
    public $priority;

    /**
     * @var timestamp
     */
    public $logtime;

    /**
     * @var bigint Max length is 20.
     */
    public $id_gateway;

    /**
     * @var bigint Max length is 20.
     */
    public $id_user;

    /**
     * @var int Max length is 5.
     */
    public $is_dnd;

    /**
     * @var datetime
     */
    public $sendondate;

    /**
     * @var int Max length is 11.
     */
    public $smpp_dlr;

    /**
     * @var varchar Max length is 200.
     */
    public $smpp_vendor_dlr;

    /**
     * @var varchar Max length is 500.
     */
    public $vendor_reply;

    public $_table = 'sc_sqlbox_log';
    public $_primarykey = 'sql_id';
    public $_fields = array('sql_id','foreign_id','momt','sender','receiver','msgdata','udhdata','time','smsc_id','service','account','id','sms_type','mclass','mwi','coding','compress','validity','deferred','dlr_mask','dlr_url','pid','alt_dcs','rpi','charset','boxc_id','binfo','meta_data','priority','logtime','id_gateway','id_user','is_dnd','sendondate','smpp_dlr','smpp_vendor_dlr','vendor_reply');

    public function getVRules() {
        return array(
                'sql_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'foreign_id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'momt' => array(
                        array( 'optional' ),
                ),

                'sender' => array(
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'receiver' => array(
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'msgdata' => array(
                        array( 'optional' ),
                ),

                'udhdata' => array(
                        array( 'optional' ),
                ),

                'time' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'smsc_id' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'service' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'account' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'id' => array(
                        array( 'maxlength', 200 ),
                        array( 'optional' ),
                ),

                'sms_type' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'mclass' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'mwi' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'coding' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'compress' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'validity' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'deferred' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'dlr_mask' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'dlr_url' => array(
                        array( 'optional' ),
                ),

                'pid' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'alt_dcs' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'rpi' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'charset' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'boxc_id' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'binfo' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'meta_data' => array(
                        array( 'optional' ),
                ),

                'priority' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'logtime' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'id_gateway' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'id_user' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'is_dnd' => array(
                        array( 'integer' ),
                        array( 'maxlength', 5 ),
                        array( 'optional' ),
                ),

                'sendondate' => array(
                        array( 'datetime' ),
                        array( 'optional' ),
                ),

                'smpp_dlr' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'smpp_vendor_dlr' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'vendor_reply' => array(
                        array( 'maxlength', 500 ),
                        array( 'notnull' ),
                )
            );
    }

}