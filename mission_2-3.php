<form action="" method="post">//フォーム作成
名前:<br/>
<input type="text" name="名前"/>
<br/>
コメント:<br/>
<textarea name="コメント" rows="5" cols="30"></textarea>
<input type="submit" name="送信"/>
</form>

<?php

$filename='mission2.txt';

$fp = fopen($filename,'a');

$dt=date("c");

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

$file=file($filename);

//ファイルの中身を表示
foreach($file as $value){
	$line = explode('<>',$value);
	print "番号:$line[0] 名前:$line[1] 時刻:$line[3]<br/>";
	print "コメント:$line[2]<br/>";
}

?>
