<?php

function Ext_DummyPre($id, $options, $colInfo)
{
	echo '<div style="text-align:left;">Calling extension function Ext_DummyPre<br>Result Set ID: '.$id .'<br>' ;
	
	echo '$options =<br>';
	var_dump($options);
	echo '$colInfo =<br>';
	var_dump($colInfo);
	
	echo '</div>';
}


function Ext_DummyRow($dataRow)
{
	echo '<div style="text-align:left;">Calling extension function Ext_DummyRow<br>';
	
	echo '$dataRow =<br>';
	var_dump($dataRow);
	
	echo '</div>';
}

function Ext_DummyAfter()
{
	echo '<div style="text-align:left;">Calling extension function Ext_DummyAfter<br>';	
	echo '</div>';
}


function Ext_DummyAll($id, $options,  $dataIn, $colInfo )
{
	echo '<div style="text-align:left;">Calling extension function Ext_Dummy_All<br>Result Set ID: '.$id .'<br>' ;
	
	echo '$options =<br>';
	var_dump($options);
	echo '$dataIn =<br>';
	var_dump($dataIn);
	echo '$colInfo =<br>';
	var_dump($colInfo);
	
	echo '</div>';
}

?>