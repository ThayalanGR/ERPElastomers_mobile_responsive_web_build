<?php
	global $grn_customers, $grn_role, $grn_emailadd, $grn_mixtype;
	$invoice_id		=	trim((ISO_IS_REWRITE)?$_VAR['invID']:$_GET['invID']);
	$invoice_id		=	split("-",$invoice_id);
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
				$mixtypeArr		=	$grn_mixtype[$customer];
				for($mx=0;$mx<count($mixtypeArr);$mx++)
				{
					$aEmail 	= 	new AsyncCreatePDFAndEmail("Sales/CPDStockLedger",$customer."-".$mixtypeArr[$mx], $grn_emailadd[$customer],"",$mixtypeArr[$mx]." Compound Stock Report for:".$today,"Dear Sir/Madam,\n Please find the attached file for the ".$mixtypeArr[$mx]." Compound Stock Report for :".$today,true);									
					$aEmail->start();
				}
			}
		}
		exit();
	}
	if ( in_array($invoice_id[0],$grn_customers))
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_NAME'].'/Sales/CPDStockLedger/?type=stocklist&cust='.$invoice_id[0].'&mixtype='.$invoice_id[1].'"</script>';
		exit();
	}
?>