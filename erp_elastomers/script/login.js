function loginUser(){
   user		=	$("#username");
   pass		=	$("#password");
   error	=	$("#err_msg");
   url		=	window.location.href;
   
   if(user.val() == null && user.val() == ""){
	   user.focus();
	   displayError(error, "error", "Invalid Username . . .");
	   return false;
   }
   if(pass.val() == null && pass.val() == ""){
	   pass.focus();
	   displayError(error, "error", "Invalid Password . . .");
	   return false;
   }
   
   // Check URL
   if(url != null && url != ""){
	   	if(!isRewrite){
			if(url.indexOf("?") > -1){
				urlSplit	=	url.split("?");
				mURL		=	urlSplit[0];
				urlSplit.splice(0, 1);
			}
			if(typeof urlSplit == "object" && urlSplit[urlSplit.length-1].indexOf("&") > -1)
				urlSplit	=	urlSplit[urlSplit.length-1].split("&");
			if(typeof urlSplit == "object" && urlSplit[0].indexOf("=") > -1)
				urlSplit	=	urlSplit[0].split("=");
	
			url		=	(typeof urlSplit == 'object' && urlSplit[urlSplit.length-1] == "Logout")?mURL:url;
			
			if(url.lastIndexOf("#") > -1)
			url		=	url.substr(0, url.length-1);
		}
		else{
			url		=	window.location.protocol+"//"+window.location.host;
			urlPath	=	window.location.pathname;
			url		+=	(urlPath.toLowerCase().indexOf("logout") < 0)
							?urlPath
							:'';
		}
   }
   
   pData		=	"user="+user.val()+"&pass="+pass.val();
   logindata	=	postback(((isRewrite)?"/":"") + "login_validate.php", pData, "POST", "XML");
   
   if(logindata == "success"){
	   window.location.href	=	url;
   }else{
	   displayError(error, "error", "Invalid User/Login . . .");
   }
}

$(document).ready(function(){
	$("#login").button();
	$("#login").click(loginUser);
});
