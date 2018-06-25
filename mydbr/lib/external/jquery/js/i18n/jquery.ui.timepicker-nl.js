jQuery(function($){
    $.timepicker.regional['nl'] = {
		ampm: false,
		timeFormat: 'hh:mm',
		timeOnlyTitle: 'Kies tijd',
		timeText: 'Tijd',
		hourText: 'Uur',
		minuteText: 'Minuten',
		secondText: 'Seconden'
	};
    $.timepicker.setDefaults($.timepicker.regional['nl']);
});
