<?php

	$view	=	(ISO_IS_REWRITE)?$_VAR['view']:$_GET['view'];
	
	switch($view){
		case "location":
			require_once '/modules/Masters/Tool/child_location.php';
		break;
		case "history":
			require_once '/modules/Masters/Tool/child_history.php';
		break;
		case "docs":
			require_once '/modules/Masters/Tool/child_docs.php';
		break;			
		case "stock":
			require_once 'child_stocklist.php';
		break;		
	}

?>