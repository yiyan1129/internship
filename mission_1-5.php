<form action="" method="post">
<input type="text" name="data"/>
<input type="submit" name="‘—M"/>
</form>

<?php

$filename ='kadai1-5.txt';

$fp =fopen($filename,'w');

if(isset($_POST["data"]))
	fwrite($fp,"{$_POST["data"]}");

fclose($fp);

?>

