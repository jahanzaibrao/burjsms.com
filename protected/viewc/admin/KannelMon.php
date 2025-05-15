<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Kannel Monitor') ?><small><?php echo SCTEXT('view and manage all SMSC connections') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <?php
                                //include("xmlfunc.php");
                                //include("xmltoarray.php");

                                $depth = array();
                                $status = array();
                                $configs = $data['kannel_config'];

                                ?>
                                <div class="kmon-topbar">
                                    <div class="col-md-6">
                                        <h4><?php echo SCTEXT('Monitoring 1 configured Instance') ?></h4>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="dropdown btn-group">
                                            <button title="<?php echo SCTEXT('Reload Page') ?>" class="btn btn-info reloadPg"><i class="fa fa-large fa-refresh"></i></button>
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-large fa-navicon"></i> &nbsp; <?php echo SCTEXT('Actions') ?> <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                                <li><a href="javascript:void(0);" class="kmon-action" data-act="editConf"><i class="fa fa-large fa-edit"></i>&nbsp;&nbsp; <?php echo SCTEXT('Edit Conf File') ?> </a></li>
                                                <li><a href="javascript:void(0);" class="kmon-action" data-act="restart"><i class="fa fa-large fa-repeat"></i>&nbsp;&nbsp; <?php echo SCTEXT('Graceful Restart Kannel') ?> </a></li>
                                                <li><a href="javascript:void(0);" class="kmon-action" data-act="flushdlr"><i class="fa fa-large fa-trash"></i>&nbsp;&nbsp; <?php echo SCTEXT('Flush Kannel Dlr') ?> </a></li>
                                                <li><a href="javascript:void(0);" class="kmon-action" data-act="shutdown"><i class="fa fa-large fa-power-off"></i>&nbsp;&nbsp; <?php echo SCTEXT('Shutdown Kannel') ?> </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <hr>
                                <div class="kmon-headerblock">

                                    <table class="wd100 table row-column table-bordered sc_responsive" id="kmonhdr" valign="top">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('Name') ?></th>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th><?php echo SCTEXT('Started') ?></th>
                                                <th><?php echo SCTEXT('Uptime') ?></th>
                                                <th colspan="3" align="center">SMS (MO)</th>
                                                <th colspan="3" align="center">DLR (MO)</th>
                                                <th colspan="3" align="center">SMS (MT)</th>
                                                <th colspan="3" align="center">DLR (MT)</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            /* loop through all configured URLs */

                                            foreach ($configs as $inst => $config) {
                                                //echo "<tr><td class=text valign=top align=left>\n";

                                                /* get the status.xml URL of one config */
                                                $url = $config["base_url"] . "/status.xml?password=" . $config["status_passwd"];

                                                $xml_data = "";

                                                /* open the file description to the URL */
                                                if (($fp = fopen($url, "r"))) {

                                                    $bgcolor = 'green';
                                                    /* read the XML input */
                                                    while (!feof($fp)) {
                                                        $xml_data .= fread($fp, 200000);
                                                    }
                                                    fclose($fp);
                                                    $xml_str = simplexml_load_file($url);
                                                    //$xml_obj = new XmlToArray($xml_data);
                                                    $status[$inst] = unserialize(serialize(json_decode(json_encode((array) $xml_str), 1)));  //cleanup_array($xml_obj->createArray());
                                                    //echo '<kbd>'; var_dump($status[$inst]);echo '</kbd>';die;
                                                    $totalsmscs = is_array($status[$inst]['smscs']) ? sizeof($status[$inst]['smscs']) : 0;
                                                    for ($i = 0; $i < $totalsmscs; $i++) {
                                                        clean_branch($status[$inst]['smscs'][$i], '');
                                                    }
                                                    //echo '<pre>'; var_dump($status[$inst]);die;

                                                    /* get the status of this bearerbox */
                                                    list($st, $started, $uptime) = parse_uptime($status[$inst]['status']);
                                                    /* get the info of this bearerbox into a button, to save some screen real-estate*/
                                                    $ver = preg_replace("/\n+/", '\\n', $status[$inst]['version']);
                                                    $ver = preg_replace("/[\'\`]/", "\'", $ver);
                                                    $ver = 'Url: ' . $config["base_url"] . '\n\n' . $ver;
                                                    $boxstatus = array(
                                                        'name' => '<a class="href" style="color: green; font-weight: bold" href="#" onClick="alert(\'' . $ver . '\'); return false;">' . $config['name'] . '</a>',
                                                        'bgcolor' => 'green',
                                                        'status'  => '<span class="label label-success">' . ucwords($st) . '</span>',
                                                        'started' => $started,
                                                        'uptime'  => $uptime,
                                                    );
                                                } else {
                                                    $boxstatus = array(
                                                        'name'    => $config['name'],
                                                        'bgcolor' => 'red',
                                                        'status'  => '<span class="label label-danger">Stopped</span>',
                                                        'started' => '-',
                                                        'uptime'  => '-',
                                                    );
                                                }
                                            ?>
                                                <tr class="<?php echo $boxstatus['bgcolor'] ?>">
                                                    <td><?php echo $boxstatus['name'] ?></td>
                                                    <td><?php echo $boxstatus['status'] ?></td>
                                                    <td><?php echo $boxstatus['started'] ?></td>
                                                    <td><?php echo $boxstatus['uptime'] ?></td>
                                                    <?php echo split_load($status[$inst]['sms']['inbound']) ?>
                                                    <?php echo split_load($status[$inst]['dlr']['inbound']) ?>
                                                    <?php echo split_load($status[$inst]['sms']['outbound']) ?>
                                                    <?php echo split_load($status[$inst]['dlr']['outbound']) ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                                <br>

                                <div class="kmon-allcons">
                                    <h4><?php echo SCTEXT('Overall SMS traffic') ?></h4>
                                    <hr>
                                    <table class="wd100 table row-column table-bordered sc_responsive" id="kmonallcon" valign="top">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('Instance') ?></th>
                                                <th><?php echo SCTEXT('Received') ?> (MO)</th>
                                                <th><?php echo SCTEXT('Received') ?> (DLR)</th>
                                                <th><?php echo SCTEXT('Inbound') ?> (MO)</th>
                                                <th><?php echo SCTEXT('Inbound') ?> (DLR)</th>
                                                <th><?php echo SCTEXT('Sent') ?> (MT)</th>
                                                <th><?php echo SCTEXT('Sent') ?> (DLR)</th>
                                                <th><?php echo SCTEXT('Outbound') ?> (MT)</th>
                                                <th><?php echo SCTEXT('Outbound') ?> (DLR)</th>
                                                <th><?php echo SCTEXT('Queued') ?> (MO)</th>
                                                <th><?php echo SCTEXT('Queued') ?> (MT)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sums = array(
                                                0, 0, 0.0, 0.0, 0, 0, 0.0, 0.0, 0, 0
                                            );
                                            foreach ($configs as $inst => $config) {
                                                $cols = array(
                                                    get_path($status[$inst], 'sms/received/total'),
                                                    get_path($status[$inst], 'dlr/received/total'),
                                                    get_path($status[$inst], 'sms/inbound'),
                                                    get_path($status[$inst], 'dlr/inbound'),
                                                    get_path($status[$inst], 'sms/sent/total'),
                                                    get_path($status[$inst], 'dlr/sent/total'),
                                                    get_path($status[$inst], 'sms/outbound'),
                                                    get_path($status[$inst], 'dlr/outbound'),
                                                    get_path($status[$inst], 'sms/received/queued'),
                                                    get_path($status[$inst], 'sms/sent/queued'),
                                                );
                                                for ($i = 0; $i < 10; $i++) {
                                                    $sums[$i] += $cols[$i];
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $config['name'] ?></td>
                                                    <td><?php echo nf($cols[0]) ?></td>
                                                    <td><?php echo nf($cols[1]) ?></td>
                                                    <td><?php echo nfd($cols[2]) ?></td>
                                                    <td><?php echo nfd($cols[3]) ?></td>
                                                    <td><?php echo nf($cols[4]) ?></td>
                                                    <td><?php echo nf($cols[5]) ?></td>
                                                    <td><?php echo nfd($cols[6]) ?></td>
                                                    <td><?php echo nfd($cols[7]) ?></td>
                                                    <td><?php echo nf($cols[8]) ?></td>
                                                    <td><?php echo nf($cols[9]) ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr class="sum">
                                                <td><?php echo SCTEXT('Total') ?></td>
                                                <td><?php echo nf($sums[0]) ?></td>
                                                <td><?php echo nf($sums[1]) ?></td>
                                                <td><?php echo nfd($sums[2]) ?></td>
                                                <td><?php echo nfd($sums[3]) ?></td>
                                                <td><?php echo nf($sums[4]) ?></td>
                                                <td><?php echo nf($sums[5]) ?></td>
                                                <td><?php echo nfd($sums[6]) ?></td>
                                                <td><?php echo nfd($sums[7]) ?></td>
                                                <td><?php echo nf($sums[8]) ?></td>
                                                <td><?php echo nf($sums[9]) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <div class="kmon-boxcons">
                                    <h4><?php echo SCTEXT('Box Connections') ?></h4>
                                    <hr>
                                    <table class="wd100 table row-column table-bordered sc_responsive" id="kmonbcons" valign="top">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('Instance') ?></th>
                                                <th><?php echo SCTEXT('Type') ?></th>
                                                <th><?php echo SCTEXT('ID') ?></th>
                                                <th><?php echo SCTEXT('IP') ?></th>
                                                <th><?php echo SCTEXT('Queued') ?> (MO)</th>
                                                <th><?php echo SCTEXT('Started') ?></th>
                                                <th><?php echo SCTEXT('Uptime') ?></th>
                                                <th>SSL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($configs as $inst => $config) {
                                                /* drop an error in case we have no boxes connected */
                                                if (!is_array($status[$inst]['boxes']) || sizeof($status[$inst]['boxes']) < 0) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo $config['name'] ?></td>
                                                        <td colspan="8" class="">
                                                            <span class="label label-danger"><?php echo SCTEXT('No boxes connected to this bearerbox!') ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    $sep = ($inst > 0) ? " class=\"sep\"" : '';
                                                    /* loop the boxes */
                                                    foreach ($status[$inst]['boxes'] as $box) {
                                                        list($st, $started, $uptime) = parse_uptime($box['status']);

                                                    ?>
                                                        <tr<?php echo $sep ?>>
                                                            <td><?php echo $config['name'] ?></td>
                                                            <td><?php echo $box['type'] ?></td>
                                                            <td><?php echo $box['id'] != '' ? $box['id'] : 'N/A'; ?></td>
                                                            <td><?php echo $box['IP'] ?></td>
                                                            <td><?php echo nf($box['queued']) ?> msgs</td>
                                                            <td><?php echo $started ?></td>
                                                            <td><?php echo $uptime ?></td>
                                                            <td><?php echo $box['ssl'] ?></td>
                                                            </tr>
                                                <?php
                                                        $sep = '';
                                                    }
                                                }
                                            }
                                                ?>
                                        </tbody>
                                    </table>
                                </div>


                                <br>
                                <div class="kmon-scon">
                                    <h4><?php echo SCTEXT('SMSC Connections') ?></h4>
                                    <hr>
                                    <table class="wd100 table row-column table-bordered sc_responsive" id="kmonscons" valign="top">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('Instance') ?></th>
                                                <th><?php echo SCTEXT('Links') ?></th>
                                                <th><?php echo SCTEXT('Online') ?></th>
                                                <th><?php echo SCTEXT('Disconnected') ?></th>
                                                <th><?php echo SCTEXT('Connecting') ?></th>
                                                <th><?php echo SCTEXT('Re-Connecting') ?></th>
                                                <th><?php echo SCTEXT('Dead') ?></th>
                                                <th><?php echo SCTEXT('Unknown') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sums = array(
                                                0, 0, 0, 0, 0, 0, 0
                                            );
                                            foreach ($configs as $inst => $config) {
                                                $smsc_status = count_smsc_status($status[$inst]['smscs']);
                                                $cols = array(
                                                    array_sum($smsc_status),
                                                    $smsc_status['online'],
                                                    $smsc_status['disconnected'],
                                                    $smsc_status['connecting'],
                                                    $smsc_status['re-connecting'],
                                                    $smsc_status['dead'],
                                                    $smsc_status['unknown'],
                                                );
                                                for ($i = 0; $i < 7; $i++) {
                                                    $sums[$i] += $cols[$i];
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $config['name'] ?></td>
                                                    <td><?php echo make_link($cols[0], 'total') ?></td>
                                                    <td><?php echo make_link($smsc_status, 'online', 'green') ?></td>
                                                    <td><?php echo make_link($smsc_status, 'disconnected') ?></td>
                                                    <td><?php echo make_link($smsc_status, 'connecting') ?></td>
                                                    <td><?php echo make_link($smsc_status, 're-connecting') ?></td>
                                                    <td><?php echo make_link($smsc_status, 'dead') ?></td>
                                                    <td><?php echo make_link($smsc_status, 'unknown') ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr class="sum">
                                                <td><?php echo SCTEXT('Total') ?></td>
                                                <td><?php echo make_link($sums[0], 'total') ?></td>
                                                <td><?php echo make_link($sums[1], 'total') ?></td>
                                                <td><?php echo make_link($sums[2], 'total') ?></td>
                                                <td><?php echo make_link($sums[3], 'total') ?></td>
                                                <td><?php echo make_link($sums[4], 'total') ?></td>
                                                <td><?php echo make_link($sums[5], 'total') ?></td>
                                                <td><?php echo make_link($sums[6], 'total') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <br>
                                <div class="kmon-scond">
                                    <h4><?php echo SCTEXT('SMSC Connection Details') ?></h4>
                                    <hr>
                                    <table class="wd100 table row-column table-bordered sc_responsive" id="kmonscond" valign="top">
                                        <thead>
                                            <tr>
                                                <th><?php echo SCTEXT('Instance') ?></th>
                                                <th><?php echo SCTEXT('SMSC-ID') ?></th>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th><?php echo SCTEXT('Uptime') ?></th>
                                                <th><?php echo SCTEXT('Received') ?> (MO)</th>
                                                <th><?php echo SCTEXT('Received') ?> (DLR)</th>
                                                <th><?php echo SCTEXT('Sent') ?> (MT)</th>
                                                <th><?php echo SCTEXT('Sent') ?> (DLR)</th>
                                                <th><?php echo SCTEXT('Failed') ?> (MT)</th>
                                                <th><?php echo SCTEXT('Queued') ?> (MT)</th>
                                                <th><?php echo SCTEXT('Actions') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($configs as $inst => $config) {
                                                $sep = ($inst > 0) ? ' class="sep"' : '';
                                                if ($status[$inst]['smscs']['count'] == 1) {
                                                    $smsc_mod_list = [$status[$inst]['smscs']['smsc']];
                                                } else {
                                                    $smsc_mod_list = $status[$inst]['smscs']['smsc'];
                                                }
                                                foreach ($smsc_mod_list as $smsc) {
                                                    list($st, $uptime) = explode(" ", $smsc['status']);
                                                    $uptime = ($uptime) ? get_uptime($uptime) : '-';
                                            ?>
                                                    <tr<?php echo $sep ?>>
                                                        <td><?php echo $config['name'] ?></td>
                                                        <td>
                                                            <b><?php echo $data['smpplist'][$smsc['id']]  ?> <kbd><?php echo $smsc['id'] ?></kbd></b> [<?php echo $smsc['admin-id'] ?>]<br />
                                                            <?php echo str_replace(":", "\n", $smsc['name']) ?>
                                                        </td>
                                                        <td><?php echo format_status($st) ?></td>
                                                        <td><?php echo $uptime ?></td>
                                                        <td><?php echo nf($smsc['sms']['received']) ?></td>
                                                        <td><?php echo nf($smsc['dlr']['received']) ?></td>
                                                        <td><?php echo nf($smsc['sms']['sent']) ?></td>
                                                        <td><?php echo nf($smsc['dlr']['sent']) ?></td>
                                                        <td><?php echo nf($smsc['failed']) ?></td>
                                                        <td><?php echo nf($smsc['queued']) ?></td>
                                                        <td>
                                                            <a class="btn btn-danger stop-smsc" data-smsc="<?php echo $smsc['admin-id'] ?>" href="javascript:void(0);"><?php echo SCTEXT('Stop') ?></a>
                                                            <a class="btn btn-success start-smsc" data-smsc="<?php echo $smsc['admin-id'] ?>" href="javascript:void(0);"><?php echo SCTEXT('Start') ?></a>

                                                        </td>
                                                        </tr>
                                                <?php
                                                    $sep = '';
                                                }
                                            }
                                                ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>