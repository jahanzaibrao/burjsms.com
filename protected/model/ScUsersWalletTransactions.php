<?php
Doo::loadModel('base/ScUsersWalletTransactionsBase');

class ScUsersWalletTransactions extends ScUsersWalletTransactionsBase{

    public function getOrdersByDate($dates,$limit,$walletid = 0,$type='credit'){
        if($dates!=NULL && trim(urldecode($dates))!='Select Date'){
           //split the dates
           $datr = explode("-",urldecode($dates));
           $from = date('Y-m-d',strtotime(trim($datr[0])));
           $to = date('Y-m-d',strtotime(trim($datr[1])));	

           $opt['where'] = "t_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
        }
       
        if($type=='credit'){
            $this->transac_type = 1;
        }elseif($type=='debit'){
            $this->transac_type = 0;
        }elseif($type=='all'){
            //show all
        }

        if($walletid!=0){
            $this->wallet_id = $walletid;
        }
        
        if($limit!=0) $opt['limit'] = $limit;
        $opt['desc'] = 'id';
        return Doo::db()->find($this, $opt);	
   }
   
}