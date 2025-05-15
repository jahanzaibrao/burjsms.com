<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('SMPP API')?><small><?php echo SCTEXT('details of SMPP API specification and SMPP client accounts')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong><?php echo SCTEXT('API Usage Instructions')?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="well" style="position:relative;">
                                                            <span class="label label-info" style="position:absolute;top:0;left:0">SMPP API</span>
                                                            <?php echo SCTEXT('SMPP is one of the known telecommunication protocols that are widely used between the SMS peer entities as short message service. You can submit SMS to our server using SMPP protocol. You need to have an SMPP account activated. You can have multiple SMPP accounts. Contact your administrator for SMPP accounts.')?>
                                                        </div>
                                                        <div class="well" style="position:relative;">
                                                        <span class="label label-info" style="position:absolute;top:0;left:0"><?php echo SCTEXT('Requirements')?></span>
                                                        <?php echo SCTEXT('In order to send SMS via SMPP, you need to establish a connection to our server. Here are the parameters you would need')?>:<br />
                                                           <table class="table table-bordered table-striped">
                                                           	<tbody>
                                                            	<tr>
                                                                	<td><strong><?php echo SCTEXT('Parameter')?></strong></td>
                                                                    <td><strong><?php echo SCTEXT('Description')?></strong></td>
                                                                    <td><strong><?php echo SCTEXT('Value')?></strong></td>
                                                                </tr>
                                                                <tr>
                                                                	<td><?php echo SCTEXT('SMPP Host')?></td>
                                                                    <td><?php echo SCTEXT('Our SMPP Server IP')?></td>
                                                                    <td><?php echo Doo::conf()->server_ip ?></td>
                                                                </tr>
                                                                 <tr>
                                                                	<td><?php echo SCTEXT('SMPP Port')?></td>
                                                                    <td><?php echo SCTEXT('Server port where SMPP PDU is received')?></td>
                                                                    <td>&lt;<?php echo SCTEXT('As Defined in Account')?>></td>
                                                                </tr>
                                                                 <tr>
                                                                	<td><?php echo SCTEXT('SystemID')?></td>
                                                                    <td><?php echo SCTEXT('Username of SMPP account')?></td>
                                                                     <td>&lt;<?php echo SCTEXT('As Defined in Account')?>></td>
                                                                </tr>
                                                                <tr>
                                                                	<td><?php echo SCTEXT('Password')?></td>
                                                                    <td><?php echo SCTEXT('Password of SMPP account')?></td>
                                                                     <td>&lt;<?php echo SCTEXT('As Defined in Account')?>></td>
                                                                </tr>
                                                                <tr>
                                                                	<td><?php echo SCTEXT('Sessions')?></td>
                                                                    <td><?php echo SCTEXT('It can have either Transmitter(Tx), Receiver(Rx) or Transceiver(TRx) mode.')?></td>
                                                                     <td>&lt;<?php echo SCTEXT('As Defined in Account')?>></td>
                                                                </tr>
                                                                
                                                                 <tr>
                                                                	<td><?php echo SCTEXT('Whitelisted IP')?></td>
                                                                    <td><?php echo SCTEXT('List of IP addresses from which connection will be accepted.')?> </td>
                                                                    <td>&lt;<?php echo SCTEXT('As Defined in Account')?>></td>
                                                                </tr>
                                                            </tbody>
                                                           </table>
                                                        </div>
                                                        <div class="well" style="position:relative;">
                                                        <span class="label label-info" style="position:absolute;top:0;left:0"><?php echo SCTEXT('Supported Commands')?></span>
                                                        <?php echo SCTEXT('Our SMPP Server Supports following commands')?>:<br />
                                                           <table class="table table-bordered table-striped">
                                                               <tbody>
                                                                    <tr>
                                                                        <td><strong><?php echo SCTEXT('Command')?></strong></td>
                                                                        <td><strong><?php echo SCTEXT('Description')?></strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>BIND_TRANSCEIVER </td>
                                                                        <td><?php echo SCTEXT('Initiated by the client')?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>UNBIND </td>
                                                                        <td><?php echo SCTEXT('Initiated by the client or the server')?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>SUBMIT_SM</td>
                                                                        <td><?php echo SCTEXT('Initiated by the client. Use this to send the messages')?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>DELIVER_SM</td>
                                                                        <td><?php echo SCTEXT('Initiated by the server as delivery reports')?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>ENQUIRE_LINK</td>
                                                                        <td><?php echo SCTEXT('Initiated by the client')?></td>
                                                                    </tr>
                                                                </tbody>
															</table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-l-lg">
                                    <table id="t-saclient" data-plugin="DataTable" data-options="{
                                    ajax: '<?php echo Doo::conf()->APP_URL ?>getSmppApiClients', <?php if($_SESSION['user']['group']=='admin'){ ?>columns: [null,{width:'25%'},<?php if($_SESSION['user']['account_type']==0){ ?>null,<?php } ?>null], <?php } ?>order:[], serverSide: true, processing: true, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, ordering: false, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('System ID')?></th>
                                            <?php if($_SESSION['user']['group']=='admin'){ ?> <th><?php echo SCTEXT('User')?></th> <?php } ?>
                                            <?php if($_SESSION['user']['account_type']==0){ ?> <th><?php echo SCTEXT('Route')?></th> <?php } ?>
                                                <th><?php echo SCTEXT('Actions')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                        </div>
                                    </div>
                                   
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>           