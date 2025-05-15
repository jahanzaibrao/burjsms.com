<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('View Document') ?><small><?php echo SCTEXT('share, comment or download document') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <!-- start content -->
                            <div class="col-md-12 clearfix m-b-md">
                                <div class="col-md-6 media">
                                    <div class="media-left">
                                        <?php
                                        $payment_info = unserialize($data['reseller_cdata']->c_payment);
                                        $mime = $data['docdata']->file_data;
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
                                        ?>
                                        <i class="fa fa-5x <?php echo $ftype ?>"></i>
                                    </div>
                                    <div class="media-body p-l-md">
                                        <span class="label label-info"><?php echo SCTEXT('Name') ?></span> <?php echo $data['docdata']->filename ?><br>
                                        <span class="label label-info"><?php echo SCTEXT('Type') ?></span> <?php
                                                                                                            switch ($data['docdata']->type) {
                                                                                                                case 3:
                                                                                                                    echo SCTEXT('Normal Document');
                                                                                                                    break;
                                                                                                                case 2:
                                                                                                                    echo SCTEXT('Agreement');
                                                                                                                    echo $data['docdata']->file_status == 0 ? '<span class="label label-warning m-l-sm"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . SCTEXT('Pending') . '</span>' : ($data['docdata']->file_status == 1 ? '<span class="label label-success m-l-sm"><i class="fa fa-lg fa-check-circle m-r-sx"></i> ' . SCTEXT('Approved') . '</span>' : '<span class="label label-danger m-l-sm"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . SCTEXT('Declined') . '</span>');
                                                                                                                    break;
                                                                                                                case 1:
                                                                                                                    echo SCTEXT('Invoice');
                                                                                                                    echo $data['docdata']->file_status == 0 ? '<span class="label label-warning m-l-sm"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . SCTEXT('Due') . '</span>' : ($data['docdata']->file_status == 1 ? '<span class="label label-success m-l-sm"><i class="fa fa-lg fa-check-circle m-r-sx"></i> ' . SCTEXT('Paid') . '</span>' : ($data['docdata']->file_status == 2 ? '<span class="label label-danger m-l-sm"><i class="fa fa-lg fa-exclamation-circle m-r-sx"></i> ' . SCTEXT('Overdue') . '</span>' : '<span class="label label-info m-l-sm"><i class="fa fa-lg fa-info-circle m-r-sx"></i> ' . SCTEXT('Cancelled') . '</span>'));
                                                                                                            }
                                                                                                            ?><br>
                                        <span class="label label-info"><?php echo SCTEXT('Uploaded On') ?>:</span> <?php echo date(Doo::conf()->date_format_med_time, strtotime($data['docdata']->created_on)) ?><br>
                                    </div>

                                </div>
                                <?php if ($data['docdata']->type == 1) { ?>
                                    <div class="col-md-6 text-right">
                                        <a class="btn btn-outline btn-primary m-b-xs" href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><i class="fa fa-lg fa-angle-double-left m-r-xs"></i> <?php echo SCTEXT('Back to Documents') ?></a><br>

                                        <?php if ($data['owner'] == 'no') {
                                            if ($data['docdata']->file_status == 0 || $data['docdata']->file_status == 2) {
                                        ?>

                                                <a id="docinvpay" data-docid="<?php echo $data['docdata']->id ?>" class="btn btn-sm btn-primary m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-credit-card m-r-xs"></i> <?php echo SCTEXT('Make Payment') ?></a>

                                            <?php }
                                        } else { ?>
                                            <div class="dropdown btn-group"><button data-toggle="dropdown" class="btn btn-sm btn-primary m-b-xs dropdown-toggle"> <?php echo SCTEXT('Actions') ?> &nbsp; <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                                    <li><a class="invaction" data-action="1" data-docid="<?php echo $data['docdata']->id ?>" href="javascript:void(0);"><?php echo SCTEXT('Mark as <b>PAID</b>') ?></a></li>
                                                    <li><a class="invaction" href="javascript:void(0);" data-action="3" data-docid="<?php echo $data['docdata']->id ?>"><?php echo SCTEXT('Cancel Invoice') ?></a></li>
                                                </ul>
                                            </div>

                                        <?php } ?>

                                        <a id="docinvprint" class="btn btn-sm btn-success m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-download m-r-xs"></i> <?php echo SCTEXT('Print Invoice') ?></a>

                                        <a data-docid="<?php echo $data['docdata']->id ?>" class="<?php if ($data['owner'] == 'no') { ?> disabledBox <?php } ?>deleteDoc btn btn-sm btn-danger m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-trash m-r-xs"></i> <?php echo SCTEXT('Delete File') ?></a>
                                    </div>
                                <?php } elseif ($data['docdata']->type == 2) { ?>

                                    <div class="col-md-6 text-right">
                                        <a class="btn btn-outline btn-primary m-b-xs" href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><i class="fa fa-lg fa-angle-double-left m-r-xs"></i> <?php echo SCTEXT('Back to Documents') ?></a><br>

                                        <?php if ($data['owner'] == 'no') { ?>

                                            <div class="dropdown btn-group"><button data-toggle="dropdown" class="btn btn-sm btn-primary m-b-xs dropdown-toggle"> <?php echo SCTEXT('Actions') ?> &nbsp; <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                                    <li><a class="agraction" data-action="1" data-docid="<?php echo $data['docdata']->id ?>" href="javascript:void(0);"><?php echo SCTEXT('Approve') ?></a></li>
                                                    <li><a class="agraction" href="javascript:void(0);" data-action="2" data-docid="<?php echo $data['docdata']->id ?>"><?php echo SCTEXT('Decline') ?></a></li>
                                                </ul>
                                            </div>

                                        <?php } ?>

                                        <a class="btn btn-sm btn-success m-b-xs" href="<?php echo Doo::conf()->APP_URL . 'globalFileDownload/docmgr/' . $data['docdata']->id ?>"><i class="fa fa-lg fa-download m-r-xs"></i> <?php echo SCTEXT('Download File') ?></a>
                                        <a id="docreupload" data-docid="<?php echo $data['docdata']->id ?>" class="<?php if ($data['owner'] == 'no') { ?> disabledBox <?php } ?>btn btn-sm btn-primary m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-upload m-r-xs"></i> <?php echo SCTEXT('Re-upload File') ?></a>
                                        <a data-docid="<?php echo $data['docdata']->id ?>" class="<?php if ($data['owner'] == 'no') { ?> disabledBox <?php } ?>deleteDoc btn btn-sm btn-danger m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-trash m-r-xs"></i> <?php echo SCTEXT('Delete File') ?></a>
                                    </div>

                                <?php } else { ?>
                                    <div class="col-md-6 text-right">
                                        <a class="btn btn-outline btn-primary m-b-xs" href="<?php echo Doo::conf()->APP_URL ?>manageDocs"><i class="fa fa-lg fa-angle-double-left m-r-xs"></i> <?php echo SCTEXT('Back to Documents') ?></a><br>
                                        <a class="btn btn-sm btn-success m-b-xs" href="<?php echo Doo::conf()->APP_URL . 'globalFileDownload/docmgr/' . $data['docdata']->id ?>"><i class="fa fa-lg fa-download m-r-xs"></i> <?php echo SCTEXT('Download File') ?></a>
                                        <a id="docreupload" data-docid="<?php echo $data['docdata']->id ?>" class="<?php if ($data['owner'] == 'no') { ?> disabledBox <?php } ?>btn btn-sm btn-primary m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-upload m-r-xs"></i> <?php echo SCTEXT('Re-upload File') ?></a>
                                        <a data-docid="<?php echo $data['docdata']->id ?>" class="<?php if ($data['owner'] == 'no') { ?> disabledBox <?php } ?>deleteDoc btn btn-sm btn-danger m-b-xs" href="javascript:void(0);"><i class="fa fa-lg fa-trash m-r-xs"></i> <?php echo SCTEXT('Delete File') ?></a>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-md-12 clearfix">
                                <div class="col-md-8">


                                    <?php if ($data['docdata']->type == 1) {

                                        $filedata = unserialize($data['docdata']->file_data);
                                    ?>



                                        <div id="invoiceBox" class="widget">
                                            <div class="widget-body">
                                                <div class="invoice-box">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tr class="top">
                                                            <td colspan="2">
                                                                <table style="border-bottom: 2px grey solid;">
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $data['reseller_cdata']->c_name ?><br>
                                                                            <?php echo nl2br($data['reseller_cdata']->c_address) ?>

                                                                        </td>
                                                                        <td class="title">
                                                                            <img class="invoice-logo" src="<?php echo Doo::conf()->APP_URL . Doo::conf()->image_upload_url . 'logos/' . $_SESSION['webfront']['logo'] ?>">
                                                                        </td>


                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr class="information">
                                                            <td colspan="2">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $data['user_cdata']->c_name ?><br>
                                                                            <?php echo nl2br($data['user_cdata']->c_address) ?>
                                                                        </td>

                                                                        <td>
                                                                            Invoice #: <?php echo $data['docdata']->id ?><br>
                                                                            Created: <?php echo date(Doo::conf()->date_format_med, strtotime($data['docdata']->created_on)) ?>

                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <?php if (isset($filedata['routes_credits'])) { ?>

                                                            <tr class="heading">
                                                                <td>
                                                                    Route
                                                                </td>

                                                                <td>
                                                                    Credits
                                                                </td>
                                                            </tr>


                                                            <?php foreach ($filedata['routes_credits'] as $id => $rdata) {
                                                                $rid = isset($rdata->id) ? $rdata->id : $id;
                                                            ?>

                                                                <tr class="item">

                                                                    <td>
                                                                        <?php echo $_SESSION['credits']['routes'][$rid]['name'] ?><br>
                                                                        @ <?php echo Doo::conf()->currency . ($rdata->price == '' ? $rdata['price'] : $rdata->price) ?> per SMS
                                                                    </td>

                                                                    <td>
                                                                        <?php echo ($rdata->credits == '' ? $rdata['credits'] : $rdata->credits) ?>
                                                                    </td>

                                                                </tr>

                                                        <?php }
                                                        } ?>

                                                        <?php if (isset($filedata['wallet_credits'])) { ?>

                                                            <tr class="heading">
                                                                <td>
                                                                    Service
                                                                </td>

                                                                <td>
                                                                    Price
                                                                </td>
                                                            </tr>
                                                            <tr class="item">
                                                                <td>
                                                                    Added credits in WALLET
                                                                </td>

                                                                <td>
                                                                    <?php echo Doo::conf()->currency . $filedata['wallet_credits'] ?>
                                                                </td>

                                                            </tr>

                                                        <?php } ?>


                                                        <tr class="item last">
                                                            <td>
                                                                Total <br><?php echo $filedata['plan_tax'] != '' ? '<small class="fz-sm"> ' . $filedata['plan_tax'] . '</small>' : ''; ?>
                                                            </td>

                                                            <td>
                                                                <?php echo Doo::conf()->currency . $filedata['total_cost'] ?>
                                                            </td>
                                                        </tr>
                                                        <tr class="item last">
                                                            <td>
                                                                Additional Taxes
                                                            </td>

                                                            <td>
                                                                <?php echo $filedata['additional_tax'] ?>
                                                            </td>
                                                        </tr>
                                                        <tr class="item last">
                                                            <td>
                                                                Discount
                                                            </td>

                                                            <td>
                                                                <?php echo $filedata['discount'] ?>
                                                            </td>
                                                        </tr>

                                                        <tr class="total">
                                                            <td></td>

                                                            <td>
                                                                Total: <?php echo Doo::conf()->currency . $filedata['grand_total'] ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title"><?php echo SCTEXT('Remarks/Comments') ?></h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body">
                                            <div id="docremk" class="sc_chatctr">

                                                <?php
                                                //if no remarks or comments
                                                if ($data['docdata']->init_remarks == '' && sizeof($data['comments']) == 0) {

                                                ?>

                                                    <div class="sc_chatbox text-center text-white">
                                                        - <?php echo SCTEXT('No Comments Yet') ?> -
                                                    </div>


                                                <?php
                                                }
                                                if ($data['owner'] == 'no') {
                                                    $ownerid = intval($data['docdata']->owner_id);

                                                    $owobj = array_filter($data['udata'], function ($e) use ($ownerid) {
                                                        return $e->user_id == $ownerid;
                                                    });
                                                    $k = key($owobj);
                                                    $ownerav = $data['udata'][$k]->avatar;
                                                    $ownernm = $data['udata'][$k]->name;
                                                    $ownerct = $data['udata'][$k]->category;
                                                    $ownerem = $data['udata'][$k]->email;
                                                    $ownerup = $data['udata'][$k]->upline_id;
                                                } else {
                                                    $ownerav = $_SESSION['user']['avatar'];
                                                    $ownernm = $_SESSION['user']['name'];
                                                    $ownerct = $_SESSION['user']['group'];
                                                    $ownerem = $_SESSION['user']['email'];
                                                    $ownerup = $_SESSION['user']['upline'];
                                                }

                                                //check if initial remarks by uploader present
                                                if ($data['docdata']->init_remarks != '') {

                                                ?>
                                                    <div class="sc_chatbox <?php echo $data['owner'] == 'no' ? 'chat-other' : 'chat-me clearfix'; ?>">
                                                        <div class="sc_chatele">
                                                            <div class="media-left">
                                                                <div class="avatar avatar-lg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $ownerav ?>" alt="Photo"></a></div>
                                                            </div>
                                                            <div class="media-body p-l-md">

                                                                <h5 class="text-dark"><?php echo $ownernm ?></h5>
                                                                <span><?php echo $data['docdata']->init_remarks ?></span>
                                                                <small class="text-dark text-right"><i class="fa fa-clock"></i> <?php echo date(Doo::conf()->date_format_med_time, strtotime($data['docdata']->created_on)) ?> </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                <?php
                                                //all other remarks

                                                //if user is owner of the document then:
                                                //case 1: Client account: User can see communications between self and account mgr. Because if client uploads a doc, the only one he can share this doc with is acc. manager

                                                //case 2: non-client account: User can share the document with any user in their downline. User can see all the comments with identities.

                                                //if user is not the owner of document then:
                                                // Case 3: User can only see comments by owner and himself, other comments will be visible but names will be omitted. User cannot share it with other users if not the owner

                                                if ($data['owner'] == 'yes' && $_SESSION['user']['group'] == 'client') {
                                                    $case = 1;
                                                }
                                                if ($data['owner'] == 'yes' && $_SESSION['user']['group'] != 'client') {
                                                    $case = 2;
                                                }
                                                if ($data['owner'] == 'no') {
                                                    $case = 3;
                                                }

                                                foreach ($data['comments'] as $cmt) {
                                                    $cmuid = $cmt->user_id;
                                                    if ($cmuid == $_SESSION['user']['userid']) {
                                                        $cmuav = $_SESSION['user']['avatar'];
                                                        $cmunm = $_SESSION['user']['name'];
                                                    } else {
                                                        $cmuobj = array_filter($data['udata'], function ($e) use ($cmuid) {
                                                            return $e->user_id == $cmuid;
                                                        });
                                                        $k = key($cmuobj);
                                                        $cmuav = $data['udata'][$k]->avatar;
                                                        $cmunm = $data['udata'][$k]->name;
                                                    }


                                                    if ($case == 1 || $case == 2) {

                                                ?>

                                                        <div class="sc_chatbox <?php echo $cmuid != $_SESSION['user']['userid'] ? 'chat-other' : 'chat-me clearfix'; ?>">
                                                            <div class="sc_chatele">
                                                                <div class="media-left">
                                                                    <div class="avatar avatar-lg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $cmuav ?>" alt="Photo"></a></div>
                                                                </div>
                                                                <div class="media-body p-l-md">

                                                                    <h5 class="text-dark"><?php echo $cmunm ?></h5>
                                                                    <span><?php echo $cmt->remark_text ?></span>
                                                                    <small class="text-dark text-right"><i class="fa fa-clock"></i> <?php echo date(Doo::conf()->date_format_med_time, strtotime($cmt->posted_on)) ?> </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php } elseif ($case == 3) {

                                                    ?>

                                                        <div class="sc_chatbox <?php echo $cmuid != $_SESSION['user']['userid'] ? 'chat-other' : 'chat-me clearfix'; ?>">
                                                            <div class="sc_chatele">
                                                                <div class="media-left">
                                                                    <div class="avatar avatar-lg avatar-circle"><a href="javascript:void(0);">
                                                                            <?php echo $cmuid == $_SESSION['user']['userid'] || $cmuid == $data['docdata']->owner_id ? '<img src="' . $cmuav . '" alt="Photo">' : '<img src="' . Doo::conf()->default_avatar_male_user . '" class="disabledBox" alt="Photo">' ?>
                                                                        </a></div>
                                                                </div>
                                                                <div class="media-body p-l-md">

                                                                    <h5><?php echo $cmuid == $_SESSION['user']['userid'] || $cmuid == $data['docdata']->owner_id ? $cmunm : '- Private User -' ?></h5>
                                                                    <span><?php echo $cmt->remark_text ?></span>
                                                                    <small class="text-dark text-right"><i class="fa fa-clock"></i> <?php echo date(Doo::conf()->date_format_med_time, strtotime($cmt->posted_on)) ?> </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                <?php }
                                                } ?>



                                            </div>
                                            <div class="text-right m-t-sm">
                                                <form method="post" id="cmt_form">
                                                    <input type="hidden" name="docid" value="<?php echo $data['docdata']->id ?>" /> <textarea id="doc_comment" name="doc_comment" class="form-control" placeholder="<?php echo SCTEXT('enter your comment') ?> ..."></textarea>
                                                    <a id="submitCmt" class="m-t-xs btn btn-primary btn-sm"><?php echo SCTEXT('Submit') ?></a>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title"><?php echo SCTEXT('Shared with') ?></h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body">
                                            <?php
                                            //show owner of doc

                                            //view account link only active if admin/reseller is viewing account of their downline
                                            //find out if doc is shared by user from the downline or from the higher reseller/admin

                                            if ($_SESSION['user']['userid'] == $ownerup) {
                                                $downuser = 1; //owner of doc is from the downline
                                            } else {
                                                $downuser = 0; //owner is either peer or is the admin/staff
                                            }


                                            if ($data['owner'] == 'yes') {
                                                $viewacclink = 'javascript:void(0);';
                                            } else {
                                                if ($_SESSION['user']['group'] != 'client') {
                                                    if ($_SESSION['user']['group'] == 'admin') {
                                                        $viewacclink = Doo::conf()->APP_URL . 'viewUserAccount/' . $data['docdata']->owner_id;
                                                    } else {
                                                        if ($_SESSION['user']['userid'] == $ownerup) {
                                                            $viewacclink = Doo::conf()->APP_URL . 'viewUserAccount/' . $data['docdata']->owner_id;
                                                        } else {
                                                            $viewacclink = 'javascript:void(0);';
                                                        }
                                                    }
                                                } else {
                                                    $viewacclink = 'javascript:void(0);';
                                                }
                                            }
                                            ?>
                                            <div class="media-group-item">

                                                <div class="media">
                                                    <div class="media-left">
                                                        <div class="avatar avatar-md avatar-circle"><a href="<?php echo $viewacclink;  ?>"><img src="<?php echo $ownerav; ?>" alt="Owner"></a></div>
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="m-t-0 m-b-0"><a href="<?php echo $viewacclink; ?>" class="m-r-xs theme-color"><?php echo $ownernm ?></a><small class="text-muted fz-sm"><?php echo $data['owner'] == 'yes' || $downuser == 1 ? $ownerct : 'Manager'; ?></small></h5>
                                                        <p class="m-b-0" style="font-size: 12px;font-style: Italic;"><?php echo $ownerem ?></p>
                                                        <span class="m-b-sm label label-info label-sm"><?php echo SCTEXT('Document Owner') ?></span>
                                                    </div>
                                                </div>

                                            </div>


                                            <?php
                                            //all the users associated with this document

                                            if ($data['owner'] == 'no') {
                                                //if the user is not the owner, show his profile first then show all other associated users
                                            ?>
                                                <div class="media-group-item">

                                                    <div class="media">
                                                        <div class="media-left">
                                                            <div class="avatar avatar-sm avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $_SESSION['user']['avatar'] ?>" alt="Self"></a></div>
                                                        </div>
                                                        <div class="media-body">
                                                            <h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs theme-color"><?php echo $_SESSION['user']['name'] ?></a><small class="text-muted fz-sm"><?php echo $_SESSION['user']['group'] ?></small></h5>
                                                            <p style="font-size: 12px;font-style: Italic;"><?php echo $_SESSION['user']['email'] ?></p>
                                                        </div>
                                                    </div>

                                                </div>




                                            <?php } ?>
                                            <div style="max-height:350px;overflow-y:auto;">

                                                <?php
                                                //all other users associated except owner and logged in user

                                                //if case 1: show all users none of the links should be there for view account
                                                //if case 2: show all users and all users will have active view acc link except if the shared_with user is not in direct downline
                                                //if case 3: user will only see his name as share_with user and number saying e.g. and with 8 others. No name/profile pic etc will be displayed. Except for admin. Admin will see all users with links.

                                                $totalskipped = 0;   // no of users to hide for privacy

                                                if (sizeof($data['udata']) > 0) {
                                                    foreach ($data['udata'] as $sharedusr) {

                                                        if ($sharedusr->user_id != $data['docdata']->owner_id && intval($sharedusr->user_id) != intval($_SESSION['user']['userid'])) {
                                                            $skipflag = 0;
                                                            if ($case == 1) {
                                                                $valink = 'javascript:void(0)';
                                                            } elseif ($case == 2 && $sharedusr->upline_id != $_SESSION['user']['userid']) {
                                                                //unclickable if not admin
                                                                if ($_SESSION['user']['group'] != 'admin') {
                                                                    $valink = 'javascript:void(0)';
                                                                } else {
                                                                    $valink = Doo::conf()->APP_URL . 'viewUserAccount/' . $sharedusr->user_id;
                                                                }
                                                            } elseif ($case == 2 && $sharedusr->upline_id == $_SESSION['user']['userid']) {
                                                                //usr in direct downline
                                                                $valink = Doo::conf()->APP_URL . 'viewUserAccount/' . $sharedusr->user_id;
                                                            } elseif ($case == 3) {
                                                                //if client, skip this
                                                                if ($_SESSION['user']['group'] == 'client') {
                                                                    $skipflag = 1;
                                                                    $totalskipped++;
                                                                } elseif ($_SESSION['user']['group'] == 'reseller') {
                                                                    //if reseller: downline user clickable and viewable else skip
                                                                    if ($sharedusr->upline_id != $_SESSION['user']['userid']) {
                                                                        $skipflag = 1;
                                                                        $totalskipped++;
                                                                    } else {
                                                                        $valink = Doo::conf()->APP_URL . 'viewUserAccount/' . $sharedusr->user_id;
                                                                    }
                                                                } elseif ($_SESSION['user']['group'] == 'admin') {
                                                                    //if admin/staff: all user clickable
                                                                    $valink = Doo::conf()->APP_URL . 'viewUserAccount/' . $sharedusr->user_id;
                                                                }
                                                            }

                                                            if ($skipflag == 0) {

                                                ?>

                                                                <div class="media-group-item">
                                                                    <?php if ($data['owner'] == 'yes') { ?>
                                                                        <span data-docid="<?php echo $data['docdata']->id ?>" data-uid="<?php echo $sharedusr->user_id ?>" class="pointer remshare" title="<?php echo SCTEXT('Undo Sharing') ?>">
                                                                            <i class="fa fa-lg fa-times-circle text-danger"></i>
                                                                        </span>
                                                                    <?php } ?>
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <div class="avatar avatar-sm avatar-circle"><a href="<?php echo $valink ?>"><img src="<?php echo $sharedusr->avatar ?>" alt="Pic"></a></div>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <h5 class="m-t-0 m-b-0"><a href="<?php echo $valink ?>" class="m-r-xs theme-color"><?php echo $sharedusr->name ?></a><small class="text-muted fz-sm"><?php echo $sharedusr->category != 'admin' ? $sharedusr->category : 'Manager'; ?></small></h5>
                                                                            <p style="font-size: 12px;font-style: Italic;"><?php echo $sharedusr->email ?></p>
                                                                        </div>
                                                                    </div>

                                                                </div>


                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div> <!-- end scrollable box -->
                                            <?php

                                            if (intval($totalskipped) > 0) {
                                            ?>

                                                <div class="media-group-item text-right">
                                                    . . . and <?php echo number_format(intval($totalskipped)) ?> other user(s).
                                                </div>

                                            <?php
                                            }
                                            if ($data['owner'] == 'yes') {
                                                //only doc owner can share
                                            ?>

                                                <hr>
                                                <div class="text-right">
                                                    <a id="sharedoc" class="btn btn-info btn-sm"><i class="fa fa-lg fa-share-alt m-r-xs"></i> <?php echo SCTEXT('Share') ?></a>
                                                </div>

                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- various boxes -->
                            <?php if ($data['owner'] == 'yes') {
                                $shusr = explode(",", $data['docdata']->shared_with);
                            ?>
                                <!-- share box -->

                                <form id="shareform" method="post" action="">
                                    <input type="hidden" name="sdocid" value="<?php echo $data['docdata']->id ?>" />
                                    <div class="modal fade" id="shareusrbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><?php echo SCTEXT('Share Document') ?></h4>
                                                </div>
                                                <div id="uboxbody" class="modal-body p-lg">
                                                    <select multiple id="sharedusr" name="sharedusr[]" class="form-control wd100" data-plugin="select2" data-options="{placeholder: '<?php echo SCTEXT('select users to share the document') ?> ...', templateResult: function (data){ if(data.element){var lstr = data.element.label; var myarr = lstr.split('|'); var nstr = '<div class=\'media m-t-xs\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+data.title+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic;\'>'+myarr[1]+'</p></div></div>';}else{var nstr='';} return $(nstr);}, templateSelection: function (data){var lstr=data.element.label;var myarr = lstr.split('|'); var nstr = '<div class=\'media\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+data.title+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic;\'>'+myarr[1]+'</p></div></div>'; return $(nstr);} }">
                                                        <option <?php if (in_array($_SESSION['manager']['id'], $shusr)) { ?> selected <?php } ?> value="<?php echo $_SESSION['manager']['id']; ?>" label="<?php echo $_SESSION['manager']['avatar'] . '|' . $_SESSION['manager']['email'] . '|Manager' ?>" title="<?php echo $_SESSION['manager']['name'] ?>"><?php echo $_SESSION['manager']['name'] ?></option>

                                                        <?php foreach ($data['shareusers'] as $usr) {

                                                        ?>
                                                            <option <?php if (in_array($usr->user_id, $shusr)) { ?> selected <?php } ?> value="<?php echo $usr->user_id ?>" title="<?php echo $usr->name ?>" label="<?php echo $usr->avatar . '|' . $usr->email . '|' . $usr->category ?>"><?php echo $usr->name ?></option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                                <div class="modal-footer">
                                                    <button id="submitShare" type="button" class="btn btn-primary"><i class="fa fa-lg fa-check-circle m-r-xs"></i> <?php echo SCTEXT('Save Changes') ?></button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-lg fa-times-circle m-r-xs"></i> <?php echo SCTEXT('Cancel') ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <!-- re-upload box -->

                                <form id="reuploadform" method="post" data-plugin="dropzone" data-options="{ url: '<?php echo Doo::conf()->APP_URL ?>globalFileUpload', previewsContainer:'.dropzone', maxFiles:1, acceptedFiles:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/x-csv,application/vnd.ms-excel,text/plain,text/csv,application/x-compressed,application/x-zip-compressed,application/zip,image/png,image/jpeg,application/pdf,.xls', addRemoveLinks:true, params:{mode:'docmgr'}, success: function(file,res){createInputFile('reuploadform',res); $('#uprocess').val('0'); $('.dz-message').addClass('hidden');}, processing: function(file){$('#uprocess').val('1');}, canceled: function(file){$('#uprocess').val('0');}, removedfile: function(file){if(file.xhr) deleteInputFile(file.xhr.response,'docmgr');$('.dz-message').removeClass('hidden');var _ref; return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;}}">
                                    <input type="hidden" id="uprocess" value="0" />
                                    <input type="hidden" name="rdocid" value="<?php echo $data['docdata']->id ?>" />
                                    <div class="modal fade" id="reupbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><?php echo SCTEXT('Re-Upload Document') ?></h4>
                                                </div>
                                                <div class="modal-body p-lg">
                                                    <div class="dropzone text-center">
                                                        <div class="dz-message">
                                                            <h3 class="m-h-lg"><?php echo SCTEXT('Drop files here or click to upload.') ?></h3>
                                                            <p class="m-b-lg">( Upload Excel, Text, PDF, Image or Zip files only )</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button id="submitReup" type="button" class="btn btn-primary"><i class="fa fa-lg fa-check-circle m-r-xs"></i> <?php echo SCTEXT('Save Changes') ?></button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-lg fa-times-circle m-r-xs"></i> <?php echo SCTEXT('Cancel') ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                            <?php } ?>

                            <?php
                            $wallet = $_SESSION['credits']['wallet']['amount'];
                            ?>
                            <div id="paymentopts" class="hidden">
                                <div class="form-horizontal">
                                    <input type="hidden" id="gtotal" value="<?php echo floatval($filedata['grand_total']); ?>" />
                                    <input type="hidden" id="walbal" value="<?php echo $wallet; ?>" />
                                    <div class="form-group m-b-xs">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total') ?>:</label>
                                        <div class="col-md-8">
                                            <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="grand_total_amt"><?php echo $filedata['grand_total'] ?></span> <small id="all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes') ?>)</small></h3>
                                        </div>
                                    </div>

                                    <?php if ($_SESSION['user']['account_type'] == 0) { ?>
                                        <div class="form-group m-b-xs">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Wallet credits') ?>:</label>
                                            <div class="col-md-8">
                                                <div class="checkbox checkbox-primary">
                                                    <input name="useWallet" type="checkbox" id="wbalflag" checked>
                                                    <label style="cursor:text;"><?php echo SCTEXT('Use your') ?> <b><?php echo Doo::conf()->currency . number_format($wallet, 2) ?></b> <?php echo SCTEXT('wallet balance') ?> </label>
                                                </div>
                                                <span class="help-block"><?php echo SCTEXT('Remaining balance after this payment') ?> <span id="remwal"><?php echo Doo::conf()->currency;
                                                                                                                                                        echo $filedata['grand_total'] >= $wallet ? '0' : number_format(($wallet - $filedata['grand_total']), 2); ?></span></span>
                                            </div>
                                        </div>
                                    <?php } else { ?>

                                    <?php } ?>

                                    <div class="form-group m-b-xs">
                                        <label class="control-label col-md-3"><?php echo SCTEXT('Total Payable') ?>:</label>
                                        <div class="col-md-8">
                                            <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?>
                                                <span id="total_amt_payable">
                                                    <?php
                                                    if ($_SESSION['user']['account_type'] == 0) {
                                                        echo $wallet >= $filedata['grand_total'] ? '0.00' : number_format(($filedata['grand_total'] - $wallet), 2);
                                                    } else {
                                                        echo number_format($filedata['grand_total'], 2);
                                                    }
                                                    ?>
                                                </span> <small id="all_taxes_payable" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes') ?>)</small>
                                            </h3>
                                        </div>
                                    </div>



                                </div>
                            </div>


                            <!-- end content -->

                        </div>
                    </div>
                </div>
            </div>

        </section>