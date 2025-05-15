<?php
Doo::loadModel('base/ScDlrRefundRulesBase');

class ScDlrRefundRules extends ScDlrRefundRulesBase{
	
	public function getMyTotal($whr){
		if($whr!='')$opt['where'] = $whr;
		$opt['select'] = 'id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}
	
	public function getRules( $orderby, $otype, $where, $limit){
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
		
	public function addRule($title, $status){
		$this->title = $title;
		$this->def_status = $status;
		Doo::db()->insert($this);	
	}
	
	public function getDetails($rid){
		$this->id =  $rid;
		return Doo::db()->find($this, array('limit'=>1));
		}
	
	public function saveRule($rid, $title, $status){
		$this->id = $rid;
		Doo::db()->find($this);
		$this->title = $title;
		$this->def_status = $status;
		Doo::db()->update($this);
		}
}