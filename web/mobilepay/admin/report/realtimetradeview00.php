<?
$thismenucode = "10n008";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;


$t = new Template(".","keep");
$t->set_file("template","realtimetradeview.html");


	

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("","���","ˢ�����豸��","ˢ��������","��Ա��","��Ա�绰","��ʢ�˻�","����ʱ��","״̬","��������","���׽��","�ѽ��״���","���׽��","�ۼ�����","����");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';	
}	
$t->set_var("theadth"     , $theadth     );    
$t->set_var("action",$action);
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //����������


?>