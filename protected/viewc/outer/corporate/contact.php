<?php include('header.php') ?>

<div class="sub_page_top" style="margin-bottom:0px;">
  <div class="container">

    <section class="row-fluid">
      <header>
        <h1 class="sub_page_hdng"><?php echo SCTEXT('Contact Us') ?></h1>
      </header>
      <p><?php echo SCTEXT('We are easily reachable.') ?></p>

    </section>
  </div>
</div>

</div>
<!--End Slide 1-->



<div class="slide" id="slide3" data-slide="3" data-stellar-background-ratio="0.5">


  <div class="container">

    <!--end map-->

    <section class="row-fluid">
      <div class="span8">
        <form class="fixed" id="contact-form" name="contact-form" method="post" action="<?php echo Doo::conf()->APP_URL ?>saveContactLead">
          <input type="hidden" name="cemail" value="<?php echo base64_encode($pdata['qmail']); ?>" />
          <div class="form no_padd_top" id="contact_form">
            <div id="response">
              <?php if (isset($data['notif_msg']['msg'])) { ?>
                <p class="edit"><i class="icon-info-sign icon-large"></i><span style="color:#000;padding-left:10px;"><?php echo $data['notif_msg']['msg']; ?></span></p>
              <?php } ?>
            </div>
            <header>
              <h1 class="light"><?php echo SCTEXT('Send us a message') ?></h1>
            </header>
            <label><?php echo SCTEXT('Name') ?>:</label>
            <input style="width:77%;" type="text" id="name" name="name" />
            <div class="clr"></div>
            <label><?php echo SCTEXT('Email') ?>:</label>
            <input style="width:77%;" type="text" id="email" name="email" />
            <div class="clr"></div>
            <label><?php echo SCTEXT('Subject') ?>:</label>
            <input style="width:77%;" type="text" id="subject" name="subject" value="<?php echo !isset($data['sub']) ? '' : 'Details for: ' . $data['sub'] ?>" />
            <div class="clr"></div>
            <label><?php echo SCTEXT('Message') ?>:</label>
            <textarea id="message" name="message"></textarea>
            <div class="clr"></div>
            <label></label>
            <input type="submit" value="<?php echo SCTEXT('submit') ?>" class="btn btn-danger" id="submit-contact" />
            <div class="clr"></div>
          </div>
        </form>

        <!--end form #contact_form-->


      </div>
      <!--end span8-->

      <div class="span4 contact_right">
        <h1 class="light"><?php echo SCTEXT('Get in Touch') ?></h1>
        <address>
          <div class="contact_info">
            <strong><?php echo SCTEXT('Contact Info') ?></strong>
            <ul>
              <li class="one"><i class="icon-mobile-phone"></i><?php echo $cdata['helpline'] ?></li>
              <li class="two"><a href="mailto:<?php echo $cdata['helpmail'] ?>"><i class="icon-envelope"></i><?php echo $cdata['helpmail'] ?></a></li>
              <li class="four"><i class="icon-home"></i><?php echo nl2br($pdata['address']) ?></li>
            </ul>
            <div class="clr"></div>
          </div>
          <!--end contact_info-->
        </address>
        <div class="clr"></div>
      </div>

      <!--end span4-->

      <div class="clr"></div>
    </section>
    <!--end row-fluid-->

    <div class="clr"></div>
  </div>


  <?php include('footer.php') ?>