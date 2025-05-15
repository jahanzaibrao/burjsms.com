<?php
Doo::loadModel('base/ScSmsPlanOptionsBase');

class ScSmsPlanOptions extends ScSmsPlanOptionsBase{
    
    public function addSubsOptions($plan_id, $opt_data){
        $query = "INSERT INTO `sc_sms_plan_options` (`plan_id`,`plan_type`,`subopt_idn`,`opt_data`) VALUES ";
		foreach ($opt_data as $data){
		   
		$query .= "(";
		$query .= $plan_id.",1,";
		$query .= "'".$data['idn']."',";
		$query .= "'".serialize($data)."'),";
		
		}
		$query = substr($query, 0, strlen($query)-1); 
		//echo $query;
		$rs = Doo::db()->query($query);
    }
    
    public function getSmsPrice($plan, $rdata, $subopt=''){
        //get opt_data
        if($subopt==''){
            //volume based
            $this->plan_id = $plan;
            $data = Doo::db()->find($this,array('limit'=>1,'select'=>'opt_data'));
            $prc_data = unserialize($data->opt_data);
            $res = array();
            $total = 0;
            foreach($rdata as $cdata){
                $credits = intval($cdata->credits);
                $prc = floatval($cdata->price); //I think we need to remove this
                $rid = $cdata->id;
                if($credits!=0){
                    $found = 0; //flag to see if the price entered is in range or not
                    //loop through pricing 
                    foreach($prc_data as $prc){
                        if($credits>=$prc['min'] && $credits<=$prc['max']){
                            //credits in range
                            $rate = $prc[$rid];
                            $found = 1;
                            break;
                        }   
                    }
                    
                    if($found==0){
                        //max volume rate
                        $rate = end($prc_data)[$rid];
                    }
                    
                    $total += $credits*$rate; 
                    $res[$rid]['credits'] = $credits;
                    $res[$rid]['price'] = $rate;
                    $res[$rid]['total'] = $credits*$rate;
                }else{
                    //min volume rate
                    $rate = $prc_data[0][$rid];
                        
                    $total += 0; 
                    $res[$rid]['credits'] = 0;
                    $res[$rid]['price'] = $rate;
                    $res[$rid]['total'] = 0;
                }
                  
            }
            $res['total'] = $total;
            return $res;
            
        }else{
            //subscription based
            $this->plan_id = $plan;
            $this->subopt_idn = $subopt;
            $prc_data = Doo::db()->find($this,array('limit'=>1,'select'=>'opt_data'));
            $data = unserialize($prc_data->opt_data);
            $total = $data['fee'];
            return $total;
        }
        
    }
    
    public function getIdnData($plan, $idn){
        $this->plan_id = $plan;
        $this->subopt_idn = $idn;
        return Doo::db()->find($this,array('limit'=>1,'select'=>'opt_data'));
    }
}