<?php

	function csv_to_array($filename='', $delimiter=',')
	{
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
		
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
			{
				$data[] = $row ; 
			}
			fclose($handle);
		}
		return $data;
	}
 
   if ( isset($_FILES["file"])) {
   
		try {
			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
				throw new RuntimeException('Invalid parameters.');
			}

			 // Check $_FILES['file']['error'] value.
			 switch ($_FILES['file']['error']) {
				 case UPLOAD_ERR_OK:
					 break;
				 case UPLOAD_ERR_NO_FILE:
					 throw new RuntimeException('No file sent.');
				 case UPLOAD_ERR_INI_SIZE:
				 case UPLOAD_ERR_FORM_SIZE:
					 throw new RuntimeException('Exceeded filesize limit.');
				 default:
					 throw new RuntimeException('Unknown errors.');
			 }

			 // You should also check filesize here. 
			 if ($_FILES['file']['size'] > 1000000) {
				 throw new RuntimeException('Exceeded filesize limit.');
			 }

			 if( 'text/csv' == $_FILES['file']['type'] ||  'application/vnd.ms-excel' == $_FILES['file']['type'] ) 
			 {
				//if file already exists
				if (file_exists("upload/" . $_FILES["file"]["name"])) {
					echo $_FILES["file"]["name"] . " already exists. ";
				}
				else 
				{
					//Store file in directory "upload" with the name of "uploaded_file.csv"
					$storagename = "uploaded_file.csv";
					if(!move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename))
					{
						throw new RuntimeException('Failed to move uploaded file.');
					}
					$testArray = csv_to_array("upload/" . $storagename);
					echo $testArray[0][0] . " " .$testArray[0][1];
				}	
			}
			else
			{
				 throw new RuntimeException('Invalid file format.');
			}
		} 
		catch (RuntimeException $e) {
			echo $e->getMessage();
		}      
     } 
	 else 
	 {
        echo "No file selected <br />";
     }
 
?>
