<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix"><?php echo SCTEXT('View Account') ?><small><?php echo $data['user']->name . ' (' . $data['user']->email . ')' ?></small>
                                <input type="hidden" id="userid" value="<?php echo $data['user']->user_id ?>" />
                                <span class="dropdown pull-right">
                                    <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><i class="fa fa-large fa-navicon"></i> &nbsp; <?php echo SCTEXT('Actions') ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                        <li><a class="useraction" data-act="upgradeacc" href="javascript:void(0);"><i class="fa fa-large fa-user-plus"></i>&nbsp;&nbsp; <?php echo SCTEXT('Upgrade to Reseller') ?> </a></li>
                                        <li><a class="useraction" data-act="changepsw" href="javascript:void(0);"><i class="fa fa-large fa-key"></i>&nbsp;&nbsp; <?php echo SCTEXT(
                                                                                                                                                                        'Change Password'
                                                                                                                                                                    ) ?> </a></li>
                                        <li><a class="useraction" data-act="usersus" href="javascript:void(0);"><i class="fa fa-large fa-ban"></i>&nbsp;&nbsp; <?php echo SCTEXT('Suspend Account') ?> </a></li>
                                        <li><a class="useraction" data-act="userdel" href="javascript:void(0);"><i class="fa fa-large fa-trash"></i>&nbsp;&nbsp; <?php echo SCTEXT('Delete Account') ?> </a></li>
                                    </ul>

                                </span>
                            </h3>
                            <hr class="m-t-xs">
                            <?php include('notification.php') ?>

                            <?php include('navpills.php') ?>

                            <hr>
                            <!-- start content -->
                            <div class="col-md-12">
                                <?php if ($data['user']->account_type == '1') { ?>
                                    <h4><?php echo SCTEXT('SMS Plan Assignments') ?> <small class="p-l-sm" style="font-size:14px;"><?php echo SCTEXT('Switch SMS plans for currency based account') ?></small></h4>
                                    <hr>
                                    <form action="" class="form-horizontal" method="post" id="rsetform">
                                        <input type="hidden" name="userid" value="<?php echo $data['user']->user_id ?>">

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Assigned Plan') ?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" data-plugin="select2" name="mplan">
                                                    <?php foreach ($data['plans'] as $plan) { ?>
                                                        <option <?php if ($data['userplan']->plan_id == $plan->id) { ?> selected <?php } ?> value="<?php echo $plan->id ?>"><?php echo $plan->plan_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('DLR Percentage') ?>:</label>
                                            <div class="col-md-9">
                                                <?php if ($_SESSION['user']['group'] == 'admin') { ?>
                                                    <div class="col-md-12">
                                                        <?php $options = json_decode($data['userplan']->subopt_idn, true); ?>
                                                        <div class="col-md-3 col-sm-3">
                                                            <table class=" table table-striped table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <td tabindex="0"><?php echo SCTEXT('Delivery Percentage') ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                <input value="<?php echo $options['delv_per'] ?>" name="plan_delv_per" class="form-control input-sm" placeholder="e.g. 50" maxlength="3" type="text">
                                                                                <span class="input-group-addon">%</span>

                                                                            </span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3">
                                                            <table class=" table table-striped table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <td tabindex="0"><?php echo SCTEXT('Cutting Threshold') ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                <input value="<?php echo $options['delv_threshold'] ?>" name="plan_delv_threshold" class="form-control input-sm" placeholder="e.g. 50" type="text">
                                                                                <span class="input-group-addon">Contacts</span>

                                                                            </span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-4 col-sm-4">
                                                            <table class=" table table-striped table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <td tabindex="0"><?php echo SCTEXT('Fake DLR Template') ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding:5px;" tabindex="0">
                                                                            <select name="plan_fdlr" class="form-control input-sm" data-plugin="select2">
                                                                                <option value="0">- Select One -</option>
                                                                                <?php foreach ($data['fdlrs'] as $fdlr) { ?>
                                                                                    <option <?php if ($options['fdlr_id'] == $fdlr->id) { ?> selected <?php } ?> value='<?php echo $fdlr->id ?>'><?php echo $fdlr->title ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                            </div>
                                        </div>

                                    </form>
                                <?php } else { ?>
                                    <h4><?php echo SCTEXT('Route Assignments') ?> <small class="p-l-sm" style="font-size:14px;"><?php echo SCTEXT('Check/Uncheck the box in front of Route to Assign or Remove route from User Account') ?></small></h4>

                                    <hr>
                                    <form action="" method="post" id="rsetform">
                                        <input type="hidden" name="userid" value="<?php echo $data['user']->user_id ?>">
                                        <div class="list-group">
                                            <?php
                                            $i = 0;
                                            foreach ($_SESSION['credits']['routes'] as $myrt) {
                                                $i++;
                                                $k = 0;
                                                $rid = $myrt['id'];
                                                $rmap = function ($e) use ($rid) {
                                                    return $e->route_id == $rid;
                                                };
                                                $rtobj = array_filter($data['rdata'], $rmap);
                                                $k = key($rtobj);

                                            ?>
                                                <?php if ($data['user']->account_type == '2') { ?>
                                                    <li class="list-group-item">
                                                        <h4 class="list-group-item-heading">
                                                            <input data-rid="<?php echo $rid ?>" class="rtsel" id="rtas-<?php echo $i ?>" data-size="medium" name="routes[<?php echo $rid ?>]" type="checkbox" data-switchery data-color="#10c469" <?php if ($data['rdata'][$k]->id) { ?> checked <?php } ?>>
                                                            <span class="m-l-sm"><label class="fz-md" for="rtas-<?php echo $i ?>"><?php echo $myrt['name'] ?></label></span>

                                                        </h4>

                                                        <div id="rtdetail-<?php echo $rid ?>" class="clearfix collapse <?php if ($data['rdata'][$k]->id) { ?> in <?php } ?>">
                                                            <hr class="m-h-sm">
                                                            <div class="col-md-3 col-sm-3 m-r-sm">
                                                                <table class=" table table-striped table-bordered">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td tabindex="0"><?php echo SCTEXT('SMS Price') ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                    <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                                                    <input value="<?php echo floatval($data['rdata'][$k]->price) ?>" name="ratecur[<?php echo $rid ?>]" class="form-control input-sm" placeholder="e.g. 0.05" maxlength="8" type="text">
                                                                                    <span class="input-group-addon">per SMS</span>
                                                                                </span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                            <?php if ($_SESSION['user']['group'] == 'admin') { ?>
                                                                <div class="col-md-2 col-sm-2">
                                                                    <table class=" table table-striped table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td tabindex="0"><?php echo SCTEXT('Delivery Percentage') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                        <input value="<?php echo $data['rdata'][$k]->delv_per ?>" name="dlrper[<?php echo $rid ?>]" class="form-control input-sm" placeholder="e.g. 50" maxlength="3" type="text">
                                                                                        <span class="input-group-addon">%</span>

                                                                                    </span></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-sm-3">
                                                                    <table class=" table table-striped table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td tabindex="0"><?php echo SCTEXT('Cutting Threshold') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                        <input value="<?php echo $data['rdata'][$k]->delv_threshold ?>" name="dlrperth[<?php echo $rid ?>]" class="form-control input-sm" placeholder="e.g. 50" type="text">
                                                                                        <span class="input-group-addon">Contacts</span>

                                                                                    </span></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-sm-3">
                                                                    <table class=" table table-striped table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td tabindex="0"><?php echo SCTEXT('Fake DLR Template') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px;" tabindex="0">
                                                                                    <select name="ufdlr[<?php echo $rid ?>]" class="form-control input-sm" data-plugin="select2">
                                                                                        <option value="0">- Select One -</option>
                                                                                        <?php foreach ($data['fdlrs'] as $fdlr) { ?>
                                                                                            <option <?php if ($data['rdata'][$k]->fdlr_id == $fdlr->id) { ?> selected <?php } ?> value='<?php echo $fdlr->id ?>'><?php echo $fdlr->title ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                <?php } else { ?>
                                                    <li class="list-group-item">
                                                        <h4 class="list-group-item-heading">
                                                            <input data-rid="<?php echo $rid ?>" class="rtsel" id="rtas-<?php echo $i ?>" data-size="medium" name="routes[<?php echo $rid ?>]" type="checkbox" data-switchery data-color="#10c469" <?php if ($data['rdata'][$k]->id) { ?> checked <?php } ?>>
                                                            <span class="m-l-sm"><label class="fz-md" for="rtas-<?php echo $i ?>"><?php echo $myrt['name'] ?></label></span>

                                                        </h4>

                                                        <div id="rtdetail-<?php echo $rid ?>" class="clearfix collapse <?php if ($data['rdata'][$k]->id) { ?> in <?php } ?>">
                                                            <hr class="m-h-sm">
                                                            <div class="col-md-3 col-sm-3 m-r-sm">
                                                                <table class="table table-striped table-bordered">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td tabindex="0"><?php echo SCTEXT('Total Credits') ?></td>
                                                                            <td><span class="badge label-md badge-info"><?php echo number_format(intval($data['rdata'][$k]->credits)) ?> </span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td tabindex="0"><?php echo SCTEXT('SMS Price') ?></td>
                                                                            <td><span class="badge label-md badge-info"> <?php echo Doo::conf()->currency . ' ' . floatval($data['rdata'][$k]->price) ?> per SMS</span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <?php if ($_SESSION['user']['group'] == 'admin') { ?>
                                                                <div class="col-md-2 col-sm-2">
                                                                    <table class=" table table-striped table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td tabindex="0"><?php echo SCTEXT('Delivery Percentage') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                        <input value="<?php echo $data['rdata'][$k]->delv_per ?>" name="dlrper[<?php echo $rid ?>]" class="form-control input-sm" placeholder="e.g. 50" maxlength="3" type="text">
                                                                                        <span class="input-group-addon">%</span>

                                                                                    </span></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-sm-3">
                                                                    <table class=" table table-striped table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td tabindex="0"><?php echo SCTEXT('Cutting Threshold') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px;" tabindex="0"><span class="input-group">
                                                                                        <input value="<?php echo $data['rdata'][$k]->delv_threshold ?>" name="dlrperth[<?php echo $rid ?>]" class="form-control input-sm" placeholder="e.g. 50" type="text">
                                                                                        <span class="input-group-addon">Contacts</span>

                                                                                    </span></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-3 col-sm-3">
                                                                    <table class=" table table-striped table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td tabindex="0"><?php echo SCTEXT('Fake DLR Template') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px;" tabindex="0">
                                                                                    <select name="ufdlr[<?php echo $rid ?>]" class="form-control input-sm" data-plugin="select2">
                                                                                        <option value="0">- Select One -</option>
                                                                                        <?php foreach ($data['fdlrs'] as $fdlr) { ?>
                                                                                            <option <?php if ($data['rdata'][$k]->fdlr_id == $fdlr->id) { ?> selected <?php } ?> value='<?php echo $fdlr->id ?>'><?php echo $fdlr->title ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </li>


                                            <?php }
                                            } ?>

                                        </div>
                                        <div class="text-right">
                                            <a id="savefrm" class="btn btn-primary"><i class="fa fa-lg fa-check m-r-xs"></i> <?php echo SCTEXT('Save Changes') ?></a>
                                        </div>


                                    </form>

                                <?php } ?>
                            </div>
                            <!-- end content -->

                        </div>
                    </div>
                </div>
            </div>

        </section>