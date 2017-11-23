<html>
<head><title>PHP TEST</title></head>
<body>

<?php

header('Content-Type: text/html; charset=UTF-8');

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

try{
	$pdo = new PDO($dsn, $user, $password);

	print('編集前のデータ一覧：<br>');

	$sql = 'select id, name from apple';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	//編集前のデータを表示
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print($result['id']);
        	print($result['name'].'<br>');
	}

	$sql = 'update apple set name =:name where id =:id';//appleテーブルの指定したidの名前を編集する
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
	$id='5';
	$name= '葡萄';
	$flag = $stmt->execute();

	if($flag){
		print('データの編集に成功しました<br>');
	}else{
		print('データの編集に失敗しました<br>');
	}

	print('編集後のデータ一覧：<br>');

	$sql = 'select id, name from apple';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	//編集後のデータを表示
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