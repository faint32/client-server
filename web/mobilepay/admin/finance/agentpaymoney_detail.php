<?php
require("../include/common.inc.php");

$db=new db_test;
$t = new Template(".","keep");
$t->set_file("template","agentpaymoney_detail.html");
if(!empty($type)){
	switch ($type){
		case "all":
			$where = "and (fd_pymylt_state ='9' or fd_pymylt_state ='3')";
			$tabtype="�ܴ�����";
		break;
		case "ysp":
			$where = "and fd_pymylt_state ='9'";
			$tabtype="�Ѻ˶Գ���";
		break;
		case "wsp":
			$where ="and fd_pymylt_state ='3'";
			$tabtype="δ�˶Գ���";
		break;
	}
}
if(!empty($typekind)){
	switch ($typekind){
		case "pay":
			$thname="֧����";
		break;
		case "cost":
			$thname="�ɱ�";
		break;
		case "fee":
			$thname="������";
		break;
	}
}
if(!empty($datetype)){
	switch($datetype){
		case "year":
		$gourl='agentpaymoneyyear_view.php?year='.$year.'&month='.$month;
		$title="�ʽ�����걨��";
		break;
		case "month":
		$title="�ʽ�����±���";
		$gourl='agentpaymoneymonth_view.php?year='.$year.'&month='.$month;
		break;
	}
}
$gotourl = $gourl.$tempurl ;

$arr_text = array('���','����������',"��������","��������","{thname}","�ն��̻�","�տ���<br>�˺�","�տ���<br>��������","�տ�<br>������","�տ���<br>�ֻ�","ˢ����<br>�豸��");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}

$t->set_var("theadth" , $theadth );

$t->set_var("gotourl",$gotourl);// ת�õĵ�ַ
$t->set_var("error",$error);
$t->set_var("allmoney",$allmoney);
$t->set_var("skin",$loginskin);
$t->set_var("month",$month);
$t->set_var("year",$year);
$t->set_var("thname",$thname);
$t->set_var("title",$title);
$t->set_var("tabtype",$tabtype);
$t->set_var("type",$type);
$t->set_var("typekind",$typekind);
$t->set_var("listdate",$listdate);
$t->set_var("datetype",$datetype);

// �ж�Ȩ��
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");// ����������
?>