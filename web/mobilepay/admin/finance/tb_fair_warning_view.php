<?
$thismenucode = "10n003";
require ("../include/common.inc.php");

$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("agency_monthtable_view","tb_fair_warning_view.html");
$t->set_block("agency_monthtable_view", "BXBK", "bxbks");

$weekarray = array("��","һ","��","��","��","��","��");

if ($bgcolor=="#FFFFFF") {
	$bgcolor="#F1F4F9";
}else{
	$bgcolor="#FFFFFF";
}

$t->set_var("count",$count);
$t->set_var("error",$error);
$t->set_var("bgcolor",$bgcolor);

$t->set_var("skin",$loginskin);
$t->pparse("out", "agency_monthtable_view");    # ������ҳ��

?>