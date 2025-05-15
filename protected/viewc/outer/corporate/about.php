<?php include('header.php') ?>

<div class="sub_page_top" style="margin-bottom:0px;">
<div class="container">
        
        <section class="row-fluid">
        <header>
          <h1 class="sub_page_hdng"><?php echo SCTEXT('About Us')?></h1>
        </header>
        <p><?php echo SCTEXT('All about our company and our values.')?></p>
      
    </section>
</div>
</div>

</div>
<!--End Slide 1-->



<div class="slide" id="slide3" data-slide="3" data-stellar-background-ratio="0.5">
 
    
    <div class="container">
       
        
        <section class="row-fluid">
            <?php echo htmlspecialchars_decode($pdata['content']); ?>
        </section>
    
    </div>
  

<?php include('footer.php') ?>