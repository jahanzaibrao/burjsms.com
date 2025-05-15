<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('New SMS Plan') ?><small><?php echo SCTEXT('add a new sms plan') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->
                                <form class="form-horizontal form-wizard" method="post" id="add_splan_form" action="">
                                    <div class="wizard-nav">
                                        <div class="nav-element col-md-4 step-1 active">
                                            <h4 class="nav-step-title"><?php echo SCTEXT('Step 1') ?>: <?php echo SCTEXT('SMS Plan Info') ?></h4>
                                            <span class="nav-step-desc"><?php echo SCTEXT('basic info about sms plan') ?></span>
                                        </div>
                                        <div class="nav-element col-md-4 step-2 ">
                                            <h4 class="nav-step-title"><?php echo SCTEXT('Step 2') ?>: <?php echo SCTEXT('Plan options') ?></h4>
                                            <span class="nav-step-desc"><?php echo SCTEXT('define sms rates') ?></span>
                                        </div>
                                        <div class="nav-element col-md-4 step-3 ">
                                            <h4 class="nav-step-title"><?php echo SCTEXT('Step 3') ?>: <?php echo SCTEXT('App Features') ?></h4>
                                            <span class="nav-step-desc"><?php echo SCTEXT('features available') ?></span>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div id="step-1-form" class="block">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Plan Name') ?>:</label>
                                            <div class="col-md-8">
                                                <input type="text" name="pname" id="pname" class="form-control" placeholder="<?php echo SCTEXT('enter a title for this sms plan') ?>" maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Select Routes') ?>:</label>
                                            <div class="col-md-8">
                                                <select name="proutes[]" id="proutes" class="form-control" data-plugin="select2" multiple data-options="{placeholder:'<?php echo SCTEXT('Select Routes for this plan') ?> ...', maximumSelectionLength: 5}">
                                                    <?php foreach ($data['rdata'] as $rt) { ?>
                                                        <option value="<?php echo $rt->id ?>"><?php echo $rt->title ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Plan Type') ?>:</label>
                                            <div class="col-md-8">
                                                <div class="radio radio-primary">
                                                    <input id="pt1" checked="checked" value="0" type="radio" name="ptype">
                                                    <label for="radio-primary"><?php echo SCTEXT('Volume based pricing') ?></label>
                                                    <span class="help-block"><?php echo SCTEXT('Pricing is based primarily on SMS credits purchased. No monthly/yearly subscription fee is charged') ?></span>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input id="pt2" value="1" type="radio" name="ptype">
                                                    <label for="radio-primary"><?php echo SCTEXT('Subscription based pricing') ?></label>
                                                    <span class="help-block"><?php echo SCTEXT('SMS plan is based on monthly/yearly subscription. A fix SMS credit is assigned against the subscription fee, more can be purchased as well.') ?></span>

                                                </div>
                                                <div id="sexp_box" class="checkbox checkbox-primary m-t-xs hidden m-b-lg">
                                                    <input id="sexp-1" name="expireflag" type="checkbox" value="1">
                                                    <label for="sexp-1">Expire Credits after the cycle ends</label>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo SCTEXT('Tax on tariff') ?>:</label>
                                            <div class="col-md-8">
                                                <div class="col-md-3 col-sm-4 col-xs-8 input-group">
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
                                            <div class="col-md-3"><button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Back to SMS Plans') ?></button></div>
                                            <div class="col-md-8 text-right">
                                                <button class="btn btn-primary nextStep" data-step="1" type="button"><?php echo SCTEXT('Next Step') ?> &nbsp; <i class="fa fa-lg fa-chevron-right"></i> </button>

                                            </div>
                                        </div>

                                    </div>

                                    <div id="step-2-form" class="block" style="display:none;">
                                        <!-- volume based -->
                                        <div id="volbox">
                                            <div class="panel-heading text-center"><?php echo SCTEXT('Define SMS volumes and SMS rates for the Routes selected in previous step.') ?> </div>
                                            <div class="form-group">
                                                <table id="splan-vol" class="sc_responsive wd100 table row-border order-column ">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-lg-6 col-md-8 col-sm-12"><?php echo SCTEXT('SMS Volume') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td data-colname="SMS Volume">
                                                                <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[0][from]" readonly placeholder="e.g. 1" value="1" /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[0][to]" placeholder="e.g. 10000" /><span class="input-group-addon">SMS</span></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td data-colname="SMS Volume">
                                                                <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[1][from]" readonly /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[1][to]" /><span class="input-group-addon">SMS</span></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td data-colname="SMS Volume">
                                                                <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[2][from]" readonly /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[2][to]" /><span class="input-group-addon">SMS</span></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td data-colname="SMS Volume">
                                                                <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[3][from]" readonly /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[3][to]" /><span class="input-group-addon">SMS</span></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td data-colname="SMS Volume">
                                                                <div class="input-group tdvol col-lg-6 col-md-8 col-sm-12"><input type="text" class="form-control input-small-sc-r input-sm bg-white" name="range[4][from]" readonly /><span class="input-group-addon"> to </span><input type="text" class="form-control input-small-sc-r input-sm rangeto" name="range[4][to]" /><span class="input-group-addon">SMS</span></div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- subscription based -->

                                        <div id="subbox" class="hidden clearfix">
                                            <div class="subbox-head">
                                                <a data-oid="1" href="javascript:void(0);" id="add_subplan" class="btn btn-success"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('more plan options') ?> </a>
                                            </div>
                                            <hr>

                                            <div id="SU-POPT-1" class="col-md-3 planopts">
                                                <input type="hidden" name="subopts[]" value="SU-POPT-1" />
                                                <div class="panel panel-info">
                                                    <div class="panel-heading"><input type="text" class="form-control poptnametxt" data-oid="1" placeholder="<?php echo SCTEXT('enter plan option name e.g. PRO') ?>" name="poptname[SU-POPT-1]" /></div>
                                                    <div class="panel-body">
                                                        <div class="popt-item">
                                                            <select class="form-control" data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('choose payment cycle') ?>..'}" name="poptcycle[SU-POPT-1]" data-placeholder="<?php echo SCTEXT('choose payment cycle') ?>..">
                                                                <option></option>
                                                                <option value="m"><?php echo SCTEXT('Monthly') ?></option>
                                                                <option value="y"><?php echo SCTEXT('Yearly') ?></option>
                                                            </select>
                                                        </div>

                                                        <div class="popt-item">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                                <input type="text" class="form-control" name="poptrate[SU-POPT-1]" placeholder="<?php echo SCTEXT('enter subscription Fee e.g. 150') ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="popt-item poptrt">

                                                        </div>

                                                        <div class="popt-item">
                                                            <textarea name="poptdesc[SU-POPT-1]" class="form-control" placeholder="<?php echo SCTEXT('enter a small description e.g. list some features etc.') ?>"></textarea>
                                                        </div>


                                                        <div class="popt-item">
                                                            <select class="form-control" data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('choose opt-in method') ?>..'}" name="poptsel[SU-POPT-1]" data-placeholder="<?php echo SCTEXT('choose opt-in method') ?>..">
                                                                <option></option>
                                                                <option value="0"><?php echo SCTEXT('Payment & Sign Up') ?></option>
                                                                <option value="1"><?php echo SCTEXT('Contact form') ?></option>
                                                            </select>
                                                        </div>



                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- end of both boxes -->
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-3"><button id="" data-step="2" type="button" class="btn btn-default prevStep"><i class="fa fa-lg fa-chevron-left"></i>&nbsp; <?php echo SCTEXT('Previous Step') ?> </button></div>
                                            <div class="col-md-8 text-right">
                                                <button class="btn btn-primary nextStep" id="" data-step="2" type="button"><?php echo SCTEXT('Next Step') ?> &nbsp; <i class="fa fa-lg fa-chevron-right"></i> </button>

                                            </div>
                                        </div>

                                    </div>


                                    <div id="step-3-form" class="block" style="display:none;">
                                        <div class="panel-heading text-center"><?php echo SCTEXT('Choose which features will be available by default to the users signing-up with this plan. You can modify these permissions later for each user as well.') ?></div>


                                        <div id="volftbox">

                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <?php echo SCTEXT('Volume based pricing') ?>
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
                                                                        <input data-size="small" name="vftperm[flash]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>Flash</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="vftperm[wap]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>WAP-Push</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="vftperm[vcard]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>VCard</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="vftperm[unicode]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>Unicode</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="vftperm[per]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
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
                                                                        <input data-size="small" name="vftperm[hapi]" type="checkbox" data-switchery data-color="#ff5b5b" checked="checked">
                                                                        <label>HTTP API</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="vftperm[xapi]" type="checkbox" data-switchery data-color="#ff5b5b" checked="checked">
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
                                                                            <input data-size="small" name="vftperm[ref][<?php echo $ref->id ?>]" type="checkbox" data-switchery data-color="#10c469" checked="checked">
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


                                        <div id="subftbox" class="hidden">
                                            <div id="FT-SU-POPT-1" class="panel panel-info">
                                                <div class="panel-heading">
                                                    - PLAN
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
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][flash]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>Flash</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][wap]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>WAP-Push</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][vcard]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>VCard</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][unicode]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
                                                                        <label>Unicode</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][per]" type="checkbox" data-switchery data-color="#35b8e0" checked="checked">
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
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][hapi]" type="checkbox" data-switchery data-color="#ff5b5b" checked="checked">
                                                                        <label>HTTP API</label>
                                                                    </div>
                                                                    <div class="m-b-xs m-r-xl">
                                                                        <input data-size="small" name="sftperm[SU-POPT-1][xapi]" type="checkbox" data-switchery data-color="#ff5b5b" checked="checked">
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
                                                                <div id="reftypebox" class="widget-body">
                                                                    <?php foreach ($data['refunds'] as $ref) { ?>
                                                                        <div class="m-b-xs m-r-xl">
                                                                            <input data-ref="<?php echo $ref->title ?>" data-refid="<?php echo $ref->id ?>" data-size="small" name="sftperm[SU-POPT-1][ref][<?php echo $ref->id ?>]" type="checkbox" data-switchery data-color="#10c469" checked="checked">
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

                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-3"><button id="" data-step="3" type="button" class="btn btn-default prevStep"><i class="fa fa-lg fa-chevron-left"></i>&nbsp; <?php echo SCTEXT('Previous Step') ?> </button></div>
                                            <div class="col-md-8 text-right">
                                                <button class="btn btn-primary nextStep" id="" data-step="3" type="button"> <i class="fa fa-lg fa-check"></i>&nbsp; <?php echo SCTEXT('Save SMS Plan') ?> </button>

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