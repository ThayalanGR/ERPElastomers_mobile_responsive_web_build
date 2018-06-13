<?php

	global $cmpd_grp_email;
	$invoice_id		=	trim((ISO_IS_REWRITE)?$_VAR['invID']:$_GET['invID']);
	$lastMonth		=	date("F Y", strtotime(date('Y-m')." -1 month"));
	if($_REQUEST["type"] == "RUNJOB" ) 
	{
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
		
		$aEmail 	= 	new AsyncCreatePDFAndEmail("Sales/In-CpdStockLedger","stocklist", $cmpd_grp_email,""," In-Coming Compound Stock Report for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the in-coming Compound Stock Report for :".$lastMonth,true);									
		$aEmail->start();
		exit();
	}
	if ( $invoice_id == "stocklist")
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_NAME'].'/Sales/In-CpdStockLedger/?type=stocklist"</script>';
		exit();
	}

	$sql_getcom	=	"select cpdPolymer,cpdName ,group_concat(concat(SUBSTRING_INDEX(di.batId,'_',-1),'(',recvQty-issuedQty,')') separator ', ') as batRef ,sum(recvQty-issuedQty) as avlQty 
					from tbl_component_cpd_recv di
						inner join tbl_compound tc on di.cpdid=tc.cpdId
						inner join tbl_polymer_order tpo on polyName = cpdPolymer
					where di.status=1 and ((recvQty-issuedQty) > 0) group by cpdName order by dispOrder,cpdName";
		
	$getcom	    =	@getMySQLData($sql_getcom);
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Moulding Compound Stock Statement</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />		
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(45);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
			<tr>
				<td rowspan="2" align="center" >
					<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
				</td>
				<td colspan="3" class="content_bold cellpadding content_center" height="45px">
					<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
				</td>
				<td rowspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:14px;">&nbsp;</div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
				</td>
			</tr>
			<tr>
				<td colspan='3' align="center" style="font-size:16px;"><b>As on Date: <?php echo date('d-m-Y',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?></b>
				</td>
			<tr>			
			<tr>
				<th width="10%">
					No.
				</th>
				<th width="15%" align="left">
					Compound Name
				</th>
				<th width="55%" align="left">
					Batch Details
				</th>
				<th width="10%" align="right">
					Avl. Qty
				</th>
				<th align="right">
					Phy. Qty
				</th>				
			</tr>
			<?php
				$totsno			=	$getcom['count'];
				$particulars	=	$getcom['data'];
				$tAvlqty		=	0;	
				$polyQty		=	0;	
				$polymer		=	"";
				for($p=0;$p<$totsno;$p++)
				{					
					$avlQty		=	$particulars[$p]['avlQty'];
					$tAvlqty 	= 	$tAvlqty + $avlQty;
					if($polymer	==	"")
					{
						$polymer	=	$particulars[$p]['cpdPolymer'];
					}
					else if($polymer != $particulars[$p]['cpdPolymer'])
					{
			?>
						<tr>
							<td align="center" colspan="3">
								<?php print $polymer; ?> Total
							</td>
							<td align="right">
								<?php print @number_format($polyQty,3); ?>
							</td>
							<td align="right">
								&nbsp;
							</td>							
						</tr>
			<?php
						$polymer	=	$particulars[$p]['cpdPolymer'];
						$polyQty	=	0;
					}
					
					if($polymer == $particulars[$p]['cpdPolymer'])
					{
						$polyQty	+=	$avlQty;
					}
					
			?>
					<tr>
						<td align="center">
							<?php echo $p+1; ?>
						</td>
						<td align="left">
							<?php print ($particulars[$p]['cpdName'])?$particulars[$p]['cpdName']:'&nbsp;'; ?>
						</td>
						<td align="left" style="font-size:8px;">
							<?php print ($particulars[$p]['batRef'])?$particulars[$p]['batRef']:'&nbsp;'; ?>
						</td>
						<td align="right">
							<?php print @number_format($avlQty,3); ?>
						</td>
						<td align="right">
							&nbsp;
						</td>						
					</tr>			
			<?php		
				}
			?>
			<tr>
				<td align="center" colspan="3">
					<?php print $polymer; ?> Total
				</td>
				<td align="right">
					<?php print @number_format($polyQty,3); ?>
				</td>
				<td align="right">
					&nbsp;
				</td>				
			</tr>			
			<tr>
				<td colspan="3" class="content_center content_bold" >
					Total
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tAvlqty, 3); ?>
				</td>
				<td align="right">
					&nbsp;
				</td>				
			</tr>
			<tr>
				<td colspan="5" class="content_left content_bold" >
					Remarks: <BR></BR><BR></BR>
				</td>
			</tr>	
			<tr>
				<td colspan="2" class="content_left content_bold" >
					Prepared:
				</td>
				<td colspan="3" class="content_left content_bold" >
					Approved:
				</td>	
			</tr>
		</table>
     </body>
</html>