(function($) {
	
	function rand(min, max) {
		return Math.floor((Math.random() * (max - min + 1)) + min);
	}
	
	var defaults = {
		'randMin': 100,
		'randMax': 2000,
		'time':	1000
	};
	
	$(function() {
		
		var settings = $.extend(defaults, animate); 

		$('.animate').click(function(e) {
			e.preventDefault();

			var obj = $(this);
			var time = settings.time;

			if(obj.hasClass('rand')) {

				time = rand(settings.randMin, settings.randMax);

			} else {

				var result = /time[0-9]+/.exec(obj.attr('class'));
				if(result)
					time = parseInt(new String(result).replace('time', ''));

			}

			if( $(window).width() > 768 ){
				$('html, body').animate({				
					scrollTop: $(obj.attr('href')).offset().top - 80
				}, time);
			}else{
				$('html, body').animate({				
					scrollTop: $(obj.attr('href')).offset().top - 58
				}, time);
			}

		});
	
	});


})(jQuery);

var animate = {
	'time': 500,
	'randMin': 700,
	'randMax': 900
};