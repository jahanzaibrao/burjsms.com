<?php
Doo::loadModel('base/ScStatsSmsUsersBase');

class ScStatsSmsUsers extends ScStatsSmsUsersBase{
    
    public function getSmsDayWise($uid,$start,$end){
        $opt['where'] = "user_id = $uid AND c_date BETWEEN '$start' AND '$end'";
        return Doo::db()->find($this, $opt);        
	}
    
    public function getSmsByDate($dates,$limit,$upline=1){
     if($dates!=NULL && trim(urldecode($dates))!='Select Date'){
		//split the dates
		$datr = explode("-",urldecode($dates));
		$from = date('Y-m-d',strtotime(trim($datr[0])));
		$to = date('Y-m-d',strtotime(trim($datr[1])));	
         
       if($from==$to){
				$sWhere = "upline_id = $upline AND c_date = '$from'";	
				}else{
				$sWhere = "upline_id = $upline AND c_date BETWEEN '$from' AND '$to'";	
				}
			$opt['where'] = $sWhere;
     }else{
         $opt['where'] = 'upline_id = '.$upline;
     }
	$opt['select'] ='DISTINCT user_id , SUM( total_sms_sent ) AS total_sms';
	$opt['limit'] = $limit;
	$opt['desc'] = 'total_sms';
    $opt['groupby'] = 'user_id';    
	return Doo::db()->find($this, $opt);	
	}
    
    public function addStat($date, $uid, $upline, $sent, $del, $fail, $used, $ref){
        $this->c_date = $date;
        $this->user_id = $uid;
        $this->upline_id = $upline;
        $rs = Doo::db()->find($this,array('limit'=>1));
        if($rs->id){
            //update
            $this->id = $rs->id;
            $this->total_sms_sent = $rs->total_sms_sent+$sent;
            $this->total_sms_delivered = $rs->total_sms_delivered+$del;
            $this->total_sms_failed = $rs->total_sms_failed+$fail;
            $this->credits_used = $rs->credits_used+$used;
            $this->credits_refunded = $rs->credits_refunded+$ref;
            Doo::db()->update($this);
        }else{
            $this->total_sms_sent = $sent;
            $this->total_sms_delivered = $del;
            $this->total_sms_failed = $fail;
            $this->credits_used = $used;
            $this->credits_refunded = $ref;
            Doo::db()->insert($this);
        }
    }
	
}