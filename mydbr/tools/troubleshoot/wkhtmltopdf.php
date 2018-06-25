<?php
include('../../defaults.php');

ob_start();
@passthru('echo TEST');
$ouput = ob_get_contents();
if(substr($ouput,0,4) !== 'TEST'){
    
    $disabled = explode(',', ini_get('disable_functions'));
    if (in_array('passthru', $disabled)) {
        echo 'passthru function disabled in php.ini. Check the disable_functions-directive.';
        die();
    }
    echo 'passthru() not working';
    die();
}
ob_end_clean();

var_dump($mydbr_defaults['export']['wkhtmltopdf']['command']);
passthru($mydbr_defaults['export']['wkhtmltopdf']['command'].' -V', $output);
var_dump($output);

?>
