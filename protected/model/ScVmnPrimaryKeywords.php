<?php
Doo::loadModel('base/ScVmnPrimaryKeywordsBase');

class ScVmnPrimaryKeywords extends ScVmnPrimaryKeywordsBase{
    public function getKeywordData($id,$fields="all"){
        $this->id = $id;
        if($fields!='all'){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);
        
    }
    public function revokeAllKeywords($uid){
        $qry = 'UPDATE sc_vmn_primary_keywords SET user_assigned=0 WHERE user_assigned='.$uid;
        Doo::db()->query($qry);
    }
}