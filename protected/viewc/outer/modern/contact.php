<?php include('header.php') ?>
     

<div class="spacing"></div>

<div class="row">
    <div class="special-title centered-text">
          <i class="icon-mail"></i>
          <h2><?php echo SCTEXT('Contact us')?></h2>
          <p><?php echo SCTEXT('We are easily reachable.')?></p>
          <p class="shortline"></p>
        </div>
</div> 

<div class="spacing"></div>

<div class="row">
    <p>
        <?php echo htmlspecialchars_decode($pdata['content']); ?>
    </p>
</div>

<div class="full form-bg">
      <div class="form">
        <div class="row">
          <form id="contact" method="post" action="<?php echo Doo::conf()->APP_URL ?>saveContactLead" novalidate="novalidate">
               <?php if($data['notif_msg']['msg']!=''){ ?>
                <div class="large-12 columns">
                  <p id="thanks">
                    <i class="icon-info-circled"></i>  <?php echo $data['notif_msg']['msg']; ?>
                  </p>
                </div>
              <?php } ?>
            <input type="hidden" name="cemail" value="<?php echo base64_encode($pdata['qmail']); ?>" />
            <div class="medium-6 large-6 columns">
              <input class="required" name="name" placeholder="<?php echo SCTEXT('NAME')?>" type="text">
              <input class="required email" name="email" placeholder="<?php echo SCTEXT('EMAIL')?>" type="text">
              <input class="required" name="subject" placeholder="<?php echo SCTEXT('SUBJECT')?>" value="<?php echo $data['sub']==''?'':'Details for: '.$data['sub'] ?>" type="text">
            </div>
            <div class="medium-6 large-6 columns">
              <textarea class="required" name="message" placeholder="<?php echo SCTEXT('MESSAGE')?>"></textarea>
              <input class="button white" type="submit" value="<?php echo SCTEXT('Send Message')?>">
            </div>
          </form>
        </div>
      </div>
    </div>


<?php include('footer.php') ?>
<!-- Localized -->