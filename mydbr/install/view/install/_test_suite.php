<table class="install_tests">
  <?php
  foreach( $suite->test_list as $test)
  {
    if ( $test->passed )
    {
      $value = "OK";
      $class = "ok";
    }
    else
    {
      $value = $test->show_stopper ? "Failed" : "ToDo";
      $class = $test->show_stopper ? "nok" : "todo";
    }

    echo '<tr>
      <td class="topic">' . $test->title . '</td>
      <td class="' . $class . '">' . $value . '</td>
    </tr>';

    if ( $test->info != '' )
    {
      echo '<tr><td colspan="2" class="' . $class . ' details">' . $test->info . '</td></tr>';
    }

  }
  ?>
</table>