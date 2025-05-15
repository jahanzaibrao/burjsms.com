<?php
Doo::loadModel('base/ScUsersCompanyBase');

class ScUsersCompany extends ScUsersCompanyBase{
    
    public function getDataByUser($uid){
        $this->user_id = $uid;
        return Doo::db()->find($this, array('limit'=>1));
    }
}