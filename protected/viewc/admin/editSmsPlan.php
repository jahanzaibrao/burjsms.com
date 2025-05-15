
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit SMS Plan')?><small><?php echo SCTEXT('modify sms plan parameters')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal form-wizard" method="post" id="add_splan_form" action="">
                                        <input type="hidden" id="plan_id" name="plan_id" value="<?php echo $data['pdata']->id ?>" />
                                        <div class="wizard-nav">
                                            <div class="nav-element col-md-4 step-1 active">
                                                <h4 class="nav-step-title"><?php echo SCTEXT('Step 1')?>: <?php echo SCTEXT('SMS Plan Info')?></h4>
                                                <span class="nav-step-desc"><?php echo SCTEXT('basic info about sms plan')?></span>
                                            </div>
                                            <div class="nav-element col-md-4 step-2 ">
                                                <h4 class="nav-step-title"><?php echo SCTEXT('Step 2')?>: <?php echo SCTEXT('Plan options')?></h4>
                                                <span class="nav-step-desc"><?php echo SCTEXT('define sms rates')?></span>
                                            </div>
                                            <div class="nav-element col-md-4 step-3 ">
                                                <h4 class="nav-step-title"><?php echo SCTEXT('Step 3')?>: <?php echo SCTEXT('App Features')?></h4>
                                                <span class="nav-step-desc"><?php echo SCTEXT('features available')?></span>
                                            </div>

                                            <div class="clearfix"></div>
                                        </div>

                                        <div id="step-1-form" class="block">
                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Plan Name')?>:</label>
												    <div class="col-md-8">
													<input type="text" name="pname" id="pname" class="form-control" placeholder="<?php echo SCTEXT('enter a title for this sms plan')?>" maxlength="50" value="<?php echo $data['pdata']->plan_name ?>" />
												    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Select Routes')?>:</label>
                                                        <div class="col-md-8">
                                                            <select name="proutes[]" id="proutes" class="form-control" data-plugin="select2" multiple data-options="{placeholder:'<?php echo SCTEXT('Select Routes for this plan')?> ...', maximumSelectionLength: 5}">
                                                                <?php
                                                                $selrt = explode(",",$data['pdata']->route_ids);
                                                                foreach($data['rdata'] as $rt){ ?>
                                                                <option <?php if(in_array($rt->id,$selrt)){ ?> selected <?php } ?> value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Plan Type')?>: (<?php echo SCTEXT('cannot be edited')?>)</label>
                                                    <input type="hidden" name="ptype" value="<?php echo $data['pdata']->plan_type ?>"/>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-primary">
                                                                <input id="pt1" <?php if($data['pdata']->plan_type==0){ ?> checked <?php } ?> disabled value="0" type="radio" name="ptype">
                                                                <label for="radio-primary"><?php echo SCTEXT('Volume based pricing')?></label>
                                                                <span class="help-block"><?php echo SCTEXT('Pricing is based primarily on SMS credits purchased. No monthly/yearly subscription fee is charged')?></span>
                                                            </div>
                                                            <div class="radio radio-primary">
                                                                <input id="pt2" <?php if($data['pdata']->plan_type==1){ ?> checked <?php } ?> disabled value="1" type="radio" name="ptype">
                                                                <label for="radio-primary"><?php echo SCTEXT('Subscription based pricing')?></label>
                                                                <span class="help-block"><?php echo SCTEXT('SMS plan is based on monthly/yearly subscription. A fix SMS credit is assigned against the subscription fee, more can be purchased as well.')?></span>
                                                            </div>
                                                            <?php $expireflag = $data['pdata']->plan_type==1 ? unserialize($data['opdata'][0]->opt_data)['expire']: ''; ?>
                                                            <div id="sexp_box" class="m-b-lg checkbox checkbox-primary m-t-xs <?php if($data['pdata']->plan_type==0){ ?> hidden <?php } ?>">
                                                                <input id="sexp-1" name="expireflag" type="checkbox" <?php if($expireflag==1){ ?> checked <?php } ?> value="1">
                                                                <label for="sexp-1">Expire Credits after the cycle ends</label>
                                                            </div>
                                                        </div>
                                            </div>
                                             <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Tax on tariff')?>:</label>
												    <div class="col-md-8">
                                                        <div class="col-md-3 col-sm-4 col-xs-8 input-group">
                                                            <input type="text" name="ptax" id="ptax" class="form-control input-sm" placeholder="e.g. 14.5" maxlength="50" value="<?php echo $data['pdata']->tax ?>" />
                                                            <span class="input-group-addon">%</span>
                                                            <select class="form-control input-sm" name="taxtype">
                                                                <option <?php if($data['pdata']->tax_type=='GT'){ ?> selected <?php } ?> value="GT">GST</option>
                                                                <option <?php if($data['pdata']->tax_type=='VT'){ ?> selected <?php } ?> value="VT">VAT</option>
                                                                <option <?php if($data['pdata']->tax_type=='ST'){ ?> selected <?php } ?> value="ST">Service Tax</option>
                                                                <option <?php if($data['pdata']->tax_type=='SC'){ ?> selected <?php } ?> value="SC">Service Charge</option>
                                                                <option <?php if($data['pdata']->tax_type=='OT'){ ?> selected <?php } ?> value="OT"><?php echo SCTEXT('Other Taxes')?></option>
                                                            </select>

                                                        </div>

												    </div>
                                            </div>


                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-3"><button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Back to SMS Plans')?></button></div>
												<div class="col-md-8 text-right">
													<button class="btn btn-primary nextStep" data-step="1" type="button"><?php echo SCTEXT('Next Step')?> &nbsp; <i class="fa fa-lg fa-chevron-right"></i> </button>

												</div>
											</div>




                                        </div>

                                        <div id="step-2-form" class="block" style="display:none;">
                                            <?php if($data['pdata']->plan_type==0){ ?>
                                            <!-- volume based -->

                                            <?php
                                            $pricing_data = unserialize($data['opdata']->opt_data);
                                            ?>
                                            <div id="volbox">
                                                <div class="panel-heading text-center"><?php echo SCTEXT('Define SMS volumes and SMS rates for the Routes selected in previous step.')?> </div>
                                                <div class="form-group">
                                                    <table id="splan-vol" class="sc_responsive wd100 table row-border order-column ">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-lg-6 col-md-8 col-sm-12"><?php echo SCTEXT('SMS Volume')?></th>
                                                                <?php
                                                                // iterate first item to match the route labels
                                                                $k = 0;
                                                                foreach($pricing_data[0] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        );
                                                                        $k = key($rtObj);

                                                                ?>
                                                                <th id="rhcol<?php echo $ind ?>"><?php echo $rtObj[$k]->title ?></th>
                                                                <?php }} ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td data-colname="SMS Volume">
                                                                    <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[0][from]" readonly placeholder="e.g. 1" value="<?php echo $pricing_data[0]['min'] ?>" /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[0][to]" placeholder="e.g. 10000" value="<?php echo $pricing_data[0]['max'] ?>" /><span class="input-group-addon">SMS</span></div>
                                                                </td>
                                                                <?php
                                                                // iterate first item
                                                                $k=0;
                                                                foreach($pricing_data[0] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj1 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        );
                                                                    $k = key($rtObj1);
                                                                ?>
                                                                <td class="tdcol<?php echo $ind ?>" data-colname="<?php echo $rtObj1[$k]->title ?>"><div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input type="text" class="form-control input-small-sc input-sm" name="price[0][<?php echo $ind ?>]" placeholder="e.g. 0.05" value="<?php echo $val ?>" /><span class="input-group-addon">/sms</span></div></td>
                                                                <?php }} ?>
                                                            </tr>
                                                            <tr>
                                                                <td data-colname="SMS Volume">
                                                                    <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[1][from]" readonly value="<?php echo $pricing_data[1]['min'] ?>"  /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[1][to]" value="<?php echo $pricing_data[1]['max'] ?>" /><span class="input-group-addon">SMS</span></div>
                                                                </td>
                                                                <?php
                                                                // iterate first item
                                                                $k=0;
                                                                foreach($pricing_data[1] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj2 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        );
                                                                    $k = key($rtObj2);
                                                                ?>
                                                                <td class="tdcol<?php echo $ind ?>" data-colname="<?php echo $rtObj2[$k]->title ?>"><div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input type="text" class="form-control input-small-sc input-sm" name="price[1][<?php echo $ind ?>]" placeholder="e.g. 0.05" value="<?php echo $val ?>" /><span class="input-group-addon">/sms</span></div></td>
                                                                <?php }} ?>
                                                            </tr>

                                                            <tr>
                                                                <td data-colname="SMS Volume">
                                                                    <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[2][from]" readonly value="<?php echo $pricing_data[2]['min'] ?>"  /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[2][to]" value="<?php echo $pricing_data[2]['max'] ?>" /><span class="input-group-addon">SMS</span></div>
                                                                </td>
                                                                <?php
                                                                // iterate first item
                                                                $k=0;
                                                                foreach($pricing_data[2] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj3 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        );
                                                                    $k = key($rtObj3);
                                                                ?>
                                                                <td class="tdcol<?php echo $ind ?>" data-colname="<?php echo $rtObj3[$k]->title ?>"><div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input type="text" class="form-control input-small-sc input-sm" name="price[2][<?php echo $ind ?>]" placeholder="e.g. 0.05" value="<?php echo $val ?>" /><span class="input-group-addon">/sms</span></div></td>
                                                                <?php }} ?>
                                                            </tr>
                                                            <tr>
                                                                <td data-colname="SMS Volume">
                                                                    <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[3][from]" readonly value="<?php echo $pricing_data[3]['min'] ?>"  /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[3][to]" value="<?php echo $pricing_data[3]['max'] ?>" /><span class="input-group-addon">SMS</span></div>
                                                                </td>
                                                                <?php
                                                                // iterate first item
                                                                $k=0;
                                                                foreach($pricing_data[3] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj4 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        );
                                                                    $k = key($rtObj4);
                                                                ?>
                                                                <td class="tdcol<?php echo $ind ?>" data-colname="<?php echo $rtObj4[$k]->title ?>"><div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input type="text" class="form-control input-small-sc input-sm" name="price[3][<?php echo $ind ?>]" placeholder="e.g. 0.05" value="<?php echo $val ?>" /><span class="input-group-addon">/sms</span></div></td>
                                                                <?php }} ?>
                                                            </tr>
                                                            <tr>
                                                                <td data-colname="SMS Volume">
                                                                    <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[4][from]" readonly value="<?php echo $pricing_data[4]['min'] ?>"  /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[4][to]" value="<?php echo $pricing_data[4]['max'] ?>" /><span class="input-group-addon">SMS</span></div>
                                                                </td>
                                                                <?php
                                                                // iterate first item
                                                                $k=0;
                                                                foreach($pricing_data[4] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj5 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        );
                                                                    $k = key($rtObj5);
                                                                ?>
                                                                <td class="tdcol<?php echo $ind ?>" data-colname="<?php echo $rtObj5[$k]->title ?>"><div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input type="text" class="form-control input-small-sc input-sm" name="price[4][<?php echo $ind ?>]" placeholder="e.g. 0.05" value="<?php echo $val ?>" /><span class="input-group-addon">/sms</span></div></td>
                                                                <?php }} ?>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php } ?>


                                            <?php if($data['pdata']->plan_type==1){ ?>
                                            <!-- subscription based -->

                                            <div id="subbox" class=" clearfix">
                                                <div class="subbox-head">
                                                    <a data-oid="<?php echo count($data['opdata']) ?>" href="javascript:void(0);" id="add_subplan" class="btn btn-success"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('more plan options')?> </a>
                                                </div>
                                                <hr>
                                                <?php
                                                $ctr = 1;
                                                foreach($data['opdata'] as $opt){
                                                $subdata = unserialize($opt->opt_data);
                                                ?>
                                                <div id="<?php echo $opt->subopt_idn ?>" class="col-md-3 planopts">
                                                    <input type="hidden" name="subopts[]" value="<?php echo $opt->subopt_idn ?>" />
                                                    <?php if($ctr!=1){
                                                    //first item cannot be removable
                                                    ?>
                                                    <a href="javascript:void(0);" class="plan-remove" data-oid="<?php echo $ctr ?>"><i class="fa fa-3x text-danger fa-minus-circle"></i> </a>
                                                    <?php } ?>
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading"><input type="text" class="form-control poptnametxt" data-oid="<?php echo $ctr ?>" placeholder="<?php echo SCTEXT('enter plan option name e.g. PRO')?>" name="poptname[<?php echo $opt->subopt_idn ?>]" value="<?php echo $subdata['name'] ?>" /></div>
                                                        <div class="panel-body">
                                                            <div class="popt-item">
                                                                <select class="form-control" data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('choose payment cycle')?>..'}" name="poptcycle[<?php echo $opt->subopt_idn ?>]" data-placeholder="<?php echo SCTEXT('choose payment cycle')?>..">
                                                                    <option></option>
                                                                    <option <?php if($subdata['cycle']=='m'){ ?> selected <?php } ?> value="m"><?php echo SCTEXT('Monthly')?></option>
                                                                    <option <?php if($subdata['cycle']=='y'){ ?> selected <?php } ?> value="y"><?php echo SCTEXT('Yearly')?></option>
                                                                </select>
                                                            </div>

                                                            <div class="popt-item">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                                    <input type="text" class="form-control" name="poptrate[<?php echo $opt->subopt_idn ?>]" placeholder="<?php echo SCTEXT('enter subscription Fee e.g. 150')?>" value="<?php echo $subdata['fee'] ?>" />
                                                                </div>
                                                            </div>

                                                            <div class="popt-item poptrt">
                                                                <?php foreach($subdata['route_credits'] as $rid=>$cre){

                                                                $rtObj = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($rid) {
                                                                                return $e->id == $rid;
                                                                            }
                                                                        );
                                                                        $k = key($rtObj);
                                                                ?>
                                                                <div class="rtblock<?php echo $rid ?>"><div class="text-white text-center label-info"><?php echo $rtObj[$k]->title ?></div><div class="rtopts"><div class="control-label"><?php echo SCTEXT('SMS Credits')?>:</div> <div class="input-group"><input type="text" class="form-control" name="poptcredits[<?php echo $opt->subopt_idn ?>][<?php echo $rid ?>]" placeholder="e.g. 1000" value="<?php echo $cre ?>" /><span class="input-group-addon"> SMS</span></div><div class="control-label"><?php echo SCTEXT('Additional Purchases')?>:</div><div class="input-group"><span class="input-group-addon"><?php echo Doo::conf()->currency ?></span><input type="text" class="form-control" name="poptaddrate[<?php echo $opt->subopt_idn ?>][<?php echo $rid ?>]" placeholder="e.g. 0.05" value="<?php echo $subdata['route_add_sms_rate'][$rid] ?>" /><span class="input-group-addon"><?php echo SCTEXT('per sms')?></span></div></div></div>
                                                                <?php } ?>
                                                            </div>

                                                            <div class="popt-item">
                                                                <textarea name="poptdesc[<?php echo $opt->subopt_idn ?>]" class="form-control" placeholder="<?php echo SCTEXT('enter a small description e.g. list some features etc.')?>"><?php echo $subdata['description'] ?></textarea>
                                                            </div>


                                                            <div class="popt-item">
                                                                <select class="form-control" data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('choose opt-in method')?>..'}" name="poptsel[<?php echo $opt->subopt_idn ?>]" data-placeholder="<?php echo SCTEXT('choose opt-in method')?>..">
                                                                    <option></option>
                                                                    <option <?php if($subdata['optin']==0){ ?> selected <?php } ?> value="0"><?php echo SCTEXT('Payment & Sign Up')?></option>
                                                                    <option <?php if($subdata['optin']==1){ ?> selected <?php } ?> value="1"><?php echo SCTEXT('Contact form')?></option>
                                                                </select>
                                                            </div>



                                                        </div>
                                                    </div>

                                                </div>
                                                <?php
                                                $ctr++; } ?>

                                            </div>

                                            <?php } ?>
                                            <!-- end of both boxes -->
                                            <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"><button id="" data-step="2" type="button" class="btn btn-default prevStep"><i class="fa fa-lg fa-chevron-left"></i>&nbsp;  <?php echo SCTEXT('Previous Step')?>  </button></div>
                                                    <div class="col-md-8 text-right">
                                                        <button class="btn btn-primary nextStep" id="" data-step="2" type="button"><?php echo SCTEXT('Next Step')?> &nbsp; <i class="fa fa-lg fa-chevron-right"></i> </button>

                                                    </div>
                                                </div>

                                        </div>


                                        <div id="step-3-form" class="block" style="display:none;">
                                            <div class="panel-heading text-center"><?php echo SCTEXT('Choose which features will be available by default to the users signing-up with this plan. You can modify these permissions later for each user as well.')?></div>

                                            <?php if($data['pdata']->plan_type==0){ ?>
                                            <div id="volftbox">

                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                       <?php echo SCTEXT('Volume based pricing')?>
                                                    </div>
                                                    <?php $ft = unserialize($data['opdata']->opt_feats); ?>
                                                    <div class="panel-body">
                                                        <div class="clearfix">
                                                            <div class="col-md-4 splanft-item">
                                                                <div class="widget">
                                                                    <header class="widget-header">
                                                                        <h4 class="widget-title"><?php echo SCTEXT('Allowed SMS Types')?></h4>
                                                                    </header>
                                                                    <hr class="widget-separator">
                                                                    <div class="widget-body">
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[flash]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($ft['flash']=='on'){ ?> checked="checked" <?php } ?> >
                                                                           <label>Flash</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[wap]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($ft['wap']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>WAP-Push</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[vcard]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($ft['vcard']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>VCard</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[unicode]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($ft['unicode']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>Unicode</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[per]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($ft['per']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>Personalised SMS</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 splanft-item">
                                                                <div class="widget">
                                                                    <header class="widget-header">
                                                                        <h4 class="widget-title"><?php echo SCTEXT('Allowed API access')?></h4>
                                                                    </header>
                                                                    <hr class="widget-separator">
                                                                    <div class="widget-body">
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[hapi]" type="checkbox" data-switchery data-color="#ff5b5b" <?php if($ft['hapi']=='on'){ ?> checked="checked" <?php } ?> >
                                                                           <label>HTTP API</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="vftperm[xapi]" type="checkbox" data-switchery data-color="#ff5b5b" <?php if($ft['xapi']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>XML API</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 splanft-item">
                                                    <div class="widget">
                                                        <header class="widget-header">
                                                            <h4 class="widget-title"><?php echo SCTEXT('Allowed Refunds')?></h4>
                                                        </header>
                                                        <hr class="widget-separator">
                                                        <div class="widget-body">
                                                            <?php foreach($data['refunds'] as $ref){ ?>
                                                            <div class="m-b-xs m-r-xl">
                                                                <input data-size="small" name="vftperm[ref][<?php echo $ref->id ?>]" type="checkbox" data-switchery data-color="#10c469" <?php if($ft['ref'][$ref->id]=='on'){ ?> checked="checked" <?php } ?> >
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
                                            <?php } ?>

                                            <?php if($data['pdata']->plan_type==1){ ?>

                                            <div id="subftbox" class="">
                                                <?php
                                                    $ctr = 1;
                                                    foreach($data['opdata'] as $opt){
                                                    $subdata = unserialize($opt->opt_data);
                                                    $feats = $subdata['features'];
                                                ?>
                                                <div id="FT-SU-POPT-<?php echo $ctr ?>" class="panel panel-info">
                                                    <div class="panel-heading">
                                                     <?php echo $subdata['name'] ?>  - PLAN
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="clearfix">
                                                            <div class="col-md-4 splanft-item">
                                                                <div class="widget">
                                                                    <header class="widget-header">
                                                                        <h4 class="widget-title"><?php echo SCTEXT('Allowed SMS Types')?></h4>
                                                                    </header>
                                                                    <hr class="widget-separator">
                                                                    <div class="widget-body">
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][flash]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($feats['flash']=='on'){ ?> checked="checked" <?php } ?> >
                                                                           <label>Flash</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][wap]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($feats['wap']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>WAP-Push</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][vcard]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($feats['vcard']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>VCard</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][unicode]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($feats['unicode']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>Unicode</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][per]" type="checkbox" data-switchery data-color="#35b8e0" <?php if($feats['per']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>Personalised SMS</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 splanft-item">
                                                                <div class="widget">
                                                                    <header class="widget-header">
                                                                        <h4 class="widget-title"><?php echo SCTEXT('Allowed API access')?></h4>
                                                                    </header>
                                                                    <hr class="widget-separator">
                                                                    <div class="widget-body">
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][hapi]" type="checkbox" data-switchery data-color="#ff5b5b" <?php if($feats['hapi']=='on'){ ?> checked="checked" <?php } ?> >
                                                                           <label>HTTP API</label>
                                                                        </div>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][xapi]" type="checkbox" data-switchery data-color="#ff5b5b" <?php if($feats['xapi']=='on'){ ?> checked="checked" <?php } ?> >
                                                                            <label>XML API</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 splanft-item">
                                                                <div class="widget">
                                                                    <header class="widget-header">
                                                                        <h4 class="widget-title"><?php echo SCTEXT('Allowed Refunds')?></h4>
                                                                    </header>
                                                                    <hr class="widget-separator">
                                                                    <div id="reftypebox" class="widget-body">
                                                                        <?php foreach($data['refunds'] as $ref){ ?>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-ref="<?php echo $ref->title ?>" data-refid="<?php echo $ref->id ?>" data-size="small" name="sftperm[<?php echo $opt->subopt_idn ?>][ref][<?php echo $ref->id ?>]" type="checkbox" data-switchery data-color="#10c469" <?php if($feats['ref'][$ref->id]=='on'){ ?> checked="checked" <?php } ?> >
                                                                           <label><?php echo $ref->title ?></label>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <?php $ctr++; } ?>
                                            </div>
                                            <?php } ?>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-3"><button id="" data-step="3" type="button" class="btn btn-default prevStep"><i class="fa fa-lg fa-chevron-left"></i>&nbsp;  <?php echo SCTEXT('Previous Step')?>  </button></div>
												<div class="col-md-8 text-right">
													<button class="btn btn-primary nextStep" id="" data-step="3" type="button"> <i class="fa fa-lg fa-check"></i>&nbsp; <?php echo SCTEXT('Save SMS Plan')?>  </button>

												</div>
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
