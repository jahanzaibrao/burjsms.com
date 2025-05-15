<?php

/**
 * AuthController
 *
 * @author saurav
 *
 * //1. Auth User
 * //2. Logout
 */

use Google\Cloud\Translate\V2\TranslateClient;

class AuthController extends DooController
{


    //1. Auth User: User authentication and loading/logging data

    public function authUser()
    {
        session_start();
        $url = Doo::conf()->APP_URL;
        $url = preg_replace("(^https?://)", "", $url); //remove protocol from domain
        $url = str_replace('/app/', '', $url);
        $url = rtrim($url, "/");

        //1. check if session contains post data, if so populate the post data
        if (isset($_SESSION['postvars'])) {
            $_POST = array_merge($_POST, json_decode(base64_decode($_SESSION['postvars']), true));
        }

        $gsigninFlag = isset($_POST['g_csrf_token']) && isset($_POST['client_id']) && $_POST['credential'] != "" ? 1 : 0;

        //2. check if empty fields
        if ($gsigninFlag == 0 && ($_POST['loginid'] == "" || $_POST['upassword'] == "")) {
            //set login attempt
            $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Please enter login credentials';
            return Doo::conf()->APP_URL . 'web/sign-in';
        }

        //3. check TnC, throw out if no
        if ($gsigninFlag == 0 && Doo::conf()->smd_tnc_flag == 1) {
            //tnc flag check if not google signup and flag is set 
            if ($_POST['tncflag'] != 1) {
                //set login attempt
                $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Please agree to our terms';
                return Doo::conf()->APP_URL . 'web/sign-in';
            }
        }

        //4. check captcha, throw out if no 
        if ($gsigninFlag == 0) {
            //throw out
            if (Doo::conf()->disable_recaptcha == "0") {
                if (!isset($_POST['g-recaptcha-response']) || $_POST['g-recaptcha-response'] == "") {
                    //set login attempt
                    $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Please complete the CAPTCHA';
                    return Doo::conf()->APP_URL . 'web/sign-in';
                } else {
                    //validate
                    if ($_SESSION['captcha_verified'] != 1) {
                        //curl attemp instead of file get contents
                        $capverUrl = 'https://www.google.com/recaptcha/api/siteverify';
                        $postdata = http_build_query(array(
                            'secret' => Doo::conf()->recaptcha_secret,
                            'response' => $_POST['g-recaptcha-response']
                        ));
                        $curl = curl_init($capverUrl);
                        curl_setopt($curl, CURLOPT_HEADER, false);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt(
                            $curl,
                            CURLOPT_HTTPHEADER,
                            array("Content-Type: application/x-www-form-urlencoded")
                        );
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

                        $json_response = curl_exec($curl);

                        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);
                        $capdata = json_decode($json_response, true);

                        if (Doo::conf()->disable_captcha != 1 && (!$capdata || $capdata["success"] == false)) {
                            //set login attempt
                            $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
                            //throw out
                            $_SESSION['notif_msg']['type'] = 'error';
                            $_SESSION['notif_msg']['msg'] = 'CAPTCHA Verification failed';
                            return Doo::conf()->APP_URL . 'web/sign-in';
                        }
                        $_SESSION['captcha_verified'] = 1; //so if resend otp or any other method calls this again we dont reveify captcha
                    }
                }
            }
        }

        //5. fetch user
        if ($gsigninFlag == 1) {
            //login if google signup
            $client = new Google_Client(['client_id' => Doo::conf()->gcp_client_id]);
            $payload = $client->verifyIdToken($_POST['credential']);
            if (!$payload) {
                // Invalid credential
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Google authentication Failed. Try again.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            }
            $userId = $payload['sub'];
            $userEmail = $payload['email'];
            //check if there is any user with this email
            $echeckqry = "SELECT user_id FROM sc_users WHERE email ='$userEmail' LIMIT 1";
            $res = Doo::db()->fetchAll($echeckqry);
            if (!$res || !is_array($res) || intval($res[0]) == 0) {
                //set login attempt
                $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Your Email is not registered with us. Please Sign-Up for a New Account.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            }
            $userObj = Doo::loadModel('ScUsers', true);
            $userObj->user_id = $res[0]['user_id'];
            $userObj->status = 1;
            $user = Doo::db()->find($userObj, array('limit' => 1));
        } else {
            //check if password matches, throw out if no
            $userObj = Doo::loadModel('ScUsers', true);
            $userObj->login_id = $_POST['loginid'];
            $userObj->status = 1;
            $user = Doo::db()->find($userObj, array('limit' => 1));
            if (!$user->user_id) {
                //set login attempt
                $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid Login Details.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            }
            Doo::loadHelper('DooEncrypt');
            $hfunck = base64_encode($user->login_id . '_' . base64_encode('smppcubehash'));
            $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
            $dbpass = $encobj->decrypt($user->password, $hfunck);
            if ($dbpass != $_POST['upassword']) {
                //failed login attempt
                $this->recordAuthActivity(array(
                    'user_id' => $user->user_id,
                    'activity' => 'Failed Login attempt. Credentials entered:|| LOGIN:' . $_POST['loginid'] . ' PASSWORD:' . $_POST['upassword'],
                    'flag' => 1
                ));

                //set login attempt
                $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;

                //throw out
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid Login Details.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            }
        }

        //6. throw out if non admin and maintenance mode
        $obj = Doo::loadModel('ScMiscVars', true);
        $obj->var_name = 'MAINTENANCE_MODE_DATA';
        $mminfo = Doo::db()->find($obj, array('limit' => 1, 'select' => 'var_value,var_status'));
        if ($mminfo->var_status == 1 && $user->category != 'admin') {
            //redirect out
            unset($_SESSION['webfront']);
            $_SESSION['notif_msg']['type'] = 'info';
            $_SESSION['notif_msg']['msg'] = 'App is in Maintenance mode. Only System Administrators are allowed to login.';
            return Doo::conf()->APP_URL . 'web/sign-in';
        }

        //7. block IP if failed login attempt exceeds 5
        if (intval($_SESSION['failed_login_attempt']) > 5) {
            unset($_SESSION['failed_login_attempt']);
            $this->recordAuthActivity(array(
                'user_id' => 0,
                'activity' => 'Multiple Failed Login attempts. Last credentials entered:|| LOGIN:' . $_POST['loginid'] . ' PASSWORD:' . $_POST['upassword'],
                'flag' => 3,
                'block_ip' => true
            ));

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->new_blocked_ip_alert, 'manageBlockedIpList');

            //return
            return Doo::conf()->APP_URL . 'web/sign-in';
        }

        //8. check if allowed IP, throw out if not
        $ipbobj = Doo::loadModel('ScBlockedIpList', true);
        $ipbobj->ip_address = $_SERVER['REMOTE_ADDR'];
        $iprs = Doo::db()->find($ipbobj, array('limit' => 1));
        if ($iprs) {
            //block access
            return array('/denied', 'internal');
        }

        //9. check if allowed domain, throw out if not, if yes set proper color theme and site data
        if (Doo::conf()->restrict_domain_login == 1) {
            //check if user is logging in from a valid domain
            if ($user->category != 'admin' && $user->upline_id != $_SESSION['webfront']['owner']) {
                //login not allowed
                $this->recordAuthActivity(array(
                    'user_id' => $user->user_id,
                    'activity' => 'Login attempt from different website. Credentials entered:|| LOGIN:' . $_POST['loginid'] . ' PASSWORD:' . $_POST['upassword'],
                    'flag' => 2
                ));

                //set login attempt
                $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;

                //throw out
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid Login Details.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            } else {
                //restrcited login but the user logged in from valid domain
                //if reseller then load resellers own theme if set
                if ($user->category == 'reseller') {
                    Doo::loadModel('ScWebsites');
                    $webobj = new ScWebsites;
                    $wdata = $webobj->getWebsiteData($user->user_id, 'owner');

                    //set theme color only of settings are saved by reseller
                    if ($wdata->id) {
                        //write in session
                        $cdata = unserialize($wdata->site_data);
                        unset($_SESSION['webfront']);
                        $_SESSION['webfront']['id'] = $wdata->id;
                        $_SESSION['webfront']['owner'] = $wdata->user_id;
                        $_SESSION['webfront']['company_name'] = $cdata['company_name'];
                        $_SESSION['webfront']['current_domain'] = $url;
                        $_SESSION['webfront']['all_domains'] = $wdata->domains;
                        $_SESSION['webfront']['logo'] = $wdata->logo;
                        $_SESSION['webfront']['company_data'] = $wdata->site_data;
                        $_SESSION['webfront']['front_type'] = $wdata->front_type;
                        $_SESSION['webfront']['skin_data'] = $wdata->skin_data;
                        $_SESSION['webfront']['status'] = $wdata->status;
                        $_SESSION['webfront']['intheme'] = $cdata['theme'];
                    } //end of check if settings saved
                } //end of check if reseller
            } //end of check if user is logging in from valid domain

        } else {
            //valid users can login from any domain
            if ($user->category != 'admin' && $user->upline_id != $_SESSION['webfront']['owner']) {
                //load users upline website data in session
                //load resellers setting if available
                if ($user->category == 'reseller') {
                    Doo::loadModel('ScWebsites');
                    $webobj = new ScWebsites;
                    $wdata = $webobj->getWebsiteData($user->user_id, 'owner');
                    //write in session
                    $cdata = unserialize($wdata->site_data);
                    unset($_SESSION['webfront']);
                    $_SESSION['webfront']['id'] = $wdata->id;
                    $_SESSION['webfront']['owner'] = $wdata->user_id;
                    $_SESSION['webfront']['company_name'] = $cdata['company_name'];
                    $_SESSION['webfront']['current_domain'] = $url;
                    $_SESSION['webfront']['all_domains'] = $wdata->domains;
                    $_SESSION['webfront']['logo'] = $wdata->logo;
                    $_SESSION['webfront']['company_data'] = $wdata->site_data;
                    $_SESSION['webfront']['front_type'] = $wdata->front_type;
                    $_SESSION['webfront']['skin_data'] = $wdata->skin_data;
                    $_SESSION['webfront']['status'] = $wdata->status;
                    $_SESSION['webfront']['intheme'] = $cdata['theme'];
                } else {
                    //client account hence load upline theme and web settings
                    //get website settings from owner
                    //check if staff is the upline
                    $staffchkobj = Doo::loadModel('ScUsers', true);
                    $upline_data = $staffchkobj->getProfileInfo($user->upline_id);
                    Doo::loadModel('ScWebsites');
                    $webobj = new ScWebsites;
                    if ($upline_data->subgroup == 'staff') {
                        $wdata = $webobj->getWebsiteData($upline_data->upline_id, 'owner');
                    } else {
                        $wdata = $webobj->getWebsiteData($user->upline_id, 'owner');
                    }

                    if (!$wdata->id) {
                        //upline website is not active
                        //alert admin
                        $alobj = Doo::loadModel('ScUserNotifications', true);
                        $alobj->addAlert(1, 'warning', Doo::conf()->website_inactive_reseller, 'viewUserAccount/' . $user->upline_id);
                        //return
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = 'Currently logins are not allowed. Contact Admin.';
                        return Doo::conf()->APP_URL . 'web/sign-in';
                    }

                    //write in session
                    $cdata = unserialize($wdata->site_data);
                    unset($_SESSION['webfront']);
                    $_SESSION['webfront']['id'] = $wdata->id;
                    $_SESSION['webfront']['owner'] = $wdata->user_id;
                    $_SESSION['webfront']['company_name'] = $cdata['company_name'];
                    $_SESSION['webfront']['current_domain'] = $url;
                    $_SESSION['webfront']['all_domains'] = $wdata->domains;
                    $_SESSION['webfront']['logo'] = $wdata->logo;
                    $_SESSION['webfront']['company_data'] = $wdata->site_data;
                    $_SESSION['webfront']['front_type'] = $wdata->front_type;
                    $_SESSION['webfront']['skin_data'] = $wdata->skin_data;
                    $_SESSION['webfront']['status'] = $wdata->status;
                    $_SESSION['webfront']['intheme'] = $cdata['theme'];
                }
            }
        }

        //10. check for 2FA with resend option
        if (Doo::conf()->tfa_auth_mode > 0) {
            Doo::loadHelper('DooSmppcubeHelper');
            $sendotpFlag = 0;
            $verifyotpFlag = 0;
            //validate if 2FA, allow to cancel

            if (intval($_SESSION['otpresend_attemps']) > 3) {
                //max attempt exceeded
                echo 'MAX_ATTEMPTS_REACHED';
                exit; // since resends are ajax req
            }

            if (isset($_POST['mfaotp'])) {
                //otp was received, try to verify it
                $verifyotpFlag = 1;
            }
            if (!isset($_POST['mfaotp'])) {
                //this is the first we're getting here, no otp was send so send now
                $sendotpFlag = 1;
            }

            if ($verifyotpFlag == 1) {
                //check if supplied OTP matches the session OTP
                if ($_SESSION['login_email_otp'] == implode("", $_POST['mfaotp'])) {
                    //mark email as verified
                    $uobj = Doo::loadModel('ScUsers', true);
                    $uobj->email_verified = 1;
                    Doo::db()->update($uobj, array('where' => "email='" . $_SESSION['email_tbv'] . "'"));
                    //if matches proceed as usual by setting post vars again from session
                    $_POST = json_decode(base64_decode($_SESSION['postvars']), true);
                    $_POST['otpresend'] = 0;
                    unset($_SESSION['postvars']);
                    unset($_SESSION['otpresend']);
                    unset($_SESSION['otpresend_attemps']);
                    unset($_SESSION['email_tbv']);
                    unset($_SESSION['login_email_otp']);
                } else {
                    //send on the page again
                    Doo::loadModel('ScWebsitesPageData');
                    $pgobj = new ScWebsitesPageData;
                    $pgobj->site_id = $_SESSION['webfront']['id'];
                    $pgobj->user_id = $_SESSION['webfront']['owner'];
                    $pgobj->page_type = 'LOGIN';
                    $data['notif_msg']['type'] = 'error';
                    $data['notif_msg']['msg'] = 'Incorrect OTP. Please try again';
                    $sendotpFlag = 0;
                    $verifyotpFlag = 0;
                    //set login attempt
                    $_SESSION['failed_login_attempt'] = intval($_SESSION['failed_login_attempt']) > 0 ? ($_SESSION['failed_login_attempt'] + 1) : 1;
                    unset($_SESSION['otpresend_attemps']);
                    $_POST['otpresend'] = 0;
                    $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));
                    $this->view()->renderc('outer/verifyMfaOtp', $data);
                    exit;
                }
            }

            if ($sendotpFlag == 1 || intval($_POST['otpresend']) > 0) {
                //send OTP to email
                if (intval($_POST['otpresend']) > 0) {
                    //resend already generated otp
                    $otp = $_SESSION['login_email_otp'];
                    $_SESSION['otpresend_attemps']++;
                } else {
                    //send fresh otp
                    $otp = rand(100000, 999999);
                    $_SESSION['login_email_otp'] = $otp;
                    $_SESSION['otpresend_attemps'] = 0; // 0 because no resend attempts are made yet
                }
                $_SESSION['email_tbv'] = $user->email;

                //send email using hypernode
                Doo::loadHelper('DooOsInfo');
                $browser = DooOsInfo::getBrowser();
                $osdata['system'] = $browser['platform'];
                $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
                $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
                $osdata['city'] = $browser['city'];
                $osdata['country'] = $browser['country'];
                $osdata['lat'] = $browser['lat'];
                $osdata['lon'] = $browser['lon'];
                $userdata = array(
                    "mode" => "email_verify_otp",
                    "data" => array(
                        "user_id" => $user->user_id,
                        "platform_data" => $osdata,
                        "incidentDateTime" => date(Doo::conf()->date_format_db),
                        "otpCode" => $otp,
                        "actionType" => "Two-factor Authentication (2FA)",
                        "expirationTime" => 5
                    )
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, Doo::conf()->APP_URL . 'hypernode/log/add');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userdata));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json; charset=UTF-8"
                ));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Ignore SSL host verification
                $res = curl_exec($ch);
                //print_r($res);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                //store all posted data into session
                $_SESSION['postvars'] = base64_encode(json_encode($_POST));

                if (Doo::conf()->tfa_auth_mode == 2) {
                    //use upline creds for sending sms because this task is system related.
                    Doo::loadModel('ScWebsitesSignupSettings');
                    $stobj = new ScWebsitesSignupSettings;
                    $stobj->user_id = $user->upline_id == 0 ? $user->user_id : $user->upline_id;
                    $res = Doo::db()->find($stobj, array('limit' => 1, 'select' => 'notif_data'));
                    $sendsms_opts = unserialize($res->notif_data);

                    $mobile = $user->mobile;
                    $arrContextOptions = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n",
                            'method'  => 'GET'
                        ),
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        )
                    );
                    $cmpobj = Doo::loadModel('ScUsersCampaigns', true);
                    $akobj = Doo::loadModel('ScApiKeys', true);
                    $api_key = $akobj->getApiKey($user->upline_id == 0 ? $user->user_id : $user->upline_id); //sender user id
                    $smstext = $this->SCTEXT('Your One Time Password for your login verification is:') . ' ' . $otp;
                    $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . intval($cmpobj->getCampaignId($user->upline_id == 0 ? $user->user_id : $user->upline_id, 'system')) . '&routeid=' . $sendsms_opts['sms_route'] . '&type=text&contacts=' . $mobile . '&senderid=' . $sendsms_opts['sms_sid'] . '&msg=' . urlencode($smstext);
                    $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
                }

                //show the page and make sure it posts to itself
                $pgobj = Doo::loadModel('ScWebsitesPageData', true);
                $pgobj->site_id = $_SESSION['webfront']['id'];
                $pgobj->user_id = $_SESSION['webfront']['owner'];
                $pgobj->page_type = 'LOGIN';
                $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));
                //behavior depending if ajax or not
                if (intval($_POST['otpresend']) > 0) {
                    echo 'DONE';
                } else {
                    $this->view()->renderc('outer/verifyMfaOtp', $data);
                }
                exit;
            }
        }

        //11. if all well, proceed and create session
        unset($_SESSION['failed_login_attempt']);
        unset($_SESSION['user']);
        $_SESSION['user'] = array(
            'userid' => $user->user_id,
            'loginid' => $user->login_id,
            'group' => $user->category,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'subgroup' => $user->subgroup,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'optin' => $user->optin_only,
            'upline' => $user->upline_id,
            'account_mgr' => $user->acc_mgr_id,
            'account_type' => $user->account_type,
        );
        //12. get the app language after login in case logged in user has set different langiage than website of reseller
        $langobj = Doo::loadModel('ScUsersSettings', true);
        $langobj->user_id = $user->user_id;
        $app_lang = Doo::db()->find($langobj, array('limit' => 1, 'select' => 'def_lang'));
        $_SESSION['APP_LANG'] = $app_lang->def_lang == false ? Doo::conf()->default_lang : $app_lang->def_lang;

        //13. get account manager details
        $mobj = Doo::loadModel('ScUsers', true);
        $mgr = $mobj->getProfileInfo($user->acc_mgr_id == 0 ? $user->user_id : $user->acc_mgr_id, 'name, category, avatar, email, mobile');
        unset($_SESSION['manager']);
        $_SESSION['manager']['id'] = $user->acc_mgr_id == 0 ? $user->user_id : $user->acc_mgr_id;
        $_SESSION['manager']['name'] = $mgr->name;
        $_SESSION['manager']['email'] = $mgr->email;
        $_SESSION['manager']['category'] = $mgr->category;
        $_SESSION['manager']['mobile'] = $mgr->mobile;
        $_SESSION['manager']['avatar'] = $mgr->avatar;

        //14. record login activity and notify admin and staff if login from new platform
        $login_alert = false;
        if ($user->category == 'admin' && (Doo::conf()->admin_login_alert == 1 || Doo::conf()->admin_login_alert == 2)) {
            $login_alert = true;
        }
        $osdata = $this->recordAuthActivity(array(
            'user_id' => $user->user_id,
            'activity' => 'User logged in the app. Details are:|| NAME: ' . $user->name . '  CATEGORY:' . $_SESSION['user']['subgroup'],
            'flag' => 0,
            'login_alert' => array(
                'flag' => $login_alert,
                'cdata' => unserialize($_SESSION['webfront']['company_data']),
                'udata' => $user
            )
        ));
        $user->doLoginStat($user->user_id);

        //15. get user wallet and credit information
        unset($_SESSION['credits']);
        if ($user->category != 'admin') {
            $credit_data = array();
            //load wallet credits
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $wlobj->user_id = $user->user_id;
            $wldata = Doo::db()->find($wlobj, array('limit' => 1));
            if (!$wldata->id) {
                //no wallet exist
                $credit_data['wallet']['id'] = '0';
                $credit_data['wallet']['code'] = '0';
                $credit_data['wallet']['amount'] = '0';
            } else {
                $credit_data['wallet']['id'] = $wldata->id;
                $credit_data['wallet']['code'] = $wldata->wallet_code;
                $credit_data['wallet']['amount'] = $wldata->amount;
            }
            //only for credit/currency based accounts
            if ($user->account_type == '0' || $user->account_type == '2') {
                //load data only for reseller and clients, admin and staff have unlimited credits
                $robj = Doo::loadModel('ScSmsRoutes', true);
                $creobj = Doo::loadModel('ScUsersCreditData', true);
                $creobj->user_id = $user->user_id;
                $creobj->status = 0;
                $cdata = Doo::db()->find($creobj, array('select' => 'route_id, credits, price, validity, delv_per'));
                $credit_data['routes'] = array();
                foreach ($cdata as $cre) {
                    $rdata = $robj->getRouteData($cre->route_id, 'title,credit_rule,country_id,active_time,template_flag,max_sid_len,def_sender,sender_type,tlv_ids,optout_config');
                    //expire-check (later TBD)
                    if (strtotime($cre->validity) < time()) {
                    }
                    $crear = array();
                    $crear['id'] = $cre->route_id;
                    $crear['name'] = $rdata->title;
                    $crear['credits'] = $cre->credits;
                    $crear['price'] = $cre->price;
                    $crear['validity'] = $cre->validity;
                    $crear['delv_per'] = $cre->delv_per;
                    $crear['senderType'] = $rdata->sender_type;
                    $crear['maxSender'] = $rdata->max_sid_len;
                    $crear['defaultSender'] = $rdata->def_sender;
                    $crear['templateFlag'] = $rdata->template_flag;
                    $crear['activeTime'] = $rdata->active_time;
                    $crear['coverage'] = $rdata->country_id;
                    $crear['creditRule'] = $rdata->credit_rule;
                    $crear['tlv_ids'] = $rdata->tlv_ids;
                    $crear['optout_config'] = $rdata->optout_config;

                    $credit_data['routes'][$cre->route_id] = $crear;
                    $plan_id = 0;
                }
            } else {
                //load mcc mnc assigned plan
                $uplobj = Doo::loadModel('ScUsersSmsPlans', true);
                $uplobj->user_id = $user->user_id;
                $uplobj->plan_type = 1;
                $userplan = Doo::db()->find($uplobj, array('limit' => 1, 'select' => 'plan_id, subopt_idn'));
                $plan_id = $userplan->plan_id;

                $plobj = Doo::loadModel('ScMccMncPlans', true);
                $plobj->id = $plan_id;
                $plandata = Doo::db()->find($plobj, array('limit' => 1));

                $routeid = $plandata->route_id;
                $robj = Doo::loadModel('ScSmsRoutes', true);
                $rdata = $robj->getRouteData($routeid, 'title,credit_rule,country_id,active_time,template_flag,max_sid_len,def_sender,sender_type,optout_config');
                $crear = array();
                $crear['id'] = $routeid;
                $crear['name'] = $rdata->title;
                $crear['credits'] = 0;
                $crear['price'] = 0;
                $crear['validity'] = '';
                $crear['delv_per'] = 100;
                $crear['senderType'] = $rdata->sender_type;
                $crear['maxSender'] = $rdata->max_sid_len;
                $crear['defaultSender'] = $rdata->def_sender;
                $crear['templateFlag'] = $rdata->template_flag;
                $crear['activeTime'] = $rdata->active_time;
                $crear['coverage'] = $rdata->country_id;
                $crear['creditRule'] = $rdata->credit_rule;
                $crear['optout_config'] = $rdata->optout_config;

                $credit_data['routes'][$routeid] = $crear;

                //save in session
                $_SESSION['plan'] = array();
                $_SESSION['plan']['id'] = $plan_id;
                $_SESSION['plan']['name'] = $plandata->plan_name;
                $_SESSION['plan']['routes'] = $plandata->route_ids;
                $_SESSION['plan']['tax'] = $plandata->tax;
                $_SESSION['plan']['tax_type'] = $plandata->tax_type;
                $_SESSION['plan']['delivery'] = $userplan->subopt_idn;
                $_SESSION['plan']['routesniso'] = $plandata->route_coverage;
                $_SESSION['plan']['override_rule'] = $plandata->override_rule;
            }
            $_SESSION['credits'] = $credit_data;
        } else {

            //--- load routes in session for admin ---//
            $credit_data = array();
            //load wallet credits
            //no wallet exist
            $credit_data['wallet']['id'] = '0';
            $credit_data['wallet']['code'] = '0';
            $credit_data['wallet']['amount'] = '0';

            //load data only for reseller and clients, admin and staff have unlimited credits
            $robj = Doo::loadModel('ScSmsRoutes', true);
            $rdata = Doo::db()->find($robj);

            $credit_data['routes'] = array();
            foreach ($rdata as $rt) {
                $crear = array();
                $crear['id'] = $rt->id;
                $crear['name'] = $rt->title;
                $crear['credits'] = 999999999;
                $crear['price'] = 0.001;
                $crear['senderType'] = $rt->sender_type;
                $crear['maxSender'] = $rt->max_sid_len;
                $crear['defaultSender'] = $rt->def_sender;
                $crear['templateFlag'] = $rt->template_flag;
                $crear['activeTime'] = $rt->active_time;
                $crear['coverage'] = $rt->country_id;
                $crear['creditRule'] = $rt->credit_rule;
                $crear['tlv_ids'] = $rt->tlv_ids;
                $crear['optout_config'] = $rt->optout_config;

                $credit_data['routes'][$rt->id] = $crear;
            }
            $_SESSION['credits'] = $credit_data;
        }

        //16. load permissions for the user
        unset($_SESSION['permissions']);
        if ($user->category == 'admin') {
            //load permissions for admin and staff
            $_SESSION['permissions'] = json_decode('{"master": "1"}', true);
            if ($user->subgroup == 'staff') {
                //staff permissions
                $stpobj = Doo::loadModel('ScStaffRights', true);
                $stpobj->staff_uid = $user->user_id;
                $strights = Doo::db()->find($stpobj, array('limit' => 1, 'select' => 'rights'));
                $_SESSION['permissions'] = json_decode($strights->rights, true);
            }
        } else {
            //load permissions for reseller and client
            Doo::loadModel('ScUsersPermissions');
            $upobj = new ScUsersPermissions;
            $upobj->user_id = $user->user_id;
            $upermdata = Doo::db()->find($upobj, array('select' => 'id, perm_data', 'limit' => 1));
            $uperms = $upermdata->perm_data;
            $_SESSION['permissions'] = json_decode($uperms, true);
            $_SESSION['permissions']['id'] = $upermdata->id;
        }

        //17. load user settings
        $ustobj = Doo::loadModel('ScUsersSettings', true);
        $ustobj->user_id = $user->user_id;
        $ustdata = Doo::db()->find($ustobj, array('limit' => 1));
        $_SESSION['settings']['lang'] = $ustdata->def_lang;
        $_SESSION['settings']['def_route'] = $ustdata->def_route;

        //18. generate jwt token
        $userdata = array(
            "userid" => $user->user_id,
            "login_id" => $user->login_id,
            "account_type" => $user->account_type,
            "group" => $user->subgroup,
            "permissions" => $_SESSION['permissions'],
            "manager" => $_SESSION['manager'],
            "plan_id" => $plan_id,
            "flags" => array("open_template" => $_SESSION['permissions']['master'] == '1' ? 1 : (isset($_SESSION['permissions']['master']['messaging']['open_template']) ? 1 : 0), "spam_allowed" =>  $_SESSION['permissions']['master'] == '1' ? 1 : (isset($_SESSION['permissions']['master']['messaging']['allow_spam']) ? 1 : 0)),
            "platform" => $osdata
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, Doo::conf()->search_api_auth_url . 'login');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Ignore SSL host verification

        $token = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $_SESSION['user_auth_token'] = $token;
        session_regenerate_id();
        session_write_close();

        //19. redirect to dashboard
        return Doo::conf()->APP_URL . 'Dashboard';
    }

    public function apiAuth()
    {
        Doo::loadHelper('DooSmppcubeHelper');
        $loginid = urldecode($this->params['loginid']);
        $pwd = urldecode($this->params['password']);

        $user = Doo::loadModel('ScUsers', true);
        $user->login_id = $loginid;
        $user->status = 1;
        $user = Doo::db()->find($user, array('limit' => 1));

        if ($user->user_id) {
            //user found
            Doo::loadHelper('DooEncrypt');
            $hfunck = base64_encode($user->login_id . '_' . base64_encode('smppcubehash'));
            $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
            $dbpass = $encobj->decrypt($user->password, $hfunck);

            if ($dbpass == $pwd) {
                //auth success
                $apikobj = Doo::loadModel('ScApiKeys', true);
                $apikobj->user_id = $user->user_id;
                $api_key = Doo::db()->find($apikobj, array('limit' => 1))->api_key;
                $response = array("result" => "success", "api_key" => DooSmppcubeHelper::aesDecrypt($api_key), "user_id" => $user->user_id, "account_type" => $user->account_type);
                echo json_encode($response);
                exit;
            }
        }
        $response = array("result" => "error", "api_key" => "invalid credentials");
        echo json_encode($response);
        exit;
    }

    public function recordAuthActivity($data)
    {
        session_start();
        Doo::loadHelper('DooSmppcubeHelper');
        $user_id = $data['user_id'];
        $activity = $data['activity'];
        $flag = $data['flag'];

        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        $osdata['system'] = $browser['platform'];
        $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
        $osdata['city'] = $browser['city'];
        $osdata['country'] = $browser['country'];
        $osdata['lat'] = $browser['lat'];
        $osdata['lon'] = $browser['lon'];

        //log activity with system data
        $lobj = Doo::loadModel('ScLogsUserActivity', true);
        $lobj->action_type = 'LOGIN';
        $lobj->user_id = $user_id;
        $lobj->page_url = base64_encode((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $lobj->activity = $activity;
        $lobj->flag = $flag;
        $lobj->visitor_ip = $_SERVER['REMOTE_ADDR'];
        $lobj->platform_data = serialize($osdata);
        Doo::db()->insert($lobj);

        if ($data['block_ip'] == true) {
            //block ip
            $biobj = Doo::loadModel('ScBlockedIpList', true);
            $biobj->ip_address = $_SERVER['REMOTE_ADDR'];
            $biobj->user_assoc = $user_id;
            $biobj->platform_data = serialize($osdata);
            $biobj->date_added = date(Doo::conf()->date_format_db);
            $biobj->remarks = $activity;
            Doo::db()->insert($biobj);
        }

        if (isset($data['login_alert'])) {
            $curpldata = serialize($osdata);
            //enter in saved login platform
            $uplatobj = Doo::loadModel('ScUsersSavedPlatforms', true);
            $uplatobj->user_id = $user_id;
            $uplatobj->platform_data = $curpldata;
            $upltdata = Doo::db()->find($uplatobj, array('limit' => 1, 'select' => 'id'));

            $platflag = 0; //0 means login from old platform, 1 means login from new platform i.e. IP, browser, OS
            if (!$upltdata->id) {
                //this is login from a new platform
                Doo::db()->insert($uplatobj);
                $platflag = 1;
            }
            if ($data['login_alert']['flag'] == true) {
                if (!(Doo::conf()->admin_login_alert == 2 && $platflag == 0)) {
                    //send mail if admin and admin login alert on for all login or admin alert on for new platforms
                    $userdata = array(
                        "mode" => "login_alert",
                        "data" => array(
                            "user_id" => $user_id,
                            "incidentPlatform" => $osdata,
                            "incidentDateTime" => date(Doo::conf()->date_format_db),
                            "deviceAndBrowser" => $browser['browser'] . ' v' . $browser['version'] . ' on ' . $browser['platform'],
                            "loginLocation" => $browser['city'] . ', ' . $browser['country'],
                            "ipAddress" => $_SERVER['REMOTE_ADDR']
                        )
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, Doo::conf()->APP_URL . 'hypernode/log/add');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userdata));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Content-Type: application/json; charset=UTF-8"
                    ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Ignore SSL host verification
                    $res = curl_exec($ch);
                    //print_r($res);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            }
        }

        return $osdata;
    }

    //2. Logout

    public function logout()
    {
        session_start();
        //record logout activity
        if ($_SESSION['user']['userid']) {
            Doo::loadModel('ScUsers');
            $obj = new ScUsers;
            $obj->doLogoutStat($_SESSION['user']['userid']);
        }

        $sitedata = unserialize($_SESSION['webfront']['site_data']);

        unset($_SESSION['manager']);
        unset($_SESSION['permissions']);
        unset($_SESSION['notifications']);
        unset($_SESSION['alerts']);
        unset($_SESSION['user']);
        unset($_SESSION['credits']);
        unset($_SESSION['webfront']);
        unset($_SESSION['captcha_random_number']);
        $token = json_decode($_SESSION['user_auth_token'], true);
        $authdata = array("token" => $token['token']);
        $soptions = array(
            'http' => array(
                'header'  => "Content-type: application/json; charset=UTF-8\r\n",
                'method'  => 'POST',
                'content' => json_encode($authdata)
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        $context  = stream_context_create($soptions);
        $token = file_get_contents(Doo::conf()->search_api_auth_url . 'logout', false, $context);
        unset($_SESSION['user_auth_token']);

        $_SESSION['opage'] = 'LOGIN';
        $_SESSION['notif_msg']['type'] = 'info';
        $_SESSION['notif_msg']['msg'] = 'Logged out successfully.';
        session_regenerate_id();
        return $sitedata->logout_url == "" ? Doo::conf()->APP_URL . 'web/sign-in' : $sitedata->logout_url;
    }

    public function expired()
    {
        session_start();
        unset($_SESSION['manager']);
        unset($_SESSION['permissions']);
        unset($_SESSION['notifications']);
        unset($_SESSION['alerts']);
        unset($_SESSION['user']);
        unset($_SESSION['credits']);
        unset($_SESSION['webfront']);
        unset($_SESSION['captcha_random_number']);
        $token = json_decode($_SESSION['user_auth_token'], true);
        $authdata = array("token" => $token['token']);
        $soptions = array(
            'http' => array(
                'header'  => "Content-type: application/json; charset=UTF-8\r\n",
                'method'  => 'POST',
                'content' => json_encode($authdata)
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        $context  = stream_context_create($soptions);
        $token = file_get_contents(Doo::conf()->search_api_auth_url . 'logout', false, $context);
        unset($_SESSION['user_auth_token']);

        if (isset($this->params['eid'])) {
            $msg = 'Session Expired. Event ID:' . $this->params['eid'];
        } else {
            $msg = 'Session Expired. Please login again.';
        }

        $_SESSION['opage'] = 'LOGIN';
        $_SESSION['notif_msg']['type'] = 'warning';
        $_SESSION['notif_msg']['msg'] = $msg;
        session_regenerate_id();
        return Doo::conf()->APP_URL . 'web/sign-in';
    }

    //** TRANSLATION FUNCTION **//
    public static function SCTEXT($str)
    {
        if (!isset($_SESSION)) session_start();
        if ($_SESSION['APP_LANG'] == Doo::conf()->default_lang)
            return $str;
        $lang_file = './protected/plugin/lang/' . $_SESSION['APP_LANG'] . '.lang.php';
        include $lang_file;
        if (isset($lang[strtolower($str)])) {
            return $lang[strtolower($str)];
        } else {
            //for now just return the english string
            //return $str;
            //get translated text using API call
            $translate = new TranslateClient([
                'key' => Doo::conf()->gcp_api_key
            ]);
            $result = $translate->translate($str, [
                'target' => $_SESSION['APP_LANG']
            ]);
            //write that in the language file
            $lang[strtolower($str)] = ucfirst($result['text']);

            if (strpos($str, "'") == false) {
                $langstr = '$lang[\'' . strtolower($str) . '\'] = "' . str_replace('"', '\"', ucfirst($result['text'])) . '";
';
            } else {

                $langstr = '$lang["' . strtolower($str) . '"] = "' . str_replace('"', '\"', ucfirst($result['text'])) . '";
';
            }
            $handle = fopen($lang_file, 'a');
            fwrite($handle, $langstr);
            fclose($handle);
            //return the translated text
            return ucfirst($result['text']);
        }
    }

    //** END OF TRANSLATION FUNCTION **//


}
