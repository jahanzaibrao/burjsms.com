<?php

/**
 * DooSmppcubeHelper class file.
 *
 * @author Saurav <saurabh.pandey@cubelabs.in>
 * @license http://www.doophp.com/license
 */

use DooSmppcubeHelper as GlobalDooSmppcubeHelper;
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/* logic to send sms

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $akobj = Doo::loadModel('ScApiKeys', true);
        $api_key = $akobj->getApiKey($user_id); //sender user id
        $smsdata['sms'] = $smstext;
        $api_url = Doo::conf()->APP_URL.'smsapi/index.php?key='.$api_key.'&campaign=0&routeid='.$route_id.'&type=text&contacts='.$mobiles_by_comma.'&senderid='.$sender_id_text.'&msg='.urlencode($smstext);

        $response = file_get_contents( $api_url, false, stream_context_create($arrContextOptions));

*/


/**
 * A helper class that provides common functions for app
 *
 *
 */
class DooSmppcubeHelper
{



    public static function pushToKannel($data)
    {
        //set out parameters
        $dynamic = false;
        $type = unserialize($data['sms_type']);
        $data['senderid'] = urlencode($data['senderid']);
        //- DLR URL
        // e.g. http://server/app/getDLR/index.php?route_id=1&shoot_id=abcd&user_id=2&upline_id=1&umsgid=234432a45rfd&persmscount=2&personalize=1&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F

        if (isset($data['manual_dlrurl']) && $data['manual_dlrurl'] != '') {
            $dlrurl = $data['manual_dlrurl'];
        } else {
            $dlrurl = 'https://' . Doo::conf()->admin_domain . '/getDLR/index?rid=' . $data['route_id'] . '&shoot_id=' . $data['sms_shoot_id'] . '&uid=' . $data['user_id'] . '&upid=' . $data['upline_id'] . '&umsgid=' . $data['umsgid'] . '&pscnt=' . $data['smscount'] . '&sts=' . urlencode(strtotime('now')) . '&rnm=' . urlencode($data['route_title']) . '&sen=' . $data['senderid'] . '&utype=' . $data['usertype']; //admin domain and ssl
            //below is an experiment to take DLR load off PHP
            //$dlrurl = 'https://'.Doo::conf()->admin_domain.':5306/dlrUpdate/app/?route_id='.$data['route_id'].'&shoot_id='.$data['sms_shoot_id'].'&user_id='.$data['user_id'].'&upline_id='.$data['upline_id'].'&umsgid='.$data['umsgid'].'&persmscount='.$data['smscount'].'&sender='.$data['senderid'].'&usertype='.$data['usertype'];

            if ($type['personalize'] == '1') {
                $dlrurl .= '&dyn=1';
                $dynamic = true;
            }
            if (isset($data['callback_url']) && $data['callback_url'] != '') {
                $dlrurl .= '&cb=' . intval($data['callback_url']); //callback url id passed instead of url because kannel was trimming this
            }
            $dlrurl .= '&mode=' . $data['mode'];
            $dlrurl .= '&mobile=%p&dlr=%d&vdlr=%A&vmsgid=%F';
        }

        $dlrurl = urlencode($dlrurl);

        //- contacts
        $contacts = is_array($data['contacts']) ? implode('+', array_column($data['contacts'], 'mobile')) : $data['contacts'];


        if ($type['main'] == 'text') {

            $url = 'username=' . Doo::conf()->username . '&password=' . Doo::conf()->password . '&smsc=' . $data['smsc'] . '&dlr-mask=27&dlr-url=' . $dlrurl . '&to=' . $contacts . '&from=' . $data['senderid'];

            if ($type['flash'] == '1') {
                $url .= '&mclass=0';
            }
            if ($type['unicode'] == '1') {
                $url .= '&charset=UTF-8&coding=2';
                $txtstr = urlencode(html_entity_decode($data['sms_text'], ENT_QUOTES, "UTF-8"));
            } else {
                $txtstr = urlencode($data['sms_text']);
            }
            $url .= '&text=' . $txtstr;
        } elseif ($type['main'] == 'wap') {

            $url = 'username=' . Doo::conf()->username . '&password=' . Doo::conf()->password . '&smsc=' . $data['smsc'] . '&dlr-mask=27&dlr-url=' . $dlrurl . '&to=' . $contacts . '&from=' . $data['senderid'] . '&mclass=1&udh=%06%05%04%0B%84%23%F0&text=%1B%06%01%AE%02%05%6A%00%45%C6%0C%03' . $data['sms_text']['wap_url'] . '%00%01%03' . $data['sms_text']['wap_url'] . '%00%01%01';
        } elseif ($type['main'] == 'vcard') {

            $text  = "BEGIN:VCARD\r\n";
            $text .= "VERSION:2.1\r\n";
            $text .= "N:" . $data['sms_text']['vcard_lname'] . ";" . $data['sms_text']['vcard_fname'] . "\r\n";
            $text .= "TITLE:" . $data['sms_text']['vcard_job'] . "\r\n";
            $text .= "ORG:" . $data['sms_text']['vcard_comp'] . "\r\n";
            $text .= "EMAIL:" . $data['sms_text']['vcard_email'] . "\r\n";
            $text .= "TEL;PREF:+" . $data['sms_text']['vcard_tel'] . "\r\n";
            $text .= "END:VCARD\r\n";

            $msg = urlencode($text);

            $url = 'username=' . Doo::conf()->username . '&password=' . Doo::conf()->password . '&smsc=' . $data['smsc'] . '&dlr-mask=27&dlr-url=' . $dlrurl . '&to=' . $contacts . '&from=' . $data['senderid'] . '&udh=%06%05%04%23%F4%00%00&text=' . $msg;
        }

        //tlv tags
        if ($data['tlv'] && $data['tlv'] != '') {
            $url .= '&meta-data=' . urlencode('?smpp?');
            foreach ($data['tlv'] as $tlv) {
                //split tlv params
                $tlv_params = explode("||", $tlv);
                $url .= urlencode($tlv_params[0] . '=' . $tlv_params[1] . '&');
            }
        }
        //submit to Gateway
        return self::smppSubmit($url, $dynamic);
    }

    public static function smppSubmit($URL, $dynamic = false)
    {
        if (Doo::conf()->demo_mode == 'false') {
            if ($dynamic == false) {
                //could be bulk shoot
                // $filename = strtotime(date('Y-m-d H:i:s')) . '-Campaign-' . uniqid() . mt_rand() . '.txt';
                // $myfile = fopen($filename, "w");
                // fwrite($myfile, $URL);
                // fclose($myfile);
                // $command = "curl -s 'http://" . Doo::conf()->bearerbox_host . ":" . Doo::conf()->sendsms_port . "/cgi-bin/sendsms' -G  -d @" . $filename . " > /dev/null &";
                // exec($command);
                // unset($command);
                $connection = fsockopen(Doo::conf()->bearerbox_host, Doo::conf()->sendsms_port, $error_number, $error_description, 60);
                socket_set_blocking($connection, false);
                fputs($connection, "GET /cgi-bin/sendsms?$URL HTTP/1.0\r\n\r\n");
                while (!feof($connection)) {
                    $myline = fgets($connection, 128);
                }
                fclose($connection);
            } else {
                //single sms, hit it
                $connection = fsockopen(Doo::conf()->bearerbox_host, Doo::conf()->sendsms_port, $error_number, $error_description, 60);
                socket_set_blocking($connection, false);
                fputs($connection, "GET /cgi-bin/sendsms?$URL HTTP/1.0\r\n\r\n");
                while (!feof($connection)) {
                    $myline = fgets($connection, 128);
                }
                fclose($connection);
            }

            return;
        } else {
            $filename = strtotime(date('Y-m-d H:i:s')) . '-Campaign-' . uniqid() . mt_rand() . '.txt';
            // $myfile = fopen($filename, "w");
            // fwrite($myfile, $URL);
            // fclose($myfile);
            return 'submitted';
        }
    }

    public static function vendorApiCampaignProcessor($data)
    {

        $contacts = is_array($data['contacts']) ? array_column($data['contacts'], 'mobile') : [$data['contacts']];

        $callbackurl = "https://" . Doo::conf()->admin_domain . "/apiVendorDlr/twilio/index?shoot=" . $data['sms_shoot_id'];
        $sid = "AC39cb7e383d36ab9ae8efeb194e21510f";
        $token = "93a25ec0331cb106f3c49b6e3c63fd9a";
        $twilio = new Client($sid, $token);
        foreach ($contacts as $mobile) {
            $message = $twilio->messages
                ->create(
                    "+" . $mobile, // to
                    [
                        "body" => $data["sms_text"],
                        "from" => "+12056724888",
                        "statusCallback" => $callbackurl
                    ]
                );
            print($message->sid);
        }
    }

    public static function getVisitorOs()
    {
        $osList = array(
            /* -- WINDOWS -- */
            'Windows 10 (Windows NT 10.0)' => 'windows nt 10.0',
            'Windows 8.1 (Windows NT 6.3)' => 'windows nt 6.3',
            'Windows 8 (Windows NT 6.2)' => 'windows nt 6.2',
            'Windows 7 (Windows NT 6.1)' => 'windows nt 6.1',
            'Windows Vista (Windows NT 6.0)' => 'windows nt 6.0',
            'Windows Server 2003 (Windows NT 5.2)' => 'windows nt 5.2',
            'Windows XP (Windows NT 5.1)' => 'windows nt 5.1',
            'Windows 2000 sp1 (Windows NT 5.01)' => 'windows nt 5.01',
            'Windows 2000 (Windows NT 5.0)' => 'windows nt 5.0',
            'Windows NT 4.0' => 'windows nt 4.0',
            'Windows Me  (Windows 9x 4.9)' => 'win 9x 4.9',
            'Windows 98' => 'windows 98',
            'Windows 95' => 'windows 95',
            'Windows CE' => 'windows ce',
            'Windows (version unknown)' => 'windows',
            /* -- MAC OS X -- */
            'Mac OS X Beta (Kodiak)' => 'Mac OS X beta',
            'Mac OS X Cheetah' => 'Mac OS X 10.0',
            'Mac OS X Puma' => 'Mac OS X 10.1[^0-9]',
            'Mac OS X Jaguar' => 'Mac OS X 10.2',
            'Mac OS X Panther' => 'Mac OS X 10.3',
            'Mac OS X Tiger' => 'Mac OS X 10.4',
            'Mac OS X Leopard' => 'Mac OS X 10.5',
            'Mac OS X Snow Leopard' => 'Mac OS X 10.6',
            'Mac OS X Lion' => 'Mac OS X 10.7',
            'Mac OS X Mountain Lion' => 'Mac OS X 10.8',
            'Mac OS X Mavericks' => 'Mac OS X 10.9',
            'Mac OS X Yosemite' => 'Mac OS X 10.10',
            'Mac OS X El Capitan' => 'Mac OS X 10.11',
            'macOS Sierra' => 'Mac OS X 10.12',
            'Mac OS X (version unknown)' => 'Mac OS X',
            'Mac OS (classic)' => '(mac_powerpc)|(macintosh)',
            /* -- OTHERS -- */
            'OpenBSD' => 'openbsd',
            'SunOS' => 'sunos',
            'Ubuntu' => 'ubuntu',
            'Linux (or Linux based)' => '(linux)|(x11)',
            'QNX' => 'QNX',
            'BeOS' => 'beos',
            'OS2' => 'os/2',
            'SearchBot' => '(nuhk)|(googlebot)|(yammybot)|(openbot)|(slurp)|(msnbot)|(ask jeeves/teoma)|(ia_archiver)'
        );

        $useragent = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
        $useragent = strtolower($useragent);

        foreach ($osList as $os => $match) {
            if (preg_match('/' . $match . '/i', $useragent)) {
                break;
            } else {
                $os = "Unknown";
            }
        }
        return $os;
    }

    public static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function sendSms($data)
    {
        /*format
        $data['userid] is user id of sender
        $data['senderid'] is the actual from address using which sms will be sent
        $data['smstype'] usually text but could be unicode etc
        $data['smstext'] message to be sent
        $data['routeid'] id of the route to be sed for sending sms
        $data['mobiles'] comma separated mobile numbers
        */

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $akobj = Doo::loadModel('ScApiKeys', true);
        $api_key = $akobj->getApiKey($data['userid']); //sender user id
        $smstext = $data['smstext'];
        $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=0&routeid=' . $data['routeid'] . '&type=' . $data['smstype'] . '&contacts=' . $data['mobiles'] . '&senderid=' . $data['senderid'] . '&msg=' . urlencode($smstext);

        $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
        return $response;
    }

    public static function sendEmail($data)
    {
        /*format
        $data['sendermail'] is email of sender, the from address
        $data['sendername'] is the name that will display as from
        $data['receivermail] is the TO address, the destination email
        $data['subject'] is email subject
        $data['mailbody'] is the string html that will be sent
        */

        $mail = new PHPMailer(true);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPDebug = 0; //0 - silent 2 = echo all
        $mail->isSMTP();
        $mail->Host = $data['smtpHost'];
        $mail->SMTPAuth = true;
        $mail->Username = $data['smtpUsername'];
        $mail->Password = $data['smtpPassword'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $data['smtpPort'];

        $mail->setFrom($data['senderEmail'], $data['senderName']);
        $mail->addAddress($data['receiverEmail']);
        $mail->Subject  = $data['subject'];
        $mail->isHTML(true);
        $mail->Body = $data['mailbody'];

        $mail->send();
    }


    public function getUserTree($userid)
    {
        $ulist = array();
        $userobj = Doo::loadModel('ScUsers', true);
        $userobj->upline_id = $userid;
        $downlines = Doo::db()->find($userobj, array('select' => 'user_id,category'));
        //var_dump($downlines);die;
        if (sizeof($downlines) > 0) {
            foreach ($downlines as $usr) {
                if ($usr->category == 'client') {
                    array_push($ulist, $usr->user_id);
                } else {
                    $newdl = $this->getUserTree($usr->user_id);
                    $ulist = array_merge($ulist, $newdl);
                }
            }
            array_push($ulist, $userid);
        } else {
            array_push($ulist, $userid);
        }
        return $ulist;
    }

    public static function getCountryIso($msisdn)
    {
        $ccodes_3len = array(
            998 => "UZ",
            996 => "KG",
            995 => "GE",
            994 => "AZ",
            993 => "TM",
            992 => "TJ",
            977 => "NP",
            976 => "MN",
            975 => "BT",
            974 => "QA",
            973 => "BH",
            972 => "IL",
            971 => "AE",
            970 => "PS",
            968 => "OM",
            967 => "YE",
            966 => "SA",
            965 => "KW",
            964 => "IQ",
            963 => "SY",
            962 => "JO",
            961 => "LB",
            960 => "MV",
            886 => "TW",
            880 => "BD",
            856 => "LA",
            855 => "KH",
            853 => "MO",
            852 => "HK",
            850 => "KP",
            692 => "MH",
            691 => "FM",
            690 => "TK",
            689 => "PF",
            688 => "TV",
            687 => "NC",
            686 => "KI",
            685 => "WS",
            683 => "NU",
            682 => "CK",
            681 => "WF",
            680 => "PW",
            679 => "FJ",
            678 => "VU",
            677 => "SB",
            676 => "TO",
            675 => "PG",
            674 => "NR",
            673 => "BN",
            672 => "NF",
            670 => "TL",
            599 => "BQ",
            598 => "UY",
            597 => "SR",
            596 => "MQ",
            595 => "PY",
            594 => "GF",
            593 => "EC",
            591 => "BO",
            590 => "BL",
            509 => "HT",
            508 => "PM",
            507 => "PA",
            506 => "CR",
            505 => "NI",
            504 => "HN",
            503 => "SV",
            502 => "GT",
            501 => "BZ",
            500 => "FK",
            423 => "LI",
            421 => "SK",
            420 => "CZ",
            389 => "MK",
            387 => "BA",
            386 => "SI",
            385 => "HR",
            383 => "XK",
            382 => "ME",
            381 => "RS",
            380 => "UA",
            378 => "SM",
            377 => "MC",
            376 => "AD",
            375 => "BY",
            374 => "AM",
            373 => "MD",
            372 => "EE",
            371 => "LV",
            370 => "LT",
            359 => "BG",
            358 => "FI",
            357 => "CY",
            356 => "MT",
            355 => "AL",
            354 => "IS",
            353 => "IE",
            352 => "LU",
            351 => "PT",
            350 => "GI",
            299 => "GL",
            298 => "FO",
            297 => "AW",
            291 => "ER",
            290 => "SH",
            269 => "KM",
            268 => "SZ",
            267 => "BW",
            266 => "LS",
            265 => "MW",
            264 => "NA",
            263 => "ZW",
            262 => "TF",
            261 => "MG",
            260 => "ZM",
            258 => "MZ",
            257 => "BI",
            256 => "UG",
            255 => "TZ",
            254 => "KE",
            253 => "DJ",
            252 => "SO",
            251 => "ET",
            250 => "RW",
            249 => "SD",
            248 => "SC",
            245 => "GW",
            244 => "AO",
            243 => "CD",
            242 => "CG",
            241 => "GA",
            240 => "GQ",
            239 => "ST",
            238 => "CV",
            237 => "CM",
            236 => "CF",
            235 => "TD",
            234 => "NG",
            233 => "GH",
            232 => "SL",
            231 => "LR",
            230 => "MU",
            229 => "BJ",
            228 => "TG",
            227 => "NE",
            226 => "BF",
            225 => "CI",
            224 => "GN",
            223 => "ML",
            222 => "MR",
            221 => "SN",
            220 => "GM",
            218 => "LY",
            216 => "TN",
            213 => "DZ",
            212 => "MA"
        );
        $ccodes_2len = array(
            98 => "IR",
            95 => "MM",
            94 => "LK",
            93 => "AF",
            92 => "PK",
            91 => "IN",
            90 => "TR",
            86 => "CN",
            84 => "VN",
            82 => "KR",
            81 => "JP",
            79 => "RU",
            66 => "TH",
            65 => "SG",
            64 => "NZ",
            63 => "PH",
            62 => "ID",
            61 => "AU",
            60 => "MY",
            58 => "VE",
            57 => "CO",
            56 => "CL",
            55 => "BR",
            54 => "AR",
            53 => "CU",
            52 => "MX",
            51 => "PE",
            49 => "DE",
            48 => "PL",
            47 => "NO",
            46 => "SE",
            45 => "DK",
            44 => "GB",
            43 => "AT",
            41 => "CH",
            40 => "RO",
            39 => "IT",
            36 => "HU",
            34 => "ES",
            33 => "FR",
            32 => "BE",
            31 => "NL",
            30 => "GR",
            27 => "ZA",
            20 => "EG"
        );
        $ccodes_1len = array(
            7 => "KZ",
            1 => "US"
        );
        $iso = '';

        //check 3 length
        $prefix_3 = substr($msisdn, 0, 3);
        $iso = $ccodes_3len[$prefix_3];
        if (!isset($iso) || $iso == '') {
            $prefix_2 = substr($msisdn, 0, 2);
            $iso = $ccodes_2len[$prefix_2];
            if (!isset($iso) || $iso == '') {
                $prefix_1 = substr($msisdn, 0, 1);
                $iso = $ccodes_1len[$prefix_1];
            }
        }
        return $iso;
    }

    /*
Takes in all contacts and outputs arrays of contacts as associative array with details like dynamic text, sms count etc
*/

    public function sortContacts($data, $params)
    {
        //tasks outside the loop
        $replaceurl = 0;
        $dupcount = 0;
        $totalsubmitted = 0;
        $totalsmscount = 0; //total number of contacts * persmscount, this would be credits required for credit based account
        $dynamicsmstotal = 0; //dynamic sms: now the length of the sms is going to be different because for each contact the sms text is changing
        $dynurls = array();
        $finalcontacts = array();
        $uploadcontacts = array();
        $invalidcontacts = array();
        $blmatchedcontacts = array();
        $optout_contacts = array();
        $uniquecontacts = array();
        $dlrfilcontacts = array();
        $droppedcontacts = array();
        $fakedlrcontacts_del = array();
        $fakedlrcontacts_undel = array();
        $fakedlrcontacts_exp = array();
        $routewisecontacts = array(); // this is for currency based account
        $currencybasecost = 0; // this is the total cost of all the sms for currency based account
        $factor = $params['ratefactor']; //this has sms price in case of currency based route account(not mccmnc)
        Doo::loadHelper('DooTextHelper');
        Doo::loadHelper('DooFile');
        $fhobj = new DooFile;
        $blmatcharray = array();
        //check if dynamic url is present
        //check if personalize
        $tinyurl = DooTextHelper::getTinyUrl($params['userid']);
        $pos = strpos($params["smstext"], $tinyurl);
        if ($params['smstype']['personalize'] == 1 && $pos !== false) {
            $urlidf = substr($params["smstext"], $pos + strlen($tinyurl) + 1, 6);
            $turlobj = Doo::loadModel('ScShortUrlsMaster', true);
            $turlobj->url_idf = $urlidf;
            $urldata = Doo::db()->find($turlobj, array('select' => 'id,type', 'limit' => 1));

            if (intval($urldata->type) != 0) {
                $replaceurl = 1;
            }
        }

        if ($data['phonebookFlag'] == 1) {
            $phonebooknums = $this->getPhonebookContacts($data, $params);
            $blacklistFilter = 0;
            if ($params['routedata']->blacklist_ids != '') {
                $blacklistFilter = 1;
                $dbs = explode(",", $params['routedata']->blacklist_ids);
                $getBldbParams = $this->getBlacklistClassname($dbs[0]);
            }
            $blmatcharray = array();
            foreach ($phonebooknums as $phn) {
                $mdata = array();
                //1. duplicate check
                if ($data['duplicateFlag'] == 1) {
                    if (isset($uniquecontacts[$phn->mobile])) {
                        $dupcount++;
                        continue;
                    }
                    $uniquecontacts[$phn->mobile] = true;
                }

                if ($params['account_type'] == 1) {
                    $mobile = intval($phn->mobile);
                    $mdata['mobile'] = $mobile;
                    $mdata['text'] = '';
                    $mdata['smslen'] = 0; //counted later
                    $mdata['smscount'] = 0; //counted later
                    //currency based user, get mccmnc and route and per sms cost
                    //2. Get country ISO based on prefix
                    $iso = DooSmppcubeHelper::getCountryIso($mobile);
                    if ($iso == '') {
                        //invalid number
                        array_push($invalidcontacts, $mdata);
                        continue;
                    } else {
                        //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                        //get coverage details like prefix and NSN prefix length
                        $covdata = explode('|', $params['coverages'][$iso]);
                        //4. Based on operator prefix get MCCMNC code
                        $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                        $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                        //5. get price for the mcc mnc according to this plan
                        $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                        $persmsprice = floatval($routecostar[1]);
                        $routeid = intval($routecostar[0]);
                        if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                            //no mccmnc matched, use default route pricing
                            $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                            $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                            $mdata['routeid'] = $routeid;
                        }
                        $mdata['smscost'] = $persmsprice;
                        $mdata['routeid'] = $routeid;
                        $mdata['mccmnc'] = $mccmnc;
                        //3. Replace URL if applicable
                        if ($replaceurl == 1) {
                            $uid = $params['userid'];
                            $turl = $this->generateUrlIdf($uid, 7);
                            $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                            //prepare the sql string
                            $durl = array();
                            $durl['parent_url_id'] = $urldata->id;
                            $durl['url_idf'] = $turl;
                            $durl['sms_shoot_id'] = $params['shootid'];
                            $durl['mobile'] = $mobile;
                            array_push($dynurls, $durl);
                        } else {
                            $dyn_text = $params['smstext'];
                        }
                        $mdata['mobile'] = $mobile;
                        $mdata['text'] = $dyn_text;
                        //get sms cost based on sms count
                        $creditruleid = $params['routes'][$routeid]['credit_rule'];
                        $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                        $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                        $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                        $spcl_ccrule = $spcl_rule['counts'];
                        $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $dyn_text);
                        $mdata['smslen'] = $countdata['length'];
                        $mdata['smscount'] = $countdata['count'];
                        $smscost = $mdata['smscost'] * intval($countdata['count']);
                        $mdata['smscost'] = $smscost;

                        if (intval($routeid) != 0) {
                            //6. Invalid check
                            if ($data['invalidFlag'] == 1) {
                                if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                }
                            }
                            //7. Blacklist filter
                            //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                            if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                            array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                            //8. optout filter
                            if (isset($params['optouts'][$mobile])) {
                                array_push($optout_contacts, $mdata);
                                continue;
                            }
                        } else {
                            //unable to match any routes consider this invalid
                            array_push($invalidcontacts, $mdata);
                            continue;
                        }

                        //7. Save data in array so the final contacts as grouped based on route id
                        if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                        $currencybasecost += $smscost;
                        $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                        $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                        $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                        array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                    }
                } else {
                    //credit based account
                    //2. Add country prefix if applicable
                    if ($params['routedata']->add_pre != '0') {
                        $mobile = strlen($phn->mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $phn->mobile) :  intval($phn->mobile);
                    } else {
                        $mobile = intval($phn->mobile);
                    }
                    //3. Replace URL if applicable
                    if ($replaceurl == 1) {
                        $uid = $params['userid'];
                        $turl = $this->generateUrlIdf($uid, 7);
                        $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                        //prepare the sql string
                        $durl = array();
                        $durl['parent_url_id'] = $urldata->id;
                        $durl['url_idf'] = $turl;
                        $durl['sms_shoot_id'] = $params['shootid'];
                        $durl['mobile'] = $mobile;
                        array_push($dynurls, $durl);
                    } else {
                        $dyn_text = $params['smstext'];
                    }
                    $mdata['mobile'] = $mobile;
                    $mdata['text'] = $dyn_text;
                    $mdata['smslen'] = 0; //counted later
                    $mdata['smscount'] = 0; //counted later
                    $mdata['smscost'] = 0; //counted later

                    //4. get dynamic sms text and sms count
                    if ($params['smstype']['personalize'] == 1) {
                        $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $dyn_text);
                        $mdata['smslen'] = $countdata['length'];
                        $mdata['smscount'] = $countdata['count'];
                        $mdata['smscost'] = $countdata['count'] * $factor;
                    }
                    //5. invalid check
                    if ($data['invalidFlag'] == 1) {
                        if (!DooTextHelper::verifyFormData('mobile', $phn->mobile, $params['validlengths'])) {
                            array_push($invalidcontacts, $mdata);
                            continue;
                        }
                    }
                    //6. optout filter
                    if (isset($params['optouts'][$mobile])) {
                        array_push($optout_contacts, $mdata);
                        continue;
                    }
                    $dynamicsmstotal += $mdata['smscost'];
                    array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                    array_push($finalcontacts, $mdata);
                }
            }
            //6. Check and remove blacklist
            if (sizeof($blmatcharray) > 0) {
                if ($params['account_type'] == 1) {
                    //filter blacklist for multiple routes
                    foreach ($blmatcharray as $rid => $mobdata) {
                        $blstr = implode(",", $mobdata['mobiles']);
                        if ($params['routes'][$rid]['blacklist_ids'] != '' && $blstr != '') {
                            $dbs = explode(",", $params['routes'][$rid]['blacklist_ids']);
                            $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                            $blacklistFilterQuery = 'SELECT id,' . $getBldbParams[1] . ' FROM ' . $getBldbParams[0] . ' WHERE ' . $getBldbParams[1] . ' IN (' . $blstr . ')';
                            $blacklistMatched = Doo::db()->fetchAll($blacklistFilterQuery, null, PDO::FETCH_KEY_PAIR, null, array(), 2);
                            $filterData = $this->flip_isset_diff($routewisecontacts[$rid]['mobiles'], $blacklistMatched);
                            $routewisecontacts[$rid]['mobiles'] = $filterData[0];
                            $currencybasecost -= $filterData[2];
                            $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) - $filterData[2];
                            $blmatchedcontacts = array_merge($blmatchedcontacts, $filterData[1]);
                        }
                    }
                } else {
                    $blstr = implode(",", $blmatcharray);
                    if ($blacklistFilter == 1) {
                        $blacklistFilterQuery = 'SELECT id,' . $getBldbParams[1] . ' FROM ' . $getBldbParams[0] . ' WHERE ' . $getBldbParams[1] . ' IN (' . $blstr . ')';
                        $blacklistMatched = Doo::db()->fetchAll($blacklistFilterQuery, null, PDO::FETCH_KEY_PAIR, null, array(), 2);
                        $filterData = $this->flip_isset_diff($finalcontacts, $blacklistMatched);
                        $finalcontacts = $filterData[0];
                        $blmatchedcontacts = $filterData[1];
                        //deduct credits charged for blacklist contacts
                        $dynamicsmstotal -= $filterData[2];
                    }
                }
            }
            //perform after loop tasks
            if (sizeof($dynurls) > 0) {
                $dynturlobj = Doo::loadModel('ScShortUrlsMsisdnMap', true);
                $dynturlobj->addData($dynurls);
            }
        } else {
            //contacts supplied by user
            //loop through each mode of contact supply and perform above 4 tasks for each mode
            //read text input only if non-dynamic sms
            if ($params['smstype']['personalize'] != 1) {
                if (is_countable($data['inputbox']) && sizeof($data['inputbox']) > 0) {
                    $blacklistFilter = 0;
                    if ($params['routedata']->blacklist_ids != '') {
                        $blacklistFilter = 1;
                        $dbs = explode(",", $params['routedata']->blacklist_ids);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                    }
                    $blmatcharray = array();
                    foreach ($data['inputbox'] as $txtcel) {
                        $mdata = array();
                        $mobile = intval($txtcel);
                        if ($mobile != 0) {
                            $totalsubmitted++;
                            //1. duplicate check
                            if ($data['duplicateFlag'] == 1) {
                                if (isset($uniquecontacts[$mobile])) {
                                    $dupcount++;
                                    continue;
                                }
                                $uniquecontacts[$mobile] = true;
                            }
                            $mdata['mobile'] = $mobile;
                            $mdata['text'] = '';
                            $mdata['smslen'] = 0; //counted later
                            $mdata['smscount'] = 0; //counted later
                            $mdata['smscost'] = 0; //counted later
                            if ($params['account_type'] == 1) {

                                //currency based user, get mccmnc and route and per sms cost
                                //2. Get country ISO based on prefix
                                $iso = DooSmppcubeHelper::getCountryIso($mobile);
                                if ($iso == '') {
                                    //invalid number
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                } else {
                                    //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                    //get coverage details like prefix and NSN prefix length
                                    $covdata = explode('|', $params['coverages'][$iso]);
                                    //4. Based on operator prefix get MCCMNC code
                                    $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                    $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                    //5. get price for the mcc mnc according to this plan
                                    $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                    $persmsprice = floatval($routecostar[1]);
                                    $routeid = intval($routecostar[0]);
                                    if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                        //no mccmnc matched, use default route pricing
                                        $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                        $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                    }
                                    $mdata['smscost'] = $persmsprice;
                                    $mdata['routeid'] = $routeid;
                                    $mdata['mccmnc'] = $mccmnc;

                                    //get sms cost based on sms count
                                    $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                    $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                    $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                    $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                    $spcl_ccrule = $spcl_rule['counts'];
                                    $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $params['smstext']);
                                    $mdata['smslen'] = $countdata['length'];
                                    $mdata['smscount'] = $countdata['count'];
                                    $smscost = $mdata['smscost'] * intval($countdata['count']);
                                    $mdata['smscost'] = $smscost;
                                    if (intval($routeid) != 0) {
                                        //6. Invalid check
                                        if ($data['invalidFlag'] == 1) {
                                            if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                                array_push($invalidcontacts, $mdata);
                                                continue;
                                            }
                                        }
                                        //6.1. optout filter
                                        if (isset($params['optouts'][$mobile])) {
                                            array_push($optout_contacts, $mdata);
                                            continue;
                                        }
                                        //7. Blacklist filter
                                        //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                        if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                        array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                    } else {
                                        //unable to match any routes consider this invalid
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    }

                                    //7. Save data in array so the final contacts as grouped based on route id
                                    if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                    $currencybasecost += $smscost;
                                    $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                    $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                    $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                    array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                                }
                            } else {
                                //credit based system, proceed as usual
                                //2. Add country prefix if applicable
                                if ($params['routedata']->add_pre != '0') {
                                    $mobile = strlen($mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $mobile) :  $mobile;
                                }
                                $mdata['mobile'] = $mobile;
                                //3. invalid check
                                if ($data['invalidFlag'] == 1) {
                                    if (!DooTextHelper::verifyFormData('mobile', $mobile, $params['validlengths'])) {
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    }
                                }
                                //4. optout filter
                                if (isset($params['optouts'][$mobile])) {
                                    array_push($optout_contacts, $mdata);
                                    continue;
                                }

                                array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                                array_push($finalcontacts, $mdata);
                            }
                        }
                    }
                }
            }
            //read contact group data for both dynamic and non dynamic sms
            if (is_countable($data['groupdata']) && sizeof($data['groupdata']) > 0) {
                $grp_ids = $params['smstype']['personalize'] == 1 ? intval($data['groupdata'][0]) : implode(",", $data['groupdata']);
                if ($grp_ids != 0) {
                    $ucobj = Doo::loadModel('ScUserContacts', true);
                    $group_contacts = $ucobj->getGroupsContact($grp_ids);
                    $blacklistFilter = 0;
                    if ($params['routedata']->blacklist_ids != '') {
                        $blacklistFilter = 1;
                        $dbs = explode(",", $params['routedata']->blacklist_ids);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                    }
                    $blmatcharray = array();
                    foreach ($group_contacts as $grpdata) {
                        $totalsubmitted++;
                        $mdata = array();
                        $grpcel = intval(trim($grpdata->mobile));
                        //1. duplicate check
                        if ($data['duplicateFlag'] == 1) {
                            if (isset($uniquecontacts[$grpcel])) {
                                $dupcount++;
                                continue;
                            }
                            $uniquecontacts[$grpcel] = true;
                        }

                        if ($params['account_type'] == 1) {
                            //currency based user, get mccmnc and route and per sms cost
                            //2. Get country ISO based on prefix
                            $mobile = $grpcel;
                            $iso = DooSmppcubeHelper::getCountryIso($mobile);
                            $mdata['mobile'] = $mobile;
                            $mdata['text'] = $params['smstext'];
                            if ($iso == '') {
                                //invalid number
                                array_push($invalidcontacts, $mdata);
                                continue;
                            } else {
                                //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                //get coverage details like prefix and NSN prefix length
                                $covdata = explode('|', $params['coverages'][$iso]);
                                //4. Based on operator prefix get MCCMNC code
                                $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                //5. get price for the mcc mnc according to this plan
                                $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                $persmsprice = floatval($routecostar[1]);
                                $routeid = intval($routecostar[0]);
                                if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                    //no mccmnc matched, use default route pricing
                                    $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                    $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                }
                                $mdata['smscost'] = $persmsprice;
                                $mdata['routeid'] = $routeid;
                                $mdata['mccmnc'] = $mccmnc;

                                //3. Replace URL if applicable
                                if ($replaceurl == 1) {
                                    $uid = $params['userid'];
                                    $turl = $this->generateUrlIdf($uid, 7);
                                    $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                    //prepare the sql string
                                    $durl = array();
                                    $durl['parent_url_id'] = $urldata->id;
                                    $durl['url_idf'] = $turl;
                                    $durl['sms_shoot_id'] = $params['shootid'];
                                    $durl['mobile'] = $mobile;
                                    array_push($dynurls, $durl);
                                } else {
                                    $dyn_text = $params['smstext'];
                                }
                                $mdata['mobile'] = $mobile;
                                $mdata['text'] = $dyn_text;

                                //4. Get dynamic SMS if applicable
                                //get credit rule data
                                $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                $spcl_ccrule = $spcl_rule['counts'];

                                if ($params['smstype']['personalize'] == 1) {
                                    $dyn_a = str_replace("#A#", $mobile, $dyn_text);
                                    $dyn_b = str_replace("#B#", $grpdata->name, $dyn_a);
                                    $dyn_c = str_replace("#C#", $grpdata->varC, $dyn_b);
                                    $dyn_d = str_replace("#D#", $grpdata->varD, $dyn_c);
                                    $dyn_e = str_replace("#E#", $grpdata->varE, $dyn_d);
                                    $dyn_f = str_replace("#F#", $grpdata->varF, $dyn_e);
                                    $finaltext = str_replace("#G#", $grpdata->varG, $dyn_f);
                                    $mdata['text'] = $finaltext;
                                    $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $finaltext);
                                    $mdata['smslen'] = $countdata['length'];
                                    $mdata['smscount'] = $countdata['count'];
                                    $smscost = $mdata['smscost'] * intval($countdata['count']);
                                } else {
                                    $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $params['smstext']);
                                    $mdata['smslen'] = $countdata['length'];
                                    $mdata['smscount'] = $countdata['count'];
                                    $smscost = $mdata['smscost'] * intval($countdata['count']);
                                }
                                $mdata['smscost'] = $smscost;
                                if (intval($routeid) != 0) {
                                    //6. Invalid check
                                    if ($data['invalidFlag'] == 1) {
                                        if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                            array_push($invalidcontacts, $mdata);
                                            continue;
                                        }
                                    }
                                    //6.1. optout filter
                                    if (isset($params['optouts'][$mobile])) {
                                        array_push($optout_contacts, $mdata);
                                        continue;
                                    }
                                    //7. Blacklist filter
                                    //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                    if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                    array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                } else {
                                    //unable to match any routes consider this invalid
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                }

                                //7. Save data in array so the final contacts as grouped based on route id
                                if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                $currencybasecost += $smscost;
                                $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                            }
                        } else {
                            //credit based account
                            //2. Add country prefix if applicable
                            if ($params['routedata']->add_pre != '0') {
                                $mobile = strlen($grpcel) < max($params['validlengths']) ? intval($params['countryPrefix'] . $grpcel) :  $grpcel;
                            } else {
                                $mobile = $grpcel;
                            }
                            //3. Replace URL if applicable
                            if ($replaceurl == 1) {
                                $uid = $params['userid'];
                                $turl = $this->generateUrlIdf($uid, 7);
                                $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                //prepare the sql string
                                $durl = array();
                                $durl['parent_url_id'] = $urldata->id;
                                $durl['url_idf'] = $turl;
                                $durl['sms_shoot_id'] = $params['shootid'];
                                $durl['mobile'] = $mobile;
                                array_push($dynurls, $durl);
                            } else {
                                $dyn_text = $params['smstext'];
                            }
                            $mdata['mobile'] = $mobile;
                            $mdata['text'] = $dyn_text;
                            $mdata['smslen'] = 0; //counted later
                            $mdata['smscount'] = 0; //counted later
                            $mdata['smscost'] = 0; //counted later

                            //4. Get dynamic SMS if applicable
                            if ($params['smstype']['personalize'] == 1) {
                                $dyn_a = str_replace("#A#", $mobile, $dyn_text);
                                $dyn_b = str_replace("#B#", $grpdata->name, $dyn_a);
                                $dyn_c = str_replace("#C#", $grpdata->varC, $dyn_b);
                                $dyn_d = str_replace("#D#", $grpdata->varD, $dyn_c);
                                $dyn_e = str_replace("#E#", $grpdata->varE, $dyn_d);
                                $dyn_f = str_replace("#F#", $grpdata->varF, $dyn_e);
                                $finaltext = str_replace("#G#", $grpdata->varG, $dyn_f);
                                $mdata['text'] = $finaltext;
                                $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $finaltext);
                                $mdata['smslen'] = $countdata['length'];
                                $mdata['smscount'] = $countdata['count'];
                                $mdata['smscost'] = $countdata['count'] * $factor;
                            }
                            //5. invalid check
                            if ($data['invalidFlag'] == 1) {
                                if (!DooTextHelper::verifyFormData('mobile', $mobile, $params['validlengths'])) {
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                }
                            }
                            //6. optout filter
                            if (isset($params['optouts'][$mobile])) {
                                array_push($optout_contacts, $mdata);
                                continue;
                            }

                            $dynamicsmstotal += $mdata['smscost'];
                            array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                            array_push($finalcontacts, $mdata);
                        }
                    }
                }
            }
            //read supplied file and read data for each file type
            if ($data['filedata']['name'] != '') {
                $filepath = Doo::conf()->global_upload_dir . $data['filedata']['name'];
                $ext = strtolower($fhobj->getFileExtensionFromPath($filepath, true));

                //Only one of the following cases will be true as file can only be one type
                //LOOP THROUGH EXCEL FILE
                if ($ext == 'xlsx') {
                    Doo::loadHelper('DooSpoutExcel');
                    $reader = DooSpoutExcel::getReader('xlsx');
                    $reader->setShouldFormatDates(true);
                    $reader->open($filepath);
                    $mobile_index = ord(strtoupper($data['filedata']['column'])) - ord('A'); //this gets index of alphabet
                    $blacklistFilter = 0;
                    if ($params['routedata']->blacklist_ids != '') {
                        $blacklistFilter = 1;
                        $dbs = explode(",", $params['routedata']->blacklist_ids);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                    }
                    $blmatcharray = array();
                    foreach ($reader->getSheetIterator() as $sheet) {
                        if ($sheet->getName() == $data['filedata']['sheet']) {
                            foreach ($sheet->getRowIterator() as $n => $row) {
                                if ($n > 100000) {
                                    break;
                                }
                                $mdata = array();
                                // echo '<pre>'; var_dump(get_class_methods($row->getCellAtIndex($mobile_index)));
                                $mobile = $org_mobile = intval(trim($row->getCellAtIndex($mobile_index)->getValue()));

                                if ($mobile != 0) {
                                    $totalsubmitted++;
                                    //1. duplicate check
                                    if ($data['duplicateFlag'] == 1) {
                                        if (isset($uniquecontacts[$mobile])) {
                                            $dupcount++;
                                            continue;
                                        }
                                        $uniquecontacts[$mobile] = true;
                                    }
                                    if ($params['account_type'] == 1) {
                                        //currency based user, get mccmnc and route and per sms cost
                                        //2. Get country ISO based on prefix
                                        $iso = DooSmppcubeHelper::getCountryIso($mobile);
                                        $mdata['mobile'] = $mobile;
                                        $mdata['text'] = $params['smstext'];
                                        if ($iso == '') {
                                            //invalid number
                                            array_push($invalidcontacts, $mdata);
                                            continue;
                                        } else {
                                            //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                            //get coverage details like prefix and NSN prefix length
                                            $covdata = explode('|', $params['coverages'][$iso]);
                                            //4. Based on operator prefix get MCCMNC code
                                            $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                            $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                            //5. get price for the mcc mnc according to this plan
                                            $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                            $persmsprice = floatval($routecostar[1]);
                                            $routeid = intval($routecostar[0]);
                                            if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                                //no mccmnc matched, use default route pricing
                                                $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                                $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                            }
                                            $mdata['smscost'] = $persmsprice;
                                            $mdata['routeid'] = $routeid;
                                            $mdata['mccmnc'] = $mccmnc;

                                            //3. Replace URL if applicable
                                            if ($replaceurl == 1) {
                                                $uid = $params['userid'];
                                                $turl = $this->generateUrlIdf($uid, 7);
                                                $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                                //prepare the sql string
                                                $durl = array();
                                                $durl['parent_url_id'] = $urldata->id;
                                                $durl['url_idf'] = $turl;
                                                $durl['sms_shoot_id'] = $params['shootid'];
                                                $durl['mobile'] = $mobile;
                                                array_push($dynurls, $durl);
                                            } else {
                                                $dyn_text = $params['smstext'];
                                            }
                                            $mdata['mobile'] = $mobile;
                                            $mdata['text'] = $dyn_text;

                                            //4. Get dynamic SMS if applicable
                                            //get credit rule data
                                            $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                            $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                            $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                            $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                            $spcl_ccrule = $spcl_rule['counts'];

                                            if ($params['smstype']['personalize'] == 1) {
                                                $dyn_a = str_replace('#A#', ($row->getCellAtIndex(0) ? $row->getCellAtIndex(0)->getValue() : ''), $dyn_text);
                                                $dyn_b = str_replace('#B#', ($row->getCellAtIndex(1) ? $row->getCellAtIndex(1)->getValue() : ''), $dyn_a);
                                                $dyn_c = str_replace('#C#', ($row->getCellAtIndex(2) ? $row->getCellAtIndex(2)->getValue() : ''), $dyn_b);
                                                $dyn_d = str_replace('#D#', ($row->getCellAtIndex(3) ? $row->getCellAtIndex(3)->getValue() : ''), $dyn_c);
                                                $dyn_e = str_replace('#E#', ($row->getCellAtIndex(4) ? $row->getCellAtIndex(4)->getValue() : ''), $dyn_d);
                                                $dyn_f = str_replace('#F#', ($row->getCellAtIndex(5) ? $row->getCellAtIndex(5)->getValue() : ''), $dyn_e);
                                                $dyn_g = str_replace('#G#', ($row->getCellAtIndex(6) ? $row->getCellAtIndex(6)->getValue() : ''), $dyn_f);
                                                $dyn_h = str_replace('#H#', ($row->getCellAtIndex(7) ? $row->getCellAtIndex(7)->getValue() : ''), $dyn_g);
                                                $dyn_i = str_replace('#I#', ($row->getCellAtIndex(8) ? $row->getCellAtIndex(8)->getValue() : ''), $dyn_h);
                                                $dyn_j = str_replace('#J#', ($row->getCellAtIndex(9) ? $row->getCellAtIndex(9)->getValue() : ''), $dyn_i);
                                                $finaltext = str_replace('#K#', ($row->getCellAtIndex(10) ? $row->getCellAtIndex(10)->getValue() : ''), $dyn_j);
                                                $mdata['text'] = $finaltext;
                                                $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $finaltext);
                                                $mdata['smslen'] = $countdata['length'];
                                                $mdata['smscount'] = $countdata['count'];
                                                $smscost = $mdata['smscost'] * intval($countdata['count']);
                                            } else {
                                                $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $dyn_text);
                                                $mdata['smslen'] = $countdata['length'];
                                                $mdata['smscount'] = $countdata['count'];
                                                $smscost = $mdata['smscost'] * intval($countdata['count']);
                                            }
                                            $mdata['smscost'] = $smscost;
                                            if (intval($routeid) != 0) {
                                                //6. Invalid check
                                                if ($data['invalidFlag'] == 1) {
                                                    if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                                        array_push($invalidcontacts, $mdata);
                                                        continue;
                                                    }
                                                }
                                                //6.1. optout filter
                                                if (isset($params['optouts'][$mobile])) {
                                                    array_push($optout_contacts, $mdata);
                                                    continue;
                                                }
                                                //7. Blacklist filter
                                                //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                                if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                                array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                            } else {
                                                //unable to match any routes consider this invalid
                                                array_push($invalidcontacts, $mdata);
                                                continue;
                                            }

                                            //7. Save data in array so the final contacts as grouped based on route id
                                            if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                            $currencybasecost += $smscost;
                                            $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                            $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                            $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                            array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                                        }
                                    } else {
                                        //credit based account
                                        //2. Add country prefix if applicable
                                        if ($params['routedata']->add_pre != '0') {
                                            $mobile = strlen($mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $mobile) :  $mobile;
                                        }
                                        //3. Replace URL if applicable
                                        if ($replaceurl == 1) {
                                            $uid = $params['userid'];
                                            $turl = $this->generateUrlIdf($uid, 7);
                                            $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                            //prepare the sql string
                                            $durl = array();
                                            $durl['parent_url_id'] = $urldata->id;
                                            $durl['url_idf'] = $turl;
                                            $durl['sms_shoot_id'] = $params['shootid'];
                                            $durl['mobile'] = $mobile;
                                            array_push($dynurls, $durl);
                                        } else {
                                            $dyn_text = $params['smstext'];
                                        }
                                        $mdata['mobile'] = $mobile;
                                        $mdata['text'] = $dyn_text;
                                        $mdata['smslen'] = 0; //counted later
                                        $mdata['smscount'] = 0; //counted later
                                        $mdata['smscost'] = 0; //counted later

                                        //4. Get dynamic SMS if applicable
                                        if ($params['smstype']['personalize'] == 1) {
                                            $dyn_a = str_replace('#A#', ($row->getCellAtIndex(0) ? $row->getCellAtIndex(0)->getValue() : ''), $dyn_text);
                                            $dyn_b = str_replace('#B#', ($row->getCellAtIndex(1) ? $row->getCellAtIndex(1)->getValue() : ''), $dyn_a);
                                            $dyn_c = str_replace('#C#', ($row->getCellAtIndex(2) ? $row->getCellAtIndex(2)->getValue() : ''), $dyn_b);
                                            $dyn_d = str_replace('#D#', ($row->getCellAtIndex(3) ? $row->getCellAtIndex(3)->getValue() : ''), $dyn_c);
                                            $dyn_e = str_replace('#E#', ($row->getCellAtIndex(4) ? $row->getCellAtIndex(4)->getValue() : ''), $dyn_d);
                                            $dyn_f = str_replace('#F#', ($row->getCellAtIndex(5) ? $row->getCellAtIndex(5)->getValue() : ''), $dyn_e);
                                            $dyn_g = str_replace('#G#', ($row->getCellAtIndex(6) ? $row->getCellAtIndex(6)->getValue() : ''), $dyn_f);
                                            $dyn_h = str_replace('#H#', ($row->getCellAtIndex(7) ? $row->getCellAtIndex(7)->getValue() : ''), $dyn_g);
                                            $dyn_i = str_replace('#I#', ($row->getCellAtIndex(8) ? $row->getCellAtIndex(8)->getValue() : ''), $dyn_h);
                                            $dyn_j = str_replace('#J#', ($row->getCellAtIndex(9) ? $row->getCellAtIndex(9)->getValue() : ''), $dyn_i);
                                            $finaltext = str_replace('#K#', ($row->getCellAtIndex(10) ? $row->getCellAtIndex(10)->getValue() : ''), $dyn_j);
                                            $mdata['text'] = $finaltext;
                                            $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $finaltext);
                                            $mdata['smslen'] = $countdata['length'];
                                            $mdata['smscount'] = $countdata['count'];
                                            $mdata['smscost'] = $countdata['count'] * $factor;
                                        }
                                        //5. invalid check
                                        if ($data['invalidFlag'] == 1) {
                                            if (!DooTextHelper::verifyFormData('mobile', $org_mobile, $params)) {
                                                array_push($invalidcontacts, $mdata);
                                                continue;
                                            }
                                        }
                                        //6. optout filter
                                        if (isset($params['optouts'][$mobile])) {
                                            array_push($optout_contacts, $mdata);
                                            continue;
                                        }
                                        $dynamicsmstotal += $mdata['smscost'];
                                        array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                                        array_push($finalcontacts, $mdata);
                                    }
                                }
                            }
                        }
                    }
                    $reader->close();
                }
                if ($ext == 'xls') {
                    Doo::loadHelper('PHPExcel');
                    $filetype = PHPExcel_IOFactory::identify($filepath);
                    $objReader = PHPExcel_IOFactory::createReader($filetype);
                    $xlobj = $objReader->load($filepath);
                    $sheet = $xlobj->getSheetByName($data['filedata']['sheet']);
                    $highestrow =  $sheet->getHighestRow();
                    $blacklistFilter = 0;
                    if ($params['routedata']->blacklist_ids != '') {
                        $blacklistFilter = 1;
                        $dbs = explode(",", $params['routedata']->blacklist_ids);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                    }
                    $blmatcharray = array();
                    for ($i = 2; $i <= $highestrow; $i++) {
                        $mdata = array();
                        //echo '<pre>'; var_dump(get_class_methods($sheet->getCell($data['filedata']['column'].$i)));
                        $mobile = $org_mobile = floatval(trim($sheet->getCell($data['filedata']['column'] . $i)->getCalculatedValue()));
                        if ($mobile != 0) {
                            //echo $mobile;die;
                            $totalsubmitted++;
                            //1. duplicate check
                            if ($data['duplicateFlag'] == 1) {
                                if (isset($uniquecontacts[$mobile])) {
                                    $dupcount++;
                                    continue;
                                }
                                $uniquecontacts[$mobile] = true;
                            }

                            if ($params['account_type'] == 1) {
                                //currency based user, get mccmnc and route and per sms cost
                                //2. Get country ISO based on prefix
                                $iso = DooSmppcubeHelper::getCountryIso($mobile);
                                $mdata['mobile'] = $mobile;
                                $mdata['text'] = $params['smstext'];
                                if ($iso == '') {
                                    //invalid number
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                } else {
                                    //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                    //get coverage details like prefix and NSN prefix length
                                    $covdata = explode('|', $params['coverages'][$iso]);
                                    //4. Based on operator prefix get MCCMNC code
                                    $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                    $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                    //5. get price for the mcc mnc according to this plan
                                    $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                    $persmsprice = floatval($routecostar[1]);
                                    $routeid = intval($routecostar[0]);
                                    if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                        //no mccmnc matched, use default route pricing
                                        $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                        $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                    }
                                    $mdata['smscost'] = $persmsprice;
                                    $mdata['routeid'] = $routeid;
                                    $mdata['mccmnc'] = $mccmnc;

                                    //3. Replace URL if applicable
                                    if ($replaceurl == 1) {
                                        $uid = $params['userid'];
                                        $turl = $this->generateUrlIdf($uid, 7);
                                        $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                        //prepare the sql string
                                        $durl = array();
                                        $durl['parent_url_id'] = $urldata->id;
                                        $durl['url_idf'] = $turl;
                                        $durl['sms_shoot_id'] = $params['shootid'];
                                        $durl['mobile'] = $mobile;
                                        array_push($dynurls, $durl);
                                    } else {
                                        $dyn_text = $params['smstext'];
                                    }
                                    $mdata['mobile'] = $mobile;
                                    $mdata['text'] = $dyn_text;

                                    //4. Get dynamic SMS if applicable
                                    //get credit rule data
                                    $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                    $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                    $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                    $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                    $spcl_ccrule = $spcl_rule['counts'];

                                    if ($params['smstype']['personalize'] == 1) {
                                        $dyn_a = str_replace('#A#', $sheet->getCell('A' . $i)->getValue(), $dyn_text);
                                        $dyn_b = str_replace('#B#', $sheet->getCell('B' . $i)->getCalculatedValue(), $dyn_a);
                                        $dyn_c = str_replace('#C#', $sheet->getCell('C' . $i)->getCalculatedValue(), $dyn_b);
                                        $dyn_d = str_replace('#D#', $sheet->getCell('D' . $i)->getCalculatedValue(), $dyn_c);
                                        $dyn_e = str_replace('#E#', $sheet->getCell('E' . $i)->getCalculatedValue(), $dyn_d);
                                        $dyn_f = str_replace('#F#', $sheet->getCell('F' . $i)->getCalculatedValue(), $dyn_e);
                                        $dyn_g = str_replace('#G#', $sheet->getCell('G' . $i)->getCalculatedValue(), $dyn_f);
                                        $dyn_h = str_replace('#H#', $sheet->getCell('H' . $i)->getCalculatedValue(), $dyn_g);
                                        $dyn_i = str_replace('#I#', $sheet->getCell('I' . $i)->getCalculatedValue(), $dyn_h);
                                        $dyn_j = str_replace('#J#', $sheet->getCell('J' . $i)->getCalculatedValue(), $dyn_i);
                                        $finaltext = str_replace('#K#', $sheet->getCell('K' . $i)->getCalculatedValue(), $dyn_j);
                                        $mdata['text'] = $finaltext;
                                        $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $finaltext);
                                        $mdata['smslen'] = $countdata['length'];
                                        $mdata['smscount'] = $countdata['count'];
                                        $smscost = $mdata['smscost'] * intval($countdata['count']);
                                    } else {
                                        $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $dyn_text);
                                        $mdata['smslen'] = $countdata['length'];
                                        $mdata['smscount'] = $countdata['count'];
                                        $smscost = $mdata['smscost'] * intval($countdata['count']);
                                    }
                                    $mdata['smscost'] = $smscost;
                                    if (intval($routeid) != 0) {
                                        //6. Invalid check
                                        if ($data['invalidFlag'] == 1) {
                                            if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                                array_push($invalidcontacts, $mdata);
                                                continue;
                                            }
                                        }
                                        //6.1. optout filter
                                        if (isset($params['optouts'][$mobile])) {
                                            array_push($optout_contacts, $mdata);
                                            continue;
                                        }
                                        //7. Blacklist filter
                                        //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                        if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                        array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                    } else {
                                        //unable to match any routes consider this invalid
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    }

                                    //7. Save data in array so the final contacts as grouped based on route id
                                    if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                    $currencybasecost += $smscost;
                                    $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                    $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                    $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                    array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                                }
                            } else {
                                //credit based account
                                //2. Add country prefix if applicable
                                if ($params['routedata']->add_pre != '0') {
                                    $mobile = strlen($mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $mobile) :  $mobile;
                                }
                                //3. Replace URL if applicable
                                if ($replaceurl == 1) {
                                    $uid = $params['userid'];
                                    $turl = $this->generateUrlIdf($uid, 7);
                                    $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                    //prepare the sql string
                                    $durl = array();
                                    $durl['parent_url_id'] = $urldata->id;
                                    $durl['url_idf'] = $turl;
                                    $durl['sms_shoot_id'] = $params['shootid'];
                                    $durl['mobile'] = $mobile;
                                    array_push($dynurls, $durl);
                                } else {
                                    $dyn_text = $params['smstext'];
                                }
                                $mdata['mobile'] = $mobile;
                                $mdata['text'] = $dyn_text;
                                $mdata['smslen'] = 0; //counted later
                                $mdata['smscount'] = 0; //counted later
                                $mdata['smscost'] = 0; //counted later

                                //4. Get dynamic SMS if applicable
                                if ($params['smstype']['personalize'] == 1) {
                                    $dyn_a = str_replace('#A#', $sheet->getCell('A' . $i)->getValue(), $dyn_text);
                                    $dyn_b = str_replace('#B#', $sheet->getCell('B' . $i)->getValue(), $dyn_a);
                                    $dyn_c = str_replace('#C#', $sheet->getCell('C' . $i)->getValue(), $dyn_b);
                                    $dyn_d = str_replace('#D#', $sheet->getCell('D' . $i)->getValue(), $dyn_c);
                                    $dyn_e = str_replace('#E#', $sheet->getCell('E' . $i)->getValue(), $dyn_d);
                                    $dyn_f = str_replace('#F#', $sheet->getCell('F' . $i)->getValue(), $dyn_e);
                                    $dyn_g = str_replace('#G#', $sheet->getCell('G' . $i)->getValue(), $dyn_f);
                                    $dyn_h = str_replace('#H#', $sheet->getCell('H' . $i)->getValue(), $dyn_g);
                                    $dyn_i = str_replace('#I#', $sheet->getCell('I' . $i)->getValue(), $dyn_h);
                                    $dyn_j = str_replace('#J#', $sheet->getCell('J' . $i)->getValue(), $dyn_i);
                                    $finaltext = str_replace('#K#', $sheet->getCell('K' . $i)->getValue(), $dyn_j);
                                    $mdata['text'] = $finaltext;
                                    $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $finaltext);
                                    $mdata['smslen'] = $countdata['length'];
                                    $mdata['smscount'] = $countdata['count'];
                                    $mdata['smscost'] = $countdata['count'] * $factor;
                                }
                                //5. invalid check
                                if ($data['invalidFlag'] == 1) {
                                    if (!DooTextHelper::verifyFormData('mobile', $org_mobile, $params)) {
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    }
                                }
                                //6. optout filter
                                if (isset($params['optouts'][$mobile])) {
                                    array_push($optout_contacts, $mdata);
                                    continue;
                                }
                                $dynamicsmstotal += $mdata['smscost'];
                                array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                                array_push($finalcontacts, $mdata);
                            }
                        }
                    }
                }
                //LOOP THROGH CSV FILE
                if ($ext == 'csv') {
                    Doo::loadHelper('DooSpoutExcel');
                    $reader = DooSpoutExcel::getReader('csv');
                    $reader->setShouldFormatDates(true);
                    $reader->open($filepath);
                    $blacklistFilter = 0;
                    if ($params['routedata']->blacklist_ids != '') {
                        $blacklistFilter = 1;
                        $dbs = explode(",", $params['routedata']->blacklist_ids);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                    }
                    $blmatcharray = array();
                    foreach ($reader->getSheetIterator() as $sheet) {
                        foreach ($sheet->getRowIterator() as $n => $row) {
                            if ($n > 100000) {
                                break;
                            }
                            $mdata = array();
                            $mobile = $org_mobile = intval(trim($row->getCellAtIndex(0)->getValue()));
                            if ($mobile != 0) {
                                $totalsubmitted++;
                                //1. duplicate check
                                if ($data['duplicateFlag'] == 1) {
                                    if (isset($uniquecontacts[$mobile])) {
                                        $dupcount++;
                                        continue;
                                    }
                                    $uniquecontacts[$mobile] = true;
                                }
                                if ($params['account_type'] == 1) {
                                    //currency based user, get mccmnc and route and per sms cost
                                    //2. Get country ISO based on prefix
                                    $iso = DooSmppcubeHelper::getCountryIso($mobile);
                                    $mdata['mobile'] = $mobile;
                                    $mdata['text'] = $params['smstext'];
                                    if ($iso == '') {
                                        //invalid number
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    } else {
                                        //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                        //get coverage details like prefix and NSN prefix length
                                        $covdata = explode('|', $params['coverages'][$iso]);
                                        //4. Based on operator prefix get MCCMNC code
                                        $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                        $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                        //5. get price for the mcc mnc according to this plan
                                        $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                        $persmsprice = floatval($routecostar[1]);
                                        $routeid = intval($routecostar[0]);
                                        if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                            //no mccmnc matched, use default route pricing
                                            $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                            $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                        }
                                        $mdata['smscost'] = $persmsprice;
                                        $mdata['routeid'] = $routeid;
                                        $mdata['mccmnc'] = $mccmnc;

                                        //3. Replace URL if applicable
                                        if ($replaceurl == 1) {
                                            $uid = $params['userid'];
                                            $turl = $this->generateUrlIdf($uid, 7);
                                            $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                            //prepare the sql string
                                            $durl = array();
                                            $durl['parent_url_id'] = $urldata->id;
                                            $durl['url_idf'] = $turl;
                                            $durl['sms_shoot_id'] = $params['shootid'];
                                            $durl['mobile'] = $mobile;
                                            array_push($dynurls, $durl);
                                        } else {
                                            $dyn_text = $params['smstext'];
                                        }
                                        $mdata['mobile'] = $mobile;
                                        $mdata['text'] = $dyn_text;

                                        //4. Get dynamic SMS if applicable
                                        //get credit rule data
                                        $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                        $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                        $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                        $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                        $spcl_ccrule = $spcl_rule['counts'];

                                        if ($params['smstype']['personalize'] == 1) {
                                            $dyn_a = str_replace('#A#', ($row->getCellAtIndex(0) ? $row->getCellAtIndex(0)->getValue() : ''), $dyn_text);
                                            $dyn_b = str_replace('#B#', ($row->getCellAtIndex(1) ? $row->getCellAtIndex(1)->getValue() : ''), $dyn_a);
                                            $dyn_c = str_replace('#C#', ($row->getCellAtIndex(2) ? $row->getCellAtIndex(2)->getValue() : ''), $dyn_b);
                                            $dyn_d = str_replace('#D#', ($row->getCellAtIndex(3) ? $row->getCellAtIndex(3)->getValue() : ''), $dyn_c);
                                            $dyn_e = str_replace('#E#', ($row->getCellAtIndex(4) ? $row->getCellAtIndex(4)->getValue() : ''), $dyn_d);
                                            $dyn_f = str_replace('#F#', ($row->getCellAtIndex(5) ? $row->getCellAtIndex(5)->getValue() : ''), $dyn_e);
                                            $dyn_g = str_replace('#G#', ($row->getCellAtIndex(6) ? $row->getCellAtIndex(6)->getValue() : ''), $dyn_f);
                                            $dyn_h = str_replace('#H#', ($row->getCellAtIndex(7) ? $row->getCellAtIndex(7)->getValue() : ''), $dyn_g);
                                            $dyn_i = str_replace('#I#', ($row->getCellAtIndex(8) ? $row->getCellAtIndex(8)->getValue() : ''), $dyn_h);
                                            $dyn_j = str_replace('#J#', ($row->getCellAtIndex(9) ? $row->getCellAtIndex(9)->getValue() : ''), $dyn_i);
                                            $finaltext = str_replace('#K#', ($row->getCellAtIndex(10) ? $row->getCellAtIndex(10)->getValue() : ''), $dyn_j);
                                            $mdata['text'] = $finaltext;
                                            $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $finaltext);
                                            $mdata['smslen'] = $countdata['length'];
                                            $mdata['smscount'] = $countdata['count'];
                                            $smscost = $mdata['smscost'] * intval($countdata['count']);
                                        } else {
                                            $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $dyn_text);
                                            $mdata['smslen'] = $countdata['length'];
                                            $mdata['smscount'] = $countdata['count'];
                                            $smscost = $mdata['smscost'] * intval($countdata['count']);
                                        }
                                        $mdata['smscost'] = $smscost;
                                        if (intval($routeid) != 0) {
                                            //6. Invalid check
                                            if ($data['invalidFlag'] == 1) {
                                                if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                                    array_push($invalidcontacts, $mdata);
                                                    continue;
                                                }
                                            }
                                            //6.1. optout filter
                                            if (isset($params['optouts'][$mobile])) {
                                                array_push($optout_contacts, $mdata);
                                                continue;
                                            }
                                            //7. Blacklist filter
                                            //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                            if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                            array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                        } else {
                                            //unable to match any routes consider this invalid
                                            array_push($invalidcontacts, $mdata);
                                            continue;
                                        }

                                        //7. Save data in array so the final contacts as grouped based on route id
                                        if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                        $currencybasecost += $smscost;
                                        $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                        $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                        $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                        array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                                    }
                                } else {
                                    //credit based account
                                    //2. Add country prefix if applicable
                                    if ($params['routedata']->add_pre != '0') {
                                        $mobile = strlen($mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $mobile) :  $mobile;
                                    }
                                    //3. Replace URL if applicable
                                    if ($replaceurl == 1) {
                                        $uid = $params['userid'];
                                        $turl = $this->generateUrlIdf($uid, 7);
                                        $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                        //prepare the sql string
                                        $durl = array();
                                        $durl['parent_url_id'] = $urldata->id;
                                        $durl['url_idf'] = $turl;
                                        $durl['sms_shoot_id'] = $params['shootid'];
                                        $durl['mobile'] = $mobile;
                                        array_push($dynurls, $durl);
                                    } else {
                                        $dyn_text = $params['smstext'];
                                    }
                                    $mdata['mobile'] = $mobile;
                                    $mdata['text'] = $dyn_text;
                                    $mdata['smslen'] = 0; //counted later
                                    $mdata['smscount'] = 0; //counted later
                                    $mdata['smscost'] = 0; //counted later

                                    //5. Get dynamic SMS if applicable
                                    if ($params['smstype']['personalize'] == 1) {
                                        $dyn_a = str_replace('#A#', ($row->getCellAtIndex(0) ? $row->getCellAtIndex(0)->getValue() : ''), $dyn_text);
                                        $dyn_b = str_replace('#B#', ($row->getCellAtIndex(1) ? $row->getCellAtIndex(1)->getValue() : ''), $dyn_a);
                                        $dyn_c = str_replace('#C#', ($row->getCellAtIndex(2) ? $row->getCellAtIndex(2)->getValue() : ''), $dyn_b);
                                        $dyn_d = str_replace('#D#', ($row->getCellAtIndex(3) ? $row->getCellAtIndex(3)->getValue() : ''), $dyn_c);
                                        $dyn_e = str_replace('#E#', ($row->getCellAtIndex(4) ? $row->getCellAtIndex(4)->getValue() : ''), $dyn_d);
                                        $dyn_f = str_replace('#F#', ($row->getCellAtIndex(5) ? $row->getCellAtIndex(5)->getValue() : ''), $dyn_e);
                                        $dyn_g = str_replace('#G#', ($row->getCellAtIndex(6) ? $row->getCellAtIndex(6)->getValue() : ''), $dyn_f);
                                        $dyn_h = str_replace('#H#', ($row->getCellAtIndex(7) ? $row->getCellAtIndex(7)->getValue() : ''), $dyn_g);
                                        $dyn_i = str_replace('#I#', ($row->getCellAtIndex(8) ? $row->getCellAtIndex(8)->getValue() : ''), $dyn_h);
                                        $dyn_j = str_replace('#J#', ($row->getCellAtIndex(9) ? $row->getCellAtIndex(9)->getValue() : ''), $dyn_i);
                                        $finaltext = str_replace('#K#', ($row->getCellAtIndex(10) ? $row->getCellAtIndex(10)->getValue() : ''), $dyn_j);
                                        $mdata['text'] = $finaltext;
                                        $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $finaltext);
                                        $mdata['smslen'] = $countdata['length'];
                                        $mdata['smscount'] = $countdata['count'];
                                        $mdata['smscost'] = $countdata['count'] * $factor;
                                    }

                                    //3. invalid check
                                    if ($data['invalidFlag'] == 1) {
                                        if (!DooTextHelper::verifyFormData('mobile', $org_mobile, $params)) {
                                            array_push($invalidcontacts, $mdata);
                                            continue;
                                        }
                                    }
                                    //4. optout filter
                                    if (isset($params['optouts'][$mobile])) {
                                        array_push($optout_contacts, $mdata);
                                        continue;
                                    }
                                    $dynamicsmstotal += $mdata['smscost'];
                                    array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                                    array_push($finalcontacts, $mdata);
                                }
                            }
                        }
                    }
                    $reader->close();
                }
                //LOOP THROUGH TXT FILE
                if ($ext == 'txt') {
                    $file_h = fopen($filepath, "r");
                    $blacklistFilter = 0;
                    if ($params['routedata']->blacklist_ids != '') {
                        $blacklistFilter = 1;
                        $dbs = explode(",", $params['routedata']->blacklist_ids);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                    }
                    $blmatcharray = array();
                    while (!feof($file_h)) {
                        $mdata = array();
                        $mobile = $org_mobile = floatval(trim(fgets($file_h)));
                        if ($mobile != 0) {
                            $totalsubmitted++;
                            //1. duplicate check
                            if ($data['duplicateFlag'] == 1) {
                                if (isset($uniquecontacts[$mobile])) {
                                    $dupcount++;
                                    continue;
                                }
                                $uniquecontacts[$mobile] = true;
                            }

                            if ($params['account_type'] == 1) {
                                //currency based user, get mccmnc and route and per sms cost
                                //2. Get country ISO based on prefix
                                $iso = DooSmppcubeHelper::getCountryIso($mobile);
                                $mdata['mobile'] = $mobile;
                                $mdata['text'] = $params['smstext'];
                                if ($iso == '') {
                                    //invalid number
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                } else {
                                    //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                    //get coverage details like prefix and NSN prefix length
                                    $covdata = explode('|', $params['coverages'][$iso]);
                                    //4. Based on operator prefix get MCCMNC code
                                    $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                    $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                    //5. get price for the mcc mnc according to this plan
                                    $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                    $persmsprice = floatval($routecostar[1]);
                                    $routeid = intval($routecostar[0]);
                                    if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                        //no mccmnc matched, use default route pricing
                                        $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                        $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                    }
                                    $mdata['smscost'] = $persmsprice;
                                    $mdata['routeid'] = $routeid;
                                    $mdata['mccmnc'] = $mccmnc;

                                    //3. Replace URL if applicable
                                    if ($replaceurl == 1) {
                                        $uid = $params['userid'];
                                        $turl = $this->generateUrlIdf($uid, 7);
                                        $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                        //prepare the sql string
                                        $durl = array();
                                        $durl['parent_url_id'] = $urldata->id;
                                        $durl['url_idf'] = $turl;
                                        $durl['sms_shoot_id'] = $params['shootid'];
                                        $durl['mobile'] = $mobile;
                                        array_push($dynurls, $durl);
                                    } else {
                                        $dyn_text = $params['smstext'];
                                    }
                                    $mdata['mobile'] = $mobile;
                                    $mdata['text'] = $dyn_text;

                                    //4. Get dynamic SMS if applicable
                                    //get credit rule data
                                    $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                    $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                    $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                    $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                    $spcl_ccrule = $spcl_rule['counts'];

                                    $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $dyn_text);
                                    $mdata['smslen'] = $countdata['length'];
                                    $mdata['smscount'] = $countdata['count'];
                                    $smscost = $mdata['smscost'] * intval($countdata['count']);

                                    $mdata['smscost'] = $smscost;
                                    if (intval($routeid) != 0) {
                                        //6. Invalid check
                                        if ($data['invalidFlag'] == 1) {
                                            if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                                array_push($invalidcontacts, $mdata);
                                                continue;
                                            }
                                        }
                                        //6.1. optout filter
                                        if (isset($params['optouts'][$mobile])) {
                                            array_push($optout_contacts, $mdata);
                                            continue;
                                        }
                                        //7. Blacklist filter
                                        //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                        if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                        array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                    } else {
                                        //unable to match any routes consider this invalid
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    }

                                    //7. Save data in array so the final contacts as grouped based on route id
                                    if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                    $currencybasecost += $smscost;
                                    $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                    $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                    $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                    array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                                }
                            } else {
                                //credit based account
                                //2. Add country prefix if applicable
                                if ($params['routedata']->add_pre != '0') {
                                    $mobile = strlen($mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $mobile) :  $mobile;
                                }
                                //3. Replace URL if applicable
                                if ($replaceurl == 1) {
                                    $uid = $params['userid'];
                                    $turl = $this->generateUrlIdf($uid, 7);
                                    $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                    //prepare the sql string
                                    $durl = array();
                                    $durl['parent_url_id'] = $urldata->id;
                                    $durl['url_idf'] = $turl;
                                    $durl['sms_shoot_id'] = $params['shootid'];
                                    $durl['mobile'] = $mobile;
                                    array_push($dynurls, $durl);
                                } else {
                                    $dyn_text = $params['smstext'];
                                }
                                $mdata['mobile'] = $mobile;
                                $mdata['text'] = $dyn_text;
                                $mdata['smslen'] = 0; //counted later
                                $mdata['smscount'] = 0; //counted later
                                $mdata['smscost'] = 0; //counted later

                                //4. get dynamic sms text and sms count
                                if ($params['smstype']['personalize'] == 1) {
                                    $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $dyn_text);
                                    $mdata['smslen'] = $countdata['length'];
                                    $mdata['smscount'] = $countdata['count'];
                                    $mdata['smscost'] = $countdata['count'] * $factor;
                                }

                                //5. invalid check
                                if ($data['invalidFlag'] == 1) {
                                    if (!DooTextHelper::verifyFormData('mobile', $org_mobile, $params)) {
                                        array_push($invalidcontacts, $mdata);
                                        continue;
                                    }
                                }
                                //6. optout filter
                                if (isset($params['optouts'][$mobile])) {
                                    array_push($optout_contacts, $mdata);
                                    continue;
                                }
                                $dynamicsmstotal += $mdata['smscost'];
                                array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                                array_push($finalcontacts, $mdata);
                            }
                        }
                    }
                    fclose($file_h);
                }
            }
            //read contacts supplied via api
            if (is_countable($data['apicontacts']) && sizeof($data['apicontacts']) > 0) {
                $blacklistFilter = 0;
                if ($params['routedata']->blacklist_ids != '') {
                    $blacklistFilter = 1;
                    $dbs = explode(",", $params['routedata']->blacklist_ids);
                    $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                }
                $blmatcharray = array();
                foreach ($data['apicontacts'] as $cell) {
                    $mdata = array();
                    //$mobile = floatval(trim($cell));
                    if ($data['bulk_dynamic_flag'] == 1) {
                        $mobile = floatval(trim($cell->mobile));
                        $org_mobile = floatval(trim($cell->mobile));
                        $apismstext = $cell->msg;
                    } else {
                        $mobile = floatval(trim($cell));
                        $org_mobile = floatval(trim($cell));
                        $apismstext = $params['smstext'];
                    }
                    if ($mobile != 0) {
                        $totalsubmitted++;
                        //1. duplicate check
                        if ($data['duplicateFlag'] == 1) {
                            if (isset($uniquecontacts[$mobile])) {
                                $dupcount++;
                                continue;
                            }
                            $uniquecontacts[$mobile] = true;
                        }

                        if ($params['account_type'] == 1) {
                            //currency based user, get mccmnc and route and per sms cost
                            //2. Get country ISO based on prefix
                            $iso = DooSmppcubeHelper::getCountryIso($mobile);

                            $mdata['mobile'] = $mobile;
                            $mdata['text'] = $params['smstext'];
                            $mdata['umsgid'] = isset($params['setMsgIds'][$mobile]) ? $params['setMsgIds'][$mobile] : '';
                            if ($iso == '') {
                                //invalid number
                                array_push($invalidcontacts, $mdata);
                                continue;
                            } else {
                                //3. Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                                //get coverage details like prefix and NSN prefix length
                                $covdata = explode('|', $params['coverages'][$iso]);
                                //4. Based on operator prefix get MCCMNC code
                                $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                                $mccmnc = $params['prefixes'][$covdata[0] . $pfx];

                                //5. get price for the mcc mnc according to this plan
                                $routecostar = explode("|", $params['smsplan']['pricing'][$mccmnc]);
                                $persmsprice = floatval($routecostar[1]);
                                $routeid = intval($routecostar[0]);
                                if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                                    //no mccmnc matched, use default route pricing
                                    $routeid = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['route'] : $params['smsplan']['routesniso'][intval($covdata[1])]['route'];
                                    $persmsprice = !isset($params['smsplan']['routesniso'][intval($covdata[1])]) ? $params['smsplan']['routesniso'][0]['price'] : $params['smsplan']['routesniso'][intval($covdata[1])]['price'];
                                }
                                $mdata['smscost'] = $persmsprice;
                                $mdata['routeid'] = $routeid;
                                $mdata['mccmnc'] = $mccmnc;
                                //3. Replace URL if applicable
                                if ($replaceurl == 1) {
                                    $uid = $params['userid'];
                                    $turl = $this->generateUrlIdf($uid, 7);
                                    $dyn_text = str_replace($urlidf, $turl, $params['smstext']);
                                    //prepare the sql string
                                    $durl = array();
                                    $durl['parent_url_id'] = $urldata->id;
                                    $durl['url_idf'] = $turl;
                                    $durl['sms_shoot_id'] = $params['shootid'];
                                    $durl['mobile'] = $mobile;
                                    array_push($dynurls, $durl);
                                } else {
                                    $dyn_text = $params['smstext'];
                                }
                                $mdata['mobile'] = $mobile;
                                $mdata['text'] = $dyn_text;

                                //4. Get dynamic SMS if applicable
                                //get credit rule data
                                $creditruleid = $params['routes'][$routeid]['credit_rule'];
                                $normal_ccrule = unserialize($params['countrules'][$creditruleid]['normal_sms_rule']);
                                $unicode_ccrule = unserialize($params['countrules'][$creditruleid]['unicode_rule']);
                                $spcl_rule = unserialize($params['countrules'][$creditruleid]['special_chars_rule']);
                                $spcl_ccrule = $spcl_rule['counts'];
                                $countdata = $this->getSmsCount(array('specialrule' => $spcl_ccrule, 'unicoderule' => $unicode_ccrule, 'normalrule' => $normal_ccrule, 'smstype' => $params['smstype']), $dyn_text);
                                $mdata['smslen'] = $countdata['length'];
                                $mdata['smscount'] = $countdata['count'];
                                $smscost = $mdata['smscost'] * intval($countdata['count']);

                                $mdata['smscost'] = $smscost;
                                if (intval($routeid) != 0) {
                                    //6. Invalid check
                                    if ($data['invalidFlag'] == 1) {
                                        if (!DooTextHelper::verifyFormData('mobile', $mobile, explode(",", $covdata[2]))) {
                                            array_push($invalidcontacts, $mdata);
                                            continue;
                                        }
                                    }
                                    //6.1. optout filter
                                    if (isset($params['optouts'][$mobile])) {
                                        array_push($optout_contacts, $mdata);
                                        continue;
                                    }
                                    //7. Blacklist filter
                                    //Blacklist filtering is performed route-wise as for each number lookup takes a lot of time
                                    if (!is_array($blmatcharray[$routeid]['mobiles'])) $blmatcharray[$routeid]['mobiles'] = array();
                                    array_push($blmatcharray[$routeid]['mobiles'], $mobile);
                                } else {
                                    //unable to match any routes consider this invalid
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                }

                                //7. Save data in array so the final contacts as grouped based on route id
                                if (!is_array($routewisecontacts[$routeid]['mobiles'])) $routewisecontacts[$routeid]['mobiles'] = array();
                                $currencybasecost += $smscost;
                                $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) + $smscost;
                                $routecnf = json_decode($params['routes'][$routeid]['route_config'], true);
                                $routewisecontacts[$routeid]['smpp'] = $routecnf['primary_smsc'];
                                array_push($routewisecontacts[$routeid]['mobiles'], $mdata);
                            }
                        } else {
                            //credit based account
                            //2. Add country prefix if applicable
                            if ($params['routedata']->add_pre != '0') {
                                $mobile = strlen($mobile) < max($params['validlengths']) ? intval($params['countryPrefix'] . $mobile) :  $mobile;
                            }
                            //3. Replace URL if applicable
                            if ($replaceurl == 1) {
                                $uid = $params['userid'];
                                $turl = $this->generateUrlIdf($uid, 7);
                                $dyn_text = str_replace($urlidf, $turl, $apismstext);
                                //prepare the sql string
                                $durl = array();
                                $durl['parent_url_id'] = $urldata->id;
                                $durl['url_idf'] = $turl;
                                $durl['sms_shoot_id'] = $params['shootid'];
                                $durl['mobile'] = $mobile;
                                array_push($dynurls, $durl);
                            } else {
                                $dyn_text = $apismstext;
                            }
                            $mdata['mobile'] = $mobile;
                            $mdata['text'] = $dyn_text;
                            $mdata['umsgid'] = isset($params['setMsgIds'][$mobile]) ? $params['setMsgIds'][$mobile] : '';
                            $mdata['smslen'] = 0; //counted later
                            $mdata['smscount'] = 0; //counted later
                            $mdata['smscost'] = 0; //counted later

                            //4. get dynamic sms text and sms count
                            if ($params['smstype']['personalize'] == 1) {
                                $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $dyn_text);
                                $mdata['smslen'] = $countdata['length'];
                                $mdata['smscount'] = $countdata['count'];
                                $mdata['smscost'] = $countdata['count'] * $factor;
                            }

                            //5. invalid check
                            if ($data['invalidFlag'] == 1) {
                                if (!DooTextHelper::verifyFormData('mobile', $mobile, $params['validlengths'])) {
                                    array_push($invalidcontacts, $mdata);
                                    continue;
                                }
                            }
                            //6. optout filter
                            if (isset($params['optouts'][$mobile])) {
                                array_push($optout_contacts, $mdata);
                                continue;
                            }
                            $dynamicsmstotal += $mdata['smscost'];
                            array_push($blmatcharray, $mobile); // same contacts as $finalcontacts but in 1-D array
                            array_push($finalcontacts, $mdata);
                        }
                    }
                }
            }
            //save dynamic URLS for the case of custom contacts supplied by user
            if (sizeof($dynurls) > 0) {
                $dynturlobj = Doo::loadModel('ScShortUrlsMsisdnMap', true);
                $dynturlobj->addData($dynurls);
            }
        }

        //Filter blacklist
        if (sizeof($blmatcharray) > 0) {
            if ($params['account_type'] == 1) {
                //filter blacklist for multiple routes
                foreach ($blmatcharray as $rid => $mobdata) {
                    $blstr = implode(",", $mobdata['mobiles']);
                    if ($params['routes'][$rid]['blacklist_ids'] != '' && $blstr != '') {
                        $dbs = explode(",", $params['routes'][$rid]['blacklist_ids']);
                        $getBldbParams = $this->getBlacklistClassname($dbs[0]);
                        $blacklistFilterQuery = 'SELECT id,' . $getBldbParams[1] . ' FROM ' . $getBldbParams[0] . ' WHERE ' . $getBldbParams[1] . ' IN (' . $blstr . ')';
                        $blacklistMatched = Doo::db()->fetchAll($blacklistFilterQuery, null, PDO::FETCH_KEY_PAIR, null, array(), 2);
                        $filterData = $this->flip_isset_diff($routewisecontacts[$rid]['mobiles'], $blacklistMatched);
                        $routewisecontacts[$rid]['mobiles'] = $filterData[0];
                        $currencybasecost -= $filterData[2];
                        $routewisecontacts[$routeid]['costperroute'] = floatval($routewisecontacts[$routeid]['costperroute']) - $filterData[2];
                        $blmatchedcontacts = array_merge($blmatchedcontacts, $filterData[1]);
                    }
                }
            } else {
                //credit based account, filter blacklist from single route
                $blstr = implode(",", $blmatcharray);
                if ($blacklistFilter == 1) {
                    $blacklistFilterQuery = 'SELECT id,' . $getBldbParams[1] . ' FROM ' . $getBldbParams[0] . ' WHERE ' . $getBldbParams[1] . ' IN (' . $blstr . ')';
                    $blacklistMatched = Doo::db()->fetchAll($blacklistFilterQuery, null, PDO::FETCH_KEY_PAIR, null, array(), 2);
                    $filterData = $this->flip_isset_diff($finalcontacts, $blacklistMatched);
                    $finalcontacts = $filterData[0];
                    $blmatchedcontacts = $filterData[1];
                }
            }
        }
        //merge optout contacts into blacklist
        $blmatchedcontacts = array_merge($blmatchedcontacts, $optout_contacts);

        //all contacts have been read now
        if ($params['account_type'] == 1) {
            //currency based counting
            //foreach route repeat the process for dlr cutting batch processing and so on
            $returndata = array();
            $returndata['routewisecontacts'] = $routewisecontacts;
            $returndata['invalidcontacts'] = $invalidcontacts;
            $returndata['blmatchedcontacts'] = $blmatchedcontacts;
            $returndata['totaldropped'] = 0; // dlr cutting for currency based is done in clientcontroller
            $returndata['totalsmscost'] = $currencybasecost;
            $returndata['totalsubmitted'] = $totalsubmitted;
            $returndata['duplicatesremoved'] = $dupcount;
            $returndata['persmscount'] = 0;

            return $returndata;
        } else {
            //credit based account
            $totalcontacts = sizeof($finalcontacts);
            //add to whitelist of a small sms shoot - skip for api
            if ($totalcontacts < 15 && (is_countable($data['apicontacts']) && sizeof($data['apicontacts']) == 0)) {
                Doo::loadModel('ScUsersWhitelist');
                $objwl = new ScUsersWhitelist;
                $objwl->user_id = $params['userid'];
                $rswl = Doo::db()->find($objwl, array('limit' => 1));
                $wlnoar = array();
                $i = 0;
                foreach ($finalcontacts as $cdata) {
                    if ($i > 20) break;
                    array_push($wlnoar, $cdata['mobile']);
                    $i++;
                }
                if (!$rswl) {
                    //insert
                    $objwl->mobiles = implode(",", $wlnoar);
                    Doo::db()->insert($objwl);
                } else {
                    //to ensure duplicates are not there
                    $wl_arry = explode(",", $rswl->mobiles . ',' . implode(",", $wlnoar));
                    $wl_arry = array_unique($wl_arry);
                    $objwl->id = $rswl->id;
                    $objwl->mobiles = implode(",", $wl_arry);
                    Doo::db()->update($objwl);
                }
            }
            //apply DLR cutting and create real batch vs dropped batch
            $totalcontacts = sizeof($finalcontacts);
            if ($totalcontacts > Doo::conf()->dlr_per_threshold) {
                if ($params['delvper'] != 100 && $params['usergroup'] != 'admin') {
                    //apply dlr cut
                    //check and keep whitelist number out of dlr cutting
                    $wlobj = Doo::loadModel('ScUsersWhitelist', true);
                    $wlobj->user_id = $params['userid'];
                    $wlcontacts = sizeof($data['apicontacts']) > 0 ? false : Doo::db()->find($wlobj, array('select' => 'mobiles', 'limit' => 1))->mobiles;
                    if ($wlcontacts) {
                        //whitelist contacts are present, filter them out before applying cutting
                        $wlcontacts = explode(",", $wlcontacts, 11);
                        //create 2d array of whitelist numbers
                        $wlcontacts_ar = array();
                        foreach ($wlcontacts as $clno) {
                            $tar['mobile'] = $clno;
                            array_push($wlcontacts_ar, $tar);
                        }
                        $nowhitelistcontacts = array_udiff($finalcontacts, $wlcontacts_ar, function ($a, $b) {
                            return $a['mobile'] - $b['mobile'];
                        }); // this is list without any whitelist numbers
                        $wlpresentcontacts = array_diff_assoc($finalcontacts, $nowhitelistcontacts); //this is list of whitelist nums present in the current shoot
                        //apply dlr cut on contacts array without any whitelist
                        $sms_to_cut = $totalcontacts - intval(($params['delvper'] / 100) * $totalcontacts);
                        if (sizeof($nowhitelistcontacts) > 0) {
                            if ($sms_to_cut > sizeof($nowhitelistcontacts)) {
                                $sms_to_cut = sizeof($nowhitelistcontacts);
                            }

                            $randm_keys = array_rand($nowhitelistcontacts, $sms_to_cut);

                            foreach ($randm_keys as $key) {
                                $droppedcontacts[$key] = $nowhitelistcontacts[$key];
                            }

                            //get the numbers which will be sent to server
                            $dlrfilcontacts = array_diff_assoc($nowhitelistcontacts, $droppedcontacts);

                            //merge the whitelist number with numbers going to go to smpp
                            $dlrfilcontacts = $wlpresentcontacts + $dlrfilcontacts;
                        } else {
                            //all contacts are white list: no dlr cutting
                            $dlrfilcontacts = $finalcontacts;
                        }
                    } else {
                        //no whitelist to filter
                        $sms_to_cut = $totalcontacts - intval(($params['delvper'] / 100) * $totalcontacts);
                        $randm_keys = array_rand($finalcontacts, $sms_to_cut);
                        foreach ($randm_keys as $key) {
                            $droppedcontacts[$key] = $finalcontacts[$key];
                        }
                        //get the numbers which will be sent to server
                        $dlrfilcontacts = array_diff_assoc($finalcontacts, $droppedcontacts);
                    }
                    //end of dlr cut
                } else {
                    $dlrfilcontacts = $finalcontacts;
                }
            } else {
                $dlrfilcontacts = $finalcontacts;
            }
            //save dropped contacts according to fake dlr ratios
            $totaldropped = sizeof($droppedcontacts);
            if ($totaldropped > 0) {
                $smstofakedel = intval((Doo::conf()->fakedlr_del / 100) * $totaldropped);
                $smstofakeundel = intval((Doo::conf()->fakedlr_undel / 100) * $totaldropped);
                $smstofakeexp = $totaldropped - ($smstofakedel + $smstofakeundel);

                //split dropped contacts according to fake dlr ratios
                $fakedlrcontacts = array();
                $fakedlrcontacts = $droppedcontacts;
                $fakedlrcontacts_del = array_slice($fakedlrcontacts, 0, $smstofakedel);
                $fakedlrcontacts_undel = array_slice($fakedlrcontacts, ($smstofakedel + 1), $smstofakeundel);
                $fakedlrcontacts_exp = array_slice($fakedlrcontacts, ($smstofakeundel + 1), $smstofakeexp);
            }

            //count sms text
            $persmscount = 0;
            if ($params['smstype']['main'] == 'text' && $params['smstype']['personalize'] != 1) {
                $countdata = $this->getSmsCount(array('specialrule' => $params['specialcountRule'], 'unicoderule' => $params['unicodecountRule'], 'normalrule' => $params['normalcountRule'], 'smstype' => $params['smstype']), $params['smstext']);

                $totalsmscount = ($totalcontacts * $countdata['count']) * $factor;
                $persmscount = $countdata['count'];
                $persmscost = $countdata['count'] * $factor;
            } elseif ($params['smstype']['main'] == 'text' && $params['smstype']['personalize'] == 1) {
                $totalsmscount = $dynamicsmstotal;
                $persmscount = $finalcontacts[0]['smscount'];
                $persmscost = $finalcontacts[0]['smscount'] * $factor;
            } elseif ($params['smstype']['main'] != 'text') {
                $totalsmscount = $totalcontacts;
                $persmscount = 1;
                $persmscost = 1;
            }
            //return data
            $returndata = array();
            $returndata['finalcontacts'] = $finalcontacts;
            $returndata['dlrfilcontacts'] = $dlrfilcontacts;
            $returndata['invalidcontacts'] = $invalidcontacts;
            $returndata['blmatchedcontacts'] = $blmatchedcontacts;
            $returndata['totaldropped'] = $totaldropped;
            $returndata['droppedcontacts'] = $droppedcontacts;
            $returndata['fakedlr_del'] = $fakedlrcontacts_del;
            $returndata['fakedlr_undel'] = $fakedlrcontacts_undel;
            $returndata['fakedlr_exp'] = $fakedlrcontacts_exp;
            $returndata['totalsmscredits'] = $totalsmscount;
            $returndata['duplicatesremoved'] = $dupcount;
            $returndata['persmscount'] = $persmscount;
            $returndata['persmscost'] = $persmscost;

            return $returndata;
        }
    }

    private function getPhonebookContacts($data)
    {
        $startlimit = $data['phonebook']['start'];
        $numrows = $data['phonebook']['end'];
        $pbcobj = Doo::loadModel('ScPhonebookContacts', true);
        $pbcobj->group_id = $data['phonebook']['group'];
        $limit = ($startlimit . ', ' . $numrows);
        return Doo::db()->find($pbcobj, array('select' => 'mobile', 'limit' => $limit));
    }

    private function getBlacklistClassname($db)
    {
        //get the mobile column and classname
        $bliobj = Doo::loadModel('ScBlacklistIndex', true);
        $bliobj->id = $db;
        $bldata = Doo::db()->find($bliobj, array('select' => 'table_name,mobile_column', 'limit' => 1), 2);

        $classname = '';
        $temptbl = $bldata->table_name;
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
        return array($bldata->table_name, $bldata->mobile_column);
    }

    private function generateUrlIdf($uid, $len = 6)
    {
        $turl = self::getToken($len);
        return $turl;
    }

    private function getSmsCount($data, $text)
    {
        $totaloccurences = 0;
        $totalspcladd = 0;
        $totalspclchargroups = is_countable($data['specialrule']) ? sizeof($data['specialrule']) : 0;
        $rawsmslength = mb_strlen(trim($text), 'UTF-8');
        //check special characters
        for ($i = 1; $i <= $totalspclchargroups; $i++) {
            if ($i > 1) {
                //it means according to this credit count rule, there are some special characters which will be counted as more than 1 character
                $charlist = implode("", $data['specialrule'][$i]);
                $spclchars = str_replace("clb", "]", str_replace("ln", '', $charlist));
                //remove ln as checking for new lines will be done separately
                //check out occurences
                $occurrences = preg_match_all('/[' . preg_quote($spclchars) . ']/u', $text);
                $totaloccurences += $occurrences;
                $totalspcladd += $occurrences * $i;
                //check for new line
                if (strpos($charlist, "ln") !== false) {
                    $linebreaks = substr_count($text, PHP_EOL);
                    $totaloccurences += $linebreaks;
                    $totalspcladd += $linebreaks * $i;
                }
            }
        }
        $totallength = ($rawsmslength - $totaloccurences) + $totalspcladd;
        $smscount = 1;
        if ($data['smstype']['main'] != 'text') {
            //wap and vcard
            return array('length' => $totallength, 'count' => $smscount);
        } else {
            //text sms
            if ($data['smstype']['unicode'] == 1) {
                //use unicode rule
                for ($j = 1; $j <= 5; $j++) {
                    if ($totallength >= $data['unicoderule'][$j]['from'] && $totallength <= $data['unicoderule'][$j]['to']) {
                        //matched
                        $smscount = $j;
                        break;
                    }
                    if ($totallength > $data['unicoderule'][5]['to']) {
                        //simply calculate the per sms factor
                        $persms = ceil($data['unicoderule'][5]['to'] / 5);
                        $smscount = ceil($totallength / $persms);
                    }
                }
            } else {
                //use normal sms rule
                for ($j = 1; $j <= 5; $j++) {
                    if ($totallength >= $data['normalrule'][$j]['from'] && $totallength <= $data['normalrule'][$j]['to']) {
                        //matched
                        $smscount = $j;
                        break;
                    }
                    if ($totallength > $data['normalrule'][5]['to']) {
                        //simply calculate the per sms factor
                        $persms = ceil($data['normalrule'][5]['to'] / 5);
                        $smscount = ceil($totallength / $persms);
                    }
                }
            }
            //return total sms count and length of sms
            return array('length' => $totallength, 'count' => $smscount);
        }
    }

    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }


    /*
Functions for bulk inserting and processing of SMS data
*/
    public function storeSpamData($data, $params)
    {
        $action = Doo::conf()->spam_action;
        $response = array();
        if ($action == 'NOTIFY') {
            //simply notify
            $response['message'] = 'Spam keywords were detected in SMS text. Please try again.';
            $response['deductcredits'] = 0; //dont deduct credits
            return $response;
        } elseif ($action == 'NOTIFY_DEDUCT') {
            //prepare and save data
            $spmobj = Doo::loadModel('ScSpamCampaigns', true);
            $spmobj->sms_shoot_id = $params['shootid'];
            $spmobj->user_id = $params['userid'];
            $spmobj->route_id = $params['account_type'] == 1 ? $params['smsplan']['id'] : $params['routedata']->id;
            $spmobj->sender_id = $params['senderid'];
            $spmobj->contacts = $params['account_type'] == 1 ? base64_encode(serialize($data['routewisecontacts'])) : base64_encode(serialize($data['dlrfilcontacts']));
            $spmobj->dropped_contacts = base64_encode(serialize(array('DEL' => $data['fakedlr_del'], 'UNDEL' => $data['fakedlr_undel'], 'EXP' => $data['fakedlr_exp'])));
            $spmobj->invalid_contacts = base64_encode(serialize($data['invalidcontacts']));
            $spmobj->blacklist_contacts = base64_encode(serialize($data['blmatchedcontacts']));
            $spmobj->duplicates_removed = $data['duplicatesremoved'];
            $spmobj->smscount = $data['persmscount'];
            $spmobj->cc_rule = $params['account_type'] == 1 ? 0 : $params['routedata']->credit_rule;
            $spmobj->credits_charged = $params['account_type'] == 1 ? $data['totalsmscost'] : $data['totalsmscredits'];
            $spmobj->pushed_via = $params['pushedvia'];
            $spmobj->count = $params['account_type'] == 1 ? $data['totalsubmitted'] : sizeof($data['dlrfilcontacts']) + sizeof($data['invalidcontacts']) + sizeof($data['blmatchedcontacts']) + sizeof($data['droppedcontacts']) + $data['duplicatesremoved'];
            $spmobj->sms_type = serialize($params['smstype']);
            $spmobj->sms_text = $params['savetext'];
            $spmobj->keywords_match = serialize($data['spamdata']);
            $spmobj->submission_time = date(Doo::conf()->date_format_db);
            $spmobj->schedule_data = serialize(array('schedule' => $data['schflag'], 'time' => $data['actualschtime']));
            $spmobj->status = 1;
            Doo::db()->insert($spmobj);

            //deduct credits
            if ($params['usergroup'] != 'admin') {
                $response['deductcredits'] = 1;
            }
            $response['message'] = 'SPAM content was detected in your campaign. Your campaign is held for approval from Admin. You will be notified of the result.';
            //return
            return $response;
        }
    }

    public function storeTempData($data, $params)
    {
        $tmpobj = Doo::loadModel('ScTempCampaigns', true);
        $tmpobj->sms_shoot_id = $params['shootid'];
        $tmpobj->user_id = $params['userid'];
        $tmpobj->route_id = $params['routedata']->id;
        $tmpobj->sender_id = $params['senderid'];
        $tmpobj->contacts = base64_encode(serialize($data['dlrfilcontacts']));
        $tmpobj->dropped_contacts = base64_encode(serialize(array('DEL' => $data['fakedlr_del'], 'UNDEL' => $data['fakedlr_undel'], 'EXP' => $data['fakedlr_exp'])));
        $tmpobj->invalid_contacts = base64_encode(serialize($data['invalidcontacts']));
        $tmpobj->blacklist_contacts = base64_encode(serialize($data['blmatchedcontacts']));
        $tmpobj->duplicates_removed = $data['duplicatesremoved'];
        $tmpobj->smscount = $data['persmscount'];
        $tmpobj->cc_rule = $params['routedata']->credit_rule;
        $tmpobj->credits_charged = $data['totalsmscredits'];
        $tmpobj->pushed_via = $params['pushedvia'];
        $tmpobj->count = sizeof($data['dlrfilcontacts']) + sizeof($data['invalidcontacts']) + sizeof($data['blmatchedcontacts']) + $data['totaldropped'] + $data['duplicatesremoved'];
        $tmpobj->sms_type = serialize($params['smstype']);
        $tmpobj->sms_text = $params['savetext'];
        $tmpobj->submission_time = date(Doo::conf()->date_format_db);
        $tmpobj->schedule_data = serialize(array('schedule' => $data['schflag'], 'time' => $data['actualschtime']));
        $tmpobj->status = 1;

        Doo::db()->insert($tmpobj);
    }

    public function saveScheduleSms($data, $params, $smsbatch, $queue = array())
    {
        Doo::loadModel('ScScheduledCampaigns');
        $schobj = new ScScheduledCampaigns;

        $schobj->sms_shoot_id = $params['shootid'];
        $schobj->user_id = $params['userid'];
        $schobj->route_id = $params['account_type'] == 1 ? $params['smsplan']['id'] : $params['routedata']->id;
        $schobj->sender_id = $params['senderid'];
        $schobj->contacts = base64_encode(serialize($smsbatch));
        $schobj->dropped_contacts = base64_encode(serialize(array('DEL' => $data['fakedlr_del'], 'UNDEL' => $data['fakedlr_undel'], 'EXP' => $data['fakedlr_exp'])));
        $schobj->invalid_contacts = base64_encode(serialize($data['invalidcontacts']));
        $schobj->blacklist_contacts = base64_encode(serialize($data['blmatchedcontacts']));
        $schobj->smscount = $data['persmscount'];
        $schobj->pushed_via = $params['pushedvia'];
        $schobj->count = sizeof($smsbatch) + sizeof($data['invalidcontacts']) + sizeof($data['blmatchedcontacts']) + sizeof($data['droppedcontacts']);
        $schobj->sms_type = serialize($params['smstype']);
        $schobj->sms_text = $params['savetext'];
        $schobj->submission_time = date(Doo::conf()->date_format_db);
        $schobj->schedule_time = $data['actualschtime'];
        $schobj->status = 1;
        Doo::db()->insert($schobj);

        if (sizeof($queue) > 0) {
            $schqueue = array();
            foreach ($queue as $batch) {
                $tmpschar = array();
                $tmpschar['sms_shoot_id'] = $params['shootid'];
                $tmpschar['user_id'] = $params['userid'];
                $tmpschar['route_id'] = $params['routedata']->id;
                $tmpschar['sender_id'] = $params['senderid'];
                $tmpschar['contacts'] = base64_encode(serialize($batch));
                $tmpschar['pushed_via'] = $params['pushedvia'];
                $tmpschar['count'] = sizeof($batch);
                $tmpschar['smscount'] = $data['persmscount'];
                $tmpschar['sms_type'] = serialize($params['smstype']);
                $tmpschar['sms_text'] = $params['savetext'];
                $tmpschar['submission_time'] = date(Doo::conf()->date_format_db);
                $tmpschar['schedule_time'] = $data['actualschtime'];
                $tmpschar['status'] = 1;
                array_push($schqueue, $tmpschar);
            }
            if (sizeof($schqueue) > 0) $schobj->addScheduledQueue($schqueue);
        }
    }

    public function submitInitialCampaign($data, $params, $smsbatch, $queue = array())
    {
        $sentobj = Doo::loadModel('ScSentSms', true);
        $savefirstbatch = array();
        $dynamic_push_queue = array();
        //This is needed because in case of personalized sms, sometimes ACK comes before the entry is made in the DB. Hence is required to make DB entry first and then push to kannel
        //check if verified sms is needed then put all sms in queue
        if ($params['verified_sms']['status'] == 1) {
            //verified campaign
            $qobj = Doo::loadModel('ScQueuedCampaigns', true);
            $savequeue = array();
            $tmpschar = array();
            $tmpschar['sms_shoot_id'] = $params['shootid'];
            $tmpschar['user_id'] = $params['userid'];
            $tmpschar['route_id'] = $params['routedata']->id;
            $tmpschar['sender_id'] = $params['senderid'];
            $tmpschar['contacts'] = base64_encode(serialize($smsbatch));
            $tmpschar['pushed_via'] = $params['pushedvia'];
            $tmpschar['count'] = sizeof($smsbatch);
            $tmpschar['smscount'] = $data['persmscount'];
            $tmpschar['sms_type'] = serialize($params['smstype']);
            $tmpschar['sms_text'] = $params['savetext'];
            $tmpschar['submission_time'] = date(Doo::conf()->date_format_db);
            $tmpschar['status'] = -1; // -1 so node process can make the Google API call
            array_push($savequeue, $tmpschar);
            if (sizeof($savequeue) > 0) $qobj->addQueueItem($savequeue);
            //additional batches
            //save in queue
            if (sizeof($queue) > 0) {
                $qobj = Doo::loadModel('ScQueuedCampaigns', true);
                $savequeue = array();
                foreach ($queue as $batch) {
                    $tmpschar = array();
                    $tmpschar['sms_shoot_id'] = $params['shootid'];
                    $tmpschar['user_id'] = $params['userid'];
                    $tmpschar['route_id'] = $params['routedata']->id;
                    $tmpschar['sender_id'] = $params['senderid'];
                    $tmpschar['contacts'] = base64_encode(serialize($batch));
                    $tmpschar['pushed_via'] = $params['pushedvia'];
                    $tmpschar['count'] = sizeof($batch);
                    $tmpschar['smscount'] = $data['persmscount'];
                    $tmpschar['sms_type'] = serialize($params['smstype']);
                    $tmpschar['sms_text'] = $params['savetext'];
                    $tmpschar['submission_time'] = date(Doo::conf()->date_format_db);
                    $tmpschar['status'] = -1; // -1 so node process can make the Google API call
                    array_push($savequeue, $tmpschar);
                }
                if (sizeof($savequeue) > 0) $qobj->addQueueItem($savequeue);
            }
        } else {
            //non-verified sms, proceed as usual
            foreach ($smsbatch as $mdata) {
                $mobile = $mdata['mobile'];
                if ($params['smstype']['personalize'] == 1) {
                    $smstext = $mdata['text'];
                    $umsgid = $mdata['umsgid'] != "" ? $mdata['umsgid'] : self::getToken(7); //uniqid(rand());
                    $smscount = $mdata['smscount'];
                    $smscost = $mdata['smscost'];
                    //send to kannel
                    $dynsms = array();
                    $dynsms['sms_shoot_id'] = $params['shootid'];
                    $dynsms['route_id'] = $params['account_type'] == 1 ? $params['batchrouteid'] : $params['routedata']->id;
                    $dynsms['user_id'] = $params['userid'];
                    $dynsms['upline_id'] = $params['uplineid'];
                    $dynsms['umsgid'] = $umsgid;
                    $dynsms['smscount'] = $params['account_type'] == 1 ? floatval($mdata['smscost']) : $smscost;
                    $dynsms['smsc'] = $params['smsc'];
                    $dynsms['senderid'] = $params['senderstr'];
                    $dynsms['contacts'] = $mobile;
                    $dynsms['sms_type'] = serialize($params['smstype']);
                    $dynsms['sms_text'] = htmlspecialchars($smstext, ENT_QUOTES);
                    $dynsms['usertype'] = $params['account_type'] == 1 ? 1 : 0;
                    $dynsms['tlv'] = $params['tlv'];
                    $dynsms['callback_url'] = $params['callback_url'];
                    $dynsms['route_title'] = $params['routedata']->title;
                    array_push($dynamic_push_queue, $dynsms);
                    //DooSmppcubeHelper::pushToKannel($dynsms);
                } else {
                    $smstext = ''; //sms for non dynamic is $params['savetext'] but we store empty string in send sms table for non dynamic sms to save db space
                    $smscount = $params['account_type'] == 1 ? $mdata['smscount'] : $data['persmscount'];
                    $smscost = $params['account_type'] == 1 ? $mdata['smscost'] : $data['persmscost'];
                    $umsgid = $mdata['umsgid'] != "" ? $mdata['umsgid'] : self::getToken(7);
                }

                //get per sms price and cost in currency even for credit based accounts
                if ($params['account_type'] == 0) {
                    //flat credit based account
                    $persmsprice_currency = $params['creditdata']->price;
                    $persmscost_currency = $persmsprice_currency * $smscount;
                }
                if ($params['account_type'] == 1) {
                    //mccmnc based account
                    $persmsprice_currency = $smscost / $smscount;
                    $persmscost_currency = $smscost;
                }
                if ($params['account_type'] == 2) {
                    //dynamic credit based account
                    $persmsprice_currency = $params['creditdata']->price;
                    $persmscost_currency = $smscost;
                }
                $saveText = $params['smstype']['main'] == 'text' ? htmlspecialchars($smstext, ENT_QUOTES) : base64_encode(serialize($smstext));
                $tmpar = array();
                $tmpar['sms_shoot_id'] = $params['shootid'];
                $tmpar['user_id'] = $params['userid'];
                $tmpar['route_id'] = $params['account_type'] == 1 ? $params['batchrouteid'] : $params['routedata']->id;
                $tmpar['smsc'] = $params['smsc'];
                $tmpar['sender_id'] = $params['senderid'];
                $tmpar['mobile'] = $mobile;
                $tmpar['sms_type'] = serialize($params['smstype']);
                $tmpar['sms_text'] = $saveText;
                $tmpar['sms_count'] = $smscount;
                $tmpar['submission_time'] = date(Doo::conf()->date_format_db);
                $tmpar['sending_time'] = date(Doo::conf()->date_format_db);
                $tmpar['umsgid'] = $umsgid;
                $tmpar['mccmnc'] = $mdata['mccmnc'];
                //$tmpar['price'] = $params['account_type']==1 ? floatval($mdata['smscost']) : $smscost;
                $tmpar['price'] = $persmsprice_currency;
                $tmpar['cost'] = $persmscost_currency;
                $tmpar['status'] = 1;
                array_push($savefirstbatch, $tmpar);
            }
            //save in DB

            if (sizeof($savefirstbatch) > 0) $sentobj->saveSentSMS($savefirstbatch);
            //send to kannel if non dynamic sms
            if ($params['smstype']['personalize'] != 1) {
                $kansms = array();
                $kansms['sms_shoot_id'] = $params['shootid'];
                $kansms['route_id'] = $params['account_type'] == 1 ? $params['batchrouteid'] : $params['routedata']->id;
                $kansms['user_id'] = $params['userid'];
                $kansms['upline_id'] = $params['uplineid'];
                $kansms['smscount'] = $data['persmscount'];
                $kansms['smsc'] = $params['smsc'];
                $kansms['senderid'] = $params['senderstr'];
                $kansms['contacts'] = $smsbatch;
                $kansms['sms_type'] = serialize($params['smstype']);
                $kansms['sms_text'] = $params['smstext'];
                $kansms['usertype'] = $params['account_type'] == 1 ? 1 : 0;
                $kansms['tlv'] = $params['tlv'];
                $kansms['callback_url'] = $params['callback_url'];
                $kansms['route_title'] = $params['routedata']->title;
                $kansms['mode'] = $params['mode'];
                if ($params['smsc_type'] == "http") {
                    //send to api processor
                    DooSmppcubeHelper::vendorApiCampaignProcessor($kansms);
                } else {
                    //send to kannel
                    DooSmppcubeHelper::pushToKannel($kansms);
                }
            } else {
                foreach ($dynamic_push_queue as $dynsms_item) {
                    if ($params['smsc_type'] == "http") {
                        DooSmppcubeHelper::vendorApiCampaignProcessor($dynsms_item);
                    } else {
                        DooSmppcubeHelper::pushToKannel($dynsms_item);
                    }
                }
            }
            //save in queue
            if (sizeof($queue) > 0) {
                $qobj = Doo::loadModel('ScQueuedCampaigns', true);
                $savequeue = array();
                foreach ($queue as $batch) {
                    $tmpschar = array();
                    $tmpschar['sms_shoot_id'] = $params['shootid'];
                    $tmpschar['user_id'] = $params['userid'];
                    $tmpschar['route_id'] = $params['account_type'] == 1 ? intval($params['batchrouteid']) : $params['routedata']->id;
                    $tmpschar['sender_id'] = $params['senderid'];
                    $tmpschar['contacts'] = base64_encode(serialize($batch));
                    $tmpschar['pushed_via'] = $params['pushedvia'];
                    $tmpschar['count'] = sizeof($batch);
                    $tmpschar['smscount'] = $data['persmscount'];
                    $tmpschar['sms_type'] = serialize($params['smstype']);
                    $tmpschar['sms_text'] = $params['savetext'];
                    $tmpschar['submission_time'] = date(Doo::conf()->date_format_db);
                    $tmpschar['status'] = 1;
                    array_push($savequeue, $tmpschar);
                }
                if (sizeof($savequeue) > 0) $qobj->addQueueItem($savequeue);
            }
        }
    }

    public function saveSmsBatch($data, $params, $smsbatch)
    {
        $sentobj = Doo::loadModel('ScSentSms', true);
        $saveFilterCallback = array();
        $cbqobj = Doo::loadModel('ScApiCallbackQueue', true);
        $savebatch = array();
        $filteredCallback = 0; //flag
        if (intval($params['callback_url']) > 0 && ($params['dlrcode'] == 1 || $params['dlrcode'] == -1 || $params['dlrcode'] == 16)) {
            $filteredCallback = 1;
        }
        if (sizeof($smsbatch) > 0) {
            foreach ($smsbatch as $mdata) {
                if ($params['smstype']['personalize'] == 1) {
                    $smstext = $mdata['text'];
                    $smscount = $mdata['smscount'];
                    $smscost = $mdata['smscost'];
                } else {
                    $smstext = ''; //sms for non dynamic is $params['savetext'] but we store empty string in send sms table for non dynamic sms to save db space
                    $smscount = $data['persmscount'];
                    $smscost = $data['persmscost'];
                }
                if ($params['account_type'] == 0) {
                    //flat credit based account
                    $persmsprice_currency = $params['creditdata']->price;
                    $persmscost_currency = $persmsprice_currency * $smscount;
                }
                if ($params['account_type'] == 1) {
                    //mccmnc based account
                    $persmsprice_currency = $smscost / $smscount;
                    $persmscost_currency = $smscost;
                }
                if ($params['account_type'] == 2) {
                    //mccmnc based account
                    $persmsprice_currency = $params['creditdata']->price;
                    $persmscost_currency = $smscost;
                }
                $saveText = $params['smstype']['main'] == 'text' ? htmlspecialchars($smstext, ENT_QUOTES) : base64_encode(serialize($smstext));
                $tmpar = array();
                $tmpar['sms_shoot_id'] = $params['shootid'];
                $tmpar['user_id'] = $params['userid'];
                $tmpar['route_id'] = $params['account_type'] == 1 ? intval($mdata['routeid']) : $params['routedata']->id;
                $tmpar['smsc'] = $params['smsc'];
                $tmpar['sender_id'] = $params['senderid'];
                $tmpar['mobile'] = $mdata['mobile'];
                $tmpar['sms_type'] = serialize($params['smstype']);
                $tmpar['sms_text'] = $saveText;
                $tmpar['sms_count'] = $smscount;
                $tmpar['submission_time'] = date(Doo::conf()->date_format_db);
                $tmpar['sending_time'] = date(Doo::conf()->date_format_db);
                $tmpar['mccmnc'] = intval($mdata['mccmnc']);
                $tmpar['price'] = $persmsprice_currency;
                $tmpar['cost'] = $persmscost_currency;
                $tmpar['status'] = $params['smsstatus'];
                $tmpar['smppcode'] = $params['smppcode'];
                $tmpar['dlr'] = $params['dlrcode'];
                $tmpar['vendor_dlr'] = $params['dlrvendorcode'];

                array_push($savebatch, $tmpar);
                if ($filteredCallback == 1) {
                    $cbar = array();
                    $cbar['user_id'] = $params['userid'];
                    $cbar['route_id'] = $params['routedata']->id;
                    $cbar['route_title'] = $params['routedata']->title;
                    $cbar['sms_shoot_id'] = $params['shootid'];
                    $cbar['mobile'] = $mdata['mobile'];
                    $cbar['sender_id'] = $params['senderstr'];
                    $cbar['sms_count'] = $smscount;
                    $cbar['sms_sent_ts'] = date('Y-m-d H:i:s');
                    $cbar['delivery_ts'] = $params['dlrcode'] == 1  ? date('Y-m-d H:i:s', strtotime('+4 seconds')) : '';
                    $cbar['dlr'] = $params['dlrcode'];
                    $cbar['vendor_dlr'] = $params['dlrvendorcode'];
                    $cbar['operator_reply'] = $params['dlrcode'] == 1  ? 'id:' . uniqid() . ' err:000 Delivered On Priority Channel' : '';
                    $cbar['callback_url'] = intval($params['callback_url']);
                    $cbar['attempts'] = 0;
                    $cbar['status'] = 0;
                    array_push($saveFilterCallback, $cbar);
                }
            }
            if (sizeof($savebatch) > 0) $sentobj->saveSentSMS($savebatch);
            //save filtered sms in callback url queue
            if (sizeof($saveFilterCallback) > 0) $cbqobj->saveDlrQueue($saveFilterCallback);
        }
    }

    public function saveLongcourseBatch($data, $params, $batches)
    {
        $sbobj = Doo::loadModel('ScLongcourseCampaigns', true);
        $savebatch = array();
        if (sizeof($batches) > 0) {
            foreach ($batches as $smsbatch) {
                $tmpar = array();
                $tmpar['sms_shoot_id'] = $params['shootid'];
                $tmpar['user_id'] = $params['userid'];
                $tmpar['route_id'] =  $params['routedata']->id;
                $tmpar['sender_id'] = $params['senderid'];
                $tmpar['contacts'] = base64_encode(serialize($smsbatch));
                $tmpar['price'] = $params['ratefactor'];
                $tmpar['total_contacts'] = sizeof($smsbatch);
                $tmpar['sms_type'] = serialize($params['smstype']);
                $tmpar['sms_text'] = $params['savetext'];
                $tmpar['sms_count'] = $data['persmscount'];
                $tmpar['submission_time'] = date(Doo::conf()->date_format_db);
                $tmpar['start_time'] = $data['longcoursedata']['start'];
                $tmpar['submission_interval'] = $data['longcoursedata']['interval'];
                $tmpar['submission_days'] = $data['longcoursedata']['days'];
                $tmpar['send_flag'] = intval($params['smsstatus']) == 0 ? 1 : 0;
                $tmpar['sms_status'] = $params['smsstatus'];
                $tmpar['dlr'] = $params['dlrcode'];
                $tmpar['vendor_dlr'] = $params['dlrvendorcode'];

                array_push($savebatch, $tmpar);
            }
            if (sizeof($savebatch) > 0) $sbobj->addBatch($savebatch);
        }
    }

    public function storeSummaryData($data, $params)
    {
        //create entry in sms summary table
        $sumobj = Doo::loadModel('ScSmsSummary', true);
        $sumobj->campaign_id = $params['campaignid'];
        $sumobj->sms_shoot_id = $params['shootid'];
        $sumobj->user_id = $params['userid'];
        $sumobj->route_id = $params['account_type'] == 1 ? $params['smsplan']['id'] : $params['routedata']->id;
        $sumobj->sender_id = $params['senderid'];
        $sumobj->contacts = $params['account_type'] == 1 ? $data['totalsubmitted'] : sizeof($data['dlrfilcontacts']);
        $sumobj->dropped_contacts = $data['totaldropped'];
        $sumobj->invalid_contacts = sizeof($data['invalidcontacts']);
        $sumobj->blacklist_contacts = sizeof($data['blmatchedcontacts']);
        $sumobj->duplicates_removed = $data['duplicatesremoved'];
        $sumobj->smscount = $data['persmscount'];
        $sumobj->cc_rule = $params['account_type'] == 1 ?: $params['routedata']->credit_rule;
        $sumobj->credits_charged = $params['account_type'] == 1 ? $data['totalsmscost'] : $data['totalsmscredits'];
        $sumobj->pushed_via = $params['pushedvia'];
        $sumobj->count = $params['account_type'] == 1 ? $data['totalsubmitted'] : sizeof($data['dlrfilcontacts']) + sizeof($data['invalidcontacts']) + sizeof($data['blmatchedcontacts']) + sizeof($data['droppedcontacts']) + $data['duplicatesremoved'];
        $sumobj->sms_type = serialize($params['smstype']);
        $sumobj->sms_text = $params['savetext'];
        $sumobj->submission_time = date(Doo::conf()->date_format_db);
        $sumobj->schedule_data = serialize(array('schedule' => $data['schflag'], 'time' => $data['actualschtime']));
        $sumobj->hide_mobile = $params['phonebookflag'];
        $sumobj->contacts_label = $params['contact_label'];
        $sumobj->vsms_data = !$params['verified_sms'] ? '' : json_encode($params['verified_sms']);
        $sumobj->tlv_data = !$params['tlv'] ? '' : json_encode($params['tlv']);
        $sumobj->status = $params['summarystatus'];
        $sumobj->platform_data = serialize($params['osdata']);
        Doo::db()->insert($sumobj);

        //log platform and user details
        if ($params['summarystatus'] == 3) {
            $actData['activity_type'] = 'SPAM CAMPAIGN';
            $actData['activity'] = Doo::conf()->spam_campaign_alert . '|| CAMPAIGN-ID: ' . $params['shootid'];
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($params['userid'], $actData);

            //notify admin
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->spam_campaign_alert, 'manageSpam');
        }

        if ($params['summarystatus'] == 2) {
            $actData['activity_type'] = 'SEND CAMPAIGN';
            $actData['activity'] = Doo::conf()->temp_campaign_hold . '|| CAMPAIGN ID:' . $params['shootid'];
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($params['userid'], $actData, 1);

            //notify admin of the fact that kannel or smpp might be down
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert(1, 'danger', Doo::conf()->temp_campaign_hold, 'systemMonitor');
        }

        if ($params['summarystatus'] == 1) {
            $actData['activity_type'] = 'SMS CAMPAIGN';
            $actData['activity'] = Doo::conf()->sms_campaign_scheduled . '|| CAMPAIGN ID:' . $params['shootid'];
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($params['userid'], $actData);
        }

        if ($params['summarystatus'] == 0) {
            $actData['activity_type'] = 'SMS CAMPAIGN';
            $actData['activity'] = Doo::conf()->sms_campaign_sent . '|| CAMPAIGN ID:' . $params['shootid'];
            $ulobj = Doo::loadModel('ScLogsUserActivity', true);
            $ulobj->addLog($params['userid'], $actData);
        }
    }

    public function deductUserCredits($data, $params)
    {
        //deduct credits
        if ($params['account_type'] == '0') {
            $creobj = Doo::loadModel('ScUsersCreditData', true);
            $newavcredits = $creobj->doCreditTrans('debit', $params['userid'], $params['routedata']->id, $data['totalsmscredits']);
            //log credit activity
            $lcobj = Doo::loadModel('ScLogsCredits', true);
            $lcobj->user_id = $params['userid'];
            $lcobj->timestamp = date(Doo::conf()->date_format_db);
            $lcobj->amount = '-' . $data['totalsmscredits'];
            $lcobj->route_id = $params['routedata']->id;
            $lcobj->credits_before = $params['creditdata']->credits;
            $lcobj->credits_after = $newavcredits;
            $lcobj->reference = 'Send Campaign';
            $lcobj->comments = 'SMS campaign was sent. Details are:|| <a href="' . Doo::conf()->APP_URL . 'showDLR/' . $params['shootid'] . '/' . $params['userid'] . '">Link</a> TOTAL: ' . (sizeof($data['dlrfilcontacts']) + sizeof($data['invalidcontacts']) + sizeof($data['blmatchedcontacts']) + $data['totaldropped'] + $data['duplicatesremoved']) . ' SMS.';
            Doo::db()->insert($lcobj);
            return $newavcredits;
        } else {
            //currency based account
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $newwallet = $wlobj->doCreditTrans('deduct', $params['userid'], $data['totalsmscredits']);
            //make log entry
            $lcobj = Doo::loadModel('ScLogsCredits', true);
            $lcobj->user_id = $params['userid'];
            $lcobj->timestamp = date(Doo::conf()->date_format_db);
            $lcobj->amount = '-' . $data['totalsmscredits'];
            $lcobj->route_id = $params['routedata']->id;
            $lcobj->credits_before = $newwallet['before'];
            $lcobj->credits_after = $newwallet['after'];
            $lcobj->reference = 'Send Campaign';
            $lcobj->comments = 'SMS campaign was sent. Details are:||<a href="' . Doo::conf()->APP_URL . 'showDLR/' . $params['shootid'] . '/' . $params['userid'] . '">Link</a> TOTAL: ' . (sizeof($data['dlrfilcontacts']) + sizeof($data['invalidcontacts']) + sizeof($data['blmatchedcontacts']) + $data['totaldropped'] + $data['duplicatesremoved']) . ' SMS.';
            Doo::db()->insert($lcobj);
            return $newwallet['after'];
        }
    }

    public function flip_isset_diff($allcontacts, $blacklistmatches, $account_type = 1)
    {
        $blacklistmatches = array_flip($blacklistmatches);
        $withoutblacklist = array();
        $blacklistSorted = array();
        $blacklistCredits = 0;
        foreach ($allcontacts as $item) {
            if (!isset($blacklistmatches[$item['mobile']])) {
                $withoutblacklist[] = $item;
            } else {
                $blacklistSorted[] = $item;
                $blacklistCredits += $item['smscost'];
            }
        }

        return array($withoutblacklist, $blacklistSorted, $blacklistCredits);
    }

    public static function checkOverrideMatch($action, $input, $value)
    {
        if ($action == 0) {
            return true;
        }
        if ($action == 'equal') {
            return $input == $value ? true : false;
        }
        if ($action == 'start') {
            return substr($value, 0, strlen($input)) == $input ? true : false;
        }
        if ($action == 'end') {
            return substr($value, -strlen($input)) == $input ? true : false;
        }
        if ($action == 'has') {
            return strpos($value, $input) !== false ? true : false;
        }
        if ($action == 'nothave') {
            return strpos($value, $input) === false ? true : false;
        }
    }

    public static function applyContentOverride($action, $input, $value, $data = array())
    {
        if ($action == 'replace') {
        }
        if ($action == 'prepend') {
            return $input . $value;
        }
        if ($action == 'append') {
            return $value . $input;
        }
        if ($action == 'remove') {
            //remove prefix

        }
        if ($action == 'reject') {
            return 'reject';
        }
    }
    /*
API responses
*/
    public function getApiResponse($mode, $reason, $data = array())
    {
        $output = array();
        if ($reason == 'maintenance') {
            $output['result'] = 'error';
            $output['message'] = 'APP is in Maintenance Mode. SENDSMS not Allowed';
        }
        if ($reason == 'invalidkey') {
            $output['result'] = 'error';
            $output['message'] = 'INVALID API KEY';
        }
        if ($reason == 'apidisable') {
            $output['result'] = 'error';
            $output['message'] = 'API ACCESS IS DISABLED BY ADMINISTRATOR';
        }
        if ($reason == 'invalidroute') {
            $output['result'] = 'error';
            $output['message'] = 'ROUTE ID SUPPLIED WAS INVALID';
        }
        if ($reason == 'invalidsender') {
            $output['result'] = 'error';
            if ($data['reason'] == 'len') {
                $output['message'] = 'MAXIMUM ALLOWED LENGTH OF ' . intval($data['maxlen']) . ' FOR SENDER ID EXCEEDED.';
            } else {
                $output['message'] = 'INVALID SENDER ID SUPPLIED';
            }
        }
        if ($reason == 'invalidtemplate') {
            $output['result'] = 'error';
            $output['message'] = 'SMS TEMPLATE MISMATCH. ROUTE ALLOWS APPROVED SMS TEMPLATES ONLY';
        }
        if ($reason == 'invalidtime') {
            $output['result'] = 'error';
            $output['message'] = 'INVALID SCHEDULE TIME. CANNOT SCHEDULE CAMPAIGN IN THE PAST';
        }
        if ($reason == 'timemismatch') {
            $output['result'] = 'error';
            $output['message'] = 'ROUTE HAS TIMING RESTRICTIONS. CANNOT SUBMIT CAMPAIGN AT THE MOMENT';
        }
        if ($reason == 'schtimemismatch') {
            $output['result'] = 'error';
            $output['message'] = 'ROUTE HAS TIMING RESTRICTIONS. CANNOT SCHEDULE CAMPAIGN FOR THE SUPPLIED TIME';
        }
        if ($reason == 'lowcredits') {
            $output['result'] = 'error';
            $output['message'] = 'INSUFFICIENT CREDITS OR VALIDTY EXPIRED';
        }
        if ($reason == 'spamcampaign') {
            if ($data['mode'] == 'notify') {
                $output['result'] = 'error';
                $output['message'] = 'SPAM KEYWORDS WERE DETECTED IN THE SMS TEXT. PLEASE TRY AGAIN';
            } else {
                $output['result'] = 'success';
                $output['message'] = 'SPAM CONTENT DETECTED. CAMPAIGN HELD FOR APPROVAL';
                $output['sms_shoot_id'] = $data['shootid'];
            }
        }
        if ($reason == 'submitted') {
            $output['result'] = 'success';
            $output['message'] = 'SMS SUBMITTED SUCCESSFULLY';
            $output['sms_shoot_id'] = $data['shootid'];
        }
        if ($reason == 'emptysms') {
            $output['result'] = 'error';
            $output['message'] = 'SMS CONTENT CANNOT BE EMPTY.';
        }
        return $mode == 'http' ? DooSmppcubeHelper::getApiJsonOutput($output) : DooSmppcubeHelper::getApiXmlOutput($output);
    }

    private static function getApiJsonOutput($data)
    {
        return json_encode($data);
        die;
    }

    public static function getApiXmlOutput($data)
    {
        $xmlstr = '<?xml version="1.0" encoding="UTF-8"?>';
        if ($data['result'] == 'error') {
            $xmlstr .= '
                    <Message_resp>
                        <result>error</result>
                        <message>' . $data['message'] . '</message>
                    </Message_resp>';
        }
        if ($data['result'] == 'success') {
            $xmlstr .= '
                    <Message_resp>
                        <result>success</result>
                        <message>' . $data['message'] . '</message>
                        <sms_shoot_id>' . $data['sms_shoot_id'] . '</sms_shoot_id>
                    </Message_resp>';
        }
        return $xmlstr;
    }
    /* MISC functions */

    public static function readXls($filepath, $mode, $sheet = 'all', $column = 'A')
    {
        Doo::loadHelper("PHPExcel");
        $output = array();
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_memcache;
        $cacheSettings = array(
            'memcacheServer'  => 'localhost',
            'memcachePort'    => 11211,
            'cacheTime'       => 600
        );
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        //--  Identify the type of file
        $inputFileType = PHPExcel_IOFactory::identify($filepath);
        //--  Create a new Reader of the type that has been identified
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);

        $xlobj = $objReader->load($filepath);
        if ($sheet == 'all') {
            //fetch all sheets and columns for first sheet
            $sheets = $xlobj->getSheetNames();
            $columns = array();
            $columns['A'] = $xlobj->getSheetByName($sheets[0])->getCell('A1')->getValue();
            $columns['B'] = $xlobj->getSheetByName($sheets[0])->getCell('B1')->getValue();
            $columns['C'] = $xlobj->getSheetByName($sheets[0])->getCell('C1')->getValue();
            $columns['D'] = $xlobj->getSheetByName($sheets[0])->getCell('D1')->getValue();
            $columns['E'] = $xlobj->getSheetByName($sheets[0])->getCell('E1')->getValue();
            $columns['F'] = $xlobj->getSheetByName($sheets[0])->getCell('F1')->getValue();
            $columns['G'] = $xlobj->getSheetByName($sheets[0])->getCell('G1')->getValue();
            $columns['H'] = $xlobj->getSheetByName($sheets[0])->getCell('H1')->getValue();
            $columns['I'] = $xlobj->getSheetByName($sheets[0])->getCell('I1')->getValue();
            $columns['J'] = $xlobj->getSheetByName($sheets[0])->getCell('J1')->getValue();

            $output['sheets'] = $sheets;
            $output['cols'] = $columns;
            //to get accurate mobile count
            $mobcnt = 0;
            for ($i = 2; $i <= $xlobj->getSheetByName($sheets[0])->getHighestRow(); $i++) {
                $mobile = intval(trim($xlobj->getSheetByName($sheets[0])->getCell('A' . $i)->getValue()));
                if ($mobile != 0) {
                    $mobcnt++;
                }
            }
            $output['totalrows'] = $mobcnt;
            return json_encode($output);
        }
        //A sheet is selected so load all the columns and count rows from first one
        if ($mode == 'columns' || $mode == 'colcount') {
            $columns = array();
            $columns['A'] = $xlobj->getSheetByName($sheet)->getCell('A1')->getValue();
            $columns['B'] = $xlobj->getSheetByName($sheet)->getCell('B1')->getValue();
            $columns['C'] = $xlobj->getSheetByName($sheet)->getCell('C1')->getValue();
            $columns['D'] = $xlobj->getSheetByName($sheet)->getCell('D1')->getValue();
            $columns['E'] = $xlobj->getSheetByName($sheet)->getCell('E1')->getValue();
            $columns['F'] = $xlobj->getSheetByName($sheet)->getCell('F1')->getValue();
            $columns['G'] = $xlobj->getSheetByName($sheet)->getCell('G1')->getValue();
            $columns['H'] = $xlobj->getSheetByName($sheet)->getCell('H1')->getValue();
            $columns['I'] = $xlobj->getSheetByName($sheet)->getCell('I1')->getValue();
            $columns['J'] = $xlobj->getSheetByName($sheet)->getCell('J1')->getValue();
            $output['cols'] = $columns;
            //to get accurate mobile count
            $mobcnt = 0;
            for ($i = 2; $i <= $xlobj->getSheetByName($sheet)->getHighestRow(); $i++) {
                $mobile = intval(trim($xlobj->getSheetByName($sheet)->getCell($column . $i)->getValue()));
                if ($mobile != 0) {
                    $mobcnt++;
                }
            }
            $output['totalrows'] = $mobcnt;
            return json_encode($output);
        }
    }

    public static function uuidv4()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function exportAsCsv($data, $filename, $delimiter = "\t")
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        // clean output buffer
        ob_end_clean();

        $handle = fopen('php://output', 'w');

        // use as column titles
        fputcsv($handle, $data['columns'], $delimiter);

        foreach ($data['rows'] as $value) {
            fputcsv($handle, $value, $delimiter);
        }

        fclose($handle);

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();
    }

    public static function convertCsvToArray($csvFile)
    {
        $lines = array();
        $file_to_read = fopen($csvFile, 'r');

        while (!feof($file_to_read)) {

            array_push($lines, fgetcsv($file_to_read, 1000, ","));
        }

        fclose($file_to_read);

        return $lines;
    }

    public static function isArrayEqual($a, $b)
    {
        return (is_array($a)
            && is_array($b)
            && count($a) == count($b)
            && array_diff($a, $b) === array_diff($b, $a)
        );
    }

    //AES encryption 
    public static function aesEncrypt($plaintext)
    {
        $cipher = "aes-256-cbc"; // Or aes-128-cbc
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen); //16
        $ciphertext = openssl_encrypt($plaintext, $cipher, Doo::conf()->nodephp_aes_key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $ciphertext);
    }

    public static function aesDecrypt($ciphertext_base64)
    {
        $cipher = "aes-256-cbc"; // Or aes-128-cbc
        $ciphertext = base64_decode($ciphertext_base64);
        $ivlen = openssl_cipher_iv_length($cipher); //16
        $iv = substr($ciphertext, 0, $ivlen);
        $ciphertext = substr($ciphertext, $ivlen);
        $plaintext = openssl_decrypt($ciphertext, $cipher, Doo::conf()->nodephp_aes_key, OPENSSL_RAW_DATA, $iv);
        return $plaintext;
    }
}
