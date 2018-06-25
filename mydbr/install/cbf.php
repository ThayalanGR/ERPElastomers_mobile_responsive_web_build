<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
	<title>myDBR Install</title>
	<link rel="stylesheet" href="install.css" type="text/css" media="screen" title="no title">
	<link rel="stylesheet" href="install/install.css" type="text/css" media="screen" title="no title">
	<!--[if IE]>
	<link href="ie.css" rel="stylesheet" type="text/css">
	<link href="install/ie.css" rel="stylesheet" type="text/css">
	<![endif]-->
<style>
div.loadererror {
font-size: 14px;
margin: 20px;
padding-top: 20px;
padding-bottom: 20px;
text-align: center;
background-color: #FFFF99;
border: 1px solid;
}
</style>
</head>
<div id="header_noc">
	<div id="header_content" class="fixed">myDBR Installation - Installation error
	</div>
</div>

<div id="content" style="margin-top: 40px;">
	<p>myDBR has encountered an error in installation.</p>
	<table class="install_tests" style="margin-top: 40px;">
		<tbody>
			<tr>				
<?php
function ioncube_event_handler($err_code, $params)
{
	switch ($err_code) {
		case ION_LICENSE_EXPIRED:
			$hdr   = 'Version expired';
			$error = 'This version has expired. Please check for the new version at <a href="http://mydbr.com">http://mydbr.com</a>';
			break;
		case ION_LICENSE_NOT_FOUND:
			$hdr   = 'Missing ionCube license file';
			$error = 'See that a valid "ioncube.txt" file has been placed at top level of mydbr directory and that the web server can read the file.<br>';
			$error .= 'Click <a href="ioncube.txt">here</a> to access the file and see if you simply have a permission problem.';
			break;
		case ION_CORRUPT_FILE:
			$hdr   = 'File corrupted';
			$error = 'Re-install the mydbr application files.';
			break;
		case ION_NO_PERMISSIONS:
			$hdr   = 'Permission problem';
			$error = 'An encoded file has a server restriction and is used on a non-authorised system';
			break;
		case ION_CLOCK_SKEW:
			$hdr   = 'Time/Date error';
			$error = 'Check the system clock';
			break;
		case ION_LICENSE_PROPERTY_INVALID:
		case ION_LICENSE_HEADER_INVALID:
		case ION_LICENSE_CORRUPT:
			$hdr   = 'Invalid license file';
			$error = 'Your license file "ioncube.txt" does not match the application or is corrupted. Please check your installation';
			break;
		case ION_UNAUTH_INCLUDING_FILE:
			$hdr   = 'Invalid inlcude';
			$error = 'The encoded file has been included by a file which is either unencoded or has incorrect properties';
			break;
		case ION_LICENSE_SERVER_INVALID:
			$hdr   = 'Invalid license';
			$error = 'Cannot use the application in this server. Please contact <a href="http://mydbr.com">http://mydbr.com</a>';
			break;
		case ION_UNAUTH_INCLUDED_FILE:
			$hdr   = 'Unauthorized inlcude';
			$error = 'The encoded file has included a file which is either unencoded or has incorrect properties';
			break;
		case ION_UNAUTH_APPEND_PREPEND_FILE:
			$hdr   = 'php.ini configuration problem';
			$error = 'The php.ini has either the --auto-append-file or  --auto-prepend-file setting enabled';
			break;
		case ION_UNTRUSTED_EXTENSION:
			$hdr   = 'Extension problem';
			$error = 'Server has system with an unrecognised extension installed';
			break;
		default:
			$hdr = 'Unknown error';
			$error = 'Unknown error ('.$err_code.') returned.'; 
	}
	echo '<td class="topic">'. $hdr .'</td><td class="todo">ToDo</td></tr>';
	echo '<tr><td class="details" colspan="2">'. $error .'</td></tr></table>';
	echo '</div>
	</body>
	</html>';
}
?>
