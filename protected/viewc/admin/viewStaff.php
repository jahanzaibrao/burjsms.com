<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Staff Member') ?><small><?php echo SCTEXT('view account details and permissions') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <input type="hidden" id="staff_uid" value="<?php echo $data['udata']->user_id ?>">
                                <div class="evenboxes row clearfix">
                                    <div class="col-md-6">
                                        <div class="widget">
                                            <header class="widget-header">
                                                <h4 class="widget-title"><?php echo SCTEXT('Profile Information') ?></h4>
                                            </header>
                                            <hr class="widget-separator">
                                            <div class="widget-body">
                                                <div class="media-group-item">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <div class="avatar avatar-xl avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $data['udata']->avatar ?> " alt=""></a></div>
                                                        </div>
                                                        <div class="media-body clearfix">
                                                            <h5 class="m-t-sm"><a href="javascript:void(0);" class="m-r-xs theme-color"><?php echo ucwords($data['udata']->name) ?></a></h5>
                                                            <small><?php echo $data['udata']->email ?></small>
                                                            <div class="pull-right">
                                                                <span class="label label-flat label-md label-info">
                                                                    <i class="fa fa-lg fa-phone"></i>
                                                                </span>
                                                                <span class="label label-flat label-md label-default">
                                                                    <?php echo $data['udata']->mobile ?>
                                                                </span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                                <hr>
                                                <ul class="profile-intro">
                                                    <?php
                                                    $teamid = $data['rdata']->team_id;
                                                    $tobj = array_filter(
                                                        $data['tdata'],
                                                        function ($e) use ($teamid) {
                                                            return $e->id == $teamid;
                                                        }
                                                    );
                                                    $k = key($tobj);
                                                    ?>
                                                    <li><label><?php echo SCTEXT('Team') ?>: </label> <span class="label label-md label-<?php echo $tobj[$k]->theme ?>"><?php echo $tobj[$k]->name ?></span> </li>
                                                    <li><label><?php echo SCTEXT('Credentials') ?>: </label> <span><?php echo $data['udata']->login_id ?></span> </li>
                                                    <li><label><?php echo SCTEXT('Member since') ?>: </label> <span class="label label-md label-inverse"><?php echo date(Doo::conf()->date_format_long, strtotime($data['udata']->registered_on)) ?></span> </li>
                                                    <li><label><?php echo SCTEXT('Last Activity') ?>: </label> <span><?php echo $data['last-act'] == '' ? SCTEXT('Never Logged In') : $data['last-act']; ?></span> </li>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="widget text-right">
                                            <header class="widget-header">
                                                <h4 class="widget-title"><?php echo SCTEXT('Actions') ?></h4>
                                            </header>
                                            <hr class="widget-separator">
                                            <div class="widget-body clearfix">
                                                <button type="button" id="delStaff" data-uid="<?php echo $data['udata']->user_id ?>" class="btn btn-danger"><i class="fa fa-lg fa-trash"></i>&nbsp;&nbsp; <?php echo SCTEXT('Delete Staff member') ?></button>
                                                <button type="button" id="resetPass" data-uid="<?php echo $data['udata']->user_id ?>" class="btn btn-inverse"><i class="fa fa-lg fa-key"></i>&nbsp;&nbsp; <?php echo SCTEXT('Reset Password') ?></button>


                                                <hr>
                                                <div class="text-left pull-right planopts p-md m-r-0 m-b-0">
                                                    <div class="control-label text-right"><?php echo SCTEXT('Change Team') ?>:</div>
                                                    <div class="input-group col-md-12 m-t-sm">
                                                        <div class="input-group">
                                                            <select class="form-control" id="team" data-plugin="select2" data-options="{templateResult: function (data){return $('<span class=\'label label-'+data.title+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span> '+data.text+'</span>');},templateSelection: function (data){return $('<span class=\'label label-'+data.title+' label-lg\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span>'+data.text+'</span>');} }">
                                                                <?php foreach ($data['tdata'] as $team) { ?>
                                                                    <option title="<?php echo $team->theme ?>" value="<?php echo $team->id ?>"> <?php echo $team->name ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <button id="changeTeam" data-uid="<?php echo $data['udata']->user_id ?>" type="button" class="btn btn-primary btn-sm m-t-sm"><i class="fa fa-lg fa-check"></i>&nbsp;&nbsp; <?php echo SCTEXT('Save Changes') ?></button>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><?php echo SCTEXT('Staff Activity Log') ?></h4>
                                    </div>
                                    <div class="panel-body">

                                    </div>
                                </div>


                                <!-- Modal -->
                                <div id="resetPassBox" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><?php echo SCTEXT('Reset Password') ?></h4>
                                            </div>
                                            <div class="modal-body form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Enter Password') ?>:</label>
                                                    <div class="col-md-8">
                                                        <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="spass" id="spass" class="form-control" placeholder="<?php echo SCTEXT('enter password') ?>..." maxlength="100" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Retype Password') ?>:</label>
                                                    <div class="col-md-8">
                                                        <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="spass2" id="spass2" class="form-control" placeholder="<?php echo SCTEXT('enter password again') ?>..." maxlength="100" />
                                                        <span id="pass-err" class="help-block text-danger"></span>
                                                        <span id="pass-help" class="help-block text-primary">
                                                            <?php switch (Doo::conf()->password_strength) {
                                                                case 'weak':
                                                                    echo SCTEXT('Password length should be minimum 6 characters.');
                                                                    break;

                                                                case 'average':
                                                                    echo SCTEXT('Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.');
                                                                    break;

                                                                case 'strong':
                                                                    echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.');
                                                                    break;
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" id="resetPassSubmit"><?php echo SCTEXT('Save Changes') ?></button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo SCTEXT('Cancel') ?></button>
                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>