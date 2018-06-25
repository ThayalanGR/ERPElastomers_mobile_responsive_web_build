<?php
/*
  /mydbr/tools/troubleshoot/mysql.php
  
  To test the connection to MySQL

  $dbhost   - name / IP address of the database server
  $username - username for the Microsoft SQL Server database
  $password - password
  $dbname   - database name (by default mydbr)
  $dbport   - a TCP/IP port when using a non default port number
  $ssl_use_ssl - use SSL when connecting to the dataabase
  $ssl_key - Client private key if client certificate is to be used
  $ssl_certificate - Client certificate
  $ssl_ca_cert - Optional CA certificate
*/

$dbhost = 'mysqlserver';
$username = 'root';
$password = 'password';
$dbname = 'mydbr';
// $dbport= 3306;
$ssl_use_ssl = false;
$ssl_key = '';
$ssl_certificate = '';
$ssl_ca_cert = '';


if (!function_exists('mysqli_connect')) {
   die('mysqli is not installed in php');
}

if ($ssl_use_ssl) {
  $dbconn = mysqli_init();
  mysqli_options ($dbconn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
  mysqli_ssl_set($dbconn, $ssl_key, $ssl_certificate, $ssl_ca_cert, NULL, NULL);

  $rconn = mysqli_real_connect(  $dbconn, 
                  $dbhost, 
                  $username, 
                  $password, 
                  $dbname ? $dbname : null, 
                  isset($dbport) ? $dbport : null, 
                  NULL, 
                  MYSQLI_CLIENT_SSL
                 );

  if ($rconn===false) {
     $dbconn = false;
  }
} else {

  if (isset($dbport)) {
    $dbconn = @mysqli_connect( $dbhost, $username, $password, $dbname, $dbport );
  }
  else {
    $dbconn = @mysqli_connect( $dbhost, $username, $password, $dbname );
  }
}

if (!$dbconn) {
   die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}

echo 'Connection ok. ' . mysqli_get_host_info($dbconn) . "<br>";

$result = mysqli_query($dbconn, "SHOW STATUS LIKE 'Ssl_cipher';");
$row = mysqli_fetch_array($result);
if ($row[1]=='') {
  echo 'Database connection does not use SSL' . "<br>";
} else {
  echo 'Database connection uses SSL'. "<br>";
  echo $row[0].': '.$row[1]. "<br>";
}

@mysqli_close($dbconn);

?>