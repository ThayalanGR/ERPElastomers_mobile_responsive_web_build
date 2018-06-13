<?php
	
	// Set Headers
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
	
	session_start();
	ob_start();
	
	$inv_page_ext		=	array('js', 'css', 'action', 'run', 'design');
	
	$is_rewrite			=	((((isset($_GET['rewrite']))?$_GET['rewrite']:((isset($_SESSION['app']['iso_is_rewrite']))?$_SESSION['app']['iso_is_rewrite']:$_GET['r']))+0) > 0);
	$rewrite_url		=	($is_rewrite)?"/":"";	
	$page_ext			=	strtolower(end(explode('.', $_SERVER['REQUEST_URI'])));
	$is_src_page		=	!in_array($page_ext, $inv_page_ext);
	
	// Define Constants
	@define('ISO_DS', DIRECTORY_SEPARATOR);
	@define('ISO_DIR', $_SERVER['DOCUMENT_ROOT'] . ISO_DS);
	@define('ISO_LIB', ISO_DIR."libraries");
	@define('ISO_IS_REWRITE', $is_rewrite);
	@define('ISO_REWRITE_URL', $rewrite_url);
	@define('ISO_SRC_PAGE', $is_src_page);
	
	$_SESSION['app']['iso_dir']			=	ISO_DIR;
	$_SESSION['app']['iso_lib']			=	ISO_LIB;
	$_SESSION['app']['iso_is_rewrite']	=	ISO_IS_REWRITE;
	$_SESSION['app']['iso_rewrite_url']	=	ISO_REWRITE_URL;
	
	include_once "number2Word.php";
	include_once "functions.php";

	$_VAR				=	($is_rewrite)?@getVarFromURL():$_GET;
	$load_module		=	($is_rewrite)?$_VAR[0].(($_VAR[1])?"/".$_VAR[1]:''):$_VAR['module'];
	$is_sub				=	$_VAR['page'];
	$show_design		=	($is_sub != "invoice");
	$open_file			=	"";
	$open_file_rel		=	"";
	
	@define('ISO_SHOW_DESIGN', $show_design);
	@define('ISO_SUB_PAGE', $is_sub);
	$_SESSION['app']['iso_show_design']	=	ISO_SHOW_DESIGN;
	$tempName = @getSettingsValue(array('company_name','gstn','pan'));
	$_SESSION['app']['comp_name']	=	$tempName['company_name'];
	$_SESSION['app']['comp_gstn']	=	$tempName['gstn'];
	$_SESSION['app']['comp_pan']	=	$tempName['pan'];
	
	// Check for Logout Action
	if($load_module == 'Logout')
	removeAllSessionButNot("app");
	
	// Update for Financial Year Start
	check4FinancialYear();
	
	// Set Headers for Other Files
	setPageHeaders($page_ext);
	
	// To Show Design
	switch($is_sub){
		case "invoice":
			$show_design	=	false;
			$user		=	$_GET['user'];
			if($user != null && $user != '')
			{
				$password	=	$_GET['pass'];
				$passmd5	=	md5($password);
				
				$sql = "select * from tbl_users where status>0 and userName='$user' and password='$passmd5'";
				$res = @getMySQLData($sql);
				
				if($res['count'] > 0 && $res['status'] == "success"){
					//echo $res['status'];
					$_SESSION['login']			=	1;
					$_SESSION['userdetails']	=	$res['data'][0];
					
					// User Permisssion
					$_SESSION['userdetails']['userPermissions']		=	($_SESSION['userdetails']['userPermissions'])
																			?@preg_split("/[,]/", $_SESSION['userdetails']['userPermissions'], -1, PREG_SPLIT_NO_EMPTY)
																			:'';
					$_SESSION['userdetails']['userSubPermissions']	=	($_SESSION['userdetails']['userSubPermissions'])
																			?@preg_split("/[,]/", $_SESSION['userdetails']['userSubPermissions'], -1, PREG_SPLIT_NO_EMPTY)
																			:'';
				}
			}			
		break;
		default:
			$show_design	=	true;
		break;
	}
	
	if($is_src_page){
		// Set Which Page to Load
		$load_module	=	($_SESSION['login'] == 1)?$load_module:"login";
		@define('ISO_LOAD_MODULE', $load_module);
		$_SESSION['app']['iso_load_module']	=	ISO_LOAD_MODULE;
		// Load Page
		$file_permission	=	@getPagePermission($load_module);
		$open_file_rel		=	substr(preg_replace("/[\/]/", "\\", $file_permission[2]), (($is_rewrite)?1:0), strlen($file_permission[2]));
		$show_design		=	$file_permission[0];
		
		@define('ISO_BASE_URL', (ISO_SRC_PAGE)?$file_permission[1]:$_SESSION['app']['iso_base_url']);
		@define('ISO_OPEN_FILE', $file_permission[2]);
		@define('ISO_RELATIVE_FILE', ISO_DIR.$open_file_rel);
		$_SESSION['app']['iso_open_file']		=	ISO_OPEN_FILE;
		$_SESSION['app']['iso_relative_file']	=	ISO_RELATIVE_FILE;
		$_SESSION['app']['iso_base_url']		=	ISO_BASE_URL;
		/*echo "<script language='javascript'>alert('1, ".ISO_REWRITE_URL."');</script>";*/
	}
	
?>