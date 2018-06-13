<?php 
include('/phpqrcode/qrlib.php');
$param 		= 	$_GET['id']; 
$param_ecc	=	($_GET['ecc'])?$_GET['ecc']:QR_ECLEVEL_L;
$param_pix	=	($_GET['pixel'])?$_GET['pixel']:3;
QRcode::png($param,null,$param_ecc,$param_pix);
?>
