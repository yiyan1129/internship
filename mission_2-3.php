<form action="" method="post">//�t�H�[���쐬
���O:<br/>
<input type="text" name="���O"/>
<br/>
�R�����g:<br/>
<textarea name="�R�����g" rows="5" cols="30"></textarea>
<input type="submit" name="���M"/>
</form>

<?php

$filename='mission2.txt';

$fp = fopen($filename,'a');

$dt=date("c");

$file=file($filename);

$maxNumber=0;

foreach($file as $value){//���e�ԍ��ɂ���
	$line = explode('<>',$value);
	if($maxNumber<$line[0])$maxNumber=$line[0];//�f���ɑ��݂���ő�̓��e�ԍ����擾
}

$nextNumber=$maxNumber+1;//�ŐV�̓��e�ԍ������߂�

if(($_POST["���O"]) && isset($_POST["�R�����g"])){
	fwrite($fp,"$nextNumber<>{$_POST["���O"]}<>{$_POST["�R�����g"]}<>$dt\n");//�t�@�C���ɏ�������
}
if(''===$_POST["���O"]) print "���O�������͂ł�<br/>";
if(''===$_POST["�R�����g"]) print "�R�����g�������͂ł�<br/>";

fclose($fp);

$file=file($filename);

//�t�@�C���̒��g��\��
foreach($file as $value){
	$line = explode('<>',$value);
	print "�ԍ�:$line[0] ���O:$line[1] ����:$line[3]<br/>";
	print "�R�����g:$line[2]<br/>";
}

?>
