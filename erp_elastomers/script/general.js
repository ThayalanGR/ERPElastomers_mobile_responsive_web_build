// Variables
var scriptPath		=	null;
var XSLPath			=	null;
var XMLContent		=	null;
var isSelectable	=	false;

//Timeout settings
var IDLE_TIMEOUT = 10; //minutes
var _idleSecondsCounter = 0;

//Filter Settings
var tableFilters = {	
	sort: true,
	on_keyup: true,
	on_keyup_delay: 1500,
	highlight_keywords: true,
	alternate_rows: true,	
	btn_reset: true,
	btn_reset_text: "Clear",
	popup_filters: true,	
	rows_counter: true,
	rows_counter_text: "Displayed rows: ",
	loader: true,  
	loader_html: '<img style="margin: 0px 5px; vertical-align: middle;" alt="" src="/images/loader_48.gif"><span id="\lblStatus"></span>',  
	loader_css_class: 'myLoader',  
	status_bar: true,  
	status_bar_target_id: 'lblStatus', 
	mark_active_columns: true,
	remember_grid_values: true
};
document.onclick = function() {
    _idleSecondsCounter = 0;
};
document.onmousemove = function() {
    _idleSecondsCounter = 0;
};
document.onkeypress = function() {
    _idleSecondsCounter = 0;
};
window.setInterval(CheckIdleTime, 60000);

function CheckIdleTime() {
	if(location.pathname != "/Logout")
	{
		_idleSecondsCounter++;
		var oPanel = document.getElementById("SecondsUntilExpire");
		if (oPanel)
			oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
		if (_idleSecondsCounter >= IDLE_TIMEOUT ) {
			document.location.href = location.protocol + '//' + location.host+"/Logout";
		}
	}
}

// Functions
function onError(pMsg){
	alert("Oops!!! Somthing went wrong\n\n Error:"+pMsg);	
}

function displayError(errObj, errStat, errTxt, errNos, errShow){
	errObj	=	(typeof errObj == "object")?errObj:"#"+errObj;
	errShow	=	(errShow!=null)?errShow:true;
	errNos	=	(errNos > 0)?errNos:5000;
	$(errObj).slideDown('fast');
	
	$(errObj).css('display', 'block');
	switch(errStat){
		case "highlight":
			$(errObj).removeClass().addClass("ui-state-highlight ui-corner-all");
			$(errObj).html(errTxt);
		break;
		case "error":
			switch(errTxt){
				case "err1":
					errTxt			=	"Failed to Connect Server, Err: " + errTxt;
				break;
				case "err2":
				case "err3":
					errTxt			=	"Server Under Maintenance, Err: " + errTxt;
				break;
			}
			$(errObj).removeClass().addClass("ui-state-error ui-corner-all");
			$(errObj).html("<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>"+errTxt);
		break;
		default:
			$(errObj).removeClass().css("display", "none");
		break;
	}
	
	if(errShow){
		setTimeout(function(){
			$(errObj).slideUp('fast');
		}, errNos);
	}
}

function encodeError(pStatus,pMsg,pData){
	var strPrefix="<DATA><HEADER>";
	var strStatus="<STATUS>"+pStatus+"</STATUS>";
	var strMsg="<MESSAGE>"+pMsg+"</MESSAGE>";
	var strSuffix="</HEADER><CONTENT>"+pData+"</CONTENT></DATA>";
	return(strPrefix+strStatus+strMsg+strSuffix);
}

function processData(pData,pType){
	if(pType=="XML"){
		try{
			statusFlag=0;
			//var statusFlag=parseXMLdoc(pData).getElementsByTagName("STATUS")[0].childNodes[0].nodeValue;
			if(statusFlag==0){
				return(pData);
			}
			else{
				var errorMsg=parseXMLdoc(pData).getElementsByTagName("MESSAGE")[0].childNodes[0].nodeValue;
				return(encodeError(statusFlag,errorMsg,"none"));
			}
		}catch(err){
			return(encodeError(1,err,"none"));
		}
		//alert($(pData).find('STATUS'));	
	}
	else if(pType=="JSON"){
		return(pData);
	}
}


function postback(pURL,pData,pType,pDatatype){
	var obj;
	$.ajax({
		async:false,
		url: pURL,
		type: pType,
		data: pData,
		dataType: pDatatype,
		success: function(msg) {	
			//obj=msg;
			if(pDatatype=="XML"){
				obj=processData(msg,pDatatype);
			}
			else{
				obj=msg;
			}
			//onError(msg)
		},
		error: function(msg){
			obj=encodeError(1,"error",msg);	
		}
	});
	return(obj);
}

function loadXSLDoc(pXSLPath){
	if (window.XMLHttpRequest){
	  xhttp=new XMLHttpRequest();
	}
	else{
	  xhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.open("GET",pXSLPath,false);
	xhttp.send("");
	return xhttp.responseXML;
}

function parseXMLdoc(pXMLStr){
	if (window.DOMParser)
	  {
		  parser=new DOMParser();
		  xmlDoc=parser.parseFromString(pXMLStr,"text/xml");
	  }
	else // Internet Explorer
	  {
		  xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		  xmlDoc.async="false";
		  xmlDoc.loadXML(pXMLStr); 
	  }	
  return(xmlDoc);
}

function xslTranform(pXml,pXsl){
	//return $.xslt({xml:pXml, xslUrl:pXsl});
	xml=parseXMLdoc(pXml);
	xsl=loadXSLDoc(pXsl);
// code for IE
	if (window.ActiveXObject){
  		htmlDoc=xml.transformNode(xsl);
	}
// code for Mozilla, Firefox, Opera, etc.
	else if (document.implementation && document.implementation.createDocument){
	  xsltProcessor=new XSLTProcessor();
	  xsltProcessor.importStylesheet(xsl);
	  htmlDoc = xsltProcessor.transformToFragment(xml,document);
	}
  return(htmlDoc);
}

$(document).ready(function(){
	$(".menu_container ul li").click(function(){
		if($($(this).find("a")).attr("href"))
		document.location	=	$($(this).find("a")).attr("href");
	})
	
   //make all date html5 close on change in chrome
    $('input[type="date"]').change(function () {
        closeDate(this);
    });	
	
	document.onkeypress = stopRKey;

});


function closeDate(dateInput) {
 $(dateInput).get(0).setAttribute('type', 'text');
 $(dateInput).get(0).setAttribute('type', 'date');
}
//Disable Enter Key
function stopRKey(evt) { 
     var evt = (evt) ? evt : ((event) ? event : null); 
	 var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
     if ((evt.keyCode == 13) && (node.type!="textarea"))   {return false;} 
} 
