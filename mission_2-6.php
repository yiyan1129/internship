<?php

$filename='mission2.txt';
$file=file($filename);
$dt=date("c");


if(!empty($_POST["deletepassword"])){//�폜�@�\���g���p�̃p�X���[�h�����͂��ꂽ�Ƃ�
	foreach($file as $value){
		$line = explode('<>',$value);
		if($_POST["checkdelete"]==$line[0]){
			if($_POST["deletepassword"]==trim($line[4])){//�p�X���[�h����v�����Ƃ�
				$de=1;
			}
		}
	}
	if($de==1){//�p�X���[�h����v�����̂ō폜�@�\���g�p
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
				if($_POST["checkdelete"]!=$line[0]){//�폜�������s�̔ԍ��ƁA���݌��Ă���s�̔ԍ����Ⴄ�Ƃ�
					if($_POST["checkdelete"]==1){//�폜�������s�̔ԍ���1�̂Ƃ�
						if($line[0]==2){//���݌��Ă���s�̔ԍ���2�̎�
							$fp=fopen($filename,'w');//2�s�ڂ���V�K�������݂ɂ��邱�Ƃ�1�s�ڂ��폜
						}else{
							$fp=fopen($filename,'a');//3�s�ȍ~�͒ǋL
						}
					}else{
						if($line[0]==1) $fp=fopen($filename,'w');//�폜�������s�̔ԍ���2�ȏ�̎�
						else $fp=fopen($filename,'a');//2�s�ڂ���͒ǋL
					}
					if($_POST["checkdelete"]<$line[0]) $line[0]--;//�폜�����s�̕��A�폜�����s����̓��e�ԍ���-1����
					fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]<>$line[4]");//�t�@�C���ɏ�������
					fclose($fp);
					if($_POST["checkdelete"]<$line[0]) $line[0]++;
				}
			}
		}
		$correct=1;
	}else $miss=1;

}else if(!empty($_POST["editpassword"])){//�ҏW�@�\���g���p�̃p�X���[�h�����͂��ꂽ�Ƃ�
	foreach($file as $value){
		$line = explode('<>',$value);
		if($_POST["checkedit"]==$line[0]){
			if($_POST["editpassword"]==trim($line[4])){//�p�X���[�h����v
				$ed=1;
			}
		}
	}
	if($ed==1){
		for($i=0;$i<count($file);$i++){
			$ediData = explode('<>',$file[$i]);
			if($ediData[0]==$_POST["checkedit"]){//�ҏW�������s�ԍ��ƌ��݌��Ă���s�ԍ�����v�����Ƃ�
				for($j=0;$j<count($ediData);$j++){
					$simEdit[$j]=trim($ediData[$j]);//�z��simEdit�ɓ��e�ԍ��A���O�A�R�����g�A�����A�p�X���[�h������
				}
			}
		}
	}else $miss=1;
}
?>
<form action="" method="post">//�t�H�[���쐬
���O:<br/>
<input type="text" name="���O" value="<?php echo $simEdit[1]; ?>">//�ҏW�����͍Ϗ�Ԃɂ���
<br/>
�R�����g:<br/>
<textarea name="�R�����g" rows="5" cols="30" ><?= $simEdit[2] ?></textarea>//�ҏW�����͍Ϗ�Ԃɂ���
<input type="hidden" name="editnum" value="<?php echo $simEdit[0] ?>">
<br/>
�p�X���[�h:<br/>
<input type="text" name="password">
<input type="submit" value="���e"/>
</form>
<form action="" method="post">
�폜�ԍ�:<br/>
<input type="text" name="delete"/>
<input type="submit" name="delsub" value="�폜"/>
</form>
<form action="" method="post">
�ҏW�ԍ�:<br/>
<input type="text" name="edit"/>
<input type="submit" name="edisub" value="�ҏW"/>
</form>
<?php
if(!(""==$_POST["editnum"])){//�ҏW���̓��͍Ϗ�Ԃ���ҏW���ē��e�����ꍇ
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==1) $fp=fopen($filename,'w');//�ҏW�������s�ԍ���1�ł������Ƃ��V�K�������݂Ńt�@�C�����J��
		else $fp=fopen($filename,'a');//����ȊO�͒ǋL�Ńt�@�C�����J��
		if($_POST["editnum"]!=$line[0]) fwrite($fp,"$line[0]<>$line[1]<>$line[2]<>$line[3]<>$line[4]");//�ҏW�����Ƃ���ȊO�t�@�C����������
		else fwrite($fp,"$line[0]<>{$_POST["���O"]}<>{$_POST["�R�����g"]}<>$dt<>{$_POST["password"]}\n");//�ҏW�����Ƃ�����t�@�C���ɏ�������
		fclose($fp);
	}
	$correct=2;
}else if(empty($_POST["delete"]) && empty($_POST["edit"])){//�V�K���e��
	$fp = fopen($filename,'a');

	$maxNumber=0;

	foreach($file as $value){//���e�ԍ��ɂ���
		$line = explode('<>',$value);
		if($maxNumber<$line[0])$maxNumber=$line[0];//�f���ɑ��݂���ő�̓��e�ԍ����擾
	}

	$nextNumber=$maxNumber+1;//�ŐV�̓��e�ԍ������߂�

	if(($_POST["���O"]) && ($_POST["�R�����g"]) && ($_POST["password"])){
		fwrite($fp,"$nextNumber<>{$_POST["���O"]}<>{$_POST["�R�����g"]}<>$dt<>{$_POST["password"]}\n");//�t�@�C����������
	}
	if(''===$_POST["���O"]) print "���O�������͂ł�<br/>";
	if(''===$_POST["�R�����g"]) print "�R�����g�������͂ł�<br/>";
	if(''===$_POST["password"]) print "�p�X���[�h�������͂ł�<br/>";

	fclose($fp);
}


if(!empty($_POST["delete"])){//�폜�{�^�����������Ƃ�
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==$_POST["delete"])$flag=1//�폜�������s�ԍ����f�����ɑ��݂���;
	}
	if($flag==1){//�p�X���[�h�̊m�F
		print "check deletepassword time<br>";
?>
		<form action="" method="post"/>
		�p�X���[�h�̊m�F:<br/>
		<input type="text" name="deletepassword"/>
		<input type="hidden" name="checkdelete" value="<?php echo $_POST["delete"] ?>">
		<input type="submit" value="���M"/>
		</form>
<?php
	}else $miss=2;
}else if(!empty($_POST["edit"])){//�ҏW�{�^�����������Ƃ�
	foreach($file as $value){
		$line = explode('<>',$value);
		if($line[0]==$_POST["edit"])$flag=1;//�ҏW�������s�ԍ����f�����ɑ��݂���
	}
	if($flag==1){//�p�X���[�h�̊m�F
		print "check editpassword time<br>";
?>
		<form action="" method="post"/>
		�p�X���[�h�̊m�F:<br/>
		<input type="text" name="editpassword"/>
		<input type="hidden" name="checkedit" value="<?php echo $_POST["edit"] ?>">
		<input type="submit" value="���M"/>
		</form>
<?php
	}else $miss=3;
}

switch($correct){//���������ꍇ
	case 1:
		echo "�폜�������܂���<br>";
		break;
	case 2:
		echo "�ҏW�������܂���<br>";
		break;
}
switch($miss){//�G���[���o���ꍇ
	case 1:
		echo "�p�X���[�h���Ⴂ�܂�<br>";
		break;
	case 2:
		echo "���̍폜�ԍ��͑��݂��܂���<br>";
		break;
	case 3:
		echo "���̕ҏW�ԍ��͑��݂��܂���<br>";
		break;
}

$file=file('mission2.txt');

//�t�@�C���̒��g��\��
foreach($file as $value){
	$line = explode('<>',$value);
	print "�ԍ�:$line[0] ���O:$line[1] ����:$line[3]<br>";
	print "�R�����g:$line[2]<br>";
}


?>


