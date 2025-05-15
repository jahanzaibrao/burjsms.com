<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Power Grid') ?><small><?php echo SCTEXT('manage app processes & switches') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>

                            <!-- start content -->
                            <div class="col-md-12">
                                <div class="col-md-8 p-r-md">
                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title clearfix">
                                                <?php echo SCTEXT('Background Processes') ?>

                                            </h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body" id="">
                                            <span>
                                                <?php echo SCTEXT('Processes are very integral for successful operation of the system. Please ensure that all processes are running properly. Below is a summary of the processes. Refer to the terminal and use PM2 for logs and active monitoring.') ?>
                                            </span>
                                            <table class="table m-t-sm">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo SCTEXT('Name') ?></th>
                                                        <th><?php echo SCTEXT('RAM') ?></th>
                                                        <th><?php echo SCTEXT('CPU') ?></th>
                                                        <th><?php echo SCTEXT('Uptime') ?></th>
                                                        <th><?php echo SCTEXT('Pulse') ?></th>
                                                        <th><?php echo SCTEXT('Actions') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pm2list">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="widget">
                                        <header class="widget-header">
                                            <h4 class="widget-title">
                                                <?php echo SCTEXT('Maintenance Mode') ?>
                                            </h4>
                                        </header>
                                        <hr class="widget-separator">
                                        <div class="widget-body">
                                            <?php $mmdata = unserialize($data['mmdata']); ?>
                                            <form id="mm_form" class="form-horizontal" method="post">
                                                <input type="hidden" name="var" value="MAINTENANCE_MODE_DATA">
                                                <div class="form-group">
                                                    <label class=" col-md-3 control-label"><?php echo SCTEXT('Status') ?>:</label>
                                                    <div class="col-md-9">
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="mm-y" <?php if ($data['mmFlag'] == 1) { ?> checked="checked" <?php } ?> name="vstatus" value="1" type="radio">
                                                            <label for="mm-y"><?php echo SCTEXT('Enabled') ?></label>
                                                        </div>
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="mm-n" <?php if ($data['mmFlag'] == 0) { ?> checked="checked" <?php } ?> name="vstatus" value="0" type="radio">
                                                            <label for="mm-n"><?php echo SCTEXT('Disabled') ?></label>
                                                        </div>
                                                        <span class="help-block text-info m-t-xs m-b-xs"><?php echo SCTEXT('Enabling this will only allow ADMIN to log into the system.') ?></span>
                                                    </div>
                                                </div>

                                                <div id="mm-opts" <?php if ($data['mmFlag'] == 0) { ?> class="disabledBox" <?php } ?>>

                                                    <div class="form-group">
                                                        <label class=" col-md-3 control-label"><?php echo SCTEXT('Message') ?>:</label>
                                                        <div class="col-md-9">
                                                            <textarea data-placement="top" class="form-control pop-over" data-original-title="<?php echo SCTEXT('Display Text') ?>" data-content="<?php echo SCTEXT('You could enter text like<br><br>We are currently under maintenance etc.') ?>" data-trigger="hover" name="msg" placeholder="<?php echo SCTEXT('enter text to be displayed on the page') ?> ..."><?php echo $mmdata['msg'] ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" col-md-3 control-label"><?php echo SCTEXT('Deadline') ?>:</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group">

                                                                <label for="mmdp" class="input-group-addon bg-info text-white"><i class="fa fa-lg fa-calendar-alt"></i> </label>

                                                                <input type="text" id="mmdp" name="end_date" class="form-control" value="">
                                                                <input type="hidden" id="deftime" value="<?php echo $mmdata['end_date']; ?>">


                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button type="button" class="btn btn-primary" id="submit_mm"><?php echo SCTEXT('Save Changes') ?></button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-12">
                                <div class="widget">
                                    <header class="widget-header">
                                        <h4 class="widget-title clearfix">
                                            <?php echo SCTEXT('Database Archiving') ?>
                                            <div class="pull-right" style="font-size: 12px; font-weight:500; line-height:20px;">

                                                <a href="<?php echo Doo::conf()->APP_URL ?>dbArchiveLog" class="btn btn-info btn-sm"><i class="fa fa-list"></i>&nbsp; <?php echo SCTEXT('Check Log') ?></a>


                                            </div>
                                        </h4>
                                    </header>
                                    <hr class="widget-separator">
                                    <div class="widget-body clearfix" id="">
                                        <div class="col-md-6">
                                            <span class="m-b-sm block">
                                                <?php echo SCTEXT('Here you can start archiving task for Database. This task will move old sent SMS records into a separate archive database for optimum DB performance.') ?>
                                            </span>
                                            <span class="pointer m-b-xs block"><i class="m-r-xs fa fa-lg fa-fixed fa-clock text-purple"></i> <b><?php echo SCTEXT('Last Archive task') ?>:</b> <?php echo $data['ahdata']->latest_arch == '' ? ' ' . SCTEXT('Never') : date(Doo::conf()->date_format_long_time, strtotime($data['ahdata']->latest_arch)) ?></span>
                                            <span class="pointer m-b-xs block"><i class="m-r-xs fa fa-lg fa-fixed fa-calendar-check text-success"></i> <b><?php echo SCTEXT('Archived Data till') ?>:</b> <?php echo $data['ahdata']->arch_upto == '' ? ' ' . SCTEXT('Never') : date(Doo::conf()->date_format_long, strtotime($data['ahdata']->arch_upto)) ?></span>
                                            <span class="pointer m-b-xs block"><i class="m-r-xs fa fa-lg fa-fixed fa-database text-primary"></i> <b><?php echo SCTEXT('Archive Size') ?>:</b> <?php echo $data['arsize'] ?> MB (~<?php echo $data['arnum'] ?> <?php echo SCTEXT('records') ?>)</span>
                                        </div>

                                        <div class="col-md-6 p-l-lg" style="border-left:1px #ccc solid;">
                                            <h4 class="m-b-md"><?php echo SCTEXT('Auto Archive Period') ?></h4>
                                            <span>Enter a time period and the system will archive all the sent SMS data saved before that.</span>
                                            <form method="POST" action="" id="arch_form">
                                                <div class="input-group m-t-sm col-md-10">
                                                    <span class="input-group-addon">Archive data older than</span>
                                                    <input type="text" name="arch_ts" id="arch_ts" class="form-control " value="<?php echo intval($data['arch']) ?>" placeholder="e.g. 180" maxlength="4">
                                                    <span class="input-group-addon">Days</span>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary " type="button" id="save_archts">Save</button>
                                                    </span>
                                                </div>
                                            </form>
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