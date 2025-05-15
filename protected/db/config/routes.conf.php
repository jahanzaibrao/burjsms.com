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



$admin = array('admin'=>'1234'); 
$root = array('root'=>'123!@#');  


/* New Routes definitions */

// - Main Controller
$route['*']['/'] = array('MainController', 'appHome');
$route['*']['/web/:page'] = array('MainController', 'appHome');
$route['*']['/getScText'] = array('MainController', 'getScText');
$route['post']['/checkAvailability'] = array('MainController','checkAvailability');
$route['post']['/regNewAccount'] = array('MainController','regNewAccount');
$route['post']['/passwordReset'] = array('MainController','passwordReset');
$route['*']['/Dashboard'] = array('MainController','dashboard');
$route['post']['/submitGwTestSms'] = array('MainController','submitGwTestSms');
$route['post']['/getSelPlanOptionsOuter'] = array('MainController','getSelPlanOptionsOuter');
$route['post']['/getPlanSmsPriceOuter'] = array('MainController','getPlanSmsPriceOuter');
$route['*']['/scProcessPayment/:data'] = array('MainController','scProcessPayment');
$route['*']['/scPaymentReturn/:data'] = array('MainController','scPaymentReturn');
$route['post']['/saveContactLead'] = array('MainController','saveContactLead');
$route['post']['/generateDateExample'] = array('MainController','generateDateExample');
$route['*']['/createCaptcha'] = array('MainController','createCaptcha');
$route['*']['/createCaptcha/:random'] = array('MainController','createCaptcha');



// - Auth Controller
$route['post']['/auth/authService'] = array('AuthController','authUser');



// - Client Controller
$route['*']['/manageSenderId'] = array('ClientController','manageSenderId');
$route['*']['/getAllSenders'] = array('ClientController','getAllSenders');
$route['*']['/getAllSenders/:id'] = array('ClientController','getAllSenders');
$route['*']['/addSender'] = array('ClientController','addSender');
$route['*']['/editSender/:id'] = array('ClientController','editSender');
$route['*']['/deleteSender/:id'] = array('ClientController','deleteSender');
$route['post']['/saveSender'] = array('ClientController','saveSender');
$route['*']['/manageTemplates'] = array('ClientController','manageTemplates');
$route['*']['/getAllTemplates'] = array('ClientController','getAllTemplates');
$route['*']['/getAllTemplates/:id'] = array('ClientController','getAllTemplates');
$route['*']['/getUseTemplates'] = array('ClientController','getUseTemplates');
$route['*']['/addTemplate'] = array('ClientController','addTemplate');
$route['*']['/editTemplate/:id'] = array('ClientController','editTemplate');
$route['*']['/deleteTemplate/:id'] = array('ClientController','deleteTemplate');
$route['post']['/saveTemplate'] = array('ClientController','saveTemplate');
$route['*']['/manageGroups'] = array('ClientController','manageGroups');
$route['*']['/getAllGroups'] = array('ClientController','getAllGroups');
$route['*']['/addGroup'] = array('ClientController','addGroup');
$route['*']['/editGroup/:id'] = array('ClientController','editGroup');
$route['*']['/moveContacts/:id'] = array('ClientController','moveContacts');
$route['post']['/saveMoveContacts'] = array('ClientController','saveMoveContacts');
$route['*']['/deleteGroup/:id'] = array('ClientController','deleteGroup');
$route['post']['/saveGroup'] = array('ClientController','saveGroup');
$route['*']['/manageContacts'] = array('ClientController','manageContacts');
$route['*']['/getAllContacts'] = array('ClientController','getAllContacts');
$route['*']['/addContact'] = array('ClientController','addContact');
$route['*']['/importContacts'] = array('ClientController','importContacts');
$route['*']['/editContact/:id'] = array('ClientController','editContact');
$route['*']['/deleteContact/:id'] = array('ClientController','deleteContact');
$route['*']['/delManyContacts'] = array('ClientController','delManyContacts');
$route['post']['/saveContacts'] = array('ClientController','saveContacts');
$route['*']['/composeSMS'] = array('ClientController','composeSMS');
$route['*']['/getCreditCountRuleDetails/:id'] = array('ClientController','getCreditCountRuleDetails');
$route['post']['/processCampaign'] = array('ClientController','processCampaign');
$route['*']['/transactionReports'] = array('ClientController','viewTransactionReports');
$route['*']['/getMyTransactions'] = array('ClientController','getMyTransactions');
$route['*']['/getMyTransactions/:dr'] = array('ClientController','getMyTransactions');
$route['*']['/getMyTransactions/:dr/:uid'] = array('ClientController','getMyTransactions');
$route['*']['/showDlrSummary'] = array('ClientController','showDlrSummary');
$route['*']['/getMySmsCampaigns'] = array('ClientController','getMySmsCampaigns');
$route['*']['/getMySmsCampaigns/:dr'] = array('ClientController','getMySmsCampaigns');
$route['*']['/getMySmsCampaigns/:dr/:uid'] = array('ClientController','getMySmsCampaigns');
$route['*']['/showDLR/:id'] = array('ClientController','showDLR');
$route['*']['/showDLR/:id/:uid'] = array('ClientController','showDLR');
$route['*']['/getDlrSummary/:id'] = array('ClientController','getDlrSummary');
$route['*']['/getMySentSms/:id'] = array('ClientController','getMySentSms');
$route['*']['/getMySentSms/:id/:uid'] = array('ClientController','getMySentSms');
$route['*']['/resendCampaign/:mode/:id'] = array('ClientController','resendCampaign');
$route['*']['/getUserDashStats'] = array('ClientController','getUserDashStats');
$route['*']['/getUserSmsActivity/:dr'] = array('ClientController','getUserSmsActivity');
$route['*']['/getRecentTransactions'] = array('ClientController','getRecentTransactions');
$route['*']['/getRecentCampaigns'] = array('ClientController','getRecentCampaigns');
$route['*']['/reloadCreditData'] = array('ClientController','reloadCreditData');
$route['*']['/manageDocs'] = array('ClientController','manageDocs');
$route['*']['/getUserDocs/:type/:dr'] = array('ClientController','getUserDocs');
$route['*']['/getUserDocs/:type/:dr/:limit'] = array('ClientController','getUserDocs');
$route['*']['/addNewDocument'] = array('ClientController','addNewDocument');
$route['post']['/saveDocument'] = array('ClientController','saveDocument');
$route['*']['/viewDocument/:id'] = array('ClientController','viewDocument');
$route['post']['/postSharedUsers'] = array('ClientController','postSharedUsers');
$route['post']['/rmvSharedUsr'] = array('ClientController','rmvSharedUsr');
$route['post']['/postFileComment'] = array('ClientController','postFileComment');
$route['*']['/globalFileDownload/:mode/:id'] = array('ClientController','globalFileDownload');
$route['post']['/documentReupload'] = array('ClientController','documentReupload');
$route['*']['/deleteDocument/:id'] = array('ClientController','deleteDocument');
$route['*']['/supportTickets'] = array('ClientController','supportTickets');
$route['*']['/getMyTickets'] = array('ClientController','getMyTickets');
$route['*']['/getMyTickets/:dr'] = array('ClientController','getMyTickets');
$route['*']['/addNewTicket'] = array('ClientController','addNewTicket');
$route['post']['/saveSupportTicket'] = array('ClientController','saveSupportTicket');
$route['*']['/viewTicket/:id'] = array('ClientController','viewTicket');
$route['post']['/postTicketComment'] = array('ClientController','postTicketComment');
$route['*']['/refundLog'] = array('ClientController','refundLog');
$route['*']['/getRefundLog'] = array('ClientController','getRefundLog');
$route['*']['/getRefundLog/:dr'] = array('ClientController','getRefundLog');
$route['*']['/creditLog'] = array('ClientController','creditLog');
$route['*']['/getCreditLog'] = array('ClientController','getCreditLog');
$route['*']['/getCreditLog/:dr'] = array('ClientController','getCreditLog');
$route['*']['/getCreditLog/:dr/:uid'] = array('ClientController','getCreditLog');
$route['*']['/userSmsLog'] = array('ClientController','userSmsLog');
$route['*']['/getUserSmsLog'] = array('ClientController','getUserSmsLog');
$route['*']['/getUserSmsLog/:dr'] = array('ClientController','getUserSmsLog');
$route['*']['/getUserSmsLog/:dr/:sid'] = array('ClientController','getUserSmsLog');
$route['*']['/getUserSmsLog/:dr/:sid/:rid'] = array('ClientController','getUserSmsLog');
$route['*']['/api'] = array('ClientController','viewDevApi');
$route['*']['/xmlApi'] = array('ClientController','viewXmlApi');
$route['*']['/regAPIKey'] = array('ClientController','regAPIKey');
$route['*']['/smsStats'] = array('ClientController','smsStats');
$route['*']['/getSmsStatsReport/:dr'] = array('ClientController','getSmsStatsReport');
$route['*']['/smsArchive'] = array('ClientController','smsArchive');
$route['*']['/getArchivedFiles'] = array('ClientController','getArchivedFiles');
$route['post']['/saveArchiveFetchTask'] = array('ClientController','saveArchiveFetchTask');
$route['*']['/scheduledCampaigns'] = array('ClientController','scheduledCampaigns');
$route['*']['/getMyScheduledCampaigns'] = array('ClientController','getMyScheduledCampaigns');
$route['*']['/getMyScheduledCampaigns/:dr'] = array('ClientController','getMyScheduledCampaigns');
$route['*']['/editScheduledCampaign/:id'] = array('ClientController','editScheduledCampaign');
$route['post']['/saveEditScheduledCampaign'] = array('ClientController','saveEditScheduledCampaign');
$route['*']['/cancelSchedule/:id'] = array('ClientController','cancelSchedule');
$route['*']['/editUserProfile'] = array('ClientController','editUserProfile');
$route['post']['/saveUserProfile'] = array('ClientController','saveUserProfile');
$route['*']['/verifyViaOTP/:mode'] = array('ClientController','verifyViaOTP');
$route['*']['/confirmOTP/:mode'] = array('ClientController','confirmOTP');
$route['*']['/saveUserPassword'] = array('ClientController','saveUserPassword');
$route['*']['/saveCompanyInfo'] = array('ClientController','saveCompanyInfo');
$route['*']['/saveUserSettings'] = array('ClientController','saveUserSettings');
$route['*']['/userSettings'] = array('ClientController','userSettings');
$route['*']['/viewNotifications'] = array('ClientController','viewNotifications');
$route['*']['/getMyAlerts'] = array('ClientController','getMyAlerts');
$route['*']['/getAllMyAlerts'] = array('ClientController','getAllMyAlerts');
$route['*']['/getAllMyAlerts/:dr'] = array('ClientController','getAllMyAlerts');
$route['*']['/alertRedirect/:nid/:elink'] = array('ClientController','alertRedirect');
$route['*']['/markAlertsRead'] = array('ClientController','markAlertsRead');
$route['*']['/smsapi/:data'] = array('ClientController','smsViaApi');
$route['*']['/purchaseCredits'] = array('ClientController','purchaseCredits');
$route['*']['/buyOrderCheckout'] = array('ClientController','buyOrderCheckout');
$route['*']['/confirmPurchaseOrder/:data'] = array('ClientController','confirmPurchaseOrder');
$route['*']['/scOrderProcess/:data'] = array('ClientController','scOrderProcess');



$route['*']['/watchmanProcessMonitor'] = array('ClientController','watchmanProcessMonitor');
$route['*']['/queueProcess'] = array('ClientController','queueProcess');
$route['*']['/scheduleProcess'] = array('ClientController','scheduleProcess');
$route['*']['/tempStoreProcess'] = array('ClientController','tempStoreProcess');
$route['*']['/ndncTasksProcess'] = array('ClientController','ndncTasksProcess');
$route['*']['/dbArchiveProcess'] = array('ClientController','dbArchiveProcess');
$route['*']['/archiveFetchProcess'] = array('ClientController','archiveFetchProcess');
$route['*']['/dailyMailerProcess'] = array('ClientController','dailyMailerProcess');



// - Reseller Controller
$route['*']['/manageUsers'] = array('ResellerController','manageUsers');
$route['*']['/getAllUsers'] = array('ResellerController','getAllUsers');
$route['*']['/addNewUser'] = array('ResellerController','addNewUser');
$route['post']['/createUserAccount'] = array('ResellerController','createUserAccount');
$route['post']['/getPlanSmsPrice'] = array('ResellerController','getPlanSmsPrice');
$route['*']['/genWebSettings'] = array('ResellerController','genWebSettings');
$route['post']['/saveWebSettings'] = array('ResellerController','saveWebSettings');
$route['*']['/signupWebSettings'] = array('ResellerController','signupWebSettings');
$route['post']['/saveSignupSettings'] = array('ResellerController','saveSignupSettings');
$route['*']['/themeWebSettings'] = array('ResellerController','themeWebSettings');
$route['post']['/updateThemeSettings'] = array('ResellerController','updateThemeSettings');
$route['*']['/homeWebSettings'] = array('ResellerController','homeWebSettings');
$route['*']['/aboutWebSettings'] = array('ResellerController','aboutWebSettings');
$route['*']['/pricingWebSettings'] = array('ResellerController','pricingWebSettings');
$route['*']['/contactWebSettings'] = array('ResellerController','contactWebSettings');
$route['*']['/loginWebSettings'] = array('ResellerController','loginWebSettings');
$route['post']['/saveWebPageSettings'] = array('ResellerController','saveWebPageSettings');
$route['*']['/getResellerStats'] = array('ResellerController','getResellerStats');
$route['*']['/getResellerSales/:dr'] = array('ResellerController','getResellerSales');
$route['*']['/getTopConsumers'] = array('ResellerController','getTopConsumers');
$route['*']['/getTopConsumers/:dr'] = array('ResellerController','getTopConsumers');
$route['*']['/getTopConsumers/:dr/:limit'] = array('ResellerController','getTopConsumers');
$route['*']['/getLatestOrders'] = array('ResellerController','getLatestOrders');
$route['*']['/getLatestOrders/:dr'] = array('ResellerController','getLatestOrders');
$route['*']['/getLatestOrders/:dr/:limit'] = array('ResellerController','getLatestOrders');
$route['*']['/viewUserAccount/:id'] = array('ResellerController','viewUserAccount');
$route['*']['/getClientSmsActivity'] = array('ResellerController','getClientSmsActivity');
$route['*']['/getClientSmsActivity/:dr'] = array('ResellerController','getClientSmsActivity');
$route['*']['/viewUserRouteSettings/:id'] = array('ResellerController','viewUserRouteSettings');
$route['*']['/viewUserSenderIds/:id'] = array('ResellerController','viewUserSenderIds');
$route['*']['/viewUserTemplates/:id'] = array('ResellerController','viewUserTemplates');
$route['*']['/viewUserDlrSummary/:id'] = array('ResellerController','viewUserDlrSummary');
$route['*']['/makeAccountTransaction/:id'] = array('ResellerController','makeAccountTransaction');
$route['post']['/processAccountTransaction'] = array('ResellerController','processAccountTransaction');
$route['*']['/viewUserTransactions/:id'] = array('ResellerController','viewUserTransactions');
$route['*']['/viewUserCreditLog/:id'] = array('ResellerController','viewUserCreditLog');
$route['*']['/viewUserAccountSettings/:id'] = array('ResellerController','viewUserAccountSettings');
$route['*']['/showUserDLR/:id/:sid'] = array('ResellerController','showUserDLR');
$route['post']['/markInvoiceStatus'] = array('ResellerController','markInvoiceStatus');
$route['*']['/manageSupport'] = array('ResellerController','manageSupport');
$route['*']['/getAssignedTickets'] = array('ResellerController','getAssignedTickets');
$route['*']['/getAssignedTickets/:dr'] = array('ResellerController','getAssignedTickets');
$route['*']['/viewMgrTicket/:id'] = array('ResellerController','viewMgrTicket');
$route['*']['/markTicket/:status/:id'] = array('ResellerController','markTicket');
$route['post']['/saveRouteAssignments'] = array('ResellerController','saveRouteAssignments');
$route['*']['/accountActions/:action/:uid'] = array('ResellerController','accountActions');
$route['post']['/websiteToggle'] = array('ResellerController','websiteToggle');
$route['*']['/manageInactiveUsers'] = array('ResellerController','manageInactiveUsers');
$route['*']['/getInactiveUserAccounts'] = array('ResellerController','getInactiveUserAccounts');
$route['*']['/activateUserAccount/:uid'] = array('ResellerController','activateUserAccount');





// - Admin Controller
$route['*']['/getAdminDashStats'] = array('AdminController','getAdminDashStats');
$route['*']['/getSalesSmsChart/:dr'] = array('AdminController','getSalesSmsChart');
$route['*']['/getSmsActivity/:dr'] = array('AdminController','getSmsActivity');
$route['*']['/getSystemStats'] = array('AdminController','getSystemStats');
$route['*']['/getTopResellers'] = array('AdminController','getTopResellers');
$route['*']['/getTopResellers/:dr'] = array('AdminController','getTopResellers');
$route['*']['/getTopResellers/:dr/:limit'] = array('AdminController','getTopResellers');
$route['*']['/getRouteTraffic'] = array('AdminController','getRouteTraffic');
$route['*']['/getRouteTraffic/:dr'] = array('AdminController','getRouteTraffic');
$route['*']['/manageSmpp'] = array('AdminController','manageSmpp');
$route['*']['/getAllSmpp'] = array('AdminController','getAllSmpp');
$route['*']['/addSmpp'] = array('AdminController','addSmpp');
$route['post']['/saveSmpp'] = array('AdminController','saveSmpp');
$route['*']['/editSmpp/:id'] = array('AdminController','editSmpp');
$route['*']['/deleteSmpp/:id'] = array('AdminController','deleteSmpp');
$route['*']['/manageRoutes'] = array('AdminController','manageRoutes');
$route['*']['/getAllRoutes'] = array('AdminController','getAllRoutes');
$route['*']['/addRoute'] = array('AdminController','addRoute');
$route['post']['/saveRoute'] = array('AdminController','saveRoute');
$route['*']['/editRoute/:id'] = array('AdminController','editRoute');
$route['post']['/changeRouteStatus'] = array('AdminController','changeRouteStatus');
$route['*']['/routeDlrCodes/:id'] = array('AdminController','routeDlrCodes');
$route['post']['/saveRouteDlrCodes'] = array('AdminController','saveRouteDlrCodes');
$route['*']['/deleteRoute/:id'] = array('AdminController','deleteRoute');
$route['*']['/manageBlacklists'] = array('AdminController','manageBlacklists');
$route['*']['/getAllBlDb'] = array('AdminController','getAllBlDb');
$route['*']['/addBlacklistDb'] = array('AdminController','addBlacklistDb');
$route['*']['/uploadBlacklistData'] = array('AdminController','uploadBlacklistData');
$route['post']['/saveBlacklistDb'] = array('AdminController','saveBlacklistDb');
$route['post']['/saveBlacklistData'] = array('AdminController','saveBlacklistData');
$route['*']['/editBlacklistDb/:id'] = array('AdminController','editBlacklistDb');
$route['*']['/deleteBlacklistDb/:id'] = array('AdminController','deleteBlacklistDb');
$route['post']['/addUploadTask'] = array('AdminController','addUploadTask');
$route['*']['/viewBlDb/:id'] = array('AdminController','viewBlDb');
$route['*']['/getImportTasks/:id'] = array('AdminController','getImportTasks');
$route['*']['/deleteImportTask/:id'] = array('AdminController','deleteImportTask');
$route['*']['/numberLookupBlDb'] = array('AdminController','numberLookupBlDb');
$route['*']['/deleteNdncNumber/:id'] = array('AdminController','deleteNdncNumber');
$route['post']['/bldbActions'] = array('AdminController','bldbActions');
$route['*']['/manageCountRules'] = array('AdminController','manageCountRules');
$route['*']['/getAllCountRules'] = array('AdminController','getAllCountRules');
$route['*']['/addCountRule'] = array('AdminController','addCountRule');
$route['post']['/saveCountRule'] = array('AdminController','saveCountRule');
$route['*']['/editCountRule/:id'] = array('AdminController','editCountRule');
$route['*']['/delCountRule/:id'] = array('AdminController','delCountRule');
$route['*']['/manageSmsPlans'] = array('AdminController','manageSmsPlans');
$route['*']['/getAllSmsPlans'] = array('AdminController','getAllSmsPlans');
$route['*']['/addSmsPlan'] = array('AdminController','addSmsPlan');
$route['post']['/saveSmsPlan'] = array('AdminController','saveSmsPlan');
$route['*']['/editSmsPlan/:id'] = array('AdminController','editSmsPlan');
$route['*']['/delSmsPlan/:id'] = array('AdminController','delSmsPlan');
$route['post']['/getSelPlanOptions'] = array('AdminController','getSelPlanOptions');
$route['*']['/manageCountries'] = array('AdminController','manageCountries');
$route['*']['/getAllCountries'] = array('AdminController','getAllCountries');
$route['*']['/editCountry/:id'] = array('AdminController','editCountry');
$route['post']['/saveCountry'] = array('AdminController','saveCountry');
$route['*']['/uploadPrefixes'] = array('AdminController','uploadPrefixes');
$route['post']['/importPrefixes'] = array('AdminController','importPrefixes');
$route['*']['/viewAllOP/:id'] = array('AdminController','viewAllOP');
$route['*']['/getAllOP/:id'] = array('AdminController','getAllOP');
$route['*']['/editOP/:id'] = array('AdminController','editOP');
$route['post']['/saveOP'] = array('AdminController','saveOP');
$route['post']['/delManyOP'] = array('AdminController','delManyOP');
$route['*']['/deleteOP/:id/:cid'] = array('AdminController','deleteOP');
$route['*']['/approveSenderIds'] = array('AdminController','approveSenderIds');
$route['*']['/getAllPendingSenderIds'] = array('AdminController','getAllPendingSenderIds');
$route['*']['/approveSid/:id'] = array('AdminController','approveSid');
$route['post']['/approveManySids'] = array('AdminController','approveManySids');
$route['*']['/rejectSid/:id'] = array('AdminController','rejectSid');
$route['post']['/rejectManySids'] = array('AdminController','rejectManySids');
$route['*']['/approveTemplates'] = array('AdminController','approveTemplates');
$route['*']['/getAllPendingTemps'] = array('AdminController','getAllPendingTemps');
$route['*']['/approveTemp/:id'] = array('AdminController','approveTemp');
$route['post']['/approveManyTemps'] = array('AdminController','approveManyTemps');
$route['*']['/rejectTemp/:id'] = array('AdminController','rejectTemp');
$route['post']['/rejectManyTemps'] = array('AdminController','rejectManyTemps');
$route['*']['/refundRules'] = array('AdminController','refundRules');
$route['*']['/getRefundRules'] = array('AdminController','getRefundRules');
$route['*']['/addRefundRule'] = array('AdminController','addRefundRule');
$route['post']['/saveRefundRule'] = array('AdminController','saveRefundRule');
$route['*']['/editRefundRule/:id'] = array('AdminController','editRefundRule');
$route['*']['/delRefundRule/:id'] = array('AdminController','delRefundRule');
$route['*']['/kannelMonitor'] = array('AdminController','kannelMonitor');
$route['post']['/kannelActions'] = array('AdminController','kannelActions');
$route['*']['/announcements'] = array('AdminController','announcements');
$route['*']['/getAnnouncements'] = array('AdminController','getAnnouncements');
$route['post']['/setAnnouncementState'] = array('AdminController','setAnnouncementState');
$route['*']['/addAnnouncement'] = array('AdminController','addAnnouncement');
$route['post']['/saveAnnouncement'] = array('AdminController','saveAnnouncement');
$route['*']['/editAnnouncement/:id'] = array('AdminController','editAnnouncement');
$route['*']['/deleteAnnouncement/:id'] = array('AdminController','deleteAnnouncement');
$route['*']['/manageStaffTeams'] = array('AdminController','manageStaffTeams');
$route['*']['/getAllStaffTeams'] = array('AdminController','getAllStaffTeams');
$route['*']['/addStaffTeam'] = array('AdminController','addStaffTeam');
$route['post']['/saveStaffTeam'] = array('AdminController','saveStaffTeam');
$route['*']['/editStaffTeam/:id'] = array('AdminController','editStaffTeam');
$route['*']['/delStaffTeam/:id'] = array('AdminController','delStaffTeam');
$route['*']['/manageStaff'] = array('AdminController','manageStaff');
$route['*']['/getAllStaff'] = array('AdminController','getAllStaff');
$route['*']['/addStaff'] = array('AdminController','addStaff');
$route['post']['/saveStaff'] = array('AdminController','saveStaff');
$route['*']['/viewStaff/:id'] = array('AdminController','viewStaff');
$route['*']['/delStaff/:id'] = array('AdminController','delStaff');
$route['post']['/changeTeam'] = array('AdminController','changeTeam');
$route['post']['/saveStaffRights'] = array('AdminController','saveStaffRights');
$route['post']['/saveUserPermissions'] = array('AdminController','saveUserPermissions');
$route['post']['/saveUserSpecialFlags'] = array('AdminController','saveUserSpecialFlags');
$route['post']['/saveUserWhitelist'] = array('AdminController','saveUserWhitelist');
$route['*']['/manageSpam'] = array('AdminController','manageSpamCampaigns');
$route['*']['/getAllSpamCampaigns'] = array('AdminController','getAllSpamCampaigns');
$route['*']['/rejectSpam/:id'] = array('AdminController','rejectSpam');
$route['*']['/approveSpam/:id'] = array('AdminController','approveSpam');
$route['*']['/manageSpamKeywords'] = array('AdminController','manageSpamKeywords');
$route['*']['/getAllSpamKeywords'] = array('AdminController','getAllSpamKeywords');
$route['*']['/addSpamKeyword'] = array('AdminController','addSpamKeyword');
$route['post']['/saveSpamKeyword'] = array('AdminController','saveSpamKeyword');
$route['*']['/deleteSpamKeyword/:id'] = array('AdminController','deleteSpamKeyword');
$route['*']['/systemMonitor'] = array('AdminController','systemMonitor');
$route['*']['/getAllQueuedCampaigns'] = array('AdminController','getAllQueuedCampaigns');
$route['*']['/getAllScheduledCampaigns'] = array('AdminController','getAllScheduledCampaigns');
$route['post']['/changeCampaignStatus'] = array('AdminController','changeCampaignStatus');
$route['*']['/getAllTempCampaigns'] = array('AdminController','getAllTempCampaigns');
$route['*']['/getAllUserStatus'] = array('AdminController','getAllUserStatus');
$route['*']['/getRouteTrafficInfo'] = array('AdminController','getRouteTrafficInfo');
$route['post']['/dropQueuedBatches'] = array('AdminController','dropQueuedBatches');
$route['post']['/shiftQueuedBatches'] = array('AdminController','shiftQueuedBatches');
$route['post']['/cancelTempCampaign'] = array('AdminController','cancelTempCampaign');
$route['post']['/sendScheduleManually'] = array('AdminController','sendScheduleManually');
$route['*']['/appSettings'] = array('AdminController', 'appSettings', 'authName'=>'Smppcube Admin', 'auth'=>$root, 'authFail'=>'Unauthorized!');
$route['post']['/saveAppSettings'] = array('AdminController','saveAppSettings');
$route['*']['/powerGrid'] = array('AdminController','powerGrid');
$route['post']['/saveMiscVars'] = array('AdminController','saveMiscVars');
$route['post']['/addArchiveTask'] = array('AdminController','addArchiveTask');
$route['post']['/cancelArchiveTask'] = array('AdminController','cancelArchiveTask');
$route['*']['/watchmanLog'] = array('AdminController','watchmanLog');
$route['*']['/getWatchmanLog'] = array('AdminController','getWatchmanLog');
$route['*']['/getWatchmanLog/:dr'] = array('AdminController','getWatchmanLog');
$route['*']['/dbArchiveLog'] = array('AdminController','dbArchiveLog');
$route['*']['/getDbArchiveLog'] = array('AdminController','getDbArchiveLog');
$route['*']['/getDbArchiveLog/:dr'] = array('AdminController','getDbArchiveLog');
$route['*']['/setProcessStatus'] = array('AdminController','setProcessStatus');











 






























///// ------- end of new route definitions -------- //////










$route['*']['/testProcess'] = array('ClientController', 'testProcess');
$route['post']['/sendTestSMS'] = array('ClientController','sendTestSMS');
$route['*']['/pricing'] = array('MainController', 'viewPricing');
$route['*']['/contact'] = array('MainController', 'viewContact');
$route['*']['/sign-in'] = array('MainController', 'viewLogin');
$route['*']['/sign-up'] = array('MainController', 'viewReg');
$route['*']['/expired'] = array('MainController', 'expired');
$route['*']['/mailtest'] = array('MainController', 'mailtest');
$route['*']['/getMailTemplate/:mode'] = array('MainController', 'getMailTemplate');

$route['*']['/error'] = array('ErrorController', 'index');
$route['*']['/denied'] = array('ErrorController', 'denied');
$route['*']['/getPageContent/:page'] = array('MainController','ajaxPageLoad');
$route['*']['/checkLoginID'] = array('MainController','checkLoginID');
$route['*']['/checkSystemID'] = array('MainController','checkSystemID');
$route['*']['/checkEmailID'] = array('MainController','checkEmailID');
$route['post']['/saveNewUser'] = array('MainController','saveNewUser');
$route['post']['/resetPass'] = array('MainController','resetPass');
$route['*']['/getkey/:uid/:pass'] = array('MainController','getKey');

//$route['post']['/auth/authService'] = array('AuthController','login');
$route['*']['/error/login'] = array('AuthController','loginError');
$route['*']['/error/disabled'] = array('MainController','disabledSite');


$route['*']['/getSMSGraph'] = array('MainController','getSMSGraph');
$route['*']['/getSMSGraph/:uid'] = array('MainController','getSMSGraph');
$route['*']['/getSMSPie'] = array('MainController','getSMSPie');
$route['*']['/getSalesLine'] = array('MainController','getSalesLine');
$route['*']['/getHlrResponse'] = array('MainController','getHlrResponse');




$route['post']['/globalFileUpload'] = array('ClientController','globalFileUpload');
$route['post']['/deleteUploadedFile'] = array('ClientController','deleteUploadedFile');
$route['*']['/importBlacklistDb'] = array('ClientController','importBlacklistDb');

$route['*']['/miscapi/:key/:mode/:data'] = array('ClientController','miscApi');
$route['*']['/getSheetnColumns'] = array('ClientController','getSheetnColumns');
$route['*']['/miscXmlApi/:data'] = array('ClientController','miscXmlApi');
$route['*']['/ndncClientApp'] = array('ClientController','ndncClientApp');
$route['*']['/getMyNdnc'] = array('ClientController','getMyNdnc');
$route['*']['/addappndnc'] = array('ClientController','addappndnc');
$route['post']['/saveNdncApp'] = array('ClientController','saveNdncApp');
$route['*']['/editNdncApp/:aid'] = array('ClientController','editNdncApp');
$route['*']['/deleteNdncApp/:aid'] = array('ClientController','deleteNdncApp');
$route['post']['/addNewContact'] = array('ClientController','addNewContact');
$route['*']['/uploadContacts'] = array('ClientController','uploadContacts');
$route['*']['/editProfile'] = array('ClientController','viewEditProfile');

$route['*']['/changePassword'] = array('ClientController','viewChangePassword');
$route['*']['/transactionReports/:pindex'] = array('ClientController','viewTransactionReports');
$route['*']['/logout'] = array('AuthController','logout');

$route['*']['/uploadFile'] = array('ClientController','uploadFile');
$route['post']['/sendSMS'] = array('ClientController','processSMS');
$route['*']['/lowCredits'] = array('ClientController','viewLowBalance');
$route['*']['/templateSelector'] = array('ClientController','viewSelectTemplate');
$route['*']['/uploadInstructions'] = array('ClientController','viewUploadHelp');
$route['post']['/saveProfile'] = array('ClientController','saveProfile');
$route['*']['/changePass'] = array('ClientController','changePassword');
$route['*']['/exportDLR/:shoot_id'] = array('ClientController','exportDLR');
$route['post']['/setDlrSearch'] = array('ClientController','setDlrSearch');
$route['post']['/setTxnSrch'] = array('ClientController','setTxnSearch');
$route['*']['/getTodaySent'] = array('ClientController','getTodaySent');
$route['*']['/getTodayDel'] = array('ClientController','getTodayDel');
$route['*']['/getWeekSent'] = array('ClientController','getWeekSent');
$route['*']['/getWeekDel'] = array('ClientController','getWeekDel');
$route['*']['/getMonthSent'] = array('ClientController','getMonthSent');
$route['*']['/getMonthSent/:uid'] = array('ClientController','getMonthSent');
$route['*']['/getMonthDel'] = array('ClientController','getMonthDel');
$route['*']['/getActiveDays'] = array('ClientController','getActiveDays');
$route['*']['/getMonthlySMSCountByType/:type'] = array('ClientController','getMonthlySMSCountByType');
$route['*']['/getDLR/:data'] = array('ClientController','updateDLR');
$route['*']['/fetchDLR/:data'] = array('ClientController','fetchSmppDlr');
$route['*']['/iframeClose'] = array('ClientController','iframeClose');
$route['*']['/addSenderId'] = array('ClientController','addSenderId');
$route['*']['/getMyGroups'] = array('ClientController','getMyGroups');
$route['*']['/campaigns'] = array('ClientController','viewCampaigns');
$route['*']['/getMyCampaigns'] = array('ClientController','getMyCampaigns');
$route['*']['/addCampaign'] = array('ClientController','addCampaign');
$route['*']['/saveCampaign'] = array('ClientController','saveCampaign');
$route['*']['/editCampaign/:cid'] = array('ClientController','editCampaign');
$route['*']['/delCampaign/:cid'] = array('ClientController','delCampaign');
$route['*']['/getSMSSummaryPie'] = array('ClientController','getSMSSummaryPie');
$route['*']['/getSMSSummaryPie/:uid'] = array('ClientController','getSMSSummaryPie');
$route['*']['/getSMSSummaryChart'] = array('ClientController','getSMSSummaryChart');
$route['*']['/getSMSSummaryChart/:uid'] = array('ClientController','getSMSSummaryChart');
$route['*']['/getSMSDeliveryChart'] = array('ClientController','getSMSDeliveryChart');
$route['*']['/getSMSDeliveryChart/:uid'] = array('ClientController','getSMSDeliveryChart');


//$route['*']['/smsapi/:key/:type/:contacts/:senderid/:msg/:time'] = array('ClientController','smsViaApi');
//$route['*']['/smsapi/:key/:type/:contacts/:senderid/:msg'] = array('ClientController','smsViaApi');
$route['*']['/runSchedular'] = array('ClientController','schedular');
$route['*']['/sendSpam/:shootid'] = array('ClientController','sendSpam');
$route['*']['/getSMSAck/:shootid'] = array('ClientController','getSMSAck');
$route['*']['/cancelShoot/:shootid'] = array('ClientController','cancelShoot');
$route['*']['/addTemplate'] = array('ClientController','addTemplate');

$route['*']['/viewPriceList'] = array('ClientController','viewPriceList');
$route['*']['/getAvailableCredits/:rid'] = array('ClientController','getAvailableCredits');
$route['*']['/getRoutesByCoverage/:cid'] = array('ClientController','getRoutesByCoverage');
$route['*']['/getRoutesChartByCoverage/:cid'] = array('ClientController','getRoutesChartByCoverage');
$route['*']['/getMySentJobs'] = array('ClientController','getMySentJobs');
$route['*']['/getMySentJobs/:dr'] = array('ClientController','getMySentJobs');
$route['*']['/getMySentJobs/:dr/:cid'] = array('ClientController','getMySentJobs');
$route['*']['/getMySentJobs/:dr/:cid/:uid'] = array('ClientController','getMySentJobs');
$route['*']['/getSMSJobCount/:cid'] = array('ClientController','getSMSJobCount');

$route['*']['/getLatestCampaigns'] = array('ClientController','getLatestCampaigns');
$route['*']['/getLatestCampaigns/:uid'] = array('ClientController','getLatestCampaigns');
$route['*']['/getLatestTxn'] = array('ClientController','getLatestTxn');
$route['*']['/viewOptins/:cid'] = array('ClientController','viewOptins');
$route['*']['/getMyCmpOptins/:cid'] = array('ClientController','getMyCmpOptins');
$route['*']['/deleteOptinNo/:cid/:cpid'] = array('ClientController','deleteOptinNo');
$route['*']['/viewOptouts/:cid'] = array('ClientController','viewOptouts');
$route['*']['/getMyCmpOptouts/:cid'] = array('ClientController','getMyCmpOptouts');
$route['*']['/deleteOptoutNo/:cid/:cpid'] = array('ClientController','deleteOptoutNo');
$route['*']['/manageKeywords'] = array('ClientController','manageKeywords');
$route['*']['/getMykeywords'] = array('ClientController','getMykeywords');
$route['*']['/addReplyKeyword'] = array('ClientController','addReplyKeyword');
$route['post']['/saveReplyKeyword'] = array('ClientController','saveReplyKeyword');
$route['*']['/inbox'] = array('ClientController','inbox');
$route['*']['/getMyInbox'] = array('ClientController','getMyInbox');
$route['*']['/getMonthExpense'] = array('ClientController','getMonthExpense');
$route['*']['/getMonthExpense/:uid'] = array('ClientController','getMonthExpense');
$route['*']['/getMonthRefunds'] = array('ClientController','getMonthRefunds');
$route['*']['/getMonthRefunds/:uid'] = array('ClientController','getMonthRefunds');
$route['*']['/getCreditLog/:dr/:uid'] = array('ClientController','getCreditLog');
$route['*']['/smppAccounts'] = array('ClientController','smppAccounts');
$route['*']['/getClientSmpp'] = array('ClientController','getClientSmpp');
$route['*']['/getMySids'] = array('ClientController','getMySids');
$route['*']['/getMyTemps'] = array('ClientController','getMyTemps');
$route['*']['/getReplies/:data'] = array('ClientController','getReplies');
$route['*']['/twoWayApi'] = array('ClientController','twoWayApi');
$route['*']['/missedCallApi'] = array('ClientController','missedCallApi');
$route['*']['/hlrApi'] = array('ClientController','hlrApi');
$route['*']['/getHlrLookups'] = array('ClientController','getHlrLookups');
$route['*']['/getHlrLookups/:dr'] = array('ClientController','getHlrLookups');
$route['*']['/getHlrLookups/:dr/:uid'] = array('ClientController','getHlrLookups');
$route['*']['/editReplyKeyword/:kid'] = array('ClientController','editReplyKeyword');
$route['*']['/deleteReplyKeyword/:kid'] = array('ClientController','deleteReplyKeyword');
$route['*']['/deleteReplySMS/:rid'] = array('ClientController','deleteReplySMS');
$route['*']['/viewIncomingSMS/:rid'] = array('ClientController','viewIncomingSMS');
$route['*']['/newHlrLookup'] = array('ClientController','newHlrLookup');
$route['post']['/submitHlrRequest'] = array('ClientController','submitHlrRequest');
$route['*']['/updateActivity'] = array('ClientController','updateActivity');
$route['*']['/genDynPreview'] = array('ClientController','genDynPreview');
$route['*']['/scheduledSms'] = array('ClientController','scheduledSms');
$route['*']['/getMyScheduled'] = array('ClientController','getMyScheduled');
$route['*']['/getMyScheduled/:dr'] = array('ClientController','getMyScheduled');
$route['*']['/getMyScheduled/:dr/:uid'] = array('ClientController','getMyScheduled');
$route['*']['/longCourseProcessor'] = array('ClientController','longCourseProcessor');
$route['*']['/getTwoWayStats'] = array('ClientController','getTwoWayStats');
$route['*']['/getMyMcsList/:rno'] = array('ClientController','getMyMcsList');
$route['*']['/deleteMCS/:mid'] = array('ClientController','deleteMCS');



$route['post']['/saveTestGatewaySettings'] = array('ResellerController','saveTestGatewaySettings');
$route['*']['/tgwLeads'] = array('ResellerController','tgwLeads');
$route['*']['/getMyLeads'] = array('ResellerController','getMyLeads');
$route['*']['/getMyLeads/:dr'] = array('ResellerController','getMyLeads');
$route['*']['/getMyLeads/:dr/:uid'] = array('ResellerController','getMyLeads');
$route['*']['/viewAllotSMS/:uid'] = array('ResellerController','viewAllotSMS');
$route['*']['/viewSiteManagement'] = array('ResellerController','viewSiteManagement');
$route['*']['/siteSignupSettings'] = array('ResellerController','siteSignupSettings');
$route['*']['/checkBalance'] = array('ResellerController','checkCredits');
$route['*']['/checkBalance/:crd'] = array('ResellerController','checkCredits');
$route['post']['/allotSMS'] = array('ResellerController','allotSMS');
$route['post']['/deductSMS'] = array('ResellerController','deductSMS');
$route['*']['/viewDeductSMS/:uid'] = array('ResellerController','viewDeductSMS');
$route['*']['/confirmMakeReseller/:uid'] = array('ResellerController','confirmMakeReseller');
$route['*']['/makeReseller'] = array('ResellerController','makeReseller');
$route['*']['/resetUserPass/:uid'] = array('ResellerController','viewResetUserPass');
$route['post']['/saveUserPass'] = array('ResellerController','saveUserPass');
$route['*']['/delUser/:uid'] = array('ResellerController','deactivateUser');
$route['post']['/setUserSearch'] = array('ResellerController','setUserSearch');
$route['post']['/saveCompanySettings'] = array('ResellerController','saveCompanySettings');
$route['*']['/setTheme/:siteid'] = array('ResellerController','switchTheme');
$route['*']['/viewChangeLogo'] = array('ResellerController','viewChangeLogo');
$route['post']['/changeLogo'] = array('ResellerController','changeLogo');
$route['post']['/saveSiteContent'] = array('ResellerController','saveSiteContent');
$route['post']['/uploadBanner/:banner'] = array('ResellerController','uploadBanner');
$route['*']['/coverage'] = array('ResellerController','coverage');
$route['*']['/coverage/:pindex'] = array('ResellerController','coverage');
$route['*']['/addCountry'] = array('ResellerController','addCountry');
$route['post']['/saveCoverage'] = array('ResellerController','saveCoverage');
$route['*']['/allowCountry/:cid'] = array('ResellerController','allowCountry');
$route['*']['/blockCountry/:cid'] = array('ResellerController','blockCountry');
$route['*']['/removeCountry/:cid'] = array('ResellerController','removeCountry');
$route['*']['/managePricing'] = array('ResellerController','managePricing');
$route['*']['/addAccount'] = array('ResellerController','addAccount');
$route['*']['/editUserAdmin/:uid'] = array('AdminController','editUserAdmin');
$route['*']['/editVolume'] = array('ResellerController','editVolume');
$route['post']['/saveVolume'] = array('ResellerController','saveVolume');
$route['*']['/editPricing/:rid'] = array('ResellerController','editPricing');
$route['post']['/saveEditPricing'] = array('ResellerController','saveEditPricing');
$route['*']['/viewUserRoutes/:uid'] = array('ResellerController','viewUserRoutes');
$route['*']['/viewUserSent/:uid'] = array('ResellerController','viewUserSent');
$route['*']['/viewUserStats/:uid'] = array('ResellerController','viewUserStats');
$route['*']['/viewUserTrans/:uid'] = array('ResellerController','viewUserTrans');
$route['*']['/doUserTrans/:uid'] = array('ResellerController','doUserTrans');
$route['*']['/viewUserLog/:uid'] = array('ResellerController','viewUserLog');
$route['*']['/viewUserSet/:uid'] = array('ResellerController','viewUserSet');
$route['*']['/viewUserAddons/:uid'] = array('ResellerController','viewUserAddons');
$route['post']['/saveUserPricing'] = array('ResellerController','saveUserPricing');
$route['*']['/addUser'] = array('ResellerController','addUser');
$route['*']['/viewAccount/:uid'] = array('ResellerController','viewAccount');
$route['*']['/userSettings/:uid'] = array('ResellerController','userSettings');
$route['*']['/validateRates'] = array('ResellerController','validateRates');
$route['*']['/usrTransaction/:uid'] = array('ResellerController','usrTransaction');
$route['post']['/makeTransaction'] = array('ResellerController','makeTransaction');
$route['*']['/assignKeywords/:uid'] = array('ResellerController','assignKeywords');
$route['post']['/allotKeywords'] = array('ResellerController','allotKeywords');
$route['*']['/editHome'] = array('ResellerController','editHome');
$route['post']['/saveHome'] = array('ResellerController','saveHome');
$route['*']['/editAbout'] = array('ResellerController','editAbout');
$route['post']['/saveAbout'] = array('ResellerController','saveAbout');
$route['*']['/editAbout'] = array('ResellerController','editAbout');
$route['*']['/editPricing'] = array('ResellerController','editPricingPage');
$route['post']['/savePricing'] = array('ResellerController','savePricing');
$route['*']['/editContactPage'] = array('ResellerController','editContactPage');
$route['post']['/saveContactPage'] = array('ResellerController','saveContactPage');
$route['*']['/addonSettings/:uid'] = array('ResellerController','addonSettings');
$route['post']['/saveAddonSet'] = array('ResellerController','saveAddonSet');
$route['*']['/getAllCoverage'] = array('ResellerController','getAllCoverage');
$route['*']['/susManyUsers'] = array('ResellerController','susManyUsers');
$route['*']['/delManyUsers'] = array('ResellerController','delManyUsers');
$route['post']['/setRefundValues'] = array('ResellerController','setRefundValues');
$route['post']['/setSpamStatus'] = array('ResellerController','setSpamStatus');
$route['post']['/saveSiteSet'] = array('ResellerController','saveSiteSet');
$route['post']['/setAddonStatus'] = array('ResellerController','setAddonStatus');
$route['*']['/susUsers'] = array('ResellerController','susUsers');
$route['*']['/getSusUsers'] = array('ResellerController','getSusUsers');
$route['*']['/activateManyUsers'] = array('ResellerController','activateManyUsers');
$route['*']['/activateManyUsers/:user'] = array('ResellerController','activateManyUsers');
$route['post']['/saveUserHlrPrice'] = array('ResellerController','saveUserHlrPrice');
$route['post']['/saveUserMcsNumber'] = array('ResellerController','saveUserMcsNumber');
$route['post']['/revokeUserMcsNumber'] = array('ResellerController','revokeUserMcsNumber');
$route['post']['/setSMSTypePermissions'] = array('ResellerController','setSMSTypePermissions');
$route['post']['/saveNotifySettings'] = array('ResellerController','saveNotifySettings');
$route['post']['/saveSignupDefaults'] = array('ResellerController','saveSignupDefaults');











$route['*']['/getTodayTotalSMS'] = array('AdminController','getTodayTotalSMS');
$route['*']['/getWeekTotalSMS'] = array('AdminController','getWeekTotalSMS');
$route['*']['/getMonthTotalSMS'] = array('AdminController','getMonthTotalSMS');
$route['*']['/getTotalUsers'] = array('AdminController','getTotalUsers');
$route['*']['/getTotalSMS'] = array('AdminController','getTotalSMS');
$route['*']['/getTotalDelSMS'] = array('AdminController','getTotalDelSMS');
$route['*']['/getTodaysTopClients/:dr'] = array('AdminController','getTodaysTopClients');
$route['*']['/adminStats'] = array('AdminController','viewAdminStats');
$route['*']['/adminStats/:refresh'] = array('AdminController','viewAdminStats');
$route['*']['/setAdminRoute/:smsc'] = array('AdminController','setAdminRoute');
$route['*']['/addNewRoute'] = array('AdminController','viewAddNewRoute');
$route['*']['/ndncLetters'] = array('AdminController','ndncLetters');
$route['*']['/getMyAllNdnc'] = array('AdminController','getMyAllNdnc');
$route['*']['/approveNdncApp/:aid'] = array('AdminController','approveNdncApp');
$route['*']['/rejectNdncApp/:aid'] = array('AdminController','rejectNdncApp');
$route['*']['/spamKeywords'] = array('AdminController','viewSpamKeywords');
$route['*']['/spamKeywords/:pindex'] = array('AdminController','viewSpamKeywords');
$route['post']['/addSpamKeyword'] = array('AdminController','addSpamKeyword');
$route['*']['/removeKeyword/:kid'] = array('AdminController','removeKeyword');
$route['*']['/spamSms'] = array('AdminController','viewSpamSms');
$route['*']['/spamSms/:pindex'] = array('AdminController','viewSpamSms');
$route['*']['/showSpamDLR/:shoot_id'] = array('AdminController','showSpamDLR');
$route['*']['/showSpamDLR/:shoot_id/:pindex'] = array('AdminController','showSpamDLR');
$route['*']['/getRoutesTraffic'] = array('AdminController','getRoutesTraffic');
$route['*']['/getSignupData'] = array('AdminController','getSignupData');
$route['*']['/getDownlineStats'] = array('AdminController','getDownlineStats');
$route['post']['/saveUserAdmin'] = array('AdminController','saveUserAdmin');
$route['post']['/switchRoutes'] = array('AdminController','switchRoutes');
$route['post']['/changeSpamStatuses'] = array('AdminController','changeSpamStatuses');
$route['post']['/changeDndStatuses'] = array('AdminController','changeDndStatuses');
$route['post']['/changeInvStatuses'] = array('AdminController','changeInvStatuses');
$route['*']['/editErrorCodes/:rtid'] = array('AdminController','editErrorCodes');
$route['post']['/saveDlrCodes'] = array('AdminController','saveDlrCodes');
$route['*']['/addKeyword'] = array('AdminController','addKeyword');
$route['*']['/editKeyword/:id'] = array('AdminController','editKeyword');
$route['*']['/getTodaysNewUsers'] = array('AdminController','getTodaysNewUsers');
$route['*']['/getPendingSid'] = array('AdminController','getPendingSid');
$route['*']['/getWeeklySales'] = array('AdminController','getWeeklySales');
$route['*']['/getMonthlySales'] = array('AdminController','getMonthlySales');
$route['*']['/getWeeksNewUsers'] = array('AdminController','getWeeksNewUsers');
$route['*']['/getMonthsNewUsers'] = array('AdminController','getMonthsNewUsers');
$route['*']['/getDirectUsers/:type'] = array('AdminController','getDirectUsers');
$route['*']['/getIndirectUsers/:type'] = array('AdminController','getIndirectUsers');
$route['*']['/getTotalUsersByType/:type'] = array('AdminController','getTotalUsersByType');

$route['*']['/customDlrCodes/:rid'] = array('AdminController','customDlrCodes');
$route['*']['/replyNumbers'] = array('AdminController','replyNumbers');
$route['*']['/getReplyNums'] = array('AdminController','getReplyNums');
$route['*']['/addReplyNum'] = array('AdminController','addReplyNum');
$route['post']['/saveReplyNum'] = array('AdminController','saveReplyNum');
$route['*']['/editReplyNum/:nid'] = array('AdminController','editReplyNum');
$route['*']['/delReplyNum/:nid'] = array('AdminController','delReplyNum');
$route['*']['/kannelAccess'] = array('AdminController','kannelAccess');
$route['*']['/getKannelAccess'] = array('AdminController','getKannelAccess');
$route['*']['/getAllSids'] = array('AdminController','getAllSids');
$route['*']['/getAllSpamKw'] = array('AdminController','getAllSpamKw');
$route['post']['/setBatchStatus'] = array('AdminController','setBatchStatus');
$route['post']['/setScheduledBatchStatus'] = array('AdminController','setScheduledBatchStatus');
$route['*']['/getAllSent'] = array('AdminController','getAllSent');
$route['*']['/getAllSent/:dr'] = array('AdminController','getAllSent');
$route['post']['/setDlrRefundFlag'] = array('AdminController','setDlrRefundFlag');
$route['post']['/setDlrRefundRule'] = array('AdminController','setDlrRefundRule');
$route['*']['/addSecRoute'] = array('AdminController','addSecRoute');
$route['post']['/saveSecRoute'] = array('AdminController','saveSecRoute');
$route['*']['/editSecRoute/:rtid'] = array('AdminController','editSecRoute');
$route['*']['/smppSetup'] = array('AdminController','smppSetup');
$route['*']['/addSmppInstance'] = array('AdminController','addSmppInstance');
$route['*']['/delSmppInstance/:bid'] = array('AdminController','delSmppInstance');
$route['post']['/saveSmppServer'] = array('AdminController','saveSmppServer');
$route['*']['/hlrApiSetup'] = array('AdminController','hlrApiSetup');
$route['post']['/saveHlrSetting'] = array('AdminController','saveHlrSetting');
$route['post']['/setRouteState'] = array('AdminController','setRouteState');
$route['*']['/getAllUsersAdmin'] = array('AdminController','getAllUsersAdmin');
$route['*']['/getOnlineUsersAdmin'] = array('AdminController','getOnlineUsersAdmin');
$route['*']['/getSysMonTraffic'] = array('AdminController','getSysMonTraffic');
$route['*']['/allSentSMS'] = array('AdminController','allSentSMS');
$route['*']['/viewSpamShoots'] = array('AdminController','viewSpamShoots');
$route['*']['/getAllSpam'] = array('AdminController','getAllSpam');
$route['*']['/mobilePrefixes'] = array('AdminController','mobilePrefixes');
$route['*']['/getAllPrefixes'] = array('AdminController','getAllPrefixes');
$route['*']['/addPrefix'] = array('AdminController','addPrefix');
$route['post']['/savePrefix'] = array('AdminController','savePrefix');
$route['post']['/saveImportedPrefixes'] = array('AdminController','saveImportedPrefixes');
$route['*']['/editPrefix/:pid'] = array('AdminController','editPrefix');
$route['*']['/deletePrefix/:pid'] = array('AdminController','deletePrefix');
$route['post']['/delManyPrefixes'] = array('AdminController','delManyPrefixes');
$route['*']['/addSmppUser/:uid'] = array('AdminController','addSmppUser');
$route['post']['/saveSmppAccount'] = array('AdminController','saveSmppAccount');
$route['*']['/editSmppAccount/:uid/:aid'] = array('AdminController','editSmppAccount');
$route['*']['/deleteSmppAccount/:uid/:aid'] = array('AdminController','deleteSmppAccount');
$route['*']['/sshTest'] = array('AdminController','sshTest');
$route['post']['/getMPStatus'] = array('AdminController','getMPStatus');
$route['post']['/startMasterProcess'] = array('AdminController','startMasterProcess');
$route['*']['/restartServer'] = array('AdminController','restartServer');
$route['*']['/startKannel'] = array('AdminController','startKannel');
$route['*']['/stopKannel'] = array('AdminController','stopKannel');
$route['post']['/startBox'] = array('AdminController','startBox');
$route['post']['/stopBox'] = array('AdminController','stopBox');
$route['*']['/startSqlbox'] = array('AdminController','startSqlbox');
$route['*']['/stopSqlbox'] = array('AdminController','stopSqlbox');
$route['post']['/clearLogs'] = array('AdminController','clearLogs');
$route['*']['/kannelLog'] = array('AdminController','kannelLog');
$route['*']['/getKannelLog'] = array('AdminController','getKannelLog');
$route['*']['/smsboxLog'] = array('AdminController','smsboxLog');
$route['*']['/getSmsboxLog'] = array('AdminController','getSmsboxLog');
$route['*']['/sqlboxLog'] = array('AdminController','sqlboxLog');
$route['*']['/getSqlboxLog'] = array('AdminController','getSqlboxLog');
$route['*']['/smppboxLogs'] = array('AdminController','smppboxLogs');
$route['*']['/smppboxLogs/:abid'] = array('AdminController','smppboxLogs');
$route['post']['/getSmppboxLog'] = array('AdminController','getSmppboxLog');
$route['*']['/globalSettings'] = array('AdminController','globalSettings');
$route['*']['/msgSettings'] = array('AdminController','msgSettings');
$route['*']['/apSettings'] = array('AdminController','apSettings');
$route['*']['/kannelSettings'] = array('AdminController','kannelSettings');
$route['post']['/saveGlobalSettings'] = array('AdminController','saveGlobalSettings');
$route['*']['/lcrLoops'] = array('AdminController','lcrLoops');
$route['*']['/addLcrLoop'] = array('AdminController','addLcrLoop');
$route['*']['/addLcrLoop/:page'] = array('AdminController','addLcrLoop');
$route['post']['/saveLcrLoop'] = array('AdminController','saveLcrLoop');
$route['*']['/getRoutePrefixCount'] = array('AdminController','getRoutePrefixCount');
$route['*']['/viewLcrPrefixes/:lcrid/:rid'] = array('AdminController','viewLcrPrefixes');
$route['*']['/removeLcrRoutePrefixes'] = array('AdminController','removeLcrRoutePrefixes');
$route['*']['/editLcrLoop/:lid'] = array('AdminController','editLcrLoop');
$route['*']['/editLcrPrefixes/:lid'] = array('AdminController','editLcrPrefixes');
$route['*']['/getRoutePrefixes/:lid/:rid'] = array('AdminController','getRoutePrefixes');
$route['*']['/delLcrLoop/:lid'] = array('AdminController','delLcrLoop');







//---------- Delete if not needed ------------


//view the logs and profiles XML, filename = db.profile, log, trace.log, profile
$route['*']['/debug/:filename'] = array('MainController', 'debug', 'authName'=>'DooPHP Admin', 'auth'=>$admin, 'authFail'=>'Unauthorized!');

//show all urls in app
$route['*']['/allurl'] = array('MainController', 'allurl', 'authName'=>'DooPHP Admin', 'auth'=>$admin, 'authFail'=>'Unauthorized!');

//generate routes file. This replace the current routes.conf.php. Use with the sitemap tool.
$route['post']['/gen_sitemap'] = array('MainController', 'gen_sitemap', 'authName'=>'DooPHP Admin', 'auth'=>$admin, 'authFail'=>'Unauthorized!');

//generate routes & controllers. Use with the sitemap tool.
$route['post']['/gen_sitemap_controller'] = array('MainController', 'gen_sitemap_controller', 'authName'=>'DooPHP Admin', 'auth'=>$admin, 'authFail'=>'Unauthorized!');

//generate Controllers automatically
$route['*']['/gen_site'] = array('MainController', 'gen_site', 'authName'=>'DooPHP Admin', 'auth'=>$admin, 'authFail'=>'Unauthorized!');

//generate Models automatically
$route['*']['/gen_model'] = array('MainController', 'gen_model', 'authName'=>'DooPHP Admin', 'auth'=>$admin, 'authFail'=>'Unauthorized!');


?>