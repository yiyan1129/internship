<?php
if($_GET['no']){
$dsn = '�f�[�^�x�[�X��';
$user = '���[�U�[��';
$password = '�p�X���[�h';
try{
	//�f�[�^�x�[�X�ɐڑ�
	$pdo = new PDO($dsn, $user, $password);
	//URL����id�擾
	$id1=$_GET['no'];
	//�f���e�[�u�����炻��id�̏����擾
	$sql = "SELECT * FROM `keijiban` WHERE `id`={$id1}";
	$result = $pdo->query($sql);

	//id�Ɖ摜�f�[�^�Ɗg���q���擾
	foreach($result as $row){
		$id=$row['id'];
		$imgdat=$row['imgdat'];
		$mime=$row['mime'];
	}
	//�摜�\��
	if($_GET['mime']=="jpg")$mime="image/jpeg";
	header("Content-Type: $mime");
	echo $row['imgdat'];

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();

}
}
?>