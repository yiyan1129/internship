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
	$sql = 'SELECT * FROM apple';
	$result = $pdo->query($sql);

	foreach($result as $row){//appleテーブル内のデータを表示
		echo $row['id'];
		echo ':';
		echo $row['name'];
		echo '<br>';
	}

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>

</body>
</html>