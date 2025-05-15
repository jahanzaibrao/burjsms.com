<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc clearfix"><?php echo SCTEXT('SMS Details')?><small><?php echo SCTEXT('view details of the incoming SMS')?></small>
                                    <span class="pull-right">
                                        <a class="btn btn-danger del-mo" data-moid="<?php echo $data['smsdata']->id ?>" href="javascript:void(0);"><?php echo SCTEXT('Delete')?></a>
                                    </span>
                                </h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td><span class="label label-purple"><?php echo SCTEXT('User Matched') ?></span></td>
                                                    <td>
                                                        <?php echo $data['ustr'] ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-primary"><?php echo SCTEXT('SMS Sender') ?></span></td>
                                                    <td><?php echo $data['smsdata']->mobile ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-success"><?php echo SCTEXT('SMS Text') ?></span></td>
                                                    <td><?php echo htmlspecialchars($data['smsdata']->sms_text, ENT_QUOTES) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-warning"><?php echo SCTEXT('Received') ?></span></td>
                                                    <td><?php echo date(Doo::conf()->date_format_long_time,strtotime($data['smsdata']->receiving_time)) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-danger"><?php echo SCTEXT('VMN Matched') ?></span></td>
                                                    <td><span class="label label-info label-md"><?php echo $data['smsdata']->vmn ?></span></td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6 p-l-sm">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td><span class="label label-primary"><?php echo SCTEXT('Auto Reply SenderID') ?></span></td>
                                                    <td><?php echo $data['ardata']->sender==''?'-':$data['ardata']->sender; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-success"><?php echo SCTEXT('Auto Reply SMS') ?></span></td>
                                                    <td><?php echo $data['ardata']->sent_sms_text==''?'-': htmlspecialchars($data['ardata']->sent_sms_text, ENT_QUOTES); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-purple"><?php echo SCTEXT('Primary Keyword Match') ?></span></td>
                                                    <td><?php echo $data['ardata']->primary_keyword==''?'-':$data['ardata']->primary_keyword; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-danger"><?php echo SCTEXT('Other Keyword Match') ?></span></td>
                                                    <td><?php echo $data['ardata']->other_keyword==''?'-':$data['ardata']->other_keyword; ?></td>
                                                </tr>
                                               
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