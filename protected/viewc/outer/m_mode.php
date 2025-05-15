
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SMS PANEL :: Under Upgrade - Coming Soon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Loading Bootstrap -->
    <link href="<?php echo Doo::conf()->APP_URL ?>global/mmode/css/bootstrap.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>	
	<!-- Loading Font Icons -->
	<link href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <!-- Loading  Style-->
    <link href="<?php echo Doo::conf()->APP_URL ?>global/mmode/css/animate.min.css" rel="stylesheet" type='text/css'>
    <link href="<?php echo Doo::conf()->APP_URL ?>global/mmode/css/style.css" rel="stylesheet" type='text/css'>

    <link rel="shortcut icon" href="<?php echo Doo::conf()->APP_URL ?>global/mmode/images/favicon.ico">
</head>
<body>
<div class="container">
   <header>
	  <div class="row">
		<div class="logo">
			<h1 class="text-center animated fadeInDown delayTwo">SMS Panel</h1>
		</div>
	   </div>				
   </header>
						
	<div class="row">
		<div class="col-md"><h2 class="text-center tagline"><?php echo $data['mm_data']['msg'] ?></h2></div>
	</div>
			
	<div class="row">
		<ul id="countdown" ms-user-select="none" class="animated fadeInUp delay">
	
			<li>
			<span id="days-sub">days</span>
			<input class="knob" id="days" data-readonly=true data-min="0" data-max="99" data-skin="tron" data-width="150" data-height="150" data-thickness="0.1" data-fgcolor="#ecf0f1">
			</li>
			
			<li>
			<span id="hours-sub">hours</span>
			<input class="knob" id="hours" data-readonly=true data-min="0" data-max="24" data-skin="tron" data-width="150" data-height="150" data-thickness="0.1" data-fgcolor="#ecf0f1">
			</li>
			
			<li>
			<span id="mins-sub">minutes</span>
			<input class="knob" id="mins" data-readonly=true data-min="0" data-max="60" data-skin="tron" data-width="150" data-height="150" data-thickness="0.1" data-fgcolor="#ecf0f1">
			</li>
			
			<li>	
			<span id="secs-sub">seconds</span>			
			<input class="knob" id="secs" data-readonly=true data-min="0" data-max="60" data-skin="tron" data-width="150" data-height="150" data-thickness="0.1" data-fgcolor="#ecf0f1">
		</li>
	
		</ul>	
	</div>
		
	<div class="row text-center">
		
	</div>

	<div class="row text-center">
		<div class="icon-effect">
		 <a href="<?php echo Doo::conf()->APP_URL ?>web/sign-in" class="animated bounceIn delay social-icon icon-lock"></a>	
		</div>	
	</div>
				
</div>

	<footer>
		<div class="text-center"><p>Copyright &copy; <?php echo date('Y') ?></p></div>
	</footer>		

<!-- Load jQuery library -->	
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/mmode/js/jquery.min.js"></script>

<!-- Load Javascripts -->
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/mmode/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/mmode/js/countdown.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/mmode/js/jquery.knob.js"></script>

<?php 
$dtstr = $data['mm_data']['end_date'];
?>
	
<script type="text/javascript">
( function ( $ ) {
	"use strict";
		$(document).ready(function(){
			
			$("#countdown").countdown({
				date: "<?php echo date('d M Y H:i:s',strtotime($dtstr)) ?>",
				format: "on"
			},
			
			function() {
				// callback function
			});
		});
		} ( jQuery ) );
        </script>	
		
<script type="text/javascript" src="<?php echo Doo::conf()->APP_URL ?>global/mmode/js/knob.js"></script>		
	    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="<?php echo Doo::conf()->APP_URL ?>global/mmode/js/html5shiv.js"></script>
    <![endif]-->
</body>

</html>