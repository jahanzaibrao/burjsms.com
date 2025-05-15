<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
            <nav class="searchbar navbar fixed-top navbar-expand-lg navbar-light bg-white m-b-0">
                <a class="navbar-brand text-dark" href="javascript:void(0);"><i class="fas fa-search fa-lg m-r-xs"></i>Search SMS Data</a>

                <div class="navbar-nav clearfix col-md-8 p-t-sm">
                    <!-- User selector -->
                    <!-- Date selector -->
                    <div class="nav-item col-md-7 p-v-xs">
                        <div class="input-group ">
                            <span class="input-group-addon bg-info "><i class="fas fa-calendar-alt text-white fa-lg"></i></span>
                            <input class="form-control fz-sm" id="datetime" type="text" name="datetime" placeholder="select date and time range...." />
                        </div>

                    </div>
                    <!-- Mobile selector -->
                    <div class="nav-item col-md-5 p-v-xs">
                        <div class="input-group ">
                            <span class="input-group-addon bg-success "><i class="fas fa-phone text-white fa-lg"></i></span>
                            <input class="form-control fz-sm" id="msisdn" type="text" placeholder="enter mobile..." />
                        </div>
                    </div>
                </div>

                <div class="btn-group m-t-sm" role="group">
                    <button class="btn btn-primary" id="filter_search">Filter Results</button>
                    <div class="dropdown btn-group"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><i class="fas fa-download text-white"></i></button>
                        <ul class="dropdown-menu dropdown-menu-btn pull-right">
                            <li><a href="javascript:void(0);" id="export_search">Export to CSV</a></li>
                        </ul>
                    </div>
                </div>

            </nav>

            <div class="darksearchbar fixed-top bg-dark text-light p-t-sm p-v-sm p-b-0 col-md-12">
                <div class="col-md-3">
                    <ul class="list-group bg-dark m-b-0">
                        <?php if ($_SESSION['user']['account_type'] == 1) { ?>
                            <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                                <span class="darksearchbar-param-title">Channel:</span>
                                <div class="btn-group" style="vertical-align: top;">
                                    <button id="s_channel_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                    </button>
                                    <ul id="s_channel_dropdown" class="dropdown-menu search-options-bar">
                                        <li><a class="search_option_selector chosen" data-inputid="s_channel" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                        <li><a class="search_option_selector" data-inputid="s_channel" data-myvalue="APP" href="javascript:void(0);">Panel</a></li>
                                        <li><a class="search_option_selector" data-inputid="s_channel" data-myvalue="API" href="javascript:void(0);">API</a></li>
                                        <li><a class="search_option_selector" data-inputid="s_channel" data-myvalue="SMPP" href="javascript:void(0);">SMPP</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" id="s_channel" value="">
                                <input type="hidden" id="s_route" value="">
                            </li>
                        <?php } else { ?>
                            <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                                <span class="darksearchbar-param-title">Route:</span>
                                <div class="btn-group" style="vertical-align: top;">
                                    <button id="s_route_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                    </button>
                                    <ul id="s_route_dropdown" class="dropdown-menu search-options-bar">
                                        <li><a class="search_option_selector chosen" data-inputid="s_route" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                        <?php foreach ($_SESSION['credits']['routes'] as $rt) { ?>
                                            <li><a class="search_option_selector routeslist" data-inputid="s_route" data-myvalue="<?php echo $rt['id'] ?>" href="javascript:void(0);"><?php echo $rt['name'] ?></a> </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <input type="hidden" id="s_route" value="">
                                <input type="hidden" id="s_smpp" value="">
                            </li>
                        <?php } ?>


                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">smpp-client:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_smppclient_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_smppclient_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_smppclient" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <?php foreach ($data['smppclients'] as $scl) { ?>
                                        <li><a class="search_option_selector" data-inputid="s_smppclient" data-myvalue="<?php echo $scl->system_id ?>" href="javascript:void(0);"><?php echo $scl->system_id ?></a> </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <input type="hidden" id="s_smppclient" value="">
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">Country:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_country_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_country_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_country" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <?php foreach ($data['countries'] as $ctr) { ?>
                                        <li><a class="search_option_selector" data-inputid="s_country" data-myvalue="<?php echo $ctr->country_code ?>" href="javascript:void(0);"><?php echo $ctr->country . ' <span class="fz-sm m-l-xs code"> ' . $ctr->prefix . '</span>' ?></a> </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <input type="hidden" id="s_country" value="">
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">Operator:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_operator_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_operator_dropdown" class="dropdown-menu has-form search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_operator" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li class="col-md-11 p-t-sm">
                                        <div class="input-group">
                                            <input type="text" id="s_operator" class="form-control input-sm p-r-md" placeholder="brand name or MCCMNC.." value="">
                                            <span class="input-group-btn z-3">
                                                <a class="search_option_input btn btn-sm btn-primary" data-inputid="s_operator" href="javascript:void(0);">Apply</a>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-group bg-dark m-b-0">
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">SMS ID:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_smsid_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_smsid_dropdown" class="dropdown-menu has-form search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_smsid" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li class="col-md-11 p-t-sm">
                                        <div class="input-group">
                                            <input type="text" id="s_smsid" class="form-control input-sm p-r-md" placeholder="enter SMS ID.." value="">
                                            <span class="input-group-btn z-3">
                                                <a class="search_option_input btn btn-sm btn-primary" data-inputid="s_smsid" href="javascript:void(0);">Apply</a>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">Sender ID:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_senderid_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_senderid_dropdown" class="dropdown-menu has-form search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_senderid" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li class="col-md-11 p-t-sm">
                                        <div class="input-group">
                                            <input type="text" id="s_senderid" class="form-control input-sm p-r-md" placeholder="enter Sender ID.." value="">
                                            <span class="input-group-btn z-3">
                                                <a class="search_option_input btn btn-sm btn-primary" data-inputid="s_senderid" href="javascript:void(0);">Apply</a>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">SMS Type:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_smstype_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_smstype_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_smstype" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_smstype" data-myvalue="text" href="javascript:void(0);">Text</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_smstype" data-myvalue="unicode" href="javascript:void(0);">Unicode</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smstype" data-myvalue="flash" href="javascript:void(0);">Flash</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smstype" data-myvalue="unicodeflash" href="javascript:void(0);">Unicode with Flash</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smstype" data-myvalue="wap" href="javascript:void(0);">WAP</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smstype" data-myvalue="vcard" href="javascript:void(0);">vCard</a></li>
                                </ul>
                            </div>
                            <input type="hidden" id="s_smstype" value="">
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">SMS Parts:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_smsparts_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_smsparts_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_smsparts" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_smsparts" data-myvalue="1" href="javascript:void(0);">One</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_smsparts" data-myvalue="2" href="javascript:void(0);">Two</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smsparts" data-myvalue="3" href="javascript:void(0);">Three</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smsparts" data-myvalue="4" href="javascript:void(0);">Four</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smsparts" data-myvalue="5" href="javascript:void(0);">Five</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smsparts" data-myvalue="100" href="javascript:void(0);">Greater than Five</a></li>
                                </ul>
                            </div>
                            <input type="hidden" id="s_smsparts" value="">
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-group bg-dark m-b-0">
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">SMPP DLR:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_smppdlr_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_smppdlr_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_smppdlr" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="DELIVRD" href="javascript:void(0);">DELIVRD</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="EXPIRED" href="javascript:void(0);">EXPIRED</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="UNDELIV" href="javascript:void(0);">UNDELIV</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="DELETED" href="javascript:void(0);">DELETED</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="ACCEPTD" href="javascript:void(0);">ACCEPTD</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="UNKNOWN" href="javascript:void(0);">UNKNOWN</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_smppdlr" data-myvalue="REJECTD" href="javascript:void(0);">REJECTD</a></li>
                                </ul>
                            </div>
                            <input type="hidden" id="s_smppdlr" value="">
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">App DLR:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_appdlr_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_appdlr_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_appdlr" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_appdlr" data-myvalue="1" href="javascript:void(0);">Delivered</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_appdlr" data-myvalue="2" href="javascript:void(0);">Failed</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_appdlr" data-myvalue="8" href="javascript:void(0);">SMSC Submitted</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_appdlr" data-myvalue="16" href="javascript:void(0);">Rejected</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_appdlr" data-myvalue="-1" href="javascript:void(0);">Invalid</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_appdlr" data-myvalue="0" href="javascript:void(0);">Pending DLR</a></li>
                                </ul>
                            </div>
                            <input type="hidden" id="s_appdlr" value="">
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">Operator DLR:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_vendordlr_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_vendordlr_dropdown" class="dropdown-menu has-form search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_vendordlr" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li class="col-md-11 p-t-sm">
                                        <div class="input-group">
                                            <input type="text" id="s_vendordlr" class="form-control input-sm p-r-md" placeholder="enter DLR code e.g. 0x00045" value="">
                                            <span class="input-group-btn z-3">
                                                <a class="search_option_input btn btn-sm btn-primary" data-inputid="s_vendordlr" href="javascript:void(0);">Apply</a>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title">Refunded:</span>
                            <div class="btn-group" style="vertical-align: top;">
                                <button id="s_refund_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                </button>
                                <ul id="s_refund_dropdown" class="dropdown-menu search-options-bar">
                                    <li><a class="search_option_selector chosen" data-inputid="s_refund" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                    <li><a class="search_option_selector" data-inputid="s_refund" data-myvalue="1" href="javascript:void(0);">Yes</a></li>
                                    <li><a class="search_option_selector" data-inputid="s_refund" data-myvalue="0" href="javascript:void(0);">No</a></li>
                                </ul>
                            </div>
                            <input type="hidden" id="s_refund" value="">
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-group bg-dark m-b-0">
                        <?php if ($_SESSION['user']['account_type'] != 1) { ?>
                            <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                                <span class="darksearchbar-param-title">Channel:</span>
                                <div class="btn-group" style="vertical-align: top;">
                                    <button id="s_channel_selection" class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Any <i class="m-l-sm fas fa-caret-down fa-lg"></i>
                                    </button>
                                    <ul id="s_channel_dropdown" class="dropdown-menu search-options-bar">
                                        <li><a class="search_option_selector chosen" data-inputid="s_channel" data-myvalue="" href="javascript:void(0);">Any</a> </li>
                                        <li><a class="search_option_selector" data-inputid="s_channel" data-myvalue="APP" href="javascript:void(0);">Panel</a></li>
                                        <li><a class="search_option_selector" data-inputid="s_channel" data-myvalue="API" href="javascript:void(0);">API</a></li>
                                        <li><a class="search_option_selector" data-inputid="s_channel" data-myvalue="SMPP" href="javascript:void(0);">SMPP</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" id="s_channel" value="">
                            </li>
                        <?php } ?>
                        <li class="bg-dark m-b-xs" style="vertical-align: middle;">
                            <span class="darksearchbar-param-title m-b-sm">SMS Text:</span>
                            <div class="form-group">
                                <textarea style="<?php if ($_SESSION['user']['account_type'] != 1) { ?>min-height: 50px; height: 50px;<?php } else { ?>min-height: 65px; height: 65px;<?php } ?>" class="form-control" id="s_smstext" placeholder="enter pharses or text to match..."></textarea>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="searchstatsbar fixed-top bg-white p-sm col-md-12">
                <div class="col-md-2 text-center">
                    <h5 class="m-b-0">
                        Total:
                        <span id="sr_total" class="m-l-sm counter fz-bold text-primary">0</span>
                    </h5>
                    <div class="fz-sm text-primary text-center">messages sent</div>
                </div>
                <div class="col-md-2 text-center">
                    <h5 class="m-b-0">
                        Average:
                        <span id="sr_average" class="m-l-sm counter fz-bold text-primary" data-plugin="counterUp">0</span>
                    </h5>
                    <div class="fz-sm text-primary text-center">sms per day</div>
                </div>
                <div class="col-md-2 text-center">
                    <h5 class="m-b-0">
                        Delivered:
                        <span class="m-l-sm counter fz-bold text-primary"><span id="sr_del" data-plugin="counterUp">0</span></span>
                    </h5>
                    <div class="fz-sm text-primary text-center"><span id="sr_del_per" class="text-success">0%</span> success rate</div>
                </div>
                <div class="col-md-2 text-center">
                    <h5 class="m-b-0">
                        Failed:
                        <span class="m-l-sm counter fz-bold text-primary"><span id="sr_fail" data-plugin="counterUp">0</span></span>
                    </h5>
                    <div class="fz-sm text-primary text-center"><span id="sr_fail_per" class="text-danger">0%</span> failure rate</div>
                </div>
                <div class="col-md-2 text-center">
                    <h5 class="m-b-0">
                        Cost:
                        <span class="m-l-sm counter fz-bold text-danger"><?php echo trim(Doo::conf()->currency) ?><span id="sr_cost" data-plugin="counterUp">0.00</span>
                        </span>
                    </h5>
                    <div id="sr_credits" class="fz-sm text-primary text-center">0 credits</div>
                </div>
                <div class="col-md-2 text-center">
                    <h5 class="m-b-0">
                        Refunds:
                        <span class="m-l-sm counter fz-bold text-success"><?php echo trim(Doo::conf()->currency) ?><span id="sr_refund_cost" data-plugin="counterUp">0.00</span>
                        </span>
                    </h5>
                    <div id="sr_refund_credits" class="fz-sm text-primary text-center">0 credits</div>
                </div>

            </div>

            <div class="col-md-12 p-sm bg-white">
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list m-r-sm"></i> <?php echo SCTEXT('SMS Records') ?></a></li>
                        <li><a href="#tab2" data-toggle="tab"><i class="fa fa-download m-r-sm"></i><?php echo SCTEXT('Downloads') ?></a></li>
                    </ul>
                    <div class="tab-content p-v-lg">
                        <div class="tab-pane active fade in p-t-md" id="tab1">
                            <div style="overflow: auto; max-width: 100%; max-height: 500px; padding-bottom:40px;">
                                <table id="smsdata" class="wd100 table-compact table-responsive table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Mobile</th>
                                            <th>Type</th>
                                            <th>Sender</th>
                                            <th>Client</th>
                                            <th>Route</th>
                                            <th>Channel</th>
                                            <th>Country</th>
                                            <th>Operator</th>
                                            <th>Parts</th>
                                            <th>Cost</th>
                                            <th>DLR</th>
                                            <th>DLR-time</th>
                                            <th>State</th>
                                            <th>VendorDLR</th>
                                            <th>SMSID</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="18" class="text-center">Loading Data ....</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade p-t-md" id="tab2">
                            <div class="alert alert-custom alert-info">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <i class="fa fa-lg fa-info-circle"></i> <?php echo SCTEXT('Download requests older than 30 days will be deleted') ?>
                            </div>
                            <table id="dt_dljobs" data-plugin="DataTable" data-options="{language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], responsive: {breakpoints: [
                            { name: 'desktop', width: Infinity },
                            { name: 'tablet',  width: 1024 },
                            { name: 'fablet',  width: 768 },
                            { name: 'phone',   width: 480 }]}}" class="wd100 table row-border order-column">
                                <thead>
                                    <tr>
                                        <th data-priority="1"><?php echo SCTEXT('File Name') ?></th>
                                        <th><?php echo SCTEXT('Request Date') ?></th>
                                        <th><?php echo SCTEXT('Status') ?></th>
                                        <th data-priority="2"><?php echo SCTEXT('Download') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['dljobs'] as $dlj) { ?>
                                        <tr>
                                            <td><?php echo $dlj->file_name ?></td>
                                            <td><?php echo date(Doo::conf()->date_format_med_time, strtotime($dlj->added_on)) ?></td>
                                            <td><?php
                                                echo $dlj->status == 0 ? '<span class="label label-warning">In Process...</span>' : '<span class="label label-success">Ready for download</span>';
                                                ?></td>
                                            <td>
                                                <?php if ($dlj->status == 1) { ?>
                                                    <a href="<?php echo Doo::conf()->APP_URL . 'exports/' . $dlj->file_name ?>" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i></a>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>