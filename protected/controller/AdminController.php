<?php

/** 
 * Admin Controller
 *
 * @author saurav
 *
 * 1. Dashboard functions
 * 2. Kannel Management
 * 2. Manage SMPP Connections
 * 3. Manage Routes
 * 4. Manage Blacklist DBs
 * 5. Manage Credit Count Rules
 * 6. Manage SMS plans
 * 7. Manage countries and prefixes
 * 8. Approve Sender ID
 * 9. Approve Templates
 * 10. Manage Refund Rules
 * 11. Kannel Monitor
 * 12. Announcements
 * 13. Staff Management
 * 14. System Monitor
 * 15. User Management : Admin Stuff
 * 16. Spam Management
 * 17. Blocked IP Management
 * 18. App Settings
 * 19. Power Grid
 * 20. Admin logs
 * 21. Phonebook database
 * 22. SSL Management
 * 23. API Vendors
 * 24. Miscellaneous Functions
 *
 */

use Google\Cloud\Translate\V2\TranslateClient;

class AdminController extends DooController
{
    public function __construct()
    {
        session_start();
        Doo::loadHelper('DooSmppcubeHelper');
        if (!$_SESSION['user'] || $_SESSION['user']['group'] != 'admin' || !$_SESSION['webfront']) {
            throw new Exception();
        }
    }
    // 1. Dashboard functions
    // Load Dashboard Stats
    public function getAdminSalesStats()
    {
        //get all sales data from last 30 days till today
        Doo::loadModel('ScStatsSalesAdmin');
        $obj = new ScStatsSalesAdmin;
        $first_day_month = date('Y-m-d', strtotime('first day of this month'));
        $first_day_week = date('Y-m-d', strtotime('last monday'));
        $last_seventh = date('Y-m-d', strtotime('today - 6 days'));
        $today = date('Y-m-d');
        $start = date('Y-m-d', strtotime('today - 30 days'));
        $data = $obj->getSalesDayWise($start, $today);

        //array with last 7 days sales
        $last_seven = array();
        //array with this weeks sales
        $this_week = array();
        //array with this months sales
        $this_month = array();
        //total sales in this week
        $total_week = 0;
        //total sales in this month
        $total_month = 0;

        foreach ($data as $salesobj) {

            if ($salesobj->c_date == $last_seventh || strtotime($salesobj->c_date) > strtotime($last_seventh)) {
                $last_seven["$salesobj->c_date"] = $salesobj->sale_amount;
            }
            if ($salesobj->c_date == $first_day_week || strtotime($salesobj->c_date) > strtotime($first_day_week)) {
                $this_week["$salesobj->c_date"] = $salesobj->sale_amount;
            }
            if ($salesobj->c_date == $first_day_month || strtotime($salesobj->c_date) > strtotime($first_day_month)) {
                $this_month["$salesobj->c_date"] = $salesobj->sale_amount;
            }
        }

        $total_week = array_sum($this_week);
        $total_month = array_sum($this_month);
        //prepare response
        $res = array();
        $res['total_weekly_sales'] = $total_week;
        $res['total_monthly_sales'] = $total_month;
        $res['sales_this_week'] = $this_week;
        $res['sales_this_month'] = $this_month;
        $res['sales_seven_days'] = $last_seven;
        echo json_encode($res);
    }

    public function getTopResellers()
    {
        $dates = $this->params['dr'];
        $limit = $this->params['limit'];
        if (!$dates) {
            $dates = 'Select Date';
        }
        if (!$limit) {
            $limit = '0,4';
        }
        Doo::loadModel('ScStatsSalesReseller');
        $obj = new ScStatsSalesReseller;
        $data = $obj->getSalesByDate($dates, $limit);
        $str = '';
        $more = 1;

        Doo::loadModel('ScUsers');
        $obj2 = new ScUsers;

        //prepare the data
        foreach ($data as $dt) {
            $user = $obj2->getProfileInfo($dt->reseller_id);
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

                                                <h5 class="m-t-0 label label-success">' . number_format($dt->total_sms) . ' ' . $this->SCTEXT('SMS Sold') . '</h5>
                                                <p style="font-size: 12px;margin-top:3px;">' . number_format($dt->new_clients) . ' ' . $this->SCTEXT('new Sign-ups') . '</p>

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>';
        }

        if ($str == '') {

            $str = '<div align="center">' . $this->SCTEXT('No recent resellers to show') . '</div>';
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

    // 2. Manage SMPP Connections

    public function adminRemoteCalls()
    {
        $url = base64_decode(urldecode($this->params['url']));
        $data = file_get_contents($url);
        //custom code for VF route
        if (strstr($url, 'vfsmpp') && strstr($data, 'creditLimit')) {
            $parts = explode(",", $data);
            $total = floatval(str_replace("creditLimit=", '', $parts[0]));
            $used = floatval(str_replace("creditUsed=", '', $parts[1]));
            $data = number_format($total - $used);
        }
        //end of custom code for VF route
        echo htmlspecialchars($data);
    }

    public function manageSmpp()
    {

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //staff permission check
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }

        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Smpp Accounts';

        $data['page'] = 'Administration';
        $data['subpage'] = 'Gateways';
        $data['current_page'] = 'manage_smpp';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageSmpp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllSmpp()
    {

        //simply send all data to page as the quantity is small so no serverside processing required
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }

        Doo::loadModel('ScSmppAccounts');
        $obj = new ScSmppAccounts;
        $smpp = Doo::db()->find($obj, array('select' => 'id,title,username,password,provider,host,port,smsc_id,tx,rx,trx,credits_api,status'));
        $total = count($smpp);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 0;
        foreach ($smpp as $dt) {
            $ctr++;
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editSmpp/' . $dt->id . '">' . $this->SCTEXT('Edit Details') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'gatewayCostPrice/' . $dt->id . '">' . $this->SCTEXT('Gateway Cost Price') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'smppDlrCodes/' . $dt->id . '">' . $this->SCTEXT('Vendor DLR Codes') . '</a></li><li><a class="remove_smpp" data-rid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Smpp') . '</a></li></ul></div>';
            $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="switchid-' . $dt->id . '" data-rid="' . $dt->id . '" class="togstatus myswitch" type="checkbox" value="0" data-dtswitch="true" data-color="#10c469"';
            if ($dt->status == 0) {
                $status_str .= " checked";
            }
            $status_str .= '></div>';
            $crstr = $dt->credits_api == '' ? 'N/A' : '<kbd data-apicall="' . base64_encode(str_replace('%p', urlencode($dt->password), str_replace('%u', urlencode($dt->username), $dt->credits_api))) . '" class="smppcredits"> fetching ....</kbd>';

            $output = array($dt->title, $dt->smsc_id, $dt->provider, $crstr, $dt->host . ' (Port:' . $dt->port . ')', $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addSmpp()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Smpp'] = Doo::conf()->APP_URL . 'manageSmpp';
        $data['active_page'] = 'Add Smpp Account';
        //get all tlvs
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $data['tlvs'] = Doo::db()->find($tlvobj, array('select' => 'id, tlv_title'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_smpp';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addSmpp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editSmpp()
    {

        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Smpp'] = Doo::conf()->APP_URL . 'manageSmpp';
        $data['active_page'] = 'Edit Smpp Account';

        //fetch data
        $rid = intval($this->params['id']);
        if ($rid > 0) {
            //valid id
            Doo::loadModel('ScSmppAccounts');
            $obj = new ScSmppAccounts;
            $obj->id = $rid;
            $rdata = Doo::db()->find($obj, array('limit' => 1));
            if ($rdata->id) {
                //record found
                $data['rdata'] = $rdata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageSmpp';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }

        //get all tlvs
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $data['tlvs'] = Doo::db()->find($tlvobj, array('select' => 'id, tlv_title'));


        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_smpp';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editSmpp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveSmpp()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScSmppAccounts');
        $obj = new ScSmppAccounts;

        if (intval($_POST['rid']) > 0) {
            $mode = 'edit';
            $obj->id = intval($_POST['rid']);
            $data = Doo::db()->find($obj, array('limit' => 1));
            if (!$data->id) {
                $_SESSION['notif_msg']['msg'] = 'Invalid SMPP ID';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageSmpp';
            }
        } else {
            $mode = 'add';
        }

        //collect values and save in DB
        $obj->admin_id = $_SESSION['user']['userid'];
        $obj->kannel_id = intval($_POST['skannel']);
        $obj->title = DooTextHelper::cleanInput($_POST['title'], " ", 0);
        $obj->provider = DooTextHelper::cleanInput($_POST['provider'], " .()", 0);
        $obj->smsc_id = DooTextHelper::cleanInput(trim($_POST['smscid']), ".");
        $obj->purpose = intval($_POST['rcv_sms']) == 0 ? 'SMSC' : '2WAY';
        $obj->host = DooTextHelper::cleanInput($_POST['smpphost'], ".&");
        $obj->port = intval($_POST['smppport']);
        $obj->username = DooTextHelper::cleanInput(trim($_POST['smppuid']), "!@#$%^&*().?=+[]{}|:;", 0);
        $obj->password = DooTextHelper::cleanInput(trim($_POST['smpppass']), "!@#$%^&*().?=+[]{}|:;", 0);
        $obj->use_ssl = intval($_POST['use_ssl']);
        $obj->tx = intval($_POST['txno']);
        $obj->rx = intval($_POST['rxno']);
        $obj->trx =  intval($_POST['trxno']);
        $obj->trx_mode = intval($_POST['trxno']) > 0 ? 1 : 0;
        $obj->rcv_port = intval($_POST['rcvport']);
        $obj->system_type = DooTextHelper::cleanInput($_POST['systype'], " ", 0);
        $obj->service_type = DooTextHelper::cleanInput($_POST['sertype'], " ", 0);
        $obj->throughput = intval($_POST['tps']);
        $obj->allowed_prefix = DooTextHelper::cleanInput($_POST['allpre'], ",+;", 0);
        $obj->denied_prefix = DooTextHelper::cleanInput($_POST['denpre'], ",+;", 0);
        $obj->enquire_link_interval = intval($_POST['eli']);
        $obj->reconnect_delay = intval($_POST['recon']);
        $obj->esm_class = intval($_POST['esm_class']);
        $obj->alt_charset = DooTextHelper::cleanInput($_POST['alt_charset'], "", 0);
        $obj->ston = intval($_POST['ston']);
        $obj->snpi = intval($_POST['snpi']);
        $obj->dton = intval($_POST['dton']);
        $obj->dnpi = intval($_POST['dnpi']);
        $obj->smpp_version = intval($_POST['smppversion']);
        $obj->credits_api = DooTextHelper::cleanInput($_POST['creditsapi'], ":\/&?.=", 0);
        $obj->tlv_ids = isset($_POST['tlv']) ? implode(",", $_POST['tlv']) : "";
        $obj->max_octets = intval($_POST['maxoctets']) == 0 ? 140 : intval($_POST['maxoctets']);
        $obj->logfile = $_POST['slog'];
        $obj->log_level = $_POST['sloglvl'];

        //save in DB
        if ($mode == 'edit') {
            $obj->id = $data->id;
            Doo::db()->update($obj);
            //recreate kannel config
            $this->recreateKannelConfig();
            //dynamically add the smpp in kannel
            if ($data->smsc_id != DooTextHelper::cleanInput(trim($_POST['smscid']), ".") || $data->tx != intval($_POST['txno']) || $data->rx != intval($_POST['rxno']) || $data->trx != intval($_POST['trxno'])) {
                $_SESSION['notif_msg']['msg'] = 'Changes Saved. SMSC ID or Session count is changed. Please restart Kannel gracefully.';
            } else {
                $this->dynamicKannelSmpp('edit', $obj->smsc_id, $obj->tx, $obj->rx, $obj->trx);
                $_SESSION['notif_msg']['msg'] = 'SMPP details saved successfully';
            }

            $_SESSION['notif_msg']['type'] = 'success';
        } else {
            Doo::db()->insert($obj);
            //recreate kannel config
            $this->recreateKannelConfig();
            //dynamically add the smpp in kannel
            $this->dynamicKannelSmpp('add', $obj->smsc_id, $obj->tx, $obj->rx, $obj->trx);
            $_SESSION['notif_msg']['msg'] = 'New SMPP connection added';
            $_SESSION['notif_msg']['type'] = 'success';
        }

        //redirect
        return Doo::conf()->APP_URL . 'manageSmpp';
    }

    public function deleteSmpp()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $rid = intval($this->params['id']);
        //get record
        Doo::loadModel('ScSmppAccounts');
        $obj = new ScSmppAccounts;
        $obj->id = $rid;
        $data = Doo::db()->find($obj, array('select' => 'id,smsc_id,tx,rx,trx', 'limit' => 1));
        if (!$data->id) {
            $_SESSION['notif_msg']['msg'] = 'Invalid SMPP ID';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manageSmpp';
        }
        //remove record
        Doo::db()->delete($obj);
        //recreate kannel config
        $this->recreateKannelConfig();
        //dynamically add the smpp in kannel
        $this->dynamicKannelSmpp('delete', $data->smsc_id, $data->tx, $data->rx, $data->trx);
        //redirect
        $_SESSION['notif_msg']['msg'] = 'SMPP removed from system';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageSmpp';
    }

    public function changeSmppStatus()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }

        $rid = intval($_POST['rid']);
        if ($rid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        $status = intval($_POST['status']);
        Doo::loadModel('ScSmppAccounts');
        $obj = new ScSmppAccounts;
        $obj->id = $rid;
        $obj->status = $status;
        Doo::db()->update($obj, array('limit' => 1));
        //set status in kannel as well so sqlbox doesn't send messages here
        echo $status == 0 ? $this->SCTEXT('The SMPP is now ACTIVE. Status changed successfully') : $this->SCTEXT('The SMPP has been shutdown. Status changed successfully.');
        exit;
    }

    public function gatewayCostPrice()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }

        $smppid = $this->params['id'];

        if (!isset($_SESSION['notif_msg'])) {
            $_SESSION['notif_msg']['msg'] = 'Make sure to regerate selling price for MCCMNC SMS Plans using this SMSC after changing the cost price.';
            $_SESSION['notif_msg']['type'] = 'info';
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['links']['Manage SMPP'] = Doo::conf()->APP_URL . 'manageSmpp';
        $data['active_page'] = 'Gateway Cost Price';

        //get route details
        $robj = Doo::loadModel('ScSmppAccounts', true);
        $robj->id = $smppid;
        $data['rdata'] = Doo::db()->find($robj, array('limit' => 1));

        //get all countries
        $cvobj = Doo::loadModel('ScCoverage', true);
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country_code, country, prefix', 'where' => 'id > 1'));

        //get all operators
        $opobj = Doo::loadModel('ScMccMncList', true);
        $data['opdata'] = Doo::db()->find($opobj, array('where' => 'status = 1', 'select' => 'brand, operator, country_name, country_iso, country_code', 'groupby' => 'brand, country_iso', 'asc' => 'country_name'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'gw_costprice';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/gatewayCostPrice', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getGatewayCostPriceSorted()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        //by default if no param is supplied assume sorted by country
        //first parameter is sorted by operator, param will be country
        //second parameter is sorted by mccmnc, param will be country and operator name
        $smppid = $this->params['id'];
        $country = $this->params['country'];
        $operator = $this->params['operator']; //base64 encoded for unicode URI compatibility 
        $mode = intval($this->params['mode']);
        $columns = array(
            array('db' => 'country_iso', 'dt' => 0),
            array('db' => 'mccmnc', 'dt' => 1),
            array('db' => 'brand', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        $obj = Doo::loadModel('ScMccMncList', true);
        if ($country != '0') {
            $obj->country_code = $country; //calling prefix
        }
        if ($operator != '0') {
            $opdata = explode("|", base64_decode($operator));
            $obj->country_iso = $opdata[1]; //calling iso
            $obj->brand = $opdata[0];
        }
        //get only operational ones

        if (isset($dtdata['where']) && $dtdata['where'] != "") {
            $dtdata['where'] .= ' AND status = 1';
        } else {
            $dtdata['where'] = 'status = 1';
        }
        if (!isset($dtdata['asc']) && !isset($dtdata['desc'])) $dtdata['asc'] = 'country_iso';
        if ($mode == 0) {
            $total = Doo::db()->find($obj, array('select' => 'COUNT(*) OVER () AS total', 'where' => $dtdata['where'], 'groupby' => 'country_iso', 'limit' => 1))->total;
            $dtdata['groupby'] = 'country_iso';
            $data = Doo::db()->find($obj, $dtdata);
        }
        if ($mode == 1) {
            $total = Doo::db()->find($obj, array('select' => 'COUNT(*) OVER () AS total', 'where' => $dtdata['where'], 'groupby' => 'brand, country_iso', 'limit' => 1))->total;
            $dtdata['groupby'] = 'brand, country_iso';
            $data = Doo::db()->find($obj, $dtdata);
        }
        if ($mode == 2) {
            $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
            $data = Doo::db()->find($obj, $dtdata);
        }


        $costpriceqry = "SELECT mccmnc, cost_price FROM sc_smpp_cost_price WHERE smpp_id = $smppid";
        $costprice = Doo::db()->fetchAll($costpriceqry, null, PDO::FETCH_KEY_PAIR);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($data as $dt) {

            $nw_costprice = isset($costprice[$dt->mccmnc]) ? $costprice[$dt->mccmnc] : 0;
            $cpstr = '<div class="input-group"><span class="input-group-addon">' . Doo::conf()->currency . '</span><input id="cp_' . $dt->mccmnc . '" type="text" placeholder="e.g. 0.045" class="form-control" value="' . $nw_costprice . '"></div>';

            $button_str = '<div class="btn-group"><a title="Save Pricing" href="javascript:void(0);" data-smppid="' . $smppid . '" data-mccmnc="' . $dt->mccmnc . '" data-cc="' . $dt->country_code . '" data-mode="' . $mode . '" class="btn btn-success savepricing"> <i class="fa fa-large fa-check"></i> </a><a title="Remove Pricing" href="javascript:void(0);" data-smppid="' . $smppid . '" data-mccmnc="' . $dt->mccmnc . '" data-cc="' . $dt->country_code . '" data-mode="' . $mode . '" class="btn btn-danger delpricing"><i class="fa fa-large fa-trash"></i> </a></div>';
            //now based on the pricing mode, display the records
            if ($mode == 0) {
                //by country
                $output = array($dt->country_iso . ' (+' . $dt->country_code . ')', '<kbd> All MCCMNC </kbd>', 'All Brands', 'All Operators', $cpstr, $button_str);
            }
            if ($mode == 1) {
                //by operator
                //since operator narrowes down region we need to skip it e.g show Airtel, instead of Airtel Punjab
                $opstr = $dt->brand == '' ? $dt->operator : $dt->brand;
                $output = array($dt->country_iso . ' (+' . $dt->country_code . ')', '<kbd> All MCCMNC </kbd>', ($opstr), ('-'), $cpstr, $button_str);
            }
            if ($mode == 2) {
                //by mccmnc
                $output = array($dt->country_iso . ' (+' . $dt->country_code . ')', '<kbd>' . $dt->mccmnc . '</kbd>', ($dt->brand), ($dt->operator), $cpstr, $button_str);
            }


            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function uploadGatewayCostPricing()
    {
        $smppid = $this->params['id'];
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['links']['Manage SMPP'] = Doo::conf()->APP_URL . 'manageSmpp';
        $data['links']['Gateway Cost Price'] = Doo::conf()->APP_URL . 'gatewayCostPrice/' . $smppid;
        $data['active_page'] = 'Upload SMS Rates';

        //get route details
        $robj = Doo::loadModel('ScSmppAccounts', true);
        $robj->id = $smppid;
        $data['rdata'] = Doo::db()->find($robj, array('limit' => 1));

        $data['page'] = 'Administration';
        $data['current_page'] = 'import_cost_price';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/uploadGatewayCostPricing', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function importGatewayCostPrice()
    {
        $smppid = intval($_POST['smppid']);
        //read CSV file and check for supplied sms rates
        Doo::loadHelper('DooFile');
        $fhobj = new DooFile;
        $output = array();
        $filename = isset($_POST["uploadedFiles"]) ? $_POST["uploadedFiles"][0] : '';
        if ($filename == '') {
            $_SESSION['notif_msg']['msg'] = 'Please upload a file';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'uploadGatewayCostPricing/' . $smppid;
        }
        $filepath = Doo::conf()->global_upload_dir . $filename;
        $ext = $fhobj->getFileExtensionFromPath($filepath, true);

        $insertQry = "INSERT INTO sc_smpp_cost_price (smpp_id, country_prefix, mccmnc, cost_price) VALUES ";
        $validnums = array();
        if ($ext == 'csv') {
            $suppliedData = DooSmppcubeHelper::convertCsvToArray($filepath);
            //find the mode of data i.e. by country, by operator or by mccmnc by looking at columns
            $mode = count($suppliedData[0]) == 3 ? 'country' : (count($suppliedData[0]) == 4 ? 'operator' : 'mccmnc');
            $prefcode = 0;
            // echo '<pre>';
            // var_dump($suppliedData);
            // die;
            for ($i = 1; $i <= count($suppliedData); $i++) {
                $costprice = $mode == 'country' ? floatval($suppliedData[$i][2]) : ($mode == 'operator' ? floatval($suppliedData[$i][3]) : floatval($suppliedData[$i][5]));
                if ($costprice > 0) {
                    //save cost price only if not-zero, so that if record is missing in sc_smpp_cost_price table, it means routing is not supported
                    if ($mode == 'country') {
                        //for this country get all mccmnc and set the same cost price for all of them
                        $country_prefix = $suppliedData[$i][1];
                        $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($country_prefix) . " AND status = 1";
                        $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
                        //prepare the insert query by appending for each country 
                        foreach ($mccmnclist as $mccmnc) {
                            array_push($validnums, $mccmnc);
                            $insertQry .= '(';
                            $insertQry .= "$smppid, $country_prefix, $mccmnc, $costprice";
                            $insertQry .= '),';
                        }
                    }
                    if ($mode == 'operator') {
                        $prefcode = 1;
                        //for this operator-country pair, get all mccmnc and set the same cost price for all of them
                        $country_prefix = $suppliedData[$i][2];
                        $operator = filter_var($suppliedData[$i][0], FILTER_SANITIZE_ADD_SLASHES);
                        $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($country_prefix) . " AND (brand = '$operator' OR operator = '$operator') AND status = 1";
                        $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
                        //prepare the insert query by appending for each operator 
                        foreach ($mccmnclist as $mccmnc) {
                            array_push($validnums, $mccmnc);
                            $insertQry .= '(';
                            $insertQry .= "$smppid, $country_prefix, $mccmnc, $costprice";
                            $insertQry .= '),';
                        }
                    }
                    if ($mode == 'mccmnc') {
                        $prefcode = 2;
                        //read all mccmnc cost price and save them
                        $country_prefix = $suppliedData[$i][1];
                        $mccmnc = $suppliedData[$i][4];
                        array_push($validnums, $mccmnc);
                        $insertQry .= '(';
                        $insertQry .= "$smppid, $country_prefix, $mccmnc, $costprice";
                        $insertQry .= '),';
                    }
                }
            }

            //add data
            $insertQry = substr($insertQry, 0, strlen($insertQry) - 1);

            $insertQry .= "ON DUPLICATE KEY UPDATE cost_price = VALUES(cost_price)";
            // echo $insertQry;
            // die;
            if (sizeof($validnums) > 0) {
                Doo::db()->query($insertQry);
            }
            // update the pricing preference for this smpp
            $updQry = "UPDATE sc_smpp_accounts SET pricing_preference = $prefcode WHERE id = $smppid";
            Doo::db()->query($updQry);
        } else {
            $_SESSION['notif_msg']['msg'] = 'Incorrect File format. Make sure the uploaded file is CSV format.';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'uploadGatewayCostPricing/' . $smppid;
        }

        //success return
        $_SESSION['notif_msg']['msg'] = 'Your file has been imported successfully. Total records modified:' . ' ' . sizeof($validnums);
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'gatewayCostPrice/' . $smppid;
    }

    public function saveGatewayCostPrice()
    {
        //depending on the mode save the updated pricing
        if (intval($_POST['mode']) == 0) {
            //pricing mode is country save the same price for all operators for this country
            //delete already existing price if any
            $delqry = "DELETE FROM sc_smpp_cost_price WHERE smpp_id = " . intval($_POST['smppid']) . " AND country_prefix = " . intval($_POST['country']);
            //echo $delqry;
            Doo::db()->query($delqry);
            //get all mccmnc for this country from sc_mcc_mnc_list
            //insert them into sc_smpp_cost_price if not exist else update the pricing
            $validnums = array();
            $insertQry = "INSERT INTO sc_smpp_cost_price (smpp_id, country_prefix, mccmnc, cost_price) VALUES ";
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($_POST['country']) . " AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
            //prepare the insert query by appending for each country 
            foreach ($mccmnclist as $mccmnc) {
                array_push($validnums, $mccmnc);
                $insertQry .= '(';
                $insertQry .= intval($_POST['smppid']) . ', ' . intval($_POST['country']) . ', ' . $mccmnc . ', ' . $_POST['price'];
                $insertQry .= '),';
            }
            $insertQry = substr($insertQry, 0, strlen($insertQry) - 1);
            //echo $insertQry;
            // die;
            if (sizeof($validnums) > 0) {
                Doo::db()->query($insertQry);
            }
        }
        if (intval($_POST['mode']) == 1) {
            //pricing mode is operatpr save the same price for all mccmnc for this operator
            //get the currently saved operator name from sc_mcc_mnc_list
            $samplemccmnc = intval($_POST['mccmnc']); // the reason it is sample is because the mode is operator, so even if single mccmnc is supplied we have to put this pricing for all mccmnc for this operator
            $operator = Doo::db()->getOne('ScMccMncList', array('select' => 'brand, operator', 'where' => "mccmnc = " . $samplemccmnc . " AND status = 1"));
            $operator_name = $operator->brand == "" ? $operator->operator : $operator->brand;

            $validnums = array();
            $insertQry = "INSERT INTO sc_smpp_cost_price (smpp_id, country_prefix, mccmnc, cost_price) VALUES ";
            //get all mccmnc for this operator from sc_mcc_mnc_list
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($_POST['country']) . " AND (brand = '$operator_name' OR operator = '$operator_name') AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
            //prepare the insert query by appending for each operator 
            foreach ($mccmnclist as $mccmnc) {
                array_push($validnums, $mccmnc);
                $insertQry .= '(';
                $insertQry .= intval($_POST['smppid']) . ', ' . intval($_POST['country']) . ', ' . $mccmnc . ', ' . $_POST['price'];
                $insertQry .= '),';
            }
            // before inserting delete all exisitng price for this operator and smpp if exist
            $delqry = "DELETE FROM sc_smpp_cost_price WHERE smpp_id = " . intval($_POST['smppid']) . " AND mccmnc IN (" . implode(',', $validnums) . ")";
            Doo::db()->query($delqry);
            $insertQry = substr($insertQry, 0, strlen($insertQry) - 1);
            //echo $insertQry;
            // die;
            if (sizeof($validnums) > 0) {
                Doo::db()->query($insertQry);
            }
        }

        if (intval($_POST['mode']) == 2) {
            $obj = Doo::loadModel('ScSmppCostPrice', true);
            $obj->smpp_id = intval($_POST['smppid']);
            $obj->mccmnc = intval($_POST['mccmnc']);
            $rs = Doo::db()->find($obj, array('limit' => 1));
            if ($rs->id) {
                //update
                $obj->id = $rs->id;
                $obj->cost_price = $_POST['price'];
                Doo::db()->update($obj);
            } else {
                //insert
                $obj->cost_price = $_POST['price'];
                Doo::db()->insert($obj);
            }
        }
        // update the pricing preference for this smpp
        $updQry = "UPDATE sc_smpp_accounts SET pricing_preference = " . intval($_POST['mode'] . " WHERE id = " . intval($_POST['smppid']));
        Doo::db()->query($updQry);
        exit;
    }

    public function removeGatewayCostPrice()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        //if mode 0 delete price for all mccmnc for this country
        if (intval($_POST['mode']) == 0) {
            $delqry = "DELETE FROM sc_smpp_cost_price WHERE smpp_id = " . intval($_POST['smppid']) . " AND country_prefix = " . intval($_POST['country']);
            //echo $delqry;
            Doo::db()->query($delqry);
        }
        //if mode 1 delete price for all mccmnc for this operator
        if (intval($_POST['mode']) == 1) {
            $samplemccmnc = intval($_POST['mccmnc']); // the reason it is sample is because the mode is operator, so even if single mccmnc is supplied we have to put this pricing for all mccmnc for this operator
            $operator = Doo::db()->getOne('ScMccMncList', array('select' => 'brand, operator', 'where' => "mccmnc = " . $samplemccmnc . " AND status = 1"));
            $operator_name = $operator->brand == "" ? $operator->operator : $operator->brand;
            $validnums = array();
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($_POST['country']) . " AND (brand = '$operator_name' OR operator = '$operator_name') AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);

            if (is_countable($mccmnclist) && count($mccmnclist) > 0) {
                $delqry = "DELETE FROM sc_smpp_cost_price WHERE smpp_id = " . intval($_POST['smppid']) . " AND mccmnc IN (" . implode(',', $mccmnclist) . ")";
                Doo::db()->query($delqry);
            }
        }
        //if mode 2 delete price for single mccmnc
        if (intval($_POST['mode']) == 2) {
            $obj = Doo::loadModel('ScSmppCostPrice', true);
            $obj->smpp_id = intval($_POST['smppid']);
            $obj->mccmnc = intval($_POST['mccmnc']);

            Doo::db()->delete($obj);
        }
        // update the pricing preference for this smpp
        $updQry = "UPDATE sc_smpp_accounts SET pricing_preference = " . intval($_POST['mode'] . " WHERE id = " . intval($_POST['smppid']));
        Doo::db()->query($updQry);

        exit;
    }

    public function smppDlrCodes()
    {

        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Smpp'] = Doo::conf()->APP_URL . 'manageSmpp';
        $data['active_page'] = 'Edit DLR Codes';

        $rid = intval($this->params['id']);
        if ($rid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //get route data
        Doo::loadModel('ScSmppAccounts');
        $robj = new ScSmppAccounts;
        $data['allsmpp'] = Doo::db()->find($robj, array("where" => "id <> $rid"));
        $rdobj = new ScSmppAccounts;
        $rdobj->id = $rid;
        $data['rdata'] = Doo::db()->find($rdobj, array('limit' => 1));
        //get dlr codes
        Doo::loadModel('ScSmppCustomDlrCodes');
        $cobj = new ScSmppCustomDlrCodes;
        $cobj->smpp_id = $rid;
        $data['codes'] = Doo::db()->find($cobj);
        //get refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'smpp_dlrcodes';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageDlrCodes', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function importSmppVdlr()
    {
        $srcSmpp = $this->params['src'];
        $targetSmpp = $this->params['tgt'];
        //get source dlr code
        Doo::loadModel('ScSmppCustomDlrCodes');
        $cobj = new ScSmppCustomDlrCodes;
        $cobj->smpp_id = intval($srcSmpp);
        $codes = Doo::db()->find($cobj);

        $ec_obj = new ScSmppCustomDlrCodes;
        //remove all previous error codes
        $ec_obj->cleanData($targetSmpp);

        //collect value
        $ec_obj2 = new ScSmppCustomDlrCodes;
        foreach ($codes as $code) {
            $ec_obj2->addCode($targetSmpp, $code->vendor_dlr_code, $code->optional_custom_code, $code->description, $code->action, $code->param_value, $code->category);
        }
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'DLR Codes Imported Successfully';
        return Doo::conf()->APP_URL . 'manageSmpp';
    }

    public function saveSmppDlrCodes()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smpp']) {
            //denied
            return array('/denied', 'internal');
        }
        //get route id

        $rid = $_POST['smppid'];
        if (!$_POST['codes'] && $rid) {
            //delete all data
            Doo::loadModel('ScSmppCustomDlrCodes');
            $ec_obj = new ScSmppCustomDlrCodes;
            //remove all error codes
            $ec_obj->cleanData($rid);
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'DLR Codes Saved Successfully';
        }
        if ($rid && $_POST['codes']) {
            //insert data
            Doo::loadModel('ScSmppCustomDlrCodes');
            $ec_obj = new ScSmppCustomDlrCodes;
            //remove all previous error codes
            $ec_obj->cleanData($rid);
            $ctr = 0;
            //collect values
            $descs = $_POST['descs'];
            $cuscode = $_POST['appcodes'];
            $dlr_actions = $_POST['dlr_actions'];
            $act_params = $_POST['act_params'];
            $types = $_POST['types'];

            $ec_obj2 = new ScSmppCustomDlrCodes;
            foreach ($_POST['codes'] as $code) {
                $customcode = $cuscode[$ctr] != '' ? $cuscode[$ctr] : $code;
                $ec_obj2->addCode($rid, $code, $customcode, $descs[$ctr], $dlr_actions[$ctr], $act_params[$ctr], $types[$ctr]);
                $ctr++;
            }
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'DLR Codes Saved Successfully';
        }

        return Doo::conf()->APP_URL . 'manageSmpp';
    }


    // 2. Manage Routes

    public function manageRoutes()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Routes';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_routes';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageRoutes', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllRoutes()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //simply send all data to page as the quantity is small so no serverside processing required

        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $routes = Doo::db()->find($obj);
        $total = count($routes);

        $smppqry = 'SELECT id, title FROM sc_smpp_accounts';
        $smpplist = Doo::db()->fetchAll($smppqry, null, PDO::FETCH_KEY_PAIR);

        $vapiqry = 'SELECT id, title FROM sc_api_vendors';
        $vapilist = Doo::db()->fetchAll($vapiqry, null, PDO::FETCH_KEY_PAIR);

        Doo::loadModel('ScCoverage');
        $cvobj = new ScCoverage;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($routes as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editRoute/' . $dt->id . '">' . $this->SCTEXT('Edit Details') . '</a></li><li><a class="remove_route" data-rid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Route') . '</a></li></ul></div>';

            $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="switchid-' . $dt->id . '" data-rid="' . $dt->id . '" class="togstatus myswitch" type="checkbox" value="0" data-dtswitch="true" data-color="#10c469"';
            if ($dt->status == 0) {
                $status_str .= " checked";
            }
            $status_str .= '></div>';
            $routeconf = json_decode($dt->route_config);
            $smppdivs = '';
            if (is_object($routeconf) && $routeconf->mode == 0) {
                if (!isset($routeconf->primary_smsc_type) || $routeconf->primary_smsc_type == "smpp") {
                    $smppdivs .= '<span class="label label-primary label-md m-r-xs m-b-xs">' . $smpplist[$routeconf->primary_smsc] . '</span>';
                }
                if (!isset($routeconf->backup_smsc_type) || $routeconf->backup_smsc_type == "smpp") {
                    $smppdivs .= '<span class="label label-primary label-md m-r-xs m-b-xs">' . $smpplist[$routeconf->backup_smsc] . '</span>';
                }
                if (isset($routeconf->primary_smsc_type) && $routeconf->primary_smsc_type == "http") {
                    $smppdivs .= '<span class="label label-primary label-md m-r-xs m-b-xs">' . $vapilist[$routeconf->primary_smsc] . '</span>';
                }
                if (isset($routeconf->backup_smsc_type) && $routeconf->backup_smsc_type == "http") {
                    $smppdivs .= '<span class="label label-primary label-md m-r-xs m-b-xs">' . $vapilist[$routeconf->backup_smsc] . '</span>';
                }
            } else {
                $smppar = explode(",", $dt->smpp_list);
                foreach ($smppar as $sid) {
                    $smppdivs .= '<span class="label label-primary label-md m-r-xs m-b-xs">' . $smpplist[$sid] . '</span>';
                }
            }



            $country = $cvobj->getCoverageData($dt->country_id, 'country, prefix');

            $output = array($dt->title, $smppdivs, $country->country . ' (' . $country->prefix . ')', $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addRoute()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Routes'] = Doo::conf()->APP_URL . 'manageRoutes';
        $data['active_page'] = 'Add New Route';

        //get smpp accounts
        Doo::loadModel('ScSmppAccounts');
        $sobj = new ScSmppAccounts;
        $data['smpp'] = Doo::db()->find($sobj, array('select' => 'id, title'));
        //get api accounts
        if (Doo::conf()->http_apivendor == 1) {
            Doo::loadModel('ScApiVendors');
            $vapiobj = new ScApiVendors;
            $data['vapi'] = Doo::db()->find($vapiobj, array('select' => 'id, title'));
        }
        //get blacklist databases
        Doo::loadModel('ScBlacklistIndex');
        $bobj = new ScBlacklistIndex;
        $data['bldb'] = Doo::db()->find($bobj, array('select' => 'id, table_name'), 2);
        //get credit count rules
        Doo::loadModel('ScCreditCountRules');
        $cobj = new ScCreditCountRules;
        $data['ccrule'] = Doo::db()->find($cobj, array('select' => 'id, rule_name'));
        //get all countries
        Doo::loadModel('ScCoverage');
        $cvobj = new ScCoverage;
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country, prefix, timezone'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_route';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addRoute', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editRoute()
    {

        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Routes'] = Doo::conf()->APP_URL . 'manageRoutes';
        $data['active_page'] = 'Edit Route';
        //validate id
        $rid = intval($this->params['id']);
        if ($rid > 0) {
            //valid id
            Doo::loadModel('ScSmsRoutes');
            $obj = new ScSmsRoutes;
            $obj->id = $rid;
            $rdata = Doo::db()->find($obj, array('limit' => 1));
            if ($rdata->id) {
                //record found
                $data['rdata'] = $rdata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageRoutes';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }

        //get smpp accounts
        Doo::loadModel('ScSmppAccounts');
        $sobj = new ScSmppAccounts;
        $data['smpp'] = Doo::db()->find($sobj, array('select' => 'id, title'));
        if (Doo::conf()->http_apivendor == 1) {
            //get api accounts
            Doo::loadModel('ScApiVendors');
            $vapiobj = new ScApiVendors;
            $data['vapi'] = Doo::db()->find($vapiobj, array('select' => 'id, title'));
        }
        //get blacklist databases
        Doo::loadModel('ScBlacklistIndex');
        $bobj = new ScBlacklistIndex;
        $data['bldb'] = Doo::db()->find($bobj, array('select' => 'id, table_name'), 2);
        //get credit count rules
        Doo::loadModel('ScCreditCountRules');
        $cobj = new ScCreditCountRules;
        $data['ccrule'] = Doo::db()->find($cobj, array('select' => 'id, rule_name'));
        //get all countries
        Doo::loadModel('ScCoverage');
        $cvobj = new ScCoverage;
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country, prefix, timezone', 'asc' => 'country'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_route';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editRoute', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveRoute()
    {

        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values

        $title = DooTextHelper::cleanInput($_POST['title'], " ", 0);
        $sidtype = intval($_POST['sid_type']);
        $defsid = DooTextHelper::cleanInput($_POST['defsid'], " ", 0);
        $slen = intval($_POST['sidlen']);
        $tflag = intval($_POST['tmpflag']);
        $cov = intval($_POST['cov']);
        $acttype = intval($_POST['acttype']);
        $bldbs = $_POST['bldb'];
        $crule = intval($_POST['ccrule']);

        //routing logic
        $route_config = array();
        $route_algo = intval($_POST['ralgo']);
        $all_smpps = []; //all smsc involved in this route
        if ($route_algo == 3) {
            //lcr
            $route_config['mode'] = 3;
            $route_config['smsc_list'] = array();
            $ctr = 0;
            foreach ($_POST['lcrsmppid'] as $smppid) {
                array_push($all_smpps, $smppid);
                array_push($route_config['smsc_list'], ['smpp' => $smppid]);
                $ctr++;
            }
        } elseif ($route_algo == 2) {
            //round-robin
            $route_config['mode'] = 2;
            $route_config['smsc_list'] = array();
            $ctr = 0;
            foreach ($_POST['rrsmppid'] as $smppid) {
                array_push($all_smpps, $smppid);
                array_push($route_config['smsc_list'], ['smpp' => $smppid, 'batchsize' => $_POST['rrsmppval'][$ctr]]);
                $ctr++;
            }
        } elseif ($route_algo == 1) {
            //percent dist
            $route_config['mode'] = 1;
            $route_config['smsc_list'] = array();
            $ctr = 0;
            foreach ($_POST['persmppid'] as $smppid) {
                array_push($all_smpps, $smppid);
                array_push($route_config['smsc_list'], ['smpp' => $smppid, 'percent' => $_POST['persmppval'][$ctr]]);
                $ctr++;
            }
        } else {
            //dedicated
            $route_config['mode'] = 0;
            $route_config['primary_smsc'] = intval($_POST['prismpp']);
            $route_config['primary_smsc_type'] = $_POST['pritype'];
            $route_config['backup_smsc'] = intval($_POST['bkpsmpp']);
            $route_config['backup_smsc_type'] = $_POST['bkptype'];
            $route_config['switch_rule']['main_down'] = intval($_POST['bkrule']);
            $route_config['switch_rule']['fail_switch'] = intval($_POST['bkruleo']);
            array_push($all_smpps, intval($_POST['prismpp']), intval($_POST['bkpsmpp']));
        }

        //prepare data
        $actdata = array();
        $actdata['type'] = $acttype;
        if ($acttype == 1) {
            $actdata['from'] = $_POST['actfrom'];
            $actdata['to'] = $_POST['actto'];
        }
        $actdata['timezone'] = DooTextHelper::cleanInput($_POST['acttz'], "\/");

        //get all the SMPP assigned and get TLV categories for all
        $smppobj = Doo::loadModel('ScSmppAccounts', true);
        $tlv_csv_strs = Doo::db()->find($smppobj, array('select' => 'tlv_ids', 'where' => 'id IN (' . implode(",", $all_smpps) . ')'));
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlv_id_groups = array();
        foreach ($tlv_csv_strs as $tlv_csv) {
            if ($tlv_csv->tlv_ids != "" && $tlv_csv->tlv_ids != null) {
                array_push($tlv_id_groups, $tlv_csv->tlv_ids);
            }
        }
        $tlv_types = array();
        if (sizeof($tlv_id_groups) > 0) {
            $tlv_cats = Doo::db()->find($tlvobj, array('select' => 'DISTINCT(tlv_category)', 'where' => 'id IN (' . implode(",", $tlv_id_groups) . ')'));
            foreach ($tlv_cats as $tlv_type) {
                array_push($tlv_types, $tlv_type->tlv_category);
            }
        }

        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $obj->admin_id = $_SESSION['user']['userid'];
        $obj->title = $title;
        $obj->smpp_list = implode(",", $all_smpps);
        $obj->route_config = json_encode($route_config);
        $obj->sender_type = $sidtype;
        $obj->def_sender = $defsid;
        $obj->max_sid_len = $slen;
        $obj->template_flag = $tflag;
        $obj->active_time = base64_encode(json_encode($actdata));
        $obj->country_id = $cov;
        $obj->add_pre = intval($_POST['add_pre']);
        $obj->blacklist_ids = is_array($bldbs) ? implode(",", $bldbs) : "";
        $obj->credit_rule = $crule;
        $obj->tlv_ids = json_encode($tlv_types);
        $obj->optout_config = htmlspecialchars($_POST['optoutmsg']);

        if ($_POST['routeid']) {
            //update db
            $rid = intval($_POST['routeid']);
            $obj->id = $rid;
            Doo::db()->update($obj, array('limit' => 1));
            $msg = 'Route details changed successfully';
        } else {
            //insert in db
            $routeid = Doo::db()->insert($obj);
            $msg = 'New route added successfully';
        }


        //return
        $_SESSION['notif_msg']['msg'] = $msg;
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageRoutes';
    }

    public function changeRouteStatus()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }

        $rid = intval($_POST['rid']);
        if ($rid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        $status = intval($_POST['status']);
        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $obj->id = $rid;
        $obj->status = $status;
        Doo::db()->update($obj, array('limit' => 1));
        echo $status == 0 ? $this->SCTEXT('The Route is now ACTIVE. Status changed successfully') : $this->SCTEXT('The route has been shutdown. Status changed successfully.');
        exit;
    }

    public function deleteRoute()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete route
        $rid = intval($this->params['id']);
        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $obj->id = $rid;
        Doo::db()->delete($obj, array('limit' => 1));

        //if yes refund {credits*rate} to wallet and delete entries from user rute assignment table
        if (Doo::conf()->wallet_refund_on_route_delete == 1) {
            //check if user assigned
            $crobj = Doo::loadModel('ScUsersCreditData', true);
            $usrs = Doo::db()->find($crobj, array('where' => 'route_id=' . $rid . ' AND status=0'));
            if (sizeof($usrs) > 0) {
                //matched users found
                $wlobj = Doo::loadModel('ScUsersWallet', true);
                $wltobj = Doo::loadModel('ScUsersWalletTransactions', true);
                $dobj = Doo::loadModel('ScUsersDocuments', true);
                $uobj = Doo::loadModel('ScUsers', true);

                foreach ($usrs as $usr) {
                    //calculate the invoice
                    $rdata = array();
                    $rdata[$rid]['credits'] = $usr->credits;
                    $rdata[$rid]['price'] = $usr->price;
                    $total = floatval($usr->credits * $usr->price);

                    $invdata['plan_tax'] = '';
                    $invdata['routes_credits'] = $rdata;
                    $invdata['total_cost'] = $total;
                    $invdata['additional_tax'] = '';
                    $invdata['discount'] = 'N/A';
                    $invdata['grand_total'] = $total;
                    $invdata['inv_status'] = 1;
                    $invdata['inv_rem'] = 'REFUND added into wallet equivalent to remaining SMS balance for the route.';

                    $udata = $uobj->getProfileInfo($usr->user_id, 'login_id,upline_id');

                    $dobj->filename = 'INVOICE_' . $udata->login_id . '_' . time();
                    $dobj->type = 1;
                    $dobj->owner_id = $udata->upline_id;
                    $dobj->shared_with = $usr->user_id;
                    $dobj->created_on = date(Doo::conf()->date_format_db);
                    $dobj->file_data = serialize($invdata);
                    $dobj->file_status = 1;
                    $dobj->init_remarks = $invdata['inv_rem'];

                    $inv_id = Doo::db()->insert($dobj);

                    //add into wallet
                    $wlobj->user_id = $usr->user_id;
                    $wdata = Doo::db()->find($wlobj, array('limit' => 1));

                    $wlobj->amount = $wdata->amount + floatval($invdata['grand_total']);
                    $wlobj->id = $wdata->id;
                    Doo::db()->update($wlobj, array('limit' => 1));

                    //add in wallet transaction history
                    $wltobj->wallet_id = $wdata->id;
                    $wltobj->transac_type = 1; //0 debit, 1 credit
                    $wltobj->amount = floatval($invdata['grand_total']);
                    $wltobj->t_date = date(Doo::conf()->date_format_db);
                    $wltobj->linked_invoice = $inv_id;

                    Doo::db()->insert($wltobj);
                }
            }
        }

        //delete route info from user credit table
        $crobj = Doo::loadModel('ScUsersCreditData', true);
        Doo::db()->delete($crobj, array("where" => 'route_id=' . $rid));

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'SMS Route deleted successfully';
        return Doo::conf()->APP_URL . 'manageRoutes';
    }

    // 4. Manage Blacklist DBs

    public function manageBlacklists()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Blacklist Databases';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_bl_db';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllBlDb()
    {
        //simply send all data to page as the quantity is small so no serverside processing required

        Doo::loadModel('ScBlacklistIndex');
        $obj = new ScBlacklistIndex;
        $tables = Doo::db()->find($obj, array(), 2);
        $total = count($tables);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 0;
        foreach ($tables as $dt) {

            $ctr++;
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editBlacklistDb/' . $dt->id . '">' . $this->SCTEXT('Edit Table') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewBlDb/' . $dt->id . '">' . $this->SCTEXT('View Details') . '</a></li></li><li><a href="' . Doo::conf()->APP_URL . 'manualInsertBlDb/' . $dt->id . '">' . $this->SCTEXT('Add Small Data') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'manualDelBlDb/' . $dt->id . '">' . $this->SCTEXT('Delete Numbers') . '</a></li><li><a class="remove_bldb" data-tid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Table') . '</a></li></ul></div>';

            $date = $dt->last_mod == '0000-00-00 00:00:00' ? '-' : date(Doo::conf()->date_format_long, strtotime($dt->last_mod));

            $output = array($ctr, $dt->table_name, $dt->mobile_column, number_format($dt->total_records), $date, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addBlacklistDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Blacklist Databases'] = Doo::conf()->APP_URL . 'manageBlacklists';
        $data['active_page'] = 'Add New Database';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_bl_db';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveBlacklistDb()
    {
        //validate input
        Doo::loadHelper('DooTextHelper');
        $tname = 'sc_' . DooTextHelper::cleanInput($_POST['tname']);
        $mcol = DooTextHelper::cleanInput($_POST['mcol']);

        Doo::loadModel('ScBlacklistIndex');
        $obj = new ScBlacklistIndex;

        if (intval($_POST['tid']) > 0) {
            $mode = 'edit';
            $obj->id = intval($_POST['tid']);
            $data = Doo::db()->find($obj, array('limit' => 1), 2);
            if (!$data->id) {
                $_SESSION['notif_msg']['msg'] = 'Invalid Table ID';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageBlacklists';
            }
        } else {
            $mode = 'add';
        }

        $obj->admin_id = $_SESSION['user']['userid'];
        $obj->table_name = $tname;
        $obj->mobile_column = $mcol;

        if ($mode == 'add') {
            //add record
            $obj->total_records = 0;
            Doo::db()->insert($obj, 2);
            //create table
            $qry = 'CREATE TABLE `' . $tname . '` (
                        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `' . $mcol . '` BIGINT NOT NULL ,
                        INDEX (
                        `' . $mcol . '`
                        )
                        )';
            Doo::db()->query($qry, null, 2);
        } else {
            //update record
            $obj->id = $data->id;
            Doo::db()->update($obj, null, 2);
            //alter table
            if ($tname != $data->table_name) {
                $alqry = 'RENAME TABLE `' . $data->table_name . '` TO `' . $tname . '`';
                Doo::db()->query($alqry, null, 2);
            }
            if ($mcol != $data->mobile_column) {
                $clqry = 'ALTER TABLE `' . $tname . '` CHANGE `' . $data->mobile_column . '` `' . $mcol . '` BIGINT( 20 )';
                Doo::db()->query($clqry, null, 2);
            }
        }

        //generate model
        $username = 'admin';
        $password = '1234';
        $ch = curl_init(Doo::conf()->APP_URL . 'gen_model');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );

        $resources = curl_exec($ch);
        curl_close($ch);
        //return
        $_SESSION['notif_msg']['msg'] = $mode == 'add' ? 'Blacklist database added in the system' : 'Blacklist database edited successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageBlacklists';
    }

    public function editBlacklistDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Blacklist Databases'] = Doo::conf()->APP_URL . 'manageBlacklists';
        $data['active_page'] = 'Edit Database';

        //fetch data
        $tid = intval($this->params['id']);
        if ($tid > 0) {
            //valid id
            Doo::loadModel('ScBlacklistIndex');
            $obj = new ScBlacklistIndex;
            $obj->id = $tid;
            $tdata = Doo::db()->find($obj, array('limit' => 1), 2);
            if ($tdata->id) {
                //record found
                $data['tdata'] = $tdata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageBlacklists';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }


        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_bl_db';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deleteBlacklistDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //fetch data
        $tid = intval($this->params['id']);
        if ($tid > 0) {
            //valid id
            Doo::loadModel('ScBlacklistIndex');
            $obj = new ScBlacklistIndex;
            $obj->id = $tid;
            $tdata = Doo::db()->find($obj, array('limit' => 1), 2);
            if (!$tdata->id) {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageBlacklists';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }

        //delete record
        $obj->id = $tdata->id;
        Doo::db()->delete($obj, null, 2);
        //drop table
        $qry = 'DROP TABLE `' . $tdata->table_name . '`';
        Doo::db()->query($qry, null, 2);
        //return
        $_SESSION['notif_msg']['msg'] = 'Blacklist database deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageBlacklists';
    }

    public function uploadBlacklistData()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //notifications
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Blacklist Databases'] = Doo::conf()->APP_URL . 'manageBlacklists';
        $data['active_page'] = 'Upload Mobile numbers';

        //fetch data
        Doo::loadModel('ScBlacklistIndex');
        $obj = new ScBlacklistIndex;
        $tdata = Doo::db()->find($obj, null, 2);
        $data['tdata'] = $tdata;
        $data['page'] = 'Administration';
        $data['current_page'] = 'upload_bl_db';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/uploadBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function addUploadTask()
    {
        //get values
        $table = $_POST['tid'];
        $files = $_POST['uploadedFiles'];

        //validate
        if (intval($table) == 0) {
            $_SESSION['notif_msg']['msg'] = 'Please select a table to import data.';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'uploadBlacklistData';
        }
        if (sizeof($files) == 0) {
            $_SESSION['notif_msg']['msg'] = 'Please upload at least one file.';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'uploadBlacklistData';
        }

        //read file
        Doo::loadHelper('PHPExcel');
        $ctr = 0;
        foreach ($files as $filename) {
            $path = Doo::conf()->global_upload_dir . $filename;
            $inputFileType = PHPExcel_IOFactory::identify($path);
            //prepare data
            $udata[$ctr]['admin_id'] = $_SESSION['user']['userid'];
            $udata[$ctr]['table_id'] = $table;
            $udata[$ctr]['file_name'] = $filename;
            $udata[$ctr]['filetype'] = $inputFileType;
            $udata[$ctr]['uploaded_on'] = date(Doo::conf()->date_format_db);
            $ctr++;
        }

        //add task
        Doo::loadModel('ScImportTasks');
        $obj = new ScImportTasks;
        $obj->addTask($udata);

        //return
        $_SESSION['notif_msg']['msg'] = 'The import task is added. System will start upload in a few minutes. You can check the status in <b><i>Actions -> View Details</i></b> Page';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageBlacklists';
    }

    public function viewBlDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //notifications
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Blacklist Databases'] = Doo::conf()->APP_URL . 'manageBlacklists';
        $data['active_page'] = 'View Blacklist DB Details';

        //get table info
        $tid = intval($this->params['id']);
        if ($tid > 0) {
            //valid id
            Doo::loadModel('ScBlacklistIndex');
            $obj = new ScBlacklistIndex;
            $obj->id = $tid;
            $tdata = Doo::db()->find($obj, array('limit' => 1), 2);
            if (!$tdata->id) {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageBlacklists';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }

        $data['tdata'] = $tdata;
        $data['page'] = 'Administration';
        $data['current_page'] = 'view_bl_db';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/viewBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getImportTasks()
    {
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScImportTasks');
        $obj = new ScImportTasks;
        $obj->table_id = $tid;
        $tasks = Doo::db()->find($obj, array(), 2);
        $total = count($tasks);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($tasks as $dt) {

            $filenm = '<i class="fa fa-file-text fa-lg"></i> &nbsp;' . $dt->filetype . ' File';
            $added = date(Doo::conf()->date_format_short_time, strtotime($dt->uploaded_on));
            if ($dt->status == 0) {
                $taskdata = '<ul class="list-group"><li class="list-group-item"><button data-taskid="' . $dt->id . '" class="cancel-import btn btn-sm btn-danger"><i class="fa fa-ban fa-lg"></i>&nbsp; ' . $this->SCTEXT('Cancel Upload') . '</button></li></ul>';

                $status = '<span class="label label-danger">' . $this->SCTEXT('Not Started') . '</span> &nbsp;<i class="fa fa-info-circle fa-lg taskinfo" data-title="' . htmlentities('Task Info <a class="pull-right closeDS" href="javascript:;"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($taskdata) . '"></i>';
            } else if ($dt->status == 1) {

                if ($dt->total_records > 0) {
                    $done_per = intval(($dt->records_done / $dt->total_records) * 100);
                } else {
                    $done_per = 0;
                }

                $taskdata = '<ul class="list-group"><li class="list-group-item"><div class="col-md-9"><div class="myprog progress progress-md"><div class="progress-bar progress-bar-striped active progress-bar-info" role="progressbar" aria-valuenow="' . $done_per . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $done_per . '%"></div></div></div><div class="col-md-3 text-right"><b class="progtext">' . $done_per . '%</b></div><div class="clearfix"></div></li><li class="list-group-item"><span class="badge badge-info">' . number_format($dt->total_records) . '</span>' . $this->SCTEXT('Total Rows') . ':<br><span class="badge badge-primary">' . number_format($dt->records_done) . '</span>' . $this->SCTEXT('Total Added') . ':</li><li class="list-group-item"><button data-taskid="' . $dt->id . '" class="cancel-import btn btn-sm btn-danger"><i class="fa fa-ban fa-lg"></i>&nbsp; ' . $this->SCTEXT('Cancel Upload') . '</button></li></ul>';

                $status = '<span class="label label-warning">' . $this->SCTEXT('In Progress') . '...</span> &nbsp;<i class="fa fa-info-circle fa-lg taskinfo" data-title="' . htmlentities('Task Info <a class="pull-right closeDS" href="javascript:;"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($taskdata) . '"></i>';
            } else if ($dt->status == 2) {

                $taskdata = '<ul class="list-group"><li class="list-group-item"><div class="col-md-9"><div class="myprog progress progress-md"><div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div><div class="col-md-3 text-right"><b class="progtext">100%</b></div><div class="clearfix"></div></li><li class="list-group-item"><span class="badge badge-info">' . number_format($dt->total_records) . '</span>' . $this->SCTEXT('Total Rows') . ':<br><span class="badge badge-primary">' . number_format($dt->records_done) . '</span>' . $this->SCTEXT('Total Added') . ':</li><li class="list-group-item"></li><li class="list-group-item list-group-item-info"><i class="fa fa-check-circle fa-lg"></i> &nbsp;' . date(Doo::conf()->date_format_med_time, strtotime($dt->completed_on)) . '</li></ul>';

                $status = '<span class="label label-success">' . $this->SCTEXT('Finished') . '</span> &nbsp;<i class="fa fa-info-circle fa-lg taskinfo" data-title="' . htmlentities('Task Info <a class="pull-right closeDS" href="javascript:;"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($taskdata) . '"></i>';
            } else {
                $status = '<span class="label label-default">' . $this->SCTEXT('Deleting Task') . '...</span>';
            }

            $output = array($filenm, $added, $status);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function deleteImportTask()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        $taskid = intval($this->params['id']);
        if ($taskid == 0) {
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScImportTasks');
        $obj = new ScImportTasks;
        $obj->id = $taskid;
        $tdata = Doo::db()->find($obj, array('limit' => 1), 2);
        $obj->status = 3;
        Doo::db()->update($obj, array(), 2);
        //return
        $_SESSION['notif_msg']['msg'] = 'The import task is marked for deletion and will be deleted in a few minutes.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewBlDb/' . $tdata->table_id;
    }

    public function numberLookupBlDb()
    {
        $mobile = $_GET['mobile'];
        $tinfo = $_GET['tinfo'];

        $tdata = explode("|", base64_decode($tinfo));
        Doo::loadHelper('DooTextHelper');
        $tblname = DooTextHelper::cleanInput($tdata[0]);
        $mobile_col = DooTextHelper::cleanInput($tdata[1]);
        //get classname
        $classname = '';
        $temptbl = $tblname;
        for ($i = 0; $i < strlen($temptbl); $i++) {
            if ($i == 0) {
                $classname .= strtoupper($temptbl[0]);
            } else if ($temptbl[$i] == '_' || $temptbl[$i] == '-' || $temptbl[$i] == '.') {
                $classname .= strtoupper($temptbl[($i + 1)]);
                $arr = str_split($temptbl);
                array_splice($arr, $i, 1);
                $temptbl = implode('', $arr);
            } else {
                $classname .= $temptbl[$i];
            }
        }
        //lookup
        Doo::loadModel($classname);
        $obj = new $classname;
        $obj->{$mobile_col} = $mobile;
        $opt['select'] = 'id,' . $mobile_col;
        $opt['limit'] = 1;
        $res = Doo::db()->find($obj, $opt, 2);

        //prepare response
        if (!$res || !$res->id) {
            $data['result'] = 0;
        } else {
            $data['result'] = 1;
            $data['id'] = $res->id;
        }
        //output
        echo json_encode($data);
        exit;
    }

    public function deleteNdncNumber()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        $id = intval($this->params['id']);
        if ($id == 0) {
            return array('/denied', 'internal');
        }
        //delete record
        $tinfo = $_GET['tinfo'];

        $tdata = explode("|", base64_decode($tinfo));
        Doo::loadHelper('DooTextHelper');
        $tblname = DooTextHelper::cleanInput($tdata[0]);
        $mobile_col = DooTextHelper::cleanInput($tdata[1]);
        //get classname
        $classname = '';
        $temptbl = $tblname;
        for ($i = 0; $i < strlen($temptbl); $i++) {
            if ($i == 0) {
                $classname .= strtoupper($temptbl[0]);
            } else if ($temptbl[$i] == '_' || $temptbl[$i] == '-' || $temptbl[$i] == '.') {
                $classname .= strtoupper($temptbl[($i + 1)]);
                $arr = str_split($temptbl);
                array_splice($arr, $i, 1);
                $temptbl = implode('', $arr);
            } else {
                $classname .= $temptbl[$i];
            }
        }
        //lookup
        Doo::loadModel($classname);
        $obj = new $classname;
        $obj->id = $id;
        Doo::db()->delete($obj, array('limit' => 1), 2);
        //update index
        Doo::loadModel('ScBlacklistIndex');
        $iobj = new ScBlackListIndex;
        $iobj->recordsChanged('remove', $tblname, 1);

        echo 'DONE';
    }

    public function bldbActions()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        $mode = $_POST['mode'];

        //delete record
        $tinfo = $_POST['tinfo'];
        $tdata = explode("|", base64_decode($tinfo));
        Doo::loadHelper('DooTextHelper');
        $tblname = DooTextHelper::cleanInput($tdata[0]);
        $mobile_col = DooTextHelper::cleanInput($tdata[1]);
        //get classname
        $classname = '';
        $temptbl = $tblname;
        for ($i = 0; $i < strlen($temptbl); $i++) {
            if ($i == 0) {
                $classname .= strtoupper($temptbl[0]);
            } else if ($temptbl[$i] == '_' || $temptbl[$i] == '-' || $temptbl[$i] == '.') {
                $classname .= strtoupper($temptbl[($i + 1)]);
                $arr = str_split($temptbl);
                array_splice($arr, $i, 1);
                $temptbl = implode('', $arr);
            } else {
                $classname .= $temptbl[$i];
            }
        }
        //act

        Doo::loadModel($classname);
        $obj = new $classname;

        if ($mode == 'empty') {
            Doo::db()->truncateTable($obj, 2);
            $msg = 'All records deleted successfully';
            //update index
            Doo::loadModel('ScBlacklistIndex');
            $iobj = new ScBlackListIndex;
            $iobj->recordsChanged('removeall', $tblname);
        } else {
            Doo::db()->optimizeTable($obj, 2);
            $msg = 'Table optimized successfully.';
        }
        echo $msg;
        exit;
    }

    public function manualInsertBldb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //notifications
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Blacklist Databases'] = Doo::conf()->APP_URL . 'manageBlacklists';
        $data['active_page'] = 'Manually Add Data';

        //fetch data
        Doo::loadModel('ScBlacklistIndex');
        $obj = new ScBlacklistIndex;
        $obj->id = intval($this->params['id']);
        $tdata = Doo::db()->find($obj, array('limit' => 1), 2);

        $data['tdata'] = $tdata;
        $data['page'] = 'Administration';
        $data['current_page'] = 'manual_add_bldb';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manAddBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveManualInsertBlDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $table_id = intval($_POST['tableid']);
        $numbers = $_POST['numbers'];
        $stmt = '';
        if ($numbers != '') {
            //contact numbers are entered in input box -- read them all in the array
            $input_contacts = explode("\n", $numbers);
            $total = 0;
            foreach ($input_contacts as $contact) {
                $contact = floatval($contact);
                if ($contact != 0) {
                    $stmt .= '(' . $contact . '),';
                    $total++;
                }
            }
        } else {
            //return error
            $_SESSION['notif_msg']['msg'] = 'Mobile numbers cannot be empty.';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manualInserBlDb/' . $table_id;
        }
        $stmt = substr($stmt, 0, strlen($stmt) - 1);
        $tblobj = Doo::loadModel('ScBlacklistIndex', true);

        //get table details
        $tblobj->id = $table_id;
        $tbldata = Doo::db()->find($tblobj, array('limit' => 1), 2);
        $orgtblcount = $tbldata->total_records;
        //prepare insert statement
        $query = "INSERT INTO `$tbldata->table_name`(`$tbldata->mobile_column`) VALUES $stmt";
        //insert in ndnc table
        $rs = Doo::db()->query($query, null, 2);

        //update count
        $tblobj->id = $table_id;
        $tblobj->last_mod = date(Doo::conf()->date_format_db);
        $tblobj->total_records = $orgtblcount + $total;
        Doo::db()->update($tblobj, array('limit' => 1), 2);
        //return
        $_SESSION['notif_msg']['msg'] = 'Successfully imported mobile numbers into blacklist database.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageBlacklists';
    }

    public function manualDelBlDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //notifications
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Blacklist Databases'] = Doo::conf()->APP_URL . 'manageBlacklists';
        $data['active_page'] = 'Delete Numbers';

        //fetch data
        Doo::loadModel('ScBlacklistIndex');
        $obj = new ScBlacklistIndex;
        $obj->id = intval($this->params['id']);
        $tdata = Doo::db()->find($obj, array('limit' => 1), 2);

        $data['tdata'] = $tdata;
        $data['page'] = 'Administration';
        $data['current_page'] = 'manual_del_bldb';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manDelBlDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveManualDelBlDb()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['ndnc']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $table_id = intval($_POST['tableid']);
        $numbers = $_POST['numbers'];
        $stmt = '';
        if ($numbers != '') {
            //contact numbers are entered in input box -- read them all in the array
            $input_contacts = explode("\n", $numbers);
            $total = 0;
            foreach ($input_contacts as $contact) {
                $contact = floatval($contact);
                if ($contact != 0) {
                    $stmt .= $contact . ',';
                    $total++;
                }
            }
        } else {
            //return error
            $_SESSION['notif_msg']['msg'] = 'Mobile numbers cannot be empty.';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manualDelBlDb/' . $table_id;
        }
        $stmt = substr($stmt, 0, strlen($stmt) - 1);
        $tblobj = Doo::loadModel('ScBlacklistIndex', true);

        //get table details
        $tblobj->id = $table_id;
        $tbldata = Doo::db()->find($tblobj, array('limit' => 1), 2);
        $orgtblcount = $tbldata->total_records;
        //prepare insert statement
        $query = "DELETE FROM `$tbldata->table_name` WHERE `$tbldata->mobile_column` IN ($stmt)";
        //insert in ndnc table
        $rs = Doo::db()->query($query, null, 2);
        //update count
        $tblobj->id = $table_id;
        $tblobj->last_mod = date(Doo::conf()->date_format_db);
        $tblobj->total_records = $orgtblcount - $total;
        Doo::db()->update($tblobj, array('limit' => 1), 2);
        //return
        $_SESSION['notif_msg']['msg'] = 'Successfully deleted mobile numbers from blacklist database.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageBlacklists';
    }


    //5. Manage Credit count Rules


    public function manageCountRules()
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
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Credit Count Rules';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_ccrules';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageCCR', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllCountRules()
    {
        Doo::loadModel('ScCreditCountRules');
        $obj = new ScCreditCountRules;
        $rules = Doo::db()->find($obj, array('select' => 'id,rule_name,normal_sms_rule,unicode_rule,special_chars_rule'));
        $total = count($rules);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($rules as $dt) {

            $normal_rule = json_decode($dt->normal_sms_rule, true);
            $unicode_rule = json_decode($dt->unicode_rule, true);
            $spcl_rule = json_decode($dt->special_chars_rule, true);
            //echo '<pre>';var_dump($normal_rule);die;
            $rule_html = '<ul class="list-group">
                                                    <li class="list-group-item">
                                                        Normal SMS
                                                        <hr>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-success">1&nbsp; sms</span>
                                                            <span class="pull-right">' . $normal_rule[1]['from'] . ' to ' . $normal_rule[1]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-info">2&nbsp; sms</span>
                                                            <span class="pull-right">' . $normal_rule[2]['from'] . ' to ' . $normal_rule[2]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-warning">3&nbsp; sms</span>
                                                            <span class="pull-right">' . $normal_rule[3]['from'] . ' to ' . $normal_rule[3]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-primary">4&nbsp; sms</span>
                                                            <span class="pull-right">' . $normal_rule[4]['from'] . ' to ' . $normal_rule[4]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-deepOrange">5&nbsp; sms</span>
                                                            <span class="pull-right">' . $normal_rule[5]['from'] . ' to ' . $normal_rule[5]['to'] . ' chars</span>
                                                        </div>



                                                    </li>
                                                    <li class="list-group-item">
                                                        Unicode SMS
                                                        <hr>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-success">1&nbsp; sms</span>
                                                            <span class="pull-right">' . $unicode_rule[1]['from'] . ' to ' . $unicode_rule[1]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-info">2&nbsp; sms</span>
                                                            <span class="pull-right">' . $unicode_rule[2]['from'] . ' to ' . $unicode_rule[2]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-warning">3&nbsp; sms</span>
                                                            <span class="pull-right">' . $unicode_rule[3]['from'] . ' to ' . $unicode_rule[3]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-primary">4&nbsp; sms</span>
                                                            <span class="pull-right">' . $unicode_rule[4]['from'] . ' to ' . $unicode_rule[4]['to'] . ' chars</span>
                                                        </div>
                                                        <div class="m-h-xs">
                                                            <span class="badge badge-f14 badge-deepOrange">5&nbsp; sms</span>
                                                            <span class="pull-right">' . $unicode_rule[5]['from'] . ' to ' . $unicode_rule[5]['to'] . ' chars</span>
                                                        </div>



                                                    </li>

                                                    <li class="list-group-item">
                                                        Special Characters
                                                        <hr>';
            foreach ($spcl_rule['counts'] as $cnt => $signs) {
                $rule_html .= '<div class="m-h-xs clearfix">
                                                            <span class="badge badge-f14 badge-info">' . intval($cnt) . '&nbsp; char(s)</span>
                                                            <span class="pull-right signsbox">' . str_replace("clb", "]", str_replace("ln", '\n', implode("  ", $signs))) . '</span>
                                                        </div>';
            }

            $rule_html .= '
                                                    </li>

                                                </ul>';

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editCountRule/' . $dt->id . '">' . $this->SCTEXT('Edit Details') . '</a></li><li><a class="remove_ccr" data-rid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Rule') . '</a></li></ul></div>';

            $details_str = '<span class="label label-info">' . $this->SCTEXT('View Count Rule Details') . '</span> &nbsp;<i class="fa fa-info-circle fa-lg ruleinfo" data-title="' . htmlentities('Credit Count Rule <a class="pull-right closeDS" href="javascript:;"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($rule_html) . '"></i>';

            $output = array($dt->rule_name, $details_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addCountRule()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Credit Count Rules'] = Doo::conf()->APP_URL . 'manageCountRules';
        $data['active_page'] = 'Add New Rule';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_ccrule';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addCCR', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editCountRule()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Credit Count Rules'] = Doo::conf()->APP_URL . 'manageCountRules';
        $data['active_page'] = 'Edit Count Rule';

        //fetch data
        $rid = intval($this->params['id']);
        if ($rid > 0) {
            //valid id
            Doo::loadModel('ScCreditCountRules');
            $obj = new ScCreditCountRules;
            $obj->id = $rid;
            $rdata = Doo::db()->find($obj, array('limit' => 1));
            if ($rdata->id) {
                //record found
                $data['rdata'] = $rdata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageCountRules';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }

        //$sd = unserialize($rdata->special_chars_rule);
        //echo '<pre>';var_dump($sd['vals']);die;

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_ccrule';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editCCR', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveCountRule()
    {
        //get values
        $rule_name = $_POST['ccrname'];

        $normal_rule = json_encode($_POST['normal_chars']);
        $unicode_rule = json_encode($_POST['unicode_chars']);
        $sign_rule = $_POST['signs'];
        //prepare data
        $data = array();
        foreach ($sign_rule as $sign => $count) {
            $data['counts'][intval($count)][] = $sign == "clb" ? "]" : $sign;
            if ($sign == "clb") {
                $sign_rule[']'] = $count;
                unset($sign_rule['clb']);
            }
        }
        $data['vals'] = $sign_rule;
        $special_chars_rule = json_encode($data, JSON_UNESCAPED_UNICODE);

        //save values
        Doo::loadModel('ScCreditCountRules');
        $obj = new ScCreditCountRules;

        $obj->rule_name = $rule_name;
        $obj->normal_sms_rule = $normal_rule;
        $obj->unicode_rule = $unicode_rule;
        $obj->special_chars_rule = $special_chars_rule;

        if (intval($_POST['rid']) > 0) {
            $obj->id = intval($_POST['rid']);
            Doo::db()->update($obj, array('limit' => 1));
            $msg = 'Credit count rule edited successfully';
        } else {
            Doo::db()->insert($obj);
            $msg = 'New credit count rule added successfully';
        }

        //return
        $_SESSION['notif_msg']['msg'] = $msg;
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageCountRules';
    }

    public function delCountRule()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        $ruleid = intval($this->params['id']);
        if ($ruleid == 0) {
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScCreditCountRules');
        $obj = new ScCreditCountRules;
        $obj->id = $ruleid;
        Doo::db()->delete($obj, array('limit' => 1));
        //return
        $_SESSION['notif_msg']['msg'] = 'The rule was deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageCountRules';
    }



    //6. Manage SMS Plans

    public function manageSmsPlans()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['SMS Plans'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage SMS Plans';

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'manage_smsplans';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageSmsPlans', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllSmsPlans()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once as its not huge
        Doo::loadModel('ScSmsPlans');
        $sobj = new ScSmsPlans;
        $plans = Doo::db()->find($sobj);
        $total = count($plans);

        Doo::loadModel('ScUsersSmsPlans');
        $obj = new ScUsersSmsPlans;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($plans as $dt) {

            if ($dt->plan_type == 0) {
                $tstr = '<span class="label label-info label-md">' . $this->SCTEXT('Volume based pricing plan') . '</span>';
            } else {
                $tstr = '<span class="label label-warning label-md">' . $this->SCTEXT('Subscription based pricing plan') . '</span>';
            }
            $ucount = sizeof(Doo::db()->find($obj, array('select' => 'id', 'where' => 'plan_id=' . $dt->id)));

            switch ($dt->tax_type) {
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
            }

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editSmsPlan/' . $dt->id . '">' . $this->SCTEXT('Edit Plan') . '</a></li><li><a href="javascript:void(0);" class="del-plan" data-ucount="' . $ucount . '" data-pid="' . $dt->id . '">' . $this->SCTEXT('Delete Plan') . '</a></li></ul></div>';


            $output = array($dt->plan_name, $tstr, number_format($ucount), $dt->tax . '% ' . $type, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addSmsPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage SMS Plans'] = Doo::conf()->APP_URL . 'manageSmsPlans';
        $data['active_page'] = 'Add New SMS Plan';

        //all routes
        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $data['rdata'] = Doo::db()->find($obj, array('select' => 'id, title'));

        //all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'add_smsplan';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addSmsPlan', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editSmsPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage SMS Plans'] = Doo::conf()->APP_URL . 'manageSmsPlans';
        $data['active_page'] = 'Edit SMS Plan';

        //all routes
        Doo::loadModel('ScSmsRoutes');
        $obj = new ScSmsRoutes;
        $data['rdata'] = Doo::db()->find($obj, array('select' => 'id, title'));

        //all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        //plan details
        Doo::loadModel('ScSmsPlans');
        $sobj = new ScSmsPlans;
        $sobj->id = intval($this->params['id']);
        $data['pdata'] = Doo::db()->find($sobj, array('limit' => 1));

        //plan options
        Doo::loadModel('ScSmsPlanOptions');
        $obj = new ScSmsPlanOptions;
        $obj->plan_id = intval($data['pdata']->id);
        if (intval($data['pdata']->plan_type) == 0) {
            $data['opdata'] = Doo::db()->find($obj, array('limit' => 1));
        } else {
            $data['opdata'] = Doo::db()->find($obj, array('asc' => 'subopt_idn'));
        }


        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'edit_smsplan';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editSmsPlan', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveSmsPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $planname = $_POST['pname'];
        $type = intval($_POST['ptype']);
        $routes = implode(",", $_POST['proutes']);
        $taxper = $_POST['ptax'];
        $taxtype = $_POST['taxtype'];

        if ($type == 0) {
            //volume based pricing

            //prepare to save
            $pricing_data = array();
            foreach ($_POST['range'] as $index => $range) {
                $pricing_data[$index]['min'] = $range['from'];
                $pricing_data[$index]['max'] = $range['to'];
                foreach ($_POST['price'][$index] as $rid => $prc) {
                    $pricing_data[$index][$rid] = $prc;
                }
            }

            if (intval($_POST['plan_id']) != 0) {
                //update
                Doo::loadModel('ScSmsPlans');
                $sobj = new ScSmsPlans;
                $sobj->id = intval($_POST['plan_id']);
                $sobj->plan_name = $planname;
                $sobj->route_ids = $routes;
                $sobj->tax = $taxper;
                $sobj->tax_type = $taxtype;

                Doo::db()->update($sobj, array('limit' => 1));

                //update plan options
                Doo::loadModel('ScSmsPlanOptions');
                $obj = new ScSmsPlanOptions;
                $obj->opt_data = serialize($pricing_data);
                $opt['where'] = 'plan_id =' . intval($_POST['plan_id']);
                $opt['limit'] = 1;
                Doo::db()->update($obj, $opt);
                $msg = 'SMS Plan modified successfully';
            } else {

                //insert in sms plans
                Doo::loadModel('ScSmsPlans');
                $sobj = new ScSmsPlans;
                $sobj->admin_id = $_SESSION['user']['userid'];
                $sobj->plan_name = $planname;
                $sobj->route_ids = $routes;
                $sobj->plan_type = $type;
                $sobj->tax = $taxper;
                $sobj->tax_type = $taxtype;

                $planid = Doo::db()->insert($sobj);

                //insert in sms plan options
                Doo::loadModel('ScSmsPlanOptions');
                $obj = new ScSmsPlanOptions;
                $obj->plan_id = $planid;
                $obj->plan_type = $type;
                $obj->opt_data = serialize($pricing_data);

                Doo::db()->insert($obj);
                $msg = 'SMS Plan added successfully';
            }
        } else {
            //subscription based pricing
            //update
            if (intval($_POST['plan_id']) != 0) {
                //update
                Doo::loadModel('ScSmsPlans');
                $sobj = new ScSmsPlans;
                $sobj->id = intval($_POST['plan_id']);
                $sobj->plan_name = $planname;
                $sobj->route_ids = $routes;
                $sobj->tax = $taxper;
                $sobj->tax_type = $taxtype;

                Doo::db()->update($sobj, array('limit' => 1));
                //prepare to save
                $plan_options = array();
                foreach ($_POST['subopts'] as $subopt_idn) {
                    $plan_options[$subopt_idn]['idn'] = $subopt_idn;
                    $plan_options[$subopt_idn]['name'] = $_POST['poptname'][$subopt_idn];
                    $plan_options[$subopt_idn]['cycle'] = $_POST['poptcycle'][$subopt_idn];
                    $plan_options[$subopt_idn]['fee'] = $_POST['poptrate'][$subopt_idn];
                    $plan_options[$subopt_idn]['route_credits'] = $_POST['poptcredits'][$subopt_idn];
                    $plan_options[$subopt_idn]['route_add_sms_rate'] = $_POST['poptaddrate'][$subopt_idn];
                    $plan_options[$subopt_idn]['description'] = $_POST['poptdesc'][$subopt_idn];
                    $plan_options[$subopt_idn]['optin'] = $_POST['poptsel'][$subopt_idn];
                    $plan_options[$subopt_idn]['features'] = $_POST['sftperm'][$subopt_idn];
                    $plan_options[$subopt_idn]['expire'] = intval($_POST['expireflag']);
                }

                //clean data
                Doo::loadModel('ScSmsPlanOptions');
                $cobj = new ScSmsPlanOptions;
                Doo::db()->delete($cobj, array('where' => 'plan_id=' . intval($_POST['plan_id'])));
                //insert in sms plan options
                Doo::loadModel('ScSmsPlanOptions');
                $obj = new ScSmsPlanOptions;
                $obj->addSubsOptions(intval($_POST['plan_id']), $plan_options);

                $msg = 'SMS Plan modified successfully';
            } else {
                //insert in sms plans
                Doo::loadModel('ScSmsPlans');
                $sobj = new ScSmsPlans;
                $sobj->admin_id = $_SESSION['user']['userid'];
                $sobj->plan_name = $planname;
                $sobj->route_ids = $routes;
                $sobj->plan_type = $type;
                $sobj->tax = $taxper;
                $sobj->tax_type = $taxtype;

                $planid = Doo::db()->insert($sobj);
                //prepare to save
                $plan_options = array();
                foreach ($_POST['subopts'] as $subopt_idn) {
                    $subopt_idn_new = 'P' . $planid . '-' . $subopt_idn;
                    $plan_options[$subopt_idn_new]['idn'] = $subopt_idn_new;
                    $plan_options[$subopt_idn_new]['name'] = $_POST['poptname'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['cycle'] = $_POST['poptcycle'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['fee'] = $_POST['poptrate'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['route_credits'] = $_POST['poptcredits'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['route_add_sms_rate'] = $_POST['poptaddrate'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['description'] = $_POST['poptdesc'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['optin'] = $_POST['poptsel'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['features'] = $_POST['sftperm'][$subopt_idn];
                    $plan_options[$subopt_idn_new]['expire'] = intval($_POST['expireflag']);
                }

                //insert in sms plan options
                Doo::loadModel('ScSmsPlanOptions');
                $obj = new ScSmsPlanOptions;
                $obj->addSubsOptions($planid, $plan_options);

                $msg = 'SMS Plan added successfully';
            }
        }


        //return
        $_SESSION['notif_msg']['msg'] = $msg;
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageSmsPlans';
    }

    public function delSmsPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
        $planid = $this->params['id'];
        Doo::loadModel('ScSmsPlans');
        $sobj = new ScSmsPlans;
        $sobj->id = $planid;
        Doo::db()->delete($sobj, array('limit' => 1));

        Doo::loadModel('ScSmsPlanOptions');
        $cobj = new ScSmsPlanOptions;
        Doo::db()->delete($cobj, array('where' => 'plan_id=' . $planid));

        //return
        $_SESSION['notif_msg']['msg'] = 'SMS Plan deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageSmsPlans';
    }

    public function getSelPlanOptions()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['creditplan']) {
            //denied
            return array('/denied', 'internal');
        }
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



    //7. Manage countries and prefixes

    public function manageCountries()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Countries';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_countries';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageCountries', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllCountries()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once as its not huge
        Doo::loadModel('ScCoverage');
        $obj = new ScCoverage;
        $cts = Doo::db()->find($obj, array('where' => 'id > 1'));
        $total = count($cts);

        Doo::loadModel('ScNsnPrefixList');
        $cobj = new ScNsnPrefixList;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($cts as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editCountry/' . $dt->id . '">' . $this->SCTEXT('Edit Details') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewAllOP/' . $dt->id . '">' . $this->SCTEXT('View Operator prefixes') . '</a></li></ul></div>';

            $count = $cobj->countPrefixesByCoverage($dt->prefix);

            $output = array($dt->country . ' ( ' . $dt->country_code . ' )', $dt->prefix, number_format($count), $dt->timezone, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function editCountry()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Countries'] = Doo::conf()->APP_URL . 'manageCountries';
        $data['active_page'] = 'Edit Country Details';
        //notif
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //fetch data
        $cid = intval($this->params['id']);
        if ($cid > 0) {
            //valid id
            Doo::loadModel('ScCoverage');
            $obj = new ScCoverage;
            $obj->id = $cid;
            $cdata = Doo::db()->find($obj, array('limit' => 1));
            if ($cdata->id) {
                //record found
                $data['cdata'] = $cdata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageCountries';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }


        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_country';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editCountry', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveCountry()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $cid = intval($_POST['cid']);
        $cname = $_POST['cname'];
        $prefix = '+' . intval($_POST['cpre']);
        $valid_lens = $_POST['cvl'];
        $op_plen = intval($_POST['copl']);
        $digs = implode(",", array_keys($_POST['digsallowed']));
        $tz = $_POST['timezone'];
        $regulations = htmlspecialchars($_POST['creg']);
        //validate
        if ($cid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        $valid_len_ar = explode(",", $valid_lens);
        $err = 0;
        foreach ($valid_len_ar as $val) {
            if (intval($val) == 0) {
                $err = 1;
                $msg = 'Only integer values allowed in Valid Mobile No. Length.';
            }
            if (intval($val) > 15) {
                $err = 1;
                $msg = 'Valid mobile number cannot be more than 15 digits.';
            }
        }
        if ($err > 0) {
            $_SESSION['notif_msg']['msg'] = $msg;
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'editCountry/' . $cid;
        }
        //save in db
        Doo::loadModel('ScCoverage');
        $obj = new ScCoverage;
        $obj->id = $cid;
        $obj->country = $cname;
        $obj->prefix = $prefix;
        $obj->valid_lengths = $valid_lens;
        $obj->allowed_first_digits = $digs;
        $obj->regulations = $regulations;
        $obj->timezone = $tz;

        Doo::db()->update($obj, array('limit' => 1));
        //return
        $_SESSION['notif_msg']['msg'] = 'Country details edited successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageCountries';
    }

    public function uploadPrefixes()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Countries'] = Doo::conf()->APP_URL . 'manageCountries';
        $data['active_page'] = 'Upload Operator Prefixes';
        //load all coverages
        Doo::loadModel('ScCoverage');
        $obj = new ScCoverage;
        $data['cdata'] = Doo::db()->find($obj, array("select" => 'id,country,prefix', 'asc' => 'country'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'upload_prefix';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/uploadOpre', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function importPrefixes()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        // $cid = intval($_POST['country']);
        // $file = $_POST['uploadedFiles'][0];
        // //read excel file
        // Doo::loadHelper("PHPExcel");
        // $filepath = Doo::conf()->global_upload_dir . $file;
        // $inputFileType = PHPExcel_IOFactory::identify($filepath);
        // //--  Create a new Reader of the type that has been identified
        // $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        // //--  Load $inputFileName to a PHPExcel Object
        // $xlobj = $objReader->load($filepath);

        // //save data into table
        // $data = array();
        // $sheet = $xlobj->getActiveSheet();
        // if (strtolower($sheet->getCell('A1')->getValue()) == 'prefix' || strtolower($sheet->getCell('A1')->getValue()) == 'prefixes') {
        //     //format is correct -- read the data
        //     for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
        //         $prefix = $xlobj->getActiveSheet()->getCell('A' . $i)->getValue();
        //         if ($prefix != '') {
        //             $data[$i]['prefix'] = $prefix;
        //             $data[$i]['operator'] = $sheet->getCell('B' . $i)->getValue();
        //             $data[$i]['circle'] = $sheet->getCell('C' . $i)->getValue();
        //         }
        //     }
        // } else {
        //     //invalid format of excelfile
        //     $_SESSION['notif_msg']['msg'] = 'Invalid data format in the file. Make sure you have COLUMN NAMES as directed in the instructions. Download the Sample File below for reference';
        //     $_SESSION['notif_msg']['type'] = 'error';
        //     return Doo::conf()->APP_URL . 'uploadPrefixes';
        // }

        // Doo::loadModel('ScOcprMapping');
        // $obj = new ScOcprMapping;
        // $obj->importData($cid, $data);
        //delete file
        //@unlink($filepath);
        //return
        $_SESSION['notif_msg']['msg'] = 'Operator prefix data import has been disabled in this version';
        $_SESSION['notif_msg']['type'] = 'error';
        return Doo::conf()->APP_URL . 'manageCountries';
    }

    public function viewAllOP()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Countries'] = Doo::conf()->APP_URL . 'manageCountries';
        $data['active_page'] = 'Operator Prefix data';

        $cid = intval($this->params['id']);
        Doo::loadModel('ScCoverage');
        $obj = new ScCoverage;
        $obj->id = $cid;
        $data['cdata'] = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id,country,country_code'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'view_prefix';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/managePrefixes', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllOP()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once as its not huge
        Doo::loadModel('ScNsnPrefixList');
        $obj = new ScNsnPrefixList;
        $obj->country_iso = $this->params['id'];
        $pres = Doo::db()->find($obj);
        $total = count($pres);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($pres as $dt) {

            $button_str = '<div class="btn-group"><a href="' . Doo::conf()->APP_URL . 'editOP/' . $dt->id . '" class="btn btn-info"> <i class="fa fa-large fa-pencil-alt" title="' . $this->SCTEXT('Edit Details') . '"></i> </a><a href="javascript:void(0);" data-pid="' . $dt->id . '" class="delprefix btn btn-danger"> <i class="fa fa-large fa-trash" title="' . $this->SCTEXT('Delete') . '"></i> </a></div>';

            $mccmnc = $dt->mccmnc == 0 ? '-' : '<kbd>' . $dt->mccmnc . '</kbd>';

            $output = array($dt->prefix . '<input type="hidden" class="pids" value="' . $dt->id . '"/>', $mccmnc, ($dt->brand), ($dt->operator), $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function editOP()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        $pid = intval($this->params['id']);
        Doo::loadModel('ScNsnPrefixList');
        $obj = new ScNsnPrefixList;
        $obj->id = $pid;
        $data['pdata'] = Doo::db()->find($obj, array('limit' => 1));

        //get id of coverage for breadcrumb
        $cobj = Doo::loadModel('ScCoverage', true);
        $cobj->country_code = $data['pdata']->country_iso;
        $cvinfo = Doo::db()->find($cobj, array('limit' => 1));

        //breadcrums
        $data['links']['Manage Countries'] = Doo::conf()->APP_URL . 'manageCountries';
        $data['links']['Manage Prefixes'] = Doo::conf()->APP_URL . 'viewAllOP/' . $cvinfo->id;
        $data['active_page'] = 'Edit Operator Prefix';

        //get country code

        $countryiso = $data['pdata']->country_iso;

        //get all mcc mnc list
        $mobj = Doo::loadModel('ScMccMncList', true);
        $mobj->country_iso = $countryiso;
        $data['mccmnc'] = Doo::db()->find($mobj);
        $data['covid'] = $cvinfo->id;


        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_prefix';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editPrefix', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveOP()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }

        //collect values
        $id = intval($_POST['pid']);
        $brand = $_POST['operator'];
        $operator = $_POST['circle'];
        //validate
        if ($id == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //save changes
        Doo::loadModel('ScNsnPrefixList');
        $obj = new ScNsnPrefixList;
        $obj->id = $id;
        $obj->mccmnc = $_POST['mccmnc'];
        $obj->brand = $brand;
        $obj->operator = $operator;
        Doo::db()->update($obj, array('limit' => 1));

        //return
        $_SESSION['notif_msg']['msg'] = 'Operator prefix data edited successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewAllOP/' . intval($_POST['cid']);
    }

    public function delManyOP()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        $cid = intval($_POST['cid']);
        $pids = $_POST['pids'];

        Doo::loadModel('ScNsnPrefixList');
        $obj = new ScNsnPrefixList;
        $opt['where'] = "id IN ($pids)";
        Doo::db()->delete($obj, $opt);

        //return
        $_SESSION['notif_msg']['msg'] = 'Selected prefixes deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        echo 'DONE';
        exit;
    }

    public function deleteOP()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['cpre']) {
            //denied
            return array('/denied', 'internal');
        }
        $pid = intval($this->params['id']);
        $cid = intval($this->params['cid']);
        Doo::loadModel('ScNsnPrefixList');
        $obj = new ScNsnPrefixList;
        $obj->id = $pid;
        Doo::db()->delete($obj, array('limit' => 1));

        //return
        $_SESSION['notif_msg']['msg'] = 'Selected prefix deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewAllOP/' . $cid;
    }


    //8. Approve Sender ID

    public function approveSenderIds()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Approve Sender Ids';

        $data['page'] = 'Administration';
        $data['current_page'] = 'approve_sids';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/approveSids', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllPendingSenderIds()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once since there cannot be huge sender ids
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $sids = $obj->getSenderIds();
        $total = count($sids);

        $sqry = "SELECT prefix, country FROM sc_coverage";
        $cvlist = Doo::db()->fetchAll($sqry, null, PDO::FETCH_KEY_PAIR);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($sids as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'approveSid/' . $dt->id . '">' . $this->SCTEXT('Approve') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'reviewSid/' . $dt->id . '">' . $this->SCTEXT('Under Review') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'rejectSid/' . $dt->id . '">' . $this->SCTEXT('Reject') . '</a></li></ul></div>';

            $status_str = $dt->status == '1' ? ' <span class="label label-success label-md">' . $this->SCTEXT('Approved') . '</span>' : ($dt->status == '2' ? '<span class="label label-danger label-md">' . $this->SCTEXT('Rejected') . '</span>' : ($dt->status == '-1' ? '<span class="label label-success label-md">' . $this->SCTEXT('Under Review') . '</span>' : '<span class="label label-warning label-md">' . $this->SCTEXT('Pending') . '</span>'));

            if ($dt->countries_matrix == "") {
                $covstr = '<span>' . 'All Countries' . '</span><hr><span>' . 'All Operators' . '</span>';
            } else {
                $cmtrx = json_decode($dt->countries_matrix, true);
                $countries = $cmtrx['countries'];
                $operators = $cmtrx['operators'];

                $countries_str = implode(', ', array_map(function ($key) use ($cvlist) {
                    return $cvlist[$key];
                }, $countries));

                $operators_str = $operators[0] == '0' ? 'All Operators' : implode(', ', array_map(function ($str) {
                    return implode(' - ', explode('|', base64_decode($str)));
                }, $operators));
                $covstr = '<span>' . $countries_str . '</span><hr><span>' . $operators_str . '</span>';
            }


            if ($dt->file_ids != "") {
                $cfiles = explode(",", $dt->file_ids);
                $file_str = "";
                foreach ($cfiles as $fl) {
                    $file_str .= '<div class="btn-group m-r-sm">
                    <span class="input-group-addon m-r-sm"><i class="fa fa-2x fa-file text-primary"></i></span><a class="btn btn-sm btn-info" title="' . $this->SCTEXT('View File') . '" target="_blank" href="' . Doo::conf()->APP_URL . 'viewDocument/' . $fl . '"><i class="fa fa-search fa-lg"></i></a><a class="btn btn-sm btn-success" title="' . $this->SCTEXT('Download File') . '" href="' . Doo::conf()->APP_URL . 'globalFileDownload/docmgr/' . $fl . '"><i class="fa fa-download fa-lg"></i></a> 
                    </div>';
                }
            } else {
                $file_str = "- No Files Attached -";
            }

            $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->req_by . '"><img src="' . $dt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->req_by . '" class="m-r-xs theme-color">' . ucwords($dt->name) . '</a><small class="text-muted fz-sm">' . ucwords($dt->category) . '</small></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $dt->email . '</p>
                                            </div>
                                        </div>';

            $output = array($dt->sender_id . '<input type="hidden" class="sids" data-user="' . $dt->req_by . '" value="' . $dt->id . '"/>', $user_str, $covstr, date(Doo::conf()->date_format_long, strtotime($dt->req_on)), $file_str, $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function approveManySids()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        $sids = $_POST['sids'];
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->status = 1;
        Doo::db()->update($obj, array('where' => "id IN ($sids)"));

        //all sid data
        $allSids = Doo::db()->find($obj, array('where' => "id IN ($sids)"));
        //return
        $_SESSION['notif_msg']['msg'] = 'Selected sender ID approved successfully';
        $_SESSION['notif_msg']['type'] = 'success';

        //set notification for user
        $sqry = "SELECT prefix, country FROM sc_coverage";
        $cvlist = Doo::db()->fetchAll($sqry, null, PDO::FETCH_KEY_PAIR);
        Doo::loadHelper('DooOsInfo');
        $browser = DooOsInfo::getBrowser();
        $osdata['system'] = $browser['platform'];
        $osdata['browser'] = $browser['browser'] . ' v' . $browser['version'];
        $osdata['ip'] = $_SERVER['REMOTE_ADDR'];
        $osdata['city'] = $browser['city'];
        $osdata['country'] = $browser['country'];
        $osdata['lat'] = $browser['lat'];
        $osdata['lon'] = $browser['lon'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Doo::conf()->APP_URL . 'hypernode/log/add');
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Ignore SSL host verification
        foreach ($allSids as $sdata) {
            if ($sdata->countries_matrix == "") {
                $covstr = 'All Countries and All Network Operators';
            } else {
                $cmtrx = json_decode($sdata->countries_matrix, true);
                $countries = $cmtrx['countries'];
                $operators = $cmtrx['operators'];

                $countries_str = implode(', ', array_map(function ($key) use ($cvlist) {
                    return $cvlist[$key];
                }, $countries));

                $operators_str = $operators[0] == '0' ? 'All Operators' : implode(', ', array_map(function ($str) {
                    return implode(' - ', explode('|', base64_decode($str)));
                }, $operators));
                $covstr = $countries_str . " | " . $operators_str;
            }
            $userdata = array(
                "mode" => "sender_approved",
                "data" => array(
                    "user_id" => $sdata->req_by,
                    "incidentPlatform" => $osdata,
                    "incidentDateTime" => date(Doo::conf()->date_format_db),
                    "senderId" => $sdata->sender_id,
                    "requestDate" => $sdata->req_on,
                    "allowedCountries" => $covstr
                )
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userdata));
            $res = curl_exec($ch);
            //print_r($res);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
        }
        curl_close($ch);

        echo 'DONE';
        exit;
    }

    public function rejectManySids()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        $sids = $_POST['sids'];
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;

        Doo::db()->delete($obj, array('where' => "id IN ($sids)"));

        //set notification for user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $uar = explode(",", $_POST['users']);
        foreach ($uar as $uid) {
            $alobj->addAlert($uid, 'danger', Doo::conf()->sender_id_rejected, 'manageSenderId');
        }
        //return
        $_SESSION['notif_msg']['msg'] = 'Selected sender ID rejected successfully';
        $_SESSION['notif_msg']['type'] = 'success';

        echo 'DONE';
        exit;
    }

    public function approveSid()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        $sid = intval($this->params['id']);
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->id = $sid;
        $sdata = Doo::db()->find($obj, array('limit' => 1));
        $obj->status = 1;
        Doo::db()->update($obj, array('limit' => 1));

        //return
        $_SESSION['notif_msg']['msg'] = 'Sender ID approved successfully';
        $_SESSION['notif_msg']['type'] = 'success';

        if ($sdata->countries_matrix == "") {
            $covstr = 'All Countries and All Network Operators';
        } else {
            $cmtrx = json_decode($sdata->countries_matrix, true);
            $countries = $cmtrx['countries'];
            $operators = $cmtrx['operators'];

            $sqry = "SELECT prefix, country FROM sc_coverage";
            $cvlist = Doo::db()->fetchAll($sqry, null, PDO::FETCH_KEY_PAIR);

            $countries_str = implode(', ', array_map(function ($key) use ($cvlist) {
                return $cvlist[$key];
            }, $countries));

            $operators_str = $operators[0] == '0' ? 'All Operators' : implode(', ', array_map(function ($str) {
                return implode(' - ', explode('|', base64_decode($str)));
            }, $operators));
            $covstr = $countries_str . " | " . $operators_str;
        }

        //set notification for user using hypernode
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
            "mode" => "sender_approved",
            "data" => array(
                "user_id" => $sdata->req_by,
                "incidentPlatform" => $osdata,
                "incidentDateTime" => date(Doo::conf()->date_format_db),
                "senderId" => $sdata->sender_id,
                "requestDate" => $sdata->req_on,
                "allowedCountries" => $covstr
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
        return Doo::conf()->APP_URL . 'approveSenderIds';
    }

    public function rejectSid()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        $sid = intval($this->params['id']);
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->id = $sid;
        $sdata = Doo::db()->find($obj, array('limit' => 1));

        Doo::db()->delete($obj, array('limit' => 1));
        //set notification for user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($sdata->req_by, 'danger', Doo::conf()->sender_id_rejected, 'manageSenderId');

        //return
        $_SESSION['notif_msg']['msg'] = 'Sender ID rejected successfully';
        $_SESSION['notif_msg']['type'] = 'success';

        return Doo::conf()->APP_URL . 'approveSenderIds';
    }
    public function reviewSid()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['sid']) {
            //denied
            return array('/denied', 'internal');
        }
        $sid = intval($this->params['id']);
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->id = $sid;
        $sdata = Doo::db()->find($obj, array('limit' => 1));
        $obj->status = -1;
        Doo::db()->update($obj, array('limit' => 1));

        //inform the associated user
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
            "mode" => "sender_under_review",
            "data" => array(
                "user_id" => $sdata->req_by,
                "incidentPlatform" => $osdata,
                "incidentDateTime" => date(Doo::conf()->date_format_db),
                "senderId" => $sdata->sender_id,
                "requestDate" => $sdata->req_on
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

        //return
        $_SESSION['notif_msg']['msg'] = 'Sender ID is now Under Review';
        $_SESSION['notif_msg']['type'] = 'success';

        return Doo::conf()->APP_URL . 'approveSenderIds';
    }



    //9. Approve Templates

    public function approveTemplates()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tmp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Approve Templates';

        $data['page'] = 'Administration';
        $data['current_page'] = 'approve_temps';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/approveTemps', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllPendingTemps()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tmp']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once since there cannot be huge templates
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $tids = $obj->getPendingTemplates();
        $total = count($tids);

        Doo::loadModel('ScSmsRoutes');
        $robj = new ScSmsRoutes;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($tids as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'approveTemp/' . $dt->id . '">' . $this->SCTEXT('Approve') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'rejectTemp/' . $dt->id . '">' . $this->SCTEXT('Reject') . '</a></li></ul></div>';
            if ($dt->status == 0) {
                $status_str = '<span class="label label-danger">' . $this->SCTEXT('Rejected') . '</span>';
            } elseif ($dt->status == 1) {
                $status_str = '<span class="label label-success">' . $this->SCTEXT('Approved') . '</span>';
            } elseif ($dt->status == 2) {
                $status_str = '<span class="label label-warning">' . $this->SCTEXT('Pending') . '</span>';
            }


            $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->uid . '"><img src="' . $dt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->uid . '" class="m-r-xs theme-color">' . ucwords($dt->name) . '</a><small class="text-muted fz-sm">' . ucwords($dt->category) . '</small></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $dt->email . '</p>
                                            </div>
                                        </div>';
            $rdata = $robj->getRouteData($dt->route_id, 'title');
            if ($dt->file_ids != "") {
                $cfiles = explode(",", $dt->file_ids);
                $file_str = "";
                foreach ($cfiles as $fl) {
                    $file_str .= '<div class="btn-group m-r-sm">
                    <span class="input-group-addon m-r-sm"><i class="fa fa-2x fa-file text-primary"></i></span><a class="btn btn-sm btn-info" title="' . $this->SCTEXT('View File') . '" target="_blank" href="' . Doo::conf()->APP_URL . 'viewDocument/' . $fl . '"><i class="fa fa-search fa-lg"></i></a><a class="btn btn-sm btn-success" title="' . $this->SCTEXT('Download File') . '" href="' . Doo::conf()->APP_URL . 'globalFileDownload/docmgr/' . $fl . '"><i class="fa fa-download fa-lg"></i></a> 
                    </div>';
                }
            } else {
                $file_str = "- No Files Attached -";
            }

            $output = array($user_str . '<input type="hidden" data-user="' . $dt->uid . '" class="tids" value="' . $dt->id . '"/>', '<div class="panel panel-info panel-custom">' . $dt->content . '</div>', date(Doo::conf()->date_format_long, strtotime($dt->created_on)), $rdata->title, $file_str, $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function approveManyTemps()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tmp']) {
            //denied
            return array('/denied', 'internal');
        }
        $tids = $_POST['tids'];
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->status = 1;

        Doo::db()->update($obj, array('where' => "id IN ($tids)"));
        //return
        $_SESSION['notif_msg']['msg'] = 'Selected templates approved successfully';
        $_SESSION['notif_msg']['type'] = 'success';

        //set notification for user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $uar = explode(",", $_POST['users']);
        foreach ($uar as $uid) {
            $alobj->addAlert($uid, 'success', Doo::conf()->template_approved, 'manageTemplates');
        }

        echo 'DONE';
        exit;
    }

    public function rejectManyTemps()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tmp']) {
            //denied
            return array('/denied', 'internal');
        }
        $tids = $_POST['tids'];
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->status = 0;

        Doo::db()->update($obj, array('where' => "id IN ($tids)"));
        //return
        $_SESSION['notif_msg']['msg'] = 'Selected templates rejected successfully';
        $_SESSION['notif_msg']['type'] = 'success';

        //set notification for user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $uar = explode(",", $_POST['users']);
        foreach ($uar as $uid) {
            $alobj->addAlert($uid, 'danger', Doo::conf()->template_rejected, 'manageTemplates');
        }

        echo 'DONE';
        exit;
    }

    public function approveTemp()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tmp']) {
            //denied
            return array('/denied', 'internal');
        }
        $tid = intval($this->params['id']);
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->id = $tid;
        $tdata = Doo::db()->find($obj, array('limit' => 1));

        $obj->status = 1;
        Doo::db()->update($obj, array('limit' => 1));
        $_SESSION['notif_msg']['msg'] = 'SMS template approved for requested route.';
        $_SESSION['notif_msg']['type'] = 'success';

        //notify user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($tdata->user_id, 'success', Doo::conf()->template_approved, 'manageTemplates');

        //return
        return Doo::conf()->APP_URL . 'approveTemplates';
    }

    public function rejectTemp()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tmp']) {
            //denied
            return array('/denied', 'internal');
        }
        $tid = intval($this->params['id']);
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->id = $tid;
        $tdata = Doo::db()->find($obj, array('limit' => 1));

        $obj->status = 0;
        Doo::db()->update($obj, array('limit' => 1));
        $_SESSION['notif_msg']['msg'] = 'SMS template rejected for requested route.';
        $_SESSION['notif_msg']['type'] = 'success';

        //notify user
        $alobj = Doo::loadModel('ScUserNotifications', true);
        $alobj->addAlert($tdata->user_id, 'danger', Doo::conf()->template_rejected, 'manageTemplates');

        //return
        return Doo::conf()->APP_URL . 'approveTemplates';
    }


    //10. Manage Refund Rules

    public function refundRules()
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
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Refund Rules';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_rrules';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageRefRules', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getRefundRules()
    {
        Doo::loadModel('ScDlrRefundRules');
        $obj = new ScDlrRefundRules;
        $rules = Doo::db()->find($obj);
        $total = count($rules);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($rules as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editRefundRule/' . $dt->id . '">' . $this->SCTEXT('Edit Rule Name') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'delRefundRule/' . $dt->id . '">' . $this->SCTEXT('Delete Rule') . '</a></li></ul></div>';

            $output = array($dt->title, '<div class="panel panel-info panel-custom">' . $dt->description . '</div>', $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addRefundRule()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Refund Rules'] = Doo::conf()->APP_URL . 'refundRules';
        $data['active_page'] = 'Add Refund Rule';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_rrule';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addRefRule', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveRefundRule()
    {
        $rname = $_POST['rname'];
        $rdesc = $_POST['rdesc'];

        Doo::loadModel('ScDlrRefundRules');
        $obj = new ScDlrRefundRules;
        $obj->title = $rname;
        $obj->description = $rdesc;

        if (intval($_POST['rule_id']) > 0) {
            $obj->id = intval($_POST['rule_id']);
            Doo::db()->update($obj, array('limit' => 1));
            $_SESSION['notif_msg']['msg'] = 'Refund Rule successfully Edited';
            $_SESSION['notif_msg']['type'] = 'success';
        } else {
            Doo::db()->insert($obj);
            $_SESSION['notif_msg']['msg'] = 'Refund Rule successfully Added';
            $_SESSION['notif_msg']['type'] = 'success';
        }

        return Doo::conf()->APP_URL . 'refundRules';
    }

    public function editRefundRule()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage Refund Rules'] = Doo::conf()->APP_URL . 'refundRules';
        $data['active_page'] = 'Edit Refund Rule';

        Doo::loadModel('ScDlrRefundRules');
        $obj = new ScDlrRefundRules;
        $obj->id = intval($this->params['id']);
        $data['rdata'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_rrule';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editRefRule', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function delRefundRule()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScDlrRefundRules');
        $obj = new ScDlrRefundRules;
        if ($this->params['id'] != '') {
            $obj->id = $this->params['id'];
            Doo::db()->delete($obj, array('limit' => 1));
        }
        $_SESSION['notif_msg']['msg'] = 'Refund Rule deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'refundRules';
    }


    //11. Kannel Monitor

    public function kannelMonitor()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['kannel']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Kannel Monitor';

        //check if kannel is running
        if (!($fp = fopen('http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/status.xml?password=' . Doo::conf()->status_password, "r"))) {
            //kannel is down
            $status = 'offline';
        } else {
            $status = 'online';
        }

        $data['kannel_config'] = array(
            array(
                "base_url" => "http://" . Doo::conf()->bearerbox_host . ":" . Doo::conf()->admin_port,
                "status_passwd" => Doo::conf()->status_password,
                "admin_passwd" => Doo::conf()->admin_password,
                "name" => "Master Kannel"
            )
        );
        //get all smpp to display name in the monitor
        $sqry = "SELECT smsc_id,title FROM sc_smpp_accounts";
        $data["smpplist"] = Doo::db()->fetchAll($sqry, null, PDO::FETCH_KEY_PAIR);


        $data['page'] = 'Administration';
        $data['current_page'] = 'kannel_mon';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/xmlfunc', $data);
        $this->view()->renderc('admin/xmltoarray', $data);
        $this->view()->renderc('admin/KannelMon', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function kannelActions()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['kannel']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $action = $_POST['action'];
        $pass = trim($_POST['kpass']);
        //auth
        if ($pass != Doo::conf()->admin_password) {
            //failed
            $_SESSION['notif_msg']['msg'] = 'Kannel Authentication Failed. Unable to perform task.';
            $_SESSION['notif_msg']['type'] = 'error';
            echo 'failed';
            exit;
        }
        //check action
        if ($action == 'shutdown') {
            $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/shutdown?password=' . Doo::conf()->admin_password;
            file_get_contents($url);
            $_SESSION['notif_msg']['msg'] = 'Kannel stopped successfully';
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }
        if ($action == 'restart') {
            $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/graceful-restart?password=' . Doo::conf()->admin_password;
            file_get_contents($url);
            $_SESSION['notif_msg']['msg'] = 'Kannel restarted successfully';
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }
        if ($action == 'flushdlr') {
            $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/flushdlr?password=' . Doo::conf()->admin_password;
            file_get_contents($url);
            $_SESSION['notif_msg']['msg'] = 'Queued DLR removed successfully';
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }


        if ($action == 'stop-smsc') {
            $smsc = $_POST['smsc'];
            //--
            $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/stop-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smsc;
            file_get_contents($url);
            $_SESSION['notif_msg']['msg'] = $smsc . ': ' . $this->SCTEXT('SMSC stopped successfully');
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }
        if ($action == 'start-smsc') {
            $smsc = $_POST['smsc'];
            //--
            $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/start-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smsc;
            file_get_contents($url);
            $_SESSION['notif_msg']['msg'] = $smsc . ': ' . $this->SCTEXT('SMSC started successfully');
            $_SESSION['notif_msg']['type'] = 'success';
            exit;
        }
        if ($action == 'editConf') {
            //read conf file
            $confData = file_get_contents(Doo::conf()->kannel_conf_path);
            echo nl2br($confData);
            exit;
        }
        if ($action == 'saveConf') {
            //save conf file
            $confData = $_POST['confData'];
            $confData = preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n", "", str_replace("\r", "", htmlspecialchars_decode($confData))));
            //write in the file
            $my_file = Doo::conf()->kannel_conf_path;
            $handle = @fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file . '<br>Check file path and permissions.');
            @fwrite($handle, $confData);
            fclose($handle);
            $_SESSION['notif_msg']['msg'] = 'Kannel Conf modified successfully';
            $_SESSION['notif_msg']['type'] = 'success';
        }
    }



    //12. Announcements

    public function announcements()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Announcements';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_annc';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageAnnc', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAnnouncements()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScAnnouncements');
        $obj = new ScAnnouncements;
        $anns = Doo::db()->find($obj);
        $total = count($anns);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($anns as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editAnnouncement/' . $dt->id . '">' . $this->SCTEXT('Edit Announcement') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'deleteAnnouncement/' . $dt->id . '">' . $this->SCTEXT('Delete Announcement') . '</a></li></ul></div>';
            if ($dt->show_to == 1) {
                $showto = $this->SCTEXT('All Users');
            } elseif ($dt->show_to == 2) {
                $showto = $this->SCTEXT('Only Resellers');
            } elseif ($dt->show_to == 3) {
                $showto = $this->SCTEXT('Only Clients');
            }

            $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="switchid-' . $dt->id . '" class="togstatus myswitch" type="checkbox" value="' . $dt->id . '" data-dtswitch="true" data-color="#10c469"';
            if ($dt->status == 1) {
                $status_str .= " checked";
            }
            $status_str .= '></div>';

            if ($dt->type == 1) {
                $msg = '<div class="panel panel-success panel-custom">' . $dt->msg . '</div>';
            } elseif ($dt->type == 2) {
                $msg = '<div class="panel panel-info panel-custom">' . $dt->msg . '</div>';
            } elseif ($dt->type == 3) {
                $msg = '<div class="panel panel-danger panel-custom">' . $dt->msg . '</div>';
            }


            $output = array($msg, $showto, $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function setAnnouncementState()
    {

        session_start();
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        //get values
        $aid = intval($_POST['aid']);
        $val = intval($_POST['value']);
        if ($aid != 0) {
            Doo::loadModel('ScAnnouncements');
            $obj = new ScAnnouncements;
            $obj->id = $aid;
            $obj->status = $val;
            Doo::db()->update($obj);
        }
        exit;
    }

    public function addAnnouncement()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        $data['links']['Manage Announcements'] = Doo::conf()->APP_URL . 'announcements';
        $data['active_page'] = 'Add Announcement';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_annc';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addAnnc', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editAnnouncement()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        $data['links']['Manage Announcements'] = Doo::conf()->APP_URL . 'announcements';
        $data['active_page'] = 'Edit Announcement';

        $aid = intval($this->params['id']);
        if ($aid == 0) {
            return array('/denied', 'internal');
        }

        //get data
        Doo::loadModel('ScAnnouncements');
        $obj = new ScAnnouncements;
        $obj->id = $aid;
        $data['adata'] = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id,msg,type,show_to'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_annc';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editAnnc', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveAnnouncement()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $aid = intval($_POST['aid']);
        $atxt = htmlspecialchars($_POST['dtxt'], ENT_QUOTES);
        $afor = $_POST['dfor'];
        $atype = $_POST['stype'];

        Doo::loadModel('ScAnnouncements');
        $obj = new ScAnnouncements;
        $obj->msg = $atxt;
        $obj->type = intval($atype);
        $obj->show_to = intval($afor);

        //save data
        if ($aid == 0) {
            //insert
            $obj->status = 1;
            Doo::db()->insert($obj);
            $msg = 'Announcement Added Successfully';
        } else {
            //update
            $obj->id = $aid;
            Doo::db()->update($obj, array('limit' => 1));
            $msg = 'Announcement Edited Successfully';
        }
        //set notif
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        //return
        return Doo::conf()->APP_URL . 'announcements';
    }

    public function deleteAnnouncement()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['announce']) {
            //denied
            return array('/denied', 'internal');
        }
        $aid = intval($this->params['id']);
        if ($aid == 0) {
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScAnnouncements');
        $obj = new ScAnnouncements;
        $obj->id = $aid;
        Doo::db()->delete($obj);
        //set notif
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Announcement deleted successfully';
        //return
        return Doo::conf()->APP_URL . 'announcements';
    }


    // 13. Staff Management
    public function manageStaffTeams()
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
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Teams';

        $data['page'] = 'Staff-Admin';
        $data['current_page'] = 'manage_teams';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageTeams', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllStaffTeams()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScStaffTeams');
        $obj = new ScStaffTeams;
        $teams = Doo::db()->find($obj);
        $total = count($teams);

        Doo::loadModel('ScStaffRights');
        $sobj = new ScStaffRights;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($teams as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editStaffTeam/' . $dt->id . '">' . $this->SCTEXT('Edit Team') . '</a></li><li><a href="javascript:void(0);" class="del_team" data-tid="' . $dt->id . '">' . $this->SCTEXT('Delete Team') . '</a></li></ul></div>';

            $scount = Doo::db()->find($sobj, array('select' => 'count(id) as total', 'limit' => 1, 'where' => 'team_id=' . $dt->id))->total;
            $desc = '<div class="panel panel-' . $dt->theme . ' panel-custom">' . $dt->description . '</div>';


            $output = array($dt->name, $desc, '<span class="label label-' . $dt->theme . ' label-md">' . $scount . ' ' . $this->SCTEXT('staff members'), $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addStaffTeam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        $data['links']['Manage Teams'] = Doo::conf()->APP_URL . 'manageStaffTeams';
        $data['active_page'] = 'Add New Team';

        $data['page'] = 'Staff-Admin';
        $data['current_page'] = 'add_team';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addTeam', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editStaffTeam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        $data['links']['Manage Teams'] = Doo::conf()->APP_URL . 'manageStaffTeams';
        $data['active_page'] = 'Edit Team';

        $tid = intval($this->params['id']);
        if ($tid == 0) {
            return array('/denied', 'internal');
        }

        //get data
        Doo::loadModel('ScStaffTeams');
        $obj = new ScStaffTeams;
        $obj->id = $tid;
        $data['tdata'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = 'Staff-Admin';
        $data['current_page'] = 'edit_team';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editTeam', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveStaffTeam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $tname = $_POST['tname'];
        $tdesc = $_POST['tdesc'];
        $theme = $_POST['theme'];
        $rights = json_encode($_POST['perms']);
        //save values
        Doo::loadModel('ScStaffTeams');
        $obj = new ScStaffTeams;
        $obj->name = $tname;
        $obj->description = $tdesc;
        $obj->theme = $theme;
        $obj->rights = $rights;

        if (intval($_POST['team_id']) != 0) {
            $obj->id = intval($_POST['team_id']);
            Doo::db()->update($obj, array('limit' => 1));
            $msg = 'Team edited successfully';
            //update permissons to all staff who belong to this team
            $qry = "UPDATE sc_staff_rights SET rights = ? WHERE team_id = ?";
            Doo::db()->query($qry, [$rights, intval($_POST['team_id'])]);
        } else {
            Doo::db()->insert($obj);
            $msg = 'New team added successfully';
        }
        //reassign permissions for all the staff in this team
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageStaffTeams';
    }

    public function delStaffTeam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScStaffTeams');
        $obj = new ScStaffTeams;
        $obj->id = $tid;
        Doo::db()->delete($obj);
        //set notif
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Team deleted successfully';
        //return
        return Doo::conf()->APP_URL . 'manageStaffTeams';
    }

    public function manageStaff()
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
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Staff Members';

        $data['page'] = 'Staff-Admin';
        $data['current_page'] = 'manage_staff';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageStaff', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllStaff()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScUsers');
        $obj = new ScUsers;
        $obj->subgroup = 'staff';
        $staff = Doo::db()->find($obj);
        $total = count($staff);

        Doo::loadModel('ScStaffTeams');
        $tobj = new ScStaffTeams;

        Doo::loadModel('ScStaffRights');
        $sobj = new ScStaffRights;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($staff as $dt) {

            $ustr = '<div class="media-group-item">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewStaff/' . $dt->user_id . '"><img src="' . $dt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewStaff/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($dt->name) . '</a></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $dt->email . '</p>
                                            </div>
                                        </div>

                                    </div>';

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewStaff/' . $dt->user_id . '">' . $this->SCTEXT('View Staff Details') . '</a></li><li><a href="javascript:void(0);" class="del_staff" data-uid="' . $dt->user_id . '">' . $this->SCTEXT('Delete Staff Member') . '</a></li></ul></div>';

            $sobj->staff_uid = $dt->user_id;
            $team_id = Doo::db()->find($sobj, array('limit' => 1, 'select' => 'team_id'))->team_id;
            $tobj->id = $team_id;
            $team = Doo::db()->find($tobj, array('limit' => 1));

            $tstr = '<span class="label label-md label-' . $team->theme . '">' . $team->name . '</span>';

            $output = array($ustr, $dt->login_id, $dt->mobile, $tstr, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addStaff()
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
        $data['links']['Manage Staff'] = Doo::conf()->APP_URL . 'manageStaff';
        $data['active_page'] = 'Add New Staff Member';

        //teams
        Doo::loadModel('ScStaffTeams');
        $tobj = new ScStaffTeams;
        $data['tdata'] = Doo::db()->find($tobj);

        $data['page'] = 'Staff-Admin';
        $data['current_page'] = 'add_staff';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addStaff', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveStaff()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //Doo::loadHelper('DooTextHelper');
        //collect values
        $teamid = intval($_POST['team']);
        $name = DooTextHelper::cleanInput($_POST['sname']);
        $loginid = DooTextHelper::cleanInput($_POST['slogin']);
        $email = $_POST['semail'];
        $phn = intval($_POST['sphn']);
        $pass = $_POST['spass'];
        $pass2 = $_POST['spass2'];
        //validate
        if (strlen($loginid) < 5) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Login ID. Must be at least 5 characters long.';
            return Doo::conf()->APP_URL . 'addStaff';
        }
        if (!DooTextHelper::verifyFormData('email', $email)) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid email address.';
            return Doo::conf()->APP_URL . 'addStaff';
        }
        if (!DooTextHelper::verifyFormData('mobile', $phn)) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid phone number.';
            return Doo::conf()->APP_URL . 'addStaff';
        }
        if (!DooTextHelper::verifyFormData('password', $pass) || $pass != $pass2) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Password. Please see the instructions for password and make sure both passwords match.';
            return Doo::conf()->APP_URL . 'addStaff';
        }

        //get rights
        Doo::loadModel('ScStaffTeams');
        $tobj = new ScStaffTeams;
        $tobj->id = $teamid;
        $rights = Doo::db()->find($tobj, array('limit' => 1, 'select' => 'rights'))->rights;

        //add user
        $hfunck = base64_encode($loginid . '_' . base64_encode('smppcubehash'));
        Doo::loadHelper('DooEncrypt');
        $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $encpass = $encobj->encrypt($pass, $hfunck);

        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $uobj->login_id = $loginid;
        $uobj->password = $encpass;
        $uobj->name = $name;
        $uobj->gender = $_POST['gender'];
        $uobj->avatar = $_POST['gender'] == 'm' ? Doo::conf()->default_avatar_male_staff : Doo::conf()->default_avatar_female_staff;
        $uobj->category = 'admin';
        $uobj->subgroup = 'staff';
        $uobj->mobile = $phn;
        $uobj->email = $email;
        $uobj->upline_id = $_SESSION['user']['userid'];
        $uobj->status = 1;
        $uobj->registered_on = date(Doo::conf()->date_format_db);

        $uid = Doo::db()->insert($uobj);

        //staff rights
        Doo::loadModel('ScStaffRights');
        $sobj = new ScStaffRights;
        $sobj->staff_uid = $uid;
        $sobj->team_id = $teamid;
        $sobj->rights = $rights;

        Doo::db()->insert($sobj);

        //send notification email n sms

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'New staff member added successfully.';
        return Doo::conf()->APP_URL . 'manageStaff';
    }

    public function viewStaff()
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
        $data['links']['Manage Staff'] = Doo::conf()->APP_URL . 'manageStaff';
        $data['active_page'] = 'View Staff Member';

        //teams
        Doo::loadModel('ScStaffTeams');
        $tobj = new ScStaffTeams;
        $data['tdata'] = Doo::db()->find($tobj);

        //staff user details
        //validate id
        $sid = intval($this->params['id']);
        if ($sid > 0) {
            //valid id
            Doo::loadModel('ScUsers');
            $uobj = new ScUsers;
            $uobj->user_id = $sid;
            $opt['limit'] = 1;
            $opt['where'] = "subgroup='staff'";
            $opt['select'] = 'user_id,login_id,name,email,avatar,mobile,registered_on,last_activity';
            $udata = Doo::db()->find($uobj, $opt);
            if ($udata->user_id) {
                //record found
                $data['udata'] = $udata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageStaff';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }

        //staff rights
        Doo::loadModel('ScStaffRights');
        $robj = new ScStaffRights;
        $robj->staff_uid = $sid;
        $data['rdata'] = Doo::db()->find($robj, array('limit' => 1, 'select' => 'rights, team_id'));

        //last activity
        $la = new DateTime($data['udata']->last_activity);
        $ct = new DateTime(date(Doo::conf()->date_format_db));
        $interval = $la->diff($ct);
        $data['last-act'] = DooTextHelper::format_interval($interval);

        $data['page'] = 'Staff-Admin';
        $data['current_page'] = 'view_staff';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/viewStaff', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function delStaff()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //collect value
        $uid = intval($this->params['id']);
        //validate id
        if ($uid == 0) {
            $_SESSION['notif_msg']['msg'] = 'No records found';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manageStaff';
        }

        //delete user
        Doo::loadModel('ScUsers');
        $uobj = new ScUsers;
        $uobj->user_id = $uid;
        Doo::db()->delete($uobj, array('limit' => 1));

        //delete rights
        Doo::loadModel('ScStaffRights');
        $robj = new ScStaffRights;
        $robj->staff_uid = $uid;
        Doo::db()->delete($robj, array('limit' => 1));

        //return
        $_SESSION['notif_msg']['msg'] = 'Staff member deleted successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageStaff';
    }

    public function changeTeam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $uid = intval($_POST['user']);
        $tid = intval($_POST['team']);
        if ($uid == 0 || $tid == 0) {
            $_SESSION['notif_msg']['msg'] = 'Invalid Operation';
            $_SESSION['notif_msg']['type'] = 'error';
            exit;
        }
        //validate action
        if ($_SESSION['user']['group'] == 'admin') {
            //get team data
            Doo::loadModel('ScStaffTeams');
            $tobj = new ScStaffTeams;
            $tobj->id = $tid;
            $team = Doo::db()->find($tobj, array('limit' => 1, 'select' => 'rights'));
            //change values
            Doo::loadModel('ScStaffRights');
            $robj = new ScStaffRights;
            $robj->rights = $team->rights;
            $robj->team_id = $tid;
            $opt['where'] = 'staff_uid=' . $uid;
            $opt['limit'] = 1;
            Doo::db()->update($robj, $opt);
        } else {
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            $_SESSION['notif_msg']['type'] = 'error';
            exit;
        }

        //return
        $_SESSION['notif_msg']['msg'] = 'Team switched successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        exit;
    }

    public function switchStaff()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['subgroup'] == 'admin') {
            $user = intval($_POST['user']);
            $newstaff = intval($_POST['staff']);

            if ($user == 0 || $newstaff == 0) {
                //error
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid Request';
                exit;
            }
            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->user_id = $user;
            $uobj->acc_mgr_id = $newstaff;
            Doo::db()->update($uobj);

            //alert user
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert($user, 'info', Doo::conf()->staff_switch_alert, 'supportTickets');

            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Account manager switched successfully.';
            exit;
        } else {
            //not allowed
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed.';
            exit;
        }
    }


    //14. System Monitor

    //moved to resellerController
    public function getSysmonData()
    {
        //set the date
        $dates = urldecode($this->params['daterange']);
        $datar = explode(" - ", $dates);
        $from = date('Y-m-d', strtotime(trim($datar[0])));
        $to = date('Y-m-d', strtotime(trim($datar[1])));
        if ($from == $to) {
            $sWhere = "day = '$from'";
        } else {
            $sWhere = "day BETWEEN '$from' AND '$to'";
        }
        $lgobj = Doo::loadModel('ScLogsSystemMonitor', true);
        $logs = Doo::db()->find($lgobj, array('where' => $sWhere));

        //prepare json data for the chart
        $res = ["dates" => [], "smpp_totals" => [], "smpp_peaks" => [], "api_totals" => [], "api_peaks" => []];
        foreach ($logs as $dt) {
            if (!in_array($dt->day, $res['dates'])) {
                array_push($res['dates'], $dt->day);
            }
            if ($dt->channel == "smpp") {
                array_push($res['smpp_totals'], $dt->total);
                array_push($res['smpp_peaks'], $dt->peak_rate);
            } else {
                array_push($res['api_totals'], $dt->total);
                array_push($res['api_peaks'], $dt->peak_rate);
            }
        }

        echo json_encode($res);
    }


    //15. User Management: Admin Stuff
    public function saveUserPermissions()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $uid = intval($_POST['uid']);
        $pgid = intval($_POST['pgid']);

        $utobj = Doo::loadHelper('DooSmppcubeHelper', true);
        $usrar = $utobj->getUserTree($uid);
        $usrstr = implode(",", $usrar);

        if ($uid == 0) {
            $_SESSION['notif_msg']['msg'] = 'Invalid Operation';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        if ($_SESSION['user']['group'] == 'admin') {
            //change values
            Doo::loadModel('ScUsersPermissions');
            $upobj = new ScUsersPermissions;
            $upobj->user_id = $uid;
            $updata = Doo::db()->find($upobj, array('select' => 'id', 'limit' => 1));

            //get permission data for the assigned permission group
            $pgobj = Doo::loadModel('ScPermissionGroups', true);
            $pgobj->id = $pgid;
            $perms = Doo::db()->find($pgobj, array('select' => 'permissions', 'limit' => 1))->permissions;

            if ($updata->id) {
                //recursively make changes in the downline
                $upobj2 = new ScUsersPermissions;
                $upobj2->id = $updata->id;
                $upobj2->pg_id = $pgid;
                $upobj2->perm_data = $perms;
                Doo::db()->update($upobj2, array('where' => "user_id IN($usrstr)"));
            } else {
                $upobj->user_id = $uid;
                $upobj->pg_id = $pgid;
                $upobj->perm_data = $perms;
                Doo::db()->insert($upobj);
            }
        } else {
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
        }
        //return
        $_SESSION['notif_msg']['msg'] = 'User account permissions modified successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
    }

    public function saveUserSpecialFlags()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $uid = intval($_POST['uid']);
        $sflag = $_POST['spamflag'] == 'on' ? 1 : 0;
        $tflag = $_POST['tempflag'] == 'on' ? 1 : 0;
        $pflag = $_POST['panel_campaign_perm'] == 'on' ? 1 : 0;
        $def_tax = $_POST['dtax'];
        $def_tax_type = $_POST['dtaxtype'];
        //echo serialize(array('tax'=>floatval($def_tax),'type'=>$def_tax_type)); die;

        if ($uid == 0) {
            $_SESSION['notif_msg']['msg'] = 'Invalid Operation';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        if ($_SESSION['user']['group'] == 'admin') {
            //change values
            $uobj = Doo::loadModel('ScUsers', true);
            $uobj->user_id = $uid;
            $uobj->spam_status = $sflag;
            $uobj->opentemp_flag = $tflag;
            $uobj->panel_campaign_perm = $pflag;
            $uobj->payment_perm = intval($_POST['upay']);
            $uobj->default_tax = serialize(array('tax' => floatval($def_tax), 'type' => $def_tax_type));
            Doo::db()->update($uobj, array('limit' => 1));
            $msg = 'User account permissions modified successfully';
            //save custom labels or default values for TLV
            $ctlvobj = Doo::loadModel('ScUsersTlvDefaults', true);
            $ctlvobj->user_id = $uid;
            Doo::db()->delete($ctlvobj); //clean old settings
            if ($_POST['cus_tlv_flag'] == 'on') {
                $insertqry = "INSERT INTO sc_users_tlv_defaults (`user_id`, `tlv_category`, `custom_label`, `default_value`) VALUES ";
                foreach ($_POST['customlbl'] as $tlvtype => $label) {
                    if ($_POST['customval'][$tlvtype] != '' && $label != '') {
                        $insertqry .= "(";
                        $insertqry .= $uid . ",";
                        $insertqry .= "'" . $tlvtype . "',";
                        $insertqry .= "'" . $label . "',";
                        $insertqry .= "'" . $_POST['customval'][$tlvtype] . "'),";
                    }
                }
                $insertqry = substr($insertqry, 0, strlen($insertqry) - 1);
                $rs = Doo::db()->query($insertqry);
                $msg = 'Custom TLV labels updated successfully';
            }
        } else {
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
        }
        //return
        $_SESSION['notif_msg']['msg'] = $msg;
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
    }

    public function saveUserWhitelist()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $uid = intval($_POST['uid']);
        $wcon = $_POST['wcontacts'];

        if ($uid == 0) {
            $_SESSION['notif_msg']['msg'] = 'Invalid Operation';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        if ($_SESSION['user']['group'] == 'admin') {
            //change values
            $uconobj = Doo::loadModel('ScUsersWhitelist', true);
            $uconobj->user_id = $uid;
            $urs = Doo::db()->find($uconobj, array('limit' => 1));
            if ($urs->id) {
                //update
                $uconobj->id = $urs->id;
                $uconobj->mobiles = $wcon;
                Doo::db()->update($uconobj, array('limit' => 1));
            } else {
                //insert
                $uconobj->mobiles = $wcon;
                Doo::db()->insert($uconobj);
            }
        } else {
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
        }
        //return
        $_SESSION['notif_msg']['msg'] = 'Whitelist contacts modified successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
    }

    public function saveUserPhonebookPermissions()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $uid = intval($_POST['uid']);
        $contact_groups = $_POST["groups"];
        $ctflag = $_POST['click_track'];
        $mask_pattern = $_POST['mask_pattern'] == 1 ? serialize(array('type' => 1, 'mpos' => -5, 'mlen' => 4)) : serialize(array('type' => 0, 'mpos' => $_POST['maskstart'], 'mlen' => $_POST['masklen']));

        if ($uid == 0) {
            $_SESSION['notif_msg']['msg'] = 'Invalid Operation';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'manageUsers';
        }
        if ($_SESSION['user']['group'] == 'admin') {
            //change values
            $uobj = Doo::loadModel('ScUsersPhonebookSettings', true);
            $uobj->user_id = $uid;
            $rs = Doo::db()->find($uobj, array('limit' => 1));
            if ($rs->id) {
                $uobj->id = $rs->id;
                $uobj->phonebook_ids = is_array($contact_groups) ? implode(",", $contact_groups) : "";
                $uobj->click_track = $ctflag;
                $uobj->mask_pattern = $mask_pattern;
                Doo::db()->update($uobj, array('limit' => 1));
            } else {
                $uobj->phonebook_ids = is_array($contact_groups) ? implode(",", $contact_groups) : "";
                $uobj->click_track = $ctflag;
                $uobj->mask_pattern = $mask_pattern;
                Doo::db()->insert($uobj);
            }

            //get this users downline and propogate changes to all users in downline
            $utobj = Doo::loadHelper('DooSmppcubeHelper', true);
            $usrar = $utobj->getUserTree($uid);
            if (sizeof($usrar) > 0) {
                foreach ($usrar as $duid) {
                    $uobj->user_id = $duid;
                    $rs = Doo::db()->find($uobj, array('limit' => 1));
                    if ($rs->id) {
                        $uobj->id = $rs->id;
                        $uobj->phonebook_ids = is_array($contact_groups) ? implode(",", $contact_groups) : "";
                        $uobj->click_track = $ctflag;
                        $uobj->mask_pattern = $mask_pattern;
                        Doo::db()->update($uobj, array('limit' => 1));
                    } else {
                        $uobj->phonebook_ids = is_array($contact_groups) ? implode(",", $contact_groups) : "";
                        $uobj->click_track = $ctflag;
                        $uobj->mask_pattern = $mask_pattern;
                        Doo::db()->insert($uobj);
                    }
                }
            }
        } else {
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
        }
        //return
        $_SESSION['notif_msg']['msg'] = 'User account permissions modified successfully';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $uid;
    }

    public function saveUserVmnSettings()
    {
        $vobj = Doo::loadModel('ScVmnList', true);
        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);

        //clear settings for this user
        $vobj->revokeAllVmn($_POST['uid']);
        $kobj->revokeAllKeywords($_POST['uid']);

        //collect values
        $vids = is_countable($_POST['usrvmn']) ? implode(",", $_POST['usrvmn']) : '';
        $kids = is_countable($_POST['usrkws']) ?  implode(",", $_POST['usrkws']) : '';
        if (strlen($vids) > 0) {
            //assign vmn
            $vobj->user_assigned = intval($_POST['uid']);
            Doo::db()->update($vobj, array('where' => "id IN ($vids)"));
            //assign all keywords of the assigned vmns
            $kobj->user_assigned = intval($_POST['uid']);
            Doo::db()->update($kobj, array('where' => "vmn IN ($vids)"));
        }

        if (strlen($kids) > 0) {
            //assign selected keywords
            $kobj->user_assigned = intval($_POST['uid']);
            Doo::db()->update($kobj, array('where' => "id IN ($kids)"));
        }

        //return
        $_SESSION['notif_msg']['msg'] = '2-WAY settings saved successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . intval($_POST['uid']);
    }

    public function getSmppClients()
    {
        $uobj = Doo::loadModel('ScUsers', true);
        $udata = $uobj->getProfileInfo($this->params['uid'], 'account_type');

        $obj = Doo::loadModel('ScSmppClients', true);
        $obj->user_id = $this->params['uid'];
        $clients = Doo::db()->find($obj);
        $total = count($clients);

        $robj = Doo::loadModel('ScSmsRoutes', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($clients as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editSmppClient/' . $this->params['uid'] . '/' . $dt->id . '">' . $this->SCTEXT('Edit') . '</a></li><li><a class="delsmppclient" data-aid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete') . '</a></li></ul></div>';

            $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="switchid-' . $dt->id . '" data-sid="' . $dt->id . '" class="togstatus myswitch" type="checkbox" value="0" data-dtswitch="true" data-color="#10c469"';
            if ($dt->status == 1) {
                $status_str .= " checked";
            }
            $status_str .= '></div>';

            if ($dt->route_id != 0) $route = $robj->getRouteData($dt->route_id, 'title');
            if ($udata->account_type == 0 || $udata->account_type == 2) {
                $output = array($dt->system_id, Doo::conf()->show_password == 1 ? DooSmppcubeHelper::aesDecrypt($dt->smpp_password) : '******', '<kbd>' . str_replace(',', "<br>", $dt->allowed_ip) . '</kbd>', $route->title, $status_str, $button_str);
            } else {
                $output = array($dt->system_id, Doo::conf()->show_password == 1 ? DooSmppcubeHelper::aesDecrypt($dt->smpp_password) : '******', '<kbd>' . str_replace(',', "<br>", $dt->allowed_ip) . '</kbd>', $status_str, $button_str);
            }

            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addSmppClient()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['links']['User Account Settings'] = Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $this->params['uid'];
        $data['active_page'] = 'Add SMPP Account';

        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($this->params['uid']);

        //get routes for this user
        $cobj = Doo::loadModel('ScUsersCreditData', true);
        $cobj->user_id = $this->params['uid'];
        $cobj->status = 0;
        $data['cdata'] = Doo::db()->find($cobj);

        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        foreach ($data['cdata'] as $rt) {
            $rt->title = $rtobj->getRouteData($rt->route_id)->title;
        }

        //get assigned VMNs
        $vmnobj = Doo::loadModel('ScVmnList', true);
        $vmnobj->user_assigned = $this->params['uid'];
        $data['vmns'] = Doo::db()->find($vmnobj);

        $data['page'] = 'User Management';
        $data['current_page'] = 'add_smppclient';
        $data['page_family'] = 'view_account';
        $data['role'] = $_SESSION['user']['group'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addSmppClient', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editSmppClient()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Users'] = Doo::conf()->APP_URL . 'manageUsers';
        $data['links']['User Account Settings'] = Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $this->params['uid'];
        $data['active_page'] = 'Edit SMPP Account';

        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['user'] = $uobj->getProfileInfo($this->params['uid']);

        //get routes for this user
        $cobj = Doo::loadModel('ScUsersCreditData', true);
        $cobj->user_id = $this->params['uid'];
        $cobj->status = 0;
        $data['cdata'] = Doo::db()->find($cobj);

        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        foreach ($data['cdata'] as $rt) {
            $rt->title = $rtobj->getRouteData($rt->route_id)->title;
        }

        //get assigned VMNs
        $vmnobj = Doo::loadModel('ScVmnList', true);
        $vmnobj->user_assigned = $this->params['uid'];
        $data['vmns'] = Doo::db()->find($vmnobj);

        //get smpp client details
        $sobj = Doo::loadModel('ScSmppClients', true);
        $sobj->id = $this->params['id'];
        $data['smpp'] = Doo::db()->find($sobj, array('limit' => 1));
        $data['smpppass'] = DooSmppcubeHelper::aesDecrypt($data['smpp']->smpp_password);

        $data['page'] = 'User Management';
        $data['current_page'] = 'add_smppclient';
        $data['page_family'] = 'view_account';
        $data['role'] = $_SESSION['user']['group'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editSmppClient', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function toggleSmppClientStatus()
    {
        $id = intval($_POST['id']);
        if ($id == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        $status = intval($_POST['status']);
        $obj = Doo::loadModel('ScSmppClients', true);
        $obj->id = $id;
        $obj->status = $status;
        Doo::db()->update($obj, array('limit' => 1));
        echo $status == 1 ? $this->SCTEXT('This SMPP account is now ACTIVE. Status changed successfully') : $this->SCTEXT('This SMPP account has been shutdown. Status changed successfully.');
        exit;
    }

    public function saveSmppClient()
    {
        //collect values
        $routeid = intval($_POST['route']);
        $userid = intval($_POST['userid']);
        $systemid = $_POST['systemid'];
        $password = $_POST['smpp_pass'];
        $iplist = $_POST['allowed_ip'];
        $tx = intval($_POST['tx']);
        $rx = intval($_POST['rx']);
        $trx = intval($_POST['trx']);
        $vmn = intval($_POST['vmn']);
        //validate
        if ($systemid == '' || strlen($systemid) < 5) {
            $_SESSION['notif_msg']['msg'] = 'Invalid System ID. Must be at least 5 characters without spaces.';
            $_SESSION['notif_msg']['type'] = 'error';
            return Doo::conf()->APP_URL . 'addSmppClient/' . $userid;
        }
        $uobj = Doo::loadModel('ScUsers', true);
        $udata = $uobj->getProfileInfo($userid);
        $upline = $udata->upline_id;

        if ($udata->account_type == '1') {
            $plobj = Doo::loadModel('ScUsersSmsPlans', true);
            $plobj->user_id = $userid;
            $planid = Doo::db()->find($plobj, array('limit' => 1))->plan_id;
        } else {
            $planid = 0;
        }
        if ($routeid == 0) {
            //get route from the plan
            $plnobj = Doo::loadModel('ScMccMncPlans', true);
            $plnobj->id = $planid;
            $plan = Doo::db()->find($plnobj, array('limit' => 1));
            $routeid = $plan->route_id;
        }

        //save changes
        $obj = Doo::loadModel('ScSmppClients', true);
        if (intval($_POST['scid']) == 0) {
            //insert
            $obj->user_id = $userid;
            $obj->upline_id = $upline;
            $obj->system_id = $systemid;
            $obj->smpp_password = DooSmppcubeHelper::aesEncrypt($password);
            $obj->route_id = $routeid;
            $obj->allowed_ip = $iplist;
            $obj->tx_max = $tx;
            $obj->rx_max = $rx;
            $obj->trx_max = $trx;
            $obj->tps_max = 0;
            $obj->plan_id = $planid;
            $obj->vmn = $vmn;
            $obj->status = 1;
            Doo::db()->insert($obj);
            $msg = 'SMPP Account successfully created.';
        } else {
            //update
            $obj->id = intval($_POST['scid']);
            $obj->smpp_password = DooSmppcubeHelper::aesEncrypt($password);
            $obj->route_id = $routeid;
            $obj->allowed_ip = $iplist;
            $obj->tx_max = $tx;
            $obj->rx_max = $rx;
            $obj->trx_max = $trx;
            $obj->plan_id = $planid;
            $obj->vmn = $vmn;
            Doo::db()->update($obj);
            $msg = 'SMPP Account updated created.';
        }

        //return
        $_SESSION['notif_msg']['msg'] = $msg;
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $userid;
    }

    public function deleteSmppClient()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['set']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete
        $obj = Doo::loadModel('ScSmppClients', true);
        $obj->id = $this->params['id'];
        Doo::db()->delete($obj);
        //return
        $_SESSION['notif_msg']['msg'] = 'SMPP Account deleted successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $this->params['uid'];
    }

    public function saveUserPlanAssignment()
    {
        $upobj = Doo::loadModel('ScUsersSmsPlans', true);
        $upobj->user_id = intval($_POST['userid']);
        $rs = Doo::db()->find($upobj, array('limit' => 1));
        $options = array(
            "delv_per" => intval($_POST['plan_delv_per']),
            "delv_threshold" => intval($_POST['plan_delv_threshold']),
            "fdlr_id" => intval($_POST['plan_fdlr']),
        );
        if ($rs->id) {
            //update
            $upobj->id = $rs->id;
            $upobj->plan_id = $_POST['mplan'];
            $upobj->subopt_idn = json_encode($options);
            Doo::db()->update($upobj);

            //update plan id for all smpp clients as well
            $scobj = Doo::loadModel('ScSmppClients', true);
            $scobj->user_id = intval($_POST['userid']);
            $scrs = Doo::db()->find($scobj, array('limit' => 1));
            if ($scrs->id) {
                $scobj->id = $scrs->id;
                $scobj->plan_id = $_POST['mplan'];
                Doo::db()->update($scobj);
            }

            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'SMS Plan changed successfully.';
            return Doo::conf()->APP_URL . 'viewUserRouteSettings/' . $_POST['userid'];
        } else {
            //error
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            return Doo::conf()->APP_URL . 'viewUserRouteSettings/' . $_POST['userid'];
        }
    }

    public function saveUserHlrSettings()
    {
        $uhobj = Doo::loadModel('ScUsersHlrSettings', true);
        $uhobj->user_id = intval($_POST['userid']);
        $rs = Doo::db()->find($uhobj, array('limit' => 1));
        if ($rs->id) {
            //update
            $uhobj->id = $rs->id;
            $uhobj->channel_id = $_POST['hlrchannel'];
            $uhobj->credits_cost = $_POST['hlrcredits'];
            Doo::db()->update($uhobj);

            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'HLR settings changed successfully.';
            return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $_POST['userid'];
        } else {
            //insert
            $uhobj->user_id = intval($_POST['userid']);
            $uhobj->channel_id = $_POST['hlrchannel'];
            $uhobj->credits_cost = $_POST['hlrcredits'];
            Doo::db()->insert($uhobj);

            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'HLR settings saved successfully.';
            return Doo::conf()->APP_URL . 'viewUserAccountSettings/' . $_POST['userid'];
        }
    }

    public function getUserActivity()
    {

        $columns = array(
            array('db' => 'action_type', 'dt' => 1),
            array('db' => 'activity', 'dt' => 2),
            array('db' => 'visitor_ip', 'dt' => 3)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        $uid = intval($this->params['uid']);
        if ($uid == 0) {
            exit;
        }
        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "user_id = $uid AND act_time LIKE '$from%'";
            } else {
                $sWhere = "user_id = $uid AND act_time BETWEEN '$from' AND '$to'";
            }
        } else {
            $sWhere = 'user_id = ' . $uid;
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

        //fetch records
        $obj = Doo::loadModel('ScLogsUserActivity', true);
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $entries = Doo::db()->find($obj, $dtdata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($entries as $dt) {

            switch ($dt->flag) {
                case 0:
                    $actthm = 'info';
                    break;
                case 1:
                    $actthm = 'inverse';
                    break;
                case 2:
                    $actthm = 'warning';
                    break;
                case 3:
                    $actthm = 'danger';
                    break;
            }
            if (strpos($dt->activity, '||')) {
                $comar = explode("||", $dt->activity);
                $fincom = $this->SCTEXT(trim($comar[0])) . ' ' . $comar[1];
            } else {
                $fincom = $this->SCTEXT($dt->activity);
            }
            $actstr = '<div class="panel panel-' . $actthm . ' panel-custom m-b-xs"><p class="p-sm text-dark m-b-0"> ' . $fincom . '</p></div>';

            $pldata = json_validate($dt->platform_data) ? json_decode($dt->platform_data, true) : unserialize($dt->platform_data);
            $system = !$pldata['system'] ? '' : $pldata['system'];
            $browser = !$pldata['browser'] ? '' : $pldata['browser'];
            $pageurl = base64_decode($dt->page_url) || $dt->page_url;
            $plstr = '<div class="smstxt-ctr p-sm panel panel-custom panel-info fz-sm">
                                                        <span class="block"><i class="fas fa-lg fa-desktop fa-fixed m-r-md m-b-xs"></i>' . $system . '</span>
                                                    <span class="block"><i class="fas fa-lg fa-globe fa-fixed m-r-md"></i>' . $browser . '</span>
                                                    <span class="block"><i class="fas fa-lg  fa-link fa-fixed m-r-md"></i> ' . $pageurl . '</span>';
            if ($pldata['city'] != '' && $pldata['country'] != '') {
                $plstr .= '<span class="block"><i class="fas fa-lg  fa-map-marker fa-fixed m-r-md"></i> ' . $pldata['city'] . ', ' . $pldata['country'] . '</span>';
            }

            $plstr .= '</div>';

            $buttonstr = '<button class="btn btn-danger blockipbtn" data-aid="' . $dt->id . '" data-uid="' . $uid . '" data-ip="' . $dt->visitor_ip . '"><i class="fa fa-ban fa-lg"></i> ' . $this->SCTEXT('Block IP') . '</button>';

            $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->act_time)), $dt->action_type, $actstr, '<span style="letter-spacing:2px;font-weight:bold;">' . $dt->visitor_ip . '</span>', $plstr, $buttonstr);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function managePermissionGroups()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['permgroups']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Permission Groups';

        $data['page'] = 'User Management';
        $data['current_page'] = 'manage_permgroups';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/managePermissionGroups', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllPermissionGroups()
    {
        Doo::loadModel('ScPermissionGroups');
        $obj = new ScPermissionGroups;
        $permgroups = Doo::db()->find($obj);
        $total = count($permgroups);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($permgroups as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editPermissionGroup/' . $dt->id . '">' . $this->SCTEXT('Edit Permission Group') . '</a></li><li><a href="javascript:void(0);" class="del_pgrp" data-pid="' . $dt->id . '">' . $this->SCTEXT('Delete Permission Group') . '</a></li></ul></div>';

            $desc = '<div class="panel panel-' . $dt->color_scheme . ' panel-custom">' . $dt->description . '</div>';


            $output = array($dt->title, $desc, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addPermissionGroup()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['permgroups']) {
            //denied
            return array('/denied', 'internal');
        }
        $data['links']['Manage Permission Groups'] = Doo::conf()->APP_URL . 'managePermissionGroups';
        $data['active_page'] = 'Add New Permission Group';

        //get all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        $data['page'] = 'User Management';
        $data['current_page'] = 'add_permgroup';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addPermissionGroup', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editPermissionGroup()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['permgroups']) {
            //denied
            return array('/denied', 'internal');
        }
        $data['links']['Manage Permission Groups'] = Doo::conf()->APP_URL . 'managePermissionGroups';
        $data['active_page'] = 'Edit Permission Group';

        $tid = intval($this->params['id']);
        if ($tid == 0) {
            return array('/denied', 'internal');
        }

        //get data
        Doo::loadModel('ScPermissionGroups');
        $obj = new ScPermissionGroups;
        $obj->id = $tid;
        $data['pdata'] = Doo::db()->find($obj, array('limit' => 1));

        //get all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        $data['page'] = 'User Management';
        $data['current_page'] = 'edit_permgroup';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editPermissionGroup', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function savePermissionGroup()
    {
        //collect values
        $pgname = $_POST['pgname'];
        $pdesc = $_POST['pdesc'];
        $theme = $_POST['theme'];
        $permissions = json_encode($_POST['perms']);

        //save values
        Doo::loadModel('ScPermissionGroups');
        $obj = new ScPermissionGroups;
        $obj->title = $pgname;
        $obj->description = $pdesc;
        $obj->color_scheme = $theme;
        $obj->permissions = $permissions;

        if (intval($_POST['pgid']) != 0) {
            $obj->id = intval($_POST['pgid']);
            Doo::db()->update($obj, array('limit' => 1));
            $msg = 'Permission Group edited successfully';
            //update permissons to all users who belong to this permission group
            $qry = "UPDATE sc_users_permissions SET perm_data = ? WHERE pg_id = ?";
            Doo::db()->query($qry, [$permissions, intval($_POST['pgid'])]);
        } else {
            Doo::db()->insert($obj);
            $msg = 'New permission group added successfully';
        }
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'managePermissionGroups';
    }

    public function deletePermissionGroup()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['permgroups']) {
            //denied
            return array('/denied', 'internal');
        }
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScPermissionGroups');
        $obj = new ScPermissionGroups;
        $obj->id = $tid;
        Doo::db()->delete($obj);
        //set notif
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Permission Group deleted successfully';
        //return
        return Doo::conf()->APP_URL . 'managePermissionGroups';
    }


    //16. Spam Campaign Management

    public function manageSpamCampaigns()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spam']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Spam Campaigns';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_spam_cmp';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageSpamCmp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllSpamCampaigns()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spam']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once since there cannot be huge spam campaigns
        Doo::loadModel('ScSpamCampaigns');
        $obj = new ScSpamCampaigns;
        $scmps = Doo::db()->find($obj);
        $total = count($scmps);

        Doo::loadModel('ScSmsRoutes');
        $robj = new ScSmsRoutes;

        $uobj = Doo::loadModel('ScUsers', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($scmps as $dt) {
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'approveSpam/' . $dt->id . '">' . $this->SCTEXT('Approve Campaign & Unblock Account') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'rejectSpam/' . $dt->id . '">' . $this->SCTEXT('Reject & Delete Campaign') . '</a></li></ul></div>';


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

            $rstr = '<span class="label label-info label-md">' . $robj->getRouteData($dt->route_id, 'title')->title . '</span>';

            $smscat = json_decode($dt->sms_type, true);
            $stxtstr = '';
            if ($smscat['main'] == 'text') {
                $stxtstr = '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dt->sms_text) . '</div>';
            } elseif ($smscat['main'] == 'wap') {
                $stdata = json_decode($dt->sms_text, true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                        <h5>' . $stdata['wap_title'] . '</h5>
                                                    <hr class="m-h-xs">

                                                    <span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span>

                                                    </div>';
            } elseif ($smscat['main'] == 'vcard') {
                $stdata = json_decode($dt->sms_text, true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                        <span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span>
                                                    </div>';
            }



            $kwstr = '<span class="label label-danger m-r-xs">' . $dt->spam_keywords . '</span>';

            $output = array($user_str, date(Doo::conf()->date_format_short_time_s, strtotime($dt->submission_time)), $stxtstr, number_format($dt->total_contacts), $rstr, $kwstr, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function rejectSpam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spam']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $cmpid = intval($this->params['id']);

        //fetch records
        $spmobj = Doo::loadModel('ScSpamCampaigns', true);
        $spmobj->id = $cmpid;
        $spmdata = Doo::db()->find($spmobj, array('limit' => 1));

        if (!$spmdata->user_id) {
            //not found
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Campaign not found';
            return Doo::conf()->APP_URL . 'manageSpam';
        }
        //delete fron scheduled queue if applicable
        if ($spmdata->schedule_type == 1) {
            $delqry = "DELETE FROM `sc_scheduled_campaigns` WHERE `sms_shoot_id` = '" . $spmdata->sms_shoot_id . "' LIMIT 1";
            Doo::db()->query($delqry);
        }
        //delete from summary table as well
        $dsumQry = "DELETE FROM `sc_sms_summary` WHERE `sms_shoot_id` = '" . $spmdata->sms_shoot_id . "' LIMIT 1";
        Doo::db()->query($dsumQry);
        //delete from spam campaigns table
        Doo::db()->delete($spmobj);

        //do the log and alert

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Spam campaign was rejected and removed from queue.';
        return Doo::conf()->APP_URL . 'manageSpam';
    }

    public function approveSpam()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spam']) {
            //denied
            return array('/denied', 'internal');
        }
        $cmpid = intval($this->params['id']);

        //simply update the status
        //processor will take care of checking if it is a schedule campaign, then update status there and delete the record here. Normal campaigns will be processed using data from this record and deleted once done. The schedule processor will take care of this. Decided this because its already handling the scheduled and this is kind of like that, as it is a campaign in queue waiting to be sent based on a condition
        $spmobj = Doo::loadModel('ScSpamCampaigns', true);
        $spmobj->id = $cmpid;
        $spmobj->status = 1;
        Doo::db()->update($spmobj);

        //do the log and alert in the hypernode

        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Campaign submitted approved successfully.';
        //return
        return Doo::conf()->APP_URL . 'manageSpam';
    }

    public function manageSpamKeywords()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spamkw']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['links']['Manage Spam'] = Doo::conf()->APP_URL . 'manageSpam';
        $data['active_page'] = 'Manage Spam Keywords';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_spam_kw';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageSpamKw', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllSpamKeywords()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spamkw']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all data at once since there cannot be huge spam keywords
        Doo::loadModel('ScSpamKeywords');
        $obj = new ScSpamKeywords;
        $kws = Doo::db()->find($obj);
        $total = count($kws);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $i = 1;
        foreach ($kws as $dt) {
            $button_str = '<a href="' . Doo::conf()->APP_URL . 'deleteSpamKeyword/' . $dt->id . '" class="btn btn-danger btn-small"><i class="fa fa-lg fa-times m-r-xs"></i> ' . $this->SCTEXT('Delete') . '</a>';

            $output = array($i, $dt->phrase, $button_str);
            array_push($res['aaData'], $output);
            $i++;
        }
        echo json_encode($res);
    }

    public function addSpamKeyword()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spamkw']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['links']['Manage Spam'] = Doo::conf()->APP_URL . 'manageSpam';
        $data['links']['SPAM Keywords'] = Doo::conf()->APP_URL . 'manageSpamKeywords';
        $data['active_page'] = 'Add New Keyword';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_spam_kw';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addSpamKw', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveSpamKeyword()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spam']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect data
        Doo::loadHelper('DooTextHelper');
        $all_kws = $_POST['kwp'];
        if (empty($all_kws) || (is_array($all_kws) && count($all_kws) == 0)) {
            //empty
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Spam keyword cannot be empty. Please enter a keyword.';
            return Doo::conf()->APP_URL . 'manageSpamKeywords';
        }
        $kws = explode("\n", $all_kws);
        foreach ($kws as $kw) {
            $kw = trim(DooTextHelper::cleanInput($kw, ' ', 0));
            if (!empty($kw)) {
                $spmobj = Doo::loadModel('ScSpamKeywords', true);
                $spmobj->phrase = $kw;
                Doo::db()->insert($spmobj);
            }
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Spam keyword added successfully.';
        return Doo::conf()->APP_URL . 'manageSpamKeywords';
    }

    public function deleteSpamKeyword()
    {

        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['spamkw']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect data
        $kw = intval($this->params['id']);

        if ($kw <= 0) {
            //empty
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid SPAM keyword.';
            return Doo::conf()->APP_URL . 'manageSpamKeywords';
        }
        //delete from DB
        $spmobj = Doo::loadModel('ScSpamKeywords', true);
        $spmobj->id = $kw;
        Doo::db()->delete($spmobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Spam keyword deleted successfully.';
        return Doo::conf()->APP_URL . 'manageSpamKeywords';
    }


    //17. Blocked IP Management

    public function newBlockIpRequest()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['blockip']) {
            //denied
            return array('/denied', 'internal');
        }
        //get values
        $uid = intval($_POST['user']);
        $aid = intval($_POST['action']);
        $ip = $_POST['ip'];
        //validate
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            //invalid ip
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid IP. Please check if IP is in correct format.';
            exit;
        }
        //fetch activity details to get platform info
        if ($aid != 0) {
            $actobj = Doo::loadModel('ScLogsUserActivity', true);
            $actobj->id = $aid;
            $actdata = Doo::db()->find($actobj, array('select' => 'activity,platform_data', 'limit' => 1));
        }

        $pldata = $aid != 0 ? $actdata->platform_data : '';
        $biobj = Doo::loadModel('ScBlockedIpList', true);
        $bidata = Doo::db()->find($biobj, array('select' => 'id', 'where' => "ip_address='$ip' AND platform_data='$pldata'", 'limit' => 1));
        if ($bidata->id) {
            //already exists
            $_SESSION['notif_msg']['type'] = 'info';
            $_SESSION['notif_msg']['msg'] = 'IP address already exists in Blocked List.';
            exit;
        }

        $biobj->ip_address = $ip;
        $biobj->user_assoc = $uid;
        $biobj->platform_data = $pldata;
        $biobj->date_added = date(Doo::conf()->date_format_db);
        $biobj->remarks = $_POST['bi_remarks'] == '' ? $actdata->activity : 'MANUAL: ' . $_POST['bi_remarks'];
        Doo::db()->insert($biobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'IP address added in Blocked List.';
        exit;
    }

    public function manageBlockedIpList()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['blockip']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Blocked IP';

        $data['page'] = 'Administration';
        $data['current_page'] = 'blocked_ip_list';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/blockedIp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getBlockedIpList()
    {
        $columns = array(
            array('db' => 'ip_address', 'dt' => 0),
            array('db' => 'remarks', 'dt' => 4)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "date_added LIKE '$from%'";
            } else {
                $sWhere = "date_added BETWEEN '$from' AND '$to'";
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

        //fetch records
        $obj = Doo::loadModel('ScBlockedIpList', true);
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $entries = Doo::db()->find($obj, $dtdata);

        $uobj = Doo::loadModel('ScUsers', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($entries as $dt) {

            $remstr = $dt->remarks == '' ? '- ' . $this->SCTEXT('No Remarks') . ' -' : '<div class="smstxt-ctr img-rounded p-sm bg-danger"><span class="block"> ' . $dt->remarks . '</span></div>';


            if ($dt->platform_data == '') {
                $plstr = '- ' . $this->SCTEXT('No Platform Details') . ' -';
            } else {
                $pldata = unserialize($dt->platform_data);
                $plstr = '<div class="smstxt-ctr p-sm panel panel-custom panel-info fz-sm">
                                                        <span class="block"><i class="fas fa-lg fa-desktop fa-fixed m-r-md m-b-xs"></i>' . $pldata['system'] . '</span>
                                                    <span class="block"><i class="fas fa-lg fa-globe fa-fixed m-r-md"></i>' . $pldata['browser'] . '</span>';
                if ($pldata['city'] != '' && $pldata['country'] != '') {
                    $plstr .= '<span class="block"><i class="fas fa-lg  fa-map-marker fa-fixed m-r-md"></i> ' . $pldata['city'] . ', ' . $pldata['country'] . '</span>';
                }

                $plstr .= '</div>';
            }

            $buttonstr = '<button class="btn btn-success unblockipbtn" data-ipid="' . $dt->id . '"><i class="fa fa-unlock-alt fa-lg"></i> ' . $this->SCTEXT('Unblock IP') . '</button>';

            //get user
            if (intval($dt->user_assoc) == 0) {
                $user_str = '-' . $this->SCTEXT('No User Associated') . '-';
            } else {
                $udata = $uobj->getProfileInfo($dt->user_assoc, 'name,category,email,avatar');
                $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '"><img src="' . $udata->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($udata->name) . '</a><small class="text-muted fz-sm">' . ucwords($udata->category) . '</small></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $udata->email . '</p>
                                            </div>
                                        </div>';
            }


            $output = array('<span style="letter-spacing:2px;font-weight:bold;">' . $dt->ip_address . '</span>', $user_str, $plstr, date(Doo::conf()->date_format_long_time, strtotime($dt->date_added)), $remstr, $buttonstr);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function newUnblockIpRequest()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['blockip']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete from record
        $ipid = intval($_POST['ip']);
        $obj = Doo::loadModel('ScBlockedIpList', true);
        $obj->id = $ipid;
        Doo::db()->delete($obj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'IP address is now removed from the Blocked List.';
        exit;
    }

    public function manuallyBlockIp()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['blockip']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['links']['All Blocked IP'] = 'manageBlockedIpList';
        $data['active_page'] = 'Manually Add IP';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_block_ip';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manualBlockIp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveBlockIps()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['blockip']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $ips = $_POST['ipadds'];
        $ip_ar = explode("\r\n", $ips);
        $rem = DooTextHelper::cleanInput($_POST['bliprem'], ' ', 0);

        //echo '<pre>';var_dump($ip_ar);die;

        if ($ips == '') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'IP addresses cannot be empty.';
            return Doo::conf()->APP_URL . 'manuallyBlockIp';
        }

        $qvalues = '';

        foreach ($ip_ar as $ip) {
            $normalized_ip = str_replace('*', '0', $ip);
            if (filter_var(trim($normalized_ip), FILTER_VALIDATE_IP)) {
                //valid ip
                $qvalues .= "(";
                $qvalues .= "'$ip',";
                $qvalues .= "'" . date(Doo::conf()->date_format_db) . "',";
                $qvalues .= "'$rem'),";
            }
        }


        if ($qvalues == '') {
            //no valid ips supplied
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'No Valid IP Addresses found. Please check if IP address has correct format.';
            return Doo::conf()->APP_URL . 'manuallyBlockIp';
        } else {
            $query = "INSERT INTO `sc_blocked_ip_list` (`ip_address`,`date_added`,`remarks`) VALUES " . $qvalues;
            $query = substr($query, 0, strlen($query) - 1);
            $rs = Doo::db()->query($query);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'IP addresses successfully added in Blocked List.';
            return Doo::conf()->APP_URL . 'manuallyBlockIp';
        }
    }



    //18. App Settings

    public function appSettings()
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
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'App Settings';

        //all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        //all permission groups
        Doo::loadModel('ScPermissionGroups');
        $permobj = new ScPermissionGroups;
        $data['permgroups'] = Doo::db()->find($permobj, array('select' => 'id,title'));

        //fake dlr templates
        $fdobj = Doo::loadModel('ScFdlrTemplates', true);
        $data['fdlrs'] = Doo::db()->find($fdobj);

        $data['page'] = 'App Settings';
        $data['current_page'] = 'app_settings';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/appSettings', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveAppSettings()
    {
        if ($_SESSION['user']['subgroup'] == 'staff') {
            //denied
            return array('/denied', 'internal');
        }
        //check mode
        if ($_POST['setcat'] == 'main') {
            $settings['default_server_timezone'] = DooTextHelper::cleanInput($_POST['default_server_timezone'], '\/');
            $settings['demo_mode'] = intval($_POST['demo_mode']) == 0 ? 'false' : 'true';
            if (!filter_var($_POST['server_ip'], FILTER_VALIDATE_IP)) {
                //error
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid IP address.';
                return Doo::conf()->APP_URL . 'appSettings';
            } else {
                $settings['server_ip'] = $_POST['server_ip'];
            }
            $settings['admin_domain'] = DooTextHelper::cleanInput($_POST['admin_domain'], '.\/');
            if (strlen(trim($_POST['currency'])) > 4) {
                //error
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid Currency sign.';
                return Doo::conf()->APP_URL . 'appSettings';
            } else {
                $settings['currency'] = $_POST['currency'];
            }
            $settings['currency_name'] = DooTextHelper::cleanInput($_POST['currency_name'], '');
            $settings['global_page_title'] = DooTextHelper::cleanInput($_POST['global_page_title'], ': ', 0);
            $settings['eod_alert_time'] = DooTextHelper::cleanInput($_POST['eod_alert_time'], ':');

            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/main.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Main settings saved successfully';
            $_SESSION['activeTabId'] = 'tab-1';
            return Doo::conf()->APP_URL . 'appSettings';
        }

        //msg settings
        if ($_POST['setcat'] == 'messaging') {
            $settings['default_sender_id'] = DooTextHelper::cleanInput($_POST['default_sender_id'], ' ');
            $settings['def_dlr_per'] = intval($_POST['def_dlr_per'] > 0) ? intval($_POST['def_dlr_per']) : 100;
            $settings['dlr_per_threshold'] = intval($_POST['dlr_per_threshold']);
            $settings['dlr_callback_mechanism'] = intval($_POST['dlr_callback_mechanism']);
            $settings['dlr_callback_retry'] = intval($_POST['dlr_callback_retry']);
            $settings['fakedlr_composition'] = json_validate($_POST['systemfdlr']) ? $_POST['systemfdlr'] : '{}';
            $settings['batch_threshold'] = intval($_POST['batch_threshold']);
            $settings['queue_batch_size'] = intval($_POST['queue_batch_size']);
            $settings['queue_process_interval'] = intval($_POST['queue_process_interval']);
            $settings['schedule_process_interval'] = intval($_POST['schedule_process_interval']);
            $settings['temp_process_interval'] = intval($_POST['temp_process_interval']);
            $settings['store_temp_campaigns'] = intval($_POST['store_temp_campaigns']);
            $settings['spam_action'] = DooTextHelper::cleanInput($_POST['spam_action'], '');

            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/messaging.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Messaging settings saved successfully';
            $_SESSION['activeTabId'] = 'tab-2';
            return Doo::conf()->APP_URL . 'appSettings';
        }

        //kannel settings
        if ($_POST['setcat'] == 'kannel') {
            if (!filter_var($_POST['bearerbox_host'], FILTER_VALIDATE_IP)) {
                //error
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid Bearerbox host in Kannel Settings. Provide valid IP address.';
                $_SESSION['activeTabId'] = 'tab-3';
                return Doo::conf()->APP_URL . 'appSettings';
            } else {
                $settings['bearerbox_host'] = $_POST['bearerbox_host'];
            }
            $settings['admin_port'] = intval($_POST['admin_port']);
            $settings['sendsms_port'] = intval($_POST['sendsms_port']);
            $settings['admin_password'] = DooTextHelper::cleanInput($_POST['admin_password'], '!@#$%&*()', 1);
            $settings['status_password'] = DooTextHelper::cleanInput($_POST['status_password'], '!@#$%&*()', 1);
            $settings['username'] = DooTextHelper::cleanInput($_POST['username'], '@', 1);
            $settings['password'] = DooTextHelper::cleanInput($_POST['password'], '!@#$%&*()', 1);
            $settings['kannel_default_refresh'] = intval($_POST['kannel_default_refresh']);
            $settings['kannel_dir'] = DooTextHelper::truePath($_POST['kannel_dir']);
            $settings['kannel_conf_path'] = DooTextHelper::truePath($_POST['kannel_conf_path'], 1);
            $settings['smsbox_port'] = intval($_POST['smsbox_port']);
            $settings['kannel_log_dir'] = DooTextHelper::truePath($_POST['kannel_log_dir']);
            $settings['kannel_dlr_db_host'] = !filter_var($_POST['kannel_dlr_db_host'], FILTER_VALIDATE_DOMAIN) && !filter_var($_POST['kannel_dlr_db_host'], FILTER_VALIDATE_IP) ? 'localhost' : $_POST['kannel_dlr_db_host'];
            $settings['kannel_dlr_db_port'] = intval($_POST['kannel_dlr_db_port']);
            $settings['kannel_dlr_db_user'] = $_POST['kannel_dlr_db_user'];
            $settings['kannel_dlr_db_password'] = $_POST['kannel_dlr_db_password'];
            $settings['kannel_dlr_db_name'] = $_POST['kannel_dlr_db_name'];

            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/kannel.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Kannel settings saved successfully';
            $_SESSION['activeTabId'] = 'tab-3';
            return Doo::conf()->APP_URL . 'appSettings';
        }

        //reseller settings
        if ($_POST['setcat'] == 'reseller') {
            $settings['default_website_status'] = intval($_POST['default_website_status']);
            $settings['default_user_permissions'] = intval($_POST['default_permissions']);
            $settings['reseller_show_password'] = intval($_POST['reseller_show_password']) == 1 ? 'yes' : 'no';
            $settings['allow_buy_sms'] = intval($_POST['allow_buy_sms']);
            $settings['invoice_discount'] = DooTextHelper::cleanInput($_POST['invoice_discount'], '', 1);
            $settings['allowed_payments'] = DooTextHelper::cleanInput(implode(",", array_keys($_POST['payments'])), ',', 1);
            $settings['reseller_pg'] = intval($_POST['allow_reseller_payment']);
            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/reseller.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Reseller settings saved successfully';
            $_SESSION['activeTabId'] = 'tab-4';
            return Doo::conf()->APP_URL . 'appSettings';
        }


        //security settings
        if ($_POST['setcat'] == 'security') {
            $settings['password_strength'] = DooTextHelper::cleanInput($_POST['password_strength'], '', 1);
            $settings['restrict_domain_login'] = intval($_POST['restrict_domain_login']);
            $settings['batch_notify'] = intval($_POST['batch_notify']);
            $settings['order_notify'] = intval($_POST['order_notify']);
            $settings['captcha_action'] = intval($_POST['captcha_action']);
            $settings['admin_login_alert'] = intval($_POST['admin_login_alert']);

            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/security.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Security settings saved successfully';
            $_SESSION['activeTabId'] = 'tab-5';
            return Doo::conf()->APP_URL . 'appSettings';
        }

        //misc settings
        if ($_POST['setcat'] == 'misc') {
            $settings['date_format_db'] = DooTextHelper::cleanInput($_POST['date_format_db'], ',: ', 0);
            $settings['date_format_long'] = DooTextHelper::cleanInput($_POST['date_format_long'], ',: ', 0);
            $settings['date_format_long_time'] = DooTextHelper::cleanInput($_POST['date_format_long_time'], ',: ', 0);
            $settings['date_format_long_time_s'] = DooTextHelper::cleanInput($_POST['date_format_long_time_s'], ',: ', 0);
            $settings['date_format_med'] = DooTextHelper::cleanInput($_POST['date_format_med'], ',: ', 0);
            $settings['date_format_med_time'] = DooTextHelper::cleanInput($_POST['date_format_med_time'], ',: ', 0);
            $settings['date_format_med_time_s'] = DooTextHelper::cleanInput($_POST['date_format_med_time_s'], ',: ', 0);
            $settings['date_format_short'] = DooTextHelper::cleanInput($_POST['date_format_short'], ',: ', 0);
            $settings['date_format_short_time'] = DooTextHelper::cleanInput($_POST['date_format_short_time'], ',: ', 0);
            $settings['date_format_short_time_s'] = DooTextHelper::cleanInput($_POST['date_format_short_time_s'], ',: ', 0);
            $settings['global_upload_dir'] = DooTextHelper::truePath($_POST['global_upload_dir']);
            $settings['global_export_dir'] = DooTextHelper::cleanInput($_POST['global_export_dir'], '\/'); //relative path
            $settings['image_upload_dir'] = DooTextHelper::truePath($_POST['image_upload_dir']);
            $settings['image_upload_url'] = DooTextHelper::cleanInput($_POST['image_upload_url'], '\/');
            $settings['language_dir'] = DooTextHelper::truePath($_POST['language_dir']);



            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/misc.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Miscellaneous settings saved successfully';
            $_SESSION['activeTabId'] = 'tab-6';
            return Doo::conf()->APP_URL . 'appSettings';
        }
    }


    //19. Power Grid

    public function powerGrid()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['powergrid']) {
            //denied
            return array('/denied', 'internal');
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Power Grid';

        //maintenance mode
        $mmobj = Doo::loadModel('ScMiscVars', true);
        $mmdata = Doo::db()->find($mmobj, array('where' => "var_name='MAINTENANCE_MODE_DATA'", 'limit' => 1));
        $data['mmFlag'] = $mmdata->var_status;
        $data['mmdata'] = $mmdata->var_value;

        //check if any active archive task
        $atobj = Doo::loadModel('ScArchiveTasks', true);
        $atobj->task_type = 0;
        $data['artasks'] = []; //Doo::db()->find($atobj, array('limit' => 1, 'where' => 'status <> 2'), 3);

        //archive info
        $miscObj = Doo::loadModel('ScMiscVars', true);
        $miscObj->var_name = 'AUTO_ARCHIVE_DAYS';
        $data['arch'] = Doo::db()->find($miscObj, array('limit' => 1))->var_value;

        $data['page'] = 'Power Grid';
        $data['current_page'] = 'power_grid';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/powerGrid', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveMiscVars()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['powergrid']) {
            //denied
            return array('/denied', 'internal');
        }
        if ($_POST['var'] != '') {
            //get var
            $var = $_POST['var'];
            //get value
            if ($var == 'MAINTENANCE_MODE_DATA') {
                $value = serialize(array('msg' => DooTextHelper::cleanInput($_POST['msg'], " .,@&()+=\/?", 0), 'end_date' => $_POST['end_date']));
            }
            //find var
            Doo::loadModel('ScMiscVars');
            $obj = new ScMiscVars;
            $obj->var_name = $var;
            $rs = Doo::db()->find($obj, array('limit' => 1));
            //save value
            if ($rs) {
                $obj->id = $rs->id;
                $obj->var_status = $_POST['vstatus'];
                $obj->var_value = $value;
                Doo::db()->update($obj);
            }
            //set notif
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Settings saved successfully';
            //return
            return Doo::conf()->APP_URL . 'powerGrid';
        }
    }

    public function updateAutoArchiver()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['powergrid']) {
            //denied
            return array('/denied', 'internal');
        }
        $miscObj = Doo::loadModel('ScMiscVars', true);
        $miscObj->var_name = 'AUTO_ARCHIVE_DAYS';
        $rs = Doo::db()->find($miscObj, array('limit' => 1));
        $miscObj->id = $rs->id;
        $miscObj->var_value = intval($_POST['arch_ts']);
        Doo::db()->update($miscObj);
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Archive settings updated successfully.';
        //return
        return Doo::conf()->APP_URL . 'powerGrid';
    }

    public function addArchiveTask()
    {

        //add a new task in archive DB
        $date = date(Doo::conf()->date_format_db, strtotime($_POST['archdate']));
        if ($date != '') {

            $dt['from'] = 'BOT'; //beginning of time it means get the data from other table
            $dt['to'] = $date;

            $atobj = Doo::loadModel('ScArchiveTasks', true);
            $atobj->task_type = 0;
            $atobj->user_id = $_SESSION['user']['userid'];
            $atobj->date_range = serialize($dt);
            $atobj->added_on = date(Doo::conf()->date_format_db);
            $atobj->status = 0;

            $taskid = Doo::db()->insert($atobj, 3);

            //add history
            $ahobj = Doo::loadModel('ScArchiveHistory', true);
            $ahobj->archive_time = date(Doo::conf()->date_format_db);
            $ahobj->task_id = $taskid;
            $ahobj->selected_date = $date;

            Doo::db()->insert($ahobj, 3);

            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Archive task was added successfully.';
            //return
            return Doo::conf()->APP_URL . 'powerGrid';
        } else {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid date supplied.';
            //return
            return Doo::conf()->APP_URL . 'powerGrid';
        }
    }

    public function cancelArchiveTask()
    {

        //get task id
        $tid = intval($_POST['tid']);

        //check if valid
        $tobj = Doo::loadModel('ScArchiveTasks', true);
        $tobj->id = $tid;
        $tdata = Doo::db()->find($tobj, array('limit' => 1), 3);

        //check if status changed
        if ($tdata->status == '1') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Task has started. It cannot be stopped.';
            exit;
        }

        //remove
        Doo::db()->delete($tobj, array('limit' => 1), 3);
        //remove from history


        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Task has been cancelled.';
        exit;
    }

    public function setProcessStatus()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['powergrid']) {
            //denied
            return array('/denied', 'internal');
        }
        $pcobj = Doo::loadModel('ScAppProcesses', true);

        //check mode
        if ($_POST['mode'] == 'state') {
            //write to state file
            $settings['state_api'] = $_POST['pid'] == 'state_api' ? intval($_POST['status']) : Doo::conf()->state_api;
            $settings['state_xapi'] = $_POST['pid'] == 'state_xapi' ? intval($_POST['status']) : Doo::conf()->state_xapi;;
            $settings['state_webhook'] = $_POST['pid'] == 'state_webhook' ? intval($_POST['status']) : Doo::conf()->state_webhook;;
            $settings['state_smppa'] = $_POST['pid'] == 'state_smppa' ? intval($_POST['status']) : Doo::conf()->state_smppa;;
            $settings['state_smppb'] = $_POST['pid'] == 'state_smppb' ? intval($_POST['status']) : Doo::conf()->state_smppb;;
            $settings['state_gui'] = $_POST['pid'] == 'state_gui' ? intval($_POST['status']) : Doo::conf()->state_gui;;


            //write conf file
            $str = '<?php
            //last updated: ' . date('Y-m-d H:i:s') . '

			';
            foreach ($settings as $name => $value) {

                $str .= '$config[\'' . strtolower($name) . '\']=\'' . $value . '\';
				';
            }

            $my_file = './protected/config/state.conf.php';
            $handle = fopen($my_file, 'w');
            fwrite($handle, $str);
            fclose($handle);
            echo 'Settings saved successfully';
            exit;
        }

        if ($_POST['mode'] == 'ALL') {
            //disable all
            $pcobj->manual_flag = 0;
            Doo::db()->update($pcobj, array('where' => 'id>0')); //all processes
            //log entry - user activity
            $actData['activity_type'] = 'PROCESS TOGGLE';
            $actData['activity'] = Doo::conf()->admin_stop_all_procs;

            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData, 1);
            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'All background processes are disabled.';
            exit;
        } else {
            //disable or enable single function
            $pcobj->id = intval($_POST['pid']);
            $procname = Doo::db()->find($pcobj, array('limit' => 1, 'select' => 'process_name as name'))->name;
            $pcobj->manual_flag = intval($_POST['status']);
            Doo::db()->update($pcobj);
            //log entry - user activity
            $task = intval($_POST['status']) == 1 ? 'ENABLED' : 'DISABLED';
            $actData['activity_type'] = 'PROCESS TOGGLE';
            $actData['activity'] = intval($_POST['status']) == 1 ? Doo::conf()->admin_start_proc . $procname : Doo::conf()->admin_stop_proc . $procname;

            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($_SESSION['user']['userid'], $actData);
            //return

            echo $task == 'ENABLED' ? '<i class="fa fa-lg fa-check-circle text-success"></i> ' . $this->SCTEXT('Process successfully ENABLED') : '<i class="fa fa-lg fa-check-circle text-success"></i> ' . $this->SCTEXT('Process successfully DISABLED');
        }
    }


    //20. Admin Logs

    public function watchmanlog()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['logs']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Logs'] = 'javascript:void(0);';
        $data['active_page'] = 'Watchman Log';

        $data['page'] = 'Logs';
        $data['current_page'] = 'watchman_log';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/watchmanLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getWatchmanLog()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['logs']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'activity', 'dt' => 1)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "`timestamp` LIKE '$from%'";
            } else {
                $sWhere = "`timestamp` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
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

        Doo::loadModel('ScLogsWatchman');
        $obj = new ScLogsWatchman;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $wldata = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($wldata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($wldata as $dt) {

            $type = $dt->concern_flag == 0 ? '<span class="label label-md label-info">' . $this->SCTEXT('Normal') . '</span>' : '<span class="label label-md label-danger">' . $this->SCTEXT('Problem') . '</span>';
            if (strpos($dt->activity, '||')) {
                $comar = explode("||", $dt->activity);
                $fincom = $this->SCTEXT(trim($comar[0])) . ' ' . $comar[1];
            } else {
                $fincom = $this->SCTEXT($dt->activity);
            }
            $output = array(date(Doo::conf()->date_format_long_time_s, strtotime($dt->timestamp)), $fincom, $type);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function dbArchivelog()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['logs']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Logs'] = 'javascript:void(0);';
        $data['active_page'] = 'DB Archive Log';

        $data['page'] = 'Logs';
        $data['current_page'] = 'dbarchive_log';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/dbArchiveLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getDbArchiveLog()
    {
        $columns = array(
            array('db' => 'activity', 'dt' => 1)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "`timestamp` LIKE '$from%'";
            } else {
                $sWhere = "`timestamp` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
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


        Doo::loadModel('ScLogsArchiveActivity');
        $obj = new ScLogsArchiveActivity;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1), 3)->total;

        $wldata = Doo::db()->find($obj, $dtdata, 3);
        $totalFiltered = sizeof($wldata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($wldata as $dt) {

            $type = $dt->concern_flag == 0 ? '<span class="label label-md label-info">' . $this->SCTEXT('Normal') . '</span>' : '<span class="label label-md label-danger">' . $this->SCTEXT('Problem') . '</span>';
            if (strpos($dt->activity, '||')) {
                $comar = explode("||", $dt->activity);
                $fincom = $this->SCTEXT(trim($comar[0])) . ' ' . $comar[1];
            } else {
                $fincom = $this->SCTEXT($dt->activity);
            }
            $output = array(date(Doo::conf()->date_format_long_time_s, strtotime($dt->timestamp)), $fincom, $type);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function susActivityLog()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['logs']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Logs'] = 'javascript:void(0);';
        $data['active_page'] = 'Security Log';

        $data['page'] = 'Logs';
        $data['current_page'] = 'security_log';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/securityLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getSusActivityLog()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['logs']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'action_type', 'dt' => 2),
            array('db' => 'activity', 'dt' => 3),
            array('db' => 'visitor_ip', 'dt' => 4)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "flag IN(2,3) AND act_time LIKE '$from%'";
            } else {
                $sWhere = "flag IN(2,3) AND act_time BETWEEN '$from' AND '$to'";
            }
        } else {
            $sWhere = 'flag IN(2,3)';
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

        //fetch records
        $obj = Doo::loadModel('ScLogsUserActivity', true);
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $entries = Doo::db()->find($obj, $dtdata);

        $uobj = Doo::loadModel('ScUsers', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($entries as $dt) {

            switch ($dt->flag) {
                case 0:
                    $actthm = 'info';
                    break;
                case 1:
                    $actthm = 'inverse';
                    break;
                case 2:
                    $actthm = 'warning';
                    break;
                case 3:
                    $actthm = 'danger';
                    break;
            }
            if (strpos($dt->activity, '||')) {
                $comar = explode("||", $dt->activity);
                $fincom = $this->SCTEXT(trim($comar[0])) . ' ' . $comar[1];
            } else {
                $fincom = $this->SCTEXT($dt->activity);
            }
            $actstr = '<div class="panel panel-' . $actthm . ' panel-custom m-b-xs"><p class="p-sm text-dark m-b-0"> ' . $fincom . '</p></div>';

            $pldata = unserialize($dt->platform_data);
            $plstr = '<div class="smstxt-ctr p-sm panel panel-custom panel-info fz-sm">
                                                        <span class="block"><i class="fas fa-lg fa-desktop fa-fixed m-r-md m-b-xs"></i>' . $pldata['system'] . '</span>
                                                    <span class="block"><i class="fas fa-lg fa-globe fa-fixed m-r-md"></i>' . $pldata['browser'] . '</span>
                                                    <span class="block"><i class="fas fa-lg  fa-link fa-fixed m-r-md"></i> ' . base64_decode($dt->page_url) . '</span>';
            if ($pldata['city'] != '' && $pldata['country'] != '') {
                $plstr .= '<span class="block"><i class="fas fa-lg  fa-map-marker fa-fixed m-r-md"></i> ' . $pldata['city'] . ', ' . $pldata['country'] . '</span>';
            }

            $plstr .= '</div>';

            $buttonstr = '<button class="btn btn-danger blockipbtn" data-aid="' . $dt->id . '" data-uid="' . $dt->user_id . '" data-ip="' . $dt->visitor_ip . '"><i class="fa fa-ban fa-lg"></i> ' . $this->SCTEXT('Block IP') . '</button>';

            //get user
            $udata = $uobj->getProfileInfo($dt->user_id, 'name,category,email,avatar');
            $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '"><img src="' . $udata->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($udata->name) . '</a><small class="text-muted fz-sm">' . ucwords($udata->category) . '</small></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $udata->email . '</p>
                                            </div>
                                        </div>';

            $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->act_time)), $user_str, $dt->action_type, $actstr, '<span style="letter-spacing:2px;font-weight:bold;">' . $dt->visitor_ip . '</span>', $plstr, $buttonstr);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }


    //22. Permission groups management




    //21. Phonebook database

    public function managePhonebookDb()
    {

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Phonebook';

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'manage_pbdb';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/phonebookDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getPhonebookDb()
    {
        $columns = array(
            array('db' => 'group_name', 'dt' => 1),
            array('db' => 'contact_count', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }

        Doo::loadModel('ScPhonebookGroups');
        $obj = new ScPhonebookGroups;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $pbdata = Doo::db()->find($obj, $dtdata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($pbdata as $dt) {

            $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="switchid-' . $dt->id . '" class="pbdbstatus myswitch" type="checkbox" value="' . $dt->id . '" data-dtswitch="true" data-color="#10c469"';
            if ($dt->status == 1) {
                $status_str .= " checked";
            }
            $status_str .= '></div>';

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewPhonebookContacts/' . $dt->id . '">' . $this->SCTEXT('View Contacts') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'editPhonebookDb/' . $dt->id . '">' . $this->SCTEXT('Edit Group') . '</a></li><li><a class="delpbdb" data-gid="' . $dt->id . '" data-gcount="' . number_format($dt->contact_count) . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Group') . '</a></li></ul></div>';

            $output = array($ctr, $dt->group_name, number_format($dt->contact_count), $status_str, $button_str);
            array_push($res['aaData'], $output);
            $ctr++;
        }

        echo json_encode($res);
        exit;
    }

    public function setPhonebookStatus()
    {
        $gid = intval($_POST['gid']);
        $val = intval($_POST['value']);
        $obj = Doo::loadModel('ScPhonebookGroups', true);
        $obj->id = $gid;
        $obj->status = $val;
        Doo::db()->update($obj, array('limit' => 1));
        exit;
    }

    public function addPhonebookDb()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Phonebook'] = Doo::conf()->APP_URL . 'phonebook';
        $data['active_page'] = 'Add Phonebook Group';

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'add_pbdb';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addPhonebookDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editPhonebookDb()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Phonebook'] = Doo::conf()->APP_URL . 'phonebook';
        $data['active_page'] = 'Edit Phonebook Group';

        //get data
        $gid = intval($this->params['id']);
        $obj = Doo::loadModel('ScPhonebookGroups', true);
        $obj->id = $gid;
        $data['gdata'] = Doo::db()->find($obj, array('select' => 'group_name,id', 'limit' => 1));

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'edit_pbdb';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editPhonebookDb', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function savePhonebookDb()
    {
        $gname = DooTextHelper::cleanInput($_POST['pbgroup'], ' ()#!@&*[]|', 0);

        $gid = intval($_POST['pbgid']);

        if ($gid == 0) {
            //insert
            if ($gname == '') {
                //error
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Group name cannot be blank.';
                return Doo::conf()->APP_URL . 'addPhonebookDb';
            }

            Doo::loadModel('ScPhonebookGroups');
            $obj = new ScPhonebookGroups;
            $obj->group_name = $gname;
            Doo::db()->insert($obj);
            $msg = 'New phonebook group added successfully';
        } else {
            //update
            if ($gname == '') {
                //error
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Group name cannot be blank.';
                return Doo::conf()->APP_URL . 'editPhonebookDb/' . $gid;
            }
            Doo::loadModel('ScPhonebookGroups');
            $obj = new ScPhonebookGroups;
            $obj->id = $gid;
            $res = Doo::db()->find($obj, array('limit' => 1));
            if ($res->id) {
                $obj->group_name = $gname;
                Doo::db()->update($obj, array('limit' => 1));
                $msg = 'Phonebook group updated successfully';
            } else {
                return array('/denied', 'internal');
            }
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'phonebook';
    }

    public function deletePhonebookDb()
    {
        $gid = intval($this->params['id']);
        //delete group
        $gobj = Doo::loadModel('ScPhonebookGroups', true);
        $gobj->id = $gid;
        Doo::db()->delete($gobj, array('limit' => 1));
        //delete contacts
        $cobj = Doo::loadModel('ScPhonebookContacts', true);
        Doo::db()->delete($cobj, array('where' => 'group_id=' . $gid));

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Phonebook contacts group deleted successfully.';
        return Doo::conf()->APP_URL . 'phonebook';
    }

    public function viewPhonebookContacts()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Phonebook'] = Doo::conf()->APP_URL . 'phonebook';
        $data['active_page'] = 'Phonebook Contacts';

        //get phonebook contacts
        $gid = intval($this->params['id']);

        //get group name
        $gid = intval($this->params['id']);
        $obj = Doo::loadModel('ScPhonebookGroups', true);
        $obj->id = $gid;
        $data['gdata'] = Doo::db()->find($obj, array('select' => 'group_name,id', 'limit' => 1));

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'view_pbcontacts';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/phonebookContacts', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getPhonebookContacts()
    {
        $gid = intval($this->params['id']);
        $columns = array(
            array('db' => 'mobile', 'dt' => 1)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }

        Doo::loadModel('ScPhonebookContacts');
        $obj = new ScPhonebookContacts;
        $obj->group_id = $gid;
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $pbdata = Doo::db()->find($obj, $dtdata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($pbdata as $dt) {

            $button_str = '<div class="btn-group"><a href="' . Doo::conf()->APP_URL . 'editPhonebookContact/' . $dt->id . '" class="btn btn-info"> <i class="fa fa-large fa-pencil-alt" title="' . $this->SCTEXT('Edit Details') . '"></i> </a><a href="' . Doo::conf()->APP_URL . 'deletePhonebookContact/' . $gid . '/' . $dt->id . '" class="btn btn-danger"> <i class="fa fa-large fa-trash" title="' . $this->SCTEXT('Delete') . '"></i> </a></div>';

            $output = array($ctr, $dt->mobile, $button_str);
            array_push($res['aaData'], $output);
            $ctr++;
        }

        echo json_encode($res);
        exit;
    }

    public function importPhonebookContacts()
    {
        //get group id
        $gid = intval($this->params['id']);
        $gid = intval($this->params['id']);
        $obj = Doo::loadModel('ScPhonebookGroups', true);
        $obj->id = $gid;
        $data['gdata'] = Doo::db()->find($obj, array('select' => 'group_name,id', 'limit' => 1));

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Phonebook'] = Doo::conf()->APP_URL . 'phonebook';
        $data['links'][$data['gdata']->group_name] = Doo::conf()->APP_URL . 'viewPhonebookContacts/' . $gid;
        $data['active_page'] = 'Import Phonebook Contacts';

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'add_pbcontacts';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/importPhonebookContacts', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function savePhonebookContacts()
    {
        $gid = intval($_POST['pbdbid']);
        //get task
        if ($_POST['task'] == 'import') {
            if (sizeof($_POST['uploadedFiles']) < 1) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Please upload at least one file with contact numbers.';
                return Doo::conf()->APP_URL . 'importPhonebookContacts/' . $gid;
            }

            Doo::loadModel('ScPhonebookContacts');
            $cobj = new ScPhonebookContacts;

            //fetch data

            foreach ($_POST['uploadedFiles'] as $file) {

                Doo::loadHelper('DooFile');
                $fhobj = new DooFile;
                $output = array();
                $filename = $file;
                $filepath = Doo::conf()->global_upload_dir . $filename;
                $ext = $fhobj->getFileExtensionFromPath($filepath, true);

                Doo::loadHelper('PHPExcel');
                $filetype = PHPExcel_IOFactory::identify($filepath);
                $objReader = PHPExcel_IOFactory::createReader($filetype);
                $xlobj = $objReader->load($filepath);

                //excel files
                if ($ext == 'xls' || $ext == 'xlsx') {
                    if (strtolower($xlobj->getActiveSheet()->getCell('A1')->getValue()) == 'mobile' || 1 == 1) {
                        //format is correct -- read the data

                        for ($i = 2; $i <= $xlobj->getActiveSheet()->getHighestRow(); $i++) {
                            $mobile = floatval(trim($xlobj->getActiveSheet()->getCell('A' . $i)->getValue()));
                            if ($mobile != 0) {

                                array_push($output, $mobile);
                            }
                        }
                    } else {
                        //format is incorrect
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = 'Wrong format of data in the file.';
                        return Doo::conf()->APP_URL . 'importPhonebookContacts/' . $gid;
                    }
                }

                //CSV File
                if ($filetype == 'CSV' && $ext == 'csv') {
                    $fh = fopen($filepath, 'r');
                    $fdata = fgetcsv($fh);

                    foreach ($fdata as $mobile) {
                        $mobile = floatval(trim($mobile));
                        if ($mobile != 0) {
                            array_push($output, $mobile);
                        }
                    }

                    fclose($fh);
                }

                //TXT File
                if ($filetype == 'CSV' && $ext == 'txt') {
                    //text file is uploaded
                    $file_h = fopen($filepath, "r");

                    while (!feof($file_h)) {
                        $mobile = floatval(trim(fgets($file_h)));

                        array_push($output, $mobile);
                    }
                }

                $resp['data'] = array_unique($output);
                $resp['filetype'] = $ext;

                $filedata = $resp;

                if (!$filedata) {
                    //incorrect data format
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'The format of data in the file was not correct. Please download the Sample File for reference.';
                    return Doo::conf()->APP_URL . 'importContacts';
                }

                //populate DB
                $cobj->insertBulkContacts($filedata['data'], $gid);
                //delete files
                unlink(Doo::conf()->global_upload_dir . $file);
                sleep(1); //take a break from task for 1 second
            }

            //update the count
            $totalcount = $cobj->getTotalContacts($gid);

            $gobj = Doo::loadModel('ScPhonebookGroups', true);
            $gobj->id = $gid;
            $gobj->contact_count = $totalcount;
            Doo::db()->update($gobj);

            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Successfully imported phonebook contacts. Database updated.';
            return Doo::conf()->APP_URL . 'viewPhonebookContacts/' . $gid;
        } else {
            //save edited contact
            if (!DooTextHelper::verifyFormData('mobile', $_POST['pbcontact'])) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid phone number.';
                return Doo::conf()->APP_URL . 'editPhonebookContact/' . intval($_POST['cid']);
            }
            $cobj = Doo::loadModel('ScPhonebookContacts', true);
            $cobj->id = intval($_POST['cid']);
            $cobj->mobile = $_POST['pbcontact'];
            Doo::db()->update($cobj, array('limit' => 1));

            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Phone number modified successfully.';
            return Doo::conf()->APP_URL . 'viewPhonebookContacts/' . intval($_POST['gid']);
        }
    }

    public function editPhonebookContact()
    {
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //get data
        $cid = intval($this->params['id']);
        $obj = Doo::loadModel('ScPhonebookContacts', true);
        $obj->id = $cid;
        $data['pbcdata'] = Doo::db()->find($obj, array('limit' => 1));

        $gobj = Doo::loadModel('ScPhonebookGroups', true);
        $gobj->id = $data['pbcdata']->group_id;
        $data['gdata'] = Doo::db()->find($gobj, array('select' => 'group_name', 'limit' => 1));

        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Phonebook'] = Doo::conf()->APP_URL . 'phonebook';
        $data['links'][$data['gdata']->group_name] = Doo::conf()->APP_URL . 'viewPhonebookContacts/' . $data['pbcdata']->group_id;
        $data['active_page'] = 'Edit Phonebook Contact';


        $data['page'] = 'Contact Management';
        $data['current_page'] = 'edit_pbcontact';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editPhonebookContact', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deletePhonebookContact()
    {
        $cid = intval($this->params['cid']);
        $gid = intval($this->params['gid']);

        //delete contact
        Doo::loadModel('ScPhonebookContacts');
        $cobj = new ScPhonebookContacts;
        $cobj->id = $cid;
        Doo::db()->delete($cobj, array('limit' => 1));

        //reset count
        $cobj2 = new ScPhonebookContacts;
        $totalcount = $cobj2->getTotalContacts($gid);
        $gobj = Doo::loadModel('ScPhonebookGroups', true);
        $gobj->id = $gid;
        $gobj->contact_count = $totalcount;
        Doo::db()->update($gobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Phonebook contact deleted successfully.';
        return Doo::conf()->APP_URL . 'viewPhonebookContacts/' . $gid;
    }


    //22. SSL Management

    public function manageSSL()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['ssl']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Users'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage SSL Certificates';

        $data['page'] = 'User Management';
        $data['current_page'] = 'manage_ssl';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageSSL', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllCertificates()
    {
        //get all data at once as its not huge
        Doo::loadHelper('DooTextHelper');
        Doo::loadModel('ScWebsitesSslCerts');
        $wobj = new ScWebsitesSslCerts;
        $opt['select'] = 'sc_websites_ssl_certs.*, sc_users.name as name, sc_users.user_id as uid, sc_users.avatar as avatar, sc_users.email as email';
        $opt['filters'] = array();
        $opt['filters'][0]['model'] = 'ScUsers';
        $opt['desc'] = 'sc_websites_ssl_certs.id';

        $sites = Doo::db()->find($wobj, $opt);
        $total = count($sites);

        $uobj = Doo::loadModel('ScUsers');

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($sites as $dt) {
            $user_str = '<div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->uid . '"><img src="' . $dt->avatar . '" alt=""></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0 m-b-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->uid . '" class="m-r-xs theme-color">' . ucwords($dt->name) . '</a></h5>
                                                <p style="font-size: 12px;font-style: Italic;">' . $dt->email . '</p>
                                            </div>
                                        </div>';

            $domstr = '<button type="button" class="btn btn-primary btn-xs">' . $dt->domain_name . '</button> ';

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a class="removeSSL" data-dom="' . base64_encode($dt->domain_name) . '" href="javascript:void(0);">' . $this->SCTEXT('Uninstall SSL Certificate') . '</a></li></ul></div>';

            $output = array($user_str, $domstr, date(Doo::conf()->date_format_med_time, strtotime($dt->install_date)), $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addNewSSL()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['ssl']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage SSL Certificates'] = Doo::conf()->APP_URL . 'manageSSL';
        $data['active_page'] = 'Add New SSL';

        //get all resellers
        Doo::loadModel('ScWebsites');
        $wobj = new ScWebsites;
        $opt['select'] = 'sc_websites.*, sc_users.name as name, sc_users.user_id as uid, sc_users.avatar as avatar, sc_users.email as email';
        $opt['filters'] = array();
        $opt['filters'][0]['model'] = 'ScUsers';
        $opt['asc'] = 'sc_users.name';
        $opt['where'] = "sc_users.subgroup NOT IN ('client','staff')";

        $data['rsites'] = Doo::db()->find($wobj, $opt);

        //get all domain names with ssl installed
        $dsobj = Doo::loadModel('ScWebsitesSslCerts', true);
        $res = Doo::db()->find($dsobj, array('select' => 'domain_name'));
        $avdoms = array();
        foreach ($res as $do) {
            array_push($avdoms, $do->domain_name);
        }
        $data['available_certs'] = $avdoms;

        $data['page'] = 'User Management';
        $data['current_page'] = 'add_ssl';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addSSL', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function installNewSSL()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['ssl']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $uid = intval($_POST['domresfil']);
        $domains = $_POST['seldoms'];
        $sshpass = base64_decode($_SESSION['scServerVar']);
        unset($_SESSION['scServerVar']);
        //write files
        $qstr = 'INSERT INTO sc_websites_ssl_certs (user_id,domain_name) VALUES';
        $this->load()->helper("DooSSH");
        $ssh = new Net_SSH2(Doo::conf()->server_ip);
        if ($ssh->login('root', $sshpass)) {
            foreach ($domains as $dom) {
                $confstr = str_replace("yourdomain", $dom, Doo::conf()->nginx_conf_template);
                $ssh->exec("echo -e '$confstr' >> /etc/nginx/sites-enabled/$dom");
                //start ssl install
                $ssh->exec("certbot --nginx -d $dom -n --agree-tos -m support@cubelabs.in --redirect");
                $qstr .= "($uid,'$dom'),";
            }
            $ssh->exec("systemctl reload nginx");
        }

        //add values in DB
        $qstr = substr($qstr, 0, strlen($qstr) - 1);
        Doo::db()->query($qstr);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'SSL Certificate installed successfully.';
        return Doo::conf()->APP_URL . 'manageSSL';
    }

    public function removeSSL()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['user']['ssl']) {
            //denied
            return array('/denied', 'internal');
        }
        $dom = base64_decode($this->params['domain']);
        $sshpass = base64_decode($_SESSION['scServerVar']);
        unset($_SESSION['scServerVar']);
        $this->load()->helper("DooSSH");
        $ssh = new Net_SSH2(Doo::conf()->server_ip);
        if ($ssh->login('root', $sshpass)) {
            //remove nginx conf
            $ssh->exec("cd /etc/nginx/sites-enabled/; rm -rf $dom");
            //remove ssl
            $ssh->exec("certbot delete --cert-name $dom");
            $ssh->exec("systemctl reload nginx");
        }

        //add values in DB
        $qstr = "DELETE FROM sc_websites_ssl_certs WHERE domain_name = '$dom'";
        Doo::db()->query($qstr);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'SSL Certificate removed successfully.';
        return Doo::conf()->APP_URL . 'manageSSL';
    }


    //23. SMPP Server Monitor
    public function smppServerMonitor()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['smppmon']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'SMPP Server Monitor';

        //read monitor file
        $str = file_get_contents(Doo::conf()->node_server_monitor_file);
        $data['moninfo'] = json_decode($str, true);
        $data['totalclients'] = is_countable($data['moninfo']) ? sizeof($data['moninfo']) : 0;
        $data['totalbinds'] = is_countable($data['moninfo']) ? array_reduce($data['moninfo'], function ($res, $a) {
            return $res + $a["totalbinds"];
        }, 0) : 0;

        $data['page'] = 'SMPP Server';
        $data['current_page'] = 'smpp_mon';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/smppServerMonitor', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    //24. MCC MNC Plans
    public function mccmncRatePlans()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'MCC MNC SMS Plans';

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'mccmnc_plans';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/mccmncSmsPlans', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllMccmncPlans()
    {
        //get all data at once as its not huge
        Doo::loadModel('ScMccMncPlans');
        $obj = new ScMccMncPlans;
        $plans = Doo::db()->find($obj);
        $total = count($plans);

        $upobj = Doo::loadModel('ScUsersSmsPlans', true);
        $robj = Doo::loadModel('ScSmsRoutes', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($plans as $dt) {
            $rdata = $robj->getPlanRoutes($dt->route_id);
            $rstr = '';
            foreach ($rdata as $rt) {
                $rstr .= '<span class="label label-md label-primary m-r-xs">' . $rt->title . '</span>';
            }
            $ucount = $upobj->getPlanUserCount($dt->id, 1);
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editMccmncSmsPlan/' . $dt->id . '">' . $this->SCTEXT('Edit Plan') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'setMccmncPricing/' . $dt->id . '">' . $this->SCTEXT('SMS Prices') . '</a></li><li><a class="delmplan" data-pid="' . $dt->id . '" data-ucount="' . $ucount . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Plan') . '</a></li></ul></div>';

            switch ($dt->tax_type) {
                case 'VT':
                    $type = 'VAT';
                    break;
                case 'GT':
                    $type = 'GST';
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
            }

            $output = array($dt->plan_name, $rstr, $ucount, $dt->tax . '% ' . $type, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addMccmncPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['MCCMNC Based Plans'] = Doo::conf()->APP_URL . 'mccmncRatePlans';
        $data['active_page'] = 'Add MCC/MNC Plan';

        //routes for sms plan
        $qry = 'SELECT r.id, r.title, c.country, c.country_code FROM sc_sms_routes r, sc_coverage c WHERE r.country_id = c.id';
        $data['rdata'] = Doo::db()->fetchAll($qry);

        //all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));


        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'add_mplan';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addMccmncPlan', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveMccmncPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $name = $_POST['pname'];
        $route_id = intval($_POST['proutes']); //earlier multiple routes were supported but that caused complications like validating sender id type which can be different for different routes, active time, supported country, blacklist etc. so we only support one
        //get associated country calling prefix
        $qry = 'SELECT r.id, c.country_code FROM sc_sms_routes r, sc_coverage c WHERE r.country_id = c.id AND r.id =' . $route_id;
        $rs = Doo::db()->fetchAll($qry);
        //create route coverage to have countries covered by routes
        $routecoverage = array();
        foreach ($rs as $covdata) {
            $routecoverage[$covdata['country_code']] = array(
                "route" => $covdata['id'],
                "price" => 0
            );
        }

        if (!is_numeric($_POST['pmarg'])) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid profit margin. Please enter a number.';

            if (intval($_POST['planid']) > 0) {
                return Doo::conf()->APP_URL . 'editMccmncSmsPlan/' . $_POST['planid'];
            } else {
                return Doo::conf()->APP_URL . 'addMccmncPlan';
            }
            exit;
        }

        //check and make sure that cost price is set for the associated smpp
        $costprices = [];
        $q = "select smpp_list from sc_sms_routes where id = " . $route_id;
        $smpplist = Doo::db()->fetchRow($q)['smpp_list'];
        //with this query we will get the highest cost price for each mccmnc and then we can apply the profit margin over it. The lower price will be overwritten by higher price as the array key is mccmnc and results are sorted by cost price in increasing order
        $qry = "SELECT mccmnc, CONCAT_WS('|', smpp_id, cost_price, country_prefix) as cpdata FROM sc_smpp_cost_price WHERE smpp_id IN ($smpplist) ORDER BY cost_price";
        $costprices = Doo::db()->fetchAll($qry, null, PDO::FETCH_KEY_PAIR);

        if (sizeof($costprices) == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Cost price not set for associated SMPP. Please set cost price first.';

            if (intval($_POST['planid']) > 0) {
                return Doo::conf()->APP_URL . 'editMccmncSmsPlan/' . $_POST['planid'];
            } else {
                return Doo::conf()->APP_URL . 'addMccmncPlan';
            }
            exit;
        }




        $supported_countries = [];

        //apply selected profit margin based on profit margin type to each mccmnc and prepare to add this data in sc_mccmnc_plan pricing, first clear old pricing
        $newpricing = array();
        foreach ($costprices as $mccmnc => $smppcp) {
            list($smppid, $costprice, $country_prefix) = explode('|', $smppcp);
            if ($_POST['margtype'] == 0) {
                $sellingprice = $costprice + ($costprice * ($_POST['pmarg'] / 100));
            } else {
                $sellingprice = $costprice + $_POST['pmarg'];
            }
            $newpricing[$mccmnc] = array(
                "route_id" => $route_id,
                "plan_id" => intval($_POST['planid']),
                "selling_price" => $sellingprice,
            );
            if (isset($supported_countries[$country_prefix])) {
                if ($sellingprice >= $supported_countries[$country_prefix]["price"]) {
                    $supported_countries[$country_prefix]["price"] = $sellingprice;
                }
            } else {
                $supported_countries[$country_prefix] = array("price" => $sellingprice, "route" => $route_id);
            }
        }


        $obj = Doo::loadModel('ScMccMncPlans', true);
        if (intval($_POST['planid']) > 0) {
            $mode = 'update';
            //update
            $obj->id = intval($_POST['planid']);
            $obj->plan_name = $name;
            $obj->route_id = $route_id;
            $obj->tax = floatval($_POST['ptax']);
            $obj->default_profit = $_POST['pmarg'];
            $obj->default_profit_type = $_POST['margtype'];
            $obj->nonref_amount = floatval($_POST['nfmarg']);
            $obj->tax_type = $_POST['taxtype'];
            $obj->plan_features = serialize($_POST['vftperm']);
            $obj->route_coverage = json_encode($supported_countries);
            Doo::db()->update($obj);
            $msg = 'SMS Plan modified successfully. Routes default prices have been reset please enter them again.';
        } else {
            //insert
            $mode = 'insert';
            $obj->plan_name = $name;
            $obj->route_id = $route_id;
            $obj->tax = floatval($_POST['ptax']);
            $obj->tax_type = $_POST['taxtype'];
            $obj->default_profit = $_POST['pmarg'];
            $obj->default_profit_type = $_POST['margtype'];
            $obj->nonref_amount = floatval($_POST['nfmarg']);
            $obj->plan_features = serialize($_POST['vftperm']);
            $obj->route_coverage = json_encode($supported_countries);
            $planid = Doo::db()->insert($obj);
            $msg = 'SMS Plan created successfully. Proceed to enter the SMS prices from Actions Menu.';
        }



        Doo::db()->delete('ScMccMncPlanPricing', array('where' => 'plan_id=' . intval($_POST['planid'])));

        $insQ = '';
        foreach ($newpricing as $mccmnc => $data) {
            $plan_id = $mode == 'insert' ? $planid : intval($_POST['planid']);
            $insQ .= "( $mccmnc, " . $data['route_id'] . ", " . $plan_id . ", " . $data['selling_price'] . "),";
        }
        //remove the final comma from insQ
        $insQ = rtrim($insQ, ',');
        $qry = "INSERT INTO sc_mcc_mnc_plan_pricing (mccmnc, route_id, plan_id, price) VALUES " . $insQ;
        Doo::db()->query($qry);

        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'mccmncRatePlans';
    }


    public function editMccmncSmsPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['MCCMNC Based Plans'] = Doo::conf()->APP_URL . 'mccmncRatePlans';
        $data['active_page'] = 'Edit MCC/MNC Plan';

        //routes for sms plan
        $qry = 'SELECT r.id, r.title, c.country, c.country_code FROM sc_sms_routes r, sc_coverage c WHERE r.country_id = c.id';
        $data['rdata'] = Doo::db()->fetchAll($qry);

        //all refund rules
        Doo::loadModel('ScDlrRefundRules');
        $refobj = new ScDlrRefundRules;
        $data['refunds'] = Doo::db()->find($refobj, array('select' => 'id,title'));

        //get plan details
        $pobj = Doo::loadModel('ScMccMncPlans', true);
        $pobj->id = $this->params['id'];
        $data['plan'] = Doo::db()->find($pobj, array('limit' => 1));

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'edit_mplan';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editMccmncPlan', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deleteMccmncSmsPlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete plan pricing
        $probj = Doo::loadModel('ScMccMncPlanPricing', true);
        $probj->plan_id = $this->params['id'];
        Doo::db()->delete($probj);

        //delete plan
        $pobj = Doo::loadModel('ScMccMncPlans', true);
        $pobj->id = $this->params['id'];
        Doo::db()->delete($pobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'SMS Plan deleted successfully.';
        return Doo::conf()->APP_URL . 'mccmncRatePlans';
    }

    public function setMccmncPricing()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['MCCMNC Based Plans'] = Doo::conf()->APP_URL . 'mccmncRatePlans';
        $data['active_page'] = 'SMS Plan Pricing';

        //get plan details
        $pobj = Doo::loadModel('ScMccMncPlans', true);
        $pobj->id = $this->params['id'];
        $data['plan'] = Doo::db()->find($pobj, array('limit' => 1));

        //get all countries
        $cvobj = Doo::loadModel('ScCoverage', true);
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country_code, country, prefix', 'where' => 'id > 1'));

        //get all operators
        $opobj = Doo::loadModel('ScMccMncList', true);
        $data['opdata'] = Doo::db()->find($opobj, array('where' => 'status = 1', 'select' => 'brand, operator, country_name, country_iso, country_code', 'groupby' => 'brand, country_iso', 'asc' => 'country_name'));

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'mplan_route_prices';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/mccmncPlanPricing', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getPlanSellingPriceSorted()
    {
        //by default if no param is supplied assume sorted by country
        //first parameter is sorted by operator, param will be country
        //second parameter is sorted by mccmnc, param will be country and operator name
        $planid = $this->params['id'];
        $country = $this->params['country'];
        $operator = $this->params['operator']; //base64 encoded for unicode URI compatibility 
        $mode = intval($this->params['mode']);
        $columns = array(
            array('db' => 'country_iso', 'dt' => 0),
            array('db' => 'mccmnc', 'dt' => 1),
            array('db' => 'brand', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //get plan information
        $planobj = Doo::loadModel('ScMccMncPlans', true);
        $planobj->id = $planid;
        $planinfo = Doo::db()->find($planobj, array('limit' => 1));

        //get all smpp id associated with the plan route
        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        $rtobj->id = $planinfo->route_id;
        $smpp_list = Doo::db()->find($rtobj, array('limit' => 1, 'select' => 'smpp_list'))->smpp_list;

        //get smpp cost price and then only show those who fit
        $costpriceqry = "SELECT mccmnc, cost_price FROM sc_smpp_cost_price WHERE smpp_id IN ($smpp_list) ORDER BY cost_price";
        $costprice = Doo::db()->fetchAll($costpriceqry, null, PDO::FETCH_KEY_PAIR);

        //get all plan mccmnc pricing and apply filter to mccmnclist to only show desired, because we messed up db design here
        $selpriceqry = "SELECT mccmnc, price FROM sc_mcc_mnc_plan_pricing WHERE plan_id = $planid";
        $selprice = Doo::db()->fetchAll($selpriceqry, null, PDO::FETCH_KEY_PAIR);

        $obj = Doo::loadModel('ScMccMncList', true);
        if ($country != '0') {
            $obj->country_code = $country; //calling prefix
        }
        if ($operator != '0') {
            $opdata = explode("|", base64_decode($operator));
            $obj->country_iso = $opdata[1]; //calling iso
            $obj->brand = $opdata[0];
        }
        //get only operational ones

        if (isset($dtdata['where']) && $dtdata['where'] != "") {
            $dtdata['where'] .= ' AND status = 1';
        } else {
            $dtdata['where'] = 'status = 1';
        }
        if (!isset($dtdata['asc']) && !isset($dtdata['desc'])) $dtdata['asc'] = 'country_iso';
        //get only mccmnc relevant to this plan
        if (isset($dtdata['where']) && $dtdata['where'] != "") {
            $dtdata['where'] .= ' AND mccmnc IN (' . implode(',', array_keys($costprice)) . ')';
        } else {
            $dtdata['where'] = 'mccmnc IN (' . implode(',', array_keys($costprice)) . ')';
        }

        //check mode and sort
        if ($mode == 0) {
            $total = Doo::db()->find($obj, array('select' => 'COUNT(*) OVER () AS total', 'where' => $dtdata['where'], 'groupby' => 'country_iso', 'limit' => 1))->total;
            $dtdata['groupby'] = 'country_iso';
            $data = Doo::db()->find($obj, $dtdata);
        }
        if ($mode == 1) {
            $total = Doo::db()->find($obj, array('select' => 'COUNT(*) OVER () AS total', 'where' => $dtdata['where'], 'groupby' => 'brand, country_iso', 'limit' => 1))->total;
            $dtdata['groupby'] = 'brand, country_iso';
            $data = Doo::db()->find($obj, $dtdata);
        }
        if ($mode == 2) {
            $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
            $data = Doo::db()->find($obj, $dtdata);
        }


        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($data as $dt) {

            $nw_selprice = isset($selprice[$dt->mccmnc]) ? $selprice[$dt->mccmnc] : 0;
            $spstr = '<div class="input-group"><span class="input-group-addon">' . Doo::conf()->currency . '</span><input id="' . $dt->mccmnc . '-planprice" type="text" placeholder="e.g. 0.045" class="form-control" value="' . $nw_selprice . '"></div>';
            $cpstr = '<kbd class="text-white bg-primary fz-md">' . Doo::conf()->currency .  ' <b>' . $costprice[$dt->mccmnc] . '</b></kbd>';

            $button_str = '<div class="btn-group"><a title="Save Pricing" href="javascript:void(0);" data-planid="' . $planid . '" data-routeid="' . $planinfo->route_id . '" data-mccmnc="' . $dt->mccmnc . '" data-mode="' . $mode . '" data-rcp="' . $costprice[$dt->mccmnc] . '" class="btn btn-success savePlanpricing"> <i class="fa fa-large fa-check"></i> </a><a title="Remove Pricing" href="javascript:void(0);" data-planid="' . $planid . '" data-routeid="' . $planinfo->route_id . '" data-mccmnc="' . $dt->mccmnc . '" data-mode="' . $mode . '" class="btn btn-danger delPlanpricing"> <i class="fa fa-large fa-trash"></i> </a></div>';
            //now based on the pricing mode, display the records
            if ($mode == 0) {
                //by country
                $output = array($dt->country_iso . ' (+' . $dt->country_code . ')', '<kbd> All MCCMNC </kbd>', 'All Brands', 'All Operators', $cpstr, $spstr, $button_str);
            }
            if ($mode == 1) {
                //by operator
                //since operator narrowes down region we need to skip it e.g show Airtel, instead of Airtel Punjab
                $opstr = $dt->brand == '' ? $dt->operator : $dt->brand;
                $output = array($dt->country_iso . ' (+' . $dt->country_code . ')', '<kbd> All MCCMNC </kbd>', ($opstr), ('-'), $cpstr, $spstr, $button_str);
            }
            if ($mode == 2) {
                //by mccmnc
                $output = array($dt->country_iso . ' (+' . $dt->country_code . ')', '<kbd>' . $dt->mccmnc . '</kbd>', ($dt->brand), ($dt->operator), $cpstr, $spstr, $button_str);
            }


            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;

        //return
    }

    public function saveMccMncPlanPrice()
    {
        //if mode is 0, save this selling price to all operators/mccmnc for the country of the current mccmnc
        if ($_POST['mode'] == 0) {
            //get country of the current mccmnc
            $countryObj = Doo::db()->getOne('ScMccMncList', array('select' => 'country_code', 'where' => "mccmnc = " . $_POST['mccmnc'] . " AND status = 1"));
            $country = $countryObj->country_code;
            //get all mccmnc from the same country as the currently supplied mccmnc
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($country) . " AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
            //prepare insert query
            $validnums = array();
            $insertQry = "INSERT INTO sc_mcc_mnc_plan_pricing (plan_id, mccmnc, route_id, price) VALUES ";
            foreach ($mccmnclist as $mccmnc) {
                array_push($validnums, $mccmnc);
                $insertQry .= '(';
                $insertQry .= intval($_POST['planid']) . ', ' . $mccmnc . ', ' . intval($_POST['routeid']) . ', ' . $_POST['price'];
                $insertQry .= '),';
            }
            $insertQry = substr($insertQry, 0, strlen($insertQry) - 1);
            //delete existing prices for these mccmnc for this country
            $delqry = "DELETE FROM sc_mcc_mnc_plan_pricing WHERE plan_id = " . intval($_POST['planid']) . " AND mccmnc IN (" . implode(',', $validnums) . ")";
            Doo::db()->query($delqry);
            if (sizeof($validnums) > 0) {
                Doo::db()->query($insertQry);
            }
        }
        if ($_POST['mode'] == 1) {
            //get operator of the current mccmnc
            $samplemccmnc = intval($_POST['mccmnc']); // the reason it is sample is because the mode is operator, so even if single mccmnc is supplied we have to put this pricing for all mccmnc for this operator
            $operator = Doo::db()->getOne('ScMccMncList', array('select' => 'brand, operator, country_code', 'where' => "mccmnc = " . $samplemccmnc . " AND status = 1"));
            $operator_name = $operator->brand == "" ? $operator->operator : $operator->brand;

            $validnums = array();
            $insertQry = "INSERT INTO sc_mcc_mnc_plan_pricing (plan_id, mccmnc, route_id, price) VALUES ";
            //get all mccmnc for this operator from sc_mcc_mnc_list
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($operator->country_code) . " AND (brand = '$operator_name' OR operator = '$operator_name') AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
            //prepare the insert query by appending for each operator 
            foreach ($mccmnclist as $mccmnc) {
                array_push($validnums, $mccmnc);
                $insertQry .= '(';
                $insertQry .= intval($_POST['planid']) . ', ' . $mccmnc . ', ' . intval($_POST['routeid']) . ', ' . $_POST['price'];
                $insertQry .= '),';
            }
            $insertQry = substr($insertQry, 0, strlen($insertQry) - 1);
            // before inserting delete all exisitng price for this operator in this plan if exist
            $delqry = "DELETE FROM sc_mcc_mnc_plan_pricing WHERE plan_id = " . intval($_POST['planid']) . " AND mccmnc IN (" . implode(',', $validnums) . ")";
            Doo::db()->query($delqry);
            if (sizeof($validnums) > 0) {
                Doo::db()->query($insertQry);
            }
        }
        if ($_POST['mode'] == 2) {
            $obj = Doo::loadModel('ScMccMncPlanPricing', true);
            $obj->plan_id = intval($_POST['planid']);
            $obj->mccmnc = intval($_POST['mccmnc']);
            $obj->route_id = intval($_POST['routeid']);
            $rs = Doo::db()->find($obj, array('limit' => 1));
            if ($rs->id) {
                //update
                $obj->id = $rs->id;
                $obj->price = $_POST['price'];
                Doo::db()->update($obj);
            } else {
                //insert
                $obj->price = $_POST['price'];
                Doo::db()->insert($obj);
            }
        }
        // update the pricing preference for this plan
        $updQry = "UPDATE sc_mcc_mnc_plans SET pricing_preference = " . intval($_POST['mode'] . " WHERE id = " . intval($_POST['planid']));
        Doo::db()->query($updQry);
        exit;
    }

    public function removeMccMncPlanPrice()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mccmncplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //if mode 0 delete price for all mccmnc for this country
        if (intval($_POST['mode']) == 0) {
            //get country of the current mccmnc
            $countryObj = Doo::db()->getOne('ScMccMncList', array('select' => 'country_code', 'where' => "mccmnc = " . $_POST['mccmnc'] . " AND status = 1"));
            $country = $countryObj->country_code;
            //get all mccmnc from the same country as the currently supplied mccmnc
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($country) . " AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
            if (is_countable($mccmnclist) && count($mccmnclist) > 0) {
                //delete price for all mccmnc in the same country
                $delqry = "DELETE FROM sc_mcc_mnc_plan_pricing WHERE plan_id = " . intval($_POST['planid']) . " AND mccmnc IN (" . implode(',', $mccmnclist) . ")";
                Doo::db()->query($delqry);
            }
        }
        //if mode 1 delete price for all mccmnc for this operator
        if (intval($_POST['mode']) == 1) {
            //get operator of the current mccmnc
            $samplemccmnc = intval($_POST['mccmnc']); // the reason it is sample is because the mode is operator, so even if single mccmnc is supplied we have to delete the pricing for all mccmnc for this operator
            $operator = Doo::db()->getOne('ScMccMncList', array('select' => 'brand, operator', 'where' => "mccmnc = " . $samplemccmnc . " AND status = 1"));
            $operator_name = $operator->brand == "" ? $operator->operator : $operator->brand;

            //get all mccmnc for this operator from sc_mcc_mnc_list
            $mccmncQry = "SELECT mccmnc FROM sc_mcc_mnc_list WHERE country_code = " . intval($_POST['country']) . " AND (brand = '$operator_name' OR operator = '$operator_name') AND status = 1";
            $mccmnclist = Doo::db()->fetchAll($mccmncQry, null, PDO::FETCH_COLUMN);
            if (is_countable($mccmnclist) && count($mccmnclist) > 0) {
                //delete price for all mccmnc in the same country
                $delqry = "DELETE FROM sc_mcc_mnc_plan_pricing WHERE plan_id = " . intval($_POST['planid']) . " AND mccmnc IN (" . implode(',', $mccmnclist) . ")";
                Doo::db()->query($delqry);
            }
        }

        //if mode 2 delete price for single mccmnc
        if (intval($_POST['mode']) == 2) {
            $obj = Doo::loadModel('ScMccMncPlanPricing', true);
            $obj->plan_id = intval($_POST['planid']);
            $obj->mccmnc = intval($_POST['mccmnc']);

            Doo::db()->delete($obj);
        }
        // update the pricing preference for this plan
        $updQry = "UPDATE sc_mcc_mnc_plans SET pricing_preference = " . intval($_POST['mode'] . " WHERE id = " . intval($_POST['planid']));
        Doo::db()->query($updQry);
        exit;
    }

    public function saveDefaultPlanPrice()
    {
        //decided to remove this as we now keep default price for supported countries in route_coverage column
        //get the plan default route price
        // $obj = Doo::loadModel('ScMccMncPlans', true);
        // $obj->id = intval($_POST['planid']);
        // $rs = Doo::db()->find($obj, array('limit' => 1));
        // $routeid = intval($_POST['routeid']);
        // //edit the price for the route
        // $coveragedata = json_decode($rs->route_coverage, true);
        // $covfil = array_filter($coveragedata, function ($elem) use ($routeid) {
        //     return $elem['route'] == $routeid;
        // });
        // $iso = key($covfil); //country iso e.g. IN, FR
        // $coveragedata[$iso]['price'] = floatval($_POST['price']);
        // //save
        // $obj->id = $rs->id;
        // $obj->route_coverage = json_encode($coveragedata);
        // Doo::db()->update($obj);
        exit;
    }

    //25. HLR Lookup Management

    public function manageHlr()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'HLR API Management';

        $data['page'] = 'Administration';
        $data['current_page'] = 'hlrset';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageHlr', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllHlr()
    {
        //get all data at once as its not huge
        Doo::loadModel('ScHlrChannels');
        $obj = new ScHlrChannels;
        $channels = Doo::db()->find($obj);
        $total = count($channels);

        Doo::loadHelper('DooHlrLookupHelper');

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($channels as $dt) {
            $hobj = new DooHlrLookupHelper();
            $hdata = $hobj->getProviderById($dt->provider_id);
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editHlrApi/' . $dt->id . '">' . $this->SCTEXT('Edit Channel') . '</a></li><li><a class="delhlrchn" data-cid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Channel') . '</a></li></ul></div>';
            $pstr = $hdata['name'] . ' ( ' . $hdata['website'] . ' )';
            $output = array($dt->channel_name, $pstr, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addHlrApi()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['HLR Channels'] = Doo::conf()->APP_URL . 'manageHlr';
        $data['active_page'] = 'Add HLR Channel';

        $hobj = Doo::loadHelper('DooHlrLookupHelper', true);
        $data['providers'] = $hobj->getCallers();

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_hlr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addHlrApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editHlrApi()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['HLR Channels'] = Doo::conf()->APP_URL . 'manageHlr';
        $data['active_page'] = 'Edit HLR Channel';

        $hobj = Doo::loadHelper('DooHlrLookupHelper', true);
        $data['providers'] = $hobj->getCallers();

        //get channel details
        $cobj = Doo::loadModel('ScHlrChannels', true);
        $cobj->id = $this->params['id'];
        $data['channel'] = Doo::db()->find($cobj, array('limit' => 1));

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_hlr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editHlrApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveHlrApi()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        $hobj = Doo::loadHelper('DooHlrLookupHelper', true);
        $channel_name = $_POST['chname'];
        $providerid = intval($_POST['provider']);
        $hdata = $hobj->getProviderById($providerid);
        $url = intval($_POST['cid']) > 0 ? 'editHlrApi/' . intval($_POST['cid']) : 'addHlrApi';
        if (!isset($hdata['name'])) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Provider selected.';
            return Doo::conf()->APP_URL . $url;
        }
        if ($hdata['auth']['method'] == 'httpauth') {
            $auth = array('key' => $_POST['param3']);
        } else {
            $auth = array('api_key' => $_POST['param1']);
        }
        $cobj = Doo::loadModel('ScHlrChannels', true);
        $cobj->id = intval($_POST['cid']);
        $rs = Doo::db()->find($cobj, array('limit' => 1));

        if ($rs->channel_name) {
            //edit
            $cobj->id = $rs->id;
            $cobj->provider_id = $providerid;
            $cobj->channel_name = $channel_name;
            $cobj->auth_data = serialize($auth);
            Doo::db()->update($cobj);
            $msg = 'HLR channel modified successfully.';
        } else {
            //insert
            $cobj->provider_id = $providerid;
            $cobj->channel_name = $channel_name;
            $cobj->auth_data = serialize($auth);
            Doo::db()->insert($cobj);
            $msg = 'New HLR channel added successfully.';
        }
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageHlr';
    }

    public function deleteHlrApi()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        $cobj = Doo::loadModel('ScHlrChannels', true);
        $cobj->id = intval($this->params['id']);
        Doo::db()->delete($cobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'HLR channel deleted successfully.';
        return Doo::conf()->APP_URL . 'manageHlr';
    }

    //26. MNP Database
    public function mnpDatabase()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mnpdb']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'MNP Database';
        //get last updated time for the database
        $mnpjobsobj = Doo::loadModel('ScJobsMnp', true);
        $res = Doo::db()->find($mnpjobsobj, array('limit' => 1, 'order' => 'id DESC', 'select' => 'last_run'));
        if ($res->last_run) {
            $nt = new DateTime($res->last_run);
            $ct = new DateTime(date(Doo::conf()->date_format_db));
            $interval = $nt->diff($ct);
            $passed = DooTextHelper::format_interval($interval, 'short');

            $data['last_updated'] = $passed . ' ago<br><span class="fz-sm"> <i class="fa fa-lg m-r-xs fa-clock-o"></i>' . date(Doo::conf()->date_format_med_time_s, strtotime($res->last_run)) . '</span>';
        } else {

            $data['last_updated'] = 'Never';
        }

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_mnp';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/mnpDatabase', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getMnpJobs()
    {
        //get all the mnp jobs in one go as they wont be many
        Doo::loadModel('ScJobsMnp');
        $obj = new ScJobsMnp;
        $jobs = Doo::db()->find($obj);
        $total = count($jobs);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($jobs as $dt) {

            $filenm = '<i class="fa fa-file-text fa-lg"></i> &nbsp;' . $dt->file_name;
            $added = date(Doo::conf()->date_format_short_time, strtotime($dt->start_date));
            $type = $dt->task_type == 0 ? "Import Job" : "Delete Job";
            if ($dt->status == 0) {
                $taskdata = '<ul class="list-group"><li class="list-group-item"><button data-taskid="' . $dt->id . '" class="cancel-mnpjob btn btn-sm btn-danger"><i class="fa fa-ban fa-lg"></i>&nbsp; ' . $this->SCTEXT('Cancel Job') . '</button></li></ul>';

                $status = '<span class="label label-danger">' . $this->SCTEXT('Not Started') . '</span> &nbsp;<i class="fa fa-info-circle fa-lg taskinfo" data-title="' . htmlentities('Task Info <a class="pull-right closeDS" href="javascript:void(0);"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($taskdata) . '"></i>';
            } else if ($dt->status == 1) {

                if ($dt->total_msisdn > 0) {
                    $done_per = intval(($dt->done_msisdn / $dt->total_msisdn) * 100);
                } else {
                    $done_per = 0;
                }

                $taskdata = '<ul class="list-group"><li class="list-group-item"><div class="col-md-8"><div class="myprog progress progress-md"><div class="progress-bar progress-bar-striped active progress-bar-info" role="progressbar" aria-valuenow="' . $done_per . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $done_per . '%"></div></div></div><div class="col-md-4 text-right"><b class="progtext">' . $done_per . '%</b></div><div class="clearfix"></div></li><li class="list-group-item clearfix"><kbd class="pull-right"><b>' . number_format($dt->total_msisdn) . '</b></kbd>' . $this->SCTEXT('Total Rows') . ':</li><li class="list-group-item clearfix"><kbd class="pull-right"><b>' . number_format($dt->done_msisdn) . '</b></kbd>' . $this->SCTEXT('Total Added') . ':</li><li class="list-group-item"><button data-taskid="' . $dt->id . '" class="cancel-mnpjob btn btn-sm btn-danger"><i class="fa fa-ban fa-lg"></i>&nbsp; ' . $this->SCTEXT('Cancel Job') . '</button></li></ul>';

                $status = '<span class="label label-warning">' . $this->SCTEXT('In Progress') . '...</span> &nbsp;<i class="fa fa-info-circle fa-lg taskinfo" data-title="' . htmlentities('Task Info <a class="pull-right closeDS" href="javascript:;"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($taskdata) . '"></i>';
            } else if ($dt->status == 2) {

                $taskdata = '<ul class="list-group"><li class="list-group-item"><div class="col-md-8"><div class="myprog progress progress-md"><div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div><div class="col-md-4 text-right"><b class="progtext">100%</b></div><div class="clearfix"></div></li><li class="list-group-item clearfix"><kbd class="pull-right"><b>' . number_format($dt->total_msisdn) . '</b></kbd>' . $this->SCTEXT('Total Rows') . ':</li><li class="list-group-item clearfix"><kbd class="pull-right"><b>' . number_format($dt->done_msisdn) . '</b></kbd>' . $this->SCTEXT('Total Added') . ':</li><li class="list-group-item"></li><li class="list-group-item list-group-item-info"><i class="fa fa-check-circle fa-lg"></i> &nbsp;' . date(Doo::conf()->date_format_med_time, strtotime($dt->last_run)) . '</li></ul>';

                $status = '<span class="label label-success">' . $this->SCTEXT('Finished') . '</span> &nbsp;<i class="fa fa-info-circle fa-lg taskinfo" data-title="' . htmlentities('Task Info <a class="pull-right closeDS" href="javascript:;"><i class="fa fa-times-circle"></i></a>') . '" data-content="' . htmlentities($taskdata) . '"></i>';
            } else {
                $status = '<span class="label label-default">' . $this->SCTEXT('Cancelled') . '</span>';
            }

            $output = array($type, $filenm, $added, $status);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addMnpRecords()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mnpdb']) {
            //denied
            return array('/denied', 'internal');
        }
        //this will handle both addition and deletion jobs for MNP database
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['MNP Databse'] = Doo::conf()->APP_URL . 'mnpDatabase';
        $data['active_page'] = 'Add MNP Task';
        //all countries
        $covobj = Doo::loadModel('ScCoverage', true);
        $data['countries'] = Doo::db()->find($covobj, array('select' => 'prefix, country'));

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_mnp';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addMnpJob', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveMnpRecords()
    {
        //this will handle both addition and deletion jobs for MNP database
        $jobobj = Doo::loadModel('ScJobsMnp', true);
        $jobobj->task_type = intval($_POST['jtype']);
        $jobobj->file_name = $_POST['uploadedFiles'][0];
        $jobobj->coverage = intval($_POST['coverage']);
        $jobobj->format_flag = 0; //refer to conf file, 0 is for NT Thailand
        Doo::db()->insert($jobobj);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'New MNP job added successfully.';
        return Doo::conf()->APP_URL . 'mnpDatabase';
    }

    public function deleteMnpJob()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['mnpdb']) {
            //denied
            return array('/denied', 'internal');
        }
        $taskid = $this->params['id'];
        $jobobj = Doo::loadModel('ScJobsMnp', true);
        $jobobj->id = $taskid;
        $jobobj->status = 3;
        Doo::db()->update($jobobj);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'MNP job cancelled successfully.';
        return Doo::conf()->APP_URL . 'mnpDatabase';
    }


    //23. SMPP TLV

    public function manageSmppTlv()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'Manage SMPP TLV Parameters';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_tlv';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageTlv', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function listAllTlvParams()
    {
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlvs = Doo::db()->find($tlvobj);
        if (!$tlvs || sizeof($tlvs) == 0) {
            echo '<h5>No TLVs present...</h5>';
            exit;
        }
        $str = '';
        foreach ($tlvs as $tlv) {
            $str .= '<div class="col-md-2" style="position: relative;">
            <div class="text-dark fz-md text-bold text-center m-b-sm">' . $tlv->tlv_title . '<br><kbd class="fz-sm">' . $tlv->tlv_category . '</kbd></div>
            <div class="panel bg-purple rounded-2x planopts tlvboxes pointer">
            <div class="panel-body">
                <h4 class="text-white code fz-12">' . $tlv->tlv_name . '</h4>
                <h4 class="text-white text-bold code">' . $tlv->tlv_tag . '</h4>
                <h4 class="text-white text-bold code">' . $tlv->tlv_type . '</h4>
                <h4 class="text-white code">length: ' . $tlv->tlv_length . '</h4>
            </div>

            </div>
            <div class="tlv_actions" style="position: absolute; top:55%; left:50%; transform:translate(-50%, -50%); display:none">
                    <a href="' . Doo::conf()->APP_URL . 'editSmppTlv/' . $tlv->id . '" class="btn btn-warning p-xs">
                        <i class="fas fa-pencil-alt fa-lg"></i>
                    </a>
                    <a href="javascript:void(0);" data-tlvid="' . $tlv->id . '" class="deltlv btn btn-danger p-xs">
                        <i class="fas fa-trash-alt fa-lg"></i>
                    </a>
                </div>
        </div>';
        }
        echo $str;
        exit;
    }

    public function addNewSmppTlv()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage TLV'] = Doo::conf()->APP_URL . 'manageSmppTlv';
        $data['active_page'] = 'Add New TLV';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_tlv';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addTlv', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editSmppTlv()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage TLV'] = Doo::conf()->APP_URL . 'manageSmppTlv';
        $data['active_page'] = 'Edit TLV Parameters';

        //get tlv details
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlvobj->id = intval($this->params['id']);
        $data['tlv'] = Doo::db()->find($tlvobj, array('limit' => 1));

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_tlv';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editTlv', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveSmppTlv()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlvobj->tlv_title = $_POST['tlv_title'];
        $tlvobj->tlv_category = $_POST['tlv_cat'];
        $tlvobj->tlv_name = $_POST['tlv_name'];
        $tlvobj->tlv_tag = $_POST['tlv_tag'];
        $tlvobj->tlv_type = $_POST['tlv_type'];
        $tlvobj->tlv_length = $_POST['tlv_length'];
        $tlvobj->default_value = $_POST['def_tlv'];

        if (isset($_POST['tlv_id']) && intval($_POST['tlv_id']) != 0) {
            $tlvobj->id = $_POST['tlv_id'];
            Doo::db()->update($tlvobj);
            $msg = 'TLV Parameters updated successfully.';
        } else {
            Doo::db()->insert($tlvobj);
            $msg = 'New TLV tag added successfully. Assign them to SMPP connections to enable them.';
        }
        $this->recreateKannelConfig();
        $this->recreateSmppTlvJson();
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageSmppTlv';
    }

    public function deleteSmppTlv()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete tag
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlvobj->id = intval($this->params['id']);
        Doo::db()->delete($tlvobj);
        //remove from all smpp assignments
        $this->recreateKannelConfig();
        $this->recreateSmppTlvJson();
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'TLV and all asscoiated values have been deleted. Restart Kannel Gracefully to take effect.';
        return Doo::conf()->APP_URL . 'manageSmppTlv';
    }

    public function recreateSmppTlvJson()
    {
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlvs = Doo::db()->find($tlvobj);
        $data = array();
        foreach ($tlvs as $tlv) {
            //in kannel we can have duplicate tlv tags if we specify smsc-id
            //no such luck here, so we define a new tag for tlv with duplicate tags
            if (isset($data[$tlv->tlv_name])) {
                //now we need to define a new tag
                $decimalValue = intval($tlv->tlv_tag, 16);
                $incrementedValue = $decimalValue + 0x1000;
                $newHexString = "0x" . dechex($incrementedValue);
                $data[$tlv->tlv_name] = array(
                    "id" => $newHexString,
                    "type" => $tlv->tlv_type == "octetstring" ? "string" : ($tlv->tlv_type == "integer" ? "int32" : "cstring"),
                );
            } else {
                $data[$tlv->tlv_name] = array(
                    "id" => $tlv->tlv_tag,
                    "type" => $tlv->tlv_type == "octetstring" ? "string" : ($tlv->tlv_type == "integer" ? "int32" : "cstring"),
                );
            }
        }
        $my_file = Doo::conf()->smpp_server_tlv_file;
        $handle = @fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file . '<br>Check file path and permissions.');
        @fwrite($handle, json_encode($data, JSON_PRETTY_PRINT));
        fclose($handle);
    }


    //25. API Vendors
    public function manageApiVendors()
    {

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //staff permission check
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }

        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage API Vendors';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_apivendors';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageApiVendors', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllApiVendors()
    {
        //simply send all data to page as the quantity is small so no serverside processing required
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }

        Doo::loadModel('ScApiVendors');
        $obj = new ScApiVendors;
        $vapis = Doo::db()->find($obj);
        $total = count($vapis);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 0;
        foreach ($vapis as $dt) {
            $ctr++;
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editApiVendor/' . $dt->id . '">' . $this->SCTEXT('Edit Details') . '</a></li><li><a class="remove_vapi" data-rid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Vendor') . '</a></li></ul></div>';
            $status_str = '<div class="m-b-lg m-r-xl inline-block"><input id="switchid-' . $dt->id . '" data-rid="' . $dt->id . '" class="togstatus myswitch" type="checkbox" value="0" data-dtswitch="true" data-color="#10c469"';
            if ($dt->status == 0) {
                $status_str .= " checked";
            }
            $status_str .= '></div>';

            $output = array($dt->title, $dt->provider, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addApiVendor()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage API Vendors'] = Doo::conf()->APP_URL . 'manage Api Vendors';
        $data['active_page'] = 'Add New API Vendor';
        //get all tlvs
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $data['tlvs'] = Doo::db()->find($tlvobj, array('select' => 'id, tlv_title'));

        //get all registered providers e.g. Twillio

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_apivendor';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addApiVendor', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editApiVendor()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }
        //breadcrums
        $data['links']['Manage API Vendors'] = Doo::conf()->APP_URL . 'manageApiVendors';
        $data['active_page'] = 'Edit API Vendor';
        //fetch data
        $rid = intval($this->params['id']);
        if ($rid > 0) {
            //valid id
            Doo::loadModel('ScApiVendors');
            $obj = new ScApiVendors;
            $obj->id = $rid;
            $rdata = Doo::db()->find($obj, array('limit' => 1));
            if ($rdata->id) {
                //record found
                $data['rdata'] = $rdata;
            } else {
                //no records found
                $_SESSION['notif_msg']['msg'] = 'No records found';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageApiVendors';
            }
        } else {
            //invalid id
            return array('/denied', 'internal');
        }
        //get all tlvs
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $data['tlvs'] = Doo::db()->find($tlvobj, array('select' => 'id, tlv_title'));

        //get all registered providers e.g. Twillio

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_apivendor';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editApiVendor', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveApiVendor()
    {
        Doo::loadModel('ScApiVendors');
        $obj = new ScApiVendors;

        if (intval($_POST['aid']) > 0) {
            $mode = 'edit';
            $obj->id = intval($_POST['aid']);
            $data = Doo::db()->find($obj, array('limit' => 1));
            if (!$data->id) {
                $_SESSION['notif_msg']['msg'] = 'Invalid API Vendor ID';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageApiVendors';
            }
        } else {
            $mode = 'add';
        }
        //collect values
        $obj->admin_id = $_SESSION['user']['userid'];
        $obj->title = DooTextHelper::cleanInput($_POST['title'], " ", 0);
        $obj->provider = DooTextHelper::cleanInput($_POST['vapi_provider'], " .()", 0);
        $obj->smsc_id = $mode == 'edit' ? $data->smsc_id : DooTextHelper::cleanInput(trim($_POST['smscid']), ".");
        $obj->auth_data = json_encode(array('sid' => $_POST["authsid"], 'token' => $_POST['authtoken']));
        $obj->status = 1;
        //save in db
        if ($mode == 'edit') {
            $obj->id = $data->id;
            Doo::db()->update($obj);
            $_SESSION['notif_msg']['msg'] = 'API Vendor details saved successfully';
            $_SESSION['notif_msg']['type'] = 'success';
        } else {
            Doo::db()->insert($obj);
            $_SESSION['notif_msg']['msg'] = 'New API Vendor connection added';
            $_SESSION['notif_msg']['type'] = 'success';
        }

        //redirect
        return Doo::conf()->APP_URL . 'manageApiVendors';
    }


    //26. Fake DLR Templates

    public function manageFdlrTemplates()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['fdlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'Manage Fake-DLR Templates';

        $data['page'] = 'Administration';
        $data['current_page'] = 'manage_fdlr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageFdlr', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function listAllFdlrTemplates()
    {
        // get all data at once
        $fdobj = Doo::loadModel('ScFdlrTemplates', true);
        $fdtemps = Doo::db()->find($fdobj);
        $total = count($fdtemps);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($fdtemps as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editFdlrTemplate/' . $dt->id . '">' . $this->SCTEXT('Edit Template') . '</a></li><li><a class="delfdlr" data-tid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete Template') . '</a></li></ul></div>';

            $comp = json_decode($dt->composition, true);
            $compstr = '';
            if (sizeof($comp) > 0) {
                $compstr = '<table class="table table-striped table-bordered bg-white"><tbody>';
                foreach ($comp as $element) {
                    $typeclass = $element['type'] == 1 ? "success" : ($element['type'] == 2 ? 'warning' : 'danger');
                    $compstr .= '<tr><td style="width:30%;">' . $element['ratio'] . '% </td><td> <span class="label label-sm label-' . $typeclass . '"> ' . $element['dlrexp'] . '</span> </td></tr>';
                }
                $compstr .= '</tbody></table>';
            }

            $output = array($dt->title, $compstr, date(Doo::conf()->date_format_med_time, strtotime($dt->last_changed)), $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addNewFdlrTemplate()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['fdlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Fake DLR Templates'] = Doo::conf()->APP_URL . 'manageFdlrTemplates';
        $data['active_page'] = 'Add New Fake DLR Template';

        $data['page'] = 'Administration';
        $data['current_page'] = 'add_fdlr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addFdlr', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editFdlrTemplate()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['fdlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Fake DLR Templates'] = Doo::conf()->APP_URL . 'manageFdlrTemplates';
        $data['active_page'] = 'Modify Fake DLR Template';

        $fdobj = Doo::loadModel('ScFdlrTemplates', true);
        $fdobj->id = intval($this->params['id']);
        $data['fdlr'] = Doo::db()->find($fdobj, array('limit' => 1));

        $data['page'] = 'Administration';
        $data['current_page'] = 'edit_fdlr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editFdlr', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveFdlrTemplate()
    {
        $fdobj = Doo::loadModel('ScFdlrTemplates', true);
        //validate
        if ($_POST['fdtitle'] == "") {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Template title cannot be blank.';
            return Doo::conf()->APP_URL . 'manageFdlrTemplates';
        }
        if (sizeof($_POST['ratio']) < 1) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'At least one DLR code is required.';
            return Doo::conf()->APP_URL . 'manageFdlrTemplates';
        }
        //prepare ratio object
        $comp = array();
        $i = 0;
        foreach ($_POST['ratio'] as $percent) {
            $element = array(
                'ratio' => $percent,
                'dlrcode' => $_POST['codes'][$i],
                'dlrexp' => $_POST['descs'][$i],
                'type' => $_POST['types'][$i]
            );
            array_push($comp, $element);
            $i++;
        }

        $fdobj->title = $_POST['fdtitle'];
        $fdobj->composition = json_encode($comp);

        if (intval($_POST['tid']) > 0) {
            //edit
            $fdobj->id = $_POST['tid'];
            Doo::db()->update($fdobj);
            $_SESSION['notif_msg']['msg'] = 'DLR ratio template modified successfully.';
        } else {
            //add new
            Doo::db()->insert($fdobj);
            $_SESSION['notif_msg']['msg'] = 'New DLR ratio template added successfully.';
        }
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageFdlrTemplates';
    }

    public function deleteFdlrTemplate()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['fdlr']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete template
        $fdobj = Doo::loadModel('ScFdlrTemplates', true);
        $fdobj->id = intval($this->params['id']);
        Doo::db()->delete($fdobj);
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'DLR ratio template deleted successfully.';
        return Doo::conf()->APP_URL . 'manageFdlrTemplates';
    }


    //27. View WABA Admin

    public function syncWaba()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        $options = array('http' => array(
            'method'  => 'GET',
            'header' => 'Authorization: Bearer ' . Doo::conf()->wba_perm_token
        ));
        $context  = stream_context_create($options);
        //but first get all agents to bind them later to user id
        $agqry = "SELECT waba_id, user_id FROM wba_agents";
        $userwaba = Doo::db()->fetchAll($agqry, null, PDO::FETCH_KEY_PAIR);
        //delete existing data
        $delqry = 'DELETE FROM wba_agents';
        Doo::db()->query($delqry);
        $delqry2 = 'DELETE FROM wba_agent_business_profiles';
        Doo::db()->query($delqry2);
        //echo '<br> Own Business Account Details ----------<br>';
        $url_ow = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_business_id . '/owned_whatsapp_business_accounts';
        $owaba_list = json_decode(file_get_contents($url_ow, false, $context), true);
        //var_dump($owaba_list);
        //echo '<br> Sorting through owned Business Account Details ----------<br>';
        //$all_wabas  = array();
        foreach ($owaba_list['data'] as $waba) {

            $waba_id = $waba['id'];
            $waba_business_name = $waba['name'];

            $ownagentinsert = "INSERT INTO wba_agents (user_id, waba_id, waba_name, meta_tz_id, message_template_namespace, is_owned) VALUES (" . intval($_SESSION['user']['userid']) . ", '" . $waba_id . "', '" . htmlspecialchars($waba_business_name) . "', '" . $waba['timezone_id'] . "', '" . $waba['message_template_namespace'] . "', 1)";
            Doo::db()->query($ownagentinsert);

            //get phone numbers from WABA ID
            //echo 'getting phone numbers for WABA ID : ' . $waba_id . '<br>';
            $wp_url = 'https://graph.facebook.com/v19.0/' . $waba_id . '/phone_numbers';
            $waba_nums = json_decode(file_get_contents($wp_url, false, $context), true);
            //var_dump($waba_nums);
            foreach ($waba_nums['data'] as $wabaphn) {
                $waba_phonenumber_id = $wabaphn['id'];
                //get business profile using phone number id of the first registed phone number
                //echo 'getting business profile using phone number id : ' . $waba_phonenumber_id . '<br>';
                $wba_url = 'https://graph.facebook.com/v19.0/' . $waba_phonenumber_id . '/whatsapp_business_profile?fields=about,address,description,email,profile_picture_url,websites,vertical';
                $waba_bp = json_decode(file_get_contents($wba_url, false, $context), true);
                //insert into business profile table
                $ownwababp = "INSERT INTO wba_agent_business_profiles (user_id, waba_id, phone_id, verified_name, display_phone, quality, throughput, webhook, bp_about, bp_address, bp_email, bp_description, bp_websites, bp_profile_picture, bp_verticle) VALUES (" . intval($_SESSION['user']['userid']) . ", '$waba_id', '$waba_phonenumber_id', '" . htmlspecialchars($wabaphn['verified_name']) . "', '" . $wabaphn['display_phone_number'] . "', '" . $wabaphn['quality_rating'] . "', '" . json_encode($wabaphn['throughput']) . "', '" . json_encode($wabaphn['webhook_configuration']) . "', '" . htmlspecialchars($waba_bp['data'][0]['about']) . "', '" . htmlspecialchars($waba_bp['data'][0]['address']) . "', '" . $waba_bp['data'][0]['email'] . "', '" . htmlspecialchars($waba_bp['data'][0]['description']) . "', '" . json_encode($waba_bp['data'][0]['websites']) . "', '" . $waba_bp['data'][0]['profile_picture_url'] . "', '" . $waba_bp['data'][0]['vertical'] . "')";
                Doo::db()->query($ownwababp);
                //var_dump($waba_bp);
            }
        }
        // echo '<br><br>----------All  Shared WABAs----------<br><br>';
        //get all shared waba details

        $url_sw = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_business_id . '/client_whatsapp_business_accounts';
        $waba_list = json_decode(file_get_contents($url_sw, false, $context), true);
        //var_dump($waba_list);
        //foreach WABA get the details
        foreach ($waba_list['data'] as $waba) {
            $waba_id = $waba['id'];
            $waba_business_name = $waba['name'];
            $userid = $userwaba[$waba_id];
            $usragentinsert = "INSERT INTO wba_agents (user_id, waba_id, waba_name, meta_tz_id, message_template_namespace, is_owned) VALUES (" . intval($userid) . ", '" . $waba_id . "', '" . htmlspecialchars($waba_business_name) . "', '" . $waba['timezone_id'] . "', '" . $waba['message_template_namespace'] . "', 0)";
            Doo::db()->query($usragentinsert);
            //get phone numbers from WABA ID
            //echo 'getting phone numbers for WABA ID : ' . $waba_id . ' for business name : ' . $waba_business_name . '<br>';
            $wp_url = 'https://graph.facebook.com/v19.0/' . $waba_id . '/phone_numbers';
            $waba_nums = json_decode(file_get_contents($wp_url, false, $context), true);
            //var_dump($waba_nums);
            //$waba_phn_bp = [];
            foreach ($waba_nums['data'] as $wabaphn) {
                $waba_phonenumber_id = $wabaphn['id'];
                //get business profile using phone number id of the first registed phone number
                //echo 'getting business profile using phone number id : ' . $waba_phonenumber_id . '<br>';
                $wba_url = 'https://graph.facebook.com/v19.0/' . $waba_phonenumber_id . '/whatsapp_business_profile?fields=about,address,description,email,profile_picture_url,websites,vertical';
                $waba_bp = json_decode(file_get_contents($wba_url, false, $context), true);
                $usrwababp = "INSERT INTO wba_agent_business_profiles (user_id, waba_id, phone_id, verified_name, display_phone, quality, last_onboarded_time, throughput, webhook, bp_address, bp_email, bp_about, bp_description, bp_websites, bp_profile_picture, bp_verticle) VALUES (" . intval($userid) . ", '$waba_id', '$waba_phonenumber_id', '" . htmlspecialchars($wabaphn['verified_name']) . "', '" . $wabaphn['display_phone_number'] . "', '" . $wabaphn['quality_rating'] . "', '" . $wabaphn['last_onboarded_time'] . "', '" . json_encode($wabaphn['throughput']) . "', '" . json_encode($wabaphn['webhook_configuration']) . "', '" . htmlspecialchars($waba_bp['data'][0]['address']) . "', '" . $waba_bp['data'][0]['email'] . "', '" . htmlspecialchars($waba_bp['data'][0]['about']) . "', '" . htmlspecialchars($waba_bp['data'][0]['description']) . "', '" . json_encode($waba_bp['data'][0]['websites']) . "', '" . $waba_bp['data'][0]['profile_picture_url'] . "', '" . $waba_bp['data'][0]['vertical'] . "')";
                Doo::db()->query($usrwababp);
                //var_dump($waba_bp);
            }
        }
        return Doo::conf()->APP_URL . 'manageWaba';
    }
    public function manageWaba()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //staff permission check
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['routes']) {
            //denied
            return array('/denied', 'internal');
        }

        //breadcrums
        $data['links']['User Mgmt.'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage WhatsApp Business Accounts';

        //fetch profiles from database
        $wbaobj = Doo::loadModel('WbaAgents', true);
        $data['wbaagents'] = Doo::db()->find($wbaobj);

        $userobj = Doo::loadModel('ScUsers', true);
        $data['user_profiles'] = [];

        foreach ($data['wbaagents'] as $dt) {
            if (!isset($data['user_profiles'][$dt->user_id])) $data['user_profiles'][$dt->user_id] = $userobj->getProfileInfo($dt->user_id, 'name, avatar, email');
        }

        $wbaprofiles = Doo::loadModel('WbaAgentBusinessProfiles', true);
        $wbaprofiles = Doo::db()->find($wbaprofiles);
        $data['wbaprofiles'] = [];
        foreach ($wbaprofiles as $prf) {
            if (!is_array($data['wbaprofiles'][$prf->waba_id])) {
                $data['wbaprofiles'][$prf->waba_id] = [$prf];
            } else {
                array_push($data['wbaprofiles'][$prf->waba_id], $prf);
            }
        }

        $data['page'] = 'User Management';
        $data['current_page'] = 'manage_waba';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageWaba', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }
    public function viewWabaAdmin()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'WhatsApp Business Messaging';

        $url = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_phone_id . '/whatsapp_business_profile?fields=about,address,description,email,profile_picture_url,websites,vertical';
        $options = array('http' => array(
            'method'  => 'GET',
            'header' => 'Authorization: Bearer ' . Doo::conf()->wba_perm_token
        ));
        $context  = stream_context_create($options);
        $data['main_agent'] = json_decode(file_get_contents($url, false, $context), true);


        //echo '<pre>'; var_dump($data['main_agent']); die;
        //get phone number details
        $url2 = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_phone_id;

        $data['main_phone'] = json_decode(file_get_contents($url2, false, $context), true);
        //echo '<pre>'; var_dump($data['main_phone']); die;
        //get default prices for meta
        $defpobj = Doo::loadModel('WbaMetaZonePrices', true);
        $data['def_costs'] = Doo::db()->find($defpobj);

        $data['page'] = 'Administration';
        $data['current_page'] = 'view_waba_admin';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/viewWabaAdmin', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveMetaPricing()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        $defpobj = Doo::loadModel('WbaMetaZonePrices', true);
        $defpobj->id = intval($_POST['id']);
        $defpobj->marketing = floatval($_POST['cp_m']);
        $defpobj->utility = floatval($_POST['cp_u']);
        $defpobj->cp_auth = floatval($_POST['cp_a']);
        $defpobj->auth_int = floatval($_POST['cp_ai']);
        $defpobj->cp_ser = floatval($_POST['cp_s']);
        Doo::db()->update($defpobj);
        echo 'Price changes saved successfully';
    }

    public function whatsappRatePlans()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['SMS Plans'] = 'javascript:void(0);';
        $data['active_page'] = 'WhatsApp Business Messaging Plans';

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'waba_plans';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/manageWabaPlans', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }
    public function viewWhatsappRatePlanPrices()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        //breadcrums
        $data['links']['WhatsApp Business Messaging Plans'] = Doo::conf()->APP_URL . 'whatsappRatePlans';
        $data['active_page'] = 'WBM Plan Prices';

        //get plan details
        $planobj = Doo::loadModel('WbaRatePlans', true);
        $plan = Doo::db()->find($planobj, array('limit' => 1, 'where' => 'id = ' . $this->params['id']));
        //get supported countries zone id
        $supported_countries = json_decode($plan->allowed_countries, true);
        if (sizeof($supported_countries) == 1 && $supported_countries[0] == 0) {
            //all countries are supported
            $zonecovobj = Doo::loadModel("WbaMetaZoneCountries", true);
            $data['plan_coverage'] = Doo::db()->find($zonecovobj, array("desc" => 'zone'));
        } else {
            //only selected countries are supported
            $zonecovobj = Doo::loadModel("WbaMetaZoneCountries", true);
            $data['plan_coverage'] = Doo::db()->find($zonecovobj, array("desc" => 'zone', "where" => 'prefix IN (' . implode(',', $supported_countries) . ')'));
        }

        //get the cost price and selling price for this plan
        $planid = intval($this->params['id']);
        $costpriceqry = "SELECT id, zone, marketing, utility, cp_auth, auth_int, cp_ser FROM wba_meta_zone_prices";
        $costpriceres = Doo::db()->fetchAll($costpriceqry);
        $costprices = array();
        foreach ($costpriceres as $cp) {
            $costprices[$cp['id']] = $cp;
        }
        $data['cost_prices'] = $costprices;

        $planpriceqry = "SELECT id, zone_id, marketing, utility, cp_auth, auth_int, cp_ser FROM wba_rate_plan_price WHERE plan_id = " . $planid;
        $planpriceres = Doo::db()->fetchAll($planpriceqry);
        $planprices = array();
        foreach ($planpriceres as $sp) {
            $planprices[$sp['zone_id']] = $sp;
        }
        $data['plan_prices'] = $planprices;
        $data['planid'] = $planid;

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'waba_plans_prices';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/viewWabaPlanPrices', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }
    public function getWhatsappRatePlans()
    {
        //simply send all data to page as the quantity is small so no serverside processing required
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }

        Doo::loadModel('WbaRatePlans');
        $obj = new WbaRatePlans;
        $wplans = Doo::db()->find($obj);
        $total = count($wplans);

        $sqry = "SELECT prefix, country FROM sc_coverage";
        $cvlist = Doo::db()->fetchAll($sqry, null, PDO::FETCH_KEY_PAIR);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 0;
        foreach ($wplans as $dt) {
            $ctr++;
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editWhatsappRatePlan/' . $dt->id . '">' . $this->SCTEXT('Edit Details') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewWhatsappRatePlanPrices/' . $dt->id . '">' . $this->SCTEXT('View Prices') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'setWhatsappRatePlanDefault/' . $dt->id . '">' . $this->SCTEXT('Set as Default') . '</a></li><li><a class="remove_wplan" data-wpid="' . $dt->id . '" href="javascript:void(0)">' . $this->SCTEXT('Delete Plan') . '</a></li></ul></div>';

            if ($dt->allowed_countries == '["0"]') {
                $covstr = '<span>' . 'All Countries' . '</span>';
            } else {
                $cmtrx = json_decode($dt->allowed_countries, true);

                $countries_str = implode(', ', array_map(function ($key) use ($cvlist) {
                    return $cvlist[$key];
                }, $cmtrx));


                $covstr = '<span>' . $countries_str . '</span>';
            }
            $status_str = $dt->is_default == 0 ? '-' : '<span class="badge badge-success label-md">' . $this->SCTEXT('default plan') . '</span>';

            $output = array($dt->plan_name, $dt->profit_margin . '%', $covstr, $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }
    public function addWhatsappRatePlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['WhatsApp Business Messaging Plans'] = Doo::conf()->APP_URL . 'whatsappRatePlans';
        $data['active_page'] = 'Add New WBM Plan';

        $cvobj = Doo::loadModel('ScCoverage', true);
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country_code, country, prefix', 'where' => 'id > 1'));


        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'add_wabaplan';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addWabaPlan', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }
    public function editWhatsappRatePlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['WhatsApp Business Messaging Plans'] = Doo::conf()->APP_URL . 'whatsappRatePlans';
        $data['active_page'] = 'Edit WBM Plan';

        $cvobj = Doo::loadModel('ScCoverage', true);
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country_code, country, prefix', 'where' => 'id > 1'));

        //get plan details
        $planobj = Doo::loadModel('WbaRatePlans', true);
        $planobj->id = $this->params['id'];
        $data['plan'] = Doo::db()->find($planobj, array('limit' => 1));

        $data['page'] = 'SMS Plans';
        $data['current_page'] = 'edit_wabaplan';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/editWabaPlan', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }
    public function deleteWhatsappRatePlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete plan and pricing 
        $planobj = Doo::loadModel('WbaRatePlans', true);
        $planobj->id = $this->params['id'];
        Doo::db()->delete($planobj);
        $planprcobj = Doo::loadModel('WbaRatePlanPrice', true);
        Doo::db()->delete($planprcobj, array('where' => 'plan_id = ' . $this->params['id']));
        $_SESSION['notif_msg'] = array('type' => 'success', 'msg' => $this->SCTEXT('Whatsapp rate plan deleted successfully.'));
        return Doo::conf()->APP_URL . 'whatsappRatePlans';
    }
    public function saveWhatsappRatePlan()
    {
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['admin']['wabaplan']) {
            //denied
            return array('/denied', 'internal');
        }

        if (!is_numeric($_POST['pmarg'])) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid profit margin. Please enter a number.';

            if (intval($_POST['planid']) > 0) {
                return Doo::conf()->APP_URL . 'editWhatsappRatePlan/' . $_POST['planid'];
            } else {
                return Doo::conf()->APP_URL . 'addWhatsappRatePlan';
            }
            exit;
        }

        //collect values and save in DB
        if (intval($_POST['planid']) > 0) {
            //update
            $plan_id = $_POST['planid'];
            $planobj = Doo::loadModel('WbaRatePlans', true);
            $planobj->id = $plan_id;
            $planobj->plan_name = $_POST['pname'];
            $planobj->profit_margin = $_POST['pmarg'];
            $planobj->allowed_countries = json_encode($_POST['cvsel']);
            $planobj->tax = $_POST['ptax'];
            $planobj->tax_type = $_POST['taxtype'];
            $planobj->nrm = $_POST['nfmarg'];
            Doo::db()->update($planobj, array('limit' => 1));
            $msg = 'Whatsapp Rate plan updated successfully';
        } else {
            //insert
            $planobj = Doo::loadModel('WbaRatePlans', true);
            $planobj->plan_name = $_POST['pname'];
            $planobj->profit_margin = $_POST['pmarg'];
            $planobj->allowed_countries = json_encode($_POST['cvsel']);
            $planobj->tax = $_POST['ptax'];
            $planobj->tax_type = $_POST['taxtype'];
            $planobj->nrm = $_POST['nfmarg'];
            $plan_id = Doo::db()->insert($planobj);
            $msg = 'Whatsapp Rate plan added successfully';
        }

        //update plan pricing for each zone
        //get zone ids that are relevant
        $prefixes = sizeof($_POST['cvsel']) == 1 && $_POST['cvsel'][0] == '0' ? '' : implode(',', $_POST['cvsel']);

        $zoneqry = $prefixes == '' ? "SELECT id, zone, marketing, utility, cp_auth, auth_int, cp_ser FROM wba_meta_zone_prices" : "SELECT id, zone, marketing, utility, cp_auth, auth_int, cp_ser FROM wba_meta_zone_prices WHERE id IN (SELECT zone_id FROM wba_meta_zone_countries WHERE prefix IN (" . $prefixes . "))";
        $zones_with_costprice = Doo::db()->fetchAll($zoneqry, null, PDO::FETCH_OBJ);

        //calculate selling price by margin
        $sellingPriceQ = "INSERT INTO wba_rate_plan_price (plan_id, zone_id, marketing, utility, cp_auth, auth_int, cp_ser) VALUES ";
        foreach ($zones_with_costprice as $zone) {
            $zone_id = $zone->id;
            $sp_marketing = $zone->marketing + ($zone->marketing * $_POST['pmarg'] / 100);
            $sp_utility = $zone->utility + ($zone->utility * $_POST['pmarg'] / 100);
            $sp_auth = $zone->cp_auth + ($zone->cp_auth * $_POST['pmarg'] / 100);
            $sp_auth_int = $zone->auth_int + ($zone->auth_int * $_POST['pmarg'] / 100);
            $sp_ser = $zone->cp_ser + ($zone->cp_ser * $_POST['pmarg'] / 100);
            $sellingPriceQ .= "(" . $plan_id . ", " . $zone_id . ", " . $sp_marketing . ", " . $sp_utility . ", " . $sp_auth . ", " . $sp_auth_int . ", " . $sp_ser . "),";
        }
        //remove comma from end
        $sellingPriceQ = rtrim($sellingPriceQ, ',');
        //delete already existing prices for this plan
        Doo::db()->delete('WbaRatePlanPrice', array('where' => 'plan_id=' . $plan_id));
        //save new prices
        Doo::db()->query($sellingPriceQ);

        $_SESSION['notif_msg'] = array('type' => 'success', 'msg' => $this->SCTEXT($msg));
        return Doo::conf()->APP_URL . 'whatsappRatePlans';
    }

    public function saveWhatsappRatePlanPrices()
    {
        //get all prices
        $plan_id = $_POST['planid'];
        $zone_id = $_POST['zoneid'];
        $prices = $_POST['prices'];
        //save
        $updQry = "UPDATE wba_rate_plan_price SET marketing = " . floatval($prices["marketing"]) . ", utility = " . floatval($prices["utility"]) . ", cp_auth = " . floatval($prices["auth"]) . ", auth_int = " . floatval($prices["authint"]) . ", cp_ser = " . floatval($prices["service"]) . " WHERE plan_id = " . intval($plan_id) . " AND zone_id = " . intval($zone_id);
        Doo::db()->query($updQry);
        //return
        $_SESSION['notif_msg'] = array('type' => 'success', 'msg' => $this->SCTEXT('Plan prices saved successfully.'));
        echo "done";
        exit;
    }

    public function setWhatsappRatePlanDefault()
    {
        $planid = $this->params['id'];
        //remove default flag from all other plans
        $rmQ = "UPDATE wba_rate_plans SET is_default = 0";
        Doo::db()->query($rmQ);
        //set this plan as default
        $upQ = "UPDATE wba_rate_plans SET is_default = 1 WHERE id = " . $planid;
        Doo::db()->query($upQ);
        $_SESSION['notif_msg'] = array('type' => 'success', 'msg' => $this->SCTEXT('Default whatsapp rate plan set successfully.'));
        return Doo::conf()->APP_URL . 'whatsappRatePlans';
    }

    public function switchWabaAgent()
    {
        //check if this user already has an agent assigned or not, if so remove it
        $remQ = "UPDATE wba_agents SET user_id = 0 WHERE user_id = " . intval($_POST['userid']);
        Doo::db()->query($remQ);
        $rembpQ = "UPDATE wba_agent_business_profiles SET user_id = 0 WHERE user_id = " . intval($_POST['userid']);
        Doo::db()->query($rembpQ);
        //assign waba agent
        $agentQ = "UPDATE wba_agents SET user_id = " . intval($_POST['userid']) . " WHERE waba_id = '" . ($_POST['wabaid']) . "'";
        Doo::db()->query($agentQ);

        $bpQ = "UPDATE wba_agent_business_profiles SET user_id = " . intval($_POST['userid']) . " WHERE waba_id = '" . ($_POST['wabaid']) . "'";
        Doo::db()->query($bpQ);

        //assign default plan if not assigned, if assigned leave it be
        $_SESSION['notif_msg'] = array('type' => 'success', 'msg' => $this->SCTEXT('Waba agent assigned successfully.'));
        echo "done";
        exit;
    }

    public function switchWabaPlan()
    {
        //check if this user already has a plan then update else insert it
        $wrobj = Doo::loadModel("WbaAgentRatePlan", true);
        $wrobj->user_id = intval($_POST['userid']);
        $agentplan = Doo::db()->find($wrobj, array('limit' => 1));
        if ($agentplan) {
            $wrobj->id = intval($agentplan->id);
            $wrobj->plan_id = intval($_POST['planid']);
            Doo::db()->update($wrobj);
        } else {
            $wrobj->plan_id = intval($_POST['planid']);
            Doo::db()->insert($wrobj);
        }

        $_SESSION['notif_msg'] = array('type' => 'success', 'msg' => $this->SCTEXT('Waba plan assigned successfully.'));
        echo "done";
        exit;
    }


    //28. Miscellaneous functions

    public function dynamicKannelSmpp($action, $smscid, $tx, $rx, $trx)
    {

        //check if kannel is running

        if (!($fp = fopen('http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/status.xml?password=' . Doo::conf()->status_password, "r"))) {
            //kannel is down
            return;
        }

        if ($action == 'add') {
            //new smpp so just call add-smsc

            // tx
            for ($i = 1; $i <= intval($tx); $i++) {
                $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/add-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_tx' . $i;
                file_get_contents($url);
            }
            // rx
            for ($j = 1; $j <= intval($rx); $j++) {
                $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/add-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_rx' . $j;
                file_get_contents($url);
            }
            // trx
            for ($k = 1; $k <= intval($trx); $k++) {
                $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/add-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_trx' . $k;
                file_get_contents($url);
            }
        }


        if ($action == 'edit') {
            //smpp is edited so stop and start smsc

            // tx
            for ($i = 1; $i <= intval($tx); $i++) {
                $url1 = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/stop-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_tx' . $i;
                file_get_contents($url1);
                sleep(3);
                $url2 = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/start-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_tx' . $i;
                file_get_contents($url2);
            }
            // rx
            for ($j = 1; $j <= intval($rx); $j++) {
                $url1 = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/stop-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_rx' . $j;
                file_get_contents($url1);
                sleep(3);
                $url2 = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/start-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_rx' . $j;
                file_get_contents($url2);
            }
            // trx
            for ($k = 1; $k <= intval($trx); $k++) {
                $url1 = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/stop-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_trx' . $k;
                file_get_contents($url1);
                sleep(3);
                $url2 = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/start-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_trx' . $k;
                file_get_contents($url2);
            }
        }

        if ($action == 'delete') {
            //smpp is deleted so just call remove-smsc

            // tx
            for ($i = 1; $i <= intval($tx); $i++) {
                $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/remove-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_tx' . $i;
                file_get_contents($url);
            }
            // rx
            for ($j = 1; $j <= intval($rx); $j++) {
                $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/remove-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_rx' . $j;
                file_get_contents($url);
            }
            // trx
            for ($k = 1; $k <= intval($trx); $k++) {
                $url = 'http://' . Doo::conf()->bearerbox_host . ':' . Doo::conf()->admin_port . '/remove-smsc?password=' . Doo::conf()->admin_password . '&smsc=' . $smscid . '_trx' . $k;
                file_get_contents($url);
            }
        }
    }




    public function recreateKannelConfig()
    {
        if (Doo::conf()->demo_mode == 'true') return;
        // try to figure out how to writw the config file for differnt kannel instance for different host: Later
        Doo::loadModel('ScSmppAccounts');
        $obj = new ScSmppAccounts;
        $obj->kannel_id = 0;
        $obj->status = 0;
        $all_smpp = Doo::db()->find($obj);

        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $tlvs = Doo::db()->find($tlvobj);
        $smpp_tlvs = array();

        //write conf file
        $my_file = Doo::conf()->kannel_conf_path;
        $handle = @fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file . '<br>Check file path and permissions.');

        $data = '
group=core
admin-port = ' . Doo::conf()->admin_port . '
smsbox-port = ' . Doo::conf()->smsbox_port . '
admin-password = ' . Doo::conf()->admin_password . '
status-password = ' . Doo::conf()->status_password . '
log-file = "' . Doo::conf()->kannel_log_dir . 'kannel.log"
box-deny-ip = "*.*.*.*"
box-allow-ip = "127.0.0.1"
access-log = "' . Doo::conf()->kannel_log_dir . 'bb-access.log"
store-location = "/var/log/kannel/kannel.store"
dlr-storage = mysql

#---------------------------------------------
# SMSC CONNECTIONS
';
        $mo_smsc_list = array();
        foreach ($all_smpp as $rt) {
            //all trx binds
            for ($i = 1; $i <= $rt->trx; $i++) {
                $data .= '

group=smsc
smsc = smpp
smsc-id = ' . $rt->smsc_id . '
smsc-admin-id= ' . $rt->smsc_id . '_trx' . $i . '
host = ' . $rt->host . '
port = ' . $rt->port . '
interface-version = ' . $rt->smpp_version . '
smsc-username = ' . $rt->username . '
smsc-password = ' . $rt->password . '
system-type = "' . $rt->system_type . '"';

                $data .= $rt->esm_class != -1 ? '
esm-class = ' . intval($rt->esm_class) : '';

                $data .= $rt->service_type != '' ? '
service-type = "' . $rt->service_type . '"' : '';

                $data .= $rt->allowed_prefix != '' ? '
allowed-prefix = ' . $rt->allowed_prefix : '';

                $data .= $rt->denied_prefix != '' ? '
denied-prefix = ' . $rt->denied_prefix : '';

                $data .= $rt->ston != '' ? '
source-addr-ton = ' . $rt->ston : '';

                $data .= $rt->snpi != '' ? '
source-addr-npi = ' . $rt->snpi : '';

                $data .= $rt->dton != '' ? '
dest-addr-ton = ' . $rt->dton : '';

                $data .= $rt->dnpi != '' ? '
dest-addr-npi = ' . $rt->dnpi : '';

                $data .= $rt->alt_charset != '' ? '
alt-charset = "' . $rt->alt_charset . '"' : '';

                $data .= $rt->max_octets != 0 ? '
max-sms-octets = ' . intval($rt->max_octets) : '';

                $data .= $rt->logfile != '' ? '
log-file = "' . $rt->logfile . '"' : '';

                $data .= $rt->log_level != '' ? '
log-level = ' . intval($rt->log_level) : '';

                $data .= '
allowed-smsc-id = ' . $rt->smsc_id . '
transceiver-mode = true
enquire-link-interval = ' . $rt->enquire_link_interval . '
reconnect-delay = ' . $rt->reconnect_delay;
                $data .= intval($rt->tps) != 0 ? '
throughput = ' . $rt->tps . '

' : '

';
            }


            //all tx binds
            for ($j = 1; $j <= $rt->tx; $j++) {
                $data .= '

group=smsc
smsc = smpp
smsc-id = ' . $rt->smsc_id . '
smsc-admin-id=' . $rt->smsc_id . '_tx' . $j . '
host = ' . $rt->host . '
port = ' . $rt->port . '
interface-version = ' . $rt->smpp_version . '
smsc-username = ' . $rt->username . '
smsc-password = ' . $rt->password . '
system-type = "' . $rt->system_type . '"';
                $data .= $rt->esm_class != -1 ? '
esm-class = ' . intval($rt->esm_class) : '';
                $rt->service_type != '' ? $data .= '
service-type = "' . $rt->service_type . '"' : '';
                $rt->allowed_prefix != '' ? $data .= '
allowed-prefix = ' . $rt->allowed_prefix : '';
                $rt->denied_prefix != '' ? $data .= '
denied-prefix = ' . $rt->denied_prefix : '';
                $data .= $rt->alt_charset != '' ? '
alt-charset = "' . $rt->alt_charset . '"' : '';
                $rt->ston != '' ? $data .= '
source-addr-ton = ' . $rt->ston : '';
                $rt->snpi != '' ? $data .= '
source-addr-npi = ' . $rt->snpi : '';
                $rt->dton != '' ? $data .= '
dest-addr-ton = ' . $rt->dton : '';
                $rt->dnpi != '' ? $data .= '
dest-addr-npi = ' . $rt->dnpi : '';

                $data .= $rt->max_octets != 0 ? '
max-sms-octets = ' . intval($rt->max_octets) : '';

                $data .= $rt->logfile != '' ? '
log-file = "' . $rt->logfile . '"' : '';

                $data .= $rt->log_level != '' ? '
log-level = ' . intval($rt->log_level) : '';

                $data .= '
allowed-smsc-id = ' . $rt->smsc_id . '
transceiver-mode = false
enquire-link-interval = ' . $rt->enquire_link_interval . '
reconnect-delay = ' . $rt->reconnect_delay;
                $data .= intval($rt->tps) != 0 ? '
throughput = ' . $rt->tps . '

' : '

';
            }

            //all rx binds
            for ($k = 1; $k <= $rt->rx; $k++) {
                $data .= '

group=smsc
smsc = smpp
smsc-id = ' . $rt->smsc_id . '
smsc-admin-id=' . $rt->smsc_id . '_rx' . $k . '
host = ' . $rt->host . '
interface-version = ' . $rt->smpp_version . '
smsc-username = ' . $rt->username . '
smsc-password = ' . $rt->password . '
system-type = "' . $rt->system_type . '"';
                $data .= $rt->esm_class != -1 ? '
esm-class = ' . intval($rt->esm_class) : '';
                $rt->service_type != '' ? $data .= '
service-type = "' . $rt->service_type . '"' : '';
                $rt->allowed_prefix != '' ? $data .= '
allowed-prefix = ' . $rt->allowed_prefix : '';
                $rt->denied_prefix != '' ? $data .= '
denied-prefix = ' . $rt->denied_prefix : '';
                $data .= $rt->alt_charset != '' ? '
alt-charset = "' . $rt->alt_charset . '"' : '';
                $rt->ston != '' ? $data .= '
source-addr-ton = ' . $rt->ston : '';
                $rt->snpi != '' ? $data .= '
source-addr-npi = ' . $rt->snpi : '';
                $rt->dton != '' ? $data .= '
dest-addr-ton = ' . $rt->dton : '';
                $rt->dnpi != '' ? $data .= '
dest-addr-npi = ' . $rt->dnpi : '';

                $data .= $rt->max_octets != 0 ? '
max-sms-octets = ' . intval($rt->max_octets) : '';

                $data .= $rt->logfile != '' ? '
log-file = "' . $rt->logfile . '"' : '';

                $data .= $rt->log_level != '' ? '
log-level = ' . intval($rt->log_level) : '';

                $data .= '
allowed-smsc-id = ' . $rt->smsc_id . '
transceiver-mode = false
enquire-link-interval = ' . $rt->enquire_link_interval . '
reconnect-delay = ' . $rt->reconnect_delay . '
receive-port = ' . ($rt->rcv_port == 0 ? $rt->port : $rt->rcv_port);
                $data .= intval($rt->tps) != 0 ? '
throughput = ' . $rt->tps . '

' : '

';
            }
            //check if specific tlv is bound to this smpp
            if ($rt->tlv_ids != "") {
                $smpp_tlv_list = explode(",", $rt->tlv_ids);
                foreach ($smpp_tlv_list as $tlv) {
                    if (is_array($smpp_tlvs[$tlv])) {
                        array_push($smpp_tlvs[$tlv], $rt->smsc_id);
                    } else {
                        $smpp_tlvs[$tlv] = array($rt->smsc_id);
                    }
                }
            }
            //check if receiving sms is enabled
            if ($rt->purpose == "2WAY") {
                array_push($mo_smsc_list, $rt->smsc_id);
            }
        }

        $data .= '

#---------------------------------------------
# SMSBOX SETUP

group=smsbox
smsbox-id = smppcubebox
bearerbox-host = 127.0.0.1
sendsms-port = ' . Doo::conf()->sendsms_port . '
mo-recode = true
log-file = "' . Doo::conf()->kannel_log_dir . 'smsbox.log"
access-log = "' . Doo::conf()->kannel_log_dir . 'sms-access.log"

group = smsbox-route
smsbox-id = smppcubebox
smsc-id = "' . implode(";", $mo_smsc_list) . '"

#---------------------------------------------
# SEND-SMS USERS

group=sendsms-user
username = ' . Doo::conf()->username . '
password = ' . Doo::conf()->password . '
max-messages = 20
concatenation = true

#---------------------------------------------
# 2-WAY SERVICE

group=sms-service
keyword = default
get-url = "https://' . Doo::conf()->admin_domain . '/getReplies/index?senderid=%P&phone=%p&reply=%a&smscid=%i&vmsgid=%F&metadata=%D&intsmsid=%I&billing=%B&accountidf=%o"
max-messages = 0
alt-charset = UTF-8
catch-all = true

group = mysql-connection
id = mydlr
host = ' . Doo::conf()->kannel_dlr_db_host . '
port = ' . Doo::conf()->kannel_dlr_db_port . '
username = ' . Doo::conf()->kannel_dlr_db_user . '
password = "' . Doo::conf()->kannel_dlr_db_password . '"
database = ' . Doo::conf()->kannel_dlr_db_name . '
max-connections = 10

group = dlr-db
id = mydlr
table = sc_kannel_dlr
field-smsc = smsc
field-timestamp = ts
field-destination = destination
field-source = source
field-service = service
field-url = url
field-mask = mask
field-status = status
field-boxc-id = boxc
';

        if ($tlvs && sizeof($tlvs) > 0) {
            foreach ($tlvs as $tlv) {
                $smsc_str = isset($smpp_tlvs[$tlv->id]) ? implode(";", $smpp_tlvs[$tlv->id]) : "";
                $data .= '
group = smpp-tlv
name = ' . $tlv->tlv_name . '
tag = ' . $tlv->tlv_tag . '
type = ' . $tlv->tlv_type . '
length = ' . $tlv->tlv_length;
                if ($smsc_str != "") {
                    $data .= '
smsc-id = ' . $smsc_str . '
';
                }
                if ($tlv->default_value != "") {
                    $data .= 'const = ' . $tlv->default_value . '
';
                }
                $data .= '

';
            }
        }

        @fwrite($handle, $data);
        fclose($handle);
        //end of write
    }

    public function sshTestConnection()
    {
        $this->load()->helper("DooSSH");
        $ssh = new Net_SSH2(Doo::conf()->server_ip);
        if (!$ssh->login('root', $_REQUEST['rpass'])) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'SSH Authentication Failed. Please try again.';
            echo 'FAIL';
            exit;
        }
        echo 'OK';
        exit;
    }

    public function getAdminSmsSummary()
    {
        $shootid = $this->params['shootid'];

        Doo::loadModel('ScSentSms');
        $obj = new ScSentSms;
        $obj->sms_shoot_id = $shootid;
        //total
        $totalsms = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total'))->total;
        //total actual sent
        $totalact = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => 'status = 1'))->total;
        //total fake dlr
        $totalfake = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => 'status = 2'))->total;

        //fake delivered
        $totalfdel = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => "vendor_dlr='FDEL'"))->total;
        //fake undel
        $totalfundel = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => "vendor_dlr='FUNDEL'"))->total;
        //fake expired
        $totalfexp = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => "vendor_dlr='FEXP'"))->total;

        //real delivered
        $totaldel = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => 'status = 1 AND dlr = 1'))->total;
        //real undel
        $totalundel = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => 'status = 1 AND dlr IN (8,0)'))->total;
        //real expired
        $totalexp = Doo::db()->find($obj, array('limit' => 1, 'select' => 'COUNT(id) as total', 'where' => 'status = 1 AND dlr IN (2,16)'))->total;

        $str = '<table class="table">
                    <tbody>
                        <tr>
                            <td>' . $this->SCTEXT('Total SMS Sent') . '</td>
                            <td class="text-right"><span class="label label-primary">' . number_format($totalsms) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('SMS Actually Sent') . '</td>
                            <td class="text-right"><span class="label label-success">' . number_format($totalact) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('Total Fake Dlr') . '</td>
                            <td class="text-right"><span class="label label-danger">' . number_format($totalfake) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('Fake Delivered') . '</td>
                            <td class="text-right"><span class="label label-success">' . number_format($totalfdel) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('Fake Undelivered') . '</td>
                            <td class="text-right"><span class="label label-pink">' . number_format($totalfundel) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('Fake Expired') . '</td>
                            <td class="text-right"><span class="label label-danger">' . number_format($totalfexp) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('SMS Actually Delivered') . '</td>
                            <td class="text-right"><span class="label label-success">' . number_format($totaldel) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('SMS Actually Undelivered') . '</td>
                            <td class="text-right"><span class="label label-purple">' . number_format($totalundel) . '</span></td>
                        </tr>
                        <tr>
                            <td>' . $this->SCTEXT('SMS Actually Expired') . '</td>
                            <td class="text-right"><span class="label label-danger">' . number_format($totalexp) . '</span></td>
                        </tr>
                    </tbody>
                </table>';
        echo $str;
        exit;
    }

    public function showAppLicense()
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
        $data['links']['Administration'] = 'javascript:void(0);';
        $data['active_page'] = 'GW License Information';

        $data['page'] = 'License';
        $data['current_page'] = 'license';
        $data['role'] = $_SESSION['user']['group'];
        $data['username'] = $_SESSION['user']['name'];
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/showAppLicense', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
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
