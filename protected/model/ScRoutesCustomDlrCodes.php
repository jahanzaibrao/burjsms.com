<?php
Doo::loadModel('base/ScRoutesCustomDlrCodesBase');

class ScRoutesCustomDlrCodes extends ScRoutesCustomDlrCodesBase{
	public function getErrorCodes($rid)
	{
		$this->route_id = $rid;
		return Doo::db()->find($this);
	}

	public function getMultipleErrorCodes($rids)
	{
		$opt['where'] ="route_id IN ($rids)";
		return Doo::db()->find($this,$opt);
	}

	public function cleanData($rid)
	{
		$this->route_id = $rid;
		Doo::db()->delete($this);
	}

	public function addCode($rid, $code, $desc, $rflag, $rtype, $type)
	{
		$this->route_id = $rid;
		$this->dlr_code = $code;
		$this->description = $desc;
		$this->action = $rflag;
		$this->param_value = $rtype;
		$this->category = $type;

		Doo::db()->insert($this);
	}

	public function decodeStatus($code, $smsc)
	{
		$this->route_id = $smsc;
		$this->error_code = $code;
		$opt['select'] = 'description';
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
	}

	public function checkRefund($rid, $cd){

		$opt['where'] = "route_id = $rid AND dlr_code = '$cd'";
		$opt['select'] = 'action, param_value';
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
	}
	public function explainDlrCode($err, $route){

		$opt['where'] = "route_id = $route AND dlr_code = '$err'";
		$opt['select'] = 'description';
		$opt['limit'] = 1;
		$res = Doo::db()->find($this, $opt)->description;
		return $res==''?'N/A':$res;
	}
}
