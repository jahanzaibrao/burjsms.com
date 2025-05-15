<?php
Doo::loadModel('base/ScMccMncListBase');

class ScMccMncList extends ScMccMncListBase{

    public function getDetailsByMccmnc($mccmnc){
        $this->mccmnc = $mccmnc;
        return Doo::db()->find($this, array('limit'=>1));
    }
}