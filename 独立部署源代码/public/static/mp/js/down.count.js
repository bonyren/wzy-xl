(function ($) {
	$.fn.downCount = function(options, callback){
		var settings = $.extend({
			date: null,
			currentdate: null
		},options);
		if (!settings.date) {
			alert('日期未定义');
		}
		if (!Date.parse(settings.date)) {
			alert('日期格式不正确，格式如下 12/24/2012 12:00:00.')
		}
		var container = this;
		var tagert_date = new Date(settings.date),
			current_date = new Date(settings.currentdate);
		var difference = tagert_date - current_date;
		function countdown() {
			if (difference < 0) {
				clearInterval(interval);
				if (callback && typeof callback === 'function') callback();
                return;
			} else {
				var _second = 1000,
				_minute = _second * 60,
				_hour = _minute * 60,
				_day = _hour * 24;

				var days = Math.floor( difference / _day ),
					hours = Math.floor((difference % _day) / _hour),
	                minutes = Math.floor((difference % _hour) / _minute),
	                seconds = Math.floor((difference % _minute) / _second);

	                days = (String(days).length >= 2) ? days : '0' + days;
	                hours = (String(hours).length >= 2) ? hours : '0' + hours;
	                minutes = (String(minutes).length >= 2) ? minutes : '0' + minutes;
	                seconds = (String(seconds).length >= 2) ? seconds : '0' + seconds;


	            container.find('.days').text(days);
	            container.find('.hours').text(hours);
	            container.find('.minutes').text(minutes);
	            container.find('.seconds').text(seconds);
			}
			difference -= 1000;
		}
		var interval = setInterval(countdown,1000);
	};
})(jQuery);