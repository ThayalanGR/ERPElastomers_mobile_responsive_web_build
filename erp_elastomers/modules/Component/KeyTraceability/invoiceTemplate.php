<?php
	$keyId					=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$isInternal				=	true;
	$sql_getcom				=	"select tmr.modRecRef, timp.cmpdName, timp.cmpdRefNo, timp.cmpdPolymer, timp.cmpdCpdName, timp.cmpdBlankWgt, timp.cmpdCpdId, tmp.mdIssRef, timp.strip_dim1, timp.strip_dim3,
									timp.strip_weight, timp.cmpdCurTime, timp.cmpdCurTemp, timp.cmpdPressure, timp.strips_per_lift,timp.strip_dim2,timp.toolRef,timp.no_of_active_cavities,
									timp.liftPlanned,timp.invdate,timp.operator,tmr.mouldQty
									from tbl_moulding_receive tmr
										inner join tbl_moulding_plan tmp on tmr.planRef = tmp.planid and tmp.status > 0
										inner join tbl_invoice_mould_plan timp on tmp.planid = timp.planid
									WHERE tmr.status>0 and upper(tmr.modRecRef) = upper('$keyId')";
	if(substr($keyId,1,1) == 'O')
	{
		$sql_getcom		=	"select cmpdName, cmpdRefNo,'Open Stock' as operator,planref as modRecRef
									from tbl_moulding_quality tmq
										inner join tbl_component tc on tmq.cmpdid=tc.cmpdId
									WHERE tmq.status>0 and upper(tmq.planref) = upper('$keyId')";
		$isInternal		=	false;
	}
	else if(!(is_numeric(substr($keyId,1,1))))
	{
		$sql_getcom		=	"select cmpdName, cmpdRefNo,operator,planId as modRecRef
									from tbl_component_recv cr
										inner join tbl_component tc on cr.cmpdid=tc.cmpdId
									WHERE cr.status>0 and upper(cr.planId) = upper('$keyId')";
		$isInternal		=	false;
	}
	
	$getcom					=	@getMySQLData($sql_getcom);
	$particulars			=	$getcom['data'];
	$mdIssRef				=	$particulars[0]['mdIssRef'];
	$cpdId					=	$particulars[0]['cmpdCpdId'];
	$keyId					=	$particulars[0]['modRecRef'];
	$strip_dim1				=	$particulars[0]['strip_dim1'];
	$strip_dim2				=	$particulars[0]['strip_dim2'];
	$strip_dim3				=	$particulars[0]['strip_dim3'];
	$no_of_active_cavities	=	$particulars[0]['no_of_active_cavities'];
	$cmpdBlankWgt			=	$particulars[0]['cmpdBlankWgt'];
	$invdate				=	$particulars[0]['invdate'];
	$operator				=	$particulars[0]['operator'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Traceability Report for <?php print $keyId; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />		
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(36);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
			<tr>
				<td rowspan="2"  width="10%" align="center" >
					<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" height="70px" />
				</td>
				<td colspan="4" class="content_bold cellpadding content_center" height="45px">
					<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
				</td>
				<td rowspan="2"  width="15%" class="content_bold uppercase" >
					<div style="font-size:12px;">Date: </div>
					<div style="font-size:14px;" ><?php echo date('d-m-Y',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?></div>
					<div style="font-size:34px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
				</td>
			</tr>
			<tr>
				<td colspan='3' align="center" style="font-size:16px;border-right:0px">
					For Key Reference: <b><?php echo $keyId; ?> </b>
				</td>
				<td width="10%">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6"  class="content_center content_bold" style="font-size:14px;">
					Product Details
				</td>
				
			</tr>			
			<tr>
				<td colspan='2'>
					Part Number
				</td>					
				<td width="25%" class="content_bold">
					<?php print ($particulars[0]['cmpdName'])?$particulars[0]['cmpdName']:'&nbsp;'; ?>
				</td>
				<td width="25%">
					Description
				</td>					
				<td colspan="2" class="content_bold">
					<?php print ($particulars[0]['cmpdRefNo'])?$particulars[0]['cmpdRefNo']:'&nbsp;'; ?>
				</td>				
			</tr>
			<?php if($isInternal) {?>
			<tr>
				<td colspan="6"  class="content_center content_bold" style="font-size:14px;">
					Tool Details
				</td>			
			</tr>			
			<tr>
				<td colspan='2'>
					Tool ID
				</td>					
				<td width="25%" class="content_bold">
					<?php print $particulars[0]['toolRef']; ?>
				</td>
				<td width="25%">
					Cavities
				</td>					
				<td colspan="2" class="content_bold">
					<?php print $no_of_active_cavities; ?>
				</td>				
			</tr>	
			<tr>
				<td colspan="6" class="content_center content_bold" style="font-size:14px;">
					Compound Details
				</td>
			</tr>
			<tr >
				<td colspan='2'>
					Name (Base Polymer)
				</td>					
				<td class="content_bold">
					<?php print $particulars[0]['cmpdCpdName'] ." (".$particulars[0]['cmpdPolymer'].")"; ?>
				</td>
				<td>
					Blank Weight
				</td>					
				<td colspan="2"  class="content_bold">
					<?php print @number_format($cmpdBlankWgt,2); ?> gms
				</td>				
			</tr>
			<tr >
				<td colspan='2'>
					Batch Id
				</td>					
				<td class="content_bold">
					<?php 
						$getDC	=	@getMySQLData("select invId, date_format(invDate,'%d-%m-%Y') as invDate,group_concat(batId) as batId from tbl_component_cpd_recv  tccr
														inner join tbl_moulding_issue tmi on tmi.batRef = tccr.sno						
													where tccr.cpdId = '$cpdId' and mdIssRef = '$mdIssRef' group by tmi.mdIssRef ");
						$dcData	=	$getDC['data'];
						$batId	=	$dcData[0]['batId'];
						if($batId)
						{
							$batArr	=	split(",",$batId);
							for($arrCount=0;$arrCount < count($batArr);$arrCount++)							
							{
								$value	=	$batArr[$arrCount];
								print (strpos($value,'_')!== false)?substr(strrchr($value, "_"),1):$value;
								if($arrCount < count($batArr) -1)
									print ", ";
							}
						}
						else
						{
							print '&nbsp;';
						}				
					?>
				</td>
				<td>
					Invoice/DC no (Date)*
				</td>					
				<td colspan="2"  class="content_bold">
					<?php print ($batId)?$dcData[0]['invId']." (".$dcData[0]['invDate'].")":'&nbsp;';?>
				</td>				
			</tr>
			<tr>
				<td colspan="6" class="content_center content_bold" style="font-size:14px;">
					Process Parameters
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					Sheet Thickness
				</td>					
				<td class="content_bold">
					<?php print ($strip_dim1 > 0)?@number_format($strip_dim1,2)." mm":'&nbsp;'; ?> 
				</td>
				<td>
					Sheet Breath
				</td>					
				<td colspan="2"  class="content_bold">
					<?php print ($strip_dim3 > 0)?@number_format($strip_dim3,2)." mm":'&nbsp;'; ?> 
				</td>				
			</tr>
			<tr>
				<td colspan='2'>
					Strip Weight
				</td>					
				<td class="content_bold">
					<?php print ($particulars[0]['strip_weight'] > 0)?@number_format($particulars[0]['strip_weight'],2)." gms":'&nbsp;'; ?>
				</td>
				<td>
					Strips/Lift
				</td>					
				<td colspan="2"  class="content_bold">
					<?php print ($particulars[0]['strips_per_lift'] > 0)?@number_format($particulars[0]['strips_per_lift'],0):'&nbsp;'; ?> 
				</td>				
			</tr>
			<tr>
				<td colspan='2'>
					Strip Dimension (lXbXth)
				</td>					
				<td class="content_bold">
					<?php print (($strip_dim2) && ($strip_dim2 > 0))?@number_format($strip_dim2,2)." x":'- x '; ?>
					<?php print (($strip_dim3) && ($strip_dim3 > 0))?@number_format($strip_dim3,2)." x":'- x '; ?>
					<?php print (($strip_dim1) && ($strip_dim1 > 0))?@number_format($strip_dim1,2)." ":'-'; ?> mm 
				</td>
				<td>
					Curing Time
				</td>					
				<td colspan="2"  class="content_bold">
					<?php print ($particulars[0]['cmpdCurTime'] > 0)?@number_format($particulars[0]['cmpdCurTime'],0)." Sec":'&nbsp;'; ?> 
				</td>				
			</tr>			
			<tr>
				<td colspan='2'>
					Pressure
				</td>					
				<td class="content_bold">
					<?php print ($particulars[0]['cmpdPressure'])?$particulars[0]['cmpdPressure']." kg/cm<sup>2</sup>":'&nbsp;'; ?> 
				</td>
				<td>
					Temperature
				</td>					
				<td colspan="2"  class="content_bold">
					<?php print ($particulars[0]['cmpdCurTemp'] > 0)?number_format($particulars[0]['cmpdCurTemp'] - 10,0)." - ".number_format($particulars[0]['cmpdCurTemp'] + 10,0)." &deg;C":'&nbsp'; ?>
				</td>				
			</tr>
			<?php 
			}
			else
			{?>
			<tr>
				<td colspan='6' class="content_center content_bold">
					Traceability Records Maintained at <?php print $operator ?>
				</td>					
			</tr>			
			<?php }			
			?>				
			<tr>
				<td colspan="6"  class="content_center content_bold" style="font-size:14px;">
					Process Details
				</td>			
			</tr>	
			<tr style="font-size:8px;">
				<th colspan='2'>
					Name
				</th>					
				<th>
					Date
				</th>
				<th>
					Location
				</th>					
				<th colspan="2">
					Qty
				</th>				
			</tr>
			<?php if($isInternal)
			{ ?>
			<tr>
				<td colspan='2' class="content_center content_bold">
					Blanking
				</td>					
				<td class="content_center content_bold">
					<?php print date("d-m-Y", strtotime('-1 day', strtotime($invdate))); ?> 
				</td>
				<td class="content_center content_bold">
					<?php print $operator; ?>
				</td>					
				<td colspan="2"  class="content_right content_bold">
					<?php print number_format(($cmpdBlankWgt*$no_of_active_cavities*$particulars[0]['liftPlanned'])/1000,3); ?> Kgs 
				</td>				
			</tr>	
			<tr>
				<td colspan='2' class="content_center content_bold">
					Moulding
				</td>					
				<td class="content_center content_bold">
					<?php print date("d-m-Y", strtotime($invdate)); ?> 
				</td>
				<td class="content_center content_bold">
					<?php print $operator; ?>
				</td>					
				<td colspan="2"  class="content_right content_bold">
					<?php print ($particulars[0]['mouldQty'] > 0)?number_format($particulars[0]['mouldQty'],0):'&nbsp;'; ?> 
				</td>				
			</tr>
			<?php
				$getDefRec		=	@getMySQLData("select tdi.operator, tdr.defrecdate, tdr.currrec 
													from tbl_deflash_issue tdi
													left join tbl_deflash_reciept  tdr on tdr.defissref = tdi.sno
													where tdi.defiss = '$keyId' and tdi.status > 0 order by tdr.defrecdate");
				$defRecData		=	$getDefRec['data'];	
				for($p=0;$p<count($defRecData);$p++)
					{
					
			?>
					<tr>
						<td colspan='2' class="content_center content_bold">
							Deflashing
						</td>					
						<td class="content_center content_bold">
							<?php
								$deflashRecDate	=	$defRecData[$p]['defrecdate'];
								print (($deflashRecDate) && ($deflashRecDate != '0000-00-00'))?date("d-m-Y", strtotime($deflashRecDate)):'&nbsp;'; 
							?> 
						</td>
						<td class="content_center content_bold">
							<?php print $defRecData[$p]['operator']; ?>
						</td>					
						<td colspan="2"  class="content_right content_bold">
							<?php print ($defRecData[$p]['currrec'] > 0)?number_format($defRecData[$p]['currrec'],0):'&nbsp;'; ?> 
						</td>				
					</tr>
			<?php
				}
			}

			$getQualRec		=	@getMySQLData("select qualitydate, receiptqty, inspector 
												from tbl_moulding_quality
												where planref = '$keyId' and status > 0 group by qualityref order by qualitydate");
			$qualityData	=	$getQualRec['data'];	
			for($p=0;$p<count($qualityData);$p++)
			{					
			?>
					<tr>
						<td colspan='2' class="content_center content_bold">
							Inspection
						</td>					
						<td class="content_center content_bold">
							<?php
								$qualityDate	=	$qualityData[$p]['qualitydate'];
								print (($qualityDate) && ($qualityDate != '0000-00-00'))?date("d-m-Y", strtotime($qualityDate)):'&nbsp;'; 
							?> 
						</td>
						<td class="content_center content_bold">
							<?php print $qualityData[$p]['inspector']; ?>
						</td>					
						<td colspan="2"  class="content_right content_bold">
							<?php print ($qualityData[$p]['receiptqty'] > 0)?number_format($qualityData[$p]['receiptqty'],0):'&nbsp;'; ?> 
						</td>				
					</tr>
			<?php
				}
				$getDespRec		=	@getMySQLData("select invQty, tic.invid as dispinvid, tic.invDate, tc.cusName 
													from tbl_invoice_sales_items tici
													inner join tbl_invoice_sales tic on tic.invId = tici.invId
													inner join tbl_customer tc on tc.cusId = tic.invCusId
													where invPlanRef = '$keyId' and tic.status > 0  order by tic.invDate");
				$despatchData	=	$getDespRec['data'];	
				for($p=0;$p<count($despatchData);$p++)
				{
					
			?>
					<tr>
						<td colspan='2' class="content_center content_bold">
							Despatch
						</td>					
						<td class="content_center content_bold">
							<?php
								$despatchDate	=	$despatchData[$p]['invDate'];
								print (($despatchDate) && ($despatchDate != '0000-00-00'))?date("d-m-Y", strtotime($despatchDate)):'&nbsp;'; 
							?> 
						</td>
						<td class="content_center content_bold">
							<?php print $despatchData[$p]['cusName']; ?>
						</td>					
						<td colspan="2"  class="content_right content_bold">
							<?php print number_format($despatchData[$p]['invQty'],0)."(".$despatchData[$p]['dispinvid'].")"; ?> 
						</td>				
					</tr>
			<?php
				}
				function print_keyvalue($item2, $key)
				{
					echo $key . " - " .$item2.";";
				}
				$getInspRec		=	@getMySQLData("select receiptqty, appqty, rejcode, rejval, qualityref
													from tbl_moulding_quality 
													where planRef = '$keyId' and status > 0 ");
				$InspectionData	=	$getInspRec['data'];
				if(count($InspectionData) > 0)
				{
					$qualityRef	=	"";
					$inspQty	= 	0;
					$appQty		=	0;
					$rejQty		=	0;
					$rejDetails	=	array();
					for($p=0;$p<count($InspectionData);$p++)					
					{
						$rejQty					+=	$InspectionData[$p]['rejval'];
						$rejCode				=	$InspectionData[$p]['rejcode'];
						$rejDetails[$rejCode]	+=	$InspectionData[$p]['rejval'];
						if($qualityRef != $InspectionData[$p]['qualityref'])
						{
							$qualityRef	=	$InspectionData[$p]['qualityref'];
							$inspQty	+=	$InspectionData[$p]['receiptqty'];
							$appQty		+=	$InspectionData[$p]['appqty'];
						}
					
					}
			?>	
					<tr>
						<td colspan="6" class="content_center content_bold" style="font-size:14px;">
							Inspection Details
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<sub>Insp. Qty:</sub><div style="margin-left:30px;font-weight:bold;" ><?php print @number_format($inspQty,0); ?></div>
						</td>					
						<td>
							<sub>App. Qty:</sub><div style="margin-left:30px;font-weight:bold;"><?php print @number_format($appQty,0); ?></div> 
						</td>
						<td>
							<sub>NC. Qty:</sub>
							<div style="margin-left:10px;font-weight:bold;">
							<?php 
								if($rejQty > 0)
								{
									print @number_format($rejQty,0)."(";
									array_walk($rejDetails, 'print_keyvalue');
									print ")";
								}
								else
									print '0'; 
							?>
							</div>
						</td>					
						<td colspan="2">
							<sub>NC. %:</sub><div style="margin-left:30px;font-weight:bold;"><?php print @number_format(($rejQty / $inspQty) * 100,2); ?></div> 
						</td>				
					</tr>
			<?php } ?>
			
			<tr>
				<td colspan="3" class="content_bold" valign="top">
					Remarks:<br /><br />
				</td>			
				<td  class="content_bold" valign="top">
					Prepared:
				</td>
				<td colspan="2" class="content_bold" valign="top">
					Approved:
				</td>	
			</tr>
		</table>
     </body>
</html>