<?php

function Ext_SyntaxHighlighter_All($id, $options,  $dataIn, $colInfo )
{
  $source = Extension::reportSourceCode( $dataIn[0][0] );
  $template = substr($dataIn[0][0], 0, 1) == '#';
  if ( $source ) {
    echo '<div style="text-align: left">';
    if ($template) {
      echo '<div style="margin-left: 20px;margin-right: 20px;">';
        echo '<div class="title">Template:'.$dataIn[0][0].'</div>';
        echo '<div class="template_code">';
          echo '<ul>';
          foreach ($source as $key => $value) {
            echo '<li><a href="#'.$key.$id.'">'.$key.'</a></li>';
          }
          echo '</ul>';
          foreach ($source as $key => $value) {
            echo '<div id="'.$key.$id.'">';
              echo '<pre class="brush: xml">' . pl($source[$key]) . '</pre>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';
    } else {
      echo '<pre class="brush: mydbrsql">' . pl($source) . '</pre>';
    }
    echo '</div>';
  }
  else {
    echo '<div>No source code available</div>';
  }
}
