<?php
$dsn = '�f�[�^�x�[�X��';
$user = '���[�U�[��';
$password = '�p�X���[�h';
try{
	//�f�[�^�x�[�X�ɐڑ�
	$pdo = new PDO($dsn, $user, $password);
	//URL����id�擾
	$id1=$_GET['no'];
	//�f���e�[�u�����炻��id�̏����擾
	$sql = "SELECT * FROM `keijiban` WHERE `id`=$id1";
	$result = $pdo->query($sql);

	//id�Ɠ���f�[�^�Ɗg���q���擾
	foreach($result as $row){
		$id=$row['id'];
		$imgdat=$row['imgdat'];
		$mime=$row['mime'];
	}

	//����\��
	header('Content-Type: video/mp4');
	echo $row['imgdat'];

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();

}

?>