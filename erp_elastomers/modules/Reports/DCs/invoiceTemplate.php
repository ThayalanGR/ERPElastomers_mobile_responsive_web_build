<?php
	$module		=	strtolower((ISO_IS_REWRITE)?$_VAR['mod']:$_GET['mod']);
	$file		=	"";
	$rel_path	=	substr(ISO_RELATIVE_FILE, 0, strrpos(ISO_RELATIVE_FILE, "\\"));

	switch($module){
		case "testcert":
		case "ram":		
		case "cpd":
		case "cmpd":
			$file	=	"dcInvoiceTemplate.php";
		break;
		case "testreport":
		default:
			$file	=	"tcInvoiceTemplate.php";
		break;
	}
	
	// Include the file
	if($file != null)
		include_once($rel_path."/".$file);

?>