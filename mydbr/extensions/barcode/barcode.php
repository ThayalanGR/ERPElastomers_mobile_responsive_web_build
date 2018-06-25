<?php

function Ext_Barcode( $id, $options,  $dataIn, $colInfo )
{
  $obj_id = 'code'.$id;
  
  $code_object = $options['dbr.barcode']['code'];
  
  $tag = isset($options['dbr.barcode']['tag']) ? $options['dbr.barcode']['tag'] : 'img';

  if (substr($code_object, 0,1)!=='{') {
    $code_object = '{format:"'.$code_object.'"}';
  }
  
  $subtitle = Extension::subtitle();
  for ($i=0; $i < sizeof($dataIn); $i++) { 
    if ($subtitle) {
      echo '<div '.Extension::keepwithnext().'>';
      echo '<div class="subtitle">'.Extension::subtitle().'</div>';
    }

    $id = $obj_id.'_'.$i;
    echo '<'.$tag.' id="'.$id.'" '.Extension::resultclass(true, 'barcode', $subtitle == '').'>';

    if (in_array($tag, array('svg', 'canvas'))) {
      echo '</'.$tag.'>';
    }
    echo '<script>'
      .'$("#'.$id.'").JsBarcode('.json_encode($dataIn[$i][0]).','.$code_object.');'
      .'</script>';
    if ($subtitle) {
      echo '</div>';
    }
  }
}