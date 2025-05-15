<!-- tabs nav area -->

<ul class="nav nav-pills">
    <li <?php if($data['current_page']=='view_account'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserAccount/'.$data['user']->user_id ?>"><?php echo SCTEXT('Overview')?></a>
    </li>
    <li <?php if($data['current_page']=='va_rset'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserRouteSettings/'.$data['user']->user_id ?>"><?php echo $data['user']->account_type=='1'? SCTEXT('SMS Plan') : SCTEXT('Route Settings')?></a>
    </li>
    <li <?php if($data['current_page']=='va_usid'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserSenderIds/'.$data['user']->user_id ?>"><?php echo SCTEXT('Sender ID')?></a>
    </li>
    <li <?php if($data['current_page']=='va_utemps'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserTemplates/'.$data['user']->user_id ?>"><?php echo SCTEXT('Templates')?></a>
    </li>
    <li <?php if($data['current_page']=='va_sentsms' || $data['current_page']=='va_dlrdetails'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserDlrSummary/'.$data['user']->user_id ?>"><?php echo SCTEXT('Sent SMS')?></a>
    </li>
    
<?php if( $_SESSION['user']['subgroup']=='admin' || $_SESSION['user']['subgroup']=='reseller' || ($_SESSION['user']['subgroup']=='staff' && $_SESSION['staffRights']['user']['transaction']=='on') ){ ?>
    
    <li <?php if($data['current_page']=='va_utrans'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'makeAccountTransaction/'.$data['user']->user_id ?>"><?php echo SCTEXT('Credit/Debit Account')?></a>
    </li>
<?php } ?>    
    

<?php if( $_SESSION['user']['subgroup']=='admin' || $_SESSION['user']['subgroup']=='reseller' || ($_SESSION['user']['subgroup']=='staff' && $_SESSION['staffRights']['user']['logs']=='on') ){ ?>    
    
    <li <?php if($data['current_page']=='va_tran_history'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserTransactions/'.$data['user']->user_id ?>"><?php echo SCTEXT('Transactions')?></a>
    </li>
    <li <?php if($data['current_page']=='va_crelog'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserCreditLog/'.$data['user']->user_id ?>"><?php echo SCTEXT('Credit Log')?></a>
    </li>
<?php } ?>    
    
    
    <?php if( $_SESSION['user']['subgroup']=='admin' || ($_SESSION['user']['subgroup']=='staff' && $_SESSION['staffRights']['user']['set']=='on') ){ ?>
    <li <?php if($data['current_page']=='va_uset'){ ?> class="active" <?php } ?>>
        <a href="<?php echo Doo::conf()->APP_URL.'viewUserAccountSettings/'.$data['user']->user_id ?>"><?php echo SCTEXT('Account Settings')?></a>
    </li>
    <?php } ?>
</ul>
<input type="hidden" id="page_family" value="view_account" />


<!-- reset password box -->
 <div id="resetPassBox" class="modal fade" role="dialog">
                                                      <div class="modal-dialog">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Reset Password</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                                <div class="form-group">
                                                                        <label class="control-label col-md-3">Enter Password:</label>
                                                                            <div class="col-md-8">
                                                                            <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="upass" id="upass" class="form-control" placeholder="enter password..." maxlength="100" />
                                                                            </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class="control-label col-md-3">Retype Password:</label>
                                                                            <div class="col-md-8">
                                                                            <input data-strength="<?php echo Doo::conf()->password_strength ?>" type="password" name="upass2" id="upass2" class="form-control" placeholder="enter password again..." maxlength="100" />
                                                                                <span id="pass-err" class="help-block text-danger"></span>
                                                                                <span id="pass-help" class="help-block text-primary">
                                                                                    <?php switch(Doo::conf()->password_strength){
                                                                                       case 'weak':
                                                                                       echo 'Password length should be minimum 6 characters.';
                                                                                       break;

                                                                                       case 'average':
                                                                                       echo 'Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.';
                                                                                       break;

                                                                                       case 'strong':
                                                                                       echo 'Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.';
                                                                                       break;
                                                                                    }
                                                                                    ?>
                                                                                </span>
                                                                            </div>
                                                                </div>
                                                          </div>
                                                          <div class="modal-footer">
                                                              <button type="button" class="btn btn-info" id="resetPassSubmit">Save Changes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                          </div>
                                                        </div>

                                                      </div>
                                                    </div>
                                                    
<!-- end box -->




<!-- end of tabs nav area -->
            