<?php

//Job to send daily mixing data
	if($_REQUEST["type"] == "RUNJOB") 
	{
		global $mgmt_grp_email,$grn_customers,$company_abbrv,$cpd_grp_email;
		$today					=	date("Y-m-d");		
		$curMonth				=	date("F Y");
		$thisMon1stday			=	date("Y-m-d", mktime(0, 0, 0, date("m")  , 1, date("Y")));	
		$lastMonToday			=	date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")));
		$lastMon1stday			=	date("Y-m-d", mktime(0, 0, 0, date("m")-1 , 1, date("Y")));
		$output					=	'<html><body><table><tr><th width="10%">&nbsp;</th><th width="20%">Today</th><th width="20%">Upto Date</th><th width="20%"> Upto Date Last Month</th><th width="20%">Schedule</th><th>Completion %</th></tr>';
		//Compound Planning Data
		$output					.=	'<tr><th colspan="6">Batch Planning Data(Kg(No))</th></tr>';
		$cusGroups				=	$grn_customers;
        $cusGroups[]			=	'';
        $schValArr       		=   array();
		$schPolyValArr     		=   array();
		$polymerData			=	@getMySQLData("select polyName from tbl_polymer_order order by dispOrder");
		$cpdPolymers 			= 	array();
		foreach($polymerData['data'] as $poly){
			array_push($cpdPolymers,$poly['polyName']);	
		}
		array_push($cpdPolymers,"");
		for ($grpCount = 0;$grpCount < count($cusGroups);$grpCount++)
		{
			for($polyCount = 0;$polyCount < count($cpdPolymers) ;$polyCount++)
			{
				$cusGroup				=	$cusGroups[$grpCount];
				$polymer				=	$cpdPolymers[$polyCount];
				if($cusGroup	==	"")
				{
					$polyCount	=	count($cpdPolymers);
					$polymer	=	"";
				}
				$planData				=	@getMySQLData("select sum(sumPlanned) as sumplanned,count(*) as batches
															from tbl_mixing tm 
																inner join tbl_compound tc on tc.cpdId = tm.cpdId
																inner join (select batid, sum(planQty) as sumPlanned from tbl_invoice_mixplan_items group by batid) tmp on tm.batid = tmp.batid 
															where tm.status > 0 and batDate = '$today' ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):""));
				$todaysPlan				=	($planData['data'][0]['sumplanned'])?number_format($planData['data'][0]['sumplanned'])."(".$planData['data'][0]['batches'].")":"0(0)";
				$planData				=	@getMySQLData("select sum(sumPlanned) as sumplanned,count(*) as batches
															from tbl_mixing tm
																inner join tbl_compound tc on tc.cpdId = tm.cpdId
																inner join (select batid, sum(planQty) as sumPlanned from tbl_invoice_mixplan_items group by batid) tmp on tm.batid = tmp.batid 
															where tm.status > 0 and batDate between '$thisMon1stday' and '$today' ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):""));
				$planUptodateVal		=	$planData['data'][0]['sumplanned'];
				$planUptodate			=	($planUptodateVal)?number_format($planUptodateVal)."(".$planData['data'][0]['batches'].")":"0(0)";			
				$planData				=	@getMySQLData("select sum(sumPlanned) as sumplanned,count(*) as batches
															from tbl_mixing tm
																inner join tbl_compound tc on tc.cpdId = tm.cpdId
																inner join (select batid, sum(planQty) as sumPlanned from tbl_invoice_mixplan_items group by batid) tmp on tm.batid = tmp.batid 
															where tm.status > 0 and batDate  between '$lastMon1stday' and '$lastMonToday' ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):""));
				$planUptodateLastMon	=	($planData['data'][0]['sumplanned'])?number_format($planData['data'][0]['sumplanned'])."(".$planData['data'][0]['batches'].")":"0(0)";
				
				if($company_abbrv != $cusGroup && $cusGroup != "")
				{
					$scheduleData		=	@getMySQLData("select sum(schQty) as schvalue 
															from (select * from 
																	(select cpdId_cmpdId, cusId, schQty 
																		from tbl_scheduling  
																	where schOrder='compound' and schMonth='$curMonth' and status>0 
																	order by entry_on desc)tbl1  group by cusId,cpdId_cmpdId )  ts
																".(($cusGroup != '')?" INNER JOIN tbl_customer tc ON ts.cusId = tc.cusId and tc.status = 1 and upper(tc.cusGroup) = '$cusGroup'":"").
																(($polymer != '')?" INNER JOIN tbl_compound tcpd ON ts.cpdId_cmpdId = tcpd.cpdId and tcpd.status = 1 and tcpd.cpdPolymer = '$polymer'":""));
				}
				else if($company_abbrv == $cusGroup)
				{
					$scheduleData		=	@getMySQLData("select sum(schQty * cmpdBlankWgt/1000) as schvalue 
															from (select * from 
																	(select cpdId_cmpdId, schQty,cusId 
																		from tbl_scheduling  
																	where schOrder='component' and schMonth='$curMonth' and status>0 
																	order by entry_on desc)tbl1  group by cpdId_cmpdId,cusId )  ts 
																INNER JOIN tbl_component  tc  ON tc.cmpdId = ts.cpdId_cmpdId and tc.status != 0 
																".(($polymer != '')?" INNER JOIN tbl_compound tcpd ON tc.cmpdCpdId = tcpd.cpdId and tcpd.status = 1 and tcpd.cpdPolymer = '$polymer'":""));
				}
				if($polymer != "")
				{
					$schPolyValArr[$cusGroup][$polymer]		=	($scheduleData['data'][0]['schvalue'])?$scheduleData['data'][0]['schvalue']:0.00;
					$scheduleValue          				=   $schPolyValArr[$cusGroup][$polymer];
					if(!($todaysPlan == "0(0)" && $planUptodate == "0(0)" && $scheduleValue == 0))
					{
						$output					.=	"<tr style='font-size:12px'><td>".$polymer."</td>" ;		
						$output					.=	"<td align='right'>$todaysPlan</td>";
						$output					.=	"<td align='right'>$planUptodate</td>";
						$output					.=	"<td align='right'>$planUptodateLastMon</td>";
						$output					.=	"<td align='right'>".number_format($scheduleValue)."</td>";
						$output					.=	"<td align='right'>".(($scheduleValue != 0)?number_format(round(100 * $planUptodateVal/$scheduleValue)):"N.A.")."</td></tr>";	
					}
				}
				else
				{
					if($grpCount < count($cusGroups) - 1)
					{
						$schValArr[$grpCount]	=	($scheduleData['data'][0]['schvalue'])?$scheduleData['data'][0]['schvalue']:0.00;
					}
					else
					{
						$schValArr[$grpCount]	=	array_sum($schValArr);
					}
					$scheduleValue          =   $schValArr[$grpCount];
					$output					.=	"<tr style='".(($cusGroup != '')?"font-size:15px;font-weight:bold;":"font-size:18px;font-weight:bold;")."'><td>".(($cusGroup != '')?$cusGroup:"Total")."</td>" ;		
					$output					.=	"<td align='right'>$todaysPlan</td>";
					$output					.=	"<td align='right'>$planUptodate</td>";
					$output					.=	"<td align='right'>$planUptodateLastMon</td>";
					$output					.=	"<td align='right'>".number_format($scheduleValue)."</td>";
					$output					.=	"<td align='right'>".(($scheduleValue != 0)?number_format(round(100 * $planUptodateVal/$scheduleValue)):"N.A.")."</td></tr>";		
				}
			}
		}
		// Compound Receipt Data
		$output					.=	'<tr><th colspan="6">Compound Receipt Data (Kg(No))</th></tr>';	
		for ($grpCount = 0;$grpCount < count($cusGroups);$grpCount++)
		{
			for($polyCount = 0;$polyCount < count($cpdPolymers) ;$polyCount++)
			{		
				$cusGroup				=	$cusGroups[$grpCount];	
				$polymer				=	$cpdPolymers[$polyCount];
				if($cusGroup	==	"")
				{
					$polyCount	=	count($cpdPolymers);
					$polymer	=	"";
				}
				$receiptData			=	@getMySQLData("select count(batId)  as batches, sum(batRecvWgt) as batrecvwgt 
															from (select mxrv.batId, mxrv.batRecvWgt 
																	from tbl_mixing_recv mxrv
																		inner join tbl_mixing mx on mxrv.batId = mx.batId    
																		inner join tbl_compound tc on tc.cpdId = mx.cpdId
																	where mxrv.mixRecvDate = '$today' and mx.status > 2 ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")."
																union all
																select batId, masterBatchWgt as batRecvWgt 
																	from tbl_mixing tm
																		inner join tbl_compound tc on tc.cpdId = tm.cpdId
																	where masterBatchWgt > 0 and tm.status > 1 and batDate = '$today' and batFinalDate = '0000-00-00' ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")." ) tbl1");
				$todaysReceipts			=	($receiptData['data'][0]['batrecvwgt'])?number_format($receiptData['data'][0]['batrecvwgt'])."(".$receiptData['data'][0]['batches'].")":"0(0)";
				$receiptData			=	@getMySQLData("select count(batId)  as batches, sum(batRecvWgt) as batrecvwgt 
															from ( select mxrv.batId, mxrv.batRecvWgt 
																	from tbl_mixing_recv mxrv
																		inner join tbl_mixing mx on mxrv.batId = mx.batId  
																		inner join tbl_compound tc on tc.cpdId = mx.cpdId
																	where mxrv.mixRecvDate between '$thisMon1stday' and '$today' and mx.status > 2 ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")."
																union all
																select batId, masterBatchWgt as batRecvWgt 
																	from tbl_mixing tm
																		inner join tbl_compound tc on tc.cpdId = tm.cpdId
																where masterBatchWgt > 0 and tm.status > 1 and batDate between '$thisMon1stday' and '$today' and batFinalDate = '0000-00-00' ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")." ) tbl1");
				$receiptsUptodateVal	=	$receiptData['data'][0]['batrecvwgt'];
				$receiptsUptodate		=	($receiptsUptodateVal)?number_format($receiptsUptodateVal)."(".$receiptData['data'][0]['batches'].")":"0(0)";			
				$receiptData			=	@getMySQLData("select count(batId)  as batches, sum(batRecvWgt) as batrecvwgt 
															from (select mxrv.batId, mxrv.batRecvWgt 
																	from tbl_mixing_recv mxrv
																		inner join tbl_mixing mx on mxrv.batId = mx.batId 
																		inner join tbl_compound tc on tc.cpdId = mx.cpdId
																	where mxrv.mixRecvDate between '$lastMon1stday' and '$lastMonToday' and mx.status > 2 ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")." 
																union all
																select batId, masterBatchWgt as batRecvWgt 
																	from tbl_mixing tm
																		inner join tbl_compound tc on tc.cpdId = tm.cpdId
																	where masterBatchWgt > 0 and tm.status > 1 and batDate between '$lastMon1stday' and '$lastMonToday' and batFinalDate = '0000-00-00' ".((isset($cusGroup) && $cusGroup != "")?" and customer='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")." ) tbl1");
				$receiptsUptodateLastMon=	($receiptData['data'][0]['batrecvwgt'])?number_format($receiptData['data'][0]['batrecvwgt'])."(".$receiptData['data'][0]['batches'].")":"0(0)";
				if($polymer != "")
				{
					$scheduleValue          	=   $schPolyValArr[$cusGroup][$polymer];
					if(!($todaysReceipts == "0(0)" && $receiptsUptodate == "0(0)" && $scheduleValue == 0))
					{
						$output					.=	"<tr style='font-size:12px'><td>".$polymer."</td>" ;		
						$output					.=	"<td align='right'>$todaysReceipts</td>";
						$output					.=	"<td align='right'>$receiptsUptodate</td>";
						$output					.=	"<td align='right'>$receiptsUptodateLastMon</td>";
						$output					.=	"<td align='right'>".number_format($scheduleValue)."</td>";
						$output					.=	"<td align='right'>".(($scheduleValue != 0)?number_format(round(100 * $receiptsUptodateVal/$scheduleValue)):"N.A.")."</td></tr>";	
					}
				}
				else
				{				
					$scheduleValue          =   $schValArr[$grpCount];
					$output					.=	"<tr style='".(($cusGroup != '')?"font-size:15px;font-weight:bold;":"font-size:18px;font-weight:bold;")."'><td>".(($cusGroup != '')?$cusGroup:"Total")."</td>" ;		
					$output					.=	"<td align='right'>$todaysReceipts</td>";
					$output					.=	"<td align='right'>$receiptsUptodate</td>";
					$output					.=	"<td align='right'>$receiptsUptodateLastMon</td>";
					$output					.=	"<td align='right'>".number_format($scheduleValue)."</td>";
					$output					.=	"<td align='right'>".(($scheduleValue != 0)?number_format(round(100 * $receiptsUptodateVal/$scheduleValue)):"N.A.")."</td></tr>";	
				}
			}
        }
        //despatch data
        $output					.=	'<tr><th colspan="6">Compound Despatch Data (Kg(No))</th></tr>';	
		for ($grpCount = 0;$grpCount < count($cusGroups);$grpCount++)
		{
			for($polyCount = 0;$polyCount < count($cpdPolymers) ;$polyCount++)
			{
				$cusGroup				=	$cusGroups[$grpCount];
				$polymer				=	$cpdPolymers[$polyCount];
				if($cusGroup	==	"")
				{
					$polyCount	=	count($cpdPolymers);
					$polymer	=	"";
				}					
				$dispatchData			=	@getMySQLData("select sum(batches)  as batches, sum(dcQty) as dcQty from 
															(select count(*) as batches, sum(dcQty) as dcQty from tbl_invoice_dc dc 
																	inner join tbl_invoice_dc_items dci on dc.dcId = dci.dcId 
																	inner join tbl_compound tc on tc.cpdId = dci.dcCode
																	inner join tbl_customer c on dc.dcCusId = c.cusId 
																where dc.dcDate = '$today' and dc.status > 0 and dc.dcType = 'cpd' ".((isset($cusGroup) && $cusGroup != "")?" and cusGroup='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")."
															union all
															select count(*) as batches, sum(invQty) as dcQty from tbl_invoice_sales tis 
																inner join tbl_invoice_sales_items tisi on tis.invId = tisi.invId 
																inner join tbl_compound tc on tc.cpdId = tisi.invCode
																inner join tbl_customer c on tis.invCusId = c.cusId   
															where tis.invDate = '$today' and tis.status > 0  and  tisi.invType = 'cpd' ".((isset($cusGroup) && $cusGroup != "")?" and cusGroup='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"").") tbl2 ") ;       

				$todaysDispatch			=	($dispatchData['data'][0]['dcQty'])?number_format($dispatchData['data'][0]['dcQty'])."(".$dispatchData['data'][0]['batches'].")":"0(0)";
				$dispatchData			=	@getMySQLData("select sum(batches)  as batches, sum(dcQty) as dcQty from
															(select count(*) as batches, sum(dcQty) as dcQty from tbl_invoice_dc dc 
																inner join tbl_invoice_dc_items dci on dc.dcId = dci.dcId 
																inner join tbl_compound tc on tc.cpdId = dci.dcCode
																inner join tbl_customer c on dc.dcCusId = c.cusId  
															 where dc.dcDate between '$thisMon1stday' and '$today' and dc.status > 0 and dc.dcType = 'cpd' ".((isset($cusGroup) && $cusGroup != "")?" and cusGroup='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")."
															union all
															select count(*) as batches, sum(invQty) as dcQty from tbl_invoice_sales tis 
																inner join tbl_invoice_sales_items tisi on tis.invId = tisi.invId 
																inner join tbl_compound tc on tc.cpdId = tisi.invCode
																inner join tbl_customer c on tis.invCusId = c.cusId 
															where tis.invDate between '$thisMon1stday' and '$today' and tis.status > 0  and  tisi.invType = 'cpd' ".((isset($cusGroup) && $cusGroup != "")?" and cusGroup='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"").") tbl2 ");
				
				$dispatchUptodateVal	=	$dispatchData['data'][0]['dcQty'];
				$dispatchUptodate		=	($dispatchUptodateVal)?number_format($dispatchUptodateVal)."(".$dispatchData['data'][0]['batches'].")":"0(0)";			
				$dispatchData			=	@getMySQLData("select sum(batches)  as batches, sum(dcQty) as dcQty from
															(select count(*) as batches, sum(dcQty) as dcQty from tbl_invoice_dc dc 
																inner join tbl_invoice_dc_items dci on dc.dcId = dci.dcId 
																inner join tbl_compound tc on tc.cpdId = dci.dcCode
																inner join tbl_customer c on dc.dcCusId = c.cusId 
															where dc.dcDate between '$lastMon1stday' and '$lastMonToday' and dc.status > 0 and dc.dcType = 'cpd' ".((isset($cusGroup) && $cusGroup != "")?" and cusGroup='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"")."
															union all
															select count(*) as batches, sum(invQty) as dcQty from tbl_invoice_sales tis 
																inner join tbl_invoice_sales_items tisi on tis.invId = tisi.invId 
																inner join tbl_compound tc on tc.cpdId = tisi.invCode
																inner join tbl_customer c on tis.invCusId = c.cusId 
															where tis.invDate between '$lastMon1stday' and '$lastMonToday' and tis.status > 0  and  tisi.invType = 'cpd' ".((isset($cusGroup) && $cusGroup != "")?" and cusGroup='$cusGroup' ".((isset($polymer) && $polymer != "")?" and  tc.cpdPolymer = '$polymer'":""):"").") tbl2 ");
				
				$dispatchUptodateLastMon=	($dispatchData['data'][0]['dcQty'])?number_format($dispatchData['data'][0]['dcQty'])."(".$dispatchData['data'][0]['batches'].")":"0(0)";
				if($polymer != "")
				{
					$scheduleValue          	=   $schPolyValArr[$cusGroup][$polymer];
					if(!($todaysDispatch == "0(0)" && $dispatchUptodate == "0(0)" && $scheduleValue == 0))
					{
						$output					.=	"<tr style='font-size:12px'><td>".$polymer."</td>" ;		
						$output					.=	"<td align='right'>$todaysDispatch</td>";
						$output					.=	"<td align='right'>$dispatchUptodate</td>";
						$output					.=	"<td align='right'>$dispatchUptodateLastMon</td>";
						$output					.=	"<td align='right'>".number_format($scheduleValue)."</td>";
						$output					.=	"<td align='right'>".(($scheduleValue != 0)?number_format(round(100 * $dispatchUptodateVal/$scheduleValue)):"N.A.")."</td></tr>";	
					}
				}
				else
				{
					$scheduleValue          =   $schValArr[$grpCount];
					$output					.=	"<tr style='".(($cusGroup != '')?"font-size:15px;font-weight:bold;":"font-size:18px;font-weight:bold;")."'><td>".(($cusGroup != '')?$cusGroup:"Total")."</td>" ;		
					$output					.=	"<td align='right'>$todaysDispatch</td>";
					$output					.=	"<td align='right'>$dispatchUptodate</td>";
					$output					.=	"<td align='right'>$dispatchUptodateLastMon</td>";
					$output					.=	"<td align='right'>".number_format($scheduleValue)."</td>";
					$output					.=	"<td align='right'>".(($scheduleValue != 0)?number_format(round(100 * $dispatchUptodateVal/$scheduleValue)):"N.A.")."</td></tr>";	
				}
			}
        }
        
		$output					.=	"</table></body></html>";
		$pstatus 				= 	sendEmail($mgmt_grp_email,$cpd_grp_email, " Mixing Data for ".date("d-m-Y"),$output,"");
		exit();
	} 				

	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$invoice_ids	=	split(",", $invoice_id);
	
	$invIssArr		=	@getMySQLData(" select batId, DATE_FORMAT(batDate, '%d-%b-%Y') as batdate, cpdId, cpdName, batRecvWgt, cpdPolymer,cpdUOM, mastTime, mastTimeMin, mastTimeMax, 
										mastTemp, mastTempMin, mastTempMax, mastPres, mastPresMin, mastPresMax, 
										blendTime, blendTimeMin, blendTimeMax, blendTemp, blendTempMin, blendTempMax, 
										blendPres, blendPresMin, blendPresMax, kneadTime, kneadTimeMin, kneadTimeMax, 
										kneadTemp, kneadTempMin, kneadTempMax, kneadPres, kneadPresMin, kneadPresMax, 
										millRollTime, millRollTimeMin, millRollTimeMax, millRollTemp, millRollTempMin, millRollTempMax,
										mixFinalTime, mixFinalTimeMin, mixFinalTimeMax, mixFinalTemp, mixFinalTempMin, mixFinalTempMax, 
										mixSheetTime, mixSheetTimeMin, mixSheetTimeMax, mixSheetTemp, mixSheetTempMin, mixSheetTempMax 
										from tbl_invoice_mixplan where batId='".$invoice_id."' ");
	
	$batId			=	$invIssArr['data'][0]['batId'];
	$batDate		=	$invIssArr['data'][0]['batdate'];
	$cpdId			=	$invIssArr['data'][0]['cpdId'];
	$cpdName		=	$invIssArr['data'][0]['cpdName'];
	$cpdPolymer		=	$invIssArr['data'][0]['cpdPolymer'];
	$uom			=	$invIssArr['data'][0]['cpdUOM'];
	$mastTime		=	$invIssArr['data'][0]['mastTime']; 
	$mastTimeMin	=	$invIssArr['data'][0]['mastTimeMin'];
	$mastTimeMax	=	$invIssArr['data'][0]['mastTimeMax'];
	$mastTemp		=	$invIssArr['data'][0]['mastTemp'];
	$mastTempMin	=	$invIssArr['data'][0]['mastTempMin']; 
	$mastTempMax	=	$invIssArr['data'][0]['mastTempMax'];
	$mastPres 		=	$invIssArr['data'][0]['mastPres'];
	$mastPresMin 	=	$invIssArr['data'][0]['mastPresMin'];
	$mastPresMax	=	$invIssArr['data'][0]['mastPresMax'];
	$blendTime		=	$invIssArr['data'][0]['blendTime'];
	$blendTimeMin	=	$invIssArr['data'][0]['blendTimeMin'];
	$blendTimeMax	=	$invIssArr['data'][0]['blendTimeMax'];
	$blendTemp		=	$invIssArr['data'][0]['blendTemp']; 
	$blendTempMin	=	$invIssArr['data'][0]['blendTempMin'];
	$blendTempMax	=	$invIssArr['data'][0]['blendTempMax']; 
	$blendPres		=	$invIssArr['data'][0]['blendPres'];
	$blendPresMin	=	$invIssArr['data'][0]['blendPresMin']; 
	$blendPresMax	=	$invIssArr['data'][0]['blendPresMax']; 
	$kneadTime		=	$invIssArr['data'][0]['kneadTime']; 
	$kneadTimeMin	=	$invIssArr['data'][0]['kneadTimeMin']; 
	$kneadTimeMax	=	$invIssArr['data'][0]['kneadTimeMax'];
	$kneadTemp		=	$invIssArr['data'][0]['kneadTemp'];
	$kneadTempMin	=	$invIssArr['data'][0]['kneadTempMin'];
	$kneadTempMax	=	$invIssArr['data'][0]['kneadTempMax'];
	$kneadPres		=	$invIssArr['data'][0]['kneadPres'];
	$kneadPresMin	=	$invIssArr['data'][0]['kneadPresMin'];
	$kneadPresMax 	=	$invIssArr['data'][0]['kneadPresMax'];
	$millRollTime	=	$invIssArr['data'][0]['millRollTime'];
	$millRollTimeMin=	$invIssArr['data'][0]['millRollTimeMin'];
	$millRollTimeMax=	$invIssArr['data'][0]['millRollTimeMax'];
	$millRollTemp	=	$invIssArr['data'][0]['millRollTemp'];
	$millRollTempMin=	$invIssArr['data'][0]['millRollTempMin'];
	$millRollTempMax=	$invIssArr['data'][0]['millRollTempMax'];
	$mixFinalTime	=	$invIssArr['data'][0]['mixFinalTime'];
	$mixFinalTimeMin=	$invIssArr['data'][0]['mixFinalTimeMin'];
	$mixFinalTimeMax=	$invIssArr['data'][0]['mixFinalTimeMax'];
	$mixFinalTemp	=	$invIssArr['data'][0]['mixFinalTemp'];
	$mixFinalTempMin=	$invIssArr['data'][0]['mixFinalTempMin'];
	$mixFinalTempMax=	$invIssArr['data'][0]['mixFinalTempMax'];
	$mixSheetTime	=	$invIssArr['data'][0]['mixSheetTime'];
	$mixSheetTimeMin=	$invIssArr['data'][0]['mixSheetTimeMin'];
	$mixSheetTimeMax=	$invIssArr['data'][0]['mixSheetTimeMax'];
	$mixSheetTemp	=	$invIssArr['data'][0]['mixSheetTemp'];
	$mixSheetTempMin=	$invIssArr['data'][0]['mixSheetTempMin'];
	$mixSheetTempMax=	$invIssArr['data'][0]['mixSheetTempMax'];	
	$batRecvWgt	= 		$invIssArr['data'][0]['batRecvWgt'];
	
	$sql_mi_grn	=	@getMySQLData("select group_concat(concat(grnId,' (',mixIssQty,')') SEPARATOR', ') as grnRef, sum(mixIssQty)
										 from tbl_invoice_mixrecv_grn where invId='".$invoice_id."' group by ramId" );
	$grn_dtls		=	$sql_mi_grn['data'];
	//$grn_count		=	$sql_mi_grn['count'];

	$sql_mi_rm	=	@getMySQLData("select ramId, rmName, planQty, grade, UOM
										 from tbl_invoice_mixrecv_rm where invId='".$invoice_id."' group by ramId" );
	$rm_dtls		=	$sql_mi_rm['data'];
	//$rm_count		=	$sql_mi_grn['count'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $batId; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
        <br /><br />
    	<table cellpadding="6" cellspacing="0" border="0" id="print_out" >
            <tr>
            	<td colspan="9" width="100px" class="content_center content_bold uppercase" style="font-size:16px;">
                	BATCH CARD
                </td>
            </tr>
            <tr>
                <td colspan="5" valign="top" style="padding:0px;">
                   <table cellpadding="6" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                   	<tr>
                    	<td colspan="2" class="content_bold" style="border-right:0px;width:40%" >
                        	Compound Ref.
                        </td>
                        <td colspan="2" style="border-right:0px;" >
                        	: <?php echo $cpdName; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" class="content_bold" style="border-right:0px;width:40%;border-bottom:0px;" >
                        	Polymer
                        </td>
                        <td colspan="2" style="border-right:0px;border-bottom:0px;" >
                        	: <?php echo $cpdPolymer; ?>
                        </td>
                    </tr>
                   </table>
                </td>
                <td colspan="3"  style="border-right:0px;padding:0px;border-bottom:0px;">
                	<table cellpadding="6" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                Batch Reference
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php echo $batId; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Batch Date
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php echo date("d-M-Y", strtotime($batDate)); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
      		<!--<tr>
            	<td>
                    <table cellpadding="3" cellspacing="0" border="0" id="print_out" >-->
                        <tr>
                            <th width="5%">
                                No
                            </th>
                            <th width="15%">
                                Material
                            </th>
                            <th width="15%">
                                Grade
                            </th>
                            <th width="5%">
                                UoM
                            </th>
                            <th width="10%">
                                Advised
                            </th>
                            <th width="10%">
                                Weighed
                            </th>
                            <th colspan="2">
                                GRN Reference
                            </th>
                        </tr>
                        <?php
                            $totsno		=	15;
                            $sno		=	1;
                            $tqty		=	0;
                            
                            //for($p=0;$p<count($particulars);$p++){
                            for($p=0;$p<$totsno;$p++){
                                $tqty	=	$tqty + $rm_dtls[$p]['planQty'];
								$trec	=	$trec + $grn_dtls[$p]['mixIssQty'];
								?>
                                <tr>
                                    <td align="center">
                                        <?php print ($p+1); ?>
                                    </td>
                                    <td align="left">
                                        <?php print ($rm_dtls[$p]['rmName'])?$rm_dtls[$p]['rmName']:'&nbsp;'; ?>
                                    </td>
                                    <td align="left">
                                        <?php print ($rm_dtls[$p]['grade'])?$rm_dtls[$p]['grade']:'&nbsp;'; ?>
                                    </td>
                                    <td align="center">
                                        <?php print ($rm_dtls[$p]['UOM'])?$rm_dtls[$p]['UOM']:'&nbsp;'; ?>
                                    </td>
                                    <td align="right">
                                        <?php print ($rm_dtls[$p]['planQty'])?$rm_dtls[$p]['planQty']:'&nbsp;'; ?>
                                    </td>
                                    <td class="content_right">
                                        <?php print ($grn_dtls[$p]['mixIssQty'])?$grn_dtls[$p]['mixIssQty']:'&nbsp;'; ?>
                                    </td>
                                    <td colspan="2" style="font-size:9px;" >
                                      	<?php print ($grn_dtls[$p]['grnRef'])?$grn_dtls[$p]['grnRef']:'&nbsp;';?>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>	
                        <tr>
                            <td colspan="4" class="content_center content_bold" >
                                Total
                            </td>
                            <td class="content_bold content_right">
                                <?php print @number_format($tqty, 3); ?>
                            </td>
                            <td class="content_bold content_right">
                                <?php print @number_format($trec, 3); ?>
                            </td>
                            <td colspan="2" class="content_bold">&nbsp;
                                X
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br /><br />
        <div class="page_break">
        <br /><br />
        <table cellpadding="6" cellspacing="0" border="0" id="print_out">
            <tr>
                <th width="5%">
                    No
                </th>
                <th width="20%">
                    Parameter
                </th>
                <th width="7%">
                    UoM
                </th>
                <th width="10%">
                    Spec.
                </th>
                <th width="10%">
                    Min.
                </th>
                <th width="10%">
                    Max.
                </th>
                <th width="10%" >
                    Obs.
                </th>
                <th>
                	Remarks
                </th>
            </tr>
            <tr>
            	<td>1</td>
                <td>Mastication Time</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mastTime; ?></td>
                <td align="right"><?php echo $mastTimeMin; ?></td>
                <td align="right"><?php echo $mastTimeMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>2</td>
                <td>Mastication Temp</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mastTemp; ?></td>
                <td align="right"><?php echo $mastTempMin; ?></td>
                <td align="right"><?php echo $mastTempMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>3</td>
                <td>Mastication Pres</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mastPres; ?></td>
                <td align="right"><?php echo $mastPresMin; ?></td>
                <td align="right"><?php echo $mastPresMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>4</td>
                <td>Blending Time</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $blendTime; ?></td>
                <td align="right"><?php echo $blendTimeMin; ?></td>
                <td align="right"><?php echo $blendTimeMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>5</td>
                <td>Blending Temp</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $blendTemp; ?></td>
                <td align="right"><?php echo $blendTempMin; ?></td>
                <td align="right"><?php echo $blendTempMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>6</td>
                <td>Blending Pres</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $blendPres; ?></td>
                <td align="right"><?php echo $blendPresMin; ?></td>
                <td align="right"><?php echo $blendPresMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>7</td>
                <td>Kneading Time</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $kneadTime; ?></td>
                <td align="right"><?php echo $kneadTimeMin; ?></td>
                <td align="right"><?php echo $kneadTimeMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>8</td>
                <td>Kneading Temp</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $kneadTemp; ?></td>
                <td align="right"><?php echo $kneadTempMin; ?></td>
                <td align="right"><?php echo $kneadTempMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>9</td>
                <td>Kneading Pres</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $kneadPres; ?></td>
                <td align="right"><?php echo $kneadPresMin; ?></td>
                <td align="right"><?php echo $kneadPresMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>10</td>
                <td>Mill Rolling Time</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $millRollTime; ?></td>
                <td align="right"><?php echo $millRollTimeMin; ?></td>
                <td align="right"><?php echo $millRollTimeMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>11</td>
                <td>Mill Rolling Temp</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $millRollTemp; ?></td>
                <td align="right"><?php echo $millRollTempMin; ?></td>
                <td align="right"><?php echo $millRollTempMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>12</td>
                <td>Mix Finaling Time</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mixFinalTime; ?></td>
                <td align="right"><?php echo $mixFinalTimeMin; ?></td>
                <td align="right"><?php echo $mixFinalTimeMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>13</td>
                <td>Mix Finaling Temp</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mixFinalTemp; ?></td>
                <td align="right"><?php echo $mixFinalTempMin; ?></td>
                <td align="right"><?php echo $mixFinalTempMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>14</td>
                <td>Mix Sheeting Time</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mixSheetTime; ?></td>
                <td align="right"><?php echo $mixSheetTimeMin; ?></td>
                <td align="right"><?php echo $mixSheetTimeMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>15</td>
                <td>Mix Sheeting Temp</td>
                <td align="center"><?php echo $uom; ?></td>
                <td align="right"><?php echo $mixSheetTemp; ?></td>
                <td align="right"><?php echo $mixSheetTempMin; ?></td>
                <td align="right"><?php echo $mixSheetTempMax; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td><b>Output Weight:</b></td>
                <td colspan="3" class="content_bold" style="text-align:right"><?php echo $batRecvWgt; ?> Kg</td>
                <td colspan="2"><b>Kneaded By:</b></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td><b>Yield:</b></td>
                <td colspan="3" class="content_bold" style="text-align:right"><?php echo number_format((($batRecvWgt/$trec) * 100), 2); ?> %</td>
                <td colspan="2"><b>Finalled By:</b></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        </div>
    </body>
</html>