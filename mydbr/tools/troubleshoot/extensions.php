<?php
/*
	Test the extensions
*/


function setOK($ok, $required)
{
	if ($ok) {
		return '<span style="color: green">OK</span>'."<br>";
	} else {
		return '<span style="color: red">not installed</span>'."<br>";
	}
}

$extensions = array(
	'ionCube Loader' => 'ionCube Loader',
	'mysqli' => 'MySQL Support',
	'mssql' => 'Microsoft SQL Server / Sybase ASE / SQL Anywhere support',
	'ChartDirector PHP API' => 'ChartDirector support',
);
	
echo '<b>Extensions:</b><br>';
foreach ($extensions as $key => $value) {
	echo "$value ".setOK(extension_loaded($key), true);
}

?>