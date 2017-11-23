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

	//$pdo -> exec("DROP TABLE IF EXISTS banana");

	$sql="CREATE TABLE IF NOT EXISTS `banana`"//bananaテーブルが存在しない場合作成
		."("
		."`id` INT,"
		."`name` CHAR(20),"
		."`comment` CHAR(128),"
		."`date` DATETIME,"
		."`password` CHAR(20)"
		.");";

	$stmt=$pdo->query($sql);

	if(!empty($_POST["editpassword"])){//編集機能を使う用のパスワードが入力されたとき
		$sql = 'select id, name, comment, date, password from banana';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($result['id'] == $_POST["checkedit"]){
				if($_POST["editpassword"]==$result['password']){//パスワードが一致したとき
					$simEdit[0]=$result['id'];//配列simEditに投稿番号、名前、コメントを入れる
					$simEdit[1]=$result['name'];
					$simEdit[2]=$result['comment'];
				}
			}
		}
		if(empty($simEdit[0]))$miss=1;
	}
?>

<form action="" method="post">//フォーム作成
名前:<br/>
<input type="text" name="名前" value="<?php echo $simEdit[1]; ?>">//編集時入力済状態にする
<br/>
コメント:<br/>
<textarea name="コメント" rows="5" cols="30" ><?= $simEdit[2] ?></textarea>//編集時入力済状態にする
<input type="hidden" name="editnum" value="<?php echo $simEdit[0] ?>">
<br/>
パスワード:<br/>
<input type="text" name="password">
<input type="submit" value="投稿"/>
</form>
<form action="" method="post">
削除番号:<br/>
<input type="text" name="delete"/>
<input type="submit" value="削除"/>
</form>
<form action="" method="post">
編集番号:<br/>
<input type="text" name="edit"/>
<input type="submit" value="編集"/>
</form>

<?php

	if(!(""==$_POST["editnum"])){//編集時の入力済状態から編集して投稿した場合
		//bananaテーブルの指定したidにおける名前、コメント、日時、パスワードを編集
		$sql = 'update banana set name =:name, comment=:comment, date=:date, password=:password where id =:id';
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
		$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
		$stmt -> bindParam(':password', $password, PDO::PARAM_STR);
		$id=$_POST["editnum"];
		$name=$_POST["名前"];
		$comment=$_POST["コメント"];
		$date=date("c");
		$password=$_POST["password"];
		$flag = $stmt->execute();
		if($flag)$correct=2;
		else $miss=5;

	}else if((!empty($_POST["名前"])) || (!empty($_POST["コメント"]))){//新規投稿時
		$sql = 'select id, name, comment, date, password from banana';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		$maxid=0;
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($maxid<$result['id'])$maxid=$result['id'];//掲示板に存在する最大の投稿番号を取得
		}
		
		//bananaテーブルにデータを追加
		$stmt = $pdo->prepare("INSERT INTO banana (id, name, comment, date, password ) VALUES (:id, :name, :comment, :date, :password)");
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
		$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
		$stmt -> bindParam(':password', $password, PDO::PARAM_STR);
		$id=$maxid+1;
		$name=$_POST["名前"];
		$comment=$_POST["コメント"];
		$date=date("c");
		$password=$_POST["password"];
		$flag = $stmt->execute();
		if($flag) $correct=3;
		else $miss=6;
	
	}else if(!empty($_POST["delete"])){//削除ボタンを押したとき
		$sql = 'select id from banana';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$maxid=0;
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) $maxid=$result['id'];//掲示板に存在する最大の投稿番号を取得
		if($_POST["delete"]<=$maxid){//削除したい行番号が掲示板内の最大の投稿番号以下であるとき
			print "check deletepassword time<br>";//パスワード確認
?>
			<form action="" method="post"/>
			パスワードの確認:<br/>
			<input type="text" name="deletepassword"/>
			<input type="hidden" name="checkdelete" value="<?php echo $_POST["delete"] ?>">
			<input type="submit" value="送信"/>
			</form>
<?php
		}else $miss=2;
	}else if(!empty($_POST["edit"])){//編集ボタンを押したとき
		$sql = 'select id from banana';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$maxid=0;
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			$maxid=$result['id'];//掲示板に存在する最大の投稿番号を取得
		}
		if($_POST["edit"]<=$maxid){//編集したい行番号が掲示板内の最大の投稿番号以下であるとき
			print "check editpassword time<br>";//パスワード確認
?>
		<form action="" method="post"/>
		パスワードの確認:<br/>
		<input type="text" name="editpassword"/>
		<input type="hidden" name="checkedit" value="<?php echo $_POST["edit"] ?>">
		<input type="submit" value="送信"/>
		</form>
<?php
		}else $miss=3;
	}
	if(!empty($_POST["deletepassword"])){//削除機能を使う用のパスワードが入力されたとき
		$sql = 'select id, name, comment, date, password from banana';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($result['id'] == $_POST["checkdelete"]){
				if($_POST["deletepassword"]==$result['password'])$de=1;//パスワード一致
			}
		}
		if($de==1){
			$sql = 'DELETE FROM banana where id =:id';//bananaテーブルの指定したidのデータを削除
			$stmt = $pdo->prepare($sql);
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$id=$_POST["checkdelete"];
			$flag = $stmt->execute();
			if($flag)$correct=1;
			else $miss=4;
			
		}else $miss=1;

		if($correct==1){
			$sql = 'select id, name, comment, date, password from banana';//投稿番号と日時を調整
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
	
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				if($result['id']>$_POST["checkdelete"]){
					$sqle = 'update banana set id =:id where date =:date';
					$stmte = $pdo->prepare($sqle);
					$stmte -> bindParam(':id', $id, PDO::PARAM_INT);
					$stmte -> bindParam(':date', $date, PDO::PARAM_STR);
					$id=$result['id']-1;
					$date= $result['date'];
					$flag = $stmte->execute();
					if($flag)$correct=1;
					else $miss=4;
				}
			}
		}
	}
	switch($correct){
		case 1:
			echo "削除完了しました<br>";
			break;
		case 2:
			echo "編集完了しました<br>";
			break;
		case 3:
			echo "投稿完了しました<br>";
			break;
	}
	switch($miss){
		case 1:
			echo "パスワードが違います<br>";
			break;
		case 2:
			echo "その削除番号は存在しません<br>";
			break;
		case 3:
			echo "その編集番号は存在しません<br>";
			break;
		case 4:
			echo "削除失敗しました<br>";
			break;
		case 5:
			echo "編集失敗しました<br>";
			break;
		case 6:
			echo "投稿失敗しました<br>";
			break;
	}

	
		$sql = 'select id, name, comment, date, password from banana order by id';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		
		//データを表示
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			print('番号:'.$result['id'].'  ');
			print('名前:'.$result['name'].'<br>');
			print('投稿時刻:'.$result['date'].'<br>');
	        	print('コメント:'.$result['comment'].'<br>');
			echo '<br>';
		}

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


?>

</body>
</html>