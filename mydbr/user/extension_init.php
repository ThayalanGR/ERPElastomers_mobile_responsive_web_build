<?php
// SMS Extension - specify which SMS gateway you want to use. 
// 
// 
// Clickatell (http://www.clickatell.com)
// -------------------------------
// 
// required parameters: user, pass, api_id
// 
// In order to use SMS in myDBR you have to register with clickatell and
// create a HTTP interface.
$mydbr_sms = array( 
  'sender'  => "clickatell",
  'user' 	  => "USER",
  'pass' 	  => "PASS", 
	'api_id'  => "API_ID",
);

// Pushbullet (https://www.pushbullet.com)
// Pushover (https://pushover.net)
// ProwlApp (https://www.prowlapp.com)

$mydbr_push = array( 
	'sender'  => "pushover", // Default sender: pushover, pushbullet, prowl
	'pushover_app_api_token'  => "APP_TOKEN",
  'pushbullet_access_token'  => "ACCESS_TOKEN"
);
