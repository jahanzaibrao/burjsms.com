 <main id="app-main" class="app-main">
     <?php include('breadcrums.php') ?>
     <div class="wrap">
         <section class="app-content">
             <div class="row">
                 <div class="col-md-12">
                     <div class="widget p-lg">
                         <div class="row no-gutter">
                             <h3 class="page-title-sc"><?php echo SCTEXT('Edit Plan') ?><small><?php echo SCTEXT('modify the  MCC/MCC based plan for currency based accounts') ?> <b><?php echo $data['plan']->plan_name ?></b></small></h3>
                             <hr>
                             <?php include('notification.php') ?>
                             <div class="col-md-12">
                                 <!-- start content -->
                                 <form class="form-horizontal" action="" method="post" id="mplanfrm">
                                     <input type="hidden" name="planid" value="<?php echo $data['plan']->id ?>">
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Plan Name') ?></label>
                                         <div class="col-md-8">
                                             <input type="text" value="<?php echo $data['plan']->plan_name ?>" name="pname" placeholder="enter name for the sms plan" class="form-control">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Select Route') ?></label>
                                         <div class="col-md-8">
                                             <select name="proutes" id="proutes" class="form-control" data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('Select Routes for this plan') ?> ...', maximumSelectionLength: 5}">
                                                 <?php
                                                    foreach ($data['rdata'] as $rt) { ?>
                                                     <option <?php if ($rt['id'] == $data['plan']->route_id) { ?> selected <?php } ?> id="<?php echo 'routesel_' . $rt['id'] ?>" data-iso="<?php echo strtolower($rt['country_code']) ?>" data-country="<?php echo $rt['country'] ?>" value="<?php echo $rt['id'] ?>"><?php echo $rt['title'] ?></option>
                                                 <?php } ?>
                                             </select>
                                             <div class="m-t-sm">
                                                 <hr>
                                                 <h5>Supported Countries</h5>
                                                 <hr>
                                                 <div id="country_list">- No Route Selected -</div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Default Profit margin') ?>:</label>
                                         <div class="col-md-8">
                                             <div class="col-md-6 col-sm-6 col-xs-8 input-group input-group-sm">
                                                 <input value="<?php echo $data['plan']->default_profit ?>" style="width: 80px;" type="text" name="pmarg" id="pmarg" class="form-control input-group-text" placeholder="e.g. 14.5" maxlength="10" />
                                                 <div class="input-group-addon p-0 pull-left" style="border: none;">
                                                     <select style="min-width: 52px;" class="form-control input-sm" name="margtype">
                                                         <option <?php if ($data['plan']->default_profit_type == 0) { ?> selected <?php } ?> value="0">%</option>
                                                         <option <?php if ($data['plan']->default_profit_type == 1) { ?> selected <?php } ?> value="1"><?php echo Doo::conf()->currency_name ?></option>
                                                     </select>
                                                 </div>


                                             </div>

                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Non-refundable margin') ?>:</label>
                                         <div class="col-md-8">
                                             <div class="col-md-6 col-sm-6 col-xs-8 input-group input-group-sm">
                                                 <span class="input-group-addon"><?php echo Doo::conf()->currency_name ?></span>
                                                 <input style="width: 80px;" type="text" name="nfmarg" id="nfmarg" class="form-control input-group-text" placeholder="e.g. 0.02" maxlength="10" value="<?php echo $data['plan']->nonref_amount ?>" />
                                             </div>

                                         </div>
                                     </div>

                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Tax on tariff') ?>:</label>
                                         <div class="col-md-8">
                                             <div class="col-md-6 col-sm-6 col-xs-8 input-group">
                                                 <input type="text" value="<?php echo $data['plan']->tax ?>" name="ptax" id="ptax" class="form-control input-sm" placeholder="e.g. 14.5" maxlength="50" />
                                                 <span class="input-group-addon">%</span>
                                                 <select class="form-control input-sm" name="taxtype">
                                                     <option <?php if ($data['plan']->tax_type == 'GT') { ?> selected <?php } ?> value="GT">GST</option>
                                                     <option <?php if ($data['plan']->tax_type == 'VT') { ?> selected <?php } ?> value="VT">VAT</option>
                                                     <option <?php if ($data['plan']->tax_type == 'ST') { ?> selected <?php } ?> value="ST">Service Tax</option>
                                                     <option <?php if ($data['plan']->tax_type == 'SC') { ?> selected <?php } ?> value="SC">Service Charge</option>
                                                     <option <?php if ($data['plan']->tax_type == 'OT') { ?> selected <?php } ?> value="OT"><?php echo SCTEXT('Other Taxes') ?></option>
                                                 </select>

                                             </div>

                                         </div>
                                     </div>
                                     <hr>
                                     <div class="block">
                                         <div class="panel-heading text-center"><?php echo SCTEXT('Choose which features will be available by default to the users signing-up with this plan. You can modify these permissions later for each user as well.') ?></div>


                                         <div id="volftbox">
                                             <?php $ft = unserialize($data['plan']->plan_features); ?>
                                             <div class="panel panel-info">
                                                 <div class="panel-heading">
                                                     <?php echo SCTEXT('Account Permissions') ?>
                                                 </div>
                                                 <div class="panel-body">
                                                     <div class="clearfix">
                                                         <div class="col-md-4 splanft-item">
                                                             <div class="widget">
                                                                 <header class="widget-header">
                                                                     <h4 class="widget-title"><?php echo SCTEXT('Allowed SMS Types') ?></h4>
                                                                 </header>
                                                                 <hr class="widget-separator">
                                                                 <div class="widget-body">
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[flash]" type="checkbox" data-switchery data-color="#35b8e0" <?php if ($ft['flash'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>Flash</label>
                                                                     </div>
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[wap]" type="checkbox" data-switchery data-color="#35b8e0" <?php if ($ft['wap'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>WAP-Push</label>
                                                                     </div>
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[vcard]" type="checkbox" data-switchery data-color="#35b8e0" <?php if ($ft['vcard'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>VCard</label>
                                                                     </div>
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[unicode]" type="checkbox" data-switchery data-color="#35b8e0" <?php if ($ft['unicode'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>Unicode</label>
                                                                     </div>
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[per]" type="checkbox" data-switchery data-color="#35b8e0" <?php if ($ft['per'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>Personalised SMS</label>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </div>


                                                         <div class="col-md-4 splanft-item">
                                                             <div class="widget">
                                                                 <header class="widget-header">
                                                                     <h4 class="widget-title"><?php echo SCTEXT('Allowed API access') ?></h4>
                                                                 </header>
                                                                 <hr class="widget-separator">
                                                                 <div class="widget-body">
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[hapi]" type="checkbox" data-switchery data-color="#ff5b5b" <?php if ($ft['hapi'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>HTTP API</label>
                                                                     </div>
                                                                     <div class="m-b-xs m-r-xl">
                                                                         <input data-size="small" name="vftperm[xapi]" type="checkbox" data-switchery data-color="#ff5b5b" <?php if ($ft['xapi'] == 'on') { ?> checked="checked" <?php } ?>>
                                                                         <label>XML API</label>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </div>


                                                         <div class="col-md-4 splanft-item">
                                                             <div class="widget">
                                                                 <header class="widget-header">
                                                                     <h4 class="widget-title"><?php echo SCTEXT('Allowed Refunds') ?></h4>
                                                                 </header>
                                                                 <hr class="widget-separator">
                                                                 <div class="widget-body">
                                                                     <?php foreach ($data['refunds'] as $ref) { ?>
                                                                         <div class="m-b-xs m-r-xl">
                                                                             <input data-size="small" name="vftperm[ref][<?php echo $ref->id ?>]" type="checkbox" data-switchery data-color="#10c469" <?php if ($ft['ref'][$ref->id] == 'on') { ?> checked="checked" <?php } ?>>
                                                                             <label><?php echo $ref->title ?></label>
                                                                         </div>
                                                                     <?php } ?>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </div>

                                                 </div>
                                             </div>

                                         </div>

                                     </div>

                                     <hr>
                                     <div class="form-group">
                                         <div class="col-md-3"></div>
                                         <div class="col-md-8">
                                             <button id="save_changes" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                             <button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel') ?></button>
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