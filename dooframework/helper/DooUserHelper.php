<?php

/**
 * DooUserHelper class file.
 *
 * @author Saurav <saurabh.pandey@cubelabs.in>
 * @license http://www.doophp.com/license
 */

class DooUserHelper
{

    var $billingType = 0; // 0 = credit based, 1 = MCCMNC based, 2 = currency based

    public function validateInputs($input)
    {
        Doo::loadHelper('DooTextHelper');
        $result = array();
        if (strlen($input['loginid']) < 5) {
            $result['type'] = 'error';
            $result['msg'] = 'Invalid Login ID. Must be at least 5 characters long.';
            return $result;
        }
        if (!DooTextHelper::verifyFormData('email', $input['email'])) {
            $result['type'] = 'error';
            $result['msg'] = 'Invalid email address.';
            return $result;
        }
        if (!DooTextHelper::verifyFormData('mobile', $input['mobile'])) {
            $result['type'] = 'error';
            $result['msg'] = 'Invalid phone number.';
            return $result;
        }
        if (!DooTextHelper::verifyFormData('password', $input['supplied_password']) || $input['supplied_password'] != $input['verify_password']) {
            $result['type'] = 'error';
            $result['msg'] = 'Invalid Password. Please see the instructions for password and make sure both passwords match.';
            return $result;
        }
        if ($this->billingType == 0 && ($input['cat'] == 'reseller' && $input['credit_plan_type'] == 1)) {
            //reseller cannot have subscription based plans
            $result['type'] = 'error';
            $result['msg'] = 'Subscription based plans cannot be assigned to reseller accounts. Please choose a different plan or assign custom SMS rates.';
            return $result;
        }
        if ($this->billingType == 1 && $input['planid'] == 0) {
            $result['type'] = 'error';
            $result['msg'] = 'Please assign a MCC/MNC based plan to the currency based user account.';
            return $result;
        }
        if ($this->billingType != 0 && $input['walletCredits'] <= 0) {
            $result['type'] = 'error';
            $result['msg'] = 'Invalid wallet credits assigned. Please enter a value more than zero.';
            return $result;
        }
        if ($input['account_creator_group'] == 'reseller') {
            //reseller should not be able to add more credits than their own account balance
            $crobj = Doo::loadModel('ScUsersCreditData', true);
            foreach ($input['supplied_routes'] as $rid => $on) {
                $credits = intval($input['supplied_credits'][$rid]);
                if ($credits > $crobj->getRouteCredits($input['account_creator_uid'], $rid)) {
                    //fail
                    $result['type'] = 'error';
                    $result['credits_error'] = array(
                        'type' => 'credits',
                        'routeid' => $rid
                    );
                    $result['msg'] = 'Allotted credits exceeds account balance for a route.  You cannot allot more credits than available. ROUTE-NAME: ';
                    return $result;
                }
            }
        }
        $result['type'] = 'success';
        return $result;
    }

    public function saveUser($input)
    {
        $hfunck = base64_encode($input['loginid'] . '_' . base64_encode('smppcubehash'));
        Doo::loadHelper('DooEncrypt');
        $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $encpass = $encobj->encrypt($input['supplied_password'], $hfunck);

        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $uobj->login_id = $input['loginid'];
        $uobj->password = $encpass;
        $uobj->name = $input['name'];
        $uobj->gender = $input['gender'];
        $uobj->avatar = $input['gender'] == 'm' ? Doo::conf()->default_avatar_male_user : Doo::conf()->default_avatar_female_user;
        $uobj->category = $input['cat'];
        $uobj->subgroup = $input['cat'];
        $uobj->optin_only = $input['optin_perm'];
        $uobj->acl_mode = $input['acl_mode'];
        $uobj->acl_ip_list = $input['acl_ip_list'];
        $uobj->mobile = $input['mobile'];
        $uobj->email = $input['email'];
        $uobj->upline_id = $input['account_creator_uid'];
        $uobj->acc_mgr_id = $input['account_mgr'];
        $uobj->status = 1;
        $uobj->registered_on = date(Doo::conf()->date_format_db);
        $uobj->activation_date = $input['activation'];
        $uobj->account_type = $this->billingType;
        return Doo::db()->insert($uobj);
    }

    public function createInvoiceParams($input)
    {
        $invoice_data = array();
        //calculate total routes and credit cost
        $rcp_matrix = $this->getRouteCreditPriceMatrix($input);
        //calculate tax and discounts
        $total_price = $rcp_matrix ? $rcp_matrix['total_cost'] : $input['walletCredits'];
        $tax_calculated_values = $this->calculateTaxAndDiscount($total_price, $input);
        //return array with values
        return array(
            'plan_tax' => $tax_calculated_values['plan_tax'],
            'wallet_credits' => $this->billingType == 0 ? 0 : $input['walletCredits'],
            'routes_credits' => $rcp_matrix['rcp_data'],
            'total_cost' => $total_price,
            'additional_tax' => isset($input['additionalTax']) && $input['additionalTax'] != 0 ? $input['additionalTax'] . '%' : 'N/A',
            'discount' => $tax_calculated_values['discount'],
            'grand_total' => $tax_calculated_values['grand_total'],
            'inv_status' => $input['invoice_status'],
            'inv_rem' => $input['invoice_remarks']
        );
    }

    public function getRouteCreditPriceMatrix($input)
    {
        if ($this->billingType == 1) return false;
        $total = 0;
        $rdata = array();
        if ($this->billingType == 0) {
            if ($input['planid'] == 0) {
                //custom pricing
                foreach ($input['supplied_routes'] as $rid => $on) {
                    $credits = intval($input['supplied_credits'][$rid]);
                    $rate = floatval($input['prices'][$rid]);
                    $total += $credits * $rate;

                    $rdata[$rid]['credits'] = $credits;
                    $rdata[$rid]['price'] = $rate;
                    $rdata[$rid]['total'] = $credits * $rate;
                }
            } else {
                //plan associated
                if ($input['plan_type'] == 0) {
                    //volume based plan
                    $dbCreditsData = array();
                    foreach ($input['supplied_routes'] as $rid => $on) {
                        $myobj = new stdClass;
                        $myobj->credits = intval($input['supplied_credits'][$rid]);
                        $myobj->id = $rid;
                        array_push($dbCreditsData, $myobj);
                    }

                    $spoobj = Doo::loadModel("ScSmsPlanOptions", true);
                    $pricedata = $spoobj->getSmsPrice($input['planid'], $dbCreditsData);
                    $rdata = $pricedata;
                    unset($rdata['total']);
                    $total = $pricedata['total'];
                } else {
                    //subscription based plan
                    $spoobj = Doo::loadModel("ScSmsPlanOptions", true);
                    $pricedata = $spoobj->getIdnData($input['planid'], $input['plan_suboption']);
                    $subopt_data = unserialize($pricedata->opt_data);
                    $total = $subopt_data['fee'];
                    if ($subopt_data['cycle'] == 'm') {
                        $validity = '30 Days';
                    } else {
                        $validity = '1 year';
                    }
                    foreach ($subopt_data['route_credits'] as $rid => $credits) {
                        $rdata[$rid]['credits'] = $credits;
                        $rdata[$rid]['price'] = $subopt_data['route_add_sms_rate'][$rid];
                        $rdata[$rid]['validity'] = $validity;
                    }
                }
            }
        }

        if ($this->billingType == 2) {
            $total = $input['walletCredits'];
            foreach ($input['supplied_routes'] as $rid => $on) {
                $rate = floatval($input['prices'][$rid]);
                $rdata[$rid]['credits'] = 0; //since this is currency based account
                $rdata[$rid]['price'] = $rate;
                $rdata[$rid]['total'] = 0;
            }
        }

        return array(
            'total_cost' => $total,
            'rcp_data' => $rdata
        );
    }

    public function calculateTaxAndDiscount($total, $input)
    {
        //get tax is applicable by a plan
        if ($this->billingType == 1) {
            $plobj = Doo::loadModel('ScMccMncPlans', true);
            $plobj->id = $input['planid'];
            $plandata = Doo::db()->find($plobj, array('limit' => 1));
            $plan_tax = $plandata->tax;
            $plan_tax_type = $plandata->tax_type;
        } else {
            if (isset($input['planid']) && $input['planid'] != 0) {
                $sobj = Doo::loadModel('ScSmsPlans', true);
                $sobj->plan_id = $input['planid'];
                $taxdata = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'tax, tax_type'));
                $plan_tax = $taxdata->tax;
                switch ($taxdata->tax_type) {
                    case 'VT':
                        $plan_tax_type = 'VAT';
                        break;
                    case 'ST':
                        $plan_tax_type = 'Service Tax';
                        break;
                    case 'SC':
                        $plan_tax_type = 'Service Charge';
                        break;
                    case 'OT':
                        $plan_tax_type = 'Tax';
                        break;
                    case 'GT':
                        $plan_tax_type = 'GST';
                        break;
                }
            } else {
                //no plan associated
                $plan_tax = 0;
                $plan_tax_type = '';
            }
        }


        if (Doo::conf()->invoice_discount == 'before_taxes') {
            //apply discount on total price
            if (isset($input['discount']) && $input['discount'] != 0) {
                if ($input['discountType'] == 'per') {
                    //percent discount
                    $total_af_dis = $total - ($total * $input['discount'] / 100);
                } else {
                    //flat discount
                    $total_af_dis = $total - $input['discount'];
                }
            } else {
                $total_af_dis = $total;
            }

            $total_af_plntax = $plan_tax == 0 ? $total_af_dis : $total_af_dis + ($total_af_dis * $plan_tax / 100);

            //apply additional tax
            if (isset($input['additionalTax']) && $input['additionalTax'] != 0) {
                $total_af_adtax = $total_af_plntax + ($total_af_plntax * $input['additionalTax'] / 100);
            } else {
                $total_af_adtax = $total_af_plntax;
            }
            $grand_total = $total_af_adtax;
        } else {
            //first calculate tax and apply discount on grand total
            $total_af_plntax = $plan_tax == 0 ? $total : $total + ($total * $plan_tax / 100);

            //apply additional tax
            if (isset($input['additionalTax']) && $input['additionalTax'] != 0) {
                $total_af_adtax = $total_af_plntax + ($total_af_plntax * $input['additionalTax'] / 100);
            } else {
                $total_af_adtax = $total_af_plntax;
            }
            //apply discount
            if (isset($input['discount']) && $input['discount'] != 0) {
                if ($input['discountType'] == 'per') {
                    //percent discount
                    $total_af_dis = $total_af_adtax - ($total_af_adtax * $input['discount'] / 100);
                } else {
                    //flat discount
                    $total_af_adtax = $total_af_adtax - $input['discount'];
                }
            } else {
                $total_af_dis = $total_af_adtax;
            }
            $grand_total = $total_af_dis;
        }

        return array(
            'plan_tax' => $plan_tax == 0 ? 'N/A' : $plan_tax . '% ' . $plan_tax_type,
            'discount' => isset($input['discount']) && $input['discount'] != 0 ? ($input['discountType'] == 'per' ? $input['discount'] . '%' : $input['discount'] . ' ' . Doo::conf()->currency_name) : 'N/A',
            'grand_total' => $grand_total
        );
    }

    public function addRoutesCreditsPrice($userid, $creditdata)
    {
        //do not add this for mccmnc type user
        if ($this->billingType != 1) {
            Doo::loadModel('ScUsersCreditData');
            $cobj = new ScUsersCreditData;
            $cobj->saveCreditData($userid, $creditdata['routedata'], $creditdata['expiry']);
        }
        //for wallet based accounts add credit log with no route and wallet credits
        if ($this->billingType != 0) {
            $this->addCreditLog($userid, array(
                'route' => 0,
                'amount' => $creditdata['walletcredits'],
                'before' => 0,
                'after' => $creditdata['walletcredits'],
                'reference' => 'Signup Credits',
                'comments' => 'Wallet credits added during account creation.'
            ));
        } else {
            //for credit based make log entry for each assigned route
            foreach ($creditdata['routedata'] as $routeid => $info) {
                $this->addCreditLog($userid, array(
                    'route' => $routeid,
                    'amount' => $info['credits'],
                    'before' => 0,
                    'after' => $info['credits'],
                    'reference' => 'Signup Credits',
                    'comments' => 'SMS was added during account creation.'
                ));
            }
        }
    }

    public function addCreditTransaction($mode, $userid, $txndata)
    {
        if ($this->billingType == 0) {
            $trandata['transac_id'] = $txndata['transactionid'];
            $trandata['cdata'] = $txndata['routedata'];
            $trandata['transac_by'] = $txndata['upline'];
            $trandata['transac_to'] = $userid;
            $trandata['invoice_id'] = $txndata['invoice'];

            Doo::loadModel('ScUsersCreditTransactions');
            $tobj = new ScUsersCreditTransactions;
            $tobj->newTransaction($mode, $trandata);
        }
    }

    public function addCreditLog($userid, $logdata)
    {
        $ulcobj = Doo::loadModel('ScLogsCredits', true);
        $ulcobj->user_id = $userid;
        $ulcobj->timestamp = date(Doo::conf()->date_format_db);
        $ulcobj->amount = $logdata['amount'];
        $ulcobj->route_id = $logdata['route'];
        $ulcobj->credits_before = $logdata['before'];
        $ulcobj->credits_after = $logdata['after'];
        $ulcobj->reference = $logdata['reference'];
        $ulcobj->comments = $logdata['comments'];
        Doo::db()->insert($ulcobj);
    }

    public function saveUserPlanAssociation($userid, $plandata)
    {
        if ($this->billingType == 2) return; //no plan support for currency based
        if ($plandata['id'] == 0) return; //no plan assigned
        Doo::loadModel('ScUsersSmsPlans');
        $spobj = new ScUsersSmsPlans;
        if ($this->billingType == 0) {
            $spobj->user_id = $userid;
            $spobj->plan_id = $plandata['id'];
            $spobj->subopt_idn = $plandata['option'];
        }
        if ($this->billingType == 1) {
            $spobj->user_id = $userid;
            $spobj->plan_id = $plandata['id'];
            $spobj->subopt_idn = '';
            $spobj->plan_type = 1;
        }
        Doo::db()->insert($spobj);
    }

    public static function userWalletTransaction($mode, $userid, $walletdata)
    {
        $wlobj = Doo::loadModel('ScUsersWallet', true);
        if ($mode == 'create') {
            $action = 1; // add credit
            $wlobj->wallet_code = $walletdata['code'];
            $wlobj->user_id = $userid;
            $wlobj->amount = $walletdata['amount'];
            $wlobj->expiry_date = $walletdata['expiry'];
            $walletid = Doo::db()->insert($wlobj);
        }

        $wxobj = Doo::loadModel('ScUsersWalletTransactions', true);
        $wxobj->wallet_id = $walletid;
        $wxobj->transac_type = $action;
        $wxobj->amount = $walletdata['amount'];
        $wxobj->t_date = date(Doo::conf()->date_format_db);
        $wxobj->linked_invoice = $walletdata['invoice'];
        Doo::db()->insert($wxobj);
    }

    public function deductResellerCredits($resellerid, $data)
    {
        $creobj = Doo::loadModel('ScUsersCreditData', true);
        $credits_after_deduction = array();
        foreach ($data['routedata'] as $routeid => $info) {
            $newavcredits = $creobj->doCreditTrans('debit', $resellerid, $routeid, $info['credits'], "", 0, true);
            $credits_after_deduction[$routeid] = $newavcredits['new'];
            $this->addCreditLog($resellerid, array(
                'route' => $routeid,
                'amount' => 0 - intval($info['credits']),
                'before' => $newavcredits['old'],
                'after' => $newavcredits['new'],
                'reference' => 'Add User',
                'comments' => 'New account with LOGIN ID:' . $data['new_user'] . ' was added.'
            ));
        }
        return $credits_after_deduction;
    }

    public function getDefaultPermissions($resellerdata, $plandata)
    {
        if ($this->billingType == 1) {
            //mccmnc based account, get permission from plan
            $plobj = Doo::loadModel('ScMccMncPlans', true);
            $plobj->id = $plandata['id'];
            $plandata = Doo::db()->find($plobj, array('limit' => 1, 'select' => 'plan_features'));
            return json_encode(unserialize($plandata->plan_features));
        } else {
            //credit/currency based accounts
            if ($plandata['id'] == 0) {
                //no plan assocciated, get default permissions
                return $resellerdata['category'] == 'admin' ? json_encode(unserialize(Doo::conf()->default_user_permissions)) : json_encode($resellerdata['reseller_permissions']);
            } else {
                //plan is associated, get permissions from db
                $plobj = Doo::loadModel('ScSmsPlanOptions', true);
                $plobj->plan_id = $plandata['id'];
                if ($plandata['plan_option'] != '') {
                    $plobj->subopt_idn = $plandata['plan_option'];
                }
                $feat_data = Doo::db()->find($plobj, array('limit' => 1, 'select' => 'opt_feats'));
                if (!$feat_data) {
                    return $resellerdata['category'] == 'admin' ? json_encode(unserialize(Doo::conf()->default_user_permissions)) : json_encode($resellerdata['reseller_permissions']);
                } else {
                    return json_encode(unserialize($feat_data->opt_feats));
                }
            }
        }
    }
}
