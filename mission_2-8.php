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

	print('接続に成功しました。<br>');

	$pdo -> exec("DROP TABLE IF EXISTS apple");//appleというテーブルが存在したら削除

	$sql="CREATE TABLE IF NOT EXISTS `apple`"//appleというテーブルが存在しなかったら作成
		."("
		."`id` INT,"
		."`name` CHAR(20)"
		.");";

	$stmt=$pdo->query($sql);

	if(!$stmt){
		print "miss";
	}else print "ok";

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>

</body>
</html>