<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
$db = new DB_test();
$gourl = "tb_slotcardmoneyset_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :
		$query = "select 1 from tb_slotcardmoneyset where  fd_scdmset_auindustryid='$auindustryid' and fd_scdmset_mode = '$mode' and fd_scdmset_scope = '$scope' ";
		$db->query($query);
		if ($db->nf()) {
			$error = "已经添加了额度，不需要重复添加";
		} else {
			$query = "INSERT INTO tb_slotcardmoneyset(
						   fd_scdmset_mode,fd_scdmset_no,fd_scdmset_auindustryid,fd_scdmset_nallmoney, fd_scdmset_sallmoney,fd_scdmset_scope,fd_scdmset_everymoney,fd_scdmset_everycounts,fd_scdmset_neverymoney,fd_scdmset_severymoney," .
						   		"fd_scdmset_name,fd_scdmset_datetime,fd_scdmset_bkcount,fd_scdmset_changebkcount) VALUES(
						   '$mode','$no','$auindustryid','$nallmoney','$sallmoney','$scope','$everymoney','$everycounts'," .
						   		"'$neverymoney','$severymoney'," .
						   		"'$name',now(),'$bkcount','$changebkcount')";
			$db->query($query);
			//echo $query;
			$listid = $db->insert_id();
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "edit" :
		$query = "select 1 from tb_slotcardmoneyset where  fd_scdmset_auindustryid='$auindustryid' and fd_scdmset_mode = '$mode' and fd_scdmset_scope = '$scope' and fd_scdmset_id <> '$listid'";
		$db->query($query);
		if ($db->nf()) {
			$error = "已经重复，请重新修改！";
		} else {
			$query = "update tb_slotcardmoneyset set fd_scdmset_mode='$mode',fd_scdmset_no='$no',
									  fd_scdmset_auindustryid='$auindustryid',fd_scdmset_sallmoney='$sallmoney',
									  fd_scdmset_nallmoney='$nallmoney',fd_scdmset_scope = '$scope',
									   fd_scdmset_everymoney = '$everymoney',fd_scdmset_name = '$name',
									   fd_scdmset_severymoney = '$severymoney' , fd_scdmset_neverymoney = '$neverymoney',
									   fd_scdmset_everycounts ='$everycounts', fd_scdmset_bkcount ='$bkcount',
									    fd_scdmset_changebkcount ='$changebkcount',
									  fd_scdmset_datetime = now()  where fd_scdmset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "delete":
		$query="select * from tb_author where fd_author_bkcardscdmsetid='$listid' or fd_author_slotscdmsetid='$listid'";
		$db->query($query);
		if($db->nf())
		{
			$error='该套餐已绑定商户,请先取消绑定!';
			}else{
			$query = "delete from tb_slotcardmoneyset where fd_scdmset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		break;
	default :
		break;
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("author", "slotcardmoneyset.html");

if (!isset ($listid)) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_slotcardmoneyset where fd_scdmset_id='$listid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_scdmset_id);
		$mode = $db->f(fd_scdmset_mode);
		$no = $db->f(fd_scdmset_no);
		$auindustryid = $db->f(fd_scdmset_auindustryid);
		$sallmoney = $db->f(fd_scdmset_sallmoney);   // 每月审批总额
		$nallmoney = $db->f(fd_scdmset_nallmoney);   // 每月正常总额
		$everymoney = $db->f(fd_scdmset_everymoney); // 每天正常总额
		$everycounts = $db->f(fd_scdmset_everycounts);// 每天正常刷卡次数
		$scope = $db->f(fd_scdmset_scope);            // 刷卡类型 信用卡或者借记卡   
		$severymoney = $db->f(fd_scdmset_severymoney); // 每天审批总额
		$neverymoney = $db->f(fd_scdmset_neverymoney); // 每天正常总额
		$name    = $db->f(fd_scdmset_name);
		$bkcount    = $db->f(fd_scdmset_bkcount);
		$changebkcount    = $db->f(fd_scdmset_changebkcount);
		
		
		
	}

}
//
$arr_modeno = array (
	"date",
	"month"
);
$arr_mode = array (
	"日套餐",
	"月套餐"
);
$mode = makeselect($arr_mode, $mode, $arr_modeno);

$arr_scopeno = array (
	"creditcard",
	"bankcard"
);
$arr_scopename = array (
	"信用卡",
	"储蓄卡"
);
$scope = makeselect($arr_scopename, $scope, $arr_scopeno);

$query = "select * from tb_authorindustry ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_auindustryid[] = $db->f(fd_auindustry_id);
		$arr_auindustry[] = $db->f(fd_auindustry_name);
	}
}
$auindustryid = makeselect($arr_auindustry, $auindustryid, $arr_auindustryid);

$t->set_var("listid", $listid); //listid
$t->set_var("mode", $mode);
$t->set_var("no", $no);
$t->set_var("name", $name);
$t->set_var("auindustryid", $auindustryid);
$t->set_var("sallmoney", $sallmoney);
$t->set_var("nallmoney", $nallmoney);
$t->set_var("everymoney", $everymoney);
$t->set_var("everycounts", $everycounts);
$t->set_var("severymoney", $severymoney);
$t->set_var("neverymoney", $neverymoney);
$t->set_var("bkcount", $bkcount);
$t->set_var("changebkcount", $changebkcount);
$t->set_var("scope", $scope);
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址    
$t->set_var("fckeditor", $fckeditor);
$t->set_var("error", $error);
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "author"); # 最后输出页面
?>

