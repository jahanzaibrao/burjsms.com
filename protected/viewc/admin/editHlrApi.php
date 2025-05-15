 <main id="app-main" class="app-main">
     <?php include('breadcrums.php') ?>
     <div class="wrap">
         <section class="app-content">
             <div class="row">
                 <div class="col-md-12">
                     <div class="widget p-lg">
                         <div class="row no-gutter">
                             <h3 class="page-title-sc"><?php echo SCTEXT('Edit HLR Channel') ?><small><?php echo SCTEXT('modify HLR Lookup channel parameters') ?></small></h3>
                             <hr>
                             <?php include('notification.php') ?>
                             <div class="col-md-12">
                                 <!-- start content -->
                                 <form class="form-horizontal" action="" method="post" id="hlrcfrm">
                                     <input type="hidden" name="cid" value="<?php echo $data['channel']->id ?>">
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Channel Name') ?>:</label>
                                         <div class="col-md-8">
                                             <input value="<?php echo $data['channel']->channel_name ?>" type="text" class="form-control" name="chname" placeholder="enter the name to identify this HLR channel . . . .">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Select Provider') ?>:</label>
                                         <div class="col-md-8">
                                             <select id="prsel" data-plugin="select2" class="form-control" name="provider" data-options="{placeholder: 'select HLR API provider . . .'}">
                                                 <option></option>
                                                 <?php foreach ($data['providers'] as $pr) { ?>
                                                     <option data-async="<?php echo $pr['method'] ?>" <?php if ($data['channel']->provider_id == $pr['id']) { ?> selected <?php } ?> data-website="<?php echo $pr['website'] ?>" data-method="<?php echo $pr['auth']['method'] ?>" value="<?php echo $pr['id'] ?>"><?php echo $pr['name'] ?></option>
                                                 <?php } ?>
                                             </select>
                                             <span class="help-block text-info" id="hlrdesc">
                                                 Select a provider for more information
                                             </span>
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Authentication') ?>:</label>
                                         <div class="col-md-8">
                                             <?php $params = unserialize($data['channel']->auth_data); ?>
                                             <div class="col-md-6 clearfix p-sm planopts text-dark" id="apiparam_getpost">
                                                 <input class="form-control block m-h-md" name="param1" value="<?php echo $params['api_key'] ?>" placeholder="enter API Key . . .">
                                             </div>
                                             <div class="col-md-6 clearfix p-sm planopts text-dark hidden" id="apiparam_httpauth">
                                                 <input class="form-control block m-h-md" name="param3" value="<?php echo $params['key'] ?>" placeholder="enter API key . . .">
                                             </div>
                                         </div>
                                     </div>

                                     <hr>
                                     <div class="form-group">
                                         <div class="col-md-3"></div>
                                         <div class="col-md-8">
                                             <button id="save_changes" class="btn btn-primary" type="button"><?php echo SCTEXT('Save changes') ?></button>
                                             <button id="bk" class="btn btn-default" type="button"><?php echo SCTEXT('Cancel') ?></button>
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