jQuery(function($){
    $.timepicker.regional['it'] = {
		ampm: false,
		timeFormat: 'hh:mm',
		timeOnlyTitle: 'Scegli il tempo',
		timeText: 'Tempo',
		hourText: 'Ore',
		minuteText: 'Minuti',
		secondText: 'Secondi'
	};
    $.timepicker.setDefaults($.timepicker.regional['it']);
});
