(function( $ ) {
	$.widget( "ui.tooltip", {
		_create: function() {
			// Get Objects
			self		=	this;
			obj			=	this.element;
			title		=	$(obj).attr("title");
			method		=	($(obj).attr("method"))?$(obj).attr("method"):"";
			
			if(title != null && title != ""){
				title	=	title.split("\n").join("<br />");
			}
			
			// Remove Titles from Object
			obj.removeAttr("title");
			
			// Set Tooltip
			var tooltip = this.tooltip = $( "<div>" )
				.insertAfter(obj)
				.html(
					"<div class='ui-tooltip-arrow'>&nbsp;</div>" +
					"<div class='ui-corner-all ui-tooltip-text'>" + title + "</div>"
				)
				.addClass( "ui-tooltip" );
			
			$(obj).mouseover(function(e) {
                tooltip.fadeIn();
            })
			.mousemove(function(e) {
				w	=	$(document).width();
				h	=	$(document).height();
				t	=	e.pageY + 10;
				l	=	e.pageX + 2;
				
				objP	=	$(obj).position();
				objL	=	objP.left;
				objT	=	objP.top;
				objW	=	$(obj).width();
				objH	=	$(obj).height();
				
				l	=	l - (objW / 2);
				
				if(method.toLowerCase() == "static"){
					l = objL + ((objW/2) - (tooltip.width()/2));
					t = objT + objH;
				}
				
				tooltip.offset({top:t, left:l});
            })
			.mouseout(function(e){
				$(tooltip).css("display", "none");
			})
			.mouseleave(function(e) {
                $(tooltip).css("display", "none");
            });;
		},

		destroy: function() {
			this.tooltip.remove();
			$.Widget.prototype.destroy.call( this );
		}
	});
})( jQuery );