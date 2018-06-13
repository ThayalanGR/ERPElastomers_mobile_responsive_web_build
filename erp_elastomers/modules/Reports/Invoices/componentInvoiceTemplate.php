<?php
	global	$cmpdMonthCode,$eduCessAbolishCutOff,$gstChangeCutoff,$qr_genrate_url,$cpdMonthCode;
	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$invtype			=	(ISO_IS_REWRITE)?trim($_VAR['mod']):trim($_GET['mod']);
	$getCusEmail		=	(ISO_IS_REWRITE)?trim($_VAR['cusEmail']):trim($_GET['cusEmail']);
	
	if($getCusEmail)
	{
		$output = "";
		if($invtype == 'testcert')
		{
 			$sql 			= 	"select batId, sum(hardVal) as hardVal, sum(spGravityVal) as spGravityVal 
									from (select  invPlanRef as batId, if(cpdQanParam ='".$cpd_std_test_refnos[0]."',cpdQanValue,0) as hardVal, if(cpdQanParam ='".$cpd_std_test_refnos[1]."',cpdQanValue,0) as spGravityVal
											from tbl_invoice_sales_items t1 
												left outer join (select * from tbl_compound_qan where (cpdQanParam = '".$cpd_std_test_refnos[0]."' or cpdQanParam = '".$cpd_std_test_refnos[1]."')) t2 on t1.invPlanRef = t2.batId 
											where invId='".$invoice_id."') tab1 
									group by batId order by batId";
			$outSql 		=	@getMySQLData($sql);
			$testDetails 	=   $outSql['data'];
			// Get Records from the table
			foreach($testDetails as $keys=>$values) {
				foreach($values as $key=>$value)  {
					if($key != "batId" && $value == 0)
					{
						echo 'Sending email failed as Hardness/Specific Gravity not completed for batch Id :'.$values['batId'].' Click to <a href="javascript:window.open('."''".",'_self').close();".'">close</a>';
						exit();
					}
					$output 	.=	'"'.$value.'",';
				}
				$output 	.=	"\n";
			}			
			$thefilecsv 	= 	$_SERVER['DOCUMENT_ROOT']."/export/".$invoice_id."-tc.csv";
			file_put_contents($thefilecsv, $output);			
			$filesArr		=	glob($_SERVER['DOCUMENT_ROOT']."/export/".$invoice_id."-tc.csv");
			$pstatus 		= 	createPDFforReport("Sales/DCRegister","Inv~".$invoice_id);
			$tempPath		=	sys_get_temp_dir();
			if($pstatus == "")			
			{
				rename($tempPath.'/Inv~'.$invoice_id.'.pdf', $tempPath.'/CompoundApprovalReport'.$invoice_id.'.pdf');
				$thefilepdf = $tempPath.'/CompoundApprovalReport'.$invoice_id.'.pdf';	
				array_push($filesArr,$thefilepdf);	
			}
			
			$output = sendEmail(explode(",",$getCusEmail),"","Compound Test Certificate: ".$invoice_id,"Dear Sir/Madam, please find the Compound Test Certificate for upload/filing attached to this email<br\> Thank you <br\>".$_SESSION['app']['comp_name'],$filesArr);
			if($thefilecsv != null && $thefilecsv != "")
			{
				unlink($thefilecsv);
			}
			if($thefilepdf != null && $thefilepdf != "")
			{
				unlink($thefilepdf);
			}					
		}
		else 
		{
			$loc_sql		=	@getMySQLData("select value from tbl_settings where name='mixLocCode'");
			$locCode		=	$loc_sql['data'][0]['value'];			
			$sql			=	"select concat(tis.invId,'".$locCode."') as invId, invdate,  invName,invPlanRef ,batFinalDate as batdate,invQty 
										from tbl_invoice_sales_items tisi 
											inner join tbl_invoice_sales tis on tisi.invId = tis.invId
											inner join tbl_mixing tm on tm.batId = tisi.invPlanRef
								where tis.invId='".$invoice_id."'";
			$docType		=	"Compound";
			if ($invtype == 'cmpd')
			{
				$sql		=	"select concat(tis.invId,'".$locCode."') as invId, invDate, invName,invPlanRef,FORMAT(invQty,0) as invQty  
									from tbl_invoice_sales_items tis 
										inner join tbl_invoice_sales tis on tisi.invId=tis.invId 
								where tis.invId='".$invoice_id."'";
				$docType	=	"Component";
			}
			$outSql 		=	@getMySQLData($sql);
	        $cpdDetails    	=   $outSql['data'];
			// Get Records from the table
			foreach($cpdDetails as $keys=>$values) {
				foreach($values as $key=>$value) {		
					if($key == 'invPlanRef' && $invtype == 'cpd')
					{
						$mixDate		= 	$values['batdate'];
						$partBatId		=	"";
						if($mixDate != null && $mixDate != '0000-00-00' && $mixDate != '')
						{
							list($y, $m, $d) 	= 	explode('-', $mixDate);
							$partBatId			=	"/".$d . $cpdMonthCode[$m+0];	
						}
						$batId			=	$value;
						$output 		.=	'"'.$batId.$partBatId.'",';
					}
					else
					{
						$output 		.=	'"'.$value.'",';
					}			
				}
				$output .="\n";
			}
			
			$thefile 	= 	$_SERVER['DOCUMENT_ROOT']."/export/".$invoice_id.".csv";
			file_put_contents($thefile, $output);			
			$output = sendEmail(explode(",",$getCusEmail),"",$docType." Invoice: ".$invoice_id,"please find the ".$docType." Invoice for upload",$thefile);
		}
		if($output == 'success')
			echo '<script>javascript:window.open("","_self").close();</script>';
		else
			echo 'Sending email failed due to: ' .$output . ' Click to <a href="javascript:window.open('."''".",'_self').close();".'">close</a>';
		exit;	
	}
	
	$invIDs				=	[];
	$dispinvid			=	"";
	if (strpos($invoice_id,',') !== false) {
		$tempInvIDs		=	explode(",", $invoice_id);
		$dispinvid		=	$invoice_id;
		$invIDs			=	array_values(array_unique($tempInvIDs));
	}
	else
	{
		$invIDs			=	array($invoice_id);	
		$invoice_id		=	explode("-",$invoice_id);
		$dispinvid		=	$invoice_id[2];
	}
	$formArray		=	@getFormDetails(38);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $dispinvid ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>	
	
<?php 
	for($invCount=0;$invCount<count($invIDs);$invCount++)
	{
		$invoice_id			=	$invIDs[$invCount];
		$sql_bill			=	"select * from tbl_invoice_sales tic where tic.invId='".$invoice_id."'";
		$out_bill			=	@getMySQLData($sql_bill);
		$data				=	$out_bill['data'][0];
		$addr				=	@preg_split("/[|]/", $data['invConsignee'], -1, PREG_SPLIT_NO_EMPTY);
		$consignee			=	$addr[0]."<br>".$addr[1];
		
		$sql_custin			=	"select * from tbl_customer where cusId='".trim($data['invCusId'])."'";
		$out_custin			=	@getMySQLData($sql_custin);	
		$custin				=	$out_custin['data'][0];
		$invDate			=	$data['invDate'];
		$multiItemInvoice	=	$data['invMultiItems'];
		
		$sqlvar				=	"truncate(invQty,0),')'";		
		if($invtype == "mix" || $invtype == "scrap" || $invtype == "ram")
			$sqlvar			=	"invQty,'Kgs)'";
		
		
		$sql_particulars	=	"select invName,invDesc,invCode,invPORef,invPODate,invTariff,group_concat(invPlanRef separator ',') as keyIds, group_concat(concat(invPlanRef,'(',".$sqlvar.") separator ', ') as keyDetails,invRate,sum(invAmt) as totAmt,sum(invQty) as totQty,invDespId from tbl_invoice_sales_items tici where tici.invId='".$invoice_id."' group by invCode order by invPlanRef";
		$out_particulars	=	@getMySQLData($sql_particulars);		
		$particulars		=	$out_particulars['data'];
		$numPackets			=	0;

?>
		<p align="right">Form No:<?php print $formArray[0]; ?></p>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="2" align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="6" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td colspan="1" width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
					<?php echo '<img src="'.$qr_genrate_url.'?id=inv~'.$invoice_id.'" />'; ?>
                </td>
            </tr>			
			<?php 
			if($invDate > $gstChangeCutoff)
			{?>
            <tr>
            	<td colspan="9" class="content_center content_bold uppercase">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
            <tr height="25px">
            	<td colspan="5" rowspan="5" valign="top" style="padding:0px;">
					<div style="padding-bottom:5px;">Name &amp; Address of Consignee:<br /></div>
					<div class="content_bold" style="font-size:14px;">
						<?php print strtoupper($consignee); ?>
					</div>
				</td>
				<td  colspan="2" style="border-right:0px;">
					Invoice No.
				</td>
				<td  colspan="2" class="content_bold" id="invoice_reference" style="font-size:14px;">
					: <?php print $data['invId']; ?> 
				</td>										
			</tr>
			<tr height="25px">
				<td  colspan="2" style="border-right:0px;">
					Invoice Date
				</td>
				<td colspan="3" class="content_bold" id="invoice date" style="font-size:14px;" >
					: <?php print date("d-m-Y", strtotime($invDate)); ?>
				</td>
			</tr>
			<tr height="25px">
				<td colspan="2" style="border-right:0px;">
					Vendor Code
				</td>
				<td colspan="2" class="content_bold">
					: <?php print $data['invCustRefNo']; ?>
				</td>
			</tr>
			<tr height="25px">
				<td colspan="2"  style="border-right:0px;">
					P.O. Reference / Date
				</td>
				<td colspan="3" class="content_bold">
					: <?php print (($particulars[0]['invPORef'])?$particulars[0]['invPORef']:'&nbsp;'). (($particulars[0]['invPODate'] != "" && $particulars[0]['invPODate'] != "0000-00-00" && $particulars[0]['invPODate'] != "1970-01-01")?"/".date("d-m-Y", strtotime($particulars[0]['invPODate'])):'&nbsp;'); ?>
				</td>
			</tr>
			<tr height="25px">
				<td colspan="2"  style="border-right:0px;">
					Payment Terms
				</td>
				<td colspan="2" class="content_bold">
					 : <?php print $data['invPayTerms']; ?>&nbsp;
				</td>
			</tr>			
			 <tr height="25px">
				<td colspan="2" style="border-right:0px;">
					Buyer GSTIN
				</td>
				<td colspan="3" class="content_bold" style="font-size:14px;">
					: <?php print $data['invBGSTN']; ?>
				</td>
				<td colspan="2"  style="border-right:0px;">
					Our GSTIN
				</td>
				<td colspan="2" class="content_bold" style="font-size:14px;">
					: <?php print $data['invGSTN']; ?> 
				</td>													
			</tr>
			<tr height="25px">
				<td colspan="2" style="border-right:0px;">
					Buyer PAN No.
				</td>
				<td colspan="3"  class="content_bold">
					 : <?php print $data['invBPAN']; ?>
				</td>
				<td colspan="2" style="border-right:0px;">
					Our PAN No. 
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print $data['invPAN']; ?>&nbsp;
				</td>
			</tr>
			<tr height="25px">
				<td colspan="2" style="border-right:0px;">
					Place of Supply
				</td>
				<td colspan="3"  class="content_bold">
					 : <?php print strtoupper($custin['cusPlace']); ?>&nbsp;
				</td>
				<td colspan="2" style="border-right:0px;">
					State of Supply 
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print strtoupper($data['invSupplyPlace']); ?>&nbsp;
				</td>
			</tr>	
            <tr height="50px" style="font-size:14px;">
				<?php if ($multiItemInvoice > 0) {?>
               	<td class="content_center" width="5%" >
                	No.
                </td>				
               	<td class="content_center" width="10%">
                	Part No.
                </td>				
				<?php } else { ?>
               	<td class="content_center" width="15%" colspan="2">
                	<?php print ($invtype == 'mix')?'Service Name':'Part No.'; ?>
                </td>
				<?php } ?>
            	<td class="content_center" colspan="3">
                	Description of <?php print ($invtype == 'mix')?'Service':'Goods'; ?> <br/> (U/S 2 of CGST Act 2017)
                </td>
				<td class="content_center" width="10%">
					<?php print ($invtype == 'mix')?'SAC':'HSN'; ?> Code 
				</td>
               	<td class="content_center" width="12%">
                	Quantity <?php print ($invtype != 'cmpd' && $invtype != 'tool')?'(Kgs)':'(Nos.)'; ?>
                </td>
            	<td class="content_center" width="15%">
                	Rate
                </td>
            	<td class="content_center" width="10%">
                	Value (Rs.)
                </td>
            </tr>
            <?php
				$tqty				=	0;
				if ($multiItemInvoice > 0) {
				for($invRow = 0; $invRow < count($particulars);$invRow++)
				{
					$invQty	=	$particulars[$invRow]['totQty'];
					$tqty	+=	$invQty;
			?>
			<tr height="30px" style="font-size:12px;">
				<td class="content_bold content_center">
					<?php print ($invRow + 1); ?>
				</td>			
				<td class="content_bold content_center">
					<?php print $particulars[$invRow]['invName']; ?>
				</td>
				<td class="content_bold content_center" colspan="3">
					<?php 						
						print $particulars[$invRow]['invDesc'];
					?>
				</td>
				<td class="content_bold content_center" >
                	<?php print ($particulars[$invRow]['invTariff'])?$particulars[$invRow]['invTariff']:'&nbsp;'; ?>
                </td>					
				<td class="content_bold content_right">
					<?php print ($invtype != 'cmpd' && $invtype != 'tool')?@number_format($invQty, 3):@number_format($invQty, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print ($particulars[$invRow]['invRate'])?@number_format($particulars[$invRow]['invRate'], 2):'&nbsp;'; ?>
				</td>
				<td class="content_bold content_right">
					<?php print ($particulars[$invRow]['totAmt'])?@number_format($particulars[$invRow]['totAmt'], 2):'&nbsp;'; ?>
				</td>
			</tr>			
			<?php } ?>
				<tr height="30px" style="font-size:14px;">
					<td class="content_bold content_center" colspan="6">
						Total
					</td>			
					<td class="content_bold content_right">
						<?php print ($invtype != 'cmpd' && $invtype != 'tool')?@number_format($tqty, 3):@number_format($tqty, 0); ?>
					</td>
					<td class="content_bold content_right">
						&nbsp;
					</td>
					<td class="content_bold content_right">
						<?php print ($data['invTotalAmt'])?@number_format($data['invTotalAmt'], 2):'&nbsp;'; ?>
					</td>
				</tr>			
			<?php 
			} 
			else 
			{ 
				$tqty	=	$particulars[0]['totQty'];
			?>
			<tr height="100px" style="font-size:16px;">
				<td class="content_bold content_center" colspan="2" >
					<?php print $particulars[0]['invName']; ?>
				</td>
				<td class="content_bold content_center" colspan="3">
					<?php 						
						print $particulars[0]['invDesc'];
					?>
				</td>
				<td class="content_bold content_center" >
                	<?php print ($particulars[0]['invTariff'])?$particulars[0]['invTariff']:'&nbsp;'; ?>
                </td>					
				<td class="content_bold content_right">
					<?php print ($invtype != 'cmpd' && $invtype != 'tool')?@number_format($tqty, 3):@number_format($tqty, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print ($particulars[0]['invRate'])?@number_format($particulars[0]['invRate'], 2):'&nbsp;'; ?>
				</td>
				<td class="content_bold content_right" style="font-size:14px;">
					<?php print ($particulars[0]['totAmt'])?@number_format($particulars[0]['totAmt'], 2):'&nbsp;'; ?>
				</td>
			</tr>
			<?php 
			} 
			if (! ($data['invIGST'] > 0))
			{
			?>	
            <tr height="50px">
            	<td colspan="6" valign="top" >
                	<div style="padding-bottom:3px;">
                		CGST Amount in Words:
                    </div>
					<div style="font-size:14px">
						<?php echo number2Word($data['invCGSTAmt']); ?> only
					</div>
                </td>			
                <td style="font-size:14px">
                	CGST
                </td>
                <td class="content_right" style="font-size:14px">
                    <?php print @number_format($data['invCGST'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;" >
                	<?php print @number_format($data['invCGSTAmt'], 2); ?>
                </td>
            </tr>
			<?php 
			}
			?>
			<tr height="50px">
				<td colspan="6" valign="top" >
					<div style="padding-bottom:3px;">
						<?php print ($data['invIGST'] > 0)?'IGST':'SGST'; ?> Amount in Words:
					</div>
					<div style="font-size:14px">
						<?php echo number2Word(($data['invIGST'] > 0)?$data['invIGSTAmt']:$data['invSGSTAmt']); ?> only
					</div>
				</td>
                <td style="font-size:14px">
                	<?php print ($data['invIGST'] > 0)?'IGST':'SGST'; ?>
                </td>
                <td class="content_right" style="font-size:14px">
                	<?php print ($data['invIGST'] > 0)?@number_format($data['invIGST'], 2):@number_format($data['invSGST'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print ($data['invIGST'] > 0)?@number_format($data['invIGSTAmt'], 2):@number_format($data['invSGSTAmt'], 2); ?>
                </td>
            </tr>			
			<tr height="50px">
            	<td colspan="6" valign="top">
                	<div style="padding-bottom:3px;">
                		Total Amount in Words:
                    </div>
					<div style="font-size:14px;">
						<?php echo number2Word($data['invGrandTotal']); ?> only
					</div>
                </td>				
                <td colspan="2" style="font-size:14px" >
                	Grand Total
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format($data['invGrandTotal'], 2); ?>
                </td>
            </tr>
			<?php 
			if ($invtype == 'mix' ){
			?>
			<tr height="15px">
            	<td class="content_center" colspan="9">
                	Batch Summary
                </td>	
            </tr>
			<tr height="60px">
            	<td  colspan="9">
                	<?php 
						$keyDtls		=	$particulars[0]['keyIds'];
						$keyArr			=	split(",",$keyDtls);
						$keyIds			=	"";
						foreach($keyArr as $key => $value)
							$keyIds	.=	(($keyIds != "")?"','":"").$value ;						
						$cpdData			=	@getMySQLData("select concat(dcName, ':',sum(dcQty),'Kgs/', count(*), 'batch(es)') as dcDetails, count(*) as batches
																	from tbl_invoice_dc tic
																		inner join tbl_invoice_dc_items tici on tic.dcId = tici.dcId 
																	where tici.dcId in ('".$keyIds."') and tic.status > 0
																group by dcName order by dcName");					
						$dispString 	= 	"";
						$totBatches 	=	0;
						foreach($cpdData['data'] as $key => $value)
						{
							$dispString	.=	(($dispString != "")?", ":"").$value['dcDetails'] ;
							$totBatches	+=	$value['batches'];
						}
						echo $dispString.", Total: ".$tqty."Kgs/".$totBatches."batch(es)";
					?>
                </td>	
            </tr>					
			<?php }			
			else {			
			?>
			<tr height="25px">
            	<td class="content_center" colspan="5">
                	Issue of Invoice
                </td>	
            	<td class="content_center" colspan="4">
                	Removal of Goods
                </td>				
            </tr>
            <tr height="25px">
            	<td class="content_center" colspan="3">
                	Date
                </td>	
            	<td class="content_center" colspan="2">
                	Time
                </td>
            	<td class="content_center" colspan="2">
                	Date
                </td>	
            	<td class="content_center" colspan="2">
                	Time
                </td>		
            </tr>
            <tr height="25px">
            	<td class="content_center" colspan="3">
                	<?php print date("d-m-Y", strtotime($data['entry_on'])); ?>
                </td>	
            	<td class="content_center" colspan="2">
                	<?php print date("H:m:s", strtotime($data['entry_on'])); ?>
                </td>
            	<td class="content_center" colspan="2">
                	<?php print date("d-m-Y", strtotime($data['entry_on'])); ?>
                </td>	
            	<td class="content_center" colspan="2">
                	&nbsp;
                </td>					
            </tr>
			
			<?php } if($multiItemInvoice == 0) { ?>
			<tr height="100px">
                <td colspan="9" valign="top">
					<div style="padding-bottom:3px;">
						Remarks:
					</div>
					<?php print $data['invRemarks']; ?>
                </td>
            </tr>						
			<?php 
				}
			} 				
			else
			{ 
			//------------------------------------------------------------------------------ Old Excise Duty Invoice-----------------------------------------------
			?>
            <tr>
            	<td colspan="9" class="content_center content_bold uppercase">
					TAX INVOICE CUM DELIVERY CHALLAN [UNDER RULE 11 OF CEX(NO.2) RULES, 2002]                	
                </td>
            </tr>
            <tr>
            	<td colspan="9" valign="top" style="padding:0px;">
                    <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border:0px;">
                        <tr>
                            <td valign="top" height="75px" width="50%" style="padding:0px;border-bottom:0px;">
                                <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border:0px;">
                                    <tr>
                                        <td valign="top" height="120px" style="border-right:0px;">
                                            <div style="padding-bottom:5px;"><b>Name &amp; Address of Consignee:</b><br /></div>
                                            <div class="content_bold" style="font-size:14px;">
												<?php print strtoupper($consignee); ?>
											</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:0px;border-right:0px;border-bottom:0px;" valign="top">
                                            <table border=0 cellspacing=0 cellpadding=5 style="width:100%;border:0px;">
                                                <tr>
                                                    <td colspan="5" class="content_bold content_center" style="border-right:0px;width:45%;">
                                                        Consignee's Details
                                                    </td>
                                                </tr>											
                                                <tr>
                                                    <td colspan="2" class="content_bold" style="border-right:0px;width:45%;">
                                                        TIN No. :
                                                    </td>
                                                    <td colspan="3" style="border-right:0px;">
                                                         : <?php print $data['invBGSTN']; ?>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="content_bold" style="border-right:0px;">
                                                        CST No. / Date :
                                                    </td>
                                                    <td colspan="3" style="border-right:0px;">
                                                         : <?php print $data['invBPAN']; ?>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="content_bold" style="border-right:0px;border-bottom:0px;">
                                                        ECC No. :
                                                    </td>
                                                    <td colspan="3" style="border-right:0px;border-bottom:0px;">
                                                         : <?php print $data['invSupplyPlace']; ?>&nbsp;
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border-right:0px;padding:0px;border-bottom:0px;">
                                <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                                    <tr>
                                        <td  class="content_bold" style="border-right:0px;">
                                            Invoice No.
                                        </td>
                                        <td  class="content_bold" id="invoice_reference" style="border-right:0px;font-size:14px;">
                                            : <?php print $data['invId']; ?>
                                        </td>
									</tr>
									<tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Invoice Date
                                        </td>
                                        <td colspan="3" class="content_bold" id="invoice date" style="border-right:0px;font-size:14px;" >
                                            : <?php print date("d-m-Y", strtotime($invDate)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="content_bold" style="border-right:0px;">
                                            Vendor Code
                                        </td>
                                        <td colspan="3" style="border-right:0px;">
                                            : <?php print $data['invCustRefNo']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="content_bold" style="border-right:0px;">
                                            P.O. Reference / Date
                                        </td>
                                        <td id="freight" style="border-right:0px;">
											: <?php print ($particulars[0]['invPORef'])?$particulars[0]['invPORef']:'&nbsp;'; ?> / <?php print ($particulars[0]['invPODate'] != "" && $particulars[0]['invPODate'] != "0000-00-00" && $particulars[0]['invPODate'] != "1970-01-01")?date("d-m-Y", strtotime($particulars[0]['invPODate'])):'&nbsp;'; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Payment Terms
                                        </td>
                                        <td colspan="3" id="payement_terms" style="border-right:0px;">
                                             : <?php print $data['invPayTerms']; ?>&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Excise Control Code
                                        </td>
                                        <td colspan="3" id="invoice_reference" style="border-right:0px;">
                                            : <?php print $data['invECCNo']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="content_bold" style="border-right:0px;">
                                            Excise Range
                                        </td>
                                        <td colspan="3" id="invoice date" style="border-right:0px;">
                                            : <?php print $data['invERange']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Division
                                        </td>
                                        <td colspan="3" style="border-right:0px;">
                                            : <?php print $data['invEDivision']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Comissionerate
                                        </td>
                                        <td colspan="3" id="po_date"  style="border-right:0px;">
                                            : <?php print $data['invEComissionerate']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            TIN No.
                                        </td>
                                        <td colspan="3" id="po_reference" style="border-right:0px;">
                                            : <?php print $data['invGSTN']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;border-bottom:0px;">
                                            CST No. / Date
                                        </td>
                                        <td colspan="3" id="freight" style="border-right:0px;border-bottom:0px;">
                                             : <?php print $data['invPAN']; ?>&nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr height="30px" style="font-size:14px;">
               	<td class="content_center" width="10%" colspan="2">
                	Part No.
                </td>
            	<td class="content_center" colspan="3">
                	Description
                </td>
            	<td class="content_center" width="10%">
                	UNIT
                </td>
               	<td class="content_center" width="12%">
                	Quantity
                </td>
            	<td class="content_center" width="15%">
                	Rate
                </td>
            	<td class="content_center" width="10%">
                	Value (Rs.)
                </td>
            </tr>
            <?php
				$tqty		=	$particulars[0]['totQty'];;
				$amount		=	$particulars[0]['totAmt'];
			?>
			<tr height="50px" style="font-size:14px;">
				<td class="content_bold content_center" colspan="2" >
					<?php print $particulars[0]['invName']; ?>
				</td>
				<td class="content_bold content_center" colspan="3">
					<?php 						
						print $particulars[0]['invDesc'];
					?>
				</td>
				<td class="content_bold content_center">
					Nos.
				</td>
				<td class="content_bold content_right">
					<?php print ($tqty)?@number_format($tqty, 0):'&nbsp;'; ?>
				</td>
				<td class="content_bold content_right">
					<?php print ($particulars[0]['invRate'])?@number_format($particulars[0]['invRate'], 2):'&nbsp;'; ?>
				</td>
				<td class="content_bold content_right" style="font-size:14px;">
					<?php print ($amount)?@number_format($amount, 2):'&nbsp;'; ?>
				</td>
			</tr>
            <?php
				$insertValData	=	@getMySQLData("select * from tbl_invoice_component_insertval where invId='".$invoice_id."'");
				$insertValData	=	$insertValData['data'];	
				$totExciseDuty	=	$data['invCGSTAmt'];
				$grandTotal		=	$data['invGrandTotal'];
				$insValAvl		=	false;
				if(count($insertValData) > 0)
				{
					$insValAvl = true;
					$amount += $insertValData[0]['insValue'];
					$totExciseDuty += $insertValData[0]['insExcise'];
					$grandTotal	+= $insertValData[0]['insExcise'] + $insertValData[0]['insEC'] + $insertValData[0]['insSEC'];
				}
				
				if($invDate < $eduCessAbolishCutOff)
				{
			?>	
            <tr>
            	<td rowspan="2" colspan="6" valign="top" >
                	<div class="content_bold" style="padding-bottom:3px;">
                		Duty Amount in Words:
                    </div>					
					<?php echo number2Word($totExciseDuty); ?> only					
                </td>			
                <td class="content_bold" >
                	Excise Duty
                </td>
                <td class="content_right" >
                	<!--0 -->
                    <?php print @number_format($data['invCGST'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;" >
                	<?php print @number_format($data['invCGSTAmt'], 2); ?>
                </td>
            </tr>			
            <tr>
                <td class="content_bold" >
                	E Cess
                </td>
                <td class="content_right" >
                    <?php print @number_format($data['invECess'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format($data['invECessAmt'], 2); ?>
                </td>
            </tr>
			<tr>
            	<td colspan="6" rowspan="3" valign="top">
                	<div class="content_bold" style="padding-bottom:3px;">
                		Total Amount in Words:
                    </div>
					<div class="content_bold" style="font-size:14px;">
						<?php echo number2Word(@preg_replace("/[,]/", "", @number_format($grandTotal, 2))); ?> only
					</div>
                </td>				
            	<td class="content_bold" >
                	S&amp;H E Cess
                </td>
                <td class="content_right" >
                	<?php print @number_format($data['invSHEC'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format($data['invSHECAmt'], 2); ?>
                </td>
            </tr>
            <tr>
                <td class="content_bold" >
                	VAT Value
                </td>
                <td class="content_right" >
                	-
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format($data['invTaxableValAmt'], 2); ?>
                </td>
            </tr>
			<tr>
                <td class="content_bold" >
                	<?php print ($data['invIGST'] > 0)?'CST':'VAT'; ?>
                </td>
                <td class="content_right" >
                	<?php print ($data['invIGST'] > 0)?@number_format($data['invIGST'], 2):@number_format($data['invSGST'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print ($data['invIGST'] > 0)?@number_format($data['invIGSTAmt'], 2):@number_format($data['invSGSTAmt'], 2); ?>
                </td>
            </tr>
  
			<?php if($insValAvl) { ?>			
				<tr>
					<td colspan="6">
						<b>Value of Materials provided by you :</b>
						<?php print @number_format($insertValData[0]['insValue'],2); ?>
					</td>			
					<td class="content_bold" >
						Insert Excise Duty 
					</td>
					<td class="content_right" >
						<?php print @number_format($data['invCGST'], 2); ?>%
					</td>
					<td class="content_bold content_right" style="font-size:14px;">
						<?php print @number_format($insertValData[0]['insExcise'], 2); ?>
					</td>
				</tr>
				<tr>
					<td colspan='6'>
						<b>Total Excise Duty Assessable Value :</b>
						<?php print ($amount)?@number_format($amount, 2):'&nbsp;'; ?>
					</td>
					<td class="content_bold" >
						Insert E Cess
					</td>
					<td class="content_right" >
						<?php print @number_format($data['invECess'], 2); ?>%
					</td>
					<td class="content_bold content_right" style="font-size:14px;">
						<?php print @number_format($insertValData[0]['insEC'], 2); ?>
					</td>
				</tr>	
				<tr>
					<td  colspan='6'>
						<b>Total Excise Duty Amount :</b>
						<?php print @number_format($totExciseDuty, 2); ?>
					</td>				
					<td class="content_bold" >
						Insert S&amp;H E Cess
					</td>
					<td class="content_right" >
						<?php print @number_format($data['invSHEC'], 2); ?>%
					</td>
					<td class="content_bold content_right" style="font-size:14px;">
						<?php print @number_format($insertValData[0]['insSEC'], 2); ?>
					</td>
				</tr>		
			<?php	} 
			}
			else
			{?>
            <tr>
            	<td colspan="6" valign="top" >
                	<div class="content_bold" style="padding-bottom:3px;">
                		Duty Amount in Words:
                    </div>					
					<?php echo number2Word($totExciseDuty); ?> only					
                </td>			
                <td class="content_bold" >
                	Excise Duty
                </td>
                <td class="content_right" >
                	<!--0 -->
                    <?php print @number_format($data['invCGST'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;" >
                	<?php print @number_format($data['invCGSTAmt'], 2); ?>
                </td>
            </tr>			
			<tr>
            	<td colspan="6" rowspan="2" valign="top">
                	<div class="content_bold" style="padding-bottom:3px;">
                		Total Amount in Words:
                    </div>
					<div class="content_bold" style="font-size:14px;">
						<?php echo number2Word(@preg_replace("/[,]/", "", @number_format($grandTotal, 2))); ?> only
					</div>
                </td>				
                <td class="content_bold" >
                	VAT Value
                </td>
                <td class="content_right" >
                	-
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format($data['invTaxableValAmt'], 2); ?>
                </td>
            </tr>
			<tr>
                <td class="content_bold" >
                	<?php print ($data['invIGST'] > 0)?'CST':'VAT'; ?>
                </td>
                <td class="content_right" >
                	<?php print ($data['invIGST'] > 0)?@number_format($data['invIGST'], 2):@number_format($data['invSGST'], 2); ?>%
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print ($data['invIGST'] > 0)?@number_format($data['invIGSTAmt'], 2):@number_format($data['invSGSTAmt'], 2); ?>
                </td>
            </tr>
  
			<?php if($insValAvl) { ?>			
				<tr>
					<td colspan="6">
						<b>Value of Materials provided by you :</b>
						<?php print @number_format($insertValData[0]['insValue'],2); ?>
					</td>			
					<td class="content_bold" rowspan="3">
						Insert Excise Duty 
					</td>
					<td class="content_right" rowspan="3">
						<?php print @number_format($data['invCGST'], 2); ?>%
					</td>
					<td class="content_bold content_right" style="font-size:14px;" rowspan="3">
						<?php print @number_format($insertValData[0]['insExcise'], 2); ?>
					</td>
				</tr>
				<tr>
					<td colspan='6'>
						<b>Total Excise Duty Assessable Value :</b>
						<?php print ($amount)?@number_format($amount, 2):'&nbsp;'; ?>
					</td>
				</tr>	
				<tr>
					<td  colspan='6'>
						<b>Total Excise Duty Amount :</b>
						<?php print @number_format($totExciseDuty, 2); ?>
					</td>				
				</tr>		
			<?php	} 
			} ?>			
			<tr>
            	<td class="content_center" colspan="6">
                	TARIFF HEADING: <b><?php print ($particulars[0]['invTariff'])?$particulars[0]['invTariff']:'&nbsp;'; ?></b>
                </td>			
                <td colspan="2" class="content_bold" style="font-size:14px" >
                	Grand Total
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format($grandTotal, 2); ?>
                </td>
            </tr>			
            <tr>
            	<td class="content_center" colspan="3">
                	Issue of Invoice
                </td>	
            	<td class="content_center" colspan="3">
                	Removal of Goods
                </td>				
                <td colspan="3" rowspan="3" valign="top">
					<div style="padding-bottom:3px;">
						Remarks:
					</div>
					<?php print $data['invRemarks']; ?>
                </td>
            </tr>
            <tr>
            	<td class="content_center" colspan="2">
                	Date
                </td>	
            	<td class="content_center">
                	Time
                </td>
            	<td class="content_center" colspan="2">
                	Date
                </td>	
            	<td class="content_center">
                	Time
                </td>		
            </tr>
            <tr>
            	<td class="content_bold content_center" colspan="2">
                	<?php print date("d-m-Y", strtotime($data['entry_on'])); ?>
                </td>	
            	<td class="content_bold content_center">
                	<?php print date("H:m:s", strtotime($data['entry_on'])); ?>
                </td>
            	<td class="content_bold content_center" colspan="2">
                	<?php print date("d-m-Y", strtotime($data['entry_on'])); ?>
                </td>	
            	<td class="content_center">
                	&nbsp;
                </td>					
            </tr>
			<tr>
				<td colspan="9" valign="top">
                	<div style="padding-left:30px;padding-bottom:10px;padding-top:5px;text-align:justify; font-size:10px;">
                        Deductions / Additions made to arrive at value under sec (4) of the Act - Nil.<br/> 
							 Certified that the particulars furnished herein are true and correct and that the amount indicated <br/>represents the 
							 price actually charged and that there is no additional consideration flowing <br/>directly or otherwise from the buyer on
							 account of this transaction.</pre><br/>
                    </div>
                </td>
            </tr>
			<?php 
			} 
			if ($multiItemInvoice > 0 && $invtype == "cmpd") 
			{ 
			?>
				<tr>
					<td class="content_center">
						No. 
					</td>			
					<td colspan="4" class="content_center">
						Key Details 
					</td>					
					<td colspan="2" class="content_center">
						Manufacture Date						
					</td>					
					<td colspan="2" class="content_center">
						Despatch Plan/DI Details 
					</td>
				</tr>			
			<?php
				for($invRow = 0; $invRow < count($particulars);$invRow++)
				{ 
					$keyDtls			=	$particulars[$invRow]['keyIds'];
					$keyArr				=	split(",",$keyDtls);
					$diRef				=	$particulars[$invRow]['invDespId'];
					//get Manufacture Date
					$manuDateData		=	@getMySQLData("select date_format(planDate,'%d-%m-%Y') as manuDate 
																from tbl_moulding_plan tmp
																	inner join tbl_moulding_receive tmr on tmr.planRef = tmp.planid
																where tmr.modRecRef = '".$keyArr[0]."'");
					$manuDate			=	$manuDateData['data'][0]['manuDate'];				
					$component			=	@getMySQLData("select * from tbl_component where cmpdId='".$particulars[$invRow]['invCode']."'");
					$component			=	$component['data'];	
					if($component[0]['cmpdStdPckQty'] > 0 )
						$numPackets		+=	ceil($particulars[$invRow]['totQty'] / $component[0]['cmpdStdPckQty']);	
					else
						$numPackets++;
				?>
					<tr style="font-size:10px;">
						<td class="content_center" >
							<?php print ($invRow + 1); ?>
						</td>						
						<td colspan="4" class="content_bold" >
							<?php print $particulars[$invRow]['keyDetails']; ?>
						</td>
						<td colspan="2" class="content_bold content_center" >
							<?php print $manuDate; ?>
						</td>					
						<td colspan="2" class="content_bold content_center">
							<?php print $diRef; ?>
						</td>
					</tr>					
				<?php 
				}
			}
			else if($invtype == 'ram')
			{
			?>
				<tr height="15px">
					<td class="content_center" colspan="9">
						Our GRN Summary
					</td>	
				</tr>
				<tr height="60px">
					<td  colspan="9">
						<?php 
						$dispString 	= 	"";
						$totBatches 	=	0;
						for($invRow = 0; $invRow < count($particulars);$invRow++)
						{ 
							$keyDtls		=	$particulars[$invRow]['keyIds'];
							$keyArr			=	split(",",$keyDtls);
							$keyIds			=	"";
							foreach($keyArr as $key => $value)
								$keyIds	.=	(($keyIds != "")?"','":"").$value ;						
							$cpdData			=	@getMySQLData("select concat(invName,' - ',invDesc, ':', group_concat(concat(invPlanRef,'(',invQty,'Kgs)'))) as invDetails, count(*) as batches
																		from tbl_invoice_sales tic
																			inner join tbl_invoice_sales_items tici on tic.invId = tici.invId 
																		where tici.invPlanRef in ('".$keyIds."') and tic.status > 0 and tic.invId = '$invoice_id'
																	group by invName,invDesc order by invName");					
							foreach($cpdData['data'] as $key => $value)
							{
								$dispString	.=	(($dispString != "")?", ":"").$value['invDetails'] ;
								$totBatches	+=	$value['batches'];
							}
						}
							echo $dispString.", Total: ".$totBatches." GRN(s)";
						?>
					</td>	
				</tr>	
			<?php
			} 
			else if($invtype == 'cpd')
			{
			?>
				<tr height="15px">
					<td class="content_center" colspan="9">
						Batch Summary
					</td>	
				</tr>
				<tr height="60px">
					<td  colspan="9">
						<?php 
						$dispString 	= 	"";
						$totBatches 	=	0;
						for($invRow = 0; $invRow < count($particulars);$invRow++)
						{ 
							$keyDtls		=	$particulars[$invRow]['keyIds'];
							$keyArr			=	split(",",$keyDtls);
							$keyIds			=	"";
							foreach($keyArr as $key => $value)
								$keyIds	.=	(($keyIds != "")?"','":"").$value ;						
							$cpdData			=	@getMySQLData("select concat(invName, ':', count(*), 'batch(es)') as invDetails, count(*) as batches
																		from tbl_invoice_sales tic
																			inner join tbl_invoice_sales_items tici on tic.invId = tici.invId 
																		where tici.invPlanRef in ('".$keyIds."') and tic.status > 0 and tic.invId = '$invoice_id'
																	group by invName order by invName");					
							
							foreach($cpdData['data'] as $key => $value)
							{
								$dispString	.=	(($dispString != "")?", ":"").$value['invDetails'] ;
								$totBatches	+=	$value['batches'];
							}
						}
							echo $dispString.", Total: ".$totBatches."batch(es)";
						?>
					</td>	
				</tr>	
			<?php
			} 				
			else
			{			
				$keyDtls			=	$particulars[0]['keyIds'];
				$keyArr				=	split(",",$keyDtls);
				$diRef				=	$particulars[0]['invDespId'];
				//get Manufacture Date
				$manuDateData		=	@getMySQLData("select date_format(planDate,'%d-%m-%Y') as manuDate 
															from tbl_moulding_plan tmp
																inner join tbl_moulding_receive tmr on tmr.planRef = tmp.planid
															where tmr.modRecRef = '".$keyArr[0]."'");
				$manuDate			=	$manuDateData['data'][0]['manuDate'];			
				$component			=	@getMySQLData("select * from tbl_component where cmpdId='".$particulars[0]['invCode']."'");
				$component			=	$component['data'];	
				if($component[0]['cmpdStdPckQty'] > 0 && $invtype == 'cmpd')
					$numPackets		=	ceil($tqty / $component[0]['cmpdStdPckQty']);			
			
			?>
				<tr>
					<?php if($diRef != "" && $diRef != null && $diRef != 'undefined' ) {?>
						<td colspan="5" height="40px" valign="top">
							<?php print ($invtype == 'tool')?'Tool':(($invtype == 'mix')?'DC':'Key');?> Details :<b> <br /> <?php print $particulars[0]['keyDetails']; ?></b>
						</td>
						<?php if($manuDate) {?>
						<td colspan="2" height="40px" valign="top">
							Manufacture Date : 
							<div class="content_bold" style="padding-left:30px;padding-bottom:10px;padding-top:5px;text-align:center; font-size:14px;">
								<?php print $manuDate; ?>
							</div> 
						</td>					
						<td colspan="2" height="40px" valign="top">
							Despatch Plan/DI Details : 
							<div class="content_bold" style="padding-left:30px;padding-bottom:10px;padding-top:5px;text-align:center; font-size:14px;">
								<?php print $diRef; ?>
							</div> 
						</td>
						<?php } else { ?>
						<td colspan="4" height="40px" valign="top">
							Despatch Plan/DI Details : 
							<div class="content_bold" style="padding-left:30px;padding-bottom:10px;padding-top:5px;text-align:center; font-size:14px;">
								<?php print $diRef; ?>
							</div> 
						</td>					
					<?php } 
							} else { ?>
						<td colspan="9" height="40px" valign="top">
							<?php print ($invtype == 'tool')?'Tool':(($invtype == 'mix')?'DC':'Key');?> Details :<b> <br /> <?php print $particulars[0]['keyDetails'];  ?></b>
						</td>
					<?php } ?>
				</tr>
			<?php }?>

            <tr>
				<td colspan="9" style="padding:0px;">
                    <table cellspacing="0" cellpadding="5" width="100%">
                        <tr>
                            <td rowspan="4" style="width:43%;border-bottom:0px;" valign="top">
                                Receiver's Seal & Sign
                            </td>
							<td style="width:15%;border-bottom:0px;text-align:center;">
								No. of Packets
							</td>
							<td rowspan="3" style="width:42%;border-bottom:0px;border-right:0px;" valign="top">
                                For <?php  echo $_SESSION['app']['comp_name'];?>
                            </td>
						</tr>
						<tr>
							<td style="text-align:center;font-weight:bold">
								<?php print ($numPackets > 0)?$numPackets:'&nbsp;'; ?>
							</td>
						</tr>
						<tr>
							<td style="border-bottom:0px;text-align:center;">
								Packet Weight 
							</td>
						</tr>
						<tr>
							<td style="border-bottom:0px;">&nbsp;</td>
							<td style="text-align:right;border-bottom:0px;">Authorised Signatory</td>
						</tr>
                    </table>
                </td>
			</tr>
            <tr>
            	<td colspan="9" style="padding:0px;border-bottom:0px;">
                    <table cellspacing="0" cellpadding="3" width="100%">
                        <tr>
                            <td style="width:25%;border-bottom:0px;">
                                Buyer's Copy (Original) 
                            </td>
                            <td style="width:25%;border-bottom:0px;">
                                Duplicate for Transporter
                            </td>
                            <td style="width:25%;border-bottom:0px;">
                                Assessee's Copy 
                            </td>
                            <td style="width:25%;border-bottom:0px;border-right:0px;">
                                Extra Copy 
                            </td>
                        </tr>
						<tr>
							<td align="center"><b>White</b></td>
							<td align="center"><b>Blue</b></td>
							<td align="center"><b>Pink</b></td>
							<td align="center"><b>Green</b></td>
						</tr>
                    </table>
                </td>
            </tr>
        </table>
		<b>E. & O.E.</b>
		<?php 
			if($invCount+1<count($invIDs))
				echo "<div class='page_break'><br /></div>";
		}
		?>
		
    </body>
</html>