/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Prototypes |---------------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/

		Array.prototype.inArray		=	function(txt){
			obj		=	this;
			if(typeof obj == 'object' && obj.length > 0 && txt != null && txt != ""){
				for(ia=0; ia<obj.length; ia++){
					if(obj[ia].toLowerCase() == txt.toLowerCase()){
						return true;
					}
				}
			}
			return false;
		};
		
		Array.prototype.inArrayNo	=	function(txt){
			obj		=	this;
			if(typeof obj == 'object' && obj.length > 0 && txt != null && txt != ""){
				for(ia=0; ia<obj.length; ia++){
					if(obj[ia].toLowerCase() == txt.toLowerCase()){
						return ia;
					}
				}
			}
			return -1;
		};
		
		Array.prototype.in_array	=	function(str){
			thisArray	=	this;
			selText		=	(str);
			
			if(typeof thisArray == 'object' && selText != null && selText != ""){
				for(_arr in thisArray){
					if(thisArray[_arr].toLowerCase() == selText.toLowerCase())
						return true;
				}
			}
			
			return false;
		}
		
		Array.prototype.in_array_no	=	function(str){
			thisArray	=	this;
			selText		=	(str);
			
			if(typeof thisArray == 'object' && selText != null && selText != ""){
				for(_arr in thisArray){
					if(thisArray[_arr].toLowerCase() == selText.toLowerCase())
						return _arr;
				}
			}
			
			return -1;
		}
		
		Array.prototype.inArrayKey	=	function(str){
			thisArray	=	this;
			selText		=	(str);
			
			if(typeof thisArray == 'object' && selText != null && selText != ""){
				for(_arr in thisArray){
					if(_arr == selText)
						return true;
				}
			}
			
			return false;
		}

		Array.prototype.Keys	=	function(){
			thisArray	=	this;
			arrKeys		=	[];
			
			if(typeof thisArray == 'object'){
				for(_arr in thisArray){
					if(typeof thisArray[_arr] != "function")
					arrKeys.push(_arr);
				}
			}
			
			return arrKeys;
		}

		Array.prototype.KeyLength	=	function(){
			thisArray	=	this;
			arrKeys		=	[];
			
			if(typeof thisArray == 'object'){
				for(_arr in thisArray){
					if(typeof thisArray[_arr] != "function")
					arrKeys.push(_arr);
				}
			}
			
			return arrKeys.length;
		}

		Array.prototype.toSelectWithEmpty	=	function(name, id, selected, style, attr, func, empty){
			txt		=	'';
			obj		=	this;
			empty	=	(empty == false)?false:true;
			txt		=	'<select '+
							((name)?'name="' + name + '"':'') +
							((id)?'id="' + id + '"':'') +
							((style)?'style="' + style + '"':'') +
							((attr)?attr:'') +
							((func)?func:'') +
						'>' +
						((empty == true)?'<option></option>':'');
			if(typeof obj == 'object' && obj.length > 0){
				for(itm in obj){
					if(typeof obj[itm] != 'object' && typeof obj[itm] != 'function')
					txt		+=	'<option title="' + obj[itm] + '" ' + ((obj[itm] == selected)?'selected':'') + '>' + obj[itm] + '</option>';
				}
			}
			txt		+=	'</select>';
			
			return txt;
		}
		
		Array.prototype.toSelectWithValue	=	function(values, name, id, selected, style, attr, func, empty){
			txt		=	'';
			obj		=	this;
			empty	=	(empty == false)?false:true;
			txt		=	'<select '+
							((name)?'name="' + name + '"':'') +
							((id)?'id="' + id + '"':'') +
							((style)?'style="' + style + '"':'') +
							((attr)?attr:'') +
							((func)?func:'') +
						'>' +
						((empty == true)?'<option></option>':'');
			if(typeof obj == 'object' && obj.length > 0){
				for(itm in obj){
					if(typeof obj[itm] != 'object' && typeof obj[itm] != 'function')
					txt		+=	'<option ' +
									((obj[itm] == selected || values[itm] == selected)?'selected ':'') +
									((values[itm])?'value="'+values[itm]+'" ':'') +
									" title='" + obj[itm] + "' " +
								'>' +
									obj[itm] +
								'</option>';
				}
			}
			txt		+=	'</select>';
			
			return txt;
		}
		
		Array.prototype.toAsc		=	function(){
			thisArray				=	this;
			ascendingFunction		=	function(a, b){
				return a - b;
			}
			if(typeof thisArray == 'object'){
				thisArray.sort(ascendingFunction);
			}
		}
		
		Array.prototype.toDesc		=	function(){
			thisArray				=	this;
			descendingFunction		=	function(a, b){
				return b - a;
			}
			if(typeof thisArray == 'object'){
				thisArray.sort(descendingFunction);
			}
		}
		
		String.prototype.trim		=	function(){
			return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');
		}
		
		String.prototype.toNumber	=	function(){
			num		=	this;
			
			// Check if Comma Exist
			if(num.indexOf(",") > -1)
			num		=	num.split(",").join("");
			
			// Convert to Number
			num		=	!isNaN(num) && isFinite(num) ?Number(num) :0;
			
			return num;
		}
		
		String.prototype.capitalize = function() {
			return this.charAt(0).toUpperCase() + this.substring(1).toLowerCase();
		}
		
		String.prototype.toCurrency	=	function(dec, chk){
			dec		=	(dec)?dec:0;
			amt		=	Number(this);
			return getCurrency(amt, dec, chk);
		}
		
		Number.prototype.toCurrency	=	function(dec, chk){
			dec		=	(dec)?dec:0;
			amt		=	Number(this);
			return getCurrency(amt, dec, chk);
		}
		
/*	+----------------------------------------------------------------------------------------------------------------------+
	+----------------------------------------------------------------------------------------------------------------------+	*/





/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Functions |----------------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
	
		alphaMonth		=	{'Jan':'J', 'Feb':'K', 'Mar':'L', 'Apr':'A', 'May':'B', 'Jun':'C',
								  'Jul':'D', 'Aug':'E', 'Sep':'F', 'Oct':'G', 'Nov':'H', 'Dec':'I'};
		numMonth		=	['J', 'K', 'L', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
		prevInpMse		=	null;
		prevInpKey		=	null;
		window2Fit		=	false;
		ucCustFunc		=	null;
		
		function getUrlParameter(sParam) {
			var sPageURL = decodeURIComponent(window.location.search.substring(1)),
				sURLVariables = sPageURL.split('&'),
				sParameterName,
				i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : sParameterName[1];
				}
			}
		};		

		function openInvoice(attr, curl){
			if(attr != null && typeof attr == "object"){
				var_split	=	(isRewrite)?"/":"&"; 
				inv_url		=	(curl != null && curl != "")
									?curl
									:window.location;
				inv_url		+=	var_split + "page=invoice" + var_split;
				for(obj in attr){
					inv_url	+=	obj + "=" + attr[obj] + var_split;
				}
				window.open(inv_url);
			}
		}
		
		function openMvmt(attr, curl){
				if(attr != null && typeof attr == "object"){
				var_split	=	(isRewrite)?"/":"&"; 
				inv_url		=	(curl != null && curl != "")
									?curl
									:window.location;
				inv_url		+=	var_split + "page=inner" + var_split;
				for(obj in attr){
					inv_url	+=	obj + "=" + attr[obj] + var_split;
				}  
				window.open(inv_url);
			}
		}

		function getSubmitButton(fid, btn_txt){
			if(fid != null){
				$("#"+fid).siblings('.ui-dialog-buttonpane')
				.find(".ui-dialog-buttonset button")
				.each(function(index, element) {
					this_btn_click	=	(btn_txt != null && btn_txt != "")
											?($(element).find("span").html().toLowerCase() == btn_txt.toLowerCase())
											:false;
					
					if(btn_txt != null && btn_txt != ""){
						if(this_btn_click == true)
						$(element).click();
					}
					else{
						if(index == 0)
						$(element).click();
					}
                });
			}
		}
		
		function getCurrency(amt, dec, chk){
			fin = 0;
			neg = 0;
			chk = (chk == "undefined" || chk == null || chk == '')?0:chk;
			if(String(amt) != ""){
				amt = (typeof amt != "number")?(!isNaN(amt) && Number(amt) > 0)?Number(amt):0:amt;
				amt	= amt.toFixed((dec == null || isNaN(dec) || Number(dec) < 0)?0:dec);
				if(amt < 0){
					neg = 1;
					amt = String(amt).split("-").join("");
				}
				amtSpl = String(amt).split("\.");
				if(amtSpl.length > 1){
					fin = ((chk != 1)?addComma(Number(amtSpl[0])):Number(amtSpl[0])) + "." + amtSpl[1];
				}
				else{
					fin = (chk != 1)?addComma(Number(amtSpl))+((dec>0)?".":''):Number(amtSpl)+".";
					if(dec > 0){
						for(d=0; d<dec; d++){
							fin += "0";
						}
					}
				}
				fin = (neg == 1)?"-"+fin:fin;
				return fin;
			}
		}
		
		function addComma(number) {
			number = '' + number;
			if (number.length > 3){
				splNum = number.split(".");
				number = (splNum[0] == "" || splNum[0] == "undefined")?number:splNum[0];
				var mod = number.length % 3;
				var output = (mod > 0 ? (number.substring(0,mod)) : '');
				for (ici=0 ; ici < Math.floor(number.length / 3); ici++) {
					if ((mod == 0) && (ici == 0))
						output += number.substring(mod+ 3 * ici, mod + 3 * ici + 3);
					else
						output+= ',' + number.substring(mod + 3 * ici, mod + 3 * ici + 3);
				}
				output = (splNum[1] == "" || !splNum[1])?output:output+"."+splNum[1];
				return (output);
			}
			else return number;
		}

		function getRegisterNo(regType, no, cid, cno){
			date		=	new Date();
			cid			=	(cid)?cid:'';
			cno			=	(cno)?cno:1;
			finalReg	=	'';
			YYYY		=	Number(date.getFullYear()) - ((date.getMonth() > 2)?0:1);
			YY			=	Number(String(date.getFullYear()).substr(2, 2)) - ((date.getMonth() > 2)?0:1);
			DD			=	date.getDate();
			M			=	numMonth[date.getMonth()];
			
			if(regType != "" && no > 0){
				regSplit	=	regType.split("|");
				regName		=	'';
				
				for(obj in regSplit){
					obj	=	regSplit[obj];
					if(typeof obj == "string"){
						switch(obj){
							case "YYYY":
								regName	+=	YYYY;
							break;
							case "YY":
								regName	+=	YY;
							break;
							case "DD":
								regName	+=	DD;
							break;
							case "M":
								regName	+=	M;
							break;
							case "cid":
								regName	+=	cid;
							break;
							case "@":
								regName	+=	String.fromCharCode(64 + cno);
							break;
							default:
								noMatches	=	obj.split("{");
								if(noMatches.length > 1){
									obj	=	obj.split("{").join("");
									obj	=	obj.split("}").join("");
									obj	=	obj.toNumber() + 0;
									
									// Generate Serial No
									if(obj > 0){
										noZ	=	"";
										for(gr=0; gr <= (obj-String(no).length)-1; gr++){
											noZ += "0";
										}
										obj	=	noZ + no;
									}
								}
								regName	+=	obj;
							break;
						}
					}
				}
			}
			
			return regName;
		}
		
		function FieldHiddenValue(o, t, v){
			obj		=	$((typeof o == "object")?o:'#'+o);
			if(	obj != null &&
				t != null && t != "" &&
				v != null && v != ""){
				switch(t){
					case 'in':
						if(obj.val() == v){
							obj.val('');
						}
						obj.removeClass("invisible_text").addClass("normal_text");
					break;
					case 'out':
						if(obj.val() == ''){
							obj.val(v);
							obj.removeClass("normal_text").addClass("invisible_text");
						}
						//obj.chk		=	(obj.value == '')?0:1;
						obj.attr("chk", (obj.val() == '')?0:1);
					break;
				}
			}
		}
		
		function emailCheck(eml){
			var re 		= 	/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			var tempArr = 	new Array();
			tempArr 	= 	eml.split(",");
			retval		=	true;
			var a		=	0;
			do {
				retval = re.test(tempArr[a]);
				a++;
			} while ( retval == true && a < tempArr.length)			
			return retval;
		}
		
		function numbersOnly(evt){
			kc		=	evt.keyCode;
			if((kc < 37 || kc > 40) && (kc < 95 || kc > 106) && (kc < 47 || kc > 58) &&
				kc != 8  && kc != 9 && kc != 13 && kc != 46 && kc != 110 && kc != 190){
				evt.preventDefault();
			}
		}
		
		function listPageData(obj, data, design){
			dCont	=	$(data); 
			wError	=	$(".window_error");
			wCont	=	$(".content_body");
			dStat	=	dCont.find("root:first status:last").html();
			dList	=	dCont.find("root data row");
			dCount	=	Number(dCont.find("root count").html());
			dTot	=	(isNaN(dCount))?dList.length:dCount;
			errMsg	=	'';
			objTxt	=	'';
			
			if(dStat != 1 && dStat != "success"){
				errMsg	=	'<div class="error_txt"><span>Error Fetching Data . . . Err No: ' + dStat + '</span></div>'
			}
			else if(dTot <= 0){
				errMsg	=	'<div class="warning_txt"><span>No Data Available . . .</span></div>';
			}
			else{
				errMsg	=	dList.length + ' Rows Found . . .';
				objTxt	=	xslTranform(data, design);
			}
			wError.html(errMsg);
			obj.html(objTxt);
			wCont.css("display", ((dStat != 1 && dStat != "success")?"none":"block"));
			wError.css("display", ((dStat == 1 || dTot > 0)?"none":"block"));
		}
		
		function updateHeader(){
			$("#content_head table, #content_foot table")
				.css(
					"width",
					(($("#window_list").attr("id") != null && $("#window_list").hasScrollBar() == true)
						?"98.8%"
						:"100%")
				);
		}
		
		function updateContent(){
			if($("#window_list").html() != null){
				winHeight	=	$(window).height() + 15;
				docHeight	=	$(document).height();
				bodyHgt		=	$(document.body).height();
				bodyHeight	=	bodyHgt;
				winPos		=	$("#window_list").position();
				objHeight	=	$("#window_list").height();
				diffHeight	=	winHeight - winPos.top;
				diffOfHgt	=	diffHeight - objHeight;
				bodyHeight	=	bodyHeight + diffOfHgt;
				
				if(bodyHeight > winHeight)
				diffHeight	=	diffHeight - (bodyHeight - winHeight);
				$("#window_list").css("height", ((window2Fit)?diffHeight + "px":'auto'));
				$("#window_list").css("max-height", diffHeight + "px");
				
				if(typeof ucCustFunc != null && typeof ucCustFunc == "function")
					ucCustFunc();
			}
		}


		function CheckBox(id, func){
			chkId	=	$("#" + id);
			if(chkId){
				chkVal	=	chkId.val();
				chkOP	=	(chkId.attr('checked'))
								?false
								:true;
				chkId.attr('checked', chkOP);
				
				if(typeof func == "function")
					func();
				
				return chkOP;
			}
			return false;
		}

		function disableSelection(target){
			$(target).select(function(e) {
				inpField	=	e.srcElement.type;
				if(inpField != "textarea" && inpField != "text" && inpField != "password" && inpField != "select-one"){
					return false;
				}
			});
			$(target).mousedown(function(e) {
				inpField	=	e.srcElement.type;
				if(inpField != "textarea" && inpField != "text" && inpField != "password" && inpField != "select-one" &&
					prevInpMse != "textarea" && prevInpMse != "text" && prevInpMse != "password" && prevInpMse != "select-one")
					return false;
				else
					prevInpMse	=	inpField;
			});
			/*$(document).keydown(function(e) {
				inpField	=	e.srcElement.type;
				if(inpField != "textarea" && inpField != "text" && inpField != "password" && inpField != "select-one" &&
					prevInpKey != "textarea" && prevInpKey != "text" && prevInpKey != "password" && prevInpKey != "select-one")
					return false;
				else
					prevInpKey	=	inpField;
            });*/
			$(target).css("MozUserSelect", "none");
			$(target).css("cursor", "default");
		}
		
/*	+----------------------------------------------------------------------------------------------------------------------+
	+----------------------------------------------------------------------------------------------------------------------+	*/

