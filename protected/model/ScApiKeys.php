<?php
Doo::loadModel('base/ScApiKeysBase');

class ScApiKeys extends ScApiKeysBase
{

	public function getApiKey($uid)
	{
		Doo::loadHelper('DooSmppcubeHelper');
		$this->user_id = $uid;
		$rs = Doo::db()->find($this);
		if ($rs) {
			return DooSmppcubeHelper::aesDecrypt($rs[0]->api_key); // $rs[0]->api_key;
		} else {
			$key = strtoupper(DooSmppcubeHelper::uuidv4()); // strtoupper(uniqid(rand(2,5)));
			$ekey = DooSmppcubeHelper::aesEncrypt($key);
			$this->user_id = $uid;
			$this->api_key = $ekey;
			$this->dhash = hash('sha256', $key);
			Doo::db()->insert($this);
			return $key;
		}
	}

	public function generateKey($uid)
	{
		$opt['limit'] = 1;
		$opt['where'] = 'user_id=' . $uid;
		Doo::db()->find($this, $opt);
		Doo::loadHelper('DooSmppcubeHelper');
		$key = strtoupper(DooSmppcubeHelper::uuidv4());
		$ekey = DooSmppcubeHelper::aesEncrypt($key);
		$this->api_key = $ekey; //strtoupper(uniqid(rand(2,5)));
		$this->dhash = hash('sha256', $key);
		Doo::db()->update($this, $opt);
	}

	public function getUserByKey($key)
	{
		$this->api_key = $key;
		$opt['limit'] = 1;
		$rs = Doo::db()->find($this, $opt);
		if ($rs) {
			return $rs->user_id;
		} else {
			return false;
		}
	}
}
