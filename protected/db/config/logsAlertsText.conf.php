<?php
/* This file contans a list of texts which is used for activity logging and/or notifying users via alerts. The purpose of this file is to provide a single point where you can access/modify all the texts used as comments/remarks in logging process and notifications. */

//Activity Log texts
$config['admin_start_proc'] = 'Process was manually enabled: ';
$config['admin_stop_proc'] = 'Process was manually disabled: ';
$config['admin_stop_all_procs'] = 'All background tasks were disabled.';

//Credit Log texts



//Refund Log texts



//Archive Process log text
$config['arch_proc_start'] = 'Archive process started.';
$config['arch_proc_insert_sum'] = 'SMS summary imported successfully.';
$config['arch_proc_insert_sent'] = 'Process has successfully imported specified data into archive database.';
$config['arch_proc_delete'] = 'Archived data is removed successfully from the transactional database.';
$config['arch_proc_end'] = 'Archive process finished.';
$config['arch_proc_error'] = 'Archive Process encountered an error: ';


//system notifications
$config['sysnot_ndnc_success'] = 'NDNC Import task has been completed successfully.';
$config['sysnot_ndnc_error'] = 'NDNC Import task encountered an error. Please check web-server error log.';
$config['sysnot_schedule_sent'] = 'Your scheduled campaign was successfully sent';


//watchman log
$config['wm_log_emails'] = 'Emails with Daily Activity Reports are now drafted and in queue.';
$config['wm_log_proc_err'] = 'The process was found dead. We have started it again. Please check web server error logs.';

