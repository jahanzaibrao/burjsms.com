<?php
Doo::loadModel('base/ScTempCampaignsBase');

class ScTempCampaigns extends ScTempCampaignsBase{
    
    public function getQueuedCampaigns(){  
        $opt['select'] = 'sc_temp_campaigns.id, sc_temp_campaigns.user_id, sc_temp_campaigns.route_id, sc_temp_campaigns.sender_id, sc_temp_campaigns.count, sc_temp_campaigns.sms_type, sc_temp_campaigns.sms_text, sc_temp_campaigns.submission_time, sc_temp_campaigns.status, sc_users.name as name,sc_users.category as category, sc_users.user_id as uid, sc_users.avatar as avatar, sc_users.email as email';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';
		$opt['desc'] = 'sc_temp_campaigns.id';
		return Doo::db()->find($this, $opt);
    }
    
}