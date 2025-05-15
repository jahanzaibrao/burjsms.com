/*!
 * Start Bootstrap - SB UI Kit Pro v1.0.2 (https://shop.startbootstrap.com/product/sb-ui-kit-pro)
 * Copyright 2013-2020 Start Bootstrap
 * Licensed under SEE_LICENSE (https://github.com/BlackrockDigital/sb-ui-kit-pro/blob/master/LICENSE)
 */
(function ($) {
	"use strict";

	// Enable Bootstrap tooltips via data-attributes globally
	$('[data-toggle="tooltip"]').tooltip();

	// Enable Bootstrap popovers via data-attributes globally
	$('[data-toggle="popover"]').popover();

	$(".popover-dismiss").popover({
		trigger: "focus",
	});

	// Activate Feather icons
	feather.replace();

	// Activate Bootstrap scrollspy for the sticky nav component
	$("body").scrollspy({
		target: "#stickyNav",
		offset: 82,
	});

	// Scrolls to an offset anchor when a sticky nav link is clicked
	$('.nav-sticky a.nav-link[href*="#"]:not([href="#"])').click(function () {
		if (
			location.pathname.replace(/^\//, "") ==
				this.pathname.replace(/^\//, "") &&
			location.hostname == this.hostname
		) {
			var target = $(this.hash);
			target = target.length
				? target
				: $("[name=" + this.hash.slice(1) + "]");
			if (target.length) {
				$("html, body").animate(
					{
						scrollTop: target.offset().top - 81,
					},
					200
				);
				return false;
			}
		}
	});

	//submit contact
	if ($("#submitContact").length > 0) {
		$("#submitContact").on("click", function () {
			if ($("#inputName").val() == "") {
				$("#msg").html(
					`<div class="alert alert-danger" role="alert">Name cannot be empty!</div>`
				);
				return;
			}
			if ($("#inputEmail").val() == "") {
				$("#msg").html(
					`<div class="alert alert-danger" role="alert">Email cannot be empty!</div>`
				);
				return;
			}
			if ($("#inputMessage").val() == "") {
				$("#msg").html(
					`<div class="alert alert-danger" role="alert">Please enter your message!</div>`
				);
				return;
			}
			$.ajax({
				type: "post",
				url: "https://console.smshandover.com/saveContactLead",
				data: {
					name: $("#inputName").val(),
					email: $("#inputEmail").val(),
					subject: "Contact Request from Website",
					message: $("#inputMessage").val(),
					fe_site_xhr: 1,
				},
				success: function (res) {
					if (res == "DONE") {
						$("#msg").html(
							`<div class="alert alert-success" role="alert">Thank you for reaching out! We'll get back to you shortly</div>`
						);
					} else {
						$("#msg").html(
							`<div class="alert alert-danger" role="alert">${res}</div>`
						);
					}
				},
			});
		});
	}

	// Collapse Navbar
	// Add styling fallback for when a transparent background .navbar-marketing is scrolled
	var navbarCollapse = function () {
		if ($(".navbar-marketing.bg-transparent.fixed-top").length === 0) {
			return;
		}
		if ($(".navbar-marketing.bg-transparent.fixed-top").offset().top > 0) {
			$(".navbar-marketing").addClass("navbar-scrolled");
		} else {
			$(".navbar-marketing").removeClass("navbar-scrolled");
		}
	};
	// Collapse now if page is not at top
	navbarCollapse();
	// Collapse the navbar when page is scrolled
	$(window).scroll(navbarCollapse);
})(jQuery);
