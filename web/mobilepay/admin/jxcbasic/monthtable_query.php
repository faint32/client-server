<?
$thismenucode = "2k112";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("yeartable_query","monthtable_query.html");

$year = date("Y", mktime()) ;
for($i=5;$i>=0;$i--){
	$arr_year[] = $year-$i;
}
$year = makeselect($arr_year,$year,$arr_year);

$t->set_var("year",$year);

// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);

$t->pparse("out", "yeartable_query");       # ������ҳ��


?>