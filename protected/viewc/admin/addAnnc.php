 <main id="app-main" class="app-main">
     <?php include('breadcrums.php') ?>
     <div class="wrap">
         <section class="app-content">
             <div class="row">
                 <div class="col-md-12">
                     <div class="widget p-lg">
                         <div class="row no-gutter">
                             <h3 class="page-title-sc"><?php echo SCTEXT('Add Announcement') ?><small><?php echo SCTEXT('create a new announcement to broadcast to users') ?></small></h3>
                             <hr>
                             <?php include('notification.php') ?>
                             <div class="col-md-12">
                                 <!-- start content -->
                                 <form class="form-horizontal" action="" method="post" id="annfrm">

                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Display Text') ?></label>
                                         <div class="col-md-8">
                                             <textarea class="form-control input-large" rows="5" id="dtxt" name="dtxt" placeholder="<?php echo SCTEXT('Enter the text to display as announcement') ?>..."></textarea>
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Announcement Type') ?></label>
                                         <div class="col-md-8">

                                             <div class="radio radio-success">
                                                 <input id="a1" checked="checked" value="1" type="radio" name="stype">
                                                 <label for="a1"><span class="label label-success"><?php echo SCTEXT('Positive News') ?></span></label>
                                                 <span class="help-block"><?php echo SCTEXT('Use this option when the information is a good news or a positive message.') ?></span>
                                             </div>
                                             <div class="radio radio-info">
                                                 <input id="a2" value="2" type="radio" name="stype">
                                                 <label for="a2"><span class="label label-info"><?php echo SCTEXT('General Information') ?></span></label>
                                                 <span class="help-block"><?php echo SCTEXT('Use this for generic informational broadcasts') ?></span>
                                             </div>
                                             <div class="radio radio-danger">
                                                 <input id="a3" value="3" type="radio" name="stype">
                                                 <label for="a3"><span class="label label-danger"><?php echo SCTEXT('Warning Message') ?></span></label>
                                                 <span class="help-block"><?php echo SCTEXT('Use this for negative messages like if the system is going to be down for a while etc.') ?></span>
                                             </div>

                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="control-label col-md-3"><?php echo SCTEXT('Display to') ?></label>
                                         <div class="col-md-8">
                                             <select id="dfor" data-plugin="select2" class="form-control" data-placeholder="<?php echo SCTEXT('Who will see this announcement') ?>.." name="dfor">
                                                 <option value="1"><?php echo SCTEXT('All Users') ?></option>
                                                 <option value="2"><?php echo SCTEXT('Only Resellers') ?></option>
                                                 <option value="3"><?php echo SCTEXT('Only Clients') ?></option>
                                             </select>

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
