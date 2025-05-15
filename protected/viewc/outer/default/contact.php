   <?php include('header.php') ?>

<?php $cdata = unserialize($_SESSION['webfront']['company_data']); ?>

<div id="main-content">

        <section class=" flx-divider">

        	<div class="wrapper flx-line">

            	<div class="row-fluid">

                    <div class="span12 clearfix">

                        <p class="flx-map-thumb"></p>

                        <div class="flx-intro-content">

                            <h2><?php echo SCTEXT('Contact us')?></h2>
                            <p><?php echo SCTEXT('We are easily reachable.')?></p>

                        </div><!--end:flx-intro-content-->

                    </div><!--end:span12-->

                </div><!--end:row-fluid-->

            </div><!--end:wrapper-->

        </section><!--end:flx-intro-->

        <div class="breadcrumb flx-divider">

        	<div class="wrapper flx-line">

            	<div class="row-fluid">

                	<div class="span12 clearfix">
                    	<a href="<?php echo Doo::conf()->APP_URL ?>">Home</a>
                        <span>&nbsp;&nbsp;/&nbsp;&nbsp;</span>
                        <span><?php echo SCTEXT('Contact Us')?></span>
                    </div><!--end:span12-->

                </div><!--end:row-fluid-->

            </div><!--end:wrapper-->

        </div><!--end:breadcrumb-->

        <!--end:flx-divider-->

        <section class=" flx-divider">



            <div class="wrapper flx-line">

            	<div class="row-fluid">

                	<div id="mycontactbox" class="span12">


                        <div class="wrapper flx-line" style="background: rgb(41, 53, 53) none repeat scroll 0px 0px;">

            <div class="row-fluid">

                <div class="span12 clearfix">

                    <ul id="bottom-sidebar">

                        <li>

                            <aside class="widget widget_text">

                                <h2 class="widget-title"><?php echo SCTEXT('Contact Info')?></h2>

                                <div class="textwidget">

                                    <p> </p>

                                </div><!--textwidget-->

                                <ul class="contact-info">

                                    <li class="green">
                                        <i class="icon-map-marker"></i><span class="contact-address"><?php echo nl2br($pdata['address']) ?></span>
                                    </li>
                                    <li class="orange">
                                        <i class="icon-phone"></i><a class="contact-phone" href="javascript:void(0);"><?php echo $cdata['helpline'] ?></a>
                                    </li>
                                    <li class="blue">
                                        <i class="icon-envelope-alt"></i><a class="contact-email" href="mailto:<?php echo $cdata['helpmail'] ?>"><?php echo $cdata['helpmail'] ?></a>
                                    </li>

                                </ul><!--end:contact-info-->

                            </aside><!--end:widget-->

                        </li>

                        <li class="bottom-contact-form" style="border-right: medium none;">

                            <aside class="widget">

                                <h2 class="widget-title"><?php echo SCTEXT('Send Us a Message')?></h2>

                            <?php if($data['notif_msg']['msg']!=''){ ?>
                                <div class="alert-box <?php echo $data['notif_msg']['type']=='error'?'alert-box-warning':'alert-box-success' ?>">
                                    <p><?php echo $data['notif_msg']['msg'] ?></p>
                                </div>
                            <?php } ?>

                                <div id="contact-form-wrap">
                                    <div id="response"></div>
                                    <form id="contact-form" class="clearfix" action="<?php echo Doo::conf()->APP_URL ?>saveContactLead" method="post" novalidate="novalidate">
                                        <input type="hidden" name="cemail" value="<?php echo base64_encode($pdata['qmail']); ?>" />
                                        <span class="c-note"><?php echo SCTEXT('Drop us a mail if you have any query or concern about our services. Our representative will get in touch with you shortly.')?></span>
                                        <div class="contact-left">
                                        	<p class="input-block clearfix">
                                                <label class="required" for="contact_name"><?php echo SCTEXT('Name')?><span>*</span></label>
                                                <input class="valid" name="name" id="contact_name" value="" type="text">
                                            </p>
                                            <p class="input-block">
                                                <label class="required" for="contact_email"><?php echo SCTEXT('Email')?><span>*</span></label>
                                                <input class="valid" name="email" id="contact_email" value="" type="email">
                                            </p>
                                            <p class="input-block last">
                                                <label class="required" for="contact_url"><?php echo SCTEXT('Subject')?></label>
                                                <input value="<?php echo $data['sub']==''?'':'Details for: '.$data['sub'] ?>" class="valid" id="contact_url" name="subject" type="text">
                                            </p>
                                        </div><!--end:contact-left-->
                                        <div class="contact-right">
                                            <p class="textarea-block">
                                                <label class="required" for="contact_message"><?php echo SCTEXT('Message')?><span>*</span></label>
                                                <textarea class="valid" rows="6" cols="80" id="contact_message" name="message"></textarea>
                                            </p>
                                        </div><!--end:contact-right-->
                                        <div class="clear"></div>
                                        <p class="contact-button clearfix">
                                            <input id="submit-contact" value="<?php echo SCTEXT('Send message')?>" type="submit">
                                        </p>
                                        <div class="clear"></div>
                                    </form>

                                </div><!--contact-form-wrap-->

                            </aside><!--end:widget-->

                        </li>

                    </ul><!--end:bottom-sidebar-->

                </div><!--end:span12-->

            </div><!--end:row-fluid-->

        </div><!--end:wrapper-->




                    </div><!--end:span12-->

                </div><!--end:row-fluid-->

            </div><!--end:wrapper-->

        </section>


        <!--end:widget-->

    </div>








   <?php include('footer.php') ?>
