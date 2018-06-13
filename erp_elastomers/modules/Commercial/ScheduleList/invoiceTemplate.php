<?php
	global $mgmt_grp_email,$company_abbrv,$vendor_grp_email,$numdays_tosend,$schVsDespGroup,$despValidDays;
	$invoice_id		=	trim((ISO_IS_REWRITE)?$_VAR['invID']:$_GET['invID']);
	if($invoice_id != null && $invoice_id != "")
	{
		$pos 			= 	strpos($invoice_id, "-",1);
		if($pos > 1)
			$invoice_id		=	split("-",$invoice_id);
	}
	//Job to send daily sales data
	if($_REQUEST["type"] == "RUNJOB") 
	{			
		if($invoice_id == "reports" ) 
		{
			$lastMonth	=	date("F Y", strtotime(date('Y-m')." -1 month"));
			// close & send the result to user & then send email							
			closeConnForAsyncProcess("");	
			foreach($schVsDespGroup as $key=>$value)
			{			
				$reportType	=	array("Summary","Detailed");
				for($i=0;$i<count($reportType);$i++)
				{
					$aEmail 	= 	new AsyncCreatePDFAndEmail("Sales/ScheduleVsDespatch",$key."-".$reportType[$i], $mgmt_grp_email,"",$value." Schedule Vs Despatch ".$reportType[$i]." Report for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the ".$value." Schedule Vs Despatch ".$reportType[$i]." Report for :".$lastMonth,true);									
					$aEmail->start();
				}				
			}			
			reset($schVsDespGroup);
			$key 		= 	key($schVsDespGroup);			
			$aEmail 	= 	new AsyncCreatePDFAndEmail("Sales/ScheduleVsDespatch",$key."-Exception", $mgmt_grp_email,"","Exception Schedule Vs Despatch Detailed Report for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Exception Schedule Vs Despatch Detailed Report for :".$lastMonth,true);									
			$aEmail->start();			
			exit();
		}		
		
		$today					=	date("Y-m-d");
		$curMonth				=	date("F Y");
		$thisMon1stday			=	date("Y-m-d", mktime(0, 0, 0, date("m")  , 1, date("Y")));	
		$lastMonToday			=	date("Y-m-d", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
		$lastMon1stday			=	date("Y-m-d", mktime(0, 0, 0, date("m")-1  , 1, date("Y")));
		$cusGroupSql			=	@getMySQLData("select distinct IF(cusGroup = '', 'OTHERS', upper(cusGroup)) as cusGroup  from tbl_customer where status = 1 and cusGroup != '$company_abbrv' order by cusGroup");
		$cusGroups				=	$cusGroupSql['data'];
		$output					=	'<html><body><table><tr><th width="10%">&nbsp;</th><th width="20%">Today</th><th width="20%">Upto Date This Month</th><th width="20%"> Upto Date Last Month</th><th width="20%">Total Schedule This Month</th><th>Completion percent</th></tr>';
		//Sales Data
		$output					.=	'<tr><th colspan="6">Sales Data(Rs Lakhs)</th></tr>';
		$cusGroups[count($cusGroups)]['cusGroup']	=	'';
		for ($grpCount = 0;$grpCount < count($cusGroups);$grpCount++)
		{
			$cusGroup				=	$cusGroups[$grpCount]['cusGroup'];	
			$salesData				=	@getMySQLData("select sum(invAmt) as totsales from tbl_invoice_sales tic INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId  ".(($cusGroup != '')?" INNER JOIN tbl_customer tc ON tic.invCusId = tc.cusId and tc.status = 1 and upper(tc.cusGroup) = '$cusGroup'":"")." where tic.status = 1 and invDate = '$today'");
			$todaysSale				=	($salesData['data'][0]['totsales'])?$salesData['data'][0]['totsales']:0.00;
			$salesData				=	@getMySQLData("select sum(invAmt) as totsales from tbl_invoice_sales tic INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId  ".(($cusGroup != '')?" INNER JOIN tbl_customer tc ON tic.invCusId = tc.cusId and tc.status = 1 and upper(tc.cusGroup) = '$cusGroup'":"")." where tic.status = 1 and invDate between '$thisMon1stday' and '$today'");
			$salesUptodate			=	($salesData['data'][0]['totsales'])?$salesData['data'][0]['totsales']:0.00;			
			$salesData				=	@getMySQLData("select sum(invAmt) as totsales from tbl_invoice_sales tic INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId  ".(($cusGroup != '')?" INNER JOIN tbl_customer tc ON tic.invCusId = tc.cusId and tc.status = 1 and upper(tc.cusGroup) = '$cusGroup'":"")." where tic.status = 1 and invDate between '$lastMon1stday' and '$lastMonToday'");
			$salesUptodateLastMon	=	($salesData['data'][0]['totsales'])?$salesData['data'][0]['totsales']:0.00;	
			$scheduleData			=	@getMySQLData("select sum(schQty * rate) as schvalue from (select * from (select cpdId_cmpdId, cusId, schQty, rate from tbl_scheduling  where schOrder='component' and schMonth='$curMonth' and status>0 order by entry_on desc)tbl1  group by cusId,cpdId_cmpdId )  ts".(($cusGroup != '')?" INNER JOIN tbl_customer tc ON ts.cusId = tc.cusId and tc.status = 1 and upper(tc.cusGroup) = '$cusGroup'":""));
			$scheduleValue			=	($scheduleData['data'][0]['schvalue'])?$scheduleData['data'][0]['schvalue']:0.00;
			if($todaysSale > 0 || $salesUptodate > 0 || $salesUptodateLastMon > 0 || $scheduleValue > 0 ) 
			{		
				$output					.=	"<tr><td><b>".(($cusGroup != '')?$cusGroup:"Total Sales")."</b></td>" ;		
				$output					.=	"<td align='right'>".number_format($todaysSale)."</td>";
				$output					.=	"<td align='right'>".number_format($salesUptodate/100000,2)."</td>";
				$output					.=	"<td align='right'>".number_format($salesUptodateLastMon/100000,2)."</td>";
				$output					.=	"<td align='right'>".number_format($scheduleValue/100000,2)."</td>";
				$output					.=	"<td align='right'>".(($scheduleValue > 0)?number_format(round(100 * $salesUptodate/$scheduleValue)):"N.A.")."</td></tr>";
			}
			if($cusGroup == '')
			{
				// Tool Data
				$toolData					=	@getMySQLData("select sum(invAmt) as totsales from tbl_invoice_sales tic INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId and tici.invtype='tool' where tic.status = 1 and invDate = '$today'");
				$todaysToolSale				=	($toolData['data'][0]['totsales'])?$toolData['data'][0]['totsales']:0.00;
				$toolData					=	@getMySQLData("select sum(invAmt) as totsales from tbl_invoice_sales tic INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId and tici.invtype='tool' where tic.status = 1 and invDate between '$thisMon1stday' and '$today'");
				$toolSalesUptodate			=	($toolData['data'][0]['totsales'])?$toolData['data'][0]['totsales']:0.00;			
				$toolData					=	@getMySQLData("select sum(invAmt) as totsales from tbl_invoice_sales tic INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId and tici.invtype='tool' where tic.status = 1 and invDate between '$lastMon1stday' and '$lastMonToday'");
				$toolSalesUptodateLastMon	=	($toolData['data'][0]['totsales'])?$toolData['data'][0]['totsales']:0.00;	
				$output						.=	"<tr><td><b>(Tool Sales)</b></td>" ;
				$output						.=	"<td align='right'>".number_format($todaysToolSale)."</td>";
				$output						.=	"<td align='right'>".number_format($toolSalesUptodate/100000,2)."</td>";
				$output						.=	"<td align='right'>".number_format($toolSalesUptodateLastMon/100000,2)."</td>";
				$output						.=	"<td align='right'>N.A.</td>";
				$output						.=	"<td align='right'>N.A.</td></tr>";
				// Insert Data
				$insertData					=	@getMySQLData("select sum(tici.invQty * tr.ramApprovedRate) as insertCost from tbl_invoice_sales tic 
																	INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId 
																	INNER JOIN tbl_component_insert tci on tci.cmpdId=tici.invCode
																	INNER JOIN tbl_rawmaterial tr on tci.ramId=tr.ramId									
																where tic.status = 1 and invDate = '$today'");
				$todaysInsertSale			=	($insertData['data'][0]['insertCost'])?$insertData['data'][0]['insertCost']:0.00;
				$insertData					=	@getMySQLData("select sum(tici.invQty * tr.ramApprovedRate) as insertCost from tbl_invoice_sales tic 
																	INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId 
																	INNER JOIN tbl_component_insert tci on tci.cmpdId=tici.invCode
																	INNER JOIN tbl_rawmaterial tr on tci.ramId=tr.ramId									
																where tic.status = 1 and invDate between '$thisMon1stday' and '$today'");
				$insertSalesUptodate		=	($insertData['data'][0]['insertCost'])?$insertData['data'][0]['insertCost']:0.00;			
				$insertData					=	@getMySQLData("select sum(tici.invQty * tr.ramApprovedRate) as insertCost from tbl_invoice_sales tic 
																	INNER JOIN tbl_invoice_sales_items tici on tici.invId=tic.invId 
																	INNER JOIN tbl_component_insert tci on tci.cmpdId=tici.invCode
																	INNER JOIN tbl_rawmaterial tr on tci.ramId=tr.ramId									
																where tic.status = 1 and invDate between '$lastMon1stday' and '$lastMonToday'");
				$insertSalesUptodateLastMon	=	($insertData['data'][0]['insertCost'])?$insertData['data'][0]['insertCost']:0.00;	
				$output						.=	"<tr><td><b>(Insert Cost)</b></td>" ;
				$output						.=	"<td align='right'>".number_format($todaysInsertSale)."</td>";
				$output						.=	"<td align='right'>".number_format($insertSalesUptodate/100000,2)."</td>";
				$output						.=	"<td align='right'>".number_format($insertSalesUptodateLastMon/100000,2)."</td>";
				$output						.=	"<td align='right'>N.A.</td>";
				$output						.=	"<td align='right'>N.A.</td></tr>";
				$output						.=	"<tr><td><b>Net Sales</b></td>" ;
				$output						.=	"<td align='right'>".number_format($todaysSale - $todaysToolSale - $todaysInsertSale)."</td>";
				$output						.=	"<td align='right'>".number_format(($salesUptodate - $toolSalesUptodate - $insertSalesUptodate)/100000,2)."</td>";
				$output						.=	"<td align='right'>".number_format(($salesUptodateLastMon - $toolSalesUptodateLastMon - $insertSalesUptodateLastMon)/100000,2)."</td>";
				$output						.=	"<td align='right'>N.A.</td>";
				$output						.=	"<td align='right'>N.A.</td></tr>";				
			}
		}
		// New DI Data
		$output					.=	'<tr><th colspan="6">Despatch Plan Data (Rs Lakhs)</th></tr>';	
		$diData					=	@getMySQLData("select sum(qty * porate) as totdi from tbl_component_di tcd inner join ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid  where tcd.status = 1 and di_date = '$today'");
		$todaysDi				=	($diData['data'][0]['totdi'])?$diData['data'][0]['totdi']:0.00;
		$diData					=	@getMySQLData("select sum(qty * porate) as totdi from tbl_component_di tcd inner join ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid  where tcd.status = 1 and di_date between '$thisMon1stday' and '$today'");
		$diUptodate				=	($diData['data'][0]['totdi'])?$diData['data'][0]['totdi']:0.00;			
		$diData					=	@getMySQLData("select sum(qty * porate) as totdi from tbl_component_di tcd inner join ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid  where tcd.status = 1 and di_date between '$lastMon1stday' and '$lastMonToday'");
		$diUptodateLastMon		=	($diData['data'][0]['totdi'])?$diData['data'][0]['totdi']:0.00;	
		$output					.=	"<tr><td><b>DI Value</b></td>" ;
		$output					.=	"<td align='right'>".number_format($todaysDi)."</td>";
		$output					.=	"<td align='right'>".number_format($diUptodate/100000,2)."</td>";
		$output					.=	"<td align='right'>".number_format($diUptodateLastMon/100000,2)."</td>";
		$output					.=	"<td align='right'>N.A.</td>";
		$output					.=	"<td align='right'>N.A.</td></tr>";	
		// DI lapsed data
		$output					.=	'<tr><th colspan="6">DI Lapsed Data(Rs Lakhs) till Yesterday</th></tr>';
		$output					.=	'<tr><tr><th colspan="4">&nbsp;</th><th width="20%">Total DI Value</th><th>DI Lapse %</th></tr>';
		for ($grpCount = 0;$grpCount < count($cusGroups);$grpCount++)
		{
			$cusGroup				=	$cusGroups[$grpCount]['cusGroup'];	
			$diData					=	@getMySQLData("select if((sum(qty * porate) - sum(ifnull(invQty,0) * porate)) > 0,(sum(qty * porate) - sum(ifnull(invQty,0) * porate)),0) as penddi  from tbl_component_di tcd
															INNER JOIN ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid
															".(($cusGroup != '')?"INNER JOIN tbl_customer tc ON tcd.cusid = tc.cusId and upper(tc.cusGroup) = '$cusGroup'":"")."        
															LEFT JOIN (select sum(invQty) as invQty, invCusId, invDespId, invCode from tbl_invoice_sales_items tinv INNER JOIN tbl_invoice_sales tici on tici.invId=tinv.invId and status = 1 group by invDespId) tic on di_desc = invDespId and tcd.cusId = invCusId and tcd.cmpdId = invCode
														where tcd.status  = 1 and di_date = DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day)");		
			$todaysLapDi			=	($diData['data'][0]['penddi'])?$diData['data'][0]['penddi']:0;
			$diData					=	@getMySQLData("select if((sum(qty * porate) - sum(ifnull(invQty,0) * porate)) > 0,(sum(qty * porate) - sum(ifnull(invQty,0) * porate)),0) as penddi  from tbl_component_di tcd
															INNER JOIN ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid
															".(($cusGroup != '')?"INNER JOIN tbl_customer tc ON tcd.cusid = tc.cusId and upper(tc.cusGroup) = '$cusGroup'":"")."        
															LEFT JOIN (select sum(invQty) as invQty, invCusId, invDespId, invCode from tbl_invoice_sales_items tinv INNER JOIN tbl_invoice_sales tici on tici.invId=tinv.invId and status = 1 group by invDespId) tic on di_desc = invDespId and tcd.cusId = invCusId and tcd.cmpdId = invCode          
														where tcd.status  = 1 and di_date between '$thisMon1stday' and DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day)");
			$lapDiUptodate			=	($diData['data'][0]['penddi'])?$diData['data'][0]['penddi']:0;
			$diData					=	@getMySQLData("select if((sum(qty * porate) - sum(ifnull(invQty,0) * porate)) > 0,(sum(qty * porate) - sum(ifnull(invQty,0) * porate)),0) as penddi  from tbl_component_di tcd
															INNER JOIN ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid
															".(($cusGroup != '')?"INNER JOIN tbl_customer tc ON tcd.cusid = tc.cusId and upper(tc.cusGroup) = '$cusGroup'":"")."        
															LEFT JOIN (select sum(invQty) as invQty, invCusId, invDespId, invCode from tbl_invoice_sales_items tinv INNER JOIN tbl_invoice_sales tici on tici.invId=tinv.invId and status = 1 group by invDespId) tic on di_desc = invDespId and tcd.cusId = invCusId and tcd.cmpdId = invCode          
														where tcd.status  = 1 and di_date between '$lastMon1stday' and DATE_ADD('$lastMonToday', INTERVAL -".$despValidDays." day)");			
			$lapDiUptodateLastMon	=	($diData['data'][0]['penddi'])?$diData['data'][0]['penddi']:0;	
			$totDiData				=	@getMySQLData("select sum(qty * porate) as totdi  from tbl_component_di tcd
															INNER JOIN ( select cmpdid,porate from (select * from tbl_customer_cmpd_po_rate order by update_on desc) temp_tab group by cmpdid) tccpr on tccpr.cmpdid =  tcd.cmpdid
															".(($cusGroup != '')?"INNER JOIN tbl_customer tc ON tcd.cusid = tc.cusId and upper(tc.cusGroup) = '$cusGroup'":"")."        
														where tcd.status  = 1 and di_date between '$thisMon1stday' and DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day)");
			$totDiValue				=	($totDiData['data'][0]['totdi'])?$totDiData['data'][0]['totdi']:0;
			if($todaysLapDi > 0 || $lapDiUptodate > 0 || $lapDiUptodateLastMon > 0 || $totDiValue > 0 ) 
			{
				$output					.=	"<tr><td><b>".(($cusGroup != '')?$cusGroup:"Total")."</b></td>" ;		
				$output					.=	"<td align='right'>".number_format($todaysLapDi)."</td>";
				$output					.=	"<td align='right'>".number_format($lapDiUptodate/100000,2)."</td>";
				$output					.=	"<td align='right'>".number_format($lapDiUptodateLastMon/100000,2)."</td>";
				$output					.=	"<td align='right'>".number_format($totDiValue/100000,2)."</td>";
				$output					.=	"<td align='right'>".(($totDiValue > 0)?number_format(round(100 * $lapDiUptodate/$totDiValue)):"N.A.")."</td></tr>";
			}
		}	
		// Compound Issue Data
		$output					.=	'<tr><th colspan="6">Compound Issued Data (Kg)</th></tr>';	
		$operGrps				=	array('In-House','Sub-Contractors','');
		for ($grpCount = 0;$grpCount < count($operGrps);$grpCount++)
		{
			$operGrp				=	$operGrps[$grpCount];
			$cpdData				=	@getMySQLData("select sum(qtyIss) as totcpdissued from tbl_moulding_issue  where status > 0 ".(($operGrp != '')?" and operator ".(($operGrp == 'In-House')?" = ":" != "). " 'In-House'":"")."  and issueDate = '$today'");
			$todaysCpdIss			=	($cpdData['data'][0]['totcpdissued'])?$cpdData['data'][0]['totcpdissued']:0;
			$cpdData				=	@getMySQLData("select sum(qtyIss) as totcpdissued from tbl_moulding_issue  where status > 0 ".(($operGrp != '')?" and operator ".(($operGrp == 'In-House')?" = ":" != "). " 'In-House'":"")."  and issueDate between '$thisMon1stday' and '$today'");
			$cpdIssUptodate			=	($cpdData['data'][0]['totcpdissued'])?$cpdData['data'][0]['totcpdissued']:0;			
			$cpdData				=	@getMySQLData("select sum(qtyIss) as totcpdissued from tbl_moulding_issue  where status > 0 ".(($operGrp != '')?" and operator ".(($operGrp == 'In-House')?" = ":" != "). " 'In-House'":"")."  and issueDate between '$lastMon1stday' and '$lastMonToday'");
			$cpdIssUptodateLastMon	=	($cpdData['data'][0]['totcpdissued'])?$cpdData['data'][0]['totcpdissued']:0;	
			$scheduleCpdData		=	@getMySQLData("select sum(schQty * cmpdBlankWgt/1000) as schcpdval from (select * from (select cpdId_cmpdId, schQty,cusId from tbl_scheduling  where schOrder='component' and schMonth='$curMonth' and status>0 order by entry_on desc)tbl1  group by cpdId_cmpdId,cusId )  ts INNER JOIN tbl_component  tc  ON tc.cmpdId = ts.cpdId_cmpdId and tc.status != 0 ");
			$scheduleCpdIss			=	($scheduleCpdData['data'][0]['schcpdval'])?$scheduleCpdData['data'][0]['schcpdval']:0;		
			$output					.=	"<tr><td><b>".(($operGrp != '')?$operGrp:"Total")."</b></td>" ;
			$output					.=	"<td align='right'>".number_format($todaysCpdIss)."</td>";
			$output					.=	"<td align='right'>".number_format($cpdIssUptodate)."</td>";
			$output					.=	"<td align='right'>".number_format($cpdIssUptodateLastMon)."</td>";
			$output					.=	"<td align='right'>".number_format($scheduleCpdIss)."</td>";
			$output					.=	"<td align='right'>".(($scheduleCpdIss > 0)?number_format(round(100 * $cpdIssUptodate/$scheduleCpdIss)):"N.A.")."</td></tr>";		
		}
		// Lifts Planned Data - Operator Wise
		$output					.=	'<tr><th colspan="6">Planned Lifts Data - Operator Wise(Nos)</th></tr>';
		$operGrps				=	array('In-House','Sub-Contractors','');
		for ($grpCount = 0;$grpCount < count($operGrps);$grpCount++)
		{
			$operGrp				=	$operGrps[$grpCount];
			$liftData				=	@getMySQLData("select sum(liftPlanned) as totlifts from tbl_moulding_plan where status > 1 ".(($operGrp != '')?" and operator ".(($operGrp == 'In-House')?" = ":" != "). " 'In-House'":"")."  and planDate = '$today'");
			$todaysLifts			=	($liftData['data'][0]['totlifts'])?$liftData['data'][0]['totlifts']:0;
			$liftData				=	@getMySQLData("select sum(liftPlanned) as totlifts from tbl_moulding_plan where status > 1 ".(($operGrp != '')?" and operator ".(($operGrp == 'In-House')?" = ":" != "). " 'In-House'":"")."  and planDate between '$thisMon1stday' and '$today'");
			$liftsUptodate			=	($liftData['data'][0]['totlifts'])?$liftData['data'][0]['totlifts']:0;			
			$liftData				=	@getMySQLData("select sum(liftPlanned) as totlifts from tbl_moulding_plan where status > 1 ".(($operGrp != '')?" and operator ".(($operGrp == 'In-House')?" = ":" != "). " 'In-House'":"")."  and planDate between '$lastMon1stday' and '$lastMonToday'");
			$liftsUptodateLastMon	=	($liftData['data'][0]['totlifts'])?$liftData['data'][0]['totlifts']:0;	
			$scheduleData			=	@getMySQLData("select sum(schQty / no_of_active_cavities) as schvalue from (select * from (select cpdId_cmpdId, schQty,cusId from tbl_scheduling  where schOrder='component' and schMonth='$curMonth' and status>0 order by entry_on desc)tbl1  group by cpdId_cmpdId,cusId )  ts INNER JOIN (select * from (select compId, no_of_active_cavities from tbl_tool  where status1>0 order by no_of_active_cavities desc)tbl1  group by compId )  tt  ON tt.compId = ts.cpdId_cmpdId ");
			$scheduleLifts			=	($scheduleData['data'][0]['schvalue'])?$scheduleData['data'][0]['schvalue']:0;		
			$output					.=	"<tr><td><b>".(($operGrp != '')?$operGrp:"Total")."</b></td>" ;
			$output					.=	"<td align='right'>".number_format($todaysLifts)."</td>";
			$output					.=	"<td align='right'>".number_format($liftsUptodate)."</td>";
			$output					.=	"<td align='right'>".number_format($liftsUptodateLastMon)."</td>";
			$output					.=	"<td align='right'>".number_format($scheduleLifts)."</td>";
			$output					.=	"<td align='right'>".(($scheduleLifts > 0)?number_format(round(100 * $liftsUptodate/$scheduleLifts)):"N.A.")."</td></tr>";		
		}
		// Lifts Planned Data - Customer Group Wise
		$output					.=	'<tr><th colspan="6">Planned Lifts Data - Customer Group Wise(Nos)</th></tr>';
		for ($grpCount = 0;$grpCount < count($cusGroups);$grpCount++)
		{
			$cusGroup				=	$cusGroups[$grpCount]['cusGroup'];	
			$liftsData				=	@getMySQLData("select sum(plannedLifts) as plannedLifts from  (select planRef,sum(plannedLifts) as plannedLifts from tbl_moulding_receive  group by planRef ) tmp
															inner join tbl_invoice_mould_plan timp on timp.planid = tmp.planRef 
															inner join tbl_moulding_plan tmop on tmp.planRef = tmop.planid and tmop.status > 1
															left join (select * from (SELECT cmpdId,upper(cusGroup) as cusGroup  FROM tbl_customer_cmpd_po_rate tccpr	inner join tbl_customer tcus on tcus.cusId = tccpr.cusId where tccpr.status = 1 order by tccpr.update_on desc) tbl1 group by cmpdId) tblrate on timp.cmpdid = tblrate.cmpdId
														where invDate  = '$today' ".(($cusGroup != '')?" and cusGroup = '$cusGroup'":""));		
			$todaysLifts			=	($liftsData['data'][0]['plannedLifts'])?$liftsData['data'][0]['plannedLifts']:0;
			$liftsData				=	@getMySQLData("select sum(plannedLifts) as plannedLifts from  (select planRef,sum(plannedLifts) as plannedLifts from tbl_moulding_receive  group by planRef ) tmp
															inner join tbl_invoice_mould_plan timp on timp.planid = tmp.planRef 
															inner join tbl_moulding_plan tmop on tmp.planRef = tmop.planid and tmop.status > 1
															left join (select * from (SELECT cmpdId,upper(cusGroup) as cusGroup  FROM tbl_customer_cmpd_po_rate tccpr	inner join tbl_customer tcus on tcus.cusId = tccpr.cusId where tccpr.status = 1 order by tccpr.update_on desc) tbl1 group by cmpdId) tblrate on timp.cmpdid = tblrate.cmpdId
														where invDate  between '$thisMon1stday' and '$today' ".(($cusGroup != '')?" and cusGroup = '$cusGroup'":""));
			$liftsUptodate			=	($liftsData['data'][0]['plannedLifts'])?$liftsData['data'][0]['plannedLifts']:0;
			$liftsData				=	@getMySQLData("select sum(plannedLifts) as plannedLifts from  (select planRef,sum(plannedLifts) as plannedLifts from tbl_moulding_receive  group by planRef ) tmp
															inner join tbl_invoice_mould_plan timp on timp.planid = tmp.planRef 
															inner join tbl_moulding_plan tmop on tmp.planRef = tmop.planid and tmop.status > 1
															left join (select * from (SELECT cmpdId,upper(cusGroup) as cusGroup  FROM tbl_customer_cmpd_po_rate tccpr	inner join tbl_customer tcus on tcus.cusId = tccpr.cusId where tccpr.status = 1 order by tccpr.update_on desc) tbl1 group by cmpdId) tblrate on timp.cmpdid = tblrate.cmpdId
														where invDate between '$lastMon1stday' and '$lastMonToday' ".(($cusGroup != '')?" and cusGroup = '$cusGroup'":""));			
			$liftsUptodateLastMon	=	($liftsData['data'][0]['plannedLifts'])?$liftsData['data'][0]['plannedLifts']:0;	
			$totSchData				=	@getMySQLData("select sum(schQty / no_of_active_cavities) as schvalue from (select * from (select cpdId_cmpdId, schQty,upper(cusGroup) as cusGroup, tcus.cusId from tbl_scheduling tsch inner join tbl_customer tcus on tcus.cusId = tsch.cusId where schOrder='component' and schMonth='$curMonth' and tsch.status>0 ".(($cusGroup != '')?" and cusGroup = '$cusGroup'":"")." order by tsch.entry_on desc)tbl1  group by cpdId_cmpdId,cusId )  ts INNER JOIN (select * from (select compId, no_of_active_cavities from tbl_tool  where status1>0 order by no_of_active_cavities desc)tbl1  group by compId )  tt  ON tt.compId = ts.cpdId_cmpdId");
			$totSchValue			=	($totSchData['data'][0]['schvalue'])?$totSchData['data'][0]['schvalue']:0;
			if($todaysLifts > 0 || $liftsUptodate > 0 || $liftsUptodateLastMon > 0 || $totSchValue > 0 ) 
			{
				$output					.=	"<tr><td><b>".(($cusGroup != '')?$cusGroup:"Total")."</b></td>" ;		
				$output					.=	"<td align='right'>".number_format($todaysLifts)."</td>";
				$output					.=	"<td align='right'>".number_format($liftsUptodate)."</td>";
				$output					.=	"<td align='right'>".number_format($liftsUptodateLastMon)."</td>";
				$output					.=	"<td align='right'>".number_format($totSchValue)."</td>";
				$output					.=	"<td align='right'>".(($totSchValue > 0)?number_format(round(100 * $liftsUptodate/$totSchValue)):"N.A.")."</td></tr>";
			}
		}			
		// Rejection Data
		$output					.=	'<tr><th colspan="6">Inspected Data (Lakh Nos)</th></tr>';	
		$inspectData			=	@getMySQLData("select sum(rejval) as rejval, sum(receiptqty) as inspectqty from ( select qualityref, receiptqty, sum(if(rejcode!='REWORK',rejval,0)) as rejval from tbl_moulding_quality where qualitydate = '$today'  and is_open_stock = 0 group by qualityref )t");
		$inspQtyToday			=	($inspectData['data'][0]['inspectqty'])?$inspectData['data'][0]['inspectqty']:0;
		$rejQtyToday			=	($inspectData['data'][0]['rejval'])?$inspectData['data'][0]['rejval']:0;		
		$inspectData			=	@getMySQLData("select sum(rejval) as rejval, sum(receiptqty) as inspectqty from ( select qualityref, receiptqty, sum(if(rejcode!='REWORK',rejval,0)) as rejval from tbl_moulding_quality where qualitydate  between '$thisMon1stday' and '$today'  and is_open_stock = 0 group by qualityref )t");
		$inspQtyUptodate		=	($inspectData['data'][0]['inspectqty'])?$inspectData['data'][0]['inspectqty']:0;
		$rejQtyUptodate			=	($inspectData['data'][0]['rejval'])?$inspectData['data'][0]['rejval']:0;		
		$inspectData			=	@getMySQLData("select sum(rejval) as rejval, sum(receiptqty) as inspectqty from ( select qualityref, receiptqty, sum(if(rejcode!='REWORK',rejval,0)) as rejval from tbl_moulding_quality where qualitydate  between '$lastMon1stday' and '$lastMonToday'  and is_open_stock = 0 group by qualityref )t");
		$inspQtyUptodateLastMon	=	($inspectData['data'][0]['inspectqty'])?$inspectData['data'][0]['inspectqty']:0;
		$rejQtyUptodateLastMon	=	($inspectData['data'][0]['rejval'])?$inspectData['data'][0]['rejval']:0;	
		$output					.=	"<tr>" ;
		$output					.=	"<td><b>Inspected Quantity</b></td>" ;
		$output					.=	"<td align='right'>".number_format($inspQtyToday/100000,2)."</td>";
		$output					.=	"<td align='right'>".number_format($inspQtyUptodate/100000,2)."</td>";
		$output					.=	"<td align='right'>".number_format($inspQtyUptodateLastMon/100000,2)."</td>";
		$output					.=	"<td align='right'>N.A.</td>";
		$output					.=	"<td align='right'>N.A.</td></tr>";		
		$output					.=	"<tr><td><b>Rejected Quantity</b></td>" ;
		$output					.=	"<td align='right'>".number_format($rejQtyToday/100000,2)."</td>";			
		$output					.=	"<td align='right'>".number_format($rejQtyUptodate/100000,2)."</td>";
		$output					.=	"<td align='right'>".number_format($rejQtyUptodateLastMon/100000,2)."</td>";
		$output					.=	"<td align='right'>N.A.</td>";
		$output					.=	"<td align='right'>N.A.</td></tr>";			
		$output					.=	"<tr><td><b>Rejection Percentatge (%)</b></td>" ;
		$output					.=	"<td align='right' style='font-size:16px;'>".(($inspQtyToday > 0)?number_format(round(100 * $rejQtyToday/$inspQtyToday)):"N.A.")."</td>";
		$output					.=	"<td align='right' style='font-size:16px;'>".(($inspQtyUptodate > 0)?number_format(round(100 * $rejQtyUptodate/$inspQtyUptodate)):"N.A.")."</td>";
		$output					.=	"<td align='right' style='font-size:16px;'>".(($inspQtyUptodateLastMon > 0)?number_format(round(100 * $rejQtyUptodateLastMon/$inspQtyUptodateLastMon)):"N.A.")."</td>";		
		$output					.=	"<td align='right'>N.A.</td>";
		$output					.=	"<td align='right'>N.A.</td></tr>";		
		$output					.=	"</table></body></html>";
		$pstatus 				= 	sendEmail($mgmt_grp_email,"", " Sales/Production Data for ".date("d-m-Y"),$output,"");
		// send compound schedule 
		$todate					=	date("d");
		if( $todate % $numdays_tosend === 0) 
		{
			// Fetch Record from Database
			$output 		= 	"";
			$sql 			= 	"select cmpdCpdName, sum(schQty * cmpdBlankWgt/1000) as schcpdval from ( select * from (select cmpdId, cmpdBlankWgt, cmpdCpdName, t1.schQty, cusId from tbl_scheduling t1 inner join tbl_component t2 on t1.cpdId_cmpdId=t2.cmpdId where t1.schMonth='$curMonth' and t1.status=1 and t2.status=1 order by t1.entry_on desc)as component group by cmpdId,cusId) tab1 group by cmpdCpdName";
			$outSql 		=	@getMySQLData($sql);
			$schedDetails 	=   $outSql['data'];
			
			// Get Records from the table
			foreach($schedDetails as $keys=>$values) {
				foreach($values as $key=>$value) {				
						$output .='"'.$value.'",';
				}
				$output .="\n";
			}		
			$thefile = sys_get_temp_dir()."\\compound-schedule-".$today.".csv";
			file_put_contents($thefile, $output);
			$output = sendEmail($vendor_grp_email,"","Latest Compound Schedule for ".$curMonth,"please find the Latest Compound Schedule for ".$curMonth,$thefile);
		}	
		exit();
	} 

	if ( array_key_exists($invoice_id[0],$schVsDespGroup))
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_NAME'].'/Sales/ScheduleVsDespatch/?type=schedulelist&grouping='.$invoice_id[0].'&reporttype='.$invoice_id[1].'"</script>';
		exit();
	}			

	$cutOffDate = 	date('Y-m-d', mktime(0, 0, 0, date('n')-$invoice_id, 1, date('y')));
	$sql_getcom	=	"select cmpdId,cmpdName,cmpdCpdName,cmpdBlankWgt,cmpdAMR,cmpdRemarks,
							(select sum(no_of_cavities) from tbl_tool where status1 > 0 and compId = tc.cmpdId) as no_of_cavities,
							(select sum(avlQty) from tbl_mould_store where  status > 0 and cmpdId  = tc.cmpdId) as stockQty,
							(select group_concat(distinct cusGroup separator '<br/>') as cusGroup  from tbl_customer tcus
												inner join tbl_customer_cmpd_po_rate tccpr on tccpr.cusId = tcus.cusId  and tccpr.status > 0 
								where tccpr.cmpdId = tc.cmpdId) as cusGroup,
							(select poRate from tbl_customer_cmpd_po_rate where status > 0 and cmpdId = tc.cmpdId order by poRate desc limit 1) as poRate,
							(select concat(date_format(invDate,'%d-%m-%y'),'(',format(sum(quantity),0),')') from tbl_invoice_mould_plan where cmpdId = tc.cmpdId  order by invDate desc limit 1) as lastplandets
						from tbl_component tc
							left join (select invCode from tbl_invoice_sales_items tici
											inner join tbl_invoice_sales tic on tici.invId=tic.invId and tic.status > 0 and (tic.invDate between '$cutOffDate' and CURDATE())
										where invtype = 'cmpd' group by invCode) ti on ti.invCode=tc.cmpdId
						WHERE tc.status>0 and ti.invCode IS NULL 
						order by cusGroup,cmpdCpdName";
	//echo $sql_getcom; 	
	$getcom			=	@getMySQLData($sql_getcom);
	$particulars	=	$getcom['data'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Non-Moving Components</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />		
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(41);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
			<tr>
				<td rowspan="2" colspan="2" align="center" >
					<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" height="70px" />
				</td>
				<td colspan="8" class="content_bold cellpadding content_center" height="45px">
					<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
				</td>
				<td rowspan="2" colspan="2" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Date: </div>
					<div style="font-size:14px;" ><?php echo date('d-m-Y',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?></div>
					<div style="font-size:34px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
				</td>
			</tr>
			<tr>
				<td colspan='8' align="center" style="font-size:16px;"><b>Report for last: <?php echo $invoice_id; ?> months</b>
				</td>
			<tr>			
			<tr style="font-size:8px;">
				<th width="3%">
					No.
				</th>
				<th width="10%">
					Name
				</th>
				<th width="10%">
					Compound
				</th>
				<th width="8%">
					Blnk Wgt.
				</th>				
				<th width="5%">
					Cavs.
				</th>
				<th width="6%">
					Rate
				</th>
				<th width="8%">
					AMR
				</th>
				<th width="5%">
					Cust.
				</th>				
				<th width="12%">
					Last Inv.
				</th>
				<th width="12%">
					Last Plan
				</th>
				<th width="5%">
					In-Stk
				</th>	
				<th>
					Remarks
				</th>						
			</tr>
            <?php
				for($p=0;$p<count($particulars);$p++){
			?>
				<tr style="font-size:10px;">
					<td align="left" >
						<?php print $p+1; ?>
					</td>					
					<td align="left" >
						<?php print ($particulars[$p]['cmpdName'])?$particulars[$p]['cmpdName']:'&nbsp;'; ?>
					</td>
					<td align="left" >
						<?php print ($particulars[$p]['cmpdCpdName'])?$particulars[$p]['cmpdCpdName']:'&nbsp;'; ?>
					</td>						
					<td align="right">						
						<?php print ($particulars[$p]['cmpdBlankWgt'])?@number_format($particulars[$p]['cmpdBlankWgt'],2):'0.00';?>
					</td>
					<td align="right">
						<?php print ($particulars[$p]['no_of_cavities'])?@number_format($particulars[$p]['no_of_cavities'],0):'0';?>
					</td>
					<td align="right">						
						<?php print ($particulars[$p]['poRate'])?@number_format($particulars[$p]['poRate'],2):'0.00';?>
					</td>
					<td align="right">						
						<?php print ($particulars[$p]['cmpdAMR'])?@number_format($particulars[$p]['cmpdAMR'],0):'0';?>
					</td>
					<td align="left">
						<?php print ($particulars[$p]['cusGroup'])?$particulars[$p]['cusGroup']:'-'; ?>
					</td>
					<td align="left" >
						<?php
						$invSql	=	@getMySQLData("select concat(date_format(invDate,'%d-%m-%y'),'(',format(sum(invQty),0),')') as lastinvdets 
														from tbl_invoice_sales_items tici1
														inner join tbl_invoice_sales tic1 on tici1.invId=tic1.invId and tic1.status > 0 
													where invtype = 'cmpd' and tici1.invCode = '".$particulars[$p]['cmpdId']."' group by tici1.invId order by invdate desc limit 1");
						print ($invSql['data'][0]['lastinvdets'])?$invSql['data'][0]['lastinvdets']:'-'; ?>
					</td>
					<td align="left" >
						<?php print ($particulars[$p]['lastplandets'])?$particulars[$p]['lastplandets']:'-'; ?>
					</td>			
					<td align="right">
						<?php print ($particulars[$p]['stockQty'])?@number_format($particulars[$p]['stockQty'],0):'0'; ?>
					</td>
					<td align="left">
						<?php print $particulars[$p]['cmpdRemarks']; ?>
					</td>					
				</tr>
            <?php
			}
			?>				
			<tr>
				<td colspan="4" class="content_left content_bold" valign="top">
					Remarks:<br /><br />
				</td>			
				<td colspan="5" class="content_left content_bold" valign="top">
					Prepared:
				</td>
				<td colspan="3" class="content_left content_bold" valign="top">
					Approved:
				</td>	
			</tr>
		</table>
     </body>
</html>