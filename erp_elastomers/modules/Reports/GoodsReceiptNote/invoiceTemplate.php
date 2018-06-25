<?php
	global $grn_role,$grn_customers,$grn_emailadd;	

	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$mod			=	trim((ISO_IS_REWRITE)?$_VAR['mod']:$_GET['mod']);
	$today			=	date("d-m-Y");
	
	if($_REQUEST["type"] == "RUNJOB" || $mod == 'manual') 
	{
		$loc_sql	=	@getMySQLData("select value from tbl_settings where name='mixLocCode'");
		$locCode	=	$loc_sql['data'][0]['value'];
		for($ct=0;$ct<count($grn_customers);$ct++){
			if($grn_role[$grn_customers[$ct]] == 'client')
			{
				$grnClient		=	$grn_customers[$ct];	
				$output 		= 	"";
				$rowcount		=	0;
				$sql			=	"select grnId, concat(grnId,'$locCode') as recNoId,grnDate,invPORef,invSupName,invRamName,invGrade,invNo,invDate,invRecvQty,invRamRate,
										invTotal,invCGSTVal,invSGSTVal,invIGSTVal,invGrandTotal,invTestCert,invExpiryDate,invHSNCode,invGSTRate 
									from tbl_invoice_grn";
				if($invoice_id != "" && $invoice_id != null)
					$sql			.=	" where grnId in ('".implode("','",explode(',',$invoice_id))."') and invIssuer='$grnClient' ";
				else
					$sql			.=	" where invIssuer='$grnClient' and emailSent = 0";
				//echo $sql; exit();
				$outSql 		=	@getMySQLData($sql);
				$grnDetails 	=   $outSql['data'];
				
				// Get Records from the table
				foreach($grnDetails as $keys=>$values) {
					foreach($values as $key=>$value) {	
						if($key == 'grnId')
						{
							$grnId	=	$value;
							@getMySQLData("update tbl_invoice_grn set emailSent = 1 where grnId = '$grnId'");
						}
						else
							$output .='"'.$value.'",';
					}
					$output .="\n";
					$rowcount++;
				}
				
				if($rowcount > 0) {
					$thefile = $_SERVER['DOCUMENT_ROOT']."/export/GRN".$today.".csv";
					file_put_contents($thefile, $output);
					$output = sendEmail($grn_emailadd[$grnClient],"","New GRNs: ".$today,"please find the GRNs for upload",$thefile);
				}
			}
		}			
		if($mod == 'manual')
		{
			if($output == 'success')
				echo '<script>javascript:window.open("","_self").close();</script>';
			else
				echo 'Sending GRNs as email failed due to: ' .$output . ' Click to <a href="javascript:window.open('."''".",'_self').close();".'">close</a>';
		}
		exit();
	}	
	
	$sql_grn				=	"select *, date_format(grnDate,\"%d-%b-%Y\") as grnDate, date_format(invDate, \"%d-%b-%Y\") as invDate, invIssuer, date_format(invExpiryDate, \"%d-%b-%Y\") as invExpiryDate from tbl_invoice_grn where grnId='$invoice_id' and status > 0 ";
	$grn					=	@getMySQLData($sql_grn);
	
	/*echo '<pre>';
		$sql_grn ; '<br/>';
		print_r($grn);
	echo '</pre>';*/
	
	$invoice_date			=	$grn['data'][0]['invDate'];
	$po_ref					=	$grn['data'][0]['invPoRef'];
	$grndate				=	$grn['data'][0]['grnDate'];
	$issuer					=	$grn['data'][0]['invIssuer'];	
	$supInvRef				=	$grn['data'][0]['invNo'];
	$testCertDet			=	$grn['data'][0]['invTestCert'];
	$testCertAvailable		= 	false;
	if($testCertDet != null && $testCertDet != '' && $testCertDet != 'N.A')
	{
		$testCertAvailable		= 	true;	
	}
	$doe					=	$grn['data'][0]['invExpiryDate'];
	$compRole				=	$grn_role[$issuer];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $invoice_id; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(11);
				print $formArray[0]; 
			?>
		</p> 
    	<table cellpadding="0" cellspacing="0" border="0" width="100%" id="print_out">
        	<tr>
            	<td>
                    <table cellpadding="3" cellspacing="0" width="100%" border="0">
                        <tr>
                        	<td rowspan="2" width="100px" align="center" style="padding:10px; border-top:0px;" >
                            	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                          	</td>
                            <td class="content_bold cellpadding content_center" height="80px">
                            	<div style="font-size:22px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
                            </td>
                            <td rowspan="2"  width="100px" valign="top" align="left">
								<b>GRN No:</b> <br /> <div style="font-size:12px;"><b><?php print $invoice_id; ?></b>&nbsp;</div>
								<br /><b>GRN Date:</b> <br /><div style="font-size:12px;"><b> <?php print $grndate; ?></b>&nbsp;</div>
								<hr noshade size=1 width="100%"></hr>
								<b>PO No:</b><br /><div style="font-size:12px;"><b> <?php print $po_ref; ?></b>&nbsp;</div>
                            </td>
                      	</tr>
                        <tr>
                        	<td align="center" style="font-size:13px;"><b><?php echo @getCompanyDetails('address'); ?> (CIN : <?php echo @getCompanyDetails('cin'); ?> )</b>
                            </td>
                      	<tr>
                  	</table>
       			</td>
          	</tr>
            <tr>
            	<td colspan="3" class="content_center content_bold font_16" style="">
                                <?php print $formArray[1]; ?>
                </td>
          	</tr>
			<?php if($compRole == 'client')
			{
			?>
			<tr>
				<td style="border-bottom:0px;border-right:0px;">
					<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="50%" height="80px" valign="top">
								Recieved From:<BR /> <BR /> <div style="font-size:14px;font-weight:bold"><?php echo $issuer;?></div>					
							</td>
							<td  width="50%" style="border-right:0px;" height="80px" valign="top">
								Supplier Details: <BR /> <div style="font-size:14px;font-weight:bold"><?php echo $grn['data'][0]['invSupDetail'];?></div>							
							</td>
						</tr>
					</table>
				</td>			
			</tr>
			<?php
			} 
				else 
			{ 
			?>
			<tr>
				<td style="border-bottom:0px;border-right:0px;">
					<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td height="80px" valign="top">
								Recieved From:<BR /> <BR /> <div style="font-size:14px;font-weight:bold"><?php echo $grn['data'][0]['invSupDetail'];?></div>					
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td style="border-bottom:0px;border-right:0px;">
					<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="5%" class="content_center">
								S.No					
							</td>
							<td width="35%" class="content_center">
								Item					
							</td>
							<td width="10%" class="content_center">
								UOM					
							</td>
							<td width="20%" class="content_center">
								Qty Advised					
							</td>
							<td width="15%" class="content_center">
								Qty Recieved					
							</td>
							<td width="15%" class="content_center">
								Qty Rejected					
							</td>							
						</tr>
						<tr height="70px" style="font-size:14px;">
							<td class="content_center content_bold"  valign="center">
								1				
							</td>
							<td  class="content_center content_bold"  valign="center">
								<?php print ($grn['data'][0]['invRamName'])?$grn['data'][0]['invRamName']:'&nbsp;'; ?> - 
								<?php print ($grn['data'][0]['invGrade'])?$grn['data'][0]['invGrade']:'&nbsp;'; ?>
							</td>
							<td  class="content_center content_bold"  valign="center">
								<?php print ($grn['data'][0]['invUom'])?$grn['data'][0]['invUom']:'&nbsp;'; ?>				
							</td>
							<td class="content_center content_bold" valign="center">
								<?php 
									print ($grn['data'][0]['invPOQty'])?@number_format($grn['data'][0]['invPOQty'], 3):'&nbsp;'; 
								?>
							</td>
							<td class="content_center content_bold"  valign="center">
								<?php print ($grn['data'][0]['invRecvQty'])?@number_format($grn['data'][0]['invRecvQty'], 3):'&nbsp;'; ?>				
							</td>
							<td class="content_center content_bold"  valign="center">
								&nbsp;					
							</td>							
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border-bottom:0px;border-right:0px;">
					<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="15%" align="left" valign="top" height="80px">
								Document Details
								<div style="font-size:14px;">  Invoice No:
								<BR /> <BR /> Date:</div>
							</td>
							<td width="25%" align="left" valign="top" height="80px" class="content_bold">
								<div style="font-size:14px;"><b> <br /> <?php print $supInvRef; ?>&nbsp;
								<BR /> <BR /> <?php print $invoice_date; ?>&nbsp;</b></div>
							</td>
							<td width="30%" align="left" valign="top" height="80px">
								Test Certificate Details:
								<div style="font-size:14px;"> &nbsp;&nbsp; <b>Recvd <?php print ($testCertAvailable)?'&#9746;':'&#9744;'; ?> &nbsp;&nbsp;&nbsp;&nbsp;Not Recvd <?php print ($testCertAvailable)?'&#9744;':'&#9746;'; ?></b>
								<BR /><BR /> Cert. Details: <BR /><b><?php print ($testCertDet)?$testCertDet:'&nbsp;'; ?></b></div>
							</td>
							<td width="30%" align="left" valign="top" height="80px">
								Use Before:
								<div style="font-size:14px;"><BR /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b><?php print $doe; ?> &nbsp;&nbsp;</b></div>
							</td>		
						</tr>
					</table>
				</td>
			</tr>					
            <tr>
            	<td style="border-bottom:0px;border-right:0px;">
                    <table cellpadding="3" cellspacing="0" border="0" width="100%">
                    	<tr>
                        	<td width="50%" style="border-bottom:0px;" valign="top">
                            	Remarks:
                                <br /><br /><br /><br />
                            </td>
                        	<td width="25%" style="border-bottom:0px;" valign="top">
                            	Approved:
                            </td>
                        	<td style="border-bottom:0px;" valign="top" align="left">
                            	Recieved By:
                            </td>
                        </tr>
                    	<tr>
                        	<td>
                            	<b>E & O.E.</b>
                            </td>
                        	<td>
								&nbsp;                            	
                            </td>
                        	<td>
                            	&nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
		</table>
    </body>
</html>