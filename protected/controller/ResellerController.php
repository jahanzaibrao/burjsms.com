<?php

/**
 * ResellerController
 *
 * @author saurav
 *
 * 0. Dashboard functions
 * 1. Manage Users
 * 2. Website Management
 * 3. Support Management
 *
 */

use Google\Cloud\Translate\V2\TranslateClient;

class ResellerController extends DooController
{
    public function __construct()
    {
        session_start();
        Doo::loadHelper('DooSmppcubeHelper');
        if (!$_SESSION['user'] || !$_SESSION['webfront'] || $_SESSION['user']['group'] == 'client') {
            throw new Exception();
        }
    }


    // 0. Dashboard Functions

    public function getResellerStats()
    {
        $first_day_month = date('Y-m-d', strtotime('first day of this month'));
        $first_day_week = date('Y-m-d', strtotime('last monday'));
        $last_tenth = date('Y-m-d', strtotime('today - 9 days'));
        $today = date('Y-m-d');
        $start = date('Y-m-d', strtotime('today - 30 days'));

        //get all data from last 30 days till today
        Doo::loadModel('ScStatsSalesReseller');
        $s_obj = new ScStatsSalesReseller;

        $s_data = $s_obj->getSalesDayWise($_SESSION['user']['userid'], $start, $today);

        $line_r = array();
        $line_r['dates'] = array();
        $line_r['sales'] = array();
        $line_r['signups'] = array();

        //array with this weeks sales
        $s_this_week = array();
        //array with this months sales
        $s_this_month = array();

        //array with this weeks signups
        $u_this_week = array();
        //array with this months signups
        $u_this_month = array();
        //total sales in this week
        $s_total_week = 0;
        //total sales in this month
        $s_total_month = 0;
        //total signups in this week
        $u_total_week = 0;
        //total signups in this month
        $u_total_month = 0;


        foreach ($s_data as $dt) {
            if ($dt->c_date == $last_tenth || strtotime($dt->c_date) > strtotime($last_tenth)) {
                array_push($line_r['dates'], date('Md', strtotime($dt->c_date)));
                array_push($line_r['sales'], intval($dt->sms_sold_today));
                array_push($line_r['signups'], intval($dt->new_users_today));
            }
            if ($dt->c_date == $first_day_week || strtotime($dt->c_date) > strtotime($first_day_week)) {
                $s_this_week["$dt->c_date"] = $dt->sms_sold_today;
                $u_this_week["$dt->c_date"] = $dt->new_users_today;
            }
            if ($dt->c_date == $first_day_month || strtotime($dt->c_date) > strtotime($first_day_month)) {
                $s_this_month["$dt->c_date"] = $dt->sms_sold_today;
                $u_this_month["$dt->c_date"] = $dt->new_users_today;
            }
        }

        //draw line even if empty
        if (sizeof($line_r['dates']) < 1) {
            $begin = new DateTime($last_tenth);
            $end = new DateTime($today);
            $end = $end->modify('+1 day');

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);

            foreach ($daterange as $date) {
                array_push($line_r['dates'], date('Md', strtotime($date->format("Y-m-d"))));
                array_push($line_r['sales'], 0);
                array_push($line_r['signups'], 0);
            }
        }

        $s_total_week = array_sum($s_this_week);
        $s_total_month = array_sum($s_this_month);
        $u_total_week = array_sum($u_this_week);
        $u_total_month = array_sum($u_this_month);

        $resp['line_r'] = $line_r;
        $resp['s_this_week'] = $s_this_week;
        $resp['u_this_week'] = $u_this_week;
        $resp['s_this_month'] = $s_this_month;
        $resp['u_this_month'] = $u_this_month;
        $resp['s_total_week'] = $s_total_week;
        $resp['s_total_month'] = $s_total_month;
        $resp['u_total_week'] = $u_total_week;
        $resp['u_total_month'] = $u_total_month;

        echo json_encode($resp);
        exit;
    }

    public function getResellerSales()
    {

        $dr = $this->params['dr'];
        $uid = $_SESSION['user']['userid'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "reseller_id = $uid AND c_date = '$from'";
            } else {
                $sWhere = "reseller_id = $uid AND c_date BETWEEN '$from' AND '$to'";
            }
        } else {
            $to = date('Y-m-d');
            $from = date('Y-m-d', strtotime('today - 9 days'));
            $sWhere = "reseller_id = $uid AND c_date BETWEEN '$from' AND '$to'";
        }
        $last_tenth = date('Y-m-d', strtotime('today - 9 days'));
        //get all data from last 30 days till today
        $stobj = Doo::loadModel('ScStatsSalesReseller', true);
        $stdata = Doo::db()->find($stobj, array('where' => $sWhere));

        $line_r = array();
        $line_r['dates'] = array();
        $line_r['sales'] = array();
        $line_r['signups'] = array();


        foreach ($stdata as $dt) {
            if ($dt->c_date == $last_tenth || strtotime($dt->c_date) > strtotime($last_tenth)) {
                array_push($line_r['dates'], date('Md', strtotime($dt->c_date)));
                array_push($line_r['sales'], intval($dt->sms_sold_today));
                array_push($line_r['signups'], intval($dt->new_users_today));
            }
        }

        //draw line even if empty
        if (sizeof($line_r['dates']) < 1) {
            $begin = new DateTime($from);
            $end = new DateTime($to);
            $end = $end->modify('+1 day');

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);

            foreach ($daterange as $date) {
                array_push($line_r['dates'], date('Md', strtotime($date->format("Y-m-d"))));
                array_push($line_r['sales'], 0);
                array_push($line_r['signups'], 0);
            }
        }

        $resp['line_r'] = $line_r;

        echo json_encode($resp);
        exit;
    }

    public function getLatestOrders()
    {
        $dates = $this->params['dr'];
        $limit = $this->params['limit'];
        if (!$dates) {
            $dates = 'Select Date';
        }
        if (!$limit) {
            $limit = '0,4';
        }
        if ($_SESSION['user']['group'] == 'admin') {
            //admin, get top orders by invoice amount, check wallet transactions
            Doo::loadModel('ScUsersWalletTransactions');
            $obj = new ScUsersWalletTransactions;

            $data = $obj->getOrdersByDate($dates, $limit);

            $str = '';
            $more = 1;

            Doo::loadModel('ScUsers');
            $obj2 = new ScUsers;

            $wlobj = Doo::loadModel('ScUsersWallet', true);

            //prepare the data
            foreach ($data as $dt) {
                $wlobj->id = $dt->wallet_id;
                $wldata = Doo::db()->find($wlobj, array('limit' => 1));
                $user = $obj2->getProfileInfo($wldata->user_id);
                $str .= '<div class="media-group-item">
                                            <div class="media col-md-6 col-sm-6 col-xs-6">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $user->user_id . '"><img src="' . $user->avatar . '" alt=""></a></div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $user->user_id . '" class="m-r-xs theme-color">' . ucwords($user->name) . '</a><small class="text-muted fz-sm">' . ucwords($user->category) . '</small></h5>
                                                    <p style="font-size: 12px;font-style: Italic;">' . $user->email . '</p>
                                                </div>
                                            </div>

                                            <div class="text-right col-md-6 col-sm-6 col-xs-6">

                                                    <h5 class="m-t-0 label label-success">' . Doo::conf()->currency . number_format($dt->amount, 2) . '</h5>
                                                    <p style="font-size: 12px;margin-top:3px;">on ' . date('dS M Y', strtotime($dt->t_date)) . '</p>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>';
            }

            if ($str == '') {

                $str = '<div align="center">' . $this->SCTEXT('No recent sales to show') . '</div>';
                $limit = '0,4';
                $more = 0;
            } else {
                //increase the limit
                $ctr = intval(substr($limit, 0, 1));
                $ctr = $ctr + 4;
                $limit = $ctr . ',4';
                //send the count
                $count = sizeof($data);
            }

            //prepare response
            $res['str'] = $str;
            $res['limit'] = $limit;
            $res['more'] = $more;
            $res['rows'] = $count;

            echo json_encode($res);
            exit;
        } else {
            //reseller, get top orders from credit transactions table
            Doo::loadModel('ScUsersCreditTransactions');
            $obj = new ScUsersCreditTransactions;

            $data = $obj->getOrdersByDate($dates, $limit, $_SESSION['user']['userid']);

            $str = '';
            $more = 1;

            Doo::loadModel('ScUsers');
            $obj2 = new ScUsers;

            //prepare the data
            foreach ($data as $dt) {
                $user = $obj2->getProfileInfo($dt->transac_to_user);
                $str .= '<div class="media-group-item">
                                            <div class="media col-md-6 col-sm-6 col-xs-6">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $user->user_id . '"><img src="' . $user->avatar . '" alt=""></a></div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $user->user_id . '" class="m-r-xs theme-color">' . ucwords($user->name) . '</a><small class="text-muted fz-sm">' . ucwords($user->category) . '</small></h5>
                                                    <p style="font-size: 12px;font-style: Italic;">' . $user->email . '</p>
                                                </div>
                                            </div>

                                            <div class="text-right col-md-6 col-sm-6 col-xs-6">

                                                    <h5 class="m-t-0 label label-success">' . number_format($dt->credits) . ' SMS</h5>
                                                    <p style="font-size: 12px;margin-top:3px;">on ' . date('dS M Y', strtotime($dt->transac_date)) . '</p>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>';
            }

            if ($str == '') {

                $str = '<div align="center">' . $this->SCTEXT('No recent sales to show') . '</div>';
                $limit = '0,4';
                $more = 0;
            } else {
                //increase the limit
                $ctr = intval(substr($limit, 0, 1));
                $ctr = $ctr + 4;
                $limit = $ctr . ',4';
                //send the count
                $count = sizeof($data);
            }

            //prepare response
            $res['str'] = $str;
            $res['limit'] = $limit;
            $res['more'] = $more;
            $res['rows'] = $count;

            echo json_encode($res);
            exit;
        }
    }



    // 1. Manage Users


    public function manageUsers()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['User Mgmt.'] = 'javascript:void(0);';
        $data['active_page'] = 'Active Users';

        $data['page'] = 'User Management';
        $data['current_page'] = 'manage_users';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/manageUsers', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllUsers()
    {

        $columns = array(
            array('db' => 'name', 'dt' => 0),
            array('db' => 'login_id', 'dt' => 1),
            array('db' => 'mobile', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);


        Doo::loadModel('ScUsers');
        $obj = new ScUsers;
        $uobj = new ScUsers;

        $uid = $_SESSION['user']['userid'];
        if ($_SESSION['user']['subgroup'] == 'reseller') {
            $sWhere = "upline_id = $uid AND category <> 'admin' AND status=1";
        } elseif ($_SESSION['user']['subgroup'] == 'staff') {
            $downline_flag = intval($_REQUEST['flag']);
            $sWhere = $downline_flag == 0 ? "(acc_mgr_id = $uid OR upline_id = $uid) AND category <> 'admin' AND status=1" : "upline_id = $uid AND category <> 'admin' AND status=1";
        } elseif ($_SESSION['user']['subgroup'] == 'admin') {
            $downline_flag = intval($_REQUEST['flag']);
            $sWhere = $downline_flag == 0 ? "category <> 'admin' AND status=1" : "category <> 'admin' AND status=1 AND upline_id=1";
        } else {
            //uh oh log this
            $actData['activity_type'] = 'VIEW USER';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| PAGE: User Management';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);
            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->Doo::conf()->unauthorized_user_access . '|| PAGE: User Management', 'viewUserAccount/' . $_SESSION['user']['userid']);
            //return
            exit;
        }


        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND' . $dtdata['where'];
            }
        }

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'user_id';
        }

        $total = Doo::db()->find($obj, array('select' => 'count(`user_id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $users = Doo::db()->find($obj, $dtdata);

        Doo::loadModel('ScUsersCreditData');
        $creobj = new ScUsersCreditData;

        Doo::loadModel('ScSmsRoutes');
        $robj = new ScSmsRoutes;

        Doo::loadHelper('DooEncrypt');

        $wobj = Doo::loadModel('ScUsersWallet', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($users as $dt) {

            //email verified
            if ($dt->email_verified == '1') {
                $evef = '<i class="fa fa-check-circle fa-lg text-success pointer" title="' . $this->SCTEXT('Email Verified') . '"></i>';
            } else {
                $evef = '<i class="fa fa-exclamation-circle fa-lg text-warning pointer" title="' . $this->SCTEXT('Email Not Verified') . '"></i>';
            }
            //mobile verified
            if ($dt->email_verified == '1') {
                $mvef = '<i class="fa fa-check-circle fa-lg text-success pointer" title="' . $this->SCTEXT('Phone number Verified') . '"></i>';
            } else {
                $mvef = '<i class="fa fa-exclamation-circle fa-lg text-warning pointer" title="' . $this->SCTEXT('Phone number Not Verified') . '"></i>';
            }


            $ustr = '<div class="media-group-item" style="padding-top:0;padding-left:0;">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm m-r-xs avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '"><img src="' . $dt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-xs"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($dt->name) . '</a></h5>
                                                <p style="font-size: 12px; margin-bottom: 0px; margin-top: -8px;">' . $dt->email . ' &nbsp; ' . $evef . '</p>
                                            </div>
                                        </div>

                                    </div>';

            if (Doo::conf()->reseller_show_password == 'yes' && Doo::conf()->show_password == 1) {
                $hfunck = base64_encode($dt->login_id . '_' . base64_encode('smppcubehash'));
                $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
                $pass = $encobj->decrypt($dt->password, $hfunck);
                $pass_str = ' ( ' . $pass . ' )';
            }

            if ($dt->category == 'reseller') {
                $cstr = '<span class="label label-md label-purple">Reseller</span>';
            } else {
                $cstr = '<span class="label label-md label-pink">Client</span>';
            }

            if ($dt->account_type == '1' || $dt->account_type == '2') {
                //currency based account
                $wobj->user_id = $dt->user_id;
                $wallet = Doo::db()->find($wobj, array('limit' => 1));
                $crestr = '<table class="wd100 clearfix"><tbody>';
                $crestr .= '<tr><td class="pull-right"><i class="zmdi zmdi-hc-2x zmdi-balance-wallet text-primary m-r-xs"></i></td><td>' . Doo::conf()->currency . number_format($wallet->amount, 2) . '</td></tr>';
                $crestr .= '</tbody></table>';
            } else {
                //credit based account
                $creobj->user_id = $dt->user_id;
                $creobj->status = 0;
                $credit_data = Doo::db()->find($creobj, array('select' => 'route_id,credits'));
                $crestr = '<table class="wd100 table clearfix"><tbody>';
                foreach ($credit_data as $cd) {
                    $rtd = $robj->getRouteData($cd->route_id, 'title');
                    $crestr .= '<tr><td>' . $rtd->title . '</td><td><span class="badge label-md badge-success pull-right">' . number_format($cd->credits) . '</span></td></tr>';
                }
                $crestr .= '</tbody></table>';
            }



            if ($_SESSION['user']['group'] == 'admin') {
                $upinfo = $uobj->getProfileInfo($dt->upline_id, 'name,avatar,email');
                $uplinestr = '<div class="media-group-item" style="padding-top:0;padding-left:0;">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm m-r-xs avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->upline_id . '"><img src="' . $upinfo->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-xs"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->upline_id . '" class="m-r-xs theme-color">' . ucwords($upinfo->name) . '</a></h5>
                                                <p style="font-size: 12px; margin-bottom: 0px; margin-top: -8px;">' . $upinfo->email . '</p>
                                            </div>
                                        </div>

                                    </div>';
                $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '">' . $this->SCTEXT('View Account') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewUserDlrSummary/' . $dt->user_id . '">' . $this->SCTEXT('Sent SMS') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'makeAccountTransaction/' . $dt->user_id . '">' . $this->SCTEXT('Credit/Debit Account') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $dt->user_id . '">' . $this->SCTEXT('Account Settings') . '</a></li></ul></div>';

                $output = array($ustr, $dt->login_id . $pass_str, $dt->mobile . '&nbsp; ' . $mvef, $cstr, $crestr, $uplinestr, $button_str);
            } else {
                $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '">' . $this->SCTEXT('View Account') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewUserDlrSummary/' . $dt->user_id . '">' . $this->SCTEXT('Sent SMS') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'makeAccountTransaction/' . $dt->user_id . '">' . $this->SCTEXT('Credit/Debit Account') . '</a></li></ul></div>';

                $output = array($ustr, $dt->login_id . $pass_str, $dt->mobile . '&nbsp; ' . $mvef, $cstr, $crestr, $button_str);
            }




            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function manageInactiveUsers()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['User Mgmt.'] = 'javascript:void(0);';
        $data['active_page'] = 'Inactive Users';

        $data['page'] = 'User Management';
        $data['current_page'] = 'manage_iusers';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/manageInactiveUsers', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getInactiveUserAccounts()
    {


        $columns = array(
            array('db' => 'name', 'dt' => 0),
            array('db' => 'login_id', 'dt' => 1),
            array('db' => 'mobile', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);


        Doo::loadModel('ScUsers');
        $obj = new ScUsers;
        $uobj = new ScUsers;
        $uid = $_SESSION['user']['userid'];
        if ($_SESSION['user']['subgroup'] == 'reseller') {
            $sWhere = "upline_id = $uid AND category <> 'admin' AND status<>1";
        } elseif ($_SESSION['user']['subgroup'] == 'staff') {
            $sWhere = "(acc_mgr_id = $uid OR upline_id = $uid) AND category <> 'admin' AND status<>1";
        } elseif ($_SESSION['user']['subgroup'] == 'admin') {
            $sWhere = "category <> 'admin' AND status<>1";
        } else {
            //uh oh log this
            $actData['activity_type'] = 'VIEW USER';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| PAGE: Suspended Accounts';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);
            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->Doo::conf()->unauthorized_user_access . '|| PAGE: Suspended Accounts', 'viewUserAccount/' . $_SESSION['user']['userid']);
            //return
            exit;
        }

        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND' . $dtdata['where'];
            }
        }

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'user_id';
        }

        $total = Doo::db()->find($obj, array('select' => 'count(`user_id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $users = Doo::db()->find($obj, $dtdata);

        Doo::loadModel('ScUsersCreditData');
        $creobj = new ScUsersCreditData;

        Doo::loadModel('ScSmsRoutes');
        $robj = new ScSmsRoutes;

        Doo::loadHelper('DooEncrypt');

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($users as $dt) {

            //email verified
            if ($dt->email_verified == '1') {
                $evef = '<i class="fa fa-check-circle fa-lg text-success pointer" title="' . $this->SCTEXT('Email Verified') . '"></i>';
            } else {
                $evef = '<i class="fa fa-exclamation-circle fa-lg text-warning pointer" title="' . $this->SCTEXT('Email Not Verified') . '"></i>';
            }
            //mobile verified
            if ($dt->email_verified == '1') {
                $mvef = '<i class="fa fa-check-circle fa-lg text-success pointer" title="' . $this->SCTEXT('Phone number Verified') . '"></i>';
            } else {
                $mvef = '<i class="fa fa-exclamation-circle fa-lg text-warning pointer" title="' . $this->SCTEXT('Phone number Not Verified') . '"></i>';
            }


            $ustr = '<div class="media-group-item" style="padding-top:0;padding-left:0;">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm m-r-xs avatar-circle"><a href="javascript:void(0);"><img src="' . $dt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-xs"><a href="javascript:void(0);" class="m-r-xs theme-color">' . ucwords($dt->name) . '</a></h5>
                                                <p style="font-size: 12px; margin-bottom: 0px; margin-top: -8px;">' . $dt->email . ' &nbsp; ' . $evef . '</p>
                                            </div>
                                        </div>

                                    </div>';

            if (Doo::conf()->reseller_show_password == 'yes') {
                $hfunck = base64_encode($dt->login_id . '_' . base64_encode('smppcubehash'));
                $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
                $pass = $encobj->decrypt($dt->password, $hfunck);
                $pass_str = ' ( ' . $pass . ' )';
            }

            if ($dt->category == 'reseller') {
                $cstr = '<span class="label label-md label-purple">Reseller</span>';
            } else {
                $cstr = '<span class="label label-md label-pink">Client</span>';
            }

            $creobj->user_id = $dt->user_id;
            $creobj->status = 0;
            $credit_data = Doo::db()->find($creobj, array('select' => 'route_id,credits'));
            $crestr = '<table class="wd100 table clearfix"><tbody>';
            foreach ($credit_data as $cd) {
                $rtd = $robj->getRouteData($cd->route_id, 'title');
                $crestr .= '<tr><td>' . $rtd->title . '</td><td><span class="badge label-md badge-success pull-right">' . number_format($cd->credits) . '</span></td></tr>';
            }
            $crestr .= '</tbody></table>';


            if ($_SESSION['user']['group'] == 'admin') {
                $upinfo = $uobj->getProfileInfo($dt->upline_id, 'name,avatar,email');
                $uplinestr = '<div class="media-group-item" style="padding-top:0;padding-left:0;">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm m-r-xs avatar-circle"><a href="javascript:void(0);"><img src="' . $upinfo->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-xs"><a href="javascript:void(0);" class="m-r-xs theme-color">' . ucwords($upinfo->name) . '</a></h5>
                                                <p style="font-size: 12px; margin-bottom: 0px; margin-top: -8px;">' . $upinfo->email . '</p>
                                            </div>
                                        </div>

                                    </div>';
                $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'activateUserAccount/' . $dt->user_id . '">' . $this->SCTEXT('Activate User') . '</a></li><li><a class="del-user" data-uid="' . $dt->user_id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete User') . '    </a></li></ul></div>';

                $output = array($ustr, $dt->login_id . $pass_str, $dt->mobile . '&nbsp; ' . $mvef, $cstr, $crestr, $uplinestr, $button_str);
            } else {
                $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'activateUserAccount/' . $dt->user_id . '">' . $this->SCTEXT('Activate User') . '</a></li><li><a class="del-user" data-uid="' . $dt->user_id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete User') . '</a></li></ul></div>';

                $output = array($ustr, $dt->login_id . $pass_str, $dt->mobile . '&nbsp; ' . $mvef, $cstr, $crestr, $button_str);
            }




            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addNewUser()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['add'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'Add New User';

        if ($_SESSION['user']['group'] == 'admin') {
            //all staff members
            Doo::loadModel('ScUsers');
            $obj = new ScUsers;
            $data['staff'] = $obj->getAllStaff();
            //all sms plans
            Doo::loadModel('ScSmsPlans');
            $sobj = new ScSmsPlans;
            $data['plans'] = Doo::db()->find($sobj);
            //all routes
            Doo::loadModel('ScSmsRoutes');
            $robj = new ScSmsRoutes;
            $data['routes'] = Doo::db()->find($robj);
            //get all permission groups
            $pgobj = Doo::loadModel('ScPermissionGroups', true);
            $data['pgroups'] = Doo::db()->find($pgobj);
        }

        //get all mcc mnc based plans
        $pobj = Doo::loadModel('ScMccMncPlans', true);
        $data['mplans'] = Doo::db()->find($pobj);

        //get all approved sender ids
        $sidobj = Doo::loadModel('ScSenderId', true);
        $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['senders'] = Doo::db()->find($sidobj);

        //get all approved templates
        $tmpobj = Doo::loadModel('ScSmsTemplates', true);
        $tmpobj->user_id = $_SESSION['user']['userid'];
        $tmpobj->status = 1;
        $data['templates'] = Doo::db()->find($tmpobj);

        //get all DLT params
        $tlvobj = Doo::loadModel('ScUsersTlvValues', true);
        $tlvobj->user_id = $_SESSION['user']['userid'];
        $data['tlvs'] = Doo::db()->find($tlvobj);

        $data['page'] = 'User Management';
        $data['current_page'] = 'add_user';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/addUser', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getPlanSmsPrice()
    {
        //calculations for user transaction
        //debit transaction
        if ($_POST['mode'] == 'utrans_d') {
            //collect values
            $pid = intval($_POST['plan']);
            $rdata = json_decode(stripslashes($_POST['routesData']));
            $dis = floatval($_POST['discount']);
            $dtype = $_POST['dtype'];
            $adtx = floatval($_POST['addTax']);

            $avcredits = intval(str_replace(",", "", $_POST['avcre'])); //credits available in user's account for this route

            if ($pid == 0) {
                //custom pricing
                $errcredits = 0;
                $res = array();
                $total = 0;
                foreach ($rdata as $cdata) {
                    $credits = intval($cdata->credits);
                    $rate = floatval($cdata->price);
                    $rid = $cdata->id;
                    if ($credits != 0) {
                        $total += $credits * $rate;
                        $res[$rid]['credits'] = $credits;
                        $res[$rid]['price'] = $rate;
                        $res[$rid]['total'] = $credits * $rate;

                        //cannot deduct more than available
                        if ($credits > $avcredits) {
                            $errcredits = 1;
                        } else {
                            $errcredits = 0;
                        }
                    } else {
                        //null
                        //$rate = $prc;
                        $total += 0;
                        $res[$rid]['credits'] = 0;
                        $res[$rid]['price'] = $rate;
                        $res[$rid]['total'] = 0;
                    }
                }
                $res['total'] = $total;
                $total_price = $total;

                $total_af_plntax = $total_price;

                //apply additional tax
                if ($adtx != 0) {
                    $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                } else {
                    $total_af_adtax = $total_af_plntax;
                }
                $grand_total = $total_af_adtax;


                //return rate and total cost
                $resp['price'] = $res;
                $resp['total_plan'] = round($total_af_plntax, 5);
                $resp['grand_total'] = round($grand_total, 5);
                $resp['plan_tax'] = '';
                $resp['errcredits'] = $errcredits;
                echo json_encode($resp);

                //--end of custom pricing
                exit;
            } else {
                //plan is assigned
                Doo::loadModel("ScSmsPlanOptions");
                $obj = new ScSmsPlanOptions;
                $pricedata = $obj->getSmsPrice($pid, $rdata);

                //get the rates from db. Only get applicable plan tax from db
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
                $res['total_plan'] = round($total_af_plntax, 5);
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
        }

        //credit transaction
        else if ($_POST['mode'] == 'utrans') {
            //collect values
            $pid = intval($_POST['plan']);
            $rdata = json_decode(stripslashes($_POST['routesData']));
            $dis = floatval($_POST['discount']);
            $dtype = $_POST['dtype'];
            $adtx = floatval($_POST['addTax']);

            if ($pid == 0) {
                //custom pricing
                $errcredits = 0;
                $res = array();
                $total = 0;
                foreach ($rdata as $cdata) {
                    $credits = intval($cdata->credits);
                    $rate = floatval($cdata->price);
                    $rid = $cdata->id;
                    if ($credits != 0) {
                        $total += $credits * $rate;
                        $res[$rid]['credits'] = $credits;
                        $res[$rid]['price'] = $rate;
                        $res[$rid]['total'] = $credits * $rate;

                        if ($_SESSION['user']['group'] == 'reseller') {
                            if ($credits > $_SESSION['credits']['routes'][$rid]['credits']) {
                                $errcredits = 1;
                            } else {
                                $errcredits = 0;
                            }
                        }
                    } else {
                        //null
                        //$rate = $prc;
                        $total += 0;
                        $res[$rid]['credits'] = 0;
                        $res[$rid]['price'] = $rate;
                        $res[$rid]['total'] = 0;
                    }
                }
                $res['total'] = $total;
                $total_price = $total;
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


                    $total_af_plntax = $total_af_dis;

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    $grand_total = $total_af_adtax;
                } else {

                    $total_af_plntax = $total_price;

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
                $resp['price'] = $res;
                $resp['total_plan'] = round($total_af_plntax, 5);
                $resp['grand_total'] = round($grand_total, 5);
                $resp['plan_tax'] = '';
                $resp['errcredits'] = $errcredits;
                echo json_encode($resp);

                //--end of custom pricing
                exit;
            } else {
                //plan is assigned
                Doo::loadModel("ScSmsPlanOptions");
                $obj = new ScSmsPlanOptions;
                $pricedata = $obj->getSmsPrice($pid, $rdata);

                //get the rates from post. Only get applicable plan tax from db
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
                $res['total_plan'] = round($total_af_plntax, 5);
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
        } else if ($_POST['mode'] == 'sub') {
            //-- subscription based ---//
            $pid = intval($_POST['plan']);
            $idn = $_POST['idn'];
            $dis = floatval($_POST['discount']);
            $dtype = $_POST['dtype'];
            $adtx = floatval($_POST['addTax']);

            Doo::loadModel("ScSmsPlanOptions");
            $obj = new ScSmsPlanOptions;
            $total_price = $obj->getSmsPrice($pid, null, $idn);


            //get Tax
            Doo::loadModel('ScSmsPlans');
            $sobj = new ScSmsPlans;
            $sobj->id = $pid;
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

            //return rate and total cost
            $res['price'] = $total_price;
            $res['total_plan'] = round($total_af_plntax, 5);
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
        } else {

            //---- volume based ---//
            //collect values
            $pid = intval($_POST['plan']);
            $rdata = json_decode(stripslashes($_POST['routesData']));
            $dis = floatval($_POST['discount']);
            $dtype = $_POST['dtype'];
            $adtx = floatval($_POST['addTax']);

            if ($pid == 0) {
                //custom pricing
                $errcredits = 0;
                $res = array();
                $total = 0;
                foreach ($rdata as $cdata) {
                    $credits = intval($cdata->credits);
                    $rate = floatval($cdata->price);
                    $rid = $cdata->id;
                    if ($credits != 0) {
                        $total += $credits * $rate;
                        $res[$rid]['credits'] = $credits;
                        $res[$rid]['price'] = $rate;
                        $res[$rid]['total'] = $credits * $rate;

                        if ($_SESSION['user']['group'] == 'reseller') {
                            if ($credits > $_SESSION['credits']['routes'][$rid]['credits']) {
                                $errcredits = 1;
                            } else {
                                $errcredits = 0;
                            }
                        }
                    } else {
                        //null
                        //$rate = $prc;
                        $total += 0;
                        $res[$rid]['credits'] = 0;
                        $res[$rid]['price'] = $rate;
                        $res[$rid]['total'] = 0;
                    }
                }
                $res['total'] = $total;
                $total_price = $total;
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


                    $total_af_plntax = $total_af_dis;

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    $grand_total = $total_af_adtax;
                } else {

                    $total_af_plntax = $total_price;

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
                $resp['price'] = $res;
                $resp['total_plan'] = round($total_af_plntax, 5);
                $resp['grand_total'] = round($grand_total, 5);
                $resp['plan_tax'] = '';
                $resp['errcredits'] = $errcredits;
                echo json_encode($resp);

                //--end of custom pricing
                exit;
            }

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
            $res['total_plan'] = round($total_af_plntax, 5);
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
    }

    public function createUserAccount()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['add'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }

        $userHelper = Doo::loadHelper('DooUserHelper', true);
        //collect profile info
        $input['cat'] = DooTextHelper::cleanInput($_POST['category']);
        $input['name'] = DooTextHelper::cleanInput($_POST['uname'], ' ', 0);
        $input['gender'] = $_POST['gender'];
        $input['loginid'] = DooTextHelper::cleanInput($_POST['ulogin']);
        $input['email'] = $_POST['uemail'];
        $input['mobile'] = intval($_POST['uphn']);
        $input['supplied_password'] = $_POST['upass'];
        $input['verify_password'] = $_POST['upass2'];
        $input['account_mgr'] = intval($_POST['staff']) == 0 ? $_SESSION['user']['userid'] : intval($_POST['staff']);
        $input['optin_perm'] = $_SESSION['user']['group'] == 'admin' ? intval($_POST['optperm']) : $_SESSION['user']['optin'];
        $input['credit_plan_type'] = intval($_POST['ptype']);
        $account_type = $_SESSION['user']['group'] == 'admin' ? intval($_POST['acctype']) : $_SESSION['user']['account_type'];
        //acl
        $input['acl_mode'] = intval($_POST['acl_mode']);
        $input['acl_ip_list'] = $_POST['acl_ip_list'];

        if ($_SESSION['user']['subgroup'] == 'admin') {
            $input['activation'] = date(Doo::conf()->date_format_db, strtotime($_POST['act_start']));
            $input['expiry'] = date(Doo::conf()->date_format_db, strtotime($_POST['act_expire']));
        } else {
            $input['activation'] = date(Doo::conf()->date_format_db);
            $input['expiry'] = '';
        }


        //get logged in user info
        $input['account_creator_uid'] = $_SESSION['user']['userid'];
        $input['account_creator_group'] = $_SESSION['user']['group'];

        $userHelper->billingType = $account_type;
        //get the plan, routes and credits information supplied
        if ($account_type == 0) {
            $input['discount'] = floatval($_POST['udis']);
            $input['discountType'] = $_POST['distype'];
            $input['additionalTax'] = floatval($_POST['utax']);
            //get chosen plan
            $input['planid'] = intval($_POST['plan']);
            $input['plan_type'] = intval($_POST['ptype']);
            //get routes, credits and prices
            $input['supplied_routes'] = $_POST['route'];
            $input['supplied_credits'] = $_POST['credits'];
            $input['prices'] = $_POST['rate'];
            //if sub based -> get sub plan
            $input['plan_suboption'] = $_POST['plan_option'];
        }
        if ($account_type == 1) {
            $input['planid'] = intval($_POST['mccmncplans']);
            $input['walletCredits'] = floatval($_POST['mplancredits']);
        }
        if ($account_type == 2) {
            $input['additional_tax'] = floatval($_POST['utax']);
            $input['walletCredits'] = floatval($_POST['curcredits']);
            //get routes and prices
            $input['supplied_routes'] = $_POST['routecur'];
            $input['prices'] = $_POST['ratecur'];
        }
        //validate
        $validation = $userHelper->validateInputs($input);
        if ($validation['type'] != 'success') {
            //check if credits validation failed
            if (isset($validation['credits_error'])) {
                $routeid = $validation['credits_error']['routeid'];
                $validation['msg'] .= strtoupper($_SESSION['credits']['routes'][$routeid]['name']);
            }
            $_SESSION['notif_msg']['type'] = $validation['type'];
            $_SESSION['notif_msg']['msg'] = $validation['msg'];
            return Doo::conf()->APP_URL . 'addNewUser';
        }
        $input['invoice_status'] = intval($_POST['invstatus']);
        $input['invoice_remarks'] = DooTextHelper::cleanInput($_POST['invremarks']);
        //generate invoice
        $invoice_data = $userHelper->createInvoiceParams($input);
        //add in database
        $user_id = $userHelper->saveUser($input);
        //add invoice set status based on paid status
        Doo::loadModel('ScUsersDocuments');
        $dobj = new ScUsersDocuments;
        $dobj->filename = 'INVOICE_' . $input['loginid'] . '_' . time();
        $dobj->type = 1;
        $dobj->owner_id = $_SESSION['user']['userid'];
        $dobj->shared_with = $user_id;
        $dobj->created_on = date(Doo::conf()->date_format_db);
        $dobj->file_data = serialize($invoice_data);
        $dobj->file_status = $invoice_data['inv_status'];
        $dobj->init_remarks = $invoice_data['inv_rem'];
        $inv_id = Doo::db()->insert($dobj);

        //add routes and price data for credit and dynamic credit based user and also add credit log entry
        $userHelper->addRoutesCreditsPrice($user_id, array(
            'routedata' => $invoice_data['routes_credits'],
            'walletcredits' => floatval($input['walletCredits']),
            'expiry' => $input['expiry']
        ));

        //save any plan association for the user
        $userHelper->saveUserPlanAssociation($user_id, array(
            'id' => isset($input['planid']) ? $input['planid'] : 0,
            'option' => isset($input['plan_suboption']) ? $input['plan_suboption'] : ''
        ));
        //create a wallet and add credits if applicable
        DooUserHelper::userWalletTransaction('create', $user_id, array(
            'code' => strtoupper(md5(uniqid($input['loginid'], true))),
            'amount' => floatval($input['walletCredits']),
            'invoice' => $inv_id,
            'expiry' => $input['expiry']
        ));

        //save transaction history for credit based account
        $userHelper->addCreditTransaction('credit', $user_id, array(
            'transactionid' => $input['loginid'] . rand(0, 100) . time(),
            'upline' => $_SESSION['user']['userid'],
            'routedata' => $invoice_data['routes_credits'],
            'invoice' => $inv_id
        ));

        //deduct upline balance if applicable
        if ($_SESSION['user']['group'] != 'admin') {
            //reseller account deduct balance
            $new_credits_array = $userHelper->deductResellerCredits($_SESSION['user']['userid'], array(
                'routedata' => $invoice_data['routes_credits'],
                'new_user' => $input['loginid']
            ));
            foreach ($new_credits_array as $routeid => $new_credits) {
                $_SESSION['credits']['routes'][$routeid]['credits'] = $new_credits;
            }
        }

        if (is_array($_POST['sids']) && sizeof($_POST['sids']) > 0) {
            $sidqry = "SELECT sender_id, countries_matrix FROM sc_sender_id WHERE id IN (" . implode(',', $_POST['sids']) . ")";
            $sids = Doo::db()->fetchAll($sidqry, null, PDO::FETCH_KEY_PAIR);
            foreach ($sids as $sid => $cov) {
                $sidobj = Doo::loadModel('ScSenderId', true);
                $sidobj->sender_id = $sid;
                $sidobj->countries_matrix = $cov;
                $sidobj->req_by = $user_id;
                $sidobj->status = 1;
                Doo::db()->insert($sidobj);
            }
        } else {
            //add default sender id
            $sidobj = Doo::loadModel('ScSenderId', true);
            $sidobj->addNewSid(Doo::conf()->default_sender_id, $user_id, 1);
        }
        //add default templates
        if (is_array($_POST['templates']) && sizeof($_POST['templates']) > 0) {
            $tmpqry = "SELECT title, content FROM sc_sms_templates WHERE id IN (" . implode(',', $_POST['templates']) . ")";
            $temps = Doo::db()->fetchAll($tmpqry, null, PDO::FETCH_KEY_PAIR);
            foreach ($temps as $tmp => $content) {
                $tmpobj = Doo::loadModel('ScSmsTemplates', true);
                $tmpobj->user_id = $user_id;
                $tmpobj->title = $tmp;
                $tmpobj->content = $content;
                $tmpobj->status = 1;
                Doo::db()->insert($tmpobj);
            }
        }
        //add default tlv if applicable
        if (is_array($_POST['tlvs']) && sizeof($_POST['tlvs']) > 0) {
            $tlvqry = "SELECT tlv_title, tlv_value, tlv_category, assoc_route FROM sc_users_tlv_values WHERE id IN (" . implode(',', $_POST['tlvs']) . ")";
            $tlvs = Doo::db()->fetchAll($tlvqry, null, PDO::FETCH_ASSOC);
            foreach ($tlvs as $tlv) {
                $tlvobj = Doo::loadModel('ScUsersTlvValues', true);
                $tlvobj->user_id = $user_id;
                $tlvobj->tlv_title = $tlv['tlv_title'];
                $tlvobj->tlv_value = $tlv['tlv_value'];
                $tlvobj->tlv_category = $tlv['tlv_category'];
                $tlvobj->assoc_route = $tlv['assoc_route'];
                Doo::db()->insert($tlvobj);
            }
        }

        //add default campaign for this user
        $cmpobj = Doo::loadModel('ScUsersCampaigns', true);
        $cmpobj->createNewCampaigns($user_id);
        //add a website for this user if reseller
        if ($input['cat'] == 'reseller') {
            $wobj = Doo::loadModel('ScWebsites', true);
            $wobj->user_id = $user_id;
            $wobj->status = Doo::conf()->default_website_status;
            Doo::db()->insert($wobj);
        }
        //add user permissions based on plan data and account type
        $pgobj = Doo::loadModel('ScPermissionGroups', true);
        $pgobj->id = $_SESSION['user']['group'] == 'admin' ? intval($_POST['pgid']) : intval($_SESSION['permissions']['id']);
        $permdata = Doo::db()->find($pgobj, array('limit' => 1));

        $permobj = Doo::loadModel('ScUsersPermissions', true);
        $permobj->user_id = $user_id;
        $permobj->pg_id = $permdata->id;
        $permobj->perm_data = $permdata->permissions;
        Doo::db()->insert($permobj);

        //set sales statistics
        if ($_SESSION['user']['group'] == 'admin') {
            $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
            $stobj->addStat(date('Y-m-d'), $invoice_data['total_cost']);
        } else {
            $totalsms = 0;
            foreach ($invoice_data['routes_credits'] as $rid => $rinfo) {
                $totalsms += $rinfo['credits'];
            }
            $stobj = Doo::loadModel('ScStatsSalesReseller', true);
            $stobj->addStat(date('Y-m-d'), $_SESSION['user']['userid'], $totalsms, 1);
        }
        //log event
        $actData['activity_type'] = 'ADD USER';
        $actData['activity'] = Doo::conf()->added_user_account . '|| LOGINID: ' . $input['loginid'];
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);

        //send notifications if applicable
        $snobj = Doo::loadModel('ScWebsitesSignupSettings', true);
        $snobj->user_id = $_SESSION['user']['userid'];
        $sndata = Doo::db()->find($snobj, array('limit' => 1, 'select' => 'notif_data'));
        $sndatar = unserialize($sndata->notif_data);

        if ($sndatar['email'] == 1) {
            // $cdata = unserialize($_SESSION['webfront']['company_data']);

            // $maildata['company_url'] = Doo::conf()->APP_URL;
            // $maildata['logo'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'];
            // $maildata['name'] = $input['name'];
            // $maildata['loginid'] = $input['loginid'];
            // $maildata['password'] = $input['supplied_password'];
            // $maildata['acc_type'] = $input['cat'];
            // $maildata['login_url'] = Doo::conf()->APP_URL . 'web/sign-in';
            // $maildata['main_ip'] = Doo::conf()->server_ip;
            // $maildata['helpline'] = $cdata['helpline'];
            // $maildata['company_domain'] = $_SESSION['webfront']['current_domain'];
            // $mailbody = $this->view()->getRendered('mail/newAccount', $maildata);

            // Doo::loadHelper("DooPhpMailer");
            // $mail = DooPhpMailer::getMailObj();

            // $mail->setFrom($cdata['helpmail'], $cdata['company_name']);
            // $mail->Subject  = $this->SCTEXT('New Account Created') . ' || ' . $cdata['company_name'] . ' SMS Portal';
            // $mail->isHTML(true);
            // $mail->Body = $mailbody;
            // $mail->addAddress($input['email']);
            // $mail->send();
            // $mail->clearAddresses();
        }
        //send sms
        if ($sndatar['sms'] == 1) {
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
            $api_key = $akobj->getApiKey($_SESSION['user']['userid']);
            $sms = $this->SCTEXT('Thank you for registration. Here are your login details:') . "\nURL:" . Doo::conf()->APP_URL . 'web/sign-in\nLogin ID: ' . $input['loginid'] . "\n" . 'Password: ' . $input['supplied_password'];
            $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . intval($cmpobj->getCampaignId($_SESSION['user']['userid'], 'system')) . '&routeid=' . $sndatar['sms_route'] . '&type=text&contacts=' . $input['mobile'] . '&senderid=' . $sndatar['sms_sid'] . '&msg=' . urlencode($sms);


            //Submit to server
            $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
        }

        //send response
        $_SESSION['notif_msg']['msg'] = 'New user added successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageUsers';
    }

    public function viewUserAccount()
    {
        //select2 templateresult function fires a Searching call. Handle it here
        if (urldecode($this->params['id']) == 'Searching…') {
            exit;
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: ViewUserAccount';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: ViewUserAccount';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //get user domain status
        if ($data['user']->category == 'reseller') {
            $wobj = Doo::loadModel('ScWebsites', true);
            $wobj->user_id = $uid;
            $data['wstatus'] = Doo::db()->find($wobj, array('limit' => 1, 'select' => 'status'));
        }

        //all staff members
        if ($_SESSION['user']['subgroup'] == 'admin') {
            $stfobj = Doo::loadModel('ScUsers', true);
            $data['staff'] = $stfobj->getAllStaff();
        }
        //all waba agents, allow staff to manage these as well
        if ($_SESSION['user']['group'] == 'admin') {
            if (Doo::conf()->whatsapp == 1) {
                $wabaobj = Doo::loadModel('WbaAgents', true);
                $data['wabas'] = Doo::db()->find($wabaobj);
                //get all users for admin
                $usrqry = "SELECT user_id, CONCAT_WS('|', name, email, avatar) as info FROM sc_users";
                $wudata = Doo::db()->fetchAll($usrqry, null, PDO::FETCH_KEY_PAIR);
                $data['wusers'] = $wudata;
                //get all the Whatsapp plans
                $wpobj = Doo::loadModel('WbaRatePlans', true);
                $data['wplans'] = Doo::db()->find($wpobj);
                //get associated plan for this Waba as well
                $wapobj = Doo::loadModel('WbaAgentRatePlan', true);
                $wapobj->user_id = $uid;
                $data['agent_plan'] = Doo::db()->find($wapobj, array('limit' => 1));
            }
        }

        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'Account Overview';

        $data['page'] = 'User Management';
        $data['current_page'] = 'view_account';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/viewAccount', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewUserRouteSettings()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserRouteSettings';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: viewUserRouteSettings';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //get route assigned to the user
        $rtobj = Doo::loadModel('ScUsersCreditData', true);
        $rtobj->user_id = $uid;
        $rtobj->status = 0;
        $data['rdata'] = Doo::db()->find($rtobj);

        //get sms plan for currency based account
        if ($data['user']->account_type == '1') {
            $uplobj = Doo::loadModel('ScUsersSmsPlans', true);
            $uplobj->user_id = $uid;
            $data['userplan'] = Doo::db()->find($uplobj, array('limit' => 1));
            $plobj = Doo::loadModel('ScMccMncPlans', true);
            $data['plans'] = Doo::db()->find($plobj);
        }
        //get all fdlr templates
        $fdobj = Doo::loadModel('ScFdlrTemplates', true);
        $data['fdlrs'] = Doo::db()->find($fdobj);

        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'Route Settings';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_rset';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaRouteSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveRouteAssignments()
    {
        //collect values
        $uid = intval($_POST['userid']);
        $routes = $_POST['routes'];
        $dlrper = $_POST['dlrper'];
        $delvcut = $_POST['dlrperth'];
        $fdlr = $_POST['ufdlr'];

        $uobj = Doo::loadModel('ScUsers', true);
        $user = $uobj->getProfileInfo($uid);
        $ridar = array();

        Doo::loadModel('ScUsersCreditData');

        //get complete user tree
        $utobj = Doo::loadModel('DooSmppcubeHelper', true);
        $usrar = $utobj->getUserTree($uid);
        $usrstr = implode(",", $usrar);

        //get dlr percentage if admin else set default dlr percentage
        foreach ($routes as $rid => $val) {

            if ($_SESSION['user']['group'] == 'admin') {
                $rdlr = $dlrper[$rid] == '' ? Doo::conf()->def_dlr_per : $dlrper[$rid];
                $rdelv_th = intval($delvcut[$rid]) == 0 ? Doo::conf()->dlr_per_threshold : intval($delvcut[$rid]);
                $rfdlr_id = intval($fdlr[$rid]);
            } else {
                //if not admin get dlr percentage applied to loggin in user
                $rdlr = $_SESSION['credits']['routes'][$rid]['delv_per'];
                $rdelv_th = $_SESSION['credits']['routes'][$rid]['delv_threshold'];
                $rfdlr_id = $_SESSION['credits']['routes'][$rid]['fdlr_id'];
            }

            //add data
            $rtobj = new ScUsersCreditData;
            $rtobj->user_id = $uid;
            $rtobj->route_id = $rid;
            $rfobj = Doo::db()->find($rtobj, array('limit' => 1));

            if ($rfobj->id) {
                //record already exists
                $rtobj->id = $rfobj->id;
                $rtobj->status = 0;
                if ($user->account_type == 2 && $_SESSION['user']['group'] == 'admin') {
                    $rtobj->price = floatval($_POST['ratecur'][$rid]);
                }
                $rtobj->delv_per = $rdlr;
                $rtobj->delv_threshold = $rdelv_th;
                $rtobj->fdlr_id = $rfdlr_id;
                Doo::db()->update($rtobj, array('limit' => 1));
            } else {
                if ($user->account_type == 2 && $_SESSION['user']['group'] == 'admin') {
                    $rtobj->price = floatval($_POST['ratecur'][$rid]);
                }
                $rtobj->delv_per = $rdlr;
                $rtobj->delv_threshold = $rdelv_th;
                $rtobj->fdlr_id = $rfdlr_id;
                Doo::db()->insert($rtobj);
            }

            array_push($ridar, $rid);

            //migrate this dlr percentage for this route for all downline tree
            $dqry = "UPDATE sc_users_credit_data SET status=0,delv_per=$rdlr WHERE route_id = $rid AND user_id IN ($usrstr)";
            Doo::db()->query($dqry);
        }

        if (!empty($ridar)) {
            $ridstr = implode(",", $ridar);
            $qry = "UPDATE sc_users_credit_data SET status=1 WHERE user_id IN($usrstr) AND route_id NOT IN($ridstr)";
            Doo::db()->query($qry);
        }

        //return
        $_SESSION['notif_msg']['msg'] = 'Route assignments changed successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserRouteSettings/' . $uid;
    }

    public function viewUserSenderIds()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserSenderIds';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: viewUserSenderIds';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'User Sender ID';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_usid';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaUserSenderIds', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewUserTemplates()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserTemplates';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: viewUserTemplates';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'User Templates';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_utemps';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaUserTemplates', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewUserDlrSummary()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserDlrSummary';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: viewUserDlrSummary';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'User Sent SMS';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_sentsms';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaSentSms', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function showUserDLR()
    {

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: showUserDLR';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: showUserDLR';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //get sms dlr data
        $shootid = $this->params['sid'];
        //get queued sms count

        $queued = 0;
        $lcobj = Doo::loadModel('ScLongcourseCampaigns', true);
        $lcobj->sms_shoot_id = $shootid;
        $lc_queued = intval(Doo::db()->find($lcobj, array('select' => 'SUM(`total_contacts`) as total', 'limit' => 1))->total);
        $data['qcount'] = intval($queued) + intval($lc_queued);
        //get sent sms count
        $sntobj = Doo::loadModel('ScSentSms', true);
        $sntobj->sms_shoot_id = $shootid;
        $data['sent'] = intval(Doo::db()->find($sntobj, array('select' => 'count(id) as total', 'limit' => 1))->total);
        //get sms summary
        $sobj = Doo::loadModel('ScSmsSummary', true);
        $sobj->sms_shoot_id = $shootid;
        $sobj->user_id = $uid;
        $data['sum'] = Doo::db()->find($sobj, array('limit' => 1));

        //sms type
        $smscat = json_decode($data['sum']->sms_type, true);
        $stypestr = '';
        if ($smscat['main'] == 'text') {
            $stypestr = '<span>Text';
            if ($smscat['flash'] == '1') {
                $stypestr .= '<i title="Flash" class="fa fa-lg text-primary fa-fixed pointer fa-flash m-l-xs"></i>';
            }
            if ($smscat['personalize'] == '1') {
                $stypestr .= '<i title="Personalized SMS" class="fa fa-lg text-primary fa-fixed pointer fa-user-circle m-l-xs"></i>';
            }
            if ($smscat['unicode'] == '1') {
                $stypestr .= '<i title="Unicode" class="fa fa-lg text-primary fa-fixed pointer fa-language m-l-xs"></i>';
            }

            $stypestr .= '</span>';
        } elseif ($smscat['main'] == 'wap') {
            $stypestr = '<span class="label label-success label-md"><i class="fa fa-lg fa-globe m-r-xs"></i>WAP</span>';
        } elseif ($smscat['main'] == 'vcard') {
            $stypestr = '<span class="label label-primary label-md"><i class="fa fa-vcard m-r-xs"></i>vCard</span>';
        }

        $data['stype'] = $stypestr;


        //get total refunds for this campaign
        $rflobj = Doo::loadModel('ScLogsDlrRefunds', true);
        $rflobj->sms_shoot_id = $shootid;
        $data['reftotal'] = intval(Doo::db()->find($rflobj, array('select' => 'SUM(`refund_amt`) as total', 'limit' => 1))->total);



        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['links']['Sent SMS'] = Doo::conf()->APP_URL . 'viewUserDlrSummary/' . $uid;
        $data['active_page'] = 'DLR Details';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_dlrdetails';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaDlrDetails', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function makeAccountTransaction()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['transaction'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: makeAccountTransaction';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: makeAccountTransaction';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }
        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'Credit/Debit Account';

        if ($data['user']->account_type == '1' || $data['user']->account_type == '2') {
            //currency based account
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $wlobj->user_id = $uid;
            $data['wallet'] = Doo::db()->find($wlobj, array('limit' => 1));

            //get plan
            if ($data['user']->account_type == '1') {
                $pobj = Doo::loadModel('ScUsersSmsPlans', true);
                $pobj->user_id = $uid;
                $uplan = Doo::db()->find($pobj, array('limit' => 1));
                $plobj = Doo::loadModel('ScMccMncPlans', true);
                $plobj->id = $uplan->plan_id;
                $data['plan'] = Doo::db()->find($plobj, array('limit' => 1));
            }
        } else {
            //credit based account
            //load credit
            $cobj = Doo::loadModel('ScUsersCreditData', true);
            $cobj->user_id = $uid;
            $cobj->status = 0;
            $data['cdata'] = Doo::db()->find($cobj);

            $rtobj = Doo::loadModel('ScSmsRoutes', true);
            foreach ($data['cdata'] as $rt) {
                $rt->title = $rtobj->getRouteData($rt->route_id)->title;
            }

            //if admin/staff is making the transaction, get assigned sms plan
            if ($_SESSION['user']['group'] == 'admin') {
                $pobj = Doo::loadModel('ScUsersSmsPlans', true);
                $pobj->user_id = $uid;
                $uplan = Doo::db()->find($pobj, array('limit' => 1, 'select' => 'plan_id,subopt_idn'));
                if ($uplan->plan_id) {
                    $data['plan']['id'] = $uplan->plan_id;
                }
            }
        }

        //default tax
        $data['deftax'] = unserialize($data['user']->default_tax);
        switch ($data['deftax']["type"]) {
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
            case '':
                $type = 'Tax';
                break;
        }
        $data['deftax_str'] = $data['deftax']['tax'] . '% ' . $type;

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_utrans';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaMakeTrans', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function processAccountTransaction()
    {

        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['transaction'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }

        $userid = intval($_POST['userid']);

        //validate action
        if ($userid == 0 || $userid == $_SESSION['user']['userid']) {

            //generate alert for admin as this may be URL tampering or an attemp to add credits to own account

            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: processAccountTransaction';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Operation Not Allowed.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($userid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {

                //generate a moderate alert for admin as this reseller tried to make transaction outside the downline

                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: processAccountTransaction';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Operation Not Allowed.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {

                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = "Operation not allowed. User has a different account manager.";
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        if ($data['user']->account_type == '1' || $data['user']->account_type == '2') {
            //currency based account
            if ($_SESSION['user']['group'] != 'admin') {
                //deny
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. NON ADMIN tried to add into wallet. PAGE: processAccountTransaction';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| NON ADMIN tried to add into wallet.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Operation Not Allowed.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
            //get wallet details
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $wlobj->user_id = $userid;
            $wallet = Doo::db()->find($wlobj, array('limit' => 1));
            if ($data['user']->account_type == '1') {
                //get user plans
                $uplan = Doo::loadModel('ScUsersSmsPlans', true);
                $uplan->user_id = $userid;
                $planid = Doo::db()->find($uplan, array('limit' => 1))->plan_id;

                $plobj = Doo::loadModel('ScMccMncPlans', true);
                $plobj->id = $planid;
                $plandata = Doo::db()->find($plobj, array('limit' => 1));
            }

            $wcredits = floatval($_POST['mplanscredits']);

            if ($_POST['waction'] == '1') {
                //credit account
                //create invoice
                if ($data['user']->account_type == '1') {
                    $total = $plandata->tax == 0 ? $wcredits : $wcredits + ($wcredits * ($plandata->tax / 100));

                    switch ($plandata->tax_type) {
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

                    //
                    $invdata['plan_tax'] = $plandata->tax == 0 ? 0 : $plandata->tax . '% ' . $type;
                } else {
                    if (floatval($_REQUEST['add_tax']) > 0) {
                        $total = $wcredits + ($wcredits * (floatval($_REQUEST['add_tax']) / 100));
                    } else {
                        $total = $wcredits;
                    }

                    $invdata['plan_tax'] = 0;
                }

                $invdata['wallet_credits'] = $wcredits;
                $invdata['total_cost'] = round($wcredits, 2);
                $invdata['additional_tax'] = floatval($_REQUEST['add_tax']) . '%';
                $invdata['discount'] = 'N/A';
                $invdata['grand_total'] = round($total, 2);
                $invdata['inv_status'] = intval($_POST['invstatus']);
                $invdata['inv_rem'] = DooTextHelper::cleanInput($_POST['d_transremarks']);

                //add invoice set status based on paid status
                Doo::loadModel('ScUsersDocuments');
                $dobj = new ScUsersDocuments;
                $dobj->filename = 'INVOICE_' . $data['user']->login_id . '_' . time();
                $dobj->type = 1;
                $dobj->owner_id = $_SESSION['user']['userid'];
                $dobj->shared_with = $userid;
                $dobj->created_on = date(Doo::conf()->date_format_db);
                $dobj->file_data = serialize($invdata);
                $dobj->file_status = intval($_POST['invstatus']);
                $dobj->init_remarks = $invdata['inv_rem'];
                $inv_id = Doo::db()->insert($dobj);

                //add credits in wallet only if invoice is marked as paid
                if (intval($_POST['invstatus']) == 1) {
                    $wlobj2 = Doo::loadModel('ScUsersWallet', true);
                    $wlobj2->id = $wallet->id;
                    $wlobj2->amount = floatval($wallet->amount) + $wcredits;
                    Doo::db()->update($wlobj2);

                    //add transaction in wallet txn table
                    $wxobj = Doo::loadModel('ScUsersWalletTransactions', true);
                    $wxobj->wallet_id = $wallet->id;
                    $wxobj->transac_type = 1;
                    $wxobj->amount = $wcredits;
                    $wxobj->t_date = date(Doo::conf()->date_format_db);
                    $wxobj->linked_invoice = $inv_id;
                    Doo::db()->insert($wxobj);

                    //credit log entry
                    Doo::loadModel('ScLogsCredits');
                    $ulcobj = new ScLogsCredits;
                    $ulcobj->user_id = $userid;
                    $ulcobj->timestamp = date(Doo::conf()->date_format_db);
                    $ulcobj->amount = $wcredits;
                    $ulcobj->route_id = 0;
                    $ulcobj->credits_before = $wallet->amount;
                    $ulcobj->credits_after = floatval($wallet->amount) + $wcredits;
                    $ulcobj->reference = 'WALLET CREDIT';
                    $ulcobj->comments = Doo::conf()->reseller_make_transaction . '|| TYPE: CREDIT, INVOICE-ID: ' . $inv_id;
                    Doo::db()->insert($ulcobj);

                    //sale stats
                    $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
                    $stobj->addStat(date('Y-m-d'), $invdata['total_cost']);

                    //create alert for user
                    $alobj = Doo::loadModel('ScUserNotifications', true);
                    $alobj->addAlert($userid, 'success', Doo::conf()->account_credit_alert, 'transactionReports');

                    //if email on alert enabled send email to user
                    $eflobj = Doo::loadModel('ScUsersSettings', true);
                    $eflobj->user_id = $userid;
                    $efl = intval(Doo::db()->find($eflobj, array('limit' => 1, 'select' => 'email_app_notif'))->email_app_notif);
                    if ($efl == 1) {
                        //send mail
                        $cdata = unserialize($_SESSION['webfront']['company_data']);
                        $maildata['company_url'] = Doo::conf()->APP_URL;
                        $maildata['logo'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'];
                        $maildata['name'] = $data['user']->name;
                        $maildata['txntype'] = 'CREDIT';
                        $maildata['login_url'] = Doo::conf()->APP_URL . 'web/sign-in';
                        $maildata['helpline'] = $cdata['helpline'];
                        $maildata['company_domain'] = $_SESSION['webfront']['current_domain'];
                        $mailbody = $this->view()->getRendered('mail/transactionAlert', $maildata);

                        Doo::loadHelper("DooPhpMailer");
                        $mail = DooPhpMailer::getMailObj();

                        $mail->setFrom($cdata['helpmail'], $cdata['company_name']);
                        $mail->Subject  = 'TRANSACTION ALERT || ' . $cdata['company_name'] . ' SMS Portal';
                        $mail->isHTML(true);
                        $mail->Body = $mailbody;
                        $mail->addAddress($data['user']->email);
                        $mail->send();
                        $mail->clearAddresses();
                    }
                }


                //return
                $_SESSION['notif_msg']['msg'] = 'Credits added successfully to user account.';
                $_SESSION['notif_msg']['type'] = 'success';
                return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
            } else {
                //debit account
                //validate if amount is less than wallet and not a negative value
                if ($wcredits > floatval($wallet->amount)) {
                    //cannot deduct more credits
                    $_SESSION['notif_msg']['msg'] = 'Cannot deduct more credits than wallet balance.';
                    $_SESSION['notif_msg']['type'] = 'error';
                    return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
                }
                if ($wcredits <= 0) {
                    $_SESSION['notif_msg']['msg'] = 'Invalid amount for deduction.';
                    $_SESSION['notif_msg']['type'] = 'error';
                    return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
                }
                //create invoice
                if ($data['user']->account_type == '1') {
                    $total = $plandata->tax == 0 ? $wcredits : $wcredits + ($wcredits * ($plandata->tax / 100));

                    switch ($plandata->tax_type) {
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

                    //
                    $invdata['plan_tax'] = $plandata->tax == 0 ? 0 : $plandata->tax . '% ' . $type;
                } else {
                    $total = $wcredits;
                    $invdata['plan_tax'] = 0;
                }
                $invdata['wallet_credits'] = 0 - $wcredits;
                $invdata['total_cost'] = round($total, 2);
                $invdata['additional_tax'] = '0%';
                $invdata['discount'] = 'N/A';
                $invdata['grand_total'] = round($total, 2);
                $invdata['inv_status'] = intval($_POST['invstatus']);
                $invdata['inv_rem'] = DooTextHelper::cleanInput($_POST['d_transremarks']);

                //add invoice set status based on paid status
                Doo::loadModel('ScUsersDocuments');
                $dobj = new ScUsersDocuments;
                $dobj->filename = 'INVOICE_' . $data['user']->login_id . '_' . time();
                $dobj->type = 1;
                $dobj->owner_id = $_SESSION['user']['userid'];
                $dobj->shared_with = $userid;
                $dobj->created_on = date(Doo::conf()->date_format_db);
                $dobj->file_data = serialize($invdata);
                $dobj->file_status = intval($_POST['invstatus']);
                $dobj->init_remarks = $invdata['inv_rem'];
                $inv_id = Doo::db()->insert($dobj);

                //add credits in wallet
                $wlobj->id = $wallet->id;
                $wlobj->amount = floatval($wallet->amount) - $wcredits;
                Doo::db()->update($wlobj);

                //add transaction in wallet txn table
                $wxobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $wxobj->wallet_id = $wallet->id;
                $wxobj->transac_type = 0;
                $wxobj->amount = 0 - $wcredits;
                $wxobj->t_date = date(Doo::conf()->date_format_db);
                $wxobj->linked_invoice = $inv_id;
                Doo::db()->insert($wxobj);

                //credit log entry
                Doo::loadModel('ScLogsCredits');
                $ulcobj = new ScLogsCredits;
                $ulcobj->user_id = $userid;
                $ulcobj->timestamp = date(Doo::conf()->date_format_db);
                $ulcobj->amount = 0 - $wcredits;
                $ulcobj->route_id = 0;
                $ulcobj->credits_before = $wallet->amount;
                $ulcobj->credits_after = floatval($wallet->amount) - $wcredits;
                $ulcobj->reference = 'WALLET CREDIT';
                $ulcobj->comments = Doo::conf()->reseller_make_transaction . '|| TYPE: DEBIT, INVOICE-ID: ' . $inv_id;
                Doo::db()->insert($ulcobj);

                //sale stats
                $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
                $stobj->addStat(date('Y-m-d'), 0 - $invdata['total_cost']);

                //create alert for user
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert($userid, 'success', Doo::conf()->account_debit_alert, 'transactionReports');

                //if email on alert enabled send email to user
                $eflobj = Doo::loadModel('ScUsersSettings', true);
                $eflobj->user_id = $userid;
                $efl = intval(Doo::db()->find($eflobj, array('limit' => 1, 'select' => 'email_app_notif'))->email_app_notif);
                if ($efl == 1) {
                    //send mail
                    $cdata = unserialize($_SESSION['webfront']['company_data']);
                    $maildata['company_url'] = Doo::conf()->APP_URL;
                    $maildata['logo'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'];
                    $maildata['name'] = $data['user']->name;
                    $maildata['txntype'] = 'DEBIT';
                    $maildata['login_url'] = Doo::conf()->APP_URL . 'web/sign-in';
                    $maildata['helpline'] = $cdata['helpline'];
                    $maildata['company_domain'] = $_SESSION['webfront']['current_domain'];
                    $mailbody = $this->view()->getRendered('mail/transactionAlert', $maildata);

                    Doo::loadHelper("DooPhpMailer");
                    $mail = DooPhpMailer::getMailObj();

                    $mail->setFrom($cdata['helpmail'], $cdata['company_name']);
                    $mail->Subject  = 'TRANSACTION ALERT || ' . $cdata['company_name'] . ' SMS Portal';
                    $mail->isHTML(true);
                    $mail->Body = $mailbody;
                    $mail->addAddress($data['user']->email);
                    $mail->send();
                    $mail->clearAddresses();
                }

                //return
                $_SESSION['notif_msg']['msg'] = 'Credits deducted successfully to user account.';
                $_SESSION['notif_msg']['type'] = 'success';
                return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
            }
        } else {
            //credit based account
            Doo::loadModel('ScUsersCreditData');

            //collect values
            $action = $_POST['action'];


            if ($action == 'credit') {
                $route = intval($_POST['c_route']);
                $validity = $_POST['expiry'];
                $credits = intval($_POST['add_cre']);
                $price = floatval($_POST['c_price']);

                if ($credits < 1) {
                    //invalid credit count: generate a warning if credit is less than zero as this is an attempt to manipulate credit balance unethically

                    $actData['activity_type'] = 'INVALID CREDIT';
                    $actData['activity'] = Doo::conf()->invalid_post_value . '|| Possible attack. Negative credit amount. PAGE: processAccountTransaction';
                    $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                    $ulobj->addLog($_SESSION['user']['userid'], $actData);

                    //alert admin
                    $alobj = Doo::loadModel('ScUserNotifications', true);
                    $alobj->addAlert(1, 'danger', Doo::conf()->invalid_post_value . '|| Negative credit amount.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = "Invalid credits. Please enter a number greater than zero.";
                    return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
                }

                //check if sufficient balance
                if ($_SESSION['user']['group'] != 'admin') {
                    $upcobj = new ScUsersCreditData;
                    $upcre = intval($upcobj->getRouteCredits($_SESSION['user']['userid'], $route));
                    if ($credits > $upcre) {
                        //cannot assign more credits than available balance
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = "You cannot assign more credits than available in your account.";
                        return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
                    }
                }

                //create and save invoice
                //collect values
                $pid = intval($_POST['planid']);
                $rdata = array();
                $rdata[0] = json_decode(stripslashes(json_encode(["id" => $route, "credits" => $credits, "price" => $price]))); //it is done like this because we already have a function which uses object array of routes and prices and calculate grand total including taxes
                //echo '<pre>';var_dump($rdata);die;
                $dis = 0;
                $dtype = '';
                $adtx = floatval($_POST['c_utax']);
                if ($pid == 0) {
                    //custom pricing
                    $errcredits = 0;
                    $res = array();
                    $total = 0;
                    foreach ($rdata as $cdata) {
                        $credits = intval($cdata->credits);
                        $rate = floatval($cdata->price);
                        $rid = $cdata->id;
                        if ($credits != 0) {
                            $total += $credits * $rate;
                            $res[$rid]['credits'] = $credits;
                            $res[$rid]['price'] = $rate;
                            $res[$rid]['total'] = $credits * $rate;
                        } else {
                            //null
                            //$rate = $prc;
                            $total += 0;
                            $res[$rid]['credits'] = 0;
                            $res[$rid]['price'] = $rate;
                            $res[$rid]['total'] = 0;
                        }
                    }
                    $res['total'] = $total;
                    $total_price = $total;
                    //calculate cost after tax
                    $total_af_plntax = $total_price;

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    //apply discount: not really

                    $grand_total = $total_af_adtax;


                    //return rate and total cost
                    $res['price'] = $res;
                    $res['total_plan'] = round($total_af_plntax, 5);
                    $res['grand_total'] = round($grand_total, 5);
                    $res['plan_tax'] = '';
                    $res['errcredits'] = $errcredits;

                    //--end of custom pricing

                } else {
                    //plan is assigned
                    Doo::loadModel("ScSmsPlanOptions");
                    $obj = new ScSmsPlanOptions;
                    $pricedata = $obj->getSmsPrice($pid, $rdata);

                    //get the rates from post. Only get applicable plan tax from db
                    Doo::loadModel('ScSmsPlans');
                    $sobj = new ScSmsPlans;
                    $sobj->id = $pid;
                    $taxdata = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'tax, tax_type'));

                    $total_price = $pricedata['total'];


                    //apply plan tax
                    $total_af_plntax = $total_price + ($total_price * $taxdata->tax / 100);

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    //apply discount: not really

                    $grand_total = $total_af_adtax;


                    //return rate and total cost
                    $res['price'] = $pricedata;
                    $res['total_plan'] = round($total_af_plntax, 5);
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
                }

                $invdata['plan_tax'] = $res['plan_tax'];
                $invdata['routes_credits'] = $rdata;
                $invdata['total_cost'] = $res['total_plan'];
                $invdata['additional_tax'] = $adtx . '%';
                $invdata['discount'] = 'N/A';
                $invdata['grand_total'] = $res['grand_total'];
                $invdata['inv_status'] = intval($_POST['invstatus']);
                $invdata['inv_rem'] = DooTextHelper::cleanInput($_POST['c_transremarks']);

                Doo::loadModel('ScUsersDocuments');
                $dobj = new ScUsersDocuments;
                $dobj->filename = 'INVOICE_' . $data['user']->login_id . '_' . time();
                $dobj->type = 1;
                $dobj->owner_id = $_SESSION['user']['userid'];
                $dobj->shared_with = $userid;
                $dobj->created_on = date(Doo::conf()->date_format_db);
                $dobj->file_data = serialize($invdata);
                $dobj->file_status = intval($_POST['invstatus']);
                $dobj->init_remarks = $invdata['inv_rem'];

                $inv_id = Doo::db()->insert($dobj);

                //modify credit data

                //deduct credits
                //deduct balance from own account
                if ($_SESSION['user']['group'] != 'admin') {
                    //reseller account deduct balance
                    $lcobj = Doo::loadModel('ScLogsCredits', true);

                    $creobj = new ScUsersCreditData;
                    $newavcredits = $creobj->doCreditTrans('debit', intval($_SESSION['user']['userid']), $route, $credits);

                    //credit log
                    $lcobj->user_id = $_SESSION['user']['userid'];
                    $lcobj->timestamp = date(Doo::conf()->date_format_db);
                    $lcobj->amount = '-' . $credits;
                    $lcobj->route_id = $route;
                    $lcobj->credits_before = $_SESSION['credits']['routes'][$route]['credits'];
                    $lcobj->credits_after = $newavcredits;
                    $lcobj->reference = 'Credit Account';
                    $lcobj->comments = 'Credit transaction was made on a User Account. Details are:|| <a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $userid . '">link</a>.';
                    Doo::db()->insert($lcobj);

                    $_SESSION['credits']['routes'][$route]['credits'] = $newavcredits;
                }

                //add credits
                $lcobj2 = Doo::loadModel('ScLogsCredits', true);
                $creobj2 = new ScUsersCreditData;
                $subcre = new ScUsersCreditData;
                $olducredits = $subcre->getRouteCredits($userid, $route);
                $newavcredits2 = $creobj2->doCreditTrans('credit', intval($userid), $route, $credits, $validity, $price);

                //credit log
                $lcobj2->user_id = $userid;
                $lcobj2->timestamp = date(Doo::conf()->date_format_db);
                $lcobj2->amount = $credits;
                $lcobj2->route_id = $route;
                $lcobj2->credits_before = $olducredits;
                $lcobj2->credits_after = $newavcredits2;
                $lcobj2->reference = 'Credit Account';
                $lcobj2->comments = 'Credit transaction was made on your account by your Account Manager.';
                Doo::db()->insert($lcobj2);


                //make an entry in transactions table
                $trdata = array();
                $trdata[$route]['credits'] = $credits;
                $trdata[$route]['price'] = $price;

                $trandata['transac_id'] = $data['user']->login_id . rand(0, 100) . time();
                $trandata['cdata'] = $trdata;
                $trandata['transac_by'] = $_SESSION['user']['userid'];
                $trandata['transac_to'] = $userid;
                $trandata['invoice_id'] = $inv_id;

                Doo::loadModel('ScUsersCreditTransactions');
                $tobj = new ScUsersCreditTransactions;
                $tobj->newTransaction('credit', $trandata);

                //make an entry in applicable stats table

                if ($_SESSION['user']['group'] == 'admin') {
                    $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
                    $stobj->addStat(date('Y-m-d'), $invdata['total_cost']);
                } else {
                    $stobj = Doo::loadModel('ScStatsSalesReseller', true);
                    $stobj->addStat(date('Y-m-d'), $_SESSION['user']['userid'], $credits, 0);
                }

                //log activity
                $actData['activity_type'] = 'CREDIT ACCOUNT';
                $actData['activity'] = Doo::conf()->reseller_make_transaction . '|| TYPE: CREDIT, INVOICE-ID: ' . $inv_id;
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //create alert for user
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert($userid, 'success', Doo::conf()->account_credit_alert, 'transactionReports');

                //if email on alert enabled send email to user
                $eflobj = Doo::loadModel('ScUsersSettings', true);
                $eflobj->user_id = $userid;
                $efl = intval(Doo::db()->find($eflobj, array('limit' => 1, 'select' => 'email_app_notif'))->email_app_notif);
                if ($efl == 1) {
                    //send mail
                    $cdata = unserialize($_SESSION['webfront']['company_data']);
                    $maildata['company_url'] = Doo::conf()->APP_URL;
                    $maildata['logo'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'];
                    $maildata['name'] = $data['user']->name;
                    $maildata['txntype'] = 'CREDIT';
                    $maildata['login_url'] = Doo::conf()->APP_URL . 'web/sign-in';
                    $maildata['helpline'] = $cdata['helpline'];
                    $maildata['company_domain'] = $_SESSION['webfront']['current_domain'];
                    $mailbody = $this->view()->getRendered('mail/transactionAlert', $maildata);

                    Doo::loadHelper("DooPhpMailer");
                    $mail = DooPhpMailer::getMailObj();

                    $mail->setFrom($cdata['helpmail'], $cdata['company_name']);
                    $mail->Subject  = 'TRANSACTION ALERT || ' . $cdata['company_name'] . ' SMS Portal';
                    $mail->isHTML(true);
                    $mail->Body = $mailbody;
                    $mail->addAddress($data['user']->email);
                    $mail->send();
                    $mail->clearAddresses();
                }

                //return
                $_SESSION['notif_msg']['msg'] = 'Credits added successfully to user account.';
                $_SESSION['notif_msg']['type'] = 'success';
                return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
            }


            // ---- Debit transaction ---- //

            if ($action == 'debit') {
                $route = intval($_POST['d_route']);
                $credits = intval($_POST['deductcredits']);

                if ($credits < 1) {
                    //invalid credit count: generate a warning if credit is less than zero as this is an attempt to manipulate credit balance unethically

                    $actData['activity_type'] = 'INVALID CREDIT';
                    $actData['activity'] = Doo::conf()->invalid_post_value . '|| Possible attack. Negative credit amount. PAGE: processAccountTransaction';
                    $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                    $ulobj->addLog($_SESSION['user']['userid'], $actData);

                    //alert admin
                    $alobj = Doo::loadModel('ScUserNotifications', true);
                    $alobj->addAlert(1, 'danger', Doo::conf()->invalid_post_value . '|| Negative credit amount.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = "Invalid credits. Please enter a number greater than zero.";
                    return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
                }

                //check if sufficient balance
                $clcreobj = new ScUsersCreditData;
                $clcreobj->user_id = $userid;
                $clcreobj->route_id = $route;
                $clcredata = Doo::db()->find($clcreobj, array('limit' => 1));
                if ($credits > intval($clcredata->credits)) {
                    //cannot deduct more credits than available balance
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = "You cannot deduct more credits than available in user account.";
                    return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
                }


                //create and save invoice
                //collect values
                $pid = intval($_POST['planid']);
                $rdata = array();
                $rdata[0] = json_decode(stripslashes(json_encode(["id" => $route, "credits" => $credits, "price" => $clcredata->price]))); //it is done like this because we already have a function which uses object array of routes and prices and calculate grand total including taxes
                $dis = 0;
                $dtype = '';
                $adtx = floatval($_POST['d_utax']);
                if ($pid == 0) {
                    //custom pricing
                    $errcredits = 0;
                    $res = array();
                    $total = 0;
                    foreach ($rdata as $cdata) {
                        $credits = intval($cdata->credits);
                        $rate = floatval($cdata->price);
                        $rid = $cdata->id;
                        if ($credits != 0) {
                            $total += $credits * $rate;
                            $res[$rid]['credits'] = $credits;
                            $res[$rid]['price'] = $rate;
                            $res[$rid]['total'] = $credits * $rate;
                        } else {
                            //null
                            //$rate = $prc;
                            $total += 0;
                            $res[$rid]['credits'] = 0;
                            $res[$rid]['price'] = $rate;
                            $res[$rid]['total'] = 0;
                        }
                    }
                    $res['total'] = $total;
                    $total_price = $total;
                    //calculate cost after tax
                    $total_af_plntax = $total_price;

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    //apply discount: not really

                    $grand_total = $total_af_adtax;



                    //return rate and total cost
                    $res['price'] = $res;
                    $res['total_plan'] = round($total_af_plntax, 5);
                    $res['grand_total'] = round($grand_total, 5);
                    $res['plan_tax'] = '';
                    $res['errcredits'] = $errcredits;

                    //--end of custom pricing

                } else {
                    //plan is assigned
                    Doo::loadModel("ScSmsPlanOptions");
                    $obj = new ScSmsPlanOptions;
                    $pricedata = $obj->getSmsPrice($pid, $rdata);

                    //get the rates from db. Only get applicable plan tax from db
                    Doo::loadModel('ScSmsPlans');
                    $sobj = new ScSmsPlans;
                    $sobj->id = $pid;
                    $taxdata = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'tax, tax_type'));

                    $total_price = $pricedata['total'];


                    //apply plan tax
                    $total_af_plntax = $total_price + ($total_price * $taxdata->tax / 100);

                    //apply additional tax
                    if ($adtx != 0) {
                        $total_af_adtax = $total_af_plntax + ($total_af_plntax * $adtx / 100);
                    } else {
                        $total_af_adtax = $total_af_plntax;
                    }
                    //apply discount: not really

                    $grand_total = $total_af_adtax;


                    //return rate and total cost
                    $res['price'] = $pricedata;
                    $res['total_plan'] = round($total_af_plntax, 5);
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
                }

                $invdata['plan_tax'] = $res['plan_tax'];
                $invdata['routes_credits'] = $rdata;
                $invdata['total_cost'] = $res['total_plan'];
                $invdata['additional_tax'] = $adtx . '%';
                $invdata['discount'] = 'N/A';
                $invdata['grand_total'] = $res['grand_total'];
                $invdata['inv_status'] = 1; //reverse invoice is paid
                $invdata['inv_rem'] = DooTextHelper::cleanInput($_POST['d_transremarks']);

                Doo::loadModel('ScUsersDocuments');
                $dobj = new ScUsersDocuments;
                $dobj->filename = 'INVOICE_' . $data['user']->login_id . '_' . time();
                $dobj->type = 1;
                $dobj->owner_id = $_SESSION['user']['userid'];
                $dobj->shared_with = $userid;
                $dobj->created_on = date(Doo::conf()->date_format_db);
                $dobj->file_data = serialize($invdata);
                $dobj->file_status = 1;
                $dobj->init_remarks = $invdata['inv_rem'];

                $inv_id = Doo::db()->insert($dobj);

                //modify credit data

                //add credits
                //add balance to own account
                if ($_SESSION['user']['group'] != 'admin') {
                    //reseller account add balance
                    $lcobj = Doo::loadModel('ScLogsCredits', true);

                    $creobj = new ScUsersCreditData;
                    $newavcredits = $creobj->doCreditTrans('credit', intval($_SESSION['user']['userid']), $route, $credits);

                    //credit log
                    $lcobj->user_id = $_SESSION['user']['userid'];
                    $lcobj->timestamp = date(Doo::conf()->date_format_db);
                    $lcobj->amount = $credits;
                    $lcobj->route_id = $route;
                    $lcobj->credits_before = $_SESSION['credits']['routes'][$route]['credits'];
                    $lcobj->credits_after = $newavcredits;
                    $lcobj->reference = 'Credit Deduction from downline';
                    $lcobj->comments = 'Debit transaction was made on a User Account. User is:|| <a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $userid . '">link</a>.';
                    Doo::db()->insert($lcobj);

                    $_SESSION['credits']['routes'][$route]['credits'] = $newavcredits;
                }

                //deduct credits
                $lcobj2 = Doo::loadModel('ScLogsCredits', true);
                $creobj2 = new ScUsersCreditData;
                $olducredits = $clcredata->credits;
                $newavcredits2 = $creobj2->doCreditTrans('debit', intval($userid), $route, $credits);

                //credit log
                $lcobj2->user_id = $userid;
                $lcobj2->timestamp = date(Doo::conf()->date_format_db);
                $lcobj2->amount = '-' . $credits;
                $lcobj2->route_id = $route;
                $lcobj2->credits_before = $olducredits;
                $lcobj2->credits_after = $newavcredits2;
                $lcobj2->reference = 'Debit Account';
                $lcobj2->comments = 'Debit transaction was made on your account by your Account Manager.';
                Doo::db()->insert($lcobj2);


                //make an entry in transactions table
                $trdata = array();
                $trdata[$route]['credits'] = $credits;
                $trdata[$route]['price'] = $clcredata->price;

                $trandata['transac_id'] = $data['user']->login_id . rand(0, 100) . time();
                $trandata['cdata'] = $trdata;
                $trandata['transac_by'] = $_SESSION['user']['userid'];
                $trandata['transac_to'] = $userid;
                $trandata['invoice_id'] = $inv_id;

                Doo::loadModel('ScUsersCreditTransactions');
                $tobj = new ScUsersCreditTransactions;
                $tobj->newTransaction('debit', $trandata);

                //add refund amount to wallet if applicable
                if ($_POST['invstatus'] == '2') {
                    $wobj = Doo::loadModel('ScUsersWallet', true);
                    $wobj->user_id = $userid;
                    $wdata = Doo::db()->find($wobj, array('limit' => 1));
                    if ($wdata->id) {
                        $wobj->amount = $wdata->amount + floatval($invdata['grand_total']);
                        $wobj->id = $wdata->id;
                        Doo::db()->update($wobj, array('limit' => 1));

                        //add in wallet transaction history
                        $wtobj = Doo::loadModel('ScUsersWalletTransactions', true);
                        $wtobj->wallet_id = $wdata->id;
                        $wtobj->transac_type = 1; //0 debit, 1 credit
                        $wtobj->amount = floatval($invdata['grand_total']);
                        $wtobj->t_date = date(Doo::conf()->date_format_db);
                        $wtobj->linked_invoice = $inv_id;

                        Doo::db()->insert($wtobj);
                    }
                }

                //make an entry in applicable stats table
                if ($_SESSION['user']['group'] == 'admin') {
                    $stobj = Doo::loadModel('ScStatsSalesAdmin', true);
                    $stobj->addStat(date('Y-m-d'), 0 - $invdata['total_cost']);
                } else {
                    $stobj = Doo::loadModel('ScStatsSalesReseller', true);
                    $stobj->addStat(date('Y-m-d'), $_SESSION['user']['userid'], 0 - intval($credits), 0);
                }
                //log activity
                $actData['activity_type'] = 'CREDIT ACCOUNT';
                $actData['activity'] = Doo::conf()->reseller_make_transaction . '|| TYPE: DEBIT, INVOICE-ID: ' . $inv_id;
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //create alert for user
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert($userid, 'info', Doo::conf()->account_debit_alert, 'transactionReports');

                //if email on alert enabled send email to user
                $eflobj = Doo::loadModel('ScUsersSettings', true);
                $eflobj->user_id = $userid;
                $efl = intval(Doo::db()->find($eflobj, array('limit' => 1, 'select' => 'email_app_notif'))->email_app_notif);
                if ($efl == 1) {
                    //send mail
                    $cdata = unserialize($_SESSION['webfront']['company_data']);
                    $maildata['company_url'] = Doo::conf()->APP_URL;
                    $maildata['logo'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'];
                    $maildata['name'] = $data['user']->name;
                    $maildata['txntype'] = 'DEBIT';
                    $maildata['login_url'] = Doo::conf()->APP_URL . 'web/sign-in';
                    $maildata['helpline'] = $cdata['helpline'];
                    $maildata['company_domain'] = $_SESSION['webfront']['current_domain'];
                    $mailbody = $this->view()->getRendered('mail/transactionAlert', $maildata);

                    Doo::loadHelper("DooPhpMailer");
                    $mail = DooPhpMailer::getMailObj();

                    $mail->setFrom($cdata['helpmail'], $cdata['company_name']);
                    $mail->Subject  = 'TRANSACTION ALERT || ' . $cdata['company_name'] . ' SMS Portal';
                    $mail->isHTML(true);
                    $mail->Body = $mailbody;
                    $mail->addAddress($data['user']->email);
                    $mail->send();
                    $mail->clearAddresses();
                }

                //return
                $_SESSION['notif_msg']['msg'] = 'Credits deducted successfully from user account.';
                $_SESSION['notif_msg']['type'] = 'success';
                return Doo::conf()->APP_URL . 'makeAccountTransaction/' . $userid;
            }
        }
    }

    public function viewUserTransactions()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['logs'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserTransactions';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: viewUserTransactions';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'Transaction History';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_tran_history';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaTranHistory', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewUserCreditLog()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['logs'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserCreditLog';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($data['user']->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: viewUserCreditLog';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'Credit Log';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_crelog';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaCreditLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewUserAccountSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['permissions']['user']['set'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['group'] == 'reseller') {
            //only admin can see this
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $uid = intval($this->params['id']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: viewUserAccountSettings';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($uid);

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($data['user']->upline_id != $_SESSION['user']['userid'] && $data['user']->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }
        //all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        //get user permissions
        $upobj = Doo::loadModel('ScUsersPermissions', true);
        $upobj->user_id = $uid;
        $data['uperm'] = Doo::db()->find($upobj, array('limit' => 1));
        //get all permissions
        $permobj = Doo::loadModel('ScPermissionGroups', true);
        $data['pgroups'] = Doo::db()->find($permobj);

        //get whitelist numbers
        $wconobj = Doo::loadModel('ScUsersWhitelist', true);
        $wconobj->user_id = $uid;
        $data['wcon'] = Doo::db()->find($wconobj, array('select' => 'mobiles', 'limit' => 1));

        //get all phonebook groups
        $pbobj = Doo::loadModel('ScPhonebookGroups', true);
        $pbobj->status = 1;
        $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id'));

        //get assigned phonebook groups
        $upbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
        $upbobj->user_id = $uid;
        $data['upbdb'] = Doo::db()->find($upbobj, array('limit' => 1));

        //get all vmn and keywords
        $vmnobj = Doo::loadModel('ScVmnList', true);
        $data['vmns'] = Doo::db()->find($vmnobj);
        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
        $opt['filters'] = array();
        $opt['filters'][0]['model'] = 'ScVmnList';
        $opt['select'] = 'sc_vmn_primary_keywords.id,sc_vmn_primary_keywords.keyword,sc_vmn_primary_keywords.user_assigned,sc_vmn_list.vmn,sc_vmn_list.type';
        $opt['where'] = 'sc_vmn_primary_keywords.added_by=1';
        $data['kws'] = Doo::db()->find($kobj, $opt);

        //get hlr settings for user
        $uhlrobj = Doo::loadModel('ScUsersHlrSettings', true);
        $uhlrobj->user_id = $uid;
        $data['hlrinfo'] = Doo::db()->find($uhlrobj, array('limit' => 1));
        $hlrobj = Doo::loadModel('ScHlrChannels', true);
        $data['channels'] = Doo::db()->find($hlrobj);

        //get custom tlv labels for this user
        $custqry = "SELECT tlv_category, CONCAT_WS('|', custom_label, default_value) as cusdata FROM sc_users_tlv_defaults WHERE user_id = " . intval($uid);
        $data['customTlvLabels'] = Doo::db()->fetchAll($custqry, null, PDO::FETCH_KEY_PAIR);

        //get all the tlvs possible for this account
        $usercreobj = Doo::loadModel('ScUsersCreditData', true);
        $usercreobj->user_id = $uid;
        $usercreobj->status = 0;
        $usercredits = Doo::db()->find($usercreobj, array('select' => 'route_id'));
        $userroutes = [];
        foreach ($usercredits as $userdata) {
            array_push($userroutes, $userdata->route_id);
        }
        if (sizeof($userroutes) > 0) {
            $assignedRoutes = implode(",", $userroutes);
            $routeobj = Doo::loadModel('ScSmsRoutes', true);
            $tlvopt['select'] = 'tlv_ids';
            $tlvopt['where'] = "id IN ( $assignedRoutes ) AND tlv_ids <> ''";
            $routetlvs = Doo::db()->find($routeobj, $tlvopt);
            $allUserTlvs = [];
            foreach ($routetlvs as $tdata) {
                $tlvs = json_decode($tdata->tlv_ids);
                foreach ($tlvs as $tlv_category) {
                    array_push($allUserTlvs, $tlv_category);
                }
            }
            $data['allUserTlvs'] = $allUserTlvs;
        }


        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['active_page'] = 'User Account Settings';

        $data['page'] = 'User Management';
        $data['current_page'] = 'va_uset';
        $data['page_family'] = 'view_account';
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/vaAccountSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function markInvoiceStatus()
    {
        //get data
        $docid = intval($_POST['docid']);
        $action = intval($_POST['action']);

        if ($docid == 0 || $action == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid data passed!';
            exit;
        }

        //check if invoice exist
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $docid;
        $docdata = Doo::db()->find($docobj, array('limit' => 1));

        if (!$docdata->owner_id) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid document requested!';
            exit;
        }

        //check if owner is performing the action
        if ($docdata->owner_id != $_SESSION['user']['userid']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'You are not allowed to perform this operation as you have not issued this invoice';
            exit;
        }

        //add credits if not already added in case invoice is being marked as paid
        if ($action == 1) {
            $usrobj = Doo::loadModel('ScUsers', true);
            $uinfo = $usrobj->getProfileInfo($docdata->shared_with);
            $invdata = unserialize($docdata->file_data);
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
                $wxobj->linked_invoice = $docid;
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
                $ulcobj->comments = Doo::conf()->reseller_make_transaction . '|| TYPE: CREDIT, INVOICE-ID: ' . $docid;
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
                $ctcheckobj->invoice_id = $docid;
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
                    $trandata['invoice_id'] = $docid;
                    $tobj = new ScUsersCreditTransactions;
                    $tobj->newTransaction('credit', $trandata);
                }
            }

            //add a remark with transaction detail
            $remark = '[AUTO-GENERATED] Invoice Settled By Issuer.';
            $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
            $drobj->user_id = $uinfo->user_id;
            $drobj->file_id = $docid;
            $drobj->remark_text = $remark;
            Doo::db()->insert($drobj);
        }
        //mark invoice as specified
        $docobj->file_status = $action;
        Doo::db()->update($docobj, array('limit' => 1));

        //record activity
        $actData['activity_type'] = 'INVOICE STATUS';
        $actData['activity'] = Doo::conf()->document_status_changed . '|| INVOICE-ID:' . $docid . ' STATUS:' . $action;
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);

        //send alert to all associated users
        $uids = explode(",", $docdata->shared_with);
        if (sizeof($uids) > 0) {
            $alobj = Doo::loadModel('ScUserNotifications', true);
            foreach ($uids as $uid) {
                $alobj->addAlert($uid, 'info', Doo::conf()->document_status_changed . '|| INVOICE', 'viewDocument/' . $docid);
            }
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Invoice status changed successfully';
        exit;
    }

    public function markAgreementStatus()
    {
        //get data
        $docid = intval($_POST['docid']);
        $action = intval($_POST['action']);

        if ($docid == 0 || $action == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid data passed!';
            exit;
        }

        //check if invoice exist
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $docid;
        $docdata = Doo::db()->find($docobj, array('limit' => 1));

        if (!$docdata->owner_id) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid document requested!';
            exit;
        }

        //mark invoice as specified
        $docobj->file_status = $action;
        Doo::db()->update($docobj, array('limit' => 1));

        //record activity
        $actData['activity_type'] = 'AGREEMENT STATUS';
        $actData['activity'] = Doo::conf()->agreement_status_changed . '|| AGREEMENT-ID:' . $docid . ' STATUS:' . $action;
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);

        //send alert to the user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($docdata->owner_id, 'info', Doo::conf()->agreement_status_changed . '|| AGREEMENT', 'viewDocument/' . $docid);


        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Agreement status changed successfully';
        exit;
    }

    public function accountActions()
    {
        $uid = intval($this->params['uid']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: accountActions';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            exit;
        }
        //get user details
        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $udata = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($udata->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: accountActions';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                exit;
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($udata->upline_id != $_SESSION['user']['userid'] && $udata->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                exit;
            }
        }


        //upgrade
        if ($this->params['action'] == 'upgrade') {
            if ($udata->account_type == '1') {
                $_SESSION['notif_msg']['msg'] = 'Currency based accounts cannot be upgraded to Reseller';
                $_SESSION['notif_msg']['type'] = 'error';
            } else {
                $uobj->category = 'reseller';
                $uobj->subgroup = 'reseller';
                Doo::db()->update($uobj, array('where' => 'user_id=' . $uid, 'limit' => 1));

                $_SESSION['notif_msg']['msg'] = 'User account has been upgraded to Reseller';
                $_SESSION['notif_msg']['type'] = 'success';
            }

            exit;
        }

        //suspend
        if ($this->params['action'] == 'suspend') {
            //suspend complete downline user tree
            $utobj = Doo::loadModel('DooSmppcubeHelper', true);
            $usrar = $utobj->getUserTree($uid);
            $usrstr = implode(",", $usrar);

            $uobj2 = new ScUsers;
            $uobj2->status = 2;
            Doo::db()->update($uobj2, array('where' => "user_id IN($usrstr)"));

            $_SESSION['notif_msg']['msg'] = $this->SCTEXT('Suspend successful. Total accounts suspended:') . sizeof($usrar);
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }

        //delete
        if ($this->params['action'] == 'delete') {

            //get credits
            Doo::loadModel('ScUsersCreditData');
            $crobj = new ScUsersCreditData;
            $crobj->user_id = $uid;
            $crdata = Doo::db()->find($crobj);

            //add credits to corresponding reseller

            $rcrobj = new ScUsersCreditData;
            $lcobj = Doo::loadModel('ScLogsCredits', true);
            foreach ($crdata as $crd) {
                $res = $rcrobj->doCreditTrans('credit', $udata->upline_id, $crd->route_id, $crd->credits, '', 0, true);
                if ($res != false) {
                    //make log entry
                    $lcobj->user_id = $udata->upline_id;
                    $lcobj->timestamp = date(Doo::conf()->date_format_db);
                    $lcobj->amount = $crd->credits;
                    $lcobj->route_id = $crd->route_id;
                    $lcobj->credits_before = $res['old'];
                    $lcobj->credits_after = $res['new'];
                    $lcobj->reference = 'User Delete';
                    $lcobj->comments = 'Available credits for the user were credited back to your account';
                    Doo::db()->insert($lcobj);
                }
            }

            //remove credit data for the user
            Doo::db()->delete($crobj);

            //if reseller, allot all the downline to corresponding reseller
            if ($udata->category == 'reseller') {
                $ruobj = new ScUsers;
                $ruobj->upline_id = $udata->upline_id;
                $ruobj->acc_mgr_id = $udata->upline_id;

                Doo::db()->update($ruobj, array('where' => 'upline_id=' . $uid));
            }

            //cancel any pending invoices
            $invobj = Doo::loadModel('ScUsersDocuments', true);
            $invobj->file_status = 3;
            Doo::db()->update($invobj, array('where' => 'type = 1 AND shared_with=' . $uid));

            //delete all contacts and groups
            $ucobj = Doo::loadModel('ScUserContacts', true);
            $ucobj->user_id = $uid;
            Doo::db()->delete($ucobj);

            $ugobj = Doo::loadModel('ScUserContactGroups', true);
            $ugobj->user_id = $uid;
            Doo::db()->delete($ugobj);

            //delete smpp clients
            $scobj = Doo::loadModel('ScSmppClients', true);
            $scobj->user_id = $uid;
            Doo::db()->delete($scobj);

            //log this action in activity log with some details of the deleted user
            $actData['activity_type'] = 'DELETE USER';
            $actData['activity'] = Doo::conf()->user_account_delete . '|| USERID: ' . $uid;
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //delete user
            $delaccobj = Doo::loadModel('ScUsersDeletedAccounts', true);
            $delaccobj->user_id = $uid;
            $delaccobj->login_id = $udata->login_id;
            $delaccobj->name = $udata->name;
            $delaccobj->gender = $udata->gender;
            $delaccobj->avatar = $udata->avatar;
            $delaccobj->category = $udata->category;
            $delaccobj->subgroup = $udata->subgroup;
            $delaccobj->mobile = $udata->mobile;
            $delaccobj->email = $udata->email;
            $delaccobj->email_verifed = $udata->email_verifed;
            $delaccobj->mobile_verified = $udata->mobile_verified;
            $delaccobj->upline_id = $udata->upline_id;
            $delaccobj->acc_mgr_id = $udata->acc_mgr_id;
            $delaccobj->spam_status = $udata->spam_status;
            $delaccobj->opentemp_flag = $udata->opentemp_flag;
            $delaccobj->registered_on = $udata->registered_on;
            $delaccobj->last_login_ip = $udata->last_login_ip;
            $delaccobj->last_activity = $udata->last_activity;

            Doo::db()->insert($delaccobj);

            Doo::db()->delete($uobj, array('where' => 'user_id=' . $uid, 'limit' => 1));

            //return
            $_SESSION['notif_msg']['msg'] = 'User account has been deleted successfully.';
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }
    }

    public function websiteToggle()
    {
        $uid = intval($_POST['uid']);
        $status = intval($_POST['status']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: websiteToggle';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $udata = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($udata->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: websiteToggle';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($udata->upline_id != $_SESSION['user']['userid'] && $udata->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }


        //action
        $wobj = Doo::loadModel('ScWebsites', true);
        $wobj->status = $status;
        Doo::db()->update($wobj, array('where' => 'user_id=' . $uid));

        $_SESSION['notif_msg']['msg'] = 'Website status changed successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        exit;
    }

    public function activateUserAccount()
    {
        $uid = intval($this->params['uid']);
        if ($uid == 0 || $uid == $_SESSION['user']['userid']) {
            //log
            $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
            $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. PAGE: activateUserAccount';
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);

            //alert admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

            //return
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'User not found.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        //get user details
        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $udata = $uobj->getProfileInfo($uid);

        //reseller can only view downline
        if ($_SESSION['user']['group'] == 'reseller') {
            if ($udata->upline_id != $_SESSION['user']['userid']) {
                //log
                $actData['activity_type'] = 'UNAUTHORIZED ACCESS';
                $actData['activity'] = Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper. Open user from other downline. PAGE: activateUserAccount';
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);

                //alert admin
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', Doo::conf()->unauthorized_user_access . '|| Possible URL Tamper.', 'viewUserAccount/' . $_SESSION['user']['userid']);

                //return
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'User not found.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //staff can only see users assigned or created
        if ($_SESSION['user']['subgroup'] == 'staff') {
            if ($udata->upline_id != $_SESSION['user']['userid'] && $udata->acc_mgr_id != $_SESSION['user']['userid']) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You can manage users only in your downline.';
                return Doo::conf()->APP_URL . 'manageUsers';
            }
        }

        //only admin can activate if account is blocked (due to spam etc)
        if ($udata->status == '3' && $_SESSION['user']['group'] != 'admin') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed.';
            return Doo::conf()->APP_URL . 'manageUsers';
        }

        //activate complete downline user tree
        $utobj = Doo::loadModel('DooSmppcubeHelper', true);
        $usrar = $utobj->getUserTree($uid);
        $usrstr = implode(",", $usrar);

        $uobj2 = new ScUsers;
        $uobj2->status = 1;
        Doo::db()->update($uobj2, array('where' => "user_id IN($usrstr)"));

        $_SESSION['notif_msg']['msg'] = 'User account and downline has been activated successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageInactiveUsers';
    }





    //2. Website Management

    public function genWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'General Settings';

        //get the website data
        Doo::loadModel('ScWebsites');
        $wobj = new ScWebsites;
        $wobj->user_id = $_SESSION['user']['userid'];
        $data['wdata'] = Doo::db()->find($wobj, array('limit' => 1));

        $data['page'] = 'Website Management';
        $data['current_page'] = 'gen_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/genWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $domstr = $_POST['domains'];
        $ftype = intval($_POST['fronttype']);
        $logo = $_POST['uploadedFiles'][0];

        //validate domain
        Doo::loadModel('ScWebsites');
        $wobj = new ScWebsites;
        $res = $wobj->checkDomains($domstr, $_SESSION['user']['userid']);
        if ($res != 'none') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = '<b>' . $res . '</b> ' . $this->SCTEXT('The domain name already exists. Please remove this domain from your list. Contact administrator if this is your domain.');
            return Doo::conf()->APP_URL . 'genWebSettings';
        }
        //prepare site data
        $sdata['company_name'] = $_POST['comname'];
        $sdata['helpline'] = $_POST['helpline'];
        $sdata['helpmail'] = $_POST['helpmail'];
        $sdata['logout_url'] = $_POST['outurl'];
        $sdata['theme'] = $_POST['theme'];
        $sdata['tnc'] = htmlspecialchars($_POST['tnc']);
        $sdata['policy'] = htmlspecialchars($_POST['prpolicy']);

        //update data
        $wuobj = new ScWebsites;
        $wuobj->domains = $domstr;
        $wuobj->logo = $logo;
        $wuobj->site_data = serialize($sdata);
        $wuobj->front_type = $ftype;

        Doo::db()->update($wuobj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));

        //apply color theme
        $_SESSION['webfront']['intheme'] = $_POST['theme'];

        //redirect
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Website settings saved successfully';
        return Doo::conf()->APP_URL . 'genWebSettings';
    }

    public function signupWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'Sign up Settings';

        //get all routes
        if ($_SESSION['user']['group'] == 'admin') {
            Doo::loadModel('ScSmsRoutes');
            $obj = new ScSmsRoutes;
            $data['rdata'] = Doo::db()->find($obj, array('select' => 'id, title'));
        } else {
            $qry = 'SELECT id, title FROM sc_sms_routes WHERE id IN (SELECT route_id FROM sc_users_credit_data WHERE user_id = ' . $_SESSION['user']['userid'] . ' AND status=0)';
            $data['rdata'] = Doo::db()->fetchAll($qry, null, PDO::FETCH_OBJ);
        }

        //get all sender id
        Doo::loadModel('ScSenderId');
        $sobj = new ScSenderId;
        $sobj->req_by = $_SESSION['user']['userid'];
        $data['sdata'] = Doo::db()->find($sobj, array('select' => 'id, sender_id'));

        //get all staff if admin
        if ($_SESSION['user']['group'] == 'admin') {
            Doo::loadModel('ScUsers');
            $obj = new ScUsers;
            $data['staff'] = $obj->getAllStaff();
        }

        //get website signup settings
        Doo::loadModel('ScWebsitesSignupSettings');
        $stobj = new ScWebsitesSignupSettings;
        $stobj->user_id = $_SESSION['user']['userid'];
        $data['stdata'] = Doo::db()->find($stobj, array('limit' => 1));

        if (!$data['stdata']->id) {
            $data['notif_msg']['msg'] = "You haven't completed the Notification & Sign up settings. Please save the settings for proper working of your reseller features.";
            $data['notif_msg']['type'] = 'warning';
        }

        $data['page'] = 'Website Management';
        $data['current_page'] = 'sig_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/sigWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveSignupSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //prepare data
        $ndata['email'] = intval($_POST['signupmail']);
        $ndata['sms'] = intval($_POST['signupsms']);
        $ndata['pass_flag'] = intval($_POST['sendpass']);
        $ndata['sms_route'] = intval($_POST['smsrt']);
        $ndata['sms_sid'] = $_POST['smssid'];

        $sdata['def_route'] = intval($_POST['defrt']);
        $sdata['free_credits'] = intval($_POST['frecre']);
        $sdata['sms_rate'] = floatval($_POST['smsrate']);
        $sdata['taxper'] = floatval($_POST['ptax']);
        $sdata['taxtype'] = $_POST['taxtype'];
        $sdata['def_validity'] = $_POST['defval'];
        $sdata['def_sender'] = $_POST['defsid'];
        $sdata['optin'] = intval($_POST['optperm']);
        $sdata['acc_mgr'] = intval($_POST['staff']);

        //save
        Doo::loadModel('ScWebsitesSignupSettings');
        $stobj = new ScWebsitesSignupSettings;
        $stobj->user_id = $_SESSION['user']['userid'];
        $res = Doo::db()->find($stobj, array('limit' => 1));

        if ($res->id) {
            //update
            $stobj->id = $res->id;
            $stobj->notif_data = serialize($ndata);
            $stobj->signup_data = serialize($sdata);
            Doo::db()->update($stobj, array('limit' => 1));
        } else {
            //insert
            $stobj->site_id = $_SESSION['webfront']['id'];
            $stobj->notif_data = serialize($ndata);
            $stobj->signup_data = serialize($sdata);
            Doo::db()->insert($stobj);
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Website Sign up settings saved successfully';
        return Doo::conf()->APP_URL . 'signupWebSettings';
    }

    public function themeWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'Theme Settings';

        //get the website data
        Doo::loadModel('ScWebsites');
        $wobj = new ScWebsites;
        $wobj->user_id = $_SESSION['user']['userid'];
        $data['tdata'] = Doo::db()->find($wobj, array('limit' => 1, 'select' => 'id,skin_data'));

        $data['page'] = 'Website Management';
        $data['current_page'] = 'thm_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/thmWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function updateThemeSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //prepare data
        $tdata['name'] = DooTextHelper::cleanInput($_POST['tname']);
        $tdata['code'] = $_POST['ccode'];
        $tdata['color'] = DooTextHelper::cleanInput($_POST['cname']);
        //update
        Doo::loadModel('ScWebsites');
        $obj = new ScWebsites;
        $obj->skin_data = serialize($tdata);
        Doo::db()->update($obj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Website theme updated successfully';
        return Doo::conf()->APP_URL . 'themeWebSettings';
    }

    public function homeWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'Homepage Settings';

        //get the website homepage data
        Doo::loadModel('ScWebsitesPageData');
        $pobj = new ScWebsitesPageData;
        $pobj->page_type = 'HOME';
        $data['pdata'] = Doo::db()->find($pobj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));

        //get all routes
        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $data['rdata'] = Doo::db()->find($obj, array('select' => 'id, title'));
        //get all sender id
        Doo::loadModel('ScSenderId');
        $sobj = new ScSenderId;
        $sobj->req_by = $_SESSION['user']['userid'];
        $data['sdata'] = Doo::db()->find($sobj, array('select' => 'id, sender_id'));


        $data['page'] = 'Website Management';
        $data['current_page'] = 'home_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/homeWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function aboutWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'About page Settings';

        //get the website homepage data
        Doo::loadModel('ScWebsitesPageData');
        $pobj = new ScWebsitesPageData;
        $pobj->page_type = 'ABOUT';
        $data['pdata'] = Doo::db()->find($pobj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));

        $data['page'] = 'Website Management';
        $data['current_page'] = 'about_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/aboutWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function pricingWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'Pricing page Settings';

        //get the website homepage data
        Doo::loadModel('ScWebsitesPageData');
        $pobj = new ScWebsitesPageData;
        $pobj->page_type = 'PRICING';
        $data['pdata'] = Doo::db()->find($pobj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));

        $data['page'] = 'Website Management';
        $data['current_page'] = 'pricing_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/pricingWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function contactWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'Contact page Settings';

        //get the website homepage data
        Doo::loadModel('ScWebsitesPageData');
        $pobj = new ScWebsitesPageData;
        $pobj->page_type = 'CONTACT';
        $data['pdata'] = Doo::db()->find($pobj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));

        $data['page'] = 'Website Management';
        $data['current_page'] = 'contact_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/contactWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function loginWebSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Website Management'] = 'javascript:void(0);';
        $data['active_page'] = 'Login page Settings';

        //get the website homepage data
        Doo::loadModel('ScWebsitesPageData');
        $pobj = new ScWebsitesPageData;
        $pobj->page_type = 'LOGIN';
        $data['pdata'] = Doo::db()->find($pobj, array('limit' => 1, 'where' => 'user_id=' . $_SESSION['user']['userid']));

        $data['page'] = 'Website Management';
        $data['current_page'] = 'login_web_set';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/loginWebSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveWebPageSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //collect and prepare values
        $page = DooTextHelper::cleanInput($_POST['page']);
        if ($page == 'HOME') {
            //upload files
            Doo::loadHelper('DooFile');
            $doofile = new DooFile;
            $imgBanners = array();

            //validate files
            for ($i = 1; $i < 5; $i++) {
                $banner = 'sld-' . $i . '-img';
                if (file_exists($_FILES[$banner]['tmp_name']) && is_uploaded_file($_FILES[$banner]['tmp_name'])) {
                    //file is uploaded
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimetype = finfo_file($finfo, $_FILES[$banner]['tmp_name']);
                    finfo_close($finfo);
                    $allowed_mime_types = array(
                        'image/png',
                        'image/jpeg'
                    );
                    $allowed_extentions = array('png', 'jpg', 'jpeg');

                    //specific checks for image
                    list($width, $height, $type, $attr) = getimagesize($_FILES[$banner]['tmp_name']);
                    $img_mime = image_type_to_mime_type($type);

                    if (!$width) {
                        $fail = 1;
                        $reason = $this->SCTEXT('Invalid File supplied. Please provide valid image file for banner #') . $i;
                    }

                    //check if extension is among allowed ones
                    if (!$doofile->checkFileExtension($banner, $allowed_extentions)) {
                        $fail = 1;
                        $reason = $this->SCTEXT('Invalid File supplied. Please provide png, jpg or jpeg file for banner #') . $i;
                    }
                    //check if mime type is among allowed ones
                    if (!in_array($mimetype, $allowed_mime_types) || !in_array($img_mime, $allowed_mime_types)) {
                        $fail = 1;
                        $reason = $this->SCTEXT('Invalid File supplied. Please provide valid image file for banner #') . $i;
                    }

                    //return
                    if ($fail == 0) {
                        //rename and upload file
                        $newfile = $doofile->upload(Doo::conf()->image_upload_dir . 'banners/', $banner);
                        $vals['count'] = $i;
                        $vals['image'] = $newfile;
                        $vals['title'] = DooTextHelper::cleanInput($_POST['sld-' . $i . '-t'], ' ', 0);
                        $vals['desc'] = DooTextHelper::cleanInput($_POST['sld-' . $i . '-d'], ' @.', 0);
                        array_push($imgBanners, $vals);
                    } else {
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = $reason;
                        return Doo::conf()->APP_URL . 'homeWebSettings';
                    }
                } else {
                    //file was not uploaded
                    //may be file was already there and reseller only changed title or decription of one of the banners
                    $vals['count'] = $i;
                    $vals['image'] = DooTextHelper::cleanInput($_POST['sld-' . $i . '-oldimg'], '.');
                    $vals['title'] = DooTextHelper::cleanInput($_POST['sld-' . $i . '-t'], ' ', 0);
                    $vals['desc'] = DooTextHelper::cleanInput($_POST['sld-' . $i . '-d'], ' @.', 0);
                    array_push($imgBanners, $vals);
                }
            }

            //prepare data
            $sitedata['title'] = $_POST['pgtitle']; //DooTextHelper::cleanInput($_POST['pgtitle'],' |',0);
            $sitedata['metadesc'] = DooTextHelper::cleanInput($_POST['metadesc'], ' ,.', 0);
            $sitedata['twgflag'] = intval($_POST['twgflag']);
            $sitedata['twgdata']['title'] = $_POST['twgtitle']; //DooTextHelper::cleanInput($_POST['twgtitle'],' .!',0);
            $sitedata['twgdata']['route'] = intval($_POST['twgrt']);
            $sitedata['twgdata']['sender'] = DooTextHelper::cleanInput($_POST['twgsid']);
            $sitedata['twgdata']['sms'] = $_POST['twgsms'];
            $sitedata['content'] = htmlspecialchars($_POST['homecnt']);
            $sitedata['sliderflag'] = intval($_POST['sldflag']);
            $sitedata['sliderdata'] = $imgBanners;
            //insert
            Doo::loadModel('ScWebsitesPageData');
            $obj = new ScWebsitesPageData;
            $obj->page_type = 'HOME';
            $res = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id', 'where' => 'user_id=' . $_SESSION['user']['userid']));

            if (!$res->id) {
                //insert
                $obj->site_id = $_SESSION['webfront']['id'];
                $obj->user_id = $_SESSION['user']['userid'];
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->insert($obj);
            } else {
                //update
                $obj->id = $res->id;
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->update($obj, array('limit' => 1,));
            }
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Home page settings saved successfully';
            return Doo::conf()->APP_URL . 'homeWebSettings';
        }

        if ($page == 'ABOUT') {
            //prepare data
            $sitedata['title'] = $_POST['pgtitle']; //DooTextHelper::cleanInput($_POST['pgtitle'],' |',0);
            $sitedata['metadesc'] = DooTextHelper::cleanInput($_POST['metadesc'], ' ,.', 0);
            $sitedata['content'] = htmlspecialchars($_POST['content']);

            //insert
            Doo::loadModel('ScWebsitesPageData');
            $obj = new ScWebsitesPageData;
            $obj->page_type = 'ABOUT';
            $res = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id', 'where' => 'user_id=' . $_SESSION['user']['userid']));

            if (!$res->id) {
                //insert
                $obj->site_id = $_SESSION['webfront']['id'];
                $obj->user_id = $_SESSION['user']['userid'];
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->insert($obj);
            } else {
                //update
                $obj->id = $res->id;
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->update($obj, array('limit' => 1,));
            }
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'About page settings saved successfully';
            return Doo::conf()->APP_URL . 'aboutWebSettings';
        }

        if ($page == 'PRICING') {
            //prepare data
            $sitedata['title'] = $_POST['pgtitle']; //DooTextHelper::cleanInput($_POST['pgtitle'],' |',0);
            $sitedata['metadesc'] = DooTextHelper::cleanInput($_POST['metadesc'], ' ,.', 0);
            $sitedata['content'] = htmlspecialchars($_POST['content']);

            //insert
            Doo::loadModel('ScWebsitesPageData');
            $obj = new ScWebsitesPageData;
            $obj->page_type = 'PRICING';
            $res = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id', 'where' => 'user_id=' . $_SESSION['user']['userid']));

            if (!$res->id) {
                //insert
                $obj->site_id = $_SESSION['webfront']['id'];
                $obj->user_id = $_SESSION['user']['userid'];
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->insert($obj);
            } else {
                //update
                $obj->id = $res->id;
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->update($obj, array('limit' => 1,));
            }
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Pricing page settings saved successfully';
            return Doo::conf()->APP_URL . 'pricingWebSettings';
        }

        if ($page == 'CONTACT') {
            //prepare data
            $sitedata['title'] = $_POST['pgtitle']; //DooTextHelper::cleanInput($_POST['pgtitle'],' |',0);
            $sitedata['metadesc'] = DooTextHelper::cleanInput($_POST['metadesc'], ' ,.', 0);
            $sitedata['address'] = $_POST['address']; // DooTextHelper::cleanInput($_POST['address'], '\n ,.()', 0);
            $sitedata['qmail'] = $_POST['qmail'];
            //validate
            if (!DooTextHelper::verifyFormData('email', $_POST['qmail'])) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid email address provided.';
                return Doo::conf()->APP_URL . 'contactWebSettings';
            }
            //insert
            Doo::loadModel('ScWebsitesPageData');
            $obj = new ScWebsitesPageData;
            $obj->page_type = 'CONTACT';
            $res = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id', 'where' => 'user_id=' . $_SESSION['user']['userid']));

            if (!$res->id) {
                //insert
                $obj->site_id = $_SESSION['webfront']['id'];
                $obj->user_id = $_SESSION['user']['userid'];
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->insert($obj);
            } else {
                //update
                $obj->id = $res->id;
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->update($obj, array('limit' => 1,));
            }
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Contact page settings saved successfully';
            return Doo::conf()->APP_URL . 'contactWebSettings';
        }

        if ($page == 'LOGIN') {
            //prepare data
            $sitedata['title'] = DooTextHelper::cleanInput($_POST['pgtitle'], ' |', 0);
            $sitedata['metadesc'] = DooTextHelper::cleanInput($_POST['metadesc'], ' ,.', 0);
            $sitedata['theme'] = DooTextHelper::cleanInput($_POST['theme']);
            $sitedata['regflag'] = intval($_POST['regflag']);

            //insert
            Doo::loadModel('ScWebsitesPageData');
            $obj = new ScWebsitesPageData;
            $obj->page_type = 'LOGIN';
            $res = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id', 'where' => 'user_id=' . $_SESSION['user']['userid']));

            if (!$res->id) {
                //insert
                $obj->site_id = $_SESSION['webfront']['id'];
                $obj->user_id = $_SESSION['user']['userid'];
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->insert($obj);
            } else {
                //update
                $obj->id = $res->id;
                $obj->page_data = base64_encode(serialize($sitedata));
                Doo::db()->update($obj, array('limit' => 1,));
            }
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Login page settings saved successfully';
            return Doo::conf()->APP_URL . 'loginWebSettings';
        }
    }

    public function webLeads()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'Website Leads';

        $data['page'] = 'Reports';
        $data['current_page'] = 'webleads';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/webLeads', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getWebsiteLeads()
    {
        $columns = array(
            array('db' => 'activity_date', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);


        //date range
        $mode = $this->params['mode'];
        $uid = $_SESSION['user']['userid'];

        if ($_SESSION['user']['subgroup'] == 'staff') {
            //show all leads from admin website
            if (intval($mode) >= 0) {

                if ($mode == 0) {
                    $sWhere = "mode = 0 AND user_assoc = 1";
                } else {
                    $sWhere = "mode = 1 AND user_assoc = 1";
                }
            } else {
                $sWhere = 'user_assoc = 1';
            }
        } else {

            if (intval($mode) >= 0) {

                if ($mode == 0) {
                    $sWhere = "mode = 0 AND user_assoc = $uid";
                } else {
                    $sWhere = "mode = 1 AND user_assoc = $uid";
                }
            } else {
                $sWhere = 'user_assoc = ' . $uid;
            }
        }



        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND' . $dtdata['where'];
            }
        }


        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }


        Doo::loadModel('ScWebsitesLeads');
        $obj = new ScWebsitesLeads;
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $leads = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($leads);


        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($leads as $dt) {

            if ($dt->mode == 0) {
                //contact form
                $srcstr = '<span class="label label-info label-md">' . $this->SCTEXT('Contact Form') . '</span>';
                $leaddata = unserialize(base64_decode($dt->visitor_info));
                $ldstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <span class="block"><i class="fas  fa-user fa-fixed m-r-md"></i>' . $leaddata[0] . '</span>
                                                     <span class="block"><i class="fas  fa-envelope fa-fixed m-r-md"></i>' . $leaddata[1] . '</span>
                                                    <span class="block"><i class="fas  fa-heading fa-fixed m-r-md"></i> ' . $leaddata[2] . '</span>
                                                     <span class="block"><i class="fas fa-th-list fa-fixed m-r-md"></i>' . htmlspecialchars_decode($leaddata[3]) . '</span>
                                                    </div>';
                $sdstr = 'N/A';
            } else {
                //test gateway widget
                $srcstr = '<span class="label label-success label-md">' . $this->SCTEXT('Test Gateway Widget') . '</span>';
                $ldstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <span class="block"><i class="fa fa-phone fa-flip-horizontal fa-fixed m-r-md"></i>' . $dt->visitor_info . '</span>

                                                    </div>';
                $smsdata = unserialize($dt->sms_data);
                $moreinfo = '<span class=smstxt-ctr><b>Callback URL</b><hr class=m-h-xs>' . base64_decode($smsdata['apiurl']) . '<hr class=m-h-xs><b>API Response<b><hr class=m-h-xs>' . $smsdata['response'] . '</span>';
                $sdstr = '<div class="smstxt-ctr img-rounded p-sm panel panel-custom panel-success">

                                                    <span class="block">' . htmlspecialchars_decode(base64_decode($smsdata['sms'])) . '</span>
                                                    <hr class="m-h-xs">
                                                    <a class="pop-over" data-trigger="click" data-content="' . $moreinfo . '" data-placement="bottom" href="javascript:void(0);">' . $this->SCTEXT('More Info') . ' ...</a>
                                                    </div>';
            }

            $pldata = unserialize($dt->platform_data);
            $plstr = '<div class="smstxt-ctr p-sm panel panel-custom panel-info fz-sm">
                                                         <span class="block"><i class="fas fa-lg fa-desktop fa-fixed m-r-md m-b-xs"></i>' . $pldata['system'] . '</span>
                                                     <span class="block"><i class="fas fa-lg fa-globe fa-fixed m-r-md"></i>' . $pldata['browser'] . '</span>
                                                    <span class="block"><i class="fas fa-lg  fa-server fa-fixed m-r-md"></i> ' . $pldata['ip'] . '</span>';
            if ($pldata['city'] != '' && $pldata['country'] != '') {
                $plstr .= '<span class="block"><i class="fas fa-lg  fa-map-marker fa-fixed m-r-md"></i> ' . $pldata['city'] . ', ' . $pldata['country'] . '</span>';
            }

            $plstr .= '</div>';



            $output = array($ctr, $srcstr, date(Doo::conf()->date_format_long_time, strtotime($dt->activity_date)), $ldstr, $sdstr, $plstr);
            array_push($res['aaData'], $output);
            $ctr++;
        }
        echo json_encode($res);
        exit;
    }



    //3. Support Management

    public function manageSupport()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Support'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Support Tickets';

        $data['page'] = 'ManageSupport';
        $data['current_page'] = 'support_tickets_mgmt';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/manageTickets', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAssignedTickets()
    {
        //get all tickets from downline

        $columns = array(
            array('db' => 'ticket_title', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);


        //date range
        $dr = $this->params['dr'];
        $uid = $_SESSION['user']['userid'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "manager_id = $uid AND date_opened LIKE '$from%'";
            } else {
                $sWhere = "manager_id = $uid AND date_opened BETWEEN '$from' AND '$to'";
            }
        } else {
            $sWhere = 'manager_id = ' . $uid;
        }

        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND' . $dtdata['where'];
            }
        }


        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }


        Doo::loadModel('ScSupportTickets');
        $obj = new ScSupportTickets;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $tkts = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($tkts);

        $uobj = Doo::loadModel('ScUsers', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($tkts as $dt) {

            $udt = $uobj->getProfileInfo($dt->user_id, 'name,category,email,avatar');

            $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '"><img src="' . $udt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($udt->name) . '</a><small class="text-muted fz-sm">' . ucwords($udt->category) . '</small></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $udt->email . '</p>
                                            </div>
                                        </div>';

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewMgrTicket/' . $dt->id . '">' . $this->SCTEXT('View') . '</a></li></ul></div>';

            $pstr = $dt->priority == 0 ? '<span class="label label-info label-md">' . $this->SCTEXT('Normal') . '</span>' : ($dt->priority == 1 ? '<span class="label label-warning label-md">' . $this->SCTEXT('Medium') . '</span>' : '<span class="label label-danger label-md">' . $this->SCTEXT('Critical') . '</span>');

            $status = $dt->status == 0 ? ' <span class="label label-warning label-md"><i class="fa fa-clock-o fa-lg m-r-xs"></i>' . $this->SCTEXT('Issue Open') . '</span>' : '<span class="label label-success label-md"><i class="fa fa-check-circle fa-lg m-r-xs"></i>' . $this->SCTEXT('Resolved') . '</span>';



            $output = array($user_str, date(Doo::conf()->date_format_long_time, strtotime($dt->date_opened)), $dt->ticket_title, $pstr, $status, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function viewMgrTicket()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Support Tickets'] = Doo::conf()->APP_URL . 'manageSupport';
        $data['active_page'] = 'View Ticket';

        //get the ticket data
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Ticket ID';
            return Doo::conf()->APP_URL . 'manageSupport';
        }
        $tobj = Doo::loadModel('ScSupportTickets', true);
        $tobj->id = $tid;
        $tobj->manager_id = $_SESSION['user']['userid'];
        $tdata = Doo::db()->find($tobj, array('limit' => 1));

        if (!$tdata->id) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Ticket Not Found';
            return Doo::conf()->APP_URL . 'manageSupport';
        }

        $data['tdata'] = $tdata;

        $trobj = Doo::loadModel('ScSupportTicketComments', true);
        $trobj->ticket_id = $tid;
        $data['tcoms'] = Doo::db()->find($trobj);

        //pull the user profile
        $uobj = Doo::loadModel('ScUsers', true);
        $data['udata'] = $uobj->getProfileInfo($tdata->user_id, 'name,category,email,avatar');

        $data['page'] = 'ManageSupport';
        $data['current_page'] = 'view_ticket_mgr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('reseller/viewMgrTicket', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function markTicket()
    {
        //get data
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Ticket ID';
            return Doo::conf()->APP_URL . 'manageSupport';
        }
        $tobj = Doo::loadModel('ScSupportTickets', true);
        $tobj->id = $tid;
        $tobj->manager_id = $_SESSION['user']['userid'];
        $tdata = Doo::db()->find($tobj, array('limit' => 1));

        if (!$tdata->id) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Ticket Not Found';
            return Doo::conf()->APP_URL . 'manageSupport';
        }

        //mark status
        if ($this->params['status'] == 'c') {
            $tobj->status = 1;
            $tobj->date_closed = date(Doo::conf()->date_format_db);
            Doo::db()->update($tobj);
            $msg = 'Ticket was marked as RESOLVED. The client has been notified';
            $alertmsg = 'Support ticket was marked Resolved.';
            //notify the client using hyperlog
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
                "mode" => "support_ticket_closed",
                "data" => array(
                    "user_id" => $tdata->user_id,
                    "incidentPlatform" => $osdata,
                    "incidentDateTime" => date(Doo::conf()->date_format_db),
                    "ticketId" => 'MGWST' . $tid,
                    "ticketSubject" => $tdata->ticket_title,
                    "ticketPriority" => $tdata->priority == 0 ? 'Normal' : ($tdata->priority == 1 ? 'Medium' : 'Critical'),
                    "ticketUrl" => Doo::conf()->APP_URL . "viewTicket/" . $tid
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
        } else {
            $tobj->status = 0;
            $tobj->date_closed = '0000-00-00 00:00:00';
            Doo::db()->update($tobj);
            $msg = 'Ticket was marked as OPEN. The client has been notified';
            $alertmsg = 'Support ticket has been re-opened.';
        }
        //notify
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($tdata->user_id, 'info', $alertmsg, 'viewTicket/' . $tid);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'viewMgrTicket/' . $tid;
    }

    //System monitor
    public function systemMonitor()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sysmon']) {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['subgroup'] == 'reseller' && !$_SESSION['permissions']['reseller']['user_monitor']) {
            //denied
            return array('/denied', 'internal');
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'System Monitor';

        $data['page'] = 'Administration';
        $data['current_page'] = 'system_mon';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/systemMon', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }
    public function getAllScheduledCampaigns()
    {

        //get all scheduled at once
        $schobj = Doo::loadModel('ScScheduledCampaigns', true);
        if ($_SESSION['user']['group'] == 'reseller') {
            //get downline users
            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->upline_id = $_SESSION['user']['userid'];
            $dlusers = Doo::db()->find($uobj);
            $downline = array();
            foreach ($dlusers as $k => $v) {
                array_push($downline, $v->user_id);
            }
            if (sizeof($downline) == 0) {
                $downline[] = $_SESSION['user']['userid'];
            }
            $cmpns = Doo::db()->find($schobj, array('where' => 'user_id IN (' . implode(',', $downline) . ')', 'limit' => 1000, 'desc' => 'schedule_time'));
        } else {
            $cmpns = Doo::db()->find($schobj, array('limit' => 1000, 'desc' => 'schedule_time'));
        }

        $total = count($cmpns);

        //get all users and routes
        if ($_SESSION['user']['group'] == 'reseller') {
            $userQry = "SELECT user_id, CONCAT_WS('|', name, category, email, avatar) AS info FROM `sc_users` WHERE upline_id = " . $_SESSION['user']['userid'];
        } else {
            $userQry = "SELECT user_id, CONCAT_WS('|', name, category, email, avatar) AS info FROM `sc_users`";
        }

        $userdata = Doo::db()->fetchAll($userQry, null, PDO::FETCH_KEY_PAIR);

        $routeQry = "SELECT id, title FROM `sc_sms_routes`";
        $routedata = Doo::db()->fetchAll($routeQry, null, PDO::FETCH_KEY_PAIR);


        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($cmpns as $dt) {

            $userInfo = explode('|', $userdata[$dt->user_id]);

            $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-xs avatar-circle m-r-xs"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '"><img src="' . $userInfo[3] . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($userInfo[0]) . '</a><small class="text-muted fz-xs">' . ucwords($userInfo[1]) . '</small></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $userInfo[2] . '</p>
                                            </div>
                                        </div>';


            if ($dt->status == 2) {
                //5 means is locked currently being processed
                $status_str = '<div class="m-b-lg m-r-xl inline-block"><i title="' . $this->SCTEXT('Sending Now') . '..." class="text-success fa-2x fa fa-cog fa-spin"></i></div>';
            } else {
                $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="sswitchid-' . $dt->id . '" data-size="small" data-cid="' . $dt->id . '" class="sswitch togscstatus" type="checkbox" value="0" data-dtswitch="true" data-color="#10c469"';
                // 1 means campaign is active, 0 means it is paused
                if ($dt->status == 1) {
                    $status_str .= " checked";
                }
                $status_str .= '></div>';
            }

            $actstr = '<button type="button" data-cid="' . $dt->id . '" class="mansc btn btn-primary btn-sm"><i class="fa fa-check m-r-xs"></i>' . $this->SCTEXT('Send Now') . '</button>';

            $rstr = '<span class="label label-info label-sm">' . $routedata[$dt->route_id] . '</span>';

            $smscat = json_decode($dt->sms_type, true);
            $stxtstr = '';
            if ($smscat['main'] == 'text') {
                $stxtstr = '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dt->sms_text) . '</div>';
            } elseif ($smscat['main'] == 'wap') {
                $stdata = unserialize(base64_decode($dt->sms_text));
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                        <h5>' . $stdata['wap_title'] . '</h5>
                                                    <hr class="m-h-xs">

                                                    <span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span>

                                                    </div>';
            } elseif ($smscat['main'] == 'vcard') {
                $stdata = unserialize(base64_decode($dt->sms_text));
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                        <span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span>
                                                    </div>';
            }


            $sdatastr = '<span class="inline-block">' . $this->SCTEXT('Total SMS') . ': ' . number_format($dt->total_contacts) . '<br>' . $this->SCTEXT('Sender ID') . ': ' . $dt->sender_id . '<br>' . $this->SCTEXT('Route') . ': ' . $rstr . '</span>';

            $output = array($user_str, $stxtstr, $sdatastr, date(Doo::conf()->date_format_med_time, strtotime($dt->schedule_time)), $status_str, $actstr);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function changeCampaignStatus()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sysmon']) {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['subgroup'] == 'reseller' && !$_SESSION['permissions']['reseller']['user_monitor']) {
            //denied
            return array('/denied', 'internal');
        }
        $cid = intval($_POST['cid']);
        if ($cid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        $status = intval($_POST['status']);

        if ($_POST['mode'] == 'sch') {
            $obj = Doo::loadModel('ScScheduledCampaigns', true);
            $obj->id = $cid;
            $obj->status = $status;
            Doo::db()->update($obj, array('limit' => 1));
            echo $status == 0 ? $this->SCTEXT('This campaign has been PAUSED. Status changed successfully') : $this->SCTEXT('This campaign is now ACTIVE. Status changed successfully.');
            exit;
        }
    }

    public function getAllUserStatus()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sysmon']) {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['subgroup'] == 'reseller' && !$_SESSION['permissions']['reseller']['user_monitor']) {
            //denied
            return array('/denied', 'internal');
        }
        $uobj = Doo::loadModel('ScUsers', true);
        $uobj->status = 1;
        $uobj->subgroup <> 'admin';
        if ($_SESSION['user']['group'] == 'reseller') {
            $users = Doo::db()->find($uobj, array('where' => "status = 1 AND upline_id = " . $_SESSION['user']['userid'], 'select' => 'user_id,name,avatar,category,email,state,last_login_ip,last_activity'));
        } else {
            $users = Doo::db()->find($uobj, array('where' => "status = 1 AND category <> 'admin'", 'select' => 'user_id,name,avatar,category,email,state,last_login_ip,last_activity'));
        }

        $allustr = '';
        $olustr = '';

        foreach ($users as $usr) {
            $now = date(Doo::conf()->date_format_db);
            $delta = (strtotime($now) - strtotime($usr->last_activity)) / 60;
            if ($usr->state == 1 && $delta <= 5) {
                $state = '<button type="button" class="m-t-xs btn rounded mw-xs btn-success">Online</button>';
            } else if ($usr->state == 1 && $delta > 5 && $delta <= 30) {
                $state = '<button type="button" class="m-t-xs btn rounded mw-xs btn-warning">Idle</button>';
            } else {
                $state = '<button type="button" class="m-t-xs btn rounded mw-xs btn-grey">Offline</button>';
            }

            $nt = new DateTime($usr->last_activity);
            $ct = new DateTime($now);
            $interval = $nt->diff($ct);
            $passed = DooTextHelper::format_interval($interval, 'short');

            $actstr = $passed == '' ? $this->SCTEXT('Never Logged In') : $passed . ' ago';

            $lip = $usr->last_login_ip == '' ? 'NA' : $usr->last_login_ip;

            $allustr .= '<div class="media">
                                                            <div class="media-left">
                                                                <div class="avatar avatar-xlg avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $usr->user_id . '"><img src="' . $usr->avatar . '" alt="User Img"></a></div>
                                                            </div>
                                                            <div class="media-body clearfix">
                                                                <div class="col-md-8">
                                                                    <h5 class="m-t-0 m-b-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $usr->user_id . '" class="m-r-xs theme-color">' . ucwords($usr->name) . ' </a><small class="text-muted fz-sm">' . ucwords($usr->category) . '</small></h5>
                                                                    <p class="m-b-0" style="font-size: 12px;font-style: Italic;"><i class="fa fa-lg fa-envelope m-r-xs"></i> ' . $usr->email . '</p>
                                                                    <div class="fz-sm"><span  class="label label-primary label-sm">' . $this->SCTEXT('Last Activity') . ':</span> ' . $actstr . ' </div>
                                                                    <div style="display:inline-block;" class="m-t-xs label label-info label-sm">' . $this->SCTEXT('Last Login IP') . ': ' . $lip . '</div>
                                                                </div>
                                                                <div class="col-md-4 text-right">
                                                                    ' . $state . '

                                                                </div>

                                                            </div>
                                                        </div>';


            if ($usr->state == 1 && $delta <= 5) {

                $olustr .= '<div class="media">
                                                            <div class="media-left">
                                                                <div class="avatar avatar-xlg avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $usr->user_id . '"><img src="' . $usr->avatar . '" alt="User Img"></a></div>
                                                            </div>
                                                            <div class="media-body clearfix">
                                                                <div class="col-md-8">
                                                                    <h5 class="m-t-0 m-b-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $usr->user_id . '" class="m-r-xs theme-color">' . ucwords($usr->name) . ' </a><small class="text-muted fz-sm">' . ucwords($usr->category) . '</small></h5>
                                                                    <p class="m-b-0" style="font-size: 12px;font-style: Italic;"><i class="fa fa-lg fa-envelope m-r-xs"></i> ' . $usr->email . '</p>
                                                                    <div class="fz-sm"><span  class="label label-primary label-sm">' . $this->SCTEXT('Last Activity') . ':</span> ' . $actstr . '</div>
                                                                    <div style="display:inline-block;" class="m-t-xs label label-info label-sm">' . $this->SCTEXT('Last Login IP') . ': ' . $lip . '</div>
                                                                </div>
                                                                <div class="col-md-4 text-right">
                                                                    ' . $state . '

                                                                </div>

                                                            </div>
                                                        </div>';
            }
        }

        //prepare response

        if ($allustr == '') $allustr = '- ' . $this->SCTEXT('No Users Found') . ' -';
        if ($olustr == '') $olustr = '- ' . $this->SCTEXT('No Users Online') . ' -';

        $res['all'] = $allustr;
        $res['online'] = $olustr;
        echo json_encode($res);
        exit;
    }


    public function sendScheduleManually()
    {
        $cmpid = intval($_POST['cid']);
        if ($cmpid <= 0) {
            echo $this->SCTEXT('ERROR: Invalid Campaign ID');
            exit;
        }

        $obj = Doo::loadModel('ScScheduledCampaigns', true);
        $obj->id = $cmpid;
        $obj->status = 2;
        Doo::db()->update($obj, array('limit' => 1));
        //return
        echo $this->SCTEXT('Campaign is queued and will be sent shortly.');
        exit;
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
