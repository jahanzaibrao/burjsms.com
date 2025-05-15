<?php
Doo::loadModel('base/ScBlacklistIndexBase');

class ScBlacklistIndex extends ScBlacklistIndexBase{
    
    public function recordsChanged($mode, $table, $count=0){
        if($mode=='add'){
            $qry = "UPDATE `sc_blacklist_index` SET `total_records` = `total_records`+$count WHERE `table_name`='$table' LIMIT 1";
        }else if($mode=='remove'){
             $qry = "UPDATE `sc_blacklist_index` SET `total_records` = `total_records`-$count WHERE `table_name`='$table' LIMIT 1";
        }else if($mode=='removeall'){
            $qry = "UPDATE `sc_blacklist_index` SET `total_records` = 0 WHERE `table_name`='$table' LIMIT 1";
        }
        Doo::db()->query($qry, null, 2);
    }
    
    
}