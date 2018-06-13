<?php

$connectionInfo = array(
	'UID' => 'mydbr', 
	"PWD" => 'password', 
	'ReturnDatesAsStrings' => true,
	'CharacterSet'  => 'UTF-8'
);

$serverName = 'myserver';

$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br>";
}else{
     echo "Connection could not be established.<br>";
     die( print_r( sqlsrv_errors(), true));
}
?>
