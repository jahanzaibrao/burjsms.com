<?php
Doo::loadCore('db/DooModel');

class ScKannelDlrBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 40.
     */
    public $smsc;

    /**
     * @var varchar Max length is 40.
     */
    public $ts;

    /**
     * @var varchar Max length is 40.
     */
    public $destination;

    /**
     * @var varchar Max length is 40.
     */
    public $source;

    /**
     * @var varchar Max length is 40.
     */
    public $service;

    /**
     * @var varchar Max length is 255.
     */
    public $url;

    /**
     * @var int Max length is 10.
     */
    public $mask;

    /**
     * @var int Max length is 10.
     */
    public $status;

    /**
     * @var varchar Max length is 40.
     */
    public $boxc;

    public $_table = 'sc_kannel_dlr';
    public $_primarykey = 'id';
    public $_fields = array('id','smsc','ts','destination','source','service','url','mask','status','boxc');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'smsc' => array(
                        array( 'maxlength', 40 ),
                        array( 'optional' ),
                ),

                'ts' => array(
                        array( 'maxlength', 40 ),
                        array( 'optional' ),
                ),

                'destination' => array(
                        array( 'maxlength', 40 ),
                        array( 'optional' ),
                ),

                'source' => array(
                        array( 'maxlength', 40 ),
                        array( 'optional' ),
                ),

                'service' => array(
                        array( 'maxlength', 40 ),
                        array( 'optional' ),
                ),

                'url' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'mask' => array(
                        array( 'integer' ),
                        array( 'maxlength', 10 ),
                        array( 'optional' ),
                ),

                'status' => array(
                        array( 'integer' ),
                        array( 'maxlength', 10 ),
                        array( 'optional' ),
                ),

                'boxc' => array(
                        array( 'maxlength', 40 ),
                        array( 'optional' ),
                )
            );
    }

}