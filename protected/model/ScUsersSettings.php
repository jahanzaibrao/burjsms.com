<?php
Doo::loadModel('base/ScUsersSettingsBase');

class ScUsersSettings extends ScUsersSettingsBase{
    
    public function getSettingValue($uid, $set){
        $this->user_id = $uid;
        return Doo::db()->find($this,array('limit'=>1,'select'=>$set));
    } 
}