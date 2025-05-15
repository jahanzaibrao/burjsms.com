
    <main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('Add new SSL')?><small><?php echo SCTEXT('install a new SSL for one of the resellers')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    <form class="form-horizontal" method="post" id="add_ssl_form" action="">
                                        <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Select Reseller')?>:</label>
                                                        <div class="col-md-8">
                                                            <select id="domresfil" name="domresfil" class="form-control" data-plugin="select2" data-options="{placeholder: '<?php echo SCTEXT('Select a Reseller account')?> ...', templateResult: function (data){ var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[3]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                                                                <?php foreach($data['rsites'] as $usr){ ?>
                                                                <option data-doms="<?php echo $usr->domains ?>" value="<?php echo $usr->uid ?>" title="<?php echo  $usr->name ?>"><?php echo  $usr->avatar.'||primary|'. $usr->email ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="help-block text-danger"><?php echo SCTEXT('Before installing SSL, make sure that selected domains are pointing to your server. Ask the reseller to add correct <b>A record</b> in their domain DNS settings.')?></span>
                                                        </div>
                                            </div>
                                        <div class="form-group">
												<label class="control-label col-md-3"><?php echo SCTEXT('Select Domains')?>:</label>
												    <div class="col-md-8" id="domainctr">
													   <?php $domar = explode(",",$data['rsites'][0]->domains);$i=0;
                                                        foreach($domar as $dom){ if($dom!=''){$i++; ?>
                                                        <div class="checkbox checkbox-success">
                                                        <input <?php if(in_array($dom,$data['available_certs'])){ ?> disabled <?php } ?> name="seldoms[]" value="<?php echo $dom ?>" type="checkbox" id="cb-<?php echo $i ?>" checked="checked">
                                                            <label for="cb-<?php echo $i ?>"><?php echo $dom ?></label>
                                                        </div>
                                                        <?php }} ?>
                                                        
												    </div>
										</div>
                                        <hr>
                                        
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
												<div class="col-md-8">
													<button class="btn btn-primary" id="save_changes" type="button"><?php echo SCTEXT('Install SSL for selected domains')?></button>
													<button id="bk" type="button" class="btn btn-default"><?php echo SCTEXT('Cancel')?></button>
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