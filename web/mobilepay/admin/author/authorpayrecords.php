<?
$thismenucode = "2k302";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep"); //����һ��ģ��
	$arr_type=explode("@",$type);

$t->set_file("authorpayrecords", "authorpayrecords.html");
if($arr_type[0]=="use")
{
	$arr_text = array (
		"ˢ����",
		"��������",
		"����״̬",	
		"����ʱ��",	
		"���׽��",	
		"������"
	);
}else{
	$arr_text = array (
		"������",
		"�������",
		"����ʱ��"
	);
}
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
if($scope=="creditcard"){$title="���ÿ�";}
if($scope=="bankcard"){$title="��ǿ�";}
$t->set_var("authorid", $authorid);
$t->set_var("scope", $scope);
$t->set_var("scdmsetid", $scdmsetid);
$t->set_var("type", $type);
$t->set_var("title", $title);
$t->set_var("theadth", $theadth);
$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "authorpayrecords"); # ������ҳ��
?>

