
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add Override Rule')?><small><?php echo SCTEXT('add new content override rule')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <form class="form-horizontal" method="post" id="add_orule_frm" action="">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Rule Name')?>:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" placeholder="enter a title for this override rule . . . ." name="orname" id="orname">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Templates')?>:</label>
                                                    <div class="col-md-8" id="templatebox">
                                                        <div class="clearfix text-right m-b-sm">
                                                            <button class="btn btn-sm btn-success" type="button" id="addtmp"><i class="fa fa-plus fa-lg m-r-xs"></i> Add Template</button>
                                                        </div>
                                                        <div class="p-sm panel m-b-xs bg-info text-white m-b-lg">
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
                                                                                        <option value="0">Any</option>
                                                                                        <option value="equal">Equals to</option>
                                                                                        <option value="start">Starts With</option>
                                                                                        <option value="end">Ends With</option>
                                                                                        <option value="has">Contains</option>
                                                                                        <option value="nothave">Does not contain</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderinput[]" type="text" class="form-control input-sm" placeholder="e.g. WEBSMS">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <select name="senderreplace[]" class="form-control input-sm">
                                                                                        <option value="0">No Action</option>
                                                                                        <option value="replace">Replace With</option>
                                                                                        <option value="prepend">Add Prefix</option>
                                                                                        <option value="append">Append Suffix</option>
                                                                                        <option value="remove">Remove Prefix</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="senderreplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. AUTOWEB">
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
                                                                                        <option value="0">Any</option>
                                                                                        <option value="equal">Equals to</option>
                                                                                        <option value="start">Starts With</option>
                                                                                        <option value="end">Ends With</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="mobileinput[]" type="text" class="form-control input-sm" placeholder="e.g. 243812556677">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <select name="mobilereplace[]" class="form-control input-sm">
                                                                                        <option value="0">No Action</option>
                                                                                        <option value="reject">Reject Number</option>
                                                                                        <option value="prepend">Append Prefix</option>
                                                                                        <option value="append">Append Suffix</option>
                                                                                        <option value="remove">Remove Prefix</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="mobilereplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. 243">
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
                                                                                        <option value="0">Any</option>
                                                                                        <option value="equal">Equals to</option>
                                                                                        <option value="start">Starts With</option>
                                                                                        <option value="end">Ends With</option>
                                                                                        <option value="has">Contains</option>
                                                                                        <option value="nothave">Does not contain</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="textinput[]" type="text" class="form-control input-sm" placeholder="e.g. karl">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <select name="textreplace[]" class="form-control input-sm">
                                                                                        <option value="0">No Action</option>
                                                                                        <option value="replace">Replace With</option>
                                                                                        <option value="prepend">Add Prefix</option>
                                                                                        <option value="append">Append Suffix</option>
                                                                                        <option value="remove">Remove Prefix</option>
                                                                                    </select>
                                                                                </span>
                                                                                <span class="input-group-addon">
                                                                                    <input name="textreplaceinput[]" type="text" class="form-control input-sm" placeholder="e.g. k@rl">
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </tbody>

                                                            </table>
                                                        </div>
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