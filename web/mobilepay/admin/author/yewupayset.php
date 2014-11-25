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
	"编号",
	"业务类型",
	"交易规则",
	"操作"
);

for ($i = 0; $i < count($arr_text); $i++) {
	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
$query = "select * from tb_creditcardset";
$arr_creditcset = $db->get_one($query);
if($arr_creditcset['fd_creditcset_whenpayfee']='post'){
	$showname='通付宝刷卡时';
}else{$showname='支金代付款时';}
$arr_cinput['creditcardset'] = "
	还信用卡刷卡金额   <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_slotcardmoney'] . "</font></u>   元/笔，
	商户刷卡  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_dayslotcard'] . "</font></u>   次/月，
	月还款总限额  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_maxmoney'] . "</font></u>   元,
	单卡还款次数  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_monthslotcard'] . "</font></u>   次/月，";
if ($arr_creditcset['fd_creditcset_mode'] == 'fix') {
	$arr_cinput['creditcardset'] .= "固定手续费  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_fee'] . "</font></u>   元/笔。";
} else {
	$arr_cinput['creditcardset'] .= "
	最低费率额  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_minfee'] . "</font></u>   ,
	最高费率额  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_maxfee'] . "</font></u>   ，
	收取费率  <u><font color='#00CC00'>" . $arr_creditcset['fd_creditcset_sqfee'] . "</font></u>   。";
}
$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype = 'tfmg'";
$arr_transfermoneyset = $db->get_one($query);

$arr_cinput['transfermoneyset'] = "
	转账刷卡金额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_slotcardmoney'] . "</font></u>   元/笔，
	转账刷卡次数   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_dayslotcardcount'] . "</font></u>   次/日 ，   
	转账月限额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_datemaxcount'] . "</font></u>   元,
	
	";
if ($arr_transfermoneyset['fd_transfermoneyset_mode'] == 'fix') {
	$arr_cinput['transfermoneyset'] .= "
		固定手续费   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_perfee'] . "</font></u>   元/笔。";
} else {
	$arr_cinput['transfermoneyset'] .= "
		最低费率额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_minperfee'] . "</font></u>   ，
		最高费率额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_maxperfee'] . "</font></u>  ， 
		收取费率  <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_sqperfee'] . "</font></u>   。";
}
$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype = 'suptfmg'";
$arr_transfermoneyset = $db->get_one($query);

$arr_cinput['suptransfermoneyset'] = "
	转账刷卡金额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_slotcardmoney'] . "</font></u>   元/笔，
	转账刷卡次数   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_dayslotcardcount'] . "</font></u>   次/日 ，   
	转账月限额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_datemaxcount'] . "</font></u>   元,
	
	";
if ($arr_transfermoneyset['fd_transfermoneyset_mode'] == 'fix') {
	$arr_cinput['suptransfermoneyset'] .= "
		固定手续费   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_perfee'] . "</font></u>   元/笔。";
} else {
	$arr_cinput['suptransfermoneyset'] .= "
		最低费率额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_minperfee'] . "</font></u>   ，
		最高费率额   <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_maxperfee'] . "</font></u>  ， 
		收取费率  <u><font color='#00CC00'>" . $arr_transfermoneyset['fd_transfermoneyset_sqperfee'] . "</font></u>   。";
}
$query = "select * from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
$arr_couponset = $db->get_one($query);
if($arr_couponset['fd_couponset_payfeedirct']=='post'){$showname='通付宝刷卡时';}else{$showname='资金代付款时';}
$arr_cinput['couponset'] = "
	购买抵用券收取费率   <u><font color='#00CC00'>" . $arr_couponset['fd_couponset_fee'] . "</font></u> ，" .
	"最高手续费限额   <u><font color='#00CC00'>" . $arr_couponset['fd_couponset_maxfee'] . "</font></u> ，".
    "到帐周期   <u><font color='#00CC00'>" . $arr_couponset['fd_arrive_name'] . "</font></u> ，
	费率扣取节点	<u><font color='#00CC00'>" . $showname . "</font></u> 。
	";
$arr_contentno = array (
	"creditcardset",
	"transfermoneyset",
	"suptransfermoneyset",
	"repaymoneyset",
	"couponset"
);
$arr_contentname = array (
	"信用卡还款",
	"普通转账",
	"超级转账",
	"信贷还款",
	"抵用劵"
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
		$show .= "<td>尚没有业务规则！</td>";
		$show .= "<td> </td>";
	} else {
		$show .= "<td>" . $paycardtypename . "</td>";
		$show .= "<td class='center'><a href='" . $value . ".php' >修改</a></td>";
	}
	$show .= "</tr>";
}

$t->set_var("theadth", $theadth);
$t->set_var("show", $show);

include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);
$t->pparse("out", "template");
?>