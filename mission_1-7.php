<?php

$filename ='kadai1-5.txt';

$fp =fopen($filename,'r');

$i=0;
while(!feof($fp)){
	$a[i]=fgets($fp);
	print "{$a[i]}<br/>";
	$i++;
}

fclose($fp);

?>

