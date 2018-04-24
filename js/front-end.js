jQuery(function($) {

	function afa_process_icons() {
		var prefix = "icon-";
		var iconRegex = new RegExp('^icon-');

		$('[class^="icon-"].menu-item').each(function() {
			var $this = $(this);
			var fa_classes = [];

			var classes = $(this).attr("class").split(' ');
			classes.forEach(function(className) {
				if( iconRegex.test(className )) {
					// get all classes that start with the icon- prefix
					var name = className.substring(prefix.length);
					fa_classes.push(name);
				} 
			});
			fa_classes.forEach(function(icon_name) {
				var a = $this.find('a'); 
				$this.find('a').prepend("<span class='fa fa-fw fa-" + icon_name + "'></span>&nbsp;");
			});
		});

		$('li.icon-blank.menu-item').each(function() {
			$(this).prepend("<span class='fa'></span>&nbsp;");
		});
	}

	afa_process_icons();

});