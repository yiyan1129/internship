<form action="" method="post">//�t�H�[���쐬
���O:<br/>
<input type="text" name="���O"/>
<br/>
�R�����g:<br/>
<textarea name="�R�����g" rows="5" cols="30"></textarea>
<input type="submit" value="���e"/>
</form>
<form action="" method="post">
�폜�ԍ�:<br/>
<input type="text" name="delate"/>
<input type="submit" value="�폜"/>
</form>

<?php

$filename='mission2.txt';

$dt=date("c");

if(!empty($_POST["delate"])){//�폜����Ƃ�
	$file=file($filename);
	$maxNumber=0;
	foreach($file as $value){
		$line = explode('<>',$value);
		if($maxNumber<$line[0])$maxNumber=$line[0];//�f���ɑ��݂���ő�̓��e�ԍ����擾
	}
	if($maxNumber==1){//�ő�̓��e�ԍ���1�̎�
		$fp=fopen($filename,'w');//�t�@�C����V�K�������݂ŊJ����
		fclose($fp);
	}else{//�ő�̓��e�ԍ���2�ȏ�̎�
		foreach($file as $value){
			$line = explode('<>',$value);
			if($_POST["delate"]!=$line[0]){//�폜�������s�̔ԍ��ƁA���݌��Ă���s�̔ԍ����Ⴄ�Ƃ�
				if($_POST["delate"]==1){//�폜�������s�̔ԍ���1�̂Ƃ�
					if($line[0]==2){//���݌��Ă���s�̔ԍ���2�̎�
						$fp=fopen($filename,'w');//2�s�ڂ���V�K�������݂ɂ��邱�Ƃ�1�s�ڂ��폜
					}else{
						$fp=fopen($filename,'a');//3�s�ȍ~�͒ǋL
					}
				}else{//�폜�������s�̔ԍ���2�ȏ�̎�
					if($line[0]==1) $fp=fopen($filename,'w');//1�s�ڂ�V�K��������
					else $fp=fopen($filename,'a');//2�s�ڂ���͒ǋL
				}
				if($_POST["delate"]<$line[0])$line[0]--;//�폜�����s�̕��A�폜�����s����̓��e�ԍ���-1����
				fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]");//�t�@�C���ɏ�������
				fclose($fp);
				if($_POST["delate"]<$line[0])$line[0]++;
			}
		}
	}

}else{//�V�K���e��
	$fp = fopen($filename,'a');

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
}
	$file=file($filename);

	//�t�@�C���̒��g��\��
	foreach($file as $value){
		$line = explode('<>',$value);
		print "�ԍ�:$line[0] ���O:$line[1] ����:$line[3]<br/>";
		print "�R�����g:$line[2]<br/>";
	}



?>