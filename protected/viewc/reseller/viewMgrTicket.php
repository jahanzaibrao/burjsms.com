<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc"><?php echo SCTEXT('View Ticket')?><small><?php echo SCTEXT('communicate with your clients regarding the issue')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                <!-- start content -->
                                    
                                <div class="clearfix">
                                    <div class="col-md-3 col-sm-4">
                                        <div class="media-group-item p-t-0">
                                                        
                                                       <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-sm avatar-circle"><a href="<?php echo Doo::conf()->APP_URL.'viewUserAccount/'.$data['tdata']->user_id ?>"><img src="<?php echo $data['udata']->avatar ?>" alt="UserImage"></a></div>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="m-t-0"><a href="<?php echo Doo::conf()->APP_URL.'viewUserAccount/'.$data['udata']->user_id ?>" class="m-r-xs theme-color"> <?php echo ucwords($data['udata']->name) ?></a><small class="text-muted fz-sm"> <?php echo ucwords($data['udata']->category) ?></small></h5>
                                                <p style="font-size: 12px;font-style: Italic;"><?php echo $data['udata']->email ?></p> 
                                            </div>
                                        </div>

                                                    </div>
                                        
                                        <hr>
                                        <h4><?php echo SCTEXT('Ticket ID')?>: #<?php echo $data['tdata']->id ?></h4>
                                        <?php if($data['tdata']->status==0){ ?>
                                        <span class="m-b-lg p-h-xs label label-md p-v-md label-warning label-sm"><i class="fa fa-lg fa-clock-o m-r-xs"></i><?php echo SCTEXT('Issue Open')?> </span>
                                        <?php }else{ ?>
                                        <span class="m-b-lg p-h-xs label label-md p-v-md label-success label-sm"><i class="fa fa-lg fa-check-circle m-r-xs"></i><?php echo SCTEXT('Resolved')?> </span>
                                        <?php } ?>
                                        <div class="m-t-lg">
                                            <span class="pointer m-b-xs block" title="<?php echo SCTEXT('Priority')?>"><i class="m-r-xs fa fa-lg fa-fixed fa-flag text-info"></i> <?php echo $data['tdata']->priority==0?'<span class="label label-info label-md">'.SCTEXT('Normal').'</span>':($data['tdata']->priority==1?'<span class="label label-warning label-md">'.SCTEXT('Medium').'</span>':'<span class="label label-danger label-md">'.SCTEXT('Critical').'</span>'); ?></span>
                                            <span class="pointer m-b-xs block" title="<?php echo SCTEXT('Date Opened')?>"><i class="m-r-xs fa fa-lg fa-fixed fa-clock-o text-danger"></i> <?php echo date(Doo::conf()->date_format_long_time,strtotime($data['tdata']->date_opened)) ?></span>
                                            <span class="pointer block m-b-sm" title="<?php echo SCTEXT('Date Resolved')?>"><i class="m-r-xs fa fa-lg fa-fixed fa-check-circle text-success"></i><?php echo $data['tdata']->date_closed=='0000-00-00 00:00:00'?'- N/A -':date(Doo::conf()->date_format_long_time,strtotime($data['tdata']->date_closed)); ?></span>
                                            
                                            <div class="dropdown btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"> <?php echo SCTEXT('Ticket Actions')?> <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-btn pull-left"><li><a href="<?php echo Doo::conf()->APP_URL.'markTicket/c/'.$data['tdata']->id ?>"><?php echo SCTEXT('Mark as Resolved')?></a></li><li><a href="<?php echo Doo::conf()->APP_URL.'markTicket/o/'.$data['tdata']->id ?>"><?php echo SCTEXT('Mark as Open')?></a></li></ul></div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-md-9 col-sm-8">
                                        
                                        <h4><?php echo $data['tdata']->ticket_title ?></h4>
                                        <hr>
                                        <div id="docremk" class="sc_chatctr">
                                            <?php foreach($data['tcoms'] as $cmt){ ?>
                                          <div class="sc_chatbox <?php echo $cmt->user_id!=$_SESSION['user']['userid']?'chat-other':'chat-me clearfix'; ?>">
                                                <div class="sc_chatele">
                                                            <div class="media-left">
                                                                    <div class="avatar avatar-lg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo $cmt->user_id!=$_SESSION['user']['userid']?$data['udata']->avatar:$_SESSION['user']['avatar']; ?>" alt="Photo"></a></div>
                                                            </div>
                                                            <div class="media-body p-l-md">
                                                                    
                                                                    <h5 class="text-dark"><?php echo $cmt->user_id!=$_SESSION['user']['userid']?$data['udata']->name:$_SESSION['user']['name']; ?></h5>
                                                                    <span><?php echo $cmt->ticket_text ?></span>
                                                                <?php if($cmt->files_included!=''){ ?>
                                                                <hr>
                                                                <h5><?php echo SCTEXT('Files Attached')?></h5>
                                                                <?php $cfiles = explode(",",$cmt->files_included);
                                                                foreach($cfiles as $fl){
                                                                ?>
                                                                <div class="btn-group m-r-sm">
                                                                <span class="input-group-addon m-r-sm"><i class="fa fa-2x fa-file text-primary"></i></span><a class="btn btn-sm btn-info" title="View File" target="_blank" href="<?php echo Doo::conf()->APP_URL.'viewDocument/'.$fl ?>"><i class="fa fa-search fa-lg"></i></a><a class="btn btn-sm btn-success" title="<?php echo SCTEXT('Download File')?>" href="<?php echo Doo::conf()->APP_URL.'globalFileDownload/docmgr/'.$fl ?>"><i class="fa fa-download fa-lg"></i></a> 
                                                                </div>
                                                                <?php }} ?>
                                                                    <small class="text-dark text-right"><i class="fa fa-clock-o"></i> <?php echo date(Doo::conf()->date_format_med_time,strtotime($cmt->date_added)) ?> </small>
                                                            </div>  
                                                </div>
                                            </div>
                                            <?php } ?>
                                         </div>
                                        
                                        
                                         <div id="tktcmt" class="text-right m-t-sm clearfix">
                                                <form method="post" id="cmt_form">
                                                    <input type="hidden" name="tpage" value="mgr"/>
                                                    <input type="hidden" name="ticketid" value="<?php echo $data['tdata']->id ?>"/>      <textarea id="t_comment" name="t_comment" class="form-control" placeholder="<?php echo SCTEXT('enter your comment')?> ..."></textarea>
                                                    
                                                    <a class="btn btn-outline btn-primary pull-left m-t-xs" href="<?php echo Doo::conf()->APP_URL ?>manageSupport"><i class="fa fa-lg fa-angle-double-left m-r-xs"></i> <?php echo SCTEXT('Back to Tickets')?></a>
                                                    <a id="submitCmt" class="m-t-xs btn btn-primary btn-sm"><?php echo SCTEXT('Submit')?></a>
                                                    
                                                </form>
                                                        
                                         </div>
                                        
                                    </div>
                                    
                                </div>    
                                    
                                    
                                  
                                <!-- end content -->    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>           