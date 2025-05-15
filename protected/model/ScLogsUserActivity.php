<?php
Doo::loadModel('base/ScLogsUserActivityBase');

class ScLogsUserActivity extends ScLogsUserActivityBase{
    
    public function addLog($uid, $actData, $flag=0){
        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        $osdata['system'] = $browser['platform'];
        $osdata['browser'] = $browser['browser'].' v'.$browser['version'];
        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
        $osdata['city'] = $browser['city'];
        $osdata['country'] = $browser['country'];
                    
        $this->action_type = $actData['activity_type'];
        $this->user_id = $uid;
        $this->page_url = base64_encode( (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $this->activity = $actData['activity'];
        $this->flag = $flag;
        $this->visitor_ip = $_SERVER['REMOTE_ADDR'];
        $this->platform_data = serialize($osdata);
        Doo::db()->insert($this);
    }
}