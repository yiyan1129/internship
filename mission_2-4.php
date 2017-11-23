<form action="" method="post">//フォーム作成
名前:<br/>
<input type="text" name="名前"/>
<br/>
コメント:<br/>
<textarea name="コメント" rows="5" cols="30"></textarea>
<input type="submit" value="投稿"/>
</form>
<form action="" method="post">
削除番号:<br/>
<input type="text" name="delate"/>
<input type="submit" value="削除"/>
</form>

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
						$fp=fopen($filename,'a');//3行以降は追記
					}
				}else{//削除したい行の番号が2以上の時
					if($line[0]==1) $fp=fopen($filename,'w');//1行目を新規書き込み
					else $fp=fopen($filename,'a');//2行目より後は追記
				}
				if($_POST["delate"]<$line[0])$line[0]--;//削除した行の分、削除した行より後の投稿番号を-1する
				fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]");//ファイルに書き込み
				fclose($fp);
				if($_POST["delate"]<$line[0])$line[0]++;
			}
		}
	}

}else{//新規投稿時
	$fp = fopen($filename,'a');

	$file=file($filename);

	$maxNumber=0;

	foreach($file as $value){//投稿番号について
		$line = explode('<>',$value);
		if($maxNumber<$line[0])$maxNumber=$line[0];//掲示板に存在する最大の投稿番号を取得
	}

	$nextNumber=$maxNumber+1;//最新の投稿番号を決める

	if(($_POST["名前"]) && isset($_POST["コメント"])){
		fwrite($fp,"$nextNumber<>{$_POST["名前"]}<>{$_POST["コメント"]}<>$dt\n");//ファイルに書き込み
	}
	if(''===$_POST["名前"]) print "名前が未入力です<br/>";
	if(''===$_POST["コメント"]) print "コメントが未入力です<br/>";

	fclose($fp);
}
	$file=file($filename);

	//ファイルの中身を表示
	foreach($file as $value){
		$line = explode('<>',$value);
		print "番号:$line[0] 名前:$line[1] 時刻:$line[3]<br/>";
		print "コメント:$line[2]<br/>";
	}



?>