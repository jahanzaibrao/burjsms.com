/* =================== Load Function =================== */
$(window).load(function() {
	"use strict";
	/* ----------- Page Loader ----------- */
	$(".loader-item").delay(700).fadeOut();
	$("#pageloader").delay(1000).fadeOut("slow");
	/* ----------- Slanting Divs ----------- */
	slantSection();
	
	/* ----------- Pretty Photo ----------- */
	$("a[data-rel^='prettyPhoto']").prettyPhoto({
 		theme: "light_square",
 	});
	
	/* ----------- Blog Flexslider ----------- */
	$('.flexslider').flexslider({
		animation: "fade",
		prevText: "", 
		nextText: "", 
		directionNav: true
  	});
	
	/* ----------- Partners Section ----------- */
	PartnersSlider_Init();
	/* ----------- Reviews Section ----------- */
	ReviewSlider_Init();
});
/* =================== Load Function Ends =================== */

/* =================== Resize Function =================== */
$(window).resize(function() {  
	"use strict";
	/* ----------- Slanting Divs ----------- */
	slantSection();
	/* ----------- Partners Section ----------- */
	PartnersSlider_Init();
	/* ----------- Reviews Section ----------- */
	ReviewSlider_Init();
});
/* =================== Resize Function Ends =================== */

/* =================== Ready Function =================== */
$(document).ready(function() {
	/* ----------- Slanting Divs ----------- */
	$(function() {
		"use strict";
		slantSection();
	});
	
	/* ----------- Scroll Navigation ----------- */
	$(function() {
		"use strict";
		$('.scroll').bind('click', function(event) {
			var $anchor = $(this);
			var headerH = $('#navigation').outerHeight();
				$('html, body').stop().animate({					
					scrollTop : $($anchor.attr('href')).offset().top  + 1 + "px"
				}, 1200, 'easeInOutExpo');		
			event.preventDefault();
		});
	});
	
	/* ----------- Menus hide after click -- mobile devices ----------- */	
	$(function() {
		"use strict";
		$('.nav li a').click(function () {
			$('.navbar-collapse').removeClass('in');
		});
	});
	
	/* ----------- Active Navigation ----------- */		
	$(function() {
		"use strict";
		$('body').scrollspy({ 
			target: '#topnav',
			offset: 95
		});
	});
	
	/* ----------- Fixed Menu on Scroll	----------- */
	$(function() {
		"use strict";
		$("#sticky-section").sticky({topSpacing:0});
	});
	
	/* ----------- Animation ----------- */
	$(function() {
		"use strict";
		$('.animated').appear(function() {
			var elem = $(this);
			var animation = elem.data('animation');
			if ( !elem.hasClass('visible') ) {
				var animationDelay = elem.data('animation-delay');
				if ( animationDelay ) {			
					setTimeout(function(){
					 elem.addClass( animation + " visible" );
					}, animationDelay);				
				} else {
					elem.addClass( animation + " visible" );
				}
			}
		});
	});
	
	/* ----------- Progress Bar ----------- */ 
	$(function() {
		"use strict";
		$('.progress-bar').each(function() {
			$(this).appear(function(){
			 var datavl = $(this).attr('data-percentage');
			 $(this).animate({ "width" : datavl + "%"}, '1200');
			});
		});
	});
	
	/* =================== Owl Carousel Slider =================== */	
	$(function() {
		"use strict";
		/* ----------- Your Work Section ----------- */
		$("#your-work-carousel").owlCarousel({
			items : 3,
			lazyLoad : true,
			autoPlay: false,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],		
			pagination: true,
			itemsCustom : false,
			itemsDesktop : [1199, 3],
			itemsDesktopSmall : [980, 3],
			itemsTablet : [768, 2],
			itemsTabletSmall : [480, 1],
			itemsMobile : [479, 1]
		});

		/* ----------- Partners Section ----------- */
		PartnersSlider_Init();

		/* ----------- Pricing Section ----------- */
		$("#pricing-slider").owlCarousel({
			items : 4,
			lazyLoad : true,
			autoPlay: true,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],		
			pagination: false,
			itemsCustom : false,
			itemsDesktop : [1199, 3],
			itemsDesktopSmall : [980, 3],
			itemsTablet : [768, 2],
			itemsTabletSmall : [480, 1],
			itemsMobile : [479, 1]
		});

		/* ----------- Team Section ----------- */
		$("#team-slider").owlCarousel({
			items : 3,
			lazyLoad : true,
			autoPlay: false,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],		
			pagination: false,
			itemsCustom : false,
			itemsDesktop : [1199, 3],
			itemsDesktopSmall : [980, 3],
			itemsTablet : [768, 2],
			itemsTabletSmall : [480, 1],
			itemsMobile : [479, 1]
		});
		
		/* ----------- Reviews Section ----------- */
		ReviewSlider_Init();

		/* ----------- Testimonials Section ----------- */
		$("#testimonials-slider").owlCarousel({
			items : 2,
			lazyLoad : true,
			autoPlay: false,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],		
			pagination: false,
			itemsCustom : false,
			itemsDesktop : [1199, 2],
			itemsDesktopSmall : [980, 2],
			itemsTablet : [768, 2],
			itemsTabletSmall : [480, 1],
			itemsMobile : [479, 1]
		});

		/* ----------- Project Section ----------- */	
		$("#single-project-carousel").owlCarousel({
			items : 1,
			lazyLoad : true,
			autoPlay:  true,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],		
			pagination: true,
			itemsCustom : false,
			itemsDesktop : [1199, 1],
			itemsDesktopSmall : [980, 1],
			itemsTablet : [768, 1],
			itemsTabletSmall : [480, 1],
			itemsMobile : [479, 1]
		});

		/* ----------- Skill Section ----------- */	
		$("#skill-slider").owlCarousel({
			items : 4,
			lazyLoad : true,
			autoPlay: true,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],		
			pagination: false,
			itemsCustom : false,
			itemsDesktop : [1199, 3],
			itemsDesktopSmall : [980, 3],
			itemsTablet : [768, 3],
			itemsTabletSmall : [640, 2],
			itemsMobile : [479, 1]
		});
	});
	
	/* ----------- Comment Form ----------- */	
	$(function() {
		"use strict";
		$('#commentform').bootstrapValidator({
				container: 'tooltip',
				feedbackIcons: {
					valid: 'fa fa-check',
					warning: 'fa fa-user',
					invalid: 'fa fa-times',
					validating: 'fa fa-refresh'
				},
				fields: {            
					name: {
						validators: {
							notEmpty: {
								message: 'Name is required. Please enter name.'
							}
						}
					},
					email: {
						validators: {
							notEmpty: {
								message: 'Email is required. Please enter email.'
							},
							emailAddress: {
								message: 'Please enter a correct email address.'
							}
						}
					},	
					subject: {
						validators: {
							notEmpty: {
								warning: {
								message: 'Subject is required. Please enter a subject.'
							}
							}
						}
					},	
					message: {
						validators: {
							notEmpty: {
								message: 'Message is required. Please enter your message.'
							}                    
						}
					}
				}
			})	
				
		
		function resetForm($form) {
			$form.find('input:text, input:password, input, input:file, select, textarea').val('');
			$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
			$form.find('.form-control-feedback').css('display', 'none');
		}
	});
	
	
	
});


/* ============================= Carousel Slider ============================= */	
/* ----------- Partners Section ----------- */
function PartnersSlider_Init() {
	var width = $(window).width();
	if( width > 480 ) {  
		$("#partners-slider").owlCarousel({
			items : 5,
			lazyLoad : true,
			autoPlay: false,
			navigation : true,
			navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],  
			pagination: false,
			itemsCustom : false,
			itemsDesktop : [1199, 5],
			itemsDesktopSmall : [980, 5],
			itemsTablet : [768, 3],
			itemsTabletSmall : [640, 2],
			itemsMobile : [480, 1]
		});
	}
}

/* ----------- Reviews Section ----------- */
function ReviewSlider_Init() {
	$("#reviewslider").owlCarousel({
		items :1,
		lazyLoad : false,
		autoPlay: true,
		navigation : true,
		navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],  
		pagination: false,
		itemsCustom : false,
		itemsDesktop : [1199, 1],
		itemsDesktopSmall : [980, 1],
		itemsTablet : [768, 1],
		itemsTabletSmall : [480, 1],
		itemsMobile : [479, 1]
	});
}

/* ----------- Slanting Div's ----------- */
var slantAngle = 3;
var valAlpha = slantAngle * (Math.PI / 180);
var valBeta = (90 - slantAngle) * (Math.PI / 180);

function slantSection() {
	var slantHeight = ($(window).width() * Math.sin(valAlpha))/ Math.sin(valBeta);
	var slantWidth = $(window).width();
	$('.slant-top-angle').each(function() {		
		$(this).css('border-top-width', + slantHeight + 'px ');
		$(this).css('border-left-width', + slantWidth + 'px');
	});	
	$('.slant-bottom-angle').each(function() {
		var slantPosition = $(this).parent('.slant-angle').outerHeight() - slantHeight + 2;		
		$(this).css('top', + slantPosition + 'px ');	
		$(this).css('border-bottom-width', + slantHeight + 'px ');
		$(this).css('border-right-width', + slantWidth + 'px');
	});	
};
/* Color Panel */
$(document).ready(function() {
     $("#theme-panel").show();
});