<?php
Doo::loadModel('base/ScQueuedCampaignsBase');

class ScQueuedCampaigns extends ScQueuedCampaignsBase{

    public function addQueueItem($queue_data){
		$query = "INSERT INTO `sc_queued_campaigns` (`sms_shoot_id`,`user_id`,`route_id`,`sender_id`,`contacts`,`pushed_via`, `count`,`smscount`,`sms_type`,`sms_text`,`submission_time`,`status`) VALUES ";
		foreach ($queue_data as $data){

		$query .= "(";
		$query .= "'".$data['sms_shoot_id']."',";
		$query .= $data['user_id'].",";
		$query .= $data['route_id'].",";
		$query .= $data['sender_id'].",";
		$query .= "'".$data['contacts']."',";
		$query .= "'".$data['pushed_via']."',";
		$query .= $data['count'].",";
		$query .= $data['smscount'].",";
		$query .= "'".$data['sms_type']."',";
		$query .= "'".$data['sms_text']."',";
		$query .= "'".$data['submission_time']."',";
		$query .= $data['status']."),";
		}
		$query = substr($query, 0, strlen($query)-1);
		$rs = Doo::db()->query($query);
	}

    public function getQueuedCampaigns(){
        $opt['select'] = 'sc_queued_campaigns.id, sc_queued_campaigns.user_id, sc_queued_campaigns.route_id, sc_queued_campaigns.sender_id, sc_queued_campaigns.count, sc_queued_campaigns.sms_type, sc_queued_campaigns.sms_text, sc_queued_campaigns.submission_time, sc_queued_campaigns.status, sc_users.name as name,sc_users.category as category, sc_users.user_id as uid, sc_users.avatar as avatar, sc_users.email as email';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';
		$opt['desc'] = 'sc_queued_campaigns.id';
		return Doo::db()->find($this, $opt);
    }

    public function getTotalQueuedByRoute($rid){
		$this->route_id = $rid;
		$opt['select'] = "SUM(`count`) as total, COUNT(`id`) as batches";
		$opt['limit'] = 1;
		return Doo::db()->find($this,$opt);
    }

}
