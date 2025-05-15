
    <div class='footer centered-text'>
      <div class='spacing'></div>
      <div class='row'>
        <div class='large-12 columns'>
          <h1 class='light'>
            
            <div class='spacing'></div>
            <?php echo $cdata['company_name']; ?>
          </h1>
          <div class='spacing'></div>
          <a class="red" href="<?php echo Doo::conf()->APP_URL ?>web/terms"><?php echo SCTEXT('Terms of Use')?></a> &nbsp;<span style="color:#fff;">|</span>&nbsp; <a class="red" href="<?php echo Doo::conf()->APP_URL ?>web/privacy"><?php echo SCTEXT('Privacy Policy')?></a>
          <p class='copyright'>Copyright <?php echo Date('Y'); ?> <?php echo $cdata['company_name']; ?></p>
        </div>
      </div>
    </div>
    <script src='<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/jquery.countTo.js'></script>
    <script src='<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/jquery.appear.js'></script>
    <script src='<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/jquery.sequence-min.js'></script>
    <script src='<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/jquery.validate.js'></script>
    <script src="<?php echo Doo::conf()->APP_URL ?>global/rskins/modern/javascripts/app.js" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans%7CDroid+Serif:400,400italic%7CPT+Sans:400,700" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript">

		var tpj=jQuery;
		tpj.noConflict();

		tpj(document).ready(function() {
            
        //test gateway widget
            tpj(document).on("click","#submit_tgw",function(){
                var ele = tpj(this);
                ele.html("Sending ...").addClass('disabledBox');
                tpj.ajax({
                    url: app_url+'submitGwTestSms',
                    data: {mobile: tpj("#tgw_contact").val()},
                    type: 'post',
                    success: function(res){
                        var mydata = JSON.parse(res);
                        if(mydata.result=='error'){
                            //fail
                            var rstr = '<div class="alert-box myalert alert"><p><i class="icon-cancel-circled"></i> '+mydata.msg+'</p></div>';
                            tpj("#tgw_msg").html(rstr);
                            ele.html("<?php echo SCTEXT('Send SMS')?>").removeClass('disabledBox');
                        }else{
                            //success
                            var rstr = '<div class="alert-box myalert info"><p><i class="icon-info-circled"></i> '+mydata.msg+'</p></div>';
                            tpj("#tgw_msg").html(rstr);
                            tpj("#tgw_contact").val('');
                            ele.html("<?php echo SCTEXT('Send SMS')?>").removeClass('disabledBox');
                        }
                    }
                })
            })
        })
</script>
  </body>
</html>

<!-- Localized -->