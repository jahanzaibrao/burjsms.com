<?php
Doo::loadModel('base/ScUsersCreditDataBase');

class ScUsersCreditData extends ScUsersCreditDataBase
{

    public function saveCreditData($uid, $data, $expiry = '')
    {
        $query = "INSERT INTO `sc_users_credit_data` (`user_id`,`route_id`,`credits`,`price`,`validity`,`delv_per`,`delv_threshold`,`fdlr_id`) VALUES ";
        foreach ($data as $rid => $info) {

            $query .= "(";
            $query .= $uid . ",";
            $query .= $rid . ",";
            $query .= intval($info['credits']) . ",";
            $query .= floatval($info['price']) . ",";
            if ($expiry != '') {
                $val = date(Doo::conf()->date_format_db, strtotime($expiry));
            } else {
                if ($info['validity'] == '') {
                    $val = date(Doo::conf()->date_format_db, strtotime('today + 20 years'));
                } else {
                    $val = date(Doo::conf()->date_format_db, strtotime('today + ' . $info['validity']));
                }
            }

            $query .= "'" . $val . "',";
            $query .= Doo::conf()->def_dlr_per . ",";
            $query .= Doo::conf()->dlr_per_threshold . ",";
            $query .= "1),";
        }
        $query = substr($query, 0, strlen($query) - 1);
        //echo $query; die;
        $rs = Doo::db()->query($query);
    }

    public function doCreditTrans($mode, $user, $route, $amount, $validity = '', $price = 0, $both = false)
    {
        if ($mode == 'debit') {
            $this->user_id = $user;
            $this->route_id = $route;
            $rs = Doo::db()->find($this, array('select' => 'id,credits', 'limit' => 1));
            if (!$rs->id) {
                return false; //in case of admin don't do any transaction
            } else {
                $newcredits = $rs->credits - $amount;

                $this->id = $rs->id;
                $this->credits = $newcredits;
                Doo::db()->update($this, array('limit' => 1));
                return $both == false ? $newcredits : array('old' => $rs->credits, 'new' => $newcredits);
            }
        }
        if ($mode == 'credit') {
            $this->user_id = $user;
            $this->route_id = $route;
            $rs = Doo::db()->find($this, array('select' => 'id,credits,validity', 'limit' => 1));
            if (!$rs->id) {
                return false; //in case of admin don't do any transaction
            } else {
                $newcredits = $rs->credits + $amount;
                if ($price != 0) {
                    //meaning reseller/admin changed the price per sms during this transaction
                    $this->price = $price;
                }
                $this->id = $rs->id;
                $this->credits = $newcredits;
                Doo::db()->update($this, array('limit' => 1));
                return $both == false ? $newcredits : array('old' => $rs->credits, 'new' => $newcredits);
            }
        }
    }

    public function getRouteCredits($uid, $rid)
    {
        $this->user_id = $uid;
        $this->route_id = $rid;
        $rs = Doo::db()->find($this, array('select' => 'credits', 'limit' => 1));
        return $rs->credits;
    }
}
