<?php
require_once '../includes/DbOperations.php';



if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['search_quarry'])){
		$db=new DbOperations();
		
			$user = $db->dtaReturn($_POST['search_quarry']);
			echo $user;
		
	}
}

?>