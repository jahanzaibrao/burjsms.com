<?php
Doo::loadCore('db/DooModel');

class ScBlockedIpListBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 20.
     */
    public $ip_address;

    /**
     * @var int Max length is 11.
     */
    public $user_assoc;

    /**
     * @var longtext
     */
    public $platform_data;

    /**
     * @var timestamp
     */
    public $date_added;

    /**
     * @var longtext
     */
    public $remarks;

    public $_table = 'sc_blocked_ip_list';
    public $_primarykey = 'id';
    public $_fields = array('id','ip_address','user_assoc','platform_data','date_added','remarks');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'ip_address' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'user_assoc' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'platform_data' => array(
                        array( 'notnull' ),
                ),

                'date_added' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'remarks' => array(
                        array( 'notnull' ),
                )
            );
    }

}