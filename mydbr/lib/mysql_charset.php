<?php
/* 
	Will set the MySQL database connection charset to same as the page encoding 
	This lookup table provides a way to dynamically add encodings
*/

$mysql_charset_convert = array (
  'ISO-8859-1' => 'latin1',
  'UTF-8' => 'utf8',
);
?>