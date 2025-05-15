<?php
Doo::loadModel('base/ScVmnListBase');

class ScVmnList extends ScVmnListBase{
    public function getVmnData($id,$fields="all"){
        $this->id = $id;
        if($fields!='all'){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);
        
    }

    public function revokeAllVmn($uid){
        $qry = 'UPDATE sc_vmn_list SET user_assigned=0 WHERE user_assigned='.$uid;
        Doo::db()->query($qry);
    }
}