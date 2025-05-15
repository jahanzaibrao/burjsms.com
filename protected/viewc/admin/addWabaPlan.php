 <main id="app-main" class="app-main">
     <?php include('breadcrums.php') ?>
     <div class="wrap">
         <section class="app-content">
             <div class="row">
                 <div class="col-md-12">
                     <div class="widget p-lg">
                         <div class="row no-gutter">
                             <h3 class="page-title-sc"><?php echo SCTEXT('New WhatsApp Messaging Plan') ?><small><?php echo SCTEXT('create a new WhatsApp plan for billing user accounts') ?></small></h3>
                             <hr>
                             <?php include('notification.php') ?>
                             <div class="col-md-12">
                                 <!-- start content -->
                                 <form class="form-horizontal" action="" method="post" id="wplanfrm">

                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Plan Name') ?></label>
                                         <div class="col-md-8">
                                             <input type="text" name="pname" id="pname" placeholder="enter name for the sms plan" class="form-control">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Allowed Countries') ?>:</label>
                                         <div class="col-md-8">
                                             <div class="clearfix">
                                                 <div class="col-md-6 p-r-sm">
                                                     <select class="form-control" data-plugin="select2" id="cvsel" name="cvsel[]" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center\'>'+data.text+'</div>';return $(nstr); } }">
                                                         <option selected value="0"> <?php echo SCTEXT('All Countries') ?> </option>
                                                         <?php foreach ($data['cvdata'] as $cv) { ?>
                                                             <option value="<?php echo $cv->prefix ?>"> <?php echo $cv->country . ' (+' . $cv->prefix . ')' ?></option>
                                                         <?php } ?>
                                                     </select>
                                                 </div>

                                             </div>

                                             <span class="help-block"><?php echo SCTEXT('Select the countries and operators for which you want to request sender ID.') ?></span>
                                         </div>
                                     </div>

                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Default Profit margin') ?>:</label>
                                         <div class="col-md-8">
                                             <div class="col-md-6 col-sm-6 col-xs-8 input-group input-group-sm">
                                                 <input style="width: 80px;" type="text" name="pmarg" id="pmarg" class="form-control input-group-text" placeholder="e.g. 14.5" maxlength="10" />
                                                 <div class="input-group-addon p-0 pull-left" style="border: none;">
                                                     <select style="min-width: 52px;" class="form-control input-sm" name="margtype">
                                                         <option value="0">%</option>

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
                                                 <input style="width: 80px;" type="text" name="nfmarg" id="nfmarg" class="form-control input-group-text" placeholder="e.g. 0.02" maxlength="10" />
                                             </div>

                                         </div>
                                     </div>

                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Tax on tariff') ?>:</label>
                                         <div class="col-md-8">
                                             <div class="col-md-6 col-sm-6 col-xs-8 input-group">
                                                 <input type="text" name="ptax" id="ptax" class="form-control input-sm" placeholder="e.g. 14.5" maxlength="50" />
                                                 <span class="input-group-addon">%</span>
                                                 <select class="form-control input-sm" name="taxtype">
                                                     <option value="GT">GST</option>
                                                     <option value="VT">VAT</option>
                                                     <option value="ST">Service Tax</option>
                                                     <option value="SC">Service Charge</option>
                                                     <option value="OT"><?php echo SCTEXT('Other Taxes') ?></option>
                                                 </select>

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