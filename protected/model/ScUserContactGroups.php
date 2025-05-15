<?php
Doo::loadModel('base/ScUserContactGroupsBase');

class ScUserContactGroups extends ScUserContactGroupsBase{
    
    public function getGroupData($gid, $fields='all'){
        $this->id = $gid;
        if($fields!='all') $opt['select'] = $fields;
        $opt['limit'] = 1;
        return Doo::db()->find($this, $opt);
    }
}