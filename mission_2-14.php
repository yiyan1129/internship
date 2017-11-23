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

	print('削除前のデータ一覧：<br>');

	$sql = 'select id, name from apple';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	//削除前のデータを表示
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print($result['id']);
        	print($result['name'].'<br>');
	}

	$sql = 'DELETE FROM apple where id =:id';//appleテーブルの指定したidのデータを削除する
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$id='5';
	$flag = $stmt->execute();

	if($flag){
		print('データの削除に成功しました<br>');
	}else{
		print('データの削除に失敗しました<br>');
	}

	print('削除後のデータ一覧：<br>');

	$sql = 'select id, name from apple';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	//削除後のデータを表示
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
	        print($result['id']);
	        print($result['name'].'<br>');
	}



}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>

</body>
</html>