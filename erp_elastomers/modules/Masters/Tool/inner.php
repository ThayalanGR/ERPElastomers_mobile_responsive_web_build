<?php

	$view	=	(ISO_IS_REWRITE)?$_VAR['view']:$_GET['view'];
	
	switch($view){
		case "location":
			require_once 'child_location.php';
		break;
		case "history":
			require_once 'child_history.php';
		break;
		case "docs":
			require_once 'child_docs.php';
		break;		
	}

?>