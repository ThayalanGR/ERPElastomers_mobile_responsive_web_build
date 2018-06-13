<?php
/*
	To test the connection to Sybase

  Add following defintion in the freetds.conf:

  [aseserver]
    host = 172.16.125.224
    port = 5000
    client charset = UTF-8
    tds version = 5.0
    text size = 64512

  Variables:
	$dbhost   - Use "aseserver" as defined abobe
	$username - username to use to connect to Sybase
	$password - password
	$dbname   - database name (by default mydbr)
	$dbport   - defined in freetds.conf
*/
$dbhost = 'aseserver';
$username = 'mydbr';
$password = 'password';
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