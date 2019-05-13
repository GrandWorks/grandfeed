(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( window ).load(function() {
		$("#details-form").submit(function(e){
			var showInstagram = $("#show-instagram:checked").val();
			var showTwitter = $("#show-twitter:checked").val();
			var instagramCount = 0;
			var twitterCount = 0;
			// Instagram
			if(showInstagram)
			{
				var instagramFields = $("[data-validate-instagram]");

				instagramFields.each(function(index){
					var field = instagramFields[index];
					if(field.value.trim()=="")
					{
						$("#"+field.id).css("border-color","red");
						$("#"+field.id).siblings(".error").html("Field is required").css("color","red");
					}
					else
					{
						$("#"+field.id).css("border-color","#ddd");
						$("#"+field.id).siblings(".error").html("");
						instagramCount++;
					}
				});
			}

			// Twitter
			if(showTwitter)
			{
				var twitterFields = $("[data-validate-twitter]");
				twitterFields.each(function(index){
					var field = twitterFields[index];
					if(field.value.trim()=="")
					{
						$("#"+field.id).css("border-color","red");
						$("#"+field.id).siblings(".error").html("Field is required").css("color","red");
					}
					else
					{
						$("#"+field.id).css("border-color","#ddd");
						$("#"+field.id).siblings(".error").html("");
						twitterCount++;
					}
				});
			}

			if(instagramCount != $("[data-validate-instagram]").length || twitterCount != $("[data-validate-twitter]").length)
			{
				// $("#details-form").submit();
				e.preventDefault();
			}

		});
	});
	 

})( jQuery );
