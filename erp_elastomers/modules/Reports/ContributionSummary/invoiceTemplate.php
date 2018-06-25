<?php
	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	if($_REQUEST["type"] == "RUNJOB") 
	{
		global $mgmt_grp_email;
		$lastMonth	=	date("F Y", strtotime(date('Y-m')." -1 month"));
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
		// send email
		$aEmail = new AsyncCreatePDFAndEmail("Management/ContributionSummary","summaryreport1", $mgmt_grp_email,"","Customer-wise Contribution Summary for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Contribution Report for :".$lastMonth);									
		$aEmail->start();
		$aEmail = new AsyncCreatePDFAndEmail("Management/ContributionSummary","summaryreport2", $mgmt_grp_email,"","Product-wise Contribution Summary for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Contribution Report for :".$lastMonth);									
		$aEmail->start();
		$aEmail = new AsyncCreatePDFAndEmail("Management/ContributionSummary","summaryreport3", $mgmt_grp_email,"","Blankweight-wise Contribution Summary for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Contribution Report for :".$lastMonth);									
		$aEmail->start();
		
		exit();						
	}
	else if ( rtrim($invoice_id,"0..9") == "summaryreport")
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_ADDR'].'/'.ISO_LOAD_MODULE.'/?type='.$invoice_id.'";</script>';
		exit();
	}		

?>
