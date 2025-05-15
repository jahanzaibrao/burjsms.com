<?php
Doo::loadModel('base/ScUserNotificationsBase');

class ScUserNotifications extends ScUserNotificationsBase{
    
    public function addAlert($uid,$type,$text,$link=''){
        $this->user_id = $uid;
        $this->type = $type;
        $this->notif_text = $text;
        if($link!='') $this->link_to = $link;
        $this->notif_time = date(Doo::conf()->date_format_db);
        $this->status = 0;
        
        Doo::db()->insert($this);
    }
    
}