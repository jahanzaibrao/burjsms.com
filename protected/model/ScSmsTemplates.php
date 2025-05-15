<?php
Doo::loadModel('base/ScSmsTemplatesBase');

class ScSmsTemplates extends ScSmsTemplatesBase{
    
    public function getPendingTemplates(){
        $opt['select'] = 'sc_sms_templates.*, sc_users.name as name,sc_users.category as category, sc_users.user_id as uid, sc_users.avatar as avatar, sc_users.email as email';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';
		$opt['desc'] = 'sc_sms_templates.status';
		$opt['where'] = 'sc_sms_templates.route_id<>0 AND sc_users.user_id<>1';
		return Doo::db()->find($this, $opt);
    }
	
	public function getAllTemps($usrid,$limit=0){
	$this->user_id = $usrid;
	
	return $limit==0? Doo::db()->find($this):Doo::db()->find($this, array('limit'=>$limit))	;
	}
	
	public function addNewTemp($usrid, $title, $content){
	$this->user_id = $usrid;
	$this->title = $title;
	$this->content = $content;
	$this->created_on = date('Y-m-d h:i:s A');	
	$rs = Doo::db()->insert($this);
	}
	
	public function getTempDetails($uid, $tid){
	$this->id = $tid;
	$this->user_id = $uid;
	return Doo::db()->find($this, array('limit'=>1))	;
	}
	
	public function saveTemplate($uid, $tid, $title, $content){
		$this->id = $tid;
		$this->user_id = $uid;
		Doo::db()->find($this)	;
		$this->title = $title;
		$this->content = $content;
		$this->status = 0;
		Doo::db()->update($this);
	}
	
	public function removeTid($uid,$tid){
		$this->id = $tid;
		$this->user_id = $uid;
		Doo::db()->delete($this)	;
	}
	
	public function getMyTotal2($uid, $whr){
			$this->user_id = $uid;
		if($whr!='')$opt['where'] = $whr;
		$opt['select'] = 'id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}
	
	public function getNums( $uid, $orderby, $otype, $where, $limit){
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
		
	public function getAdminTotal($whr){
		if($whr!='')$opt['where'] = $whr;
		$opt['select'] = 'id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
		}	
	public function getApproveTemps( $orderby, $otype, $where, $limit){
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
