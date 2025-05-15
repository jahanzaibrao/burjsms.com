<?php
Doo::loadModel('base/ScUsersBase');

class ScUsers extends ScUsersBase
{

	public function getAllStaff($team = 0)
	{
		if ($team == 0) {
			$query = "SELECT u.user_id as uid, u.avatar as avatar, u.name as name, u.email as email, t.theme as theme, t.name as teamname FROM sc_users u, sc_staff_teams t, sc_staff_rights r WHERE u.user_id = r.staff_uid AND r.team_id = t.id ORDER BY t.id";
		}
		return Doo::db()->fetchAll($query);
	}

	public function getProfileInfo($uid, $fields = 'all')
	{
		$this->user_id = $uid;
		$opt['limit'] = 1;
		if ($fields != 'all') {
			$opt['select'] = $fields;
		}
		return Doo::db()->find($this, $opt);
	}

	public function doLoginStat($uid)
	{
		$this->user_id = $uid;
		Doo::db()->find($this, array('limit' => 1));
		$this->state = 1;
		$this->last_activity = date('Y-m-d h:i:s A');
		$this->last_login_ip = $_SERVER['REMOTE_ADDR'];
		Doo::db()->update($this, array('limit' => 1));
	}

	public function doLogoutStat($uid)
	{
		$this->user_id = $uid;
		Doo::db()->find($this, array('limit' => 1));
		$this->state = 0;
		$this->last_activity = date('Y-m-d h:i:s A');
		$this->last_login_ip = $_SERVER['REMOTE_ADDR'];
		Doo::db()->update($this, array('limit' => 1));
	}


	public function saveUser($user_data, $uid = '')
	{
		if (isset($uid) && $uid != '') {
			$this->user_id = $uid;
			Doo::db()->find($this, array('limit' => 1));
			$this->login_id = $user_data['loginid'];
			$this->password = $user_data['password'];
			$this->category = $user_data['type'];
			$this->name = $user_data['name'];
			$this->mobile = $user_data['mobile'];
			$this->email = $user_data['email'];
			$this->status = $user_data['status'];
			$this->upline_id = $user_data['upline'];
			$this->spam_status = $user_data['spam_status'];

			Doo::db()->update($this);
			return $uid;
		} else {
			$this->login_id = $user_data['loginid'];
			$this->password = $user_data['password'];
			$this->category = $user_data['type'];
			$this->name = $user_data['name'];
			$this->mobile = $user_data['mobile'];
			$this->email = $user_data['email'];
			$this->status = $user_data['status'];
			$this->upline_id = $user_data['upline'];
			$this->spam_status = $user_data['spam_status'];
			$this->registered_on = $user_data['registered_on'];


			return Doo::db()->insert($this);
		}
	}

	public function getAllUsers($userid, $limit = 0)
	{
		$this->upline_id = $userid;
		return Doo::db()->find($this, array('limit' => $limit));
	}

	public function updateLastLoginIp($userid, $ip)
	{
		$this->user_id = $userid;
		$options['limit'] = 1;

		Doo::db()->find($this, $options);
		$this->last_login_ip = $ip;
		Doo::db()->update($this);
	}



	public function saveProfileInfo($userid, $data)
	{
		$this->user_id = $userid;
		Doo::db()->find($this, array('limit' => 1));
		$this->name = $data['name'];
		$this->email = $data['email'];
		$this->mobile = $data['mobile'];
		Doo::db()->update($this);
	}

	public function updatePassword($uid, $old, $new)
	{
		$this->user_id = $uid;
		$rs = Doo::db()->find($this, array('limit' => 1));
		$output = 'ERR';
		if ($old == $rs->password) {
			$this->password = $new;
			Doo::db()->update($this);
			$output = 'DONE';
		}
		return $output;
	}

	public function getDownlineCount($uid)
	{
		$this->upline_id = $uid;
		$opt['select'] = 'count( user_id ) as total_users , category';
		$opt['groupby'] = 'category';
		$opt['asc'] = 'category';
		$opt['where'] = 'status = 1';
		return Doo::db()->find($this, $opt);
	}

	public function getMyTotal($uid, $params)
	{
		$wheresql = 'status = 1';

		if ($uid != 1) {
			$this->upline_id = $uid;
		} else {

			$wheresql .= ' AND upline_id <> 0';

			if ($params['upline'] != '0') {
				$wheresql .= " AND upline_id = " . $params['upline'] . "";
			}
		}


		if ($params['type'] != '0') {
			$wheresql .= " AND category = '" . $params['type'] . "'";
		}

		if ($params['keywords'] != 'Search by keywords..') {
			$wheresql .= " AND ( name LIKE '%" . $params['keywords'] . "%' OR login_id LIKE '%" . $params['keywords'] . "%' OR email LIKE '%" . $params['keywords'] . "%') ";
		}
		$opt['where'] = $wheresql;
		return count(Doo::db()->find($this, $opt));
	}

	public function getCreditsInfo($uid)
	{
		$this->user_id = $uid;
		$opt['limit'] = 1;
		$opt['select'] = 'sc_users.user_id, sc_users.login_id, sc_users.name, sc_users.category, sc_user_credits.rem_balance, sc_user_credits.expiry';
		$opt['filters'] = array();
		$opt['filters'][0]['model'] = 'ScUserCredits';
		return Doo::db()->find($this, $opt);
	}

	public function checkUser($lid)
	{
		$this->login_id = $lid;
		return Doo::db()->find($this, array('limit' => 1));
	}

	public function changeType($uid, $type)
	{
		$this->user_id = $uid;
		Doo::db()->find($this);
		$this->category = $type;
		Doo::db()->update($this);
	}

	public function resetPassword($uid, $pass)
	{
		$this->user_id = $uid;
		Doo::db()->find($this);
		$this->password = $pass;
		Doo::db()->update($this);
	}

	public function changeUserStatus($uid, $status)
	{
		$this->user_id = $uid;
		Doo::db()->find($this);
		$this->status = $status;
		Doo::db()->update($this);
	}


	public function getTopResellers()
	{
		$opt['select'] = 'DISTINCT upline_id , count( user_id ) AS total_users';
		$opt['limit'] = 5;
		$opt['where'] = 'upline_id <> 0';
		$opt['desc'] = 'total_users';
		$opt['groupby'] = 'upline_id';
		return Doo::db()->find($this, $opt);
	}

	public function getAllResellers()
	{
		$this->category = 'reseller';
		return Doo::db()->find($this);
	}

	public function getSignupData()
	{
		$opt['select'] = "count( user_id ) as total , SUBSTR( `registered_on` , 1, 10 ) AS mydate";
		$opt['limit'] = 5;
		$opt['where'] = "registered_on <> ''";
		$opt['groupby'] = 'mydate';
		$opt['desc'] = 'total';

		return Doo::db()->find($this, $opt);
	}

	public function getAdminDownline($type)
	{
		$this->category = $type;
		$this->upline_id = 1;
		return count(Doo::db()->find($this));
	}

	public function getIndirectUsers($type)
	{
		$this->category = $type;
		$opt['where'] = "upline_id <> 1 AND upline_id <> 0";
		return count(Doo::db()->find($this, $opt));
	}

	public function setSpam($state, $uid)
	{
		$this->user_id = $uid;
		Doo::db()->find($this);
		$this->spam_status = $state;
		Doo::db()->update($this);
	}


	public function getInfoByEmail($email)
	{
		$this->email = $email;
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
	}

	public function checkEmail($lid)
	{
		$this->email = $lid;
		return Doo::db()->find($this, array('limit' => 1));
	}

	public function getTodaysReg()
	{
		$today = date('Y-m-d');
		$opt['where'] = "registered_on LIKE '$today%'";
		return count(Doo::db()->find($this, $opt));
	}

	public function getWeeksReg()
	{
		$today = date('Y-m-d');
		$start = date('Y-m-d', strtotime('first day of this week'));
		$opt['where'] = "registered_on BETWEEN '$start' AND '$today'";
		return count(Doo::db()->find($this, $opt));
	}

	public function getMonthsReg()
	{
		$today = date('Y-m-d');
		$start = date('Y-m-d', strtotime('first day of this month'));
		$opt['where'] = "registered_on BETWEEN '$start' AND '$today'";
		return count(Doo::db()->find($this, $opt));
	}

	public function getDirectUsers($type)
	{
		$this->category = $type;
		$this->upline_id = 1;
		return count(Doo::db()->find($this));
	}

	public function getTotalUsers($type)
	{
		$this->category = $type;
		return count(Doo::db()->find($this));
	}

	public function getMyTotal2($uid, $whr)
	{
		if ($uid != '1') $this->upline_id = $uid;
		if ($whr != '') $opt['where'] = $whr;
		$opt['select'] = 'user_id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}

	public function getNums($uid, $orderby, $otype, $where, $limit)
	{
		if ($uid != '1') $this->upline_id = $uid;
		if ($orderby != '' && $orderby != 'no') $opt[$otype] = $orderby;
		if ($where != '') $opt['where'] = $where;
		if ($limit != '') $opt['limit'] = $limit;
		$opt['desc'] = 'user_id';
		//var_dump($opt);die;
		if (!empty($opt)) {
			return Doo::db()->find($this, $opt);
		} else {
			return Doo::db()->find($this);
		}
	}

	public function isValidUpline($uid, $upline_id)
	{
		if (($uid == $upline_id) && $uid != '' && $upline_id != '') {
			return true;
		}

		$this->user_id = $uid;
		$opt['limit'] = 1;
		$opt['select'] = 'upline_id';
		$rs = Doo::db()->find($this, $opt);
		if ($rs->upline_id == $upline_id) {
			return true;
		} else {
			return false;
		}
	}
}
