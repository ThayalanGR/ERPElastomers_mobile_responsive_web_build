
	$(document).ready(function(){
		setTimeout(updateContent, 500);
		setTimeout(updateHeader, 500);
		$(window).resize(function(e) {
			updateContent();
			updateHeader();
        });
	});
	
	if(!isSelectable)
	disableSelection(document);
	
