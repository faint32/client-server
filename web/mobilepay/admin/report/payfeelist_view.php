<?php
$thismenucode = "2k302";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","payfeelist_view.html");


$arr_paytype=getauthorpaycardmenu();

$query="select sum(fd_agpm_payfee) as allpayfee,sum(fd_agpm_payfee-fd_agpm_sdcrpayfeemoney-fd_agpm_sdcragentfeemoney) as allyingli from tb_agentpaymoneylist where fd_agpm_payrq='00'   ";
$db->query($query);
$arr_data=$db->get_one($query);

$arr_text = array('ˢ����','��������','�������',"���һ��ˢ��ʱ��");

foreach($arr_paytype as $value)
{
	$arr_text[]=$value;
}
$arr_text1 = array("������","�����");
for($i=0;$i<count($arr_text);$i++)
{
	$max=count($arr_text)-1;

	if(3<$i)
	{
		$colspan="colspan=2";
		foreach($arr_text1 as $value)
		{
			$theadth1 .=' <th onclick="changeorderby(this,\''.$i.'\')">'.$value.'</th>';
		}
	}else{
		$colspan="";
		$theadth1 .='<th onclick="changeorderby(this,\''.$i.'\')"></th>';
	}
	$theadth .=' <th '.$colspan.'>'.$arr_text[$i].'</th>';
}
$t->set_var("theadth"     , $theadth     );
$t->set_var("theadth1"     , $theadth1     );
$t->set_var("allpayfee"     , $arr_data['allpayfee']     );
$t->set_var("allyingli"     , $arr_data['allyingli']     );
$t->set_var("listid",$listid);
$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );// ת�õĵ�ַ
// �ж�Ȩ��
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //����������
?>