<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Edit Prefix') ?><small><?php echo SCTEXT('edit operator prefix information') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal" method="post" id="edit_pre_form" action="">
                                    <input type="hidden" name="pid" id="pid" value="<?php echo $data['pdata']->id ?>" />
                                    <input type="hidden" name="cid" id="cid" value="<?php echo $data['covid'] ?>" />
                                    <div class="">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Prefix') ?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" name="prefix" id="prefix" class="form-control" value="<?php echo $data['pdata']->prefix ?>" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('MCCMNC') ?>:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="mccmnc" id="msel" data-plugin="select2" data-options="{templateResult: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var nstr = '<div style=\'max-width:80%;word-wrap:break-word;\' class=\'pull-left\'>'+data.text+'</div><div class=\'pull-right\'><kbd>'+data.element.dataset.mccmnc+'</kbd></div><div class=\'clearfix\'></div>';return $(nstr);} }, templateSelection: function (data){ if(!data.element){return ''}; if(data.element.value==0){return data.text;}else{ var nstr = '<div style=\'max-width:80%;word-wrap:break-word;\' class=\'pull-left\'>'+data.text+'</div><div class=\'pull-right\'><kbd>'+data.element.dataset.mccmnc+'</kbd></div><div class=\'clearfix\'></div>';return $(nstr);} } }">
                                                    <option value="0">- Select MCCMNC -</option>
                                                    <?php foreach ($data['mccmnc'] as $np) { ?>
                                                        <option <?php if ($data['pdata']->mccmnc == $np->mccmnc) { ?> selected <?php } ?> data-nw="<?php echo ($np->brand) ?>" data-rg="<?php echo ($np->operator) ?>" value="<?php echo $np->mccmnc ?>" data-mccmnc="<?php echo $np->mccmnc ?>"><?php echo ($np->brand) ?> (<?php echo ($np->operator) ?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Network') ?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" readonly name="operator" id="operator" class="form-control" value="<?php echo ($data['pdata']->brand) ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Region') ?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" readonly name="circle" id="circle" class="form-control" value="<?php echo ($data['pdata']->operator) ?>" />
                                            </div>
                                        </div>

                                    </div>



                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                            <button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel') ?></button>
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