<?
$thismenucode = "10n009";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep"); //����һ��ģ��

$t->set_file("transactionmonitoring", "transactionmonitoring.html");

//����pos
if(!empty($staruppaycardid))
{
	$query="update tb_paycard set fd_paycard_posstate ='2' where fd_paycard_id='$staruppaycardid'";
	$db->query($query);
}

$arr_text = array (
	"<INPUT onclick=CheckAll(this.form) type=checkbox 	 value=on name=chkall class='checkall'>",
	"�����̻�",
	"ˢ�������",
	"�����ײ�",
	"���ÿ����(��)",
	"������(��)",	
	"���ÿ���ˢ���(��)",	
	"�����ˢ���(��)",	
	"�Ƿ�Ԥ��",	
	"POS״̬",	
	"����"
);
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
$theadth .= "</thead>";
$arr_code=array("","1","0");
$arr_name=array("ѡ��","��","��");
$search_warning = makeselect($arr_name,$search_warning,$arr_code);

$t->set_var("theadth", $theadth);

$t->set_var("search_authorname", $search_authorname); // 
$t->set_var("search_begintime", $search_begintime); // 
$t->set_var("search_endtime", $search_endtime); // 
$t->set_var("search_time", $search_time); // 
$t->set_var("search_possate", $search_possate); // 
$t->set_var("search_warning", $search_warning); // 

$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ
$t->set_var("action", $action); // 

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "transactionmonitoring"); # ������ҳ��
?>

