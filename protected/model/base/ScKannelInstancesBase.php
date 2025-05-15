<?php
Doo::loadCore('db/DooModel');

class ScKannelInstancesBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $kannel_name;

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
    public $admin_pass;

    /**
     * @var varchar Max length is 100.
     */
    public $status_pass;

    /**
     * @var varchar Max length is 100.
     */
    public $sendsms_user;

    /**
     * @var varchar Max length is 100.
     */
    public $sendsms_pass;

    /**
     * @var int Max length is 11.
     */
    public $smsbox_port;

    /**
     * @var varchar Max length is 100.
     */
    public $log_dir;

    /**
     * @var varchar Max length is 200.
     */
    public $kdb_host;

    /**
     * @var int Max length is 11.
     */
    public $kdb_port;

    /**
     * @var varchar Max length is 100.
     */
    public $kdb_user;

    /**
     * @var varchar Max length is 100.
     */
    public $kdb_pass;

    /**
     * @var varchar Max length is 100.
     */
    public $kdb_name;

    public $_table = 'sc_kannel_instances';
    public $_primarykey = 'id';
    public $_fields = array('id','kannel_name','host','port','admin_pass','status_pass','sendsms_user','sendsms_pass','smsbox_port','log_dir','kdb_host','kdb_port','kdb_user','kdb_pass','kdb_name');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'kannel_name' => array(
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

                'admin_pass' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'status_pass' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'sendsms_user' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'sendsms_pass' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'smsbox_port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'log_dir' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'kdb_host' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                ),

                'kdb_port' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'kdb_user' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'kdb_pass' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'kdb_name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                )
            );
    }

}