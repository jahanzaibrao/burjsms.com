<?php
Doo::loadModel('base/ScStatsSmsAdminBase');

class ScStatsSmsAdmin extends ScStatsSmsAdminBase{
    
    public function getSmsDayWise($start,$end){
        $opt['where'] = $start==$end?"c_date = '$start'":"c_date BETWEEN '$start' AND '$end'";
        return Doo::db()->find($this, $opt);        
	}
    
    public function addStat($date, $sent, $del, $fail, $used, $ref){
        $this->c_date = $date;
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