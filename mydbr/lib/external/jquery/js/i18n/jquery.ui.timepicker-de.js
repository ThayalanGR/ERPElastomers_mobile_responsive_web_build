jQuery(function($){
    $.timepicker.regional['de'] = {
		ampm: false,
		timeFormat: 'hh:mm',
		timeOnlyTitle: 'Wählen Sie Zeit',
		timeText: 'Zeit',
		hourText: 'Stunden',
		minuteText: 'Minuten',
		secondText: 'Sekunden'
	};
    $.timepicker.setDefaults($.timepicker.regional['de']);
});
