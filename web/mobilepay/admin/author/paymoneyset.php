<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
$db = new DB_test();
$gourl = "tb_paymoneyset_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :
	    $query = "select 1 from tb_paymoneyset where  fd_paymset_authortypeid='$auindustryid' and fd_paymset_mode = '$mode' and fd_paymset_scope = '$scope' ";
	    $db->query($query);
	    if($db->nf())
	    {
	    	$error = "已经添加了额度，不需要重复添加";
	    }else
	    {
		$query = "INSERT INTO tb_paymoneyset(
				   fd_paymset_mode,fd_paymset_no,fd_paymset_authortypeid,fd_paymset_nallmoney, fd_paymset_sallmoney,fd_paymset_scope,fd_paymset_everymoney,fd_paymset_everycounts) VALUES(
				   '$mode','$no','$authortypeid','$nallmoney','$sallmoney','$scope','$everymoney','$everycounts')";
		$db->query($query);
		//echo $query;
		$listid = $db->insert_id();
		require ("../include/alledit.2.php");
		Header("Location: $gotourl");
	    }
	    $action = "";
		break;
	case "edit" :
		$query = "select 1 from tb_paymoneyset where  fd_paymset_authortypeid='$auindustryid' and fd_paymset_mode = '$mode' and fd_paymset_scope = '$scope' and fd_paymset_id <> '$listid'";
	    $db->query($query);
	    if($db->nf())
	    {
	    	$error = "已经重复，请重新修改！";
	    }else
	    {
		$query = "update tb_paymoneyset set fd_paymset_mode='$mode',fd_paymset_no='$no',
						  fd_paymset_authortypeid='$authortypeid',fd_paymset_sallmoney='$sallmoney',
						  fd_paymset_nallmoney='$nallmoney',fd_paymset_scope = '$scope' ,fd_paymset_everymoney = '$everymoney',fd_paymset_everycounts ='$everycounts' where fd_paymset_id='$listid'";
		$db->query($query);
		require ("../include/alledit.2.php");
		Header("Location: $gotourl");
	    }
	    $action = "";
		break;
	case "delete" :
		$query = "delete from tb_paymoneyset where fd_paymset_id='$listid'";
		$db->query($query);
		require ("../include/alledit.2.php");

		Header("Location: $gotourl");
		break;
	default :
		break;
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("author", "paymoneyset.html");

if (!isset ($listid)) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_paymoneyset where fd_paymset_id='$listid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_paymset_id);
		$mode = $db->f(fd_paymset_mode);
		$no = $db->f(fd_paymset_no);
		$authortypeid = $db->f(fd_paymset_authortypeid);
		$sallmoney = $db->f(fd_paymset_sallmoney);
		$nallmoney = $db->f(fd_paymset_nallmoney);
		$everymoney = $db->f(fd_paymset_everymoney);
		$everycounts = $db->f(fd_paymset_everycounts);
		$scope       = $db->f(fd_paymset_scope);
		
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

$query = "select * from tb_authortype ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_authortypeid[] = $db->f(fd_authortype_id);
		$arr_authortype[] = $db->f(fd_authortype_name);
	}
}
$authortypeid = makeselect($arr_authortype, $authortypeid, $arr_authortypeid);

$t->set_var("listid", $listid); //listid
$t->set_var("mode", $mode);
$t->set_var("no", $no);
$t->set_var("authortypeid", $authortypeid);
$t->set_var("sallmoney", $sallmoney);
$t->set_var("nallmoney", $nallmoney);
$t->set_var("everymoney", $everymoney);
$t->set_var("everycounts", $everycounts);
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

