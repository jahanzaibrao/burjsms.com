<?php

/**
 * ClientController
 *
 * @author saurav
 *
 * 1. MISC APP FUNCTIONS
 * 2. WATCHMAN PROCESS
 * 3. Sender ID management
 * 4. SMS Templates Mgmt
 * 5. Contact Management
 * 6. Send SMS
 * 7. Reports
 * 8. Support
 * 9. Logs
 * 10. API
 * 11. URL Shortner
 * 12. Misc
 * 13. 2-Way
 */

use Google\Cloud\Translate\V2\TranslateClient;
use Google\Service\AndroidProvisioningPartner\Dpc;

class ClientController extends DooController
{

    public function __construct()
    {
        Doo::loadHelper('DooSmppcubeHelper');
        Doo::loadHelper('DooTextHelper');
    }
    /**-----------------------------------------AUTH-----------------------------------**/
    public function isLogin()
    {
        session_start();
        if (!$_SESSION['user'] || !$_SESSION['webfront']) {
            throw new Exception();
        }
    }


    //1. MISC APP FUNCTIONS

    public function globalFileUpload()
    {
        $this->isLogin();
        $mode = $_POST['mode'];

        Doo::loadHelper('DooFile');
        $doofile = new DooFile;

        $fail = 0;
        $reason = '';

        //1. NDNC
        if ($mode == 'ndnc') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'text/x-csv',
                'text/csv',
                'text/plain',
                'application/octet-stream'
            ); //since xlsx are essentially zip of xmls
            $allowed_extentions = array('CSV', 'csv');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide CSV files only';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide CSV files only, not ' . $mimetype;
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir, 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($reason);
            }
        }

        //2. Countries prefix
        if ($mode == 'ocpr') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'application/zip',
                'application/octet-stream'
            ); //since xlsx are essentially zip of xmls
            $allowed_extentions = array('xlsx', 'xls');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx or xls file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx or xls file only.';
            }


            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir, 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //3. Logo Upload
        if ($mode == 'logo') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'image/png',
                'image/jpeg'
            );
            $allowed_extentions = array('png', 'jpg', 'jpeg');

            //specific checks for image
            list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
            $img_mime = image_type_to_mime_type($type);

            if (!$width) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid image file only.';
            }

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide png, jpg or jpeg file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types) || !in_array($img_mime, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid image file only.';
            }


            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->image_upload_dir . 'logos/', 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //4. Banner images upload


        //5. Contact Import
        if ($mode == 'contacts') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'application/zip',
                'application/octet-stream',
                'text/plain'
            ); //since xlsx are essentially zip of xmls
            $allowed_extentions = array('xlsx', 'xls', 'csv', 'txt');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx, xls, csv or txt file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx, xls, csv or txt file only.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir, 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //6. Send SMS contact upload
        if ($mode == 'sendsms') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            $original_name = DooTextHelper::cleanInput($_FILES['file']['name'], '.', 1);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'application/zip',
                'application/octet-stream',
                'text/plain'
            ); //since xlsx are essentially zip of xmls
            $allowed_extentions = array('xlsx', 'xls', 'csv', 'txt');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx, xls, csv or txt file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx, xls, csv or txt file only!';
            }
            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir, 'file');
                echo json_encode(array('newfile' => $newfile, 'orgfile' => $original_name));
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //7. document manager
        if ($mode == 'docmgr') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'application/octet-stream',
                'text/plain',
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/x-pdf',
                'application/zip',
                'application/x-compressed',
                'application/x-zip-compressed'
            ); //since xlsx are essentially zip of xmls
            $allowed_extentions = array('xlsx', 'xls', 'csv', 'txt', 'jpeg', 'gif', 'jpg', 'png', 'pdf', 'zip');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid file only.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir, 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //8. phonebook import
        if ($mode == 'phonebook') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'application/zip',
                'application/octet-stream',
                'text/plain'
            ); //since xlsx are essentially zip of xmls
            $allowed_extentions = array('xlsx', 'xls', 'csv', 'txt');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx, xls, csv or txt file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide xlsx, xls, csv or txt file only.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir, 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //9. Media
        if ($mode == 'media') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'image/bmp',
                'image/gif',
                'image/jpeg',
                'image/png',
                'image/svg+xml',
                'image/tiff',
                'image/webp',
                'audio/midi',
                'audio/x-midi',
                'audio/mpeg',
                'audio/ogg',
                'audio/wav',
                'audio/webm',
                'audio/3gpp',
                'audio/3gpp2',
                'video/x-msvideo',
                'video/mpeg',
                'video/ogg',
                'video/mp2t',
                'video/webm',
                'video/3gpp',
                'video/3gpp2',
                'video/mp4',
                'application/pdf'
            );
            $allowed_extentions = array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'svg', 'tiff', 'tif', 'webp', 'mid', 'midi', 'mp3', 'oga', 'wav', 'weba', '3g2', 'avi', 'mpeg', 'ogv', 'ts', 'webm', '3gp', 'mp4', 'pdf');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid file only.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir . 'media/', 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //10. Send MMS
        if ($mode == 'sendmms') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'image/bmp',
                'image/gif',
                'image/jpeg',
                'image/svg+xml',
                'image/tiff',
                'image/webp',
                'audio/midi',
                'audio/x-midi',
                'audio/mpeg',
                'audio/ogg',
                'audio/wav',
                'audio/webm',
                'audio/3gpp',
                'audio/3gpp2',
                'video/x-msvideo',
                'video/mpeg',
                'video/ogg',
                'video/mp2t',
                'video/webm',
                'video/3gpp',
                'video/3gpp2',
                'video/mp4',
                'application/pdf'
            );
            $allowed_extentions = array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'svg', 'tiff', 'tif', 'webp', 'mid', 'midi', 'mp3', 'oga', 'wav', 'weba', '3g2', 'avi', 'mpeg', 'ogv', 'ts', 'webm', '3gp', 'mp4', 'pdf');

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid file only.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->global_upload_dir . 'media/', 'file');
                echo $newfile;
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }

        //11. WhatsApp Template Header
        //3. Logo Upload
        if ($mode == 'wt_header') {
            //validate file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            $filelen = filesize($_FILES['file']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'image/png',
                'image/jpeg'
            );
            $allowed_extentions = array('png', 'jpg', 'jpeg');

            //specific checks for image
            list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
            $img_mime = image_type_to_mime_type($type);

            if (!$width) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid image file only.';
            }

            //check if extension is among allowed ones
            if (!$doofile->checkFileExtension('file', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide png, jpg or jpeg file only.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types) || !in_array($img_mime, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid image file only.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->image_upload_dir . 'logos/', 'file');
                //call resumable API to get file handle
                $url = 'https://graph.facebook.com/v20.0/' . Doo::conf()->wba_app_id . '/uploads?file_name=' . $newfile . '&file_length=' . $filelen . '&file_type=' . $img_mime . '&access_token=' . Doo::conf()->wba_perm_token;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $resUp = json_decode($result, true);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($status != 201 && $status != 200) {
                    die("Error: call to URL $url failed with status $status, response $result, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch));
                }
                curl_close($ch);
                //start the upload now
                $url2 = 'https://graph.facebook.com/v20.0/' . $resUp["id"];
                $headers = [
                    "Authorization: OAuth " . Doo::conf()->wba_perm_token,
                    "file_offset: 0"
                ];
                $ch2 = curl_init();

                curl_setopt($ch2, CURLOPT_URL, $url2);
                curl_setopt($ch2, CURLOPT_POST, true);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, file_get_contents(Doo::conf()->image_upload_dir . 'logos/' . $newfile));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch2);
                if ($response === false) {
                    $error = curl_error($ch2);
                    header('Content-Type: application/json');
                    header("HTTP/1.0 406 File Not Acceptable");
                    echo "cURL Error: $error";
                } else {
                    $handle = json_decode($response, true);
                }

                curl_close($ch2);
                //append handle in the newfilename
                echo base64_encode($newfile . '||' . $handle["h"]);
            } else {
                header('Content-Type: application/json');
                header("HTTP/1.0 406 File Not Acceptable");
                echo json_encode($this->SCTEXT($reason));
            }
        }
    }

    public function deleteUploadedFile()
    {
        $this->isLogin();

        $mode = $_POST['mode'];
        $file = $_POST['filename'];

        if ($file == '') exit;

        Doo::loadHelper('DooFile');
        $ofile = new DooFile;

        if ($mode == 'ndnc' || $mode == 'ocpr' || $mode == 'contacts' || $mode == 'sendsms' || $mode == 'docmgr' || $mode == 'phonebook' || $mode == 'rprice') {
            $ofile->delete(Doo::conf()->global_upload_dir . $file);
        }

        if ($mode == 'logo') {
            $ofile->delete(Doo::conf()->image_upload_dir . 'logos/' . $file);
        }
        if ($mode == 'media') {
            $ofile->delete(Doo::conf()->global_upload_dir . 'media/' . $file);
        }
    }

    public function globalFileDownload()
    {
        $this->isLogin();
        $mode = $this->params['mode'];

        //documents download
        if ($mode == 'docmgr') {
            //get document data
            $docid = intval($this->params['id']);
            if ($docid == 0) {
                //invalid id
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Invalid File';
                return Doo::conf()->APP_URL . 'manageDocs';
            }
            $docobj = Doo::loadModel('ScUsersDocuments', true);
            $docobj->id = $docid;
            $docdata = Doo::db()->find($docobj, array('select' => 'type, location, owner_id, shared_with', 'limit' => 1));

            $uar = explode(",", $docdata->shared_with);
            if ($docdata->owner_id != $_SESSION['user']['userid'] && !in_array($_SESSION['user']['userid'], $uar)) {
                //invalid user
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Access Denied';
                return Doo::conf()->APP_URL . 'manageDocs';
            }

            if ($docdata->type == 1) {
                //invoice doesn't really exist as a file, we need to generate a pdf
                //do nothing jquery print implemented
            } else {
                $file = Doo::conf()->global_upload_dir . $docdata->location;
                if (file_exists($file) && $docdata->location != '') {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    exit;
                } else {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'The file is missing. Please ask document owner to re-upload the file.';
                    return Doo::conf()->APP_URL . 'viewDocument/' . $docid;
                }
            }
        }


        //export contacts
        if ($mode == 'group') {
            Doo::loadModel('ScUserContacts');
            $obj = new ScUserContacts;
            $obj->user_id = $_SESSION['user']['userid'];
            $obj->group_id = $this->params['id'];
            $contacts = Doo::db()->find($obj);
            $total = sizeof($contacts);

            $grpobj = Doo::loadModel('ScUserContactGroups', true);
            $grpobj->id = $this->params['id'];
            $grpdata = Doo::db()->find($grpobj, array('limit' => 1, 'select' => 'group_name,column_labels'));
            $grpname = $grpdata->group_name;
            $grpcols = unserialize($grpdata->column_labels);

            //get all countries
            $cvobj = Doo::loadModel('ScCoverage', true);
            $cvdata = Doo::db()->find($cvobj, array('select' => 'id, country'));

            //if total is more than one file can handle, split it

            if ($total > 50000) {
                //split into many
                $batches = array_chunk($contacts, 50000);
                //prepare zip
                $filename = 'CONTACTS-' . strtoupper(str_replace(" ", "-", $grpname)) . '-' . $_SESSION['user']['loginid'] . '-' . time();
                $fileloc = Doo::conf()->global_export_dir . $filename;
                $zip = new ZipArchive;
                $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);
                $part = 1;

                foreach ($batches as $batch) {
                    $exstr = " Mobile " . "\t";
                    $exstr .= "Name " . "\t";
                    if ($grpcols['varC'] != '')  $exstr .= $grpcols['varC'] . "\t";
                    if ($grpcols['varD'] != '')  $exstr .= $grpcols['varD'] . "\t";
                    if ($grpcols['varE'] != '')  $exstr .= $grpcols['varE'] . "\t";
                    if ($grpcols['varF'] != '')  $exstr .= $grpcols['varF'] . "\t";
                    if ($grpcols['varG'] != '')  $exstr .= $grpcols['varG'] . "\t";
                    $exstr .= "Network " . "\t";
                    $exstr .= "Circle " . "\t";
                    $exstr .= "Country " . "\t";
                    $exstr .= "\n";
                    //loop through each

                    foreach ($batch as $dt) {
                        $cid = $dt->country;
                        $cmap = function ($e) use ($cid) {
                            return $e->id == $cid;
                        };
                        $cvfobj = array_filter($cvdata, $cmap);
                        $k = key($cvfobj);

                        $country = $cvfobj[$k]->country;

                        $exstr .= $dt->mobile . " \t";
                        $exstr .= $dt->name . " \t";
                        if ($grpcols['varC'] != '')  $exstr .= $dt->varC . "\t";
                        if ($grpcols['varD'] != '')  $exstr .= $dt->varD . "\t";
                        if ($grpcols['varE'] != '')  $exstr .= $dt->varE . "\t";
                        if ($grpcols['varF'] != '')  $exstr .= $dt->varF . "\t";
                        if ($grpcols['varG'] != '')  $exstr .= $dt->varG . "\t";
                        $exstr .= $dt->network . " \t";
                        $exstr .= $dt->circle . " \t";
                        $exstr .= $country . " \t";
                        $exstr .= "\n";
                    }
                    // write in excel file
                    $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);
                    fclose($fh);

                    //add to zip
                    $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                    $part++;
                }
                $zip->close();

                //download
                $file = $filename . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                array_map('unlink', glob($fileloc . '*'));
                exit;
            } else {
                //single excel file

                $exstr = " Mobile " . "\t";
                $exstr .= "Name " . "\t";
                if ($grpcols['varC'] != '')  $exstr .= $grpcols['varC'] . "\t";
                if ($grpcols['varD'] != '')  $exstr .= $grpcols['varD'] . "\t";
                if ($grpcols['varE'] != '')  $exstr .= $grpcols['varE'] . "\t";
                if ($grpcols['varF'] != '')  $exstr .= $grpcols['varF'] . "\t";
                if ($grpcols['varG'] != '')  $exstr .= $grpcols['varG'] . "\t";
                $exstr .= "Network " . "\t";
                $exstr .= "Circle " . "\t";
                $exstr .= "Country " . "\t";
                $exstr .= "\n";

                foreach ($contacts as $dt) {
                    $cid = $dt->country;
                    $cmap = function ($e) use ($cid) {
                        return $e->id == $cid;
                    };
                    $cvfobj = array_filter($cvdata, $cmap);
                    $k = key($cvfobj);

                    $country = $cvfobj[$k]->country;

                    $exstr .= $dt->mobile . " \t";
                    $exstr .= $dt->name . " \t";
                    if ($grpcols['varC'] != '')  $exstr .= $dt->varC . "\t";
                    if ($grpcols['varD'] != '')  $exstr .= $dt->varD . "\t";
                    if ($grpcols['varE'] != '')  $exstr .= $dt->varE . "\t";
                    if ($grpcols['varF'] != '')  $exstr .= $dt->varF . "\t";
                    if ($grpcols['varG'] != '')  $exstr .= $dt->varG . "\t";
                    $exstr .= $dt->network . " \t";
                    $exstr .= $dt->circle . " \t";
                    $exstr .= $country . " \t";
                    $exstr .= "\n";
                }

                //output
                $xlfile = 'CONTACTS-' . strtoupper(str_replace(" ", "-", $grpname)) . '-' . $_SESSION['user']['loginid'] . '-' . time();
                $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                fwrite($fh, $exstr);
                fclose($fh);

                //zip file for easier download
                $zip = new ZipArchive;
                $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                $zip->close();

                //download
                $file = $xlfileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                unlink($xlfileloc . '.xls');
                exit;
            }
        }


        //export dlr reports
        if ($mode == 'dlr') {
            Doo::loadModel('ScSentSms');
            $obj = new ScSentSms;
            $obj->sms_shoot_id = $this->params['id'];
            $cmpns = Doo::db()->find($obj);
            $total = sizeof($cmpns);
            $uid = $cmpns[0]->user_id;
            $uobj = Doo::loadModel('ScUsers', true);
            $udata = $uobj->getProfileInfo($uid);
            if ($_SESSION['user']['userid'] != $uid && $_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }

            $sidobj = Doo::loadModel('ScSenderId', true);

            //get dlr codes
            $rtobj = Doo::loadModel('ScSmsRoutes', true);
            $rtobj->id = $cmpns[0]->route_id;
            $rdata = Doo::db()->find($rtobj, array('limit' => 1, 'select' => 'smpp_list'));
            //get route custom dlr codes
            $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
            $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category', 'where' => 'smpp_id IN (' . $rdata->smpp_list . ')'));

            //get network and circle data
            $rtobj = Doo::LoadModel('ScSmsRoutes', true);
            $rtobj->id = $cmpns[0]->route_id;
            $country_id = Doo::db()->find($rtobj, array('select' => 'country_id', 'limit' => 1))->country_id;

            $cvobj = Doo::loadModel('ScCoverage', true);
            $cvobj->id = $country_id;
            $cvdata = Doo::db()->find($cvobj, array('select' => 'prefix, valid_lengths', 'limit' => 1));

            // $ncobj = Doo::loadModel('ScOcprMapping', true);
            // $ncobj->coverage = $country_id;
            $pfxdata = []; // Doo::db()->find($ncobj, array('select' => 'prefix, operator, circle'));

            //summary
            $clicktrack = 1; //enable by default and later check
            if ($_SESSION['user']['group'] != 'admin') {
                //check the format of masking and if user is allowed to see at least the click reports
                $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
                $upbdbobj->user_id = $_SESSION['user']['userid'];
                $upbdata = Doo::db()->find($upbdbobj, array('limit' => 1));
                $maskdata = $upbdata->id ? unserialize($upbdata->mask_pattern) : array('type' => 0, 'mpos' => -5, 'mlen' => 4);
                $clicktrack = $upbdata->id ? $upbdata->click_track : 0;
            }

            $sumobj = Doo::loadModel('ScSmsSummary', true);
            $sumobj->sms_shoot_id = $this->params['id'];
            $sumdata = Doo::db()->find($sumobj, array('select' => 'sms_text,hide_mobile', 'limit' => 1));

            $pbflag = $_SESSION['user']['group'] == 'admin' ? 0 : $sumdata->hide_mobile;

            $res = array();
            $res['iTotalRecords'] = $total;
            $res['iTotalDisplayRecords'] = $total;
            $res['aaData'] = array();

            $smscat = unserialize($cmpns[0]->sms_type);

            if ($smscat['personalize'] == '1') {
                if ($total > 50000) {
                    //split total no of sms into smaller chunks
                    $batches = array_chunk($cmpns, 50000);

                    //prepare zip
                    $filename = 'REPORTS-' . $_SESSION['user']['loginid'] . '-' . time();
                    $fileloc = Doo::conf()->global_export_dir . $filename;
                    $zip = new ZipArchive;
                    $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);
                    $part = 1;
                    foreach ($batches as $batch) {
                        $exstr = " Destination Mobile " . "\t";
                        $exstr .= "Network " . "\t";
                        $exstr .= "Circle " . "\t";
                        $exstr .= "Time " . "\t";
                        $exstr .= "Sender ID " . "\t";
                        $exstr .= "SMS Text " . "\t";
                        $exstr .= "SMS Count " . "\t";
                        if ($udata->account_type == 1) $exstr .= "Price (" . Doo::conf()->currency_name . ") " . "\t";
                        $exstr .= "Msg ID " . "\t";
                        $exstr .= "DLR Status " . "\t";
                        $exstr .= "Explanation " . "\t";

                        $exstr .= "\n";
                        //loop through each
                        foreach ($batch as $dt) {
                            $str = preg_replace("/\t/", "\\t", htmlspecialchars_decode($dt->sms_text));
                            $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                            $senderid = $sidobj->getName($dt->sender_id);
                            $vdlr = $dt->vendor_dlr;
                            $dmap = function ($e) use ($vdlr) {
                                return $e->vendor_dlr_code == $vdlr;
                            };
                            $dlrcobj = array_filter($dlrcodes, $dmap);
                            $k = key($dlrcobj);
                            if ($dlrcodes[$k]->description != '') {
                                $dlrdesc = $dlrcodes[$k]->description;
                            } else {
                                $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                                $dlrdesc = $dcdata['desc'];
                            }

                            //network and circle
                            if ($dt->dlr != '-1') {
                                //get this for valid numbers only
                                if (strlen($dt->mobile) == min(explode(",", $cvdata->valid_lengths))) {
                                    //mobile is without country prefix
                                    $pfx = substr($dt->mobile, 0, intval(4));
                                    $pmap = function ($d) use ($pfx) {
                                        return $d->prefix == $pfx;
                                    };
                                    $pfxfilobj = array_filter($pfxdata, $pmap);
                                    $k = key($pfxfilobj);
                                    $network = trim(utf8_decode($pfxdata[$k]->operator));
                                    $circle = trim(utf8_decode($pfxdata[$k]->circle));
                                } else {
                                    //country code is also present
                                    $pfx = substr($dt->mobile, strlen($cvdata->prefix) - 1, intval(4));
                                    $pmap = function ($d) use ($pfx) {
                                        return $d->prefix == $pfx;
                                    };
                                    $pfxfilobj = array_filter($pfxdata, $pmap);
                                    $k = key($pfxfilobj);
                                    $network = trim(utf8_decode($pfxdata[$k]->operator));
                                    $circle = trim(utf8_decode($pfxdata[$k]->circle));
                                }
                            }

                            $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                            $exstr .= $mobile . " \t";
                            $exstr .= $network . " \t";
                            $exstr .= $circle . " \t";
                            $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                            $exstr .= $senderid . " \t";
                            $exstr .= $stxtstr . " \t";
                            $exstr .= number_format($dt->sms_count) . " \t";
                            if ($udata->account_type == 1) $exstr .= number_format($dt->price, 4) . " \t";
                            $exstr .= '#' . $dt->id . " \t";
                            $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                            $exstr .= $dlrdesc . " \t";
                            $exstr .= "\n";
                        }

                        // write in excel file
                        $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                        fwrite($fh, $exstr);
                        fclose($fh);

                        //add to zip
                        $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                        $part++;
                    }

                    $zip->close();

                    //download
                    $file = $fileloc . '.zip';
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    unlink($file);
                    array_map('unlink', glob($fileloc . '*'));
                    exit;
                } else {
                    //single export file

                    $exstr = " Destination Mobile " . "\t";
                    $exstr .= "Network " . "\t";
                    $exstr .= "Circle " . "\t";
                    $exstr .= "Time " . "\t";
                    $exstr .= "Sender ID " . "\t";
                    $exstr .= "SMS Text " . "\t";
                    $exstr .= "SMS Count " . "\t";
                    if ($udata->account_type == 1) $exstr .= "Price (" . Doo::conf()->currency_name . ") " . "\t";
                    $exstr .= "Msg ID " . "\t";
                    $exstr .= "DLR Status " . "\t";
                    $exstr .= "Explanation " . "\t";

                    $exstr .= "\n";


                    foreach ($cmpns as $dt) {
                        //$stxtstr = $dt->sms_text;
                        $str = preg_replace("/\t/", "\\t", htmlspecialchars_decode($dt->sms_text));
                        $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                        $senderid = $sidobj->getName($dt->sender_id);
                        $vdlr = $dt->vendor_dlr;
                        $dmap = function ($e) use ($vdlr) {
                            return $e->vendor_dlr_code == $vdlr;
                        };
                        $dlrcobj = array_filter($dlrcodes, $dmap);
                        $k = key($dlrcobj);
                        if ($dlrcodes[$k]->description != '') {
                            $dlrdesc = $dlrcodes[$k]->description;
                        } else {
                            $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                            $dlrdesc = $dcdata['desc'];
                        }

                        //network and circle
                        if ($dt->dlr != '-1') {
                            //get this for valid numbers only
                            if (strlen($dt->mobile) == min(explode(",", $cvdata->valid_lengths))) {
                                //mobile is without country prefix
                                $pfx = substr($dt->mobile, 0, intval(4));
                                $pmap = function ($d) use ($pfx) {
                                    return $d->prefix == $pfx;
                                };
                                $pfxfilobj = array_filter($pfxdata, $pmap);
                                $k = key($pfxfilobj);
                                $network = trim(utf8_decode($pfxdata[$k]->operator));
                                $circle = trim(utf8_decode($pfxdata[$k]->circle));
                            } else {
                                //country code is also present
                                $pfx = substr($dt->mobile, strlen($cvdata->prefix) - 1, intval(4));
                                $pmap = function ($d) use ($pfx) {
                                    return $d->prefix == $pfx;
                                };
                                $pfxfilobj = array_filter($pfxdata, $pmap);
                                $k = key($pfxfilobj);
                                $network = trim(utf8_decode($pfxdata[$k]->operator));
                                $circle = trim(utf8_decode($pfxdata[$k]->circle));
                            }
                        }

                        $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                        $exstr .= $mobile . " \t";
                        $exstr .= $network . " \t";
                        $exstr .= $circle . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                        $exstr .= $senderid . " \t";
                        $exstr .= $stxtstr . " \t";
                        $exstr .= number_format($dt->sms_count) . " \t";
                        if ($udata->account_type == 1) $exstr .= number_format($dt->price, 4) . " \t";
                        $exstr .= '#' . $dt->id . " \t";
                        $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                        $exstr .= $dlrdesc . " \t";
                        $exstr .= "\n";
                    }
                    //output
                    $xlfile = 'REPORTS-' . $_SESSION['user']['loginid'] . '-' . time();
                    $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                    $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);
                    fclose($fh);

                    //zip file for easier download
                    $zip = new ZipArchive;
                    $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                    $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                    $zip->close();

                    //download
                    $file = $xlfileloc . '.zip';
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    unlink($file);
                    unlink($xlfileloc . '.xls');
                    exit;
                }
            } else {
                //get sms text from summary table
                $smstext = $sumdata->sms_text;

                $stxtstr = '';
                if ($smscat['main'] == 'text') {
                    $str = preg_replace("/\t/", "\\t", htmlspecialchars_decode($smstext));
                    $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                } elseif ($smscat['main'] == 'wap') {
                    $stdata = unserialize(base64_decode($smstext));
                    $stxtstr = 'TITLE: ' . $stdata['wap_title'] . '\n URL: ' . $stdata['wap_url'];
                } elseif ($smscat['main'] == 'vcard') {
                    $stdata = unserialize(base64_decode($smstext));
                    $stxtstr = 'NAME: ' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '\n JOB: ' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '\n TEL: ' . $stdata['vcard_tel'] . '\n EMAIL: ' . $stdata['vcard_email'];
                }


                if ($total > 50000) {
                    //split total no of sms into smaller chunks

                    $batches = array_chunk($cmpns, 50000);

                    //prepare zip
                    $filename = 'REPORTS-' . $_SESSION['user']['loginid'] . '-' . time();
                    $fileloc = Doo::conf()->global_export_dir . $filename;
                    $zip = new ZipArchive;
                    $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);

                    $part = 1;
                    foreach ($batches as $batch) {
                        $exstr = " Destination Mobile " . "\t";
                        $exstr .= "Network " . "\t";
                        $exstr .= "Circle " . "\t";
                        $exstr .= "Time " . "\t";
                        $exstr .= "Sender ID " . "\t";
                        $exstr .= "SMS Text " . "\t";
                        $exstr .= "SMS Count " . "\t";
                        if ($udata->account_type == 1) $exstr .= "Price (" . Doo::conf()->currency_name . ") " . "\t";
                        $exstr .= "Msg ID " . "\t";
                        $exstr .= "DLR Status " . "\t";
                        $exstr .= "Explanation " . "\t";

                        $exstr .= "\n";
                        //loop through each
                        foreach ($batch as $dt) {
                            $senderid = $sidobj->getName($dt->sender_id);
                            $vdlr = $dt->vendor_dlr;
                            $dmap = function ($e) use ($vdlr) {
                                return $e->vendor_dlr_code == $vdlr;
                            };
                            $dlrcobj = array_filter($dlrcodes, $dmap);
                            $k = key($dlrcobj);
                            if ($dlrcodes[$k]->description != '') {
                                $dlrdesc = $dlrcodes[$k]->description;
                            } else {
                                $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                                $dlrdesc = $dcdata['desc'];
                            }

                            //network and circle
                            if ($dt->dlr != '-1') {
                                //get this for valid numbers only
                                if (strlen($dt->mobile) == min(explode(",", $cvdata->valid_lengths))) {
                                    //mobile is without country prefix
                                    $pfx = substr($dt->mobile, 0, intval(4));
                                    $pmap = function ($d) use ($pfx) {
                                        return $d->prefix == $pfx;
                                    };
                                    $pfxfilobj = array_filter($pfxdata, $pmap);
                                    $k = key($pfxfilobj);
                                    $network = trim(utf8_decode($pfxdata[$k]->operator));
                                    $circle = trim(utf8_decode($pfxdata[$k]->circle));
                                } else {
                                    //country code is also present
                                    $pfx = substr($dt->mobile, strlen($cvdata->prefix) - 1, intval(4));
                                    $pmap = function ($d) use ($pfx) {
                                        return $d->prefix == $pfx;
                                    };
                                    $pfxfilobj = array_filter($pfxdata, $pmap);
                                    $k = key($pfxfilobj);
                                    $network = trim(utf8_decode($pfxdata[$k]->operator));
                                    $circle = trim(utf8_decode($pfxdata[$k]->circle));
                                }
                            }
                            $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                            $exstr .= $mobile . " \t";
                            $exstr .= $network . " \t";
                            $exstr .= $circle . " \t";
                            $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                            $exstr .= $senderid . " \t";
                            $exstr .= $stxtstr . " \t";
                            $exstr .= number_format($dt->sms_count) . " \t";
                            if ($udata->account_type == 1) $exstr .= number_format($dt->price, 4) . " \t";
                            $exstr .= '#' . $dt->id . " \t";
                            $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                            $exstr .= $dlrdesc . " \t";
                            $exstr .= "\n";
                        }

                        // write in excel file
                        $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                        fwrite($fh, $exstr);


                        //add to zip
                        $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                        $part++;
                        fclose($fh);
                    }

                    $zip->close();
                    //var_dump($zip);die;
                    //download
                    $file = $fileloc . '.zip';
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    unlink($file);
                    array_map('unlink', glob($fileloc . '*'));
                    exit;
                } else {
                    //single export file

                    $exstr = " Destination Mobile " . "\t";
                    $exstr .= "Network " . "\t";
                    $exstr .= "Circle " . "\t";
                    $exstr .= "Time " . "\t";
                    $exstr .= "Sender ID " . "\t";
                    $exstr .= "SMS Text " . "\t";
                    $exstr .= "SMS Count " . "\t";
                    if ($udata->account_type == 1) $exstr .= "Price (" . Doo::conf()->currency_name . ") " . "\t";
                    $exstr .= "Msg ID " . "\t";
                    $exstr .= "DLR Status " . "\t";
                    $exstr .= "Explanation " . "\t";

                    $exstr .= "\n";


                    foreach ($cmpns as $dt) {
                        $senderid = $sidobj->getName($dt->sender_id);
                        $vdlr = $dt->vendor_dlr;
                        $dmap = function ($e) use ($vdlr) {
                            return $e->vendor_dlr_code == $vdlr;
                        };
                        $dlrcobj = array_filter($dlrcodes, $dmap);
                        $k = key($dlrcobj);
                        if ($dlrcodes[$k]->description != '') {
                            $dlrdesc = $dlrcodes[$k]->description;
                        } else {
                            $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                            $dlrdesc = $dcdata['desc'];
                        }

                        //network and circle
                        if ($dt->dlr != '-1') {
                            //get this for valid numbers only
                            if (strlen($dt->mobile) == min(explode(",", $cvdata->valid_lengths))) {
                                //mobile is without country prefix
                                $pfx = substr($dt->mobile, 0, intval(4));
                                $pmap = function ($d) use ($pfx) {
                                    return $d->prefix == $pfx;
                                };
                                $pfxfilobj = array_filter($pfxdata, $pmap);
                                $k = key($pfxfilobj);
                                $network = trim(utf8_decode($pfxdata[$k]->operator));
                                $circle = trim(utf8_decode($pfxdata[$k]->circle));
                            } else {
                                //country code is also present
                                $pfx = substr($dt->mobile, strlen($cvdata->prefix) - 1, intval(4));
                                $pmap = function ($d) use ($pfx) {
                                    return $d->prefix == $pfx;
                                };
                                $pfxfilobj = array_filter($pfxdata, $pmap);
                                $k = key($pfxfilobj);
                                $network = trim(utf8_decode($pfxdata[$k]->operator));
                                $circle = trim(utf8_decode($pfxdata[$k]->circle));
                            }
                        }
                        $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));
                        $exstr .= $mobile . " \t";
                        $exstr .= $network . " \t";
                        $exstr .= $circle . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                        $exstr .= $senderid . " \t";
                        $exstr .= $stxtstr . " \t";
                        $exstr .= number_format($dt->sms_count) . " \t";
                        if ($udata->account_type == 1) $exstr .= number_format($dt->price, 4) . " \t";
                        $exstr .= '#' . $dt->id . " \t";
                        $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                        $exstr .= $dlrdesc . " \t";
                        $exstr .= "\n";
                    }
                    //output
                    $xlfile = 'REPORTS-' . $_SESSION['user']['loginid'] . '-' . time();
                    $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                    $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file Here" . $xlfileloc);
                    fwrite($fh, $exstr);
                    fclose($fh);

                    //zip file for easier download
                    $zip = new ZipArchive;
                    $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                    $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                    $zip->close();

                    //download
                    $file = $xlfileloc . '.zip';
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    unlink($file);
                    unlink($xlfileloc . '.xls');
                    exit;
                    //exit;
                }
            }
        }


        //click reports download
        if ($mode == 'clickTracking') {
            Doo::loadModel('ScSentSms');
            $obj = new ScSentSms;
            $obj->sms_shoot_id = $this->params['id'];
            $obj->url_visit_flag = 1;
            $cmpns = Doo::db()->find($obj);
            $total = sizeof($cmpns);

            $uid = $cmpns[0]->user_id;

            if ($_SESSION['user']['userid'] != $uid && $_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                $uobj = Doo::loadModel('ScUsers', true);
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }

            //check if permission is allowed
            $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
            $upbdbobj->user_id = $_SESSION['user']['userid'];
            $upbdata = Doo::db()->find($upbdbobj, array('limit' => 1));
            $clicktrack = $upbdata->id ? $upbdata->click_track : 0;
            if ($_SESSION['user']['group'] != 'admin' && $clicktrack == 0) {
                //not allowed
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'showDLR/' . $this->params['id'];
            }

            if ($total > 50000) {
                //split total no of sms into smaller chunks
                $batches = array_chunk($cmpns, 50000);

                //prepare zip
                $filename = 'CLICK-REPORTS-' . $_SESSION['user']['loginid'] . '-' . time();
                $fileloc = Doo::conf()->global_export_dir . $filename;
                $zip = new ZipArchive;
                $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);
                $part = 1;
                foreach ($batches as $batch) {
                    $exstr = " Mobile " . "\t";
                    $exstr .= "Click Time " . "\t";
                    $exstr .= "Browser " . "\t";
                    $exstr .= "Platform " . "\t";
                    $exstr .= "IP " . "\t";
                    $exstr .= "City " . "\t";
                    $exstr .= "Country " . "\t";

                    $exstr .= "\n";
                    //loop through each
                    foreach ($batch as $dt) {

                        $osdata = unserialize($dt->url_visit_platform);

                        $exstr .= $dt->mobile . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->url_visit_ts)) . " \t";
                        $exstr .= $osdata['browser'] . " \t";
                        $exstr .= $osdata['system'] . " \t";
                        $exstr .= $osdata['ip'] . " \t";
                        $exstr .= $osdata['city'] . " \t";
                        $exstr .= $osdata['country'] . " \t";
                        $exstr .= "\n";
                    }

                    // write in excel file
                    $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);
                    fclose($fh);

                    //add to zip
                    $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                    $part++;
                }

                $zip->close();

                //download
                $file = $fileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                array_map('unlink', glob($fileloc . '*'));
                exit;
            } else {
                //single export file

                $exstr = " Mobile " . "\t";
                $exstr .= "Click Time " . "\t";
                $exstr .= "Browser " . "\t";
                $exstr .= "Platform " . "\t";
                $exstr .= "IP " . "\t";
                $exstr .= "City " . "\t";
                $exstr .= "Country " . "\t";

                $exstr .= "\n";


                foreach ($cmpns as $dt) {


                    $osdata = unserialize($dt->url_visit_platform);

                    $exstr .= $dt->mobile . " \t";
                    $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->url_visit_ts)) . " \t";
                    $exstr .= $osdata['browser'] . " \t";
                    $exstr .= $osdata['system'] . " \t";
                    $exstr .= $osdata['ip'] . " \t";
                    $exstr .= $osdata['city'] . " \t";
                    $exstr .= $osdata['country'] . " \t";
                    $exstr .= "\n";
                }
                //output
                $xlfile = 'CLICK-REPORTS-' . $_SESSION['user']['loginid'] . '-' . time();
                $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                fwrite($fh, $exstr);
                fclose($fh);

                //zip file for easier download
                $zip = new ZipArchive;
                $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                $zip->close();

                //download
                $file = $xlfileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                unlink($xlfileloc . '.xls');
                exit;
            }
        }

        //download sms log
        if ($mode == 'smslog') {

            $sidobj = Doo::loadModel('ScSenderId', true);
            //get route custom dlr codes
            $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
            $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category'));

            $getstr = $this->params['id'];
            $vals = json_decode(urldecode($getstr));

            //echo '<pre>';var_dump($vals);die;

            $dr = $vals->filDate;
            if (trim($dr) != 'Select Date' && $dr != '' && $dr != NULL) {
                //split the dates
                $datr = explode("-", urldecode($dr));
                $from = date('Y-m-d', strtotime(trim($datr[0])));
                $to = date('Y-m-d', strtotime(trim($datr[1])));
                $sWhere = "sc_sent_sms.sending_time BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
            }

            //sender id
            if (intval($vals->senderId) > 0) {
                $sWhere = $sWhere == '' ? 'sc_sent_sms.sender_id =' . $vals->senderId : $sWhere . ' AND sc_sent_sms.sender_id=' . $vals->senderId;
            }

            //route id
            if (intval($vals->routeId) > 0) {
                $sWhere = trim($sWhere) == '' ? 'sc_sent_sms.route_id =' . $vals->routeId : $sWhere . ' AND sc_sent_sms.route_id=' . $vals->routeId;
            }


            if ($sWhere != '') {
                if (trim($vals->search) == '') {
                    $finalWhere = $sWhere;
                } else {
                    $finalWhere = $sWhere . " AND sc_sent_sms.mobile LIKE '%$vals->search%'";
                }
            } else {
                $finalWhere = "sc_sent_sms.mobile LIKE '%$vals->search%'";
            }

            $finalWhere = $finalWhere == '' ? 'sc_sent_sms.sms_shoot_id = sc_sms_summary.sms_shoot_id' : $finalWhere . ' AND sc_sent_sms.sms_shoot_id = sc_sms_summary.sms_shoot_id';

            //echo $finalWhere;die;

            $stobj = Doo::loadModel('ScSentSms', true);
            $stobj->user_id = $_SESSION['user']['userid'];
            $opt['filters'] = array();
            $opt['filters'][0]['model'] = 'ScSmsSummary';
            $opt['select'] = 'sc_sent_sms.sms_shoot_id as sms_shoot_id, sc_sent_sms.mobile as mobile, sc_sent_sms.sms_type as sms_type, sc_sent_sms.sms_text as dyn_text, sc_sms_summary.sms_text as org_text, sc_sms_summary.hide_mobile as hide_mobile, sc_sent_sms.id as msgid, sc_sent_sms.route_id as route_id, sc_sent_sms.sender_id as sender_id, sc_sent_sms.sms_count as sms_count, sc_sent_sms.sending_time as sending_time, sc_sent_sms.dlr as dlr, sc_sent_sms.vendor_dlr as vendor_dlr, sc_sent_sms.url_visit_flag as url_visit_flag';
            $opt['where'] = $finalWhere;

            $smsdata = Doo::db()->find($stobj, $opt);

            $total = sizeof($smsdata);

            $cvobj = Doo::loadModel('ScCoverage', true);
            $cvdata = Doo::db()->find($cvobj, array('select' => 'id, prefix, valid_lengths'));

            // $ncobj = Doo::loadModel('ScOcprMapping', true);
            $pfxdata = []; // Doo::db()->find($ncobj, array('select' => 'coverage, prefix, operator, circle'));

            $clicktrack = 1; //enable by default and later check
            if ($_SESSION['user']['group'] != 'admin') {
                //check the format of masking and if user is allowed to see at least the click reports
                $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
                $upbdbobj->user_id = $_SESSION['user']['userid'];
                $upbdata = Doo::db()->find($upbdbobj, array('limit' => 1));
                $maskdata = $upbdata->id ? unserialize($upbdata->mask_pattern) : array('type' => 0, 'mpos' => -5, 'mlen' => 4);
                $clicktrack = $upbdata->id ? $upbdata->click_track : 0;
            }

            if ($total > 50000) {
                //split into batches
                $batches = array_chunk($smsdata, 50000);

                //prepare zip
                $filename = 'SMSLOG-' . $_SESSION['user']['loginid'] . '-' . time();
                $fileloc = Doo::conf()->global_export_dir . $filename;
                $zip = new ZipArchive;
                $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);

                $part = 1;
                foreach ($batches as $batch) {
                    $exstr = " Destination Mobile " . "\t";
                    $exstr = " Network " . "\t";
                    $exstr = " Region " . "\t";
                    $exstr .= "Time " . "\t";
                    $exstr .= "Sender ID " . "\t";
                    $exstr .= "SMS Text " . "\t";
                    $exstr .= "SMS Count " . "\t";
                    $exstr .= "Msg ID " . "\t";
                    $exstr .= "DLR Status " . "\t";
                    $exstr .= "Explanation " . "\t";

                    $exstr .= "\n";
                    //loop through each
                    foreach ($batch as $dt) {
                        $senderid = $sidobj->getName($dt->sender_id);
                        $vdlr = $dt->vendor_dlr;
                        $dmap = function ($e) use ($vdlr) {
                            return $e->vendor_dlr_code == $vdlr;
                        };
                        $dlrcobj = array_filter($dlrcodes, $dmap);
                        $k = key($dlrcobj);
                        if ($dlrcodes[$k]->description != '') {
                            $dlrdesc = $dlrcodes[$k]->description;
                        } else {
                            $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                            $dlrdesc = $dcdata['desc'];
                        }

                        $pbflag = $_SESSION['user']['group'] == 'admin' ? 0 : $dt->hide_mobile;

                        $smscat = unserialize($dt->sms_type);
                        $stxtstr = '';
                        if ($smscat['personalize'] == '1') {
                            $str = preg_replace("/\t/", "\\t", html_entity_decode($dt->dyn_text));
                            $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                        } else {
                            if ($smscat['main'] == 'text') {
                                $str = preg_replace("/\t/", "\\t", html_entity_decode($dt->org_text));
                                $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                            } elseif ($smscat['main'] == 'wap') {
                                $stdata = unserialize(base64_decode($dt->org_text));
                                $stxtstr = 'TITLE: ' . $stdata['wap_title'] . '\n URL: ' . $stdata['wap_url'];
                            } elseif ($smscat['main'] == 'vcard') {
                                $stdata = unserialize(base64_decode($dt->org_text));
                                $stxtstr = 'NAME: ' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '\n JOB: ' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '\n TEL: ' . $stdata['vcard_tel'] . '\n EMAIL: ' . $stdata['vcard_email'];
                            }
                        }

                        //network and circle
                        if ($dt->dlr != '-1') {
                            //get this for valid numbers only
                            $covid = $_SESSION['credits']['routes'][$dt->route_id]['coverage'];
                            $cvmap = function ($f) use ($covid) {
                                return $f->id == $covid;
                            };
                            $cvfobj = array_filter($cvdata, $cvmap);
                            $j = key($cvfobj);

                            if (strlen($dt->mobile) == min(explode(",", $cvdata[$j]->valid_lengths))) {
                                //mobile is without country prefix
                                $pfx = substr($dt->mobile, 0, intval(4));
                                $pmap = function ($d) use ($pfx, $covid) {
                                    return $d->prefix == $pfx && $d->coverage == $covid;
                                };
                                $pfxfilobj = array_filter($pfxdata, $pmap);
                                $i = key($pfxfilobj);
                                $network = utf8_decode($pfxdata[$i]->operator);
                                $circle = utf8_decode($pfxdata[$i]->circle);
                            } else {
                                //country code is also present
                                $pfx = substr($dt->mobile, strlen($cvdata[$j]->prefix) - 1, intval(4));
                                $pmap = function ($d) use ($pfx, $covid) {
                                    return $d->prefix == $pfx && $d->coverage == $covid;
                                };
                                $pfxfilobj = array_filter($pfxdata, $pmap);
                                $i = key($pfxfilobj);
                                $network = utf8_decode($pfxdata[$i]->operator);
                                $circle = utf8_decode($pfxdata[$i]->circle);
                            }
                        }


                        $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                        $exstr .= $mobile . " \t";
                        $exstr .= $network . " \t";
                        $exstr .= $circle . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                        $exstr .= $senderid . " \t";
                        $exstr .= $stxtstr . " \t";
                        $exstr .= number_format($dt->sms_count) . " \t";
                        $exstr .= '#' . $dt->msgid . " \t";
                        $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                        $exstr .= $dlrdesc . " \t";
                        $exstr .= "\n";
                    }
                    // write in excel file
                    $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);


                    //add to zip
                    $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                    $part++;
                    fclose($fh);
                }
                $zip->close();

                $file = $fileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                array_map('unlink', glob($fileloc . '*'));
                exit;
            } else {
                //all download in one go
                $exstr = " Destination Mobile " . "\t";
                $exstr = " Network " . "\t";
                $exstr = " Region " . "\t";
                $exstr .= "Time " . "\t";
                $exstr .= "Sender ID " . "\t";
                $exstr .= "SMS Text " . "\t";
                $exstr .= "SMS Count " . "\t";
                $exstr .= "Msg ID " . "\t";
                $exstr .= "DLR Status " . "\t";
                $exstr .= "Explanation " . "\t";

                $exstr .= "\n";
                foreach ($smsdata as $dt) {
                    $senderid = $sidobj->getName($dt->sender_id);
                    $vdlr = $dt->vendor_dlr;
                    $dmap = function ($e) use ($vdlr) {
                        return $e->vendor_dlr_code == $vdlr;
                    };
                    $dlrcobj = array_filter($dlrcodes, $dmap);
                    $k = key($dlrcobj);
                    if ($dlrcodes[$k]->description != '') {
                        $dlrdesc = $dlrcodes[$k]->description;
                    } else {
                        $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                        $dlrdesc = $dcdata['desc'];
                    }

                    $pbflag = $_SESSION['user']['group'] == 'admin' ? 0 : $dt->hide_mobile;
                    $smscat = unserialize($dt->sms_type);
                    $stxtstr = '';
                    if ($smscat['personalize'] == '1') {
                        $str = preg_replace("/\t/", "\\t", html_entity_decode($dt->dyn_text));
                        $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                    } else {
                        if ($smscat['main'] == 'text') {
                            $str = preg_replace("/\t/", "\\t", html_entity_decode($dt->org_text));
                            $stxtstr = preg_replace("/\r?\n/", "\\n", $str);
                        } elseif ($smscat['main'] == 'wap') {
                            $stdata = unserialize(base64_decode($dt->org_text));
                            $stxtstr = 'TITLE: ' . $stdata['wap_title'] . '\n URL: ' . $stdata['wap_url'];
                        } elseif ($smscat['main'] == 'vcard') {
                            $stdata = unserialize(base64_decode($dt->org_text));
                            $stxtstr = 'NAME: ' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '\n JOB: ' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '\n TEL: ' . $stdata['vcard_tel'] . '\n EMAIL: ' . $stdata['vcard_email'];
                        }
                    }
                    //network and circle
                    //network and circle
                    if ($dt->dlr != '-1') {
                        //get this for valid numbers only
                        $covid = $_SESSION['credits']['routes'][$dt->route_id]['coverage'];
                        $cvmap = function ($f) use ($covid) {
                            return $f->id == $covid;
                        };
                        $cvfobj = array_filter($cvdata, $cvmap);
                        $j = key($cvfobj);

                        if (strlen($dt->mobile) == min(explode(",", $cvdata[$j]->valid_lengths))) {
                            //mobile is without country prefix
                            $pfx = substr($dt->mobile, 0, intval(4));
                            $pmap = function ($d) use ($pfx, $covid) {
                                return $d->prefix == $pfx && $d->coverage == $covid;
                            };
                            $pfxfilobj = array_filter($pfxdata, $pmap);
                            $i = key($pfxfilobj);
                            $network = utf8_decode($pfxdata[$i]->operator);
                            $circle = utf8_decode($pfxdata[$i]->circle);
                        } else {
                            //country code is also present
                            $pfx = substr($dt->mobile, strlen($cvdata[$j]->prefix) - 1, intval(4));
                            $pmap = function ($d) use ($pfx, $covid) {
                                return $d->prefix == $pfx && $d->coverage == $covid;
                            };
                            $pfxfilobj = array_filter($pfxdata, $pmap);
                            $i = key($pfxfilobj);
                            $network = utf8_decode($pfxdata[$i]->operator);
                            $circle = utf8_decode($pfxdata[$i]->circle);
                        }
                    }

                    $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                    $exstr .= $mobile . " \t";
                    $exstr .= $network . " \t";
                    $exstr .= $circle . " \t";
                    $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                    $exstr .= $senderid . " \t";
                    $exstr .= $stxtstr . " \t";
                    $exstr .= number_format($dt->sms_count) . " \t";
                    $exstr .= '#' . $dt->msgid . " \t";
                    $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                    $exstr .= $dlrdesc . " \t";
                    $exstr .= "\n";
                }

                $xlfile = 'SMSLOG-' . $_SESSION['user']['loginid'] . '-' . time();
                $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                fwrite($fh, $exstr);
                fclose($fh);

                //zip file for easier download
                $zip = new ZipArchive;
                $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                $zip->close();

                //download
                $file = $xlfileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                unlink($xlfileloc . '.xls');
                exit;
            }
        }

        //download optin
        if ($mode == 'optin') {
            $cid = intval($this->params['id']);
            //get campaign name
            $cobj = Doo::loadModel('ScUsersCampaigns', true);
            $cobj->id = $cid;
            $cobj->user_id = $_SESSION['user']['userid'];
            $cdata = Doo::db()->find($cobj, array('limit' => 1, 'select' => 'campaign_name'));

            $obj = Doo::loadModel('ScUsersCampaignsOptins', true);
            $obj->campaign_id = $cid;
            $obj->user_id = $_SESSION['user']['userid'];
            $data = Doo::db()->find($obj, array('select' => 'mobile, keyword_matched, date_added'));
            $total = sizeof($data);


            if ($total > 50000) {
                //split into multiple files
                $batches = array_chunk($data, 50000);

                //prepare zip
                $filename = $cdata->campaign_name . '-OPTIN-' . $_SESSION['user']['loginid'] . '-' . time();
                $fileloc = Doo::conf()->global_export_dir . $filename;
                $zip = new ZipArchive;
                $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);
                $part = 1;
                foreach ($batches as $batch) {
                    $exstr = " Mobile " . "\t";
                    $exstr .= "Added On " . "\t";
                    $exstr .= "Keyword Matched " . "\t";
                    $exstr .= "\n";
                    //loop through each
                    foreach ($batch as $dt) {

                        $exstr .= $dt->mobile . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->date_added)) . " \t";
                        $exstr .= $dt->keyword_matched . " \t";
                        $exstr .= "\n";
                    }

                    // write in excel file
                    $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);
                    fclose($fh);

                    //add to zip
                    $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                    $part++;
                }

                $zip->close();

                //download
                $file = $fileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                array_map('unlink', glob($fileloc . '*'));
                exit;
            } else {
                //single file
                $exstr = " Mobile " . "\t";
                $exstr .= "Added On " . "\t";
                $exstr .= "Keyword Matched " . "\t";
                $exstr .= "\n";
                //loop through each
                foreach ($data as $dt) {

                    $exstr .= $dt->mobile . " \t";
                    $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->date_added)) . " \t";
                    $exstr .= $dt->keyword_matched . " \t";
                    $exstr .= "\n";
                }
                //output
                $xlfile = $cdata->campaign_name . '-OPTIN-' . $_SESSION['user']['loginid'] . '-' . time();
                $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                fwrite($fh, $exstr);
                fclose($fh);

                //zip file for easier download
                $zip = new ZipArchive;
                $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                $zip->close();

                //download
                $file = $xlfileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                unlink($xlfileloc . '.xls');
                exit;
            }
        }


        //download optout
        if ($mode == 'optout') {
            $cid = intval($this->params['id']);
            //get campaign name
            $cobj = Doo::loadModel('ScUsersCampaigns', true);
            $cobj->id = $cid;
            $cobj->user_id = $_SESSION['user']['userid'];
            $cdata = Doo::db()->find($cobj, array('limit' => 1, 'select' => 'campaign_name'));

            $obj = Doo::loadModel('ScUsersCampaignsOptouts', true);
            $obj->campaign_id = $cid;
            $obj->user_id = $_SESSION['user']['userid'];
            $data = Doo::db()->find($obj, array('select' => 'mobile, keyword_matched, date_added'));
            $total = sizeof($data);


            if ($total > 50000) {
                //split into multiple files
                $batches = array_chunk($data, 50000);

                //prepare zip
                $filename = $cdata->campaign_name . '-OPTOUT-' . $_SESSION['user']['loginid'] . '-' . time();
                $fileloc = Doo::conf()->global_export_dir . $filename;
                $zip = new ZipArchive;
                $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);
                $part = 1;
                foreach ($batches as $batch) {
                    $exstr = " Mobile " . "\t";
                    $exstr .= "Added On " . "\t";
                    $exstr .= "Keyword Matched " . "\t";
                    $exstr .= "\n";
                    //loop through each
                    foreach ($batch as $dt) {

                        $exstr .= $dt->mobile . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->date_added)) . " \t";
                        $exstr .= $dt->keyword_matched . " \t";
                        $exstr .= "\n";
                    }

                    // write in excel file
                    $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);
                    fclose($fh);

                    //add to zip
                    $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                    $part++;
                }

                $zip->close();

                //download
                $file = $fileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                array_map('unlink', glob($fileloc . '*'));
                exit;
            } else {
                //single file
                $exstr = " Mobile " . "\t";
                $exstr .= "Added On " . "\t";
                $exstr .= "Keyword Matched " . "\t";
                $exstr .= "\n";
                //loop through each
                foreach ($data as $dt) {

                    $exstr .= $dt->mobile . " \t";
                    $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->date_added)) . " \t";
                    $exstr .= $dt->keyword_matched . " \t";
                    $exstr .= "\n";
                }
                //output
                $xlfile = $cdata->campaign_name . '-OPTOUT-' . $_SESSION['user']['loginid'] . '-' . time();
                $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                fwrite($fh, $exstr);
                fclose($fh);

                //zip file for easier download
                $zip = new ZipArchive;
                $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                $zip->close();

                //download
                $file = $xlfileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                unlink($xlfileloc . '.xls');
                exit;
            }
        }


        //download smpp sms
        if ($mode == 'smppsms') {
            $dobj = json_decode(base64_decode($this->params['id']));
            $clientid = $dobj->clientid;
            $clobj = Doo::loadModel('ScSmppClients', true);
            $clobj->id = $clientid;
            $cldata = Doo::db()->find($clobj, array('limit' => 1));
            if (!$cldata->system_id) {
                //invalid client
                return array('/denied', 'internal');
            }
            if ($_SESSION['user']['group'] != 'admin' && $_SESSION['user']['userid'] != $cldata->user_id) {
                //invalid access
                return array('/denied', 'internal');
            }

            //fetch all sent sms based on the supplied criteria
            if (trim(urldecode($dobj->daterange)) != 'Select Date' && $dobj->daterange != '' && $dobj->daterange != null) {
                //split the dates
                $datr = explode("-", urldecode($dobj->daterange));
                $from = date('Y-m-d', strtotime(trim($datr[0])));
                $to = date('Y-m-d', strtotime(trim($datr[1])));
                $sWhere = "`sending_time` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
            } else {
                $sWhere = '';
            }

            $usrobj = Doo::loadModel('ScUsers', true);
            $uinfo = $usrobj->getProfileInfo($cldata->user_id);


            $obj = Doo::loadModel('ScSmppClientSms', true);
            if ($_SESSION['user']['group'] != 'admin') {
                $obj->user_id = $_SESSION['user']['userid'];
            }
            $obj->smpp_client = $cldata->system_id;
            $smsdata = Doo::db()->find($obj, array('where' => $sWhere));
            $total = sizeof($smsdata);
            // //get dlr codes
            // $dcobj = Doo::loadModel('ScRoutesCustomDlrCodes', true);
            // $dcobj->route_id = $smsdata[0]->route_id;
            // $dlrcodes = Doo::db()->find($dcobj,array('select'=>'dlr_code, description, category'));

            //get network and circle data
            $rtobj = Doo::LoadModel('ScSmsRoutes', true);
            $rtobj->id = $smsdata[0]->route_id;
            $rdata = Doo::db()->find($rtobj, array('select' => 'title, country_id, smpp_list', 'limit' => 1));

            //get route custom dlr codes
            if ($rdata->smpp_list != '') {
                $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
                $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category', 'where' => 'smpp_id IN (' . $rdata->smpp_list . ')'));
            } else {
                $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
                $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category'));
            }

            $mccmncqry = "SELECT mccmnc, CONCAT(brand, '||', operator, '||', country_name) as brandop FROM `sc_mcc_mnc_list`";
            $mccmncdata = Doo::db()->fetchAll($mccmncqry, null, PDO::FETCH_KEY_PAIR);
            //get the data from db
            if ($total > 50000) {
                //split into batches
                $batches = array_chunk($smsdata, 50000);

                //prepare zip
                $filename = 'SMPPSMS-' . $cldata->system_id . '-' . time();
                $fileloc = Doo::conf()->global_export_dir . $filename;
                $zip = new ZipArchive;
                $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);

                $part = 1;
                foreach ($batches as $batch) {
                    $exstr = " Destination Mobile " . "\t";
                    $exstr .= "Time " . "\t";
                    $exstr .= "Sender ID " . "\t";
                    $exstr .= "Country " . "\t";
                    $exstr .= "Network " . "\t";
                    $exstr .= "MCCMNC " . "\t";
                    if ($uinfo->account_type == 0) $exstr .= "Route " . "\t";
                    $exstr .= "SMS Text " . "\t";
                    $exstr .= "SMS Count " . "\t";
                    if ($uinfo->account_type == 1) $exstr .= "Price (" . Doo::conf()->currency_name . ") " . "\t";
                    $exstr .= "Msg ID " . "\t";
                    $exstr .= "DLR Status " . "\t";
                    $exstr .= "Explanation " . "\t";

                    $exstr .= "\n";
                    //loop through each
                    foreach ($batch as $dt) {

                        $vdlr = $dt->vendor_dlr;
                        $dmap = function ($e) use ($vdlr) {
                            return $e->dlr_code == $vdlr;
                        };
                        $dlrcobj = array_filter($dlrcodes, $dmap);
                        $k = key($dlrcobj);
                        if ($vdlr != '' && $dlrcodes[$k]->description != '') {
                            $dlrdesc = $dlrcodes[$k]->description;
                        } else {
                            $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                            $dlrdesc = $dcdata['desc'];
                        }

                        //network and circle
                        if ($dt->dlr != '-1') {
                            //get this for valid numbers only
                            $brand = "";
                            $operator = "";
                            if ($dt->mccmnc != 0) {
                                $brandstr = $mccmncdata[$dt->mccmnc];
                                $brandar = explode("||", $brandstr);
                                $brand = $brandar[0];
                                $operator = $brandar[1];
                                $country = $brandar[2];
                            }
                        }

                        $smscat = json_decode($dt->sms_type);

                        $str = preg_replace("/\t/", "\\t", html_entity_decode($dt->sms_text));
                        $stxtstr = preg_replace("/\r?\n/", "\\n", $str);

                        $exstr .= $dt->mobile . " \t";
                        $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                        $exstr .= $dt->sender_id . " \t";
                        $exstr .= $country . " \t";
                        $exstr .= $brand . " - " . $operator . " \t";
                        $exstr .= $dt->mccmnc . " \t";
                        if ($uinfo->account_type == 0) $exstr .= $rdata->title . " \t";
                        $exstr .= $stxtstr . " \t";
                        $exstr .= number_format($dt->sms_count) . " \t";
                        if ($uinfo->account_type == 1) $exstr .= $dt->price . " \t";
                        $exstr .= $dt->smpp_smsid . " \t";
                        $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                        $exstr .= $dlrdesc . " \t";
                        $exstr .= "\n";
                    }
                    // write in excel file
                    $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                    fwrite($fh, $exstr);


                    //add to zip
                    $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                    $part++;
                    fclose($fh);
                }
                $zip->close();

                $file = $fileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                array_map('unlink', glob($fileloc . '*'));
                exit;
            } else {
                //all download in one go
                $exstr = " Destination Mobile " . "\t";
                $exstr .= "Time " . "\t";
                $exstr .= "Sender ID " . "\t";
                $exstr .= "Country " . "\t";
                $exstr .= "Network " . "\t";
                $exstr .= "MCCMNC " . "\t";
                if ($uinfo->account_type == 0) $exstr .= "Route " . "\t";
                $exstr .= "SMS Text " . "\t";
                $exstr .= "SMS Count " . "\t";
                if ($uinfo->account_type == 1) $exstr .= "Price (" . Doo::conf()->currency_name . ") " . "\t";
                $exstr .= "Msg ID " . "\t";
                $exstr .= "DLR Status " . "\t";
                $exstr .= "Explanation " . "\t";

                $exstr .= "\n";
                foreach ($smsdata as $dt) {
                    $vdlr = $dt->vendor_dlr;
                    $dmap = function ($e) use ($vdlr) {
                        return $e->dlr_code == $vdlr;
                    };
                    $dlrcobj = array_filter($dlrcodes, $dmap);
                    $k = key($dlrcobj);
                    if ($vdlr != '' && $dlrcodes[$k]->description != '') {
                        $dlrdesc = $dlrcodes[$k]->description;
                    } else {
                        $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                        $dlrdesc = $dcdata['desc'];
                    }
                    //network and circle
                    if ($dt->dlr != '-1') {
                        //get this for valid numbers only
                        $brand = "";
                        $operator = "";
                        if ($dt->mccmnc != 0) {
                            $brandstr = $mccmncdata[$dt->mccmnc];
                            $brandar = explode("||", $brandstr);
                            $brand = $brandar[0];
                            $operator = $brandar[1];
                            $country = $brandar[2];
                        }
                    }


                    $smscat = json_decode($dt->sms_type);

                    $str = preg_replace("/\t/", "\\t", html_entity_decode($dt->sms_text));
                    $stxtstr = preg_replace("/\r?\n/", "\\n", $str);

                    $exstr .= $dt->mobile . " \t";
                    $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt->sending_time)) . " \t";
                    $exstr .= $dt->sender_id . " \t";
                    $exstr .= $country . " \t";
                    $exstr .= $brand . " - " . $operator . " \t";
                    $exstr .= $dt->mccmnc . " \t";
                    if ($uinfo->account_type == 0) $exstr .= $rdata->title . " \t";
                    $exstr .= $stxtstr . " \t";
                    $exstr .= number_format($dt->sms_count) . " \t";
                    if ($uinfo->account_type == 1) $exstr .= $dt->price . " \t";
                    $exstr .= $dt->smpp_smsid . " \t";
                    $exstr .= $this->getDlrDescription($dt->dlr) . " \t";
                    $exstr .= $dlrdesc . " \t";
                    $exstr .= "\n";
                }

                $xlfile = 'SMPPSMS-' . $cldata->system_id . '-' . time();
                $xlfileloc = Doo::conf()->global_export_dir . $xlfile;

                $fh = fopen($xlfileloc . '.xls', 'w') or die("Can't open file");
                fwrite($fh, $exstr);
                fclose($fh);

                //zip file for easier download
                $zip = new ZipArchive;
                $result_zip = $zip->open($xlfileloc . '.zip', ZipArchive::CREATE);
                $zip->addFile($xlfileloc . '.xls', $xlfileloc . '.xls');
                $zip->close();

                //download
                $file = $xlfileloc . '.zip';
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file);
                unlink($xlfileloc . '.xls');
                exit;
            }
        }

        //download mcc mnc database
        if ($mode == 'mccmnclist') {
            //id is what needs to be downloaded, 0 = only countries, 1 = operator brands, 2 = all active mccmnc, 3 = all mccmnc
            $csvdata = array();
            if ($this->params['id'] == 0) {
                $columns = ['Country', 'Prefix', 'Cost Price (' . Doo::conf()->currency . ')'];
                $dtobj = Doo::loadModel('ScCoverage', true);
                $cvdata = Doo::db()->find($dtobj, array('where' => 'id > 1 AND status = 1', 'select' => 'country_code, country, prefix'));
                foreach ($cvdata as $dt) {
                    $ar = [$dt->country . ' (' . $dt->country_code . ')', $dt->prefix, '0'];
                    array_push($csvdata, $ar);
                }
                DooSmppcubeHelper::exportAsCsv(array('columns' => $columns, 'rows' => $csvdata), 'cost_price_by_countries.csv');
            }
            if ($this->params['id'] == 1) {
                $columns = ['Operator', 'Country', 'Prefix', 'Cost Price (' . Doo::conf()->currency . ')'];
                $dtobj = Doo::loadModel('ScMccMncList', true);
                $cvdata = Doo::db()->find($dtobj, array('where' => 'status = 1', 'select' => 'brand, operator, country_name, country_iso, country_code', 'groupby' => 'brand, country_iso', 'asc' => 'country_name'));
                foreach ($cvdata as $dt) {
                    $op = $dt->brand == "" ? $dt->operator : $dt->brand;
                    if ($op != "") {
                        $ar = [$op, $dt->country_name . ' (' . $dt->country_iso . ')', $dt->country_code, '0'];
                        array_push($csvdata, $ar);
                    }
                }
                DooSmppcubeHelper::exportAsCsv(array('columns' => $columns, 'rows' => $csvdata), 'cost_price_by_operators.csv');
            }
            if ($this->params['id'] == 2) {
                $columns = ['Country', 'Prefix', 'Brand', 'Operator', 'MCCMNC', 'Cost Price (' . Doo::conf()->currency . ')'];
                $dtobj = Doo::loadModel('ScMccMncList', true);
                $cvdata = Doo::db()->find($dtobj, array('where' => 'status = 1', 'select' => 'brand, operator, country_name, country_iso, country_code, mccmnc', 'asc' => 'country_name'));
                foreach ($cvdata as $dt) {
                    $ar = [$dt->country_name . ' (' . $dt->country_iso . ')', $dt->country_code, $dt->brand, $dt->operator, $dt->mccmnc, '0'];
                    array_push($csvdata, $ar);
                }
                DooSmppcubeHelper::exportAsCsv(array('columns' => $columns, 'rows' => $csvdata), 'cost_price_by_mccmnc.csv');
            }
            exit;
        }
    }

    public function getSheetnColumns()
    {
        $this->isLogin();
        Doo::loadHelper('DooFile');
        $fhobj = new DooFile;
        $mode = $_REQUEST['mode'];
        $sheet_to_parse = !$_REQUEST['sheet'] ? 'all' : $_REQUEST['sheet'];

        $filepath = Doo::conf()->global_upload_dir . $_REQUEST['file'];
        $ext = $fhobj->getFileExtensionFromPath($filepath, true);
        if ($ext == 'xlsx') {
            $mobile_column = !$_REQUEST['col'] ? 0 : ord(strtoupper($_REQUEST['col'])) - ord('A'); //this gets index of alphabet
            Doo::loadHelper('DooSpoutExcel');
            echo DooSpoutExcel::getSheetsColumns($filepath, 'xlsx', $sheet_to_parse, $mobile_column);
            exit;
        } elseif ($ext == 'xls') {
            $mobile_column = !$_REQUEST['col'] ? 'A' : $_REQUEST['col']; //here we need the letter
            echo DooSmppcubeHelper::readXls($filepath, $mode, $sheet_to_parse, $mobile_column);
            exit;
        } elseif ($ext == 'csv') {
            $fh = fopen($filepath, 'rb');
            $lines = count(file($filepath));
            $fdata = fgetcsv($fh, 0, "\r\n");
            $csvar = explode(",", $fdata[0]);
            $columns['A'] = $csvar[0];
            if ($csvar[1] != '') $columns['B'] = $csvar[1];
            if ($csvar[2] != '') $columns['C'] = $csvar[2];
            if ($csvar[3] != '') $columns['D'] = $csvar[3];
            if ($csvar[4] != '') $columns['E'] = $csvar[4];
            if ($csvar[5] != '') $columns['F'] = $csvar[5];
            if ($csvar[6] != '') $columns['G'] = $csvar[6];
            if ($csvar[7] != '') $columns['H'] = $csvar[7];
            if ($csvar[8] != '') $columns['I'] = $csvar[8];
            fclose($fh);
            $output['cols'] = $columns;
            $output['totalrows'] = $lines;
            echo json_encode($output);
            exit;
        } else {
            //unknown file format
        }
    }

    public function getRecentTransactions()
    {
        $this->isLogin();

        if (intval($_GET['uid']) == 0) {
            $uobj = Doo::loadModel('ScUsers', true);
            $uid = $_SESSION['user']['userid'];
            $userinfo = $uobj->getProfileInfo($uid, 'account_type, upline_id');
        } else {
            $uobj = Doo::loadModel('ScUsers', true);
            $uid = intval($_GET['uid']);
            $userinfo = $uobj->getProfileInfo($uid, 'account_type, upline_id');
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller

                if ($_SESSION['user']['userid'] != $userinfo->upline_id) {
                    return array('/denied', 'internal');
                }
            }
        }


        if ($userinfo->account_type == '1') {
            //currency based account
            $wobj = Doo::loadModel('ScUsersWallet', true);
            $wobj->user_id = $uid;
            $walletid = Doo::db()->find($wobj, array('limit' => 1, 'select' => 'id'))->id;

            $obj = Doo::loadModel('ScUsersWalletTransactions', true);
            $data = $obj->getOrdersByDate(NULL, 5, $walletid, 'all');
            $str = '';
            foreach ($data as $dt) {
                $str .= '<div class="media-group-item">
                                            <div class="m-t-xs col-md-6 col-sm-6 col-xs-6">';
                if ($dt->transac_type == '0') {
                    $str .= '<span class="label label-danger label-md"> - ' . Doo::conf()->currency . number_format(0 - floatval($dt->amount)) . '</span>';
                } else {
                    $str .= '<span class="label label-success label-md"> + ' . Doo::conf()->currency . number_format($dt->amount) . '</span>';
                }

                $str .= '
                                            </div>

                                            <div class="text-right col-md-6 col-sm-6 col-xs-6">
                                                <h5 class="m-t-0 label label-info">' . ($dt->transac_type == '1' ? 'Wallet Credit' : 'Debit Wallet') . '</h5><p style="font-size: 12px;margin-top:3px;">on ' . date('dS M Y', strtotime($dt->t_date)) . '</p>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>';
            }
        } else {
            //credit based account
            Doo::loadModel('ScUsersCreditTransactions');
            $obj = new ScUsersCreditTransactions;
            $data = $obj->getOrdersByDate(NULL, 5, $uid, 'to', 'all');
            $str = '';

            $robj = Doo::loadModel('ScSmsRoutes', true);
            //prepare the data
            foreach ($data as $dt) {
                $str .= '<div class="media-group-item">
                                            <div class="m-t-xs col-md-6 col-sm-6 col-xs-6">';
                if ($dt->type == '0') {
                    $str .= '<span class="label label-danger label-md"> - ' . number_format($dt->credits) . '</span>';
                } else {
                    $str .= '<span class="label label-success label-md"> + ' . number_format($dt->credits) . '</span>';
                }

                $str .= '
                                            </div>

                                            <div class="text-right col-md-6 col-sm-6 col-xs-6">
                                                <h5 class="m-t-0 label label-info">' . $robj->getRouteData($dt->route_id, 'title')->title . '</h5><p style="font-size: 12px;margin-top:3px;">on ' . date('dS M Y', strtotime($dt->transac_date)) . '</p>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>';
            }
        }

        //prepare response
        $res['str'] = $str;
        echo json_encode($res);
        exit;
    }

    public function getRecentCampaigns()
    {
        $this->isLogin();

        if (intval($_GET['uid']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($_GET['uid']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                $uobj = Doo::loadModel('ScUsers', true);
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
        }

        Doo::loadModel('ScSmsSummary');
        $obj = new ScSmsSummary;
        $obj->user_id = $uid;
        $obj->status = 0;
        $data = Doo::db()->find($obj, array('select' => 'sms_type, sms_text, submission_time, total_contacts', 'limit' => 4, 'desc' => 'id'));
        $str = '';

        //prepare the data
        foreach ($data as $dt) {

            $smscat = json_decode($dt->sms_type, true);
            $stxtstr = '';
            if ($smscat['main'] == 'text') {
                $stxtstr = '<div class="smstxt-ctr panel panel-info panel-custom col-md-6 col-sm-6 col-xs-6">' . htmlspecialchars_decode($dt->sms_text) . '</div>';
            } elseif ($smscat['main'] == 'wap') {
                $stdata = json_decode(base64_decode($dt->sms_text), true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info col-md-6 col-sm-6 col-xs-6">
                                                         <h5>' . $stdata['wap_title'] . '</h5>
                                                    <hr class="m-h-xs">

                                                     <span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span>

                                                    </div>';
            } elseif ($smscat['main'] == 'vcard') {
                $stdata = json_decode(base64_decode($dt->sms_text), true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info col-md-6 col-sm-6 col-xs-6">
                                                         <span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span>
                                                    </div>';
            }

            $str .= '<div class="media-group-item">
                                        ' . $stxtstr . '

                                        <div class="text-right col-md-6 col-sm-6 col-xs-6">
                                            <h5 class="m-t-0 label label-info">' . number_format($dt->total_contacts) . '</h5><p style="font-size: 12px;margin-top:3px;">on ' . date('dS M Y', strtotime($dt->submission_time)) . '</p>

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>';
        }


        //prepare response
        $res['str'] = $str == '' ? '<div class="text-center m-b-md"> ' . $this->SCTEXT('No Campaigns to show') . ' </div>' : $str;

        echo json_encode($res);
        exit;
    }

	public function reloadCreditData()
    {
        $this->isLogin();
        sleep(1);
        //get user wallet and credit information
        if ($_SESSION['user']['group'] != 'admin') {
            $credit_data = array();
            //load wallet credits
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $wlobj->user_id = $_SESSION['user']['userid'];
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
            //load data only for reseller and clients, admin and staff have unlimited credits
            if ($_SESSION['user']['account_type'] == "0" || $_SESSION['user']['account_type'] == "2") {
                $robj = Doo::loadModel('ScSmsRoutes', true);
                $creobj = Doo::loadModel('ScUsersCreditData', true);
                $creobj->user_id = $_SESSION['user']['userid'];
                $cdata = Doo::db()->find($creobj, array('select' => 'route_id, credits, price, validity, delv_per', 'where' => 'status=0'));
                $credit_data['routes'] = array();
                foreach ($cdata as $cre) {
                    $rdata = $robj->getRouteData($cre->route_id, 'title,credit_rule,country_id,active_time,template_flag,max_sid_len,def_sender,sender_type,tlv_ids,optout_config');
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
                }
            } else {
                //load mcc mnc assigned plan
                $uplobj = Doo::loadModel('ScUsersSmsPlans', true);
                $uplobj->user_id = $_SESSION['user']['userid'];
                $uplobj->plan_type = 1;
                $userplan = Doo::db()->find($uplobj, array('limit' => 1, 'select' => 'plan_id, subopt_idn'));
                $plan_id = $userplan->plan_id;

                $plobj = Doo::loadModel('ScMccMncPlans', true);
                $plobj->id = $plan_id;
                $plandata = Doo::db()->find($plobj, array('limit' => 1));

                $routeid = $plandata->route_id;
                $robj = Doo::loadModel('ScSmsRoutes', true);
                $rdata = $robj->getRouteData($routeid, 'title,credit_rule,country_id,active_time,template_flag,max_sid_len,def_sender,sender_type,tlv_ids,optout_config');
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
                $crear['tlv_ids'] = $rdata->tlv_ids;
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
            //admin account, hence reload all routes in session
            $credit_data = array();
            //load wallet credits

            //no wallet exist
            $credit_data['wallet']['id'] = '0';
            $credit_data['wallet']['code'] = '0';
            $credit_data['wallet']['amount'] = '0';

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
        //session_write_close();
        //echo json_encode($credit_data);
        echo 'DONE';
        exit;
    }


    //2. WATCHMAN FUNCTION

    //a. watchman_master
    public function watchmanProcessMonitor()
    {
        //perform end of day tasks

        //0. Subscription plan reset and credit management
        $upobj = Doo::loadModel('ScUsersSmsPlans', true);
        $pobj = Doo::loadModel('ScSmsPlanOptions', true);

        $uplist = Doo::db()->find($upobj, array('where' => "subopt_idn <> ''"));

        foreach ($uplist as $up) {
            $userid = $up->user_id;
            $planid = $up->plan_id;
            $subopt = $up->subopt_idn;
            //2. Get the routes and credits for each subplan
            $planopts = Doo::db()->find($pobj, array('select' => 'opt_data', 'limit' => 1, 'where' => "plan_id = $planid AND subopt_idn = '$subopt'"));
            $planopts = unserialize($planopts->opt_data);
            foreach ($planopts['route_credits'] as $routeid => $credits) {
                //3. For each user edit the credits and get old credits
                if (intval($routeid) > 0) {
                    //fetch users old credits
                    $fqry = "SELECT id, credits FROM sc_users_credit_data WHERE user_id = $userid AND route_id = $routeid LIMIT 1";
                    $ucdata = Doo::db()->fetchRow($fqry);
                    $oldCredits = $ucdata['credits'];
                    $ucrowid = $ucdata['id'];
                    //update new credits
                    $uqry = "UPDATE sc_users_credit_data SET credits = $credits WHERE id = $ucrowid LIMIT 1";
                    Doo::db()->query($uqry);
                    echo "Updated credits for user $userid with new: $credits and old: $oldCredits for route $routeid<br>";
                    //4. Add log entry with comments "CREDIT RESET" & "Credits added as subscribed. Old credits were expired."
                    $clobj = Doo::loadModel('ScLogsCredits', true);
                    $clobj->user_id = $userid;
                    $clobj->amount = $credits;
                    $clobj->route_id = $routeid;
                    $clobj->credits_before = $oldCredits;
                    $clobj->credits_after = $credits;
                    $clobj->reference = "CREDIT RESET";
                    $clobj->comments = "Credits added as subscribed. Old credits were expired.";
                    Doo::db()->insert($clobj);
                    echo "log entry made.. <br>";
                }
            }
        }

        //1. Send email with credits and campaign summary to clients
        //2. Change status of ACK sms to Delivered/Undelivered/Expired based on ratios set

        if (date('H:i') == Doo::conf()->eod_alert_time) {
            //time for some action

            //1. Put daily reports emails in queue
            $today = date('Y-m-d');
            $webobj = Doo::loadModel('ScWebsites', true);
            $usetobj = Doo::loadModel('ScUsersSettings', true);
            $vusrobj = Doo::loadModel('ScUsers', true);
            $opt['select'] = 'name, category, user_id as uid, email, upline_id, account_type';
            $opt['where'] = "email_verified = 1 AND email <> '' AND status = 1";

            $vusers = Doo::db()->find($vusrobj, $opt);

            if (sizeof($vusers) > 0) {
                //eligible users found. Draft emails for each user
                $insertData = array();
                foreach ($vusers as $usr) {
                    //check if settings allow
                    $setflag = intval($usetobj->getSettingValue($usr->uid, 'email_daily_sms')->email_daily_sms);
                    $lowsetflag = intval($usetobj->getSettingValue($usr->uid, 'email_daily_credits')->email_daily_credits);
                    if ($setflag == 1) {
                        //get company info for upline
                        $webdata = Doo::db()->find($webobj, array('limit' => 1, 'select' => 'domains,logo,site_data', 'where' => 'user_id=' . $usr->upline_id));
                        $cdata = unserialize($webdata->site_data);


                        if ($cdata != false && $cdata['helpmail'] != '') {
                            //get the total values for the user from ES
                            $userdata = array("user" => $usr->uid, "group" => $usr->category, "mode" => 'mailstats');
                            $soptions = array(
                                'http' => array(
                                    'header'  => "Content-type: application/json; charset=UTF-8\r\n",
                                    'method'  => 'POST',
                                    'content' => json_encode($userdata)
                                ),
                                "ssl" => array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                                )
                            );
                            $context  = stream_context_create($soptions);
                            $mailstats = json_decode(file_get_contents(str_replace("auth", "search", Doo::conf()->search_api_auth_url) . 'mailstats', false, $context));
                            //$mailstats = file_get_contents('http://host.docker.internal:5305/search/'.'mailstats', false, $context);
                            //echo '<pre>'; var_dump($mailstats);die;
                            //draft email as site settings are not empty
                            $mainDomain = explode(",", $webdata->domains)[0];
                            $logo = 'https://' . $mainDomain . '/' . Doo::conf()->image_upload_url . 'logos/' . $webdata->logo;

                            $data['company_url'] = 'https://' . $mainDomain . '/';
                            $data['company_name'] = $cdata['company_name'];
                            $data['logo'] = $logo;
                            $data['name'] = $usr->name;
                            $data['helpline'] = $cdata['helpline'];
                            $data['company_domain'] = $mainDomain;

                            $data['total_sms'] = $mailstats->statsData->total;
                            $data['total_del'] = $mailstats->statsData->delivered;
                            $data['total_fail'] = $mailstats->statsData->failed;
                            $data['credits_used'] = $mailstats->statsData->credits;
                            $data['credits_refunded'] = $mailstats->statsData->refunds;

                            $mailbody = $this->view()->getRendered("mail/dailyReports", $data);

                            $maildata = array();
                            $maildata['sender_email'] = $cdata['helpmail'];
                            $maildata['sender_name'] = $cdata['company_name'];
                            $maildata['recipient_list'] = serialize(array($usr->email));
                            $maildata['email_sub'] = 'Daily Account Summary | Campaigns and Credits';
                            $maildata['email_text'] = base64_encode($mailbody);

                            array_push($insertData, $maildata);
                        }
                    }
                    if ($lowsetflag == 1) {
                        //check credit balance if it is below the threshold
                        $billing_type = $usr->account_type == 0 ? 'credit' : 'currency';
                        if ($billing_type == 'credit') {
                            //check balance for each assigned route
                            $creqry = 'SELECT c.credits, r.title FROM sc_users_credit_data c, sc_sms_routes r WHERE c.user_id = ' . intval($usr->uid) . ' AND c.route_id = r.id AND c.credits < 5000;';
                            $routeCreditsData = Doo::db()->fetchAll($creqry, null, PDO::FETCH_OBJ);
                            //compose email
                            $webdata = Doo::db()->find($webobj, array('limit' => 1, 'select' => 'domains,logo,site_data', 'where' => 'user_id=' . $usr->upline_id));
                            if (!$webdata) {
                                $webdata = Doo::db()->find($webobj, array('limit' => 1, 'select' => 'domains,logo,site_data', 'where' => 'user_id=1'));
                            }
                            $cdata = unserialize($webdata->site_data);
                            if ($cdata != false && $cdata['helpmail'] != '') {
                                $mainDomain = explode(",", $webdata->domains)[0];
                                $logo = 'https://' . $mainDomain . '/' . Doo::conf()->image_upload_url . 'logos/' . $webdata->logo;

                                $data['company_url'] = 'https://' . $mainDomain . '/';
                                $data['company_name'] = $cdata['company_name'];
                                $data['logo'] = $logo;
                                $data['name'] = $usr->name;
                                $data['helpline'] = $cdata['helpline'];
                                $data['company_domain'] = $mainDomain;

                                $data['credits'] = $routeCreditsData;

                                $mailbody = $this->view()->getRendered("mail/lowCreditAlert", $data);

                                $maildata = array();
                                $maildata['sender_email'] = $cdata['helpmail'];
                                $maildata['sender_name'] = $cdata['company_name'];
                                $maildata['recipient_list'] = serialize(array($usr->email));
                                $maildata['email_sub'] = 'Low Credits Alert | Messaging GW';
                                $maildata['email_text'] = base64_encode($mailbody);

                                array_push($insertData, $maildata);
                            }
                        } else {
                            //check balance in wallet
                            $walqry = '';
                        }
                    }
                }
                //insert in email queue
                if (sizeof($insertData) > 0) {
                    $eqobj = Doo::loadModel('ScEmailQueue', true);
                    $eqobj->bulkInsert($insertData);
                    //add watchman log
                    $logobj->addLog('DAILY EMAIL QUEUED: A total of. ' . sizeof($insertData) . ' emails were added to queue.', 0);
                }
            }

            //end of putting emails in queue

            //2. Change ACK status
            if (Doo::conf()->run_eod_dlr_settlement == "yes") {
                $psobj = Doo::loadModel('ScMiscVars', true);
                $psobj->var_name = 'ACK_DLR_SETTLE';
                $psdata = Doo::db()->find($psobj, array('limit' => 1));

                if ($psdata->var_status == '0') {
                    //the process is not running already so its safe to start

                    //fetch all the campaign records with ACK dlr status
                    Doo::loadModel('ScSentSms');
                    Doo::loadModel('ScUsers');
                    $stobj = new ScSentSms;

                    $campaigns = Doo::db()->find($stobj, array('select' => 'COUNT(id) as total_sms, user_id, route_id, sms_shoot_id', 'where' => "vendor_dlr IN ('-6','')", 'groupby' => 'sms_shoot_id', 'having' => 'total_sms>1'));

                    if (sizeof($campaigns) > 0) {
                        //start process and lock it
                        $psobj->id = $psdata->id;
                        $psobj->var_status = 1;
                        Doo::db()->update($psobj, array('limit' => 1));

                        $logobj->addLog('ACK_DLR_SETTLE: Process started.', 0);

                        //set for each campaign
                        foreach ($campaigns as $record) {
                            $totalsms = intval($record->total_sms);
                            $smstofakedel = intval((Doo::conf()->fakedlr_del / 100) * $totalsms);
                            $smstofakeundel = intval((Doo::conf()->fakedlr_undel / 100) * $totalsms);
                            $smstofakeexp = $totalsms - ($smstofakedel + $smstofakeundel);

                            if ($smstofakedel > 0) {
                                $delqry = "Update sc_sent_sms set dlr=1,vendor_dlr = 'RDEL' where sms_shoot_id = '$record->sms_shoot_id' AND vendor_dlr IN ('-6','') ORDER BY rand() LIMIT $smstofakedel";
                                $drs = Doo::db()->query($delqry);
                                $logobj->addLog('QUERY: ' . $delqry, 0);
                            }
                            if ($smstofakeundel > 0) {
                                $undelqry = "Update sc_sent_sms set vendor_dlr = 'RUNDEL' where sms_shoot_id = '$record->sms_shoot_id' AND vendor_dlr IN ('-6','') ORDER BY rand() LIMIT $smstofakeundel";
                                $urs = Doo::db()->query($undelqry);
                                $logobj->addLog('QUERY: ' . $undelqry, 0);
                            }
                            if ($smstofakeexp > 0) {
                                $expqry = "Update sc_sent_sms set dlr=2,vendor_dlr = 'REXP' where sms_shoot_id = '$record->sms_shoot_id' AND vendor_dlr IN ('-6','') ORDER BY rand() LIMIT $smstofakeexp";
                                $ers = Doo::db()->query($expqry);
                                $logobj->addLog('QUERY: ' . $expqry, 0);
                            }

                            //get users upline
                            $uobj = new ScUsers;
                            $upline_id = $uobj->getProfileInfo($record->user_id, 'upline_id')->upline_id;

                            sleep(1); //take one second break
                        }
                        //release the lock
                        $psobj->id = $psdata->id;
                        $psobj->var_status = 0;
                        Doo::db()->update($psobj, array('limit' => 1));

                        $logobj->addLog('ACK_DLR_SETTLE: Process Complete. ' . sizeof($campaigns) . ' campaign(s) processed.', 0);
                    }
                }
            }
            //end of ACK settle process

        }
    }


    //b. watchman_child processes


    public function smartScheduleProcess()
    {

        while (1) {
            set_time_limit(0);
            Doo::loadModel('ScAppProcesses');
            $probj = new ScAppProcesses;
            $probj2 = new ScAppProcesses;
            Doo::loadModel('ScLongcourseCampaigns');
            $qobj = new ScLongcourseCampaigns;
            $sentobj = Doo::loadModel('ScSentSms', true);
            $smpobj = Doo::loadModel('ScSmppAccounts', true);
            $rtobj = Doo::loadModel('ScSmsRoutes', true);
            $sidobj = Doo::loadModel('ScSenderId', true);
            $uobj = Doo::loadModel('ScUsers', true);
            $creobj = Doo::loadModel('ScUsersCreditData', true);
            $notifobj = Doo::loadModel('ScUserNotifications', true);
            $sumobj = Doo::loadModel('ScSmsSummary', true);
            $smssaveobj = new DooSmppcubeHelper;
            //check the status of the process
            $status = $probj->getStatus('SMART_SCHEDULE_PROCESS')->manual_flag;
            //send heartbeat
            $probj2->sendPulse('SMART_SCHEDULE_PROCESS');
            if ($status == '1') {
                //get the queued row
                $queued_item = $qobj->pickAllDistinctBatches();
                //echo '<pre>'; var_dump($queued_item);
                if (sizeof($queued_item) > 0) {
                    //run for each distinct batch to give every campaign a fair shot
                    foreach ($queued_item as $batch) {
                        $send = false;
                        $sms_shoot_id = $batch->shoot_id;
                        //look in the db again and pick the first entered batch, i.e. with min id
                        $qobj_minbatch = new ScLongcourseCampaigns;
                        $min_batch = $qobj_minbatch->getMinBatch($sms_shoot_id);
                        //echo '<br><br> Min Batch for '. $sms_shoot_id.'<br>';
                        //check if valid time frame
                        $now = date(Doo::conf()->date_format_db);
                        $now_ts = strtotime($now);
                        if (strtotime($min_batch->start_time) > $now_ts) {
                            continue;
                        }
                        $last_sent_time = $min_batch->last_sent_time;
                        $last_sent_ts = strtotime($last_sent_time);
                        $duration = $now_ts - $last_sent_ts;
                        $interval = $min_batch->submission_interval;
                        $days = $min_batch->submission_days;
                        $interval = ($interval * 60);

                        $weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri');
                        $weekends = array('Sat', 'Sun');
                        $current_day = date("D");
                        //echo $sms_shoot_id. ' Batch Interval: '. $interval.' Duration: '. $duration. '<br>';
                        // continue;
                        if (!$last_sent_time || $interval <= $duration) {
                            //either fresh batch or duration matched!! send sms now
                            $send = true;
                            //match if current day is in allowed days list
                            if ($days == 1) {
                                if (!in_array($current_day, $weekdays)) {
                                    //Dont send
                                    $send = false;
                                }
                            }
                            if ($days == 2) {
                                if (!in_array($current_day, $weekends)) {
                                    //Dont send
                                    $send = false;
                                }
                            }
                        }

                        if ($send == true) {
                            //process this batch
                            $uinfo = $uobj->getProfileInfo($min_batch->user_id, 'account_type, category, upline_id');
                            //get user credit info
                            $creobj->user_id = $min_batch->user_id;
                            $creobj->route_id = $min_batch->route_id;
                            $creditdata = Doo::db()->find($creobj, array('limit' => 1));
                            //sender
                            $sidobj->id = $min_batch->sender_id;
                            $senderid = Doo::db()->find($sidobj, array('select' => 'sender_id', 'limit' => 1))->sender_id;
                            //get route information
                            $rtobj->id = $min_batch->route_id;
                            $routedata =  Doo::db()->find($rtobj, array('limit' => 1));
                            $route_config = json_decode($routedata->route_config, true);
                            //smpp data
                            $smpobj->id = $route_config['primary_smsc'];
                            $smppdata = Doo::db()->find($smpobj, array('select' => 'smsc_id,status', 'limit' => 1));
                            $smpp_smsc = $smppdata->smsc_id;
                            //get sms summary data
                            $sumobj->sms_shoot_id = $min_batch->sms_shoot_id;
                            $sumdata = Doo::db()->find($sumobj, array('limit' => 1));

                            //push to sent sms table
                            $dlrfilcontacts = unserialize(base64_decode($min_batch->contacts));
                            $smstype = unserialize($min_batch->sms_type);
                            $contactdata['dlrfilcontacts'] = $dlrfilcontacts;
                            $contactdata['persmscount'] = $min_batch->sms_count;
                            $contactdata['persmscost'] = $min_batch->price;
                            $contactdata['schflag'] = 0;
                            $info['account_type'] = $uinfo->account_type;
                            $info['batchrouteid'] = $min_batch->route_id;
                            $info['routedata'] = $routedata;
                            $info['smstype'] = $smstype;
                            $info['smstext'] = $smstype['main'] == 'text' ? htmlspecialchars_decode($min_batch->sms_text, ENT_QUOTES) : unserialize(base64_decode($min_batch->sms_text));
                            $info['savetext'] = $min_batch->sms_text;
                            $info['shootid'] = $min_batch->sms_shoot_id;
                            $info['userid'] = $min_batch->user_id;
                            $info['usergroup'] = $uinfo->category;
                            $info['uplineid'] = $uinfo->upline_id;
                            $info['senderid'] = $min_batch->sender_id;
                            $info['senderstr'] = $senderid;
                            $info['smsc'] = $smpp_smsc;
                            $info['creditdata'] = $creditdata;
                            $info['tlv'] = $sumdata->tlv_data == '' ? false : json_decode($sumdata->tlv_data);
                            if ($min_batch->send_flag == 1) {
                                $smssaveobj->submitInitialCampaign($contactdata, $info, $dlrfilcontacts);
                            } else {
                                //fake dlr
                                $info['smsstatus'] = $min_batch->sms_status;
                                $info['dlrcode'] = $min_batch->dlr;
                                $info['smppcode'] = $min_batch->dlr == 1 ? 'DELIVRD' : ($min_batch->dlr == 16 ? 'REJECTD' : 'UNDELIV');
                                $info['dlrvendorcode'] = $min_batch->vendor_dlr;
                                $smssaveobj->saveSmsBatch($contactdata, $info, $dlrfilcontacts);
                            }

                            //update the last sent time for this shoot id
                            $qobj_updatelastsent = new ScLongcourseCampaigns;
                            $qobj_updatelastsent->updateLastSentTime($sms_shoot_id);
                            //delete this batch
                            $qobj_del = new ScLongcourseCampaigns;
                            $qobj_del->deleteBatch($min_batch->min_batch_id);
                            $probj2->sendPulse('SMART_SCHEDULE_PROCESS');
                        }
                    }
                }
            }
            //sleep
            //break;
            sleep(60); //1 min
        }
    }

    public function archiveFetchProcess()
    {
        Doo::loadModel('ScAppProcesses');
        $probj = new ScAppProcesses;
        $probj2 = new ScAppProcesses;

        Doo::loadModel('ScArchiveTasks');
        $atobj = new ScArchiveTasks;

        while (1) {
            set_time_limit(0);

            //check the status of the process
            $status = $probj->getStatus('ARCHIVE_FETCH_PROCESS')->manual_flag;
            //send heartbeat
            $probj2->sendPulse('ARCHIVE_FETCH_PROCESS');
            if ($status == '1') {
                //get the queued task
                $queued_item = Doo::db()->find($atobj, array('limit' => 1, 'asc' => 'id', 'where' => 'task_type=1 AND status=0'), 3);
                if ($queued_item->id) {
                    //set status to UNDER PROCESS
                    $org_status = $queued_item->status;
                    $thisid = $queued_item->id;
                    $lockobj = new ScArchiveTasks;
                    $lockobj->id = $thisid;
                    $lockobj->status = 1;
                    Doo::db()->update($lockobj, array('limit' => 1), 3);

                    //prepare to access data from summary
                    $datr = explode("-", $queued_item->date_range);
                    $from = date('Y-m-d H:i:s', strtotime(trim($datr[0])));
                    $to = date('Y-m-d H:i:s', strtotime(trim($datr[1])));

                    //go to node for this
                    $userdata = array(
                        "user" => $queued_item->user_id,
                        "from" => $from,
                        "to" => $to
                    );
                    $soptions = array(
                        'http' => array(
                            'header'  => "Content-type: application/json; charset=UTF-8\r\n",
                            'method'  => 'POST',
                            'content' => json_encode($userdata)
                        )
                    );
                    $context  = stream_context_create($soptions);
                    $archiveRecords = file_get_contents(Doo::conf()->archive_api_url . 'fetch', false, $context);
                    $archiveRecords = json_decode($archiveRecords, true);
                    //echo '<pre>'; var_dump($archiveRecords); die;
                    $routeqry = "SELECT id, title FROM sc_sms_routes";
                    $routesData = Doo::db()->fetchAll($routeqry, null, PDO::FETCH_KEY_PAIR);
                    //foreach matched record fetch all sentsms data and add to the  data string

                    $probj2->sendPulse('ARCHIVE_FETCH_PROCESS');

                    if (sizeof($archiveRecords) > 0) {
                        //put it into a file
                        $totalrecords = sizeof($archiveRecords);

                        //create string to write in file
                        $filename = 'ARCHIVE-REPORTS-' . strtotime(trim($datr[0])) . '-' . time();
                        if ($totalrecords > 100000) {
                            //if records more than half a million create separate files and zip them
                            $batches = array_chunk($archiveRecords, 100000);

                            //prepare zip
                            $fileloc = Doo::conf()->global_export_dir . $filename;
                            $zip = new ZipArchive;
                            $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);
                            $part = 1;

                            foreach ($batches as $batch) {
                                $exstr = " Mobile " . "\t";
                                $exstr .= "Network " . "\t";
                                $exstr .= "Circle " . "\t";
                                $exstr .= "Time " . "\t";
                                $exstr .= "Route " . "\t";
                                $exstr .= "Sender ID " . "\t";
                                $exstr .= "SMS Type " . "\t";
                                $exstr .= "SMS Text " . "\t";
                                $exstr .= "SMS Count " . "\t";
                                $exstr .= "Msg ID " . "\t";
                                $exstr .= "DLR Status " . "\t";
                                $exstr .= "Explanation " . "\t";

                                $exstr .= "\n";
                                //loop through each
                                foreach ($batch as $dt) {
                                    //write the string
                                    $stypestr = 'text';
                                    if ($dt['sms_type']['main'] == "text") {
                                        if ($dt['sms_type']['unicode'] == true) {
                                            $stypestr = 'unicode';
                                        }
                                        if ($dt['sms_type']['flash'] == true) {
                                            $stypestr = 'flash';
                                        }
                                        if ($dt['sms_type']['unicode'] == true && $dt['sms_type']['flash'] == true) {
                                            $stypestr = 'unicode-flash';
                                        }
                                    } elseif ($dt['sms_type']['main'] == 'wap') {
                                        $stypestr = 'wap';
                                    } elseif ($dt['sms_type']['main'] == 'vcard') {
                                        $stypestr = 'vcard';
                                    }

                                    $exstr .= $dt['msisdn'] . " \t";
                                    $exstr .= $dt['operator']['title'] . " \t";
                                    $exstr .= $dt['operator']['region'] . " \t";
                                    $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt['submit_time'])) . " \t";
                                    $exstr .= $routesData[intval($dt['route_id'])] . " \t";
                                    $exstr .= $dt['sender_id'] . " \t";
                                    $exstr .= $stypestr . " \t";
                                    $exstr .= $dt['sms_text'] . " \t";
                                    $exstr .= $dt['sms_parts'] . " \t";
                                    $exstr .= '#' . $dt['mysql_id'] . " \t";
                                    $exstr .= $dt['dlr']['app_status'] . " \t";
                                    $exstr .= $dt['dlr']['smpp_response'] . " \t";
                                    $exstr .= "\n";
                                }

                                // write in excel file
                                $fh = fopen($fileloc . '-PART-' . $part . '.xls', 'w') or die("Can't open file");
                                fwrite($fh, $exstr);
                                fclose($fh);

                                //add to zip
                                $zip->addFile($fileloc . '-PART-' . $part . '.xls', $fileloc . '-PART-' . $part . '.xls');
                                $part++;
                            }

                            $zip->close();
                            $probj2->sendPulse('ARCHIVE_FETCH_PROCESS');
                        } else {
                            //records less than 500k, create single export file and zip it

                            //prepare zip
                            $fileloc = Doo::conf()->global_export_dir . $filename;
                            $zip = new ZipArchive;
                            $result_zip = $zip->open($fileloc . '.zip', ZipArchive::CREATE);

                            $exstr = " Mobile " . "\t";
                            $exstr .= "Network " . "\t";
                            $exstr .= "Circle " . "\t";
                            $exstr .= "Time " . "\t";
                            $exstr .= "Route " . "\t";
                            $exstr .= "Sender ID " . "\t";
                            $exstr .= "SMS Type " . "\t";
                            $exstr .= "SMS Text " . "\t";
                            $exstr .= "SMS Count " . "\t";
                            $exstr .= "Msg ID " . "\t";
                            $exstr .= "DLR Status " . "\t";
                            $exstr .= "Explanation " . "\t";

                            $exstr .= "\n";
                            //loop through each
                            foreach ($archiveRecords as $dt) {

                                $stypestr = 'text';
                                if ($dt['sms_type']['main'] == "text") {
                                    if ($dt['sms_type']['unicode'] == true) {
                                        $stypestr = 'unicode';
                                    }
                                    if ($dt['sms_type']['flash'] == true) {
                                        $stypestr = 'flash';
                                    }
                                    if ($dt['sms_type']['unicode'] == true && $dt['sms_type']['flash'] == true) {
                                        $stypestr = 'unicode-flash';
                                    }
                                } elseif ($dt['sms_type']['main'] == 'wap') {
                                    $stypestr = 'wap';
                                } elseif ($dt['sms_type']['main'] == 'vcard') {
                                    $stypestr = 'vcard';
                                }

                                $exstr .= $dt['msisdn'] . " \t";
                                $exstr .= $dt['operator']['title'] . " \t";
                                $exstr .= $dt['operator']['region'] . " \t";
                                $exstr .= date(Doo::conf()->date_format_med_time_s, strtotime($dt['submit_time'])) . " \t";
                                $exstr .= $routesData[intval($dt['route_id'])] . " \t";
                                $exstr .= $dt['sender_id'] . " \t";
                                $exstr .= $stypestr . " \t";
                                $exstr .= $dt['sms_text'] . " \t";
                                $exstr .= $dt['sms_parts'] . " \t";
                                $exstr .= '#' . $dt['mysql_id'] . " \t";
                                $exstr .= $dt['dlr']['app_status'] . " \t";
                                $exstr .= $dt['dlr']['smpp_response'] . " \t";
                                $exstr .= "\n";
                            }

                            // write in excel file
                            $fh = fopen($fileloc . '.xls', 'w') or die("Can't open file");
                            fwrite($fh, $exstr);
                            fclose($fh);

                            //add to zip
                            $zip->addFile($fileloc . '.xls', $fileloc . '.xls');

                            $zip->close();
                            $probj2->sendPulse('ARCHIVE_FETCH_PROCESS');
                        }

                        //update task table
                        $lockobj->id = $thisid;
                        $lockobj->file_id = $filename . '.zip' . ':' . $totalrecords;
                        $lockobj->total_records = $totalrecords;
                        $lockobj->status = 2;
                        Doo::db()->update($lockobj, array('limit' => 1), 3);
                        $probj2->sendPulse('ARCHIVE_FETCH_PROCESS');
                        //close

                    } else {
                        //no records found matching this criteria
                    }
                }
            }

            //sleep
            sleep(60);
        }
    }

    public function dailyMailerProcess()
    {
        Doo::loadModel('ScAppProcesses');
        $probj = new ScAppProcesses;
        $probj2 = new ScAppProcesses;

        Doo::loadModel('ScEmailQueue');
        $eqobj = new ScEmailQueue;

        //process picks email tasks and excutes them

        while (1) {
            set_time_limit(0);

            //check the status of the process
            $status = $probj->getStatus('DAILY_MAILER_PROCESS')->manual_flag;
            //send heartbeat
            $probj2->sendPulse('DAILY_MAILER_PROCESS');
            if ($status == '1') {
                //get the queued task
                $queued_item = Doo::db()->find($eqobj, array('limit' => 1, 'asc' => 'id', 'where' => 'status=0'));
                if ($queued_item->id) {
                    //set status to UNDER PROCESS
                    $org_status = $queued_item->status;
                    $thisid = $queued_item->id;
                    $lockobj = new ScEmailQueue;
                    $lockobj->id = $thisid;
                    $lockobj->status = 1;
                    Doo::db()->update($lockobj, array('limit' => 1));

                    //prepare mailer
                    $data['smtpHost'] = Doo::conf()->smtp_server;
                    $data['smtpPort'] = Doo::conf()->smtp_port;
                    $data['smtpUsername'] = Doo::conf()->smtp_user;
                    $data['smtpPassword'] = Doo::conf()->smtp_pass;

                    $data['senderName'] = $queued_item->sender_name;
                    $data['senderEmail'] = $queued_item->sender_email;

                    $data['subject'] = $queued_item->email_sub;
                    $data['mailbody'] = base64_decode($queued_item->email_text);

                    //get details
                    $rlist = unserialize($queued_item->recipient_list);
                    $probj2->sendPulse('DAILY_MAILER_PROCESS');
                    if (sizeof($rlist) > 0) {
                        //send email
                        foreach ($rlist as $email) {
                            $data['receiverEmail'] = $email;
                            DooSmppcubeHelper::sendEmail($data);
                        }
                    }


                    //change status
                    $lockobj->id = $thisid;
                    $lockobj->status = 2;
                    Doo::db()->update($lockobj, array('limit' => 1));
                    $probj2->sendPulse('DAILY_MAILER_PROCESS');
                }
            }

            //sleep
            sleep(30);
        }
    }


    public function smscLiveStatusManager()
    {
        $probj2 = Doo::loadModel('ScAppProcesses', true);
        while (1) {
            $probj2->sendPulse('SMSC_STATE_MONITOR');
            set_time_limit(0);
            //--
            $xml_data = "";
            $url = "http://" . Doo::conf()->bearerbox_host . ":" . Doo::conf()->admin_port . "/status.xml?password=" . Doo::conf()->status_password;
            if (($fp = fopen($url, "r"))) {

                /* read the XML input */
                while (!feof($fp)) {
                    $xml_data .= fread($fp, 200000);
                }
                fclose($fp);
            } else {
                //kannel down
            }
            //echo '<pre>';var_dump($xml_data);die;
            $xml = simplexml_load_string($xml_data);
            $probj2->sendPulse('SMSC_STATE_MONITOR');
            $MainStatus = $xml->status;
            $smscObject = $xml->smscs;
            $smscCount = $smscObject->count;

            $smscInnerObject = $smscObject->smsc;
            $smscInfo = array();
            $onlineList = array();
            $varadmin_id = 'admin-id';
            for ($i = 0; $i < $smscCount; $i++) {
                if (strstr((string)$smscInnerObject[$i]->status, "online") !== false) {
                    $onlineList[(string)$smscInnerObject[$i]->id] = 'online';
                }
                $smscInfo[$i]['smsc_name'] = (string) $smscInnerObject[$i]->name;
                $smscInfo[$i]['smsc_admin_id'] = (string)$smscInnerObject[$i]->$varadmin_id;
                $smscInfo[$i]['smsc_id'] = (string)$smscInnerObject[$i]->id;
                $smscInfo[$i]['smsc_status'] = (string)$smscInnerObject[$i]->status;
            }
            //update live status in newly created DB field
            foreach ($smscInfo as $smsc) {
                $state = $onlineList[$smsc['smsc_id']] ? 1 : -1;
                //echo $smsc['smsc_id'].' state is '. $state;
                $qry = "UPDATE sc_smpp_accounts SET live_status = $state WHERE smsc_id = '" . $smsc['smsc_id'] . "'";
                Doo::db()->query($qry);
            }
            $probj2->sendPulse('SMSC_STATE_MONITOR');
            //--
            sleep(10);
        }
    }


    //3. Sender ID Management

    public function manageSenderId()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['sender']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Sender ID';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_sender';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageSender', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllSenders()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['sender']) {
            //denied
            return array('/denied', 'internal');
        }

        if (intval($this->params['id']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['id']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                $uobj = Doo::loadModel('ScUsers', true);
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
        }

        //get all sender id for this account
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->req_by = $uid;
        $sids = Doo::db()->find($obj);
        $total = count($sids);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($sids as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editSender/' . $dt->id . '">' . $this->SCTEXT('Edit') . '</a></li><li><a href="javascript:void(0);" class="del-sid" data-sid="' . $dt->id . '">' . $this->SCTEXT('Delete') . '</a></li></ul></div>';

            $status_str = $dt->status == '1' ? ' <span class="label label-success label-md">' . $this->SCTEXT('Approved') . '</span>' : ($dt->status == '2' ? '<span class="label label-danger label-md">' . $this->SCTEXT('Rejected') . '</span>' : ($dt->status == '-1' ? '<span class="label label-success label-md">' . $this->SCTEXT('Under Review') . '</span>' : '<span class="label label-warning label-md">' . $this->SCTEXT('Pending') . '</span>'));

            $output = array($dt->sender_id, date(Doo::conf()->date_format_long_time, strtotime($dt->req_on)), $status_str, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addSender()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['sender']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Sender ID'] = Doo::conf()->APP_URL . 'manageSenderId';
        $data['active_page'] = 'Add Sender ID';

        $dobj = Doo::loadModel('ScUsersDocuments', true);
        $dobj->owner_id = $_SESSION['user']['userid'];
        $data['docs'] = Doo::db()->find($dobj, array("where" => "type IN (2,3)"));

        //get all countries
        $cvobj = Doo::loadModel('ScCoverage', true);
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country_code, country, prefix', 'where' => 'id > 1'));

        //get all operators
        $opobj = Doo::loadModel('ScMccMncList', true);
        $data['opdata'] = Doo::db()->find($opobj, array('where' => 'status = 1', 'select' => 'brand, operator, country_name, country_iso, country_code', 'groupby' => 'brand, country_iso', 'asc' => 'country_name'));


        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_sender';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addSender', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editSender()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['sender']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Sender ID'] = Doo::conf()->APP_URL . 'manageSenderId';
        $data['active_page'] = 'Edit Sender ID';

        //get data
        $sid = intval($this->params['id']);
        if ($sid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->id = $sid;
        $obj->req_by = $_SESSION['user']['userid'];
        $data['sender'] = Doo::db()->find($obj, array('limit' => 1));

        $dobj = Doo::loadModel('ScUsersDocuments', true);
        $dobj->owner_id = $_SESSION['user']['userid'];
        $data['docs'] = Doo::db()->find($dobj, array("where" => "type IN (2,3)"));

        //get all countries
        $cvobj = Doo::loadModel('ScCoverage', true);
        $data['cvdata'] = Doo::db()->find($cvobj, array('select' => 'id, country_code, country, prefix', 'where' => 'id > 1'));

        //get all operators
        $opobj = Doo::loadModel('ScMccMncList', true);
        $data['opdata'] = Doo::db()->find($opobj, array('where' => 'status = 1', 'select' => 'brand, operator, country_name, country_iso, country_code', 'groupby' => 'brand, country_iso', 'asc' => 'country_name'));

        $data['page'] = 'Messaging';
        $data['current_page'] = 'edit_sender';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editSender', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deleteSender()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['sender']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $sid = intval($this->params['id']);
        if ($sid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //validate n delete
        Doo::loadModel('ScSenderId');
        $obj = new ScSenderId;
        $obj->id = $sid;
        $res = Doo::db()->find($obj, array('limit' => 1));
        if ($res->id && $res->req_by == $_SESSION['user']['userid']) {
            Doo::db()->delete($obj, array('limit' => 1));
            $msg = 'Sender ID deleted successfully';
        } else {
            return array('/denied', 'internal');
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageSenderId';
    }

    public function saveSender()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['sender']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $sender = DooTextHelper::cleanInput($_POST['sid'], ' ?*+&@$', 0);
        $sid = intval($_POST['senid']);
        $files = is_array($_POST['tfiles']) ? implode(",", $_POST['tfiles']) : '';
        $countries_matrix = array(
            "countries" => $_POST['cvsel'],
            "operators" => $_POST['opsel']
        );

        if ($sid == 0) {
            //insert
            Doo::loadModel('ScSenderId');
            $obj = new ScSenderId;
            $obj->sender_id = $sender;
            $obj->req_by = $_SESSION['user']['userid'];
            $obj->file_ids = $files;
            $obj->countries_matrix = json_encode($countries_matrix);
            $obj->status = $_SESSION['user']['group'] == 'admin' ? 1 : 0;
            Doo::db()->insert($obj);
            $msg = 'New Sender ID added successfully';
        } else {
            //update
            Doo::loadModel('ScSenderId');
            $obj = new ScSenderId;
            $obj->id = $sid;
            $res = Doo::db()->find($obj, array('limit' => 1));
            if ($res->id && $res->req_by == $_SESSION['user']['userid']) {
                $obj->sender_id = $sender;
                $obj->file_ids = $files;
                $obj->countries_matrix = json_encode($countries_matrix);
                if ($_SESSION['user']['group'] == 'admin') {
                    $status = 1;
                } else {
                    if ($res->status == 0) {
                        $status = 0;
                    } else {
                        $status = -1; //under review
                    }
                }
                $obj->status = $status;
                Doo::db()->update($obj, array('limit' => 1));
                $msg = 'Sender ID updated successfully';
            } else {
                return array('/denied', 'internal');
            }
        }

        if ($_SESSION['user']['group'] != 'admin') {
            //call the hypernode to alert admin via email
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
                "mode" => "sender_approval_request",
                "data" => array(
                    "user_id" => $_SESSION['user']['userid'],
                    "incidentPlatform" => $osdata,
                    "incidentDateTime" => date(Doo::conf()->date_format_db),
                    "senderId" => $sender,
                    "allowedCountries" => $countries_matrix,
                    "actionType" => $sid == 0 ? 'add' : 'update',
                    "approvalLink" => Doo::conf()->APP_URL . 'approveSenderIds'
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
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageSenderId';
    }

    public function getCoverageRegulations()
    {
        //collect values
        $id = intval($_POST['id']);
        $res = '';
        //query info
        if ($id > 0) {
            $cobj = Doo::loadModel('ScCoverage', true);
            $cobj->id = $id;
            $data = Doo::db()->find($cobj, array('limit' => 1, 'select' => 'regulations'));
            if ($data->regulations != '') {
                $regs = html_entity_decode($data->regulations);
                $res .= '<div class="planopts"><div class="p-sm text-white text-center fz-md label-info">Regulations</div><div class="p-sm">' . $regs . '</div></div>';
            }
        }
        //return
        echo $res == '' ? '- No Specific Regulastions -' : $res;
        exit;
    }

    //4. SMS Templates Mgmt

    public function manageTemplates()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'SMS Templates';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_templates';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageTemplates', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllTemplates()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }

        if (intval($this->params['id']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['id']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                $uobj = Doo::loadModel('ScUsers', true);
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
        }
        //get all sender id for this account
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->user_id = $uid;
        $temps = Doo::db()->find($obj);
        $total = count($temps);

        Doo::loadModel('ScSmsRoutes');
        $robj = new ScSmsRoutes;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($temps as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editTemplate/' . $dt->id . '">' . $this->SCTEXT('Edit') . '</a></li><li><a href="javascript:void(0);" class="del-tmp" data-tmp="' . $dt->id . '">' . $this->SCTEXT('Delete') . '</a></li></ul></div>';

            if ($dt->route_id != 0) {
                $rstr = '<span class="label label-info label-md">' . $robj->getRouteData($dt->route_id, 'title')->title . '</span>';
                $status_str = $dt->status == '1' ? ' <span class="label label-success label-md">' . $this->SCTEXT('Approved') . '</span>' : ($dt->status == '2' ? '<span class="label label-danger label-md">' . $this->SCTEXT('Rejected') . '</span>' : '<span class="label label-warning label-md">' . $this->SCTEXT('Pending') . '</span>');
            } else {
                $rstr = '<span class="label label-default label-md">N/A</span>';
                $status_str = '<span class="label label-default label-md">N/A</span>';
            }

            if ($_SESSION['user']['account_type'] == '0') {
                $output = array($dt->title, '<div class="panel panel-info panel-custom">' . $dt->content . '</div>', date(Doo::conf()->date_format_long_time, strtotime($dt->created_on)), $rstr, $status_str, $button_str);
            } else {
                $output = array($dt->title, '<div class="panel panel-info panel-custom">' . $dt->content . '</div>', date(Doo::conf()->date_format_long_time, strtotime($dt->created_on)), $status_str, $button_str);
            }

            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function getUseTemplates()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all for this account
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->user_id = $_SESSION['user']['userid'];
        $temps = Doo::db()->find($obj);
        $total = count($temps);

        Doo::loadModel('ScSmsRoutes');
        $robj = new ScSmsRoutes;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($temps as $dt) {

            $button_str = '<button type="button" data-dismiss="modal" data-template="' . ($dt->content) . '" class="useTempBtn btn btn-primary"><i class="fa fa-lg fa-check-circle text-white"></i> &nbsp; ' . $this->SCTEXT('Use This') . '</button>';

            if ($dt->route_id != 0) {
                $rstr = '<span class="label label-info label-md">' . $robj->getRouteData($dt->route_id, 'title')->title . '</span>';
                $status_str = $dt->status == '1' ? ' <span class="label label-success label-md">' . $this->SCTEXT('Approved') . '</span>' : ($dt->status == '2' ? '<span class="label label-danger label-md">' . $this->SCTEXT('Rejected') . '</span>' : '<span class="label label-warning label-md">' . $this->SCTEXT('Pending') . '</span>');
            } else {
                $rstr = '<span class="label label-default label-md">N/A</span>';
                $status_str = '<span class="label label-default label-md">N/A</span>';
            }
            if ($_SESSION['user']['account_type'] == '0') {
                $output = array($dt->title, '<div style="line-break:anywhere;" class="panel panel-info panel-custom">' . $dt->content . '</div>', $rstr, $status_str, $button_str);
            } else {
                $output = array($dt->title, '<div style="line-break:anywhere;" class="panel panel-info panel-custom">' . $dt->content . '</div>', $status_str, $button_str);
            }

            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }


    public function addTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage SMS Templates'] = Doo::conf()->APP_URL . 'manageTemplates';
        $data['active_page'] = 'Add New Template';

        $dobj = Doo::loadModel('ScUsersDocuments', true);
        $dobj->owner_id = $_SESSION['user']['userid'];
        $data['docs'] = Doo::db()->find($dobj, array("where" => "type IN (2,3)"));

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_template';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addTemplate', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage SMS Templates'] = Doo::conf()->APP_URL . 'manageTemplates';
        $data['active_page'] = 'Edit Template';

        //get data
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->id = $tid;
        $obj->user_id = $_SESSION['user']['userid'];
        $data['temp'] = Doo::db()->find($obj, array('limit' => 1));

        $dobj = Doo::loadModel('ScUsersDocuments', true);
        $dobj->owner_id = $_SESSION['user']['userid'];
        $data['docs'] = Doo::db()->find($dobj, array("where" => "type IN (2,3)"));

        $data['page'] = 'Messaging';
        $data['current_page'] = 'edit_template';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editTemplate', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deleteTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //validate n delete
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->id = $tid;
        $res = Doo::db()->find($obj, array('limit' => 1));
        if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
            Doo::db()->delete($obj, array('limit' => 1));
            $msg = 'SMS Template deleted successfully';
        } else {
            return array('/denied', 'internal');
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageTemplates';
    }

    public function saveTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['templates']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $tname = DooTextHelper::cleanInput($_POST['tname'], ' ', 0);
        $txt = htmlspecialchars($_POST['tcont']);
        $tid = intval($_POST['tempid']);
        $files = is_array($_POST['tfiles']) ? implode(",", $_POST['tfiles']) : '';

        if ($tid == 0) {
            //insert
            Doo::loadModel('ScSmsTemplates');
            $obj = new ScSmsTemplates;
            $obj->title = $tname;
            $obj->content = $txt;
            $obj->user_id = $_SESSION['user']['userid'];
            $obj->file_ids = $files;
            if ($_POST['tglrt'] == 'on') $obj->route_id = intval($_POST['route']);
            $obj->status = $_SESSION['user']['group'] == 'admin' ? 1 : 0;
            Doo::db()->insert($obj);
            $msg = 'New SMS template added successfully';
        } else {
            //update
            Doo::loadModel('ScSmsTemplates');
            $obj = new ScSmsTemplates;
            $obj->id = $tid;
            $res = Doo::db()->find($obj, array('limit' => 1));
            if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
                $obj->title = $tname;
                $obj->content = $txt;
                $obj->file_ids = $files;
                if ($_POST['tglrt'] == 'on') {
                    $obj->route_id = intval($_POST['route']);
                    $obj->status = $_SESSION['user']['group'] == 'admin' ? 1 : 0;
                } else {
                    $obj->route_id = 0;
                    $obj->status = 0;
                }

                Doo::db()->update($obj, array('limit' => 1));
                $msg = 'SMS template updated successfully';
            } else {
                return array('/denied', 'internal');
            }
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageTemplates';
    }

    //5. Contact Management

    public function manageGroups()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Groups';

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'manage_groups';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageGroups', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllGroups()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all groups for this account
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;
        $obj->user_id = $_SESSION['user']['userid'];
        $grps = Doo::db()->find($obj);
        $total = count($grps);

        Doo::loadModel('ScUserContacts');
        $cobj = new ScUserContacts;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($grps as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editGroup/' . $dt->id . '">' . $this->SCTEXT('Edit Group') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewContacts/' . $dt->id . '">' . $this->SCTEXT('View Contacts') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'moveContacts/' . $dt->id . '">' . $this->SCTEXT('Move Contacts') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'globalFileDownload/group/' . $dt->id . '">' . $this->SCTEXT('Download Contacts') . '</a></li><li><a href="javascript:void(0);" class="del-gid" data-gid="' . $dt->id . '">' . $this->SCTEXT('Delete') . '</a></li></ul></div>';

            $contacts = $cobj->countContacts($dt->id);

            $cstr = '<span class="label label-info label-md">' . number_format($contacts) . ' contact(s)</span>';

            $output = array($dt->group_name, $cstr, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addGroup()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Groups'] = Doo::conf()->APP_URL . 'manageGroups';
        $data['active_page'] = 'Add New Group';

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'add_group';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addGroup', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editGroup()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Groups'] = Doo::conf()->APP_URL . 'manageGroups';
        $data['active_page'] = 'Edit Group';

        //get data
        $gid = intval($this->params['id']);
        if ($gid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;
        $obj->id = $gid;
        $obj->user_id = $_SESSION['user']['userid'];
        $data['group'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'edit_group';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editGroup', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function moveContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Manage Groups'] = Doo::conf()->APP_URL . 'manageGroups';
        $data['active_page'] = 'Move Contacts';

        //get data
        $gid = intval($this->params['id']);
        if ($gid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;
        $obj->user_id = $_SESSION['user']['userid'];
        $data['groups'] = Doo::db()->find($obj);
        $data['gid'] = $gid;

        Doo::loadModel('ScUserContacts');
        $cobj = new ScUserContacts;
        $cobj->user_id = $_SESSION['user']['userid'];
        $cobj->group_id = $gid;
        $data['cno'] = Doo::db()->find($cobj, array('limit' => 1, 'select' => 'COUNT(id) as total'))->total;

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'move_contacts';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/moveContacts', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveMoveContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $ogid = intval($_POST['grpid']);
        $ngid = intval($_POST['grp']);
        //make sure both groups belong to the logged in user
        Doo::loadModel('ScUserContactGroups');
        $gobj = new ScUserContactGroups;
        $gopt['where'] = 'id IN(' . $ogid . ',' . $ngid . ')';
        $gopt['select'] = 'user_id';
        $usrs = Doo::db()->find($gobj, $gopt);

        foreach ($usrs as $u) {
            if ($_SESSION['user']['userid'] != $u->user_id) {
                //invalid id
                return array('/denied', 'internal');
            }
        }
        //update database
        Doo::loadModel('ScUserContacts');
        $cobj = new ScUserContacts;
        $copt['where'] = 'group_id=' . $ogid . ' AND user_id=' . $_SESSION['user']['userid'];
        $cobj->group_id = $ngid;
        $cno = Doo::db()->update($cobj, $copt);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = number_format($cno) . ' ' . $this->SCTEXT('contacts moved successfully');
        return Doo::conf()->APP_URL . 'manageGroups';
    }

    public function deleteGroup()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $gid = intval($this->params['id']);
        if ($gid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //validate n delete
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;

        Doo::loadModel('ScUserContacts');
        $cobj = new ScUserContacts;

        $obj->id = $gid;
        $res = Doo::db()->find($obj, array('limit' => 1));
        if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
            Doo::db()->delete($obj, array('limit' => 1));
            //delete contacts belonging to this group
            $num = $cobj->deleteContacts('group', $gid);
            $msg = 'Contact group deleted successfully including group contacts.';
        } else {
            return array('/denied', 'internal');
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageGroups';
    }

    public function saveGroup()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $gname = DooTextHelper::cleanInput($_POST['gname'], ' ', 0);
        $gid = intval($_POST['grpid']);

        if ($gid == 0) {
            //insert
            $colar = array();
            $colar['varC'] = $_POST['varC'];
            $colar['varD'] = $_POST['varD'];
            $colar['varE'] = $_POST['varE'];
            $colar['varF'] = $_POST['varF'];
            $colar['varG'] = $_POST['varG'];

            Doo::loadModel('ScUserContactGroups');
            $obj = new ScUserContactGroups;
            $obj->group_name = $gname;
            $obj->user_id = $_SESSION['user']['userid'];
            $obj->column_labels = serialize($colar);
            $obj->status = 1;
            Doo::db()->insert($obj);
            $msg = 'New contact group added successfully';
        } else {
            //update
            $colar = array();
            $colar['varC'] = $_POST['varC'];
            $colar['varD'] = $_POST['varD'];
            $colar['varE'] = $_POST['varE'];
            $colar['varF'] = $_POST['varF'];
            $colar['varG'] = $_POST['varG'];

            Doo::loadModel('ScUserContactGroups');
            $obj = new ScUserContactGroups;
            $obj->id = $gid;
            $res = Doo::db()->find($obj, array('limit' => 1));
            if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
                $obj->group_name = $gname;
                $obj->column_labels = serialize($colar);
                Doo::db()->update($obj, array('limit' => 1));
                $msg = 'Contact group updated successfully';
            } else {
                return array('/denied', 'internal');
            }
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageGroups';
    }

    public function manageContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }

        //get group title
        $obj = Doo::loadModel('ScUserContactGroups', true);
        $obj->id = intval($this->params['gid']);
        $data['gdata'] = Doo::db()->find($obj, array('limit' => 1));

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['links']['Contact Groups'] = Doo::conf()->APP_URL . 'manageGroups';
        $data['active_page'] = $data['gdata']->group_name . ' Contacts';

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'manage_contacts';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageContacts', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getGroupContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'mobile', 'dt' => 0),
            array('db' => 'name', 'dt' => 1),
            array('db' => 'network', 'dt' => 2),
            array('db' => 'circle', 'dt' => 3)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }

        //get all contacts for this account

        Doo::loadModel('ScUserContacts');
        $obj = new ScUserContacts;
        $obj->group_id = intval($this->params['gid']);
        $obj->user_id = $_SESSION['user']['userid'];

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $cts = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($cts);

        Doo::loadModel('ScUserContactGroups');
        $gobj = new ScUserContactGroups;
        $gdata = $gobj->getGroupData(intval($this->params['gid']));
        $colar = unserialize($gdata->column_labels);

        Doo::loadModel('ScCoverage');
        $cobj = new ScCoverage;

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($cts as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editContact/' . $gdata->id . '/' . $dt->id . '">' . $this->SCTEXT('Edit') . '</a></li><li><a href="javascript:void(0);" class="del-cid" data-cid="' . $dt->id . '">' . $this->SCTEXT('Delete') . '</a></li></ul></div>';
            if ($dt->country != 0) {
                $cvdata = $cobj->getCoverageData($dt->country, 'country,prefix');
                $cstr = '<br>' . $cvdata->country . ' (' . $cvdata->prefix . ')';
            } else {
                $cstr = '<span class="label label-default label-md"> N/A </span>';
            }

            $output = array();
            array_push($output, $dt->mobile . '<input type="hidden" class="cids" value="' . $dt->id . '"/>');
            array_push($output, ($dt->name));
            if ($colar['varC'] != '') array_push($output, ($dt->varC));
            if ($colar['varD'] != '') array_push($output, ($dt->varD));
            if ($colar['varE'] != '') array_push($output, ($dt->varE));
            if ($colar['varF'] != '') array_push($output, ($dt->varF));
            if ($colar['varG'] != '') array_push($output, ($dt->varG));
            array_push($output, ($dt->network));
            array_push($output, ($dt->circle) . $cstr);
            array_push($output, $button_str);


            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addContact()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //get group info
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;
        $obj->id = $this->params['gid'];
        $data['gdata'] = Doo::db()->find($obj, array('limit' => 1));

        //breadcrums
        $data['links']['Contact Groups'] = Doo::conf()->APP_URL . 'manageGroups';
        $data['links'][$data['gdata']->group_name] = Doo::conf()->APP_URL . 'viewContacts/' . $data['gdata']->id;
        $data['active_page'] = 'Add New Contact';


        //get all countries
        Doo::loadModel('ScCoverage');
        $cobj = new ScCoverage;
        $data['covs'] = Doo::db()->find($cobj, array('asc' => 'country'));

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'add_contact';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addContact', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editContact()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //get group info
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;
        $obj->id = $this->params['gid'];
        $data['gdata'] = Doo::db()->find($obj, array('limit' => 1));

        //breadcrums
        $data['links']['Contact Groups'] = Doo::conf()->APP_URL . 'manageGroups';
        $data['links'][$data['gdata']->group_name] = Doo::conf()->APP_URL . 'viewContacts/' . $data['gdata']->id;
        $data['active_page'] = 'Edit Contact';


        //get contact details
        $cid = intval($this->params['id']);
        if ($cid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //validate n delete
        Doo::loadModel('ScUserContacts');
        $ucobj = new ScUserContacts;

        $ucobj->id = $cid;
        $res = Doo::db()->find($ucobj, array('limit' => 1));
        if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
            $data['cinfo'] = $res;
        } else {
            return array('/denied', 'internal');
        }


        //get all countries
        Doo::loadModel('ScCoverage');
        $cobj = new ScCoverage;
        $data['covs'] = Doo::db()->find($cobj, array('asc' => 'country'));

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'edit_contact';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editContact', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function importContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Contacts'] = 'javascript:void(0);';
        $data['active_page'] = 'Import Contacts';

        //get all groups
        Doo::loadModel('ScUserContactGroups');
        $obj = new ScUserContactGroups;
        $obj->user_id = $_SESSION['user']['userid'];
        $data['groups'] = Doo::db()->find($obj);

        //get all countries
        Doo::loadModel('ScCoverage');
        $cobj = new ScCoverage;
        $data['covs'] = Doo::db()->find($cobj, array('asc' => 'country'));

        $data['page'] = 'Contact Management';
        $data['current_page'] = 'import_contact';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/importContact', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //check mode
        if ($_POST['task'] == 'import') {
            //collect values
            $gid = intval($_POST['group']);
            $cov_flag = intval($_POST['cnt_flag']);

            if (sizeof($_POST['uploadedFiles']) < 1) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Please upload at least one file with contact numbers.';
                return Doo::conf()->APP_URL . 'importContacts';
            }

            Doo::loadModel('ScUserContacts');
            $cobj = new ScUserContacts;

            //fetch data
            $idata['cflag'] = $cov_flag;
            $idata['country'] = intval($_POST['country']);
            foreach ($_POST['uploadedFiles'] as $file) {
                $filedata = $this->readExcelFile($file, 'contact', $idata);

                if (!$filedata) {
                    //incorrect data format
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'The format of data in the file was not correct. Please download the Sample File for reference.';
                    return Doo::conf()->APP_URL . 'importContacts';
                }

                //populate DB
                $cobj->insertBulkContacts($filedata['data'], $filedata['filetype'], $_SESSION['user']['userid'], $gid);
                //delete files
                unlink(Doo::conf()->global_upload_dir . $file);
                sleep(1); //take a break from task for 1 second
            }


            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Files successfully imported. Database updated.';
            return Doo::conf()->APP_URL . 'viewContacts/' . $gid;
        } else {
            //collect values
            $gid = intval($_POST['groupid']);
            $country = intval($_POST['country']);
            $crc = $_POST['ccir'];
            $ntw = $_POST['cnet'];
            $name = $_POST['cname'];
            $contact = $_POST['contactno']; //validate contact
            $cid = intval($_POST['cid']);

            //check if network n circle are provided or we need to figure that out using prefix
            if ($ntw == '' || $crc == '') {
                // Doo::loadModel('ScCoverage');
                // $cvobj = new ScCoverage;
                // $cvobj->id = $country;
                // $prelen = Doo::db()->find($cvobj, array('limit' => 1, 'select' => 'network_idn_pre_len'))->network_idn_pre_len;

                // Doo::loadModel('ScOcprMapping');
                // $ocpobj = new ScOcprMapping;

                // $prefix = substr($contact, 0, $prelen);
                // $ocpobj->prefix = $prefix;
                // $ocpobj->coverage = $country;
                // $pdata = Doo::db()->find($ocpobj, array('limit' => 1, 'select' => 'operator,circle'));

                // $network = $pdata->operator;
                // $circle = $pdata->circle;
            } else {
                $network = $ntw;
                $circle = $crc;
            }

            if ($cid == 0) {
                //insert
                Doo::loadModel('ScUserContacts');
                $obj = new ScUserContacts;
                $obj->mobile = $contact;
                $obj->user_id = $_SESSION['user']['userid'];
                $obj->name = $name;
                $obj->varC = $_POST['varC'];
                $obj->varD = $_POST['varD'];
                $obj->varE = $_POST['varE'];
                $obj->varF = $_POST['varF'];
                $obj->varG = $_POST['varG'];
                $obj->network = $network;
                $obj->circle = $circle;
                $obj->country = $country;
                $obj->group_id = $gid;
                Doo::db()->insert($obj);
                $msg = 'New contact number was added successfully.';
            } else {
                //update
                Doo::loadModel('ScUserContacts');
                $obj = new ScUserContacts;
                $obj->id = $cid;
                $res = Doo::db()->find($obj, array('limit' => 1));
                if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
                    $obj->mobile = $contact;
                    $obj->name = $name;
                    $obj->varC = $_POST['varC'];
                    $obj->varD = $_POST['varD'];
                    $obj->varE = $_POST['varE'];
                    $obj->varF = $_POST['varF'];
                    $obj->varG = $_POST['varG'];
                    $obj->network = $network;
                    $obj->circle = $circle;
                    $obj->country = $country;

                    Doo::db()->update($obj, array('limit' => 1));
                    $msg = 'Contact number was updated successfully.';
                } else {
                    return array('/denied', 'internal');
                }
            }

            //return
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = $msg;
            return Doo::conf()->APP_URL . 'viewContacts/' . $gid;
        }
    }

    public function deleteContact()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $cid = intval($this->params['id']);
        if ($cid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        //validate n delete
        Doo::loadModel('ScUserContacts');
        $cobj = new ScUserContacts;

        $cobj->id = $cid;
        $res = Doo::db()->find($cobj, array('limit' => 1));
        $gid = $res->group_id;
        if ($res->id && $res->user_id == $_SESSION['user']['userid']) {
            Doo::db()->delete($cobj, array('limit' => 1));
            $msg = 'Selected contact deleted successfully.';
        } else {
            return array('/denied', 'internal');
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'viewContacts/' . $gid;
    }

    public function delManyContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['contacts']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $cids = $_POST['cids'];
        //validate n delete
        Doo::loadModel('ScUserContacts');
        $cobj = new ScUserContacts;
        Doo::db()->delete($cobj, array('where' => 'id IN (' . $cids . ') AND user_id=' . $_SESSION['user']['userid']));

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Selected contacts deleted successfully.';
        echo 'done';
        exit; //no redirect since it is an ajax call
    }

    private function readExcelFile($filename, $mode, $idata = array())
    {
        if ($mode == 'contact') {

            Doo::loadHelper('DooFile');
            $fhobj = new DooFile;
            $output = array();

            $filepath = Doo::conf()->global_upload_dir . $filename;
            $ext = $fhobj->getFileExtensionFromPath($filepath, true);

            Doo::loadHelper('PHPExcel');
            $filetype = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($filetype);
            //if ($filetype != 'CSV') $objReader->setReadDataOnly(true);
            $xlobj = $objReader->load($filepath);

            //excel files
            if ($ext == 'xls' || $ext == 'xlsx') {
                if (strtolower($xlobj->getActiveSheet()->getCell('A1')->getValue()) == 'mobile') {
                    //format is correct -- read the data
                    Doo::loadModel('ScCoverage');
                    $cvobj = new ScCoverage;



                    if ($idata['cflag'] == '1') {
                        //country is chosen at the import page. Find prefix length.
                        $cov_id = $idata['country'];
                        $cvobj->id = $cov_id;
                        $prelen = 4; // Doo::db()->find($cvobj, array('limit' => 1, 'select' => 'network_idn_pre_len'))->network_idn_pre_len;
                    } else {
                        //have to read country from the file
                        $cov_name = ucwords($xlobj->getActiveSheet()->getCell('H2')->getValue());
                        $cvdata = Doo::db()->find($cvobj, array('limit' => 1, 'select' => 'id', 'where' => "country='$cov_name'"));
                        $prelen = 4; // 4;
                        $cov_id = $cvdata->id;
                    }

                    for ($i = 2; $i <= $xlobj->getActiveSheet()->getHighestRow(); $i++) {
                        $mobile = floatval(trim($xlobj->getActiveSheet()->getCell('A' . $i)->getValue()));
                        if ($mobile != 0) {
                            $ntw = $xlobj->getActiveSheet()->getCell('I' . $i)->getValue();
                            $crc = $xlobj->getActiveSheet()->getCell('J' . $i)->getValue();

                            if ($idata['cflag'] == '1' || ($ntw == '' || $crc == '')) {
                                if (intval($cov_id)) {
                                    //valid coverage was found
                                    $prefix = substr($mobile, 0, $prelen);
                                }
                            } else {
                                $network = $ntw;
                                $circle = $crc;
                            }

                            $mdata['contact'] = $mobile;
                            $mdata['name'] = DooTextHelper::cleanInput($xlobj->getActiveSheet()->getCell('B' . $i)->getValue(), " ", 0);
                            $mdata['varC'] = $xlobj->getActiveSheet()->getCell('C' . $i)->getValue();
                            $mdata['varD'] = $xlobj->getActiveSheet()->getCell('D' . $i)->getValue();
                            $mdata['varE'] = $xlobj->getActiveSheet()->getCell('E' . $i)->getValue();
                            $mdata['varF'] = $xlobj->getActiveSheet()->getCell('F' . $i)->getValue();
                            $mdata['varG'] = $xlobj->getActiveSheet()->getCell('G' . $i)->getValue();
                            $mdata['network'] = $network;
                            $mdata['circle'] = $circle;
                            $mdata['country'] = $cov_id;

                            array_push($output, $mdata);
                        }
                    }
                } else {
                    return false; //format is incorrect
                }
            }

            //CSV File
            if ($filetype == 'CSV' && $ext == 'csv') {
                $fh = fopen($filepath, 'r');
                $fdata = fgetcsv($fh, 0, "\r\n");


                if ($idata['cflag'] == 1) {
                    Doo::loadModel('ScCoverage');
                    $cvobj = new ScCoverage;


                    $cov_id = $idata['country'];
                    $cvobj->id = $cov_id;
                    $prelen = 4; // Doo::db()->find($cvobj, array('limit' => 1, 'select' => 'network_idn_pre_len'))->network_idn_pre_len;

                    foreach ($fdata as $csvstr) {
                        $mdata = array();
                        $csvar = explode(",", $csvstr);
                        $mobile = floatval(trim($csvar[0]));
                        if ($mobile != 0) {
                            $mdata['contact'] = $mobile;
                            $mdata['name'] = $csvar[1];
                            $mdata['varC'] = $csvar[2];
                            $mdata['varD'] = $csvar[3];
                            $mdata['varE'] = $csvar[4];
                            $mdata['varF'] = $csvar[5];
                            $mdata['varG'] = $csvar[6];
                            $prefix = substr($mobile, 0, $prelen);

                            $mdata['country'] = $cov_id;
                            array_push($output, $mdata);
                        }
                    }
                } else {
                    foreach ($fdata as $csvstr) {
                        $mdata = array();
                        $csvar = explode(",", $csvstr);
                        $mobile = floatval(trim($csvar[0]));
                        if ($mobile != 0) {
                            $mdata['contact'] = $mobile;
                            $mdata['name'] = $csvar[1];
                            $mdata['varC'] = $csvar[2];
                            $mdata['varD'] = $csvar[3];
                            $mdata['varE'] = $csvar[4];
                            $mdata['varF'] = $csvar[5];
                            $mdata['varG'] = $csvar[6];

                            array_push($output, $mdata);
                        }
                    }
                }
                fclose($fh);
            }

            //TXT File
            if ($filetype == 'CSV' && $ext == 'txt') {
                //text file is uploaded
                $file_h = fopen($filepath, "r");

                if ($idata['cflag'] == 1) {
                    Doo::loadModel('ScCoverage');
                    $cvobj = new ScCoverage;


                    $cov_id = $idata['country'];
                    $cvobj->id = $cov_id;
                    $prelen = 4; //Doo::db()->find($cvobj, array('limit' => 1, 'select' => 'network_idn_pre_len'))->network_idn_pre_len;

                    while (!feof($file_h)) {
                        $mobile = floatval(trim(fgets($file_h)));
                        if ($mobile != 0) {
                            $mdata['contact'] = $mobile;
                            $prefix = substr($mobile, 0, $prelen);

                            $mdata['country'] = $cov_id;
                            array_push($output, $mdata);
                        }
                    }
                } else {
                    while (!feof($file_h)) {
                        $mobile = floatval(trim(fgets($file_h)));

                        $mdata['contact'] = $mobile;
                        array_push($output, $mdata);
                    }
                }
            }
            $resp['data'] = $output;
            $resp['filetype'] = $ext;
            return $resp;
        }

        if ($mode == 'preview') {
            Doo::loadHelper('DooFile');
            $fhobj = new DooFile;
            $output = array();

            $filepath = Doo::conf()->global_upload_dir . $filename;
            $ext = $fhobj->getFileExtensionFromPath($filepath, true);

            Doo::loadHelper('PHPExcel');
            $filetype = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($filetype);
            //if ($filetype != 'CSV') $objReader->setReadDataOnly(true);
            $xlobj = $objReader->load($filepath);

            //excel files
            if ($ext == 'xls' || $ext == 'xlsx') {
                //dynamic sms
                $smstext = $idata['smstext'];

                $dyn_a = str_replace('#A#', $xlobj->getSheetByName($idata['sheet'])->getCell('A2')->getValue(), $smstext);
                $dyn_b = str_replace('#B#', $xlobj->getSheetByName($idata['sheet'])->getCell('B2')->getValue(), $dyn_a);
                $dyn_c = str_replace('#C#', $xlobj->getSheetByName($idata['sheet'])->getCell('C2')->getValue(), $dyn_b);
                $dyn_d = str_replace('#D#', $xlobj->getSheetByName($idata['sheet'])->getCell('D2')->getValue(), $dyn_c);
                $dyn_e = str_replace('#E#', $xlobj->getSheetByName($idata['sheet'])->getCell('E2')->getValue(), $dyn_d);
                $dyn_f = str_replace('#F#', $xlobj->getSheetByName($idata['sheet'])->getCell('F2')->getValue(), $dyn_e);
                $dyn_g = str_replace('#G#', $xlobj->getSheetByName($idata['sheet'])->getCell('G2')->getValue(), $dyn_f);
                $dyn_h = str_replace('#H#', $xlobj->getSheetByName($idata['sheet'])->getCell('H2')->getValue(), $dyn_g);
                $dyn_i = str_replace('#I#', $xlobj->getSheetByName($idata['sheet'])->getCell('I2')->getValue(), $dyn_h);
                $dyn_j = str_replace('#J#', $xlobj->getSheetByName($idata['sheet'])->getCell('J2')->getValue(), $dyn_i);
                $dyn_text = str_replace('#K#', $xlobj->getSheetByName($idata['sheet'])->getCell('K2')->getValue(), $dyn_j);

                $resp = $dyn_text;
            } else if ($filetype == 'CSV' && $ext == 'csv') {
                //CSV FILE
                $fh = fopen($filepath, 'r');
                $fdata = fgetcsv($fh, 0, "\r\n");

                $smstext = $idata['smstext'];
                $csvstr = $fdata[1]; //since first row would be column names
                $csvar = explode(",", $csvstr);
                $dyn_a = str_replace("#A#", $csvar[0], $smstext);
                $dyn_b = str_replace("#B#", $csvar[1], $dyn_a);
                $dyn_c = str_replace("#C#", $csvar[2], $dyn_b);
                $dyn_d = str_replace("#D#", $csvar[3], $dyn_c);
                $dyn_e = str_replace("#E#", $csvar[4], $dyn_d);
                $dyn_f = str_replace("#F#", $csvar[5], $dyn_e);
                $dyn_text = str_replace("#G#", $csvar[6], $dyn_f);
                $resp = $dyn_text;
            } else {
                return false;
            }

            return $resp;
        }
    }


    //6. Send SMS

    public function composeSMS()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        if (isset($_SESSION['pageResponse'])) {
            $data['pageResponse'] = $_SESSION['pageResponse'];
            unset($_SESSION['pageResponse']);
        }

        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Send SMS';

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];
        //get credit count rule for this route

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'id,sender_id'));

        //get all contact groups with number of contacts
        Doo::loadModel('ScUserContactGroups');
        $cgobj = new ScUserContactGroups;
        $cgobj->user_id = $_SESSION['user']['userid'];
        $gdata = Doo::db()->find($cgobj, array('select' => 'id,group_name,column_labels'));

        $grpdata = array();
        if (sizeof($gdata) > 0) {
            Doo::loadModel('ScUserContacts');
            $ucobj = new ScUserContacts;

            foreach ($gdata as $grp) {
                $colar = unserialize($grp->column_labels);
                $colstr = '<button data-colval="mobile" type="button" class="colsbtn btn btn-sm btn-default">mobile</button>';
                $colstr .= '<button data-colval="name" type="button" class="colsbtn btn btn-sm btn-default">name</button>';
                if ($colar['varC'] != '') $colstr .= '<button data-colval="' . $colar['varC'] . '" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varC'] . '</button>';
                if ($colar['varD'] != '') $colstr .= '<button data-colval="' . $colar['varD'] . '" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varD'] . '</button>';
                if ($colar['varE'] != '') $colstr .= '<button data-colval="' . $colar['varE'] . '" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varE'] . '</button>';
                if ($colar['varF'] != '') $colstr .= '<button data-colval="' . $colar['varF'] . '" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varF'] . '</button>';
                if ($colar['varG'] != '') $colstr .= '<button data-colval="' . $colar['varG'] . '" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varG'] . '</button>';


                $gd['id'] = $grp->id;
                $gd['name'] = $grp->group_name;
                $gd['colstr'] = $colstr;
                $gd['count'] = $ucobj->countContacts($grp->id);
                array_push($grpdata, $gd);
            }
        }
        $data['gdata'] = $grpdata;

        //get assigned phonebooks
        if ($_SESSION['user']['group'] == 'admin') {
            //get system phonebook data
            $pbobj = Doo::loadModel('ScPhonebookGroups', true);
            $pbobj->status = 1;
            $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id'));
        } else {
            //get assigned phonebook data
            $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
            $upbdbobj->user_id = $_SESSION['user']['userid'];
            $data['upbdb'] = Doo::db()->find($upbdbobj, array('limit' => 1));
            //--
            if ($data['upbdb'] && strlen($data['upbdb']->phonebook_ids) > 0) {
                $pbobj = Doo::loadModel('ScPhonebookGroups', true);
                $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id', 'where' => 'id IN (' . $data['upbdb']->phonebook_ids . ')'));
            }
        }

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //check if pending invoices
        // $invobj = Doo::loadModel('ScUsersDocuments', true);
        // $iopt['select'] = 'id';
        // $iopt['where'] = "shared_with = ".intval($_SESSION['user']['userid'])." AND type = 1 AND file_status IN (0,2) ";
        // $pending_invs = Doo::db()->find($invobj, $iopt);
        // if(sizeof($pending_invs) > 0){
        //     $data['notif_msg']["type"] = 'error';
        //     $data['notif_msg']["msg"] = 'SMS Campaigns will only be allowed once you settle the unpaid Invoices. Check Reports -> Doc Manager.';
        // }
        //get approved verified agents


        $data['page'] = 'Messaging';
        $data['current_page'] = 'composeSMS';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        if (Doo::conf()->gui_mode == 0) {
            $this->view()->renderc('client/composeSMS', $data);
        } else {
            $this->view()->renderc('client/composeSMSLite', $data);
        }

        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function composeMMS()
    {
        $this->isLogin();
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['staffRights']['msg']['sms'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        //panel sms permission
        if ($_SESSION['user']['group'] != 'admin' && $_SESSION['user']['panel_campaign_perm'] == '0') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        if (isset($_SESSION['pageResponse'])) {
            $data['pageResponse'] = $_SESSION['pageResponse'];
            unset($_SESSION['pageResponse']);
        }

        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Send MMS';

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];
        //get credit count rule for this route

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'id,sender_id'));

        //get all contact groups with number of contacts
        Doo::loadModel('ScUserContactGroups');
        $cgobj = new ScUserContactGroups;
        $cgobj->user_id = $_SESSION['user']['userid'];
        $gdata = Doo::db()->find($cgobj, array('select' => 'id,group_name,column_labels'));

        $grpdata = array();
        if (sizeof($gdata) > 0) {
            Doo::loadModel('ScUserContacts');
            $ucobj = new ScUserContacts;

            foreach ($gdata as $grp) {
                $colar = unserialize($grp->column_labels);
                $colstr = '<button data-colval="A" type="button" class="colsbtn btn btn-sm btn-default">mobile</button>';
                $colstr .= '<button data-colval="B" type="button" class="colsbtn btn btn-sm btn-default">name</button>';
                if ($colar['varC'] != '') $colstr .= '<button data-colval="C" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varC'] . '</button>';
                if ($colar['varD'] != '') $colstr .= '<button data-colval="D" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varD'] . '</button>';
                if ($colar['varE'] != '') $colstr .= '<button data-colval="E" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varE'] . '</button>';
                if ($colar['varF'] != '') $colstr .= '<button data-colval="F" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varF'] . '</button>';
                if ($colar['varG'] != '') $colstr .= '<button data-colval="G" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varG'] . '</button>';


                $gd['id'] = $grp->id;
                $gd['name'] = $grp->group_name;
                $gd['colstr'] = $colstr;
                $gd['count'] = $ucobj->countContacts($grp->id);
                array_push($grpdata, $gd);
            }
        }
        $data['gdata'] = $grpdata;

        //get assigned phonebooks
        if ($_SESSION['user']['group'] == 'admin') {
            //get system phonebook data
            $pbobj = Doo::loadModel('ScPhonebookGroups', true);
            $pbobj->status = 1;
            $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id'));
        } else {
            //get assigned phonebook data
            $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
            $upbdbobj->user_id = $_SESSION['user']['userid'];
            $data['upbdb'] = Doo::db()->find($upbdbobj, array('limit' => 1));
            //--
            if ($data['upbdb']->id && strlen($data['upbdb']->phonebook_ids) > 0) {
                $pbobj = Doo::loadModel('ScPhonebookGroups', true);
                $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id', 'where' => 'id IN (' . $data['upbdb']->phonebook_ids . ')'));
            }
        }

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //check if pending invoices
        // $invobj = Doo::loadModel('ScUsersDocuments', true);
        // $iopt['select'] = 'id';
        // $iopt['where'] = "shared_with = ".intval($_SESSION['user']['userid'])." AND type = 1 AND file_status IN (0,2) ";
        // $pending_invs = Doo::db()->find($invobj, $iopt);
        // if(sizeof($pending_invs) > 0){
        //     $data['notif_msg']["type"] = 'error';
        //     $data['notif_msg']["msg"] = 'SMS Campaigns will only be allowed once you settle the unpaid Invoices. Check Reports -> Doc Manager.';
        // }
        //get approved verified agents

        $data['page'] = 'Messaging';
        $data['current_page'] = 'composeMMS';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/composeMMS', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function composeRCS()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['rcs']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        if (isset($_SESSION['pageResponse'])) {
            $data['pageResponse'] = $_SESSION['pageResponse'];
            unset($_SESSION['pageResponse']);
        }

        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Send RCS Message';


        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'id,sender_id'));

        //get all contact groups with number of contacts
        Doo::loadModel('ScUserContactGroups');
        $cgobj = new ScUserContactGroups;
        $cgobj->user_id = $_SESSION['user']['userid'];
        $gdata = Doo::db()->find($cgobj, array('select' => 'id,group_name,column_labels'));

        $grpdata = array();
        if (sizeof($gdata) > 0) {
            Doo::loadModel('ScUserContacts');
            $ucobj = new ScUserContacts;

            foreach ($gdata as $grp) {
                $colar = unserialize($grp->column_labels);
                $colstr = '<button data-colval="A" type="button" class="colsbtn btn btn-sm btn-default">mobile</button>';
                $colstr .= '<button data-colval="B" type="button" class="colsbtn btn btn-sm btn-default">name</button>';
                if ($colar['varC'] != '') $colstr .= '<button data-colval="C" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varC'] . '</button>';
                if ($colar['varD'] != '') $colstr .= '<button data-colval="D" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varD'] . '</button>';
                if ($colar['varE'] != '') $colstr .= '<button data-colval="E" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varE'] . '</button>';
                if ($colar['varF'] != '') $colstr .= '<button data-colval="F" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varF'] . '</button>';
                if ($colar['varG'] != '') $colstr .= '<button data-colval="G" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varG'] . '</button>';


                $gd['id'] = $grp->id;
                $gd['name'] = $grp->group_name;
                $gd['colstr'] = $colstr;
                $gd['count'] = $ucobj->countContacts($grp->id);
                array_push($grpdata, $gd);
            }
        }
        $data['gdata'] = $grpdata;

        //get assigned phonebooks
        if ($_SESSION['user']['group'] == 'admin') {
            //get system phonebook data
            $pbobj = Doo::loadModel('ScPhonebookGroups', true);
            $pbobj->status = 1;
            $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id'));
        } else {
            //get assigned phonebook data
            $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
            $upbdbobj->user_id = $_SESSION['user']['userid'];
            $data['upbdb'] = Doo::db()->find($upbdbobj, array('limit' => 1));
            //--
            if ($data['upbdb']->id && strlen($data['upbdb']->phonebook_ids) > 0) {
                $pbobj = Doo::loadModel('ScPhonebookGroups', true);
                $data['pbdata'] = Doo::db()->find($pbobj, array('select' => 'id,group_name,contact_count', 'desc' => 'id', 'where' => 'id IN (' . $data['upbdb']->phonebook_ids . ')'));
            }
        }

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //check if pending invoices
        // $invobj = Doo::loadModel('ScUsersDocuments', true);
        // $iopt['select'] = 'id';
        // $iopt['where'] = "shared_with = ".intval($_SESSION['user']['userid'])." AND type = 1 AND file_status IN (0,2) ";
        // $pending_invs = Doo::db()->find($invobj, $iopt);
        // if(sizeof($pending_invs) > 0){
        //     $data['notif_msg']["type"] = 'error';
        //     $data['notif_msg']["msg"] = 'SMS Campaigns will only be allowed once you settle the unpaid Invoices. Check Reports -> Doc Manager.';
        // }


        $data['page'] = 'Messaging';
        $data['current_page'] = 'composeRCS';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/composeRCS', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getCreditCountRuleDetails()
    {
        $this->isLogin();
        $rid = intval($this->params['id']);
        if ($rid != 0) {
            //valid id
            Doo::loadModel('ScCreditCountRules');
            $obj = new ScCreditCountRules;
            $obj->id = $rid;
            $rule = Doo::db()->find($obj, array('select' => 'id,rule_name,normal_sms_rule,unicode_rule,special_chars_rule', 'limit' => 1));

            $normal_rule = json_decode($rule->normal_sms_rule, true);
            $unicode_rule = json_decode($rule->unicode_rule, true);
            $spcl_rule = json_decode($rule->special_chars_rule, true);

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

            echo json_encode(array('html' => $rule_html, 'text' => $normal_rule, 'unicode' => $unicode_rule, 'special' => $spcl_rule['counts']));
            exit;
        }
    }

    public function calculateSmsCost()
    {
        $this->isLogin();
        $sessionroutes = json_decode($_SESSION['plan']['routesniso'], true);

        if ($_POST['mode'] == 'sendsms') {
            $mobiles = json_decode(stripslashes($_POST['phones']));
            if (sizeof($mobiles) > 0) {
                //get assigned plan details and get all countries from it
                $planpfx = implode(',', array_keys($sessionroutes));
                //get coverage data for all
                if ($planpfx == 'x') {
                    $covqry = "SELECT country_code, CONCAT_WS('|', id, prefix, valid_lengths, timezone) as covdata FROM sc_coverage";
                } else {
                    $covqry = "SELECT country_code, CONCAT_WS('|', id, prefix, valid_lengths, timezone) as covdata FROM sc_coverage WHERE prefix IN ($planpfx)";
                }
                $cvdata = Doo::db()->fetchAll($covqry, null, PDO::FETCH_KEY_PAIR);

                $covidar = array();
                foreach ($cvdata as $iso => $cvinfo) {
                    $covid = explode("|", $cvinfo);
                    array_push($covidar, $covid[0]);
                }
                $covidstr = implode(",", $covidar);

                //prefixes
                //$pfxqry = "SELECT prefix, mccmnc FROM sc_ocpr_mapping WHERE coverage IN ($covidstr)";
                $pfxdata = []; //Doo::db()->fetchAll($pfxqry, null, PDO::FETCH_KEY_PAIR);
                //sms mccmnc plan pricing
                $mplanprcqry = 'SELECT mccmnc, CONCAT_WS("|", route_id, price) as route_and_cost FROM sc_mcc_mnc_plan_pricing WHERE plan_id =' . $_SESSION['plan']['id'];
                $prcdata = Doo::db()->fetchAll($mplanprcqry, null, PDO::FETCH_KEY_PAIR);

                //get route info for all the routes in volved in this sms plan
                $planroutes = $_SESSION['plan']['routes'];
                $routeqry = "SELECT id, smpp_list, route_config, blacklist_ids, credit_rule, sender_type, def_sender, max_sid_len, template_flag, active_time FROM sc_sms_routes WHERE id IN ($planroutes)";
                $rtdata = Doo::db()->fetchAll($routeqry, null, PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);

                $totalcost = 0;
                foreach ($mobiles as $mobile) {
                    $iso = DooSmppcubeHelper::getCountryIso($mobile);
                    if ($iso != '') {
                        //Based on ISO get coverage data for valid length and operator prefix length. Extract operator prefix from this
                        //get coverage details like prefix and NSN prefix length
                        $covdata = explode('|', $cvdata[$iso]);
                        //4. Based on operator prefix get MCCMNC code
                        $pfx = substr($mobile, strlen($covdata[1]) - 1, intval($covdata[3]));
                        $mccmnc = $pfxdata[$pfx];
                        //5. get price for the mcc mnc according to this plan
                        $routecostar = explode("|", $prcdata[$mccmnc]);
                        $persmsprice = floatval($routecostar[1]);
                        $routeid = intval($routecostar[0]);
                        if ($mccmnc == '0' || !$mccmnc || intval($routeid) == 0) {
                            //no mccmnc matched, use default route pricing
                            $routeid = !isset($sessionroutes[intval($covdata[1])]) ? $sessionroutes['x']['routeid'] : $sessionroutes[intval($covdata[1])]['routeid'];
                            $persmsprice = $rtdata[$routeid]['default_selling_price'];
                        }
                        $totalcost += $persmsprice;
                    }
                }
                $result['totalcontacts'] = sizeof($mobiles);
                $result['price'] = $totalcost;
                echo json_encode($result);
                exit;
            } else {
                $result['totalcontacts'] = 0;
                $result['price'] = 0;
                echo json_encode($result);
                exit;
            }
        }
        exit;
    }

    public function campaignSuccessRedirect()
    {
        $this->isLogin();
        $_SESSION['pageResponse'] = 1;
        //update credits in the session done at js
        return Doo::conf()->APP_URL . 'composeSMS';
    }

    public function spamRedirect()
    {
        //account is already likely blocked
        //ip is blocked as well
        //send to blocked account screen and destroy active session
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
        return Doo::conf()->APP_URL . 'blocked';
    }

    public function updateDLR()
    {

        // DLR URL e.g. http://server/app/getDLR/index.php?route_id=1&shoot_id=abcd&user_id=2&upline_id=1&umsgid=234432a45rfd&persmscount=2&personalize=1&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F

        $sentobj = Doo::loadModel('ScSentSms', true);
        Doo::loadModel('ScUsersCreditData');
        $lcobj = Doo::loadModel('ScLogsCredits', true);
        $ldrobj = Doo::loadModel('ScLogsDlrRefunds', true);
        $upermobj = Doo::loadModel('ScUsersPermissions', true);
        $drefobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
        $cbqobj = Doo::loadModel('ScApiCallbackQueue', true);

        //collect data
        $shoot_id = $_REQUEST['shoot_id'];
        $to = $_REQUEST['mobile'];
        $dlr = $_REQUEST['dlr'];
        $vendor_reply = $_REQUEST['vdlr'];
        //parse dlr
        $err = urldecode($vendor_reply);
        if (strpos($err, 'NACK') === false) {
            //look for err and the code is right next to it
            // preg_match("/err:(\d{3})/",$err,$ecar);
            // $errcode = $ecar[1];
            // above code was legacy. commented because it fails for error code of 4 digits
            $err_array = explode("err:", $err);
            $stat_array = explode("stat:", $err);
            $errcode = strtok($err_array[1], " ");
            $smppcode = strtok($stat_array[1], " ");
        } else {
            //break with '/' sign
            $ecar = explode("/", $err);
            $errcode = $ecar[1];
            $smppcode = 'NACK';
        }
        $data['dlr'] = $dlr;
        $data['vendor_dlr'] = $errcode;
        $vmsgid = $_REQUEST['vmsgid'];
        $routeid = intval($_REQUEST['rid']);
        $userid = intval($_REQUEST['uid']);

        $errcode_org = $errcode;

        if ($dlr == '1' && $errcode == '') $errcode = '000';
        if ($dlr == '2' && ($errcode == '' || $errcode == '000')) $errcode = '-4';
        if ($dlr == '16' && ($errcode == '' || $errcode == '000')) $errcode = '-4';
        if ($dlr == '8' && ($errcode == '' || $errcode == '000')) $errcode = '-6';

        //update dlr
        $sentobj->smpp_resp_code = $smppcode;
        $sentobj->dlr = $dlr;
        $sentobj->vendor_dlr = $errcode;
        $sentobj->vendor_msgid = $vmsgid;
        $sentobj->es_index_status = 1;


        if ($_REQUEST['personalize'] == "1") {
            $umsgid = $_REQUEST['umsgid'];
            $where = "`umsgid` = '$umsgid'";
        } else {
            $where = "`sms_shoot_id` = '$shoot_id' AND mobile =$to";
        }
        if ($dlr == '8') {
            $where .= " AND dlr = 0";
        }
        $res = Doo::db()->update($sentobj, array('where' => $where));
        echo json_encode($res);

        //get default dlr url if set
        $usetobj = Doo::loadModel('ScUsersSettings', true);
        $usetobj->user_id = $userid;
        $def_dlr_url = Doo::db()->find($usetobj, array('select' => 'default_dlr_url', 'limit' => 1))->default_dlr_url;

        $delv = 0;
        $fail = 0;
        $refc = 0;

        if ($dlr == '1') $delv = 1;
        if ($dlr == '2' || $dlr == '16') $fail = 1;


        if ($dlr != '8' && $errcode_org != '') {
            //NOT ACK
            //check if refund applicable
            $drefobj->route_id = $routeid;
            $drefobj->dlr_code = $errcode;
            $drefdata = Doo::db()->find($drefobj, array('limit' => 1, 'select' => 'action,param_value'));
            //process refund
            if ($drefdata->action == '1') {
                //check if user is allowed this refund
                $upermobj->user_id = $userid;
                $upermdata = Doo::db()->find($upermobj, array('limit' => 1, 'select' => 'perm_data'));

                if ($upermdata) {
                    $perms = unserialize($upermdata->perm_data);
                    if ($perms['ref'][$drefdata->param_value] == 'on') {
                        //refund allowed
                        $creobj = new ScUsersCreditData;
                        $subcre = new ScUsersCreditData;

                        $olducredits = $subcre->getRouteCredits($userid, $routeid);
                        $newavcredits = $creobj->doCreditTrans('credit', $userid, $routeid, $_REQUEST['pscnt']);

                        //make log entry
                        $lcobj->user_id = $userid;
                        $lcobj->timestamp = date(Doo::conf()->date_format_db);
                        $lcobj->amount = $_REQUEST['pscnt'];
                        $lcobj->route_id = $routeid;
                        $lcobj->credits_before = $olducredits;
                        $lcobj->credits_after = $newavcredits;
                        $lcobj->reference = 'Credit Refund (DLR)';
                        $lcobj->comments = 'Refund was made against DLR with following details:|| MSISDN: ' . $to . ' (ID: ' . $shoot_id . ')';
                        Doo::db()->insert($lcobj);

                        //make log entry
                        $ldrobj->user_id = $userid;
                        $ldrobj->sms_shoot_id = $shoot_id;
                        $ldrobj->mobile_no = $to;
                        $ldrobj->vendor_dlr = $errcode;
                        $ldrobj->refund_amt = $_REQUEST['pscnt'];
                        $ldrobj->refund_rule = $drefdata->refund_rule_id;
                        $ldrobj->timestamp = date(Doo::conf()->date_format_db);
                        Doo::db()->insert($ldrobj);

                        //make stats entry
                        $refc = $_REQUEST['pscnt'];
                    }
                }
            }
            //check if rerouting needed
            if ($drefdata->action == '2') {
                $reroute_id = intval($drefdata->param_value);
                //get smpp details for this route
                $rdqry = "SELECT smsc_id FROM sc_smpp_accounts WHERE id = (SELECT smpp_id FROM sc_sms_routes WHERE id = $reroute_id)";
                $smppobj = Doo::db()->fetchRow($rdqry, null, PDO::FETCH_OBJ);
                $smsc = $smppobj->smsc_id;
                //get data for sms submission
                if ($_REQUEST['dyn'] == 1) {
                    $smsobj = Doo::loadModel('ScSentSms', true);
                    $smsobj->sms_shoot_id = $shoot_id;
                    $smsobj->mobile = $to;
                    $smsobj->umsgid = $umsgid;
                    $smsdata = Doo::db()->find($smsobj, array('limit' => 1, 'select' => 'sms_type,sms_text'));
                    $smstype = unserialize($smsdata->sms_type);
                    $smstext = $smstype['main'] == 'text' ? htmlspecialchars_decode($smsdata->sms_text, ENT_QUOTES) : base64_encode(serialize($smsdata->sms_text));
                } else {
                    $smsobj = Doo::loadModel('ScSmsSummary', true);
                    $smsobj->sms_shoot_id = $shoot_id;
                    $smsdata = Doo::db()->find($smsobj, array('limit' => 1, 'select' => 'sms_type,sms_text'));
                    $smstype = unserialize($smsdata->sms_type);
                    $smstext = $smstype['main'] == 'text' ? htmlspecialchars_decode($smsdata->sms_text, ENT_QUOTES) : base64_encode(serialize($smsdata->sms_text));
                }

                $sms['sms_shoot_id'] = $shoot_id;
                $sms['route_id'] = $reroute_id;
                $sms['user_id'] = $userid;
                $sms['upline_id'] = $_REQUEST['upid'];
                $sms['umsgid'] = $_REQUEST['umsgid'];
                $sms['smscount'] = $_REQUEST['pscnt'];
                $sms['smsc'] = $smsc;
                $sms['senderid'] = $_REQUEST['sen'];
                $sms['contacts'] = $to;
                $sms['sms_type'] = $smsdata->sms_type;
                $sms['sms_text'] = $smstext;
                $sms['usertype'] = $_REQUEST['utype'];
                DooSmppcubeHelper::pushToKannel($sms);
            }
            //check if callback is set
            if ($def_dlr_url != false || (isset($_REQUEST['cb']) && $_REQUEST['cb'] != '')) {

                //get actual url
                if (!$def_dlr_url) {
                    $cbuobj = Doo::loadModel('ScApiCallbackUrls', true);
                    $cbuobj->id = intval($_REQUEST['cb']);
                    $dlr_url = Doo::db()->find($cbuobj, array('limit' => 1))->callback_url;
                } else {
                    $dlr_url = $def_dlr_url;
                }



                //get done date
                $smppformat = 'YmdHis';
                preg_match("/done date:(\d{14})/", $err, $dtar);
                if (empty($dtar)) {
                    $smppformat = 'ymdHis';
                    preg_match("/done date:(\d{12})/", $err, $dtar);
                }
                $dt = trim($dtar[1]);
                $dtobj = DateTime::createFromFormat($smppformat, $dt);
                if ($dtobj == false) {
                    $donedate = date('Y-m-d H:i:s');
                } else {
                    $donedate = $dtobj->format('Y-m-d H:i:s');
                }
                $cbqobj->user_id = $userid;
                $cbqobj->route_id = $routeid;
                $cbqobj->route_title = urldecode($_REQUEST['rnm']);
                $cbqobj->sms_shoot_id = $shoot_id;
                $cbqobj->sms_id = $vmsgid;
                $cbqobj->mobile = $to;
                $cbqobj->sender_id = urldecode($_REQUEST['sen']);
                $cbqobj->sms_count = intval($_REQUEST['pscnt']);
                $cbqobj->sms_sent_ts = date('Y-m-d H:i:s', urldecode($_REQUEST['sts']));
                $cbqobj->delivery_ts = $donedate;
                $cbqobj->dlr = $dlr;
                $cbqobj->vendor_dlr = $errcode;
                $cbqobj->operator_reply = $smppcode;
                $cbqobj->callback_url = $dlr_url;
                $cbqobj->attempts = 0;
                $cbqobj->mode = $_REQUEST["mode"];
                $cbqobj->status = 0;
                $cbid = Doo::db()->insert($cbqobj);
                echo 'Callback added id: ' . $cbid . '<br>';
            }
        }
        exit;
    }

    public function apiVendorDlr()
    {
        $provider = $this->params["provider"];
        $shoot_id = $_REQUEST['shoot'];
        $apidlr = $_REQUEST["MessageStatus"];
        $to = $_REQUEST["To"];
        $sentobj = Doo::loadModel('ScSentSms', true);
        $dlr = 0;
        if ($apidlr == 'delivered') {
            $dlr = 1;
            $errcode = '000';
            $smppcode = 'DELIVRD';
        }
        if ($apidlr == 'failed') {
            $dlr = 16;
            $errcode = '-4';
            $smppcode = 'REJECTD';
        }
        if ($apidlr == 'undelivered') {
            $dlr = 2;
            $errcode = '-4';
            $smppcode = 'UNDELIV';
        }
        if ($apidlr == 'sent') {
            $dlr = 8;
            $errcode = '-6';
            $smppcode = 'ACK';
        }

        //update dlr
        if ($dlr > 0) {
            $sentobj->smpp_resp_code = $smppcode;
            $sentobj->dlr = $dlr;
            $sentobj->vendor_dlr = $errcode;
            $sentobj->vendor_msgid = $_REQUEST["MessageSid"];
            $sentobj->es_index_status = 1;


            $where = "`sms_shoot_id` = '$shoot_id' AND mobile =$to";

            if ($dlr == '8') {
                $where .= " AND dlr = 0";
            }
            $res = Doo::db()->update($sentobj, array('where' => $where));
            echo 'API DLR updated' . json_encode($res);
        }
    }

    public function isSpamContent($text)
    {
        if (is_array($text)) {
            return false;
        } else {
            Doo::loadModel('ScSpamKeywords');
            $spam_obj = new ScSpamKeywords;
            $sp_status = $spam_obj->checkSpam($text);
            if (empty($sp_status)) {
                return false;
            } else {
                return $sp_status;
            }
        }
    }

    public function genDynamicPreview()
    {
        $this->isLogin();
        //get values
        $contact_file = $_POST['filename'];
        if ($contact_file != '') {
            //file is uploaded -- read all the mobile numbers in the array
            $exceldata = $this->readExcelFile($contact_file, 'preview', array('sheet' => $_POST['xlsheet'], 'col' => $_POST['xlcol'], 'dynamic' => 1, 'smstext' => $_POST["sms"]));
            if ($exceldata != false) {
                //correct format -- read the values in the array
                $msg = $exceldata;
            } else {
                //incorrect format -- return to sendsms page
                $msg = '[Err:' . $this->SCTEXT('Incorrect File format. Make sure the uploaded file is CSV, XLS or XLSX format.') . ']';
            }
        } else if (intval($_POST['selgrp']) != 0) {
            //contact group is selected
            $ucobj = Doo::loadModel('ScUserContacts', true);
            $ucobj->group_id = $_POST['selgrp'];
            $ucobj->user_id = $_SESSION['user']['userid'];
            $contact = Doo::db()->find($ucobj, array('limit' => 1));
            $dyn_a = str_replace("#A#", $contact->mobile, $_POST["sms"]);
            $dyn_b = str_replace("#B#", $contact->name, $dyn_a);
            $dyn_c = str_replace("#C#", $contact->varC, $dyn_b);
            $dyn_d = str_replace("#D#", $contact->varD, $dyn_c);
            $dyn_e = str_replace("#E#", $contact->varE, $dyn_d);
            $dyn_f = str_replace("#F#", $contact->varF, $dyn_e);
            $msg = str_replace("#G#", $contact->varG, $dyn_f);
        } else {
            //no file found
            $msg = '[Err: ' . $this->SCTEXT('No Contacts found. Please upload contacts or select a group for Dynamic SMS.') . ']';
        }
        echo $msg;
        exit;
    }

    public function getUserTlvList()
    {
        $this->isLogin();
        $routeid = intval($this->params['rid']);
        //get data for users tlv values
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $utlvobj = Doo::loadModel('ScUsersTlvValues', true);
        $utlvobj->user_id = $_SESSION['user']['userid'];
        $utlvobj->assoc_route = $routeid;
        $utlvs = Doo::db()->find($utlvobj);
        $data = array();
        foreach ($utlvs as $tlv) {
            //get title of TLV
            $tlv_type = $tlv->tlv_category;

            if (!is_array($data[$tlv_type])) $data[$tlv_type] = array();
            array_push($data[$tlv_type], array('title' => $tlv->tlv_title, 'value' => $tlv->tlv_value));
        }
        echo json_encode($data);
        exit;
    }

    //7. Reports

    public function date_sort($a, $b)
    {
        $ts1 = strtotime($a[1]);
        $ts2 = strtotime($b[1]);
        if ($ts1 == $ts2) {
            return 0;
        }
        return ($ts1 < $ts2) ? -1 : 1;
    }
    public function date_sort_rev($a, $b)
    {
        $ts1 = strtotime($a[1]);
        $ts2 = strtotime($b[1]);
        if ($ts1 == $ts2) {
            return 0;
        }
        return ($ts1 > $ts2) ? -1 : 1;
    }

    public function viewTransactionReports()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'Credit Transactions';

        $data['page'] = 'Reports';
        $data['current_page'] = 'transactions';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewCreditTrans', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getMyTransactions()
    {
        $this->isLogin();

        $columns = array(
            array('db' => 'transac_date', 'dt' => 0),
            array('db' => 'credits', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //get all transactions for this account
        $uobj = Doo::loadModel('ScUsers', true);
        if (intval($this->params['uid']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['uid']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
        }
        $uinfo = $uobj->getProfileInfo($uid);

        //date range
        $dr = $this->params['dr'];
        if ($uinfo->account_type == 1 || $uinfo->account_type == 2) {
            //get wallet details
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $wlobj->user_id = $uid;
            $walletid = Doo::db()->find($wlobj, array('limit' => 1))->id;
            if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
                //split the dates
                $datr = explode("-", urldecode($dr));
                $from = date('Y-m-d', strtotime(trim($datr[0])));
                $to = date('Y-m-d', strtotime(trim($datr[1])));

                if ($from == $to) {
                    $sWhere = "wallet_id = $walletid AND t_date LIKE '$from%'";
                } else {
                    $sWhere = "wallet_id = $walletid AND t_date BETWEEN '$from' AND '$to'";
                }
            } else {
                $sWhere = 'wallet_id = ' . $walletid;
            }
        } else {
            if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
                //split the dates
                $datr = explode("-", urldecode($dr));
                $from = date('Y-m-d', strtotime(trim($datr[0])));
                $to = date('Y-m-d', strtotime(trim($datr[1])));

                if ($from == $to) {
                    $sWhere = "transac_to_user = $uid AND transac_date LIKE '$from%'";
                } else {
                    $sWhere = "transac_to_user = $uid AND transac_date BETWEEN '$from' AND '$to'";
                }
            } else {
                $sWhere = 'transac_to_user = ' . $uid;
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

        if ($uinfo->account_type == 1 || $uinfo->account_type == 2) {
            //show wallet transactions
            Doo::loadModel('ScUsersWalletTransactions');
            $obj = new ScUsersWalletTransactions;
            $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
            $trans = Doo::db()->find($obj, $dtdata);
            $res = array();
            $res['iTotalRecords'] = $total;
            $res['iTotalDisplayRecords'] = $total;
            $res['aaData'] = array();
            foreach ($trans as $dt) {

                $button_str = '<a target="_blank" class="btn btn-primary" href="' . Doo::conf()->APP_URL . 'viewDocument/' . $dt->linked_invoice . '"><i class="m-r-xs fa fa-lg fa-file"></i> ' . $this->SCTEXT('View Invoice') . '</a>';

                $typestr = $dt->transac_type == '1' ? ' <span class="label label-success label-md">Credit</span>' : '<span class="label label-danger label-md">Debit</span>';

                $udata = $uobj->getProfileInfo($uinfo->upline_id, 'avatar, name, email');
                $ustr = '<div class="p-l-0 media-group-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="javascript:void();"><img src="' . $udata->avatar . '" alt=""></a></div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs theme-color">' . ucwords($udata->name) . '</a></h5>
                                                    <p style="font-size: 12px;font-style: Italic;">' . $udata->email . '</p>
                                                </div>
                                            </div>

                                        </div>';


                $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->t_date)), $typestr, Doo::conf()->currency . number_format(abs($dt->amount)), $ustr, $button_str);
                array_push($res['aaData'], $output);
            }
        } else {
            //credit transactions
            Doo::loadModel('ScUsersCreditTransactions');
            $obj = new ScUsersCreditTransactions;
            $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
            $trans = Doo::db()->find($obj, $dtdata);
            Doo::loadModel('ScSmsRoutes');
            $robj = new ScSmsRoutes;

            $res = array();
            $res['iTotalRecords'] = $total;
            $res['iTotalDisplayRecords'] = $total;
            $res['aaData'] = array();
            foreach ($trans as $dt) {

                $button_str = '<a target="_blank" class="btn btn-primary" href="' . Doo::conf()->APP_URL . 'viewDocument/' . $dt->invoice_id . '"><i class="m-r-xs fa fa-lg fa-file"></i> ' . $this->SCTEXT('View Invoice') . '</a>';

                $rstr = '<span class="label label-info label-md">' . $robj->getRouteData($dt->route_id, 'title')->title . '</span>';
                $typestr = $dt->type == '1' ? ' <span class="label label-success label-md">Credit</span>' : '<span class="label label-danger label-md">Debit</span>';

                $udata = $uobj->getProfileInfo($dt->transac_by, 'avatar, name, email');
                $ustr = '<div class="p-l-0 media-group-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="javascript:void();"><img src="' . $udata->avatar . '" alt=""></a></div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs theme-color">' . ucwords($udata->name) . '</a></h5>
                                                    <p style="font-size: 12px;font-style: Italic;">' . $udata->email . '</p>
                                                </div>
                                            </div>

                                        </div>';


                $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->transac_date)), $typestr, number_format($dt->credits), $rstr, $dt->transac_id, $ustr, $button_str);
                array_push($res['aaData'], $output);
            }
        }

        echo json_encode($res);
        exit;
    }

    public function showDlrSummary()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'DLR Summary';

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        $data['page'] = 'Reports';
        $data['current_page'] = 'dlr';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewDlrSummary', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getMySmsCampaigns()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        $viewacc = 0;

        $columns = array(
            array('db' => 'sms_text', 'dt' => 4),
            array('db' => 'count', 'dt' => 5)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);
        $uobj = Doo::loadModel('ScUsers', true);
        //get all campaigns for this account
        if (intval($this->params['uid']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['uid']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller

                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
            $viewacc = 1;
        }
        $data['uinfo'] = $uobj->getProfileInfo($uid);
        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "user_id = $uid AND submission_time LIKE '$from%' ";
            } else {
                $sWhere = "user_id = $uid AND submission_time BETWEEN '$from' AND '$to' ";
            }
        } else {
            $sWhere = 'user_id = ' . $uid . ' ';
        }
        //campaign
        $campid = intval($this->params['cid']);
        if ($campid > 0) {
            $sWhere =  $sWhere == '' ? 'campaign_id=' . $campid : $sWhere . ' AND campaign_id=' . $campid;
        }

        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND' . $dtdata['where'];
            }
        }
        if (intval($this->params['sort']) == 0) {
            $dtdata['desc'] = 'id';
        } elseif (intval($this->params['sort']) == 1) {
            $dtdata['asc'] = 'submission_time';
        } elseif (intval($this->params['sort']) == -1) {
            $dtdata['desc'] = 'submission_time';
        } elseif (intval($this->params['sort']) == 2) {
            $dtdata['asc'] = 'sent_time';
        } elseif (intval($this->params['sort']) == 3) {
            $dtdata['desc'] = 'sent_time';
        }

        Doo::loadModel('ScSmsSummary');
        $obj = new ScSmsSummary;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $cmpns = Doo::db()->find($obj, $dtdata);

        $robj = Doo::loadModel('ScSmsRoutes', true);
        $sidobj = Doo::loadModel('ScSenderId', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($cmpns as $dt) {

            $showdlrlink = $viewacc == 1 ? 'showUserDLR/' . $dt->user_id . '/' : 'showDLR/';
            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . $showdlrlink . $dt->sms_shoot_id . '">' . $this->SCTEXT('View DLR') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'globalFileDownload/dlr/' . $dt->sms_shoot_id . '">' . $this->SCTEXT('Download Reports') . '</a></li></ul></div>';

            $rstr = '<span class="label label-info label-md">' . $robj->getRouteData($dt->route_id, 'title')->title . '</span>';

            $smscat = json_decode($dt->sms_type, true);
            $stxtstr = '';
            $stypestr = '';
            $dbsmstext = Doo::conf()->sms_aes_encryption == "1" ? DooSmppcubeHelper::aesDecrypt($dt->sms_text) : $dt->sms_text;
            if ($smscat['main'] == 'text') {

                $stxtstr = '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dbsmstext) . '</div>';
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
                $stdata = json_decode(base64_decode($dbsmstext), true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <h5>' . $stdata['wap_title'] . '</h5>
                                                    <hr class="m-h-xs">

                                                     <span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span>

                                                    </div>';
                $stypestr = '<span class="label label-success label-md"><i class="fa fa-lg fa-globe m-r-xs"></i>WAP</span>';
            } elseif ($smscat['main'] == 'vcard') {
                $stdata = json_decode(base64_decode($dbsmstext), true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span>
                                                    </div>';
                $stypestr = '<span class="label label-primary label-md"><i class="fa fa-vcard m-r-xs"></i>vCard</span>';
            }

            $senderid = is_numeric($dt->sender_id) ? $sidobj->getName($dt->sender_id) : $dt->sender_id;
            $total_sms_sent = intval($dt->total_contacts) + intval($dt->dropped_contacts) + intval($dt->invalid_contacts) + intval($dt->blacklist_contacts);

            //scheduled data
            $schdata = json_decode($dt->schedule_data, true);
            if ($schdata['type'] == 1) {
                $schtime =  date(Doo::conf()->date_format_long_time, strtotime($schdata['schedule']['date'] . ' ' . $schdata['schedule']['timezone']));
            } else {
                $schtime =  date(Doo::conf()->date_format_long_time, strtotime($dt->submission_time));
            }

            if ($data['uinfo']->account_type == '0' || $data['uinfo']->account_type == '2') {
                $creditscharged = $data['uinfo']->account_type == '0' ? number_format($dt->total_cost) : Doo::conf()->currency . rtrim(number_format($dt->total_cost, 5), "0");
                $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->submission_time)), $schtime, $rstr, $senderid, $stypestr, $stxtstr, number_format($total_sms_sent) . '<i class="m-l-xs fa fa-lg fa-info-circle text-primary pop-over pointer dlrsumldr" data-shootid="' . $dt->sms_shoot_id . '" data-routeid="' . $dt->route_id . '" data-placement="right" data-content="Loading DLR Summary . . ."></i>', '<span class="label label-danger label-md">- ' . $creditscharged . '</span>', $button_str);
            } else {
                $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->submission_time)), $schtime, $senderid, $stypestr, $stxtstr, number_format($total_sms_sent), '<span class="label label-danger label-md">- ' . Doo::conf()->currency . rtrim(number_format($dt->total_cost, 5), "0") . '</span>', $button_str);
            }
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function showDLR()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        //get sms shoot id
        $shootid = $this->params['id'];
        //get queued sms count
        $queued = 0; //since we used redis no queue required
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
        $sobj->user_id = $_SESSION['user']['userid'];
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
        //check for tiny url
        $urlresponse = 0;
        $tinyurl = DooTextHelper::getTinyUrl($_SESSION['user']['userid']);
        $pos = strpos($data['sum']->sms_text, $tinyurl);

        if ($pos !== false) {
            $urlidf = substr($data['sum']->sms_text, $pos + strlen($tinyurl) + 1, 6);
            //check if personalize
            $turlobj = Doo::loadModel('ScShortUrlsMaster', true);
            $turlobj->url_idf = $urlidf;
            $urldata = Doo::db()->find($turlobj, array('select' => 'id,type', 'limit' => 1));

            if (intval($urldata->type) != 0) {
                $urlresponse = 1;
                //count total response received
                $sntobj2 = Doo::loadModel('ScSentSms', true);
                $data['urlrescount'] = intval(Doo::db()->find($sntobj2, array('select' => 'count(id) as totalresp', 'where' => "sms_shoot_id = '$shootid' AND url_visit_flag = 1", 'limit' => 1))->totalresp);
            }
        }

        $data['urlresponse'] = $urlresponse;

        //get total refunds for this campaign
        $rflobj = Doo::loadModel('ScLogsDlrRefunds', true);
        $rflobj->sms_shoot_id = $shootid;
        $data['reftotal'] = intval(Doo::db()->find($rflobj, array('select' => 'SUM(`refund_amt`) as total', 'limit' => 1))->total);

        //render
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['DLR Summary'] = Doo::conf()->APP_URL . 'showDlrSummary';
        $data['active_page'] = 'DLR Details';

        $data['page'] = 'Reports';
        $data['current_page'] = 'dlr_details';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/dlrDetails', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getDlrSummary()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }

        if (intval($this->params['id']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['id']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                $uobj = Doo::loadModel('ScUsers', true);
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
            $viewacc = 1;
        }

        //collect values
        $shootid = $_REQUEST['shootid'];
        $rid = intval($_REQUEST['routeid']);
        if ($rid == 0 && $_SESSION['user']['account_type'] == 0) {
            echo '-Invalid Route-';
            exit;
        }

        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        $rtobj->id = $rid;
        $rdata = Doo::db()->find($rtobj, array('limit' => 1, 'select' => 'smpp_list'));
        //get route custom dlr codes
        if ($rdata->smpp_list != '') {
            $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
            $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category', 'where' => 'smpp_id IN (' . $rdata->smpp_list . ')'));
        } else {
            $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
            $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category'));
        }

        //get all the dlrs of this campaign
        $sntobj = Doo::loadModel('ScSentSms', true);
        $sntobj->user_id = $uid;
        $sntobj->sms_shoot_id = $shootid;
        $smsdlrs = Doo::db()->find($sntobj, array('select' => 'vendor_dlr'));

        $cmap = function ($v) {
            return $v->vendor_dlr;
        };
        $dlrs = array_count_values(array_map($cmap, $smsdlrs)); //array with dlr code and total numbers

        $totalcontacts = sizeof($smsdlrs);

        $str = '';
        if (sizeof($smsdlrs) > 0) {
            $strarr = array();
            if (sizeof($dlrcodes) > 0) {
                foreach ($dlrs as $sd => $cnt) {
                    if (trim($sd) == "") {
                        $strarr['DLR Pending']['count'] = $cnt;
                        $strarr['DLR Pending']['cat'] = 'warning';
                        $strarr['DLR Pending']['code'] = "";
                    } else {
                        $dmap = function ($e) use ($sd) {
                            return $e->vendor_dlr_code == $sd;
                        };
                        $dlrcobj = array_filter($dlrcodes, $dmap);
                        $k = key($dlrcobj);
                        if ($dlrcodes[$k]->description != '') {
                            $dlrdesc = $dlrcodes[$k]->description;
                            $dlrcolor = $dlrcodes[$k]->category == '3' ? 'danger' : ($dlrcodes[$k]->category == '1' ? 'success' : 'warning');
                        } else {
                            $dcdata = $this->getSmppcubeDlrcodeDescription($sd);
                            $dlrdesc = $dcdata['desc'];
                            $dlrcolor = $dcdata['cat'];
                        }

                        if (trim($dlrdesc) == '') $dlrdesc = 'Unknown';
                        $strarr[$dlrdesc]['count'] += $cnt;
                        $strarr[$dlrdesc]['cat'] = $dlrcolor;
                        $strarr[$dlrdesc]['code'] = $sd;
                    }
                }
                //above loop was to aggregate summary by labels or decriptions so they do not repeat: looks good
                //below loop is to loop through final array and prepare display string

                foreach ($strarr as $desc => $dcnt) {
                    $cnt = $dcnt['count'];
                    $dlrcolor = $dcnt['cat'];
                    $per = ($cnt / $totalcontacts) * 100;

                    $str .= $_REQUEST['mode'] == 'popover' ? '<div class="closePO pointer clearfix p-xs">
                                                        <div class="col-md-9">
                                                               <span class="progtxt">' . $desc . '</span>
                                                        </div>
                                                        <div class="col-md-3 text-right">
                                                            <span class="label label-md label-' . $dlrcolor . '">' . number_format($cnt) . '</span>

                                                        </div>
                                                    </div>' : '<div class="clearfix">
                                                        <div class="col-md-9 p-t-xs">
                                                            <div class="m-b-xs progress progress-xxs "><div class="progress-bar progress-bar-striped active progress-bar-' . $dlrcolor . '" role="progressbar" aria-valuenow="' . intval($per) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . intval($per) . '%"></div></div>
                                                        </div>
                                                        <div class="col-md-3 text-right">
                                                            <span data-code="' . $dcnt['code'] . '" class="progtxt">' . $desc . ': <b>' . number_format($cnt) . '</b></span>
                                                        </div>
                                                    </div>';
                }
            } else {
                //no custom dlr code defined, use smppcube custom dlr codes definitions
                foreach ($dlrs as $sd => $cnt) {
                    $dcdata = $this->getSmppcubeDlrcodeDescription($sd);
                    $dlrdesc = $dcdata['desc'];
                    $dlrcolor = $dcdata['cat'];
                    $per = ($cnt / $totalcontacts) * 100;

                    $str .= $_REQUEST['mode'] == 'popover' ? '<div class="closePO pointer clearfix p-xs">
                                                        <div class="col-md-9">
                                                               <span class="progtxt">' . $dlrdesc . '</span>
                                                        </div>
                                                        <div class="col-md-3 text-right">
                                                            <span class="label label-md label-' . $dlrcolor . '">' . number_format($cnt) . '</span>

                                                        </div>
                                                    </div>' : '<div class="clearfix">
                                                        <div class="col-md-9 p-t-xs">
                                                            <div class="m-b-xs progress progress-xxs "><div class="progress-bar progress-bar-striped active progress-bar-' . $dlrcolor . '" role="progressbar" aria-valuenow="' . intval($per) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . intval($per) . '%"></div></div>
                                                        </div>
                                                        <div class="col-md-3 text-right">
                                                            <span class="progtxt">' . $dlrdesc . ': <b>' . number_format($cnt) . '</b></span>
                                                        </div>
                                                    </div>';
                }
            }
        } else {
            $str = 'Messages Still in Queue.';
        }
        //output
        echo $str;
        exit;
    }

    public function getSmppcubeDlrcodeDescription($code)
    {
        $output = array();
        switch ($code) {
            case '000':
                $output['desc'] = 'Handset Delivered';
                $output['cat'] = 'success';
                break;

            case '-1':
                $output['desc'] = 'Invalid Subscriber';
                $output['cat'] = 'pink';
                break;

            case '-2':
                $output['desc'] = 'Blacklist/Opted-out';
                $output['cat'] = 'danger';
                break;

            case '-3':
                $output['desc'] = 'Unavailable Sucscriber';
                $output['cat'] = 'info';
                break;

            case '-4':
                $output['desc'] = 'SMSC REJECT';
                $output['cat'] = 'danger';
                break;

            case '-5':
                $output['desc'] = 'BUFFERED';
                $output['cat'] = 'warning';
                break;

            case '-6':
                $output['desc'] = 'SMSC SUBMIT';
                $output['cat'] = 'warning';
                break;

            case '':
                $output['desc'] = 'DLR Pending';
                $output['cat'] = 'warning';
                break;
        }
        return $output;
    }

    public function getSmppcubeCustumDlrcode($code)
    {
        switch ($code) {
            case '1':
                $code = '000';
                break;

            case '2':
                $code = '-3';
                break;

            case '4':
                $code = '-5';
                break;

            case '8':
                $code = '-6';
                break;

            case '16':
                $code = '-4';
                break;
        }
        return $code;
    }

    public function getMySentSms()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        $uobj = Doo::loadModel('ScUsers', true);
        if (intval($this->params['uid']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['uid']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller
                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
            $viewacc = 1;
        }
        $data['uinfo'] = $uobj->getProfileInfo($uid);

        $columns = array(
            array('db' => 'mobile', 'dt' => 0),
            array('db' => 'sms_text', 'dt' => 5)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        Doo::loadModel('ScSentSms');
        $obj = new ScSentSms;
        $obj->user_id = $uid;
        $obj->sms_shoot_id = $this->params['id'];

        //echo '<pre>';var_dump($dtdata);

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $cmpns = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($cmpns);


        $sidobj = Doo::loadModel('ScSenderId', true);

        //get dlr codes
        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        $rtobj->id = $cmpns[0]->route_id;
        $rdata = Doo::db()->find($rtobj, array('limit' => 1, 'select' => 'smpp_list'));
        //get route custom dlr codes
        $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
        $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category', 'where' => 'smpp_id IN (' . $rdata->smpp_list . ')'));

        //get network and circle data
        $mccmncqry = "SELECT mccmnc, CONCAT(brand, '||', operator) as brandop FROM `sc_mcc_mnc_list`";
        $mccmncdata = Doo::db()->fetchAll($mccmncqry, null, PDO::FETCH_KEY_PAIR);

        //phonebook flag
        $pbflag = 0;
        if ($_SESSION['user']['group'] != 'admin') {
            $pbflagobj = Doo::loadModel('ScSmsSummary', true);
            $pbflagobj->sms_shoot_id = $this->params['id'];
            $pbflag = Doo::db()->find($pbflagobj, array('select' => 'hide_mobile', 'limit' => 1))->hide_mobile;
            //check the format of masking and if user is allowed to see at least the click reports
            $upbdbobj = Doo::loadModel('ScUsersPhonebookSettings', true);
            $upbdbobj->user_id = $_SESSION['user']['userid'];
            $upbdata = Doo::db()->find($upbdbobj, array('limit' => 1));
            $maskdata = $upbdata->id ? unserialize($upbdata->mask_pattern) : array('type' => 0, 'mpos' => -5, 'mlen' => 4);
            $clicktrack = $upbdata->id ? $upbdata->click_track : 0;
        }


        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        $smscat = json_decode($cmpns[0]->sms_type, true);

        if ($smscat['personalize'] == '1') {

            foreach ($cmpns as $dt) {

                $senderid = $dt->sender_id;
                $vdlr = $dt->vendor_dlr;
                $dmap = function ($e) use ($vdlr) {
                    return $e->vendor_dlr_code == $vdlr;
                };
                $dlrcobj = array_filter($dlrcodes, $dmap);
                $k = key($dlrcobj);
                if ($dlrcodes[$k]->description != '') {
                    $dlrdesc = $dlrcodes[$k]->description;
                    $dlrcolor = $dlrcodes[$k]->category == '3' ? 'danger' : ($dlrcodes[$k]->category == '1' ? 'success' : 'warning');
                } else {
                    $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                    $dlrdesc = $dcdata['desc'];
                    $dlrcolor = $dcdata['cat'];
                }
                //network and circle
                if ($dt->dlr != '-1') {
                    //get this for valid numbers only
                    $brand = "";
                    $operator = "";
                    if ($dt->mccmnc != 0) {
                        $brandstr = $mccmncdata[$dt->mccmnc];
                        $brandar = explode("||", $brandstr);
                        $brand = $brandar[0];
                        $operator = $brandar[1];
                    }
                }

                if ($dt->url_visit_platform != '') {
                    $pldata = unserialize($dt->url_visit_platform);
                }

                $dlrCodeTitle = $_SESSION['user']['group'] == 'admin' ? 'title="Code: "' . $dt->vendor_dlr . '"' : '';

                $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                $urlstr = $dt->url_visit_flag == 0 ? '' : '<i class="fa fa-check-circle m-l-sm text-success pointer pop-over" data-placement="right" data-trigger="hover" data-content="<b>Clicked On:</b> ' . date(Doo::conf()->date_format_med_time, strtotime($dt->url_visit_ts)) . '<br><b>Device:</b> ' . $pldata['system'] . '<br><b>Browser:</b> ' . $pldata['browser'] . '<br><b>IP: ' . $pldata['ip'] . '</b>"></i>';
                $dbsmstext = Doo::conf()->sms_aes_encryption == "1" ? DooSmppcubeHelper::aesDecrypt($dt->sms_text) : $dt->sms_text;
                if ($data['uinfo']->account_type == '0' || $data['uinfo']->account_type == '2') {
                    $creditscharged = $data['uinfo']->account_type == '0' ? number_format($dt->cost) : '<span class="label label-danger label-md">- ' . rtrim(Doo::conf()->currency . number_format($dt->cost, 5), "0") . '</span>';
                    $output = array($mobile . $urlstr, $brand, $operator, date(Doo::conf()->date_format_short_time, strtotime($dt->sent_time)), $senderid, '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dbsmstext) . '</div>', $creditscharged,  $dt->umsgid, $dt->smpp_resp_code, $this->getDlrDescription($dt->dlr), '<span ' . $dlrCodeTitle . ' class="pointer label label-sm label-' . $dlrcolor . '">' . $dlrdesc . '</span>');
                } else {
                    //get nw cr from db
                    $output = array($mobile . $urlstr, '<kbd>' . $dt->mccmnc . '</kbd>', $brand, $operator, date(Doo::conf()->date_format_short_time, strtotime($dt->sent_time)), $senderid, '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dbsmstext) . '</div>', number_format($dt->cost), '<span class="label label-danger label-md">- ' . rtrim(Doo::conf()->currency . number_format($dt->cost, 5), "0") . '</span>', $dt->umsgid, $dt->smpp_resp_code, $this->getDlrDescription($dt->dlr), '<span ' . $dlrCodeTitle . ' class="pointer label label-sm label-' . $dlrcolor . '">' . $dlrdesc . '</span>');
                }

                array_push($res['aaData'], $output);
            }
        } else {

            foreach ($cmpns as $dt) {
                $stxtstr = '';
                $dbsmstext = Doo::conf()->sms_aes_encryption == "1" ? DooSmppcubeHelper::aesDecrypt($dt->sms_text) : $dt->sms_text;
                if ($smscat['main'] == 'text') {
                    $stxtstr = '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dbsmstext) . '</div>';
                } elseif ($smscat['main'] == 'wap') {
                    $stdata = json_decode($dbsmstext, true);
                    $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <h5>' . $stdata['wap_title'] . '</h5>
                                                    <hr class="m-h-xs">

                                                     <span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span>

                                                    </div>';
                } elseif ($smscat['main'] == 'vcard') {
                    $stdata = json_decode($dbsmstext, true);
                    $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span>
                                                    </div>';
                }
                $senderid = $dt->sender_id;
                $vdlr = $dt->vendor_dlr;
                $dmap = function ($e) use ($vdlr) {
                    return $e->vendor_dlr_code == $vdlr;
                };
                $dlrcobj = array_filter($dlrcodes, $dmap);
                $k = key($dlrcobj);
                if ($dlrcodes[$k]->description != '') {
                    $dlrdesc = $dlrcodes[$k]->description;
                    $dlrcolor = $dlrcodes[$k]->category == '3' ? 'danger' : ($dlrcodes[$k]->category == '1' ? 'success' : 'warning');
                } else {
                    $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                    $dlrdesc = $dcdata['desc'];
                    $dlrcolor = $dcdata['cat'];
                }

                //network and circle
                if ($dt->dlr != '-1') {
                    //get this for valid numbers only
                    $brand = "";
                    $operator = "";
                    if ($dt->mccmnc != 0) {
                        $brandstr = $mccmncdata[$dt->mccmnc];
                        $brandar = explode("||", $brandstr);
                        $brand = $brandar[0];
                        $operator = $brandar[1];
                    }
                }

                $dlrCodeTitle = $_SESSION['user']['group'] == 'admin' ? 'title="Code: "' . $dt->vendor_dlr . '"' : '';

                $mobile = $pbflag == 0 ? $dt->mobile : ($clicktrack == 1 && $dt->url_visit_flag == 1 ? $dt->mobile : substr_replace($dt->mobile, str_repeat('x', $maskdata['mlen']), $maskdata['mpos'], $maskdata['mlen']));

                if ($dt->url_visit_platform != '') {
                    $pldata = unserialize($dt->url_visit_platform);
                }
                $creditscharged = $data['uinfo']->account_type == '0' ? number_format($dt->cost) : '<span class="label label-danger label-md">- ' . rtrim(Doo::conf()->currency . number_format($dt->cost, 5), "0") . '</span>';

                $urlstr = $dt->url_visit_flag == 0 ? '' : '<i class="fa fa-check-circle m-l-sm text-success pointer pop-over" data-placement="right" data-trigger="hover" data-content="<b>Clicked On:</b> ' . date(Doo::conf()->date_format_med_time, strtotime($dt->url_visit_ts)) . '<br><b>Device:</b> ' . $pldata['system'] . '<br><b>Browser:</b> ' . $pldata['browser'] . '<br><b>IP: ' . $pldata['ip'] . '</b>"></i>';
                if ($data['uinfo']->account_type == '0' || $data['uinfo']->account_type == '2') {

                    $output = array($mobile . $urlstr, $brand, $operator, date(Doo::conf()->date_format_short_time, strtotime($dt->sent_time)), $senderid, $stxtstr, $creditscharged, $dt->umsgid, $dt->smpp_resp_code, $this->getDlrDescription($dt->dlr), '<span ' . $dlrCodeTitle . ' class="pointer label label-sm label-' . $dlrcolor . '">' . $dlrdesc . '</span>');
                } else {
                    //get nw cr from db

                    $output = array($mobile . $urlstr, '<kbd>' . $dt->mccmnc . '</kbd>', $brand, $operator, date(Doo::conf()->date_format_short_time, strtotime($dt->sent_time)), $senderid, $stxtstr, $creditscharged, $dt->umsgid, $dt->smpp_resp_code, $this->getDlrDescription($dt->dlr), '<span ' . $dlrCodeTitle . ' class="pointer label label-sm label-' . $dlrcolor . '">' . $dlrdesc . '</span>');
                }
                array_push($res['aaData'], $output);
            }
        }
        echo json_encode($res);
        exit;
    }

    public function getDlrDescription($code)
    {
        switch ($code) {

            case '-1':
                $desc = 'Invalid';
                break;

            case '1':
                $desc = 'Delivered';
                break;

            case '2':
                $desc = 'Failed';
                break;

            case '4':
                $desc = 'Queued';
                break;

            case '8':
                $desc = 'Operator Submitted';
                break;

            case '16':
                $desc = 'Rejected';
                break;

            case '0':
                $desc = 'Pending';
                break;
        }
        return $desc;
    }

    public function resendCampaign()
    {
        $this->isLogin();
        if ($_SESSION['user']['subgroup'] == 'staff' && $_SESSION['staffRights']['msg']['sms'] != 'on') {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        $shootid = $this->params['id'];

        //get sms text, route, sender id, schedule data and sms-type
        $shobj = Doo::loadModel('ScSmsSummary', true);
        $shobj->sms_shoot_id = $shootid;
        $data['shdata'] = Doo::db()->find($shobj, array('limit' => 1, 'select' => 'id,sms_text,sms_type,route_id,sender_id,schedule_data,hide_mobile'));

        if (!$data['shdata']->id) {
            //invalid shoot id
        }

        //get all the contacts specified by mode
        $mode = $this->params['mode'];
        $sentobj = Doo::loadModel('ScSentSms', true);
        $sentobj->sms_shoot_id = $shootid;

        if ($mode == 'del') {
            //resend to delivered contacts
            $sentobj->dlr = 1;
        } elseif ($mode == 'fld') {
            //resend to failed contacts
            $sentobj->dlr = 2;
        } elseif ($mode == 'pen') {
            //resend to pending contacts
            $sentobj->dlr = 0;
        } else {
            //resend to all contacts except invalid and rejected
            $opt['where'] = 'dlr NOT IN (-1,16)';
        }

        $smstype = unserialize($data['shdata']->sms_type);
        if ($smstype['personalize'] == 1) {
            $opt['select'] = 'mobile, sms_text';
            $data['notif_msg']['type'] = 'info';
            $data['notif_msg']['msg'] = 'This is a personalized campaign, modifying SMS Text is not allowed.';
        } else {
            $opt['select'] = 'mobile';
        }

        $data['sentdata'] = Doo::db()->find($sentobj, $opt);


        //get all the data needed for compose sms page
        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];
        //get credit count rule for this route

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'id,sender_id'));

        //get all contact groups with number of contacts
        Doo::loadModel('ScUserContactGroups');
        $cgobj = new ScUserContactGroups;
        $cgobj->user_id = $_SESSION['user']['userid'];
        $gdata = Doo::db()->find($cgobj, array('select' => 'id,group_name,column_labels'));

        $grpdata = array();
        if (sizeof($gdata) > 0) {
            Doo::loadModel('ScUserContacts');
            $ucobj = new ScUserContacts;

            foreach ($gdata as $grp) {
                $colar = unserialize($grp->column_labels);
                $colstr = '<button data-colval="A" type="button" class="colsbtn btn btn-sm btn-default">mobile</button>';
                $colstr .= '<button data-colval="B" type="button" class="colsbtn btn btn-sm btn-default">name</button>';
                if ($colar['varC'] != '') $colstr .= '<button data-colval="C" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varC'] . '</button>';
                if ($colar['varD'] != '') $colstr .= '<button data-colval="D" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varD'] . '</button>';
                if ($colar['varE'] != '') $colstr .= '<button data-colval="E" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varE'] . '</button>';
                if ($colar['varF'] != '') $colstr .= '<button data-colval="F" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varF'] . '</button>';
                if ($colar['varG'] != '') $colstr .= '<button data-colval="G" type="button" class="colsbtn btn btn-sm btn-default">' . $colar['varG'] . '</button>';


                $gd['id'] = $grp->id;
                $gd['name'] = $grp->group_name;
                $gd['colstr'] = $colstr;
                $gd['count'] = $ucobj->countContacts($grp->id);
                array_push($grpdata, $gd);
            }
        }
        $data['gdata'] = $grpdata;

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //breadcrums
        $data['links']['DLR Summary'] = Doo::conf()->APP_URL . 'showDlrSummary';
        $data['links']['DLR Details'] = Doo::conf()->APP_URL . 'showDLR/' . $shootid;
        $data['active_page'] = 'Resend Campaign';

        $data['page'] = 'Reports';
        $data['current_page'] = 'resend_campaign';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/resendCampaign', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function smsStats()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api'] && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        $uid = $_SESSION['user']['userid'];
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //get all users for admin, get downline for reseller/staff and nothing for client accounts
        $userobj = Doo::loadModel('ScUsers', 'true');
        if ($_SESSION['user']['subgroup'] == "admin") {
            $data['users'] = Doo::db()->find($userobj, array('select' => 'user_id, name, category, avatar, email, mobile'));
        }
        if ($_SESSION['user']['subgroup'] == "reseller" || $_SESSION['user']['subgroup'] == "staff") {
            $data['users'] = Doo::db()->find($userobj, array('select' => 'user_id, name, category, avatar, email, mobile', 'where' => 'user_id = ' . $_SESSION['user']['userid'] . ' OR upline_id =' . $_SESSION['user']['userid']));
        }

        //get all routes for admin, assigned routes for other accounts
        if ($_SESSION['user']['subgroup'] == "admin") {
            $routeobj = Doo::loadModel('ScSmsRoutes', true);
            $data['routes'] = Doo::db()->find($routeobj, array('select' => 'id, title'));
            //get all smpp for admin only
            $smppobj = Doo::loadModel('ScSmppAccounts', true);
            $data['smpp'] = Doo::db()->find($smppobj, array('select' => 'title, smsc_id, provider'));
        }

        //get all smpp clients for admin, self smpp client accounts for others
        $smppclientobj = Doo::loadModel('ScSmppClients', true);
        if ($_SESSION['user']['subgroup'] == "admin") {
            $data['smppclients'] = Doo::db()->find($smppclientobj, array('select' => 'system_id'));
        } else {
            $data['smppclients'] = Doo::db()->find($smppclientobj, array('select' => 'system_id', 'where' => 'user_id =' . $_SESSION['user']['userid']));
        }

        //get all senderid for admin and assigned sender id for other accounts
        $senderidobj = Doo::loadModel('ScSenderId', true);
        if ($_SESSION['user']['subgroup'] == "admin") {
            $data['senderids'] = Doo::db()->find($senderidobj, array('select' => 'DISTINCT(sender_id)'));
        } else {
            $data['senderids'] = Doo::db()->find($senderidobj, array('select' => 'sender_id', 'where' => 'req_by =' . $_SESSION['user']['userid']));
        }


        $data['page'] = 'Reports';
        $data['current_page'] = 'stats';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/smsStats', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function manageDocs()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Documents';

        $data['page'] = 'Reports';
        $data['current_page'] = 'docs';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageDocs', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getUserDocs()
    {
        $this->isLogin();
        $type = intval($this->params['type']);
        $dates = $this->params['dr'];
        $limit = $this->params['limit'];
        if (!$dates) {
            $dates = 'Select Date';
        }
        if (!$limit) {
            $limit = '0,10';
        }
        Doo::loadModel('ScUsersDocuments');
        $obj = new ScUsersDocuments;
        $data = $obj->getDocsByDate($_SESSION['user']['userid'], $type, $dates, $limit);
        $str = '';
        $more = 1;

        //prepare the data

        if ($type == 1) {
            //invoice
            foreach ($data as $dt) {
                switch (intval($dt->file_status)) {
                    case 0:
                        $status = '<span class="label label-warning label-md"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . $this->SCTEXT('Due') . '</span>';
                        break;

                    case 1:
                        $status = '<span class="label label-success label-md"><i class="fa fa-lg fa-check-circle m-r-sx"></i> ' . $this->SCTEXT('Paid') . '</span>';
                        break;

                    case 2:
                        $status = '<span class="label label-danger label-md"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . $this->SCTEXT('Overdue') . '</span>';
                        break;

                    case 3:
                        $status = '<span class="label label-info label-md"><i class="fa fa-lg fa-info-circle m-r-sx"></i> ' . $this->SCTEXT('Cancelled') . '</span>';
                        break;
                }
                $fdt = unserialize($dt->file_data);
                $cur = isset($fdt['currency']) && $fdt['currency'] != '' ? $fdt['currency'] : Doo::conf()->currency_name;
                $str .= '<div class="col-md-2 docmgr-files">
                                                    <span class="dmfilestatus">
                                                        ' . $status . '
                                                    </span>
                                                    <div class="widget m-b-0">
                                                        <div class="widget-body text-center">
                                                            <div class="big-icon m-b-md"><i class="fa fa-5x fa-file-text"></i></div>
                                                            <h6 title="' . $dt->filename . '" class="pointer m-b-md">' . DooTextHelper::limitChar($dt->filename, 18) . ' <i class="fa fa-lg fa-info-circle text-primary pop-over m-l-xs pointer" data-content="<span class=\'label label-info m-r-sm\'>' . $this->SCTEXT('Amount') . '</span> ' . number_format($fdt['grand_total']) . ' ' . $cur . ' <br> <span class=\'label label-info m-r-sm\'>' . $this->SCTEXT('Created') . '</span> ' . date(Doo::conf()->date_format_long, strtotime($dt->created_on)) . '" data-trigger="hover" data-placement="top"></i> </h6>

                                                            <a href="' . Doo::conf()->APP_URL . 'viewDocument/' . $dt->id . '" class="btn p-v-sm btn-xs btn-info">' . $this->SCTEXT('View') . '</a>';
                if ($dt->owner_id == $_SESSION['user']['userid']) {
                    $str .= '<a href="javascript:void(0);" data-docid="' . $dt->id . '" class="deleteDoc btn p-v-sm m-l-xs btn-xs btn-danger">' . $this->SCTEXT('Delete') . '</a>';
                }
                $str .= '
                                                        </div>
                                                    </div>
                                                </div>';
            }
        }


        if ($type == 2) {
            //agreements
            foreach ($data as $dt) {
                switch (intval($dt->file_status)) {
                    case 0:
                        $status = '<span class="label label-warning label-md"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . $this->SCTEXT('Pending') . '</span>';
                        break;

                    case 1:
                        $status = '<span class="label label-success label-md"><i class="fa fa-lg fa-check-circle m-r-sx"></i> ' . $this->SCTEXT('Approved') . '</span>';
                        break;

                    case 2:
                        $status = '<span class="label label-danger label-md"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . $this->SCTEXT('Declined') . '</span>';
                        break;
                }
                $mime = $dt->file_data;
                if (in_array($mime, array('text/plain', 'text/csv', 'text/x-csv'))) {
                    $ftype = 'fa-file-text';
                } elseif ($mime == 'application/pdf') {
                    $ftype = 'fa-file-pdf';
                } elseif (in_array($mime, array('application/zip', 'application/x-compressed', 'application/x-zip-compressed'))) {
                    $ftype = 'fa-file-archive';
                } elseif (in_array($mime, array('application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))) {
                    $ftype = 'fa-file-excel';
                } elseif (in_array($mime, array('image/png', 'image/jpeg', 'image/gif'))) {
                    $ftype = 'fa-file-image';
                } else {
                    $ftype = 'fa-file';
                }
                $str .= '<div class="col-md-2 docmgr-files">
                                                    <span class="dmfilestatus">
                                                        ' . $status . '
                                                    </span>
                                                    <div class="widget m-b-0">
                                                        <div class="widget-body text-center">
                                                            <div class="big-icon m-b-md"><i class="fa fa-5x ' . $ftype . '"></i></div>
                                                            <h6 title="' . $dt->filename . '" class="pointer m-b-md">' . DooTextHelper::limitChar($dt->filename, 18) . ' <i class="fa fa-lg fa-info-circle text-primary pop-over m-l-xs pointer" data-content="<span class=\'label label-info m-r-sm\'>' . $this->SCTEXT('Created') . '</span> ' . date(Doo::conf()->date_format_long, strtotime($dt->created_on)) . '" data-trigger="hover" data-placement="top"></i> </h6>

                                                            <a href="' . Doo::conf()->APP_URL . 'viewDocument/' . $dt->id . '" class="btn p-v-sm btn-xs btn-info">' . $this->SCTEXT('View') . '</a>';
                if ($dt->owner_id == $_SESSION['user']['userid']) {
                    $str .= '<a href="javascript:void(0);" data-docid="' . $dt->id . '" class="deleteDoc btn p-v-sm btn-xs m-l-xs btn-danger">' . $this->SCTEXT('Delete') . '</a>';
                }
                $str .= '
                                                        </div>
                                                    </div>
                                                </div>';
            }
        }

        if ($type == 3) {
            //other docs
            foreach ($data as $dt) {
                $mime = $dt->file_data;
                if (in_array($mime, array('text/plain', 'text/csv', 'text/x-csv'))) {
                    $ftype = 'fa-file-text';
                } elseif ($mime == 'application/pdf') {
                    $ftype = 'fa-file-pdf-o';
                } elseif (in_array($mime, array('application/zip', 'application/x-compressed', 'application/x-zip-compressed'))) {
                    $ftype = 'fa-file-archive-o';
                } elseif (in_array($mime, array('application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))) {
                    $ftype = 'fa-file-excel-o';
                } elseif (in_array($mime, array('image/png', 'image/jpeg'))) {
                    $ftype = 'fa-file-image-o';
                } else {
                    $ftype = 'fa-file-o';
                }

                $str .= '<div class="col-md-2 docmgr-files">
                                                    <span class="dmfilestatus">
                                                        ' . $status . '
                                                    </span>
                                                    <div class="widget m-b-0">
                                                        <div class="widget-body text-center">
                                                            <div class="big-icon m-b-md"><i class="fa fa-5x ' . $ftype . '"></i></div>
                                                            <h6 title="' . $dt->filename . '" class="pointer m-b-md">' . DooTextHelper::limitChar($dt->filename, 18) . ' <i class="fa fa-lg fa-info-circle text-primary pop-over m-l-xs pointer" data-content="<span class=\'label label-info m-r-sm\'>' . $this->SCTEXT('Created') . '</span> ' . date(Doo::conf()->date_format_long, strtotime($dt->created_on)) . '" data-trigger="hover" data-placement="top"></i> </h6>

                                                            <a href="' . Doo::conf()->APP_URL . 'viewDocument/' . $dt->id . '" class="btn p-v-sm btn-xs btn-info">' . $this->SCTEXT('View') . '</a>';
                if ($dt->owner_id == $_SESSION['user']['userid']) {
                    $str .= '<a href="javascript:void(0);" data-docid="' . $dt->id . '" class="deleteDoc btn p-v-sm btn-xs m-l-xs btn-danger">' . $this->SCTEXT('Delete') . '</a>';
                }
                $str .= '
                                                        </div>
                                                    </div>
                                                </div>';
            }
        }


        if ($str == '') {

            $str = '<div align="center">' . $this->SCTEXT('No documents to show') . '</div>';
            $limit = '0,10';
            $more = 0;
        } else {
            //increase the limit
            $ctrar = explode(",", $limit);
            $ctr = intval($ctrar[0]);
            $ctr = $ctr + 10;
            $limit = $ctr . ',10';
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

    public function addNewDocument()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Documents'] = Doo::conf()->APP_URL . 'manageDocs';
        $data['active_page'] = 'Add New Document';

        $data['page'] = 'Reports';
        $data['current_page'] = 'add_doc';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addNewDoc', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveDocument()
    {
        $this->isLogin();
        //collect values
        $title = DooTextHelper::cleanInput($_POST['docname'], ' ', 0);
        $type = intval($_POST['doctype']);
        $remarks = htmlspecialchars($_POST['docrmk']);
        $filename = $_POST['uploadedFiles'][0];

        if ($_SESSION['user']['group'] == 'client') {
            $type = $type < 2 ? 3 : $type; //only agreement and normal doc upload allowed
        }

        $dobj = Doo::loadModel('ScUsersDocuments', true);

        //make entry
        if ($type == 1) {
            //invoice, never manually added
        }

        if ($type == 2) {
            //agreement
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, Doo::conf()->global_upload_dir . $filename);
            finfo_close($finfo);

            $dobj->filename = $title;
            $dobj->type = 2;
            $dobj->location = $filename;
            $dobj->owner_id = $_SESSION['user']['userid'];
            $dobj->created_on = date(Doo::conf()->date_format_db);
            $dobj->shared_with = $_SESSION['manager']['id'];
            $dobj->file_data = $mimetype;
            $dobj->file_status = 0;
            $dobj->init_remarks = $remarks;
            $docid = Doo::db()->insert($dobj);

            $msg = 'New agreement was successfully submitted';

            //notify account manager
            $alobj = Doo::loadModel('ScUserNotifications', true);
            $alobj->addAlert($_SESSION['manager']['id'], 'info', Doo::conf()->new_agreement_upload . ' User: ' . $_SESSION['user']['name'], 'viewDocument/' . $docid);
        }

        if ($type == 3) {
            //normal document

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, Doo::conf()->global_upload_dir . $filename);
            finfo_close($finfo);

            $dobj->filename = $title;
            $dobj->type = 3;
            $dobj->location = $filename;
            $dobj->owner_id = $_SESSION['user']['userid'];
            $dobj->created_on = date(Doo::conf()->date_format_db);
            if ($_SESSION['user']['group'] != 'client') {
                $dobj->shared_with = $_POST['sharewith'];
            }
            $dobj->file_data = $mimetype;
            $dobj->file_status = 0;
            $dobj->init_remarks = $remarks;
            $docid = Doo::db()->insert($dobj);

            $msg = 'New document successfully uploaded';
        }

        //log activity
        $actData['activity_type'] = 'DOCUMENT UPLOAD';
        $actData['activity'] = Doo::conf()->new_document_upload . '|| DOCID: ' . $docid;
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);



        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageDocs';
    }

    public function viewDocument()
    {
        $this->isLogin();
        $docid = intval($this->params['id']);

        if ($docid == 0) {
            return array('/denied', 'internal');
        }

        //get payment mode allowed
        $userpobj = Doo::loadModel('ScUsers', true);
        $userpobj->user_id = $_SESSION['user']['userid'];
        $data['payment_perm'] = intval(Doo::db()->find($userpobj, array('select' => 'payment_perm', 'limit' => 1))->payment_perm);

        //get document details
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $docid;
        $docdata = Doo::db()->find($docobj, array('limit' => 1));
        $data['docdata'] = $docdata;


        //check if user is owner or not
        if ($docdata->owner_id == $_SESSION['user']['userid']) {
            $data['owner'] = 'yes';
            //get profiles of all the users associated with this doc
            $uobj = Doo::loadModel('ScUsers', true);
            if ($docdata->shared_with != '') {
                $uopt['where'] = "user_id IN ($docdata->shared_with)";
                $uopt['select'] = 'user_id, name, avatar, category, mobile, email';
                $udata = Doo::db()->find($uobj, $uopt);
            }
        } else {
            $usar = explode(",", $docdata->shared_with);
            if (in_array($_SESSION['user']['userid'], $usar) || $_SESSION['user']['group'] == 'admin') {
                $data['owner'] = 'no';
                //remove logged in user id from list
                $key = array_search($_SESSION['user']['userid'], $usar);
                unset($usar[$key]);
                array_push($usar, $docdata->owner_id);
                $ulist = implode(",", $usar);
                //get profiles of all the users associated with this doc
                $uobj = Doo::loadModel('ScUsers', true);
                if ($docdata->shared_with != '') {
                    $uopt['where'] = "user_id IN ($ulist)";
                    $uopt['select'] = 'user_id, name, avatar, category, upline_id, email';
                    $udata = Doo::db()->find($uobj, $uopt);
                }
            } else {
                //user is not allowed to view this file
                //notify as a security concern
                $alobj = Doo::loadModel('ScUserNotifications', true);
                $alobj->addAlert(1, 'danger', 'Potential URL Tampering:' . Doo::conf()->user_url_tamper, 'viewUserAccount/' . $_SESSION['user']['userid']);

                //redirect
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'You are not allowed to view this file';
                return Doo::conf()->APP_URL . 'manageDocs';
            }
        }

        $data['udata'] = isset($udata) ? $udata : []; //list of associated users other than logged in user


        //get all the remarks
        $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
        $data['comments'] = Doo::db()->find($drobj, array('where' => 'file_id=' . $docid));

        //if invoice, get the company info for billing
        if ($docdata->type == 1) {
            $ucobj = Doo::loadModel('ScUsersCompany', true);

            $data['reseller_cdata'] = $ucobj->getDataByUser($docdata->owner_id);
            $data['user_cdata'] = $ucobj->getDataByUser($docdata->shared_with);
            //echo '<pre>';var_dump(unserialize($data['reseller_cdata']->c_payment));die;
        }

        //if owner, get all the users with whom the doc can be shared
        if ($data['owner'] == 'yes') {
            $uopt['where'] = "upline_id = " . $_SESSION['user']['userid'];
            $uopt['select'] = 'user_id, name, avatar, category, upline_id, email';
            $data['shareusers'] = Doo::db()->find($uobj, $uopt);
        }

        //render
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage Documents'] = Doo::conf()->APP_URL . 'manageDocs';
        $data['active_page'] = 'View Document';

        $data['page'] = 'Reports';
        $data['current_page'] = 'view_doc';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewDoc', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function postSharedUsers()
    {
        $this->isLogin();
        $docid = intval($_POST['sdocid']);
        if ($docid == 0) {
            //invalid id
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid File';
            return Doo::conf()->APP_URL . 'manageDocs';
        }
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $docid;
        $docobj->shared_with = implode(",", $_POST['sharedusr']);
        Doo::db()->update($docobj, array('where' => 'owner_id=' . $_SESSION['user']['userid']));

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Document sharing was changed successfully';
        return Doo::conf()->APP_URL . 'viewDocument/' . $docid;
    }

    public function rmvSharedUsr()
    {
        $this->isLogin();
        $docid = intval($_POST['docid']);
        $uid = intval($_POST['uid']);
        if ($docid == 0 || $uid == 0) {
            //invalid id
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid File';
            return Doo::conf()->APP_URL . 'manageDocs';
        }
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $docid;
        $docdata = Doo::db()->find($docobj, array('limit' => 1));

        if ($docdata->owner_id != $_SESSION['user']['userid']) {
            //invalid operation
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Sharing not allowed';
            return Doo::conf()->APP_URL . 'viewDocument/' . $docid;
        }

        $suar = explode(",", $docdata->shared_with);

        $key = array_search($uid, $suar);
        unset($suar[$key]);

        $docobj->shared_with = implode(",", $suar);
        Doo::db()->update($docobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'User was removed from the shared list.';
        return Doo::conf()->APP_URL . 'viewDocument/' . $docid;
    }

    public function postFileComment()
    {
        $this->isLogin();
        $docid = intval($_POST['docid']);
        $cmt = htmlspecialchars($_POST['doc_comment']);
        if ($docid == 0) {
            //invalid id
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid File';
            return Doo::conf()->APP_URL . 'manageDocs';
        }
        $dcobj = Doo::loadModel('ScUsersDocumentRemarks', true);
        $dcobj->user_id = $_SESSION['user']['userid'];
        $dcobj->file_id = $docid;
        $dcobj->remark_text = $cmt;
        Doo::db()->insert($dcobj);

        //notify all associated users
        $udobj = Doo::loadModel('ScUsersDocuments', true);
        $usrar = $udobj->getAssociatedUsers($docid);

        //send alerts to all except logged in user
        if (($key = array_search($_SESSION['user']['userid'], $usrar)) !== false) {
            unset($usrar[$key]);
        }
        if (sizeof($usrar) > 0) {

            $alobj = Doo::loadModel('ScUserNotifications', true);
            foreach ($usrar as $uid) {
                $alobj->addAlert($uid, 'info', Doo::conf()->doc_comment_post . ' BY: ' . $_SESSION['user']['name'], 'viewDocument/' . $docid);
            }
        }


        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Comment was successfully posted';
        return Doo::conf()->APP_URL . 'viewDocument/' . $docid;
    }

    public function documentReupload()
    {
        $this->isLogin();
        $docid = intval($_POST['rdocid']);
        if ($docid == 0) {
            //invalid id
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid File';
            return Doo::conf()->APP_URL . 'manageDocs';
        }

        $filename = $_POST['uploadedFiles'][0];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, Doo::conf()->global_upload_dir . $filename);
        finfo_close($finfo);

        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->file_data = $mimetype;
        $docobj->location = $filename;
        $docobj->status = 0; //for agreements: set status pending
        Doo::db()->update($docobj, array('where' => 'id = ' . $docid . ' AND owner_id=' . $_SESSION['user']['userid']));

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Document was re-uploaded successfully';
        return Doo::conf()->APP_URL . 'viewDocument/' . $docid;
    }

    public function deleteDocument()
    {
        $this->isLogin();
        $docid = intval($this->params['id']);
        if ($docid == 0) {
            //invalid id
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid File';
            return Doo::conf()->APP_URL . 'manageDocs';
        }

        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $docid;
        $docdata = Doo::db()->find($docobj, array('select' => 'location, owner_id', 'limit' => 1));

        if ($docdata->owner_id != $_SESSION['user']['userid']) {
            //invalid operation
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            return Doo::conf()->APP_URL . 'manageDocs';
        }

        //delete file
        if ($docdata->location != '') {
            unlink(Doo::conf()->global_upload_dir . $docdata->location);
        }

        Doo::db()->delete($docobj);

        //delete remarks
        $drobj = Doo::loadModel('ScUsersDocumentRemarks', true);
        Doo::db()->delete($drobj, array('where' => 'file_id=' . $docid));

        //log user activity
        $actData['activity_type'] = 'DOCUMENT DELETE';
        $actData['activity'] = Doo::conf()->doc_delete . '|| DOCID: ' . $docid;
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Document was deleted successfully';
        //redirect done via js
    }

    public function smsArchive()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api'] && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'SMS Archive';


        $data['page'] = 'Reports';
        $data['current_page'] = 'smsarchive';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/smsArchive', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getArchivedFiles()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api'] && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScJobsDownload');
        $obj = new ScJobsDownload;
        $obj->user_id = $_SESSION['user']['userid'];
        $obj->mode = "archive";
        $tkts = Doo::db()->find($obj);
        $total = count($tkts);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($tkts as $dt) {
            if ($dt->status == 1) {
                $status = '<span class="label label-success">' . $this->SCTEXT('Completed') . '</span>';

                $filename = $dt->file_name == "empty" ? '-' . $this->SCTEXT('No Records Found') . '-' : '<a class="btn btn-info" target="_blank" href="' . Doo::conf()->global_export_dir . $dt->file_name . '"><i class="fa fa-lg fa-cloud-download-alt m-r-xs"></i>' . $this->SCTEXT('Download File') . '</a>';
            } else {
                $status = '<span class="label label-primary">' . $this->SCTEXT('In Progress') . ' ...</span>';
                $filename = 'N/A';
            }
            $dtrange = json_decode($dt->meta_data, true);

            $output = array($ctr, date(Doo::conf()->date_format_long_time, strtotime($dt->added_on)), $dtrange["date_range"], $status, $filename);
            array_push($res['aaData'], $output);
            $ctr++;
        }
        echo json_encode($res);
        exit;
    }

    public function saveArchiveFetchTask()
    {

        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api'] && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect value
        $daterg = $_POST['chosenDate'];
        //add a new task
        $atobj = Doo::loadModel('ScJobsDownload', true);
        $atobj->mode = "archive";
        $atobj->user_id = $_SESSION['user']['userid'];
        $atobj->file_name = "archived_records_" . strtotime(date(Doo::conf()->date_format_db)) . ".zip";
        $atobj->meta_data = json_encode(['date_range' => $daterg]);
        Doo::db()->insert($atobj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Request submitted successfully. File will be available shortly, it might take a few minutes. We will notify you as soon as your data is available.';
        return Doo::conf()->APP_URL . 'smsArchive';
    }

    public function scheduledCampaigns()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['schedule']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'Scheduled Campaigns';

        $data['page'] = 'Reports';
        $data['current_page'] = 'scheduled';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/schSummary', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getMyScheduledCampaigns()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['schedule']) {
            //denied
            return array('/denied', 'internal');
        }

        $columns = array(
            array('db' => 'sms_text', 'dt' => 5),
            array('db' => 'count', 'dt' => 6)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        //get all campaigns for this account

        //date range
        $dr = $this->params['dr'];
        $uid = $_SESSION['user']['userid'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "user_id = $uid AND submission_time LIKE '$from%' AND status =1";
            } else {
                $sWhere = "user_id = $uid AND submission_time BETWEEN '$from' AND '$to' AND status =1";
            }
        } else {
            $sWhere = 'user_id = ' . $uid . ' AND status =1';
        }

        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND' . $dtdata['where'];
            }
        }

        $dtdata['desc'] = 'sent_time';

        Doo::loadModel('ScSmsSummary');
        $obj = new ScSmsSummary;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $cmpns = Doo::db()->find($obj, $dtdata);

        $robj = Doo::loadModel('ScSmsRoutes', true);
        $sidobj = Doo::loadModel('ScSenderId', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($cmpns as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editScheduledCampaign/' . $dt->sms_shoot_id . '">' . $this->SCTEXT('Edit Campaign') . '</a></li><li><a data-shootid="' . $dt->sms_shoot_id . '" href="javascript:void(0);" class="delsch" >' . $this->SCTEXT('Cancel Campaign') . '</a></li></ul></div>';

            $rstr = '<span class="label label-info label-md">' . $robj->getRouteData($dt->route_id, 'title')->title . '</span>';

            $smscat = json_decode($dt->sms_type, true);
            $stxtstr = '';
            $stypestr = '';
            if ($smscat['main'] == 'text') {
                $stxtstr = '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dt->sms_text) . '</div>';
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
                $stdata = json_decode(base64_decode($dt->sms_text), true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <h5>' . $stdata['wap_title'] . '</h5>
                                                    <hr class="m-h-xs">

                                                     <span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span>

                                                    </div>';
                $stypestr = '<span class="label label-success label-md"><i class="fa fa-lg fa-globe m-r-xs"></i>WAP</span>';
            } elseif ($smscat['main'] == 'vcard') {
                $stdata = json_decode(base64_decode($dt->sms_text), true);
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                                                         <span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span>
                                                    <span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span>
                                                     <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span>
                                                    </div>';
                $stypestr = '<span class="label label-primary label-md"><i class="fa fa-vcard m-r-xs"></i>vCard</span>';
            }

            $senderid = $dt->sender_id;

            $schdata = json_decode($dt->schedule_data, true);

            if ($_SESSION['user']['account_type'] == 1 || $_SESSION['user']['account_type'] == 2) {
                $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->submission_time)), date(Doo::conf()->date_format_long_time, strtotime($schdata['schedule']['date'] . ' ' . $schdata['schedule']['timezone'])), $senderid, $stypestr, $stxtstr, number_format($dt->total_contacts), $button_str);
            } else {
                $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->submission_time)), date(Doo::conf()->date_format_long_time, strtotime($schdata['schedule']['date'] . ' ' . $schdata['schedule']['timezone'])), $rstr, $senderid, $stypestr, $stxtstr, number_format($dt->total_contacts), $button_str);
            }

            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function editScheduledCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['schedule']) {
            //denied
            return array('/denied', 'internal');
        }
        $shootid = $this->params['id'];

        //get all the campaign data from sms summary table
        $cobj = Doo::loadModel('ScSmsSummary', true);
        $cobj->sms_shoot_id = $shootid;
        $cobj->user_id = $_SESSION['user']['userid'];
        $cobj->status = 1; //edit scheduled campaigns only
        $data['cdata'] = Doo::db()->find($cobj, array('limit' => 1));

        $smsobj = Doo::loadModel('ScScheduledCampaigns', true);
        $smsobj->sms_shoot_id = $shootid;
        $data['smsdata'] = Doo::db()->find($smsobj, array('limit' => 1));

        if (!$data['cdata']->id) {
            //invalid shoot id supplied
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Campaign not found.';
            return Doo::conf()->APP_URL . 'scheduledCampaigns';
        }
        //This page is a lot like the sendsms page so try to fetch same data for route, sender etc.

        //get all info required for sendsms
        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'id,sender_id'));

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Scheduled Campaigns'] = Doo::conf()->APP_URL . 'scheduledCampaigns';
        $data['active_page'] = 'Edit Campaign';

        $data['page'] = 'Reports';
        $data['current_page'] = 'edit_sch_campaign';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editScheduledCampaign', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveEditScheduledCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['schedule']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        // echo json_encode($_POST);
        // echo json_encode($_SESSION["permissions"]);
        // die;

        //get shoot data from sms summary table
        $shootid = $_POST['shootid'];
        $cobj = Doo::loadModel('ScSmsSummary', true);
        $cobj->sms_shoot_id = $shootid;
        $cobj->user_id = $_SESSION['user']['userid'];
        $cobj->status = 1; //edit scheduled campaigns only
        $sumdata = Doo::db()->find($cobj, array('limit' => 1));

        //get data from the scheduled queue table
        $schobj = Doo::loadModel('ScScheduledCampaigns', true);
        $schobj->sms_shoot_id = $shootid;
        $schdata = Doo::db()->find($schobj, array('limit' => 1));

        if (!$sumdata->id) {
            //invalid campaign
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Scheduled campaign not found.';
            return Doo::conf()->APP_URL . 'scheduledCampaigns';
        }
        //get route details
        $routeid = $_POST["route"]; //supplied route id, might be different from the original
        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        $rtobj->id = $routeid;
        $rtdata = Doo::db()->find($rtobj, array('limit' => 1));

        //get approved templates if applicable
        $approvedTemps = [];
        if ($routeid != $schdata->route_id && $rtdata->allow_templates == 1 && !$_SESSION['permissions']['messaging']['open_template'] && !$_SESSION['permissions']['master']) {
            $tmpobj = Doo::loadModel('ScSmsTemplates', true);
            $tmpobj->user_id = $_SESSION['user']['userid'];
            $tmpobj->route_id = $routeid;
            $tmpobj->status = 1;
            $templates = Doo::db()->find($tmpobj);
            if (sizeof($templates) > 0) {
                $approvedTemps = $templates;
            }
        }
        //get all spam keywords to match

        //simply update sender id if different
        $sender = $rtdata->sender_type == 0 ? $_POST["sendersel"] : $_POST["senderopn"];
        if ($sender != $sumdata->sender_id) {
            //check if length is ok
            if ($rtdata->sender_type != 1 && strlen($sender) > $rtdata->max_sid_len) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Sender ID length must be less than or equal to ' . $rtdata->max_sid_len . ' characters.';
                return Doo::conf()->APP_URL . 'editScheduledCampaign/' . $shootid;
            }
            //update sender countries in the schedule meta data
            $sidobj = Doo::loadModel("ScSenderId", true);
            $sidobj->sender_id = $sender;
            $senderdata = Doo::db()->find($sidobj, array('limit' => 1));
            $senderCountries = $senderdata->countries_matrix;
        }

        //preapre to update the contacts
        $finContacts = ["total" => sizeof($_POST["msisdn"]), "dynamic" => 0, "mobile_data" => []];
        if ($_POST["hasparams"] == 0 && $_POST["columns"] == "[]") {
            //non personalized campaign
            foreach ($_POST["msisdn"] as $mobile) {
                $contact = [
                    "msisdn" => $mobile,
                    "sms_length" => 0,
                    "sms_parts" => 0,
                    "sms_cost" => 0,
                    "parameters" => []
                ];
                array_push($finContacts["mobile_data"], $contact);
            }
        } else {
            //personalized campaign with parameters
            $columns = json_decode($_POST["columns"], true);
            for ($i = 0; $i < sizeof($_POST["msisdn"]); $i++) {
                $parameters = [];
                foreach ($columns as $col) {
                    $parameters[$col] = $_POST["parameters"][$col][$i];
                }
                $contact = [
                    "msisdn" => $_POST["msisdn"][$i],
                    "sms_length" => 0,
                    "sms_parts" => 0,
                    "sms_cost" => 0,
                    "parameters" => $parameters
                ];
                array_push($finContacts["mobile_data"], $contact);
            }
        }


        //prepare sms text (filter for template, spam, tracking url etc)
        $newsmsText = $_POST['smstextcontent'];
        $newsavedText = htmlspecialchars($newsmsText, ENT_QUOTES);
        if ($newsavedText != $sumdata->sms_text) {
            //check if text matches approved template
            if (sizeof($approvedTemps) > 0) {
                $matched = 0;
                foreach ($approvedTemps as $template) {
                    if ($this->partialTextMatch($newsmsText, $template->content)) {
                        $matched = 1;
                        break;
                    }
                }
                if ($matched == 0) {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Text does not match any approved templates.';
                    return Doo::conf()->APP_URL . 'editScheduledCampaign/' . $shootid;
                }
            }
            //check if there are no spam words in the text
            if ($_SESSION["permissoins"]["master"] != "1" && !$_SESSION["permissoins"]["messaging"]["allow_spam"] && $this->isSpamContent($newsmsText)) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Text update now allowed. Please check content.';
                return Doo::conf()->APP_URL . 'editScheduledCampaign/' . $shootid;
            }
        }

        //update schedule time if applicable
        $routeTimings = json_decode(base64_decode($rtdata->active_time), true);
        $original_sch_ts =  date(Doo::conf()->date_format_db, strtotime($_POST["orgschdate"] . " " . $_POST["orgschtz"]));
        $new_sch_ts =  date(Doo::conf()->date_format_db, strtotime($_POST["schtime"] . " " . $_POST["timezone"]));
        if ($routeid != $schdata->route_id && $routeTimings["type"] == 1) {
            //check if new schedule time is in allowed window
            if ($original_sch_ts != $new_sch_ts) {
                $day = strtok($new_sch_ts, " ");
                if (strtotime($new_sch_ts) < strtotime($day . " " . $routeTimings["from"] . " " . $routeTimings["timezone"]) || strtotime($new_sch_ts) > strtotime($day . " " . $routeTimings["to"] . " " . $routeTimings["timezone"])) {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Schedule time is not in allowed window.';
                    return Doo::conf()->APP_URL . 'editScheduledCampaign/' . $shootid;
                }
            }
        }

        //no need to check credits now as they will be calculated and checked during send. Credits are not deducted anyways.

        //update in both summary and queue tables if applicable
        $updateSum = 0;
        $newSumObj = Doo::loadModel('ScSmsSummary', true);
        $newSumObj->id = $sumdata->id;
        if ($routeid != $sumdata->route_id) {
            $newSumObj->route_id = $routeid;
            $updateSum = 1;
        }
        if ($sender != $sumdata->sender_id) {
            $newSumObj->sender_id = $sender;
            $updateSum = 1;
        }
        if ($finContacts["total"] != $sumdata->total_contacts) {
            $newSumObj->total_contacts = $finContacts["total"];
            $updateSum = 1;
        }
        if ($newsavedText != $sumdata->sms_text) {
            $newSumObj->sms_text = $newsavedText;
            $updateSum = 1;
        }
        if ($original_sch_ts != $new_sch_ts) {
            $newschsum = [
                "type" => 1,
                "schedule" => ["timezone" => $_POST["timezone"], "date" => $_POST["schtime"], "date_system_tz" => date(Doo::conf()->date_format_db, strtotime($_POST["schtime"] . " " . $_POST["timezone"]))],
                "batch" => ["size" => "", "duration" => 5, "days" => 0, "start" => 0]
            ];
            $newSumObj->schedule_data = json_encode($newschsum);
            $updateSum = 1;
        }

        if ($updateSum == 1) Doo::db()->update($newSumObj);

        $updateSch = 0;
        $newSchObj = Doo::loadModel('ScScheduledCampaigns', true);
        $newSchObj->id = $schdata->id;
        if ($routeid != $schdata->route_id) {
            $newSchObj->route_id = $routeid;
            $updateSch = 1;
        }
        if ($sender != $schdata->sender_id) {
            $newSchObj->sender_id = $sender;
            $oldMeta = json_decode($schdata->meta_data, true);
            $oldMeta["senderCountries"] = $senderCountries;
            $newSchObj->meta_data = json_encode($oldMeta);
            $updateSch = 1;
        }
        if (json_encode($finContacts) != $schdata->contacts) {
            $newSchObj->contacts = json_encode($finContacts);
            $updateSch = 1;
        }
        if ($finContacts["total"] != $schdata->total_contacts) {
            $newSchObj->total_contacts = $finContacts["total"];
            $updateSch = 1;
        }
        if ($newsavedText != $schdata->sms_text) {
            $newSchObj->sms_text = $newsavedText;
            $updateSch = 1;
        }
        if (date(Doo::conf()->date_format_db, strtotime($_POST["schtime"] . " " . $_POST["timezone"])) != $schdata->schedule_time) {
            $newSchObj->schedule_time = date(Doo::conf()->date_format_db, strtotime($_POST["schtime"] . " " . $_POST["timezone"])); //converted to system timezone
            $updateSch = 1;
        }
        if ($updateSch == 1) Doo::db()->update($newSchObj);

        //redirect
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Scheduled campaign was modified successfully.';
        return Doo::conf()->APP_URL . 'scheduledCampaigns';
    }

    public function cancelSchedule()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['schedule']) {
            //denied
            return array('/denied', 'internal');
        }
        //get campaign details
        $shootid = $this->params['id'];
        $cobj = Doo::loadModel('ScSmsSummary', true);
        $cobj->sms_shoot_id = $shootid;
        $cobj->user_id = $_SESSION['user']['userid'];
        $cobj->status = 1; //edit scheduled campaigns only
        $data['cdata'] = Doo::db()->find($cobj, array('limit' => 1));

        if (!$data['cdata']->id) {
            //invalid shoot id supplied
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Campaign not found.';
            return Doo::conf()->APP_URL . 'scheduledCampaigns';
        }

        //delete from sms summary
        $cobj->id = $data['cdata']->id;
        Doo::db()->delete($cobj);

        //delete from scheduled sms table
        $schcmpobj = Doo::loadModel('ScScheduledCampaigns', true);
        Doo::db()->delete($schcmpobj, array('where' => "sms_shoot_id='$shootid'"));

        if ($_SESSION['user']['account_type'] == 1 || $_SESSION['user']['account_type'] == 2) {
            //wallet refund
            //deduct credits from wallet
            $wlobj = Doo::loadModel('ScUsersWallet', true);
            $newwallet = $wlobj->doCreditTrans('add', $_SESSION['user']['userid'], $data['cdata']->credits_charged);
            $_SESSION['credits']['wallet']['amount'] = $newwallet['after'];
            //make log entry
            $lcobj = Doo::loadModel('ScLogsCredits', true);
            $lcobj->user_id = $_SESSION['user']['userid'];
            $lcobj->timestamp = date(Doo::conf()->date_format_db);
            $lcobj->amount = '-' . $data['cdata']->credits_charged;
            $lcobj->route_id = 0;
            $lcobj->credits_before = $newwallet['before'];
            $lcobj->credits_after = $newwallet['after'];
            $lcobj->reference = 'Cancel Schedule';
            $lcobj->comments = 'Scheduled SMS campaign was cancelled. Hence credits deducted were added back.|| SHOOT ID: ' . $shootid;
            Doo::db()->insert($lcobj);
        } else {
            //add credits back
            $creobj = Doo::loadModel('ScUsersCreditData', true);
            $creobj->user_id = $_SESSION['user']['userid'];
            $creobj->route_id = $data['cdata']->route_id;
            $creditdata = Doo::db()->find($creobj, array('limit' => 1));

            $newavcredits = $creobj->doCreditTrans('credit', $_SESSION['user']['userid'], $data['cdata']->route_id, $data['cdata']->credits_charged);
            $_SESSION['credits']['routes'][$data['cdata']->route_id]['credits'] = $newavcredits;

            //credit log
            $lcobj2 = Doo::loadModel('ScLogsCredits', true);
            $lcobj2->user_id = $_SESSION['user']['userid'];
            $lcobj2->timestamp = date(Doo::conf()->date_format_db);
            $lcobj2->amount = $data['cdata']->credits_charged;
            $lcobj2->route_id = $data['cdata']->route_id;
            $lcobj2->credits_before = $creditdata->credits;
            $lcobj2->credits_after = $newavcredits;
            $lcobj2->reference = 'Cancel Schedule';
            $lcobj2->comments = 'Scheduled SMS campaign was cancelled. Hence credits deducted were added back.|| SHOOT ID: ' . $shootid;
            Doo::db()->insert($lcobj2);
        }
        //no need to add refund stats for this as there was no entry made in stats when this campaign was scheduled. For scheduled campaigns, the stats entry is made only when they are actually sent
        //record activity
        $actData['activity_type'] = 'SCHEDULE CANCEL';
        $actData['activity'] = Doo::conf()->campaign_schedule_cancel . '|| CAMPAIGN-ID: ' . $shootid;
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Scheduled campaign was cancelled successfully. Credits are added back to your account. If you do not see the credits added, please click Reload Icon from the Wallet drop down';
        return Doo::conf()->APP_URL . 'scheduledCampaigns';
    }

    //8. Support
    public function supportTickets()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Support'] = 'javascript:void(0);';
        $data['active_page'] = 'Support Tickets';
        //dont allow admin and staff to see this page as they don't need to add support ticket
        if ($_SESSION['user']['group'] == 'admin') {
            //redirect
            return Doo::conf()->APP_URL . 'Dashboard';
        }
        $data['page'] = 'Support';
        $data['current_page'] = 'support_tickets';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewTickets', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getMyTickets()
    {
        $this->isLogin();
        //get all tickets for this account
        //date range
        $dr = $this->params['dr'];
        $uid = $_SESSION['user']['userid'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));

            if ($from == $to) {
                $sWhere = "user_id = $uid AND date_opened LIKE '$from%'";
            } else {
                $sWhere = "user_id = $uid AND date_opened BETWEEN '$from' AND '$to'";
            }
        } else {
            $sWhere = 'user_id = ' . $uid;
        }

        Doo::loadModel('ScSupportTickets');
        $obj = new ScSupportTickets;
        $tkts = Doo::db()->find($obj, array('where' => $sWhere));
        $total = count($tkts);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($tkts as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewTicket/' . $dt->id . '">' . $this->SCTEXT('View') . '</a></li></ul></div>';

            $pstr = $dt->priority == 0 ? '<span class="label label-info label-md">' . $this->SCTEXT('Normal') . '</span>' : ($dt->priority == 1 ? '<span class="label label-warning label-md">' . $this->SCTEXT('Medium') . '</span>' : '<span class="label label-danger label-md">' . $this->SCTEXT('Critical') . '</span>');

            $status = $dt->status == 0 ? ' <span class="label label-warning label-md"><i class="fa fa-clock-o fa-lg m-r-xs"></i>' . $this->SCTEXT('Issue Open') . '</span>' : '<span class="label label-success label-md"><i class="fa fa-check-circle fa-lg m-r-xs"></i>' . $this->SCTEXT('Resolved') . '</span>';

            $output = array(date(Doo::conf()->date_format_long_time, strtotime($dt->date_opened)), $dt->ticket_title, $pstr, $status, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }

    public function addNewTicket()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Support Tickets'] = Doo::conf()->APP_URL . 'supportTickets';
        $data['active_page'] = 'Add New Ticket';

        //get all the regular documents
        $dobj = Doo::loadModel('ScUsersDocuments', true);
        $dobj->owner_id = $_SESSION['user']['userid'];
        $dobj->type = 3;
        $data['docs'] = Doo::db()->find($dobj);

        $data['page'] = 'Support';
        $data['current_page'] = 'add_ticket';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addTicket', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveSupportTicket()
    {
        $this->isLogin();
        //collect values
        $title = $_POST['tkttitle'];
        $priority = intval($_POST['tpri']);
        $txt = htmlspecialchars($_POST['tktdesc']);
        $files = is_array($_POST['tfiles']) ? implode(",", $_POST['tfiles']) : '';

        //insert
        $tobj = Doo::loadModel('ScSupportTickets', true);
        $tobj->ticket_title = $title;
        $tobj->priority = $priority;
        $tobj->user_id = $_SESSION['user']['userid'];
        $tobj->manager_id = $_SESSION['manager']['id'];
        $ticket_id = Doo::db()->insert($tobj);

        $trobj = Doo::loadModel('ScSupportTicketComments', true);
        $trobj->ticket_id = $ticket_id;
        $trobj->ticket_text = $txt;
        $trobj->files_included = $files;
        $trobj->user_id = $_SESSION['user']['userid'];
        Doo::db()->insert($trobj);

        //share the included files with Manager
        if ($files != '') {
            $mgrid = $_SESSION['manager']['id'];
            $qry = "UPDATE sc_users_documents set shared_with = CONCAT_WS(',',NULLIF(IF(FIND_IN_SET($mgrid,shared_with)>0, TRIM(BOTH ',' FROM REPLACE(CONCAT(',', shared_with, ','),CONCAT(',', $mgrid, ','), ',')) ,shared_with),''),$mgrid) WHERE id IN ($files)";
            Doo::db()->query($qry);
        }

        //notify using hypernode 
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
            "mode" => "new_support_ticket",
            "data" => array(
                "user_id" => $_SESSION['user']['userid'],
                "manager_userid" => $_SESSION['manager']['id'],
                "incidentPlatform" => $osdata,
                "incidentDateTime" => date(Doo::conf()->date_format_db),
                "ticketId" => 'MGWST' . $ticket_id,
                "ticketSubject" => $title,
                "ticketPriority" => $priority == 0 ? 'Normal' : ($priority == 1 ? 'Medium' : 'Critical'),
                "ticketDescription" => $txt,
                "ticketUrl" => Doo::conf()->APP_URL . 'viewMgrTicket/' . $ticket_id
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
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'New Ticket opened successfully. Support will revert shortly.';
        return Doo::conf()->APP_URL . 'supportTickets';
    }

    public function viewTicket()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Support Tickets'] = Doo::conf()->APP_URL . 'supportTickets';
        $data['active_page'] = 'View Ticket';

        //get the ticket data
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid Ticket ID';
            return Doo::conf()->APP_URL . 'supportTickets';
        }
        $tobj = Doo::loadModel('ScSupportTickets', true);
        $tobj->id = $tid;
        $tobj->user_id = $_SESSION['user']['userid'];
        $tdata = Doo::db()->find($tobj, array('limit' => 1));

        if (!$tdata->id) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Ticket Not Found';
            return Doo::conf()->APP_URL . 'supportTickets';
        }

        $data['tdata'] = $tdata;

        $trobj = Doo::loadModel('ScSupportTicketComments', true);
        $trobj->ticket_id = $tid;
        $data['tcoms'] = Doo::db()->find($trobj);

        //get all the regular documents
        $dobj = Doo::loadModel('ScUsersDocuments', true);
        $dobj->owner_id = $_SESSION['user']['userid'];
        $dobj->type = 3;
        $data['docs'] = Doo::db()->find($dobj);

        $data['page'] = 'Support';
        $data['current_page'] = 'view_ticket';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/openTicket', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function postTicketComment()
    {
        $this->isLogin();
        //collect values
        $ticket_id = intval($_POST['ticketid']);
        $txt = htmlspecialchars($_POST['t_comment']);
        $files = is_array($_POST['tfiles']) ? implode(",", $_POST['tfiles']) : '';
        if ($ticket_id == 0) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Ticket Not Found';
            return Doo::conf()->APP_URL . 'supportTickets';
        }
        //insert
        $trobj = Doo::loadModel('ScSupportTicketComments', true);
        $trobj->ticket_id = $ticket_id;
        $trobj->ticket_text = $txt;
        $trobj->files_included = $files;
        $trobj->user_id = $_SESSION['user']['userid'];
        Doo::db()->insert($trobj);

        //share the included files with Manager
        if ($_SESSION['user']['group'] == 'client' && $files != '') {
            $mgrid = $_SESSION['manager']['id'];
            $qry = "UPDATE sc_users_documents set shared_with = CONCAT_WS(',',NULLIF(IF(FIND_IN_SET($mgrid,shared_with)>0, TRIM(BOTH ',' FROM REPLACE(CONCAT(',', shared_with, ','),CONCAT(',', $mgrid, ','), ',')) ,shared_with),''),$mgrid) WHERE id IN ($files)";
            Doo::db()->query($qry);
        }
        //notify
        $tktobj = Doo::loadModel('ScSupportTickets', true);
        $tktobj->id = $ticket_id;
        $tktdata = Doo::db()->find($tktobj, array('limit' => 1, 'select' => 'user_id,manager_id,ticket_title,priority'));
        $alobj = Doo::loadModel('ScUserNotifications', true);
        //return
        if ($_POST['tpage'] == 'mgr') {
            $url = 'viewMgrTicket/';
            $msg = 'Your comment was posted successfully. The user has been notified.';
            $commenterType = 'Manager';
            $toManager = 0;
            $alobj->addAlert($tktdata->user_id, 'info', Doo::conf()->support_ticket_comment, 'viewTicket/' . $ticket_id);
            $email_receiver_user = $tktdata->user_id;
        } else {
            $url = 'viewTicket/';
            $msg = 'Your comment was posted successfully. Support will revert shortly.';
            $commenterType = "customer";
            $toManager = 1;
            $customerName = $_SESSION['user']['name'];
            $customerEmail = $_SESSION['user']['email'];
            $customerPhone = $_SESSION['user']['mobile'];
            $alobj->addAlert($tktdata->manager_id, 'info', Doo::conf()->support_ticket_comment, 'viewMgrTicket/' . $ticket_id);
            $email_receiver_user = $tktdata->manager_id;
        }

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
            "mode" => "new_support_ticket_reply",
            "data" => array(
                "user_id" => $email_receiver_user,
                "incidentPlatform" => $osdata,
                "incidentDateTime" => date(Doo::conf()->date_format_db),
                "ticketId" => 'MGWST' . $ticket_id,
                "ticketSubject" => $tktdata->ticket_title,
                "ticketPriority" => $tktdata->priority == 0 ? 'Normal' : ($tktdata->priority == 1 ? 'Medium' : 'Critical'),
                "ticketDescription" => $txt,
                "commenterType" => $commenterType,
                "toManager" => $toManager,
                "customerName" => $customerName,
                "customerEmail" => $customerEmail,
                "customerPhone" => $customerPhone,
                "ticketUrl" => Doo::conf()->APP_URL . $url . $ticket_id
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

        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . $url . $ticket_id;
    }

    //9. Logs
    public function refundLog()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Logs'] = 'javascript:void(0);';
        $data['active_page'] = 'Refund Log';

        $data['page'] = 'Logs';
        $data['current_page'] = 'refund_log';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/refundLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getRefundLog()
    {
        $this->isLogin();
        $columns = array(
            array('db' => 'mobile_no', 'dt' => 3)
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
            $sWhere = "user_id = $uid AND `timestamp` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
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
        Doo::loadModel('ScLogsDlrRefunds');
        $obj = new ScLogsDlrRefunds;
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $refdata = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($refdata);
        $rrobj = Doo::loadModel('ScDlrRefundRules', true);
        $rules = Doo::db()->find($rrobj, array('select' => 'id,title'));

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($refdata as $dt) {

            $ruleid = $dt->refund_rule;
            $rmap = function ($e) use ($ruleid) {
                return $e->id == $ruleid;
            };
            $rrfobj = array_filter($rules, $rmap);
            $k = key($rrfobj);

            $output = array(date(Doo::conf()->date_format_long_time_s, strtotime($dt->timestamp)), '<span class="label label-md label-success"> + ' . $dt->refund_amt . '</span>', $rules[$k]->title, $dt->mobile_no, '<a class="btn btn-primary" href="' . Doo::conf()->APP_URL . 'showDLR/' . $dt->sms_shoot_id . '" >' . $this->SCTEXT('View Campaign') . '</a>');
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function creditLog()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Logs'] = 'javascript:void(0);';
        $data['active_page'] = 'Credit Log';

        $data['page'] = 'Logs';
        $data['current_page'] = 'credit_log';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/creditLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getCreditLog()
    {
        $this->isLogin();
        $uobj = Doo::loadModel('ScUsers', true);
        if (intval($this->params['uid']) == 0) {
            $uid = $_SESSION['user']['userid'];
        } else {
            $uid = intval($this->params['uid']);
            if ($_SESSION['user']['group'] != 'admin') {
                //check if valid reseller

                if (!$uobj->isValidUpline($uid, $_SESSION['user']['userid'])) {
                    return array('/denied', 'internal');
                }
            }
        }
        $uinfo = $uobj->getProfileInfo($uid);
        $columns = array(
            array('db' => 'amount', 'dt' => 0),
            array('db' => 'reference', 'dt' => 4)
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
            $sWhere = "user_id = $uid AND `timestamp` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
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

        Doo::loadModel('ScLogsCredits');
        $obj = new ScLogsCredits;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $credata = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($credata);

        if ($uinfo->account_type == 1 || $uinfo->account_type == 2) {
            $res = array();
            $res['iTotalRecords'] = $total;
            $res['iTotalDisplayRecords'] = $total;
            $res['aaData'] = array();
            foreach ($credata as $dt) {
                if (floatval($dt->amount) != 0) {
                    $amstr = floatval($dt->amount) < 0 ? '<span class="label label-md label-danger">- ' . Doo::conf()->currency . abs($dt->amount) . '</span>' : '<span class="label label-md label-success"><i class="fa fa-plus"></i> ' . Doo::conf()->currency . $dt->amount . '</span>';

                    //translate comment
                    if (strpos($dt->comments, '||')) {
                        $comar = explode("||", $dt->comments);
                        $fincom = $this->SCTEXT(trim($comar[0])) . ' ' . $comar[1];
                    } else {
                        $fincom = $this->SCTEXT($dt->comments);
                    }

                    $output = array($amstr, date(Doo::conf()->date_format_long_time_s, strtotime($dt->timestamp)), '<span class="label label-md label-primary">' . Doo::conf()->currency . rtrim(number_format($dt->credits_after, 5), "0") . '</span>', $dt->reference, $fincom);
                    array_push($res['aaData'], $output);
                }
            }
        } else {
            $rtobj = Doo::loadModel('ScSmsRoutes', true);
            $routes = Doo::db()->find($rtobj, array('select' => 'id,title'));
            $res = array();
            $res['iTotalRecords'] = $total;
            $res['iTotalDisplayRecords'] = $total;
            $res['aaData'] = array();
            foreach ($credata as $dt) {
                if (floatval($dt->amount) != 0) {
                    $rid = $dt->route_id;
                    if ($rid != 0) {
                        $rmap = function ($e) use ($rid) {
                            return $e->id == $rid;
                        };
                        $rtfobj = array_filter($routes, $rmap);
                        $k = key($rtfobj);
                        $rtitle = $routes[$k]->title;
                    } else {
                        $rtitle = '';
                    }

                    $cursign = $uinfo->account_type == 2 ? Doo::conf()->currency : '';
                    $amstr = floatval($dt->amount) < 0 ? '<span class="label label-md label-danger">- ' . $cursign . str_replace('-', '', $dt->amount) . '</span>' : '<span class="label label-md label-success"><i class="fa fa-plus"></i> ' . $cursign . $dt->amount . '</span>';

                    //translate comment
                    if (strpos($dt->comments, '||')) {
                        $comar = explode("||", $dt->comments);
                        $fincom = $this->SCTEXT(trim($comar[0])) . ' ' . $comar[1];
                    } else {
                        $fincom = $this->SCTEXT($dt->comments);
                    }
                    $creditsafter = $uinfo->account_type == 2 ? $cursign . (float)$dt->credits_after : number_format($dt->credits_after);
                    $output = array($amstr, date(Doo::conf()->date_format_long_time_s, strtotime($dt->timestamp)), $rtitle, '<span class="label label-md label-primary">' . $creditsafter . '</span>', $dt->reference, $fincom);
                    array_push($res['aaData'], $output);
                }
            }
        }


        echo json_encode($res);
        exit;
    }

    public function userSmsLog()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['gui'] && !$_SESSION['permissions']['messaging']['http_sms_api'] && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //get all users for admin, get downline for reseller/staff and nothing for client accounts
        $userobj = Doo::loadModel('ScUsers', 'true');
        if ($_SESSION['user']['subgroup'] == "admin") {
            $data['users'] = Doo::db()->find($userobj, array('select' => 'user_id, name, category, avatar, email, mobile'));
        }
        if ($_SESSION['user']['subgroup'] == "reseller" || $_SESSION['user']['subgroup'] == "staff") {
            $data['users'] = Doo::db()->find($userobj, array('select' => 'user_id, name, category, avatar, email, mobile', 'where' => 'user_id = ' . $_SESSION['user']['userid'] . ' OR upline_id =' . $_SESSION['user']['userid']));
        }

        //get all routes for admin, assigned routes for other accounts
        if ($_SESSION['user']['subgroup'] == "admin") {
            $routeobj = Doo::loadModel('ScSmsRoutes', true);
            $data['routes'] = Doo::db()->find($routeobj, array('select' => 'id, title'));
            //get all smpp for admin only
            $smppobj = Doo::loadModel('ScSmppAccounts', true);
            $data['smpp'] = Doo::db()->find($smppobj, array('select' => 'title, smsc_id, provider'));
        }

        //get all countries
        $countryobj = Doo::loadModel('ScCoverage', true);
        $data['countries'] = Doo::db()->find($countryobj, array('select' => 'country_code, country, prefix', 'asc' => 'country'));

        //get all download jobs
        $dlobj = Doo::loadModel('ScJobsDownload', true);
        $dlobj->user_id = $_SESSION['user']['userid'];
        $data['dljobs'] = Doo::db()->find($dlobj, array('desc' => 'added_on'));

        //get all smpp clients for admin, self smpp client accounts for others
        $smppclientobj = Doo::loadModel('ScSmppClients', true);
        if ($_SESSION['user']['subgroup'] == "admin") {
            $data['smppclients'] = Doo::db()->find($smppclientobj, array('select' => 'system_id'));
        } else {
            $data['smppclients'] = Doo::db()->find($smppclientobj, array('select' => 'system_id', 'where' => 'user_id =' . $_SESSION['user']['userid']));
        }


        $data['page'] = 'Logs';
        $data['current_page'] = 'usms_log';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/userSmsLog', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }


    //10. API

    public function viewDevApi()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['active_page'] = 'HTTP API';

        //get API Key
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $data['apikey'] = $ak_obj->getApiKey($_SESSION['user']['userid']);

        $data['baseurl'] = Doo::conf()->APP_URL;

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //get all the tlv params with the custom labels
        $tlvstr = '';
        if ($_SESSION['user']['group'] != "admin") {
            $custqry = "SELECT tlv_category, CONCAT_WS('|', custom_label, default_value) as cusdata FROM sc_users_tlv_defaults WHERE user_id = " . intval($_SESSION['user']['userid']);
            $customTlvLabels = Doo::db()->fetchAll($custqry, null, PDO::FETCH_KEY_PAIR);
            //get all the tlvs possible for this account
            $usercreobj = Doo::loadModel('ScUsersCreditData', true);
            $usercreobj->user_id = $_SESSION['user']['userid'];
            $usercreobj->status = 0;
            $usercredits = Doo::db()->find($usercreobj, array('select' => 'route_id'));
            $userroutes = [];
            foreach ($usercredits as $userdata) {
                array_push($userroutes, $userdata->route_id);
            }
            $assignedRoutes = implode(",", $userroutes);
            if ($assignedRoutes != "") {
                $routeobj = Doo::loadModel('ScSmsRoutes', true);
                $tlvopt['select'] = 'tlv_ids';
                $tlvopt['where'] = "id IN ( $assignedRoutes ) AND tlv_ids <> ''";
                $routetlvs = Doo::db()->find($routeobj, $tlvopt);
            }

            $allUserTlvs = [];

            foreach ($routetlvs as $tdata) {
                $tlvs = json_decode($tdata->tlv_ids);
                foreach ($tlvs as $tlv_category) {
                    array_push($allUserTlvs, $tlv_category);
                }
            }
            //prepare tlv string
            foreach ($allUserTlvs as $itlv) {
                if ($customTlvLabels[$itlv]) {
                    $parts = explode("|", $customTlvLabels[$itlv]);
                    $tlvstr .= '<div class="m-b-xs"><kbd class="bg-primary">&' . $parts[0] . '=</kbd></div>';
                } else {
                    $tlvstr .= '<div class="m-b-xs"><kbd class="bg-primary">&' . $itlv . '=</kbd></div>';
                }
            }
        } else {
            $routeobj = Doo::loadModel('ScSmsRoutes', true);
            $tlvopt['select'] = 'tlv_ids';
            $tlvopt['where'] = " tlv_ids <> ''";
            $routetlvs = Doo::db()->find($routeobj, $tlvopt);
            $allUserTlvs = [];
            foreach ($routetlvs as $tdata) {
                $tlvs = json_decode($tdata->tlv_ids);
                foreach ($tlvs as $tlv_category) {
                    if (!in_array($tlv_category, $allUserTlvs)) array_push($allUserTlvs, $tlv_category);
                }
            }
            //prepare tlv string
            foreach ($allUserTlvs as $itlv) {
                $tlvstr .= '<div class="m-b-xs"><kbd class="bg-primary"><b>' . $itlv . '</b></kbd></div>';
            }
        }
        $data['tlvList'] = $tlvstr;

        //permission, if reached here you got it
        $data['permission'] = 1;

        $data['page'] = 'API';
        $data['current_page'] = 'hapi';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/httpApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function regAPIKey()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $ak_obj->generateKey($_SESSION['user']['userid']);
        $_SESSION['notif_msg']['msg'] = 'API Key regenerated successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'api';
    }

    public function legacyApi()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['active_page'] = 'Legacy Messaging API';

        //get API Key
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $data['apikey'] = $ak_obj->getApiKey($_SESSION['user']['userid']);

        $data['baseurl'] = Doo::conf()->APP_URL;

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //permission
        $data['permission'] = 1;

        $data['page'] = 'API';
        $data['current_page'] = 'lapi';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/legacyApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewXmlApi()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['http_sms_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['active_page'] = 'XML API';

        //get API Key
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $data['apikey'] = $ak_obj->getApiKey($_SESSION['user']['userid']);

        $data['baseurl'] = Doo::conf()->APP_URL;

        //get campaigns
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $campaigns = Doo::db()->find($cobj);
        //add a default campaign of none
        if (sizeof($campaigns) > 0) {
            $data['camps'] = $campaigns;
        } else {
            //add a new default campaign and system message campaign for this user
            $cobj->createNewCampaigns($_SESSION['user']['userid']);
            $cobj->user_id = $_SESSION['user']['userid'];
            $campaigns = Doo::db()->find($cobj);
            $data['camps'] = $campaigns;
        }

        //permission
        $data['permission'] = 1;
        $prmobj = Doo::loadModel('ScUsersPermissions', true);
        $prmobj->user_id = $_SESSION['user']['userid'];
        $perms = Doo::db()->find($prmobj, array('limit' => 1, 'select' => 'perm_data'));
        $perms = json_decode($perms->perm_data, true);
        if (!$perms['xapi'] && $_SESSION['user']['group'] != 'admin') {
            $data['permission'] = 0;
            $data['notif_msg']['type'] = 'error';
            $data['notif_msg']['msg'] = 'Please contact your account manager to activate API access.';
        }
        $data['page'] = 'API';
        $data['current_page'] = 'xapi';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/xmlApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function smppApi()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['active_page'] = 'SMPP API';

        $data['page'] = 'API';
        $data['current_page'] = 'sapi';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/smppApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getSmppApiClients()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'system_id', 'dt' => 0)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }
        Doo::loadModel('ScSmppClients');
        $obj = new ScSmppClients;
        if ($_SESSION['user']['group'] != 'admin') {
            $obj->user_id = $_SESSION['user']['userid'];
        }

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
        $sdata = Doo::db()->find($obj, $dtdata);

        $robj = Doo::loadModel('ScSmsRoutes', true);
        $uobj = Doo::loadModel('ScUsers', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($sdata as $dt) {
            $rdata = $robj->getRouteData($dt->route_id, 'title');

            if ($_SESSION['user']['group'] == 'admin') {
                if ($dt->user_id != 0) {
                    $udt = $uobj->getProfileInfo($dt->user_id, 'name,category,email,avatar');

                    $user_str = '<div class="media">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '"><img src="' . $udt->avatar . '" alt=""></a></div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0 m-b-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_id . '" class="m-r-xs theme-color">' . ucwords($udt->name) . '</a><small class="text-muted fz-sm">' . ucwords($udt->category) . '</small></h5>
                                                    <p style="font-size: 12px;font-style: Italic;">' . $udt->email . '</p>
                                                </div>
                                            </div>';
                } else {
                    $user_str = '- ' . $this->SCTEXT('NO USER ASSOCIATED') . ' -';
                }
            }



            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewSmppClient/' . $dt->id . '">' . $this->SCTEXT('View Details') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewSmppSms/' . $dt->id . '">' . $this->SCTEXT('View Sent SMS') . '</a></li></ul></div>';
            if ($_SESSION['user']['account_type'] == 1) {
                $output = array($dt->system_id, $button_str);
            } else {
                $output = $_SESSION['user']['group'] == 'admin' ? array($dt->system_id, $user_str, $rdata->title, $button_str) : array($dt->system_id, $rdata->title, $button_str);
            }

            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function viewSmppClient()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['links']['SMPP API'] = Doo::conf()->APP_URL . 'smppApi';
        $data['active_page'] = 'SMPP Client Details';

        //get smpp client details
        $obj = Doo::loadModel('ScSmppClients', true);
        $obj->id = $this->params['id'];
        if ($_SESSION['user']['group'] != 'admin') {
            $obj->user_id = $_SESSION['user']['userid'];
        }
        $data['client'] = Doo::db()->find($obj, array('limit' => 1));

        if (!$data['client']->system_id) {
            //invalid access
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed.';
            return Doo::conf()->APP_URL . 'smppApi';
        }

        $robj = Doo::loadModel('ScSmsRoutes', true);
        $data['client']->route = $robj->getRouteData($data['client']->route_id, 'title')->title;

        $data['page'] = 'API';
        $data['current_page'] = 'smppdetail';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/smppClientDetails', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function viewSmppSms()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['links']['SMPP API'] = Doo::conf()->APP_URL . 'smppApi';
        $data['active_page'] = 'SMPP Sent SMS';

        //get smpp client details
        $obj = Doo::loadModel('ScSmppClients', true);
        $obj->id = $this->params['id'];
        if ($_SESSION['user']['group'] != 'admin') {
            $obj->user_id = $_SESSION['user']['userid'];
        }
        $data['client'] = Doo::db()->find($obj, array('limit' => 1));

        if (!$data['client']->system_id) {
            //invalid access
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed.';
            return Doo::conf()->APP_URL . 'smppApi';
        }

        $robj = Doo::loadModel('ScSmsRoutes', true);
        $data['client']->route = $robj->getRouteData($data['client']->route_id, 'title')->title;

        $data['page'] = 'API';
        $data['current_page'] = 'smppsms';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/smppClientSms', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getSmppSmsList()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['smpp_sms']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'mobile', 'dt' => 0),
            array('db' => 'sender_id', 'dt' => 2),
            array('db' => 'smpp_smsid', 'dt' => 6)
        );
        $sWhere = '';
        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);
        if ($_SESSION['user']['group'] != 'admin') {
            $sWhere = "smpp_client = '" . $this->params['systemid'] . "' AND `user_id` = " . $_SESSION['user']['userid'];
        } else {
            $sWhere = "smpp_client = '" . $this->params['systemid'] . "'";
        }

        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));
            $sWhere .= " AND `sending_time` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
        }

        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND ' . $dtdata['where'];
            }
        }

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'sending_time';
        }
        $obj = Doo::loadModel('ScSmppClientSms', true);


        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
        $sdata = Doo::db()->find($obj, $dtdata);

        $usrobj = Doo::loadModel('ScUsers', true);
        $usrdata = $usrobj->getProfileInfo($sdata[0]->user_id);

        //get dlr codes
        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        $rtobj->id = $sdata[0]->route_id;
        $rdata = Doo::db()->find($rtobj, array('limit' => 1, 'select' => 'smpp_list'));
        //get route custom dlr codes
        $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
        $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code, description, category', 'where' => 'smpp_id IN (' . $rdata->smpp_list . ')'));

        //get network and circle data
        $mccmncqry = "SELECT mccmnc, CONCAT(brand, '||', operator, '||', country_name) as brandop FROM `sc_mcc_mnc_list`";
        $mccmncdata = Doo::db()->fetchAll($mccmncqry, null, PDO::FETCH_KEY_PAIR);


        $delpduobj = Doo::loadModel('ScSmppClientDlrPdustore', true);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($sdata as $dt) {
            $vdlr = $dt->vendor_dlr;
            if ($vdlr != "") {
                $dmap = function ($e) use ($vdlr) {
                    return $e->dlr_code == $vdlr;
                };
                $dlrcobj = array_filter($dlrcodes, $dmap);
                $k = key($dlrcobj);
            }
            if ($vdlr != "" && $dlrcodes[$k]->description != '') {
                print_r($dlrcodes[$k]);
                $dlrdesc = $dlrcodes[$k]->description;
                $dlrcolor = $dlrcodes[$k]->category == '3' ? 'danger' : ($dlrcodes[$k]->category == '1' ? 'success' : 'warning');
            } else {
                $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                $dlrdesc = $dcdata['desc'];
                $dlrcolor = $dcdata['cat'];
            }
            //network and circle
            if ($dt->dlr != '-1') {
                //get this for valid numbers only
                $brand = "";
                $operator = "";
                if ($dt->mccmnc != 0) {
                    $brandstr = $mccmncdata[$dt->mccmnc];
                    $brandar = explode("||", $brandstr);
                    $brand = $brandar[0];
                    $operator = $brandar[1];
                    $country = $brandar[2];
                }
            }

            $smscat = json_decode($dt->sms_type);
            $stxtstr = '';
            $stypestr = '';
            if ($smscat->main == 'text') {

                $stxtstr = '<div class="smstxt-ctr panel panel-custom panel-info">' . htmlspecialchars_decode($dt->sms_text) . '</div>';
                $stypestr = '<span>Text';
                if ($smscat->flash == '1') {
                    $stypestr .= '<i title="Flash" class="fa fa-lg text-primary fa-fixed pointer fa-flash m-l-xs"></i>';
                }
                if ($smscat->unicode == '1') {
                    $stypestr .= '<i title="Unicode" class="fa fa-lg text-primary fa-fixed pointer fa-language m-l-xs"></i>';
                }

                $stypestr .= '</span>';
            } elseif ($smscat->main == 'wap') {
                $stdata = unserialize(base64_decode($dt->sms_text));
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info">
                <h5>' . $stdata['wap_title'] . '</h5> <hr class="m-h-xs"><span class="block"><i class="fa fa-lg fa-globe fa-fixed m-r-xs"></i>' . $stdata['wap_url'] . '</span></div>';

                $stypestr = '<span class="label label-success label-md"><i class="fa fa-lg fa-globe m-r-xs"></i>WAP</span>';
            } elseif ($smscat->main == 'vcard') {
                $stdata = unserialize(base64_decode($dt->sms_text));
                $stxtstr = '<div class="smstxt-ctr img-rounded p-sm bg-info"><span class="block"><i class="fa fa-lg fa-vcard fa-fixed m-r-md"></i>' . $stdata['vcard_lname'] . ', ' . $stdata['vcard_fname'] . '</span><span class="block"><i class="fa fa-lg fa-briefcase fa-fixed m-r-md"></i>' . $stdata['vcard_job'] . ' at ' . $stdata['vcard_comp'] . '</span><span class="block"><i class="fa fa-lg fa-phone fa-fixed m-r-md"></i> ' . $stdata['vcard_tel'] . '</span> <span class="block"><i class="fa fa-lg fa-envelope fa-fixed m-r-md"></i>' . $stdata['vcard_email'] . '</span></div>';

                $stypestr = '<span class="label label-primary label-md"><i class="fa fa-vcard m-r-xs"></i>vCard</span>';
            }

            if ($usrdata->account_type == 0) {
                $prcstr = '<span class="label label-danger">' . $dt->sms_count . '</span>';
            } else {
                $prcstr = '<span class="label label-danger">' . Doo::conf()->currency . $dt->price . '</span>';
            }

            $nstr = '<div class="smstxt-ctr p-sm panel panel-custom panel-primary fz-sm mw-md"><span class="block" title="country"><p>' . $country . '</p></span> <span class="block" title="MCCMNC"><i class="fas fa-lg fa-sim-card fa-fixed m-r-md m-b-xs"></i>' . $dt->mccmnc . '</span><span class="block" title="Network"><i class="fas fa-lg fa-satellite-dish fa-fixed m-r-sm m-t-xs"></i>' . $brand . ', ' . $operator . '</span></div>';

            $delpduobj->sms_id = $dt->smpp_smsid;
            $resp_data = Doo::db()->find($delpduobj, array('limit' => 1, 'desc' => 'id'));

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn btn-sm dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a class="vsubmit_sm" data-submitsm="' . $dt->msgdata . '" href="javascript:void(0);">' . $this->SCTEXT('View SUBMIT_PDU Received') . '</a></li><li><a class="vdel_pdu" data-delsm="' . $resp_data->deliver_sm . '" data-delsmres="' . $resp_data->deliver_sm_resp . '" data-smtime="' . $resp_data->pdu_sent_time . '" data-smrestime="' . $resp_data->last_update . '" href="javascript:void(0);">' . $this->SCTEXT('View DELIVER_SM Sent') . '</a></li></ul></div>';
            $vidstr = '';
            if ($_SESSION['user']['group'] == 'admin' && $dt->vendor_msgid != '') {
                $vidstr = '<i class="fa fa-info-circle text-primary fa-lg pop-over m-l-xs" data-trigger="click" data-title="Operator SMS ID" data-content="' . $dt->vendor_msgid . '"></i>';
            }
            $explainstr = '<i class="fa fa-info-circle text-primary fa-lg pop-over m-l-xs" data-placement="top" data-trigger="click" data-title="DLR Explanation" data-content="<span class=\'label label-sm label-' . $dlrcolor . '\'>' . $dlrdesc . '</span>"></i>';
            $cutstr = $dt->status == 2 && $_SESSION['user']['group'] == 'admin' ? '<span class="label label-sm label-danger m-r-xs">F</span>' : '';

            $output = array($cutstr . $dt->mobile . $vidstr, $nstr, $dt->sender_id, $stxtstr, date(Doo::conf()->date_format_short_time_s, strtotime($dt->sending_time)), $prcstr, $dt->smpp_smsid, $this->getDlrDescription($dt->dlr) . $explainstr, $button_str);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function smsViaApi()
    {
        $reqmode = !$_REQUEST['xml'] ? 'http' : 'xml';
        $helper = new DooSmppcubeHelper;
        //check if maintenance mode
        if (Doo::conf()->maintenance_mode == '1') {
            echo $helper->getApiResponse($reqmode, 'maintenance');
            exit;
        }
        if ($reqmode == 'xml') {
            $xmlstr = $_REQUEST['xml'];
            $suppliedparams = (array)simplexml_load_string($xmlstr);
        } else {
            $suppliedparams = $_REQUEST;
        }
        //collect variables, old API format
        $apikey = $suppliedparams['key'];
        $campaignid = $suppliedparams['campaign'];
        $routeid = $suppliedparams['routeid'];
        $type = $suppliedparams['type'];
        $apicontacts = $reqmode == 'xml' ? (array)$suppliedparams['contacts']->msisdn : explode(",", $suppliedparams['contacts']);
        $senderid = DooTextHelper::cleanInput(urldecode($suppliedparams['senderid']), ' ', 0);
        $apisms = urldecode($suppliedparams['msg']);
        $schedule = $suppliedparams['time'];
        $callback_url = $suppliedparams['dlr_url'];
        $tlv_supplied = json_decode(urldecode($suppliedparams['tlv']), true);
        $bulk_dynamic_flag = 0;

        //prepare for new api
        $mobiles = array();
        foreach ($apicontacts as $contact) {
            array_push($mobiles, ['mobile' => $contact]);
        }

        //manage tlv later if there is any demand for it
        $api_data = [
            "campaignId" => intval($campaignid),
            "routeId" => intval($routeid),
            "sender" => $senderid,
            "mode" => $type,
            "message" => $apisms,
            "contacts" => $mobiles,
            "schedule" => $schedule,
            "notifyUrl" => $callback_url,
        ];
        foreach ($suppliedparams as $key => $value) {
            // Check if the key is not already in the $data array
            if (!array_key_exists($key, $api_data)) {
                $api_data[$key] = $value;
            }
        }
        foreach ($tlv_supplied as $key => $value) {
            // Check if the key is not already in the $data array
            if (!array_key_exists($key, $api_data)) {
                $api_data[strtolower($key)] = $value;
            }
        }

        //hit the new api
        $payload = json_encode($api_data);
        $url = Doo::conf()->APP_URL . 'api/v2/sms';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apikey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        //prepare and send response in old format
        $res = json_decode($response, true);
        if ($res["status"] == "success") {
            echo $helper->getApiResponse($reqmode, 'submitted', ["shootid" => $res["batch_id"]]);
        } else {
            if ($reqmode == 'xml') {
                $output = ["result" => "error", "message" => $res["message"]];
                echo DooSmppcubeHelper::getApiXmlOutput($output);
            } else {
                echo $response;
            }
        }
        exit;
    }

    public function miscApi()
    {
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $userid = $ak_obj->getUserByKey($this->params['key']);

        if (!$userid) die('ERR: INVALID API KEY');

        if ($this->params['mode'] == 'getBalance') {

            if ($userid == '1') {
                die('INFO: ADMIN HAS UNLIMITED BALANCE AND VALIDITY');
            }

            Doo::loadModel('ScUsersCreditData');
            $crobj = new ScUsersCreditData;
            $crobj->user_id = $userid;
            $creditdata = Doo::db()->find($crobj);

            $rtobj = Doo::loadModel('ScSmsRoutes', true);

            $res = array();
            $i = 0;
            foreach ($creditdata as $balnc) {
                $res[$i]['ROUTE_ID'] = $balnc->route_id;
                $res[$i]['ROUTE'] = $rtobj->getRouteData($balnc->route_id, 'title')->title;
                $res[$i]['BALANCE'] = $balnc->credits;
                $i++;
            }

            echo json_encode($res);
            exit;
        }

        if ($this->params['mode'] == 'getDLR') {
            $shootid = $this->params['data'];
            $cobj = Doo::loadModel('ScSentSms', true);
            $cobj->sms_shoot_id = $shootid;
            $cobj->user_id = $userid;
            $dlrdata = Doo::db()->find($cobj);

            if (!$dlrdata) {
                die('ERR: INVALID SHOOT ID');
            }
            $rs = array();
            $ctr = 0;

            $dcobj = Doo::loadModel('ScSmppCustomDlrCodes', true);
            $dlrcodes = Doo::db()->find($dcobj, array('select' => 'vendor_dlr_code as dlr_code, description'));

            foreach ($dlrdata as $dt) {
                $vdlr = $dt->vendor_dlr;
                $dmap = function ($e) use ($vdlr) {
                    return $e->dlr_code == $vdlr;
                };
                $dlrcobj = array_filter($dlrcodes, $dmap);
                $k = key($dlrcobj);
                if ($dlrcodes[$k]->description != '') {
                    $dlrdesc = $dlrcodes[$k]->description;
                } else {
                    $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                    $dlrdesc = $dcdata['desc'];
                }
                $rs[$ctr]['MSISDN'] = $dt->mobile;
                $rs[$ctr]['DLR'] = $this->getDlrDescription($dt->dlr);
                $rs[$ctr]['DESC'] = $dlrdesc;
                $ctr++;
            }
            echo json_encode($rs);
        }
    }

    public function miscXmlApi()
    {
        $xmldata = simplexml_load_string($_REQUEST['xml']);


        if ($xmldata->mode != 'getKey') { //cuz can't validate key if not supplied
            Doo::loadModel('ScApiKeys');
            $ak_obj = new ScApiKeys;
            $userid = $ak_obj->getUserByKey($xmldata->key);

            if (!$userid) {
                echo ('<?xml version="1.0" encoding="UTF-8"?>
                <Api_resp>
                <err>INVALID API KEY</err>
                </Api_resp>');
                exit;
            }
        }

        if ($xmldata->mode == 'getBalance') {

            if ($userid == 1) {
                echo ('<?xml version="1.0" encoding="UTF-8"?>
            <Api_resp>
            <err>ADMIN HAS UNLIMITED BALANCE AND VALIDITY</err>
            </Api_resp>');
                exit;
            }

            Doo::loadModel('ScUsersCreditData');
            $crobj = new ScUsersCreditData;
            $crobj->user_id = $userid;
            $creditdata = Doo::db()->find($crobj);

            $rtobj = Doo::loadModel('ScSmsRoutes', true);

            $res = '<?xml version="1.0" encoding="UTF-8"?>
                <Api_resp>';

            foreach ($creditdata as $balnc) {
                $res .= '<credits>
                    ';
                $res .= '<route_id>' . $balnc->route_id . '</route_id>
                    ';
                $res .= '<route>' . $rtobj->getRouteData($balnc->route_id, 'title')->title . '</route>
                    ';
                $res .= '<balance>' . $balnc->credits . '</balance>
                    ';
                $res .= '</credits>
                    ';
            }

            $res .= '</Api_resp>';
            echo $res;
            exit;
        }


        if ($xmldata->mode == 'getDLR') {
            $shootid = $xmldata->sms_shoot_id;

            $cobj = Doo::loadModel('ScSentSms', true);
            $cobj->sms_shoot_id = $shootid;
            $cobj->user_id = $userid;
            $dlrdata = Doo::db()->find($cobj);

            if (!$dlrdata) {
                echo ('<?xml version="1.0" encoding="UTF-8"?>
            <Api_resp>
            <err>INVALID SHOOT ID</err>
            </Api_resp>');
                exit;
            }
            $rs = array();
            $ctr = 0;

            $dcobj = Doo::loadModel('ScRoutesCustomDlrCodes', true);
            $dcobj->route_id = $dlrdata[0]->route_id;
            $dlrcodes = Doo::db()->find($dcobj, array('select' => 'dlr_code, description'));

            $xmlres = '<?xml version="1.0" encoding="UTF-8"?>
            <Api_resp>';

            foreach ($dlrdata as $dt) {
                $vdlr = $dt->vendor_dlr;
                $dmap = function ($e) use ($vdlr) {
                    return $e->dlr_code == $vdlr;
                };
                $dlrcobj = array_filter($dlrcodes, $dmap);
                $k = key($dlrcobj);
                if ($dlrcodes[$k]->description != '') {
                    $dlrdesc = $dlrcodes[$k]->description;
                } else {
                    $dcdata = $this->getSmppcubeDlrcodeDescription($vdlr);
                    $dlrdesc = $dcdata['desc'];
                }


                $xmlres .= '<report>';
                $xmlres .= '<msisdn>' . $dt->mobile . '</msisdn>';
                $xmlres .= '<dlr>' . $this->getDlrDescription($dt->dlr) . '</dlr>';
                $xmlres .= '<desc>' . $dlrdesc . '</desc>';
                $xmlres .= '</report>';
            }
            $xmlres .= '</Api_resp>';
            echo $xmlres;
            exit;
        }
    }


    //11. URL Shortener

    public function manageShortUrls()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tinyurl']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Short URLs';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_tinyurl';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageTinyUrl', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllShortUrls()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tinyurl']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all urls for this account
        Doo::loadModel('ScShortUrlsMaster');
        $obj = new ScShortUrlsMaster;
        $obj->user_id = $_SESSION['user']['userid'];
        $urls = Doo::db()->find($obj);
        $total = count($urls);
        $tinydomain = DooTextHelper::getTinyUrl($_SESSION['user']['userid']);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($urls as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="javascript:void(0);" class="del-surl" data-urlid="' . $dt->id . '">' . $this->SCTEXT('Delete URL') . '</a></li></ul></div>';

            if ($dt->type != 0) {
                $urlstr = $tinydomain . '/' . $dt->url_idf . '<i title="' . $this->SCTEXT('Trackable URL') . '" class="fa fa-lg text-primary fa-fixed pointer fa-line-chart m-l-sm"></i>';
            } else {
                $urlstr = $tinydomain . '/' . $dt->url_idf;
            }


            $output = array($ctr, '<div class="panel panel-info panel-custom">' . $dt->redirect_url . '</div>', $urlstr, $button_str);
            array_push($res['aaData'], $output);
            $ctr++;
        }
        echo json_encode($res);
    }

    public function getUseShortUrls()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tinyurl']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all urls for this account
        Doo::loadModel('ScShortUrlsMaster');
        $obj = new ScShortUrlsMaster;
        $obj->user_id = $_SESSION['user']['userid'];
        $urls = Doo::db()->find($obj);
        $total = count($urls);
        $tinydomain = DooTextHelper::getTinyUrl($_SESSION['user']['userid']);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($urls as $dt) {
            if ($dt->type != 0) {
                $tstr = '<span class="label label-success">' . $this->SCTEXT('Trackable Link') . '</span>';
                $trkstr = '<i title="' . $this->SCTEXT('Trackable URL') . '" class="fa fa-lg text-primary fa-fixed pointer fa-line-chart m-l-sm"></i>';
            } else {
                $tstr = '<span class="label label-info">' . $this->SCTEXT('Regular Link') . '</span>';
                $trkstr = '';
            }
            $turl = $tinydomain . '/' . $dt->url_idf;
            $button_str = '<button type="button" data-track="' . $dt->type . '" data-dismiss="modal" data-turl="' . ($turl) . '" class="useTurlBtn btn btn-primary"><i class="fa fa-lg fa-check-circle text-white"></i> &nbsp; ' . $this->SCTEXT('Use This') . '</button>';

            $output = array($turl . $trkstr, '<div style="line-break:anywhere;" class="panel panel-info panel-custom">' . $dt->redirect_url . '</div>', $tstr, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addShortUrl()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tinyurl']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Short URLs'] = Doo::conf()->APP_URL . 'manageShortUrls';
        $data['active_page'] = 'Add New Short URL';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_tinyurl';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addTinyUrl', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function generateShortUrl()
    {
        $this->isLogin();
        $utype = intval($_POST['urltype']);
        $durl = $_POST['redurl'];

        //validate
        $vobj = Doo::loadHelper('DooValidator', true);
        $msg = $vobj->testUrl($durl, 'no');
        if ($msg == 'no') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid URL format. Please include http/https in your URL and extension e.g. http://fb.com';
            return Doo::conf()->APP_URL . 'addShortUrl';
        } else {
            //valid URL, generate link identifier
            $uid = $_SESSION['user']['userid'];
            $turl = $this->generateUrlIdf($uid);
            $obj = Doo::loadModel('ScShortUrlsMaster', true);
            $obj->user_id = $uid;
            $obj->url_idf = $turl;
            $obj->redirect_url = $durl;
            $obj->type = $utype;
            Doo::db()->insert($obj);

            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Short URL successfully generated.';
            return Doo::conf()->APP_URL . 'manageShortUrls';
        }
    }

    public function deleteShortUrl()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tinyurl']) {
            //denied
            return array('/denied', 'internal');
        }
        $obj = Doo::loadModel('ScShortUrlsMaster', true);
        $obj->id = intval($this->params['id']);
        $obj->user_id = $_SESSION['user']['userid'];
        Doo::db()->delete($obj, array('limit' => 1));
        //remove from msisdn map table
        $mobj = Doo::loadModel('ScShortUrlsMsisdnMap', true);
        Doo::db()->delete($mobj, array('where' => 'parent_url_id = ' . intval($this->params['id'])));

        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Short URL successfully deleted.';
        return Doo::conf()->APP_URL . 'manageShortUrls';
    }

    public function generateUrlIdf($uid, $len = 6)
    {

        $seed = time() . mt_rand() . $uid;
        $turl = substr(md5($seed), intval(0 - $len), $len);

        //check if already exists
        if ($len == 6) {
            //regular link request
            $obj = Doo::loadModel('ScShortUrlsMaster', true);
            $obj->url_idf = $turl;
            $tid = Doo::db()->find($obj, array('select' => 'id', 'limit' => 1));
            if ($tid->id) {
                //already exists regenerate
                $this->generateUrlIdf($uid);
            } else {
                return $turl;
            }
        } else {
            //request for personalized link
            $obj = Doo::loadModel('ScShortUrlsMsisdnMap', true);
            $obj->url_idf = $turl;
            $tid = Doo::db()->find($obj, array('select' => 'id', 'limit' => 1));
            if ($tid->id) {
                //already exists regenerate
                $this->generateUrlIdf($uid, 7);
            } else {
                return $turl;
            }
        }
    }


    //12. Media Links
    public function manageCampaignMedia()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['media_links']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Media';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_media';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageMedia', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllCampaignMedia()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['media_links']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all media for this account
        // $obj = Doo::loadModel('ScCampaignMedia', true);
        // $obj->user_id = $_SESSION['user']['userid'];
        $query = "SELECT m.media_title, m.file_info, m.long_idf, t.url_idf FROM sc_campaign_media m, sc_short_urls_master t WHERE t.id=m.tinyurl_id AND m.user_id = " . intval($_SESSION['user']['userid']);
        $media = Doo::db()->fetchAll($query, null, PDO::FETCH_OBJ);
        $total = count($media);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        $ctr = 1;
        foreach ($media as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="javascript:void(0);" class="del-med" data-mid="' . $dt->id . '">' . $this->SCTEXT('Delete Media') . '</a></li></ul></div>';
            $fileinfo = json_decode($dt->file_info, true);
            if (strpos($fileinfo['mime_type'], 'image') !== false) {
                $fileicon = 'fas fa-file-image text-primary';
            } elseif (strpos($fileinfo['mime_type'], 'audio') !== false) {
                $fileicon = 'fas fa-file-audio text-success';
            } elseif (strpos($fileinfo['mime_type'], 'video') !== false) {
                $fileicon = 'fas fa-file-video text-inverse';
            } elseif (strpos($fileinfo['mime_type'], 'pdf') !== false) {
                $fileicon = 'fas fa-file-pdf text-danger';
            }
            $file = '<div class="media"><div class="media-left"><i class="' . $fileicon . ' fa-5x"></i></div><div class="media-body p-l-sm" style="line-height: 2em;"><kbd>' . $fileinfo['mime_type'] . '</kbd><br><span class="label label-primary"><a class="text-white" target="_blank" href="' . Doo::conf()->APP_URL . 'viewMedia/' . $dt->long_idf . '"><i class="fas fa-download fz-sm m-r-xs"></i>View/Download</a></span></div></div>';
            $output = array($ctr, $dt->media_title, '<span class="code"><b>' . Doo::conf()->tinyurl . '/' . $dt->url_idf . '</b></span>', $file, $button_str);
            array_push($res['aaData'], $output);
            $ctr++;
        }
        echo json_encode($res);
    }

    public function getUseCampaignMedia()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['media_links']) {
            //denied
            return array('/denied', 'internal');
        }
        //get all media for this account
        $query = "SELECT m.media_title, m.file_info, m.long_idf, t.url_idf FROM sc_campaign_media m, sc_short_urls_master t WHERE t.id=m.tinyurl_id AND m.user_id = " . intval($_SESSION['user']['userid']);
        $media = Doo::db()->fetchAll($query, null, PDO::FETCH_OBJ);
        $total = count($media);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($media as $dt) {
            $turl = Doo::conf()->tinyurl . '/' . $dt->url_idf;
            $button_str = '<button type="button" data-dismiss="modal" data-turl="' . ($turl) . '" class="useMediaBtn btn btn-primary"><i class="fa fa-lg fa-check-circle text-white"></i> &nbsp; ' . $this->SCTEXT('Use This') . '</button>';
            $fileinfo = json_decode($dt->file_info, true);
            if (strpos($fileinfo['mime_type'], 'image') !== false) {
                $fileicon = 'fas fa-file-image text-primary';
            } elseif (strpos($fileinfo['mime_type'], 'audio') !== false) {
                $fileicon = 'fas fa-file-audio text-success';
            } elseif (strpos($fileinfo['mime_type'], 'video') !== false) {
                $fileicon = 'fas fa-file-video text-inverse';
            } elseif (strpos($fileinfo['mime_type'], 'pdf') !== false) {
                $fileicon = 'fas fa-file-pdf text-danger';
            }
            $file = '<div class="media"><div class="media-left"><i class="' . $fileicon . ' fa-5x"></i></div><div class="media-body p-l-sm" style="line-height: 2em;"><kbd>' . $fileinfo['mime_type'] . '</kbd><br><span class="label label-primary"><a class="text-white" target="_blank" href="' . Doo::conf()->APP_URL . 'viewMedia/' . $dt->long_idf . '"><i class="fas fa-download fz-sm m-r-xs"></i>View/Download</a></span></div></div>';
            $output = array($file, $dt->media_title, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addCampaignMedia()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['media_links']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Media'] = Doo::conf()->APP_URL . 'manageCampaignMedia';
        $data['active_page'] = 'Upload New Media';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_media';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addMedia', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveCampaignMedia()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['media_links']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $title = $_POST['mtitle'];
        $filename = $_POST['uploadedFiles'][0];
        if ($filename == '') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'No file was uploaded. Please upload a file.';
            return Doo::conf()->APP_URL . 'addCampaignMedia';
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, Doo::conf()->global_upload_dir . 'media/' . $filename);
        finfo_close($finfo);
        $fileinfo = json_encode(array(
            'original_name' => $filename,
            'mime_type' => $mimetype
        ));
        //generate a long URL for this file
        $long_idf = DooSmppcubeHelper::uuidv4();
        $real_url = Doo::conf()->APP_URl . 'viewMedia/' . $long_idf;
        //generate a tiny URL for this file
        $uid = $_SESSION['user']['userid'];
        $turl = $this->generateUrlIdf($uid);
        $obj = Doo::loadModel('ScShortUrlsMaster', true);
        $obj->user_id = $uid;
        $obj->url_idf = $turl;
        $obj->redirect_url = $real_url;
        $obj->type = 1; //always trackable
        $obj->media_link = 1;
        $turl_id = Doo::db()->insert($obj);
        //save the file in db
        $mobj = Doo::loadModel('ScCampaignMedia', true);
        $mobj->user_id = $uid;
        $mobj->media_title = $title;
        $mobj->file_info = $fileinfo;
        $mobj->long_idf = $long_idf;
        $mobj->tinyurl_id = $turl_id;
        Doo::db()->insert($mobj);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Media uploaded successfully';
        return Doo::conf()->APP_URL . 'manageCampaignMedia';
    }

    public function deleteCampaignMedia()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['media_links']) {
            //denied
            return array('/denied', 'internal');
        }
        $mobj = Doo::loadModel('ScCampaignMedia', true);
        $mobj->id = $this->params['id'];
        $mdata = Doo::db()->find($mobj, array('limit' => 1));
        if ($mdata->user_id != $_SESSION['user']['userid']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action not allowed';
            return Doo::conf()->APP_URL . 'manageCampaignMedia';
        }
        //delete the tiny URL and associated msisdn maps
        $tobj = Doo::loadModel('ScShortUrlsMaster', true);
        $tobj->id = $mdata->tinyurl_id;
        Doo::db()->delete($tobj);
        //delete the media file
        Doo::db()->delete($mobj);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Media deleted successfully';
        return Doo::conf()->APP_URL . 'manageCampaignMedia';
    }

    public function viewMedia()
    {
        //get param
        $id = DooTextHelper::cleanInput($this->params['id'], '-');
        //check which media file it belongs to
        $obj = Doo::loadModel('ScCampaignMedia', true);
        $obj->long_idf = $id;
        $filedata = Doo::db()->find($obj, array('limit' => 1));
        if (!$filedata->id) {
            echo 'File Not Found. Please check the link';
            exit;
        }
        $file = json_decode($filedata->file_info, true);
        $fullpath = Doo::conf()->global_upload_dir . 'media/' . $file['original_name'];
        //get the path and output the file
        /* Send headers and file to visitor */
        header('Content-Description: File Transfer');
        header('Content-Disposition: inline; filename=' . basename($file['original_name']));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullpath));
        header("Content-Type: " . $file['mime_type']);
        readfile($fullpath);
        exit;
    }




    //13. Misc

    public function editUserProfile()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        if (isset($_SESSION['tabpage'])) {
            $data['tabpage'] = $_SESSION['tabpage'];
            unset($_SESSION['tabpage']);
        }

        //get profile info
        $uobj = Doo::loadModel('ScUsers', true);
        $data['uinfo'] = $uobj->getProfileInfo($_SESSION['user']['userid'], 'name, gender, avatar, mobile, email, email_verified, mobile_verified');
        //get company info
        $cobj = Doo::loadModel('ScUsersCompany', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $data['cinfo'] = Doo::db()->find($cobj, array('limit' => 1));
        $data['userpg'] = unserialize($data['cinfo']->c_payment);
        //get payment options
        $pobj = Doo::loadHelper('DooPaymentHelper', true);
        $data['payments'] = $pobj->getAllPaymentGateways();
        //breadcrums
        $data['active_page'] = 'Edit Profile';

        //check if whatapp enabled and this user has a WABA connected
        if (Doo::conf()->whatsapp == 1) {
            $wbobj = Doo::loadModel('WbaAgentBusinessProfiles', true);
            $wbobj->user_id = $_SESSION['user']['userid'];
            $data['waba_profiles'] = Doo::db()->find($wbobj);
        }

        $data['page'] = 'Dashboard';
        $data['current_page'] = 'edit_profile';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editProfile', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function loadWabaProfile()
    {
        $this->isLogin();
        $wbobj = Doo::loadModel('WbaAgentBusinessProfiles', true);
        $wbobj->phone_id = $this->params['phoneid'];
        $wbobj->user_id = $_SESSION['user']['userid'];
        $data = Doo::db()->find($wbobj, array('limit' => 1));
        $response = array("bp_profile_picture" => $data->bp_profile_picture, "bp_about" => $data->bp_about, "bp_description" => $data->bp_description, "display_phone" => $data->display_phone, "verified_name" => $data->verified_name);
        echo json_encode($response);
        exit;
    }

    public function updateWabaProfile()
    {
        $this->isLogin();
        //call the api to update the profile details
        $phone_id = $_POST['phoneid'];
        $about = $_POST['about'];
        $desc = $_POST['description'];
        $capverUrl = 'https://graph.facebook.com/v19.0/' . $phone_id . '/whatsapp_business_profile';
        $postdata =
            array(
                'messaging_product' => "whatsapp",
                'description' => $desc,
                'about' => $about
            );
        $curl = curl_init($capverUrl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array("Content-type: application/json", "charset: utf-8", "Authorization: Bearer " . Doo::conf()->wba_perm_token)
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($status != 201 && $status != 200) {
            //die("Error: call to URL $capverUrl failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            $error_msg = json_decode($json_response, true);
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = "Share this with Admin: " . $json_response;
            $_SESSION['tabpage'] = 'tab-4';
            echo "error";
            exit;
        }

        $response = json_decode($json_response, true);
        $_SESSION['tabpage'] = 'tab-4';
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'WhatsApp Business Profile updated successfully';

        //if successful in the meta, update locally in the db as well

        $wbobj = Doo::loadModel('WbaAgentBusinessProfiles', true);
        $wbobj->phone_id = $phone_id;
        $wbobj->user_id = $_SESSION['user']['userid'];
        $wbdata = Doo::db()->find($wbobj, array('limit' => 1));

        $wbobj->id = $wbdata->id;
        $wbobj->bp_about = $about;
        $wbobj->bp_description = $desc;
        Doo::db()->update($wbobj);

        echo "success";
        exit;
    }

    public function saveUserProfile()
    {
        $this->isLogin();
        //collect values
        $uname = $_POST['uname']; //DooTextHelper::cleanInput($_POST['uname'], ' ', 0);
        $uphn = intval($_POST['uphn']);
        $uemail = $_POST['uemail'];
        $gender = $_POST['gender'];

        //check if mobile was changed if yes, set it to unverified
        //check if email was changed. if yes, set it to unverified

        if (!DooTextHelper::verifyFormData('email', $uemail)) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Invalid email address.';
            return Doo::conf()->APP_URL . 'editUserProfile';
        }

        //check if file was uploaded
        $uploaded = 0;
        if (file_exists($_FILES['uavatar']['tmp_name']) && is_uploaded_file($_FILES['uavatar']['tmp_name'])) {
            //file is uploaded
            $uploaded = 1;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $_FILES['uavatar']['tmp_name']);
            finfo_close($finfo);
            $allowed_mime_types = array(
                'image/png',
                'image/jpeg'
            );
            $allowed_extentions = array('png', 'jpg', 'jpeg');

            //specific checks for image
            list($width, $height, $type, $attr) = getimagesize($_FILES['uavatar']['tmp_name']);
            $img_mime = image_type_to_mime_type($type);

            if (!$width) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid image file.';
            }

            //check if extension is among allowed ones
            $doofile = Doo::loadHelper('DooFile', true);
            if (!$doofile->checkFileExtension('uavatar', $allowed_extentions)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide png, jpg or jpeg file.';
            }
            //check if mime type is among allowed ones
            if (!in_array($mimetype, $allowed_mime_types) || !in_array($img_mime, $allowed_mime_types)) {
                $fail = 1;
                $reason = 'Invalid File supplied. Please provide valid image file.';
            }

            //return
            if ($fail == 0) {
                //rename and upload file
                $newfile = $doofile->upload(Doo::conf()->image_upload_dir . 'upix/', 'uavatar');
            } else {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = $reason;
                return Doo::conf()->APP_URL . 'editUserProfile';
            }
        }

        //renew information in session
        $_SESSION['user']['name'] = $uname;

        //update profile
        $uobj = Doo::loadModel('ScUsers', true);
        $uobj->name = $uname;
        $uobj->gender = $gender;
        $uobj->mobile = $uphn;
        $uobj->email = $uemail;

        if ($_SESSION['user']['mobile'] != $uphn) {
            $_SESSION['user']['mobile'] = $uphn;
            $uobj->mobile_verified = 0;
        }
        if ($_SESSION['user']['email'] != $uemail) {
            $_SESSION['user']['email'] = $uemail;
            $uobj->email_verified = 0;
        }
        if ($uploaded == 1) {
            $_SESSION['user']['avatar'] = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'upix/' . $newfile;
            $uobj->avatar = Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'upix/' . $newfile;
        }

        Doo::db()->update($uobj, array('where' => 'user_id=' . $_SESSION['user']['userid']));

        //record activity
        $actData['activity_type'] = 'EDIT PROFILE';
        $actData['activity'] = Doo::conf()->user_profile_edit;
        $ulobj = Doo::loadModel('ScLogsUserActivity', true);
        $ulobj->addLog($_SESSION['user']['userid'], $actData);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Profile information saved successfully.';

        session_write_close();

        return Doo::conf()->APP_URL . 'editUserProfile';
    }

    public function verifyViaOTP()
    {
        $this->isLogin();
        //get details
        if ($this->params['mode'] == 'mobile') {
            $otp = rand(100000, 999999);
            //$otp = '123456';
            $_SESSION['session_otp'] = $otp;
            //send sms

            //use upline creds for sending sms because this task is system related.
            Doo::loadModel('ScWebsitesSignupSettings');
            $stobj = new ScWebsitesSignupSettings;
            $stobj->user_id = $_SESSION['user']['upline'] == 0 ? $_SESSION['user']['userid'] : $_SESSION['user']['upline'];
            $res = Doo::db()->find($stobj, array('limit' => 1, 'select' => 'notif_data'));
            $sendsms_opts = unserialize($res->notif_data);

            $mobile = $_SESSION['user']['mobile'];
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
            $api_key = $akobj->getApiKey($_SESSION['user']['upline'] == 0 ? $_SESSION['user']['userid'] : $_SESSION['user']['upline']); //sender user id
            $smstext = $this->SCTEXT('Your One Time Password for your phone verification is:') . ' ' . $otp;
            $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . intval($cmpobj->getCampaignId($_SESSION['user']['upline'] == 0 ? $_SESSION['user']['userid'] : $_SESSION['user']['upline'], 'system')) . '&routeid=' . $sendsms_opts['sms_route'] . '&type=text&contacts=' . $mobile . '&senderid=' . $sendsms_opts['sms_sid'] . '&msg=' . urlencode($smstext);

            $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));

            //return
            exit;
        }

        if ($this->params['mode'] == 'email') {
            $otp = rand(100000, 999999);
            //$otp = '789012';
            $_SESSION['session_otp_email'] = $otp;
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
                    "user_id" => $_SESSION['user']['userid'],
                    "platform_data" => $osdata,
                    "incidentDateTime" => date(Doo::conf()->date_format_db),
                    "otpCode" => $otp,
                    "actionType" => "Email Verification",
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
            exit;
        }
    }

    public function confirmOTP()
    {
        $this->isLogin();
        //get details
        if ($this->params['mode'] == 'mobile') {
            $otp = $_REQUEST['otp'];
            if ($_SESSION['session_otp'] == $otp) {
                //matched, verify the phone in DB
                $res['match_result'] = 'yes';
                $uobj = Doo::loadModel('ScUsers', true);
                $uobj->mobile_verified = 1;
                Doo::db()->update($uobj, array('where' => 'user_id=' . $_SESSION['user']['userid']));
                unset($_SESSION['session_otp']);
                $_SESSION['notif_msg']['type'] = 'success';
                $_SESSION['notif_msg']['msg'] = 'Your phone number is verified successfully.';
                //record activity
                $actData['activity_type'] = 'PROFILE VERIFY';
                $actData['activity'] = Doo::conf()->user_verify_mobile;
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);
            } else {
                //return error
                $res['match_result'] = 'no';
            }
            echo json_encode($res);
            exit;
        }

        if ($this->params['mode'] == 'email') {
            $otp = $_REQUEST['otp'];
            if ($_SESSION['session_otp_email'] == $otp) {
                //matched, verify the email in DB
                $res['match_result'] = 'yes';
                $uobj = Doo::loadModel('ScUsers', true);
                $uobj->email_verified = 1;
                Doo::db()->update($uobj, array('where' => 'user_id=' . $_SESSION['user']['userid']));
                unset($_SESSION['session_otp_email']);
                $_SESSION['notif_msg']['type'] = 'success';
                $_SESSION['notif_msg']['msg'] = 'Your email is verified successfully.';
                //record activity
                $actData['activity_type'] = 'PROFILE VERIFY';
                $actData['activity'] = Doo::conf()->user_verify_email;
                $ulobj = Doo::loadModel('ScLogsUserActivity', true);
                $ulobj->addLog($_SESSION['user']['userid'], $actData);
            } else {
                //return error
                $res['match_result'] = 'no';
            }
            echo json_encode($res);
            exit;
        }
    }

    public function saveUserPassword()
    {
        $this->isLogin();
        //verify old password
        $uobj = Doo::loadModel('ScUsers', true);
        $uobj->user_id = $_SESSION['user']['userid'];
        $user = Doo::db()->find($uobj, array('limit' => 1, 'select' => 'login_id, password'));

        Doo::loadHelper('DooEncrypt');
        $hfunck = base64_encode($user->login_id . '_' . base64_encode('smppcubehash'));
        $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $dbpass = $encobj->decrypt($user->password, $hfunck);

        if ($dbpass != $_POST['oldpass']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['tabpage'] = 'tab-2';
            $_SESSION['notif_msg']['msg'] = 'Old password did not match.';
            return Doo::conf()->APP_URL . 'editUserProfile';
        }

        if ($_POST['newpass1'] != $_POST['newpass2']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['tabpage'] = 'tab-2';
            $_SESSION['notif_msg']['msg'] = "New passwords don't match each other.";
            return Doo::conf()->APP_URL . 'editUserProfile';
        }
        //encrypt and save new password
        $encpass = $encobj->encrypt($_POST['newpass1'], $hfunck);
        $uobj->password = $encpass;
        Doo::db()->update($uobj);
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['tabpage'] = 'tab-2';
        $_SESSION['notif_msg']['msg'] = "Password has been changed successfully.";
        return Doo::conf()->APP_URL . 'editUserProfile';
    }

    public function saveCompanyInfo()
    {
        $this->isLogin();

        $cobj = Doo::loadModel('ScUsersCompany', true);
        $cobj->user_id = $_SESSION['user']['userid'];
        $cdata = Doo::db()->find($cobj, array('limit' => 1));

        $payment['channel'] = $_POST['userpg'];
        if ($_POST['userpg'] == 'paypal') {
            $payment['email'] = DooTextHelper::cleanInput($_POST['cpaypal'], "@.-_&");
            $payment['clientid'] = base64_encode(DooTextHelper::cleanInput($_POST['pclid'], "_-", 0));
            $payment['authkey'] = base64_encode(DooTextHelper::cleanInput($_POST['pauthk'], "_-", 0));
        }
        if ($_POST['userpg'] == 'stripe') {
            $payment['publishable_key'] = base64_encode(DooTextHelper::cleanInput($_POST['publishable_key'], "-", 0));
            $payment['secret_key'] = base64_encode(DooTextHelper::cleanInput($_POST['secret_key'], "-", 0));
        }
        if ($_POST['userpg'] == 'paystack') {
            $payment['public_key'] = base64_encode(DooTextHelper::cleanInput($_POST['public_key'], "-", 0));
            $payment['secret_key'] = base64_encode(DooTextHelper::cleanInput($_POST['secret_key_ps'], "-", 0));
        }
        $payment['bank_details'] = $_POST['offline_payment'];

        //echo DooTextHelper::cleanInput($_POST['caddr']," .)(\n&-@,\/:",0);die;
        if ($cdata->id) {
            //update
            $cobj->id = $cdata->id;
            $cobj->c_name = $_POST['cname']; // DooTextHelper::cleanInput($_POST['cname'], " .)(&-", 0);
            $cobj->c_address = $_POST['caddr']; // DooTextHelper::cleanInput($_POST['caddr'], " .)(\n&-@,\/:", 0);
            $cobj->c_phone = DooTextHelper::cleanInput($_POST['cphn'], " ()+", 0);
            $cobj->c_email = DooTextHelper::cleanInput($_POST['cmail'], "@.-_&");
            $cobj->c_vat = DooTextHelper::cleanInput($_POST['cvat'], "-");
            $cobj->c_gst = DooTextHelper::cleanInput($_POST['cgst'], "-");
            $cobj->c_stax = DooTextHelper::cleanInput($_POST['cstax'], "-");
            $cobj->c_regno = DooTextHelper::cleanInput($_POST['crno'], " #-", 0);
            $cobj->c_payment = serialize($payment);

            Doo::db()->update($cobj, array('limit' => 1));
        } else {
            //insert
            $cobj->user_id = $_SESSION['user']['userid'];
            $cobj->c_name = $_POST['cname']; //DooTextHelper::cleanInput($_POST['cname'], '.()-& ', 0);
            $cobj->c_address = $_POST['caddr']; // DooTextHelper::cleanInput($_POST['caddr'], " .)(\n&-@,\/:", 0);
            $cobj->c_phone = DooTextHelper::cleanInput($_POST['cphn'], " ()+", 0);
            $cobj->c_email = DooTextHelper::cleanInput($_POST['cmail'], "@.-_&");
            $cobj->c_vat = DooTextHelper::cleanInput($_POST['cvat'], "-");
            $cobj->c_gst = DooTextHelper::cleanInput($_POST['cgst'], "-");
            $cobj->c_stax = DooTextHelper::cleanInput($_POST['cstax'], "-");
            $cobj->c_regno = DooTextHelper::cleanInput($_POST['crno'], " #-", 0);
            $cobj->c_payment = serialize($payment);

            Doo::db()->insert($cobj);
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['tabpage'] = 'tab-3';
        $_SESSION['notif_msg']['msg'] = "Company information saved successfully.";
        return Doo::conf()->APP_URL . 'editUserProfile';
    }

    public function userSettings()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['active_page'] = 'Account Settings';

        //read settings
        $usobj = Doo::loadModel('ScUsersSettings', true);
        $usobj->user_id = $_SESSION['user']['userid'];
        $data['sdata'] = Doo::db()->find($usobj, array('limit' => 1));

        //check for tiny urls
        $utobj = Doo::loadModel('ScUsersTinyurl', true);
        $utobj->user_id = $_SESSION['user']['userid'];
        $data['turldata'] = Doo::db()->find($utobj, array('limit' => 1));

        $data['page'] = 'Dashboard';
        $data['current_page'] = 'user_settings';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/userSet', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveUserSettings()
    {
        $this->isLogin();
        $usobj = Doo::loadModel('ScUsersSettings', true);
        $usobj->user_id = $_SESSION['user']['userid'];
        $usdata = Doo::db()->find($usobj, array('limit' => 1));
        //edit code blocks to save default mo url as well
        if ($usdata->id) {
            //update
            $usobj->id = $usdata->id;
            $usobj->email_daily_sms = intval($_POST['dsflag']);
            $usobj->email_daily_credits = intval($_POST['dcflag']);
            $usobj->email_app_notif = intval($_POST['enflag']);
            $usobj->def_lang = $_POST['lang'];
            $usobj->def_route = intval($_POST['defrt']);
            $usobj->default_dlr_url = $_POST['defdlrurl'];
            $usobj->default_mo_url = $_POST['defmourl'];

            Doo::db()->update($usobj, array('limit' => 1));
        } else {
            //insert
            $usobj->user_id = $_SESSION['user']['userid'];
            $usobj->email_daily_sms = intval($_POST['dsflag']);
            $usobj->email_daily_credits = intval($_POST['dcflag']);
            $usobj->email_app_notif = intval($_POST['enflag']);
            $usobj->def_lang = $_POST['lang'];
            $usobj->def_route = intval($_POST['defrt']);
            $usobj->default_dlr_url = $_POST['defdlrurl'];
            $usobj->default_mo_url = $_POST['defmourl'];

            Doo::db()->insert($usobj);
        }


        //check for tiny url
        $utobj = Doo::loadModel('ScUsersTinyurl', true);
        $utobj->user_id = $_SESSION['user']['userid'];
        $turl = Doo::db()->find($utobj, array('limit' => 1));
        $clientdomain = DooTextHelper::cleanInput($_POST['customturl'], '.');
        if (intval($_POST['turlflag']) == 0) {
            //use system domain
            if ($turl->domain) {
                $domain = $turl->domain;
                //delete any tiny url entry
                $utobj->id = $turl->id;
                Doo::db()->delete($utobj);
                //remove config from nginx
                $this->load()->helper("DooSSH");
                $ssh = new Net_SSH2(Doo::conf()->server_ip);
                if ($ssh->login('root', base64_decode(Doo::conf()->hexpass))) {
                    //remove nginx conf
                    $ssh->exec("cd /etc/nginx/sites-enabled/; rm -rf $domain");
                    $ssh->exec("systemctl reload nginx");
                }
            }
        } else {
            //custom domain
            if ($turl->id) {
                if ($turl->domain != $clientdomain) {
                    //domain was modified, add config for new domain and remove old domain
                    $olddomain = $turl->domain;
                    $utobj->id = $turl->id;
                    $utobj->domain = $clientdomain;
                    Doo::db()->update($utobj);
                    //remove old and add new
                    $this->load()->helper("DooSSH");
                    $ssh = new Net_SSH2(Doo::conf()->server_ip);
                    if ($ssh->login('root', base64_decode(Doo::conf()->hexpass))) {
                        //remove nginx conf
                        $ssh->exec("cd /etc/nginx/sites-enabled/; rm -rf $olddomain");
                        //add nginx conf
                        $confstr = str_replace("yourdomain", $clientdomain, Doo::conf()->nginx_conf_template);
                        $ssh->exec("echo -e '$confstr' >> /etc/nginx/sites-enabled/$clientdomain");
                        $ssh->exec("systemctl reload nginx");
                    }
                }
            } else {
                //insert new record
                $utobj->user_id = $_SESSION['user']['userid'];
                $utobj->domain = $clientdomain;
                Doo::db()->insert($utobj);
                //add nginx entry
                $this->load()->helper("DooSSH");
                $ssh = new Net_SSH2(Doo::conf()->server_ip);
                if ($ssh->login('root', base64_decode(Doo::conf()->hexpass))) {
                    $confstr = str_replace("yourdomain", $clientdomain, Doo::conf()->nginx_conf_template);
                    $ssh->exec("echo -e '$confstr' >> /etc/nginx/sites-enabled/$clientdomain");
                    $ssh->exec("systemctl reload nginx");
                }
            }
        }

        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = "Account settings saved successfully.";
        return Doo::conf()->APP_URL . 'userSettings';
    }

    public function setAppLanguage()
    {
        $this->isLogin();
        $lang = $this->params['lang'];
        if ($lang == "" || !$lang) {
            $lang = "en";
        }
        $_SESSION['APP_LANG'] = $lang;
        //save setting in db
        $usobj = Doo::loadModel('ScUsersSettings', true);
        $usobj->user_id = $_SESSION['user']['userid'];
        $usdata = Doo::db()->find($usobj, array('limit' => 1));

        if ($usdata->id) {
            //update
            $usobj->id = $usdata->id;
            $usobj->def_lang = $lang;

            Doo::db()->update($usobj, array('limit' => 1));
        } else {
            //insert
            $usobj->user_id = $_SESSION['user']['userid'];
            $usobj->def_lang = $lang;

            Doo::db()->insert($usobj);
        }
        exit;
    }


    public function viewNotifications()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['active_page'] = 'Notifications';

        $data['page'] = 'Dashboard';
        $data['current_page'] = 'notifs';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewAlerts', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getMyAlerts()
    {
        $this->isLogin();
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
            $str .= '<div class="albox media list-group m-h-0" data-nid="' . $nt->id . '" data-redirect="' . base64_encode($link) . '">
                                <div class="media-left p-t-sm p-l-sm text-dark m-b-0">
                                    <i class="fa fa-3x text-' . $nt->type . ' ' . $icon . '"></i>
                                </div>
                                <div class="media-body">
                                    <p class="fz-sm p-sm text-dark m-b-0"> ' . $nt->notif_text . '</p>
                                </div>

                            </div>
                            ';
        }


        $finalstr = $str == '' ? '<div class="list-group m-b-sm p-t-sm"><p class="text-dark text-center">- ' . $this->SCTEXT('No New Notifications') . ' -</p></div>' : $str;

        $_SESSION['alerts']['count'] = sizeof($ndata);
        $_SESSION['alerts']['content'] = $finalstr;
        session_write_close();

        $final['str'] = $finalstr;
        $final['count'] = sizeof($ndata);
        echo json_encode($final);
        exit;
    }

    public function alertRedirect()
    {
        $this->isLogin();
        //collect
        $nid = $this->params['nid'];
        $link = $this->params['elink'];

        //mark as read
        $nobj = Doo::loadModel('ScUserNotifications', true);
        $nobj->id = $nid;
        $nobj->status = 1;
        Doo::db()->update($nobj);

        //reduce session notif count
        $_SESSION['alerts']['count'] -= 1;

        //redirect
        return base64_decode($link);
    }

    public function getAllMyAlerts()
    {
        $this->isLogin();

        $uid = $_SESSION['user']['userid'];

        $columns = array(
            array('db' => 'notif_text', 'dt' => 0),
            array('db' => 'notif_time', 'dt' => 2)
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
            $sWhere = "user_id = $uid AND `notif_time` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
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

        Doo::loadModel('ScUserNotifications');
        $obj = new ScUserNotifications;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $ndata = Doo::db()->find($obj, $dtdata);
        $totalFiltered = sizeof($ndata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($ndata as $dt) {
            switch ($dt->type) {
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
            $read = $dt->status == '0' ? 'unread' : '';
            if (strpos($dt->notif_text, '||')) {
                $ntftxtar = explode("||", $dt->notif_text);
                $notif_text = $this->SCTEXT(trim($ntftxtar[0])) . ' ' . $ntftxtar[1];
            } else {
                $notif_text = $this->SCTEXT($dt->notif_text);
            }
            $notstr = '<div class="' . $read . ' panel panel-' . $dt->type . ' panel-custom m-b-xs pointer">
                                <div class="media-left p-t-xs p-l-sm text-dark m-b-0">
                                    <i class="fa fa-3x text-' . $dt->type . ' ' . $icon . '"></i>
                                </div>
                                <div class="media-body">
                                    <p class="p-sm text-dark m-b-0"> ' . $notif_text . '</p>
                                </div>
                    </div>            ';

            $nt = new DateTime($dt->notif_time);
            $ct = new DateTime(date(Doo::conf()->date_format_db));
            $interval = $nt->diff($ct);
            $passed = DooTextHelper::format_interval($interval, 'short');

            $timestr = $passed . ' ago<br><span class="fz-sm"> <i class="fa fa-lg m-r-xs fa-clock-o"></i>' . date(Doo::conf()->date_format_med_time_s, strtotime($dt->notif_time)) . '</span>';
            $link = $dt->link_to == '' ? Doo::conf()->APP_URL . 'viewNotifications' : Doo::conf()->APP_URL . $dt->link_to;
            $linkstr = '<a href="' . $link . '" target="_blank">Go <i class="fa fa-external-link-alt m-l-xs fa-lg"></a>';

            $output = array($notstr . '<input type="hidden" class="nids" value="' . $dt->id . '"/>', $linkstr, $timestr);

            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function markAlertsRead()
    {
        $this->isLogin();

        $nids = $_POST['nids'];

        Doo::loadModel('ScUserNotifications');
        $obj = new ScUserNotifications;
        $obj->status = 1;

        Doo::db()->update($obj, array('where' => "id IN ($nids)"));
        //return
        $_SESSION['notif_msg']['msg'] = 'Notifications marked as READ.';
        $_SESSION['notif_msg']['type'] = 'success';

        //set notification for user

        echo 'DONE';
        exit;
    }

    public function purchaseCredits()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['active_page'] = 'Purchase Credits';

        //get SMS price data and all routes assigned
        $data['routes'] = $_SESSION['credits']['routes'];

        //get tax information for this client's billing
        if ($_SESSION['manager']['category'] == 'admin') {
            //sms plan could be associated with this user
            $upobj = Doo::loadModel('ScUsersSmsPlans', true);
            $upobj->user_id = $_SESSION['user']['userid'];
            $updata = Doo::db()->find($upobj, array('limit' => 1, 'select' => 'plan_id'));

            if (intval($updata->plan_id) != 0) {
                //plan is associated, get tax info from sms plan
                //also since plan is associated, a volumne based plan will have different sms rates based on sms volume

                $pobj = Doo::loadModel('ScSmsPlans', true);
                $pobj->id = intval($updata->plan_id);
                $tdata = Doo::db()->find($pobj, array('limit' => 1, 'select' => 'plan_type, tax, tax_type'));
                $data['plan_type'] = $tdata->plan_type;
                $data['tax'] = $tdata->tax;
                $data['taxtype'] = $tdata->tax_type;
                $data['planid'] = intval($updata->plan_id);
            } else {
                //no plan is associated, get tax from sign up settings
                Doo::loadModel('ScWebsitesSignupSettings');
                $stobj = new ScWebsitesSignupSettings;
                $stobj->user_id = $_SESSION['user']['upline'];
                $stdata = Doo::db()->find($stobj, array('limit' => 1, 'select' => 'signup_data'));
                $sdata = unserialize($stdata->signup_data);
                $data['tax'] = $sdata['tax'];
                $data['taxtype'] = $sdata['tax_type'];
            }
        } else {
            //a reseller's customer, no plans associated
            Doo::loadModel('ScWebsitesSignupSettings');
            $stobj = new ScWebsitesSignupSettings;
            $stobj->user_id = $_SESSION['user']['upline'];
            $stdata = Doo::db()->find($stobj, array('limit' => 1, 'select' => 'signup_data'));
            $sdata = unserialize($stdata->signup_data);
            $data['tax'] = $sdata['tax'];
            $data['taxtype'] = $sdata['tax_type'];
        }

        if (Doo::conf()->force_offline_payment == 1) {
            $usetobj = Doo::loadModel('ScUsersCompany', true);
            $usetobj->user_id = $_SESSION['user']['upline'];
            $usetdata = Doo::db()->find($usetobj, array('select' => 'c_payment', 'limit' => 1));
            $userpg = unserialize($usetdata->c_payment);

            //show options based on allowed payment types
            $data['payment_details'] = $userpg;
        }

        if ($_SESSION['user']['account_type'] != 0) {
            $uobj = Doo::loadModel('ScUsers', true);
            $data['user'] = $uobj->getProfileInfo($_SESSION['user']['userid'], 'default_tax');
            $txdata = unserialize($data['user']->default_tax);
            $data['tax'] = $txdata['tax'];
            $data['taxtype'] = $txdata['type'];
        }


        $data['page'] = 'Dashboard';
        $data['current_page'] = 'buy_sms';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        if ($_SESSION['user']['account_type'] != 0) {
            $this->view()->renderc('client/buySmsCurr', $data);
        } else {
            $this->view()->renderc('client/buySms', $data);
        }

        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function buyOrderCheckout()
    {
        $this->isLogin();
        //collect values
        if ($_POST['currencyaccount'] == 1) {
            //currency based account adding credits in wallet
            $wcredits = floatval($_POST['walletcredits']);
            if ($wcredits <= 0) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = "Invalid amount entered. Please retry.";
                return Doo::conf()->APP_URL . 'purchaseCredits';
            }
            //calculate total payable and create invoice
            $uobj = Doo::loadModel('ScUsers', true);
            $udata = $uobj->getProfileInfo($_SESSION['user']['userid'], 'default_tax');
            $taxdata = unserialize($udata->default_tax);
            if (floatval($taxdata['tax']) > 0) {
                $total = $wcredits + ($wcredits * (floatval($taxdata['tax']) / 100));
            } else {
                $total = $wcredits;
            }

            $invdata['plan_tax'] = 0;

            $invdata['wallet_credits'] = $wcredits;
            $invdata['total_cost'] = round($wcredits, 2);
            $invdata['additional_tax'] = floatval($taxdata['tax']) . '%';
            $invdata['discount'] = 'N/A';
            $invdata['grand_total'] = round($total, 2);
            $invdata['inv_status'] = 0; //pending payment
            $invdata['inv_rem'] = '';

            //add invoice
            Doo::loadModel('ScUsersDocuments');
            $dobj = new ScUsersDocuments;
            $dobj->filename = 'INVOICE_' . $_SESSION['user']['loginid'] . '_' . time();
            $dobj->type = 1;
            $dobj->owner_id = $_SESSION['user']['upline'];
            $dobj->shared_with = $_SESSION['user']['userid'];
            $dobj->created_on = date(Doo::conf()->date_format_db);
            $dobj->file_data = serialize($invdata);
            $dobj->file_status = 0; //invoice is due
            $dobj->init_remarks = $invdata['inv_rem'];

            $inv_id = Doo::db()->insert($dobj); //Based on user action on next page this invoice will be either paid or deleted


        } else {
            //credit based account buying sms credits
            $routeid = intval($_POST['route']);
            $credits = intval(str_replace(" ", '', $_POST['smscredits']));

            if ($routeid <= 0 || $credits <= 0) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = "Invalid credit or route selected. Please retry.";
                return Doo::conf()->APP_URL . 'purchaseCredits';
            }

            //calculate total payable

            //get tax information for this client's billing
            if ($_SESSION['manager']['category'] == 'admin') {
                //sms plan could be associated with this user
                $upobj = Doo::loadModel('ScUsersSmsPlans', true);
                $upobj->user_id = $_SESSION['user']['userid'];
                $updata = Doo::db()->find($upobj, array('limit' => 1, 'select' => 'plan_id'));

                if (intval($updata->plan_id) != 0) {
                    //plan is associated, get tax info from sms plan
                    //also since plan is associated, a volumne based plan will have different sms rates based on sms volume

                    $pobj = Doo::loadModel('ScSmsPlans', true);
                    $pobj->id = intval($updata->plan_id);
                    $tdata = Doo::db()->find($pobj, array('limit' => 1, 'select' => 'plan_type, tax, tax_type'));
                    $plan_type = $tdata->plan_type;
                    $tax = $tdata->tax;
                    $taxtype = $tdata->tax_type;
                    $planid = intval($updata->plan_id);

                    if ($plan_type == '0') {
                        //volume based
                        $rcdata = array();
                        $myobj = new stdClass;
                        $myobj->credits = $credits;
                        $myobj->id = $routeid;
                        array_push($rcdata, $myobj);

                        $dis = 0;
                        $dtype = '';
                        $adtx = 0;

                        Doo::loadModel("ScSmsPlanOptions");
                        $spoobj = new ScSmsPlanOptions;
                        $pricedata = $spoobj->getSmsPrice($planid, $rcdata);

                        $total_price = $pricedata['total'];
                    } else {
                        //subscription based, get flat rates
                        $pricedata[$routeid]['credits'] = $credits;
                        $pricedata[$routeid]['price'] = $_SESSION['credits']['routes'][$routeid]['price'];
                        $total_price = $credits * $_SESSION['credits']['routes'][$routeid]['price'];
                        $pricedata[$routeid]['total'] = $total_price;
                        $pricedata['total'] = $total_price;
                    }
                } else {
                    //no plan is associated, get tax from sign up settings
                    Doo::loadModel('ScWebsitesSignupSettings');
                    $stobj = new ScWebsitesSignupSettings;
                    $stobj->user_id = $_SESSION['user']['upline'];
                    $stdata = Doo::db()->find($stobj, array('limit' => 1, 'select' => 'signup_data'));
                    $sdata = unserialize($stdata->signup_data);
                    $tax = $sdata['tax'];
                    $taxtype = $sdata['tax_type'];

                    $pricedata[$routeid]['credits'] = $credits;
                    $pricedata[$routeid]['price'] = $_SESSION['credits']['routes'][$routeid]['price'];
                    $total_price = $credits * $_SESSION['credits']['routes'][$routeid]['price'];
                    $pricedata[$routeid]['total'] = $total_price;
                    $pricedata['total'] = $total_price;
                }
            } else {
                //a reseller's customer, no plans associated
                Doo::loadModel('ScWebsitesSignupSettings');
                $stobj = new ScWebsitesSignupSettings;
                $stobj->user_id = $_SESSION['user']['upline'];
                $stdata = Doo::db()->find($stobj, array('limit' => 1, 'select' => 'signup_data'));
                $sdata = unserialize($stdata->signup_data);
                $tax = $sdata['tax'];
                $taxtype = $sdata['tax_type'];

                $pricedata[$routeid]['credits'] = $credits;
                $pricedata[$routeid]['price'] = $_SESSION['credits']['routes'][$routeid]['price'];
                $total_price = $credits * $_SESSION['credits']['routes'][$routeid]['price'];
                $pricedata[$routeid]['total'] = $total_price;
                $pricedata['total'] = $total_price;
            }

            //calculate taxes
            $total_af_plntax = $total_price + ($total_price * $tax / 100);

            //tax type
            switch ($taxtype) {
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

            //deduct wallet if applicable
            $wflag = 0;
            $walletbal = $_SESSION['credits']['wallet']['amount'];
            if ($walletbal > 0) {
                if (isset($_POST['useWallet'])) {
                    //decided against below logic because issuing wallet credit as invoice discount is not practical. Here in performa invoice it works but for invoices already issued and if they have discount in percentage, adding a flat discount would be complicated

                    //wallet is used, give discount
                    //$dis = $total_price<=floatval($walletbal)?$total_price:floatval($walletbal);
                    //discount entire amount and payable would be zero if wallet balance is enough
                    //$dtype = 'cur';

                    //$total_af_dis = $total_af_plntax - $dis;

                    $total_af_dis = $total_af_plntax;
                    $wflag = 1;
                } else {
                    //wallet not checked
                    $total_af_dis = $total_af_plntax;
                }
            } else {
                //wallet balance is zero
                $total_af_dis = $total_af_plntax;
            }

            $grand_total = $total_af_dis;

            $rdata = $pricedata;
            unset($rdata['total']); //this duplication and unsetting of a field is done so this rdata array will have exact number of elements as routes for which the transaction happens i.e.1 in current case

            //create performa invoice
            $invdata['plan_tax'] = $tax == 0 ? 0 : $tax . '% ' . $type;
            $invdata['routes_credits'] = $rdata;
            $invdata['total_cost'] = $total_price;
            $invdata['additional_tax'] = $adtx . '%';
            $invdata['discount'] = 0;
            $invdata['grand_total'] = $grand_total;

            $invdata['inv_status'] = 0; //pending payment
            $invdata['inv_rem'] = '';


            Doo::loadModel('ScUsersDocuments');
            $dobj = new ScUsersDocuments;
            $dobj->filename = 'INVOICE_' . $_SESSION['user']['loginid'] . '_' . time();
            $dobj->type = 1;
            $dobj->owner_id = $_SESSION['user']['upline'];
            $dobj->shared_with = $_SESSION['user']['userid'];
            $dobj->created_on = date(Doo::conf()->date_format_db);
            $dobj->file_data = serialize($invdata);
            $dobj->file_status = 0; //invoice is due
            $dobj->init_remarks = $invdata['inv_rem'];

            $inv_id = Doo::db()->insert($dobj); //Based on user action on next page this invoice will be either paid or deleted
        }


        //redirect to payment if applicable
        $usetobj = Doo::loadModel('ScUsersCompany', true);
        $usetobj->user_id = $_SESSION['user']['upline'];
        $usetdata = Doo::db()->find($usetobj, array('select' => 'c_payment', 'limit' => 1));
        $userpg = unserialize($usetdata->c_payment);
        if ($userpg['channel'] == '') {
            //payments not enabled, redirect to invoice page and tell user to pay offline
            $_SESSION['notif_msg']['type'] = 'success';
            $_SESSION['notif_msg']['msg'] = 'Order confirmed. Please pay offline to your Account Manager as online payments are not active for this account.';
            return Doo::conf()->APP_URL . 'viewDocument/' . $inv_id;
        } else {
            //send to confirmation page with data
            $paymentdata['invoiceid'] = $inv_id;
            $paymentdata['walletflag'] = $wflag;
            Doo::loadHelper('DooEncrypt');
            $hfunck = base64_encode(session_id() . '_' . base64_encode('smppcubehash'));
            $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
            $link = $encobj->encrypt(serialize($paymentdata), $hfunck);
            return Doo::conf()->APP_URL . 'confirmPurchaseOrder/' . base64_encode($link);
        }
    }

    public function confirmPurchaseOrder()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['active_page'] = 'Purchase Credits';

        //this function generates a link for payment
        Doo::loadHelper('DooEncrypt');
        $hfunck = base64_encode(session_id() . '_' . base64_encode('smppcubehash'));
        $encobj = new DooEncrypt(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $pgdata = unserialize($encobj->decrypt(base64_decode($this->params['data']), $hfunck));

        //get invoice details
        $docobj = Doo::loadModel('ScUsersDocuments', true);
        $docobj->id = $pgdata['invoiceid'];
        $data['docdata'] = Doo::db()->find($docobj, array('limit' => 1));

        if ($data['docdata']->file_status == 1) {
            //invoice is paid, do not load Payment class
            $data['paid'] = 1;
            $data['page'] = 'Dashboard';
            $data['current_page'] = 'confirm_po';
            $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
            $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
            $this->view()->renderc('client/confirmPO', $data);
            $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
            exit;
        }
        $data['paid'] = 0;
        $data['invdata'] = unserialize($data['docdata']->file_data);

        //get user details
        $uobj = Doo::loadModel('ScUsers', true);
        $data['udata'] = $uobj->getProfileInfo($_SESSION['user']['userid']);

        $total_price = $data['invdata']['grand_total'];
        $wlobj = Doo::loadModel('ScUsersWallet', true);
        $userWallet = $wlobj->getWalletByUser($_SESSION['user']['userid']);
        $walletbal = $userWallet->amount;
        if ($pgdata['walletflag'] == '1') {
            //apply wallet discount in total payable
            $dis = $total_price <= floatval($walletbal) ? $total_price : floatval($walletbal);
            $data['invdata']['grand_total'] = $total_price - $dis;
        }
        $data['walletflag'] = $pgdata['walletflag'];
        $data['returntoinvoice'] = 1;
        //get Payment info
        $usetobj = Doo::loadModel('ScUsersCompany', true);
        $usetobj->user_id = $_SESSION['user']['upline'];
        $usetdata = Doo::db()->find($usetobj, array('select' => 'c_payment', 'limit' => 1));
        $userpg = unserialize($usetdata->c_payment);

        //show options based on allowed payment types
        $data['payment_details'] = $userpg;

        //load selected payment gateway
        Doo::loadHelper('DooPaymentHelper');
        $payobj = new DooPaymentHelper($userpg);
        $payobj->page = 'inner';
        $payobj->setInvoiceData(
            $pgdata['invoiceid'],
            $data['invdata']['grand_total'],
            $data['udata']
        );
        $data['userpg'] = $payobj->getPaymentGatewayParams();
        //echo '<pre>'; var_dump($data['userpg']);die;

        if ($userpg['channel'] == 'stripe') {
            //perform tasks here instead of stripe way of doing using fetch api to be more consistent with the code flow of the app
            \Stripe\Stripe::setApiKey($data['userpg']['secret_key']);
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => number_format($data['invdata']['grand_total'], 2, "", ""),
                'currency' => strtolower(Doo::conf()->currency_name),
                'payment_method_types' => ['card'],
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);
            $clientSecret =  $paymentIntent->client_secret;
            $data['userpg']['client_secret'] = $clientSecret;
            //BySam- Create a hash to verify that the request to process order came from the same source
            //format clientsecret|amount|invoiceid|userid|ip
            $hashSequence = $clientSecret . '|' . floatval($data['invdata']['grand_total']) . '|' . $pgdata['invoiceid'] . '|' . $_SESSION['user']['userid'] . '|' . $_SERVER['REMOTE_ADDR'];
            $hash = hash("sha512", $hashSequence);
            $data['userpg']['hash'] = $hash;
            unset($_SESSION['stripe_params']);
            $_SESSION['stripe_params'] = $clientSecret;
        }
        if ($userpg['channel'] == 'paystack') {

            $clientSecret =  $data['userpg']['secret_key'];
            $data['userpg']['client_secret'] = $clientSecret;
            //BySam- Create a hash to verify that the request to process order came from the same source
            //format clientsecret|amount|invoiceid|userid|ip
            $hashSequence = $clientSecret . '|' . floatval($data['invdata']['grand_total']) . '|' . $pgdata['invoiceid'] . '|' . $_SESSION['user']['userid'] . '|' . $_SERVER['REMOTE_ADDR'];
            $hash = hash("sha512", $hashSequence);
            $data['userpg']['hash'] = $hash;
            unset($_SESSION['paystack_params']);
            $_SESSION['paystack_params'] = $clientSecret;
        }
        //render
        $data['page'] = 'Dashboard';
        $data['current_page'] = 'confirm_po';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/confirmPO', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
        $this->view()->renderc('client/paypalBtn', $data);
    }

    public function scOrderProcess()
    {
        $this->isLogin();
        $channel = $_REQUEST['channel'];
        $wflag = isset($_GET['wflag']) ? $_GET['wflag'] : 0;
        $wlobj = Doo::loadModel('ScUsersWallet', true);

        Doo::loadHelper('DooPaymentHelper');
        $payobj = new DooPaymentHelper();
        $payobj->page = 'inner';

        if ($wflag == '1') {
            $userWallet = $wlobj->getWalletByUser($_SESSION['user']['userid']);
            $payobj->useWallet = true;
            $payobj->walletId = $userWallet->id;
            $payobj->walletBalance = $userWallet->amount;
        }

        //check if the channel is stripe or paypal
        if ($channel == 'stripe') {
            //collect passed objects
            $invoiceData = json_decode($_REQUEST['invData'], true);
            $clientSecret = $_SESSION['stripe_params'];
            unset($_SESSION['stripe_params']);
            $hashSequence = $clientSecret . '|' . $invoiceData['invTotal'] . '|' . $invoiceData['invoiceid'] . '|' . $_SESSION['user']['userid'] . '|' . $_SERVER['REMOTE_ADDR'];
            $hash = hash("sha512", $hashSequence);
            $response = array();
            if ($hash == $_REQUEST['hash']) {
                //authentic request
                $sessionVars = $invoiceData;
                $paymentResponse = json_decode($payobj->parseStripeResponse($_REQUEST['paymentData'], $sessionVars), true);
                //update wallet credits in session
                // $wlobj_a = Doo::loadModel('ScUsersWallet', true);
                // $userWallet = $wlobj_a->getWalletByUser($_SESSION['user']['userid']);
                // $_SESSION['credits']['wallet']['amount'] = $userWallet->amount;
                if ($paymentResponse['mode'] == 'xhr') {
                    echo json_encode($paymentResponse);
                    exit;
                } else {
                    $_SESSION['notif_msg']['type'] = $paymentResponse['type'];
                    $_SESSION['notif_msg']['msg'] = $paymentResponse['msg'];
                    return $paymentResponse['return'];
                }
            } else {
                //tampered request do not update anything
                $response['status'] = 'invalid';
                echo json_encode($response);
                exit;
            }
        }
        if ($channel == 'paystack') {
            //we need to verify the payment here by using reference
            $usetobj = Doo::loadModel('ScUsersCompany', true);
            $usetobj->user_id = $_SESSION['user']['upline'];
            $usetdata = Doo::db()->find($usetobj, array('select' => 'c_payment', 'limit' => 1));
            $userpg = unserialize($usetdata->c_payment);

            //load selected payment gateway
            Doo::loadHelper('DooPaymentHelper');
            $payobj = new DooPaymentHelper($userpg);
            $data['userpg'] = $payobj->getPaymentGatewayParams();
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $_REQUEST["paymentRef"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $data['userpg']['secret_key'],
                    "Cache-Control: no-cache",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Payment Verification failed. Please contact Admin. Payment Ref: ' . $_REQUEST["paymentRef"];
                return Doo::conf()->APP_URL . 'viewDocument/' . $_REQUEST['invid'];
            } else {
                $sessionVars = array('invoice_id' => $_REQUEST['invid'], 'wallet_flag' => $_REQUEST['wflag']);
                $paymentResponse = json_decode($payobj->parsePaystackResponse(json_decode($response), $sessionVars), true);
                //update wallet credits in session
                $wlobj_a = Doo::loadModel('ScUsersWallet', true);
                $userWallet = $wlobj_a->getWalletByUser($_SESSION['user']['userid']);
                $_SESSION['credits']['wallet']['amount'] = $userWallet->amount;
                //return
                $_SESSION['notif_msg']['type'] = $paymentResponse['type'];
                $_SESSION['notif_msg']['msg'] = $paymentResponse['msg'];
                return $paymentResponse['return'];
            }
        }
    }



    //13. 2-Way Messaging

    public function inbox()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['inbox']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Inbox';

        //get all vmn
        $vobj = Doo::loadModel('ScVmnList', true);
        $vmns = Doo::db()->find($vobj, array('where' => 'type IN (0,1)'));
        $data['vmns'] = $vmns;

        $data['page'] = '2WAY';
        $data['current_page'] = 'inbox';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/inbox', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllIncomingSms()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['inbox']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'mobile', 'dt' => 0),
            array('db' => 'sms_text', 'dt' => 2)
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
                $sWhere = "receiving_time LIKE '$from%'";
            } else {
                $sWhere = "receiving_time BETWEEN '$from' AND '$to'";
            }
        }
        //user
        if ($_SESSION['user']['group'] != 'admin') {
            $sWhere =  $sWhere == '' ? 'user_id=' . $_SESSION['user']['userid'] : $sWhere . ' AND user_id=' . $_SESSION['user']['userid'];
        }
        //campaign
        $vmn = $this->params['vmn'];
        if ($vmn != 0) {
            $sWhere =  $sWhere == '' ? 'vmn=' . $vmn : $sWhere . ' AND vmn=' . $vmn;
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


        Doo::loadModel('ScVmnInbox');
        $obj = new ScVmnInbox;

        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $cmpns = Doo::db()->find($obj, $dtdata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($cmpns as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'viewIncomingSms/' . $dt->id . '">' . $this->SCTEXT('View SMS Details') . '</a></li><li><a class="" href="' . Doo::conf()->APP_URL . 'markAsOptOut/' . $dt->id . '">' . $this->SCTEXT('Mark as Opt-out') . '</a></li><li><a class="del-mo" data-moid="' . $dt->id . '" href="javascript:void(0);">' . $this->SCTEXT('Delete SMS') . '</a></li></ul></div>';


            $output = array($dt->mobile, '<spam class="label label-primary label-md">' . $dt->vmn . '</span>', htmlspecialchars_decode($dt->sms_text, ENT_QUOTES), date(Doo::conf()->date_format_long_time, strtotime($dt->receiving_time)), $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
        exit;
    }


    public function markAsOptOut()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['inbox']) {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScUsersCampaignsOptouts', true);
        $optout = new ScUsersCampaignsOptouts;
        $optout->mobile = Doo::db()->find('ScVmnInbox', array('select' => 'mobile', 'where' => 'id=' . $this->params['id'], 'limit' => 1))->mobile;
        $optout->campaign_id = Doo::db()->find('ScUsersCampaigns', array('select' => 'id', 'where' => 'user_id=' . $_SESSION['user']['userid'] . ' AND is_default=1', 'limit' => 1))->id;
        $optout->user_id = $_SESSION['user']['userid'];
        $optout->date_added = date('Y-m-d H:i:s');
        $optout->keyword_matched = '';
        Doo::db()->insert($optout);
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Mobile added in the optout list';
        return Doo::conf()->APP_URL . 'inbox';
    }


    public function viewIncomingSms()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['inbox']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Inbox'] = Doo::conf()->APP_URL . 'inbox';
        $data['active_page'] = 'View SMS Details';

        //inbox
        $iobj = Doo::loadModel('ScVmnInbox', true);
        $iobj->id = intval($this->params['id']);
        if ($_SESSION['user']['group'] != 'admin') $iobj->user_id = $_SESSION['user']['userid'];
        $data['smsdata'] = Doo::db()->find($iobj, array('limit' => 1));

        if (!$data['smsdata']->vmn) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'SMS Not Found';
            return Doo::conf()->APP_URL . 'inbox';
        }
        //auto reply details
        $robj = Doo::loadModel('ScVmnAutoReplies', true);
        $robj->reply_against_sms_id = $data['smsdata']->id;
        $data['ardata'] = Doo::db()->find($robj, array('limit' => 1));

        //match user details
        if ($data['smsdata']->user_id != 0) {
            $uobj = Doo::loadModel('ScUsers', true);
            $upinfo = $uobj->getProfileInfo($data['smsdata']->user_id, 'name,avatar,email');
            $data['ustr'] = '<div class="media-group-item" style="padding-top:0;padding-left:0;">
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
        } else {
            $data['ustr'] = '- ' . $this->SCTEXT('NO USER MATCHED') . ' -';
        }

        $data['page'] = '2WAY';
        $data['current_page'] = 'view_mo';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/incomingSMS', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deleteIncomingSms()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['inbox']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete sms
        $obj = Doo::loadModel('ScVmnInbox', true);
        $obj->id = $this->params['id'];
        if ($_SESSION['user']['group'] != 'admin') $obj->user_id = $_SESSION['user']['userid'];
        Doo::db()->delete($obj);
        //delete auto replies
        $robj = Doo::loadModel('ScVmnAutoReplies', true);
        $robj->reply_against_sms_id = $this->params['id'];
        Doo::db()->delete($robj);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'SMS deleted successfully';
        return Doo::conf()->APP_URL . 'inbox';
    }

    public function viewAllVmn()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Virtual Mobile Numbers';

        $data['page'] = '2WAY';
        $data['current_page'] = 'manage_vmn';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageVmn', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllVmn()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScVmnList');
        $obj = new ScVmnList;
        if ($_SESSION['user']['group'] != 'admin') {
            $obj->user_assigned = $_SESSION['user']['userid'];
        }
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'limit' => 1))->total;
        $ndata = Doo::db()->find($obj);

        $robj = Doo::loadModel('ScSmsRoutes', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($ndata as $dt) {
            switch ($dt->type) {
                case '0':
                    $type = '<span class="label label-md label-success">Shortcode</span>';
                    break;
                case '1':
                    $type = '<span class="label label-md label-primary">Longcode</span>';
                    break;
                case '2':
                    $type = '<span class="label label-md label-danger">Missedcall Number</span>';
                    break;
            }

            $smpp = $robj->getRouteData($dt->sysreply_smpp, 'title')->title;

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editVmn/' . $dt->id . '">' . $this->SCTEXT('Edit VMN') . '</a></li><li><a href="javascript:void(0);" class="del-vmn" data-vmnid="' . $dt->id . '">' . $this->SCTEXT('Delete VMN') . '</a></li></ul></div>';
            if ($_SESSION['user']['group'] == 'admin' || $_SESSION['user']['account_type'] == '0') {
                $output = array($dt->vmn, $type, $smpp, $button_str);
            } else {
                $output = array($dt->vmn, $type, $button_str);
            }

            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function addNewVmn()
    {
        $this->isLogin();
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['group'] != 'admin') {
            //denied
            return array('/denied', 'internal');
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Virtual Mobile Numbers'] = Doo::conf()->APP_URL . 'manageVmn';
        $data['active_page'] = 'Add New VMN';

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));

        $data['page'] = '2WAY';
        $data['current_page'] = 'add_vmn';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/addVmn', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function importNewVmn()
    {
        $this->isLogin();
        if ($_SESSION['user']['subgroup'] == 'staff' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if ($_SESSION['user']['group'] != 'admin') {
            //denied
            return array('/denied', 'internal');
        }

        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Virtual Mobile Numbers'] = Doo::conf()->APP_URL . 'manageVmn';
        $data['active_page'] = 'Import VMN';

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));

        $userobj = Doo::loadModel('ScUsers', 'true');
        $data['users'] = Doo::db()->find($userobj, array('select' => 'user_id, name, category, avatar, email, mobile', 'where' => "category <> 'admin'"));


        $data['page'] = '2WAY';
        $data['current_page'] = 'import_vmn';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('admin/importVmn', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editVmn()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Virtual Mobile Numbers'] = Doo::conf()->APP_URL . 'manageVmn';
        $data['active_page'] = 'Edit VMN';

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));

        //get vmn details
        $vobj = Doo::loadModel('ScVmnList', true);
        $vobj->id = intval($this->params['id']);
        if ($_SESSION['user']['group'] != 'admin') $vobj->user_assigned = $_SESSION['user']['userid'];
        $rs = Doo::db()->find($vobj, array('limit' => 1));

        if (!$rs->vmn) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
            return Doo::conf()->APP_URL . 'manageVmn';
        }
        $data['vmn'] = $rs;

        $data['page'] = '2WAY';
        $data['current_page'] = 'edit_vmn';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editVmn', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveVmn()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $type = intval($_POST['vmntype']);
        $vmn = intval($_POST['vmn']);
        $def_reply = htmlspecialchars(str_replace("\r\n", "\n", $_POST["dr_sms"]), ENT_QUOTES);
        $def_turl = $_POST['turl'];
        $ar_type = intval($_POST['ar_type']);
        $rtdata = explode("|", $_POST['rsmpp']);
        $reply_route = intval($rtdata[0]);
        $reply_sender = $_POST['replysender'];

        //check if edit or add
        $vobj = Doo::loadModel('ScVmnList', true);
        $vobj->vmn = $vmn;
        $rs = Doo::db()->find($vobj, array('limit' => 1));

        if ($rs->id) {
            //update
            if ($_SESSION['user']['group'] != 'admin' && $rs->user_assigned != $_SESSION['user']['userid']) {
                //only admin or assigned user can edit vmn
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                return Doo::conf()->APP_URL . 'manageVmn';
            }
            //validate url if set
            if ($def_turl != '') {
                $uobj = Doo::loadHelper('DooValidator', true);
                $msg = $uobj->testUrl($def_turl, 'no');
                if ($msg == 'no') {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Invalid URL format. Please include http/https in your URL and extension e.g. http://fb.com';
                    return Doo::conf()->APP_URL . 'editVmn/' . $rs->id;
                }
            }
            $vobj->id = $rs->id;
            if ($_SESSION['user']['group'] == 'admin') $vobj->type = $type; //only admin can modify the type
            if ($_SESSION['user']['group'] == 'admin') $vobj->auto_reply_type = $ar_type; //only admin can modify reply rule
            $vobj->trigger_url = $def_turl;
            $vobj->default_reply = $def_reply;
            if ($_SESSION['user']['group'] == "admin") $vobj->sysreply_smpp = $reply_route;
            $vobj->sysreply_sender = $reply_sender;
            Doo::db()->update($vobj);
            $msg = 'Virtual mobile number updated successfully.';
        } else {
            //insert
            if ($_SESSION['user']['group'] != 'admin') {
                //only admin can add new vmn
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                return Doo::conf()->APP_URL . 'manageVmn';
            }
            //validate url if set
            if ($def_turl != '') {
                $uobj = Doo::loadHelper('DooValidator', true);
                $msg = $uobj->testUrl($def_turl, 'no');
                if ($msg == 'no') {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Invalid URL format. Please include http/https in your URL and extension e.g. http://fb.com';
                    return Doo::conf()->APP_URL . 'addNewVmn';
                }
            }
            //add the vmn into database
            $vobj->type = $type;
            $vobj->default_reply = $def_reply;
            $vobj->trigger_url = $def_turl;
            $vobj->auto_reply_type = $ar_type;
            $vobj->sysreply_smpp = $reply_route;
            $vobj->sysreply_sender = $reply_sender;
            Doo::db()->insert($vobj);
            $msg = 'Virtual mobile number added successfully.';
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageVmn';
    }

    public function deleteVmn()
    {
        $this->isLogin();
        if ($_SESSION['user']['group'] != 'admin') {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
            return Doo::conf()->APP_URL . 'manageVmn';
        } else {
            //delete vmn
            $vobj = Doo::loadModel('ScVmnList', true);
            $vobj->id = intval($this->params['id']);
            Doo::db()->delete($vobj);
        }
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Virtual mobile number deleted successfully.';
        return Doo::conf()->APP_URL . 'manageVmn';
    }

    public function manageKeywords()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Primary Keywords';

        $data['page'] = '2WAY';
        $data['current_page'] = 'manage_kw';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageKeywords', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllKeywords()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'keyword', 'dt' => 0)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }
        Doo::loadModel('ScVmnPrimaryKeywords');
        $obj = new ScVmnPrimaryKeywords;
        if ($_SESSION['user']['group'] != 'admin') $obj->user_assigned = $_SESSION['user']['userid'];
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
        $kdata = Doo::db()->find($obj, $dtdata);

        $vobj = Doo::loadModel('ScVmnList', true);
        $uobj = Doo::loadModel('ScUsers', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($kdata as $dt) {
            $vmn = $vobj->getVmnData($dt->vmn, 'vmn,type');
            switch ($vmn->type) {
                case '0':
                    $vmnstr = '<span class="label label-md label-success">' . $vmn->vmn . '</span>';
                    break;
                case '1':
                    $vmnstr = '<span class="label label-md label-primary">' . $vmn->vmn . '</span>';
                    break;
            }
            if ($_SESSION['user']['group'] == 'admin') {
                if ($dt->user_assigned != 0) {
                    $udt = $uobj->getProfileInfo($dt->user_assigned, 'name,category,email,avatar');

                    $user_str = '<div class="media">
                                                <div class="media-left">
                                                    <div class="avatar avatar-sm avatar-circle"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_assigned . '"><img src="' . $udt->avatar . '" alt=""></a></div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="m-t-0"><a href="' . Doo::conf()->APP_URL . 'viewUserAccount/' . $dt->user_assigned . '" class="m-r-xs theme-color">' . ucwords($udt->name) . '</a><small class="text-muted fz-sm">' . ucwords($udt->category) . '</small></h5>
                                                    <p style="font-size: 12px;font-style: Italic;">' . $udt->email . '</p>
                                                </div>
                                            </div>';
                } else {
                    $user_str = '- ' . $this->SCTEXT('NO USER ASSOCIATED') . ' -';
                }
            }



            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editKeyword/' . $dt->id . '">' . $this->SCTEXT('Edit Keyword') . '</a></li><li><a href="javascript:void(0);" class="del-kw" data-kid="' . $dt->id . '">' . $this->SCTEXT('Delete Keyword') . '</a></li></ul></div>';

            $output = $_SESSION['user']['group'] == 'admin' ? array($dt->keyword, $vmnstr, $user_str, date(Doo::conf()->date_format_long, strtotime($dt->added_on)), $button_str) : array($dt->keyword, $vmnstr, date(Doo::conf()->date_format_long, strtotime($dt->added_on)), $button_str);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function addNewKeyword()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Primary Keywords'] = Doo::conf()->APP_URL . 'manageKeywords';
        $data['active_page'] = 'Add New Keyword';

        //get all vmns
        $vobj = Doo::loadModel('ScVmnList', true);
        if ($_SESSION['user']['group'] != 'admin') $vobj->user_assigned = $_SESSION['user']['userid'];
        $vmns = Doo::db()->find($vobj, array('where' => 'type IN (0,1)'));
        if (sizeof($vmns) == 0) {
            $msg = $_SESSION['user']['group'] == 'admin' ? 'No Shortcode/Longcode VMN found. Please add a Shortcode or Longcode to proceed.' : 'No dedicated Shortcode/Longcode VMN is assigned to your account. This is necessary to add your own Keywords.';
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = $msg;
            return Doo::conf()->APP_URL . 'manageKeywords';
        }

        $data['vmns'] = $vmns;

        $data['page'] = '2WAY';
        $data['current_page'] = 'add_kw';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addKeyword', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editKeyword()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Primary Keywords'] = Doo::conf()->APP_URL . 'manageKeywords';
        $data['active_page'] = 'Edit Keyword';

        //get all vmns
        $vobj = Doo::loadModel('ScVmnList', true);
        if ($_SESSION['user']['group'] != 'admin') $vobj->user_assigned = $_SESSION['user']['userid'];
        $vmns = Doo::db()->find($vobj, array('where' => 'type IN (0,1)'));
        $data['vmns'] = $vmns;

        //get keyword details
        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
        $kobj->id = intval($this->params['id']);
        $data['keyword'] = Doo::db()->find($kobj, array('limit' => 1));
        $kvobj = Doo::loadModel('ScVmnList', true);
        $data['kw_vmn'] = $kvobj->getVmnData($data['keyword']->vmn, 'vmn')->vmn;

        $data['page'] = '2WAY';
        $data['current_page'] = 'edit_kw';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editKeyword', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveKeyword()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['vmn']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect values
        $keyword = $_POST['keyword'];
        $vmn = intval($_POST['vmn']);
        $def_reply = htmlspecialchars(str_replace("\r\n", "\n", $_POST["dr_sms"]), ENT_QUOTES);
        $def_turl = $_POST['turl'];
        $fwdmob = $_POST['fwdmob'];

        //check if edit or add
        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
        $kobj->keyword = $keyword;
        $rs = Doo::db()->find($kobj, array('limit' => 1));
        if ($rs) {
            //update
            if ($_SESSION['user']['group'] != 'admin' && $rs->user_assigned != $_SESSION['user']['userid']) {
                //only admin or assigned user can edit keyword
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                return Doo::conf()->APP_URL . 'manageKeywords';
            }
            //validate url if set
            if ($def_turl != '') {
                $uobj = Doo::loadHelper('DooValidator', true);
                $msg = $uobj->testUrl($def_turl, 'no');
                if ($msg == 'no') {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Invalid URL format. Please include http/https in your URL and extension e.g. http://fb.com';
                    return Doo::conf()->APP_URL . 'editKeyword/' . $rs->id;
                }
            }
            $kobj->id = $rs->id;
            $kobj->vmn = $vmn;
            $kobj->default_reply = $def_reply;
            $kobj->trigger_url = $def_turl;
            $kobj->forward_sms_to = $fwdmob;
            Doo::db()->update($kobj);
            $msg = 'Primay keyword updated successfully.';
        } else {
            //insert
            $vobj = Doo::loadModel('ScVmnList', true);
            $vmn_user = $vobj->getVmnData($vmn, 'user_assigned');
            if ($_SESSION['user']['group'] != 'admin' && $_SESSION['user']['userid'] != $vmn_user->user_assigned) {
                //only admin or vmn assigned user can add new keywords
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                return Doo::conf()->APP_URL . 'manageKeywords';
            }
            //validate url if set
            if ($def_turl != '') {
                $uobj = Doo::loadHelper('DooValidator', true);
                $msg = $uobj->testUrl($def_turl, 'no');
                if ($msg == 'no') {
                    $_SESSION['notif_msg']['type'] = 'error';
                    $_SESSION['notif_msg']['msg'] = 'Invalid URL format. Please include http/https in your URL and extension e.g. http://fb.com';
                    return Doo::conf()->APP_URL . 'addNewKeyword';
                }
            }
            //add the keyword into database
            $kobj->id = $rs->id;
            $kobj->vmn = $vmn;
            $kobj->default_reply = $def_reply;
            $kobj->trigger_url = $def_turl;
            $kobj->added_by = $_SESSION['user']['userid'];
            if ($_SESSION['user']['group'] != 'admin') $kobj->user_assigned = $_SESSION['user']['userid'];
            $kobj->forward_sms_to = $fwdmob;
            Doo::db()->insert($kobj);
            $msg = 'New primary keyword added successfully.';
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageKeywords';
    }

    public function deleteKeyword()
    {
        $this->isLogin();
        if ($_SESSION['user']['group'] != 'admin') {
            //check if this keyword belongs to a VMN and the VMN is a dedicated one assigned to logged in user
            $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
            $kobj->id = intval($this->params['id']);
            $rs = Doo::db()->find($kobj, array('limit' => 1, 'select' => 'vmn'));

            $vobj = Doo::loadModel('ScVmnList', true);
            $vobj->id = $rs->vmn;
            $vrs = Doo::db()->find($vobj, array('limit' => 1, 'select' => 'user_assigned'));
            if ($vrs->user_assigned == $_SESSION['user']['userid']) {
                //delete keyword
                $kobj->id = intval($this->params['id']);
                Doo::db()->delete($kobj);
            } else {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                return Doo::conf()->APP_URL . 'manageKeywords';
            }
        } else {
            //delete keyword
            $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
            $kobj->id = intval($this->params['id']);
            Doo::db()->delete($kobj);
        }
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Primary keyword deleted successfully.';
        return Doo::conf()->APP_URL . 'manageKeywords';
    }

    public function viewCampaigns()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage Campaigns';

        $data['page'] = '2WAY';
        $data['current_page'] = 'manage_cmpns';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageCampaigns', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllCampaigns()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }

        Doo::loadModel('ScUsersCampaigns');
        $obj = new ScUsersCampaigns;
        $obj->user_id = $_SESSION['user']['userid'];
        $cdata = Doo::db()->find($obj);
        $total = sizeof($cdata);

        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
        $robj = Doo::loadModel('ScSmsRoutes', true);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($cdata as $dt) {

            if ($dt->primary_keyword_id != 0) {
                $kdt = $kobj->getKeywordData($dt->primary_keyword_id, 'keyword');

                $kstr = '<span class="label label-md label-primary">' . $kdt->keyword . '</span>';
            } else {
                $kstr = '- ' . $this->SCTEXT('NO KEYWORD ASSOCIATED') . ' -';
            }

            if ($dt->default_sms_route != 0) {
                $rdt = $robj->getRouteData($dt->default_sms_route, 'title');

                $rstr = '<span class="label label-md label-primary">' . $rdt->title . '</span>';
            } else {
                $rstr = '- ' . $this->SCTEXT('NO DEFAULT ROUTE') . ' -';
            }

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editCampaign/' . $dt->id . '">' . $this->SCTEXT('Edit Campaign') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewOptinList/' . $dt->id . '">' . $this->SCTEXT('View Opt-in Numbers') . '</a></li><li><a href="' . Doo::conf()->APP_URL . 'viewOptoutList/' . $dt->id . '">' . $this->SCTEXT('View Opt-out Numbers') . '</a></li><li><a href="javascript:void(0);" class="del-cmpn" data-cid="' . $dt->id . '">' . $this->SCTEXT('Delete Campaign') . '</a></li></ul></div>';

            $defstr = $dt->is_default == 0 ? '' : '<span class="label label-sm m-l-sm label-success">default</span>';
            if ($_SESSION['user']['group'] == 'admin' || $_SESSION['user']['account_type'] == '0') {
                $output = array($dt->campaign_name . $defstr, $kstr, $rstr, $button_str);
            } else {
                $output = array($dt->campaign_name . $defstr, $kstr, $button_str);
            }

            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function addNewCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Campaigns'] = Doo::conf()->APP_URL . 'campaigns';
        $data['active_page'] = 'Add New Campaign';

        //get all assigned keywords
        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
        if ($_SESSION['user']['group'] != 'admin') $kobj->user_assigned = $_SESSION['user']['userid'];
        $opt['filters'] = array();
        $opt['filters'][0]['model'] = 'ScVmnList';
        $opt['select'] = 'sc_vmn_primary_keywords.id,sc_vmn_primary_keywords.keyword,sc_vmn_list.vmn';
        $data['kws'] = Doo::db()->find($kobj, $opt);

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));


        $data['page'] = '2WAY';
        $data['current_page'] = 'add_cmpn';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addCampaign', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Campaigns'] = Doo::conf()->APP_URL . 'campaigns';
        $data['active_page'] = 'Edit Campaign';

        //get all assigned keywords
        $kobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
        if ($_SESSION['user']['group'] != 'admin') $kobj->user_assigned = $_SESSION['user']['userid'];
        $opt['filters'] = array();
        $opt['filters'][0]['model'] = 'ScVmnList';
        $opt['select'] = 'sc_vmn_primary_keywords.id,sc_vmn_primary_keywords.keyword,sc_vmn_list.vmn';
        $data['kws'] = Doo::db()->find($kobj, $opt);

        //get route information
        $data['routes'] = $_SESSION['credits']['routes'];

        //get all approved sender IDs if route allows it
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));

        //get campaign details
        $cobj = Doo::loadModel('ScUsersCampaigns', true);
        $cobj->id = $this->params['id'];
        $data['cdata'] = Doo::db()->find($cobj, array('limit' => 1));

        $data['page'] = '2WAY';
        $data['current_page'] = 'edit_cmpn';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editCampaign', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        $obj = Doo::loadModel('ScUsersCampaigns', true);
        //collect values
        $campaign_name = DooTextHelper::cleanInput($_POST['cname'], ' ', 0);
        $campaign_desc = htmlspecialchars($_POST['cdesc'], ENT_QUOTES);
        $keyword = intval($_POST['pkeyword']);
        $optin_kws = DooTextHelper::cleanInput($_POST['optin_kws'], ',');
        $optin_reply = htmlspecialchars($_POST['optin_reply'], ENT_QUOTES);
        $optout_kws = DooTextHelper::cleanInput($_POST['optout_kws'], ',');
        $optout_reply = htmlspecialchars($_POST['optout_reply'], ENT_QUOTES);
        $rtdata = explode("|", $_POST['rsmpp']);
        $defroute = intval($rtdata[0]);
        $defsender = intval($rtdata[1]) == 2 ? $_POST['senderopn'] : $_POST['sendersel'];
        $def_flag = intval($_POST['isdef']);
        //reset default campaigns
        if ($def_flag == 1) {
            Doo::db()->query('UPDATE sc_users_campaigns SET is_default=0 WHERE user_id=' . $_SESSION['user']['userid']);
        }
        //check if insert
        if (intval($_POST['cid']) == 0) {
            //insert
            $obj->user_id = $_SESSION['user']['userid'];
            $obj->campaign_name = $campaign_name;
            $obj->campaign_desc = $campaign_desc;
            $obj->primary_keyword_id = $keyword;
            $obj->optin_keywords = $optin_kws;
            $obj->optin_reply_sms = $optin_reply;
            $obj->optout_keywords = $optout_kws;
            $obj->optout_reply_sms = $optout_reply;
            $obj->default_sms_route = $defroute;
            $obj->default_sender = $defsender;
            $obj->is_default = $def_flag;
            Doo::db()->insert($obj);
            $msg = 'New campaign added successfully.';
        } else {
            //update
            $obj->id = $_POST['cid'];
            $rs = Doo::db()->find($obj, array('limit' => 1));
            if ($rs->id && $rs->user_id == $_SESSION['user']['userid']) {
                $obj->id = $rs->id;
                $obj->campaign_name = $campaign_name;
                $obj->campaign_desc = $campaign_desc;
                $obj->primary_keyword_id = $keyword;
                $obj->optin_keywords = $optin_kws;
                $obj->optin_reply_sms = $optin_reply;
                $obj->optout_keywords = $optout_kws;
                $obj->optout_reply_sms = $optout_reply;
                $obj->default_sms_route = $defroute;
                $obj->default_sender = $defsender;
                $obj->is_default = $def_flag;
                Doo::db()->update($obj);
                $msg = 'Campaign parameters modified successfully.';
            } else {
                //not allowed
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
                return Doo::conf()->APP_URL . 'campaigns';
            }
        }

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'campaigns';
    }

    public function deleteCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        //get campaign data
        $obj = Doo::loadModel('ScUsersCampaigns', true);
        $obj->id = $this->params['id'];
        $rs = Doo::db()->find($obj, array('limit' => 1));
        if ($rs->is_default == 1) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Cannot delete default campaign. Set any other campaign as default and try again.';
            return Doo::conf()->APP_URL . 'campaigns';
        }
        if ($rs->status == 1) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'This campaign is reserved for system messaging. Please do not delete this.';
            return Doo::conf()->APP_URL . 'campaigns';
        }
        if ($rs->user_id != $_SESSION['user']['userid']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
            return Doo::conf()->APP_URL . 'campaigns';
        }
        //delete campaign
        $obj->id = $rs->id;
        Doo::db()->delete($obj);
        //delete optin optout data
        $optinobj = Doo::loadModel('ScUsersCampaignsOptins', true);
        $optinobj->campaign_id = $rs->id;
        Doo::db()->delete($optinobj);
        $optoutobj = Doo::loadModel('ScUsersCampaignsOptouts', true);
        $optoutobj->campaign_id = $rs->id;
        Doo::db()->delete($optoutobj);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Campaign was deleted successfully.';
        return Doo::conf()->APP_URL . 'campaigns';
    }

    public function viewOptinList()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Campaigns'] = Doo::conf()->APP_URL . 'campaigns';
        $data['active_page'] = 'View Opt-in Numbers';

        //get campaign
        $obj = Doo::loadModel('ScUsersCampaigns', true);
        $obj->id = $this->params['id'];
        $obj->user_id = $_SESSION['user']['userid'];
        $data['cdata'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = '2WAY';
        $data['current_page'] = 'cmpn_optin';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewOptinList', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getOptinList()
    {
        $this->isLogin();
        $columns = array(
            array('db' => 'mobile', 'dt' => 0),
            array('db' => 'keyword_matched', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }
        Doo::loadModel('ScUsersCampaignsOptins');
        $obj = new ScUsersCampaignsOptins;
        $obj->campaign_id = intval($this->params['id']);
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
        $odata = Doo::db()->find($obj, $dtdata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($odata as $dt) {

            $button_str = '<button class="btn btn-danger btn-sm del-num" data-nid="' . $dt->id . '">' . $this->SCTEXT('Delete') . '</button>';

            $output = array($dt->mobile, date(Doo::conf()->date_format_long_time, strtotime($dt->date_added)), $dt->keyword_matched == '' ? '-' : '<span class="label label-info label-md">' . $dt->keyword_matched . '</span>', $button_str);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function deleteOptinNumber()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete
        $obj = Doo::loadModel('ScUsersCampaignsOptins', true);
        $obj->id = $this->params['id'];
        $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id, campaign_id, user_id'));

        if ($rs->user_id != $_SESSION['user']['userid']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
            return Doo::conf()->APP_URL . 'campaigns';
        }
        $obj->id = $rs->id;
        Doo::db()->delete($obj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Optin number deleted successfully.';
        return Doo::conf()->APP_URL . 'viewOptinList/' . $rs->campaign_id;
    }

    public function viewOptoutList()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Campaigns'] = Doo::conf()->APP_URL . 'campaigns';
        $data['active_page'] = 'View Opt-out Numbers';

        //get campaign
        $obj = Doo::loadModel('ScUsersCampaigns', true);
        $obj->id = $this->params['id'];
        $obj->user_id = $_SESSION['user']['userid'];
        $data['cdata'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = '2WAY';
        $data['current_page'] = 'cmpn_optout';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewOptoutList', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getOptoutList()
    {
        $this->isLogin();
        $columns = array(
            array('db' => 'mobile', 'dt' => 0),
            array('db' => 'keyword_matched', 'dt' => 2)
        );

        Doo::LoadHelper('DatatableSSP');
        $dtdata = DatatableSSP::getData($_REQUEST, $columns);

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }
        Doo::loadModel('ScUsersCampaignsOptouts');
        $obj = new ScUsersCampaignsOptouts;
        $obj->campaign_id = intval($this->params['id']);
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;
        $odata = Doo::db()->find($obj, $dtdata);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($odata as $dt) {

            $button_str = '<button class="btn btn-danger btn-sm del-num" data-nid="' . $dt->id . '">' . $this->SCTEXT('Delete') . '</button>';

            $output = array($dt->mobile, date(Doo::conf()->date_format_long_time, strtotime($dt->date_added)), $dt->keyword_matched == '' ? '-' : '<span class="label label-info label-md">' . $dt->keyword_matched . '</span>', $button_str);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function deleteOptoutNumber()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete
        $obj = Doo::loadModel('ScUsersCampaignsOptouts', true);
        $obj->id = $this->params['id'];
        $rs = Doo::db()->find($obj, array('limit' => 1, 'select' => 'id, campaign_id, user_id'));

        if ($rs->user_id != $_SESSION['user']['userid']) {
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Action Not Allowed';
            return Doo::conf()->APP_URL . 'campaigns';
        }
        $obj->id = $rs->id;
        Doo::db()->delete($obj);

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Optout number deleted successfully.';
        return Doo::conf()->APP_URL . 'viewOptoutList/' . $rs->campaign_id;
    }

    public function addOptoutContacts()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['campaigns']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        $campaign_id = $this->params['id'];

        //breadcrums
        $data['links']['2-Way Messaging'] = 'javascript:void(0);';
        $data['links']['Manage Campaigns'] = Doo::conf()->APP_URL . 'campaigns';
        $data['links']['Opt-out Numbers'] = Doo::conf()->APP_URL . 'viewOptoutList/' . $campaign_id;
        $data['active_page'] = 'Add Opt-out Numbers';

        //get campaign
        $obj = Doo::loadModel('ScUsersCampaigns', true);
        $obj->id = $this->params['id'];
        $obj->user_id = $_SESSION['user']['userid'];
        $data['cdata'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = '2WAY';
        $data['current_page'] = 'cmpn_optout';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addOptoutContacts', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveOptoutContacts()
    {
        $this->isLogin();
        //save all the supplied msisdns as optout for this user
        $campaign_id = $_POST['campaignid'];
        $msisdns = $_POST['msisdns'];
        if ($msisdns == "") {
            //return with error
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Please supply at least one number.';
            return Doo::conf()->APP_URL . 'addOptoutContacts/' . $campaign_id;
        }
        //explode msisdns by newline
        $all_msidns = explode("\n", $msisdns);
        if (count($all_msidns) == 0) {
            //return with error
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Please supply at least one number.';
            return Doo::conf()->APP_URL . 'addOptoutContacts/' . $campaign_id;
        }
        $insQ = "INSERT INTO `sc_users_campaigns_optouts` (`user_id`, `campaign_id`, `mobile`) VALUES ";
        $validnums = [];
        foreach ($all_msidns as $msisdn) {
            $mob = intval($msisdn);
            if ($mob > 0 && strlen($mob) > 6 && strlen($mob) < 16) {
                array_push($validnums, $mob);
                $insQ .= "('" . $_SESSION['user']['userid'] . "', '" . $campaign_id . "', '" . $mob . "'),";
            }
        }
        if (count($validnums) == 0) {
            //return with error
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = 'Please supply at least one number.';
            return Doo::conf()->APP_URL . 'addOptoutContacts/' . $campaign_id;
        }
        $insQ = rtrim($insQ, ',');
        Doo::db()->query($insQ);
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'Optout numbers added successfully.';
        return Doo::conf()->APP_URL . 'viewOptoutList/' . $campaign_id;
    }

    public function getReplies()
    {
        $user_matched = 0;
        //get the variables
        $time = date(Doo::conf()->date_format_db);
        $req_url = $_SERVER['REQUEST_URI'];
        $req_data = base64_encode(serialize($_GET));
        $vmn = $_GET['senderid']; //vmn is also the sender id
        $mobile = $_GET['phone'];
        $smstext = $_GET['reply'];
        $incoming_smsc = $_GET['smscid'];
        $foreign_smsid = $_GET['vmsgid']; //this will usually be empty if direct incoming sms
        $internal_smsid = $_GET['intsmsid'];
        $smsc_systemid = $_GET['accountidf'];

        //add in inbox
        Doo::loadModel('ScVmnInbox');
        $inbobj = new ScVmnInbox;
        $inbobj->mobile = $mobile;
        $inbobj->vmn = $vmn;
        $inbobj->sms_text = htmlspecialchars($smstext, ENT_QUOTES);
        $inbobj->receiving_time = $time;
        $inbobj->incoming_smsc = $incoming_smsc;
        $inbobj->req_url = $req_url;
        $inbobj->sms_data = $req_data;
        $inboxid = Doo::db()->insert($inbobj);

        //check if vmn is present
        $vmnobj = Doo::loadModel('ScVmnList', true);
        $vmnobj->vmn = $vmn;
        $vmndata = Doo::db()->find($vmnobj, array('limit' => 1));

        if ($vmndata->id) {
            //vmn matched
            $user_matched = $vmndata->user_assigned;
            $reply_sms = $vmndata->default_reply;
            $reply_route = $vmndata->sysreply_smpp;
            $reply_sender = $vmndata->sysreply_sender;
            $reply_mode = $vmndata->auto_reply_type;
            $trigger_url = $vmndata->trigger_url;
            $forward_mobile = '';
            $campaignid = 0;
            if ($vmndata->type == 2) {
                //missedcall number so no need to match keywords
                $mcobj = Doo::loadModel('ScVmnMissedcalls', true);
                $mcobj->vmn_inbox_id = $inboxid;
                $mcobj->mobile = $mobile;
                $mcobj->vmn = $vmn;
                Doo::db()->insert($mcobj);
            } else {
                //shortcode or longcode
                //get all keywords for matched vmn
                $kwobj = Doo::loadModel('ScVmnPrimaryKeywords', true);
                $kwobj->vmn = $vmndata->id;
                $keywords = Doo::db()->find($kwobj);
                if (sizeof($keywords) > 0) {
                    $matched_keyword = array();
                    $otherkeyword = '';
                    $first_word = strtok($smstext, " ");
                    foreach ($keywords as $kwd) {
                        //echo 'first word of sms text is :'.$first_word."<br>";
                        //echo 'keyword is :' . $kwd->keyword . "<br>";
                        if (trim(strtolower($first_word)) == trim(strtolower($kwd->keyword))) {
                            $matched_keyword['id'] = $kwd->id;
                            $matched_keyword['keyword'] = $kwd->keyword;

                            $reply_sms = $kwd->default_reply;
                            $forward_mobile = $kwd->forward_sms_to;
                            $trigger_url = $kwd->trigger_url;
                            $user_matched = $kwd->user_assigned;

                            break;
                        }
                    }
                    if (sizeof($matched_keyword) > 0) {
                        //primary keyword matched look for optin optout keywords
                        $cmpobj = Doo::loadModel('ScUsersCampaigns', true);
                        $cmpobj->primary_keyword_id = $matched_keyword['id'];
                        $cmpdata = Doo::db()->find($cmpobj, array('limit' => 1));

                        if (intval($cmpdata->id) > 0) {

                            //campaign matched
                            $campaignid = $cmpdata->id;
                            $optin = 0;
                            $optout = 0;
                            //match opt out keywords
                            if ($cmpdata->optout_keywords != '') {
                                $optout_list = explode(",", $cmpdata->optout_keywords);
                                foreach ($optout_list as $okw) {
                                    $okpos1 = stristr($smstext, ' ' . $okw . ' ');
                                    $okpos2 = stristr($smstext, ' ' . $okw);
                                    if (strlen($okpos1) > 0 || strlen($okpos2) > 0) {
                                        //optout keyword found
                                        $optout = 1;
                                        $otherkeyword = $okw;
                                        $reply_sms = $cmpdata->optout_reply_sms;
                                        break;
                                    }
                                }
                            }
                            //match opt in keywords
                            if ($optout == 0 && $cmpdata->optin_keywords != '') {
                                $optin_list = explode(",", $cmpdata->optin_keywords);
                                foreach ($optin_list as $ikw) {
                                    $ikpos1 = stristr($smstext, ' ' . $ikw . ' ');
                                    $ikpos2 = stristr($smstext, ' ' . $ikw);
                                    if (strlen($ikpos1) > 0 || strlen($ikpos2) > 0) {
                                        //optout keyword found
                                        $optin = 1;
                                        $otherkeyword = $ikw;
                                        $reply_sms = $cmpdata->optin_reply_sms;
                                        break;
                                    }
                                }
                            }
                            //set default reply route and sender
                            $user_matched = $cmpdata->user_id;
                            if ($cmpdata->default_sms_route != 0) $reply_route = $cmpdata->default_sms_route;
                            if ($cmpdata->default_sender != '') $reply_sender = $cmpdata->default_sender;
                        }
                    }
                }
            }
        }

        //check if matching with sender id is allowed
        if (Doo::conf()->match_mo_vmn_with_sender == 1 && $vmndata->user_assigned == 0) {
            $senderobj = Doo::loadModel('ScSenderId', true);
            $senderobj->sender_id = $vmn;
            $senderobj->status = 1;
            $matchsender = Doo::db()->find($senderobj, array('limit' => 1, 'select' => 'req_by'));
            if (intval($matchsender->req_by) > 0) {
                //sender matched
                $user_matched = $matchsender->req_by;
            }
            //try to match the user by last sent
            if ($user_matched == 0) {
                $scsentobj = Doo::loadModel('ScSentSms', true);
                $scsentobj->mobile = $mobile;
                $lastsentsms = Doo::db()->find($scsentobj, array('limit' => 5, 'desc' => 'id'));
                $campaignrow = $lastsentsms[0]; //the latest campaign
                $user_matched = $campaignrow->user_id;
                //check for optout
                $ucmpobj = Doo::loadModel("ScUsersCampaigns", true);
                $ucmpobj->user_id = $user_matched;
                $optoutkwsd = Doo::db()->find($ucmpobj, array('where' => 'optout_keywords <> ""'));
                if (sizeof($optoutkwsd) > 0) {
                    foreach ($optoutkwsd as $camprow) {
                        $optout_list = explode(",", $camprow->optout_keywords);
                        foreach ($optout_list as $okw) {
                            $okpos = stristr($smstext, $okw);
                            if (strlen($okpos) > 0) {
                                //optout keyword found
                                $optout = 1;
                                $otherkeyword = $okw;
                                $reply_sms = $camprow->optout_reply_sms;
                                break;
                            }
                        }
                        $campaignid = $camprow->id;
                        if ($camprow->default_sms_route != 0) $reply_route = $camprow->default_sms_route;
                        if ($camprow->default_sender != '') $reply_sender = $camprow->default_sender;
                    }
                }
            }
        }

        //reply sms sending account
        if ($user_matched != 0) {
            $reply_send_usr = $user_matched;
        } else {
            $reply_send_usr = 1; //send replies from admin account if no user matched
        }
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
        if (!isset($campaignid) || $campaignid == 0) {
            $cmpobj = Doo::loadModel('ScUsersCampaigns', true);
            $campaignid = intval($cmpobj->getCampaignId($reply_send_usr, 'system'));
        }
        $akobj = Doo::loadModel('ScApiKeys', true);
        $api_key = $akobj->getApiKey($reply_send_usr);
        //perform 2-way messaging tasks
        //1. update matched user
        if ($user_matched != 0) {
            $mcinbobj = new ScVmnInbox;
            $mcinbobj->id = $inboxid;
            $mcinbobj->user_id = $user_matched;
            Doo::db()->update($mcinbobj);
        }

        //2. send reply sms
        if (intval($reply_mode) != 0 && $reply_sms != "") {
            //auto replies are enabled
            $sms = $reply_sms;
            $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . $campaignid . '&routeid=' . $reply_route . '&type=text&contacts=' . $mobile . '&senderid=' . urlencode($reply_sender) . '&msg=' . urlencode($sms);
            //Submit to server
            //echo $api_url;
            $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
            //save auto reply
            $arobj = Doo::loadModel('ScVmnAutoReplies', true);
            $arobj->reply_against_sms_id = $inboxid;
            $arobj->campaign_id = $campaignid;
            $arobj->user_id = $user_matched;
            $arobj->vmn = $vmn;
            $arobj->primary_keyword = $matched_keyword['keyword'];
            $arobj->other_keyword = $otherkeyword;
            $arobj->sent_sms_text = htmlspecialchars($reply_sms, ENT_QUOTES);
            $arobj->mobile = $mobile;
            $arobj->route_id = $reply_route;
            $arobj->sender = $reply_sender;
            $arobj->api_response = json_encode($response);
            Doo::db()->insert($arobj);
        }

        //3. trigger specified url
        if ($trigger_url != '') {
            $turl = str_replace("%p", $mobile, $trigger_url);
            $turl2 = str_replace("%r", urlencode($smstext), $turl);
            $turl3 = str_replace("%v", urlencode($vmn), $turl2);
            $turl4 = str_replace("%i", urlencode($internal_smsid), $turl3);
            $final_url = str_replace("%t", urlencode($time), $turl4);
            //$trigger_resp = file_get_contents( $final_url, false, stream_context_create($arrContextOptions));
            //echo json_encode($trigger_resp);
            $ch = curl_init();
            $timeout = 10;

            curl_setopt($ch, CURLOPT_URL, $final_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3');
            $verbose = fopen('/tmp/trigger.log', 'a+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $result = curl_exec($ch);
            curl_close($ch);
            echo "Triggered $final_url <br> $result";

            $fp = fopen('/tmp/trigger.log', 'a+');
            fwrite($fp, "Triggered $final_url \nResult: $result \n");
            fclose($fp);
        }

        //3.1: Trigger default MO url 
        $default_mo_url = Doo::db()->find('ScUsersSettings', array('select' => 'default_mo_url', 'where' => "user_id='$user_matched'", 'limit' => 1))->default_mo_url;
        if ($default_mo_url != '') {
            $turl = str_replace("%p", $mobile, $default_mo_url);
            $turl2 = str_replace("%r", urlencode($smstext), $turl);
            $turl3 = str_replace("%v", urlencode($vmn), $turl2);
            $turl4 = str_replace("%i", urlencode($internal_smsid), $turl3);
            $turl5 = str_replace("%t", urlencode($time), $turl4);
            $final_url = str_replace("%m", urlencode($matched_keyword['keyword']), $turl5);
            //$trigger_resp = file_get_contents( $final_url, false, stream_context_create($arrContextOptions));
            //echo json_encode($trigger_resp);
            $ch = curl_init();
            $timeout = 10;

            curl_setopt($ch, CURLOPT_URL, $final_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3');
            $verbose = fopen('/tmp/trigger.log', 'a+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $result = curl_exec($ch);
            curl_close($ch);
            echo "Triggered $final_url <br> $result";

            $fp = fopen('/tmp/trigger.log', 'a+');
            fwrite($fp, "Triggered $final_url \nResult: $result \n");
            fclose($fp);
        }


        //4. forward sms to specified mobile number
        if (isset($forward_mobile) && $forward_mobile != '' && intval($forward_mobile) > 0) {
            $sms = "SMS RECEIVED ON $vmn:\nFrom: $mobile\nMsg: $smstext";
            $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $api_key . '&campaign=' . $campaignid . '&routeid=' . $reply_route . '&type=text&contacts=' . $forward_mobile . '&senderid=' . $reply_sender . '&msg=' . urlencode($sms);
            //Submit to server
            $fw_resp = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
            //echo json_encode($fw_resp);
        }
        //5. save optin or optout
        if ($optin == 1) {
            $oiobj = Doo::loadModel('ScUsersCampaignsOptins', true);
            $oiobj->user_id = $user_matched;
            $oiobj->campaign_id = $campaignid;
            $oiobj->mobile = $mobile;
            $oiobj->keyword_matched = $otherkeyword;
            Doo::db()->insert($oiobj);
        }
        if ($optout == 1) {
            $oo_obj = Doo::loadModel('ScUsersCampaignsOptouts', true);
            $oo_obj->user_id = $user_matched;
            $oo_obj->campaign_id = $campaignid;
            $oo_obj->mobile = $mobile;
            $oo_obj->keyword_matched = $otherkeyword;
            Doo::db()->insert($oo_obj);
        }
        //6. Save in MO Queue to be forwarded to SMPP client
        $vmnsmppobj = Doo::loadModel('ScSmppClients', true);
        $vmnsmppobj->user_id = $user_matched;
        $vmnsmppobj->vmn = $vmn;
        $smppclient = Doo::db()->find($vmnsmppobj, array('limit' => 1));
        if ($smppclient) {

            $modata = array(
                "msg" => htmlspecialchars($smstext, ENT_QUOTES),
                "data_coding" => $this->detectUTF8($smstext) ? 8 : 0
            );
            $mosmppobj = Doo::loadModel('ScSmppClientDlr', true);
            $mosmppobj->smppclient = $smppclient->system_id;
            $mosmppobj->sms_id = random_int(1, 32765);
            $mosmppobj->sender = $vmn; //not the sender of msg but the senderID or VMN, sorry for confusing name
            $mosmppobj->msisdn = $mobile;
            $mosmppobj->pdu_seq = random_int(1, 255);
            $mosmppobj->is_mo = 1;
            $mosmppobj->mo_data = json_encode($modata);
            Doo::db()->insert($mosmppobj);
        }
        exit;
    }
    //14. SMPP DLR

    public function getSmppDlr()
    {

        // https://${env.ADMIN_DOMAIN}/getSmppDlr/index?smppclient=${smppclient}&sender=${encodeURIComponent(sms.senderid)}&routeid=${sms.routeid}&smsid=${sms.smsid}&userid=${sms.userid}&persmscount=${sms.sms_count}&pdu_seq=${pdu.sequence_number}&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F

        $sentobj = Doo::loadModel('ScSmppClientSms', true);
        Doo::loadModel('ScUsersCreditData');
        $lcobj = Doo::loadModel('ScLogsCredits', true);
        $ldrobj = Doo::loadModel('ScLogsDlrRefunds', true);
        $upermobj = Doo::loadModel('ScUsersPermissions', true);
        $drefobj = Doo::loadModel('ScSmppCustomDlrCodes', true);

        //collect data
        $dlr = $_REQUEST['dlr'];
        $vendor_reply = $_REQUEST['vendor_dlr'];
        //parse dlr
        $err = urldecode($vendor_reply);
        if (strpos($err, 'NACK') === false) {
            //look for err and the code is right next to it
            // preg_match("/err:(\d{3})/",$err,$ecar);
            // $errcode = $ecar[1];
            // above code was legacy. commented because it fails for error code of 4 digits
            $err_array = explode("err:", $err);
            $stat_array = explode("stat:", $err);
            $errcode = strtok($err_array[1], " ");
            $smppcode = strtok($stat_array[1], " ");
        } else {
            //break with '/' sign
            $ecar = explode("/", $err);
            $errcode = $ecar[1];
            $smppcode = 'NACK';
        }
        $smsid = $_REQUEST['smsid'];
        $vmsgid = $_REQUEST['vmsgid'];
        $routeid = intval($_REQUEST['routeid']);
        $userid = intval($_REQUEST['userid']);

        $errcode_org = $errcode;

        if ($dlr == '1' && $errcode == '') $errcode = '000';
        if ($dlr == '2' && ($errcode == '' || $errcode == '000')) $errcode = '-4';
        if ($dlr == '16' && ($errcode == '' || $errcode == '000')) $errcode = '-4';
        if ($dlr == '8' && ($errcode == '' || $errcode == '000')) $errcode = '-6';

        if ($dlr == '1' && $smppcode == "") $smppcode = 'DELIVRD';

        //update dlr
        $sentobj->smpp_resp_code = $smppcode;
        $sentobj->dlr = $dlr;
        $sentobj->vendor_dlr = $errcode;
        $sentobj->vendor_msgid = $vmsgid;
        $sentobj->es_index_status = 1;

        $where = "`smpp_smsid` = '$smsid'";
        Doo::db()->update($sentobj, array('where' => $where, 'limit' => 1));

        //add entry in smpp dlr to be forwarded to the client by NodeJS process (if requested by smpp cliemt)
        if (intval($_REQUEST['dlrreq']) > 0) {
            $sdobj = Doo::loadModel('ScSmppClientDlr', true);
            $sdobj->smppclient = $_REQUEST['smppclient'];
            $sdobj->sms_id = $smsid;
            $sdobj->sender = $_REQUEST['sender'];
            $sdobj->msisdn = $_REQUEST['mobile'];
            $sdobj->pdu_seq = $_REQUEST['pdu_seq'];
            $sdobj->dlr = $dlr;
            $sdobj->vendor_dlr = $errcode;
            $sdobj->smpp_resp_code = $smppcode;
            Doo::db()->insert($sdobj);
        }


        $delv = 0;
        $fail = 0;
        $refc = 0;

        if ($dlr == '1') $delv = 1;
        if ($dlr == '2' || $dlr == '16') $fail = 1;


        if ($dlr != '8' && $errcode_org != '') {
            //NOT ACK
            //check if refund applicable
            #$drefobj->route_id = $routeid;
            $drefobj->vendor_dlr_code = $errcode;
            $drefdata = Doo::db()->find($drefobj, array('limit' => 1, 'select' => 'action,param_value'));
            //process refund
            if ($drefdata->action == '1') {
                //check if user is allowed this refund
                $upermobj->user_id = $userid;
                $upermdata = Doo::db()->find($upermobj, array('limit' => 1, 'select' => 'perm_data'));

                if ($upermdata) {
                    $perms = unserialize($upermdata->perm_data);
                    if ($perms['ref'][$drefdata->param_value] == 'on') {
                        //refund allowed
                        $creobj = new ScUsersCreditData;
                        $subcre = new ScUsersCreditData;

                        $olducredits = $subcre->getRouteCredits($userid, $routeid);
                        $newavcredits = $creobj->doCreditTrans('credit', $userid, $routeid, $_REQUEST['persmscount']);

                        //make log entry
                        $lcobj->user_id = $userid;
                        $lcobj->timestamp = date(Doo::conf()->date_format_db);
                        $lcobj->amount = $_REQUEST['persmscount'];
                        $lcobj->route_id = $routeid;
                        $lcobj->credits_before = $olducredits;
                        $lcobj->credits_after = $newavcredits;
                        $lcobj->reference = 'Credit Refund (DLR)';
                        $lcobj->comments = 'Refund was made against DLR with following details:|| MSISDN: ' . $_REQUEST['mobile'] . ' (ID: ' . $smsid . ')';
                        Doo::db()->insert($lcobj);

                        //make log entry
                        $ldrobj->user_id = $userid;
                        $ldrobj->sms_shoot_id = $smsid;
                        $ldrobj->mobile_no = $_REQUEST['mobile'];
                        $ldrobj->vendor_dlr = $errcode;
                        $ldrobj->refund_amt = $_REQUEST['persmscount'];
                        $ldrobj->refund_rule = $drefdata->param_value;
                        $ldrobj->timestamp = date(Doo::conf()->date_format_db);
                        Doo::db()->insert($ldrobj);

                        //make stats entry
                        $refc = $_REQUEST['persmscount'];
                    }
                }
            }
            //check if rerouting needed
            if ($drefdata->action == '2') {
                $reroute_id = intval($drefdata->param_value);
                //get smpp details for this route
                $rdqry = "SELECT smsc_id FROM sc_smpp_accounts WHERE id = (SELECT smpp_id FROM sc_sms_routes WHERE id = $reroute_id)";
                $smppobj = Doo::db()->fetchRow($rdqry, null, PDO::FETCH_OBJ);
                $smsc = $smppobj->smsc_id;
                //get data for sms submission

                $smsobj = Doo::loadModel('ScSmppClientSms', true);
                $smsobj->smpp_smsid = $smsid;
                $smsdata = Doo::db()->find($smsobj, array('limit' => 1, 'select' => 'sender_id,sms_type,sms_text'));
                $smstype = unserialize($smsdata->sms_type);
                $smstext = $smstype['main'] == 'text' ? htmlspecialchars_decode($smsdata->sms_text, ENT_QUOTES) : base64_encode(serialize($smsdata->sms_text));

                $smpp_dlrurl = Doo::conf()->APP_URL . 'getSmppDlr/index?smppclient=' . $_REQUEST['smppclient'] . '&sender=' . $_REQUEST['sender'] . '&routeid=' . $reroute_id . '&smsid=' . $smsid . '&userid=' . $_REQUEST['userid'] . '&persmscount=' . $_REQUEST['persmscount'] . '&pdu_seq=' . $_REQUEST['pdu_seq'] . '&mobile=%p&dlr=%d&vendor_dlr=%A&vmsgid=%F';

                //$sms['sms_shoot_id'] = $shoot_id;
                $sms['route_id'] = $reroute_id;
                $sms['user_id'] = $userid;
                //$sms['upline_id'] = $_REQUEST['upline_id'];
                //$sms['umsgid'] = $_REQUEST['umsgid'];
                $sms['manual_dlrurl'] = $smpp_dlrurl;
                $sms['smscount'] = $_REQUEST['persmscount'];
                $sms['smsc'] = $smsc;
                $sms['senderid'] = $_REQUEST['sender'];
                $sms['contacts'] = $_REQUEST['mobile'];
                $sms['sms_type'] = $smsdata->sms_type;
                $sms['sms_text'] = $smstext;
                $sms['usertype'] = $_REQUEST['usertype'];
                DooSmppcubeHelper::pushToKannel($sms);
            }
        }
    }

    public function getApiSmppDlr()
    {
        $sentobj = Doo::loadModel('ScSmppClientSms', true);
        $apidlr = $_REQUEST["MessageStatus"];
        $to = $_REQUEST["To"];
        $smsid = $_REQUEST['smsid'];
        $dlr = 0;
        if ($apidlr == 'delivered') {
            $dlr = 1;
            $errcode = '000';
            $smppcode = 'DELIVRD';
        }
        if ($apidlr == 'failed') {
            $dlr = 16;
            $errcode = '-4';
            $smppcode = 'REJECTD';
        }
        if ($apidlr == 'undelivered') {
            $dlr = 2;
            $errcode = '-4';
            $smppcode = 'UNDELIV';
        }
        if ($apidlr == 'sent') {
            $dlr = 8;
            $errcode = '-6';
            $smppcode = 'ACK';
        }

        //update dlr
        if ($dlr > 0) {
            $sentobj->smpp_resp_code = $smppcode;
            $sentobj->dlr = $dlr;
            $sentobj->vendor_dlr = $errcode;
            $sentobj->vendor_msgid = $_REQUEST["MessageSid"];
            $sentobj->es_index_status = 1;

            $where = "`smpp_smsid` = '$smsid'";
            $res = Doo::db()->update($sentobj, array('where' => $where, 'limit' => 1));

            //add entry in smpp dlr to be forwarded to the client by NodeJS process
            $sdobj = Doo::loadModel('ScSmppClientDlr', true);
            $sdobj->smppclient = $_REQUEST['smppclient'];
            $sdobj->sms_id = $smsid;
            $sdobj->sender = $_REQUEST['sender'];
            $sdobj->msisdn = $to;
            $sdobj->pdu_seq = $_REQUEST['pdu_seq'];
            $sdobj->dlr = $dlr;
            $sdobj->vendor_dlr = $errcode;
            $sdobj->smpp_resp_code = $smppcode;
            Doo::db()->insert($sdobj);

            echo 'API SMPP DLR updated' . json_encode($res);
        }
    }

    //15. HLR Lookup

    public function viewHlrReports()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['active_page'] = 'View HLR Reports';

        $data['page'] = 'HLR';
        $data['current_page'] = 'hlrreports';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/viewHlrReports', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getHlrReports()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        $columns = array(
            array('db' => 'msisdn', 'dt' => 0)
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
            $sWhere = "`req_date` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
        }

        if ($sWhere != '') {
            if ($dtdata['where'] == '') {
                $dtdata['where'] = $sWhere;
            } else {
                $dtdata['where'] = $sWhere . ' AND ' . $dtdata['where'];
            }
        }

        if (empty($dtdata['asc']) && empty($dtdata['desc'])) {
            $dtdata['desc'] = 'id';
        }
        $obj = Doo::loadModel('ScHlrLookups', true);
        $obj->user_id = $_SESSION['user']['userid'];
        $ldata = Doo::db()->find($obj, $dtdata);
        $total = Doo::db()->find($obj, array('select' => 'count(`id`) as total', 'where' => $dtdata['where'], 'limit' => 1))->total;

        $mncobj = Doo::loadModel('ScMccMncList', true);
        $covobj = Doo::loadModel('ScCoverage', true);
        $covdata = Doo::db()->find($covobj);

        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();

        foreach ($ldata as $dt) {

            $hlrstr = $dt->hlr_status == 0 ? '<span class="label label-warning">Pending</span>' : ($dt->hlr_status == 1 ? '<span class="label label-success">Successful</span>' : '<span class="label label-danger">Failed</span>');
            $mccmncstr = $dt->mccmnc == 0 ? '-' : '<kbd>' . $dt->mccmnc . '</kbd>';
            if ($dt->mccmnc != 0) {
                $netdata = $mncobj->getDetailsByMccmnc($dt->mccmnc);
                $netstr = $netdata->brand . ' - ' . $netdata->operator;
            } else {
                $netstr = '-';
            }
            $constr = $dt->connected_flag == 0 ? '-' : ($dt->connected_flag == 1 ? '<span class="label label-success">Connected</span>' : '<span class="label label-danger">Unreachable</span>');
            $roamstr = $dt->roaming_flag == 0 ? '-' : ($dt->roaming_flag == 1 ? 'NO' : 'YES');
            $portstr = $dt->ported_flag == 0 ? '-' : ($dt->ported_flag == 1 ? 'NO' : 'YES');
            $coviso = $dt->original_location;
            $romiso = $dt->roaming_location;
            if ($coviso != '') {
                if (strlen(trim($coviso)) > 2) {
                    $covstr = $coviso;
                } else {
                    $cmap = function ($e) use ($coviso) {
                        return $e->country_code == $coviso;
                    };
                    $cvfobj = array_filter($covdata, $cmap);
                    $k = key($cvfobj);
                    $covstr = $covdata[$k]->country;
                }
            } else {
                $covstr = '-';
            }
            $prcstr = $_SESSION['user']['account_type'] == 1 ? '<span class="label label-danger">- ' . Doo::conf()->currency . $dt->lookup_cost . '</span>' : '<span class="label label-danger">- ' . $dt->lookup_cost . '</span>';

            $resdata = $dt->response_data == '' ? base64_encode('HLR Lookup Pending . . .') : $dt->response_data;
            $resbtn = '<button class="btn btn-xs force-xs btn-info showHlrData" data-rinfo="' . $resdata . '">View Response</button>';
            $output = array($dt->msisdn, $hlrstr, $mccmncstr, $netstr, $constr, date(Doo::conf()->date_format_short_time_s, strtotime($dt->req_date)), $roamstr, $portstr, $covstr, $prcstr, $resbtn);
            array_push($res['aaData'], $output);
        }

        echo json_encode($res);
        exit;
    }

    public function downloadHlr()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        $mccmncqry = "SELECT mccmnc, CONCAT(brand, '||', operator) as brandop FROM `sc_mcc_mnc_list`";
        $mccmncdata = Doo::db()->fetchAll($mccmncqry, null, PDO::FETCH_KEY_PAIR);
        //date range
        $dr = $this->params['dr'];
        if (trim(urldecode($dr)) != 'Select Date' && $dr != '' && $dr != NULL) {
            //split the dates
            $datr = explode("-", urldecode($dr));
            $from = date('Y-m-d', strtotime(trim($datr[0])));
            $to = date('Y-m-d', strtotime(trim($datr[1])));
            $sWhere = "`req_date` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
        }
        //prepare query and fetch data
        $qry = "SELECT msisdn, hlr_status, mccmnc, connected_flag, roaming_flag, ported_flag, original_location, roaming_location, lookup_cost FROM `sc_hlr_lookups` WHERE user_id = " . $_SESSION['user']['userid'];

        $results = Doo::db()->fetchAll($qry, null, PDO::FETCH_OBJ);
        $columns = ['Mobile', 'HLR Status', 'MCCMNC', 'Network', 'Roaming', 'Ported', 'Original Location', 'Roaming Location'];
        $csvdata = [];

        foreach ($results as $dt) {
            $roamstr = $dt->roaming_flag == 0 ? '-' : ($dt->roaming_flag == 1 ? 'NO' : 'YES');
            $portstr = $dt->ported_flag == 0 ? '-' : ($dt->ported_flag == 1 ? 'NO' : 'YES');
            $coviso = $dt->original_location;
            $romiso = $dt->roaming_location;
            $constr = $dt->connected_flag == 0 ? '-' : ($dt->connected_flag == 1 ? 'Connected' : 'Unreachable');
            $mccstr = explode("||", $mccmncdata[$dt->mccmnc]);

            $ar = [$dt->msisdn, $constr, $dt->mccmnc, $mccstr[0] . ' ' . $mccstr[1], $roamstr, $portstr, $coviso, $romiso];
            array_push($csvdata, $ar);
        }

        DooSmppcubeHelper::exportAsCsv(array('columns' => $columns, 'rows' => $csvdata), $_SESSION['user']['loginid'] . '_hlr_reports.csv');
    }

    public function newHlrLookup()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['HLR Lookup Reports'] = Doo::conf()->APP_URL . 'viewHlrReports';
        $data['active_page'] = 'New HLR Lookup';

        if ($_SESSION['user']['group'] == 'admin') {
            //get all channels
            $obj = Doo::loadModel('ScHlrChannels', true);
            $data['channels'] = Doo::db()->find($obj);
        } else {
            //get hlr lookup settings for user
            $uhobj = Doo::loadModel('ScUsersHlrSettings', true);
            $uhobj->user_id = $_SESSION['user']['userid'];
            $data['hlrdata'] = Doo::db()->find($uhobj, array('limit' => 1));
            if (!$data['hlrdata']->id || $data['hlrdata']->channel_id == 0) {
                $data['hlrperm'] = 0;
            } else {
                $data['hlrperm'] = 1;
            }
        }

        $data['page'] = 'HLR';
        $data['current_page'] = 'newhlrlookup';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/newHlrLookup', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function submitHlrLookup()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['hlr']) {
            //denied
            return array('/denied', 'internal');
        }
        $numbers = explode("\n", $_POST["numbers"]);
        //get channel details
        if ($_SESSION['user']['group'] == 'admin') {
            //get selected channel
            $cid = $_POST['channel'];
            $chobj = Doo::loadModel('ScHlrChannels', true);
            $chobj->id = $cid;
            $chdata = Doo::db()->find($chobj, array('limit' => 1));
            $lookupcost = 0;
            $channel_id = $chobj->id;
        } else {
            //get assigned channel
            $uhobj = Doo::loadModel('ScUsersHlrSettings', true);
            $uhobj->user_id = $_SESSION['user']['userid'];
            $usrhlrdata = Doo::db()->find($uhobj, array('limit' => 1));
            if (!$usrhlrdata->id || $usrhlrdata->channel_id == 0) {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = 'No HLR channel assigned';
                return Doo::conf()->APP_URL . 'viewHlrReports';
            }
            $chobj = Doo::loadModel('ScHlrChannels', true);
            $chobj->id = $usrhlrdata->channel_id;
            $chdata = Doo::db()->find($chobj, array('limit' => 1));
            $channel_id = $chobj->id;
            $lookupcost = $_SESSION['user']['account_type'] == 0 ? 1 : $usrhlrdata->credits_cost;
        }
        $invalids = array();
        $validnums = array();
        $validinsertqry = 'INSERT INTO sc_hlr_lookups(channel_id, user_id, msisdn, provider_id, lookup_cost) VALUES';
        $invalidinsertqry = 'INSERT INTO sc_hlr_lookups(channel_id, user_id, msisdn, provider_id, lookup_cost, hlr_status) VALUES';
        foreach ($numbers as $phn) {
            if (!DooTextHelper::verifyFormData('mobile', intval($phn))) {
                array_push($invalids, $phn);
                $invalidinsertqry .= $channel_id . ',' . $_SESSION['user']['userid'] . ',' . $phn . ',' . intval($chdata->provider_id) . ',0,3),';
            } else {
                array_push($validnums, intval($phn));
                $validinsertqry .= '(';
                $validinsertqry .= $channel_id . ',' . $_SESSION['user']['userid'] . ',' . $phn . ',' . intval($chdata->provider_id) . ',' . $lookupcost . '),';
            }
        }
        if (sizeof($validnums) > 0) {
            if ($_SESSION['user']['group'] == 'admin') {
                //insert in hlr table
                $validinsertqry = substr($validinsertqry, 0, strlen($validinsertqry) - 1);
                $rs = Doo::db()->query($validinsertqry);
                //perform lookup
                $data['numbers'] = $validnums;
                $data['authdata'] = unserialize($chdata->auth_data);
                Doo::loadHelper('DooHlrLookupHelper');
                $hlrobj = new DooHlrLookupHelper($chdata->provider_id);
                $hlrresp = $hlrobj->sendHlrRequest($data);
            } else {
                //check if available credits
                if ($_SESSION['user']['account_type'] == 0) {
                    //credit based
                    $credits_req = sizeof($validnums);
                    if ($credits_req > $usrhlrdata->credits_cost) {
                        //low credits
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = 'Insufficient credits available for this HLR lookup request. Total required credits is ' . $credits_req;
                        return Doo::conf()->APP_URL . 'newHlrLookup';
                    } else {
                        //deduct credits
                        $newcredits = intval($usrhlrdata->credits_cost) - intval($credits_req);
                        $uhobj->id = $usrhlrdata->id;
                        $uhobj->credits_cost = $newcredits;
                        Doo::db()->update($uhobj);
                        //no log option for credit based accounts
                    }
                } else {
                    //currency based
                    $credits_req = sizeof($validnums) * $usrhlrdata->credits_cost;
                    if ($_SESSION['credits']['wallet']['amount'] < $credits_req) {
                        $_SESSION['notif_msg']['type'] = 'error';
                        $_SESSION['notif_msg']['msg'] = 'Insufficient account balancr available for this HLR lookup request. Total credits required is ' . Doo::conf()->currency . $credits_req;
                        return Doo::conf()->APP_URL . 'newHlrLookup';
                    }
                    $wlobj = Doo::loadModel('ScUsersWallet', true);
                    $newwallet = $wlobj->doCreditTrans('deduct', $_SESSION['user']['userid'], $credits_req);
                    $_SESSION['credits']['wallet']['amount'] = $newwallet['after'];
                    //make log entry
                    $lcobj = Doo::loadModel('ScLogsCredits', true);
                    $lcobj->user_id = $_SESSION['user']['userid'];
                    $lcobj->timestamp = date(Doo::conf()->date_format_db);
                    $lcobj->amount = '-' . $credits_req;
                    $lcobj->route_id = 0;
                    $lcobj->credits_before = $newwallet['before'];
                    $lcobj->credits_after = $newwallet['after'];
                    $lcobj->reference = 'HLR Lookup';
                    $lcobj->comments = 'HLR lookup was performed. Total numbers: ' . sizeof($validnums);
                    Doo::db()->insert($lcobj);
                }

                //insert in hlr table
                $validinsertqry = substr($validinsertqry, 0, strlen($validinsertqry) - 1);
                $rs = Doo::db()->query($validinsertqry);
                //perform lookup
                $data['numbers'] = $validnums;
                $data['authdata'] = unserialize($chdata->auth_data);
                Doo::loadHelper('DooHlrLookupHelper');
                $hlrobj = new DooHlrLookupHelper($chdata->provider_id);
                $hlrresp = $hlrobj->sendHlrRequest($data);
            }
        }

        if (sizeof($invalids) > 0) {
            //add in hlr table
            $invalidinsertqry = substr($invalidinsertqry, 0, strlen($invalidinsertqry) - 1);
            $rs = Doo::db()->query($invalidinsertqry);
        }
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'HLR lookup request submitted successfully. Please check back in few minutes for reports.';
        return Doo::conf()->APP_URL . 'viewHlrReports';
    }

    public function hlrApiCallback()
    {
        $provid = $this->params['providerid'];
        Doo::loadHelper('DooHlrLookupHelper');

        $hlrobj = new DooHlrLookupHelper($provid);
        if ($provid == 3) {
            $response = $hlrobj->parseResponse(file_get_contents('php://input'));
        } else {
            $response = $hlrobj->parseResponse($_REQUEST);
        }
        //update records
        if (sizeof($response) > 0) {
            foreach ($response as $data) {
                $query = 'UPDATE sc_hlr_lookups SET ';
                $query .= 'hlr_status=' . $data['hlr_status'] . ', ';
                $query .= 'mccmnc=' . intval($data['mccmnc']) . ', ';
                $query .= 'connected_flag=' . $data['connected_flag'] . ', ';
                $query .= 'roaming_flag=' . $data['roaming_flag'] . ', ';
                $query .= 'ported_flag=' . $data['ported_flag'] . ', ';
                $query .= "original_location='" . $data['original_location'] . "', ";
                $query .= "roaming_location='" . $data['roaming_location'] . "', ";
                $query .= "response_data='" . $data['response_data'] . "' ";
                $query .= 'WHERE provider_id=' . $provid . ' AND msisdn=' . intval($data['msisdn'] . ' AND hlr_status=0');
                $rs = Doo::db()->query($query);
            }
        }
        echo 'DONE';
        exit;
    }

    //16. TLV Parameters

    public function manageClientTlv()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage TLV Parameters';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_client_tlv';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageClientTlv', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllClientTlv()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        $tlvobj = Doo::loadModel('ScSmppTlv', true);
        $utlv = Doo::loadModel('ScUsersTlvValues', true);
        $utlv->user_id = $_SESSION['user']['userid'];
        $user_tlvs = Doo::db()->find($utlv);
        $total = sizeof($user_tlvs);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($user_tlvs as $dt) {

            $button_str = '<a class="btn btn-danger delutlv" href="javascript:void(0);" data-tlvid="' . $dt->id . '"><i class="fas fa-trash-alt"></i></a>';

            $output = array($dt->tlv_title, $dt->tlv_category, $dt->tlv_value, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addClientTlv()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['Manage TLV Parameters'] = Doo::conf()->APP_URL . 'manageClientTlv';
        $data['active_page'] = 'Add TLV Parameter';

        //get all routes assigned to this client
        $data['routes'] = $_SESSION['credits']['routes'];

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_client_tlv';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addClientTlv', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveClientTlv()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        $utlv = Doo::loadModel('ScUsersTlvValues', true);
        $utlv->user_id = $_SESSION['user']['userid'];
        $utlv->assoc_route = intval($_POST['route']);
        $utlv->tlv_category = DooTextHelper::cleanInput($_POST['tlv_type'], "_");
        $utlv->tlv_title = DooTextHelper::cleanInput($_POST['tlv_title'], "# ", 0);
        $utlv->tlv_value = DooTextHelper::cleanInput($_POST['tlv_value']);
        Doo::db()->insert($utlv);
        $_SESSION['notif_msg']['msg'] = 'TLV parameter added successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageClientTlv';
    }

    public function deleteClientTlv()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['messaging']['tlv']) {
            //denied
            return array('/denied', 'internal');
        }
        $utlv = Doo::loadModel('ScUsersTlvValues', true);
        $utlv->id = intval($this->params['id']);
        Doo::db()->delete($utlv);
        $_SESSION['notif_msg']['msg'] = 'TLV parameter deleted successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageClientTlv';
    }

    //15. OTP Channels and API
    public function manageOtpChannels()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Manage OTP Channels';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_otp_channels';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageOtpChannels', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function getAllOtpChannels()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        $rtobj = Doo::loadModel('ScSmsRoutes', true);
        $otpobj = Doo::loadModel('ScUsersOtpChannels', true);
        $otpobj->user_id = $_SESSION['user']['userid'];
        $user_otp_channels = Doo::db()->find($otpobj);
        $total = sizeof($user_otp_channels);
        $res = array();
        $res['iTotalRecords'] = $total;
        $res['iTotalDisplayRecords'] = $total;
        $res['aaData'] = array();
        foreach ($user_otp_channels as $dt) {

            $button_str = '<div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> ' . $this->SCTEXT('Actions') . ' <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-right"><li><a href="' . Doo::conf()->APP_URL . 'editOtpChannel/' . $dt->id . '">' . $this->SCTEXT('Edit Channel') . '</a></li><li><a href="javascript:void(0);" class="del-otpch" data-cid="' . $dt->id . '">' . $this->SCTEXT('Delete Channel') . '</a></li></ul></div>';

            $route_title = $rtobj->getRouteData($dt->route_id, 'title');

            $output = array($dt->title, $route_title->title, $dt->sender, $button_str);
            array_push($res['aaData'], $output);
        }
        echo json_encode($res);
    }

    public function addOtpChannel()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['Manage OTP Channels'] = Doo::conf()->APP_URL . 'manageOtpChannels';
        $data['active_page'] = 'Add OTP Channel';

        //get all sender id
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_otp_channel';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addOtpChannel', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editOtpChannel()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }

        //breadcrums
        $data['links']['Manage OTP Channels'] = Doo::conf()->APP_URL . 'manageOtpChannels';
        $data['active_page'] = 'Edit OTP Channel';

        //get Channel details
        $otpchobj = Doo::loadModel('ScUsersOtpChannels', true);
        $otpchobj->id = intval($this->params['id']);
        $otpchobj->user_id = $_SESSION['user']['userid'];
        $data['channel'] = Doo::db()->find($otpchobj, array('limit' => 1));

        //get all sender id
        Doo::loadModel('ScSenderId');
        $sidobj = new ScSenderId;
        if ($_SESSION['user']['group'] != 'admin') $sidobj->req_by = $_SESSION['user']['userid'];
        $sidobj->status = 1;
        $data['sids'] = Doo::db()->find($sidobj, array('select' => 'DISTINCT(sender_id)'));

        $data['page'] = 'Messaging';
        $data['current_page'] = 'edit_otp_channel';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editOtpChannel', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function saveOtpChannel()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        $otpchobj = Doo::loadModel('ScUsersOtpChannels', true);

        if (intval($_POST['otpchid']) > 0) {
            //edit
            $otpchobj->id = intval($_POST['otpchid']);
            $otpchobj->user_id = $_SESSION['user']['userid'];
            $rs = Doo::db()->find($otpchobj, array('limit' => 1));
            if (!$rs) {
                $_SESSION['notif_msg']['msg'] = 'Invalid Action.';
                $_SESSION['notif_msg']['type'] = 'error';
                return Doo::conf()->APP_URL . 'manageOtpChannels';
            } else {
                $otpchobj->id = $rs->id;
                $otpchobj->title = DooTextHelper::cleanInput($_POST['title'], ' ', 0);
                $otpchobj->route_id = intval($_POST['otproute']);
                $otpchobj->sender = DooTextHelper::cleanInput($_POST['otpsender'], ' ', 0);
                $otpchobj->template = htmlspecialchars($_POST['otptemplate'], ENT_QUOTES);
                Doo::db()->update($otpchobj);
                $_SESSION['notif_msg']['msg'] = 'OTP channel modified successfully.';
                $_SESSION['notif_msg']['type'] = 'success';
                return Doo::conf()->APP_URL . 'manageOtpChannels';
            }
        } else {
            //insert
            $otpchobj->title = DooTextHelper::cleanInput($_POST['title'], ' ', 0);
            $otpchobj->user_id = $_SESSION['user']['userid'];
            $otpchobj->route_id = intval($_POST['otproute']);
            $otpchobj->sender = DooTextHelper::cleanInput($_POST['otpsender'], ' ', 0);
            $otpchobj->template = htmlspecialchars($_POST['otptemplate'], ENT_QUOTES);
            Doo::db()->insert($otpchobj);
            $_SESSION['notif_msg']['msg'] = 'New OTP channel added successfully.';
            $_SESSION['notif_msg']['type'] = 'success';
            return Doo::conf()->APP_URL . 'manageOtpChannels';
        }
    }

    public function deleteOtpChannel()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        $otpchobj = Doo::loadModel('ScUsersOtpChannels', true);
        $otpchobj->id = intval($this->params['id']);
        $otpchobj->user_id = $_SESSION['user']['userid'];
        Doo::db()->delete($otpchobj);
        $_SESSION['notif_msg']['msg'] = 'OTP channel deleted successfully.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'manageOtpChannels';
    }

    public function viewOtpApi()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['otp_api']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['active_page'] = 'OTP API';

        //get API Key
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $data['apikey'] = $ak_obj->getApiKey($_SESSION['user']['userid']);

        $data['baseurl'] = Doo::conf()->APP_URL;

        //get all channels for this account
        $otpchobj = Doo::loadModel('ScUsersOtpChannels', true);
        $otpchobj->user_id = $_SESSION['user']['userid'];
        $data['channels'] = Doo::db()->find($otpchobj);

        $data['page'] = 'API';
        $data['current_page'] = 'otpapi';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/otpApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function sendOtpApi()
    {
        //load models
        $helper = new DooSmppcubeHelper;
        $otprq_obj = Doo::loadModel('ScUsersOtpRequests', true);
        $otpch_obj = Doo::loadModel('ScUsersOtpChannels', true);

        //get all supplied params
        $apikey = $_REQUEST['key'];
        $channel_id = $_REQUEST['channel_id'];
        $mobile = $_REQUEST['mobile'];
        //validate API key
        $akobj = Doo::loadModel('ScApiKeys', true);
        $userid = $akobj->getUserByKey($apikey);
        if (!$userid) {
            echo $helper->getApiResponse('http', 'invalidkey');
            exit;
        }
        $cmpobj = Doo::loadModel('ScUsersCampaigns', true);
        $campaign_id = $cmpobj->getCampaignId($userid, 'system');

        //validate mobile number
        if (!is_numeric($mobile) || strlen($mobile) < 9) {
            echo $helper->getApiResponse('http', 'invalidmobile');
            exit;
        }
        //validate channel
        $otpch_obj->id = $channel_id;
        $otpch_obj->user_id = $userid;
        $channel = Doo::db()->find($otpch_obj, array('limit' => 1));
        if (intval($channel->route_id) == 0 || !$channel) {
            echo $helper->getApiResponse('http', 'invalidotpchannel');
            exit;
        }

        //generate a reference and otp, save in db
        $reference = strtoupper(uniqid('OTP') . mt_rand(150, 150000));
        $otp = rand(100000, 999999);

        $otprq_obj->user_id = $userid;
        $otprq_obj->channel_id = $channel_id;
        $otprq_obj->attempts = 0;
        $otprq_obj->reference = $reference;
        $otprq_obj->otp = md5($otp);
        Doo::db()->insert($otprq_obj);

        //call sms api and send sms
        if ($channel->template == "") {
            $smstext = $this->SCTEXT('Your One Time Password for your phone verification is:') . ' ' . $otp;
        } else {
            $smstext = str_replace("#OTP#", $otp, $channel->template);
        }
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
        $api_url = Doo::conf()->APP_URL . 'smsapi/index?key=' . $apikey . '&campaign=' . intval($campaign_id) . '&routeid=' . $channel->route_id . '&type=text&contacts=' . $mobile . '&senderid=' . $channel->sender . '&msg=' . urlencode($smstext);
        $response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));
        //return response
        $result = array('result' => 'success', 'reference' => $reference);
        echo json_encode($result);
        exit;
    }

    public function verifyOtpApi()
    {
        //load models
        $helper = new DooSmppcubeHelper;
        $otprq_obj = Doo::loadModel('ScUsersOtpRequests', true);
        //get parameters
        $apikey = $_REQUEST['key'];
        $reference = $_REQUEST['reference'];
        $otp = $_REQUEST['otp'];
        //validate API key
        $akobj = Doo::loadModel('ScApiKeys', true);
        $userid = $akobj->getUserByKey($apikey);
        if (!$userid) {
            echo $helper->getApiResponse('http', 'invalidkey');
            exit;
        }
        //get request data by reference
        $otprq_obj->user_id = $userid;
        $otprq_obj->reference = $reference;
        $requestdata = Doo::db()->find($otprq_obj, array('limit' => 1));
        //validate request
        if (!$requestdata || $requestdata->otp == "") {
            echo $helper->getApiResponse('http', 'invalidreference');
            exit;
        }
        if ($requestdata->attempts > 4) {
            //delete
            $otprq_obj_del = Doo::loadModel('ScUsersOtpRequests', true);
            $otprq_obj_del->id = $requestdata->id;
            Doo::db()->delete($otprq_obj_del);
            $result = array('result' => 'error', "message" => "TOO MANY ATTEMPTS. OTP EXPIRED.");
            echo json_encode($result);
            exit;
        }
        $now = date(Doo::conf()->date_format_db);
        $now_ts = strtotime($now);
        $added_on = $requestdata->added_on;
        $added_on_ts = strtotime($added_on);
        $duration = ($now_ts - $added_on_ts) / 60;
        if ($duration > 10) {
            $otprq_obj_del = Doo::loadModel('ScUsersOtpRequests', true);
            $otprq_obj_del->id = $requestdata->id;
            Doo::db()->delete($otprq_obj_del);
            $result = array('result' => 'success', "match" => "expired");
            echo json_encode($result);
            exit;
        }
        //match otp
        if (md5($otp) == $requestdata->otp) {
            $otprq_obj_del = Doo::loadModel('ScUsersOtpRequests', true);
            $otprq_obj_del->id = $requestdata->id;
            Doo::db()->delete($otprq_obj_del);
            $result = array('result' => 'success', "match" => "true");
            echo json_encode($result);
            exit;
        } else {
            //not matched, update attempt
            $otprq_obj_upd = Doo::loadModel('ScUsersOtpRequests', true);
            $otprq_obj_upd->id = $requestdata->id;
            $otprq_obj_upd->attempts = intval($requestdata->attempts) + 1;
            Doo::db()->update($otprq_obj_upd);
            $result = array('result' => 'success', "match" => "false");
            echo json_encode($result);
            exit;
        }
    }

    public function whatsappApi()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['API'] = 'javascript:void(0);';
        $data['active_page'] = 'WhatsApp API';

        //get API Key
        Doo::loadModel('ScApiKeys');
        $ak_obj = new ScApiKeys;
        $data['apikey'] = $ak_obj->getApiKey($_SESSION['user']['userid']);

        $data['baseurl'] = Doo::conf()->APP_URL;
        //get all whatsapp agents for this account

        $data['page'] = 'API';
        $data['current_page'] = 'whatsappApi';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/whatsappApi', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }


    //x. Whatsapp Templates Mgmt

    public function manageWhatsappTemplates()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'WhatsApp Templates';

        //echo '<pre>'; var_dump($data['temps']); die;

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_wtemplates';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageWhatsappTemplates', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function syncWhatsappTemplates()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        //delete all templates from db for this logged in user
        $delqry = 'DELETE FROM wba_templates WHERE user_id = ' . $_SESSION['user']['userid'];
        Doo::db()->query($delqry);
        //get all templates for this WABA 
        if ($_SESSION['user']['group'] == 'admin') {
            //sync with defaukt waba
            $url = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_waba_id . '/message_templates?fields=id,category,components,language,name,status,quality_score,rejected_reason';
            $options = array('http' => array(
                'method'  => 'GET',
                'header' => 'Authorization: Bearer ' . Doo::conf()->wba_perm_token
            ));
            $context  = stream_context_create($options);
            $waba_templates = json_decode(file_get_contents($url, false, $context));
        } else {
            //get user waba details
            $wtobj = Doo::loadModel('WbaAgents', true);
            $wtobj->user_id = $_SESSION['user']['userid'];
            $wabadata = Doo::db()->find($wtobj, array('limit' => 1));
            //sync with user waba
            $url = 'https://graph.facebook.com/v19.0/' . $wabadata->waba_id . '/message_templates?fields=id,category,components,language,name,status,quality_score,rejected_reason';
            $options = array('http' => array(
                'method'  => 'GET',
                'header' => 'Authorization: Bearer ' . Doo::conf()->wba_perm_token
            ));
            $context  = stream_context_create($options);
            $waba_templates = json_decode(file_get_contents($url, false, $context));
        }

        //save in DB
        Doo::loadModel('WbaTemplates');
        foreach ($waba_templates->data as $temp) {
            //insert in db
            $tmpobj = new WbaTemplates;
            $tmpobj->user_id = $_SESSION['user']['userid'];
            $tmpobj->name = $temp->name;
            $tmpobj->category_info = json_encode(array('category' => $temp->category));
            $tmpobj->meta_info =  json_encode(array('language' => $temp->language, 'id' => $temp->id, 'quality_score' => $temp->quality_score, 'rejected_reason' => $temp->rejected_reason));
            $tmpobj->components = json_encode($temp->components);
            $tmpobj->status = $temp->status == 'APPROVED' ? 1 : ($temp->status == 'REJECTED' ? 2 : 0);
            Doo::db()->insert($tmpobj);
        }
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'WhatsApp Templates are synced successfully';
        return Doo::conf()->APP_URL . 'manageWhatsappTemplates';
    }

    public function addWhatsappTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage WhatsApp Templates'] = Doo::conf()->APP_URL . 'manageWhatsappTemplates';
        $data['active_page'] = 'Add WhatsApp Template';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_wtemplate';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addWhatsappTemplate', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function editWhatsappTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage SMS Templates'] = Doo::conf()->APP_URL . 'manageTemplates';
        $data['active_page'] = 'Edit Template';

        //get data
        $tid = intval($this->params['id']);
        if ($tid == 0) {
            //invalid id
            return array('/denied', 'internal');
        }
        Doo::loadModel('ScSmsTemplates');
        $obj = new ScSmsTemplates;
        $obj->id = $tid;
        $obj->user_id = $_SESSION['user']['userid'];
        $data['temp'] = Doo::db()->find($obj, array('limit' => 1));

        $data['page'] = 'Messaging';
        $data['current_page'] = 'edit_template';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/editTemplate', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function deleteWhatsappTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        //get data
        $tid = $this->params['id'];
        $tname = base64_decode($this->params['tname']);

        $url = 'https://graph.facebook.com/v19.0/' . Doo::conf()->wba_waba_id . '/message_templates?hsm_id=' . $tid . '&name=' . $tname;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array("Content-type: application/json", "Authorization: Bearer " . Doo::conf()->wba_perm_token)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $jresult = json_decode($result);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status != 201 && $status != 200) {
            die("Error: call to URL $url failed with status $status, response $result, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch));
        }
        curl_close($ch);

        $msg = 'Template delete request submitted successfully.';
        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = $msg;
        return Doo::conf()->APP_URL . 'manageWhatsappTemplates';
    }

    public function saveWhatsappTemplate()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }

        //collect values
        $tid = intval($_POST['tempid']);
        $uploaded_file_hex = isset($_POST["uploadedFiles"]) ? $_POST["uploadedFiles"][0] : '';
        //build the component array
        $components = [];

        $header['type'] = "HEADER";
        if ($_POST['wt_type'] == 0) {
            $header['format'] = "TEXT";
            $header['text'] = $_POST['wtheader'];
            $header['example']['header_text'][0] = $_POST['header_egvar'];
        }
        if ($_POST['wt_type'] == 1) {
            if ($uploaded_file_hex == "") {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = "Please upload an image.";
                return Doo::conf()->APP_URL . 'addWhatsappTemplate';
            }
            $file_names = base64_decode($uploaded_file_hex);
            $file_names_ar = explode("||", $file_names, 2);
            if ($file_names_ar[1] == "") {
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = "File did not upload to Whatsapp servers. Please wait till the upload finishes or upload a smaller file";
                return Doo::conf()->APP_URL . 'addWhatsappTemplate';
            }
            $header['format'] = "IMAGE";
            $header['example']['header_handle'][0] = $file_names_ar[1];
        }
        if ($_POST['wt_type'] == 2) {
            $header['format'] = "LOCATION";
        }


        $body['type'] = 'BODY';
        $body['text'] = $_POST['tcont'];
        $body['example']['body_text'][0] = $_POST["body_eg_list"] != '' ? explode(",", $_POST["body_eg_list"]) : [];

        $footer['type'] = 'FOOTER';
        $footer['text'] = $_POST['wtfooter'];

        if (isset($_POST['btntype']) && sizeof($_POST['btntype']) > 0) {
            //buttons are set
            $buttons['type'] = 'BUTTONS';
            for ($i = 0; $i < sizeof($_POST['btntype']); $i++) {
                if ($_POST['btntype'][$i] == 0) {
                    //quick reply
                    $buttons['buttons'][$i] = ["type" => "QUICK_REPLY", "text" => $_POST["vals"][$i]];
                } elseif ($_POST['btntype'][$i] == 1) {
                    //phone number
                    $buttons['buttons'][$i] = ["type" => "PHONE_NUMBER", "text" => $_POST["captions"][$i], "phone_number" => $_POST["vals"][$i]];
                } elseif ($_POST['btntype'][$i] == 2) {
                    //url
                    $buttons['buttons'][$i] = ["type" => "URL", "text" => $_POST["captions"][$i], "url" => $_POST["vals"][$i]];
                } elseif ($_POST['btntype'][$i] == 3) {
                    //copy code
                    $buttons['buttons'][$i] = ["type" => "COPY_CODE", "example" => $_POST["vals"][$i]];
                } elseif ($_POST['btntype'][$i] == 4) {
                    //dynamic url
                    $buttons['buttons'][$i] = ["type" => "URL", "text" => $_POST["captions"][$i], "url" => $_POST["vals"][$i], "example" => [0 => $_POST["btnvars"][$i]]];
                }
            }
        }
        array_push($components, $header, $body, $footer, $buttons);
        // echo '<pre>';
        // print_r($components);
        // die;
        $waobj = Doo::loadModel('WbaAgents', true);
        $waobj->user_id = $_SESSION['user']['userid'];
        $wabadata = Doo::db()->find($waobj, array('limit' => 1));

        //save the template in the database
        $tmpobj = Doo::loadModel('WbaTemplates', true);
        $tmpobj->user_id = $_SESSION['user']['userid'];
        $tmpobj->name = $_POST['tname'];
        $tmpobj->category_info = json_encode(array('category' => $_POST['wtemp_cat'] == 0 ? 'MARKETING' : ($_POST['wtemp_cat'] == 1 ? 'UTILITY' : 'AUTHENTICATION'), 'allow_category_change' => true));
        $tmpobj->meta_info = json_encode(array('language' => strtolower($_POST['lang'])));
        $tmpobj->components = json_encode($components);
        $tmpobj->status = 0;
        Doo::db()->insert($tmpobj);

        if ($tid == 0) {
            //insert api
            $capverUrl = 'https://graph.facebook.com/v19.0/' . $wabadata->waba_id . '/message_templates';
            $postdata =
                array(
                    'name' => $_POST['tname'],
                    'category' => $_POST['wtemp_cat'] == 0 ? 'MARKETING' : ($_POST['wtemp_cat'] == 1 ? 'UTILITY' : 'AUTHENTICATION'),
                    'allow_category_change' => true,
                    'language' => strtolower($_POST['lang']),
                    'components' => $components
                );
            //echo '<pre>'; print_r(($postdata));die;
            $curl = curl_init($capverUrl);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array("Content-type: application/json", "charset: utf-8", "Authorization: Bearer " . Doo::conf()->wba_perm_token)
            );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($status != 201 && $status != 200) {
                die("Error: call to URL $capverUrl failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
                $error_msg = json_decode($json_response, true);
                $_SESSION['notif_msg']['type'] = 'error';
                $_SESSION['notif_msg']['msg'] = $error_msg['error']['error_user_title'] . ' ' . $error_msg['error']['error_user_msg'];
                return Doo::conf()->APP_URL . 'addWhatsappTemplate';
            }
            $response = json_decode($json_response, true);
            $msg = 'New WhatsApp template requested successfully';
        } else {
            //update
            //$msg = 'SMS template updated successfully';
        }

        // echo '<pre>'; var_dump($capdata);
        // echo '<pre>'; var_dump($response);
        // echo '<pre>'; print_r(json_encode($postdata));
        // die;

        //return
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'New WhatsApp template requested successfully';
        return Doo::conf()->APP_URL . 'manageWhatsappTemplates';
    }

    public function composeWhatsappCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (!isset($_SESSION['notif_msg']['type'])) {
            $_SESSION['notif_msg']['type'] = "info";
            $_SESSION['notif_msg']['msg'] = "Only Facebook/Meta Approved Templates can be used for WhatsApp Campaigns. <a href='" . Doo::conf()->APP_URL . 'addWhatsappTemplate' . "' class='text-primary'>Click here</a> to Request A Template";
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'Send WhatsApp Campaign';

        //get the contacts as well
        Doo::loadModel('ScUserContactGroups');
        $cgobj = new ScUserContactGroups;
        $cgobj->user_id = $_SESSION['user']['userid'];
        $gdata = Doo::db()->find($cgobj, array('select' => 'id,group_name,column_labels'));

        $grpdata = array();
        if (sizeof($gdata) > 0) {
            Doo::loadModel('ScUserContacts');
            $ucobj = new ScUserContacts;

            foreach ($gdata as $grp) {
                $colar = unserialize($grp->column_labels);
                $colstr = '<option value="varA">mobile</option>';
                $colstr .= '<option value="varB">name</option>';
                if ($colar['varC'] != '') $colstr .= '<option value="varC">' . $colar['varC'] . '</option>';
                if ($colar['varD'] != '') $colstr .= '<option value="varD">' . $colar['varD'] . '</option>';
                if ($colar['varE'] != '') $colstr .= '<option value="varE">' . $colar['varE'] . '</option>';
                if ($colar['varF'] != '') $colstr .= '<option value="varF">' . $colar['varF'] . '</option>';
                if ($colar['varG'] != '') $colstr .= '<option value="varG">' . $colar['varG'] . '</option>';


                $gd['id'] = $grp->id;
                $gd['name'] = $grp->group_name;
                $gd['colstr'] = $colstr;
                $gd['count'] = $ucobj->countContacts($grp->id);
                array_push($grpdata, $gd);
            }
        }
        $data['gdata'] = $grpdata;

        //get all business profiles for this user
        $bpobj = Doo::loadModel('WbaAgentBusinessProfiles', true);
        $bpobj->user_id = $_SESSION['user']['userid'];
        $data['bprofiles'] = Doo::db()->find($bpobj);


        $data['page'] = 'Messaging';
        $data['current_page'] = 'send_whatsapp';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/composeWhatsapp', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function pushWhatsAppCampaign()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        //collect variable
        if ($_POST["numbers"] == "") {
            //contact group is selected
            $group_id = intval($_POST['group']);
            $mobile = Doo::db()->fetchAll('SELECT mobile as varA, name as varB, varC, varD, varE, varF, varG FROM sc_user_contacts WHERE user_id = ' . $_SESSION['user']['userid'] . ' AND group_id = ' . $group_id . '', null, PDO::FETCH_ASSOC);
        } else {
            $mobile = explode("\n", $_POST["numbers"]);
        };
        //generate sms shoot id
        $shoot_id = str_replace(" ", '-', uniqid($_SESSION['user']['loginid']));
        $template = explode("|", $_POST['template']);
        $tn = $template[0];
        $lang = $template[1];
        $components = json_decode(base64_decode($template[2]), true);
        $buttons = array_values(array_filter($components, function ($k) {
            return $k['type'] == 'BUTTONS';
        }));
        if (sizeof($buttons) > 0 && is_array($buttons[0]['buttons']) && sizeof($buttons[0]['buttons']) > 0) {
            $dynamic_button = array_filter($buttons[0]['buttons'], function ($j) {
                return $j['type'] == 'URL' && is_array($j['example']);
            });
        } else {
            $dynamic_button = [];
        }

        // echo '<pre>';
        //print_r($components);
        //die;

        $headerobj = $components[0];
        $bodyobj = $components[1];

        if ($headerobj['format'] == "IMAGE") {
            $hd_comp = array(
                "type" => "header",
                "parameters" => array(
                    array(
                        "type" => "image",
                        "image" => array(
                            "link" => $headerobj['example']['header_handle'][0]
                        )
                    )
                )
            );
        }

        $phone_id = $_POST['waba'];
        //make meta api call
        $url = 'https://graph.facebook.com/v19.0/' . $phone_id . '/messages';
        $curl = curl_init($url);
        $campaign_responses = [];
        foreach ($mobile as $cell) {
            if (
                isset($_POST['headervarcol']) ||
                (is_array($_POST['bodyvarcol']) && sizeof($_POST['bodyvarcol']) > 0) ||
                (is_array($_POST['btnvarcol']) && sizeof($_POST['btnvarcol']) > 0)
            ) {
                //there are variables in the template
                //mobile is an assoc array as it is from contact group
                $mobile = $cell['varA'];

                $components_fin = [];
                //if header needs variable first var will be for header
                if (stristr($headerobj['text'], "{{1}}")) {
                    $hc = array(
                        "type" => 'header',
                        "parameters" => array(
                            array(
                                "type" => "text",
                                "text" => $cell[$_POST['headervarcol']]
                            )
                        )
                    );
                    array_push($components_fin, $hc);
                }
                if (isset($hd_comp)) {
                    array_push($components_fin, $hd_comp);
                }
                if (stristr($bodyobj['text'], "{{1}}")) {
                    $params = [];
                    for ($i = 0; $i < sizeof($_POST['bodyvarcol']); $i++) {

                        $pb = array(
                            "type" => "text",
                            "text" => $cell[$_POST['bodyvarcol'][$i]]
                        );
                        array_push($params, $pb);
                    }

                    $hb = array(
                        "type" => 'body',
                        "parameters" => $params
                    );
                    array_push($components_fin, $hb);
                }
                if (sizeof($dynamic_button) > 0) {
                    $i = 0;
                    foreach ($dynamic_button as $key => $btn) {
                        $hb = array(
                            "type" => 'button',
                            "sub_type" => 'url',
                            "index" => $key,
                            "parameters" => array(
                                array(
                                    "type" => "text",
                                    "text" => $cell[$_POST['btnvarcol'][$i]]
                                )

                            )
                        );
                        array_push($components_fin, $hb);
                        $i++;
                    }
                }

                $data = array(
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => "$mobile",
                    "type" => "template",
                    "template" => array(
                        "name" => "$tn",
                        "language" => array(
                            "code" => "$lang"
                        ),
                        "components" => $components_fin
                    )

                );
            } else {
                $data = array(
                    "messaging_product" => "whatsapp",
                    "to" => "$cell",
                    "type" => "template",
                    "template" => array(
                        "name" => "$tn",
                        "language" => array(
                            "code" => "$lang"
                        )
                    )
                );
            }
            //print_r($components_fin);

            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array("Content-type: application/json", "Authorization: Bearer " . Doo::conf()->wba_perm_token)
            );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

            $json_response = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status != 201 && $status != 200) {
                echo ("Error: call to URL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            }
            array_push($campaign_responses, $json_response);
            //save in db with response
            // $wobj = Doo::loadModel('WbaCampaigns', true);
            // $wobj->user_id = $_SESSION['user']['userid'];
            // $wobj->wba_id = $wabaid;
            // $wobj->campaign_name = $cname;
            // $wobj->template_id = $_POST['template'];
            // $wobj->recipient = isset($mob) ? $mob : $cell;
            // $wobj->meta_resp = base64_encode($json_response);
            // Doo::db()->insert($wobj);
        }
        curl_close($curl);

        $cmpobj = Doo::loadModel('WbaCampaigns', true);
        $cmpobj->shoot_id = $shoot_id;
        $cmpobj->user_id = $_SESSION['user']['userid'];
        $cmpobj->phone_id = $phone_id;
        $cmpobj->template_id = $tn;
        $cmpobj->total_contacts = sizeof($mobile);
        $cmpobj->meta_resp = json_encode($campaign_responses);
        Doo::db()->insert($cmpobj);

        //echo '<pre>';
        // var_dump($json_response);
        // die;
        //return
        $_SESSION['notif_msg']['msg'] = 'Campaign successfully submitted to Meta. Please wait for a while for delivery notifications.';
        $_SESSION['notif_msg']['type'] = 'success';
        return Doo::conf()->APP_URL . 'composeWhatsappCampaign';
    }

    public function viewWhatsappReports()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Reports'] = 'javascript:void(0);';
        $data['active_page'] = 'WhatsApp Campaign Reports';

        $data['page'] = 'Reports';
        $data['current_page'] = 'wh_reports';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/whatsappReports', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function wabaConversations()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Manage WABA'] = Doo::conf()->APP_URL . 'manageWaba';
        $data['active_page'] = 'View WABA Details';

        //get business id

        $businessid = $this->params['wabaid'];

        //for regular clients
        $options = array('http' => array(
            'method'  => 'GET',
            'header' => 'Authorization: Bearer ' . Doo::conf()->wba_perm_token
        ));
        $context  = stream_context_create($options);

        //foreach WABA get the details
        $waba_id = $businessid;
        //get phone numbers from WABA ID
        $wp_url = 'https://graph.facebook.com/v19.0/' . $waba_id . '/phone_numbers';
        $waba_nums = json_decode(file_get_contents($wp_url, false, $context), true);
        $waba_phn_bp = [];
        foreach ($waba_nums['data'] as $wabaphn) {
            $waba_phonenumber_id = $wabaphn['id'];
            //get business profile using phone number id of the first registed phone number
            $wba_url = 'https://graph.facebook.com/v19.0/' . $waba_phonenumber_id . '/whatsapp_business_profile?fields=about,address,description,email,profile_picture_url,websites,vertical';
            $waba_bp = json_decode(file_get_contents($wba_url, false, $context), true);

            array_push($waba_phn_bp, $waba_bp['data']);
        }
        //save in array
        $complete_waba['wabaid'] = $waba_id;
        $complete_waba['waba_business_profiles'] = $waba_phn_bp;
        $complete_waba['waba_phone_data'] = $waba_nums['data'];

        $data['wabadata'] = $complete_waba;
        //echo '<pre>'; print_r($all_wabas); die;

        //get contacts from database
        $wcobj = Doo::loadModel('WbaContacts', true);
        $wcobj->waba_id = $businessid;
        $data['contacts'] = Doo::db()->find($wcobj);

        //foreach contact fetch the last message
        $wbmsgobj = Doo::loadModel('WbaMessages', true);
        $last_msgs = [];
        foreach ($data['contacts'] as $wco) {
            $wbmsgobj->contact_id = $wco->id;
            $wcmsg = Doo::db()->find($wbmsgobj, array('desc' => 'id', 'limit' => 1));
            $last_msgs[$wco->id]['msg'] = DooSmppcubeHelper::aesDecrypt($wcmsg->message);
            $last_msgs[$wco->id]['dir'] = $wcmsg->direction;
            $last_msgs[$wco->id]['status'] = $wcmsg->status;
            $last_msgs[$wco->id]['sent_time'] = $wcmsg->sent_time;
            $last_msgs[$wco->id]['last_update'] = $wcmsg->last_update;
        }

        $data['last_msgs'] = $last_msgs;
        $data['page'] = 'User Management';
        $data['current_page'] = 'waba_details';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/wabaDetails', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    public function whatsAppNotifs()
    {

        //echo $_REQUEST['hub_challenge'];exit;
        $req = json_decode(file_get_contents("php://input"), true);
        $message = $req['entry'][0]['changes'][0]['value']['messages'][0];
        $waba_id = $req['entry'][0]['id'];
        $phone_id = $req['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];

        if ($message['type'] == "text") {
            //convert emoji codes to symbol
            $replaced = preg_replace("/\\\\u([0-9A-F]{1,4})/i", "&#x$1;", $message["text"]["body"]);
            $result = mb_convert_encoding($replaced, "UTF-16", "HTML-ENTITIES");
            $result = mb_convert_encoding($result, 'utf-8', 'utf-16');
            $result = DooSmppcubeHelper::aesEncrypt($result);

            $contact = $req['entry'][0]['changes'][0]['value']['contacts'][0];
            $wcobj = Doo::loadModel('WbaContacts', true);
            $wcobj->wa_id = $contact['wa_id'];
            $wcdata = Doo::db()->find($wcobj, array('limit' => 1));
            if (intval($wcdata->id) > 0) {
                //here we have incoming msg, store it
                $wmobj = Doo::loadModel('WbaMessages', true);
                $wmobj->meta_msg_id = $message['id'];
                $wmobj->user_id = 0;
                $wmobj->phone_id = $wcdata->phone_id;
                $wmobj->waba_id = $wcdata->waba_id;
                $wmobj->wa_id = $wcdata->wa_id;
                $wmobj->contact_id = $wcdata->id;
                $wmobj->message = $result;
                $wmobj->direction = 1;
                Doo::db()->insert($wmobj);
            } else {
                //a new contact, save contact first then the message

                $newwcobj = Doo::loadModel('WbaContacts', true);
                $newwcobj->user_id = 0;
                $newwcobj->phone_id = $phone_id;
                $newwcobj->waba_id = $waba_id;
                $newwcobj->contact = $message['from'];
                $newwcobj->name = $contact['profile']['name'];
                $newwcobj->wa_id = $contact['wa_id'];
                $newcid = Doo::db()->insert($newwcobj);
                //now add sms
                $wmobj = Doo::loadModel('WbaMessages', true);
                $wmobj->meta_msg_id = $message['id'];
                $wmobj->user_id = 0;
                $wmobj->phone_id = $phone_id;
                $wmobj->waba_id = $waba_id;
                $wmobj->wa_id = $contact['wa_id'];
                $wmobj->contact_id = $newcid;
                $wmobj->message = $result;
                $wmobj->direction = 1;
                Doo::db()->insert($wmobj);
            }
        }
        $status = $req['entry'][0]['changes'][0]['value']['statuses'][0];
        if (isset($status['id'])) {
            //this is delivery notification
            $finstatus = 0;
            if ($status['status'] == 'sent') $finstatus = 1;
            if ($status['status'] == 'delivered') $finstatus = 2;
            if ($status['status'] == 'read') $finstatus = 3;

            //update record
            $qry = "UPDATE wba_messages SET ";
            if ($finstatus != 0) $qry .= " status = $finstatus,";
            $qry .= " conversation_id = '" . $status['conversation']['id'] . "' WHERE meta_msg_id = '" . $status['id'] . "'";
            Doo::db()->query($qry);
        }
        //save as file
        $str = base64_encode(file_get_contents("php://input"));
        $tok = DooSmppcubeHelper::getToken(7);
        $timestamp = strtotime(date('Y-m-d H:i:s'));
        $filename = '/var/www/html/WABAHOOK-' . $timestamp . '-API-' . $tok . '.txt';

        $myfile = fopen($filename, "w");
        fwrite($myfile, $str);
        fclose($myfile);

        echo 'DONE';
        exit;
    }
    public function getWabaChats()
    {
        $this->isLogin();

        $wcobj = Doo::loadModel('WbaMessages', true);
        $wcobj->contact_id = intval($this->params['cid']);
        $data = Doo::db()->find($wcobj, array('limit' => '50'));
        $msgidlist = [];
        //prepare string
        $str = '
    <div class="panel-body" style="background-image: url(\'/global/img/waba-chat-bg.png\');">
      <div id="waba-chat-msgs" style="max-height: 450px;min-height: 450px;overflow-y: auto;">';

        foreach ($data as $dt) {

            if ($dt->direction == 1) {
                $str .= '<div class="text-left m-t-sm">';
                $str .= '<div class=" message-bubble message-received"> <p class="message-text">' . DooSmppcubeHelper::aesDecrypt($dt->message) . '</p> <span class="message-time">' . date('H:i', strtotime($dt->sent_time)) . '</span></div>';
                $str .= '</div>';
                array_push($msgidlist, $dt->id);
            } else {
                $str .= '<div class="text-right m-t-sm">';
                $str .= '<div class=" message-bubble message-sent"> <p class="message-text">' . DooSmppcubeHelper::aesDecrypt($dt->message) . '</p> <span class="message-time"> ' . date('H:i', strtotime($dt->sent_time)) . '</span></div>';
                $str .= '</div>';
            }
        }

        $str .= '
        <!-- end of chats-->
      </div>
    </div>
    ';
        //mark MO messages as read
        $idlist = implode(",", $msgidlist);
        $qry = 'UPDATE wba_messages SET status=1 WHERE id IN (' . $idlist . ')';
        Doo::db()->query($qry);

        echo $str;
        exit;
    }
    public function sendWabaChat()
    {
        $this->isLogin();
        //get details from cid
        $wcobj = Doo::loadModel('WbaContacts', true);
        $wcobj->id = $_POST['cid'];
        $wcdata = Doo::db()->find($wcobj, array('limit' => 1));

        //call send msg graph api
        $data = array(
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $wcdata->contact,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => $_POST['message']
            )
        );
        $url = 'https://graph.facebook.com/v19.0/' . $wcdata->phone_id . '/messages';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array("Content-type: application/json", "Authorization: Bearer " . Doo::conf()->wba_perm_token)
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 201 && $status != 200) {
            echo ("Error: call to URL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        //save in db
        $res = json_decode($json_response, true);
        $wmobj = Doo::loadModel('WbaMessages', true);
        $wmobj->meta_msg_id = $res['messages'][0]['id'];
        $wmobj->user_id = $_SESSION['user']['userid'];
        $wmobj->phone_id = $wcdata->phone_id;
        $wmobj->waba_id = $wcdata->waba_id;
        $wmobj->wa_id = $wcdata->wa_id;
        $wmobj->contact_id = $_POST['cid'];
        $wmobj->message = DooSmppcubeHelper::aesEncrypt($_POST['message']);
        $wmobj->direction = 0;
        Doo::db()->insert($wmobj);

        //return
    }
    public function fetchUnreadWabaChats()
    {
        $this->isLogin();
        //get last sent for active contact
        $wbmsgobj = Doo::loadModel('WbaMessages', true);
        $wbmsgobj->contact_id = $this->params['cid'];
        $wcmsg = Doo::db()->find($wbmsgobj, array('desc' => 'id', 'where' => "direction = 1 AND status = 0"));
        $msgidlist = [];
        $res = [];
        foreach ($wcmsg as $msg) {
            $md['message'] = DooSmppcubeHelper::aesDecrypt($msg->message);
            $md['time'] = date("H:i", strtotime($msg->last_update));
            array_push($res, $md);
            array_push($msgidlist, $msg->id);
        }
        //mark MO messages as read
        if (sizeof($msgidlist) > 0) {
            $idlist = implode(",", $msgidlist);
            $qry = 'UPDATE wba_messages SET status=1 WHERE id IN (' . $idlist . ')';
            Doo::db()->query($qry);
        }

        echo json_encode($res);
    }

    public function saveWabaBusinessProfile()
    {
        $this->isLogin();
        if ($_SESSION['permissions']['master'] != '1' && !$_SESSION['permissions']['addons']['whatsapp']) {
            //denied
            return array('/denied', 'internal');
        }
        //send the API call to update
        //make sure admin can edit all agents but users can edit only their agents
        if ($_SESSION['user']['subgroup'] == "admin") {
            $phone_id = Doo::conf()->wba_phone_id;
        }
        $capverUrl = 'https://graph.facebook.com/v19.0/' . $phone_id . '/whatsapp_business_profile';
        $postdata =
            array(
                'messaging_product' => "whatsapp",
                'description' => $_POST['ma_desc'],
                'about' => $_POST['ma_about']
            );

        //echo '<pre>'; print_r(($postdata));die;
        $curl = curl_init($capverUrl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array("Content-type: application/json", "charset: utf-8", "Authorization: Bearer " . Doo::conf()->wba_perm_token)
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($status != 201 && $status != 200) {
            //die("Error: call to URL $capverUrl failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            $error_msg = json_decode($json_response, true);
            $_SESSION['notif_msg']['type'] = 'error';
            $_SESSION['notif_msg']['msg'] = $error_msg['error']['error_user_title'] . ' ' . $error_msg['error']['error_user_msg'];
            return Doo::conf()->APP_URL . 'viewWabaAdmin';
        }


        $response = json_decode($json_response, true);
        $_SESSION['notif_msg']['type'] = 'success';
        $_SESSION['notif_msg']['msg'] = 'WhatsApp Business Profile updated successfully';
        return Doo::conf()->APP_URL . 'viewWabaAdmin';
    }
    //x. RCS
    public function manageRichcards()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['active_page'] = 'RCS Rich Cards';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'manage_richcards';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/manageRichcards', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }


    public function addNewRichcard()
    {
        $this->isLogin();
        if (isset($_SESSION['notif_msg'])) {
            $data['notif_msg'] = $_SESSION['notif_msg'];
            unset($_SESSION['notif_msg']);
        }
        //breadcrums
        $data['links']['Messaging'] = 'javascript:void(0);';
        $data['links']['Manage RCS Richcards'] = Doo::conf()->APP_URL . 'manageRichcards';
        $data['active_page'] = 'Add New Rich Card';

        $data['page'] = 'Messaging';
        $data['current_page'] = 'add_richcard';
        $this->view()->renderc($_SESSION['user']['group'] . '/head', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/sidebar', $data);
        $this->view()->renderc('client/addNewRichcard', $data);
        $this->view()->renderc($_SESSION['user']['group'] . '/footer', $data);
    }

    /**	----------------------------------------LEGACY CODE--------------------------------------------**/


    public function updateActivity()
    {
        $this->isLogin();
        Doo::loadModel('ScUsers');
        $obj = new ScUsers;
        $obj->doLoginStat($_SESSION['user']['userid']);
        echo 'OK';
        exit;
    }


    public function partialTextMatch($str1, $str2)
    {
        $match_chars = similar_text($str1, $str2, $per);
        if ($per > 70) {
            return true;
        } else {
            return false;
        }
    }

    public function partialTextMatchScore($str1, $str2)
    {
        $match_chars = similar_text($str1, $str2, $per);
        return $per;
    }

    public function testMail()
    {
        //gather values
        $data['smtpHost'] = Doo::conf()->smtp_server;
        $data['smtpPort'] = Doo::conf()->smtp_port;
        $data['smtpUsername'] = Doo::conf()->smtp_user;
        $data['smtpPassword'] = Doo::conf()->smtp_pass;

        $data['senderName'] = 'Sam';
        $data['senderEmail'] = 'support@cubelabs.in';
        $data['receiverEmail'] = 'anu.khandelwal@cubelabs.in';
        $data['subject'] = 'Testing the Waters';
        $data['mailbody'] = 'Hello to you all!!';

        DooSmppcubeHelper::sendEmail($data);
        echo 'Mail Pushed!!';
    }

    function makeApiRequest($url, $apiToken)
    {
        $payload = json_encode([
            "campaignId" => 3,
            "routeId" => 1,
            "sender" => "CUBELB",
            "mode" => "text",
            "message" => "Please play again with us",
            "contacts" => [
                ["mobile" => "91734567890"],
                ["mobile" => "91834567890"]
            ],
            "schedule" => "2024-08-28 14:20:00"
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiToken",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch);
        curl_close($ch);
        return ["res" => $response, "code" => $httpCode];
    }

    public static function detectUTF8($string)
    {
        return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $string);
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
    public function testcall()
    {
        //bug fix of sc summary not storing cost summary
        // $qry = 'select * from sc_sms_summary';
        // $sres = Doo::db()->fetchAll($qry);
        // foreach ($sres as $row) {
        //     $sms_shoot_id = $row['sms_shoot_id'];
        //     echo $sms_shoot_id . ' --- ';
        //     //get total cost
        //     $costqry  = "select sum(cost) as cost from sc_sent_sms where sms_shoot_id = " . $sms_shoot_id;
        //     $cres = Doo::db()->fetchRow($costqry);
        //     $cost = $cres['cost'];

        //     //$iqry = "update sc_sms_summary set cost = " . $cost . " where sms_shoot_id = " . $sms_shoot_id;
        //     //Doo::db()->query($iqry);
        //     //get total contacts
        //     $partsqry = "select count(id) as parts from sc_sent_sms where sms_shoot_id = " . $sms_shoot_id;
        //     $pres = Doo::db()->fetchRow($partsqry);
        //     $parts = $pres['parts'];
        //     //$iqry = "update sc_sms_summary set parts = " . $parts . " where sms_shoot_id = " . $sms_shoot_id;
        //     //Doo::db()->query($iqry);
        //     echo $cost . ' charged for ' . $parts . ' sms <br>';
        // }
        //import data from v8 to v9

        //all api keys
        // Doo::loadHelper('DooSmppcubeHelper');
        // $oqry = "select * from old_api_keys";
        // $ores = Doo::db()->fetchAll($oqry);
        // foreach ($ores as $row) {
        //     echo $row['api_key'] . "<br>";
        //     $ekey = DooSmppcubeHelper::aesEncrypt($row['api_key']);
        //     $dhash = hash('sha256', $row['api_key']);
        //     $iqry = "insert into sc_api_keys (user_id ,api_key, dhash) values(" . $row['user_id'] . ", '" . $ekey . "', '" . $dhash . "')";
        //     Doo::db()->query($iqry);
        // }
        // die;

        // $encrypted = DooSmppcubeHelper::aesEncrypt("you are my h3r0");
        // echo "Encrypted: " . $encrypted . "\n";

        // $decrypted = DooSmppcubeHelper::aesDecrypt($encrypted);
        // echo "Decrypted: " . $decrypted . "\n";
        // die;
        //encrypt particular table data using our algo so it can be decrypted later, done to make old data compatible with new data
        // $qry = "select * from  wba_messages";
        // $res = Doo::db()->fetchAll($qry);
        // foreach ($res as $row) {
        //     $msg = DooSmppcubeHelper::aesEncrypt($row['message']);
        //     $qry2 = "UPDATE wba_messages SET message = '" . $msg . "' WHERE id = " . $row['id'];
        //     Doo::db()->query($qry2);
        // }
        // die;
        // // This will import all Meta sones with country preixes
        // $csvFile = 'meta_zones.csv';
        // // Open the CSV file
        // if (($handle = fopen($csvFile, "r")) !== FALSE) {
        //     // Skip the first row if it contains headers
        //     fgetcsv($handle);

        //     // Prepare SQL statement
        //     $qry = "INSERT INTO wba_meta_zone_countries (country, zone, prefix) VALUES";

        //     // Loop through the CSV rows
        //     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //         // Read the data from CSV columns
        //         $col1 = $data[0]; //country
        //         $col2 = $data[1] == "" ? $data[0] : $data[1]; //zone
        //         $col3 = $data[2]; //prefix

        //         $qry .= "('" . $col1 . "', '" . $col2 . "', " . intval($col3) . "),";
        //     }


        //     fclose($handle);
        //     echo $qry;

        //     //echo "CSV file imported successfully.";
        // } else {
        //     echo "Failed to open the CSV file.";
        // }

        //Next, we will import zone ID and prices into meta pricing table
        // $csvFile = 'meta_zone_prices.csv';
        // if (($handle = fopen($csvFile, "r")) !== FALSE) {
        //     // Skip the first row if it contains headers
        //     fgetcsv($handle);

        //     // Prepare SQL statement
        //     $qry = "INSERT INTO wba_meta_zone_prices (zone_id, zone, marketing, utility, cp_auth, auth_int, cp_ser) VALUES";

        //     // Loop through the CSV rows
        //     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //         // Read the data from CSV columns
        //         $zone = $data[0]; //zone name
        //         $zoneqry = "SELECT id FROM wba_meta_zone_countries WHERE zone = '" . $zone . "'";
        //         $res = Doo::db()->fetchAll($zoneqry, null, PDO::FETCH_OBJ);
        //         $zoneid = $res[0]->id;
        //         $currency = $data[1]; //currency
        //         //convert prices to app currency
        //         $app_currency = 'SAR';
        //         $exchange_rate = 3.75; //1 Meta Sheet Currency = "Exchange_Rate" App Currency
        //         $marketing = $data[2] == "n/a" ? 0 : floatval($data[2]) * $exchange_rate; //marketing
        //         $utility = $data[3] == "n/a" ? 0 : floatval($data[3]) * $exchange_rate; //utility
        //         $cp_auth = $data[4] == "n/a" ? 0 : floatval($data[4]) * $exchange_rate; //cp_auth
        //         $auth_int = $data[5] == "n/a" ? 0 : floatval($data[5]) * $exchange_rate; //auth_int
        //         $cp_ser = $data[6] == "n/a" ? 0 : floatval($data[6]) * $exchange_rate; //cp_ser

        //         $qry .= "(" . intval($zoneid) . ", '" . $zone . "', " . $marketing . ", " . $utility . ", " . $cp_auth . ", " . $auth_int . ", " . $cp_ser . "),";
        //     }

        //     fclose($handle);
        //     echo $qry;

        //     //echo "CSV file imported successfully.";
        // } else {
        //     echo "Failed to open the CSV file.";
        // }
        // die;


        //one time job - for all the prefixes that have no mccmnc assigned but have brand and operator
        // $qry = "SELECT * FROM `sc_nsn_prefix_list` WHERE mccmnc = 0 ";
        // $results = Doo::db()->fetchAll($qry, null, PDO::FETCH_OBJ);
        // foreach ($results as $dt) {
        //     $brandstr = $dt->brand;
        //     $operatorstr = $dt->operator;
        //     //$opstr = $brandstr == "" ? $operatorstr : $brandstr; // $operatorstr == "" ? $brandstr : $operatorstr;
        //     echo 'Prefix ' . $dt->prefix . ' looking for mccmnc for ' . $brandstr . ' AND ' . $operatorstr . '<br>';
        //     $mcqry = "SELECT mccmnc, brand, operator FROM `sc_mcc_mnc_list` WHERE brand LIKE '%$brandstr%' AND operator LIKE '%$operatorstr%' AND country_code = " . $dt->country_prefix . " LIMIT 1";
        //     //echo $mcqry;
        //     $mccmnc = Doo::db()->fetchRow($mcqry);
        //     if (empty($mccmnc)) {
        //         continue;
        //     }
        //     $mccmnc_i = $mccmnc['mccmnc'];
        //     $upqry = "UPDATE `sc_nsn_prefix_list` SET mccmnc = $mccmnc_i, brand = '" . $mccmnc['brand'] . "', operator = '" . $mccmnc['operator'] . "' WHERE id = " . $dt->id;
        //     Doo::db()->query($upqry);
        //     echo 'updated for prefix ' . $dt->prefix . ' with mccmnc ' . $mccmnc_i . ' for brand ' . $brandstr . ' AND operator ' . $operatorstr . '<br>';
        // }
        // $qry = "SELECT * from sc_nsn_prefix_list WHERE mccmnc <> 0 AND brand = '' AND operator = ''";
        // $results = Doo::db()->fetchAll($qry, null, PDO::FETCH_OBJ);
        // foreach ($results as $dt) {
        //     $mccmnc = $dt->mccmnc;
        //     $qry2 = "SELECT brand, operator FROM sc_mcc_mnc_list WHERE mccmnc = $mccmnc LIMIT 1";
        //     $mccmnc = Doo::db()->fetchRow($qry2);
        //     $upqry = "UPDATE sc_nsn_prefix_list SET brand = '" . $mccmnc['brand'] . "', operator = '" . $mccmnc['operator'] . "' WHERE id = " . $dt->id;
        //     Doo::db()->query($upqry);
        // }
        //die;
        // $operator_name = Doo::db()->getOne('ScMccMncList', array('select' => 'brand, operator', 'where' => "mccmnc = 40401 AND status = 1"));
        // var_dump($operator_name);
        // die;


        // Database credentials (replace with your actual values)
        // $db1_host = "localhost";
        // $db1_user = "root";
        // $db1_password = 'appNewDB!$OK';
        // $db1_name = "new_updated";

        // $db2_host = "localhost";
        // $db2_user = "root";
        // $db2_password = 'appNewDB!$OK';
        // $db2_name = "old_stable";

        // // Function to connect to a MySQL database
        // function connect_db($host, $user, $password, $database)
        // {
        //     try {
        //         $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        //         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //         return $conn;
        //     } catch (PDOException $e) {
        //         die("Connection Error: " . $e->getMessage());
        //     }
        // }

        // // Function to escape table and column names for safe usage in queries
        // function escape_identifier($identifier)
        // {
        //     return "`" . str_replace("`", "``", $identifier) . "`";
        // }

        // // Connect to both databases
        // $db1_conn = connect_db($db1_host, $db1_user, $db1_password, $db1_name);
        // $db2_conn = connect_db($db2_host, $db2_user, $db2_password, $db2_name);

        // // Get all tables from DB1
        // $db1_tables = array();
        // $stmt = $db1_conn->prepare("SHOW TABLES");
        // $stmt->execute();
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $db1_tables[] = $row['Tables_in_' . $db1_name];
        // }

        // // Loop through each table in DB1
        // foreach ($db1_tables as $table) {
        //     // Get the table description for both DB1 and DB2
        //     $db1_stmt = $db1_conn->prepare("DESCRIBE $table");
        //     $db2_stmt = $db2_conn->prepare("DESCRIBE $table");

        //     $db1_stmt->execute();
        //     $db2_stmt->execute();

        //     $db1_columns = array();
        //     $db2_columns = array();

        //     while ($row = $db1_stmt->fetch(PDO::FETCH_ASSOC)) {
        //         $db1_columns[$row['Field']] = $row;
        //     }

        //     while ($row = $db2_stmt->fetch(PDO::FETCH_ASSOC)) {
        //         $db2_columns[$row['Field']] = $row;
        //     }

        //     // Loop through each column in DB1
        //     foreach ($db1_columns as $column_name => $column_info) {
        //         // Check if the column exists in DB2
        //         if (!isset($db2_columns[$column_name])) {
        //             // Add the column to DB2 if it doesn't exist
        //             $add_column_sql = "ALTER TABLE " . escape_identifier($table) . " ADD COLUMN " .
        //                 escape_identifier($column_name) . " " . $column_info['Type'];
        //             if (isset($column_info['Null'])) {
        //                 $add_column_sql .= " " . ($column_info['Null'] == "YES" ? "NULL" : "NOT NULL");
        //             }
        //             if (isset($column_info['Default'])) {
        //                 $add_column_sql .= " DEFAULT '" . $column_info['Default'] . "'";
        //             }
        //             try {
        //                 $db2_conn->exec($add_column_sql);
        //                 echo "Added column $column_name to table $table in DB2." . PHP_EOL;
        //             } catch (PDOException $e) {
        //                 echo "Error adding column $column_name: " . $e->getMessage() . PHP_EOL;
        //             }
        //         } else {
        //             // Check if column definitions differ (type, null, default)
        //             $column_differs = false;
        //             if ($db1_columns[$column_name]['Type'] != $db2_columns[$column_name]['Type']) {
        //                 $column_differs = true;
        //             }
        //         }
        //     }
        // }

        // $db1_conn = null;
        // $db2_conn = null;

        // echo "Database merge completed.";

        // die;

        //credit reset at eom
        //1. load plans and users with plan options
        /*
        $upobj = Doo::loadModel('ScUsersSmsPlans', true);
        $poobj = Doo::loadModel('ScSmsPlanOptions', true);
        
        $uplist = Doo::db()->find($upobj, array('where'=>"subopt_idn <> ''"));

        foreach ($uplist as $up) {
            $userid = $up->user_id;
            $planid = $up->plan_id;
            $subopt = $up->subopt_idn;
            //2. Get the routes and credits for each subplan
            $planopts = Doo::db()->find($pobj, array('select'=>'opt_data', 'limit' => 1, 'where'=>"plan_id = $planid AND subopt_idn = '$subopt'"));
            $planopts = unserialize($planopts->opt_data);
            foreach ($planopts['route_credits'] as $routeid => $credits) {
                //3. For each user edit the credits and get old credits
                if(intval($routeid) > 0){
                    //fetch users old credits
                    $fqry = "SELECT id, credits FROM sc_users_credit_data WHERE user_id = $userid AND route_id = $routeid LIMIT 1";
                    $ucdata = Doo::db()->fetchRow($fqry);
                    $oldCredits = $ucdata['credits'];
                    $ucrowid = $ucdata['id'];
                    //update new credits
                    $uqry = "UPDATE sc_users_credit_data SET credits = $credits WHERE id = $ucrowid LIMIT 1";
                    Doo::db()->query($uqry);
                    echo "Updated credits for user $userid with new: $credits and old: $oldCredits for route $routeid<br>";
                    //4. Add log entry with comments "CREDIT RESET" & "Credits added as subscribed. Old credits were expired."
                    $clobj = Doo::loadModel('ScLogsCredits', true);
                    $clobj->user_id = $userid;
                    $clobj->amount = $credits;
                    $clobj->route_id = $routeid;
                    $clobj->credits_before = $oldCredits;
                    $clobj->credits_after = $credits;
                    $clobj->reference = "CREDIT RESET";
                    $clobj->comments = "Credits added as subscribed. Old credits were expired.";
                    Doo::db()->insert($clobj);
                    echo "log entry made.. <br>";
                }
                
            }
        }

       */



        //check how many prefixes have incorrect coverage : later after app is done

        // }
        // include './protected/plugin/lang/fr.lang.php';
        // $en = array();
        // foreach ($lang as $eng => $it) {
        //     array_push($en, $eng);
        //     //echo htmlentities($eng);echo '<br>';
        // }
        // //die;
        // $file_h = fopen('./ar.txt', "r");
        // $i = 0;
        // $str = '';
        // while (!feof($file_h)) {
        //     $fr = trim(fgets($file_h));
        //     if ($fr != "") {
        //         $evar = $en[$i];
        //         if (strpos($evar, "'") == false) {
        //             echo htmlentities('$lang[\'' . $en[$i] . '\'] = "' . str_replace('"', '\"', ucfirst($fr)) . '";');
        //             echo '<br>';
        //         } else {
        //             echo htmlentities('$lang["' . $en[$i] . '"] = "' . str_replace('"', '\"', ucfirst($fr)) . '";');
        //             echo '<br>';
        //         }

        //         $i++;
        //     }
        // }
        // die;

        // }// $alldesh = json_decode(file_get_contents("countries-extended.json"));
        // usort($alldesh, function ($a, $b) {
        //     return strcmp($a->name->common, $b->name->common);
        // });
        // $str = "INSERT IGNORE INTO sc_coverage_old (`country_code`,`country`,`prefix`,`status`) VALUES ";
        // foreach ($alldesh as $desh) {
        //     //get country code prefix for this from db
        //     $iso = $desh->cca2;
        //     $name = $desh->name->common;
        //     $prefix = intval($desh->idd->root . (is_array($desh->idd->suffixes) ? implode($desh->idd->suffixes) : $desh->idd->suffixes));

        //     // //get other data from old coverage
        //     // $q = "SELECT valid_lengths, allowed_first_digits, timezone, regulations FROM sc_coverage_old WHERE country_code = '$iso' LIMIT 1";
        //     // $d = Doo::db()->fetchRow($q);

        //     $str .= "(";
        //     $str .= "'$iso',";
        //     $str .= "'" . addslashes($name) . "',";
        //     $str .= "$prefix,";
        //     // $str .= "'" . $d['valid_lengths'] . "',";
        //     // $str .= "'" . $d['allowed_first_digits'] . "',";
        //     // $str .= "'" . $d['timezone'] . "',";
        //     // $str .= "'" . $d['regulations'] . "',";
        //     $str .= "1),";
        // }
        // echo $str;
        // die;
        // //complete the list of countries from mccmnc list
        // $qry = "SELECT country_name, country_iso, count(id) as total  FROM `sc_mcc_mnc_list` WHERE `country_code` = 0 GROUP BY country_name";
        // $rows = Doo::db()->fetchAll($qry);
        // $alldesh = json_decode(file_get_contents("countries-extended.json"));
        // // echo '<pre>';
        // // var_dump($alldesh);
        // // die;
        // foreach ($rows as $row) {
        //     $iso = $row['country_iso'];
        //     $cvt = array_filter($alldesh, function ($desh) use ($iso) {
        //         return $desh->cca2 == $iso;
        //     });
        //     $k = key($cvt);
        //     $countrydata = $alldesh[$k];
        //     $prefix = $countrydata->idd->root . implode($countrydata->idd->suffixes);
        //     //$prefix = 0;
        //     $str = "INSERT INTO sc_coverage_old (`country_code`,`country`,`prefix`,`valid_lengths`,`status`) VALUES ('" . $row['country_iso'] . "','" . addslashes($row['country_name']) . "'," . intval($prefix) . ",'9,10,11,12',1); ";
        //     echo $str;
        // }
        // //mix existing prefix from mysql and json prefix from files

        // $allcvqry = "SELECT id, country_code, prefix FROM sc_coverage_old WHERE id > 0 AND prefix > 0 ORDER BY country";
        // $allcv = Doo::db()->fetchAll($allcvqry);
        // //build a combined array in form of index = country prefix, value = array of all NSN prefix with mccmnc and brand data
        // $final_nsn_data = array();
        // foreach ($allcv as $cv) {
        //     //if ($cv['country_code'] == "GB") {
        //     $qry = "SELECT coverage, prefix as nsn_prefix, mccmnc, operator, circle FROM sc_ocpr_mapping  WHERE coverage = " . $cv['id'];
        //     $dbprefixes = Doo::db()->fetchAll($qry);
        //     // echo '<pre>';
        //     // var_dump($dbprefixes);
        //     // die;
        //     $country = $cv["country_code"];
        //     $country_prefix = $cv["prefix"];
        //     $allmccmnc = json_decode(file_get_contents("mcc-mnc-list.json"));

        //     $merged_nsn = array();
        //     $fixed_db_nsn = array();
        //     $fixed_file_nsn = array();
        //     if (is_array($dbprefixes) && sizeof($dbprefixes) > 0) {
        //         foreach ($dbprefixes as $dbprefix) {
        //             //if ($dbprefix["country_code"] == "IN") {
        //             $final_prefix = new stdClass;
        //             $nsn_prefix = $country_prefix . $dbprefix["nsn_prefix"]; //cuz in db country prefix is not added in nsn prefix but in files its added

        //             $finalmccmnc = 0;
        //             $finalbrand = '';
        //             $finaloperator = '';
        //             //echo $finalmccmnc . " - 1<br>";
        //             //check this prefix in the files
        //             $file = 'nnpdb/intermediate/' . $country_prefix . '.json';

        //             $filedata = json_decode(file_get_contents($file));
        //             if ($filedata != null) {
        //                 //check if this dbprefix exist in the file
        //                 $file_prefix_data = array_filter($filedata, function ($fileprefix) use ($nsn_prefix) {
        //                     return $fileprefix->prefix == $nsn_prefix;
        //                 });
        //             } else {
        //                 $file_prefix_data = array();
        //             }

        //             if (!empty($file_prefix_data)) {
        //                 //data found in file
        //                 $k = key($file_prefix_data);
        //                 $file_matched = $filedata[$k];
        //                 //if db data is empty and file data is not, then use file values as final values
        //                 if ($dbprefix['mccmnc'] == 0 && $dbprefix['operator'] == "") {
        //                     $finalmccmnc = $file_matched->mccmnc;
        //                     $finalbrand = $file_matched->brand;
        //                     $finaloperator = $file_matched->operator;
        //                 }
        //                 //echo $finalmccmnc . " - 2<br>";
        //                 //if no mccmnc but operator data is present in db, then match the best possible mccmnc
        //                 if ($dbprefix['mccmnc'] == 0 && $dbprefix['operator'] != "") {
        //                     //one loop for all possible operators
        //                     foreach ($allmccmnc as $mccmnc) {

        //                         if ($mccmnc->countryCode == $country && ($this->partialTextMatchScore($dbprefix['operator'], $mccmnc->brand) > 80 || $this->partialTextMatchScore(strtolower($dbprefix['circle']), strtolower($mccmnc->operator)) > 70 || $this->partialTextMatchScore(strtolower($dbprefix['operator']), strtolower($mccmnc->notes)) > 30)) {
        //                             //matched mccmnc
        //                             $finalmccmnc = $mccmnc->mcc . $mccmnc->mnc;
        //                             $finalbrand = addslashes($mccmnc->brand);
        //                             $finaloperator = addslashes($mccmnc->operator);
        //                             //$str .= ", brand: '" . $mccmnc->brand . "', mccmnc: " . $mccmnc->mcc . $mccmnc->mnc;
        //                             break;
        //                         }
        //                     }
        //                     //echo $finalmccmnc . " - 3<br>";
        //                     //another loop for only operational ones
        //                     foreach ($allmccmnc as $mccmnc) {
        //                         if ($mccmnc->countryCode == $country && $mccmnc->status == "Operational" && ($this->partialTextMatchScore($dbprefix['operator'], $mccmnc->brand) > 80 || $this->partialTextMatchScore(strtolower($dbprefix['circle']), strtolower($mccmnc->operator)) > 70 || $this->partialTextMatchScore(strtolower($dbprefix['operator']), strtolower($mccmnc->notes)) > 30)) {
        //                             //matched mccmnc
        //                             $finalmccmnc = $mccmnc->mcc . $mccmnc->mnc;
        //                             $finalbrand = addslashes($mccmnc->brand);
        //                             $finaloperator = addslashes($mccmnc->operator);
        //                             //$str .= ", brand: '" . $mccmnc->brand . "', mccmnc: " . $mccmnc->mcc . $mccmnc->mnc;
        //                             break;
        //                         }
        //                     }
        //                     //echo $finalmccmnc . " - 4<br>";
        //                 }
        //                 //if everything is present mccmnc and operator circle, then check info in mccmnc file and update with latest operational mccmnc and brand name
        //                 if ($dbprefix['mccmnc'] != 0 && $dbprefix['operator'] != "") {
        //                     //first match operator with brand name
        //                     foreach ($allmccmnc as $mccmnc) {
        //                         if ($mccmnc->countryCode == $country && $mccmnc->status == "Operational" && ($this->partialTextMatchScore($dbprefix['operator'], $mccmnc->brand) > 80 || $this->partialTextMatchScore(strtolower($dbprefix['operator']), strtolower($mccmnc->notes)) > 30)) {
        //                             $finalmccmnc = $mccmnc->mcc . $mccmnc->mnc;
        //                             $finalbrand = addslashes($mccmnc->brand);
        //                             $finaloperator = addslashes($mccmnc->operator);
        //                             //$str .= ", brand: '" . $mccmnc->brand . "', mccmnc: " . $mccmnc->mcc . $mccmnc->mnc;
        //                             // echo $mccmnc->brand . $mccmnc->operator . '<br>';
        //                             break;
        //                         }
        //                     }
        //                     foreach ($allmccmnc as $mccmnc) {
        //                         if ($mccmnc->countryCode == $country && $mccmnc->status == "Operational" && $mccmnc->brand == $finalbrand && ($this->partialTextMatchScore($dbprefix['circle'], $mccmnc->operator) > 70 || $this->partialTextMatchScore(strtolower($dbprefix['circle']), strtolower($mccmnc->notes)) > 30)) {
        //                             $finalmccmnc = $mccmnc->mcc . $mccmnc->mnc;
        //                             $finalbrand = addslashes($mccmnc->brand);
        //                             $finaloperator = addslashes($mccmnc->operator);
        //                             //$str .= ", brand: '" . $mccmnc->brand . "', mccmnc: " . $mccmnc->mcc . $mccmnc->mnc;
        //                             //echo $mccmnc->brand . $mccmnc->operator . '<br>';
        //                             break;
        //                         }
        //                     }
        //                     //then match circle with operator
        //                     //echo $finalmccmnc . " - 5<br>";
        //                 }
        //             } else {
        //                 //new prefix, not present in file but present in DB
        //                 $finalmccmnc = $dbprefix['mccmnc'];
        //                 $finalbrand = addslashes($dbprefix['operator']);
        //                 $finaloperator = addslashes($dbprefix['circle']);
        //             }
        //             //echo $finalmccmnc . " - 6<br>";
        //             $final_prefix->prefix = $nsn_prefix;
        //             $final_prefix->mccmnc = $finalmccmnc;
        //             $final_prefix->country_iso = $country;
        //             $final_prefix->country_code = $country_prefix;
        //             $final_prefix->brand = $finalbrand;
        //             $final_prefix->operator = $finaloperator;

        //             array_push($fixed_db_nsn, $final_prefix);
        //             // echo '<pre>';
        //             // var_dump(json_encode($file_prefix_data));
        //             // echo $nsn_prefix;
        //             // die;
        //             //}
        //         }
        //     }
        //     //even if no prefix in DB get from file, even if there is prefix in db merge it from file

        //     $file = 'nnpdb/intermediate/' . $country_prefix . '.json';

        //     $filedata = json_decode(file_get_contents($file));

        //     if ($filedata != null) {
        //         foreach ($filedata as $fl) {
        //             $final_prefix = new stdClass;
        //             $final_prefix->prefix = $fl->prefix;
        //             $final_prefix->mccmnc = $fl->mccmnc;
        //             $final_prefix->country_iso = $country;
        //             $final_prefix->country_code = $country_prefix;
        //             $final_prefix->brand = $fl->brand;
        //             $final_prefix->operator = $fl->operator;

        //             array_push($fixed_file_nsn, $final_prefix);
        //         }
        //     }
        //     //merge the two
        //     $merged_nsn = array_merge($fixed_db_nsn, $fixed_file_nsn);
        //     // echo '<pre>';
        //     // var_dump($merged_nsn);
        //     // die;
        //     //$merged_nsn = json_decode(json_encode($merged_nsn), true);
        //     $final_nsn_data[$country_prefix] = array_unique($merged_nsn, SORT_REGULAR);
        //     //}
        // }
        // // echo '<pre>';
        // // var_dump($final_nsn_data);
        // // die;

        //now we have a mix of file and DB prefix data, before we create files we need to perform 2 tasks
        //1. save in db so it can be further santized. for example US, Trinidad and many other territories use same country prefix and since libphonenumber metadata has files based on main prefix, for the territories prefixes, the countries in the DB is wrong. So we will fix it in below step
        //2. For every NSN prefix in DB (since it contains country prefix also) try to match it with prefixes in sc_coverage table so get the correct country. Change the country in NSN prefix database.
        //3. Create JSON files for every country prefix according to sc_coverage country prefix

        //1. Change unique index to regular index in nsn table to import this
        // foreach ($final_nsn_data as $cvpfx => $nsn_array) {
        //     if (is_array($nsn_array) && sizeof($nsn_array) > 0) {
        //         $str = "INSERT INTO sc_nsn_prefix_list (`prefix`,`mccmnc`,`country_iso`,`country_prefix`,`brand`,`operator`) VALUES ";
        //         $vals = array();
        //         foreach ($nsn_array as $nsn) {
        //             $v = "(";
        //             $v .= $nsn->prefix . ",";
        //             $v .= $nsn->mccmnc . ",";
        //             $v .= "'" . $nsn->country_iso . "',";
        //             $v .= $nsn->country_code . ",";
        //             $v .= "'" . addslashes($nsn->brand) . "',";
        //             $v .= "'" . addslashes($nsn->operator) . "')";
        //             array_push($vals, $v);
        //         }
        //         echo $str . implode(",", $vals) . ";";
        //     }
        // }
        //2. Try to make these unique if possible
        // $duplicate_pre_qry = "SELECT prefix , count(id) as total FROM sc_nsn_prefix_list GROUP BY prefix HAVING total > 1";
        // $dup_prefixes = Doo::db()->fetchAll($duplicate_pre_qry, null, PDO::FETCH_KEY_PAIR);
        // foreach ($dup_prefixes as $prefix => $num) {
        //     //get all entries of this prefix
        //     $q = "SELECT id, mccmnc, brand, operator FROM sc_nsn_prefix_list WHERE prefix = " . $prefix;
        //     $data = Doo::db()->fetchAll($q);
        //     $better = array();
        //     for ($i = $num; $i > 0; $i--) {
        //         $prefixdata = $data[$num];
        //         if ($prefixdata['brand'] != "" && $prefixdata['operator'] != "" && $prefixdata['mccmnc'] != 0) {
        //             $better = $prefixdata;
        //         }
        //     }
        //     if (empty($better)) {
        //         $better = $data[0];
        //     }
        //     //delete all other duplicates 
        //     $del = "DELETE FROM sc_nsn_prefix_list WHERE prefix = $prefix AND id <> " . $better['id'] . "; ";
        //     echo $del;
        //     // echo '<pre>';
        //     // var_dump($better);
        //     // die;
        // }


        // foreach ($final_nsn_data as $country_prefix_f => $prefix_array) {
        //     $handle = @fopen("nnpdb/" . intval($country_prefix_f) . ".json", 'w') or die('Cannot open file for MCCMNC metadata write:  <br>Check file path and permissions.');
        //     @fwrite($handle, json_encode($prefix_array));
        //     fclose($handle);
        // }

        // echo '<pre>';
        // var_dump($final_nsn_data);
        //echo $nsn_prefix;
        //die;

        // //prepare json mccmnc to prefix data
        // //1. get all country codes
        // $qry = "SELECT prefix,country_code FROM sc_coverage_old WHERE id > 0";
        // $countries = Doo::db()->fetchAll($qry, null, PDO::FETCH_KEY_PAIR); // json_decode(file_get_contents("countries.json"), true); // Doo::db()->fetchAll($qry, null, PDO::FETCH_KEY_PAIR);
        // //$countries = array_column($cv, 'dial_code');
        // // echo '<pre>';
        // // var_dump($countries);
        // // die;
        // // $allfiles = scandir("nnpdb/metadata/");
        // // foreach ($allfiles as $file) {
        // //     if ($file != "." && $file != "..") {
        // //         $ar = explode(".", $file);
        // //         // if (array_search("+" . $ar[0], $countries) == false) {
        // //         //     echo $file . '<br>';
        // //         // }
        // //         if (!$countries[$ar[0]]) {
        // //             echo $file . '<br>';
        // //         }
        // //     }
        // // }
        // $allmccmnc = json_decode(file_get_contents("mcc-mnc-list.json"));
        // foreach ($countries as $prefix => $iso) {
        //     $file = "nnpdb/metadata/" . intval($prefix) . ".txt";
        //     $nsnp = file_get_contents($file);
        //     $prefixObj = array();

        //     if ($nsnp !== false) {
        //         $handle = @fopen("nnpdb/intermediate/" . intval($prefix) . ".json", 'w') or die('Cannot open file for MCCMNC metadata write:  <br>Check file path and permissions.');

        //         //we have a file
        //         $lines = explode("\n", $nsnp);

        //         foreach ($lines as $line) {
        //             $prefix_complete = new stdClass;
        //             if (trim($line) != "" && substr($line, 0, 1) != "#") {
        //                 //got a line with prefix
        //                 $parts = explode("|", $line); //0 = prefix, 1 = brand
        //                 $prefix_complete->prefix = $parts[0];
        //                 //$str .= 'prefix: ' . $parts[0];
        //                 //get mccmnc based on partially matching the brand
        //                 foreach ($allmccmnc as $mccmnc) {
        //                     if ($mccmnc->countryCode == $iso && ($this->partialTextMatchScore($parts[1], $mccmnc->brand) > 80 || $this->partialTextMatchScore(strtolower($parts[1]), strtolower($mccmnc->notes)) > 30)) {
        //                         //matched mccmnc
        //                         $prefix_complete->mccmnc = $mccmnc->mcc . $mccmnc->mnc;
        //                         $prefix_complete->brand = addslashes($mccmnc->brand);
        //                         $prefix_complete->operator = addslashes($mccmnc->operator);
        //                         $prefix_complete->notes = addslashes($mccmnc->notes);
        //                         //$str .= ", brand: '" . $mccmnc->brand . "', mccmnc: " . $mccmnc->mcc . $mccmnc->mnc;
        //                         break;
        //                     }
        //                 }
        //                 if (!isset($prefix_complete->mccmnc)) $prefix_complete->mccmnc = 0;
        //                 if (!isset($prefix_complete->brand)) $prefix_complete->brand = addslashes($parts[1]);
        //                 array_push($prefixObj, $prefix_complete);
        //                 //$str .= "}";
        //                 //echo '{prefix: ' . $parts[0] . ", brand: '" . $parts[1] . "'}, <br>";

        //             }
        //         }
        //         @fwrite($handle, json_encode($prefixObj));
        //         fclose($handle);

        //         //var_dump(json_encode($prefixObj));
        //     }
        //     //echo $str;
        //     //die;
        // }
        // die;

        // //import JSON MCCMNC to DB
        // $statuscodes = array(
        //     0 => "Unknown",
        //     1 => "Operational",
        //     2 => "Implement / Design",
        //     3 => "Not operational",
        //     4 => "Ongoing",
        //     5 => "Planned",
        //     6 => "Reserved",
        //     7 => "Returned spare",
        //     8 => "Temporary operational",
        //     9 => "Test Network",
        //     10 => "Testing",
        //     11 => "Allocated",
        //     12 => "Upcoming"
        // );

        // $filedata = json_decode(file_get_contents("mcc-mnc-list.json"));
        // $str = "INSERT INTO sc_mcc_mnc_list (`nw_type`,`country_name`,`country_iso`,`country_code`,`mcc`,`mnc`,`mccmnc`,`brand`,`operator`,`bands`,`notes`,`status`) VALUES ";

        // foreach ($filedata as $dt) {
        //     if (strlen($dt->countryCode) > 2) {
        //         $ciso = substr($dt->countryCode, 0, 2);
        //     } else {
        //         $ciso = $dt->countryCode;
        //     }
        //     $status = array_search($dt->status, $statuscodes);

        //     $qry = "SELECT prefix FROM sc_coverage WHERE country_code = '" . $ciso . "' LIMIT 1";
        //     $cvdt = Doo::db()->fetchRow($qry, null, PDO::FETCH_OBJ);

        //     $str .= "('" . $dt->type . "',";
        //     $str .= "'" . addslashes($dt->countryName) . "',";
        //     $str .= "'" . $ciso . "',";
        //     $str .= intval($cvdt->prefix) . ",";
        //     $str .= intval($dt->mcc) . ",";
        //     $str .= intval($dt->mnc) . ",";
        //     $str .= intval($dt->mcc . $dt->mnc) . ",";
        //     $str .= "'" . addslashes($dt->brand) . "',";
        //     $str .= "'" . addslashes($dt->operator) . "',";
        //     $str .= "'" . addslashes($dt->bands) . "',";
        //     $str .= "'" . addslashes($dt->notes) . "',";
        //     $str .= $status . "),";
        //     $str .= "<br>";
        // }
        // echo $str;

        // $mccobj = Doo::loadModel('ScMccMncList', true);
        // $mccdata = Doo::db()->find($mccobj, array('select' => 'DISTINCT(mcc) as mcc, country_prefix, country_code', 'where' => "country_code <> ''"));
        // $str = 'INSERT INTO sc_mcc_mnc_list(`country_code`,`country_prefix`,`mcc`,`mnc`,`mccmnc`,`network`,`region`) VALUES ';
        // foreach ($mccdata as $dt) {
        //     $rand = random_int(111, 999);
        //     $str .= "(";
        //     $str .= "'" . $dt->country_code . "',";
        //     $str .= "'" . $dt->country_prefix . "',";
        //     $str .= "'" . $dt->mcc . "',";
        //     $str .= "0,";
        //     $str .= "'" . $dt->mcc . "000" . $rand . "',";
        //     $str .= "'DEFAULT',";
        //     $str .= "'DEFAULT'";
        //     $str .= "),";
        // }
        // echo $str;
        // $ocpobj = Doo::loadModel('ScOcprMapping', true);
        // Doo::loadModel('ScMccMncList');
        // $ocpdata = Doo::db()->find($ocpobj);
        // foreach($ocpdata as $ocp){
        //     $id = $ocp->id;
        //     $mccmnc = $ocp->mccmnc;
        //     if(intval($mccmnc)!=0){
        //         $mccmncobj = new ScMccMncList;
        //         $mccmncobj->mccmnc = $mccmnc;
        //         $mdata = Doo::db()->find($mccmncobj, array('select'=>'network, region', 'limit'=>1));
        //         $op = $mdata->network;
        //         $cr = $mdata->region;
        //         $q = "update sc_ocpr_mapping SET operator='$op', circle='$cr' WHERE id=$id LIMIT 1";
        //         Doo::db()->query($q);

        //     }
        //     echo 'done<br>';
        // fclose($file_h);
        //echo($str);
        // for($i=0; $i< 5; $i++){
        //     $obj = Doo::loadModel('ScApiKeys', true);
        //     $obj->user_id = 333;
        //     $obj->api_key = 'THISISTEST';
        //     Doo::db()->insert($obj);

        //     sleep(5);
        // }

    }
}
