<?php
Doo::loadModel('base/ScSpamKeywordsBase');

class ScSpamKeywords extends ScSpamKeywordsBase{

	public function checkSpam($text)
	{
		$output = array();
		$spam_words = Doo::db()->find($this);
		foreach ($spam_words as $phrase)
		{
			$pos = stristr($text,$phrase->phrase);

				if(strlen(trim($pos))>0) {

                    array_push($output, $phrase->phrase);

				}
		}
		return $output;
	}

	public function getAllKeywords($limit)
	{
		$opt['limit'] = $limit;
		return Doo::db()->find($this, $opt);
	}

	public function getMyTotal()
	{
		return count(Doo::db()->find($this));
	}

	public function addKeyword($word)
	{
		$this->phrase = $word;
		Doo::db()->insert($this);
	}

	public function delKeyword($id)
	{
		$this->id = $id;
		Doo::db()->delete($this);
	}

	public function getKwDetails($id){
	$this->id = $id;
	return Doo::db()->find($this, array("limit"=>1));
	}

	public function saveKeyword($id, $phrase){
	$this->id = $id;
	Doo::db()->find($this, array("limit"=>1));
	$this->phrase = $phrase;
	Doo::db()->update($this);
	}

	public function getMyTotal2($whr){
		if($whr!='' && $whr!=')')$opt['where'] = $whr;
		$opt['select'] = 'id';
		//echo $where;die;
		return count(Doo::db()->find($this, $opt));
	}

	public function getNums( $orderby, $otype, $where, $limit){
		if($orderby!='' && $orderby!='no')$opt[$otype] = $orderby;
		if($where!='' && $where!=')')$opt['where'] = $where;
		if($limit!='')$opt['limit'] = $limit;
		$opt['desc'] = 'id';

		if(!empty($opt)){
		return Doo::db()->find($this, $opt);
		}else{
			return Doo::db()->find($this);
		}

		}
}
