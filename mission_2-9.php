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
	$sql = 'SHOW TABLES';
	$result = $pdo->query($sql);

	foreach($result as $row){//データベースに存在するテーブルを表示
		print "$row[0]<br>";
	}


}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>

</body>
</html>