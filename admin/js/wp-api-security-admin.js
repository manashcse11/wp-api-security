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
	$(document).ready(function(){
		$(".edit").click(function(){
			$("input[name='id']").val($(this).closest("tr").find("td:nth-child(6)").data("id"));
			$("input[name='api']").val($(this).closest("tr").find("td:nth-child(2)").text());
			$("input[name='api_description']").val($(this).closest("tr").find("td:nth-child(3)").text());
			$("input[name='api_key']").val($(this).closest("tr").find("td:nth-child(4)").text());
			$("input[name='ip']").val($(this).closest("tr").find("td:nth-child(5)").text());
		});

		$(".delete").click(function(){
			if (confirm("Are you sure, you want to delete this item?")) {
				$("input[name='delete_id']").val($(this).closest("tr").find("td:nth-child(6)").data("id"));
				$("#delete_submit").click();
			}
			return false;
		});

		$(".generate_key").click(function(){
			var d = new Date().getTime();

			if( window.performance && typeof window.performance.now === "function" )
			{
				d += performance.now();
			}

			var uuid = 'xxxyxx-xxxyxxx-4xxxxxx-yxxx-xxxxxyxxxxxx'.replace(/[xy]/g, function(c)
			{
				var r = (d + Math.random()*16)%16 | 0;
				d = Math.floor(d/16);
				return (c=='x' ? r : (r&0x3|0x8)).toString(16);
			});

			$("input[name='api_key']").val(uuid);
		});
	});

})( jQuery );
