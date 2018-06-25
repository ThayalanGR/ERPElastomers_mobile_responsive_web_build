<?php

require_once 'submitFunctions.php';

$dev_grp_email				=	array(DEV_GRP);
$cmpd_grp_email				=	array(CMPD_GRP);
$cpd_grp_email				=	array(CPD_GRP);
$quality_grp_email			=	array(QUALITY_GRP);

$codeArray  =  @getSettingsData("mouldqal");
$codeNo		=   @getRegisterNo($codeArray[0], $codeArray[1]);
$cmpdid		=	$_POST['cmpdid'];
$recqty		=	str2num($_POST['recqty']);
$appqty		=	str2num($_POST['appqty']);
$planId		=	$_POST['planid'];
$mdlrref	=	$_POST['mdlrref'];
$isExternal	=	$_POST['isexternal'];
$inspector	=	$_POST['inspector'];
$inspDate=date('Y-m-d');
			
$usrId		=	$_POST['userId'];

$sql		=   " insert into tbl_moulding_quality(mdlrref, isExternal, inspector, receiptqty, appqty, rejcode, rejval, qualityref, qualitydate, planref, cmpdId, entry_on, entry_by) values ";
$reworkSql	=	"";
$reworkQty	=	0;
$rejTotVal	=	0;

$rejNames=array();

$rejNames=json_decode($_POST['rejname'],true);

if(count($rejNames) > 0){
	foreach($rejNames as $key=>$val){
		
		
		$rejNamesS=json_decode($_POST['rejval'],true);

			

		$rejVal	=	str2num($rejNamesS[$key]);
		$sql	.=	 " ( '$mdlrref', '$isExternal', '$inspector', '$recqty', '$appqty', '$val', '$rejVal', '$codeNo', '$inspDate' , '$planId', '$cmpdid', now(), '$usrId' ) ";

		if($key < count($rejNames)-1){
			
			$sql	.=	" , ";
		}
		if($val == 'REWORK')
		{
			$reworkSql 		=	" insert into tbl_rework(reworkref, planid, qualref, inspdate, cmpdid, quantity, entry_on, entry_by) values ";
			$rwrkCodeArray  =   @getSettingsData("reworkCode");
			$rwrkCodeNo		=   @getRegisterNo($rwrkCodeArray[0], $rwrkCodeArray[1]);
			$reworkQty		=	$rejVal;
			$reworkSql		.=	" ( '$rwrkCodeNo', '$planId', '$codeNo', '$inspDate', '$cmpdid', '$rejVal', now(), '$usrId' ) ";	
		}
		else
		{
			$rejTotVal	+=	$rejVal;
		}
	}
}
else{
	$sql	.=	 " ( '$mdlrref', '$isExternal', '$inspector', '$recqty', '$appqty', '', 0, '$codeNo', '$inspDate' , '$planId', '$cmpdid', now(), '$usrId' ) ";
}
			
$res	=	@getMySQLData($sql,null,null,null);

if($res['status'] == 'success'){
				//update settings
	@getMySQLData("update tbl_settings set auto_inc='".($codeArray[1]+1)."' where name='mouldqal'",null,null,null);
	
				//update mould store
	$sql_planref		=	@getMySQLData("select planref from tbl_mould_store where planref='$planId' and cmpdId = '$cmpdid' and status = 1","arr",null,null,null);
	
	$plan_dtls			=	$sql_planref['data']['0'];
	$exPlanRef			=	$plan_dtls['planref'];

	if($exPlanRef == $planId)
	{
		$res		=	@getMySQLData("update tbl_mould_store set avlQty = avlQty + $appqty where planref='$planId' and cmpdId = '$cmpdid' and status = 1",null,null,null);
	}
	else
	{
		$pSql		=   " insert into tbl_mould_store( planref, cmpdId, avlQty) values ('$planId','$cmpdid','$appqty')";
		$res		=	@getMySQLData($pSql,null,null,null);
	}					
	if($res['status'] == 'success'){
					//create rework table/update settings						
		if($reworkSql != "")
		{
			$rewrkRes = @getMySQLData($reworkSql,null,null,null);
			if($rewrkRes['status'] == 'success')
			{
				@getMySQLData("update tbl_settings set auto_inc='".($rwrkCodeArray[1]+1)."' where name='reworkCode'",null,null,null);
			}
		}						
					//update tbl_deflash_receipt to close if necessary (i.e status = 2)
		$inspQtySQL		=	"";
		if($isExternal == 0)
		{
			$inspQtySQL	=	"select currrec,sum(receiptqty) as receiptqty 
			from (select mdlrref,receiptqty from tbl_moulding_quality where status > 0 and isExternal = 0 and mdlrref ='$mdlrref' group by qualityref) tmq
			inner join tbl_deflash_reciept tdr on tdr.sno = tmq.mdlrref
			where tdr.status > 0 ";
		}
		else
		{
			$inspQtySQL	=	"select recvqty as currrec,sum(receiptqty) as receiptqty 
			from (select mdlrref,receiptqty from tbl_moulding_quality where status > 0 and isExternal = 1 and mdlrref ='$mdlrref' group by qualityref) tmq
			inner join tbl_component_recv tcr on tcr.sno = tmq.mdlrref
			where tcr.status > 0 ";	
		}
		$sql_inspQty	=	@getMySQLData($inspQtySQL,"arr",null,null);			   
		$inspQty_dtls	=	$sql_inspQty['data'][0];
		$exAvlQty		=	str2num($inspQty_dtls['currrec']);
		$exInspQty		=	str2num($inspQty_dtls['receiptqty']);
		if($exInspQty >= $exAvlQty)
		{
			if($isExternal == 0)
				@getMySQLData("update tbl_deflash_reciept set status='2' where sno ='$mdlrref'",null,null,null);
			else
				@getMySQLData("update tbl_component_recv set status='2' where sno ='$mdlrref'",null,null,null);
		}	
					//close tbl_moulding_receive/rework table if all items inspected
		if($isExternal == 0){
			$moulRefSQL			= "select di.mouldref,di.issqty,sum(receiptqty) as receiptqty
			from tbl_deflash_issue di
			inner join tbl_deflash_reciept dr on di.sno = dr.defissref and dr.status > 0
			inner join (select mdlrref,receiptqty from tbl_moulding_quality where status > 0 and isExternal = 0 group by qualityref) mq on mq.mdlrref = dr.sno
			where di.status > 0 and di.sno = ( select defissref  from tbl_deflash_reciept where sno ='$mdlrref' and status > 0)";
			
			$sql_issMouldRef	=	@getMySQLData($moulRefSQL,"arr",null,null);			   
			$issMouldRef_dtls	=	$sql_issMouldRef['data'][0];
			$exissMouldRef		=	$issMouldRef_dtls['mouldref'];
			$exIssQty			=	str2num($issMouldRef_dtls['issqty']);
			$totRecptQty		=	str2num($issMouldRef_dtls['receiptqty']);
			if($totRecptQty > 0.98 * $exIssQty)
			{							
				if(strripos($planId,'-rt') == strlen($planId) - 3 ){
					@getMySQLData("update tbl_rework set status='4' where reworkref = '$exissMouldRef'",null,null,null);
				}
				else{
					@getMySQLData("update tbl_moulding_receive set status='6' where modRecRef  = '$planId'",null,null,null);
				}
			}	
		}
				$sql_comp	=	@getMySQLData("select cmpdname,cmpdrefno from tbl_component where cmpdId = '$cmpdid'","arr");			   
					$comp_dtls	=	$sql_comp['data'][0];
					$inspQty	=	$recqty - $reworkQty;
					if ($rejTotVal > ($inspQty * ($compRejPer/100)))
					{
						$rejPer		=	round((($rejTotVal/$inspQty)*100),2);
						$sql_comp	=	@getMySQLData("select cmpdname,cmpdrefno from tbl_component where cmpdId = '$cmpdid'","arr");			   
						$comp_dtls	=	$sql_comp['data'][0];
						$compName	=	$comp_dtls['cmpdname'];
						$compDesc	=	$comp_dtls['cmpdrefno'];
						$sql_rate	=	@getMySQLData("select poRate from tbl_customer_cmpd_po_rate where cmpdId = '$cmpdid' order by update_on desc limit 1","arr");
						$rate_dtls	=	$sql_rate['data'][0];
						$poRate		=	$rate_dtls['poRate'];
						$rejSum		=	$rejTotVal * $poRate;
						$pstatus 	= 	sendEmail(array_merge($dev_grp_email,$cmpd_grp_email,$cpd_grp_email),$quality_grp_email,"Rejection is ".$rejPer."% (Value Rs:".$rejSum.") for: ".$planId."(".$compName.")","Please note: The Total rejection for the Key : ".$planId." for ".$compName."(".$compDesc.") is ".$rejTotVal." for inspected quantity of  ".$inspQty,"");
						echo "Email Sent ";		
					}else{
						echo "no email sent ";
					}
				
	}else{
		echo $res['status'];
	}
	
}else{
	echo $res['status'];
}

				
if($res['status']	==	"success"){
	echo "Data Submitted";
}else{
	echo "Submission failed, try again";
}


?>