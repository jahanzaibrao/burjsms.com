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
                                   <?php if($data['paid']==0){ ?>
                                    <div id="paymentbox" class="text-center">
                                        <h4 class="text-primary m-b-xs"><?php echo SCTEXT('Dear')?> <?php echo $_SESSION['user']['name'].',' ?></h4>
                                        <?php echo SCTEXT('Your order has been confirmed. Your total payable amount is')?>:<br>
                                        <h3 class="text-success m-b-0"><?php echo Doo::conf()->currency.number_format($data['invdata']['grand_total'],2); ?></h3>
                                        <?php if($data['invdata']['plan_tax']!=''){?> <small class="help-block">(<?php echo SCTEXT('including')?> <?php echo $data['invdata']['plan_tax'] ?>)</small> <?php } ?>



                                        <?php if($data['udata']->payment_perm==2 || $data['udata']->payment_perm==0){ ?>

                                        <p><?php echo SCTEXT('To complete your purchase, please pay using our secure checkout below')?>.</p>
                                        <?php if($data['userpg']['channel']=='paypal'){ ?> <div id="paypal-button"></div> <?php } ?>
                                        <?php if($data['userpg']['channel']=='stripe'){ ?> <div class="text-center" id="stripebox">
                                        <fieldset style="width: 40%; margin: 0 auto;">
                                        <div id="payment-request-button">
                                        <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                            <div class="panel panel-primary docmgr-files">
                                                <div class="panel-heading p-sm"><span class="">Pay with Card</span></div>
                                                <div class="panel-body">
                                                <input class="form-control m-b-sm" name="cardemail" id="cardemail" placeholder="enter your email...">
                                                <div id="card-element" class="form-control m-b-sm" ></div>

                                                <input class="form-control m-b-sm" name="cardname" id="cardname" placeholder="name on the card..">
                                                <p id="card-error" class="text-danger" role="alert"></p>
                                                <button id="stripebtn" class="btn btn-primary m-t-sm"><div class="spinner hidden" id="spinner"></div><i class="fas fa-credit-card m-r-xs"></i> <span id="button-text">Proceed to Pay</span></button>
                                                <div class="text-right fz-xs text-default">Powered By Stripe</div>
                                                </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <?php } ?>
                                        <?php if($data['userpg']['channel']=='paystack'){ ?>
                                            <form id="paymentForm">

                                                <div class="form-submit">
                                                    <button type="submit" class="btn btn-primary" onclick="payWithPaystack()"> Proceed to Online Payment </button>
                                                    <span class="help-block text-danger">(A convenience fee of <b>30 naira</b> will be charged additionally)</span>
                                                </div>

                                            </form>

                                        <?php } ?>
                                        <?php if($data['udata']->payment_perm==2) { ?>

                                        <h4>OR</h4>
                                        <hr>
                                        <?php } ?>

                                        <?php if($data['udata']->payment_perm==2 || $data['udata']->payment_perm==1){ ?>
                                            <p>
                                            <?php echo SCTEXT('Make the payment to the bank account below and respond with the payment confirmation details in comment section of the invoice')?>.
                                            </p>
                                            <p class="m-t-sm">
                                            <fieldset style="width: 40%; margin: 0 auto;">
                                            <div class="panel panel-primary bg-primary text-white">
                                                <div class="panel-body">
                                                    <?php echo preg_replace(
              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
              "<a class='text-dark' target='_blank' href=\"\\0\">\\0</a>",
              nl2br($data['payment_details']['bank_details'])) ?>
                                                </div>
                                            </div>
                                            </fieldset>
                                            </p>

                                        <?php }} ?>

                                    </div>
                                   <?php }else{ ?>
                                    <div class="text-center">
                                    <h4 class="m-b-md"><i class="fa fa-check-circle fa-3x text-success"></i></h4>This invoice has already been paid.
                                    </div>

                                   <?php } ?>
                                <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
