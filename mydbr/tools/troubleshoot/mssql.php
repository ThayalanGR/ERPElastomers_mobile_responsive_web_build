<?php
/*
	To test the connection to Microsoft SQL Server

	$dbhost   - name / IP address of the database server
	$username - username for the Microsoft SQL Server database
	$password - password
	$dbname   - database name (by default mydbr)
	$dbport   - a TCP/IP port number when using a non default port
*/
$dbhost = 'localhost';
$username = 'sa';
$password = '';
$dbname = 'mydbr';
$dbport= '';

if (!function_exists('mssql_connect')) {
   die('mssql is not installed in php');
}

if (isset($dbport) && $dbport!='') {
	$dbhost = $dbhost . ',' . $dbport;
}
if ($dbconn = mssql_connect($dbhost,$username, $password, true)) {
	if (!mssql_select_db( $dbname, $dbconn )) {
		echo 'Unable to use the database. '.mssql_get_last_message();
	} else {
		echo 'Connection ok.';
	}
	mssql_close($dbconn);
} else {
	echo 'Unable to connect to the database. '.mssql_get_last_message();
}

?>