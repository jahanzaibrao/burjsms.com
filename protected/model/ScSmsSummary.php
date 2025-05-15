<?php
Doo::loadModel('base/ScSmsSummaryBase');

class ScSmsSummary extends ScSmsSummaryBase{

	public function saveSummary($data){
		$this->user_id = $data['user_id'];
		$this->campaign_id = $data['campaign_id'];
		$this->sms_shoot_id = $data['sms_shoot_id'];
		$this->route_id = $data['route_id'];
		$this->submission_time = $data['submission_time'];
		$this->sms_text = $data['sms_text'];
		$this->sent_via = $data['sent_via'];
		$this->total_submitted = $data['total_sms'];
		$this->duplicates_removed = $data['duplicates_removed'];
		$this->optouts_removed = $data['optedout_removed'];
		$this->invalids_removed = $data['invalid_removed'];
		$this->total_sent = $data['total_sent'];
		$this->charges_per_sms = $data['per_sms_charge'];
		$this->total_credits_deducted = $data['credits_deducted'];
		$this->tlv_data = $data['tlv_data'];
		$this->status = $data['status'];

		Doo::db()->insert($this);

		}

	public function getSummary($shoot_id){
		$this->sms_shoot_id = $shoot_id;
		return Doo::db()->find($this, array('limit'=>1));
		}

	public function getMyTotalJobs($uid, $cid, $whr){
		$this->user_id = $uid;
		if($cid!='0'){$this->campaign_id=$cid;}
		$this->status = 0;
		if($whr!='')$opt['where'] = $whr;
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}

	public function getMyDlr($uid, $cid, $orderby, $otype, $where, $limit){
		$this->user_id = $uid;
		if($cid!='0'){$this->campaign_id=$cid;}
		$this->status = 0;
		if($orderby!='' && $orderby!='no')$opt[$otype] = $orderby;
		if($where!='')$opt['where'] = $where;
		if($limit!='')$opt['limit'] = $limit;
		$opt['desc'] = 'id';
		//var_dump($opt);die;
		if(!empty($opt)){
		return Doo::db()->find($this, $opt);
		}else{
			return Doo::db()->find($this);
		}

		}
}
