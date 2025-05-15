   <?php include('header.php') ?>
   <div id="main-content">
       <?php if ($pdata['sliderflag'] == '1') { ?>

           <div class="fullwidthbanner-container">
               <div class="fullwidthbanner">
                   <ul>

                       <?php foreach ($pdata['sliderdata'] as $slide) { ?>

                           <li data-transition="boxfade" data-slotamount="10" data-masterspeed="300" data-delay="9400">
                               <img src="<?php echo './global/img/banners/' . $slide['image'] ?>" alt="">

                               <div class="caption lfb small_yellow" data-x="800" data-y="150" data-speed="200" data-start="1000" data-easing="easeOutExpo"><?php echo $slide['title']; ?></div>
                               <div style="white-space: normal !important; text-wrap: wrap;" class="caption lfb small_white" data-x="800" data-y="220" data-speed="300" data-start="1000" data-easing="easeOutExpo"><?php echo $slide['desc']; ?></div>
                           </li>

                       <?php } ?>

                   </ul>

               </div>
           </div>

       <?php } ?>

       <div class="">

           <div class="wrapper">

               <div class="row-fluid">

                   <div class="span12">
                       <br>
                       <?php echo htmlspecialchars_decode($pdata['content']); ?>

                   </div><!--end:span12-->

               </div><!--end:row-fluid-->

           </div><!--end:wrapper-->

       </div><!--end:flx-divider-->
       <?php
        if ($pdata['twgflag'] == '1') {

        ?>

           <section class="widget flx-divider bottom-tag-line">

               <div class="wrapper">

                   <div class="row-fluid">

                       <div class="span12">

                           <div class="tag-line-box clearfix">

                               <div class="text-center" style="background: #fff none repeat scroll 0 0;padding: 10px;margin: -15px 0 -25px 0;border-radius: 5px;">

                                   <h2 style="margin:8px 0 10px 0 !important;" class="widget-title"><?php echo $pdata['twgdata']['title'] ?></h2>
                                   <div id="tgw_msg" style="display:inline-block;width:30%;text-align:left;">

                                   </div>
                                   <div class="input-group">

                                       <input placeholder="<?php echo SCTEXT('enter mobile number with prefix') ?> . . ." style="border: 1px solid #d5e3e8;border-radius: 0;box-shadow: none;color: #838383;height: 30px;width:40%;margin-bottom: 3px;" type="text" id="tgw_contact" class="form-control" />
                                       <a style="vertical-align:top; background-color: #1abc9c;color: #FFFFFF;" id="submit_tgw" class="small-button <?php echo $data['skin']['color'] ?>" href="javascript:void(0);"><?php echo SCTEXT('Send SMS') ?></a>
                                   </div>
                               </div>

                           </div><!--tag-line-box-->

                       </div><!--end:span12-->

                   </div><!--row-fluid-->

               </div><!--end:wrapper-->

           </section><!--end:widget-->

       <?php } ?>
   </div><!--end:main-content-->
   <?php include('footer.php') ?>