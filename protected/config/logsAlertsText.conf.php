<?php
/* This file contans a list of texts which is used for activity logging and/or notifying users via alerts. The purpose of this file is to provide a single point where you can access/modify all the texts used as comments/remarks in logging process and notifications. */

//front end log
$config['user_signup'] = 'New account was created. Signup from website. Category: ||';
$config['user_signup_reseller'] = 'New signup from website. Login ID: ||';
$config['user_reset_password'] = 'Password was reset by the user.';
$config['reset_without_verify'] = 'Attempt to reset password without OTP validation.';
$config['user_payment_paypal'] = 'User made a successful payment via Paypal. The Paypal transaction ID is: ||';
$config['user_payment_paypal_fail'] = 'Paypal payment attempt failed. Invoice ID: ||';
$config['resetpass_without_otp'] = 'Attempt was made to reset password without verifying OTP. User ID associated is: ||';

//lead capture
$config['contact_lead_capture'] = 'A new query received from the Contact Form on your app website.';
$config['tgw_lead_capture'] = 'A new prospect has tested SMS delivery from the website.';

//reseller specific notifs
$config['signup_low_credits'] = 'New account did not get SMS credits due to low balance. Set a low amount or buy more SMS credits.';

//Activity Log texts
$config['admin_start_proc'] = 'Process was manually enabled: ||';
$config['admin_stop_proc'] = 'Process was manually disabled: ||';
$config['admin_stop_all_procs'] = 'All background tasks were disabled.';
$config['sms_campaign_scheduled'] = 'SMS Campaign scheduled by the user.';
$config['campaign_schedule_cancel'] = 'Scheduled campaign was cancelled.';
$config['sms_campaign_sent'] = 'SMS Campaign submitted by the user.';
$config['new_agreement_upload'] = 'A new agreement document was uploaded.';
$config['new_document_upload'] = 'A new document was uploaded by user.';
$config['user_url_tamper'] = 'User tried accessing invalid page.';
$config['doc_comment_post'] = 'A new comment was posted on a document.';
$config['doc_delete'] = 'A document was deleted.';
$config['support_ticket_new'] = 'A new support ticket was raised.';
$config['support_ticket_comment'] = 'A new comment was posted on a Support Ticket.';
$config['spam_campaign_alert'] = 'User tried to submit a campaign with SPAM keywords.';
$config['user_profile_edit'] = 'User edited and saved profile details.';
$config['user_verify_mobile'] = 'User successfully verified mobile number.';
$config['user_verify_email'] = 'User successfully verified email associated with the account.';
$config['unauthorized_user_access'] = 'User tried accessing page for which its not authorized.';
$config['added_user_account'] = 'User added another account in downline.';
$config['reseller_make_transaction'] = 'User made a credit transaction on a downline account.';
$config['account_credit_alert'] = 'SMS credits were added into your account.';
$config['account_debit_alert'] = 'Your account was debited. SMS credits were deducted by account manager.';
$config['invalid_post_value'] = 'User posted a value that was invalid. Could be a hack attempt.';
$config['document_status_changed'] = 'Status of the document was changed by owner.';
$config['agreement_status_changed'] = 'Agreement document status has been updated.';
$config['user_account_delete'] = 'User deleted an account from the downline.';
$config['sender_id_approved'] = 'A requested sender-ID was approved by admin.';
$config['sender_id_rejected'] = 'A requested sender-ID was rejected and removed. Please request a new Sender-ID.';
$config['template_approved'] = 'A requested SMS Template was approved by admin.';
$config['template_rejected'] = 'A requested SMS Template was rejected and removed. Please request a new template.';
$config['spam_campaign_rejected'] = 'Campaign held for SPAM keyword match has been rejected by Admin. Credits have been refunded. Please try a new campaign.';
$config['spam_campaign_cleared'] = 'Campaign held for SPAM keyword match has been approved by Admin. Your campaign was sent successfully.';
$config['staff_switch_alert'] = 'Your account manager has been changed. Please login again to see changes.';

//Admin activities
$config['staff_reset_password'] = 'Password was reset for the staff member with Login ID: ||';
$config['reseller_reset_password'] = 'Password was reset performed by Upline.';
$config['new_senderid_4approval'] = 'Please approve a new or modified Sender ID request: ||';
$config['temp_campaign_hold'] = 'Campaign was held temporarily because Kannel or SMPP was not running.';
$config['new_blocked_ip_alert'] = 'A new IP was blocked due to suspicious activity. Please review.';
$config['website_inactive_reseller'] = 'Reseller website is inactive. Users are not able to login. Please review.';

//Archive Process log text
$config['arch_proc_start'] = 'Archive process started.';
$config['arch_proc_insert_sum'] = 'SMS summary imported successfully.';
$config['arch_proc_insert_sent'] = 'Process has successfully imported specified data into archive database.';
$config['arch_proc_delete'] = 'Archived data is removed successfully from the transactional database.';
$config['arch_proc_end'] = 'Archive process finished.';
$config['arch_proc_error'] = 'Archive Process encountered an error: ||';


//system notifications
$config['sysnot_ndnc_success'] = 'NDNC Import task has been completed successfully.';
$config['sysnot_ndnc_error'] = 'NDNC Import task encountered an error. Please check web-server error log.';
$config['sysnot_schedule_sent'] = 'Your scheduled campaign was successfully sent';
$config['large_campaign_notify'] = 'A large campaign was submitted.';


//watchman log
$config['wm_log_emails'] = 'Emails with Daily Activity Reports are now drafted and in queue.';
$config['wm_log_proc_err'] = 'The process was found dead. We have started it again. Please check web server error logs.';

