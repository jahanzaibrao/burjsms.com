<?php
Doo::loadModel('base/ScLogsDlrRefundsBase');

class ScLogsDlrRefunds extends ScLogsDlrRefundsBase{
	
	public function addLog($data){
		$this->user_id = $data['user_id'];
		$this->campaign_id = $data['campaign_id'];
		$this->sms_shoot_id = $data['sms_shoot_id'];
		$this->mobile_no = $data['mobile_no'];
		$this->vendor_dlr = $data['vendor_dlr'];
		$this->refund_amt = $data['refund_amt'];
		$this->refund_rule = $data['refund_rule'];
		$this->timestamp = $data['timestamp'];
		
		Doo::db()->insert($this);
    }
		
	public function getMyTotal($uid, $whr){
		$this->user_id = $uid;
		if($whr!='')$opt['where'] = $whr;
		return count(Doo::db()->find($this, $opt));
	}
	
	public function getAllLog($uid, $orderby, $otype, $where, $limit){
		$this->user_id = $uid;
		if($orderby!='' && $orderby!='no')$opt[$otype] = $orderby;
		if($where!='')$opt['where'] = $where;
		if($limit!='')$opt['limit'] = $limit;
		$opt['desc'] = 'id';
		if(!empty($opt)){
		return Doo::db()->find($this, $opt);
		}else{
			return Doo::db()->find($this);
		}
		
    }
}