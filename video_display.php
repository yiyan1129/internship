<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
try{
	//データベースに接続
	$pdo = new PDO($dsn, $user, $password);
	//URLからid取得
	$id1=$_GET['no'];
	//掲示板テーブルからそのidの情報を取得
	$sql = "SELECT * FROM `keijiban` WHERE `id`=$id1";
	$result = $pdo->query($sql);

	//idと動画データと拡張子を取得
	foreach($result as $row){
		$id=$row['id'];
		$imgdat=$row['imgdat'];
		$mime=$row['mime'];
	}

	//動画表示
	header('Content-Type: video/mp4');
	echo $row['imgdat'];

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();

}

?>