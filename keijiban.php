<?php session_start(); ?>
<html>
<head><title>簡易掲示板</title></head>
<body>
<?php
ini_set("session.bug_compat_42", 0);
header('Content-Type: text/html; charset=UTF-8');
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
//データベースに接続
$pdo = new PDO($dsn, $user, $password);

//ログイン状態ではない場合
if(!$_SESSION['login']){

	try{
		if($_POST["submit"]){//フォーム入力されたとき
			//idを取得し登録用テーブルからそのidの情報を得る
			$id=$_POST["id"];
			$sql = "SELECT * FROM `interim_registration` WHERE `id`='$id'";
			$result = $pdo->query($sql);		
			if($result){
				foreach($result as $row){
					if($_POST["password"]===$row['password']){//入力されたパスワードとテーブル上にあったパスワードが一致したとき
						if($row['reg_flag']=='2'){//本登録状態であれば
							$_SESSION['login']=true;//ログイン状態へ
							$_SESSION['name']=$row['username'];//ユーザー名とパスワードをセッションへ
							$_SESSION['password']=$row['password'];
						}
					}
				}
				if(!$row['id']){//idが存在しないとき
					$_SESSION['error']="入力されたIDは存在しません";
					header('location: toppage.php');
					exit();
				}else if(!$_SESSION['login']){//パスワードが一致せずログイン状態にならないとき
					$_SESSION['error']="パスワードが違います";
					header('location: toppage.php');
					exit();
				}
				if($row['reg_flag']!='2'){//本登録状態ではないとき
					if($row['reg_flag']=='1'){//仮登録状態のとき
						$_SESSION['error']="本登録がまだ完了していません。<br>メールから本登録を完了させてください。";
						header('location: toppage.php');
						exit();
					}
				}
			}else {
				$_SESSION['error']="データベース検索エラー";
				header('location: toppage.php');
				exit();
			}
	
		}


	}catch (PDOException $e){
	    print('Error:'.$e->getMessage());
	    die();
	}
}
//ログイン状態ではないとき
if(!$_SESSION['login']){
//ログインするための入力フォーム
?>
	ログインしてください。<br>
	<hr>
	<form action="" method="post">
	ID:<br/>
	<input type="text" name="id">
	<br/>
	パスワード:<br/>
	<input type="text" name="password">
	<input type="submit" name="submit" value="送信"/>
	</form>
<?php
}else{

try{
	//掲示板テーブルが存在しないとき作成
	$sql="CREATE TABLE IF NOT EXISTS `keijiban`"
		."("
		."`id` INT,"
		."`name` CHAR(20),"
		."`comment` CHAR(128),"
		."`date` DATETIME,"
		."`password` CHAR(20),"
		."`imgdat` LONGBLOB,"
		."`mime` VARCHAR(64)"
		.");";

	$stmt=$pdo->query($sql);

	//編集ボタンを押し、パスワードが入力された後
	if($_POST["checkeditsubmit"]){
		$sql = 'select id, name, comment, date, password from keijiban';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($result['id'] == $_POST["checkedit"]){//idが存在し
				if($_POST["editpassword"]==$result['password']){//パスワードが存在した時
					$simEdit[0]=$result['id'];
					$simEdit[1]=$result['name'];
					$simEdit[2]=$result['comment'];
				}
			}
		}
		if(empty($simEdit[0]))$miss=1;
	}
?>

<form action="" method="post" enctype="multipart/form-data">
名前:<br/>
<input type="text" name="name" value="<?php echo $_SESSION['name'] ?>">
<br/>
コメント:<br/>
<textarea name="comment" rows="5" cols="30" ><?= $simEdit[2] ?></textarea>//入力済状態
<input type="hidden" name="editnum" value="<?php echo $simEdit[0] ?>">//入力済状態
<br>
ファイル:<br/>
<input type="file" name="upfile">
<input type="submit" name="sub" value="投稿"/>
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

	if(!(""==$_POST["editnum"])){//入力済状態から編集されたとき、掲示板テーブルにある情報を更新
		$sql = 'update keijiban set name =:name, comment=:comment, date=:date, password=:password, imgdat=:imgdat, mime=:mime where id =:id';
		if(strlen($_FILES["upfile"]["name"])){
			$fp=fopen($_FILES["upfile"]["tmp_name"],"rb");
			if(!$fp)print"ファイル開けない<br>";
			$imgdat=fread($fp,filesize($_FILES["upfile"]["tmp_name"]));
			fclose($fp);
			$mime=pathinfo($_FILES["upfile"]["name"], PATHINFO_EXTENSION);
		}
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
		$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
		$stmt -> bindParam(':password', $password, PDO::PARAM_STR);
		$stmt -> bindValue(':imgdat', $imgdat, PDO::PARAM_LOB);
		$stmt -> bindValue(':mime', $mime, PDO::PARAM_STR);
		$id=$_POST["editnum"];
		$name=$_POST["name"];
		$comment=$_POST["comment"];
		$date=date("c");
		$password=$_SESSION['password'];
		$flag = $stmt->execute();
		if($flag)$correct=2;
		else $miss=5;

	}else if($_POST["sub"]){//投稿ボタンを押したとき
		if((!empty($_POST["name"])) && (!empty($_POST["comment"]))){//名前とコメントが入力されているとき掲示板テーブルに情報を登録
			$sql = 'select id, name, comment, date, password, imgdat, mime from keijiban';
			$stmt = $pdo->prepare($sql);
			$stmt->execute();

			$maxid=0;
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				if($maxid<$result['id'])$maxid=$result['id'];
			}

			if(strlen($_FILES["upfile"]["name"])){
				$fp=fopen($_FILES["upfile"]["tmp_name"],"rb");
				if(!$fp)print"ファイル開けない<br>";
				$imgdat=fread($fp,filesize($_FILES["upfile"]["tmp_name"]));
				fclose($fp);
				$mime=pathinfo($_FILES["upfile"]["name"], PATHINFO_EXTENSION);
			}
			$stmt = $pdo->prepare("INSERT INTO keijiban (id, name, comment, date, password, imgdat, mime ) VALUES (:id, :name, :comment, :date, :password, :imgdat, :mime )");
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
			$stmt -> bindParam(':password', $password, PDO::PARAM_STR);
			$stmt -> bindValue(':imgdat', $imgdat, PDO::PARAM_LOB);
			$stmt -> bindValue(':mime', $mime, PDO::PARAM_STR);
			$id=$maxid+1;
			$name=$_POST["name"];
			$comment=$_POST["comment"];
			$date=date("c");
			$password=$_SESSION['password'];
			$flag = $stmt->execute();
			if($flag) $correct=3;
			else $miss=6;
		}
		if(empty($_POST["name"])) print "名前を入力してください<br>";
		if(empty($_POST["comment"])) print "コメントを入力してください<br>";

	}else if(!empty($_POST["delete"])){//削除番号が入力され削除ボタンが押されたとき
		$sql = 'select id from keijiban';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$maxid=0;
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) $maxid=$result['id'];
		if($_POST["delete"]<=$maxid){//番号が今存在する投稿番号以下であるか確認
			print "check deletepassword time<br>";//パスワード確認
?>
			<form action="" method="post"/>
			パスワードの確認:<br/>
			<input type="text" name="deletepassword"/>
			<input type="hidden" name="checkdelete" value="<?php echo $_POST["delete"] ?>">
			<input type="submit" name="checkdeletesubmit"value="送信"/>
			</form>
<?php
		}else $miss=2;
	}else if(!empty($_POST["edit"])){//編集番号が入力され、編集番号が押されたとき
		$sql = 'select id from keijiban';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$maxid=0;
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			$maxid=$result['id'];
		}
		if($_POST["edit"]<=$maxid){//番号が今存在する投稿番号以下であるか確認
			print "check editpassword time<br>";//パスワード確認
?>
			<form action="" method="post"/>
			パスワードの確認:<br/>
			<input type="text" name="editpassword"/>
			<input type="hidden" name="checkedit" value="<?php echo $_POST["edit"] ?>">
			<input type="submit" name="checkeditsubmit" value="送信"/>
			</form>
<?php
		}else $miss=3;
	}
	if($_POST["checkdeletesubmit"]){//削除ボタン押された後のパスワードを入力し送信したとき
		$sql = 'select id, name, comment, date, password from keijiban';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($result['id'] == $_POST["checkdelete"]){
				if($_POST["deletepassword"]==$result['password'])$de=1;//入力されたパスワードが掲示板テーブル上にあるか確認
			}
		}
		if($de==1){//パスワード一致したとき削除
			$sql = 'DELETE FROM keijiban where id =:id';
			$stmt = $pdo->prepare($sql);
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$id=$_POST["checkdelete"];
			$flag = $stmt->execute();
			if($flag)$correct=1;
			else $miss=4;
			
		}else $miss=1;

		if($correct==1){//削除成功した後の掲示板の投稿番号の調整
			$sql = 'select id, name, comment, date, password from keijiban';
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
	
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				if($result['id']>$_POST["checkdelete"]){
					$sqle = 'update keijiban set id =:id where date =:date';
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

	//掲示板のデータ表示
	$sql = 'select id, name, comment, date, password, imgdat, mime from keijiban order by id';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	echo '<br>';

	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		print('番号:'.$result['id'].'  ');
		print('名前:'.$result['name'].'<br>');
		print('投稿時刻:'.$result['date'].'<br>');
        	print('コメント:'.$result['comment'].'<br>');
		print('パスワード:'.$result['password'].'<br>');
		$i=$result['id'];
		$mime=$result['mime'];
		if($result['mime']=="jpg"){
			print "image:<br>";
 			echo "<img src=\"image_display.php?no=".$i."&mime=".$mime."\">";
			print('<br>mime:'.$result['mime'].'<br>');
		}else if($result['mime']=="mp4"){
			print "video:<br>";
			print "<video src=\"video_display.php?no=".$i."&mime=".$mime."\" width=\"640\" height=\"360\" controls preload></video>";
			print('<br>mime:'.$result['mime'].'<br>');
		}else print "uperror";
		echo '<br>';
		echo '<br>';
	}

	

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


}
?>
<a href="toppage.php">トップページに戻る</a>
</body>
</html>