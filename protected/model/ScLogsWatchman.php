<?php
Doo::loadModel('base/ScLogsWatchmanBase');

class ScLogsWatchman extends ScLogsWatchmanBase{
    
    public function addLog($text,$type){
        $this->timestamp = date(Doo::conf()->date_format_db);
        $this->activity = $text;
        $this->concern_flag  = intval($type);
        Doo::db()->insert($this);
    }
    
}