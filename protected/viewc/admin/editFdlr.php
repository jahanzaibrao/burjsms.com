 <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Modify Fake DLR Template')?><small><?php echo SCTEXT('modify the composition for Fake DLR template')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" action="" method="post" id="fdlrfrm">
                                        <input type="hidden" name="tid" value="<?php echo $data['fdlr']->id ?>">
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Title')?></label>
												<div class="col-md-8">
													<input value="<?php echo $data['fdlr']->title ?>" type="text" class="form-control" name="fdtitle" placeholder="enter a title for this fake dlr template...">
												</div>
											</div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php echo SCTEXT('Composition')?></label>
                                                <div class="col-md-8">
                                                    <?php
                                                        $rowstr='<tr><td><div class="input-group"><input name="ratio[]" class="form-control input-sm" type="text" placeholder="'.SCTEXT('e.g. 65').'" /><span class="input-group-addon">%</span></div></td> <td><input name="codes[]" class="form-control input-sm" type="text" placeholder="'.SCTEXT('e.g. 101').'" /></td> <td><input class="form-control input-sm" type="text" placeholder="'.SCTEXT('e.g. Delivered, Failed etc.').'" name="descs[]" /></td> <td><select name="types[]" class="form-control input-sm"><option value="1">'.SCTEXT('Success').'</option><option value="2">'.SCTEXT('Pending').'</option><option value="3">'.SCTEXT('Failure').'</option></select></td> <td><button class="rmv btn btn-round-min btn-danger btn-sm" type="button"><span><i class="fa fa-trash fa-inverse"></i></span></button></td> </tr>';
                                                    ?>
                                                    <input type="hidden" id="newrowstr" value="<?php echo htmlentities($rowstr); ?>"/>
                                                    <div class="clearfix sepH_b">
                                                        <div class="btn-group pull-right">
                                                            <a href="javascript:void(0)" id="add_new_code" class="btn btn-primary btn-sm"><i class="fa fa-large fa-inverse fa-plus"></i> &nbsp; <?php echo SCTEXT('Add Row')?></a>
                                                        </div>
                                                    </div>
                                                    <fieldset>
                                                        <table id="fdlrcodes" class="sc_responsive wd100 table row-border order-column">
                                                            <thead>
                                                                <tr>
                                                                    <th>Ratio</th>
                                                                    <th>DLR Code</th>
                                                                    <th>Description</th>
                                                                    <th>Category</th>
                                                                    <th>Remove</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="codes_ctr">
                                                                <?php
                                                                $comp = json_decode($data['fdlr']->composition, true);
                                                                foreach($comp as $element){
                                                                ?>
                                                                <tr>
                                                                <tr><td><div class="input-group"><input name="ratio[]" class="form-control input-sm" type="text" value="<?php echo $element['ratio'] ?>" placeholder="<?php echo SCTEXT('e.g. 65') ?>" /><span class="input-group-addon">%</span></div></td> <td><input name="codes[]" class="form-control input-sm" value="<?php echo $element['dlrcode'] ?>" type="text" placeholder="<?php echo SCTEXT('e.g. 101') ?>" /></td> <td><input class="form-control input-sm" type="text" placeholder="<?php echo SCTEXT('e.g. Delivered, Failed etc.') ?>" value="<?php echo $element['dlrexp'] ?>" name="descs[]" /></td> <td><select name="types[]" class="form-control input-sm"><option <?php if($element['type']==1){ ?> selected <?php } ?> value="1"><?php echo SCTEXT('Success') ?></option><option <?php if($element['type']==2){ ?> selected <?php } ?> value="2"><?php echo SCTEXT('Pending') ?></option><option <?php if($element['type']==3){ ?> selected <?php } ?> value="3"><?php echo SCTEXT('Failure') ?></option></select></td> <td><button class="rmv btn btn-round-min btn-danger btn-sm" type="button"><span><i class="fa fa-trash fa-inverse"></i></span></button></td> </tr>
                                                                </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </fieldset>
                                                </div>
                                            </div>

                                        <hr>
											<div class="form-group">
                                            <div class="col-md-3"></div>
												<div class="col-md-8">
													<button id="save_changes" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes')?></button>
													<button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel')?></button>
												</div>
											</div>

									</form>
                                <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
