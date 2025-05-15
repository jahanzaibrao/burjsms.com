<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Purchase Credits')?><small><?php echo SCTEXT('buy SMS credits here')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                   <?php if(Doo::conf()->force_offline_payment==1){ ?>

                                            <p class="m-t-sm">
                                            <fieldset style="width: 60%; margin: 0 auto;">
                                            <div class="panel panel-primary bg-primary text-white">
                                                <div class="panel-body">
                                                    <?php echo nl2br($data['payment_details']['bank_details']) ?>
                                                </div>
                                            </div>
                                            </fieldset>
                                            </p>
                                    <?php } else { ?>
                                    <form class="form-horizontal" method="post" id="bs_form" action="">
                                        <input type="hidden" id="tax" value="<?php echo $data['tax'] ?>"/>
                                        <input type="hidden" id="taxtype" value="<?php echo $data['taxtype'] ?>"/>
                                        <?php if($data['planid']!=0){ ?>
                                        <input type="hidden" id="ptype" value="<?php echo $data['plan_type'] ?>" />
                                        <input type="hidden" id="planid" value="<?php echo $data['planid'] ?>" />
                                        <?php } ?>
                                        <?php $wallet = $_SESSION['credits']['wallet']['amount']; ?>
                                        <input type="hidden" id="walbal" value="<?php echo $wallet; ?>" />
                                        <div class="col-md-6">

                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Route')?>:</label>
												    <div class="col-md-8">
													   <select class="form-control" name="route" id="routesel" data-plugin="select2">
                                                           <?php foreach($data['routes'] as $rt){ ?>
                                                           <option data-crt="<?php echo $rt['price'] ?>" data-crval="<?php echo $rt['validity'] ?>" data-acr="<?php echo $rt['credits'] ?>" <?php if($rt['id']==$_SESSION['settings']['def_route']){ ?> selected <?php } ?> value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                                           <?php } ?>
                                                        </select>
                                                        <span class="help-block clearfix text-info m-b-0"><?php echo SCTEXT('Available Credits')?>: <b id="rtavcr">0</b>
                                                            <span id="smsrate" style="float: right;">
                                                                $0.02/sms
                                                            </span>
                                                        </span>
												    </div>
											 </div>

                                            <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('SMS Credits')?>:</label>
												    <div class="col-md-8">
													   <input name="smscredits" type="text" id="smscredits" value="" placeholder="e.g.500 000" class="form-control" />
                                                        <span class="help-block clearfix text-info m-b-0"><?php echo SCTEXT('Enter SMS credits you want to buy')?></span>
												    </div>
								            </div>

                                        </div>


                                        <div class="col-md-6">

                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total')?>:</label>
                                                        <div class="col-md-8">
                                                            <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="grand_total_amt">0.00</span> <small id="all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes')?>)</small></h3>
                                                        </div>
                                            </div>


                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Wallet credits')?>:</label>
                                                        <div class="col-md-8">
                                                            <div class="checkbox checkbox-primary">
                                                                <input name="useWallet" type="checkbox" id="cb-1" checked="checked">
                                                                <label for="cb-1"><?php echo SCTEXT('Use your')?> <b><?php echo Doo::conf()->currency.number_format($wallet,2) ?></b> <?php echo SCTEXT('wallet balance')?> </label>
                                                            </div>
                                                            <span class="help-block"><?php echo SCTEXT('Remaining balance after this payment')?> <span id="remwal"><?php echo Doo::conf()->currency.'0' ?></span></span>
                                                        </div>
                                            </div>

                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Total Payable')?>:</label>
                                                        <div class="col-md-8">
                                                            <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="total_amt_payable">0.00</span> <small id="all_taxes_payable" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes')?>)</small></h3>
                                                        </div>
                                            </div>





                                            <div class="form-group">
                                                    <label class="control-label col-md-3"></label>
                                                        <div class="col-md-8">
                                                            <button class="btn btn-primary" id="proceedtopay" type="button"><?php echo SCTEXT('Confirm your order')?><i class="m-l-sm fa-lg fa fa-chevron-circle-right "></i></button>
                                                        </div>
                                            </div>

                                        </div>
                                    </form>
                                    <?php } ?>
                                <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
