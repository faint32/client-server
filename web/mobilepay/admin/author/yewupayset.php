<?
$thismenucode = "2k508";
require ("../include/common.inc.php");

$db = new db_test();
$db2 = new db_test();
$db3 = new db_test();
$gotourl = "yewupayset.php";
$t = new Template(".", "keep");

$t->set_file("template", "yewupayset.html");
$t->set_block("template", "BXBK", "BXBKs");

$arr_text = array (
	"���",
	"ҵ������",
	"���׹���",
	"����"
);

for ($i = 0; $i < count($arr_text); $i++) {
	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
$query = "select * from tb_creditcardset";
$arr_creditcset = $db->get_one($query);
if($arr_creditcset['fd_creditcset_whenpayfee']='post'){
	$showname='ͨ����ˢ��ʱ';
}else{$showname='֧�������ʱ';}
$arr_cinput['creditcardset'] = "
	�����ÿ�ˢ�����   <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_slotcardmoney'] . "</font></u>   Ԫ/�ʣ�
	�̻�ˢ��  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_dayslotcard'] . "</font></u>   ��/�£�
	�»������޶�  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_maxmoney'] . "</font></u>   Ԫ,
	�����������  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_monthslotcard'] . "</font></u>   ��/�£�";
if ($arr_creditcset['fd_creditcset_mode'] == 'fix') {
	$arr_cinput['creditcardset'] .= "�̶�������  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_fee'] . "</font></u>   Ԫ/�ʡ�";
} else {
	$arr_cinput['creditcardset'] .= "
	��ͷ��ʶ�  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_minfee'] . "</font></u>   ,
	��߷��ʶ�  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_maxfee'] . "</font></u>   ��
	��ȡ����  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_sqfee'] . "</font></u>   ��";
}
$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype = 'tfmg'";
$arr_transfermoneyset = $db->get_one($query);

$arr_cinput['transfermoneyset'] = "
	ת��ˢ�����   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_slotcardmoney'] . "</font></u>   Ԫ/�ʣ�
	ת��ˢ������   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_dayslotcardcount'] . "</font></u>   ��/�� ��   
	ת�����޶�   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_datemaxcount'] . "</font></u>   Ԫ,
	
	";
if ($arr_transfermoneyset['fd_transfermoneyset_mode'] == 'fix') {
	$arr_cinput['transfermoneyset'] .= "
		�̶�������   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_perfee'] . "</font></u>   Ԫ/�ʡ�";
} else {
	$arr_cinput['transfermoneyset'] .= "
		��ͷ��ʶ�   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_minperfee'] . "</font></u>   ��
		��߷��ʶ�   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_maxperfee'] . "</font></u>  �� 
		��ȡ����  <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_sqperfee'] . "</font></u>   ��";
}
$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype = 'suptfmg'";
$arr_transfermoneyset = $db->get_one($query);

$arr_cinput['suptransfermoneyset'] = "
	ת��ˢ�����   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_slotcardmoney'] . "</font></u>   Ԫ/�ʣ�
	ת��ˢ������   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_dayslotcardcount'] . "</font></u>   ��/�� ��   
	ת�����޶�   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_datemaxcount'] . "</font></u>   Ԫ,
	
	";
if ($arr_transfermoneyset['fd_transfermoneyset_mode'] == 'fix') {
	$arr_cinput['suptransfermoneyset'] .= "
		�̶�������   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_perfee'] . "</font></u>   Ԫ/�ʡ�";
} else {
	$arr_cinput['suptransfermoneyset'] .= "
		��ͷ��ʶ�   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_minperfee'] . "</font></u>   ��
		��߷��ʶ�   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_maxperfee'] . "</font></u>  �� 
		��ȡ����  <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_sqperfee'] . "</font></u>   ��";
}
$query = "select * from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
$arr_couponset = $db->get_one($query);
if($arr_couponset['fd_couponset_payfeedirct']=='post'){$showname='ͨ����ˢ��ʱ';}else{$showname='�ʽ������ʱ';}
$arr_cinput['couponset'] = "
	�������ȯ��ȡ����   <u><font color='#00CC00'>" . $arr_couponset['fd_couponset_fee'] . "</font></u> ��" .
	"����������޶�   <u><font color='#00CC00'>" . $arr_couponset['fd_couponset_maxfee'] . "</font></u> ��".
    "��������   <u><font color='#00CC00'>" . $arr_couponset['fd_arrive_name'] . "</font></u> ��
	���ʿ�ȡ�ڵ�	<u><font color='#00CC00'>" . $showname . "</font></u> ��
	";
$arr_contentno = array (
	"creditcardset",
	"transfermoneyset",
	"suptransfermoneyset",
	"repaymoneyset",
	"couponset"
);
$arr_contentname = array (
	"���ÿ�����",
	"��ͨת��",
	"����ת��",
	"�Ŵ�����",
	"���Ä�"
);
$v_i = 0;
foreach ($arr_contentno as $key => $value) {
	$v_i++;
	$authortypename = $arr_authortypename[$atypekey];
	//$paycardtypename = $arr_paycardtypename [$ptypekey];
	$paycardtypename = $arr_cinput[$value];
	$show .= "<tr><td class='center'>" . $v_i . "</td>";
	$show .= "<td class='center'>" . $arr_contentname[$key] . "</td>";
	if ($value == "repaymoneyset") {
		$show .= "<td>��û��ҵ�����</td>";
		$show .= "<td> </td>";
	} else {
		$show .= "<td>" . $paycardtypename . "</td>";
		$show .= "<td class='center'><a href='" . $value . ".php' >�޸�</a></td>";
	}
	$show .= "</tr>";
}

$t->set_var("theadth", $theadth);
$t->set_var("show", $show);

include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);
$t->pparse("out", "template");
?>