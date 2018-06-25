<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Moulding Issue</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<!-- <br /> -->
<?php
	global $HSN;
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$cpd_sql		=	"";
	if($invoice_id == 'BLENDISSUE')
	{
		$issdate		=	(ISO_IS_REWRITE)?trim($_VAR['issDate']):trim($_GET['issDate']);
		$cpd_sql		=  "select DATE_FORMAT(issueDate,'%d-%b-%Y')as issueDate, tmiss.cpdId as cmpdCpdId, tc.cpdName as cmpdCpdName, 'NA' as cmpdname, 'NA' as planid, issQty as advisedQty,'In-House' as operator, 'NA' as cmpdBlankWgt,issQty,rate
							from (select mdIssRef,cpdId, issueDate, sum(qtyIss) as issQty,rate from tbl_moulding_issue tmi inner join tbl_component_cpd_recv tccr on tccr.sno = tmi.batRef where tmi.mdIssRef = '$invoice_id' and issueDate = '$issdate'  group by tccr.cpdId) tmiss
							inner join tbl_compound tc  on tmiss.cpdId = tc.cpdId
							inner join tbl_polymer_order tpo on tpo.polyName = tc.cpdPolymer
							where tmiss.mdIssRef = '$invoice_id'  and issueDate = '$issdate' 
							order by dispOrder,cpdName";
	}	
	else
	{
		$cpd_sql		=	"select DATE_FORMAT(issueDate,'%d-%b-%Y')as issueDate, cmpdCpdId, cmpdCpdName, imp.cmpdname, imp.planid, ((imp.liftPlanned * cmpdBlankWgt * no_of_active_cavities )/1000) as advisedQty,tmp.operator,cmpdBlankWgt,issQty,rate
							from tbl_invoice_mould_plan imp
							inner join tbl_moulding_plan tmp on tmp.planid = imp.planid 
							inner join tbl_polymer_order tpo on tpo.polyName = imp.cmpdPolymer
							inner join (select cpdId, issueDate, sum(qtyIss) as issQty,rate from tbl_moulding_issue tmi inner join tbl_component_cpd_recv tccr on tccr.sno = tmi.batRef where tmi.mdIssRef = '$invoice_id'  group by tccr.cpdId) tmiss on tmiss.cpdId = imp.cmpdCpdId
							where tmp.mdIssRef = '$invoice_id'  
							order by dispOrder,cmpdCpdName";
	}
	//echo $cpd_sql ; exit();
	$cpd_sql_arr		=	@getMySQLData($cpd_sql);		
	$locName			= 	$cpd_sql_arr['data'][0]['operator'];
	$issueDate			=	$cpd_sql_arr['data'][0]['issueDate'];
	$cpdName			= 	"";
	$cpdId				= 	"";
	$cpdCount			=	0;
	$cpdTotal			=	0;
	$cpdIssTotal		=	0;
	$cpdIssGrandTotal	=	0;
	$userDet			= 	"";
	$cpdCost			=	0;
	$rate				=	0;
	if($locName != 'In-House')
	{
		$sql_userin			=	"select * from tbl_users where fullName='".$locName."'";
		//echo $sql_userin . "<br />";
		$out_userin			=	@getMySQLData($sql_userin);	
		$userin				=	$out_userin['data'][0];
		//echo var_dump($userin) . "<br />";
		$userDet			=	"<br>".$userin['userDesignation']."<br>".$userin['userAddress1']."<br> ".$userin['userAddress2']." - ".$userin['userAddress3'];
		//echo $userDet; exit();
	}
	else
	{
		$userDet			=  $locName;
	}	
	$tot_issQty 			= 	0;
		
	?>
		<p align="right">Form No:
			<?php 
				// Get Form Details.
				$formArray		=	@getFormDetails(21);
				print $formArray[0]; 
			?>
		</p>    	
		<table cellpadding="4" cellspacing="0" border="0" id="print_out" style="width:100%;">
        	<tr>
            	<td align="Left" colspan='3' >
                	<div><b><?php  echo $_SESSION['app']['comp_name'];?></b></div>
					<b><?php echo @getCompanyDetails('address'); ?></b>
                </td>
				<?php if($locName != 'In-House') {?>
                <td rowspan="4" colspan='3' style='vertical-align:top;text-align:left' >
                	To: <br/> <b><?php echo $userDet; ?></b>
                </td>
                <td align="center" valign="top" colspan='2'>
					<div style="text-align:left;font-size:10px;">DC No: </div> <div style="font-size:14px;"><b><?php echo $invoice_id; ?></b></div>
					<?php echo '<img src="'.$qr_genrate_url.'?id=mold~'.$invoice_id.'" />'; ?>
                </td>
				<?php } 
				else
				{?>
				<td rowspan="2" colspan='3' style='font-size:16px;vertical-align:center;text-align:center'>
					<b><?php print ($invoice_id == 'BLENDISSUE')?"Blend Issue":"Compound Order" ?></b>
				</td>			
				<td align="left" valign="top" colspan='2'>
					<div style="font-size:10px;">No:</div> <div style="margin-left:50px;font-size:14px;"><b><?php echo $invoice_id; ?></b></div>
				</td>
				<?php } ?>				
            </tr>
			<tr>
				<td align="left" colspan='3'>GST NO: <b><?php  echo $_SESSION['app']['comp_gstn'];?></b></td>
				<td align="left" colspan='2'>Date: <b><?php echo $issueDate; ?></b></td>
			<tr>
			<?php if($locName != 'In-House') {?>
            <tr>
				<td align="center" style="font-size:16px;" colspan='3'><b><?php print $formArray[1]; ?></b></td>
				<td align="center" style="font-size:16px;" colspan='2'><b>Compound Order </b></td>
            </tr>
			<tr><td colspan='8' align='center'>Kindly Arrange to Mold/Trim and return the following items.</td></tr>
			<?php } ?>
			<tr>
				<td style="width:12%;">
					Key ID
				</td>
				<td style="width:15%;">
					Part Number
				</td>
				<td style="width:15%;">
					Compound Name
				</td>
				<td style="width:8%;">
					Blank Weight
				</td>				
				<td style="width:12%">
					Adviced Qty (kgs)
				</td>				
				<td style="width:12%;">
					Issued Qty (kgs)
				</td>
				<td style="width:12%;">
					Returned Qty (kgs)
				</td>				
				<td>
					Remarks
				</td>						
			</tr>			
		<?php
		foreach($cpd_sql_arr['data'] as $cpdDet){
			$planid		=	$cpdDet['planid'];
			$planid		=	($planid)?((strpos($planid,'_')!== false)?strstr($planid,"_",true):$planid):'&nbsp;';
			$tot_issQty += 	$cpdDet['advisedQty'];
			if($cpdId != 	$cpdDet['cmpdCpdId'])
			{ 
				$cpdCount++;
				if($cpdCount > 1)
				{
					if($rate > 0)
						$cpdCost	+= $cpdIssTotal * $rate; 
					else
						$cpdCost	+= $cpdIssTotal * getCompoundCost($cpdId)
				?>
					<tr style="font-size:12px;" height="40px">
						<td colspan="4" align="right">
							<?php echo $cpdName; ?>	&nbsp; Total
						</td>				
						<td class="content_bold" align='right'>
							<?php echo number_format($cpdTotal,3); ?>
						</td>
						<td class="content_bold" align='right'>
							<?php echo number_format($cpdIssTotal,3); ?>
						</td>
						<td align='right'>
							&nbsp;
						</td>						
						<td >
							&nbsp;
						</td>				
					</tr>
				<?php
				}
				$cpdName 			= 	$cpdDet['cmpdCpdName'];	
				$cpdId 				= 	$cpdDet['cmpdCpdId'];
				$cpdTotal			=	$cpdDet['advisedQty'];
				$cpdIssTotal		=	$cpdDet['issQty'];
				$rate				=	$cpdDet['rate'];
				$cpdIssGrandTotal	+=	$cpdIssTotal;
			}
			else
			{
				$cpdTotal	+=	$cpdDet['advisedQty'];
			}
			
	?>	
 
            <tr height="40px">
				<td class="content_bold">
					<?php echo $planid; ?>
				</td>
				<td class="content_bold">
					<?php echo $cpdDet['cmpdname']; ?>
				</td>
				<td class="content_bold">
					<?php echo $cpdName; ?>
				</td>				
				<td class="content_bold" align='right'>
					<?php echo ($cpdDet['cmpdBlankWgt'] > 0)?number_format($cpdDet['cmpdBlankWgt'],3):$cpdDet['cmpdBlankWgt']; ?>
				</td>
				<td class="content_bold" align='right'>
					<?php echo number_format($cpdDet['advisedQty'],3); ?>
				</td>
				<td align='right'>
					&nbsp;
				</td>				
				<td align='right'>
					&nbsp;
				</td>
				<td >
					&nbsp;
				</td>				
            </tr>			

		<?php
		}
		if($cpdCount > 1)
		{
			if($rate > 0)
				$cpdCost	+= $cpdIssTotal * $rate; 
			else
				$cpdCost	+= $cpdIssTotal * getCompoundCost($cpdId)
		?>
			<tr style="font-size:12px;" height="40px">
				<td colspan="4" align="right">
					<?php echo $cpdName; ?>	&nbsp; Total
				</td>				
				<td class="content_bold" align='right'>
					<?php echo number_format($cpdTotal,3); ?>
				</td>
				<td class="content_bold" align='right'>
					<?php echo number_format($cpdIssTotal,3); ?>
				</td>				
				<td align='right'>
					&nbsp;
				</td>
				<td >
					&nbsp;
				</td>				
			</tr>
		<?php
		}		
		?>
			<tr height="40px">
				<td style="font-size:14px;text-align:center;" colspan='4' >
					Total 
				</td>
				<td style="font-size:14px;text-align:right;">
					<b><?php echo number_format($tot_issQty,3);  ?></b> 
				</td>
				<td style="font-size:14px;text-align:right;">
					<b><?php echo number_format($cpdIssGrandTotal,3); ?></b>
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
				<td style="text-align:center;border-right:0" colspan='2'>Estimated Taxable Value (Rs)</td>
				<td style="border-right:0"><b> :</b></td>
				<td style="text-align:right;"><b> <?php echo number_format($cpdCost * 0.90,2); ?></b></td>
				<td style="text-align:center;border-right:0" colspan='2'>HSN Code</td>
				<td style="border-right:0"><b> :</b></td>
				<td><b> <?php echo $HSN['cpd']; ?></b></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan='2'>
					<sup>Issued</sup> <br /> &nbsp;
				</td>
				<td colspan='2'>
					<sup>Received</sup> <br /> &nbsp;
				</td>				
				<td colspan='2'>
					<sup>Returned</sup> <br /> &nbsp;
				</td>
				<td colspan='2'>
					<sup>Data Entry</sup> <br /> &nbsp;
				</td>						
			</tr>	
		</table>
    </body>
</html>