<?php

/**
 * MainController
 *
 * @author saurav
 *
 * 1. Front End Pages
 * 2. Front end tasks (contact form and Test Gateway Widget(TGW) )
 * 3. Sign up and forget password
 * 4. Dashboard
 * 5. Misc tasks
 *
 */

use Google\Cloud\Translate\V2\TranslateClient;

use function PHPSTORM_META\map;

class MainController extends DooController
{
    //1. Front End Pages
    public function appHome()
    {
        session_start();
        unset($_SESSION['otpresend']);
        unset($_SESSION['captcha_verified']);
        unset($_SESSION['postvars']); // any post var stored in session needs to be cleared because it may cause unwanted behavior
        //this is the beginning of the app. The very first piece of code which is executed when we visit app

        //block access if visitor ip is in blocked ip list
        Doo::loadHelper('DooTextHelper');
        $ipQ = 'SELECT ip_address FROM sc_blocked_ip_list';
        $iprs = Doo::db()->fetchAll($ipQ, null, PDO::FETCH_COLUMN);
        if ($iprs) {
            //since this is a blacklist, if IP matches a pattern or present, block it
            if (DooTextHelper::isIpAllowed($_SERVER['REMOTE_ADDR'], $iprs)) {
                return array('/denied', 'internal');
            }
        }
        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        //get the URL and match it against a user
        $url = Doo::conf()->APP_URL;
        $page = is_array($this->params) ? $this->params['page'] : '';
        $url = preg_replace("(^https?://)", "", $url); //remove protocol from domain
        $url = str_replace('/app/', '', $url);
        $url = rtrim($url, "/");
        $uparts = parse_url($url);
        $url = isset($uparts['host']) ? $uparts['host'] : $url;
        //check if this is a tinyURL
        $tinyurlqry = 'SELECT user_id, domain FROM sc_users_tinyurl';
        $tinydomains = Doo::db()->fetchAll($tinyurlqry, null, PDO::FETCH_KEY_PAIR);
        if (in_array($url, $tinydomains) || $url == Doo::conf()->tinyurl) {
            $this->tinyUrlProcess();
            exit;
        }

        //get website settings and user
        Doo::loadModel('ScWebsites');
        $webobj = new ScWebsites;
        $wdata = $webobj->getWebsiteData($url, 'domain');

        if (!$wdata) {
            //no user matched
            return array('/error/disabled', 'internal');
        } else {
            //check if a user account has active session
            if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
                return Doo::conf()->APP_URL . 'Dashboard';
            } else {
                //set website details into session
                $cdata = unserialize($wdata->site_data);
                if (empty($_SESSION['webfront'])) {
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

        //get the app language
        $lgobj = Doo::loadModel('ScUsersSettings', true);
        $lgobj->user_id = $wdata->user_id;
        $app_lang = Doo::db()->find($lgobj, array('limit' => 1, 'select' => 'def_lang'));
        $_SESSION['APP_LANG'] = $app_lang->def_lang == false ? Doo::conf()->default_lang : $app_lang->def_lang;

        //check if app is in maintenance mode
        Doo::loadModel('ScMiscVars');
        $obj = new ScMiscVars;
        $obj->var_name = 'MAINTENANCE_MODE_DATA';
        $mminfo = Doo::db()->find($obj, array('limit' => 1, 'select' => 'var_value,var_status'));

        if ($mminfo->var_status == 1 && $page != 'sign-in') {
            //allow login page to display
            $data['mm_data'] = unserialize($mminfo->var_value);
            $this->view()->renderc('outer/m_mode', $data);
        } else {
            //check front end is website or just login page
            Doo::loadModel('ScWebsitesPageData');
            $pgobj = new ScWebsitesPageData;

            //sign up page
            if ($page == 'sign-up') {
                $pgobj->site_id = $_SESSION['webfront']['id'];
                $pgobj->user_id = $_SESSION['webfront']['owner'];
                $pgobj->page_type = 'LOGIN';

                //any notification
                if (isset($_SESSION['notif_msg'])) {
                    $data['notif_msg'] = $_SESSION['notif_msg'];
                    unset($_SESSION['notif_msg']);
                }

                $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));

                //check if web signups are allowed
                if (unserialize(base64_decode($data['pdata']->page_data))['regflag'] == 0) {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Please contact admin for new account.';
                    return Doo::conf()->APP_URL . 'web/sign-in';
                }

                //check if sms plans added in pricing page
                $pgobj_prc = new ScWebsitesPageData;
                $pgobj_prc->site_id = $_SESSION['webfront']['id'];
                $pgobj_prc->user_id = $_SESSION['webfront']['owner'];
                $pgobj_prc->page_type = 'PRICING';
                $prcdata = Doo::db()->find($pgobj_prc, array('limit' => 1, 'select' => 'page_data'));

                //get all coverage
                $covobj = Doo::loadModel('ScCoverage', true);
                $data['covdata'] = Doo::db()->find($covobj, array("where" => 'id > 1'));

                //check if admin has added shortcodes to display plans
                $pdata = unserialize(base64_decode($prcdata->page_data));
                $content = htmlspecialchars_decode($pdata['content']);
                $no_plans = substr_count($content, '[PLANID=');
                $pidar = array();
                if (intval($no_plans) > 0) {
                    //there are plan shortcodes. translate them

                    //all routes
                    Doo::loadModel('ScSmsRoutes');
                    $obj = new ScSmsRoutes;
                    $data['rdata'] = Doo::db()->find($obj, array('select' => 'id, title'));

                    //plan details
                    Doo::loadModel('ScSmsPlans');
                    $plobj = new ScSmsPlans;

                    //plan options
                    Doo::loadModel('ScSmsPlanOptions');
                    $optobj = new ScSmsPlanOptions;

                    //for each occurence of shortcode prepare this
                    for ($i = 1; $i <= $no_plans; $i++) {
                        $intplan = array();
                        //extract the plan
                        preg_match('~PLANID=(.*?)]~', $content, $output);
                        $planid = intval($output[1]);
                        array_push($pidar, $planid);
                        //replace temporarily
                        $content = str_replace("[PLANID=" . $planid . "]", '', $content);
                    }
                }

                if (sizeof($pidar) > 0) {
                    $data['pflag'] = 1;
                    //plans found
                    //do the same as add new user in reseller controller
                    $pidstr = implode(",", $pidar);
                    $data['plans'] = Doo::db()->find($plobj, array('where' => "id IN ($pidstr)"));
                } else {
                    $data['pflag'] = 0;
                }

                $data['country'] = $browser['iso'];
                if ($_SESSION['webfront']['owner'] == 1) {
                    $this->view()->renderc('outer/mainSignup' . Doo::conf()->custom_login_view, $data);
                } else {
                    $this->view()->renderc('outer/mainSignup', $data);
                }
                exit;
            }

            //forget password page
            if ($page == 'resetPassword') {
                $pgobj->site_id = $_SESSION['webfront']['id'];
                $pgobj->user_id = $_SESSION['webfront']['owner'];
                $pgobj->page_type = 'LOGIN';

                //any notification from logging out
                if (isset($_SESSION['notif_msg'])) {
                    $data['notif_msg'] = $_SESSION['notif_msg'];
                    unset($_SESSION['notif_msg']);
                }

                if (isset($_SESSION['opage'])) {
                    $data['openPage'] = $_SESSION['opage'];
                    unset($_SESSION['opage']);
                }

                $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));
                $this->view()->renderc('outer/resetPass', $data);
                exit;
            }

            //forget password otp page
            if ($page == 'resetPasswordOtpVerify') {
                $pgobj->site_id = $_SESSION['webfront']['id'];
                $pgobj->user_id = $_SESSION['webfront']['owner'];
                $pgobj->page_type = 'LOGIN';

                if (!isset($_SESSION['rpvars']) && !isset($_SESSION['verifiedUser'])) {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Invalid session. Please login again.';
                    return Doo::conf()->APP_URL . 'web/sign-in';
                }

                //any notification from logging out
                if (isset($_SESSION['notif_msg'])) {
                    $data['notif_msg'] = $_SESSION['notif_msg'];
                    unset($_SESSION['notif_msg']);
                }

                $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));
                $this->view()->renderc('outer/verifyFpOtp', $data);
                exit;
            }

            if ($wdata->front_type == 0 || $page == 'sign-in') {
                if ($page == 'terms') {
                    $skin_data = unserialize($_SESSION['webfront']['skin_data']);
                    $data['skin'] = $skin_data;
                    $data['content'] = $cdata['tnc'];
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/tnc', $data);
                } else {
                    //login-page
                    $pgobj->site_id = $_SESSION['webfront']['id'];
                    $pgobj->user_id = $_SESSION['webfront']['owner'];
                    $pgobj->page_type = 'LOGIN';

                    //if captcha
                    if (Doo::conf()->captcha_action == '1' || (isset($_SESSION['failed_login_attempt']) && intval($_SESSION['failed_login_attempt']) == 1)) {
                        //initialize
                        $_SESSION['captcha_random_number'] = '';
                    }

                    //any notification from logging out
                    if (isset($_SESSION['notif_msg'])) {
                        $data['notif_msg'] = $_SESSION['notif_msg'];
                        unset($_SESSION['notif_msg']);
                    }

                    if (isset($_SESSION['opage'])) {
                        $data['openPage'] = $_SESSION['opage'];
                        unset($_SESSION['opage']);
                    }

                    $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));
                    //$this->view()->renderc('outer/verifyMfaOtp', $data);
                    if ($_SESSION['webfront']['owner'] == 1) {
                        $this->view()->renderc('outer/mainLogin' . Doo::conf()->custom_login_view, $data);
                    } else {
                        $this->view()->renderc('outer/mainLogin', $data);
                    }
                }
            } else {
                //website
                $skin_data = unserialize($_SESSION['webfront']['skin_data']);
                if ($page == '') {
                    //home page
                    $pgobj->site_id = $_SESSION['webfront']['id'];
                    $pgobj->user_id = $_SESSION['webfront']['owner'];
                    $pgobj->page_type = 'HOME';
                    $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data, page_type'));
                    $data['skin'] = $skin_data;
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/home', $data);
                } elseif ($page == 'about') {
                    //about page
                    $pgobj->site_id = $_SESSION['webfront']['id'];
                    $pgobj->user_id = $_SESSION['webfront']['owner'];
                    $pgobj->page_type = 'ABOUT';
                    $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data, page_type'));
                    $data['skin'] = $skin_data;
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/about', $data);
                } elseif ($page == 'pricing') {
                    //pricing page
                    $pgobj->site_id = $_SESSION['webfront']['id'];
                    $pgobj->user_id = $_SESSION['webfront']['owner'];
                    $pgobj->page_type = 'PRICING';
                    $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data, page_type'));
                    $data['skin'] = $skin_data;

                    //check if admin has added shortcodes to display plans
                    $pdata = unserialize(base64_decode($data['pdata']->page_data));
                    $content = htmlspecialchars_decode($pdata['content']);
                    $no_plans = substr_count($content, '[PLANID=');
                    $finalplans = array();

                    if (intval($no_plans) > 0) {
                        //there are plan shortcodes. translate them
                        //all routes
                        Doo::loadModel('ScSmsRoutes');
                        $obj = new ScSmsRoutes;
                        $data['rdata'] = Doo::db()->find($obj, array('select' => 'id, title'));

                        //plan details
                        Doo::loadModel('ScSmsPlans');
                        $plobj = new ScSmsPlans;

                        //plan options
                        Doo::loadModel('ScSmsPlanOptions');
                        $optobj = new ScSmsPlanOptions;

                        //for each occurence of shortcode prepare this
                        for ($i = 1; $i <= $no_plans; $i++) {
                            $intplan = array();
                            //extract the plan
                            preg_match('~PLANID=(.*?)]~', $content, $output);
                            $planid = intval($output[1]);
                            //prepare an array of plan data
                            $plobj->id = $planid;
                            $plandata = Doo::db()->find($plobj, array('limit' => 1));
                            //plan opts
                            $optobj->plan_id = intval($plandata->id);
                            if (intval($plandata->plan_type) == 0) {
                                $opdata = Doo::db()->find($optobj, array('limit' => 1));
                            } else {
                                $opdata = Doo::db()->find($optobj, array('asc' => 'subopt_idn'));
                            }

                            $intplan['id'] = $planid;
                            $intplan['type'] = intval($plandata->plan_type) == 0 ? 'vol' : 'sub';
                            $intplan['pdata'] = $plandata;
                            $intplan['opdata'] = $opdata;

                            array_push($finalplans, $intplan);
                            //replace plan shortcode temporarily
                            $content = str_replace("[PLANID=" . $planid . "]", '', $content);
                        }
                    }

                    $data['allplans'] = $finalplans;
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/pricing', $data);
                } elseif ($page == 'contact-us') {
                    //contact page
                    $pgobj->site_id = $_SESSION['webfront']['id'];
                    $pgobj->user_id = $_SESSION['webfront']['owner'];
                    $pgobj->page_type = 'CONTACT';
                    $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data, page_type'));
                    $data['skin'] = $skin_data;
                    if (isset($_GET['sub'])) {
                        $data['sub'] = $_GET['sub'];
                    }
                    //contact form submission notifs
                    if (isset($_SESSION['notif_msg'])) {
                        $data['notif_msg'] = $_SESSION['notif_msg'];
                        unset($_SESSION['notif_msg']);
                    }
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/contact', $data);
                } elseif ($page == 'terms') {
                    //tnc
                    $data['skin'] = $skin_data;
                    $data['content'] = $cdata['tnc'];
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/tnc', $data);
                } elseif ($page == 'privacy') {
                    //privacy policy
                    $data['skin'] = $skin_data;
                    $data['content'] = $cdata['policy'];
                    $this->view()->renderc('outer/' . $skin_data['name'] . '/privacy', $data);
                }
            }
        }

        session_write_close();
    }

    //2. Front end tasks
    //-- Test Gateway Widget
    public function submitGwTestSms()
    {
        session_start();
        Doo::loadHelper('DooSmppcubeHelper');
        Doo::loadHelper('DooTextHelper');
        //get posted data
        $mobile = $_POST['mobile'];
        $res = array();
        //validate phone number
        if (!DooTextHelper::verifyFormData('mobile', $mobile)) {
            $res['result'] = 'error';
            $res['msg'] = 'Invalid mobile number provided.';
            echo json_encode($res);
            exit;
        }

        //get gw test widget settings
        $wobj = Doo::loadModel('ScWebsitesPageData', true);
        $wobj->site_id = $_SESSION['webfront']['id'];
        $wobj->user_id = $_SESSION['webfront']['owner'];
        $wobj->page_type = 'HOME';
        $wdata = Doo::db()->find($wobj, array('limit' => 1, 'select' => 'page_data'));

        $pdata = unserialize(base64_decode($wdata->page_data));

        //send the sms
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
        $api_key = $akobj->getApiKey($_SESSION['webfront']['owner']);
        $smsdata['sms'] = base64_encode($pdata['twgdata']['sms']);
        $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . intval($cmpobj->getCampaignId($_SESSION['webfront']['owner'], 'system')) . '&routeid=' . $pdata['twgdata']['route'] . '&type=text&contacts=' . $mobile . '&senderid=' . $pdata['twgdata']['sender'] . '&msg=' . urlencode($pdata['twgdata']['sms']);

        //Submit to server
        $smsdata['apiurl'] = base64_encode($api_url);
        $smsdata['response'] = file_get_contents($api_url, false, stream_context_create($arrContextOptions));


        //save data in website leads
        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        $osdata['system'] = $browser['platform'];
        $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
        $osdata['city'] = $browser['city'];
        $osdata['country'] = $browser['country'];
        $osdata['lat'] = $browser['lat'];
        $osdata['lon'] = $browser['lon'];

        $wlobj = Doo::loadModel('ScWebsitesLeads', true);
        $wlobj->mode = 1;
        $wlobj->visitor_info = $mobile;
        $wlobj->user_assoc = $_SESSION['webfront']['owner'];
        $wlobj->activity_date = date(Doo::conf()->date_format_db);
        $wlobj->web_url = Doo::conf()->APP_URL;
        $wlobj->platform_data = serialize($osdata);
        $wlobj->sms_data = serialize($smsdata);

        Doo::db()->insert($wlobj);

        //notify website owner
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($_SESSION['webfront']['owner'], 'info', Doo::conf()->tgw_lead_capture, 'webLeads');

        //return
        $res['result'] = 'success';
        $res['msg'] = 'SMS sent successfully.';
        echo json_encode($res);
        exit;
    }

    //-- submit contact form
    public function saveContactLead()
    {
        //echo '<pre>';var_dump($_POST);die;
        session_start();
        Doo::loadHelper('DooTextHelper');
        Doo::loadHelper('DooSmppcubeHelper');

        //get data
        $name = DooTextHelper::cleanInput($_POST['name'], ' ', 0);
        $lead_email = $_POST['email'];
        $subject = DooTextHelper::cleanInput($_POST['subject'], ' ', 0);
        $msg = DooTextHelper::cleanInput($_POST['message'], ' .,', 0);
        $cemail = base64_decode($_POST['cemail']);

        //validate data
        if (!DooTextHelper::verifyFormData('email', $lead_email)) {
            if ($_POST['fe_site_xhr'] == 1) {
                echo 'Incorrect Email type Entered';
                exit;
            } else {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid email address.';
                return Doo::conf()->APP_URL . 'web/contact-us';
            }
        }
        //add entry in lead table
        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        $osdata['system'] = $browser['platform'];
        $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
        $osdata['city'] = $browser['city'];
        $osdata['country'] = $browser['country'];
        $osdata['lat'] = $browser['lat'];
        $osdata['lon'] = $browser['lon'];

        $wlobj = Doo::loadModel('ScWebsitesLeads', true);
        $wlobj->mode = 0;
        $wlobj->visitor_info = base64_encode(serialize(array($name, $lead_email, $subject, $msg)));
        $wlobj->user_assoc = isset($_SESSION['webfront']['owner']) ? $_SESSION['webfront']['owner'] : 1;
        $wlobj->activity_date = date(Doo::conf()->date_format_db);
        $wlobj->web_url = Doo::conf()->APP_URL;
        $wlobj->platform_data = serialize($osdata);

        Doo::db()->insert($wlobj);

        //notify website owner
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($_SESSION['webfront']['owner'], 'info', Doo::conf()->contact_lead_capture, 'webLeads');

        //send email if valid recepient
        //return
        if ($_POST['fe_site_xhr'] == 1) {
            echo 'DONE';
            exit;
        } else {
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Your query has been submitted. Our staff will get in touch with you soon.';
            return Doo::conf()->APP_URL . 'web/contact-us';
        }
    }

    //-- get selected plan options on sign up page
    public function getSelPlanOptionsOuter()
    {
        //get data
        $pid = intval($_POST['planid']);
        $ptype = intval($_POST['ptype']);

        if ($ptype == 0) {
            $rts = $_POST['routes'];
            Doo::loadModel('ScSmsRoutes');
            $obj = new ScSmsRoutes;
            $o['select'] = 'id, title';
            if ($pid != 0) $o['where'] = "id IN ($rts)";
            $routes = Doo::db()->find($obj, $o);

            //get pricing for this plan and this route
            if ($pid != 0) {
                Doo::loadModel('ScSmsPlanOptions');
                $pobj = new ScSmsPlanOptions;
                $pobj->plan_id = $pid;
                $option = Doo::db()->find($pobj, array('limit' => 1, 'select' => 'opt_data'));
                $prices = unserialize($option->opt_data);
            }

            //prepare response
            $response = array();
            $response['type'] = 0;



            foreach ($routes as $rt) {
                $response['opt_data'][$rt->id]['title'] = $rt->title;
                if ($pid != 0) {
                    $response['opt_data'][$rt->id]['price'] = $prices[0][$rt->id];
                } else {
                    $response['opt_data'][$rt->id]['price'] = 0;
                }
            }
        } else {
            //get plan option
            Doo::loadModel('ScSmsPlanOptions');
            $obj = new ScSmsPlanOptions;
            $obj->plan_id = $pid;
            $options = Doo::db()->find($obj, array('select' => 'subopt_idn,opt_data'));
            //prepare response
            $response = array();
            $response['type'] = 1;
            foreach ($options as $opt) {
                $response['opt_data']["$opt->subopt_idn"] = unserialize($opt->opt_data);
            }
        }

        //return
        echo json_encode($response);
        exit;
    }

    //-- get final sms price for sms plan based on sms volume
    public function getPlanSmsPriceOuter()
    {
        //collect values
        $pid = intval($_POST['plan']);
        $rdata = json_decode(stripslashes($_POST['routesData']));
        $dis = 0;
        $dtype = '';
        $adtx = 0;

        //$additional_tax_cal = $final - $initial($red/100);

        //based on plan route and volume get rate
        Doo::loadModel("ScSmsPlanOptions");
        $obj = new ScSmsPlanOptions;
        $pricedata = $obj->getSmsPrice($pid, $rdata);

        //get Tax
        Doo::loadModel('ScSmsPlans');
        $sobj = new ScSmsPlans;
        $sobj->id = $pid;
        $taxdata = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'tax, tax_type'));

        $total_price = $pricedata['total'];

        if (Doo::conf()->invoice_discount == 'before_taxes') {
            //apply discount on total price
            if ($dis != 0) {
                if ($dtype == 'per') {
                    //percent discount
                    $total_af_dis = $total_price - ($total_price * $dis / 100);
                } else {
                    //flat discount
                    $total_af_dis = $total_price - $dis;
                }
            } else {
                $total_af_dis = $total_price;
            }

            //apply plan tax
            $total_af_plntax = $total_af_dis + ($total_af_dis * $taxdata->tax / 100);

            //apply additional tax
            if ($adtx != 0) {
                $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
            } else {
                $total_af_adtax = $total_af_plntax;
            }
            $grand_total = $total_af_adtax;
        } else {
            //apply plan tax
            $total_af_plntax = $total_price + ($total_price * $taxdata->tax / 100);

            //apply additional tax
            if ($adtx != 0) {
                $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
            } else {
                $total_af_adtax = $total_af_plntax;
            }
            //apply discount
            if ($dis != 0) {
                if ($dtype == 'per') {
                    //percent discount
                    $total_af_dis = $total_af_adtax - ($total_af_adtax * $dis / 100);
                } else {
                    //flat discount
                    $total_af_adtax = $total_af_adtax - $dis;
                }
            } else {
                $total_af_dis = $total_af_adtax;
            }
            $grand_total = $total_af_dis;
        }

        //return rate and total cost
        $res['price'] = $pricedata;
        $res['total_plan'] = round($total_price, 5);
        $res['grand_total'] = round($grand_total, 5);
        switch ($taxdata->tax_type) {
            case 'VT':
                $type = 'VAT';
                break;
            case 'ST':
                $type = 'Service Tax';
                break;
            case 'SC':
                $type = 'Service Charge';
                break;
            case 'OT':
                $type = 'Tax';
                break;
            case 'GT':
                $type = 'GST';
                break;
        }

        $res['plan_tax'] = $taxdata->tax == 0 ? '' : '(including ' . $taxdata->tax . '% ' . $type . ')';
        echo json_encode($res);
        exit;
    }

    //3. Sign up and forget password

    public function regNewAccount()
    {
        session_start();

        Doo::loadHelper('DooTextHelper');
        $alobj = Doo::loadModel('ScUserNotifications', true);

        // collect profile info
        $cat = "client"; // reseseller account creation is not allowed in v9 // DooTextHelper::cleanInput($_POST['ucat']);
        $name = DooTextHelper::cleanInput($_POST['uname'], ' ', 0);
        $gender = $_POST['gender'];
        $loginid = DooTextHelper::cleanInput($_POST['ulogin']);
        $email = $_POST['uemail'];
        $phn = intval($_POST['uphn']);

        //generate password
        $pass = DooTextHelper::generateUserPassword();

        //get upline and account manager
        $siteid = $_SESSION['webfront']['id'];
        $upline_id = $_SESSION['webfront']['owner'];

        $usrobj = Doo::loadModel('ScUsers', true);
        $uplineInfo = $usrobj->getProfileInfo($upline_id);

        $suobj = Doo::loadModel('ScWebsitesSignupSettings', true);
        //$suobj->site_id = $siteid;
        $suobj->user_id = $upline_id;
        $sudata = Doo::db()->find($suobj, array('limit' => 1));

        $signupdata = unserialize($sudata->signup_data);
        if (isset($signupdata['acc_mgr']) && $signupdata['acc_mgr'] != '') {
            $account_mgr = $signupdata['acc_mgr'];
        } else {
            $account_mgr = $upline_id;
        }

        $optin_perm = 0; // this feature is introduced in higher editions
        $redtologin = 0; //redirect flag to check where to redirect after sign up
        //collect invoice data
        $dis = 0;
        $dtype = '';
        $adtx = 0;
        $ptype = intval($_POST['ptype']);

        //validate
        if (strlen($loginid) < 5) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Login ID. Must be at least 5 characters long.';
            return Doo::conf()->APP_URL . 'web/sign-up';
        }
        if (!DooTextHelper::verifyFormData('email', $email)) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid email address.';
            return Doo::conf()->APP_URL . 'web/sign-up';
        }
        if (!DooTextHelper::verifyFormData('mobile', $phn)) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid phone number.';
            return Doo::conf()->APP_URL . 'web/sign-up';
        }
        if ($cat == 'reseller' && $ptype == 1) {
            //reseller cannot have subscription based plans
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Subscription based plans cannot be assigned to reseller accounts. Please choose a different plan or assign custom SMS rates.';
            return Doo::conf()->APP_URL . 'web/sign-up';
        }

        if (intval($_POST['uplan']) > 0) {
            //plan is selected
            //this is only applicable in case of admin downline as resellers cannot show sms plans on front end
            $plan_id = intval($_POST['uplan']);
            $ptype = intval($_POST['ptype']);
            if ($ptype == 0) {
                $rcdata = array();
                foreach ($_POST['route'] as $rid => $on) {
                    $myobj = new stdClass;
                    $myobj->credits = intval($_POST['credits'][$rid]);
                    $myobj->id = $rid;
                    array_push($rcdata, $myobj);
                }

                Doo::loadModel("ScSmsPlanOptions");
                $spoobj = new ScSmsPlanOptions;
                $pricedata = $spoobj->getSmsPrice($plan_id, $rcdata);
                $rdata = $pricedata;
                unset($rdata['total']);

                //get Tax
                Doo::loadModel('ScSmsPlans');
                $sobj = new ScSmsPlans;
                $sobj->id = $plan_id;
                $taxdata = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'tax, tax_type'));

                $total_price = $pricedata['total'];

                if (Doo::conf()->invoice_discount == 'before_taxes') {
                    //apply discount on total price
                    if ($dis != 0) {
                        if ($dtype == 'per') {
                            //percent discount
                            $total_af_dis = $total_price - ($total_price * $dis / 100);
                        } else {
                            //flat discount
                            $total_af_dis = $total_price - $dis;
                        }
                    } else {
                        $total_af_dis = $total_price;
                    }

                    //apply plan tax
                    $total_af_plntax = $total_af_dis + ($total_af_dis * $taxdata->tax / 100);

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    $grand_total = $total_af_adtax;
                } else {
                    //apply plan tax
                    $total_af_plntax = $total_price + ($total_price * $taxdata->tax / 100);

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    //apply discount
                    if ($dis != 0) {
                        if ($dtype == 'per') {
                            //percent discount
                            $total_af_dis = $total_af_adtax - ($total_af_adtax * $dis / 100);
                        } else {
                            //flat discount
                            $total_af_adtax = $total_af_adtax - $dis;
                        }
                    } else {
                        $total_af_dis = $total_af_adtax;
                    }
                    $grand_total = $total_af_dis;
                }

                //prepare array of routes n credits n sms rates applied
                switch ($taxdata->tax_type) {
                    case 'VT':
                        $type = 'VAT';
                        break;
                    case 'ST':
                        $type = 'Service Tax';
                        break;
                    case 'SC':
                        $type = 'Service Charge';
                        break;
                    case 'OT':
                        $type = 'Tax';
                        break;
                    case 'GT':
                        $type = 'GST';
                        break;
                }

                $invdata['plan_tax'] = $taxdata->tax == 0 ? 0 : $taxdata->tax . '% ' . $type;
                $invdata['routes_credits'] = $rdata;
                $invdata['total_cost'] = $total_price;
                $invdata['additional_tax'] = $adtx . '%';
                $invdata['discount'] = $dis == 0 ? 'N/A' : ($dtype == 'per' ? $dis . '%' : $dis . ' ' . Doo::conf()->currency_name);
                $invdata['grand_total'] = $grand_total;

                $invdata['inv_status'] = 0; //pending payment
                $invdata['inv_rem'] = '';

                $permissions = Doo::conf()->default_user_permissions;
            } else {
                //subscription based
                $idn = $_POST['plan_option'];
                Doo::loadModel("ScSmsPlanOptions");
                $obj = new ScSmsPlanOptions;
                $prc_data = $obj->getIdnData($plan_id, $idn);
                $subopt_data = unserialize($prc_data->opt_data);
                $total_price = $subopt_data['fee'];
                //get Tax
                Doo::loadModel('ScSmsPlans');
                $sobj = new ScSmsPlans;
                $sobj->id = $plan_id;
                $taxdata = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'tax, tax_type'));

                if (Doo::conf()->invoice_discount == 'before_taxes') {
                    //apply discount on total price
                    if ($dis != 0) {
                        if ($dtype == 'per') {
                            //percent discount
                            $total_af_dis = $total_price - ($total_price * $dis / 100);
                        } else {
                            //flat discount
                            $total_af_dis = $total_price - $dis;
                        }
                    } else {
                        $total_af_dis = $total_price;
                    }
                    //apply plan tax
                    $total_af_plntax = $total_af_dis + ($total_af_dis * $taxdata->tax / 100);

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    $grand_total = $total_af_adtax;
                } else {
                    //apply plan tax
                    $total_af_plntax = $total_price + ($total_price * $taxdata->tax / 100);

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    //apply discount
                    if ($dis != 0) {
                        if ($dtype == 'per') {
                            //percent discount
                            $total_af_dis = $total_af_adtax - ($total_af_adtax * $dis / 100);
                        } else {
                            //flat discount
                            $total_af_adtax = $total_af_adtax - $dis;
                        }
                    } else {
                        $total_af_dis = $total_af_adtax;
                    }
                    $grand_total = $total_af_dis;
                }
                //prepare routes and credits
                $rdata = array();
                if ($subopt_data['cycle'] == 'm') {
                    $val = '30 Days';
                } else {
                    $val = '1 year';
                }
                foreach ($subopt_data['route_credits'] as $rid => $credits) {
                    $rdata[$rid]['credits'] = $credits;
                    $rdata[$rid]['price'] = $subopt_data['route_add_sms_rate'][$rid];
                    $rdata[$rid]['validity'] = $val;
                }
                //invoice
                switch ($taxdata->tax_type) {
                    case 'VT':
                        $type = 'VAT';
                        break;
                    case 'ST':
                        $type = 'Service Tax';
                        break;
                    case 'SC':
                        $type = 'Service Charge';
                        break;
                    case 'OT':
                        $type = 'Tax';
                        break;
                    case 'GT':
                        $type = 'GST';
                        break;
                }
                $invdata['plan_tax'] = $taxdata->tax == 0 ? 0 : $taxdata->tax . '% ' . $type;
                $invdata['routes_credits'] = $rdata;
                $invdata['total_cost'] = $total_price;
                $invdata['additional_tax'] = $adtx . '%';
                $invdata['discount'] = $dis == 0 ? 'N/A' : ($dtype == 'per' ? $dis . '%' : $dis . ' ' . Doo::conf()->currency_name);
                $invdata['grand_total'] = $grand_total;
                $invdata['inv_status'] = 0;
                $invdata['inv_rem'] = '';
                //set user permissions
                $permissions = serialize($subopt_data['features']);
            }
        } else {
            //free trial - this can work for both admin and reseller web signups
            $redtologin = 1;
            //this is when no plan is selected::also works for both admin and reseller accounts downline

            //get free trial info from sign up settings
            $rid = $signupdata['def_route'];
            $credits = $signupdata['free_credits'];
            $price = $signupdata['sms_rate'];

            //if reseller, make sure the account has enough credits to add to this user if not set it to zero
            if ($uplineInfo->category == 'reseller') {
                $crechkobj = Doo::loadModel('ScUsersCreditData', true);
                $avcre = $crechkobj->getRouteCredits($upline_id, $rid);
                if ($avcre < $credits) {
                    $credits = 0;
                    //add alert for reseller about depeleting credit balance
                    $alobj->addAlert($upline_id, 'warning', 'LOW CREDITS: ' . Doo::conf()->signup_low_credits, 'signupWebSettings');
                }
            }

            $rdata = array();
            $rdata[$rid]['credits'] = $credits;
            $rdata[$rid]['price'] = $price;
            $rdata[$rid]['validity'] = $signupdata['def_validity'];


            $invdata['plan_tax'] = '';
            $invdata['routes_credits'] = $rdata;
            $invdata['total_cost'] = 0;
            $invdata['additional_tax'] = '';
            $invdata['discount'] = 'N/A';
            $invdata['grand_total'] = 0;
            $invdata['inv_status'] = 1;
            $invdata['inv_rem'] = 'Free SMS credits for Trial';

            //set user permissions
            if ($uplineInfo->category == 'admin') {
                //default global permissions
                $permissions = Doo::conf()->default_user_permissions;
            } else {
                //reseller -> get his permissions and apply the same to downline user
                Doo::loadModel('ScUsersPermissions');
                $upobj = new ScUsersPermissions;
                $upobj->user_id = $uplineInfo->user_id;
                $permissions = Doo::db()->find($upobj, array('select' => 'perm_data', 'limit' => 1))->perm_data;
            }
        }
        //add user account
        $hfunck = base64_encode($loginid . '_' . base64_encode('smppcubehash'));
        Doo::loadHelper('DooEncrypt');
        $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $encpass = $encobj->encrypt($pass, $hfunck);

        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $uobj->login_id = $loginid;
        $uobj->password = $encpass;
        $uobj->name = $name;
        $uobj->gender = $gender;
        $uobj->avatar = $_POST["profilepic"] != "" ? $_POST["profilepic"] : ($gender == 'm' ? Doo::conf()->default_avatar_male_user : Doo::conf()->default_avatar_female_user);
        $uobj->category = $cat;
        $uobj->subgroup = $cat;
        $uobj->optin_only = $optin_perm;
        $uobj->mobile = $phn;
        $uobj->email = $email;
        $uobj->upline_id = $upline_id;
        $uobj->acc_mgr_id = $account_mgr;
        $uobj->status = 1;
        $uobj->registered_on = date(Doo::conf()->date_format_db);
        $uid = Doo::db()->insert($uobj);

        //save user routes assigned and credits and sms rate applied with dlr percentage
        Doo::loadModel('ScUsersCreditData');
        $cobj = new ScUsersCreditData;
        $cobj->saveCreditData($uid, $rdata);

        //create default campaigns
        $cmpobj = Doo::loadModel('ScUsersCampaigns', true);
        $cmpobj->createNewCampaigns($uid);

        //save credit log for newly created user
        //whatever the case may be - plan selected or not $rdata has routes and credits
        Doo::loadModel('ScLogsCredits');
        $ulcobj = new ScLogsCredits;
        foreach ($rdata as $routeid => $info) {
            $d_credits = intval($info['credits']);
            //credit log
            $ulcobj->user_id = $uid;
            $ulcobj->timestamp = date(Doo::conf()->date_format_db);
            $ulcobj->amount = $d_credits;
            $ulcobj->route_id = $routeid;
            $ulcobj->credits_before = 0;
            $ulcobj->credits_after = $d_credits;
            $ulcobj->reference = 'Signup Credits';
            $ulcobj->comments = 'SMS was added during account creation.';
            Doo::db()->insert($ulcobj);
        }

        //deduct balance from upline if site owner is reseller
        if ($uplineInfo->category == 'reseller') {
            //debit account
            $rcobj = new ScUsersCreditData;
            $newavcredits = $rcobj->doCreditTrans('debit', $upline_id, $rid, $credits);
            //credit log
            $lcobj = new ScLogsCredits;
            $lcobj->user_id = $upline_id;
            $lcobj->timestamp = date(Doo::conf()->date_format_db);
            $lcobj->amount = '-' . $credits;
            $lcobj->route_id = $rid;
            $lcobj->credits_before = $avcre;
            $lcobj->credits_after = $newavcredits;
            $lcobj->reference = 'Add User';
            $lcobj->comments = 'New account registered from App Website. LOGIN ID:' . '|| ' . $loginid;
            Doo::db()->insert($lcobj);
        }

        //save user permissions based on plan or default global permissions
        Doo::loadModel('ScUsersPermissions');
        $pobj = new ScUsersPermissions;
        $pobj->user_id = $uid;
        $pobj->perm_data = $permissions;
        Doo::db()->insert($pobj);

        //save user plan association
        if ($plan_id != 0) {
            //a plan was chosen for this account
            Doo::loadModel('ScUsersSmsPlans');
            $spobj = new ScUsersSmsPlans;
            $spobj->user_id = $uid;
            $spobj->plan_id = $plan_id;
            $spobj->subopt_idn = $ptype == 1 ? $_POST['plan_option'] : '';
            Doo::db()->insert($spobj);
        }

        //add invoice set status based on paid status
        Doo::loadModel('ScUsersDocuments');
        $dobj = new ScUsersDocuments;
        $dobj->filename = 'INVOICE_' . $loginid . '_' . time();
        $dobj->type = 1;
        $dobj->owner_id = $upline_id;
        $dobj->shared_with = $uid;
        $dobj->created_on = date(Doo::conf()->date_format_db);
        $dobj->file_data = serialize($invdata);
        $dobj->file_status = 0; //invoice is due
        $dobj->init_remarks = $invdata['inv_rem'];
        $inv_id = Doo::db()->insert($dobj);

        //add sender id approved
        $defSender = $signupdata['def_sender'] != '' ? $signupdata['def_sender'] : Doo::conf()->default_sender_id;

        Doo::loadModel('ScSenderId');
        $siobj = new ScSenderId;
        $siobj->sender_id = $defSender;
        $siobj->req_by = $uid;
        $siobj->status = 1;
        Doo::db()->insert($siobj);

        //credit transaction details
        $trandata['transac_id'] = $loginid . rand(0, 100) . time();
        $trandata['cdata'] = $rdata;
        $trandata['transac_by'] = $upline_id;
        $trandata['transac_to'] = $uid;
        $trandata['invoice_id'] = $inv_id;

        Doo::loadModel('ScUsersCreditTransactions');
        $tobj = new ScUsersCreditTransactions;
        $tobj->newTransaction('credit', $trandata);

        //add site data if reseller
        if ($cat == 'reseller') {
            Doo::loadModel('ScWebsites');
            $wobj = new ScWebsites;
            $wobj->user_id = $uid;
            $wobj->status = Doo::conf()->default_website_status;
            Doo::db()->insert($wobj);
        }
        //update stats table
        $totalsms = 0;
        foreach ($rdata as $rid => $rinfo) {
            $totalsms += $rinfo['credits'];
        }
        $uobj2 = new ScUsers;
        $uplinedata = $uobj2->getProfileInfo($upline_id, 'category');
        if ($uplinedata->category == 'admin') {
            $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
            $stobj->addStat(date('Y-m-d'), $totalsms);
        } else {
            $stobj = Doo::loadModel('ScStatsSalesReseller', true);
            $stobj->addStat(date('Y-m-d'), $upline_id, $totalsms, 1);
        }
        //send email n sms
        $notifdata = unserialize($sudata->notif_data);

        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        $osdata['system'] = $browser['platform'];
        $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
        $osdata['city'] = $browser['city'];
        $osdata['country'] = $browser['country'];
        $osdata['lat'] = $browser['lat'];
        $osdata['lon'] = $browser['lon'];
        //it is better to forward this responsibility to the hypernode as there we have a hyperLog system in place
        $userdata = array(
            "mode" => "new_user_registration",
            "data" => array(
                "user_id" => $uid,
                "incidentPlatform" => $osdata,
                "incidentDateTime" => date(Doo::conf()->date_format_db),
                "userEmailFlag" => $notifdata['email'],
                "userSmsFlag" => $notifdata['sms'],
                "accountType" => $cat,
                "accountLink" => Doo::conf()->APP_URL . 'viewUserAccount/' . $uid,
                "domain" => Doo::conf()->APP_URL
            )
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Doo::conf()->API_URL . 'hypernode/log/add');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Ignore SSL host verification
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        //even if there is an error on this event this wont crash the app like it has been doing for a longest time

        $redtologin = 1;
        //check if Paypal settings entered
        // $usetobj = Doo::loadModel('ScUsersCompany', true);
        // $usetobj->user_id = $upline_id;
        // $usetdata = Doo::db()->find($usetobj,array('select'=>'c_paypal','limit'=>1));
        // $usetar = unserialize($usetdata->c_paypal);
        // if(!isset($usetar['clientid']) || !isset($usetar['authkey'])){
        //     //paypal payments not enabled
        //     $redtologin=1;
        // }
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Your account has been created successfully. Please check your email for Login details.';

        if ($redtologin == 1) {
            //else return to login page
            return Doo::conf()->APP_URL . 'web/sign-in';
        } else {
            $paypaldata['userid'] = $uid;
            $paypaldata['invoiceid'] = $inv_id;
            return Doo::conf()->APP_URL . 'scProcessPayment/' . base64_encode(serialize($paypaldata));
        }
    }

    public function scProcessPayment()
    {
        session_start();
        //this function generates a Paypal link for payment
        $paypaldata = unserialize(base64_decode($this->params['data']));

        //any notification
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        Doo::loadModel('ScWebsitesPageData');
        $pgobj = new ScWebsitesPageData;
        $pgobj->site_id = $_SESSION['webfront']['id'];
        $pgobj->user_id = $_SESSION['webfront']['owner'];
        $pgobj->page_type = 'LOGIN';

        $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));

        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['udata'] = $uobj->getProfileInfo($paypaldata['userid']);

        //get invoice details
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $paypaldata['invoiceid'];
        $data['docdata'] = Doo::db()->find($docobj, array('limit' => 1));

        //load Paypal class
        $usetobj = Doo::loadModel('ScUsersCompany', true);
        $usetobj->user_id = $_SESSION['webfront']['owner'];
        $usetdata = Doo::db()->find($usetobj, array('select' => 'c_paypal', 'limit' => 1));
        $usetar = unserialize($usetdata->c_paypal);

        $clientid = $usetar['clientid'];
        $secret = $usetar['authkey'];
        Doo::loadHelper('DooPaypalCheckout');
        $paypalobj = new DooPaypalCheckout($clientid, $secret);

        $data['paypal']['env'] = $paypalobj->paypalEnv;
        $data['paypal']['clientid'] = $paypalobj->paypalClientID;


        //render
        $data['mode'] = 'paypal';
        $data['paypaldata'] = $paypaldata;
        if ($_SESSION['webfront']['owner'] == 1) {
            $this->view()->renderc('outer/mainSignup' . Doo::conf()->custom_login_view, $data);
        } else {
            $this->view()->renderc('outer/mainSignup', $data);
        }
    }

    public function scPaymentReturn()
    {
        session_start();
        //collect values
        $paymentID = $_GET['paymentID'];
        $token = $_GET['token'];
        $payerID = $_GET['payerID'];
        $inv_id = $_GET['invid'];

        //get invoice data
        Doo::loadModel('ScUsersDocuments');
        $docobj = new ScUsersDocuments;
        $docobj->id = $inv_id;
        $docdata = Doo::db()->find($docobj, array('limit' => 1));

        //validate
        $usetobj = Doo::loadModel('ScUsersCompany', true);
        $usetobj->user_id = $docdata->owner_id;
        $usetdata = Doo::db()->find($usetobj, array('select' => 'c_paypal', 'limit' => 1));
        $usetar = unserialize($usetdata->c_paypal);

        $clientid = $usetar['clientid'];
        $secret = $usetar['authkey'];

        Doo::loadHelper('DooPaypalCheckout');
        $paypalobj = new DooPaypalCheckout($clientid, $secret);

        $paymentCheck = $paypalobj->validate($paymentID, $token, $payerID, $inv_id);

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

            //add document remark from payer with transaction details
            $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
            $drobj->user_id = $docdata->shared_with; //user id of payer
            $drobj->file_id = $inv_id;
            $drobj->remark_text = '[AUTO-GENERATED] Paypal payment was made for ' . $currency . number_format($paidAmount, 2) . ' with Transaction ID ' . $id;
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

            //log event
            $actData['activity_type'] = 'PAYPAL PAYMENT';
            $actData['activity'] = Doo::conf()->user_payment_paypal . strtoupper($id);
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($docdata->shared_with, $actData);

            //send email to reg user
            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->user_id = $payerID;
            $user = Doo::db()->find($uobj, array('limit' => 1, 'select' => 'name, email, mobile, upline_id'));

            $cdata = unserialize($_SESSION['webfront']['company_data']);

            $maildata['company_url'] = Doo::conf()->APP_URL;
            $maildata['logo'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'];
            $maildata['name'] = $user->name;
            $maildata['helpline'] = $cdata['helpline'];
            $maildata['company_domain'] = $_SESSION['webfront']['current_domain'];

            $maildata['payment_status'] = $state;
            $maildata['invoice_id'] = $inv_id;
            $maildata['amount_paid'] = $currency . ' ' . $paidAmount;
            $maildata['paypal_txnid'] = $id;
            $maildata['payer'] = $payerEmail;

            $mailbody = $this->view()->getRendered('mail/paypalConfirmation', $maildata);;
            Doo::loadHelper("DooPhpMailer");
            $mail = DooPhpMailer::getMailObj();

            $mail->setFrom($cdata['helpmail'], $cdata['company_name']);
            $mail->Subject  = $this->SCTEXT('Payment Received') . ' || ' . $cdata['company_name'] . ' SMS Portal';
            $mail->isHTML(true);
            $mail->Body = $mailbody;
            $mail->addAddress($user->email);
            $mail->send();
            $mail->clearAddresses();


            //send notification to upline
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert($_SESSION['webfront']['owner'], 'info', Doo::conf()->user_payment_paypal . $id, 'viewDocument/' . $inv_id);

            //redirect to login page
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Your payment has been processed. Login now and start your campaign.';
            return Doo::conf()->APP_URL . 'web/sign-in';
        } else {
            //payment not approved redirect to login page

            //log event
            $actData['activity_type'] = 'PAYPAL PAYMENT';
            $actData['activity'] = Doo::conf()->user_payment_paypal_fail . $inv_id;
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($docdata->shared_with, $actData);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Payment failed. Please login and try the payment again.';
            return Doo::conf()->APP_URL . 'web/sign-in';
        }
    }

    public function passwordReset()
    {
        //staff
        if ($_POST['cat'] == 'staff') {
            session_start();
            if ($_SESSION['user']['group'] == 'admin') {
                //validate id
                $sid = intval($_POST['user']);
                if ($sid > 0) {
                    //valid id

                    if (!DooTextHelper::verifyFormData('password', $_POST['pass1']) || $_POST['pass1'] != $_POST['pass2']) {
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = 'Invalid Password. Please see the instructions for password and make sure both passwords match.';
                        exit;
                    }

                    Doo::loadModel('ScUsers');
                    $uobj = new ScUsers;
                    $uobj->user_id = $sid;
                    $udata = Doo::db()->find($uobj, array('limit' => 1));

                    Doo::loadHelper('DooEncrypt');
                    $hfunck = base64_encode($udata->login_id . '_' . base64_encode('smppcubehash'));
                    $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);

                    //encrypt and save new password
                    $encpass = $encobj->encrypt($_POST['pass1'], $hfunck);

                    Doo::loadModel('ScUsers');
                    $uobj = new ScUsers;
                    $uobj->user_id = $sid;
                    $uobj->password = $encpass;
                    Doo::db()->update($uobj, array('limit' => 1));

                    //log event
                    $actData['activity_type'] = 'RESET PASSWORD';
                    $actData['activity'] = Doo::conf()->staff_reset_password . $udata->login_id;
                    $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                    $ulobj->addLog($_SESSION['user']['userid'], $actData);

                    //return
                    $_SESSION['notif_msg']['msg'] = 'Password successfully changed for the staff member';
                    $_SESSION['notif_msg']['type'] = 'success';
                    exit;
                } else {
                    //invalid id
                    $_SESSION['notif_msg']['msg'] = 'Invalid User ID';
                    $_SESSION['notif_msg']['type'] = 'error';
                    exit;
                }
            }
        }

        //user
        if ($_POST['cat'] == 'user') {
            session_start();
            if ($_SESSION['user']['group'] != 'client') {
                //validate id
                $uid = intval($_POST['user']);
                if ($uid > 0) {
                    //valid id

                    if (!DooTextHelper::verifyFormData('password', $_POST['pass1']) || $_POST['pass1'] != $_POST['pass2']) {
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = 'Invalid Password. Please see the instructions for password and make sure both passwords match.';
                        exit;
                    }

                    $uobj = Doo::loadModel('ScUsers', true);
                    $uobj->user_id = $uid;
                    $user = Doo::db()->find($uobj, array('limit' => 1, 'select' => 'login_id, password, upline_id'));

                    if ($_SESSION['user']['group'] == 'admin' || $user->upline_id == $_SESSION['user']['userid']) {

                        Doo::loadHelper('DooEncrypt');
                        $hfunck = base64_encode($user->login_id . '_' . base64_encode('smppcubehash'));
                        $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);

                        //encrypt and save new password
                        $encpass = $encobj->encrypt($_POST['pass1'], $hfunck);
                        $uobj->password = $encpass;
                        Doo::db()->update($uobj);

                        //log event for user
                        $actData['activity_type'] = 'RESET PASSWORD';
                        $actData['activity'] = Doo::conf()->reseller_reset_password;
                        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                        $ulobj->addLog($uid, $actData);


                        //return
                        $_SESSION['notif_msg']['msg'] = 'Password successfully changed for the user account';
                        $_SESSION['notif_msg']['type'] = 'success';
                        exit;
                    }
                } else {
                    //invalid id
                    $_SESSION['notif_msg']['msg'] = 'Invalid User ID';
                    $_SESSION['notif_msg']['type'] = 'error';
                    exit;
                }
            }
        }

        //user before login
        if ($_POST['cat'] == 'fp') {
            session_start();
            Doo::loadHelper('DooTextHelper');
            $email = $_POST['emailid'];
            if (!DooTextHelper::verifyFormData('email', $email)) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid email address.';
                return Doo::conf()->APP_URL . 'web/resetPassword';
            }

            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->email = $email;
            $user = Doo::db()->find($uobj, array('limit' => 1));
            $cdata = unserialize($_SESSION['webfront']['company_data']);

            if ($user->user_id) {
                //check if sending clear passwords is allowed
                $suobj = Doo::loadModel('ScWebsitesSignupSettings', true);
                $suobj->site_id = $_SESSION['webfront']['id'];
                $sudata = Doo::db()->find($suobj, array('limit' => 1, 'select' => 'notif_data'));
                $suset = unserialize($sudata->notif_data);
                Doo::loadHelper('DooOsInfo');
                $browser = DooOsInfo::getBrowser();
                $osdata['system'] = $browser['platform'];
                $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
                $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
                $osdata['city'] = $browser['city'];
                $osdata['country'] = $browser['country'];
                $osdata['lat'] = $browser['lat'];
                $osdata['lon'] = $browser['lon'];
                if ($suset['pass_flag'] == '1') {
                    //send password in email using hypernode
                    Doo::loadHelper('DooEncrypt');
                    $hfunck = base64_encode($user->login_id . '_' . base64_encode('smppcubehash'));
                    $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
                    $pass = $encobj->decrypt($user->password, $hfunck);

                    $userdata = array(
                        "mode" => "forget_password_email",
                        "data" => array(
                            "user_id" => $user->user_id,
                            "incidentPlatform" => $osdata,
                            "incidentDateTime" => date(Doo::conf()->date_format_db),
                            "password" => $pass,
                            "appLoginLink" => Doo::conf()->APP_URL . 'web/sign-in',
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
                    $_SESSION['notif_msg']['msg'] = 'Your account password has been sent to your email.';
                    $_SESSION['notif_msg']['type'] = 'success';
                    return Doo::conf()->APP_URL . 'web/resetPassword';
                } else {
                    //send otp and reset password
                    unset($_SESSION['verifiedUser']);
                    $reset_otp = rand(100000, 999999);

                    $userdata = array(
                        "mode" => "password_reset_otp",
                        "data" => array(
                            "user_id" => $user->user_id,
                            "platform_data" => $osdata,
                            "incidentDateTime" => date(Doo::conf()->date_format_db),
                            "otpCode" => $reset_otp,
                            "actionType" => "Password Reset",
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

                    $_SESSION['rpvars']['otp'] = $reset_otp;
                    $_SESSION['rpvars']['userid'] = $user->user_id;
                    $_SESSION['rpvars']['name'] = $user->name;
                    $_SESSION['rpvars']['avatar'] = $user->avatar;
                    $_SESSION['rpvars']['email'] = $email;
                    $_SESSION['notif_msg']['msg'] = 'One-time Password has been sent to your email.';
                    $_SESSION['notif_msg']['type'] = 'success';
                    return Doo::conf()->APP_URL . 'web/resetPasswordOtpVerify';
                }
            } else {
                //no user found
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'No user account associated with this email.';
                return Doo::conf()->APP_URL . 'web/resetPassword';
            }
        }
    }

    public function verifyResetPassOtp()
    {
        session_start();
        //get otp

        $otp = $_POST['rpotp'];
        if ($otp == '') {
            $_SESSION['notif_msg']['msg'] = 'OTP cannot be empty';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'web/resetPasswordOtpVerify';
        }
        //check against session stored var

        if ($otp != $_SESSION['rpvars']['otp']) {
            if (isset($_SESSION['rpvars']['otpAttempt'])) {
                $_SESSION['rpvars']['otpAttempt']++;
            } else {
                $_SESSION['rpvars']['otpAttempt'] = 1;
            }

            if ($_SESSION['rpvars']['otpAttempt'] > 5) {
                //someone is trying to break in
                //add an alert
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'warning', Doo::conf()->resetpass_without_otp . $_SESSION['rpvars']['userid'], 'securityLog');

                //log event
                $actData['activity_type'] = 'RESET PASSWORD';
                $actData['activity'] = Doo::conf()->resetpass_without_otp . $_SESSION['rpvars']['userid'];
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['rpvars']['userid'], $actData, 2);

                //redirect
                $_SESSION['notif_msg']['msg'] = 'OTP verification required.';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'web/resetPassword';
            }

            $_SESSION['notif_msg']['msg'] = 'OTP mismatch. Please try again';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'web/resetPasswordOtpVerify';
        } else {
            //otp matched
            $userid = $_SESSION['rpvars']['userid'];
            unset($_SESSION['rpvars']);

            //get user details and take to custom password reset page
            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->user_id = $userid;
            $uobj->status = 1;
            $user = Doo::db()->find($uobj, array('limit' => 1));

            if ($user->user_id) {
                //set the session with the user details
                $_SESSION['verifiedUser']['mode'] = 'verified';
                $_SESSION['verifiedUser']['userid'] = $user->user_id;
                $_SESSION['verifiedUser']['name'] = $user->name;
                $_SESSION['verifiedUser']['avatar'] = $user->avatar;
                $_SESSION['verifiedUser']['category'] = $user->category;
                $_SESSION['verifiedUser']['email'] = $user->email;
                //redirect to reset password page
                return Doo::conf()->APP_URL . 'web/resetPasswordOtpVerify';
            } else {
                //user not found or account is disabled
                $_SESSION['notif_msg']['msg'] = 'Account is deactivated at the moment. Please contact the Admin.';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'web/resetPassword';
            }
        }
    }

    public function resetOuterVerifiedPassword()
    {
        session_start();
        //check if user is verified
        if ($_SESSION['verifiedUser']['mode'] == 'verified') {
            //reset password
            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->user_id = $_SESSION['verifiedUser']['userid'];
            $user = Doo::db()->find($uobj, array('limit' => 1, 'select' => 'login_id, name, email, mobile'));

            if ($user->login_id) {
                Doo::loadHelper('DooEncrypt');
                $hfunck = base64_encode($user->login_id . '_' . base64_encode('smppcubehash'));
                $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);

                $encpass = $encobj->encrypt($_POST['newpass1'], $hfunck);
                $uobj->password = $encpass;
                Doo::db()->update($uobj);

                //log user activity
                $actData['activity_type'] = 'RESET PASSWORD';
                $actData['activity'] = Doo::conf()->user_reset_password;

                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['verifiedUser']['userid'], $actData);

                //send sms
                $siteid = $_SESSION['webfront']['id'];

                $suobj = Doo::loadModel('ScWebsitesSignupSettings', true);
                $suobj->site_id = $siteid;
                $sudata = Doo::db()->find($suobj, array('limit' => 1, 'select' => 'notif_data'));
                $notifdata = unserialize($sudata->notif_data);
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
                $api_key = $akobj->getApiKey($_SESSION['webfront']['owner']);
                $sms = "Your password has been reset successfully.";
                $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . intval($cmpobj->getCampaignId($_SESSION['webfront']['owner'], 'system')) . '&routeid=' . $notifdata['sms_route'] . '&type=text&contacts=' . $user->mobile . '&senderid=' . $notifdata['sms_sid'] . '&msg=' . urlencode($sms);

                //Submit to server
                $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));

                //unset sessions
                unset($_SESSION['verifiedUser']);
                unset($_SESSION['rpvars']);

                //redirect to login page
                $_SESSION['notif_msg']['type'] = 'success';
                $_SESSION['notif_msg']['msg'] = 'Password changed successfully.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            } else {
                //invalid user
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'web/sign-in';
            }
        } else {
            //somebody trying to break in
            $actData['activity_type'] = 'RESET PASSWORD';
            $actData['activity'] = Doo::conf()->reset_without_verify;

            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog(intval($_SESSION['verifiedUser']['userid']), $actData, 2);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'warning', Doo::conf()->reset_without_verify, 'securityLogs');

            //redirect
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Session. Please try again.';
            return Doo::conf()->APP_URL . 'web/sign-in';
        }
    }

    //4. Dashboard
    public function dashboard()
    {
        session_start();

        if (isset($_SESSION['user']) && !empty($_SESSION['user']['userid'])) {

            //check user type
            $uid = $_SESSION['user']['userid'];
            $group = $_SESSION['user']['group'];
            $subgrp = $_SESSION['user']['subgroup'];

            if ($_SESSION['user']['group'] == 'admin') {

                if ($_SESSION['user']['subgroup'] == 'admin') {
                    //admin login
                    Doo::loadHelper('DooOsInfo');
                    $obj = new DooOsInfo;

                    //get RAM info
                    $str = @file_get_contents("/proc/meminfo");
                    $data = explode("\n", $str);

                    $meminfo = [];
                    if (sizeof($data) > 0) {
                        foreach ($data as $line) {
                            $parts = explode(":", $line);
                            $meminfo[$parts[0]] = isset($parts[1]) ? trim($parts[1]) : '';
                        }
                    }

                    if (sizeof($meminfo) > 1) {
                        $total_ram_kb = intval($meminfo['MemTotal']);
                        $free_ram_kb = intval($meminfo['MemAvailable']);
                        $used_ram_kb = $total_ram_kb - $free_ram_kb;
                        $ram = $used_ram_kb / $total_ram_kb;
                        $ram_per = round($ram * 100);

                        $total_ram = $obj->formatBytes($total_ram_kb * 1000);
                        $used_ram = $obj->formatBytes($used_ram_kb * 1000);
                    } else {
                        $total_ram = 0;
                        $used_ram = 0;
                        $ram = 0;
                        $ram_per = 0;
                    }
                    //get hard disk space info
                    $total_ds = disk_total_space("/");
                    $free_ds = disk_free_space("/");
                    $used_ds = $total_ds - $free_ds;
                    $ds = $used_ds / $total_ds;
                    $ds_per = round($ds * 100);

                    //fill color
                    if ($ram_per <= 60) {
                        $ramfill = '&quot;color&quot;: &quot;#10c469&quot;';
                        $ramef = 'rgba(16, 196, 105,.3)';
                    } else if ($ram_per > 60 && $ram_per <= 80) {
                        $ramfill = '&quot;color&quot;: &quot;#188ae2&quot;';
                        $ramef = 'rgba(24, 138, 226, .3)';
                    } else if ($ram_per > 80) {
                        $ramfill = '&quot;color&quot;: &quot;#ff5b5b&quot;';
                        $ramef = 'rgba(255, 91, 91, .3)';
                    }

                    if ($ds_per <= 60) {
                        $dsfill = '&quot;color&quot;: &quot;#10c469&quot;';
                        $dsef = 'rgba(16, 196, 105,.3)';
                    } else if ($ds_per > 60 && $ds_per <= 80) {
                        $dsfill = '&quot;color&quot;: &quot;#188ae2&quot;';
                        $dsef = 'rgba(24, 138, 226, .3)';
                    } else if ($ds_per > 80) {
                        $dsfill = '&quot;color&quot;: &quot;#ff5b5b&quot;';
                        $dsef = 'rgba(255, 91, 91, .3)';
                    }

                    //prepare response;
                    $res['total_ram'] = $total_ram;
                    $res['used_ram'] = $used_ram;
                    $res['ram_ratio'] = $ram;
                    $res['ram_per'] = $ram_per;
                    $res['ram_fillcol'] = $ramfill;
                    $res['ram_ef'] = $ramef;

                    $res['total_ds'] = $obj->formatBytes($total_ds);
                    $res['used_ds'] = $obj->formatBytes($used_ds);
                    $res['ds_ratio'] = $ds;
                    $res['ds_per'] = $ds_per;
                    $res['ds_fillcol'] = $dsfill;
                    $res['ds_ef'] = $dsef;

                    $data['current_page'] = 'admin_dashboard';
                    $data['hldata'] = $res;
                }
                if ($_SESSION['user']['subgroup'] == 'staff') {
                    //staff login
                    $data['current_page'] = 'reseller_dashboard';
                }
                //load all smpp, routes and user data
                $rt_query = "SELECT id, title FROM sc_sms_routes";
                $data['all_routes'] = Doo::db()->fetchAll($rt_query, null, PDO::FETCH_KEY_PAIR);
                $smpp_query = "SELECT smsc_id, CONCAT_WS('|', title, provider) as smppdata FROM sc_smpp_accounts";
                $data['all_smpp'] = Doo::db()->fetchAll($smpp_query, null, PDO::FETCH_KEY_PAIR);
                $usr_query = "SELECT user_id, CONCAT_WS('|', name, category, email, avatar) as usrdata FROM sc_users";
                $data['all_users'] = Doo::db()->fetchAll($usr_query, null, PDO::FETCH_KEY_PAIR);
            }

            if ($_SESSION['user']['group'] == 'reseller') {
                $usr_query = "SELECT user_id, CONCAT_WS('|', name, category, email, avatar) as usrdata FROM sc_users WHERE upline_id =" . intval($_SESSION['user']['userid']);
                $data['all_users'] = Doo::db()->fetchAll($usr_query, null, PDO::FETCH_KEY_PAIR);
                $data['current_page'] = 'reseller_dashboard';
            }
            if ($_SESSION['user']['group'] == 'client') {
                $data['account_type'] = $_SESSION['user']['account_type'];
                $data['current_page'] = 'client_dashboard';
            }

            //check for notifs, announcements etc

            //notifications
            $nobj = Doo::loadModel('ScUserNotifications', true);
            $nobj->user_id = $_SESSION['user']['userid'];
            $nobj->status = 0;
            $ndata = Doo::db()->find($nobj);

            $str = '';

            foreach ($ndata as $nt) {
                $link = $nt->link_to == '' ? Doo::conf()->APP_URL . 'viewNotifications' : Doo::conf()->APP_URL . $nt->link_to;
                switch ($nt->type) {
                    case 'info':
                        $icon = 'fa-info-circle';
                        break;
                    case 'success':
                        $icon = 'fa-check-circle';
                        break;
                    case 'danger':
                        $icon = 'fa-exclamation-triangle';
                        break;
                    case 'warning':
                        $icon = 'fa-exclamation-triangle';
                        break;
                }
                if (strpos($nt->notif_text, '||')) {
                    $ntftxtar = explode("||", $nt->notif_text);
                    $notif_text = $this->SCTEXT(trim($ntftxtar[0])) . ' ' . $ntftxtar[1];
                } else {
                    $notif_text = $this->SCTEXT($nt->notif_text);
                }
                $str .= '<div class="albox media list-group m-h-0" data-nid="' . $nt->id . '" data-redirect="' . base64_encode($link) . '">
                                    <div class="media-left p-t-sm p-l-sm text-dark m-b-0">
                                        <i class="fa fa-3x text-' . $nt->type . ' ' . $icon . '"></i>
                                    </div>
                                    <div class="media-body">
                                        <p class="fz-sm p-sm text-dark m-b-0"> ' . $notif_text . '</p>
                                    </div>

                                </div>
                                ';
            }

            $finalstr = $str == '' ? '<div class="list-group m-b-sm p-t-sm"><p class="text-dark text-center">- ' . $this->SCTEXT('No New Notifications') . ' -</p></div>' : $str;

            $_SESSION['alerts']['count'] = sizeof($ndata);
            $_SESSION['alerts']['content'] = $finalstr;
            //announcements
            if ($_SESSION['user']['group'] != 'admin') {
                Doo::loadModel('ScAnnouncements');
                $anobj = new ScAnnouncements;
                if ($_SESSION['user']['group'] == 'reseller') {
                    $data['current_page'] = 'reseller_dashboard';
                    $whr = "show_to IN(1,2)";
                }
                if ($_SESSION['user']['group'] == 'client') {
                    $data['current_page'] = 'client_dashboard';
                    $whr = "show_to IN(1,3)";
                }
                $anobj->status = 1;
                $opt['where'] = $whr;
                $opt['select'] = 'id,msg,type';
                $opt['desc'] = 'last_updated';
                $data['announcements'] = Doo::db()->find($anobj, $opt);
            }

            //check if subscription validity expiring soon

            //based on user type load dashboard data
            $pagedir = $_SESSION['user']['subgroup'] == 'staff' ? 'reseller' : $_SESSION['user']['subgroup'];

            session_write_close();

            //render view
            $data['page'] = 'Dashboard';
            $data['username'] = $_SESSION['user']['name'];
            $data['baseurl'] = Doo::conf()->APP_URL;
            $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
            $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
            $this->view()->renderc($pagedir . '/page', $data);
            $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
            exit;
        } else {
            return Doo::conf()->APP_URL . 'expired';
        }
    }


    //5. Misc Tasks

    public function generateDateExample()
    {
        $dformat = $_POST['dtype'];
        echo $dformat != '' ? 'date will be shown as: <b>' . date($dformat) . '</b>' : $this->SCTEXT('enter valid date format');
        exit;
    }

    public function getScText()
    {
        session_start();
        if ($_SESSION['APP_LANG'] != 'en') {
            include './protected/plugin/lang/' . $_SESSION['APP_LANG'] . '.lang.php';
            echo json_encode($lang);
            exit;
        } else {
            echo 'en';
            exit;
        }
    }

    public function checkAvailability()
    {
        //email
        if ($_POST['mode'] == 'email') {
            if ($_POST['page'] == 'editprofile') {
                session_start();
                if ($_SESSION['user']['email'] == $_POST['value']) {
                    //on edit profile page user has not changed the email
                    echo 'TRUE';
                    exit;
                } else {
                    //check through DB
                    Doo::loadModel('ScUsers');
                    $obj = new ScUsers;
                    $obj->email = $_POST['value'];
                    $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'user_id'));
                    if ($rs->user_id) {
                        echo 'FALSE';
                    } else {
                        echo 'TRUE';
                    }
                }
            } else {
                //signup case, look through DB
                Doo::loadModel('ScUsers');
                $obj = new ScUsers;
                $obj->email = $_POST['value'];
                $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'user_id'));
                if ($rs->user_id) {
                    echo 'FALSE';
                } else {
                    echo 'TRUE';
                }
            }
        }
        //phone
        if ($_POST['mode'] == 'mobile') {
            if ($_POST['page'] == 'editprofile') {
                session_start();
                if ($_SESSION['user']['mobile'] == $_POST['value']) {
                    //on edit profile page user has not changed the email
                    echo 'TRUE';
                    exit;
                } else {
                    //check through DB
                    Doo::loadModel('ScUsers');
                    $obj = new ScUsers;
                    $obj->mobile = $_POST['value'];
                    $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'user_id'));
                    if ($rs->user_id) {
                        echo 'FALSE';
                    } else {
                        echo 'TRUE';
                    }
                }
            } else {
                //sign up, look through DB
                Doo::loadModel('ScUsers');
                $obj = new ScUsers;
                $obj->mobile = $_POST['value'];
                $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'user_id'));
                if ($rs->user_id) {
                    echo 'FALSE';
                } else {
                    echo 'TRUE';
                }
            }
        }
        //login id
        if ($_POST['mode'] == 'login') {
            Doo::loadModel('ScUsers');
            $obj = new ScUsers;
            $obj->login_id = strtolower($_POST['value']);
            $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'user_id'));
            if ($rs->user_id) {
                echo 'FALSE';
            } else {
                echo 'TRUE';
            }
        }

        //system id
        if ($_POST['mode'] == 'systemid') {
            $obj = Doo::loadModel('ScSmppClients', true);
            $obj->system_id = strtolower($_POST['value']);
            $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id'));
            if ($rs->id) {
                echo 'FALSE';
            } else {
                echo 'TRUE';
            }
        }
    }

    public function tinyUrlProcess()
    {
        $urlidf = $this->params['tinyurl'];
        if ($urlidf == '') {
            die('MISSING URL PARAMETERS');
        }
        if (strlen($urlidf) > 6) {
            //personalized link
            $sobj = Doo::loadModel('ScShortUrlsMsisdnMap', true);
            $sobj->url_idf = $urlidf;
            $urlinfo = Doo::db()->find($sobj, array('limit' => 1));

            if ($urlinfo->id) {
                //update sent sms field
                $smsobj = Doo::loadModel('ScSentSms', true);
                $smsobj->sms_shoot_id = $urlinfo->sms_shoot_id;
                $smsobj->mobile = $urlinfo->mobile;
                $rs = Doo::db()->find($smsobj, array('select' => 'id', 'limit' => 1));
                if ($rs->id) {
                    Doo::loadHelper('DooOsInfo');
                    $browser = DooOsInfo::getBrowser();
                    // prevent crawlers
                    if ($browser['platform'] != "") {
                        $osdata['system'] = $browser['platform'];
                        $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
                        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
                        $osdata['city'] = $browser['city'];
                        $osdata['country'] = $browser['country'];
                        $osdata['lat'] = $browser['lat'];
                        $osdata['lon'] = $browser['lon'];

                        $smsobj->id = $rs->id;
                        $smsobj->url_visit_flag = 1;
                        $smsobj->url_visit_ts = date(Doo::conf()->date_format_db);
                        $smsobj->url_visit_platform = json_encode($osdata);
                        Doo::db()->update($smsobj, array('limit' => 1));
                        //update map table
                        $sobj->id = $urlinfo->id;
                        $sobj->visited_on = date(Doo::conf()->date_format_db);
                        Doo::db()->update($sobj, array('limit' => 1));
                        //redirect
                        $mobj = Doo::loadModel('ScShortUrlsMaster', true);
                        $mobj->id = $urlinfo->parent_url_id;
                        $urldata = Doo::db()->find($mobj, array('select' => 'redirect_url', 'limit' => 1));
                    }
                }
                header("location:" . $urldata->redirect_url);
                exit;
            } else {
                //url not found
                echo 'URL EXPIRED';
                exit;
            }
        } else {
            //regular link, simply redirect
            $mobj = Doo::loadModel('ScShortUrlsMaster', true);
            $mobj->url_idf = $urlidf;
            $urldata = Doo::db()->find($mobj, array('select' => 'redirect_url', 'limit' => 1));
            //echo '<pre>';var_dump($urldata->redirect_url);die;
            //redirect
            header("location:" . $urldata->redirect_url);
            exit;
        }
    }

    public function legacyRedirect()
    {
        return Doo::conf()->APP_URL;
    }

    public function renderTest()
    {
        $data['name'] = 'Sam';
        $this->view()->renderc('mail/dailyReports', $data);
    }

    public function disabledSite()
    {
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc('outer/disabled', $data);
    }

    public function allurl()
    {
        Doo::loadCore('app/DooSiteMagic');
        DooSiteMagic::showAllUrl();
    }

    public function debug()
    {
        Doo::loadCore('app/DooSiteMagic');
        DooSiteMagic::showDebug($this->params['filename']);
    }

    public function gen_sitemap_controller()
    {
        //This will replace the routes.conf.php file
        Doo::loadCore('app/DooSiteMagic');
        DooSiteMagic::buildSitemap(true);
        DooSiteMagic::buildSite();
    }

    public function gen_sitemap()
    {
        //This will write a new file,  routes2.conf.php file
        Doo::loadCore('app/DooSiteMagic');
        DooSiteMagic::buildSitemap();
    }

    public function gen_site()
    {
        Doo::loadCore('app/DooSiteMagic');
        DooSiteMagic::buildSite();
    }

    public function gen_model()
    {
        Doo::loadCore('db/DooModelGen');
        DooModelGen::genMySQL();
    }

    public function unserializeUtility()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $mode = $data['mode'];
        if ($mode == 'single') {
            echo json_encode(unserialize($data['data']));
            exit;
        }
        if ($mode == 'bulk') {
            $result = array();
            foreach ($data['data'] as $str) {
                array_push($result, json_encode(unserialize($str)));
            }
            echo json_encode($result);
            exit;
        }
    }

    public function encryptData()
    {
        session_start();
        if ($_REQUEST['mode'] == 'payment') {
            $paymentdata['invoiceid'] = $_REQUEST['invoiceid'];
            $paymentdata['walletflag'] = $_REQUEST['walletflag'];
            $paymentdata['returntoinvoice'] = $_REQUEST['returntoinvoice'];
            Doo::loadHelper('DooEncrypt');
            $hfunck = base64_encode(session_id() . '_' . base64_encode('smppcubehash'));
            $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
            $link = $encobj->encrypt(serialize($paymentdata), $hfunck);
            echo base64_encode($link);
            exit;
        }
    }

    public function getNdncCodes()
    {
        $dlrcodeqry = "SELECT dlr_code FROM `sc_routes_custom_dlr_codes` WHERE description REGEXP 'DND|NDNC'";
        $codedata = Doo::db()->fetchAll($dlrcodeqry, null, PDO::FETCH_COLUMN);
        $codes = sizeof($codedata) > 0 ? implode(",", $codedata) : '';
        echo $codes;
        exit;
    }

    public function getCallbackConfigData()
    {
        $param = $this->params['var'];
        if ($param == 'mode') {
            echo Doo::conf()->dlr_callback_mechanism;
            exit;
        }
        if ($param == 'retry') {
            echo Doo::conf()->dlr_callback_retry;
            exit;
        }
    }

    public function finishWabaOnboarding()
    {
        $token = $this->params['tok'];

        $access_token = file_get_contents("https://graph.facebook.com/v17.0/oauth/access_token?client_id=" . Doo::conf()->wba_app_id . "&client_secret=" . Doo::conf()->wba_client_secret . "&code=$token");
        $myfile = fopen('/var/www/html/' . strtotime(date('Y-m-d H:i:s')) . '-WBA' . '.txt', "w");
        fwrite($myfile, $token . ' - ' . $access_token);
        fclose($myfile);
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'WhatsApp Business Acoount linked Succesfully. Wait while we review your account.';


        //else return to login page
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
        if (isset($lang[trim(strtolower($str))])) {
            return $lang[trim(strtolower($str))];
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
            $lang[trim(strtolower($str))] = ucfirst($result['text']);

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
