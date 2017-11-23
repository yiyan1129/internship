//トップページ
<?php session_start(); ?>
<html>
<head><title>掲示板へようこそ</title></head>
<body>
<?php 
header('Content-Type: text/html; charset=UTF-8');
$_SESSION['login']=false;//ログイン状態初期化
$_SESSION['password']="";//記録していたパスワード初期化
if($_SESSION['error']){//エラーメッセージが配列内に存在していたら
	echo $_SESSION['error'];//出力して初期化
	$_SESSION['error']="";
}
?>
<h1>掲示板へようこそ</h1>
<a href="keijiban.php">掲示板ページ</a>
<hr>
ユーザー登録されていない方はこちら↓<br>
<a href="mailform.php">アカウント作成ページ</a>

</body>
</html>