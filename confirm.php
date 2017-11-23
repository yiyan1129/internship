<html>
<body>
<?php
header('Content-Type: text/html; charset=UTF-8');

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

//データベースに接続
$pdo = new PDO($dsn, $user, $password);
try{
	//メールの本文のURLからユーザー名とキーを取得
	$username=$_GET['username'];
	$reg_key=$_GET['key'];

	//
	$sql = "SELECT * FROM `interim_registration` WHERE `username`='$username' AND reg_key='$reg_key'";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	if(($result = $stmt->fetch(PDO::FETCH_ASSOC))==NULL){
		echo "エラーがでました。<br>初めからやり直してください。<br>";
	}else{
		//フラグを仮登録から本登録へ変更
		$sql = 'update interim_registration set reg_flag =:reg_flag where reg_key=:reg_key';
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':reg_flag', $reg_flag, PDO::PARAM_INT);
		$stmt -> bindValue(':reg_key', $reg_key, PDO::PARAM_STR);
		$reg_flag='2';
		$flag = $stmt->execute();

		//編集の確認用コード
		/*if($flag){
			print('データの編集に成功しました<br>');
		}else{
			print('データの編集に失敗しました<br>');
		}*/
	}

	//本登録できた情報を表示
	$sql = 'select id, username, password, mailadr, reg_key, reg_flag from `interim_registration` order by id';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print('番号:'.$result['id'].'  ');
		print('名前:'.$result['username'].'<br>');
		print('password:'.$result['password'].'<br>');
        	print('mailadr:'.$result['mailadr'].'<br>');
		print('reg_key:'.$result['reg_key'].'<br>');
		print('reg_flag:'.$result['reg_flag'].'<br>');
	}


}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
?>
<br>
<a href="toppage.php">トップページへ戻る</a>
</body>
</html>
