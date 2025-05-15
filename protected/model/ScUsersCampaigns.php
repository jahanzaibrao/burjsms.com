<?php
Doo::loadModel('base/ScUsersCampaignsBase');

class ScUsersCampaigns extends ScUsersCampaignsBase
{
    public function createNewCampaigns($uid)
    {
        $qry = "INSERT INTO sc_users_campaigns(`user_id`,`campaign_name`,`campaign_desc`,`is_default`,`status`) VALUES";
        //create default campaign
        $qry .= "($uid, 'Default Campaign', 'By default all outgoing SMS are grouped into this campaigm', 1, 0),";
        //create campaign for sending system sms e.g. sign up alerts
        $qry .= "($uid, 'System SMS', 'Campaign for sending system alert messages', 0, 1)";
        Doo::db()->query($qry);
    }

    public function getCampaignId($uid, $mode)
    {
        $this->user_id = $uid;
        if ($mode == 'default') {
            $this->is_default = 1;
        }
        if ($mode == 'system') {
            $this->status = 1;
        }
        return Doo::db()->find($this, array('limit' => 1, 'select' => 'id'))->id;
    }
    public function getCampaignData($uid, $mode)
    {
        $this->user_id = $uid;
        if ($mode == 'default') {
            $this->is_default = 1;
        }
        if ($mode == 'system') {
            $this->status = 1;
        }
        return Doo::db()->find($this, array('limit' => 1));
    }
}
