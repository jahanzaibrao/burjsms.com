<?php
Doo::loadModel('base/ScSmsRoutesBase');

class ScSmsRoutes extends ScSmsRoutesBase{
    
    public function getRouteData($rid,$fields="all"){
        $this->id = $rid;
        if($fields!='all'){
            $opt['select'] = $fields;
        }
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt);
        
    }

    public function getPlanRoutes($idlist){
        $opt['where'] = "id IN ($idlist)";
        return Doo::db()->find($this,$opt);
    }
    
}