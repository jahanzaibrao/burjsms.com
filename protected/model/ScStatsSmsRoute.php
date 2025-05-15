<?php
Doo::loadModel('base/ScStatsSmsRouteBase');

class ScStatsSmsRoute extends ScStatsSmsRouteBase{
    
    public function getSmsByDate($dates){
     if($dates!=NULL && trim(urldecode($dates))!='Select Date'){
		//split the dates
		$datr = explode("-",urldecode($dates));
		$from = date('Y-m-d',strtotime(trim($datr[0])));
		$to = date('Y-m-d',strtotime(trim($datr[1])));	
         
       if($from==$to){
				$sWhere = "c_date = '$from'";	
				}else{
				$sWhere = "c_date BETWEEN '$from' AND '$to'";	
				}
			$opt['where'] = $sWhere;
     }
	$opt['select'] ='DISTINCT route_id , SUM( total_sms_sent ) AS total_sms';
	$opt['desc'] = 'total_sms';
    $opt['groupby'] = 'route_id';    
	return Doo::db()->find($this, $opt);	
	}
    
    public function addStat($date, $rid, $sent, $del, $fail, $used, $ref){
        $this->c_date = $date;
        $this->route_id = $rid;
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