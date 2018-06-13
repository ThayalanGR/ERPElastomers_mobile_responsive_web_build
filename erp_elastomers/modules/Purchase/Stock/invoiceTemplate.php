<?php
	global $grn_customers, $grn_role, $grn_emailadd;
	$invoice_id		=	trim((ISO_IS_REWRITE)?$_VAR['invID']:$_GET['invID']);
	$today			=	date("d-m-Y");	
	if($_REQUEST["type"] == "RUNJOB" ) 
	{
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
	
		for($cc=0;$cc<count($grn_customers);$cc++)
		{
			$customer	=	$grn_customers[$cc];
			if( ($grn_role[$customer] == 'client' || $grn_role[$customer] == 'self' ) && empty($grn_emailadd[$customer]) == false )
			{
				// send email
				$aEmail = new AsyncCreatePDFAndEmail("Compound/StockLedger",$customer, $grn_emailadd[$customer],"","Raw Material Stock Report for:".$today,"Dear Sir/Madam,\n Please find the attached file for the Raw Material Stock Report for :".$today,true);									
				$aEmail->start();
			}
		}
		exit();
	}
	if ( in_array($invoice_id,$grn_customers))
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_NAME'].'/Compound/StockLedger/?type=stocklist&cust='.$invoice_id.'"</script>';
		exit();
	}			
	


?>