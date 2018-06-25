<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Deflashing Issue DC</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<br />
<?php
	global $HSN;
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$cpd_sql			=	"select tc.cmpdId,cmpdRefNo,cmpdName,defiss, issqty,operator,issdate 
							from tbl_deflash_issue tdi
							inner join tbl_component tc on tdi.cmpdid = tc.cmpdId  
							where tdi.defissref = '$invoice_id' and tdi.status > 0  
							order by cmpdName ";
	//echo $cpd_sql ; exit();
	$cpd_sql_arr		=	@getMySQLData($cpd_sql);	
	$locName			= 	$cpd_sql_arr['data'][0]['operator'];
	//echo $locName; exit();
	$userDet			= "";
	if($locName != 'In-House')
	{
		$sql_userin			=	"select * from tbl_users where fullName='".$locName."'";
		$out_userin			=	@getMySQLData($sql_userin);	
		$userin				=	$out_userin['data'][0];
		$userDet			=	"<br>".$userin['userDesignation']."<br>".$userin['userAddress1']."<br> ".$userin['userAddress2']." - ".$userin['userAddress3'];
	}
	else
	{
		$userDet			=  $locName;
	}	
	$issueDate				=	$cpd_sql_arr['data'][0]['issdate'];
	$tot_issQty 			= 	0;
	$oldCmpdId				=	"";
	$oldCmpdName			=	"";
	$cmpdId					=	"";
	$cmpdName				=	"";
	$cmpdDesc				=	"";
	$assValue				=	0;
	$cmpdRate				=	0;	
	?>
	<p align="right">Form No:
		<?php 
			// Get Form Details.
			$formArray		=	@getFormDetails(23);
			print $formArray[0]; 
		?>
	</p>	
	<table cellpadding="4" cellspacing="0" border="0" id="print_out" style="width:100%;">
		<tr>
			<td align="Left" >
				<div><b><?php  echo $_SESSION['app']['comp_name'];?></b></div>
				<b><?php echo @getCompanyDetails('address'); ?></b>
			</td>
			<?php if($locName != 'In-House') {?>
			<td rowspan="4" colspan='2' style='vertical-align:top;text-align:left'>
				To: <br/><b><?php echo $userDet; ?></b>
			</td>			
			<td align="center" valign="top" >
				<div style="text-align:left;font-size:10px;">DC No: </div> <div style="font-size:14px;"><b><?php echo $invoice_id; ?></b></div>
					<?php echo '<img src="'.$qr_genrate_url.'?id=trim~'.$invoice_id.'" />'; ?>				
			</td>
			<?php } 
			else
			{?>
			<td rowspan="2" colspan='2' style='font-size:16px;vertical-align:center;text-align:center'>
				<b>Deflashing Order</b>
			</td>			
			<td align="left" valign="top" >
				<div style="font-size:10px;">No:</div> <div style="margin-left:50px;font-size:14px;"><b><?php echo $invoice_id; ?></b></div>
			</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="left">GST NO: <b><?php  echo $_SESSION['app']['comp_gstn'];?></b></td>
			<td align="left" >Date: <b><?php echo $issueDate; ?></b></td>
		<tr>
		<?php if($locName != 'In-House') {?>
		<tr>
			<td align="center" style="font-size:16px;"><b><?php print $formArray[1]; ?></b></td>
			<td align="center" style="font-size:16px;"><b>Deflashing Order </b></td>
		</tr>
		<tr><td colspan='4' align='center'>Kindly Arrange to Trim and return the following items.</td></tr>
		<?php } ?>
		<tr>
			<td style="width:35%;">
				Plan ID
			</td>
			<td style="width:20%">
				Issued Qty (Nos)
			</td>				
			<td style="width:20%;">
				Returned Qty (Nos)
			</td>
			<td style="width:25%;">
				Remarks
			</td>						
		</tr>	
	
	<?php
	foreach($cpd_sql_arr['data'] as $cmpdDet){			
			$cmpdId		=  	$cmpdDet['cmpdId'];
			$cmpdName	=  	$cmpdDet['cmpdName'];
			$cmpdDesc	=  	$cmpdDet['cmpdRefNo'];
			$issQty		=	$cmpdDet['issqty'];
			$cmpdRate	=	$cmpdDet['rate'];
			if($oldCmpdId != "" && $oldCmpdId != $cmpdId)
			{
				if($cmpdRate > 0)
				{
					$assValue	+=	$cmpdRate * $tot_issQty;	
				}
				else
				{
					$cmpdPORate	=	@getMySQLData("select poRate from tbl_customer_cmpd_po_rate	where cmpdId='$oldCmpdId' and status > 0 order by update_on limit 1");
					$assValue	+=	$cmpdPORate['data'][0]['poRate'] * $tot_issQty;			
				}
		?>
			<tr>
				<td style="font-size:14px;text-align:center;">
					<?php echo $oldCmpdName."(".$cmpdDesc.")";  ?> - Total 
				</td>
				<td style="font-size:14px;text-align:right;">
					<b><?php echo number_format($tot_issQty);  ?></b> 
				</td>				
				<td style="font-size:14px;text-align:right;">
					&nbsp;
				</td>
				<td style="font-size:14px;text-align:right;">
					&nbsp;
				</td>						
			</tr>
		<?php 
				$oldCmpdName	=	$cmpdName;
				$oldCmpdId 		= 	$cmpdId;
				$tot_issQty		=	0;			
			}
			else if ($oldCmpdId == "" )
			{
				$oldCmpdName	=	$cmpdName;
				$oldCmpdId 		= 	$cmpdId;
			}
		?>
			<tr>
				<td class="content_bold" style="font-size:14px;text-align:center;">
					<?php print $cmpdDet['defiss']; ?>
				</td>
				<td class="content_bold" style="font-size:14px;text-align:right;">
					<?php echo $issQty  ?>
				</td>				
				<td style="font-size:14px;text-align:right;">
					&nbsp;
				</td>
				<td style="font-size:14px;text-align:right;">
					&nbsp;
				</td>						
			</tr>
	<?php 
			$tot_issQty += $issQty;
		}
		if($cmpdRate > 0)
		{
			$assValue	+=	$cmpdRate * $tot_issQty;	
		}
		else
		{
			$cmpdPORate	=	@getMySQLData("select poRate from tbl_customer_cmpd_po_rate	where cmpdId='$cmpdId' and status > 0 order by update_on limit 1");
			$assValue	+=	$cmpdPORate['data'][0]['poRate'] * $tot_issQty;			
		}		
	?>
			<tr>
				<td style="font-size:14px;text-align:center;">
					<?php echo $cmpdName."(".$cmpdDesc.")";  ?> - Total 
				</td>
				<td style="font-size:14px;text-align:right;">
					<b><?php echo number_format($tot_issQty);  ?></b> 
				</td>				
				<td style="font-size:14px;text-align:right;">
					&nbsp;
				</td>
				<td style="font-size:14px;text-align:right;">
					&nbsp;
				</td>						
			</tr>
			<?php if($locName != 'In-House') {?>
			<tr>
				<td style="text-align:center;border-right:0" >Estimated Taxable Value (Rs)</td>
				<td><b> : <?php echo number_format($assValue * 0.90,2); ?></b></td>
				<td style="text-align:center;border-right:0" >HSN Code</td>
				<td><b>: <?php echo $HSN['cmpd']; ?></b></td>
			</tr>
			<?php } ?>
			<tr>
				<td >
					<sup>Issued</sup> <br /> &nbsp;
				</td>
				<td >
					<sup>Received</sup> <br /> &nbsp;
				</td>				
				<td >
					<sup>Returned</sup> <br /> &nbsp;
				</td>
				<td >
					<sup>Data Entry</sup> <br /> &nbsp;
				</td>						
			</tr>	
		</table>
    </body>
</html>