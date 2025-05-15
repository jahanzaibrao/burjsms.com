<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Manage DLR Codes') ?><small><?php echo SCTEXT('define dlr codes with description & behavior') ?> <?php echo strtoupper($data['rdata']->title); ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="clearfix sepH_b">
                                            <div class="btn-group pull-right">
                                                <a href="javascript:void(0)" id="add_new_code" class="btn btn-primary"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add New Code') ?></a>
                                            </div>
                                            <div style="margin:auto;width:40%;" class="clearfix p-md bg-default img-rounded">
                                                <form method="post" id="import_vdlr_frm">
                                                    <input type="hidden" id="targetSmpp" name="targetSmpp" value="<?php echo $data['rdata']->id ?>">
                                                    <div class="col-md-4 p-l-sm clearfix">
                                                        <div class="">
                                                            <select class="form-control" data-plugin="select2" id="sourceSmpp">
                                                                <?php foreach ($data['allsmpp'] as $rt) { ?>
                                                                    <option value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 p-l-sm">
                                                        <a class="btn btn-primary" id="submit_importVdlr" href="javascript:void(0);"><i class="fa fa-lg fa-file-import m-r-xs"></i> Import DLR Codes</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><br />
                                        <form class="form-horizontal" method="post" id="add_dlrcode_form" action="">
                                            <input type="hidden" id="smppid" name="smppid" value="<?php echo $data['rdata']->id ?>" />
                                            <?php
                                            $rowstr = '<tr> <td><input name="codes[]" class="form-control" type="text" placeholder="' . SCTEXT('DLR Code') . '" /></td> <td><input name="appcodes[]" class="form-control" type="text" placeholder="' . SCTEXT('DLR Code') . '" /></td> <td><input class="form-control" type="text" placeholder="' . SCTEXT('Description') . '" name="descs[]" /></td> <td><select name="dlr_actions[]" class="form-control dlrcodeaction"><option value="0">' . SCTEXT('- No Action -') . '</option><option value="1">' . SCTEXT('Refund Credits') . '</option><option value="2">' . SCTEXT('Re-route SMS') . '</option><option value="3">' . SCTEXT('Refund & Add to Blacklist') . '</option><option value="4">' . SCTEXT('Refund & Shutdown SMPP') . '</option></select></td> <td><select name="act_params[]" class="form-control"><option value="0">' . SCTEXT('- Select Action First -') . '</option></select></td> <td><select name="types[]" class="form-control"><option value="1">' . SCTEXT('Success') . '</option><option value="2">' . SCTEXT('Pending') . '</option><option value="3">' . SCTEXT('Failure') . '</option></select></td> <td><button class="rmv btn btn-round-min btn-danger" type="button"><span><i class="fa fa-large fa-trash fa-inverse"></i></span></button></td> </tr>';

                                            $refstr = '<select name="act_params[]" class="form-control">';
                                            foreach ($data['refunds'] as $ref) {
                                                $refstr .= '<option value="' . $ref->id . '">' . $ref->title . '</option>';
                                            }
                                            $refstr .= '</select>';

                                            $rtstr = '<select name="act_params[]" class="form-control">';
                                            foreach ($data['allsmpp'] as $rt) {
                                                $rtstr .= '<option value="' . $rt->id . '">' . $rt->title . '</option>';
                                            }
                                            $rtstr .= '</select>';


                                            ?>
                                            <input type="hidden" id="newrowstr" value="<?php echo htmlentities($rowstr); ?>" />
                                            <input type="hidden" id="allref_opts" value="<?php echo htmlentities($refstr); ?>" />
                                            <input type="hidden" id="allrt_opts" value="<?php echo htmlentities($rtstr); ?>" />

                                            <fieldset>
                                                <table id="dlrcodetbl" class="sc_responsive wd100 table row-border order-column ">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo SCTEXT('Vendor DLR Code') ?></th>
                                                            <th><?php echo SCTEXT('Optional Custom Code') ?></th>
                                                            <th><?php echo SCTEXT('Description') ?></th>
                                                            <th><?php echo SCTEXT('DLR Action') ?></th>
                                                            <th><?php echo SCTEXT('Parameter') ?></th>
                                                            <th><?php echo SCTEXT('Category') ?></th>
                                                            <th><?php echo SCTEXT('Remove') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="codes_ctnr">
                                                        <?php if (isset($data['codes']) && !empty($data['codes'])) { ?>


                                                            <?php foreach ($data['codes'] as $code) { ?>

                                                                <tr>
                                                                    <td><input value="<?php echo $code->vendor_dlr_code ?>" name="codes[]" class="form-control" type="text" placeholder="<?php echo SCTEXT('DLR Code') ?>" />
                                                                    </td>

                                                                    <td><input value="<?php echo $code->optional_custom_code ?>" name="appcodes[]" class="form-control" type="text" placeholder="<?php echo SCTEXT('DLR Code') ?>" />
                                                                    </td>

                                                                    <td><input value="<?php echo $code->description ?>" class="form-control" type="text" placeholder="<?php echo SCTEXT('Description') ?>" name="descs[]" />
                                                                    </td>

                                                                    <td><select name="dlr_actions[]" class="form-control dlrcodeaction">
                                                                            <option <?php if ($code->action == '0') { ?> selected="selected" <?php } ?> value="0"><?php echo SCTEXT('- No Action -') ?></option>
                                                                            <option <?php if ($code->action == '1') { ?> selected="selected" <?php } ?> value="1"><?php echo SCTEXT('Refund Credits') ?></option>
                                                                            <option <?php if ($code->action == '2') { ?> selected="selected" <?php } ?> value="2"><?php echo SCTEXT('Re-route SMS') ?></option>
                                                                            <option <?php if ($code->action == '3') { ?> selected="selected" <?php } ?> value="3"><?php echo SCTEXT('Refund & Add to Blacklist') ?></option>
                                                                            <option <?php if ($code->action == '4') { ?> selected="selected" <?php } ?> value="4"><?php echo SCTEXT('Refund & Shutdown SMPP') ?></option>
                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <?php if ($code->action == '0') { ?>
                                                                            <select name="act_params[]" class="form-control">
                                                                                <option value="0">- Select Action First -</option>
                                                                            </select>
                                                                        <?php } ?>
                                                                        <?php if ($code->action == '1' || $code->action == '3' || $code->action == '4') { ?>
                                                                            <select name="act_params[]" class="form-control"><?php foreach ($data['refunds'] as $ref) { ?><option <?php if ($code->param_value == $ref->id) { ?> selected="selected" <?php } ?> value="<?php echo $ref->id ?>"><?php echo $ref->title ?></option><?php } ?></select>
                                                                        <?php } ?>
                                                                        <?php if ($code->action == '2') { ?>
                                                                            <select name="act_params[]" class="form-control">
                                                                                <?php foreach ($data['allsmpp'] as $rt) { ?><option <?php if ($code->param_value == $rt->id) { ?> selected="selected" <?php } ?> value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option><?php } ?>
                                                                            </select>
                                                                        <?php } ?>

                                                                    </td>

                                                                    <td><select name="types[]" class="form-control">
                                                                            <option <?php if ($code->category == '1') { ?> selected="selected" <?php } ?> value="1"><?php echo SCTEXT('Success') ?></option>
                                                                            <option <?php if ($code->category == '2') { ?> selected="selected" <?php } ?> value="2"><?php echo SCTEXT('Pending') ?></option>
                                                                            <option <?php if ($code->category == '3') { ?> selected="selected" <?php } ?> value="3"><?php echo SCTEXT('Failure') ?></option>
                                                                        </select>
                                                                    </td>

                                                                    <td><button class="rmv btn btn-round-min btn-danger" type="button"><span><i class="fa fa-large fa-trash fa-inverse"></i></span></button>
                                                                    </td>
                                                                </tr>

                                                            <?php } ?>


                                                        <?php } else { ?>

                                                            <tr class="empty_table">
                                                                <td colspan="7"> <?php echo SCTEXT('No DLR Codes Found') ?> </td>
                                                            </tr>

                                                        <?php } ?>
                                                    </tbody>
                                                </table><br />
                                                <div class="">
                                                    <div class="">
                                                        <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                                        <button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>


                                            </fieldset>
                                        </form>

                                    </div>
                                </div>

                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>