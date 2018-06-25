<?php

function Ext_Qrcode( $id, $options,  $dataIn, $colInfo )
{
  $obj_id = 'code'.$id;
  
  $subtitle = Extension::subtitle();
  for ($i=0; $i < sizeof($dataIn); $i++) { 
    $id = $obj_id.'_'.$i;
    
    if ($subtitle) {
      echo '<div '.Extension::keepwithnext().'>';
      echo '<div class="subtitle">'.Extension::subtitle().'</div>';
    }
    echo '<div id="'.$id.'" '.Extension::resultclass(true, 'qrcode', $subtitle == '').'></div>';
    if (substr($dataIn[$i][0], 0,1)=='{') {
      $code = $dataIn[$i][0];
    } else {
      $code = '"'.$dataIn[$i][0].'"';
    }
    echo '<script>new QRCode(document.getElementById("'.$id.'"), '.$code.');</script>';
    if ($subtitle) {
      echo '</div>';
    }
  }
}