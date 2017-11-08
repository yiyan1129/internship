<?php
$filename = 'kadai2.txt';

$fp = fopen($filename, 'r');

while(!feof($fp)){
	print fgets($fp);
}

fclose($fp);

?>
