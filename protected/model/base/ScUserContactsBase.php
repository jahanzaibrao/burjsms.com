<?php
Doo::loadCore('db/DooModel');

class ScUserContactsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var int Max length is 11.
     */
    public $user_id;

    /**
     * @var bigint Max length is 20.
     */
    public $mobile;

    /**
     * @var varchar Max length is 100.
     */
    public $name;

    /**
     * @var varchar Max length is 500.
     */
    public $varC;

    /**
     * @var varchar Max length is 500.
     */
    public $varD;

    /**
     * @var varchar Max length is 500.
     */
    public $varE;

    /**
     * @var varchar Max length is 500.
     */
    public $varF;

    /**
     * @var varchar Max length is 500.
     */
    public $varG;

    /**
     * @var varchar Max length is 100.
     */
    public $network;

    /**
     * @var varchar Max length is 100.
     */
    public $circle;

    /**
     * @var int Max length is 11.
     */
    public $country;

    /**
     * @var int Max length is 11.
     */
    public $group_id;

    /**
     * @var int Max length is 11.
     */
    public $status;

    public $_table = 'sc_user_contacts';
    public $_primarykey = 'id';
    public $_fields = array('id','user_id','mobile','name','varC','varD','varE','varF','varG','network','circle','country','group_id','status');

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

                'mobile' => array(
                        array( 'integer' ),
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'varC' => array(
                        array( 'maxlength', 500 ),
                        array( 'optional' ),
                ),

                'varD' => array(
                        array( 'maxlength', 500 ),
                        array( 'optional' ),
                ),

                'varE' => array(
                        array( 'maxlength', 500 ),
                        array( 'optional' ),
                ),

                'varF' => array(
                        array( 'maxlength', 500 ),
                        array( 'optional' ),
                ),

                'varG' => array(
                        array( 'maxlength', 500 ),
                        array( 'optional' ),
                ),

                'network' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'circle' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'country' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'group_id' => array(
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