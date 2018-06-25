// JQuery Scrollbar Function
(function($) {
	$.fn.hasScrollBar = function() {
		return this.get(0).scrollHeight > this.height();
	}
})(jQuery);

// Outer HTML
(function($) {
	$.fn.outer = function() {
		return $($('<div></div>').html(this.clone())).html();
	}
})(jQuery);
