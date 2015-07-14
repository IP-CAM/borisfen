// JavaScript Document
/* Top Div Arrow */
jQuery(document).ready(function(){
	// hide #back-top first
	jQuery("#topon").hide();
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('#topon').fadeIn();
			} else {
				jQuery('#topon').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery('#topon a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});