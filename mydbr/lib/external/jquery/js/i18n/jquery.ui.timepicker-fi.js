jQuery(function($){
    $.timepicker.regional['fi'] = {
		ampm: false,
		timeFormat: 'hh:mm',
		timeOnlyTitle: 'Valitse aika',
		timeText: 'Aika',
		hourText: 'Tunnit',
		minuteText: 'Minuutit',
		secondText: 'Sekunnit'
	};
    $.timepicker.setDefaults($.timepicker.regional['fi']);
});
