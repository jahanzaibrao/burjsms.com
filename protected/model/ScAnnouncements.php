<?php
Doo::loadModel('base/ScAnnouncementsBase');

class ScAnnouncements extends ScAnnouncementsBase{
	public function getMyTotal($whr){
		if($whr!='')$opt['where'] = $whr;
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}
	
	public function getList($orderby, $otype, $where, $limit){
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