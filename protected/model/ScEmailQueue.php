<?php
Doo::loadModel('base/ScEmailQueueBase');

class ScEmailQueue extends ScEmailQueueBase{
    
    public function bulkInsert($data){
        $query = "INSERT INTO `sc_email_queue` (`added_on`,`sender_email`,`sender_name`,`recipient_list`,`email_sub`,`email_text`,`status`) VALUES ";
		foreach ($data as $dt){
            $query .= "(";
            $query .= "'".date(Doo::conf()->date_format_db)."',";	
            $query .= "'".$dt['sender_email']."',";	
            $query .= "'".$dt['sender_name']."',";	
            $query .= "'".$dt['recipient_list']."',";
            $query .= "'".$dt['email_sub']."',";
            $query .= "'".$dt['email_text']."',";
            $query .= "0),";
		}
		$query = substr($query, 0, strlen($query)-1);
		$rs = Doo::db()->query($query);
    }
}