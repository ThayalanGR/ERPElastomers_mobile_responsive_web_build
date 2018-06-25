<?php

require_once '../includes/Constants.php';

function getSettingsData($set){
	if($set){
		$sql	=	"select * from tbl_settings where name='$set'";
		$data	=	getMySQLData($sql,null,null,null);

		if($data['status'] == "success"){
			return array($data['data'][0]['value'], $data['data'][0]['auto_inc']);
		}
		else{
			return $data['status'];
		}
	}
}


function getMySQLData($sql, $type, $startNode, $nodeCase){
	global $db_host, $db_user, $db_pass, $db_database, $db_settings;
	$db_host= DB_HOST;
	$db_user= DB_USER;
	$db_pass= DB_PASSWORD;
	$db_database= DB_NAME;

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

		
			}
			
			return $regName;
		}
		
		function str2num($string){
			//$string		=	'3,345.67';
			$num		=	0;
			$temp		=	split ('[,]',$string);
			$number		=	"";
			foreach($temp as $value){
				$number		.=  $value;
			}
			return (float)$number;
		}


		function sendEmail($to,$cc_address,$subject,$message,$attachment,$send_add = null,$send_pass = null)
		{
		
			$send_add= SENDER_EMAIL;
			$send_pass= SENDER_PASSWORD;
			
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
				$mail->Username 	= 	$send_add;
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


		?>
