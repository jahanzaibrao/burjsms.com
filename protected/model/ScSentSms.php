<?php
Doo::loadModel('base/ScSentSmsBase');

class ScSentSms extends ScSentSmsBase
{

	public function saveSentSMS($sms_data)
	{
		$query = "INSERT INTO `sc_sent_sms` (`sms_shoot_id`,`user_id`,`route_id`,`smsc`,`sender_id`,`mobile`,`sms_count`,`sms_type`,`sms_text`,`submission_time`,`sending_time`,`umsgid`,`mccmnc`,`price`,`cost`,`status`,`smpp_resp_code`,`dlr`,`vendor_dlr`) VALUES ";
		foreach ($sms_data as $data) {
			$query .= "(";
			$query .= "'" . $data['sms_shoot_id'] . "',";
			$query .= $data['user_id'] . ",";
			$query .= $data['route_id'] . ",";
			$query .= "'" . $data['smsc'] . "',";
			$query .= $data['sender_id'] . ",";
			$query .= $data['mobile'] . ",";
			$query .= intval($data['sms_count']) . ",";
			$query .= "'" . $data['sms_type'] . "',";
			$query .= "'" . $data['sms_text'] . "',";
			$query .= "'" . $data['submission_time'] . "',";
			$query .= "'" . $data['sending_time'] . "',";
			$query .= "'" . $data['umsgid'] . "',";
			$query .= intval($data['mccmnc']) . ",";
			$query .= floatval($data['price']) . ",";
			$query .= floatval($data['cost']) . ",";
			$query .= $data['status'] . ",";
			$query .= "'" . $data['smppcode'] . "',";
			$query .= intval($data['dlr']) . ",";
			$query .= "'" . $data['vendor_dlr'] . "'),";
		}
		$query = substr($query, 0, strlen($query) - 1);
		$rs = Doo::db()->query($query);
	}

	public function getMyTotalJobs($uid, $whr)
	{
		$this->user_id = $uid;
		if ($whr != '') $opt['where'] = $whr;
		$opt['groupby'] = 'sms_shoot_id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}

	public function getMyDlr($uid, $orderby, $otype, $where, $limit)
	{
		$this->user_id = $uid;
		if ($orderby != '' && $orderby != 'no') $opt[$otype] = $orderby;
		if ($where != '') $opt['where'] = $where;
		if ($limit != '') $opt['limit'] = $limit;
		$opt['select'] = '*,count(id) as total_sms';
		$opt['groupby'] = 'sms_shoot_id';
		$opt['desc'] = 'id';
		//var_dump($opt);die;
		if (!empty($opt)) {
			return Doo::db()->find($this, $opt);
		} else {
			return Doo::db()->find($this);
		}
	}

	public function getCountByCampaignShoot($cid)
	{
		$this->sms_shoot_id = $cid;
		$opt['select'] = "count(id) as total";
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt)->total;
	}

	public function getMyTotalDlr($uid, $cid, $whr)
	{
		$this->user_id = $uid;
		$this->sms_shoot_id = $cid;
		if ($whr != '') $opt['where'] = $whr;
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}

	public function getMyAllDlr($uid, $cid, $orderby, $otype, $where, $limit)
	{
		$this->user_id = $uid;
		$this->sms_shoot_id = $cid;
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


	public function getTodaysTotal($uid = 0)
	{
		if ($uid == 0) {
			$today = date('Y-m-d');
			$opt['where'] = "sending_time LIKE '$today%'";
			return count(Doo::db()->find($this, $opt));
		} else {
			$this->user_id = $uid;
			$today = date('Y-m-d');
			$opt['where'] = "sending_time LIKE '$today%'";
			return count(Doo::db()->find($this, $opt));
		}
	}

	public function getWeeksTotal($uid = 0)
	{
		if ($uid == 0) {
			$start = date('Y-m-d H:i:s', strtotime('last monday'));
			$today = date('Y-m-d H:i:s');
			$opt['where'] = "sending_time BETWEEN '$start' AND '$today'";
			return count(Doo::db()->find($this, $opt));
		} else {
			$this->user_id = $uid;
			$start = date('Y-m-d H:i:s', strtotime('last monday'));
			$today = date('Y-m-d H:i:s');
			$opt['where'] = "sending_time BETWEEN '$start' AND '$today'";
			return count(Doo::db()->find($this, $opt));
		}
	}

	public function getMonthsTotal($uid = 0)
	{
		if ($uid == 0) {
			$start = date('Y-m-d H:i:s', strtotime('first day of this month'));
			$today = date('Y-m-d H:i:s');
			$opt['where'] = "sending_time BETWEEN '$start' AND '$today'";
			return count(Doo::db()->find($this, $opt));
		} else {
			$this->user_id = $uid;
			$start = date('Y-m-d H:i:s', strtotime('first day of this month'));
			$today = date('Y-m-d H:i:s');
			$today_date = date('Y-m-d');
			$opt['where'] = strtotime($start) == strtotime($today) ? "sending_time LIKE '$today_date%'" : "sending_time BETWEEN '$start' AND '$today'";
			return count(Doo::db()->find($this, $opt));
		}
	}

	public function getDelStatusCount($status, $uid = 0, $duration = 'all')
	{
		if ($uid == 0) {
			$this->dlr = $status;
			return count(Doo::db()->find($this));
		} else {
			if ($duration == 'today') {
				$today = date('Y-m-d');
				$opt['where'] = "sending_time LIKE '$today%'";
			}
			if ($duration == 'week') {
				$start = date('Y-m-d H:i:s', strtotime('last monday'));
				$today = date('Y-m-d H:i:s');
				$opt['where'] = "sending_time BETWEEN '$start' AND '$today'";
			}
			if ($duration == 'month') {
				$start = date('Y-m-d H:i:s', strtotime('first day of this month'));
				$today = date('Y-m-d H:i:s');
				$opt['where'] = "sending_time BETWEEN '$start' AND '$today'";
			}
			$this->user_id = $uid;
			$this->dlr = $status;
			return count(Doo::db()->find($this, $opt));
		}
	}

	public function getTopConsumers($dr, $limit)
	{
		if (trim(urldecode($dr)) != 'Select Date') {
			//split the dates
			$datr = explode("-", urldecode($dr));
			$from = date('Y-m-d', strtotime(trim($datr[0])));
			$to = date('Y-m-d', strtotime(trim($datr[1])));


			if ($from == $to) {
				$sWhere = "sc_sent_sms.sending_time LIKE '$from%'";
			} else {
				$sWhere = "sc_sent_sms.sending_time BETWEEN '$from' AND '$to'";
			}
			$opt['where'] = $sWhere;
		}
		$opt['select'] = 'DISTINCT sc_sent_sms.user_id , count( sc_sent_sms.id ) AS total_sms, sc_users.name, sc_users.email';
		$opt['limit'] = $limit;
		$opt['desc'] = 'total_sms';
		$opt['groupby'] = 'sc_sent_sms.user_id';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';
		return Doo::db()->find($this, $opt);
	}

	public function updateDlr($shootid, $to, $data)
	{
		$options['where'] = "sms_shoot_id = '$shootid' AND sent_to = " . $to;
		$options['limit'] = 1;
		Doo::db()->find($this, $options);
		$this->dlr = $data['dlr'];
		$this->vendor_dlr = $data['vendor_dlr'];
		$this->vendor_msgid = $data['vmsgid'];
		Doo::db()->update($this, $options);
	}

	public function getShootData($shootid, $limit = '0')
	{
		$this->sms_shoot_id = $shootid;
		if ($limit != '0') {
			$opt['limit'] = $limit;
		}
		$opt['select'] = 'sc_sent_sms.*, sc_sender_id.sender_id as sid_name';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScSenderId';
		return Doo::db()->find($this, $opt);
	}

	public function getAdminTotal($whr)
	{
		$opt['select'] = 'sc_sent_sms.id, sc_sent_sms.user_id';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';

		return count(Doo::db()->find($this, $opt));
	}

	public function getAll($orderby, $otype, $where, $limit)
	{
		$opt['select'] = 'sc_sent_sms.*, sc_users.name as user, sc_users.user_id as uid';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUsers';
		$opt['desc'] = 'sc_sent_sms.id';
		if ($orderby != '' && $orderby != 'no') $opt[$otype] = $orderby;
		if ($where != '') $opt['where'] = $where;
		if ($limit != '') $opt['limit'] = $limit;
		return Doo::db()->find($this, $opt);
	}

	public function getShootDLR($shootid)
	{
		$this->sms_shoot_id = $shootid;
		$opt['select'] = 'sent_to, dlr, vendor_dlr, route_id, user_id';
		return Doo::db()->find($this, $opt);
	}
}
