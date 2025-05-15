<?php

/**
 * DooPaymentHelper class file
 * @package doo.helper
 * @author Saurav <saurabh.pandey@cubelabs.in>
 */

/**
 * DooPaymentHelper
 *
 * This acts as a single point for accessing all payment methods and gateways included in the app
 */
class DooPaymentHelper
{
    var $active_pg = array();
    var $paymentProviders = array(
        array(
            'id' => 'paypal',
            'name' => 'Paypal (Global)',
            'link' => 'https://www.paypal.com/',
            'auth' => array(
                'email' => '',
                'clientid' => '',
                'secret' => ''
            ),
            'currencies' => 'AUD,BRL,CAD,CNY,CZK,DKK,EUR,HKD,HUF,ILS,JPY,MYR,MXN,TWD,NZD,NOK,PHP,PLN,GBP,SGD,SEK,CHF,THB,USD'
        ),
        array(
            'id' => 'stripe',
            'name' => 'Stripe (Global)',
            'link' => 'https://stripe.com/',
            'auth' => array(
                'publishable_key' => '',
                'secret_key' => ''
            ),
            'currencies' => 'USD,EUR,GBP,CAD,AUD,JPY,NZD,CHF,SEK,DKK,NOK,SGD,HKD,MXN,INR,BRL,PLN,ILS,MYR,TRY,RUB,TWD,THB,CZK,AED,BGN,CLP,CNY,COP,HRK,HUF,IDR,ISK,KRW,MAD,PEN,PHP,PKR,RON,SAR,VND,ZAR'
        ),
        array(
            'id' => 'paystack',
            'name' => 'Paystack (Africa)',
            'link' => 'https://paystack.com/',
            'auth' => array(
                'secret_key' => ''
            ),
            'currencies' => 'GHS,NGN,ZAR,KES,USD'
        ),
    );

    var $invoiceData = array(
        'id' => 0,
        'grand_total' => 0,
        'user' => array(
            'id' => 0,
            'name' => '',
            'email' => ''
        )
    );

    var $useWallet = false; // set to true if user wallet credits are used
    var $walletId = 0;
    var $walletBalance = 0;

    var $page = 'outer'; // or inner, this variable tracks where the payment request is initiated

    public function __construct($pg = array())
    {
        if (sizeof($pg) != 0) {
            $channel = $pg['channel'];
            $response = array_filter($this->paymentProviders, function ($i) use ($channel) {
                return $i['id'] == $channel;
            });
            $this->active_pg = array_values($response)[0];
            if ($channel == 'paypal') {
                $this->active_pg['auth']['email'] = $pg['email'];
                $this->active_pg['auth']['clientid'] = base64_decode($pg['clientid']);
                $this->active_pg['auth']['secret'] = base64_decode($pg['authkey']);
            }
            if ($channel == 'stripe') {
                $this->active_pg['auth']['publishable_key'] = base64_decode($pg['publishable_key']);
                $this->active_pg['auth']['secret_key'] = base64_decode($pg['secret_key']);
            }
            if ($channel == 'paystack') {
                $this->active_pg['auth']['public_key'] = base64_decode($pg['public_key']);
                $this->active_pg['auth']['secret_key'] = base64_decode($pg['secret_key']);
            }
        }
    }

    public function getAllPaymentGateways()
    {
        return $this->paymentProviders;
    }

    public function setInvoiceData($id, $total, $user)
    {
        $this->invoiceData['id'] = $id;
        $this->invoiceData['grand_total'] = $total;
        $this->invoiceData['user']['id'] = $user->user_id;
        $this->invoiceData['user']['name'] = $user->name;
        $this->invoiceData['user']['email'] = $user->email;
    }

    public function getPaymentGatewayParams()
    {
        if ($this->active_pg['id'] == 'paypal') {
            return $this->getPaypalParams();
        }
        if ($this->active_pg['id'] == 'stripe') {
            return $this->getStripeParams();
        }
        if ($this->active_pg['id'] == 'paystack') {
            return $this->getPaystackParams();
        }
    }

    public function getPaypalParams()
    {
        Doo::loadHelper('DooPaypalCheckout');
        $paypalobj = new DooPaypalCheckout($this->active_pg['auth']['clientid'], $this->active_pg['auth']['secret']);
        return array(
            'channel' => 'paypal',
            'env' => $paypalobj->paypalEnv,
            'clientid' => $paypalobj->paypalClientID
        );
    }

    public function getStripeParams()
    {
        $stripetxn = strtoupper(uniqid());
        $returnUrl = $this->page == 'outer' ? Doo::conf()->APP_URL . 'scPaymentReturn/index' : Doo::conf()->APP_URL . 'scOrderProcess/index';
        return array(
            'channel' => 'stripe',
            'publishable_key' => $this->active_pg['auth']['publishable_key'],
            'secret_key' => $this->active_pg['auth']['secret_key'],
            'stripetxnid' => $stripetxn,
            'product' => 'SMS-CREDITS PURCHASE',
            'returnurl' => $returnUrl
        );
    }

    public function getPaystackParams()
    {
        return array(
            'channel' => 'paystack',
            'public_key' => $this->active_pg['auth']['public_key'],
            'secret_key' => $this->active_pg['auth']['secret_key'],
        );
    }

    public function parsePaymentResponse($data, $sessionVars)
    {
        if ($data['channel'] == 'paypal') {
            return $this->parsePaypalResponse($data, $sessionVars);
        }
        if ($data['channel'] == 'stripe') {
            return $this->parseStripeResponse($data, $sessionVars);
        }
    }

    public function parsePaypalResponse($data, $sessionVars)
    {
        $paymentID = $data['paymentID'];
        $token = $data['token'];
        $payerID = $data['payerID'];
        $inv_id = $data['invid'];

        //get invoice data
        Doo::loadModel('ScUsersDocuments');
        $docobj = new ScUsersDocuments;
        $docobj->id = $inv_id;
        $docdata = Doo::db()->find($docobj, array('limit' => 1));

        //get user infor
        $usrobj = Doo::loadModel('ScUsers', true);
        $uinfo = $usrobj->getProfileInfo($docdata->shared_with);

        //validate
        $usetobj = Doo::loadModel('ScUsersCompany', true);
        $usetobj->user_id = $docdata->owner_id;
        $usetdata = Doo::db()->find($usetobj, array('select' => 'c_payment', 'limit' => 1));
        $userpg = unserialize($usetdata->c_payment);

        $clientid = $userpg['clientid'];
        $secret = $userpg['authkey'];

        Doo::loadHelper('DooPaypalCheckout');
        $paypalobj = new DooPaypalCheckout($clientid, $secret);

        $paymentCheck = $paypalobj->validate($paymentID, $token, $payerID, $inv_id);
        $invdata = unserialize($docdata->file_data);
        //update invoice status
        if ($paymentCheck && $paymentCheck->state == 'approved') {
            // Get the transaction data
            $id = $paymentCheck->id;
            $state = $paymentCheck->state;
            $payerFirstName = $paymentCheck->payer->payer_info->first_name;
            $payerLastName = $paymentCheck->payer->payer_info->last_name;
            $payerName = $payerFirstName . ' ' . $payerLastName;
            $payerEmail = $paymentCheck->payer->payer_info->email;
            $payerID = $paymentCheck->payer->payer_info->payer_id;
            $payerCountryCode = $paymentCheck->payer->payer_info->country_code;
            $paidAmount = $paymentCheck->transactions[0]->amount->details->subtotal;
            $currency = $paymentCheck->transactions[0]->amount->currency;

            $docobj2 = new ScUsersDocuments;
            $docobj2->id = $docdata->id;
            $docobj2->file_status = 1;
            Doo::db()->update($docobj2, array('limit' => 1));

            //check if wallet balance was used
            $wcomment = '';
            if ($this->useWallet == true) {
                //apply wallet discount in total payable
                $total_price = $invdata['grand_total'];
                $dis = $total_price <= floatval($this->walletBalance) ? $total_price : floatval($this->walletBalance);
                $wcomment .= ' Additional amount of ' . Doo::conf()->currency . number_format($dis, 2) . ' was paid from my Wallet.';

                $wobj = Doo::loadModel('ScUsersWallet', true);
                $wobj->id = $this->walletId;
                $wdata = Doo::db()->find($wobj, array('limit' => 1));
                if ($wdata->id) {
                    $wobj->amount = $wdata->amount - floatval($dis);
                    $wobj->id = $wdata->id;
                    Doo::db()->update($wobj, array('limit' => 1));
                }

                //add in wallet transaction history
                $wtobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $wtobj->wallet_id = $wdata->id;
                $wtobj->transac_type = 0; //0 debit, 1 credit
                $wtobj->amount = floatval($dis);
                $wtobj->t_date = date(Doo::conf()->date_format_db);
                $wtobj->linked_invoice = $inv_id;
                Doo::db()->insert($wtobj);
            }

            //add document remark from payer with transaction details
            $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
            $drobj->user_id = $docdata->shared_with; //user id of payer
            $drobj->file_id = $inv_id;
            $drobj->remark_text = '[AUTO-GENERATED] Paypal payment was made for ' . $currency . number_format($paidAmount, 2) . ' with Transaction ID ' . $id . $wcomment;
            Doo::db()->insert($drobj);

            //insert into paypal transaction table
            $ptobj = Doo::loadModel('ScPaypalTransactions', true);
            $ptobj->invoice_id = $inv_id;
            $ptobj->payer_userid = $docdata->shared_with;
            $ptobj->receiver_userid = $docdata->owner_id;
            $ptobj->txn_id = $id;
            $ptobj->payment_gross = $paidAmount;
            $ptobj->currency_code = $currency;
            $ptobj->payer_id = $payerID;
            $ptobj->payer_name = $payerName;
            $ptobj->payer_email = $payerEmail;
            $ptobj->payer_country = $payerCountryCode;
            $ptobj->payment_status = $state;
            Doo::db()->insert($ptobj);

            //check if credits are already assigned to user or not
            //some invoices are performa, meaning credits have not been assigned, some invoices are generated after a credit transaction
            Doo::loadModel('ScUsersCreditTransactions');
            Doo::loadModel('ScUsersCreditData');
            $lcobj2 = Doo::loadModel('ScLogsCredits', true);
            $creobj2 = new ScUsersCreditData;

            $ctcheckobj = new ScUsersCreditTransactions;
            $ctcheckobj->transac_to_user = $docdata->shared_with;
            $ctcheckobj->invoice_id = $inv_id;
            $ctdata = Doo::db()->find($ctcheckobj);

            if (sizeof($ctdata) == 0) {
                //no credit transactions done yet
                //add credits to the user
                foreach ($invdata['routes_credits'] as $rid => $cinfo) {
                    $affcr = $creobj2->doCreditTrans('credit', intval($docdata->shared_with), $rid, $cinfo['credits'], '', $cinfo['price'], true);

                    //credit log
                    $lcobj2->user_id = intval($docdata->shared_with);
                    $lcobj2->timestamp = date(Doo::conf()->date_format_db);
                    $lcobj2->amount = $cinfo['credits'];
                    $lcobj2->route_id = $rid;
                    $lcobj2->credits_before = $affcr['old'];
                    $lcobj2->credits_after = $affcr['new'];
                    $lcobj2->reference = 'Credit Account';
                    $lcobj2->comments = 'Credit transaction was made on your account by SMS purchase order.';
                    Doo::db()->insert($lcobj2);

                    //deduct balance from upline if applicable
                    if ($_SESSION['manager']['category'] != 'admin') {
                        //reseller account deduct balance
                        $lcobj = Doo::loadModel('ScLogsCredits', true);

                        $creobj = new ScUsersCreditData;
                        $affcr2 = $creobj->doCreditTrans('debit', intval($docdata->owner_id), $rid, $cinfo['credits'], '', 0, true);

                        //credit log
                        $lcobj->user_id = $docdata->owner_id;
                        $lcobj->timestamp = date(Doo::conf()->date_format_db);
                        $lcobj->amount = '-' . $cinfo['credits'];
                        $lcobj->route_id = $rid;
                        $lcobj->credits_before = $affcr2['old'];
                        $lcobj->credits_after = $affcr2['new'];
                        $lcobj->reference = 'Credit Transaction deduction';
                        $lcobj->comments = 'Credit transaction was made on a User Account:|| <a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . intval($docdata->shared_with) . '">link</a>';
                        Doo::db()->insert($lcobj);
                    }
                }

                //add in credit transaction table
                $trandata['transac_id'] = $uinfo->login_id . rand(0, 100) . time();
                $trandata['cdata'] = $invdata['routes_credits'];
                $trandata['transac_by'] = $docdata->owner_id;
                $trandata['transac_to'] = $docdata->shared_with;
                $trandata['invoice_id'] = $inv_id;
                $tobj = new ScUsersCreditTransactions;
                $tobj->newTransaction('credit', $trandata);
            }

            //log event
            $actData['activity_type'] = 'PAYPAL PAYMENT';
            $actData['activity'] = Doo::conf()->user_payment_paypal . strtoupper($id);
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($docdata->shared_with, $actData);

            //send notification to upline
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert($uinfo->upline_id, 'info', Doo::conf()->user_payment_paypal . $id, 'viewDocument/' . $inv_id);

            //redirect to login page
            $res['type'] = 'success';
            if ($this->page == 'inner') {
                $res['msg'] = 'Your payment has been processed successfully.';
                $res['return'] = Doo::conf()->APP_URL . 'viewDocument/' . $inv_id;
            } else {
                $res['msg'] = 'Your payment has been processed. Login now and start your campaign.';
                $res['return'] = Doo::conf()->APP_URL . 'web/sign-in';
            }

            return json_encode($res);
        } else {
            //payment not approved redirect to login page

            //log event
            $actData['activity_type'] = 'PAYPAL PAYMENT';
            $actData['activity'] = Doo::conf()->user_payment_paypal_fail . $inv_id;
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($docdata->shared_with, $actData);

            //return
            $res['type'] = 'error';
            $res['msg'] = 'Payment failed. Please login and try the payment again.';
            $res['return'] = $this->page == 'inner' ? Doo::conf()->APP_URL . 'viewDocument/' . $inv_id : Doo::conf()->APP_URL . 'web/sign-in';
            return json_encode($res);
        }
    }

    public function parseStripeResponse($data, $sessionVars)
    {
        $channel = 'stripe';
        $stripedata = json_decode($data)->paymentIntent;
        //get invoice and user information
        $invoiceid = $sessionVars['invoiceid'];
        $invobj = Doo::loadModel('ScUsersDocuments', true);
        $invobj->id = $invoiceid;
        $docdata = Doo::db()->find($invobj, array('limit' => 1));
        $invdata = unserialize($docdata->file_data);

        $usrobj = Doo::loadModel('ScUsers', true);
        $uinfo = $usrobj->getProfileInfo($docdata->shared_with);

        if ($stripedata->status == 'succeeded') {
            //add document remark with payment details
            $invobj->id = $docdata->id;
            $invobj->file_status = 1;
            Doo::db()->update($invobj);

            //check if wallet balance was used
            $wcomment = '';
            if ($this->useWallet == true) {
                //apply wallet discount in total payable
                $total_price = $invdata['grand_total'];
                $dis = $total_price <= floatval($this->walletBalance) ? $total_price : floatval($this->walletBalance);
                $wcomment .= ' Additional amount of ' . Doo::conf()->currency . number_format($dis, 2) . ' was paid from my Wallet.';

                $wobj = Doo::loadModel('ScUsersWallet', true);
                $wobj->id = $this->walletId;
                $wdata = Doo::db()->find($wobj, array('limit' => 1));
                if ($wdata->id) {
                    $wobj->amount = $wdata->amount - floatval($dis);
                    $wobj->id = $wdata->id;
                    Doo::db()->update($wobj, array('limit' => 1));
                }

                //add in wallet transaction history
                $wtobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $wtobj->wallet_id = $wdata->id;
                $wtobj->transac_type = 0; //0 debit, 1 credit
                $wtobj->amount = floatval($dis);
                $wtobj->t_date = date(Doo::conf()->date_format_db);
                $wtobj->linked_invoice = $invoiceid;
                Doo::db()->insert($wtobj);
            }
            //if credits were not added before add them now
            if ($uinfo->account_type != 0) {
                $wlobj = Doo::loadModel('ScUsersWallet', true);
                $wlobj->user_id = $uinfo->user_id;
                $wallet = Doo::db()->find($wlobj, array('limit' => 1));
                $wlobj2 = Doo::loadModel('ScUsersWallet', true);
                $wlobj2->id = $wallet->id;
                $wlobj2->amount = floatval($wallet->amount) + $invdata['wallet_credits'];
                Doo::db()->update($wlobj2);

                //add transaction in wallet txn table
                $wxobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $wxobj->wallet_id = $wallet->id;
                $wxobj->transac_type = 1;
                $wxobj->amount =  $invdata['wallet_credits'];
                $wxobj->t_date = date(Doo::conf()->date_format_db);
                $wxobj->linked_invoice = $invoiceid;
                Doo::db()->insert($wxobj);

                //credit log entry
                Doo::loadModel('ScLogsCredits');
                $ulcobj = new ScLogsCredits;
                $ulcobj->user_id = $uinfo->user_id;
                $ulcobj->timestamp = date(Doo::conf()->date_format_db);
                $ulcobj->amount = $invdata['wallet_credits'];
                $ulcobj->route_id = 0;
                $ulcobj->credits_before = $wallet->amount;
                $ulcobj->credits_after = floatval($wallet->amount) + $invdata['wallet_credits'];
                $ulcobj->reference = 'WALLET CREDIT';
                $ulcobj->comments = Doo::conf()->reseller_make_transaction . '|| TYPE: CREDIT, INVOICE-ID: ' . $invoiceid;
                Doo::db()->insert($ulcobj);

                //sale stats
                $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
                $stobj->addStat(date('Y-m-d'), $invdata['total_cost']);

                //create alert for user
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert($uinfo->user_id, 'success', Doo::conf()->account_credit_alert, 'transactionReports');
            }

            //add a remark with transaction detail
            $remark = '[AUTO-GENERATED] Payment was received ' . '<br>Amount: ' . Doo::conf()->currency . sprintf('%.2f', $stripedata->amount / 100) . '<br>Payment Date: ' . date(Doo::conf()->date_format_med_time_s) . '<br>' . $wcomment;
            $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
            $drobj->user_id = $uinfo->user_id;
            $drobj->file_id = $invoiceid;
            $drobj->remark_text = $remark;
            Doo::db()->insert($drobj);


            //redirect to login page with message
            $res['mode'] = 'xhr';
            $res['response'] = 'success';
            $res['message']['status'] = 'Valid';
            return json_encode($res);
        } else {
            //failed transaction
            $res['mode'] = 'xhr';
            $res['response'] = 'success';
            $res['message']['status'] = 'Invalid';
            $res['message']['errorMessage'] = $stripedata->status;
            return json_encode($res);
        }
    }

    public function parsePaystackResponse($data, $sessionVars)
    {
        //get invoice information
        $invobj = Doo::loadModel('ScUsersDocuments', true);
        $invobj->id = $sessionVars['invoice_id'];
        $docdata = Doo::db()->find($invobj, array('limit' => 1));
        $invdata = unserialize($docdata->file_data);
        //get associated user (payer) details
        $usrobj = Doo::loadModel('ScUsers', true);
        $uinfo = $usrobj->getProfileInfo($docdata->shared_with);

        if ($data->data->status == "success") {
            //payment succeeded
            $invobj->id = $docdata->id;
            $invobj->file_status = 1;
            Doo::db()->update($invobj);

            //check if wallet balance was used
            $wcomment = '';
            if ($sessionVars['wallet_flag'] == 1) {
                //apply wallet discount in total payable
                $total_price = $invdata['grand_total'];
                $dis = $total_price <= floatval($this->walletBalance) ? $total_price : floatval($this->walletBalance);
                $wcomment .= ' Additional amount of ' . Doo::conf()->currency . number_format($dis, 2) . ' was paid from my Wallet.';

                $wobj = Doo::loadModel('ScUsersWallet', true);
                $wobj->id = $this->walletId;
                $wdata = Doo::db()->find($wobj, array('limit' => 1));
                if ($wdata->id) {
                    $wobj->amount = $wdata->amount - floatval($dis);
                    $wobj->id = $wdata->id;
                    Doo::db()->update($wobj, array('limit' => 1));
                }

                //add in wallet transaction history
                $wtobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $wtobj->wallet_id = $wdata->id;
                $wtobj->transac_type = 0; //0 debit, 1 credit
                $wtobj->amount = floatval($dis);
                $wtobj->t_date = date(Doo::conf()->date_format_db);
                $wtobj->linked_invoice = $sessionVars['invoice_id'];
                Doo::db()->insert($wtobj);
            }

            //if credits not added, add them
            //currency based, add in wallet
            if ($uinfo->account_type != 0) {
                $wlobj = Doo::loadModel('ScUsersWallet', true);
                $wlobj->user_id = $uinfo->user_id;
                $wallet = Doo::db()->find($wlobj, array('limit' => 1));
                $wlobj2 = Doo::loadModel('ScUsersWallet', true);
                $wlobj2->id = $wallet->id;
                $wlobj2->amount = floatval($wallet->amount) + $invdata['wallet_credits'];
                Doo::db()->update($wlobj2);

                //add transaction in wallet txn table
                $wxobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $wxobj->wallet_id = $wallet->id;
                $wxobj->transac_type = 1;
                $wxobj->amount =  $invdata['wallet_credits'];
                $wxobj->t_date = date(Doo::conf()->date_format_db);
                $wxobj->linked_invoice = $sessionVars['invoice_id'];
                Doo::db()->insert($wxobj);

                //credit log entry
                Doo::loadModel('ScLogsCredits');
                $ulcobj = new ScLogsCredits;
                $ulcobj->user_id = $uinfo->user_id;
                $ulcobj->timestamp = date(Doo::conf()->date_format_db);
                $ulcobj->amount = $invdata['wallet_credits'];
                $ulcobj->route_id = 0;
                $ulcobj->credits_before = $wallet->amount;
                $ulcobj->credits_after = floatval($wallet->amount) + $invdata['wallet_credits'];
                $ulcobj->reference = 'WALLET CREDIT';
                $ulcobj->comments = Doo::conf()->reseller_make_transaction . '|| TYPE: CREDIT, INVOICE-ID: ' . $sessionVars['invoice_id'];
                Doo::db()->insert($ulcobj);

                //sale stats
                $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
                $stobj->addStat(date('Y-m-d'), $invdata['total_cost']);

                //create alert for user
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert($uinfo->user_id, 'success', Doo::conf()->account_credit_alert, 'transactionReports');
            } else {
                //credits based add in credit data
                Doo::loadModel('ScUsersCreditTransactions');
                Doo::loadModel('ScUsersCreditData');
                $lcobj2 = Doo::loadModel('ScLogsCredits', true);
                $creobj2 = new ScUsersCreditData;

                $ctcheckobj = new ScUsersCreditTransactions;
                $ctcheckobj->transac_to_user = $docdata->shared_with;
                $ctcheckobj->invoice_id = $sessionVars['invoice_id'];
                $ctdata = Doo::db()->find($ctcheckobj);

                if (sizeof($ctdata) == 0) {
                    //no credit transactions done yet
                    //add credits to the user
                    foreach ($invdata['routes_credits'] as $rid => $cinfo) {
                        $affcr = $creobj2->doCreditTrans('credit', intval($docdata->shared_with), $rid, $cinfo['credits'], '', $cinfo['price'], true);

                        //credit log
                        $lcobj2->user_id = intval($docdata->shared_with);
                        $lcobj2->timestamp = date(Doo::conf()->date_format_db);
                        $lcobj2->amount = $cinfo['credits'];
                        $lcobj2->route_id = $rid;
                        $lcobj2->credits_before = $affcr['old'];
                        $lcobj2->credits_after = $affcr['new'];
                        $lcobj2->reference = 'Credit Account';
                        $lcobj2->comments = 'Credit transaction was made on your account by SMS purchase order.';
                        Doo::db()->insert($lcobj2);

                        //deduct balance from upline if applicable
                        if ($_SESSION['manager']['category'] != 'admin') {
                            //reseller account deduct balance
                            $lcobj = Doo::loadModel('ScLogsCredits', true);

                            $creobj = new ScUsersCreditData;
                            $affcr2 = $creobj->doCreditTrans('debit', intval($docdata->owner_id), $rid, $cinfo['credits'], '', 0, true);

                            //credit log
                            $lcobj->user_id = $docdata->owner_id;
                            $lcobj->timestamp = date(Doo::conf()->date_format_db);
                            $lcobj->amount = '-' . $cinfo['credits'];
                            $lcobj->route_id = $rid;
                            $lcobj->credits_before = $affcr2['old'];
                            $lcobj->credits_after = $affcr2['new'];
                            $lcobj->reference = 'Credit Transaction deduction';
                            $lcobj->comments = 'Credit transaction was made on a User Account:|| <a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . intval($docdata->shared_with) . '">link</a>';
                            Doo::db()->insert($lcobj);
                        }
                    }

                    //add in credit transaction table
                    $trandata['transac_id'] = $uinfo->login_id . rand(0, 100) . time();
                    $trandata['cdata'] = $invdata['routes_credits'];
                    $trandata['transac_by'] = $docdata->owner_id;
                    $trandata['transac_to'] = $docdata->shared_with;
                    $trandata['invoice_id'] = $sessionVars['invoice_id'];
                    $tobj = new ScUsersCreditTransactions;
                    $tobj->newTransaction('credit', $trandata);
                }
            }

            //add a remark with transaction detail
            $remark = '[AUTO-GENERATED] Payment was received ' . '<br>Amount: ' . Doo::conf()->currency . sprintf('%.2f', $data->data->amount / 100) . '<br>Payment Date: ' . date(Doo::conf()->date_format_med_time_s) . '<br>' . $wcomment;
            $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
            $drobj->user_id = $uinfo->user_id;
            $drobj->file_id = $sessionVars['invoice_id'];
            $drobj->remark_text = $remark;
            Doo::db()->insert($drobj);

            //return
            $res['type'] = 'success';
            $res['msg'] = 'Your payment has been completed successfully.';
            $res['return'] = Doo::conf()->APP_URL . 'viewDocument/' . $sessionVars['invoice_id'];
            return json_encode($res);
        } else {
            //payment failed
            $res['type'] = 'error';
            $res['msg'] = 'Payment failed. Gateway error: ' . $data->data->gateway_response;
            $res['return'] = Doo::conf()->APP_URL . 'viewDocument/' . $sessionVars['invoice_id'];
            return json_encode($res);
        }
    }
}
