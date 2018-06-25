<?php

	if($_REQUEST["type"] == "RUNJOB") 
	{
		global $quality_grp_email;
		$today					=	date("Y-m-d");
		$output					=	'<html><body><table><tr><th width="5%">S.No</th><th width="25%">Inspector</th><th width="20%">Part Number</th><th width="10%">Insp. Qty</th><th width="10%">App. Qty</th><th width="10%">Rej. Qty</th><th width="10%">Rework Qty</th><th>Rej. %</th></tr>';
		//Inspection Data
		$salesDataArr			=	@getMySQLData("select  inspector, tmp.cmpdName, sum(receiptqty) as receiptqty, sum(appqty) as appqty, sum(rejqty) as rejqty, sum(reworkqty) as reworkqty  
														FROM (select inspector, mdlrref, planref, receiptqty, appqty, sum(if(rejcode!='REWORK',rejval,0)) as rejqty, sum(if(rejcode ='REWORK',rejval,0)) as reworkqty from tbl_moulding_quality where qualitydate = '$today' and is_open_stock = 0 group by qualityref) tmq
															INNER JOIN tbl_invoice_mould_plan tmp on tmp.planid = SUBSTRING_INDEX(tmq.planref,'-',1) 
														group by inspector,tmp.cmpdName
													order by inspector");
		$salesDataArr			=	$salesDataArr['data'];
		$keyCount				=	0;
		$currInsp				=	"";
		if(count($salesDataArr) > 0)
		{
			foreach($salesDataArr as $salesData ) 
			{	
				$keyCount ++;
				if($currInsp == $salesData['inspector'])
					$printInsp	=	'&nbsp;';
				else
				{
					$currInsp 	= 	$salesData['inspector'];
					$printInsp	=	$currInsp;
				}
				
				
				$output					.=	"<tr><td>$keyCount</td><td>$printInsp</td><td>".$salesData['cmpdName']. "</td>";	
				$output					.=	"<td align='right'>".number_format($salesData['receiptqty'])."</td><td align='right'>".number_format($salesData['appqty'])."</td>";
				$output					.=	"<td align='right'>".number_format($salesData['rejqty'])."</td><td align='right'>".number_format($salesData['reworkqty'])."</td>";
				$output					.=	"<td align='right'>".(($salesData['receiptqty'] > 0)?number_format((100 * $salesData['rejqty']/($salesData['receiptqty'] - $salesData['reworkqty'])),2):"N.A.")."</td></tr>";
			}
			// Inspection Total Data
			$inspectTotData			=	@getMySQLData("select sum(rejval) as rejqty, sum(reworkval) as reworkqty,sum(receiptqty) as inspectqty, sum(appqty) as appqty from ( select qualityref, receiptqty, appqty, sum(if(rejcode!='REWORK',rejval,0)) as rejval, sum(if(rejcode='REWORK',rejval,0)) as reworkval from tbl_moulding_quality where qualitydate = '$today' and is_open_stock = 0 group by qualityref )t");
			$inspectTotData			=	$inspectTotData['data'][0];
			$output					.=	"<tr><td colspan='3' align='center'><b>Grand Total</b></td>";	
			$output					.=	"<td align='right'>".number_format($inspectTotData['inspectqty'])."</td><td align='right'>".number_format($inspectTotData['appqty'])."</td>";
			$output					.=	"<td align='right'>".number_format($inspectTotData['rejqty'])."</td><td align='right'>".number_format($inspectTotData['reworkqty'])."</td>";
			$output					.=	"<td align='right'>".(($inspectTotData['inspectqty'] > 0)?number_format((100 * $inspectTotData['rejqty']/($inspectTotData['inspectqty'] - $inspectTotData['reworkqty'])),2):"N.A.")."</td></tr>";	
			$output					.=	"</table></body></html>";
			$pstatus 				= 	sendEmail($quality_grp_email,"", " Visual Inspection Data for ".date("d-m-Y"),$output,"");
		}		
		exit();
	} 			
	
	$module		=	strtolower((ISO_IS_REWRITE)?$_VAR['mod']:$_GET['mod']);
	$file		=	"";
	$rel_path	=	substr(ISO_RELATIVE_FILE, 0, strrpos(ISO_RELATIVE_FILE, "\\"));

	switch($module){
		case "overall":
			$file	=	"invoiceOvrAllRptTemplate.php";
		break;
		default:
			$file	=	"invoiceIndRptTemplate.php";
	}
	
	// Include the file
	if($file != null)
	include_once($rel_path."/".$file);

?>