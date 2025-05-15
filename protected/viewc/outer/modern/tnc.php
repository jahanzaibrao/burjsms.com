<?php include('header.php') ?>
     

<div class="spacing"></div>

<div class="row">
    <div class="special-title centered-text">
          <i class="icon-menu"></i>
          <h2><?php echo SCTEXT('Terms & Conditions')?></h2>
          <p><?php echo SCTEXT('Please read our terms of service.')?></p>
          <p class="shortline"></p>
        </div>
</div> 

<div class="spacing"></div>

<div class="row">
    <p>
        <?php echo htmlspecialchars_decode($data['content']); ?>
    </p>
</div>



<?php include('footer.php') ?>
<!-- Localized -->