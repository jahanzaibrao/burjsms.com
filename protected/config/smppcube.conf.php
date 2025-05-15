<?php
//******************** SMPPcube Config ********************//
include('main.conf.php');


//-------------- DEFAULT ADDONS PERMISSIONS -----------------//
include('addon_perm.conf.php');


//------------ MESSAGING -------------------//
include('messaging.conf.php');


//--------------- NEW ACCOUNT DEFAULTS --------------------//
include('new_accounts.conf.php');

//---------------- SIGN UP SETTINGS ----------------------//
include('signup.conf.php');

//---------------- KANNEL SETTINGS ----------------------//
// These variables MUST match your kannel.conf configuration
include('kannel.conf.php');



include('misc.conf.php');

include('avatar.conf.php');

include('security.conf.php');

include('reseller.conf.php');

include('logsAlertsText.conf.php');

include('smtp.conf.php');

//----

$config['tinyurl'] = 'hnd.ink';
$config['whitelist_threshold'] = 20;
$config['credit_counter_dir'] = 'l2h'; //count credits on composeSMS page low to high or high to low
$config['wallet_refund_on_route_delete'] = 1; //1=enable, 0=disable
$config['ip_lookup_db_path'] = 'ipdb/IP2LOCATION-LITE-DB5.BIN';
$config['search_api_auth_url'] = $config['APP_URL'] . 'hypernode/auth/';
$config['smpp_server_tlv_file'] = '/var/www/hypernode/kannel_tlvs.json';
$config['node_server_monitor_file'] = '/var/www/html/monitor.json';
$config['smpp_host'] = $config['server_ip'];
$config['smpp_port'] = '2775';
$config['hexpass'] = '';
$config['low_credit_alert_threshold'] = 5000;
$config['match_mo_vmn_with_sender'] = 1; //1=enable, 0=disable. This will match the sender id with incoming sms VMN to immidiately match the user and show this sms in that user inbox
$config['nginx_conf_template'] = 'server {
        listen 80;
        root /var/www/html;
        index index.php index.html index.htm index.nginx-debian.html;
        server_name yourdomain;

        location / {
                try_files $uri $uri/ /index.php$is_args$args;
        }

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        }
        location /global/{
                #Do nothing
        }

        location ~ /\.ht {
                deny all;
        }

        #browser caching of static assets
                location ~*  \.(jpg|jpeg|png|gif|ico|css|js)$ {
                expires 180d;
        }
}';
$config['tlv_categories'] = array(
        "DLT_ENTITY_ID",
        "DLT_TEMPLATE_ID",
        "DLT_TELEMARKETER_ID",
        "DEST_ADDR_SUBUNIT",
        "DEST_NETWORK_TYPE",
        "DEST_BEARER_TYPE",
        "DEST_TELEMATICS_ID",
        "SOURCE_ADDR_SUBUNIT",
        "SOURCE_NETWORK_TYPE",
        "SOURCE_BEARER_TYPE",
        "SOURCE_TELEMATICS_ID",
        "QOS_TIME_TO_LIVE",
        "PAYLOAD_TYPE",
        "ADDITIONAL_STATUS_INFO_TEXT",
        "RECEIPTED_MESSAGE_ID",
        "MS_MSG_WAIT_FACILITIES",
        "PRIVACY_INDICATOR",
        "SOURCE_SUBADDRESS",
        "DEST_SUBADDRESS",
        "USER_MESSAGE_REFERENCE",
        "USER_RESPONSE_CODE",
        "SOURCE_PORT",
        "DEST_PORT",
        "SAR_MSG_REF_NUM",
        "LANGUAGE_INDICATOR",
        "SAR_TOTAL_SEGMENTS",
        "SAR_SEGMENT_SEQNUM",
        "SC_INTERFACE_VERSION",
        "CALLBACK_NUM_PRES_IND",
        "CALLBACK_NUM_ATAG",
        "NUMBER_OF_MESSAGES",
        "CALLBACK_NUM",
        "DPF_RESULT",
        "SET_DPF",
        "MS_AVAILABILITY_STATUS",
        "NETWORK_ERROR_CODE",
        "MESSAGE_PAYLOAD",
        "DELIVERY_FAILURE_REASON",
        "MORE_MESSAGES_TO_SEND",
        "MESSAGE_STATE",
        "CONGESTION_STATE",
        "USSD_SERVICE_OP",
        "BROADCAST_CHANNEL_INDICATOR",
        "BROADCAST_CONTENT_TYPE",
        "BROADCAST_CONTENT_TYPE_INFO",
        "BROADCAST_MESSAGE_CLASS",
        "BROADCAST_REP_NUM",
        "BROADCAST_FREQUENCY_INTERVAL",
        "BROADCAST_AREA_IDENTIFIER",
        "BROADCAST_ERROR_STATUS",
        "BROADCAST_AREA_SUCCESS",
        "BROADCAST_END_TIME",
        "BROADCAST_SERVICE_GROUP",
        "BILLING_IDENTIFICATION",
        "SOURCE_NETWORK_ID",
        "DEST_NETWORK_ID",
        "SOURCE_NODE_ID",
        "DEST_NODE_ID",
        "DEST_ADDR_NP_RESOLUTION",
        "DEST_ADDR_NP_INFORMATION",
        "DEST_ADDR_NP_COUNTRY",
        "DISPLAY_TIME",
        "SMS_SIGNAL",
        "MS_VALIDITY",
        "ALERT_ON_MESSAGE_DELIVERY",
        "ITS_REPLY_TYPE",
        "ITS_SESSION_INFO"
);
date_default_timezone_set($config['default_server_timezone']);
$config['smd_tnc_flag'] = 1;
$config['smd_sendsms_warning'] = 0;
$config['add_auto_stop_button'] = 0;
$config['archive_api_url'] = 'http://localhost:5306/archive/';
$config['force_offline_payment'] = 0;
$config["gvsms_plugin"] = false;
$config["paystack_ng_pg"] = 0;
$config["top_payment_button"] = 0;
$config["default_user_type"] = 2;
$config['http_apivendor'] = 0;
$config['mms_vendor'] = 0;
$config['show_license'] = 0;
$config['sender_noc'] = 1;
$config['gui_mode'] = 0; // 0 - default, 1 = minimal (by sms handover)
$config['custom_theme_id'] = 2; // 0 = no custom theme, else add custom style
$config['custom_login_view'] = 'DEF'; //leave blank for default views
$config['tfa_auth_mode'] = 0; //0 - disabled 1 - email only, 2 - email and sms both
$config['tfa_admin_auth_mode'] = 0; // same as above but for admin
$config['custom_tnc_url'] = 'https://smshandover.com/terms'; //if you need to display TnC from other source like your own website
$config['peak_throughput_allowed'] = '2500'; //license allowed peak message per second for smpp

$config['sms_aes_encryption'] = 0; // 0 - disabled, 1 - enabled, Keep it disabled if you are upgrading from old system
$config['nodephp_aes_key'] = 'FIn0v2Y4WtLl9djGGhTXR8jofwX5Qn2G'; //this MUST be same as in hypernode ENV file otherwise APP will fail

$config['show_password'] = 1;

$config['gcp_client_id'] = '616784894374-3lsq2bpj46b66htkspp9lgtjfofi3tlb.apps.googleusercontent.com';
$config['recaptcha_site_id'] = '6LdpvJ0pAAAAAEM5JQ94Dpw6tXnKqkh1o-Ts-HJg';
$config['recaptcha_secret'] = '6LdpvJ0pAAAAAIOuCC63wk5nnk-J79sN3gzD9O0z';
$config['gcp_api_key'] = 'AIzaSyCurzsUlJhOCO5O95TkVZG3Kw4uH5xIHAA';
