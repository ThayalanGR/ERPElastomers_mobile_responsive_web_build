(function( $ ) {
	$.widget( "ui.filters", {
		options:{
			filterHead:'#content_head',
			filterBody:'#content_body',
			filterType:'table',
			filterPos:[],
			filterData:[],
			headData:[],
			bodyData:[],
			isOpen:false,
			prevOpen:null,
			onUpdate:null
		},
		_create: function() {
			// Get Objects
			self		=	this;
			obj			=	this.element;
			objHead		=	$(obj).find(this.options.filterHead + " table tr th");
			objCont		=	$(obj).find(this.options.filterBody + " table tr");
			headData	=	[];
			
			obj.addClass("ui-filters");
			objHead.each(function(index, element) {
				headHtml	=	self._trim($(element).html());
				headText	=	self._trim($(element).text());
				filterType	=	$(element).attr("filter");
				
				headData.push(headText);
				if(filterType != 'ignore'){
					$(element).addClass("ui-filters-head");
					$(element).html(
						"<span class='ui-filters-head-icon ui-filters-icon'>" + headHtml + "</span> \
						<div class='ui-filters-window'> \
							<input type='hidden' class='ui-filters-type' value='" + filterType + "' /> \
							<input type='hidden' class='ui-filters-no' value='0' /> \
							<div class='ui-select-all' style=\"display:" + ((filterType!='date')?'display':'none') + "\"> \
								<input type='checkbox' id='select-all-4-" + headText + "' /> <label for='select-all-4-" + headText + "'>Select All</label> \
							</div> \
							" + ((filterType=='date')
									?"<div class='ui-filters-date-cover ui-corner-all'> \
										<b>Date Range</b> \
										<hr/> \
										<table border='0' cellspacing='0' cellpadding='2'> \
											<tr> \
												<td style='width:25%'> \
													From \
												</td> \
												<td> \
													: <input type='text' class='from' style='width:80%' value='DD/MM/YYYY' text='DD/MM/YYYY' /> \
													<img src='/images/date-icon.jpg' align='absmiddle' style='position:relative;top:-2px;' onclick='$(this).prev(\".from\").focus()' /> \
												</td> \
											</tr> \
											<tr> \
												<td> \
													To \
												</th> \
												<td> \
													: <input type='text' class='to' style='width:80%' value='DD/MM/YYYY' text='DD/MM/YYYY' /> \
													<img src='/images/date-icon.jpg' align='absmiddle' style='position:relative;top:-2px;' onclick='$(this).prev(\".to\").focus()' /> \
												</td> \
											</tr> \
											<tr> \
												<td colspan=2 align='right'> \
													<button class='clear'>Clear</button> \
												</td> \
											</tr> \
										</table> \
									</div>"
									:"<input type='text' class='invisible_text' value='Search' text='Search' onfocus='FieldHiddenValue(this, \"in\", \"Search\")' onblur='FieldHiddenValue(this, \"out\", \"Search\")' />"
								) + " \
							<div class='ui-filters-options'> \
								&nbsp; \
							</div> \
						</div>"
					)
					
					$(element).find(".ui-filters-window .ui-select-all input:checkbox").click(function(e) {
						chkStat		=	($(this).attr("checked") == true || $(this).attr("checked") == 'checked')
											?true:false;
						$(this).parent().parent().find(".ui-filters-options input:checkbox").attr("checked", chkStat);
						self._getSelData($(this).parent().parent());
					});
					
					if(filterType != 'date'){
						$(element).find(".ui-filters-window input:text").keyup(function(e) {
							self._fineFilterData(this);
						});
					}
					else{
						dates = $(element).find(".ui-filters-window .from, .ui-filters-window .to").datepicker({
							dateFormat:'dd/mm/yy',
							changeMonth: true,
							changeYear:true,
							onSelect: function( selectedDate, inst ) {
								var option = $(this).hasClass("from") ? "minDate" : "maxDate",
									instance = $( this ).data( "datepicker" );
									date = $.datepicker.parseDate(
										instance.settings.dateFormat ||
										$.datepicker._defaults.dateFormat,
										selectedDate, instance.settings );
								dates.not( this ).datepicker( "option", option, date );
								self._fineFilterData(this, 'date');
							}
						});
						$(element).find(".ui-filters-window .clear").button().click(function(e) {
							$(element).find(".ui-filters-window .from, .ui-filters-window .to")
								.val($(element).find(".ui-filters-window .from, .ui-filters-window .to").attr("text"))
								.datepicker('destroy');
							dates = $(element).find(".ui-filters-window .from, .ui-filters-window .to").datepicker({
								dateFormat:'dd/mm/yy',
								changeMonth: true,
								changeYear:true,
								onSelect: function( selectedDate ) {
									var option = $(this).hasClass("from") ? "minDate" : "maxDate",
										instance = $( this ).data( "datepicker" );
										date = $.datepicker.parseDate(
											instance.settings.dateFormat ||
											$.datepicker._defaults.dateFormat,
											selectedDate, instance.settings );
									dates.not( this ).datepicker( "option", option, date );
									self._fineFilterData(this, 'date');
								}
							});
							setTimeout(function(){
								self._fineFilterData($(element).find(".ui-filters-window .from"), 'date');
							}, 50);
						});
					}
					
					$(element).find(".ui-filters-head-icon").click(function(e) {
						if(self.options.prevOpen != null && self.options.prevOpen != this){
							$(self.options.prevOpen).next(".ui-filters-window").slideUp('fast');
							self.options.prevOpen	=	null;
							self.options.isOpen		=	false;
							self.options.filterPos	=	[];
							if($(self.options.prevOpen).next(".ui-filters-window").find(".ui-filters-type").val() == 'date'){
								$(self.options.prevOpen).next(".ui-filters-window").find(".from")
									.datepicker({minDate:null, maxDate:null})
									.datepicker('refresh');
								$(self.options.prevOpen).next(".ui-filters-window").find(".to")
									.datepicker({minDate:null, maxDate:null})
									.datepicker('refresh');
							}
						}
						
						objWindow	=	$(this).next(".ui-filters-window");
						rowTxt		=	$(this).text();
						filterType	=	$(objWindow).find(".ui-filters-type").val();
						filterObj	=	Number($(objWindow).find(".ui-filters-no").val());
						
						winWidth	=	$(document.body).width();
						headPos		=	$(this).parent().position();
						headHgt		=	$(this).parent().height();
						headInHgt	=	$(this).height();
						objPos		=	objWindow.position();
						objWidth	=	objWindow.width();
						objHeight	=	objWindow.height();
						diffWidth	=	winWidth - (objWidth + 25);
						diffX		=	(headPos.left > diffWidth)?diffWidth:headPos.left;
						diffY		=	(headPos.top + headHgt + headInHgt + 1);
						
						if(self.options.isOpen == false){
							objWindow.css("left", diffX);
							objWindow.css("top", diffY);
							if(!objWindow.find("input:text").hasClass("from") && !objWindow.find("input:text").hasClass("to")){
								objWindow.find("input:text").val(objWindow.find("input:text").attr("text"))
								objWindow.find("input:text").removeClass("normal_text").addClass("invisible_text");
							}
							objWindow.find(".ui-filters-options")
								.html(self._showFilteredData(self._showFullFilteredData(rowTxt), rowTxt, filterObj))
								.css("display", (filterType!='date')?'block':'none');
							objWindow.find(".ui-select-all input:checkbox")
								.attr("checked", (objWindow.find(".ui-filters-options input:checkbox:checked").length > 0 && objWindow.find(".ui-filters-options input:checkbox").length == objWindow.find(".ui-filters-options input:checkbox:checked").length));
							objWindow.find(".ui-filters-options input:checkbox")
								.click(function(e){
									self._getSelData($(this).parent().parent().parent());
								});
							self.options.filterPos	=	{
								startX:diffX,
								endX:(diffX + objWidth + 15 + ((filterType == 'date')?100:0)),
								startY:headPos.top,
								endY:(diffY + objHeight + 15)
							};
							
							if(filterType == 'date'){
								self._fineFilterData(objWindow.find(".ui-filters-date-cover .from"), 'date');
							}
						
							objWindow.slideDown(250);
						}
						else{
							objWindow.slideUp(250);
							self.options.filterPos	=	[];
						}
						self.options.prevOpen	=	this;
						self.options.isOpen		=	(!self.options.isOpen)?true:false;
                    })
				}
            });
			self.options.headData	=	headData;
			
			$(document).click(function(e) {
				mouseX		=	e.clientX;
				mouseY		=	e.clientY;
				filterPos	=	self.options.filterPos;
				
				if(self._arrayLength(filterPos) > 0){
					if(mouseX <= filterPos.startX || mouseX >= filterPos.endX ||
						mouseY <= filterPos.startY || mouseY >= filterPos.endY){
						if(self.options.prevOpen != null){
							$(self.options.prevOpen).next(".ui-filters-window").slideUp('fast');
							self.options.prevOpen	=	null;
							self.options.isOpen		=	false;
							self.options.filterPos	=	[];
						}
					}
				}
            });
			
			$(window).blur(function(e) {
				if(self.options.prevOpen != null){
					$(self.options.prevOpen).next(".ui-filters-window").slideUp('fast');
					self.options.prevOpen	=	null;
					self.options.isOpen		=	false;
					self.options.filterPos	=	[];
				}
            });
			
			// Body Contents
			self._construct(objCont);
		},
		
		renew:function(){
			objCont		=	$(obj).find(this.options.filterBody + " table tr");
			this._construct(objCont);
		},
		
		_construct:function(obj){
			$this		=	this;
			bodyData	=	[];
			headData	=	this.options.headData;
			$(obj).each(function(index, element) {
                bodyCont			=	[];
				bodyCont['_row']	=	$(element).outer();
				
				$(this).find("td").each(function(index, element) {
                    bodyCont[headData[index]]			=	$(this).text();
                    bodyCont[headData[index] + "_html"]	=	$(this).html();
                });
				
				bodyData.push(bodyCont);
            });
			this.options.bodyData	=	bodyData;
		},
		
		_getSelData:function(obj){
			$this		=	this;
			totObj		=	0;
			totChk		=	0;
			chkItems	=	[];
			
			if(obj != null){
				headObj		=	$(obj).prev(".ui-filters-head-icon");
				filtName	=	headObj.html();
				filterNo	=	$(obj).find(".ui-filters-no");
				filtNo		=	Number(filterNo.val());
				$(obj).find(".ui-filters-options div input:checkbox").each(function(index, element) {
					chkStat		=	($(this).attr("checked") == true || $(this).attr("checked") == 'checked')
										?true:false;
					
					if(chkStat == true){
						chkItems.push($(this).val());
						totChk++;
					}
					totObj++;
				});
				
				$(obj).find(".ui-select-all input:checkbox").attr("checked", (totObj == totChk));
				if(totObj == totChk){
					if(filtNo > 0){
						$this.options.filterData	=	$this._arrayRemove($this.options.filterData, filtNo-1);
					}
					filterNo.val(0);
					headObj.removeClass("ui-filters-pipe-icon").addClass("ui-filters-icon");
				}
				else{
					filtNoVal	=	(filtNo > 0)?filtNo:$this.options.filterData.length+1;
					filterNo.val(filtNoVal);
					headObj.removeClass("ui-filters-icon").addClass("ui-filters-pipe-icon");
					$this.options.filterData[filtNoVal-1]	=	{
						_obj:headObj,
						_filter:filterNo,
						_row:filtName,
						_data:chkItems
					};
					
					if($this.options.filterData.length > filtNoVal)
					$this.options.filterData	=	$this._setFiltersTo(filtNoVal);
				}
				
				$this._showFilteredContent();
			}
		},
		
		_setFiltersTo:function(no){
			$this		=	this;
			filterData	=	$this.options.filterData;
			nFiltData	=	new Array();
			
			if(no != null && !isNaN(no) && no > 0){
				for(fd=0; fd<no; fd++){
					nFiltData.push(filterData[fd]);
				}
				$this._removeFiltersFrom(filterData, no);
				filterData	=	nFiltData;
			}
			
			return filterData;
		},
		
		_removeFiltersFrom:function(fData, no){
			$this		=	this;
			filterData	=	fData;
			
			for(fd=no; fd<filterData.length; fd++){
				if(filterData[fd]._obj != null){
					$(filterData[fd]._obj).removeClass("ui-filters-pipe-icon").addClass("ui-filters-icon");
					$(filterData[fd]._filter).val(0);
				}
			}
		},
		
		_processData:function(data, row){
			$this		=	this;
			headData	=	$this.options.headData;
			data		=	($this._isArray(data))?data:$this.options.bodyData;
			filterArr	=	[];
			
			if(!$this._isEmpty(row) && $this._isArray(data) && $this._inArray(headData, row)){
				for(fa=0; fa<data.length; fa++){
					arrData	=	data[fa][row];
					if(!$this._inArray(filterArr, arrData)){
						filterArr.push(arrData);
					}
				}
			}
			return filterArr;
		},
		
		_showFilteredData:function(data, row, filtNo){
			$this		=	this;
			headData	=	$this.options.headData;
			data		=	($this._isArray(data))?data:$this.options.bodyData;
			filterData	=	'';
			filterNo	=	(!isNaN(filtNo) && Number(filterObj) > 0)?filtNo:0;
			ftSelData	=	(filterNo > 0 && $this.options.filterData[filterNo-1]._row == row)?$this.options.filterData[filterNo-1]._data:null;
			
			if(!$this._isEmpty(row) && $this._isArray(data) && $this._inArray(headData, row)){
				filterArr	=	$this._processData(data, row);
				for(fd=0; fd<filterArr.length; fd++){
					filterId	=	(row.split(" ").join("")) + "_" + fd + "_" + (filterArr[fd].split(" ").join(""));
					filtData	=	$this._trim(filterArr[fd]);
					filterName	=	(filtData.length > 30)?filtData.substr(0, 29):filtData;
					filterTitle	=	(filtData.length > 30)?"title= '" + filtData + "'":"";
					filterChk	=	(ftSelData == null)
										?"checked='checked'"
										:(ftSelData.length>0 && $this._inArray(ftSelData, filterArr[fd]))
											?"checked='checked'"
											:"";
					filterData	+=	"<div " + filterTitle + "> \
										<input type='checkbox' id='" + filterId + "' value='" + filtData + "' " + filterChk + " /> \
										<label for='" + filterId + "'>" + filterName + "</label> \
									</div>";
				}
			}
			return filterData;
		},
		
		_showFullFilteredData:function(row){
			$this		=	this;
			filterData	=	$this.options.filterData;
			finalArr	=	$this.options.bodyData;
			
			if(filterData.length > 0){
				for(fd=0; fd<filterData.length; fd++){
					newArr		=	[];
					_row		=	filterData[fd]._row;
					_data		=	filterData[fd]._data;
					
					if(row == _row)
					break;
					
					for(fdr=0; fdr<finalArr.length; fdr++){
						_rData	=	finalArr[fdr][_row].trim();
						if($this._inArray(_data, _rData)){
							newArr.push(finalArr[fdr]);
						}
					}
					finalArr	=	newArr;
				}
			}
			else{
				finalArr	=	$this.options.bodyData;
			}
			
			return finalArr;
		},
		
		_showFilteredContent:function(){
			$this		=	this;
			bodyCont	=	$($this.options.filterBody + " table");
			filtArr		=	$this._showFullFilteredData();
			filtRows	=	$this._arrayRows(filtArr, "_row");
			
			if(filtRows == null)
			filtRows	=	$this._arrayRows($this.options.bodyData, "_row");
			
			$(bodyCont).find("tr").remove();
			$(bodyCont).append(filtRows.join(""));
			$(bodyCont).find("tr").each(function(index, element) {
				$(element).removeClass($(element).hasClass("content_rows_dark") ? "content_rows_dark" : "content_rows_light");
                $(element).addClass((index%2)?"content_rows_dark":"content_rows_light");
            });
			
			if($this.options.onUpdate != null && typeof $this.options.onUpdate == 'function')
				$this.options.onUpdate();
		},
		
		_fineFilterData:function(obj, type){
			$this		=	this;
			type		=	(type)?type:'';
			txtObj		=	$(obj);
			txtSearch	=	$(obj).val().toLowerCase();
			txtType		=	(type == 'date')
								?$(obj).parent().parent().parent().parent().parent().parent().find(".ui-filters-type").val()
								:$(obj).parent().find(".ui-filters-type").val();
			txtFilter	=	(type == 'date')
								?$(obj).parent().parent().parent().parent().parent().parent()
								:$(obj).parent();
			txtOption	=	(type == 'date')
								?$(obj).parent().parent().parent().parent().parent().parent().find(".ui-filters-options div")
								:$(obj).parent().find(".ui-filters-options div");
			
			txtOption.each(function(index, element) {
				thisTxt		=	$(element).find("label").html();
				thisChkBox	=	$(element).find("input:checkbox");
				thisShow	=	true;
				thisChk		=	true;
				
				switch(txtType){
					case "date":
						dateFrom		=	$(txtObj).hasClass("from")
												? $(txtObj).datepicker('getDate')
												: $(txtObj).parent().parent().parent().find(".from").datepicker('getDate');
						dateFromTxt		=	$(txtObj).hasClass("from")
												? $(txtObj).val()
												: $(txtObj).parent().parent().parent().find(".from").val();
						dateFromTxt		=	(dateFromTxt == 'DD/MM/YYYY')?null:dateFromTxt;
						dateTo			=	$(txtObj).hasClass("to")
												? $(txtObj).datepicker('getDate')
												: $(txtObj).parent().parent().parent().find(".to").datepicker('getDate');
						dateToTxt		=	$(txtObj).hasClass("to")
												? $(txtObj).val()
												: $(txtObj).parent().parent().parent().find(".to").val();
						dateToTxt		=	(dateToTxt == 'DD/MM/YYYY')?null:dateToTxt;
						thisDate		=	$.datepicker.parseDate("dd-M-yy", thisTxt);
						thisShow		=	(dateFromTxt != null && dateToTxt != null)
												?(thisDate >= dateFrom && thisDate <= dateTo)
												:(dateFromTxt != null)
													?(thisDate >= dateFrom)
													:(dateToTxt != null)
														?(thisDate <= dateTo)
														:thisShow;
						thisChk			=	thisShow;
					break;
					case "currency":
						thisTxt			=	thisTxt.toNumber();
						if(txtSearch.indexOf("-") > -1){
							txtSplit	=	txtSearch.split("-");
							thisShow	=	(txtSplit[0] != null && !isNaN(txtSplit[0]) && Number(txtSplit[0]) >= 0)
												?(txtSplit[1] != null && !isNaN(txtSplit[1]) && Number(txtSplit[1]) >= 0)
													?(thisTxt != null && !isNaN(thisTxt) && Number(thisTxt) >= 0)
														?(Number(thisTxt) >= Number(txtSplit[0]) && Number(thisTxt) <= Number(txtSplit[1]))
														:false
													:false
												:false;
						}
						else{
							thisShow	=	(txtSearch == '' || String(thisTxt).toLowerCase().indexOf(txtSearch) > -1);
						}
						thisChk			=	(thisChkBox.attr("checked") == true || thisChkBox.attr("checked") == 'checked');
					break;
					default:
						thisShow		=	(txtSearch == '' || thisTxt.toLowerCase().indexOf(txtSearch) > -1);
						thisChk			=	(thisChkBox.attr("checked") == true || thisChkBox.attr("checked") == 'checked');
					break;
				}
				thisChkBox.attr("checked", thisChk);
				$(element).css("display", (thisShow)?'block':'none');
			});
			
			$this._getSelData(txtFilter);
		},
		
		_trim:function(txt){
			return txt.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');
		},
		
		_arrayRows:function(arr, txt){
			finArr	=	[];
			if(this._isArray(arr) && !this._isEmpty(txt)){
				for(obj in arr){
					if(typeof arr[obj] != "function" && !this._isEmpty(arr[obj][txt])){
						finArr.push(arr[obj][txt]);
					}
				}
			}
			return finArr;
		},
		
		_arrayLength:function(arr){
			totArr	=	0;
			if(this._isArray(arr)){
				for(obj in arr){
					if(typeof arr[obj] != "function"){
						totArr++;
					}
				}
			}
			return totArr;
		},
		
		_arrayRemove:function(arr, no){
			if(this._isArray(arr) && no != null && no >= 0){
				nArr	=	new Array();
				for(obj in arr){
					if(typeof arr[obj] != "function" && obj != no){
						nArr.push(arr[obj]);
					}
				}
				arr		=	nArr;
			}
			return arr;
		},
		
		_isArray:function(arr){
			return (arr != null && typeof arr == 'object');
		},
		
		_isEmpty:function(txt){
			return (txt == null || txt == "");
		},
		
		_isNull:function(txt){
			return (txt == null);
		},
		
		_inArray:function(arr, txt){
			if(this._isArray(arr) && !this._isNull(txt)){
				for(obj in arr){
					if(typeof arr[obj] != "function" && arr[obj] == txt){
						return true;
					}
				}
			}
			return false;
		},

		_inArrayKeys:function(arr, txt){
			if(this._isArray(arr) && !this._isEmpty(txt)){
				for(obj in arr){
					if(typeof arr[obj] != "function" && obj == txt){
						return true;
					}
				}
			}
			return false;
		},

		destroy: function() {
			$.Widget.prototype.destroy.call( this );
		}
	});
})( jQuery );