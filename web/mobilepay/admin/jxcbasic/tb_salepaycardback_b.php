<?
$thismenucode = "2k402";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","tb_salepaycardback_b.html");



$arr_text = array("����",'���','����',"�豸��","��Ӧ��","��Ʒ����","�ɹ��۸�","��ˢ������","�˻�ʱ��","�˻�ԭ��");


for($i=0;$i<count($arr_text);$i++)
{

	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
	
}
$t->set_var("theadth"     , $theadth     ); 


$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ  
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //����������



?>