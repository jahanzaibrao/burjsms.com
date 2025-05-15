<?php
Doo::loadModel('base/ScSmsRoutesPricingBase');

class ScSmsRoutesPricing extends ScSmsRoutesPricingBase{

    public function addBulkMnc($id, $data)
    {
        $qry = 'INSERT INTO sc_sms_routes_pricing(route_id, mccmnc) VALUES ';
        foreach($data as $dt){
            $mccmnc = $dt->mccmnc;
            $qry .= '(';
            $qry .= "$id, '$mccmnc'";
            $qry .= '),';
        }
        $qry = substr($qry, 0, strlen($qry)-1);
		$rs = Doo::db()->query($qry);
    }

    public function getPricingByMccmnc($id, $mccmnc){
        $this->route_id = $id;
        $this->mccmnc = $mccmnc;
        return Doo::db()->find($this, array('limit'=>1));
    }
}