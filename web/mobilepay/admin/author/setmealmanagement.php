<?
$thismenucode = "10n010";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep"); //����һ��ģ��


$t->set_file("setmealmanagement", "setmealmanagement.html");
$arr_text = array (
	"�����ײ�",
	"�̻�����",
	"�Ѱ��̻�����",	
	"�������(��)",	
	"�������",	
	"����Χ",	
	"ÿ���޶�",	
	"ÿ�ջ�ÿ��ˢ������",
	"���������(��)",
	"����������"
);
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}

$arr_text1 = array (
	"�����ײ�",
	"�̻�����",
	"�Ѱ��̻�����",
	"ˢ��������",	
	"��������",	
	"���ʿۿ��",	
	"��������",
	"�̶�������(Ԫ)",	
	"��ȡ���ʣ�%��",	
	"��ͷ��ʶ�",
	"��߷��ʶ�"
);
$theadth1 = "<thead>";
for ($i = 0; $i < count($arr_text1); $i++) {

	$theadth1 .= ' <th>' . $arr_text1[$i] . '</th>';
}

$t->set_var("theadth", $theadth);

$t->set_var("theadth1", $theadth1);


$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "setmealmanagement"); # ������ҳ��
?>

