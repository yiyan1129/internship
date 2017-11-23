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

	print('追加前のデータ一覧：<br>');

	$sql = 'select id, name from apple';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	//追加前のデータを表示
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print($result['id']);
        	print($result['name'].'<br>');
	}

	$stmt = $pdo->prepare("INSERT INTO apple (id, name) VALUES (:id, :name)");//appleテーブルにデータを追加
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
	$id='5';
	$name = '米';
	$flag = $stmt->execute();


	if($flag){
		print('データの追加に成功しました<br>');
	}else{
		print('データの追加に失敗しました<br>');
	}

	print('追加後のデータ一覧：<br>');

	$sql = 'select id, name from apple';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
	//追加後のデータを表示
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