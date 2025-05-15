<?php
Doo::loadModel('base/ScLogsCreditsBase');

class ScLogsCredits extends ScLogsCreditsBase{
	
	
	
	public function getMyTotal($uid, $whr){
		$this->user_id = $uid;
		if($whr!='')$opt['where'] = $whr;
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}
	
	public function getAllLog($uid, $orderby, $otype, $where, $limit){
		$this->user_id = $uid;
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