
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                 <h3 class="page-title-sc clearfix"><?php echo SCTEXT('View Account')?><small><?php echo $data['user']->name.' ('.$data['user']->email.')' ?></small>
                                <input type="hidden" id="userid" value="<?php echo $data['user']->user_id ?>">
                                <input type="hidden" id="usertype" value="<?php echo $data['user']->account_type ?>">
                                    <span class="dropdown pull-right">
                                      <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><i class="fa fa-large fa-navicon"></i> &nbsp; <?php echo SCTEXT('Actions')?> <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-btn pull-right">
                                                    <li><a class="useraction" data-act="upgradeacc" href="javascript:void(0);" ><i class="fa fa-large fa-user-plus"></i>&nbsp;&nbsp; <?php echo SCTEXT('Upgrade to Reseller')?> </a></li>
                                                    <li><a class="useraction" data-act="changepsw" href="javascript:void(0);" ><i class="fa fa-large fa-key"></i>&nbsp;&nbsp; <?php echo SCTEXT('Change Password'
)?> </a></li>
                                                    <li><a class="useraction" data-act="usersus" href="javascript:void(0);" ><i class="fa fa-large fa-ban"></i>&nbsp;&nbsp; <?php echo SCTEXT('Suspend Account')?> </a></li>
                                                    <li><a class="useraction" data-act="userdel" href="javascript:void(0);" ><i class="fa fa-large fa-trash"></i>&nbsp;&nbsp; <?php echo SCTEXT('Delete Account')?> </a></li>
                                                </ul>

                                    </span>
                                </h3>
                                <hr class="m-t-xs">
                                <?php include('notification.php') ?>

                                <?php include('navpills.php') ?>

                                <hr>
                                <!-- start content -->
                                <?php if($data['user']->account_type=='1' || $data['user']->account_type=='2'){ ?>

                                    <div class="col-md-12">
                                        <h4><?php echo SCTEXT('Add/Deduct Wallet Credits')?></h4>
                                        <hr>
                                        <form class="form-horizontal" method="post" id="w_form">
                                                <input type="hidden" name="userid" id="w_userid" value="<?php echo $data['user']->user_id ?>"/>
                                                <input type="hidden" data-ptax="<?php echo $data['plan']->tax ?>" data-taxtype="<?php echo $data['plan']->tax_type ?>" name="planid" id="w_planid" value="<?php echo intval($data['plan']->id) ?>" />
                                                <input type="hidden" name="walletbalance" id="walletbalance" value="<?php echo floatval($data['wallet']->amount) ?>" />
                                                <input type="hidden" name="billing" value="mccmnc" />
                                                <div class="col-md-6">
                                                    <?php
                                                    $crestr = '<i class="zmdi zmdi-hc-lg zmdi-balance-wallet text-primary m-r-xs"></i><kbd class="text-white bg-primary">'.Doo::conf()->currency.number_format($data['wallet']->amount, 4).'</kbd>';
                                                    ?>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Wallet Balance')?>:</label>
                                                            <div class="col-md-8">
                                                                <span class="help-block clearfix text-info m-b-0"><?php echo $crestr ?></span>
                                                            </div>
                                                     </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Action')?></label>
                                                        <div class="col-md-8">
                                                            <div class="radio radio-primary">
                                                                <input id="wcr" checked="checked" value="1" type="radio" name="waction">
                                                                <label for="wcr"><?php echo SCTEXT('Add Credits') ?></label>
                                                                <span class="help-block"><?php echo SCTEXT('This action will add credits in user wallet. User will make the payment against this invoice.') ?></span>
                                                            </div>
                                                            <div class="radio radio-primary">
                                                                <input id="wcd" value="0" type="radio" name="waction">
                                                                <label for="wcd"><?php echo SCTEXT('Deduct Credits') ?></label>
                                                                <span class="help-block"><?php echo SCTEXT('This action will deduct credits from user wallet. If you have received money from the client, you would have to return that offline.') ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Amount')?></label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><?php echo Doo::conf()->currency ?></span>
                                                                <input type="text" name="mplanscredits" id="mplanscredits" class="form-control cur_inputs" placeholder="e.g. 500">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Account Tax')?>:</label>
                                                            <div class="col-md-8">
                                                                <input type="hidden" id="add_tax" name="add_tax" value="<?php echo floatval($data['deftax']['tax']) ?>">
                                                                <?php echo $data['deftax']['tax'] > 0 ? '<span class="help-block text-primary">'.$data['deftax_str'].'</span>' : "N/A" ?>
                                                            </div>
                                                    </div>


                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total')?>:</label>
                                                                <div class="col-md-8">
                                                                    <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="w_grand_total_amt">0.00</span> <small id="w_all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes')?>)</small></h3>

                                                                </div>
                                                    </div>


                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Remarks')?>:</label>
                                                            <div class="col-md-8">
                                                                <textarea name="d_transremarks" rows="8" class="form-control" placeholder="<?php echo SCTEXT('you can enter remarks for transaction e.g. transaction details or reason for deduction')?> .."></textarea>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Status')?>:</label>
                                                            <div class="col-md-8">

                                                                <div class="radio radio-primary">
                                                                    <input id="inv-p" checked value="1" type="radio" name="invstatus">
                                                                    <label for="inv-p"><?php echo SCTEXT('Complete')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT('The payment associated with this transaction has been made.')?></span>
                                                                </div>

                                                                <div class="radio radio-primary">
                                                                    <input id="inv-d" value="0" type="radio" name="invstatus">
                                                                    <label for="inv-d"><?php echo SCTEXT('Pending')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT("The payment associated with this transaction is due.")?></span>
                                                                </div>


                                                            </div>
                                                        </div>




                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                    <div class="col-md-3"></div>
                                                        <div class="col-md-8">
                                                            <button class="btn btn-primary" type="button" id="subcurfrm"><?php echo SCTEXT('Save changes')?></button>
                                                            <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel')?></button>
                                                        </div>
                                                    </div>
                                                </div>


                                            </form>
                                    </div>

                                <?php } else { ?>


                                <div class="col-md-12">
                                    <ul class="nav nav-pills clearfix m-b-md" role="tablist">
                                        <li role="presentation" class="active"><a data-toggle="tab" aria-controls="credit-ctr" role="tab" class="docnav" href="#credit-ctr"><i class="fa fa-lg fa-plus-circle m-r-xs"></i><?php echo SCTEXT('Add Credits')?></a></li>
                                        <li role="presentation" class=""><a data-toggle="tab" aria-controls="debit-ctr" role="tab" class="docnav" href="#debit-ctr"><i class="fa fa-lg fa-minus-circle m-r-xs"></i><?php echo SCTEXT('Deduct Credits')?></a></li>


                                    </ul>
                                    <hr>



                                    <div class="tab-content">
                                        <div class="tab-pane active clearfix" id="credit-ctr" role="tabpanel">
                                            <form class="form-horizontal" method="post" id="c_form">
                                                <input type="hidden" name="userid" id="userid" value="<?php echo $data['user']->user_id ?>"/>
                                                <input type="hidden" name="planid" id="planid" value="<?php echo intval($data['plan']['id']) ?>" />
                                                <input type="hidden" name="action" value="credit" />
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Select Route')?></label>
                                                        <div class="col-md-8">
                                                            <select data-plugin="select2" data-options="{placeholder:'<?php echo SCTEXT('select route')?> ..'}" class="form-control" name="c_route" id="c_route">
                                                                <option></option>
                                                             <?php foreach ($data['cdata'] as $rt){ ?>
                                                             <option data-price="<?php echo $rt->price ?>" value="<?php echo $rt->route_id ?>"><?php echo $rt->title ?></option>
                                                             <?php } ?>
                                                             </select>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Choose the route you want to assign credits for.')?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Credits Validity')?></label>
                                                        <div class="col-md-8">
                                                            <select data-plugin="select2" class="form-control" name="expiry" id="expiry">
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+1 Month')); ?>"><?php echo SCTEXT('1 Month')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+2 Months')); ?>"><?php echo SCTEXT('2 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+3 Months')); ?>"><?php echo SCTEXT('3 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+4 Months')); ?>"><?php echo SCTEXT('4 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+5 Months')); ?>"><?php echo SCTEXT('5 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+6 Months')); ?>"><?php echo SCTEXT('6 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+7 Months')); ?>"><?php echo SCTEXT('7 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+8 Months')); ?>"><?php echo SCTEXT('8 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+9 Months')); ?>"><?php echo SCTEXT('9 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+10 Months')); ?>"><?php echo SCTEXT('10 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+11 Months')); ?>"><?php echo SCTEXT('11 Months')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+1 Year')); ?>"><?php echo SCTEXT('1 Year')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+2 Years')); ?>"><?php echo SCTEXT('2 Years')?></option>
                                                                <option value="<?php echo date('Y-m-d h:i:s A',strtotime('+3 Years')); ?>"><?php echo SCTEXT('3 Years')?></option>
                                                                <option selected value="<?php echo date('Y-m-d h:i:s A',strtotime('+20 Years')); ?>"><?php echo SCTEXT('Unlimited')?></option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Add Credits')?></label>
                                                        <div class="col-md-8">
                                                             <div class="input-group"><input id="add_cre" class="rtcredits numtxt form-control input-sm" name="add_cre" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">SMS</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('SMS Price')?></label>
                                                        <div class="col-md-8">
                                                             <div class="input-group"><span class="input-group-addon"> <?php echo Doo::conf()->currency ?> </span><input id="c_price" class="rtrates numtxt form-control input-sm" name="c_price" placeholder="e.g. 0.05" value="" type="text"><span class="input-group-addon">per SMS</span></div>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('The price above is current SMS price you are charging for this route to this user. You can change this price anytime based on your preference.')?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Total Amount')?></label>
                                                        <div class="col-md-8">
                                                             <h4 class=" text-primary"><?php echo Doo::conf()->currency ?><span id="total_c_amt">0.00</span> <small id="c_plan_taxes" class="m-l-sm" style="font-size:14px; "></small></h4>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Account Tax')?>:</label>
                                                            <div class="col-md-8">
                                                            <input type="hidden" id="add_tax" name="add_tax" value="<?php echo floatval($data['deftax']['tax']) ?>">
                                                                <?php echo $data['deftax']['tax'] > 0 ? '<span class="help-block text-primary">'.$data['deftax_str'].'</span>' : "N/A" ?>
                                                            </div>
                                                    </div>



                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total')?>:</label>
                                                                <div class="col-md-8">
                                                                    <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="c_grand_total_amt">0.00</span> <small id="c_all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes')?>)</small></h3>
                                                                </div>
                                                    </div>


                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Remarks')?>:</label>
                                                            <div class="col-md-8">
                                                                <textarea name="c_transremarks" rows="8" class="form-control" placeholder="<?php echo SCTEXT('you can enter remarks for transaction e.g. Payment terms, due date information, tax breakdown etc.')?>"></textarea>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Invoice status')?>:</label>
                                                            <div class="col-md-8">
                                                                <div class="radio radio-primary">
                                                                    <input id="inv-d" checked="checked" value="2" type="radio" name="invstatus">
                                                                    <label for="inv-d"><?php echo SCTEXT('Payment Due')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT('User will pay at a later date')?></span>
                                                                </div>
                                                                <div class="radio radio-primary">
                                                                    <input id="inv-p" value="1" type="radio" name="invstatus">
                                                                    <label for="inv-p"><?php echo SCTEXT('Prepaid')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT('User has already paid the amount via cheque, cash etc.')?></span>
                                                                </div>


                                                            </div>
                                                        </div>




                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                    <div class="col-md-4"></div>
                                                        <div class="col-md-8">
                                                            <button class="btn btn-primary" type="button" id="sub_cform"><?php echo SCTEXT('Make Transaction')?></button>
                                                            <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel')?></button>
                                                        </div>
                                                    </div>
                                                </div>


                                            </form>

                                        </div>



                                        <div class="tab-pane clearfix" id="debit-ctr" role="tabpanel">
                                            <form class="form-horizontal" method="post" id="d_form">
                                                <input type="hidden" name="userid" id="d_userid" value="<?php echo $data['user']->user_id ?>"/>
                                                <input type="hidden" name="planid" id="d_planid" value="<?php echo intval($data['plan']['id']) ?>" />
                                                <input type="hidden" name="action" value="debit" />
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Select Route')?></label>
                                                        <div class="col-md-8">
                                                            <select data-plugin="select2" class="form-control" name="d_route" id="d_route" data-options="{placeholder:'<?php echo SCTEXT('select route')?> ..'}">
                                                                <option></option>
                                                             <?php foreach ($data['cdata'] as $rt){ ?>
                                                             <option data-credits="<?php echo number_format($rt->credits) ?>" data-price="<?php echo $rt->price ?>" value="<?php echo $rt->route_id ?>"><?php echo $rt->title ?></option>
                                                             <?php } ?>
                                                             </select>
                                                            <span class="help-block clearfix text-info m-b-0"><?php echo SCTEXT('Available Credits')?>: <b id="rtavcr">0</b> </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Deduct Credits')?></label>
                                                        <div class="col-md-8">
                                                             <div class="input-group"><input id="deduct_cre" class="drtcredits form-control input-sm" name="deductcredits" placeholder="e.g. 5000" value="" type="text"><span class="input-group-addon">SMS</span></div>
                                                            <span class="help-block text-info"><?php echo SCTEXT('Current SMS rate')?> @ <b id="d_rtprc"><?php echo Doo::conf()->currency ?>0.00</b> per SMS</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Refundable Amount')?></label>
                                                        <div class="col-md-8">
                                                             <h4 class=" text-primary"><?php echo Doo::conf()->currency ?><span id="d_total_amt">0.00</span> <small id="d_plan_taxes" class="m-l-sm" style="font-size:14px; "></small></h4>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Tax Charged')?>:</label>
                                                            <div class="col-md-8">
                                                                <div class="col-md-6 col-sm-4 col-xs-8 input-group">
                                                                <input type="text" name="dutax" id="dutax" class="drtcredits form-control input-sm" placeholder="e.g. 14.5" maxlength="50" />
                                                                <span class="input-group-addon">%</span>

                                                                </div>

                                                            </div>
                                                    </div>

                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Grand Total')?>:</label>
                                                                <div class="col-md-8">
                                                                    <h3 class="m-t-0 text-primary"><?php echo Doo::conf()->currency ?><span id="d_grand_total_amt">0.00</span> <small id="d_all_taxes" class="m-l-sm" style="font-size:14px; ">(<?php echo SCTEXT('including all taxes')?>)</small></h3>
                                                                    <span class="help-block"><?php echo SCTEXT('User is entitled to this refund')?></span>
                                                                </div>
                                                    </div>


                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"><?php echo SCTEXT('Remarks')?>:</label>
                                                            <div class="col-md-8">
                                                                <textarea name="d_transremarks" rows="8" class="form-control" placeholder="<?php echo SCTEXT('you can enter remarks for transaction e.g. reason for this deduction')?> .."></textarea>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label col-md-3"><?php echo SCTEXT('Refund Action')?>:</label>
                                                            <div class="col-md-8">

                                                                <div class="radio radio-primary">
                                                                    <input id="inv-p" checked value="1" type="radio" name="invstatus">
                                                                    <label for="inv-p"><?php echo SCTEXT('Already Paid')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT('You have paid back the user offline via cash, cheque etc.')?></span>
                                                                </div>

                                                                <div class="radio radio-primary">
                                                                    <input id="inv-d" value="2" type="radio" name="invstatus">
                                                                    <label for="inv-d"><?php echo SCTEXT('Credit To Wallet')?></label>
                                                                    <span class="help-block"><?php echo SCTEXT("Credit this refund amount to user's wallet")?></span>
                                                                </div>


                                                            </div>
                                                        </div>




                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                    <div class="col-md-3"></div>
                                                        <div class="col-md-8">
                                                            <button class="btn btn-primary" type="button" id="subfrm"><?php echo SCTEXT('Save changes')?></button>
                                                            <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel')?></button>
                                                        </div>
                                                    </div>
                                                </div>


                                            </form>

                                        </div>

                                    </div>


                                </div>

                                <?php } ?>
                                <!-- end content -->

                            </div>
                        </div>
                    </div>
                </div>

            </section>
