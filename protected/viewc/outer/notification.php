<!--Feedback notifications -->
                                                 <?php if (isset($data['notif_msg'])){ ?>
                                            
                                            
                                            <?php if($data['notif_msg']['type']=='success'){ ?>
                                           <div class="alert alert-custom alert-success">
						<i class="fa fa-check-circle fa-lg m-r-xs"></i> <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                            
                            <?php }elseif($data['notif_msg']['type']=='warning'){ ?>
                            
                            <div class="alert alert-custom alert-warning">
						<i class="fa fa-lg m-r-xs fa-exclamation-circle"></i> <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                            
                              <?php }elseif($data['notif_msg']['type']=='error'){ ?>
                              <div class="alert alert-custom alert-danger">
						<i class="fa fa-times-circle fa-lg m-r-xs"></i>&nbsp; <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                              
                              
                                <?php }elseif($data['notif_msg']['type']=='info'){ ?>
                                <div class="alert alert-custom alert-info">
						<i class="fa fa-lg m-r-xs fa-info-circle"></i> <?php echo SCTEXT($data['notif_msg']['msg']) ?>
					</div>
                                
                            
                            <?php }} ?>