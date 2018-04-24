
jQuery(function($) {
	var el = $("#css-classes-hide");
	if( el.length ) {
		el.prop('checked', true)	
	}

	console.log($("#afa-include"))
	if( $("#afa-include").length ) {
		$("#afa-include").on('change', function() {
			$("#afa-settings-submit").submit();
		});	
	}
	
})