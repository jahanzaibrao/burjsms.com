<?php
Doo::loadModel('base/ScSmppAccountsBase');

class ScSmppAccounts extends ScSmppAccountsBase{

    public function getSmppData($rid,$fields='all'){
        $opt = array();
        $this->id = $rid;
        if($fields!='all'){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);
    }

    public function getTotalSmppByKannel($kannelid)
    {
        $opt['select'] = "COUNT(id) as total";
        $opt['where'] = "kannel_id = $kannelid";
        $opt['limit'] = 1;
        return Doo::db()->find($this, $opt)->total;
    }

}
