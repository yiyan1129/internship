<html>
<head><title>PHP TEST</title></head>
<body>

<?php

header('Content-Type: text/html; charset=UTF-8');

$dsn = '�f�[�^�x�[�X��';
$user = '���[�U�[��';
$password = '�p�X���[�h';

try{
	$pdo = new PDO($dsn, $user, $password);//�f�[�^�x�[�X�ɐڑ�
	$sql = 'SHOW CREATE TABLE apple';
	$result = $pdo->query($sql);
	foreach($result as $row){//�e�[�u���̒��g���m�F����
		print "{$row[0]}<br>";
		print "{$row[1]}<br>";
		print "<br>";
	}


}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>

</body>
</html>