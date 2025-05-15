<?php include('header.php') ?>
		<div id="content" style="padding-bottom:0px;">
		
		<!-- /// CONTENT  /////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		<div id="page-header">
            
            	<div class="row">
                	<div class="span12">
                    	
                        <h2><?php echo SCTEXT('Privacy Policy')?></h2>
                        <p><?php echo SCTEXT('We care for information privacy.')?></p>
                        
                    </div><!-- end .span12 -->
                </div><!-- end .row -->
            
            </div>
            
            <div class="row" style="margin-top:-40px;">
                <div class="span12">
                        <?php echo htmlspecialchars_decode($data['content']); ?>
                    	
                    </div>
            </div><!-- end .row -->
		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

		</div><!-- end #content -->
		
<?php include('footer.php') ?>