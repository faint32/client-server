<?
$thismenucode = "2n005";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("salerpushmoney_info","salerpushmoney_info.html"); 
$gourl = "salerpushmoney.php";

$gotourl = $gourl.$tempurl ;

$arr_text = array("��ˮ��","ˢ�����豸��","����","����","֧��","���׷�ʽ","����ժҪ","ʱ��");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';

}


$t->set_var("paycardid"     , $paycardid     );
$t->set_var("year"     , $year );
$t->set_var("month"     , $month );
$t->set_var("theadth"     , $theadth     ); 
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "salerpushmoney_info");    # ������ҳ��


?>

