<?
$thismenucode = "2n602";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php"); //调用生成单据编号文件    
require ("../function/changemoney.php"); //调用应付应付金额文件
require ("../function/chanceaccount.php"); //调用修改帐户金额文件
require ("../function/cashglide.php"); //调用现金流水帐文件
require ("../function/currentaccount.php"); //调用往来对帐单文件

$db  = new DB_test ( );
$db1 = new DB_test ( );
$gourl = "tb_agentpaymoney_sp_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

if (! empty ( $action ) or ! empty ( $end_action ) or ! empty ( $listid )) {
	$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' 
	          and (fd_pymylt_state = 2 or fd_pymylt_state = 2 )";
	$db->query ( $query );
	if ($db->nf ()) {
		echo "<script>alert('该单据已经过帐了，不能再修改，请查证')</script>";
		
		$action = "";
		$end_action = "";
	}
}


//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if ($end_action == "endsave") {
	$todaydate = date ( "Y-m-d" );
	if ($todaydate < $date) {
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$end_action = "";
	}
}

switch ($end_action) {
	case "endsave" :
	
			$query = "update tb_paymoneylist set fd_pymylt_state = 1, fd_pymylt_dealwithman= '$dealwithman' ,fd_pymylt_datetime = now()
                       where fd_pymylt_id = '$listid'";
			$db->query ( $query ); //修改付款单
			require ("../include/alledit.2.php");
			Header ( "Location: $gotourl" );
		
		break;
	
	case "back" : //删除整条单据数据
		//--------------------start----------------------
		$query = "update tb_paymoneylist set fd_pymylt_state = 0, fd_pymylt_datetime = now()
                       where fd_pymylt_id = '$listid'";
		$db->query ( $query ); //修改付款单
		
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	
	default :
		break;
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "paymoneylist", "agentpaymoney_sp.html" );

$count = 0;
if (empty ( $listid )) {
	$date = date ( "Y-m-d" );
	$action = "new";

} else { //如果销售单id好不是为空
	$action = "edit";
	$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' ";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$listno = $db->f ( fd_pymylt_no );
		$accountid = $db->f ( fd_pymylt_accountid );
		$cusid = $db->f ( fd_pymylt_clientid );
		$cusno = $db->f ( fd_pymylt_clientno );
		$cusname = $db->f ( fd_pymylt_clientname );
		$clienttype = $db->f ( fd_pymylt_type );
		$date = $db->f ( fd_pymylt_date );
		$payallmoney = $db->f ( fd_pymylt_money );
		$dealwithman = $db->f ( fd_pymylt_dealwithman );
		$memo_z = $db->f ( fd_pymylt_memo );
		$sklinkman = $db->f ( fd_pymylt_sklinkman );
		$insidecomid = $db->f ( fd_pymylt_insidecompanyid );
		$receiptno = $db->f ( fd_pymylt_receiptno );
		$payapplydetailid = $db->f ( fd_pymylt_payapplydetailid );
		$payapplyno = $db->f ( fd_pymylt_payapplyno );
		$paytype = $db->f ( fd_pymylt_paytype );
		$sqorganid = $db->f ( fd_pymylt_sqorganid );
		$staid = $db->f ( fd_pymylt_staid );
	}
}
$t->set_block ( "paymoneylist", "prolist", "prolists" );
$query = "select fd_agpm_bkntno as bkntno,fd_pymyltdetail_id as agpmid,	fd_agpm_shoucardno as shoucardno,
                 fd_agpm_shoucardbank as shoucardbank,fd_agpm_shoucardman as shoucardman,
                 fd_agpm_shoucardmobile as shoucardmobile,
                 fd_agpm_paymoney as paymoney,fd_agpm_paydate as paydate 
                 from tb_paymoneylistdetail  left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id  order by fd_agpm_no"; 
$db->query($query);
$arr_result = $db->getFiledData('');
//echo var_dump($arr_result);
foreach($arr_result as $val)
{	
$t->set_var($val);
$t->parse("prolists", "prolist", true);	
}
if(empty($arr_result))
{
$t->parse("prolists", "", true);	
}
if (empty ( $listno )) { //显示暂时的单据编号
	$listno = listnumber_view ( "9" );
}



if (empty ( $listid )) { //如果已经暂存，提交跟删除按钮可用，否则不可用
	$tijiao_dis = "disabled";
} else {
	$tijiao_dis = "";
}
$t->set_var ( "tijiao_dis", $tijiao_dis );

//echo $sqorganid;
//require ("ypaylist.php");  --暂时屏蔽掉
$t->set_var ( "tijiao_dis", $tijiao_dis ); //单据id  
$t->set_var ( "sqorganid", $sqorganid ); //单据id 
$t->set_var ( "listid", $listid ); //单据id 
$t->set_var ( "listno", $listno ); //单据编号 
$t->set_var ( "cusno", $cusno ); //客户编号
$t->set_var ( "cusname", $cusname ); //客户名称
$t->set_var ( "cusid", $cusid ); //客户id号
$t->set_var ( "cusno", $cusno ); //客户编号
$t->set_var ( "clienttype", $clienttype ); //客户类型
$t->set_var ( "staid", $staid ); //录单人id
$t->set_var ( "wpaymoney", $wpaymoney ); //录单人id
$t->set_var ( "fppaymoney", $fppaymoney ); //单据id 
//$staname= getstaname($staid);
$t->set_var ( "staname", $staname ); //经手人
$t->set_var ( "memo_z", $memo_z ); //备注
$t->set_var ( "insidecompanyid", $insidecompanyid ); //内部分公司
$t->set_var ( "accountid", $accountid ); //帐户
$t->set_var ( "payallmoney", $payallmoney ); //付款金额
$t->set_var ( "dealwithman", $dealwithman ); //业务员
$t->set_var ( "yfk_show", $yfk_show ); //应付款
$t->set_var ( "sklinkman", $sklinkman ); //业务员
$t->set_var ( "receiptno", $receiptno ); //收据号码
$t->set_var ( "payapplydetailid", $payapplydetailid ); //付款申请明细id
$t->set_var ( "payapplyno", $payapplyno ); //付款申请编号
$t->set_var ( "paytype", $paytype ); //付款类型
$t->set_var ( "count1", $count1 ); //经手人


$t->set_var ( "date", $date ); //年月日 


$t->set_var ( "count1", $count1 ); //子表记录数


$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "paymoneylist" ); # 最后输出页面


//生成选择菜单的函数(所有代码)
function makeallselect($arritem, $hadselected, $arry) {
	$x .= "<select name='accountid' onChange='changeaccount()'>";
	for($i = 0; $i < count ( $arritem ); $i ++) {
		if ($hadselected == $arry [$i]) {
			$x .= "<option selected value='$arry[$i]'>" . $arritem [$i] . "</option>";
		} else {
			$x .= "<option value='$arry[$i]'>" . $arritem [$i] . "</option>";
		}
	}
	$x .= "</select>";
	return $x;
}

?>

