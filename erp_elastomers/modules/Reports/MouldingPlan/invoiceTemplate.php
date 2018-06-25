<?php
	$module		=	strtolower((ISO_IS_REWRITE)?$_VAR['mod']:$_GET['mod']);
	$file		=	$module."InvoiceTemplate.php";
	$rel_path	=	substr(ISO_RELATIVE_FILE, 0, strrpos(ISO_RELATIVE_FILE, "\\"));	
	if($file != null)
		include_once($rel_path."/".$file);

?>