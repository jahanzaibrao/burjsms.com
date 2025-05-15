<?php
Doo::loadModel('base/ScStatsSalesResellerBase');

class ScStatsSalesReseller extends ScStatsSalesResellerBase{
    
    public function getSalesDayWise($uid,$start,$end){
        $opt['where'] = "reseller_id = $uid AND c_date BETWEEN '$start' AND '$end'";
        return Doo::db()->find($this, $opt);        
	}
    
    public function getSalesByDate($dates,$limit){
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
	$opt['select'] ='DISTINCT reseller_id , SUM( sms_sold_today ) AS total_sms, SUM(new_users_today) as new_clients';
	$opt['limit'] = $limit;
	$opt['desc'] = 'total_sms';
    $opt['groupby'] = 'reseller_id';    
	return Doo::db()->find($this, $opt);	
	}
    
    public function addStat($date,$rid,$amt,$ucount){
        $this->c_date = $date;
        $this->reseller_id = $rid;
        $rs = Doo::db()->find($this,array('limit'=>1));
        if($rs->id){
            //update
            $this->id = $rs->id;
            $this->sms_sold_today = $rs->sms_sold_today+$amt;
            $this->new_users_today = $rs->new_users_today+$ucount;
            Doo::db()->update($this);
        }else{
            $this->sms_sold_today = $amt;
            $this->new_users_today = $ucount;
            Doo::db()->insert($this);
        }
    }
}