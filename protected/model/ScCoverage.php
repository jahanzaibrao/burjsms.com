<?php
Doo::loadModel('base/ScCoverageBase');

class ScCoverage extends ScCoverageBase{
    public function getCoverageData($cid, $fields="all"){
        $this->id = $cid;
        $opt = array();
        if($fields!="all"){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);
    }

    public function getCoverageDataByIso($code, $fields="all")
    {
        $this->country_code = $code;
        $opt = array();
        if($fields!="all"){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);
    }
}