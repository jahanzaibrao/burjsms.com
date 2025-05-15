<!--Feedback notifications -->
                                                 <?php if (isset($data['notif_msg'])){ ?>
                                            
                                            
                                            <?php if($data['notif_msg']['type']=='success'){ ?>
                                           <div class="alert alert-custom alert-success">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<i class="fa fa-check-circle fa-2x"></i> <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                            
                            <?php }elseif($data['notif_msg']['type']=='warning'){ ?>
                            
                            <div class="alert alert-custom alert-warning">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<i class="fa fa-2x fa-exclamation-circle"></i> <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                            
                              <?php }elseif($data['notif_msg']['type']=='error'){ ?>
                              <div class="alert alert-custom alert-danger">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<i class="fa fa-times-circle fa-2x"></i>&nbsp; <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                              
                              
                                <?php }elseif($data['notif_msg']['type']=='info'){ ?>
                                <div class="alert alert-custom alert-info">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<i class="fa fa-2x fa-info-circle"></i> <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                                
                            
                            <?php }} ?>