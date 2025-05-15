<?php
Doo::loadModel('base/ScArchiveHistoryBase');

class ScArchiveHistory extends ScArchiveHistoryBase{
    
    public function getLastArchived(){
        $opt['select'] = 'MAX(`selected_date`) as arch_upto, MAX(`archive_time`) as latest_arch';
        $opt['where'] = 'records_moved <> 0';
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt,3);
    }
}