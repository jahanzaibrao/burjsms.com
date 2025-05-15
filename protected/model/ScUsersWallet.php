<?php
Doo::loadModel('base/ScUsersWalletBase');

class ScUsersWallet extends ScUsersWalletBase{

    public function doCreditTrans($mode, $user, $amount){
        if($mode=='deduct'){
            $this->user_id = $user;
            $rs = Doo::db()->find($this,array('select'=>'id,amount','limit'=>1));
            if(!$rs->id){
                return false; //in case of admin don't do any transaction
            }else{
                $newcredits = $rs->amount - $amount;
            
                $this->id = $rs->id;
                $this->amount = $newcredits;
                Doo::db()->update($this,array('limit'=>1));

                return array('before'=>$rs->amount,'after'=>$newcredits);
            }
            
        }

        if($mode=='add'){
            $this->user_id = $user;
            $rs = Doo::db()->find($this,array('select'=>'id,amount','limit'=>1));
            if(!$rs->id){
                return false; //in case of admin don't do any transaction
            }else{
                $newcredits = $rs->amount + $amount;
            
                $this->id = $rs->id;
                $this->amount = $newcredits;
                Doo::db()->update($this,array('limit'=>1));

                return array('before'=>$rs->amount,'after'=>$newcredits);
            }
            
        }
       
    }

}