<?php
	global $cmpd_grp_email;
	$formArray		=	@getFormDetails(25);
	if($_REQUEST["type"] == "RUNJOB") 
	{		
		$lastMonth	=	date("F Y", strtotime(date('Y-m')." -1 month"));
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
		// send email
		$aEmail = new AsyncCreatePDFAndEmail("Component/VendorBillPassing",$lastMonth, $cmpd_grp_email,"",$formArray[1]." Summary for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Vendor Bill Summary for :".$lastMonth);									
		$aEmail->start();	
		exit();						
	}	
	$curMonth		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
	$oper			=	(ISO_IS_REWRITE)?trim($_VAR['operator']):trim($_GET['operator']);
	$procType		=	(ISO_IS_REWRITE)?trim($_VAR['process']):trim($_GET['process']);
	$repType		=	(ISO_IS_REWRITE)?trim($_VAR['repType']):trim($_GET['repType']);
	$curMonth		=	($curMonth)?$curMonth:date("F Y",mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
	$oper			=	($oper)?$oper:'All';
	$procType		=	($procType)?$procType:'All';
	$repType		=	($repType)?$repType:'Vendor-wise';
	$totlifts		=	0;
	$totmodcost		=	0;
	$tottrimqty		=	0;
	$tottrimcost	=	0;
	$totinspqty		=	0;
	$totrejqty		=	0;
	$totrejval		=	0;				
	$totqty			=	0;	
	
?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Vendor Bill(s) for <?php print $curMonth.(($operator != 'All')?" For ".$operator:""); ?></title>
			<link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
		</head>
		<body>
		
	<?php if ($repType =='Vendor-wise') { ?>
		<p align="right">Form No:<?php print $formArray[0]; ?></p>	
		<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
				<tr>
					<td rowspan="2" colspan="2" align="center" style="padding:3px" >
						<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="50px" />
					</td>
					<td colspan="<?php print(($procType == 'All')?"7":"5");?>" class="content_bold cellpadding content_center" >
						<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
					</td>
					<td style="font-size:14px;" rowspan="2" colspan="1" class="content_center content_bold" >
						<?php echo date("d-m-Y"); ?>
					</td>
				</tr>
				<tr>
					<td colspan="<?php print(($procType == 'All')?"7":"5");?>" style="font-size:14px;" class="content_center content_bold">
						For <?php echo $operator; ?> for <?php echo $curMonth; ?>
					</td>
				</tr>	
				<tr style="font-size:8px;" height="10px">
				<th width="5%">
					No
				</th>
				<th colspan="2" width="30%">
					Vendor Name
				</th>
				<?php if($procType == 'All') {?>		
				<th width="7.5%">
					Lifts<sup>Nos</sup>
				</th>
				<th width="7.5%">
					Mold Cost<sup>Rs</sup>
				</th>
				<th width="7.5%">
					Rec. Qty<sup>Nos</sup>
				</th>
				<th width="7.5%">
					Trim Cost<sup>Rs</sup>
				</th>					
				<?php } else if ($procType == 'Moulding') { ?>
				<th width="15%">
					Lifts<sup>Nos</sup>
				</th>
				<th width="15%">
					Mold Cost<sup>Rs</sup>
				</th>					
				<?php } else if ($procType == 'Trimming') { ?>
				<th width="15%">
					Rec. Qty<sup>Nos</sup>
				</th>
				<th width="15%">
					Trim Cost<sup>Rs</sup>
				</th>
				<?php } ?>
				<th width="7.5%">
					Rej. %
				</th>
				<th width="7.5%">
					Rej. Value<sup>Rs</sup>
				</th>
				<th>
					Total Value<sup>Rs</sup>
				</th>								
             </tr>		
	
	<?php
	}
	if($oper == 'All' || $repType =='Vendor-wise')
	{
		$sql			=	"SELECT distinct operator from 
								(".(($procType != 'Trimming')?"SELECT operator FROM tbl_moulding_receive 
									WHERE status > 2 and  DATE_FORMAT(entry_on,'%M %Y') = '$curMonth'":"")
									.(($procType == 'All')?" UNION ":"").
									(($procType != 'Moulding')?"SELECT tdi.operator FROM tbl_deflash_reciept tdr
										inner join tbl_deflash_issue tdi on tdi.sno = tdr.defissref
									WHERE tdr.status > 0 and  DATE_FORMAT(defrecdate,'%M %Y') = '$curMonth'":"")." )tbl1
							ORDER BY operator";							
		$operators		=  	@getMySQLData($sql);
		$operators		= 	$operators['data'];
	}
	else
	{
		$operators[0]['operator']		= 	$oper;
	}
	
	for($operCount=0;$operCount<count($operators);$operCount++)
	{
		$operator			=	$operators[$operCount]['operator'];	
		$sql_particulars	=	"select concat(SUBSTRING_INDEX(tcd.planRef, '_', 1),'-',SUBSTRING_INDEX(tcd.planRef, '-', -1),'('".(($procType != 'Trimming')?", lift_rate":"").(($procType == 'All')?",':'":"") .(($procType != 'Moulding')?", trim_rate":"").",')') as dispPlanRef,tcd.planRef, recvdate, cmpdName, lift_rate,sum(actualLifts) as actualLifts,sum( moldcost) as moldcost, trim_rate, sum(trimrecvqty) as trimrecvqty, sum(trimcost) as trimcost, sum(ifnull(tmq.appqty,0) + ifnull(tmq.rejqty,0)) as inspectqty, sum(ifnull(tmq.rejqty,0)) as rejectqty, sum(ifnull(tmq.rejqty,0) * tccpr.poRate) as rejectval
									from (".(($procType != 'Trimming')?"select tmr.modRecRef as planRef, tmp.cmpdId, DATE_FORMAT(tmr.entry_on,'%d-%m-%Y') as recvdate,tmp.cmpdName,lift_rate,tmr.actualLifts,(tmr.actualLifts * lift_rate) as moldcost, trim_rate,0 as trimrecvqty, 0 as  trimcost
												from tbl_moulding_receive tmr 
												inner join tbl_invoice_mould_plan tmp on tmp.planid = tmr.planRef
												inner join tbl_tool tt on tt.tool_ref = tmr.toolRef												
											where DATE_FORMAT(tmr.entry_on,'%M %Y') ='$curMonth' and tmr.status>2 and tmr.operator = '$operator'":"")	
											.(($procType == 'All')?" UNION ALL ":"").
											(($procType != 'Moulding')?"select tdi.defiss as planRef, tmp.cmpdId, DATE_FORMAT(tdr.defrecdate,'%d-%m-%Y') as recvdate, tmp.cmpdName, lift_rate, 0 as actualLifts, 0 as moldcost, trim_rate,tdr.currrec  as trimrecvqty,(tdr.currrec * trim_rate) as trimcost
												FROM tbl_deflash_reciept tdr
												inner join tbl_deflash_issue tdi on tdr.issref = tdi.defiss
												inner join tbl_invoice_mould_plan tmp on tmp.planid = tdi.mouldref												
												inner join tbl_tool tt on tt.tool_ref = tmp.toolRef
											where  tdr.status > 0 and  DATE_FORMAT(defrecdate,'%M %Y') = '$curMonth' and tdi.operator = '$operator'":"")	
										.") as tcd
										left join (select planref,appqty, sum(if(rejcode != 'REWORK',rejval,0)) as rejqty from tbl_moulding_quality group by planref) tmq on tmq.planref = tcd.planRef
										inner join (select * from (SELECT cmpdId, poRate FROM tbl_customer_cmpd_po_rate where status  > 0 ORDER BY update_on desc) tpo group by cmpdId)tccpr on tccpr.cmpdId = tcd.cmpdId
									group by tcd.planRef,recvdate
									order by ". (($repType !='Key-wise')?"cmpdName,recvdate":"recvdate");
		//echo $sql_particulars; exit();
		$out_particulars	=	@getMySQLData($sql_particulars);
		$particulars		=	$out_particulars['data'];
		$noOfKeys			=	count($particulars);
		if ($repType !='Vendor-wise') {		
?>
		<p align="right">Form No:<?php print $formArray[0]; ?></p>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
         	<tr>
            	<td rowspan="2" colspan="2" align="center" style="padding:3px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="50px" />
                </td>
                <td colspan="<?php print(($procType == 'All')?"7":"5");?>" class="content_bold cellpadding content_center" >
                	<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
                </td>
                <td style="font-size:14px;" rowspan="2" colspan="1" class="content_center content_bold" >
                	<?php echo date("d-m-Y"); ?>
                </td>
            </tr>
            <tr>
            	<td colspan="<?php print(($procType == 'All')?"7":"5");?>" style="font-size:14px;" class="content_center content_bold">
                	For <?php echo $operator; ?> for <?php echo $curMonth; ?>
                </td>
            </tr>			
            <tr style="font-size:8px;" height="10px">
				<th width="5%">
					No
				</th>
				<?php if($repType =='Summary') { ?>
				<th colspan="2" width="30%">
					Component Name
				</th>
				<?php } else { ?>
				<th width="20%">
					Key Id
				</th>
				<th width="10%">
					Recv. Date
				</th>
				<?php } if($procType == 'All') {?>		
				<th width="7.5%">
					Lifts<sup>Nos</sup>
				</th>
				<th width="7.5%">
					Mold Cost<sup>Rs</sup>
				</th>
				<th width="7.5%">
					Rec. Qty<sup>Nos</sup>
				</th>
				<th width="7.5%">
					Trim Cost<sup>Rs</sup>
				</th>					
				<?php } else if ($procType == 'Moulding') { ?>
				<th width="15%">
					Lifts<sup>Nos</sup>
				</th>
				<th width="15%">
					Mold Cost<sup>Rs</sup>
				</th>					
				<?php } else if ($procType == 'Trimming') { ?>
				<th width="15%">
					Rec. Qty<sup>Nos</sup>
				</th>
				<th width="15%">
					Trim Cost<sup>Rs</sup>
				</th>
				<?php } ?>
				<th width="7.5%">
					Rej. %
				</th>
				<th width="7.5%">
					Rej. Value<sup>Rs</sup>
				</th>
				<th>
					Total Value<sup>Rs</sup>
				</th>								
             </tr>
            <?php
			}
				$pgPixel		=	150;
				$pgBrk			=	1000;
				$compNo			=	0;
				$tlifts			=	0;
				$tglifts		=	0;
				$tmodcost		=	0;
				$tgmodcost		=	0;
				$ttrimqty		=	0;
				$tgtrimqty		=	0;
				$ttrimcost		=	0;
				$tgtrimcost		=	0;
				$tinspqty		=	0;
				$tginspqty		=	0;
				$trejqty		=	0;
				$tgrejpqty		=	0;
				$trejval		=	0;
				$tgrejval		=	0;				
				$tqty			=	0;
				$tgqty			=	0;				
				$currCmpdName	=	"";
				for($p=0;$p<$noOfKeys;$p++){
					if($currCmpdName == "" )
						$currCmpdName	= 	$particulars[$p]['cmpdName'];						
					else if( $currCmpdName != $particulars[$p]['cmpdName'] && $repType !='Key-wise' && $repType !='Vendor-wise' )
					{
						$compNo++;?>
						<tr height="20px" style="font-size:8px;">
							<?php if($repType =='Summary') { ?>
							<td>
								<?php print $compNo; ?>
							</td>
							<?php } ?>
							<td colspan ="<?php print (($repType == 'Summary')?'2':'3'); ?>" class="content_center content_bold">
								<?php print $currCmpdName. " (".(($procType != 'Trimming')?(($tglifts)?@number_format($tgmodcost/$tglifts, 3):'0.000'):"").(($procType == 'All')?":":"") .(($procType != 'Moulding')?(($tgtrimqty)?@number_format($tgtrimcost/$tgtrimqty, 3):'0.000'):"").")"; ?>
							</td>
							<?php if($procType == 'All') {?>		
							<td class="content_bold content_right">
								<?php print @number_format($tglifts, 0); ?>
							</td>
							<td class="content_bold content_right">
								<?php print @number_format($tgmodcost, 0); ?>
							</td>
							<td class="content_bold content_right">
								<?php print @number_format($tgtrimqty, 0); ?>
							</td>	
							<td class="content_bold content_right">
								<?php print @number_format($tgtrimcost, 0); ?>
							</td>	
							<?php } else if ($procType == 'Moulding') { ?>
							<td class="content_bold content_right">
								<?php print @number_format($tglifts, 0); ?>
							</td>
							<td class="content_bold content_right">
								<?php print @number_format($tgmodcost, 0); ?>
							</td>				
							<?php } else if ($procType == 'Trimming') { ?>
							<td class="content_bold content_right">
								<?php print @number_format($tgtrimqty, 0); ?>
							</td>	
							<td class="content_bold content_right">
								<?php print @number_format($tgtrimcost, 0); ?>
							</td>	
							<?php } ?>	
							<td class="content_bold content_right">
								<?php print ($tginspqty)?@number_format(($tgrejqty/$tginspqty)*100, 2):'0.00'; ?>
							</td>	
							<td class="content_bold content_right">
								<?php print @number_format($tgrejval, 0); ?>
							</td>
							<td class="content_bold content_right">
								<?php print @number_format($tgqty,0); ?>
							</td>							
						</tr>					
					<?php
						if($repType !='Key-wise' && $repType !='Vendor-wise')
							$pgPixel		+=	20;
						$currCmpdName	= 	$particulars[$p]['cmpdName'];	
						$tglifts		=	0;
						$tgmodcost		=	0;
						$tgtrimqty		=	0;
						$tgtrimcost		=	0;
						$tginspqty		=	0;
						$tgrejqty		=	0;
						$tgrejval		=	0;				
						$tgqty			=	0;						
					}
					if($repType !='Summary' && $repType !='Vendor-wise')
						$pgPixel		+=	15;
					$planRef		=	$particulars[$p]['dispPlanRef'];
					$actualLifts	=	$particulars[$p]['actualLifts'];				
					$moldCost		=	$particulars[$p]['moldcost'];
					$trimrecvqty	=	$particulars[$p]['trimrecvqty'];
					$trimCost		=	$particulars[$p]['trimcost'];
					$inspectqty		=	$particulars[$p]['inspectqty'];
					$rejectqty		=	$particulars[$p]['rejectqty'];
					$rejectval		=	$particulars[$p]['rejectval'];
					$tlifts			+=	$actualLifts;
					$tglifts		+=	$actualLifts;
					$totlifts		+=	$actualLifts;
					$tmodcost		+=	$moldCost;
					$tgmodcost		+=	$moldCost;
					$totmodcost		+=	$moldCost;
					$ttrimqty		+=	$trimrecvqty;
					$tgtrimqty		+=	$trimrecvqty;
					$tottrimqty		+=	$trimrecvqty;
					$ttrimcost		+=	$trimCost;
					$tgtrimcost		+=	$trimCost;
					$tottrimcost	+=	$trimCost;
					$tinspqty		+=	$inspectqty;
					$tginspqty		+=	$inspectqty;
					$totinspqty		+=	$inspectqty;
					$trejqty		+=	$rejectqty;
					$tgrejqty		+=	$rejectqty;
					$totrejqty		+=	$rejectqty;
					$trejval		+=	$rejectval;
					$tgrejval		+=	$rejectval;	
					$totrejval		+=	$rejectval;
					$tqty			+=	$moldCost + $trimCost;
					$tgqty			+=	$moldCost + $trimCost;
					$totqty			+=	$moldCost + $trimCost;
					
					if((($pgPixel / $pgBrk) > 1) && $p > 0 )
					{ 
						$pgPixel	=	150;
						?>		
						<tr>
							<td colspan="<?php print(($procType == 'All')?"10":"8");?>" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="<?php print(($procType == 'All')?"10":"8");?>" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;" height="10px">
							<th width="5%">
								No
							</th>
							<th width="20%">
								Key Id
							</th>
							<th width="10%">
								Recv. Date
							</th>							
							<?php if($procType == 'All') {?>		
							<th width="7.5%">
								Lifts<sup>Nos</sup>
							</th>
							<th width="7.5%">
								Mold Cost<sup>Rs</sup>
							</th>
							<th width="7.5%">
								Rec. Qty<sup>Nos</sup>
							</th>
							<th width="7.5%">
								Trim Cost<sup>Rs</sup>
							</th>					
							<?php } else if ($procType == 'Moulding') { ?>
							<th width="15%">
								Lifts<sup>Nos</sup>
							</th>
							<th width="15%">
								Mold Cost<sup>Rs</sup>
							</th>					
							<?php } else if ($procType == 'Trimming') { ?>
							<th width="15%">
								Rec. Qty<sup>Nos</sup>
							</th>
							<th width="15%">
								Trim Cost<sup>Rs</sup>
							</th>
							<?php } ?>
							<th width="7.5%">
								Rej. %
							</th>
							<th width="7.5%">
								Rej. Value<sup>Rs</sup>
							</th>
							<th>
								Total Value<sup>Rs</sup>
							</th>				
						 </tr>							
					<?php	
						}
						if($repType == 'All' || $repType == 'Key-wise') {
					?>	
                    <tr style="font-size:8px;" height="15px">
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
						<td>
                            <?php print ($planRef)?$planRef:'&nbsp;'; ?>
                        </td>
                        <td>
                            <?php print ($particulars[$p]['recvdate'])?$particulars[$p]['recvdate']:'&nbsp;'; ?>
                        </td>
						<?php if($procType == 'All') {?>		
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($actualLifts, 0):'&nbsp;'; ?>
                        </td>
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($moldCost, 0):'&nbsp;'; ?>
                        </td>
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($trimrecvqty, 0):'&nbsp;'; ?>
                        </td>	
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($trimCost, 0):'&nbsp;'; ?>
                        </td>
						<?php } else if ($procType == 'Moulding') { ?>
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($actualLifts, 0):'&nbsp;'; ?>
                        </td>
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($moldCost, 0):'&nbsp;'; ?>
                        </td>		
						<?php } else if ($procType == 'Trimming') { ?>
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($trimrecvqty, 0):'&nbsp;'; ?>
                        </td>	
                        <td class="content_right">
                            <?php print ($planRef)?@number_format($trimCost, 0):'&nbsp;'; ?>
                        </td>
						<?php } ?>						
                        <td class="content_right">
                            <?php print (($planRef)?'Not Insp.':($inspectqty)?@number_format(($rejectqty/$inspectqty)*100, 2):'&nbsp;'); ?>
                        </td>	
                        <td class="content_right">
                            <?php print (($planRef)?'Not Insp.':($rejectval)?@number_format($rejectval, 0):'&nbsp;'); ?>
                        </td>
                        <td class="content_right">
                            <?php print (($moldCost + $trimCost) > 0)?@number_format($moldCost + $trimCost,0):'&nbsp;'; ?>
                        </td>							
                    </tr>
                    <?php
					}
				}
				if($repType !='Key-wise' && $repType !='Vendor-wise')
				{
			?>
			<tr height="20px" style="font-size:8px;">
			<?php 

				$compNo++;
				if($repType =='Summary') { ?>
				<td>
					<?php print $compNo; ?>
				</td>
				<?php } ?>
				<td colspan ="<?php print (($repType == 'Summary')?'2':'3'); ?>" class="content_center content_bold" >
					<?php print $currCmpdName. " (".(($procType != 'Trimming')?(($tglifts)?@number_format($tgmodcost/$tglifts, 3):'0.000'):"").(($procType == 'All')?":":"") .(($procType != 'Moulding')?(($tgtrimqty)?@number_format($tgtrimcost/$tgtrimqty, 3):'0.000'):"").")"; ?>
				</td>			
				<?php if($procType == 'All') {?>		
				<td class="content_bold content_right">
					<?php print @number_format($tglifts, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tgmodcost, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tgtrimqty, 0); ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($tgtrimcost, 0); ?>
				</td>
				<?php } else if ($procType == 'Moulding') { ?>
				<td class="content_bold content_right">
					<?php print @number_format($tglifts, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tgmodcost, 0); ?>
				</td>	
				<?php } else if ($procType == 'Trimming') { ?>
				<td class="content_bold content_right">
					<?php print @number_format($tgtrimqty, 0); ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($tgtrimcost, 0); ?>
				</td>
				<?php } ?>	
				<td class="content_bold content_right">
					<?php print ($tginspqty)?@number_format(($tgrejqty/$tginspqty)*100, 2):'0.00'; ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($tgrejval, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tgqty,0); ?>
				</td>							
			</tr>	
			<?php }	?>
            <tr style="font-size:10px;" height="20px">
				<?php if($repType =='Vendor-wise') { ?>
				<td>
					<?php print $operCount + 1; ?>
				</td>
				<?php } ?>
				<td colspan ="<?php print (($repType == 'Vendor-wise')?'2':'3'); ?>" class="content_center content_bold" >
					<?php print (($repType !='Vendor-wise')?"Total": $operator)." (".(($procType != 'Trimming')?(($tlifts)?@number_format($tmodcost/$tlifts, 3):'0.000'):"").(($procType == 'All')?":":"") .(($procType != 'Moulding')?(($ttrimqty)?@number_format($ttrimcost/$ttrimqty, 3):'0.000'):"").")"; ?>
				</td>					
				<?php if($procType == 'All') {?>		
				<td class="content_bold content_right">
					<?php print @number_format($tlifts, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tmodcost, 2); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($ttrimqty, 0); ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($ttrimcost, 2); ?>
				</td>
				<?php } else if ($procType == 'Moulding') { ?>
				<td class="content_bold content_right">
					<?php print @number_format($tlifts, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tmodcost, 2); ?>
				</td>
				<?php } else if ($procType == 'Trimming') { ?>
				<td class="content_bold content_right">
					<?php print @number_format($ttrimqty, 0); ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($ttrimcost, 0); ?>
				</td>
				<?php } ?>
				<td class="content_bold content_right">
					<?php print ($tinspqty)?@number_format(($trejqty/$tinspqty)*100, 2):'0.00'; ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($trejval, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tqty,0); ?>
				</td>										
           </tr>
		   <?php if ($repType !='Vendor-wise') { ?>
           <tr height="40px">
				<td colspan="<?php print(($procType == 'All')?"4":"3");?>" style="padding:0px;" valign="top">
					<b>Remarks</b><br/>&nbsp;
				</td>
				<td colspan="<?php print(($procType == 'All')?"3":"2");?>"valign="top">
					<b>Reviewed By</b>
				</td>				
				<td colspan="3" valign="top">
					<b>Approved By</b>
				</td>
			</tr>			 
        </table>
			<?php 
			if($operCount+1<count($operators))
				echo "<div class='page_break'><br /></div>";
			}
		}
		if ($repType =='Vendor-wise') {
		?>
            <tr style="font-size:12px;" height="25px">
				<td colspan ="3" class="content_center content_bold" >
					Grand Total <?php print "(".(($procType != 'Trimming')?(($totlifts)?@number_format($totmodcost/$totlifts, 2):'0.00'):"").(($procType == 'All')?":":"") .(($procType != 'Moulding')?(($tottrimqty)?@number_format($tottrimcost/$tottrimqty, 2):'0.00'):"").")";?>
				</td>					
				<?php if($procType == 'All') {?>		
				<td class="content_bold content_right">
					<?php print @number_format($totlifts, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($totmodcost, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($tottrimqty, 0); ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($tottrimcost, 0); ?>
				</td>
				<?php } else if ($procType == 'Moulding') { ?>
				<td class="content_bold content_right">
					<?php print @number_format($totlifts, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($totmodcost, 0); ?>
				</td>
				<?php } else if ($procType == 'Trimming') { ?>
				<td class="content_bold content_right">
					<?php print @number_format($tottrimqty, 0); ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($tottrimcost, 0); ?>
				</td>
				<?php } ?>
				<td class="content_bold content_right">
					<?php print ($tinspqty)?@number_format(($totrejqty/$totinspqty)*100, 2):'0.00'; ?>
				</td>	
				<td class="content_bold content_right">
					<?php print @number_format($totrejval, 0); ?>
				</td>
				<td class="content_bold content_right">
					<?php print @number_format($totqty,0); ?>
				</td>										
           </tr>		
		   <tr height="40px">
				<td colspan="<?php print(($procType == 'All')?"4":"3");?>" style="padding:0px;" valign="top">
					<b>Remarks</b><br/>&nbsp;
				</td>
				<td colspan="<?php print(($procType == 'All')?"3":"2");?>"valign="top">
					<b>Reviewed By</b>
				</td>				
				<td colspan="3" valign="top">
					<b>Approved By</b>
				</td>
			</tr>			 
		</table>
		<?php } ?>
    </body>
</html>