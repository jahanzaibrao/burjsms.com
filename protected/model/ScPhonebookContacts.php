<?php
Doo::loadModel('base/ScPhonebookContactsBase');

class ScPhonebookContacts extends ScPhonebookContactsBase{
    
    public function insertBulkContacts($data, $group){

            $query = "INSERT INTO `sc_phonebook_contacts` (`mobile`,`group_id`) VALUES ";
            
            foreach($data as $mob){
                $query .= "(";
                $query .= $mob.",";
                $query .= $group."),";
            }
            
              $query = substr($query, 0, strlen($query)-1);
		      $rs = Doo::db()->query($query);
    }
    
    public function getTotalContacts($group){
        $opt['select'] = 'COUNT(`id`) as total';
        $opt['where'] = 'group_id='.$group;
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt)->total;
    }
    
}