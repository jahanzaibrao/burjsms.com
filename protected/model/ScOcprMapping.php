<?php
Doo::loadModel('base/ScOcprMappingBase');

class ScOcprMapping extends ScOcprMappingBase{
	
	public function importData($cid, $prefixes){
        $query = "INSERT INTO `sc_ocpr_mapping` (`coverage`,`prefix`,`operator`,`circle`) VALUES ";
        foreach($prefixes as $pre){
            $query .= '('.$cid.',';
            $query .= $pre['prefix'].",";
            $query .= "'".$pre['operator']."',";
            $query .= "'".$pre['circle']."'),";
        }
        $query = substr($query, 0, strlen($query)-1);
		Doo::db()->query($query);
    }
	
    public function countPrefixes($cid){
        $this->coverage = $cid;
        return intval(Doo::db()->find($this,array('select'=>'count(id) as total','limit'=>1))->total);
    }
}