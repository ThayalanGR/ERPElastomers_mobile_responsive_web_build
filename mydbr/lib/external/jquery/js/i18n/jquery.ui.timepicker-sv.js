jQuery(function($){
    $.timepicker.regional['sv'] = {
		ampm: false,
		timeFormat: 'hh:mm',
		timeOnlyTitle: 'Välj tid',
		timeText: 'Tid',
		hourText: 'Timmar',
		minuteText: 'Minuter',
		secondText: 'Sekunder'
	};
    $.timepicker.setDefaults($.timepicker.regional['sv']);
});
