<?php include('header.php') ?>
     

<div class="spacing"></div>

<div class="row">
    <div class="special-title centered-text">
          <i class="icon-users"></i>
          <h2><?php echo SCTEXT('About us')?></h2>
          <p><?php echo SCTEXT('All about our company and our values.')?></p>
          <p class="shortline"></p>
        </div>
</div> 

<div class="spacing"></div>

<div class="row">
    <p>
        <?php echo htmlspecialchars_decode($pdata['content']); ?>
    </p>
</div>



<?php include('footer.php') ?>
<!-- Localized -->