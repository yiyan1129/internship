<?php

$filename='mission2.txt';
$file=file($filename);
$dt=date("c");


if(!empty($_POST["deletepassword"])){//削除機能を使う用のパスワードが入力されたとき
	foreach($file as $value){
		$line = explode('<>',$value);
		if($_POST["checkdelete"]==$line[0]){
			if($_POST["deletepassword"]==trim($line[4])){//パスワードが一致したとき
				$de=1;
			}
		}
	}
	if($de==1){//パスワードが一致したので削除機能を使用
		$maxNumber=0;
		foreach($file as $value){
			$line = explode('<>',$value);
			if($maxNumber<$line[0])$maxNumber=$line[0];//掲示板に存在する最大の投稿番号を取得
		}
		if($maxNumber==1){//最大の投稿番号が1の時
			$fp=fopen($filename,'w');//ファイルを新規書き込みで開ける
			fclose($fp);
		}else{//最大の投稿番号が2以上の時
			foreach($file as $value){
				$line = explode('<>',$value);
				if($_POST["checkdelete"]!=$line[0]){//削除したい行の番号と、現在見ている行の番号が違うとき
					if($_POST["checkdelete"]==1){//削除したい行の番号が1のとき
						if($line[0]==2){//現在見ている行の番号が2の時
							$fp=fopen($filename,'w');//2行目から新規書き込みにすることで1行目を削除
						}else{
							$fp=fopen($filename,'a');//3行以降は追記
						}
					}else{
						if($line[0]==1) $fp=fopen($filename,'w');//削除したい行の番号が2以上の時
						else $fp=fopen($filename,'a');//2行目より後は追記
					}
					if($_POST["checkdelete"]<$line[0]) $line[0]--;//削除した行の分、削除した行より後の投稿番号を-1する
					fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]<>$line[4]");//ファイルに書き込み
					fclose($fp);
					if($_POST["checkdelete"]<$line[0]) $line[0]++;
				}
			}
		}
		$correct=1;
	}else $miss=1;

}else if(!empty($_POST["editpassword"])){//編集機能を使う用のパスワードが入力されたとき
	foreach($file as $value){
		$line = explode('<>',$value);
		if($_POST["checkedit"]==$line[0]){
			if($_POST["editpassword"]==trim($line[4])){//パスワードが一致
				$ed=1;
			}
		}
	}
	if($ed==1){
		for($i=0;$i<count($file);$i++){
			$ediData = explode('<>',$file[$i]);
			if($ediData[0]==$_POST["checkedit"]){//編集したい行番号と現在見ている行番号が一致したとき
				for($j=0;$j<count($ediData);$j++){
					$simEdit[$j]=trim($ediData[$j]);//配列simEditに投稿番号、名前、コメント、日時、パスワードを入れる
				}
			}
		}
	}else $miss=1;
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
<input type="submit" name="delsub" value="削除"/>
</form>
<form action="" method="post">
編集番号:<br/>
<input type="text" name="edit"/>
<input type="submit" name="edisub" value="編集"/>
</form>
<?php
if(!(""==$_POST["editnum"])){//編集時の入力済状態から編集して投稿した場合
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==1) $fp=fopen($filename,'w');//編集したい行番号が1であったとき新規書き込みでファイルを開く
		else $fp=fopen($filename,'a');//それ以外は追記でファイルを開く
		if($_POST["editnum"]!=$line[0]) fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]<>$line[4]");//編集したところ以外ファイル書き込み
		else fwrite($fp,"$line[0]<>{$_POST["名前"]}<>{$_POST["コメント"]}<>$dt<>{$_POST["password"]}\n");//編集したところをファイルに書き込み
		fclose($fp);
	}
	$correct=2;
}else if(empty($_POST["delete"]) && empty($_POST["edit"])){//新規投稿時
	$fp = fopen($filename,'a');

	$maxNumber=0;

	foreach($file as $value){//投稿番号について
		$line = explode('<>',$value);
		if($maxNumber<$line[0])$maxNumber=$line[0];//掲示板に存在する最大の投稿番号を取得
	}

	$nextNumber=$maxNumber+1;//最新の投稿番号を決める

	if(($_POST["名前"]) && ($_POST["コメント"]) && ($_POST["password"])){
		fwrite($fp,"$nextNumber<>{$_POST["名前"]}<>{$_POST["コメント"]}<>$dt<>{$_POST["password"]}\n");//ファイル書き込み
	}
	if(''===$_POST["名前"]) print "名前が未入力です<br/>";
	if(''===$_POST["コメント"]) print "コメントが未入力です<br/>";
	if(''===$_POST["password"]) print "パスワードが未入力です<br/>";

	fclose($fp);
}


if(!empty($_POST["delete"])){//削除ボタンを押したとき
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==$_POST["delete"])$flag=1//削除したい行番号が掲示板内に存在する;
	}
	if($flag==1){//パスワードの確認
		print "check deletepassword time<br>";
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
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==$_POST["edit"])$flag=1;//編集したい行番号が掲示板内に存在する
	}
	if($flag==1){//パスワードの確認
		print "check editpassword time<br>";
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

switch($correct){//成功した場合
	case 1:
		echo "削除完了しました<br>";
		break;
	case 2:
		echo "編集完了しました<br>";
		break;
}
switch($miss){//エラーが出た場合
	case 1:
		echo "パスワードが違います<br>";
		break;
	case 2:
		echo "その削除番号は存在しません<br>";
		break;
	case 3:
		echo "その編集番号は存在しません<br>";
		break;
}

$file=file('mission2.txt');

//ファイルの中身を表示
foreach($file as $value){
	$line = explode('<>',$value);
	print "番号:$line[0] 名前:$line[1] 時刻:$line[3]<br>";
	print "コメント:$line[2]<br>";
}


?>


