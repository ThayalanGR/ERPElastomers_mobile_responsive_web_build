<?php
	global $stdforms_upload_dir;
	include_once "EXCEL/eiseXLSX.php";
	$xcelFieldArray = 	array("B2","B3","C3",
								"O2","B4","K4","C9",
								"C11","L9","L11","L15","L17","H30","J30","H31","J31","H32","J32","H33","J33","H34","J34",
								"C42","C43","E44","C46","E47","C48","M43","M44","M45");
	$filePath 		= 	$_SESSION['app']['iso_dir'].$stdforms_upload_dir;
	
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
	$sql_bill		=	"select (select value from tbl_settings where name = 'company_name') as company_name,concat('Part No.:',part_number) as part_number,concat('Part Description:',part_description) as part_description,
							DATE_FORMAT(quotedate, '%d-%b-%Y') as quote_date,concat('Customer :' , ( select cusname from tbl_customer tc where tc.cusId  = tdr.cusId)) as cusname,application,cpd_base_polymer,
							compcost,blankwgt,prodwgt,mixcost,inscost,concat('=',ROUND(moldoutput/toolcavs),'*M43') as moldoutput,moldhrcost,trimoutput,trimhrcost,inspoutput,insphrcost,insprepoutput,insprephrcost,adhoutput,adhhrcost,
							inventper,rejper,amortcost,adminper,freightcost,profitper,toolcavs,toolcost,(toollife * toolcavs) as toollife
						from tbl_invoice_quote tiq
							inner join tbl_develop_request  tdr on tiq.rfqid = tdr.drId
							inner join tbl_develop_feasibility tdf on tdr.sno = tdf.prod_ref
						where quoteref='".$invoice_id."'";
	$out_bill		=	@getMySQLData($sql_bill);		
	$data			=	$out_bill['data'][0];
	//write into excel sheet
	$xlsx 			= 	new eiseXLSX($filePath."Quote-template.xlsx");
	$count			=	0;
	foreach($data as $value)
	{
		if(is_numeric($value) &&  $value== 0 && $count > 6)
		{
			//do nothing
		}
		else
		{	
			//echo $xcelFieldArray[$count]." - ".$value."<br/>";
			$xlsx->data($xcelFieldArray[$count], $value);
		}
		
		$count++;
	}	
	$xlsx->Output($invoice_id.".xlsx", "D"); 	
?>

