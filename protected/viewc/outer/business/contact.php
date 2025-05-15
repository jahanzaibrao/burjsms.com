<?php include('header.php') ?>
		<div id="content" style="padding-bottom:0px;">

		<!-- /// CONTENT  /////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		<div id="page-header">

            	<div class="row">
                	<div class="span12">

                        <h2><?php echo SCTEXT('Contact us')?></h2>
                        <p><?php echo SCTEXT('We are easily reachable.')?></p>

                    </div><!-- end .span12 -->
                </div><!-- end .row -->

            </div>

            <div class="row">
            	<div class="span4">

                   <div class="widget ewf_widget_contact_info">

                        <h4 class="widget-title"><span><?php echo SCTEXT('Contact Info')?></span></h4>

                        <ul>
                            <li>
                                <i class="ifc-home"></i>
                                <?php echo nl2br($pdata['address']) ?>
                            </li>
                            <li>
                                <i class="ifc-phone1"></i>
                                <span class="hidden-tablet">Tel: </span><?php echo $cdata['helpline'] ?>
                            </li>
                            <li>
                                <i class="ifc-message"></i>
                                <a class="contact-email" href="mailto:<?php echo $cdata['helpmail'] ?>"><?php echo $cdata['helpmail'] ?></a>
                            </li>

                        </ul>

                    </div>
                </div><!-- end .span4 -->
                <div class="span8">

                    <?php if($data['notif_msg']['msg']!=''){ ?>
                            <div class="alert success">
                    	<i class="ifc-info"></i>
                        <?php echo $data['notif_msg']['msg']; ?>
                    </div>
                            <?php } ?>
                    <form class="fixed" id="contact-form"  name="contact-form" method="post" action="<?php echo Doo::conf()->APP_URL ?>saveContactLead">
                        <input type="hidden" name="cemail" value="<?php echo base64_encode($pdata['qmail']); ?>" />
                        <fieldset>
                            <div id="response"></div>

                            <div class="row">
                            	<div class="span4">

                                    <p>
                                        <input class="span4" type="text" id="name" name="name" value="" placeholder="<?php echo SCTEXT('name')?>" />
                                    </p>

                                </div><!-- end .span4 -->
                                <div class="span4">

                                    <p>
                                        <input class="span4" type="text" id="email" name="email" value="" placeholder="<?php echo SCTEXT('email')?>" />
                                    </p>

                                </div><!-- end .span4 -->
                            </div><!-- end .row -->

                            <p>
                                <input class="span8" type="text" id="subject" name="subject" value="<?php echo $data['sub']==''?'':'Details for: '.$data['sub'] ?>" placeholder="<?php echo SCTEXT('subject')?>"  />
                            </p>

                            <p>
                                <textarea class="span8" id="message" name="message" rows="7" cols="25" placeholder="<?php echo SCTEXT('write a message')?>"></textarea>
                            </p>

                            <p class="last text-right">
                                <input id="submit-contact" type="submit" name="submit" class="btn btn-blue" value="<?php echo SCTEXT('Submit')?>" />
                            </p>
                        </fieldset>
					</form><!-- end #contact-form -->

                </div><!-- end .span8 -->
            </div><!-- end .row -->

		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #content -->

<?php include('footer.php') ?>
