<html>
<head><title>PHP TEST</title></head>
<body>

<?php

header('Content-Type: text/html; charset=UTF-8');

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

try{
	$dbh = new PDO($dsn, $user, $password);//データベースに接続
	$dbh = null;

    print('接続に成功しました。<br>');

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}

?>

</body>
</html>