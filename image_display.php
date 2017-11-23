<?php
if($_GET['no']){
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
try{
	//データベースに接続
	$pdo = new PDO($dsn, $user, $password);
	//URLからid取得
	$id1=$_GET['no'];
	//掲示板テーブルからそのidの情報を取得
	$sql = "SELECT * FROM `keijiban` WHERE `id`={$id1}";
	$result = $pdo->query($sql);

	//idと画像データと拡張子を取得
	foreach($result as $row){
		$id=$row['id'];
		$imgdat=$row['imgdat'];
		$mime=$row['mime'];
	}
	//画像表示
	if($_GET['mime']=="jpg")$mime="image/jpeg";
	header("Content-Type: $mime");
	echo $row['imgdat'];

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();

}
}
?>