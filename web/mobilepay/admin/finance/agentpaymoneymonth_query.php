<?
//$thismenucode = "2k222";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("agentpaymoneymonth_query","agentpaymoneymonth_query.html");

$year = date("Y", mktime()) ;
$month = date("m", mktime()) ;
for($i=2;$i>=0;$i--){
	$arr_year[] = $year-$i;
}
$year = makeselect($arr_year,$year,$arr_year);

for($i=1;$i<=12;$i++){
	$arr_month[] = $i;
}
$month = makeselect($arr_month,$month,$arr_month);

$t->set_var("year",$year);
$t->set_var("month",$month);


// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);

$t->pparse("out", "agentpaymoneymonth_query");       # ������ҳ��


?>