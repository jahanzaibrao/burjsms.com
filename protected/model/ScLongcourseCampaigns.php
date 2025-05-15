<?php
Doo::loadModel('base/ScLongcourseCampaignsBase');

class ScLongcourseCampaigns extends ScLongcourseCampaignsBase{
    public function addBatch($smsbatch){
        $query = "INSERT INTO `sc_longcourse_campaigns` (`sms_shoot_id`, `user_id`, `route_id`,`sender_id`, `contacts`, `sms_count`, `price`, `total_contacts`, `sms_type`, `sms_text`, `submission_time`, `start_time`, `submission_interval`, `submission_days`, `send_flag`, `sms_status`, `dlr`, `vendor_dlr`) VALUES";

        foreach($smsbatch as $data){
            $query .= "(";
            $query .= "'".$data['sms_shoot_id']."',";
            $query .= $data['user_id'].",";
            $query .= $data['route_id'].",";
            $query .= $data['sender_id'].",";
            $query .= "'".$data['contacts']."',";
            $query .= $data['sms_count'].",";
            $query .= $data['price'].",";
            $query .= $data['total_contacts'].",";
            $query .= "'".$data['sms_type']."',";
            $query .= "'".$data['sms_text']."',";
            $query .= "'".$data['submission_time']."',";
            $query .= "'".$data['start_time']."',";
            $query .= $data['submission_interval'].",";
            $query .= $data['submission_days'].",";
            $query .= $data['send_flag'].",";
            $query .= intval($data['sms_status']).",";
            $query .= intval($data['dlr']).",";
            $query .= "'".$data['vendor_dlr']."'),";
        }
        $query = substr($query, 0, strlen($query)-1);
		$rs = Doo::db()->query($query);
    }

    public function pickAllDistinctBatches(){
        $opt['select'] = 'DISTINCT (sms_shoot_id) as shoot_id';
        return Doo::db()->find($this,$opt);
    }

    public function getMinBatch($shoot_id){
        $opt['select']="Min(id) as min_batch_id,`sms_shoot_id`, `user_id`, `route_id`,`sender_id`, `contacts`, `sms_count`, `price`, `total_contacts`, `sms_type`, `sms_text`, `submission_time`, `start_time`, `submission_interval`, `submission_days`, `send_flag`, `sms_status`, `last_sent_time`, `dlr`, `vendor_dlr`";
        $opt['limit'] = 1;
        $opt['where'] = "sms_shoot_id = '$shoot_id'";
        return Doo::db()->find($this,$opt);
    }

    public function updateLastSentTime($shoot_id){
		$opt['where'] = "sms_shoot_id = '$shoot_id'";
		Doo::db()->find($this,$opt);
		$this->last_sent_time = date(Doo::conf()->date_format_db);
		Doo::db()->update($this,$opt);
	}

	public function deleteBatch($id){
		$this->id = $id;
		Doo::db()->delete($this,array('limit'=>1));
	}
}
