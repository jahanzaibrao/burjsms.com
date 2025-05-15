<?php
Doo::loadModel('base/ScUserContactsBase');

class ScUserContacts extends ScUserContactsBase{
    
    public function insertBulkContacts($data, $ext, $user, $group){
     
            //excel file load all columns
            $query = "INSERT INTO `sc_user_contacts` (`user_id`,`mobile`,`name`,`varC`,`varD`,`varE`,`varF`,`varG`,`network`,`circle`,`country`,`group_id`) VALUES ";
            
            foreach($data as $dt){
                $query .= "(";
                $query .= $user.",";	
                $query .= $dt['contact'].",";	
                $query .= "'".$dt['name']."',";	
                $query .= "'".$dt['varC']."',";		
                $query .= "'".$dt['varD']."',";		
                $query .= "'".$dt['varE']."',";		
                $query .= "'".$dt['varF']."',";		
                $query .= "'".$dt['varG']."',";		
                $query .= "'".$dt['network']."',";
                $query .= "'".$dt['circle']."',";
                $query .= intval($dt['country']).",";
                $query .= $group."),";
            }
            
              $query = substr($query, 0, strlen($query)-1);
		      $rs = Doo::db()->query($query);
            
    }
    
    
    
    
    
    
    
	
	public function countContacts($groupid){
        $opt['select'] = "COUNT(id) as total";
        $opt['where'] = 'group_id='.$groupid;
        $opt['limit'] = 1;
        return Doo::db()->find($this,$opt)->total;
    }
    
    public function deleteContacts($mode, $param){
        if($mode=='group'){
            $this->group_id = $param;
            $num = Doo::db()->delete($this);
            return $num;
        }
    }
    
    public function getContacts($userid){
	$this->user_id = $userid;
	return Doo::db()->find( $this);	
	}
	
	public function getContactsByGroup($group_id){
	$opt['where'] = "group_id LIKE '%$group_id%'";
	$opt['select'] = 'contact_no';
	$opt['asArray'] = true;
	return Doo::db()->find($this, $opt);	
	}
	
	public function getMyTotal($uid, $params)
	{
		
		$where = 'user_id = '.$uid;
		if($params['group']!=0)
		{
		$where .= ' AND group_id = '.$params['group'];	
		}
		if($params['name']!='Search by name..')
		{
		$where .= ' AND contact_name LIKE "%'.$params['name'].'%"'	;
		}
		if($params['email']!='Search by email..')
		{
		$where .= ' AND contact_email LIKE "%'.$params['email'].'%"'	;
		}
		if($params['contact_no']!='Search by contact no..')
		{
		$where .= ' AND contact_no LIKE "%'.$params['contact_no'].'%"'	;
		}
		$options['where'] = $where;
		return $where==''?count(Doo::db()->find( $this)):count(Doo::db()->find( $this, $options));	
	}
	
	public function bulkContactImport($data, $grpid, $usrid)
	{
		$query = "INSERT INTO `sc_user_contacts` (`user_id`,`contact_no`,`contact_name`,`contact_email`,`group_id`,`operator`,`status`) VALUES ";
		foreach ($data as $contact=>$info)
		{
		
		$query .= "(";
		$query .= $usrid.",";	
		$query .= "'".$contact."',";	
		$query .= "'".$info['name']."',";	
		$query .= "'".$info['email']."',";
		$query .= "'".$grpid."',";
		$query .= "'".$info['operator']."',";
		$query .= '1'."),";
		}
		$query = substr($query, 0, strlen($query)-1);
		
		$rs = Doo::db()->query($query);
	}
	
	public function multipleAdd($data, $grpid, $usrid)
	{
		foreach ($data as $contact=>$info)
		{
		$this->user_id = $usrid;
		$this->contact_no = $contact;
		$this->contact_name = $info['name'];
		$this->contact_email = $info['email'];
		$this->group_id = $grpid;
		$this->city = $info['city'];
		$this->state = $info['state'];
		$this->zip = $info['zip'];	
		$lid = Doo::db()->insert($this);
		}
	}
	
	public function getContactDetails($cid)
	{
		$this->id = $cid;
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
	}
	
	public function removeContact($cid)
	{
		$this->id = $cid;
		Doo::db()->delete($this);
	}
	
	public function getMyTotal2($uid, $whr){
		$this->user_id = $uid;
		if($whr!='')$opt['where'] = $whr;
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScContactGroups';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}
	
	public function getCt2($uid, $orderby, $otype, $where, $limit){
		$this->user_id = $uid;
		if($orderby!='' && $orderby!='no')$opt[$otype] = $orderby;
		if($where!='')$opt['where'] = $where;
		if($limit!='')$opt['limit'] = $limit;
		$opt['select'] = 'sc_user_contacts.*, sc_contact_groups.group_name as group_name';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScContactGroups';
		//var_dump($opt);die;
		if(!empty($opt)){
		return Doo::db()->find($this, $opt);
		}else{
			return Doo::db()->find($this);
		}
		
		}
		
		public function removeMany($cids){
		$opt['where'] = "id IN ($cids)";
		Doo::db()->delete($this, $opt);	
		}
		
	
	public function getGroupsContact($gids){
		$opt['where'] = "group_id IN ($gids)";
		return Doo::db()->find($this, $opt);	
	}
}