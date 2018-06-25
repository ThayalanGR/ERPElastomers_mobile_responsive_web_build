<?php

/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Includes |-----------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/

		require_once "config.php";
		require_once 'XML/Query2XML.php';
		require_once 'MDB2.php';
		require_once 'Numbers/Words.php';
/*	+----------------------------------------------------------------------------------------------------------------------+
	+----------------------------------------------------------------------------------------------------------------------+	*/

/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Variables |----------------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/

		$alphaMonth			=	array('Jan'=>'J', 'Feb'=>'K', 'Mar'=>'L', 'Apr'=>'A', 'May'=>'B', 'Jun'=>'C',
									  'Jul'=>'D', 'Aug'=>'E', 'Sep'=>'F', 'Oct'=>'G', 'Nov'=>'H', 'Dec'=>'I');
		$numMonth			=	array('', 'J', 'K', 'L', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
		$pageDefault		=	ISO_REWRITE_URL.'modules/Common/index.php';
		$pageNotFound		=	ISO_REWRITE_URL.'modules/Common/not_found.php';
		$pageNoPermission	=	ISO_REWRITE_URL.'modules/Common/no_permission.php';

/*	+----------------------------------------------------------------------------------------------------------------------+
	+----------------------------------------------------------------------------------------------------------------------+	*/

/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Functions |----------------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
	
		function setPageHeaders($ext){
			switch($ext){
				case "js":case"run": header("Content-type: application/javascript"); break;
				case "css": header('Content-type: text/css'); break;
				case "action": header('Content-type: text/css'); break;
				case "design": header('Content-type: application/xslt+xml'); break;
			}
		}
		
		function closeConnForAsyncProcess($outMsg)
		{
			set_time_limit(0); 
			ignore_user_abort(true);    
			// buffer all upcoming output - make sure we care about compression: 
			if(!ob_start("ob_gzhandler")) 
				ob_start();         
			echo $outMsg;    
			// get the size of the output 
			$size = ob_get_length();    
			// send headers to tell the browser to close the connection   
			header("Content-Length: $size"); 
			header('Connection: close');    
			// flush all output 
			ob_end_flush(); 
			ob_flush(); 
			flush();    
			// close current session 
			if (session_id()) session_write_close();		
		}
		
		function makecomma($input)
		{
			if(strlen($input)<=2)
			{ return $input; }
			$length=substr($input,0,strlen($input)-2);
			$formatted_input = makecomma($length).",".substr($input,-2);
			return $formatted_input;
		}
	
		function in_array_recursive($needle, $haystack) { 
			$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack)); 
			foreach($it AS $element) { 
				if($element == $needle) { 
					return true; 
				} 
			} 
			return false; 
		}

		function getFormDetails($sno){
			if($sno){
				$sql	=	"select * from tbl_form_number where sno='$sno'";
				$data	=	@getMySQLData($sql);
				
				if($data['status'] == "success"){
					return array($data['data'][0]['formNo'], $data['data'][0]['reportName']);
				}
				else{
					return $data['status'];
				}
			}
		} 	
		
		function getCompanyDetails($detail)
		{
			global $company_address, $company_phone, $company_mobile, $company_website, $company_email, $company_abbrv, $company_cin;
			
			switch($detail){
				case "address":return $company_address; break;
				case "phone": return $company_phone; break;
				case "mobile": return $company_mobile; break;
				case "website": return $company_website; break;
				case "email":return $company_email; break;
				case "abbrv":return $company_abbrv; break;
				case "cin":return $company_cin; break;
			}
		}

		class AsyncCreatePDFAndEmail extends Thread {
			private $ModuleName;
			private $invoice;
			private $toAdd;
			private $ccAdd;
			private $subject;
			private $message;
			private	$isLandscape;
			private $di_user;
			private $di_pass;
			private $di_baseURL;
			private $pdf_generator_path;
			private $pdf_options;
			private $sender_add;
			private $sender_pass;
			private $sender_user;
			
			public function __construct($ModuleName,$invoice,$toAdd,$ccAdd,$subject,$message,$isLandscape = null){
				global $di_user, $di_pass, $di_baseURL, $pdf_generator_path, $pdf_options_async,$sender_add, $sender_pass, $sender_user;
				$this->di_user				=	$di_user;
				$this->di_pass				=	$di_pass;
				$this->di_baseURL			=	$di_baseURL;
				$this->pdf_generator_path	=	$pdf_generator_path;
				$this->pdf_options			=	$pdf_options_async;
				$this->sender_add			=	$sender_add;
				$this->sender_pass			=	$sender_pass;
				$this->sender_user			=	$sender_user;
				$this->ModuleName 			= 	$ModuleName;
				$this->invoice 				= 	$invoice;
				$this->toAdd 				= 	$toAdd;
				$this->ccAdd 				= 	$ccAdd;
				$this->subject 				= 	$subject;
				$this->message 				= 	$message;
				if($isLandscape	==	null)
					$this->isLandscape			=	false;
				else
					$this->isLandscape			=	$isLandscape;
			}

			public function run() {	
				$output		=	createPDFforReport($this->ModuleName,$this->invoice,$this->isLandscape,$this->di_user,$this->di_pass,$this->di_baseURL,$this->pdf_generator_path,$this->pdf_options);
				if ($output == "") 
				{
					$thefile 	= 	sys_get_temp_dir().'\\'.$this->invoice.".pdf";
					sendEmail($this->toAdd,$this->ccAdd,$this->subject,$this->message,$thefile,$this->sender_add,$this->sender_pass,$this->sender_user);
				}
			}
		}		
		
		function createPDFforReport($ModuleName,$invoice,$isLandscape,$rpt_user = null,$rpt_pass = null,$rpt_baseURL = null,$rpt_pdf_gen_path = null,$rpt_pdf_options = null)
		{
			global $di_user, $di_pass, $di_baseURL, $pdf_generator_path, $pdf_options;	
			if($rpt_user == null)
				$rpt_user			=	$di_user;
			if($rpt_pass == null)
				$rpt_pass			=	$di_pass;
			if($rpt_baseURL == null)
				$rpt_baseURL		=	$di_baseURL;
			if($rpt_pdf_gen_path == null)
				$rpt_pdf_gen_path	=	$pdf_generator_path;
			if($rpt_pdf_options == null)
				$rpt_pdf_options	=	$pdf_options;			
			$getUrl	=	$rpt_baseURL.$ModuleName."/page=invoice/invID=".$invoice."/?user=".$rpt_user."&pass=".$rpt_pass;
			exec( $rpt_pdf_gen_path .' '.$rpt_pdf_options.(($isLandscape)?" --orientation Landscape":"").' "'.$getUrl.'" "'. sys_get_temp_dir().'\\'.$invoice.'.pdf"',$dummy,$output);
			if ($output == 0) 
			{
				return "";
			}
			else
			{
				return "PDF Generation Failed!";
			}
		}

		function sendEmail($to,$cc_address,$subject,$message,$attachment,$send_add = null,$send_pass = null,$send_user = null)
		{
			global $sender_user, $sender_add, $sender_pass;
			if($send_user == null)
				$send_user	=	$sender_user;			
			if($send_add == null)
				$send_add	=	$sender_add;
			if($send_pass == null)
				$send_pass	=	$sender_pass;
			require_once( 'PHPMailerAutoload.php');
			$output 	= 	"success";
			$mail 		= 	new PHPMailer;			
			try
			{
				$mail->isSMTP();
				//Enable SMTP debugging
				// 0 = off (for production use)
				// 1 = client messages
				// 2 = client and server messages
				$mail->SMTPDebug 	= 	0;
				//Ask for HTML-friendly debug output
				//$mail->Debugoutput = 'html';
				$mail->SMTPOptions 	= 	array(
					'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				));				
				$mail->Host 		= 	'smtp.gmail.com';
				$mail->Port 		= 	587;
				$mail->SMTPSecure 	= 	'tls';
				$mail->SMTPAuth 	= 	true;
				$mail->Username 	= 	$send_user;
				$mail->Password 	= 	$send_pass;
				$mail->setFrom($send_add);
				if($to == null || $to == '')
					throw new phpmailerException('No "To" address Provided!');
				if(is_array($to))
				{
					for($i=0;$i<count($to);$i++) 
					{
						$mail->addAddress($to[$i], '');
					}	
				}
				else
				{
					$mail->addAddress($to, '');
				}
				if(is_array($cc_address))
				{
					for($i=0;$i<count($cc_address);$i++)
					{ 
						$mail->addCC($cc_address[$i], '');
					}
				}
				else if($cc_address != null && $cc_address != "")
				{
					$mail->addCC($cc_address, '');
				}
				$mail->IsHTML(true);
				$mail->Subject 		= 	$subject;
				$mail->Body 		= 	$message;			
				if($attachment != null && $attachment != "")
				{
					if(is_array($attachment))
					{
						foreach($attachment as $thefile)
						{
							if(!($mail->addAttachment($thefile)))
							{
								throw new phpmailerException('Unable to attach the file: ' . $mail->ErrorInfo);
							}							
						}
						$mail->send();
					}					
					else if($mail->addAttachment($attachment))
					{
						$mail->send();
						unlink($attachment);
					}
					else
					{
						throw new phpmailerException('Unable to attach the file: ' . $mail->ErrorInfo);
					}
				}
				else
				{
					$mail->send();
				}				
			}
			catch (phpmailerException $e) {
				$output		=	$e->errorMessage(); 
			}			 
			catch (Exception $e) {
				$output 	=  	$e->getMessage(); 
			}
			return $output;
		}

		function getCompoundCost($cpd){
			$output = 0.00;
			$sql_comp = @getMySQLData("SELECT SUM((ramParts / sumrem) * ramApprovedRate) AS rate
										FROM (select * 
												from (select cpdId, item_no,ramParts, ramApprovedRate 
														from tbl_compound_rm tcr
															inner join tbl_invoice_grn tig on tig.invRamId = tcr.ramId
															inner join tbl_rawmaterial trm on trm.ramId = tcr.ramId	
														order by grnDate Desc)table1 group by cpdId,item_no)t1 
											INNER JOIN (SELECT cpdId, SUM( ramParts ) AS sumrem FROM (SELECT cpdId, item_no, ramparts FROM tbl_compound_rm  group by cpdId,item_no )table1 group by cpdId )t2 on t1.cpdId = t2.cpdId												
										WHERE t1.cpdid =  '$cpd'" );
						
			if( ($sql_comp['errno'] == 0)&&($sql_comp['count'] > 0) ){
				$output		=	round($sql_comp['data'][0]['rate'],2);
			}
			return $output;
		}		

		function getBlanksGroup($blankWeight){
			global $blanksGroup;
			$lastWeight	=	0;			
			foreach($blanksGroup as $value)
			{
				if($blankWeight >= $lastWeight && $blankWeight < $value)
					return array_search($value,$blanksGroup);
				else
					$lastWeight	= $value;
			}			
			return count($blanksGroup);			
		}		
		
		function getHeaderDesign(){
			
		}
		
		function getFooterDesign(){
			
		}
		
		function getHeaderLogo(){
			$logo_val	=	@getSettingsValue(array('head_show_type', 'company_name', 'head_image_data'));
			switch($logo_val['head_show_type']){
				case "text":
					echo $logo_val['company_name'];
				break;
				case "image":
					echo '<img src="data:image/png;base64,'.$logo_val['head_image_data'].'" width="100" />';
				break;
			}
		}
		
		function getPagePermission($module){
			global $pageDefault, $pageNotFound, $pageNoPermission, $is_sub, $_VAR;
			$out_permission		=	array();
			if(isset($module)){
				if($module != "login"){
					$url	=	$pageDefault;
					if($module != ""){
						$mod_split		=	@preg_split("/[\/]/", $module, -1, PREG_SPLIT_NO_EMPTY);
						
						// Get Menus' and Sub Menu's
						$menuHeadSQL		=	@getMySQLData("select autoId, menu_head, menu_link from tbl_menu_head
																where status>0 order by menu_order asc");
						$menuSubSQL			=	@getMySQLData("select autoId, menu_sub, menu_link, invoice_link, permission from tbl_menu_sub
																where menu_head='".$mod_split[0]."' and status>0 order by menu_order asc");
						$menuCount			=	$menuHeadSQL['count'];
						$menuHead			=	$menuHeadSQL['data'];
						$menuSubCount		=	$menuSubSQL['count'];
						$menuSub			=	$menuSubSQL['data'];						
						// Check for Header Permission
						if($mod_split[0] != ""){
							// Get Masters Permission No.
							$headNo		=	0;
							$headURL	=	'';
							for($mh=0; $mh<$menuCount; $mh++){
								if($menuHead[$mh]['menu_head'] == $mod_split[0]){
									$headNo		=	$menuHead[$mh]['autoId'];
									$headURL	=	$menuHead[$mh]['menu_link'];
									break;
								}
							}							
							if($headNo > 0 && in_array($headNo, $_SESSION['userdetails']['userPermissions'])){
								
								// Check for Sub Page Permission
								if($mod_split[1] != ""){
									$subNo		=	0;
									$subURL		=	'';
									$perURL		=	1;
									for($sm=0; $sm<$menuSubCount; $sm++){
										$menuSubItem	=	@preg_replace("/[ ]/", '', $menuSub[$sm]['menu_sub']);
										if($menuSubItem == $mod_split[1]){
											$subNo		=	$menuSub[$sm]['autoId'];
											$subURL		=	$menuSub[$sm]['menu_link'];
											$invURL		=	$menuSub[$sm]['invoice_link'];
											$perURL		=	$menuSub[$sm]['permission'];
											break;
										}
									}
									
									if($subNo > 0 && (in_array($subNo, $_SESSION['userdetails']['userSubPermissions']) || $perURL == 0)){
										
										switch($is_sub){
											case "invoice":
											case "sub":
											case "inner":
												$subURL		=	$invURL;
											break;
											default:
												$subURL		=	$subURL;
											break;
										}
										if($subURL != ""  && is_file($subURL)){
											$url	=	ISO_REWRITE_URL.$subURL;
										}
										else{
											$url			=	$pageNotFound;
											$show_design	=	true;
										}
									}
									else{
										$url			=	$pageNoPermission;
										$show_design	=	true;
									}
								}
								else{
									// Check if Link Exist
									if($headURL != ""){
										if(is_file($headURL)){
											$url			=	ISO_REWRITE_URL.$headURL;
										}
										else{
											$url 			=	$pageNotFound;
											$show_design	=	true;
										}
									}
								}
								
							}
							else{
								$url			=	$pageNoPermission;
								$show_design	=	true;
							}
						}
					}
					
					$baseURLSplit	=	preg_split("/[\/]/", $url, -1, PREG_SPLIT_NO_EMPTY);
					$baseURL		=	ISO_REWRITE_URL;
					for($bu=0; $bu<count($baseURLSplit)-1; $bu++){
						$baseURL	.=	$baseURLSplit[$bu]."/";
					}
					
					$open_file		=	$url;
					$out_permission	=	array($show_design, $baseURL, $open_file);
				}
				else{
					$out_permission	=	array(true, ISO_REWRITE_URL.'modules/Common/Login/', ISO_REWRITE_URL.'modules/Common/Login/index.php');
				}
			}
			return $out_permission;
		}
	
		function getXML($pSQL,$pHeader=true,$pArray="null"){
			global $db_host, $db_user, $db_pass, $db_database, $db_settings;
			try {
				$q2x = XML_Query2XML::factory(MDB2::factory("mysqli://$db_user:$db_pass@$db_host/$db_database"));
				if(is_array($pArray)){
					$xml = $q2x->getXML($pSQL,$pArray);
				}
				else{
					$xml = $q2x->getFlatXML($pSQL);
				}
				if($pHeader){
					header('Content-Type: text/xml');
					$xml->formatOutput = $pHeader;
				}
				return $xml->saveXML();
			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
		
		
		function number2Word($no){
			$word	=	'';
			if($no > 0){
				$nsplit		=	split('\.', $no);
				if($nsplit[1] == 0)
					$no		=	$nsplit[0];
				$rupee		=	Numbers_Words::toCurrency($no,'en_IN');
				$word		.=	@ucwords(@preg_replace('/[-]/', ' ', $rupee));
			}
			
			return $word;
		}
		
		function str2num($string){
			//$string		=	'3,345.67';
			$num		=	0;
			$temp		=	split('[,]',$string);
			$number		=	"";
			foreach($temp as $value){
				$number		.=  $value;
			}
			return (float)$number;
		}
		
		function ceiling($value, $precision = 0) {
			$offset = 0.5;
			if ($precision !== 0)
				$offset /= pow(10, $precision);
			return round($value + $offset, $precision, PHP_ROUND_HALF_DOWN);
		}		
        
		function getSettingsData($set){
			if($set){
				$sql	=	"select * from tbl_settings where name='$set'";
				$data	=	@getMySQLData($sql);
				
				if($data['status'] == "success"){
					return array($data['data'][0]['value'], $data['data'][0]['auto_inc']);
				}
				else{
					return $data['status'];
				}
			}
		}
		
		function getSettingsValue($data){
			if($data != "" || is_array($data)){
				if(is_array($data)){
					$sett_value	=	array();
					foreach($data as $val){
						array_push($sett_value, "'$val'");
					}
					$sett_value	=	@join(", ", $sett_value);
				}
				else{
					$sett_value	=	"'$data'";
				}
				
				$sql			=	"select * from tbl_settings where name in ($sett_value)";
				$duties			=	@getMySQLData($sql);
				$count			=	$duties['count'];
				$duties			=	$duties['data'];
				$return 		=	array();
				if($count > 0){
					foreach($duties as $value){
						$return[$value['name']]	=	$value['value'];
					}
				}
				return $return;
			}
		}
		
		function updateSettingsData($settname){
			if(is_array($settname) && count($settname) > 0){
				$sql	=	"update tbl_settings set auto_inc=auto_inc+1 where ";
				$tot	=	0;
				foreach($settname as $obj){
					if($tot > 0)
					$sql	.=	" or ";
					$sql	.=	"name='".$obj."'";
					$tot++;
				}
				@getMySQLData($sql);
			}
		}
	
		function updateSettingsDataByVal($settname, $settval=array()){
			if(is_array($settname) && count($settname) > 0 && is_array($settval) && count($settval) > 0){
				for($sn=0; $sn<count($settname); $sn++){
					if(($settname[$sn] != "" || $settname[$sn] > 0) && $settval[$sn] != ""){
						$sql	=	"update tbl_settings set auto_inc='".$settval[$sn]."' where name='".$settname[$sn]."'";
						@getMySQLData($sql);
					}
				}
			}
		}
	
		function check4FinancialYear(){
			$now = time();
			$finyear = strtotime("31-03-".date("Y")." 11:59:59 PM");
			if($now >= $finyear){
				$query	=	@getMySQLData("select * from tbl_settings where is_updatable=1 and byear_update<".date("Y"));
				if($query['count'] > 0){
					@getMySQLData("update tbl_settings set auto_inc=1, byear_update='".date("Y")."', lyear_update=now() where is_updatable=1 and byear_update<".date("Y"));
					return true;
				}
			}
			return false;
		}
		
		
		function getRegisterNo($regType, $no, $cid='', $cno=1){
			global $numMonth;
			
			$finalReg	=	'';
			$YYYY		=	date("Y") - ((date("m") > 3)?0:1);
			$YY			=	date("y") - ((date("m") > 3)?0:1);
			$DD			=	date("d");
			$M			=	$numMonth[date("m")+0];
			
			if($regType != "" && $no > 0){
				$regSplit	=	preg_split("/[|]/", $regType, PREG_NO_ERROR);
				$regName	=	'';
				
				foreach($regSplit as $obj){
					switch($obj){
						case "YYYY":
						case "YY":
						case "DD":
						case "M":
							$regName	.=	$$obj;
						break;
						case "cid":
							$regName	.=	$cid;
						break;
						case "@":
							$regName	.=	chr(64 + $cno);
						break;
						default:
							preg_match("/[\{\}]/", $obj, $noMatches);
							if(count($noMatches) > 0){
								$obj	=	preg_replace("/[\{\}]/", "", $obj);
								$obj	=	$obj + 0;
								
								// Generate Serial No
								if($obj > 0){
									$noZ	=	"";
									for($gr=0; $gr <= ($obj-strlen($no))-1; $gr++){
										$noZ .= "0";
									}
									$obj	=	$noZ.$no;
								}
							}
							$regName	.=	$obj;
						break;
					}
				}
				
				/*$regName	=	$regSplit[0];
				$noZero		=	$regSplit[1];
				
				// Generate Reg Pattern
				$regName	=	preg_replace("/[YYYY]/", date("Y"), $regName);
				$regName	=	preg_replace("/[YY]/", date("y"), $regName);
				
				// Generate Serial No
				if($noZero > 0){
					$noZ	= "";
					for($gr=0; $gr <= ($noZero-strlen($no))-1; $gr++){
						$noZ .= "0";
					}
					$noZero	= $noZ.$no;
				}*/
			}
			
			return $regName;
		}
		
		function getRegisterData($regType){
			$codeArray		=	getSettingsData($regType);
			$codeNo			=	getRegisterNo($codeArray[0], $codeArray[1]);
			return $codeNo;
		}
		
		function getMySQLData($sql, $type, $startNode, $nodeCase){
			global $db_host, $db_user, $db_pass, $db_database, $db_settings;
			$db_output		=	array();
			$SN				=	($startNode)?$startNode:"mysql";
			$xml_output		=	"<$SN>";
			if($sql){
				$db_output['sql']	=	$sql;
				$xml_output			.=	"<sql><![CDATA[".$sql."]]></sql>";
				if($connection = mysqli_connect($db_host, $db_user, $db_pass,$db_database)){
					if($query = mysqli_query($connection,$sql)){
						if($query) 
						{
							if(mysqli_num_rows($query) > 0){
								$db_output['count']		=	mysqli_num_rows($query);
								$db_output['data']		=	array();
								$xml_output				.=	"<count>".$db_output['count']."</count>";
								$xml_output				.=	"<data>";
								
								while($data = mysqli_fetch_array($query, MYSQLI_ASSOC)){
									$keys			=	array_keys($data);
										$ndata			=	array();
										if(count($keys) > 0){
											for($k=0; $k<count($keys); $k++){
												if(preg_match("/\(/", $keys[$k])){
													$data_key	=	split("\(", $keys[$k]);
													$data_key	=	split("\)", $data_key[count($data_key)-1]);
													$data_key	=	$data_key[0];
													
													if(preg_match("/\./", $data_key)){
														$data_key	=	split("\.", $data_key);
														$data_key	=	$data_key[count($data_key)-1];
													}
													
												}
												else{
													$data_key	=	$keys[$k];
												}
												
												switch(strtolower($nodeCase)){
													case "lower":
													case "lowercase":
													$data_key	=	@strtolower($data_key);
													break;
													case "upper":
													case "uppercase":
													$data_key	=	@strtoupper($data_key);
													break;
												}
												
												$ndata[$data_key]	=	$data[$keys[$k]];
											}
											$data		=	$ndata;
											$keys		=	array_keys($data);
										}
										// Array Data
										array_push($db_output['data'], $data);
										// Xml Data
										$xml_output			.=	"<row>";
										for($k=0; $k<count($keys); $k++){
											$xml_output		.=	"<".$keys[$k].">".$data[$keys[$k]]."</".$keys[$k].">";
										}
									$xml_output			.=	"</row>";
								};
								$xml_output				.=	"</data>";
							}
							$db_output['status']	=	"success";
						}
						else
						{
							$db_output['status']	=	"err3";						
						}
					}
					else
					{
						$db_output['status']	=	"err2";
					}
				}
			    else
				{
					$db_output['status']	=	"err1";
				}
					
				$db_output['errno']		=	mysqli_errno($connection);
				$db_output['errtxt']	=	mysqli_error($connection);
				$xml_output				.=	"<status>".$db_output['status']."</status>";
				$xml_output				.=	"<errno>".$db_output['errno']."</errno>";
				$xml_output				.=	"<errtxt>".urlencode($db_output['errtxt'])."</errtxt>";
				$xml_output				.=	"</$SN>";
				return ($type == "xml")?$xml_output:(($type=="both")?array('arr'=>$db_output, 'xml'=>$xml_output):$db_output);
			}
		}		
	
		function getVarFromURL(){
			$var_list		=	array();
			$rewrite_arr	=	@preg_split("/[\/]/", (($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:$_SERVER['REQUEST_URI']), -1, PREG_SPLIT_NO_EMPTY);
			$load_module	=	($is_rewrite)?$rewrite_arr[0].(($rewrite_arr[1])?"/".$rewrite_arr[1]:''):$_VAR['module'];
			if(is_array($rewrite_arr)){
				foreach($rewrite_arr as $obj){
					$objSplit	=	@explode("=", $obj);
					if(count($objSplit) > 1){
						$var_list[$objSplit[0]] = $objSplit[1];
					}
					else{
						array_push($var_list, $objSplit[0]);
					}
				}
			}
			return $var_list;
		}
		
		function removeAllSessionButNot($that){
			$new_session	=	array();
			if(isset($that) && $that != ""){
				foreach($_SESSION as $obj=>$val){
					if($obj == $that){
						$new_session[$obj]	=	$val;
					}
				}
			}
			
			$_SESSION	=	$new_session;
		}

/*	+----------------------------------------------------------------------------------------------------------------------+
	+----------------------------------------------------------------------------------------------------------------------+	*/
	function checkOutputArray($output){
		echo "<pre>";
		print_r($output); 
		echo "</pre>";
	}

?>