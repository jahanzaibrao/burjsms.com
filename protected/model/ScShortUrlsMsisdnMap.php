<?php
Doo::loadModel('base/ScShortUrlsMsisdnMapBase');

class ScShortUrlsMsisdnMap extends ScShortUrlsMsisdnMapBase{
    public function addData($data){
        $query = "INSERT INTO `sc_short_urls_msisdn_map` (`parent_url_id`,`url_idf`,`sms_shoot_id`,`mobile`) VALUES ";
		foreach ($data as $dt){
            $query .= "(";	
            $query .= $dt['parent_url_id'].",";
            $query .= "'".$dt['url_idf']."',";
            $query .= "'".$dt['sms_shoot_id']."',";
            $query .= $dt['mobile']."),";
		}
		$query = substr($query, 0, strlen($query)-1);
		$rs = Doo::db()->query($query);
    }
}