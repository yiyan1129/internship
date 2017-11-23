<?php
header('Content-Type: text/html; charset=UTF-8');

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

try{
	//データベースに接続
	$pdo = new PDO($dsn, $user, $password);

	//入力されたIDを取得
	$id=$_POST['id'];

	//仮登録用テーブルに同じIDがあるか確認
	$sql = "SELECT * FROM `interim_registration` WHERE `id`='$id'";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$flag=0;

	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($result['id']) $flag=1;//同じIDが存在するときflag=1にする
	}

	if($flag){
		print("このIDは存在します<br>別のIDにしてください<br>");
		echo '<a href="mailform.php">戻る</a>';
	}else{//同じID存在しないとき
		$username=$_POST['username'];
		$password=$_POST['password'];
		$mailadr=$_POST['mailadr'];
		$reg_key=sha1(uniqid(rand(),1));//ハッシュ
		$reg_flag=1;//仮登録は1、本登録は2とする

		//仮登録用テーブルに入力された情報を挿入する
		$stmt = $pdo->prepare("INSERT INTO interim_registration (id, username, password, mailadr, reg_key, reg_flag) VALUES (:id, :username, :password, :mailadr, :reg_key, :reg_flag)");
		$stmt -> bindValue(':id', $id, PDO::PARAM_STR);
		$stmt -> bindValue(':username', $username, PDO::PARAM_STR);
		$stmt -> bindValue(':password', $password, PDO::PARAM_STR);
		$stmt -> bindValue(':mailadr', $mailadr, PDO::PARAM_STR);
		$stmt -> bindValue(':reg_key', $reg_key, PDO::PARAM_STR);
		$stmt -> bindValue(':reg_flag', $reg_flag, PDO::PARAM_INT);

		$flag = $stmt->execute();

		//データ挿入確認用コード
		//if($flag) print('<br>データの追加に成功しました<br>');

		//メールを送る
		$to=$mailadr;
		$subject='e-mail confirm';
		$message="http://co-928.it.99sv-coco.com/confirm.php?username={$username}&key={$reg_key}";
		$headers='From:webmaster@example.com';
	
		if(mail($to,$subject,$message,$headers)){
			echo "{$mailadr}宛に確認メールを送信しました。<br>";
		}
	
		//テーブルに登録した情報を表示
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
	}

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>
