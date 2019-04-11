

<?php

$path    = 'C:\WinNMP\WWW\logs';
$files = scandir($path);

echo count($files) . " total file" . "<br>";
foreach ($files as $file)
{
	echo $file."<br>";
}


for($i=2;$i<=count($files)-1;$i++) {
	echo "<br>" . $files[$i] . "<br />\n";

		echo "<script type='text/javascript'> window.scrollBy(0, 5000000); </script>";

		foreach(file("logs\\" .$files[$i]) as $line) {
		   echo $line. "<br>";
		   
 
		}

	}

  ?>

