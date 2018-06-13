<?php
// This is used to override values in defaults.php at main level.

$mydbr_defaults['page_title'] = 'myDBR Own';
$mydbr_defaults['export']['wkhtmltopdf']['command'] = '"C:\\wkhtmltopdf\\bin\\wkhtmltopdf.exe"';

/* 
  Debug flag to determine server cookie problems. 
  Enabling this may expose your database login information so use with caution

  $mydbr_defaults['debug_cookie'] = true;
*/
