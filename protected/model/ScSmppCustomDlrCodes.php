<?php
Doo::loadModel('base/ScSmppCustomDlrCodesBase');

class ScSmppCustomDlrCodes extends ScSmppCustomDlrCodesBase{
    public function getErrorCodes($id)
	{
		$this->smpp_id = $id;
		return Doo::db()->find($this);
	}

	public function getMultipleErrorCodes($rids)
	{
		$opt['where'] ="smpp_id IN ($rids)";
		return Doo::db()->find($this,$opt);
	}

	public function cleanData($id)
	{
		$this->smpp_id = $id;
		Doo::db()->delete($this);
	}

	public function addCode($smppid, $code, $customcode, $desc, $rflag, $rtype, $type)
	{
		$this->smpp_id = $smppid;
		$this->vendor_dlr_code = $code;
		$this->optional_custom_code = $customcode;
		$this->description = $desc;
		$this->action = $rflag;
		$this->param_value = $rtype;
		$this->category = $type;

		Doo::db()->insert($this);
	}

	public function decodeStatus($code, $smsc)
	{
		$this->smpp_id = $smsc;
		$this->vendor_dlr_code = $code;
		$opt['select'] = 'description';
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
	}

	public function checkRefund($rid, $cd){

		$opt['where'] = "smpp_id = $rid AND vendor_dlr_code = '$cd'";
		$opt['select'] = 'action, param_value';
		$opt['limit'] = 1;
		return Doo::db()->find($this, $opt);
	}
	public function explainDlrCode($err, $route){

		$opt['where'] = "smpp_id = $route AND vendor_dlr_code = '$err'";
		$opt['select'] = 'description';
		$opt['limit'] = 1;
		$res = Doo::db()->find($this, $opt)->description;
		return $res==''?'N/A':$res;
	}
}
