<?php
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;

$t = new Template(".","keep");
$t->set_file("template","consp_data.html");

if($type=="paycard")
{
	$name='ˢ�����豸��';
	$gourl='paycardconsp_view.php';
	$title='���񱨱� -->ˢ��������ͳ����ϸ��';
}
elseif($type=="author")
{
	$name='�̻���';
	$gourl='authorconsp_view.php';
	$title='���񱨱� -->�̻�����ͳ����ϸ��';
}
elseif($type=="sdcr")
{
	$name='��ʢ����';
	$gourl='sdcrconsp_view.php';
	$title='���񱨱� -->��ʢ����ͳ����ϸ��';
}
elseif($type=="checkdetail")
{
	$name='ˢ�����豸��';
	$title='ˢ����������ϸ��';
	$display='style=display:none';
}
else
{
	$name='ˢ�����豸��';
	$gourl='payfeelist_view.php';
	$title='���񱨱� -->������ͳ����ϸ��';
}



$gotourl = $gourl.$tempurl ;
$arr_text = array($name,'����������',"��������","��������","���׽��","������","�����","��������");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}

if (empty($listid))
{
	$listid = '';
}

if (empty($paytype))
{
	$paytype = '';
}

$t->set_var("theadth" , $theadth );
$t->set_var("listid" , $listid );
$t->set_var("type" , $type );
$t->set_var("paytype" , $paytype );
$t->set_var("display" , $display );
$t->set_var("title" , $title);
$t->set_var("action" , $action );
$t->set_var("error" , $error );
$t->set_var("gotourl" , $gotourl );// ת�õĵ�ַ
// �ж�Ȩ��
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");// ����������
?>