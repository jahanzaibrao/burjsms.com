<nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-header" id="custom-sidebar-header">


        <button type="button" id="menubar-toggle-btn" class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger"><span class="sr-only">Toggle navigation</span> <span class="hamburger-box"><span class="hamburger-inner"></span></span>
        </button>
        <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false"><span class="sr-only">Toggle navigation</span> <span class="zmdi zmdi-hc-lg zmdi-more"></span></button>



        <div class="app-user whitetxt" style="margin-top:5%;">
            <div class="media">
                <div class="media-left">
                    <div id="custom-sidebar-avatar" class="avatar avatar-md avatar-circle">
                        <a href="javascript:void(0)"><img class="img-responsive" src="<?php echo $_SESSION['user']['avatar'] == '' ? Doo::conf()->APP_URL . 'global/skin/assets/images/male.png' : $_SESSION['user']['avatar']; ?>" alt="avatar"></a>
                    </div>
                </div>
                <div class="media-body">
                    <div class="foldable">
                        <h5><a href="javascript:void(0)" class="username"><?php echo $_SESSION['user']['name'] ?></a></h5>
                        <ul>
                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><small><?php echo ucfirst($_SESSION['user']['subgroup']) . ' Account' ?></small> <span class="caret"></span></a>
                                <ul class="dropdown-menu animated flipInY">
                                    <li><a class="text-color" href="<?php echo Doo::conf()->APP_URL ?>editUserProfile"><span class="m-r-xs"><i class="fa fa-user"></i></span> <span><?php echo SCTEXT('Profile') ?></span></a></li>
                                    <li><a class="text-color" href="<?php echo Doo::conf()->APP_URL ?>userSettings"><span class="m-r-xs"><i class="fa fa-gear"></i></span> <span><?php echo SCTEXT('Settings') ?></span></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a class="text-color" href="<?php echo Doo::conf()->APP_URL ?>logout"><span class="m-r-xs"><i class="fa fa-power-off"></i></span> <span><?php echo SCTEXT('Logout') ?></span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="navbar-container container-fluid topbar" style="">
        <div class="collapse navbar-collapse" id="app-navbar-collapse" style="">
            <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">

                <li>
                    <h5 class="page-title dashboard-link hidden-menubar-top hidden-float"><i class="fa fa-building fa-inverse fa-large"></i>&nbsp; <?php echo $_SESSION['webfront']['company_name'] != '' ? $_SESSION['webfront']['company_name'] : Doo::conf()->global_page_title; ?></h5>
                </li>
            </ul>
            <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">
                <li class="dropdown" style="padding-top:10px;">
                    <select class="" style="background-color: transparent !important;" data-plugin="select2" id="applang" data-options="{minimumResultsForSearch: -1, templateResult: function (data){ if(data.id) return $('<div style=\'\'><img width=\'30\' src=\''+app_url+'global/img/flags/'+data.text+'.png\' /></div>');},templateSelection: function (data){  if(data.id) return $('<div style=\'margin-top: -10%;\'><img width=\'30\' src=\''+app_url+'global/img/flags/'+data.text+'.png\' /></div> ');} }">
                        <option <?php if ($_SESSION["APP_LANG"] == 'en') { ?> selected <?php } ?> title="English" value="en">en</option>
                        <?php /*<option <?php if ($_SESSION["APP_LANG"] == 'he') { ?> selected <?php } ?> title="Hebrew" value="he">il</option> */ ?>
                        <option <?php if ($_SESSION["APP_LANG"] == 'th') { ?> selected <?php } ?> title="Thai" value="th">th</option>
                        <option <?php if ($_SESSION["APP_LANG"] == 'nl') { ?> selected <?php } ?> title="Dutch" value="nl">nl</option>
                        <option <?php if ($_SESSION["APP_LANG"] == 'it') { ?> selected <?php } ?> title="Italian" value="it">it</option>
                        <option <?php if ($_SESSION["APP_LANG"] == 'fr') { ?> selected <?php } ?> title="French" value="fr">fr</option>
                        <option <?php if ($_SESSION["APP_LANG"] == 'ar') { ?> selected <?php } ?> title="Arabic" value="ar">sa</option>
                    </select>
                </li>

                <li class="dropdown">
                    <a title="Monitors" href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-desktop-mac"></i> &nbsp;<span class="hidden-xs"><?php echo SCTEXT('Monitors') ?></span>
                    </a>

                    <div class="media-group dropdown-menu animated flipInY">
                        <ul class="app-menu">
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['kannel']))) { ?>
                                <li <?php echo $data['current_page'] == 'kannel_mon' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>kannelMonitor"><i class="menu-icon fas fa-tv "></i> <span class="menu-text"><?php echo SCTEXT('Kannel Monitor') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['sysmon']))) { ?>
                                <li <?php echo $data['current_page'] == 'system_mon' ? 'class="active"' : ''; ?>><a href="<?php echo Doo::conf()->APP_URL ?>systemMonitor"><i class="menu-icon fas fa-terminal "></i> <span class="menu-text"><?php echo SCTEXT('System Monitor') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['smppmon']))) { ?>
                                <li class=" <?php if ($data['page'] == 'SMPP Server') { ?>active <?php } ?>"><a href="<?php echo Doo::conf()->APP_URL ?>smppServerMonitor" class=""><i class="menu-icon fas fa-server "></i> <span class="menu-text"><?php echo SCTEXT('SMPP Server Monitor') ?></span></a></li>
                            <?php } ?>
                            <?php if ($_SESSION['user']['subgroup'] == 'admin' || ($_SESSION['user']['subgroup'] == 'staff' && isset($_SESSION['permissions']['admin']['powergrid']))) { ?>
                                <li class="<?php if ($data['page'] == 'Power Grid') { ?>active <?php } ?>"><a href="<?php echo Doo::conf()->APP_URL ?>powerGrid"><i class="menu-icon zmdi zmdi-power-setting zmdi-hc-lg"></i> <span class="menu-text"><?php echo SCTEXT('Power Grid') ?></span></a></li>
                            <?php } ?>
                        </ul>

                    </div>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-lg fa-code-branch fa-rotate-270"></i> &nbsp;<?php echo SCTEXT('Routes') ?>
                    </a>

                    <div class="row dropdown-menu animated flipInY">
                        <div class="widget m-b-0">
                            <header class="widget-header">
                                <h4 class="widget-title"><?php echo SCTEXT('Available Routes') ?>
                                    <div id="rlcrehdr" class="tsub_sml pull-right" style="background: #fff; cursor: pointer; padding: 2px 4px; border: 1px solid #ccc;">
                                        <i class="fa fa-repeat"></i>
                                    </div>
                                </h4>
                            </header>
                            <hr class="widget-separator">

                            <div class="p-t-xs widget-body">
                                <?php foreach ($_SESSION['credits']['routes'] as $rt) { ?>
                                    <div class="">
                                        <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                            <span class="label label-info"><?php echo $rt['name'] ?></span>
                                        </div>

                                        <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                            <h5 class="m-t-xs text-dark"><?php echo number_format($rt['credits']) ?></h5>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-notifications mynotif"></i>
                        <i id="notifcnt" class="mynotifball label label-danger"><?php echo $_SESSION['alerts']['count'] <= '0' ? '' : $_SESSION['alerts']['count']; ?></i> <?php echo SCTEXT('Alerts') ?>
                    </a>

                    <div class="media-group dropdown-menu animated flipInY">
                        <div id="notifctr">
                            <?php echo $_SESSION['alerts']['content'] ?>
                        </div>

                        <hr class="m-h-xs">
                        <a href="<?php echo Doo::conf()->APP_URL ?>viewNotifications" class="btn btn-info block"><?php echo SCTEXT('View All Alerts') ?></a>
                    </div>
                </li>

                <li class="dropdown"><a title="Logout" href="<?php echo Doo::conf()->APP_URL ?>logout" role="button"><i class="zmdi zmdi-hc-lg zmdi-lock"></i> &nbsp;<span class="hidden-xs"><?php echo SCTEXT('Logout') ?></span></a></li>

            </ul>

            <!-- dynamic app-user -->

            <div class="app-user">
                <div class="media">
                    <div class="media-left">
                        <div class="avatar avatar-md avatar-circle">
                            <a href="javascript:void(0)"><img class="img-responsive" src="<?php echo $_SESSION['user']['avatar'] == '' ? Doo::conf()->APP_URL . 'global/skin/assets/images/male.png' : $_SESSION['user']['avatar']; ?>" alt="avatar"></a>
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="foldable">
                            <h5><a href="javascript:void(0)" class="username"><?php echo $_SESSION['user']['name'] ?></a></h5>
                            <ul>
                                <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><small><?php echo ucfirst($_SESSION['user']['group']) . ' Account' ?></small> <span class="caret"></span></a>
                                    <ul class="dropdown-menu animated flipInY">
                                        <li><a class="text-color" href="profile.html"><span class="m-r-xs"><i class="fa fa-user"></i></span> <span>Profile</span></a></li>
                                        <li><a class="text-color" href="settings.html"><span class="m-r-xs"><i class="fa fa-gear"></i></span> <span>Settings</span></a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a class="text-color" href="<?php echo Doo::conf()->APP_URL ?>logout"><span class="m-r-xs"><i class="fa fa-power-off"></i></span> <span>Logout</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end dynamic app-user -->
        </div>
    </div>
</nav>
<style>
    #applang+span.select2.select2-container.select2-container--default span.selection span.select2-selection.select2-selection--single {
        background-color: transparent !important;
        border: none;
    }

    #applang+span.select2.select2-container.select2-container--default span.selection span.select2-selection span b {
        border-color: #fff transparent transparent transparent !important;
    }

    .menu-text {
        max-width: 150px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>