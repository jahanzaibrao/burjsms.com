<?php
Doo::loadModel('base/ScUsersSmsPlansBase');

class ScUsersSmsPlans extends ScUsersSmsPlansBase{
    
    public function getPlanUserCount($planid, $type=0){
       $opt['select'] = 'COUNT(id) as total';
       $opt['where'] = 'plan_id = '.intval($planid).' AND plan_type = '.$type;
       $opt['limit'] = 1;
       return Doo::db()->find($this, $opt)->total;
    }
    
}