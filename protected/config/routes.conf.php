<?php

/**
 * Define your URI routes here.
 *
 * $route[Request Method][Uri] = array( Controller class, action method, other options, etc. )
 *
 * RESTful api support, *=any request method, GET PUT POST DELETE
 * POST 	Create
 * GET      Read
 * PUT      Update, Create
 * DELETE 	Delete
 *
 * Use lowercase for Request Method
 *
 * If you have your controller file name different from its class name, eg. home.php HomeController
 * $route['*']['/'] = array('home', 'index', 'className'=>'HomeController');
 *
 * If you need to reverse generate URL based on route ID with DooUrlBuilder in template view, please defined the id along with the routes
 * $route['*']['/'] = array('HomeController', 'index', 'id'=>'home');
 *
 * If you need dynamic routes on root domain, such as http://facebook.com/username
 * Use the key 'root':  $route['*']['root']['/:username'] = array('UserController', 'showProfile');
 *
 * If you need to catch unlimited parameters at the end of the url, eg. http://localhost/paramA/paramB/param1/param2/param.../.../..
 * Use the key 'catchall': $route['*']['catchall']['/:first'] = array('TestController', 'showAllParams');
 *
 * If you have placed your controllers in a sub folder, eg. /protected/admin/EditStuffController.php
 * $route['*']['/'] = array('admin/EditStuffController', 'action');
 *
 * If you want a module to be publicly accessed (without using Doo::app()->getModule() ) , use [module name] ,   eg. /protected/module/forum/PostController.php
 * $route['*']['/'] = array('[forum]PostController', 'action');
 *
 * If you create subfolders in a module,  eg. /protected/module/forum/post/ListController.php, the module here is forum, subfolder is post
 * $route['*']['/'] = array('[forum]post/PostController', 'action');
 *
 * Aliasing give you an option to access the action method/controller through a different URL. This is useful when you need a different url than the controller class name.
 * For instance, you have a ClientController::new() . By default, you can access via http://localhost/client/new
 *
 * $route['autoroute_alias']['/customer'] = 'ClientController';
 * $route['autoroute_alias']['/company/client'] = 'ClientController';
 *
 * With the definition above, it allows user to access the same controller::method with the following URLs:
 * http://localhost/company/client/new
 *
 * To define alias for a Controller inside a module, you may use an array:
 * $route['autoroute_alias']['/customer'] = array('controller'=>'ClientController', 'module'=>'example');
 * $route['autoroute_alias']['/company/client'] = array('controller'=>'ClientController', 'module'=>'example');
 *
 * Auto routes can be accessed via URL pattern: http://domain.com/controller/method
 * If you have a camel case method listAllUser(), it can be accessed via http://domain.com/controller/listAllUser or http://domain.com/controller/list-all-user
 * In any case you want to control auto route to be accessed ONLY via dashed URL (list-all-user)
 *
 * $route['autoroute_force_dash'] = true;	//setting this to false or not defining it will keep auto routes accessible with the 2 URLs.
 *
 */



$admin = array('admin' => '1234');
$root = array('root' => '123!@#');


/* New Routes definitions */

$route['*']['/error'] = array('ErrorController', 'index');
$route['*']['/denied'] = array('ErrorController', 'denied');
$route['*']['/blocked'] = array('ErrorController', 'blocked');

// - Main Controller
$route['*']['/app'] = array('MainController', 'legacyRedirect');
$route['*']['/app/:params'] = array('MainController', 'legacyRedirect');
$route['*']['/app/web/:params'] = array('MainController', 'legacyRedirect');
$route['*']['/renderTest'] = array('MainController', 'renderTest');
$route['*']['/expired'] = array('AuthController', 'expired');
$route['*']['/systemError/:eid'] = array('AuthController', 'expired');


$route['*']['/'] = array('MainController', 'appHome');
$route['*']['root']['/:tinyurl'] = array('MainController', 'appHome');
$route['*']['/web/:page'] = array('MainController', 'appHome');
$route['*']['/getScText'] = array('MainController', 'getScText');
$route['post']['/checkAvailability'] = array('MainController', 'checkAvailability');
$route['post']['/regNewAccount'] = array('MainController', 'regNewAccount');
$route['post']['/passwordReset'] = array('MainController', 'passwordReset');
$route['*']['/Dashboard'] = array('MainController', 'dashboard');
$route['post']['/submitGwTestSms'] = array('MainController', 'submitGwTestSms');
$route['post']['/getSelPlanOptionsOuter'] = array('MainController', 'getSelPlanOptionsOuter');
$route['post']['/getPlanSmsPriceOuter'] = array('MainController', 'getPlanSmsPriceOuter');
$route['*']['/scProcessPayment/:data'] = array('MainController', 'scProcessPayment');
$route['*']['/scPaymentReturn/:data'] = array('MainController', 'scPaymentReturn');
$route['post']['/saveContactLead'] = array('MainController', 'saveContactLead');
$route['post']['/generateDateExample'] = array('MainController', 'generateDateExample');
$route['*']['/createCaptcha'] = array('MainController', 'createCaptcha');
$route['*']['/createCaptcha/:random'] = array('MainController', 'createCaptcha');
$route['post']['/verifyResetPassOtp'] = array('MainController', 'verifyResetPassOtp');
$route['post']['/resetOuterVerifiedPassword'] = array('MainController', 'resetOuterVerifiedPassword');
$route['*']['/unserializeUtility'] = array('MainController', 'unserializeUtility');
$route['*']['/getNdncCodes'] = array('MainController', 'getNdncCodes');
$route['*']['/encryptData'] = array('MainController', 'encryptData');
$route['*']['/getCallbackConfigData/:var'] = array('MainController', 'getCallbackConfigData');
$route['*']['/finishWabaOnboarding/:tok'] = array('MainController', 'finishWabaOnboarding');
// - Auth Controller
$route['post']['/auth/authService'] = array('AuthController', 'authUser');
$route['*']['/apiAuth/:loginid/:password'] = array('AuthController', 'apiAuth');

// - Client Controller
$route['*']['/manageSenderId'] = array('ClientController', 'manageSenderId');
$route['*']['/getAllSenders'] = array('ClientController', 'getAllSenders');
$route['*']['/getAllSenders/:id'] = array('ClientController', 'getAllSenders');
$route['*']['/addSender'] = array('ClientController', 'addSender');
$route['*']['/editSender/:id'] = array('ClientController', 'editSender');
$route['*']['/deleteSender/:id'] = array('ClientController', 'deleteSender');
$route['post']['/saveSender'] = array('ClientController', 'saveSender');
$route['*']['/manageTemplates'] = array('ClientController', 'manageTemplates');
$route['*']['/getAllTemplates'] = array('ClientController', 'getAllTemplates');
$route['*']['/getAllTemplates/:id'] = array('ClientController', 'getAllTemplates');
$route['*']['/getUseTemplates'] = array('ClientController', 'getUseTemplates');
$route['*']['/addTemplate'] = array('ClientController', 'addTemplate');
$route['*']['/editTemplate/:id'] = array('ClientController', 'editTemplate');
$route['*']['/deleteTemplate/:id'] = array('ClientController', 'deleteTemplate');
$route['post']['/saveTemplate'] = array('ClientController', 'saveTemplate');
$route['*']['/manageGroups'] = array('ClientController', 'manageGroups');
$route['*']['/getAllGroups'] = array('ClientController', 'getAllGroups');
$route['*']['/addGroup'] = array('ClientController', 'addGroup');
$route['*']['/editGroup/:id'] = array('ClientController', 'editGroup');
$route['*']['/moveContacts/:id'] = array('ClientController', 'moveContacts');
$route['post']['/saveMoveContacts'] = array('ClientController', 'saveMoveContacts');
$route['*']['/deleteGroup/:id'] = array('ClientController', 'deleteGroup');
$route['post']['/saveGroup'] = array('ClientController', 'saveGroup');
$route['*']['/viewContacts/:gid'] = array('ClientController', 'manageContacts');
$route['*']['/getGroupContacts/:gid'] = array('ClientController', 'getGroupContacts');
$route['*']['/addContact/:gid'] = array('ClientController', 'addContact');
$route['*']['/importContacts'] = array('ClientController', 'importContacts');
$route['*']['/editContact/:gid/:id'] = array('ClientController', 'editContact');
$route['*']['/deleteContact/:id'] = array('ClientController', 'deleteContact');
$route['*']['/delManyContacts'] = array('ClientController', 'delManyContacts');
$route['post']['/saveContacts'] = array('ClientController', 'saveContacts');
$route['*']['/composeSMS'] = array('ClientController', 'composeSMS');
$route['*']['/composeMMS'] = array('ClientController', 'composeMMS');
$route['*']['/composeRCS'] = array('ClientController', 'composeRCS');
$route['*']['/getCreditCountRuleDetails/:id'] = array('ClientController', 'getCreditCountRuleDetails');
$route['post']['/processCampaign'] = array('ClientController', 'processCampaign');
$route['*']['/transactionReports'] = array('ClientController', 'viewTransactionReports');
$route['*']['/getMyTransactions'] = array('ClientController', 'getMyTransactions');
$route['*']['/getMyTransactions/:dr'] = array('ClientController', 'getMyTransactions');
$route['*']['/getMyTransactions/:dr/:uid'] = array('ClientController', 'getMyTransactions');
$route['*']['/showDlrSummary'] = array('ClientController', 'showDlrSummary');
$route['*']['/getMySmsCampaigns'] = array('ClientController', 'getMySmsCampaigns');
$route['*']['/getMySmsCampaigns/:cid'] = array('ClientController', 'getMySmsCampaigns');
$route['*']['/getMySmsCampaigns/:cid/:dr'] = array('ClientController', 'getMySmsCampaigns');
$route['*']['/getMySmsCampaigns/:cid/:dr/:uid'] = array('ClientController', 'getMySmsCampaigns');
$route['*']['/getMySmsCampaigns/:cid/:dr/:uid/:sort'] = array('ClientController', 'getMySmsCampaigns');
$route['*']['/showDLR/:id'] = array('ClientController', 'showDLR');
$route['*']['/showDLR/:id/:uid'] = array('ClientController', 'showDLR');
$route['*']['/getDlrSummary/:id'] = array('ClientController', 'getDlrSummary');
$route['*']['/getMySentSms/:id'] = array('ClientController', 'getMySentSms');
$route['*']['/getMySentSms/:id/:uid'] = array('ClientController', 'getMySentSms');
$route['*']['/resendCampaign/:mode/:id'] = array('ClientController', 'resendCampaign');
$route['*']['/getUserDashStats'] = array('ClientController', 'getUserDashStats');
$route['*']['/getUserSmsActivity/:dr'] = array('ClientController', 'getUserSmsActivity');
$route['*']['/getRecentTransactions'] = array('ClientController', 'getRecentTransactions');
$route['*']['/getRecentCampaigns'] = array('ClientController', 'getRecentCampaigns');
$route['*']['/reloadCreditData'] = array('ClientController', 'reloadCreditData');
$route['*']['/manageDocs'] = array('ClientController', 'manageDocs');
$route['*']['/getUserDocs/:type/:dr'] = array('ClientController', 'getUserDocs');
$route['*']['/getUserDocs/:type/:dr/:limit'] = array('ClientController', 'getUserDocs');
$route['*']['/addNewDocument'] = array('ClientController', 'addNewDocument');
$route['post']['/saveDocument'] = array('ClientController', 'saveDocument');
$route['*']['/viewDocument/:id'] = array('ClientController', 'viewDocument');
$route['post']['/postSharedUsers'] = array('ClientController', 'postSharedUsers');
$route['post']['/rmvSharedUsr'] = array('ClientController', 'rmvSharedUsr');
$route['post']['/postFileComment'] = array('ClientController', 'postFileComment');
$route['*']['/globalFileDownload/:mode/:id'] = array('ClientController', 'globalFileDownload');
$route['post']['/documentReupload'] = array('ClientController', 'documentReupload');
$route['*']['/deleteDocument/:id'] = array('ClientController', 'deleteDocument');
$route['*']['/supportTickets'] = array('ClientController', 'supportTickets');
$route['*']['/getMyTickets'] = array('ClientController', 'getMyTickets');
$route['*']['/getMyTickets/:dr'] = array('ClientController', 'getMyTickets');
$route['*']['/addNewTicket'] = array('ClientController', 'addNewTicket');
$route['post']['/saveSupportTicket'] = array('ClientController', 'saveSupportTicket');
$route['*']['/viewTicket/:id'] = array('ClientController', 'viewTicket');
$route['post']['/postTicketComment'] = array('ClientController', 'postTicketComment');
$route['*']['/refundLog'] = array('ClientController', 'refundLog');
$route['*']['/getRefundLog'] = array('ClientController', 'getRefundLog');
$route['*']['/getRefundLog/:dr'] = array('ClientController', 'getRefundLog');
$route['*']['/creditLog'] = array('ClientController', 'creditLog');
$route['*']['/getCreditLog'] = array('ClientController', 'getCreditLog');
$route['*']['/getCreditLog/:dr'] = array('ClientController', 'getCreditLog');
$route['*']['/getCreditLog/:dr/:uid'] = array('ClientController', 'getCreditLog');
$route['*']['/userSmsLog'] = array('ClientController', 'userSmsLog');
$route['*']['/getUserSmsLog'] = array('ClientController', 'getUserSmsLog');
$route['*']['/getUserSmsLog/:dr'] = array('ClientController', 'getUserSmsLog');
$route['*']['/getUserSmsLog/:dr/:sid'] = array('ClientController', 'getUserSmsLog');
$route['*']['/getUserSmsLog/:dr/:sid/:rid'] = array('ClientController', 'getUserSmsLog');
$route['*']['/api'] = array('ClientController', 'viewDevApi');
$route['*']['/xmlApi'] = array('ClientController', 'viewXmlApi');
$route['*']['/legacyApi'] = array('ClientController', 'legacyApi');
$route['*']['/otpApi'] = array('ClientController', 'viewOtpApi');
$route['*']['/whatsappApi'] = array('ClientController', 'whatsappApi');
$route['*']['/rcsApi'] = array('ClientController', 'rcsApi');
$route['*']['/regAPIKey'] = array('ClientController', 'regAPIKey');
$route['*']['/smsStats'] = array('ClientController', 'smsStats');
$route['*']['/getSmsStatsReport/:dr'] = array('ClientController', 'getSmsStatsReport');
$route['*']['/smsArchive'] = array('ClientController', 'smsArchive');
$route['*']['/getArchivedFiles'] = array('ClientController', 'getArchivedFiles');
$route['post']['/saveArchiveFetchTask'] = array('ClientController', 'saveArchiveFetchTask');
$route['*']['/scheduledCampaigns'] = array('ClientController', 'scheduledCampaigns');
$route['*']['/getMyScheduledCampaigns'] = array('ClientController', 'getMyScheduledCampaigns');
$route['*']['/getMyScheduledCampaigns/:dr'] = array('ClientController', 'getMyScheduledCampaigns');
$route['*']['/editScheduledCampaign/:id'] = array('ClientController', 'editScheduledCampaign');
$route['post']['/saveEditScheduledCampaign'] = array('ClientController', 'saveEditScheduledCampaign');
$route['*']['/cancelSchedule/:id'] = array('ClientController', 'cancelSchedule');
$route['*']['/editUserProfile'] = array('ClientController', 'editUserProfile');
$route['post']['/saveUserProfile'] = array('ClientController', 'saveUserProfile');
$route['*']['/verifyViaOTP/:mode'] = array('ClientController', 'verifyViaOTP');
$route['*']['/confirmOTP/:mode'] = array('ClientController', 'confirmOTP');
$route['*']['/saveUserPassword'] = array('ClientController', 'saveUserPassword');
$route['*']['/saveCompanyInfo'] = array('ClientController', 'saveCompanyInfo');
$route['*']['/saveUserSettings'] = array('ClientController', 'saveUserSettings');
$route['*']['/userSettings'] = array('ClientController', 'userSettings');
$route['*']['/viewNotifications'] = array('ClientController', 'viewNotifications');
$route['*']['/getMyAlerts'] = array('ClientController', 'getMyAlerts');
$route['*']['/getAllMyAlerts'] = array('ClientController', 'getAllMyAlerts');
$route['*']['/getAllMyAlerts/:dr'] = array('ClientController', 'getAllMyAlerts');
$route['*']['/alertRedirect/:nid/:elink'] = array('ClientController', 'alertRedirect');
$route['*']['/markAlertsRead'] = array('ClientController', 'markAlertsRead');
$route['*']['/smsapi/:data'] = array('ClientController', 'smsViaApi');
$route['*']['/app/smsapi/:data'] = array('ClientController', 'smsViaApi');
$route['*']['/purchaseCredits'] = array('ClientController', 'purchaseCredits');
$route['*']['/buyOrderCheckout'] = array('ClientController', 'buyOrderCheckout');
$route['*']['/confirmPurchaseOrder/:data'] = array('ClientController', 'confirmPurchaseOrder');
$route['*']['/scOrderProcess/:data'] = array('ClientController', 'scOrderProcess');
$route['*']['/genDynamicPreview'] = array('ClientController', 'genDynamicPreview');
$route['*']['/updateActivity'] = array('ClientController', 'updateActivity');
$route['*']['/markAsOptOut/:id'] = array('ClientController', 'markAsOptOut');
$route['*']['/spamRedirect'] = array('ClientController', 'spamRedirect');


$route['*']['/watchmanProcessMonitor'] = array('ClientController', 'watchmanProcessMonitor');
$route['*']['/queueProcess'] = array('ClientController', 'queueProcess');
$route['*']['/scheduleProcess'] = array('ClientController', 'scheduleProcess');
$route['*']['/smartScheduleProcess'] = array('ClientController', 'smartScheduleProcess');
$route['*']['/tempStoreProcess'] = array('ClientController', 'tempStoreProcess');
$route['*']['/ndncTasksProcess'] = array('ClientController', 'ndncTasksProcess');
$route['*']['/dbArchiveProcess'] = array('ClientController', 'dbArchiveProcess');
$route['*']['/archiveFetchProcess'] = array('ClientController', 'archiveFetchProcess');
$route['*']['/updateAutoArchiver'] = array('AdminController', 'updateAutoArchiver');
$route['*']['/dailyMailerProcess'] = array('ClientController', 'dailyMailerProcess');
$route['*']['/getDLR/:data'] = array('ClientController', 'updateDLR');
$route['*']['/apiVendorDlr/:provider/:data'] = array('ClientController', 'apiVendorDlr');
$route['*']['/getApiSmppDlr/:data'] = array('ClientController', 'getApiSmppDlr');
$route['*']['/manageCampaignMedia'] = array('ClientController', 'manageCampaignMedia');
$route['*']['/getAllCampaignMedia'] = array('ClientController', 'getAllCampaignMedia');
$route['*']['/getUseCampaignMedia'] = array('ClientController', 'getUseCampaignMedia');
$route['*']['/addCampaignMedia'] = array('ClientController', 'addCampaignMedia');
$route['post']['/saveCampaignMedia'] = array('ClientController', 'saveCampaignMedia');
$route['*']['/deleteCampaignMedia/:id'] = array('ClientController', 'deleteCampaignMedia');
$route['*']['/viewMedia/:id'] = array('ClientController', 'viewMedia');
$route['*']['/manageClientTlv'] = array('ClientController', 'manageClientTlv');
$route['*']['/getAllClientTlv'] = array('ClientController', 'getAllClientTlv');
$route['*']['/addClientTlv'] = array('ClientController', 'addClientTlv');
$route['post']['/saveClientTlv'] = array('ClientController', 'saveClientTlv');
$route['*']['/deleteClientTlv/:id'] = array('ClientController', 'deleteClientTlv');
$route['*']['/getUserTlvList/:rid'] = array('ClientController', 'getUserTlvList');
$route['*']['/manageOtpChannels'] = array('ClientController', 'manageOtpChannels');
$route['*']['/getAllOtpChannels'] = array('ClientController', 'getAllOtpChannels');
$route['*']['/addOtpChannel'] = array('ClientController', 'addOtpChannel');
$route['post']['/saveOtpChannel'] = array('ClientController', 'saveOtpChannel');
$route['*']['/editOtpChannel/:id'] = array('ClientController', 'editOtpChannel');
$route['*']['/deleteOtpChannel/:id'] = array('ClientController', 'deleteOtpChannel');
$route['*']['/send-otp/:data'] = array('ClientController', 'sendOtpApi');
$route['*']['/verify-otp/:data'] = array('ClientController', 'verifyOtpApi');
$route['*']['/manageVsmsAgents'] = array('ClientController', 'manageVsmsAgents');
$route['*']['/getAllVerifiedAgents'] = array('ClientController', 'getAllVerifiedAgents');
$route['*']['/addNewVsmsAgent'] = array('ClientController', 'addNewVsmsAgent');
$route['*']['/editVsmsAgent/:id'] = array('ClientController', 'editVsmsAgent');
$route['*']['/deleteVsmsAgent/:id'] = array('ClientController', 'deleteVsmsAgent');
$route['*']['/approveVsmsAgent/:id'] = array('ClientController', 'approveVsmsAgent');
$route['post']['/saveVsmsAgent'] = array('ClientController', 'saveVsmsAgent');
$route['*']['/smscLiveStatusManager'] = array('ClientController', 'smscLiveStatusManager');
$route['post']['/getCoverageRegulations'] = array('ClientController', 'getCoverageRegulations');

$route['*']['/manageWhatsappTemplates'] = array('ClientController', 'manageWhatsappTemplates');
$route['*']['/getAllWhatsappTemplates'] = array('ClientController', 'getAllWhatsappTemplates');
$route['*']['/getAllWhatsappTemplates/:id'] = array('ClientController', 'getAllWhatsappTemplates');
$route['*']['/addWhatsappTemplate'] = array('ClientController', 'addWhatsappTemplate');
$route['*']['/editWhatsappTemplate/:id'] = array('ClientController', 'editWhatsappTemplate');
$route['*']['/deleteWhatsappTemplate/:id/:tname'] = array('ClientController', 'deleteWhatsappTemplate');
$route['post']['/saveWhatsappTemplate'] = array('ClientController', 'saveWhatsappTemplate');
$route['*']['/composeWhatsappCampaign'] = array('ClientController', 'composeWhatsappCampaign');
$route['*']['/viewWhatsappReports'] = array('ClientController', 'viewWhatsappReports');
$route['*']['/syncWhatsappTemplates'] = array('ClientController', 'syncWhatsappTemplates');
$route['*']['/campaignSuccessRedirect'] = array('ClientController', 'campaignSuccessRedirect');

$route['*']['/manageRichcards'] = array('ClientController', 'manageRichcards');
$route['*']['/addNewRichcard'] = array('ClientController', 'addNewRichcard');
$route['post']['/pushWhatsAppCampaign'] = array('ClientController', 'pushWhatsAppCampaign');
$route['*']['/whatsAppNotifs'] = array('ClientController', 'whatsAppNotifs');
$route['*']['/wabaConversations/:wabaid'] = array('ClientController', 'wabaConversations');
$route['*']['/getWabaChats/:cid'] = array('ClientController', 'getWabaChats');
$route['*']['/fetchUnreadWabaChats/:cid'] = array('ClientController', 'fetchUnreadWabaChats');
$route['post']['/sendWabaChat'] = array('ClientController', 'sendWabaChat');



// - Reseller Controller
$route['*']['/manageUsers'] = array('ResellerController', 'manageUsers');
$route['*']['/getAllUsers'] = array('ResellerController', 'getAllUsers');
$route['*']['/addNewUser'] = array('ResellerController', 'addNewUser');
$route['post']['/createUserAccount'] = array('ResellerController', 'createUserAccount');
$route['post']['/getPlanSmsPrice'] = array('ResellerController', 'getPlanSmsPrice');
$route['*']['/genWebSettings'] = array('ResellerController', 'genWebSettings');
$route['post']['/saveWebSettings'] = array('ResellerController', 'saveWebSettings');
$route['*']['/signupWebSettings'] = array('ResellerController', 'signupWebSettings');
$route['post']['/saveSignupSettings'] = array('ResellerController', 'saveSignupSettings');
$route['*']['/themeWebSettings'] = array('ResellerController', 'themeWebSettings');
$route['post']['/updateThemeSettings'] = array('ResellerController', 'updateThemeSettings');
$route['*']['/homeWebSettings'] = array('ResellerController', 'homeWebSettings');
$route['*']['/aboutWebSettings'] = array('ResellerController', 'aboutWebSettings');
$route['*']['/pricingWebSettings'] = array('ResellerController', 'pricingWebSettings');
$route['*']['/contactWebSettings'] = array('ResellerController', 'contactWebSettings');
$route['*']['/loginWebSettings'] = array('ResellerController', 'loginWebSettings');
$route['post']['/saveWebPageSettings'] = array('ResellerController', 'saveWebPageSettings');
$route['*']['/getResellerStats'] = array('ResellerController', 'getResellerStats');
$route['*']['/getResellerSales/:dr'] = array('ResellerController', 'getResellerSales');
$route['*']['/getTopConsumers'] = array('ResellerController', 'getTopConsumers');
$route['*']['/getTopConsumers/:dr'] = array('ResellerController', 'getTopConsumers');
$route['*']['/getTopConsumers/:dr/:limit'] = array('ResellerController', 'getTopConsumers');
$route['*']['/getLatestOrders'] = array('ResellerController', 'getLatestOrders');
$route['*']['/getLatestOrders/:dr'] = array('ResellerController', 'getLatestOrders');
$route['*']['/getLatestOrders/:dr/:limit'] = array('ResellerController', 'getLatestOrders');
$route['*']['/viewUserAccount/:id'] = array('ResellerController', 'viewUserAccount');
$route['*']['/getClientSmsActivity'] = array('ResellerController', 'getClientSmsActivity');
$route['*']['/getClientSmsActivity/:dr'] = array('ResellerController', 'getClientSmsActivity');
$route['*']['/viewUserRouteSettings/:id'] = array('ResellerController', 'viewUserRouteSettings');
$route['*']['/viewUserSenderIds/:id'] = array('ResellerController', 'viewUserSenderIds');
$route['*']['/viewUserTemplates/:id'] = array('ResellerController', 'viewUserTemplates');
$route['*']['/viewUserDlrSummary/:id'] = array('ResellerController', 'viewUserDlrSummary');
$route['*']['/makeAccountTransaction/:id'] = array('ResellerController', 'makeAccountTransaction');
$route['post']['/processAccountTransaction'] = array('ResellerController', 'processAccountTransaction');
$route['*']['/viewUserTransactions/:id'] = array('ResellerController', 'viewUserTransactions');
$route['*']['/viewUserCreditLog/:id'] = array('ResellerController', 'viewUserCreditLog');
$route['*']['/viewUserAccountSettings/:id'] = array('ResellerController', 'viewUserAccountSettings');
$route['*']['/showUserDLR/:id/:sid'] = array('ResellerController', 'showUserDLR');
$route['post']['/markInvoiceStatus'] = array('ResellerController', 'markInvoiceStatus');
$route['post']['/markAgreementStatus'] = array('ResellerController', 'markAgreementStatus');
$route['*']['/manageSupport'] = array('ResellerController', 'manageSupport');
$route['*']['/getAssignedTickets'] = array('ResellerController', 'getAssignedTickets');
$route['*']['/getAssignedTickets/:dr'] = array('ResellerController', 'getAssignedTickets');
$route['*']['/viewMgrTicket/:id'] = array('ResellerController', 'viewMgrTicket');
$route['*']['/markTicket/:status/:id'] = array('ResellerController', 'markTicket');
$route['post']['/saveRouteAssignments'] = array('ResellerController', 'saveRouteAssignments');
$route['*']['/accountActions/:action/:uid'] = array('ResellerController', 'accountActions');
$route['post']['/websiteToggle'] = array('ResellerController', 'websiteToggle');
$route['*']['/manageInactiveUsers'] = array('ResellerController', 'manageInactiveUsers');
$route['*']['/getInactiveUserAccounts'] = array('ResellerController', 'getInactiveUserAccounts');
$route['*']['/activateUserAccount/:uid'] = array('ResellerController', 'activateUserAccount');
$route['*']['/webLeads'] = array('ResellerController', 'webLeads');
$route['*']['/getWebsiteLeads/:mode'] = array('ResellerController', 'getWebsiteLeads');

// - Admin Controller
$route['*']['/getAdminSalesStats'] = array('AdminController', 'getAdminSalesStats');
$route['*']['/getSalesSmsChart/:dr'] = array('AdminController', 'getSalesSmsChart');
$route['*']['/getSmsActivity/:dr'] = array('AdminController', 'getSmsActivity');
$route['*']['/getSystemStats'] = array('AdminController', 'getSystemStats');
$route['*']['/getTopResellers'] = array('AdminController', 'getTopResellers');
$route['*']['/getTopResellers/:dr'] = array('AdminController', 'getTopResellers');
$route['*']['/getTopResellers/:dr/:limit'] = array('AdminController', 'getTopResellers');
$route['*']['/getRouteTraffic'] = array('AdminController', 'getRouteTraffic');
$route['*']['/getRouteTraffic/:dr'] = array('AdminController', 'getRouteTraffic');
$route['*']['/manageSmpp'] = array('AdminController', 'manageSmpp');
$route['*']['/getAllSmpp'] = array('AdminController', 'getAllSmpp');
$route['*']['/addSmpp'] = array('AdminController', 'addSmpp');
$route['post']['/saveSmpp'] = array('AdminController', 'saveSmpp');
$route['*']['/editSmpp/:id'] = array('AdminController', 'editSmpp');
$route['*']['/deleteSmpp/:id'] = array('AdminController', 'deleteSmpp');
$route['post']['/changeSmppStatus'] = array('AdminController', 'changeSmppStatus');
$route['*']['/manageRoutes'] = array('AdminController', 'manageRoutes');
$route['*']['/getAllRoutes'] = array('AdminController', 'getAllRoutes');
$route['*']['/addRoute'] = array('AdminController', 'addRoute');
$route['post']['/saveRoute'] = array('AdminController', 'saveRoute');
$route['*']['/editRoute/:id'] = array('AdminController', 'editRoute');
$route['post']['/changeRouteStatus'] = array('AdminController', 'changeRouteStatus');
$route['*']['/smppDlrCodes/:id'] = array('AdminController', 'smppDlrCodes');
$route['post']['/saveSmppDlrCodes'] = array('AdminController', 'saveSmppDlrCodes');
$route['*']['/deleteRoute/:id'] = array('AdminController', 'deleteRoute');
$route['*']['/manageBlacklists'] = array('AdminController', 'manageBlacklists');
$route['*']['/getAllBlDb'] = array('AdminController', 'getAllBlDb');
$route['*']['/addBlacklistDb'] = array('AdminController', 'addBlacklistDb');
$route['*']['/uploadBlacklistData'] = array('AdminController', 'uploadBlacklistData');
$route['post']['/saveBlacklistDb'] = array('AdminController', 'saveBlacklistDb');
$route['post']['/saveBlacklistData'] = array('AdminController', 'saveBlacklistData');
$route['*']['/editBlacklistDb/:id'] = array('AdminController', 'editBlacklistDb');
$route['*']['/deleteBlacklistDb/:id'] = array('AdminController', 'deleteBlacklistDb');
$route['*']['/manualInsertBlDb/:id'] = array('AdminController', 'manualInsertBlDb');
$route['post']['/saveManualInsertBlDb'] = array('AdminController', 'saveManualInsertBlDb');
$route['*']['/manualDelBlDb/:id'] = array('AdminController', 'manualDelBlDb');
$route['post']['/saveManualDelBlDb'] = array('AdminController', 'saveManualDelBlDb');
$route['post']['/addUploadTask'] = array('AdminController', 'addUploadTask');
$route['*']['/viewBlDb/:id'] = array('AdminController', 'viewBlDb');
$route['*']['/getImportTasks/:id'] = array('AdminController', 'getImportTasks');
$route['*']['/deleteImportTask/:id'] = array('AdminController', 'deleteImportTask');
$route['*']['/numberLookupBlDb'] = array('AdminController', 'numberLookupBlDb');
$route['*']['/deleteNdncNumber/:id'] = array('AdminController', 'deleteNdncNumber');
$route['post']['/bldbActions'] = array('AdminController', 'bldbActions');
$route['*']['/manageCountRules'] = array('AdminController', 'manageCountRules');
$route['*']['/getAllCountRules'] = array('AdminController', 'getAllCountRules');
$route['*']['/addCountRule'] = array('AdminController', 'addCountRule');
$route['post']['/saveCountRule'] = array('AdminController', 'saveCountRule');
$route['*']['/editCountRule/:id'] = array('AdminController', 'editCountRule');
$route['*']['/delCountRule/:id'] = array('AdminController', 'delCountRule');
$route['*']['/manageSmsPlans'] = array('AdminController', 'manageSmsPlans');
$route['*']['/getAllSmsPlans'] = array('AdminController', 'getAllSmsPlans');
$route['*']['/addSmsPlan'] = array('AdminController', 'addSmsPlan');
$route['post']['/saveSmsPlan'] = array('AdminController', 'saveSmsPlan');
$route['*']['/editSmsPlan/:id'] = array('AdminController', 'editSmsPlan');
$route['*']['/delSmsPlan/:id'] = array('AdminController', 'delSmsPlan');
$route['post']['/getSelPlanOptions'] = array('AdminController', 'getSelPlanOptions');
$route['*']['/manageCountries'] = array('AdminController', 'manageCountries');
$route['*']['/getAllCountries'] = array('AdminController', 'getAllCountries');
$route['*']['/editCountry/:id'] = array('AdminController', 'editCountry');
$route['post']['/saveCountry'] = array('AdminController', 'saveCountry');
$route['*']['/uploadPrefixes'] = array('AdminController', 'uploadPrefixes');
$route['post']['/importPrefixes'] = array('AdminController', 'importPrefixes');
$route['*']['/viewAllOP/:id'] = array('AdminController', 'viewAllOP');
$route['*']['/getAllOP/:id'] = array('AdminController', 'getAllOP');
$route['*']['/editOP/:id'] = array('AdminController', 'editOP');
$route['post']['/saveOP'] = array('AdminController', 'saveOP');
$route['post']['/delManyOP'] = array('AdminController', 'delManyOP');
$route['*']['/deleteOP/:id/:cid'] = array('AdminController', 'deleteOP');
$route['*']['/approveSenderIds'] = array('AdminController', 'approveSenderIds');
$route['*']['/getAllPendingSenderIds'] = array('AdminController', 'getAllPendingSenderIds');
$route['*']['/approveSid/:id'] = array('AdminController', 'approveSid');
$route['*']['/reviewSid/:id'] = array('AdminController', 'reviewSid');
$route['post']['/approveManySids'] = array('AdminController', 'approveManySids');
$route['*']['/rejectSid/:id'] = array('AdminController', 'rejectSid');
$route['post']['/rejectManySids'] = array('AdminController', 'rejectManySids');
$route['*']['/approveTemplates'] = array('AdminController', 'approveTemplates');
$route['*']['/getAllPendingTemps'] = array('AdminController', 'getAllPendingTemps');
$route['*']['/approveTemp/:id'] = array('AdminController', 'approveTemp');
$route['post']['/approveManyTemps'] = array('AdminController', 'approveManyTemps');
$route['*']['/rejectTemp/:id'] = array('AdminController', 'rejectTemp');
$route['post']['/rejectManyTemps'] = array('AdminController', 'rejectManyTemps');
$route['*']['/refundRules'] = array('AdminController', 'refundRules');
$route['*']['/getRefundRules'] = array('AdminController', 'getRefundRules');
$route['*']['/addRefundRule'] = array('AdminController', 'addRefundRule');
$route['post']['/saveRefundRule'] = array('AdminController', 'saveRefundRule');
$route['*']['/editRefundRule/:id'] = array('AdminController', 'editRefundRule');
$route['*']['/delRefundRule/:id'] = array('AdminController', 'delRefundRule');
$route['*']['/kannelMonitor'] = array('AdminController', 'kannelMonitor');
$route['post']['/kannelActions'] = array('AdminController', 'kannelActions');
$route['*']['/announcements'] = array('AdminController', 'announcements');
$route['*']['/getAnnouncements'] = array('AdminController', 'getAnnouncements');
$route['post']['/setAnnouncementState'] = array('AdminController', 'setAnnouncementState');
$route['*']['/addAnnouncement'] = array('AdminController', 'addAnnouncement');
$route['post']['/saveAnnouncement'] = array('AdminController', 'saveAnnouncement');
$route['*']['/editAnnouncement/:id'] = array('AdminController', 'editAnnouncement');
$route['*']['/deleteAnnouncement/:id'] = array('AdminController', 'deleteAnnouncement');
$route['*']['/getUserActivity/:dr/:uid'] = array('AdminController', 'getUserActivity');
$route['*']['/manageStaffTeams'] = array('AdminController', 'manageStaffTeams');
$route['*']['/getAllStaffTeams'] = array('AdminController', 'getAllStaffTeams');
$route['*']['/addStaffTeam'] = array('AdminController', 'addStaffTeam');
$route['post']['/saveStaffTeam'] = array('AdminController', 'saveStaffTeam');
$route['*']['/editStaffTeam/:id'] = array('AdminController', 'editStaffTeam');
$route['*']['/delStaffTeam/:id'] = array('AdminController', 'delStaffTeam');
$route['*']['/manageStaff'] = array('AdminController', 'manageStaff');
$route['*']['/getAllStaff'] = array('AdminController', 'getAllStaff');
$route['*']['/addStaff'] = array('AdminController', 'addStaff');
$route['post']['/saveStaff'] = array('AdminController', 'saveStaff');
$route['*']['/viewStaff/:id'] = array('AdminController', 'viewStaff');
$route['*']['/delStaff/:id'] = array('AdminController', 'delStaff');
$route['post']['/switchStaff'] = array('AdminController', 'switchStaff');
$route['post']['/switchWabaPlan'] = array('AdminController', 'switchWabaPlan');
$route['post']['/switchWabaAgent'] = array('AdminController', 'switchWabaAgent');

$route['post']['/changeTeam'] = array('AdminController', 'changeTeam');
$route['post']['/saveStaffRights'] = array('AdminController', 'saveStaffRights');
$route['post']['/saveUserPermissions'] = array('AdminController', 'saveUserPermissions');
$route['post']['/saveUserSpecialFlags'] = array('AdminController', 'saveUserSpecialFlags');
$route['post']['/saveUserWhitelist'] = array('AdminController', 'saveUserWhitelist');
$route['*']['/manageSpam'] = array('AdminController', 'manageSpamCampaigns');
$route['*']['/getAllSpamCampaigns'] = array('AdminController', 'getAllSpamCampaigns');
$route['*']['/rejectSpam/:id'] = array('AdminController', 'rejectSpam');
$route['*']['/approveSpam/:id'] = array('AdminController', 'approveSpam');
$route['*']['/manageSpamKeywords'] = array('AdminController', 'manageSpamKeywords');
$route['*']['/getAllSpamKeywords'] = array('AdminController', 'getAllSpamKeywords');
$route['*']['/addSpamKeyword'] = array('AdminController', 'addSpamKeyword');
$route['post']['/saveSpamKeyword'] = array('AdminController', 'saveSpamKeyword');
$route['*']['/deleteSpamKeyword/:id'] = array('AdminController', 'deleteSpamKeyword');
$route['*']['/systemMonitor'] = array('ResellerController', 'systemMonitor');
$route['*']['/getSysmonData/:daterange'] = array('AdminController', 'getSysmonData');
$route['*']['/getAllScheduledCampaigns'] = array('ResellerController', 'getAllScheduledCampaigns');
$route['post']['/changeCampaignStatus'] = array('ResellerController', 'changeCampaignStatus');
$route['*']['/getAllTempCampaigns'] = array('AdminController', 'getAllTempCampaigns');
$route['*']['/getAllUserStatus'] = array('ResellerController', 'getAllUserStatus');
$route['*']['/getRouteTrafficInfo'] = array('AdminController', 'getRouteTrafficInfo');
$route['post']['/dropQueuedBatches'] = array('AdminController', 'dropQueuedBatches');
$route['post']['/shiftQueuedBatches'] = array('AdminController', 'shiftQueuedBatches');
$route['post']['/cancelTempCampaign'] = array('AdminController', 'cancelTempCampaign');
$route['post']['/sendScheduleManually'] = array('AdminController', 'sendScheduleManually');
$route['*']['/appSettings'] = array('AdminController', 'appSettings', 'authName' => 'Smppcube Admin', 'auth' => $root, 'authFail' => 'Unauthorized!');
$route['post']['/saveAppSettings'] = array('AdminController', 'saveAppSettings');
$route['*']['/powerGrid'] = array('AdminController', 'powerGrid');
$route['post']['/saveMiscVars'] = array('AdminController', 'saveMiscVars');
$route['post']['/addArchiveTask'] = array('AdminController', 'addArchiveTask');
$route['post']['/cancelArchiveTask'] = array('AdminController', 'cancelArchiveTask');
$route['*']['/watchmanLog'] = array('AdminController', 'watchmanLog');
$route['*']['/getWatchmanLog'] = array('AdminController', 'getWatchmanLog');
$route['*']['/getWatchmanLog/:dr'] = array('AdminController', 'getWatchmanLog');
$route['*']['/dbArchiveLog'] = array('AdminController', 'dbArchiveLog');
$route['*']['/getDbArchiveLog'] = array('AdminController', 'getDbArchiveLog');
$route['*']['/getDbArchiveLog/:dr'] = array('AdminController', 'getDbArchiveLog');
$route['*']['/setProcessStatus'] = array('AdminController', 'setProcessStatus');
$route['post']['/newBlockIpRequest'] = array('AdminController', 'newBlockIpRequest');
$route['post']['/newUnblockIpRequest'] = array('AdminController', 'newUnblockIpRequest');
$route['*']['/susActivityLog'] = array('AdminController', 'susActivityLog');
$route['*']['/getSusActivityLog'] = array('AdminController', 'getSusActivityLog');
$route['*']['/getSusActivityLog/:dr'] = array('AdminController', 'getSusActivityLog');
$route['*']['/manageBlockedIpList'] = array('AdminController', 'manageBlockedIpList');
$route['*']['/getBlockedIpList'] = array('AdminController', 'getBlockedIpList');
$route['*']['/getBlockedIpList/:dr'] = array('AdminController', 'getBlockedIpList');
$route['*']['/manuallyBlockIp'] = array('AdminController', 'manuallyBlockIp');
$route['post']['/saveBlockIps'] = array('AdminController', 'saveBlockIps');
$route['*']['/manageSSL'] = array('AdminController', 'manageSSL');
$route['*']['/getAllCertificates'] = array('AdminController', 'getAllCertificates');
$route['*']['/addNewSSL'] = array('AdminController', 'addNewSSL');
$route['post']['/installNewSSL'] = array('AdminController', 'installNewSSL');
$route['*']['/removeSSL/:domain'] = array('AdminController', 'removeSSL');
$route['post']['/sshTestConnection'] = array('AdminController', 'sshTestConnection');
$route['*']['/getAdminSmsSummary/:shootid'] = array('AdminController', 'getAdminSmsSummary');
$route['post']['/saveUserPhonebookPermissions'] = array('AdminController', 'saveUserPhonebookPermissions');
$route['*']['/adminRemoteCalls/:url'] = array('AdminController', 'adminRemoteCalls');
$route['*']['/manageSmppTlv'] = array('AdminController', 'manageSmppTlv');
$route['*']['/listAllTlvParams'] = array('AdminController', 'listAllTlvParams');
$route['*']['/addNewSmppTlv'] = array('AdminController', 'addNewSmppTlv');
$route['*']['/editSmppTlv/:id'] = array('AdminController', 'editSmppTlv');
$route['post']['/saveSmppTlv'] = array('AdminController', 'saveSmppTlv');
$route['*']['/deleteSmppTlv/:id'] = array('AdminController', 'deleteSmppTlv');
$route['*']['/advancedSmsSearch'] = array('AdminController', 'advancedSmsSearch');
$route['*']['/manageKannelInstances'] = array('AdminController', 'manageKannelInstances');
$route['*']['/listAllKannelInstances'] = array('AdminController', 'listAllKannelInstances');
$route['*']['/addNewKannelInstance'] = array('AdminController', 'addNewKannelInstance');
$route['*']['/editKannelInstance/:id'] = array('AdminController', 'editKannelInstance');
$route['post']['/saveKannelInstance'] = array('AdminController', 'saveKannelInstance');
$route['*']['/deleteKannelInstance/:id'] = array('AdminController', 'deleteKannelInstance');
$route['*']['/manageApiVendors'] = array('AdminController', 'manageApiVendors');
$route['*']['/getAllApiVendors'] = array('AdminController', 'getAllApiVendors');
$route['*']['/addApiVendor'] = array('AdminController', 'addApiVendor');
$route['post']['/saveApiVendor'] = array('AdminController', 'saveApiVendor');
$route['*']['/editApiVendor/:id'] = array('AdminController', 'editApiVendor');
$route['*']['/deleteApiVendor/:id'] = array('AdminController', 'deleteApiVendor');
$route['post']['/changeApiVendorStatus'] = array('AdminController', 'changeApiVendorStatus');
$route['*']['/license'] = array('AdminController', 'showAppLicense');
$route['*']['/manageWaba'] = array('AdminController', 'manageWaba');
$route['*']['/viewWabaAdmin'] = array('AdminController', 'viewWabaAdmin');

$route['*']['/whatsappRatePlans'] = array('AdminController', 'whatsappRatePlans');
$route['*']['/getWhatsappRatePlans'] = array('AdminController', 'getWhatsappRatePlans');
$route['*']['/addWhatsappRatePlan'] = array('AdminController', 'addWhatsappRatePlan');
$route['post']['/saveWhatsappRatePlan'] = array('AdminController', 'saveWhatsappRatePlan');
$route['*']['/editWhatsappRatePlan/:id'] = array('AdminController', 'editWhatsappRatePlan');
$route['*']['/deleteWhatsappRatePlan/:id'] = array('AdminController', 'deleteWhatsappRatePlan');
$route['*']['/viewWhatsappRatePlanPrices/:id'] = array('AdminController', 'viewWhatsappRatePlanPrices');
$route['post']['/saveWhatsappRatePlanPrices'] = array('AdminController', 'saveWhatsappRatePlanPrices');
$route['*']['/setWhatsappRatePlanDefault/:id'] = array('AdminController', 'setWhatsappRatePlanDefault');

$route['*']['/managePermissionGroups'] = array('AdminController', 'managePermissionGroups');
$route['*']['/getAllPermissionGroups'] = array('AdminController', 'getAllPermissionGroups');
$route['*']['/addPermissionGroup'] = array('AdminController', 'addPermissionGroup');
$route['post']['/savePermissionGroup'] = array('AdminController', 'savePermissionGroup');
$route['*']['/editPermissionGroup/:id'] = array('AdminController', 'editPermissionGroup');
$route['*']['/deletePermissionGroup/:id'] = array('AdminController', 'deletePermissionGroup');

$route['*']['/manageFdlrTemplates'] = array('AdminController', 'manageFdlrTemplates');
$route['*']['/listAllFdlrTemplates'] = array('AdminController', 'listAllFdlrTemplates');
$route['*']['/addNewFdlrTemplate'] = array('AdminController', 'addNewFdlrTemplate');
$route['*']['/editFdlrTemplate/:id'] = array('AdminController', 'editFdlrTemplate');
$route['post']['/saveFdlrTemplate'] = array('AdminController', 'saveFdlrTemplate');
$route['*']['/deleteFdlrTemplate/:id'] = array('AdminController', 'deleteFdlrTemplate');




/** phonebook DB **/

$route['*']['/phonebook'] = array('AdminController', 'managePhonebookDb');
$route['*']['/getPhonebookDb'] = array('AdminController', 'getPhonebookDb');
$route['*']['/addPhonebookDb'] = array('AdminController', 'addPhonebookDb');
$route['*']['/editPhonebookDb/:id'] = array('AdminController', 'editPhonebookDb');
$route['post']['/savePhonebookDb'] = array('AdminController', 'savePhonebookDb');
$route['post']['/setPhonebookStatus'] = array('AdminController', 'setPhonebookStatus');
$route['*']['/deletePhonebookDb/:id'] = array('AdminController', 'deletePhonebookDb');
$route['*']['/viewPhonebookContacts/:id'] = array('AdminController', 'viewPhonebookContacts');
$route['*']['/getPhonebookContacts/:id'] = array('AdminController', 'getPhonebookContacts');
$route['*']['/importPhonebookContacts/:id'] = array('AdminController', 'importPhonebookContacts');
$route['post']['/savePhonebookContacts'] = array('AdminController', 'savePhonebookContacts');
$route['*']['/editPhonebookContact/:id'] = array('AdminController', 'editPhonebookContact');
$route['*']['/deletePhonebookContact/:gid/:cid'] = array('AdminController', 'deletePhonebookContact');
$route['*']['/importSmppVdlr/:src/:tgt'] = array('AdminController', 'importSmppVdlr');

$route['post']['/saveMetaPricing'] = array('AdminController', 'saveMetaPricing');


/** Short URL **/
$route['*']['/manageShortUrls'] = array('ClientController', 'manageShortUrls');
$route['*']['/getAllShortUrls'] = array('ClientController', 'getAllShortUrls');
$route['*']['/addShortUrl'] = array('ClientController', 'addShortUrl');
$route['post']['/generateShortUrl'] = array('ClientController', 'generateShortUrl');
$route['*']['/deleteShortUrl/:id'] = array('ClientController', 'deleteShortUrl');
$route['*']['/getUseShortUrls'] = array('ClientController', 'getUseShortUrls');
$route['*']['/testcall'] = array('ClientController', 'testcall');
$route['*']['/testMail'] = array('ClientController', 'testMail');
$route['*']['/loadWabaProfile/:phoneid'] = array('ClientController', 'loadWabaProfile');
$route['post']['/updateWabaProfile'] = array('ClientController', 'updateWabaProfile');


///// ------- end of new route definitions -------- //////

// -- Legacy route definitions
$route['*']['/error/disabled'] = array('MainController', 'disabledSite');
$route['post']['/globalFileUpload'] = array('ClientController', 'globalFileUpload');
$route['post']['/deleteUploadedFile'] = array('ClientController', 'deleteUploadedFile');
$route['*']['/importBlacklistDb'] = array('ClientController', 'importBlacklistDb');
$route['*']['/miscapi/:key/:mode/:data'] = array('ClientController', 'miscApi');
$route['*']['/getSheetnColumns'] = array('ClientController', 'getSheetnColumns');
$route['*']['/miscXmlApi/:data'] = array('ClientController', 'miscXmlApi');
$route['*']['/logout'] = array('AuthController', 'logout');
// -- end of legacy route defs



$route['post']['/saveWabaBusinessProfile'] = array('ClientController', 'saveWabaBusinessProfile');
//-- THE ROUTE DEFINITIONS FOR 2-WAY MESSAGING

$route['*']['/inbox'] = array('ClientController', 'inbox');
$route['*']['/getAllIncomingSms/:vmn'] = array('ClientController', 'getAllIncomingSms');
$route['*']['/getAllIncomingSms/:vmn/:dr'] = array('ClientController', 'getAllIncomingSms');
$route['*']['/viewIncomingSms/:id'] = array('ClientController', 'viewIncomingSms');
$route['*']['/deleteIncomingSms/:id'] = array('ClientController', 'deleteIncomingSms');
$route['*']['/manageVmn'] = array('ClientController', 'viewAllVmn');
$route['*']['/getAllVmn'] = array('ClientController', 'getAllVmn');
$route['*']['/addNewVmn'] = array('ClientController', 'addNewVmn');
$route['*']['/importNewVmn'] = array('ClientController', 'importNewVmn');
$route['*']['/editVmn/:id'] = array('ClientController', 'editVmn');
$route['*']['/deleteVmn/:id'] = array('ClientController', 'deleteVmn');
$route['post']['/saveVmn'] = array('ClientController', 'saveVmn');
$route['*']['/manageKeywords'] = array('ClientController', 'manageKeywords');
$route['*']['/getAllKeywords'] = array('ClientController', 'getAllKeywords');
$route['*']['/addNewKeyword'] = array('ClientController', 'addNewKeyword');
$route['*']['/editKeyword/:id'] = array('ClientController', 'editKeyword');
$route['*']['/deleteKeyword/:id'] = array('ClientController', 'deleteKeyword');
$route['post']['/saveKeyword'] = array('ClientController', 'saveKeyword');
$route['*']['/campaigns'] = array('ClientController', 'viewCampaigns');
$route['*']['/getAllCampaigns'] = array('ClientController', 'getAllCampaigns');
$route['*']['/addNewCampaign'] = array('ClientController', 'addNewCampaign');
$route['*']['/editCampaign/:id'] = array('ClientController', 'editCampaign');
$route['*']['/deleteCampaign/:id'] = array('ClientController', 'deleteCampaign');
$route['post']['/saveCampaign'] = array('ClientController', 'saveCampaign');
$route['*']['/viewOptinList/:id'] = array('ClientController', 'viewOptinList');
$route['*']['/getOptinList/:id'] = array('ClientController', 'getOptinList');
$route['*']['/viewOptoutList/:id'] = array('ClientController', 'viewOptoutList');
$route['*']['/getOptoutList/:id'] = array('ClientController', 'getOptoutList');
$route['*']['/deleteOptinNumber/:id'] = array('ClientController', 'deleteOptinNumber');
$route['*']['/deleteOptoutNumber/:id'] = array('ClientController', 'deleteOptoutNumber');
$route['*']['/addOptoutContacts/:id'] = array('ClientController', 'addOptoutContacts');
$route['post']['/saveOptoutContacts'] = array('ClientController', 'saveOptoutContacts');
$route['*']['/getReplies/:data'] = array('ClientController', 'getReplies');
$route['post']['/saveUserVmnSettings'] = array('AdminController', 'saveUserVmnSettings');



//-- END OF ROUTE DEFS FOR 2-WAY MESSAGING




//-- Route definitions for SMPP Outbound API component --//


$route['*']['/getSmppClients/:uid'] = array('AdminController', 'getSmppClients');
$route['*']['/addSmppClient/:uid'] = array('AdminController', 'addSmppClient');
$route['*']['/editSmppClient/:uid/:id'] = array('AdminController', 'editSmppClient');
$route['*']['/deleteSmppClient/:uid/:id'] = array('AdminController', 'deleteSmppClient');
$route['post']['/saveSmppClient'] = array('AdminController', 'saveSmppClient');
$route['post']['/toggleSmppClientStatus'] = array('AdminController', 'toggleSmppClientStatus');

$route['*']['/smppServerMonitor'] = array('AdminController', 'smppServerMonitor');

$route['*']['/smppApi'] = array('ClientController', 'smppApi');
$route['*']['/getSmppApiClients'] = array('ClientController', 'getSmppApiClients');
$route['*']['/viewSmppClient/:id'] = array('ClientController', 'viewSmppClient');
$route['*']['/viewSmppSms/:id'] = array('ClientController', 'viewSmppSms');
$route['*']['/getSmppSmsList/:systemid'] = array('ClientController', 'getSmppSmsList');
$route['*']['/getSmppSmsList/:systemid/:dr'] = array('ClientController', 'getSmppSmsList');
$route['*']['/getSmppDlr/:data'] = array('ClientController', 'getSmppDlr');
$route['*']['/setAppLanguage/:lang'] = array('ClientController', 'setAppLanguage');




//-- End of Route definitions for SMPP Outbound API component --//


//-- Route definitions for MCC/MNC based billing and routing module --//
$route['*']['/gatewayCostPrice/:id'] = array('AdminController', 'gatewayCostPrice');
$route['*']['/uploadGatewayCostPricing/:id'] = array('AdminController', 'uploadGatewayCostPricing');
$route['*']['/getGatewayCostPrice/:id/:country'] = array('AdminController', 'getGatewayCostPrice');
$route['*']['/getGatewayCostPriceSorted/:id/:country/:operator/:mode'] = array('AdminController', 'getGatewayCostPriceSorted');
$route['post']['/saveGatewayCostPrice'] = array('AdminController', 'saveGatewayCostPrice');
$route['post']['/removeGatewayCostPrice'] = array('AdminController', 'removeGatewayCostPrice');
$route['post']['/importGatewayCostPrice'] = array('AdminController', 'importGatewayCostPrice');

$route['*']['/mccmncRatePlans'] = array('AdminController', 'mccmncRatePlans');
$route['*']['/getAllMccmncPlans'] = array('AdminController', 'getAllMccmncPlans');
$route['*']['/addMccmncPlan'] = array('AdminController', 'addMccmncPlan');
$route['post']['/saveMccmncPlan'] = array('AdminController', 'saveMccmncPlan');
$route['*']['/editMccmncSmsPlan/:id'] = array('AdminController', 'editMccmncSmsPlan');
$route['*']['/deleteMccmncSmsPlan/:id'] = array('AdminController', 'deleteMccmncSmsPlan');
$route['*']['/setMccmncPricing/:id'] = array('AdminController', 'setMccmncPricing');
$route['*']['/setMccmncPricing/:id/:routeid'] = array('AdminController', 'setMccmncPricing');
$route['*']['/getPlanSellingPriceSorted/:id/:country/:operator/:mode'] = array('AdminController', 'getPlanSellingPriceSorted');
$route['post']['/saveMccMncPlanPrice'] = array('AdminController', 'saveMccMncPlanPrice');
$route['post']['/removeMccMncPlanPrice'] = array('AdminController', 'removeMccMncPlanPrice');
$route['post']['/saveDefaultPlanPrice'] = array('AdminController', 'saveDefaultPlanPrice');
$route['post']['/saveUserPlanAssignment'] = array('AdminController', 'saveUserPlanAssignment');
$route['*']['/overrideRules'] = array('AdminController', 'overrideRules');
$route['*']['/getAllOverrideRules'] = array('AdminController', 'getAllOverrideRules');
$route['*']['/addOverrideRule'] = array('AdminController', 'addOverrideRule');
$route['post']['/saveOverrideRule'] = array('AdminController', 'saveOverrideRule');
$route['*']['/editOverrideRule/:id'] = array('AdminController', 'editOverrideRule');
$route['*']['/deleteOverrideRule/:id'] = array('AdminController', 'deleteOverrideRule');

$route['*']['/mnpDatabase'] = array('AdminController', 'mnpDatabase');
$route['*']['/getMnpJobs'] = array('AdminController', 'getMnpJobs');
$route['*']['/addMnpRecords'] = array('AdminController', 'addMnpRecords');
$route['post']['/saveMnpRecords'] = array('AdminController', 'saveMnpRecords');
$route['*']['/deleteMnpJob/:id'] = array('AdminController', 'deleteMnpJob');

$route['*']['/syncWaba'] = array('AdminController', 'syncWaba');

$route['post']['/calculateSmsCost'] = array('ClientController', 'calculateSmsCost');

//-- HLR Component
$route['*']['/manageHlr'] = array('AdminController', 'manageHlr');
$route['*']['/getAllHlr'] = array('AdminController', 'getAllHlr');
$route['*']['/addHlrApi'] = array('AdminController', 'addHlrApi');
$route['post']['/saveHlrApi'] = array('AdminController', 'saveHlrApi');
$route['*']['/editHlrApi/:id'] = array('AdminController', 'editHlrApi');
$route['*']['/deleteHlrApi/:id'] = array('AdminController', 'deleteHlrApi');
$route['post']['/saveUserHlrSettings'] = array('AdminController', 'saveUserHlrSettings');

$route['*']['/viewHlrReports'] = array('ClientController', 'viewHlrReports');
$route['*']['/getHlrReports'] = array('ClientController', 'getHlrReports');
$route['*']['/getHlrReports/:dr'] = array('ClientController', 'getHlrReports');
$route['*']['/newHlrLookup'] = array('ClientController', 'newHlrLookup');
$route['post']['/submitHlrLookup'] = array('ClientController', 'submitHlrLookup');
$route['*']['/hlrApiCallback/:providerid/:data'] = array('ClientController', 'hlrApiCallback');
$route['*']['/downloadHlr/:dr'] = array('ClientController', 'downloadHlr');






//---------- Delete if not needed ------------


//view the logs and profiles XML, filename = db.profile, log, trace.log, profile
$route['*']['/debug/:filename'] = array('MainController', 'debug', 'authName' => 'DooPHP Admin', 'auth' => $admin, 'authFail' => 'Unauthorized!');

//show all urls in app
$route['*']['/allurl'] = array('MainController', 'allurl', 'authName' => 'DooPHP Admin', 'auth' => $admin, 'authFail' => 'Unauthorized!');

//generate routes file. This replace the current routes.conf.php. Use with the sitemap tool.
$route['post']['/gen_sitemap'] = array('MainController', 'gen_sitemap', 'authName' => 'DooPHP Admin', 'auth' => $admin, 'authFail' => 'Unauthorized!');

//generate routes & controllers. Use with the sitemap tool.
$route['post']['/gen_sitemap_controller'] = array('MainController', 'gen_sitemap_controller', 'authName' => 'DooPHP Admin', 'auth' => $admin, 'authFail' => 'Unauthorized!');

//generate Controllers automatically
$route['*']['/gen_site'] = array('MainController', 'gen_site', 'authName' => 'DooPHP Admin', 'auth' => $admin, 'authFail' => 'Unauthorized!');

//generate Models automatically
$route['*']['/gen_model'] = array('MainController', 'gen_model', 'authName' => 'DooPHP Admin', 'auth' => $admin, 'authFail' => 'Unauthorized!');
