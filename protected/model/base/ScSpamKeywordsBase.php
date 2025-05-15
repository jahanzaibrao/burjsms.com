<?php
Doo::loadCore('db/DooModel');

class ScSpamKeywordsBase extends DooModel{

    /**
     * @var int Max length is 11.
     */
    public $id;

    /**
     * @var varchar Max length is 100.
     */
    public $phrase;

    public $_table = 'sc_spam_keywords';
    public $_primarykey = 'id';
    public $_fields = array('id','phrase');

    public function getVRules() {
        return array(
                'id' => array(
                        array( 'integer' ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'phrase' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                )
            );
    }

}