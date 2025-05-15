	jQuery(document).ready(function($) {
	
		  $("#green" ).click(function(){
			  $("#color" ).attr("href", "assets/css/green.css");
			  return false;
		  });
		  
		  $("#red" ).click(function(){
			  $("#color" ).attr("href", "assets/css/red.css");
			  return false;
		  });
		  
		  $("#blue" ).click(function(){
			  $("#color" ).attr("href", "assets/css/blue.css");
			  return false;
		  });
		  
		  $("#orange" ).click(function(){
			  $("#color" ).attr("href", "assets/css/orange.css");
			  return false;
		  });
		  
		  $("#cyan" ).click(function(){
			  $("#color" ).attr("href", "assets/css/cyan.css");
			  return false;
		  });
		  
		  $("#light_pink" ).click(function(){
			  $("#color" ).attr("href", "assets/css/light_pink.css");
			  return false;
		  });
		  
		  
		  
		  
		  
		  
		  
		  // picker buttton
		  $(".picker_close").click(function(){
			  
			  	$("#choose_color").toggleClass("position");
			  
		   });
		  
		  
	});