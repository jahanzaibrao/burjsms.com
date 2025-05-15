<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-9 p-l-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget">
                                    <div class="widget-body">
                                        <div id="s_traffic_summary" style="height: 200px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="widget">
                                    <div class="widget-header"><h4 class="widget-title">Channels</h4></div>
                                    <hr class="widget-separator">
                                    <div class="widget-body">
                                        <div id="s_channels" style="height: 200px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="widget">
                                    <div class="widget-header clearfix">
                                        <h4 class="widget-title pull-left">DLR Summary</h4>
                                        <span class="pull-right"><i id="print_dlr_summary" class="fas fa-download fa-lg text-primary pointer"></i></span>
                                    </div>
                                    <hr class="widget-separator">
                                    <div style="overflow: auto; max-height: 230px; padding-bottom:40px;" class="widget-body p-t-0">
                                        <table id="dlrsummary" class="wd100 p-sm table-responsive table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>DLR</th>
                                                    <th>Total SMS</th>
                                                    <th>Credits</th>
                                                    <th>Cost (<?php echo trim(Doo::conf()->currency) ?>)</th>
                                                    <th>Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="5">Loading Data....</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 p-r-0">
                        <div style="box-shadow:0px 0px 20px 0px hsl(0, 0%, 80%);" class="panel panel-inverse">
                            <div class="panel-heading p-sm clearfix">
                                <h4 class="panel-title pull-left" style="margin-top: 4px;">Filters</h4>
                                <span class="pull-right">
                                    <button style="padding: 2px 10px 5px 9px;" class="btn btn-primary btn-xs" id="apply_filter"><i class="fas fa-check"></i> Apply</button>
                                </span>
                            </div>
                            <div style="background: rgba(0,0,0,0.04);" class="panel-body p-b-xs">
                                <div class="form-group m-b-md">
                                    <label class="control-label fz-sm">By Dates or Time</label>
                                    <div class="input-group ">
                                        <span class="input-group-addon bg-info "><i class="fas fa-calendar-alt text-white fa-lg"></i></span>
                                        <input class="form-control fz-sm" id="datetime" type="text" value="<?php //echo date(Doo::conf()->date_format_db,strtotime("today - 30 days")).' - '.date(Doo::conf()->date_format_db); ?>" name="datetime" placeholder="select date and time range...." />
                                    </div>
                                </div>
                                <?php if($_SESSION['user']['group']!= "client"){ ?>
                                <div class="form-group m-b-md">
                                    <label class="control-label fz-sm">By User</label>
                                    <select title="" id="userpicker" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){ if(data.element && data.element.value>0){ let uname = data.element.text; var lstr = data.element.label; var myarr = lstr.split('|'); var nstr = '<div class=\'media m-t-xs\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+uname+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic; line-height:14px;\'>'+myarr[1]+'</p></div></div>';}else{var nstr='<h5>- All User Acccounts -</h5>';} return $(nstr);}, templateSelection: function (data){ if(data.element.value==0) return '- All User Acccounts -'; let uname = data.element.text; var lstr=data.element.label;var myarr = lstr.split('|'); var nstr = '<div class=\'media\' style=\'padding-top: 2px;\'><div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\'><h5 class=\'m-t-0 m-b-0\'><a href=\'javascript:void(0);\' class=\'m-r-xs text-dark\'>'+uname+'</a><small class=\'text-muted fz-sm\'>'+myarr[2]+'</small></h5><p style=\'font-size: 12px;font-style: Italic;line-height:14px;\'>'+myarr[1]+'</p></div></div>'; return $(nstr);} }">
                                        <option value="0">- All User Acccounts -</option>
                                        <?php foreach($data['users'] as $usr){ ?>
                                            <option value="<?php echo $usr->user_id ?>" data-fullname="<?php echo $usr->name ?>" label="<?php echo $usr->avatar.'|'.$usr->email.'|'.$usr->category."|".$usr->mobile ?>" ><?php echo strtok($usr->name," ") ?></option>
                                        <?php } ?>
                                        <?php ?>
                                    </select>
                                </div>
                                        <?php } ?>

                                <div class="form-group m-b-md">
                                    <label class="control-label fz-sm">By SMPP Client</label>
                                    <select id="f_smpp_client" title="" class="form-control" data-plugin="select2" name="f_smpp_client">
                                        <option value="">- Any Client -</option>
                                        <?php foreach($data['smppclients'] as $smppclient){ ?>
                                            <option value="<?php echo $smppclient->system_id ?>"><?php echo $smppclient->system_id ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php if($_SESSION['user']['group']== "admin"){ ?>
                                <div class="form-group m-b-md">
                                    <label class="control-label fz-sm">By SMPP</label>
                                    <select title="" class="form-control" data-plugin="select2" id="f_smpp" name="f_smpp">
                                        <option value="">- All SMPP Accounts -</option>
                                        <?php foreach($data['smpp'] as $smpp){ ?>
                                            <option value="<?php echo $smpp->smsc_id ?>"><?php echo $smpp->title ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php } ?>
                                <?php if($_SESSION['user']['account_type'] != "1"){ ?>
                                <div class="form-group m-b-md">
                                    <label class="control-label fz-sm">By Routes</label>
                                    <select title="" class="form-control" data-plugin="select2" id="f_route" name="f_route">
                                        <option value="">- All Routes -</option>
                                        <?php if($_SESSION['user']['group']=="admin"){ ?>
                                        <?php foreach($data['routes'] as $route){ ?>
                                            <option value="<?php echo $route->id ?>"><?php echo $route->title ?></option>
                                        <?php }}else{ ?>
                                            <?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                                                <option value="<?php echo $rt['id'] ?>"><?php echo $rt['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <?php }else{ ?>
                                    <input type="hidden" id="f_route" value="">
                                <?php } ?>
                                <div class="form-group m-b-md">
                                    <label class="control-label fz-sm">By Sender ID</label>
                                    <select title="" class="form-control" data-plugin="select2" id="f_senderid" name="f_senderid">
                                        <option value="">- Any Sender ID -</option>
                                        <?php foreach($data['senderids'] as $senderid){ ?>
                                            <option value="<?php echo $senderid->sender_id ?>"><?php echo $senderid->sender_id ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="widget">
                                    <div class="widget-header"><h4 class="widget-title">SMS Types</h4></div>
                                    <hr class="widget-separator m-b-0">
                                    <div class="widget-body">
                                        <div id="s_smstypes" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget">
                                    <div class="widget-header"><h4 class="widget-title">Popular Networks</h4></div>
                                    <hr class="widget-separator m-b-0">
                                    <div class="widget-body p-0">
                                        <div id="s_networks" style="height: 280px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget">
                                    <div class="widget-header"><h4 class="widget-title">Top Countries</h4></div>
                                    <hr class="widget-separator m-b-0">
                                    <div class="widget-body">
                                        <div id="top_countries" class="list-group m-0" style="max-height: 250px;overflow: auto;">
                                            <h5>Loading Data ......</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
