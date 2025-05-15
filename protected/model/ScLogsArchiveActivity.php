<?php
Doo::loadModel('base/ScLogsArchiveActivityBase');

class ScLogsArchiveActivity extends ScLogsArchiveActivityBase{
    
    public function addLog($taskid, $activity, $flag=0){
        $this->timestamp = date(Doo::conf()->date_format_db);
        $this->task_id = $taskid;
        $this->activity = $activity;
        $this->concern_flag = $flag;
        
        Doo::db()->insert($this,3);
    }
}