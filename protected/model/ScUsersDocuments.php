<?php
Doo::loadModel('base/ScUsersDocumentsBase');

class ScUsersDocuments extends ScUsersDocumentsBase{
    
    public function getDocsByDate($user, $type, $dates, $limit){
        if($dates!=NULL && trim(urldecode($dates))!='Select Date'){
            //split the dates
            $datr = explode("-",urldecode($dates));
            $from = date('Y-m-d',strtotime(trim($datr[0])));
            $to = date('Y-m-d',strtotime(trim($datr[1])));	

            $opt['where'] = "( FIND_IN_SET('$user',shared_with )>0 OR owner_id = $user ) AND created_on BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
         }else{
            $opt['where'] = "( owner_id = $user OR FIND_IN_SET('$user',shared_with )>0 )";
        }

        $this->type = $type;
        
        $opt['limit'] = $limit;
        $opt['desc'] = 'id';
        return Doo::db()->find($this, $opt);	
    }
    
    public function getAssociatedUsers($docid){
        $this->id = $docid;
        $opt['limit'] = 1;
        $opt['select'] = 'owner_id,shared_with';
        $res = Doo::db()->find($this,$opt);
        $users = explode(",",$res->shared_with);
        array_push($users, $res->owner_id);
        return $users;
    }
}