<?php
Doo::loadModel('base/ScUsersCreditTransactionsBase');

class ScUsersCreditTransactions extends ScUsersCreditTransactionsBase{
    
    public function newTransaction($type, $tdata){
        $query = "INSERT INTO `sc_users_credit_transactions` (`transac_id`,`type`,`credits`,`price`,`route_id`,`transac_by`,`transac_to_user`,`invoice_id`) VALUES ";
        $t = $type=='credit'?1:0;
		foreach ($tdata['cdata'] as $rid=>$info){
		   
		$query .= "(";
		$query .= "'".$tdata['transac_id']."',";
		$query .= $t.",";
		$query .= $info['credits'].",";
		$query .= floatval($info['price']).",";
		$query .= $rid.",";
		$query .= $tdata['transac_by'].",";
		$query .= $tdata['transac_to'].",";
		$query .= $tdata['invoice_id']."),";
		
		}
		$query = substr($query, 0, strlen($query)-1); 
		//echo $query;
		$rs = Doo::db()->query($query);
        
    }
    
    public function getOrdersByDate($dates,$limit,$user=1,$mode='by',$type='credit'){
         if($dates!=NULL && trim(urldecode($dates))!='Select Date'){
            //split the dates
            $datr = explode("-",urldecode($dates));
            $from = date('Y-m-d',strtotime(trim($datr[0])));
            $to = date('Y-m-d',strtotime(trim($datr[1])));	

            $opt['where'] = "transac_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
         }
        if($mode=='by'){
            $this->transac_by = $user;
        }else{
            $this->transac_to_user = $user;
        }
        if($type=='credit'){
            $this->type = 1;
        }elseif($type=='debit'){
            $this->type = 0;
        }
        
        if($limit!=0) $opt['limit'] = $limit;
        $opt['desc'] = 'id';
        return Doo::db()->find($this, $opt);	
	}
    
}