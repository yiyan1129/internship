//仮登録用フォーム
<form action="register.php" method="post">
ID：<input type="text" name="id"><br>
名前：<input type="text" name="username"><br>
パスワード:<input type="password" name="password"><br>
メアド:<input type="text" name="mailadr"><br>
<input type="submit" value="仮登録">
</form>
<?php
header('Content-Type: text/html; charset=UTF-8');

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

try{
	//データベースに接続
	$pdo = new PDO($dsn, $user, $password);

	//仮登録用のテーブルが存在していなければ作成
	$sql="CREATE TABLE IF NOT EXISTS `interim_registration`"
		."("
		."`id` VARCHAR(32) NOT NULL,"
		."`username` VARCHAR(32) NOT NULL,"
		."`password` VARCHAR(32) NOT NULL,"
		."`mailadr` VARCHAR(64) NOT NULL,"
		."`reg_key` VARCHAR(64) NOT NULL,"
		."`reg_flag` INT"
		.");";

	$stmt=$pdo->query($sql);

	//テーブル作成確認用
	/*if(!$stmt){
		print "miss";
	}else print "ok";*/

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>
