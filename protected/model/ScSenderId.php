<?php
Doo::loadModel('base/ScSenderIdBase');

class ScSenderId extends ScSenderIdBase
{

	public function getAllSID($userid, $limit = 0)
	{
		$this->req_by = $userid;
		return Doo::db()->find($this, array('limit' => $limit));
	}

	public function getApprovedSID($userid)
	{
		if ($userid != '1') {
			$this->req_by = $userid;
		}
		$opt['groupby'] = "sender_id";

		$this->status = 1;
		return Doo::db()->find($this, $opt);
	}

	public function addNewSid($sid, $usrid, $status = 0)
	{
		$this->sender_id = $sid;
		$this->req_by = $usrid;
		$flag = Doo::db()->find($this, array('limit' => 1, 'select' => 'id'));
		if ($flag->id) {
			//sender id is already there
			return $flag->id;
		} else {
			//add sender id
			$this->req_on = date(Doo::conf()->date_format_db);
			$this->status = $status;
			return Doo::db()->insert($this);
		}
	}

	public function removeSid($uid, $sid)
	{
		$this->id = $sid;
		$this->req_by = $uid;
		Doo::db()->delete($this);
	}

	public function getName($senderid)
	{
		if (intval($senderid) <= 0) {
			return '[AUTO]';
		} else {
			$this->id = $senderid;
			$opt['select'] = 'sender_id';
			$opt['limit'] = 1;
			return Doo::db()->find($this, $opt)->sender_id;
		}
	}

	public function getMyTotal($whr)
	{
		//$this->req_by = $uid;	
		return count(Doo::db()->find($this));
	}

	public function getAdminTotal($whr)
	{
		$opt['select'] = 'sc_sender_id.id, sc_users.user_id';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';

		return count(Doo::db()->find($this, $opt));
	}

	public function getSenderIds()
	{
		$opt['select'] = 'sc_sender_id.*, sc_users.name as name,sc_users.category as category, sc_users.user_id as uid, sc_users.avatar as avatar, sc_users.email as email';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';
		$opt['asc'] = 'sc_sender_id.status';
		$opt['where'] = 'sc_sender_id.id<>1';
		return Doo::db()->find($this, $opt);
	}

	public function approveSid($sid)
	{
		$this->id = $sid;
		Doo::db()->find($this);
		$this->status = 1;
		Doo::db()->update($this);
	}

	public function getSidDetailsByName($name, $approved = "no")
	{
		$this->sender_id = $name;
		if ($approved == "yes") {
			$opt['where'] = 'status=1';
		}
		$opt['limit'] = 1;

		return Doo::db()->find($this, $opt);
	}

	public function getPendingCount()
	{
		$this->status = 0;
		return count(Doo::db()->find($this));
	}

	public function getMyTotal2($uid, $whr)
	{
		$this->req_by = $uid;
		if ($whr != '') $opt['where'] = $whr;
		$opt['select'] = 'id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}

	public function getNums($uid, $orderby, $otype, $where, $limit)
	{
		$this->req_by = $uid;
		if ($orderby != '' && $orderby != 'no') $opt[$otype] = $orderby;
		if ($where != '') $opt['where'] = $where;
		if ($limit != '') $opt['limit'] = $limit;
		$opt['desc'] = 'id';
		//var_dump($opt);die;
		if (!empty($opt)) {
			return Doo::db()->find($this, $opt);
		} else {
			return Doo::db()->find($this);
		}
	}
}
