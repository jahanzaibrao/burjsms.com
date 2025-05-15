<?php include('header.php') ?>


    
    <?php if($pdata['sliderflag']=='1'){ ?>
  <section>
    <div class="fullwidthbanner-container">
      <div class="fullwidthbanner">
        <ul>
          
          <!-- FADE -->
             <?php foreach($pdata['sliderdata'] as $slide){ ?>
            
          <li data-transition="fade" data-slotamount="7"> <img src="<?php echo './global/img/banners/'.$slide['image'] ?>" alt="image" />
            <div class="caption sfb" data-x="0" data-y="150" data-speed="900" data-start="900" data-easing="easeOutSine">
              <p class="white"><?php echo $slide['title']; ?></p>
            </div>
            <div class="caption lft" data-x="0" data-y="200" data-speed="900" data-start="1700" data-easing="easeOutBack">
              <p class="black"><?php echo $slide['desc']; ?></p>
            </div>
          </li>
          
            <?php } ?>
          
        </ul>
        <div class="tp-bannertimer"></div>
      </div>
    </div>
  </section>
<?php } ?> 
  <div class="clr"></div>
    
    
  <section class="banner_bottom">
    <?php
        if($pdata['twgflag']=='1'){
        
        ?>
      
      <section class="banner_bottom">
         <h1 style="" class="widget-title"><?php echo $pdata['twgdata']['title'] ?></h1>
                                        <div id="tgw_msg" style="display:inline-block;width:30%;text-align:left;">
                                            
                                        </div>
                                        <div class="input-group">
                                            
                                            <input placeholder="<?php echo SCTEXT('enter mobile number with prefix')?> . . ." style="border: 1px solid #d5e3e8;border-radius: 0;box-shadow: none;color: #838383;height: 30px;width:40%;margin-bottom: 3px;" type="text" id="tgw_contact" class="form-control" />
                                            <a style="vertical-align:top;padding:9px 31px !important;font-size:16px !important;" id="submit_tgw" class="btn btn-danger" href="javascript:void(0);"><?php echo SCTEXT('Send SMS')?></a>
                                        </div>
      </section>
      
      <?php } ?>
  </section>
  <!--end banner_bottom-->
  
  <!--end container-->

</div>
<!--End Slide 1-->



<div class="slide" id="slide3" data-slide="3" data-stellar-background-ratio="0.5">
 
    
    <div class="container">
        <section class="row-fluid">
            <?php echo htmlspecialchars_decode($pdata['content']); ?>
        </section>
    
    </div>
  

<?php include('footer.php') ?>