<?php
Doo::loadModel('base/ScSmppTlvBase');

class ScSmppTlv extends ScSmppTlvBase{
    public function getTlvData($id, $fields="all"){
        $this->id = $id;
        if($fields!='all'){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);

    }
}
