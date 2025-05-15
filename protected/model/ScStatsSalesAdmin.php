<?php
Doo::loadModel('base/ScStatsSalesAdminBase');

class ScStatsSalesAdmin extends ScStatsSalesAdminBase{
    
    public function getSalesDayWise($start,$end){
        $opt['where'] = "c_date BETWEEN '$start' AND '$end'";
        return Doo::db()->find($this, $opt);        
	}
    
    public function addStat($date,$amt){
        $this->c_date = $date;
        $rs = Doo::db()->find($this,array('limit'=>1));
        if($rs->id){
            //update
            $this->id = $rs->id;
            $this->sale_amount = $rs->sale_amount+$amt;
            Doo::db()->update($this);
        }else{
            $this->sale_amount = $amt;
            Doo::db()->insert($this);
        }
    }
}