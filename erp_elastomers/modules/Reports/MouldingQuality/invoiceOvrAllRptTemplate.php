<?php

	$noOfMonths			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$fromDate 			= 	date('Y-m-d', mktime(0, 0, 0, date('n')-$noOfMonths, 1, date('y')));
	$toDate				=	date('Y-m-d', strtotime('last day of previous month'));
	$sql_InspInfo		=	"select 
								 DATE_FORMAT(qualitydate,'%b-%Y') as batMonth,
								 cmpdname,
								 sum(receiptqty) as inspQty,
								 sum(appqty) as approvedQty, 
								 sum(rejval) as rejQty,
								 (sum(rejval)/sum(receiptqty)) * 100 as rejPer, 
								 sum(FL) as FL,
								 sum(SF) as SF,
								 sum(CR) as CR,
								 sum(TR) as TR,
								 sum(TI) as TI, 
								 sum(rejval - (FL+SF+CR+TR+TI)) as OTHERS,
								 sum(rewrkval) as reworkQty,
								 (sum(rewrkval)/sum(receiptqty)) * 100 as reworkPer
							from ( select 
										qualitydate,
										tc.cmpdid,
										concat(cmpdName,'(',cmpdRefNo,')') as cmpdname,
										receiptqty, 
										appqty, 
										sum(if(rejcode!='REWORK',rejval,0)) as rejval,
										sum(if(rejcode='FL',rejval,0)) as FL,
										sum(if(rejcode='SF',rejval,0)) as SF,
										sum(if(rejcode='CR',rejval,0)) as CR,
										sum(if(rejcode='TR',rejval,0)) as TR,
										sum(if(rejcode='TI',rejval,0)) as TI,
										sum(if(rejcode='REWORK',rejval,0)) as rewrkval
									from tbl_moulding_quality  tmq
										inner join tbl_component tc on tc.cmpdid = tmq.cmpdid
									where is_open_stock = 0 and qualitydate between  '$fromDate' and '$toDate' group by qualityref	
								)t 
								GROUP BY cmpdid,DATE_FORMAT(qualitydate,'%b-%Y')
							order by cmpdname,qualitydate;";	
	
	// filename for download
	$filename = "overall_quality_inspection_report_".$fromDate."_to_" . $toDate . ".csv";
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");	
	$output 		=	"";
	$outSql 		=	@getMySQLData($sql_InspInfo);
	$qualDetails 	=   $outSql['data'];		
	$output 		.= 	'"Month","Component Ref.","Inspected Qty","Approved Qty","Rejected Qty","Rejection %","FL","SF","CR","TR","TI","Others","Rework Qty","Rework %"';
	$output 		.=	"\n";
	
	// Get Records from the table
	foreach($qualDetails as $keys=>$values) {
			foreach($values as $key=>$value) {
			$output .='"'.$value.'",';
		}
		$output .="\n";
	}
	
	echo $output;	
	exit();
	
?>
