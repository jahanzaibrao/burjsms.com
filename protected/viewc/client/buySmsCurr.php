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
                                        <input type="hidden" name="currencyaccount" value="1"/>
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
												<label class="control-label col-md-3"><?php echo SCTEXT('Wallet Credits')?>:</label>
												    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                             <input name="walletcredits" type="text" id="walletcredits" value="" placeholder="e.g.1000" class="form-control" />
                                                        </div>

                                                        <span class="help-block clearfix text-info m-b-0"><?php echo SCTEXT('Enter the amount you want to add to your wallet')?></span>
												    </div>
								            </div>

                                        </div>


                                        <div class="col-md-6">



                                            <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Total Payable')?>:</label>
                                                        <div class="col-md-8">
                                                            <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="total_amt_payable">0.00</span> <small id="all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes')?>)</small></h3>
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
