<?php

$filename='mission2.txt';

$dt=date("c");

if(!empty($_POST["delate"])){//削除するとき
	$file=file($filename);
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
			if($_POST["delate"]!=$line[0]){//削除したい行の番号と、現在見ている行の番号が違うとき
				if($_POST["delate"]==1){//削除したい行の番号が1のとき
					if($line[0]==2){//現在見ている行の番号が2の時
						$fp=fopen($filename,'w');//2行目から新規書き込みにすることで1行目を削除
					}else{
						$fp=fopen($filename,'a+');//3行以降は追記
					}
				}else{
					if($line[0]==1) $fp=fopen($filename,'w');//削除したい行の番号が2以上の時
					else $fp=fopen($filename,'a+');//2行目より後は追記
				}
				if($_POST["delate"]<$line[0])$line[0]--;//削除した行の分、削除した行より後の投稿番号を-1する
				fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]");//ファイルに書き込み
				fclose($fp);
				if($_POST["delate"]<$line[0])$line[0]++;
			}
		}
	}
}else if(!empty($_POST["edit"])){//編集時
	$file=file($filename);
	for($i=0;$i<count($file);$i++){
		$ediData = explode('<>',$file[$i]);
		if($ediData[0]==$_POST["edit"]){//編集したい行番号と現在見ている行番号が一致したとき
			for($j=0;$j<count($ediData);$j++){
				$simEdit[$j]=trim($ediData[$j]);//配列simEditに投稿番号、名前、コメント、日時を入れる
			}
		}
	}

}
?>
<form action="" method="post">//フォーム作成
名前:<br/>
<input type="text" name="名前" value="<?php echo $simEdit[1]; ?>">//編集時入力済状態にする
<br/>
コメント:<br/>
<textarea name="コメント" rows="5" cols="30" ><?= $simEdit[2] ?></textarea>//編集時入力済状態にする
<input type="hidden" name="editnum" value="<?php echo $simEdit[0] ?>">
<input type="submit" value="投稿"/>
</form>
<form action="" method="post">
削除番号:<br/>
<input type="text" name="delate"/>
<input type="submit" value="削除"/>
</form>
<form action="" method="post">
編集番号:<br/>
<input type="text" name="edit"/>
<input type="submit" value="編集"/>
</form>
<?php
if(!(""==$_POST["editnum"])){//編集時の入力済状態から編集して投稿した場合
	$file=file($filename);
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==1) $fp=fopen($filename,'w');//編集したい行番号が1であったとき新規書き込みでファイルを開く
		else $fp=fopen($filename,'a');//それ以外は追記でファイルを開く
		if($_POST["editnum"]!=$line[0]) fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]");//編集したところ以外ファイル書き込み	
		else fwrite($fp,"$line[0]<>{$_POST["名前"]}<>{$_POST["コメント"]}<>$dt\n");//編集したところをファイルに書き込み
		fclose($fp);
	}
}else if(empty($_POST["delate"]) && empty($_POST["edit"])){//新規投稿時
	$fp = fopen($filename,'a');

	$file=file($filename);

	$maxNumber=0;

	foreach($file as $value){//投稿番号について
		$line = explode('<>',$value);
		if($maxNumber<$line[0])$maxNumber=$line[0];//掲示板に存在する最大の投稿番号
	}

	$nextNumber=$maxNumber+1;//最新の投稿番号を決める

	if(($_POST["名前"]) && isset($_POST["コメント"])){
		fwrite($fp,"$nextNumber<>{$_POST["名前"]}<>{$_POST["コメント"]}<>$dt\n");//ファイル書き込み
	}
	if(''===$_POST["名前"]) print "名前が未入力です<br/>";
	if(''===$_POST["コメント"]) print "コメントが未入力です<br/>";

	fclose($fp);
}

	$file=file('mission2.txt');

	//ファイルの中身を表示
	foreach($file as $value){
		$line = explode('<>',$value);
		print "番号:$line[0] 名前:$line[1] 時刻:$line[3]<br/>";
		print "コメント:$line[2]<br/>";
	}
?>


