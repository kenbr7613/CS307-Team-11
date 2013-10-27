<?php

	echo "Reading file:\r\n";
	$filecontents = file_get_contents('file.txt');
	echo $filecontents;

	$file = fopen('allclasses.txt','r') . "\r\n";
	
	for ($i = 0; $i< 10; $i++)
	{
		$line = fgets($file, 4096);
		echo $i . ": " . $line . "\r\n";
	}

	fclose($file);
?>
