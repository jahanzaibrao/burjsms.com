<?php include('header.php') ?>
     <?php if($pdata['sliderflag']=='1'){ ?>
    <div id='sequence'>
      <ul class='sequence-canvas'>
          
           <?php foreach($pdata['sliderdata'] as $slide){ ?>
          
        <li class='frame'>
          <div class='bg' style='background-image: url(<?php echo './global/img/banners/'.$slide['image'] ?>);'></div>
          <div class='title left-to-right'>
            <div class='row'>
              <div class='large-12 columns'>
                <h2><?php echo $slide['title']; ?></h2>
              </div>
            </div>
          </div>
          <div class='info left-to-right'>
            <div class='row'>
              <div class='large-12 columns'>
                <p><?php echo $slide['desc']; ?></p>
                
              </div>
            </div>
          </div>
        </li>
          
          <?php } ?>
          
      </ul>
      <a class='sequence-prev' href='javascript:void(0);'>
        <span></span>
      </a>
      <a class='sequence-next' href='javascript:void(0);'>
        <span></span>
      </a>
      
    </div>

<?php } ?>

 

<?php
        if($pdata['twgflag']=='1'){
        
        ?>


    <div class='full white'>
        <div class='row'>
            <div class="medium-6 large-8 columns">
                <?php echo htmlspecialchars_decode($pdata['content']); ?>
            </div>
            <div class="medium-6 large-4 form-bg columns text-center" style="padding:10px;">
                <div id="tgw_msg" style="display:inline-block;text-align:left;">
                    
                </div>
                <div class="input-group text-center">
                    <h2 style="" class="widget-title"><?php echo $pdata['twgdata']['title'] ?></h2>
                    <input placeholder="<?php echo SCTEXT('enter mobile number with prefix')?> . . ." type="text" id="tgw_contact" class="form-control" />
                    <a style="vertical-align:top;padding:9px 31px !important;font-size:16px !important;" id="submit_tgw" class="button white" href="javascript:void(0);"><?php echo SCTEXT('Send SMS')?></a>
                </div>
            </div>
             
        </div>
    </div>

<?php }else{ ?>
    <div class='full white'>
        <div class='row'>
            <?php echo htmlspecialchars_decode($pdata['content']); ?>
        </div>
    </div>

<?php } ?>


<?php include('footer.php') ?>
<!-- Localized -->