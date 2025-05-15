
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Edit Override Rule')?><small><?php echo SCTEXT('modify override rule parameters and templates')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <form class="form-horizontal" method="post" id="add_orule_frm" action="">
                                                <input type="hidden" name="ruleid" value="<?php echo $data['rule']->id ?>">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Rule Name')?>:</label>
                                                    <div class="col-md-8">
                                                        <input value="<?php echo $data['rule']->rule_title ?>" type="text" class="form-control" placeholder="enter a title for this override rule . . . ." name="orname" id="orname">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Templates')?>:</label>
                                                    <div class="col-md-8" id="templatebox">
                                                        <div class="clearfix text-right m-b-sm">
                                                            <button class="btn btn-sm btn-success" type="button" id="addtmp"><i class="fa fa-plus fa-lg m-r-xs"></i> Add Template</button>
                                                        </div>
                                                        <?php 
                                                            $sender_rules = json_decode($data['rule']->sender_rules);
                                                            $msisdn_rules = json_decode($data['rule']->msisdn_rules);
                                                            $mtext_rules = json_decode($data['rule']->mtext_rules);
                                                            $i=0;
                                                            foreach($sender_rules as $index=>$vals){
                                                        ?>
                                                        <div class="p-sm panel m-b-xs bg-info text-white m-b-lg" style="position: relative;">
                                                            <?php if($i!=0){ echo ' <a href="javascript:void(0);" class="plan-remove rmv_temp"><i class="fa fa-3x text-danger fa-minus-circle"></i> </a>'; } ?>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Match</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Sender ID</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="sendermatch[]" class="form-control input-sm">
                                                            <option <?php if($sender_rules[$index]->matchAction=="0"){ ?> selected <?php } ?> value="0">Any</option>
                                                                                        <option <?php if($sender_rules[$index]->matchAction=="equal"){ ?> selected <?php } ?> value="equal">Equals to</option>
                                                                                        <option <?php if($sender_rules[$index]->matchAction=="start"){ ?> selected <?php } ?> value="start">Starts With</option>
                                                                                        <option <?php if($sender_rules[$index]->matchAction=="end"){ ?> selected <?php } ?> value="end">Ends With</option>
                                                                                        <option <?php if($sender_rules[$index]->matchAction=="has"){ ?> selected <?php } ?> value="has">Contains</option>
                                                                                        <option <?php if($sender_rules[$index]->matchAction=="nothave"){ ?> selected <?php } ?> value="nothave">Does not contain</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="e.g. WEBSMS" value="<?php echo $sender_rules[$index]->matchInput ?>">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <select name="senderreplace[]" class="form-control input-sm">
                                                                                        <option <?php if($sender_rules[$index]->overrideAction=="0"){ ?> selected <?php } ?> value="0">No Action</option>
                                                                                        <option <?php if($sender_rules[$index]->overrideAction=="replace"){ ?> selected <?php } ?> value="replace">Replace With</option>
                                                                                        <option <?php if($sender_rules[$index]->overrideAction=="prepend"){ ?> selected <?php } ?> value="prepend">Add Prefix</option>
                                                                                        <option <?php if($sender_rules[$index]->overrideAction=="append"){ ?> selected <?php } ?> value="append">Append Suffix</option>
                                                                                        <option <?php if($sender_rules[$index]->overrideAction=="remove"){ ?> selected <?php } ?> value="remove">Remove Prefix</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. AUTOWEB" value="<?php echo $sender_rules[$index]->overrideInput ?>">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Mobile No.</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="mobilematch[]" class="form-control input-sm">
                                                                                        <option <?php if($msisdn_rules[$index]->matchAction=="0"){ ?> selected <?php } ?> value="0">Any</option>
                                                                                        <option <?php if($msisdn_rules[$index]->matchAction=="equal"){ ?> selected <?php } ?> value="equal">Equals to</option>
                                                                                        <option <?php if($msisdn_rules[$index]->matchAction=="start"){ ?> selected <?php } ?> value="start">Starts With</option>
                                                                                        <option <?php if($msisdn_rules[$index]->matchAction=="end"){ ?> selected <?php } ?> value="end">Ends With</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="mobileinput[]" type="text" class="form-control input-sm" placeholder="e.g. 243812556677" value="<?php echo $msisdn_rules[$index]->matchInput ?>">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <select name="mobilereplace[]" class="form-control input-sm">
                                                                                        <option <?php if($msisdn_rules[$index]->overrideAction=="0"){ ?> selected <?php } ?> value="0">No Action</option>
                                                                                        <option <?php if($msisdn_rules[$index]->overrideAction=="reject"){ ?> selected <?php } ?> value="reject">Reject Number</option>
                                                                                        <option <?php if($msisdn_rules[$index]->overrideAction=="prepend"){ ?> selected <?php } ?> value="prepend">Append Prefix</option>
                                                                                        <option <?php if($msisdn_rules[$index]->overrideAction=="append"){ ?> selected <?php } ?> value="append">Append Suffix</option>
                                                                                        <option <?php if($msisdn_rules[$index]->overrideAction=="remove"){ ?> selected <?php } ?> value="remove">Remove Prefix</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="mobilereplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. 243" value="<?php echo $msisdn_rules[$index]->overrideInput ?>">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">SMS Text</span>
                                                                                <span class="input-group-addon">
                                                                                    <select name="textmatch[]" class="form-control input-sm">
                                                                                        <option <?php if($mtext_rules[$index]->matchAction=="0"){ ?> selected <?php } ?> value="0">Any</option>
                                                                                        <option <?php if($mtext_rules[$index]->matchAction=="equal"){ ?> selected <?php } ?> value="equal">Equals to</option>
                                                                                        <option <?php if($mtext_rules[$index]->matchAction=="start"){ ?> selected <?php } ?> value="start">Starts With</option>
                                                                                        <option <?php if($mtext_rules[$index]->matchAction=="end"){ ?> selected <?php } ?> value="end">Ends With</option>
                                                                                        <option <?php if($mtext_rules[$index]->matchAction=="has"){ ?> selected <?php } ?> value="has">Contains</option>
                                                                                        <option <?php if($mtext_rules[$index]->matchAction=="nothave"){ ?> selected <?php } ?> value="nothave">Does not contain</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="textinput[]" type="text" class="form-control input-sm" placeholder="e.g. karl" value="<?php echo $mtext_rules[$index]->matchInput ?>">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <select name="textreplace[]" class="form-control input-sm">
                                                                                        <option <?php if($mtext_rules[$index]->overrideAction=="0"){ ?> selected <?php } ?> value="0">No Action</option>
                                                                                        <option <?php if($mtext_rules[$index]->overrideAction=="replace"){ ?> selected <?php } ?> value="replace">Replace With</option>
                                                                                        <option <?php if($mtext_rules[$index]->overrideAction=="prepend"){ ?> selected <?php } ?> value="prepend">Add Prefix</option>
                                                                                        <option <?php if($mtext_rules[$index]->overrideAction=="append"){ ?> selected <?php } ?> value="append">Append Suffix</option>
                                                                                        <option <?php if($mtext_rules[$index]->overrideAction=="remove"){ ?> selected <?php } ?> value="remove">Remove Prefix</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="textreplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. k@rl" value="<?php echo $mtext_rules[$index]->overrideInput ?>">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </tbody>

                                                            </table>
                                                        </div>

                                                            <?php $i++; } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button id="save_changes" class="btn btn-primary" type="button">Save changes</button>
                                                        <button id="bk" class="btn btn-default" type="button">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                     
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>