<?php
Doo::loadModel('base/ScWebsitesBase');

class ScWebsites extends ScWebsitesBase{
    
    public function checkDomains($domains, $uid){
        $val = 'none';
        //split the domains
        $domar = explode(',',$domains);
        //check each domain
        foreach($domar as $dom){
            $opt['where'] = "user_id <> $uid AND FIND_IN_SET('$dom',domains )>0";
            $opt['select'] = 'id';
            $opt['limit'] = 1;
            $res = Doo::db()->find($this,$opt);
            if($res->id){
                $val = $dom;
                break;
            }
        }
        
        return $val;
    }
    
    
    public function getWebsiteData($param, $type){
        if($type=='domain'){
            $opt['limit'] = 1;
            $opt['where'] = "FIND_IN_SET('$param',`domains` )>0 AND status=1"; //website status must be active
            $res = Doo::db()->find($this,$opt);
            if($res->id){
                return $res;
            }else{
                return false;
            }
        }
        
        if($type=='owner'){
            $opt['limit'] = 1;
            $opt['where'] = "user_id=$param AND status=1"; //website status must be active
            $res = Doo::db()->find($this,$opt);
            if($res->id){
                return $res;
            }else{
                return false;
            }
        }
    }
    
}