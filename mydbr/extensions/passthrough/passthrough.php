<?php
/*
    Copyright mydbr.com 2007-2015 http://www.mydbr.com
    You are free to modify this file

    Example of the passthrough extension. Mark the extension with 'passthrough' => true in extensions.php
*/

function Ext_Passthrough($id, $options,  $dataIn, $colInfo )
{
  /*
      Do your stuff with the $dataIn which contains the data from the database.
  */

  // This is the data we'll send back to myDBR
  $data = array(
    array('ABC', 'Q1', 10, '2016-10-22'),
    array('ABC', 'Q2', 20, '2016-10-23'),
    array('Third', 'Q1', 40, '2016-10-24')
  );

  // Define the columns: name & datatype
  // Datatype needs to be one of following generic datatypes: char, float, int, datetime, date, time
  $columns = array(
    array('name' => 'Sector', 'datatype' => 'char'),
    array('name' => 'Quarter', 'datatype' => 'char'),
    array('name' => 'Value[v]', 'datatype' => 'int'),
    array('name' => 'Date[d]', 'datatype' => 'date')
  );

  // Pass it back to myDBR
  Extension::result_set($data, $columns);
}
