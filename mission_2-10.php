<html>
<head><title>PHP TEST</title></head>
<body>

<?php

header('Content-Type: text/html; charset=UTF-8');

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

try{
	$pdo = new PDO($dsn, $user, $password);//データベースに接続
	$sql = 'SHOW CREATE TABLE apple';
	$result = $pdo->query($sql);
	foreach($result as $row){//テーブルの中身を確認する
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