<?
$thismenucode = "2k302";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;

if($type=="check"){$gourl="tb_author_b.php";}
if($type=="author_sp"){$gourl="tb_author_sp_b.php";}
if($type=="checkdetail"){$isdisplay="none";$title='�鿴ˢ�����Ѱ����ÿ�';}else{$title='�鿴�̻��ѵǼ����ÿ�';}

$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","checkauthorbank.html");




$arr_text = array('������',"�󶨵�ˢ����",'����','��Ƭ����','�ֿ���',"���ö��","�˵���","������","��Ϣ��","��Ƭ������","ҵ������ʱ��","���п�����","��ʱ��");


for($i=0;$i<count($arr_text);$i++)
{

	$theadth .=' <th >'.$arr_text[$i].'</th>';
	
	
}
$t->set_var("theadth"     , $theadth     ); 

$t->set_var("type",$type); 
$t->set_var("title",$title); 
$t->set_var("isdisplay",$isdisplay);  
$t->set_var("listid",$listid);  
$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ  
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //����������


?>