<?php
require("../include/common.inc.php");

$db=new db_test;
$t = new Template(".","keep");
$t->set_file("template","payment_detail.html");

if($type=="day")
{
	
	$gourl='tb_payment_dateview.php';
	$title='��֧�ձ���';
}elseif($type=="month") {
	
	$gourl='tb_payment_monthview.php';
	$title='��֧�±���';
}else{
	
	$gourl='tb_payment_yearview.php';
	$title='��֧�걨��';
}



$gotourl = $gourl.$tempurl ;
$arr_text = array('ˢ�����豸��','����������',"��������","��������","���׽��","֧��","������","�����","��������");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}

$t->set_var("theadth" , $theadth );

$t->set_var("collect" ,$collect);
$t->set_var("datetime" ,$datetime);
$t->set_var("type" ,$type);
$t->set_var("paytype" , $paytype );


$t->set_var("title" , $title);
$t->set_var("action" , $action );
$t->set_var("error" , $error );
$t->set_var("gotourl" , $gotourl );// ת�õĵ�ַ
// �ж�Ȩ��
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");// ����������
?>